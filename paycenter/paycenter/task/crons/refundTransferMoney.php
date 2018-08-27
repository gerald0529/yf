<?php
if (!defined('ROOT_PATH'))
{
    if (is_file('../../../paycenter/configs/config.ini.php'))
    {
        require_once '../../../paycenter/configs/config.ini.php';
    }
    else
    {
        die('请先运行index.php,生成应用程序框架结构！');
    }
}


Yf_Log::log(__FILE__, Yf_Log::INFO, 'crontab');

$file_name_row = pathinfo(__FILE__);
$crontab_file = $file_name_row['basename'];

//检测转账时间，过期退款
$transferMoneyModel = new Transfer_MoneyModel;

$not_accepted_rows = $transferMoneyModel->getByWhere([
    'status'=> Transfer_MoneyModel::STATUS_NOT_RECEIVED
]);

$now_time = time();
$one_day_second = 24 * 60 * 60;

//过滤出过期红包
$expired_rows = array_filter($not_accepted_rows, function($row) use ($now_time, $one_day_second) {
    return $row['send_time'] + $one_day_second >= $now_time
        ? true
        : false;
});

if (! $expired_rows) {
    return false;
}

/**
 * 进行退款
 * 处理流程
 * transfer_money改变状态已过期
 * pay_user_resource用户余额从冻结金额还原
 * pay_consume_record改变状态交易取消
 */

$notice_url = 'http://imbuilder.local.yuanfeng021.com?ctl=ImApi&met=pushMsg&account_system=admin'; //推送接口
$notice_msg = '你发起的%s已过期，已退回'; //通知信息

foreach ($expired_rows as $k=> $transfer_data) {
    $flag = $transferMoneyModel->refundTransferMoney($transfer_data); //此处应记录失败日志

    $expired_rows[$k]['is_success'] = $flag !== false
        ? true
        : false;
}

/**
 * @param $notice_url
 * @param $notice_msg
 * @param $expired_rows
 * 推送退款信息
 */
function startPush($notice_url, $notice_msg, $expired_rows)
{
    $success_rows = array_filter($expired_rows, function($row) {
        return $row['is_success']
            ? true
            : false;
    });

    $user_ids = array_column($success_rows, 'from_user');
    $userInfoModel = new User_InfoModel;
    $user_rows = $userInfoModel->getInfo($user_ids);

    foreach ($success_rows as $data) {
        $user_mobile = $user_rows[$data['from_to']]['user_mobile'];
        $msg = sprintf($notice_msg, Transfer_MoneyModel::$types[$data['type']]);
        $get_url = $notice_url."&receiver=$user_mobile&msg_content=$msg";
        pushRefundNotice($get_url);
    }
}

function pushRefundNotice($notice_url)
{
    $curl = curl_init($notice_url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);//设置等待时间
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//FALSE 禁止 cURL 验证对等证书（peer's certificate）。要验证的交换证书可以在 CURLOPT_CAINFO 选项中设置，或在 CURLOPT_CAPATH中设置证书目录

    for ($i=0; $i < 5; $i++) {
        $res = curl_exec($curl);
        $err = curl_error($curl);
        if(empty($err)) {
            break;
        }
    }
}

startPush($notice_url, $notice_msg, $expired_rows); //开始推送退款

return true;
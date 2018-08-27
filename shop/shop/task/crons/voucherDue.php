<?php
if (!defined('ROOT_PATH'))
{
    if (is_file('../../../shop/configs/config.ini.php'))
    {
        require_once '../../../shop/configs/config.ini.php';
    }
    else
    {
        die('请先运行index.php,生成应用程序框架结构！');
    }

    //不会重复包含, 否则会死循环: web调用不到此处, 通过crontab调用
    $Base_CronModel = new Base_CronModel();
    $rows = $Base_CronModel->checkTask(); //并非指执行自己, 将所有需要执行的都执行掉, 如果自己达到执行条件,也不执行.

    //终止执行下面内容, 否则会执行两次
    return ;
}


Yf_Log::log(__FILE__, Yf_Log::INFO, 'crontab');

$file_name_row = pathinfo(__FILE__);
$crontab_file = $file_name_row['basename'];

fb($crontab_file);


//执行任务

//代金券即将到期通知

$voucherBaseModel  = new Voucher_BaseModel(); //代金券

$cond_row_voucher['voucher_state'] = Voucher_BaseModel::UNUSED;//代金券尚未使用
$cond_row_voucher['voucher_end_date:<'] = date("Y-m-d H:i:s", strtotime("+2 days"));//代金券两天后到期
$voucher_rows = $voucherBaseModel->getByWhere($cond_row_voucher);//查找出尚未使用，即将过期的代金券

if($voucher_rows)
{
    $message = new MessageModel();
    foreach($voucher_rows as $key=>$value)
    {
        $message->sendMessage('Imminent expiration reminder', $value['voucher_owner_id'], '亲爱的会员！', $order_id = NULL, $shop_name = NULL, 0, MessageModel::USER_MESSAGE, $value['voucher_end_date']);
    }
}

return  true;
?>
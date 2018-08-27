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

if(!function_exists('refund')){
    function refund($unionOrderIds)
    {
        $key = Yf_Registry::get('paycenter_api_key');
        $url = Yf_Registry::get('paycenter_api_url');
        $paycenter_app_id = Yf_Registry::get('paycenter_app_id');

        $formvars = array();
        $formvars['unionOrderIds'] = $unionOrderIds;
        $formvars['app_id'] = $paycenter_app_id;

        return get_url_with_encrypt($key, sprintf('%s?ctl=Api_Refund&met=pintuan&typ=json', $url), $formvars);
    }
}

if(!function_exists('refundSuccess')){

    function refundSuccess($successOrderIds)
    {
        //支付宝、微信退款成功，在这里处理商家业务逻辑
        if (empty($successOrderIds)) {
            return;
        }
        $orderBaseModel = new Order_BaseModel;
        //注意: 这个方法是朱羽婷写的
        foreach($successOrderIds as $successOrderId) {
            $orderBaseModel->autoRefund($successOrderId);
        }
    }
}

/******************************判断拼团是否失败，更新团状态S********************************************/

/* *
 * 判断拼团是否失败，更新团状态
 */
$pintuanModel = new PinTuan_Base;

$disabledPintuanList = $pintuanModel->getByWhere([
    'end_time:<='=> date('Y-m-d H:i:s')
]);
if ($disabledPintuanList) {
    $pintuanIds = array_keys($disabledPintuanList);

    $detailModel = new PinTuan_Detail;
    $detailList = $detailModel->getByWhere([
        'pintuan_id:IN'=> $pintuanIds
    ]);
    $detailIds = array_keys($detailList);

    $markModel = new PinTuan_Mark;
    $markList = $markModel->getByWhere([ //获取成团失败列表
        'detail_id:IN'=> $detailIds,
        'status'=> PinTuan_Mark::STATUS_WAIT
    ]);

    if ($markList) { //更新团状态失败
        $markIds = array_keys($markList);
        $markModel->editInfo($markIds, [
            'status'=> PinTuan_Mark::STATUS_FAILURE
        ]);
    }
}
/******************************判断拼团是否失败，更新团状态E********************************************/


/******************************获取拼团失败且支付的订单进行批量退款S*************************************/
/* *
 * 获取拼团失败且支付的订单进行批量退款
 * 注意，此脚本依赖于cancelPintuan.php，注意执行顺序
 */

$markModel = new PinTuan_Mark;
$failureMarkList = $markModel->getByWhere([
    'status'=> PinTuan_Mark::STATUS_FAILURE
]);
$failureMarkIds = implode(',', array_keys($failureMarkList));

$table_prefix = TABEL_PREFIX;
$sql = <<<EOF
SELECT
  temp.order_id,
  order_base.payment_other_number
FROM
  `{$table_prefix}pintuan_temp` AS temp
LEFT JOIN
  `{$table_prefix}order_base` AS order_base
ON
temp.order_id = order_base.order_id
WHERE
  order_base.order_status = 2
AND temp.mark_id IN ($failureMarkIds)
EOF;
if($failureMarkIds){ 
	$orderModel = new Order_BaseModel;
	$orderList = $orderModel->sql->getAll($sql); 
}

/**
 * 退款成功修改表状态
 * 可能存在退款失败订单
 */
ini_set('display_errors', 1);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

if ($orderList) {
    $unionOrderIds = array_column($orderList, 'payment_other_number');
    $result = refund($unionOrderIds);

    if ($result['status'] == 200) {

        $successOrderIds = [];
        $resultOrderList = $result['data'];
        foreach($resultOrderList as $refundOrder) {
            if ($refundOrder['status'] == 200) {
                $successOrderIds[] = $refundOrder['order_id'];
            } else {
                Yf_Log::log(var_export($refundOrder), Yf_Log::INFO, 'pintuan');
            }
        }
        refundSuccess($successOrderIds);
    }
}
/******************************获取拼团失败且支付的订单进行批量退款E*************************************/
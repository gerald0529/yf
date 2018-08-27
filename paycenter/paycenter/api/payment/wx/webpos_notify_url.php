<?php
/**
 * 此页面专门处理POS端业务
 * 逻辑很简单，改变订单支付状态为已支付
 */
require_once '../../../configs/config.ini.php';

$Payment_WxNativeModel = PaymentModel::create('wx_native');
$verify_result = $Payment_WxNativeModel->verifyNotify();

//计算得出通知验证结果
if ($verify_result && $notify_row = $Payment_WxNativeModel->getNotifyData())
{
    Yf_Log::log(var_export($notify_row, true), Yf_Log::INFO, 'pay_webpos_wx_notify');

    $unionOrderModel = new Union_OrderModel();
    $union_order_id = $notify_row['order_id']; //订单id
    $flag = $unionOrderModel->editUnionOrder($union_order_id, ['order_state_id'=> Union_OrderModel::PAYED]);

    Yf_Log::log("执行结果：订单号=>$union_order_id,执行状态=>$flag", Yf_Log::INFO, 'pay_webpos_wx_notify');

    echo $flag ? 'SUCCESS' : 'FAIL';
} else {
    echo 'FAIL';
}

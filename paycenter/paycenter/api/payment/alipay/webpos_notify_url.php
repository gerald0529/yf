<?php
/**
 * 此页面专门处理POS端业务
 * 逻辑很简单，改变订单支付状态为已支付
 */
require_once '../../../configs/config.ini.php';

$Payment_AlipayWapModel = PaymentModel::create('alipay');
$verify_result          = $Payment_AlipayWapModel->verifyNotify();


Yf_Log::log(var_export($verify_result,true), Yf_Log::INFO, 'pay_webpos_alipay_notify');
//通过验证结果
if ($verify_result)
{
    $notify_param = $_POST;
    $union_order_id = $notify_param['out_trade_no']; //订单id

    $unionOrderModel = new Union_OrderModel();
    $flag = $unionOrderModel->editUnionOrder($union_order_id, ['order_state_id'=> Union_OrderModel::PAYED]);

    Yf_Log::log("执行结果：订单号=>$union_order_id,执行状态=>$flag", Yf_Log::INFO, 'pay_webpos_alipay_notify');
    echo $flag ? 'success' : 'fail';
} else {
    echo 'fail';
}
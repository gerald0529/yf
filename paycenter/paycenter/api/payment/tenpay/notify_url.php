<?php

//---------------------------------------------------------
//财付通即时到帐支付后台回调示例，商户按照此文档进行开发即可
//---------------------------------------------------------
require_once '../../../configs/config.ini.php';

include_once LIB_PATH . '/Api/tenpay/lib/classes/ResponseHandler.class.php';
include_once LIB_PATH . '/Api/tenpay/lib/classes/RequestHandler.class.php';
include_once LIB_PATH . '/Api/tenpay/lib/classes/client/ClientResponseHandler.class.php';
include_once LIB_PATH . '/Api/tenpay/lib/classes/client/TenpayHttpClient.class.php';
include_once LIB_PATH . '/Api/tenpay/lib/classes/function.php';


log_result("进入后台回调页面");
Yf_Log::log("r=" . json_encode($_REQUEST), Yf_Log::INFO, 'pay_tenpay_notify');
Yf_Log::log("p=" . json_encode($_POST), Yf_Log::INFO, 'pay_tenpay_notify');
Yf_Log::log("g=" . json_encode($_GET), Yf_Log::INFO, 'pay_tenpay_notify');


$Payment_TenpayWapModel = PaymentModel::create('tenpay');
$verify_result          = $Payment_TenpayWapModel->verifyNotify();

//计算得出通知验证结果
if ($verify_result)
{
	log_result("即时到帐后台回调成功");
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
	//插入充值记录
	$Consume_DepositModel = new Consume_DepositModel();
	$rs = $Consume_DepositModel->processDeposit($verify_result);

	if ($rs)
	{
		//处理一步回调-通知商城更新订单状态
		$Consume_DepositModel->notifyShop($verify_result['order_id']);

		echo "SUCCESS";        //请不要修改或删除
		Yf_Log::log('Process-SUCCESS', Yf_Log::INFO, 'pay_tenpay_notify');
	}
	else
	{
		echo "FAIL";
		Yf_Log::log('Process-FAIL', Yf_Log::ERROR, 'pay_tenpay_notify_error');
		Yf_Log::log('Process-FAIL', Yf_Log::ERROR, 'pay_tenpay_notify');
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else
{
	log_result("即时到帐后台回调失败");
	//验证失败
	echo "FAIL";
	Yf_Log::log($error_msg, Yf_Log::ERROR, 'pay_tenpay_notify_error');
	Yf_Log::log($error_msg, Yf_Log::ERROR, 'pay_tenpay_notify');

}

 

?>
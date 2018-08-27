<?php

//---------------------------------------------------------
//财付通即时到帐支付页面回调示例，商户按照此文档进行开发即可
//---------------------------------------------------------
require_once '../../../configs/config.ini.php';

include_once LIB_PATH . '/Api/tenpay/lib/classes/ResponseHandler.class.php';
include_once LIB_PATH . '/Api/tenpay/lib/classes/function.php';
include_once LIB_PATH . '/Api/tenpay/lib/tenpay_config.php';

log_result("进入前台回调页面");

$Payment_TenpayModel = PaymentModel::create('tenpay');
$verify_result          = $Payment_TenpayModel->verifyReturn();

Yf_Log::log('$verify_result=' . $verify_result, Yf_Log::INFO, 'pay_tenpay_return');

//计算得出通知验证结果
if ($verify_result)
{
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
		//插入充值记录
		$Consume_DepositModel = new Consume_DepositModel();
		$rs = $Consume_DepositModel->processDeposit($verify_result);

		if ($rs)
		{
			//处理一步回调-通知商城更新订单状态
			$Consume_DepositModel->notifyShop($verify_result['order_id']);

			echo "SUCCESS";        //请不要修改或删除
			Yf_Log::log('Process-SUCCESS', Yf_Log::INFO, 'pay_tenpay_return');
		}
		else
		{
			echo "FAIL";
			Yf_Log::log('Process-FAIL', Yf_Log::ERROR, 'pay_tenpay_return_error');
			Yf_Log::log('Process-FAIL', Yf_Log::ERROR, 'pay_tenpay_return');
		}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else
{
	//验证失败
	echo "FAIL";
	Yf_Log::log($error_msg, Yf_Log::ERROR, 'pay_tenpay_return_error');
	Yf_Log::log($error_msg, Yf_Log::ERROR, 'pay_tenpay_return');

}

?>
<?php
require_once '../../../configs/config.ini.php';
spl_autoload_register("__autoload");
$paypalModel = new Payment_Paypal;
$paypalResponse = $paypalModel->getReturnUrl();

if (isset($paypalResponse['PAYMENTINFO_0_ACK']) && $paypalResponse['PAYMENTINFO_0_ACK'] === 'Success'&&$paypalResponse['PAYMENTINFO_0_PAYMENTSTATUS']==='Completed')
{
	    Session_start();
        $params=$_SESSION['params'];
		//查找此支付单的交易类型
		$Union_OrderModel = new Union_OrderModel();
		$data = $Union_OrderModel->getOne($params['transactionId']);

		$trade_type = Trade_TypeModel::$trade_type_row[$data['trade_type_id']];

		//购物
		if($trade_type == 'shopping')
		{
            //修改订单表中的各种状态
            $Consume_DepositModel = new Consume_DepositModel();
            $rs = $Consume_DepositModel->notifyShop($data['union_order_id'], $data['buyer_id']);
		}

		if($trade_type == 'deposit')
		{
			//Yf_Log::log(var_export($notify_row,true), Yf_Log::INFO, '__deposit_noti');
			//修改充值表的状态
			$Consume_DepositModel = new Consume_DepositModel();
			$deposit = $Consume_DepositModel->getOne($data['union_order_id']);
			
			if($deposit['deposit_trade_status']==2)
			{
				$rs = 1;
			}else{
				$rs = $Consume_DepositModel->notifyDeposit($data['union_order_id'],$data['buyer_id'],$data['payment_channel_id'],$notify_row['trade_no']);
			}
		}

		if ($rs)
		{
			//重定向浏览器
			if($trade_type == 'shopping')
			{
				$app_id = $data['app_id'];

				//查找回调地址
				$User_AppModel = new User_AppModel();
				$user_app = $User_AppModel->getOne($app_id);
				$return_app_url = $user_app['app_url'] . '?ctl=Buyer_Order&met=physical';
			}

			if($trade_type == 'deposit')
			{
				//var_dump(111);
				$return_app_url = Yf_Registry::get('paycenter_api_url')."?ctl=Info&met=index";
			}
			header("Location: " . $return_app_url);
		}
		else
		{
			echo "fail1";
		}

}



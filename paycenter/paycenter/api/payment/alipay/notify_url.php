<?php

require_once '../../../configs/config.ini.php';
require_once realpath(__DIR__ . '/../../../../libraries/Api/alipayMobile/pagepay/service') . '/AlipayTradeService.php';

spl_autoload_register('__autoload'); //ali SDK覆盖系统__autoload方法
$alipayModel = PaymentModel::create('alipay');
$config = $alipayModel->getConfig();


$arr = $_POST;

$alipaySevice = new AlipayTradeService($config);
$alipaySevice->writeLog(var_export($_POST,true));
$result = $alipaySevice->check($arr);
Yf_Log::log(var_export($arr, true), Yf_Log::INFO, 'alipay_verification');
Yf_Log::log("验签结果：$result($arr[trade_no])", Yf_Log::INFO, 'alipay_verification');
//计算得出通知验证结果
if ($result)
{
    if($arr['trade_status'] != 'TRADE_SUCCESS'){
        echo "FAIL";    exit;
    }
    $order_model = new Union_OrderModel();
    $order_model->editUnionOrder($arr['out_trade_no'],array('notify_data'=>$arr));
    Yf_Log::log(var_export($arr, true), Yf_Log::INFO, 'alipay_success');

    $notify_row = $alipayModel->getNotifyData($arr);
	if ($notify_row)
	{
		//查找此支付单的交易类型
		$Union_OrderModel = new Union_OrderModel();
		$data = $Union_OrderModel->getOne($notify_row['order_id']);

        //webpos 支付
        if ($data['app_id'] == 207) {
            $edit_row = array();
            $edit_row['order_state_id'] = Order_StateModel::ORDER_PAYED;
            $edit_row['pay_time'] = date('Y-m-d H:i:s');
            $result = $Union_OrderModel->editUnionOrder($notify_row['order_id'], $edit_row);
            if ($result) {
                echo "SUCCESS";        //请不要修改或删除
            } else {
                echo "FAIL";        //请不要修改或删除
            }
            exit;
        }

		$trade_type = Trade_TypeModel::$trade_type_row[$data['trade_type_id']];

		if($trade_type == 'deposit')
		{
			Yf_Log::log(var_export($notify_row,true), Yf_Log::INFO, '__deposit_noti');
			//修改充值表的状态
			$Consume_DepositModel = new Consume_DepositModel();
			$deposit = $Consume_DepositModel->getOne($notify_row['order_id']);
			if($deposit['deposit_trade_status']==2)
			{
				$rs = 1;
			}else{
				$rs = $Consume_DepositModel->notifyDeposit($notify_row['order_id'],$notify_row['buyer_id'],$notify_row['payment_channel_id'],$notify_row['trade_no']);
			}
		}
		else
		{
			//修改订单表中的各种状态
			$Consume_DepositModel = new Consume_DepositModel();
			$rs = $Consume_DepositModel->notifyShop($notify_row['order_id'], $notify_row['buyer_id']);
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
				$return_app_url = $user_app['app_url'];
			}

			if($trade_type == 'deposit')
			{
				$return_app_url = Yf_Registry::get('paycenter_api_url')."?ctl=Info&met=index";
			}

			//确保重定向后，后续代码不会被执行
			echo "success";
		}
		else
		{
			echo "fail1";
		}
	}
	else
	{
		echo "fail2";
	}
}
else
{
	//验证失败
	echo "fail3";
}
?>
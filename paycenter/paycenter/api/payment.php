<?php
/* *
 * 全部要用异步的，不要用return。 
 */
require_once '../../configs/config.ini.php';
 

function bbc_payment_notify($notify_row,$named = 'abc'){
		if ($notify_row)
		{
				//插入充值记录
				$Consume_DepositModel = new Consume_DepositModel();
		  
				//查找此支付单的交易类型
				$trade_type = Trade_TypeModel::$trade_type_row[$notify_row['trade_type_id']];

				fb($trade_type);
				//购物
				if($trade_type == 'shopping')
				{
					//处理一步回调-通知商城更新订单状态
					//修改订单表中的各种状态
					$Consume_DepositModel = new Consume_DepositModel();
					$rs                 = $Consume_DepositModel->notifyShop($notify_row['order_id'],$notify_row['buyer_id']);
				}

				if($trade_type == 'deposit')
				{
					//修改充值表的状态
					$Consume_DepositModel = new Consume_DepositModel();
					$rs = $Consume_DepositModel->notifyDeposit($notify_row['order_id'],$notify_row['buyer_id'],$notify_row['payment_channel_id']);
				} 
				echo "SUCCESS";        //请不要修改或删除
				Yf_Log::log('Process-SUCCESS', Yf_Log::INFO, $named.'_success');
			 
		}
		else
		{
			echo "FAIL";
			Yf_Log::log('Process-FAIL', Yf_Log::ERROR, $named.'_error');
			Yf_Log::log('Process-FAIL', Yf_Log::ERROR, $named.'_notify');
		}
}
	
	

 
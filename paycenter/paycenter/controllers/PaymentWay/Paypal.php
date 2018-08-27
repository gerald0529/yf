<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Api接口, 让App等调用
 *
 * 
 * @todo
 */
class PaymentWay_Paypal  extends PaymentWay_Base
{
	/**
	 * 使用paypal
	 *
	 */
	public function paypal()
	{
		$trade_id = request_string('trade_id');

		//如果订单号为合并订单号，则获取合并订单号的信息
		$Union_OrderModel = new Union_OrderModel();
		$trade_row        = $Union_OrderModel->getOne($trade_id);

		//判断订单状态是否为等待付款状态
		if($trade_row['order_state_id'] == Order_StateModel::ORDER_WAIT_PAY)
		{
			$pay_flag = false;
			$pay_user_id = 0;
			//判断当前用户是否是下单者，并且订单状态是否是待付款状态
			if($trade_row['buyer_id'] == Perm::$userId)
			{
				$pay_flag = true;
				$pay_user_id = $trade_row['buyer_id'];
			}
			else
			{
				//判断当前用户是否是下单者的主管账户
				$key      = Yf_Registry::get('shop_api_key');
				$url         = Yf_Registry::get('shop_api_url');
				$shop_app_id = Yf_Registry::get('shop_app_id');
				$formvars = array();

				$formvars['app_id']					= $shop_app_id;
				$formvars['user_id']     = Perm::$userId;
				$formvars['sub_user_id'] = $trade_row['buyer_id'];

				$sub_user = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Info&met=checkSubUser&typ=json',$url), $formvars);
				if(!empty($sub_user['data']) && $sub_user['status'] == 200)
				{
					$pay_flag = true;
					$pay_user_id = Perm::$userId;
				}
			}
//ignore  给webpos使用
			if($pay_flag || $_GET['ignore']=='abc' )
			{
				if ($trade_row)
				{
					$Payment = PaymentModel::create('paypal');
					$Payment->pay($trade_row);
				}
				else
				{
					echo"<script>alert('支付失败,请重新支付');history.go(-1);</script>";
				}
			}
			else
			{
				echo"<script>alert('支付失败，请重新支付');history.go(-1);</script>";
			}
		}
		else
		{
			echo"<script>alert('支付失败，请重新支付');history.go(-1);</script>";
		}

	}

}
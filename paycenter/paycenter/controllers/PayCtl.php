<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}
/**
 * 支付入口
 * @author     Cbin
 */
class PayCtl extends Controller
{
	//支付分发在PaymentWay目录中
	public function __call($name,$arg){
		$cls =  "PaymentWay_".ucfirst($name); 
		$cls = new $cls; 
		$met = $name; 
		$cls->$met();
		exit;
	}
	/**
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}

	/**
	 * 微信二维码支付
	 * 构造 url
	 * @param product_id产品ID
	 */
	public function structWXurl()
	{
		// 第一步 参数过滤
		$product_id  = trim($_REQUEST['product_id']);
		if (!$product_id || is_int($product_id))
		{
			$this->data->setError('参数错误');
			$this->data->printJSON();
			die;
		}

		// 第二步  调用url生成类
		$pw = new Payment_WxQrcodeModel();
		$url = $pw->url($product_id);
		include $this->view->getView();
	}

	/**
	 * 微信二维码支付
	 * 生成二维码
	 */
	public function structWXcode()
	{
		require_once MOD_PATH.'/Payment/phpqrcode/phpqrcode.php';
		$url = urldecode($_REQUEST["data"]);
		QRcode::png($url);
	}

	/**
	 * 微信二维码支付
	 * 微信回调
	 */
	public function WXnotify()
	{
		// 确定支付
		$pw = new Payment_WxQrcodeModel();
		$pw->notify();

		// 支付金额写入数据库
		// code
	}

	/**
	 * 使用余额支付
	 *
	 */
	public function money()
	{
		$trade_id = request_string('trade_id');

		//如果订单号为合并订单号，则获取合并订单号的信息
		$Union_OrderModel = new Union_OrderModel();

		//开启事物
		$Consume_DepositModel = new Consume_DepositModel();

		$uorder = $Union_OrderModel->getOne($trade_id);
		$data = array();

		//判断订单状态是否为等待付款状态
		if($uorder['order_state_id'] == Order_StateModel::ORDER_WAIT_PAY)
		{
			$pay_flag = false;
			$pay_user_id = 0;
			//判断当前用户是否是下单者，并且订单状态是否是待付款状态
			if($uorder['buyer_id'] == Perm::$userId)
			{
				$pay_flag = true;
				$pay_user_id = $uorder['buyer_id'];
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
				$formvars['sub_user_id'] = $uorder['buyer_id'];

				$sub_user = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Info&met=checkSubUser&typ=json',$url), $formvars);
				if(!empty($sub_user['data']) && $sub_user['status'] == 200)
				{
					$pay_flag = true;
					$pay_user_id = Perm::$userId;
				}
			}

			if($pay_flag)
			{
				//修改订单表中的各种状态
				$flag = $Consume_DepositModel->notifyShop($trade_id,$pay_user_id);
				if ($flag['status'] == 200)
				{
					//查找回调地址
					$User_AppModel = new User_AppModel();
					$user_app = $User_AppModel->getOne($uorder['app_id']);
					$return_app_url = $user_app['app_url'];

					$data['return_app_url'] = $return_app_url;
					$data['order_id'] = $uorder['inorder'];
					$msg    = 'success';
					$status = 200;
				}
				else
				{
					$msg    = _('failure3');
					$status = 250;
				}
			}
			else
			{
				$msg    = _('failure2');
				$status = 250;
			}
		}
		else
		{
			$msg    = _('failure1');
			$status = 250;
		}
		
		$this->data->addBody(-140, $data, $msg, $status);
	}



	public function checkAvailableMoney(){
		$trade_id = request_string('trade_id');
		$Union_OrderModel = new Union_OrderModel();


		$uorder = $Union_OrderModel->getOne($trade_id);


		$key      = Yf_Registry::get('shop_api_key');
		$url         = Yf_Registry::get('shop_api_url');
		$shop_app_id = Yf_Registry::get('shop_app_id');
		$formvars = array();

		$formvars['app_id']					= $shop_app_id;
		$formvars['order_id'] = $uorder['inorder'];


		$order_base = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Trade_Order&met=getOrderBase&typ=json',$url), $formvars);
		$order_base = array_values($order_base['data']);


		//如果是分销订单
		$capital_scarcity = false;
		if($order_base){
			//查询分销商账户余额
			$User_Resource = new User_ResourceModel();
			$user_resource = $User_Resource->getOne($order_base[0]['buyer_user_id']);

			if($user_resource['user_money'] <($order_base[0]['order_payment_amount']) + $order_base[0]['order_shipping_fee']){
				$capital_scarcity = true;
			}

		}

		if(!$capital_scarcity){

			$status = 200;
			$msg = __('success');

		}else{
			$msg = __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, array(), $msg, $status);


	}


	//主管账号待支付
	public function subpay()
	{
		$trade_id = request_string('trade_id');

		//如果订单号为合并订单号，则获取合并订单号的信息
		$Union_OrderModel = new Union_OrderModel();

		$uorder = $Union_OrderModel->getOne($trade_id);
		$inorder = $uorder['inorder'];

		$uorder_id = $trade_id;
		$order_id = explode(",",$inorder);
		array_filter($order_id);
		$data = array();
		$data['order_id'] = implode(',',$order_id);

		$act = request_string('act');
		//用于判断订单类型，order_g_type = physical实物订单，virtual虚拟订单

		$order_g_type = request_string('order_g_type') ? request_string('order_g_type') : 'physical';


		//获取需要支付的订单信息
		$Union_OrderModel = new Union_OrderModel();
		$uorder_base = $Union_OrderModel->getOne($uorder);


		$flag = false;
		//判断当前用户是否是下单者，并且订单状态是否是待付款状态
		if($uorder['buyer_id'] == Perm::$userId && $uorder['order_state_id'] == Order_StateModel::ORDER_WAIT_PAY)
		{
			$key      = Yf_Registry::get('shop_api_key');
			$url         = Yf_Registry::get('shop_api_url');
			$shop_app_id = Yf_Registry::get('shop_app_id');
			$formvars = array();

			$formvars['app_id']					= $shop_app_id;
			$formvars['sub_user_id']     = Perm::$userId;

			$sub_user = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Info&met=getSubUser&typ=json',$url), $formvars);

			$rs_row = array();
			//获取当前用户的主管账号
			if($sub_user['status'] == 200 && $sub_user['data']['count'] > 0)
			{
				//将该笔订单的交易明细表中的支付者修改为主管账号
				$Consume_RecordModel = new Consume_RecordModel();
				$cond_row = array();
				$cond_row['order_id:IN'] = $order_id;
				$cond_row['user_type'] = 2;
				$consume = $Consume_RecordModel->getByWhere($cond_row);
				$consume_id = array_values(array_column($consume,'consume_record_id'));

				$edit_row = array();
				$edit_row['user_id'] = $sub_user['data']['sub']['user_id'];
				$edit_row['user_nickname'] = $sub_user['data']['sub']['user_account'];
				fb($edit_row);
				$edit_flag = $Consume_RecordModel->editRecord($consume_id,$edit_row);
				check_rs($edit_flag,$rs_row);
				//修改这笔订单的支付人
				$order_edit_row = array();
				$order_edit_row['pay_user_id'] = $sub_user['data']['sub']['user_id'];
				$Consume_TradeModel = new Consume_TradeModel();
				$flag = $Consume_TradeModel->editTrade($order_id,$order_edit_row);
				check_rs($flag, $rs_row);

				if(is_ok($rs_row))
				{
					$Consume_TradeModel = new Consume_TradeModel();
					$consume_record = $Consume_TradeModel->getOne($order_id);
					$app_id = $consume_record['app_id'];

					$User_AppModel = new User_AppModel();
					$app_row = $User_AppModel->getOne($app_id);

					$return_app_url = $app_row['app_url'];

					$data['return_app_url'] = $return_app_url;

					$key = $app_row['app_key'];
					$url = $app_row['app_url'];
					$shop_app_id = $app_id;

					$formvars = array();
					$formvars = $_POST;
					$formvars['app_id'] = $shop_app_id;
					$formvars['order_id'] = $order_id;
					$formvars['order_sub_user'] = $sub_user['data']['sub']['user_id'];

					fb($formvars);

					//远程修改订单表中的order_sub_pay = 1:主管账号支付
					$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Trade_Order&met=editOrderSubPay&typ=json', $url), $formvars);

					if($rs['status'] == 200)
					{
						$flag = true;
					}


				}

			}
		}

		if($flag)
		{
			$msg    = _('success');
			$status = 200;


//			/****************************************************************************************************/
			$user_id_row = $sub_user['data']['sub']['user_id'];
			try{
				//订单付款成功后进行极光推送
				require_once "Jpush/JPush.php";
				$type=array('type'=>'1');
				$app_key = '67c48d5035a1f01bc8c09a88';
				$master_secret = '805f959b10b0d13d63a231fd';
				$alert="您作为主管账号在".date("Y-m-d H:i:s")."帮助".$sub_user['data']['sub']['user_account']."支付订单成功，支出-".$edit_row['record_money'];
				$client = new JPush($app_key, $master_secret);
				$result=$client->push()
					->setPlatform(array('ios', 'android'))
					->addAlias($user_id_row)
					->addIosNotification($alert,'', null, null, null, $type)
					->addAndroidNotification($alert,null,null,$type)
					->setOptions(100000, 3600, null, false)
					->send();
			}
			catch(Exception $e){

			}
			/****************************************************************************************************/

		}else
		{
			$msg    = _('failure');
			$status = 250;
		}





		$this->data->addBody(-140, $data, $msg, $status);
	}


	/**
	 * 使用支付宝支付
	 *
	 */
	public function alipay()
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
					$Payment = PaymentModel::create('alipay');
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




	
	
	/**
	 * 使用银联在线支付
	 *
	 */
	public function unionpay()
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

			if($pay_flag)
			{
				if ($trade_row)
				{
					$Payment = PaymentModel::create('unionpay');
					$Payment->pay($trade_row);
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
		else
		{
			echo"<script>alert('支付失败，请重新支付');history.go(-1);</script>";
		}

	}

	/**
	 * 使用微信支付
	 *
	 */
	public function wx_native()
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

			if($pay_flag || $_GET['ignore']=='abc')
			{
				if ($trade_row)
				{
                    if (Yf_Utils_Device::isMobile() && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false) {
                        $trade_row['trade_type'] = "MWEB";
                        $trade_row['trade_id'] = $trade_id;
                    } else {
                        $trade_row['trade_type'] = $_REQUEST['trade_type'];
                    }
                    $Payment = PaymentModel::create('wx_native');
                    $Payment->pay($trade_row);
				}
				else
				{
					echo"<script>alert('支付失败，请重新支付');history.go(-1);</script>";
				}
			}
			else
			{
				echo"<script>alert('支付失败，请重新支付 !');history.go(-1);</script>";
			}
		}
		else
		{
			echo"<script>alert('支付失败，请重新支付!!!');history.go(-1);</script>";
		}

	}

	/**
	 * @param $uorder_data
	 * @return boolean
	 * 检查订单是否为付款状态
	 */
	private function checkOrderStatus ($uorder_data)
	{
		if ($uorder_data['order_state_id'] == Order_StateModel::ORDER_WAIT_PAY) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * 暂时手机端无联合付款，手机端支付为全款支付
	 * 手机端支付->修改pay_union_order状态
	 * union_online_pay_amount = trade_payment_amount
	 * union_cards_pay_amount = union_cards_return_amount = union_money_pay_amount = union_money_return_amount = 0;
	 */

	/**
	 * PHP服务端SDK生成APP支付订单信息 （支付宝）
	 */
	public function createAliOrder()
	{
		$uorder_id = request_string('uorder_id');

		//检查参数
		if (empty($uorder_id)) {
			return $this->data->addBody(-140, [], _('无效访问参数'), 250);
		}

		$unionOrderModel = new Union_OrderModel();
		$uorder_data = $unionOrderModel->getOne($uorder_id);

		$unionOrderModel->editUnionOrder($uorder_id, ['union_online_pay_amount'=> $uorder_data['trade_payment_amount'],
														'union_cards_pay_amount'=> 0,
														'union_cards_return_amount'=> 0,
														'union_money_pay_amount'=> 0,
														'union_money_return_amount'=> 0
													]);

		//检查订单是否为付款状态
		if (!$this->checkOrderStatus($uorder_data)) {
			return $this->data->addBody(-140, [], _('订单状态不为待付款状态'), 250);
		}

        $paymentChannelModel = new Payment_ChannelModel();
        $config_row = $paymentChannelModel->getChannelConfig('alipay');
		$paymentAlipay = new Payment_Alipay($config_row);
        $response = $paymentAlipay->getPayString($uorder_data);
		$this->data->addBody(-140, ['orderString'=> $response], 'success', 200);
	}

	/**
	 * 微信统一下单，返回app （生成预付订单）
	 */
	public function createWXOrder()
	{
		$trade_type = request_string('trade_type');
		$uorder_id = request_string('uorder_id');
		if (empty($uorder_id)) {
			return $this->data->addBody(-140, [], _('无效访问参数'), 250);
		}

		$unionOrderModel = new Union_OrderModel();

		//恢复ConsumeTrade表金额记录，之前数据可能有误
		$uorder_data = $unionOrderModel->getOne($uorder_id);

		$urow = $unionOrderModel->getByWhere(array('inorder'=>$uorder_data['inorder']));
		$uorder_id_row = array_column($urow,'union_order_id');

		//订单支付的总金额
		$payment_amount = $uorder_data['trade_payment_amount'];

		$edit_union_order_row = ['union_online_pay_amount'=> $payment_amount,
			'union_cards_pay_amount'=> 0,
			'union_money_pay_amount'=> 0,
            'payment_channel_id'=>$trade_type
		];

		$flag = $unionOrderModel->editUnionOrder($uorder_id_row, $edit_union_order_row);

		if ($flag === false) {
			return $this->data->addBody(-140, [], _('交易订单记录初始化失败'), 250);
		}

		//单据详情
		$order_row = array_merge($uorder_data, $edit_union_order_row);

		if($trade_type == 'APP') //原生BBC
		{
			$payment_model = PaymentModel::create('app_wx_native');
		}
		elseif($trade_type == 'APPH5')//买家版App
		{
            $payment_model = PaymentModel::create('app_h5_wx_native');
		}elseif($trade_type == 'APP_H5')//卖家版App
		{
            $payment_model = PaymentModel::create('seller_app_h5_wx_native');
		}elseif($trade_type == 'WXAPP'){ //小程序
            $openid= request_string('openid');
            $body = $order_row['trade_title'];
            $total_fee = floatval($order_row['union_online_pay_amount']*100);

            $payment_model = PaymentModel::create('wxapp',array(),$openid,$body,$total_fee,$uorder_id);
        }elseif($trade_type == 'IM_WXAPP'){ //IMApp
            $payment_model = PaymentModel::create('im_wxapp');
        }else{
            //PC扫码
			$payment_model = PaymentModel::create('wx_native');
		}

		$result = $payment_model->pay($order_row, true);

		$this->data->addBody(-140, ['orderString'=> $result, 'APPID'=> APPID_DEF, 'MCHID'=> MCHID_DEF,'timeStamp'=>(string)time()], 'success', 200);
	}


    /*修改小程序订单状态*/
    public function order_status(){
        $order_id = request_string('order_id');
        $buyer_id = request_string('buyer_id');
        //处理一步回调-通知商城更新订单状态
        //修改订单表中的各种状态

        $Consume_DepositModel = new Consume_DepositModel();
        $rs                 = $Consume_DepositModel->notifyShop($order_id,$buyer_id);

        $this->data->addBody(-140, $rs, 'success', 200);
    }


	//收款码支付
	public function qr_pay()
	{
		$check_row = array();
		//支付方式
		$pay_type = request_string('pay_type');
		//付款金额
		$pay_money = request_float('pay_money');
		//收款店铺id
		$shopid = request_int('shopid');
		//付款用户id
		$user_id = Perm::$userId;
		//付款用户name
		$user_name = Perm::$row['user_account'];

		//获取当前用户的手机号
		$User_InfoModel = new User_InfoModel();
		$user_info = $User_InfoModel->getOne($user_id);

		//获取店铺的信息
		$key = Yf_Registry::get('shop_api_key');
		$url = Yf_Registry::get('shop_api_url');
		$shop_app_id = Yf_Registry::get('shop_app_id');

		$formvars = array();
		$formvars = $_POST;
		$formvars['app_id'] = $shopid;
		$formvars['shop_id'] = $shopid;

		$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Shop_Info&met=getShopInfoByShopId&typ=json', $url), $formvars);

		//开店用户id
		$shop_uid = $rs['data']['user_id'];
		$shop_uname = $rs['data']['user_name'];

		$Consume_TradeModel = new Consume_TradeModel();

		//1.添加订单记录
		$fix = sprintf('%s-%s', Yf_Registry::get('paycenter_app_id'), date('YmdHis'));
		$order_id = sprintf('%s-%s-%s-%s', 'QP', $shop_uid, $user_id, $fix);

		$da = array();
		$da['consume_trade_id'] = $order_id; //交易订单id
		$da['order_id'] = $order_id;      //商户订单id
		$da['buy_id']  = $user_id;        //买家id
		$da['pay_user_id'] = $user_id;   //付款人id
		$da['buyer_name'] = $user_name;    //付款人名称
		$da['seller_id'] = $shop_uid;     //商家用户id
		$da['seller_name'] = $shop_uname;   //商家用户名称
		$da['payment_channel_id'] = Payment_ChannelModel::MONEY;//支付渠道
		$da['trade_type'] = 1;    //交易类型 1：担保交易 2：直接交易
		$da['order_payment_amount'] = $pay_money;  //总付款额度
		$da['trade_payment_amount'] = $pay_money;  //实付金额，在线支付金额
		$da['trade_remark'] = 0;          //备注
		$da['trade_create_time'] = date('Y-m-d H:i:s');     //创建时间
		$da['trade_pay_time'] = date('Y-m-d H:i:s');        //支付时间
		$da['trade_title'] = '收款码付款';       //标题
		$da['from_app_id'] = Yf_Registry::get('paycenter_app_id');  //订单来源
		$da['order_commission_fee'] = 0;  //佣金
		$da['notify_data'] = '';  //支付回调参数信息

		if($pay_type == 'money')
		{
			$da['order_state_id'] = Union_OrderModel::PAYED;//订单状态id
			$da['trade_payment_money'] = $pay_money;   //余额支付金额
			$da['trade_payment_online'] = 0;  //在线支付金额
		}else
		{
			$da['order_state_id'] = Union_OrderModel::WAIT_PAY;//订单状态id
			$da['trade_payment_money'] = 0;   //余额支付金额
			$da['trade_payment_online'] = $pay_money;  //在线支付金额
		}
		$flag3 =  $Consume_TradeModel->addConsumeTrade($da);
		check_rs($flag3['flag'],$check_row);

		//增加商家冻结资金的金额
		$User_ResourceModel = new User_ResourceModel();
		$user_resource_row = array();
		$user_resource_row['user_money'] = $pay_money;
		$flag2 = $User_ResourceModel->editResource($shop_uid,$user_resource_row,true);
		check_rs($flag2,$check_row);

		switch ($pay_type)
		{
			case 'money':
				//2.根据付款金额修改用户的资金信息
				$user_resource_row = array();
				$user_resource_row['user_money'] = $pay_money*(-1);
				$flag1 = $User_ResourceModel->editResource($user_id,$user_resource_row,true);
				check_rs($flag1,$check_row);

				//短信通知用户消费情况提醒
				$contents = "您有一笔".$pay_money."元的余额支出，可去支付中心查看余额。";
				$result = Sms::send($user_info['user_mobile'], $contents);
				break;
			case 'alipay':
				$this->qr_alipay($flag3['uorder']);
				break;
			case 'wx_native':
				$this->qr_wx_native($flag3['uorder']);
				break;
			default:
				# code...
				break;

		}
		$flag = is_ok($check_row);
		if ($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$Consume_TradeModel->sql->rollBackDb();
			$m      = $Consume_TradeModel->msg->getMessages();
			$msg    = $m ? $m[0] : 'failure';
			$status = 250;
		}
		$this->data->addBody(-140, array('order_id' => $order_id), $msg, $status);
	}

	public function qr_alipay($trade_id)
	{
		//如果订单号为合并订单号，则获取合并订单号的信息
		$Union_OrderModel = new Union_OrderModel();
		$trade_row        = $Union_OrderModel->getOne($trade_id);

		if ($trade_row)
		{
			$Payment = PaymentModel::create('alipay');
			$Payment->pay($trade_row);
		}

	}

	public function qr_wx_native($trade_id)
	{
		//如果订单号为合并订单号，则获取合并订单号的信息
		$Union_OrderModel = new Union_OrderModel();
		$trade_row        = $Union_OrderModel->getOne($trade_id);

		if ($trade_row)
		{
			$trade_row['trade_type'] = $_REQUEST['trade_type'];
			$Payment = PaymentModel::create('wx_native');
			$Payment->pay($trade_row);
		}

	}

    public function test()
    {
        $Consume_TradeModel = new Consume_TradeModel();
//远程改变订单状态
        //根据订单来源，修改订单状态
        $uorder_id = 123456789;
        $order_id = ['DD-11037-200-102-20180421141414-0001'];
        $consume_record = $Consume_TradeModel->getOne($order_id);
        $app_id = $consume_record['app_id'];

        $User_AppModel = new User_AppModel();
        $app_row = $User_AppModel->getOne($app_id);


        $key = $app_row['app_key'];
        $url = $app_row['app_url'];
        $shop_app_id = $app_id;

        $formvars = array();
        $formvars = $_POST;
        $formvars['app_id'] = $shop_app_id;
        $formvars['order_id'] = $order_id;
        $formvars['uorder_id'] = $uorder_id;
        if($consume_record['payment_channel_id'] == Payment_ChannelModel::BAITIAO){
            $formvars['payment_channel_code'] = 'baitiao';
        }else{
            $formvars['payment_channel_code'] = '';
        }

        $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Trade_Order&met=editOrderRowSatus&typ=json', $url), $formvars);
    }

}
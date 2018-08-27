<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_IndexCtl extends Yf_AppController
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}

	//显示支付中心首页
	public function index()
	{
		include $this->view->getView();
	}

	//显示支付页面
	public function pay()
	{
		include $this->view->getView();
	}

	//支付宝支付
    public function alipay()
    {
		$trade_id = request_string('trade_id');

		//判断trade_id 是普通订单号还是合并订单号
		if(substr($trade_id, 0,1) == "U")
		{
			//如果订单号为合并订单号，则获取合并订单号的信息
			$Union_orderModel = new Union_orderModel();
			$trade_row = $Union_orderModel->getOne($trade_id);
		}
		else
		{
			$Consume_TradeModel = new Consume_TradeModel();
			$trade_row = $Consume_TradeModel->getOne($trade_id);
		}

		if ($trade_row)
		{
            $Payment = PaymentModel::create('alipay');
			$Payment->pay($trade_row);
		}
		else
		{

		}
    }

	public function wx()
	{
		$Consume_TradeModel = new Consume_TradeModel();
		$trade_row = $Consume_TradeModel->getOne('11321322');

		if ($trade_row)
		{
            $Payment = PaymentModel::create('wx_native');
			$Payment->pay($trade_row);
		}
		else
		{

		}
	}


	public function tenpay()
	{
		$trade_id = request_string('trade_id');

		//判断trade_id 是普通订单号还是合并订单号
		if(substr($trade_id, 0,1) == "U")
		{
			//如果订单号为合并订单号，则获取合并订单号的信息
			$Union_orderModel = new Union_orderModel();
			$trade_row = $Union_orderModel->getOne($trade_id);
		}
		else
		{
			$Consume_TradeModel = new Consume_TradeModel();
			$trade_row = $Consume_TradeModel->getOne($trade_id);
		}

		$trade_row['trade_mode'] = '1';  //交易模式 1即时到账（默认） 2中介担保 3后台选择（买家进支付中心列表选择�?

		if ($trade_row)
		{
            $Payment = PaymentModel::create('tenpay');
			$Payment->pay($trade_row);
		}
		else
		{

		}
	}


	public function tenpayWap()
	{
		$trade_id = request_string('trade_id');
		$Consume_TradeModel = new Consume_TradeModel();
		$trade_row = $Consume_TradeModel->getOne('11321322');

		if ($trade_row)
		{
			$Payment = PaymentModel::create('tenpay_wap');
			$Payment->pay($trade_row);
		}
		else
		{

		}
	}

    public function main()
    {
		//include $this->view->getView();
    }

    //添加合并支付订单信息pay_union_order
    public function addUnionOrder()
    {
    	//生成合并支付订单号
		$uorder  = "U".date("Ymdhis",time()).rand(100,999);  //18位
		$inorder = request_string('inorder');
		$inorder = substr($inorder, 0, -1);
		$trade_title = request_string('trade_title');
		$uprice  = request_float('uprice');
		$buyer   = request_int('buyer');

		$add_row = array(
						'union_order_id' => $uorder,
						'order_id' => $uorder,
						'inorder'     => $inorder,
						'trade_payment_amount'  => $uprice,
						'create_time' => time(),
						'buyer_id'       => $buyer,
						'order_state_id'	  => Union_orderModel::WAIT_PAY,
						 );

		$Union_orderModel = new Union_orderModel();
		$flag = $Union_orderModel->addUnionOrder($add_row);

		if ($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}

		$data = array('uorder' => $uorder);
		$this->data->addBody(-140, $data, $msg, $status);
    }

	//添加交易订单信息
	public function addConsumeTrade()
	{
		$consume_trade_id = request_string('consume_trade_id');
		$order_id = request_string('order_id');
		$buy_id = request_int('buy_id');
		$seller_id = request_int('seller_id');
		$order_state_id = request_int('order_state_id');
		$order_payment_amount = request_float('order_payment_amount');
		$trade_remark = request_string('trade_remark');
		$trade_create_time = request_string('trade_create_time');

		$add_row = array();
		$add_row['consume_trade_id'] = $consume_trade_id;
		$add_row['order_id'] = $order_id;
		$add_row['buy_id'] = $buy_id;
		$add_row['seller_id'] = $seller_id;
		$add_row['order_state_id'] = $order_state_id;
		$add_row['order_payment_amount'] = $order_payment_amount;
		$add_row['trade_type_id'] = Trade_TypeModel::SHOPPING;
		$add_row['trade_remark'] = $trade_remark;
		$add_row['trade_create_time'] = $trade_create_time;
		$add_row['trade_amount'] = $order_payment_amount;
		$add_row['trade_payment_amount'] = $order_payment_amount;

		$Consume_TradeModel = new Consume_TradeModel();
		$flag = $Consume_TradeModel->addTrade($add_row);

		if ($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

	//交易明细(待修改)
	public function getConsumeRecord()
	{
		$page = request_int('page',1);
		$rows = request_int('rows',20);
		$type = request_string('type');   //交易分类  1收款方  2付款方
		$status = request_string('status'); //交易状态 1未付款 2等待发货 3未确认发货 4成功 5失败

		$user_id = Perm::$userId;
		$user_id = $user_id ? $user_id : request_int('user_id');
		//$user_id = 10001;
		$Consume_RecordModel = new Consume_RecordModel();
		$row = $Consume_RecordModel->getRecordList($user_id,$type,$status,$page,$rows);

		$this->data->addBody(-140, $row);
	}

	//提现记录   转账记录(1)
	public function getConsumeRecordByType()
	{
		$page = request_int('page',1);
		$rows = request_int('rows',20);
		//$user_id = Perm::$userId;
		$user_id = 1;
			//const SHOPPING = 1;  //购物
			//const TRANSFER = 2;  //转账
			//const DEPOSIT  = 3; //充值
			//const WITHDRAW = 4;  //提现
			//const RECEIPT  = 5;  //收款
			//const PAY		= 6;   //付款
		$type = request_string('type');

		$Consume_RecordModel = new Consume_RecordModel();
		$row = $Consume_RecordModel->getRecordListByType($user_id,$type,$page,$rows);
		fb($row);
		$this->data->addBody(-140, $row);
	}

	//获取用户资源信息
	public function getUserResourceInfo()
	{
		$user_id = Perm::$userId;
		$user_id = $user_id ? $user_id : request_int('user_id');
		//$user_id = 1;

		$User_ResourceModel = new User_ResourceModel();

		$data = $User_ResourceModel->getResource($user_id);

		$data = array_values($data);

		if ($data)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}


		$this->data->addBody(-140, $data, $msg, $status);

	}

	//获取用户信息（1）
	public function getUserInfo()
	{
		//$user_id = Perm::$userId;
		$user_id = 1;

		$User_InfoModel = new User_InfoModel();

		$data = $User_InfoModel->getInfo($user_id);

		if ($data)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}


		$this->data->addBody(-140, $data, $msg, $status);

	}

	//修改用户信息(1)
	public function editUserInfo()
	{
		//$user_id = Perm::$userId;
		$user_id = 1;

		$user_info_row = array();
		//真实姓名
		$user_realname = request_string('user_realname');
		if($user_realname)
		{
			$user_info_row['user_realname'] = $user_realname;
		}

		//用户昵称
		$user_nickname = request_string('user_nickname');
		if($user_nickname)
		{
			$user_info_row['user_nickname'] = $user_nickname;
		}

		//手机号码
		$user_mobile = request_int('uer_mobile');
		if($user_mobile)
		{
			$user_info_row['user_mobile'] = $user_mobile;
		}

		//用户邮箱
		$user_email = request_string('user_email');
		if($user_email)
		{
			$user_info_row['user_email'] = $user_email;
		}


		$User_InfoModel = new User_InfoModel();

		$data = $User_InfoModel->editInfo($user_id,$user_info_row);

		if ($data)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}

		$this->data->addBody(-140, $user_info_row, $msg, $status);

	}

	//修改用户支付密码(1)
	public function  editUserPayPassword()
	{
		//$user_id = Perm::$userId;
		$user_id = 1;

		$user_base_row = array();

		$old_password = request_string('old_password');
		$set_password = request_string('set_password');

		$User_BaseModel =new User_BaseModel();
		$user_base = current($User_BaseModel->getBase($user_id));

		if(md5($old_password) == $user_base['user_pay_passwd'])
		{
			$user_base_row['user_pay_passwd'] = md5($set_password);
			$flag = $User_BaseModel->editBase($user_id,$user_base_row);
		}
		else
		{
			$flag = false;
		}

		if ($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	//提交提现申请(1)
	public function addWithdraw()
	{
		//$user_id = Perm::$userId;
		//$user_name = Perm::$userName;
		$user_id = 1;

		$bank = request_string('bank');  //收款方 银行
		$cardno = request_string('cardno'); //收款方 银行卡号
		$cardname = request_string('cardname'); //收款方 开户人姓名

		$amount = request_int('amount')?request_int('amount')*1:0;  //提现金额

		$supporttime = request_int('supporttime');  //到账时间 1-2小时内到账  2-次日24点 3-次日48点

		$reason = request_string('reason')?request_string('reason'):'提现';  //提款说明

		$paypasswd = request_string('paypasswd');  //支付密码

		//获取用户信息
		$User_BaseModel =new User_BaseModel();
		$user_base = current($User_BaseModel->getBase($user_id));

		$User_ResourceModel = new User_ResourceModel();
		$user_resource = current($User_ResourceModel->getResource($user_id));

		$Consume_RecordModel = new Consume_RecordModel();
		$Consume_WithdrawModel = new Consume_WithdrawModel();

		if($user_base['user_pay_passwd'] != MD5($paypasswd))
		{
			$flag = false;
			$res = '支付密码错误';
		}
		else
		{
			$Service_FeeModel = new Service_FeeModel();
			$fee = current($Service_FeeModel->getFeeById($supporttime));

			$num = 0;
			$price = $amount*($fee['fee_rates']/100);

			if($price > 0)
			{
				if($price <= $fee['fee_min'])
				{
					$num = $fee['fee_min'];
				}
				elseif($price >= $fee['fee_max'])
				{
					$num = $fee['fee_max'];
				}
				else
				{
					$num = $price;
				}
			}

			if($amount + $num <= $user_resource['user_money'])
			{
				$m = $amount + $num;

				//减少费用
				$resource_edit_row['user_money'] = $user_resource['user_money'] - $m;
				$resource_edit_row['user_money_frozen'] = $m;
				$User_ResourceModel->editResource($user_id,$resource_edit_row);


				//插入交易明细表
				$flow_id = date("Ymdhis").rand(0,9);
				$add_time = time();
				$record_row = array(
					'order_id'=>$flow_id,
					'user_id'=>$user_id,
					//'user_nickname'=>$user_name,
					'record_money'=>-$m,
					'record_date'=>date("Y-m-d"),
					'record_year'=>date("Y"),
					'record_month'=>date("m"),
					'record_day'=>date("d"),
					'record_title'=>$reason,
					'record_time'=>date('Y-m-d h:i:s'),
					'trade_type_id'=>'4',
					'user_type'=>'2',
				);
				$record_id = $Consume_RecordModel->addRecord($record_row,true);

				//插入提现申请表
				$widthdraw_row = array(
					'pay_uid' => $user_id,
					'orderid'=> $flow_id,
					'amount'  => $amount,
					'add_time'=> $add_time,
					'con'      => $reason,
					'bank'    => $bank,
					'cardno'  => $cardno,
					'cardname'=> $cardname,
					'supportTime'=> $supporttime,
					'fee'     => $num,
				);
				$flag = $Consume_WithdrawModel->addWithdraw($widthdraw_row);

			}
			else
			{
				$flag = false;
				$res = '余款不足';
			}
		}

		if($flag)
		{
			$msg    = 'success';
			$status = 200;
			$date = $widthdraw_row;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
			$date[] = $res;
		}
		$this->data->addBody(-140, $date, $msg, $status);

	}

	//转账(1)
	public function addTransfer()
	{
		$user_id = Perm::$userId;
		//$user_name = Perm::$userName;
		$user_id = $user_id ? $user_id :  request_int('user_id');

		$date = array();

		$requirer = request_string('requirer');  //收款人
		$amount    = request_int('amount');		//付款金额
		$reason   = request_string('reason','转账');  //付款说明
		$paypasswd = request_string('paypasswd');  //支付密码

		//确认支付密码
		$User_BaseModel =new User_BaseModel();
		$user_base = current($User_BaseModel->getBase($user_id));

		$User_ResourceModel = new User_ResourceModel();
		$user_resource = current($User_ResourceModel->getResource($user_id));

		$Consume_RecordModel = new Consume_RecordModel();

		if($user_base['user_pay_passwd'] != MD5($paypasswd))
		{
			$flag = false;
			$date[] = '支付密码错误';
		}
		else
		{
			if($requirer && is_numeric($amount))
			{
				if($amount <= $user_resource['user_money'])
				{
					//获取收款人的支付id
					$requirer_id = current($User_BaseModel->getBaseIdByAccount($requirer));
					if($requirer_id)
					{
						$requirer_resource = current($User_ResourceModel->getResource($requirer_id));

						$time = time();
						$flow_id = time();

						//插入付款方的交易记录
						$record_row1 = array(
							'order_id'=>$flow_id,
							'user_id'=>$user_id,
							'record_money'=>-$amount,
							'record_date'=>date("Y-m-d"),
							'record_year'=>date("Y"),
							'record_month'=>date("m"),
							'record_day'=>date("d"),
							'record_title'=>$reason,
							'record_time'=>date('Y-m-d h:i:s'),
							'trade_type_id'=>'2',
							'user_type'=>'2',
						);
						$Consume_RecordModel->addRecord($record_row1,true);

						//插入收款方的交易记录
						$record_row2 = array(
							'order_id'=>$flow_id,
							'user_id'=>$requirer_id,
							'record_money'=>$amount,
							'record_date'=>date("Y-m-d"),
							'record_year'=>date("Y"),
							'record_month'=>date("m"),
							'record_day'=>date("d"),
							'record_title'=>$reason,
							'record_time'=>date('Y-m-d h:i:s'),
							'trade_type_id'=>'2',
							'user_type'=>'1',
						);
						$Consume_RecordModel->addRecord($record_row2,true);

						//修改付款方的金额
						$user_resource_row['user_money'] = $user_resource['user_money'] - $amount;
						$flag1 = $User_ResourceModel->editResource($user_id,$user_resource_row);

						if($flag1)
						{
							//修改收款方的金额
							$requirer_resource_row['user_money'] = $requirer_resource['user_money'] + $amount;
							$flag2 = $User_ResourceModel->editResource($requirer_id,$requirer_resource_row);

							if($flag2)
							{
								$flag = true;
							}
							else
							{
								//返回付款方的钱
								$user_resource_row['user_money'] = $user_resource['user_money'] + $amount;
								$User_ResourceModel->editResource($user_id,$user_resource_row);

								$flag = false;
							}
						}
						else
						{
							$flag = false;
						}
					}
					else
					{
						$flag = false;
						$date[] = '用户不存在';
					}
				}
				else
				{
					$flag = false;
				}
			}
			else
			{
				$flag = false;
			}
		}

		if($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		$this->data->addBody(-140, $date, $msg, $status);
	}

	//获取订单信息（1）
	public function getOrderInfo()
	{
		$order_id = request_string('order_id');

		$Consume_TradeModel = new Consume_TradeModel();
		$date = $Consume_TradeModel->getConsumeTradeByOid($order_id);

		if($date)
		{
			$msg    = 'success';
			$status = 250;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		$this->data->addBody(-140, $date, $msg, $status);

	}

	//提现详情(1)
	public function getWithdrawInfo()
	{
		$order_id = request_string('order_id');

		$Consume_RecordModel = new Consume_RecordModel();
		$Consume_WithdrawModel = new Consume_WithdrawModel();

		$record_row = current($Consume_RecordModel->getRecordByOid($order_id));

		$widthraw_row = current($Consume_WithdrawModel->getWithdrawByOid($order_id));

		$data['record'] = $record_row;
		$data['widthraw'] = $widthraw_row;

		if($data)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		$this->data->addBody(-140, $data, $msg, $status);

	}

	//申请退款操作
	Public function editOrderState()
	{
		$order_id = request_string('order_id');
		$user_id = Perm::$userId;
		$user_id = $user_id ? $user_id : request_int('user_id');

	}

}
?>
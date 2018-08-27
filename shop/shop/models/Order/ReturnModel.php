<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Order_ReturnModel extends Order_Return
{

	const RETURN_WAIT_PASS      = 1;
	const RETURN_SELLER_PASS    = 2;
	const RETURN_SELLER_UNPASS  = 3;
	const RETURN_SELLER_GOODS   = 4;
	const RETURN_PLAT_PASS      = 5;
	const RETURN_PLAT_UNPASS    = 6;
	const RETURN_TYPE_ORDER     = 1;  //退款
	const RETURN_TYPE_GOODS     = 2;	//退货
	const RETURN_TYPE_VIRTUAL   = 3; //虚拟商品退款
	const RETURN_GOODS_ISRETURN = 0;
	const RETURN_GOODS_RETURN   = 1;

	const BEHALF_DELIVER_SHOP = 1; //分销代发货（分销订单DD）
	const BEHALF_DELIVER_DIST = 2; //分销代发货（供应订单SP）

	public static $state = array(
		'1' => 'wait_pass',
		'2' => 'seller_pass',
		'3' => 'seller_unpass',
		'4' => 'seller_goods',
		'5' => 'plat_pass',
	);

	public $return_state;
	public $return_type;

	public function __construct()
	{
		parent::__construct();
		$this->return_state = array(
			'1' => __("等待卖家审核"),
			'2' => __("卖家审核通过"),
			'3' => __("卖家审核未通过"),
			'4' => __("等待平台审核"),
			'5' => __("退款/货完成"),
		);
		$this->return_type  = array(
			'1' => __("退款"),
			'2' => __("退货"),
			'3' => __("虚拟订单退款")
		);
	}

	/**
	 * 读取分页列表
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getReturnList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);

		foreach ($data['items'] as $k => $v)
		{
			if($v['return_shop_handle'] == 3)
			{
				$data['items'][$k]['return_state_text'] = @$this->return_state[$v['return_state']].'('.@$this->return_state[$v['return_shop_handle']].')';
			}
			else
			{
				$data['items'][$k]['return_state_text'] = @$this->return_state[$v['return_state']];
			}

			$data['items'][$k]['return_type_text']  = @$this->return_type[$v['return_type']];
		}

		return $data;
	}

	public function getReturnExcel($cond_row = array(), $order_row = array())
	{
		$data = $this->getByWhere($cond_row, $order_row);

		foreach ($data as $k => $v)
		{
			$data[$k]['order_number'] = " " . $v['order_number'] . " ";
			$data[$k]['return_code']  = " " . $v['return_code'] . " ";
		}

		return array_values($data);
	}

	public function getReturn($cond_row = array(), $order_row = array())
	{
		$data = $this->getOneByWhere($cond_row, $order_row);

		$data['return_state_etext'] = self::$state[$data['return_state']];
		$data['return_platform_state_text']  = @$this->return_state[$data['return_state']];
		$data['return_shop_state_text']  = @$this->return_state[$data['return_shop_handle']];
		if($data['return_shop_handle'] == 3)
		{
			$data['return_state_text'] = @$this->return_state[$data['return_shop_handle']];
		}
		else
		{
			$data['return_state_text'] = @$this->return_state[$data['return_state']];
		}

		return $data;
	}

	public function getReturnBase($id)
	{
		$data                       = $this->getOne($id);
		$data['return_state_etext'] = self::$state[$data['return_state']];
		$data['return_platform_state_text']  = @$this->return_state[$data['return_state']];
		$data['return_shop_state_text']  = @$this->return_state[$data['return_shop_handle']];
		if($data['return_shop_handle'] == 3)
		{
			$data['return_state_text'] = @$this->return_state[$data['return_shop_handle']];
		}
		else
		{
			$data['return_state_text'] = @$this->return_state[$data['return_state']];
		}

		return $data;
	}

	public function settleReturn($cond_row = array())
	{
		//退款金额
		$return_amount = 0;
		//退款佣金
		$commission_return_amount = 0;

		$data = $this->getByWhere($cond_row);
		
		$res = array(
			'return_amount' => array_sum(array_column($data,'return_cash')),
			'commission_return_amount' => array_sum(array_column($data,'return_commision_fee')),
			'redpacket_return_amount' => array_sum(array_column($data,'return_rpt_cash'))
		);
		return $res;
	}

	public function getSubQuantity($cond_row)
	{
		return $this->getNum($cond_row);
	}

	//商家同意退款/退货
	public function SellerAgreeReturn($return_detail = array())
	{
		$orderReturnModel       = new Order_ReturnModel();
		$Order_BaseModel = new Order_BaseModel();
		$message = new MessageModel();

		//查找该笔退单的订单信息
		$order_base = $Order_BaseModel->getOne($return_detail['order_number']);

		//判断该笔订单是否已经收获，如果没有收货的话，不扣除卖家资金。已确认收货则扣除卖家资金
		if($order_base['order_status'] == Order_StateModel::ORDER_FINISH)
		{//已经确认收货
			$order_finish = false;

			//获取商户的资金信息
			$key                 = Yf_Registry::get('shop_api_key');
			$formvars            = array();
			$user_id             = $order_base['seller_user_id'];
			$formvars['user_id'] = $user_id;
			$formvars['app_id'] = Yf_Registry::get('shop_app_id');

			$money_row = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Info&met=getUserResourceInfo&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
			$user_money = 0;
			$user_money_frozen = 0;

			if ($money_row['status'] == '200')
			{
				$money = $money_row['data'];

				$user_money        = $money['user_money'];
				$user_money_frozen = $money['user_money_frozen'];
			}

			//需要退还的金额
			$shop_return_amount = $return_detail['return_cash'] - $return_detail['return_commision_fee'];

			//获取商家最新的结算结束日期
			$Order_SettlementModel = new Order_SettlementModel();
			$settlement_last_info = $Order_SettlementModel->getLastSettlementByShopid($order_base['shop_id'], $return_detail['order_is_virtual']);

			if($settlement_last_info)
			{
				$settlement_unixtime = $settlement_last_info['os_end_date'] ;
			}
			else
			{
				$settlement_unixtime = '';
			}

			$settlement_unixtime = strtotime($settlement_unixtime);
			$order_finish_time = $order_base['order_finished_time'];
			$order_finish_unixtime = strtotime($order_finish_time);

			//判断结算时间和订单完成时间，用以决定是从商家的账户中退款，还是商家的冻结资金中退款
			if($settlement_unixtime >= $order_finish_unixtime )
			{
				//结算时间大于订单完成时间。需要扣除卖家的现金账户
				$money = $user_money;
				$pay_type = 'cash';
			}
			else
			{
				//结算时间小于订单完成时间。需要扣除卖家的冻结资金,如果冻结资金不足就扣除账户余额
				$money = $user_money_frozen + $user_money;
				$pay_type = 'frozen_cash';
			}

		}
		else
		{
			$order_finish = true;
		}

		//退款金额
		$shop_return_amount = sprintf("%.2f",$shop_return_amount);
		//商家资金总额
		$money = sprintf("%.2f",$money);

		//判断商家资金是否足以退款，或者该笔退单是都需要商家退还金额
		//$order_finish:true->未收货，不需要退款
		//$order_finish:false->已收货，需要退款
		if(($shop_return_amount <= $money) || $order_finish)
		{
			$data['return_shop_message'] = '同意';
			//判断是退款还是退货，如果是退货需要进入买家退货阶段
			if ($return_detail['return_goods_return'] == Order_ReturnModel::RETURN_GOODS_RETURN)
			{//退货
				$data['return_state'] = Order_ReturnModel::RETURN_SELLER_PASS;
			}
			else
			{//退款
				$data['return_state'] = Order_ReturnModel::RETURN_SELLER_GOODS;
			}
			$data['return_shop_handle'] = Order_ReturnModel::RETURN_SELLER_PASS;
			$data['return_shop_time'] = get_date_time();
			$flag                     = $orderReturnModel->editReturn($return_detail['order_return_id'], $data);
			check_rs($flag,$rs_row);

			//如果订单为分销商采购单，扣除分销商的钱
			if($order_base['order_source_id'])
			{
				$dist_order = $Order_BaseModel -> getOneByWhere(array('order_id'=>$order_base['order_source_id']));
				if(!empty($dist_order)){
					$dist_return_order = $orderReturnModel->getOneByWhere(array('order_number'=>$dist_order['order_id'],'return_type'=>$return_detail['return_type']));

					$flag = $orderReturnModel->editReturn($dist_return_order['order_return_id'], $data);
					check_rs($flag,$rs_row);
				}
			}

			if($flag && !$order_finish)
			{
				//扣除卖家的金额
				$key                 = Yf_Registry::get('shop_api_key');
				$formvars            = array();
				$user_id             = $order_base['seller_user_id'];
				$formvars['user_id'] = $user_id;
				$formvars['user_name'] = $order_base['seller_user_name'];
				$formvars['app_id'] = Yf_Registry::get('shop_app_id');
				$formvars['money'] = $shop_return_amount * (-1);
				$formvars['pay_type'] = $pay_type;
				$formvars['reason'] = '退款';
				$formvars['order_id'] = $order_base['order_id'];
				$formvars['goods_id'] = $return_detail['order_goods_id'];

				$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Info&met=editReturnUserResourceInfo&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);

				$dist_rs['status'] = 200;
				//分销
				if(isset($dist_return_order) && !empty($dist_return_order))
				{
					$key                 = Yf_Registry::get('shop_api_key');
					$formvars            = array();
					$formvars['user_id'] = $dist_order['seller_user_id'];
					$formvars['user_name'] = $dist_order['seller_user_name'];
					$formvars['money'] = ($dist_return_order['return_cash']-$dist_return_order['return_commision_fee'])*(-1);
					$formvars['order_id'] = $dist_order['order_id'];
					$formvars['goods_id'] = 0;
					$formvars['app_id'] = Yf_Registry::get('shop_app_id');
					$formvars['pay_type'] = $pay_type;
					$formvars['reason'] = '退款';

					$dist_rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Info&met=editReturnUserResourceInfo&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
				}

				if($rs['status'] == 200 && $dist_rs['status']==200)
				{
					$flag = true;
				}
				else
				{
					$flag = false;
				}
				check_rs($flag,$rs_row);
			}
		}
		else
		{
			$flag = false;
			$msg    = __('账户余额不足');
			check_rs($flag,$rs_row);
		}

		$flag = is_ok($rs_row);

		return $flag;

	}

}

?>
<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Seller_Order_SettlementCtl extends Seller_Controller
{
	/**
	 * Constructor
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
	 * 首页
	 *
	 * @access public
	 */
	public function addBill()
	{
	
		$Order_SettlementModel = new Order_SettlementModel();

		$Shop_BaseModel = new Shop_BaseModel();
		//查找店铺信息
		$shop_info      = $Shop_BaseModel->getSettlementCycle('12');

		foreach($shop_info as $key => $val)
		{
			if($val['shop_settlement_last_time'] > 0)
			{
				$start_unixtime = strtotime($val['shop_settlement_last_time']);
			}
			else
			{
				$start_unixtime = strtotime($val['shop_create_time']);
			}

			$start_unixtime = $start_unixtime ? strtotime(date('Y-m-d 00:00:00', $start_unixtime) . "+1 day") : "";
			$start_time     = @date('Y-m-d H:i:s', $start_unixtime);

			$end_unixtime = $start_unixtime ? strtotime(date('Y-m-d 23:59:59', $start_unixtime) . "+" . ($val['shop_settlement_cycle']-1) . " day") : "";
			$end_time     = @date('Y-m-d H:i:s', $end_unixtime);

			$time = time();

			fb($time);
			fb($end_unixtime);

			fb($val['shop_settlement_cycle']);
			fb($start_time);
			fb($end_time);

			if ($time > $end_unixtime)
			{
				$rs_row = array();

				//开启事务
				$Order_SettlementModel->sql->startTransactionDb();

				//店铺实物订单结算
				$data = $Order_SettlementModel->settleNormalOrder($val);
				check_rs($data['flag'],$rs_row);

				//店铺虚拟订单结算
				$data1 = $Order_SettlementModel->settleVirtualOrder($val);
				check_rs($data1['flag'],$rs_row);


				if(is_ok($rs_row))
				{
					//修改店铺信息中的结算时间
					$edit_shop_base['shop_settlement_last_time'] = $data['end_time'];
					$edit_flag = $Shop_BaseModel->editBase($val['shop_id'],$edit_shop_base);
					check_rs($edit_flag,$rs_row);
				}

				$flag = is_ok($rs_row);
				//关闭事务
				if ($flag && $Order_SettlementModel->sql->commitDb())
				{
					//结算单等待确认提醒
					$message = new MessageModel();
					$message->sendMessage('Settlement sheet for confirmation',$val['user_id'], $val['user_name'], $data['os_id'], $shop_name = NULL, 1, 1, $end_time = $data['end_time'],$common_id=NULL,$goods_id=NULL,$des=NULL, $start_time = $data['start_time']);

					$message->sendMessage('Settlement sheet for confirmation',$val['user_id'], $val['user_name'], $data1['os_id'], $shop_name = NULL, 1, 1, $end_time = $data1['end_time'],$common_id=NULL,$goods_id=NULL,$des=NULL, $start_time = $data1['start_time']);
				}
				else
				{
					$Order_SettlementModel->sql->rollBackDb();
					$m      = $Order_SettlementModel->msg->getMessages();
				}

				$this->data->addBody(-140,$data);
			}
		}
	}

	//获取虚拟结算
	public function virtual()
	{
		$op   = request_string('op');
		$id   = request_string('id');
		$type = request_string('type', 'active');

		$Yf_Page           = new Yf_Page();
		$Yf_Page->listRows = 10;
		$rows              = $Yf_Page->listRows;
		$offset            = request_int('firstRow', 0);
		$page              = ceil_r($offset / $rows);


		if ($op == 'show')
		{
			$Order_SettlementModel = new Order_SettlementModel();

			$data = $Order_SettlementModel->getOneSettle($id);

			//订单列表
			if ($type == 'active')
			{
				//查找结算订单表中的订单列表
				$order_cond_row                           = array();
				$order_cond_row["shop_id"]                = $data['shop_id'];
				$order_cond_row['order_finished_time:>='] = $data['os_start_date'];
				$order_cond_row['order_finished_time:<='] = $data['os_end_date'];
				$order_cond_row['order_is_virtual'] = 1;

				$Order_BaseModel = new Order_BaseModel;
				$list            = $Order_BaseModel->listByWhere($order_cond_row, array(), $page, $rows);

			}
			//退款订单
			if ($type == 'refund')
			{
				//查找结算订单表中的订单列表
				$order_cond_row                           = array();
				$order_cond_row["seller_user_id"]                = $data['shop_id'];
				$order_cond_row['return_finish_time:>='] = $data['os_start_date'];
				$order_cond_row['return_finish_time:<='] = $data['os_end_date'];
				$order_cond_row['order_is_virtual'] = 1;
				$Order_ReturnModel = new Order_ReturnModel();

				$list                                     = $Order_ReturnModel->listByWhere($order_cond_row, array(), $page, $rows);

			}
			//店铺费用
			if ($type == 'cost')
			{
				$shop_cost_row                 = array();
				$shop_cost_row["shop_id"]       = $data['shop_id'];
				$shop_cost_row['cost_time:>='] = $data['os_start_date'];
				$shop_cost_row['cost_time:<='] = $data['os_end_date'];
				$shop_cost_row['cost_status']  = Shop_CostModel::SETTLED;

				$Shop_CostModel = new Shop_CostModel();
				$list           = $Shop_CostModel->listByWhere($shop_cost_row, array(), $page, $rows);
			}

			$this->view->setMet('showVirtual');
		}
		else
		{
			$Order_SettlementModel = new Order_SettlementModel();

			$shop_id = Perm::$shopId;

			$cond_row  = array();
			$order_row = array('os_id'=> 'DESC');

			$cond_row = array(
				'shop_id' => $shop_id,
				'os_order_type' => Order_SettlementModel::SETTLEMENT_VIRTUAL_ORDER
			);

            if (request_string('settlement_status') === 'finish') {
                $cond_row['os_state'] = Order_SettlementModel::SETTLEMENT_FINISH;
            } elseif (request_string('settlement_status') === 'unfinished') {
                $cond_row['os_state:IN'] = [
                    Order_SettlementModel::SETTLEMENT_WAIT_OPERATE,
                    Order_SettlementModel::SETTLEMENT_SELLER_COMFIRMED,
                    Order_SettlementModel::SETTLEMENT_PLATFORM_COMFIRMED,
                ];
            }

			$list = $Order_SettlementModel->getSettlementList($cond_row, $order_row, $page, $rows);

            if ($this->typ === 'json') {
                return $this->data->addBody(-140, $list, 'success', 200);
            }
		}


		$Yf_Page->totalRows = $list['totalsize'];
		$page_nav           = $Yf_Page->prompt();

		include $this->view->getView();
	}

	//获取虚拟结算列表
	public function getVirtualList()
	{
		$Order_SettlementModel = new Order_SettlementModel();

		$shop_id = Perm::$shopId;
		//$shop_id   = 1;
		$cond_row  = array();
		$order_row = array();

		$cond_row = array(
			'shop_id' => $shop_id,
			'os_order_type' => Order_SettlementModel::SETTLEMENT_VIRTUAL_ORDER
		);

		$data = $Order_SettlementModel->getSettlementList($cond_row, $order_row);

		if ($data)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}


		$this->data->addBody(-140, $data, $msg, $status);

		return $data;
	}

	//获取实物结算列表
	public function getNormalList()
	{
		$Order_SettlementModel = new Order_SettlementModel();

		$shop_id = Perm::$shopId;
		//$shop_id   = 1;
		$cond_row  = array();
		$order_row = array();

		$cond_row = array(
			'shop_id' => $shop_id,
			'os_order_type' => Order_SettlementModel::SETTLEMENT_NORMAL_ORDER
		);

		$data = $Order_SettlementModel->getSettlementList($cond_row, $order_row);

		if ($data)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}


		$this->data->addBody(-140, $data, $msg, $status);

		return $data;
	}


	//获取实物结算
	public function normal()
	{
		$op   = request_string('op');
		$id   = request_string('id');
		$type = request_string('type', 'active');

		$Yf_Page           = new Yf_Page();
		$Yf_Page->listRows = 10;
		$rows              = $Yf_Page->listRows;
		$offset            = request_int('firstRow', 0);
		$page              = ceil_r($offset / $rows);


		if ($op == 'show')
		{
			$Order_SettlementModel = new Order_SettlementModel();

			$data = $Order_SettlementModel->getOneSettle($id);

			//订单列表
			if ($type == 'active')
			{
				//查找结算订单表中的订单列表
				$order_cond_row                           = array();
				$order_cond_row["shop_id"]                = $data['shop_id'];
				$order_cond_row['order_finished_time:>='] = $data['os_start_date'];
				$order_cond_row['order_finished_time:<='] = $data['os_end_date'];
				$order_cond_row['order_is_virtual'] = 0;

				$Order_BaseModel = new Order_BaseModel;
				$list            = $Order_BaseModel->listByWhere($order_cond_row, array(), $page, $rows);
			}
			//退款订单
			if ($type == 'refund')
			{
				//查找退款退货订单
				$order_cond_row                           = array();
				$order_cond_row["seller_user_id"]                = $data['shop_id'];
				$order_cond_row['return_finish_time:>='] = $data['os_start_date'];
				$order_cond_row['return_finish_time:<='] = $data['os_end_date'];
				$order_cond_row['order_is_virtual'] = 0;
				$order_cond_row['return_state'] = Order_ReturnModel::RETURN_PLAT_PASS;

				$Order_ReturnModel = new Order_ReturnModel();

				$list                                     = $Order_ReturnModel->listByWhere($order_cond_row, array(), $page, $rows);

			}
			//店铺费用
			if ($type == 'cost')
			{
				$shop_cost_row                 = array();
				$shop_cost_row["shop_id"]      = $data['shop_id'];
				$shop_cost_row['cost_time:>='] = $data['os_start_date'];
				$shop_cost_row['cost_time:<='] = $data['os_end_date'];
				$shop_cost_row['cost_status']  = Shop_CostModel::SETTLED;

				$Shop_CostModel = new Shop_CostModel();
				$list           = $Shop_CostModel->listByWhere($shop_cost_row, array(), $page, $rows);
			}

			$this->view->setMet('showNormal');
		}
		else
		{
			$Order_SettlementModel = new Order_SettlementModel();

			$shop_id = Perm::$shopId;
			//$shop_id   = 1;
			$cond_row  = array();
			$order_row = array('os_id'=> 'DESC');

			$cond_row = array(
				'shop_id' => $shop_id,
				'os_order_type' => Order_SettlementModel::SETTLEMENT_NORMAL_ORDER
			);
            
            if (request_string('settlement_status') === 'finish') {
                $cond_row['os_state'] = Order_SettlementModel::SETTLEMENT_FINISH;
            } elseif (request_string('settlement_status') === 'unfinished') {
                $cond_row['os_state:IN'] = [
                    Order_SettlementModel::SETTLEMENT_WAIT_OPERATE,
                    Order_SettlementModel::SETTLEMENT_SELLER_COMFIRMED,
                    Order_SettlementModel::SETTLEMENT_PLATFORM_COMFIRMED,
                ];
            }

			$list = $Order_SettlementModel->getSettlementList($cond_row, $order_row, $page, $rows);

            if ($this->typ === 'json') {
                return $this->data->addBody(-140, $list, 'success', 200);
            }
		}


		$Yf_Page->totalRows = $list['totalsize'];
		$page_nav           = $Yf_Page->prompt();

		include $this->view->getView();
	}

	//结算无误，确认结算
	public function confirmSettlement()
	{
		$id                    = request_int('id');
		$Order_SettlementModel = new Order_SettlementModel();
		$edit['os_state']      = $Order_SettlementModel::SETTLEMENT_SELLER_COMFIRMED;
		$flag                  = $Order_SettlementModel->editSettlement($id, $edit);
		if ($flag !== false)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		$this->data->addBody(-140, array(), $msg, $status);
	}


	public function getShopSettlementData()
    {
        $shop_id = Perm::$shopId;

        $orderSettlementModel = new Order_SettlementModel();

        $order_settlement_rows = $orderSettlementModel->getByWhere([
            'shop_id'=> $shop_id,
        ]);

        $result = [
            'settled_amount_sum'=> 0,
            'not_settled_amount_sum'=> 0,
        ];

        foreach ($order_settlement_rows as $data) {
            if ($data['os_state'] == Order_SettlementModel::SETTLEMENT_FINISH) {
                $result['settled_amount_sum'] += $data['os_amount'];
            } else {
                $result['not_settled_amount_sum'] += $data['os_amount'];
            }
        }

        $this->data->addBody(-140, $result, 'success', 200);
	}
}

?>
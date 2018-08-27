<?php

class Api_Operation_SettlementCtl extends Api_Controller
{

	public $settlementModel  = null;
	public $orderdetailModel = null;
	public $orderReturnModel = null;
	public $Shop_CostModel   = null;

	/**
	 * 初始化方法，构造函数
	 *
	 * @access public
	 */
	public function init()
	{
		$this->settlementModel  = new Order_SettlementModel();
		$this->orderdetailModel = new Order_BaseModel();
		$this->orderReturnModel = new Order_ReturnModel();
		$this->Shop_CostModel   = new Shop_CostModel();
	}

	public function getSettleList()
	{

		$os_order_type = request_int("otyp", Order_SettlementModel::SETTLEMENT_NORMAL_ORDER);
		$page          = request_int('page', 1);
		$rows          = request_int('rows', 10);
		$type          = request_string('user_type');
		$settleId      = request_string('settleId');
		$shopName      = request_string('shopName');
		$state         = request_string('state');
		$start_time    = request_string('start_time');
		$end_time      = request_string('end_time');
		$oname         = request_string('sidx');
		$osort         = request_string('sord');

		$cond_row                  = array();
		$sort                      = array();
		$cond_row["os_order_type"] = $os_order_type;
		if ($settleId)
		{
			$cond_row['os_id:LIKE'] = '%' . $settleId . '%';
		}
		if ($shopName)
		{
			$cond_row['shop_name:LIKE'] = '%' . $shopName . '%';
		}
		if ($state)
		{
			$cond_row['os_state'] = $state;
		}
		if ($start_time)
		{
			$cond_row['os_start_date:>='] = $start_time;
		}
		if ($end_time)
		{
			$cond_row['os_end_date:<='] = $end_time;
		}
		if ($oname != "number")
		{
			$sort[$oname] = $osort;
		}
        //结算分站筛选
        $sub_site_id = request_int('sub_site_id');
        $sub_flag = true;
        if($sub_site_id > 0){
            //获取站点信息
            $Sub_SiteModel = new Sub_SiteModel();
            $sub_site_district_ids = $Sub_SiteModel->getDistrictChildId($sub_site_id);
            if(!$sub_site_district_ids){
                $sub_flag = false;
            }else{
                $cond_row['district_id:IN'] = $sub_site_district_ids;
            }
        }
        if($sub_flag == false){
            $status = 250;
			$msg    = __('分站信息获取失败');
            $this->data->addBody(-140, array(), $msg, $status);
        }else{
            $data = $this->settlementModel->getSettlementList($cond_row, $sort, $page, $rows);
            if($data){
                $status = 200;
                $msg    = __('success');
            }else{
                $status = 250;
                $msg    = __('没有数据');;
            }
            $this->data->addBody(-140, $data, $msg, $status);
        }
	}

	public function getSettleExcel()
	{

		$os_order_type = request_int("otyp", Order_SettlementModel::SETTLEMENT_NORMAL_ORDER);
		$type          = request_string('user_type');
		$settleId      = request_string('settleId');
		$shopName      = request_string('shopName');
		$state         = request_string('state');
		$start_time    = request_string('start_time');
		$end_time      = request_string('end_time');
		$oname         = request_string('sidx');
		$osort         = request_string('sord');

		$cond_row                  = array();
		$sort                      = array();
		$cond_row["os_order_type"] = $os_order_type;
		if ($settleId)
		{
			$cond_row['os_id:LIKE'] = '%' . $settleId . '%';
		}
		if ($shopName)
		{
			$cond_row['shop_name:LIKE'] = '%' . $shopName . '%';
		}
		if ($state)
		{
			$cond_row['os_state'] = $state;
		}
		if ($start_time)
		{
			$cond_row['os_start_date:>='] = $start_time;
		}
		if ($end_time)
		{
			$cond_row['os_end_date:<='] = $end_time;
		}
//        if ($oname != "number")
//        {
//            $sort[$oname] = $osort;
//        }
		$con = array();
		$con = $this->settlementModel->getSettlementExcel($cond_row, $sort);
		$tit = array(
			"序号",
			"账单编号",
			"订单金额(含运费)",
			"运费",
			"平台红包",
			"收取佣金",
			"退单金额",
			"退还红包金额",
			"退还佣金",
			"店铺费用",
			"本期应结",
			"出账日期",
			"账单状态",
			"商家名称",
			"开始日期",
			"结束日期"
		);
		$key = array(
			"os_id",
			"os_order_amount",
			"os_shipping_amount",
			"os_redpacket_amount",
			"os_commis_amount",
			"os_order_return_amount",
			"os_redpacket_return_amount",
			"os_commis_return_amount",
			"os_shop_cost_amount",
			"os_amount",
			"os_datetime",
			"os_state_text",
			"shop_name",
			"os_start_date",
			"os_end_date"
		);
		$this->excel("结算单", $tit, $con, $key);
	}

	public function detail()
	{
		$Order_SettlementModel = new Order_SettlementModel();


		$data['id']    = request_string('id');
		$os_id         = request_string('id');
//		echo '<pre>';print_r($os_id);exit;
		$data['items'] = $Order_SettlementModel->getOneSettle($os_id);
		if ($data['items']['os_order_type'] == Order_SettlementModel::SETTLEMENT_NORMAL_ORDER)
		{
			$data['tab'] = request_string('tab', "order");
		}
		elseif ($data['items']['os_order_type'] == Order_SettlementModel::SETTLEMENT_VIRTUAL_ORDER)
		{
			$data['tab'] = request_string('tab', "used");
		}
		$this->data->addBody(-140, $data);
	}

	public function getOrder()
	{
		$page               = request_int('page', 1);
		$rows               = request_int('rows', 10);
		$order_id           = request_string('order_id');
		$buyer_user_account = request_string('buyer_user_account');
		$oname              = request_string('sidx');
		$osort              = request_string('sord');
		$os_id              = request_string('id');

		$cond_row = array();
		$sort     = array();
		if ($order_id)
		{
			$cond_row['order_id'] = $order_id;
		}
		if ($buyer_user_account)
		{
			$cond_row['buyer_user_name:LIKE'] = '%' . $buyer_user_account . '%';
		}
		if ($oname != "number")
		{
			$sort[$oname] = $osort;
		}
		$settle                             = $this->settlementModel->getOneSettle($os_id);
		$cond_row["order_finished_time:>="] = $settle['os_start_date'];
		$cond_row["order_finished_time:<="] = $settle['os_end_date'];
		$cond_row["shop_id"]                = $settle['shop_id'];
		$data                               = array();
		$data                               = $this->orderdetailModel->getBaseList($cond_row, $sort, $page, $rows);
		$this->data->addBody(-140, $data);
	}

	public function getOrderExcel()
	{
		$order_id           = request_string('order_id');
		$buyer_user_account = request_string('buyer_user_account');
		$oname              = request_string('sidx');
		$osort              = request_string('sord');
		$os_id              = request_string('id');

		$cond_row = array();
		$sort     = array();
		if ($order_id)
		{
			$cond_row['order_id'] = $order_id;
		}
		if ($buyer_user_account)
		{
			$cond_row['buyer_user_name:LIKE'] = '%' . $buyer_user_account . '%';
		}
//        if ($oname != "number")
//        {
//            $sort[$oname] = $osort;
//        }
		$settle                             = $this->settlementModel->getOneSettle($os_id);
		$cond_row["order_finished_time:>="] = $settle['os_start_date'];
		$cond_row["order_finished_time:<="] = $settle['os_end_date'];
		$cond_row["shop_id"]                = $settle['shop_id'];
		$con                                = array();
		$con                                = $this->orderdetailModel->getBaseExcel($cond_row, $sort);
		$tit                                = array(
			"序号",
			"订单编号",
			"订单金额(含运费)",
			"红包金额",
			"运费",
			"佣金",
			"下单日期",
			"成交日期",
			"买家",
			"商家"
		);
		$key                                = array(
			"order_id",
			"order_payment_amount",
			"order_rpt_price",
			"order_shipping_fee",
			"order_commission_fee",
			"order_create_time",
			"order_finished_time",
			"buyer_user_name",
			"seller_user_name"
		);
		$this->excel("结算单订单详情", $tit, $con, $key);
	}

	public function getReturn()
	{
		$page               = request_int('page', 1);
		$rows               = request_int('rows', 10);
		$order_id           = request_string('order_id');
		$buyer_user_account = request_string('buyer_user_account');
		$return_code        = request_string("return_code");
		$oname              = request_string('sidx');
		$osort              = request_string('sord');
		$os_id              = request_string('id');

		$cond_row = array();
		$sort     = array();
		if ($order_id)
		{
			$cond_row['order_number'] = $order_id;
		}
		if ($buyer_user_account)
		{
			$cond_row['buyer_user_account:LIKE'] = '%' . $buyer_user_account . '%';
		}
		if ($return_code)
		{
			$cond_row['return_code'] = $return_code;
		}
		if ($oname != "number")
		{
			$sort[$oname] = $osort;
		}
		$settle                            = $this->settlementModel->getOneSettle($os_id);
		$cond_row["return_finish_time:>="] = $settle['os_start_date'];
		$cond_row["return_finish_time:<="] = $settle['os_end_date'];
		$cond_row["seller_user_id"]        = $settle['shop_id'];
		$data                              = array();
		$data                              = $this->orderReturnModel->getReturnList($cond_row, $sort, $page, $rows);
		$this->data->addBody(-140, $data);
	}

	public function getReturnExcel()
	{
		$order_id           = request_string('order_id');
		$buyer_user_account = request_string('buyer_user_account');
		$return_code        = request_string("return_code");
		$oname              = request_string('sidx');
		$osort              = request_string('sord');
		$os_id              = request_string('id');

		$cond_row = array();
		$sort     = array();
		if ($order_id)
		{
			$cond_row['order_number'] = $order_id;
		}
		if ($buyer_user_account)
		{
			$cond_row['buyer_user_account:LIKE'] = '%' . $buyer_user_account . '%';
		}
		if ($return_code)
		{
			$cond_row['return_code'] = $return_code;
		}
//        if ($oname != "number")
//        {
//            $sort[$oname] = $osort;
//        }
		$settle                            = $this->settlementModel->getOneSettle($os_id);
		$cond_row["return_finish_time:>="] = $settle['os_start_date'];
		$cond_row["return_finish_time:<="] = $settle['os_end_date'];
		$cond_row["seller_user_id"]        = $settle['shop_id'];
		$con                               = array();
		$con                               = $this->orderReturnModel->getReturnExcel($cond_row, $sort);
		$tit                               = array(
			"序号",
			"退单编号",
			"订单编号",
			"退款金额",
			"退还佣金",
			"退还红包",
			"类型",
			"退款日期",
			"买家",
			"店铺"
		);
		$key                               = array(
			"return_code",
			"order_number",
			"return_cash",
			"return_commision_fee",
			"return_rpt_cash",
			"return_type_text",
			"return_finish_time",
			"buyer_user_account",
			"seller_user_account"
		);
		$this->excel("结算单退单详情", $tit, $con, $key);
	}

	public function getShopFee()
	{
		$page  = request_int('page', 1);
		$rows  = request_int('rows', 10);
		$oname = request_string('sidx');
		$osort = request_string('sord');
		$os_id = request_string('id');

		$cond_row = array();
		$sort     = array();
		if ($oname != "number")
		{
			$sort[$oname] = $osort;
		}
		$settle                   = $this->settlementModel->getOneSettle($os_id);
		$cond_row["cost_time:>="] = $settle['os_start_date'];
		$cond_row["cost_time:<="] = $settle['os_end_date'];
		$cond_row["shop_id"]      = $settle['shop_id'];
		$cond_row['cost_status']  = Shop_CostModel::SETTLED;

		$data                     = array();
		$data                     = $this->Shop_CostModel->listByWhere($cond_row, $sort, $page, $rows);
		$this->data->addBody(-140, $data);
	}


	public function getShopFeeExcel()
	{
		$oname = request_string('sidx');
		$osort = request_string('sord');
		$os_id = request_string('id');

		$cond_row = array();
		$sort     = array();
//        if ($oname != "number")
//        {
//            $sort[$oname] = $osort;
//        }
		$settle                   = $this->settlementModel->getOneSettle($os_id);
		$cond_row["cost_time:>="] = $settle['os_start_date'];
		$cond_row["cost_time:<="] = $settle['os_end_date'];
		$cond_row["shop_id"]      = $settle['shop_id'];
		$con                      = array();
		$con                      = $this->Shop_CostModel->getCostExcel($cond_row, $sort);
		$tit                      = array(
			"序号",
			"店铺名称",
			"促销名称",
			"促销费用",
			"申请日期"
		);
		$key                      = array(
			"shop_name",
			"cost_desc",
			"cost_price",
			"cost_time"
		);
		$this->excel("结算单店铺费用详情", $tit, $con, $key);
	}

	public function getVirtual()
	{
		$page               = request_int('page', 1);
		$rows               = request_int('rows', 10);
		$state              = request_string('state', 'used');
		$code               = request_string('order_id');
		$buyer_user_account = request_string('buyer_user_account');
		$oname              = request_string('sidx');
		$osort              = request_string('sord');
		$os_id              = request_string('id');

		$cond_row                     = array();
		$sort                         = array();
		$cond_row['order_is_virtual'] = Order_BaseModel::ORDER_IS_VIRTUAL;
		if ($state == "used")
		{
			$cond_row['order_virtual_use'] = Order_BaseModel::VIRTUAL_USED;
		}
		elseif ($state == "unused")
		{
			$cond_row['order_virtual_use'] = Order_BaseModel::VIRTUAL_UNUSE;
		}

		if ($code)
		{
			$cond_row['order_virtual_code'] = $code;
		}
		if ($buyer_user_account)
		{
			$cond_row['buyer_user_name:LIKE'] = '%' . $buyer_user_account . '%';
		}
		if ($oname != "number")
		{
			$sort[$oname] = $osort;
		}
		$settle                             = $this->settlementModel->getOneSettle($os_id);
		$cond_row["order_finished_time:>="] = $settle['os_start_date'];
		$cond_row["order_finished_time:<="] = $settle['os_end_date'];
		$cond_row["shop_id"]                = $settle['shop_id'];
		$data                               = array();
		$data                               = $this->orderdetailModel->getBaseList($cond_row, $sort, $page, $rows);
		$this->data->addBody(-140, $data);
	}

	public function getVirtualExcel()
	{
		$state              = request_string('state', 'used');
		$code               = request_string('order_id');
		$buyer_user_account = request_string('buyer_user_account');
		$oname              = request_string('sidx');
		$osort              = request_string('sord');
		$os_id              = request_string('id');

		$cond_row                     = array();
		$sort                         = array();
		$cond_row['order_is_virtual'] = Order_BaseModel::ORDER_IS_VIRTUAL;
		if ($state == "used")
		{
			$cond_row['order_virtual_use'] = Order_BaseModel::VIRTUAL_USED;
		}
		elseif ($state == "unused")
		{
			$cond_row['order_virtual_use'] = Order_BaseModel::VIRTUAL_UNUSE;
		}

		if ($code)
		{
			$cond_row['order_virtual_code'] = $code;
		}
		if ($buyer_user_account)
		{
			$cond_row['buyer_user_name:LIKE'] = '%' . $buyer_user_account . '%';
		}
//        if ($oname != "number")
//        {
//            $sort[$oname] = $osort;
//        }
		$settle                             = $this->settlementModel->getOneSettle($os_id);
		$cond_row["order_finished_time:>="] = $settle['os_start_date'];
		$cond_row["order_finished_time:<="] = $settle['os_end_date'];
		$cond_row["shop_id"]                = $settle['shop_id'];
		$con                                = array();
		$con                                = $this->orderdetailModel->getBaseExcel($cond_row, $sort);
		$tit                                = array(
			"序号",
			"兑换码",
			"消费时间",
			"订单号",
			"消费金额",
			"佣金金额",
			"买家"
		);
		$key                                = array(
			"order_virtual_code",
			"order_create_time",
			"order_id",
			"order_payment_amount",
			"order_commission_fee",
			"buyer_user_name"
		);
		$this->excel("虚拟结算单详情", $tit, $con, $key);

	}

	public function updateStatu()
	{
		$os_id  = request_int("os_id");
		$handle = request_string("handle");

		if ($handle == "platform_comfirmed")
		{
			$data['os_state'] = Order_SettlementModel::SETTLEMENT_PLATFORM_COMFIRMED;
			$flag             = $this->settlementModel->editSettlement($os_id, $data);
		}
		elseif ($handle == "finish")
		{
			$data['os_state']       = Order_SettlementModel::SETTLEMENT_FINISH;
			$data['os_pay_content'] = request_string("os_pay_content");
			$data['os_pay_date']    = date("Y-m-d H:i:s");
			$flag                   = $this->settlementModel->editSettlement($os_id, $data);

			if($flag)
			{
				$settlement =  $this->settlementModel->getOne($os_id);
				//根据店铺id获取用户id
				$Shop_BaseModel = new Shop_BaseModel();
				$shop_base = $Shop_BaseModel->getOne($settlement['shop_id']);

				//paycenter中修改商家的资金
				$key      = Yf_Registry::get('shop_api_key');
				$url         = Yf_Registry::get('paycenter_api_url');
				$shop_app_id = Yf_Registry::get('shop_app_id');
				$formvars = array();

				$formvars['app_id']					= $shop_app_id;
				$formvars['user_id']     			= $shop_base['user_id'];
				$formvars['amount']                = $settlement['os_amount'];
				fb($formvars);
				$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=shopSettlement&typ=json',$url), $formvars);

				if($rs['status'] == 200)
				{
					$flag = true;
				}
				else
				{
					$flag = false;
				}
			}

		}

		if ($flag)
		{
			$msg    = __('success');
			$status = 200;
			if ($handle == "finish")
			{
				$settlement = $this->settlementModel->getOne($os_id);
				$this->shopBaseModel = new Shop_BaseModel();
				$shop  = $this->shopBaseModel->getOne($settlement['shop_id']);
				//结算单已经付款提醒
				//[order_id]
				$message = new MessageModel();
				$message->sendMessage('Settlement bill has been paid to remind',$shop['user_id'],$shop['user_name'], $os_id, $shop_name = NULL, 1, 1);
			}
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['id'] = $flag;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	function excel($title, $tit, $con, $key)
	{
		ob_end_clean();   //***这里再加一个
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("mall_new");
		$objPHPExcel->getProperties()->setLastModifiedBy("mall_new");
		$objPHPExcel->getProperties()->setTitle($title);
		$objPHPExcel->getProperties()->setSubject($title);
		$objPHPExcel->getProperties()->setDescription($title);
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle($title);
		$letter = array(
			'A',
			'B',
			'C',
			'D',
			'E',
			'F',
			'G',
			'H',
			'I',
			'J',
			'K',
			'L',
			'M',
			'N',
			'O',
			'P',
			'Q',
			'R',
			'S',
			'T',
			'U'
		);
		foreach ($tit as $k => $v)
		{
			$objPHPExcel->getActiveSheet()->setCellValue($letter[$k] . "1", $v);
		}
		foreach ($con as $k => $v)
		{
			$objPHPExcel->getActiveSheet()->setCellValue($letter[0] . ($k + 2), $k + 1);
			foreach ($key as $k2 => $v2)
			{

				$objPHPExcel->getActiveSheet()->setCellValue($letter[$k2 + 1] . ($k + 2), $v[$v2]);
			}
		}
		ob_end_clean();   //***这里再加一个
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment; filename=\"$title.xls\"");
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
		die();
	}
}

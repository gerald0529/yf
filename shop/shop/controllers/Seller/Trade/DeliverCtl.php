<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     windfnn
 */
class Seller_Trade_DeliverCtl extends Seller_Controller
{
	public $shopShippingAddressModel = null;

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
		$this->shopShippingAddressModel = new Shop_ShippingAddressModel();
		$this->shopExpressModel         = new Shop_ExpressModel();
	}

	/**
	 * 待发货(index)
	 *
	 * @access public
	 *
	 * 筛选出已付款和货到付款的订单
	 */
	public function deliver()
	{
		$Order_BaseModel              = new Order_BaseModel();
		$condition['order_status:IN'] = array( Order_StateModel::ORDER_PAYED, Order_StateModel::ORDER_WAIT_PREPARE_GOODS );
		$data                         = $Order_BaseModel->getPhysicalList($condition);

		include $this->view->getView();
	}

	/**
	 * 发货中
	 *
	 * @access public
	 */
	public function delivering()
	{
		$Order_BaseModel           = new Order_BaseModel();
		$condition['order_status'] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
		$data                      = $Order_BaseModel->getPhysicalList($condition);

		foreach ($data['items'] as $key => $val)
		{
			if (strtotime($val['order_receiver_date']))
			{
				$data['items'][$key]['order_receiver_date'] = $val['order_receiver_date'];
			}
		}

		$this->view->setMet('deliver');
		include $this->view->getView();
	}

	/**
	 * 已收货
	 *
	 * @access public
	 */
	public function delivered()
	{
		$Order_BaseModel           = new Order_BaseModel();
		$condition['order_status'] = Order_StateModel::ORDER_FINISH;
		$data                      = $Order_BaseModel->getPhysicalList($condition);

		$this->view->setMet('deliver');
		include $this->view->getView();
	}
	
	/**
	 * 延迟发货
	 *
	 * @access public
	 */
	public function delayReceive()
	{
		$typ = request_string('typ');

		if ($typ == 'e')
		{
			include $this->view->getView();
		}
		else
		{
			$order_id            = request_string('order_id');
			$delayDays           = request_int('delay_days');
			$order_receiver_date = request_string('order_receiver_date');

			$order_receiver_date           = strtotime($order_receiver_date);
			$order_receiver_date           = strtotime("+$delayDays days", $order_receiver_date);
			$update['order_receiver_date'] = date('Y-m-d H:i:s', $order_receiver_date);

			$Order_BaseModel = new Order_BaseModel();
			$flag            = $Order_BaseModel->editBase($order_id, $update);

			if ($flag)
			{
				$msg    = __('success');
				$status = 200;
			}
			else
			{
				$msg    = __('failure');
				$status = 250;
			}

			$this->data->addBody(-140, $update, $msg, $status);

		}

	}

	/**
	 * 发货设置
	 * 店铺发货地址列表
	 * @access public
	 */
	public function deliverSetting()
	{
        $act = request_string('act');
        if($act == 'addAddress'){
            //获取一级地址
            $district_parent_id = request_int('pid', 0);
            $baseDistrictModel  = new Base_DistrictModel();
            $district           = $baseDistrictModel->getDistrictTree($district_parent_id);
            $shipping_address_id = request_int('shipping_address_id');
            if ($shipping_address_id){
                //获取发货地址信息
                $data = $this->shopShippingAddressModel->getAddress($shipping_address_id);
            }
            $this->view->setMet('addAddress');
            include $this->view->getView();
        }else{
            $Yf_Page           = new Yf_Page();
            $Yf_Page->listRows = 10;
            $rows              = $Yf_Page->listRows;
            $offset            = request_int('firstRow', 0);
            $page              = ceil_r($offset / $rows);

            $cond_row['shop_id'] = Perm::$shopId;
            $data                = $this->shopShippingAddressModel->getBaseList($cond_row, array('shipping_address_time' => 'desc'), $page, $rows);

            $Yf_Page->totalRows = $data['totalsize'];
            $page_nav           = $Yf_Page->prompt();
            if ('json' == $this->typ)
            {
                $this->data->addBody(-140, $data);
            }
            else
            {
                include $this->view->getView();
            }
        }
		
		
	}

	/**
	 * 默认物流公司
	 *
	 * @access public
	 */
	public function express()
	{
		$shop_id             = Perm::$shopId;
		$cond_row['shop_id'] = $shop_id;
		$data                = $this->shopExpressModel->getShopExpressList($cond_row);

        if($data['items']){
            $sort_arr = array();
            foreach ($data['items'] as $value){
                $sort_arr[] = $value['express_pinyin'];
            }
            array_multisort($sort_arr,SORT_ASC,$data['items']);
        }
		
		//保存操作
		if (request_string('op') == 'save')
		{
			$express_id = request_row('id');                       //选中的快递ID
			$flag       = $this->shopExpressModel->editShopExpress($shop_id);   //更改
		}
		if ('json' == $this->typ)
		{
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}

	}
	
	/**
	 * 免运费额度
	 *
	 * @access public
	 */
	public function freightAmount()
	{
		$shop_id        = Perm::$shopId;
		$Shop_BaseModel = new Shop_BaseModel();

		//保存操作
		if (request_string('op') == 'save')
		{
			$flag = $Shop_BaseModel->editFreightAmount($shop_id);
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
			$data = array();
			$this->data->addBody(-140, $data, $msg, $status);
			
			//location_to('index.php?ctl=Seller_Trade_Deliver&met=freightAmount');
		}

		$data = $Shop_BaseModel->getShopBaseInfo($shop_id);
		
		if ('json' == $this->typ)
		{
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}
	}
	
	/**
	 * 默认配送地区
	 *
	 * @access public
	 */
	public function deliverArea()
	{
		//获取一级地址
		$district_parent_id = request_int('pid', 0);
		$baseDistrictModel  = new Base_DistrictModel();
		$district           = $baseDistrictModel->getDistrictTree($district_parent_id);
		
		$shop_id        = Perm::$shopId;
		$Shop_BaseModel = new Shop_BaseModel();
		$data           = $Shop_BaseModel->getShopBaseInfo($shop_id);

		//保存地址
		if (request_string('op') == 'save')
		{
			$field_row['shop_region'] = request_string('shop_region');
			$flag                     = $Shop_BaseModel->setPrint($shop_id, $field_row);
			
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
			$data = array();
			$this->data->addBody(-140, $data, $msg, $status);
			//location_to('index.php?ctl=Seller_Trade_Deliver&met=deliverArea');
		}
		
		if ('json' == $this->typ)
		{
			$data['district'] = $district;
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}
	}
	
	/**
	 * 发货单打印设置
	 *
	 * @access public
	 */
	public function printSetting()
	{
		$shop_id        = Perm::$shopId;
		$Shop_BaseModel = new Shop_BaseModel();
		
		//保存修改
		if (request_string('op') == 'save')
		{
			$field_row['shop_print_desc'] = request_string('shop_print_desc');  //打印描述
			$field_row['shop_stamp']      = request_string('shop_stamp');            //店铺印章

			$flag = $Shop_BaseModel->setPrint($shop_id, $field_row);
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
			$data = array();
			$this->data->addBody(-140, $data, $msg, $status);
			//location_to('index.php?ctl=Seller_Trade_Deliver&met=printSetting');
		}
		
		$data = $Shop_BaseModel->getShopBaseInfo($shop_id);
		
		if ('json' == $this->typ)
		{
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}
	}
	
	/**
	 * 新增发货地址
	 * 修改发货地址
	 * @access public
	 */
	public function addAddress()
	{
		$field_row                                 = array();
		$field_row['shop_id']                      = Perm::$shopId;
		$field_row['shipping_address_contact']     = request_string('shipping_address_contact');    //联系人
		$field_row['shipping_address_phone']       = request_string('shipping_address_phone');        //联系方式
		$field_row['shipping_address_address']     = request_string('shipping_address_address');    //详细地址
		$field_row['shipping_address_province_id'] = request_int('province_id');                //省份ID
		$field_row['shipping_address_city_id']     = request_int('city_id');                        //城市ID
		$field_row['shipping_address_area_id']     = request_int('area_id');                        //地区ID
		$field_row['shipping_address_area']        = request_string('address_area');                    //地址信息
		$field_row['shipping_address_company']     = request_string('shipping_address_company');    //公司
		$field_row['shipping_address_time']        = get_date_time();                                  //添加时间
		
		
		//新增地址
		if (request_string('op') == 'save')
		{
			$flag = $this->shopShippingAddressModel->addAddress($field_row, true);

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
			$data = array();
			return $this->data->addBody(-140, $data, $msg, $status);
		}
		
		//修改地址
		if (request_string('op') == 'edit')
		{
			
            $id   = request_int('id');
            $flag = $this->shopShippingAddressModel->updateAddress($id, $field_row);
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
            $data = array();
            return $this->data->addBody(-140, $data, $msg, $status);
		}

	}

	/**
	 * 设置默认发货地址
	 *
	 * @access public
	 */
	public function setDefaultAddress()
	{
		$shop_id                               = Perm::$shopId;
		$shipping_address_id                   = request_int('shipping_address_id');
		$field_row                             = array();
		$field_row['shop_id']                  = $shop_id;
		$field_row['shipping_address_default'] = 1;

		$this->shopShippingAddressModel->setDefaultAddress($shop_id, $shipping_address_id, $field_row);
	}

	/**
	 * 删除发货地址
	 *
	 * @access public
	 */
	public function delAddress()
	{
		$shop_id             = Perm::$shopId;
		if(request_row('id'))
		{
			$shipping_address_id = request_row('id');
		}
		else
		{
			$shipping_address_id = request_row('id');
		}

		$flag                = $this->shopShippingAddressModel->removeAddress($shipping_address_id);

		if ($flag !== false)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}
}

?>
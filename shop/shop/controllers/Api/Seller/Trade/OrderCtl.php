<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}
 
/**
 * @author     windfnn
 */
class Api_Seller_Trade_OrderCtl extends Api_Controller
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
	 * 交易订单
	 * @param int $type 当前订单交易状态
	 * @param int $page 第几页
	 * @param int $type 当前订单交易状态
	 * @access public
	 */
	public function orderList()
	{
        $condition       = array();
		$type = request_int('type');
		$page = request_int('page');
		$rows = request_int('rows');
		$kind = request_string('kind');
		$user_id = request_int('user_id');
		$buyer_user_id = request_string('buyer_user_id');
		$order_id = request_string('order_id');
		$Order_StateModel = new Order_StateModel();
		$Order_GoodsModel = new Order_GoodsModel();
		$User_InfoModel = new User_InfoModel();
		$Order_ReturnModel = new Order_ReturnModel();
		$Order_BaseModel = new Order_BaseModel();
		$Shop_BaseModel = new Shop_BaseModel();
		$order_status = $Order_StateModel->getByWhere();
		$shop_info = $Shop_BaseModel->getOneByWhere(['user_id'=>$user_id]);
		//获取订单状态表中的状态id数组
		$order_status_id = array_keys($order_status);

		if($kind == 'xuni')
		{
			//表示虚拟订单
			switch($type)
			{
				case 2;//$type=2表示全部代付款订单
					$condition['order_status'] = $Order_StateModel::ORDER_WAIT_PAY;
					break;
				case 3;//$type=3表示待发货订单
					$condition['order_status'] = $Order_StateModel::ORDER_WAIT_PREPARE_GOODS;
					break;
				case 4;//$type=4表示退款，
					$condition['return_type'] = 3;
					break;
				case 5;//$type=5表示已发货订单
					$condition['order_status'] = $Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
					break;
				case 6;//$type=6表示已完成订单
					$condition['order_status'] = $Order_StateModel::ORDER_FINISH;
					break;
				case 7;//$type=7表示已取消订单
					$condition['order_status'] = $Order_StateModel::ORDER_CANCEL;
					break;
				case 1;//默认表示全部订单
					$condition['order_status:IN'] = $order_status_id;
			}
			$condition['order_is_virtual'] = 1;
		}
		else
		{
			switch($type)
			{
				case 2;//$type=2表示全部代付款订单
					$condition['order_status'] = $Order_StateModel::ORDER_WAIT_PAY;
					break;
				case 3;//$type=3表示待发货订单
					$condition['order_status:IN'] = array($Order_StateModel::ORDER_PAYED,$Order_StateModel::ORDER_WAIT_PREPARE_GOODS);
					break;
				case 4;//$type=4表示退货/款订单，包括实物退款，退货，
					$condition['return_type:IN'] = [1,2];
					break;
				case 5;//$type=5表示已发货订单
					$condition['order_status'] = $Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
					break;
				case 6;//$type=6表示已完成订单
					$condition['order_status'] = $Order_StateModel::ORDER_FINISH;
					break;
				case 7;//$type=7表示已取消订单
					$condition['order_status'] = $Order_StateModel::ORDER_CANCEL;
					break;
				case 1;//默认表示全部订单
					$condition['order_status:IN'] = $order_status_id;
			}

			//表示实物订单
			if($type != 4)
			{
				$condition['order_is_virtual'] = 0;
			}
		}
		if($type != 4)
		{
			$condition['seller_user_id'] = $user_id;
		}
		else
		{
			$condition['seller_user_id'] = $Shop_BaseModel->getOneByWhere(['user_id'=>$user_id])['shop_id'];
		}
		$order_row = ['order_create_time'=>'DESC'];
//		echo '<pre>';print_r($condition);exit;
		if($type == 4)
		{
			$data = $Order_ReturnModel->getReturnList($condition,['return_add_time'=>'DESC'],$page,$rows);
		}
		else
		{
			$condition['order_shop_hidden'] = 0;
			if($order_id)//如果$search_id存在，说明是按照订单号搜索
			{
				$data = $Order_BaseModel->getBaseList(['order_id'=>$order_id], $order_row, $page, $rows);
			}
			elseif($buyer_user_id)
			{
				$buyer_user_name = explode(',', $buyer_user_id);
				$data = $Order_BaseModel->getBaseList(['buyer_user_name:IN'=>$buyer_user_name, 'shop_id'=>$shop_info['shop_id']], $order_row, $page, $rows);
			}
			else
			{
				$data = $Order_BaseModel->getBaseList($condition, $order_row, $page, $rows);
			}
		}
		foreach($data['items'] as $key=>$val)
		{
			if($type == 4)
			{
				//取出当前退款订单商品信息，退款退货表每条记录商品只会返回一条数据
				$data['items'][$key]['goods_list'] = $Order_GoodsModel->getByWhere(['order_goods_id'=>$val['order_goods_id']]);
			}
			else
			{
				foreach($data['items'][$key]['goods_list'] as $k=>$v)
				{
					$data['items'][$key]['total_goods_num'] += $v['order_goods_num'];
				}
			}
			$user_base_info = $User_InfoModel->getOneByWhere(['user_id'=>$val['buyer_user_id']]);
			$data['items'][$key]['user_logo'] = $user_base_info['user_logo'];
			unset($user_base_info);
		}

		if($data['items'])
		{
			$this->data->addBody(-140, $data, $condition);
		}
		else
		{
			$msg = '现在还没有订单哦！';
			$status = 250;
			$this->data->addBody(-140, $data, $msg, $status);
		}
	}

	/**
	 * 搜索订单
	 *
	 * @access public
	 */
	public function searchOrder()
	{
		$search_type = request_string('search_type');
		$search_word = request_string('search_word');
		$data = [];
		if($search_type == 'buyer')//如果是按照用户昵称搜索，则返回用户id
		{
			$User_BaseModel = new User_BaseModel();
			$cond_row['user_account:LIKE'] = "%".$search_word . "%";
			$user_data = $User_BaseModel->getByWhere($cond_row);
			$user_name_arr = array_column($user_data, 'user_account');
			$user_name_str = implode(',', $user_name_arr);
			$data['id'] = $user_name_str;
			$data['type'] = 'buyer';
		}
		else
		{
			$data['id'] = $search_word;
			$data['type'] = 'order';
		}
		
		if($data)
		{
			$this->data->addBody(-140, $data);
		}
		else
		{
			$msg = 'failure:信息有误';
			$status = 250;
			$this->data->addBody(-140, $data, $msg, $status);
		}
	}

	/**
	 * 虚拟交易订单
	 *
	 * @access public
	 */
	public function virtual()
	{
		$Order_BaseModel = new Order_BaseModel();

		$condition['shop_id']           = Perm::$shopId;
		$condition['order_is_virtual']  = Order_BaseModel::ORDER_IS_VIRTUAL;
		$condition['order_shop_hidden'] = Order_BaseModel::ORDER_IS_REAL;
		$Order_BaseModel->createSearchCondi($condition);

		$order_virtual_list = $Order_BaseModel->getOrderList($condition);  //获取店铺订单列表

		fb($order_virtual_list);
		fb('order_virtual_list');
		if($order_virtual_list['items'])
		{
			$this->data->addBody(-140, $order_virtual_list);
		}
		else
		{
			$msg = '现在还没有订单哦！';
			$status = 250;
			$this->data->addBody(-140, $order_virtual_list, $msg, $status);
		}
	}

    /**
     * 门店自提订单
     *
     * @access public
     */
    public function chain()
    {
        $Order_BaseModel = new Order_BaseModel();
        $condition['chain_id:!=']       = 0;
        $data            = $Order_BaseModel->getPhysicalList($condition);
        $condition       = $data['condi'];
		$condition['chain_name'] = request_string('chain_name');

		//获取门店信息
		if($data['totalsize'] > 0) {
			$chain_base = new Chain_BaseModel;
			$chain_ids = array_unique(array_column($data['items'], 'chain_id'));
			$chain_rows = $chain_base->getBase($chain_ids);
		}

		$this->data->addBody(-140, $chain_rows);

	}

	/**
	 * 虚拟交易订单--待付款订单
	 *
	 * @access public
	 */
	public function getVirtualNew()
	{
		$condition       = array();
		$Order_BaseModel = new Order_BaseModel();
		$Order_BaseModel->createSearchCondi($condition);

		$condition['shop_id']           = Perm::$shopId;
		$condition['order_is_virtual']  = Order_BaseModel::ORDER_IS_VIRTUAL;
		$condition['order_shop_hidden'] = Order_BaseModel::ORDER_IS_REAL;
		$condition['order_status']      = Order_StateModel::ORDER_WAIT_PAY;

		$order_virtual_list = $Order_BaseModel->getOrderList($condition);  //获取店铺订单列表

		$this->view->setMet('virtual');
		include $this->view->getView();
	}

	/**
	 * 虚拟交易订单--已付款订单
	 *
	 * @access public
	 */
	public function getVirtualPay()
	{
		$condition       = array();
		$Order_BaseModel = new Order_BaseModel();
		$Order_BaseModel->createSearchCondi($condition);

		$condition['shop_id']           = Perm::$shopId;
		$condition['order_is_virtual']  = Order_BaseModel::ORDER_IS_VIRTUAL;
		$condition['order_shop_hidden'] = Order_BaseModel::ORDER_IS_REAL;
		$condition['order_status']      = Order_StateModel::ORDER_PAYED;

		$order_virtual_list = $Order_BaseModel->getOrderList($condition);  //获取店铺订单列表

		$this->view->setMet('virtual');
		include $this->view->getView();
	}

	/**
	 * 虚拟交易订单--交易成功订单
	 *
	 * @access public
	 */
	public function getVirtualSuccess()
	{
		$condition       = array();
		$Order_BaseModel = new Order_BaseModel();
		$Order_BaseModel->createSearchCondi($condition);

		$condition['shop_id']           = Perm::$shopId;
		$condition['order_is_virtual']  = Order_BaseModel::ORDER_IS_VIRTUAL;
		$condition['order_shop_hidden'] = Order_BaseModel::ORDER_IS_REAL;
		$condition['order_status']      = Order_StateModel::ORDER_FINISH;

		$order_virtual_list = $Order_BaseModel->getOrderList($condition);  //获取店铺订单列表

		$this->view->setMet('virtual');
		include $this->view->getView();
	}

	/**
	 * 虚拟交易订单--取消订单列表
	 *
	 * @access public
	 */
	public function getVirtualCancel()
	{
		$condition       = array();
		$Order_BaseModel = new Order_BaseModel();
		$Order_BaseModel->createSearchCondi($condition);

		$condition['shop_id']           = Perm::$shopId;
		$condition['order_is_virtual']  = Order_BaseModel::ORDER_IS_VIRTUAL;
		$condition['order_shop_hidden'] = Order_BaseModel::ORDER_IS_REAL;
		$condition['order_status']      = Order_StateModel::ORDER_CANCEL;

		$order_virtual_list = $Order_BaseModel->getOrderList($condition);  //获取店铺订单列表

		$this->view->setMet('virtual');
		include $this->view->getView();
	}

	/**
	 * 取消订单
	 *
	 * @access public
	 */
	public function orderCancel()
	{
		$rs_row = array();

		$Order_BaseModel = new Order_BaseModel();

		//开启事物
		$Order_BaseModel->sql->startTransactionDb();

		$order_id   = request_string('order_id');
		$user_id    = request_int('user_id');

		//获取订单详情，判断订单的当前状态与下单这是否为当前用户
		$order_base = $Order_BaseModel->getOne($order_id);

		if( ($order_base['payment_id'] == PaymentChannlModel::PAY_CONFIRM
				&& $order_base['order_status'] == Order_StateModel::ORDER_WAIT_PREPARE_GOODS) //货到付款+等待发货
			|| $order_base['order_status'] == Order_StateModel::ORDER_WAIT_PAY
			&& $order_base['seller_user_id'] == $user_id
		)
		{

			//加入取消时间
			$condition['order_status']        = Order_StateModel::ORDER_CANCEL;

			$condition['order_cancel_identity'] = Order_BaseModel::IS_SELLER_CANCEL;

			$condition['order_cancel_date'] = get_date_time();

			$edit_flag = $Order_BaseModel->editBase($order_id, $condition);
			check_rs($edit_flag, $rs_row);

			//修改订单商品表中的订单状态
			$edit_row['order_goods_status'] = Order_StateModel::ORDER_CANCEL;
			$Order_GoodsModel               = new Order_GoodsModel();
			$order_goods_id                 = $Order_GoodsModel->getKeyByWhere(array('order_id' => $order_id));

			$edit_flag1 = $Order_GoodsModel->editGoods($order_goods_id, $edit_row);
			check_rs($edit_flag1, $rs_row);

			//退还订单商品的库存
			$Goods_BaseModel = new Goods_BaseModel();
			$Chain_GoodsModel = new Chain_GoodsModel();
			if($order_base['chain_id']!=0){
				$chain_row['chain_id:='] = $order_base['chain_id'];
				$chain_row['goods_id:='] = is_array($order_goods_id)?$order_goods_id[0]:$order_goods_id;
				$chain_row['shop_id:='] = $order_base['shop_id'];
				$chain_goods = current($Chain_GoodsModel->getByWhere($chain_row));
				$chain_goods_id = $chain_goods['chain_goods_id'];
				$goods_stock['goods_stock'] = $chain_goods['goods_stock'] + 1;
				$edit_flag2 = $Chain_GoodsModel->editGoods($chain_goods_id, $goods_stock);
				check_rs($edit_flag2, $rs_row);
			}else{
				$edit_flag2 = $Goods_BaseModel->returnGoodsStock($order_goods_id);
				check_rs($edit_flag2, $rs_row);
			}

			//将需要取消的订单号远程发送给Paycenter修改订单状态
			//远程修改paycenter中的订单状态
			$key      = Yf_Registry::get('shop_api_key');
			$url         = Yf_Registry::get('paycenter_api_url');
			$shop_app_id = Yf_Registry::get('shop_app_id');
			$formvars = array();

			$formvars['order_id']    = $order_id;
			$formvars['app_id']        = $shop_app_id;


			$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=cancelOrder&typ=json', $url), $formvars);

			//如果是供货商取消进货订单，同时取消买家的订单或减少买家订单的金额
			$dist_order  =  $Order_BaseModel->getOne($order_base['order_source_id']);
			if(!empty($dist_order)){
				$dist_goods_order   =  $Order_GoodsModel -> getByWhere(array('order_id' => $dist_order['order_id']));
				if(count($dist_goods_order) == 1){
					$Order_BaseModel-> editBase($dist_order['order_id'], $condition);
					$Order_GoodsModel-> editGoods($dist_goods_order[0]['order_goods_id'], $edit_row);
					$Goods_BaseModel -> returnGoodsStock($dist_goods_order[0]['order_goods_id']);
				}else{
					foreach($dist_goods_order as $key => $value){
						if($value['order_goods_source_id'] == $order_id){
							$dist_edit_row = array();
							$dist_edit_row['order_goods_amount']       = $dist_order['order_goods_amount'] - $value['goods_price']*$value['order_goods_num'];
							$dist_edit_row['order_payment_amount']   = $dist_order['order_payment_amount'] - $value['order_goods_amount'];
							$Order_BaseModel-> editBase($dist_order['order_id'], $dist_edit_row);
							$Order_GoodsModel-> editGoods($dist_goods_order[$key]['order_goods_id'], $edit_row);
							$Goods_BaseModel -> returnGoodsStock($dist_goods_order[$key]['order_goods_id']);
						}
					}
					$formvars['payment_amount'] = $dist_edit_row['order_payment_amount'];
				}
				$formvars['order_id']    = $dist_order['order_id'];
				$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=cancelOrder&typ=json', $url), $formvars);
			}
			
			if($rs['status'] == 200)
			{
				$edit_flag3 = true;
				check_rs($edit_flag3, $rs_row);
			}else
			{
				$edit_flag3 = false;
				check_rs($edit_flag3, $rs_row);
			}
		}

		$flag = is_ok($rs_row);

		if ($flag && $Order_BaseModel->sql->commitDb())
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$Order_BaseModel->sql->rollBackDb();
			$m      = $Order_BaseModel->msg->getMessages();
			$msg    = $m ? $m[0] : __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, array(), $msg, $status);


	}

	/**
	 * 虚拟订单列表详情
	 *
	 * @access public
	 */
	public function virtualInfo()
	{
		$Goods_BaseModel       = new Goods_BaseModel();
		$Order_BaseModel       = new Order_BaseModel();
		$condition['order_id'] = request_string('order_id');

		$order_data = $Order_BaseModel->getOrderList($condition);
		$order_data = isset($order_data['items']) ? pos($order_data['items']) : array();
		$goods_list = isset($order_data['goods_list']) ? pos($order_data['goods_list']) : array();
        $data = [];
		if($goods_list){
            //取出虚拟商品有效期 common_base => common_virtual_date
            $goods_id                          = $goods_list['goods_id'];
            $common_data                       = $Goods_BaseModel->getCommonInfo($goods_id);
            $order_data['common_virtual_date'] = isset($common_data['common_virtual_date']) ? $common_data['common_virtual_date'] : '';
			$orderGoodsVirtualCodeModel = new Order_GoodsVirtualCodeModel();
			$order_data['code_data']              = $orderGoodsVirtualCodeModel->getCode($condition['order_id']);
			$data = $order_data;
			$msg = 'success';
			$status = 200;
        }
		else
		{
			$msg = 'failure';
			$status = 250;
		}
        
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 兑换虚拟订单
	 *
	 * @access public
	 */
	public function virtualExchange()
	{
		$virtual_code_id = request_string('vr_code');
        $orderGoodsVirtualCodeModel = new Order_GoodsVirtualCodeModel();
        $result = $orderGoodsVirtualCodeModel->virtualExchange($virtual_code_id);
        if($result['status'] == true){
            $msg = $result['msg'] ? __($result['msg']) : __('兑换成功'); 
            $data = $result['data'] ? $result['data'] : array(); 
            return $this->data->addBody(-140, $data, $result['msg'], 200);
        }else{
            $msg = $result['msg'] ? __($result['msg']) : __('兑换失败'); 
            return $this->data->addBody(-140, array(), $msg, 250);
        }
		
	}

	/**
	 * 实物交易订单 ==> 待付款
	 *
	 * @access public
	 */
	public function getPhysicalNew()
	{
		$Order_BaseModel       = new Order_BaseModel();
		$condi['order_status'] = Order_StateModel::ORDER_WAIT_PAY;
		$data                  = $Order_BaseModel->getPhysicalList($condi);
		$condition             = $data['condi'];

		$this->view->setMet('physical');
		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 已付款
	 *
	 * @access public
	 */
	public function getPhysicalPay()
	{
		$Order_BaseModel       = new Order_BaseModel();
		$condi['order_status'] = Order_StateModel::ORDER_PAYED;
		$data                  = $Order_BaseModel->getPhysicalList($condi);
		$condition             = $data['condi'];

		$this->view->setMet('physical');
		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 待自提
	 *
	 * @access public
	 */
	public function getPhysicalNotakes()
	{
		$Order_BaseModel       = new Order_BaseModel();
		$condi['order_status'] = Order_StateModel::ORDER_SELF_PICKUP;
		$data                  = $Order_BaseModel->getPhysicalList($condi);
		$condition             = $data['condi'];

		$this->view->setMet('physical');
		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 已发货
	 *
	 * @access public
	 */
	public function getPhysicalSend()
	{
		$Order_BaseModel       = new Order_BaseModel();
		$condi['order_status'] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
		$data                  = $Order_BaseModel->getPhysicalList($condi);
		$condition             = $data['condi'];

		$this->view->setMet('physical');
		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 已完成
	 *
	 * @access public
	 */
	public function getPhysicalSuccess()
	{
		$Order_BaseModel       = new Order_BaseModel();
		$condi['order_status'] = Order_StateModel::ORDER_FINISH;
		$data                  = $Order_BaseModel->getPhysicalList($condi);
		$condition             = $data['condi'];

		$this->view->setMet('physical');
		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 已取消
	 *
	 * @access public
	 */
	public function getPhysicalCancel()
	{
		$Order_BaseModel       = new Order_BaseModel();
		$condi['order_status'] = Order_StateModel::ORDER_CANCEL;
		$data                  = $Order_BaseModel->getPhysicalList($condi);
		$condition             = $data['condi'];

		$this->view->setMet('physical');
		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 订单详情
	 *
	 * @access public
	 */
	public function physicalInfo()
	{
		$order_id        = request_string('order_id');
		$Order_BaseModel = new Order_BaseModel();
		$User_InfoModel = new User_InfoModel();
		$User_GradeModel = new User_GradeModel();
		$data            = $Order_BaseModel->getPhysicalInfoData(array('order_id' => $order_id));
		if($data)
		{
//			if($data['order_shop_benefit'])
//			{
//				$order_shop_benefit = explode(':', $data['order_shop_benefit']);
//				$data['order_shop_benefit'] = $order_shop_benefit[1];
//				$data['order_shop_benefit_txt'] = $order_shop_benefit[0]."：";
//			}
			$user_info = $User_InfoModel->getOneByWhere(['user_id'=>$data['buyer_user_id'], 'user_name'=>$data['buyer_user_name']]);

			$user_grade_info = $User_GradeModel->getOneByWhere(['user_grade_id'=>$user_info['user_grade']]);

			$data['buyer_user_image'] = $user_info['user_logo'];
			$data['buyer_user_rate'] = '：'.$user_grade_info['user_grade_rate'].'%';
			$msg = 'success';
			$status = 200;
		}
		else
		{
			$msg = 'failure:没有此订单';
			$status = 200;
		}
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 退款、退货实物交易订单 ==> 订单详情
	 *
	 * @access public
	 */
	public function returnOrderInfo()
	{
		$order_id        = request_string('order_id');
		$Order_ReturnModel = new Order_ReturnModel();
		$Order_BaseModel = new Order_BaseModel();
		$Order_GoodsModel   = new Order_GoodsModel();
		$User_InfoModel  = new User_InfoModel();
		$order_info = $Order_GoodsModel->getOneByWhere(array('order_id' => $order_id));
		$order_base = $Order_BaseModel->getOneByWhere(['order_id'=>$order_id]);
		$data = $Order_ReturnModel->getOneByWhere(array('order_number' => $order_id));
		if($data)
		{
			$url = Yf_Registry::get('url');
			$user_info = $User_InfoModel->getOneByWhere(['user_id'=>$data['buyer_user_id']]);
			$data['buyer_user_image'] = $user_info['user_logo'];
			$data['goods_link'] = $url . '?ctl=Goods_Goods&met=snapshot&goods_id=' . $order_info['goods_id'] . '&order_id=' . $order_info['order_id'];//商品链接
			if(is_array($order_info['order_spec_info']) && $order_info['order_spec_info']){
				$data['order_spec_info'] = implode('，',$order_info['order_spec_info']);
			}
			$data['order_receiver_name'] = $order_base['order_receiver_name'];
			$data['order_receiver_address'] = $order_base['order_receiver_address'];
			$data['order_receiver_contact'] = $order_base['order_receiver_contact'];
			$data['payment_number'] = $order_base['payment_number'];
			$data['payment_name'] = $order_base['payment_name'];
			$data['payment_time'] = $order_base['payment_time'];
			$data['order_create_time'] = $order_base['order_create_time'];
			$data['order_payment_amount'] = $order_base['order_payment_amount'];
			$msg = 'success';
			$status = 200;
		}
		else
		{
			$msg = 'failure:没有此订单';
			$status = 200;
		}
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * @return bool 同意退款或退货
	 */
	public function agreeReturn()
	{
		$Order_StateModel  = new Order_StateModel();
		$Order_ReturnModel = new Order_ReturnModel();
		$order_return_id     = request_int("order_return_id");
		$return_shop_message = request_string("return_msg");
		$return              = $Order_ReturnModel->getOne($order_return_id);
		if($return['return_state'] == Order_ReturnModel::RETURN_SELLER_PASS){
			$msg    = __('已经退款，请刷新页面。');
			$status = 200;
			$this->data->addBody(-140, array(), $msg, $status);
			return false;
		}

		$rs_row = array();

		$msg = '';
		$order_finish = false;
		$shop_return_amount = 0;
		$money = 0;

		//开启事物
		$Order_ReturnModel->sql->startTransactionDb();

		$matche_row = array();
		//有违禁词
		if (Text_Filter::checkBanned($return_shop_message, $matche_row))
		{

			$msg    = __('含有违禁词');

			$status = 250;
			$this->data->addBody(-140, array(), $msg, $status);
			return false;
		}

		//判断该笔退款金额的订单是否已经结算
		$Order_BaseModel = new Order_BaseModel();
		$order_base = $Order_BaseModel->getOne($return['order_number']);

		//判断该笔订单是否已经收货，如果没有收货的话，不扣除卖家资金。已确认收货则扣除卖家资金
		if($order_base['order_status'] == $Order_StateModel::ORDER_FINISH )
		{
			$order_finish = false;

			//获取用户的账户资金资源
			$key                 = Yf_Registry::get('shop_api_key');
			$formvars            = array();
			$formvars['user_id'] = $order_base['seller_user_id'];
			$formvars['app_id'] = Yf_Registry::get('shop_app_id');
			$money_row = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Info&met=getUserResourceInfo&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
//			return $this->data->addBody(-140, array($money_row,$formvars,$order_base,$order_return_id));
			$user_money = 0;
			$user_money_frozen = 0;
			if ($money_row['status'] == '200')
			{
				$money = $money_row['data'];

				$user_money        = $money['user_money'];
				$user_money_frozen = $money['user_money_frozen'];
			}

//			return $this->data->addBody(-140, array($user_money,$user_money_frozen,$money));
			$shop_return_amount = $return['return_cash'] - $return['return_commision_fee'];

			//获取该店铺最新的结算结束日期
			$Order_SettlementModel = new Order_SettlementModel();
			$settlement_last_info = $Order_SettlementModel->getLastSettlementByShopid($order_base['shop_id'], $return['order_is_virtual']);

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

			fb($settlement_unixtime);
			fb($order_finish_unixtime);
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

			fb($pay_type);
		}
		else
		{
			$order_finish = true;
		}



		if ($return['seller_user_id'] == $order_base['shop_id'])
		{

			$shop_return_amount = sprintf("%.2f",$shop_return_amount);
			$money = sprintf("%.2f",$money);
//			return $this->data->addBody(-140, array($shop_return_amount,$money,$order_finish));
			if(($shop_return_amount <= $money) || $order_finish)
			{
				$data['return_shop_message'] = $return_shop_message;
				if ($return['return_goods_return'] == Order_ReturnModel::RETURN_GOODS_RETURN)
				{
					$data['return_state'] = Order_ReturnModel::RETURN_SELLER_PASS;
				}
				else
				{
					$data['return_state'] = Order_ReturnModel::RETURN_SELLER_GOODS;
				}
                $data['return_shop_handle'] = Order_ReturnModel::RETURN_SELLER_PASS;
				$data['return_shop_time'] = get_date_time();
				$flag                     = $Order_ReturnModel->editReturn($order_return_id, $data);
//				return $this->data->addBody(-140, array($flag));
				check_rs($flag,$rs_row);

				//如果订单为分销商采购单，扣除分销商的钱
				if($order_base['order_source_id'])
				{
					fb($return);
					fb('return');
					$dist_order = $Order_BaseModel -> getOneByWhere(array('order_id'=>$order_base['order_source_id']));

					fb($data);
					fb('data');
					fb($dist_order);
					fb('dist_order');
					if(!empty($dist_order)){
						$dist_return_order = $Order_ReturnModel->getOneByWhere(array('order_number'=>$dist_order['order_id'],'return_type'=>$return['return_type']));

						fb($dist_return_order);
						fb('$dist_return_order');
						$flag = $Order_ReturnModel->editReturn($dist_return_order['order_return_id'], $data);
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
					$formvars['goods_id'] = $return['order_goods_id'];

					$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Info&met=editReturnUserResourceInfo&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);


					$dist_rs['status'] = 200;
					if(isset($dist_return_order) && !empty($dist_return_order)){
						$key                 = Yf_Registry::get('shop_api_key');
						$formvars            = array();
						$user_id             = $return['buyer_user_id'];
						$formvars['user_id'] = $dist_order['seller_user_id'];
						$formvars['user_name'] = $dist_order['seller_user_name'];
						$formvars['money'] = ($dist_return_order['return_cash']-$dist_return_order['return_commision_fee'])*(-1);
						$formvars['order_id'] = $dist_order['order_id'];
						$formvars['goods_id'] =0;
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

		}
		else
		{
			$flag = false;
			$msg    = __('failure');
			check_rs($flag,$rs_row);
		}

		$flag = is_ok($rs_row);
		if ($flag && $Order_ReturnModel->sql->commitDb())
		{
			$status = 200;
			$msg    = __('success');
			//退款退货提醒
			$message = new MessageModel();
			$message->sendMessage('Refund return reminder', $return['buyer_user_id'], $return['buyer_user_account'], $order_id = NULL, $shop_name = NULL, 0, MessageModel::ORDER_MESSAGE);
		}
		else
		{
			$Order_ReturnModel->sql->rollBackDb();
			$status = 250;
			$msg    = $msg ? $msg : __('failure');
		}

		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);

	}

	/**
	 * @return bool 不同意退款或退货
	 */
	public function closeReturn()
	{
		$Order_ReturnModel = new Order_ReturnModel();
		$Order_StateModel    = new Order_StateModel();
		$Order_BaseModel = new Order_BaseModel();
		$Order_GoodsModel = new Order_GoodsModel();
		$order_return_id     = request_int("order_return_id");
		$return_shop_message = request_string("return_msg");
		
		$matche_row = array();
		//有违禁词
		if (Text_Filter::checkBanned($return_shop_message, $matche_row))
		{
			$data   = array();
			$msg    = __('failure');
			$status = 250;
			$this->data->addBody(-140, array(), $msg, $status);
			return false;
		}
		$return = $Order_ReturnModel->getOne($order_return_id);
		$order_base = $Order_BaseModel->getOne($return['order_number']);
//		return $this->data->addBody(-140, array($return,$order_base));
		if ($return['seller_user_id'] == $order_base['shop_id'])
		{
			$data['return_shop_message'] = $return_shop_message;
			$data['return_state']        = Order_ReturnModel::RETURN_SELLER_UNPASS;
			$data['return_shop_handle']        = Order_ReturnModel::RETURN_SELLER_UNPASS;
			$data['return_shop_time'] = get_date_time();

			$rs_row = array();
			$Order_ReturnModel->sql->startTransactionDb();
			$edit_flag = $Order_ReturnModel->editReturn($order_return_id, $data);
			check_rs($edit_flag, $rs_row);

			if ($return['order_goods_id'])
			{
				$goods_field['goods_refund_status'] = Order_GoodsModel::REFUND_NO;
				$edit_flag                          = $Order_GoodsModel->editGoods($return['order_goods_id'], $goods_field);
				check_rs($edit_flag, $rs_row);
			}
			else
			{
				$order_field['order_refund_status'] = Order_BaseModel::REFUND_NO;
				$edit_flag                          = $Order_BaseModel->editBase($return['order_number'], $order_field);
				check_rs($edit_flag, $rs_row);
			}
			$flag = is_ok($rs_row);
			if ($flag && $Order_ReturnModel->sql->commitDb())
			{
				$status = 200;
				$msg    = __('success');
			}
			else
			{
				$Order_ReturnModel->sql->rollBackDb();
				$status = 250;
				$msg    = __('failure');
			}
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

	/**
	 * @return bool 已收到货
	 */
	public function agreeGoods()
	{
		$Order_ReturnModel = new Order_ReturnModel();
		$rs_row = array();
		$order_return_id = request_int("order_return_id");
		$return          = $Order_ReturnModel->getOne($order_return_id);
		fb($return);
		fb('return');

		//开启事物
		$Order_ReturnModel->sql->startTransactionDb();

		$Order_BaseModel = new Order_BaseModel();
		$order_base = $Order_BaseModel->getOne($return['order_number']);

		if ($return['seller_user_id'] == $order_base['shop_id'])
		{
			$data['return_state'] = Order_ReturnModel::RETURN_SELLER_GOODS;
			$flag                 = $Order_ReturnModel->editReturn($order_return_id, $data);
			check_rs($flag,$rs_row);

			//如果订单为分销商采购单，扣除分销商的钱
			if($order_base['order_source_id'])
			{

				$dist_order = $Order_BaseModel -> getOneByWhere(array('order_id'=>$order_base['order_source_id']));

				fb($dist_order);
				fb('dist_order');
				if(!empty($dist_order)){
					$dist_return_order = $Order_ReturnModel->getOneByWhere(array('order_number'=>$dist_order['order_id'],'return_type'=>$return['return_type']));

					fb($dist_return_order);
					fb('$dist_return_order');
					$flag = $Order_ReturnModel->editReturn($dist_return_order['order_return_id'], $data);
					check_rs($flag,$rs_row);
				}
			}

			$flag = is_ok($rs_row);
			if ($flag && $Order_ReturnModel->sql->commitDb())
			{
				//退款退货提醒
				$message = new MessageModel();
				$message->sendMessage('Refund return reminder', $return['buyer_user_id'], $return['buyer_user_account'], $order_id = NULL, $shop_name = NULL, 0, 1);
				$status = 200;
				$msg    = __('success');
			}
			else
			{
				$status = 250;
				$msg    = __('failure');
			}
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

	/**
	 * 实物交易订单 ==> 打印发货单
	 *
	 * @access public
	 */
	public function getOrderPrint()
	{
		$Order_BaseModel   = new Order_BaseModel();
		$condi['order_id'] = request_string('order_id');

		$data = $Order_BaseModel->getOrderList($condi);
		$data = pos($data['items']);

		$data['goods_count'] = 0;
		foreach($data['goods_list'] as $key => $val)
		{
			$data['goods_count'] += $val['order_goods_num'];
			//如果商品有规格属性，则展示

			if (!empty($val['order_spec_info'])) {
				$data['goods_list'][$key]['goods_name'] .= "($val[order_spec_info])";
			}
		}

		//读取店铺印章等信息
		$shop_id = Perm::$shopId;
		$shop_BaseModel = new Shop_BaseModel();
		$shop_base = $shop_BaseModel->getBase( $shop_id );
		$shop_base = pos($shop_base);
		$shop_print_desc = $shop_base['shop_print_desc'];
		$shop_stamp = $shop_base['shop_stamp'];

		$this->view->setMet('orderPrint');
		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 设置发货
	 *
	 * @access public
	 */
	public function getOrSend()
	{
		$type      = request_string('type');
		$order_id = request_string('order_id');
		$Order_BaseModel   = new Order_BaseModel();
		$Shop_ExpressModel = new Shop_ExpressModel();
		$Order_GoodsModel  = new Order_GoodsModel();
		$User_InfoModel    = new User_InfoModel();
		$ExpressModel     = new ExpressModel();
		$Shop_ShippingAddressModel = new Shop_ShippingAddressModel();
		if ($type == 'get')
		{
			$condi['order_id'] = $order_id;
			$data              = $Order_BaseModel->getOrderList($condi);
			$data              = pos($data['items']);
			$data['buyer_logo'] = $User_InfoModel->getOneByWhere(['user_id'=>$data['buyer_user_id']])['user_logo'];
			$data['default_address'] = $Shop_ShippingAddressModel->getOneByWhere(['shop_id'=>$data['shop_id'], 'shipping_address_default'=>1]);
			if($data['order_shipping_express_id'])
			{
				$data['order_shipping_name'] = $ExpressModel->getOneByWhere(['express_id'=>$data['order_shipping_express_id']])['express_name'];
			}
			if($data)
			{
				$msg = 'success';
				$status = 200;
			}
			else
			{
				$msg = 'failure:没有数据';
				$status = 250;
			}

			$this->data->addBody(-140, $data, $msg, $status);
		}
		else
		{
			//判断该笔订单是否是自己的单子
			$order_base = $Order_BaseModel->getOne($order_id);
			$rs_row = array();

			//开启事物
			$Order_BaseModel->sql->startTransactionDb();

            //判断账号是否可以发货
//            $check_send = $this->checkSend($order_base['seller_user_id'],$order_base['shop_id']);
			if($order_base['order_status'] < Order_StateModel::ORDER_WAIT_CONFIRM_GOODS)
			{
				//设置发货
				$update_data['order_status']              = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
				$update_data['order_shipping_express_id'] = request_int('order_shipping_express_id');
				$update_data['order_shipping_code']       = request_string('order_shipping_code');
				$update_data['order_shipping_message']    = request_string('order_shipping_message');
				$update_data['order_seller_message']      = request_string('order_seller_message');

				//配送时间 收货时间
				$current_time                       = time();
				$confirm_order_time                 = Yf_Registry::get('confirm_order_time');
				$update_data['order_shipping_time'] = date('Y-m-d H:i:s', $current_time);
				$update_data['order_receiver_date'] = date('Y-m-d H:i:s', $current_time + $confirm_order_time);

				$edit_flag = $Order_BaseModel->editBase($order_id, $update_data);
				check_rs($edit_flag,$rs_row);

				$order_list = $Order_GoodsModel -> getByWhere(array('order_id' => $order_base['order_source_id'],'order_goods_source_id' => ''));//查看不是分销商品的订单
				if(!empty($order_list) && $order_base['order_source_id']){
					foreach ($order_list as $key => $value) {
						$edit_flag1 = $Order_GoodsModel -> editGoods($key,array('order_goods_source_ship' => $update_data['order_shipping_code'].'-'.$update_data['order_shipping_express_id']));
						check_rs($edit_flag1,$rs_row);
					}
				}
				//如果为采购单，改变 "买家<-->分销商" 订单状态
				if($order_base['order_source_id'])
				{
					$dist_order = $Order_BaseModel->getOneByWhere(array('order_id'=>$order_base['order_source_id']));
					if(!empty($dist_order)){
						/*
                            只有订单中不含分销商自己的商品时改变订单状态，如果含有分销商自己的商品，
                            供货商发货改变订单状态，分销商自己就发不了货了.
                            所以如果订单中含有分销商自己的商品，只有分销商的商品发货了，才能改变订单状态
                        */
						if(empty($order_list)){
							$dist_flag = $Order_BaseModel->editBase($dist_order['order_id'], $update_data);
							check_rs($dist_flag,$rs_row);
						}
						//买家商品订单表里添加物流单号
						$order_goods_id = $Order_GoodsModel->getKeyByWhere(array('order_goods_source_id' => $order_id));
						$edit_flag2 = $Order_GoodsModel -> editGoods($order_goods_id,array('order_goods_source_ship' => $update_data['order_shipping_code'].'-'.$update_data['order_shipping_express_id']));

						check_rs($edit_flag2,$rs_row);
					}
				}

				$message = new MessageModel();
				//远程修改paycenter中的订单信息
				$key      = Yf_Registry::get('shop_api_key');
				$url         = Yf_Registry::get('paycenter_api_url');
				$shop_app_id = Yf_Registry::get('shop_app_id');
				$formvars = array();

				$formvars['order_id']    = $order_id;
				$formvars['app_id']        = $shop_app_id;

				$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=sendOrderGoods&typ=json', $url), $formvars);
				if($rs['status'] == 200)
				{
					$rs_flag = true;
					check_rs($rs_flag,$rs_row);
				}
				else
				{
					$rs_flag = false;
					check_rs($rs_flag,$rs_row);
				}
				if(!empty($dist_order) && isset($dist_flag) && $dist_flag){//如果为采购单，改变 "买家<-->分销商" 订单状态
					$message->sendMessage('ordor_complete_shipping', $dist_order['buyer_user_id'], $dist_order['buyer_user_name'], $dist_order['order_id'], $dist_order['shop_name'], 0, MessageModel::ORDER_MESSAGE);
					$formvars['order_id']    = $dist_order['order_id'];
					$formvars['app_id']        = $shop_app_id;

					$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=sendOrderGoods&typ=json', $url), $formvars);
					if($rs['status'] == 200)
					{
						$rs_flag = true;
						check_rs($rs_flag,$rs_row);
					}
					else
					{
						$rs_flag = false;
						check_rs($rs_flag,$rs_row);
					}
				}
			}
			else
			{
				$flag = false;
				check_rs($flag,$rs_row);
			}
			$flag = is_ok($rs_row);

			if ($flag && $Order_BaseModel->sql->commitDb())
			{
				//发送站内信
				$message = new MessageModel();
				$message->sendMessage('ordor_complete_shipping', $order_base['buyer_user_id'], $order_base['buyer_user_name'], $order_id, $order_base['shop_name'], 0, MessageModel::ORDER_MESSAGE);

				$msg    = __('success');
				$status = 200;
			}
			else
			{
				$Order_BaseModel->sql->rollBackDb();
				$msg    = __('failure');
				$status = 250;
			}

			$this->data->addBody(-140, array(), $msg, $status);
		}
	}

	//获取默认物流公司列表
	public function getLogList()
	{
		$data = [];
		$ExpressModel     = new ExpressModel();
		$Shop_ExpressModel     = new Shop_ExpressModel();
		$Waybill_TplModel = new Waybill_TplModel();
		$Order_BaseModel   = new Order_BaseModel();
		$order_id = request_string('order_id');

		$shop_id = $Order_BaseModel->getOneByWhere(['order_id'=>$order_id])['shop_id'];
		$default_shop_express = $Shop_ExpressModel->getByWhere(array('shop_id' => $shop_id),array('express_id'=>'asc'));

		if (!empty($default_shop_express))
		{
			$default_express_ids = array_column($default_shop_express, 'express_id');
			$default_waybill_ids = array_column($default_shop_express, 'waybill_tpl_id');

			//店铺支持的快递公司的信息
			$express_data  = $ExpressModel->getExpress($default_express_ids);
			//店铺支持的所有运单的信息
			$way_bill_data = $Waybill_TplModel->getByWhere(array('waybill_tpl_id:IN' => $default_waybill_ids));



			$way_bill_list = array();
			foreach ($way_bill_data as $value){
				$way_bill_list[$value['waybill_tpl_id']][$value['express_id']] = $value;
			}

			foreach ($default_shop_express as $key => $val)
			{
				if ( empty($express_data[$val['express_id']]) )
				{
					unset($default_shop_express[$key]); continue;
				}
				$default_shop_express[$key]['express_name'] = $express_data[$val['express_id']]['express_name'];
				$default_shop_express[$key]['way_bill'] = $way_bill_list[$val['waybill_tpl_id']][$val['express_id']] ? $way_bill_list[$val['waybill_tpl_id']][$val['express_id']] : array();
			}
			if(is_array($default_shop_express) && $default_shop_express){
				$data = array_values($default_shop_express);
			}
			if($data)
			{
				$msg = 'success';
				$status = 200;
			}
			else
			{
				$msg = 'failure';
				$status = 250;
			}
		}
		else
		{
			$data['express_id'] = 0;
			$msg = 'failure';
			$status = 250;
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}


	public function addLogInfo()
	{
		$Order_BaseModel   = new Order_BaseModel();
		$order_id 				  = request_string('order_id');
		$update_data['order_shipping_express_id'] = request_int('order_shipping_express_id');
		$update_data['order_shipping_code']       = request_string('order_shipping_code');

		//配送时间 收货时间
		$current_time                       = time();
		$confirm_order_time                 = Yf_Registry::get('confirm_order_time');
		$update_data['order_shipping_time'] = date('Y-m-d H:i:s', $current_time);
		$update_data['order_receiver_date'] = date('Y-m-d H:i:s', $current_time + $confirm_order_time);

		$edit_flag = $Order_BaseModel->editBase($order_id, $update_data);
		if($edit_flag)
		{
			$msg = 'success';
			$status = 200;
		}
		else
		{
			$msg = 'failure';
			$status = 250;
		}
		$data = [];
		$this->data->addBody(-140, $data, $msg, $status);
	}
	
	/**
	 * 新增发货地址
	 * 修改发货地址
	 * @access public
	 */
	public function addAddress()
	{
		$Shop_BaseModel   = new Shop_BaseModel();
		$user_id = request_int('user_id');
		$shop_base_info = $Shop_BaseModel->getOneByWhere(['user_id'=>$user_id]);
		$field_row                                 = array();
		$field_row['shop_id']                      = $shop_base_info['shop_id'];
		$field_row['shipping_address_contact']     = request_string('shipping_address_contact');    //联系人
		$field_row['shipping_address_phone']       = request_string('shipping_address_phone');        //联系方式
		$field_row['shipping_address_address']     = request_string('address_area');    		//详细地址
		$field_row['shipping_address_province_id'] = request_int('province_id');                //省份ID
		$field_row['shipping_address_city_id']     = request_int('city_id');                        //城市ID
		$field_row['shipping_address_area_id']     = request_int('area_id');                        //地区ID
		$field_row['shipping_address_area']        = request_string('shipping_address_address');    //地址信息
		$field_row['shipping_address_company']     = request_string('shipping_address_company');    //公司
		$field_row['shipping_address_time']        = get_date_time();                                  //添加时间
		$field_row['shipping_address_default']     = request_int('shipping_address_default');          //是否设为默认地址
		$order_id = request_string('order_id');          		//订单id
		$Shop_ShippingAddressModel = new Shop_ShippingAddressModel();
		$Order_BaseModel   = new Order_BaseModel();
		$data = array();
		//当前用户是否开店成功
		if($shop_base_info && $shop_base_info['shop_status'] == 3)
		{
			//如果修改当前发货地址为默认地址，则先将店铺对应的发货地址修改为不是默认地址
			if(request_int('shipping_address_default') == 1)
			{
				$shop_address_data = $Shop_ShippingAddressModel->getByWhere(['shop_id'=>$shop_base_info['shop_id'], 'shipping_address_default'=>1]);

				if(count($shop_address_data) >= 1)
				{
					foreach($shop_address_data as $k=>$v)
					{
						$Shop_ShippingAddressModel->updateAddress($v['shipping_address_id'], ['shipping_address_default'=>0]);
					}
				}
			}

			//新增地址
			if (request_string('op') == 'save')
			{
				$address_data = $Shop_ShippingAddressModel->getByWhere(['shop_id'=>$shop_base_info['shop_id']]);
				//如果没有发货地址，则将新添加的地址设为默认地址
				if(!$address_data)
				{
					$field_row['shipping_address_default'] = 1;
				}
				$flag = $Shop_ShippingAddressModel->addAddress($field_row, true);

				if ($flag !== false)
				{
					$edit_rows['order_seller_name'] = $field_row['shipping_address_contact'];
					$edit_rows['order_seller_address'] = $field_row['shipping_address_area'].' '.$field_row['shipping_address_address'];
					$edit_rows['order_seller_contact'] = $field_row['shipping_address_phone'];
					$flag1 = $Order_BaseModel->editBase($order_id, $edit_rows);
					$msg    = __('success');
					$status = 200;
				}
				else
				{
					$msg    = __('failure');
					$status = 250;
				}
				$this->data->addBody(-140, $data, $msg, $status);
			}

			//修改地址
			if (request_string('op') == 'edit')
			{
				$ship_id = request_string('ship_id');  //发货地址id
				//开启事务
				$Shop_ShippingAddressModel->sql->startTransactionDb();
				$ship_info = $Shop_ShippingAddressModel->getOneByWhere(['shipping_address_id'=>$ship_id]);
				//删除发货地址时同步修改未发货订单此发货地址为最新修改地址
				$update_flag = $Shop_ShippingAddressModel->updateAddress($ship_id, $field_row);
				if($update_flag)
				{
					$edit_flag_arr = [];
					$cond_row['order_seller_name'] = $ship_info['shipping_address_contact'];
					$cond_row['order_seller_address'] = $ship_info['shipping_address_area'].' '.$ship_info['shipping_address_address'];
					$cond_row['order_seller_contact'] = $ship_info['shipping_address_phone'];
					$cond_row['order_status:IN'] = [Order_StateModel::ORDER_PAYED, Order_StateModel::ORDER_WAIT_PREPARE_GOODS];
					$Order_BaseModel = new Order_BaseModel();
					$order_data = $Order_BaseModel->getByWhere($cond_row);
					if($order_data)
					{
						$order_ids = array_values(array_column($order_data, 'order_id'));
						$edit_rows['order_seller_name'] = $field_row['shipping_address_contact'];
						$edit_rows['order_seller_address'] = $field_row['shipping_address_area'].' '.$field_row['shipping_address_address'];
						$edit_rows['order_seller_contact'] = $field_row['shipping_address_phone'];
						foreach($order_ids as $key=>$val)
						{
							$edit_flag_arr[$key] = $Order_BaseModel->editBase($val, $edit_rows);
						}
						//判断是否都修改成功
						$edit_flag_arr = array_unique($edit_flag_arr);
						if(count($edit_flag_arr) == 1 && $edit_flag_arr[0] && $Shop_ShippingAddressModel->sql->commitDb())
						{
							$msg = __('success');
							$status = 200;
						}
						else
						{
							$Shop_ShippingAddressModel->sql->rollBackDb();
							$msg = __('failure');
							$status = 250;
						}
					}
					else
					{
						$Shop_ShippingAddressModel->sql->commitDb();
						$msg = 'success';
						$status = 200;
					}
				}
				else
				{
					$Shop_ShippingAddressModel->sql->rollBackDb();
					$msg = __('failure');
					$status = 250;
				}
				$data = [];
				return $this->data->addBody(-140, $data, $msg, $status);
			}
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
			if($shop_base_info)
			{
				if($shop_base_info['shop_status'] == 1 || $shop_base_info['shop_status'] == 2)
				{
					$data['state'] = '已经申请，在审核中';
				}
				//判断用户是否进行了运费模板设置
				$Transport_TemplateModle = new Transport_TemplateModel();
				$template_info = $Transport_TemplateModle->getByWhere(['shop_id'=>$shop_base_info['shop_id']]);
				if(!$template_info)
				{
					$data['state'] = '由于老数据问题未进行运费模板设置';
				}
				
			}
			else
			{
				$data['state'] = '未申请开店';
			}

			return $this->data->addBody(-140, $data, $msg, $status);
		}

	}


	public function editOrderSellerAddress()
	{
		$order_id = request_string('order_id');
		$shipping_address_id = request_int('shipping_address_id');
		$Order_BaseModel   = new Order_BaseModel();
		$Shop_ShippingAddressModel = new Shop_ShippingAddressModel();
		$shipping_address = $Shop_ShippingAddressModel->getOneByWhere(['shipping_address_id'=>$shipping_address_id]);
		$edit_rows['order_seller_name'] = $shipping_address['shipping_address_contact'];
		$edit_rows['order_seller_address'] = $shipping_address['shipping_address_area'].' '.$shipping_address['shipping_address_address'];
		$edit_rows['order_seller_contact'] = $shipping_address['shipping_address_phone'];
		$flag = $Order_BaseModel->editBase($order_id, $edit_rows);
		if($flag)
		{
			$msg = 'success';
			$status = 200;
		}
		else
		{
			$msg = 'failure';
			$status = 250;
		}
		$data = [$order_id,$edit_rows,$flag];
		return $this->data->addBody(-140, $data, $msg, $status);
	}
	
	
	/**
	 * 根据发货表id获取发货地址信息
	 * @access public
	 */
	public function getOrderSellerAddress()
	{
		$data = [];
		$shipping_address_id = request_int('shipping_address_id');
		$Shop_ShippingAddressModel = new Shop_ShippingAddressModel();
		$data = $Shop_ShippingAddressModel->getOneByWhere(['shipping_address_id'=>$shipping_address_id]);
		
		if($data)
		{
			$msg = 'success';
			$status = 200;
		}
		else
		{
			$msg = 'failure';
			$status = 250;
		}
		
		return $this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 根据发货表id删除发货地址信息
	 * @access public
	 */
	public function delAddress()
	{
		$shipping_address_id = request_int('shipping_address_id');
		$Shop_ShippingAddressModel = new Shop_ShippingAddressModel();
		$ship_info = $Shop_ShippingAddressModel->getOneByWhere(['shipping_address_id'=>$shipping_address_id]);
		//开启事务
		$Shop_ShippingAddressModel->sql->startTransactionDb();
		//删除发货地址时同步修改未发货订单此发货地址为空
		$flag = $Shop_ShippingAddressModel->removeAddress($shipping_address_id);
		if($flag)
		{
			$edit_flag_arr = [];
			$cond_row['order_seller_name'] = $ship_info['shipping_address_contact'];
			$cond_row['order_seller_address'] = $ship_info['shipping_address_area'].' '.$ship_info['shipping_address_address'];
			$cond_row['order_seller_contact'] = $ship_info['shipping_address_phone'];
			$cond_row['order_status:IN'] = [Order_StateModel::ORDER_PAYED, Order_StateModel::ORDER_WAIT_PREPARE_GOODS];
			$Order_BaseModel = new Order_BaseModel();
			$order_data = $Order_BaseModel->getByWhere($cond_row);
			if($order_data)
			{
				$order_ids = array_values(array_column($order_data, 'order_id'));
				$edit_row['order_seller_name'] = '';
				$edit_row['order_seller_address'] = '';
				$edit_row['order_seller_contact'] = '';
				foreach($order_ids as $key=>$val)
				{
					$edit_flag_arr[$key] = $Order_BaseModel->editBase($val, $edit_row);
				}
				//判断是否都修改成功
				$edit_flag_arr = array_unique($edit_flag_arr);
				if(count($edit_flag_arr) == 1 && $edit_flag_arr[0] && $Shop_ShippingAddressModel->sql->commitDb())
				{
					$msg = 'success';
					$status = 200;
				}
				else
				{
					$Shop_ShippingAddressModel->sql->rollBackDb();
					$msg = 'failure';
					$status = 250;
				}
			}
			else
			{
				$Shop_ShippingAddressModel->sql->commitDb();
				$msg = 'success';
				$status = 200;
			}

		}
		else
		{
			$Shop_ShippingAddressModel->sql->rollBackDb();
			$msg = 'failure';
			$status = 250;
		}
		$data = [];

		return $this->data->addBody(-140, $data, $msg, $status);
	}

	
	/**
	 * 发货设置
	 * 店铺发货地址列表
	 * @access public
	 */
	public function getAddressList()
	{
		$user_id = request_int('user_id');
		$page = request_int('page');
		$rows = request_int('rows');
		$Shop_BaseModel   = new Shop_BaseModel();
		$Shop_ShippingAddressModel = new Shop_ShippingAddressModel();

		$cond_row['shop_id'] = $Shop_BaseModel->getOneByWhere(['user_id'=>$user_id])['shop_id'];
		if($cond_row['shop_id'])
		{
			$data                = $Shop_ShippingAddressModel->getBaseList($cond_row, array('shipping_address_default' => 'desc'),$page, $rows);
		}
		else
		{
			$data = [];
		}
		if($data)
		{
			$msg = 'success';
			$status = 200;
		}
		else
		{
			$msg = 'failure';
			$status = 250;
		}
		$this->data->addBody(-140, $data, $msg, $status);
	}
	
	
	/**
	 * 实物交易订单 ==> 选择发货地址
	 *
	 * @access public
	 */
	public function chooseSendAddress()
	{
		$typ = request_string('typ');

		if ($typ == 'e')
		{
			$shop_id                   = request_int('shop_id');
			$Shop_ShippingAddressModel = new Shop_ShippingAddressModel();
			$address_list              = $Shop_ShippingAddressModel->getByWhere(array('shop_id' => $shop_id));
			$address_list              = array_values($address_list);
			foreach ($address_list as $key => $val)
			{
				$address_list[$key]['address_info']  = $val['shipping_address_area'] . " " . $val['shipping_address_address'];
				$address_list[$key]['address_value'] = $val['shipping_address_contact'] . "&nbsp" . $val['shipping_address_phone'] . "&nbsp" . $val['shipping_address_area'] . "&nbsp" . $val['shipping_address_address'];
			}

			include $this->view->getView();
		}
		else
		{
			$order_id     = request_string('order_id');
			$send_address = request_row('send_address');

			$Order_BaseModel                     = new Order_BaseModel();
			$update_data['order_seller_name']    = $send_address['order_seller_name'];
			$update_data['order_seller_address'] = $send_address['order_seller_address'];
			$update_data['order_seller_contact'] = $send_address['order_seller_contact'];
			$flag                                = $Order_BaseModel->editBase($order_id, $update_data);

			if ($flag || $flag === 0)
			{
				$msg    = __('设置成功');
				$status = 200;
			}
			else
			{
				$msg    = __('设置失败');
				$status = 250;
			}

			$this->data->addBody(-140, array(), $msg, $status);
		}

	}

	/**
	 * 实物交易订单 ==> 选择发货地址
	 *
	 * @access public
	 */
	public function editBuyerAddress()
	{
		$typ = request_string('typ');

		if ($typ == 'e')
		{
			include $this->view->getView();
		}
		else
		{
			$Order_BaseModel = new Order_BaseModel();

			$order_id = request_string('order_id');

			$update_data['order_receiver_name']    = request_string('order_receiver_name');
			$update_data['order_receiver_address'] = request_string('order_receiver_address');
			$update_data['order_receiver_contact'] = request_string('order_receiver_contact');

			$flag = $Order_BaseModel->editBase($order_id, $update_data);

			if ($flag)
			{
				$update_data['receiver_info'] = $update_data['order_receiver_name'] . "&nbsp;" . $update_data['order_receiver_address'] . "&nbsp;" . $update_data['order_receiver_contact'];
				$msg                          = __('success');
				$status                       = 200;
			}
			else
			{
				$msg    = __('failure');
				$status = 250;
			}

			$this->data->addBody(-140, $update_data, $msg, $status);
		}

	}


	/**
	 * 商家中心首页不同状态订单数目
	 *
	 * @access public
	 */
	public function getOrderNum()
	{
		$order_type = request_int('order_type');

		$orderBaseModel = new Order_BaseModel();
		$orderReturn = new Order_ReturnModel();

		//待付款订单
		$condi                 = array();
		$condi['shop_id']      = Perm::$shopId;
		$condi['order_status'] = Order_StateModel::ORDER_WAIT_PAY;
		$wait_pay_data         = $orderBaseModel->getByWhere($condi);

		//待发货订单
		$condi                     = array();
		$condi['shop_id']          = Perm::$shopId;
		$condi['order_status:IN']  = array(
			Order_StateModel::ORDER_PAYED,
			Order_StateModel::ORDER_WAIT_PREPARE_GOODS
		);
		$payed_data                = $orderBaseModel->getByWhere($condi);

		//退款订单
		$condi                        = array();
		$condi['seller_user_id']             = Perm::$shopId;
		$condi['return_state'] = Order_ReturnModel::RETURN_WAIT_PASS;
		$condi['return_type:!='] = Order_ReturnModel::RETURN_TYPE_GOODS;
		$refund_data                  = $orderReturn->getByWhere($condi);

		//退货订单
		$condi                        = array();
		$condi['seller_user_id']             = Perm::$shopId;
		$condi['return_state'] = Order_ReturnModel::RETURN_WAIT_PASS;
		$condi['return_type'] = Order_ReturnModel::RETURN_TYPE_GOODS;
		$return_data                  = $orderReturn->getByWhere($condi);


		$data['wait_pay_num'] = count($wait_pay_data);
		$data['payed_num']    = count($payed_data);
		$data['refund_num']   = count($refund_data);
		$data['return_num']   = count($return_data);

		$this->data->addBody(-140, $data);

	}

    /**
     * 门店自提订单 ==> 待付款
     *
     * @access public
     */
    public function getChainNew()
    {
        $Order_BaseModel       = new Order_BaseModel();
        $condi['order_status'] = Order_StateModel::ORDER_WAIT_PAY;
        $condi['chain_id:!=']       = 0;
        $data                  = $Order_BaseModel->getPhysicalList($condi);
        $condition             = $data['condi'];

        $this->view->setMet('chain');
        include $this->view->getView();
    }

    /**
     * 门店自提订单 ==> 待自提
     *
     * @access public
     */
    public function getChainNotakes()
    {
        $Order_BaseModel       = new Order_BaseModel();
        $condi['order_status'] = Order_StateModel::ORDER_SELF_PICKUP;
        $condi['chain_id:!=']       = 0;
        $data                  = $Order_BaseModel->getPhysicalList($condi);
        $condition             = $data['condi'];

        $this->view->setMet('chain');
        include $this->view->getView();
    }

    /**
     * 门店自提订单 ==> 已完成
     *
     * @access public
     */
    public function getChainSuccess()
    {
        $Order_BaseModel       = new Order_BaseModel();
        $condi['order_status'] = Order_StateModel::ORDER_FINISH;
        $condi['chain_id:!=']       = 0;
        $data                  = $Order_BaseModel->getPhysicalList($condi);
        $condition             = $data['condi'];

        $this->view->setMet('chain');
        include $this->view->getView();
    }

    /**
     * 门店自提订单 ==> 已取消
     *
     * @access public
     */
    public function getChainCancel()
    {
        $Order_BaseModel       = new Order_BaseModel();
        $condi['order_status'] = Order_StateModel::ORDER_CANCEL;
        $condi['chain_id:!=']       = 0;
        $data                  = $Order_BaseModel->getPhysicalList($condi);
        $condition             = $data['condi'];

        $this->view->setMet('chain');
        include $this->view->getView();
    }

    /**
     * 门店自提订单 ==> 订单详情
     *
     * @access public
     */public function chainInfo()
    {
        $order_id        = request_string('order_id');
        $Order_BaseModel = new Order_BaseModel();
        $data            = $Order_BaseModel->getChainInfoData(array('order_id' => $order_id));

		//获取门店信息
		$chain_id = $data['chain_id'];
		$chain_model = new Chain_BaseModel;
		$chain_data = $chain_model->getChainInfo($chain_id);
        include $this->view->getView();
    }

	/**
	 * 修改订单金额
	 *
	 * @access public
	 */
	public function cost()
	{

		$Order_BaseModel = new Order_BaseModel();

		$order_id = request_string('order_id');

		$order_base = $Order_BaseModel->getBase($order_id);  //获取店铺订单列表
		$order_base = $order_base[$order_id];
		fb($order_base);
		//获取订单商品信息
		$Order_GoodsModel = new Order_GoodsModel();
		$order_goods_row = $Order_GoodsModel->getGoodsListByOrderId($order_id);
		$data = $order_goods_row['items'];
		fb($data);
		include $this->view->getView();
	}

	public function editCost()
	{
		$order_id = request_string('order_id');
		$product_row  = request_row('product_id');
		$shipping = request_float('shipping');
		$goods_edit_flag = false;
		$shipping_edit_flag = false;
		$flag = true;
//		echo '<pre>';print_r($data);exit;
		$Order_GoodsModel = new Order_GoodsModel();

		//开启事物
		$Order_GoodsModel->sql->startTransactionDb();

		$order_goods_row = $Order_GoodsModel->getGoodsListByOrderId($order_id);
		//订单商品列表
		$data = $order_goods_row['items'];

		$Order_BaseModel = new Order_BaseModel();
		//订单详情
		$order_base = $Order_BaseModel->getBase($order_id);
		$order_base = $order_base[$order_id];

		$Goods_CatModel = new Goods_CatModel();
		$Order_GoodsSnapshot = new Order_GoodsSnapshot();

		//1.修改订单商品表中商品的价格
		$order_edit_row = array();
		$order_goods_amount = 0;    //商品总价（不包含运费）
		$order_payment_amount = 0;  //实际应付金额（商品总价 + 运费）
		$order_discount_fee = 0;   //优惠价格
		$order_commission_fee = 0;   //交易佣金

		//判断该订单是否为待付款订单
		if($order_base['order_status'] == Order_StateModel::ORDER_WAIT_PAY)
		{
			foreach ($data as $key => $val)
			{
				//判断商品价格是否被修改了
				if($val['order_goods_payment_amount'] != $product_row[$val['goods_id']])
				{
                    if(intval($product_row[$val['goods_id']]) > intval($val['goods_price'])){
                        $flag = false;
                    }else{
                        $goods_edit_flag = true;

                        $edit_row = array();

                        //每件商品实际支付金额
                        $edit_row['order_goods_payment_amount'] = $product_row[$val['goods_id']];
                        //手工调整金额
                        $edit_row['order_goods_adjust_fee'] = $val['order_goods_payment_amount'] - $product_row[$val['goods_id']];
                        //商品实际支付总金额
                        $edit_row['order_goods_amount'] = $product_row[$val['goods_id']] * $val['order_goods_num'];
                        //优惠价格
                        $edit_row['order_goods_benefit'] = $val['order_goods_benefit'] + $edit_row['order_goods_adjust_fee'];

                        //重新计算该件商品的佣金
                        //获取分类佣金
                        $cat_base = $Goods_CatModel->getOne($val['goods_class_id']);
                        if ($cat_base)
                        {
                            $cat_commission = $cat_base['cat_commission'];
                        }
                        else
                        {
                            $cat_commission = 0;
                        }

                        //订单商品的佣金
                        $edit_row['order_goods_commission'] = number_format(($product_row[$val['goods_id']] * $cat_commission / 100), 2, '.', '');

                        $Order_GoodsModel->editGoods($val['order_goods_id'], $edit_row);

                        $order_goods_amount += $edit_row['order_goods_amount'];
                        $order_discount_fee += $edit_row['order_goods_benefit'];
                        $order_commission_fee += $edit_row['order_goods_commission'];


                        //2.修改快照表
                        $array = array();
                        $array['order_id'] = $order_id;
                        $array['goods_id'] = $val['goods_id'];
                        $snapshot_id = $Order_GoodsSnapshot->getKeyByWhere($array);

                        $edit_snapshot_row = array();
                        $edit_snapshot_row['goods_price'] = $product_row[$val['goods_id']];
                        $edit_snapshot_row['freight'] = $shipping;
                        $Order_GoodsSnapshot->editSnapshot($snapshot_id, $edit_snapshot_row);
                    }


				}
				else
				{
					$order_goods_amount += $val['order_goods_amount'];
					$order_discount_fee += $val['order_goods_benefit'];
					$order_commission_fee += $val['order_goods_commission'];
				}

			}

			//3.修改订单表
			//判断运费是否改变
            if($order_base['order_shipping_fee'] < $shipping){
                $flag = false;
            }else{
                if($order_base['order_shipping_fee'] != $shipping)
                {
                    $shipping_edit_flag = true;
                    $order_edit_row['order_shipping_fee'] = $shipping;
                }
            }


			//如果修改了商品价格或者修改了运费则需要修改订单表
			if($shipping_edit_flag || $goods_edit_flag)
			{
				//商品总价（不包含运费）
				$order_edit_row['order_goods_amount'] = $order_goods_amount;
				//应付金额（商品总价 + 运费）
				$order_edit_row['order_payment_amount'] = $order_goods_amount + $shipping;
				//优惠价格
				$order_edit_row['order_discount_fee'] = $order_discount_fee;
				//交易佣金
				$order_edit_row['order_commission_fee'] = $order_commission_fee;

				$Order_BaseModel->editBase($order_id,$order_edit_row );


				//远程修改paycenter中的订单数据
				//生成合并支付订单
				$key      = Yf_Registry::get('shop_api_key');
				$url         = Yf_Registry::get('paycenter_api_url');
				$shop_app_id = Yf_Registry::get('shop_app_id');
				$formvars = array();

				$formvars['order_id']    = $order_id;
				$formvars['uorder_id']     = $order_base['payment_number'];
				$formvars['app_id']        = $shop_app_id;
				$formvars['edit_row']  = $order_edit_row;

				fb($formvars);

				$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=editOrderCost&typ=json', $url), $formvars);

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
		else
		{
			$flag = false;
		}

		if ($flag && $Order_GoodsModel->sql->commitDb())
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$Order_GoodsModel->sql->rollBackDb();
			$m      = $Order_GoodsModel->msg->getMessages();
			$msg    = $m ? $m[0] : __('failure');
			$status = 250;
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}
    /**
     *  获取隐藏的实物订单
     * @author Str
     */
    public function getPhysicalHideOrder(){
        $Order_BaseModel       = new Order_BaseModel();
        $condi['order_shop_hidden'] = $Order_BaseModel::IS_SELLER_HIDDEN;
        $data                  = $Order_BaseModel->getPhysicalList($condi);
        $condition             = $data['condi'];
        $this->view->setMet('physical');
        include $this->view->getView();
    }

    public function getVirtualHideOrder(){
        $Order_BaseModel = new Order_BaseModel();
        $condition['order_shop_hidden'] = $Order_BaseModel::IS_SELLER_HIDDEN;
		$condition['shop_id']           = Perm::$shopId;
		$condition['order_is_virtual']  = Order_BaseModel::ORDER_IS_VIRTUAL;
		$Order_BaseModel->createSearchCondi($condition);
//print_r($condition);exit;
		$order_virtual_list = $Order_BaseModel->getOrderList($condition);  //获取店铺订单列表
        $this->view->setMet('virtual');
		include $this->view->getView();
    }
    
    public function getChainHideOrder(){
        $Order_BaseModel = new Order_BaseModel();
        $condition['order_shop_hidden'] = $Order_BaseModel::IS_SELLER_HIDDEN;
        $condition['chain_id:!=']       = 0;
        $data            = $Order_BaseModel->getPhysicalList($condition);
        $condition       = $data['condi'];
        $this->view->setMet('chain');
		include $this->view->getView();
    }
    
    
    /**
	 * 删除订单
	 *
	 * @author     Str
	 */
	public function hideOrder()
	{
		$order_id = request_string('order_id');
		$user_id = request_int('user_id');
		$op       = request_string('op');

		$edit_row = array();
        $flag = false;
		$Order_BaseModel = new Order_BaseModel();
		$order_base = $Order_BaseModel->getOne($order_id);
		
		//判断订单状态是否是已完成（6）或者已取消（7）状态
		if($order_base['order_status'] >= Order_StateModel::ORDER_FINISH)
		{
			//判断当前用户是否是卖家
			if($order_base['seller_user_id'] == $user_id)
			{
				if ($op == 'del')
				{
					$edit_row['order_shop_hidden'] = Order_BaseModel::IS_SELLER_REMOVE;
				}
				else
				{
					$edit_row['order_shop_hidden'] = Order_BaseModel::IS_SELLER_HIDDEN;
				}
			}
		}

		$flag = $Order_BaseModel->editBase($order_id, $edit_row);

		if ($flag)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, array(), $msg, $status);
	}

	/**
	 * 还原回收站中的订单
	 *
	 * @author     Str
	 */
	public function restoreOrder()
	{
		$order_id = request_string('order_id');
		$user     = request_string('user');

		$edit_row = array();
		$flag = false;
		$Order_BaseModel = new Order_BaseModel();
		
		if ($user == 'seller')
		{
			$edit_row['order_shop_hidden'] = Order_BaseModel::NO_SELLER_HIDDEN;
			$flag = $Order_BaseModel->editBase($order_id, $edit_row);
		}

		

		if ($flag)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, array(), $msg, $status);

	}
    
    /**
     * 检验该账户是否可以发货
     * @param type $seller_id
     * @param type $shop_id
     * @return boolean
     */
    public function checkSend($seller_id,$shop_id){
        $user_id = Perm::$userId;
        if($seller_id == $user_id){
            return true;
        }else{
            //判断是否为子账号
            $seller_base_model = new Seller_BaseModel();
            $result = $seller_base_model->getByWhere(array('user_id'=>$user_id));
            $seller_info = array_shift($result);
            if($seller_info['shop_id'] == $shop_id){
                return true;
            }else{
                return false;
            }
        }
    }

	/**
	 * 货到付款订单确认收款
	 * @param type $order_id
	 * @return boolean
	 */
	public function confirmCollection()
	{
		$order_id = request_string('order_id');

		$Order_BaseModel = new Order_BaseModel();
		//查找订单信息
		$order_base = $Order_BaseModel->getOne($order_id);

		//判断当前用户是否是商家，判断订单状态是否是已发货或者已完成状态，判断当前订单是否是货到付款订单，判断是否已经确认收款
		if($order_base['seller_user_id'] == Perm::$userId && ($order_base['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS || $order_base['order_status'] == Order_StateModel::ORDER_FINISH) && $order_base['payment_id'] == PaymentChannlModel::PAY_CONFIRM && $order_base['payment_time'] <= 0)
		{
			//修改订单的付款时间
			$flag = $Order_BaseModel->editBase($order_id, array('payment_time'=>get_date_time()));
		}
		else
		{
			$flag = false;
		}
		if ($flag)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, array(), $msg, $status);

	}
}

?>
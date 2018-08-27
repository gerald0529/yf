<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Api接口, 让App等调用
 *
 *
 * @category   Game
 * @package    User
 * @author     Yf <service@yuanfeng.cn>
 * @copyright  Copyright (c) 2015远丰仁商
 * @version    1.0
 * @todo
 */
class Api_Trade_OrderCtl extends Api_Controller
{
	
	public $Order_BaseModel = null;

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
		$this->Order_BaseModel = new Order_BaseModel();

		$this->tradeOrderModel = new Order_BaseModel();
		
	}

	/*
	 * 获取商品订单列表
	 * */
	public function getOrderList()
	{
		$page = request_int('page', 1);
		$rows = request_int('rows', 100);

		$order_row = array();
		$sidx      = request_string('sidx');
		$sord      = request_string('sord', 'asc');
		$action    = request_string('action');

		if ($sidx)
		{
			$order_row[$sidx] = $sord;
		}
		
		if (request_string('order_id'))
		{
			$cond_row['order_id:LIKE'] = request_string('order_id') . '%';
		}
		if (request_string('buyer_name'))
		{
			$cond_row['buyer_user_name:LIKE'] = request_string('buyer_name') . '%';
		}
		if (request_string('shop_name'))
		{
			$cond_row['shop_name:LIKE'] = request_string('shop_name') . '%';
		}
		if (request_string('payment_number'))
		{
			$cond_row['payment_number:LIKE'] = '%'.request_string('payment_number') . '%';
		}
		if (!empty($action) && $action == 'virtual')
		{
			$cond_row['order_is_virtual'] = Order_BaseModel::ORDER_IS_VIRTUAL;
		}
		if (request_string('payment_date_f'))
		{
			$cond_row['payment_time:>='] = request_string('payment_date_f');
		}
		if (request_string('payment_date_t'))
		{
			$cond_row['payment_time:<='] = request_string('payment_date_t');
		}
        //分站筛选
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
            $data = $this->Order_BaseModel->getPlatOrderList($cond_row, array('order_create_time'=>'desc'), $page, $rows);
            if ($data['records'] > 0)
            {
                $status = 200;
                $msg    = __('success');
            }
            else
            {
                $status = 250;
                $msg    = __('没有满足条件的结果哦');
            }
            $this->data->addBody(-140, $data, $msg, $status);
        }
		
	}

	/*
	 * 取消订单
	 * */
	public function cancelOrder()
	{
		$order_id = request_string('order_id');

		$data['order_status']          = Order_StateModel::ORDER_CANCEL;
		$data['order_cancel_identity'] = Order_BaseModel::CANCEL_USER_SYSTEM;

		$flag = $this->Order_BaseModel->editBase($order_id, $data);

		if ($flag != false)
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

	/**
	 * 获取订单详细信息
	 */
	public function getOrderInfo()
	{
		$order_id = request_string('order_id');

		$data = $this->Order_BaseModel->getPhysicalInfoData(array('order_id' => $order_id));
		
		if ($data)
		{
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

	/**
	 * 收到货款
	 */
	public function receivePay()
	{
		$order_id                     = request_string('order_id');
		$data['payment_number']       = request_string('payment_number');
		$data['payment_time']         = request_string('payment_date');
		$data['payment_name']         = request_string('payment_name');
		$data['payment_other_number'] = request_string('payment_other_number');
		$data['order_status']         = Order_StateModel::ORDER_PAYED;


		$flag = $this->Order_BaseModel->editBase($order_id, $data);

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

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function getPaymentNum()
	{
		$data['payment_number'] = $this->Order_BaseModel->createPaymentNum();

		$msg    = __('success');
		$status = 200;
		$this->data->addBody(-140, $data, $msg, $status);
	}

    //修改订单支付名称
    public function editOrderPaymentName(){
        $order_id= request_string('order_id');
        $payment_name = request_string('payment_name');
        $flag = $this->Order_BaseModel->editBase($order_id,['payment_name'=> $payment_name]);
        $this->data->addBody(-140,[],'success','200');
    }
    /**
     * 检查订单中的商品状态
     */
    public function checkOrder(){
        $order_id = request_string('order_id');
        if(empty($order_id)){
            $this -> data -> addBody(-140, array(), "订单错误", 250);
        }
        $Order_GoodsModel = new Order_GoodsModel();
        $order_goods_info = $Order_GoodsModel->getByWhere(array('order_id'=>$order_id));
        $goods_ids = array_column($order_goods_info,'goods_id');
        $Goods_BaseModel = new Goods_BaseModel();
        $goods_info = $Goods_BaseModel->getByWhere(array('goods_id:IN'=> $goods_ids));
        if(empty($goods_info)){
            $this -> data -> addBody(-140, array(), "订单中商品已被商户下架或者删除，请重新选择商品并下单！", 250);
        }
        foreach ($goods_info as $k => $value){
            if($value['is_del'] == Goods_BaseModel::IS_DEL_YES){
                $this -> data -> addBody(-140, array(), "订单中商品:{$value['goods_name']}已被商户下架或者删除，请重新选择商品并下单", 250);
            }
        }
        //$this->data->addBody(-140,array(),'',200);

    }
	//修改订单状态(数组支付成功)
	public function editOrderRowSatus()
	{
		$order_id = request_row('order_id');
		$uorder_id = request_string('uorder_id');

		//开启事物
		$this->tradeOrderModel->sql->startTransactionDb();

		$supplier = new Supplier;
		if (is_array($order_id))
		{
			$order_id = array_filter($order_id);

			$order_id_str = implode(',',$order_id);

			foreach ($order_id as $key => $val)
			{
				$flag = $this->tradeOrderModel->editOrderStatusAferPay($val,$uorder_id);
				
				$order_base = $this->tradeOrderModel->getOne($val);
                $payment_name = $order_base['payment_name'];
				//如果存才采购单，改变采购单状态

                if (Yf_Registry::get('supplier_is_open') == 0) {
                    $sp_order = $this->tradeOrderModel->getByWhere(array('order_source_id'=>$val));
                } else {
                    $sp_order = $supplier->getOrderList(array('order_source_id'=>$val));
                }

				if(!empty($sp_order)){
					foreach ($sp_order as $k => $v) {

                        if (Yf_Registry::get('supplier_is_open') == 0) {
                            $this->tradeOrderModel->editOrderStatusAferPay($v['order_id']);
                        } else {
                            $supplier->editOrderStatusAferPay($v['order_id']);
                        }

						//请求paycenter,扣除分销商的钱
						$key      = Yf_Registry::get('shop_api_key');
						$url         = Yf_Registry::get('paycenter_api_url');
						$shop_app_id = Yf_Registry::get('shop_app_id');
						$formvars = array();
						$formvars['app_id']					= $shop_app_id;
						$formvars['from_app_id'] 			 = $shop_app_id;
						$formvars['uorder']             = $v['payment_number'];
						$formvars['buyer_id']           = $v['buyer_user_id'];

						$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=reduceDistMoney&typ=json',$url), $formvars);
					}
				}

				if ($flag && !$order_base['order_is_virtual'] && !$order_base['chain_id']) //2017-07-10加入判断：门店自提订单不需要通知商家发货
				{
					$message           = new MessageModel();
					$code              = 'place_your_order';
					$message_user_id   = $order_base['seller_user_id'];
					$message_user_name = $order_base['seller_user_name'];
					$shop_name         = $order_base['shop_name'];
					$message->sendMessage($code, $message_user_id, $message_user_name, $val, $shop_name, 1, 1);

				}

				$buyer_user_id = $order_base['buyer_user_id'];
				$buyer_user_name = $order_base['buyer_user_name'];
			}
		}
		else
		{
			$order_id_str = $order_id;

			$flag = $this->tradeOrderModel->editOrderStatusAferPay($order_id,$uorder_id);

			$order_base = $this->tradeOrderModel->getOne($order_id);
            $payment_name = $order_base['payment_name'];

			if ($flag && !$order_base['chain_id']) //2017-07-10加入判断：门店自提订单不需要通知商家发货
			{
				$message           = new MessageModel();
				$code              = 'place_your_order';
				$message_user_id   = $order_base['seller_user_id'];
				$message_user_name = $order_base['seller_user_name'];
				$shop_name         = $order_base['shop_name'];
				$message->sendMessage($code, $message_user_id, $message_user_name, $order_id_str, $shop_name, 1, 1);
			}

			$buyer_user_id = $order_base['buyer_user_id'];
			$buyer_user_name = $order_base['buyer_user_name'];

		}
        //将支付名称改为 白条支付
        if(request_string('payment_channel_code') === 'baitiao'){
            $payment_name = $payment_name ? $payment_name.'/白条支付' : '白条支付';
            $this->Order_BaseModel->editBase($order_id,array('payment_name'=>$payment_name));
        }

        //判断订单是否为拼团订单，如果是，则修改对应拼团信息
        $this->checkPtOrder($order_id,$buyer_user_id);
		if ($flag && $this->tradeOrderModel->sql->commitDb())
			{
			/**
			 *  加入统计中心
			 */
			$analytics_data = array();
			if(is_array($order_id)){
				$analytics_data['order_id'] = $order_id;
			}else{
				$analytics_data['order_id'] = array($order_id);
			}
			$analytics_data['status'] =  Order_StateModel::ORDER_PAYED;
			Yf_Plugin_Manager::getInstance()->trigger('analyticsUpdateOrderStatus',$analytics_data);
			/******************************************************************/

			$status = 200;
			$msg    = __('success');

			//付款成功提醒
			//$order_id
			$message = new MessageModel();
			$message->sendMessage('Payment reminder', $buyer_user_id, $buyer_user_name, $order_id_str, NULL, 0, MessageModel::ORDER_MESSAGE);

            if (Web_ConfigModel::value('Plugin_Fenxiao')) {
                Fenxiao::getInstance()->order($order_id);
            }
		}
		else
		{
			$this->tradeOrderModel->sql->rollBackDb();
			$m      = $this->tradeOrderModel->msg->getMessages();
			$msg    = $m ? $m[0] : __('failure');
			$status = 250;
		}
		
		$data = array($order_id);
		$this->data->addBody(-140, $data, $flag, $status);

	}

    //后台显示数据查询
    public function getEvaluateList()
    {
        $page = request_int('page');
        $rows = request_int('rows');
        $Goods_EvaluationModel = new Goods_EvaluationModel();

		$cond_row = array();

		$goods_name = request_string('goods_name');
		$shop_name  = request_string('shop_name');
		$member_name = request_string('member_name');
		$scores		= request_string('scores');
		$start_time = request_string('start_time');
		$end_time	= request_string('end_time');

		if($goods_name)
		{
			$cond_row['goods_name:LIKE'] ='%'.$goods_name.'%';
		}

		if($shop_name)
		{
			$cond_row['shop_name:LIKE'] ='%'.$shop_name.'%';
		}

		if($member_name)
		{
			$cond_row['member_name:LIKE'] ='%'.$member_name.'%';
		}

		if($scores)
		{
			$cond_row['scores'] = $scores;
		}

		if($start_time)
		{
			$cond_row['create_time:>='] = $start_time;
		}

		if($end_time)
		{
			$cond_row['create_time:<='] = $end_time;
		}

        $data = $Goods_EvaluationModel->listByWhere($cond_row, array(), $page, $rows);

        if($data)
        {
            $msg = __('success');
            $status = 200;
        }
        else
        {
            $msg = __('failure');
            $status = 250;
        }

        $this->data->addBody(-140,$data,$msg,$status);
    }

    public function removeEvaluate()
    {
        $id = request_int('id');
        $Goods_EvaluationModel = new Goods_EvaluationModel();
        $flag = $Goods_EvaluationModel->removeEvalution($id);

        if($flag)
        {
            $msg = __('success');
            $status = 200;
        }
        else
        {
            $msg = __('failure');
            $status = 250;
        }

        $this->data->addBody(-140, array(), $msg, $status);
    }

    public function getShopEvaluateList()
    {
        $page = request_int('page');
        $rows = request_int('rows');
        $Shop_EvaluationModel = new Shop_EvaluationModel();
        $Shop_BaseModel       = new Shop_BaseModel();
        $User_BaseModel       = new User_BaseModel();

		$cond_row = array();

		$evaluation_desccredit    = request_string('evaluation_desccredit');
		$evaluation_servicecredit = request_string('evaluation_servicecredit');
		$evaluation_deliverycredit = request_string('evaluation_deliverycredit');
		$start_time = request_string('start_time');
		$end_time   = request_string('end_time');

		if($evaluation_desccredit)
		{
			$cond_row['evaluation_desccredit'] = $evaluation_desccredit;
		}

		if($evaluation_servicecredit)
		{
			$cond_row['evaluation_servicecredit'] = $evaluation_servicecredit;
		}

		if($evaluation_deliverycredit)
		{
			$cond_row['evaluation_deliverycredit'] = $evaluation_deliverycredit;
		}

		if($start_time)
		{
			$cond_row['evaluation_create_time:>='] = $start_time;
		}

		if($end_time)
		{
			$cond_row['evaluation_create_time:<='] = $end_time;
		}

        $data = $Shop_EvaluationModel->listByWhere($cond_row , array(), $page, $rows);
        $items = $data['items'];
        unset($data['items']);
        if(!empty($items))
        {
            foreach($items as $key=>$value)
            {
                $shop_id = $value['shop_id'];
                $user_id = $value['user_id'];
                if($shop_id)
                {
                    $data_shop = $Shop_BaseModel->getOne($shop_id);
                    if($data_shop)
                        $items[$key]['shop_name'] = $data_shop['shop_name'];
                    else
                        $items[$key]['shop_name'] = '';
                }
                if($user_id)
                {
                    $data_user = $User_BaseModel->getOne($user_id);
                    if($data_user)
                        $items[$key]['user_name'] = $data_user['user_account'];
                    else
                        $items[$key]['user_name'] = '';
                }
            }
        }
        $data['items'] = $items;

        if($items)
        {
            $msg = __('success');
            $status = 200;
        }
        else
        {
            $msg = __('failure');
            $status = 250;
        }

        $this->data->addBody(-140,$data,$msg,$status);
    }

    public function removeShopEvaluate()
    {
        $id = request_int('id');
        $Shop_EvaluationModel = new Shop_EvaluationModel();
        $flag = $Shop_EvaluationModel->removeEvalution($id);

        if($flag)
        {
            $msg = __('success');
            $status = 200;
        }
        else
        {
            $msg = __('failure');
            $status = 250;
        }

        $this->data->addBody(-140, array(), $msg, $status);
    }


	public function CountAmount()
	{
		$order_id = request_string('id');
		$Order_BaseModel = new Order_BaseModel();
		$order_ids  = explode(',',$order_id);
		$data = $Order_BaseModel->getByWhere(array('order_id:in'=>$order_ids));
		$data = array_values($data);
		$money = 0;
		if(!empty($data))
		{
			foreach($data as $key=>$value)
			{
				$money+=$value['order_payment_amount'];
			}
		}
		$result = array();
		$result['money'] = $money;
		$this->data->addBody(-140, $result);
	}
    public function getListByOrderId(){
        $union_id = request_string('order_ids');
        $union_ids = explode(',', $union_id);
        if($union_ids){
            $order_model = new Order_BaseModel();
            $cond_row = array(
                'order_id:IN'=>$union_ids
            );
            $order_row = array('order_create_time'=>'desc');
            $order_list = $order_model->listByWhere($cond_row, $order_row);
            if(isset($order_list['items']) && $order_list){
                $this->data->addBody(-140, $order_list['items']);
            }else{
                $this->data->addBody(-140, array(),__('failure'),250);
            }
            
        }else{
            $this->data->addBody(-140, array(),__('failure'),250);
        }
        
    }


	public function editOrderSubPay()
	{
		$order_id = request_row('order_id');
		$order_sub_user = request_int('order_sub_user');

		$Order_BaseModel = new Order_BaseModel();
		$edit_row = array();
		$edit_row['order_sub_pay'] = Order_StateModel::SUB_USER_PAY;
		$edit_row['order_sub_user'] = $order_sub_user;

		$flag = $Order_BaseModel->editBase($order_id,$edit_row);

		if($flag)
		{
			$msg = __('success');
			$status = 200;
		}
		else
		{
			$msg = __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, array(), $msg, $status);
	}

	//根据订单号获取订单商品
	public function getGoodsByOrderId()
	{
		$order_id = request_string('order_id');

		$Order_GoodsModel = new Order_GoodsModel();
		$order_goods = $Order_GoodsModel->getByWhere(array('order_id'=>$order_id));

		$this->data->addBody(-140, $order_goods);

	}

	//判断是否为分销单，是分销单则获取分销金额
	public function getOrderBase(){

		$order_id = request_string('order_id');
		$Order_BaseModel = new Order_BaseModel();
		$order_base = $Order_BaseModel->getByWhere(array('order_source_id'=>$order_id));


		$this->data->addBody(-140, $order_base);

	}
    
    /**
     * 处理拼团的商品
     * @param type $order_id
     * @param type $buyer_user_id
     * @return boolean
     */
    private function checkPtOrder($order_id,$buyer_user_id){
        $order_id = is_array($order_id) ? $order_id[0] : $order_id;
		$pt_mark_model = new PinTuan_Mark();
        $pt_temp_model = new PinTuan_Temp();
        $pt_info = $pt_temp_model->getPtInfoByOrderId($order_id,'pintuan');

        if(!$pt_info['temp']){
            return false;
        }
        $now_time = date('Y-m-d H:i:s');
        if($pt_info['temp']['mark_id'] == 0){
            //添加mark表和buyer表
            if($pt_info['base']['end_time'] < $now_time){
                //1过了时间
                $status = 2;
            }else if($pt_info['base']['end_time'] > $now_time && $pt_info['base']['start_time'] < $now_time && $pt_info['base']['person_num'] == 1){
                //拼团成功
                $status = 1;
            }else{
                $status = 0;
            }
            $mark_data = array('user_id'=> $buyer_user_id,'detail_id'=>$pt_info['temp']['detail_id'],'created_time'=> date('Y-m-d H:i:s'),'status'=>$status,'num'=>1);
            $mark_id = $pt_mark_model->addInfo($mark_data,true);
            $pt_temp_model->editInfo($pt_info['temp']['id'],array('mark_id'=>$mark_id));
            $pt_buyer_model = new PinTuan_Buyer();
            $buyer_data = array('detail_id'=>$pt_info['temp']['detail_id'],'user_id'=>$buyer_user_id,'created_time'=>$now_time,'mark_id'=>$mark_id);
            $buyer_res = $pt_buyer_model->addInfo($buyer_data);
            
        }else{
            $pt_buyer_model = new PinTuan_Buyer();
            $buyer_num = $pt_buyer_model->getCount(array('mark_id'=>$pt_info['temp']['mark_id'])) + 1;
            //添加mark表和buyer表
            if($pt_info['base']['end_time'] < $now_time){
                //1过了时间
                $status = 2;
            }else if($pt_info['base']['end_time'] > $now_time && $pt_info['base']['start_time'] < $now_time && $pt_info['base']['person_num'] <= $buyer_num){
                //拼团成功
                $status = 1;
            }else{
                $status = 0;
            }
            //修改buyer表和mark表
            $mark_res1 = $pt_mark_model->editInfo($pt_info['temp']['mark_id'],array('num'=>1),true);
            if($status != 0){
                $mark_res2 = $pt_mark_model->editInfo($pt_info['temp']['mark_id'],array('status'=>$status));
            }
            $buyer_data = array('detail_id'=>$pt_info['temp']['detail_id'],'user_id'=>$buyer_user_id,'created_time'=>$now_time,'mark_id'=>$pt_info['temp']['mark_id']);
            $buyer_res = $pt_buyer_model->addInfo($buyer_data);
        }
        return $buyer_res;
    }
    
    public function isChainOrder()
    {
        $orderId = request_string('orderId');
        $orderModel = new Order_BaseModel;
        $order = $orderModel->getOne($orderId);
        $chainId = $order ? $order['chain_id'] : 0;
        $result = [
            'flag'=> $chainId == 0 ? false : true
        ];
        $this->data->addBody(-140, $result, 'success', 200);
    }


    //订单导出
    public function getOrderExcel()
    {
        $order_id = request_string("order_id");
        $buyer_name = request_int("buyer_name");
        $shop_name = request_int("shop_name");
        $payment_number = request_int("payment_number");
        $payment_date_f = request_int("payment_date_f");
        $payment_date_t = request_int("payment_date_t");
        $limit = request_int("limit");
        $start_limit = request_int("start_limit");
        $is_limit = request_int("is_limit");
        $action = request_string('action');
        $cond_row = array();
        if ($order_id) {
            $cond_row['order_id'] = $order_id;
        }
        if ($buyer_name) {
            $cond_row['buyer_name'] = $buyer_name;
        }
        if ($shop_name) {
            $cond_row['shop_name'] = $shop_name;
        }
        if ($payment_number) {
            $cond_row['payment_number'] = $payment_number;
        }
        if ($payment_date_f) {
            $cond_row['payment_date_f'] = $payment_date_f;
        }
        if ($payment_date_t) {
            $cond_row['payment_date_t'] = $payment_date_t;
        }
        if ($limit) {
            $cond_row['limit'] = $limit;
        }
        if ($limit) {
            $cond_row['start_limit'] = $start_limit;
        }
        if ($is_limit) {
            $cond_row['is_limit'] = $is_limit;
        }
        if ($action) {
            $cond_row['action'] = $action;
        }
        $Order_BaseModel = new Order_BaseModel();
        $limits = $Order_BaseModel->getCounts($action);
        if ($is_limit) {
            $this->export($cond_row);
        } else {
            //保存地址
            $path = ROOT_PATH . '/shop/data/download/';
            $file_template = $path . time();//临时文件
            $url = $file_template . '/';
            for ($i = 0; $i < $limits; $i++) {
                $cond_row['limits'] = $i;
                $this->export($cond_row, $i, $url);
            }
            //打包
            $zip = new ZipArchive();
            $down_name = 'order_info.zip';
            $file_name = $path . $down_name;
            if ($zip->open($file_name, ZipArchive::CREATE) === TRUE) {
                $this->addFileToZip($url, $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
                $zip->close(); //关闭处理的zip文件
            }
            $fp = fopen($file_name, "r");
            $file_size = filesize($file_name);//获取文件的字节
            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length:" . $file_size);
            Header("Content-Disposition: attachment; filename=$down_name");
            $buffer = 1024; //设置一次读取的字节数，每读取一次，就输出数据（即返回给浏览器）
            $file_count = 0; //读取的总字节数
            //向浏览器返回数据 如果下载完成就停止输出，如果未下载完成就一直在输出。根据文件的字节大小判断是否下载完成
            while(!feof($fp) && $file_count < $file_size) {
                $file_con = fread($fp, $buffer);
                $file_count += $buffer;
                echo $file_con;
            }
            fclose($fp);
            //下载完成后删除压缩包，临时文件夹
            if ($file_count >= $file_size) {
                unlink($file_name);
                exec("rm -rf ".$file_template);
            }
        }
    }

    /**压缩文件夹
     * @param $path
     * @param $zip
     */
    function addFileToZip($path,$zip){
        $handler = opendir($path); //打开当前文件夹由$path指定。
        while(($filename = readdir($handler)) !== false){
            if($filename != "." && $filename != ".."){//文件夹文件名字为'.'和‘..'，不要对他们进行操作
                if(is_dir($path."/".$filename)){// 如果读取的某个对象是文件夹，则递归
                    addFileToZip($path."/".$filename, $zip);
                }else{ //将文件加入zip对象
                    $zip->addFile($path."/".$filename,$filename);
                }
            }
        }
        @closedir($path);
    }
    public function export($cond_row,$i="",$url="")
    {
        $Order_BaseModel = new Order_BaseModel();
        $con = $Order_BaseModel->getOrderInfoExcel($cond_row);
        $tit = array(
            "序号",
            "订单编号",
            "订单来源",
            "下单时间",
            "订单金额（元）",
            "订单状态",
            "支付单号",
            "支付方式",
            "支付时间",
            "发货物流单号",
            "退款金额（元）",
            "订单完成时间",
            "是否评价",
            "店铺ID",
            "店铺名称",
            "买家ID",
            "买家账号",
        );
        $key = array(
            "order_id",
            "order_from_text",//订单来源
            "order_create_time",
            "order_payment_amount",
            "order_status_text",//订单状态
            "payment_number",
            "payment_name",
            "payment_time",
            "order_shipping_code",
            "order_refund_amount",
            "order_finished_time",
            "order_buyer_evaluation_status",//是否评价
            "shop_id",
            "shop_name",
            "buyer_user_id",
            "buyer_user_name",
        );
        if(isset($i)&& is_numeric($i)){
            $this->download_excel("订单信息".$i, $tit, $con, $key,$url);
        }else{
            $this->excel("订单信息", $tit, $con, $key);
        }
    }
    public function download_excel($title,$tit,$con,$key,$url){
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
            'T'
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
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        if (!file_exists($url) && !mkdir($url, 0777, true)) {
            return false;
        }
        $url = $url.time().'.xls';
        $objWriter->save($url);

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
            'T'
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

    }


    //修改订单状态(数组支付成功)
	public function webpos_editOrderRowSatus()
	{
		$order_id = request_row('order_id');
		$uorder_id = request_string('uorder_id');

		//开启事物
		$this->tradeOrderModel->sql->startTransactionDb();

		$supplier = new Supplier;
		if (is_array($order_id))
		{
			$order_id = array_filter($order_id);

			$order_id_str = implode(',',$order_id);

			foreach ($order_id as $key => $val)
			{
				$flag = $this->tradeOrderModel->webpos_editOrderStatusAferPay($val,$uorder_id);
				
				$order_base = $this->tradeOrderModel->getOne($val);
                $payment_name = $order_base['payment_name'];
				//如果存才采购单，改变采购单状态

                if (Yf_Registry::get('supplier_is_open') == 0) {
                    $sp_order = $this->tradeOrderModel->getByWhere(array('order_source_id'=>$val));
                } else {
                    $sp_order = $supplier->getOrderList(array('order_source_id'=>$val));
                }

				if(!empty($sp_order)){
					foreach ($sp_order as $k => $v) {

                        if (Yf_Registry::get('supplier_is_open') == 0) {
                            $this->tradeOrderModel->editOrderStatusAferPay($v['order_id']);
                        } else {
                            $supplier->editOrderStatusAferPay($v['order_id']);
                        }

						//请求paycenter,扣除分销商的钱
						$key      = Yf_Registry::get('shop_api_key');
						$url         = Yf_Registry::get('paycenter_api_url');
						$shop_app_id = Yf_Registry::get('shop_app_id');
						$formvars = array();
						$formvars['app_id']					= $shop_app_id;
						$formvars['from_app_id'] 			 = $shop_app_id;
						$formvars['uorder']             = $v['payment_number'];
						$formvars['buyer_id']           = $v['buyer_user_id'];

						$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=reduceDistMoney&typ=json',$url), $formvars);
					}
				}

				if ($flag && !$order_base['order_is_virtual'] && !$order_base['chain_id']) //2017-07-10加入判断：门店自提订单不需要通知商家发货
				{
					$message           = new MessageModel();
					$code              = 'place_your_order';
					$message_user_id   = $order_base['seller_user_id'];
					$message_user_name = $order_base['seller_user_name'];
					$shop_name         = $order_base['shop_name'];
					$message->sendMessage($code, $message_user_id, $message_user_name, $val, $shop_name, 1, 1);

				}

				$buyer_user_id = $order_base['buyer_user_id'];
				$buyer_user_name = $order_base['buyer_user_name'];
			}
		}
		else
		{
			$order_id_str = $order_id;

			$flag = $this->tradeOrderModel->editOrderStatusAferPay($order_id,$uorder_id);

			$order_base = $this->tradeOrderModel->getOne($order_id);
            $payment_name = $order_base['payment_name'];

			if ($flag && !$order_base['chain_id']) //2017-07-10加入判断：门店自提订单不需要通知商家发货
			{
				$message           = new MessageModel();
				$code              = 'place_your_order';
				$message_user_id   = $order_base['seller_user_id'];
				$message_user_name = $order_base['seller_user_name'];
				$shop_name         = $order_base['shop_name'];
				$message->sendMessage($code, $message_user_id, $message_user_name, $order_id_str, $shop_name, 1, 1);
			}

			$buyer_user_id = $order_base['buyer_user_id'];
			$buyer_user_name = $order_base['buyer_user_name'];

		}
        //将支付名称改为 白条支付
        if(request_string('payment_channel_code') === 'baitiao'){
            $payment_name = $payment_name ? $payment_name.'/白条支付' : '白条支付';
            $this->Order_BaseModel->editBase($order_id,array('payment_name'=>$payment_name));
        }

        //判断订单是否为拼团订单，如果是，则修改对应拼团信息
        $this->checkPtOrder($order_id,$buyer_user_id);
		if ($flag && $this->tradeOrderModel->sql->commitDb())
			{
			/**
			 *  加入统计中心
			 */
			$analytics_data = array();
			if(is_array($order_id)){
				$analytics_data['order_id'] = $order_id;
			}else{
				$analytics_data['order_id'] = array($order_id);
			}
			$analytics_data['status'] =  Order_StateModel::ORDER_PAYED;
			Yf_Plugin_Manager::getInstance()->trigger('analyticsUpdateOrderStatus',$analytics_data);
			/******************************************************************/

			$status = 200;
			$msg    = __('success');

			//付款成功提醒
			//$order_id
			$message = new MessageModel();
			$message->sendMessage('Payment reminder', $buyer_user_id, $buyer_user_name, $order_id_str, NULL, 0, MessageModel::ORDER_MESSAGE);

            if (Web_ConfigModel::value('Plugin_Fenxiao')) {
                Fenxiao::getInstance()->order($order_id);
            }
		}
		else
		{
			$this->tradeOrderModel->sql->rollBackDb();
			$m      = $this->tradeOrderModel->msg->getMessages();
			$msg    = $m ? $m[0] : __('failure');
			$status = 250;
		}
		
		$data = array($order_id);
		$this->data->addBody(-140, $data, $flag, $status);

	}

}

?>
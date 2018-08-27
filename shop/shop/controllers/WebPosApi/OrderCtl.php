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
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class WebPosApi_OrderCtl extends WebPosApi_Controller
{
    	
	public $Order_BaseModel  = null;
	public $Order_GoodsModel = null;
	public $Goods_BaseModel = null;
    public $userBaseModel    = null;
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
		$this->shopInfoModel = new Shop_BaseModel();
		$this->orderBaseModel = new Order_BaseModel();
		$this->ChainUserModel = new Chain_UserModel();
		$this->orderGoodsModel = new Order_GoodsModel();
	}

    /*
     *通过店家id获取店铺所有订单信息
     * @param int $user_id 用户id
     * @access public
     */
    public function getShopOrderByUserId(){
        $user_id = request_int('user_id');//店员或店家用户id
        $data = [];
        $shop_info = $this->shopInfoModel->getOneByWhere(['user_id'=>$user_id]);
        $chain_info = $this->ChainUserModel->getByWhere(['shop_id'=>$shop_info['shop_id']]);
        //如果没有店铺信息，说明是店员登录，返回
        if($shop_info)
        {
            $status = 200;
            foreach ($chain_info as $k => $v) {
            	$chain_id[] = $v['chain_id'];
            }
            $data = $this->orderBaseModel->getByWhere(['shop_id'=>$shop_info['shop_id'],'chain_id:IN'=>$chain_id]);
            if($data)
            {
                $data = array_values($data);
                $msg = 'success';
            }
            else
            {
                $msg = 'success:店铺还没有订单';
            }
        }
        else
        {
            $msg = 'failure:您不是店家';
            $status = 250;
        }

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /*
     *通过店家id获取店铺所有订单商品
     * @param int $user_id 用户id
     * @access public
     */
    public function getShopOrdergoodsByUserId(){
    	$user_id = request_int('user_id');//店员或店家用户id
        $order_id = request_row('order_id');
        $data = [];
        $shop_info = $this->shopInfoModel->getOneByWhere(['user_id'=>$user_id]);
        //如果没有店铺信息，说明是店员登录，返回
        if($shop_info)
        {
            $status = 200;
            $cond_rows['shop_id'] = $shop_info['shop_id'];
            $data = $this->orderGoodsModel->getByWhere(array("order_id:IN" => $order_id));
            if($data)
            {
                $data = array_values($data);
                foreach($data as $key=>$value)
                {
                    $data[$key]['goods_spec'] = implode(',',$value['order_spec_info']);
                    $data[$key]['shop_name'] = $shop_info['shop_name'];
                }
                $msg = 'success';
            }
            else
            {
                $msg = 'success:店铺还没有订单商品';
            }
        }
        else
        {
            $msg = 'failure:您不是店家';
            $status = 250;
        }

        $this->data->addBody(-140, $data, $msg, $status);
    }
        
	//判断此会员是否存在
	public function getCustomer()
	{
		$skey = request_string('skey');
		$cond_row['user_account'] = $skey;
		$data = $this->userBaseModel->getOneBywhere($cond_row);
		if(!empty($skey))
		{
			if(!empty($data))
			{
				$status=200;
			}
			else
			{
				$status=100;
			}
		}
		else
		{
                     $status=300;
		}
		 $msg='success';
		$this->data->addBody(-140, $data, $msg, $status);
	}

	//返回会员的钱
	public function getcontactInfo()
	{
		//会员的钱
		$key                 = Yf_Registry::get('shop_api_key');
		$formvars            = array();
		$formvars['user_id'] = request_int('buid');
		$formvars['app_id']  = Yf_Registry::get('shop_app_id');

		$money_row = get_url_with_encrypt($key, sprintf('%sindex.php?ctl=Api_User_Info&met=getUserResourceInfo&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);

		$this->data->addBody(-140, $money_row['data'], $money_row['msg'],  $money_row['status']);
	}
	
	//报表模板
	public function reportForm()
	{
		$period = request_string("period");
		$now = date('Y-m-d',time());
		switch($period)
		{
			case "daily":$begin = $now;break;
			case "weekly":$begin = date('Y-m-d',strtotime('-6 day'));break;
			case "monthly":$begin = date('Y-m-d',strtotime('-29 day'));break;
		}

		$data['begin'] = $begin;
		$msg = "success";
		$status = "200";
	    $this->data->addBody(-140, $data,$msg,$status);
	}
	
	/**
	 * 获取订单报表信息
	 */
	public function getOrderReport()
	{
		$cond_row  = array();
		$order_row = array();
		$page = request_int('page',0);
		$rows = request_int('rows',50);
		//获取时间按照时间搜索订单
		$beginDate  = request_string("beginDate");
		$endDate 	= request_string("endDate");
		$cond_row['shop_id']	   = request_int('shop_id');
		$cond_row['order_from']	   = Order_BaseModel::FROM_WEBPOS;
		$cond_row['order_date:>='] = $beginDate;
		$cond_row['order_date:<='] = $endDate;
		$order_row['order_create_time'] = 'DESC';
		$data = $this->Order_BaseModel->listByWhere($cond_row, $order_row, $page, $rows);
                
        $Order_GoodsModel = new Order_GoodsModel();
		$order_id_row     = array_column($data['items'], 'order_id');
		$order_buyer_row     = array_column($data['items'], 'buyer_user_name', 'order_id');
		$order_goods_list = $Order_GoodsModel->listByWhere(array('order_id:IN' => $order_id_row),array('order_goods_id'=>'DESC'));

		if ($order_goods_list)
		{
			foreach($order_goods_list['items'] as $key=>$value)
			{
				$order_goods_list['items'][$key]['order_spec_desc'] = json_decode($value['order_spec_info'],true);
				$order_goods_list['items'][$key]['buyer_user_name'] = $order_buyer_row[$value['order_id']];
			}
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, $order_goods_list, $msg, $status);
	}

	//生成订单编号
	public function generateNo()
	{
		$data 					= array();
		$data['status'] 		= 200;
		$data['msg'] 			= 'success';
		$data['data']['billNo'] = "XS".request_int('billDate');
		$msg 					= "success";
		$this->data->addBody(-140, $data, $msg, 200);
	}

	//返回狗屁数据，不知道有啥鸟用
	public function listBySelected()
	{
		echo '{"status":200,"msg":"success","data":{"result":[{"advanceDays":0,"amount":5,"barCode":"","categoryName":"","currentQty":"","delete":false,"discountRate":0,"id":129459311923097,"invSkus":[],"isSerNum":0,"isWarranty":0,"josl":"","locationId":0,"locationName":"","locationNo":"","name":"qunhong","nearPrice":10,"number":"00000000","pinYin":"","purPrice":1,"quantity":2,"remark":"","retailPrice":0,"safeDays":0,"salePrice":10,"salePrice1":0,"salePrice2":0,"salePrice3":0,"skuAssistId":"","skuBarCode":"","skuClassId":0,"skuId":0,"skuName":"","skuNumber":"","spec":"1g","unitCost":2.5,"unitId":0,"unitName":""}]}}';
		die;
	}

	//保存数据，生成订单，
	public function addOrder()
	{
		$shop_id 				= request_int('shop_id');
		$Shop_BaseModel 		= new Shop_BaseModel();
		$shop_info 				= $Shop_BaseModel->getShopBaseInfo($shop_id);

        //订单信息部分
		$buyer_id 				= request_int('buId');				//买家ID
		$salesId 				= request_int('salesId');			//销售ID
		$totalQty 				= request_int('totalQty');			//总的数量
		$totalDiscount 			= request_float('totalDiscount');	//总的折扣额
		$totalAmount 			= request_float('totalAmount');		//总的金额
		$des 					= request_string('description');	//总订单描述
		$disRate 				= request_float('disRate');			//优惠率
		$disAmount 				= request_float('disAmount');		//优惠金额
		$amount 				= request_float('amount');			//最后金额
		$cash 					= request_float('cash');			//账户余额
		$password 				= request_string('password');		//支付密码
		$paymentMethod 			= request_int('paymentMethod');		//支付方式
		$order_payment_amount 	= $amount;
		$order_goods 			= request_row('entries');			//商品列表

		//根据支付方式判断用户账户余额是否充足，决定是否进行下单操作
		switch($paymentMethod)
		{
			case "1":$payment_name = '现金支付';break;
			case "2":$payment_name = '余额支付';break;
			case "3":$payment_name = '微信支付';break;
			case "4":$payment_name = '支付宝支付';break;
		}

		//判断支付方式和余额
		//账户余额支付
		if($paymentMethod==2) //账户余额支付，和线上支付一样，卖家金额每月结算一次
		{
			$errorMsg = '';
			//获取买家支付账户资产信息
			$key      						= Yf_Registry::get('shop_api_key');
			$url         					= Yf_Registry::get('paycenter_api_url');
			$shop_app_id 					= Yf_Registry::get('shop_app_id');

			$formvars 						= array();
			$formvars['user_id']     		= $buyer_id;
			$formvars['user_pay_passwd'] 	= request_string('password');      //支付密码；
			$formvars['money'] 				= request_float('amount');         //订单支付金额
			$formvars['app_id']        		= $shop_app_id;
			$formvars['from_app_id'] 		= $shop_app_id;

			fb($formvars);

			$rs = get_url_with_encrypt($key, sprintf('%sindex.php?ctl=Api_Pay_Pay&met=getPayUserInfo&typ=json', $url), $formvars);
			fb($rs);

			if($rs['status'] == 250)
			{
				$errorMsg = $rs['msg'];
			}

			if(!empty($errorMsg))
			{
				$body_data_rows = array();
				$body_data_rows['status'] = 0;
				$body_data_rows['msg'] = $errorMsg;
				$body_data_rows['data'] = array();
				$pro_data_rows = array('cmd_id'=>-140) + $body_data_rows;
				header('Content-type: application/json');
				echo json_encode($pro_data_rows);
				die;
			}
		}

		$order_goods_base_row   = array();
		foreach($order_goods as $key=>$value)
		{
			$goods_id = $value['invId'];
			$order_goods_base_row[$goods_id]['goods_id'] 					= $value['invId'];     	//商品goods_id
			$order_goods_base_row[$goods_id]['goods_price'] 				= $value['price'];  	//商品价格
			$order_goods_base_row[$goods_id]['order_goods_num'] 			= $value['qty'];		//商品数量
			$order_goods_base_row[$goods_id]['order_goods_amount'] 			= $value['amount'];     //实付金额
			$order_goods_base_row[$goods_id]['order_goods_payment_amount'] 	= $value['amount'];     //实付金额
		}

		$goods_base_id_row = array_column($order_goods,'invId');
		$goods_base_rows   = $this->Goods_BaseModel->getGoodsListByGoodId($goods_base_id_row);
		$goods_error       = "";
		foreach($order_goods_base_row as $key=>$value)
		{
			if(in_array($value['goods_id'],array_keys($goods_base_rows)))
			{
				//获取商品Common信息
				$Goods_CommonModel = new Goods_CommonModel();
				$goods_common      = $Goods_CommonModel->getOne($goods_base_rows[$key]['common_id']);
				if (empty($goods_common))
				{
					return null;
				}

				//商品规格信息
				$spec_name  = $goods_common['common_spec_name'];
				$spec_value = $goods_common['common_spec_value'];

				if (is_array($spec_name) && $spec_name && $goods_base_rows[$key]['goods_spec'])
				{
					$goods_spec = current($goods_base_rows[$key]['goods_spec']);

					foreach ($goods_spec as $gpk => $gbv)
					{
						foreach ($spec_value as $svk => $svv)
						{
							$pk = array_search($gbv, $svv);

							if ($pk)
							{
								$goods_base_rows[$key]['spec'][] = $spec_name[$svk] . ":" . $gbv;
							}
						}
					}

				}
				else
				{
					$goods_base_rows[$key]['spec'] = array();
				}

				$order_goods_base_row[$key]['order_goods_amount']		= $goods_base_rows[$key]['goods_price']*$value['order_goods_num'];    //商品原价
				$order_goods_base_row[$key]['order_goods_discount_fee'] = $goods_base_rows[$key]['goods_price']*$value['order_goods_num'] - $value['order_goods_amount'];//商品优惠金额
				$order_goods_base_row[$key]['goods_stock']  			= $goods_base_rows[$key]['goods_stock'];    //商品库存
				$order_goods_base_row[$key]['order_spec_info']  		= $goods_base_rows[$key]['spec'];     		//商品规格
				$order_goods_base_row[$key]['common_id'] 				= $goods_base_rows[$key]['common_id'];      //商品common_id
				$order_goods_base_row[$key]['goods_name'] 				= $goods_base_rows[$key]['goods_name'];		//商品名称
				$order_goods_base_row[$key]['goods_class_id'] 			= $goods_base_rows[$key]['cat_id']; 		//商品分类
				$order_goods_base_row[$key]['goods_image'] 				= $goods_base_rows[$key]['goods_image'];    //商品图片
			}
			else
			{
				unset($order_goods_base_row[$key]);
			}
		}

		$order = array();
		$uprice = 0;
		$buyer = $buyer_id;
		$inorder = "";

		//获取买家的用户信息
		$User_InfoModel 		= new User_InfoModel();
		$user_info      		= $User_InfoModel->getOne($buyer_id);
		if($user_info)
		{
			$buyer_name = $user_info['user_name'];
		}
		else
		{
			$buyer_error = __('用户信息不存在！');
		}

		//判断各个商品的库存
		if(!empty($datas['entries']))
		{
			$str='';
			foreach($datas['entries'] as $k=>$v)
			{
				if($v['goods_stock']<$v['qty'])
				{
					$str.="商品：$v[goodsName]【$v[skuName]】库存不足（$v[goods_stock]）";
				}
			}
			if(!empty($str))
			{
				$body_data_rows = array();
				$body_data_rows['status'] = 0;
				$body_data_rows['msg'] = $str;
				$body_data_rows['data'] = array();
				$pro_data_rows = array('cmd_id'=>-140) + $body_data_rows;
				header('Content-type: application/json');
				echo json_encode($pro_data_rows);
				//die;
			}
		}

		//END 2016-01-08

		$uprice  = 0;
		$inorder = '';
		$utrade_title = '';	//商品名称 - 标题

        //开始写入订单表
        $Number_SeqModel = new Number_SeqModel();
        $prefix          = sprintf('%s-%s-', Yf_Registry::get('shop_app_id'), date('YmdHis'));
        $order_number    = $Number_SeqModel->createSeq($prefix);
        $order_id 		 = sprintf('%s-%s-%s-%s', 'DD', $shop_info['user_id'], $shop_id, $order_number);

		//开启事物
		$this->Order_BaseModel->sql->startTransactionDb();

        $order_row                           	= array();
        $order_row['order_id']               	= $order_id;
        $order_row['shop_id']                	= $shop_info['shop_id'];
        $order_row['shop_name']              	= $shop_info['shop_name'];
        $order_row['buyer_user_id']          	= $buyer_id;
        $order_row['buyer_user_name']        	= $buyer_name;
        $order_row['seller_user_id']         	= $shop_info['user_id'];	//卖家ID
        $order_row['seller_user_name']       	= $shop_info['user_name'];	//卖家用户名称
        $order_row['order_date']             	= date('Y-m-d');
        $order_row['order_create_time']      	= get_date_time();
		$order_row['order_finished_time']		= get_date_time();
        $order_row['order_receiver_name']    	= "";
        $order_row['order_receiver_address'] 	= "";
        $order_row['order_receiver_contact'] 	= "";
        $order_row['order_invoice']          	= __('不需要发票');
        $order_row['order_invoice_id']	     	= 0;
        $order_row['order_from']	         	= Order_BaseModel::FROM_WEBPOS;  //来源于webpos线下下单
        $order_row['order_goods_amount']     	= array_sum(array_column($order_goods_base_row, 'order_goods_amount')); //订单商品总金额
        $order_row['order_payment_amount']   	= $order_payment_amount;	     // 店铺商品价格 + 运费价格 + 加价购商品价格   - 代金券价格
        $order_row['order_discount_fee']     	= $order_row['order_goods_amount'] - $order_row['order_payment_amount']; //折扣金额  店铺优惠价格 + 会员折扣价格  +  代金券价格
        $order_row['order_point_fee']        	= 0;    					    //买家使用积分
        $order_row['order_shipping_fee']     	= 0;						   //运费价格
        $order_row['order_message']          	= ''; 						   //订单备注信息

        if($paymentMethod == 3 || $paymentMethod == 4)
		{
            $order_row['order_status']           =  Order_StateModel::ORDER_WAIT_PAY; //如果是微信扫码支付或支付宝支付，订单状态为等待付款
        }
		else
		{
            $order_row['order_status']           = Order_StateModel::ORDER_FINISH; 	//订单状态，订单完成，针对账户余额支付和现金支付
        }

        $order_row['order_points_add']       = 0;    								//订单赠送的积分
        $order_row['voucher_id']             = 0;    								//代金券id
        $order_row['voucher_price']          = 0;    								//代金券面额
        $order_row['voucher_code']           = 0;    								//代金券编码
        $order_row['order_commission_fee']   = 0;									//佣金金额
        $order_row['order_is_virtual']       = 0;    								//1-虚拟订单 0-实物订单
        $order_row['order_shop_benefit']     = '';  								//店铺优惠
        $order_row['payment_id']			 = 3;									//支付方式
        $order_row['payment_name']			 = $payment_name;						//支付方式名称
        $flag = $this->Order_BaseModel->addBase($order_row);						//添加订单基本信息

		//写入订单商品表
		foreach ($order_goods_base_row as $k => $v)
		{
			//计算商品的优惠
			$order_goods_row                                  = array();
			$order_goods_row['order_id']                      = $order_id;
			$order_goods_row['goods_id']                      = $v['goods_id'];
			$order_goods_row['common_id']                     = $v['common_id'];
			$order_goods_row['buyer_user_id']                 = $buyer_id;
			$order_goods_row['goods_name']                    = $v['goods_name'];
			$order_goods_row['goods_class_id']                = $v['goods_class_id'];
			$order_goods_row['order_spec_info']               = $v['order_spec_info'];
			$order_goods_row['goods_price']                   = $v['goods_price'];
			$order_goods_row['order_goods_num']               = $v['order_goods_num'];
			$order_goods_row['goods_image']                   = $v['goods_image'];
			$order_goods_row['order_goods_amount']            = $v['order_goods_amount'];
			$order_goods_row['order_goods_payment_amount']    = $v['order_goods_payment_amount'];
			$order_goods_row['order_goods_discount_fee']      = $v['order_goods_discount_fee']; //优惠金额，即便宜了多少钱
			$order_goods_row['order_goods_adjust_fee']        = 0;    							//手工调整金额
			$order_goods_row['order_goods_point_fee']         = 0;    							//积分费用
			$order_goods_row['order_goods_commission']        = @$v['commission'];    			//商品佣金
			$order_goods_row['shop_id']                       = $shop_id;
			$order_goods_row['order_goods_status']            = ($paymentMethod==1 || $paymentMethod==2)?Order_StateModel::ORDER_FINISH:Order_StateModel::ORDER_WAIT_PAY;
			$order_goods_row['order_goods_evaluation_status'] = 0;  							//0未评价 1已评价
			$order_goods_row['order_goods_benefit']           = '';
			$order_goods_row['order_goods_time']              = get_date_time();
			$order_goods_row['order_goods_finish_time']		  = get_date_time();

			$flag2 = $this->Order_GoodsModel->addGoods($order_goods_row);

			$flag3 = $this->Goods_BaseModel->delStock($v['goods_id'], $v['goods_num']);			//修改商品库存信息
			$trade_title = $v['goods_name'];
		}


        /*
        *  经验与成长值
        */
        $user_points        = Web_ConfigModel::value("points_recharge");//订单每多少获取多少积分
        $user_points_amount = Web_ConfigModel::value("points_order");//订单每多少获取多少积分

        if ($order_payment_amount / $user_points > $user_points_amount)
        {
            $user_points = floor($order_payment_amount / $user_points);
        }
        else
        {
            $user_points = $user_points_amount;
        }


        $user_grade        = Web_ConfigModel::value("grade_recharge");	//订单每多少获取多少积分
        $user_grade_amount = Web_ConfigModel::value("grade_order");		//订单每多少获取多少成长值

        if ($order_payment_amount / $user_grade > $user_grade_amount)
        {
            $user_grade = floor($order_payment_amount / $user_grade);
        }
        else
        {
            $user_grade = $user_grade_amount;
        }

        $User_ResourceModel = new User_ResourceModel();
        //获取积分经验值
        $ce = $User_ResourceModel->getResource($buyer_id);

        $resource_row['user_points'] = $ce[$buyer_id]['user_points'] * 1 + $user_points * 1;
        $resource_row['user_growth'] = $ce[$buyer_id]['user_growth'] * 1 + $user_grade * 1;
        $res_flag = $User_ResourceModel->editResource($buyer_id, $resource_row);

        $User_GradeModel = new User_GradeModel;
        //升级判断
        $res_flag = $User_GradeModel->upGrade($buyer_id, $resource_row['user_growth']);

        //积分
        $points_row['user_id']           = $buyer_id;
        $points_row['user_name']         = $buyer_name;
        $points_row['class_id']          = Points_LogModel::ONBUY;
        $points_row['points_log_points'] = $user_points;
        $points_row['points_log_time']   = get_date_time();
        $points_row['points_log_desc']   = '确认收货';
        $points_row['points_log_flag']   = 'confirmorder';
        $Points_LogModel = new Points_LogModel();
        $Points_LogModel->addLog($points_row);

        //成长值
        $grade_row['user_id']         = $buyer_id;
        $grade_row['user_name']       = $buyer_name;
        $grade_row['class_id']        = Grade_LogModel::ONBUY;
        $grade_row['grade_log_grade'] = $user_grade;
        $grade_row['grade_log_time']  = get_date_time();
        $grade_row['grade_log_desc']  = '确认收货';
        $grade_row['grade_log_flag']  = 'confirmorder';
        $Grade_LogModel = new Grade_LogModel();
        $Grade_LogModel->addLog($grade_row);

		if($paymentMethod != 1)    //不是现金支付
		{
			//支付中心生成订单
			$key      							= Yf_Registry::get('shop_api_key');
			$url         						= Yf_Registry::get('paycenter_api_url');
			$shop_app_id 						= Yf_Registry::get('shop_app_id');

			$formvars 							= array();
			$formvars['app_id']					= $shop_app_id;
			$formvars['from_app_id'] 			= Yf_Registry::get('shop_app_id');
			$formvars['consume_trade_id']     	= $order_row['order_id'];
			$formvars['order_id']             	= $order_row['order_id'];
			$formvars['buy_id']               	= $buyer_id;
			$formvars['buyer_name'] 		   	= $buyer_name;
			$formvars['seller_id']            	= $order_row['seller_user_id'];
			$formvars['seller_name']		   	= $order_row['seller_user_name'];
			$formvars['order_state_id']       	= $order_row['order_status'];
			$formvars['order_payment_amount'] 	= $order_row['order_payment_amount'];
			$formvars['trade_remark']         	= $order_row['order_message'];
			$formvars['trade_create_time']    	= $order_row['order_create_time'];
			$formvars['trade_title']			= $trade_title;		//商品名称 - 标题
			fb($formvars);

			//支付中心添加交易流水
			$rsc = get_url_with_encrypt($key, sprintf('%sindex.php?ctl=Api_Pay_Pay&met=addConsumeTrade&typ=json',$url), $formvars);
			fb("合并支付返回的结果");
			//将合并支付单号插入数据库
			if($rsc['status'] == 200)
			{
				$this->Order_BaseModel->editBase($order_id,array('payment_number' => $rsc['data']['union_order']));
			}

			$uprice += $order_row['order_payment_amount'];
			$inorder .= $order_id . ',';

			$utrade_title .=$trade_title;

			//生成合并支付订单
			$key      					= Yf_Registry::get('shop_api_key');
			$url         				= Yf_Registry::get('paycenter_api_url');
			$shop_app_id 				= Yf_Registry::get('shop_app_id');

			$formvars 		         	= array();
			$formvars['inorder']     	= $inorder;
			$formvars['uprice']      	= $uprice;
			$formvars['buyer']       	= $buyer_id;
			$formvars['trade_title'] 	= $utrade_title;
			$formvars['buyer_name']  	= $buyer_name;
			$formvars['app_id']      	= $shop_app_id;
			$formvars['from_app_id'] 	= Yf_Registry::get('shop_app_id');

			fb($formvars);
			//添加合并订单
			$rs = get_url_with_encrypt($key, sprintf('%sindex.php?ctl=Api_Pay_Pay&met=addUnionOrder&typ=json', $url), $formvars);

			$data = array();

			if ($rs['status'] == 200)
			{
				$uorder = $rs['data']['uorder'];   //合并订单号

				if($paymentMethod == 2) //账户余额支付
				{
					$key       =   Yf_Registry::get('shop_api_key');
					$formvars  = array();
					$formvars['app_id'] =   Yf_Registry::get('shop_app_id');
					$formvars['trade_id'] = $uorder;
					$formvars['union_money_pay_amount']  =  $order_row['order_payment_amount'];

					$pay_res = get_url_with_encrypt($key, sprintf('%sindex.php?ctl=Api_Pay_Pay&met=money&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
					fb($pay_res);
				}
				elseif($paymentMethod==3)	//微信支付
				{

					$key      					= Yf_Registry::get('shop_api_key');
					$url         				= Yf_Registry::get('paycenter_api_url');
					$shop_app_id 				= Yf_Registry::get('shop_app_id');

					$formvars 					= array();
					$formvars['uorder_id'] 		= $uorder;
					$formvars['card_payway'] 	= "false";
					$formvars['money_payway'] 	= "false";
					$formvars['online_payway'] 	= "wx_native";
					$formvars['app_id']        	= $shop_app_id;  //webpos可能更改
					$formvars['from_app_id'] 	= $shop_app_id;

					fb($formvars);

					$pay_res = get_url_with_encrypt($key, sprintf('%sindex.php?ctl=Api_Pay_Pay&met=checkPayWay&typ=json', $url), $formvars);
					fb($pay_res);
				}
			}
			else
			{
				$uorder = '';
			}
		}
		else        //现金支付，返回空的合并订单号
		{
			$uorder = '';
		}


		if ($flag && $this->Order_BaseModel->sql->commitDb())
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$this->Order_BaseModel->sql->rollBackDb();
			$m      = $this->Order_BaseModel->msg->getMessages();
			$msg    = $m ? $m[0] : __('failure');
			$status = 250;
		}

		$data['id'] 		= $order_id;
		$data['order_id'] 	= $order_id;
		$data['uorder'] 	= $uorder;
//        var_dump('end');
		$this->data->addBody(-140, $data, $msg, $status);
	}


	/*更新订单信息
	* 订单详情信息
	*/
	public function updateOrderInfo()
	{
		$order_id = request_string('id');

		$data_order_row = $this->Order_BaseModel->getOrderDetail($order_id);
		if($data_order_row)
		{
			$data_order_row['id']               = $data_order_row['order_id'];
			$data_order_row['contactName']      = $data_order_row['buyer_user_name'];  //买家用户名
			$data_order_row['salesId']          = $data_order_row['seller_user_id'];
			$data_order_row['shopId']           = $data_order_row['shop_id'];
			$data_order_row['totalTaxAmount']   = 0;					//价税合计

			$data_order_row['disAmount']        = $data_order_row['order_goods_amount'] - $data_order_row['order_payment_amount'];     //优惠金额
			$data_order_row['totalDiscount']    = $data_order_row['order_goods_amount'] - $data_order_row['order_payment_amount'];    //总折扣
            $data_order_row['disRate']          = $data_order_row[''];    	//优惠率
			$data_order_row['totalAmount']      = $data_order_row['order_payment_amount'];	//订单总金额
			$data_order_row['amount']           = $data_order_row['order_goods_amount'];		//所有商品总价格
			$data_order_row['date']             = date("Y-m-d",strtotime($data_order_row['order_create_time']));
			$data_order_row['modifyTime']       = $data_order_row['order_create_time'];

			$data_order_row['status'] = 'view';
			$data_order_row['checked'] = 1;

			$data=$data_order_row;
            $entries = array();

            if($data_order_row['goods_list'])
            {
                foreach($data_order_row['goods_list'] as $key=>$value)
                {
                    $entries[$key]['goods_id'] 		= $value['goods_id'];
                    $entries[$key]['goods_code'] 	= $value['goods_code'];
					$entries[$key]['goods'] 		= $value['goods_name'];
					$entries[$key]['goods_name'] 	= $value['goods_name'];
					$entries[$key]['cat_id'] 		= $value['goods_class_id'];
					$entries[$key]['pic'] 			= $value['goods_image'];
					$entries[$key]['price'] 		= $value['goods_price'];
					$entries[$key]['amount'] 		= $value['order_goods_amount'];
                    $entries[$key]['qty'] 			= $value['order_goods_num'];
                    $entries[$key]['deduction'] 	= $value['order_goods_discount_fee'];
                    $entries[$key]['discountRate'] 	= ($value['order_goods_amount']/$value['order_goods_payment_amount'])*100;
                    $entries[$key]['skuName'] 		= $value['order_spec_info'];
                    $entries[$key]['shop_id'] 		= $value['shop_id'];
                    $entries[$key]['shop_name'] 	= $data_order_row['shop_name'];
                }
            }
            $data['entries'] = $entries;
            $data['totalQty'] = array_sum(array_column($data_order_row['goods_list'],'order_goods_num'));

			$this->data->addBody(-140, $data, 'success', 200);
		}

	}

	/*订单退款*/
	public function orderReturn()
	{
		/*include("../module/product/includes/plugin_refund_class.php");
		$refund = new refund();
		$id = $_REQUEST['id'];
		$paymentMethod = $_REQUEST['paymentMethod'];

		//获取该订单产品的信息
		$sql = "SELECT * FROM mallbuilder_product_order_pro WHERE id=$id";
		$db->query($sql);
		$re = $db->fetchRow();

		//卖家账户
		if($_SESSION['IDENTITY']!=1){
			$userid = $_SESSION['ADMIN_USER_ID'];
			$sql = "select pay_id,pay_email from pay_member where userid='$userid'";
			$db->query($sql);
			$re2 = $db->fetchRow();
		}else{
			$sql = "select pay_id,pay_email from pay_member where pay_email='admin@systerm.com'";
			$db->query($sql);
			$re2 = $db->fetchRow();
		}

		//获取卖家的id
		$sql = "SELECT member_id FROM mallbuilder_product WHERE id=$re[pid]";
		$db->query($sql);
		$seller_id = $db->fetchField('member_id');

		//申请退货，插入订单退货表
		$T = time();
		$R = "R".$T;
		$sql="insert into mallbuilder_return (order_id,refund_id,product_id,seller_id,member_id,refund_price,create_time,reason,status,goods_status,type) values ('$re[order_id]','".$R."','$re[id]','$seller_id','$re[buyer_id]','$re[taxAmount]','".time()."','','5','1','2')";
		$db->query($sql);

		$msg = "买家于 ".date("Y-m-d H:i:s",$T)." 创建了退款申请。退款金额：$re[price]元";
		$refund->add_talk($R,$re['order_id'],$msg,'');

		if($paymentMethod==2)
		{
			$commission = $re['commission'];
			//退货 更改状态
			$sql="update pay_cashflow set refund_amount = refund_amount + '$re[taxAmount]' , is_refund = 'true' where order_id='$re[order_id]'";
			$db->query($sql);

			//返还钱
			$sql="update pay_member set cash = cash + '$re[taxAmount]' where userid='$re[buyer_id]'";
			$db->query($sql);

			//卖家减钱
			$sql = "update pay_member set cash = cash - '$re[taxAmount]' where pay_id='$re2[pay_id]'";
			$db->query($sql);

			if($_SESSION['IDENTITY']!=1&&$commission>0){
				//--------------写入流水账
				$post['type']=1;//直接到账
				$post['action']='add';//
				$post['buyer_email']='0';//
				$post['seller_email']=$re2[pay_email];//
				$post['order_id']='C'.time();//外部订单号
				$post['extra_param'] = 'Commission';
				$post['price'] = $commission;//订单总价，单价元
				$post['name1'] = '线下订单'.$re[order_id].'佣金退还';
				$post['name'] = '线下订单'.$re[order_id].'佣金退还';
				pay_get_url($post,true);//跳转至订单生成页面
			}
		}

		//更改订单产品状态
		$sql = "UPDATE mallbuilder_product_order_pro set `status`=5 where id=$id";
		$db->query($sql);


		$msg = "卖家于 ".date("Y-m-d H:i:s",time())." 同意退款申请。";
		$refund->add_talk($R,$re['order_id'],$msg,'');
		echo '{"status":200,"msg":"success"}';
		die;*/




	}
	
	public function printOrder()
	{
		$order_id = request_string('id');
		$data = array();
		if($order_id)
		{
			//订单信息
			$data['order'] = $this->Order_BaseModel->getOrderDetail($order_id);

			//买家信息
			$User_infoModel     = new User_InfoModel();
			$data['buyer'] 		= $User_infoModel->getOne($data['order']['buyer_user_id']);
			$data['buyer']['name'] = $data['buyer']['user_realname']?$data['buyer']['user_realname']:$data['buyer']['user_name'];

			$this->data->addBody(-140, $data);
		}
	}
		
	
}

?>

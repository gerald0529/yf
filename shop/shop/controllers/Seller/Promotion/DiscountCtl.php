<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     yesai
 */
class Seller_Promotion_DiscountCtl extends Seller_Controller
{
	public $discountBaseModel  = null;
	public $discountGoodsModel = null;
	public $discountQuotaModel = null;
	public $goodsBaseModel     = null;
	public $shopCostModel      = null;
	public $shopBaseModel      = null;
	
	public $combo_flag        = false;
	public $shop_info         = array();  //店铺信息
	public $self_support_flag = false;    //是否为自营店铺

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

		if (!Web_ConfigModel::value('promotion_allow')) //团购功能设置，关闭，跳转到卖家首页
		{
			if ("e" == $this->typ)
			{
				$this->view->setMet('error');
				include $this->view->getView();
				die;
			}
			else
			{
				$data = new Yf_Data();
				$data->setError(__('限时折扣功能已关闭'), 30);
				$d = $data->getDataRows();

				$protocol_data = Yf_Data::encodeProtocolData($d);
				echo $protocol_data;
				exit();
			}
		}

		$this->discountBaseModel  = new Discount_BaseModel();
		$this->discountGoodsModel = new Discount_GoodsModel();
		$this->discountQuotaModel = new Discount_QuotaModel();
		$this->goodsBaseModel     = new Goods_BaseModel();
		$this->shopCostModel      = new Shop_CostModel();
		$this->shopBaseModel      = new Shop_BaseModel();

		$this->shop_info         = $this->shopBaseModel->getOne(Perm::$shopId);//店铺信息
		$this->self_support_flag = ($this->shop_info['shop_self_support'] == "true" || Web_ConfigModel::value('promotion_discount_price') == 0) ? true : false; //是否为自营店铺标志
		if ($this->self_support_flag) //平台店铺，没有套餐限制
		{
			$this->combo_flag = true;
		}
		else
		{
			$this->combo_flag = $this->discountQuotaModel->checkQuotaStateByShopId(Perm::$shopId);//普通店铺需要查询套餐状态
		}

	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function index()
	{
        $data   = array();
		$combo_row = array();

        if(request_string('op') == 'manage')
        {
            $discount_id = request_int('id');

            if ($discount_id)
            {
                $cond_row['discount_id']     = $discount_id;
                $cond_row['shop_id']         = Perm::$shopId;
                $data['discount_detail']     = $this->discountBaseModel->getDiscountActInfo($cond_row);
                $data['discount_goods_rows'] = $this->discountGoodsModel->getDiscountGoods($cond_row, array('discount_goods_id' => 'DESC'));
            }
            else
            {
                location_go_back('活动不存在');
            }

            $this->view->setMet('manage');
        }
        else
        {
            $Yf_Page           = new Yf_Page();
            $Yf_Page->listRows = request_int('listRows')?request_int('listRows'):10;
            $rows              = $Yf_Page->listRows;
            $offset            = request_int('firstRow', 0);
            $page              = ceil_r($offset / $rows);

            $cond_row['shop_id'] = Perm::$shopId;         //店铺ID

            if (request_string('keyword'))
            {
                $cond_row['discount_name:LIKE'] = "%".request_string('keyword') . "%";
            }
            if (request_int('state'))
            {
                $cond_row['discount_state'] = request_int('state');
            }

            $data               = $this->discountBaseModel->getDiscountActList($cond_row, array('discount_id' => 'DESC'), $page, $rows);
            $Yf_Page->totalRows = $data['totalsize'];
            $page_nav           = $Yf_Page->prompt();
        }

        $shop_type = $this->self_support_flag;

        if (!$this->self_support_flag)  //普通店铺
        {
            $com_flag = $this->combo_flag;

            if ($this->combo_flag)//套餐可用
            {
                $combo_row = $this->discountQuotaModel->getDiscountQuotaByShopID(Perm::$shopId);
            }
        }

		if('json' == $this->typ)
		{
			$json_data['data']       = $data;
			$json_data['shop_type']  = $shop_type;
			$json_data['combo_flag'] = $this->combo_flag;
			$json_data['combo_row']  = $combo_row;

			$this->data->addBody(-140, $json_data);
		}
		else
		{
			include $this->view->getView();
		}

	}


	/**
	 *自营店铺无需判断套餐是否可用
	 */
	public function add()
	{
		$data      = array();
		$combo     = array();
		$shop_type = $this->self_support_flag;

		if (!$this->self_support_flag)  //普通店铺
		{
			if (!$this->combo_flag)
			{
				location_to(Yf_Registry::get('url') . '?ctl=Seller_Promotion_Discount&met=combo&typ=e');
			}
			else
			{
				$combo = $this->discountQuotaModel->getDiscountQuotaByShopID(Perm::$shopId);
			}
		}
		else // 自营店铺
		{
			$combo['combo_end_time'] = date("Y-m-d H:i:s", strtotime("11 june 2030"));
		}

		if (request_string('op') == 'edit')
		{
			$cond_row['discount_id'] = request_int('id');
			$cond_row['shop_id']     = Perm::$shopId;
			$data                    = $this->discountBaseModel->getDiscountActInfo($cond_row);

			$this->view->setMet('edit');
		}

		if('json' == $this->typ)
		{
			$json_data['data']		= $data;
			$json_data['shop_type']	= $shop_type;	//店铺类型
			$json_data['combo']		= $combo; 		//套餐信息

			$this->data->addBody(-140, $json_data);
		}
		else
		{
			include $this->view->getView();
		}

	}

    /**添加加价购活动
     * 注意：同一个限时折扣活动可以有多个商品参加
     * 商品的购买下限设置只是针对每个商品而言
     * 参加同一活动的多个商品数量不可累加作为满足最低购买数量的限定标准
     * 后期如需调整规则可在此基础上进行修改
     */
	public function addDiscount()
	{
		if (!$this->combo_flag)
		{
			$flag = false;
		}
		else
		{
            $check_post_data = true;

            $field_row                         = array();
            $field_row['discount_name']        = request_string('discount_name');
            if(empty( $field_row['discount_name']))
            {
                $check_post_data = false;
                $msg_label = __('活动名称不能为空！');
            }
            $field_row['discount_title']       = request_string('discount_title');
            $field_row['discount_explain']     = request_string('discount_explain');
            $field_row['discount_start_time']  = request_string('discount_start_time');
            $field_row['discount_end_time']    = request_string('discount_end_time');

            if (empty( $field_row['discount_end_time']))
            {
                $check_post_data = false;
                $msg_label = __('活动结束时间不能为空！');
            }

            $field_row['discount_lower_limit'] = request_int('discount_lower_limit');

            if ($field_row['discount_lower_limit'] <= 0)
            {
                $check_post_data = false;
                $msg_label = __('活动商品购买下限必须为正整数！');
            }

            if (!$this->self_support_flag)
            {
                $combo                        = $this->discountQuotaModel->getDiscountQuotaByShopID(Perm::$shopId);
                $field_row['combo_id']       = $combo['combo_id'];
            }

            $field_row['user_id']        = Perm::$userId;
            $field_row['user_nick_name'] = Perm::$row['user_account'];
            $field_row['shop_id']        = Perm::$shopId;
            $field_row['shop_name']      = $this->shop_info['shop_name'];
            $field_row['discount_state'] = Discount_BaseModel::NORMAL;

            if ($check_post_data)
            {
                $flag = $discount_id = $this->discountBaseModel->addDiscountActivity($field_row, true);
            }
            else
            {
                $flag = false;
            }

		}

		if ($flag)
		{
			$msg    = __('添加成功!');
			$status = 200;
		}
		else
		{
			$msg    = $msg_label?$msg_label:__('添加失败！');
			$status = 250;
		}
		$data['discount_id'] = $discount_id;
		
		$this->data->addBody(-140, $data, $msg, $status);
	}

	//编辑活动
	public function editDiscount()
	{
		if (!$this->combo_flag)
		{
			$flag = false;
		}
		else
		{
            $discount_id = request_int('discount_id');
            $check_right = $this->discountBaseModel->getOne($discount_id);

            if ($check_right['shop_id'] == Perm::$shopId)
            {
                $check_post_data = true;

                $field_row['discount_name']        = request_string('discount_name');

                if (empty($field_row['discount_name']))
                {
                    $check_post_data = false;
                    $msg_label = __('活动名称不能为空！');
                }

                $field_row['discount_title']       = request_string('discount_title');
                $field_row['discount_explain']     = request_string('discount_explain');
                $field_row['discount_lower_limit'] = request_int('discount_lower_limit');

                if ($field_row['discount_lower_limit'] <= 0)
                {
                    $check_post_data = false;
                    $msg_label = __('活动商品购买下限必须为正整数！');
                }

                if ($check_post_data)
                {
                    $this->discountBaseModel->editDiscountActInfo($discount_id, $field_row);
                    $discountgoods_list = $this->discountGoodsModel->getByWhere(['discount_id'=>$discount_id]);
                    foreach($discountgoods_list as $v){
                        $in[] = $v['discount_goods_id'];
                    }
                    unset($field_row['discount_lower_limit']);
                    $field_row['goods_lower_limit'] = request_int('discount_lower_limit');
                    if($in){
                        $this->discountGoodsModel->editDiscountGoods($in,$field_row);
                    }

                    $flag = true;
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
		
		if ($flag)
		{
			$msg    = __('编辑成功！');
			$status = 200;
		}
		else
		{
			$msg    = $msg_label?$msg_label:__('编辑失败！');
			$status = 250;
		}
		$data['discount_id'] = $discount_id;
		
		$this->data->addBody(-140, $data, $msg, $status);
	}

	//添加活动商品
	public function addDiscountGoods()
	{
		if (!$this->combo_flag)
		{
			$msg_label    = __('您尚未购买套餐或套餐已到期！');
			$flag   = false;
		}
		else
		{
            $discount_id                                         = request_int('discount_id');
            $cond_row_discount_base['shop_id']                   = Perm::$shopId;
            $cond_row_discount_base['discount_id']               = $discount_id;
            $cond_row_discount_base['discount_state']            = Discount_BaseModel::NORMAL;
            $cond_row_discount_base['discount_end_time:>=']      = get_date_time();
			$discount_base_row = $this->discountBaseModel->getDiscountActInfo($cond_row_discount_base);

            fb($cond_row_discount_base);
            fb($discount_base_row);
            if (empty($discount_base_row))
            {
                $msg_label    = __('活动不存在或状态不可用！');
                $flag   = false;
            }
            else
            {
                //检查商品是否已经加入过同时段的活动
                $discount_goods_rows1              = array();
                $discount_goods_rows2              = array();
                $cond_row1                         = array();
                $cond_row1['goods_id']             = request_int('goods_id');
                $cond_row1['discount_goods_state'] = Discount_goodsModel::NORMAL;
                $cond_row1['goods_start_time:<=']  = $discount_base_row['discount_start_time'];
                $cond_row1['goods_end_time:>=']    = $discount_base_row['discount_start_time'];
                $discount_goods_rows1              = $this->discountGoodsModel->getDiscountGoodsByWhere($cond_row1);

                $cond_row2                         = array();
                $cond_row2['goods_id']             = request_int('goods_id');
                $cond_row2['discount_goods_state'] = Discount_goodsModel::NORMAL;
                $cond_row2['goods_start_time:<=']  = $discount_base_row['discount_end_time'];
                $cond_row2['goods_end_time:>=']    = $discount_base_row['discount_end_time'];
                $discount_goods_rows2              = $this->discountGoodsModel->getDiscountGoodsByWhere($cond_row2);

                if (!empty($discount_goods_rows1) || !empty($discount_goods_rows2))
                {
                    $msg_label    = __('该商品已参加过同时段的活动！');
                    $flag         = false;
                }
                else
                {
                    $check_post_data = true;
                    $field_row['discount_price'] = request_float('discount_price');  //商品折扣价
                    $field_row['goods_id']       =  request_int('goods_id');          //商品goods_id

                    $cond_row_goods_base['goods_id'] = $field_row['goods_id'];
                    $cond_row_goods_base['shop_id']  = Perm::$shopId;
                    $goodsBaseModel = new Goods_BaseModel();
                    $goods_base_row = $goodsBaseModel->getOneByWhere($cond_row_goods_base);

                    if (!empty($goods_base_row))
                    {
                        $field_row['goods_price']    = $goods_base_row['goods_price']; //商品原价
                        $field_row['common_id']      = $goods_base_row['common_id'];    //商品common_id
                        $field_row['goods_name']     = $goods_base_row['goods_name'];  //商品名
                        if ($field_row['discount_price'] <= 0)
                        {
                            $check_post_data = false;
                            $msg_label = __('请填写商品的折扣价格！');
                        }
                        else
                        {
                            if ($field_row['discount_price'] >= $field_row['goods_price'])
                            {
                                $check_post_data = false;
                                $msg_label = __('折扣价格必须小于商品价格！');
                            }
                        }
                    }
                    else
                    {
                        $check_post_data = false;
                        $msg_label = __('请选择参加活动的商品！');
                    }

                    $field_row['discount_id']               = $discount_id;
                    $field_row['shop_id']                   = Perm::$shopId;
                    $field_row['discount_name']            = $discount_base_row['discount_name'];
                    $field_row['discount_title']           = $discount_base_row['discount_title'];
                    $field_row['discount_explain']         = $discount_base_row['discount_explain'];
                    $field_row['goods_start_time']         = $discount_base_row['discount_start_time'];
                    $field_row['goods_end_time']           = $discount_base_row['discount_end_time'];
                    $field_row['goods_lower_limit']        = $discount_base_row['discount_lower_limit'];
                    $field_row['discount_goods_state']     = Discount_GoodsModel::NORMAL;
                    $field_row['discount_goods_recommend'] = Discount_GoodsModel::UNRECOMMEND;

                    if ($check_post_data)
                    {
                        $rs_row = array();

                        $this->discountGoodsModel->sql->startTransactionDb();

                        $insert_flag = $this->discountGoodsModel->addDiscountGoods($field_row, true);
                        check_rs($insert_flag, $rs_row);

                        $Goods_CommonModel = new Goods_CommonModel();
                        $update_flag       = $Goods_CommonModel->editCommon(request_int('common_id'), array('common_is_xian' => 1));
                        check_rs($update_flag, $rs_row);

                        if (is_ok($rs_row) && $this->discountGoodsModel->sql->commitDb())
                        {
                            $flag = true;
                        }
                        else
                        {
                            $this->discountGoodsModel->sql->rollBackDb();
                            $flag = false;
                        }
                    }
                    else
                    {
                        $flag = false;
                    }
                }
            }
		}

        if ($flag)
        {
            $msg    = __('添加成功！');
            $status = 200;
        }
        else
        {
            $msg    = $msg_label?$msg_label:__('商品添加失败！');
            $status = 250;
        }

		$data['discount_goods_id'] = $insert_flag?$insert_flag:0;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	//编辑活动商品价格
	public function editDiscountGoodsPrice()
	{
		$data = array();

        if ($this->combo_flag)
        {
			$discount_goods_id           = request_int('discount_goods_id');
            $field_row['discount_price'] = request_float('discount_price');

            $cond_row_discount_goods['discount_goods_id']   = $discount_goods_id;
            $cond_row_discount_goods['shop_id']             = Perm::$shopId;
            $discount_goods_row = $this->discountGoodsModel->getDiscountGoodsDetailByWhere($cond_row_discount_goods);

            if ($discount_goods_row)
            {
                $check_post_data = true;

                if ($field_row['discount_price'] <= 0)
                {
                    $check_post_data = false;
                    $msg_label = __('请输入商品折扣价格！');
                }
                else
                {
                    if ($field_row['discount_price'] >= $discount_goods_row['goods_price'])
                    {
                        $check_post_data = false;
                        $msg_label = __('折扣价格必须低于商品价格！');
                    }
                }

                if ($check_post_data)
                {
                    $this->discountGoodsModel->editDiscountGoods($discount_goods_id, $field_row);
                    $flag                      = true;
                    $data                      = $field_row;
                    $data['discount_goods_id'] = $discount_goods_id;
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
        else
        {
            $flag = false;
            $msg_label = __('套餐不可用！');
        }

		if ($flag)
		{
			$msg    = __('操作成功！');
			$status = 200;
		}
		else
		{
			$msg    = $msg_label?$msg_label:__('操作失败！');
			$status = 250;
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/*
	 *删除活动
	 *删除活动下的商品
	 *此处需要开启事务
	*/
	public function removeDiscountAct()
	{
		$discount_ids = request_row('id');

        if (is_array($discount_ids)) {
            foreach ($discount_ids as $discount_id){
                $check_right = $this->discountBaseModel->getOne($discount_id);
            }
        }

		if ($check_right['shop_id'] == Perm::$shopId)
		{
			$this->discountBaseModel->sql->startTransactionDb(); //开启事务

			$flag = $this->discountBaseModel->removeDiscountActItem($discount_id);

			if ($flag && $this->discountBaseModel->sql->commitDb())
			{
				$msg    = __('删除成功！');
				$status = 200;
			}
			else
			{
				$this->discountBaseModel->sql->rollBackDb();
				$msg    = __('删除失败！');
				$status = 250;
			}
		}
		else
		{
			$msg    = __('删除失败！');
			$status = 250;
		}

		$data['discount_id'] = $discount_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function getShopGoods()
	{
		$cond_row = array();

		//分页
		$Yf_Page           = new Yf_Page();
		$Yf_Page->listRows = request_int('listRows')?request_int('listRows'):12;
		$rows              = $Yf_Page->listRows;
		$offset            = request_int('firstRow', 0);
		$page              = ceil_r($offset / $rows);

		$cond_row['shop_id'] = Perm::$shopId;

		$goods_name = request_string('goods_name');
		if ($goods_name)
		{
			$cond_row['common_name:LIKE'] = "%".$goods_name . "%";
		}
        $Goods_CommonModel = new Goods_CommonModel();
        //虚拟商品只参加团购活动
        $cond_row['common_is_virtual'] = Goods_CommonModel::GOODS_NORMAL;
        //获取一键代发的商品
        $shop_id = Perm::$shopId;
        $supplier_end_list = $Goods_CommonModel->getSupplierSendCommonByShopId($shop_id);
        $supplier_end_common_id = is_array($supplier_end_list) ? array_column($supplier_end_list, 'common_id') : array();
        if($supplier_end_common_id){
            $cond_row['common_id:not in'] = $supplier_end_common_id;
        }
		$data              = $Goods_CommonModel->getNormalSateGoodsBase($cond_row, array('goods_id' => 'DESC'), $page, $rows);

        if($data['items']){
            //如果商品参加活动标记
            $goods_ids = array();
            foreach ($data['items'] as $value){
                $goods_ids[] = $value['goods_ids']; 
            }
            $GroupBuy_BaseModel = new GroupBuy_BaseModel();
            $check_goods_ids = $GroupBuy_BaseModel->getAllActivityGoodsId($goods_ids);
        
            foreach ($data['items'] as $key=>$val){
                if(in_array($val['goods_id'], $check_goods_ids))
                {
                    $data['items'][$key]['is_join'] = 'true';
                }
            }
        }
        
		$Yf_Page->totalRows = $data['totalsize'];
		$page_nav           = $Yf_Page->prompt();
		if('json' == $this->typ)
		{
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}
	}

	public function removeDiscountGoods()
	{
		$discount_goods_ids = request_row('id');
        if (is_array($discount_goods_ids)) {
            foreach ($discount_goods_ids as $discount_goods_id){
                $check_right       = $this->discountGoodsModel->getOne($discount_goods_id);
            }
        }
		if ($check_right['shop_id'] == Perm::$shopId)
		{
			$this->discountGoodsModel->sql->startTransactionDb(); //开启事务

			$flag = $this->discountGoodsModel->removeDiscountGoods($discount_goods_id);

			if ($flag && $this->discountGoodsModel->sql->commitDb())
			{
				$msg    = __('删除成功');
				$status = 200;
			}
			else
			{
				$this->discountGoodsModel->sql->rollBackDb();
				$msg    = __('删除失败');
				$status = 250;
			}
		}
		else
		{
			$msg    = __('删除失败');
			$status = 250;
		}

		$data['discount_goods_id'] = $discount_goods_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function combo()
	{
		if ($this->self_support_flag)   //免费发布活动
		{
            location_go_back(__('自营店铺或者套餐续费， 不需要设置。'));
			//location_to('index.php?ctl=Seller_Promotion_Discount&met=add&typ=e');
		}

        //查找出店铺团购活动的消费记录
        $Yf_Page           = new Yf_Page();
        $Yf_Page->listRows = request_int('listRows')?request_int('listRows'):10;
        $rows              = $Yf_Page->listRows;
        $offset            = request_int('firstRow', 0);
        $page              = ceil_r($offset / $rows);

        $cond_row = array();
        $order_row = array();
        $cond_row['shop_id'] = Perm::$shopId;
        $cond_row['activity_type'] = Shop_CostModel::DISCOUNT;
        $data = $this->shopCostModel->listByWhere($cond_row,$order_row,$page, $rows);

        $Yf_Page->totalRows = $data['totalsize'];
        $page_nav           = $Yf_Page->prompt();

		if('json' == $this->typ)
		{
			//购买活动套餐每个月需支付的金额
			$data['promotion_discount_price'] = Web_ConfigModel::value('promotion_discount_price');
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}

	}

	public function addCombo()
	{
		$data        = array();
		$combo_row   = array();
		$rs_row      = array();
		$month_price = Web_ConfigModel::value('promotion_discount_price');
		$month       = request_int('month');
		$days        = 30 * $month;

		if($month > 0)
		{
			$this->discountQuotaModel->sql->startTransactionDb();

			$field_row['user_id']     = Perm::$row['user_id'];
			$field_row['shop_id']     = Perm::$shopId;
			$field_row['cost_price']  = $month_price * $month;
			$field_row['cost_desc']   = __('店铺购买限时折扣活动消费');
			$field_row['cost_status'] = 0;
			$field_row['cost_time']   = get_date_time();
            $field_row['activity_type']   = Shop_CostModel::DISCOUNT;
            $field_row['activity_price']   = $month_price;
            $field_row['activity_month']   = $month;
			$flag                     = $this->shopCostModel->addCost($field_row, true);
			check_rs($flag, $rs_row);

			if ($flag)
			{
				$combo_row = $this->discountQuotaModel->getDiscountQuotaByShopID(Perm::$shopId);
				//记录已经存在，套餐续费
				if ($combo_row)
				{
					//1、原套餐已经过期,更新套餐开始时间和结束时间
					if (strtotime($combo_row['combo_end_time']) < time())
					{
						$field['combo_start_time'] = get_date_time();
						$field['combo_end_time']   = date('Y-m-d H:i:s', strtotime("+$days days"));
					}
					elseif ((time() >= strtotime($combo_row['combo_start_time'])) && (time() <= strtotime($combo_row['combo_end_time'])))
					{
						//2、原套餐尚未过期，只需更新结束时间
						$field['combo_end_time'] = date('Y-m-d H:i:s', strtotime("+$days days", strtotime($combo_row['combo_end_time'])));
					}
					$op_flag = $this->discountQuotaModel->renewDiscountCombo($combo_row['combo_id'], $field);
				}
				else            //记录不存在，添加套餐
				{
					$shop_row = $this->shopBaseModel->getBaseOneList(array('shop_id' => Perm::$shopId));

					$field['combo_start_time'] = get_date_time();
					$field['combo_end_time']   = date('Y-m-d H:i:s', strtotime("+$days days"));
					$field['shop_id']          = Perm::$shopId;
					$field['shop_name']        = $shop_row['shop_name'];
					$field['user_id']          = Perm::$userId;
					$field['user_nickname']    = Perm::$row['user_account'];
					$op_flag                   = $this->discountQuotaModel->addDiscountCombo($field, true);
				}
				check_rs($op_flag, $rs_row);
			}

            if(is_ok($rs_row))
            {
                //在paycenter中添加交易记录
                $key      = Yf_Registry::get('shop_api_key');
                $url         = Yf_Registry::get('paycenter_api_url');
                $shop_app_id = Yf_Registry::get('shop_app_id');

                $formvars             = array();
                $formvars['app_id']        = $shop_app_id;
                $formvars['buyer_user_id'] = Perm::$userId;
                $formvars['buyer_user_name'] = Perm::$row['user_account'];
                $formvars['amount'] = $month_price * $month;

                $rs                   = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=addCombo&typ=json', $url), $formvars);
            }
			if (is_ok($rs_row) && isset($rs) && $rs['status'] == 200 && $this->discountQuotaModel->sql->commitDb())
			{
				$msg    = __('操作成功！');
				$status = 200;
			}
			else
			{
				$this->discountQuotaModel->sql->rollBackDb();
				$msg    = __('操作失败！');
				$status = 250;
			}
		}
		else
		{
			$msg    = __('购买月份必须为正整数！');
			$status = 250;
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}

    //如果商品本身存在限购，并且限购数量低于活动最低购买下限时，则不能添加为限时折扣商品
    public function check_limit()
    {
        $discount_id = request_int('discount_id');
        $common_id = request_int('common_id');
        $Goods_CommonModel = new Goods_CommonModel();
        $Discount_BaseModel = new Discount_BaseModel();
        $common_list = $Goods_CommonModel->getOneByWhere(array('common_id'=>$common_id));
        $discount_list = $Discount_BaseModel->getOneByWhere(array('discount_id'=>$discount_id));
        $data['discount_lower_limit'] = $discount_list['discount_lower_limit'];  
        $data['common_limit'] = $common_list['common_limit'];  
        if($common_list['common_limit'] <= $discount_list['discount_lower_limit'] && $common_list['common_limit'] > 0)
        {
            $msg = '';
            $status = 200;
        }
        else
        {
            $msg = '';
            $status = 250;
        }
        $this->data->addBody(-140, $data, $msg, $status);
    }
	
}

?>
<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Seller_Promotion_MeetConditionGiftCtl extends Seller_Controller
{
	public $manSongBaseModel  = null;
	public $manSongQuotaModel = null;
	public $manSongRuleModel  = null;
	public $goodsBaseModel    = null;
	public $shopCostModel     = null;
	public $shopBaseModel     = null;

	public $combo_flag        = false;
	public $shop_info         = array();  //店铺信息
	public $self_support_flag = false;    //自营店铺标志

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
				$data->setError(__('满级送功能已关闭'), 30);
				$d = $data->getDataRows();

				$protocol_data = Yf_Data::encodeProtocolData($d);
				echo $protocol_data;
				exit();
			}
		}

		$this->manSongBaseModel  = new ManSong_BaseModel();
		$this->manSongQuotaModel = new ManSong_QuotaModel();
		$this->manSongRuleModel  = new ManSong_RuleModel();
		$this->goodsBaseModel    = new Goods_BaseModel();
		$this->shopCostModel     = new Shop_CostModel();
		$this->shopBaseModel     = new Shop_BaseModel();

		$this->shop_info         = $this->shopBaseModel->getOne(Perm::$shopId);//店铺信息
        //是否为自营店铺标志，平台设置可免费使用的活动，统一当作自营店铺处理
		$this->self_support_flag = ($this->shop_info['shop_self_support'] == "true" || Web_ConfigModel::value('promotion_mansong_price') == 0) ? true : false;
		if ($this->self_support_flag) //平台店铺，没有套餐限制
		{
			$this->combo_flag = true;
		}
		else
		{
			$this->combo_flag = $this->manSongQuotaModel->checkQuotaStateByShopId(Perm::$shopId);//普通店铺需要查询套餐状态
		}
	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function index()
	{
		$data              = array();
		$combo			   = array();
		$Yf_Page           = new Yf_Page();
		$Yf_Page->listRows = request_int('listRows')?request_int('listRows'):10;
		$rows              = $Yf_Page->listRows;
		$offset            = request_int('firstRow', 0);
		$page              = ceil_r($offset / $rows);

		if (request_string('op') == 'detail')
		{
			if (request_int('id'))
			{
				$data = $this->manSongBaseModel->getManSongActItem(array(
																	   'shop_id' => Perm::$shopId,
																	   'mansong_id' => request_int('id')
																   ));
			}

			$this->view->setMet('detail');
		}
		else
		{
			$cond_row = array();

			if (request_int('state'))
			{
				$cond_row['mansong_state'] = request_int('state');
			}
			if (request_string('keyword'))
			{
				$cond_row['mansong_name:LIKE'] = request_string('keyword') . '%';
			}
			$cond_row['shop_id'] = Perm::$shopId;
			$data                = $this->manSongBaseModel->getManSongActList($cond_row, array('mansong_id' => 'ASC'), $page, $rows);
			$Yf_Page->totalRows  = $data['totalsize'];
			$page_nav            = $Yf_Page->prompt();
		}

		$shop_type = $this->self_support_flag;

		if (!$this->self_support_flag)  //普通店铺
		{
			$com_flag = $this->combo_flag;
			if ($this->combo_flag)//套餐可用
			{
				$combo = $this->manSongQuotaModel->getManSongQuotaDetailByWhere(array('shop_id' => Perm::$shopId));
			}
		}

		if('json' == $this->typ)
		{
			$json_data['data'] 		= $data;
			$json_data['shop_type'] = $shop_type;
			$json_data['combo_flag']= $this->combo_flag;
			$json_data['combo'] 	= $combo;
			$this->data->addBody(-140, $json_data);
		}
		else
		{
			include $this->view->getView();
		}

	}

	/**
	 *添加活动
	 */
	public function add()
	{
		$combo     = array();
		$shop_type = $this->self_support_flag;

		if (!$this->self_support_flag)  //普通店铺
		{
			if (!$this->combo_flag)
			{
				location_to(Yf_Registry::get('url') . '?ctl=Seller_Promotion_MeetConditionGift&met=combo&typ=e');
			}
			else
			{
				$combo = $this->manSongQuotaModel->getManSongQuotaDetailByWhere(array('shop_id' => Perm::$shopId));
			}
		}
		else // 自营店铺
		{
			$combo['combo_end_time'] = date("Y-m-d H:i:s", strtotime("11 june 2030"));
		}

		if ($this->getLatestManSongDate())
		{
			$combo['combo_start_time'] = $this->getLatestManSongDate();
		}
		else
		{
			$combo['combo_start_time'] = get_date_time();
		}

		$data['shop_type'] = $shop_type;
		$data['combo']     = $combo;

		if('json' == $this->typ)
		{
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}

	}

	//获取最近一次满送活动的结束时间
	public function getLatestManSongDate()
	{
		$latest_date = '';

		$rows = $this->manSongBaseModel->getManSongByWhere(array('shop_id' => Perm::$shopId));

		if ($rows)
		{
			foreach ($rows as $key => $value)
			{
				if ($value['mansong_end_time'] > $latest_date)
				{
					$latest_date = $value['mansong_end_time'];
				}
			}
		}

		return $latest_date;
	}


	/*
	 * 添加满送活动活动
	 * 满送活动针对的是整个店铺的商品
	 * 只要订单的总金额满足满送规则限制，即可参加活动
	 * 活动规则遵循高规则覆盖地规则准则，规则以规则满足金额升序排序
	 * 满送活动一经编辑发布后不可修改
	 * 同时间段内不可存在交叉的满送活动
	 * */
	public function addManSong()
	{
		$data_re = array();
		$rs_row  = array();

		if ($this->combo_flag)
		{
            $check_post_data_flag = true;
			$mansong_start_date_limit = $this->getLatestManSongDate() ? $this->getLatestManSongDate() : '';

            $mansong_rule             = request_row('mansong_rule');            //活动规则
//			echo '<pre>';print_r($mansong_rule);exit;
            if(!$mansong_rule)
            {
                $check_post_data_flag = false;
                $msg_label = __('最少设置一个满送规则！');
            }

            $field_row['mansong_name']       = request_string('mansong_name'); //活动名称
            if(empty($field_row['mansong_name']))
            {
                $check_post_data_flag = false;
                $msg_label = __('活动名称不能为空！');
            }

            $field_row['mansong_start_time'] = request_string('mansong_start_time');
            if(strtotime(request_string('mansong_start_time')) <= strtotime($mansong_start_date_limit))
            {
                $check_post_data_flag = false;
                $msg_label = __('满级送活动时间段不能重叠！');
            }

            $field_row['mansong_end_time']   = request_string('mansong_end_time');
            //非自营店铺，活动结束时间不能晚于套餐截止日期
            if($this->self_support_flag == false)
            {
                $combo_row = $this->manSongQuotaModel->getManSongQuotaByShopID(Perm::$shopId);

                if(strtotime($field_row['mansong_end_time']) > strtotime($combo_row['combo_end_time']))
                {
                    $check_post_data_flag = false;
                    $msg_label = __('活动结束时间不能晚于套餐有效期！');
                }

            }

            $field_row['mansong_remark']     = request_string('mansong_remark');
            $field_row['shop_id']            = Perm::$shopId;
            $field_row['shop_name']          = $this->shop_info['shop_name'];
            $field_row['user_id']            = Perm::$userId;
            $field_row['mansong_state']      = ManSong_BaseModel::NORMAL;

			//满送活动时间不能出现交叉重叠，即一段时间内只能存在一个有效的满送活动
			if ($check_post_data_flag)
			{
				$this->manSongBaseModel->sql->startTransactionDb();

				$flag                            = $mansong_id = $this->manSongBaseModel->addManSongAct($field_row, true);

				check_rs($flag, $rs_row);

				if ($flag)
				{
					foreach ($mansong_rule as $key => $rule)
					{
						$rule_row                        = array();
						$goods_row                       = array();
						$rule_row                        = explode(',', $rule);
						$field_rule_row['mansong_id']    = $mansong_id;
						$field_rule_row['rule_price']    = $rule_row[0];
						$field_rule_row['rule_discount'] = $rule_row[1];
						$field_rule_row['goods_id']      = $rule_row[2];

						if($field_rule_row['goods_id'])
						{
							$goods_row = $this->goodsBaseModel->getOne($field_rule_row['goods_id']); //检查赠品是否是该店铺的商品

							if ($goods_row['shop_id'] == Perm::$shopId && is_numeric($field_rule_row['rule_price']) && $field_rule_row['rule_price'] > 0 && is_numeric($field_rule_row['rule_discount']))
							{
								$insert_flag = $this->manSongRuleModel->addManSongRule($field_rule_row, true);
								check_rs($insert_flag, $rs_row);
							}
							else
							{
								check_rs(false, $rs_row);
							}
						}
						else
						{
							$insert_flag = $this->manSongRuleModel->addManSongRule($field_rule_row, true);
							check_rs($insert_flag, $rs_row);
						}
					}
				}

				if (is_ok($rs_row) && $this->manSongBaseModel->sql->commitDb())
				{
					$data_re['mansong_id'] = $mansong_id;
					$msg                   = __('添加成功');
					$status                = 200;
				}
				else
				{
					$this->manSongBaseModel->sql->rollBackDb();
					$msg    = __('添加失败');
					$status = 250;
				}
			}
			else
			{
				$msg    = $msg_label?$msg_label:__('添加失败');
				$status = 250;
			}
		}
		else
		{
			$msg    = __('添加失败');
			$status = 250;
		}

		$this->data->addBody(-140, $data_re, $msg, $status);
	}
	
	//删除满送活动和活动下的规则
	public function removeManSong()
	{
		$mansong_ids = request_row('id');
        if (is_array($mansong_ids)) {
            foreach ($mansong_ids as $mansong_id){
                $check_right = $this->manSongBaseModel->getOne($mansong_id);

            }
        }

		if ($check_right['shop_id'] == Perm::$shopId)
		{
			$this->manSongBaseModel->sql->startTransactionDb();

			$flag = $this->manSongBaseModel->removeManSongActItem($mansong_id); //删除满送活动及活动下的规则

			if ($flag && $this->manSongBaseModel->sql->commitDb())
			{
				$msg    = __('删除成功');
				$status = 200;
			}
			else
			{
				$this->manSongBaseModel->sql->rollBackDb();
				$msg    = __('删除失败');
				$status = 250;
			}
		}
		else
		{
			$msg    = __('删除失败');
			$status = 250;
		}

		$data['mansong_id'] = $mansong_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}
	
	//获取店铺商品
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
        //获取一键代发的商品
        $shop_id = Perm::$shopId;
        $supplier_end_list = $Goods_CommonModel->getSupplierSendCommonByShopId($shop_id);
        $supplier_end_common_id = is_array($supplier_end_list) ? array_column($supplier_end_list, 'common_id') : array();
        if($supplier_end_common_id){
            $cond_row['common_id:not in'] = $supplier_end_common_id;
        }
		//2017-08-11加入满即送不能添加虚拟商品
		$cond_row['common_is_virtual'] = Goods_CommonModel::GOODS_NORMAL;
		$data              = $Goods_CommonModel->getNormalSateGoodsBase($cond_row, array('goods_id' => 'DESC'), $page, $rows);
		$Yf_Page->totalRows = $data['totalsize'];
		$page_nav           = $Yf_Page->prompt();
		//店铺下参加满即送活动的商品
        $this->ManSong_BaseModel = new ManSong_BaseModel();
        $this->ManSong_RuleModel = new ManSong_RuleModel();
        $mansong_cond_row['shop_id'] = Perm::$shopId;
        $mansong_cond_row['mansong_state'] = ManSong_BaseModel::NORMAL;
        $mansong_rows = $this->ManSong_BaseModel->getKeyByWhere($mansong_cond_row);
        
        if ($mansong_rows) {
            $cond_rows_join['mansong_id:IN'] = $mansong_rows;
            //店铺已加入加价购活动的商品ID
            $join_goods_id = $this->ManSong_RuleModel->getManSongGoodsIdByWhere($cond_rows_join);
        } else {
            $join_goods_id = array();
        }
        foreach ($data['items'] as $key => $value) {
			if (in_array($value['goods_id'], $join_goods_id) || $value['is_join'] == 'true') {
                $data['items'][$key]['is_join'] = 'true';
            } else {
                $data['items'][$key]['is_join'] = 'false';
            }
        }
		if('json' == $this->typ)
		{
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}

	}

	public function combo()
	{
		if ($this->self_support_flag)   //免费发布活动
		{
            
            location_go_back(__('自营店铺或者套餐续费， 不需要设置。'));
			//location_to('index.php?ctl=Seller_Promotion_MeetConditionGift&met=add&typ=e');
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
		$cond_row['activity_type'] = Shop_CostModel::MEET;
		$data = $this->shopCostModel->listByWhere($cond_row,$order_row,$page, $rows);

		$Yf_Page->totalRows = $data['totalsize'];
		$page_nav           = $Yf_Page->prompt();

		if('json' == $this->typ)
		{
			$data['promotion_mansong_price'] = Web_ConfigModel::value('promotion_mansong_price');
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
		$rs_row      = array();
		$month_price = Web_ConfigModel::value('promotion_mansong_price');
		$month       = request_int('month');
		$days        = 30 * $month;

		if($month > 0)
		{
			$this->manSongQuotaModel->sql->startTransactionDb();

			$field_row['user_id']     = Perm::$row['user_id'];
			$field_row['shop_id']     = Perm::$shopId;
			$field_row['cost_price']  = $month_price * $month;
			$field_row['cost_desc']   = __('店铺购买满送活动消费');
			$field_row['cost_status'] = 0;
			$field_row['cost_time']   = date('Y-m-d H:i:s');
			$field_row['activity_type']   = Shop_CostModel::MEET;
			$field_row['activity_price']   = $month_price;
			$field_row['activity_month']   = $month;
			$flag                     = $this->shopCostModel->addCost($field_row, true);
			check_rs($flag, $rs_row);

			if ($flag)
			{
				$combo_row = $this->manSongQuotaModel->getManSongQuotaByShopID(Perm::$shopId);
				//记录已经存在，套餐续费
				if ($combo_row)
				{
					//1、原套餐已经过期,更新套餐开始时间和结束时间
					if (strtotime($combo_row['combo_end_time']) < time())
					{
						$field['combo_start_time'] = date('Y-m-d H:i:s');
						$field['combo_end_time']   = date('Y-m-d H:i:s', strtotime("+$days days"));
					}
					elseif ((time() >= strtotime($combo_row['combo_start_time'])) && (time() <= strtotime($combo_row['combo_end_time'])))
					{
						//2、原套餐尚未过期，只需更新结束时间
						$field['combo_end_time'] = date('Y-m-d H:i:s', strtotime("+$days days", strtotime($combo_row['combo_end_time'])));
					}
					$op_flag = $this->manSongQuotaModel->renewManSongCombo($combo_row['combo_id'], $field);
				}
				else            //记录不存在，添加套餐
				{
					$shop_row = $this->shopBaseModel->getBaseOneList(array('shop_id' => Perm::$shopId));

					$field['combo_start_time'] = date('Y-m-d H:i:s');
					$field['combo_end_time']   = date('Y-m-d H:i:s', strtotime("+$days days"));
					$field['shop_id']          = Perm::$shopId;
					$field['shop_name']        = $shop_row['shop_name'];
					$field['user_id']          = Perm::$userId;
					$field['user_nickname']    = Perm::$row['user_account'];
					$op_flag                   = $this->manSongQuotaModel->addManSongCombo($field, true);
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

			if (is_ok($rs_row) && isset($rs) && $rs['status'] == 200 && $this->manSongQuotaModel->sql->commitDb())
			{
				$msg    = __('操作成功');
				$status = 200;
			}
			else
			{
				$this->manSongQuotaModel->sql->rollBackDb();
				$msg    = __('操作失败');
				$status = 250;
			}
		}
		else
		{
			$msg    = __('购买月份必须为正整数');
			$status = 250;
		}


		$this->data->addBody(-140, $data, $msg, $status);
	}
	
}

?>
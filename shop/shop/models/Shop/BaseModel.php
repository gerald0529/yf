<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Shop_BaseModel extends Shop_Base
{
	const SHOP_STATUS_OPEN = 3;  //开启

	const SELF_SUPPORT_TRUE  = 'true';
	const SELF_SUPPORT_FALSE = 'false';

	const SHOP_ALL_CLASS_TRUE = 1;

	const ADMIN_SHOP_ID = 0; //admin上传图片
	const ADMIN_USER_ID = 0; //admin上传图片

	public static $shop_status            = array(
		"0" => "关闭",
		"1" => "待审核信息",
		"2" => "待审核付款",
		"3" => "开店成功",
		"4" => "审核未通过",
		"5" => "待审核信息",
		"6" => "待审核信息",
	);
	public static $shop_all_class         = array(
		"0" => "否",
		"1" => "是"
	);
	public static $shop_class_bind_enable = array(
		"1" => "不启用",
		"2" => "启用",
		'0'=> '拒绝'
	);
	public static $shop_grade_name        = '自营店铺';
	public static $shop_payment           = array(
		"0" => "未付款",
		"1" => "已付款"
	);


	/**
	 * 读取店铺列表
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data           = $this->listByWhere($cond_row, $order_row, $page, $rows);
		
		$shopClassModel = new Shop_ClassModel();
		$shopGradeModel = new Shop_GradeModel();
    	$shopCompanyModel   = new Shop_CompanyModel();
    	$User_FavoritesShopModel = new User_FavoritesShopModel();
    	//判断当前是否有用户登录,如果有就查询收藏店铺
    	if(Perm::$row['user_id'])
    	{
	    	//获取当前用户收藏的店铺
	    	$Favorites_row['user_id'] = Perm::$row['user_id'];
	    	$User_FavoritesShop = $User_FavoritesShopModel->getFavoritesShops($Favorites_row);
	    	$FavoritesShopIds = @array_column($User_FavoritesShop['items'],'shop_id');
    	}
    	else
    	{
    		$FavoritesShopIds = array();
    	}

		//把数据库的状态以及分类id 和等级id全部变成中文。
		foreach ($data["items"] as $key => $value)
		{
			$data["items"][$key]["shop_status_cha"]    = __(self::$shop_status[$value["shop_status"]]);
			$data["items"][$key]["shop_all_class_cha"] = __(self::$shop_all_class[$value["shop_all_class"]]);
			$data["items"][$key]["shop_payment_cha"]   = __(self::$shop_payment[$value["shop_payment"]]);
			if ($value['shop_class_id'])
			{
				//获取分类名
				$shop_class_id                     = $value['shop_class_id'];
				$data["items"][$key]["shop_class"] = $shopClassModel->getClassName("$shop_class_id");
			}
			if ($value['shop_grade_id'])
			{
				//获取等级名
				$shop_grade_id                     = $value['shop_grade_id'];
				$data["items"][$key]["shop_grade"] = $shopGradeModel->getGradeName("$shop_grade_id");
			}
			if(!empty($FavoritesShopIds) && in_array($value['shop_id'],$FavoritesShopIds))
			{
				$data["items"][$key]['is_collect'] = 1;
			}
			else
			{
				$data["items"][$key]['is_collect'] = 0;
			}
                        
            $company            = $shopCompanyModel->getOne($value['shop_id']);
            $data['items'][$key] = array_merge($data['items'][$key], $company);

		}
		return $data;
	}

	/**
	 * 根据店铺id 获取该店铺的详细信息
	 *
	 * Zhuyt
	 */
	public function getShopDetail($shop_id)
	{
		$data = $this->getOne($shop_id);

		if ($data)
		{
			$data["shop_status_cha"]    = __(self::$shop_status[$data["shop_status"]]);
			$data["shop_all_class_cha"] = __(self::$shop_all_class[$data["shop_all_class"]]);

			if (!$data['shop_self_support'])
			{
				if ($data['shop_class_id'])
				{
					//获取分类名
					$shopClassModel     = new Shop_ClassModel();
					$shop_class_id      = $data['shop_class_id'];
					$data["shop_class"] = $shopClassModel->getClassName("$shop_class_id");
				}
			}

			if ($data['shop_grade_id'])
			{
				//获取等级名
				$shopGradeModel     = new Shop_GradeModel();
				$shop_grade_id      = $data['shop_grade_id'];
				$data["shop_grade"] = $shopGradeModel->getGradeName("$shop_grade_id");
			}
			else
			{
				$data["shop_grade"] = __(self::$shop_grade_name);
			}

			//获取company信息
			$Shop_CompanyModel = new Shop_CompanyModel();
			$shop_company = $Shop_CompanyModel->getOne($shop_id);
			$data['shop_company_address'] = $shop_company['shop_company_address'];

		}
		$shop_cond_row = [];
		$shop_cond_row['shop_id'] = $shop_id;
		$shop_cond_row['evaluation_desccredit:!='] = 0;
		$shop_cond_row['evaluation_servicecredit:!='] = 0;
		$shop_cond_row['evaluation_deliverycredit:!='] = 0;
		//计算店铺动态评分
		$Shop_EvaluationModel = new Shop_EvaluationModel();
		$shop_evaluation      = $Shop_EvaluationModel->getByWhere($shop_cond_row);
		$desc_scores    = 0;    //描述相符评分
		$service_scores = 0;    //服务态度评分
		$send_scores    = 0;   //发货速度评分
		if($shop_evaluation)
		{
			$evaluation_num       = count($shop_evaluation);

			foreach ($shop_evaluation as $key => $val)
			{
				$desc_scores += $val['evaluation_desccredit'];
				$service_scores += $val['evaluation_servicecredit'];
				$send_scores += $val['evaluation_deliverycredit'];
			}
		}
		if ($evaluation_num)	//是否有评价
		{
			$data['shop_desc_scores']    = round($desc_scores / $evaluation_num, 5);//保留5位小数，使计算更精确，最后与行业平均值比较的时候再保留2位小数
			$data['shop_service_scores'] = round($service_scores / $evaluation_num, 5);
			$data['shop_send_scores']    = round($send_scores / $evaluation_num, 5);
		}
		else
		{
			$data['shop_desc_scores']    = 5;
			$data['shop_service_scores'] = 5;
			$data['shop_send_scores']    = 5;
		}

		$com_cond_row = [];
		$com_cond_row['evaluation_desccredit:!='] = 0;
		$com_cond_row['evaluation_servicecredit:!='] = 0;
		$com_cond_row['evaluation_deliverycredit:!='] = 0;
		//计算行业平均分
		$all_shop_eval      = $Shop_EvaluationModel->getByWhere($com_cond_row);
		$all_eval_num       = count($all_shop_eval);
		$all_desc_scores    = 0;    //描述相符评分
		$all_service_scores = 0;    //服务态度评分
		$all_send_scores    = 0;   //发货速度评分
		$max = $min = $all_shop_eval[0];
		foreach ($all_shop_eval as $key => $val)
		{
			$all_desc_scores += $val['evaluation_desccredit'];
			$all_service_scores += $val['evaluation_servicecredit'];
			$all_send_scores += $val['evaluation_deliverycredit'];
			$max_evaluation_desccredit = ($val['evaluation_desccredit'] > $max['evaluation_desccredit']) ? $val['evaluation_desccredit']:$max['evaluation_desccredit'];
			$max_evaluation_servicecredit = ($val['evaluation_servicecredit'] > $max['evaluation_servicecredit']) ? $val['evaluation_servicecredit']:$max['evaluation_servicecredit'];
			$max_evaluation_deliverycredit = ($val['evaluation_deliverycredit'] > $max['evaluation_deliverycredit']) ? $val['evaluation_deliverycredit']:$max['evaluation_deliverycredit'];
			$min_evaluation_desccredit = ($val['evaluation_desccredit'] < $min['evaluation_desccredit']) ? $val['evaluation_desccredit']:$min['evaluation_desccredit'];
			$min_evaluation_servicecredit = ($val['evaluation_servicecredit'] < $min['evaluation_servicecredit']) ? $val['evaluation_servicecredit']:$min['evaluation_servicecredit'];
			$min_evaluation_deliverycredit = ($val['evaluation_deliverycredit'] < $min['evaluation_deliverycredit']) ? $val['evaluation_deliverycredit']:$min['evaluation_deliverycredit'];
		}

		if ($all_eval_num == 0)
		{
			$data['com_desc_scores']    = 5;
			$data['com_service_scores'] = 5;
			$data['com_send_scores']    = 5;
		}
		else
		{
			$avg_desc_scores    = round($all_desc_scores / $all_eval_num, 5);
			$avg_service_scores = round($all_service_scores / $all_eval_num, 5);
			$avg_send_scores    = round($all_send_scores / $all_eval_num, 5);
             
			//判断描述相符是否大于行业平均分
			if($data['shop_desc_scores'] > $avg_desc_scores) {
				$data['com_desc_scores'] = round(($data['shop_desc_scores'] - $avg_desc_scores)/($max_evaluation_desccredit - $avg_desc_scores)*100, 2);
               
				$data['desc_status'] = 1;
			} else {
				$data['com_desc_scores'] = round(($avg_desc_scores - $data['shop_desc_scores'])/($avg_desc_scores- $min_evaluation_desccredit)*100, 2);

				$data['desc_status'] = 0;
			}
			//判断服务态度是否大于行业平均分
			if($data['shop_service_scores'] > $avg_service_scores) {
				$data['com_service_scores'] = round(($data['shop_service_scores'] - $avg_service_scores)/($max_evaluation_servicecredit - $avg_service_scores)*100, 2);
				$data['service_status'] = 1;
			} else {
				$data['com_service_scores'] = round(($avg_service_scores - $data['shop_service_scores'])/($avg_service_scores- $min_evaluation_servicecredit)*100, 2);
				$data['service_status'] = 0;
			}
			//判断发货速度是否大于行业平均分
			if($data['shop_send_scores'] > $avg_send_scores) {
				$data['com_send_scores'] = round(($data['shop_send_scores'] - $avg_send_scores)/($max_evaluation_deliverycredit - $avg_send_scores)*100, 2);
				$data['send_status'] = 1;
			} else {
				$data['com_send_scores'] = round(($avg_send_scores - $data['shop_send_scores'])/($avg_send_scores- $min_evaluation_deliverycredit)*100, 2);
				$data['send_status'] = 0;
			}
		}

		//获取店铺支持的消费者保障服务
		$Shop_ContractModel = new Shop_ContractModel();
		$contract = $Shop_ContractModel->getByWhere(array('shop_id'=>$shop_id));

		$Shop_ContractTypeModel = new Shop_ContractTypeModel();

		$Shop_ContractLogModel = new Shop_ContractLogModel();
		if($contract)
		{
			foreach($contract as $ckey => $cval)
			{
				$contract_type =  $Shop_ContractTypeModel->getOne($cval['contract_type_id']);
				$contract_log = $Shop_ContractLogModel->getOne($cval['contract_log_id']);


				if($contract_type && $contract_type['contract_type_state'] == Shop_ContractTypeModel::CONTRACT_OPEN && $cval['contract_state'] == Shop_ContractModel::CONTRACT_INUSE && (($cval['contract_use_state'] == Shop_ContractModel::CONTRACT_JOIN  && $contract_log['contract_log_state'] == Shop_ContractLogModel::LOG_STATE_PASS)||($cval['contract_use_state'] == Shop_ContractModel::CONTRACT_QUIT  && $contract_log['contract_log_state'] == Shop_ContractLogModel::LOG_STATE_INCHECK)) )
				{
					$contract[$ckey]['contract_type_logo'] = $contract_type['contract_type_logo'];
					$contract[$ckey]['contract_type_url'] = $contract_type['contract_type_url'];
				}
				else
				{
					unset($contract[$ckey]);
				}



			}
		}

		$data['contract'] = $contract;
        $data['shop_desc_scores'] = round($data['shop_desc_scores'],2);
        $data['shop_service_scores'] = round($data['shop_service_scores'],2);
        $data['shop_send_scores'] = round($data['shop_send_scores'],2);
		return $data;
	}

	/**
	 * 读取单个店铺
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseOneList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->getOneByWhere($cond_row, $order_row, $page, $rows);

		//把数据库的状态以及分类id 和等级id全部变成中文。

		if ($data)
		{
			$data["shop_status_cha"]    = __(self::$shop_status[$data["shop_status"]]);
			$data["shop_all_class_cha"] = __(self::$shop_all_class[$data["shop_all_class"]]);

			if (!$data['shop_self_support'])
			{
				if ($data['shop_class_id'])
				{
					//获取分类名
					$shopClassModel     = new Shop_ClassModel();
					$shop_class_id      = $data['shop_class_id'];
					$data["shop_class"] = $shopClassModel->getClassName("$shop_class_id");
				}
			}

			if ($data['shop_grade_id'])
			{
				//获取等级名
				$shopGradeModel         = new Shop_GradeModel();
				$shop_grade_id          = $data['shop_grade_id'];
				$data["shop_grade"]     = $shopGradeModel->getGradeName("$shop_grade_id");
				$data["shop_grade_row"] = $shopGradeModel->getOne($shop_grade_id);
			}
			else
			{
				$data["shop_grade"] = __(self::$shop_grade_name);
			}

		}

		return $data;
	}


	//多条件获取主键
	public function getShopId($cond_row = array(), $order_row = array())
	{

		return $this->getKeyByMultiCond($cond_row, $order_row);

	}

	/**
	 * 读取添加成功的数据
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseWhere($cond_row = array(), $order_row = array())
	{

		$datas = $this->getByWhere($cond_row, $order_row);
		foreach ($datas as $key => $value)
		{

			$data["shop_status_cha"]    = __(self::$shop_status[$value["shop_status"]]);
			$data["shop_all_class_cha"] = __(self::$shop_all_class[$value["shop_all_class"]]);
			$data['shop_id']            = $value['shop_id'];
			$data['shop_name']          = $value['shop_name'];
		}
		return $data;

	}

	/**
	 * 获取店铺所有的开店信息
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */

	public function getbaseAllList($shop_id = null)
	{

		$shopCompanyModel   = new Shop_CompanyModel();
		$shopClassBindModel = new Shop_ClassBindModel();
		$shopClassModel     = new Shop_ClassModel();
		$User_InfoModel     = new User_InfoModel();
		$shopGradeModel     = new Shop_GradeModel();
		$company            = $shopCompanyModel->getCompanyrow($shop_id);

		$data['base'] = $this->getBase($shop_id);
		//把两个数组拼成一个数组
		foreach ($data['base'] as $key => $value)
		{
			if ($company)
			{
				$data['base'][$key] = array_merge($data['base'][$key], $company[$key]);

				$data['base'][$key]['legal_identity_type_content'] = __(Shop_CompanyModel::$legal_identity_type[$data['base'][$key]['legal_identity_type']]);

				if ($value['shop_class_id'])
				{
					//获取分类名
					$shop_class_id                    = $value['shop_class_id'];
					$data['base'][$key]["shop_class"] = $shopClassModel->getClass("$shop_class_id");
				}
				if ($value['shop_grade_id'])
				{
					//获取等级名
					$shop_grade_id                    = $value['shop_grade_id'];
					$data['base'][$key]["shop_grade"] = $shopGradeModel->getGrade("$shop_grade_id");
				}

				$data['base'][$key]['classbind'] = $shopClassBindModel->getClassBindlist(array("shop_id" => $shop_id));
			}
		}
		return $data;

	}


	/**
	 * 获取店铺所有的开店信息
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */

	public function getbaseCompanyList($shop_id = null)
	{

		$shopCompanyModel   = new Shop_CompanyModel();
		$shopClassBindModel = new Shop_ClassBindModel();
		$company            = $shopCompanyModel->getCompanyrow($shop_id);

		$shop_base = $this->getBase($shop_id);
		$data = array();
		//把两个数组拼成一个数组
		foreach ($shop_base as $key => $value)
		{
			if ($company)
			{
				$data = array_merge($shop_base[$key], $company[$key]);

				if ($value['shop_class_id'])
				{
					//获取分类名
					$shopClassModel     = new Shop_ClassModel();
					$shop_class_id      = $value['shop_class_id'];
					$data["shop_class"] = $shopClassModel->getClass("$shop_class_id");
					$data['shop_class_row'] = current($data["shop_class"]);
				}
				if ($value['shop_grade_id'])
				{
					//获取等级名
					$shopGradeModel     = new Shop_GradeModel();
					$shop_grade_id      = $value['shop_grade_id'];
					$data["shop_grade"] = $shopGradeModel->getGrade("$shop_grade_id");
					$data['shop_grade_row'] = current($data["shop_grade"]);
				}

				$data['classbind'] = $shopClassBindModel->getClassBindlist(array("shop_id" => $shop_id));
				$data['classbind_row'] = array();
				if($data['classbind']['items'])
				{
					foreach($data['classbind']['items']['product_parent_name'] as $key => $val)
					{
						$str = '';
						foreach($val as $k => $v)
						{
							$data['classbind_row'][$key] = $v;
							$str .= $v['cat_name'].'>';
						}
						$data['classbind_row'][$key]['cat_name_str'] = substr($str,0,strlen($str)-1);
					}
				}
			}
		}
		return $data;

	}

	/**
	 * 获取店铺经营类目
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCategorylist($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		//$data  = array();
		$cat_num            = array();
		$shopClassBindModel = new Shop_ClassBindModel();
		$data               = $shopClassBindModel->listClassBindWhere($cond_row, $order_row, $page, $rows);
		//循环查询出店铺名字，以及类目名
		foreach ($data['items'] as $key => $value)
		{
			$data['items'][$key]['shop_class_bind_enablecha'] = __(self::$shop_class_bind_enable[$value['shop_class_bind_enable']]);
			$data['items'][$key]['shop_name']                 = $this->getshopName($value['shop_id']);
			$data['items'][$key]['user_name']                 = $this->getuserName($value['shop_id']);
			$product_class_id                                 = $value['product_class_id'];

			$cat_num                            = $this->catNameNum($product_class_id);
			$data['items'][$key]['cat_namenum'] = implode(" --> ", $cat_num);

		}

		return $data;
	}

	/**
	 * 根据分类id 获取所有的经营类目名称
	 *
	 * @param  array $cond_row
	 * @return array $rows 信息
	 * @access public
	 */
	public function catNameNum($product_class_id = null, $level = 100)
	{
		$product_name = array();
		// $product_cat= array();
		$CatModel    = new Goods_CatModel();
		$product_cat = $CatModel->getOne($product_class_id);
		//循环父类经营类目把子类插进去
		if ($product_cat)
		{
			$product_name[$level] = $product_cat['cat_name'];
			$cat_id               = $product_cat['cat_parent_id'];
			if ($cat_id)
			{
				$level--;
				$rs           = call_user_func_array(array(
														 $this,
														 'catNameNum'
													 ), array(
														 $cat_id,
														 $level
													 ));
				$product_name = $product_name + $rs;
			}
		}
		//数组颠倒
		ksort($product_name);
		return $product_name;
	}


	/**
	 * 获取店铺结算周期
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */

	public function getSettlementCycle($shop_id = null)
	{
		$cond_row = array();
		if ($shop_id)
		{
			$cond_row['shop_id'] = $shop_id;
		}

		$cond_row['shop_status'] = Shop_BaseModel::SHOP_STATUS_OPEN;
		$shop_info = $this->getByWhere($cond_row);

		$data = array();

		foreach ($shop_info as $key => $val)
		{
			$data[$key]['shop_id']               = $val['shop_id'];
			$data[$key]['shop_name']             = $val['shop_name'];
			$data[$key]['shop_settlement_cycle'] = $val['shop_settlement_cycle'];
			$data[$key]['shop_settlement_last_time'] = $val['shop_settlement_last_time'];
			$data[$key]['shop_create_time']      = $val['shop_create_time'];
			$data[$key]['user_id']				   = $val['user_id'];
			$data[$key]['user_name']			   = $val['user_name'];
            $data[$key]['district_id']			   = $val['district_id'];
		}

		return $data;
	}

	/**
	 * 更改店铺免运费额度
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function editFreightAmount($shop_id)
	{
		$update_flag = $this->edit($shop_id, array('shop_free_shipping' => request_int('free_shipping')));
		return $update_flag;
	}

	/**
	 * 获取店铺免运费额度
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getShopBaseInfo($shop_id)
	{
		$data = $this->getOne($shop_id);
		return $data;
	}

	/*
	 * 发货单打印设置
	 */
	public function setPrint($shop_id, $field_row)
	{
		$update_flag = $this->edit($shop_id, $field_row);
		return $update_flag;
	}
	
	/*
	 * 查店铺详情
	 */
	public function getShopListByGoodId($shop_id)
	{
		if (is_array($shop_id))
		{
			$cond_row = array('shop_id:in' => $shop_id);

		}
		else
		{
			$cond_row = array('shop_id' => $shop_id);

		}

		return $this->getByWhere($cond_row);
	}
	
	
	//获取店铺的销量
	function getShopSales($shop_id,$date)
	{

		$orderModel = new Order_BaseModel();
		$cond_row['shop_id'] = $shop_id;
		$cond_row['order_create_time:>='] = $date;
		// $cond_row['order_status:>='] = Order_StateModel::ORDER_PAYED;
		$cond_row['order_status:IN'] = [Order_StateModel::ORDER_RECEIVED, Order_StateModel::ORDER_FINISH];
		$cond_row['order_is_virtual'] = 0;

		$orderModel->sql->noLimit = true;
		$orders = $orderModel->getByWhere($cond_row);
		$data = array();
		$data['sales_num'] = count($orders);
		$data['order_sales'] =  $orders?array_sum(array_column($orders,'order_payment_amount')):0;

		return $data;
	}
    
    
    
    /**
     * 取的附近的店铺  单位米
     *
     * @param  float $lat
     * @param  float $lng
     * @param  int $distance 小于范围
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getNearShop($lat, $lng, $distance=20000, $page = 1, $rows=10)
    {
        $offset = max(0, $rows * ($page-1));
    
        $shop_entity = TABEL_PREFIX . 'shop_entity';
        
        $sql = "
SELECT * FROM
	(SELECT  s.*, e.entity_xxaddr, (round(6378.138*2*asin(sqrt(pow(sin( (e.lat*pi()/180-$lat*pi()/180)/2),2)+cos(e.lat*pi()/180)*cos($lat*pi()/180)* pow(sin( (e.lng*pi()/180-$lng*pi()/180)/2),2)))*1000)) as distance FROM " . $this->_tableName . " s LEFT JOIN  " . $shop_entity . " e ON s.shop_id = e.shop_id WHERE 1 and s.shop_status=" . Shop_BaseModel::SHOP_STATUS_OPEN .  " ORDER BY distance ASC limit 200) as temp
WHERE
	distance < $distance
ORDER BY distance ASC
limit $offset, $rows
";
        
        $shop_rows = $this->sql->getAll($sql);
        $total = $this->getFoundRows();
    
        $data = array();
        $data['page'] = $page;
        $data['total'] = ceil_r($total / $rows);  //total page
        $data['totalsize'] = $total;
        $data['records'] = $total;
    
        $data['items'] = array_values($shop_rows);
    
    
        $shopClassModel = new Shop_ClassModel();
        $shopGradeModel = new Shop_GradeModel();
        $shopCompanyModel   = new Shop_CompanyModel();
        $User_FavoritesShopModel = new User_FavoritesShopModel();
    	//判断当前是否有用户登录,如果有就查询收藏店铺
    	if(Perm::$row['user_id'])
    	{
	    	//获取当前用户收藏的店铺
	    	$Favorites_row['user_id'] = Perm::$row['user_id'];
	    	$User_FavoritesShop = $User_FavoritesShopModel->getFavoritesShops($Favorites_row);
	    	$FavoritesShopIds = array_column($User_FavoritesShop['items'],'shop_id');
    	}
    	else
    	{
    		$FavoritesShopIds = array();
    	}
        //把数据库的状态以及分类id 和等级id全部变成中文。
        foreach ($data["items"] as $key => $value)
        {
        
            $data["items"][$key]["shop_status_cha"]    = __(self::$shop_status[$value["shop_status"]]);
            $data["items"][$key]["shop_all_class_cha"] = __(self::$shop_all_class[$value["shop_all_class"]]);
            $data["items"][$key]["shop_payment_cha"]   = __(self::$shop_payment[$value["shop_payment"]]);
            if ($value['shop_class_id'])
            {
                //获取分类名
                $shop_class_id                     = $value['shop_class_id'];
                $data["items"][$key]["shop_class"] = $shopClassModel->getClassName("$shop_class_id");
            }
            if ($value['shop_grade_id'])
            {
                //获取等级名
                $shop_grade_id                     = $value['shop_grade_id'];
                $data["items"][$key]["shop_grade"] = $shopGradeModel->getGradeName("$shop_grade_id");
            }
        
            $company            = $shopCompanyModel->getOne($value['shop_id']);
            $data['items'][$key] = array_merge($data['items'][$key], $company);
        	if(in_array($value['shop_id'],$FavoritesShopIds))
			{
				$data["items"][$key]['is_collect'] = 1;
			}
			else
			{
				$data["items"][$key]['is_collect'] = 0;
			}
        }
    
        //$data['items'] = array_values($shop_rows);
        
        return $data;
    }

	public function getSubQuantity($cond_row)
	{
		return $this->getNum($cond_row);
	}

	/**
	 * 获取店铺绑定类目信息
	 *
	 * @param $shop_id
	 * @param $shop_self_support boolean 是否自营
	 * @param $shop_all_class int 绑定所有经营类目
	 * @return array
	 */

	public function getBindCategoryByShopId ( $shop_id, $shop_self_support = 'false', $shop_all_class = 0)
	{
		if ($shop_self_support != 'false' || intval($shop_all_class)) { //店铺绑定所有类目
			$goods_cat_rows = $this->getLowestGoodsCategories();
		} else {
			$shopClassBindModel = new Shop_ClassBindModel;
			$shop_class_bind_rows = $shopClassBindModel->getByWhere(['shop_id'=> $shop_id, 'shop_class_bind_enable'=> 2]);

			if (empty($shop_class_bind_rows)) {
				$goods_cat_rows = [];
			} else {
				$lowest_cat_ids = array_column($shop_class_bind_rows, 'product_class_id', 'product_class_id');
				$goods_cat_rows = $this->getLowestGoodsCatByCatIds($lowest_cat_ids);
			}
		}
		return $goods_cat_rows;
	}

	/**
	 * 查找所有最底层分类
	 *
	 * @return array
	 */
	public function getLowestGoodsCategories()
	{
		$goodsCatModel = new Goods_CatModel;

		$goods_cat_rows = $goodsCatModel->getByWhere();

		$lowest_goods_cat_rows = [];

		foreach ($goods_cat_rows as $goods_cat_data) {

			$cat_id = $goods_cat_data['cat_id'];
			$cat_parent_id = $goods_cat_data['cat_parent_id'];

			if ( isset($lowest_goods_cat_rows[$cat_parent_id]) ) {
				unset($lowest_goods_cat_rows[$cat_parent_id]);
			}
			$lowest_goods_cat_rows[$cat_id] = $goods_cat_data;
		}

		return $lowest_goods_cat_rows;
	}

	/**
	 * 查找分类下的最底层分类
	 *
	 * @param $lowest_cat_ids array [8=> '8']
	 * @return array
	 */
	public function getLowestGoodsCatByCatIds ( $lowest_cat_ids )
	{
		$goodsCatModel = new Goods_CatModel;
		$children_cat_rows = $goodsCatModel->getByWhere(['cat_parent_id:IN'=> $lowest_cat_ids]);

		if (empty($children_cat_rows)) {
			$lowest_cat_rows = $goodsCatModel->get($lowest_cat_ids);
			return $lowest_cat_rows;
		} else {

			$cat_ids = [];
			$cat_parent_ids = [];
			foreach ($children_cat_rows as $children_cat_data) {

				$cat_id = $children_cat_data['cat_id'];
				$cat_parent_id = $children_cat_data['cat_parent_id'];
				if (isset($lowest_cat_ids[$cat_parent_id])) {
					unset($lowest_cat_ids[$cat_parent_id]);
				}
				$lowest_cat_ids[$cat_id] = $cat_id;
			}
			return $this->getLowestGoodsCatByCatIds($lowest_cat_ids);
		}
	}

	/**
	 * 获取店铺下的商品
	 * @param $shop_id
	 * @return boolean
	 */
	public function getShopGoods ($shop_id)
	{
		$goodsCommonModel = new Goods_CommonModel;
		$goods_common_rows = $goodsCommonModel->getByWhere(['shop_id'=> $shop_id]);
		return $goods_common_rows;
	}
    
    
    /**
     *  获取自营店铺im
     * @param type $user_name
     * @param type $shop_self_support
     * @return type
     */
    public function getSelfShopIm($user_name,$shop_self_support){
        $self_shop_im = $shop_self_support == 'true' && Web_ConfigModel::value('self_shop_im') ? Web_ConfigModel::value('self_shop_im') : $user_name;
        return $self_shop_im;
    }

    /**
     * 编辑店铺信息
     * @param mix $config_key 主键
     * @param array $field_row key=>value数组
     * @return bool $update_flag 是否成功
     * @access public
     */
    public function editShopInfo($config_key = null, $field_row){
        $update_flag = $this->editBase($config_key, $field_row);
        return $update_flag;
    }
}

?>
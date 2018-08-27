<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Seller_Promotion_VoucherCtl extends Seller_Controller
{
	public $voucherTempModel  = null;
	public $voucherPriceModel = null;
	public $voucherQuotaModel = null;
	public $shopBaseModel     = null;
	public $shopCostModel     = null;

	public $combo_flag        = false; //套餐是否可用
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

		if (!Web_ConfigModel::value('voucher_allow')) //团购功能设置，关闭，跳转到卖家首页
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
				$data->setError(__('代金券功能已关闭'), 30);
				$d = $data->getDataRows();

				$protocol_data = Yf_Data::encodeProtocolData($d);
				echo $protocol_data;
				exit();
			}
		}

		$this->voucherTempModel  = new Voucher_TempModel();
		$this->voucherPriceModel = new Voucher_PriceModel();
		$this->voucherQuotaModel = new Voucher_quotaModel();
		$this->shopBaseModel     = new Shop_BaseModel();
		$this->shopCostModel     = new Shop_CostModel();

		$this->shop_info         = $this->shopBaseModel->getOne(Perm::$shopId);//店铺信息
		$this->self_support_flag = ($this->shop_info['shop_self_support'] == "true" || Web_ConfigModel::value('promotion_voucher_price') == 0) ? true : false;  //是否为自营店铺标志

		if ($this->self_support_flag) //平台店铺，没有套餐限制
		{
			$this->combo_flag = true;
		}
		else
		{
			$this->combo_flag = $this->voucherQuotaModel->checkQuotaStateByShopId(Perm::$shopId);
		}
	}

	/**
	 * 首页
	 * @access public
	 * 卖家发布的代金券列表
	 */
	public function index()
	{
		$cond_row['shop_id'] = Perm::$shopId;

		if (request_string('op') == 'detail')
		{
			$cond_row['voucher_t_id'] = request_int('id');
			$this->view->setMet('detail');
			$data = $this->voucherTempModel->getVoucherTempInfoByWhere($cond_row);
			if ($data['shop_class_id'])
			{
				$Shop_ClassModel         = new Shop_ClassModel();
				$shop_class_row          = $Shop_ClassModel->getOne($data['shop_class_id']);
				$data['shop_class_name'] = $shop_class_row['shop_class_name'];
			}
			else
			{
				$data['shop_class_name'] = '';
			}

			if ($data['voucher_t_user_grade_limit'])
			{
				$User_GradeModel                          = new User_GradeModel();
				$grade_row                                = $User_GradeModel->getOne($data['voucher_t_user_grade_limit']);
				$data['voucher_t_user_grade_limit_label'] = $grade_row['user_grade_name'];
			}
		}
		else
		{
			$Yf_Page           = new Yf_Page();
			$Yf_Page->listRows = request_int('listRows')?request_int('listRows'):10;
			$rows              = $Yf_Page->listRows;
			$offset            = request_int('firstRow', 0);
			$page              = ceil_r($offset / $rows);

			if (request_string('start_date'))
			{
				$cond_row['voucher_t_start_date:>'] = request_string('start_date');
			}
			if (request_string('end_date'))
			{
				$cond_row['voucher_t_end_date:<'] = request_string('end_date');
			}
			if (request_int('state'))
			{
				$cond_row['voucher_t_state'] = request_int('state');
			}
			if (request_int('method'))
			{
				$cond_row['voucher_t_access_method'] = request_int('method');
			}
			if (request_string('keyword'))
			{
				$cond_row['voucher_t_title:LIKE'] = "%".request_string('keyword') . "%";
			}

			$data               = $this->voucherTempModel->getVoucherTempList($cond_row, array('voucher_t_id' => 'DESC'), $page, $rows);
			$Yf_Page->totalRows = $data['totalsize'];
			$page_nav           = $Yf_Page->prompt();

			$shop_type = $this->self_support_flag;
			if (!$this->self_support_flag)  //普通店铺
			{
				$com_flag = $this->combo_flag;
				if ($this->combo_flag)//套餐可用
				{
					$combo = $this->voucherQuotaModel->getVoucherQuotaByShopID(Perm::$shopId);
				}
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

	/**
	 *添加代金券模板
	 */
	public function add()
	{
		$date = array();

		$Shop_ClassModel = new Shop_ClassModel();
		if ($this->shop_info['shop_self_support'] == 'true')
		{
			$data['shop_class'] = $Shop_ClassModel->getClassWhere();//店铺分类
		}
		elseif ($this->shop_info['shop_self_support'] == 'false')
		{
			$data['shop_class'] = $Shop_ClassModel->getClassWhere(array('shop_class_id' => $this->shop_info['shop_class_id'])); //店铺分类
		}

		$shop_type           = $this->self_support_flag;
		$cond_row['shop_id'] = Perm::$shopId;
		if ($this->self_support_flag) //平台店铺，包含所有店铺分类
		{
			$combo['combo_end_time'] = date("Y-m-d H:i:s", strtotime("11 june 2030"));
		}
		else
		{

			if (!$this->combo_flag)
			{
				location_to('index.php?ctl=Seller_Promotion_Voucher&met=combo&typ=e');
			}
			else
			{
				$combo = $this->voucherQuotaModel->getVoucherQuotaItemByWhere($cond_row);
			}
		}

		if (request_string('op') == 'edit' && request_int('id'))
		{
			$cond_row_v_t['voucher_t_id'] = request_int('id');
			$cond_row_v_t['shop_id']      = Perm::$shopId;
			$row                          = $this->voucherTempModel->getVoucherTempByWhere($cond_row_v_t);

			if ($row)
			{
				$data = array_merge($data, $row);
				$this->view->setMet('edit');
			}
		}

		//会员等级
		$User_GradeModel       = new User_GradeModel();
		$data['user_grade']    = $User_GradeModel->getGradeList();
		$data['access_method'] = Voucher_TempModel::$voucher_access_method_map;     //代金券的领取方式
		$data['denomination']  = $this->voucherPriceModel->getVoucherDenomination();   //面额种类

		if('json' == $this->typ)
		{
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}

	}

	public function addVoucherTemp()
    {
        $data_re = array();
        $check_post_data_flag = true;

        $combo_row = $this->voucherQuotaModel->getVoucherQuotaByShopID(Perm::$shopId);
        $voucher_price_range_id = $this->voucherPriceModel->getKeyByWhere(array());

        $voucher_t_price_id = request_int('voucher_t_price');
        //代金券面额是否合法
        if (in_array($voucher_t_price_id, $voucher_price_range_id))
        {
            $voucher_price = $this->voucherPriceModel->getVoucherPriceByID($voucher_t_price_id);
            $field_row['voucher_t_price'] = $voucher_price['voucher_price'];
        }
        else
        {
            $voucher_price = array();
            $check_post_data_flag = false;
            $msg_label = __('代金券面额有误！');
        }

        $field_row['voucher_t_access_method'] = request_int('voucher_t_access_method');
        //代金券领取方式是否合法
        if(in_array($field_row['voucher_t_access_method'], array_keys(Voucher_TempModel::$voucher_access_method_map)))
        {
            //领取方式为积分兑换，根据面额查询需积分数
            if ($field_row['voucher_t_access_method'] == Voucher_TempModel::GETBYPOINTS && $voucher_price)
            {
                $field_row['voucher_t_points'] = $voucher_price['voucher_defaultpoints'];
            }
            else
            {
                $field_row['voucher_t_points'] = 0;
            }
        }
        else
        {
            $check_post_data_flag = false;
            $msg_label = __('代金券领取方式有误！');
        }

        $field_row['voucher_t_creator_id']      = Perm::$userId;                               //发布代金券用户ID
		$field_row['shop_id']                     = Perm::$shopId;                                 //店铺ID
		$field_row['shop_name']                   = $this->shop_info['shop_name'];              //店铺名称
		$field_row['shop_class_id']               = request_int('shop_class');                  //代金券分类，同店铺分类
		$field_row['voucher_t_title']            = request_string('voucher_t_title');          //代金券模板名称
        //名称不能为空
        if(!$field_row['voucher_t_title'])
        {
            $check_post_data_flag = false;
            $msg_label = __('代金券模板名称不能为空！');
        }
		$field_row['voucher_t_add_date']         = get_date_time();                                 //创建时间
		$field_row['voucher_t_start_date']       = get_date_time();                                 //開始時間
		$field_row['voucher_t_end_date']         = request_string('voucher_t_end_date');         //有效期
        if(!$field_row['voucher_t_end_date'] || (!$this->combo_flag && strtotime($field_row['voucher_t_end_date']) > strtotime($combo_row['combo_end_time'])))
        {
            $check_post_data_flag = false;
            $msg_label = __('代金券有效期不正确！');
        }
		$field_row['voucher_t_update_date']      = get_date_time();                                 //最后更新时间
		$field_row['voucher_t_state']            = Voucher_TempModel::VALID;                      //代金券模板狀態
		$field_row['voucher_t_total']            = request_int('voucher_t_total');               //可发放代金券总数
        if($field_row['voucher_t_total'] <=0)
        {
            $check_post_data_flag = false;
            $msg_label = __('代金券可发放总数不正确！');
        }
		$field_row['voucher_t_eachlimit']        = request_int('voucher_t_eachlimit');          //限领数量
        if($field_row['voucher_t_eachlimit'] < 0)
        {
            $check_post_data_flag = false;
            $msg_label = __('代金券限领数量不正确！');
        }
		$field_row['voucher_t_limit']            = request_int('voucher_t_limit');              //消费金额限制
//		 || $field_row['voucher_t_limit'] < $field_row['voucher_t_price']
        if($field_row['voucher_t_limit'] <0)
        {
            $check_post_data_flag = false;
            $msg_label = __('消费金额限制不能小于0！');
        }
		$field_row['voucher_t_user_grade_limit'] = request_int('voucher_t_user_grade_limit');   //领取用户等级限制
        $User_GradeModel       = new User_GradeModel();
        $user_grade_key_row    = $User_GradeModel->getKeyByWhere(array());
        if(!in_array($field_row['voucher_t_user_grade_limit'], $user_grade_key_row))
        {
            $check_post_data_flag = false;
            $msg_label = __('用户等级限制不正确！');
        }
		$field_row['voucher_t_desc']             = request_string('voucher_t_desc');             //代金券描述
        if(empty($field_row['voucher_t_desc']))
        {
            $check_post_data_flag = false;
            $msg_label = __('代金券描述不能为空！');
        }
		$field_row['voucher_t_customimg']        = request_string('voucher_t_customimg');       //代金券模板图片
        if(empty($field_row['voucher_t_customimg']))
        {
            $check_post_data_flag = false;
            $msg_label = __('请上传代金券图片！');
        }
		$field_row['voucher_t_recommend']        = Voucher_TempModel::UNRECOMMEND;                //代金券模板是否推荐，0-不推荐,默认
        $field_row['voucher_t_end_date'] = $field_row['voucher_t_end_date']." 23:59:59";

		$cond_row['shop_id'] = Perm::$shopId;
		$BeginDate = date('Y-m-01 00:00:00');
		$cond_row['voucher_t_add_date:>='] = $BeginDate;
		$cond_row['voucher_t_add_date:<='] = date('Y-m-d H:i:s', (strtotime("$BeginDate +1 month")-1));
		$created_voucher_num_this_month = $this->voucherTempModel->getVoucherTempNumByWhere($cond_row);

		if($created_voucher_num_this_month >= Web_ConfigModel::value('promotion_voucher_storetimes_limit'))
		{
			$check_post_data_flag = false;
			$msg_label = __('您已达到每月发布代金券数量限制!');
		}
		
        if($check_post_data_flag)
        {
            $flag = $voucher_t_id = $this->voucherTempModel->addVoucherTemp($field_row, true);
        }
		else
        {
            $flag = false;
        }
		
		if ($flag)
		{
			$msg    = __('添加成功！');
			$status = 200;
		}
		else
		{
			$msg    = $msg_label?$msg_label:__('添加失败！');
			$status = 250;
		}

		$data_re['voucher_t_id'] = $voucher_t_id;

		$this->data->addBody(-140, $data_re, $msg, $status);

	}

	/*编辑修改代金券模板*/
	public function editVoucherTemp()
	{
		$voucher_t_id = request_int('voucher_t_id');
		$check_v_temp = $this->voucherTempModel->getVoucherTempById($voucher_t_id);

        if ($check_v_temp['shop_id'] == Perm::$shopId)
		{
            $check_post_data_flag = true;

            $combo_row = $this->voucherQuotaModel->getVoucherQuotaByShopID(Perm::$shopId);

            $voucher_price_range_id = array_keys($this->voucherPriceModel->getVoucherDenomination());
			$voucher_t_price_id     = request_int('voucher_t_price');
            //代金券面额是否合法
            if (in_array($voucher_t_price_id, $voucher_price_range_id))
            {
                $voucher_price = $this->voucherPriceModel->getVoucherPriceByID($voucher_t_price_id);
                $field_row['voucher_t_price'] = $voucher_price['voucher_price'];
            }
            else
            {
                $voucher_price = array();
                $check_post_data_flag = false;
                $msg_label = __('代金券面额有误！');
            }

            $field_row['voucher_t_access_method'] = request_int('voucher_t_access_method');
            //代金券领取方式是否合法
            if(in_array($field_row['voucher_t_access_method'], array_keys(Voucher_TempModel::$voucher_access_method_map)))
            {
                //领取方式为积分兑换，根据面额查询需积分数
                if ($field_row['voucher_t_access_method'] == Voucher_TempModel::GETBYPOINTS && $voucher_price)
                {
                    $field_row['voucher_t_points'] = $voucher_price['voucher_defaultpoints'];
                }
                else
                {
                    $field_row['voucher_t_points'] = 0;
                }
            }
            else
            {
                $check_post_data_flag = false;
                $msg_label = __('代金券领取方式有误！');
            }

			$field_row['shop_class_id']              = request_int('shop_class');
			$field_row['voucher_t_title']            = request_string('voucher_t_title');          //代金券模板名称
            //名称不能为空
            if(!$field_row['voucher_t_title'])
            {
                $check_post_data_flag = false;
                $msg_label = __('代金券模板名称不能为空！');
            }
			$field_row['voucher_t_update_date']      = get_date_time();                         //最后更新时间
			$field_row['voucher_t_end_date']         = request_string('voucher_t_end_date');        //有效期
            if(!$field_row['voucher_t_end_date'] ||  (!$this->combo_flag && strtotime($field_row['voucher_t_end_date']) > strtotime($combo_row['combo_end_time'])))
            {
                $check_post_data_flag = false;
                $msg_label = __('代金券有效期不正确！');
            }
            $field_row['voucher_t_end_date']         = $field_row['voucher_t_end_date']." 23:59:59";
			$field_row['voucher_t_total']            = request_int('voucher_t_total');               //可发放代金券总数
            if($field_row['voucher_t_total'] <=0)
            {
                $check_post_data_flag = false;
                $msg_label = __('代金券可发放总数不正确！');
            }
			$field_row['voucher_t_eachlimit']        = request_int('voucher_t_eachlimit');          //限领数量
            if($field_row['voucher_t_eachlimit'] < 0)
            {
                $check_post_data_flag = false;
                $msg_label = __('代金券限领数量不正确！');
            }
			$field_row['voucher_t_limit']            = request_int('voucher_t_limit');              //消费金额限制
            if($field_row['voucher_t_limit'] <0)
            {
                $check_post_data_flag = false;
                $msg_label = __('消费金额限制不正确！');
            }
			$field_row['voucher_t_user_grade_limit'] = request_int('voucher_t_user_grade_limit');   //领取用户等级限制
            $User_GradeModel       = new User_GradeModel();
            $user_grade_key_row    = $User_GradeModel->getKeyByWhere(array());
            if(!in_array($field_row['voucher_t_user_grade_limit'], $user_grade_key_row))
            {
                $check_post_data_flag = false;
                $msg_label = __('用户等级限制不正确！');
            }
			$field_row['voucher_t_desc']             = request_string('voucher_t_desc');             //代金券描述
            if(empty($field_row['voucher_t_desc']))
            {
                $check_post_data_flag = false;
                $msg_label = __('代金券描述不能为空！');
            }
			$field_row['voucher_t_customimg']        = request_string('voucher_t_customimg');       //代金券模板图片
            if(empty($field_row['voucher_t_customimg']))
            {
                $check_post_data_flag = false;
                $msg_label = __('请上传代金券图片！');
            }

            if($check_post_data_flag)
            {
                $this->voucherTempModel->editVoucherTemp($voucher_t_id, $field_row);
            }
		}
		
		$data_re = array();
		$data_re['voucher_t_id'] = $voucher_t_id;

		$this->data->addBody(-140, $data_re);
	}

	//删除代金券模板
	public function removeVoucherTemp()
	{
		$voucher_t_ids = request_row('id');
        if (is_array($voucher_t_ids)) {
            foreach ($voucher_t_ids as $voucher_t_id){
                $check_right = $this->voucherTempModel->getOne($voucher_t_id);
            }
        }

        if ($check_right['shop_id'] == Perm::$shopId)
        {
            $flag = $this->voucherTempModel->removeVoucherTemp($voucher_t_id);

            if ($flag)
            {
                $msg = __('删除成功！');
                $status = 200;
            }
            else
            {
                $msg = __('删除失败！');
                $status = 250;
            }
        }
        else
        {
            $msg    = __('删除失败');
            $status = 250;
        }

		$data['voucher_t_id'] = $voucher_t_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function combo()
	{
		if ($this->self_support_flag)  //免费发布活动
		{
            location_go_back(__('自营店铺或者套餐续费， 不需要设置。'));
			//location_to('index.php?ctl=Seller_Promotion_Voucher&met=add&typ=e');
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
		$cond_row['activity_type'] = Shop_CostModel::VOUCHER;
		$data = $this->shopCostModel->listByWhere($cond_row,$order_row,$page, $rows);

		$Yf_Page->totalRows = $data['totalsize'];
		$page_nav           = $Yf_Page->prompt();

		if('json' == $this->typ)
		{
			$data['promotion_voucher_price'] = Web_ConfigModel::value('promotion_voucher_price');
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
		$month_price = Web_ConfigModel::value('promotion_voucher_price');
		$month       = request_int('month');
		$days        = 30 * $month;

		if($month > 0)
		{
			$this->voucherQuotaModel->sql->startTransactionDb();

			$field_row['user_id']     = Perm::$row['user_id'];
			$field_row['shop_id']     = Perm::$shopId;
			$field_row['cost_price']  = $month_price * $month;
			$field_row['cost_desc']   = __('店铺购买代金券活动消费');
			$field_row['cost_status'] = 0;
			$field_row['cost_time']   = date('Y-m-d H:i:s');
			$field_row['activity_type']   = Shop_CostModel::VOUCHER;
			$field_row['activity_price']   = $month_price;
			$field_row['activity_month']   = $month;
			$flag                     = $this->shopCostModel->addCost($field_row, true);
			check_rs($flag, $rs_row);

			if ($flag)
			{
				$combo_row = $this->voucherQuotaModel->getVoucherQuotaByShopID(Perm::$shopId);
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
					$op_flag = $this->voucherQuotaModel->renewVoucherCombo($combo_row['combo_id'], $field);
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
					$op_flag                   = $this->voucherQuotaModel->addVoucherCombo($field, true);
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

			if (is_ok($rs_row) && isset($rs) && $rs['status'] && $this->voucherQuotaModel->sql->commitDb())
			{
				$msg    = __('操作成功！');
				$status = 200;
			}
			else
			{
				$this->voucherQuotaModel->sql->rollBackDb();
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
}

?>
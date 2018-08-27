<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Banchangle
 */
class Seller_Shop_InfoCtl extends Seller_Controller
{

	public $shopBaseModel      = null;
	public $shopClassModel     = null;
	public $shopGradeModel     = null;
	public $shopClassBindModel = null;
	public $shopRenewalModel   = null;
	public $goodsCatModel      = null;

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
		$this->shopBaseModel      = new Shop_BaseModel();
		$this->shopClassModel     = new Shop_ClassModel();
		$this->shopGradeModel     = new Shop_GradeModel();
		$this->shopClassBindModel = new Shop_ClassBindModel();
		$this->shopRenewalModel   = new Shop_RenewalModel();
		$this->goodsCatModel      = new Goods_CatModel();

	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function info()
	{
		$act = request_string('act');
		//首先判断一下是不是自营店铺如果是自营店铺就没有店铺公司信息以及续费申请
		$shop_id = Perm::$shopId;
		$shop    = $this->shopBaseModel->getOne($shop_id);

		if ($act == 'renew')
		{
			//推算出他的续签时间（前一个月即可申请）
			$frontmonth = date("Y-m-d H:i:s", strtotime("$shop[shop_end_time] - 1 month"));
			$date       = date("Y-m-d h:i:s", time());
			$data       = $this->shopRenewalModel->getRenewalList(array("shop_id" => $shop_id));
			$grade      = $this->shopGradeModel->getGradeWhere();
			// var_dump($grade);
			$this->view->setMet('renew');

		}
		elseif ($act == 'info')
		{
			//店铺信息
			$shopCompanyModel = new Shop_CompanyModel();
			$company          = $shopCompanyModel->getCompanyrow($shop_id);
			if ($company)
			{
				$data = $this->shopBaseModel->getbaseAllList($shop_id);
			}
			else
			{
				$data = array();
			};
		}
		elseif ($act == 'createQrCode')
		{
			$Shop_BaseModel = new Shop_BaseModel();
			$shop_id = Perm::$shopId;
			$shop_base = $Shop_BaseModel->getOne($shop_id); 
			$this->view->setMet('Qrcode');
		}
		else
		{
			//判断是否绑定所有类目
			if ($shop['shop_all_class'])
			{
				$data = array();
			}
			else
			{
				$Yf_Page            = new Yf_Page();
				$Yf_Page->listRows  = 10;
				$rows               = $Yf_Page->listRows;
				$offset             = request_int('firstRow', 0);
				$page               = ceil_r($offset / $rows);
				$data               = $this->shopClassBindModel->getClassBindlist(array("shop_id" => $shop_id), array(), $page, $rows);
				$Yf_Page->totalRows = $data['totalsize'];
				$page_nav           = $Yf_Page->prompt();
			}
			$this->view->setMet('category');
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


	public function delInfo()
	{

		$shop_class_bind_id = request_string('id');
		$shop_id            = Perm::$shopId;
		if ($shop_class_bind_id)
		{
			//判断是不是当前用户操作的
			$class_Bind_info = $this->shopClassBindModel->getOne($shop_class_bind_id);
			if ($shop_id == $class_Bind_info['shop_id'])
			{
				$flag = $this->shopClassBindModel->removeClassBind($shop_class_bind_id);
				if ($flag)
				{
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

		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function addRenew()
	{
		//接收数据
		$shop_grade_id           = request_int('shop_grade');
		$renew_row['renew_time'] = request_int('renew_time');
		//根据等级id获取等级的名称以及单价
		$renew                        = $this->shopGradeModel->getOne($shop_grade_id);
		$renew_row['shop_grade_id']   = $renew['shop_grade_id'];
		$renew_row['shop_grade_name'] = $renew['shop_grade_name'];
		$renew_row['shop_grade_fee']  = $renew['shop_grade_fee'];
		$renew_row['renew_cost']      = $renew_row['renew_time'] * $renew['shop_grade_fee'];
		$renew_row['create_time']     = date("Y-m-d H:i:s", time());

		$shop_id = Perm::$shopId;
		//根据店铺id查询出店铺信息
		$shop_row = $this->shopBaseModel->getOne($shop_id);

		$renew_row['start_time'] = $shop_row['shop_end_time'];
		$renew_row['shop_name']  = $shop_row['shop_name'];
		$renew_row['shop_id']    = $shop_row['shop_id'];
		//续费结束时间等于店铺结束时间 + 续费的年数
		$renew_row['end_time'] = date("Y-m-d H:i:s", strtotime("$shop_row[shop_end_time] + $renew_row[renew_time] year"));
		$renew_row['status']   = 0;
        //获取店铺位置
        $renew_row['district_id']  = $shop_row['district_id'];
		$flag                  = $this->shopRenewalModel->addRenewal($renew_row);
		if ($flag)
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

	public function delRenew()
	{
		$renew_id = request_string('id');
		$shop_id  = Perm::$shopId;
		if ($renew_id)
		{
			//判断是不是当前用户操作的
			$renew_info = $this->shopRenewalModel->getOne($renew_id);
			if ($shop_id == $renew_info['shop_id'])
			{
				$flag = $this->shopRenewalModel->removeRenewal($renew_id);
				if ($flag)
				{
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

		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	//加载添加类目页面
	public function addcategoryInfo()
	{
		include $this->view->getView();
	}

	public function addcategoryrow()
	{
		$data['product_class_id'] = request_string('cat_id');
		if ($data['product_class_id'] != "")
		{
            //检查是否添加过此分类
            $check_category = $this->shopClassBindModel->getByWhere(array('product_class_id'=>$data['product_class_id'],'shop_id'=>Perm::$shopId));

            if(is_array($check_category) && $check_category){
                $status = 250;
				$msg    = __('该类目已存在');
            }else{
                $good_cat                       = $this->goodsCatModel->getOne($data['product_class_id']);
                $data['commission_rate']        = $good_cat['cat_commission'];
                $data['shop_class_bind_enable'] = "1";
                $data['shop_id']                = Perm::$shopId;
                $flag                           = $this->shopClassBindModel->addClassBind($data);
                if ($flag)
                {
                    $status = 200;
                    $msg    = __('success');
                }
                else
                {
                    $status = 250;
                    $msg    = __('failure');
                }
            }
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}

		$date = array();
		$this->data->addBody(-140, $date, $msg, $status);

	}
}

?>
<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Buyer_Service_ReportCtl extends Buyer_Controller
{
	public $reportBaseModel    = null;
	public $reportSubjectModel = null;
	public $reportTypeModel    = null;
	public $goodsBaseModel     = null;
	public $goodsCommonModel     = null;

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
		$this->reportBaseModel    = new Report_BaseModel();
		$this->reportSubjectModel = new Report_SubjectModel();
		$this->reportTypeModel    = new Report_TypeModel();
		$this->goodsBaseModel     = new Goods_BaseModel();
		$this->goodsCommonModel     = new Goods_CommonModel();
	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function index()
	{
		$act = request_string('act');
		if ($act == "detail")
		{
			$data = $this->detail();
			$this->view->setMet('detail');
			$d = $data;
		}
		elseif ($act == "add")
		{
			$data = $this->add();
			if ($data)
			{
				$this->view->setMet('add');
			}
			else
			{
				$this->view->setMet('error');
			}
			$d = $data;
		}
		else
		{
			$Yf_Page             = new Yf_Page();
			$Yf_Page->listRows   = 10;
			$rows                = $Yf_Page->listRows;
			$offset              = request_int('firstRow', 0);
			$page                = ceil_r($offset / $rows);
			$state               = request_int("report_state");
			$cond_row['user_id'] = Perm::$userId;         //店铺ID
			if ($state)
			{
				$cond_row['report_state'] = $state;
			}
			$data               = $this->reportBaseModel->getCatList($cond_row, array("report_date" => "DESC"), $page, $rows);
			$Yf_Page->totalRows = $data['totalsize'];
			$page_nav           = $Yf_Page->prompt();
			$data['state']      = $state;
			$d                  = $data;
		}
		if ($this->typ == "json")
		{
			$this->data->addBody(-140, $d);
		}
		else
		{
			include $this->view->getView();
		}
	}


	public function delReport()
	{
		$service_id = request_int('id');
		$service    = $this->reportBaseModel->getOne($service_id);
		if ($service['user_id'] == Perm::$userId && $service['report_state'] == Report_BaseModel::REPORT_DO)
		{
			$flag = $this->reportBaseModel->removeCat($service_id);
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
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function add()
	{
		$goods_id        = request_int("gid");
		$data['goods']   = $this->goodsBaseModel->getOne($goods_id);

		$goods = $this->goodsBaseModel->getByWhere(array("common_id"=>$data['goods']['common_id']));
		$goods_ids = array_column($goods,"goods_id");
		$report = $this->reportBaseModel->getByWhere(array("user_id"=>Perm::$userId,"goods_id:IN"=>$goods_ids,"report_state"=>Report_BaseModel::REPORT_DO));
		if(!empty($report)){
			return 0;
		}
		$data['type']    = $this->reportTypeModel->getByWhere();
		$data['type']    = array_values($data['type']);
		$data['subject'] = $this->reportSubjectModel->getByWhere(array("report_type_id" => $data['type'][0]['report_type_id']));
		return $data;
	}

	public function addReport()
	{
		$data['report_type_id']      = request_int("report_type_id");
		$type                        = $this->reportTypeModel->getOne($data['report_type_id']);
		$data['report_type_name']    = $type['report_type_name'];
		$data['report_subject_id']   = request_int("report_subject_id");
		$subject                     = $this->reportSubjectModel->getOne($data['report_subject_id']);
		$data['report_subject_name'] = $subject['report_subject_name'];
		$data['report_message']      = request_string("report_message");
		$pic                         = request_row("report_pic");
		$data['report_pic']          = implode(",", $pic);
		$data['goods_id']            = request_int("goods_id");
		$goods                       = $this->goodsBaseModel->getOne($data['goods_id']);
		$data['goods_name']          = $goods['goods_name'];
		$data['shop_id']             = $goods['shop_id'];
		$data['shop_name']           = $goods['shop_name'];
		$data['goods_pic']           = $goods['goods_image'];
		$data['user_id']             = Perm::$userId;
		$data['user_account']        = Perm::$row['user_account'];
		$data['report_date']         = get_date_time();

		$goods_l = $this->goodsBaseModel->getByWhere(array("common_id"=>$goods ['common_id']));
		$goods_ids = array_column($goods_l,"goods_id");
		$report = $this->reportBaseModel->getByWhere(array("user_id"=>Perm::$userId,"goods_id:IN"=>$goods_ids,"report_state"=>Report_BaseModel::REPORT_DO));
		if(!empty($report)){
			$data   = array();
			$msg    = __('failure');
			$status = 250;
			$this->data->addBody(-140, array(), $msg, $status);
			return false;
		}

		$matche_row = array();
		//有违禁词
		if (Text_Filter::checkBanned($data['report_message'], $matche_row))
		{
			$data   = array();
			$msg    = __('failure');
			$status = 250;
			$this->data->addBody(-140, array(), $msg, $status);
			return false;
		}

		$flag = $this->reportBaseModel->addCat($data, true);
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
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function detail()
	{
		$id                    = request_int("id");
		$cond_row['report_id'] = $id;
		$cond_row['user_id']   = Perm::$userId;

		$data = $this->reportBaseModel->getReportBase($cond_row);

		return $data;
	}

	public function getSubject()
	{
		$type_id = request_int("type_id");
		$data    = $this->reportSubjectModel->getByWhere(array("report_type_id" => $type_id));
		$this->data->addBody(-140, $data);
	}
}

?>
<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Buyer_Service_CustomCtl extends Buyer_Controller
{
	public $customtype    = null;
	public $customService = null;

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
		$this->customtype    = new Platform_CustomServiceTypeModel();
		$this->customService = new Platform_CustomServiceModel();

	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function index()
	{
		$act = request_string('act');
		if ($act == "add")
		{
			$data = $this->add();
			$this->view->setMet('add');
			$d = $data;

		}
		elseif ($act == "detail")
		{
			$data = $this->detail();
			$this->view->setMet('detail');
			$d = $data;
		}
		else
		{
			$Yf_Page             = new Yf_Page();
			$Yf_Page->listRows   = 10;
			$rows                = $Yf_Page->listRows;
			$offset              = request_int('firstRow', 0);
			$page                = ceil_r($offset / $rows);
			$custom_state        = request_int("state");
			$cond_row['user_id'] = Perm::$userId;         //店铺ID
			$cond_row['status'] = 0;        


			if ($custom_state)
			{
				$cond_row['custom_service_status'] = $custom_state;
			}

			$data               = $this->customService->getCustomServiceList($cond_row, array(
				"custom_service_answer_time" => "DESC",
				"custom_service_question_time" => "DESC"
			), $page, $rows);
			$data['state']      = $custom_state;
			$Yf_Page->totalRows = $data['totalsize'];
			$page_nav           = $Yf_Page->prompt();
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


	public function delService()
	{
		$service_id = request_int('id');
		$service    = $this->customService->getOne($service_id);
		$data['status'] = 1;
		if ($service['user_id'] == Perm::$userId)
		{
			$flag = $this->customService->editCustomService($service_id, $data);
			if ($flag !== 'false')
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

//		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function detail()
	{
		$service_id = request_int("id");
		$data       = $this->customService->getOneService($service_id);

		$data['type'] = $this->customtype->getOne($data['custom_service_type_id']);
		return $data;
	}

	public function add()
	{
		$data = $this->customtype->getByWhere();

		return $data;
	}

	public function addService()
	{
		$type_id                              = request_int("custom_service_type_id");
		$data['custom_service_type_id']       = $type_id;
		$data['user_id']                      = Perm::$userId;
		$data['user_account']                 = Perm::$row['user_account'];
		$data['custom_service_question']      = request_string("custom_service_question");
		$data['custom_service_question_time'] = date("Y-m-d H:i:s");

		$matche_row = array();
		//有违禁词
		if (Text_Filter::checkBanned($data['custom_service_question'], $matche_row))
		{
			$data   = array();
			$msg    = __('failure');
			$status = 250;
			$this->data->addBody(-140, array(), $msg, $status);
			return false;
		}

		$flag = $this->customService->addCustomService($data);
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
}

?>
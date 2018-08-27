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
class Api_Trade_ConsultCtl extends Api_Controller
{

	public $Consult_TypeModel  = null;
	public $Consult_BaseModel  = null;
	public $Consult_ReplyModel = null;

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
		$this->Consult_TypeModel  = new Consult_TypeModel();
		$this->Consult_BaseModel  = new Consult_BaseModel();
		$this->Consult_ReplyModel = new Consult_ReplyModel();

	}

	public function getTypeList()
	{
		$page                      = request_int('page', 1);
		$rows                      = request_int('rows', 10);
		$oname                     = request_string('sidx');
		$osort                     = request_string('sord');
		$cond_row                  = array();
		$sort                      = array();
		$sort['consult_type_sort'] = "ASC";
		if ($oname != "number")
		{
			$sort[$oname] = $osort;
		}
		$data = array();
		$data = $this->Consult_TypeModel->getCatList($cond_row, $sort, $page, $rows);
		$this->data->addBody(-140, $data);
	}

	public function addTypeBase()
	{
		$field['consult_type_name'] = request_string("consult_type_name");
		$field['consult_type_sort'] = request_int("consult_type_sort");
		$flag                       = $this->Consult_TypeModel->addCat($field, true);
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

	public function editType()
	{
		$id   = request_int("id");
		$data = $this->Consult_TypeModel->getOne($id);
		$this->data->addBody(-140, $data);
	}

	public function editTypeBase()
	{
		$id                         = request_int("consult_type_id");
		$field['consult_type_name'] = request_string("consult_type_name");
		$field['consult_type_sort'] = request_int("consult_type_sort");
		$flag                       = $this->Consult_TypeModel->editCat($id, $field);
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

	public function delType()
	{
		$id   = request_int("id");
		$flag = $this->Consult_TypeModel->removeCat($id);
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

	public function getConsultList()
	{
		$consult_question = request_string("consult_question");
		$user_account     = request_string("user_account");
		$start_time       = request_string("start_time");
		$end_time         = request_string("end_time");

		$page     = request_int('page', 1);
		$rows     = request_int('rows', 10);
		$oname    = request_string('sidx');
		$osort    = request_string('sord');
		$cond_row = array();
		$sort     = array();
		if ($oname != "number")
		{
			$sort[$oname] = $osort;
		}

		if ($consult_question)
		{
			$cond_row['consult_question:LIKE'] = '%' . $consult_question . '%';
		}
		if ($user_account)
		{
			$cond_row['user_account'] = $user_account;
		}
		if ($start_time)
		{
			$cond_row['question_time:>='] = $start_time;
		}
		if ($end_time)
		{
			$cond_row['question_time:<='] = $end_time;
		}
		$data = array();
		$data = $this->Consult_BaseModel->getBaseList($cond_row, $sort, $page, $rows);
		$this->data->addBody(-140, $data);
	}

	public function delConsult()
	{
		$consult_id = request_int("id");
		$consult    = $this->Consult_BaseModel->getOne($consult_id);
		$flag       = $this->Consult_BaseModel->removeBase($consult_id);
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
}

?>
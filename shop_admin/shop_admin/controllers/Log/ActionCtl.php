<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Log_ActionCtl extends AdminController
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}

	/**
	 * 显示页面容器
	 *
	 * @access public
	 */
	public function index()
	{
		include $view = $this->view->getView();;

	}


	/**
	 * 获取lists
	 *
	 * @access public
	 */
	public function lists()
	{
		$Log_ActionModel = new Log_ActionModel();

		//($cond_row = array(), $order_row = array(), $page=1, $rows=100)

		$page = request_int('page', 1);
		$rows = request_int('rows', 100);

		$cond_row = array();

		if (-1 != request_int('user_id', -1))
		{
			$cond_row['user_id'] = request_int('user_id');
		}

		if (request_string('skey'))
		{
			$cond_row['log_param:LIKE'] = '%' . request_string('skey') . '%';
		}


		if (request_string('begin_date'))
		{
			$cond_row['log_date:>='] = request_string('begin_date');
		}

		if (request_string('end_date'))
		{
			$cond_row['log_date:<='] = request_string('end_date');
		}

		$data = $Log_ActionModel->getActionList($cond_row, array('log_id' => 'DESC'), $page, $rows);

		if ($data['records'])
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$status = 250;
			$msg    = _('没有满足条件的结果哦');
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}
}

?>
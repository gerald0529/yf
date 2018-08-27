<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Rec_PositionCtl extends Yf_AppController
{
	public $recPositionModel = null;

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

		//include $this->view->getView();
		$this->recPositionModel = new Rec_PositionModel();
	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function index()
	{
		include $this->view->getView();
	}

	/**
	 * 管理界面
	 *
	 * @access public
	 */
	public function manage()
	{
		include $this->view->getView();
	}

	/**
	 * 列表数据
	 *
	 * @access public
	 */
	public function lists()
	{
		$user_id = Perm::$userId;

		$page = request_int('page');
		$rows = request_int('rows');
		$sort = request_int('sord');

		$cond_row  = array();
		$order_row = array();

		$data = array();

		if ($skey = request_string('skey'))
		{
			$data = $this->recPositionModel->getPositionList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->recPositionModel->getPositionList($cond_row, $order_row, $page, $rows);
		}


		$this->data->addBody(-140, $data);
	}

	/**
	 * 读取
	 *
	 * @access public
	 */
	public function get()
	{
		$user_id = Perm::$userId;

		$position_id = request_int('position_id');
		$rows        = $this->recPositionModel->getPosition($position_id);

		$data = array();

		if ($rows)
		{
			$data = array_pop($rows);
		}

		$this->data->addBody(-140, $data);
	}

	/**
	 * 添加
	 *
	 * @access public
	 */
	public function add()
	{
		$data['position_id']         = request_string('position_id'); // id
		$data['position_title']      = request_string('position_title'); // 推荐位标题
		$data['position_type']       = request_string('position_type'); // 推荐位类型 0-图片 1-文字
		$data['position_pic']        = request_string('position_pic'); // 推荐位图片
		$data['position_content']    = request_string('position_content'); // 文字展示
		$data['position_alert_type'] = request_string('position_alert_type'); // 弹出方式 0 本窗口 1 新窗口
		$data['position_url']        = request_string('position_url'); // 跳转网址
		$data['position_code']       = request_string('position_code'); // 调用代码


		$position_id = $this->recPositionModel->addPosition($data, true);

		if ($position_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['position_id'] = $position_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$position_id = request_int('position_id');

		$flag = $this->recPositionModel->removePosition($position_id);

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

		$data['position_id'] = array($position_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['position_id']         = request_string('position_id'); // id
		$data['position_title']      = request_string('position_title'); // 推荐位标题
		$data['position_type']       = request_string('position_type'); // 推荐位类型 0-图片 1-文字
		$data['position_pic']        = request_string('position_pic'); // 推荐位图片
		$data['position_content']    = request_string('position_content'); // 文字展示
		$data['position_alert_type'] = request_string('position_alert_type'); // 弹出方式 0 本窗口 1 新窗口
		$data['position_url']        = request_string('position_url'); // 跳转网址
		$data['position_code']       = request_string('position_code'); // 调用代码


		$position_id = request_int('position_id');
		$data_rs     = $data;

		unset($data['position_id']);

		$flag = $this->recPositionModel->editPosition($position_id, $data);
		$this->data->addBody(-140, $data_rs);
	}
}

?>
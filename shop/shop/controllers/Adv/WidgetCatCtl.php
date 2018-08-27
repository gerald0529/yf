<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Adv_WidgetCatCtl extends Yf_AppController
{
	public $advWidgetCatModel = null;

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
		$this->advWidgetCatModel = new Adv_WidgetCatModel();
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
			$data = $this->advWidgetCatModel->getWidgetCatList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->advWidgetCatModel->getWidgetCatList($cond_row, $order_row, $page, $rows);
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

		$widget_cat_id = request_int('widget_cat_id');
		$rows          = $this->advWidgetCatModel->getWidgetCat($widget_cat_id);

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
		$data['widget_cat_id']   = request_string('widget_cat_id'); // 分类id
		$data['widget_cat_name'] = request_string('widget_cat_name'); // 分类名称
		$data['widget_cat_desc'] = request_string('widget_cat_desc'); // 描述


		$widget_cat_id = $this->advWidgetCatModel->addWidgetCat($data, true);

		if ($widget_cat_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['widget_cat_id'] = $widget_cat_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$widget_cat_id = request_int('widget_cat_id');

		$flag = $this->advWidgetCatModel->removeWidgetCat($widget_cat_id);

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

		$data['widget_cat_id'] = array($widget_cat_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['widget_cat_id']   = request_string('widget_cat_id'); // 分类id
		$data['widget_cat_name'] = request_string('widget_cat_name'); // 分类名称
		$data['widget_cat_desc'] = request_string('widget_cat_desc'); // 描述


		$widget_cat_id = request_int('widget_cat_id');
		$data_rs       = $data;

		unset($data['widget_cat_id']);

		$flag = $this->advWidgetCatModel->editWidgetCat($widget_cat_id, $data);
		$this->data->addBody(-140, $data_rs);
	}
}

?>
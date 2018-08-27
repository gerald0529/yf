<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Adv_WidgetItemCtl extends Yf_AppController
{
	public $advWidgetItemModel = null;

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
		$this->advWidgetItemModel = new Adv_WidgetItemModel();
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
			$data = $this->advWidgetItemModel->getWidgetItemList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->advWidgetItemModel->getWidgetItemList($cond_row, $order_row, $page, $rows);
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

		$item_id = request_int('item_id');
		$rows    = $this->advWidgetItemModel->getWidgetItem($item_id);

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
		$data['item_id']        = request_string('item_id'); // ID
		$data['user_id']        = request_string('user_id'); // 会员ID
		$data['widget_id']      = request_string('widget_id'); // 广告位id
		$data['item_name']      = request_string('item_name'); // 广告名
		$data['item_url']       = request_string('item_url'); // 点击访问网址
		$data['item_text']      = request_string('item_text'); // 内容
		$data['item_img_url']   = request_string('item_img_url'); // 图片
		$data['item_bgcolor']   = request_string('item_bgcolor'); // 背景颜色
		$data['item_province']  = request_string('item_province'); // 省
		$data['item_city']      = request_string('item_city'); // 市
		$data['item_area']      = request_string('item_area'); // 区
		$data['item_street']    = request_string('item_street'); //
		$data['item_cat_id']    = request_string('item_cat_id'); // 类别ID
		$data['item_stime']     = request_string('item_stime'); // 开始时间
		$data['item_etime']     = request_string('item_etime'); // 结束时间
		$data['item_sort']      = request_string('item_sort'); // 排序
		$data['item_active']    = request_string('item_active'); // 是否启用
		$data['item_time']      = request_string('item_time'); // 创建时间
		$data['item_click_num'] = request_string('item_click_num'); // 点击次数-- 独立建表更好


		$item_id = $this->advWidgetItemModel->addWidgetItem($data, true);

		if ($item_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['item_id'] = $item_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$item_id = request_int('item_id');

		$flag = $this->advWidgetItemModel->removeWidgetItem($item_id);

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

		$data['item_id'] = array($item_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['item_id']        = request_string('item_id'); // ID
		$data['user_id']        = request_string('user_id'); // 会员ID
		$data['widget_id']      = request_string('widget_id'); // 广告位id
		$data['item_name']      = request_string('item_name'); // 广告名
		$data['item_url']       = request_string('item_url'); // 点击访问网址
		$data['item_text']      = request_string('item_text'); // 内容
		$data['item_img_url']   = request_string('item_img_url'); // 图片
		$data['item_bgcolor']   = request_string('item_bgcolor'); // 背景颜色
		$data['item_province']  = request_string('item_province'); // 省
		$data['item_city']      = request_string('item_city'); // 市
		$data['item_area']      = request_string('item_area'); // 区
		$data['item_street']    = request_string('item_street'); //
		$data['item_cat_id']    = request_string('item_cat_id'); // 类别ID
		$data['item_stime']     = request_string('item_stime'); // 开始时间
		$data['item_etime']     = request_string('item_etime'); // 结束时间
		$data['item_sort']      = request_string('item_sort'); // 排序
		$data['item_active']    = request_string('item_active'); // 是否启用
		$data['item_time']      = request_string('item_time'); // 创建时间
		$data['item_click_num'] = request_string('item_click_num'); // 点击次数-- 独立建表更好


		$item_id = request_int('item_id');
		$data_rs = $data;

		unset($data['item_id']);

		$flag = $this->advWidgetItemModel->editWidgetItem($item_id, $data);
		$this->data->addBody(-140, $data_rs);
	}
}

?>
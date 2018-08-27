<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Adv_WidgetBaseCtl extends Yf_AppController
{
	public $advWidgetBaseModel = null;

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
		$this->advWidgetBaseModel = new Adv_WidgetBaseModel();
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
			$data = $this->advWidgetBaseModel->getWidgetBaseList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->advWidgetBaseModel->getWidgetBaseList($cond_row, $order_row, $page, $rows);
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

		$widget_id = request_int('widget_id');
		$rows      = $this->advWidgetBaseModel->getWidgetBase($widget_id);

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
		$data['widget_id']        = request_string('widget_id'); // id
		$data['user_id']          = request_string('user_id'); // 用户id
		$data['page_id']          = request_string('page_id'); // 广告页id
		$data['layout_id']        = request_string('layout_id'); // 模板布局id， 如果没有可以为0，可以理解为组概念
		$data['widget_name']      = request_string('widget_name'); // 广告位名:如果有layout, 则用block1... 程序自动命名。  目前只按照具备layout的功能开发
		$data['widget_cat']       = request_string('widget_cat'); // 类别，目前有layout设定决定：广告（自定义数据）|商品分类（商城获取）|商品（商城获取）
		$data['widget_width']     = request_string('widget_width'); // 宽度
		$data['widget_height']    = request_string('widget_height'); // 高度
		$data['widget_type']      = request_string('widget_type'); // 类型: 图片 幻灯片 滚动 文字  - 如果针对mall等等固定使用地方，可以修改成固定类型
		$data['widget_desc']      = request_string('widget_desc'); // 描述
		$data['widget_price']     = request_string('widget_price'); // 价格
		$data['widget_unit']      = request_string('widget_unit'); // 单位
		$data['widget_total']     = request_string('widget_total'); // 广告数量
		$data['widget_time']      = request_string('widget_time'); // 创建时间
		$data['widget_view_num']  = request_string('widget_view_num'); // page view  - 独立建表更好 - cpm可以使用
		$data['widget_click_num'] = request_string('widget_click_num'); // 点击次数 - 独立建表更好 - cpc可以使用


		$widget_id = $this->advWidgetBaseModel->addWidgetBase($data, true);

		if ($widget_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['widget_id'] = $widget_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$widget_id = request_int('widget_id');

		$flag = $this->advWidgetBaseModel->removeWidgetBase($widget_id);

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

		$data['widget_id'] = array($widget_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['widget_id']        = request_string('widget_id'); // id
		$data['user_id']          = request_string('user_id'); // 用户id
		$data['page_id']          = request_string('page_id'); // 广告页id
		$data['layout_id']        = request_string('layout_id'); // 模板布局id， 如果没有可以为0，可以理解为组概念
		$data['widget_name']      = request_string('widget_name'); // 广告位名:如果有layout, 则用block1... 程序自动命名。  目前只按照具备layout的功能开发
		$data['widget_cat']       = request_string('widget_cat'); // 类别，目前有layout设定决定：广告（自定义数据）|商品分类（商城获取）|商品（商城获取）
		$data['widget_width']     = request_string('widget_width'); // 宽度
		$data['widget_height']    = request_string('widget_height'); // 高度
		$data['widget_type']      = request_string('widget_type'); // 类型: 图片 幻灯片 滚动 文字  - 如果针对mall等等固定使用地方，可以修改成固定类型
		$data['widget_desc']      = request_string('widget_desc'); // 描述
		$data['widget_price']     = request_string('widget_price'); // 价格
		$data['widget_unit']      = request_string('widget_unit'); // 单位
		$data['widget_total']     = request_string('widget_total'); // 广告数量
		$data['widget_time']      = request_string('widget_time'); // 创建时间
		$data['widget_view_num']  = request_string('widget_view_num'); // page view  - 独立建表更好 - cpm可以使用
		$data['widget_click_num'] = request_string('widget_click_num'); // 点击次数 - 独立建表更好 - cpc可以使用


		$widget_id = request_int('widget_id');
		$data_rs   = $data;

		unset($data['widget_id']);

		$flag = $this->advWidgetBaseModel->editWidgetBase($widget_id, $data);
		$this->data->addBody(-140, $data_rs);
	}
}

?>
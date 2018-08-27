<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Platform_NavCtl extends Yf_AppController
{
	public $platformNavModel = null;

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
		$this->platformNavModel = new Platform_NavModel();
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
			$data = $this->platformNavModel->getNavList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->platformNavModel->getNavList($cond_row, $order_row, $page, $rows);
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

		$nav_id = request_int('nav_id');
		$rows   = $this->platformNavModel->getNav($nav_id);

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
		$data['nav_id']      = request_string('nav_id'); // 索引ID
		$data['nav_type']    = request_string('nav_type'); // 类别，0自定义导航，1商品分类，2文章导航，3活动导航，默认为0
		$data['nav_item_id'] = request_string('nav_item_id'); // 类别ID，对应着nav_type中的内容，默认为0
		$data['nav_title']   = request_string('nav_title'); // 导航标题
		$data['nav_url']     = request_string('nav_url'); // 导航链接
		//$data['nav_location']           = request_string('nav_location')  ; // 导航位置，0头部，1中部，2底部，默认为0
		$data['nav_new_open']     = request_string('nav_new_open'); // 是否以新窗口打开，0为否，1为是，默认为0
		$data['nav_displayorder'] = request_string('nav_displayorder'); // 排序
		$data['nav_active']       = request_string('nav_active'); // 是否启用
		$data['nav_readonly']     = request_string('nav_readonly'); // 不可修改-团购、积分等等


		$nav_id = $this->platformNavModel->addNav($data, true);

		if ($nav_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['nav_id'] = $nav_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$nav_id = request_int('nav_id');

		$flag = $this->platformNavModel->removeNav($nav_id);

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

		$data['nav_id'] = array($nav_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['nav_id']      = request_string('nav_id'); // 索引ID
		$data['nav_type']    = request_string('nav_type'); // 类别，0自定义导航，1商品分类，2文章导航，3活动导航，默认为0
		$data['nav_item_id'] = request_string('nav_item_id'); // 类别ID，对应着nav_type中的内容，默认为0
		$data['nav_title']   = request_string('nav_title'); // 导航标题
		$data['nav_url']     = request_string('nav_url'); // 导航链接
		//$data['nav_location']           = request_string('nav_location')  ; // 导航位置，0头部，1中部，2底部，默认为0
		$data['nav_new_open']     = request_string('nav_new_open'); // 是否以新窗口打开，0为否，1为是，默认为0
		$data['nav_displayorder'] = request_string('nav_displayorder'); // 排序
		$data['nav_active']       = request_string('nav_active'); // 是否启用
		$data['nav_readonly']     = request_string('nav_readonly'); // 不可修改-团购、积分等等


		$nav_id  = request_int('nav_id');
		$data_rs = $data;

		unset($data['nav_id']);

		$flag = $this->platformNavModel->editNav($nav_id, $data);

		if ($flag !== false)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, $data_rs, $msg, $status);
	}
}

?>
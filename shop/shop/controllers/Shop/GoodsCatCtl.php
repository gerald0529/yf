<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Shop_GoodsCatCtl extends Yf_AppController
{
	public $shopGoodsCatModel = null;

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
		$this->shopGoodsCatModel = new Shop_GoodsCatModel();
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
			$data = $this->shopGoodsCatModel->getGoodsCatList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->shopGoodsCatModel->getGoodsCatList($cond_row, $order_row, $page, $rows);
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

		$shop_goods_cat_id = request_int('shop_goods_cat_id');
		$rows              = $this->shopGoodsCatModel->getGoodsCat($shop_goods_cat_id);

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
		$data['shop_goods_cat_id']           = request_string('shop_goods_cat_id'); //
		$data['shop_goods_cat_name']         = request_string('shop_goods_cat_name'); //
		$data['shop_id']                     = request_string('shop_id'); //
		$data['parent_id']                   = request_string('parent_id'); //
		$data['shop_goods_cat_displayorder'] = request_string('shop_goods_cat_displayorder'); //
		$data['shop_goods_cat_status']       = request_string('shop_goods_cat_status'); //


		$shop_goods_cat_id = $this->shopGoodsCatModel->addGoodsCat($data, true);

		if ($shop_goods_cat_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['shop_goods_cat_id'] = $shop_goods_cat_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$shop_goods_cat_id = request_int('shop_goods_cat_id');

		$flag = $this->shopGoodsCatModel->removeGoodsCat($shop_goods_cat_id);

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

		$data['shop_goods_cat_id'] = array($shop_goods_cat_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['shop_goods_cat_id']           = request_string('shop_goods_cat_id'); //
		$data['shop_goods_cat_name']         = request_string('shop_goods_cat_name'); //
		$data['shop_id']                     = request_string('shop_id'); //
		$data['parent_id']                   = request_string('parent_id'); //
		$data['shop_goods_cat_displayorder'] = request_string('shop_goods_cat_displayorder'); //
		$data['shop_goods_cat_status']       = request_string('shop_goods_cat_status'); //


		$shop_goods_cat_id = request_int('shop_goods_cat_id');
		$data_rs           = $data;

		unset($data['shop_goods_cat_id']);

		$flag = $this->shopGoodsCatModel->editGoodsCat($shop_goods_cat_id, $data);
		$this->data->addBody(-140, $data_rs);
	}
}

?>
<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_PropertyIndexCtl extends Yf_AppController
{
	public $goodsPropertyIndexModel = null;

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
		$this->goodsPropertyIndexModel = new Goods_PropertyIndexModel();
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
			$data = $this->goodsPropertyIndexModel->getPropertyIndexList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->goodsPropertyIndexModel->getPropertyIndexList($cond_row, $order_row, $page, $rows);
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

		$goods_id = request_int('goods_id');
		$rows     = $this->goodsPropertyIndexModel->getPropertyIndex($goods_id);

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
		$data['goods_id']          = request_string('goods_id'); // 商品id
		$data['common_id']         = request_string('common_id'); // 商品公共表id
		$data['cat_id']            = request_string('cat_id'); // 商品分类id
		$data['type_id']           = request_string('type_id'); // 类型id
		$data['property_id']       = request_string('property_id'); // 属性id
		$data['property_value_id'] = request_string('property_value_id'); // 属性值id


		$goods_id = $this->goodsPropertyIndexModel->addPropertyIndex($data, true);

		if ($goods_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['goods_id'] = $goods_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$goods_id = request_int('goods_id');

		$flag = $this->goodsPropertyIndexModel->removePropertyIndex($goods_id);

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

		$data['goods_id'] = array($goods_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['goods_id']          = request_string('goods_id'); // 商品id
		$data['common_id']         = request_string('common_id'); // 商品公共表id
		$data['cat_id']            = request_string('cat_id'); // 商品分类id
		$data['type_id']           = request_string('type_id'); // 类型id
		$data['property_id']       = request_string('property_id'); // 属性id
		$data['property_value_id'] = request_string('property_value_id'); // 属性值id


		$goods_id = request_int('goods_id');
		$data_rs  = $data;

		unset($data['goods_id']);

		$flag = $this->goodsPropertyIndexModel->editPropertyIndex($goods_id, $data);
		$this->data->addBody(-140, $data_rs);
	}
}

?>
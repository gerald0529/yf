<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_RecommendCtl extends Yf_AppController
{
	public $goodsRecommendModel = null;

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
		$this->goodsRecommendModel = new Goods_RecommendModel();
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
			$data = $this->goodsRecommendModel->getRecommendList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->goodsRecommendModel->getRecommendList($cond_row, $order_row, $page, $rows);
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

		$goods_recommend_id = request_int('goods_recommend_id');
		$rows               = $this->goodsRecommendModel->getRecommend($goods_recommend_id);

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
		$data['goods_recommend_id'] = request_string('goods_recommend_id'); // 商品推荐id
		$data['goods_cat_id']       = request_string('goods_cat_id'); // 商品分类id
		$data['common_id']          = request_string('common_id'); // 推荐商品id，最多四个


		$goods_recommend_id = $this->goodsRecommendModel->addRecommend($data, true);

		if ($goods_recommend_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['goods_recommend_id'] = $goods_recommend_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$goods_recommend_id = request_int('goods_recommend_id');

		$flag = $this->goodsRecommendModel->removeRecommend($goods_recommend_id);

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

		$data['goods_recommend_id'] = array($goods_recommend_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['goods_recommend_id'] = request_string('goods_recommend_id'); // 商品推荐id
		$data['goods_cat_id']       = request_string('goods_cat_id'); // 商品分类id
		$data['common_id']          = request_string('common_id'); // 推荐商品id，最多四个


		$goods_recommend_id = request_int('goods_recommend_id');
		$data_rs            = $data;

		unset($data['goods_recommend_id']);

		$flag = $this->goodsRecommendModel->editRecommend($goods_recommend_id, $data);
		$this->data->addBody(-140, $data_rs);
	}
}

?>
<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_ImagesCtl extends Yf_AppController
{
	public $goodsImagesModel = null;

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
		$this->goodsImagesModel = new Goods_ImagesModel();
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
			$data = $this->goodsImagesModel->getImagesList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->goodsImagesModel->getImagesList($cond_row, $order_row, $page, $rows);
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

		$id   = request_int('id');
		$rows = $this->goodsImagesModel->getImages($id);

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
		$data['id']           = request_string('id'); // 商品图片id
		$data['common_id']    = request_string('common_id'); // 商品公共内容id
		$data['shop_id']      = request_string('shop_id'); // 店铺id
		$data['color_id']     = request_string('color_id'); // 颜色规格值id
		$data['image']        = request_string('image'); // 商品图片
		$data['displayorder'] = request_string('displayorder'); // 排序
		$data['is_default']   = request_string('is_default'); // 默认主题，1是，0否


		$id = $this->goodsImagesModel->addImages($data, true);

		if ($id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['id'] = $id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$id = request_int('id');

		$flag = $this->goodsImagesModel->removeImages($id);

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

		$data['id'] = array($id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['id']           = request_string('id'); // 商品图片id
		$data['common_id']    = request_string('common_id'); // 商品公共内容id
		$data['shop_id']      = request_string('shop_id'); // 店铺id
		$data['color_id']     = request_string('color_id'); // 颜色规格值id
		$data['image']        = request_string('image'); // 商品图片
		$data['displayorder'] = request_string('displayorder'); // 排序
		$data['is_default']   = request_string('is_default'); // 默认主题，1是，0否


		$id      = request_int('id');
		$data_rs = $data;

		unset($data['id']);

		$flag = $this->goodsImagesModel->editImages($id, $data);
		$this->data->addBody(-140, $data_rs);
	}
}

?>
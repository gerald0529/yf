<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_CommonDetailCtl extends Yf_AppController
{
	public $goodsCommonDetailModel = null;

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
		$this->initData();
		//include $this->view->getView();
		$this->goodsCommonDetailModel = new Goods_CommonDetailModel();
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
			$data = $this->goodsCommonDetailModel->getCommonDetailList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->goodsCommonDetailModel->getCommonDetailList($cond_row, $order_row, $page, $rows);
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

		$common_id = request_int('common_id');
		$rows      = $this->goodsCommonDetailModel->getCommonDetail($common_id);

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
		$data['common_id']   = request_string('common_id'); // 商品id
		$data['common_body'] = request_string('common_body'); // 商品内容


		$common_id = $this->goodsCommonDetailModel->addCommonDetail($data, true);

		if ($common_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['common_id'] = $common_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$common_id = request_int('common_id');

		$flag = $this->goodsCommonDetailModel->removeCommonDetail($common_id);

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

		$data['common_id'] = array($common_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['common_id']   = request_string('common_id'); // 商品id
		$data['common_body'] = request_string('common_body'); // 商品内容


		$common_id = request_int('common_id');
		$data_rs   = $data;

		unset($data['common_id']);

		$flag = $this->goodsCommonDetailModel->editCommonDetail($common_id, $data);
		$this->data->addBody(-140, $data_rs);
	}
}

?>
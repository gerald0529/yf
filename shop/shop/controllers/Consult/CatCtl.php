<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Consult_CatCtl extends Yf_AppController
{
	public $consultCatModel = null;

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
		$this->consultCatModel = new Consult_CatModel();
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
			$data = $this->consultCatModel->getCatList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->consultCatModel->getCatList($cond_row, $order_row, $page, $rows);
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

		$consult_cat_id = request_int('consult_cat_id');
		$rows           = $this->consultCatModel->getCat($consult_cat_id);

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
		$data['consult_cat_id']   = request_string('consult_cat_id'); // 咨询类别id
		$data['consult_cat_name'] = request_string('consult_cat_name'); // 咨询类别名称
		$data['consult_cat_type'] = request_string('consult_cat_type'); // 是否启用
		$data['consult_cat_con']  = request_string('consult_cat_con'); // 咨询分类内容


		$consult_cat_id = $this->consultCatModel->addCat($data, true);

		if ($consult_cat_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['consult_cat_id'] = $consult_cat_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$consult_cat_id = request_int('consult_cat_id');

		$flag = $this->consultCatModel->removeCat($consult_cat_id);

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

		$data['consult_cat_id'] = array($consult_cat_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['consult_cat_id']   = request_string('consult_cat_id'); // 咨询类别id
		$data['consult_cat_name'] = request_string('consult_cat_name'); // 咨询类别名称
		$data['consult_cat_type'] = request_string('consult_cat_type'); // 是否启用
		$data['consult_cat_con']  = request_string('consult_cat_con'); // 咨询分类内容


		$consult_cat_id = request_int('consult_cat_id');
		$data_rs        = $data;

		unset($data['consult_cat_id']);

		$flag = $this->consultCatModel->editCat($consult_cat_id, $data);
		$this->data->addBody(-140, $data_rs);
	}
}

?>
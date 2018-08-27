<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Consult_BaseCtl extends Yf_AppController
{
	public $consultBaseModel = null;

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
		$this->consultBaseModel = new Consult_BaseModel();
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
			$data = $this->consultBaseModel->getBaseList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->consultBaseModel->getBaseList($cond_row, $order_row, $page, $rows);
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

		$consult_id = request_int('consult_id');
		$rows       = $this->consultBaseModel->getBase($consult_id);

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
		$data['consult_id']        = request_string('consult_id'); // 咨询id
		$data['consult_type_id']   = request_string('consult_type_id'); // 咨询类别id
		$data['consult_type_name'] = request_string('consult_type_name'); // 咨询类别名
		$data['shop_id']           = request_string('shop_id'); // 店铺id
		$data['shop_name']         = request_string('shop_name'); // 店铺名称
		$data['goods_id']          = request_string('goods_id'); // 商品id
		$data['goods_name']        = request_string('goods_name'); // 商品名称
		$data['user_id']           = request_string('user_id'); // 用户id
		$data['user_account']      = request_string('user_account'); // 用户名称
		$data['consult_question']  = request_string('consult_question'); // 咨询内容
		$data['consult_answer']    = request_string('consult_answer'); // 咨询回答
		$data['question_time']     = request_string('question_time'); // 提问时间
		$data['answer_time']       = request_string('answer_time'); // 回答时间
		$data['consult_state']     = request_string('consult_state'); // 1-未回复 2-已回复


		$consult_id = $this->consultBaseModel->addBase($data, true);

		if ($consult_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['consult_id'] = $consult_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$consult_id = request_int('consult_id');

		$flag = $this->consultBaseModel->removeBase($consult_id);

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

		$data['consult_id'] = array($consult_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['consult_id']        = request_string('consult_id'); // 咨询id
		$data['consult_type_id']   = request_string('consult_type_id'); // 咨询类别id
		$data['consult_type_name'] = request_string('consult_type_name'); // 咨询类别名
		$data['shop_id']           = request_string('shop_id'); // 店铺id
		$data['shop_name']         = request_string('shop_name'); // 店铺名称
		$data['goods_id']          = request_string('goods_id'); // 商品id
		$data['goods_name']        = request_string('goods_name'); // 商品名称
		$data['user_id']           = request_string('user_id'); // 用户id
		$data['user_account']      = request_string('user_account'); // 用户名称
		$data['consult_question']  = request_string('consult_question'); // 咨询内容
		$data['consult_answer']    = request_string('consult_answer'); // 咨询回答
		$data['question_time']     = request_string('question_time'); // 提问时间
		$data['answer_time']       = request_string('answer_time'); // 回答时间
		$data['consult_state']     = request_string('consult_state'); // 1-未回复 2-已回复


		$consult_id = request_int('consult_id');
		$data_rs    = $data;

		unset($data['consult_id']);

		$flag = $this->consultBaseModel->editBase($consult_id, $data);
		$this->data->addBody(-140, $data_rs);
	}
}

?>
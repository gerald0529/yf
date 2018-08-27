<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Member_AgreementCtl extends Yf_AppController
{
	public $memberAgreementModel = null;

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
		$this->memberAgreementModel = new Member_AgreementModel();
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
			$data = $this->memberAgreementModel->getAgreementList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->memberAgreementModel->getAgreementList($cond_row, $order_row, $page, $rows);
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

		$member_agreement_id = request_int('member_agreement_id');
		$rows                = $this->memberAgreementModel->getAgreement($member_agreement_id);

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
		$data['member_agreement_id']      = request_string('member_agreement_id'); // 会员协议id
		$data['member_agreement_title']   = request_string('member_agreement_title'); // 会员协议标题
		$data['member_agreement_content'] = request_string('member_agreement_content'); // 会员协议内容
		$data['member_agreement_time']    = request_string('member_agreement_time'); // 会员协议添加时间


		$member_agreement_id = $this->memberAgreementModel->addAgreement($data, true);

		if ($member_agreement_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['member_agreement_id'] = $member_agreement_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$member_agreement_id = request_int('member_agreement_id');

		$flag = $this->memberAgreementModel->removeAgreement($member_agreement_id);

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

		$data['member_agreement_id'] = array($member_agreement_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['member_agreement_id']      = request_string('member_agreement_id'); // 会员协议id
		$data['member_agreement_title']   = request_string('member_agreement_title'); // 会员协议标题
		$data['member_agreement_content'] = request_string('member_agreement_content'); // 会员协议内容
		$data['member_agreement_time']    = request_string('member_agreement_time'); // 会员协议添加时间


		$member_agreement_id = request_int('member_agreement_id');
		$data_rs             = $data;

		unset($data['member_agreement_id']);

		$flag = $this->memberAgreementModel->editAgreement($member_agreement_id, $data);
		$this->data->addBody(-140, $data_rs);
	}
}

?>
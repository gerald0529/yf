<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Member_AgreementCtl extends Api_Controller
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}

	/*
	 * 获取会员协议内容
	 */
	public function memberAgreementList()
	{
		$Member_Agreement = new Member_Agreement();

		$page       = request_int('page');
		$rows       = request_int('rows');
		$cond_rows  = array();
		$order_rows = array();

		$data = $Member_Agreement->listByWhere($cond_rows, $order_rows, $page, $rows);

		if ($data)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/*
	 * 添加会员协议
	 */
	public function addMmberAgreement()
	{
		$Member_AgreementModel = new Member_AgreementModel();

		$data                             = array();
		$data['member_agreement_title']   = request_string('member_agreement_title');
		$data['member_agreement_content'] = request_string('content');
		$member_agreement_pic             = request_row('setting');
		$data['member_agreement_pic']     = $member_agreement_pic['member_agreement_logo'];
		$data['member_agreement_time']    = date('Y-m-d h:i:s', time());

		$member_agreement_id = $Member_AgreementModel->addAgreement($data, true);

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

		$data['id']                  = $member_agreement_id;
		$data['member_agreement_id'] = $member_agreement_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function editMemberAgreement()
	{
		$Member_AgreementModel = new Member_AgreementModel();
		$data                  = array();

		$id = request_int('member_agreement_id');

		$data['member_agreement_title']   = request_string('member_agreement_title');
		$data['member_agreement_content'] = request_string('content');
		$member_agreement_pic             = request_row('setting');
		$data['member_agreement_pic']     = $member_agreement_pic['member_agreement_logo'];

		$flag = $Member_AgreementModel->editAgreement($id, $data);

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

		$data['id']                  = $id;
		$data['member_agreement_id'] = $id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function removeMemberAgreement()
	{
		$Member_AgreementModel = new Member_AgreementModel();
		$id                    = request_int('member_agreement_id');
		$flag                  = $Member_AgreementModel->removeAgreement($id);
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

		$this->data->addBody(-140, array(), $msg, $status);
	}

}

?>
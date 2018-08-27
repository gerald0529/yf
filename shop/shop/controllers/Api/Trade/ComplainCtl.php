<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}


/**
 * Api接口, 让App等调用
 *
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class Api_Trade_ComplainCtl extends Api_Controller
{
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
	}

	/**
	 * 获取投诉列表
	 *
	 * @access public
	 */
	public function getComplainList()
	{
		$page = request_int('page', 1);
		$rows = request_int('rows', 100);

		$state        = request_string('state', null);
		$user_type    = request_string('user_type');
		$user_account = request_string('search_name');

		$cond_row = array();
		$data     = array();

		//投诉状态
		if (null !== $state)
		{
			$cond_row['complain_state'] = $state;
		}
		//按照投诉人与被投诉人查询
		if ($user_account)
		{
			if ($user_type)
			{
				$type = 'user_account_accused:LIKE';
			}
			else
			{
				$type = 'user_account_accuser:LIKE';
			}
			$cond_row[$type] = '%' . $user_account . '%';
		}
		$Complain_BaseModel = new Complain_BaseModel();
		$data               = $Complain_BaseModel->getBaseList($cond_row, array('complain_id' => 'ASC'), $page, $rows);

		if ($data['records'])
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('没有满足条件的结果哦');
		}
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 获取投诉主题列表
	 *
	 * @access public
	 */
	public function getComplainSubjectList()
	{
		$page = request_int('page', 1);
		$rows = request_int('rows', 100);

		$state = request_int('state');

		$cond_row = array();
		//投诉主题状态
		$cond_row['complain_subject_state'] = $state;


		$Complain_SubjectModel = new Complain_SubjectModel();
		$data                  = $Complain_SubjectModel->listByWhere($cond_row, array('complain_subject_id' => 'ASC'), $page, $rows);


		if ($data['records'])
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('没有满足条件的结果哦');
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 添加投诉主题
	 *
	 * @access public
	 */
	public function addComplainSubject()
	{
		$complain_subject_content = request_string('complain_subject_content');
		$complain_subject_desc    = request_string('complain_subject_desc');

		$field = array(
			'complain_subject_content' => $complain_subject_content,
			'complain_subject_desc' => $complain_subject_desc,
		);

		$Complain_SubjectModel = new Complain_SubjectModel();
		$flag                  = $Complain_SubjectModel->addSubject($field);

		if ($flag)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

	/**
	 * 删除投诉主题
	 *
	 * @access public
	 */
	public function delComplainSubjectById()
	{
		$complain_subject_id = request_int('complain_subject_id');

		$edit_row              = array('complain_subject_state' => Complain_SubjectModel::SUBJECT_MASK);
		$Complain_SubjectModel = new Complain_SubjectModel();
		$flag                  = $Complain_SubjectModel->editSubject($complain_subject_id, $edit_row);

		if ($flag)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}
		$data = array('id' => $complain_subject_id);
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 获取投诉详细（审核）
	 *
	 * @access public
	 */
	public function getComplainInfo()
	{
		$complain_id    = request_int('id');
		$complain_state = request_int('state');

		//投诉信息
		$complain_cond_row['complain_id'] = $complain_id;
		$Complain_BaseModel               = new Complain_BaseModel();
		$data['complain']                 = $Complain_BaseModel->getComplainBase($complain_cond_row);

		$data['complain']['complain_state_content'] = $Complain_BaseModel->stateMap[$data['complain']['complain_state']];
		$data['complain']['state']                  = Complain_BaseModel::$state[$complain_state];


		//订单详情
		$order_cond_row['order_id'] = $data['complain']['order_id'];
		$Order_BaseModel            = new Order_BaseModel();
		$data['order']              = $Order_BaseModel->getOneByWhere($order_cond_row);

		$order_detail_cond_row['order_id'] = $data['order']['order_id'];
		$Order_BaseModel                   = new Order_BaseModel();
		$data['order_detail']              = $Order_BaseModel->getOneByWhere($order_detail_cond_row);

		$order_state_cond_row['order_state_id'] = $data['order_detail']['order_status'];
		$Order_StateModel                       = new Order_StateModel();
		$state                                  = $Order_StateModel->getOneByWhere($order_state_cond_row);
		$data['order_detail']['status']         = $state['order_state_text_1'];


		//投诉商品信息
		$good_cond_row['complain_id'] = $data['complain']['complain_id'];
		$Complain_GoodsModel          = new Complain_GoodsModel();
		$data['complain_goods']       = $Complain_GoodsModel->getOneByWhere($good_cond_row);

		$this->data->addBody(-140, $data);
	}

	/**
	 * 获取时效设置
	 *
	 * @access public
	 */
	public function setting()
	{
		$Web_ConfigModel = new Web_ConfigModel();
		$data[]          = $Web_ConfigModel->getConfigValue('complain_datetime');

		$this->data->addBody(-140, $data);
	}

	/**
	 * 获取投诉对话
	 *
	 * @access public
	 */
	public function getComplainTalk()
	{
		$complain_id = request_int('complain_id');
		//对话信息
		$talk_cond_row['complain_id'] = $complain_id;
		$Complain_TalkModel           = new Complain_TalkModel();
		$data                         = $Complain_TalkModel->getComplainTalk($talk_cond_row, array('talk_id' => 'DESC'));

		$this->data->addBody(-140, $data);
	}

	/**
	 * 屏蔽对话
	 *
	 * @access public
	 */
	public function forbitTalk()
	{
		$talk_id    = request_int('talk_id');
		$talk_admin = request_int('talk_admin');

		$Complain_TalkModel = new Complain_TalkModel();

		$edit_row = array(
			'talk_state' => Complain_TalkModel::TALK_MASK,
			'talk_admin' => $talk_admin,
		);

		$flag = $Complain_TalkModel->editTalk($talk_id, $edit_row);

		if ($flag)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 发布投诉对话
	 *
	 * @access public
	 */
	public function publishComplainTalk()
	{
		$complain_id   = request_int('complain_id');
		$complain_talk = request_string('complain_talk');
		$user_id       = request_int('user_id');
		$user_account  = request_string('user_account');
		$member_type   = request_string('member_type');


		$filed = array(
			'complain_id' => $complain_id,
			'user_id' => $user_id,
			'user_name' => $user_account,
			'talk_member_type' => $member_type,
			'talk_content' => $complain_talk,
			'talk_datetime' => date('Y-m-d H:i:s'),
		);

		$Complain_TalkModel = new Complain_TalkModel();
		$flag               = $Complain_TalkModel->addTalk($filed);

		if ($flag)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

	/**
	 * 审核投诉
	 *
	 * @access public
	 */
	public function verifyComplain()
	{
		$complain_id = request_int('complain_id');
		//处理人id
		$complain_handle_user_id = request_int('complain_handle_user_id');

		$field['complain_state']           = Complain_BaseModel::COMPLAIN_APPEAL;
		$field['complain_handle_user_id']  = $complain_handle_user_id;
		$field['complain_handle_datetime'] = date('Y-m-d H:i:s');
		$field['complain_active']          = Complain_BaseModel::VERIFY_PASS;

		$Complain_BaseModel = new Complain_BaseModel();
		$flag               = $Complain_BaseModel->editBase($complain_id, $field);

		if ($flag)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}


	/**
	 * 处理投诉（关闭）
	 *
	 * @access public
	 */
	public function handleComplain()
	{
		$complain_id = request_int('complain_id');
		//最终处理人id
		$user_id_final_handle = request_int('user_id_final_handle');
		//最终处理意见
		$final_handle_message = request_string('final_handle_message');

		$field['complain_state']        = Complain_BaseModel::COMPLAIN_FINISH;
		$field['user_id_final_handle']  = $user_id_final_handle;
		$field['final_handle_message']  = $final_handle_message;
		$field['final_handle_datetime'] = date('Y-m-d H:i:s');

		$Complain_BaseModel = new Complain_BaseModel();
		$flag               = $Complain_BaseModel->editBase($complain_id, $field);

		if ($flag)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}
}

?>
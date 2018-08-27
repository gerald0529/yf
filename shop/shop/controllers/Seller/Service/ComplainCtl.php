<?php

if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Seller_Service_ComplainCtl extends Seller_Controller
{

	public $complainBaseModel  = null;
	public $complainGoodsModel = null;


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
		$this->complainBaseModel  = new Complain_BaseModel();
		$this->complainGoodsModel = new Complain_GoodsModel();
	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function index()
	{
		$act = request_string('act');

		if ($act == "detail")
		{
			$data = $this->detail();
			$this->view->setMet('detail');
			$d = $data;
		}
		else
		{
			$Yf_Page           = new Yf_Page();
			$Yf_Page->listRows = 10;
			$rows              = $Yf_Page->listRows;
			$offset            = request_int('firstRow', 0);
			$page              = ceil_r($offset / $rows);

			$cond_row['user_id_accused'] = Perm::$shopId;         //店铺ID

			$keyword    = request_string("keys");
			$start_time = request_string("start_date");
			$end_time   = request_string("end_date");
			$state      = request_int("status");

			if ($keyword)
			{
				$cond_row['complain_content:LIKE'] = "%" . $keyword . "%";
			}
			if ($state)
			{
				$cond_row['complain_state'] = $state;
			}
			if ($start_time)
			{
				$cond_row['complain_datetime:>='] = $start_time;
			}
			if ($end_time)
			{
				$cond_row['complain_datetime:<='] = $end_time;
			}
			$data = $this->complainBaseModel->getBaseList($cond_row, array('complain_datetime' => 'DESC'), $page, $rows);

			$complain_ids                    = array_column($data['items'], 'complain_id');
			$cond_good_row['complain_id:in'] = $complain_ids;
			$complain_goods                  = $this->complainGoodsModel->getByWhere($cond_good_row);

			foreach ($complain_goods as $v)
			{
				$goods[$v['complain_id']] = $v;
			}
			foreach ($data['items'] as $key => $val)
			{
				$data['items'][$key]['good'] = $goods[$val['complain_id']];
			}

			$Yf_Page->totalRows = $data['totalsize'];
			$page_nav           = $Yf_Page->prompt();
			$data['keys']       = $keyword;
			$data['state']      = $state;
			$data['start_date'] = $start_time;
			$data['end_date']   = $end_time;
			$d                  = $data;
		}

		if ($this->typ == "json")
		{
			$this->data->addBody(-140, $d);
		}
		else
		{
			include $this->view->getView();
		}
	}


	public function detail()
	{
		$complain_id                 = request_int("id");
		$cond_row['complain_id']     = $complain_id;
		$cond_row['user_id_accused'] = Perm::$shopId;
		$data                        = $this->complainBaseModel->getComplainBase($cond_row);

		$data['good'] = $this->complainGoodsModel->getOneByWhere(array("complain_id" => $complain_id));
		$data['id']   = $complain_id;
		return $data;
	}


	public function cancelComplain()
	{
		$complain_id   = request_int("id");
		$complain_data = $this->complainBaseModel->getOne($complain_id);

		if ($complain_data['user_id_accused'] == Perm::$shopId)
		{
			$field_row['complain_state'] = 5;
			$flag                        = $this->complainBaseModel->editBase($complain_id, $field_row);

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
		else
		{

			$status = 250;
			$msg    = __('failure');

			$data = array();
			$this->data->addBody(-140, $data, $msg, $status);

		}
	}


	public function appealComplain()
	{
		$complain_id = request_int("complain_id");

		$complain = $this->complainBaseModel->getOne($complain_id);

		if ($complain['user_id_accused'] == Perm::$shopId)
		{
			$data['appeal_message']  = request_string("appeal_message");
			$data['appeal_pic']      = implode(",", request_row("appeal_pic"));
			$data['appeal_datetime'] = date("Y-m-d H:i:s");
			$data['complain_state']  = Complain_BaseModel::COMPLAIN_TALK;

			$flag = $this->complainBaseModel->editBase($complain_id, $data);

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
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

	public function submitComplain()
	{
		$complain_id = request_int("complain_id");

		$complain = $this->complainBaseModel->getOne($complain_id);

		if ($complain['user_id_accused'] == Perm::$shopId)
		{
			$data['complain_state'] = Complain_BaseModel::COMPLAIN_HANDLE;

			$flag = $this->complainBaseModel->editBase($complain_id, $data);

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
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

	public function closeComplain()
	{
		$complain_id = request_int("complain_id");

		$complain = $this->complainBaseModel->getOne($complain_id);


		if ($complain['user_id_accused'] == Perm::$shopId)
		{
			$data['complain_state'] = Complain_BaseModel::COMPLAIN_FINISH;

			$flag = $this->complainBaseModel->editBase($complain_id, $data);

			if ($flag)
			{
				$status = 200;
				$msg    = __('success');
			}
			else
			{
				$status = 250;
				$msg    = __('failure111');
			}
		}
		else
		{
			$status = 250;
			$msg    = __('failure222');
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

	public function getComplainTalk()
	{
		$complain_id = request_int('complain_id');
		//对话信息
		$talk_cond_row['complain_id'] = $complain_id;
		$Complain_TalkModel           = new Complain_TalkModel();
		$data                         = $Complain_TalkModel->getComplainTalk($talk_cond_row, array('talk_id' => 'DESC'));

		$this->data->addBody(-140, $data);
	}

	public function publishComplainTalk()
	{
		$complain_id   = request_int('complain_id');
		$complain_talk = request_string('complain_talk');
		$user_id       = Perm::$userId;
		$user_account  = Perm::$row['user_account'];
		$member_type   = 2;

		$matche_row = array();
		//有违禁词
		if (Text_Filter::checkBanned($complain_talk, $matche_row))
		{
			$data   = array();
			$msg    = __('failure');
			$status = 250;
			$this->data->addBody(-140, array(), $msg, $status);
			return false;
		}

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

}

?>
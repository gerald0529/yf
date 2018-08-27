<?php

if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Buyer_Service_ComplainCtl extends Buyer_Controller
{

	public $complainBaseModel    = null;
	public $complainGoodsModel   = null;
	public $orderBaseModel       = null;
	public $orderGoodsModel      = null;
	public $complainSubjectModel = null;
	public $shopBaseModel        = null;

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
		$this->complainBaseModel    = new Complain_BaseModel();
		$this->complainGoodsModel   = new Complain_GoodsModel();
		$this->orderBaseModel       = new Order_BaseModel();
		$this->orderGoodsModel      = new Order_GoodsModel();
		$this->complainSubjectModel = new Complain_SubjectModel();
		$this->shopBaseModel        = new Shop_BaseModel();
		$this->shopCompanyModel        = new Shop_CompanyModel();
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
		elseif ($act == "add")
		{
			$data = $this->add();
			if ($data)
			{
				$this->view->setMet('add');
			}
			else
			{
				$this->view->setMet('error');
			}
			$d = $data;
		}
		else
		{
			$Yf_Page                     = new Yf_Page();
			$Yf_Page->listRows           = 10;
			$rows                        = $Yf_Page->listRows;
			$offset                      = request_int('firstRow', 0);
			$page                        = ceil_r($offset / $rows);
			$state                       = request_int("status");
			$cond_row['user_id_accuser'] = Perm::$userId;         //店铺ID
			if ($state)
			{
				$cond_row['complain_state'] = $state;
			}
			$data = $this->complainBaseModel->getBaseList($cond_row, array('complain_datetime' => 'DESC'), $page, $rows);

			$complain_ids   = array_column($data['items'], 'complain_id');
			$complain_goods = array();
			if (!empty($complain_ids))
			{
				$cond_good_row['complain_id:in'] = $complain_ids;
				$complain_goods                  = $this->complainGoodsModel->getByWhere($cond_good_row);
			}

			$goods = array();
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
			$data['state']      = $state;
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
		$cond_row['user_id_accuser'] = Perm::$userId;

		$data = $this->complainBaseModel->getComplainBase($cond_row);

		$data['good']       = $this->complainGoodsModel->getOneByWhere(array("complain_id" => $complain_id));
		$data['id']         = $complain_id;
		$data['order']      = $this->orderBaseModel->getOneByWhere(array('order_id' => $data['good']['order_id']));
		$data['ordergoods'] = $this->orderGoodsModel->getOneByWhere(array('order_goods_id' => $data['good']['order_goods_id']));
		$data['shop']       = $this->shopBaseModel->getOne($data['user_id_accused']);
		$shop_company = $this->shopCompanyModel->getOne($data['user_id_accused']);
		$data['shop']['shop_company_address'] = $shop_company['shop_company_address'];

		return $data;
	}


	public function cancelComplain()
	{
		$complain_id   = request_int("id");
		$complain_data = $this->complainBaseModel->getOne($complain_id);

		if ($complain_data['user_id_accuser'] == Perm::$userId)
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

		if ($complain['user_id_accuser'] == Perm::$userId)
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

		if ($complain['user_id_accuser'] == Perm::$userId)
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

		if ($complain['user_id_accuser'] == Perm::$userId)
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
		$member_type   = 1;


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

	public function add()
	{
		$cond_row['complain_subject_state'] = Complain_SubjectModel::SUBJECT_USE;
		$data['subject']                    = $this->complainSubjectModel->getByWhere($cond_row);
		$data['goods_id']                   = request_int("gid");
		$complain_goods                     = $this->complainGoodsModel->getByWhere(array("order_goods_id" => $data['goods_id']));
		$complain_ids                       = array_column($complain_goods, "complain_id");
		$complain                           = $this->complainBaseModel->getByWhere(array(
																					   "complain_state:!=" => Complain_BaseModel::COMPLAIN_FINISH,
																					   "complain_id:in" => $complain_ids
																				   ));
		if (empty($complain))
		{
			return $data;
		}
		else
		{
			return 0;
		}

	}

	public function addComplain()
	{
		$goods_id                          = request_int("goods_id");
		$complain_subject_id               = request_int("complain_subject_id");
		$complain_content                  = request_string("complain_content");
		$subject                           = $this->complainSubjectModel->getOne($complain_subject_id);
		$goods                             = $this->orderGoodsModel->getOne($goods_id);
		$order                             = $this->orderBaseModel->getOne($goods['order_id']);
		$field['order_id']                 = $goods['order_id'];
		$field['user_id_accuser']          = Perm::$userId;
		$field['user_account_accuser']     = Perm::$row['user_account'];
		$field['user_id_accused']          = $order['shop_id'];
		$field['user_account_accused']     = $order['shop_name'];
		$field['complain_subject_content'] = $subject['complain_subject_content'];
		$field['complain_subject_id']      = $complain_subject_id;
		$field['complain_content']         = $complain_content;
		$field['complain_pic']             = implode(",", request_row("complain_pic"));
		$field['complain_datetime']        = get_date_time();
		$field['complain_subject_id']      = $complain_subject_id;

		$matche_row = array();
		//有违禁词
		if (Text_Filter::checkBanned($complain_content, $matche_row))
		{
			$data   = array();
			$msg    = __('failure');
			$status = 250;
			$this->data->addBody(-140, array(), $msg, $status);
			return false;
		}

		$complain_goods = $this->complainGoodsModel->getByWhere(array("order_goods_id" => $goods_id));
		$complain_ids   = array_column($complain_goods, "complain_id");
		$complain       = $this->complainBaseModel->getByWhere(array(
																   "complain_state:!=" => Complain_BaseModel::COMPLAIN_FINISH,
																   "complain_id:in" => $complain_ids
															   ));

		if (empty($complain))
		{
			if ($order['buyer_user_id'] == Perm::$userId)
			{
				$rs_row = array();
				$this->complainBaseModel->sql->startTransactionDb();

				$add_flag = $complain_id = $this->complainBaseModel->addBase($field, true);
				check_rs($add_flag, $rs_row);
				$field2['complain_id']      = $complain_id;
				$field2['goods_id']         = $goods['goods_id'];
				$field2['goods_name']       = $goods['goods_name'];
				$field2['goods_price']      = $goods['goods_price'];
				$field2['goods_num']        = $goods['order_goods_num'];
				$field2['goods_image']      = $goods['goods_image'];
				$field2['complain_message'] = $complain_content;
				$field2['order_goods_id']   = $goods_id;
				$field2['order_goods_type'] = 1;
				$field2['order_id']         = $goods['order_id'];

				$add_flag = $this->complainGoodsModel->addGoods($field2);
				check_rs($add_flag, $rs_row);

				$shop              = $this->shopBaseModel->getOne($order['shop_id']);
				$message_user_id   = $shop['user_id'];
				$code              = "Complaints_of_goods";
				$message_user_name = $shop['user_name'];
				$order_id          = $goods['order_id'];
				$shop_name         = $order['shop_name'];
				$message           = new MessageModel();
				$message->sendMessage($code, $message_user_id, $message_user_name, $order_id, $shop_name, 1, 3);

				$flag = is_ok($rs_row);
				
				if ($flag && $this->complainBaseModel->sql->commitDb())
				{
					$msg    = __('success');
					$status = 200;
				}
				else
				{
					$this->complainBaseModel->sql->rollBackDb();
					$msg    = __('failure');
					$status = 250;
				}
			}
			else
			{
				$msg    = __('failure');
				$status = 250;
			}
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function addPic()
	{
		$complain_id           = request_int("complain_id");
		$field['complain_pic'] = request_string("complain_pic");
		$complain              = $this->complainBaseModel->getOne($complain_id);

		if ($complain['user_id_accuser'] == Perm::$userId)
		{
			$flag = $this->complainBaseModel->editBase($complain_id, $field);
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
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);
	}
}

?>
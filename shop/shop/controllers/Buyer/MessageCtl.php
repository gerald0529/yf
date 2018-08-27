<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Buyer_MessageCtl extends Buyer_Controller
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
		
		$this->messageModel         = new MessageModel();
		$this->userMessageModel     = new User_MessageModel();
		$this->userFriendModel      = new User_FriendModel();
		$this->userInfoModel        = new User_InfoModel();
		$this->messageTemplateModel = new Message_TemplateModel();
		$this->messageSettingModel  = new Message_SettingModel();
		$this->articleBaseModel     = new Article_BaseModel();
	}

	/**
	 * 系统消息页面
	 *
	 * @access public
	 */
	public function message()
	{
		$remind_cat = array(
			"1" => __('订单信息'),
			"3" => __('账户信息'),
			"4" => __('其他')
		);
		
		$type = request_int('type');
		$op   = request_string('op');
		
		if ($op == 'receive')//收到消息
		{
			
			$Yf_Page           = new Yf_Page();
			$Yf_Page->listRows = request_int('listRows')?request_int('listRows'):10;
			$rows              = $Yf_Page->listRows;
			$offset            = request_int('firstRow', 0);
			$page              = ceil_r($offset / $rows);

			$order_row                            = array();
			$order_row['user_message_receive_id'] = Perm::$userId;
			$order_row['message_receive_status'] = MessageModel::MESSAGE_SHOW;

			$data = $this->userMessageModel->getMessageList($order_row, array(
				'message_islook' => 'ASC',
				'user_message_time' => 'DESC'
			), $page, $rows);

			foreach ($data['items'] as $k => $v)
			{
				$order_row                            = array();
				$order_row['user_message_pid']        = $v['user_message_id'];
				$order_row['user_message_receive_id'] = Perm::$userId;
				$order_row['message_receive_status'] = MessageModel::MESSAGE_SHOW;
				$order_row['message_islook']          = 0;

				$this->Message                = $this->userMessageModel->getCount($order_row);
				$data['items'][$k]['receive'] = $this->Message;
			}

			$Yf_Page->totalRows = $data['totalsize'];
			$page_nav           = $Yf_Page->prompt();

			$this->view->setMet('userMessage');
		}
		elseif ($op == 'send')//发送的消息
		{
			$Yf_Page           = new Yf_Page();
			$Yf_Page->listRows = request_int('listRows')?request_int('listRows'):10;
			$rows              = $Yf_Page->listRows;
			$offset            = request_int('firstRow', 0);
			$page              = ceil_r($offset / $rows);
			
			$order_row                         = array();
			$order_row['user_message_send_id'] = Perm::$userId;
			$order_row['message_send_status'] = MessageModel::MESSAGE_SHOW;

			$data = $this->userMessageModel->getMessageList($order_row, array('user_message_time' => 'DESC'), $page, $rows);
			
			$Yf_Page->totalRows = $data['totalsize'];
			$page_nav           = $Yf_Page->prompt();
			
			$this->view->setMet('userMessage');
			
		}
		elseif ($op == 'detail')//查看消息
		{
			$order_row = array();
			
			$order_row['user_message_id'] = request_int("id");
			
			$de = $this->userMessageModel->getMessageDetail($order_row);
			if ($de['user_message_pid'] != 0)
			{
				$order_row                    = array();
				$user_message_id              = $de['user_message_pid'];
				$order_row['user_message_id'] = $user_message_id;
			}
			
			$data = $this->detail($order_row);

			$this->view->setMet('detail');
			
		}
		elseif ($op == 'messageAnnouncement')//系统公告
		{
			$Yf_Page           = new Yf_Page();
			$Yf_Page->listRows = request_int('listRows')?request_int('listRows'):10;
			$rows              = $Yf_Page->listRows;
			$offset            = request_int('firstRow', 0);
			$page              = ceil_r($offset / $rows);
			
			$user_id           = Perm::$userId;
			
			$user = $this->userInfoModel->getOne($user_id);
			
			$user_am = $user['user_am'];
			$am_row = array();
			if($user_am){
				$am_row	= explode(",",$user_am);
			}

			$order_row                   = array();
			$order_row['article_type']   = 1;
			$order_row['article_status'] = 1;
			
			$data = $this->articleBaseModel->getBaseAllList($order_row, array('article_add_time' => 'DESC'), $page, $rows);
			if($data['items'])
			{
				foreach($data['items'] as $k=>$v)
				{
					if(in_array($v['article_id'],$am_row))
					{
						$data['items'][$k]['article_islook'] = 1;
					}
				}
			}
			$Yf_Page->totalRows = $data['totalsize'];
			$page_nav           = $Yf_Page->prompt();
            fb(12);
            fb($this->countMessage['article']);
			$this->view->setMet('messageAnnouncement');
		}
		elseif ($op == 'messageManage')//接收设置
		{
			$user_id             = Perm::$userId;
			$cond_row            = array();
			$cond_row['user_id'] = $user_id;

			$re  = $this->messageSettingModel->getSettingDetail($cond_row);
			$all = array();
			if ($re)
			{
				$all = explode(',', $re['message_template_all']);
			}
			$order_row         = array();
			$order_row['type'] = 1;
			$name = ['预定订单尾款支付提醒'];
			$order_row['name:NOT IN'] = $name;
			$data = $this->messageTemplateModel->getTemplateList($order_row);
			
			$this->view->setMet('messageManage');
		}
		elseif ($op == 'sendMessage')//发送站内信
		{
			$userid = request_int('id');
			$user   = array();
			if ($userid)
			{
				$user_row['user_id'] = $userid;
				$user                = $this->userInfoModel->getUserInfo($user_row);
			}

			$user_id           = Perm::$userId;
			$Yf_Page           = new Yf_Page();
			$Yf_Page->listRows = request_int('listRows')?request_int('listRows'):30;
			$rows              = $Yf_Page->listRows;
			$offset            = request_int('firstRow', 0);
			$page              = ceil_r($offset / $rows);
			
			$cond_row            = array();
			$cond_row['user_id'] = $user_id;
			
			$data = $this->userFriendModel->getFriendList($cond_row, array('friend_addtime' => 'DESC'), $page, $rows);

			$Yf_Page->totalRows = $data['totalsize'];
			$page_nav           = $Yf_Page->prompt();
				

			$this->view->setMet('sendMessage');
		}
		elseif ($op == 'get_user_list')//
		{
			//临时测试, 获取用户列表
			$order_row                            = array();
			$order_row['user_message_receive_id'] = Perm::$userId;

			$data = $this->userMessageModel->getMessageList($order_row, array(
				'message_islook' => 'ASC',
				'user_message_time' => 'DESC'
			));

			$user_message_send_id_row = array_column($data['items'], 'user_message_send_id');

			$User_InfoModel = new User_InfoModel();
			$user_info_rows = $User_InfoModel->getInfo($user_message_send_id_row);

			foreach ($data['items'] as $item)
			{
				if (!isset($user_info_rows[$item['user_message_send_id']]['msg']))
				{
					$user_info_rows[$item['user_message_send_id']]['msg'] = array(
						'message_islook'=> $item['message_islook'],
						'message_title'=>$item['user_message_content'],
						'message_create_time'=>$item['user_message_time']
					);
				}
			}

			$data['user'] = $user_info_rows;

		}
		elseif ($op == 'get_chat_msg')//
		{
			$chat_user_id   = request_string('user_id');

			$order_row                            = array();
			$order_row['user_message_receive_id'] = Perm::$userId;
			$order_row['user_message_send_id'] = $chat_user_id;

			$data = $this->userMessageModel->getMessageList($order_row, array(
				'message_islook' => 'ASC',
				'user_message_time' => 'DESC'
			));

			$User_InfoModel = new User_InfoModel();
			$user_info_rows = $User_InfoModel->getInfo($chat_user_id);

			$data['user'] = $user_info_rows;

		}
		else//系统消息
		{
			$Yf_Page           = new Yf_Page();
			$Yf_Page->listRows = request_int('listRows')?request_int('listRows'):8;
			$rows              = $Yf_Page->listRows;
			$offset            = request_int('firstRow', 0);
			$page              = ceil_r($offset / $rows);
			
			$cond_row                    = array();
			$cond_row['message_user_id'] = Perm::$userId;
			$cond_row['message_mold']    = 0;
			$cond_row['message_isdelete']    = 0;
			
			if ($type)
			{
				$cond_row['message_type'] = $type;
			}

			$data = $this->messageModel->getMessageList($cond_row, array(
				'message_islook' => 'ASC',
				'message_create_time' => 'DESC'
			), $page, $rows);
			
			$Yf_Page->totalRows = $data['totalsize'];
			$page_nav           = $Yf_Page->prompt();
			
			
		}

		if ('json' == $this->typ)
		{
			//发送小心用户信息
			if ('wap' == $op)
			{

			}

			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}
	}

	/**
	 * 临时测试
	 *
	 * @access public
	 */
	public function getNodeInfo()
	{
		$from_user_id = request_int('u_id');

		$User_InfoModel = new User_InfoModel();
		$user_info_row = $User_InfoModel->getOne($from_user_id);
		$member_info_row = $User_InfoModel->getOne(Perm::$userId);

		$Shop_BaseModel = new Shop_BaseModel();
		$shop_base = $Shop_BaseModel->getByWhere(array('user_id' => $member_info_row['user_id']));
		if (!empty($shop_base)) {
			$shop_base = pos($shop_base);
			$user_info_row['store_name'] = $shop_base['shop_name'];
		}

		$data['node_chat'] = true;
		$data['node_site_url'] = "http://b2b2c.bbc-builder.com:8091";
		$data['resource_site_url'] = "http://wap.bbc-builder.com";
		$data['userInfo'] = $user_info_row;
		$data['member_info'] = $member_info_row;

		$tr =  '
		{
			"code": 200,
			"data": {
				"node_chat": true,
				"node_site_url":
				"resource_site_url":
				"member_info": {
					"member_id": "1",
					"member_name": "bbc-builder",
					"member_avatar": "http://b2b2c.bbc-builder.com/tesa/data/upload/shop/common/default_user_portrait.gif",
					"store_id": "1",
					"store_name": "平台自营",
					"store_avatar": "http://b2b2c.bbc-builder.com/tesa/data/upload/shop/common/default_store_avatar.png",
					"grade_id": "0",
					"seller_name": "bbc-builder_seller"
				},
				"userInfo": {
					"member_id": "1",
					"member_name": "bbc-builder",
					"member_avatar": "http://b2b2c.bbc-builder.com/tesa/data/upload/shop/common/default_user_portrait.gif",
					"store_id": "1",
					"store_name": "平台自营",
					"store_avatar": "http://b2b2c.bbc-builder.com/tesa/data/upload/shop/common/default_store_avatar.png",
					"grade_id": "0",
					"seller_name": "bbc-builder_seller"
				}
			}
		}';

		$this->data->addBody(-140, $data);
	}


	/**
	 * 删除选择系统消息
	 *
	 * @access public
	 */
	public function delAllMessage()
	{
		$message_id_list = request_row("id");

		//开启事物
		$rs_row = array();
		$this->messageModel->sql->startTransactionDb();

		//查找需要删除的消息信息。判断当前操作用户是接受者还是发送者
		$message_data = $this->messageModel->getOne($message_id_list);

		//修改需要删除的信息的显示状态
		// $edit_row['message_send_status'] = MessageModel::MESSAGE_HIDE;
		$edit_row['message_isdelete'] = MessageModel::MESSAGE_HIDE;
		$flag = $this->messageModel->editMessage($message_id_list,$edit_row);

		check_rs($flag, $rs_row);

		$flag = is_ok($rs_row);

		if ($flag !== false && $this->messageModel->sql->commitDb())
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$this->messageModel->sql->rollBackDb();
			$status = 250;
			$msg    = __('failure');
		}

		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);

	}

	/**
	 * 消息详情页面
	 *
	 * @access public
	 */
	public function detail($order_row = array())
	{

		$data = $this->userMessageModel->getMessageDetail($order_row);
		
		$row['user_message_pid'] = $data['user_message_id'];
		
		$list         = $this->userMessageModel->getMessageList($row, array('user_message_time' => 'desc'));
		$data['list'] = $list['items'];
		
		return $data;
	}

	/**
	 * 回复消息
	 *
	 * @access public
	 */
	public function addDetail()
	{

		$user_message_id              = request_row("user_message_id");
		$user_message_content         = request_row("user_message_content");
		$order_row['user_message_id'] = $user_message_id;
		$user_message_time            = get_date_time();
		$matche_row                   = array();
		//有违禁词
		if (Text_Filter::checkBanned($user_message_content, $matche_row))
		{
			$data   = array();
			$msg    = __('failure');
			$status = 230;
			$this->data->addBody(-140, array(), $msg, $status);
			return false;
		}
		
		$de = $this->userMessageModel->getMessageDetail($order_row);

		if (!$de)
		{
			$status = 240;
			$msg    = __('failure');
		}
		else
		{
			if ($de['user_message_pid'] != 0)
			{
				$user_message_id = $de['user_message_pid'];
			}
			
			$add_row['user_message_send_id']    = $de['user_message_receive_id'];
			$add_row['user_message_send']       = $de['user_message_receive'];
			$add_row['user_message_receive']    = $de['user_message_send'];
			$add_row['user_message_receive_id'] = $de['user_message_send_id'];
			$add_row['user_message_content']    = $user_message_content;
			$add_row['user_message_pid']        = $user_message_id;
			$add_row['user_message_time']       = $user_message_time;
			
			$flag = $this->userMessageModel->addMessage($add_row);
			
			if ($flag !== false)
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
		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 发送消息
	 *
	 * @access public
	 */
	public function addMessageDetail()
	{
		$user_id              = Perm::$userId;
		$user_message_receive = request_string("user_message_receive");
		$user_message_content = request_string("user_message_content");
		$user_message_time    = get_date_time();
		$matche_row = array();
		//有违禁词
		if (Text_Filter::checkBanned($user_message_content, $matche_row))
		{
			$data   = array();
			$msg    = __('failure');
			$status = 230;
			$this->data->addBody(-140, array(), $msg, $status);
			return false;
		}
		
		$de = $this->userInfoModel->getOne($user_id);
		
		if (!$de)
		{
			$status = 240;
			$msg    = __('failure');
		}
		else
		{
			$add_row['user_message_send_id'] = $user_id;
			$add_row['user_message_send']    = $de['user_name'];
			$add_row['user_message_content'] = $user_message_content;
			$add_row['user_message_pid']     = 0;
			$add_row['user_message_time']    = $user_message_time;
			
			$user_message_receive = trim($user_message_receive, ',');
			$send_id_row          = explode(',', $user_message_receive);
			foreach($send_id_row as $k=>$v){
				if($de['user_name']== $v){
					unset($send_id_row[$k]);
				}
			}
			if($send_id_row){
				//开启事物
				$rs_row = array();
				$this->userInfoModel->sql->startTransactionDb();
				
				foreach ($send_id_row as $key => $val)
				{
					
					$user_name = $val;
					
					$cond_row = array();
					$cond_row['user_name'] = $val;
					
					$message_receive = $this->userInfoModel->getUserInfo($cond_row);
					
					if ($user_name != $de['user_name'] && $message_receive )
					{
						$cond_row['user_name'] = $user_name;
						$re                    = $this->userInfoModel->getUserInfo($cond_row);

						$add_row['user_message_receive_id'] = $re['user_id'];
						$add_row['user_message_receive']    = $user_name;
						
						$flag = $this->userMessageModel->addMessage($add_row);
						check_rs($flag, $rs_row);
						
					}
	
				}
				$flag = is_ok($rs_row);
				if ($flag !== false && $this->userInfoModel->sql->commitDb())
				{
					$status = 200;
					$msg    = __('success');
				}
				else
				{
					$this->userInfoModel->sql->rollBackDb();
					$status = 250;
					$msg    = __('failure');
				}
				
			}else{
				$status = 260;
				$msg    = __('failure');
			}
		}
		$data = array();
		//排除非商城用户
		foreach($send_id_row as $k=>$v){
			$cond_row['user_name'] = $v;
			$send_shop_user = $this->userInfoModel->getOneByWhere($cond_row);
			if(empty($send_shop_user))
			{
				$status = 220;
				$msg    = __('failure');
			}
		}
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除用户消息
	 *
	 * @access public
	 */
	public function delUserMessage()
	{
		
		$user_message_id = request_int("id");

		//查找需要删除的消息信息。判断当前操作用户是接受者还是发送者
		$message_data = $this->userMessageModel->getOne($user_message_id);

		$edit_row = array();

		if($message_data['user_message_receive_id'] == Perm::$userId)
		{
			//修改需要删除的信息的显示状态
			$edit_row['message_receive_status'] = MessageModel::MESSAGE_HIDE;
		}

		if($message_data['user_message_send_id'] == Perm::$userId)
		{
			//修改需要删除的信息的显示状态
			$edit_row['message_send_status'] = MessageModel::MESSAGE_HIDE;
		}

		$flag = $this->userMessageModel->editMessage($user_message_id,$edit_row);
		
		if ($flag !== false)
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
	 * 编辑用户接收设置
	 *
	 * @access public
	 */
	public function editManage()
	{
		$user_id          = Perm::$userId;
		$user_message_id  = request_row("id");
		$user_message_all = '';
		foreach ($user_message_id as $k => $v)
		{
			$user_message_all .= ',' . $v;
		}
		$user_message_all = trim($user_message_all, ',');
		
		$cond_row            = array();
		$cond_row['user_id'] = $user_id;

		$re = $this->messageSettingModel->getSettingDetail($cond_row);
		
		$cond_row['message_template_all'] = $user_message_all;
		$cond_row['setting_time']         = get_date_time();
		
		//开启事物
		$rs_row = array();
		$this->messageSettingModel->sql->startTransactionDb();
		
		if ($re)
		{
			$setting_id = $re['setting_id'];
			$flag       = $this->messageSettingModel->editSetting($setting_id, $cond_row);
			check_rs($flag, $rs_row);
		}
		else
		{
			
			$flag = $this->messageSettingModel->addSetting($cond_row);
			check_rs($flag, $rs_row);
		}
		$flag = is_ok($rs_row);
		if ($flag !== false && $this->messageSettingModel->sql->commitDb())
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$this->messageSettingModel->sql->rollBackDb();
			$status = 250;
			$msg    = __('failure');
		}

		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 查看用户公告
	 *
	 * @access public
	 */
	public function changeAnnouncement()
	{
		
		$article_id    	= request_int("id");
		$user_id        = Perm::$userId;
			
		$user = $this->userInfoModel->getOne($user_id);
		
		$user_am = $user['user_am'];
		
		$am_row = '';
		
		if($user_am){
			$row = explode(",",$user_am);
			
			if(in_array($article_id,$row))
			{
				$user_am = $user_am;
			}else{
				$user_am = $user_am.",".$article_id;
			}
			
			$am_row	= $user_am;
		}else{
			
			$am_row	= $article_id ;
		}
		$cond_row['user_am'] = $am_row;

		$flag = $this->userInfoModel->editInfo($user_id, $cond_row);
		
		if ($flag !== false)
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
	 * 查看用户信息
	 *
	 * @access public
	 */
	public function changeUserMessage()
	{
		
		$user_message_id            = request_int("id");
		$cond_row['message_islook'] = 1;

		$flag = $this->userMessageModel->editMessage($user_message_id, $cond_row);
		
		if ($flag !== false)
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
	 * 查看用户系统信息
	 *
	 * @access public
	 */
	public function changeMessage()
	{
		
		$message_id                 = request_int("id");
		$cond_row['message_islook'] = 1;

		$flag = $this->messageModel->editMessage($message_id, $cond_row);
		
		if ($flag !== false)
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
	 * 查看用户新消息数量
	 *
	 * @access public
	 */
	public function getNewMessageNum()
	{
		//会员未读消息
		$order_row                            = array();
		$order_row['user_message_receive_id'] = Perm::$userId;;
		$order_row['message_islook']          = 0;

		$data['count'] = $this->userMessageModel->getCount($order_row);

		$this->data->addBody(-140, $data);
	}
}

?>
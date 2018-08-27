<?php if (!defined('ROOT_PATH')){exit('No Permission');}

/**
 * Api接口
 *
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class ImApiCtl extends Yf_AppController
{
	private $rest = null;
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
	}


	public function initAccount()
	{
		$im_config_row = Yf_Registry::get('im_config_row');

		$account_sid = $im_config_row['account_sid'];
		$account_token = $im_config_row['account_token'];
		$app_id = $im_config_row['app_id'];
		$server_ip = $im_config_row['server_ip'];
		$server_port = $im_config_row['server_port'];
		$soft_version = $im_config_row['soft_version'];

		// 初始化REST SDK
		$this->rest = new Yuntongxun_Rest($server_ip, $server_port, $soft_version);
		$this->rest->setAccount($account_sid, $account_token);
		$this->rest->setAppId($app_id);
	}


	public function initSubAccount()
	{
		$im_config_row = Yf_Registry::get('im_config_row');

		$account_sid = $im_config_row['account_sid'];
		$account_token = $im_config_row['account_token'];
		$app_id = $im_config_row['app_id'];
		$server_ip = $im_config_row['server_ip'];
		$server_port = $im_config_row['server_port'];
		$soft_version = $im_config_row['soft_version'];


		$sub_account_row = $im_config_row['sub_account'][0];

		$sub_account_sid = $sub_account_row['sub_account_sid'];
		$sub_account_token = $sub_account_row['sub_account_token'];
		$voip_account = $sub_account_row['voip_account'];
		$voip_password = $sub_account_row['voip_password'];

		// 初始化REST SDK
		$this->rest = new Yuntongxun_Rest($server_ip, $server_port, $soft_version);

		$this->rest->setSubAccount($sub_account_sid,$sub_account_token, $voip_account, $voip_password);
		$this->rest->setAppId($app_id);

	}

	/**
	 * 获取所有公共群组
	 *
	 * @access public
	 */
	public function getPublicGroups()
	{
		$this->initSubAccount();

		$result = $this->rest->getPublicGroups();

		fb($result);
		if ($result == NULL)
		{
			echo "result error!";
			return;
		}

		if ($result->statusCode != 0)
		{
			echo "error code :" . $result->statusCode . "<br>";
			echo "error msg :" . $result->statusMsg . "<br>";
			//TODO 添加错误处理逻辑
		}
		else
		{
			echo "获取成功!<br/>";
			// 获取返回信息
			//TODO 添加成功处理逻辑
		}
	}

	/**
	 * Constructor
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */
	public function searchPublicGroups()
	{
		$this->initSubAccount();

		$result = $this->rest->searchPublicGroups();

		fb($result);
		if ($result == NULL)
		{
			echo "result error!";
			return;
		}

		if ($result->statusCode != 0)
		{
			echo "error code :" . $result->statusCode . "<br>";
			echo "error msg :" . $result->statusMsg . "<br>";
			//TODO 添加错误处理逻辑
		}
		else
		{
			echo "push msg success!<br/>";
			// 获取返回信息
			$smsmessage = $result->TemplateSMS;
			//TODO 添加成功处理逻辑
		}
	}



	/**
	 * 取得群组成员user_name
	 *
	 * @access public
	 */
	public function getGroupMember()
	{
		$this->initSubAccount();

		$group_bind_id = request_string('group_bind_id');
		$result = $this->rest->queryMember($group_bind_id);

		fb($result);
		if ($result == NULL)
		{
			echo "result error!";
			return;
		}

		if ($result->statusCode != 0)
		{
			echo "error code :" . $result->statusCode . "<br>";
			echo "error msg :" . $result->statusMsg . "<br>";
			//TODO 添加错误处理逻辑
		}
		else
		{
			echo "get group members success!<br/>";
			echo encode_json($result->members);
			//TODO 添加成功处理逻辑
		}
	}


	/**
	 * 消息推送
	 */
	public function pushMsg()
	{
		$this->initAccount();

		$im_config_row = Yf_Registry::get('im_config_row');

		$account_sid = $im_config_row['account_sid'];
		$account_token = $im_config_row['account_token'];
		$app_id = $im_config_row['app_id'];
		$server_ip = $im_config_row['server_ip'];
		$server_port = $im_config_row['server_port'];
		$soft_version = $im_config_row['soft_version'];

		$account_system = request_string('account_system', $im_config_row['account_system']) ;

		/*
		pushType	int	必选	推送类型，1：个人，2：群组，默认为1
		appId	String	必选	应用Id
		sender	String	必选	发送者帐号
		receiver	String	必选	接收者帐号，如果是个人，最大上限100人/次，如果是群组，仅支持1个；如果需要跨应用给个人发送信息，需要在接收者帐号前加appid和#。例如：appid=1，接收者帐号=a，则需要拼为1#a。由于群组ID为唯一ID，因此跨应用给群组发送消息无需增加appid和#。
		msgType	int	必选	消息类型，1：文本消息，2：语音消息，3：视频消息，4：图片消息，5：位置消息，6：文件
		msgContent	String	可选	文本内容，最大长度2048字节，文本和附件二选一，不能都为空
		msgDomain	String	可选	扩展字段
		msgFileName	String	可选	文件名，最大长度128字节
		msgFileUrl	String	可选	文件绝对路径
		*/
		$receiver = request_string('receiver', '');
		if(strpos($receiver,',')!==false){
			$receiver_row = explode(',',  $receiver);	
		}else{
			$receiver_row = [$receiver];
		}
 
		$body_row = array();
		$body_row["pushType"] = request_int('push_type', 1);
		$body_row["appId"] = $app_id;
		$body_row["sender"] = $account_system;
		$body_row["receiver"] = $receiver_row;
		$body_row["msgType"] = request_string('msg_type', '1');;
		$body_row["msgContent"] = request_string('msg_content', '111');;
		$body_row["msgDomain"] = request_string('msgDomain', 'yuntongxun');
		$body_row["msgFileName"] = request_string('msg_file_name', '');
		$body_row["msgFileUrl"] = request_string('msg_file_url', '');
 
		// 发送模板短信 
		$result = $this->rest->pushMsg($body_row);
		
		if ($result == NULL)
		{
	 
			exit(json_encode(['status'=>250,'msg'=>"result error"]));
			return;
		}

		if ($result->statusCode != 0)
		{
		 
			exit(json_encode(['status'=>1,'msg'=>$result->statusMsg,'data'=>$result]));
			//TODO 添加错误处理逻辑
		}
		else
		{
			
			// 获取返回信息
			$smsmessage = $result->TemplateSMS;
			exit(json_encode(['status'=>200,'msg'=>'success']));
			//TODO 添加成功处理逻辑
		}
	}

	/**
	 * 历史记录
	 */
	public function msgRecordsNew()
	{
		$this->initAccount();

		$im_config_row = Yf_Registry::get('im_config_row');

		$app_id = $im_config_row['app_id'];


		$result = $this->rest->msgRecordsNew($app_id, null);
		fb($result);

		if (false !== $result)
		{
			echo "get records success!";

			foreach ($result as $data)
			{
				$this->userMsgModel = new User_MsgModel();
				$msg_log_id = $this->userMsgModel->addMsg($data, true);

				fb($msg_log_id);
			}
		}
	}

	public function genFileSig()
	{
		$im_config_row = Yf_Registry::get('im_config_row');
		$account_sid = $im_config_row['account_sid'];
		$account_pwd = $im_config_row['account_pwd'];

		header('Content-type: application/json');
		$cb = $_REQUEST['cb'];

		$parm         = array();
		$parm['code'] = '000000';
		$parm['sig']  = md5($account_sid . $account_pwd);;

		$rs = sprintf('%s(%s)', $cb, encode_json($parm));
		echo $rs;
		die();
	}

	public function genSig()
	{
		$im_config_row = Yf_Registry::get('im_config_row');

		header('Content-type: application/json');
		$cb = $_REQUEST['cb'];

		$parm         = array();
		$parm['code'] = '000000';

		$app_id = $im_config_row['app_id'];

		$user_name = $_REQUEST['username'];
		$timestamp = $_REQUEST['timestamp'];

		$app_token = $im_config_row['app_token'];

		$parm['sig'] = md5($app_id . $user_name . $timestamp . $app_token);

		$rs = sprintf('%s(%s)', $cb, encode_json($parm));
		echo $rs;
		die();
	}

	public function getUserList()
	{

		$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		$rows = isset($_REQUEST['rows']) ? intval($_REQUEST['rows']) : 20;
		$sort = isset($_REQUEST['sort']) ? intval($_REQUEST['sort']) : 'asc';

		$user_name = isset($_REQUEST['user_name']) ? $_REQUEST['user_name'] : null;

		$User_BaseModel = new User_BaseModel();
		$user_list = $User_BaseModel->getUserBaseList($user_name,$page,$rows,$sort);

		if ($user_list)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}

		$this->data->addBody(-140, $user_list, $msg, $status);
	}

	public function getMsgList()
	{
		$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		$rows = isset($_REQUEST['rows']) ? intval($_REQUEST['rows']) : 20;
		$sort = isset($_REQUEST['sort']) ? intval($_REQUEST['sort']) : 'desc';

		$msg_sender = isset($_REQUEST['msg_sender']) ? $_REQUEST['msg_sender'] : null;
		$msg_receiver = isset($_REQUEST['msg_receiver']) ? $_REQUEST['msg_receiver'] : null;

		$User_MsgModel = new User_MsgModel();
		$msg_list = $User_MsgModel->getMsgList($page,$rows,$sort,$msg_sender,$msg_receiver);

		if ($msg_list)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		fb($msg_list);
		$this->data->addBody(-140, $msg_list, $msg, $status);
	}

	/**
	 * 朋友圈API - 获取动态信息
	 *
	 * @access public
	 */
	public function getSns()
	{
		$page = request_int('page',1);
		$rows = request_int('rows',10);
		//$user_name   = isset($_REQUEST['skey']) ? $_REQUEST['skey'] : null;

		$Sns_BaseModel = new Sns_BaseModel();

		$Sns_CommentModel = new Sns_CommentModel();

		//$data = $Sns_BaseModel->getBaseList($user_name,$page,$rows);
		$data = $Sns_BaseModel->getBaseList($page,$rows);
		$itemsl = $data['items'];

		foreach ($itemsl as $key => $value)
		{
			//内容
			$items[$key]['sns'] = $value;
			$sns_user_name = $value['user_name'];
			if($value['sns_img'])
			{
				$items[$key]['sns']['img'] = explode(',', $value['sns_img']);
			}else
			{
				$items[$key]['sns']['img'] = array();
			}

			//发布动态者信息
			if($sns_user_name)
			{
				$User_InfoModel = new User_InfoModel();
				$user_info_row = $User_InfoModel->getInfo($sns_user_name);
				$user_info_row = array_values($user_info_row);
				$items[$key]['sns_user'] = $user_info_row[0];
			}
			else
			{
				$items[$key]['sns_user'] = array();
			}

			//点赞人数
			$sns_like_user = $value['sns_like_user'];
			$like_user_row = explode(',', $sns_like_user);

			$User_BaseModel = new User_BaseModel();
			$like_user_name = '';
			foreach ($like_user_row as $ke => $val)
			{
				$user_info = array();

				if($val)
				{
					$user_info = $User_BaseModel->getUser($val);

					if($user_info)
					{
						$like_user_name .= $user_info[$val]['user_account'].',';
					}
				}
			}
			$like_user_name =  substr($like_user_name, 0, -1) ;
			$items[$key]['like_user_name'] = $like_user_name;

			//获取评论
			$comment = $Sns_CommentModel->getCommentBySid($value['sns_id']);
			$items[$key]['comment'] = array_values($comment);

		}

		$data['items'] = array_values($items);
		$msg    = 'success';
		$status = 200;
		fb($data);
		$this->data->addBody(-140, $data, $msg, $status);
	}

	//查找动态图片与评论
	public function getSnsInfo()
	{
		$sns_id =  $_REQUEST['sns_id'];

		$Sns_BaseModel = new Sns_BaseModel();
		$sns_info = $Sns_BaseModel->getBase($sns_id);
		$sns_info = array_values($sns_info);
		if($sns_info[0]['sns_img'])
		{
			$sns['img'] = explode(',', $sns_info[0]['sns_img']);
		}else
		{
			$sns['img'] = array();
		}

		$Sns_CommentModel = new Sns_CommentModel();
		$comment = $Sns_CommentModel->getCommentBySid($sns_id);
		$sns['comment'] = array_values($comment);

		$msg    = 'success';
		$status = 200;
		$this->data->addBody(-140, $sns, $msg, $status);
	}

	//删除动态
	public function delSns()
	{
		$sns_id = request_int('sns_id');

		$Sns_BaseModel = new Sns_BaseModel();
		$field['is_del'] = '2';
		$flag = $Sns_BaseModel->editBase($sns_id,$field);

		if($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	//删除评论
	public function delCommect()
	{
		$commect_id = request_int('commect_id');

		$Sns_CommentModel = new Sns_CommentModel();
		$flag = $Sns_CommentModel->removeComment($commect_id);
		if ($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		$date = array();
		$this->data->addBody(-140, $date, $msg, $status);
	}

	public function getImConfig()
	{
		/*$data['im_appId'] = Web_ConfigModel::value('im_appId');
		$data['im_appToken'] = Web_ConfigModel::value('im_appToken');*/
		$ret = Yf_Registry::get('im_config_row');
		$data['im_appId'] = $ret['app_id'];
		$data['im_appToken'] = $ret['app_token'];
		$this->data->addBody(-140, $data);
		if ($jsonp_callback = request_string('jsonp_callback'))
		{
			exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
		}
	}
}

?>

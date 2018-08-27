<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * SNS主页及会员中心模块
 *
 * @category   Game
 * @package    User
 * @author     Cbin <chengbin@live.cn>
 * @copyright  Copyright (c) 2016, Cbin
 * @version    1.0
 * @todo
 */
class UserCtl extends Yf_AppController
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
	 * SNS主页 模板显示
	 * @access public
	 * http://域名/sns/index.php?ctl=Login&met=index
	 */
	public function getName()
	{
		// 业务逻辑
		$uid = request_int('uid');

		$User_BaseModel = new User_BaseModel();
		$getAccount = $User_BaseModel->getAccount($uid);
		$data['name'] = $getAccount[$uid]['user_account'];
		// code
		$this->data->addBody(-140, $data);
		//模板显示
	}

	//发送信息
	public function addMessage()
	{
		// 业务逻辑
		//获取发送者信息
		$id = request_int('user_id');
		$name = request_string('user_name');
		//$id = 10116;
		//$name = 12123344;
		//获取接收者信息
		$uname = request_string('uname');
		$uname = explode(',',$uname);
		$msg_content = request_string('content');
		$msg_domain = request_string('msg_domain');
		$date_created =date('Y-m-d H:i:s.u',time());
		//$msg_content = '你好';
		$msg_len = strlen($msg_content);

		//根据接受者姓名获取id
		$User_BaseModel = new User_BaseModel();

		$fail = array();

		if($msg_content)
		{
			foreach ($uname as $key => $v) {
				$v = trim($v);
				$getUserIdByName = $User_BaseModel->getUserIdByName($v);

				if(count($getUserIdByName)==1&&$id!=''&&$v!=''){
					$uid = $getUserIdByName[0];
					$fild = array('app_id_sender' => $id,
						'msg_sender' => $name,
						'app_id_receiver' => $uid,
						'msg_receiver' => $v,
						'msg_len' => $msg_len,
						'date_created' => $date_created,
						'msg_content' => $msg_content,
						'msg_domain' => $msg_domain
					);
					$User_MsgModel = new User_MsgModel();
					$addMsg = $User_MsgModel->addMsg($fild,true);

				}else{
					$fail[] = $v;
				}
			}
		}


		if(count($fail) == 0){
			$data[0] = '成功';
			$msg   = 'success';
			$status = 200;	
		}else{
			$data[0] = implode(',',$fail);
			$msg    = 'failure';
			$status = 250;
		}
		// code
		$this->data->addBody(-140, $data, $msg, $status);
		//模板显示
	}

	//获得发件箱
	public function getOutbox(){
		$name = request_string('name');
		$page = request_int('page',1);
		$rows = request_int('rows',20);
		//print_r($name);
		$User_MsgModel = new User_MsgModel();
		$getMsgBySender = $User_MsgModel->getMsgBySender($name,$page,$rows);
		$data = array();
		$data['records'] = $getMsgBySender['records'];
		$data['page'] = $getMsgBySender['page'];
		$data['total'] = $getMsgBySender['total'];
		$getMsgBySender = $getMsgBySender['items'];
		$data['box'] = array();
		if (count($getMsgBySender) > 0) {
			foreach ($getMsgBySender as $key => $v) {
			$data['box'][] = $v;
			}
			foreach ($data['box'] as $key => $v) {
				$User_InfoModel = new User_InfoModel();
				$getInfo = $User_InfoModel->getInfo($v['msg_receiver']);
				if(count($getInfo) == 1)
					$data['box'][$key]['info'] = $getInfo[$v['msg_receiver']];
			}
		}

		$this->data->addBody(-140, $data);
	}

	//获得收件箱
	public function getInbox(){
		$name = request_string('name');
		$page = request_int('page',1);
		$rows = request_int('rows',20);

		$User_MsgModel = new User_MsgModel();
		$getMsgByReceiver = $User_MsgModel->getMsgByReceiver($name);
		$data = array();
		$data['records'] = $getMsgByReceiver['records'];
		$data['page'] = $getMsgByReceiver['page'];
		$data['total'] = $getMsgByReceiver['total'];
		$getMsgByReceiver = $getMsgByReceiver['items'];
		$data['box'] = array();
		if (count($getMsgByReceiver) > 0) {
			foreach ($getMsgByReceiver as $key => $v) {
			$data['box'][] = $v;
			}
			foreach ($data['box'] as $key => $v) {
				$User_InfoModel = new User_InfoModel();
				$getInfo = $User_InfoModel->getInfo($v['msg_sender']);
				$data['box'][$key]['info'] = $getInfo[$v['msg_sender']];
			}
		}
		$this->data->addBody(-140, $data);
	}

	//获取信息详情
	public function getBox(){
		$msg_id = request_int('msg_id');
		$box = request_string('box');

		$User_MsgModel = new User_MsgModel();
		$getMsg = $User_MsgModel->getMsg($msg_id);
		$data = $getMsg[$msg_id];

		$User_InfoModel = new User_InfoModel();

		$User_BaseModel = new User_BaseModel();

		if($box == 'in'){
			$getInfo = $User_InfoModel->getInfo($data['msg_sender']);
			$data['info'] = $getInfo[$data['msg_sender']];

			$getUserIdByName = $User_BaseModel->getUserIdByName($data['msg_sender']);
			$data['uid'] = $getUserIdByName[0];
		}

		if($box == 'out'){
			$getInfo = $User_InfoModel->getInfo($data['msg_receiver']);
			$data['info'] = $getInfo[$data['msg_receiver']];

			$getUserIdByName = $User_BaseModel->getUserIdByName($data['msg_receiver']);
			$data['uid'] = $getUserIdByName[0];
		}
		//print_r($data);

		$this->data->addBody(-140, $data);
	}

	//删除邮件
	public function delbox(){
		$msg_id = request_string('msg_id');
		$msg_id = explode(',',$msg_id);
		$User_MsgModel = new User_MsgModel();
		foreach ($msg_id as $key => $v) {
			$v = trim($v);
			$removeMsg = $User_MsgModel->removeMsg($v);
			$data[] = "成功";
		}
		$this->data->addBody(-140, $data);
	}

	public function getLogo(){
		$name = request_string('name');
		$User_Info = new User_Info();
		$getInfo = $User_Info->getInfo($name);
		$data = $getInfo[$name];
		$this->data->addBody(-140, $data);
	}

}
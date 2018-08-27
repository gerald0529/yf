<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * 广场模块
 *
 * @category   Game
 * @package    User
 * @author     Cbin <chengbin@live.cn>
 * @copyright  Copyright (c) 2016, Cbin
 * @version    1.0
 * @todo
 */
class SquareCtl extends Yf_AppController
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
	 * 模板显示
	 * @access public
	 * http://域名/sns/index.php?ctl=Login&met=index
	 */
	public function index()
	{
		// 业务逻辑
		// code

		//模板显示
		include 'login.php';
		include $this->view->getView();
	}

	//广场关注
	public function follow()
	{
		//$user_name = Perm::$row['user_account'];
		//$user_id = Perm::$row['user_id'];

		//$friend_id = request_int('user_id');
		//$friend_account = request_string('user_account');
		$user_name = request_string('user_account');
		$user_id = request_string('user_id');

		$friend_id = request_int('friend_id');
		$friend_account = request_string('friend_account');


		$User_BaseModel = new User_BaseModel();

		$friend_row = array();

		if ($friend_id)
		{
			$friend_rows = $User_BaseModel->getUser($friend_id);
			if ($friend_rows)
			{
				$friend_row = array_pop($friend_rows);
			}

		}
		elseif($friend_account)
		{
			$friend_row = $User_BaseModel->getInfoByName($friend_account);
		}
		fb($friend_row);
		if ($friend_row)
		{
			$User_FriendModel = new User_FriendModel();

			$d = array();
			$d['user_id'] = $user_id;
			$d['friend_id'] = $friend_row['user_id'];
			$rs = $User_FriendModel->addFriend($d,true);
			
			if ($rs)
			{
				
				$User_InfoModel = new User_InfoModel();
				$user_detail_rows = $User_InfoModel->getInfo($friend_row['user_account']);
				$user_detail_row = array_pop($user_detail_rows);
				
				//查找user_friend_is_xin 和 user_friend_xin_limit
				$user_xin_info = $User_FriendModel->getFriend($rs);
				
				$user_detail_row['user_friend_is_xin'] = $user_xin_info[$rs]['user_friend_is_xin'];
				$user_detail_row['user_friend_xin_limit'] = $user_xin_info[$rs]['user_friend_xin_limit'];

				$this->data->addBody(100, $user_detail_row);
			}
			else
			{

				$User_InfoModel = new User_InfoModel();
				$user_detail_rows = $User_InfoModel->getInfo($friend_row['user_account']);
				$user_detail_row = array_pop($user_detail_rows);

				$user_detail_row['user_friend_is_xin'] = 1;

				$this->data->addBody(100, $user_detail_row);
			}
		}
		else
		{
			$this->data->setError('无此用户');
			return false;
		}
	}


	/**
	 * 广场API - 获取广场信息
	 *
	 * @access public
	 */
	public function getSnsList($page=1,$rows=20)
	{
		$page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
		$aaa = empty($_REQUEST['aaa'])?'':$_REQUEST['aaa'];
		$Sns_BaseModel = new Sns_BaseModel();
		if($aaa!=''){
			$getBaseList = $Sns_BaseModel->getBaseList($page,$rows,'asc',$aaa);
		}else{
			$getBaseList = $Sns_BaseModel->getBaseList($page,$rows);
		}
		$data = array();
		$data = array_values($getBaseList);
		$base = $data[4];
		$data[4] = array();
		foreach ($base as $key => $val){
			$v = $val;
			$name = $v['user_name'];
			$User_Info = new User_Info();
			$getInfo = $User_Info->getInfo($name);
			array_push($v,$getInfo);
			
			$data[4][] = $v;
		}
		$msg = 'success';
		$status = 200;
		//fb($data);
		//print_r($data);
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 广场API - 转发
	 *
	 * @access public
	 */
	public function addForward()
	{
		$forward_sns_id = request_int('forward_sns_id');
		$user_id = request_int('user_id');
		$user_name = request_string('user_account');		
		$content = request_string('content');
		$sns_privacy = request_int('sns_privacy',1);
		$time = time();

		$Sns_BaseModel = new Sns_BaseModel();

		//在信息表加入新的信息
		$row = array('user_id'=>$user_id,
					'user_name'=>$user_name,
					'sns_content'=>$content,
					'sns_create_time'=>$time,
					'sns_forward'=>1,
					'sns_privacy'=>$sns_privacy
					);
		$addBase = $Sns_BaseModel->addBase($row,'ture');

		//给被转发的信息转发数+1
		$flag = $Sns_BaseModel->addForwardCount($forward_sns_id);

		//在转发表中插入数据
		$Sns_Forward = new Sns_Forward();
		$forwardrow = array('forward_sns_id' => $forward_sns_id,
							'addtime' => $time,
							'sns_id' => $addBase
							);
		$addForward = $Sns_Forward->addForward($forwardrow,'true');

		//在时间线表中插入数据

		$Sns_TimelineModel = new Sns_TimelineModel();

		$User_BaseModel = new User_BaseModel();

		$User_FriendModel = new User_FriendModel();

		if($sns_privacy == 0) //所有人可见
		{
			//查找出所有用户id
			$User_BaseModel->selectKeyLimit();
			$user_id_row = $User_BaseModel->getUser('*');
			$user_id_row = array_keys($user_id_row);

		}	

		if($sns_privacy == 1) //好友可见
		{
			//查出所有好友id
			$user_friend_rows = array();
			$user_friend_rows = $User_FriendModel->getUserUserIdByFriendId($user_id);
			$user_id_row = array_filter_key('user_id', $user_friend_rows);

			$flagl = in_array($user_id, $user_id_row);

			if(!$flagl)
			{
				array_unshift($user_id_row, $user_id);
			}
			
		}

		if($sns_privacy == 2) //仅自己可见
		{
			$user_id_row[] = $user_id; 
		}

		foreach ($user_id_row as $key => $value) 
		{
			$file = array();
			$file = array(
					'user_id' => $value,
					'msg_id'  => $addBase,
					'action_time' => date('Y-m-d H-i-s',$time)
					);
			$Sns_TimelineModel->addTimeline($file);
		}
		
		//返回结果
		$date = array();
		if($addBase&&$flag&&$addForward){
			$date = array('base' => $addBase,
						'forward' => $addForward,
						'flag' => $flag
						);
			$msg    = 'success';
			$status = 200;
		}else{
			$msg    = 'failure';
			$status = 250;
		}
		print_r($date);die;
		$this->data->addBody(-140, $date, $msg, $status);
	}


	//广场的听众和更新数量
	public function number()
	{
		//获得听众
		$User_Base = new User_Base();
		$getUser = $User_Base->getUser('*');
		
		$data = array();
		$data['audience'] = count($getUser);

		//获取今日更新

		//获取今日0点时间
		$time = date('Y-m-d',time());
		$time = strtotime($time);

		//获取今日24点时间
		$oldtime = $time+3600*24;

		$update = 0;
		$Sns_Base = new Sns_Base();
		$getBase = $Sns_Base->getBase('*');
		foreach ($getBase as $key => $v) {
			if ($v['sns_create_time']>=$time&&$v['sns_create_time']<=$oldtime) {
				$update++;
			}
		}
		$data['update'] = $update;

		$this->data->addBody(-140, $data);
	}



}
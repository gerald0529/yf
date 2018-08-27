<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * 个人主页模块
 *
 * @category   Game
 * @package    User
 * @author     Cbin <chengbin@live.cn>
 * @copyright  Copyright (c) 2016, Cbin
 * @version    1.0
 * @todo
 */
class MainCtl extends Yf_AppController
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
	}

	public function getSns()
	{
		// 业务逻辑
		// code
		$user_id = request_int('user_id',Perm::$row['user_id']);
		$self = $_REQUEST['self'];

		$page = request_int('page',1);
		$rows = request_int('rows',10);
		
		if ($self == 'self') {
			//获取要查看的主页信息
			$Sns_BaseModel = new Sns_BaseModel();
			$datas = $Sns_BaseModel->getBaseSns($user_id,$page,$rows);
		
			foreach ($datas['items'] as $key => $v) {
				$items[$key]['sns'] = $v;

				//获取发布者信息
				$User_Info = new User_Info();
				$getInfo = $User_Info->getInfo($v['user_name']);
				$items[$key]['info'] = $getInfo[$v['user_name']];

				if(!empty($v['sns_img']))
				{
					$items[$key]['sns']['img'] = explode(',', $sns[0]['sns_img']);
				}else
				{
					$items[$key]['sns']['img'] = array();
					if($v['sns_forward'] == 1) {
						
						$Sns_ForwardModel = new Sns_ForwardModel();
						$getForwardrow = $Sns_ForwardModel->getForwardrow($v['sns_id']);
						$getForwardrow = array_pop($getForwardrow);
						
						$row = $Sns_BaseModel->getBase($getForwardrow['forward_sns_id']);
						$row = array_pop($row);
						
						if($row['is_del'] == 0){
							$items[$key]['forward'] = $row;
						}else{
							$items[$key]['forward']['is_del'] = 1;
						}
					}else
					{
						$items[$key]['forward'] = array();
					}
				}
			}
		}elseif($self=='friend'){
			//获取好友动态
			$Sns_TimelineModel = new Sns_TimelineModel();

			$datas = $Sns_TimelineModel->getTimelineList($user_id,$page, $rows);
		
			$items = $datas['items'];
			$Sns_BaseModel = new Sns_BaseModel();

			$Sns_CommentModel = new Sns_CommentModel();

			foreach ($items as $key => $value)
			{
				//内容
				$sns = array_values($Sns_BaseModel->getBase($value['msg_id']));

				//fb($sns);
				if($sns && $sns[0]['is_del'] == 0)
				{
					$items[$key]['sns'] = $sns[0];
					$sns_user_name = $sns[0]['user_name'];

					//获取发布者信息
					$User_Info = new User_Info();
					$getInfo = $User_Info->getInfo($sns[0]['user_name']);
					$items[$key]['info'] = $getInfo[$sns[0]['user_name']];

					if(!empty($sns[0]['sns_img']))
					{
						$items[$key]['sns']['img'] = explode(',', $sns[0]['sns_img']);
					}else
					{
						$items[$key]['sns']['img'] = array();
						if($sns[0]['sns_forward'] == 1) {
							
							$Sns_ForwardModel = new Sns_ForwardModel();
							$getForwardrow = $Sns_ForwardModel->getForwardrow($sns[0]['sns_id']);

							$getForwardrow = array_pop($getForwardrow);
							
							$row = $Sns_BaseModel->getBase($getForwardrow['forward_sns_id']);
							$row = array_pop($row);
							
							if($row['is_del'] == 0){
								$items[$key]['forward'] = $row;
							}else{
								$items[$key]['forward']['is_del'] = 1;
							}
						}else
						{
							$items[$key]['forward'] = array();
						}
						
					}	
					//获取评论
					$comment = $Sns_CommentModel->getCommentBySid($value['msg_id']);
					$items[$key]['comment'] = array_values($comment); 

					
				}
				else
				{
					unset($items[$key]);
				}
			}
		}

		
		$data['items'] = array_values($items);
		$msg    = 'success';
		$status = 200;
		//fb($data);
		$this->data->addBody(-140, $data, $msg, $status);	
	}

	//删除动态
	public function deleteSns()
	{
		$sns_id = request_int('sns_id');
		//将base表is_del变为1；
		$Sns_BaseModel = new Sns_BaseModel();
		$field['is_del'] = '1';
		$flag = $Sns_BaseModel->editBase($sns_id,$field);

		//删除时间轴对应信息
		$Sns_TimelineModel = new Sns_TimelineModel();
		$flag1 = $Sns_TimelineModel->removeTimeBySid($sns_id);

		if($flag&&$flag1)
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

	public function number()
	{
		$user_id = request_int('user_id');
		$User_FriendModel = new User_FriendModel();
		$Myfriend = $User_FriendModel->getUserFriendIdById($user_id);

		$friend = $User_FriendModel->getUserUserIdByFriendId($user_id);

		//主页访问
		$VisitorModel = new User_VisitorModel();
		$master_id = $user_id;
		$visitor =  $VisitorModel ->getVisitorAll($master_id);

		//微博
		$Sns_BaseModel = new Sns_BaseModel();
		$weiboid = $Sns_BaseModel->getBaseByUserId($user_id);

		$weibo = array();
		foreach ($weiboid as $key => $v) {
			$wei = $Sns_BaseModel->getBase($v);
			if ($wei[$v]['is_del'] != 1) {
				$weibo[] = $wei[$v];
			}
		}
		//获取主页用户信息
		$User_Base = new User_Base();
		$getUser = $User_Base->getUser($user_id);
		$user_name = $getUser[$user_id]['user_account'];

		$User_Info = new User_Info();
		$getInfo = $User_Info->getInfo($user_name);

		//判断好友动态是否有更多
		$Sns_TimelineModel = new Sns_TimelineModel();
		$snsrow = $Sns_TimelineModel->getTimeByUid($user_id);
		foreach ($snsrow as $key => $value) {
			$Time = $Sns_TimelineModel->getTimeline($value);
			$snsrowid = $Sns_BaseModel->getBase($Time[$value]['msg_id']);
			if ($snsrowid[$Time[$value]['msg_id']]['is_del'] == 1) {
				unset($snsrow[$key]);
			}
		}
		//print_r($row);die;
		$data['visitor'] = count($visitor);
		$data['snsrow'] = count($snsrow);
		$data['Myfriend'] = count($Myfriend);
		$data['friend'] = count($friend);
		$data['weibo'] = count($weibo);
		$data['info'] = $getInfo[$user_name];
		$msg    = 'success';
		$status = 200;
		//fb($data);
		$this->data->addBody(-140, $data, $msg, $status);

	}

	public function trace()
	{
		// 业务逻辑
		// code

		//模板显示
		// include $this->view->getView();
	}

	public function oneSns()
	{
		$sns_id = request_int('sns_id');

		$Sns_BaseModel = new Sns_BaseModel();

		$datas = $Sns_BaseModel->getBase($sns_id);
		$datas = $datas[$sns_id];
		$data['sns'] = $datas;

		$User_Info = new User_Info();
		$getInfo = $User_Info->getInfo($datas['user_name']);
		$data['info'] = $getInfo[$datas['user_name']];

		if($datas['sns_forward'] == 1) {
			
			$Sns_ForwardModel = new Sns_ForwardModel();
			$getForwardrow = $Sns_ForwardModel->getForwardrow($datas['sns_id']);
			$getForwardrow = array_pop($getForwardrow);
			
			$row = $Sns_BaseModel->getBase($getForwardrow['forward_sns_id']);
			$row = array_pop($row);
			
			if($row['is_del'] == 0){
				$data['forward'] = $row;
			}else{
				$data['forward']['is_del'] = 1;
			}
		}else
		{
			$data['forward'] = array();
		}
		$Sns_CommentModel = new Sns_CommentModel();
		$comment = $Sns_CommentModel->getCommentBySid($sns_id);
		$data['comment'] = array_values($comment); 

		
		$msg    = 'success';
		$status = 200;
		//fb($data);
		$this->data->addBody(-140, $data, $msg, $status);
	}
	public function addVisitor(){
		$master_id = request_int('master_id');
		$visitor_user_account = request_string('visitor_user_account');
		$visitor_user_id = request_int('visitor_user_id');
		$visitor_time = date('Y-m-d');

		if($master_id && $visitor_user_account){
			$VisitorModel = new User_VisitorModel();
			$visitor = $VisitorModel->getVisitor($master_id,$visitor_user_account,$visitor_time);
			if(!$visitor){
				$visitor_row['master_id'] = $master_id;
				$visitor_row['visitor_user_id'] = $visitor_user_id;
				$visitor_row['visitor_user_account'] = $visitor_user_account;
				$visitor_row['visitor_time'] = $visitor_time;

				$data = $VisitorModel ->addVisitor($visitor_row);
			}
			}
		}
	public function getVisitor(){
		$master_id = request_int('master_id');
		$VisitorModel = new User_VisitorModel();
		$InfoModel = new User_InfoModel();
		$row= array();
		$row = $VisitorModel->getVisitorAll($master_id);
		$data = array();
		foreach($row as $k => $v){
			$list = $InfoModel->getFollow($v['visitor_user_account']);
				foreach($list as $key => $val){
					$v['visitor_time'] = substr($v['visitor_time'],5);
					$list[$key]['time'] = $v['visitor_time'];
					$list[$key]['visitor_user_id'] = $v['visitor_user_id'];
					}
				$data[] = $list;
		}
		$msg    = 'success';
		$status = 200;
		$this->data->addBody(-140, $data, $msg, $status);
	}

}
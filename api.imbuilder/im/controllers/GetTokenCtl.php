<?php
	//获取ios的token
	class GetTokenCtl extends Yf_AppController{
		public function token(){
			$token=new API_ServerAPIModel('c9kqb3rdkdinj','J64wAhMLpHd7s');
			$user_name = Perm::$row['user_account'];
			$user_id = Perm::$row['user_id'];
			$logo='http://democn.mall-builder.com/uploadfile/adv/2015/02/05/1423110761.jpg';
			$data=$token->getToken($user_id,$user_name,$logo);
			$data=json_decode($data);
			$data=array($data);
			if(true){
				$status=200;
				$msg='获取成功';
			}else{
				$staus=250;
				$msg='获取失败';
			}
			$this->data->addBody(-140, $data, $msg, $status);
		}
	//获取android的token
		public function Androidtoken(){
			$token=new API_ServerAPIModel('c9kqb3rdkdinj','J64wAhMLpHd7s');
			$user_name = Perm::$row['user_account'];
			$user_id = Perm::$row['user_id'];
			$logo='http://democn.mall-builder.com/uploadfile/adv/2015/02/05/1423110761.jpg';
			$data=$token->getToken($user_id,$user_name,$logo);
			$data=json_decode($data);
			$data=array($data);
			if(true){
				$status=200;
				$msg='获取成功';
			}else{
				$staus=250;
				$msg='获取失败';
			}
			$this->data->addBody(-140, $data, $msg, $status);
		}
		//添加好友的消息提醒
		public function sendAddFriendMessage(){
			$user_accounts=request_string('user_account');
			// var_dump($user_accounts);
			$token=new API_ServerAPIModel('c9kqb3rdkdinj','J64wAhMLpHd7s');
			$user_Base=new User_BaseModel();
			$user_Info=new User_InfoModel();
			$toUserId=$user_Base->getUserIdByAccount($user_accounts);
			$toUserId=array_pop($toUserId);
			// var_dump($toUserId);
			$user_account=Perm::$row['user_account'];
			$data_Info=$user_Info->getInfo($user_account);
			if($data_Info[$user_account]['nickname']){
				$user_account=$data_Info[$user_account]['nickname'];
			}
			$fromUserId=Perm::$row['user_id'];
			// var_dump($fromUserId);
			$objectName="RC:ContactNtf";
			$contents=$user_account.'请求加你为好友';
			$content='{"operation":"messageSystemPublish","sourceUserId":'.'"'.$fromUserId.'"'.',"targetUserId":'.'"'.$toUserId.'"'.',"message":'.'"'.$contents.'"'.',"extra":"helloExtra"}';
			// $content="{'message':$contents,'extra':'hello'}";
			$data=$token->messageSystemPublish($fromUserId,$toUserId,$objectName,$content,$contents,$content);
			// var_dump($data);die;
			$data=json_decode($data);
			if($data->code==200){
				$status=200;
				$msg='发送成功';
			}else{
				$status=250;
				$msg='发送失败';
			}
			$data=array();
			$this->data->addBody(-140, $data, $msg, $status);
		}
		//返回添加好友的结果
		public function getAddFriendsResult(){
			$result=request_int('result');//0代表拒绝 1代表同意
			$id=request_int('uid');
			$fromUserId=Perm::$row['user_id'];
			$token=new API_ServerAPIModel('c9kqb3rdkdinj','J64wAhMLpHd7s');
			$objectName="RC:ContactNtf";
			$content='{"operation":"messageSystemPublish","sourceUserId":'.'"'.$fromUserId.'"'.',"targetUserId":'.'"'.$id.'"'.',"message":'.'"'.$contents.'"'.',"extra":"helloExtra"}';
			$data=$token->messageSystemPublish($fromUserId,$id,$objectName,$content);
			$data=json_decode($data);
			$user_friend=new User_FriendModel();
			if($data->code==200&$result==1){
				$field=array('user_id'=>$id,
						   'friend_id'=>$fromUserId,
						    'add_time'=>date('Y-m-d H:i:s',time()));
				$user_friend->addFriend($field);
				$fields=array('user_id'=>$fromUserId,
					        'friend_id'=>$id,
							 'add_time'=>date('Y-m-d H:i:s',time()));
			$user_friend->addFriend($fields);
			}
			if($data->code==200){
				$status=200;
				$msg='发送成功';
			}else{
				$status=250;
				$msg='发送失败';
			}
			$data=array();
			$this->data->addBody(-140, $data, $msg, $status);
		}

	}

?>

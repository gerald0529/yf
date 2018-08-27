<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_PushModel extends User_Push
{
	public function checkUser($user_id=null,$fuid=null){
		$this->sql->setwhere('user_id',$user_id)->setwhere('fuid',$fuid);
		$id_row=$this->selectKeyLimit();
		$flag=0;
		if(!$id_row){
			$flag=1;
		}
		return $flag;
	}
	//获取添加好友列表
	public function getFriendList($user_id=null){
		$this->sql->setwhere('fuid',$user_id);
		$this->sql->setorder('addtime','desc');
		$id_row=$this->selectKeyLimit();
		$data = array();
		if($id_row){
			$data=$this->getPush($id_row);
		}else{
			$flag=0;
		}
		
		return $data;
	}	
	//添加成功修改状态
	public function eidtFriendStatus($id=null){
		$fiel=array('replay_id'=>'1');
		$flag=$this->editPush($id,$fiel);
		return $flag;
	}
	//删除推送好友
	public function removePushUser($user_id=null,$fuid=null){
		$this->sql->setwhere('user_id',$user_id)->setwhere('fuid',$fuid);
		$id=$this->selectKeyLimit();
		$flag=1;
		if($id){
		$this->remove($id[0]);	
		}
		$this->sql->setwhere('user_id',$fuid)->setwhere('fuid',$user_id);
		$id_row=$this->selectKeyLimit();
		if($id_row){
			$this->remove($id_row[0]);
		}
		
		return $flag;

	}

	public function countFriend($user_id=null,$status=0){

		$this->sql->setwhere('fuid',$user_id);
		$this->sql->setWhere('replay_id',$status);
		$this->sql->setorder('addtime','desc');
		$id_row=$this->selectKeyLimit();

		return $id_row;
	}
}
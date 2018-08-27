<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_FriendModel extends User_Friend
{
	/**
	 * 读取分页列表
	 *
	 * @param  int $user_friend_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getFriendList($user_friend_id = null, $page=1, $rows=100, $sort='asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);
		$user_friend_id_row = array();
		$user_friend_id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($user_friend_id_row)
		{
			$data_rows = $this->getFriend($user_friend_id_row);
		}

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);
		//var_dump($data);exit;
		return $data;
	}
	//查询关注我的
	public function getFollowid($friend_id=null,$rows=null,$page=null){

		$data = array();

		if($rows!=null&&$page!=null){
			$offset = $rows * ($page - 1);
			$this->sql->setLimit($offset, $rows);
		}

		$this->sql->setWhere('friend_id',$friend_id);
		$data_rows = $this->getFriend('*');

		return $data_rows;
	}

	public function getUserFriendIdById($user_id, $friend_id=null)
	{
		$data = array();

		$this->sql->setWhere('user_id', $user_id);
		if ($friend_id)
		{
			$this->sql->setWhere('friend_id', $friend_id);
		}
		$data_rows = $this->getFriend('*');
        fb($data_rows);
		return $data_rows;
	}
	//获取好友分页id
	public function getUserFriendIdByIdList($user_id, $group_id=null,$rows=null,$page=null)
	{
		if($rows!=null&&$page!=null){
			$offset = $rows * ($page - 1);
			$this->sql->setLimit($offset, $rows);
			}
		$data = array();

		$this->sql->setWhere('user_id', $user_id);
		if ($group_id)
		{
			$this->sql->setWhere('group_id', $group_id);
		}
		$data_rows = $this->getFriend('*');
		return $data_rows;
	}

	public function getUserFriendId($user_id, $group_id=null)
	{
		$data = array();

		$this->sql->setWhere('user_id', $user_id);

		if ($group_id)
		{
			$this->sql->setWhere('group_id', $group_id);
		}

		$data_rows = $this->getFriend('*');


		return $data_rows;
	}

	//分组有多少好友
	public function getGroupFriend($group_id=null)
	{
		$data = array();
		$this->sql->setWhere('group_id', $group_id);
		$data_rows = $this->getFriend('*');
		return $data_rows;
	}

	public function getUserUserIdByFriendId($friend_id=null)
	{
		$data = array();

		$this->sql->setWhere('friend_id', $friend_id);


		$data_rows = $this->getFriend('*');


		return $data_rows;
	}
	//查询是否是好友
	public function friend($user_id=null,$friend_id=null){

		$this->sql->setWhere('friend_id', $friend_id);
		$this->sql->setWhere('user_id', $user_id);
		$data_rows = $this->getFriend('*');
		return $data_rows;
	}

	public function getfriends($user_id=null){

		$this->sql->setWhere("user_id",$user_id);
		$rows = $this->getFriend("*");
		//var_dump($this->sql); exit;
		return $rows;
	}

	//判断是否是该用户的好友
	public function checkFriend($userid=null,$friendid=null){
		$this->sql->setwhere('user_id',$userid)->setwhere('friend_id',$friendid);
		$friend_rows=$this->getFriend('*');
		if($friend_rows){
			$flag=1;
		}else{
			$flag=0;
		}
		return $flag;

	}
}
?>
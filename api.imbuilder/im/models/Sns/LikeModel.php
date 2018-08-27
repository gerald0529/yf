<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Sns_LikeModel extends Sns_Like
{

	public $_multiCollect = array();

	//通过sns_id查找点赞的信息
	public function getLikeBySid($sns_id=null)
	{
		$this->sql->setWhere('sns_id', $sns_id);
		$sns_id_row = $this->selectKeyLimit();

		$data_rows = array();
		$like_user = array();
		if ($sns_id_row)
		{
			foreach($sns_id_row as $key=>$value)
			{
				$data_rows[] = current($this->getLike($value));
			}
			foreach($data_rows as $k=>$v)
			{
				$like_user[$k]['user_id'] = $v['user_id'];
				$like_user[$k]['addtime'] = $v['like_time'];
			}
		}

		return $like_user;
	}

	//通过sns_id与user_id查找点赞记录
	public function getLikeIdByUidSid($sns_id=null,$user_id=null)
	{
		$this->sql->setWhere('sns_id', $sns_id);
		$this->sql->setWhere('user_id', $user_id);
		$like_id_row = $this->selectKeyLimit();

		return $like_id_row;
	}
}
?>
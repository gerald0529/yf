<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Sns_CollectionModel extends Sns_Collection
{

	public $_multiCollect = array();
	/**
	 * 读取分页列表
	 *
	 * @param  int $collect_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCollectionList($user_id = null, $page=1, $rows=100, $sort='asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$collect_id_row = array();
		$this->sql->setwhere('user_id',$user_id);
		$collect_id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($collect_id_row)
		{
			$data_rows = $this->getCollection($collect_id_row);
		}
		rsort($data_rows);
		fb($data_rows);

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}

	public function getCollectBySidUid($sns_id,$user_id)
	{
		$this->_multiCollect['sns_id'] = $sns_id;
		$this->_multiCollect['user_id'] = $user_id;
		$id_row = $this->getKeyByMultiCond($this->_multiCollect);
		return $id_row;
	}

	public function getCollectSnsByUid($user_id=null)
	{
		$data = array();

		$this->sql->setWhere('user_id', $user_id);

		$data_rows = $this->getCollection('*');


		return $data_rows;
	}
	
	public function removeCollection($collect_id)
	{
		$del_flag = $this->remove($collect_id);

		//$this->removeKey($user_friend_id);
		return $del_flag;
	}

	//通过sns_id查找收藏的信息   2016.12.1  houpeng
	public function getCollectBySid($sns_id)
	{
		$this->sql->setWhere('sns_id', $sns_id);
		$sns_id_row = $this->selectKeyLimit();
		//echo '<pre>';print_r($sns_id_row);exit;
		$data_rows = array();
		$collection_user = array();
		if ($sns_id_row)
		{
			foreach($sns_id_row as $key=>$value)
			{
				$data_rows[] = current($this->getCollection($value));
			}
			foreach($data_rows as $k=>$v)
			{
				$collection_user[$k]['user_id'] = $v['user_id'];
				$collection_user[$k]['addtime'] = $v['collect_time'];
			}
		}
		//echo '<pre>';print_r($collection_user);exit;
		return $collection_user;
	}
}
?>
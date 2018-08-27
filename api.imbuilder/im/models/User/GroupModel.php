<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_GroupModel extends User_Group
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $group_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getGroupList($group_id = null, $page=1, $rows=100, $sort='asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$group_id_row = array();
		$group_id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($group_id_row)
		{
			$data_rows = $this->getGroup($group_id_row);
		}

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}

	public function getIdByGroupBindId($group_bind_id)
	{
		$data_row = array();

		$data_rows = $this->getKey($group_bind_id, 'group_bind_id');

		if ($data_rows)
		{
			$data_row = array_pop($data_rows);
		}

		return $data_row;
	}

	public function getGIdByGroupBindId($group_bind_id)
	{
		$data_row = array();

		$data_rows = $this->getKey($group_bind_id, 'group_bind_id');

		return $data_rows;
	}


	public function getUserGroup($user_id,$group_id=""){
		$data_row = array();
		$this->sql->setWhere('user_id', $user_id);
		if(!empty($group_id)){
			$this->sql->setWhere('group_id', $group_id);
		}
		$data_rows = $this->getGroup('*');

		return $data_rows;
	}

	public function checkUserGroup($user_id,$group_bind_id){
		$data_row = array();
		$this->sql->setWhere('user_id', $user_id);
		$this->sql->setWhere('group_bind_id', $group_bind_id);

		$data_rows = $this->getGroup('*');

		return $data_rows;
	}
}
?>
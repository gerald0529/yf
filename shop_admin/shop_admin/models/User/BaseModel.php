<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class User_BaseModel extends User_Base
{

	private $_multiCond = array('user_account' => null);

	public function getUserIdByAccount($user_account)
	{
		$user_id_row = array();

		$this->_multiCond['user_account'] = $user_account;

		$user_id_row = $this->getKeyByMultiCond($this->_multiCond);

		return $user_id_row;
	}

	/**
	 * 读取分页列表
	 *
	 * @param  int $user_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
		
		foreach ($data['items'] as $k => $item)
		{
			unset($item['user_password']);
			unset($item['user_key']);
			$item['id']        = $item['user_id'];
			$item['name']      = $item['user_account'];
			$data['items'][$k] = $item;
		}
		
		return $data;
	}

	/**
	 * 读取分页列表
	 *
	 * @param  int $user_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getUserList($user_id = null, $page = 1, $rows = 100, $sort = 'asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$user_id_row = array();
		$user_id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($user_id_row)
		{
			$this->baseRightsGroupModel = new Rights_GroupModel();
			$data_rights                = $this->baseRightsGroupModel->getRightsGroupList();
			$data_rights                = $data_rights['items'];
			$data_rows                  = $this->getUser($user_id_row);
			foreach ($data_rows as $key => $val)
			{
				foreach ($data_rights as $k => $v)
				{
					if ($val['rights_group_id'] == $v['rights_group_id'])
					{
						$data_rows[$key]['rights_group_name'] = $v['rights_group_name'];
					}
				}
			}
		}

		$data              = array();
		$data['page']      = $page;
		$data['total']     = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records']   = count($data_rows);
		$data['items']     = array_values($data_rows);

		return $data;
	}
}

?>
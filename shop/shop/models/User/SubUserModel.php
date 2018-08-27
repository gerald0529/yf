<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class User_SubUserModel extends User_SubUser
{
	const IS_ACTIVE = 1;  //激活
	const NO_ACTIVE = 0;  //不激活
	
	/**
	 * 读取分页列表
	 *
	 * @param  array $cond_row 查询条件
	 * @param  array $order_row 排序信息
	 * @param  array $page 当前页码
	 * @param  array $rows 每页记录数
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getSubUserList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);

		$user_id_row = array_column($data['items'],'sub_user_id');

		$User_InfoModel = new User_InfoModel();

		//查找子账号的用户信息
		$user_info_row = array();
		if($user_id_row)
		{
			$user_info_row = $User_InfoModel->getByWhere(array('user_id:IN' => $user_id_row));
		}

		foreach($data['items'] as $key => $val)
		{
			if(isset($user_info_row[$val['sub_user_id']]))
			{
				$data['items'][$key]['user_name'] = $user_info_row[$val['sub_user_id']]['user_name'];
			}

			if($val['sub_user_active'])
			{
				$data['items'][$key]['active_state'] = '是';
			}
			else
			{
				$data['items'][$key]['active_state'] = '否';
			}
		}

		return $data;
	}

	/**
	 * 读取列表
	 *
	 * @param  array $cond_row 查询条件
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getSubList($cond_row = array())
	{
		
		$data = $this->getByWhere($cond_row);

		return $data;
	}

	/**
	 * 读取一个详情
	 *
	 * @param  array $cond_row 查询条件
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getSubUserDetail($cond_row)
	{
		$data = $this->getOneByWhere($cond_row);

		//查找主管账户信息
		$User_BaseModel = new  User_BaseModel();
		$user_base = $User_BaseModel->getOne($data['user_id']);
		$data['user_account'] = $user_base['user_account'];

		return $data;
	}
}

?>
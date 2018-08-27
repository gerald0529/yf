<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class User_FriendModel extends User_Friend
{

	/**
	 * 读取分页列表
	 *
	 * @param  array $cond_row 查询条件
	 * @param  array $order_row 排序信息
	 * @param  array $page 当前页码
	 * @param  array $rows 每页记录数
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getFriendList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}
	
	/**
	 * 读取关注会员以及会员所有的基本信息,status是否关注我
	 *
	 * @param  array $cond_row 查询条件
	 * @param  array $order_row 排序信息
	 * @param  array $page 当前页码
	 * @param  array $rows 每页记录数
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getFriendAllDetail($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
		
		foreach ($data['items'] as $key => $val)
		{
			$cond_row            = array();
			$cond_row['user_id'] = $val['friend_id'];
			
			$cond_row['user_statu'] = 0;
			
			$this->userInfoModel = new User_InfoModel();
			
			$re = $this->userInfoModel->getUserInfo($cond_row);
			
			if ($re)
			{
				$data['items'][$key]['detail'] = $re;
				
				$friend_row['user_id'] = $data['items'][$key]['detail']['user_id'];
				$de                    = $this->getOneByWhere($friend_row);
				if ($de)
				{
					$data['items'][$key]['detail']['status'] = 1;
				}
				else
				{
					$data['items'][$key]['detail']['status'] = 0;
				}
			}
			
		}
		return $data;
	}

	/**
	 * 读取关注我会员以及会员所有的基本信息,status是否关注我
	 *
	 * @param  array $cond_row 查询条件
	 * @param  array $order_row 排序信息
	 * @param  array $page 当前页码
	 * @param  array $rows 每页记录数
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getBeFriendAllDetail($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
		
		foreach ($data['items'] as $key => $val)
		{
			$cond_row            = array();
			$cond_row['user_id'] = $val['user_id'];
			
			$cond_row['user_statu'] = 0;
			
			$this->userInfoModel = new User_InfoModel();
			
			$re = $this->userInfoModel->getUserInfo($cond_row);
			
			if ($re)
			{
				$data['items'][$key]['detail'] = $re;
				
				$friend_row['friend_id'] = $data['items'][$key]['detail']['user_id'];
				$de                      = $this->getOneByWhere($friend_row);
				if ($de)
				{
					$data['items'][$key]['detail']['status'] = 1;
				}
				else
				{
					$data['items'][$key]['detail']['status'] = 0;
				}
			}
			
		}
		return $data;
	}

	/**
	 * 读取好友所有信息
	 *
	 * @param  array $cond_row 查询条件
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getFriendAll($cond_row = null)
	{
		$data = $this->getByWhere($cond_row);

		return $data;
	}
	
	
	/**
	 * 读取一个好友信息
	 *
	 * @param  array $cond_row 查询条件
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getFriendInfo($cond_row = array())
	{
		return $this->getOneByWhere($cond_row);
	}
	
	/**
	 * 读取数量
	 *
	 * @param  array $cond_row 查询条件
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCount($cond_row = array())
	{
		return $this->getNum($cond_row);
	}
}

?>
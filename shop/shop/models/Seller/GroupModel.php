<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author    yesai
 */
class Seller_GroupModel extends Seller_Group
{

	/**
	 * 读取分页列表
	 *
	 * @param  array $cond_row
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getSellerGroupList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	/**
	 * 读取列表，不分页
	 *
	 * @param  array  主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getSellerGroup($cond_row = array(), $order_row = array())
	{
		return $this->getByWhere($cond_row, $order_row);
	}

	/**
	 * 多条件获取卖家用户组信息
	 *
	 * @param  array $cond_row
	 * @return bool
	 * @access public
	 */
	public function getSellerGroupInfoByID($group_id)
	{
		return $this->getOne($group_id);
	}
}

?>
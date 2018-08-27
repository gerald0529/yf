<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class User_ResourceModel extends User_Resource
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
	public function getResourceList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->getByWhere($cond_row, $order_row, $page, $rows);
	}

	/**
	 * 读取会员信息
	 *
	 * @param  array $order_row 查询条件
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getUserResource($order_row = array())
	{
		return $this->getOneByWhere($order_row);
	}
}

?>
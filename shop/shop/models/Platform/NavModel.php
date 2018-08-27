<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Platform_NavModel extends Platform_Nav
{
	const NAV_LOCATION_TOP  = 0; //头部显示
	const NAV_LOCATION_BODY = 1; //中部显示
	const NAV_LOCATION_FOOT = 2; //底部显示
	const NEW_OPEN_FALSE    = 0; //不是新窗口打开
	const NEW_OPEN_TRUE     = 1;    //新窗口打开
	const NAV_ACTIVE_FALSE  = 0; //不启用
	const NAV_ACTIVE_TRUE   = 1;   //启用

	/**
	 * 读取分页列表
	 *
	 * @param  int $nav_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getNavList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}
}

?>
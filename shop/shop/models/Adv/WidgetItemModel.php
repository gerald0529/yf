<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Adv_WidgetItemModel extends Adv_WidgetItem
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $item_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getWidgetItemList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}
}

?>
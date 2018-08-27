<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_PropertyIndexModel extends Goods_PropertyIndex
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $goods_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getPropertyIndexList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}
}

?>
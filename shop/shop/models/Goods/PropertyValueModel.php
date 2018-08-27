<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_PropertyValueModel extends Goods_PropertyValue
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $property_value_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getPropertyValueList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}
}

?>
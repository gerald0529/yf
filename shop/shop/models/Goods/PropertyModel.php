<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_PropertyModel extends Goods_Property
{
	const GOODS_PROPERTY_TEXT     = 'text';  //text
	const GOODS_PROPERTY_SELECT   = 'select';  //select
	const GOODS_PROPERTY_CHECKBOX = 'checkbox'; //checkbox

	public static $propertyMap = array(
		'text' => 'text....',
		'select' => 'select.....',
		'checkbox' => 'checkbox....'
	);


	/**
	 * 读取分页列表
	 *
	 * @param  int $property_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getPropertyList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}
}

?>
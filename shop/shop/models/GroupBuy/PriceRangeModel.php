<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/20
 * Time: 15:44
 */
class GroupBuy_PriceRangeModel extends GroupBuy_PriceRange
{
	/*多条件获取价格区间列表，分页*/
	public function getPriceRangeList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	public function getPriceRangeByWhere($cond_row = array(), $order_row = array())
	{
		return $this->getByWhere($cond_row, $order_row);
	}

	/**
	 * 插入
	 * @param array $field_row 插入数据信息
	 * @param bool $return_insert_id 是否返回inset id
	 * @param array $field_row 信息
	 * @return bool  是否成功
	 * @access public
	 */
	public function addPriceRange($field_row, $return_insert_id = false)
	{
		$add_flag = $this->add($field_row, $return_insert_id);

		return $add_flag;
	}

	public function editPriceRange($range_id, $field_row)
	{
		return $this->edit($range_id, $field_row);
	}

	public function getPriceRangeById($range_id)
	{
		return $this->getOne($range_id);
	}
	/*删除价格区间列表*/
	/**
	 * @param $combo_id
	 * @return bool
	 */
	public function removePriceRange($range_id)
	{
		$del_flag = $this->remove($range_id);

		return $del_flag;
	}
}
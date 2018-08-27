<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/15
 * Time: 17:57
 */
class Points_OrderGoodsModel extends Points_OrderGoods
{
	public function addPointsOrderGoods($field_row, $flag)
	{
		return $this->add($field_row, $flag);
	}

	public function getPointsOrderGoodsByWhere($cond_row, $order_row = array())
	{
		return $this->getByWhere($cond_row, $order_row);
	}

	public function getPointsOrderGoodsCount($cond_row)
	{
		return count($this->listByWhere($cond_row));
	}


}
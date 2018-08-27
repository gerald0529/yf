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
class Increase_RedempGoodsModel extends Increase_RedempGoods
{
	public function getIncreaseRedempGoodsList($cond_row = array(), $order_row = array(), $page, $rows)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	public function getIncreaseRedempGoodsByWhere($cond_row, $order_row = array())
	{
		return $this->getByWhere($cond_row, $order_row);
	}

	public function getIncreaseRedempGoodsIdByWhere($cond_row, $order_row)
	{
		$rows = array();
		$row  = array();
		$rows = $this->getIncreaseRedempGoodsByWhere($cond_row, $order_row);
		foreach ($rows as $key => $value)
		{
			$row[$value['redemp_goods_id']] = $value['goods_id'];
		}
		return $row;
	}

	//获取换购商品主键id
	public function getRedempGoodsKeyByWhere($cond_row, $order_row)
	{
		return $this->getKeyByWhere($cond_row, $order_row);
	}

	public function removeIncreaseRedempGoods($redemp_goods_id)
	{
		$del_flag = $this->remove($redemp_goods_id);

		return $del_flag;
	}

	public function addIncreaseRedempGoods($field_row, $return_insert_id)
	{
		$this->add($field_row, $return_insert_id);
	}

	public function editRedemptionGoods($redemp_goods_id, $field_row)
	{
		$this->edit($redemp_goods_id, $field_row);
	}
}
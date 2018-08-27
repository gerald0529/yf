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
class Points_CartModel extends Points_Cart
{

	public function addCart($field_row, $return_insert_id = true)
	{
		$flag = $this->add($field_row, $return_insert_id);
		return $flag;
	}

	public function getOnePointsCartByWhere($cond_row)
	{
		$row = $this->getOneByWhere($cond_row);
		return $row;
	}

	public function getUserPointsCartCount($user_id)
	{
		$cond_row['points_user_id'] = $user_id;
		return $this->getNum($cond_row);
	}

	public function getPointsCartByWhere($cond_row)
	{
		$rows = $this->getByWhere($cond_row);
		return $rows;
	}

	public function editPointsCart($points_cart_id, $field_row, $flag)
	{
		return $this->edit($points_cart_id, $field_row, $flag);
	}

	public function removePointsCart($points_cart_id)
	{
		return $this->remove($points_cart_id);
	}

}
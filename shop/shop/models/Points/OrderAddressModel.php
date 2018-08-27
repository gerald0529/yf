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
class Points_OrderAddressModel extends Points_OrderAddress
{
	public function addPointsOrderAddress($field_row, $flag)
	{
		return $this->add($field_row, $flag);
	}

}
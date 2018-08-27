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
class Increase_ComboModel extends Increase_Combo
{
	/*套餐列表*/
	public function getIncreaseComboList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	/**
	 *店铺套餐信息
	 * @param $shop_id 店铺id
	 * @return array
	 */
	public function getIncreaseComboByShopID($shop_id)
	{
		return $this->getOneByWhere(array('shop_id' => $shop_id));
	}
	
	/**
	 * 删除套餐
	 * @param $combo_id
	 * @return bool
	 */
	public function removeIncreaseCombo($combo_id)
	{
		$del_flag = $this->remove($combo_id);

		return $del_flag;
	}

	/*
	 * 购买套餐
	 * */
	public function addIncreaseCombo($field_row, $return_insert_id)
	{
		return $this->add($field_row, $return_insert_id);
	}

	/*
	 * 套餐续费
	 * */
	public function renewIncreaseCombo($combo_id, $field_row)
	{
		return $this->edit($combo_id, $field_row);
	}

	public function getComboInfo($shop_id)
	{
		$cond_row['shop_id'] = $shop_id;
		return $this->getOneByWhere($cond_row);
	}

	public function checkComboRight($shop_id)
	{
		$cond_row['shop_id']            = $shop_id;
		$cond_row['combo_start_time:<'] = date("Y-m-d H:i:s");
		$cond_row['combo_end_time:>']   = date("Y-m-d H:i:s");
		$shop_right                     = $this->getOneByWhere($cond_row);

		if ($shop_right)
		{
			return true;
		}
		else
		{
			return false;
		}

	}

	/*检查套餐状态*/
	public function checkQuotaStateByShopId($shop_id)
	{
		$row                           = array();
		$cond_row['shop_id']           = $shop_id;
		$cond_row['combo_end_time:>='] = date('Y-m-d H:i:s');
		$row                           = $this->getOneByWhere($cond_row);
		if (!empty($row))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
}
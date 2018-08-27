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
class ManSong_QuotaModel extends ManSong_Quota
{
	public function getManSongQuotaList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	/**
	 *根据店铺ID获取店铺满送套餐
	 * @param $shop_id 店铺id
	 * @return array
	 */
	public function getManSongQuotaByShopID($shop_id)
	{
		return $this->getOneByWhere(array('shop_id' => $shop_id));
	}

	/*检查套餐状态*/
	public function checkQuotaStateByShopId($shop_id)
	{
		$row                           = array();
		$cond_row['shop_id']           = $shop_id;
		$cond_row['combo_end_time:>='] = get_date_time();
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

	public function removeManSongQuotaItem($combo_id)
	{
		$del_flag = $this->remove($combo_id);

		return $del_flag;
	}
	
	public function getManSongQuotaDetailByWhere($cond_row)
	{
		return $this->getOneByWhere($cond_row);
	}

	/*
	* 购买套餐
	* */
	public function addManSongCombo($field_row, $return_insert_id)
	{
		return $this->add($field_row, $return_insert_id);
	}

	/*
	 * 套餐续费
	 * */
	public function renewManSongCombo($combo_id, $field_row)
	{
		return $this->edit($combo_id, $field_row);
	}

}
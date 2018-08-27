<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Voucher_quotaModel extends Voucher_quota
{
	/**
	 * 读取分页列表
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getConfigList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->getByWhere($cond_row, $order_row, $page, $rows);
	}

	public function getVoucherQuotaList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	public function getVoucherQuotaItemByWhere($cond_row)
	{
		return $this->getOneByWhere($cond_row);
	}

	/**
	 *根据店铺ID获取店铺代金券套餐
	 * @param $shop_id 店铺id
	 * @return array
	 */
	public function getVoucherQuotaByShopID($shop_id)
	{
		return $this->getOneByWhere(array('shop_id' => $shop_id));
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

	/*
	* 购买套餐
	* */
	public function addVoucherCombo($field_row, $return_insert_id)
	{
		return $this->add($field_row, $return_insert_id);
	}

	/*
	 * 套餐续费
	 * */
	public function renewVoucherCombo($combo_id, $field_row)
	{
		return $this->edit($combo_id, $field_row);
	}
}

?>
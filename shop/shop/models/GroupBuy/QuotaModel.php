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
class GroupBuy_QuotaModel extends GroupBuy_Quota
{
	/*获取团购套餐列表*/
	public function getGroupBuyQuotaList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
	    $data = $this->listByWhere($cond_row, $order_row, $page, $rows);
	    //这段话解决因为店铺名称冗余，造成数据不同步的问题
		$shopIds = array_column($data['items'], 'shop_id');
		$shopModel = new Shop_BaseModel;
        $shopList = $shopModel->getBase($shopIds);

        array_walk($data['items'], function (&$item, $k, $shopList) {
            $item['shop_name'] = $shopList[$item['shop_id']]['shop_name'];
        }, $shopList);

        return $data;
	}

	/**
	 *根据店铺ID获取店铺团购活动套餐
	 * @param $shop_id 店铺id
	 * @return array
	 */
	public function getGroupBuyQuotaByShopID($shop_id)
	{
		return $this->getOneByWhere(array('shop_id' => $shop_id));
	}

	/*检查套餐状态*/
	public function checkQuotaStateByShopId($shop_id)
	{
		$row                          = array();
		$cond_row['shop_id']          = $shop_id;
		$cond_row['combo_endtime:>='] = get_date_time();
		$row                          = $this->getOneByWhere($cond_row);
		if (!empty($row))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	/*删除团购套餐*/
	/**
	 * @param $combo_id
	 * @return bool
	 */
	public function removeGroupBuyQuota($combo_id)
	{
		$del_flag = $this->remove($combo_id);

		return $del_flag;
	}
	
	/*
	* 购买套餐
	* */
	public function addGroupBuyCombo($field_row, $return_insert_id)
	{
		return $this->add($field_row, $return_insert_id);
	}

	/*
	 * 套餐续费
	 * */
	public function renewGroupBuyCombo($combo_id, $field_row)
	{
		return $this->edit($combo_id, $field_row);
	}
}
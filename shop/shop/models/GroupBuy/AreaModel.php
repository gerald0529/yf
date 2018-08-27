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
class GroupBuy_AreaModel extends GroupBuy_Area
{
	/*获取团购地区列表*/
	public function getGroupBuyAreaList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	/*获取团购地区列表*/
	public function getGroupBuyAreaByWhere($cond_row = array(), $order_row = array())
	{
		return $this->getByWhere($cond_row, $order_row);
	}

	/*获取团购地区列表*/
	public function getDistrictTree($district_parent_id = 0, $recursive = true, $level = 0)
	{
		$data_rows = $this->getDistrictTreeData($district_parent_id, $recursive, $level);

		$data['items'] = array_values($data_rows);

		return $data;
	}

	/**
	 *根据ID获取团购地区信息
	 * @param $groupbuy_area_id 团购地区ID
	 * @return array
	 */
	public function getGroupBuyAreaByID($groupbuy_area_id)
	{
		return $this->getOne($groupbuy_area_id);
	}

	public function getGroupBuyAreaNameByID($groupbuy_area_id)
	{
		$ret_name = '全国';
		$row      = $this->getOne($groupbuy_area_id);
		if ($row)
		{
			$ret_name = $row['groupbuy_area_name'];
		}

		return __($ret_name);

	}


}
<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Shop_RenewalModel extends Shop_Renewal
{
	public static $renewal_status = array(
		"0" => "申请中",
		"1" => "申请成功"
	);

	public function getRenewalList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);

		//把数据库的状态以及分类id 和等级id全部变成中文。
		foreach ($data["items"] as $key => $value)
		{

			$data["items"][$key]["renewal_status_cha"] = __(self::$renewal_status[$value["status"]]);


		}
		return $data;
	}

	/**
	 * 读取单个店铺导航
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getRenewalinfo($table_primary_key_value = null, $key_row = null)
	{
		$data = $this->getOne($table_primary_key_value);
		return $data;
	}


	/**
	 *
	 *  更改店铺的结束时间
	 * @param  int $config_key 主键值
	 */

	public function editEndTime($table_primary_key_value = null)
	{
		$shop_renewal_info = $this->getOne($table_primary_key_value);

		if ($shop_renewal_info)
		{
			$shopBaseModel = new Shop_BaseModel();
			$shop_id = $shop_renewal_info['shop_id'];
			$con_row['shop_end_time'] = $shop_renewal_info['end_time'];
			$con_row['shop_grade_id'] = $shop_renewal_info['shop_grade_id'];
			$edit_base = $shopBaseModel->editBase($shop_id, $con_row);

		}
		return $edit_base;
	}

	public function getSubQuantity($cond_row)
	{
		return $this->getNum($cond_row);
	}
}

?>
<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Shop_NavModel extends Shop_Nav
{
	public static $nav_status = array(
		"0" => "关闭",
		"1" => "开启"
	);

	public function getNavList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);

		//把数据库的状态以及分类id 和等级id全部变成中文。
		foreach ($data["items"] as $key => $value)
		{

			$data["items"][$key]["nav_status_cha"] = __(self::$nav_status[$value["status"]]);


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
	public function getNavinfo($table_primary_key_value = null, $key_row = null)
	{
		$data = $this->getOne($table_primary_key_value);
		return $data;
	}

}

?>
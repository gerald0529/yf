<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Shop_CompanyModel extends Shop_Company
{
	public static $legal_identity_type            = array(
		"1" => "身份证",
		"2" => "护照",
		"3" => "军官证",
	);

	/**
	 * 读取店铺经营类目
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCompanyrow($table_primary_key_value = null, $key_row = null, $order_row = array())
	{
		return $this->get($table_primary_key_value, $key_row, $order_row);
	}


	/**
	 * 根据多个条件取得
	 *
	 * @param  array $cond_row
	 * @return array $rows 信息
	 * @access public
	 */
	public function getCompanyWhere($cond_row = array(), $order_row = array())
	{
		return $this->getByWhere($cond_row, $order_row);
	}

	/**
	 * 获取分页信息
	 *
	 * @param  array $cond_row
	 * @return array $rows 信息
	 * @access public
	 */
	public function listCompanyWhere($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{

		return $this->listByWhere($cond_row, $order_row, $page, $rows);

	}


}

?>
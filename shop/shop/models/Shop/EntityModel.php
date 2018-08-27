<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Shop_EntityModel extends Shop_Entity
{


	public function getEntityList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);


		return $data;
	}

	/**
	 * 读取单个店铺供货商
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getEntityinfo($table_primary_key_value = null, $key_row = null)
	{
		$data = $this->getOne($table_primary_key_value);
		return $data;
	}

}

?>
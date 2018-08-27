<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Shop_DomainModel extends Shop_Domain
{
	/**
	 * 读取分页列表
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getDomainList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data          = $this->listByWhere($cond_row, $order_row, $page, $rows);
		$shopBaseModel = new Shop_BaseModel();
		foreach ($data["items"] as $key => $value)
		{
			$domain              = $shopBaseModel->getOneByWhere(array('shop_id' => $value['shop_id']));
			$data["items"][$key] = array_merge($data["items"][$key], $domain);
		}
		return $data;

	}

	/**
	 * 读取单个信息
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getDomainRow($table_primary_key_value = null, $key_row = null, $order_row = array())
	{
		$data          = $this->get($table_primary_key_value, $key_row, $order_row);
		$shopBaseModel = new Shop_BaseModel();
		foreach ($data as $key => $value)
		{
			$domain     = $shopBaseModel->getOneByWhere(array('shop_id' => $value['shop_id']));
			$data[$key] = array_merge($data[$key], $domain);
		}
		return $data;
	}
}

?>
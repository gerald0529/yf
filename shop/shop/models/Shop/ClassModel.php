<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Shop_ClassModel extends Shop_Class
{

	/**
	 * 读取店铺分类
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getClassrow($table_primary_key_value = null, $key_row = null, $order_row = array())
	{
		return $this->get($table_primary_key_value, $key_row, $order_row);
	}

	/**
	 * 根据分类id查询出分类的名字
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getClassName($config_key = null)
	{
		$data       = $this->getClass($config_key);
		$class_name = array();
		foreach ($data as $key => $value)
		{
			$class_name = $value['shop_class_name'];
		}
		return $class_name;


	}

	/**
	 * 根据多个条件取得
	 *
	 * @param  array $cond_row
	 * @return array $rows 信息
	 * @access public
	 */
	public function getClassWhere($cond_row = array(), $order_row = array())
	{
		return $this->getByWhere($cond_row, $order_row);
	}

	public function listClassWhere($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	//多条件获取主键
	public function getClassId($cond_row = array(), $order_row = array())
	{
		return $this->getKeyByMultiCond($cond_row, $order_row);
	}


}

?>
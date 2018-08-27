<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 *
 *
 * @category   Framework
 * @package    __init__
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2016, 黄新泽
 * @version    1.0
 * @todo
 */
class Goods_PropertyValue extends Yf_Model
{
	public $_cacheKeyPrefix  = 'c|goods_property_value|';
	public $_cacheName       = 'goods';
	public $_tableName       = 'goods_property_value';
	public $_tablePrimaryKey = 'property_value_id';

	/**
	 * @param string $user User Object
	 * @var   string $db_id 指定需要连接的数据库Id
	 * @return void
	 */
	public function __construct(&$db_id = 'shop', &$user = null)
	{
		$this->_tableName = TABEL_PREFIX . $this->_tableName;
		$this->_cacheFlag = CHE;
		parent::__construct($db_id, $user);
	}

	/**
	 * 根据主键值，从数据库读取数据
	 *
	 * @param  int $property_value_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getPropertyValue($property_value_id = null, $sort_key_row = null)
	{
		$rows = array();
		$rows = $this->get($property_value_id, $sort_key_row);

		return $rows;
	}

	/**
	 * 插入
	 * @param array $field_row 插入数据信息
	 * @param bool $return_insert_id 是否返回inset id
	 * @param array $field_row 信息
	 * @return bool  是否成功
	 * @access public
	 */
	public function addPropertyValue($field_row, $return_insert_id = false)
	{
		$add_flag = $this->add($field_row, $return_insert_id);

		//$this->removeKey($property_value_id);
		return $add_flag;
	}

	/**
	 * 根据主键更新表内容
	 * @param mix $property_value_id 主键
	 * @param array $field_row key=>value数组
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editPropertyValue($property_value_id = null, $field_row)
	{
		$update_flag = $this->edit($property_value_id, $field_row);

		return $update_flag;
	}

	/**
	 * 更新单个字段
	 * @param mix $property_value_id
	 * @param array $field_name
	 * @param array $field_value_new
	 * @param array $field_value_old
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editPropertyValueSingleField($property_value_id, $field_name, $field_value_new, $field_value_old)
	{
		$update_flag = $this->editSingleField($property_value_id, $field_name, $field_value_new, $field_value_old);

		return $update_flag;
	}

	/**
	 * 删除操作
	 * @param int $property_value_id
	 * @return bool $del_flag 是否成功
	 * @access public
	 */
	public function removePropertyValue($property_value_id)
	{
		$del_flag = $this->remove($property_value_id);

		//$this->removeKey($property_value_id);
		return $del_flag;
	}
}

?>
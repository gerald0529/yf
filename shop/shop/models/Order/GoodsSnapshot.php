<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 *
 *
 * @category   Framework
 * @package    __init__
 * @author
 * @copyright  Copyright (c) 2016, 朱羽婷
 * @version    1.0
 * @todo
 */
class Order_GoodsSnapshot extends Yf_Model
{
	public $_cacheKeyPrefix  = 'c|order_goods_snapshot|';
	public $_cacheName       = 'order_goods_snapshot';
	public $_tableName       = 'order_goods_snapshot';
	public $_tablePrimaryKey = 'order_goods_snapshot_id';

	//public $jsonKey = array('order_spec_info');

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
	 * @param  int $order_goods_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getSnspshot($order_goods_snapshot_id = null, $sort_key_row = null)
	{
		$rows = array();
		$rows = $this->get($order_goods_snapshot_id, $sort_key_row);

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
	public function addSnapshot($field_row, $return_insert_id = false)
	{
		$add_flag = $this->add($field_row, $return_insert_id);

		//$this->removeKey($order_goods_id);
		return $add_flag;
	}

	/**
	 * 根据主键更新表内容
	 * @param mix $order_goods_id 主键
	 * @param array $field_row key=>value数组
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editSnapshot($order_goods_snapshot_id = null, $field_row)
	{
		$update_flag = $this->edit($order_goods_snapshot_id, $field_row);

		return $update_flag;
	}

	/**
	 * 更新单个字段
	 * @param mix $order_goods_id
	 * @param array $field_name
	 * @param array $field_value_new
	 * @param array $field_value_old
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editCodeSingleField($virtual_code_id, $field_name, $field_value_new, $field_value_old)
	{
		$update_flag = $this->editSingleField($virtual_code_id, $field_name, $field_value_new, $field_value_old);

		return $update_flag;
	}

	/**
	 * 删除操作
	 * @param int $order_goods_id
	 * @return bool $del_flag 是否成功
	 * @access public
	 */
	public function removeSnapshot($order_goods_snapshot_id)
	{
		$del_flag = $this->remove($order_goods_snapshot_id);

		//$this->removeKey($order_goods_id);
		return $del_flag;
	}
}

?>
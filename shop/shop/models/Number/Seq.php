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
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo
 */
class Number_Seq extends Yf_Model
{
	public $_cacheKeyPrefix  = 'c|number_seq|';
	public $_cacheName       = 'number';
	public $_tableName       = 'number_seq';
	public $_tablePrimaryKey = 'prefix';

	/**
	 * @param string $user User Object
	 * @var   string $db_id 指定需要连接的数据库Id
	 * @return void
	 */
	public function __construct(&$db_id = 'shop', &$user = null)
	{
		$this->_tableName = TABEL_PREFIX . $this->_tableName;
		parent::__construct($db_id, $user);
	}

	/**
	 * 插入
	 * @param array $field_row 插入数据信息
	 * @param bool $return_insert_id 是否返回inset id
	 * @param array $field_row 信息
	 * @return bool  是否成功
	 * @access public
	 */
	protected function addSeq($field_row, $return_insert_id = false)
	{
		$add_flag = $this->add($field_row, $return_insert_id);

		//$this->removeKey($prefix);
		return $add_flag;
	}

	/**
	 * 根据主键更新表内容
	 * @param mix $prefix 主键
	 * @param array $field_row key=>value数组
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	protected function editSeq($prefix = null, $field_row, $flag = false)
	{
		$update_flag = $this->edit($prefix, $field_row, $flag);

		return $update_flag;
	}

	/**
	 * 更新单个字段
	 * @param mix $prefix
	 * @param array $field_name
	 * @param array $field_value_new
	 * @param array $field_value_old
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	protected function editSeqSingleField($prefix, $field_name, $field_value_new, $field_value_old)
	{
		$update_flag = $this->editSingleField($prefix, $field_name, $field_value_new, $field_value_old);

		return $update_flag;
	}

	/**
	 * 删除操作
	 * @param int $prefix
	 * @return bool $del_flag 是否成功
	 * @access public
	 */
	protected function removeSeq($prefix)
	{
		$del_flag = $this->remove($prefix);

		//$this->removeKey($prefix);
		return $del_flag;
	}
}

?>
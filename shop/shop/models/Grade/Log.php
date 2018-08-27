<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/15
 * Time: 17:57
 */
class Grade_Log extends Yf_Model
{

	public $_cacheKeyPrefix  = 'c|grade_log|';
	public $_cacheName       = 'log';
	public $_tableName       = 'grade_log';
	public $_tablePrimaryKey = 'grade_log_id';

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
	 * @param  int $grade_log_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getLog($grade_log_id = null, $sort_key_row = null)
	{
		$rows = array();
		$rows = $this->get($grade_log_id, $sort_key_row);

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
	public function addLog($field_row, $return_insert_id = false)
	{
		$add_flag = $this->add($field_row, $return_insert_id);

		return $add_flag;
	}

	/**
	 * 根据主键更新表内容
	 * @param mix $grade_log_id 主键
	 * @param array $field_row key=>value数组
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editLog($grade_log_id = null, $field_row)
	{
		$update_flag = $this->edit($points_log_id, $field_row);

		return $update_flag;
	}

	/**
	 * 更新单个字段
	 * @param mix $grade_log_id
	 * @param array $field_name
	 * @param array $field_value_new
	 * @param array $field_value_old
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editLogSingleField($grade_log_id, $field_name, $field_value_new, $field_value_old)
	{
		$update_flag = $this->editSingleField($points_log_id, $field_name, $field_value_new, $field_value_old);

		return $update_flag;
	}

	/**
	 * 删除操作
	 * @param int $grade_log_id
	 * @return bool $del_flag 是否成功
	 * @access public
	 */
	public function removeLog($grade_log_id)
	{
		$del_flag = $this->remove($grade_log_id);

		return $del_flag;
	}


}
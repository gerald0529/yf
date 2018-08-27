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
class Base_Protocol extends Yf_Model
{
	public $_cacheKeyPrefix  = 'c|base_protocol|';
	public $_cacheName       = 'base';
	public $_tableName       = 'base_protocol';
	public $_tablePrimaryKey = 'protocol_id';

	/**
	 * @param string $user User Object
	 * @var   string $db_id 指定需要连接的数据库Id
	 * @return void
	 */
	public function __construct(&$db_id = 'shop_admin', &$user = null)
	{
		$this->_tableName = TABEL_PREFIX . $this->_tableName;
		parent::__construct($db_id, $user);
	}

	/**
	 * 根据主键值，从数据库读取数据
	 *
	 * @param  int $protocol_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getProtocol($protocol_id = null, $sort_key_row = null)
	{
		$rows = array();
		$rows = $this->get($protocol_id, $sort_key_row);

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
	public function addProtocol($field_row, $return_insert_id = false)
	{
		$add_flag = $this->add($field_row, $return_insert_id);

		//$this->removeKey($protocol_id);
		return $add_flag;
	}

	/**
	 * 根据主键更新表内容
	 * @param mix $protocol_id 主键
	 * @param array $field_row key=>value数组
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editProtocol($protocol_id = null, $field_row)
	{
		$update_flag = $this->edit($protocol_id, $field_row);

		return $update_flag;
	}

	/**
	 * 更新单个字段
	 * @param mix $protocol_id
	 * @param array $field_name
	 * @param array $field_value_new
	 * @param array $field_value_old
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editProtocolSingleField($protocol_id, $field_name, $field_value_new, $field_value_old)
	{
		$update_flag = $this->editSingleField($protocol_id, $field_name, $field_value_new, $field_value_old);

		return $update_flag;
	}

	/**
	 * 删除操作
	 * @param int $protocol_id
	 * @return bool $del_flag 是否成功
	 * @access public
	 */
	public function removeProtocol($protocol_id)
	{
		$del_flag = $this->remove($protocol_id);

		//$this->removeKey($protocol_id);
		return $del_flag;
	}
    
    public function getProtocolByWhere($cond_row, $order_row = array()){
        if(!is_array($cond_row) || !$cond_row){
            return false;
        }
        $result = $this->getOneByWhere($cond_row, $order_row);
        return $result; 
    }
}

?>
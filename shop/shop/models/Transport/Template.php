<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Description of Transport_Template
 * 运费模板设置 basic model
 * @author Str <tech40@yuanfeng.cn>
 * @version    shop3.1.3
 * 
 */
class Transport_Template extends Yf_Model
{
	public $_cacheKeyPrefix  = 'c|transport_template|';
	public $_cacheName       = 'transport';
	public $_tableName       = 'transport_template';
	public $_tablePrimaryKey = 'id';

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
	 * @param  int $id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getTemplate($id = null, $sort_key_row = null)
	{
		$rows = $this->get($id, $sort_key_row);
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
	public function addTemplate($field_row, $return_insert_id = false)
	{
		$add_flag = $this->add($field_row, $return_insert_id);
		return $add_flag;
	}

	/**
	 * 根据主键更新表内容
	 * @param mix $id 主键
	 * @param array $field_row key=>value数组
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editTemplate($id, $field_row)
	{
		$update_flag = $this->edit($id, $field_row);
		return $update_flag;
	}

	/**
	 * 更新单个字段
	 * @param mix $id
	 * @param array $field_name
	 * @param array $field_value_new
	 * @param array $field_value_old
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editTemplateSingleField($id, $field_name, $field_value_new, $field_value_old)
	{
		$update_flag = $this->editSingleField($id, $field_name, $field_value_new, $field_value_old);
		return $update_flag;
	}

	/**
	 * 删除操作
	 * @param int $id
	 * @return bool $del_flag 是否成功
	 * @access public
	 */
	public function removeTemplate($id)
	{
		$del_flag = $this->remove($id);
		return $del_flag;
	}
    
    /**
     * 根据条件返回主键id
     * @param type $cond_row
     * @param type $order_row
     * @return type
     */
    public function getId($cond_row,$order_row=array()){
        $key_rows = $this->getKeyByMultiCond($cond_row, $order_row);
        return $key_rows;
    }
    
    /**
     * 根据条件获取数量
     * @param type $cond_row
     * @return type
     */
    public function getCount($cond_row){
        $count = $this->getNum($cond_row);
        return $count;
    }
}

?>
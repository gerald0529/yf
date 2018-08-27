<?php if (!defined('ROOT_PATH')) exit('No Permission');
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
class Base_Config extends Yf_Model
{
    public $_cacheKeyPrefix  = 'c|pay_payment_channel|';
    public $_cacheName       = 'base';
    public $_tableName       = 'pay_payment_channel';
    public $_tablePrimaryKey = 'config_key';

    /**
     * @param string $user  User Object
     * @var   string $db_id 指定需要连接的数据库Id
     * @return void
     */
    public function __construct(&$db_id='paycenter_way', &$user=null)
    {
        $this->_tableName = TABEL_PREFIX . $this->_tableName;
        parent::__construct($db_id, $user);
    }

    /**
     * 根据主键值，从数据库读取数据
     *
     * @param  int   $config_id  主键值
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getConfig($config_key=null, $sort_key_row=null)
    {
        $rows = array();
        $rows = $this->get($config_key, $sort_key_row);

        return $rows;
    }

    /**
     * 插入
     * @param array $field_row 插入数据信息
     * @param bool  $return_insert_id 是否返回inset id
     * @param array $field_row 信息
     * @return bool  是否成功
     * @access public
     */
    public function addConfig($field_row, $return_insert_id=false)
    {
        $add_flag = $this->add($field_row, $return_insert_id);

        //$this->removeKey($config_key);
        return $add_flag;
    }

    /**
     * 根据主键更新表内容
     * @param mix   $config_key  主键
     * @param array $field_row   key=>value数组
     * @return bool $update_flag 是否成功
     * @access public
     */
    public function editConfig($config_key=null, $field_row)
    {
        $update_flag = $this->edit($config_key, $field_row);

        return $update_flag;
    }

    /**
     * 更新单个字段
     * @param mix   $config_id
     * @param array $field_name
     * @param array $field_value_new
     * @param array $field_value_old
     * @return bool $update_flag 是否成功
     * @access public
     */
    public function editConfigSingleField($config_key, $field_name, $field_value_new, $field_value_old)
    {
        $update_flag = $this->editSingleField($config_key, $field_name, $field_value_new, $field_value_old);

        return $update_flag;
    }    
    
    /**
     * 删除操作
     * @param int $config_key
     * @return bool $del_flag 是否成功
     * @access public
     */
    public function removeConfig($config_key)
    {
        $del_flag = $this->remove($config_key);

        //$this->removeKey($config_key);
        return $del_flag;
    }
}
?>
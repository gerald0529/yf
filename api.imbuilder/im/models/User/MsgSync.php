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
class User_MsgSync extends Yf_Model
{
    public $_cacheKeyPrefix  = 'c|user_msg_sync|';
    public $_cacheName       = 'user';
    public $_tableName       = 'user_msg_sync';
    public $_tablePrimaryKey = 'msg_sync_date';

    /**
     * @param string $user  User Object
     * @var   string $db_id 指定需要连接的数据库Id
     * @return void
     */
    public function __construct(&$db_id='im-builder', &$user=null)
    {
        $this->_tableName = TABEL_PREFIX . $this->_tableName;
        parent::__construct($db_id, $user);
    }

    /**
     * 根据主键值，从数据库读取数据
     *
     * @param  int   $msg_sync_date  主键值
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getMsgSync($msg_sync_date=null, $sort_key_row=null)
    {
        $rows = array();
        $rows = $this->get($msg_sync_date, $sort_key_row);

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
    public function addMsgSync($field_row, $return_insert_id=false)
    {
        $add_flag = $this->add($field_row, $return_insert_id);

        //$this->removeKey($msg_sync_date);
        return $add_flag;
    }

    /**
     * 根据主键更新表内容
     * @param mix   $msg_sync_date  主键
     * @param array $field_row   key=>value数组
     * @return bool $update_flag 是否成功
     * @access public
     */
    public function editMsgSync($msg_sync_date=null, $field_row)
    {
        $update_flag = $this->edit($msg_sync_date, $field_row);

        return $update_flag;
    }

    /**
     * 更新单个字段
     * @param mix   $msg_sync_date
     * @param array $field_name
     * @param array $field_value_new
     * @param array $field_value_old
     * @return bool $update_flag 是否成功
     * @access public
     */
    public function editMsgSyncSingleField($msg_sync_date, $field_name, $field_value_new, $field_value_old)
    {
        $update_flag = $this->editSingleField($msg_sync_date, $field_name, $field_value_new, $field_value_old);

        return $update_flag;
    }    
    
    /**
     * 删除操作
     * @param int $msg_sync_date
     * @return bool $del_flag 是否成功
     * @access public
     */
    public function removeMsgSync($msg_sync_date)
    {
        $del_flag = $this->remove($msg_sync_date);

        //$this->removeKey($msg_sync_date);
        return $del_flag;
    }
}
?>
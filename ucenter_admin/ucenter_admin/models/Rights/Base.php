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
class Rights_Base extends Yf_Model
{
    public $_cacheKeyPrefix  = 'c|rights_base|';
    public $_cacheName       = 'base';
    public $_tableName       = 'rights_base';
    public $_tablePrimaryKey = 'rights_id';

    /**
     * @param string $user  User Object
     * @var   string $db_id 指定需要连接的数据库Id
     * @return void
     */
    public function __construct(&$db_id='ucenter_admin', &$user=null)
    {
        $this->_tableName = TABEL_PREFIX . $this->_tableName;
        parent::__construct($db_id, $user);
    }

    /**
     * 根据主键值，从数据库读取数据
     *
     * @param  int   $rights_id  主键值
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getRights($rights_id=null, $sort_key_row=null)
    {
		$rs_rows = array();
        $rows = $this->get($rights_id, $sort_key_row);


		foreach ($rows as $k => $item)
		{
			if ($item['rights_parent_id'])
			{
				$row = array();
				$row['fobjectid'] = $item['rights_parent_id'];
				$row['fobject']   = $rows[$item['rights_parent_id']]['rights_name'];
				$row['faction'] = $item['rights_name'];
				$row['frightid'] = $item['rights_id'];

				$row['fright'] = 0;
				$row['readonly'] = 0;

				$rs_rows[] = $row;

			}
		}
	
	
		return $rs_rows;
    }

    /**
     * 插入
     * @param array $field_row 插入数据信息
     * @param bool  $return_insert_id 是否返回inset id
     * @param array $field_row 信息
     * @return bool  是否成功
     * @access public
     */
    public function addRights($field_row, $return_insert_id=false)
    {
        $add_flag = $this->add($field_row, $return_insert_id);

        //$this->removeKey($rights_id);
        return $add_flag;
    }

    /**
     * 根据主键更新表内容
     * @param mix   $rights_id  主键
     * @param array $field_row   key=>value数组
     * @return bool $update_flag 是否成功
     * @access public
     */
    public function editRights($rights_id=null, $field_row)
    {
        $update_flag = $this->edit($rights_id, $field_row);

        return $update_flag;
    }

    /**
     * 更新单个字段
     * @param mix   $rights_id
     * @param array $field_name
     * @param array $field_value_new
     * @param array $field_value_old
     * @return bool $update_flag 是否成功
     * @access public
     */
    public function editRightsSingleField($rights_id, $field_name, $field_value_new, $field_value_old)
    {
        $update_flag = $this->editSingleField($rights_id, $field_name, $field_value_new, $field_value_old);

        return $update_flag;
    }    
    
    /**
     * 删除操作
     * @param int $rights_id
     * @return bool $del_flag 是否成功
     * @access public
     */
    public function removeRights($rights_id)
    {
        $del_flag = $this->remove($rights_id);

        //$this->removeKey($rights_id);
        return $del_flag;
    }
}
?>
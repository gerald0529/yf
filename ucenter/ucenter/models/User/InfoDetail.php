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
class User_InfoDetail extends Yf_Model
{
    public $_cacheKeyPrefix  = 'c|user_info_detail|';
    public $_cacheName       = 'user';
    public $_tableName       = 'user_info_detail';
    public $_tablePrimaryKey = 'user_name';

    /**
     * @param string $user  User Object
     * @var   string $db_id 指定需要连接的数据库Id
     * @return void
     */
    public function __construct(&$db_id='ucenter', &$user=null)
    {
        $this->_tableName = TABEL_PREFIX . $this->_tableName;
		$this->_cacheFlag        = CHE;
        parent::__construct($db_id, $user);
    }

    /**
     * 根据主键值，从数据库读取数据
     *
     * @param  int   $user_name  主键值
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getInfoDetail($user_name=null, $sort_key_row=null)
    {
        $rows = array();
        $rows = $this->get($user_name, $sort_key_row);


		foreach ($rows as $k => $row)
		{
			$rows[$k]['user_avatar']     = $row['user_avatar'] ? $row['user_avatar'] : Web_ConfigModel::value('user_default_avatar', Yf_Registry::get('static_url') . '/images/default_user_portrait.png');
		}

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
    public function addInfoDetail($field_row, $return_insert_id=false)
    {
        $add_flag = $this->add($field_row, $return_insert_id);

        //$this->removeKey($user_name);
        return $add_flag;
    }

    /**
     * 根据主键更新表内容
     * @param mix   $user_name  主键
     * @param array $field_row   key=>value数组
     * @return bool $update_flag 是否成功
     * @access public
     */
    public function editInfoDetail($user_name=null, $field_row)
    {
        $update_flag = $this->edit($user_name, $field_row);

        return $update_flag;
    }

    /**
     * 更新单个字段
     * @param mix   $user_name
     * @param array $field_name
     * @param array $field_value_new
     * @param array $field_value_old
     * @return bool $update_flag 是否成功
     * @access public
     */
    public function editInfoDetailSingleField($user_name, $field_name, $field_value_new, $field_value_old)
    {
        $update_flag = $this->editSingleField($user_name, $field_name, $field_value_new, $field_value_old);

        return $update_flag;
    }

    /**
     * 删除操作
     * @param int $user_name
     * @return bool $del_flag 是否成功
     * @access public
     */
    public function removeInfoDetail($user_name)
    {
        $del_flag = $this->remove($user_name);

        //$this->removeKey($user_name);
        return $del_flag;
    }

    /**
     * 检查用户名
     * @param type $user_name
     * @return type
     */
    public function checkUserName($user_name,$user_id=0){
        if($user_id){
            $user_model = new User_Info();
            $user_name_info = $user_model->getOne($user_id);
            $org_user_name = $user_name_info['user_name'];
            $where = 'user_name!="'.$org_user_name.'" and (user_tel="'.$user_name.'" or user_mobile="'.$user_name.'" or user_email="'.$user_name.'")';
        }else{
            $where = 'user_tel="'.$user_name.'" or user_mobile="'.$user_name.'" or user_email="'.$user_name.'"';
        }

        $sql = 'select user_name from '.$this->_tableName.' where '.$where.' limit 1';
		$data_rows = $this->sql->getAll($sql);
        return $data_rows;
    }

}
?>

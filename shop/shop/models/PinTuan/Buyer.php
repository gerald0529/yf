<?php

/**
 * Created by PhpStorm.
 * User: rd04
 * Date: 2017/11/13
 * Time: 15:46
 */
class PinTuan_Buyer extends Yf_Model
{
    public $_cacheKeyPrefix  = 'c|pintuan_buyer|';
    public $_cacheName       = 'pintuan_buyer';
    public $_tableName       = 'pintuan_buyer';
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
    
    public function getBuyerInfoByMarkId($mark_id){
        $user_model = new User_InfoModel();
        $info = $this->getByWhere(array('mark_id'=>$mark_id),array('id'=>'ASC'));
        foreach($info as $key=>$val)
        {
            $user_info = $user_model->getOneByWhere(array('user_id'=>$val['user_id']));
            $info[$key]['user_logo'] = $user_info['user_logo'];
        }
        return $info;
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
    
    /**
     *  获取团员列表
     */
    public function getBuyerByMarkId($mark_id){
        if($mark_id < 0){
            return array();
        }
        $buyer_list = $this->getByWhere(array('mark_id'=>$mark_id));
        if(is_array($buyer_list)){
            foreach ($buyer_list as $key=>$value){
                $buyer_list[$key]['is_mark'] = $value['mark_id'] == $mark_id ? 1 : 0;
            }
        }
        return $buyer_list;
    }
    
    public function addInfo($field_row, $return_insert_id = false) {
        $result = $this->add($field_row,$return_insert_id);
        return $result;
    }
    
    public function editInfo($table_primary_key_value, $field_row, $flag=null) {
        $result = $this->edit($table_primary_key_value, $field_row, $flag);
        return $result;
    }
}
<?php

/**
 * Created by PhpStorm.
 * User: rd04
 * Date: 2017/11/13
 * Time: 15:49
 */
class PinTuan_Temp extends Yf_Model
{
    public $_cacheKeyPrefix  = 'c|pintuan_temp|';
    public $_cacheName       = 'pintuan_temp';
    public $_tableName       = 'pintuan_temp';
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
    
    
    public function addInfo($field_row, $return_insert_id = false) {
        $result = $this->add($field_row,$return_insert_id);
        return $result;
    }
    
    public function editInfo($table_primary_key_value, $field_row, $flag=null){
        $result = $this->edit($table_primary_key_value, $field_row, $flag);
        return $result;
    }
   
    /**
     * 
     * @param type $order_id
     * @param type $type == 'pintuan' 表示拼团订单， $type == 'alone'表示单独买 ，不传表示不做区分
     * @return boolean
     */
    public function getPtInfoByOrderId($order_id,$type=null){
        if($type){
            $where = $type == 'pintuan' ? array('order_id'=>$order_id,'type'=>0) : array('order_id'=>$order_id,'type'=>1);
        }else{
            $where = array('order_id'=>$order_id);
        }
        $temp = $this->getOneByWhere($where);
        if(!$temp){
            return false;
        }
        $pt_detail_model = new PinTuan_Detail();
        $pt_detail_info = $pt_detail_model->getOne($temp['detail_id']);
        $pt_base_model = new PinTuan_Base();
        $pt_base_info = $pt_base_model->getOne($pt_detail_info['pintuan_id']);
        $result = array(
            'temp'=>$temp,'base'=>$pt_base_info,'detail'=>$pt_detail_info
        );
        return $result;
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
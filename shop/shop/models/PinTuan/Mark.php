<?php

/**
 * Created by PhpStorm.
 * User: rd04
 * Date: 2017/11/13
 * Time: 15:49
 */
class PinTuan_Mark extends Yf_Model
{
    public $_cacheKeyPrefix  = 'c|pintuan_mark|';
    public $_cacheName       = 'pintuan_mark';
    public $_tableName       = 'pintuan_mark';
    public $_tablePrimaryKey = 'id';

    const STATUS_WAIT = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_FAILURE = 2;

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
    /**
     *  获取拼团信息
     *  $cond_row 查询条件
     *  $order_row 排序条件
     *  $page 页数
     *  $rows 每页数量
     */
    public function getMarkDetail($cond_row,$order_row=array('num'=>'desc'),$page=1,$rows=2){

        $mark_list = $this->listByWhere($cond_row,$order_row,$page,$rows);
        $mark_info = $mark_list['items'];
        if(!$mark_info){
            return array();
        }
        $buyer_model = new PinTuan_Buyer();
        $detail_model = new PinTuan_Detail();
        $base_model = new PinTuan_Base();
        $user_ids = array();
        foreach ($mark_info as $key=>$value){
            $user_ids[] = $value['user_id'];
            $mark_info[$key]['buyer_count'] = $buyer_model->getCount(array('mark_id'=>$value['id']));
            $detail_id = $value['detail_id'];
            $pintuan_detail = $detail_model->getDetail($detail_id);
        }
        $pintuan_base = $base_model->getBase(array('id'=>$pintuan_detail[$detail_id]['pintuan_id']));

        foreach($pintuan_base as $val)
        {
            $person_num = $val['person_num'];
            $end_time = $val['end_time'];
            $base = $val;
        }
        $user_model = new User_InfoModel();
        $user_info = $user_model->getInfo($user_ids);

        foreach ($mark_info as $key=>$val){
            $mark_info[$key]['user_name'] = $user_info[$val['user_id']]['user_name'];
            $mark_info[$key]['user_logo'] = $user_info[$val['user_id']]['user_logo'];
            $mark_info[$key]['person_num'] = $person_num;
            $mark_info[$key]['end_time'] = $end_time;
            $mark_info[$key]['lack'] = $person_num - $mark_info[$key]['buyer_count'];
            $mark_info[$key]['shop_user_id'] = $base['user_id'];
        }
        return $mark_info;
    }


    public function getMark($cond_row)
    {
        $rows = $this->getOneByWhere($cond_row);
        return $rows;
    }
    
    /**
     *  获取团信息
     */
    public function getOneMarkInfo($cond_row){
        $mark = $this->getOneByWhere($cond_row);
        $buyer_model = new PinTuan_Buyer();
        $buyer = $buyer_model->getByWhere(array('mark_id'=>$mark['id']));
        $mark['buyer_list'] = $buyer;
        return $mark;
    }
    
    public function editInfo($table_primary_key_value, $field_row, $flag=null) {
        $result = $this->edit($table_primary_key_value, $field_row, $flag);
        return $result;
    }
}
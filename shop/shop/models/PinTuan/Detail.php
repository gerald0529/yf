<?php

/**
 * Created by PhpStorm.
 * User: rd04
 * Date: 2017/11/13
 * Time: 15:47
 */
class PinTuan_Detail extends Yf_Model
{
    public $_cacheKeyPrefix  = 'c|pintuan_detail|';
    public $_cacheName       = 'pintuan_detail';
    public $_tableName       = 'pintuan_detail';
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
     * @param  int $config_key 主键值
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getDetail($id = null, $sort_key_row = null)
    {
        $rows = array();
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
    public function addDetail($field_row, $return_insert_id = false)
    {
        $add_flag = $this->add($field_row, $return_insert_id);

        return $add_flag;
    }

    /**
     * 根据主键更新表内容
     * @param mix $config_key 主键
     * @param array $field_row key=>value数组
     * @return bool $update_flag 是否成功
     * @access public
     */
    public function editDetail($id = null, $field_row, $flag = false)
    {
        $update_flag = $this->edit($id, $field_row, $flag);

        return $update_flag;
    }

    /**
     * 更新单个字段
     * @param mix $config_key
     * @param array $field_name
     * @param array $field_value_new
     * @param array $field_value_old
     * @return bool $update_flag 是否成功
     * @access public
     */
    public function editDetailSingleField($id, $field_name, $field_value_new, $field_value_old)
    {
        $update_flag = $this->editSingleField($id, $field_name, $field_value_new, $field_value_old);

        return $update_flag;
    }

    /**
     * 删除操作
     * @param int $config_key
     * @return bool $del_flag 是否成功
     * @access public
     */
    public function removeDetail($id)
    {
        $del_flag = $this->remove($id);
        return $del_flag;
    }

    //删除活动详情
    public function removePinTuanDetail($PinTuanId)
    {
        $cond['pintuan_id'] = $PinTuanId;
        $rows = $this->getByWhere($cond);
        $detailIds = array_column($rows, 'id');

        $flag = $this->removeDetail($detailIds);
        return $flag;
    }

    
    
    /**
     * 根据拼团list获取详情
     * @param type $list
     */
    public function getDetailByList($list=array()){
        $pt_id = array();
        if($list){
            foreach ($list as $value){
                $pt_id[] = $value['id'];
            }
            $pt_detail = $this->getByWhere(array('pintuan_id:IN'=>$pt_id));
            
            foreach ($pt_detail as $val){
                if($list[$val['pintuan_id']]){
                    $list[$val['pintuan_id']]['detail'] = $val;
                }
            }
            
        }
        return $list;
    }
    
    /**
     * 根据详情ID获取参团人数
     */
    public function getSaleNumsByDetailId($id){
        $where  = is_array($id) ? array('detail_id:IN'=>$id) : array('detail_id'=>$id);
        $buyer_model = new PinTuan_Buyer();
        $num = $buyer_model->getCount($where);
        return $num;
    }
    
    /**
     *  获取商品的参团量
     */
    public function getSaleNumsByList($list = array()){
        if(!$list){
            return $list;
        }
        $result = array();
        foreach ($list as $value){
            $result[$value['detail']['id']] = $this->getSaleNumsByDetailId($value['detail']['id']);
        }
        foreach ($list as $key=>$val){
            $list[$key]['detail']['buyer_num'] = $result[$list[$key]['detail']['id']] ? $result[$list[$key]['detail']['id']] : 0;
        }
        return $list;
    }
    


     //详情
    public function getPinTuanDetail($cond)
    {
        return $this->getByWhere($cond);
    }

    
    /**
     * 根据ID获取拼团活动详情
     * @param type $detail_id
     * @return type
     */
    public function getPtByDetailId($detail_id){
        if($detail_id < 0){
            return array();
        }
        $detail_info = $this->getOne($detail_id);
        $pt_model = new PinTuan_Base();
        $pt_info = $pt_model->getOne($detail_info['pintuan_id']);
        $pt_info['detail'] =  $detail_info;
        return $pt_info;
    }

    /**
     * 根据拼团goods_id获取详情
     * @param is_all 是否查询全部信息，包含mark信息，默认两条
     */
    public function getOneDetailInfo($detail_id,$is_all=true){
        $detail_info = $this->getOne($detail_id);
        if(!$detail_info['pintuan_id']){
            return array();
        }
        $pt_model = new PinTuan_Base();
        $pt_info = $pt_model->getOne($detail_info['pintuan_id']);
        $pt_info['detail'] = $detail_info;
        if($is_all){
            $ptMark_model = new PinTuan_Mark();
            $cond_row = array('detail_id'=>$detail_info['id'],'status'=>0);
            $ptMark_info = $ptMark_model->getMarkDetail($cond_row);
            $pt_info['mark'] = $ptMark_info;
        }
        return $pt_info;
    }

    /**
     * 根据拼团pintuan_id获取详情
     * @param type $list
     */
    public function getPinTuanDetailsById($ids)
    {
        $detail =  $this->getByWhere(array('pintuan_id:IN'=>$ids));
        $goods_ids = is_array($detail)?array_column($detail,'goods_id'):array();
        $goodsModel = new Goods_BaseModel();
        $goods_detail = $goodsModel->getGoodsDetailss(array('goods_id:IN'=>$goods_ids));
        foreach($detail as $key=>$value){
            $detail[$value]['goods_name'] = $goods_detail[$value['goods_id']]['goods_name'];
        }
        return $detail;
    }

}
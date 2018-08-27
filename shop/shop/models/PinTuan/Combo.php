<?php

/**
 * Created by PhpStorm.
 * User: rd04
 * Date: 2017/11/13
 * Time: 15:47
 */
class PinTuan_Combo extends Yf_Model
{
    public $_cacheKeyPrefix  = 'c|pintuan_combo|';
    public $_cacheName       = 'pintuan_combo';
    public $_tableName       = 'pintuan_combo';
    public $_tablePrimaryKey = 'combo_id';

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
    public function getCombo($id = null, $sort_key_row = null)
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
    public function addCombo($field_row, $return_insert_id = false)
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
    public function editCombo($id = null, $field_row, $flag = false)
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
    public function editComboSingleField($id, $field_name, $field_value_new, $field_value_old)
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
    public function removeCombo($id)
    {
        $del_flag = $this->remove($id);
        return $del_flag;
    }


    /**
     *根据店铺ID获取店铺活动套餐
     * @param $shop_id 店铺id
     * @return array
     */
    public function getPinTuanQuotaByShopID($shop_id)
    {
        return $this->getByWhere(array('shop_id' => $shop_id));
    }

    /*
     * 套餐续费
     * */
    public function renewPinTuanCombo($combo_id, $field_row)
    {
        return $this->edit($combo_id, $field_row);
    }

    /*
    * 购买套餐
    * */
    public function addPinTuanCombo($field_row, $return_insert_id)
    {
        return $this->add($field_row, $return_insert_id);
    }

    /*
    * 购买套餐列表
    * */
    public function getPinTuanList($cond_row)
    {
        $list = $this->getByWhere($cond_row);
        return $list;
    }
}
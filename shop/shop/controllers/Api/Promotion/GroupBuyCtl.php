<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    /**
     * Author: yesai
     * Date: 2016/5/19
     * Time: 9:26
     */
    class Api_Promotion_GroupBuyCtl extends Api_Controller
    {
        public $GroupBuy_BaseModel = null;
        public $GroupBuy_QuotaModel = null;
        public $GroupBuy_PriceRangeModel = null;
        public $GroupBuy_CatModel = null;
        public $GroupBuy_AreaModel = null;
        
        /**
         * Constructor
         *
         * @param  string $ctl 控制器目录
         * @param  string $met 控制器方法
         * @param  string $typ 返回数据类型
         *
         * @access public
         */
        public function __construct(&$ctl, $met, $typ)
        {
            parent ::__construct($ctl, $met, $typ);
            $this -> GroupBuy_BaseModel = new GroupBuy_BaseModel();
            $this -> GroupBuy_QuotaModel = new GroupBuy_QuotaModel();
            $this -> GroupBuy_PriceRangeModel = new GroupBuy_PriceRangeModel();
            $this -> GroupBuy_CatModel = new GroupBuy_CatModel();
            $this -> GroupBuy_AreaModel = new GroupBuy_AreaModel();
        }
        
        //团购商品列表
        public function getGroupBuyGoodsList()
        {
            $page = request_int('page', 1);
            $rows = request_int('rows', 100);
            $cond_row = array();
            $groupbuy_state = request_int('groupbuy_state');
            $groupbuy_name = trim(request_string('groupbuy_name'));   //团购活动名称
            $goods_name = trim(request_string('goods_name'));        //团购商品名称
            $shop_name = trim(request_string('shop_name'));         //店铺名称
            $group_class_id = request_int('group_class');
            if ($groupbuy_state) {
                $cond_row['groupbuy_state'] = $groupbuy_state;
            }
            if ($group_class_id) {
                $cond_row['groupbuy_cat_id'] = $group_class_id;
            }
            if ($groupbuy_name) {
                $cond_row['groupbuy_name:LIKE'] = '%' . $groupbuy_name . '%';
            }
            $goods_base = array();
            if ($goods_name) {
                $cond['goods_name:LIKE'] = '%' . $goods_name . '%';
                $Goods_BaseModel = new Goods_BaseModel();
                $goods_base = $Goods_BaseModel -> getByWhere($cond);
                $cond_row['goods_id:IN'] = array_column($goods_base, 'goods_id');
            }
            if ($shop_name) {
                $cond_row['shop_name:LIKE'] = '%' . $shop_name . '%';
            }
            $data = $this -> GroupBuy_BaseModel -> getGroupBuyGoodsList($cond_row, array('groupbuy_id' => 'DESC'), $page, $rows, 0, true);
            $data['goods_base'] = $goods_base;
            $this -> data -> addBody(-140, $data);
        }
        
        public function groupbuyManage()
        {
            $groupbuy_id = request_int('groupbuy_id');
            $data = $this -> GroupBuy_BaseModel -> getGroupBuyDetailByID($groupbuy_id);
            //查找推荐团购的个数
            $groupbuy_recommend = $this -> GroupBuy_BaseModel -> getByWhere(array('groupbuy_state' => GroupBuy_BaseModel::NORMAL, 'groupbuy_recommend' => GroupBuy_BaseModel::RECOMMEND));
            $groupbuy_count = count($groupbuy_recommend);
            if ($groupbuy_count < 5) {
                $data['groupbuy_recommend'] = GroupBuy_BaseModel::RECOMMEND;
            }
            $data['groupbuy_count'] = $groupbuy_count;
            $this -> data -> addBody(-140, $data);
        }
        
        public function editGroupBuy()
        {
            $groupbuy_id = request_int('groupbuy_id');
            $field_row['groupbuy_id'] = $groupbuy_id;
            $field_row['groupbuy_state'] = request_int('groupbuy_state');
            $field_row['groupbuy_recommend'] = request_int('groupbuy_recommend');
            $group_data = $this -> GroupBuy_BaseModel -> getGroupBuyDetailByID($groupbuy_id);
            //如果团购状态为结束、审核失败、关闭的话，则不能修改为正常和审核中
            $status_data = array(3, 4, 5);
            $get_status_data = array(3, 4, 5);
            if ((in_array($group_data['groupbuy_state'], $status_data) && in_array($field_row['groupbuy_state'], $get_status_data)) || !in_array($group_data['groupbuy_state'], $status_data)) {
                $flag = $this -> GroupBuy_BaseModel -> editGroupBuy($groupbuy_id, $field_row);
            } else {
                $field_row['groupbuy_state'] = $group_data['groupbuy_state'];
                $field_row['groupbuy_recommend'] = $group_data['groupbuy_state'];
                $flag = 0;
            }
            if ($flag) {
                $msg = __('success');
                $status = 200;
            } else {
                $msg = __('failure');
                $status = 250;
            }
            $data = $field_row;
            $data['groupbuy_state_label'] = GroupBuy_BaseModel ::$groupbuy_state_map[$field_row['groupbuy_state']];
            $data['groupbuy_recommend_label'] = GroupBuy_BaseModel ::$recommend_map[$field_row['groupbuy_recommend']];
            $data['groupbuy_id'] = $groupbuy_id;
            $this -> data -> addBody(-140, $data, $msg, $status);
        }
        
        //删除团购
        public function removeGroupBuyGoods()
        {
            $groupbuy_id = request_int('groupbuy_id');
            $info = $this -> GroupBuy_BaseModel -> getOne($groupbuy_id);
            if ($info['groupbuy_state'] == GroupBuy_BaseModel::UNDERREVIEW || $info['groupbuy_state'] == GroupBuy_BaseModel::NORMAL) {
                return $this -> data -> addBody(-140, array(), __('请结束后再删除'), 250);
            }
            $flag = $this -> GroupBuy_BaseModel -> removeGroupBuyGoods($groupbuy_id);
            if ($flag) {
                $msg = __('success');
                $status = 200;
            } else {
                $msg = __('failure');
                $status = 250;
            }
            $data = array();
            return $this -> data -> addBody(-140, $data, $msg, $status);
        }
        
        //团购分类
        public function getCat()
        {
            $cond_row = array();
            if (request_int('cat_type')) {
                $cond_row['groupbuy_cat_type'] = request_int('cat_type');
            }
            $groupbuy_cat_parent_id = request_int('nodeid', 0);
            $groupbuy_cat_deep = request_int('n_level', 0);
            $data = $this -> GroupBuy_CatModel -> getCatTree($groupbuy_cat_parent_id, false, $groupbuy_cat_deep);
            $this -> data -> addBody(-140, $data);
        }
        
        //分类管理
        public function getGroupBuyCatName()
        {
            $data_re = array();
            $groupbuy_cat_id = request_int('id');
            $data = $this -> GroupBuy_CatModel -> getOne($groupbuy_cat_id);
            if ($data) {
                $data_re['id'] = $groupbuy_cat_id;
                $data_re['groupbuy_cat_name'] = $data['groupbuy_cat_name'];
                $data_re['groupbuy_cat_type'] = $data['groupbuy_cat_type'];
                $data_re['groupbuy_cat_parent_id'] = $data['groupbuy_cat_parent_id'];
                $data_re['groupbuy_cat_type_label'] = GroupBuy_CatModel ::$cat_type_map[$data['groupbuy_cat_type']];
            }
            if ($data_re) {
                $msg = __('success');
                $status = 200;
            } else {
                $msg = __('failure');
                $status = 250;
            }
            $this -> data -> addBody(-140, $data_re, $msg, $status);
        }
        
        //增加分类
        public function addGroupBuyCat()
        {
            $data['groupbuy_cat_name'] = $field_row['groupbuy_cat_name'] = request_string('groupbuy_cat_name');
            $data['groupbuy_cat_parent_id'] = $field_row['groupbuy_cat_parent_id'] = request_int('parent_cat');
            $data['groupbuy_cat_sort'] = $field_row['groupbuy_cat_sort'] = request_int('groupbuy_cat_sort');
            $data['groupbuy_cat_type'] = $field_row['groupbuy_cat_type'] = request_int('groupbuy_cat_type');
            $data['groupbuy_cat_type_label'] = GroupBuy_CatModel ::$cat_type_map[request_int('groupbuy_cat_type')];
            if (!$data['groupbuy_cat_name']) {
                return $this -> data -> addBody(-140, array(), __('请输入分类名称'), 250);
            }
            if ($this -> checkGroupCatName($data['groupbuy_cat_name'])) {
                return $this -> data -> addBody(-140, array(), __('分类名称已存在'), 250);
            }
            $groupbuy_cat_id = $this -> GroupBuy_CatModel -> addGroupBuyCat($field_row, true);
            if ($groupbuy_cat_id) {
                $msg = __('success');
                $status = 200;
            } else {
                $msg = __('failure');
                $status = 250;
            }
            $data['id'] = $groupbuy_cat_id;
            $data_re['items'] = $data;
            $this -> data -> addBody(-140, $data_re, $msg, $status);
        }
        
        //编辑分类
        public function editGroupBuyCat()
        {
            $groupbuy_cat_id = request_string('groupbuy_cat_id');
            $field_row['groupbuy_cat_name'] = request_string('groupbuy_cat_name');
            $field_row['groupbuy_cat_sort'] = request_int('groupbuy_cat_sort');
            $flag = $this -> GroupBuy_CatModel -> editGroupBuyCat($groupbuy_cat_id, $field_row);
            if (!$field_row['groupbuy_cat_name']) {
                return $this -> data -> addBody(-140, array(), __('请输入分类名称'), 250);
            }
            if ($this -> checkGroupCatName($field_row['groupbuy_cat_name'], $groupbuy_cat_id)) {
                return $this -> data -> addBody(-140, array(), __('分类名称已存在'), 250);
            }
            if ($flag !== false) {
                $msg = __('success');
                $status = 200;
            } else {
                $msg = __('failure');
                $status = 250;
            }
            $data = $field_row;
            $data['id'] = $groupbuy_cat_id;
            $data['groupbuy_cat_id'] = $groupbuy_cat_id;
            $data_re['items'] = $data;
            $this -> data -> addBody(-140, $data_re, $msg, $status);
        }
        
        /**
         * 团购分类名称不能重复
         *
         * @param type $name
         * @param type $cate_id
         *
         * @return boolean
         */
        private function checkGroupCatName($name, $cate_id = false)
        {
            if ($cate_id) {
                $where = array('groupbuy_cat_name' => $name, 'groupbuy_cat_id!=' => $cate_id);
            } else {
                $where = array('groupbuy_cat_name' => $name);
            }
            $result = $this -> GroupBuy_CatModel -> getByWhere($where);
            return $result;
        }
        
        /**
         * 删除操作
         *
         * @access public
         */
        public function removeGroupBuyCat()
        {
            $groupbuy_cat_id = request_int('groupbuy_cat_id');
            $flag = $this -> GroupBuy_CatModel -> removeGroupBuyCat($groupbuy_cat_id);
            if ($flag) {
                $msg = __('success');
                $status = 200;
            } else {
                $m = $this -> GroupBuy_CatModel -> msg -> getMessages();
                $msg = $m ? $m[0] : __('failure');
                $status = 250;
            }
            $data['id'] = array($groupbuy_cat_id);
            $this -> data -> addBody(-140, $data, $msg, $status);
        }
        
        //虚拟团购地区
        public function getArea()
        {
            $district_parent_id = request_int('nodeid', 0);
            $district_level = request_int('n_level', 0);
            $data = $this -> GroupBuy_AreaModel -> getDistrictTree($district_parent_id, false, $district_level);
            $this -> data -> addBody(-140, $data);
        }
        
        public function getGroupBuyAreaName()
        {
            $data_re = array();
            $groupbuy_area_id = request_int('id');
            $data = $this -> GroupBuy_AreaModel -> getGroupBuyAreaByID($groupbuy_area_id);
            if ($data) {
                $data_re['id'] = $groupbuy_area_id;
                $data_re['groupbuy_area_name'] = $data['groupbuy_area_name'];
            }
            if ($data_re) {
                $msg = __('success');
                $status = 200;
            } else {
                $msg = __('failure');
                $status = 250;
            }
            $this -> data -> addBody(-140, $data_re, $msg, $status);
        }
        
        public function addGroupBuyArea()
        {
            $data['groupbuy_area_name'] = $field_row['groupbuy_area_name'] = request_string('groupbuy_area_name');
            $data['groupbuy_area_parent_id'] = $field_row['groupbuy_area_parent_id'] = request_int('parent_district');
            $groupbuy_area_id = $this -> GroupBuy_AreaModel -> addGroupBuyArea($field_row, true);
            if ($groupbuy_area_id) {
                $msg = __('success');
                $status = 200;
            } else {
                $msg = __('failure');
                $status = 250;
            }
            $data['id'] = $groupbuy_area_id;
            $data_re['items'] = $data;
            $this -> data -> addBody(-140, $data_re, $msg, $status);
        }
        
        public function editGroupBuyArea()
        {
            $id = request_int('groupbuy_area_id');
            $edit_data['groupbuy_area_name'] = request_string('groupbuy_area_name');
            $flag = $this -> GroupBuy_AreaModel -> editGroupBuyArea($id, $edit_data);
            if ($flag !== false) {
                $msg = __('success');
                $status = 200;
            } else {
                $msg = __('failure');
                $status = 250;
            }
            $data = $edit_data;
            $data['id'] = $id;
            $data['groupbuy_area_id'] = $id;
            $data_re['items'] = $data;
            $this -> data -> addBody(-140, $data_re, $msg, $status);
        }
        
        /**
         * 删除操作
         *
         * @access public
         */
        public function removeArea()
        {
            $groupbuy_area_id = request_int('groupbuy_area_id');
            $flag = $this -> GroupBuy_AreaModel -> removeGroupArea($groupbuy_area_id);
            if ($flag) {
                $msg = __('success');
                $status = 200;
            } else {
                $m = $this -> GroupBuy_AreaModel -> msg -> getMessages();
                $msg = $m ? $m[0] : __('failure');
                $status = 250;
            }
            $data['id'] = array($groupbuy_area_id);
            $this -> data -> addBody(-140, $data, $msg, $status);
        }
        
        //团购活动套餐类表
        public function getGroupBuyQuotaList()
        {
            $cond_row = array();
            $page = request_int('page', 1);
            $rows = request_int('rows', 100);
            $shop_name = request_string('shop_name');
            if ($shop_name) {
                $cond_row['shop_name:LIKE'] = $shop_name . '%';
            }
            $data = $this -> GroupBuy_QuotaModel -> getGroupBuyQuotaList($cond_row, array('combo_id' => 'DESC'), $page, $rows);
            $this -> data -> addBody(-140, $data);
        }
        
        //团购价格区间列表
        public function getPriceRangeList()
        {
            $data = $this -> GroupBuy_PriceRangeModel -> getPriceRangeList();
            $this -> data -> addBody(-140, $data);
        }
        
        //添加团购价格区间
        public function addPriceRange()
        {
            $data["range_name"] = request_string("range_name");
            $data["range_start"] = request_int("range_start");
            $data["range_end"] = request_int("range_end");
            if ($data) {
                $range_id = $this -> GroupBuy_PriceRangeModel -> addPriceRange($data);
            }
            if ($range_id) {
                $msg = __('success');
                $status = 200;
            } else {
                $msg = __('failure');
                $status = 250;
            }
            $data['range_id'] = $range_id;
            $data['id'] = $range_id;
            $this -> data -> addBody(-140, $data, $msg, $status);
        }
        
        //修改团购价格区间
        public function editPriceRange()
        {
            $range_id = request_int("range_id");
            $data["range_name"] = request_string("range_name");
            $data["range_start"] = request_int("range_start");
            $data["range_end"] = request_int("range_end");
            $data_rs = $data;
            $data_rs['range_id'] = $range_id;
            $this -> GroupBuy_PriceRangeModel -> editPriceRange($range_id, $data);
            $this -> data -> addBody(-140, $data_rs);
        }
        
        //删除团购价格区间
        public function removePriceRange()
        {
            $range_id = request_int('range_id');
            $flag = $this -> GroupBuy_PriceRangeModel -> removePriceRange($range_id);
            if ($flag) {
                $msg = __('success');
                $status = 200;
            } else {
                $msg = __('failure');
                $status = 250;
            }
            $data = array();
            $this -> data -> addBody(-140, $data, $msg, $status);
        }
        
        //获取团购分类
        public function getGroupBuyCatList()
        {
            $data = $this -> GroupBuy_CatModel -> getGroupBuyCatList();
            $this -> data -> addBody(-140, $data);
        }
        
        public function groupbuyClass()
        {
            $data = $this -> GroupBuy_CatModel -> getByWhere(array(), array());
            $data = array_values($data);
            $result = array();
            $result[0]['id'] = 0;
            $result[0]['name'] = "团购分类";
            foreach ($data as $key => $value) {
                $result[$key + 1]['id'] = $value['groupbuy_cat_id'];
                $result[$key + 1]['name'] = $value['groupbuy_cat_name'];
            }
            $this -> data -> addBody(-140, $result);
        }
    }

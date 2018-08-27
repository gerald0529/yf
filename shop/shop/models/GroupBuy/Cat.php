<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    /**
     * Created by PhpStorm.
     * User: Administrator
     * Date: 2016/5/20
     * Time: 15:42
     */
    class GroupBuy_Cat extends Yf_Model
    {
        public $_cacheKeyPrefix = 'c|groupbuy_cat|';
        public $_cacheName = 'groupbuy';
        public $_tableName = 'groupbuy_cat';
        public $_tablePrimaryKey = 'groupbuy_cat_id';
        
        /**
         * @param string $user  User Object
         *
         * @var   string $db_id 指定需要连接的数据库Id
         * @return void
         */
        public function __construct(&$db_id = 'shop', &$user = null)
        {
            $this -> _tableName = TABEL_PREFIX . $this -> _tableName;
            $this -> _cacheFlag = CHE;
            parent ::__construct($db_id, $user);
            $this -> treeAllKey = $this -> _cacheKeyPrefix . 'tree|all_data';
        }
        
        /*
        * 添加团购地区
        * */
        public function addGroupBuyCat($field_row, $return_insert_id)
        {
            $add_flag = $this -> add($field_row, $return_insert_id);
            $Cache = Yf_Cache ::create('groupbuy');
            $Cache -> remove($this -> treeAllKey);
            $groupbuy_cat_row = $this -> getOne($add_flag);
            $groupbuy_cat_parent_id = $groupbuy_cat_row['groupbuy_cat_parent_id'];
            if ($groupbuy_cat_parent_id != 0) {
                $groupbuy_cat_par_row = $this -> getOne($groupbuy_cat_parent_id);
                $groupbuy_cat_par_id = $groupbuy_cat_par_row['groupbuy_cat_parent_id'];
            } else {
                $groupbuy_cat_par_id = 0;
            }
            $cache_key = $this -> _cacheKeyPrefix . 'groupbuy_cat_parent_id|' . $groupbuy_cat_par_id;
            $Cache -> remove($cache_key);
            $cache_key = $this -> _cacheKeyPrefix . 'groupbuy_cat_parent_id|' . $groupbuy_cat_parent_id;
            $Cache -> remove($cache_key);
            return $add_flag;
        }
        
        /**
         * 根据分类父类id赌气子类信息,
         *
         * @param  int  $groupbuy_cat_parent_id 父id
         * @param  bool $recursive              是否子类信息
         * @param  int  $level                  当前层级
         *
         * @return array $rows 返回的查询内容
         * @access public
         */
        public function getCatTreeData($groupbuy_cat_parent_id = 0, $recursive = true, $level = 0)
        {
            $district_data = array();
            $level++;
            if (is_array($groupbuy_cat_parent_id)) {
                $cond_row = array('groupbuy_cat_parent_id:in' => $groupbuy_cat_parent_id);
                $cache_key = $this -> _cacheKeyPrefix . 'groupbuy_cat_parent_id|' . implode(':', $groupbuy_cat_parent_id);
            } else {
                $cond_row = array('groupbuy_cat_parent_id' => $groupbuy_cat_parent_id);
                $cache_key = $this -> _cacheKeyPrefix . 'groupbuy_cat_parent_id|' . $groupbuy_cat_parent_id;
            }
            //设置cache
            $Cache = Yf_Cache ::create('groupbuy');
            if ($district_rows = $Cache -> get($cache_key)) {
            } else {
                $district_rows = $this -> getByWhere($cond_row, array('groupbuy_cat_sort' => 'ASC'));
                //类似数据可以放到前端整理
                foreach ($district_rows as $key => $district_row) {
                    $district_row['parent_id'] = $district_row['groupbuy_cat_parent_id'];
                    $district_row['name'] = $district_row['groupbuy_cat_name'];
                    //for treegrid
                    $district_row['level'] = $level;
                    $district_row['district_level'] = $level;
                    $district_row['groupbuy_cat_type_label'] = GroupBuy_CatModel ::$cat_type_map[$district_row['groupbuy_cat_type']];
                    $district_row['cat_icon'] = 'ui-icon-star';
                    $district_row['expanded'] = false;
                    $district_row['loaded'] = false;
                    {
                        //判断是否有子节点
                        $rs = $this -> getCatChildId($district_row['groupbuy_cat_id'], false);
                        if ($rs) {
                            $district_row['is_leaf'] = false;
                        } else {
                            $district_row['is_leaf'] = true;
                        }
                        $district_rows[$key] = $district_row;
                    }
                }
                $Cache -> save($district_rows, $cache_key);
            }
            return $district_rows;
        }
        
        /**
         * 读取子类id
         *
         * @param  int   $groupbuy_cat_parent_id 主键值
         * @param  bools $recursive              是否递归查询
         *
         * @return array $rows 返回的查询内容
         * @access public
         */
        public function getCatChildId($groupbuy_cat_parent_id = 0, $recursive = true)
        {
            $district_data = array();
            if (is_array($groupbuy_cat_parent_id)) {
                $cond_row = array('groupbuy_cat_parent_id:in' => $groupbuy_cat_parent_id);
            } else {
                $cond_row = array('groupbuy_cat_parent_id' => $groupbuy_cat_parent_id);
            }
            $groupbuy_cat_id_row = $this -> getKeyByMultiCond($cond_row);
            if ($recursive && $groupbuy_cat_id_row) {
                $rs = call_user_func_array(array(
                    $this,
                    'getCatChildId'
                ), array(
                    $groupbuy_cat_id_row,
                    $recursive
                ));
                $groupbuy_cat_id_row = array_merge($groupbuy_cat_id_row, $rs);
            }
            return $groupbuy_cat_id_row;
        }
        
        /*
         * 编辑团购分类
         * */
        public function editGroupBuyCat($groupbuy_cat_id, $field_row)
        {
            $update_flag = $this -> edit($groupbuy_cat_id, $field_row);
            $Cache = Yf_Cache ::create('groupbuy');
            $Cache -> remove($this -> treeAllKey);
            $groupbuy_cat_row = $this -> getOne($groupbuy_cat_id);
            $groupbuy_cat_parent_id = $groupbuy_cat_row['groupbuy_cat_parent_id'];
            $cache_key = $this -> _cacheKeyPrefix . 'groupbuy_cat_parent_id|' . $groupbuy_cat_parent_id;
            $Cache -> remove($cache_key);
            return $update_flag;
        }
        
        /**
         * 删除团购分类
         *
         * @param int $district_id
         *
         * @return bool $del_flag 是否成功
         * @access public
         */
        public function removeGroupBuyCat($groupbuy_cat_id)
        {
            //判断是否有子类, 如果有,不允许删除
            $data_rows = $this -> getCatTreeData($groupbuy_cat_id, false);
            if ($data_rows) {
                $this -> msg -> setMessages(__('有子分类,不允许删除'));
                return false;
            }
            $groupbuy_cat_row = $this -> getOne($groupbuy_cat_id);
            $groupbuy_cat_parent_id = $groupbuy_cat_row['groupbuy_cat_parent_id'];
            $del_flag = $this -> remove($groupbuy_cat_id);
            $Cache = Yf_Cache ::create('groupbuy');
            $Cache -> remove($this -> treeAllKey);
            $cache_key = $this -> _cacheKeyPrefix . 'groupbuy_cat_parent_id|' . $groupbuy_cat_parent_id;
            $Cache -> remove($cache_key);
            return $del_flag;
        }
    }

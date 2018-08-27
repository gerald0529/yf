<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    /**
     * @author     Xinze <xinze@live.cn>
     */
    class Shop_GoodCatModel extends Shop_GoodCat
    {
        
        const ENABLE = 1;
        const DISABLE = 0;
        
        public static $shop_goods_cat_status = array(
            "0" => "否",
            "1" => "是"
        );
        
        public function getGoodCatList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
        {
            $cond_row['parent_id'] = 0;
            $data = $this->getByWhere($cond_row, $order_row);
            if ($data) {
                $shop_goods_cat_id = array_column($data, 'shop_goods_cat_id');
                
                $shop_goods_cat_id['parent_id:IN'] = $shop_goods_cat_id;
                $subclass_rows = $this->getByWhere($shop_goods_cat_id, $order_row);
                
                foreach ($subclass_rows as $keys => $subclass_row) {
                    $subclass_row['shop_goods_cat_statuscha'] = __(self::$shop_goods_cat_status[$subclass_row['shop_goods_cat_status']]);
                    
                    $data[$subclass_row['parent_id']]['subclass'][] = $subclass_row;
                    
                    //$data[$subclass_row['parent_id']]['shop_goods_cat_statuscha'] = __(self::$shop_goods_cat_status[$data[$subclass_row['parent_id']]['shop_goods_cat_status']]);
                }
                foreach ($data as $key => $val) {
                    $data[$key]['shop_goods_cat_statuscha'] = __(self::$shop_goods_cat_status[$val['shop_goods_cat_status']]);
                }
                
            } else {
                $data = array();
            }
            //$data['items'] = $data;
            
            return $data;
        }
        
        /**
         * 删除选中的分类，如果选中的有一级分类循环删除下面的子类
         *
         * @param  array $config_array 主键值
         *
         * @return array $rows 返回的查询内容
         * @access public
         */
        public function removeGoodSelectedCat($config_array = array())
        {
            
            foreach ($config_array as $key => $value) {
                $cat = $this->getOne($value);
                if ($cat['parent_id'] == "0") {
                    $flag = $this->removeGoodAllCat($value);
                } else {
                    $flag = $this->removeGoodCat($value);
                }
            }
            
        }
        
        /**
         * 删除一级分类以及下面的子分类
         *
         * @param  int $config_key 主键值
         *
         * @return array $rows 返回的查询内容
         * @access public
         */
        public function removeGoodAllCat($cat_id = null)
        {
            $cat_parent = $this->removeGoodCat($cat_id);
            $cond_row['parent_id'] = $cat_id;
            $subcat = $this->getByWhere($cond_row);
            //循环查询出下面的子分类 再一一删除
            if ($subcat) {
                foreach ($subcat as $key => $value) {
                    $sub_id = $value['shop_goods_cat_id'];
                    $data[] = $this->removeGoodCat($sub_id);
                }
            }
            $data['cat_parent'] = $cat_parent;
            return $data;
        }
        
        /**
         * 读取店铺一级分类
         *
         * @param  int $config_key 主键值
         *
         * @return array $rows 返回的查询内容
         * @access public
         */
        public function getGoodCatparent($cond_row, $order_row = array())
        {
            $cond_row['parent_id'] = 0;
            $data = $this->getByWhere($cond_row, $order_row);
            return $data;
        }
        
        /*获取店铺分类*/
        
        public function getShopCatList()
        {
            $condi['shop_id'] = Perm::$shopId;
            $condi['shop_goods_cat_status'] = Shop_GoodCatModel::ENABLE;
            
            $data = $this->getByWhere($condi);
            
            //拼接数据
            $result_data = array();
            
            foreach ($data as $key => $val) {
                if ($val['parent_id'] != 0) {
                    continue;
                }
                
                $shop_goods_cat_id = $val['shop_goods_cat_id'];
                $result_data[] = $val;
                unset($data[$key]);
                
                foreach ($data as $k => $v) {
                    if ($v['parent_id'] == $shop_goods_cat_id) {
                        $v['shop_goods_cat_name'] = '&nbsp;&nbsp;&nbsp;&nbsp;' . $v['shop_goods_cat_name'];
                        $result_data[] = $v;
                        unset($data[$k]);
                    }
                }
                
            }
            
            return $result_data;
        }
        
        /*public function createLevel ( &$shop_cat )
        {
            foreach ( $shop_cat as $key => $cat_data)
            {
                if ( $cat_data['parent_id'] == 0 )
                {
                    $shop_cat[$key]['level'] = 1;
                }
                else
                {
                    $leave = $this->findGCPCount($cat_data['parent_id'], $shop_cat);
                    $shop_cat[$key]['level'] = 1;
                }
            }
        }
    
        public function findGCPCount( $parent_id ,$cat_data, $leave = 2 )
        {
            $parent_data = $cat_data[$parent_id];
            if ($parent_data['parent_id'] != 0 )
            {
                $leave ++;
                $this->findGCPCount($parent_data['parent_id'], $cat_data, $leave);
            }
            else
            {
                return $leave;
            }
        }*/
    }

?>
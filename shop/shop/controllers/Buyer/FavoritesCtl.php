<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    /**
     * @author     Xinze <xinze@live.cn>
     */
    class Buyer_FavoritesCtl extends Buyer_Controller
    {
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
            $this -> userFavoritesGoodsModel = new User_FavoritesGoodsModel();
            $this -> goodsBaseModel = new Goods_BaseModel();
            $this -> goodsCommonModel = new Goods_CommonModel();
            $this -> shopBaseModel = new Shop_BaseModel();
            $this -> goodsCatModel = new Goods_CatModel();
            $this -> userFavoritesShopModel = new User_FavoritesShopModel();
            $this -> userFootprintModel = new User_FootprintModel();
        }
        
        /**
         *收藏商品信息
         *
         * @access public
         */
        public function favoritesGoods()
        {
            $Yf_Page = new Yf_Page();
            $Yf_Page -> listRows = request_int('listRows') ? request_int('listRows') : 18;
            $rows = $Yf_Page -> listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $rows);
            $page_nav = '';
            $user_id = Perm ::$userId;
            $cond_row['user_id'] = $user_id;
            $data = $this -> userFavoritesGoodsModel -> getFavoritesGoodsDetail($cond_row, array('favorites_goods_time' => 'DESC'), $page, $rows);
            if ($data) {
                //判断是否是虚拟商品或分销商品
                foreach ($data['items'] as $key => $val) {
                    //获取common_id
                    $goods_base = $this -> goodsBaseModel -> getOne($val['goods_id']);
                    //获取common信息
                    $goods_common = $this -> goodsCommonModel -> getOne($goods_base['common_id']);
                    // $val['goods_image'] = $goods_common['common_image'];
                    if ($goods_common['common_is_virtual'] || $goods_common['product_distributor_flag']) {
                        $data['items'][$key]['is_virtual'] = true;
                    } else {
                        $data['items'][$key]['is_virtual'] = false;
                    }
                    // 把goods_common表的common_image值赋值给$data['items'][$key]['detail']['goods_image'];
                    $data['items'][$key]['detail']['goods_image'] = $goods_common['common_image'];
                }
                $Yf_Page -> totalRows = $data['totalsize'];
                $page_nav = $Yf_Page -> prompt();
            }
            if ('json' == $this -> typ) {
                return $this -> data -> addBody(-140, $data);
            } else {
                include $this -> view -> getView();
            }
        }
        
        /**
         *删除收藏商品信息
         *
         * @access public
         */
        public function delFavoritesGoods()
        {
            $userId = Perm ::$userId;
            $goods_id = request_int('id');
            $order_row['user_id'] = $userId;
            $order_row['goods_id'] = $goods_id;
            $de = $this -> userFavoritesGoodsModel -> getFavoritesGoods($order_row);
            $favorites_goods_id = $de['favorites_goods_id'];
            $flag = $this -> userFavoritesGoodsModel -> removeGoods($favorites_goods_id);
            if ($flag === false) {
                $status = 250;
                $msg = __('failure');
            } else {
                //修改商品人气(收藏数)
                $Goods_Model = new Goods_BaseModel();
                $favorites_goods_id = $de['favorites_goods_id'];
                $common = $Goods_Model -> getCommonInfo($goods_id);
                if ($common['common_collect'] > 0) {
                    $Goods_CommonModel = new Goods_CommonModel();
                    $edit_common_row = array();
                    $edit_common_row['common_collect'] = -1;
                    $result = $Goods_CommonModel -> editCommonTrue($common['common_id'], $edit_common_row);
                }
                $status = 200;
                $msg = __('success');
                //删除收藏商品成功添加数据到统计中心
                $analytics_data = array(
                    'product_id' => $goods_id,
                );
                Yf_Plugin_Manager ::getInstance() -> trigger('analyticsProductCancleCollect', $analytics_data);
            }
            $data = array();
            return $this -> data -> addBody(-140, $data, $msg, $status);
        }
        
        /**
         *收藏店铺信息
         *
         * @access public
         */
        public function favoritesShop()
        {
            $Yf_Page = new Yf_Page();
            $Yf_Page -> listRows = request_int('listRows') ? request_int('listRows') : 10;
            $rows = $Yf_Page -> listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $rows);
            $user_id = Perm ::$userId;
            $cond_row['user_id'] = $user_id;
            $data = $this -> userFavoritesShopModel -> getFavoritesShops($cond_row, array('favorites_shop_time' => 'DESC'), $page, $rows);
            if ($data['items']) {
                foreach ($data['items'] as $k => $v) {
                    if ($v['shop_logo']) {
                        $data['items'][$k]['shop_logo'] = $v['shop_logo'];
                    } else {
                        $data['items'][$k]['shop_logo'] = Web_ConfigModel ::value('photo_shop_head_logo');
                    }
                }
            }
            $Yf_Page -> totalRows = $data['totalsize'];
            $page_nav = $Yf_Page -> prompt();
            if ('json' == $this -> typ) {
                /*
                $shop_id_row = array_column($data['items'], 'shop_id');
    
                //获取单个店铺数据
                $Goods_CommonModel = new Goods_CommonModel();
                $goods_num   = $Goods_CommonModel->getCommonStateNum($data['items']['shop_id'], -1);
    
                $data['items']['shop_collect'] = $goods_num;
                */
//			echo '<pre>';print_r($data);exit;
                $this -> data -> addBody(-140, $data);
            } else {
                include $this -> view -> getView();
            }
        }
        
        /**
         *删除收藏店铺信息
         *
         * @access public
         */
        public function delFavoritesShop()
        {
            $userId = Perm ::$userId;
            $shop_id = request_int('id');
            $cond_row['user_id'] = $userId;
            $cond_row['shop_id'] = $shop_id;
            $de = $this -> userFavoritesShopModel -> getFavoritesShop($cond_row);
            if ('json' == request_string('typ')) {
                $favorites_shop_id = $de['favorites_shop_id'];
            } else {
                $favorites_shop_id = $de[0];
            }
            $flag = $this -> userFavoritesShopModel -> removeShop($favorites_shop_id);
            if ($flag === false) {
                $status = 250;
                $msg = __('failure');
            } else {
                //维护shop_base冗余字段 shop_collect
                $this -> shopBaseModel -> editBaseCollectNum($shop_id, ['shop_collect' => -1], true);
                $status = 200;
                $msg = __('success');
                //取消店铺收藏成功添加数据到统计中心
                $analytics_data = array(
                    'shop_id' => $shop_id,
                    'date' => date('Y-m-d'),
                );
                Yf_Plugin_Manager ::getInstance() -> trigger('analyticsShopCancleCollect', $analytics_data);
            }
            $data = array();
            $this -> data -> addBody(-140, $data, $msg, $status);
        }
        
        /**
         *个人足迹信息 - 按照日期分页
         *
         * @access public
         */
        public function footprint()
        {
            $Yf_Page = new Yf_Page();
            $Yf_Page -> listRows = request_int('listRows') ? request_int('listRows') : 5;
            $rows = $Yf_Page -> listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $rows);
            $user_id = Perm ::$userId;
            $order_row['user_id'] = $user_id;
            $classid = request_int('classid');
            if ($classid) {
                $goodsid['cat_id'] = $classid;
                $goodsall = $this -> goodsCommonModel -> getGoodsByCommonId($goodsid);
                $commonid = array_column($goodsall['items'], 'common_id');
                $order_row['common_id:in'] = $commonid;
            }
            $data = $this -> userFootprintModel -> getFootprintList($order_row, array('footprint_time' => 'DESC'), $page, $rows);
            fb($data);
            $page_nav = '';
            $arr = array();
            $cat = array();
            if ($data['items']) {
                $items = array();
                foreach ($data['items'] as $key => $val) {
                    //查找该天浏览的商品
                    $order_row = array();
                    $order_row['user_id'] = $user_id;
                    $order_row['footprint_date'] = $val['footprint_date'];
                    if ($classid) {
                        $order_row['common_id:in'] = $commonid;
                    }
                    $foot = array();
                    $foot = $this -> userFootprintModel -> getByWhere($order_row);
                    $goodsid = array();
                    $goodsid['common_id:in'] = array_column($foot, 'common_id');
                    if ($classid) {
                        $goodsid['cat_id'] = $classid;
                    }
                    $goods = array();
                    $goods = $this -> goodsCommonModel -> getGoodsByCommonId($goodsid, array(), false);
                    fb($goods);
                    if ($foot) {
                        foreach ($foot as $k => $v) {
                            $foot[$k]['goods'] = $goods[$v['common_id']];
                        }
                    }
                    fb($foot);
                    $items[$val['footprint_date']] = array_values($foot);
                }
                $data['items'] = $items;
                fb($data);
                fb('data');
                //计算足记商品的分类
                //查找出所有足记商品的common_id
                $goods_common = $this -> userFootprintModel -> getFootGoodCommonId();
                $commonid = array_column($goods_common, 'common_id');
                fb($goods_common);
                $cond['common_id:IN'] = $commonid;
                $commonall = $this -> goodsCommonModel -> getGoodsByCommonId($cond);
                fb($commonall);
                if (!empty($commonall['items'])) {
                    $cat_id = array_column($commonall['items'], 'cat_id');
                    $cat_id = array_unique($cat_id);
                    foreach ($cat_id as $k => $v) {
                        $cat_name = $this -> goodsCatModel -> getNameByCatid($v);
                        if ($cat_name != '未分组') {
                            $cat[$k]['cat_name'] = $cat_name;
                            $cat[$k]['cat_id'] = $v;
                        }
                    }
                }
                fb($cat);
                fb('cat');
                $Yf_Page -> totalRows = $data['totalsize'];
                $page_nav = $Yf_Page -> prompt();
            }
            if ('json' == $this -> typ) {
                $data = array();
                $data['cat'] = $cat;
                foreach ($arr['items'] as $akey => $aval) {
                    if (is_array($aval)) {
                        $aval = array_values($aval);
                        $arr['items'][$akey] = $aval;
                    }
                    foreach ($aval as $ake => $ava) {
                        if (is_array($ava)) {
                            $ava = array_values($ava);
                            $arr['items'][$akey][$ake] = $ava;
                        }
                    }
                }
                $this -> data -> addBody(-140, $data);
            } else {
                include $this -> view -> getView();
            }
        }
        
        /**
         *个人足迹信息  wap
         *
         * @access public
         */
        public function footprintwap()
        {
            $Yf_Page = new Yf_Page();
            $Yf_Page -> listRows = $size = request_int('listRows') ? request_int('listRows') : 10;
            $page = (int)$_REQUEST['curpage'];
            $user_id = Perm ::$userId;
            $order_row = array();
            $order_row['user_id'] = $user_id;
            $data = $this -> userFootprintModel -> getFootprintList($order_row, array('footprint_time' => 'DESC'), $page, $size, '');
            if (!$data['items']) {
                $data['arr']['items'] = [];
                return $this -> data -> addBody(-140, $data);
            }
            $goodsid = array();
            $cond = array_column($data['items'], 'common_id');
            $goodsid['common_id:in'] = $cond;
            $tb = TABEL_PREFIX . "goods_common";
            $order_by = implode(",", $cond);
            $condition = " where common_id in (" . $order_by . ")";
            $sql = "select * from " . $tb . " " . $condition . " order by field(common_id," . $order_by . ")";
            $goods_cat = $this -> goodsCommonModel -> sql -> getAll($sql);
            foreach ($goods_cat as $key => $value) {
                $goods_cat[$key]['goods_id'] = json_decode($value['goods_id'], true)[0]['goods_id'];
            }
            $data['arr']['items'] = $goods_cat;
            $page_nav = '';
            $arr = array();
            $cat = array();
            $data['hasmore'] = $page >= $data['total'] ? false : true;
            return $this -> data -> addBody(-140, $data);
        }
        
        /**
         *个人足迹信息 - 按照商品分页
         *
         * @access public
         */
        public function footprintGoods()
        {
            $Yf_Page = new Yf_Page();
            $Yf_Page -> listRows = request_int('listRows') ? request_int('listRows') : 4;
            $rows = $Yf_Page -> listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $rows);
            $user_id = Perm ::$userId;
            $order_row['user_id'] = $user_id;
            $data = $this -> userFootprintModel -> getFootprintList($order_row, array('footprint_time' => 'DESC'), $page, $rows);
            fb($data);
            fb('data1');
            if ($data['items']) {
                foreach ($data['items'] as $key => $val) {
                    $goods_common = $this -> goodsCommonModel -> getOne($val['common_id']);
                    $data['items'][$key]['goods_common'] = $goods_common;
                }
            }
            $Yf_Page -> totalRows = $data['totalsize'];
            $page_nav = $Yf_Page -> prompt();
            $this -> data -> addBody(-140, $data);
        }
        
        /**
         *删除足迹信息
         *
         * @access public
         */
        public function delFootprint()
        {
            $userId = Perm ::$userId;
            $footprint_date = request_string('time');
            fb($footprint_date);
            fb('footprint_time');
            $order_row['user_id'] = $userId;
            if ($footprint_date) {
                $order_row['footprint_date'] = $footprint_date;
            }
            $de = $this -> userFootprintModel -> getFootprintAll($order_row);
            //开启事物
            $rs_row = array();
            $this -> userFootprintModel -> sql -> startTransactionDb();
            $footprint_ids = array_column($de, 'footprint_id');
            $flag = $this -> userFootprintModel -> removeFootprint($footprint_ids);
            check_rs($flag, $rs_row);
            $flag = is_ok($rs_row);
            if ($flag !== false && $this -> userFootprintModel -> sql -> commitDb()) {
                $status = 200;
                $msg = __('success');
            } else {
                $this -> userFootprintModel -> sql -> rollBackDb();
                $status = 250;
                $msg = __('failure');
            }
            $data = array();
            $this -> data -> addBody(-140, $data, $msg, $status);
        }
        
        /**
         *
         * 判断商品是否被收藏 wap
         */
        public function getGoodsFI()
        {
            $goods_id = request_int('fav_id');
            $user_id = Perm ::$userId;
            $fav_result = $this -> userFavoritesGoodsModel -> getByWhere(array('user_id' => $user_id, 'goods_id' => $goods_id));
            $data = array();
            if (!empty($fav_result)) {
                $data['favorites_info'] = pos($fav_result);
                $status = 200;
                $msg = __('success');
            } else {
                $status = 250;
                $msg = __('failure');
            }
            $this -> data -> addBody(-140, $data, $msg, $status);
        }
    }

?>

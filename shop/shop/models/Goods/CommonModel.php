<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    /**
     * @author     Yf <service@yuanfeng.cn>
     */
    class Goods_CommonModel extends Goods_Common
    {
        const GOODS_STATE_NORMAL = 1;  //正常
        const GOODS_STATE_OFFLINE = 0;  //下架
        const GOODS_STATE_ILLEGAL = 10; //违规下架-禁售
        const GOODS_FROM_OUTSIDEIMPORT = 2;   //外部导入
        const GOODS_FORM_SHOP = 1;//正常添加
        const GOODS_STATE_TIMING = 2;   //定时发布
        const GOODS_STATE_WAITING_ADD = 2;   //外部导入商品待上架
        const GOODS_VIRTUAL = 1;   //虚拟商品
        const GOODS_NORMAL = 0;   //实物商品
        const GOODS_VIRTUAL_REFUND = 1;   //支持过期退款
        const GOODS_NO_ALARM = 0;  //不需要预警
        const RECOMMEND_TRUE = 2;
        const RECOMMEND_FALSE = 1;
        const GOODS_VERIFY_ALLOW = 1;
        const GOODS_VERIFY_DENY = 0;  //通过
        const GOODS_VERIFY_WAITING = 10;  //未通过
        const CONTRACT_USE = 1; //审核中
        const IS_DEL_YES = 2;//商品删除
        const IS_DEL_NO = 1; //商品没有删除
        public static $stateMap = array(
            '0' => '下架',
            '1' => '正常',
            '10' => '违规（禁售）'
        );
        public static $verifyMap = array(
            '0' => '未通过',
            '1' => '通过',
            '10' => '待审核'
        );
        
        /**
         * 读取分页列表
         *
         * @param  int $common_id 主键值
         *
         * @return array $rows 返回的查询内容
         * @access public
         */
        public function getCommonList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
        {
            $cond_row['is_del'] = self::IS_DEL_NO;
            return $this -> listByWhere($cond_row, $order_row, $page, $rows);
        }
        
        /**
         * 读取分页列表
         *
         * @param  int $common_id 主键值
         *
         * @return array $rows 返回的查询内容
         * @access public
         */
        public function getCommonNormal($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
        {
            $cond_row['shop_id'] = Perm::$shopId;
            $cond_row['common_state'] = Goods_CommonModel::GOODS_STATE_NORMAL;
            $cond_row['is_del'] = self::IS_DEL_NO;
            return $this -> listByWhere($cond_row, $order_row, $page, $rows);
        }
        
        public function getCommonOffline($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
        {
            $cond_row['shop_id'] = Perm::$shopId;
            // $cond_row['common_state'] = Goods_CommonModel::GOODS_STATE_OFFLINE; //下架
            $cond_row['common_goods_from'] = 1; //不是外部导入商品(外部导入商品上架后属于正常商品，撕掉外部导入标签)
            $cond_row['is_del'] = self::IS_DEL_NO;
            if (Web_ConfigModel::value('goods_verify_flag') == 1) {
                $cond_row['common_verify'] = Goods_CommonModel::GOODS_VERIFY_ALLOW; //审核通过
            }
            return $this -> listByWhere($cond_row, $order_row, $page, $rows);
        }
        
        public function getCommonIllegal($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
        {
            $cond_row['shop_id'] = Perm::$shopId;
            $cond_row['common_state'] = Goods_CommonModel::GOODS_STATE_ILLEGAL;
            $cond_row['is_del'] = self::IS_DEL_NO;
            return $this -> listByWhere($cond_row, $order_row, $page, $rows);
        }
        
        public function getCommonVerifyWaiting($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
        {
            $cond_row['shop_id'] = Perm::$shopId;
            $cond_row['common_state:IN'] = [Goods_CommonModel::GOODS_STATE_OFFLINE, Goods_CommonModel::GOODS_STATE_NORMAL, Goods_CommonModel::GOODS_STATE_TIMING];
            $cond_row['common_verify'] = Goods_CommonModel::GOODS_VERIFY_WAITING;
            $cond_row['is_del'] = self::IS_DEL_NO;
            return $this -> listByWhere($cond_row, $order_row, $page, $rows);
        }
        
        public function getCommonVerifyDeny($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
        {
            $cond_row['shop_id'] = Perm::$shopId;
            $cond_row['common_state:IN'] = [Goods_CommonModel::GOODS_STATE_OFFLINE, Goods_CommonModel::GOODS_STATE_NORMAL]; //下架和上架两种状态
            $cond_row['common_verify'] = Goods_CommonModel::GOODS_VERIFY_DENY;
            $cond_row['is_del'] = self::IS_DEL_NO;
            return $this -> listByWhere($cond_row, $order_row, $page, $rows);
        }
        
        public function getCommonOutsideImport($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
        {
            $cond_row['shop_id'] = Perm::$shopId;
            $cond_row['common_goods_from'] = Goods_CommonModel::GOODS_FROM_OUTSIDEIMPORT; //外部导入商品
            $cond_row['common_verify'] = Goods_CommonModel::GOODS_VERIFY_ALLOW; //审核通过
            $cond_row['is_del'] = self::IS_DEL_NO;
            return $this -> listByWhere($cond_row, $order_row, $page, $rows);
        }
        
        /**
         * 取得各种下拉框
         *
         * @access public
         */
        public function getStateCombo()
        {
            $data = array();
            foreach (Goods_CommonModel::$stateMap as $id => $name) {
                $row = array();
                $row['id'] = $id;
                $row['name'] = $name;
                $data['goods_state'][] = $row;
            }
            foreach (Goods_CommonModel::$verifyMap as $id => $name) {
                $row = array();
                $row['id'] = $id;
                $row['name'] = $name;
                $data['goods_verify'][] = $row;
            }
            //goods type
            $Goods_TypeModel = new Goods_TypeModel();
            $goods_type_rows = $Goods_TypeModel -> getByWhere();
            if ($goods_type_rows) {
                $row = array();
                foreach ($goods_type_rows as $goods_type_row) {
                    $row = array();
                    $row['id'] = $goods_type_row['type_id'];
                    $row['name'] = $goods_type_row['type_name'];
                    $data['goods_type'][] = $row;
                }
            }
            return $data;
        }
        
        /**
         * 读取分页列表+goods_id
         *
         * @param  int $common_id 主键值
         *
         * @return array $rows 返回的查询内容
         * @access public
         */
        public function getGoodsIdList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
        {
            $cond_row['is_del'] = self::IS_DEL_NO;
            $common_list = $this -> getByWhere($cond_row, $order_row, $page, $rows);
            $Goods_BaseModel = new Goods_BaseModel();
            foreach ($common_list as $key => $value) {
                //这里随便取一个goods_id 因为多个good_id 对应的都是那个产品
                $goods_cond_row['common_id'] = $value['common_id'];
                $goods_cond_row['shop_id'] = $value['shop_id'];
                $goods_cond_row['goods_is_shelves'] = Goods_BaseModel::GOODS_UP;
                $goods_cond_row['is_del'] = $Goods_BaseModel::IS_DEL_NO;
                $goods_list = $Goods_BaseModel -> getOneByWhere($goods_cond_row);
                if ($goods_list) {
                    $common_list[$key]["goods_id"] = $goods_list['goods_id'];
                } else {
                    // $common_list['items'][$key]["goods_id"] = 0;
                    //若此common_id没有商品则删除此数组
                    unset($common_list[$key]);
                }
            }
            $total = ceil_r(count($common_list) / $rows);
            $start = ($page - 1) * $rows;
            $data_rows = array_slice($common_list, $start, $rows, true);
            $arr = array();
            $arr['page'] = $page;
            $arr['total'] = $total;  //total page
            $arr['totalsize'] = count($common_list);
            $arr['records'] = count($data_rows);
            $arr['items'] = array_values($data_rows);
            return $arr;
        }
        
        //获取正常状态的商品goods_base 列表
        public function getNormalSateGoodsBase($cond_common_row, $order_row, $page, $rows)
        {
            $cond_common_row['common_state'] = Goods_CommonModel::GOODS_STATE_NORMAL;
            $cond_common_row['common_verify'] = Goods_CommonModel::GOODS_VERIFY_ALLOW;
            $cond_common_row['shop_status'] = Shop_BaseModel::SHOP_STATUS_OPEN;
            $cond_common_row['is_del'] = self::IS_DEL_NO;
            $common_id_rows = $this -> getKeyByWhere($cond_common_row);
            $Goods_BaseModel = new Goods_BaseModel();
            $cond_base_row['common_id:IN'] = $common_id_rows;
            $cond_base_row['goods_is_shelves'] = Goods_BaseModel::GOODS_UP;
            $cond_base_row['is_del'] = $Goods_BaseModel::IS_DEL_NO;
            $goods_base_rows = $Goods_BaseModel -> getGoodsSpecByGoodsId($cond_base_row, $order_row, $page, $rows);
            //排除拼团商品
            $PinTuan_BaseModel = new PinTuan_Base();
            foreach ($goods_base_rows['items'] as $k => $v) {
                $flag = $PinTuan_BaseModel -> isPinTuanGoods($v['goods_id']);
                if ($flag) {
                    $goods_base_rows['items'][$k]['is_join'] = 'true';
                } else {
                    $goods_base_rows['items'][$k]['is_join'] = 'false';
                }
                if (is_array($v['spec'])) {
                    foreach ($v['spec'] as $key => $value) {
                        $goods_base_rows['items'][$k]['spec_title'] .= $key . '：' . $value . '；';
                    }
                }
            }
            return $goods_base_rows;
        }
        //状态正常的商品，团购需要
        // Author ye
        public function getNormalStateGoodsCommon($common_id, $cond_row = array())
        {
            if (is_array($common_id)) {
                $cond_row['common_id:IN'] = $common_id;
            } else {
                $cond_row['common_id'] = $common_id;
            }
            $cond_row['common_state'] = Goods_CommonModel::GOODS_STATE_NORMAL;
            $cond_row['common_verify'] = Goods_CommonModel::GOODS_VERIFY_ALLOW;
            $cond_row['shop_status'] = Shop_BaseModel::SHOP_STATUS_OPEN;
            $cond_row['is_del'] = self::IS_DEL_NO;
            $common_rows = $this -> getByWhere($cond_row);
            return $common_rows;
        }
        
        /**
         * 根据common_id读取其中一个状态正常的 goods_id
         *
         * @  param  int $common_id 主键值
         *
         * @return array $rows 返回的查询内容
         * @access public
         * Author ye
         */
        public function getNormalStateGoodsId($common_id)
        {
            $cond_row['common_id'] = $common_id;
            $cond_row['common_state'] = Goods_CommonModel::GOODS_STATE_NORMAL;
            $cond_row['common_verify'] = Goods_CommonModel::GOODS_VERIFY_ALLOW;
            $cond_row['shop_status'] = Shop_BaseModel::SHOP_STATUS_OPEN;
            $cond_row['is_del'] = self::IS_DEL_NO;
            $common_row = $this -> getOneByWhere($cond_row);
            $goods_id = null;
            if ($common_row) {
                $Goods_BaseModel = new Goods_BaseModel();
                //这里随便取一个goods_id 因为多个good_id 对应的都是那个产品
                $goods_cond_row['common_id'] = $common_row['common_id'];
                $goods_cond_row['shop_id'] = $common_row['shop_id'];
                $goods_cond_row['goods_is_shelves'] = Goods_BaseModel::GOODS_UP;
                $goods_cond_row['is_del'] = Goods_BaseModel::IS_DEL_NO;
                $goods_row = $Goods_BaseModel -> getOneByWhere($goods_cond_row);
                if ($goods_row) {
                    $goods_id = $goods_row['goods_id'];
                }
            }
            return $goods_id;
        }
        
        /**
         * 读取商品,
         *
         * @param  int    $common_id 主键值
         * @param  string $type      SKU  SPU
         *
         * @return array $rows 返回的查询内容
         * @access public
         */
        public function getGoodsList($cond_row = array(), $order_row = array(), $page = 1, $rows = 10, $property_value_row = array(),$del_flg = true)
        {
            if($del_flg == true){
                $cond_row['is_del'] = self::IS_DEL_NO;
            }

            $type = 'SKU';
            //判断辅助属性, left join
            if ($property_value_row) {
                $sql = "
                    SELECT
                        SQL_CALC_FOUND_ROWS c.*, gp.goods_id
                    FROM
                        " . TABEL_PREFIX . "goods_common c LEFT OUTER JOIN " . TABEL_PREFIX . "goods_property_index gp ON c.common_id = gp.common_id AND c.common_verify=1 AND c.common_state=1 AND c.shop_status=3
                    ";
                //需要分页如何高效，易扩展
                $offset = $rows * ($page - 1);
                $this -> sql -> setLimit($offset, $rows);
                if ($cond_row) {
                    foreach ($cond_row as $k => $v) {
                        $k_row = explode(':', $k);
                        if (count($k_row) > 1) {
                            $this -> sql -> setWhere('c.' . $k_row[0], $v, $k_row[1]);
                        } else {
                            $this -> sql -> setWhere('c.' . $k, $v);
                        }
                    }
                } else {
                }
                if ($order_row) {
                    foreach ($order_row as $k => $v) {
                        $this -> sql -> setOrder('c.' . $k, $v);
                    }
                }
                $limit = $this -> sql -> getLimit();
                $where = $this -> sql -> getWhere();
                $where = $where . " AND gp.goods_id IS NOT NULL AND gp.goods_is_shelves AND  gp.property_value_id IN (" . implode(', ', $property_value_row) . ") and (common_is_virtual=0 OR (common_is_virtual=1 AND common_virtual_date > '" . date('Y-m-d') . "'))";
                $order = $this -> sql -> getOrder();
                $sql = $sql . $where . $order . $limit;
                $common_rows = $this -> sql -> getAll($sql);
                //读取影响的函数, 和记录封装到一起.
                $total = $this -> getFoundRows();
                $common_data = array();
                $common_data['page'] = $page;
                $common_data['total'] = ceil_r($total / $rows);  //total page
                $common_data['totalsize'] = $total;
                $common_data['records'] = count($common_rows);
                $common_data['items'] = $common_rows;
            } else {
                $vr_where = $cond_row;
                $vr_where['common_is_virtual'] = 1;
                $vr_where['common_virtual_date:<'] = date('Y-m-d');
                $vr_common_ids_res = $this -> getByWhere($vr_where);
                if ($vr_common_ids_res) {
                    $vr_common_ids = array_column($vr_common_ids_res, 'common_id');
                    $cond_row['common_id:NOT IN'] = $vr_common_ids; //过期的虚拟商品
                }
                $common_data = $this -> listByWhere($cond_row, $order_row, $page, $rows, false);
                $common_rows = $common_data['items'];
            }
            //同步店铺名称
            $shop_base_model = new Shop_BaseModel();
            foreach ($common_rows as $key => $val) {
                $shop_base = $shop_base_model -> getOne($val['shop_id']);
                $common_rows[$key]['shop_name'] = $shop_base['shop_name'];
            }
            
            if ('SKU' == $type) {
                $common_ids = array_column($common_rows, 'common_id');
                if ($common_ids) {
                    $Goods_BaseModel = new Goods_BaseModel();
                    $goods_cond_row['common_id:IN'] = $common_ids;
                    $goods_cond_row['goods_is_shelves'] = Goods_BaseModel::GOODS_UP;
                    $goods_cond_row['is_del'] = Goods_BaseModel::IS_DEL_NO;
                    if ($_REQUEST['ua'] == 'wap') {
                        $goods_rows = $Goods_BaseModel -> getByWhere($goods_cond_row);
                    } else {
                        $goods_rows = $Goods_BaseModel -> getByWhere($goods_cond_row, array('goods_id' => 'desc'));
                    }
                    //获取当前用户收藏的商品id
                    $User_FavoritesGoodsModel = new User_FavoritesGoodsModel();
                    if (Perm::checkUserPerm()) {
                        $user_favoritr_row = $User_FavoritesGoodsModel -> getByWhere(array("user_id" => Perm::$userId));
                        $user_favoritr = array_column($user_favoritr_row, 'goods_id');
                    } else {
                        $user_favoritr = array();
                    }
                    //查找当前用户是否是商品店铺的子账号
                    $Seller_BaseModel = new Seller_BaseModel();
                    $seller_row = array();
                    $seller_row['user_id'] = Perm::$userId;
                    $seller_info = $Seller_BaseModel -> getByWhere($seller_row);
                    $seller_info = array_pop($seller_info);
                    $user_shop_id = 0;
                    if ($seller_info) {
                        $user_shop_id = $seller_info['shop_id'];
                    }

                    foreach ($goods_rows as $key => $goods_row) {
                        
                        if ($goods_row && isset($common_rows[$goods_row['common_id']])) {
                            $common_rows[$goods_row['common_id']]["goods_id"] = $goods_row['goods_id'];
                            $common_rows[$goods_row['common_id']]["good"][] = $goods_row;
                            //判断该商品是否是自己的商品
                            if ($goods_row['shop_id'] == $user_shop_id) {
                                $common_rows[$goods_row['common_id']]["shop_owner"] = 1;
                            } else {
                                $common_rows[$goods_row['common_id']]["shop_owner"] = 0;
                            }
                            //判断该商品是否是当前用户的分销商品
                            $common_rows[$goods_row['common_id']]["dist_owner"] = 0;
                            if ($goods_row['goods_parent_id']) {
                                $goods_parent_base = $Goods_BaseModel -> getOne($goods_row['goods_parent_id']);
                                if ($goods_parent_base['shop_id'] == Perm::$shopId) {
                                    $common_rows[$goods_row['common_id']]["dist_owner"] = 1;
                                }
                            }
                            //判断该商品是否已经收藏过
                            if (in_array($goods_row['goods_id'], $user_favoritr)) {
                                $common_rows[$goods_row['common_id']]["is_favorite"] = 1;
                            } else {
                                $common_rows[$goods_row['common_id']]["is_favorite"] = 0;
                            }
                           //判断该虚拟商品有效期
                           // if ($common_rows[$goods_row['common_id']]['common_is_virtual']==1 && $common_rows[$goods_row['common_id']]['common_virtual_date']<date('Y-m-d')) {
                           //     unset($common_rows[$goods_row['common_id']]);
                           // }
                        } else {
                            //错误数据,干掉吧
                            // $common_rows[$goods_row['common_id']]["goods_id"] = 0;
                        }
                    }
                    
                }
            }
            // echo '<pre>';
            // print_r($common_rows);
            // die;
            $common_data['items'] = array_values($common_rows);

            return $common_data;
        }
        
        public function getGoodsByCommonId($cond_row = array(), $order_row = array(), $flag = true, $is_del_flag  = false)
        {
            if($is_del_flag){
                $cond_row['is_del'] = self::IS_DEL_NO;
            }
            $type = 'SKU';
            $common_rows = $this -> getByWhere($cond_row, $order_row);
            if ('SKU' == $type) {
                $common_ids = array_column($common_rows, 'common_id');
                if ($common_ids) {
                    $Goods_BaseModel = new Goods_BaseModel();
                    $goods_cond_row['common_id:IN'] = $common_ids;
                    $goods_cond_row['goods_is_shelves'] = Goods_BaseModel::GOODS_UP;
                    $goods_cond_row['is_del'] = Goods_BaseModel::IS_DEL_NO;
                    $goods_rows = $Goods_BaseModel -> getByWhere($goods_cond_row);
                    //获取当前用户收藏的商品id
                    $User_FavoritesGoodsModel = new User_FavoritesGoodsModel();
                    if (Perm::checkUserPerm()) {
                        $user_favoritr_row = $User_FavoritesGoodsModel -> getByWhere(array("user_id" => Perm::$userId));
                        $user_favoritr = array_column($user_favoritr_row, 'goods_id');
                    } else {
                        $user_favoritr = array();
                    }
                    //查找当前用户是否是商品店铺的子账号
                    $Seller_BaseModel = new Seller_BaseModel();
                    $seller_row = array();
                    $seller_row['user_id'] = Perm::$userId;
                    $seller_info = $Seller_BaseModel -> getByWhere($seller_row);
                    $seller_info = array_pop($seller_info);
                    $user_shop_id = 0;
                    if ($seller_info) {
                        $user_shop_id = $seller_info['shop_id'];
                    }
                    foreach ($goods_rows as $key => $goods_row) {
                        if ($goods_row && isset($common_rows[$goods_row['common_id']])) {
                            $common_rows[$goods_row['common_id']]["goods_id"] = $goods_row['goods_id'];
                            $common_rows[$goods_row['common_id']]["good"][] = $goods_row;
                            //判断该商品是否是自己的商品
                            if ($goods_row['shop_id'] == $user_shop_id) {
                                $common_rows[$goods_row['common_id']]["shop_owner"] = 1;
                            } else {
                                $common_rows[$goods_row['common_id']]["shop_owner"] = 0;
                            }
                            //判断该商品是否已经收藏过
                            if (in_array($goods_row['goods_id'], $user_favoritr)) {
                                $common_rows[$goods_row['common_id']]["is_favorite"] = 1;
                            } else {
                                $common_rows[$goods_row['common_id']]["is_favorite"] = 0;
                            }
                        } else {
                            //错误数据,干掉吧
                            //$common_rows[$goods_row['common_id']]["goods_id"] = 0;
                        }
                    }
                }
            }
            if ($flag) {
                $common_data['items'] = array_values($common_rows);
            } else {
                $common_data = $common_rows;
            }
            return $common_data;
        }
        
        //获取热销
        public function getHotSalle($shop_id = 0, $is_wap = false)
        {
            if ($is_wap) {
                $common_num = 8;
            } else {
                $common_num = 5;
            }
            $cond_row = array();
            $order_row = array();
            $cond_row['shop_id'] = $shop_id;  //店铺id
            $cond_row['common_state'] = $this::GOODS_STATE_NORMAL;  //正常上架
            $cond_row['common_verify'] = self::GOODS_VERIFY_ALLOW;   //审核通过
            $order_row['common_salenum'] = 'desc';
            $cond_row['is_del'] = self::IS_DEL_NO;
            $data = $this -> listByWhere($cond_row, $order_row, 0, $common_num);
            return $data;
        }
        
        //热门收藏
        public function getHotCollect($shop_id = 0)
        {
            $cond_row = array();
            $order_row = array();
            $cond_row['shop_id'] = $shop_id;  //店铺id
            $cond_row['common_state'] = $this::GOODS_STATE_NORMAL;  //正常上架
            $cond_row['is_del'] = self::IS_DEL_NO;
            $order_row['common_collect'] = 'desc';
            $data = $this -> listByWhere($cond_row, $order_row, 0, 5);
            return $data;
        }
        
        /*
         * 向映射添加数据
         * */
        public function createMapRelation($common_id = 0, $common_data = array())
        {
        }
        
        /*
         * 根据common_id 获取所有goods_id下面的详细信息
         * @param array $common_id_rows 商品id
         * @return array $re 查询数据
         */
        public function getGoodsDetailRows($common_id_rows)
        {
            $Goods_CommonModel = new Goods_CommonModel();
            $Goods_BaseModel = new Goods_BaseModel();
            $data = array();
            if (!empty($common_id_rows)) {
                foreach ($common_id_rows as $key => $value) {
                    $common_id = $value;
                    $goods_rows = $Goods_BaseModel -> getByWhere(array('common_id' => $common_id,'is_del'=>$Goods_BaseModel::IS_DEL_NO));
                    if (!empty($goods_rows)) {
                        $goods_ids = array_column($goods_rows, 'goods_id');
                        foreach ($goods_ids as $k => $v) {
                            $goods_id = $v;
                            $data[$common_id][$v] = $Goods_BaseModel -> getGoodsSpecByGoodId($goods_id);
                        }
                    }
                }
            }
            return $data;
        }
        
        /*
         * 根据shop 店铺关联的消费者保障服务
         * @param array $common_data 商品common
         * @return array $re 查询数据
         */
        public function getShopContract(&$common_data)
        {
            $shop_id = Perm::$shopId;
            $Shop_ContractModel = new Shop_ContractModel();
            $condi_con['shop_id'] = $shop_id;
            $condi_con['contract_state'] = Shop_ContractModel::CONTRACT_JOIN;
            $condi_con['contract_use_state'] = Shop_ContractModel::CONTRACT_INUSE;
            $contract_list = $Shop_ContractModel -> getByWhere($condi_con);
            if (!empty($contract_list)) {
                foreach ($contract_list as $contract_id => $contract_data) {
                    $contract_type_id = $contract_data['contract_type_id'];
                    $common_data["common_shop_contract_$contract_type_id"] = Goods_CommonModel::CONTRACT_USE;
                }
            }
        }
        
        /**
         *  根据店铺修改商品属性
         */
        public function editCommonByShopId($shop_id, $set = array())
        {
            if (!$set || !$shop_id) {
                return false;
            }
            $result = $this -> updateCommonByShopId($shop_id, $set);
            return $result;
        }
        
        /**
         *  同步商品common表信息
         */
        public function SynchronousCommon($old_common_id, $shop_info, $op = 'add')
        {
            //商品信息
            $Goods_CommonModel = new Goods_CommonModel();
            $common_info = $Goods_CommonModel -> getOne($old_common_id);
            $common_row = array();
            $common_row['common_name'] = $common_info['common_name'];
            $common_row['cat_id'] = $common_info['cat_id'];
            $common_row['cat_name'] = str_replace('&gt;', '>', $common_info['cat_name']);
            $common_row['shop_id'] = $shop_info['shop_id'];
            $common_row['shop_name'] = $shop_info['shop_name'];
            $common_row['shop_self_support'] = $shop_info['shop_self_support'] == 'false' ? 0 : 1;
            $common_row['brand_id'] = $common_info['brand_id'];
            $common_row['brand_name'] = $common_info['brand_name'];
            $common_row['common_property'] = $common_info['common_property'];
            $common_row['common_spec_name'] = $common_info['common_spec_name'];
            $common_row['common_spec_value'] = $common_info['common_spec_value'];
            $common_row['common_image'] = $common_info['common_image'];
            $common_row['common_price'] = $common_info['goods_recommended_min_price'];
            $common_row['common_cubage'] = $common_info['common_cubage'];
            $common_row['common_market_price'] = $common_info['goods_recommended_max_price'];
            $common_row['common_cost_price'] = $common_info['common_cost_price'];
            $common_row['common_verify'] = $common_info['common_verify'];
            $common_row['common_stock'] = $common_info['common_stock'];
            $common_row['common_is_virtual'] = $common_info['common_is_virtual'];
            $common_row['common_add_time'] = get_date_time();
            $common_row['common_state'] = 1;
            $common_row['product_is_allow_update'] = $common_info['product_is_allow_update'];
            $common_row['product_is_allow_price'] = $common_info['product_is_allow_price'];
            $common_row['product_is_behalf_delivery'] = $common_info['product_is_behalf_delivery'];
            $common_row['goods_recommended_min_price'] = $common_info['goods_recommended_min_price'];
            $common_row['goods_recommended_max_price'] = $common_info['goods_recommended_max_price'];
            $common_row['common_parent_id'] = $common_info['common_id'];
            $common_row['supply_shop_id'] = $common_info['shop_id'];
            $common_row['common_virtual_date'] = $common_info['common_virtual_date'];
            if ($op == 'add') {
                //添加新商品
                $new_common_id = $Goods_CommonModel -> addCommon($common_row, true);
                //商品详情信息
                $goodsCommonDetailModel = new Goods_CommonDetailModel();
                $common_detail = $goodsCommonDetailModel -> getOne($old_common_id);
                $common_detail_data['common_id'] = $new_common_id;
                $common_detail_data['common_body'] = $common_detail['common_body'];
                $goodsCommonDetailModel -> addCommonDetail($common_detail_data);
                //返回商品common_id
                return $new_common_id;
            } else {
                return $common_row;
            }
        }
        
        /**
         *  同步商品goods_base表
         */
        public function SynchronousGoods($old_common_id, $new_common_id, $shop_info)
        {
            $Goods_BaseModel = new Goods_BaseModel();
            //根据common_id查询base表，同步base数据
            $base_list = $Goods_BaseModel -> getByWhere(array('common_id' => $old_common_id));
            if (!empty($base_list)) {
                foreach ($base_list as $k => $v) {
                    $base_row = array();
                    $base_row['common_id'] = $new_common_id;
                    $base_row['shop_id'] = $shop_info['shop_id'];
                    $base_row['shop_name'] = $shop_info['shop_name'];
                    $base_row['goods_name'] = $v['goods_name'];
                    $base_row['cat_id'] = $v['cat_id'];
                    $base_row['brand_id'] = $v['brand_id'];
                    $base_row['goods_spec'] = $v['goods_spec'];
                    $base_row['goods_price'] = $v['goods_recommended_min_price'];
                    $base_row['goods_market_price'] = $v['goods_recommended_max_price'];
                    $base_row['goods_stock'] = $v['goods_stock'];
                    $base_row['goods_image'] = $v['goods_image'];
                    $base_row['goods_parent_id'] = $v['goods_id'];
                    $base_row['goods_is_shelves'] = 1;
                    $base_row['goods_recommended_min_price'] = $v['goods_recommended_min_price'];
                    $base_row['goods_recommended_max_price'] = $v['goods_recommended_max_price'];
                    $goods_id = $Goods_BaseModel -> addBase($base_row, true);
                    $new_goods_ids[] = array(
                        'goods_id' => $goods_id,
                        'color' => $v['color_id']
                    );
                }
            }
            return $new_goods_ids;
        }
        
        public function getSubQuantity($cond_row)
        {
            $cond_row['is_del'] = self::IS_DEL_NO;
            return $this -> getNum($cond_row);
        }
        
        /**
         * ERP上传商品
         *
         * @param $upload_goods_rows
         * @param $shop_data
         *
         * @return array 返回添加失败的商品名称，如都添加成功返回[]
         */
        public function addGoodsByERPUpload($upload_goods_rows, $shop_data)
        {
            $shop_id = $shop_data['shop_id']; //店铺id
            $shop_name = $shop_data['shop_name']; //店铺名称
            $shop_self_support = $shop_data['shop_self_support'] == Shop_BaseModel::SELF_SUPPORT_TRUE ? 1 : 0; //判断店铺是否为自营店铺
            $common_data = [];
            $common_data['shop_id'] = $shop_id;
            $common_data['shop_name'] = $shop_name;
            $common_data['shop_self_support'] = $shop_self_support;
            $common_data['common_state'] = self::GOODS_STATE_OFFLINE; //erp上传商品默认是下架状态
            $goods_verify_flag = Web_ConfigModel::value('goods_verify_flag'); //判断商品是否需要审核
            if ($goods_verify_flag == 0) {//不需要审核
                $common_data['common_verify'] = self::GOODS_VERIFY_ALLOW;
            } else {
                $common_data['common_verify'] = self::GOODS_VERIFY_WAITING;
            }
            $shop_contract_rows = $this -> getShopContractsByShopId($shop_id); //店铺关联的消费者保障服务
            if (!empty($shop_contract_rows)) {
                $common_data += $shop_contract_rows;
            }
            $goodsBaseModel = new Goods_BaseModel;
            $goodsCommonDetailModel = new Goods_CommonDetailModel;
            $errors = []; //记录错误信息说
            $goods_data = []; //goods_base 插入数据
            $goods_data['shop_id'] = $shop_id;
            $goods_data['shop_name'] = $shop_name;
            foreach ($upload_goods_rows as $upload_goods_data) {
                $cat_id = $upload_goods_data['cat_id'];
                $goods_name = $upload_goods_data['goods_name'];
                $goods_price = $upload_goods_data['goods_price'];
                $common_data['cat_id'] = $cat_id; //经营类目id
                $common_data['cat_name'] = $upload_goods_data['cat_name']; //经营类目名称
                $common_data['type_id'] = $upload_goods_data['type_id']; //商品分类id
                $common_data['common_name'] = $goods_name; //商品名称
                $common_data['common_code'] = $upload_goods_data['goods_number']; //商家编号
                $common_data['common_cubage'] = $upload_goods_data['goods_weight']; //商品重量
                $common_data['common_add_time'] = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']); //商品添加时间
                $common_data['common_price'] = $goods_price; //商品价格
                $common_data['common_market_price'] = $goods_price; //市场价
                $common_data['common_cost_price'] = $goods_price; //成本价
                //如果商品为多属性
                if (isset($upload_goods_data['spec_names'])) {
                    $common_data['common_spec_name'] = $upload_goods_data['spec_names'];   //规格名
                    $common_data['common_spec_value'] = $upload_goods_data['spec_values'];  //规格值
                } else {
                    $common_data['common_spec_name'] = '';  //规格名
                    $common_data['common_spec_value'] = '';  //规格值
                }
                /**
                 * 添加商品时关联表
                 * 1.goods_common （如果有颜色规格 goods_id=> [[goods_id=> goods_id, color_id=> spec_value_id]]）
                 * 2.goods_common_detail
                 * 3.goods_base 一对多的关系（如果商品为多属性）如果有颜色规格，还需添加color_id=> spec_value_id
                 */
                $this -> sql -> startTransactionDb(); //开启事物
                $common_id = $this -> addCommon($common_data, true);
                $common_detail_id = $goodsCommonDetailModel -> addCommonDetail(['common_id' => $common_id], true); //不管怎样都要添加一条商品描述
                //goods_base 添加数据
                $goods_data['common_id'] = $common_id;
                $goods_data['goods_name'] = $goods_name; //商品名称
                $goods_data['cat_id'] = $cat_id; //商品分类
                $goods_data['goods_price'] = $goods_price; //商品价格
                $goods_data['goods_market_price'] = $goods_price; //市场价
                $goods_ids = []; //存放所有goods_base添加记录的返回结果
                $common_color_ids = []; //common_base 冗余字段 存放商品颜色信息 [['goods_id'=> goods_id, 'color_id'=> spec_value_id]]
                if (isset($upload_goods_data['goods_rows'])) {
                    foreach ($upload_goods_data['goods_rows'] as $g_k => $g_data) {
                        $goods_data['goods_spec'] = [$g_k => $g_data['sku_value']]; //sku
                        $goods_data['goods_code'] = $g_data['sku_number']; //商家编号
                        $goods_data['color_id'] = $g_data['color_spec_value_id']; //color_id
                        $goods_id = $goodsBaseModel -> addBase($goods_data, true);
                        $goods_ids[] = $goods_id;
                        $common_color_ids[] = ['goods_id' => $goods_id, 'color_id' => $g_data['color_spec_value_id']];
                    }
                } else {
                    $goods_data['goods_spec'] = ''; //sku
                    $goods_data['goods_code'] = ''; //商家编号
                    $goods_data['color_id'] = ''; //color_id
                    $goods_ids[] = $goodsBaseModel -> addBase($goods_data, true);
                }
                if ($common_id && $common_detail_id && !in_array(false, $goods_ids, true)) {
                    $this -> sql -> commitDb();
                    //common_goods 存放color_id冗余字段
                    $common_color_id = current($common_color_ids);
                    if ($common_color_id['color_id'] !== 0) {
                        $this -> editCommon($common_id, ['goods_id' => $common_color_ids]);
                    }
                } else {
                    $errors[] = $goods_name;
                }
            }
            return $errors;
        }
        
        /**
         * @param $shop_id
         *
         * @return array
         * 根据店铺id获取店铺关联的消费者保障服务
         */
        public function getShopContractsByShopId($shop_id)
        {
            $shopContractModel = new Shop_ContractModel;
            $condition = [];
            $condition['shop_id'] = $shop_id;
            $condition['contract_state'] = Shop_ContractModel::CONTRACT_JOIN;
            $condition['contract_use_state'] = Shop_ContractModel::CONTRACT_INUSE;
            $contract_list = $shopContractModel -> getByWhere($condition);
            if (empty($contract_list)) {
                return []; //店铺没有关联消费者保障服务
            } else {
                $shop_contract_rows = [];
                foreach ($contract_list as $contract_id => $contract_data) {
                    $contract_type_id = $contract_data['contract_type_id'];
                    $common_data["common_shop_contract_$contract_type_id"] = Goods_CommonModel::CONTRACT_USE;
                }
                return $shop_contract_rows;
            }
        }
        
        /**
         * @param $goods_id
         * @param $goods_num
         *
         * @return array
         * 根据商品限购判断是否可以继续购买
         *
         * 限购场景：1.团购限购。2.商品限购。
         *
         * 返回：
         *      1.没有开启限购 return ['open_limit'=> false]
         *      2.开启限购 return ['open_limit'=> true, 'valid_status'=> true, valid_num=> num]
         * 解释：
         *      1.valid_status: 当前请求是否允许
         *      2.valid_num: 当前状态还可以添加的有效商品数量
         *      3.open_limit: 商品是否开启限购
         */
        public function checkGoodsPurchaseLimit($goods_id, $goods_num)
        {
            $flag = $this -> isOpenGoodsLimit($goods_id);
            //商品没有开启限购
            if (!$flag) {
                return ['open_limit' => false];
            }
            //商品开启限购
            $limit_data = $flag;
            $orderGoodsModel = new Order_GoodsModel();
            $common_id = $limit_data['common_id'];
            $limit_num = $limit_data['limit_num'];
            //获取用户已购买数量
            if ($limit_data['type'] == 'goods') {
                $user_bought_num = $orderGoodsModel -> getGoodsPurchaseNumByUser($common_id);
            } else {
                //获取用户在团购区间所购买的商品数量
                $limit_time['start_time'] = $limit_data['start_time'];
                $limit_time['end_time'] = $limit_data['end_time'];
                $user_bought_num = $orderGoodsModel -> getGoodsPurchaseNumByUser($common_id, $limit_time);
            }
            //当前还可以添加的有效数量
            $valid_num = $limit_num - $user_bought_num;
//        return $valid_num >= $goods_num
//            ? ['common_id'=> $common_id, 'open_limit'=> true, 'valid_status'=> true, 'valid_num'=> $valid_num]
//            : ['common_id'=> $common_id, 'open_limit'=> true, 'valid_status'=> false, 'valid_num'=> $valid_num];
            //需求改为购物车内的商品与立即购买的商品数不累加
            return $limit_num >= $goods_num
                ? ['common_id' => $common_id, 'open_limit' => true, 'valid_status' => true, 'limit_num' => $limit_num]
                : ['common_id' => $common_id, 'open_limit' => true, 'valid_status' => false, 'limit_num' => $limit_num];
        }
        
        /**
         * @param $goods_id
         *
         * @return array
         * 商品没有开启限购 return false
         * 商品开启限购：
         *     1.团购限购 return [common_id: xxx, limit_num: xxx, type: group_buy, start_time: xxx, end_time: xxx]
         *     2.商品限购 return [common_id: xxx, limit_num: xxx, type: goods]
         * 判断商品是否开启限购
         */
        public function isOpenGoodsLimit($goods_id)
        {
            //获取common_id
            $goodsBaseModel = new Goods_BaseModel();
            $goods_data = $goodsBaseModel -> getOne($goods_id);
            $common_id = $goods_data['common_id'];
            //获取团购信息判断是否满足场景一
            $groupBuyBaseModel = new GroupBuy_BaseModel();
            //满足条件：当前商品、时间在团购范围内
            $now_time = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
            $group_buy_condi = [];
            $group_buy_condi['common_id'] = $common_id;
            $group_buy_condi['groupbuy_starttime:<='] = $now_time;
            $group_buy_condi['groupbuy_endtime:>='] = $now_time;
            $group_buy_condi['groupbuy_state'] = 2;
            $group_buy_rows = $groupBuyBaseModel -> getByWhere($group_buy_condi);
            if (empty($group_buy_rows)) {
                $gb_limit_num = 0;
            } else {
                $group_buy_data = current($group_buy_rows);
                $gb_limit_num = $group_buy_data['groupbuy_upper_limit'];
            }
            //如果判断团购是否限购，如果限购触发团购限购，否则继续判断商品限购
            if ($gb_limit_num) {
                return [
                    'common_id' => $common_id,
                    'limit_num' => $gb_limit_num,
                    'start_time' => $group_buy_data['groupbuy_starttime'],
                    'end_time' => $group_buy_data['groupbuy_endtime'],
                    'type' => 'group_buy'
                ];
            }
            $common_data = $this -> getOne($common_id);
            $common_limit_num = $common_data['common_limit'];
            if ($common_limit_num) {
                return [
                    'common_id' => $common_id,
                    'limit_num' => $common_limit_num,
                    'type' => 'goods'
                ];
            }
            return false;
        }
        
        /**
         * 判断商品是否为虚拟商品
         *
         * @param type $comon_id
         *
         * @return boolean
         */
        public function isVirtual($comon_id)
        {
            if (!$comon_id) {
                return false;
            }
            $common_info = $this -> getOne($comon_id);
            if ($common_info['common_is_virtual']) {
                return true;
            } else {
                return false;
            }
        }
        
        /**
         * 获取供应商的信息替换分销商信息
         *
         * @param type $goods_common 分销商商品
         */
        public function getSupplierCommon($goods_common)
        {
            //判断是否为代发货的分销商品
            if ($goods_common['supply_shop_id'] && $goods_common['common_parent_id']) {
                $supplier_goods_common = $this -> getOne($goods_common['common_parent_id']);
                if ($goods_common['product_is_behalf_delivery'] == 1) {
                    $goods_common['transport_area_id'] = $supplier_goods_common['transport_area_id'];
                }
                $goods_common['common_limit'] = $supplier_goods_common['common_limit'];
            }
            return $goods_common;
        }
        
        /**
         * 检查商品的common信息是否正常
         *
         * @param type $goods_common
         *
         * @return boolean
         */
        public function checkCommonBase($goods_common)
        {
            if (!is_array($goods_common) || !$goods_common) {
                return false;
            }
            //common状态与店铺状态
            if ($goods_common['common_state'] != Goods_CommonModel::GOODS_STATE_NORMAL || $goods_common['shop_status'] != Shop_BaseModel::SHOP_STATUS_OPEN) {
                return false;
            }
            return true;
        }
        
        /**
         * 获取供应商代发货的商品
         *
         * @param type $goods_common 分销商商品
         */
        public function getSupplierSendCommonByShopId($shop_id)
        {
            $where = array(
                'shop_id' => $shop_id,
                // 'product_is_behalf_delivery' => 1,//一件代发
                'common_parent_id:>' => 0,
                'supply_shop_id:>' => 0,
                'common_state' => self::GOODS_STATE_NORMAL,
                'common_verify' => self::GOODS_VERIFY_ALLOW,
                'is_del' => self::IS_DEL_NO,
            );
            $result = $this -> getByWhere($where);
            return $result;
        }
        
        //获取店铺所有商品common_id
        public function getCommonIdByShopId($shop_id)
        {
            $where = array(
                'shop_id' => $shop_id,
                'common_state' => self::GOODS_STATE_NORMAL,
                'common_verify' => self::GOODS_VERIFY_ALLOW
            );
            $result = $this -> getByWhere($where);
            $common_id = array_column($result, 'common_id');
            return $common_id;
        }
        
        /**
         * @param $commonId
         * 更新商品销售数量
         */
        public function updateGoodsSaleNum($commonId, $num)
        {
            $this -> editCommon($commonId, [
                'common_salenum' => $num
            ], true);
        }
        /**
         * 获取商品收藏量common_collect
         *
         * @access public
         */
        public function getCommonCollect($cond_row)
        {
            $Order_BaseModel = new Order_BaseModel();
            $sql = "SELECT SUM(common_collect) collect from `yf_goods_common` 
                where shop_id ='" . $cond_row['shop_id'] . "'  
                and common_state >='" . self::GOODS_STATE_NORMAL . "' ";

            $sums = $Order_BaseModel->sql->getAll($sql);

            return $sums[0]['collect'];
        }
    }

?>

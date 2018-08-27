<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    /**
     * @author     Xinze <xinze@live.cn>
     */
    class Seller_Promotion_IncreaseCtl extends Seller_Controller
    {
        public $increaseBaseModel = null;
        public $increaseGoodsModel = null;
        public $increaseRuleModel = null;
        public $increaseRedempGoodsModel = null;
        public $increaseComboModel = null;
        public $goodsBaseModel = null;
        public $shopCostModel = null;
        public $shopBaseModel = null;
        
        public $combo_flag = false; //套餐是否可用
        public $shop_info = array();  //店铺信息
        public $self_support_flag = false;    //是否为自营店铺
        
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
            parent::__construct($ctl, $met, $typ);
            
            if (!Web_ConfigModel::value('promotion_allow')) //团购功能设置，关闭，跳转到卖家首页
            {
                if ("e" == $this->typ) {
                    $this->view->setMet('error');
                    include $this->view->getView();
                    die;
                } else {
                    $data = new Yf_Data();
                    $data->setError(__('加价购功能已关闭'), 30);
                    $d = $data->getDataRows();
                    
                    $protocol_data = Yf_Data::encodeProtocolData($d);
                    echo $protocol_data;
                    exit();
                }
            }
            
            $this->increaseBaseModel = new Increase_BaseModel();
            $this->increaseGoodsModel = new Increase_GoodsModel();
            $this->increaseRuleModel = new Increase_RuleModel();
            $this->increaseRedempGoodsModel = new Increase_RedempGoodsModel();
            $this->increaseComboModel = new Increase_ComboModel();
            $this->goodsBaseModel = new Goods_BaseModel();
            $this->shopCostModel = new Shop_CostModel();
            $this->shopBaseModel = new Shop_BaseModel();
            
            $this->shop_info = $this->shopBaseModel->getOne(Perm::$shopId);//店铺信息
            $this->self_support_flag = ($this->shop_info['shop_self_support'] == "true" || Web_ConfigModel::value('promotion_increase_price') == 0) ? true : false; //是否为自营店铺标志
            
            if ($this->self_support_flag) //平台店铺，没有套餐限制
            {
                $this->combo_flag = true;
            } else {
                $this->combo_flag = $this->increaseComboModel->checkQuotaStateByShopId(Perm::$shopId);
                
            }
            
        }
        
        /**
         * 首页
         *活动列表
         *
         * @access public
         */
        public function index()
        {
            $data = array();
            $combo_row = array();
            
            $cond_row['shop_id'] = Perm::$shopId;
            
            $Yf_Page = new Yf_Page();
            $Yf_Page->listRows = request_int('listRows') ? request_int('listRows') : 10;
            $rows = $Yf_Page->listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $rows);
            
            if (request_string('op') == 'edit' && request_int('id')) {
                $increase_id = request_int('id');
                $check_row = $this->increaseBaseModel->getIncreaseActItem($increase_id);
                
                if ($check_row['shop_id'] == Perm::$shopId) {
                    $data = $this->increaseBaseModel->getIncreaseActDetail($increase_id);
                } else {
                    location_to('index.php?ctl=Seller_Promotion_Increase&met=index&typ=e');
                }
                
                $this->view->setMet('edit');
                //店铺下的加价购活动
            } else {
                if (request_string('keyword')) {
                    $cond_row['increase_name:LIKE'] = request_string('keyword') . '%';
                }
                
                if (request_int('state')) {
                    $cond_row['increase_state'] = request_int('state');
                }
                
                $data = $this->increaseBaseModel->getIncreaseActList($cond_row, array('increase_id' => 'ASC'), $page, $rows);
                
                $Yf_Page->totalRows = $data['totalsize'];
                $page_nav = $Yf_Page->prompt();
                
                $shop_type = $this->self_support_flag;
                //普通店铺
                if (!$this->self_support_flag) {
                    $com_flag = $this->combo_flag;
                    //套餐可用
                    if ($this->combo_flag) {
                        $combo_row = $this->increaseComboModel->getComboInfo(Perm::$shopId);
                    }
                }
            }
            
            if ('json' == $this->typ) {
                $json_data['data'] = $data;
                $json_data['shop_type'] = $this->self_support_flag;
                $json_data['common_flag'] = $this->combo_flag;
                $json_data['common_row'] = $combo_row;
                $this->data->addBody(-140, $json_data);
            } else {
                include $this->view->getView();
            }
        }
        
        /*编辑活动*/
        public function editIncrease()
        {
            $data = array();
            
            if (!$this->combo_flag) {
                $msg = __('套餐不可用');
                $status = 250;
            } else {
                //判断规则购满金额是否有重复
                $increase_id = request_int('increase_id');
                $check_row = $this->increaseBaseModel->getIncreaseActItem($increase_id);
                
                if ($check_row['shop_id'] == Perm::$shopId) {
                    $rs_row = array();
                    
                    $Goods_CommonModel = new Goods_CommonModel();
                    
                    $this->increaseBaseModel->sql->startTransactionDb();
                    
                    //修改活动名称
                    $field_row_act['increase_name'] = request_string('increase_name');
                    if (request_string('increase_name')) {
                        $this->increaseBaseModel->editIncrease($increase_id, $field_row_act);
                    }
                    
                    //已加入该活动的商品goods_id
                    $cond_row_joined['increase_id'] = $increase_id;
                    $cond_row_joined['shop_id'] = Perm::$shopId;
                    $joined_goods_id_row = $this->increaseGoodsModel->getIncreaseGoodsIdByWhere($cond_row_joined, array('increase_goods_id' => 'ASC'));
                    //编辑加入活动的商品
                    $join_goods_id_row = request_row('join_act_goods_id');//商品SKU
                    $join_goods_common_row = request_row('join_act_common_id');//商品Common
                    
                    /*$cond_row_goods_base['shop_id'] = Perm::$shopId;
                    $cond_row_goods_base['goods_id:IN'] = $join_goods_id_row;
                    $join_goods_base_rows   =  $this->goodsBaseModel->getByWhere($cond_row_goods_base);*/
                    
                    $common_id_row = array();
                    foreach ($join_goods_id_row as $key => $goods_id) {
                        $goods_row = array();
                        if (!in_array($goods_id, $joined_goods_id_row)) //如果商品以前没有参加该活动，加入活动
                        {
                            $goods_row['increase_id'] = $increase_id;
                            $goods_row['goods_id'] = $goods_id;
                            $goods_row['common_id'] = $join_goods_common_row[$key];
                            $goods_row['shop_id'] = Perm::$shopId;
                            $goods_row['goods_start_time'] = $check_row['increase_start_time'];
                            $goods_row['goods_end_time'] = $check_row['increase_end_time'];
                            $add_flag = $this->increaseGoodsModel->addIncreaseGoods($goods_row, true);
                            check_rs($add_flag, $rs_row);
                            
                            $common_id_row[] = $join_goods_common_row[$key];
                        }
                    }
                    
                    if ($common_id_row) {
                        //修改goods_common表 加价购活动状态
                        $update_common_a_flag = $Goods_CommonModel->editCommon($common_id_row, array('common_is_jia' => 1));
                        check_rs($update_common_a_flag, $rs_row);
                    }
                    
                    // 删除以前加入活动的商品
                    $remove_increase_goods_id = array();
                    foreach ($joined_goods_id_row as $increase_goods_id => $goods_id) {
                        if (!in_array($goods_id, $join_goods_id_row)) {
                            array_push($remove_increase_goods_id, $increase_goods_id);
                        }
                    }
                    
                    if ($remove_increase_goods_id) {
                        $remove_flag = $this->increaseGoodsModel->removeIncreaseGoods($remove_increase_goods_id, true);
                        check_rs($remove_flag, $rs_row);
                    }
                    
                    //活动规则
                    $edit_rule_row = request_row('rule_levle');
                    $edit_rule_price = array();
                    foreach ($edit_rule_row as $key => $rule) {
                        if (is_numeric($rule['mincost']) && $rule['mincost'] * 1 > 0) {
                            $edit_rule_price[] = $rule['mincost']; //购满金额
                        } else {
                            unset($edit_rule_row[$key]);
                        }
                    }
                    
                    //已存在的活动规则-价格
                    $rule_price_row = $this->increaseRuleModel->getRulePriceByWhere(array('increase_id' => $increase_id), array('rule_id' => 'ASC'));
                    
                    foreach ($edit_rule_row as $key => $rule) {
                        /*
                         * 活动规则不存在
                         * 1、添加新规则
                         * 2、添加规则下的对应换购商品
                         * */
                        if (!in_array($rule['mincost'], $rule_price_row)) {
                            $field_rule_row = array();
                            $field_rule_row['increase_id'] = $increase_id;
                            $field_rule_row['rule_price'] = $rule['mincost'];
                            $field_rule_row['rule_goods_limit'] = $rule['maxrebuy'];
                            $rule_id = $this->increaseRuleModel->addIncreaseRule($field_rule_row, true);
                            check_rs($rule_id, $rs_row);
                            
                            if ($rule_id) {
                                $field_rede_row = array();
                                $field_rede_row['rule_id'] = $rule_id;
                                $field_rede_row['increase_id'] = $increase_id;
                                $field_rede_row['shop_id'] = Perm::$shopId;
                                foreach ($rule['skus'] as $sku_id => $redemp_price) {
                                    $field_rede_row['goods_id'] = $sku_id;
                                    $field_rede_row['redemp_price'] = $redemp_price;
                                    $redemp_id = $this->increaseRedempGoodsModel->addIncreaseRedempGoods($field_rede_row, true);
                                    check_rs($redemp_id, $rs_row);
                                }
                            }
                        } else {
                            /*活动规则已存在
                             * 1、编辑规格下允许换购商品的数量
                             * 2、判断规格下的换购商品是否已经添加过
                             * （1）编辑的换购商品添加过，编辑对应的换购价
                             * （2）编辑的换购商品未添加，添加换购商品
                             * （3）已添加的换购在新编辑的规则中不存在，移除已添加的换购商品
                            */
                            
                            //编辑规格下允许换购商品的数量
                            $rule_price_row_rev = array_flip($rule_price_row);
                            $rule_id = $rule_price_row_rev[$rule['mincost']];
                            $field_row = array();
                            $field_row['rule_goods_limit'] = $rule['maxrebuy'];
                            $this->increaseRuleModel->editIncreaseRule($rule_id, $field_row);
                            
                            //活动规则下已存在的换购商品goods_id，非主键,索引为主键
                            $cond_row = array();
                            $cond_row['rule_id'] = $rule_id;
                            $cond_row['increase_id'] = $increase_id;
                            $redemp_goods_sku_id_row = $this->increaseRedempGoodsModel->getIncreaseRedempGoodsIdByWhere($cond_row, array());
                            
                            $edit_rule_sku_id = array();
                            foreach ($rule['skus'] as $goods_sku_id => $redemp_price) {
                                //编辑的换购商品未添加，添加换购商品
                                if (!in_array($goods_sku_id, $redemp_goods_sku_id_row)) {
                                    $field_rede_row = array();
                                    $field_rede_row['goods_id'] = $goods_sku_id;
                                    $field_rede_row['redemp_price'] = $redemp_price;
                                    $field_rede_row['rule_id'] = $rule_id;
                                    $field_rede_row['increase_id'] = $increase_id;
                                    $field_rede_row['shop_id'] = Perm::$shopId;
                                    $redemp_id = $this->increaseRedempGoodsModel->addIncreaseRedempGoods($field_rede_row, true);
                                    check_rs($redemp_id, $rs_row);
                                } else   //编辑的换购商品已添加，编辑换购价格
                                {
                                    $redemp_goods_id_row = array_flip($redemp_goods_sku_id_row);
                                    $field_rede_row = array();
                                    $field_rede_row['redemp_price'] = $redemp_price;
                                    $redemp_goods_id = $redemp_goods_id_row[$goods_sku_id];
                                    $this->increaseRedempGoodsModel->editRedemptionGoods($redemp_goods_id, $field_rede_row);
                                }
                                $edit_rule_sku_id[] = $goods_sku_id;
                            }
                            $edit_rule_sku_id = array_unique($edit_rule_sku_id);
                            
                            //已添加的换购在新编辑的规则中不存在，移除已添加的换购商品
                            $remove_redemption_goods_id = array();
                            foreach ($redemp_goods_sku_id_row as $redemp_goods_id => $goods_sku_id) {
                                if (!in_array($goods_sku_id, $edit_rule_sku_id)) {
                                    array_push($remove_redemption_goods_id, $redemp_goods_id);
                                }
                            }
                            if ($remove_redemption_goods_id) {
                                $delete_flag = $this->increaseRedempGoodsModel->removeIncreaseRedempGoods($remove_redemption_goods_id);
                                check_rs($delete_flag, $rs_row);
                            }
                        }
                    }
                    
                    $remove_rule_id = array();      //删除的规则id
                    $remove_redemp_id = array();    //规则下的所有换购商品id
                    foreach ($rule_price_row as $rule_id => $rule_price) {
                        if (!in_array($rule_price, $edit_rule_price)) {
                            $cond_row = array();
                            array_push($remove_rule_id, $rule_id);
                            $cond_row['rule_id'] = $rule_id;
                            $cond_row['increase_id'] = $increase_id;
                            $remove_redemp_id = array_merge($remove_redemp_id, $this->increaseRedempGoodsModel->getRedempGoodsKeyByWhere($cond_row, array()));
                        }
                    }
                    
                    if ($remove_rule_id) {
                        $delete_r_flag = $this->increaseRuleModel->removeIncreaseRuleItem($remove_rule_id);
                        check_rs($delete_r_flag, $rs_row);
                    }
                    if ($remove_redemp_id) {
                        $delete_redemp_flag = $this->increaseRedempGoodsModel->removeIncreaseRedempGoods($remove_redemp_id);
                        check_rs($delete_redemp_flag, $rs_row);
                    }
                    
                    if (is_ok($rs_row) && $this->increaseBaseModel->sql->commitDb()) {
                        $msg = __('success');
                        $status = 200;
                    } else {
                        $this->increaseBaseModel->sql->rollBackDb();
                        $msg = __('failure');
                        $status = 250;
                    }
                } else {
                    $msg = __('failure');
                    $status = 250;
                }
            }
            
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
        //删除活动
        public function removeIncreaseAct()
        {
            $increase_id = request_int("id");

            $check_row = $this->increaseBaseModel->getOne($increase_id);

            if ($check_row['shop_id'] == Perm::$shopId) {
                $this->increaseBaseModel->sql->startTransactionDb();
                $flag = $this->increaseBaseModel->removeIncreaseActItem($increase_id); //删除活动
                if ($flag && $this->increaseBaseModel->sql->commitDb()) {
                    $msg = __('删除成功！');
                    $status = 200;
                } else {
                    $this->increaseBaseModel->sql->rollBackDb();
                    $msg = __('删除失败！');
                    $status = 250;
                }
            } else {
                $msg = __('删除失败！');
                $status = 250;
            }
            
            $data = array();
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
        /**
         *添加活动页
         */
        public function add()
        {
            $combo = array();
            $shop_type = $this->self_support_flag;
            
            if (!$this->self_support_flag)  //普通店铺
            {
                if (!$this->combo_flag) {
                    location_to('index.php?ctl=Seller_Promotion_Increase&met=combo&typ=e');
                } else {
                    $combo = $this->increaseComboModel->getComboInfo(Perm::$shopId); //套餐信息
                }
            } else {
                $combo['combo_end_time'] = date("Y-m-d H:i:s", strtotime("11 june 2030"));
            }
            
            if ('json' == $this->typ) {
                $data['shop_type'] = $shop_type;
                $data['combo'] = $combo;
                $this->data->addBody(-140, $data);
            } else {
                include $this->view->getView();
            }
            
        }
        
        /**
         *添加活动
         * 判断是否有套餐使用权
         */
        public function addIncrease()
        {
            $data = array();
            //$combo = $this->Increase_ComboModel->getComboInfo(Perm::$shopId); //套餐信息
            
            if (!$this->combo_flag) {
                $flag = false;
            } else {
                $field_row['increase_name'] = request_string('increase_name');
                $field_row['increase_start_time'] = request_string('increase_start_time');
                $field_row['increase_end_time'] = request_string('increase_end_time');
                $field_row['shop_id'] = Perm::$shopId;
                $field_row['shop_name'] = $this->shop_info['shop_name'];
                $field_row['user_id'] = Perm::$userId;
                $field_row['user_nickname'] = Perm::$row['user_account'];
                //$field_row['combo_id'] = $combo['combo_id'];
                $insert_id = $this->increaseBaseModel->addIncreaseActItem($field_row);
                
                if ($insert_id) {
                    $flag = true;
                    $data['increase_id'] = $insert_id;
                }
            }
            
            if ($flag) {
                $msg = __('添加成功！');
                $status = 200;
            } else {
                $msg = __('添加失败！');
                $status = 250;
            }
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
        public function combo()
        {
            if ($this->self_support_flag)   //免费发布活动
            {
                
                location_go_back(__('自营店铺或者套餐续费， 不需要设置。'));
                //location_to('index.php?ctl=Seller_Promotion_Increase&met=add&typ=e');
            }
            
            //查找出店铺团购活动的消费记录
            $Yf_Page = new Yf_Page();
            $Yf_Page->listRows = request_int('listRows') ? request_int('listRows') : 10;
            $rows = $Yf_Page->listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $rows);
            
            $cond_row = array();
            $order_row = array();
            $cond_row['shop_id'] = Perm::$shopId;
            $cond_row['activity_type'] = Shop_CostModel::INCREASE;
            $data = $this->shopCostModel->listByWhere($cond_row, $order_row, $page, $rows);
            
            $Yf_Page->totalRows = $data['totalsize'];
            $page_nav = $Yf_Page->prompt();
            
            if ('json' == $this->typ) {
                $data['promotion_increase_price'] = floatval(Web_ConfigModel::value('promotion_increase_price'));
                $this->data->addBody(-140, $data);
            } else {
                include $this->view->getView();
            }
            
        }
        
        /*购买套餐或套餐续费*/
        public function addCombo()
        {
            $rs_row = array();
            $data = array();
            $combo_row = array();
            $month_price = floatval(Web_ConfigModel::value('promotion_increase_price'));
            $month = request_int('month');
            $days = 30 * $month;
            
            if ($month > 0) {
                $this->increaseComboModel->sql->startTransactionDb();
                
                $field_row['user_id'] = Perm::$userId;
                $field_row['shop_id'] = Perm::$shopId;
                $field_row['cost_price'] = $month_price * $month;
                $field_row['cost_desc'] = __('店铺购买加价购活动消费');
                $field_row['cost_status'] = 0;
                $field_row['cost_time'] = get_date_time();
                $field_row['activity_type'] = Shop_CostModel::INCREASE;
                $field_row['activity_price'] = $month_price;
                $field_row['activity_month'] = $month;
                $flag = $this->shopCostModel->addCost($field_row, true);
                check_rs($flag, $rs_row);
                
                //购买或续费套餐
                if ($flag) {
                    $combo_row = $this->increaseComboModel->getIncreaseComboByShopID(Perm::$shopId);
                    
                    if ($combo_row)              //套餐续费
                    {
                        //1、原套餐已经过期,更新套餐开始时间和结束时间
                        if (strtotime($combo_row['combo_end_time']) < time()) {
                            $field['combo_start_time'] = get_date_time();
                            $field['combo_end_time'] = date('Y-m-d H:i:s', strtotime("+$days days"));
                        } elseif ((time() >= strtotime($combo_row['combo_start_time'])) && (time() <= strtotime($combo_row['combo_end_time']))) {
                            //2、原套餐尚未过期，只需更新结束时间
                            $field['combo_end_time'] = date('Y-m-d H:i:s', strtotime("+$days days", strtotime($combo_row['combo_end_time'])));
                        }
                        $op_flag = $this->increaseComboModel->renewIncreaseCombo($combo_row['combo_id'], $field);
                    } else                    //套餐购买
                    {
                        $shop_row = $this->shopBaseModel->getBaseOneList(array('shop_id' => Perm::$shopId));
                        
                        $field['combo_start_time'] = get_date_time();
                        $field['combo_end_time'] = date('Y-m-d H:i:s', strtotime("+$days days"));
                        $field['shop_id'] = Perm::$shopId;
                        $field['shop_name'] = $shop_row['shop_name'];
                        $field['user_id'] = Perm::$userId;
                        $field['user_nickname'] = Perm::$row['user_account'];
                        
                        $op_flag = $this->increaseComboModel->addIncreaseCombo($field, true);
                    }
                    check_rs($op_flag, $rs_row);
                }
                
                if (is_ok($rs_row)) {
                    //在paycenter中添加交易记录
                    $key = Yf_Registry::get('shop_api_key');
                    $url = Yf_Registry::get('paycenter_api_url');
                    $shop_app_id = Yf_Registry::get('shop_app_id');
                    
                    $formvars = array();
                    $formvars['app_id'] = $shop_app_id;
                    $formvars['buyer_user_id'] = Perm::$userId;
                    $formvars['buyer_user_name'] = Perm::$row['user_account'];
                    $formvars['amount'] = $month_price * $month;
                    
                    $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=addCombo&typ=json', $url), $formvars);
                    
                }
                
                if (is_ok($rs_row) && isset($rs) && $rs['status'] == 200 && $this->increaseComboModel->sql->commitDb()) {
                    $msg = __('操作成功！');
                    $status = 200;
                } else {
                    $this->increaseComboModel->sql->rollBackDb();
                    $msg = __('操作失败！');
                    $status = 250;
                }
            } else {
                $msg = __('购买月份必须为正整数！');
                $status = 250;
            }
            
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
        //店铺商品信息和已参加活动的商品
        public function getShopGoods()
        {
            $cond_row = array();
            $increase_id = request_int('id');
            //分页
            $Yf_Page = new Yf_Page();
            $Yf_Page->listRows = request_int('listRows') ? request_int('listRows') : 12;
            $rows = $Yf_Page->listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $rows);
            //需要加入商品状态限定
            $cond_row['shop_id'] = Perm::$shopId;
            
            $goods_name = request_string('goods_name');
            if ($goods_name) {
                $cond_row['common_name:LIKE'] = "%" . $goods_name . "%";
            }
            
            //2017-08-11加入加价购不能添加虚拟商品
            $cond_row['common_is_virtual'] = Goods_CommonModel::GOODS_NORMAL;
            
            $Goods_CommonModel = new Goods_CommonModel();
            //获取一键代发的商品
            $shop_id = Perm::$shopId;
            $supplier_end_list = $Goods_CommonModel->getSupplierSendCommonByShopId($shop_id);
            $supplier_end_common_id = is_array($supplier_end_list) ? array_column($supplier_end_list, 'common_id') : array();
            if ($supplier_end_common_id) {
                $cond_row['common_id:not in'] = $supplier_end_common_id;
            }
            $data = $Goods_CommonModel->getNormalSateGoodsBase($cond_row, array('common_id' => 'DESC'), $page, $rows);   
            $Yf_Page->totalRows = $data['totalsize'];
            $page_nav = $Yf_Page->prompt();

            //店铺下已存在且状态正常的加价购活动
            $cond_rows_increase['shop_id'] = Perm::$shopId;
            $cond_rows_increase['increase_state'] = Increase_BaseModel::NORMAL;
            $increase_rows = $this->increaseBaseModel->getKeyByWhere($cond_rows_increase);
            
            if ($increase_rows) {
                $cond_rows_join['shop_id'] = Perm::$shopId;
                $cond_rows_join['increase_id:IN'] = $increase_rows;
                $cond_rows_join['goods_end_time:>='] = get_date_time();
                //店铺已加入加价购活动的商品ID
                $join_goods_id = $this->increaseGoodsModel->getIncreaseGoodsIdByWhere($cond_rows_join, array());
            } else {
                $join_goods_id = array();
            }
            
            $rows = array();
            foreach ($data['items'] as $key => $value) {
                $rows[$value['goods_id']] = $data['items'][$key];
                $rows[$value['goods_id']]['id'] = $value['goods_id'];
                $rows[$value['goods_id']]['common'] = $value['common_id'];
                $rows[$value['goods_id']]['price'] = format_money($value['goods_price']);
                $rows[$value['goods_id']]['image'] = $value['goods_image'];
                $rows[$value['goods_id']]['name'] = $value['goods_name'];
                
                if (in_array($value['goods_id'], $join_goods_id) || $value['is_join'] == 'true') {
                    $data['items'][$key]['is_join'] = 'true';
                } else {
                    $data['items'][$key]['is_join'] = 'false';
                }
            }
            $rows = encode_json($rows); 

            if ('json' == $this->typ) {
                $this->data->addBody(-140, $data);
            } else {
                include $this->view->getView();
            }
            
        }
        
        //获取店铺可参加换购的商品
        public function getShopGoodsSku()
        {
            $cond_row = array();
            //分页
            $Yf_Page = new Yf_Page();
            $Yf_Page->listRows = request_int('listRows') ? request_int('listRows') : 12;
            $rows = $Yf_Page->listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $rows);
            
            $cond_row['shop_id'] = Perm::$shopId;
            $goods_name = request_string('goods_name');
            
            if ($goods_name) {
                $cond_row['common_name:LIKE'] = "%" . $goods_name . "%";
            }
            //换购商品ID
            $increase_id = request_int('id');
            $redemption_goods_id = $this->increaseRedempGoodsModel->getIncreaseRedempGoodsIdByWhere(array('increase_id' => $increase_id), array());
            
            //2017-08-11加入加价购不能添加虚拟商品
            $cond_row['common_is_virtual'] = Goods_CommonModel::GOODS_NORMAL;
            
            //排除分销商品
            $Goods_CommonModel = new Goods_CommonModel();
            $shop_id = Perm::$shopId;
            $supplier_end_list = $Goods_CommonModel->getSupplierSendCommonByShopId($shop_id);
            $supplier_end_common_id = is_array($supplier_end_list) ? array_column($supplier_end_list, 'common_id') : array();
            if ($supplier_end_common_id) {
                $cond_row['common_id:not in'] = $supplier_end_common_id;
            }
            
            $data = $Goods_CommonModel->getNormalSateGoodsBase($cond_row, array('common_id' => 'DESC'), $page, $rows);
            
            $Yf_Page->totalRows = $data['totalsize'];
            $page_nav = $Yf_Page->prompt();
            
            $rows = array();
            foreach ($data['items'] as $key => $value) {
                $rows[$value['goods_id']] = $data['items'][$key];
                $rows[$value['goods_id']]['id'] = $value['goods_id'];
                $rows[$value['goods_id']]['price'] = format_money($value['goods_price']);
                $rows[$value['goods_id']]['goodsprice'] = $value['goods_price'];
                $rows[$value['goods_id']]['image'] = $value['goods_image'];
                $rows[$value['goods_id']]['name'] = $value['goods_name'];
                
                if (in_array($value['goods_id'], $redemption_goods_id)) {
                    $data['items'][$key]['is_join'] = 'true';
                } else {
                    $data['items'][$key]['is_join'] = 'false';
                }
            }
            
            $rows = encode_json($rows);
            $date_level = request_int('level');
            
            if ('json' == $this->typ) {
                $this->data->addBody(-140, $data);
            } else {
                include $this->view->getView();
            }
            
        }
    }

?>
<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    class Seller_Promotion_GroupBuyCtl extends Seller_Controller
    {
        public $groupBuyBaseModel = null;
        public $groupBuyQuotaModel = null;
        public $groupBuyCatModel = null;
        public $groupBuyAreaModel = null;
        public $goodsBaseModel = null;
        public $shopCostModel = null;
        public $shopBaseModel = null;
        
        public $comboFlag = false; //套餐是否可用
        public $shopInfo = array();  //店铺信息
        public $selfSupportFlag = false;    //是否为自营店铺
        
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
            
            if (!Web_ConfigModel::value('groupbuy_allow')) //团购功能设置，关闭，跳转到卖家首页
            {
                if ("e" == $this->typ) {
                    $this->view->setMet('error');
                    include $this->view->getView();
                    die;
                } else {
                    $data = new Yf_Data();
                    $data->setError(__('团购功能已关闭'), 30);
                    $d = $data->getDataRows();
                    
                    $protocol_data = Yf_Data::encodeProtocolData($d);
                    echo $protocol_data;
                    exit();
                }
            }
            
            $this->groupBuyBaseModel = new GroupBuy_BaseModel();
            $this->groupBuyQuotaModel = new GroupBuy_QuotaModel();
            $this->groupBuyCatModel = new GroupBuy_CatModel();
            $this->groupBuyAreaModel = new GroupBuy_AreaModel();
            $this->goodsBaseModel = new Goods_BaseModel();
            $this->shopCostModel = new Shop_CostModel();
            $this->shopBaseModel = new Shop_BaseModel();
            
            $this->shopInfo = $this->shopBaseModel->getOne(Perm::$shopId);//店铺信息
            $this->selfSupportFlag = ($this->shopInfo['shop_self_support'] == "true" || Web_ConfigModel::value('groupbuy_price') == 0) ? true : false; //是否为自营店铺标志
            
            if ($this->selfSupportFlag) //平台店铺，没有套餐限制
            {
                $this->comboFlag = true;
            } else {
                $this->comboFlag = $this->groupBuyQuotaModel->checkQuotaStateByShopId(Perm::$shopId); //普通店铺需要查询套餐状态
            }
        }
        
        /**
         * 首页
         * 团购活动列表
         *
         * @access public
         */
        public function index()
        {
            $combo_row = array();
            
            //分页
            $Yf_Page = new Yf_Page();
            $Yf_Page->listRows = request_int('listRows') ? request_int('listRows') : 10;
            $rows = $Yf_Page->listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $rows);
            
            $cond_row['shop_id'] = Perm::$shopId;
            
            if (request_int('type')) {
                $cond_row['groupbuy_type'] = request_int('type');
            }
            if (request_int('state')) {
                $cond_row['groupbuy_state'] = request_int('state');
            }
            if (request_string('keyword')) {
                $cond_row['groupbuy_name:LIKE'] = request_string('keyword') . '%';
            }
            
            $data = $this->groupBuyBaseModel->getGroupBuyGoodsList($cond_row, array('groupbuy_id' => 'DESC'), $page, $rows);
            $Yf_Page->totalRows = $data['totalsize'];
            $page_nav = $Yf_Page->prompt();
            
            $shop_type = $this->selfSupportFlag;
            //普通店铺
            if (!$this->selfSupportFlag) {
                $com_flag = $this->comboFlag;
                //套餐可用
                if ($this->comboFlag) {
                    $combo_row = $this->groupBuyQuotaModel->getGroupBuyQuotaByShopID(Perm::$shopId);
                }
            }
            
            if ('json' == $this->typ) {
                $json_data['data'] = $data;
                $json_data['shop_type'] = $shop_type;
                $json_data['combo_flag'] = $this->comboFlag;
                $json_data['combo'] = $combo_row;
                $this->data->addBody(-140, $json_data);
            } else {
//			echo '<pre>';print_r($data);exit;
                include $this->view->getView();
            }
            
        }
        
        /* 添加实物团购页面 */
        public function add()
        {
            $data = array();
            $data['combo'] = array();
            $data['groupbuy_cat'] = $this->groupBuyCatModel->getGroupBuyCatJson(GroupBuy_CatModel::PHYSICALCAT);//获取团购分类，实物团购
            $shop_type = $this->selfSupportFlag;
            $group_id = request_int('groupid');//判断是否是审核失败的团购
            $group_info = $this->groupBuyBaseModel->getOneByWhere(['groupbuy_id' => $group_id]);
            if (!$group_info) {
                $group_info['groupbuy_name'] = '';
                $group_info['groupbuy_remark'] = '';
                $group_info['goods_price'] = '';
                $group_info['groupbuy_price'] = '';
                $group_info['groupbuy_starttime'] = '';
                $group_info['groupbuy_endtime'] = '';
                $group_info['goods_id'] = '';
                $group_info['common_id'] = '';
                $group_info['groupbuy_image'] = '';
                $group_info['groupbuy_image_rec'] = '';
                $group_info['groupbuy_virtual_quantity'] = 0;
                $group_info['groupbuy_upper_limit'] = 0;
            } else {
                $group_info['goods'] = $this->goodsBaseModel->getOneByWhere(['goods_id' => $group_info['goods_id']]);
            }
            //echo '<pre>';print_r($group_info);exit;
            //普通店铺
            if (!$this->selfSupportFlag) {
                if (!$this->comboFlag) {
                    location_to(Yf_Registry::get('url') . '?ctl=Seller_Promotion_GroupBuy&met=combo&typ=e');
                } else {
                    $data['combo'] = $this->groupBuyQuotaModel->getGroupBuyQuotaByShopID(Perm::$shopId);
                }
            } else {
                $data['combo']['combo_endtime'] = date("Y-m-d H:i:s", strtotime("11 june 2030"));
            }
            if (Web_ConfigModel::value('groupbuy_review_day')) {
                $review_days = Web_ConfigModel::value('groupbuy_review_day');
                $data['combo']['combo_starttime'] = date('Y-m-d H:i:s', strtotime("+$review_days days"));
            } else {
                $data['combo']['combo_starttime'] = date('Y-m-d H:i:s');
            }
            
            if ('json' == $this->typ) {
                $this->data->addBody(-140, $data);
            } else {
                $data['groupbuy_cat'] = encode_json($data['groupbuy_cat']);
                include $this->view->getView();
            }
            
        }
        
        /* 添加实物团购 */
        public function addGroupBuy()
        {
            
            if (!$this->comboFlag) {
                $msg_label = __('套餐不可用！');
                $flag = false;
            } else {
                $rs_row = array();
                $group_id = request_int('groupid');
                $field_row['groupbuy_name'] = request_string('groupbuy_name');
                $field_row['groupbuy_remark'] = request_string('groupbuy_remark');
                $field_row['groupbuy_starttime'] = request_string('groupbuy_starttime');
                $field_row['groupbuy_endtime'] = request_string('groupbuy_endtime');
                $field_row['groupbuy_price'] = request_float('groupbuy_price');
                $field_row['goods_id'] = request_int('goods_id');
                $field_row['common_id'] = request_int('common_id');
                $field_row['goods_name'] = request_string('goods_name');
                $field_row['groupbuy_image'] = request_string('groupbuy_image');
                $field_row['groupbuy_upper_limit'] = request_int('groupbuy_upper_limit');
                
                $field_row['groupbuy_image_rec'] = request_string('groupbuy_image_rec');
                $field_row['groupbuy_virtual_quantity'] = request_int('groupbuy_virtual_quantity');
                $field_row['groupbuy_cat_id'] = request_int('groupbuy_cat_id');
                $field_row['groupbuy_scat_id'] = request_int('groupbuy_scat_id');
                $field_row['groupbuy_intro'] = request_string('groupbuy_intro');
                $field_row['shop_id'] = Perm::$shopId;
                $field_row['shop_name'] = $this->shopInfo['shop_name'];
                $field_row['groupbuy_type'] = GroupBuy_BaseModel::ONLINEGBY;
                $checkResult = $this->checkGroupbuyPost($field_row);
                if (!$checkResult['flag']) {
                    return $this->data->addBody(-140, array(), $checkResult['msg'], 250);
                }
                //判断当前是修改还是添加
                $group_info = $this->groupBuyBaseModel->getOneByWhere(['groupbuy_id' => $group_id]);
                
                $this->groupBuyBaseModel->sql->startTransactionDb();
                if ($group_info) {
                    $field_row['groupbuy_state'] = 1;
                    $status_flag = $this->groupBuyBaseModel->editGroupBuy($group_id, $field_row);
//				echo '<pre>';print_r($status_flag);exit;
                } else {
                    $status_flag = $this->groupBuyBaseModel->addGroupBuy($field_row, true);
                }
                check_rs($status_flag, $rs_row);
                $Goods_CommonModel = new Goods_CommonModel();
                $update_flag = $Goods_CommonModel->editCommon(request_int('common_id'), array('common_is_tuan' => 1));
                check_rs($update_flag, $rs_row);
                
                if (is_ok($rs_row) && $this->groupBuyBaseModel->sql->commitDb()) {
                    $flag = true;
                } else {
                    $this->groupBuyBaseModel->sql->rollBackDb();
                    $flag = false;
                }
            }
            
            if ($flag) {
                $msg = __('添加成功！');
                $status = 200;
            } else {
                $msg = $msg_label ? $msg_label : __('添加失败！');
                $status = 250;
            }
            $data = array();
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
        /**
         * 添加团购时检查参数
         *
         * @param type $field_row
         *
         * @return type
         */
        private function checkGroupbuyPost($field_row = array())
        {
            if (!$field_row['groupbuy_name']) {
                return array('flag' => false, 'msg' => __('团购名称不能为空！'));
            }
            if (!$field_row['groupbuy_image']) {
                return array('flag' => false, 'msg' => __('请上传团购活动图片！'));
            }
            if (!$field_row['common_id']) {
                return array('flag' => false, 'msg' => __('请选择参加团购的商品！'));
            }
            $Goods_CommonModel = new Goods_CommonModel();
            $cond_row_goods_common = array('common_id' => $field_row['common_id'], 'shop_id' => Perm::$shopId);
            $goods_common_row = $Goods_CommonModel->getOneByWhere($cond_row_goods_common);
            if (!$goods_common_row) {
                return array('flag' => false, 'msg' => __('请选择参加团购的商品！'));
            }
            if ($field_row['groupbuy_price'] <= 0) {
                return array('flag' => false, 'msg' => __('请输入正确的团购价格！'));
            }
            
            if ($field_row['groupbuy_price'] >= $goods_common_row['common_price']) {
                return array('flag' => false, 'msg' => __('团购价格必须低于商品价格！'));
            }
            if ($goods_common_row['common_limit'] != 0) {
                if ($field_row['groupbuy_upper_limit'] > $goods_common_row['common_limit']) {
                    return array('flag' => false, 'msg' => __('团购限购数不能大于发布商品限购数！'));
                }
            }
            
            return array('flag' => true);
        }
        
        /* 增加虚拟团购页面 */
        public function addVr()
        {
            $data = array();
            $data['combo'] = array();
            $data['groupbuy_cat'] = $this->groupBuyCatModel->getGroupBuyCatJson(GroupBuy_CatModel::VIRTUAL);//获取团购分类，实物团购
            $data['area'] = $this->groupBuyAreaModel->getGroupBuyAreaByWhere(array('groupbuy_area_parent_id' => 0));
            $group_id = request_int('groupid');//判断是否是审核失败的团购
            $group_info = $this->groupBuyBaseModel->getOneByWhere(['groupbuy_id' => $group_id]);
            if (!$group_info) {
                $group_info['groupbuy_name'] = '';
                $group_info['groupbuy_remark'] = '';
                $group_info['goods_price'] = '';
                $group_info['groupbuy_price'] = '';
                $group_info['groupbuy_starttime'] = '';
                $group_info['groupbuy_endtime'] = '';
                $group_info['goods_id'] = '';
                $group_info['common_id'] = '';
                $group_info['groupbuy_image'] = '';
                $group_info['groupbuy_image_rec'] = '';
                $group_info['groupbuy_virtual_quantity'] = 0;
                $group_info['groupbuy_upper_limit'] = 0;
            } else {
                $group_info['goods'] = $this->goodsBaseModel->getOneByWhere(['goods_id' => $group_info['goods_id']]);
            }
            
            $shop_type = $this->selfSupportFlag;
            if (!$this->selfSupportFlag)  //普通店铺
            {
                if (!$this->comboFlag) {
                    location_to(Yf_Registry::get('url') . '?ctl=Seller_Promotion_GroupBuy&met=combo&typ=e');
                } else {
                    $data['combo'] = $this->groupBuyQuotaModel->getGroupBuyQuotaByShopID(Perm::$shopId);
                }
            } else {
                $data['combo']['combo_endtime'] = date("Y-m-d H:i:s", strtotime("11 june 2030"));
            }
            if (Web_ConfigModel::value('groupbuy_review_day')) //预审核时间限制
            {
                $review_days = Web_ConfigModel::value('groupbuy_review_day');
                $data['combo']['combo_starttime'] = date('Y-m-d H:i:s', strtotime("+$review_days days"));
            } else {
                $data['combo']['combo_starttime'] = date('Y-m-d H:i:s');
            }
            
            if ('json' == $this->typ) {
                $this->data->addBody(-140, $data);
            } else {
                $data['groupbuy_cat'] = encode_json($data['groupbuy_cat']);
                include $this->view->getView();
            }
            
        }
        
        /* 添加虚拟团购*/
        public function addVrGroupBuy()
        {
            if (!$this->comboFlag) {
                $msg_label = __('套餐不可用！');
                $flag = false;
            } else {
                $rs_row = array();
                $check_post_data_flag = true;
                $Goods_CommonModel = new Goods_CommonModel();
                
                $field_row['shop_id'] = Perm::$shopId;
                $field_row['shop_name'] = $this->shopInfo['shop_name'];
                $field_row['groupbuy_type'] = GroupBuy_BaseModel::VIRGBY;
                
                $field_row['groupbuy_name'] = request_string('groupbuy_name');
                if (empty($field_row['groupbuy_name'])) {
                    $check_post_data_flag = false;
                    $msg_label = __('团购名称不能为空！');
                }
                $group_id = request_int('groupid');
                $field_row['groupbuy_remark'] = request_string('groupbuy_remark');
                $field_row['groupbuy_starttime'] = request_string('groupbuy_starttime');
                $field_row['groupbuy_endtime'] = request_string('groupbuy_endtime');
                $field_row['groupbuy_price'] = request_float('groupbuy_price');
                $field_row['goods_id'] = request_int('goods_id');
                $field_row['common_id'] = request_int('common_id');
                
                if ($field_row['common_id']) {
                    $cond_row_goods_common['common_id'] = $field_row['common_id'];
                    $cond_row_goods_common['shop_id'] = Perm::$shopId;
                    $goods_common_row = $Goods_CommonModel->getOneByWhere($cond_row_goods_common);
                    
                    if ($goods_common_row) {
                        if ($field_row['groupbuy_price'] > 0) {
                            if ($field_row['groupbuy_price'] >= $goods_common_row['common_price']) {
                                $check_post_data_flag = false;
                                $msg_label = __('团购价格必须低于商品价格！');
                            }
                        } else {
                            $check_post_data_flag = false;
                            $msg_label = __('请输入正确的团购价格！');
                        }
                    } else {
                        $check_post_data_flag = false;
                        $msg_label = __('请选择参加团购的商品！');
                    }
                } else {
                    $check_post_data_flag = false;
                    $msg_label = __('请选择参加团购的商品！');
                }
                
                $field_row['groupbuy_image'] = request_string('groupbuy_image');
                
                if (!$field_row['groupbuy_image']) {
                    $check_post_data_flag = false;
                    $msg_label = __('请上传团购活动图片！');
                }
                
                $field_row['groupbuy_image_rec'] = request_string('groupbuy_image_rec');
                $field_row['groupbuy_virtual_quantity'] = request_int('groupbuy_virtual_quantity');
                $field_row['groupbuy_upper_limit'] = request_int('groupbuy_upper_limit');
                
                if ($field_row['groupbuy_upper_limit'] < 0) {
                    $check_post_data_flag = false;
                    $msg_label = __('限购数量有误！');
                }
                
                $field_row['groupbuy_cat_id'] = request_int('groupbuy_cat_id');
                $field_row['groupbuy_scat_id'] = request_int('groupbuy_scat_id');
                $field_row['groupbuy_city_id'] = request_int('city');
                $field_row['groupbuy_area_id'] = request_int('area');
                $field_row['groupbuy_intro'] = request_string('groupbuy_intro');
                
                if ($check_post_data_flag) {
                    //判断当前是修改还是添加
                    $group_info = $this->groupBuyBaseModel->getOneByWhere(['groupbuy_id' => $group_id]);
                    $this->groupBuyBaseModel->sql->startTransactionDb();
                    if ($group_info) {
                        $field_row['groupbuy_state'] = 1;
                        $status_flag = $this->groupBuyBaseModel->editGroupBuy($group_id, $field_row);
                    } else {
                        $status_flag = $this->groupBuyBaseModel->addGroupBuy($field_row, true);
                    }
                    
                    check_rs($status_flag, $rs_row);
                    
                    $Goods_CommonModel = new Goods_CommonModel();
                    $update_flag = $Goods_CommonModel->editCommon(request_int('common_id'), array('common_is_tuan' => 1));
                    check_rs($update_flag, $rs_row);
                    
                    if (is_ok($rs_row) && $this->groupBuyBaseModel->sql->commitDb()) {
                        $flag = true;
                    } else {
                        $this->groupBuyBaseModel->sql->rollBackDb();
                        $flag = false;
                    }
                } else {
                    $flag = false;
                }
                
            }
            
            if ($flag) {
                $msg = __('添加成功！');
                $status = 200;
            } else {
                $msg = $msg_label ? $msg_label : __('添加失败！');
                $status = 250;
            }
            
            $data = array();
            
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
        /* 验证商品是否已经参加过同时段的活动 */
        public function checkGroupBuyGoods()
        {
            $data = array();
            
            $cond_row_s['common_id'] = request_int('common_id');
            $cond_row_s['groupbuy_state:<='] = GroupBuy_BaseModel::NORMAL;
            $cond_row_s['groupbuy_starttime:<='] = date('Y-m-d H:i:s', strtotime(request_string('start_time')));
            $cond_row_s['groupbuy_endtime:>='] = date('Y-m-d H:i:s', strtotime(request_string('start_time')));
            $row_s = $this->groupBuyBaseModel->getGroupBuyDetailByWhere($cond_row_s);
            
            $cond_row_e['common_id'] = request_int('common_id');
            $cond_row_e['groupbuy_state:<='] = GroupBuy_BaseModel::NORMAL;
            $cond_row_e['groupbuy_starttime:<='] = date('Y-m-d H:i:s', strtotime(request_string('end_time')));
            $cond_row_e['groupbuy_endtime:>='] = date('Y-m-d H:i:s', strtotime(request_string('end_time')));
            $row_e = $this->groupBuyBaseModel->getGroupBuyDetailByWhere($cond_row_e);
            
            if ($row_s || $row_e) {
                if ($row_s['groupbuy_state'] <= GroupBuy_BaseModel::NORMAL || $row_e['groupbuy_state'] <= GroupBuy_BaseModel::NORMAL) //该商品已经参加了同一时段的活动，且状态正常
                {
                    $msg = __('该商品已经参加过同时段的活动！');
                    $status = 250;
                } else {
                    $msg = __('该商品尚未参加过同时段的活动！');
                    $status = 200;
                }
            } else {
                $msg = __('该商品尚未参加过同时段的活动！');
                $status = 200;
            }
            
            $this->data->addBody(-140, $data, $msg, $status);
            
        }
        
        /*
        *购买套餐、套餐续费
         *平台店铺可以免费发布活动
         *后台可设置免费发布活动
         * */
        public function combo()
        {
            if ($this->selfSupportFlag) {
                location_go_back(__('自营店铺或者套餐续费， 不需要设置。'));
                //location_to(Yf_Registry::get('url') . '?ctl=Seller_Promotion_GroupBuy&met=add&typ=e');
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
            $cond_row['activity_type'] = Shop_CostModel::GROUPBUY;
            $data = $this->shopCostModel->listByWhere($cond_row, $order_row, $page, $rows);
            
            $Yf_Page->totalRows = $data['totalsize'];
            $page_nav = $Yf_Page->prompt();
            fb($data);
            fb('data');
            
            if ('json' == $this->typ) {
                $data['groupbuy_price'] = Web_ConfigModel::value('groupbuy_price');
                $this->data->addBody(-140, $data);
            } else {
                include $this->view->getView();
            }
            
        }
        
        /*
         * 在店铺的账期结算中扣除相关费用
         * */
        public function addCombo()
        {
            $data = array();
            $combo_row = array();
            $field = array();
            $rs_row = array();
            
            $month_price = Web_ConfigModel::value('groupbuy_price');
            $month = request_int('month');
            $days = 30 * $month;
            
            if ($month > 0) {
                $this->groupBuyQuotaModel->sql->startTransactionDb();
                //记录到店铺费用表
                $field_row['user_id'] = Perm::$userId;
                $field_row['shop_id'] = Perm::$shopId;
                $field_row['cost_price'] = $month_price * $month;
                $field_row['cost_desc'] = __('店铺购买团购活动消费');
                $field_row['cost_status'] = 0;
                $field_row['cost_time'] = get_date_time();
                $field_row['activity_type'] = Shop_CostModel::GROUPBUY;
                $field_row['activity_price'] = $month_price;
                $field_row['activity_month'] = $month;
                $flag = $this->shopCostModel->addCost($field_row, true);
                check_rs($flag, $rs_row);
                
                if ($flag) {
                    //购买或续费套餐
                    $combo_row = $this->groupBuyQuotaModel->getGroupBuyQuotaByShopID(Perm::$shopId);
                    //记录已经存在，套餐续费
                    if ($combo_row) {
                        //1、原套餐已经过期,更新套餐开始时间和结束时间
                        if (strtotime($combo_row['combo_endtime']) < time()) {
                            $field['combo_starttime'] = get_date_time();
                            $field['combo_endtime'] = date('Y-m-d H:i:s', strtotime("+$days days"));
                        } elseif ((time() >= strtotime($combo_row['combo_starttime'])) && (time() <= strtotime($combo_row['combo_endtime']))) {
                            //2、原套餐尚未过期，只需更新结束时间
                            $field['combo_endtime'] = date('Y-m-d H:i:s', strtotime("+$days days", strtotime($combo_row['combo_endtime'])));
                        }
                        
                        $op_flag = $this->groupBuyQuotaModel->renewGroupBuyCombo($combo_row['combo_id'], $field);
                    } else            //记录不存在，添加套餐
                    {
                        $field['combo_starttime'] = get_date_time();
                        $field['combo_endtime'] = date('Y-m-d H:i:s', strtotime("+$days days"));
                        $field['shop_id'] = Perm::$shopId;
                        $field['shop_name'] = $this->shopInfo['shop_name'];
                        $field['user_id'] = Perm::$userId;
                        $field['user_nickname'] = Perm::$row['user_account'];
                        
                        $op_flag = $this->groupBuyQuotaModel->addGroupBuyCombo($field, true);
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
                
                if (is_ok($rs_row) && isset($rs) && $rs['status'] == 200 && $this->groupBuyQuotaModel->sql->commitDb()) {
                    $msg = __('操作成功！');
                    $status = 200;
                } else {
                    $this->groupBuyQuotaModel->sql->rollBackDb();
                    $msg = __('操作失败！');
                    $status = 250;
                }
            } else {
                $msg = __('购买月份必须为正整数！');
                $status = 250;
            }
            
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
        /**
         * 调用接口
         *获取店铺的实物商品列表
         * 区别虚拟商品与实物商品
         */
        public function getShopGoods()
        {
            $cond_row = array();
            
            //分页
            $Yf_Page = new Yf_Page();
            $Yf_Page->listRows = request_int('listRows') ? request_int('listRows') : 12;
            $rows = $Yf_Page->listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $rows);
            
            //需要加入商品状态限定
            $cond_row['common_state'] = Goods_CommonModel::GOODS_STATE_NORMAL;
            $cond_row['common_verify'] = Goods_CommonModel::GOODS_VERIFY_ALLOW;
            $cond_row['shop_status'] = Shop_BaseModel::SHOP_STATUS_OPEN;
            
            $cond_row['shop_id'] = Perm::$shopId;
            
            if (request_string('goods_typ') == 'virtual') {
                $cond_row['common_is_virtual'] = 1;//是否是虚拟商品
            } else {
                $cond_row['common_is_virtual'] = 0;
            }
            
            $goods_name = request_string('goods_name');
            if ($goods_name) {
                $cond_row['common_name:LIKE'] = "%" . $goods_name . "%";
            }
            $Goods_CommonModel = new Goods_CommonModel();
            //获取一键代发的商品
            $shop_id = Perm::$shopId;
            $supplier_end_list = $Goods_CommonModel->getSupplierSendCommonByShopId($shop_id);
            $supplier_end_common_id = is_array($supplier_end_list) ? array_column($supplier_end_list, 'common_id') : array();
            if ($supplier_end_common_id) {
                $cond_row['common_id:not IN'] = $supplier_end_common_id;
            }
            //排除拼团商品
            $shop_common_id = $Goods_CommonModel->getCommonIdByShopId($shop_id);
            $pintuan_common_id = $Goods_CommonModel->getGoodsIds($shop_common_id);
            $active_common_id = array_unique($pintuan_common_id);//拼团商品common_id
            
            $goods_rows = $Goods_CommonModel->getCommonNormal($cond_row, array('common_id' => 'DESC'), $page, $rows);
            $data = $Goods_CommonModel->getRecommonRow($goods_rows);

            //如果商品参加活动标记
            $common_ids = array_column($data, 'common_id');           
            $GroupBuy_BaseModel = new GroupBuy_BaseModel();
            $check_common_ids = $GroupBuy_BaseModel->getAllActivityCommonId($common_ids);

            //店铺正在进行活动或者即将进行活动的商品common_id
            $activity_common = $GroupBuy_BaseModel->getGroupbuy();
            $activity_common_id = array_column($activity_common, 'common_id');
            $active_ids = array_merge($check_common_ids,$activity_common_id,$active_common_id);
            foreach ($data as $key => $val) {
                // $data[$key]['is_promotion'] = in_array($val['common_id'], $active_ids) ? 1 : 0;
                if(in_array($val['common_id'], $active_ids))
                {
                    $data[$key]['is_join'] = 'true';
                }else{
                    $data[$key]['is_join'] = 'false';
                }
            }
            
            $Yf_Page->totalRows = $goods_rows['totalsize'];
            $page_nav = $Yf_Page->prompt();
            
            if ('json' == $this->typ) {
                $this->data->addBody(-140, $data);
            } else {
                
                include $this->view->getView();
            }
            
        }
        
        //根据团购城市ID查询下级地区
        public function getGroupBuyArea()
        {
            $cond_row['groupbuy_area_parent_id'] = request_int('area_id');
            $data = $this->groupBuyAreaModel->getGroupBuyAreaByWhere($cond_row);
            
            $this->data->addBody(-140, $data);
        }
    }

?>
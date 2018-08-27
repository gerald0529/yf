<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    /**
     * Api接口, 让App等调用
     *
     *
     * @category   Game
     * @package    User
     * @author     Xinze <xinze@live.cn>
     * @copyright  Copyright (c) 2015, 黄新泽
     * @version    1.0
     * @todo
     */
    class Api_Trade_ReturnCtl extends Api_Controller
    {
        
        const PAY_SITE = "http://paycenter.yuanfeng021.com/";
        //const PAY_SITE	 = "http://localhost/repos/paycenter/";
        public $Order_BaseModel = null;
        public $Order_ReturnModel = null;
        public $Order_ReturnReasonModel = null;
        public $Order_GoodsModel = null;
        
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
            $this->Order_BaseModel = new Order_BaseModel();
            $this->Order_ReturnModel = new Order_ReturnModel();
            $this->Order_ReturnReasonModel = new Order_ReturnReasonModel();
            $this->Order_GoodsModel = new Order_GoodsModel();
            
        }
        
        public function getReasonList()
        {
            $page = request_int('page', 1);
            $rows = request_int('rows', 10);
            $oname = request_string('sidx');
            $osort = request_string('sord');
            $cond_row = array();
            $sort = array();
            $sort['order_return_reason_sort'] = "ASC";
            if ($oname != "number") {
                $sort[$oname] = $osort;
            }
            $data = array();
            $data = $this->Order_ReturnReasonModel->getReturnReasonList($cond_row, $sort, $page, $rows);
            $this->data->addBody(-140, $data);
        }
        
        public function addReasonBase()
        {
            $field['order_return_reason_content'] = request_string("order_return_reason_content");
            $field['order_return_reason_sort'] = request_int("order_return_reason_sort");
            $flag = $this->Order_ReturnReasonModel->addReturn($field, true);
            if ($flag) {
                $msg = __('success');
                $status = 200;
            } else {
                $msg = __('failure');
                $status = 250;
            }
            
            $data = array();
            
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
        public function editReason()
        {
            $id = request_int("id");
            $data = $this->Order_ReturnReasonModel->getOne($id);
            $this->data->addBody(-140, $data);
        }
        
        public function editReasonBase()
        {
            $id = request_int("order_return_reason_id");
            $field['order_return_reason_content'] = request_string("order_return_reason_content");
            $field['order_return_reason_sort'] = request_int("order_return_reason_sort");
            $flag = $this->Order_ReturnReasonModel->editReturn($id, $field);
            if ($flag) {
                $msg = __('success');
                $status = 200;
            } else {
                $msg = __('failure');
                $status = 250;
            }
            
            $data = array();
            
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
        public function delReason()
        {
            $id = request_int("id");
            $flag = $this->Order_ReturnReasonModel->removeReturn($id);
            if ($flag) {
                $msg = __('success');
                $status = 200;
            } else {
                $msg = __('failure');
                $status = 250;
            }
            
            $data = array();
            
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
        public function getReturnWaitList()
        {
            $type = request_int("otyp", Order_ReturnModel::RETURN_TYPE_ORDER);
            $return_code = request_string("return_code");
            $seller_user_account = request_string("seller_user_account");
            $buyer_user_account = request_string("buyer_user_account");
            $order_goods_name = request_string("order_goods_name");
            $order_number = request_string("order_number");
            $start_time = request_string("start_time");
            $end_time = request_string("end_time");
            $min_cash = request_float("min_cash");
            $max_cash = request_float("max_cash");
            
            $page = request_int('page', 1);
            $rows = request_int('rows', 10);
            $oname = request_string('sidx');
            $osort = request_string('sord');
            $cond_row = array();
            $sort = array();
            if ($oname != "number") {
                $sort[$oname] = $osort;
            }
            if ($order_number) {
                $cond_row['order_number'] = $order_number;
            }
            if ($return_code) {
                $cond_row['return_code'] = $return_code;
            }
            if ($seller_user_account) {
                $cond_row['seller_user_account'] = $seller_user_account;
            }
            if ($buyer_user_account) {
                $cond_row['buyer_user_account'] = $buyer_user_account;
            }
            if ($order_goods_name) {
                $cond_row['order_goods_name:LIKE'] = '%' . $order_goods_name . '%';
            }
            if ($start_time) {
                $cond_row['return_add_time:>='] = $start_time;
            }
            if ($end_time) {
                $cond_row['return_add_time:<='] = $end_time;
            }
            if ($min_cash) {
                $cond_row['return_cash:>='] = $min_cash;
            }
            if ($max_cash) {
                $cond_row['return_cash:<='] = $max_cash;
            }
            $cond_row['return_state:IN'] = array('0' => Order_ReturnModel::RETURN_SELLER_UNPASS, '1' => Order_ReturnModel::RETURN_SELLER_GOODS);
            $cond_row['return_type'] = $type;
            $cond_row['behalf_deliver:!='] = Order_ReturnModel::BEHALF_DELIVER_SHOP;
            $data = array();
            $data = $this->Order_ReturnModel->getReturnList($cond_row, $sort, $page, $rows);
            $this->data->addBody(-140, $data);
        }
        
        public function getReturnAllList()
        {
            $type = request_int("otyp", Order_ReturnModel::RETURN_TYPE_ORDER);
            $return_code = request_string("return_code");
            $seller_user_account = request_string("seller_user_account");
            $buyer_user_account = request_string("buyer_user_account");
            $order_goods_name = request_string("order_goods_name");
            $order_number = request_string("order_number");
            $start_time = request_string("start_time");
            $end_time = request_string("end_time");
            $min_cash = request_float("min_cash");
            $max_cash = request_float("max_cash");
            
            $page = request_int('page', 1);
            $rows = request_int('rows', 10);
            $oname = request_string('sidx');
            $osort = request_string('sord');
            $cond_row = array();
            $sort = array();
            if ($oname != "number") {
                $sort[$oname] = $osort;
            }
            
            if ($order_number) {
                $cond_row['order_number'] = $order_number;
            }
            if ($return_code) {
                $cond_row['return_code'] = $return_code;
            }
            if ($seller_user_account) {
                $cond_row['seller_user_account'] = $seller_user_account;
            }
            if ($buyer_user_account) {
                $cond_row['buyer_user_account'] = $buyer_user_account;
            }
            if ($order_goods_name) {
                $cond_row['order_goods_name:LIKE'] = '%' . $order_goods_name . '%';
            }
            if ($start_time) {
                $cond_row['return_add_time:>='] = $start_time;
            }
            if ($end_time) {
                $cond_row['return_add_time:<='] = $end_time;
            }
            if ($min_cash) {
                $cond_row['return_cash:>='] = $min_cash;
            }
            if ($max_cash) {
                $cond_row['return_cash:<='] = $max_cash;
            }
            $cond_row['return_type'] = $type;
            $data = array();
            $data = $this->Order_ReturnModel->getReturnList($cond_row, $sort, $page, $rows);
            $this->data->addBody(-140, $data);
        }
        
        public function detail()
        {
            $data['id'] = request_int('id');
            $id = request_int('id');
            $data = $this->Order_ReturnModel->getReturnBase($id);
            $data['order'] = $this->Order_BaseModel->getOne($data['order_number']);
            $this->data->addBody(-140, $data);
        }
        
        public function agree()
        {
            $Order_StateModel = new Order_StateModel();
            $order_return_id = request_int("order_return_id");
            $return_platform_message = request_string("return_platform_message");
            $return = $this->Order_ReturnModel->getOne($order_return_id);
            
            //开启事务
            $this->Order_ReturnModel->sql->startTransactionDb();
            
            //判断平台是否已经审核过
            if ($return['return_state'] < Order_ReturnModel::RETURN_PLAT_PASS) {
                
                //判断商家是否同意退款
                if ($return['return_shop_handle'] == Order_ReturnModel::RETURN_SELLER_UNPASS) {
                    //不同意
                    $data = array();
                    $data['return_platform_message'] = $return_platform_message;
                    $data['return_state'] = Order_ReturnModel::RETURN_PLAT_PASS;
                    $data['return_finish_time'] = get_date_time();
                    $rs_row = array();
                    
                    $edit_flag = $this->Order_ReturnModel->editReturn($order_return_id, $data);
                    check_rs($edit_flag, $rs_row);
                    
                    //根据order_id查找订单信息
                    $order_base = $this->Order_BaseModel->getOne($return['order_number']);
                    
                    //如果是分销商的进货单则同时退掉买家订单
                    if ($order_base['order_source_id']) {
                        $dist_return = $this->Order_ReturnModel->getOneByWhere(array('order_number' => $order_base['order_source_id'], 'return_type' => $return['return_type']));
                        
                        $this->refuseDist($dist_return['order_return_id'], $data);
                    }
                    
                    if ($return['return_goods_return']) {
                        //商家拒绝退款退货3
                        $goods_data['goods_refund_status'] = Order_GoodsModel::REFUND_REF;
                        $edit_flag = $this->Order_GoodsModel->editGoods($return['order_goods_id'], $goods_data);
                        check_rs($edit_flag, $rs_row);
                    } else {
                        $goods_data['goods_return_status'] = Order_GoodsModel::REFUND_REF;
                        $edit_flag = $this->Order_GoodsModel->editGoods($return['order_goods_id'], $goods_data);
                        check_rs($edit_flag, $rs_row);
                    }
                    
                } else {
                    //同意
                    
                    $data = array();
                    $data['return_platform_message'] = $return_platform_message;
                    $data['return_state'] = Order_ReturnModel::RETURN_PLAT_PASS;
                    $data['return_finish_time'] = get_date_time();
                    $rs_row = array();
                    
                    $edit_flag = $this->Order_ReturnModel->editReturn($order_return_id, $data);
                    check_rs($edit_flag, $rs_row);
                    
                    //根据order_id查找订单信息
                    $order_base = $this->Order_BaseModel->getOne($return['order_number']);
                    
                    $data['return_goods_return'] = $return['return_goods_return'];
                    
                    if ($return['return_goods_return']) {
                        $Shop_BaseModel = new Shop_BaseModel();
                        $shop_detail = $Shop_BaseModel->getOne($order_base['shop_id']);
                        /*if ($shop_detail['shop_type'] == 2) {
                            $flag = $this->edit_product($return['order_number'], $return['order_goods_num']);
    
                            $data['edit_product'] = $flag;
                        }*/
                    }
                    
                    //判断该商品是否是三级分销的商品，如果是三级分销的商品需要退还分销佣金
                    $Order_GoodsModel = new Order_GoodsModel();
                    $dc = $Order_GoodsModel->refundDirectsellercommission($return['order_goods_id'], $return['order_goods_num']);
                    
                    //如果是分销商的进货单则同时退掉买家订单
                    if ($order_base['order_source_id']) {
                        $dist_return = $this->Order_ReturnModel->getOneByWhere(array('order_number' => $order_base['order_source_id'], 'return_type' => $return['return_type']));
                        $this->agreeDist($dist_return['order_return_id'], $data);
                    }
                    if ($return['return_goods_return']) {
                        //如果是退货情况下，退还三级分销佣金
                        $Order_GoodsModel->returnDirectsellercommission($return['order_goods_id'], $dc);
                        
                        //商品退换情况为完成2
                        $goods_data['goods_refund_status'] = Order_GoodsModel::REFUND_COM;
                        $edit_flag = $this->Order_GoodsModel->editGoods($return['order_goods_id'], $goods_data);
                        check_rs($edit_flag, $rs_row);
                    } else {
                        
                        /*将商品库存加回商品中*/
                        $Goods_BaseModel = new Goods_BaseModel();
                        $Goods_BaseModel->returnGoodsStock($return['order_goods_id'],$return['order_goods_num'],$return['behalf_deliver']);
                        
                        $goods_data['goods_return_status'] = Order_GoodsModel::REFUND_COM;
                        $edit_flag = $this->Order_GoodsModel->editGoods($return['order_goods_id'], $goods_data);
                        check_rs($edit_flag, $rs_row);
                    }
                    
                    $ogoods_data = array();
                    $ogoods_data['order_goods_returnnum'] = $return['order_goods_num'];
                    $edit_flag = $this->Order_GoodsModel->editGoods($return['order_goods_id'], $ogoods_data, true);
                    check_rs($edit_flag, $rs_row);
                    
                    //退款金额，退货数量，交易佣金退款更新到订单表中
                    $order_edit = array();
                    //判断商品金额是否全都退还，如果全部退还订单状态修改为完成状态(用订单商品数判断)
                    //订单中所有商品数量
                    $order_goods = $this->Order_GoodsModel->getByWhere(array('order_id' => $return['order_number'], 'order_goods_amount:>' => 0));
                    $order_all_goods_num = array_sum(array_column($order_goods, 'order_goods_num'));
                    
                    $where = array(
                        'order_number' => $return['order_number'],
                        'return_state' => Order_ReturnModel::RETURN_PLAT_PASS,
                        'return_shop_handle:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS,
                    );
                    //查找该笔订单已经完成的退款，退货
                    $order_return = $this->Order_ReturnModel->getByWhere($where);
                    //订单已经退还的商品数量
                    $order_return_num = array_sum(array_column($order_return, 'order_goods_num'));
                    
                    $order_edit['order_refund_amount'] = $return['return_cash'];
                    $order_edit['order_return_num'] = $return['order_goods_num'];
                    $order_edit['order_commission_return_fee'] = $return['return_commision_fee'];
                    $order_edit['order_rpt_return'] = $return['return_rpt_cash'];
                    
                    $edit_flag = $this->Order_BaseModel->editBase($return['order_number'], $order_edit, true);
                    check_rs($edit_flag, $rs_row);
                    
                    if ($order_all_goods_num == $order_return_num && $order_base['order_status'] != $Order_StateModel::ORDER_FINISH) {
                        $order_edit_row = array();
                        $order_edit_row['order_status'] = $Order_StateModel::ORDER_FINISH;
                        $order_edit_row['order_finished_time'] = date('Y-m-d H:i:s');
                        $edit_flag2 = $this->Order_BaseModel->editBase($return['order_number'], $order_edit_row);
                        check_rs($edit_flag2, $rs_row);
                        //如果全部退款，增加卖家的流水
                        $formvars = array();
                        $formvars['app_id'] = Yf_Registry::get('shop_app_id');
                        $formvars['user_id'] = $order_base['buyer_user_id']; //收款人
                        $formvars['user_account'] = $order_base['buyer_user_account'];
                        $formvars['seller_id'] = $order_base['seller_user_id']; //付款人
                        $formvars['seller_account'] = $order_base['seller_user_name'];
                        $formvars['amount'] = $return['return_cash'];
                        $formvars['return_commision_fee'] = $return['return_commision_fee'];
                        $formvars['order_id'] = $return['order_number'];
                        $formvars['goods_id'] = $return['order_goods_id'];
                        $formvars['uorder_id'] = $order_base['payment_other_number'];
                        $formvars['payment_id'] = $order_base['payment_id'];
                        
                        
                        $key = Yf_Registry::get('shop_api_key');
                        $url = Yf_Registry::get('paycenter_api_url');
                        
                        $return_rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=refundShopTransfer&typ=json', $url), $formvars);
                        if ($return_rs['status'] != 200) {
                            check_rs(false, $rs_row);
                        }
                    }
                    
                    if ($edit_flag) {
                        //判断该笔订单是否是主账号支付，如果是主账号支付，则将退款金额退还主账号
                        if ($order_base['order_sub_pay'] == Order_StateModel::SUB_SELF_PAY) {
                            $return_user_id = $return['buyer_user_id'];
                            $return_user_name = $return['buyer_user_account'];
                        }
                        if ($order_base['order_sub_pay'] == Order_StateModel::SUB_USER_PAY) {
                            //查找主管账户用户名
                            $User_BaseModel = new  User_BaseModel();
                            $sub_user_base = $User_BaseModel->getOne($order_base['order_sub_user']);
                            
                            $return_user_id = $order_base['order_sub_user'];
                            $return_user_name = $sub_user_base['user_account'];
                        }
                        
                        $key = Yf_Registry::get('shop_api_key');
                        $url = Yf_Registry::get('paycenter_api_url');
                        $shop_app_id = Yf_Registry::get('shop_app_id');
                        
                        $formvars = array();
                        $formvars['app_id'] = $shop_app_id;
                        $formvars['user_id'] = $return_user_id;
                        $formvars['user_account'] = $return_user_name;
                        $formvars['seller_id'] = $return['seller_user_id'];
                        $formvars['seller_account'] = $return['seller_user_account'];
                        $formvars['amount'] = $return['return_cash'];
                        $formvars['return_commision_fee'] = $return['return_commision_fee'];
                        $formvars['order_id'] = $return['order_number'];
                        $formvars['goods_id'] = $return['order_goods_id'];
                        $formvars['payment_id'] = $order_base['payment_id'];
                        
                        //SP分销单没有payment_other_number这个字段值会报错，所以在此做判断
                        if ($order_base['payment_other_number']) {
                            $formvars['uorder_id'] = $order_base['payment_other_number'];
                        } else {
                            $formvars['uorder_id'] = $order_base['payment_number'];
                        }
                        
                        //平台同意退款（只增加买家的流水）
                        $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=refundBuyerTransfer&typ=json', $url), $formvars);
                        $data['for'] = $formvars;
                        if ($rs['status'] == 200) {
                            check_rs(true, $rs_row);
                        } else {
                            check_rs(false, $rs_row);
                        }
                        $edit_flag = is_ok($rs_row);
                    }
                    
                    //如果订单金额全数退还需要将订单商品，支付中心的订单状态修改为订单完成(未发货)
                    if ($order_all_goods_num == $order_return_num && $order_base['order_status'] == Order_StateModel::ORDER_PAYED) {
                        $goods_data['order_goods_status'] = $Order_StateModel::ORDER_FINISH;
                        $order_goods_ids_row = $this->Order_GoodsModel->getByWhere(array('order_id' => $return['order_number']));
                        $order_goods_ids = current($order_goods_ids_row);
                        $ed_flag = $this->Order_GoodsModel->editGoods($order_goods_ids['order_goods_id'], $goods_data);
                        check_rs($ed_flag, $rs_row);
                        
                        //将需要确认的订单号远程发送给Paycenter修改订单状态
                        //远程修改paycenter中的订单状态
                        $key = Yf_Registry::get('shop_api_key');
                        $url = Yf_Registry::get('paycenter_api_url');
                        $shop_app_id = Yf_Registry::get('shop_app_id');
                        $formvars = array();
                        
                        $formvars['order_id'] = $return['order_number'];
                        $formvars['app_id'] = $shop_app_id;
                        $formvars['from_app_id'] = Yf_Registry::get('shop_app_id');
                        
                        $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=confirmOrder&typ=json', $url), $formvars);
                        
                        if ($rs['status'] == 250) {
                            $rs_flag = false;
                            check_rs($rs_flag, $rs_row);
                        }
                    }
                    
                    // 如果是虚拟商品
                    if ($order_base['order_is_virtual']) {
                        $Order_GoodsVirtualCodeModel = new Order_GoodsVirtualCodeModel();
                        // 如果 冻结的数量 = 订单总数量 - 已使用数量，就修改该订单的状态为 已完成，并向平台发起 结算请求 到商家的冻结资金里。
                        
                        // 已使用数量
                        $order_used = $Order_GoodsVirtualCodeModel->getByWhere(array('order_id' => $order_base['order_id'], 'virtual_code_status' => Order_GoodsVirtualCodeModel::VIRTUAL_CODE_USED));
                        $used = count($order_used);
                        $order_return_num_new = $order_return_num + $used;
                        // 如果订单商品的总数量 = 退单商品数量 + 已使用兑换码的商品数量
                        if ($order_all_goods_num == $order_return_num_new) {
                            // 编辑该订单为已完成，并且向 卖家打 去 已使用的兑换码所对应的金额
                            $edit_flag = $this->Order_BaseModel->editBase($order_base['order_id'], array('order_status' => Order_StateModel::ORDER_FINISH, 'order_finished_time' => get_date_time()));
                            check_rs($edit_flag, $rs_row);
                            //远程同步paycenter中的订单状态
                            $key = Yf_Registry::get('shop_api_key');
                            $url = Yf_Registry::get('paycenter_api_url');
                            $shop_app_id = Yf_Registry::get('shop_app_id');
                            $formvars = array();
                            $formvars['order_id'] = $order_base['order_id'];
                            $formvars['app_id'] = $shop_app_id;
                            $formvars['from_app_id'] = Yf_Registry::get('shop_app_id');
                            $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=confirmOrder&typ=json', $url), $formvars);
                            if ($rs['status'] == 250) {
                                check_rs(false, $rs_row);
                            }
                        }
                    }
                }
                $data['rs'] = $rs_row;
                $flag = is_ok($rs_row);
            } else {
                $flag = false;
                $data = array();
            }
            
            if ($flag && $this->Order_ReturnModel->sql->commitDb()) {
                $status = 200;
                $msg = __('success');
                
                /**
                 *  加入统计中心
                 */
                //如果$return['order_goods_id']为0则为退款
                if ($return['return_goods_return']) {
                    $order_goods_data = $this->Order_GoodsModel->getOne($return['order_goods_id']);
                    $order_return_goods_id = $order_goods_data['goods_id'];
                    $order_goods_num = $return['order_goods_num'];
                } else {
                    $order_goods_data = $this->Order_GoodsModel->getGoodsListByOrderId($return['order_number']);
                    if (count($order_goods_data['items']) == 1) {
                        $order_return_goods_id = $order_goods_data['items'][0]['goods_id'];
                    } else {
                        $order_return_goods_id = 0;
                    }
                    $order_goods_num = $order_goods_data['items'][0]['order_goods_num'];
                }
                
                $analytics_data = array(
                    'order_id' => array($return['order_number']),
                    'return_cash' => $return['return_cash'],
                    'order_goods_num' => $order_goods_num,
                    'order_goods_id' => $order_return_goods_id,
                    'status' => 9    //暂时将退款退货统一处理
                );
                Yf_Plugin_Manager::getInstance()->trigger('analyticsUpdateOrderStatus', $analytics_data);
                /******************************************************************/
            } else {
                $this->Order_ReturnModel->sql->rollBackDb();
                $status = 250;
                $msg = __('failure');
            }
            
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
        public function refuseDist($order_return_id, $data)
        {
            $return = $this->Order_ReturnModel->getOne($order_return_id);
            
            $rs_row = array();
            $edit_flag = $this->Order_ReturnModel->editReturn($order_return_id, $data);
            check_rs($edit_flag, $rs_row);
            
            if ($return['return_goods_return']) {
                //商家拒绝退款退货3
                $goods_data['goods_refund_status'] = Order_GoodsModel::REFUND_REF;
                $edit_flag = $this->Order_GoodsModel->editGoods($return['order_goods_id'], $goods_data);
                check_rs($edit_flag, $rs_row);
            } else {
                $goods_data['goods_return_status'] = Order_GoodsModel::REFUND_REF;
                $edit_flag = $this->Order_GoodsModel->editGoods($return['order_goods_id'], $goods_data);
                check_rs($edit_flag, $rs_row);
            }
            
        }
        
        public function agreeDist($order_return_id, $data)
        {
            $Order_StateModel = new Order_StateModel();
            $return = $this->Order_ReturnModel->getOne($order_return_id);
            
            $Order_GoodsModel = new Order_GoodsModel();
            $dc = $Order_GoodsModel->refundDirectsellercommission($return['order_goods_id'], $return['order_goods_num']);
            
            //根据order_id查找订单信息
            $order_base = $this->Order_BaseModel->getOne($return['order_number']);
            
            $rs_row = array();
            
            $edit_flag = $this->Order_ReturnModel->editReturn($order_return_id, $data);
            check_rs($edit_flag, $rs_row);
            
            if ($return['return_goods_return']) {
                //如果是退货情况下，退还三级分销佣金
                $Order_GoodsModel->returnDirectsellercommission($return['order_goods_id'], $dc);
                
                //商品退换情况为完成2
                $goods_data['goods_refund_status'] = Order_GoodsModel::REFUND_COM;
                $edit_flag = $this->Order_GoodsModel->editGoods($return['order_goods_id'], $goods_data);
                check_rs($edit_flag, $rs_row);
            } else {
                $goods_data['goods_return_status'] = Order_GoodsModel::REFUND_COM;
                $edit_flag = $this->Order_GoodsModel->editGoods($return['order_goods_id'], $goods_data);
                check_rs($edit_flag, $rs_row);
            }
            $ogoods_data = array();
            $ogoods_data['order_goods_returnnum'] = $return['order_goods_num'];
            $edit_flag = $this->Order_GoodsModel->editGoods($return['order_goods_id'], $ogoods_data, true);
            check_rs($edit_flag, $rs_row);
            
            $sum_data['order_refund_amount'] = $return['return_cash'];
            $sum_data['order_commission_return_fee'] = $return['return_commision_fee'];
            $edit_flag = $this->Order_BaseModel->editBase($return['order_number'], $sum_data, true);
            check_rs($edit_flag, $rs_row);
            
            //订单中所有商品数量
            $order_goods = $this->Order_GoodsModel->getByWhere(array('order_id' => $return['order_number'], 'order_goods_amount:>' => 0));
            $order_all_goods_num = array_sum(array_column($order_goods, 'order_goods_num'));
            
            //查找该笔订单已经完成的退款，退货
            $order_return = $this->Order_ReturnModel->getByWhere(array(
                'order_number' => $return['order_number'],
                'return_state' => Order_ReturnModel::RETURN_PLAT_PASS
            ));
            //订单已经退还的商品数量
            $order_return_num = array_sum(array_column($order_return, 'order_goods_num'));
            
            if ($order_all_goods_num == $order_return_num && $order_base['order_status'] != $Order_StateModel::ORDER_FINISH) {
                $order_edit_row = array();
                $order_edit_row['order_status'] = $Order_StateModel::ORDER_FINISH;
                
                $edit_flag2 = $this->Order_BaseModel->editBase($return['order_number'], $order_edit_row);
                check_rs($edit_flag2, $rs_row);
            }
            
            if ($edit_flag) {
                $key = Yf_Registry::get('shop_api_key');
                $url = Yf_Registry::get('paycenter_api_url');
                $shop_app_id = Yf_Registry::get('shop_app_id');
                
                $formvars = array();
                $formvars['app_id'] = $shop_app_id;
                $formvars['user_id'] = $return['buyer_user_id'];
                $formvars['user_account'] = $return['buyer_user_account'];
                $formvars['seller_id'] = $return['seller_user_id'];
                $formvars['seller_account'] = $return['seller_user_account'];
                $formvars['amount'] = $return['return_cash'];
                $formvars['return_commision_fee'] = $return['return_commision_fee'];
                $formvars['order_id'] = $return['order_number'];
                $formvars['goods_id'] = $return['order_goods_id'];
                $formvars['uorder_id'] = $order_base['payment_other_number'];
                $formvars['payment_id'] = $order_base['payment_id'];
                
                $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=refundBuyerTransfer&typ=json', $url), $formvars);
            }
            
            //如果订单金额全数退还需要将订单商品，支付中心的订单状态修改为订单完成(未发货)
            if ($order_all_goods_num == $order_return_num && $order_base['order_status'] == Order_StateModel::ORDER_PAYED) {
                $goods_data['order_goods_status'] = $Order_StateModel::ORDER_FINISH;
                $order_goods_ids_row = $this->Order_GoodsModel->getByWhere(array('order_id' => $return['order_number']));
                $order_goods_ids = current($order_goods_ids_row);
                
                $ed_flag = $this->Order_GoodsModel->editGoods($order_goods_ids['order_goods_id'], $goods_data);
                check_rs($ed_flag, $rs_row);
                
                //将需要确认的订单号远程发送给Paycenter修改订单状态
                //远程修改paycenter中的订单状态
                $key = Yf_Registry::get('shop_api_key');
                $url = Yf_Registry::get('paycenter_api_url');
                $shop_app_id = Yf_Registry::get('shop_app_id');
                $formvars = array();
                
                $formvars['order_id'] = $return['order_number'];
                $formvars['app_id'] = $shop_app_id;
                $formvars['from_app_id'] = Yf_Registry::get('shop_app_id');
                
                $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=confirmOrder&typ=json', $url), $formvars);
                
                if ($rs['status'] == 250) {
                    $rs_flag = false;
                    check_rs($rs_flag, $rs_row);
                }
            }
            
        }
        
        //同步审核分销退款/退货订单
        
        function edit_product($order_id, $goods_num)
        {
            $rs_row = array();
            $Goods_CommonModel = new Goods_CommonModel();
            $Goods_BaseModel = new Goods_BaseModel();
            $Order_GoodsModel = new Order_GoodsModel();
            $Order_BaseModel = new Order_BaseModel();
            $Shop_BaseModel = new Shop_BaseModel();
            
            //查找出shop_id
            $order_base = $Order_BaseModel->getOne($order_id);
            $buyer_user_id = $order_base['buyer_user_id'];
            $shop_base = $Shop_BaseModel->getByWhere(array('user_id' => $buyer_user_id));
            $shop_base = current($shop_base);
            $shop_id = $shop_base['shop_id'];
            
            $order_goods_list = $Order_GoodsModel->getByWhere(array('order_id' => $order_id));
            foreach ($order_goods_list as $key => $value) {
                $common_info = $Goods_CommonModel->getOne($value['common_id']);
                
                //查看店铺商品中是否已经有该商品
                $shop_common = $Goods_CommonModel->getOneByWhere(array('shop_id' => $shop_id, 'common_parent_id' => $common_info['common_id'], 'product_is_behalf_delivery' => 0));
                
                $stock = $shop_common['common_stock'] - $goods_num;
                $common_row['common_stock'] = $stock;
                
                $flag = $Goods_CommonModel->editCommon($shop_common['common_id'], $common_row);
                check_rs($flag, $rs_row);
                
                //查看店铺的商品goods_parent_id是否存在
                $shop_base = $Goods_BaseModel->getOneByWhere(array('shop_id' => $shop_id, 'goods_parent_id' => $value['goods_id']));
                //根据商品订单表数据，同步goodbase数据
                $base = $Goods_BaseModel->getOneByWhere(array('goods_id' => $value['goods_id']));
                
                if (!empty($base)) {
                    $base_row = array();
                    $stock = $shop_base['goods_stock'] - $goods_num;
                    $base_row['goods_stock'] = $stock;
                    $flag = $Goods_BaseModel->editBase($shop_base['goods_id'], $base_row, false);
                    check_rs($flag, $rs_row);
                }
                
            }
            
            $flag = is_ok($rs_row);
            
            return $flag;
        }
        
        public function refuse()
        {
            $Order_StateModel = new Order_StateModel();
            $order_return_id = request_int("order_return_id");
            $return_platform_message = request_string("return_platform_message");
            $return = $this->Order_ReturnModel->getOne($order_return_id);
            
            $data['return_platform_message'] = $return_platform_message;
            $data['return_state'] = Order_ReturnModel::RETURN_PLAT_UNPASS;
            $data['return_finish_time'] = get_date_time();
            $rs_row = array();
            $this->Order_ReturnModel->sql->startTransactionDb();
            $edit_flag = $this->Order_ReturnModel->editReturn($order_return_id, $data);
            check_rs($edit_flag, $rs_row);
            
            if ($return['return_state'] == Order_ReturnModel::RETURN_SELLER_UNPASS) {
                //不同意
                if ($return['return_goods_return']) {
                    //商家拒绝退款退货3
                    $goods_data['goods_refund_status'] = Order_GoodsModel::REFUND_REF;
                    $edit_flag = $this->Order_GoodsModel->editGoods($return['order_goods_id'], $goods_data);
                    check_rs($edit_flag, $rs_row);
                } else {
                    $goods_data['goods_return_status'] = Order_GoodsModel::REFUND_REF;
                    $edit_flag = $this->Order_GoodsModel->editGoods($return['order_goods_id'], $goods_data);
                    check_rs($edit_flag, $rs_row);
                }
                
            } else {
                //同意
                if ($return['return_goods_return']) {
                    //商品退换情况为完成2
                    $goods_data['goods_refund_status'] = Order_GoodsModel::REFUND_COM;
                    $edit_flag = $this->Order_GoodsModel->editGoods($return['order_goods_id'], $goods_data);
                    check_rs($edit_flag, $rs_row);
                } else {
                    $goods_data['goods_return_status'] = Order_GoodsModel::REFUND_COM;
                    $edit_flag = $this->Order_GoodsModel->editGoods($return['order_goods_id'], $goods_data);
                    check_rs($edit_flag, $rs_row);
                }
            }
            
            $data['rs'] = $rs_row;
            if ($edit_flag && $this->Order_ReturnModel->sql->commitDb()) {
                $status = 200;
                $msg = __('success');
                
                /**
                 *  加入统计中心
                 */
                //如果$return['order_goods_id']为0则为退款
                if ($return['order_goods_id']) {
                    $order_goods_data = $this->Order_GoodsModel->getOne($return['order_goods_id']);
                    $order_return_goods_id = $order_goods_data['goods_id'];
                    $order_goods_num = $return['order_goods_num'];
                } else {
                    $order_goods_data = $this->Order_GoodsModel->getGoodsListByOrderId($return['order_number']);
                    if (count($order_goods_data['items']) == 1) {
                        $order_return_goods_id = $order_goods_data['items'][0]['goods_id'];
                    } else {
                        $order_return_goods_id = 0;
                    }
                    $order_goods_num = $order_goods_data['items'][0]['order_goods_num'];
                }
                
                $analytics_data = array(
                    'order_id' => array($return['order_number']),
                    'return_cash' => $return['return_cash'],
                    'order_goods_num' => $order_goods_num,
                    'order_goods_id' => $order_return_goods_id,
                    'status' => 9    //暂时将退款退货统一处理
                );
                Yf_Plugin_Manager::getInstance()->trigger('analyticsUpdateOrderStatus', $analytics_data);
                /******************************************************************/
            } else {
                $this->Order_ReturnModel->sql->rollBackDb();
                $status = 250;
                $msg = __('failure');
            }
            $this->data->addBody(-140, $data, $msg, $status);
            
        }
        
        public function CountAmount()
        {
            $order_id = request_string('id');
            $Order_ReturnModel = new Order_ReturnModel();
            $order_ids = explode(',', $order_id);
            $data = $Order_ReturnModel->getByWhere(array('order_return_id:in' => $order_ids));
            $data = array_values($data);
            $money = 0;
            if (!empty($data)) {
                foreach ($data as $key => $value) {
                    $money += $value['return_cash'];
                }
            }
            $result = array();
            $result['money'] = $money;
            $this->data->addBody(-140, $result);
        }
    }

?>

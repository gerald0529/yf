<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    /**
     * @author     Xinze <xinze@live.cn>
     */
    class Order_GoodsVirtualCodeModel extends Order_GoodsVirtualCode
    {
        const VIRTUAL_CODE_NEW = 0;     //虚拟兑换码未使用
        const VIRTUAL_CODE_USED = 1;    //虚拟兑换码已使用
        const VIRTUAL_CODE_FROZEN = 2;    //虚拟兑换码已冻结
        const VIRTUAL_CODE_INVALID = 3;    //虚拟兑换码已失效
        
        public function __construct()
        {
            parent::__construct();
            $this->codeUse = array(
                '0' => __('未使用'),
                '1' => __('已使用'),
                '2' => __('已冻结'),
                '3' => __('已失效'),
            );
        }
        
        public function getVirtualCode($cond_row = array())
        {
            $data = $this->getByWhere($cond_row);
            
            foreach ($data as $key => $val) {
                $data[$key]['code_status'] = $this->codeUse[$val['virtual_code_status']];
            }
            
            return $data;
        }
        
        /**
         * 虚拟兑换
         *
         * @param type $virtual_code_id
         *
         * @return type
         */
        public function virtualExchange($virtual_code_id = 0)
        {
            $rs_row = array();
            if (!$virtual_code_id) {
                return array('status' => false, 'msg' => '请输入虚拟码', 'data' => array());
            }
            $orderBaseModel = new Order_BaseModel();
            $virtual_base = $this->getCode($virtual_code_id);
            if (!$virtual_base) {
                return array('status' => false, 'msg' => '无效的兑换码', 'data' => array());
            }
            
            $virtual_base = pos($virtual_base);
            
            if ($virtual_base['virtual_code_status'] == Order_GoodsVirtualCodeModel::VIRTUAL_CODE_NEW) {
                // 如果订单发生退款申请，商家审核通过以后，需要 退款完成 之后才可以继续兑换余下的兑换码，即需要平台审核完成。
                // 根据$virtual_base['order_id']查询 虚拟订单退款的审核状态
                $orderReturnModel = new Order_ReturnModel();
                $cond_row = array();
                $cond_row['order_number'] = $virtual_base['order_id'];
                $order_return = $orderReturnModel->getByWhere($cond_row);
                $order_return = pos($order_return);
                if (!empty($order_return)) {
                    if ($order_return['return_state'] == $orderReturnModel::RETURN_SELLER_GOODS) {
                        return array('status' => false, 'msg' => '该兑换商品所在订单发生了退款申请，平台尚未审核，请完成退款后继续兑换！', 'data' => array());
                    }
                }
                //开启事物
                $this->sql->startTransactionDb();
                $update['virtual_code_status'] = Order_GoodsVirtualCodeModel::VIRTUAL_CODE_USED;
                $update['virtual_code_usetime'] = date('Y-m-d H:i:s', time());                            //兑换时间
                $result = $this->editCode($virtual_code_id, $update);
                check_rs($result, $rs_row);
                $conid['order_id'] = $virtual_base['order_id'];
                $order_data = $orderBaseModel->getOrderList($conid);
                $order_data = pos($order_data['items']);
                $goods_list = pos($order_data['goods_list']);
                $data = array();
                $data['goods_url'] = $goods_list['goods_link'];
                $data['img_240'] = $goods_list['goods_image'];
                $data['img_60'] = $goods_list['goods_image'];
                $data['goods_name'] = $goods_list['goods_name'];
                $data['order_url'] = $goods_list['order_id'];
                $data['order_sn'] = $goods_list['order_id'];
                $data['buyer_msg'] = $order_data['order_message'];
                
                //判断该笔订单中有多少虚拟商品，多少失效兑换码，如果是最后一笔虚拟商品，则修改订单状态为已完成，将订单金额转到商家账户
                // 虚拟商品个数
                $order_all = $this->getByWhere(array('order_id' => $virtual_base['order_id']));
                $sum = count($order_all);
                
                // 已失效数量
                $order_invalid = $this->getByWhere(array('order_id' => $virtual_base['order_id'], 'virtual_code_status' => Order_GoodsVirtualCodeModel::VIRTUAL_CODE_INVALID));
                $invalid = count($order_invalid);
                
                if ($invalid > 0 && $sum > $invalid) {
                    $sum = $sum - $invalid;
                }
                
                // 已使用数量
                $order_used = $this->getByWhere(array('order_id' => $virtual_base['order_id'], 'virtual_code_status' => Order_GoodsVirtualCodeModel::VIRTUAL_CODE_USED));
                $used = count($order_used);
                
                if ($used == $sum) {
                    $edit_flag = $orderBaseModel->editBase($order_data['order_id'], array('order_status' => Order_StateModel::ORDER_FINISH, 'order_finished_time' => get_date_time()));
                    check_rs($edit_flag, $rs_row);
                    //远程同步paycenter中的订单状态
                    $key = Yf_Registry::get('shop_api_key');
                    $url = Yf_Registry::get('paycenter_api_url');
                    $shop_app_id = Yf_Registry::get('shop_app_id');
                    $formvars = array();
                    $formvars['order_id'] = $order_data['order_id'];
                    $formvars['app_id'] = $shop_app_id;
                    $formvars['from_app_id'] = Yf_Registry::get('shop_app_id');
                    $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=confirmOrder&typ=json', $url), $formvars);
                    if ($rs['status'] == 250) {
                        check_rs(false, $rs_row);
                    }
                }
                $flag = is_ok($rs_row);
                if ($flag && $this->sql->commitDb()) {
                    return array('status' => true, 'msg' => '兑换成功', 'data' => $data);
                } else {
                    $this->sql->rollBackDb();
                    return array('status' => false, 'msg' => '兑换失败', 'data' => array());
                }
            } elseif ($virtual_base['virtual_code_status'] == Order_GoodsVirtualCodeModel::VIRTUAL_CODE_FROZEN) {
                return array('status' => false, 'msg' => '该商品正在退款申请中，兑换码已冻结', 'data' => array());
            } elseif ($virtual_base['virtual_code_status'] == Order_GoodsVirtualCodeModel::VIRTUAL_CODE_USED) {
                return array('status' => false, 'msg' => '该兑换码已兑换', 'data' => array());
            } elseif ($virtual_base['virtual_code_status'] == Order_GoodsVirtualCodeModel::VIRTUAL_CODE_INVALID) {
                return array('status' => false, 'msg' => '该兑换码已失效', 'data' => array());
            } else {
                return array('status' => false, 'msg' => '兑换失败', 'data' => array());
            }
        }
    }

?>
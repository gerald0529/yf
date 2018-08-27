<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    /**
     * Description of Transport_TemplateModel
     * 运费模板设置 Logical model
     *
     * @author     Str <tech40@yuanfeng021.com>
     * @version    shop3.1.3
     *
     */
    class Transport_TemplateModel extends Transport_Template
    {
        
        const TRANSPORT_TEMPLATE_OPEN = 1;            //开启
        const TRANSPORT_TEMPLATE_CLOSE = 0;            //关闭
        
        /**
         * 删除运费模板，同时将该运费模板下的规则一起删除
         *
         * @param type $id
         *
         * @return boolean
         */
        public function templateDel($id, $shop_id = 0)
        {
            //删除模板时必须指定店铺
            $template_info = $this->getOne($id);

            if (!$shop_id || $template_info['shop_id'] != $shop_id || $template_info['status'] == self::TRANSPORT_TEMPLATE_OPEN) {
                return false;
            }
            //开启事物，删除模板和模板下的规则
            $this->sql->startTransactionDb();
            $flag1 = $this->remove($id);
            $rs_rows = array();
            check_rs($flag1, $rs_rows);
            $rule_model = new Transport_Rule();
            $rule_ids = $rule_model->getId(array('transport_template_id' => $id));
            if ($rule_ids) {
                $flag2 = $rule_model->remove($rule_ids);
                check_rs($flag2, $rs_rows);
            }
            if (is_ok($rs_rows) && $this->sql->commitDb()) {
                return true;
            } else {
                $this->sql->rollBackDb();
                return false;
            }
        }
        
        /**
         * 添加模板
         *
         * @param type $template_data
         *
         * @return boolean
         */
        public function templateAdd($template_data = array())
        {
            $where = array('name' => $template_data['name'], 'shop_id' => $template_data['shop_id']);
            $check_result = $this->getByWhere($where);
            if ($check_result) {
                return array('msg' => '模板名称已存在', 'result' => false);
            }
            if ($template_data['status'] == self::TRANSPORT_TEMPLATE_OPEN) {
                //关闭模板
                $res_open_id = $this->getId(array('status' => $template_data['status'], 'shop_id' => $template_data['shop_id']));
                if (is_array($res_open_id) && $res_open_id) {
                    $update_result = $this->editTemplate($res_open_id, array('status' => self::TRANSPORT_TEMPLATE_CLOSE));
                    if ($update_result === false) {
                        return array('msg' => '模板状态修改失败', 'result' => false);
                    }
                }
            } else {
                $res_open_info = $this->getOpenTemplate($template_data['shop_id']);
                if (!$res_open_info) {
                    return array('msg' => '请设置一个模板是启用状态', 'result' => false);
                }
            }
            $template_id = $this->addTemplate($template_data, true);
            if ($template_id > 0) {
                return array('msg' => '模板添加成功', 'result' => $template_id);
            } else {
                return array('msg' => '模板添加失败', 'result' => false);
            }
        }
        
        /**
         * 获取当前正在使用的运费模板
         *
         * @return type
         */
        public function getOpenTemplate($shop_id)
        {
            $where = array('shop_id' => $shop_id, 'status' => self::TRANSPORT_TEMPLATE_OPEN);
            $list = $this->getByWhere($where);
            if (is_array($list) && $list) {
                return array_shift($list);
            }
            return array();
        }
        
        /**
         * 修改模板
         *
         * @param type $template_id
         *
         * @return boolean
         */
        public function templateModify($template_id, $template_data = array())
        {
            if (!$template_id) {
                return false;
            }
            $where = array('id:!=' => $template_id, 'name' => $template_data['name'], 'shop_id' => $template_data['shop_id']);
            $check_result = $this->getByWhere($where);
            if ($check_result) {
                return array('msg' => '模板名称已存在', 'result' => false);
            }
            if ($template_data['status'] == self::TRANSPORT_TEMPLATE_OPEN) {
                //关闭模板
                $res_open_id = $this->getId(array('id:!=' => $template_id, 'status' => $template_data['status'], 'shop_id' => $template_data['shop_id']));
                if (is_array($res_open_id) && $res_open_id) {
                    $update_result = $this->editTemplate($res_open_id, array('status' => self::TRANSPORT_TEMPLATE_CLOSE));
                    if ($update_result === false) {
                        return array('msg' => '模板状态修改失败', 'result' => false);
                    }
                }
            } else {
                $res_open_info = $this->getOpenTemplate($template_data['shop_id']);
                if (!$res_open_info || $res_open_info['id'] == $template_id) {
                    return array('msg' => '请设置一个模板是启用状态', 'result' => false);
                }
            }
            $result = $this->editTemplate($template_id, $template_data);
            if ($result === false) {
                return array('msg' => '模板添加失败', 'result' => false);
            } else {
                return array('msg' => '模板修改成功', 'result' => $template_id);
            }
        }
        
        /**
         * 获取购物车的运费
         *
         * @param type $city
         * @param type $cart_id
         *
         * @return string
         */
        public function cartTransportCost($city = null, $cart_id = array())
        {
            //根据cart_id获取商品的信息
            
            $cond_row = array('cart_id:IN' => $cart_id);
            //购物车中的商品信息
            $CartModel = new CartModel();
            $cart = $CartModel->getCardList($cond_row);
            unset($cart['count']);
            $data = array();
            if (!$city) {
                foreach ($cart as $key => $val) {
                    $data[$key]['cost'] = 0;
                    $data[$key]['con'] = '';
                }
                return $data;
            }
            
            foreach ($cart as $key => $val) {
                $order = array('weight' => 0, 'count' => 0, 'shop_id' => $val['shop_id'], 'price' => $val['sprice']);
                foreach ($val['goods'] as $gk => $gv) {
                    $order['weight'] += $gv['cubage'] * $gv['goods_num'];
                    $order['count'] += $gv['count'];
                }
                $shop_data = $this->shopTransportCost($city, $order);
                $data[$key] = $shop_data;
            }
            return $data;
        }
        
        /**
         * 获取店铺的运费
         *
         * @param type $city_id
         * @param type $order 订单信息，店铺shop_id必填,商品总重量weight可选，商品总数量count可选，商品总体积volume可选, 订单价格price可选，一个店铺的商品视为同一个订单
         *                    商品总重量weight，商品总数量count，商品总体积volume默认值为0
         *
         * @return type
         */
        public function shopTransportCost($city_id, $order = array())
        {
            
            if (!$city_id || !$order['shop_id']) {
                return array('cost' => 0, 'con' => '');
            }
            $price = !isset($order['price']) ? 0 : $order['price'];
            $shop_id = $order['shop_id'];
            $Shop_BaseModel = new Shop_BaseModel();
            $shop = $Shop_BaseModel->getOne($shop_id);
            if (!$shop) {
                return array('cost' => 0, 'con' => '');
            }
            
            $data = array();
            if ($shop['shop_free_shipping'] > 0 && $price >= $shop['shop_free_shipping']) {
                
                //满购包邮
                $data['cost'] = number_format(0, 2);
                $data['con'] = sprintf("满%s免运费", ceil($shop['shop_free_shipping']));
            } else {
                
                //获取运费规则
                $rule_model = new Transport_RuleModel();
                $rule_list_info = $rule_model->getOpenRuleInfo($city_id, $shop_id);
                $chose_transport = isset($rule_list_info['rule_info']) ? $rule_list_info['rule_info'] : array();
                
                //计算运费
                if ($chose_transport) {
                    $cost = $rule_model->countCost($chose_transport, $order);
                    $data['cost'] = $cost;
                    $data['con'] = '';
                } else {
                    $data['cost'] = 0;
                    $data['con'] = '';
                }
            }
            return $data;
        }
        
    }

?>
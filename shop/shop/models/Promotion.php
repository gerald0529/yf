<?php
    
    /**
     * @author     yesai
     */
    class Promotion extends Yf_Model
    {
        //活动对象初始化
        public $GroupBuy_BaseModel = null;  //团购
        
        public $Increase_BaseModel = null;    //加价购
        public $Increase_GoodsModel = null;
        public $Increase_RuleModel = null;
        public $Increase_RedempGoodsModel = null;
        
        public $Discount_BaseModel = null;  //显示折扣
        public $Discount_GoodsModel = null;
        
        public $ManSong_RuleModel = null;  //满减、满送
        public $ManSong_BaseModel = null;
        
        /**
         *
         * @access public
         */
        public function __construct()
        {
            $this->GroupBuy_BaseModel = new GroupBuy_BaseModel();
            $this->Increase_BaseModel = new Increase_BaseModel();
            $this->Increase_GoodsModel = new Increase_GoodsModel();
            $this->Increase_RuleModel = new Increase_RuleModel();
            $this->Increase_RedempGoodsModel = new Increase_RedempGoodsModel();
            $this->Discount_BaseModel = new Discount_BaseModel();
            $this->Discount_GoodsModel = new Discount_GoodsModel();
            $this->ManSong_BaseModel = new ManSong_BaseModel();
            $this->ManSong_RuleModel = new ManSong_RuleModel();
        }
        
        /*
         * 商城所有促销活动说明：
        促销互动分为以下几种：
        1、团购：优先级最高，团购中的商品价格以团购价格为准，团购商品有购买上限限制，超过购买上限的无法加入购物车；
    
        2、限时折扣，如果同一件商品既参加了团购（状态正常），又参加了限时折扣（状态正常），商品价格以团购价格为准，
            否则以限时折扣价格为准，限时折扣活动商品存在购买下限限制，即参加活动的单个商品数量必须满足该数量限制，同一
            订单中，参加同一活动的不同商品数量不做累加判断
    
        3、参加限时折扣的商品，有购买下限限制（默认为1），购物车中的商品价格需根据购买数量调整，不满足下限数量的，商品
            仍以原价格计算
    
        4、加价购活动简介
        参加加价购活动的商品对其本身价格并不会产生影响，即使该商品也在活动规则下的换购商品中
        买家在购物车页面可以自主选择对应的换购商品，每件商品仅可以换购一件，换购商品价格以换购价为准，等级高的规则会覆盖等级低的规格，规则中的
        换购商品数量限制仅用于限定不同的换购商品
        同一订单中，参与同一活动的SKU共同累加的金额用于判断是否满足换购资格；
        同一订单中可以使用多组加价购活动
    
        5、店铺满减满赠针对店铺所有商品，所以当一笔订单的总金额（包含换购商品的金额），满足规格设定的金额后，即满足活动要求，
        对应的优惠包括减现金和送礼品两种方式中的一种或全部，同一个满即送活动最多可以设置三个规则级别，订单满足的活动规则以满足
        最高规则金额的为准，减除的现金和赠送的礼品亦以该规则为准。
        */
        
        public function getGoodsPromotinListByGoodsId($goods_id, $shop_id)
        {
            $ret_rows = array();
            $ret_rows['discount'] = $this->getXianShiGoodsInfoByGoodsID($goods_id);//限时折扣
            $increase_row = $this->getIncreaseDetailByGoodsId($goods_id);//加价购
            if ($increase_row) {
                $ret_rows['increase'] = $increase_row;
            }
            
            $cut_gift_row = $this->getShopGiftInfo($shop_id);
            if ($cut_gift_row) {
                $ret_rows['cut_gift'] = $cut_gift_row;
            }
            
            return $ret_rows;
        }
        
        //查询商品参加的团购活动详情
        
        public function getXianShiGoodsInfoByGoodsID($goods_id)
        {
            $renew_row = array();
            
            if (Web_ConfigModel::value('promotion_allow')) //商品促销开启，包括限时折扣，满送，加价购
            {
                $cond_row['goods_id'] = $goods_id;
                $cond_row['discount_goods_state'] = Discount_GoodsModel::NORMAL;
                $renew_row = $this->Discount_GoodsModel->getDiscountGoodsDetailByWhere($cond_row);
            }
            
            return $renew_row;
        }
        
        //获取商品限时折扣信息
        
        public function getIncreaseDetailByGoodsId($goods_id)
        {
            $increase_row = array();
            $renew_row = array();
            if (Web_ConfigModel::value('promotion_allow')) //商品促销开启，包括限时折扣，满送，加价购
            {
                $cond_row['goods_id'] = $goods_id;
                $cond_row['goods_start_time:<='] = get_date_time();
                $cond_row['goods_end_time:>='] = get_date_time();
                $increase_goods_row = $this->Increase_GoodsModel->getOneIncreaseGoodsByWhere($cond_row);
                if ($increase_goods_row) {
                    $renew_row = $increase_row = $this->Increase_BaseModel->getIncreaseActDetail($increase_goods_row['increase_id']);
                }
            }
            
            return $renew_row;
        }
        
        //加价购信息
        
        public function getShopGiftInfo($shop_id)
        {
            $renew_row = array();
            
            if (Web_ConfigModel::value('promotion_allow')) //商品促销开启，包括限时折扣，满送，加价购
            {
                $cond_row['shop_id'] = $shop_id;
                $cond_row['mansong_state'] = ManSong_BaseModel::NORMAL;
                $row = $this->ManSong_BaseModel->getManSongActItem($cond_row);
                if ($row) {
                    if ($row['mansong_state'] == ManSong_BaseModel::NORMAL && time() >= strtotime($row['mansong_start_time'])) {
                        $renew_row = $row;
                    }
                }
            }
            
            return $renew_row;
        }
        
        /*
         * 店铺满即送信息
         *  parameter shop_id
        */
        
        public function getOrderPromotionInfo($order_info = array())
        {
            $rows = array();
            $cond_row_groupbuy = array();
            
            if (empty($order_info)) {
                //1、获取卖家userId和用户等级
                $userId = $order_info['buyer_id'];
                $userGrade = $order_info['buyer_grade'];
                $shop_id = $order_info['shop_id'];
                
                //2、循环订单中商品
                foreach ($order_info['goods'] as $okey => $goods) {
                    $goods_id = $goods['goods_id'];
                    $cond_row_groupbuy['goods_id'] = $goods_id;
                    $cond_row_groupbuy['groupbuy_state'] = GroupBuy_BaseModel::NORMAL;
                    $groupbuy_row = $this->GroupBuy_BaseModel->getGroupBuyDetailByWhere($cond_row_groupbuy);
                    if ($groupbuy_row) //团购
                    {
                        if (Web_ConfigModel::value('groupbuy_allow')) //团购功能开启
                        {
                            $rows[$shop_id][$goods_id]['groupbuy']['activity_name'] = $groupbuy_row['groupbuy_name'];
                            $rows[$shop_id][$goods_id]['groupbuy']['activity_price'] = $groupbuy_row['groupbuy_price'];
                            if ($groupbuy_row['groupbuy_upper_limit'] > 0) {
                                $rows[$shop_id][$goods_id]['groupbuy']['goods_num'] = $groupbuy_row['groupbuy_upper_limit'];
                            } else {
                                $rows[$shop_id][$goods_id]['groupbuy']['goods_num'] = $groupbuy_row['groupbuy_upper_limit'];
                            }
                        }
                    } else  //限时折扣
                    {
                        if (Web_ConfigModel::value('promotion_allow')) //促销开启
                        {
                            $cond_row_discount['goods_id'] = $goods_id;
                            $cond_row_discount['shop_id'] = $shop_id;
                            $cond_row_discount['goods_lower_limit:<='] = $goods['num'];//限时折扣商品数量限制
                            $cond_row_discount['discount_goods_state'] = Discount_GoodsModel::NORMAL;
                            $discount_row = $this->Discount_GoodsModel->getDiscountGoodsDetailByWhere($cond_row_discount);
                            if ($discount_row && time() >= strtotime($discount_row['goods_end_time'])) {
                                $rows[$shop_id][$goods_id]['discount'] = $discount_row;
                            }
                        }
                    }
                }
            }
            
            return $rows;
        }
        
        /*获取整个订单中所有商品的团购和限时折扣促销信息
        *
         *order_info 订单信息
         *
        */
        
        public function getShopOrderGift($shop_id, $order_price)
        {
            $renew_row = array();
            if (Web_ConfigModel::value('promotion_allow')) //促销开启
            {
                $cond_row['shop_id'] = $shop_id;
                $cond_row['mansong_state'] = ManSong_BaseModel::NORMAL;
                $mansong_rows = $this->ManSong_BaseModel->getManSongActItem($cond_row);
                if ($mansong_rows) {
                    if ($mansong_rows['mansong_state'] == ManSong_BaseModel::NORMAL && time() >= strtotime($mansong_rows['mansong_start_time'])) {
                        foreach ($mansong_rows['rule'] as $key => $rule) {
                            if ($order_price >= $rule['rule_price']) {
                                $renew_row['rule_discount'] = $rule['rule_discount'];
                                $renew_row['rule_price'] = $rule['rule_price'];
                                $renew_row['gift_goods_id'] = $rule['goods_id'];
                                $renew_row['shop_id'] = $shop_id;
                            }
                        }
                    }
                }
            }
            
            return $renew_row;
        }
        
        /*满即送活动信息*/
        
        public function getOrderIncreaseInfo($order_info = array())
        {
            $ret_row = array();
            if (Web_ConfigModel::value('promotion_allow')) //促销开启
            {
                if ($order_info) {
                    $shop_id = $order_info['shop_id'];
                    fb($order_info);
                    $goods_row = array_column($order_info['goods'], 'goods_id');
                    $goods_price_row = array_column($order_info['goods'], 'sumprice', 'goods_id');
                    $cond_row['goods_id:IN'] = $goods_row;
                    $cond_row['shop_id'] = $shop_id;
                    $cond_row['goods_start_time:<='] = get_date_time();
                    $cond_row['goods_end_time:>='] = get_date_time();
                    
                    //查询出该订单中所有参加活动的商品
                    $increase_goods_rows = $this->Increase_GoodsModel->getIncreaseGoodsByWhere($cond_row);
                    
                    if ($increase_goods_rows) {
                        //每个加价购活动下参加活动的商品
                        foreach ($increase_goods_rows as $k => $v) {
                            $increase_row[$v['increase_id']]['goods'][$v['goods_id']] = $v;
                        }
                        
                        $increase_id_row = array_column($increase_goods_rows, 'increase_id');
                        //一笔订单中参加的所有加价购活动
                        $cond_increase_row['increase_id:IN'] = $increase_id_row;
                        $cond_increase_row['shop_id'] = $shop_id;
                        $cond_increase_row['increase_state'] = Increase_BaseModel::NORMAL;
                        $increase_rows = $this->Increase_BaseModel->getIncreaseByWhere($cond_increase_row);
                        
                        if ($increase_rows) {
                            foreach ($increase_rows as $kk => $vv) {
                                $increase_row[$vv['increase_id']]['increase_info'] = $vv;//每个加价购活动信息
                            }
                        }
                        
                        //所有活动的规则信息
                        $cond_rule_row['increase_id:IN'] = $increase_id_row;
                        $order_rule_row['rule_price'] = 'ASC';
                        $increase_rule_rows = $this->Increase_RuleModel->getIncreaseRuleByWhere($cond_rule_row, $order_rule_row);
                        if ($increase_rule_rows) {
                            foreach ($increase_rule_rows as $rk => $rvalue) {
                                $increase_row[$rvalue['increase_id']]['rules'][$rvalue['rule_price']] = $rvalue;
                            }
                        }
                        
                        //活动下的所有规则下的换购商品信息
                        $cond_row_exc['increase_id:IN'] = $increase_id_row;
                        $cond_row_exc['shop_id'] = $shop_id;
                        $redemp_goods_rows = $this->Increase_RedempGoodsModel->getIncreaseRedempGoodsByWhere($cond_row_exc);
                        if ($redemp_goods_rows) {
                            foreach ($redemp_goods_rows as $exk => $exvalue) {
                                $increase_row[$exvalue['increase_id']]['exc_goods'][$exvalue['rule_id']][$exvalue['redemp_goods_id']] = $exvalue;
                            }
                        } else {
                            ;
                        }
                        
                        if ($increase_row) {
                            foreach ($increase_row as $key => $value) {
                                $increase_goods_price = 0;//同一活动下的商品总金额
                                foreach ($value['goods'] as $kk => $vv) {
                                    $increase_goods_price += $goods_price_row[$kk];
                                }
                                //每个活动下的规则
                                $rule_price = 0;
                                
                                //需要根据规则金额排序
                                //$value['rules'] = ksort($value['rules']);
                                // print_r($value['rules']);die;
                                $exc_goods = array();
                                foreach ($value['rules'] as $kk => $vv) {
                                    if ($increase_goods_price >= $vv['rule_price'] && $vv['rule_price'] >= $rule_price) {
                                        if ($value['exc_goods'][$vv['rule_id']]) {
                                            $exc_goods = array_merge($exc_goods, $value['exc_goods'][$vv['rule_id']]);
                                            $ret_row[$key]['exc_goods'] = $exc_goods;
                                            //$ret_row[$key]['exc_goods'] = $value['exc_goods'][$vv['rule_id']];
                                            $ret_row[$key]['exc_goods_limit'] = $vv['rule_goods_limit'];
                                            $ret_row[$key]['rule_info'] = $vv;
                                            $rule_price = $vv['rule_price'];
                                            $ret_row[$key]['shop_id'] = $shop_id;
                                        }
                                        
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            return $ret_row;
        }
        
        //根据订单中已有的商品信息获取对应可以选择的加价购换购商品信息
        /*
         * order_info 订单信息
        order_info['shop_id']
        order_info['goods_list']
        order_info['goods_list']['goods_id']
        order_info['goods_list']['goods_num']
        order_info['goods_list']['goods_price']
        */
        
        /**
         * 拼团
         */
        public function getPintuanDetailByGoodsId($pt_detail_id, $is_all = true)
        {
            if (!$pt_detail_id) {
                return array();
            }
            $pt_detail_model = new PinTuan_Detail();
            $pt_detail = $pt_detail_model->getOneDetailInfo($pt_detail_id, $is_all);
            $pt_detail['detail']['buyer_num'] = $pt_detail_model->getSaleNumsByDetailId($pt_detail['detail']['id']);
            $end_time = strtotime($pt_detail['end_time']);
            $remain_time = $end_time - time();
            $pt_detail['hour'] = floor($remain_time / 3600);
            $pt_detail['min'] = floor(($remain_time - $pt_detail['hour'] * 3600) / 60);
            $pt_detail['second'] = $remain_time - $pt_detail['hour'] * 3600 - $pt_detail['min'] * 60;
            return $pt_detail;
        }
        
        /**
         *
         * @param type $type
         * pintuan 拼团,alone 拼团单买,（ xianshi 限时折扣,jiajiagou 加价购,group 团购；这三种需要时再使用 ）
         *
         * @return type
         */
        public function getPromotion($promotion)
        {
            $goods_id = $promotion['goods_id'];
            $common_id = $promotion['common_id'] ? $promotion['common_id'] : 0;
            $type = $promotion['type'] ? $promotion['type'] : '';
            $detail_id = $promotion['pt_detail_id'] ? $promotion['pt_detail_id'] : 0;
            $types = array('pintuan', 'alone', 'xianshi', 'jiajiagou', 'groupbuy');
            if (!in_array($type, $types)) {
                return array();
            }
            switch ($type) {
                case 'pintuan':
                case 'alone':
                    $pt_detail_model = new PinTuan_Detail();
                    $promotion = $pt_detail_model->getOneDetailInfo($detail_id, false);
                    break;
                case 'xianshi':
                    $promotion = $this->getXianShiGoodsInfoByGoodsID($goods_id);
                    break;
                case 'jiajiagou':
                    $promotion = $this->getIncreaseDetailByGoodsId($goods_id);
                    break;
                case 'groupbuy':
                    $promotion = $this->getGroupBuyInfoByGoodsCommonID($common_id);
                    break;
                default :
                    $promotion = array();
            }
            if ($promotion) {
                $promotion['promotion_type'] = $type;
            }
            return $promotion;
            
        }
        
        public function getGroupBuyInfoByGoodsCommonID($common_id)
        {
            $renew_row = array();
            if (Web_ConfigModel::value('groupbuy_allow'))  //团购开启
            {
                $cond_row['common_id'] = $common_id;
                $cond_row['groupbuy_state'] = GroupBuy_BaseModel::NORMAL;
                $renew_row = $this->GroupBuy_BaseModel->getGroupBuyDetailByWhere($cond_row);
            }
            return $renew_row;
        }
        
        /**
         * 提交订单时，处理活动商品
         * 将拼团信息加入临时表
         */
        public function orderPromotion($promotion = array())
        {
            $type = $promotion['type'] ? $promotion['type'] : 0;
            $mark_id = $promotion['mark_id'] ? $promotion['mark_id'] : 0;
            $detail_id = $promotion['pt_detail_id'] ? $promotion['pt_detail_id'] : 0;
            $types = array('pintuan', 'alone', 'xianshi', 'jiajiagou', 'groupbuy');
            if (!in_array($type, $types)) {
                return array();
            }
            switch ($type) {
                case 'pintuan':
                case 'alone':
                    $data = array(
                        'detail_id' => $detail_id,
                        'mark_id' => $mark_id,
                        'order_id' => $promotion['order_id'],
                        'goods_id' => $promotion['goods_id'],
                        'type' => $type == 'pintuan' ? 0 : 1,
                    );
                    $result = $this->ptTempPromotion($data);
                    break;
                default :
                    $result = false;
            }
            
            return $result;
        }
        
        /**
         * 拼团添加临时表
         *
         * @param type $data
         *
         * @return boolean
         */
        private function ptTempPromotion($data)
        {
            if (!$data['detail_id'] || !$data['goods_id'] || !$data['order_id']) {
                return false;
            }
            
            $data['user_id'] = Perm::$userId;
            $data['create_time'] = date('Y-m-d H:i:s');
            $pt_temp_model = new PinTuan_Temp();
            $result = $pt_temp_model->addInfo($data);
            
            return $result;
        }
        
        /**
         * 检查单个促销商品
         * （目前仅支持拼团）
         *
         * @param type $goods_id
         * @param type $common_id
         * @param type $goods_type
         */
        public function checkPromotion($goods_id, $common_id, $goods_type, $detail_id)
        {
            $result = array('status' => true, 'msg' => '检验通过');
            $now_time = date('Y-m-d H:i:s');
            if ($goods_type == 'pintuan') {
                if (!$detail_id || !$goods_id) {
                    $result['status'] = false;
                    $result['msg'] = '促销信息有误';
                    return $result;
                }
                $detail_model = new PinTuan_Detail();
                $detail_info = $detail_model->getPtByDetailId($detail_id);
                if (!$detail_info || $detail_info['detail']['goods_id'] != $goods_id || $detail_info['start_time'] > $now_time || $detail_info['end_time'] < $now_time) {
                    $result['status'] = false;
                    $result['msg'] = '促销信息有误';
                    return $result;
                }
                
                $mark_id = request_int('mark_id');
                
                if ($mark_id > 0) {
                    $mark_model = new PinTuan_Mark();
                    $mark_info = $mark_model->getOne($mark_id);
                    if ($mark_info['num'] >= $detail_info['person_num']) {
                        $result['status'] = false;
                        $result['msg'] = '该拼团人数已满';
                        return $result;
                    }
                    
//                    $temp_model = new PinTuan_Temp();
//                    $count_order = $temp_model->getCount(array('mark_id' => $mark_id, 'goods_id' => $goods_id, 'detail_id' => $detail_id));
//
//                    if ($count_order > 1) {
//                        $result['status'] = false;
//                        $result['msg'] = '同一个团只能参加一次';
//                        return $result;
//                    }
                    
                }
            }
            return $result;
        }
        
        
        /*************************@author yuli start 如需修改请联系我************************ */
        /**
         * 获取单个商品是否是正在参加团购或者限时折扣
         *
         * @param $goodsId
         *
         * @return int 0->不参加 1->团购 2->限时折扣 3->拼团
         */
        public function getGoodsPromotionStatus($goodsId)
        {
            $goodsModel = new Goods_BaseModel;
            $goods = $goodsModel->getOne($goodsId);
            $shopId = $goods['shop_id'];
            $isGroup = $this->isGroupGoods($goodsId);
            if ($isGroup) {
                return 1;
            }
            $isTimeLimit = $this->isTimeLimitGoods($shopId, $goodsId);
            if ($isTimeLimit) {
                return 2;
            }
            $isTogetherBuy = $this->isTogetherBuy($shopId, $goodsId);
            if ($isTogetherBuy) {
                return 3;
            }
            return 0;
        }
        
        /**
         * 检查单个商品是否是正在参加团购
         *
         * @param $goodsId
         *
         * @return boolean
         */
        private function isGroupGoods($goodsId)
        {
            if (!Web_ConfigModel::value('groupbuy_allow')) {
                return false;
            }
            
            $groupModel = new GroupBuy_BaseModel;
            $groupRows = $groupModel->getByWhere([
                'groupbuy_state' => GroupBuy_BaseModel::NORMAL,
                'goods_id' => $goodsId
            ]);
            
            return empty($groupRows)
                ? false
                : true;
        }
        
        /**
         * 检查单个商品是否是正在参加限时折扣
         *
         * @param $shopId
         * @param $goodsId
         *
         * @return boolean
         */
        private function isTimeLimitGoods($shopId, $goodsId)
        {
            if (!Web_ConfigModel::value('promotion_allow')) {
                return false;
            }
            
            $timeLimitModel = new Discount_BaseModel;
            $timeLimitRows = $timeLimitModel->getByWhere([
                'discount_state' => Discount_BaseModel::NORMAL,
                'shop_id' => $shopId
            ]);
            
            if (empty($timeLimitRows)) {
                return false;
            }
            $timeLimitGoodsModel = new Discount_GoodsModel;
            $discountIds = array_keys($timeLimitRows);
            $timeLimitGoodsRows = $timeLimitGoodsModel->getByWhere([
                'discount_id:IN' => $discountIds,
                'goods_id' => $goodsId
            ]);
            
            return empty($timeLimitGoodsRows)
                ? false
                : true;
        }
        
        /**
         * 检查单个商品是否是正在参加拼团
         *
         * @param $shopId
         * @param $goodsId
         *
         * @return boolean
         */
        private function isTogetherBuy($shopId, $goodsId)
        {
            $togetherBuyModel = new PinTuan_Base;
            $togetherBuyList = $togetherBuyModel->getByWhere([
                'shop_id' => $shopId,
                'status' => PinTuan_Base::NORMAL
            ]);
            
            if (empty($togetherBuyList)) {
                return false;
            }
            
            $togetherBuyIds = array_keys($togetherBuyList);
            $togetherBuyDetailModel = new PinTuan_Detail;
            $togetherBuyDetailList = $togetherBuyDetailModel->getByWhere([
                'pintuan_id:IN' => $togetherBuyIds,
                'goods_id' => $goodsId
            ]);
            
            return empty($togetherBuyDetailList)
                ? false
                : true;
        }
        
        /**
         * 获取单个商品活动价格
         *
         * @param $goodsId
         *
         * @return float
         */
        public function getGoodsPromotionPrice($goodsId)
        {
            $goodsModel = new Goods_BaseModel;
            $goods = $goodsModel->getOne($goodsId);
            $shopId = $goods['shop_id'];
            $groupPrice = $this->getGroupGoodsPrice($goodsId);
            if ($groupPrice) {
                return $groupPrice;
            }
            $timeLimitPrice = $this->getTimeLimitGoodsPrice($shopId, $goodsId);
            if ($timeLimitPrice) {
                return $timeLimitPrice;
            }
            $isTogetherBuy = $this->getTogetherBuyPrice($shopId, $goodsId);
            if ($isTogetherBuy) {
                return 3;
            }
            return 0;
        }
        
        /**
         * 获取单个商品团购价格
         *
         * @param $goodsId
         *
         * @return float
         */
        private function getGroupGoodsPrice($goodsId)
        {
            if (!Web_ConfigModel::value('groupbuy_allow')) {
                return false;
            }
            
            $groupModel = new GroupBuy_BaseModel;
            $groupRows = $groupModel->getOneByWhere([
                'groupbuy_state' => GroupBuy_BaseModel::NORMAL,
                'goods_id' => $goodsId
            ]);
            
            return empty($groupRows)
                ? 0
                : $groupRows['groupbuy_price'];
        }
        
        /**
         * 获取单个商品限时折扣价格
         *
         * @param $shopId
         * @param $goodsId
         *
         * @return float
         */
        private function getTimeLimitGoodsPrice($shopId, $goodsId)
        {
            if (!Web_ConfigModel::value('promotion_allow')) {
                return false;
            }
            
            $timeLimitModel = new Discount_BaseModel;
            $timeLimitRows = $timeLimitModel->getByWhere([
                'discount_state' => Discount_BaseModel::NORMAL,
                'shop_id' => $shopId
            ]);
            
            if (empty($timeLimitRows)) {
                return false;
            }
            $timeLimitGoodsModel = new Discount_GoodsModel;
            $discountIds = array_keys($timeLimitRows);
            $timeLimitGoodsRows = $timeLimitGoodsModel->getOneByWhere([
                'discount_id:IN' => $discountIds,
                'goods_id' => $goodsId
            ]);
            
            return empty($timeLimitGoodsRows)
                ? 0
                : $timeLimitGoodsRows['discount_price'];
        }
        
        /**
         * 获取单个商品拼团价格
         *
         * @param $shopId
         * @param $goodsId
         *
         * @return boolean
         */
        private function getTogetherBuyPrice($shopId, $goodsId)
        {
            $togetherBuyModel = new PinTuan_Base;
            $togetherBuyList = $togetherBuyModel->getByWhere([
                'shop_id' => $shopId,
                'status' => PinTuan_Base::NORMAL
            ]);
            
            if (empty($togetherBuyList)) {
                return false;
            }
            
            $togetherBuyIds = array_keys($togetherBuyList);
            $togetherBuyDetailModel = new PinTuan_Detail;
            $togetherBuyDetailList = $togetherBuyDetailModel->getOneByWhere([
                'pintuan_id:IN' => $togetherBuyIds,
                'goods_id' => $goodsId
            ]);
            
            return empty($togetherBuyDetailList)
                ? 0
                : $togetherBuyDetailList['price'];
        }
        
        /**
         * 获取会员折扣
         *
         * @param $user_id
         *
         * @return int
         */
        public function getUserDiscountRate($user_id)
        {
            $userInfoModel = new User_InfoModel();
            $userGradeModel = new User_GradeModel();
            $user_info = $userInfoModel->getOne($user_id);
            $user_grade = $userGradeModel->getGradeRate($user_info['user_grade']);
            
            return $user_grade
                ? $user_grade['user_grade_rate'] / 100
                : 1; //不享受折扣时，折扣率为100%
        }
        
        /**
         * 获取门店自提订单的促销信息
         * 获取代金券
         * 获取平台红包
         * 注意：该方法并没有判断使用代金券或平台红包的上限，需在业务逻辑中自己判断
         *
         * @param $userId
         * @param $shopId
         *
         * @return array
         */
        public function getChainOrderDiscounts($userId, $shopId)
        {
            return [
                'voucherList' => $this->getShopVoucherByUser($userId, $shopId),
                'redPacketList' => $this->getPlatformRedPacket($userId)
            ];
        }
        
        /**
         * 获取用户可用的代金券列表
         *
         * @param $shopId
         * @param $userId
         *
         * @return array
         */
        public function getShopVoucherByUser($userId, $shopId)
        {
            $voucherModel = new Voucher_BaseModel;
            $voucherList = $voucherModel->getByWhere([
                'voucher_shop_id' => $shopId,
                'voucher_owner_id' => $userId,
                'voucher_state:IN' => [Voucher_BaseModel::UNUSED, Voucher_BaseModel::EXPIRED],
            ]);
            
            if ($voucherList) {
                array_walk($voucherList, function (&$item) {
                    $item['endTime'] = strtotime($item['voucher_end_date']);
                    $item['endDate'] = substr($item['voucher_end_date'], 0, 10);
                });
                $voucherList = array_values($voucherList);
            }
            return $voucherList;
        }
        
        /**
         * 获取订单可用的平台红包列表
         *
         * @param $userId
         *
         * @return array
         */
        public function getPlatformRedPacket($userId)
        {
            $platformModel = new RedPacket_BaseModel;
            $platformRedPacketList = $platformModel->getByWhere([
                'redpacket_owner_id' => $userId,
                'redpacket_state:IN' => [RedPacket_BaseModel::UNUSED, RedPacket_BaseModel::EXPIRED]
            ]);
            
            if ($platformRedPacketList) {
                array_walk($platformRedPacketList, function (&$item) {
                    $item['endTime'] = strtotime($item['redpacket_end_date']);
                    $item['endDate'] = substr($item['redpacket_end_date'], 0, 10);
                });
                
                $platformRedPacketList = array_values($platformRedPacketList);
            }
            return $platformRedPacketList;
        }
        
        /**
         * 对代金券排序
         *
         * @param $voucherList
         * @param $orderAmount 注意：这个金额是减去平台红包
         *
         * @return array
         */
        public function sortVoucher($voucherList, $orderAmount)
        {
            $enabledList = [];
            $disabledList = [];
            $presentTime = time();
            //区分可用、不可用
            foreach ($voucherList as $voucher) {
                $voucher['endTime'] = strtotime($voucher['voucher_end_date']);
                if ($presentTime <= $voucher['endTime'] && $orderAmount >= $voucher['voucher_limit']) {
                    $voucher['permission'] = 1;
                    $enabledList[] = $voucher;
                } else {
                    $voucher['permission'] = 0;
                    $disabledList[] = $voucher;
                }
            }
            
            usort($enabledList, function ($a, $b) { //优惠金额、过期时间正序排序
                if ($a['voucher_price'] > $b['voucher_price']) {
                    return 1;
                } elseif ($a['voucher_price'] < $b['voucher_price']) {
                    return -1;
                }
                
                if ($a['endTime'] > $b['endTime']) {
                    return 1;
                } elseif ($a['endTime'] < $b['endTime']) {
                    return -1;
                }
                
                return 0;
            });
            
            usort($disabledList, function ($a, $b) { //优惠金额、过期时间正序排序
                if ($a['voucher_price'] > $b['voucher_price']) {
                    return 1;
                } elseif ($a['voucher_price'] < $b['voucher_price']) {
                    return -1;
                }
                
                if ($a['endTime'] > $b['endTime']) {
                    return 1;
                } elseif ($a['endTime'] < $b['endTime']) {
                    return -1;
                }
                
                return 0;
            });
            
            return array_merge($enabledList, $disabledList);
        }
        
        /**
         * 对平台红包排序
         *
         * @param $platformRedPacketList
         * @param $orderAmount 注意：这个金额是减去店铺代金券
         *
         * @return array
         */
        public function sortPlatformRedPacketList($platformRedPacketList, $orderAmount)
        {
            $enabledList = [];
            $disabledList = [];
            $presentTime = time();
            //区分可用、不可用
            foreach ($platformRedPacketList as $item) {
                //$item['endTime'] = strtotime($item['redpacket_end_date']);
                if ($presentTime <= $item['endTime'] && $orderAmount >= $item['redpacket_t_orderlimit']) {
                    $item['permission'] = 1;
                    $enabledList[] = $item;
                } else {
                    $item['permission'] = 0;
                    $disabledList[] = $item;
                }
            }
            
            usort($enabledList, function ($a, $b) { //优惠金额、过期时间正序排序
                if ($a['redpacket_price'] > $b['redpacket_price']) {
                    return 1;
                } elseif ($a['redpacket_price'] < $b['redpacket_price']) {
                    return -1;
                }
                
                if ($a['endTime'] > $b['endTime']) {
                    return 1;
                } elseif ($a['endTime'] < $b['endTime']) {
                    return -1;
                }
                
                return 0;
            });
            
            usort($disabledList, function ($a, $b) { //优惠金额、过期时间正序排序
                if ($a['redpacket_price'] > $b['redpacket_price']) {
                    return 1;
                } elseif ($a['redpacket_price'] < $b['redpacket_price']) {
                    return -1;
                }
                
                if ($a['endTime'] > $b['endTime']) {
                    return 1;
                } elseif ($a['endTime'] < $b['endTime']) {
                    return -1;
                }
                
                return 0;
            });
            
            return array_merge($enabledList, $disabledList);
        }
        
        /**
         * 获得门店自提平台红包、店铺代金券最佳组合
         * 要考虑选择顺序
         *
         * @param $voucherList 可能包含过期、当前订单金额不能使用的代金券
         * @param $redPackList 可能包含过期、当前订单金额不能使用的代金券
         * @param $orderAmount
         *
         * @return array(voucherId, redPackId)
         */
        public function getBestDiscountCombination($voucherList, $redPackList, $orderAmount)
        {
            $voucherId = 0;
            $redPackId = 0;
            $voucherPrice = 0;
            $voucherEndTime = 0;
            $redPackPrice = 0;
            $redPackEndTime = 0;
            
            //先把过期优惠券、订单金额不符合使用上限的优惠券过滤
            $presentTime = time();
            $voucherList = array_filter($voucherList, function ($item) use ($presentTime, $orderAmount) {
                return $item['endTime'] < $presentTime || $item['voucher_limit'] > $orderAmount
                    ? false
                    : true;
                
            });
            
            $redPackList = array_filter($redPackList, function ($item) use ($presentTime, $orderAmount) {
                return $item['endTime'] < $presentTime || $item['redpacket_t_orderlimit'] > $orderAmount
                    ? false
                    : true;
                
            });
            
            //考虑只有店铺代金券或者只有平台红包或者两者都无
            if (empty($voucherList) || empty($redPackList)) {
                $deduction = 0;
                if (!empty($voucherList)) {
                    foreach ($voucherList as $voucher) {
                        if ($voucher['voucher_price'] > $deduction || (($voucher['voucher_price'] == $deduction && $voucher['endTime'] < $voucherEndTime))) { //快过期优惠券优先使用
                            $deduction = $voucher['voucher_price'];
                            $voucherId = $voucher['voucher_id'];
                            $voucherPrice = $voucher['voucher_price'];
                            $voucherEndTime = $voucher['endTime'];
                        }
                    }
                }
                
                if (!empty($redPackList)) {
                    foreach ($redPackList as $redPack) {
                        if ($redPack['redpacket_price'] > $deduction || ($redPack['redpacket_price'] == $deduction && $redPack['endTime'] < $redPackEndTime)) { //快过期优惠券优先使用
                            $deduction = $redPack['redpacket_price'];
                            $redPackId = $redPack['redpacket_id'];
                            $redPackPrice = $redPack['redpacket_price'];
                            $redPackEndTime = $redPack['endTime'];
                        }
                    }
                }
                
                return [
                    'voucherId' => $voucherId,
                    'redPackId' => $redPackId,
                    'voucherPrice' => $voucherPrice,
                    'redPackPrice' => $redPackPrice
                ];
            }
            
            //两者都有，但要考虑使用顺序
            foreach ($voucherList as $voucher) {
                foreach ($redPackList as $redPack) {
                    if ($redPack['redpacket_t_orderlimit'] > $orderAmount - $voucher['voucher_price']) {
                        $redPack['redpacket_id'] = 0;
                        $redPack['redpacket_price'] = 0; //不满足使用条件，重置为0
                    }
                    
                    if ($voucher['voucher_price'] + $redPack['redpacket_price'] > $voucherPrice + $redPackPrice) {
                        $voucherId = $voucher['voucher_id'];
                        $redPackId = $redPack['redpacket_id'];
                        $voucherPrice = $voucher['voucher_price'];
                        $redPackPrice = $redPack['redpacket_price'];
                    }
                }
            }
            
            return [
                'voucherId' => $voucherId,
                'redPackId' => $redPackId,
                'voucherPrice' => $voucherPrice,
                'redPackPrice' => $redPackPrice
            ];
        }
        /*************************@author yuli end************************ */
        
        /**
         * 重组确认订单页中加价购商品信息
         *
         * @param $increase_price_info
         *
         * @return array
         */
        public function reformIncrease($increase_price_info)
        {
            $Goods_BaseModel = new Goods_BaseModel();
            $Goods_CatModel = new Goods_CatModel();
            $Shop_ClassBindModel = new Shop_ClassBindModel();
            $Goods_CommonModel = new Goods_CommonModel();
            $increase_shop_row = array();
            $increase_shop_ids = array();
            foreach ($increase_price_info as $key => $val) {
                //获取加价购商品的信息
                $goods_base = $Goods_BaseModel->getOne($val['goods_id']);  //获取加价购商品的信息
                $common_base = $Goods_CommonModel->getOne($goods_base['common_id']);
                $val['goods_name'] = $goods_base['goods_name'];
                $val['goods_image'] = $goods_base['goods_image'];
                $val['cat_id'] = $goods_base['cat_id'];
                $val['common_id'] = $goods_base['common_id'];
                $val['shop_id'] = $goods_base['shop_id'];
                $val['now_price'] = $val['redemp_price'];
                //判断店铺中是否存在自定义的经营类目
                $cat_base = $Shop_ClassBindModel->getByWhere(array('shop_id' => $val['shop_id'], 'product_class_id' => $val['cat_id']));
                if ($cat_base) {
                    $cat_base = current($cat_base);
                    $cat_commission = $cat_base['commission_rate'];
                } else {
                    //获取分类佣金
                    $cat_base = $Goods_CatModel->getOne($val['cat_id']);
                    if ($cat_base) {
                        $cat_commission = $cat_base['cat_commission'];
                    } else {
                        $cat_commission = 0;
                    }
                }
                
                $val['cat_commission'] = $cat_commission;
                $val['commission'] = number_format(($val['redemp_price'] * $cat_commission / 100), 2, '.', '');
                
                //分销佣金
                if (Web_ConfigModel::value('Plugin_Directseller')) {
                    $val['directseller_flag'] = $common_base['common_is_directseller'];
                    if ($common_base['common_is_directseller']) {
                        //产品佣金
                        $val['directseller_commission_0'] = $val['redemp_price'] * $common_base['common_cps_rate'] / 100;
                        $val['directseller_commission_1'] = $val['redemp_price'] * $common_base['common_second_cps_rate'] / 100;
                        $val['directseller_commission_2'] = $val['redemp_price'] * $common_base['common_third_cps_rate'] / 100;
                    }
                }
                
                if (in_array($val['shop_id'], $increase_shop_ids)) {
                    $increase_shop_row[$val['shop_id']]['goods'][] = $val;
                    $increase_shop_row[$val['shop_id']]['price'] += $val['redemp_price'] * $val['goods_num'];
                    $increase_shop_row[$val['shop_id']]['commission'] += $val['commission'];
                    
                    if (Web_ConfigModel::value('Plugin_Directseller')) {
                        $increase_shop_row[$val['shop_id']]['directseller_commission'] += $val['directseller_commission_0'] + $val['directseller_commission_1'] + $val['directseller_commission_2'];
                        $increase_shop_row[$val['shop_id']]['directseller_flag'] = $common_base['common_is_directseller'];
                    }
                } else {
                    $increase_shop_ids[] = $val['shop_id'];
                    $increase_shop_row[$val['shop_id']]['goods'][] = $val;
                    $increase_shop_row[$val['shop_id']]['price'] = $val['redemp_price'] * $val['goods_num'];
                    $increase_shop_row[$val['shop_id']]['commission'] = $val['commission'];
                    
                    if (Web_ConfigModel::value('Plugin_Directseller')) {
                        $increase_shop_row[$val['shop_id']]['directseller_commission'] = $val['directseller_commission_0'] + $val['directseller_commission_1'] + $val['directseller_commission_2'];
                        $increase_shop_row[$val['shop_id']]['directseller_flag'] = $common_base['common_is_directseller'];
                    }
                }
            }
            
            return $increase_shop_row;
        }
    }

?>
<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}

/**
 * @author     Yf <service@yuanfeng.cn>
 */
class Order_BaseModel extends Order_Base
{

    const ORDER_IS_VIRTUAL = 1;            //虚拟订单
    const VIRTUAL_USED = 1;                //虚拟订单已使用
    const VIRTUAL_UNUSE = 0;                    //虚拟订单未使用
    const ORDER_IS_REAL = 0;                    //实物订单
    const IS_BUYER_CANCEL = 1;                //买家取消订单
    const IS_SELLER_CANCEL = 2;                //卖家取消订单
    const IS_ADMIN_CANCEL = 3;                //平台取消
    const IS_NOT_SETTLEMENT = 0;                //未结算
    const IS_SETTLEMENT = 1;                    //已结算

    const NO_BUYER_HIDDEN = 0;                //买家不隐藏订单
    const NO_SELLER_HIDDEN = 0;                //卖家不隐藏订单
    const NO_SUBUSER_HIDDEN = 0;                //主管账号不隐藏订单

    const IS_BUYER_HIDDEN = 1;                //买家隐藏订单
    const IS_SELLER_HIDDEN = 1;                //卖家隐藏订单
    const IS_SUBUSER_HIDDEN = 1;                //主管账号隐藏订单

    const IS_BUYER_REMOVE = 2;                //买家删除订单
    const IS_SELLER_REMOVE = 2;                //卖家删除订单
    const IS_SUBUSER_REMOVE = 2;               //主账号删除订单

    const RETURN_ALL = 2;
    const RETURN_SOME = 1;

    const REFUND_NO = 0;
    const REFUND_IN = 1;
    const REFUND_COM = 2;

    //订单取消身份
    const CANCEL_USER_BUYER = 1;
    const CANCEL_USER_SELLER = 2;
    const CANCEL_USER_SYSTEM = 3;

    //买家是否评价
    const BUYER_EVALUATE_NO = 0;
    const BUYER_EVALUATE_YES = 1;
    const BUYER_EVALUATE_AGAIN = 2; //已追加评价


    //买家是否评价
    const SELLER_EVALUATE_NO = 0;
    const SELLER_EVALUATE_YES = 1;

    //订单来源
    const FROM_PC       = 1;    //来源于pc端
    const FROM_WAP      = 2;    //来源于WAP手机端
    const FROM_WEBPOS   = 3;    //来源于WEBPOS线下下单
    //支付方式
    const PAY_LINE 		= 1;  	//在线支付
    const PAY_DELIVERY 	= 2; 	//货到付款


    //状态
    public static $state = array(
        '1' => 'wait_operate',
        //已出账
        '2' => 'seller_comfirmed',
        //商家已确认
        '3' => 'platform_comfirmed',
        //平台已审核
        '4' => 'finish',
        //结算完成
    );

    public static $orderType = array(
        //虚拟订单
        '0' => 'is_physical',
        //实物订单
        '1' => 'is_virtaul',
    );

    public static $orderEvaluatBuyer = array(
        //买家已评价
        '1' => 'is_evaluated',
        //买家未评价
        '0' => 'is_uevaluate',
    );

    public static $orderEvaluatSeller = array(
        //买家已评价
        '1' => 'is_evaluated',
        //买家未评价
        '0' => 'is_uevaluate',
    );

    public function __construct()
    {
        parent::__construct();


        $this->cancelIdentity = array(
            '1' => __('买家'),
            '2' => __('商家'),
            '3' => __('系统'),
        );

        $this->goodsRefundState = array(
            '0' => __("无退货"),
            //无退货
            '1' => __("退货中"),
            //退货中
            '2' => __("退货完成"),
            //退货完成
            '3' =>__("商家拒绝退货"),
        );

        $this->goodsReturnState = array(
            '0' => __("无退款"),
            //无退货
            '1' => __("退款中"),
            //退货中
            '2' => __("退款完成"),
            //退货完成
            '3' => __("商家拒绝退款"),
        );


    }


    /**
     * 读取分页列表
     * Zhuyt
     * @param  int $config_key 主键值
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getBaseList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
    {
        $data = $this->listByWhere($cond_row, $order_row, $page, $rows);

        $Shop_BaseModel = new Shop_BaseModel();
        $Order_GoodsModel = new Order_GoodsModel();
        $Order_StateModel = new Order_StateModel();
        $Goods_EvaluationModel = new Goods_EvaluationModel();
        $PinTuan_TempModel = new PinTuan_Temp();
        if ($data['items']) {
            foreach ($data['items'] as $key => $val) {
                //若是待付款订单，计算系统取消订单时间
                if ($val['order_status'] == Order_StateModel::ORDER_WAIT_PAY) {
                    $data['items'][$key]['cancel_time'] = date('Y-m-d H:00:00', strtotime($val['order_create_time']) + Yf_Registry::get('wait_pay_time'));

                    if ($data['items'][$key]['cancel_time'] <= get_date_time()) {
                        //修改订单状态 - 将订单状态改为取消
                        $this->cancelOrder($val['order_id']);
                    }
                }

                //若是已发货订单，计算系统自动确认收货时间
                if ($val['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS) {
                    //$data['items'][$key]['confirm_time'] = date('Y-m-d H:i:s', strtotime($val['order_shipping_time']) + Yf_Registry::get('confirm_order_time'));

                    //if($data['items'][$key]['confirm_time'] <= get_date_time())
                    if ($val['order_receiver_date'] <= get_date_time()) {
                        //修改订单状态 - 将订单状态改为已收货
                        //虚拟订单过期自动退款（未退款）
                        if($val['order_is_virtual'] == Order_BaseModel::ORDER_IS_VIRTUAL && $val['order_refund_status'] == Order_BaseModel::REFUND_NO)
                        {
                            $this->virtualReturn($val['order_id']);
                        }
                        else
                        {
                            $this->confirmOrder($val['order_id']);
                        }
                    }
                }
                //取出物流公司名称
                if (!empty($val['order_shipping_express_id'])) {
                    $expressModel = new ExpressModel();
                    $express_base = $expressModel->getExpress($val['order_shipping_express_id']);
                    $express_base = pos($express_base);
                    $data['items'][$key]['express_name'] = $express_base['express_name'];
                } else {
                    $data['items'][$key]['express_name'] = '';
                }

                //判断是否拼团
                $pintuan_temp = $PinTuan_TempModel->getPtInfoByOrderId($val['order_id']);
                if($pintuan_temp)
                {
                    $data['items'][$key]['pintuan_temp_order'] = 1;
                }else
                {
                    $data['items'][$key]['pintuan_temp_order'] = 0;
                }
            }
        }

        //取出所有shop_id 判断为哪家店铺的商品
        $shop_ids = array_column($data['items'], 'shop_id');
        if (!empty($shop_ids)) {
            $cond_row = array();
            $cond_row['shop_id:IN'] = $shop_ids;
            $shop_list = $Shop_BaseModel->getByWhere($cond_row, array());
        }
        $shop_name_list = array();
        if(!empty($shop_list)){
            foreach($shop_list as $val){
                $shop_name_list[$val['shop_id']] = $val['shop_name'];
            }
        }

        if ($data['items']) {
            foreach ($data['items'] as $key => $val) {
                $order_nums = 0;

                $data['items'][$key]['order_state_con'] = $Order_StateModel->orderState[$val['order_status']];
                $data['items'][$key]['order_refund_status_con'] = $Order_StateModel->orderRefundState[$val['order_refund_status']];
                $data['items'][$key]['shop_name'] = $shop_name_list[$val['shop_id']];
                //若是待付款订单，计算系统取消订单时间
                if ($val['order_status'] == Order_StateModel::ORDER_WAIT_PAY) {
                    $data['items'][$key]['cancel_time'] = date('Y-m-d H:00:00', strtotime($val['order_create_time']) + Yf_Registry::get('wait_pay_time'));
                }

                //若是已发货订单，计算系统自动确认收货时间
                /*if ($val['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS)
                {
                    $data['items'][$key]['confirm_time'] = date('Y-m-d H:i:s', strtotime($val['order_shipping_time']) + Yf_Registry::get('confirm_order_time'));
                }*/

                //若为退款中订单，则查找退款单id
                if ($val['order_refund_status'] != Order_StateModel::ORDER_REFUND_NO) {
                    $Order_ReturnModel = new Order_ReturnModel();
                    $order_return_id = $Order_ReturnModel->getKeyByWhere(array('order_number' => $val['order_id'], 'order_goods_id' => '0'));
                    $data['items'][$key]['order_return_id'] = $order_return_id[0];
                }

                //放入店铺信息
                $order_goods[$key]['shop_self_support'] = $shop_list[$val['shop_id']]['shop_self_support'];
                //查找订单商品
                $order_goods = $Order_GoodsModel->getByWhere(array('order_id' => $val['order_id']));

                foreach ($order_goods as $okey => $oval) {

                    $order_goods[$okey]['spec_text'] = $oval['order_spec_info'] ? implode('，', $oval['order_spec_info']) : '';
                    //判断该订单商品被评论的次数
                    $goods_evaluation_row = array();
                    $goods_evaluation_row['order_id'] = $val['order_id'];
                    $goods_evaluation_row['goods_id'] = $oval['goods_id'];
                    $goods_evaluation = $Goods_EvaluationModel->getByWhere($goods_evaluation_row);

                    $order_goods[$okey]['evaluation_count'] = count($goods_evaluation);

                    //判断订单商品的退货状态
                    $order_goods[$okey]['goods_refund_status_con'] = $this->goodsRefundState[$oval['goods_refund_status']];
                    $order_goods[$okey]['goods_return_status_con'] = $this->goodsReturnState[$oval['goods_return_status']];

                    //查找订单商品的市场价格
                    $Goods_BaseModel = new Goods_BaseModel();
                    $goods_info = $Goods_BaseModel->getOne($oval['goods_id']);
                    $order_goods[$okey]['old_price'] = $goods_info['goods_market_price'];
                    $order_goods[$okey]['is_del'] = $goods_info['is_del'];

                    //查找退货id
                    if ($oval['goods_refund_status'] !== Order_StateModel::ORDER_GOODS_RETURN_NO) {
                        $Order_ReturnModel = new Order_ReturnModel();
                        $order_goods_return_id = $Order_ReturnModel->getKeyByWhere(array('order_number' => $val['order_id'],
                            'order_goods_id' => $oval['order_goods_id'],
                            'return_type' => 2,
                        ));
                        $order_goods[$okey]['order_refund_id'] = $order_goods_return_id[0];

                    }

                    //查找退款id
                    if ($oval['goods_refund_status'] !== Order_StateModel::ORDER_GOODS_RETURN_NO) {
                        $Order_ReturnModel = new Order_ReturnModel();
                        $order_goods_return_id = $Order_ReturnModel->getKeyByWhere(array('order_number' => $val['order_id'],
                            'order_goods_id' => $oval['order_goods_id'],
                            'return_type' => 1,));
                        $order_goods[$okey]['order_return_id'] = $order_goods_return_id[0];
                    }

                    //如果订单是虚拟订单
                    if($val['order_is_virtual'] && $oval['goods_refund_status'] !== Order_StateModel::ORDER_GOODS_RETURN_NO)
                    {
                        $Order_ReturnModel = new Order_ReturnModel();
                        $order_goods_return_id = $Order_ReturnModel->getKeyByWhere(array('order_number' => $val['order_id'],
                            'order_goods_id' => $oval['order_goods_id'],
                            'return_type' => 3,));
                        $order_goods[$okey]['order_return_id'] = $order_goods_return_id[0];
                    }

                    $order_nums += $oval['order_goods_num'];

                    //商品规格
                    if ($oval['order_spec_info']){
                        $order_goods[$okey]['title_order_spec_info'] = implode(",",$oval['order_spec_info']);
                    }

                }

                //若是该订单已完成，判断其交易投诉的有效时间
                $Web_ConfigModel = new Web_ConfigModel();
                $day = $Web_ConfigModel->getOne('complain_datetime');
                $day = $day['config_value'];
                $data['items'][$key]['complain_day'] = $day;
                if ($val['order_status'] == Order_StateModel::ORDER_FINISH) {

                    $comtime = $day * 86400;

                    $complain_time = strtotime($val['order_finished_time']) + $comtime;

                    //当前时间在投诉有效期内
                    if ($complain_time > time()) {
                        $data['items'][$key]['complain_status'] = 1;
                    } else {
                        $data['items'][$key]['complain_status'] = 0;
                    }
                } else {
                    $data['items'][$key]['complain_status'] = 0;
                }


                $data['items'][$key]['goods_list'] = array_values($order_goods);

                $data['items'][$key]['order_nums'] = $order_nums;
            }
        }
        return $data;
    }

    /*
     *  zhuyt
     *
     * 订单详情
     */
    public function getOrderDetail($order_id = null)
    {
        $data = $this->getOneByWhere(array('order_id' => $order_id));

        $Order_StateModel = new Order_StateModel();

        $data['order_state_con'] = $Order_StateModel->orderState[$data['order_status']];

        //订单退款状态
        $data['order_refund_status_con'] = $Order_StateModel->orderRefundState[$data['order_refund_status']];

        //若为虚拟订单并且虚拟兑换码已发放,计算还有多少未使用的兑换码，虚拟商品是否支付过期退款，虚拟商品的过期时间
        if ($data['order_status'] != Order_StateModel::ORDER_WAIT_PAY && $data['order_is_virtual'] == Order_BaseModel::ORDER_IS_VIRTUAL)
        {
            $Order_GoodsVirtualCodeModel = new Order_GoodsVirtualCodeModel();
            $cond_row                    = array();
            $cond_row['order_id']        = $data['order_id'];

            $data['code_list'] = $Order_GoodsVirtualCodeModel->getVirtualCode($cond_row);

            $cond_row['virtual_code_status'] = Order_GoodsVirtualCodeModel::VIRTUAL_CODE_NEW;
            $new_code                        = $Order_GoodsVirtualCodeModel->getVirtualCode($cond_row);
            $data['new_code']                = count($new_code);


            //获取所有的订单商品id
            $code_order_goods_id = array_column($data['code_list'], 'order_goods_id');
            $Order_GoodsModel    = new Order_GoodsModel();
            $Goods_CommonModel   = new Goods_CommonModel();

            //查找订单商品
            $code_order_goods = $Order_GoodsModel->getByWhere(array('order_goods_id:IN' => $code_order_goods_id));

            //查找订单商品的common信息
            $code_order_goods_common = array_column($code_order_goods, 'common_id');

            foreach ($code_order_goods_common as $commonkey => $commonval)
            {
                $code_order_goods_common[$commonkey] = $Goods_CommonModel->getOne($commonval);
            }

            foreach ($data['code_list'] as $codekey => $codeval)
            {
                //查找订单商品
                $data['code_list'][$codekey]['common_virtual_refund'] = $code_order_goods_common[$data['code_list'][$codekey]['order_goods_id']]['common_virtual_refund'];
                $data['code_list'][$codekey]['common_virtual_date']   = $code_order_goods_common[$data['code_list'][$codekey]['order_goods_id']]['common_virtual_date'];
                $data['common_virtual_date']                          = $code_order_goods_common[$data['code_list'][$codekey]['order_goods_id']]['common_virtual_date'];
            }
        }

        //若是待付款订单，计算系统取消订单的时间
        if ($data['order_status'] == Order_StateModel::ORDER_WAIT_PAY) {
            $data['cancel_time'] = date('Y-m-d H:00:00', strtotime($data['order_create_time']) + Yf_Registry::get('wait_pay_time'));
        }

        //若是已发货订单，计算系统自动确认收货时间
        /*if ($data['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS)
        {
            $data['confirm_time'] = date('Y-m-d H:i:s', strtotime($data['order_shipping_time']) + Yf_Registry::get('confirm_order_time'));
        }*/

        //若为退款中订单，则查找退款单id
        if ($data['order_refund_status'] != Order_StateModel::ORDER_REFUND_NO) {
            $Order_ReturnModel = new Order_ReturnModel();
            $order_return_id = $Order_ReturnModel->getKeyByWhere(array('order_number' => $data['order_id'], 'order_goods_id' => '0'));
            $data['order_return_id'] = $order_return_id[0];
        }

        //若是已完成订单，计算交易投诉的有效时间
        $Web_ConfigModel = new Web_ConfigModel();
        $day = $Web_ConfigModel->getOne('complain_datetime');
        $day = $day['config_value'];
        $data['complain_day'] = $day;
        if ($data['order_status'] == Order_StateModel::ORDER_FINISH) {
            $comtime = $day * 86400;

            $complain_time = strtotime($data['order_finished_time']) + $comtime;

            //当前时间在投诉有效期内
            if ($complain_time > time()) {
                $data['complain_status'] = 1;
            } else {
                $data['complain_status'] = 0;
            }
        } else {
            $data['complain_status'] = 0;
        }

        //获取订单评价状态
        $data['order_buyer_evaluation_status_con'] = Order_BaseModel::$orderEvaluatBuyer[$data['order_buyer_evaluation_status']];


        //获取订单取消者身份
        if ($data['order_cancel_identity']) {
            $data['cancel_identity'] = $this->cancelIdentity[$data['order_cancel_identity']];
        }

        //查找店铺信息
        $Shop_CompanyModel = new Shop_CompanyModel();
        $Shop_Company = $Shop_CompanyModel->getOne($data['shop_id']);
        $Shop_BaseModel = new Shop_BaseModel();
        $shop_base = $Shop_BaseModel->getOne($data['shop_id']);
        $data['shop_name'] = $shop_base['shop_name'];
        $data['shop_address'] = $Shop_Company['shop_company_address'];
        $data['shop_phone'] = $Shop_Company['company_phone']?$Shop_Company['company_phone']:$Shop_Company['contacts_phone'];
        $data['shop_self_support'] = $shop_base['shop_self_support'];
        $data['shop_logo'] = $shop_base['shop_logo'];
        //获取店铺IM
        if ($shop_base['shop_self_support'] == 'true' && Web_ConfigModel::value('self_shop_im')) {
            $data['self_shop_im'] = $Shop_BaseModel->getSelfShopIm($shop_base['user_name'], $shop_base['shop_self_support']);
        }

        //查找订单商品
        $Goods_CommonModel = new Goods_CommonModel();
        $Order_GoodsModel = new Order_GoodsModel();
        $Goods_EvaluationModel = new Goods_EvaluationModel();
        $order_goods = $Order_GoodsModel->getByWhere(array('order_id' => $data['order_id']));
        //是否拼团
        $pinTuan_TempModel = new PinTuan_Temp();
        $pintuan_temp = $pinTuan_TempModel->getPtInfoByOrderId($data['order_id']);
        foreach ($order_goods as $okey => $oval) {
            //判断该订单商品被评论的次数
            $goods_evaluation_row = array();
            $goods_evaluation_row['order_id'] = $data['order_id'];
            $goods_evaluation_row['goods_id'] = $oval['goods_id'];
            $goods_evaluation = $Goods_EvaluationModel->getByWhere($goods_evaluation_row);

            $order_goods[$okey]['evaluation_count'] = count($goods_evaluation);

            //获取订单商品退货状态
            $order_goods[$okey]['goods_refund_status_con'] = $this->goodsRefundState[$oval['goods_refund_status']];
            $order_goods[$okey]['goods_return_status_con'] = $this->goodsReturnState[$oval['goods_return_status']];

            //若为退款中订单，则查找退货单id
            if ($oval['goods_refund_status'] !== Order_StateModel::ORDER_REFUND_NO) {
                $Order_ReturnModel = new Order_ReturnModel();
                $order_return_id = $Order_ReturnModel->getKeyByWhere(array('order_number' => $data['order_id'],
                                                                         'return_type' => 2,
                                                                         'order_goods_id' => $oval['order_goods_id']));
                $order_goods[$okey]['order_refund_id'] = $order_return_id[0];
            }

            //查找退款id
            if ($oval['goods_return_status'] !== Order_StateModel::ORDER_GOODS_RETURN_NO) {
                //判断是否是虚拟订单
                if ($data['order_is_virtual']) {
                    $Order_ReturnModel = new Order_ReturnModel();
                    $order_goods_return_id = $Order_ReturnModel->getKeyByWhere(array('order_number' => $data['order_id'],
                                                                                   'return_type' => 3,
                                                                                   'order_goods_id' => $oval['order_goods_id']));
                    $order_goods[$okey]['order_return_id'] = $order_goods_return_id[0];
                } else {
                    $Order_ReturnModel = new Order_ReturnModel();
                    $order_goods_return_id = $Order_ReturnModel->getKeyByWhere(array('order_number' => $data['order_id'],
                                                                                   'return_type' => 1,
                                                                                   'order_goods_id' => $oval['order_goods_id']));
                    $order_goods[$okey]['order_return_id'] = $order_goods_return_id[0];
                }
            }
            //拼团不可退款
            if ($pintuan_temp) {
                $order_goods[$okey]['pintuan_temp_order'] = 1;
            } else {
                $order_goods[$okey]['pintuan_temp_order'] = 0;
            }

            $order_goods[$okey]['spec_text'] = $oval['order_spec_info'] ? implode('，', $oval['order_spec_info']) : '';
        }
        //拼团不可退款
        if ($pintuan_temp) {
            $data['pintuan_temp'] = 1;
        } else {
            $data['pintuan_temp'] = 0;
        }
        $data['goods_list'] = array_values($order_goods);

        $data['is_open_im'] = Yf_Registry::get('im_statu');

        //自提商品
        if ($data['chain_id'] > 0) {
            $chain_base_model = new Chain_BaseModel;
            $chain_info = $chain_base_model->getChainInfo($data['chain_id']);
            $Order_GoodsChainCodeModel = new Order_GoodsChainCodeModel();
            $chain_code = current($Order_GoodsChainCodeModel->getByWhere(array('order_id' => $data['id'])));
            $data['chain_info'] = $chain_info;
            $data['chain_code'] = $chain_code ?: [];
        }
        return $data;

    }

    public function getBaseExcel($cond_row = array(), $order_row = array())
    {

        $data = $this->getByWhere($cond_row, $order_row);

        foreach ($data as $k => $v) {
            $data[$k]['order_id'] = " " . $v['order_id'] . " ";
        }

        return array_values($data);
    }

    /**
     * 读取分页列表
     *
     * @param  int $config_key 主键值
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getDetailList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
    {
        return $this->listByWhere($cond_row, $order_row, $page, $rows);
    }

    /*
     * 获取结算表下面的相关订单数据
     */

    public function getOrderDetailList($cond_row = array(), $order_row = array(), $page = 1, $rows = 10)
    {
        return $this->listByWhere($cond_row, $order_row, $page, $rows);
    }

    /*
     *  zhuyt
     *
     * 结算订单表
     * 10/22  修改订单中不再计算退款金额，退款金额通过退货单进行计算
     */
    public function settleOrder($cond_row = array(), $order_row = array())
    {
        $data = $this->getByWhere($cond_row, $order_row);

        //订单金额
        $order_amount = 0;
        //运费
        $shipping_amount = 0;
        //佣金金额
        $commission_amount = 0;
        //退款金额
        $return_amount = 0;
        //退款佣金
        $commission_return_amount = 0;

        //货到付款补丁
        $paymet_id_fee = array();
        if(!empty($data)){
            foreach ($data as $r){
                $paymet_id_fee[] = array('payment_id'=>$r['payment_id'],'order_commission_fee'=>$r['order_commission_fee']);
            }
        }

        //结算订单部分的费用
        $res = array(
            'order_amount' => array_sum(array_column($data,'order_payment_amount')),
            'shipping_amount' => array_sum(array_column($data,'order_shipping_fee')),
            'commission_amount' => array_sum(array_column($data,'order_commission_fee')),
            'redpacket_amount' => array_sum(array_column($data,'order_rpt_price')),
            'order_directseller_commission' => array_sum(array_column($data,'order_directseller_commission')),
            'paymet_id_fee' => $paymet_id_fee, //货到付款补丁
        );

        //修改订单表中的结算时间与结算状态
        $id_row = array_keys($data);
        $this->editBase($id_row, array('order_settlement_time' => get_date_time() ,'order_is_settlement' =>Order_SettlementModel::SETTLEMENT_WAIT_OPERATE));

        return $res;

    }

    /*
     *  windfnn
     *
     * 获取卖家订单列表
     */
    public function getOrderList($cond_row = array(), $order_row = array(), $page = 1, $rows = 15)
    {
        //分销商分销的商品
        $GoodsCommonModel        = new Goods_CommonModel();
        $Order_GoodsModel = new Order_GoodsModel();
        $dist_commons = $GoodsCommonModel->getByWhere(array('shop_id' => Perm::$shopId,"common_parent_id:>" => 0,'product_is_behalf_delivery' => 1));

        $dist_common_ids = array();
        if(!empty($dist_commons)){
            $dist_common_ids  = array_column($dist_commons,'common_id');
        }

        //分页
        $Yf_Page = new Yf_Page();
        $rows = $Yf_Page->listRows;
        $offset = request_int('firstRow', 0);
        $page = ceil_r($offset / $rows);

        $data = $this->listByWhere($cond_row, $order_row, $page, $rows);

        $Order_StateModel = new Order_StateModel();
        $Order_InvoiceModel = new Order_InvoiceModel();
        $Order_ReturnModel = new Order_ReturnModel();

        //分页
        $Yf_Page->totalRows = $data['totalsize'];
        $page_nav = $Yf_Page->prompt();
        $data['page_nav'] = $page_nav;

        $order_id_row = array_column($data['items'], 'order_id');
        $order_goods_list = $Order_GoodsModel->getByWhere(array('order_id:IN' => $order_id_row));

        $goods_list = array();

        foreach ($order_goods_list as $item) {
            $goods_list[$item['order_id']][] = $item;
        }

        $url = Yf_Registry::get('url');
        $data['items'] = $this->dealOrderData($data['items']);

        foreach ($data['items'] as $key => $val) {

            $data['items'][$key]['order_stauts_text'] = $Order_StateModel->orderState[$val['order_status']];
            $data['items'][$key]['order_stauts_const'] = $Order_StateModel->orderState[$val['order_status']];

            //订单详情URL
            $data['items'][$key]['info_url'] = $url . '?ctl=Seller_Trade_Order&met=physicalInfo&o&typ=e&order_id=' . $val['order_id'];
            //发货单URL
            $data['items'][$key]['delivery_url'] = $url . '?ctl=Seller_Trade_Order&met=getOrderPrint&typ=e&order_id=' . $val['order_id'];
            //设置发货URL
            $data['items'][$key]['send_url'] = $url . '?ctl=Seller_Trade_Order&met=send&typ=e&order_id=' . $val['order_id'];
//            echo '<pre>';
//            print_r($val);
//            die;
            //收货人信息 名字 + 联系方式 + 地址 &nbsp
            $data['items'][$key]['receiver_info'] = $val['order_receiver_name'] . "&nbsp" . $val['order_receiver_contact'] . "&nbsp" . $val['order_receiver_address'];

            //订单发票信息
            if($val['order_invoice_id'])
            {
                $data['items'][$key]['invoice'] = $Order_InvoiceModel->getOne($val['order_invoice_id']);
                $data['items'][$key]['invoice']['invoice_statu_txt'] = $Order_InvoiceModel->invoiceState[$data['items'][$key]['invoice']['invoice_state']];
            }


            //发货人信息(发货地址)
            $Shop_ShippingAddressModel = new Shop_ShippingAddressModel();
            $address_list              = $Shop_ShippingAddressModel->getByWhere(array('shop_id' => $val['shop_id']));
            $address_list              = array_values($address_list);
            //判断商家是否设置了发货地址
            if (empty($address_list)) {
                $data['items'][$key]['shipper'] = 0;
                $data['items'][$key]['shipper_info'] = '还未设置发货地址，请进入发货设置 &gt 地址库中添加';
            } else {
                //判断是否设置了默认收货地址，如果没有的话就取第一个收货地址
                $address_default              = $Shop_ShippingAddressModel->getByWhere(array('shop_id' => $val['shop_id'],'shipping_address_default'=>1));
                $address_default              = array_values($address_default);
                if($address_default)
                {
                    $data['items'][$key]['shipper'] = 1;
                    $data['items'][$key]['shipper_info'] = $address_default[0]['shipping_address_contact'] . "&nbsp" . $address_default[0]['shipping_address_phone']. "&nbsp" .$address_default[0]['shipping_address_area'] . "&nbsp" . $address_default[0]['shipping_address_address'];
                }
                else
                {
                    $data['items'][$key]['shipper'] = 1;
                    $data['items'][$key]['shipper_info'] = $address_list[0]['shipping_address_contact']. "&nbsp" .$address_list[0]['shipping_address_phone']. "&nbsp" .$address_list[0]['shipping_address_area']. "&nbsp" .$address_list[0]['shipping_address_address'];
                }

            }

            //运费信息
            if ($val['order_shipping_fee'] == 0) {
                $data['items'][$key]['shipping_info'] = "(免运费)";
            } else {
                $shipping_fee = @format_money($val['order_shipping_fee']);
                $data['items'][$key]['shipping_info'] = "(含运费$shipping_fee)";
            }

            /*
             * 订单操作
             * 待付款状态 ==> 取消订单
             * 待发货状态 ==> 设置发货
             * */
            
            if(Perm::$shopId){
                $shopBaseModel = new Shop_BaseModel();
                $shop_base  = $shopBaseModel->getOne(Perm::$shopId);
            }


            //获取订单产品列表
            //$goods_list                        = $Order_GoodsModel->getGoodsListByOrderId($val['order_id']);

            if(isset($goods_list[$val['order_id']]))
            {
                $data['items'][$key]['goods_list'] = $goods_list[$val['order_id']];

                $goods_cat_num = 0;
                foreach ($data['items'][$key]['goods_list'] as $k => $v) {
                    $data['items'][$key]['goods_list'][$k]['spec_text'] = $v['order_spec_info'] ? implode('，', $v['order_spec_info']) : '';
                    $data['items'][$key]['goods_list'][$k]['goods_link'] = $url . '?ctl=Goods_Goods&met=snapshot&goods_id=' . $v['goods_id'] . '&order_id=' . $val['order_id'];//商品链接
                    $goods_cat_num += 1;
                    if(is_array($data['items'][$key]['goods_list'][$k]['order_spec_info']) && $data['items'][$key]['goods_list'][$k]['order_spec_info']){
                        $data['items'][$key]['goods_list'][$k]['order_spec_info'] = implode('，',$data['items'][$key]['goods_list'][$k]['order_spec_info']);
                    }

                    //判断商品是否是一件代发分销商品，如果是一件代发分销商品，分销商无法发货
                    $deilve_able = 1;
                    if(in_array($v['common_id'],$dist_common_ids))
                    {
                        $deilve_able = 0;
                    }

                    $data['items'][$key]['goods_list'][$k]['order_goods_num_c'] = $v['order_goods_num'] - $v['order_goods_returnnum'];
                    //商品小计金额（打印发货单）
                    $data['items'][$key]['goods_list'][$k]['order_goods_amount_c'] = $v['goods_price']*$data['items'][$key]['goods_list'][$k]['order_goods_num_c'];

                    //查找该订单商品是否存在退款/退货
                    $goods_return       = $Order_ReturnModel->getByWhere(array(
                                                                             'order_goods_id' => $v['order_goods_id'],
                                                                             'return_type' => Order_ReturnModel::RETURN_TYPE_ORDER,
                                                                             'return_shop_handle:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS,
                                                                         ));

                    $return_txt = '';
                    $return_price = 0;
                    if($goods_return)
                    {
                        $goods_return = current($goods_return);

                        if($goods_return['return_state'] == Order_ReturnModel::RETURN_PLAT_PASS)
                        {
                            $return_txt = "<span class='colred'>已退".$goods_return['order_goods_num']."件</span>";
                        }else{
                            $return_url = $url . '?ctl=Seller_Service_Return&met=orderReturn&act=detail&id=' . $goods_return['order_return_id'];

                            if($deilve_able)
                            {
                                $return_txt = "<a class=\"ncbtn ncbtn-mint mt10 bbc_seller_btns\" href=\"$return_url\"><i class=\"icon-truck\"></i>处理退款</a>";
                            }
                        }
                        $return_price = $goods_return['return_cash'];
                    }
                    $data['items'][$key]['goods_list'][$k]['return_txt'] = $return_txt;
                    $data['items'][$key]['goods_list'][$k]['return_price'] = $return_price;
                }
            }
            //商品种类数
            $data['items'][$key]['goods_cat_num'] = $goods_cat_num;
            $data['items'][$key]['deilve_able'] = $deilve_able;
            
            if ($val['order_status'] == Order_StateModel::ORDER_WAIT_PAY) {
                $order_id = $val['order_id'];
                $set_html = "<a href=\"javascript:void(0)\" data-order_id=$order_id dialog_id=\"seller_order_cancel_order\" class=\"ncbtn ncbtn-grapefruit mt5\"><i class=\"icon-remove-circle\"></i>取消订单</a>";

                $data['items'][$key]['set_html'] = $set_html;
            } elseif ($val['order_status'] == Order_StateModel::ORDER_WAIT_PREPARE_GOODS || $val['order_status'] == Order_StateModel::ORDER_PAYED) {
                //订单已经支付，判断已支付订单是否存在退款
                $return       = $Order_ReturnModel->getByWhere(array(
                                                                   'order_number' => $val['order_id'],
                                                                   'return_type' => Order_ReturnModel::RETURN_TYPE_ORDER,
                                                                   'return_state:!=' => Order_ReturnModel::RETURN_PLAT_PASS
                                                               ));

                if ($return) {
                    //查找退款单
                    $order_retuen_cond['order_number'] = $val['order_id'];
                    $order_retuen_cond['return_goods_return'] = Order_ReturnModel::RETURN_GOODS_ISRETURN;
                    $return_id = $Order_ReturnModel->getKeyByWhere($order_retuen_cond);
                    $return_id = $return_id['0'];

                    $data['items'][$key]['retund_url'] = $url . '?ctl=Seller_Service_Return&met=orderReturn&act=detail&id=' . $return_id;
                    $retund_url = $url . '?ctl=Seller_Service_Return&met=orderReturn&act=detail&id=' . $return_id;

                    $set_html = "<span class=\"ncbtn ncbtn-mint  colred fz14\"><i class=\"icon-truck\"></i>退款中</span>";

                } else {

                    if($data['items'][$key]['deilve_able'])
                    {
                        if(isset($data['items'][$key]['pintuan_status']) && $data['items'][$key]['pintuan_status'] != 1){
                            $set_html = '';
                        }else{
                            $send_url = $data['items'][$key]['send_url'];
                            $set_html = "<a class=\"ncbtn ncbtn-mint  bbc_seller_btns \" href=\"$send_url\"><i class=\"icon-truck\"></i>设置发货</a>";
                        }
                    }
                    else
                    {
                        $set_html = '';
                    }
                }


                $data['items'][$key]['set_html'] = $set_html;
            } else {
                $data['items'][$key]['set_html'] = null;
            }

            //货到付款+待发货=> 可以取消订单
            if ( $val['payment_id'] == PaymentChannlModel::PAY_CONFIRM
                && $val['order_status'] == Order_StateModel::ORDER_WAIT_PREPARE_GOODS
            ) {
                $order_id = $val['order_id'];
                $set_html = "<a href=\"javascript:void(0)\" data-order_id=$order_id dialog_id=\"seller_order_cancel_order\" class=\"ncbtn ncbtn-grapefruit mt5\"><i class=\"icon-remove-circle\"></i>取消订单</a>";

                $data['items'][$key]['set_html'] .= $set_html;
            }


        }

        return $data;
    }

    /*
     *  windfnn
     *
     * 获取平台商品订单列表
     * @return array $data 订单列表
     * */
    public function getPlatOrderList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
    {
        $data = $this->getBaseList($cond_row, $order_row, $page, $rows);
        $Order_StateModel = new Order_StateModel();
        $data['totalSums'] = $this->sumMoney($cond_row , "order_payment_amount");

        foreach ($data['items'] as $key => $val) {
            $data['items'][$key]['order_stauts_text'] = $Order_StateModel->orderState[$val['order_status']];
            $data['items'][$key]['order_from_text'] = $Order_StateModel->orderFrom[$val['order_from']];
            $data['items'][$key]['evaluation_status_text'] = $Order_StateModel->evaluationStatus[$val['order_buyer_evaluation_status']];
            $data['totalSum']+=$val['order_payment_amount'];
        }

        return $data;
    }

    /*
     *  ly
     *
     * 拼接筛选条件,多个方法公用
     * @param $condition 筛选条件
     * @return array $condition 筛选条件
     * */
    public function createSearchCondi(&$condition)
    {
        $query_start_date = request_string('query_start_date');
        $query_end_date = request_string('query_end_date');
        $buyer_name = request_string('buyer_name');
        $order_sn = request_string('order_sn');
        $skip_off = request_int('skip_off');            //是否显示已取消订单
        $chain_name = request_string('chain_name'); //门店名称

        if (!empty($query_start_date)) {
            $query_start_date = $query_start_date;
            $condition['order_create_time:>='] = $query_start_date;
        }

        if (!empty($query_end_date)) {
            $query_end_date = $query_end_date;
            $condition['order_create_time:<='] = $query_end_date;
        }

        if (!empty($buyer_name)) {
            $condition['buyer_user_name:LIKE'] = "%$buyer_name%";
        }

        if (!empty($order_sn)) {
            $condition['order_id:LIKE'] = "%$order_sn%";
        }

        if ($skip_off) {
            $condition['order_status:<>'] = Order_StateModel::ORDER_CANCEL;
        }

        //门店名字
        if ($chain_name) {
            $chain_model = new Chain_BaseModel;
            $chain_rows = $chain_model->getByWhere(['chain_name:LIKE'=> '%'.$chain_name.'%']);
            $chain_ids = empty($chain_rows) ? [] : array_keys($chain_rows);
            $condition['chain_id:IN'] = $chain_ids;
        }
    }

    /*
     *  ly
     *
     * 获取实物交易订单信息
     * @param $condition 筛选条件
     * @return array $data 订单信息
     * */
    public function getPhysicalInfoData($condi = array())
    {

        $data = $this->getOrderList($condi);
        $data = pos($data['items']);
        switch ($data['order_status']) {
            case Order_StateModel::ORDER_WAIT_PAY:
                $order_create_time = time($data['order_create_time']);
                $order_close_data = date('Y-m-d', strtotime('+7days', $order_create_time));
                $data['order_status_text'] = '订单已经提交，等待买家付款';
                $data['order_status_html'] = "<li>1. 买家尚未对该订单进行支付。</li><li>2. 如果买家未对该笔订单进行支付操作，系统将于<time>$order_close_data</time>自动关闭该订单。</li>";

                //页面的订单状态
                $data['order_payed'] = "";
                $data['order_wait_confirm_goods'] = "";
                $data['order_received'] = "";
                $data['order_evaluate'] = "";
                break;

            case Order_StateModel::ORDER_PAYED:
                $payment_name = $data['payment_name'];
                if (empty($payment_name)) {
                    $payment_name = 'XXX';
                }
                $data['order_status_text'] = '已经付款';
                $data['order_status_html'] = "<li>1. 买家已使用“" . $payment_name . "”方式成功对订单进行支付，支付单号 “" .$data['payment_other_number']. "”。</li><li>2. 订单已提交商家进行备货发货准备。</li>";

                //页面的订单状态
                $data['order_payed'] = "current";
                $data['order_wait_confirm_goods'] = "";
                $data['order_received'] = "";
                $data['order_evaluate'] = "";
                break;

            case Order_StateModel::ORDER_WAIT_PREPARE_GOODS:
                $payment_name = $data['payment_name'];
                if (empty($payment_name)) {
                    $payment_name = 'XXX';
                }
                $data['order_status_text'] = '等待发货';
                $data['order_status_html'] = "<li>1. 买家已使用“" . $payment_name . "”方式成功对订单进行支付。</li><li>2. 订单已提交商家进行备货发货准备。</li>";

                //页面的订单状态
                $data['order_payed'] = "current";
                $data['order_wait_confirm_goods'] = "";
                $data['order_received'] = "";
                $data['order_evaluate'] = "";
                break;

            case Order_StateModel::ORDER_WAIT_CONFIRM_GOODS:
                $data['order_status_text'] = '已经发货';
                if (empty($data['order_receiver_date'])) {
                    $order_shipping_time = strtotime($data['order_shipping_time']);
                    $order_shipping_time = strtotime('+1 month', $order_shipping_time);
                    $order_shipping_time = date('Y-m-d', $order_shipping_time);
                    $data['order_receiver_date'] = $order_shipping_time;
                } else {
                    $order_shipping_time = $data['order_receiver_date'];
                }

                if(!empty($data['order_shipping_express_id']) && !empty($data['order_shipping_code']))
                {
                    //查找物流公司
                    $expressModel = new ExpressModel();
                    $express_base = $expressModel->getExpress($data['order_shipping_express_id']);
                    $express_base = pos($express_base);
                    $express_name = $express_base['express_name'];
                    $order_shipping_code = $data['order_shipping_code'];

                    $data['order_status_html'] = "<li>1. 商品已发出；$express_name : $order_shipping_code 。</li><li>2. 如果买家没有及时进行收货，系统将于<time>$order_shipping_time</time>自动完成“确认收货”，完成交易。</li>";
                }
                else
                {
                    $data['order_status_html'] = "<li>1. 商品已发出；无需物流。</li><li>2. 如果买家没有及时进行收货，系统将于<time>$order_shipping_time</time>自动完成“确认收货”，完成交易。</li>";
                }

                //页面的订单状态
                $data['order_payed'] = "current";
                $data['order_wait_confirm_goods'] = "current";
                $data['order_received'] = "";
                $data['order_evaluate'] = "";
                break;

            case $data['order_status'] == Order_StateModel::ORDER_RECEIVED || $data['order_status'] == Order_StateModel::ORDER_FINISH:
                $data['order_status_text'] = '已经收货';
                $data['order_status_html'] = '<li>1. 交易已完成，买家可以对购买的商品及服务进行评价。</li><li>2. 评价后的情况会在商品详细页面中显示，以供其它会员在购买时参考。</li>';

                //页面的订单状态
                $data['order_payed'] = "current";
                $data['order_wait_confirm_goods'] = "current";
                $data['order_received'] = "current";
                if ($data['order_buyer_evaluation_status'] != Order_BaseModel::BUYER_EVALUATE_NO) {
                    $data['order_evaluate'] = "current";
                } else {
                    $data['order_evaluate'] = "";
                }
                break;

            case Order_StateModel::ORDER_CANCEL:
                $data['order_status_text'] = '交易关闭';
                $order_cancel_date = $data['order_cancel_date'];
                $order_cancel_reason = $data['order_cancel_reason'];

                //判断关闭者身份 1=>买家 2=>卖家 3=>系统
                if ($data['order_cancel_identity'] == Order_BaseModel::CANCEL_USER_BUYER) {
                    $identity = '买家';
                } else if ($data['order_cancel_identity'] == Order_BaseModel::CANCEL_USER_SELLER) {
                    $identity = '商家';
                } else if ($data['order_cancel_identity'] == Order_BaseModel::CANCEL_USER_SYSTEM) {
                    $identity = '系统';
                }

                $data['order_status_html'] = "<li> $identity 于 $order_cancel_date 取消了订单 ( $order_cancel_reason ) </li>";
                break;
        }

        //取出物流公司名称
        if (!empty($data['order_shipping_express_id'])) {
            $expressModel = new ExpressModel();
            $express_base = $expressModel->getExpress($data['order_shipping_express_id']);
            $express_base = pos($express_base);
            $data['express_name'] = $express_base['express_name'];
        } else {
            $data['express_name'] = '';
        }

        //店主名称
        $shopBaseModel = new Shop_BaseModel();
        $shop_base = $shopBaseModel->getBase($data['shop_id']);
        $shop_base = pos($shop_base);
        $data['shop_user_name'] = $shop_base['user_name'];
        $data['shop_tel'] = $shop_base['shop_tel'];

        return $data;
    }

    /*
  *  zcg
  *
  * 获取门店自提订单信息
  * @param $condition 筛选条件
  * @return array $data 订单信息
  * */
    public function getChainInfoData($condi = array())
    {

        $data = $this->getOrderList($condi);
        $data = pos($data['items']);

        switch ($data['order_status'])
        {
            case Order_StateModel::ORDER_WAIT_PAY :
                $order_create_time         = time($data['order_create_time']);
                $order_close_data          = date('Y-m-d', strtotime('+7days', $order_create_time));
                $data['order_status_text'] = '订单已经提交，等待买家付款';
                $data['order_status_html'] = "<li>1. 买家尚未对该订单进行支付。</li><li>2. 如果买家未对该笔订单进行支付操作，系统将于<time>$order_close_data</time>自动关闭该订单。</li>";

                //页面的订单状态
                $data['order_payed']              = "";
                $data['order_wait_confirm_goods'] = "";
                $data['order_received']           = "";
                $data['order_evaluate']           = "";
                break;

            case Order_StateModel::ORDER_SELF_PICKUP :
                $payment_name = $data['payment_name'];
                if (empty($payment_name))
                {
                    $payment_name = 'XXX';
                }
                $data['order_status_text'] = '待自提';
                $data['order_status_html'] = "<li>买家还没有到门店自提。</li>";

                //页面的订单状态
                $data['order_payed']              = "current";
                $data['order_wait_confirm_goods'] = "";
                $data['order_received']           = "";
                $data['order_evaluate']           = "";
                break;

            case Order_StateModel::ORDER_RECEIVED:
                $data['order_status_text'] = '已经自提';
                $data['order_status_html'] = '<li>1. 交易已完成，买家可以对购买的商品及服务进行评价。</li><li>2. 评价后的情况会在商品详细页面中显示，以供其它会员在购买时参考。</li>';

                //页面的订单状态
                $data['order_payed']              = "current";
                $data['order_wait_confirm_goods'] = "current";
                $data['order_received']           = "current";
                if ($data['order_buyer_evaluation_status'] != Order_BaseModel::BUYER_EVALUATE_NO)
                {
                    $data['order_evaluate'] = "current";
                }
                else
                {
                    $data['order_evaluate'] = "";
                }

                break;
            case Order_StateModel::ORDER_FINISH :
                $data['order_status_text'] = '已经自提';
                $data['order_status_html'] = '<li>1. 交易已完成，买家可以对购买的商品及服务进行评价。</li><li>2. 评价后的情况会在商品详细页面中显示，以供其它会员在购买时参考。</li>';

                //页面的订单状态
                $data['order_payed']              = "current";
                $data['order_wait_confirm_goods'] = "current";
                $data['order_received']           = "current";
                if ($data['order_buyer_evaluation_status'] != Order_BaseModel::BUYER_EVALUATE_NO)
                {
                    $data['order_evaluate'] = "current";
                }
                else
                {
                    $data['order_evaluate'] = "";
                }

                break;

            case Order_StateModel::ORDER_CANCEL:
                $data['order_status_text'] = '交易关闭';
                $order_cancel_date         = $data['order_cancel_date'];
                $order_cancel_reason       = $data['order_cancel_reason'];

                //判断关闭者身份 1=>买家 2=>卖家 3=>系统
                if ($data['order_cancel_identity'] == Order_BaseModel::CANCEL_USER_BUYER)
                {
                    $identity = '买家';
                }
                else if ($data['order_cancel_identity'] == Order_BaseModel::CANCEL_USER_SELLER)
                {
                    $identity = '商家';
                }
                else if ($data['order_cancel_identity'] == Order_BaseModel::CANCEL_USER_SYSTEM)
                {
                    $identity = '系统';
                }

                $data['order_status_html'] = "<li> $identity 于 $order_cancel_date 取消了订单 ( $order_cancel_reason ) </li>";
                break;
        }

        //店主名称
        $shopBaseModel          = new Shop_BaseModel();
        $shop_base              = $shopBaseModel->getBase($data['shop_id']);
        $shop_base              = pos($shop_base);
        $data['shop_user_name'] = $shop_base['user_name'];
        $data['shop_tel']       = $shop_base['shop_tel'];

        return $data;
    }

    /*
     *  ly
     *
     * 拼接筛选条件,多个方法公用
     * @param $condition 筛选条件
     * @return array $data 订单信息 + 筛选条件
     * */
    public function getPhysicalList(&$condi,$order_row=array())
    {
        $condi['shop_id'] = Perm::$shopId;
        if(!isset($condi['order_is_virtual']) || !$condi['order_is_virtual']){
            $condi['order_is_virtual'] = 0;
        }
        if(!isset($condi['order_shop_hidden']) || !$condi['order_shop_hidden']){
            $condi['order_shop_hidden'] = 0;
        }
        $condi['order_payment_amount-order_refund_amount:>='] = '0';
        $this->createSearchCondi($condi); //生成查询条件

        $data = $this->getOrderList($condi,$order_row);
        $data['condi'] = $condi;

        //如果是门店自提，取出门店信息
        //获取门店信息
        if(isset($condi['chain_id:!=']) && $data['totalsize'] > 0) {
            $chain_base = new Chain_BaseModel;
            $chain_ids = array_unique(array_column($data['items'], 'chain_id'));
            $chain_rows = $chain_base->getBase($chain_ids);
            $data['chain_rows'] = $chain_rows;
        }

        return $data;
    }

    /**
     * 读数量
     *
     * @param  int $config_key 主键值
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function getCount($cond_row = array())
    {
        return $this->getNum($cond_row);
    }


    //订单支付成功后修改订单状态
    public function editOrderStatusAferPay($order_id = null, $uorder_id = null)
    {
        $flag = false;
        //查找订单信息
        $order_base = $this->getOne($order_id);
        if($order_base['order_status'] == Order_StateModel::ORDER_WAIT_PAY)
        {
            $edit_row = array('order_status' => Order_StateModel::ORDER_PAYED);
            $edit_row['payment_time'] = get_date_time();
            $edit_row['payment_other_number'] = $uorder_id;
            //修改订单状态
            $this->editBase($order_id, $edit_row);

            //修改订单商品状态
            $Order_GoodsModel = new Order_GoodsModel();
            $edit_goods_row = array('order_goods_status' => Order_StateModel::ORDER_PAYED);
            $order_goods_id = $Order_GoodsModel->getKeyByWhere(array('order_id' => $order_id));
            $flag = $Order_GoodsModel->editGoods($order_goods_id, $edit_goods_row);

            //修改商品的销量
            $Goods_BaseModel = new Goods_BaseModel();
            $Goods_BaseModel->editGoodsSale($order_goods_id);

            $Goods_CommonModel = new Goods_CommonModel();

            //判断是否是虚拟订单，若是虚拟订单则生成发送信息并修改订单状态为已发货
            if ($order_base['order_is_virtual'] == Order_BaseModel::ORDER_IS_VIRTUAL) {
                //循环订单商品
                $Text_Password = new Text_Password();
                $Order_GoodsVirtualCodeModel = new Order_GoodsVirtualCodeModel();

                $msg_str = '尊敬的用户您已在' . $order_base['shop_name'] . '成功购买';
                $goods_name_str = '';
                $virtual_code_str = '';
                foreach ($order_goods_id as $k => $v) {
                    $order_goods_base = $Order_GoodsModel->getOne($v);
                    //判断该商品是否是虚拟商品
                    $goods_common_base = $Goods_CommonModel->getOne($order_goods_base['common_id']);
                    if($goods_common_base['common_is_virtual'])
                    {
                        $num = $order_goods_base['order_goods_num'];
                        //获取购买的数量，循环生成虚拟兑换码，将信息插入虚拟兑换码表中

                        for ($i = 0; $i < $num; $i++) {
                            $virtual_code = $Text_Password->create(8, 'unpronounceable', 'numeric');
                            $virtual_code_str .= $virtual_code . ',';
                            $add_row = array();
                            $add_row['virtual_code_id'] = $virtual_code;
                            $add_row['order_id'] = $order_id;
                            $add_row['order_goods_id'] = $v;
                            $add_row['virtual_code_status'] = Order_GoodsVirtualCodeModel::VIRTUAL_CODE_NEW;

                            $Order_GoodsVirtualCodeModel->addCode($add_row);
                        }
                        $goods_name_str .= $order_goods_base['goods_name'] . '，';


                        //修改订单商品为已发货待收货4
                        $edit_order_goods_row['order_goods_status'] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
                        $Order_GoodsModel->editGoods($v, $edit_order_goods_row);
                    }
                }

                $msg_str = $msg_str . $goods_name_str . '您可凭兑换码' . $virtual_code_str . '在本店进行消费。';
                Sms::send($order_base['order_receiver_contact'], $msg_str);
                //$str = Sms::send(13918675918,"尊敬的用户您已在【变量】成功购买【变量】，您可凭兑换码【变量】在本店进行消费。");

                $goods_common_id = array_column($Order_GoodsModel->get($order_goods_id),'common_id');
                $Goods_Common = new Goods_CommonModel();
                $goods_common = current($Goods_Common->get($goods_common_id));
                //修改订单状态为已发货等待收货4
                $edit_order_row['order_status'] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
                $edit_order_row['order_shipping_time'] = get_date_time();
                $edit_order_row['order_receiver_date'] = $goods_common['common_virtual_date'];
                $this->editBase($order_id, $edit_order_row);
            }

            //判断是否是门店自提订单，若是门店自提订单则生成发送信息并修改订单状态为待自提
            if ($order_base['chain_id']) {
                $code     = VerifyCode::getCode($order_base['order_receiver_contact']);

                $Chain_BaseModel=new Chain_BaseModel();
                $chain_base = current($Chain_BaseModel->getByWhere(array('chain_id'=>$order_base['chain_id'])));

                $order_goods_base = $Order_GoodsModel->getOne($order_goods_id[0]);

                $Order_GoodsChainCodeModel = new Order_GoodsChainCodeModel();
                $code_data['order_id']=$order_id;
                $code_data['chain_id']=$order_base['chain_id'];
                $code_data['order_goods_id']=$order_goods_id[0];
                $code_data['chain_code_id']=$code;
                $Order_GoodsChainCodeModel->addGoodsChainCode($code_data);

                //修改订单状态为待自提11
                $edit_order_goods_row['order_goods_status'] = Order_StateModel::ORDER_SELF_PICKUP;
                $Order_GoodsModel->editGoods($order_goods_id, $edit_order_goods_row);

                $edit_order_row['order_status'] = Order_StateModel::ORDER_SELF_PICKUP;
                $this->editBase($order_id, $edit_order_row);

                $message = new MessageModel();
                $message->sendMessage('Self pick up code', Perm::$userId, Perm::$row['user_account'], $order_id = NULL, $shop_name = $order_base['shop_name'], 1, MessageModel::ORDER_MESSAGE,  NUll,NULL,NULL,NULL, Null,$goods_name=$order_goods_base['goods_name'], NULL,NULL,$ztm=$code,$chain_name=$chain_base['chain_name'],$order_phone=$order_base['order_receiver_contact']);
                //$str = Sms::send(13918675918,"尊敬的用户您已在[shop_name]成功购买[goods_name]，您可凭自提码[ztm]在[chain_name]自提。");
            }


            //判断此订单是否使用了代金券，如果使用，则改变代金券的使用状态
            /*if ($order_base['voucher_id']) {
                $Voucher_BaseModel = new Voucher_BaseModel();
                $Voucher_BaseModel->changeVoucherState($order_base['voucher_id'], $order_base['order_id']);

                //代金券使用提醒
                $message = new MessageModel();
                $message->sendMessage('The use of vouchers to remind', Perm::$userId, Perm::$row['user_account'], $order_id = NULL, $shop_name = NULL, 0, MessageModel::USER_MESSAGE);
            }*/
        }
        return $flag;
    }

    //取消订单
    public function cancelOrder($order_id)
    {
        $condition['order_status'] = Order_StateModel::ORDER_CANCEL;
        $condition['order_cancel_reason'] = '支付超时自动取消';
        $condition['order_cancel_identity'] = Order_BaseModel::IS_ADMIN_CANCEL;
        $condition['order_cancel_date'] = get_date_time();

        $this->editBase($order_id, $condition);
        $order_base=current($this->getByWhere(array('order_id'=>$order_id)));

        //修改订单商品表中的订单状态
        $Order_GoodsModel = new Order_GoodsModel();
        $edit_row['order_goods_status'] = Order_StateModel::ORDER_CANCEL;
        $order_goods_id = $Order_GoodsModel->getKeyByWhere(array('order_id' => $order_id));

        $Order_GoodsModel->editGoods($order_goods_id, $edit_row);

        //退还订单商品的库存
        if($order_base['chain_id']!=0){
            $Chain_GoodsModel = new Chain_GoodsModel();
            $chain_row['chain_id:='] = $order_base['chain_id'];
            $chain_row['goods_id:='] = is_array($order_goods_id)?$order_goods_id[0]:$order_goods_id;
            $chain_row['shop_id:='] = $order_base['shop_id'];
            $chain_goods = current($Chain_GoodsModel->getByWhere($chain_row));
            $chain_goods_id = $chain_goods['chain_goods_id'];
            $goods_stock['goods_stock'] = $chain_goods['goods_stock'] + 1;
            $Chain_GoodsModel->editGoods($chain_goods_id, $goods_stock);
        }else{
            $Goods_BaseModel = new Goods_BaseModel();
            $Goods_BaseModel->returnGoodsStock($order_goods_id);
        }

        //远程关闭paycenter中的订单状态
        $key = Yf_Registry::get('paycenter_api_key');
        $url = Yf_Registry::get('paycenter_api_url');
        $paycenter_app_id = Yf_Registry::get('paycenter_app_id');
        $formvars = array();

        $formvars['order_id'] = $order_id;
        $formvars['app_id'] = $paycenter_app_id;

        fb($formvars);

        $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=cancelOrder&typ=json', $url), $formvars);
        fb($rs);
    }

    //确认收货
    public function confirmOrder($order_id)
    {
        $order_base = $this->getOne($order_id);
        $order_payment_amount = $order_base['order_payment_amount'];

        $condition['order_status'] = Order_StateModel::ORDER_FINISH;

        $condition['order_finished_time'] = get_date_time();

        $flag = $this->editBase($order_id, $condition);

        //修改订单商品表中的订单状态
        $edit_row['order_goods_status'] = Order_StateModel::ORDER_FINISH;

        $Order_GoodsModel = new Order_GoodsModel();

        $order_goods_id = $Order_GoodsModel->getKeyByWhere(array('order_id' => $order_id));

        $Order_GoodsModel->editGoods($order_goods_id, $edit_row);


        //远程修改paycenter中的订单状态
        $key      = Yf_Registry::get('shop_api_key');
        $url         = Yf_Registry::get('paycenter_api_url');
        $shop_app_id = Yf_Registry::get('shop_app_id');
        $formvars = array();

        $formvars['order_id']    = $order_id;
        $formvars['app_id']        = $shop_app_id;

        fb($formvars);

        $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=confirmOrder&typ=json', $url), $formvars);


        /*
        *  经验与成长值
        */
        $user_points = Web_ConfigModel::value("points_recharge");//订单每多少获取多少积分
        $user_points_amount = Web_ConfigModel::value("points_order");//订单每多少获取多少积分

        if ($order_payment_amount / $user_points < $user_points_amount) {
            $user_points = floor($order_payment_amount / $user_points);
        } else {
            $user_points = $user_points_amount;
        }

        $user_grade = Web_ConfigModel::value("grade_recharge");//订单每多少获取多少积分
        $user_grade_amount = Web_ConfigModel::value("grade_order");//订单每多少获取多少成长值

        if ($order_payment_amount / $user_grade < $user_grade_amount)
        {
            $user_grade = floor($order_payment_amount / $user_grade);
        }
        else
        {
            $user_grade = $user_grade_amount;
        }

        $User_ResourceModel = new User_ResourceModel();
        //获取积分经验值
        $ce = $User_ResourceModel->getResource($order_base['buyer_user_id']);

        $resource_row['user_points'] = $ce[$order_base['buyer_user_id']]['user_points'] * 1 + $user_points * 1;
        $resource_row['user_growth'] = $ce[$order_base['buyer_user_id']]['user_growth'] * 1 + $user_grade * 1;

        $res_flag = $User_ResourceModel->editResource($order_base['buyer_user_id'], $resource_row);

        $User_GradeModel = new User_GradeModel;
        //升级判断
        $res_flag = $User_GradeModel->upGrade($order_base['buyer_user_id'], $resource_row['user_growth']);
        //积分
        $points_row['user_id'] = $order_base['buyer_user_id'];
        $points_row['user_name'] = $order_base['buyer_user_name'];
        $points_row['class_id'] = Points_LogModel::ONBUY;
        $points_row['points_log_points'] = $user_points;
        $points_row['points_log_time'] = get_date_time();
        $points_row['points_log_desc'] = '确认收货';
        $points_row['points_log_flag'] = 'confirmorder';

        $Points_LogModel = new Points_LogModel();

        $Points_LogModel->addLog($points_row);

        //成长值
        $grade_row['user_id'] = $order_base['buyer_user_id'];
        $grade_row['user_name'] = $order_base['buyer_user_name'];
        $grade_row['class_id'] = Grade_LogModel::ONBUY;
        $grade_row['grade_log_grade'] = $user_grade;
        $grade_row['grade_log_time'] = get_date_time();
        $grade_row['grade_log_desc'] = '确认收货';
        $grade_row['grade_log_flag'] = 'confirmorder';

        $Grade_LogModel = new Grade_LogModel;
        $Grade_LogModel->addLog($grade_row);
    }
    
    //获取推广订单数目
    function getPromotionOrderNum($cond_row)
    {
        return $this->getNum($cond_row);
    }

    //虚拟订单过期退货
    public function virtualReturn($order_id)
    {
        $Order_GoodsModel = new Order_GoodsModel();
        $Order_GoodsModel->sql->setWhere('order_id', $order_id);
        $goods_common_id = array_column($Order_GoodsModel->get('*'),'common_id');
        $Goods_Common = new Goods_CommonModel();
        $goods_common = current($Goods_Common->get($goods_common_id));
        if($goods_common['common_virtual_refund']){
            $Order_StateModel = new Order_StateModel();
            $flag2            = true;
            $Number_SeqModel  = new Number_SeqModel();
            $prefix           = sprintf('%s-%s-', Yf_Registry::get('shop_app_id'), date('YmdHis'));
            $return_number    = $Number_SeqModel->createSeq($prefix);
            $return_id        = sprintf('%s-%s-%s-%s', 'TD', Perm::$userId, 0, $return_number);

            $field['return_message']       = __('虚拟商品过期自动退款');
            $field['return_code']          = $return_id;
            $field['return_reason_id']     = 0;
            $field['return_reason']        = "";
            $field['order_number']         = $order_id;
            $this->orderBaseModel         = new Order_BaseModel();
            $order                         = $this->orderBaseModel->getOne($order_id);
            $field['return_type']          = Order_ReturnModel::RETURN_TYPE_VIRTUAL;
            $field['return_goods_return']  = 0;
            $field['return_cash']          = $order['order_payment_amount'];
            $field['order_amount']         = $order['order_payment_amount'];
            $field['seller_user_id']       = $order['shop_id'];
            $field['seller_user_account']  = $order['shop_name'];
            $field['buyer_user_id']        = $order['buyer_user_id'];
            $field['buyer_user_account']   = $order['buyer_user_name'];
            $field['return_add_time']      = get_date_time();
            $field['return_commision_fee'] = $order['order_commission_fee'];
            $field['return_state']         = Order_ReturnModel::RETURN_PLAT_PASS;
            $field['return_finish_time']   = get_date_time();

            $rs_row = array();
            $this->orderReturnModel = new Order_ReturnModel();
            $this->orderReturnModel->sql->startTransactionDb();

            $add_flag = $this->orderReturnModel->addReturn($field, true);
            check_rs($add_flag, $rs_row);

            $order_field['order_refund_status'] = Order_BaseModel::REFUND_IN;
            $order_field['order_refund_status'] = Order_BaseModel::REFUND_COM;
            $edit_flag                          = $this->orderBaseModel->editBase($order_id, $order_field);
            check_rs($edit_flag, $rs_row);

            $sum_data['order_refund_amount']         = $order['order_payment_amount'];
            $sum_data['order_commission_return_fee'] = $order['order_commission_fee'];
            $edit_flag                               = $this->orderBaseModel->editBase($order_id, $sum_data, true);
            check_rs($edit_flag, $rs_row);

            $key      = Yf_Registry::get('shop_api_key');
            $url         = Yf_Registry::get('paycenter_api_url');
            $shop_app_id = Yf_Registry::get('shop_app_id');

            $formvars             = array();
            $formvars['app_id']        = $shop_app_id;
            $formvars['user_id']  = $order['buyer_user_id'];
            $formvars['user_account'] = $order['buyer_user_name'];
            $formvars['seller_id'] = $order['seller_user_id'];
            $formvars['seller_account'] = $order['seller_user_name'];
            $formvars['amount']   = $order['order_payment_amount'];
            $formvars['order_id'] = $order_id;
            //$formvars['goods_id'] = $return['order_goods_id'];
            $formvars['uorder_id'] = $order['payment_number'];


            $rs                   = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=refundTransfer&typ=json', $url), $formvars);

            if ($rs['status'] == 200)
            {
                check_rs(true, $rs_row);
            }
            else
            {
                check_rs(false, $rs_row);
            }

            $flag = is_ok($rs_row);
            if ($flag && $this->orderReturnModel->sql->commitDb())
            {
                return true;
            }
            else
            {
                $this->orderReturnModel->sql->rollBackDb();
                return false;
            }
        }

    }


    public function getSubQuantity($cond_row)
    {
        return $this->getNum($cond_row);
    }

    /**
     * 按照订单编号或者订单商品名称搜索
     * @param $search_str string
     * @param $condition array 其他搜索条件
     * @return array $order_ids
     */
    public function searchNumOrGoodsName($search_str, $condition = [])
    {
        $orderGoodsModel = new Order_GoodsModel();
        //$order_rows = $this->getByWhere($condition);
        //$order_ids = array_keys($order_rows);
        $search_str = '%'.$search_str.'%';

        $order_row ['order_id:LIKE'] = $search_str;
        //订单号搜索
        $num_order_rows = $this->getByWhere($order_row);
        $num_order_ids = array_keys($num_order_rows);
        unset($order_row['order_id:LIKE']);
        $order_row['goods_name:LIKE'] = $search_str;

        //订单商品名称搜索
        $goods_rows = $orderGoodsModel->getByWhere($order_row);
        $g_order_ids = array_column($goods_rows, 'order_id');

        $order_ids = array_unique(array_merge($num_order_ids, $g_order_ids));

        return $order_ids;
    }
    
    /**
     * 处理订单信息，添加或修改必须字段
     * @param type $data
     * @return type
     */
    private function dealOrderData($data){
        if(!is_array($data) || !$data){
            return array();
        }
        $order_id = array();
        foreach ($data as $value){
            $order_id[] = $value['order_id'];
        }
        $pt_temp_model  = new PinTuan_Temp();
        $pt_temp = $pt_temp_model->getByWhere(array('order_id:IN'=>$order_id));
        $order_pt = array();
        foreach ($pt_temp as $temp)
        {
            $order_pt[$temp['order_id']] = array('mark_id'=>$temp['mark_id'],'type'=>$temp['type']);
        }

        foreach ($data as $k => $val){
            if(isset($order_pt[$val['order_id']])){
                if($order_pt[$val['order_id']]['type'] == 0){
                    //拼团
                    $data[$k]['is_pintuan'] = 1;
                    $mark_id = $order_pt[$val['order_id']]['mark_id'];
                    $mark_model = new PinTuan_Mark();
                    $mark_info = $mark_model->getOne($mark_id);
                    $data[$k]['pintuan_status'] = $mark_info['status'];
                }else{
                    //单独购买
                    $data[$k]['is_pintuan'] = 2;
                    $data[$k]['pintuan_status'] = 1;
                }
                
                $data[$k]['order_shop_benefit'] = 0;

            }else{
                $data[$k]['is_pintuan'] = 0;
            }
        }
        return $data;
    }

    public function autoRefund($order_id)
    {
        //查找订单信息
        $order_base = $this->getOne($order_id);
        //判断订单状态是否是已付款订单。非已支付订单（订单状态为2）报错
        if($order_base['order_status'] != Order_StateModel::ORDER_PAYED)
        {
            $msg="该订单非已支付订单";
            return false;
        }
        //查询订单是否已经退款
        $order_return_model = new Order_ReturnModel();
        $order_return = $order_return_model->getByWhere(array('order_number'=>$order_id));
        if($order_return)
        {
            return false;
        }

        //查找订单商品信息
        $Order_GoodsModel = new Order_GoodsModel();
        $order_goods_row = $Order_GoodsModel->getByWhere(array('order_id' => $order_id));
        //循环订单将订单中的所有商品都进行退款申请
        foreach($order_goods_row as $key => $order_good)
        {
            $rs_row = array();
            //开启事物
            $this->sql->startTransactionDb();

            /* 1.增加退款单信息 */
            $Number_SeqModel  = new Number_SeqModel();
            $prefix           = sprintf('%s-%s-', Yf_Registry::get('shop_app_id'), date('YmdHis'));
            $return_number    = $Number_SeqModel->createSeq($prefix);
            $return_id        = sprintf('%s-%s-%s-%s', 'TD', $order_base['buyer_user_id'], $order_base['seller_user_id'], $return_number);

            $Order_ReturnReasonModel = new Order_ReturnReasonModel();
            $Order_ReturnModel = new Order_ReturnModel();
            //定义退款单信息
            $field = array();
            $field['return_message']   = "拼团失败退款";    //“退款/退货”说明
            $field['return_reason_id']   = 6;  //“退款/退货”原因
            $field['return_code']      = $return_id;                             //退货单号
            $field['order_goods_num']   = $order_good['order_goods_num'];                          //“退款/退货”数量
            $reason                    = $Order_ReturnReasonModel->getOne(3);
            $field['return_reason']    = $reason['order_return_reason_content'];   //“退款/退货”原因

            $field['order_number']      = $order_good['order_id'];            //订单号
            $field['order_goods_id']    = $order_good['order_goods_id'];                      //订单商品id
            $field['order_goods_name']  = $order_good['goods_name'];         //退货商品名称
            $field['order_goods_price'] = $order_good['goods_price'];        //商品单价
            $field['order_goods_pic']   = $order_good['goods_image'];        //商品图片

            $field['order_amount']        = $order_base['order_payment_amount'];     //订单实际支付金额
            $field['seller_user_id']      = $order_base['shop_id'];               //店铺id
            $field['seller_user_account'] = $order_base['shop_name'];            //店铺名称
            $field['buyer_user_id']       = $order_base['buyer_user_id'];        //买家id
            $field['buyer_user_account']  = $order_base['buyer_user_name'];     //买家名称
            $field['return_add_time']     = get_date_time();                 //退款、退货申请提交时间
            $field['order_is_virtual']    = $order_base['order_is_virtual'];     //该笔订单是否为虚拟订单

            $field['return_type'] = Order_ReturnModel::RETURN_TYPE_ORDER ; //退款
            $field['return_goods_return'] = 0;      //是否需要退货  0-不需要  1-需要
            $field['return_commision_fee'] = $order_good['order_goods_commission'];
            $field['return_rpt_cash'] = $order_base['order_rpt_price'] - $order_base['order_rpt_return'];
            $field['return_cash'] = $order_base['order_payment_amount'];

            $field['return_platform_message'] = '拼团失败自动退款';
            $field['return_state']            = Order_ReturnModel::RETURN_PLAT_PASS;
            $field['return_finish_time']      = get_date_time();
            
            $add_flag = $Order_ReturnModel->addReturn($field, true);
            check_rs($add_flag,$rs_row);

            //订单商品表中插入订单商品的“退款/退货”状态
            //退款
            $goods_field['goods_return_status'] = Order_GoodsModel::REFUND_COM;
            $goods_field['order_goods_returnnum'] = $order_good['order_goods_num'];
            $goods_data['order_goods_status'] = Order_StateModel::ORDER_FINISH; //将订单商品状态改正完成
            $edit_flag                          = $Order_GoodsModel->editGoods($order_good['order_goods_id'], $goods_field);

            check_rs($edit_flag,$rs_row);

            //订单状态修改为完成
            $order_edit_row = array();
            $order_edit_row['order_status'] = Order_StateModel::ORDER_FINISH;
            $order_edit_row['order_finished_time'] = date('Y-m-d H:i:s');
            $order_edit_row['order_refund_status'] = 2; //退款完成
            $edit_flag2  = $this->editBase($field['order_number'], $order_edit_row);

            check_rs($edit_flag2,$rs_row);

            $shopBase = new Shop_BaseModel();
            $shop_detail = $shopBase->getOne($field['seller_user_id']);
            $order_return_data = $Order_ReturnModel->getOneByWhere(['order_number'=>$order_id]);
            $message = new MessageModel();
            //退款提醒
            $message->sendMessage('Refund reminder',$shop_detail['user_id'], $shop_detail['user_name'], $order_return_data['return_code'], $shop_name = NULL, 1, 1);

            /* 2.扣除商家退款金额*/
            //退款金额，退货数量，交易佣金退款更新到订单表中
            $order_edit = array();
            $order_edit['order_refund_amount'] = $order_base['order_payment_amount'];
            $order_edit['order_return_num'] = $order_good['order_goods_num'];
            $order_edit['order_commission_return_fee'] = $order_good['order_goods_commission'];
            $order_edit['order_rpt_return'] = $field['return_rpt_cash'];

            $edit_flag  = $this->editBase($field['order_number'], $order_edit,true);

            check_rs($edit_flag,$rs_row);

            //由于该订单是未完成订单所以商家不需要扣除退款金额，但是要添加扣款流水
            //如果全部退款，增加卖家的流水
            $formvars = array();
            $formvars['app_id']        = Yf_Registry::get('shop_app_id');
            $formvars['user_id']    = $order_base['buyer_user_id']; //收款人
            $formvars['user_account'] = $order_base['buyer_user_account'];
            $formvars['seller_id'] = $order_base['seller_user_id']; //付款人
            $formvars['seller_account'] = $order_base['seller_user_name'];
            $formvars['amount'] = $field['return_cash'];
            $formvars['return_commision_fee'] = $field['return_commision_fee'];
            $formvars['order_id'] = $field['order_number'];
            $formvars['goods_id'] = $field['order_goods_id'];
            $formvars['uorder_id'] = $order_base['payment_other_number'];
            $formvars['payment_id'] = $order_base['payment_id'];
            $formvars['reason'] = "拼团退款（订单编号：$order_base[order_id]）";
            $key      = Yf_Registry::get('shop_api_key');
            $url         = Yf_Registry::get('paycenter_api_url');
            $return_rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=refundShopTransfer&typ=json', $url), $formvars);

            if ($return_rs['status'] != 200)
            {
                check_rs(false, $rs_row);
            }

            //退款退货提醒
            $message = new MessageModel();
            $message->sendMessage('Refund return reminder', $order_base['buyer_user_id'], $order_base['buyer_user_name'], $order_id = NULL, $shop_name = NULL, 0, MessageModel::ORDER_MESSAGE);

            /* 3.卖价增加退款金额*/
            //判断该笔订单是否是主账号支付，如果是主账号支付，则将退款金额退还主账号
            if($order_base['order_sub_pay'] == Order_StateModel::SUB_SELF_PAY)
            {
                $return_user_id = $field['buyer_user_id'];
                $return_user_name = $field['buyer_user_account'];
            }
            if($order_base['order_sub_pay'] == Order_StateModel::SUB_USER_PAY)
            {
                //查找主管账户用户名
                $User_BaseModel = new  User_BaseModel();
                $sub_user_base = $User_BaseModel->getOne($order_base['order_sub_user']);

                $return_user_id = $order_base['order_sub_user'];
                $return_user_name = $sub_user_base['user_account'];
            }

            $key      = Yf_Registry::get('shop_api_key');
            $url         = Yf_Registry::get('paycenter_api_url');
            $shop_app_id = Yf_Registry::get('shop_app_id');

            $formvars             = array();
            $formvars['app_id']        = $shop_app_id;
            $formvars['user_id']  = $return_user_id;
            $formvars['user_account'] = $return_user_name;
            $formvars['seller_id'] = $field['seller_user_id'];
            $formvars['seller_account'] = $field['seller_user_account'];
            $formvars['amount']   = $field['return_cash'];
            $formvars['return_commision_fee']   = $field['return_commision_fee'];
            $formvars['order_id'] = $field['order_number'];
            $formvars['goods_id'] = $field['order_goods_id'];
            $formvars['payment_id'] = $order_base['payment_id'];
            $formvars['reason'] = "拼团退款（订单编号：$order_base[order_id]）";

            //SP分销单没有payment_other_number这个字段值会报错，所以在此做判断
            if($order_base['payment_other_number'])
            {
                $formvars['uorder_id'] = $order_base['payment_other_number'];
            }
            else
            {
                $formvars['uorder_id'] = $order_base['payment_number'];
            }

            //平台同意退款（只增加买家的流水）
            $rs                   = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=refundBuyerTransfer&typ=json', $url), $formvars);

            if ($rs['status'] == 200)
            {
                check_rs(true, $rs_row);
            }
            else
            {
                check_rs(false, $rs_row);
            }

            //将需要确认的订单号远程发送给Paycenter修改订单状态
            //远程修改paycenter中的订单状态(修改为完成)
            $key      = Yf_Registry::get('shop_api_key');
            $url         = Yf_Registry::get('paycenter_api_url');
            $shop_app_id = Yf_Registry::get('shop_app_id');
            $formvars = array();
            $formvars['order_id']    = $order_base['order_id'];
            $formvars['app_id']        = $shop_app_id;
            $formvars['from_app_id'] = Yf_Registry::get('shop_app_id');
            $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=confirmOrder&typ=json', $url), $formvars);
            if($rs['status'] == 250)
            {
                $rs_flag = false;
                check_rs($rs_flag,$rs_row);
            }

            $flag = is_ok($rs_row);
            if ($flag && $this->sql->commitDb())
            {
                /**
                 *  加入统计中心
                 */
                //如果$return['order_goods_id']为0则为退款
                $order_goods_data = $Order_GoodsModel->getGoodsListByOrderId($field['order_number']);
                if(count($order_goods_data['items']) == 1)
                {
                    $order_return_goods_id = $order_goods_data['items'][0]['goods_id'];
                }
                else
                {
                    $order_return_goods_id = 0;
                }
                $order_goods_num = $order_goods_data['items'][0]['order_goods_num'];


                $analytics_data = array(
                    'order_id'=>array($field['order_number']),
                    'return_cash'=>$field['return_cash'],
                    'order_goods_num'=>$order_goods_num,
                    'order_goods_id'=>$order_return_goods_id,
                    'status'=>9 //暂时将退款退货统一处理
                );
                Yf_Plugin_Manager::getInstance()->trigger('analyticsUpdateOrderStatus',$analytics_data);
            }
            else
            {
                $this->sql->rollBackDb();
            }
        }

    }
    /**
     * 获取（地区，时间段）下单会员数
     *
     * 1,在线支付订单已付款，已完成
     * 2，货到付款，已完成
     * 3，时间区间内
     * @author xzg
     * @param $cond_row
     * @return array
     */
    public function getOrderUsers($cond_row)
    {
        $Order_BaseModel = new Order_BaseModel();
        if ($cond_row['district_id']) {
            $where = "where district_id='" . $cond_row['district_id'] . "'
                and shop_id ='" . $cond_row['shop_id'] . "' ";
        } else {
            $where = "where ((payment_id ='" . self::PAY_LINE . "'";
            $where .= "and order_status in ('" . Order_StateModel::ORDER_FINISH . "','" . Order_StateModel::ORDER_PAYED . "'))";
            $where .= "or (payment_id ='" . self::PAY_DELIVERY . "'";
            $where .= "and order_status ='" . Order_StateModel::ORDER_FINISH . "'))";
            $where .= "and shop_id ='" . $cond_row['shop_id'] . "'";
        }

        $sql = "SELECT COUNT(*) nums from `yf_order_base`
                {$where}
                and order_create_time >='" . $cond_row['start_time'] . "' 
                and order_create_time <='" . $cond_row['end_time'] . "'
                GROUP BY buyer_user_id";

        $order_base_list = $Order_BaseModel->sql->getAll($sql);

        if ($order_base_list) {
            return $order_base_list;
        } else {
            return array();
        }
    }
    /**
     * 获取（地区，时间段）下单金额
     *
     * 1,在线支付订单已付款，已完成
     * 2，货到付款，已完成
     * 3，时间区间内
     * @author xzg
     * @param $cond_row
     * @return array
     */
    public function getOrderPrices($cond_row)
    {
        if ($cond_row['district_id']){
            $where = "where district_id='" . $cond_row['district_id'] . "'
                and shop_id ='" . $cond_row['shop_id'] . "' ";
        }else {
            $where = "where ((payment_id ='" . self::PAY_LINE . "'";
            $where .= "and order_status in ('" . Order_StateModel::ORDER_FINISH . "','" . Order_StateModel::ORDER_PAYED . "'))";
            $where .= "or (payment_id ='" . self::PAY_DELIVERY . "'";
            $where .= "and order_status ='" . Order_StateModel::ORDER_FINISH . "'))";

            $where .= "and shop_id ='" . $cond_row['shop_id'] . "'";
            if ($cond_row['order_date']){
                $where .= "and order_date ='" . $cond_row['order_date'] . "'";
            }else {
                $where .= "and order_date >='" . $cond_row['start_time'] . "' ";
                $where .= "and order_date <='" . $cond_row['end_time'] . "' ";
            }
        }
        $sql = "SELECT SUM(order_goods_amount) sums from `yf_order_base` {$where}";

        $sums = $this->sql->getAll($sql);

        return $sums[0]['sums'];
    }
    /**
     * 获取（地区，时间段）下单量
     *
     * 1,在线支付订单已付款，已完成
     * 2，货到付款，已完成
     * 3，时间区间内
     * @author xzg
     * @param $cond_row
     * @return array
     */
    public function getOrderNums($cond_row)
    {
        if ($cond_row['district_id']){
            $where = "where district_id='" . $cond_row['district_id'] . "'
                and shop_id ='" . $cond_row['shop_id'] . "' ";
        } else {
            $where = "where ((payment_id ='" . self::PAY_LINE . "'";
            $where .= "and order_status in ('" . Order_StateModel::ORDER_FINISH . "','" . Order_StateModel::ORDER_PAYED . "'))";
            $where .= "or (payment_id ='" . self::PAY_DELIVERY . "'";
            $where .= "and order_status ='" . Order_StateModel::ORDER_FINISH . "'))";

            $where .= "and shop_id ='" . $cond_row['shop_id'] . "'";
            if ($cond_row['order_date']){
                $where .= "and order_date ='" . $cond_row['order_date'] . "'";
            } else {
                $where .= "and order_date >='" . $cond_row['start_time'] . "' ";
                $where .= "and order_date <='" . $cond_row['end_time'] . "' ";
            }
        }
        $sql = "SELECT COUNT(*) nums from `yf_order_base` {$where}";
        $nums = $this->sql->getAll($sql);

        return $nums[0]['nums'];
    }
    /**
     * 获取某时间段下单商品(名称，数量)
     *
     * 1,在线支付订单已付款，已完成
     * 2，货到付款，已完成
     * 3，时间区间内
     * @author xzg
     * @param $cond_row
     * @return array
     */
    public function getOrderGoodsByDate($cond_row)
    {
        if ($cond_row['district_id']){
            $where = "where district_id='" . $cond_row['district_id'] . "'
                and shop_id ='" . $cond_row['shop_id'] . "' ";
        } else {
            $where = "where ((payment_id ='" . self::PAY_LINE . "'";
            $where .= "and order_status in ('" . Order_StateModel::ORDER_FINISH . "','" . Order_StateModel::ORDER_PAYED . "'))";
            $where .= "or (payment_id ='" . self::PAY_DELIVERY . "'";
            $where .= "and order_status ='" . Order_StateModel::ORDER_FINISH . "'))";

            $where .= "and shop_id ='" . $cond_row['shop_id'] . "'";
            if ($cond_row['order_date']){
                $where .= "and order_date ='" . $cond_row['order_date'] . "'";
            } else {
                $where .= "and order_date >='" . $cond_row['start_time'] . "' ";
                $where .= "and order_date <='" . $cond_row['end_time'] . "' ";
            }
        }
        $sql = "SELECT order_id from `yf_order_base` {$where}";
        //获取有效订单的订单id集合
        $order_ids = $this->sql->getAll($sql);
        //去除key值
        $order_ids = array_column($order_ids, 'order_id');
        //拼接成字符串
        $order_ids = implode("','", $order_ids);
        //查询有效订单出售最多的商品
        $sql1 = "SELECT goods_name,COUNT(*) order_num from `yf_order_goods`
                WHERE order_id in ('".$order_ids."')
                GROUP BY common_id ORDER BY order_num DESC";

        $order_goods_model = new Order_GoodsModel();
        $order_goods = $order_goods_model->sql->getAll($sql1);

        return $order_goods;
    }

    //订单导出
    public function getOrderInfoExcel($cond_row)
    {
        $sql = "SELECT order_id,order_from,order_create_time,order_payment_amount,order_status,payment_number,payment_name,payment_time,order_shipping_code,order_refund_amount,order_finished_time,order_buyer_evaluation_status,shop_id,shop_name,buyer_user_id,buyer_user_name FROM ".$this->_tableName." WHERE 1=1";
        if($cond_row['order_id']) {
            $sql .= " AND order_id LIKE '%".$cond_row['order_id']."%'";
        }
        if($cond_row['buyer_name']) {
            $sql .= " AND buyer_user_name LIKE '%".$cond_row['buyer_name']."%'";
        }
        if($cond_row['shop_name']) {
            $sql .= " AND shop_name LIKE '%".$cond_row['shop_name']."%'";
        }
        if($cond_row['action'] == 'virtual')
        {
            $sql .= " AND order_is_virtual = ".Order_BaseModel::ORDER_IS_VIRTUAL;
        }
        if($cond_row['payment_number']) {
            $sql .= " AND payment_number LIKE '%".$cond_row['payment_number']."%'";
        }
        if($cond_row['payment_date_f'] && !$cond_row['payment_date_t']) {
            $sql .= " AND payment_time > ".$cond_row['payment_date_f'];
        }else if(!$cond_row['payment_date_f'] && $cond_row['payment_date_t']) {
            $sql .= " AND payment_time < ".$cond_row['payment_date_t'];
        }else if($cond_row['payment_date_f'] && $cond_row['payment_date_t']) {
            $sql .= " AND payment_time BETWEEN '".$cond_row['payment_date_f']."' AND '".$cond_row['payment_date_t']."'";
        }
        $sql .= " ORDER BY order_create_time DESC";
        if($cond_row['limit'] && $cond_row['is_limit']){
            $sql .= " LIMIT ".$cond_row['start_limit'].','.$cond_row['limit'];
        }else{
            $start = $cond_row['limits']*1000;
            $sql .= " LIMIT ".$start.',1000';
        }
        $data = $this->sql->getAll($sql);
        if($data){
            $Order_StateModel = new Order_StateModel();
            foreach($data as $k=>$v){
                $data[$k]['order_from_text'] = $Order_StateModel->orderFrom[$v['order_from']];
                $data[$k]['order_status_text'] = $Order_StateModel->orderState[$v['order_status']];
                $data[$k]['order_buyer_evaluation_status'] = $Order_StateModel->evaluationStatus[$v['order_buyer_evaluation_status']];
            }
        }
        return $data;
    }

    //数据库总条数分页
    public function getCounts($action)
    {
        $sqls = "SELECT count(*) as sum FROM ".$this->_tableName;
        if($action == 'virtual')
        {
            $sqls .= ' WHERE order_is_virtual = '.Order_BaseModel::ORDER_IS_VIRTUAL;
        }
        $sum = $this->sql->getRow($sqls);
        $limt = ceil($sum['sum']/1000);
        return $limt;
    }

   //订单支付成功后修改订单状态
    public function webpos_editOrderStatusAferPay($order_id = null, $uorder_id = null)
    {
        $flag = false;
        //查找订单信息
        $order_base = $this->getOne($order_id);
        if($order_base['order_status'] == Order_StateModel::ORDER_WAIT_PAY)
        {
            $edit_row = array('order_status' => Order_StateModel::ORDER_PAYED);
            $edit_row['payment_time'] = get_date_time();
            $edit_row['payment_other_number'] = $uorder_id;
            //修改订单状态
            $this->editBase($order_id, $edit_row);

            //修改订单商品状态
            $Order_GoodsModel = new Order_GoodsModel();
            $edit_goods_row = array('order_goods_status' => Order_StateModel::ORDER_PAYED);
            $order_goods_id = $Order_GoodsModel->getKeyByWhere(array('order_id' => $order_id));
            $flag = $Order_GoodsModel->editGoods($order_goods_id, $edit_goods_row);

            //修改商品的销量
            $Goods_BaseModel = new Goods_BaseModel();
            $Goods_BaseModel->editGoodsSale($order_goods_id);

            $Goods_CommonModel = new Goods_CommonModel();

            //判断是否是虚拟订单，若是虚拟订单则生成发送信息并修改订单状态为已发货
            if ($order_base['order_is_virtual'] == Order_BaseModel::ORDER_IS_VIRTUAL) {
                //循环订单商品
                $Text_Password = new Text_Password();
                $Order_GoodsVirtualCodeModel = new Order_GoodsVirtualCodeModel();

                $msg_str = '尊敬的用户您已在' . $order_base['shop_name'] . '成功购买';
                $goods_name_str = '';
                $virtual_code_str = '';
                foreach ($order_goods_id as $k => $v) {
                    $order_goods_base = $Order_GoodsModel->getOne($v);
                    //判断该商品是否是虚拟商品
                    $goods_common_base = $Goods_CommonModel->getOne($order_goods_base['common_id']);
                    if($goods_common_base['common_is_virtual'])
                    {
                        $num = $order_goods_base['order_goods_num'];
                        //获取购买的数量，循环生成虚拟兑换码，将信息插入虚拟兑换码表中

                        for ($i = 0; $i < $num; $i++) {
                            $virtual_code = $Text_Password->create(8, 'unpronounceable', 'numeric');
                            $virtual_code_str .= $virtual_code . ',';
                            $add_row = array();
                            $add_row['virtual_code_id'] = $virtual_code;
                            $add_row['order_id'] = $order_id;
                            $add_row['order_goods_id'] = $v;
                            $add_row['virtual_code_status'] = Order_GoodsVirtualCodeModel::VIRTUAL_CODE_NEW;

                            $Order_GoodsVirtualCodeModel->addCode($add_row);
                        }
                        $goods_name_str .= $order_goods_base['goods_name'] . '，';


                        //修改订单商品为已发货待收货4
                        $edit_order_goods_row['order_goods_status'] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
                        $Order_GoodsModel->editGoods($v, $edit_order_goods_row);
                    }
                }

                $msg_str = $msg_str . $goods_name_str . '您可凭兑换码' . $virtual_code_str . '在本店进行消费。';
                Sms::send($order_base['order_receiver_contact'], $msg_str);
                //$str = Sms::send(13918675918,"尊敬的用户您已在【变量】成功购买【变量】，您可凭兑换码【变量】在本店进行消费。");

                $goods_common_id = array_column($Order_GoodsModel->get($order_goods_id),'common_id');
                $Goods_Common = new Goods_CommonModel();
                $goods_common = current($Goods_Common->get($goods_common_id));
                //修改订单状态为已发货等待收货4
                $edit_order_row['order_status'] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
                $edit_order_row['order_shipping_time'] = get_date_time();
                $edit_order_row['order_receiver_date'] = $goods_common['common_virtual_date'];
                $this->editBase($order_id, $edit_order_row);
            }

            //判断是否是门店自提订单，若是门店自提订单则生成发送信息并修改订单状态为待自提
            if ($order_base['chain_id']) {
                $code     = VerifyCode::getCode($order_base['order_receiver_contact']);

                $Chain_BaseModel=new Chain_BaseModel();
                $chain_base = current($Chain_BaseModel->getByWhere(array('chain_id'=>$order_base['chain_id'])));

                $order_goods_base = $Order_GoodsModel->getOne($order_goods_id[0]);

                $Order_GoodsChainCodeModel = new Order_GoodsChainCodeModel();
                $code_data['order_id']=$order_id;
                $code_data['chain_id']=$order_base['chain_id'];
                $code_data['order_goods_id']=$order_goods_id[0];
                $code_data['chain_code_id']=$code;
                $Order_GoodsChainCodeModel->addGoodsChainCode($code_data);

                //修改订单状态为待自提11
                $edit_order_goods_row['order_goods_status'] = Order_StateModel::ORDER_FINISH;
                $Order_GoodsModel->editGoods($order_goods_id, $edit_order_goods_row);

                $edit_order_row['order_status'] = Order_StateModel::ORDER_FINISH;
                $this->editBase($order_id, $edit_order_row);

                $message = new MessageModel();
                $message->sendMessage('Self pick up code', Perm::$userId, Perm::$row['user_account'], $order_id = NULL, $shop_name = $order_base['shop_name'], 1, MessageModel::ORDER_MESSAGE,  NUll,NULL,NULL,NULL, Null,$goods_name=$order_goods_base['goods_name'], NULL,NULL,$ztm=$code,$chain_name=$chain_base['chain_name'],$order_phone=$order_base['order_receiver_contact']);
                //$str = Sms::send(13918675918,"尊敬的用户您已在[shop_name]成功购买[goods_name]，您可凭自提码[ztm]在[chain_name]自提。");
            }


            //判断此订单是否使用了代金券，如果使用，则改变代金券的使用状态
            /*if ($order_base['voucher_id']) {
                $Voucher_BaseModel = new Voucher_BaseModel();
                $Voucher_BaseModel->changeVoucherState($order_base['voucher_id'], $order_base['order_id']);

                //代金券使用提醒
                $message = new MessageModel();
                $message->sendMessage('The use of vouchers to remind', Perm::$userId, Perm::$row['user_account'], $order_id = NULL, $shop_name = NULL, 0, MessageModel::USER_MESSAGE);
            }*/
        }
        return $flag;
    }

}

?>

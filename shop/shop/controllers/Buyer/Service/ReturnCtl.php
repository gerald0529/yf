<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    /**
     * @author     Xinze <xinze@live.cn>
     */
    class Buyer_Service_ReturnCtl extends Buyer_Controller
    {
        public $orderReturnModel = null;
        public $orderBaseModel = null;
        public $orderGoodsModel = null;
        public $orderReturnReasonModel = null;
        
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
            $this -> orderReturnModel = new Order_ReturnModel();
            $this -> orderBaseModel = new Order_BaseModel();
            $this -> orderGoodsModel = new Order_GoodsModel();
            $this -> orderReturnReasonModel = new Order_ReturnReasonModel();
            $this -> Order_BaseModel = new Order_BaseModel();
            $this -> Order_ReturnModel = new Order_ReturnModel();
            $this -> Order_ReturnReasonModel = new Order_ReturnReasonModel();
            $this -> Order_GoodsModel = new Order_GoodsModel();
            $this -> Order_RefundGoodsModel = new Order_RefundGoodsModel();
        }
        
        /**
         * 首页
         *
         * @access public
         */
        public function index()
        {
            $act = request_string('act');
            if ($act == "detail") {
                $data = $this -> detail();
                $this -> view -> setMet('detail');
                $d = $data;
            } elseif ($act == "add") {
                $data = $this -> add();
                fb($data);
                fb('申请退款退货订单信息');
                if ($data == -3) {
                    $this -> view -> setMet('error3');
                } elseif ($data == -4) {
                    $this -> view -> setMet('error4');
                } elseif ($data == -1) {
                    $this -> view -> setMet('error2');
                } elseif ($data == 0) {
                    $this -> view -> setMet('error');
                } else {
                    $this -> view -> setMet('add');
                }
                $d = $data;
            } else {
                $Yf_Page = new Yf_Page();
                $Yf_Page -> listRows = 10;
                $rows = $Yf_Page -> listRows;
                $offset = request_int('firstRow', 0);
                $page = ceil_r($offset / $rows);
                $start_time = request_string("start_time");
                $end_time = request_string("end_time");
                $order_id = request_string("return_code");
                $state = request_int("state", 1);
                $cond_row['buyer_user_id'] = Perm ::$userId;         //店铺ID
                if ($start_time) {
                    $cond_row['return_add_time:>='] = $start_time;
                }
                if ($end_time) {
                    $cond_row['return_add_time:<='] = $end_time;
                }
                if ($order_id) {
                    $cond_row['return_code'] = $order_id;
                }
                if ($state) {
                    $cond_row['return_type'] = $state;
                }
                $data = $this -> orderReturnModel -> getReturnList($cond_row, array('return_add_time' => 'DESC'), $page, $rows);
                $data['state'] = $state;
                $Yf_Page -> totalRows = $data['totalsize'];
                $page_nav = $Yf_Page -> prompt();
                $d = $data;
            }
            if ($this -> typ == "json") {
                if (is_array($d)) {
                    if (empty($d['goods_list'])) {
                        $d['goods_list'] = array_values($d['goods']);
                    } else {
                        $d['goods_list'] = '';
                    }
                    if (isset($d['reason'])) {
                        $d['reason'] = array_values($d['reason']);
                    } else {
                        $d['reason'] = '';
                    }
                    $this -> data -> addBody(-140, $d, __('数据获取成功'), 200);
                } else {
                    $d = array();
                    $this -> data -> addBody(-140, $d, __('数据获取失败'), 250);
                }
            } else {
                include $this -> view -> getView();
            }
        }
        
        public function detail()
        {
            $return_id = request_int("id");
            $cond_row['order_return_id'] = $return_id;
            //查找退货单信息
            $data = $this -> orderReturnModel -> getReturn($cond_row);
            //查找订单信息
            $order_base = $this -> orderBaseModel -> getOne($data['order_number']);
            //如果下单这为当前用户 或者此订单是主管账号支付订单并且当前用户是主管账号，则当前用户可以查看该笔订单的退款退货状态
            if ($order_base['buyer_user_id'] == Perm ::$userId || ($order_base['order_sub_pay'] == Order_StateModel::SUB_USER_PAY && $order_base['order_sub_user'] == Perm ::$userId)) {
                if ($data['return_type'] == Order_ReturnModel::RETURN_TYPE_GOODS) {
                    $data['text'] = __("退货");
                } else {
                    $data['text'] = __("退款");
                }
                if ($data['order_goods_id']) {
                    $data['refund_goods'] = $this -> orderGoodsModel -> getOne($data['order_goods_id']);
                }
                $data['order'] = $order_base;
                $return_limit = $this -> orderReturnModel -> getByWhere(array(
                    'order_number' => $data['order']['order_id'],
                    'return_shop_handle:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS
                ));
                $cash = 0;
                foreach ($return_limit as $v) {
                    $cash += $v['return_cash'];
                }
                $data['return_limit'] = $cash;
                return $data;
            } else {
                $data = array();
                return $data;
            }
        }
        
        public function add()
        {
            $Order_StateModel = new Order_StateModel();
            $order_id = request_string("oid");
            $goods_id = request_int("gid");
            //判断订单商品的状态，如果是已付款则显示为退款。如果是已完成则显示为退货
            $goods = $this -> orderGoodsModel -> getOne($goods_id);
            //如果传递过来的订单号和根据商品id查到的订单号不符，报错
            if ($goods['order_id'] != $order_id) {
                return;
            }
            //订单信息
            $data['order'] = $this -> orderBaseModel -> getOne($goods['order_id']);
            $data['order_goods'] = $this -> orderGoodsModel -> getByWhere([
                'order_id' => $order_id,
                'order_goods_amount:>' => 0 //过滤赠品
            ]);
            $data['goods'] = $goods;
            //订单为已完成（6），退货
            if ($goods['order_goods_status'] == Order_StateModel::ORDER_FINISH) {
                //白条不支持退款和退货
                if (strstr($data['order']['payment_name'], '白条支付')) {
                    return -4;
                }
                if ($data['order']['order_status'] < Order_StateModel::ORDER_WAIT_CONFIRM_GOODS) {
                    return -1;
                }
                $data['text'] = __("退货");
                $data['class'] = 'return';
                //判断该件商品是否已经存在退货
                $return = $this -> orderReturnModel -> getByWhere(array(
                    'order_goods_id' => $goods_id,
                    'return_type' => Order_ReturnModel::RETURN_TYPE_GOODS,
                    //'return_state:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS
                    'return_shop_handle:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS
                ));
                //订单为已付款（2），退款。自提订单为待自提（11）
            } elseif ($goods['order_goods_status'] == Order_StateModel::ORDER_PAYED || $goods['order_goods_status'] == Order_StateModel::ORDER_SELF_PICKUP) {
                //白条不支持退款和退货
                if (strstr($data['order']['payment_name'], '白条支付')) {
                    return -4;
                }
                $data['text'] = __("退款");
                $data['class'] = 'refund';
                if ($data['order']['order_status'] < Order_StateModel::ORDER_PAYED) {
                    return -1;
                }
                //判断该件商品是否存在退款
                $return = $this -> orderReturnModel -> getByWhere(array(
                    'order_goods_id' => $goods_id,
                    'return_type' => Order_ReturnModel::RETURN_TYPE_ORDER,
                    //'return_state:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS
                    'return_shop_handle:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS
                ));
            }
            //虚拟订单退款
            if ($data['order']['order_is_virtual']) {
                //白条不支持退款和退货
                if (strstr($data['order']['payment_name'], '白条支付')) {
                    return -4;
                }
                $data['text'] = __("退款");
                $data['class'] = 'refund';
                if ($data['order']['order_status'] < Order_StateModel::ORDER_PAYED) {
                    return -1;
                }
                //判断该件商品是否存在退款
                $return = $this -> orderReturnModel -> getByWhere(array(
                    'order_goods_id' => $goods_id,
                    'return_type' => Order_ReturnModel::RETURN_TYPE_VIRTUAL,
                    //'return_state:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS
                    'return_shop_handle:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS
                ));
            }
            $data['order_id'] = $order_id;
            $data['goods_id'] = $goods_id;
            $data['reason'] = $this -> orderReturnReasonModel -> getByWhere(array(), array('order_return_reason_sort' => 'ASC'));
            //判断这件“退款/退货”商品是否还有可退数量（退款，退货都会退还商品数量）
            $this_goods_return = $this -> orderReturnModel -> getByWhere(array(
                'order_goods_id' => $goods_id,
                //'return_state:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS,
                'return_shop_handle:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS,
            ));
            //“退款/退货”商品总的退还件数
            $this_goods_return_num = array_sum(array_column($this_goods_return, 'order_goods_num'));
            //“退款/退货”商品总的已退金额（包含正在审核中的金额）
            $this_goods_return_cash = array_sum(array_column($this_goods_return, 'return_cash'));
            //如果该件商品的已退或正在退货的商品数量 = 该订单商品购买数量则无可退还商品数量
            if ($this_goods_return_num == $goods['order_goods_num']) {
                return -3;
            }
            /*商品处于可退还状态下，判断订单还可退还的金额*/
            //查找该笔订单已经进行过或正进行中的的退款，退货
            $order_return = $this -> orderReturnModel -> getByWhere(array(
                'order_number' => $data['order']['order_id'],
                //'return_state:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS
                'return_shop_handle:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS
            ));
            //订单已经退款退货的金额（包括与同意的退款和正在审核中的退款）
            $order_return_cash = array_sum(array_column($order_return, 'return_cash'));
            //订单已经退还的商品数量
            $order_return_num = array_sum(array_column($order_return, 'order_goods_num'));
            //订单还可退还的金额 = 订单实付金额 - 订单已退金额
            //如果没有发货，可以退运费
            if (Order_StateModel::ORDER_PAYED == $data['order']['order_status']) {
                $order_can_return_cash = $data['order']['order_payment_amount'] - $order_return_cash;
            } else {
                $order_can_return_cash = $data['order']['order_payment_amount'] - $order_return_cash - $data['order']['order_shipping_fee'];
            }
            //订单无可退金额，则报错
            if ($order_can_return_cash <= 0) {
                return -3;
            }
            //订单中所有商品数量
            $order_all_goods_num = array_sum(array_column($data['order_goods'], 'order_goods_num'));
            /*
             * $data['order']['order_refund_amount'] 与 $order_return_cash 的区别
             * $data['order']['order_refund_amount']：表示商家已经同意的退款金额
             * $order_return_cash：表示买家已经申请的退款，除被商家拒绝的退款外，正在审核的退款也包含在内
             */
            //订单已退还的金额
            $data['return_limit'] = $data['order']['order_refund_amount'];
            //订单可退金额
            $data['cash_limit'] = $order_can_return_cash;
            //订单可退商品数量
            $data['nums'] = $order_all_goods_num - $order_return_num;
            //该件商品可退的总金额,
            //这个要判断有没有赠送的 sun
            $data['return_goods_cash'] = $goods['order_goods_amount'] - $this_goods_return_cash;
            //该件商品还可退还商品数量
            // 如果是虚拟订单，查看虚拟订单的 未使用兑换码的 数量
            if ($data['order']['order_is_virtual']) {
                // 未使用的数量
                $GoodsVirtualCodeModel = new Order_GoodsVirtualCodeModel();
                $order_new = $GoodsVirtualCodeModel -> getByWhere(array('order_id' => $data['order']['order_id'], 'virtual_code_status' => Order_GoodsVirtualCodeModel::VIRTUAL_CODE_NEW));
                $new = count($order_new);
                if ($new == 0) {
                    return -3;
                }
                $data['return_goods_nums'] = $new;
                // 可退金额 = 订单总金额/总数量 * 未使用数量
                $data['return_cash'] = $data['return_goods_cash'] / $goods['order_goods_num'] * $new;
            } else {
                $data['return_goods_nums'] = $goods['order_goods_num'] - $this_goods_return_num;
                //实际该件商品可退还的金额（有时可能包含运费）
                $data['return_cash'] = $data['return_goods_cash'];
            }
            //如果订单为已付款状态，并且所有商品都退款，则将运费退还
            if (Order_StateModel::ORDER_PAYED == $data['order']['order_status'] && $data['nums'] == $data['return_goods_nums']) {
                $data['return_cash'] = $data['cash_limit'];
            }
            //如果该商品已经存在退款退货情况则不能再进行退款退货
            if (!empty($return)) {
                return 0;
            } else {
                //退运费只发生在订单状态为已付款状态
                if ($goods['order_goods_status'] == Order_StateModel::ORDER_PAYED) {
                    $return_goods_nums = $data['return_goods_nums']; //判断当前页面是否可以退运费
                    $is_back_shipping_cost = $order_all_goods_num == ($return_goods_nums + $order_return_num) //已退商品数量+本次可以退商品数量
                        ? 1
                        : 0;
                } else {
                    $is_back_shipping_cost = 0;
                }
                $data['is_back_shipping_cost'] = $is_back_shipping_cost;
                return $data;
            }
        }
        
        public function addReturn()
        {
            $Order_StateModel = new Order_StateModel();
            $order_id = request_string("order_id");      //退款订单号
            $goods_id = request_int("goods_id");         //退货订单商品id
            $flag2 = true;
            $Number_SeqModel = new Number_SeqModel();
            $prefix = sprintf('%s-%s-', Yf_Registry ::get('shop_app_id'), date('YmdHis'));
            $return_number = $Number_SeqModel -> createSeq($prefix);
            $return_id = sprintf('%s-%s-%s-%s', 'TD', Perm ::$userId, 0, $return_number);
            $field['return_message'] = request_string("return_message");    //“退款/退货”说明
            $field['return_reason_id'] = request_string("return_reason_id");  //“退款/退货”原因
            $field['return_code'] = $return_id;                             //退货单号
            $nums = request_int("nums", 0);                                //“退款/退货”数量
            $field['order_goods_num'] = $nums;                          //“退款/退货”数量
            $reason = $this -> orderReturnReasonModel -> getOne($field['return_reason_id']);
            $field['return_reason'] = $reason['order_return_reason_content'];   //“退款/退货”原因
            $order = $this -> orderBaseModel -> getOne($order_id);
            $goods = $this -> orderGoodsModel -> getOne($goods_id);
            $field['order_number'] = $goods['order_id'];            //订单号
            $field['order_goods_id'] = $goods_id;                      //订单商品id
            $field['order_goods_name'] = $goods['goods_name'];         //退货商品名称
            $field['order_goods_price'] = $goods['goods_price'];        //商品单价
            $field['order_goods_pic'] = $goods['goods_image'];        //商品图片
            $field['order_amount'] = $order['order_payment_amount'];     //订单实际支付金额
            $field['seller_user_id'] = $order['shop_id'];               //店铺id
            $field['seller_user_account'] = $order['shop_name'];            //店铺名称
            $field['buyer_user_id'] = $order['buyer_user_id'];        //买家id
            $field['buyer_user_account'] = $order['buyer_user_name'];     //买家名称
            $field['return_add_time'] = get_date_time();                 //退款、退货申请提交时间
            $field['order_is_virtual'] = $order['order_is_virtual'];     //该笔订单是否为虚拟订单
            //如果传递过来的订单号和根据商品id查到的订单号不符，报错
            if ($goods['order_id'] != $order_id) {
                $flag2 = false;
            }
            if ($order['order_is_virtual']) {
                $field['return_type'] = Order_ReturnModel::RETURN_TYPE_VIRTUAL;
                // 如果为虚拟订单，即查询未使用的兑换码，
                $Order_GoodsVirtualCodeModel = new Order_GoodsVirtualCodeModel();
                $cond_row = array();
                $cond_row['order_id'] = $order_id;
                $cond_row['virtual_code_status'] = Order_GoodsVirtualCodeModel::VIRTUAL_CODE_NEW;
                $virtualcode = $Order_GoodsVirtualCodeModel -> getVirtualCode($cond_row);
                // 让兑换码冻结，数量为退款的商品数量。默认从 第一个 开始 变成 冻结状态 2 Order_GoodsVirtualCodeModel::VIRTUAL_CODE_FROZEN;
                $frozencode = array_slice($virtualcode, 0, $nums);
                // 根据$frozencode 的 值 查询 虚拟兑换码 列表 并将虚拟码code的变成2
                $rs_row = array();
                $update['virtual_code_status'] = Order_GoodsVirtualCodeModel::VIRTUAL_CODE_FROZEN;
                $update['virtual_code_usetime'] = date('Y-m-d H:i:s', time());                            //冻结时间
                if (is_array($frozencode)) {
                    foreach ($frozencode as $value) {
                        $result = $Order_GoodsVirtualCodeModel -> editCode($value, $update);
                    }
                    check_rs($result, $rs_row);
                }
            } else {
                switch ($order['order_status']) {
                    case Order_StateModel::ORDER_PAYED:
                        $field['return_type'] = Order_ReturnModel::RETURN_TYPE_ORDER; //退款
                        break;
                    case Order_StateModel::ORDER_FINISH:
                        $field['return_type'] = Order_ReturnModel::RETURN_TYPE_GOODS; //退货
                        break;
                }
            }
            //如果是货到付款，确认收货（付款）后才能退款
            if ($order['payment_id'] == PaymentChannlModel::PAY_CONFIRM) {
                if ($order['order_status'] < Order_StateModel::ORDER_RECEIVED) {
                    $flag2 = false;
                }
            }
            //退款(货到付款只支持退货，不支持退款)
            if ($goods['order_goods_status'] == Order_StateModel::ORDER_PAYED && $order['payment_id'] != PaymentChannlModel::PAY_CONFIRM) {
                //白条支付不支持退款和退货
                if (strstr($order['payment_name'], '白条支付')) {
                    $flag2 = false;
                }
                $field['return_goods_return'] = 0;      //是否需要退货  0-不需要  1-需要
                $return = $this -> orderReturnModel -> getByWhere(array(
                    'order_goods_id' => $goods_id,
                    'return_type' => Order_ReturnModel::RETURN_TYPE_ORDER,
                    'return_state:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS
                ));
            }
            //退货
            if ($goods['order_goods_status'] == Order_StateModel::ORDER_FINISH) {
                if (strstr($order['payment_name'], '白条支付')) {
                    $flag2 = false;
                }
                $field['return_goods_return'] = 1;    //需要退货
                //查询是否存在该订单商品的退货申请信息，且该申请未被卖家拒绝，以此判断是否重新提交退货申请
                //只有以前没有提交过该商品的退货申请，或者提交申请未被卖家拒绝的情况下，才可以提交退货申请
                $return = $this -> orderReturnModel -> getByWhere(array(
                    'order_goods_id' => $goods_id,
                    'return_type' => Order_ReturnModel::RETURN_TYPE_GOODS,
                    'return_shop_handle:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS
                ));
            }
            /* 计算“退款/退货”商品和订单的各种金额 */
            //判断这件“退款/退货”商品是否还有可退数量（退款，退货都会退还商品数量）
            $this_goods_return = $this -> orderReturnModel -> getByWhere(array(
                'order_goods_id' => $goods_id,
                'return_shop_handle:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS
            ));
            //“退款/退货”商品总的退还件数
            $this_goods_return_num = array_sum(array_column($this_goods_return, 'order_goods_num'));
            //echo "this_goods_return_num:".$this_goods_return_num."<br>";
            //“退款/退货”商品总的已退金额（包含正在审核中的金额）
            $this_goods_return_cash = array_sum(array_column($this_goods_return, 'return_cash'));
            //echo "this_goods_return_cash:".$this_goods_return_cash."<br>";
            //“退款/退货”商品总的已退佣金金额（包含正在审核中的金额）
            $this_goods_return_comission = array_sum(array_column($this_goods_return, 'return_commision_fee'));
            //echo "this_goods_return_comission:".$this_goods_return_comission."<br>";
            //echo $goods['order_goods_num']."<br>";
            //如果该件商品的已退或正在退货的商品数量 = 该订单商品购买数量则无可退还商品数量
            if ($this_goods_return_num == $goods['order_goods_num']) {
                $flag2 = false;
            }
            /*商品处于可退还状态下，判断订单还可退还的金额*/
            //查找该笔订单已经进行过或正进行中的的退款，退货
            $order_return = $this -> orderReturnModel -> getByWhere(array(
                'order_number' => $order['order_id'],
                'return_shop_handle:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS
            ));
            //订单已经退款退货的金额（包括与同意的退款和正在审核中的退款）
            $order_return_cash = array_sum(array_column($order_return, 'return_cash'));
            //echo "order_return_cash:".$order_return_cash."<br>";
            //订单已经退还的商品数量
            $order_return_num = array_sum(array_column($order_return, 'order_goods_num'));
            //echo "order_return_num:".$order_return_cash."<br>";
            //订单还可退还的金额 = 订单实付金额 - 订单已退金额
            //如果没有发货，可以退运费
            if (Order_StateModel::ORDER_PAYED == $order['order_status']) {
                $order_can_return_cash = $order['order_payment_amount'] - $order_return_cash;
            } else {
                $order_can_return_cash = $order['order_payment_amount'] - $order_return_cash - $order['order_shipping_fee'];
            }
            //订单无可退金额，则报错
            if ($order_can_return_cash <= 0) {
                $flag2 = false;
            }
            //订单中所有商品数量
            //'order_goods_amount:>'=>0 不包含赠品 ..
            //sun
            $order_goods = $this -> orderGoodsModel -> getByWhere(array('order_id' => $order_id, 'order_goods_amount:>' => 0));
            $order_all_goods_num = array_sum(array_column($order_goods, 'order_goods_num'));
            /*
             * $data['order']['order_refund_amount'] 与 $order_return_cash 的区别
             * $data['order']['order_refund_amount']：表示商家已经同意的退款金额
             * $order_return_cash：表示买家已经申请的退款，除被商家拒绝的退款外，正在审核的退款也包含在内
             */
            //订单已退还的金额
            $return_limit = $order['order_refund_amount'];
            //echo "return_limit:".$return_limit."<br>";
            //订单可退金额
            $cash_limit = $order_can_return_cash;
            //echo "cash_limit:".$cash_limit."<br>";
            //订单可退商品数量
            $goods_can_return_nums = $order_all_goods_num - $order_return_num;
            //echo "goods_can_return_nums:".$goods_can_return_nums."<br>";
            //该件商品可退的总金额
            $return_goods_cash = $goods['order_goods_amount'] - $this_goods_return_cash;
            //echo "return_goods_cash:".$return_goods_cash."<br>";
            //该件商品还可退还商品数量
            $return_goods_nums = $goods['order_goods_num'] - $this_goods_return_num;
            //echo "return_goods_nums:".$return_goods_nums."<br>";
            //如果商品退款/退货的数量则报错
            if ($goods_can_return_nums < $nums) {
                $flag2 = false;
            }
            //实际该件商品可退还的金额（有时可能包含运费）
            //该件商品全部“退款/退货” //return_goods_nums
            if ($goods_can_return_nums == $nums && Order_StateModel::ORDER_PAYED == $order['order_status']) {
                //加上运费(未发货)
                $return_cash = $return_goods_cash + $order['order_shipping_fee'];
            } else {
                $return_cash = floor($nums * $goods['order_goods_payment_amount'] * 100) / 100;
            }
            //如果订单为已付款状态，并且所有商品都退款，则将运费退还
            if (Order_StateModel::ORDER_PAYED == $order['order_status'] && $nums == $goods_can_return_nums) {
                $return_cash = $cash_limit;
            }
            //自提商品
            if ($order['order_status'] == Order_StateModel::ORDER_SELF_PICKUP) {
                if ($nums == $goods_can_return_nums) {
                    $return_cash = $cash_limit;
                } else {
                    $return_cash = floor($nums * $goods['order_goods_payment_amount'] * 100) / 100;
                }
            }
            /*退款退货走同样的流程。区别是：退款时可能会退还运费，退货不可能退还运费。*/
            //如果买家申请的退货数量与最多可以申请的退货数量相同，并且退款金额=最多可申请退款金额
            //退还佣金
            echo $order['order_commission_fee'] . "<br>";
            echo $goods['order_goods_commission'] . "<br>";
            if ($order['order_commission_fee'] && $goods['order_goods_commission']) {
                if ($nums == $goods_can_return_nums) {
                    $field['return_commision_fee'] = $goods['order_goods_commission'] - $this_goods_return_comission;
                } else {
                    $field['return_commision_fee'] = ($goods['order_goods_commission'] / $goods['order_goods_num']) * $nums;
                }
            }
            //退还红包  order_rpt_return
            //整笔订单金额已经退完，需要卖家退还平台红包
            if ($order['order_rpt_price']) {
                //整笔订单金额已经退完，需要卖家退还所有平台红包
                if ($return_cash == $cash_limit) {
                    $field['return_rpt_cash'] = $order['order_rpt_price'] - $order['order_rpt_return'];
                } else {
                    $field['return_rpt_cash'] = ($return_cash / ($order['order_payment_amount'] - $order['order_shipping_fee'])) * $order['order_rpt_price'];
                }
            }
            if (empty($return) && ($return_cash > 0) && $flag2) {
                $field['return_cash'] = $return_cash;
                if ($order['buyer_user_id'] == Perm ::$userId && !strstr($order['payment_name'], '白条支付')) {
                    $rs_row = array();
                    $this -> orderReturnModel -> sql -> startTransactionDb();
                    //若果存在分销商采购单，添加退款订单，改变购物订单状态
                    $dist_order = $this -> orderBaseModel -> getByWhere(array('order_source_id' => $order_id));
                    if (!empty($dist_order)) {
                        //判断该件商品是否是一件代发分销商品
                        $Goods_CommonModel = new Goods_CommonModel();
                        $goods_common = $Goods_CommonModel -> getOne($goods['common_id']);
                        if ($goods_common['product_is_behalf_delivery'] && $goods_common['common_parent_id']) {
                            $field['behalf_deliver'] = Order_ReturnModel::BEHALF_DELIVER_SHOP;
                        }
                    }
                    $add_flag = $this -> orderReturnModel -> addReturn($field, true);
                    check_rs($add_flag, $rs_row);
                    if (!empty($dist_order)) {
                        foreach ($dist_order as $key => $value) {
                            fb($value['order_id']);
                            /*$dist_flag.$key = $this->addDistReturn($value['order_id'],$field['return_reason_id'],$field['return_message'],$goods_id);
                            check_rs($dist_flag.$key, $rs_row);*/
                            $key = $this -> addDistReturn($value['order_id'], $field['return_reason_id'], $field['return_message'], $goods_id, $add_flag);
                            check_rs($key, $rs_row);
                        }
                    }
                    //订单商品表中插入订单商品的“退款/退货”状态
                    if ($field['return_goods_return'] == 0) {
                        //退款
                        $goods_field['goods_return_status'] = Order_GoodsModel::REFUND_IN;
                        $edit_flag = $this -> orderGoodsModel -> editGoods($goods_id, $goods_field);
                        check_rs($edit_flag, $rs_row);
                    } else {
                        //退货
                        $goods_field['goods_refund_status'] = Order_GoodsModel::REFUND_IN;
                        $edit_flag = $this -> orderGoodsModel -> editGoods($goods_id, $goods_field);
                        check_rs($edit_flag, $rs_row);
                    }
                    $flag = is_ok($rs_row);
                    if ($flag && $this -> orderReturnModel -> sql -> commitDb()) {
                        $msg = __('success');
                        $status = 200;
                        $shopBase = new Shop_BaseModel();
                        $shop_detail = $shopBase -> getOne($field['seller_user_id']);
                        $order_return_data = $this -> orderReturnModel -> getOneByWhere(['order_number' => $order_id]);
                        $message = new MessageModel();
                        if (!$field['return_goods_return']) {
                            //退款提醒
                            $message -> sendMessage('Refund reminder', $shop_detail['user_id'], $shop_detail['user_name'], $order_return_data['return_code'], $shop_name = null, 1, 1);
                        } else {
                            //退货提醒
                            $message -> sendMessage('Return reminder', $shop_detail['user_id'], $shop_detail['user_name'], $order_return_data['return_code'], $shop_name = null, 1, 1);
                        }
                        $msg = __('退款/货成功，请稍候...');
                        $status = 200;
                    } else {
                        $this -> orderReturnModel -> sql -> rollBackDb();
                        $msg = __('退款/货失败，请稍候...');
                        $status = 250;
                    }
                } else {
                    $msg = __('请稍后~订单正在处理中...');
                    $status = 250;
                }
            } else {
                $msg = __('无退款/货订单处理！');
                $status = 250;
            }
            $data = array();
            $this -> data -> addBody(-140, $data, $msg, $status);
        }
        
        public function addDistReturn($order_id, $return_reason_id, $return_message, $goods_parent_id, $order_return_id)
        {
            $ddorder_return = $this -> orderReturnModel -> getOne($order_return_id);
            $ddorder_goods_base = $this -> Order_GoodsModel -> getOne($ddorder_return['order_goods_id']);
            $order = $this -> Order_BaseModel -> getOne($order_id);
            //查找SP订单商品1.查找DD订单商品的goods_id。根据此goods_id查找出以此为goods_parent_id的goods_id。
            //				2.根据查找出的goods_id与SP的$order_id查找出订单商品信息。
            $Goods_BaseModel = new Goods_BaseModel();
            $source_goods_base = $Goods_BaseModel -> getOne($ddorder_goods_base['goods_id']);
            $goods = $this -> Order_GoodsModel -> getByWhere(array(
                'order_id' => $order_id,
                'goods_id' => $source_goods_base['goods_parent_id']));
            $goods = current($goods);
            //查找供应订单中的
            fb($goods_parent_id);
            fb($ddorder_return);
            fb($ddorder_goods_base);
            fb($order);
            fb($goods);
            //计算退款金额与退还佣金
            $nums = $ddorder_return['order_goods_num'];//退还商品数量
            //判断这件“退款/退货”商品是否还有可退数量（退款，退货都会退还商品数量）
            $this_goods_return = $this -> orderReturnModel -> getByWhere(array(
                'order_goods_id' => $goods['order_goods_id'],
                'return_state:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS
            ));
            //“退款/退货”商品总的退还件数
            $this_goods_return_num = array_sum(array_column($this_goods_return, 'order_goods_num'));
            //“退款/退货”商品总的已退金额（包含正在审核中的金额）
            $this_goods_return_cash = array_sum(array_column($this_goods_return, 'return_cash'));
            //“退款/退货”商品总的已退佣金金额（包含正在审核中的金额）
            $this_goods_return_comission = array_sum(array_column($this_goods_return, 'return_commision_fee'));
            //如果该件商品的已退或正在退货的商品数量 = 该订单商品购买数量则无可退还商品数量
            if ($this_goods_return_num == $goods['order_goods_num']) {
                return false;
            }
            /*商品处于可退还状态下，判断订单还可退还的金额*/
            //查找该笔订单已经进行过或正进行中的的退款，退货
            $order_return = $this -> orderReturnModel -> getByWhere(array(
                'order_number' => $order['order_id'],
                'return_state:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS
            ));
            //订单已经退款退货的金额（包括与同意的退款和正在审核中的退款）
            $order_return_cash = array_sum(array_column($order_return, 'return_cash'));
            //订单已经退还的商品数量
            $order_return_num = array_sum(array_column($order_return, 'order_goods_num'));
            //订单还可退还的金额 = 订单实付金额 - 订单已退金额
            //如果没有发货，可以退运费
            if (Order_StateModel::ORDER_PAYED == $order['order_status']) {
                $order_can_return_cash = $order['order_payment_amount'] - $order_return_cash;
            } else {
                $order_can_return_cash = $order['order_payment_amount'] - $order_return_cash - $order['order_shipping_fee'];
            }
            //订单无可退金额，则报错
            if ($order_can_return_cash <= 0) {
                return false;
            }
            //订单中所有商品数量
            //'order_goods_amount:>'=>0 不包含赠品 ..
            //sun
            $order_goods = $this -> orderGoodsModel -> getByWhere(array('order_id' => $order_id, 'order_goods_amount:>' => 0));
            $order_all_goods_num = array_sum(array_column($order_goods, 'order_goods_num'));
            /*
             * $data['order']['order_refund_amount'] 与 $order_return_cash 的区别
             * $data['order']['order_refund_amount']：表示商家已经同意的退款金额
             * $order_return_cash：表示买家已经申请的退款，除被商家拒绝的退款外，正在审核的退款也包含在内
             */
            //订单已退还的金额
            $return_limit = $order['order_refund_amount'];
            //订单可退金额
            $cash_limit = $order_can_return_cash;
            //订单可退商品数量
            $goods_can_return_nums = $order_all_goods_num - $order_return_num;
            //该件商品可退的总金额
            $return_goods_cash = $goods['order_goods_amount'] - $this_goods_return_cash;
            //该件商品还可退还商品数量
            $return_goods_nums = $goods['order_goods_num'] - $this_goods_return_num;
            //如果商品退款/退货的数量则报错
            if ($goods_can_return_nums < $nums) {
                return false;
            }
            //实际该件商品可退还的金额（有时可能包含运费）
            //该件商品全部“退款/退货” //return_goods_nums
            if ($goods_can_return_nums == $nums && Order_StateModel::ORDER_PAYED == $order['order_status']) {
                //加上运费(未发货)
                $return_cash = $return_goods_cash + $order['order_shipping_fee'];
            } else {
                $return_cash = floor($nums * $goods['order_goods_payment_amount'] * 100) / 100;
            }
            //如果订单为已付款状态，并且所有商品都退款，则将运费退还
            if (Order_StateModel::ORDER_PAYED == $order['order_status'] && $nums == $goods_can_return_nums) {
                $return_cash = $cash_limit;
            }
            //自提商品
            if ($order['order_status'] == Order_StateModel::ORDER_SELF_PICKUP) {
                if ($nums == $goods_can_return_nums) {
                    $return_cash = $cash_limit;
                } else {
                    $return_cash = floor($nums * $goods['order_goods_payment_amount'] * 100) / 100;
                }
            }
            /*退款退货走同样的流程。区别是：退款时可能会退还运费，退货不可能退还运费。*/
            //如果买家申请的退货数量与最多可以申请的退货数量相同，并且退款金额=最多可申请退款金额
            //退还佣金
            $return_commision_fee = 0;
            if ($order['order_commission_fee'] && $goods['order_goods_commission']) {
                if ($nums == $goods_can_return_nums) {
                    $return_commision_fee = $goods['order_goods_commission'] - $this_goods_return_comission;
                } else {
                    $return_commision_fee = ($goods['order_goods_commission'] / $goods['order_goods_num']) * $nums;
                }
            }
            fb($nums);
            fb($goods['order_goods_payment_amount']);
            fb($order['order_status']);
            fb($return_cash);
            fb($return_commision_fee);
            fb('return_cash');
            $goods_parent_base = $this -> orderGoodsModel -> getOne($goods_parent_id);
            //判断原订单是否是已完成订单，如果是已完成订单，则分销订单为退货。否则是退款
            if ($goods_parent_base['order_goods_status'] == Order_StateModel::ORDER_FINISH) {
                $order_field['order_return_status'] = 1;
                $cond_row['return_goods_return'] = 1;
                $cond_row['return_type'] = Order_ReturnModel::RETURN_TYPE_GOODS;   //退货类型 - 退货
            } else {
                $order_field['order_refund_status'] = 1;
                $cond_row['return_type'] = Order_ReturnModel::RETURN_TYPE_ORDER;   //退款
            }
            $re_rows = array();
            $order_field['order_refund_status'] = Order_BaseModel::REFUND_IN;
            $edit_flag = $this -> orderBaseModel -> editBase($order_id, $order_field);
            check_rs($edit_flag, $re_rows);
            //修改SP订单商品的退款/退货状态
            $goods_field = array();
            $goods_field['goods_return_status'] = $ddorder_goods_base['goods_return_status'];
            $goods_field['goods_refund_status'] = $ddorder_goods_base['goods_refund_status'];
            $edit_flag = $this -> orderGoodsModel -> editGoods($goods['order_goods_id'], $goods_field);
            check_rs($edit_flag, $re_rows);
            $Number_SeqModel = new Number_SeqModel();
            $prefix = sprintf('%s-%s-', Yf_Registry ::get('shop_app_id'), date('YmdHis'));
            $return_number = $Number_SeqModel -> createSeq($prefix);
            $return_id = sprintf('%s-%s-%s-%s', 'SPTD', Perm ::$userId, 0, $return_number);
            $cond_row['order_number'] = $order['order_id'];
            $cond_row['return_message'] = $return_message;
            $cond_row['return_reason_id'] = $return_reason_id;     //退货原因id'
            $cond_row['return_code'] = $return_id;
            $cond_row['order_goods_num'] = $nums;
            $cond_row['return_reason'] = $this -> orderReturnReasonModel -> getOne($cond_row['return_reason_id']);
            $cond_row['order_goods_id'] = $goods['order_goods_id'];
            $cond_row['order_goods_name'] = $goods['goods_name'];
            $cond_row['order_goods_price'] = $goods['goods_price'];
            $cond_row['order_goods_pic'] = $goods['goods_image'];
            $cond_row['order_amount'] = $order['order_payment_amount'];
            $cond_row['seller_user_id'] = $order['shop_id'];
            $cond_row['seller_user_account'] = $order['shop_name'];
            $cond_row['buyer_user_id'] = $order['buyer_user_id'];
            $cond_row['buyer_user_account'] = $order['buyer_user_name'];
            $cond_row['return_add_time'] = get_date_time();
            $cond_row['order_is_virtual'] = $order['order_is_virtual'];
            $cond_row['return_type'] = $ddorder_return['return_type'];
            $cond_row['return_goods_return'] = $ddorder_return['return_goods_return'];
            $cond_row['return_commision_fee'] = $return_commision_fee;
            $cond_row['return_cash'] = $return_cash;
            $cond_row['behalf_deliver'] = Order_ReturnModel::BEHALF_DELIVER_DIST;
            fb($cond_row);
            $add_flag = $this -> orderReturnModel -> addReturn($cond_row, true);
            $flag = is_ok($re_rows);
            if ($flag && $add_flag) {
                return true;
            } else {
                return false;
            }
        }
        
        public function virtualReturn($order_id)
        {
            $Order_StateModel = new Order_StateModel();
            $flag2 = true;
            $Number_SeqModel = new Number_SeqModel();
            $prefix = sprintf('%s-%s-', Yf_Registry ::get('shop_app_id'), date('YmdHis'));
            $return_number = $Number_SeqModel -> createSeq($prefix);
            $return_id = sprintf('%s-%s-%s-%s', 'TD', Perm ::$userId, 0, $return_number);
            $field['return_message'] = __('虚拟商品过期自动退款');
            $field['return_code'] = $return_id;
            $field['return_reason_id'] = 0;
            $field['return_reason'] = "";
            $field['order_number'] = $order_id;
            $order = $this -> orderBaseModel -> getOne($order_id);
            $field['return_type'] = Order_ReturnModel::RETURN_TYPE_VIRTUAL;
            $field['return_goods_return'] = 0;
            $field['return_cash'] = $order['order_payment_amount'];
            $field['order_amount'] = $order['order_payment_amount'];
            $field['seller_user_id'] = $order['shop_id'];
            $field['seller_user_account'] = $order['shop_name'];
            $field['buyer_user_id'] = $order['buyer_user_id'];
            $field['buyer_user_account'] = $order['buyer_user_name'];
            $field['return_add_time'] = get_date_time();
            $field['return_commision_fee'] = $order['order_commission_fee'];
            $field['return_state'] = Order_ReturnModel::RETURN_PLAT_PASS;
            $field['return_finish_time'] = get_date_time();
            $rs_row = array();
            $this -> orderReturnModel -> sql -> startTransactionDb();
            $add_flag = $this -> orderReturnModel -> addReturn($field, true);
            check_rs($add_flag, $rs_row);
            $order_field['order_refund_status'] = Order_BaseModel::REFUND_IN;
            $order_field['order_refund_status'] = Order_BaseModel::REFUND_COM;
            $edit_flag = $this -> orderBaseModel -> editBase($order_id, $order_field);
            check_rs($edit_flag, $rs_row);
            $sum_data['order_refund_amount'] = $order['order_payment_amount'];
            $sum_data['order_commission_return_fee'] = $order['order_commission_fee'];
            $edit_flag = $this -> orderBaseModel -> editBase($order_id, $sum_data, true);
            check_rs($edit_flag, $rs_row);
            $key = Yf_Registry ::get('shop_api_key');
            $url = Yf_Registry ::get('paycenter_api_url');
            $shop_app_id = Yf_Registry ::get('shop_app_id');
            $formvars = array();
            $formvars['app_id'] = $shop_app_id;
            $formvars['user_id'] = $order['buyer_user_id'];
            $formvars['user_account'] = $order['buyer_user_name'];
            $formvars['seller_id'] = $order['seller_user_id'];
            $formvars['seller_account'] = $order['seller_user_name'];
            $formvars['amount'] = $order['order_payment_amount'];
            $formvars['order_id'] = $order_id;
            //$formvars['goods_id'] = $return['order_goods_id'];
            $formvars['uorder_id'] = $order['payment_number'];
            $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=refundTransfer&typ=json', $url), $formvars);
            if ($rs['status'] == 200) {
                check_rs(true, $rs_row);
            } else {
                check_rs(false, $rs_row);
            }
            $flag = is_ok($rs_row);
            if ($flag && $this -> orderReturnModel -> sql -> commitDb()) {
                $msg = __('success');
                $status = 200;
            } else {
                $this -> orderReturnModel -> sql -> rollBackDb();
                $msg = __('failure');
                $status = 250;
            }
            $data = array();
            $this -> data -> addBody(-140, $data, $msg, $status);
        }
        
        public function agree()
        {
            $Order_StateModel = new Order_StateModel();
            $order_return_id = request_int("order_return_id");
            $return_platform_message = request_string("return_platform_message");
            $return = $this -> Order_ReturnModel -> getOne($order_return_id);
            //根据order_id查找订单信息
            $order_base = $this -> Order_BaseModel -> getOne($return['order_id']);
            $data['return_platform_message'] = $return_platform_message;
            $data['return_state'] = Order_ReturnModel::RETURN_PLAT_PASS;
            $data['return_finish_time'] = get_date_time();
            $rs_row = array();
            $this -> Order_ReturnModel -> sql -> startTransactionDb();
            $edit_flag = $this -> Order_ReturnModel -> editReturn($order_return_id, $data);
            check_rs($edit_flag, $rs_row);
            if ($return['order_goods_id']) {
                $goods_data['goods_refund_status'] = Order_GoodsModel::REFUND_COM;
                $edit_flag = $this -> Order_GoodsModel -> editGoods($return['order_goods_id'], $goods_data);
                check_rs($edit_flag, $rs_row);
            } else {
                $order_data['order_refund_status'] = Order_BaseModel::REFUND_COM;
                $edit_flag = $this -> Order_BaseModel -> editBase($return['order_number'], $order_data);
                check_rs($edit_flag, $rs_row);
            }
            $sum_data['order_refund_amount'] = $return['return_cash'];
            $sum_data['order_commission_return_fee'] = $edit_flag = $this -> Order_BaseModel -> editBase($return['order_number'], $sum_data, true);
            check_rs($edit_flag, $rs_row);
            $key = Yf_Registry ::get('paycenter_api_key');
            $formvars = array();
            $formvars['user_id'] = $return['buyer_user_id'];
            $formvars['user_account'] = $return['buyer_user_account'];
            $formvars['seller_id'] = $return['seller_user_id'];
            $formvars['seller_account'] = $return['seller_user_account'];
            $formvars['amount'] = $return['return_cash'];
            $formvars['order_id'] = $return['order_number'];
            $formvars['goods_id'] = $return['order_goods_id'];
            $formvars['uorder_id'] = $order_base['payment_other_number'];
            $formvars['payment_id'] = $order_base['payment_id'];
            $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Info&met=refundTransfer&typ=json', Yf_Registry ::get('paycenter_api_url')), $formvars);
            if ($rs['status'] == 200) {
                check_rs(true, $rs_row);
            } else {
                check_rs(false, $rs_row);
            }
            $flag = is_ok($rs_row);
            if ($edit_flag && $this -> Order_ReturnModel -> sql -> commitDb()) {
                $status = 200;
                $msg = __('success');
            } else {
                $this -> Order_ReturnModel -> sql -> rollBackDb();
                $status = 250;
                $msg = __('failure');
            }
            $this -> data -> addBody(-140, $data, $msg, $status);
        }
        
        //取消退款
        public function cancelRefund()
        {
            $rs_row = array();
            //订单退款id
            $order_return_id = request_string('order_return_id');
            //根据订单退款id查找订单退款信息
            $order_return = $this -> orderReturnModel -> getOne($order_return_id);
            //开启事务
            $this -> orderReturnModel -> sql -> startTransactionDb();
            //判断此退款订单是否是正在处理中的退款
            //1.将订单商品表中的“退款/退货”状态修改为未退款/退货
            $goods_field['goods_return_status'] = Order_GoodsModel::REFUND_NO;
            $edit_flag = $this -> orderGoodsModel -> editGoods($order_return['order_goods_id'], $goods_field);
            check_rs($edit_flag, $rs_row);
            //2.删除已经生成的退款单
            $remove_flag = $this -> orderReturnModel -> removeReturn($order_return_id);
            check_rs($remove_flag, $rs_row);
            //3.判断该商品是否是代发分销商品。
            if ($order_return['behalf_deliver'] == Order_ReturnModel::BEHALF_DELIVER_SHOP) {
                $order_goods = $this -> orderGoodsModel -> getOne($order_return['order_goods_id']);
            }
            $flag = is_ok($rs_row);
            if ($flag && $this -> orderReturnModel -> sql -> commitDb()) {
                $msg = __('success');
                $status = 200;
            } else {
                $this -> orderReturnModel -> sql -> rollBackDb();
                $msg = __('failure');
                $status = 250;
            }
            $data = array();
            $this -> data -> addBody(-140, $data, $msg, $status);
        }
    }

?>

<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

class Api_WebPosCtl extends Yf_AppController
{
    public $unionOrderModel;
    public $request_parameter;
    public $notifyUrlConfig;
    public $unionOrderData;

    public static $orderConfig = [
        'trade_desc'=> 'WEBPOS',
        'app_id'=> 207
    ];

    public static $paymentMethodConfig = [
        'alipay'=> Payment_ChannelModel::ALIPAY,
        'wx'=> Payment_ChannelModel::WECHAT_PAY
    ];

    public $require_field = [ //必要字段
        'createOrder'=> [
            'order_id',
            'trade_title',
            'amount',
            'payment_way'
        ],
        'getOrderInfo'=> [
            'order_id'
        ]
    ];

    function __construct($ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);
        $this->verify();
        $this->unionOrderModel = new Union_OrderModel();
        $this->notifyUrlConfig = [
            'alipay'=> Yf_Registry::get('base_url') . "/paycenter/api/payment/alipay/webpos_notify_url.php",
            'wx'=> Yf_Registry::get('base_url') . "/paycenter/api/payment/wx/webpos_notify_url.php?trade_type=JSAPI"
        ];
    }

    //验证
    private function verify ()
    {
        $this->request_parameter = $_REQUEST;
        if (isset($this->require_field[$this->request_parameter['met']])) {
            foreach ($this->require_field[$this->request_parameter['met']] as $field) {
                if (! isset($this->request_parameter[$field]) || empty($this->request_parameter[$field])) {
                    $this->showError('无效请求');
                }
            }
        }
    }

    private function showError ($msg)
    {
        $error_json = json_encode(array('cmd_id' => -140, 'status' => 250, 'msg' => $msg, 'data' => []));
        exit($error_json);
    }

    /**
     * webPos生成支付订单
     * 注意：
     * 1.buyer_id可能为空，为空为线下会员，未在payCenter注册
     * 2.app_id不清楚为什么等于102，翻遍这个项目没有找到相关注释，暂定102
     * 3.目前webPos只有两种支付方式支付宝、微信！该订单只有在线支付union_online_pay_amount
     * 4.新增交易类型trade_type_id = Trade_TypeModel::WEB_POS = 9
     * 5.in_array(payment_channel_id, Payment_ChannelModel::ALIPAY, Payment_ChannelModel::WECHAT_PAY)
     *
     * 目前确认该业务只和pay_union_order发生关联
     */
    public function createOrder ()
    {
        $param = [
            'order_id'=> request_string('order_id'),
            'trade_title'=> request_string('trade_title'),
            'amount'=> request_string('amount'),
            'buyer_id'=> request_string('buyer_id'),
            'payment_way'=> request_string('payment_way')
        ];
        
        if ($this->getOrder($param['order_id']) === false) {
            return $this->showError('服务器异常，请稍后重试');
        }

        if (! empty($this->unionOrderData)) {
            //如果订单已经存在且完成支付，则禁止本次访问，保证单号唯一性
            if ($this->unionOrderData['order_state_id'] == Union_OrderModel::PAYED) {
                return $this->showError('该订单已支付，请勿重复创建');
            }

            //如果订单存在且未完成支付，则判断订单支付信息是否一样。如果不一样先更新订单
            if ( $param['amount'] != $this->unionOrderData['union_online_pay_amount'] ||
                self::$paymentMethodConfig[$param['payment_way']] != $this->unionOrderData['payment_channel_id']
            ) {
                if ($this->updateUnionOrder($this->unionOrderData['union_order_id'], $param) === false) {
                    return $this->showError('更新订单失败，请稍后重试');
                }
            }
        } else {
            //如果订单不存在则创建订单
            if ($this->createUnionOrder($param) === false) {
                return $this->showError('生成支付订单失败');
            }
        }

        if ($param['payment_way'] === 'alipay') {
            $this->aliPay();
        } else {
            $this->wxPay();
        }
    }

    /**
     * @param $order_id
     * @return array
     * 获取订单信息
     */
    private function getOrder ($order_id)
    {
        return $this->unionOrderData = $this->unionOrderModel->getOneByWhere([
            'inorder'=> $order_id,
            'trade_type_id'=> Trade_TypeModel::WEB_POS,
        ]);
    }

    /**
     * 更新订单信息
     * @param order_id
     * @param $param array
     * @return boolean
     */
    public function updateUnionOrder ($order_id, $param)
    {
        $payment_channel_id = self::$paymentMethodConfig[$param['payment_way']];

        $update_data = [
            'trade_title'=> $param['trade_title'],
            'create_time'=> date('Y-m-d H:i:s'),
            'buyer_id'=> $param['buyer_id'],
            'payment_channel_id'=> $payment_channel_id,
            'union_online_pay_amount'=> $param['amount']
        ];

        return $this->unionOrderModel->editUnionOrder($order_id, $update_data);
    }

    private function createUnionOrder ($param)
    {
        $payment_channel_id = self::$paymentMethodConfig[$param['payment_way']];

        $insert_row = [
            'union_order_id'=> Union_OrderModel::createUnionOrderId(),
            'inorder'=> $param['order_id'],
            'trade_title'=> $param['trade_title'],
            'trade_payment_amount'=> $param['amount'],
            'create_time'=> date('Y-m-d H:i:s'),
            'buyer_id'=> $param['buyer_id'],
            'trade_desc'=> self::$orderConfig['trade_desc'],
            'order_state_id'=> Union_OrderModel::WAIT_PAY,
            'payment_channel_id'=> $payment_channel_id,
            'app_id'=> self::$orderConfig['app_id'],
            'trade_type_id'=> Trade_TypeModel::WEB_POS,
            'union_online_pay_amount'=> $param['amount']
        ];

        $this->unionOrderData = $insert_row;
        return $this->unionOrderModel->addUnionOrder($insert_row);
    }

    private function aliPay ()
    {
        $payment = PaymentModel::create('alipay', [
            'notify_url'=> $this->notifyUrlConfig['alipay']
        ]);
        $payment->pay($this->unionOrderData);
    }

    private function wxPay ()
    {
        $payment = PaymentModel::create('wx_native', [
            'notify_url'=> $this->notifyUrlConfig['wx']
        ]);
        $payment->pay($this->unionOrderData);
    }

    public function getOrderInfo ()
    {
        $order_id = request_string('order_id');
        $data = $this->unionOrderModel->getOneByWhere([
            'inorder'=> $order_id,
            'trade_type_id'=> Trade_TypeModel::WEB_POS
        ]);

        if (empty($data)) {
            return $this->showError('未找到此订单');
        }
        $this->data->addBody(-140, $data, 'success', 200);
    }
}
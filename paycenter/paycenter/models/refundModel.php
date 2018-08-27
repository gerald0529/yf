<?php

/**
 * Class refundModel
 * @author yuli
 * 退款
 * 目前只支持支付宝、微信退款
 */
class refundModel
{
    private static $config = [
        'alipay'=> [
            'alipay',
            'alipayMobile'
        ],
        'wx'=> [
            'wx_native',
            'app_wx_native',
            'app_h5_wx_native'
        ]
    ];

    public $orderModel;
    public $paymentChannelModel;

    public function __construct()
    {
        $this->orderModel = new Union_Order;
        $this->paymentChannelModel = new Payment_ChannelModel;
    }


    /**
     * @param $arr
     * @return boolean
     * @throws Exception
     */
    public function refundSingle($arr)
    {
        $refund_amount = $arr['refund_amount'];
        $return_number = $arr['return_number'];
        $return_goods_name = $arr['return_goods_name'];
        $shop_id = $arr['shop_id'];
        $inorder = $arr['order_number'];

        $union_order_list = $this->orderModel->getByWhere([
            'inorder:LIKE'=> "%$inorder%",
        ]);

        //购物卡支付金额\预存款支付金额 不走此方法
        $union_order = current($union_order_list);
        if ($union_order['union_online_pay_amount'] == 0) {
            return true;
        }

        $union_order_list = array_filter($union_order_list, function ($item) {
            return empty($item['notify_data'])
                ? false
                : true;
        });


        if (empty($union_order_list)) {
            throw new Exception('未找到支付返回结果');
        }
        $union_order = current($union_order_list);
        $order_amount = $union_order['trade_payment_amount'];
        $third_party_result = $union_order['notify_data']; //第三方交易返回结果

        if (isset($third_party_result['trade_no'])) {
            #支付宝
            $third_party_trade_no = $third_party_result['trade_no'];
        } else if (isset($third_party_result['transaction_id'])) {
            #微信
            $third_party_trade_no = $third_party_result['transaction_id'];
        }

        if (!is_numeric($refund_amount) || $refund_amount <= 0) {
            throw new Exception('退款金额格式不正确');
        }

        if (isset($third_party_result['app_id'])) {
            $third_party_app_id = $third_party_result['app_id'];
        } else if (isset($third_party_result['appid'])) {
            $third_party_app_id = $third_party_result['appid'];
        } else {
            throw new Exception('未找到appid');
        }

        $payment_channel = $this->getPaymentChannel($shop_id, $third_party_app_id);
        $payment_config = $payment_channel['payment_channel_config'];
        $payment_config['payment_channel_code'] = $payment_channel['payment_channel_code'];
        //$payment_config['shop_id'] = $payment_channel['shop_id'];

        $refund_order = [
            //'shop_id'=> $shop_id, #店铺id
            'refund_amount'=> $refund_amount, #退款金额
            'return_number'=> $return_number, #退款单号
            'return_goods_name'=> $return_goods_name, #退款/退货商品名称（可以为空）
            'order_amount'=> $order_amount, #订单总金额
            'third_party_trade_no'=> $third_party_trade_no #第三方订单号
        ];

        if (in_array($payment_channel['payment_channel_code'], static::$config['alipay'])) {
            $flag = $this->alipay($refund_order, $payment_config);
        } else if (in_array($payment_channel['payment_channel_code'], static::$config['wx'])) {
            $flag = $this->wx($refund_order, $payment_config);
        } else {
            throw new Exception('未找到支付配置');
        }
        return $flag;
    }

    public function getPaymentChannel($shop_id, $third_party_app_id)
    {
        $payment_channel_list = $this->paymentChannelModel->getByWhere([
            //'shop_id'=> $shop_id
        ]);

        $payment_channel_list = array_filter($payment_channel_list, function ($item) use ($third_party_app_id) {
            return $item['payment_channel_config']['appid'] == $third_party_app_id
                ? true
                : false;
        });

        $payment_channel = current($payment_channel_list);

        if ($payment_channel === false) {
            throw new Exception('未找到支付配置');
        }

        return $payment_channel;
    }

    /**
     * @param $order_ids
     * @return array
     * @throws Exception
     *
     * return array = [
     *     [order_id, status, msg
     * ]
     */
    public function refund($order_ids)
    {
        $payment_channel_list = $this->getPaymentChannelList();
        $order_list = $this->getOrder($order_ids); 
        $resultList = array();
        foreach ($order_list as $orderId=> $order) {
            #退款金额
            $order_amount = $order['trade_payment_amount'];
            $refund_amount = $order['trade_payment_amount'];
            $return_number = $order['union_order_id'];

            //购物卡支付金额\预存款支付金额 不走此方法
            if ($order['union_online_pay_amount'] == 0) {
                $resultList[] = [
                    'union_order_id'=> $order['union_order_id'],
                    'order_id'=> $order['inorder'],
                    'status'=> 200
                ];
                continue;
            }

            #notify返回信息
            $third_party_result = $order['notify_data'];


            try {
                if (isset($third_party_result['app_id'])) {
                    $third_party_app_id = $third_party_result['app_id'];
                } else if (isset($third_party_result['appid'])) {
                    $third_party_app_id = $third_party_result['appid'];
                } else {
                    throw new Exception('未找到appid');
                }

                $payment_channel_config = $payment_channel_list[$third_party_app_id];
                $payment_channel_code = $payment_channel_config['payment_channel_code'];
                //$shop_id = $payment_channel_config['shop_id'];

                #支付宝或者微信流水号
                if (isset($third_party_result['trade_no'])) {
                    #支付宝
                    $third_party_trade_no = $third_party_result['trade_no'];
                } else if (isset($third_party_result['transaction_id'])) {
                    #微信
                    $third_party_trade_no = $third_party_result['transaction_id'];
                }
                $refund_order = [
                    //'shop_id'=> $shop_id, #店铺id
                    'refund_amount'=> $refund_amount, #退款金额
                    'return_number'=> $return_number, #退款单号
                    //'return_goods_name'=> $return_goods_name, #退款/退货商品名称（可以为空）
                    'order_amount'=> $order_amount, #订单总金额
                    'third_party_trade_no'=> $third_party_trade_no #第三方订单号
                ];

                if (in_array($payment_channel_code, static::$config['alipay'])) {
                    $flag = $this->alipay($refund_order, $payment_channel_config);
                } else if (in_array($payment_channel_code, static::$config['wx'])) {
                    $flag = $this->wx($refund_order, $payment_channel_config);
                }
                $resultList[] = [
                    'union_order_id'=> $order['union_order_id'],
                    'order_id'=> $order['inorder'],
                    'status'=> $flag ? 200 : 250
                ];
            } catch (Exception $e) {
                $resultList[] = [
                    'union_order_id'=> $order['union_order_id'],
                    'order_id'=> $order['inorder'],
                    'status'=> 250,
                    'msg'=> $e->getMessage()
                ];
            }
        }
        return $resultList;
    }

    /**
     * 获取支付配置信息
     * @return array = [
     *      app_id=> array config
     * ]
     */
    private function getPaymentChannelList()
    {
        $paymentChannelModel = new Payment_ChannelModel;
        $paymentChannelList = $paymentChannelModel->getByWhere();  //全部取出（不合理，我知道）

        $res_list = [];
        foreach ($paymentChannelList as $paymentChannel) {
            //$shop_id = $paymentChannel['shop_id'];
            $payment_channel_config = $paymentChannel['payment_channel_config'];
            $payment_channel_code = $paymentChannel['payment_channel_code'];

            //$payment_channel_config['shop_id'] = $shop_id;
            $payment_channel_config['payment_channel_code'] = $payment_channel_code;

            $app_id = $payment_channel_config['appid'];
            $res_list[$app_id] = $payment_channel_config;
        }
        return $res_list;
    }

    /**
     * 获取订单信息
     */
    private function getOrder($order_ids)
    {
        $order_list = $this->orderModel->getUnionOrder($order_ids);

        if ( !$order_list ) {
            throw new Exception('获取订单失败');
        }
        return $order_list;
    }

    /**
     * 支付宝退款
     * @param $order array 订单信息
     * @param $paymentConfig 支付配置信息
     * @return boolean
     * @throws Exception
     *
     * 注意：
     * 现系统内支付宝有两套账号，退款时需找到对应账号进行退款
     *
     * out_trade_no 订单支付时传入的商户订单号,不能和trade_no同时为空
     * trade_no 支付宝交易号，和商户订单号不能同时为空
     * refund_amount 需要退款的金额，该金额不能大于订单金额,单位为元，支持两位小数
     * refund_reason 退款的原因说明
     * out_request_no 标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传
     * operator_id 商户的操作员编号
     * store_id 商户的门店编号
     * terminal_id 商户的终端编号
     *
     */
    private function alipay($order, $paymentConfig)
    {
        require_once './libraries/Api/alipayMobile/AopSdk.php';

        $aop = new AopClient();
        $aop->appId = $paymentConfig['appid'];
        $aop->rsaPrivateKey = $paymentConfig['rsaPrivateKey'];
        $aop->alipayrsaPublicKey = $paymentConfig['alipayPublicKey'];

        $request = new AlipayTradeRefundRequest();
        $bizContent = <<<EOF
            {
                "trade_no": "$order[third_party_trade_no]",
                "refund_amount": $order[refund_amount],
                "refund_reason": "正常退款",
                "out_request_no": "$order[return_number]",
                "operator_id": "OP001",
                "store_id": "NJ_S_001",
                "terminal_id": "NJ_T_001"
            }            
EOF;
        $request->setBizContent($bizContent);
        $result = $aop->execute($request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;

        if (!empty($resultCode) && $resultCode == 10000) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 微信退款
     * @param $order array 订单信息
     * @param $paymentConfig 支付配置信息
     * @throws WxPayException0
     * @return boolean
     */
    private function wx($order, $paymentConfig)
    {
        require_once LIB_PATH . '/Api/wx/lib/WxPay.Api.php';

        $this->defineWxConstants($paymentConfig);
        $inputObj = new WxPayRefund();
        $inputObj->SetOp_user_id(MCHID_DEF);
        $inputObj->SetTransaction_id($order['third_party_trade_no']); #微信生成的订单号，在支付通知中有返回
        $inputObj->SetOut_refund_no($order['return_number']); #商户系统内部的退款单号，商户系统内部唯一，只能是数字、大小写字母_-|*@ ，同一退款单号多次请求只退一笔。
        $inputObj->SetTotal_fee($order['order_amount']*100); #订单总金额，单位为分，只能为整数，详见支付金额
        $inputObj->SetRefund_fee($order['refund_amount']*100); #退款总金额，订单总金额，单位为分，只能为整数，详见支付金额

        $res = WxPayApi::refund($inputObj);

        if ($res['result_code'] != 'SUCCESS') {
            throw new Exception("err_code：$res[err_code]（$res[err_code_des]）");
        }
        return true;
    }

    /**
     * 定义微信支付常量
     * @param $app_id string 微信分配的公众账号ID（企业号corpid即为此appId）
     * @param $mch_id string
     * @param $key string 商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）
     * @param $app_secret string 公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置）
     * @param $payment_key string 支付代码名称 （商城内部）
     * @throws Exception
     */
    private function defineWxConstants($paymentConfig)
    {
        $app_id = $paymentConfig['appid'];
        $mch_id = $paymentConfig['mchid'];
        $key = $paymentConfig['key'];
        $app_secret = $paymentConfig['appsecret'];
        $payment_channel_code = $paymentConfig['payment_channel_code'];
        //$shop_id = $paymentConfig['shop_id'];

        !defined('APPID_DEF') && define('APPID_DEF', $app_id);
        !defined('MCHID_DEF') && define('MCHID_DEF', $mch_id);
        !defined('KEY_DEF') && define('KEY_DEF', $key);
        !defined('APPSECRET_DEF') && define('APPSECRET_DEF', $app_secret);

        $sslCertPath = APP_PATH . "/data/api/$payment_channel_code/cert/apiclient_cert.pem";
        $sslKeyPath = APP_PATH . "/data/api/$payment_channel_code/cert/apiclient_key.pem";
        !defined('SSLCERT_PATH_DEF') && define('SSLCERT_PATH', $sslCertPath);
        !defined('SSLKEY_PATH_DEF') && define('SSLKEY_PATH',$sslKeyPath);

        if (! is_file($sslCertPath)) {
            throw new Exception('apiclient_cert.pem文件不存在');
        }

        if (! is_file($sslKeyPath)) {
            throw new Exception('apiclient_key.pem文件不存在');
        }
    }
}
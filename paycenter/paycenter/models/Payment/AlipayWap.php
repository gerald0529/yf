<?php
if (!defined('ROOT_PATH')) {
    exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Payment_AlipayWap implements Payment_Interface
{
    public $notifyUrl;
    public $returnUrl;
    public $gatewayUrl = 'https://openapi.alipay.com/gateway.do';
    public $apiVersion = '1.0';
    public $signType = 'RSA2';
    public $postCharset = 'utf-8';
    public $format = 'json';
    public $appId = '2017112100078975';
    public $rsaPrivateKey = 'MIIEpAIBAAKCAQEA3/PTgKJtGw9JbfADhRgrHMPz3ocCdM485X8ZWq50bEA4gZUXhcSyZB6J36jEsZ7FRQzjdlwx/eiVtqrYVJ6ZnonwBVD3RXLPAq/32ugybCmq3m6A1BxePZjD6AdPXgcQ3L/YFZ3fPU5SDlqaZFgRcX96l/cPo1CYMn3nxteRf/0k7V4PcmDXnk9xOt9JaxFMrqeFT5sdaGp9BBY/SgJ41b0/vYAa5x2ChSWEp6UAGXNWzxt++hWjO3lBYGLpCFYoyzXbZdBuadUOTuY/8Qu5io+x11DGja4V9UoIZZQPH6P1r3zqyBVVCu9+9CfN48fWmiqJbHsGAfkfFp7WQwYj2wIDAQABAoIBAQCOlRE6W681RDVO7jl/elSwer2AFtrkfQ3eW15MErgC15xiPAb+3q1o+txy7mNUZq5X+Q20pJwbeQIgi4Mx1MwfWNjcuaDEsYTExD38PEsl/B1Sgm45HVqOwv0vc7//MGZ29RlhIeMoh/3ML7kOW9e3OB9YMy7cnopX0ztGqKZ1IgfmC5u0fckt5va1Pvi+8XS+00jMDkC9suAd6M4itV3GU2UmGMPa1PaxMdGt17Urga1EIPdXp0nDHSevhCgbFm/UwvN4Z7kLWsFxNXCI3wqmB9LT17/pwnJMeH3GUoQiln3+X6H63tCiXMMyz2UyxRkk//yffAR/+HEV+MT99R5BAoGBAP8/5E6lOQ3i889M2DZ6QEQWnlopNbGJupC+FVGYri3+aG+RBObOM9WqM4BK5PNR+fW+l4wix/kgHvmDjWvzvEA+OkhP8IUt+t4Sivwnwt9t7EwiP0oDVaBJLzEpfa53/uyDwKP2OuAU/oa/xWH4azEaWPrmM6SWBY8bKaDZlCJ7AoGBAOCcYRWMmHqni7r8S1+Y1vM0QWVRVm172RxwD/XAYFhwaKPRXvZm8Buo6HDAppTtxCP5tWvxsBrGah+if9pUwYAuFnCp4lTwwY/nyvlJx/TDfnaGyQ2m6ntozouhExFZEIsFgHqQDWVmdMXr4oKuRRGRW+HCwGuG2wU7ydvvS3YhAoGAeXkZmQfuaBpq92vVtc9mSEEPaU8VW4F1RS8BDE0CD6d0Yiv8zi6x4mxWiCacYOPRdk8W5j0jN/8+XnZp1kcvfs9eg01v5KGmMwtWE3yEtDom63Cc+AcwN9C8YcQiKOa4biyhgCZNjJjRLKWVNPO5Z6vnTrhBOYGf8aP2orMJWYMCgYBH50fdEikt+rzsmx+19sO5D51vxd4ZJnCWffld/rvZFAMrjjcMQl/TOvtOPR4WxxbnPWUqrTBnIeWPQwIS7tcTJa3hW0EtV/VfECEWNNxiKsMtRnDOggTGhQK6CFKGVzDIkHZUxhDDyUzQn3bfxtItkY8McsAOrBkpT76LPcu2gQKBgQDBLIVdf75Q+q1DswLs6m3zbIDv+QTk9qhpC/El7jtzKfU4j6iUNOdmotAkrI8c61YcpjNA6usY5e2KAhd041Gy15KzVssdEeor1NCgc108AilhrLA6X9q3Sgf57eUVDfgDjijQtWdS6r6lnzKqy2o/aYNwxLXjtyF4L6MpUWK64g==';
    public $alipayPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0aKseIdlhJawdBUeArvl7hAZ4rqfvtt4mElzHA8eE9zsQ1aZsfKdhggbN41KtOlAcbVmbYhZNWDqTQcGdkqEMhJCtd+Emai2Gyv6/q8zeiJ8vEVRwOXpTRGpBEBmzqS2rM8PL718SdfFEtYEAufqMlu+PeWQ65t0sYUrzheBOkgNWae372A9ZLC7/rlObxbK9afWQv28OFLkDomqVR1qvMMRe2lQyHiGfd9A80fXO7+hjZdlGGIN2LrceuTqYHloy++HGOA5tUQpcyTPLniibfcp3nrMN9jmvD1sZOdqrZRQN4W9GGW7JvUDqS4sB+WQubpUaXQBacGpPbQZVlcgnwIDAQAB';

    public function __construct($payment_row = array(), $order_row = array())
    {
        $this->appId = $payment_row['appid'];
        $this->rsaPrivateKey = $payment_row['rsaPrivateKey'];
        $this->alipayPublicKey = $payment_row['alipayrsaPublicKey'];
        $this->notifyUrl = Yf_Registry::get('base_url').'/paycenter/api/payment/alipay/notify_url.php';
        $this->returnUrl = $_GET['new_return_url'] ?: Yf_Registry::get('base_url').'/paycenter/api/payment/alipay/return_url.php';
    }

    public function pay($order_row)
    {
        // TODO: Implement pay() method.

        require_once ROOT_PATH.'/libraries/Api/alipayMobile/AopSdk.php';
        //构造参数
        $aop = new AopClient ();
        $aop->gatewayUrl = $this->gatewayUrl;
        $aop->apiVersion = $this->apiVersion;
        $aop->signType = $this->signType;
        $aop->postCharset= $this->postCharset;
        $aop->format= $this->format;

        $aop->appId = $this->appId; //请填写APPID
        $aop->rsaPrivateKey = $this->rsaPrivateKey; //请填写商户私钥
        $request = new AlipayTradeWapPayRequest();
        $request->setReturnUrl(Yf_Registry::get('base_url').'/paycenter/api/payment/alipay/return_url.php'); //请填写您的页面同步跳转地址
        $request->setNotifyUrl(Yf_Registry::get('base_url').'/paycenter/api/payment/alipay/notify_url.php'); //请填写您的异步通知地址

        $requestJson = <<<EOF
        {
            "product_code":"FAST_INSTANT_TRADE_PAY",
            "out_trade_no":"$order_row[union_order_id]",
            "subject":"$order_row[trade_title]",
            "total_amount":"$order_row[union_online_pay_amount]",
            "body":"$order_row[trade_title]"
        }
EOF;
        $request->setBizContent($requestJson);

        //请求
        $result = $aop->pageExecute($request);

        //输出
        echo $result;
    }

    public function getPayResult($param)
    {
        // TODO: Implement getPayResult() method.
    }

    public function verifyNotify()
    {
        // TODO: Implement verifyNotify() method.
    }

    public function verifyReturn()
    {
        // TODO: Implement verifyReturn() method.
    }

    public function sign($parameter)
    {
        // TODO: Implement sign() method.
    }

    public function request()
    {
        // TODO: Implement request() method.
    }

    /**
     * 得到同步返回数据
     *
     * @access public
     */
    public function getReturnData($Consume_TradeModel = null)
    {
        $notify_param = $_REQUEST;
        if ($Consume_TradeModel)
        {
            $notify_row = array();
            $Union_OrderModel = new Union_OrderModel();

            $order_id = $notify_param['out_trade_no'];
            $notify_row = $Union_OrderModel->getOne($order_id);
            $notify_row['order_id'] = $notify_param['out_trade_no'];

            //支付宝返回数据
            $notify_row['deposit_total_fee']           = $notify_param['total_fee'];
            $notify_row['deposit_price']        = isset($notify_param['price']) ? $notify_param['price'] : '0';
            $notify_row['trade_no'] = $notify_param['trade_no'];
        }
        else
        {
            //插入充值记录, 如果同步数据没有,从订单数据中读取过来
            $notify_row = array();

            $notify_row['order_id'] = $notify_param['out_trade_no'];
            $notify_row['deposit_trade_no'] = $notify_param['trade_no'];
            $notify_row['deposit_subject']      = $notify_param['subject'];
            $notify_row['deposit_body']          = isset($notify_param['body']) ? $notify_param['body'] : '';
            //$notify_row['deposit_buyer_email']  = $notify_param['buyer_email'];
            $notify_row['deposit_gmt_create']  = isset($notify_param['gmt_create']) ? $notify_param['gmt_create'] : '0000-00-00 00:00:00';
            $notify_row['deposit_notify_type']  = $notify_param['notify_type'];
            $notify_row['deposit_quantity']  = isset($notify_param['quantity']) ? $notify_param['quantity'] : '0';
            $notify_row['deposit_notify_time']  = $notify_param['notify_time'];
            $notify_row['deposit_seller_id']  = $notify_param['seller_id'];
            $notify_row['deposit_trade_status']  = $notify_param['trade_status'];
            $notify_row['deposit_is_total_fee_adjust']  = isset($notify_param['is_total_fee_adjust']) ? $notify_param['is_total_fee_adjust'] : 0;
            $notify_row['deposit_total_fee']  = $notify_param['total_fee'];
            $notify_row['deposit_gmt_payment']  = isset($notify_param['gmt_payment']) ? $notify_param['gmt_payment'] : '0000-00-00 00:00:00';
            //$notify_row['deposit_seller_email']  = $notify_param['seller_email'];
            $notify_row['deposit_gmt_close']  = isset($notify_param['gmt_close']) ? $notify_param['gmt_close'] : '0000-00-00 00:00:00';
            $notify_row['deposit_price']  =     isset($notify_param['price']) ? $notify_param['price'] : '0';
            $notify_row['deposit_buyer_id']  = $notify_param['buyer_id'];
            $notify_row['deposit_notify_id']  = $notify_param['notify_id'];
            $notify_row['deposit_use_coupon']  = isset($notify_param['use_coupon']) ? $notify_param['use_coupon'] : '';
            $notify_row['deposit_payment_type'] = $notify_param['payment_type'];

            $notify_row['deposit_extra_param']     = isset($notify_param['extra_param']) ? $notify_param['extra_param'] : '';
            $notify_row['deposit_service']     = isset($notify_param['exterface']) ? $notify_param['exterface'] : '';
            $notify_row['deposit_sign_type']    = $_REQUEST['sign_type'];
            $notify_row['deposit_sign']         = $_REQUEST['sign'];
        }
        $notify_row['payment_channel_id']   = Payment_ChannelModel::ALIPAY;
        return $notify_row;
    }

    public function getConfig()
    {
        return array(
            //应用ID,您的APPID。
            'app_id' => $this->appId,

            //商户私钥
            'merchant_private_key' => $this->rsaPrivateKey,

            //异步通知地址
            'notify_url' => $this->notifyUrl,

            //同步跳转
            'return_url' => $this->returnUrl,

            //编码格式
            'charset' => $this->postCharset,

            //签名方式
            'sign_type'=> $this->signType,

            //支付宝网关
            'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

            //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
            'alipay_public_key' => $this->alipayPublicKey,
        );
    }
}
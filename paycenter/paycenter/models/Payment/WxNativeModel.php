<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

require_once LIB_PATH . '/Api/wx/lib/WxPay.Api.php';
require_once LIB_PATH . '/Api/wx/lib/WxPay.Notify.php';

/**
 * @author     Xinze <xinze@live.cn>
 */
class Payment_WxNativeModel extends WxPayNotify implements Payment_Interface {
    /**
     * 支付接口标识
     *
     * @var string
     */
    private $code = 'wx_native';
    
    /**
     * 支付接口配置信息
     *
     * @var array
     */
    public $payment;
    
    /**
     * 订单信息
     *
     * @var array
     */
    public $order;
    
    /**
     * 发送至支付宝的参数
     *
     * @var array
     */
    private $parameter;
    
    /**
     * 订单类型 buy商品购买,   deposit预存款充值
     * @var unknown
     */
    private $order_type;
    
    
    /**
     * 通知结果
     * @var unknown
     */
    private $verifyResult = false;
    private $verifyData   = array();
    
    private $returnResult = false;
    private $returnData   = array();
    
    /**
     * Constructor
     *
     * @param  array $payment_row 支付平台信息
     * @param  array $order_row   订单信息
     *
     * @access public
     */
    public function __construct($payment_row = array(), $order_row = array())
    {
        $this->payment = $payment_row;
        $this->order   = $order_row;
        
        
        /**
         * TODO: 修改这里配置为您自己申请的商户信息
         * 微信公众号信息配置
         *
         * APPID：绑定支付的APPID（必须配置，开户邮件中可查看）
         *
         * MCHID：商户号（必须配置，开户邮件中可查看）
         *
         * KEY：商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）
         * 设置地址：https://pay.weixin.qq.com/index.php/account/api_cert
         *
         * APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置），
         * 获取地址：https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=2005451881&lang=zh_CN
         * @var string
         */
        $this->payment['appid']     = $payment_row['appid'];
        $this->payment['mchid']     = $payment_row['mchid'];
        $this->payment['key']       = $payment_row['key'];
        $this->payment['appsecret'] = $payment_row['appsecret'];
        
        //=======【证书路径设置】=====================================
        /**
         * TODO：设置商户证书路径
         * 证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载，
         * API证书下载地址：https://pay.weixin.qq.com/index.php/account/api_cert，下载之前需要安装商户操作证书）
         * @var path
         */
        $this->payment['sslcert_path'] = LIB_PATH . '/Api/wx/cert/apiclient_cert.pem';
        $this->payment['sslkey_path']  = LIB_PATH . '/Api/wx/cert/apiclient_key.pem';
        
        
        //=======【curl代理设置】===================================
        /**
         * TODO：这里设置代理机器，只有需要代理的时候才设置，不需要代理，请设置为0.0.0.0和0
         * 本例程通过curl使用HTTP POST方法，此处可修改代理服务器，
         * 默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
         * @var unknown_type
         */
        
        $this->payment['curl_proxy_host'] = "0.0.0.0";//"10.152.18.220";
        $this->payment['curl_proxy_port'] = 0;//8080;
        
        //=======【上报信息配置】===================================
        /**
         * TODO：接口调用上报等级，默认紧错误上报（注意：上报超时间为【1s】，上报无论成败【永不抛出异常】，
         * 不会影响接口调用流程），开启上报之后，方便微信监控请求调用的质量，建议至少
         * 开启错误上报。
         * 上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
         * @var int
         */
        $this->payment['report_levenl'] = 1;
        
        
        //↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
        // 支付类型 ，无需修改
        $this->payment['payment_type'] = "NATIVE";
        
        //服务器异步通知页面路径
        $this->payment['notify_url'] = !empty($this->payment['notify_url']) ? $this->payment['notify_url'] : Yf_Registry::get('base_url') . "/paycenter/api/payment/wx/notify_url.php";
        //需http://格式的完整路径，不允许加?id=123这类自定义参数
        
        //页面跳转同步通知页面路径
        $this->payment['return_url'] = Yf_Registry::get('base_url') . "/paycenter/api/payment/wx/return_url.php";
        //需http://格式的完整路径，不允许加?id=123这类自定义参数
        
        
        //检测订单状态url
        $this->payment['check_url'] = Yf_Registry::get('base_url') . "/paycenter/api/payment/wx/check_url.php";
        
        //操作中断返回地址
        $this->payment['merchant_url'] = Yf_Registry::get('base_url') . "/paycenter/api/payment/wx/merchant_url.php";
    }

    /**
     * @param $key
     * @param $url
     * @param $appId
     * @param $orderId
     * @return bool
     *
     * 检查是否为自提订单
     */
    private function isChainOrder($key, $url, $appId,  $orderId)
    {
        $request = [
            'app_id'=> $appId,
            'orderId'=> $orderId
        ];
        $result = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Trade_Order&met=isChainOrder&typ=json', $url), $request);
        if ($result['status'] == 200) {
            return $result['data']['flag']
                ? true
                : false;
        }
        return false; //请求失败，默认false
    }
    
    /**
     * 支付
     *
     * @access public
     */
    public function pay($order_row, $app = false)
    {
        if ($order_row)
        {
            $this->order = $order_row;
        }
        
        //1 == order_state_id  待付款状态
        if (1 != $this->order['order_state_id'])
        {
            throw new Exception('订单状态不为待付款状态');
        }
        
        //商户订单号
        $out_trade_no = $this->order['union_order_id'];
        //商户网站订单系统中唯一订单号，必填
        
        //订单名称
        $subject      = $this->order['trade_title'];
        $detail       = isset($this->order['trade_desc']) ? $this->order['trade_desc'] : '';
        $trade_remark = isset($this->order['trade_remark']) ? $this->order['trade_remark'] : '';
        //必填
        
        //付款金额
        $total_fee = $this->order['union_online_pay_amount'];
        
        $quantity = isset($this->order['quantity']) ? $this->order['quantity'] : 1;
        
        $extra_common_param = '';
        
        $time = time();
        
        include_once LIB_PATH . '/Api/wx/WxPay.NativePay.php';
        
        $notify = new NativePay();
        
        $input = new WxPayUnifiedOrder();
        $input->SetBody($subject);
        $input->SetAttach($extra_common_param);
        $input->SetOut_trade_no($out_trade_no);
        $input->SetTotal_fee($total_fee * 100);
        $input->SetTime_start(date("YmdHis"), $time);
        $input->SetTime_expire(date("YmdHis", $time + 60 * 10));
        $input->SetGoods_tag($trade_remark);
        $input->SetNotify_url($this->payment['notify_url']);

        if($_GET['trade_type']){
             $input->SetTrade_type('APP');
        }else{
             $input->SetTrade_type("NATIVE");
        }
        
        $input->SetProduct_id($out_trade_no);

        $result = $notify->GetPayUrl($input);

        
        if ($result && 'SUCCESS' == $result['result_code'])
        {
            
        }
        else
        {
            Yf_Log::log('GetPayUrl RES:=' . encode_json($result), Yf_Log::INFO, 'pay_wx_info');
            throw new Exception(encode_json($result)."1111111".MCHID_DEF);
        }

        if ($app === true)
        {
            return $result;
        }

        $code_url = $result["code_url"];
        $url_data = urlencode($code_url);
        
        $check_url = $this->payment['check_url'];
        $jump_url  = $this->payment['return_url'];
        
        $app_id = $this->order['app_id'];
        
        //查找回调地址
        $User_AppModel = new User_AppModel();
        $user_app      = $User_AppModel->getOne($app_id);
        if($app_id == Yf_Registry::get('paycenter_app_id')){
            $return_url    = $user_app['app_url'];
        }else{
            // if ($this->isChainOrder($user_app['app_key'], $user_app['app_url'], $user_app['app_id'], $this->order['inorder'])) {
            //     $return_url = $user_app['app_url'].'?ctl=Buyer_Order&met=chain';
            // } else {
            //     $return_url = $user_app['app_url'].'?ctl=Buyer_Order&met=physical';
            // }
            $return_url = Yf_Registry::get('paycenter_api_url') . "?ctl=Info&met=after_pay&order_id=" . $orderId . "&return=" . $user_app['app_url'];
        }
        
        
        $res_row              = array();
        $res_row['url_data']  = $url_data;
        $res_row['check_url'] = $check_url;
        $res_row['jump_url']  = $jump_url;
        $base_url             = Yf_Registry::get('base_url');
        print <<<EOT
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>微信支付</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" /> 
        <script type="text/javascript" src="{$base_url}/paycenter/static/default/js/jquery-1.9.1.js"></script>
    </head>
    <link rel="stylesheet" type="text/css"  href="../../../paycenter/static/default/css/WxNative.css"/>
    <body>
        <!--导航-->
        <header>
            <div class="clearfix Navigation">
                <span class=" pay-li-cashier">
                    <span class="fz-col">远丰商城 </span>
                     收银台
                </span>
                <span class=" pay-li-nickname">{$userInfo['user_nickname']}</span>
                <span class=" pay-li-nickname ml14"><a href="$login_out_url">退出</a></span>
                <span class=" pay-li-nickname ml14">|</span>
                <span class=" pay-li-nickname ml14"><a href="$shop_url">返回商城</a></span>
                <ul class="clearfix pay-order-number">
                    <li class="fl ">
                        订单提交成功，请尽快付款！订单号：
                        <span>{$this->order[inorder]}</span>
                    </li>
                    <li class="fr">应付金额<span class="jiage">{$this->order[trade_payment_amount]}</span>元</li>
                </ul>

            </div>
        </header>
        <!--支付内容-->
        <div>
            <div class="wx-payment clearfix">
                <div class="fl wx-payment-left">
                    <p>微信支付</p>
                    <img alt="模式二扫码支付" src="{$base_url}/paycenter/api/qrcode.php?data={$url_data}" width="290" height="290">
                    <div class="wx-payment-left-div1">
                        <ul class="clearfix">
                            <li class="pay-icon fl"></li>
                            <li class="pay-text fl">请使用微信扫描<br>扫描二维码支付</li>
                        </ul>
                    </div>
                    <div class="wx-payment-left-div2">
                        <a href="$payment_url"><img src="paycenter/models/Payment/zuojiantou.png" width="9" height="16">选择其他支付方式</a>
                    </div>
                </div>
                <div class="fl wx-payment-right">

                </div>
            </div>
        </div>
<script>
        $(function(){
           setInterval(function(){check()}, 5000);  //5秒查询一次支付是否成功
        })
        function check(){
            var url = "{$check_url}";
            var out_trade_no = '$out_trade_no';
            var param = {'code':out_trade_no};
            $.post(url, param, function(data){
                //data = JSON.parse(data);
                if(data.status == "200"){
                    //alert(JSON.stringify(data));
                    alert("订单支付成功,即将跳转...");
                    window.location.href = "{$return_url}";
                }else{
                    console.log(data);
                }
            },'json');
        }
    </script>
</body>
</html>
EOT;
        die();
    }
    
    /**
     *
     * 取得订单支付状态，成功或失败
     *
     * @param array $param
     *
     * @return array
     */
    public function getPayResult($param)
    {
        return $param['trade_status'] == 'TRADE_SUCCESS';
    }
    
    /**
     * 通知验证
     *
     * @access public
     */
    public function verifyNotify()
    {
        $this->Handle(false);
        
        return $this->verifyResult;
    }
    
    /**
     * 通知验证
     *
     * @access public
     */
    public function verifyReturn()
    {
        $this->Handle(false);
        
        return $this->returnResult;
    }
    
    public function sign($parameter)
    {
        $sign_str = '';
        
        $sign_str = $this->getSignature($parameter, $parameter['key']);
        
        return $sign_str;
    }
    
    public function getSignature($parameter, $cp_key = null)
    {
    }
    
    /**
     * 制作支付接口的请求地址 发送请求
     *
     * @access public
     */
    public function request()
    {
    }
    
    /**
     * 得到异步返回数据
     *
     * @access public
     */
    public function getNotifyData()
    {
        $notify_row = $this->getReturnData();
        
        $notify_row['deposit_async'] = 1;
        
        return $notify_row;
    }
    
    /**
     * 得到同步返回数据
     *
     * @access public
     */
    public function getReturnData($Consume_TradeModel = null)
    {
        $notify_param = $this->verifyData;
        
        if (!$Consume_TradeModel)
        {
            $notify_row       = array();
            $Union_OrderModel = new Union_OrderModel();
            
            $order_id               = $notify_param['out_trade_no'];
            $notify_row             = $Union_OrderModel->getOne($order_id);
            Yf_Log::log('Union_Order:' . encode_json($notify_row), Yf_Log::INFO, 'pay_wxnative_notify_data');
            Yf_Log::log('out_trade_no:' .$order_id, Yf_Log::INFO, 'pay_wxnative_notify_data');
            $notify_row['order_id'] = $notify_param['out_trade_no'];

            //微信返回数据
            $notify_row['deposit_total_fee']           = $notify_param['total_fee'] / 100;         
            $notify_row['deposit_price']        = isset($notify_param['cash_fee']) ? $notify_param['cash_fee'] / 100 : '0';
            $notify_row['trade_no'] = $notify_param['transaction_id'];
        }
        else
        {
            //插入充值记录, 如果同步数据没有,从订单数据中读取过来
            $notify_row = array();
            
            $notify_row['order_id']         = $notify_param['out_trade_no'];
            $notify_row['deposit_trade_no'] = $notify_param['transaction_id'];
            
            $notify_row['deposit_quantity']    = isset($notify_param['quantity']) ? $notify_param['quantity'] : '0';
            $notify_row['deposit_notify_time'] = date('Y-m-d H:i:s');
            $notify_row['deposit_seller_id']   = $notify_param['mch_id'];
            
            $notify_row['deposit_is_total_fee_adjust'] = isset($notify_param['is_total_fee_adjust']) ? $notify_param['is_total_fee_adjust'] : 0;
            $notify_row['deposit_total_fee']           = $notify_param['total_fee'] / 100;
            
            $notify_row['deposit_price']        = isset($notify_param['cash_fee']) ? $notify_param['cash_fee'] / 100 : '0';
            $notify_row['deposit_buyer_id']     = $notify_param['openid'];
            $notify_row['deposit_payment_type'] = $notify_param['bank_type'] . '|' . $notify_param['fee_type'];
            
            $notify_row['deposit_service'] = isset($notify_param['trade_type']) ? $notify_param['trade_type'] : '';
            $notify_row['deposit_sign']    = isset($notify_param['sign']) ? $notify_param['sign'] : '';
            
            $notify_row['deposit_extra_param'] = encode_json($notify_param);
        }
        
        //根据$notify_param['payment_type']  || $_REQUEST['service']可以判断其它类型
        $notify_row['payment_channel_id'] = Payment_ChannelModel::WECHAT_PAY;
        
        return $notify_row;
    }
    
    
    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        
//        Yf_Log::log("query:" . json_encode($result), Yf_Log::INFO, 'pay_wxnative_notify');
        
        if (array_key_exists("return_code", $result) && array_key_exists("result_code",
                $result) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS"
        )
        {
            return true;
        }
        
        return false;
    }
    
    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
        
        if (!array_key_exists("transaction_id", $data))
        {
            $msg = "输入参数不正确";
            
            return false;
        }
        //查询订单，判断订单真实性
        if (!$this->Queryorder($data["transaction_id"]))
        {
            $msg = "订单查询失败";
            
            return false;
        }
        
        $this->verifyResult = true;
        $this->verifyData   = $data;
        
        
        Yf_Log::log('notify_data:' . encode_json($data), Yf_Log::INFO, 'pay_wxnative_notify_data');
        
        return true;
    }

    /**
     * @param $data
     * @throws Exception
     * @return array
     * 用户充值
     */
    public function rechargeMoneyByApp($data)
    {
        include_once LIB_PATH . '/Api/wx/WxPay.NativePay.php';

        $notify = new NativePay();

        $time = time();
        $body = empty($data['body'])
            ? "用户充值"
            : $data['body'];

        $attach = empty($data['body'])
            ? "App支付"
            : $data['body'];

        $tag = empty($data['tag'])
            ? ""
            : $data['tag'];

        $out_trade_no = $data['out_trade_no'];
        $total_fee = $data['total_fee'];
        $notify_url = Yf_Registry::get('base_url') . "/paycenter/api/payment/wx/notify_url.php"; //notify 和购物notify不一样

        $input = new WxPayUnifiedOrder();
        $input->SetBody($body);
        $input->SetAttach($attach);
        $input->SetOut_trade_no($out_trade_no);
        $input->SetTotal_fee($total_fee * 100);
        $input->SetTime_start(date("YmdHis"), $time);
        $input->SetTime_expire(date("YmdHis", $time + 60 * 10));
        $input->SetGoods_tag($tag);
        $input->SetNotify_url($notify_url);
        $input->SetTrade_type('APP');
        $input->SetProduct_id($out_trade_no);

        $result = $notify->GetPayUrl($input);


        if ($result && 'SUCCESS' == $result['result_code']) {
            return $result;
        } else {
            Yf_Log::log('GetPayUrl RES:=' . encode_json($result), Yf_Log::INFO, 'recharge_money_wx_info');
            throw new Exception(encode_json($result)."1111111".MCHID_DEF);
        }
    }
}

?>


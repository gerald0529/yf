<?php
if (!defined('ROOT_PATH')) {
    exit('No Permission');
}


require_once LIB_PATH . '/Api/wxapp/WxPay.Api.php';

class Payment_Wxapp  extends Yf_Model{

    public $openid;
    public $body;
    public $total_fee;
    public $uorder_id;
    function __construct($openid, $body,$total_fee,$uorder_id) {

        $this->openid = $openid;
        $this->body = $body;
        $this->total_fee = $total_fee;
        $this->uorder_id = $uorder_id;
    }

    public function pay() {

        //         初始化值对象
        $input = new WxPayUnifiedOrder();
        //         文档提及的参数规范：商家名称-销售商品类目
        $input->SetBody($this->body);
        //         订单号应该是由小程序端传给服务端的，在用户下单时即生成，demo中取值是一个生成的时间戳
        $input->SetOut_trade_no(time().'');
        //         费用应该是由小程序端传给服务端的，在用户下单时告知服务端应付金额，demo中取值是1，即1分钱
        $input->SetTotal_fee($this->total_fee );
        $input->SetNotify_url( Yf_Registry::get('base_url') . "/paycenter/api/payment/wxapp/notify_url.php");//需要自己写的notify.php
        $input->SetTrade_type("JSAPI");
        //         由小程序端传给后端或者后端自己获取，写自己获取到的，
        $input->SetOpenid( $this->openid);
        //$input->SetOpenid($this->getSession()->openid);
        //         向微信统一下单，并返回order，它是一个array数组

        $order = WxPayApi::unifiedOrder($input);

        //         json化返回给小程序端
        header("Content-Type: application/json");
        echo $this->getJsApiParameters($order);exit;
    }

    private function getJsApiParameters($UnifiedOrderResult)
    {    //判断是否统一下单返回了prepay_id
        if(!array_key_exists("appid", $UnifiedOrderResult)
            || !array_key_exists("prepay_id", $UnifiedOrderResult)
            || $UnifiedOrderResult['prepay_id'] == "")
        {
            throw new WxPayException("参数错误");
        }
        $jsapi = new WxPayJsApiPay();
        $jsapi->SetAppid($UnifiedOrderResult["appid"]);
        $timeStamp = time();
        $jsapi->SetTimeStamp($this->uorder_id);
        $jsapi->SetNonceStr(WxPayApi::getNonceStr());
        $jsapi->SetPackage("prepay_id=" . $UnifiedOrderResult['prepay_id']);
        $jsapi->SetSignType("MD5");
        $jsapi->SetPaySign($jsapi->MakeSign());
        $parameters = json_encode($jsapi->GetValues());
        return $parameters;
    }

}

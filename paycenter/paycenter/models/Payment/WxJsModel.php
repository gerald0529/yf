<?php if ( ! defined('ROOT_PATH'))
{
    exit('No Permission');
}

require_once LIB_PATH . '/Api/wx/lib/WxPay.Api.php';
require_once LIB_PATH . '/Api/wx/lib/WxPay.Notify.php';

/**
 * @author     Xinze <xinze@live.cn>
 */
class Payment_WxJsModel extends Payment_WxNativeModel {
    /**
     * 支付
     *
     * @access public
     */
    public function pay($order_row)
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

        include_once LIB_PATH . '/Api/wx/WxPay.JsApiPay.php';
        //①、获取用户openid

        $tools = new JsApiPay();
        if ($this->order['trade_type'] != "MWEB") {
            $openId = $tools->GetOpenid();
        }

        //②、统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody($subject);
        $input->SetAttach($extra_common_param);
        $input->SetOut_trade_no($out_trade_no);
        $input->SetTotal_fee($total_fee * 100);
        $input->SetTime_start(date("YmdHis"), $time);
        $input->SetTime_expire(date("YmdHis", $time + 60 * 10));
        $input->SetGoods_tag($trade_remark);
        $input->SetNotify_url($this->payment['notify_url']);
        if ($this->order['trade_type'] == "MWEB") {
            $input->SetTrade_type("MWEB");
        } else {
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($openId);
        }
        $result = WxPayApi::unifiedOrder($input);
        if ($result && 'SUCCESS' == $result['result_code'])
        {
            if ($result['mweb_url'] && $result['trade_type'] == "MWEB") {
                $rediret_url = urlencode(Yf_Registry::get('url')."?ctl=Info&met=h5_pay&trade_id=".$this->order['trade_id']);

                $mweb_url = $result['mweb_url'] . "&redirect_url=" . $rediret_url;
                header("Location:" . $mweb_url);
            }
        }
        else
        {
            Yf_Log::log('GetPayUrl RES:=' . encode_json($result), Yf_Log::INFO, 'pay_wxjs_info');
            throw new Exception(encode_json($result));
        }
        $jsApiParameters = $tools->GetJsApiParameters($result);
        
        //获取共享收货地址js函数参数
        $editAddress = $tools->GetEditAddressParameters();
        
        
        //③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
        /**
         * 注意：
         * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
         * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
         * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
         */
        
        
        if ($jsApiParameters)
        {
            
        }
        else
        {
            Yf_Log::log('GetPayUrl RES:=' . encode_json($jsApiParameters), Yf_Log::INFO, 'pay_wxjs_info');
            throw new Exception(encode_json($jsApiParameters));
        }
    
        $app_id = $this->order['app_id'];
    
        //查找回调地址
        $User_AppModel = new User_AppModel();
        $user_app      = $User_AppModel->getOne($app_id);
        // $return_url    = $user_app['app_url'];
        $return_url = Yf_Registry::get('paycenter_api_url') . "?ctl=Info&met=after_pay&order_id=" . $this->order['inorder'] . "&return=" . $user_app['app_url'];
        

        print <<<EOT

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <title>微信支付</title>
    <script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			{$jsApiParameters},
			function(res){
				WeixinJSBridge.log(res.err_msg);
				
				
				if (res.err_msg == "get_brand_wcpay_request:ok")
				{
                    //alert('支付成功');
				    window.location.href = "{$return_url}";
				}
				else
				{
                    if (res.err_msg == "get_brand_wcpay_request:cancel")
                    {
				            //alert('取消支付');
                        history.back(-1);
                    }
				    else
				    {
				        alert(res.err_code+res.err_desc+res.err_msg);
                        history.back(-1);
				    }
				}
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
    window.onload = function(){
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', callpay, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', callpay); 
		        document.attachEvent('onWeixinJSBridgeReady', callpay);
		    }
		}else{
			callpay();
		}
	};
	
	</script>
</head>
<body>
<!--<button style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="callpay()" >立即支付</button>-->
</body>
</html>
EOT;
        die();
    }
}

?>



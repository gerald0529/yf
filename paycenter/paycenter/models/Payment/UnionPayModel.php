<?php if (!defined('ROOT_PATH')){exit('No Permission');}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Payment_UnionPayModel implements Payment_Interface
{

	public $gateway_url = ''; //网关地址
	private $verify_url = ''; //消息验证地址
	private $payment;
	private $order;
	private $parameter;
	private $order_type;


	/**
	 * Constructor
	 *
	 * @param  array $payment_row 支付平台信息
	 * @param  array $order_row 订单信息
	 * @access public
	 */
	public function __construct($payment_row = array(), $order_row = array())
	{
		$this->payment = $payment_row;
		$this->order   = $order_row;

		if(!defined('unionpay_environment') && !defined('SDK_SIGN_CERT_PWD')){
				define('SDK_SIGN_CERT_PWD',$payment_row['unionpay_key']);  
		}  
		$this->payment['parter'] = $payment_row['unionpay_partner'];  //商户号
		$this->payment['return_url'] = Yf_Registry::get('base_url') . "/paycenter/api/payment/unionpay/return_url.php"; //返回URL
		$this->payment['notify_url'] = Yf_Registry::get('base_url') . "/paycenter/api/payment/unionpay/return_url.php"; //通知URL
	}

	/**
	 * 支付
	 *
	 * @access public
	 */
	public function pay($order_row)
	{
		//BEGIN
		if ($order_row)
		{
			$this->order = $order_row;
		}

		//1 == order_state_id  待付款状态
		if (1 != $this->order['order_state_id'])
		{
			throw new Exception('订单状态不为待付款状态');
		}
		
		include_once LIB_PATH . '/Api/unionpay/lib/common.php';
        $time = date("YmdHis");
        global $log;
	    //商户订单号
		$out_trade_no = $this->order['union_order_id'];
		$amount = $this->order['union_online_pay_amount']*100; //订单金额
        $params = array(
            //以下信息非特殊情况不需要改动
            'version' => '5.0.0',                 //版本号
            'encoding' => 'utf-8',				  //编码方式
            'certId' => getSignCertId (),	      //证书ID  
            'txnType' => '01',				      //交易类型
						'txnSubType' => '01',				  //交易子类
            'bizType' => '000201',				  //业务类型 000201  000202
            'frontUrl' =>  $this->payment['return_url'],  //前台通知地址
            'backUrl' =>  $this->payment['notify_url'],	  //后台通知地址
            'signMethod' => '01',	              //签名方法
            'channelType' => '07',	              //渠道类型，07-PC，08-手机
            'accessType' => '0',		          //接入类型
            'currencyCode' => '156',	          //交易币种，境内商户固定156

            //TODO 以下信息需要填写
            'merId' => $this->payment['parter'],		//商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
            'orderId' => $out_trade_no,	//商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
            'txnTime' => $time,	//订单发送时间，格式为YYYYMMDDhhmmss，取北京时间，此处默认取demo演示页面传递的参数
            'txnAmt' => $amount,	//交易金额，单位分，此处默认取demo演示页面传递的参数
        		'reqReserved' =>$out_trade_no,        //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据
            //TODO 其他特殊用法请查看 special_use_purchase.php                               
		);
        sign ( $params );               
        $uri = SDK_FRONT_TRANS_URL;
      
        $html_form =$this-> create_html ( $params, $uri );
        echo $html_form;
	}

	/**
	 *
	 * 取得订单支付状态，成功或失败
	 * @param array $param
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
		include_once(LIB_PATH . "/Api/alipay/lib/alipay_notify.class.php");

		$alipayNotify  = new AlipayNotify($this->payment);
		$verify_result = $alipayNotify->verifyNotify();

		return $verify_result;
	}

	/**
	 * 通知验证
	 *
	 * @access public
	 */
	public function verifyReturn()
	{
		include_once(LIB_PATH . "/Api/alipay/lib/alipay_notify.class.php");

		$alipayNotify  = new AlipayNotify($this->payment);
		$verify_result = $alipayNotify->verifyReturn();

		return $verify_result;
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

		$notify_row['deposit_async']         = 1;

		return $notify_row;
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

			$order_id = $notify_param['orderId'];
			$notify_row = $Union_OrderModel->getOne($order_id);
			$notify_row['order_id'] = $notify_param['orderId'];

		}
		else
		{
			//插入充值记录, 如果同步数据没有,从订单数据中读取过来
			$notify_row = array();
			$notify_row['order_id'] = $notify_param['orderId'];
			$notify_row['deposit_trade_no'] = $notify_param['queryId'];
			$notify_row['deposit_body']          = '';
			$notify_row['deposit_seller_id']  = $notify_param['orderId'];
			$notify_row['deposit_notify_time']  = $notify_param['settleDate'];
			$notify_row['deposit_trade_status']  = $notify_param['respCode'];
			$notify_row['deposit_total_fee']  = $notify_param['txnAmt'];
			$notify_row['deposit_gmt_payment']  = $notify_param['settleDate'];
			$notify_row['deposit_notify_id']  = $notify_param['orderId'];
			$notify_row['deposit_payment_type'] = $notify_param['bizType'];
			$notify_row['deposit_service']     =  'unionpay';
			$notify_row['deposit_sign_type']    = $notify_param['signMethod'];
			$notify_row['deposit_sign']         = $notify_param['signature'];		 
		}

		$notify_row['payment_channel_id']   = Payment_ChannelModel::UNIONPAY;

		return $notify_row;
	}
	
	
	function create_html($params, $action) {
	// <body onload="javascript:document.pay_form.submit();">
	$encodeType = isset ( $params ['encoding'] ) ? $params ['encoding'] : 'UTF-8';
	$html = <<<eot
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset={$encodeType}" />
</head>
<body onload="javascript:document.pay_form.submit();">
    <form id="pay_form" name="pay_form" action="{$action}" method="post">
	
eot;
	foreach ( $params as $key => $value ) {
		$html .= "    <input type=\"hidden\" name=\"{$key}\" id=\"{$key}\" value=\"{$value}\" />\n";
	}
	$html .= <<<eot
   <!-- <input type="submit" type="hidden">-->
    </form>
</body>
</html>
eot;
	return $html;
}
}

?>
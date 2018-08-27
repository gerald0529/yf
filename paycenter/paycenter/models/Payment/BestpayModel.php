<?php if (!defined('ROOT_PATH')){exit('No Permission');}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Payment_BestpayModel implements Payment_Interface
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
 
		$this->payment['parter'] = $payment_row['bestpay_partner'];  //商户号
		$this->payment['key']    = $payment_row['bestpay_key'];      //密钥
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
		
		$out_trade_no = $this->order['union_order_id'];
		$date = date("YmdHis");
		$order = $date."0001";

		if($_SERVER['HTTP_CLIENT_IP'])
		{
			$onlineip=$_SERVER['HTTP_CLIENT_IP'];
		}elseif($_SERVER['HTTP_X_FORWARDED_FOR'])
		{
			$onlineip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
			$onlineip=$_SERVER['REMOTE_ADDR'];
		}

		$amount = $this->order['union_online_pay_amount']*100;
		$str="MERCHANTID=".$this->payment['parter']."&ORDERSEQ=$out_trade_no&ORDERDATE=$date&ORDERAMOUNT=$amount&KEY=".$this->payment['key'];
		$mac = md5($str);
		
		$parameter = array(
			"MERCHANTID"  => $this->payment['parter'],   //商户号 由翼支付网关平台统一分配
			"SUBMERCHANTID" => "",                          //子商户 
			"ORDERSEQ"    => $out_trade_no,             	//订单号 由商户平台提供，数字或字母组成
			"ORDERREQTRANSEQ"  => $order, 			  	//订单请求交易流水号 由商户平台提供，数字或字母组成
			"ORDERDATE"  => $date,   					  	//订单日期 由商户提供，长度8或14位yyyyMMdd格式yyyyMMddhhmmss
			"ORDERAMOUNT"    => $amount,    	         	//订单总金额 单位：分 订单总金额 = 产品金额+附加金额
			"PRODUCTAMOUNT"  => $amount,       	       		//产品金额 单位：分
			"ATTACHAMOUNT"      => "0",       	      		//单位：分
			"CURTYPE"    => "RMB",     						//币种 默认填 RMB
 			"ENCODETYPE"         => "1",  			//加密方式 1：MD5摘要  默认
			"MERCHANTURL"            => $this->payment['return_url'],     		//前台返回地址 商户提供的用于接收交易返回的前台url，不做业务处理，仅仅用于前台页面显示结果
			"BACKMERCHANTURL"       => $this->payment['notify_url'],       				//后台返回地址 商户提供的用于接收交易返回的后台url，用于实际的业务处理
			"ATTACH"     => $out_trade_no,
			"BUSICODE"        =>"0001", 			//业务类型 默认填0001
			"PRODUCTID" =>"99",				//业务标识
			"PRODUCTDESC"			=> "111111",//B2B类交易时，交易描述限制为8个中文，超过此限制可能导致交易失败"即时到帐接口"技术文档中的请求参数列表
			"MAC"		=> $mac,//MAC校验域 默认为0，当加密方式为1时有意义，采用标准的MD5算法，由商户实现
			"DIVDETAILS" =>""  ,  //分账明细 分账商户必填，格式参看 
			"PEDCNT" =>"" ,//分期数 只有选择银行分期支付时，此项才为必填项，取值3,6,9,12,18,24。
			"GMTOVERTIME" =>"" , //订单关闭时间
			"GOODPAYTYPE" =>"0" ,//商品付费类型 1:预付费2:后付费0:不限（帐单支付时必填）
			"GOODSCODE" =>$out_trade_no ,//商品编码 商户侧产品的唯一标识，用于翼支付统计（帐单支付时必填）
			"GOODSNAME" =>'商品名称',  //商品名称 商户侧产品名称，用于给用户发送订购短信时的展示（帐单支付时必填）
			"GOODSNUM" =>"1", //商品数量 用户购买的商户数量（帐单支付时必填）
			"CLIENTIP"	=> $onlineip//客户端IP
		);

		unset($parameter['PUB']);
		$gateway = 'https://webpaywg.bestpay.com.cn/payWeb.do';
		echo $this->buildForm($parameter,$gateway,"post","MAC");
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
	
	
	/**
     * 构造提交表单HTML数据
     * @param $para_temp 请求参数数组
     * @param $gateway 网关地址
     * @param $method 提交方式。两个值可选：post、get
	 * @param $type 类型 
     * @return 提交表单HTML文本
     */
	function buildForm($para_temp, $gateway, $method, $type = NULL)
	{
		$sHtml = "<form id='form' name='form' action='".$gateway."' method='".$method."'>";
	
		while (list ($key, $val) = each ($para_temp))
		{
			if($key == "MAC" && $type == 'ccb')
			{	
				$sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
				$sHtml.= "<script src='script/jquery-1.4.4.min.js' type='text/javascript'></script><script src='module/payment/lib/ccb/md5.js' type='text/javascript'></script><script>$('input[name=\'".$key."\']').val(hex_md5('".$val."'));</script>";		
			}
			else
			{
				$sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
			}
	    }
		$sHtml = $sHtml."</form>";
		$sHtml = $sHtml."<script>document.forms['form'].submit();</script>";
		return $sHtml;
	}
}

?>
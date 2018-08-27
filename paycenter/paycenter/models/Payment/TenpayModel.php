<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */

class Payment_TenpayModel implements Payment_Interface
{
	/**
	 *财付通网关地址（新）
	 */
	public $gateway_url = '';

	/**
	 * 消息验证地址
	 *
	 * @var string
	 */
	private $verify_url = '';

	/**
	 * 支付接口标识
	 *
	 * @var string
	 */
	private $code      = 'tenpay';

	/**
	 * 支付接口配置信息
	 *
	 * @var array
	 */
	private $payment;

	/**
	 * 订单信息
	 *
	 * @var array
	 */
	private $orderInfo;

	/**
	 * 发送至腾讯的参数
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
	 * Constructor
	 *
	 * @param  array $payment_row  支付平台信息
	 * @param  array $order_row    订单信息
	 * @access public
	 */
	public function __construct($payment_row = array(), $order_row = array())
	{
		$this->payment = $payment_row;
		$this->orderInfo   = $order_row;

		$this->payment['spname'] = $payment_row['tenpay_account'];
		$this->payment['parter'] = $payment_row['tenpay_partner'];  //财付通商户号
		$this->payment['key'] = $payment_row['tenpay_key'];//财付通密钥


		//返回URL
		$this->payment['return_url'] = Yf_Registry::get('base_url') . "/paycenter/api/payment/tenpay/return_url.php";

		//通知URL
		$this->payment['notify_url'] = Yf_Registry::get('base_url') . "/paycenter/api/payment/tenpay/notify_url.php";
	}

	/**
	 * 支付
	 *
	 * @access public
	 */
	public function pay($order_row = array())
	{
		if ($order_row)
		{
			$this->order   = $order_row;
		}


		/*获取提交的订单号*/
		$out_trade_no = $this->order['union_order_id'];
		/*获取提交的商品名称*/
		$product_name = $this->order['trade_title'];
		/* 获取提交的商品价格 */
		$order_price = $this->order["trade_payment_amount"];
		/* 获取提交的备注信息 */
		$remarkexplain = $this->order["trade_desc"];
		/* 接口类型 */
		$trade_mode=$this->order["trade_mode"];

		$strDate = date("Ymd");
		$strTime = date("His");

		/*商品价格（包含运费），以分为单位*/
		$total_fee = $order_price*100;

		/*商品名称*/
		$desc = "商品：".$product_name."，备注：".$remarkexplain;


		$randNum = rand(1000,9999);//4位随机数


		$this->parameter = array(
			'partner'       => $this->payment['parter'],
			'out_trade_no' => $out_trade_no,
			'total_free'	 => $total_fee,
			'return_url'	 => $this->payment['return_url'],
			'notify_url'	 => $this->payment['return_url'],
			'body'			 => $desc,
			'spbill_create_ip'=> $_SERVER['REMOTE_ADDR'],
			'fee_type' 	 => '1',  //币种
			'subject'		=> $desc,
			'sign_type'	=> 'MD5',
			'service_version' => '1.0',
			'input_charset' => 'UTF-8',
			'sign_key_index' => '1',
		);

		return $this->request();
	}

	/**
	 * 通知验证
	 *
	 * @access public
	 */
	public function verifyNotify()
	{
		include_once LIB_PATH . '/Api/tenpay/lib/classes/ResponseHandler.class.php';
		include_once LIB_PATH . '/Api/tenpay/lib/classes/RequestHandler.class.php';
		include_once LIB_PATH . '/Api/tenpay/lib/classes/client/ClientResponseHandler.class.php';
		include_once LIB_PATH . '/Api/tenpay/lib/classes/client/TenpayHttpClient.class.php';
		include_once LIB_PATH . '/Api/tenpay/lib/classes/function.php';

		/* 创建支付应答对象 */
		$resHandler = new ResponseHandler();
		$resHandler->setKey($key);

		//判断签名
		if($resHandler->isTenpaySign()) {

			//通知id
			$notify_id = $resHandler->getParameter("notify_id");

			//通过通知ID查询，确保通知来至财付通
			//创建查询请求
			$queryReq = new RequestHandler();
			$queryReq->init();
			$queryReq->setKey($key);
			$queryReq->setGateUrl("https://gw.tenpay.com/gateway/simpleverifynotifyid.xml");
			$queryReq->setParameter("partner", $partner);
			$queryReq->setParameter("notify_id", $notify_id);

			//通信对象
			$httpClient = new TenpayHttpClient();
			$httpClient->setTimeOut(5);
			//设置请求内容
			$httpClient->setReqContent($queryReq->getRequestURL());

			//后台调用
			if($httpClient->call()) {
				//设置结果参数
				$queryRes = new ClientResponseHandler();
				$queryRes->setContent($httpClient->getResContent());
				$queryRes->setKey($key);

				if($resHandler->getParameter("trade_mode") == "1"){
					//判断签名及结果（即时到帐）
					//只有签名正确,retcode为0，trade_state为0才是支付成功
					if($queryRes->isTenpaySign() && $queryRes->getParameter("retcode") == "0" && $resHandler->getParameter("trade_state") == "0") {
						//插入充值记录, 如果同步数据没有,从订单数据中读取过来
						$return_row = array();

						$return_row['order_id'] = $resHandler->getParameter("out_trade_no");
						$return_row['deposit_trade_no'] = $resHandler->getParameter("transaction_id");
						$return_row['deposit_body']          = $resHandler->getParameter("attach");
						$return_row['deposit_seller_id']  = $resHandler->getParameter("partner");
						$return_row['deposit_notify_time']  = $resHandler->getParameter("time_end");
						$return_row['deposit_trade_status']  = $resHandler->getParameter('trade_state');
						$return_row['deposit_total_fee']  = $resHandler->getParameter("total_fee");
						$return_row['deposit_gmt_payment']  = $resHandler->getParameter("time_end");
						$return_row['deposit_notify_id']  = $resHandler->getParameter("notify_id");
						$return_row['deposit_payment_type'] = $resHandler->getParameter("trade_mode");
						$return_row['deposit_service']     =  'tenpay';
						$return_row['deposit_sign_type']    = $resHandler->getParameter("sign_type");
						$return_row['deposit_sign']         = $resHandler->getParameter("sign");

						//根据$notify_param['payment_type']  || $_REQUEST['service']可以判断其它类型
						$notify_row['payment_channel_id']   = Payment_ChannelModel::ALIPAY;

						return $notify_row;

					} else {
						//错误时，返回结果可能没有签名，写日志trade_state、retcode、retmsg看失败详情。
						//echo "验证签名失败 或 业务错误信息:trade_state=" . $resHandler->getParameter("trade_state") . ",retcode=" . $queryRes->                         getParameter("retcode"). ",retmsg=" . $queryRes->getParameter("retmsg") . "<br/>" ;
						//log_result("即时到帐后台回调失败");
						return false;
					}
				}
				//获取查询的debug信息,建议把请求、应答内容、debug信息，通信返回码写入日志，方便定位问题
				/*
                    echo "<br>------------------------------------------------------<br>";
                    echo "http res:" . $httpClient->getResponseCode() . "," . $httpClient->getErrInfo() . "<br>";
                    echo "query req:" . htmlentities($queryReq->getRequestURL(), ENT_NOQUOTES, "GB2312") . "<br><br>";
                    echo "query res:" . htmlentities($queryRes->getContent(), ENT_NOQUOTES, "GB2312") . "<br><br>";
                    echo "query reqdebug:" . $queryReq->getDebugInfo() . "<br><br>" ;
                    echo "query resdebug:" . $queryRes->getDebugInfo() . "<br><br>";
                    */
			}else
			{
				//通信失败
				//echo "fail";
				//后台调用通信失败,写日志，方便定位问题
				//echo "<br>call err:" . $httpClient->getResponseCode() ."," . $httpClient->getErrInfo() . "<br>";
				return false;
			}

		} else
		{
			//echo "<br/>" . "认证签名失败" . "<br/>";
			//echo $resHandler->getDebugInfo() . "<br>";
			return false;
		}
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
	public function verifyReturn()
	{
		include_once LIB_PATH . '/Api/tenpay/lib/classes/ResponseHandler.class.php';
		include_once LIB_PATH . '/Api/tenpay/lib/classes/function.php';
		include_once LIB_PATH . '/Api/tenpay/lib/tenpay_config.php';

		log_result("进入前台回调页面");


		/* 创建支付应答对象 */
		$resHandler = new ResponseHandler();
		$resHandler->setKey($key);

		//判断签名
		if($resHandler->isTenpaySign()) {

			//通知id
			$notify_id = $resHandler->getParameter("notify_id");
			//商户订单号
			$out_trade_no = $resHandler->getParameter("out_trade_no");
			//财付通订单号
			$transaction_id = $resHandler->getParameter("transaction_id");
			//金额,以分为单位
			$total_fee = $resHandler->getParameter("total_fee");
			//如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
			$discount = $resHandler->getParameter("discount");
			//支付结果
			$trade_state = $resHandler->getParameter("trade_state");
			//交易模式,1即时到账
			$trade_mode = $resHandler->getParameter("trade_mode");


			if("1" == $trade_mode ) {
				if( "0" == $trade_state){
					//echo "<br/>" . "即时到帐支付成功" . "<br/>";

					//插入充值记录, 如果同步数据没有,从订单数据中读取过来
					$return_row = array();

					$return_row['order_id'] = $resHandler->getParameter("out_trade_no");
					$return_row['deposit_trade_no'] = $resHandler->getParameter("transaction_id");
					$return_row['deposit_body']          = $resHandler->getParameter("attach");
					$return_row['deposit_seller_id']  = $resHandler->getParameter("partner");
					$return_row['deposit_notify_time']  = $resHandler->getParameter("time_end");
					$return_row['deposit_trade_status']  = $resHandler->getParameter('trade_state');
					$return_row['deposit_total_fee']  = $resHandler->getParameter("total_fee");
					$return_row['deposit_gmt_payment']  = $resHandler->getParameter("time_end");
					$return_row['deposit_notify_id']  = $resHandler->getParameter("notify_id");
					$return_row['deposit_payment_type'] = $resHandler->getParameter("trade_mode");
					$return_row['deposit_service']     =  'tenpay';
					$return_row['deposit_sign_type']    = $resHandler->getParameter("sign_type");
					$return_row['deposit_sign']         = $resHandler->getParameter("sign");

					//根据$notify_param['payment_type']  || $_REQUEST['service']可以判断其它类型
					$notify_row['payment_channel_id']   = Payment_ChannelModel::ALIPAY;

					return $notify_row;

				} else {
					//当做不成功处理
					//echo "<br/>" . "即时到帐支付失败" . "<br/>";
					return false;
				}
			}
		} else {
			//echo "<br/>" . "认证签名失败" . "<br/>";
			//echo $resHandler->getDebugInfo() . "<br>";

			return false;
		}
	}

	public function sign($parameter)
	{
		$sign_str = '';

		$sign_str = $this->createSign($parameter, $parameter['key']);

		return $sign_str;
	}

	function createSign() {
		$signPars = "";
		ksort($this->parameters);
		foreach($this->parameters as $k => $v) {
			if("" != $v && "sign" != $k) {
				$signPars .= $k . "=" . $v . "&";
			}
		}
		$signPars .= "key=" . $this->getKey();
		$sign = strtolower(md5($signPars));

		return $sign;

	}

	/**
	 * 制作支付接口的请求地址 发送请求
	 *
	 * @access public
	 */
	public function request()
	{
		include_once LIB_PATH . '/Api/tenpay/lib/classes/RequestHandler.class.php';

		/*创建支付请求对象*/
		$reqHandler = new RequestHandler();
		$reqHandler->init();
		$reqHandler->setKey($this->payment['key']);
		$reqHandler->setGateURL("https://gw.tenpay.com/gateway/pay.htm");

		//----------------------------------------
		//设置支付参数
		//----------------------------------------
		$reqHandler->setParameter("partner", $this->payment['parter']); //签名方商户号 财付通统一分配的10位正整数(12XXXXXXXX)号
		$reqHandler->setParameter("out_trade_no", $this->parameter['out_trade_no']);  //商户订单号
		$reqHandler->setParameter("total_fee", $this->parameter['total_free']);  //订单总金额，单位为分
		$reqHandler->setParameter("return_url", $this->payment['return_url']); //交易完成后跳转的URL，需给绝对路径
		$reqHandler->setParameter("notify_url", $this->payment['notify_url']); //接收财付通通知的URL，需给绝对路径
		$reqHandler->setParameter("body", $this->parameter['body']);    //商品描述
		//$reqHandler->setParameter("bank_type", $this->parameter['bank_type']);  	  //银行类型，默认为财付通

		//用户ip
		$reqHandler->setParameter("spbill_create_ip", $this->parameter['spbill_create_ip']);//客户端IP
		$reqHandler->setParameter("fee_type", $this->parameter['fee_type']);               //币种
		$reqHandler->setParameter("subject",$this->parameter['subject']);          //商品名称，（中介交易时必填）

		//系统可选参数
		$reqHandler->setParameter("sign_type", $this->parameter['sign_type']);  	 	  //签名方式，默认为MD5，可选RSA
		$reqHandler->setParameter("service_version", $this->parameter['service_version']); 	  //接口版本号
		$reqHandler->setParameter("input_charset", $this->parameter['input_charset']);   	  //字符集
		$reqHandler->setParameter("sign_key_index", $this->parameter['sign_key_index']);    	  //密钥序号

		//业务可选参数
		//$reqHandler->setParameter("attach", "");             	  //附件数据，原样返回就可以了
		//$reqHandler->setParameter("product_fee", "");        	  //商品费用
		//$reqHandler->setParameter("transport_fee", "0");      	  //物流费用
		//$reqHandler->setParameter("time_start", date("YmdHis"));  //订单生成时间
		//$reqHandler->setParameter("time_expire", "");             //订单失效时间
		//$reqHandler->setParameter("buyer_id", "");                //买方财付通帐号
		//$reqHandler->setParameter("goods_tag", "");               //商品标记
		//$reqHandler->setParameter("trade_mode",$trade_mode);              //交易模式（1.即时到帐模式，2.中介担保模式，3.后台选择（卖家进入支付中心列表选择））
		//$reqHandler->setParameter("transport_desc","");              //物流说明
		//$reqHandler->setParameter("trans_type","1");              //交易类型
		//$reqHandler->setParameter("agentid","");                  //平台ID
		//$reqHandler->setParameter("agent_type","");               //代理模式（0.无代理，1.表示卡易售模式，2.表示网店模式）
		//$reqHandler->setParameter("seller_id","");                //卖家的商户号

		$reqUrl = $reqHandler->getRequestURL();
		$params = $reqHandler->getAllParameters();
		@header("Location: " . $reqUrl);

		//return $reqUrl;
	}


	/**
	 * 发送请求
	 *
	 * @access public
	 */
	public function send()
	{

	}



}

?>


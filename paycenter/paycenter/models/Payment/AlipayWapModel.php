<?php if (!defined('ROOT_PATH')){exit('No Permission');}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Payment_AlipayWapModel implements Payment_Interface
{
	/**
	 *支付宝网关地址（新）
	 */
	public $gateway_url = 'http://wappaygw.alipay.com/service/rest.htm?';

	/**
	 * 消息验证地址
	 *
	 * @var string
	 */
	private $verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';

	/**
	 * 支付接口标识
	 *
	 * @var string
	 */
	private $code = 'alipay_web';

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
	private $order;

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

		//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
		//合作身份者id，以2088开头的16位纯数字
		$this->payment['partner'] = $payment_row['alipay_partner'];

		//收款支付宝账号，以2088开头由16位纯数字组成的字符串，一般情况下收款账号就是签约账号
		$this->payment['seller_id']	= $this->payment['partner'];

		//安全检验码，以数字和字母组成的32位字符
		//如果签名方式设置为“MD5”时，请设置该参数
		$this->payment['key'] = $payment_row['alipay_key'];

		//商户的私钥（后缀是.pen）文件相对路径
		//如果签名方式设置为“0001”时，请设置该参数
		$this->payment['private_key_path'] = APP_PATH . '/data/api/alipay/cert/rsa_private_key.pem';

		//支付宝公钥（后缀是.pen）文件相对路径
		//如果签名方式设置为“0001”时，请设置该参数
		$this->payment['ali_public_key_path'] = APP_PATH . '/data/api/alipay/cert/alipay_public_key.pem';


		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

		//签名方式 不需修改
		$this->payment['sign_type'] = strtoupper('MD5');

		//字符编码格式 目前支持 gbk 或 utf-8
		$this->payment['input_charset'] = strtolower('utf-8');

		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		$this->payment['cacert'] = LIB_PATH . '/Api/alipay/cacert.pem';

		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$this->payment['transport'] = 'http';

		// 支付类型 ，无需修改
		$this->payment['payment_type'] = "1";

		// 产品类型，无需修改
		$this->payment['service'] = "alipay.wap.create.direct.pay.by.user";

		//服务器异步通知页面路径
		$this->payment['notify_url'] = Yf_Registry::get('base_url') . "/paycenter/api/payment/alipay/notify_url.php";
		//需http://格式的完整路径，不允许加?id=123这类自定义参数

		//页面跳转同步通知页面路径
		$this->payment['return_url'] = $_GET['new_return_url']?:Yf_Registry::get('base_url') . "/paycenter/api/payment/alipay/return_url.php";
		//需http://格式的完整路径，不允许加?id=123这类自定义参数

		//操作中断返回地址
		$this->payment['merchant_url'] = Yf_Registry::get('base_url') . "/paycenter/api/payment/alipay/merchant_url.php";

		//用户付款中途退出返回商户的地址。需http://格式的完整路径，不允许加?id=123这类自定义参数

		//↓↓↓↓↓↓↓↓↓↓ 请在这里配置防钓鱼信息，如果没开通防钓鱼功能，为空即可 ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓

		// 防钓鱼时间戳  若要使用请调用类文件submit中的query_timestamp函数
		$this->payment['anti_phishing_key'] = "";

		// 客户端的IP地址 非局域网的外网IP地址，如：221.0.0.1
		$this->payment['exter_invoke_ip'] = "";

		//↑↑↑↑↑↑↑↑↑↑请在这里配置防钓鱼信息，如果没开通防钓鱼功能，为空即可 ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
	}

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


		include_once LIB_PATH . '/Api/alipay/lib/alipay_submit.class.php';

		//返回格式
		$format = "xml";
		//必填，不需要修改

		//返回格式
		$v = "2.0";
		//必填，不需要修改

		//请求号
		$req_id = uniqid();
		//必填，须保证每次请求都是唯一

		//**req_data详细信息**
		//卖家支付宝帐户
		$seller_email = $this->payment['alipay_account'];
		//必填

		//商户订单号
		$out_trade_no = $this->order['union_order_id'];
		//商户网站订单系统中唯一订单号，必填

		//订单名称
		$subject = $this->order['trade_title'];
		$body = $this->order['trade_title'];
		//必填

		//付款金额
		$total_fee = $this->order['union_online_pay_amount'];

		$quantity = isset($this->order['quantity']) ? $this->order['quantity'] : 1;

		//必填
		/*
		//请求业务参数详细
		$req_data = '<direct_trade_create_req><notify_url>' . $this->payment['notify_url'] . '</notify_url><call_back_url>' . $this->payment['return_url'] . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee><merchant_url>' . $this->payment['merchant_url'] . '</merchant_url></direct_trade_create_req>';

		//$req_data = "<direct_trade_create_req><subject>彩票</subject><out_trade_no>$out_trade_no</out_trade_no><total_fee>$total_fee</total_fee><seller_account_name>$seller_email</seller_account_name><call_back_url>$call_back_url</call_back_url><notify_url>$notify_url</notify_url><out_user></out_user><merchant_url>$merchant_url</merchant_url><pay_expire>3600</pay_expire></direct_trade_create_req>";

		//必填

		//

		//构造要请求的参数数组，无需改动
		$this->parameter = array(
			"service" => "alipay.wap.trade.create.direct",
			"partner" => trim($this->payment['alipay_partner']),
			"sec_id" => $this->payment['sign_type'],
			"format" => $format,
			"v" => $v,
			"req_id" => $req_id,
			"req_data" => $req_data,
			"_input_charset" => trim(strtolower($this->payment['input_charset'])),
			//"show_url"	=> $show_url,
		);

		//建立请求
		$alipaySubmit = new AlipaySubmit($this->payment);
		$html_text    = $alipaySubmit->buildRequestHttp($this->parameter);

		//URLDECODE返回的信息
		$html_text = urldecode($html_text);

		//解析远程模拟提交后返回的信息
		$para_html_text = $alipaySubmit->parseResponse($html_text);

		if (isset($para_html_text['res_error']))
		{
			throw new  Exception($para_html_text['res_error']);
		}
		else
		{

		}

		//获取request_token
		$request_token = $para_html_text['request_token'];

		//根据授权码token调用交易接口alipay.wap.auth.authAndExecute
		//业务详细
		$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
		//必填

		//构造要请求的参数数组，无需改动
		$parameter = array(
			"service" => "alipay.wap.auth.authAndExecute",
			"payment_type" => $this->payment['payment_type'],  //支付类型,可空
			"partner" => trim($this->payment['partner']),
			"sec_id" => trim($this->payment['sign_type']),
			"format" => $format,
			"v" => $v,
			"req_id" => $req_id,
			"req_data" => $req_data,
			"_input_charset" => trim(strtolower($this->payment['input_charset']))
		);
		*/

		//构造要请求的参数数组，无需改动
		$parameter = array(
			"service"       => $this->payment['service'],
			"partner"       => $this->payment['partner'],
			"seller_id"  => $this->payment['seller_id'],
			"payment_type"	=> $this->payment['payment_type'],
			"notify_url"	=> $this->payment['notify_url'],
			"return_url"	=> $this->payment['return_url'],

			//"anti_phishing_key"=>$this->payment['anti_phishing_key'],
			//"exter_invoke_ip"=>$this->payment['exter_invoke_ip'],
			"out_trade_no"	=> $out_trade_no,
			"subject"	=> $subject,
			"total_fee"	=> $total_fee,
			"body"	=> $body,
			"app_pay" => 'Y',
			"_input_charset"	=> trim(strtolower($this->payment['input_charset'])),
			//其他业务参数根据在线开发文档，添加参数.文档地址:https://doc.open.alipay.com/doc2/detail.htm?spm=a219a.7629140.0.0.kiX33I&treeId=62&articleId=103740&docType=1
			//如"参数名"=>"参数值"
			"extra_common_param"	=> ''

		);
        
		$quit_url = trim($_GET['quit_url']);
		if($quit_url){
			$parameter['quit_url'] = $quit_url;
		}

		//建立请求
		$alipaySubmit = new AlipaySubmit($this->payment);
		$html_text    = $alipaySubmit->buildRequestForm($parameter, 'get', '确认');
		echo $html_text;
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



//		echo "<pre>";
//			print_r($notify_param);
//		echo "</rpe>";
/*		Array
		(
			[body] => 奥克斯 KFR-35GW/BPTYC
    [is_success] => T
    [notify_id] => RqPnCoPT3K9%2Fvwbh3InWfIZ4vXHEPg1CouOBMke6UQADrw5t3%2BF4G8lwmaECPzFEZk2z
    [notify_time] => 2016-08-25 11:23:42
    [notify_type] => trade_status_sync
    [out_trade_no] => U20160825105139797
    [payment_type] => 1
    [seller_id] => 2088211646997663
    [service] => alipay.wap.create.direct.pay.by.user
    [subject] => 奥克斯 KFR-35GW/BPTYC
    [total_fee] => 0.01
    [trade_no] => 2016082521001003200261871045
    [trade_status] => TRADE_FINISHED
    [sign] => 51ec57e19717afaa2d4a85dbb51cd975
    [sign_type] => MD5
)*/



		//根据$notify_param['payment_type']  || $_REQUEST['service']可以判断其它类型
		$notify_row['payment_channel_id']   = Payment_ChannelModel::ALIPAY;

		return $notify_row;
	}
}

?>


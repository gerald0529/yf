<?php

/**
 * @author yuli
 * app充值
 */
class Api_User_RechargeCtl extends Yf_AppController
{
    const AliPay_SDK_URL = './libraries/Api/alipayMobile/AopSdk.php'; //aliPay SKD路径
    const RECHARGE_TYPE_ALIPAY = 1;
    const RECHARGE_TYPE_WX = 2;

    private $eCrypt; //加密
    private $notify_url;
    private $consumeRecordModel; //交易明细表
    private $request_parameters; //请求参数
    private $out_trade_no; //流水号

    public static $error_msg = [
        'PARSED_FAIL' => '解析失败',
        'INVALID_REQUEST' => '无效参数',
        'CREATE_TRADE_LOG_FAIL'=> '创建日志失败',
    ];

    public static $recharge_type = [
        1 => '支付宝充值',
        2 => '微信充值'
    ];


    public function __construct($ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);

        $this->consumeRecordModel = new Consume_RecordModel;
        $this->notify_url = sprintf("%s/paycenter/api/payment/alipay/notify_url.php", Yf_Registry::get('base_url'));
    }

    /**
     * 初始化
     */
    public function init()
    {
        $this->eCrypt = new ECrypt;
        $decode_res = $this->decode();

        $error_arr = [
            'cmd_id' => -140,
            'data' => [],
            'status' => 250
        ];

        if ( !$decode_res['flag']) {
            $error_arr['msg'] = self::$error_msg['PARSED_FAIL'];
            exit(json_encode($error_arr));
        }

        $check_res = $this->isValidRequest();

        if (! $check_res['flag']) {
            $error_arr['msg'] = self::$error_msg['INVALID_REQUEST'];
            exit(json_encode($error_arr));
        }

        $userBaseModel = new User_BaseModel;
        $user_data = $userBaseModel->getOne($this->request_parameters['user_id']);

        if (! $user_data) {
            $error_arr['msg'] = self::$error_msg['INVALID_REQUEST'];
            exit(json_encode($error_arr));
        }

        Perm::$userId = $user_data['user_id'];
        Perm::$row = $user_data;
    }

    private function decode()
    {
        $recharge_money_str = $_REQUEST['recharge_money_str'];
        if (! $recharge_money_str) {
            return ['flag' => false, 'error_msg' => self::$error_msg['INVALID_REQUEST']];
        }

        $transfer_money_arr = $this->eCrypt->decode(urldecode($recharge_money_str));

        if (! $transfer_money_arr) {
            return ['flag' => false, 'error_msg' => self::$error_msg['PARSE_FAIL']];
        }

        $this->request_parameters = $transfer_money_arr;

        return ['flag' => true];
    }


    /**
     * 用户充值
     */
    public function rechargeMoney()
    {
         
        //生成订单
        $_REQUEST['deposit_amount'] = $this->request_parameters['amount'];
        $_REQUEST['typ'] = 'json';
        $_REQUEST['returnData'] = 1;
        $rs =   ( new InfoCtl($ctl='Info', $met='addDeposit', $typ='json') )->addDeposit(); 
        $this->out_trade_no = $rs['uorder'];
        unset($_REQUEST['deposit_amount'] ,$_REQUEST['typ'] ,$_REQUEST['returnData'] ); 
        if(! $this->out_trade_no ) {
            return $this->data->setError(self::$error_msg['CREATE_TRADE_LOG_FAIL']);
        } 

        if ($this->request_parameters['recharge_type'] == self::RECHARGE_TYPE_ALIPAY) { 
	        	$trade_id = $this->out_trade_no; 
						//如果订单号为合并订单号，则获取合并订单号的信息
						$Union_OrderModel = new Union_OrderModel();
						$trade_row        = $Union_OrderModel->getOne($trade_id); 
						Yf_Log::log(var_export($trade_row , true), Yf_Log::INFO, 'rechargeMoney'); 

            $trade_order_str = $this->getAliPayTradeAppPayData($trade_row);
        } else {
            $trade_order_str = $this->getWXTradeAppPayData();
        }

        $result = [
            'trade_order_str'=> $trade_order_str
        ];
        
        return $this->data->addBody(-140, $result, 'success', 200);
    }

    private function isValidRequest()
    {
        $check_arr = [];

        $check_arr[] = isset($this->request_parameters['user_id']) && is_numeric($this->request_parameters['user_id'])
            ? true
            : false;

        $check_arr[] = isset($this->request_parameters['recharge_type']) && is_numeric($this->request_parameters['recharge_type'])
            ? true
            : false;

        $check_arr[] = isset($this->request_parameters['amount']) && is_numeric($this->request_parameters['amount']) && $this->request_parameters['amount'] > 0
            ? true
            : false;

        return in_array(false, $check_arr, true)
            ? ['flag' => false, 'error_msg' => self::$error_msg['INVALID_REQUEST']]
            : ['flag'=> true];
    }


    /**
     * @return array
     * PHP服务端SDK生成APP支付订单信息（支付宝）
     */
    private function getAliPayTradeAppPayData($trade_row)
    {
        require_once self::AliPay_SDK_URL; //初始化SDK

        $aop = new AopClient;
        //实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
        $request = new AlipayTradeAppPayRequest;
        //SDK已经封装掉了公共参数，这里只需要传入业务参数

        $amount = $this->request_parameters['amount'];

        $bizcontent = <<<EOT
                {
					"body":"用户充值",
					"subject": "App支付",
					"out_trade_no": "$this->out_trade_no",
					"timeout_express": "30m",
					"total_amount": "$amount",
					"product_code": "QUICK_MSECURITY_PAY"
		        }
EOT;

        $request->setNotifyUrl($this->notify_url); //商户外网可以访问的异步地址
        $request->setBizContent($bizcontent);
        //这里和普通的接口调用不同，使用的是sdkExecute
        return $aop->sdkExecute($request);
    }

    /**
     * 获取微信APP支付订单信息
     */
    private function getWXTradeAppPayData()
    {
        $payment_model = PaymentModel::create('wx_native');

        return $payment_model->rechargeMoneyByApp([
            'out_trade_no'=> $this->out_trade_no,
            'total_fee' => $this->request_parameters['amount']
        ]);
    }
}
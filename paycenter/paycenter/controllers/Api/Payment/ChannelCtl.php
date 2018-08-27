<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Payment_ChannelCtl extends Api_Controller
{
    public $paymentChannelModel = null;

    public static $wxPaymentChannelCode = ['wx_native', 'app_wx_native', 'app_h5_wx_native', 'wxapp'];
    public static $alipayPaymentChannelCode = ['alipay', 'alipayMobile'];

    /**
     * Constructor
     *
     * @param  string $ctl 控制器目录
     * @param  string $met 控制器方法
     * @param  string $typ 返回数据类型
     * @access public
     */
    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);

        //include $this->view->getView();
        $this->paymentChannelModel = new Payment_ChannelModel();
    }

    /**
     * 列表数据
     * 
     * @access public
     */
    public function lists()
    {
        $user_id = Perm::$userId;

		$page = $_REQUEST['page'];
		$rows = $_REQUEST['rows'];
		$sort = $_REQUEST['sord'];


		$data = array();

		if (isset($_REQUEST['skey']))
		{
			$skey = $_REQUEST['skey'];

			$data = $this->paymentChannelModel->getChannelList('*', $page, $rows, $sort);
		}
		else
		{
			$data = $this->paymentChannelModel->getChannelList('*', $page, $rows, $sort);
		}


		$this->data->addBody(-140, $data);
    }

    /**
     * 读取
     * 
     * @access public
     */
    public function get()
    {
        $user_id = Perm::$userId;

		$payment_channel_id = $_REQUEST['payment_channel_id'];
		$rows = $this->paymentChannelModel->getChannel($payment_channel_id);

		$data = array();

		if ($rows)
		{
			$data = array_pop($rows);
		}

		$this->data->addBody(-140, $data);
    }

    /**
     * 添加
     *
     * @access public
     */
    public function add()
    {
        $data['payment_channel_id']     = request_string('payment_channel_id'); // ID              
        $data['payment_channel_code']   = request_string('payment_channel_code'); // 代码名称        
        $data['payment_channel_name']   = request_string('payment_channel_name'); // 支付名称        
        $data['payment_channel_image']  = request_string('payment_channel_image'); // 支付方式图片    
        $data['payment_channel_status'] = request_string('payment_channel_status'); // 接口状态        
        $data['payment_channel_allow']  = request_string('payment_channel_allow'); // 类型            
        $data['payment_channel_wechat'] = request_string('payment_channel_wechat'); // 微信中是否可以使用
        $data['payment_channel_enable'] = request_string('payment_channel_enable'); // 是否启用   
   //    $data['payment_channel_config'] = decode_json(request_string('payment_channel_config')); // 支付接口配置信息
        $payment_channel_config = request_row('payment_channel_config'); // 支付接口配置信息
        if(in_array($data['payment_channel_code'], static::$wxPaymentChannelCode)){  //微信支付特殊处理
            $data['payment_channel_config']["appid"] = $payment_channel_config['account'];
            $data['payment_channel_config']["key"] = $payment_channel_config['key'];
            $data['payment_channel_config']["mchid"] = $payment_channel_config['partner'];
            $data['payment_channel_config']["appsecret"] = $payment_channel_config['appsecret'];
        } elseif (in_array($data['payment_channel_code'], static::$alipayPaymentChannelCode)) {
            $data['payment_channel_config']["appid"] = $payment_channel_config['account'];
            $data['payment_channel_config']["rsaPrivateKey"] = $payment_channel_config['key'];
            $data['payment_channel_config']["alipayrsaPublicKey"] = $payment_channel_config['partner'];
        } else{
            $account = "$data[payment_channel_code]"."_account";  //拼接json 商户账号
            $key = "$data[payment_channel_code]"."_key";  //拼接json 商户key
            $partner = "$data[payment_channel_code]"."_partner";  //拼接json 商户号
            $data['payment_channel_config'][$account] = $payment_channel_config['account'];
            $data['payment_channel_config'][$key] = $payment_channel_config['key'];
            $data['payment_channel_config'][$partner] = $payment_channel_config['partner'];
        }
        $payment_channel_id = $this->paymentChannelModel->addChannel($data, true);

        if ($payment_channel_id)
        {
            $msg = 'success';
            $status = 200;
        }
        else
        {
            $msg = 'failure';
            $status = 250;
        }

        $data['payment_channel_id'] = $payment_channel_id;

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 删除操作
     *
     * @access public
     */
    public function remove()
    {
        $payment_channel_id = $_REQUEST['payment_channel_id'];

        $flag = $this->paymentChannelModel->removeChannel($payment_channel_id);

        if ($flag)
        {
            $msg = 'success';
            $status = 200;
        }
        else
        {
            $msg = 'failure';
            $status = 250;
        }

        $data['payment_channel_id'] = array($payment_channel_id);

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 修改
     *
     * @access public
     */
    public function edit()
    {
        $data['payment_channel_id']     = request_string('payment_channel_id'); // ID
        $data['payment_channel_code']   = request_string('payment_channel_code'); // 代码名称
        $data['payment_channel_name']   = request_string('payment_channel_name'); // 支付名称
        $data['payment_channel_wechat'] = request_string('payment_channel_wechat'); // 微信中是否可以使用
        $data['payment_channel_enable'] = request_string('payment_channel_enable'); // 是否启用        
           
        
        $payment_channel_config = request_row('payment_channel_config'); // 支付接口配置信息
        
        if(in_array($data['payment_channel_code'], static::$wxPaymentChannelCode)){  //微信支付特殊处理
               $data['payment_channel_config']["appid"] = $payment_channel_config['account'];
               $data['payment_channel_config']["key"] = $payment_channel_config['key'];
               $data['payment_channel_config']["mchid"] = $payment_channel_config['partner'];
               $data['payment_channel_config']["appsecret"] = $payment_channel_config['appsecret'];
        } elseif (in_array($data['payment_channel_code'], static::$alipayPaymentChannelCode)) {
            $data['payment_channel_config']["appid"] = $payment_channel_config['account'];
            $data['payment_channel_config']["rsaPrivateKey"] = $payment_channel_config['key'];
            $data['payment_channel_config']["alipayrsaPublicKey"] = $payment_channel_config['partner'];
        } else{
            $account = "$data[payment_channel_code]"."_account";  //拼接json 商户账号
            $key = "$data[payment_channel_code]"."_key";  //拼接json 商户key
            $partner = "$data[payment_channel_code]"."_partner";  //拼接json 商户号
            $data['payment_channel_config'][$account] = $payment_channel_config['account'];
            $data['payment_channel_config'][$key] = $payment_channel_config['key'];
            $data['payment_channel_config'][$partner] = $payment_channel_config['partner'];
        }
        $payment_channel_id = $_REQUEST['payment_channel_id'];
		$data_rs = $data;

        unset($data['payment_channel_id']);

        $flag = $this->paymentChannelModel->editChannel($payment_channel_id, $data);
        $this->data->addBody(-140, $data_rs);
    }
}
?>
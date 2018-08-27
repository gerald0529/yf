<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class QrCtl  extends Controller
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);

		$this->shopid =	request_int('shopid');

	}

	/*
		用户扫描收款码之后进行相关的用户注册登录操作
	*/
	public function login()
	{
		$redirect = sprintf('%s?ctl=Qr&met=index&typ=e&shopid=%s', Yf_Registry::get('url'),$this->shopid);
		$callback = Yf_Registry::get('url') . '?ctl=Login&met=check&typ=e&redirect=' . urlencode($redirect);
		//微信中扫付款码
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false)
		{
			//1.判断该微信用户是否已经注册，如果没有注册的话就获取微信用户信息注册新用户登录
			//https://ucenter.local.yuanfeng021.com/index.php?ctl=Connect_Bind&met=login&from=shop&type=weixin&callback=https%3A%2F%2Fshop.local.yuanfeng021.com%2Findex.php%3Fctl%3DLogin%26met%3Dcheck%26typ%3De%26redirect%3D

			$url = sprintf('%s?ctl=Connect_Bind&met=login&from=shop&type=%s&callback=%s&op=Qr',Yf_Registry::get('ucenter_api_url') ,'weixin', urlencode($callback));
		}
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'Alipay') !== false)
        {
            $url = sprintf('%s?ctl=Connect_Bind&met=login&from=shop&type=%s&callback=%s&op=Qr',Yf_Registry::get('ucenter_api_url') ,'alipay', urlencode($callback));
        }

		location_to($url);
	}

	/*
		进入付款页面
	*/
	public function index()
	{
		//判断当前是否有用户登录，没有用户登录的话调用login方法注册登录
		$shopid = request_int('shopid');
		if(!Perm::checkUserPerm())
		{
			$this->login();
		}
		//查询可使用的支付方式
		$Payment_ChannelModel = new Payment_ChannelModel();
		$payment_channel = $Payment_ChannelModel->getByWhere(array('payment_channel_enable' => Payment_ChannelModel::ENABLE_YES ));
		$payment_channel = array_values($payment_channel);
		//去掉不可用图标显示的支付方式
		//微信比较提别，这里的微信图标统一使用wx_native，客户端监听到微信并调取客户端时，再根据应用场景传对应参数调取app_wx_native或app_h5_wx_native
		$pay_channel = array('alipay','wx_native');
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false)
		{
			unset($pay_channel[0]);
		}
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'Alipay') !== false)
		{
			unset($pay_channel[1]);
		}
		foreach($payment_channel as $key => $val)
		{
			if(!in_array($val['payment_channel_code'], $pay_channel)){
				unset($payment_channel[$key]);
			}
		}

		include $this->view->getView();


		die;
	}

}


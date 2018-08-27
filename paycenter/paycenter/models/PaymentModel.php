<?php
/**
 * 
 * 通过这个类，统一管理支付类。
 * 
 * @category   Framework
 * @package    Db
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 */
class PaymentModel
{

    /**
     * 构造函数
     *
     * @access    private
     */
    public function __construct()
    {
    }

    /**
     * 得到支付句柄
     *
     * @param array $channel   使用的支付驱动
     * @param array $reset_config 重新设定支付配置、覆盖支付默认选项
     *
     * @return Object   Payment Object
     *
     * @access public
     */
    public static function create($channel, $reset_config= [],$openid=null,$body=null,$total_fee=null,$uorder_id)
    {
        $Payment_ChannelModel = new Payment_ChannelModel();
		$config_row = $Payment_ChannelModel->getChannelConfig($channel);

		if (!$config_row)
		{
			throw new Exception(_('支付配置数据错误!'));
		}

        $config_row = $reset_config ? array_merge($config_row, $reset_config) : $config_row;

        $PaymentModel = null;


        switch ($channel) {
             case 'alipay':
                    if (!Yf_Utils_Device::isMobile())
                    {
                        $PaymentModel = new Payment_Alipay($config_row);
                    }
                    else
                    {
                        $PaymentModel = new Payment_AlipayWap($config_row);
                    }
                    break;
             case 'tenpay':
                $PaymentModel = new Payment_TenpayModel($config_row);
                break;
             case 'tenpay_wap':
                $PaymentModel = new Payment_TenpayWapModel($config_row);
                break;
             case 'unionpay':
                    $PaymentModel = new Payment_UnionPayModel($config_row);
                break;
             case 'paypal':
                    $PaymentModel = new Payment_Paypal($config_row);
                break;
             case 'app_h5_wx_native':
             case 'wx_native':
                    //微信变量, 不变动程序,修正数据
                    !defined('APPID_DEF') && define('APPID_DEF', $config_row['appid']);
                    !defined('MCHID_DEF') && define('MCHID_DEF', $config_row['mchid']);
                    !defined('KEY_DEF') && define('KEY_DEF', $config_row['key']);
                    !defined('APPSECRET_DEF') && define('APPSECRET_DEF', $config_row['appsecret']);

                    !defined('SSLCERT_PATH_DEF') && define('SSLCERT_PATH', LIB_PATH . '/Api/wx/cert/apiclient_cert.pem');
                    !defined('SSLKEY_PATH_DEF') && define('SSLKEY_PATH', LIB_PATH . '/Api/wx/cert/apiclient_key.pem');

                    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false || $_GET['trade_type'] == 'JSAPI') {
                        $PaymentModel = new Payment_WxJsModel($config_row);
                    } else {
                        if (Yf_Utils_Device::isMobile()) {
                            $PaymentModel = new Payment_WxJsModel($config_row);
                        } else {
                            $PaymentModel = new Payment_WxNativeModel($config_row);
                        }
                    }
                 break;
             case 'bestpay':
                 $PaymentModel = new Payment_BestpayModel($config_row);   
                 break;
            case 'app_wx_native':
                !defined('APPID_DEF') && define('APPID_DEF', $config_row['appid']);
                !defined('MCHID_DEF') && define('MCHID_DEF', $config_row['mchid']);
                !defined('KEY_DEF') && define('KEY_DEF', $config_row['key']);
                !defined('APPSECRET_DEF') && define('APPSECRET_DEF', $config_row['appsecret']);

                !defined('SSLCERT_PATH_DEF') && define('SSLCERT_PATH', LIB_PATH . '/Api/wx/cert/apiclient_cert.pem');
                !defined('SSLKEY_PATH_DEF') && define('SSLKEY_PATH', LIB_PATH . '/Api/wx/cert/apiclient_key.pem');

                $PaymentModel = new Payment_WxNativeModel($config_row);
                break;
            case 'wxapp':
                !defined('APPID_DEF') && define('APPID_DEF', $config_row['appid']);
                !defined('MCHID_DEF') && define('MCHID_DEF', $config_row['mchid']);
                !defined('KEY_DEF') && define('KEY_DEF', $config_row['key']);
                !defined('APPSECRET_DEF') && define('APPSECRET_DEF', $config_row['appsecret']);

                !defined('SSLCERT_PATH_DEF') && define('SSLCERT_PATH', LIB_PATH . '/Api/wxapp/cert/apiclient_cert.pem');
                !defined('SSLKEY_PATH_DEF') && define('SSLKEY_PATH', LIB_PATH . '/Api/wxapp/cert/apiclient_key.pem');

                $PaymentModel = new Payment_Wxapp($openid,$body,$total_fee,$uorder_id);
                break;
                default:
                    # code...
                    break;
        }

       

        return $PaymentModel;
    }

    /**
     * 得到支付句柄
     *
     * @param array  $channel   使用的支付驱动
     *
     * @return Object   Payment Object
     *
     * @access public
     */
    public static function get($channel)
    {
        return self::create($channel);
    }
}
?>
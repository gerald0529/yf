<?php

require_once '../../../configs/config.ini.php';
$alipayModel = PaymentModel::create('alipay');
$config = $alipayModel->getConfig();

require_once realpath(__DIR__ . '/../../../../libraries/Api/alipayMobile/pagepay/service') . '/AlipayTradeService.php';
spl_autoload_register('__autoload'); //ali SDK覆盖系统__autoload方法
ini_set('display_errors', 1);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

if (! function_exists('isChainOrder')) {
    function isChainOrder($key, $url, $appId,  $orderId)
    {
        $request = [
            'app_id'=> $appId,
            'orderId'=> $orderId
        ];
        $result = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Trade_Order&met=isChainOrder&typ=json', $url), $request);
        if ($result['status'] == 200) {
            return $result['data']['flag']
                ? true
                : false;
        }
        return false; //请求失败，默认false
    }
}

$arr = $_GET;
$alipaySevice = new AlipayTradeService($config);
$result = $alipaySevice->check($arr);

if ($result) {
    spl_autoload_register('__autoload'); //ali SDK覆盖系统__autoload方法

    //查找此支付单的交易类型
    $Union_OrderModel = new Union_OrderModel();
    $data = $Union_OrderModel->getOne($_GET['out_trade_no']);

    $trade_type = Trade_TypeModel::$trade_type_row[$data['trade_type_id']];

    //重定向浏览器
    if($trade_type == 'shopping')
    {
        $app_id = $data['app_id'];

        //查找回调地址
        $User_AppModel = new User_AppModel();
        $user_app = $User_AppModel->getOne($app_id);

        $key = $user_app['app_key'];
        $url = $user_app['app_url'];
        $orderId = $data['inorder'];

        // if (isChainOrder($key, $url, $app_id, $orderId)) {
        //     $return_app_url = $user_app['app_url'] . '?ctl=Buyer_Order&met=chain';
        // } else {
        //     $return_app_url = $user_app['app_url'] . '?ctl=Buyer_Order&met=physical';
        // }
        $return_app_url = Yf_Registry::get('paycenter_api_url') . "?ctl=Info&met=after_pay&order_id=" . $orderId . "&return=" . $user_app['app_url'];
    }

    if ($trade_type == 'deposit') {
        $return_app_url = Yf_Registry::get('paycenter_api_url') . "?ctl=Info&met=index";
    }

    if($return_app_url)
    {
        header("Location: " . $return_app_url);
    }

    echo "支付成功";
    //确保重定向后，后续代码不会被执行
    exit;
} else {
    echo 'failure';
}
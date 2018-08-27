<?php
$spname="财付通双接口测试";
$partner = "1900000113";                                  	//财付通商户号
$key = "e82573dc7e6136ba414f2e2affbe39fa";											//财付通密钥

//$return_url = Yf_Registry::get('base_url') . "/paycenter/api/payment/tenpay/payReturnUrl.php";			//显示支付结果页面,*替换成payReturnUrl.php所在路径
//$notify_url = Yf_Registry::get('base_url') . "/paycenter/api/payment/tenpay/payNotifyUrl.php";			//支付完成后的回调处理页面,*替换成payNotifyUrl.php所在路径

$return_url = 'http://localhost/paycenter/paycenter/api/payment/tenpay/return_url.php';
$notify_url = 'http://localhost/paycenter/paycenter/api/payment/tenpay/notify_url.php';
?>
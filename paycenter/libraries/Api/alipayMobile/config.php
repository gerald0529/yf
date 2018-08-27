<?php
//$config = array (
//		//应用ID,您的APPID。
//		'app_id' => "",
//
//		//商户私钥
//		'merchant_private_key' => "",
//
//		//异步通知地址
//		'notify_url' => "http://外网可访问网关地址/alipay.trade.page.pay-PHP-UTF-8/notify_url.php",
//
//		//同步跳转
//		'return_url' => "http://外网可访问网关地址/alipay.trade.page.pay-PHP-UTF-8/return_url.php",
//
//		//编码格式
//		'charset' => "UTF-8",
//
//		//签名方式
//		'sign_type'=>"RSA2",
//
//		//支付宝网关
//		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
//
//		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
//		'alipay_public_key' => "",
//);

$config = array (
    'app_id' => '2017112100078975',
    'merchant_private_key' => 'MIIEpAIBAAKCAQEA3/PTgKJtGw9JbfADhRgrHMPz3ocCdM485X8ZWq50bEA4gZUXhcSyZB6J36jEsZ7FRQzjdlwx/eiVtqrYVJ6ZnonwBVD3RXLPAq/32ugybCmq3m6A1BxePZjD6AdPXgcQ3L/YFZ3fPU5SDlqaZFgRcX96l/cPo1CYMn3nxteRf/0k7V4PcmDXnk9xOt9JaxFMrqeFT5sdaGp9BBY/SgJ41b0/vYAa5x2ChSWEp6UAGXNWzxt++hWjO3lBYGLpCFYoyzXbZdBuadUOTuY/8Qu5io+x11DGja4V9UoIZZQPH6P1r3zqyBVVCu9+9CfN48fWmiqJbHsGAfkfFp7WQwYj2wIDAQABAoIBAQCOlRE6W681RDVO7jl/elSwer2AFtrkfQ3eW15MErgC15xiPAb+3q1o+txy7mNUZq5X+Q20pJwbeQIgi4Mx1MwfWNjcuaDEsYTExD38PEsl/B1Sgm45HVqOwv0vc7//MGZ29RlhIeMoh/3ML7kOW9e3OB9YMy7cnopX0ztGqKZ1IgfmC5u0fckt5va1Pvi+8XS+00jMDkC9suAd6M4itV3GU2UmGMPa1PaxMdGt17Urga1EIPdXp0nDHSevhCgbFm/UwvN4Z7kLWsFxNXCI3wqmB9LT17/pwnJMeH3GUoQiln3+X6H63tCiXMMyz2UyxRkk//yffAR/+HEV+MT99R5BAoGBAP8/5E6lOQ3i889M2DZ6QEQWnlopNbGJupC+FVGYri3+aG+RBObOM9WqM4BK5PNR+fW+l4wix/kgHvmDjWvzvEA+OkhP8IUt+t4Sivwnwt9t7EwiP0oDVaBJLzEpfa53/uyDwKP2OuAU/oa/xWH4azEaWPrmM6SWBY8bKaDZlCJ7AoGBAOCcYRWMmHqni7r8S1+Y1vM0QWVRVm172RxwD/XAYFhwaKPRXvZm8Buo6HDAppTtxCP5tWvxsBrGah+if9pUwYAuFnCp4lTwwY/nyvlJx/TDfnaGyQ2m6ntozouhExFZEIsFgHqQDWVmdMXr4oKuRRGRW+HCwGuG2wU7ydvvS3YhAoGAeXkZmQfuaBpq92vVtc9mSEEPaU8VW4F1RS8BDE0CD6d0Yiv8zi6x4mxWiCacYOPRdk8W5j0jN/8+XnZp1kcvfs9eg01v5KGmMwtWE3yEtDom63Cc+AcwN9C8YcQiKOa4biyhgCZNjJjRLKWVNPO5Z6vnTrhBOYGf8aP2orMJWYMCgYBH50fdEikt+rzsmx+19sO5D51vxd4ZJnCWffld/rvZFAMrjjcMQl/TOvtOPR4WxxbnPWUqrTBnIeWPQwIS7tcTJa3hW0EtV/VfECEWNNxiKsMtRnDOggTGhQK6CFKGVzDIkHZUxhDDyUzQn3bfxtItkY8McsAOrBkpT76LPcu2gQKBgQDBLIVdf75Q+q1DswLs6m3zbIDv+QTk9qhpC/El7jtzKfU4j6iUNOdmotAkrI8c61YcpjNA6usY5e2KAhd041Gy15KzVssdEeor1NCgc108AilhrLA6X9q3Sgf57eUVDfgDjijQtWdS6r6lnzKqy2o/aYNwxLXjtyF4L6MpUWK64g==',
    'notify_url' => 'http://paycenter.local.yuanfeng021.com/paycenter/api/payment/alipay/notify_url.php',
    'return_url' => 'http://paycenter.local.yuanfeng021.com/paycenter/api/payment/alipay/return_url.php',
    'charset' => 'utf-8',
    'sign_type' => 'RSA2',
    'gatewayUrl' => 'https://openapi.alipay.com/gateway.do',
    'alipay_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA3/PTgKJtGw9JbfADhRgrHMPz3ocCdM485X8ZWq50bEA4gZUXhcSyZB6J36jEsZ7FRQzjdlwx/eiVtqrYVJ6ZnonwBVD3RXLPAq/32ugybCmq3m6A1BxePZjD6AdPXgcQ3L/YFZ3fPU5SDlqaZFgRcX96l/cPo1CYMn3nxteRf/0k7V4PcmDXnk9xOt9JaxFMrqeFT5sdaGp9BBY/SgJ41b0/vYAa5x2ChSWEp6UAGXNWzxt++hWjO3lBYGLpCFYoyzXbZdBuadUOTuY/8Qu5io+x11DGja4V9UoIZZQPH6P1r3zqyBVVCu9+9CfN48fWmiqJbHsGAfkfFp7WQwYj2wIDAQAB',
);
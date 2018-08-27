<?php
if(!defined('unionpay_environment')) define('unionpay_environment','prod');
if(!defined('SDK_SIGN_CERT_PWD'))define('SDK_SIGN_CERT_PWD','123456');  
// ######(以下配置为PM环境：入网测试环境用，生产环境配置见文档说明)####### 
$dir = realpath(__DIR__.'/../../../../paycenter/certs/unionpay'); 
if(unionpay_environment == 'dev'){
		define('SDK_ENCRYPT_CERT_PATH',$dir.'/acp_test_enc.cer');
		define('SDK_SIGN_CERT_PATH',$dir.'/acp_test_sign.pfx');
		// 前台请求地址
		define('SDK_FRONT_TRANS_URL','https://gateway.test.95516.com/gateway/api/frontTransReq.do'); 
		// 后台请求地址
		define('SDK_BACK_TRANS_URL','https://gateway.test.95516.com/gateway/api/backTransReq.do');
		// 批量交易
		define('SDK_BATCH_TRANS_URL','https://gateway.test.95516.com/gateway/api/batchTrans.do');
		//单笔查询请求地址
		define('SDK_SINGLE_QUERY_URL','https://gateway.test.95516.com/gateway/api/queryTrans.do');
		//文件传输请求地址
		//define('SDK_FILE_QUERY_URL','https://101.231.204.80:9080/');
		//有卡交易地址
		define('SDK_Card_Request_Url','https://gateway.test.95516.com/gateway/api/cardTransReq.do');
		//App交易地址
		define('SDK_App_Request_Url','https://gateway.test.95516.com/gateway/api/appTransReq.do'); 
		// 前台通知地址 (商户自行配置通知地址)
		//define('SDK_FRONT_NOTIFY_URL','http://localhost:8085/upacp_sdk_php/demo/api_01_gateway/FrontReceive.php');
		// 后台通知地址 (商户自行配置通知地址，需配置外网能访问的地址)
		//define('SDK_BACK_NOTIFY_URL','http://222.222.222.222/upacp_sdk_php/demo/api_01_gateway/BackReceive.php');
}else{
		define('SDK_ENCRYPT_CERT_PATH',$dir.'/acp_prod_enc.cer');
		define('SDK_SIGN_CERT_PATH',$dir.'/acp_prod_sign.pfx');
		// 前台请求地址
		define('SDK_FRONT_TRANS_URL','https://gateway.95516.com/gateway/api/frontTransReq.do'); 
		// 后台请求地址
		define('SDK_BACK_TRANS_URL','https://gateway.95516.com/gateway/api/backTransReq.do');
		// 批量交易
		define('SDK_BATCH_TRANS_URL','https://gateway.95516.com/gateway/api/batchTrans.do');
		//单笔查询请求地址
		define('SDK_SINGLE_QUERY_URL','https://gateway.95516.com/gateway/api/queryTrans.do');
		//文件传输请求地址
		//define('SDK_FILE_QUERY_URL','https://101.231.204.80:9080/');
		//有卡交易地址
		define('SDK_Card_Request_Url','https://gateway.95516.com/gateway/api/cardTransReq.do');
		//App交易地址
		define('SDK_App_Request_Url','https://gateway.95516.com/gateway/api/appTransReq.do'); 
		// 前台通知地址 (商户自行配置通知地址)
		//define('SDK_FRONT_NOTIFY_URL','http://222.222.222.222:8080/upacp_demo_b2c/demo/api_01_gateway/BackReceive.php);
		// 后台通知地址 (商户自行配置通知地址，需配置外网能访问的地址)
		//define('SDK_BACK_NOTIFY_URL','http://222.222.222.222/upacp_sdk_php/demo/api_01_gateway/BackReceive.php');
}
 

// 验签证书路径（请配到文件夹，不要配到具体文件）
define('SDK_VERIFY_CERT_DIR',$dir);

define('SDK_FILE_DOWN_PATH',$dir."/file/");
define('SDK_LOG_FILE_PATH',$dir.'/logs/');
 
 

//日志级别
const SDK_LOG_LEVEL = 'INFO';

?>
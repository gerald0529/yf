<?php
// im/default.php 中可以设置默认的appid token
$host = 'default';
if (isset($_SERVER['HTTP_HOST']))
{
    $host = $_SERVER['HTTP_HOST'];
}
$INI_PATH = __DIR__.'/im/'; 
$server_id = $host; 
$host = str_replace(':80','',$host);
$im_config_file = $INI_PATH.$host.'.php';
if(file_exists($im_config_file)){
		include $im_config_file;
}else{
	file_put_contents($im_config_file, "<?php\n $"."appId = '';\n$"."app_token = '';");
}
//一定要配置
if(!$appId || !$app_token){
	 //默认appid token
		//$appId = '8a48b55152114d54015215890cb907d5';  ///需要修正 
		//$app_token = 'd665f1caf7d4c88653d79a3b31d78f44';
}


//主帐号
$accountSid = '8a48b55152114d54015214f89963065a';

//主帐号Token
$accountToken = 'e5b22a6d243841919e3554b2a33a070f';

//应用Id
//$appId = '8a48b55152114d54015215890cb907d5';  ///需要修正 

//请求地址，格式如下，不需要写https://
$serverIP = 'app.cloopen.com';
//https://app.cloopen.com:8883

//请求端口
$serverPort = '8883';

//REST版本号
$softVersion = '2013-12-26';

$im_config_row                 = array();
$im_config_row['account_sid']   = $accountSid;
$im_config_row['account_token'] = $accountToken; 
$im_config_row['app_id']        = $appId;
$im_config_row['app_token']        = $app_token;  ///需要修正 
$im_config_row['server_ip']     = $serverIP;
$im_config_row['server_port']   = $serverPort;
$im_config_row['soft_version']  = $softVersion;
$im_config_row['account_pwd']  = 'yuanfeng12';
$im_config_row['account_system']  = 'System';



$sub_account_row['sub_account_sid'] = '250a76e6b81a11e59288ac853d9f54f2';
$sub_account_row['sub_account_token'] = 'f01d03cfbe658b39df5e3a5f83126480';
$sub_account_row['voip_account'] = '8008438300000002';
$sub_account_row['voip_password'] = 'S1jlE3av';


$im_config_row['sub_account'][0] = $sub_account_row;



Yf_Registry::set('im_config_row', $im_config_row);

return $im_config_row;
?>

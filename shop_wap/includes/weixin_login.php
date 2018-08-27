<?php 

$openWeixinLoginInWeixin = false; 
$host = $_SERVER['HTTP_HOST']; 
/**
 * 需要打开微信自动登录的host
 * 
 */
$weixinLogin = [
	'shopwap.local.**.com' => 'http://ucenter.local.**.com/',
	 
	
];
$UCenterApiUrl = $weixinLogin[$host];
if($host && $UCenterApiUrl){
	$openWeixinLoginInWeixin = true;

}
 


function is_weixin()
{ 
    if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
        return true;
    }
        return false;
}

if (
		$openWeixinLoginInWeixin &&is_weixin() && !$_GET['ks'] && !$_COOKIE['id']

	) 
{ 
	  header('Location: '.$UCenterApiUrl.'?ctl=Connect_WeixinIn&met=login&callback='.get_http()."://".$host);
	  exit;
}


/**
 * 判断http https
 * @return  http或https
 */

function get_http()
{
    $top = 'http';
    if ($_SERVER['SERVER_PORT'] == 443 || $_SERVER['HTTPS'] == 1 || $_SERVER['HTTPS'] == 'on')
        $top = 'https';
    return $top;
}
 
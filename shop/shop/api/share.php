<?php
//针对分享链接，需要通过该处中转，以判断在不同设备上访问，跳转到不同的模板中
require_once '../configs/config.ini.php';

$wapurl  =  Yf_Registry::get('shop_wap_url');
$shopurl =  Yf_Registry::get('url');
$agent   = $_SERVER['HTTP_USER_AGENT'];  //浏览器代理

$rec = request_string('rec');
setcookie('yf_recserialize',$rec,time()+60*60*24*3,'/');

if(strpos($agent,"comFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS"))
{
    switch (request_string('type'))   //手机端
    {
        case 'goods':
            $wapurl .= '/tmpl/product_detail.html?goods_id=' . request_int('gid').'&rec='.request_string('rec');  //跳转到商品详情页
            break;
        case 'shop':
            $wapurl .= '/tmpl/member/register.html?rsid='.request_int('rsid');  //经销商推荐注册,跳转到wap注册页
            break;
        case 'member':
            $wapurl .= '/tmpl/member/register.html?uid='.request_int('uid');  //经销商推荐注册,跳转到wap注册页
            break;
        default:$wapurl.= '/index.html';
    }
    header('Location:' . $wapurl);
    exit();
}
else
{
    switch (request_string('type'))  //pc端
    {
        case 'goods':
            $shopurl .= '?ctl=Goods_Goods&met=goods&gid='.request_int('gid').'&rec='.request_string('rec');  //跳转到商品详情页;
            break;
        case 'shop':
            $shopurl .= '?ctl=Login&met=reg&rsid='.request_int('rsid');  //经销商推荐注册，跳转到pc注册页
            break;
        case 'member':
            $shopurl .= '?ctl=Login&met=reg&uid='.request_int('uid');  //经销商推荐注册，跳转到pc注册页
            break;
    }
    header('Location:' . $shopurl);
    exit();
}

?>
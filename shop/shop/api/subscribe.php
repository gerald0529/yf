<?php

/* 
 * 快递鸟订阅接口
 * 
 * 
 */
require_once '../configs/config.ini.php';

//$order_id   = request_string('order_id');  //订单编号
//$express_id = request_string('express_id'); //物流公司编码
//$nu         = request_string('shipping_code'); //物流单号
//
////快递鸟

//120258159680  中通
//896081431574  顺丰
//3906885326675  韵达
//3344055674255  申通
//887134519445456910  圆通
//9752371583042  EMS

$appid = 1310531;
$appkey = '8364c7b3-3ebf-4ae3-a8e5-f2735d875614';
/**
*
* 快递鸟订阅推送2.0接口
*
* @技术QQ群: 340378554
* @see: http://kdniao.com/api-follow
* @copyright: 深圳市快金数据技术服务有限公司
* 
* ID和Key请到官网申请：http://kdniao.com/reg
*/

//电商ID
defined('EBusinessID') or define('EBusinessID', $appid);
//电商加密私钥，快递鸟提供，注意保管，不要泄漏
defined('AppKey') or define('AppKey', $appkey);
//测试请求url
//defined('ReqURL') or define('ReqURL', 'http://testapi.kdniao.cc:8081/api/dist');
//正式请求url
defined('ReqURL') or define('ReqURL', 'http://api.kdniao.cc/api/dist');

//调用获取物流轨迹
//-------------------------------------------------------------

$logisticResult = orderTracesSubByJson();
echo $logisticResult;

//-------------------------------------------------------------
 
/**
 * Json方式  物流信息订阅
 */
function orderTracesSubByJson(){
    $ShipperCode = $_REQUEST['ShipperCode'];
    $LogisticCode = $_REQUEST['LogisticCode'];
    $OrderCode = $_REQUEST['OrderCode']; 
//	$requestData="{'OrderCode': 'SF201608081055208281',".
//			   "'ShipperCode':'STO',".
//			   "'LogisticCode':'3344055674255',".
//			   "'PayType':1,".
//			   "'ExpType':1,".
//			   "'IsNotice':0,".
//			   "'Cost':1.0,".
//			   "'OtherCost':1.0,".
//			   "'Sender':".
//			   "{".
//			   "'Company':'LV','Name':'Taylor','Mobile':'15018442396','ProvinceName':'上海','CityName':'上海','ExpAreaName':'青浦区','Address':'明珠路73号'},".
//			   "'Receiver':".
//			   "{".
//			   "'Company':'GCCUI','Name':'Yann','Mobile':'15018442396','ProvinceName':'北京','CityName':'北京','ExpAreaName':'朝阳区','Address':'三里屯街道雅秀大厦'},".
//			   "'Commodity':".
//			   "[{".
//			   "'GoodsName':'鞋子','Goodsquantity':1,'GoodsWeight':1.0}],".
//			   "'Weight':1.0,".
//			   "'Quantity':1,".
//			   "'Volume':0.0,".
//			   "'Remark':'小心轻放'}";
	
	$requestData="{'ShipperCode':'".$ShipperCode."','LogisticCode':'".$LogisticCode."','OrderCode': '".$OrderCode."'}";
	$datas = array(
        'EBusinessID' => EBusinessID,
        'RequestType' => '1008',
        'RequestData' => urlencode($requestData) ,
        'DataType' => '2',
    );
    $datas['DataSign'] = encrypt($requestData, AppKey);
	$result=sendPost(ReqURL, $datas);	
	
	//根据公司业务处理返回的信息......
	
	return $result;
}

/**
 *  post提交数据 
 * @param  string $url 请求Url
 * @param  array $datas 提交的数据 
 * @return url响应返回的html
 */
function sendPost($url, $datas) {
    $temps = array();	
    foreach ($datas as $key => $value) {
        $temps[] = sprintf('%s=%s', $key, $value);		
    }	
    $post_data = implode('&', $temps);
    $url_info = parse_url($url);
	if(empty($url_info['port']))
	{
		$url_info['port']=80;	
	}
    $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
    $httpheader.= "Host:" . $url_info['host'] . "\r\n";
    $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
    $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
    $httpheader.= "Connection:close\r\n\r\n";
    $httpheader.= $post_data;
    $fd = fsockopen($url_info['host'], $url_info['port']);
    fwrite($fd, $httpheader);
    $gets = "";
	$headerFlag = true;
	while (!feof($fd)) {
		if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
			break;
		}
	}
    while (!feof($fd)) {
		$gets.= fread($fd, 128);
    }
    fclose($fd);  
    
    return $gets;
}

/**
 * 电商Sign签名生成
 * @param data 内容   
 * @param appkey Appkey
 * @return DataSign签名
 */
function encrypt($data, $appkey) {
    return urlencode(base64_encode(md5($data.$appkey)));
}

?>


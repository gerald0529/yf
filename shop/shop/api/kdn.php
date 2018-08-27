<?php

/* 
 *快递鸟接收推送
 * 
 * 
 */

require_once '../configs/config.ini.php';

//$appid = Web_ConfigModel::value('kuaidiniao_e_business_id');
//$appkey = Web_ConfigModel::value('kuaidiniao_app_key');
//需要设置到配置中去
$appid = 1310531;
$appkey = '8364c7b3-3ebf-4ae3-a8e5-f2735d875614';
//RequestData	String	请求内容需进行URL(utf-8)编码。请求内容只支持JSON格式。	R
//RequestType	String	101-轨迹查询结果, 107-货款状态	R
//DataSign	String	数据内容签名（把(请求内容(未编码)+AppKey)进行MD5加密，然后Base64编码）
 
$RequestData = urldecode($_REQUEST['RequestData']);
$RequestType = $_REQUEST['RequestType'];
$DataSign = $_REQUEST['DataSign'];

//验证
if(!checkSign($appkey,$RequestData,$DataSign)){
    show_msg($appid,  false, '验证失败');
}

//入库
$pushData = json_decode($RequestData,true);

$ExpressDataModel = new ExpressDataModel();
$rows = [];
$data = [];
$fail_count = 0;
$success_count = 0;
foreach ($pushData['Data'] as $key => $value){
    $data['EBusinessID'] = $pushData['EBusinessID'];
    $data['Count'] = $pushData['Count'];
    $data['PushTime'] = $pushData['PushTime'];
    $data['data'] = $value;
    $rows['logistic_code'] = $value['LogisticCode'];
    $rows['shipper_code'] = $value['ShipperCode'];
    $rows['data'] = $data;
    
    $result = $ExpressDataModel->selectToaddOredit(array('logistic_code'=>$value['LogisticCode'],'shipper_code'=>$value['ShipperCode']),$rows);
    if($result === false){
        Yf_Log::log("信息写入失败:  data:". json_encode($rows), Yf_Log::INFO, 'kdnPush'.date('Ymd'));
        $fail_count ++ ;
    }else{
        $success_count ++;
    }
}
show_msg($appid,  true, '成功'.$success_count.'条数据，失败'.$fail_count.'条数据');

/**
 * 检查签名
 * @param type $RequestData
 * @param type $appkey
 * @param type $DataSign
 * @return boolean
 */
function checkSign($appkey, $RequestData, $DataSign) {
    $sign = encrypt($RequestData, $appkey);
    if($sign == $DataSign){
        return true;
    }else{
        Yf_Log::log("验证失败: appkey:".$appkey." || sign1:".$sign." || sign2:".$DataSign." || data:".$RequestData, Yf_Log::INFO, 'kdnPush'.date('Ymd'));
        return false;
    }
}

/**
 * 加密
 * @param type $data
 * @param type $appkey
 * @return type
 */
function encrypt($data, $appkey) {
    return base64_encode(md5($data.$appkey));
}


/**
 * 输出数据
 * @param type $msg
 * @param type $code
 * @param type $status
 */
function show_msg($EBusinessID,$status,$reason){
    $data = array('EBusinessID'=>$EBusinessID,'UpdateTime'=>date('Y-m-d H:i:s'),'Success'=>$status,'Reason'=>$reason);
    echo json_encode($data);
    exit;
}
<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 *
 *
 Kuaidi::find(['num'=>'单号','type'=>'物流公司代码']);
 
 返回数据如下。

 Array
(
    [0] => Array
        (
            [time] => 2017-12-25 08:57:20
            [status] => [上海市] 九亭（本部）安排投递，预计13:00:00前投递（投递员姓名：韩传奇;联系电话：18019796563）
        )

    [1] => Array
        (
            [time] => 2017-12-23 06:35:48
            [status] => [上海市] 离开上海处理中心 发往九亭（本部）
        )

    [2] => Array
        (
            [time] => 2017-12-22 18:49:52
            [status] => [上海市] 张江(本部)已收件（揽投员姓名：龚惠彬,联系电话:18301907537）
        )

)

 * 
 */
class Kuaidi 
{
	/**
	 * Kuaidi::find(['num'=>'单号','type'=>'物流公司代码']);
	 *
	 * type YUNDA EMS SFEXPRESS
	 *  //YUNDA  1202516745301
	 *	//SFEXPRESS  198095604180
	 *	//EMS 1001412553592 
	 * @param   数组        $arr
	 *  
	 */
	 static function find($arr = []){
	 	$num = $arr['num'];
	 	$type = $arr['type']?:'SFEXPRESS'; 
	 	$cacheKey = 'kuaidi_'.$num.$type; 
	 	$Cache = Yf_Cache::create('verify_code');
   		if ($list = $Cache->get($cacheKey))
   		{
     		return $list;
    	}
  		 
		$host = "http://jisukdcx.market.alicloudapi.com";
		$path = "/express/query";
		$method = "GET";
		//$appcode = "8c203cefc4a64031812ce4946755481d";
		 $appcode = Web_ConfigModel::value('kuaidi_app_code');

		$headers = array();
		array_push($headers, "Authorization:APPCODE " . $appcode);
		$querys = "number=".$num."&type=".$type;
		$bodys = "";
		$url = $host . $path . "?" . $querys; 
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_FAILONERROR, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		if (1 == strpos("$".$host, "https://"))
		{
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		}
		$data = curl_exec($curl); 
	    $data = json_decode($data,true);
	    if($data['status'] == 0){
	    	$list = $data['result']['list'];
	    	$Cache->save($list, $cacheKey);
	    	return $list;
	    }
	    $Cache->save("###", $cacheKey);
	    return false;
	 }
}

 
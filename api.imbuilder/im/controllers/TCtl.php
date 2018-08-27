<?php if (!defined('ROOT_PATH')) exit('No Permission');
 


class TCtl extends Yf_AppController
{
	public $URL = 'http://api.fanyi.baidu.com/api/trans/vip/translate'; 
	public $APP_ID = "20170518000048355";
	public $SEC_KEY = "i2cDIeJFAjN4rotoh2pr";
	public function index()
	{

		$str = $_GET['str'];
		$to = $_GET['to']?:'cht';
		$wd =  @$this->baidu_translate($str,$to)['trans_result'][0]['dst'] ; 
	 
		exit(json_encode(['status'=>200,'data'=>$wd]));
	}




	//翻译入口
	public function baidu_translate($query, $to = 'cht', $from = 'auto')
	{
	    $args = array(
	        'q' => $query,
	        'appid' => $this->APP_ID,
	        'salt' => rand(10000,99999),
	        'from' => $from,
	        'to' => $to,

	    );

	    
	    $args['sign'] = $this->baidu_buildSign($query, $this->APP_ID, $args['salt'], $this->SEC_KEY);
	    $ret = $this->baidu_call($this->URL, $args);
	    $ret = json_decode($ret, true);
	    if($ret['error_msg']){
	    	exit(json_encode(['status'=>500,'data'=>$ret['error_msg'] ]));
	    	 
	    }
	    
	    return $ret; 
	}

	//加密
	public function baidu_buildSign($query, $appID, $salt, $secKey)
	{/*{{{*/
	    $str = $appID . $query . $salt . $secKey;
	    $ret = md5($str);
	    return $ret;
	}/*}}}*/

	//发起网络请求
	public function baidu_call($url, $args=null, $method="post", $testflag = 0, $timeout = 5, $headers=array())
	{/*{{{*/
	    $ret = false;
	    $i = 0; 
	    while($ret === false) 
	    {
	        if($i > 1)
	            break;
	        if($i > 0) 
	        {
	            sleep(1);
	        }
	        $ret = $this->baidu_callOnce($url, $args, $method, false, $timeout, $headers);
	        $i++;
	    }
	    return $ret;
	}/*}}}*/

	public function baidu_callOnce($url, $args=null, $method="post", $withCookie = false, $timeout = CURL_TIMEOUT, $headers=array())
	{/*{{{*/
	    $ch = curl_init();
	    if($method == "post") 
	    {
	        $data = $this->baidu_convert($args);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	        curl_setopt($ch, CURLOPT_POST, 1);
	    }
	    else 
	    {
	        $data = $this->baidu_convert($args);
	        if($data) 
	        {
	            if(stripos($url, "?") > 0) 
	            {
	                $url .= "&$data";
	            }
	            else 
	            {
	                $url .= "?$data";
	            }
	        }
	    }
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    if(!empty($headers)) 
	    {
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    }
	    if($withCookie)
	    {
	        curl_setopt($ch, CURLOPT_COOKIEJAR, $_COOKIE);
	    }
	    $r = curl_exec($ch);
	    curl_close($ch);
	    return $r;
	}/*}}}*/

	public function baidu_convert(&$args)
	{/*{{{*/
	    $data = '';
	    if (is_array($args))
	    {
	        foreach ($args as $key=>$val)
	        {
	            if (is_array($val))
	            {
	                foreach ($val as $k=>$v)
	                {
	                    $data .= $key.'['.$k.']='.rawurlencode($v).'&';
	                }
	            }
	            else
	            {
	                $data .="$key=".rawurlencode($val)."&";
	            }
	        }
	        return trim($data, "&");
	    }
	    return $args;
	}

	 
}
 
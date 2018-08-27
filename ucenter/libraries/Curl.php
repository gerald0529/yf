<?php
/*
 * @Copyright (c) 2007,黄新泽
 * @All	rights reserved.
 *
 * 
 *
 * @filename   Curl.class.php
 * @category   
 * @package    
 * @author     Xinze <xinze@live.cn>
 * @date       2009-06-21 13:39:54
 */
/**
* Class and Function List:
* Function list:
* - __construct()
* - init()
* - setHttp()
* - setReferer()
* - setUserAgent()
* - setHeader()
* - setProxy()
* - sendPostData()
* - sendGetData()
* - storeCookies()
* - setCookie()
* - get_effective_url()
* - get_http_response_code()
* - get_error_msg()
* - exec()
* - close()
* Classes list:
* - Curl
*/

class Curl
{
    //Curl 处理对象
    private $ch;

    //设置 debug 开关
    private $debug;
    
    function __construct($debug = false) 
    {
        $this->debug = $debug;
        $this->init();
    }

    //初始化CURL
    public function init() 
    {
        // 初始化CURL
        $this->ch = curl_init();
        //设定错误大于300的情况下的http返回代码
        curl_setopt($this->ch, CURLOPT_FAILONERROR, true);
        //允许重定向
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        //使用gzip如果可能的话
        curl_setopt($this->ch, CURLOPT_ENCODING, 'gzip, deflate');
        //SSL安全
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);

		//curl_setopt($this->ch,CURLOPT_COOKIESESSION,true); //能保存cookie 

		//curl_setopt($this->ch, CURLOPT_FRESH_CONNECT, 1); //接强制获取一个新的连接，替代缓存中的连接。
    }

    /**
     * 设置基本的http认证
     * @param string User_Base
     * @param string pass
     */
    public function setHttp($username, $password) 
    {
        curl_setopt($this->ch, CURLOPT_USERPWD, "$username:$password");
    }

    /**
     * 设置来源
     * @param string referrer url
     */
    public function setReferer($referer_url) 
    {
        curl_setopt($this->ch, CURLOPT_REFERER, $referer_url);
    }

    /**
     * 设置客户端的用户代理
     * @param string User_Base agent
     */
    public function setUserAgent($useragent) 
    {
        curl_setopt($this->ch, CURLOPT_USERAGENT, $useragent);
    }

    /**
     * 设置头部信息
     */
    public function setHeader($headers_defined=array() , $value=false) 
    {
        $headers_default = array(
            'Content-Type : application/x-www-form-urlencoded',
            'Accept: image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, application/x-shockwave-flash, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/msword, application/x-silverlight, */* ',
            'Accept-Language:   zh-cn',
            'Keep-Alive: 300 ',
            'Connection:   Keep-Alive '
        );
        $headers = array_merge($headers_defined, $headers_default);
        curl_setopt($this->ch, CURLOPT_HEADER, $value);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
    }

    /**
     * 设置头部信息
     */
    public function setNobody($nobody = true) 
    {
		curl_setopt($this->ch, CURLOPT_NOBODY, $nobody);
    }

    //设置代理
    public function setProxy($proxy) 
    {
        curl_setopt($this->ch, CURLOPT_PROXY, $proxy);
    }

    // 发送POST数据到目标网址
    // param mixed post data (assoc array ie. $foo['post_var_name'] = $value or as string like var=val1&var2=val2)
    public function sendPostData($url, $postdata, $is_returntransfer = true, $ip = null, $conntimeout = 30, $timeout = 30) 
    {
        // 设置提交地址
        curl_setopt($this->ch, CURLOPT_URL, $url);
        // 反回一个变量
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, $is_returntransfer);
        // 绑定到特定的IP地址
        
        if ($ip) 
        {
            
            if ($this->debug) 
            {
                echo "Binding to ip $ip\n";
            }

            curl_setopt($this->ch, CURLOPT_INTERFACE, $ip);
        }
        // 设置超时时间
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $conntimeout);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);
        // 设置POST方式提交数据
        curl_setopt($this->ch, CURLOPT_POST, true);
        // 生成POST数据
        $post_array = array();
        
        if (is_array($postdata)) 
        {
            
            foreach($postdata as $key => $value) 
            {
                $post_array[] = urlencode($key) . "=" . urlencode($value);
            }
            $post_string = implode("&", $post_array);
            
            if ($this->debug) 
            {
                echo "Url: $url\nPost String: $post_string\n";
            }
        }
        else
        {
            $post_string = $postdata;
        }
        // 开始提交
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post_string);
    }

    // 从目标地址采集数据
    public function sendGetData($url, $is_returntransfer = true, $ip = null, $conntimeout = 30, $timeout = 30) 
    {
        // 设置采集地址
        curl_setopt($this->ch, CURLOPT_URL, $url);

        // 设置提交数据为GET方式
        curl_setopt($this->ch, CURLOPT_HTTPGET,true);
        // 反回变量
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, $is_returntransfer);
        //绑定一个IP地址
        
        if ($ip) 
        {
            
            if ($this->debug) 
            {
                echo "Binding to ip $ip\n";
            }
            curl_setopt($this->ch, CURLOPT_INTERFACE, $ip);
        }

        //设置超时时间
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $conntimeout);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);
        //发送信息
        
    }

  /**
   * Fetch data from target URL
   * and store it directly to file
   * @param string url
   * @param resource value stream resource(ie. fopen)
   * @param string ip address to bind (default null)
   * @param int timeout in sec for complete curl operation (default 5)
   * @return boolean true on success false othervise
   * @access public
   */
  function fetchIntoFile($url, $fp, $ip = null, $timeout = 5)
  {
    // set url to post to
    curl_setopt($this->ch, CURLOPT_URL, $url);
    //set method to get
    curl_setopt($this->ch, CURLOPT_HTTPGET, true);
    // store data into file rather than displaying it
    curl_setopt($this->ch, CURLOPT_FILE, $fp);
    //bind to specific ip address if it is sent trough arguments
    if ($ip)
    {
      if ($this->debug)
      {
        echo"Binding to ip $ip\n";
      }
      curl_setopt($this->ch, CURLOPT_INTERFACE, $ip);
    }
    //set curl function timeout to $timeout
    curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);

  }
	/**
	 * Send multipart post data to the target URL
	 * return data returned from url or false if error occured
	 * (contribution by vule nikolic, [email=vule@dinke.net]vule@dinke.net[/email])
	 * @param string url
	 * @param array assoc post data array ie. $foo['post_var_name'] = $value
	 * @param array assoc $file_field_array, contains file_field name = value - path pairs
	 * @param string ip address to bind (default null)
	 * @param int timeout in sec for complete curl operation (default 30 sec)
	 * @return string data
	 * @access public
	 */
	function sendMultipartPostData($url, $postdata, $file_field_array = array()
		, $ip = null, $timeout = 30)
	{
		//set various curl options first
		// set url to post to
		curl_setopt($this->ch, CURLOPT_URL, $url);
		// return into a variable rather than displaying it
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		//bind to specific ip address if it is sent trough arguments
		if ($ip)
		{
		if ($this->debug)
		{
			echo"Binding to ip $ip\n";
		}
		curl_setopt($this->ch, CURLOPT_INTERFACE, $ip);
		}
		//set curl function timeout to $timeout
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);
		//set method to post
		curl_setopt($this->ch, CURLOPT_POST, true);
		// disable Expect header
		// hack to make it working
		$headers = array("Expect: ");
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
		// initialize result post array
		$result_post = array();
		//generate post string
		$post_array = array();
		$post_string_array = array();
		if (!is_array($postdata))
		{
		return false;
		}
		foreach($postdata as $key => $value)
		{
		$post_array[$key] = $value;
		$post_string_array[] = urlencode($key)."=".urlencode($value);
		}
		$post_string = implode("&", $post_string_array);

		if ($this->debug)
		{
		echo"Post String: $post_string\n";
		}
		// set post string
		//curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post_string);

		// set multipart form data - file array field-value pairs
		if (!empty($file_field_array))
		{
		foreach($file_field_array as $var_name => $var_value)
		{
			if (strpos(PHP_OS, "WIN") !== false)
			$var_value = str_replace("/", "\\", $var_value);
			// win hack
			$file_field_array[$var_name] = "@".$var_value;
		}
		}
		// set post data
		$result_post = array_merge($post_array, $file_field_array);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $result_post);


	}
	/**
	 * Set file location where cookie data will be stored and send on each new request
	 * @param string absolute path to cookie file (must be in writable dir)
	 * @access public
	 */
    // 设置文件位置的Cookie数据将被储存和发送的每一个新的要求
    public function storeCookies($cookie_file) //服务器返回的新cookie
    {
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt ($this->ch, CURLOPT_COOKIEFILE, $cookie_file);
    }

	/**
	 * Set file location where cookie data will be stored and send on each new request
	 * @param string absolute path to cookie file (must be in writable dir)
	 * @access public
	 */
    // 设置文件位置的Cookie数据将被储存和发送的每一个新的要求
    public function oldCookies($cookie_file)   //当前使用的cookie
    {
        curl_setopt ($this->ch, CURLOPT_COOKIEFILE, $cookie_file);
		//curl_setopt($this->ch, CURLOPT_COOKIEJAR, $cookie_file);
        
    }


    // 设置自定义的cookie
    public function setCookie($cookie) //当前使用的cookie
    {
        curl_setopt($this->ch, CURLOPT_COOKIE, $cookie);
    }
    // 取得重定向后的地址
    
    function get_effective_url() 
    {
        return curl_getinfo($this->ch, CURLINFO_EFFECTIVE_URL);
    }

    // 取得HTTP响应码
    public function get_http_response_code() 
    {
        
        return curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
    }
    // 取得最后的错误 信息和代码
    public function get_error_msg() 
    {
		//$err = "Error number: ".curl_errno($this->ch)."\n";
		//$err .= "Error message: ".curl_error($this->ch)."\n";
		//return $err;
			
        return curl_error($this->ch);
    }

    // 最终结果
    public function exec() 
    {
        //最终发送请求
        $result = curl_exec($this->ch);

        if (curl_errno($this->ch)) 
        {
            
            if ($this->debug) 
            {
                echo "Error Occured in Curl\n";
                echo "Error number: " . curl_errno($this->ch) . "\n";
                echo "Error message: " . curl_error($this->ch) . "\n";
            }
            
            return false;
        }
        else
        {
            
            return $result;
        }
    }

    // 关闭CURL资源
    public function close() 
    {
        curl_close($this->ch);
    }
}
?>
<?php
/**
 * 框架初始化文件
 *
 * 将一些处理方式固定化，例如，统一魔术引用
 *
 * @category   Framework
 * @package    __init__
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo
 */
if (!is_php('5'))
{
	die('Zero Framework runtime environment required PHP version higher than 5.0！');
}

if (!defined('SAPI'))
{
	define('SAPI', php_sapi_name());
}

//设置转义信息
//如果<5.3,运行。
if (!is_php('5.3'))
{
	ini_set('magic_quotes_runtime', 0);
}

@ini_set('magic_quotes_sybase', 0);

Yf_Registry::set('magic_quotes_gpc', get_magic_quotes_gpc());
Yf_Registry::set('magic_quotes_runtime', get_magic_quotes_runtime());

if (!defined('LIB_PATH'))
{
	die('请先定义框架地址：LIB_PATH');
}

//set_include_path(get_include_path() . PATH_SEPARATOR . LIB_PATH);
set_include_path(LIB_PATH . PATH_SEPARATOR . MOD_PATH);


function copy_dir($source, $destination)
{
	if (is_dir($source) == false)
	{
		exit("The Source Directory Is Not Exist!");
	}
	
	if (is_dir($source) !== false)
	{
		mkdir($destination, 0777);
	}
	
	$handle = opendir($source);
	
	while (false !== ($file = readdir($handle)))
	{
		if ($file != "." && $file != ".." && $file != ".svn")
		{
			is_dir("$source/$file") ? copy_dir("$source/$file", "$destination/$file") : copy("$source/$file", "$destination/$file");
		}
	}
	
	closedir($handle);
}

//国际化语言设置
/*
$Translate = new Zend_Translate('gettext', APP_PATH . '/data/locales/', 'zh_CN', array(
    'scan' =>Zend_Translate::LOCALE_DIRECTORY
));

//$Translate->setLocale('auto');
$Translate->setLocale('zh_CN');
*/

//初始化语言包
if (function_exists('_'))
{
	//init_locale(APP_PATH . '/data/locales/', 'zh_CN', 'HelloWorld');
	/*
	$path    = APP_PATH . '/data/locales/';
	$domain  = 'HelloWorld';
	$codeset = 'UTF-8';
	$lang    = 'zh_CN.UTF-8';

	new Yf_Locale($path, $domain, $codeset, $lang);
	*/
}
else
{
	function _($str)
	{
		return $str;
	}
}

//$Translate = new Lable();

/**
 * Determines if the current version of PHP is greater then the supplied value
 *
 * Since there are a few places where we conditionally test for PHP > 5
 * we'll set a static variable.
 *
 * @access  public
 * @param   string
 * @return  bool
 */
function is_php($version = '5.0.0')
{
	static $_is_php;
	$version = (string)$version;
	
	if (!isset($_is_php[$version]))
	{
		$_is_php[$version] = (version_compare(PHP_VERSION, $version) < 0) ? FALSE : TRUE;
	}
	
	return $_is_php[$version];
}

/**
 * 判断操作系统
 */
function  get_sys()
{
	return substr(PHP_OS, 0, 3);
}

function encode_json($rows)
{
	//return json_encode($rows, JSON_UNESCAPED_UNICODE);
    if(!$rows['data']){
        return json_encode($rows,JSON_FORCE_OBJECT);
    }else{
        return json_encode($rows);
    }
}

function decode_json($rows, $assoc = true)
{
	return json_decode($rows, $assoc);
}


/**
 * 转义字符函数
 *
 * @param mixed $content contents should be addslashes
 *
 * @return mixed  $content
 *
 */
function quotes(&$content)
{
	if (is_array($content))
	{
		foreach ($content as $key => $value)
		{
			$content[$key] = quotes($value);
		}
	}
	else
	{
		$content = addslashes($content);
	}
	
	return $content;
}

function unquotes(&$content)
{
	if (is_array($content))
	{
		foreach ($content as $key => $value)
		{
			$content[$key] = unquotes($value);
		}
	}
	else
	{
		$content = stripslashes($content);
	}
	
	return $content;
}


function mres($str)
{
	$str = addslashes($str);
	//$str = str_replace('_', '\_', $str); //转义掉”_”
	//$str = str_replace('%', '\%', $str); //转义掉”%”
	
	//return addslashes($str);
	//return mysql_real_escape_string($str);
	
	return $str;
}

function addslashes_array($array)
{
	$array = is_array($array) ? array_map('addslashes_array', $array) : addslashes($array);
	
	return $array;
}

function stripslashes_array($array)
{
	$array = is_array($array) ? array_map('stripslashes_array', $array) : stripslashes($array);
	
	return $array;
}

function untrim($val)
{
	if (!is_numeric($val))
	{
		$val = '"' . mres($val) . '"';
	}
	
	return $val;
}

function format_update_sql(&$item1, $key)
{
	$item1 = $key . '=' . '"' . $item1 . '"';
}


//$fruits = array("d" => "lemon", "a" => "orange", "b" => "banana", "c" => "apple");

//array_walk($fruits, 'formatUpdateSql');
//implode(',', $fruits)
function array_values_recursive($arr)
{
	$arr = array_values($arr);
	foreach ($arr as $key => $val)
	{
		if (is_array($val))
		{
			$arr[$key] = array_values_recursive($val);
		}
	}
	
	return $arr;
}

//如果使用zend, 则关掉此处
function __autoload($class_name)
{
	if (!class_exists($class_name, false) && !interface_exists($class_name, false))
	{
		if (false !== strpos($class_name, '_'))
		{
			$class_file_suffix = '/' . str_replace('_', '/', $class_name) . '.php';
		}
		else if (false !== strpos($class_name, '\\'))
		{
			$class_file_suffix = '/' . str_replace('\\', '/', $class_name) . '.php';
		}
		else
		{
			$class_file_suffix = '/' . $class_name . '.php';
		}
		
		$class_file_path = MOD_PATH . $class_file_suffix;
		
		if (is_file($class_file_path))
		{
			import($class_file_path);
		}
		else
		{
			$class_file_path = LIB_PATH . $class_file_suffix;
			
			if (is_file($class_file_path))
			{
				import($class_file_path);
			}
			else
			{
				$class_file_path = CTL_PATH . $class_file_suffix;
				
				if (is_file($class_file_path))
				{
					import($class_file_path);
				}
				else
				{
					$class_file_path = LIB_PATH . '/vendor' . $class_file_suffix;
					
					if (is_file($class_file_path))
					{
						import($class_file_path);
					}
				}
			}
		}
		
		if (!class_exists($class_name, false) && !interface_exists($class_name, false))
		{
			//throw new Exception('Class ' . $class_name . ' does not exists : ' . $class_file_suffix);
			error_header(404, 'Page Not Found');
			//echo 'Class ' . $class_name . ' does not exists : ' . $class_file_suffix;
			throw new Exception('Class ' . $class_name . ' does not exists : ' . $class_file_suffix);
		}
	}
}

function import($file_path = null, $flag = true)
{
	global $import_file_row;
	
	array_unshift($import_file_row, $file_path);
	
	if ($flag)
	{
		include_once $file_path;
	}
}

if (!function_exists('clean_cache'))
{
	function clean_cache($dir = null, $del_dir = null)
	{
		if (is_dir($dir))
		{
			$dh = opendir($dir);
			
			while (false !== ($f = readdir($dh)))
			{
				if ($f == '.' || $f == '..')
				{
					continue;
				}
				elseif (is_dir($dir . '/' . $f))
				{
					clean_cache($dir . '/' . $f, $del_dir);
				}
				else
				{
					unlink($dir . '/' . $f);
				}
			}
			
			closedir($dh);
			
			if ($del_dir && 'cache' != $dir)
			{
				rmdir($dir);
			}
			
			return true;
		}
		else
		{
			return false;
		}
	}
}

if (is_php('5'))
{
	function make_dir_path($path)
	{
		if (!file_exists($path))
		{
			mkdir($path, 0777, true);
		}
	}
}
else
{
	function make_dir_path($path)
	{
		if (!file_exists($path))
		{
			make_dir_path(dirname($path));
			mkdir($path, 0777);
		}
	}
}


function l()
{
	if (!preg_match_all('/\sFirePHP\/([\.|\d]*)\s?/si', $_SERVER['HTTP_USER_AGENT'], $m) || !version_compare($m[1][0], '0.0.6', '>='))
	{
		$Logger = Log::singleton('file', APP_PATH . '/data/logs/db.log', '', array(), PEAR_LOG_DEBUG);
		
		$content = func_get_args();
		$len     = count($content) - 1;
		
		for ($i = $len; $i >= 0; $i--)
		{
			$Logger->log($content[$i]);
		}
	}
	else
	{
		$FirePHP = new FirePHP();
		
		if ($FirePHP)
		{
			$instance = $FirePHP->getInstance(true);
			$args     = func_get_args();
			return call_user_func_array(array($instance, 'fb'), $args);
		}
	}
}

function file_log()
{
	$Logger = Log::singleton('file', APP_PATH . '/data/logs/player_info.log', '', array(), PEAR_LOG_DEBUG);
	
	$content = func_get_args();
	$len     = count($content);
	
	for ($i = 0; $i < $len; $i++)
	{
		$str = "";
		
		if (is_array($content[$i]))
		{
			$str = encode_json($content[$i]);
		}
		else
		{
			$str = $content[$i];
		}
		
		$Logger->log($str);
		
	}
	
}


if (DEBUG)
{
	//是否启用php console
	$is_active_client = PhpConsole\Connector::getInstance()->isActiveClient();
	
	if ($is_active_client)
	{
		$handler = PhpConsole\Handler::getInstance();
		/* You can override default Handler behavior:
		$handler->setHandleErrors(false); // disable errors handling
		$handler->setHandleExceptions(false); // disable exceptions handling
		$handler->setCallOldHandlers(false); // disable passing errors & exceptions to prviously defined handlers */
		$handler->start(); // initialize handlers
	}
}

function fb()
{
	if ('cli' == SAPI)
	{
		return;
		//print_r(func_get_args());
		$content = func_get_args();
		$len     = count($content) - 1;
		for ($i = $len; $i >= 0; $i--)
		{
			print_r($content[$i]);
		}
		
		echo "\n";
	}
	else
	{
		if (!isset($_SERVER['HTTP_USER_AGENT']))
		{
			return;
		}
		
		if (!preg_match_all('/\sFirePHP\/([\.|\d]*)\s?/si', $_SERVER['HTTP_USER_AGENT'], $m) || !version_compare($m[1][0], '0.0.6', '>='))
		{
			if (DEBUG)
			{
				//是否启用php console
				$is_active_client = PhpConsole\Connector::getInstance()->isActiveClient();
				
				if ($is_active_client)
				{
					//PhpConsole\Handler::getInstance()->debug($info, $name);  //数组 autoload bug
					
					$content = func_get_args();
					
					$type = @$content[3];
					$name = @$content[1];
					$info = $content[0];
					
					if (!$name)
					{
						$name = 'default';
					}
					
					PhpConsole\Handler::getInstance()->debug($info, $name);
				}
			}
		}
		else
		{
			$FirePHP = new FirePHP();
			if ($FirePHP)
			{
				$instance = $FirePHP->getInstance(true);
				$args     = func_get_args();
				return call_user_func_array(array($instance, 'fb'), $args);
			}
		}
	}
	
	return true;
}
//数字转大写
function get_amount($num){
	$c1 = "零壹贰叁肆伍陆柒捌玖";
	$c2 = "分角元拾佰仟万拾佰仟亿";
	$num = round($num, 2);
	$num = $num * 100;
	if (strlen($num) > 10) {
		return "数据太长，没有这么大的钱吧，检查下";
	}
	$i = 0;
	$c = "";
	while (1) {
		if ($i == 0) {
			$n = substr($num, strlen($num)-1, 1);
		} else {
			$n = $num % 10;
		}
		$p1 = substr($c1, 3 * $n, 3);
		$p2 = substr($c2, 3 * $i, 3);
		if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
			$c = $p1 . $p2 . $c;
		} else {
			$c = $p1 . $c;
		}
		$i = $i + 1;
		$num = $num / 10;
		$num = (int)$num;
		if ($num == 0) {
			break;
		}
	}
	$j = 0;
	$slen = strlen($c);
	while ($j < $slen) {
		$m = substr($c, $j, 6);
		if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
			$left = substr($c, 0, $j);
			$right = substr($c, $j + 3);
			$c = $left . $right;
			$j = $j-3;
			$slen = $slen-3;
		}
		$j = $j + 3;
	}
	
	if (substr($c, strlen($c)-3, 3) == '零') {
		$c = substr($c, 0, strlen($c)-3);
	}
	if (empty($c)) {
		return "零元整";
	}else{
		return $c . "整";
	}
}


function get_ip()
{
	if (getenv('HTTP_CLIENT_IP'))
	{
		$ip = getenv('HTTP_CLIENT_IP');
	}
	elseif (getenv('HTTP_X_FORWARDED_FOR'))
	{
		$ip = getenv('HTTP_X_FORWARDED_FOR');
	}
	elseif (getenv('HTTP_X_FORWARDED'))
	{
		$ip = getenv('HTTP_X_FORWARDED');
	}
	elseif (getenv('HTTP_FORWARDED_FOR'))
	{
		$ip = getenv('HTTP_FORWARDED_FOR');
	}
	elseif (getenv('HTTP_FORWARDED'))
	{
		$ip = getenv('HTTP_FORWARDED');
	}
	else
	{
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	
	return $ip;
}

function ceil_r($num)
{
	return ceil(round($num, 10));
}

function floor_r($num)
{
	return floor(round($num, 10));
}

/**
 * 取得当前时间
 *
 * @return  int $time 是否成功
 *
 */
function get_time()
{
	if ('cli' == SAPI)
	{
		$time = time();
	}
	else
	{
		if (isset($_SERVER['REQUEST_TIME']))
		{
			$time = $_SERVER['REQUEST_TIME'];
		}
		else
		{
			$time = time();
		}
	}
	
	return $time;
}

function get_date_time()
{
	return date("Y-m-d H:i:s");
}

function get_days($start_time, $end_time)
{
	return (strtotime(date("Y-m-d", $end_time)) - strtotime(date("Y-m-d", $start_time))) / 86400 + 1;
}

/**
 * 取得执行结果
 *
 * @return  array   $rs_row             是否成功
 */
function is_ok(&$rs_row = array())
{
	$rs = true;
	
	if (in_array('0', $rs_row) || in_array(false, $rs_row) || in_array('', $rs_row))
	{
		$rs = false;
	}
	
	return $rs;
}

/**
 * 判断结果是否为false
 *
 * @return  array   $rs_row             是否成功
 */
function check_rs($flag, &$rs_row)
{
	if (false === $flag)
	{
		$rs_row[] = false;
	}
	else
	{
		$rs_row[] = true;
	}
	
	return $flag;
}


/**
 * 本地化  I18N 程序范例开始
 *
 *
 * @param string $lan_path 设置某个域的mo文件路径
 * @param string $lang bsd use zh_CN.UTF-8
 * @param string $domain 定义要用的mo文件名称，常规来说，我们都把PACKAGE的名称定义和程序名称相同。
 * @return void
 */
function init_locale($lan_path, $lang, $domain)
{
	setlocale(LC_ALL, $lang);   //// bsd use zh_CN.UTF-8
	
	bindtextdomain($domain, $lan_path); //设置某个域的mo文件路径
	bind_textdomain_codeset($domain, 'UTF-8'); //设置mo文件的编码为UTF-8
	textdomain($domain); //设置gettext()函数从哪个域去找mo文件
}


if (!function_exists('get_called_class'))
{
	function get_called_class()
	{
		$bt = debug_backtrace();
		$l  = 0;
		do
		{
			$l++;
			$lines      = file($bt[$l]['file']);
			$callerLine = $lines[$bt[$l]['line'] - 1];
			preg_match('/([a-zA-Z0-9\_]+)::' . $bt[$l]['function'] . '/', $callerLine, $matches);
			
			if ($matches[1] == 'self')
			{
				$line = $bt[$l]['line'] - 1;
				
				while ($line > 0 && strpos($lines[$line], 'class') === false)
				{
					$line--;
				}
				preg_match('/class[\s]+(.+?)[\s]+/si', $lines[$line], $matches);
			}
		} while ($matches[1] == 'parent' && $matches[1]);
		
		return $matches[1];
	}
}

/**
 * 通知客户端错误原因
 *
 * @param  int $error_no
 * @param  string $error_msg
 */
function error_header($error_no, $error_msg)
{
	header('HTTP/1.0 ' . $error_no . ' ' . $error_msg);
}


/**
 * 通知客户端错误原因
 *
 * @param  int $error_no
 * @param  string $error_msg
 */
function location_to($url)
{
	header("Location:$url");
	die();
}

function location_go_back($msg='')
{
	die('<script>alert("' . $msg . '!");history.go(-1);</script>;');
}

//按二维数组指定属性排序
function array_sort($arr, $keys, $type = 'asc')
{
	$keysvalue = $new_array = array();
	foreach ($arr as $k => $v)
	{
		$keysvalue[$k] = $v[$keys];
	}
	
	if ($type == 'asc')
	{
		asort($keysvalue);
	}
	else
	{
		arsort($keysvalue);
	}
	
	reset($keysvalue);
	foreach ($keysvalue as $k => $v)
	{
		$new_array[$k] = $arr[$k];
	}
	
	return $new_array;
}

function array_reset($sort_key_row, $data_rows)
{
	$data_rows_new = array();
	
	if (($n = count($sort_key_row)) > 0)
	{
		switch ($n)
		{
			case 1:
				foreach ($data_rows as $key => $value)
				{
					$data_rows_new[$value[$sort_key_row[0]]] = $value;
				}
				break;
			case 2:
				foreach ($data_rows as $key => $value)
				{
					$data_rows_new[$value[$sort_key_row[0]]][$value[$sort_key_row[1]]] = $value;
				}
				break;
			case 3:
				foreach ($data_rows as $key => $value)
				{
					$data_rows_new[$value[$sort_key_row[0]]][$value[$sort_key_row[1]]][$value[$sort_key_row[2]]] = $value;
				}
				break;
			case 4:
				foreach ($data_rows as $key => $value)
				{
					$data_rows_new[$value[$sort_key_row[0]]][$value[$sort_key_row[1]]][$value[$sort_key_row[2]]][$value[$sort_key_row[3]]] = $value;
				}
				break;
		}
	}
	else
	{
		$data_rows_new = $data_rows;
	}
	
	return $data_rows_new;
}

function get_strlen($str)
{
	/*
	$strlen = strlen($str);
	$mb_strlen = mb_strlen($str, "utf8");

	return ($strlen - $mb_strlen)/2 + $mb_strlen;
	*/
	
	return mb_strlen($str, "utf8");
}

/*判断一个数字是否是整数
*@param $intNum 要判断的数字
*@param $scope 1 > 0 ,0 >= 0, -1 所有整数;
*/
function is_int_numeric($intNum, $scope = 1)
{
	if (is_numeric($intNum) && (round($intNum, 0)) == $intNum)//是整数
	{
		if ($scope == 1)
		{
			return $intNum > 0;
		}
		elseif ($scope == 0)
		{
			return $intNum >= 0;
		}
		elseif ($scope == -1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

//按值将元素从数组中移除
function array_remove_value($value, $arr_value)
{
	if (is_array($value))
	{
		foreach ($value as $k => $v)
		{
			$del_key = array_search($v, $arr_value);
			
			if (false !== $del_key)
			{
				unset($arr_value[$del_key]);
			}
		}
	}
	else
	{
		$del_key = array_search($value, $arr_value);
		
		if (false !== $del_key)
		{
			unset($arr_value[$del_key]);
		}
	}
	
	return $arr_value;
}

//将2个二维数组的值相加
function array_add($arr_first, $arr_second)
{
	foreach ($arr_second as $key => $value)
	{
		foreach ($value as $k => $v)
		{
			$arr_first[$key][$k] = (isset($arr_first[$key][$k]) ? $arr_first[$key][$k] : 0) + $v;
		}
	}
	
	return $arr_first;
}

//将2个二维数组的值相减
function array_sub($arr_first, $arr_second)
{
	foreach ($arr_second as $key => $value)
	{
		foreach ($value as $k => $v)
		{
			$arr_first[$key][$k] = (isset($arr_first[$key][$k]) ? $arr_first[$key][$k] : 0) - $v;
		}
	}
	
	return $arr_first;
}


function file_get_contents_time($url)
{
	$ctx = stream_context_create(array('http' => array('timeout' => 3 //设置一个超时时间，单位为秒
	)));
	
	return file_get_contents($url, 0, $ctx);
}

//二维数组
function array_filter_key($key, $data_rows)
{
	$data_rows_new = array();
	
	foreach ($data_rows as $row)
	{
		if (isset($row[$key]))
		{
			$data_rows_new[] = $row[$key];
		}
	}
	
	
	return $data_rows_new;
}

function get_url($url, $arr_param=array(), $typ='JSON', $method='POST', $sign_key='', $timeout=10, $curl_header=array())
{
	$params = '';
	
	if(is_array($arr_param))
	{
		if($arr_param)
		{
			$params = http_build_query($arr_param);
		}
	}
	else
	{
		$params = $arr_param;
	}
	
	
	$curl = curl_init();//初始化curl
	
	if ('get' == $method)//以GET方式发送请求
	{
		$pos = strpos($url, "?");
		
		if($pos === false)
		{
			$request_url = $url."?".$params;
		}
		else
		{
			$request_url = $url."&".$params;
		}
		
		curl_setopt($curl, CURLOPT_URL, $request_url);
	}
	else//以POST方式发送请求
	{
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, 1);//post提交方式
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);//设置传送的参数
	}
	
	if ($curl_header)
	{
		curl_setopt($curl,CURLOPT_HTTPHEADER, $curl_header);
	}
	
	//curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers_login );
	curl_setopt($curl, CURLOPT_HEADER, false);//设置header
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//要求结果为字符串且输出到屏幕上
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);//设置等待时间
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	
	for($i=0 ;$i < 5; $i++)
	{
		$res = curl_exec($curl);//运行curl
		$err = curl_error($curl);
		
		if(empty($err))
		{
			break;
		}
	}
	
	if (false === $res || !empty($err))
	{
		$errno = curl_errno($curl);
		$Info = curl_getinfo($curl);
		curl_close($curl);
		
		
		return array(
			'status' => 250,
			'errno' => $errno,
			'msg' => $err,
			'data' => $Info,
		);
	}
	
	curl_close($curl);//关闭curl
	
	Yf_Log::log('$res : ' . $res, Yf_Log::INFO, 'get_url_with_encrypt');
	
	if ('JSON' == $typ)
	{
		$res = decode_json($res);
	}
	
	return $res;
}

//可以判断请求时间是否超过某个期限
function get_url_with_encrypt($key, $url, $formvars=array(), $typ='JSON', $method='POST')
{
	$formvars['rtime'] = get_time();
	$hash_row = $formvars;
	
	array_multiksort($hash_row, SORT_STRING);
	
	$hash_row['key'] = $key;
	
	$tmp_str = http_build_query($hash_row);
	
	Yf_Log::log('$tmp_str:' . $tmp_str, Yf_Log::INFO, 'get_url_with_encrypt');
	Yf_Log::log('$url:' . $url, Yf_Log::INFO, 'get_url_with_encrypt');
	$formvars["token"] = md5($tmp_str);
	
	Yf_Log::log($hash_row, Yf_Log::INFO, 'get_url_with_encrypt');
	Yf_Log::log($formvars, Yf_Log::INFO, 'get_url_with_encrypt');
	
	$rs = get_url($url, $formvars, $typ, $method);
	Yf_Log::log($rs, Yf_Log::INFO, 'get_url_with_encrypt');
	return $rs;
}

function check_url_with_encrypt($key, $formvars=array())
{
	Yf_Log::log($formvars, Yf_Log::INFO, 'check_url_with_encrypt');
	
	$token = $formvars['token'];
	unset($formvars['token']);
	
	$hash_row = $formvars;
	array_multiksort($hash_row, SORT_STRING);
	
	$hash_row['key'] = $key;
	$tmp_str = http_build_query($hash_row);
	
	Yf_Log::log('$tmp_str:' . $tmp_str, Yf_Log::INFO, 'check_url_with_encrypt');
	Yf_Log::log('md5-key:' . md5($tmp_str), Yf_Log::INFO, 'check_url_with_encrypt');
	
	//可以判断请求时间是否超过某个期限, 1分钟内
	if ((get_time() - $hash_row['rtime'] < 60) && $token == md5($tmp_str))
	{
		return true;
	}
	else
	{
		return false;
	}
}
function array_multiksort(&$rows)
{
	foreach ($rows as $key => $row)
	{
		if (is_array($row))
		{
			array_multiksort($rows[$key]);
		}
	}
	
	ksort($rows, SORT_STRING);
}
function conversion_array_type($data)
{
	$data = is_array($data) ? array_map('conversion_array_type', $data) : conversion($data);
	
	return $data;
}

function conversion($data)
{
	if(is_numeric($data))
	{
		if (is_float($data) || ((float)$data > (int)$data || strlen($data) != strlen((int)$data)) && (int)$data != 0)
		{
			$data = floatval($data);
		}
		else
		{
			$data = intval($data);
		}
	}
	
	return $data;
}



//格式化用户请求数据
function request_int($key, $default=0)
{
	$val = isset($_REQUEST[$key]) ? intval($_REQUEST[$key]) : $default;
	
	if ('firstRow' == $key)
	{
		$val ++;
	}
	
	return $val;
}


function request_string($key, $default='')
{
	$val = isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
	$preg = "/<script[\s\S]*?<\/script>/i";
	
	$val = preg_replace($preg, "", $val);    //第四个参数中的3表示替换3次，默认是-1，替换全部
	
	
	return $val;
}

function request_float($key, $default=0)
{
	$val = isset($_REQUEST[$key]) ? floatval($_REQUEST[$key]) : $default;
	
	return $val;
}

function request_row($key, $default = array())
{
	$val = isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
	
	return $val;
}

if (!function_exists('array_column'))
{
	function array_column($input, $column_key, $index_key = null)
	{
		$arr = array_map(function ($d) use ($column_key, $index_key)
		{
			if (!isset($d[$column_key]))
			{
				return null;
			}
			
			if ($index_key !== null)
			{
				return array($d[$index_key] => $d[$column_key]);
			}
			
			return $d[$column_key];
		}, $input);
		
		if ($index_key !== null)
		{
			$tmp = array();
			
			foreach ($arr as $ar)
			{
				$tmp[key($ar)] = current($ar);
			}
			
			$arr = $tmp;
		}
		
		return $arr;
	}
}

function image_thumb($image_url, $width=64, $height=64)
{
	if (false != strpos($image_url, 'image.php'))
	{
		$image_url_row = explode('!', $image_url);
		$image_url = $image_url_row[0];
		
		$ext_row = pathinfo($image_url);
		$ext_name = isset($ext_row['extension']) ? $ext_row['extension'] : 'jpg';
		
		return sprintf('%s!%dx%d.%s', $image_url, $width, $height, $ext_name);
	}
	else
	{
		return $image_url;
	}
	
}

function format_money($number , $decimals = 2 , $dec_point = '.' , $thousands_sep = ',' )
{
	return sprintf('%s%s', Web_ConfigModel::value('monetary_unit'), number_format($number, $decimals, $dec_point, $thousands_sep));
}




/**
 * Downloads a URL to a local temporary file using the BBC HTTP Class.
 * Please note, That the calling function must unlink() the file.
 *
 * @since 2.5.0
 *
 * @param string $url the URL of the file to download
 * @param int $timeout The timeout for the request to download the file default 300 seconds
 * @return mixed WP_Error on failure, string Filename on success.
 */
function download_url($url, $timeout = 300)
{
	//WARNING: The file is not automatically deleted, The script must unlink() the file.
	if (!$url)
	{
		throw new Exception('http_no_url', _('Invalid URL Provided.'));
	}
	
	$url_filename = basename(parse_url($url, PHP_URL_PATH));
	
	$tmpfname = Yf_Utils_File::tempnam($url_filename);
	if (!$tmpfname)
	{
		throw new Exception('http_no_file', _('Could not create Temporary file.'));
	}
	
	$download_data = file_get_contents_time($url, 600);
	
	if (false === $download_data)
	{
		
		throw new Exception('http_404');
	}
	else
	{
		$flag = file_put_contents($tmpfname, $download_data);
	}
	
	if (!$flag)
	{
		throw new Exception(_('Could not create Temporary file.'));
	}
	
	//$md5 = md5_file($tmpfname);
	$md5_check = true;
	
	if (!$md5_check)
	{
		unlink($tmpfname);
		return $md5_check;
	}
	
	return $tmpfname;
}

function update_feedback($msg)
{
	echo _('<br />');
	echo str_repeat(" ", 4096);
	
	echo $msg;
	
	echo _('<br />');
	ob_flush();
	flush();
}
 
//ip地址定位
function getIPLoc_sina($queryIP)
{ 
    $url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$queryIP; 
    $ch = curl_init($url); 
    //curl_setopt($ch,CURLOPT_ENCODING ,'utf8'); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回 
    $location = curl_exec($ch); 
    $location = json_decode($location); 
    curl_close($ch); 
    $loc = ""; 
    if($location===FALSE) return ""; 

    if(empty($location->desc))
	{ 
		$loc = @$location->province; 
    }else{ 
        $loc = @$location->desc; 
    } 
    return $loc; 
}

if (! function_exists('reset_null_recursive')) {

	/**
	 * 重置null为空字符串
	 */

	function reset_null_recursive($arr)
	{
		foreach ($arr as $k=> $v) {
			if (is_array($v)) {
				$arr[$k] = reset_null_recursive($v);
			}

			if (is_null($v)) {
				$arr[$k] = '';
			}
		}

		return $arr;
	}
}
?>
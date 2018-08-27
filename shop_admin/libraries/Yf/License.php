<?php
/**
 * 根据当前PHP版本加载不同的ZEND LOADER文件
 * 仅支持php 5.4 5.5 5.6
 */
$php_version = (float)phpversion();
$php_version = str_replace('.','',$php_version); 
$load_licese_file = __DIR__.'/License'.$php_version.'.php';
 
if(!file_exists($load_licese_file)){
	header("Content-type: text/html; charset=utf-8");    
	exit("请确认当前PHP版本，仅支持5.4、5.5、5.6、7.1，7.x版本需要扩展ioncube，其他为zendloader");
}
include $load_licese_file;
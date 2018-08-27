<?php
//版本
define('VER', '1.02');

define('APP_DIR_NAME', 'shop_admin');
define('ROOT_PATH', substr(str_replace('\\', '/', dirname(__FILE__)), 0, -8));

define('LIB_PATH', ROOT_PATH . '/libraries');   //ZeroPHP Framework 所在目录
define('APP_PATH', ROOT_PATH . '/install');         //应用程序目录
define('MOD_PATH', APP_PATH . '/models');       //应用程序模型目录

/**
 * 风格静态文件文件目录，此处变量名称$themes勿修改
 *
 * @var string
 */
$themes_name = 'default';
$pro_path    = '';

if (isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT'])
{
	$pro_path_row = explode($_SERVER['DOCUMENT_ROOT'], ROOT_PATH);

	if (isset($pro_path_row[1]))
	{
		$pro_path = '/' . ltrim($pro_path_row[1], '/');
		$themes = $pro_path . '/' . APP_DIR_NAME . '/static/' . $themes_name;
	}
	else
	{
		$themes = '/' . APP_DIR_NAME . '/static/' . $themes_name;
	}
}
else
{
	$themes = '/' . APP_DIR_NAME . '/static/' . $themes_name;
}

define('TPL_DEFAULT_PATH', APP_PATH . '/views');  //应用程序默认视图目录
define('TPL_PATH', APP_PATH . '/views');  //应用程序视图目录
define('CTL_PATH', APP_PATH);  //应用程序控制器目录
define('INI_PATH', ROOT_PATH . '/' . APP_DIR_NAME . '/configs');      //应用程序配置文件目录
define('INI_INSTALL_PATH', APP_PATH . '/configs');      //应用程序配置文件目录
define('LOG_PATH', APP_PATH . '/data/logs');
define('DATA_PATH', APP_PATH . '/data');
/*
define('HLP_PATH', APP_PATH . '/helpers');
*/
define('LAN_PATH', APP_PATH . '/data/locales');


//是否开启runtime，如果为false，则不生成runtime缓存
define('RUNTIME', false);

//是否开启debug，如果为true，则不生成runtime缓存
define('DEBUG', false);

/**
 * 保存加载过的文件，只记录class或者记录按照顺序执行的全局文件。
 *
 * @var array
 */
global $import_file_row;

$import_file_row = array();

/**
 * 计算是否需要从runtime运行
 */
{
	//初始化Zero
	require_once LIB_PATH . '/__init__.php';

	//引入用户配置文件
	require_once INI_PATH . '/config.ini.php';
}


Yf_App::start();
?>

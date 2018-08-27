<?php
    
    $uri = $_SERVER['REQUEST_URI'];
    if(substr($uri,0,2)=='//'){
        $_SERVER['REQUEST_URI'] = substr($uri,1);
    }
    
    /**
     * 入口文件
     *
     * 所有程序调用的入口， 此文件属于框架的一部分，任何人不允许修改！
     *
     * @category   Framework
     * @package    __init__
     * @author     Xinze <xinze@live.cn>
     * @copyright  Copyright (c) 2010, 黄新泽
     * @version    1.0
     * @todo
     */
    //版本
    define('VER', '1.02');
    //设置开始的时间
    $mtime = explode(' ', microtime());
    $app_starttime = $mtime[1] + $mtime[0];
    define('APP_DIR_NAME', 'shop');
    define('ROOT_PATH', str_replace('\\', '/', dirname(__FILE__)));
    define('LIB_PATH', ROOT_PATH . '/libraries');   //ZeroPHP Framework 所在目录
    define('APP_PATH', ROOT_PATH . '/' . APP_DIR_NAME);         //应用程序目录
    define('MOD_PATH', APP_PATH . '/models');       //应用程序模型目录
    /**
     * 风格静态文件文件目录，此处变量名称$themes勿修改
     *
     * @var string
     */
    $host = '';
    if (isset($_SERVER['HTTP_HOST'])) {
        $host = $_SERVER['HTTP_HOST'];
    }
    $server_id = md5($host);
    if (is_file(APP_PATH . '/configs/global_' . $server_id . '.ini.php')) {
        include_once APP_PATH . '/configs/global_' . $server_id . '.ini.php';
    } else {
        include_once APP_PATH . '/configs/global.ini.php';
    }
    $themes_name = $theme_id ? : "default";
    $pro_path = '';
    if (isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT']) {
        $pro_path_row = explode($_SERVER['DOCUMENT_ROOT'], ROOT_PATH);
        if (isset($pro_path_row[1])) {
            $pro_path = '/' . ltrim($pro_path_row[1], '/');
            $themes = $pro_path . '/' . APP_DIR_NAME . '/static/' . $themes_name;
        } else {
            $themes = '/' . APP_DIR_NAME . '/static/' . $themes_name;
        }
    } else {
        $themes = '/' . APP_DIR_NAME . '/static/' . $themes_name;
    }
    define('TPL_DEFAULT_PATH', APP_PATH . '/views/default');  //应用程序默认视图目录
    define('TPL_PATH', APP_PATH . '/views/' . $themes_name);  //应用程序视图目录
    define('CTL_PATH', APP_PATH . '/controllers');  //应用程序控制器目录
    define('INI_PATH', APP_PATH . '/configs');      //应用程序配置文件目录
    define('LOG_PATH', APP_PATH . '/data/logs');
    define('DATA_PATH', APP_PATH . '/data');
    /*
    define('HLP_PATH', APP_PATH . '/helpers');
    */
    define('LAN_PATH', ROOT_PATH . '/messages');
    //是否开启runtime，如果为false，则不生成runtime缓存
    define('RUNTIME', false);
    //是否开启debug，如果为true，则不生成runtime缓存
    define('DEBUG', false);
    //加载协议解析文件
    require_once INI_PATH . '/protocol.ini.php';
    if (RUNTIME) {
        /**
         * runtime文件名称
         *
         * @var string
         */
        global $runtime;
        if (isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] != '/') {
            if (ltrim($_SERVER['PATH_INFO'], '/')) {
                $path_info_get = explode('/', ltrim($_SERVER['PATH_INFO'], '/'));
            }
            if (isset($path_info_get[0])) {
                $runtime = implode('/', explode('_', $path_info_get[0]));
            } else {
                $runtime = 'Index';
            }
        } else {
            if (isset($_REQUEST['ctl'])) {
                $runtime = implode('/', explode('_', $_REQUEST['ctl']));
            } else {
                $runtime = 'Index';
            }
        }
        /**
         * runtime文件路径
         *
         * @var string
         */
        $runtime_file = APP_PATH . '/data/runtime/' . VER . '/' . $runtime . '.php';
    }
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
    if (RUNTIME && is_file($runtime_file)) {
        include_once $runtime_file;
    } else {
        array_push($import_file_row, LIB_PATH . '/__init__.php');
        array_push($import_file_row, APP_PATH . '/configs/config.ini.php');
        //初始化Zero
        require_once LIB_PATH . '/__init__.php';
        //引入用户配置文件
        require_once APP_PATH . '/configs/config.ini.php';
    }
    if (1 != $site_status) {
        if ((isset($_REQUEST['ctl']) && 'Api_' == substr($_REQUEST['ctl'], 0, 4))) {
        } else {
            include TPL_PATH . '/IndexCtl/error.php';
            die();
        }
    }
    if (RUNTIME) {
        Yf_Registry ::set('runtime', $runtime);
        Yf_Registry ::set('runtime_file', $runtime_file);
    }
    //var_dump($import_file_row);
    //程序控制器启动，计算结果
    $ctl = $_GET['ctl'];
    $met = $_GET['met'];
    /**
     * 控制器下的Overwrite目录可重写以前的内容
     */
    if ($ctl) {
        $overwrite = "Overwrite_" . $ctl . "Ctl";
        $file = CTL_PATH . '/' . str_replace('_', '/', $overwrite) . '.php';
        if (file_exists($file) && class_exists($overwrite) && method_exists($overwrite, $met)) {
            $_REQUEST['ctl'] = "Overwrite_" . $ctl;
        }
    }
    Yf_App ::start();
    $PluginManager -> trigger('end', '');
    $mtime = explode(' ', microtime());
    $app_endtime = $mtime[1] + $mtime[0];
?>

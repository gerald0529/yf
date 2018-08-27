<?php
/**
 * 程序启动类
 *
 * 负责初始化程序框架，控制器，路由，视图等等总入口
 *
 * @category   Framework
 * @package    Controller
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo
 */
class Yf_App
{
    /**
     * 框架版本
     *
     * @const string
     */
    const VERSION = '1.0';

    /**
     * 框架版本
     *
     * @const string
     */
    const CTLPREFIX = 'Game';

    /**
     * 构造函数
     *
     *
     * @access    public
     */
    public function __construct()
    {
    }

    /**
     * 初始化框架
     *
     *
     * @access    public
     */
    public static function init()
    {
		$PluginManager = Yf_Plugin_Manager::getInstance();
		$PluginManager->trigger('perm');
    }

    /**
     * 程序执行入口
     *
     *
     * @param  string $ctl
     * @param  string $met
     * @param  string $typ
     * @access public
     */
    public static function start($ctl='Index', $met='index', $typ='e')
    {
        if (!isset($_REQUEST['ctl']))
        {
            $_REQUEST['ctl'] = $ctl;
        }

        $ctl = $_REQUEST['ctl'] . 'Ctl';

        if (!isset($_REQUEST['met']))
        {
            $_REQUEST['met'] = $met;
        }

        $met = $_REQUEST['met'];

		$Yf_Registry = Yf_Registry::getInstance();
		$ccmd_rows = isset($Yf_Registry['ccmd_rows']) ? $Yf_Registry['ccmd_rows'] : array();

        if (!isset($_REQUEST['typ']))
        {
            //过滤类型处理
            if (isset($ccmd_rows[$_REQUEST['ctl']][$_REQUEST['met']]))
            {
                $_REQUEST['typ'] = $ccmd_rows[$_REQUEST['ctl']][$_REQUEST['met']]['typ'];
            }
            else
            {
                $_REQUEST['typ'] = $typ;
            }
        }

        $typ = $_REQUEST['typ'];

        $ctl = htmlspecialchars($ctl);
        $met = htmlspecialchars($met);
        $typ = htmlspecialchars($typ);

        //$ctl = ucfirst(strtolower($ctl));
        //$act = ucfirst(strtolower($act));

        self::init();

        $Router = new Yf_Router($ctl, $met, $typ); //调用路由，程序控制器启动

        $rs =  $Router->service();
        $shop_api_url = Yf_Registry::get('shop_api_url');
        //ob_start();
        if (is_array($rs) || is_object($rs))
        {
            print_r($rs);
        }
        else
        {
            //$rs = preg_replace("~>\s+\r~", ">", preg_replace("~>\s+\n~", ">", $rs)); //modify 压缩
            //$rs = preg_replace("~>\s+<~", "><", $rs);
            if($shop_api_url && strpos($shop_api_url,'https://')!==false){
                $rs = str_replace('http://','https://',$rs);
            }
            echo $rs;
        }



        //判断程序运行过程中，是否需要生成runtime
        if (RUNTIME)
        {
            //通过replace，将视图也加入，需要特殊标记处理
            /*
            if ($Router->controller->view)
            {
                //preg_replace($patterns, $replacements, $string);
            }
            */

            self::checkRuntime();
        }
    }

    /**
     * 生成运行环境缓存
     *
     * @access public
     */
    public static function checkRuntime()
    {
        global $import_file_row;

        $runtime_file    = Yf_Registry::get('runtime_file');
        $runtime         = Yf_Registry::get('runtime');

        if ($runtime_file && !empty($import_file_row))
        {
            $runtime_content = '';

            //添加新的调用方法
            if (is_file($runtime_file))
            {
                $runtime_content .= php_strip_whitespace($runtime_file);
                //$runtime_content .= file_get_contents($runtime_file);
            }

            foreach ($import_file_row as $key=>$php_file)
            {
                $runtime_content .= php_strip_whitespace($php_file);
                //$runtime_content .= file_get_contents($php_file);
            }

            //针对PHP5，可以直接使用这个
            if (!file_exists(dirname($runtime_file)))
            {
                mkdir(dirname($runtime_file), 0777, true);
                //make_dir_path();
            }

            file_put_contents($runtime_file, $runtime_content);
        }
    }
}
?>
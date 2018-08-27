<?php
if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))
{
    include_once '../configs/config.ini.php';
}

/**
 * 插件管理类,设置为单例模式
 * 
 * 
 * @category   Framework
 * @package    Plugin
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 */
class Yf_Plugin_Manager
{
    private static $_instance ; 

    /**
     * 监听已注册的插件
     *
     * @access private
     * @staticvar array
     */
    private static $_listeners = array();

    /**
     * 构造方法
     */
    private function __construct()
    {
    }

    final public static function getInstance() 
    {
        if (!(self::$_instance instanceof self))
        {
            self::$_instance = new self();

			$args = func_get_args();

			call_user_func_array(array(self::$_instance, 'init'), $args);
        }

        return self::$_instance;
    }
    
    /**
     * 注册需要监听的插件方法（钩子）
     *
     * @param string $hook
     * @param object $reference
     * @param string $method
     */
    public function register($hook, &$reference, $method)
    {
        //获取插件要实现的方法
        $key = get_class($reference).'->'.$method;

        //将插件的引用连同方法push进监听数组中
        self::$_listeners[$hook][$key] = array(&$reference, $method);

        #此处做些日志记录方面的东西

        return self::$_instance;
    }

    /**
     * 触发一个钩子
     *
     * @param string $hook 钩子的名称
     * @param mixed $data 钩子的入参
     * @return mixed
     */
    public function trigger()
    {
        $args = func_get_args();
        $hook = array_shift($args);
        $result = [];

        //查看要实现的钩子，是否在监听数组之中
        if (isset(self::$_listeners[$hook]) && is_array(self::$_listeners[$hook]) && count(self::$_listeners[$hook]) > 0)
        {
            // 循环调用开始
            foreach (self::$_listeners[$hook] as $plugin_name=>$listener)
            {
                // 取出插件对象的引用和方法
                $class = &$listener[0];
                $method = &$listener[1];

                if(method_exists($class, $method))
                {
                    // 动态调用插件的方法
                    $plugin_name = get_class($class) . '_' . $method;
                    //$result[$plugin_name] = $class->$method($data);

                    $result[$plugin_name] =  call_user_func_array(array($class, $method), $args);
                    //$data = $result[$plugin_name];
                }
            }
        }

        #此处做些日志记录方面的东西

        return $result;
    }

    /**
     * 初始化函数
     * 
     * @param array $plugin_rows 激活插件数组
     * $plugin = array(
     *     'name' => '插件名称',
     *     'directory'=>'插件安装目录'plugins
     * );
     * @access private
     * @return void
     */
    public function init($plugin_rows=array())
    {
        if($plugin_rows)
        {
            foreach($plugin_rows as $plugin)
            {
                if (isset($plugin['cli']) && false==$plugin['cli'])
                {
                    if ('cli' == SAPI)
                    {
                        continue;
                    }
                }
                
                //插件文件夹中包含一个{'Plugin/' . $plugin['name'] .php}文件，它是插件的具体实现
                $class = $plugin['name'];

                $plugin_path = CTL_PATH . '/' . str_replace('_', DIRECTORY_SEPARATOR, $plugin['name']) . '.php';

                if (is_file($plugin_path))
                {
                    include_once($plugin_path);

                    if (class_exists($class, false)) 
                    {
                        //初始化所有插件
                        //可以交给插件自己负责，目前这样，框架结构规则严格
                        new $class();
                    }
                    else
                    {
                        //报错。
                    }
                }
                else
                {
					Yf_Log::log(sprintf(_('插件 %s 不存在！'), $plugin['name']), Yf_Log::INFO);
                }
            }
        }

        #此处做些日志记录方面的东西
    }

    final private function __clone() 
    {
    } 
}

/*
$plugin_rows = get_active_plugins();#这个函数请自行实现

$PluginManager = Yf_Plugin_Manager::getInstance();

$PluginManager->init($plugin_rows);
$PluginManager->trigger('init', '');
$PluginManager->trigger('foot', '');
$PluginManager->trigger('end', '');
*/
?>
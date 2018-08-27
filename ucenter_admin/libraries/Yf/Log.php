<?php
/**
 * 日志工厂模式
 * 
 * 通过这个类，统一管理日志类。
 * 
 * @category   Framework
 * @package    Db
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 */
class Yf_Log
{
    /**
     * Firebug LOG level
     *
     * Logs a message to firebug console.
     * 
     * @var string
     */
    const LOG = 'LOG';
  
    /**
     * Firebug INFO level
     *
     * Logs a message to firebug console and displays an info icon before the message.
     * 
     * @var string
     */
    const INFO = 'INFO';
    
    /**
     * Firebug WARN level
     *
     * Logs a message to firebug console, displays an warning icon before the message and colors the line turquoise.
     * 
     * @var string
     */
    const WARN = 'WARN';
    
    /**
     * Firebug ERROR level
     *
     * Logs a message to firebug console, displays an error icon before the message and colors the line yellow. Also increments the firebug error count.
     * 
     * @var string
     */
    const ERROR = 'ERROR';
    
    /**
     * Dumps a variable to firebug's server panel
     *
     * @var string
     */
    const DUMP = 'DUMP';
    
    /**
     * Displays a stack trace in firebug console
     *
     * @var string
     */
    const TRACE = 'TRACE';
    
    /**
     * Displays an exception in firebug console
     * 
     * Increments the firebug error count.
     *
     * @var string
     */
    const EXCEPTION = 'EXCEPTION';
    
    /**
     * Displays an table in firebug console
     *
     * @var string
     */
    const TABLE = 'TABLE';
    
    /**
     * Starts a group in firebug console
     * 
     * @var string
     */
    const GROUP_START = 'GROUP_START';
    
    /**
     * Ends a group in firebug console
     * 
     * @var string
     */
    const GROUP_END = 'GROUP_END';
    

    private static $logMap = array(
             'ERROR'     => 3,
            'WARN' => 4,
            'INFO'    => 6,
            'LOG'   => 7
    );

    /**
     * 构造函数
     *
     * @access    private
     */
    public function __construct()
    {
    }

    /**
     *
     * @access public
     */
    public static function log($info, $type, $name='default')
    {
        if (!preg_match_all('/\sFirePHP\/([\.|\d]*)\s?/si', @$_SERVER['HTTP_USER_AGENT'] , $m) || !version_compare($m[1][0], '0.0.6', '>='))
        {
            //REQUEST=".var_export($_REQUEST,true)
            $ident = 'FILE=' . $_SERVER['SCRIPT_FILENAME'];
            $conf = array('mode' => 0777, 'timeFormat' => '%X %x');
            $log_file = APP_PATH . '/data/logs/' . $name . '.log';
            $Logger   = Log::singleton('file', $log_file, $ident, $conf, PEAR_LOG_DEBUG);

            $Logger->log($info, self::$logMap[$type]);
            $Logger->close();
        }
        else
        {
            fb($info, $name, $type);
        }
    }
}
?>
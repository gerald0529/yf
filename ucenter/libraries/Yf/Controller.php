<?php
/**
 * 控制器App基本数据
 * 
 * 初始化App控制器各项参数
 * 
 * @category   Framework
 * @package    Controller
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 */
class Yf_Controller
{
    /**
     * 控制器程序文件所在目录
     * 
     * @access public
     * @var string|null
     */
    public $ctl = null;

    /**
     * 控制器类默认调用的方法
     * 
     * @access public
     * @var string|null
     */
    public $met = null;

    /**
     * 返回个客户端数据类型，html|json
     * 
     * e : 为普通字符串
     * o : 为JSON数组
     * 
     * @access public
     * @var string|null
     */
    public $typ = null;

    /**
     * 控制器程序类名称
     * 
     * @access public
     * @var string|null
     */
    public $className = null;

    /**
     * 控制器程序类路径
     * 
     * @access public
     * @var string|null
     */
    public $path = null;

    /**
     * Constructor
     *
     * @global string $themes 视图风格
     * @param  string $ctl 控制器目录
     * @param  string $act 控制器文件
     * @param  string $met 控制器方法
     * @param  string $typ 返回数据类型
     * @access public
     */
    public function __construct($ctl, $met, $typ)
    {
        $this->ctl = &$ctl;
        $this->met = &$met;
        $this->typ = &$typ;


        $this->className = $this->ctl;
        $this->path = CTL_PATH . '/' . implode('/', explode('_', $this->ctl)) . '.php';
    }
}
?>
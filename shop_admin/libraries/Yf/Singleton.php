<?php
/**
 * 单例模式
 * 
 * 这个单例模式类，是为了让其它需要写成单例模式的类继承的。
 * 
 * @category   Framework
 * @package    Singleton
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 */
abstract class Yf_Singleton
{
    // 1. 必须要有一个保存类的实例化的静态(static)成员变量,如 $instance,最好是私有化(private)的.
    private static $_instance = array();

    //2. 必须有一个构造函数__construct(),并且必须被标记为 私有化(private) .
    protected function __construct() 
    {
    }

    //3. 最好创建一个空的,私有化(private) 的__clone()方法,防止对象被复制或者克隆.
    final private function __clone() 
    {
    } 

    //4. 拥有一个访问这个实例的公共的 (public) 静态 (static)方法
    final public static function getInstance() 
    {
        /*
        5. 静态(static)方法里面不能用$this->var的形式,必须用作用域限定符::的形式(self::$var),其中self(代表子类)可以用parent(代表父类),static(PHP 6 新增),或者类名(如类Singleton)代替.
        6. instanceof操作符是一个比较操作符,接受左右两边的参数,并返回一个布尔值,确定对象的某个实例是否为特定的类型,或者是否从某个类型继承,或者是否实现了某个接口,或者是否某个类的实例
        7. self::$instance instanceof self 可用 !is_object($instance) 或者 self::$instance == null 等代替,但不太规范,不推荐使用.
        if (!(self::$instance instanceof self))
        {
            self::$instance = new Yf_Singleton();  //8. 若不是本类的实例,则实例化本类
        }

        return self::$instance;    //9. 若是本类实例化,则返回 保存本类的实例化的静态成员变量$instance
        */

        $called_class_name = get_called_class();
        
        if (!isset($_instance[$called_class_name])) 
        {
            $_instance[$called_class_name] = new $called_class_name();
            
            $args = func_get_args();

            call_user_func_array(array($_instance[$called_class_name], 'init'), $args);
            //call_user_func_array(array($called_class_name, 'init'), func_get_args());

            //$_instance[$called_class_name]->init();

            unset($args);
        }
        
        return $_instance[$called_class_name];
    }

    public function init()
    {
    }
}
?>
<?php
/**
 * 数据库工厂模式
 * 
 * 通过这个类，统一管理Db类。
 * 
 * @category   Framework
 * @package    Db
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 */
class Yf_Db
{

    /**
     * 构造函数
     *
     * @access    private
     */
    public function __construct()
    {
    }

    /**
     * 得到数据库句柄
     *
     * @param string $id      database id
     * @param array  $drive   使用的数据库驱动
     *
     * @return self::dbHandle   Db Object
     *
     * @access public
     */
    public static function get($id='data', $drive=DB_DRIVE)
    {
        //return $drive::get($id);
        if ('Yf_Db_Pear' == DB_DRIVE)
        {
            return Yf_Db_Pear::get($id);
        }
        elseif ('Yf_Db_PearMDb2' == DB_DRIVE)
        {
            return Yf_Db_PearMDb2::get($id);
        }
        else
        {
            return Yf_Db_Pdo::get($id);
        }
    }

    /**
     * 得到数据库句柄
     *
     * @param string $id      database id
     * @param array  $drive   使用的数据库驱动
     *
     * @return self::dbHandle   Db Object
     *
     * @access public
     */
    public static function close($id=null, $drive=DB_DRIVE)
    {
        if ('Yf_Db_Pear' == DB_DRIVE)
        {
            return Yf_Db_Pear::close($id);
        }
        elseif ('Yf_Db_PearMDb2' == DB_DRIVE)
        {
            return Yf_Db_PearMDb2::close($id);
        }
        else
        {
            return Yf_Db_Pdo::close($id);
        }
    }



    /**
     * 从数据库连接模式
     *
     * @param string $id      database id
     * @param array  $drive   使用的数据库驱动
     * @return bool   true/false
     * @access  public
     */
    public static function setConnectMode($mode, $drive=DB_DRIVE)//设置模式
    {
        if ('Yf_Db_Pear' == DB_DRIVE)
        {
            return Yf_Db_Pear::setConnectMode($mode);
        }
        elseif ('Yf_Db_PearMDb2' == DB_DRIVE)
        {
            return Yf_Db_PearMDb2::setConnectMode($mode);
        }
        else
        {
            return Yf_Db_Pdo::setConnectMode($mode);
        }
    }
}
?>
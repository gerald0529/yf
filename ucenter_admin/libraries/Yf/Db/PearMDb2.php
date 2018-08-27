<?php
/**
 * PearMDb2
 *
 * 通过PearMDb2连接数据库
 *
 * @category   Framework
 * @package    Db
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 *
 */
if (!defined('DEBUG_BACKTRACE_PROVIDE_OBJECT'))
{
    define('DEBUG_BACKTRACE_PROVIDE_OBJECT', 0);
}

if (!defined('DEBUG_BACKTRACE_IGNORE_ARGS'))
{
    define('DEBUG_BACKTRACE_IGNORE_ARGS', 0);
}

class Yf_Db_PearMDb2
{
    //private static $dbHandleRow = array();  //存放数据库对应主机连接句柄object, 以key作为索引, $Db=主机连接句柄作为值
    ////private static $dbHostRow   = array();  //存放数据库对应的主机, 程序使用不到, 用户调试信息
    //private static $dbNameRow   = array();  //存放主机连接的数据库名称, 以host作为索引, 以key作为值
    //private static $dbActiveRow = array();  //存放主机当前活跃指向的数据库名称, 以host作为索引, 以key作为值
    //private static $connectMode = false;    //false, 不共用主机
    //private $dbHandle = null;
    //private $fetchMode = null;

    /**
     * 存放数据库对应主机连接句柄object, 以key作为索引, $Db=主机连接句柄作为值
     *
     * @var     array
     * @access  private
     */
    private static $dbHandleRow = array(); 

    //private static $dbHostRow   = array();

    /**
     * 存放主机连接的数据库名称, 以host作为索引, 以key作为值
     *
     * @var     array
     * @access  private
     */
    private static $dbNameRow   = array(); 

    /**
     * 存放主机当前活跃指向的数据库名称, 以host作为索引, 以key作为值
     *
     * @var     array
     * @access  private
     */
    private static $dbActiveRow = array(); 

    /**
     * 标记数据连接是否共用主机， false为不共用主机
     *
     * @var     bool
     * @access  private
     */
    private static $connectMode = false;

    /**
     * 数据库连接句柄
     *
     * @var     bool
     * @access  private
     */
    private $dbHandle = null;

    /**
     * 读取数据模式
     *
     * @var     bool
     * @access  private
     */
    private $fetchMode = null;

    /**
     * 当前数据库驱动
     *
     * @var     string
     * @access  private
     */
    private  static $drive        = 'PearMDb2';

    /**
     * 构造函数
     *
     * @param string $id  database id
     * @param array     $id  db dsn, user and passwd array
     *
     * @access    private
     */
    private function __construct($id, $cfg)
    {
        $dsn = 'mysql://' . $cfg['user'] . ':' . $cfg['password'] . '@' . $cfg['host'] . ':' . $cfg['port'] . '/' . $cfg['database'] . '';

        $this->dbHandle = MDB2::connect($dsn);

        if (MDB2::isError($this->dbHandle))
        {
            if ('cli' == SAPI)
            {
                echo $this->dbHandle;
                throw new Exception($this->dbHandle);
            }
            else
            {
                die ($this->dbHandle);
            }

            $this->dbHandle = null;
        }
        else
        {
            $this->dbHandle->cfg[$id] = $cfg;

            $this->setFetchMode(MDB2_FETCHMODE_ASSOC);

            if ($cfg['charset'])
            {
                $this->exec('SET NAMES ' . $cfg['charset']);
            }

            if (self::$connectMode)
            {
                    //存放共用主机数据信息
                    //self::$dbHostRow[$id] = $cfg['host'];
                    $host_var = str_replace('.', '_', $cfg['host']);

                    self::$dbActiveRow[$host_var] = $id;
            }
        }
    }

    /**
     * 得到数据库句柄
     *
     * @global array  $db_cfg_rows
     * @param string $id     database id
     * @param array  $drive     db dsn, user and passwd array
     *
     * @return self::dbHandle   Db Object
     *
     * @access public
     */
    public static function get($id='main', $drive=null)
    {
        if ($drive)
        {
            self::$drive = $drive;
        }
        else
        {
            self::$drive = DB_DRIVE;
        }

        if (is_null($id))
        {
            $id = 'main';
        }

        //如果根据key索引, 无法找到已经连接的主机, 则开始连接--1
        if (!isset(self::$dbHandleRow[$id]))
        {
            $config = Yf_Registry::get('db_cfg');
            $db_cfg_rows   = $config['db_cfg_rows'];
            $db_write_read = $config['db_write_read'];


            $rand_keys = array_rand($db_cfg_rows[$db_write_read][$id], 1); //是否会数据库错乱? 不会, 因为上面isset(self::$dbHandleRow[$id])已经处理了. 问题是:无法指定同一key下的某台机器
            $cfg = $db_cfg_rows[$db_write_read][$id][$rand_keys];

            //是否有相同主机句柄
            if (self::$connectMode)
            {
                //是否有连接当前key索引主机的数据库连接句柄    -- 2
                //取得Host
                $host_var = str_replace('.', '_', $cfg['host']);

                //如果当前已经有数据库连接主机
                if (isset(self::$dbActiveRow[$host_var]))
                {
                    //改变当前数据库
                    $connect_id = self::$dbActiveRow[$host_var];      //为什么不直接使用$id, 因为不同id也可以共享主机. 去句柄需要

                    $connect = self::$dbHandleRow[$connect_id];

                    //防止重复指向到同一个库,则直接返回句柄对象
                    if (self::$dbActiveRow[$host_var] == $id)
                    {
                        return $connect;
                    }
                    else
                    {
                        $rs = $connect->exec(' USE `' . $cfg['database'] . '`');

                        //如果use database 失败, 有这种情况发生,可以返回再次连接,union    dbashanghai    union_perm
                        if (false === $rs)
                        {
                            return self::$dbHandleRow[$id] = new self($id, $cfg);
                        }
                        else
                        {
                            //echo '旧主机';
                            //self::$dbHostRow[$id] = $cfg['host'];
                            self::$dbActiveRow[$host_var] = $id;
                            self::$dbHandleRow[$id] = $connect;
                            self::$dbHandleRow[$id]->dbHandle->cfg[$id] = $cfg;
                        }

                        return $connect;
                    }
                }
                else
                {
                    self::$dbHandleRow[$id] = new self($id, $cfg);
                }
            }
            else
            {
                self::$dbHandleRow[$id] = new self($id, $cfg);
            }
        }
        else
        {
            //echo '旧主机';
            //是否指向当前db
            if (self::$connectMode)
            {
                $cfg = self::$dbHandleRow[$id]->dbHandle->cfg[$id];
                //fb(' USE `' . $cfg['database'] . '`');

                //可以判断当前是否已经use 这个db, 就不用下面这句了
                self::$dbHandleRow[$id]->exec(' USE `' . $cfg['database'] . '`');
            }
        }

        return self::$dbHandleRow[$id];
    }

    /**
     * 关闭数据连接, 目前是全部关闭
     *
     * @param string $id     database id
     *
     * @access public
     */
    public static function close($id=null)
    {
        if (null == $id)
        {
            foreach (self::$dbHandleRow as $key=>$db)
            {
                if ($db->dbHandle)
                {
                    $db->dbHandle->disconnect();
                } 

                $db->dbHandle = null;

                unset(self::$dbHandleRow[$key]);
            }

            self::$dbNameRow = array();
            self::$dbActiveRow = array();
        }
        else
        {
            if (array_key_exists($id, self::$dbHandleRow))
            {
                foreach (self::$dbNameRow as $host_tmp=>$id_tmp)
                {
                    if ($id_tmp == $id)
                    {
                        unset(self::$dbNameRow[$host_tmp]);
                    }
                }

                foreach (self::$dbActiveRow as $host_tmp=>$id_tmp)
                {
                    if ($id_tmp == $id)
                    {
                        unset(self::$dbActiveRow[$host_tmp]);
                    }
                }
                self::$dbHandleRow[$id]->dbHandle->disconnect();;  
                self::$dbHandleRow[$id]->dbHandle = null;
                unset(self::$dbHandleRow[$id]);
            }
            else
            {
            }
        }
    }

    //以下简化使用,更多扩展可以随后加入!!!,尽量直接使用db handle!!!
    /**
     * 对 mysql_query 封装
     *
     * @param string $sql  运行的sql语句
     *
     * @access  public
     */
    public function query($sql = null)//执行SQL
    {
        $rs = false;

        $rs = $this->dbHandle->query($sql);

        if (MDB2::isError($rs))
        {
            $error_info = $rs->getUserInfo();

            //守护进程，需要清楚连接标记
            if ('DB Error: no database selected' == $rs->getMessage())
            {
                $id = array_pop(array_keys($this->dbHandle->cfg));

                self::close($id);
            }

            if (DB_DEBUG)
            {
                array_push($error_info, $sql);

                Yf_Log::log($error_info, Yf_Log::ERROR, 'db');
            }

            if ('cli'!= SAPI)
            {
                die($rs->getUserInfo());
            }

            $rs = false;
        }

        return $rs;
    }

    /**
     * 对 mysql_query 封装, 跟Pdo的exec一样， 如果返回falsh则表示运行失败！！！！， 其它返回影响的行数！
     *
     * @param string $sql  运行的sql语句
     *
     * @access  public
     */
    public function exec($sql = null)//执行SQL
    {
        $rs = false;

        $rs = $this->dbHandle->exec($sql);

        if (MDB2::isError($rs))
        {
            //错误log
            $error_info = $rs->getUserInfo();

            //守护进程，需要清楚连接标记
            if ('DB Error: no database selected' == $rs->getMessage())
            {
                $id = array_pop(array_keys($this->dbHandle->cfg));

                self::close($id);
            }

            if (DB_DEBUG)
            {
                array_push($error_info, $sql);

                Yf_Log::log($error_info, Yf_Log::ERROR, 'db');
            }

            if ('cli'!= SAPI)
            {
                die($rs->getUserInfo());
            }

            $rs = false;
        }
        else
        {
            $rs = intval($rs);
        }

        return $rs;
    }

    /**
     * MySQL mysql_insert_id
     *
     * @param array  $id_row     mysql_insert_id row
     *
     * @return int/bool  LAST_INSERT_ID value
     *
     * @access public
     */
    public function insertId()
    {
        $last_insert_id = $this->dbHandle->lastInsertID();

        return $last_insert_id;
    }

    /**
     * MySQL mysql_affected_rows, 在这个封装中， 如何和insertId一起使用的话， 要先使用此方法
     *
     * @return int  affectedRows
     *
     * @access public
     */
    public function affectedRows()
    {
        $affected_rows = false;
        $rs = $this->dbHandle->affectedRows();

        if (MDB2::isError($rs))
        {
            $affected_rows = false;

            $error_info = $rs->getUserInfo();

            //守护进程，需要清楚连接标记
            if ('DB Error: no database selected' == $rs->getMessage())
            {
                $id = array_pop(array_keys($this->dbHandle->cfg));

                self::close($id);
            }

            if (DB_DEBUG)
            {
                array_push($error_info, $sql);

                Yf_Log::log($error_info, Yf_Log::ERROR, 'db');
            }

            if ('cli'!= SAPI)
            {
                die($rs->getUserInfo());
            }

        }
        else
        {
            $affected_rows = $rs;
        }

        return $affected_rows;
    }

    /**
     * Returns the next free id in a sequence
     *
     * @param string  $seq_name  name of the sequence
     * @param boolean $ondemand  when true, the seqence is automatically
     *                            created if it does not exist
     *
     * @return int  the next id number in the sequence.
     *               A DB_Error object on failure.
     *
     * @see DB_common::nextID(), DB_common::getSequenceName(),
     *      DB_mysql::createSequence(), DB_mysql::dropSequence()
     */
    function nextId($seq_name, $ondemand = true)
    {
        $rs = $this->dbHandle->nextId($seq_name, $ondemand);

        if (MDB2::isError($rs))
        {
            //错误log
            $error_info = $rs->getUserInfo();

            //守护进程，需要清除连接标记
            if ('DB Error: no database selected' == $rs->getMessage())
            {
                $id = array_pop(array_keys($this->dbHandle->cfg));

                self::close($id);
            }

            if (DB_DEBUG)
            {
                array_push($error_info, $sql);

                Yf_Log::log($error_info, Yf_Log::ERROR, 'db');
            }

            if ('cli'!= SAPI)
            {
                die($rs->getUserInfo());
            }

            $rs = false;
        }

        return $rs;
    }

    /**
     * 取得一行数据, 各种参数,根据需要, 可以修改本方法
     *
     * @param string $sql            运行的sql语句
     * @param string $fetch_mod        数据格式
     *
     * @return array/bool  query resultrow
     *
     * @access  public
     */
    public function getRow($sql=null, $fetch_mod=null)//取得一行数据
    {
        $row = false;

        if ($sql)
        {
            //$rs = $this->dbHandle->getRow($sql);

            // run the query and get a result handler
            $rs = $this->dbHandle->query($sql);

            if (MDB2::isError($rs))
            {
                //错误log
                $error_info = $rs->getUserInfo();

                //守护进程，需要清楚连接标记
                if ('DB Error: no database selected' == $rs->getMessage())
                {
                    $id = array_pop(array_keys($this->dbHandle->cfg));

                    self::close($id);
                }

                if (DB_DEBUG)
                {
                    array_push($error_info, $sql);

                    Yf_Log::log($error_info, Yf_Log::ERROR, 'db');
                }

                if ('cli'!= SAPI)
                {
                    die($rs->getUserInfo());
                }

                $row = false;
            }
            // lets just get row:0 and free the result
            $row = $rs->fetchRow();
            $rs->free();
        }

        return $row;
    }

    /**
     * 取得2d数据, 各种参数,根据需要, 可以修改本方法
     *
     * @param string $sql            运行的sql语句
     * @param string $fetch_mod        数据格式
     *
     * @return array/bool  query resultrow
     *
     * @access  public
     */
    public function getAll($sql=null, $fetch_mod=null)//取得所有数据
    {
        //MDB2::loadFile('Iterator');
        $rs = false;

        if ($sql)
        {
            //$rs = $this->dbHandle->queryAll($sql, true, true, 'MDB2_BufferedIterator');
            $rs = $this->dbHandle->queryAll($sql);

            if (MDB2::isError($rs))
            {
                //错误log
                $error_info = $rs->getUserInfo();

                //守护进程，需要清楚连接标记
                if ('DB Error: no database selected' == $rs->getMessage())
                {
                    $id = array_pop(array_keys($this->dbHandle->cfg));

                    self::close($id);
                }

                if (DB_DEBUG)
                {
                    array_push($error_info, $sql);

                    Yf_Log::log($error_info, Yf_Log::ERROR, 'db');
                }

                if ('cli'!= SAPI)
                {
                    die($rs->getUserInfo());
                }

                //throw new Exception($rs->getMessage());
                $rs = false;
            }
        }

        return $rs;
    }

    /**
     * 从数据库连接模式
     *
     * @param string $mode        数据格式
     *
     * @return bool   true/false
     *
     * @access  public
     */
    public static function setConnectMode($mode)//设置模式
    {
        self::$connectMode = $mode;

        return self::$connectMode;
    }

    /**
     * 从数据库读取数据模式
     *
     * @param string $fetch_mod        数据格式
     *
     * @return bool   true/false
     *
     * @access  public
     */
    public function setFetchMode($fetch_mod = null)//设置模式
    {
        $rs = false;

        if ($fetch_mod)
        {
            $this->fetchMode = $fetch_mod;

            $rs = $this->dbHandle->setFetchMode($fetch_mod);

            if (MDB2::isError($rs))
            {
                $rs = false;
            }
            else
            {
                $rs = true;
            }
        }

        return $rs;
    }

    /**
     * 返回数据库句柄
     *
     * @return self::dbHandle   Db Object
     *
     * @access  Db Object
     */
    public function getDbHandle()
    {
        return $this->dbHandle;
    }


    /**
     * 事务开始
     *
     * @param string $id  database id
     *
     * @return bool   true/false
     */
    public function startTransaction()
    {
        return $this->dbHandle->query('START TRANSACTION;');
    }

    /**
     * 提交事务
     *
     * @param string $id  database id
     *
     * @return bool   true/false
     */
    public function commit()
    {
        return $this->dbHandle->query('COMMIT;');
    }

    /**
     * 回滚事务
     *
     * @return bool   true/false
     */
    public function rollBack()
    {
        return $this->dbHandle->query('ROLLBACK;');
    }


    ///**
     //* 事务开始
     //*
     //* @param string $id  database id
     //*
     //* @return bool   true/false
     //*/
    //public function startTransaction()
    //{
        //return $this->dbHandle->autoCommit();
    //}

    ///**
     //* 提交事务
     //*
     //* @param string $id  database id
     //*
     //* @return bool   true/false
     //*/
    //public function commit()
    //{
        //$this->dbHandle->commit();
        //return $this->dbHandle->autoCommit(true);
    //}

    ///**
     //* 回滚事务
     //*
     //* @return bool   true/false
     //*/
    //public function rollBack()
    //{
        //$this->dbHandle->rollback();
        //return $this->dbHandle->autoCommit(true);
    //}


    /**
     * 检测数据库连接是否断开
     *
     * @param string $id  database id
     *
     * @access  Db Object
     */
    public function detectDbConnect()
    {
        //心跳机制检查当前连接是否正常
        if ($this->query('SELECT 1;'))
        {
            return true;
        }
        else
        {
            return false;
        }

        //if (!mysql_ping ($this->db))
        //{  
            ////here is the major trick, you have to close the connection (even though its not currently working) for it to recreate properly.  
            //mysql_close($this->db);  
            //$this->connect();  
        //}
    }

    /**
     * 返回所有的主机连接情况
     *
     * @param string $id  database id
     *
     * @access  Db Object
     */
    public function showDbHandleRow()
    {
        return self::$dbHandleRow;
    }

    /**
     * 记录错误信息
     *
     * @param mixed $sql 
     *
     * @access  Db Object
     */
    public function errorLog($sql)
    {
        $conf = array('mode' => 0600, 'timeFormat' => '%X %x');
        $Logger = Log::singleton('file', APP_PATH . '/data/logs/db.log', 'ident', $conf);
        $Logger->log($sql);
    }
}
?>
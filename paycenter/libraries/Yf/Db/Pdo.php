<?php
/**
 * PDO
 *
 * 通过PDO连接数据库
 *
 * @category   Framework
 * @package    Db
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo
 *
 */
class Yf_Db_Pdo
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
    private static $connectMode = true;

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
    private  static $drive        = 'PDO';

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
        try
        {
            $options = array(
                PDO::ATTR_TIMEOUT => 1,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "utf8";'
            );

            if ($cfg['charset'])
            {
                $options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES ' . $cfg['charset'];
                //$this->dbHandle->exec('SET NAMES ' . $cfg['charset']);
            }



            $cfg['dsn'] = 'mysql:dbname=' . $cfg['database'] . ';port=' . $cfg['port'] . ';host=' . $cfg['host'];
            $this->dbHandle = new PDO($cfg['dsn'], $cfg['user'], $cfg['password'], $options);

            /*
            $attributes = array(
                'PDO::PARAM_BOOL', 'PDO::PARAM_NULL', 'PDO::PARAM_INT', 'PDO::PARAM_STR', 'PDO::PARAM_LOB', 'PDO::PARAM_STMT', 'PDO::PARAM_INPUT_OUTPUT', 'PDO::FETCH_LAZY', 'PDO::FETCH_ASSOC', 'PDO::FETCH_NAMED', 'PDO::FETCH_NUM', 'PDO::FETCH_BOTH', 'PDO::FETCH_OBJ', 'PDO::FETCH_BOUND', 'PDO::FETCH_COLUMN', 'PDO::FETCH_CLASS', 'PDO::FETCH_INTO', 'PDO::FETCH_FUNC', 'PDO::FETCH_GROUP', 'PDO::FETCH_UNIQUE', 'PDO::ATTR_AUTOCOMMIT', 'PDO::ATTR_PREFETCH', 'PDO::ATTR_TIMEOUT', 'PDO::ATTR_ERRMODE', 'PDO::ATTR_SERVER_VERSION', 'PDO::ATTR_CLIENT_VERSION', 'PDO::ATTR_SERVER_INFO', 'PDO::ATTR_CONNECTION_STATUS', 'PDO::ATTR_CASE', 'PDO::ATTR_CURSOR_NAME', 'PDO::ATTR_CURSOR', 'PDO::ATTR_DRIVER_NAME', 'PDO::ATTR_ORACLE_NULLS', 'PDO::ATTR_PERSISTENT', 'PDO::ATTR_FETCH_CATALOG_NAMES', 'PDO::ATTR_FETCH_TABLE_NAMES', 'PDO::ERRMODE_SILENT', 'PDO::ERRMODE_WARNING', 'PDO::ERRMODE_EXCEPTION', 'PDO::CASE_NATURAL', 'PDO::CASE_LOWER', 'PDO::CASE_UPPER', 'PDO::FETCH_ORI_NEXT', 'PDO::FETCH_ORI_PRIOR', 'PDO::FETCH_ORI_FIRST', 'PDO::FETCH_ORI_LAST', 'PDO::FETCH_ORI_ABS', 'PDO::FETCH_ORI_REL', 'PDO::CURSOR_FWDONLY', 'PDO::CURSOR_SCROLL', 'PDO::ERR_NONE'
            );

            foreach ($attributes as $val)
            {
                fb($this->dbHandle->getAttribute(constant($val)), $val);
            }
            */

            $this->dbHandle->cfg[$id] = $cfg;
            //echo '连接主机:';
            //print_r($cfg);

            $this->setFetchMode(PDO::FETCH_ASSOC);

            if (self::$connectMode)
            {
                //存放共用主机数据信息
                //self::$dbHostRow[$id] = $cfg['host'];
                $host_var = str_replace('.', '_', $cfg['host']);

                self::$dbActiveRow[$host_var] = $id;
            }
        }
        catch(PDOException $e)
        {
            if ('cli' == SAPI)
            {
                echo 'Connection    failed:    ' . $e->getMessage();

                throw new Exception($e->getMessage());
            }
            else
            {
                throw new Exception(_('数据库连接失败！'));
                die('Connection    failed:    ' . $e->getMessage());
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
                        fb(' USE `' . $cfg['database'] . '`');
                        $rs = $connect->dbHandle->exec(' USE `' . $cfg['database'] . '`');

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
                fb(' USE `' . $cfg['database'] . '`');

                //可以判断当前是否已经use 这个db, 就不用下面这句了
                self::$dbHandleRow[$id]->dbHandle->exec(' USE `' . $cfg['database'] . '`');
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
                $db->dbHandle = null;
                unset(self::$dbHandleRow[$key]);
            }

            self::$dbActiveRow = array();
        }
        else
        {
            if (array_key_exists($id, self::$dbHandleRow))
            {
                foreach (self::$dbActiveRow as $host_tmp=>$id_tmp)
                {
                    if ($id_tmp == $id)
                    {
                        unset(self::$dbActiveRow[$host_tmp]);
                    }
                }

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
        if(is_object($this->dbHandle))
        {
            $rs = $this->dbHandle->query($sql);

            if (DB_DEBUG)
            {
                Yf_Log::log($sql, Yf_Log::LOG, 'db_query');
            }

            if (false === $rs)
            {
                //错误log
                $error_info = $this->dbHandle->errorInfo();

                //守护进程，需要清楚连接标记
                if('2006' == $error_info[1] || '2013' == $error_info[1])
                {
                    $id = array_pop(array_keys($this->dbHandle->cfg));

                    self::close($id);
                }

                if (DB_DEBUG)
                {
                    array_push($error_info, $sql);

                    Yf_Log::log(encode_json($error_info), Yf_Log::ERROR, 'db_error');
                }
            }
        }
        else
        {
            $rs = false;
        }

        return $rs;
    }

    /**
     * 对 pdo exec 封装, 如果sql运行失败， 在返回falsh， 否则返回int影响的行数， 包括 0
     *
     * @param string $sql  运行的sql语句
     *
     * @access  public
     */
    public function exec($sql = null)//执行SQL
    {
        if(is_object($this->dbHandle))
        {
            //$this->dbHandle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            $rs = $this->dbHandle->exec($sql);

            if (DB_DEBUG)
            {
                Yf_Log::log($sql, Yf_Log::LOG, 'db_exec');
                Yf_Log::log('effect_rows=' . $rs, Yf_Log::LOG, 'db_exec');
            }

            if (false === $rs)
            {
                //错误log
                $error_info = $this->dbHandle->errorInfo();

                //守护进程，需要清楚连接标记
                if('2006' == $error_info[1] || '2013' == $error_info[1])
                {
                    $id = array_pop(array_keys($this->dbHandle->cfg));

                    self::close($id);
                }

                array_push($error_info, $sql);
                Yf_Log::log(encode_json($error_info), Yf_Log::ERROR, 'db_error');
            }
            else
            {
                $rs = intval($rs);
            }
        }
        else
        {
            $rs = false;
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
        $last_insert_id = $this->dbHandle->lastInsertId();

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
        //return $this->dbHandle->affectedRows();
    }

    /**
     * Generates the name used inside the database for a sequence
     *
     * The createSequence() docblock contains notes about storing sequence
     * names.
     *
     * @param string $sqn  the sequence's public name
     *
     * @return string  the sequence's name in the backend
     *
     * @access protected
     * @see DB_common::createSequence(), DB_common::dropSequence(),
     *      DB_common::nextID(), DB_common::setOption()
     */
    function getSequenceName($sqn)
    {
        return sprintf('%s_seq', preg_replace('/[^a-z0-9_.]/i', '_', $sqn));
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
        $seqname = $this->getSequenceName($seq_name);
        do {
            $repeat = 0;

            $result = $this->dbHandle->exec("UPDATE ${seqname} ".
                'SET id=LAST_INSERT_ID(id+1)');

            if ($result)
            {
                // COMMON CASE
                $id = $this->insertId();
                if ($id != 0)
                {
                    return $id;
                }

                // EMPTY SEQ TABLE
                // Sequence table must be empty for some reason, so fill
                // it and return 1 and obtain a user-level lock
                $result_row = $this->dbHandle->getRow("SELECT GET_LOCK('${seqname}_lock',10)");

                if ($result_row === false)
                {
                    return false;
                }

                $result = $result_row[0];

                if ($result == 0)
                {
                    // Failed to get the lock
                    return false;
                }

                // add the default value
                $result = $this->exec("REPLACE INTO ${seqname} (id) VALUES (0)");

                if (false === $result)
                {
                    return false;
                }

                // Release the lock
                $result_row = $this->dbHandle->getRow('SELECT RELEASE_LOCK('
                    . "'${seqname}_lock')");
                if (false === $result_row)
                {
                    return false;
                }

                // We know what the result will be, so no need to try again
                return 1;
            }
            elseif ($ondemand && false===$result &&
                $this->getErrorCode() == 1146) //DB_ERROR_NOSUCHTABLE
            {
                // ONDEMAND TABLE CREATION
                $result = $this->createSequence($seq_name);
                if (false === $result)
                {
                    return false;
                }
                else
                {
                    $repeat = 1;
                }
            }
            elseif (false===$result &&
                $this->getErrorCode() == 1062) //DB_ERROR_ALREADY_EXISTS
            {
                echo '重复记录';

                return false;
                //重复记录
                // BACKWARDS COMPAT
                // see _BCsequence() comment
                /*
                $result = $this->_BCsequence($seqname);
                if (DB::isError($result)) {
                    return $this->raiseError($result);
                }
                */
                $repeat = 1;
            }
        } while ($repeat);

        return false;
    }

    // }}}
    // {{{ createSequence()

    /**
     * Creates a new sequence
     *
     * @param string $seq_name  name of the new sequence
     *
     * @return int  DB_OK on success.  A DB_Error object on failure.
     *
     * @see DB_common::createSequence(), DB_common::getSequenceName(),
     *      DB_mysql::nextID(), DB_mysql::dropSequence()
     */
    function createSequence($seq_name)
    {
        $seqname = $this->getSequenceName($seq_name);
        $res = $this->query('CREATE TABLE ' . $seqname
            . ' (id INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,'
            . ' PRIMARY KEY(id))');
        if (DB::isError($res)) {
            return $res;
        }
        // insert yields value 1, nextId call will generate ID 2
        $res = $this->query("INSERT INTO ${seqname} (id) VALUES (0)");
        if (DB::isError($res)) {
            return $res;
        }
        // so reset to zero
        return $this->query("UPDATE ${seqname} SET id = 0");
    }

    // }}}
    // {{{ dropSequence()

    /**
     * Deletes a sequence
     *
     * @param string $seq_name  name of the sequence to be deleted
     *
     * @return int  DB_OK on success.  A DB_Error object on failure.
     *
     * @see DB_common::dropSequence(), DB_common::getSequenceName(),
     *      DB_mysql::nextID(), DB_mysql::createSequence()
     */
    function dropSequence($seq_name)
    {
        return $this->query('DROP TABLE ' . $this->getSequenceName($seq_name));
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
        $rs = false;

        if ($sql)
        {
            $sth = $this->query($sql);

            if ($sth)
            {
                if (!$fetch_mod)
                {
                    $fetch_mod = $this->fetchMode;
                }

                $rs = $sth->fetch($fetch_mod);
                $sth = null;
            }
            else
            {
                /*
                //错误log
                $error_info = $this->dbHandle->errorInfo();

                //守护进程，需要清楚连接标记
                if('2006' == $error_info[1] || '2013' == $error_info[1])
                {
                    $id = array_pop(array_keys($this->dbHandle->cfg));

                    self::close($id);
                }

                if (DB_DEBUG)
                {
                    array_push($error_info, $sql);

                    Yf_Log::log($error_info, FirePHP::ERROR, 'db_error');
                }
                */

                $rs = false;
            }
        }

        return $rs;
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
        $rs = false;

        if ($sql)
        {
            $sth = $this->query($sql);

            if ($sth)
            {
                if (!$fetch_mod)
                {
                    $fetch_mod = $this->fetchMode;
                }

                $rs = $sth->fetchAll($fetch_mod);
                $sth = null;
            }
            else
            {
                /*
                //错误log
                $error_info = $this->dbHandle->errorInfo();

                //守护进程，需要清楚连接标记
                if('2006' == $error_info[1] || '2013' == $error_info[1])
                {
                    $id = array_pop(array_keys($this->dbHandle->cfg));

                    self::close($id);
                }

                if (DB_DEBUG)
                {
                    array_push($error_info, $sql);

                    Yf_Log::log($error_info, FirePHP::ERROR, 'db_error');
                }

                //throw new Exception($sth->errorInfo());
                */
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
        if ($fetch_mod)
        {
            $this->fetchMode = $fetch_mod;
        }

        return true;
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
        return $this->dbHandle->beginTransaction();
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
        return $this->dbHandle->commit();
    }

    /**
     * 回滚事务
     *
     * @return bool   true/false
     */
    public function rollBack()
    {
        return $this->dbHandle->rollBack();
    }

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

        $status = $this->dbHandle->getAttribute(PDO::ATTR_SERVER_INFO);

        if($status == 'MySQL server has gone away')
        {
            /* 进行PDO连接 */
        }
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
     * 返回数据库的错误代码
     *
     * @access  Db Object
     */
    public function getErrorCode()
    {
        $error_info = $this->dbHandle->errorInfo();
        return $error_info[1];
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


	/**
	 * 获取数据库版本
	 *
	 *
	 * @return string   db version
	 * @access  Db Object
	 */
	public function version()
	{
		$sql = 'select version() as version;';

		if ($version_row = $this->getRow($sql))
		{
			return $version_row['version'];
		}
		else
		{
			return false;
		}
	}
}
?>
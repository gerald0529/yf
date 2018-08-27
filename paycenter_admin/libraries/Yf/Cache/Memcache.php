<?php
/**
 * Memcahce 类
 * 
 *
 * edit the singleton() metod
 * and define the list of memcached servers in a 2-d array
 * in the format
 * array(
 * array('192.168.0.1'=>'11211'),
 * array('192.168.0.2'=>'11211'),
 * );
 * 
 * @category   Framework
 * @package    Cache
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 */
class Yf_Cache_Memcache
{
    /**
     * 对象本身
     * @var int
     */
    public static $_instance;
    public static $servers;

    public static $expire;
    public static $key;
    public static $cacheServer;

    public static $persistent = true; //控制是否使用持久化连接。默认TRUE。 
    public static $retry; //是否需要重试标记

    private $moduleName;

    /**
     * 构造函数
     *
     * @param array $servers
     */
    protected function __construct($servers = null) 
    {
        if ('cli' == SAPI)
        {
            self::$persistent = false;
        }
        else
        {
            self::$persistent = true;
        }
    }

    final public static function getInstance($name='data') 
    {
        if (!@(self::$_instance[$name] instanceof self))
        {
            self::$_instance[$name] = new self();

            self::$_instance[$name]->init($name);
        }

        return self::$_instance[$name];
    }

    public function init($name)
    {
        $this->moduleName = $name;

        $config_cache = Yf_Registry::get('config_cache');

        self::$servers = $config_cache['memcache'][$name];

        self::$cacheServer[$this->moduleName] = new Memcache();

        //（注：addServer没有连接到服务器的动作，所以在memcache进程没有启动的时候，执行addServer成功也会返回true）
        foreach (self::$servers as $key=>$host)
        {
            $flag =  self::$cacheServer[$this->moduleName]->addServer(key($host), current($host), self::$persistent, 1, 1, 1, true, array(self::$_instance[$name], 'failureCallback'));
        }

        //根据我的测试结果，setCompressThreshold方法会忽略Memcache::set的flag参数。
        //$this->cacheServer[$server_id]->setCompressThreshold(2048, 0.2);
    }

    /**
     * Returns the value stored in the memory by it's key
     *
     * @param string $key
     * 
     * @return mix
     */
    public function get($key, $group=null) 
    {
        self::$key = $key;
        $data = @self::$cacheServer[$this->moduleName]->get($key);

        return  $data;
    }

    /**
     * Store the value in the memcache memory (overwrite if key exists)
     *
     * @param string $key
     * @param mix $var
     * @param bool $compress
     * @param int $expire (seconds before item expires)
     * 
     * @return bool
     */
    public function save($var, $key=null, $group=null, $compress=0, $expire=null)
    {
        if (null === $expire)
        {
            $expire = self::$expire;
        }

        if (null === $key)
        {
            $key = self::$key;
        }

        //如果有组的概念
        if ($group)
        {
            //得到本组下的Key
            $group_key_row = $this->get($group);

            if (!is_array($group_key_row))
            {
                $group_key_row = array();
            }

            if (!in_array($key, $group_key_row))
            {
                $group_key_row[] = $key;
            }

            $this->set($group, $group_key_row);
        }
        fb($var, "saveCache($key)");
        return $this->set($key, $var, $compress, $expire);
    }

    /**
     * Store the value in the memcache memory (overwrite if key exists)
     *
     * @param string $key
     * @param mix $var
     * @param bool $compress
     * @param int $expire (seconds before item expires)
     * 
     * @return bool
     */
    public function set($key, $var, $compress=0, $expire=0)
    {
        return  self::$cacheServer[$this->moduleName]->set($key, $var, $compress ? MEMCACHE_COMPRESSED : null, $expire);
    }

    /**
     * Set the value in memcache if the value does not exist; returns FALSE if value exists
     *
     * @param sting $key
     * @param mix $var
     * @param bool $compress
     * @param int $expire
     * 
     * @return bool
     */
    public function add($key, $var, $compress = 0, $expire = 0) 
    {
        return self::$cacheServer[$this->moduleName]->add($key, $var, $compress ? MEMCACHE_COMPRESSED : null, $expire);
    }

    /**
     * Replace an existing value
     *
     * @param string $key
     * @param mix $var
     * @param bool $compress
     * @param int $expire
     * 
     * @return bool
     */
    public function replace($key, $var, $compress = 0, $expire = 0) 
    {
        return self::$cacheServer[$this->moduleName]->replace($key, $var, $compress ? MEMCACHE_COMPRESSED : null, $expire);
    }

    /**
     * Delete a record or set a timeout
     *
     * @param string $key
     * @param int $timeout
     * 
     * @return bool
     */
    public function delete($key, $timeout = 0) 
    {
        return self::$cacheServer[$this->moduleName]->delete($key, $timeout);
    }

    /**
     * Delete a record or set a timeout
     *
     * @param string $key
     * @param int $timeout
     * 
     * @return bool
     */
    public function remove($key, $timeout = 0) 
    {
        return self::$cacheServer[$this->moduleName]->delete($key, $timeout);
    }


    /**
     * Delete a record or set a timeout
     *
     * @param string $key
     * @param int $timeout
     * 
     * @return bool
     */
    public function deleteGroup($group, $timeout = 0) 
    {
        $group_key_row = $this->get($group);

        if (!empty($group_key_row))
        {
            foreach ($group_key_row as $key)
            {
                $this->delete($key, $timeout);
            }
        }

        $this->delete($group, $timeout);
    }

    public function clean($group, $timeout = 0) 
    {
        $group_key_row = $this->get($group);

        if (!empty($group_key_row))
        {
            foreach ($group_key_row as $key)
            {
                $this->delete($key, $timeout);
            }
        }

        $this->delete($group, $timeout);
    }

    /**
     * Increment an existing integer value
     *
     * @param string $key
     * @param mix $value
     * 
     * @return bool
     */
    public function increment($key, $value = 1) 
    {
        return self::$cacheServer[$this->moduleName]->increment($key, $value);
    }

    /**
     * Decrement an existing value
     *
     * @param string $key
     * @param mix $value
     * 
     * @return bool
     */
    public function decrement($key, $value = 1) 
    {
        return self::$cacheServer[$this->moduleName]->decrement($key, $value);
    }

    public function getExtendedStats() 
    {
        return self::$cacheServer[$this->moduleName]->getExtendedStats();
    }

    public function getStats() 
    {
        return self::$cacheServer[$this->moduleName]->getStats();
    }

    public function getVersion() 
    {
        return self::$cacheServer[$this->moduleName]->getVersion();
    }

    /**
     * Clear the cache
        如果要清空memcache的items，常用的办法是什么？杀掉重启？如果有n台memcache需要重启怎么办？挨个做一遍？

        很简单，假设memcached运行在本地的11211端口，那么跑一下命令行：
        $ echo ”flush_all” | nc localhost 11211

        注：flush并不会将items删除，只是将所有的items标记为expired
     *
     * @return void
     */
    public function flush() 
    {
        self::$cacheServer[$this->moduleName]->flush();
    }

    /**
     * close  all the memcache connect
     *
     * @return void
     */
    public function close() 
    {
        self::$cacheServer[$this->moduleName]->close();
    }

    /**
     * failure_callback
     *
     * @return void
     */
    public function failureCallback($host, $port, $error, $error_msg, $error_no) 
    {
        //日志记录，恢复正常操作

        $this->errorLog("SERVER|{$host}:{$port} ERROR_NO|{$error_no} ERROR_STR|{$error_msg} \nPOST=".var_export($_POST,true)."\nFILE={$_SERVER['SCRIPT_FILENAME']}");
    }

    /**
     * 记录错误信息
     *
     * @param mixed $log_str 
     *
     * @access  Db Object
     */
    public function errorLog($log_str)
    {
        $conf = array('mode' => 0600, 'timeFormat' => '%X %x');
        $Logger = Log::singleton('file', APP_PATH . '/data/logs/memcache.log', 'ident', $conf);
        $Logger->log($log_str);
    }

    final private function __clone() 
    {
    }



	/**
	 * Start the cache
	 *
	 * @param string $id cache id
	 * @param string $group name of the cache group
	 * @param boolean $doNotTestCacheValidity if set to true, the cache validity won't be tested
	 * @return boolean true if the cache is hit (false else)
	 * @access public
	 */
	function start($id, $group = 'default', $doNotTestCacheValidity = false)
	{
		$data = $this->get($id, $group, $doNotTestCacheValidity);
		if ($data !== false) {
			echo($data);
			return true;
		}
		ob_start();
		ob_implicit_flush(false);
		return false;
	}

	/**
	 * Stop the cache
	 *
	 * @access public
	 */
	function end()
	{
		$data = ob_get_contents();
		ob_end_clean();
		$this->save($data, $this->_id, $this->_group);
		echo($data);
	}

}
?>
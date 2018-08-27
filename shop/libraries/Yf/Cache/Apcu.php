<?php
/**
 * Apc 类
 *
 * 
 * @category   Framework
 * @package    Cache
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 */
class Yf_Cache_Apcu implements Yf_Cache_Interface
{
    /**
     * 对象本身
     * @var int
     */
    public static $_instance;

    public $expire;
    public $key;

    public static $cacheServer;

    public static $persistent = true; //控制是否使用持久化连接。默认TRUE。 
    public static $retry; //是否需要重试标记

	private $moduleName;


    /**
     * 构造函数
     *
     * @param array $servers
     */
	public function __construct($options = array(NULL))
    {
		//设置默认时效
		$this->expire = $options['lifeTime'];
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
		$this->key = $key;
		$this->group = $group;

        return  apcu_fetch($key);
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
            $expire = $this->expire;
        }

        if (null === $key)
        {
            $key = $this->key;
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

            DEBUG && fb($group_key_row, "saveGroupCache($group)");
            $this->set($group, $group_key_row);
        }

		DEBUG && fb($var, "{$this->moduleName} - saveCache($key)");
        return $this->set($key, $var, $expire);
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
    public function set($key, $var, $expire=0)
    {
		return apcu_store($key, $var, $expire);
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
    public function add($key, $var, $expire = 0) 
    {
        return apcu_add($key, $var, $expire);
    }

    /**
     * Delete a record or set a timeout
     *
     * @param string $key
     * @param int $timeout
     * 
     * @return bool
     */
    public function delete($key)
    {
        return apcu_delete($key);
    }

    /**
     * Delete a record or set a timeout
     *
     * @param string $key
     * @param int $timeout
     * 
     * @return bool
     */
    public function remove($key, $group=null, $timeout = 0)
    {
		return apcu_delete($key);
    }


    public function clean($group = 0, $timeout = 0)
    {
        $group_key_row = $this->get($group);

        if (!empty($group_key_row))
        {
            foreach ($group_key_row as $key)
            {
                $this->delete($key);
            }
        }

		return $this->delete($group);
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
        return apcu_inc($key, $value);
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
		return apcu_dec($key, $value);
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
		return apcu_clear_cache();
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
	function end($id, $group = 'default')
	{
		$data = ob_get_contents();
		ob_end_clean();
		$this->save($data, $id, $group);
		echo($data);
	}

}
?>
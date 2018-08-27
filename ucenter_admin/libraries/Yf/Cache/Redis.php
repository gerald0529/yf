<?php
/**
 * Redis 类
 * 
 *
 * edit the singleton() metod
 * and define the list of redisd servers in a 2-d array
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
class Yf_Cache_Redis
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

    final public static function getInstance($name='redis_data')
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

        self::$servers = $config_cache['redis'][$name];

        self::$cacheServer[$this->moduleName] = new Redis();

        //（注：addServer没有连接到服务器的动作，所以在redis进程没有启动的时候，执行addServer成功也会返回true）
        foreach (self::$servers as $_key=>$hosts)
        {
            //$flag =  self::$cacheServer[$this->moduleName]->addServer(key($host), current($host), self::$persistent, 1, 1, 1, true, array(self::$_instance[$name], 'failureCallback'));
            foreach ($hosts as $key => $host) {
                    if(strpos($host,'##')!==false){
                        $narr = explode("##",$host);
                        $host = $narr[0];
                        $authPassword = $narr[1];
                    }
                    
                    if (self::$persistent)
                    {
                        self::$cacheServer[$this->moduleName]->connect($key, $host);
                    }
                    else
                    {
                        self::$cacheServer[$this->moduleName]->pconnect($key, $host);
                    }
                    if($authPassword){
                           self::$cacheServer[$this->moduleName]->auth($authPassword);
                    }
            }
            
           
            break;
        }

    }


    public function startTrans($pipe=false)
    {
        if($pipe)
        {
            $result = self::$cacheServer[$this->moduleName]->multi(Redis::PIPELINE);
        }
        else
        {
            $result = self::$cacheServer[$this->moduleName]->multi(Redis::MULTI);
        }

        return $result;
    }

    public function commit()
    {
        return  self::$cacheServer[$this->moduleName]->exec();
    }

    public function rollback()
    {
        return self::$cacheServer[$this->moduleName]->discard();
    }

    public function watch($key)
    {
        return self::$cacheServer[$this->moduleName]->watch($key);
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
            
        if (is_array($key))
        {
            return  self::$cacheServer[$this->moduleName]->getMultiple($key);
        }
        else
        {
            return  self::$cacheServer[$this->moduleName]->get($key);
        }
        
    }

    public function exists($key)
    {
        return self::$cacheServer[$this->moduleName]->exists($key);
    }

    /**
     * Store the value in the redis memory (overwrite if key exists)
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

            $this->set($group, $group_key_row, $compress, $expire);
        }

        return $this->set($key, $var, $compress, $expire);
    }

    /**
     * Store the value in the redis memory (overwrite if key exists)
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
        if (0 === $expire)
        {
            return  self::$cacheServer[$this->moduleName]->set($key, $var);
        }
        else
        {
            return  self::$cacheServer[$this->moduleName]->setex($key, $expire, $var);
        }
    }

    /**
     * Set the value in redis if the value does not exist; returns FALSE if value exists
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
        return self::$cacheServer[$this->moduleName]->delete($key);
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


    public function expire($key, $time, $flag=1)
    {
        if($flag)
        {
            return self::$cacheServer[$this->moduleName]->expire($key, $time);
        }
        else
        {
            return self::$cacheServer[$this->moduleName]->expireAt($key, $time);
        }
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
        return self::$cacheServer[$this->moduleName]->incrBy($key, $value);
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
        return self::$cacheServer[$this->moduleName]->decrBy($key, $value);
    }
    
    //redis 独有的函数  redis 操作相关
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
        如果要清空redis的items，常用的办法是什么？杀掉重启？如果有n台redis需要重启怎么办？挨个做一遍？

        很简单，假设redisd运行在本地的11211端口，那么跑一下命令行：
        $ echo ”flush_all” | nc localhost 11211

        注：flush并不会将items删除，只是将所有的items标记为expired
     *
     * @return void
     */
    public function flush() 
    {
        //不提供次方法，防止误操作

        //清空当前数据库
        self::$cacheServer[$this->moduleName]->flushDB();

        //清空所有数据库
        //self::$cacheServer[$this->moduleName]->flushAll();
    }


    /**
     * select db
     *
     * @return void
     */
    public function select($db_name) 
    {
        self::$cacheServer[$this->moduleName]->select($db_name);
    }

    public function moveKey($key, $db_index)
    {
        self::$cacheServer[$this->moduleName]->move($key, $db_index);
    }

    /**
     * close  all the redis connect
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
        $Logger = Log::singleton('file', APP_PATH . '/data/logs/redis.log', 'ident', $conf);
        $Logger->log($log_str);
    }

    final private function __clone() 
    {
    } 


    //redis 独有的函数  list相关操作
    //在名称为key的list左边（头）添加一个值为value的 元素
    public function lPush($key, $value)
    {
        return self::$cacheServer[$this->moduleName]->lPush($key, $value);
    }
    
    public function rPush($key, $value)
    {
        return self::$cacheServer[$this->moduleName]->rPush($key, $value);
    }

    //在名称为key的list左边(头)/右边（尾）添加一个值为value的元素,如果value已经存在，则不添加
    public function lPushx($key, $value)
    {
        return self::$cacheServer[$this->moduleName]->lPushx($key, $value);
    }

    public function rPushx($key, $value)
    {
        return self::$cacheServer[$this->moduleName]->rPushx($key, $value);
    }

    //输出名称为key的list左(头)起/右（尾）起的第一个元素，删除该元素
    public function lPop($key)
    {
        return self::$cacheServer[$this->moduleName]->lPop($key);
    }

    public function rPop($key)
    {
        return self::$cacheServer[$this->moduleName]->rPop($key);
    }

    //lpop命令的block版本。即当timeout为0时，若遇到名称为key i的list不存在或该list为空，则命令结束。如果timeout>0，则遇到上述情况时，等待timeout秒，如果问题没有解决，则对keyi+1开始的list执行pop操作 $redis->blPop('key1', 'key2', 10);
    public function blPop($key1, $key2, $i)
    {
        return self::$cacheServer[$this->moduleName]->blPop($key1, $key2, $i);
    }

    public function brPop($key1, $key2, $i)
    {
        return self::$cacheServer[$this->moduleName]->brPop($key1, $key2, $i);
    }

    
    //返回名称为key的list有多少个元素
    public function lSize($key)
    {
        return self::$cacheServer[$this->moduleName]->lSize($key);
    }

    //返回名称为key的list中index位置的元素
    public function lGet($key, $index)
    {
        return self::$cacheServer[$this->moduleName]->lGet($key, $index);
    }

    //给名称为key的list中index位置的元素赋值为value
    public function lSet($key, $index, $value)
    {
        return self::$cacheServer[$this->moduleName]->lSet($key, $index, $value);
    }

    //返回名称为key的list中start至end之间的元素（end为 -1 ，返回所有）  lGetRange
    public function lRange($key, $start, $end=-1)
    {
        return self::$cacheServer[$this->moduleName]->lRange($key, $start, $end);
    }

    //截取名称为key的list，保留start至end之间的元素 listTrim
    public function lTrim($key, $start, $end)
    {
        return self::$cacheServer[$this->moduleName]->lTrim($key, $start, $end);
    }

    //lRem, lRemove
    //删除count个名称为key的list中值为value的元素。count为0，删除所有值为value的元素，count>0从头至尾删除count个值为value的元素，count<0从尾到头删除|count|个值为value的元素
    public function lRem($key, $value, $count)
    {
        return self::$cacheServer[$this->moduleName]->lRem($key, $value, $count);
    }

    public function keys($parttern)
    {
        $result = self::$cacheServer[$this->moduleName]->keys($parttern);

        return $result;
    }

    public function hashGet($hash, $key=null)
    {
        if(is_null($key))
        {
            $data = self::$cacheServer[$this->moduleName]->hGetAll($hash);
        }
        else
        {
            if(is_array($key))
            {
                foreach($key as $k => $v)
                {
                    $key[$k] = (string) $v;
                }

                $data = self::$cacheServer[$this->moduleName]->hMget($hash, $key);
            }
            else
            {
                $key = (string)$key;
                $data = self::$cacheServer[$this->moduleName]->hGet($hash, $key);
            }
        }

        return $data;
    }

    public function hashSet($hash, $key, $value=false)
    {
        if(is_array($key))
        {
            $result = self::$cacheServer[$this->moduleName]->hMset($hash, $key);
        }
        else
        {
            $result = self::$cacheServer[$this->moduleName]->hSet($hash, $key, $value);
        }

        return $result;
    }

    public function hashDelete($hash, $key)
    {
        return self::$cacheServer[$this->moduleName]->hDel($hash,$key);
    }

    public function hashKeys($hash)
    {
        $result = self::$cacheServer[$this->moduleName]->hKeys($hash);

        return $result;
    }

    public function hashIncr($hash,$key,$num = 1)
    {
        $result = self::$cacheServer[$this->moduleName]->hIncrBy($hash,$key, $num);

        return $result;
    }
    /**
     * 返回hash数组的长度
     */
    public function hashLen($hash)
    {
        $result = self::$cacheServer[$this->moduleName]->hLen($hash);

        return $result;
    }

    public function sAdd($key, $member)
    {
        $result = self::$cacheServer[$this->moduleName]->sAdd($key,$member);

        return $result;
    }

    public function sCard($key)
    {
        return self::$cacheServer[$this->moduleName]->sCard($key);
    }

    public function sMembers($key)
    {
        return self::$cacheServer[$this->moduleName]->sMembers($key);
    }

    public function sPop($key)
    {
        return self::$cacheServer[$this->moduleName]->sPop($key);
    }

    public function sRandMember($key)
    {
        return self::$cacheServer[$this->moduleName]->sRandMember($key);
    }

    public function sRem($key, $member)
    {
        return self::$cacheServer[$this->moduleName]->sRem($key, $member);
    }

    public function sIsMember($key, $member)
    {
        return self::$cacheServer[$this->moduleName]->sIsMember($key, $member);
    }

    public function zsetsAdd($sets, $member, $score)
    {
        $result = self::$cacheServer[$this->moduleName]->zAdd($sets, $score, $member);

        return $result;
    }

    public function zsetsDel($sets, $member)
    {
        $result = self::$cacheServer[$this->moduleName]->zRem($sets, $member);

        return $result;
    }

    public function zsetCard($key)
    {
        return self::$cacheServer[$this->moduleName]->zCard($key);
    }

    public function zsetsAll($sets, $asc=true, $start=0, $end=-1)
    {
        if($asc)
        {
            $data = self::$cacheServer[$this->moduleName]->zRange($sets, $start, $end);
        }
        else
        {
            $data = self::$cacheServer[$this->moduleName]->zRevRange($sets, $start, $end);
        }

        return $data;
    }

    public function zsetsAllByScore($sets, $scoreMin, $scoreMax, $asc=true, $start=null, $limit=null)
    {
        $option = array();
        if($start!==null && $limit!==null)
        {
            $option = array(
                'limit' => array($start, $limit),
            );
        }

        if($asc)
        {
            if(empty($option))
            {
                $data = self::$cacheServer[$this->moduleName]->zRangeByScore($sets, $scoreMin, $scoreMax);
            }
            else
            {
                $data = self::$cacheServer[$this->moduleName]->zRangeByScore($sets, $scoreMin, $scoreMax, $option);
            }
        }
        else
        {
            if(empty($option))
            {
                $data = self::$cacheServer[$this->moduleName]->zRevRangeByScore($sets, $scoreMax, $scoreMin);
            }
            else
            {
                $data = self::$cacheServer[$this->moduleName]->zRevRangeByScore($sets, $scoreMax, $scoreMin, $option);
            }
        }

        return $data;
    }
    
    public function zsetsSize($key)
    {
        $result = self::$cacheServer[$this->moduleName]->zSize($key);

        return $result;
    }
    
    public function zsetsScore($key, $member)
    {
        $result = self::$cacheServer[$this->moduleName]->zScore($key, $member);

        return $result;
    }
    /**
     * 返回 $min_score到$max_score之间的成员数量
     */
    public function zsetsCount($key, $min_score, $max_score)
    {
        $result = self::$cacheServer[$this->moduleName]->zCount($key, $min_score, $max_score);

        return $result;
    }
    
    public function zsetsRank($sets, $member, $asc=true)
    {
        if($asc)
        {
            $result = self::$cacheServer[$this->moduleName]->zRank($sets, $member);
        }
        else
        {
            $result = self::$cacheServer[$this->moduleName]->zRevRank($sets, $member);
        }

        return $result;
    }

    public function zsetsRange($sets, $start, $end,$asc=true)
    {
        if($asc)
        {
            $result = self::$cacheServer[$this->moduleName]->zRange($sets, $start, $end);
        }
        else
        {
            $result = self::$cacheServer[$this->moduleName]->zRevRange($sets, $start, $end);
        }

        return $result;
    }

    public function zsetRem($sets, $member)
    {
        return self::$cacheServer[$this->moduleName]->zRem($sets, $member);
    }
}
?>
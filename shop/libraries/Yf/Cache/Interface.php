<?php
/**
 * Interface 类
 *
 * 
 * @category   Framework
 * @package    Cache
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 */
interface Yf_Cache_Interface
{
    public function get($key, $group=null);
    public function save($var, $key=null, $group=null, $compress=0, $expire=null);
    //public function add($key, $var, $compress = 0, $expire = 0);
    //public function replace($key, $var, $compress = 0, $expire = 0);
    public function remove($key, $timeout = 0);

    public function clean($group, $timeout = 0);
    public function flush();

	public function start($id, $group = 'default', $doNotTestCacheValidity = false);
	public function end($id, $group = 'default');
}
?>
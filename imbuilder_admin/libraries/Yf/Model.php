<?php
/**
 * 数据模型
 *
 * 让所有模型类继承，此类主要为操作数据库设计
 *
 * @category   Framework
 * @package    Model
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo
 */
class Yf_Model
{
    public $_cacheKeyPrefix  = 'c|m|';
    public $_cacheName       = 'default';
    public $_tableName       = 'test';
    public $_tablePrimaryKey = 'test_id';
	public $_cacheFlag        = false;

    /**
     * 处理SQL语句类,
     * @access public
     * @var Yf_Sql
     */
    public $sql ;

    /**
     * 存放程序中各种消息
     * @access public
     * @var Yf_Msg
     */
    public $msg ;

    /**
     * @access public
     * @var User_Base
     */
    public $user;


	/**
	 * 加入主键名称，里面的内容为JSON，自动编解码
	 *
	 * @access public
	 * @var User_Base
	 */
	public $jsonKey		= array();
	public $htmlKey		= array();

    /**
     * Constructor
     *
     * @param string $db_id 指定需要连接的数据库Id
     * @param string $user  User Object
     * @return void
     */
    public function __construct(&$db_id=null, &$user=null)
    {
        //不继承SqlParse
        $this->sql  = new Yf_Sql($db_id);

        $this->msg  = Yf_Msg::getInstance();
        $this->user = &$user;
    }

	/**
	 * 自动检测数据表信息
	 * @access protected
	 * @return void
	 */
	public function _checkTableInfo()
	{
		// 如果不是Model类 自动记录数据表信息
		// 只在第一次执行记录
		if (empty($this->tableInfo))
		{
			// 如果数据表字段没有定义则自动获取
			if (false)
			{
				//读取cache
				$fields = array();
				$this->fields = $fields;
				return;
			}

			// 每次都会读取数据表信息
			$this->tableInfo = $this->_selectFields();

			if (true && $this->tableInfo)
			{
				//cache fields
			}
		}

		$this->_tablePrimaryKey = $this->tableInfo['Key']['PRI'];
		$this->fields           = $this->tableInfo['Field'];
	}

	/**
	 * 读取fields
	 *
	 * @return array $rows 信息
	 * @access protected
	 */
	protected function _selectFields()
	{
		//$sql = sprintf('show create table %s', $this->_tableName);
		//$sql = sprintf('show index from %s', $this->_tableName);
		$sql = sprintf('show columns from %s', $this->_tableName);
		$rs = $this->sql->getAll($sql);

		$rows = array();

		if ($rs)
		{
			foreach ($rs as $v)
			{
				if ($v['Key'])
				{
					$rows['Key'][$v['Key']] = $v['Field'];
				}

				$rows['Field'][] = $v['Field'];
			}
		}

		return $rows;
	}

    /**
     * 插入
     *
     * @param array $a 信息
     * @return bool $rs 是否成功
     * @access protected
     */
    protected function _insert(&$a)
    {
        $data_row = array();
        $sql = 'INSERT INTO ' . $this->_tableName . ' SET ';

        foreach ($a as $key=>$value)
        {
			if (in_array($key, $this->jsonKey))
			{
				$value = encode_json($value);
			}
			elseif (in_array($key, $this->htmlKey))
			{
				require_once LIB_PATH . '/HTMLPurifier.auto.php';
				$purifier = new HTMLPurifier();
				$value = $purifier->purify($value);
			}
			else
			{
				$value = htmlspecialchars($value);
			}

            $data_row[] = $key . "='" . mres($value) . "'";
        }

        $sql .= implode(', ' ,$data_row);//die("sql=".$sql);
        $rs = $this->sql->exec($sql);

        return $rs;
    }

    /**
     * 取得主键,不允许分页
     *
     * @return array $rows 信息
     * @access protected
     */
    protected function _selectKey()
    {
        $sql = 'SELECT  SQL_CALC_FOUND_ROWS ' . $this->_tablePrimaryKey . ' FROM ' . $this->_tableName;
		$sql .= $this->sql->getWhere();
		$sql .= $this->sql->getOrder();
		$sql .= $this->sql->getLimit();
        $rs = $this->sql->getAll($sql);

        $rows = array();

        if ($rs)
        {
            foreach ($rs as $v)
            {
                $rows[] = $v[$this->_tablePrimaryKey];
            }
        }

        return $rows;
    }

	/**
	 * 取得主键, 加入Limit Order等限制
	 *
	 * @return array $rows 信息
	 * @access protected
	 */
	protected function selectKeyLimit()
	{
		return $this->_selectKey();
	}

    /**
     * 取得
     *
     * @param bool $cache_del 删除缓存标记
     * @return array $rows 信息
     * @access protected
     */
    protected function _select()
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS * FROM ' . $this->_tableName;
        $sql .= $this->sql->getWhere();
		$sql .= $this->sql->getOrder();
        $sql .= $this->sql->getLimit();
        $rs = $this->sql->getAll($sql);

        $rows = array();

        if($rs)
        {
            foreach ($rs as $k=>$v)
            {
				foreach ($this->jsonKey as $col_key)
				{
					if (isset($v[$col_key]))
					{
						$v[$col_key] = decode_json($v[$col_key]);
					}
				}

				$v['id'] = $v[$this->_tablePrimaryKey];
                $rows[$v[$this->_tablePrimaryKey]] = $v;
            }
        }

        return $rows;
    }

    /**
     * 取得自定义字段，暂时不使用。
     *
     * @param  string $column 字段信息
     * @return array $rows 信息
     * @access protected
     */
    protected function _selectByColumn($column = '')
    {
		$col_str = ', ';

		if (is_array($column))
		{
			$col_str .= implode(', ', $column);
		}
		else
		{
			if ($column)
			{
				$col_str .= $column;
			}
			else
			{
				$col_str = '';
			}
		}

        $sql = 'SELECT SQL_CALC_FOUND_ROWS ' . $this->_tablePrimaryKey . $col_str . ' FROM ' . $this->_tableName;
		$sql .= $this->sql->getWhere();
		$sql .= $this->sql->getOrder();
		$sql .= $this->sql->getLimit();
        $rs = $this->sql->getAll($sql);

        $rows = array();

        if($rs)
        {
            foreach ($rs as $k=>$v)
            {
                $rows[$v[$this->_tablePrimaryKey]] = $v;
            }
        }

        return $rows;
    }

	/**
     * 取得
     *
     * @return array $rows 信息
     * @access protected
     */
    protected function _num()
    {
        $query = 'SELECT count(*) as num FROM ' . $this->_tableName;

        $query  .= $this->sql->getWhere();
		$row = $this->sql->getRow($query);

		return $row['num'];
    }

    /**
     * 取得行数
     *
     * @return array $rows 信息
     * @access protected
     */
    protected function _selectFoundRows()
    {
        $query = '
                SELECT
                    FOUND_ROWS() total';


        $row = $this->sql->getRow($query);

        return $row['total'];
    }

    /**
     * 更新
     *
     * @param array $a 更新的数据
     * @return bool $update_flag 是否成功
     * @access protected
     */
    protected function _update(&$a, $flag=null)
    {
        $sql = 'UPDATE ' . $this->_tableName . ' SET ';

        foreach($a as $key => $value)
        {
			if (in_array($key, $this->jsonKey))
			{
				$value = encode_json($value);
			}
			elseif (in_array($key, $this->htmlKey))
			{
				require_once LIB_PATH . '/HTMLPurifier.auto.php';
				$purifier = new HTMLPurifier();
				$value = $purifier->purify($value);
			}
			else
			{
				$value = htmlspecialchars($value);
			}

			$value = mres($value);


            if ($flag)
            {
                $data_row[] = $key . "=" . $key . ' + ' . $value;
            }
            else
            {
                $data_row[] =  $key . "= '" . $value . "'";
            }
        }

        $sql .= implode(',', $data_row);
        $sql  .= $this->sql->getWhere();
        $update_flag  = $this->sql->exec($sql);


        return $update_flag;
    }

    /**
     * 删除操作
     *
     * @return bool $del_flag 是否成功
     * @access protected
     */
    protected function _delete()
    {
        $sql = 'DELETE FROM ' . $this->_tableName;
        $sql .= $this->sql->getWhere();

        $del_flag = $this->sql->exec($sql);

        return $del_flag;
    }
    //end

    /**
     * 取得主键
     *
     * @param  int   $primary_key
     * @return array $rows 信息
     * @access public
     */
	protected function getKey($primary_value=null, $primary_key=null)
    {
        $rows = false;
        $cond = array();

        if (null == $primary_key)
        {
            $cond[$this->_cacheKeyPrefix] = $primary_value;
        }
        else
        {
            $cond[$primary_key] = $primary_value;
        }

        $rows = $this->getKeyByMultiCond($cond);

        /*
        $rows = false;

        $key = 'keys|' .$primary_value;

        if ($this->_cacheFlag)
        {
            $rows = $this->getCache($key);
        }

        if (false === $rows)
        {
            if ($primary_value)
            {
                $this->sql->setWhere($primary_key, $primary_value);
            }
            else
            {
                throw new Exception(_('need input primary_value'));
            }

            $rows = $this->_selectKey();

            if ($this->_cacheFlag && $rows)
            {
                $this->setCache($rows, $key);
            }
        }
        */

        return $rows;
    }

    /**
     * 删除缓存，为单条记录
     *
     * @param     mix $primary_value
     * @param     mxi $primary_key
     * @return bool $rs
     * @access protected
     */
	protected function removeKey($primary_value=null, $primary_key=null)
    {
        $cond = array();

        if (null == $primary_key)
        {
            $cond[$this->_cacheKeyPrefix] = $primary_value;
        }
        else
        {
            $cond[$primary_key] = $primary_value;
        }

        return $this->removeByMultiCond($cond);
    }

	protected function formatKV(&$item, $key)
    {
        $item = $key . '|' . $item;
    }

    //多条件获取主键， add， remove， 需要删除cache， edit修改到条件，需要删除, 为了规范导致不犯错误，可以在Model中定义Condition结构，严格按照来完成，删除的时候也不会漏掉
    /**
     * 根据多个条件取得主键 multiple condition
     *
     * @param  array $cond_row
     * @return array $rows 信息
     * @access public
     */
	protected function getKeyByMultiCond($cond_row, $order_row = array())
    {
		if ($cond_row)
		{
			foreach ($cond_row as $k=>$v)
			{
				$k_row = explode(':', $k);

				if (count($k_row) > 1)
				{
					$this->sql->setWhere($k_row[0], $v, $k_row[1]);
				}
				else
				{
					$this->sql->setWhere($k, $v);
				}

			}
		}
		else
		{
			//throw new Exception(_('need input cond_row'));
		}

		if ($order_row)
		{
			foreach ($order_row as $k=>$v)
			{
				$this->sql->setOrder($k, $v);
			}
		}

		$rows = $this->_selectKey();

        return $rows;
    }

    /**
     * 删除缓存，为单条记录， 条件为数组格式
     *
     * @param     int $id
     * @return bool $rs
     * @access protected
     */
	protected function removeByMultiCond($cond_row=null)
    {
        ksort($cond_row);
        array_walk($cond_row, array($this, "formatKV"));

        $key_endfix = implode('|', $cond_row);

        $key = 'keys|' . $key_endfix;

        return $this->removeCache($key);
    }


    /**
     * 取得
     *
     * @param   array $id
     * @return array $rows 信息
     * @access protected
     */
    protected function getCacheRow($id=null, &$need_cache_id_name)
    {
        fb($id);
        $rows = array();

        if (is_array($id))
        {
            if ($this->_cacheFlag)
            {
                $Yf_Cache = Yf_Cache::create($this->_cacheName);

                $cache_key = array();
                $cache_key_index = array();

                foreach ($id as $item)
                {
                    $cache_key[] = $this->_cacheKeyPrefix . $item;
                    $cache_key_index[$this->_cacheKeyPrefix . $item] = $item;
                }

                $rows_cache = array();

                //fix file cache
                $Yf_Registry = Yf_Registry::getInstance();

                if (!isset($Yf_Registry['config_cache'][$this->_cacheName]))
                {
                    $this->_cacheName = 'default';
                }

                if (1 == $Yf_Registry['config_cache'][$this->_cacheName]['cacheType'])
                {
                    foreach ($cache_key as $key_id)
                    {
                        $tmp_data = $Yf_Cache->get($key_id);

                        if (false === $tmp_data)
                        {

                        }
                        else
                        {
                            $rows_cache[$key_id] = $tmp_data;
                        }

                    }

                    //end fix file cache
                }
                else
                {
                    $rows_cache = $Yf_Cache->get($cache_key);
                }


                foreach ($cache_key as $val)
                {
                    if (!isset($rows_cache[$val]))
                    {
                        array_push($need_cache_id_name, $cache_key_index[$val]);
                    }
                    else
                    {
                        $rows = $rows + $rows_cache[$val];
                    }
                }

            }
        }

        //fb($rows, "getCacheRow(" . encode_json($id) . ",  " . encode_json($need_cache_id_name) . ")");
        return $rows;
    }

    /**
     * 取得单个缓存
     *
     * @param int $id id
     * @return array $rows 信息
     * @access protected
     */
    protected function getCache($id=null)
    {
        $rows = array();

        if ($this->_cacheFlag)
        {
            $Yf_Cache = Yf_Cache::create($this->_cacheName);

            if ($id)
            {
                if (is_array($id))
                {
                    $cache_key = array();

                    foreach($id as $k => $v)
                    {
                        $cache_key[] = $this->_cacheKeyPrefix . $v;
                    }
                }
                else
                {
                    $cache_key = $this->_cacheKeyPrefix . $id;
                }

            }
            else
            {
                $cache_key = $this->_cacheKeyPrefix . 'all';
            }

            $rows = $Yf_Cache->get($cache_key);
        }

        fb($cache_key, 'getCache');
        fb($rows);
        return $rows;
    }

    /**
     * 添加缓存，为多条记录
     *
     * @param   array $rows_db
     * @return array $rows 信息
     * @access protected
     */
	protected function setCacheRow($rows_db=null, $expire=null)
    {
        if ($this->_cacheFlag)
        {
            if (false !== $rows_db)
            {
                $Yf_Cache = Yf_Cache::create($this->_cacheName);

                foreach ($rows_db as $key=>$val)
                {
                    //fix file cache
                    $Yf_Registry = Yf_Registry::getInstance();

                    if (!isset($Yf_Registry['config_cache'][$this->_cacheName]))
                    {
                        $this->_cacheName = 'default';
                    }

                    if (1 == $Yf_Registry['config_cache'][$this->_cacheName]['cacheType'])
                    {
                        $Yf_Cache->save(array($key=>$val), $this->_cacheKeyPrefix . $key);
                    }
                    else
                    {
                        $Yf_Cache->save(array($key=>$val), $this->_cacheKeyPrefix . $key, null, 0, $expire);
                    }
                    //end fix file cache
                    /*
                    $Yf_Cache->save(array($key=>$val), $this->_cacheKeyPrefix . $key, null, 0, $expire);
                    //$this->setCache(array($key=>$val), $this->_cacheKeyPrefix . $key);
                    */
                }
            }
        }
    }

    /**
     * 添加缓存，为单条记录
     *
     * @param   array $rows_db
     * @return array $rows 信息
     * @access protected
     */
	protected function setCache($rows_db, $key=null, $expire=null)
    {
        if ($this->_cacheFlag)
        {
            if (false !== $rows_db)
            {
                $Yf_Cache = Yf_Cache::create($this->_cacheName);

                //fix file cache
                $Yf_Registry = Yf_Registry::getInstance();

                if (!isset($Yf_Registry['config_cache'][$this->_cacheName]))
                {
                    $this->_cacheName = 'default';
                }

                if (1 == $Yf_Registry['config_cache'][$this->_cacheName]['cacheType'])
                {
                    if ($key)
                    {
                        return $Yf_Cache->save($rows_db, $this->_cacheKeyPrefix . $key);
                    }
                    else
                    {
                        return $Yf_Cache->save($rows_db, null);
                    }
                }
                else
                {
                    if ($key)
                    {
                        return $Yf_Cache->save($rows_db, $this->_cacheKeyPrefix . $key, null, 0, $expire);
                    }
                    else
                    {
                        return $Yf_Cache->save($rows_db, null, null, 0, $expire);
                    }
                }
                //end fix file cache
            }
        }
    }

    /**
     * 删除缓存，为单条记录
     *
     * @param     int $id
     * @return bool $rs
     * @access protected
     */
	protected function removeCache($id=null)
    {
        $flag = false;

        if ($this->_cacheFlag)
        {
            $Yf_Cache = Yf_Cache::create($this->_cacheName);

            if ($id)
            {
                $cache_key = $this->_cacheKeyPrefix . $id;
            }
            else
            {
                $cache_key = $this->_cacheKeyPrefix . 'all';
            }

            $flag = $Yf_Cache->remove($cache_key);
        }

        return $flag;
    }

    protected function getCacheKey()
    {
        return  implode('|', func_get_args());
    }


	//由于代码相似度太高，故封装，特别需求，特别处理。 2015-1-20
	/**
	 * 根据主键值，从数据库读取数据
	 *
	 * @param  int   $table_primary_key_value  主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getOne($table_primary_key_value=null, $key_row=null)
	{
		$row = array();
		$rows = $this->get($table_primary_key_value);

		if ($rows)
		{
			$row = reset($rows);
		}

		return $row;
	}


	/**
	 * 根据多个条件取得
	 *
	 * @param  array $cond_row
	 * @return array $rows 信息
	 * @access public
	 */
	public function getByWhere($cond_row=array(), $order_row = array())
	{
		$key_rows = $this->getKeyByMultiCond($cond_row, $order_row);

		$data_rows = array();

		if ($key_rows)
		{
			$data_tmp_rows = $this->get($key_rows, null, $order_row);

			foreach ($key_rows as $id)
			{
				if (isset($data_tmp_rows[$id]))
				{
					$data_rows[$id] = $data_tmp_rows[$id];
				}
			}
		}


		return $data_rows;
	}


	/**
	 * 根据多个条件取得主键
	 *
	 * @param  array $cond_row
	 * @return array $rows 信息
	 * @access public
	 */
	public function getKeyByWhere($cond_row, $order_row = array())
	{
		$key_rows = $this->getKeyByMultiCond($cond_row, $order_row);

		return $key_rows;
	}


	/**
	 * 根据多个条件取得
	 *
	 * @param  array $cond_row
	 * @return array $rows 信息
	 * @access public
	 */
	public function getOneByWhere($cond_row, $order_row = array())
	{
		$this->sql->setLimit(0, 1);
		$key_rows = $this->getKeyByMultiCond($cond_row, $order_row);

		$data_row = array();

		if ($key_rows)
		{
			$data_rows = $this->get($key_rows, null, $order_row);

			if ($data_rows)
			{
				$data_row = reset($data_rows);
			}
		}

		return $data_row;
	}

	/**
	 * 根据多个条件取得
	 *
	 * @param  array $cond_row
	 * @return array $rows 信息
	 * @access public
	 */
	public function listByWhere($cond_row, $order_row = array(), $page=1, $rows=100)
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);
		$this->sql->setLimit($offset, $rows);

		$key_rows = $this->getKeyByMultiCond($cond_row, $order_row);

		//读取影响的函数, 和记录封装到一起.
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($key_rows)
		{
			$data_tmp_rows = $this->get($key_rows, null, $order_row);

			foreach ($key_rows as $id)
			{
				if (isset($data_tmp_rows[$id]))
				{
					$data_rows[$id] = $data_tmp_rows[$id];
				}
			}
		}

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $total;
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}


    /**
     * 根据主键值，从数据库读取数据
     *
     * @param  int   $table_primary_key_value  主键值
     * @return array $rows 返回的查询内容
     * @access public
     */
    protected function get($table_primary_key_value=null, $key_row=null, $order_row=array())
    {
        $rows = array();

		if ($order_row)
		{
			foreach ($order_row as $k=>$v)
			{
				$this->sql->setOrder($k, $v);
			}
		}

        if (is_array($table_primary_key_value))
        {
            if (!$table_primary_key_value)
            {
                throw new Exception(sprintf(_('need input array  table_primary_key_value: $_tableName=>%s'), $this->_tableName));
            }

            if ($this->_cacheFlag)
            {
                $need_cache_id_name = array();
                $rows = $this->getCacheRow($table_primary_key_value, $need_cache_id_name);
                $rows_db = array();

                if (!empty($need_cache_id_name))
                {
                    $this->sql->setWhere($this->_tablePrimaryKey, $need_cache_id_name, 'IN');
                    $rows_db = $this->_select();
                }

                $this->setCacheRow($rows_db);

                $rows = $rows + $rows_db;
            }
            else
            {
                $this->sql->setWhere($this->_tablePrimaryKey, $table_primary_key_value, 'IN');
                $rows = $this->_select();
            }
        }
        else
        {
            if ($this->_cacheFlag)
            {
                $rows = $this->getCache($table_primary_key_value);
            }

            if (($this->_cacheFlag && false===$rows) || !$rows)
            {
                if ($table_primary_key_value && '*'!=$table_primary_key_value)
                {
                    $this->sql->setWhere($this->_tablePrimaryKey, $table_primary_key_value);
                }
                else
                {
                    if ('*' != $table_primary_key_value)
                    {
                        throw new Exception(_('need input table_primary_key_value'));
                    }
                }

                $rows = $this->_select();

                if ($this->_cacheFlag && $rows)
                {
					if($table_primary_key_value != "*")
					{
						$this->setCache($rows, $table_primary_key_value);
					}
                    
                }
            }
        }

        if ($key_row && !empty($rows))
        {
            $rows = array_reset($key_row, $rows);
        }

        return $rows;
    }

    /**
     * 取得影响的行数
     *
     * @return int $num 行数
     * @access public
     */
    public function getFoundRows()
    {
        $num = $this->_selectFoundRows();

        return $num;
    }

    /**
     * 插入
     * @param array $field_row 信息
     * @return bool  是否成功
     * @access public
     */
	protected function add($field_row, $return_insert_id=false)
    {
        //防止cache有误，可以考虑清下对应的cache，容错
        $add_flag = $this->_insert($field_row);

        if ($add_flag && $return_insert_id)
        {
            $add_flag = $this->sql->insertId();
        }

        return $add_flag;
    }

    /**
     * 根据主键更新表内容
     * @param mix   $table_primary_key_value  主键
     * @param array $field_row   key=>value数组
     * @return bool $update_flag 是否成功
     * @access public
     */
	protected function edit($table_primary_key_value=null, $field_row, $flag=null)
    {
        $update_flag = false;

        if ($table_primary_key_value)
        {
            if (is_array($table_primary_key_value))
            {
                $this->sql->setWhere($this->_tablePrimaryKey, $table_primary_key_value, 'IN');
            }
            else
            {
                $this->sql->setWhere($this->_tablePrimaryKey, $table_primary_key_value);
            }

            $update_flag  = $this->_update($field_row, $flag);

            if ($this->_cacheFlag && $update_flag)
            {
                if (is_array($table_primary_key_value))
                {
                    foreach($table_primary_key_value as $key => $value)
                    {
                        $this->removeCache($value);
                        //$this->sql->setPreCache(array($this, "removeCache"), array($value));
                    }
                }
                else
                {
                    $this->removeCache($table_primary_key_value);
                    //$this->sql->setPreCache(array($this, "removeCache"), array($table_primary_key_value));
                }
            }

        }

        return $update_flag;
    }

    /**
     * 更新单个字段，主要是为了防止并发, 如果有多个字段更新需求，可以修改兼容。
     * @param mix   $table_primary_key_value
     * @param array $field_name
     * @param array $field_value_new
     * @param array $field_value_old
     * @return bool $update_flag 是否成功
     * @access public
     */
	protected function editSingleField($table_primary_key_value, $field_name, $field_value_new, $field_value_old, $flag=null)
    {
        $update_flag = false;

        if ($table_primary_key_value)
        {
            $this->sql->setWhere($this->_tablePrimaryKey, $table_primary_key_value);
            $this->sql->setWhere($field_name, $field_value_old);

            $field_row = array();
            $field_row[$field_name] = $field_value_new;

            $update_flag  = $this->_update($field_row, $flag);

            if ($this->_cacheFlag && $update_flag)
            {
                $this->removeCache($table_primary_key_value);
                //$this->sql->setPreCache(array($this, "removeCache"), array($table_primary_key_value));
            }
        }

        return $update_flag;
    }

    /**
     * 删除操作
     * @param  mix  $table_primary_key_value
     * @return bool $del_flag 是否成功
     * @access public
     */
	protected function remove($table_primary_key_value)
    {
        $del_flag = false;

        if ($table_primary_key_value)
        {
            if (is_array($table_primary_key_value))
            {
                $this->sql->setWhere($this->_tablePrimaryKey, $table_primary_key_value, 'IN');
            }
            else
            {
                $this->sql->setWhere($this->_tablePrimaryKey, $table_primary_key_value);
            }

            $del_flag = $this->_delete();

            if ($this->_cacheFlag && $del_flag)
            {
                if (is_array($table_primary_key_value))
                {
                    foreach($table_primary_key_value as $key => $value)
                    {
                        $this->removeCache($value);
                        //$this->sql->setPreCache(array($this, "removeCache"), array($value));
                    }
                }
                else
                {
                    $this->removeCache($table_primary_key_value);
                    //$this->sql->setPreCache(array($this, "removeCache"), array($table_primary_key_value));
                }
            }
        }

        return $del_flag;
    }
}
?>
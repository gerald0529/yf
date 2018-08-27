<?php

/**
 * 构造SQL语句
 *
 * 为了操作Db方便，让控制器更灵活的操作数据库。
 *
 * @category   Framework
 * @package    Db
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo
 */
class Yf_Sql
{
	private $where  = ' WHERE 1  ';        /* 构造Sql语句where        */
	private $limit  = ' LIMIT 500 ';        /* 构造Sql语句Limit        */
	private $order  =  '';
	private $db     = null;                /* 数据库对象            */
	private $dbId   = null;                /* 数据库对象id            */
	private $sqlRow = array();

	public $sql     = null;
	public $lastSql = null;

	public static $dbTransFlag   = false;
	public static $dbTransRow     = array();
	public static $logsTransRow   = array();
	public static $cachesTransRow = array();
	public static $redisTransRow  = array();

	public function __construct($db_id = null)
	{
		$this->dbId = $db_id;
	}

	public function setDbId($str){
		$this->dbId = $str;
	}

	/**
	 * get sqlWhere
	 *
	 * @return string  $this->where
	 * @access public
	 *
	 * @author 黄新泽
	 */

	public function getWhere()
	{
		$where = $this->where;
		$this->resetWhere();

		return $where;
	}

	/**
	 * set sqlWhere
	 *
	 * @param string $sql ;
	 * @access public
	 *
	 * @author 黄新泽
	 */
	public function setWhere($key, $val, $symbol = '=', $join = 'AND')
	{
		$key = trim($key);
		//$val = trim($val);

		$symbol = strtoupper($symbol);

		switch ($symbol)
		{
			case '=' :
			case '<' :
			case '>' :
			case '<=' :
			case '>=' :
			case '!=' :
			case '<>' :
				$val = untrim($val);
				$this->where .= ' ' . $join . ' ' . $key . $symbol . $val;
				break;
			case 'IN' :
			case 'NOT IN' :
				if (is_array($val))
				{
					$val = array_map('untrim', $val);
					$this->where .= ' ' . $join . ' ' . $key .' '. $symbol. ' (' . implode(',', $val) . ')';
				}
				else
				{
					$val = untrim($val);
					$this->where .= ' ' . $join . ' ' . $key .' '.  $symbol. ' (' . $val . ')';
				}

				break;
			case 'BETWEEN' :
				$val = untrim($val);

				$this->where .= ' ' . $join . ' ' . $key . ' ' . $symbol . ' ' . $val;
				break;
			case 'LIKE' :
				$val = untrim($val);

				$this->where .= ' ' . $join . ' ' . $key . ' LIKE ' . $val;
				break;
			default    :
				break;
		}

		return $this;
	}

	public function resetConditions()
	{
		$this->resetWhere();
		$this->resetLimit();
	}

	public function resetWhere()
	{
		$this->where = ' WHERE 1  ';
	}

	public function getLimit()
	{
		$limit = $this->limit;
		$this->resetLimit();
		return $limit;
	}

	/**
	 * set Limit
	 *
	 * @param    int $offset ;
	 * @param    int $rows ;
	 */
	public function setLimit($offset = 0, $rows = 500)
	{
		$offset = max($offset, 0);

		if ($rows <= 0)
		{
			$this->resetLimit();
		}
		else
		{
			$this->limit = ' LIMIT ' . $offset . ', ' . $rows;
		}

		return $this;
	}

	public function resetLimit()
	{
		$this->limit = ' LIMIT 500 ';
	}


	public function getOrder()
	{
		$order = $this->order;
		$this->resetOrder();
		return $order;
	}

	/**
	 * set Order
	 *
	 * @param    mix $order ;
	 * @param    string  $flag ;
	 */
	public function setOrder($order = null, $flag = 'ASC')
	{
		if (!$this->order)
		{
			$this->order = ' ORDER BY ' . $order . ' ' . strtoupper($flag);
		}
		else
		{
			$this->order .= ', ' . $order . ' ' . strtoupper($flag);
		}

		return $this;
	}

	public function resetOrder()
	{
		$this->order = '';
	}


	public function getGroup()
	{
		$group = $this->group;
		$this->resetGroup();

		return $group;
	}

	/**
	 * set Group
	 *
	 * @param    string $group ;
	 */
	public function setGroup($group = null)
	{
		if ($group)
		{
			$this->group = ' GROUP BY  ' . $group;
		}

		return $this;
	}

	public function resetGroup()
	{
		$this->group = '';
	}

	public function update($table, $value, $where = null)
	{
		$this->sql = 'UPDATE ' . $table . ' SET ' . $value;

		if ($where)
		{
			$this->sql .= ' WHERE ' . $where;
		}

		return $this->sql;
	}

	public function insert($table, $field = null, $value = null)
	{
		$this->sql = 'INSERT INTO ' . $table;

		if ($value)
		{
			$this->sql .= ' SET ' . $field;
		}
		elseif ($field)
		{

			$this->sql .= '(' . $field . ')';

			$this->sql .= ' VALUES(' . $value . ')';
		}
		else
		{

		}

		return $this->sql;
	}

	public function select($field = '*', $table, $where = null, $order = null, $limit = null)
	{
		$this->sql = 'SELECT ' . $field . ' FROM ' . $table;

		if ($where)
		{
			$this->sql .= ' WHERE ' . $where;
		}

		if ($order)
		{
			$this->sql .= ' ORDER BY ' . $order;
		}
		if ($limit)
		{
			$this->sql .= ' LIMIT ' . $limit;
		}

		return $this->sql;
	}

	public function delete($table, $where = null)
	{
		$this->sql = 'DELETE FROM ' . $table;

		if ($where)
		{
			$this->sql .= ' WHERE ' . $where;
		}

		return $this->sql;
	}

	public function exec($sql)
	{
		$this->getDb();

		$rs = $this->db->exec($sql);

		return $rs;
	}

	public function query($sql)
	{
		$this->getDb();

		$rs = $this->db->query($sql);

		return $rs;
	}


	public function sqlAdd($sql)
	{
		$this->sqlArr[] = $sql;
	}

	public function sqlRun()
	{
		foreach ($this->sqlArr as $sql)
		{
			if (!$this->exec($sql))
			{
				return false;
			}
		}

		return true;
	}

	public function affectedRows()
	{
		return $this->db->affectedRows();
	}

	public function insertId()
	{
		return intval($this->db->insertId());
	}

	public function getAll($sql)
	{
		$this->getDb();

		$this->lastSql = $sql;

		$rs = $this->db->getAll($sql);

		/*
		if (empty($rs))
		{
			return false;
		}
		else
		{
			return $rs;
		}
		*/

		return $rs;

	}

	public function getRow($sql)
	{
		$this->getDb();

		$this->lastSql = $sql;
		$rs            = $this->db->getRow($sql);

		if (empty($rs))
		{
			return false;
		}
		else
		{
			return $rs;
		}
	}

	public function getSql()
	{
		return $this->lastSql;
	}

	public function setDb($db)
	{
		$this->db = &$db;
	}

	public function getDb()
	{
		/*
		if (!$this->db ||  !$this->db->getDbHandle())
		{
			$this->db = Yf_Db::get($this->dbId);
		}

		return $this->db;
		*/
		return $this->resetDb();
	}

	public function resetDb()
	{
		$this->db = Yf_Db::get($this->dbId);

		return $this->db;
	}

	/**
	 * 事务开始
	 *
	 * @param string $id database id
	 *
	 * @return bool   true/false
	 */
	public function startTransaction()
	{
		if (self::$dbTransRow || !self::$dbTransFlag)
		{
			return $this->startTransactionDb();
		}
	}

	/**
	 * 提交事务
	 *
	 * @param string $id database id
	 *
	 * @return bool   true/false
	 */
	public function commit()
	{
		$rs   = false;
		$flag = false;

		if (self::$dbTransRow || !self::$dbTransFlag)
		{
			$flag = true;
		}

		if (true === self::exePreDb())
		{
			if (true === self::exePreRedis())
			{
				if ($flag)
				{
					$this->db->commit();
				}

				self::exePreCache();//提交memcache
				self::exePreLog();//提交日志

				$rs   =  true;
			}
			else
			{
				$rs   =  false;
			}
		}
		else
		{
			$rs   =  false;
		}

		if (!$rs)
		{
			$this->rollBack();
		}

		return $rs;
	}

	/**
	 * 回滚事务
	 *
	 * @return bool   true/false
	 */
	public function rollBack()
	{
		self::$dbTransRow     = array();
		self::$redisTransRow  = array();
		self::$cachesTransRow = array();
		self::$logsTransRow   = array();

		if ($this->db)
		{
			return $this->db->rollBack();
		}
	}


	/**
	 * 事务开始
	 *
	 * @param string $id database id
	 *
	 * @return bool   true/false
	 */
	public function startTransactionDb()
	{
		$this->getDb();

		return $this->db->startTransaction();
	}

	/**
	 * 提交事务
	 *
	 * @param string $id database id
	 *
	 * @return bool   true/false
	 */
	public function commitDb()
	{
		return $this->db->commit();
	}

	/**
	 * 回滚事务
	 *
	 * @return bool   true/false
	 */
	public function rollBackDb()
	{
		return $this->db->rollBack();
	}

	public function debugSql_1($sql, $type = 'e')
	{
		if ('s' == $type)
		{
			$handle = @fopen(ROOT_PATH . '/server/log/' . date('Ymd') . '-debug_select.sql', a);
		}
		else
		{
			$handle = @fopen(ROOT_PATH . '/server/log/' . date('Ymd') . '-debug.sql', a);
		}

		if ($handle)
		{
			if (fwrite($handle, $sql) === FALSE)
			{
			}

			fclose($handle);
		}
	}

	//$result=1,执行结果影响行数必须为1，否则不允许往下执行。$result=0，则表示$result>=0都成功，执行结果只要!==false,都算执行成功
	public function setPreDb($function, $param_arr, $result = 1)
	{
		$call_back          = array('function' => $function, 'param_arr' => $param_arr, 'result' => $result);
		self::$dbTransRow[] = $call_back;

		return true;
	}


	public function exePreDb()
	{
		$result = true;
		foreach (self::$dbTransRow as $key => $value)
		{
			$call_result = call_user_func_array($value['function'], $value['param_arr']);

			if (false === $call_result)
			{
				$result = false;
			}
			elseif (true === $call_result)
			{
				$result = true;
			}
			else
			{
				if (0 == $value['result'])
				{
					if ($call_result >= 0)
					{
						$result = true;
					}
				}
				else
				{
					if ($call_result === 1)
					{
						$result = true;
					}
					else
					{
						$result = false;
					}
				}
			}

			if (!$result)
			{
				$msg = sprintf('%s::%s(%s)', get_class($value['function'][0]), $value['function'][1], encode_json($value['param_arr']));

				Yf_Log::log(encode_json($msg), Yf_Log::ERROR, 'transaction');

				break;
			}

		}

		self::$dbTransRow = array();

		return $result;
	}

	public function setPreLog($function, $param_arr)
	{
		$call_back            = array('function' => $function, 'param_arr' => $param_arr);
		self::$logsTransRow[] = $call_back;
	}

	public function exePreLog()
	{
		foreach (self::$logsTransRow as $key => $value)
		{
			call_user_func_array($value['function'], $value['param_arr']);
		}

		self::$logsTransRow = array();

		return true;
	}

	public function setPreCache($function, $param_arr)
	{
		$call_back              = array('function' => $function, 'param_arr' => $param_arr);
		self::$cachesTransRow[] = $call_back;
	}

	public function exePreCache()
	{
		foreach (self::$cachesTransRow as $key => $value)
		{
			call_user_func_array($value['function'], $value['param_arr']);
		}

		self::$cachesTransRow = array();

		return true;
	}

	public function setPreRedis($function, $param_arr)
	{
		$call_back             = array('function' => $function, 'param_arr' => $param_arr);
		self::$redisTransRow[] = $call_back;

		return true;
	}

	public function exePreRedis()
	{
		if (self::$redisTransRow)
		{
			$objRedis = Yf_Cache::create('redis_data');
			$objRedis->startTrans();

			foreach (self::$redisTransRow as $key => $value)
			{
				$result = call_user_func_array($value['function'], $value['param_arr']);

				if (!$result)
				{
					$msg = sprintf('%s::%s(%s)', get_class($value['function'][0]), $value['function'][1], encode_json($value['param_arr']));

					Yf_Log::log(encode_json($msg), Yf_Log::ERROR, 'transaction');

					return false;
				}
			}

			$arr_result = $objRedis->commit();//var_dump($arr_result);die("kkkkk");

			if ($arr_result === false)
			{
				foreach (self::$redisTransRow as $key => $value)
				{
					$msg = sprintf('%s::%s(%s)', get_class($value['function'][0]), $value['function'][1], encode_json($value['param_arr']));
					Yf_Log::log(encode_json($msg), Yf_Log::ERROR, 'transaction');
				}

				return false;
			}

			self::$redisTransRow = array();
		}

		return true;
	}
}

?>
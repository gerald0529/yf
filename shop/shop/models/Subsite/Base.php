<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 *
 *
 * @category   Framework
 * @package    __init__
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2016, 黄新泽
 * @version    1.0
 * @todo
 */
class Subsite_Base extends Yf_Model
{
	public $_cacheKeyPrefix  = 'c|sub_site|';
	public $_cacheName       = 'sub_site';
	public $_tableName       = 'sub_site';
	public $_tablePrimaryKey = 'subsite_id';

	/**
	 * @param string $user User Object
	 * @var   string $db_id 指定需要连接的数据库Id
	 * @return void
	 */
	public function __construct(&$db_id = 'shop', &$user = null)
	{
		$this->_tableName = TABEL_PREFIX . $this->_tableName;
		$this->_cacheFlag = CHE;
		parent::__construct($db_id, $user);
		$this->treeAllKey = $this->_cacheKeyPrefix . 'tree|all_data';
	}

	/**
	 * 根据主键值，从数据库读取数据
	 *
	 * @param  int $subsite_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getSubsite($subsite_id = null, $sort_key_row = null)
	{
		$rows = array();
		$rows = $this->get($subsite_id, $sort_key_row);

		return $rows;
	}

	/**
	 * 插入
	 * @param array $field_row 插入数据信息
	 * @param bool $return_insert_id 是否返回inset id
	 * @param array $field_row 信息
	 * @return bool  是否成功
	 * @access public
	 */
	public function addSubsite($field_row, $return_insert_id = false)
	{
		$add_flag = $this->add($field_row, $return_insert_id);

		$Cache = Yf_Cache::create('base');
		$Cache->remove($this->treeAllKey);

		$subsite_row       = $this->getOne($add_flag);
		$sub_site_parent_id = $subsite_row['sub_site_parent_id'];
		$cache_key          = $this->_cacheKeyPrefix . 'sub_site_parent_id|' . $sub_site_parent_id;
		$Cache->remove($cache_key);

		if ($sub_site_parent_id != 0)
		{
			$subsite_par_row = $this->getOne($sub_site_parent_id);
			$subsite_par_id  = $subsite_par_row['sub_site_parent_id'];
		}
		else
		{
			$subsite_par_id = 0;
		}
		$cache_key = $this->_cacheKeyPrefix . 'sub_site_parent_id|' . $subsite_par_id;
		$Cache->remove($cache_key);
		
		return $add_flag;
	}

	/**
	 * 根据主键更新表内容
	 * @param mix $subsite_id 主键
	 * @param array $field_row key=>value数组
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editSubsite($subsite_id = null, $field_row)
	{	
		
		$update_flag = $this->edit($subsite_id, $field_row);
		$sql = $this->sql->getSql();
		$Cache = Yf_Cache::create('base');
		$Cache->remove($this->treeAllKey);

		$subsite_row       = $this->getOne($subsite_id);
		$sub_site_parent_id = $subsite_row['sub_site_parent_id'];
		$cache_key          = $this->_cacheKeyPrefix . 'sub_site_parent_id|' . $sub_site_parent_id;
		$Cache->remove($cache_key);


		return $subsite_id;
	}

	/**
	 * 更新单个字段
	 * @param mix $subsite_id
	 * @param array $field_name
	 * @param array $field_value_new
	 * @param array $field_value_old
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editSubsiteSingleField($subsite_id, $field_name, $field_value_new, $field_value_old)
	{
		$update_flag = $this->editSingleField($subsite_id, $field_name, $field_value_new, $field_value_old);

		$Cache = Yf_Cache::create('base');
		$Cache->remove($this->treeAllKey);

		$subsite_row       = $this->getOne($subsite_id);
		$sub_site_parent_id = $subsite_row['sub_site_parent_id'];
		$cache_key          = $this->_cacheKeyPrefix . 'sub_site_parent_id|' . $sub_site_parent_id;
		$Cache->remove($cache_key);


		return $update_flag;
	}


	/**
	 * 根据分类父类id读取子类信息,
	 *
	 * @param  int $sub_site_parent_id 父id
	 * @param  bool $recursive 是否子类信息
	 * @param  int $level 当前层级
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getSubsiteTreeData($sub_site_parent_id = 0, $recursive = true, $level = 0)
	{
		$subsite_data = array();
		
		//
		$level++;
		
		if (is_array($sub_site_parent_id))
		{
			$cond_row = array('sub_site_parent_id:in' => $sub_site_parent_id);
			
			$cache_key = $this->_cacheKeyPrefix . 'sub_site_parent_id|' . implode(':', $sub_site_parent_id);
		}
		else
		{
			$cond_row = array('sub_site_parent_id' => $sub_site_parent_id);
			
			$cache_key = $this->_cacheKeyPrefix . 'sub_site_parent_id|' . $sub_site_parent_id;
		}
		
		//设置cache
		$Cache = Yf_Cache::create('base');
		
		if ($subsite_rows = $Cache->get($cache_key))
		{
		}
		else
		{
			$subsite_rows = $this->getByWhere($cond_row, array('subsite_id' => 'ASC'));

			//类似数据可以放到前端整理
			foreach ($subsite_rows as $key => $subsite_row)
			{
				$subsite_row['parent_id'] = $subsite_row['sub_site_parent_id'];
				$subsite_row['name']      = $subsite_row['sub_site_name'];

				//for treegrid
				$subsite_row['level']          = $level;
				$subsite_row['subsite_level'] = $level;

				$subsite_row['subsite_icon'] = 'ui-icon-star';


				$subsite_row['expanded'] = false;
				$subsite_row['loaded']   = false;

				/*
				if ($recursive)
				{
					$rs = call_user_func_array(array($this, 'getSubsiteTreeData'), array($subsite_row['subsite_id'], $recursive, $level));

					if ($rs)
					{
						$subsite_row['is_leaf']       = false;
					}
					else
					{
						$subsite_row['is_leaf']       = true;
					}

					$subsite_data[$key] = $subsite_row;

					$subsite_data = array_merge($subsite_data, $rs);
				}
				else
				*/
				{
					//判断是否有子节点
					$rs = $this->getSubsiteChildId($subsite_row['subsite_id'], false);

					if ($rs)
					{
						$subsite_row['is_leaf'] = false;
					}
					else
					{
						$subsite_row['is_leaf'] = true;
					}

					$subsite_rows[$key] = $subsite_row;
					//$district_data[$key] = $subsite_row;
				}


			}

			$Cache->save($subsite_rows, $cache_key);
		}

		return $subsite_rows;
	}

	/**
	 * 读取子类id
	 *
	 * @param  int $sub_site_parent_id 主键值
	 * @param  bools $recursive 是否递归查询
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getSubsiteChildId($sub_site_parent_id = 0, $recursive = true)
	{
		$district_data = array();
		
		if (is_array($sub_site_parent_id))
		{
			$cond_row = array('sub_site_parent_id:in' => $sub_site_parent_id);
		}
		else
		{
			$cond_row = array('sub_site_parent_id' => $sub_site_parent_id);
		}
		
		$subsite_id_row = $this->getKeyByMultiCond($cond_row);
		
		if ($recursive && $subsite_id_row)
		{
			$rs = call_user_func_array(array(
										   $this,
										   'getSubsiteChildId'
									   ), array(
										   $subsite_id_row,
										   $recursive
									   ));
			
			$subsite_id_row = array_merge($subsite_id_row, $rs);
		}
		
		return $subsite_id_row;
	}
	
	
}

?>
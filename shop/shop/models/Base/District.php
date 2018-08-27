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
class Base_District extends Yf_Model
{
	public $_cacheKeyPrefix  = 'c|base_district|';
	public $_cacheName       = 'base';
	public $_tableName       = 'base_district';
	public $_tablePrimaryKey = 'district_id';

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
	 * @param  int $district_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getDistrict($district_id = null, $sort_key_row = null)
	{
		$rows = array();
		$rows = $this->get($district_id, $sort_key_row);

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
	public function addDistrict($field_row, $return_insert_id = false)
	{
		$add_flag = $this->add($field_row, $return_insert_id);

		$Cache = Yf_Cache::create('base');
		$Cache->remove($this->treeAllKey);

		$district_row       = $this->getOne($add_flag);
		$district_parent_id = $district_row['district_parent_id'];
		$cache_key          = $this->_cacheKeyPrefix . 'district_parent_id|' . $district_parent_id;
		$Cache->remove($cache_key);

		if ($district_parent_id != 0)
		{
			$district_par_row = $this->getOne($district_parent_id);
			$district_par_id  = $district_par_row['district_parent_id'];
		}
		else
		{
			$district_par_id = 0;
		}
		$cache_key = $this->_cacheKeyPrefix . 'district_parent_id|' . $district_par_id;
		$Cache->remove($cache_key);
		
		return $add_flag;
	}

	/**
	 * 根据主键更新表内容
	 * @param mix $district_id 主键
	 * @param array $field_row key=>value数组
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editDistrict($district_id = null, $field_row)
	{
		$update_flag = $this->edit($district_id, $field_row);

		$Cache = Yf_Cache::create('base');
		$Cache->remove($this->treeAllKey);

		$district_row       = $this->getOne($district_id);
		$district_parent_id = $district_row['district_parent_id'];
		$cache_key          = $this->_cacheKeyPrefix . 'district_parent_id|' . $district_parent_id;
		$Cache->remove($cache_key);


		return $update_flag;
	}

	/**
	 * 更新单个字段
	 * @param mix $district_id
	 * @param array $field_name
	 * @param array $field_value_new
	 * @param array $field_value_old
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editDistrictSingleField($district_id, $field_name, $field_value_new, $field_value_old)
	{
		$update_flag = $this->editSingleField($district_id, $field_name, $field_value_new, $field_value_old);

		$Cache = Yf_Cache::create('base');
		$Cache->remove($this->treeAllKey);

		$district_row       = $this->getOne($district_id);
		$district_parent_id = $district_row['district_parent_id'];
		$cache_key          = $this->_cacheKeyPrefix . 'district_parent_id|' . $district_parent_id;
		$Cache->remove($cache_key);


		return $update_flag;
	}

	/**
	 * 删除操作
	 * @param int $district_id
	 * @return bool $del_flag 是否成功
	 * @access public
	 */
	public function removeDistrict($district_id)
	{
		//判断是否有子类, 如果有,不允许删除
		$data_rows = $this->getDistrictTreeData($district_id, false);

		if ($data_rows)
		{
			$this->msg->setMessages(__('有子分类,不允许删除'));
			return false;
		}

		$district_row       = $this->getOne($district_id);
		$district_parent_id = $district_row['district_parent_id'];

		$del_flag = $this->remove($district_id);


		$Cache = Yf_Cache::create('base');
		$Cache->remove($this->treeAllKey);

		$cache_key = $this->_cacheKeyPrefix . 'district_parent_id|' . $district_parent_id;
		$Cache->remove($cache_key);

		return $del_flag;
	}
	
	
	/**
	 * 读取子类id
	 *
	 * @param  int $district_parent_id 主键值
	 * @param  bools $recursive 是否递归查询
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getDistrictChildId($district_parent_id = 0, $recursive = true)
	{
		$district_data = array();
		
		if (is_array($district_parent_id))
		{
			$cond_row = array('district_parent_id:in' => $district_parent_id);
		}
		else
		{
			$cond_row = array('district_parent_id' => $district_parent_id);
		}
        $this->sql->setLimit(0, 50000);
		$district_id_row = $this->getKeyByMultiCond($cond_row);
		
		if ($recursive && $district_id_row)
		{
			$rs = call_user_func_array(array(
										   $this,
										   'getDistrictChildId'
									   ), array(
										   $district_id_row,
										   $recursive
									   ));
			
			$district_id_row = array_merge($district_id_row, $rs);
		}
		
		return $district_id_row;
	}
	
	/**
	 * 根据分类父类id赌气子类信息,
	 *
	 * @param  int $district_parent_id 父id
	 * @param  bool $recursive 是否子类信息
	 * @param  int $level 当前层级
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getDistrictTreeData($district_parent_id = 0, $recursive = true, $level = 0)
	{
		$district_data = array();
		
		//
		$level++;
		
		if (is_array($district_parent_id))
		{
			$cond_row = array('district_parent_id:in' => $district_parent_id);
			
			$cache_key = $this->_cacheKeyPrefix . 'district_parent_id|' . implode(':', $district_parent_id);
		}
		else
		{
			$cond_row = array('district_parent_id' => $district_parent_id);
			
			$cache_key = $this->_cacheKeyPrefix . 'district_parent_id|' . $district_parent_id;
		}
		
		//设置cache
		$Cache = Yf_Cache::create('base');
		
		if ($district_rows = $Cache->get($cache_key))
		{
		}
		else
		{
            $this->sql->noLimit = true;
			$district_rows = $this->getByWhere($cond_row, array('district_displayorder' => 'ASC'));

			//类似数据可以放到前端整理
			foreach ($district_rows as $key => $district_row)
			{
				$district_row['parent_id'] = $district_row['district_parent_id'];
				$district_row['name']      = $district_row['district_name'];

				//for treegrid
				$district_row['level']          = $level;
				$district_row['district_level'] = $level;

				$district_row['district_icon'] = 'ui-icon-star';


				$district_row['expanded'] = false;
				$district_row['loaded']   = false;

				/*
				if ($recursive)
				{
					$rs = call_user_func_array(array($this, 'getDistrictTreeData'), array($district_row['district_id'], $recursive, $level));

					if ($rs)
					{
						$district_row['is_leaf']       = false;
					}
					else
					{
						$district_row['is_leaf']       = true;
					}

					$district_data[$key] = $district_row;

					$district_data = array_merge($district_data, $rs);
				}
				else
				*/
				{
					//判断是否有子节点
					$rs = $this->getDistrictChildId($district_row['district_id'], false);

					if ($rs)
					{
						$district_row['is_leaf'] = false;
					}
					else
					{
						$district_row['is_leaf'] = true;
					}

					$district_rows[$key] = $district_row;
					//$district_data[$key] = $district_row;
				}


			}

			$Cache->save($district_rows, $cache_key);
		}

		return $district_rows;
	}
	
	
	/*
	 * 获取所有父类id
	 */
	public function getDistrictParent($district_id, $recursive = true, $level = 100)
	{
		$district_level_row = array();
		$district_row       = $this->getOne($district_id);
		
		$district_parent_id = $district_row['district_parent_id'];
		
		if ($district_parent_id)
		{
			$district_parent_row = $this->getOne($district_parent_id);
			
			if ($district_parent_row)
			{
				$level--;
				$district_level_row[$level] = $district_parent_row;
				
				if ($recursive)
				{
					$rs = call_user_func_array(array(
												   $this,
												   'getDistrictParent'
											   ), array(
												   $district_parent_id,
												   $recursive,
												   $level
											   ));
					
					$district_level_row = $district_level_row + $rs;
				}
				
			}
		}
		
		
		ksort($district_level_row);
		return $district_level_row = array_values($district_level_row);
	}
    
    
    /**
     *  获取分站最底层地区
     * @param type $district_parent_id
     * @return type
     */
    public function getSubsiteDistrictId($district_parent_id=array()){
       $child_array = array();
       foreach ($district_parent_id as $value){
           $data1 = $this->getDistrictChildId($value,false);
           if($data1){
               //有子集
               $data2 = $this->getDistrictChildId($data1,false);
               if($data2){
                   $data3 = $this->getDistrictChildId($data2,false);
                   if($data3){
                       $data4 = $this->getDistrictChildId($data2,false);
                       if($data4){
                           $child_array = array_merge($child_array,$data4);
                       }else{
                           $child_array = array_merge($child_array,$data3);
                       }
                   }else{
                       $child_array = array_merge($child_array,$data2);
                   }
               }else{
                   $child_array = array_merge($child_array,$data1);
               }
           }else{
               //没有子集
               $child_array[] = $value;
           }
       }
       return $child_array;
	}
    
}

?>
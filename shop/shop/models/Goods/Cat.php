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
class Goods_Cat extends Yf_Model
{
	public $_cacheKeyPrefix  = 'c|goods_cat|';
	public $_cacheName       = 'base';
	public $_tableName       = 'goods_cat';
	public $_tablePrimaryKey = 'cat_id';

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
		$this->catListAll = $this->_cacheKeyPrefix . 'cat_list|all_data';


	}

	/**
	 * 根据主键值，从数据库读取数据
	 *
	 * @param  int $cat_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCat($cat_id = null, $sort_key_row = null)
	{
		$rows = array();
		$rows = $this->get($cat_id, $sort_key_row);

		return $rows;
	}


	/**
	 * 读取子类id
	 *
	 * @param  int $cat_parent_id 主键值
	 * @param  bools $recursive 是否递归查询
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCatChildId($cat_parent_id = 0, $recursive = true)
	{
		$cat_data = array();

		if (is_array($cat_parent_id))
		{
			$cond_row = array('cat_parent_id:in' => $cat_parent_id);
		}
		else
		{
			$cond_row = array('cat_parent_id' => $cat_parent_id);
		}

		$cat_id_row = $this->getKeyByMultiCond($cond_row);

		if ($recursive && $cat_id_row)
		{
			$rs = call_user_func_array(array(
										   $this,
										   'getCatChildId'
									   ), array(
										   $cat_id_row,
										   $recursive
									   ));

			$cat_id_row = array_merge($cat_id_row, $rs);
		}

		return $cat_id_row;
	}

	/**
	 * 根据分类父类id赌气子类信息,
	 *
	 * @param  int $cat_parent_id 父id
	 * @param  bool $recursive 是否子类信息
	 * @param  int $level 当前层级
	 * @param  bool $filter 是否过滤分类
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCatTreeData($cat_parent_id = 0, $recursive = true, $level = 0, $filter = false)
	{
		$cat_data = array();

		//
		$level++;

		if (is_array($cat_parent_id))
		{
			$cond_row = array('cat_parent_id:in' => $cat_parent_id);

			$cache_key = $this->_cacheKeyPrefix . 'cat_parent_id|' . implode(':', $cat_parent_id);
		}
		else
		{
			$cond_row = array('cat_parent_id' => $cat_parent_id);

			$cache_key = $this->_cacheKeyPrefix . 'cat_parent_id|' . $cat_parent_id;
		}

		//设置cache
		$Cache = Yf_Cache::create('base');

		if ($cat_rows = $Cache->get($cache_key))
		{
		}
		else
		{
			$this->sql->noLimit = true;
			$cat_rows = $this->getByWhere($cond_row, array('cat_displayorder' => 'ASC'));
			$Cache->save($cat_rows, $cache_key);
		}

		if ($filter)
		{
			$this->filterCatTreeData($cat_rows);
		}

		//类似数据可以放到前端整理
		foreach ($cat_rows as $key => $cat_row)
		{
			$cat_row['parent_id'] = $cat_row['cat_parent_id'];
			//$supply_type_row['detail']      = true;
			$cat_row['type_number'] = 'trade';
			//$supply_type_row['status']      = 0;
			//$supply_type_row['remark']      = null;
			$cat_row['name'] = $cat_row['cat_name'];

			//for treegrid
			$cat_row['level']     = $level;
			$cat_row['cat_level'] = $level;

			$cat_row['cat_icon'] = 'ui-icon-star';


			$cat_row['expanded'] = false;
			$cat_row['loaded']   = true;

			if ($recursive)
			{
				$rs = call_user_func_array(array(
											   $this,
											   'getCatTreeData'
										   ), array(
											   $cat_row['cat_id'],
											   $recursive,
											   $level
										   ));

				if ($rs)
				{
					$cat_row['is_leaf'] = false;
				}
				else
				{
					$cat_row['is_leaf'] = true;
				}

				$cat_data[] = $cat_row;

				$cat_data = array_merge($cat_data, $rs);
			}
			else
			{
				$cat_row['is_leaf'] = true;
				$cat_data[]         = $cat_row;
			}

		}

		return $cat_data;
	}

    /*
     * 过滤商品分类数据
     * @param array $cat_rows 商品分类数据
     * @return array $cat_rows 过滤后的商品分类数据
     */
	public function filterCatTreeData(&$cat_rows)
	{
		//过滤数据
		$shop_id        = Perm::$shopId;
		$Shop_BaseModel = new Shop_BaseModel();
		$shop_base      = $Shop_BaseModel->getBase($shop_id);
		$shop_base      = pos($shop_base);

		if ($shop_base['shop_self_support'] == Shop_BaseModel::SELF_SUPPORT_TRUE && $shop_base['shop_all_class'] == Shop_BaseModel::SHOP_ALL_CLASS_TRUE)
		{
			return true;
		}
		else
		{
			$Shop_ClassBindModel = new Shop_ClassBindModel();
			$class_list          = $Shop_ClassBindModel->getByWhere( array('shop_id' => $shop_id, 'shop_class_bind_enable'=>Shop_ClassBindModel::PASS_VERIFY) );

			if ($product_class_ids = array_unique(array_column($class_list, 'product_class_id')))
			{
				//获取所有父类id
				foreach ($product_class_ids as $cat_id)
				{
					$father_cat_ids = $this->getCatParent($cat_id);
					if (!empty($father_cat_ids))
					{
						foreach ($father_cat_ids as $k => $v)
						{
							$product_class_ids[] = $v['cat_id'];
						}
					}

					$cat_child_rows = $this->getCatChildId($cat_id);
					if (!empty($cat_child_rows))
					{
						$product_class_ids = array_merge($product_class_ids, $cat_child_rows);
					}
					/*else
					{
						return true;
					}*/
				}

				$product_class_ids = array_unique($product_class_ids);

				foreach ($cat_rows as $key => $val)
				{
					if (!in_array($val['cat_id'], $product_class_ids))
					{
						unset($cat_rows[$key]);
					}
				}
			}
			else
			{
				$cat_rows = array();
			}
		}
	}

	/**
	 * 插入
	 * @param array $field_row 插入数据信息
	 * @param bool $return_insert_id 是否返回inset id
	 * @param array $field_row 信息
	 * @return bool  是否成功
	 * @access public
	 */
	public function addCat($field_row, $return_insert_id = true)
	{
		$add_flag = $this->add($field_row, $return_insert_id);

		$Cache = Yf_Cache::create('base');
		$Cache->remove($this->treeAllKey);
		$Cache->remove($this->catListAll);

		if ($field_row['cat_parent_id'])
		{
			/*$cat_row       = $this->getOne($field_row['cat_parent_id']);
			$cat_parent_id = $cat_row['cat_parent_id'];
			if ($cat_parent_id)
			{
				$cache_key = $this->_cacheKeyPrefix . 'cat_parent_id|' . $cat_parent_id;
				$Cache->remove($cache_key);
			}*/

            $cat_parent_id = $field_row['cat_parent_id'];
            $cache_key     = $this->_cacheKeyPrefix . 'cat_parent_id|' . $cat_parent_id;
            $Cache->remove($cache_key);

            //祖父分类类表数据
            $cat_parent_row = $this->getOne($field_row['cat_parent_id']);
            if ($cat_parent_row['cat_parent_id'])
            {
                $cat_parent_id = $cat_parent_row['cat_parent_id'];
                $cache_key     = $this->_cacheKeyPrefix . 'cat_parent_id|' . $cat_parent_id;
                $Cache->remove($cache_key);
            }
		}
		else
		{
			$cache_key = $this->_cacheKeyPrefix . 'cat_parent_id|' . 0;
			$Cache->remove($cache_key);
		}
		//


		//$this->removeKey($cat_id);
		return $add_flag;
	}

	/**
	 * 根据主键更新表内容
	 * @param mix $cat_id 主键
	 * @param array $field_row key=>value数组
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editCat($cat_id = null, $field_row)
	{
		$update_flag = $this->edit($cat_id, $field_row);

		$Cache = Yf_Cache::create('base');
		$Cache->remove($this->treeAllKey);
		$Cache->remove($this->catListAll);

		$cat_row = $this->getOne($cat_id);
		if ($cat_row['cat_parent_id'])
		{
			$cat_parent_id = $cat_row['cat_parent_id'];
			$cache_key     = $this->_cacheKeyPrefix . 'cat_parent_id|' . $cat_parent_id;
			$Cache->remove($cache_key);

			//祖父分类类表数据
			$cat_parent_row = $this->getOne($cat_row['cat_parent_id']);
			if ($cat_parent_row['cat_parent_id'])
			{
				$cat_parent_id = $cat_parent_row['cat_parent_id'];
				$cache_key     = $this->_cacheKeyPrefix . 'cat_parent_id|' . $cat_parent_id;
				$Cache->remove($cache_key);
			}
		}
		else
		{
			$cache_key = $this->_cacheKeyPrefix . 'cat_parent_id|' . 0;
			$Cache->remove($cache_key);
		}

		return $update_flag;
	}

	/**
	 * 更新单个字段
	 * @param mix $cat_id
	 * @param array $field_name
	 * @param array $field_value_new
	 * @param array $field_value_old
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editCatSingleField($cat_id, $field_name, $field_value_new, $field_value_old)
	{
		$update_flag = $this->editSingleField($cat_id, $field_name, $field_value_new, $field_value_old);

		$Cache = Yf_Cache::create('base');
		$Cache->remove($this->treeAllKey);
		$Cache->remove($this->catListAll);

		return $update_flag;
	}

	/**
	 * 删除操作
	 * @param int $cat_id
	 * @return bool $del_flag 是否成功
	 * @access public
	 */
	public function removeCat($cat_id)
	{
		//判断是否有子类, 如果有,不允许删除
		$data_rows = $this->getCatTreeData($cat_id, false);

		if ($data_rows)
		{
			$this->msg->setMessages(__('有子分类,不允许删除'));
			return false;
		}

		$cat_row       = $this->getOne($cat_id);
		$cat_parent_id = $cat_row['cat_parent_id'];

		$del_flag = $this->remove($cat_id);

		$Cache = Yf_Cache::create('base');
		$Cache->remove($this->treeAllKey);
		$Cache->remove($this->catListAll);

		$cache_key = $this->_cacheKeyPrefix . 'cat_parent_id|' . $cat_parent_id;
        if($cache_key)
        {
            $Cache->remove($cache_key);
        }

		//祖父分类类表数据
        if($cat_row['cat_parent_id'])
        {
            $cat_parent_row = $this->getOne($cat_row['cat_parent_id']);
            $cat_parent_id  = $cat_parent_row['cat_parent_id'];
            $cache_key      = $this->_cacheKeyPrefix . 'cat_parent_id|' . $cat_parent_id;
            if($cache_key)
            {
                $Cache->remove($cache_key);
            }
        }

		return $del_flag;
	}

	/*
	 * 获取所有父类及父级的子分类
	 * @param int $cat_id 分类id
	 * @param $recursive bool 是否循环
	 * @param int $level 分类等级
	 */
	public function getCatParentTree($cat_id, $recursive = true, $level = 100)
	{
		$parent_cat = $this->getCatParent($cat_id, $recursive = true, $level = 100);

		$data = $this->getOne(array('cat_id' => $cat_id));

		$parent_cat[] = $data;

		if ($parent_cat)
		{
			foreach ($parent_cat as $key => $val)
			{
				if ($val['cat_parent_id'] != 0)
				{
					$silbing                     = $this->getByWhere(array('cat_parent_id' => $val['cat_parent_id'],'cat_id:!='=> $val['cat_id']));
					$parent_cat[$key]['silbing'] = $silbing;
				}
			}
		}

		return $parent_cat;

	}


	/*
	 * 获取所有父类id
	 * @param int $cat_id 商品分类id
	 * @param bool $recursive 是否循环
	 * @param int $level 分类等级
	 * @return array $cat_level_row 查询结果
	 */
	public function getCatParent($cat_id, $recursive = true, $level = 100)
	{
		$cat_level_row = array();
		$cat_row       = $this->getOne($cat_id);

		if (!empty($cat_row))
		{
			$cat_parent_id = $cat_row['cat_parent_id'];

			if ($cat_parent_id)
			{
				$cat_parent_row = $this->getOne($cat_parent_id);

				if ($cat_parent_row)
				{
					$level--;
					$cat_level_row[$level] = $cat_parent_row;

					if ($recursive)
					{
						$rs = call_user_func_array(array(
													   $this,
													   'getCatParent'
												   ), array(
													   $cat_parent_id,
													   $recursive,
													   $level
												   ));

						$cat_level_row = $cat_level_row + $rs;
					}

				}
			}


			ksort($cat_level_row);
			return $cat_level_row = array_values($cat_level_row);
		}
		else
		{
			return "";
		}
	}
    
    /**
     * 清除缓存
     * @param type $type
     * @return boolean
     */
    public function removeCatCache($type='all') {
        $Cache = Yf_Cache::create('base');
        switch ($type){
            case 'list':
                $Cache->remove($this->catListAll);  //清除分类缓存
                break;
            case 'tree':
                $Cache->remove($this->treeAllKey);  //清除分类缓存
                break;
            case 'all':
                $Cache->remove($this->catListAll);  //清除分类缓存
                $Cache->remove($this->treeAllKey);  //清除分类缓存
                Perm::removeIndexCache();
                break;
            default :
                $Cache->remove($this->catListAll);  //清除分类缓存
                $Cache->remove($this->treeAllKey);  //清除分类缓存
                Perm::removeIndexCache();
                break;
        }
        return true;
    }
    
}

?>
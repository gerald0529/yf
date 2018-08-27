<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/20
 * Time: 15:42
 */
class GroupBuy_Area extends Yf_Model
{
	public $_cacheKeyPrefix  = 'c|groupbuy_area|';
	public $_cacheName       = 'groupbuy';
	public $_tableName       = 'groupbuy_area';
	public $_tablePrimaryKey = 'groupbuy_area_id';

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

	/*
	* 添加团购地区
	* */
	public function addGroupBuyArea($field_row, $return_insert_id)
	{
		$add_flag = $this->add($field_row, $return_insert_id);

		$Cache = Yf_Cache::create('groupbuy');
		$Cache->remove($this->treeAllKey);

		$district_row            = $this->getOne($add_flag);
		$groupbuy_area_parent_id = $district_row['groupbuy_area_parent_id'];

		if ($groupbuy_area_parent_id != 0)
		{
			$district_par_row     = $this->getOne($groupbuy_area_parent_id);
			$groupbuy_area_par_id = $district_par_row['groupbuy_area_parent_id'];

		}
		else
		{
			$groupbuy_area_par_id = 0;
		}

		$cache_key = $this->_cacheKeyPrefix . 'groupbuy_area_parent_id|' . $groupbuy_area_par_id;
		$Cache->remove($cache_key);


		$cache_key = $this->_cacheKeyPrefix . 'groupbuy_area_parent_id|' . $groupbuy_area_parent_id;
		$Cache->remove($cache_key);


		return $add_flag;
	}

	/**
	 * 根据分类父类id赌气子类信息,
	 *
	 * @param  int $groupbuy_area_parent_id 父id
	 * @param  bool $recursive 是否子类信息
	 * @param  int $level 当前层级
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getDistrictTreeData($groupbuy_area_parent_id = 0, $recursive = true, $level = 0)
	{
		$district_data = array();

		$level++;

		if (is_array($groupbuy_area_parent_id))
		{
			$cond_row = array('groupbuy_area_parent_id:in' => $groupbuy_area_parent_id);

			$cache_key = $this->_cacheKeyPrefix . 'groupbuy_area_parent_id|' . implode(':', $groupbuy_area_parent_id);
		}
		else
		{
			$cond_row = array('groupbuy_area_parent_id' => $groupbuy_area_parent_id);

			$cache_key = $this->_cacheKeyPrefix . 'groupbuy_area_parent_id|' . $groupbuy_area_parent_id;
		}

		//设置cache
		$Cache = Yf_Cache::create('groupbuy');

		if ($district_rows = $Cache->get($cache_key))
		{
		}
		else
		{
			$district_rows = $this->getByWhere($cond_row, array('groupbuy_area_sort' => 'ASC'));

			//类似数据可以放到前端整理
			foreach ($district_rows as $key => $district_row)
			{
				$district_row['parent_id'] = $district_row['groupbuy_area_parent_id'];
				$district_row['name']      = $district_row['groupbuy_area_name'];

				//for treegrid
				$district_row['level']          = $level;
				$district_row['district_level'] = $level;

				$district_row['district_icon'] = 'ui-icon-star';


				$district_row['expanded'] = false;
				$district_row['loaded']   = false;

				{
					//判断是否有子节点
					$rs = $this->getDistrictChildId($district_row['groupbuy_area_id'], false);

					if ($rs)
					{
						$district_row['is_leaf'] = false;
					}
					else
					{
						$district_row['is_leaf'] = true;
					}

					$district_rows[$key] = $district_row;
				}
			}

			$Cache->save($district_rows, $cache_key);
		}

		return $district_rows;
	}

	/**
	 * 读取子类id
	 *
	 * @param  int $groupbuy_area_parent_id 主键值
	 * @param  bools $recursive 是否递归查询
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getDistrictChildId($groupbuy_area_parent_id = 0, $recursive = true)
	{
		$district_data = array();

		if (is_array($groupbuy_area_parent_id))
		{
			$cond_row = array('groupbuy_area_parent_id:in' => $groupbuy_area_parent_id);
		}
		else
		{
			$cond_row = array('groupbuy_area_parent_id' => $groupbuy_area_parent_id);
		}

		$groupbuy_area_id_row = $this->getKeyByMultiCond($cond_row);

		if ($recursive && $groupbuy_area_id_row)
		{
			$rs = call_user_func_array(array(
										   $this,
										   'getDistrictChildId'
									   ), array(
										   $groupbuy_area_id_row,
										   $recursive
									   ));

			$groupbuy_area_id_row = array_merge($groupbuy_area_id_row, $rs);
		}

		return $groupbuy_area_id_row;
	}

	/*
	 * 编辑团购地区
	 * */
	public function editGroupBuyArea($groupbuy_area_id, $field_row)
	{
		$update_flag = $this->edit($groupbuy_area_id, $field_row);

		$Cache = Yf_Cache::create('groupbuy');
		$Cache->remove($this->treeAllKey);

		$district_row            = $this->getOne($groupbuy_area_id);
		$groupbuy_area_parent_id = $district_row['groupbuy_area_parent_id'];
		$cache_key               = $this->_cacheKeyPrefix . 'groupbuy_area_parent_id|' . $groupbuy_area_parent_id;
		$Cache->remove($cache_key);

		return $update_flag;
	}

	/*删除团购地区*/
	/**
	 * @param $combo_id
	 * @return bool
	 */
	public function removeGroupArea($groupbuy_area_id)
	{
		//判断是否有子类, 如果有,不允许删除
		$data_rows = $this->getDistrictTreeData($groupbuy_area_id, false);

		if ($data_rows)
		{
			$this->msg->setMessages(__('城市下存在区域,不允许删除'));
			return false;
		}

		$groupbuy_area_row       = $this->getOne($groupbuy_area_id);
		$groupbuy_area_parent_id = $groupbuy_area_row['groupbuy_area_parent_id'];

		$del_flag = $this->remove($groupbuy_area_id);


		$Cache = Yf_Cache::create('groupbuy');
		$Cache->remove($this->treeAllKey);

		$cache_key = $this->_cacheKeyPrefix . 'groupbuy_area_parent_id|' . $groupbuy_area_parent_id;
		$Cache->remove($cache_key);

		return $del_flag;
	}


}
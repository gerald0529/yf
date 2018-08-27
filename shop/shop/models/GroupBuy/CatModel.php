<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/20
 * Time: 15:44
 */
class GroupBuy_CatModel extends GroupBuy_Cat
{
	const PHYSICALCAT = 1;//实物团购分类
	const VIRTUAL     = 2;  //虚拟团购分类
	//团购类型 1-实物，2-虚拟商品 groupbuy_cat_type

	public static $cat_type_map = array(
		self::PHYSICALCAT => '实物',
		self::VIRTUAL => '虚拟商品'
	);

	/*获取团购分类*/
	public function getGroupBuyCatList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$rows = $this->listByWhere($cond_row, $order_row, $page, $rows);

		foreach ($rows['items'] as $key => $value)
		{
			$rows['items'][$key]['groupbuy_cat_type_label'] = __(self::$cat_type_map[$value['groupbuy_cat_type']]);
			if ($value['groupbuy_cat_parent_id'])
			{
				$rows[$key]['is_leaf'] = false;
			}
			else
			{
				$rows[$key]['is_leaf'] = true;
			}
		}

		return $rows;
	}


	/*获取团购分类列表*/
	public function getCatTree($groupbuy_cat_parent_id = 0, $recursive = true, $level = 0)
	{
		$data_rows = $this->getCatTreeData($groupbuy_cat_parent_id, $recursive, $level);

		$data['items'] = array_values($data_rows);

		return $data;
	}

	public function getGroupBuyCatByWhere($cond_row = array(), $order_row = array())
	{
		$rows = $this->getByWhere($cond_row, $order_row);

		foreach ($rows as $key => $value)
		{
			$rows[$key]['groupbuy_cat_type_label'] = __(self::$cat_type_map[$value['groupbuy_cat_type']]);
			if ($value['groupbuy_cat_parent_id'])
			{
				$rows[$key]['is_leaf'] = false;
			}
			else
			{
				$rows[$key]['is_leaf'] = true;
			}
		}
		return $rows;
	}

	public function getGroupBuyCatJson($cat_type)
	{
		$cond_row['groupbuy_cat_type'] = $cat_type;
		$order_row['groupbuy_cat_id']  = 'ASC';
		$rows                          = array();
		$cat                           = $this->getGroupBuyCatByWhere($cond_row, $order_row);
		if ($cat)
		{
			foreach ($cat as $key => $value)
			{
				$rows['name'][$value['groupbuy_cat_id']]              = $value['groupbuy_cat_name'];
				$rows['children'][$value['groupbuy_cat_parent_id']][] = $value['groupbuy_cat_id'];
				if ($value['groupbuy_cat_parent_id'])
				{
					$rows['parent'][$value['groupbuy_cat_id']] = $value['groupbuy_cat_parent_id'];
				}
				else
				{
					$rows['parent'][$value['groupbuy_cat_id']] = 0;
				}
			}
		}
		return $rows;
	}

	public function getCatName($cat_id = 0, $scat_id = 0)
	{
		$cat_row = array();
		if ($cat_id == 0)
		{
			$cat_row[1]['id']   = 0;
			$cat_row[1]['name'] = "所有团购";
		}
		else
		{
			$row = $this->getOne($cat_id);
			if ($row)
			{
				$cat_row[1]['id']   = $row['groupbuy_cat_id'];
				$cat_row[1]['name'] = $row['groupbuy_cat_name'];
			}
		}
		if ($scat_id != 0)
		{
			$row = $this->getOne($scat_id);
			if ($row)
			{
				$cat_row[2]['id']   = $row['groupbuy_cat_id'];
				$cat_row[2]['name'] = $row['groupbuy_cat_name'];
			}
		}

		return $cat_row;
	}


}
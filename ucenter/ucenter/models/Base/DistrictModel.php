<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Base_DistrictModel extends Base_District
{

	public $treeAllKey = null;

	/**
	 * 读取分页列表
	 *
	 * @param  int $district_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getDistrictList($cond_row = array(), $order_row = array('district_displayorder' => 'ASC'), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
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
	public function getDistrictTree($district_parent_id = 0, $recursive = true, $level = 0)
	{
		$data_rows = $this->getDistrictTreeData($district_parent_id, $recursive, $level);


		$data['items'] = array_values($data_rows);

		return $data;
	}

	//获取所有的地区
	public function  getAllDistrict()
	{
		$province = $this->getDistrictTreeData('0', false);

		$p_id = array_column($province, 'district_id');

		$city = $this->getDistrictTreeData($p_id, false);

		foreach ($city as $key => $val)
		{
			$province[$val['parent_id']]['city'][] = $val;
		}

		return $province;

	}

	//获取所有的地区
	public function  getDistrictAll()
	{
		$province = $this->getDistrictTreeData('0', false);
		$province = array_values($province);
		$p_id = array_column($province, 'district_id');

		$city = $this->getDistrictTreeData($p_id, false);
		fb($city);
		$c_id = array_column($city, 'district_id');

		$area = $this->getDistrictTree($c_id, false);
		fb($area);

		$data[] = $province;
		foreach ($city as $key => $val)
		{
			$data[$val['parent_id']][] = $val;
		}

		foreach ($area['items'] as $key => $val)
		{
			$data[$val['parent_id']][] = $val;
		}

		return $data;

	}

	public function getName($district_row = null)
	{
		if (is_array($district_row))
		{
			$district = $this->getByWhere(array('district_id:IN' => $district_row));
		}
		else
		{
			$district = $this->getByWhere(array('district_id:IN' => $district_row));
		}

		if ($district)
		{
			foreach ($district as $key => $val)
			{
				$district_name[] = $val['district_name'];
			}
		}
		else
		{
			return null;
		}


		return $district_name;
	}


	public function getCookieDistrict($district_id = null)
	{
		$res['provice'] = $this->getOne($district_id);

		$data['area'] = $res['provice']['district_name'];

		$data['provice']['id'] = $district_id;
		$data['provice']['name'] = $res['provice']['district_name'];

		if($res['provice'])
		{
			$res['city'] = $this->getOneByWhere(array('district_parent_id'=>$district_id));

			if($res['city'])
			{
				$data['area'] .= $res['city']['district_name'];

				$data['city']['id'] = $res['city']['district_id'];
				$data['city']['name'] = $res['city']['district_name'];

				$res['area'] = $this->getOneByWhere(array('district_parent_id'=>$res['city']['district_id']));
				if($res['area'])
				{
					$data['area'] .= $res['area']['district_name'];

					$data['address']['id'] = $res['area']['district_id'];
					$data['address']['name'] = $res['area']['district_name'];
				}
			}
		}

		return $data;
	}
}

?>
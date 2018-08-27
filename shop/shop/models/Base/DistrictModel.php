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
    public function getLowerAll($district_id=''){
        if(!isset($district_id)){
            return '';
        }
        $lower_info = $this->getByWhere(array('district_parent_id'=>$district_id),array('district_id' => 'ASC'));
        return $lower_info;
    }
	/**
     * 获取所有的地区
     * 这里只获取到省市，县级和县级以下数量太多，使用异步单独获取
     */
	public function  getDistrictAll()
	{
		$province = $this->getDistrictTreeData('0', false);
		$province = array_values($province);
		$p_id = array_column($province, 'district_id');

		$city = $this->getDistrictTreeData($p_id, false);


		$data[] = $province;
		foreach ($city as $val)
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
            $district_row = explode(',', $district_row);
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

    /**
     * 根据上级获取下级默认地址
     * @param type $district_name
     * @return type
     */
	public function getCookieDistrictName($district_name = null,$level = 4)
	{
		$res['provice'] = current($this->getByWhere(array('district_name'=>$district_name)));
        $data = array();
		if($res['provice'])
		{
			$data['area'] = $res['provice']['district_name'];

			$district_id = $res['provice']['district_id'];
			$data['provice']['id'] = $res['provice']['district_id'];
			$data['provice']['name'] = $res['provice']['district_name'];
            if($level <= 1){
                return $data;
            }
            $res['city'] = $this->getOneByWhere(array('district_parent_id'=>$district_id));
			if($res['city'])
			{
				$data['area'] .= $res['city']['district_name'];

				$data['city']['id'] = $res['city']['district_id'];
				$data['city']['name'] = $res['city']['district_name'];
                if($level <= 2){
                    return $data;
                }
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
    
    
    /**
     *  定位地区
     *  $level用于判断精确的等级
     */
    public function getDistrictByArea($district=array(),$level=4){
        if(isset($district['province']) && $district['province'] && $level >= 1){
            $district_name = $district['province'];
            if(!in_array($district['city'], array('北京','上海','天津','重庆')) ){
                $district_name_all = $district_name.' ';
            }
        }
        if(isset($district['city']) && $district['city'] && $level >= 2){
            $district_name = $district['city'];
            $district_name_all .= $district_name.' ';
        }
        if(isset($district['district']) && $district['district'] && $level >= 3){
            if(in_array($district['city'], array('北京','上海','天津','重庆')) ){
                $district_name = $district['district'];
                $district_name_all .= $district_name.' ';
            }
        }
        if(!in_array($district['city'], array('北京','上海','天津','重庆')) ){
            
            if(isset($district['street']) && $district['street'] && $level >= 4){
                $district_name = $district['street'];
                $district_name_all .= $district_name.' ';
            }
        }
        if(isset($district_name) && $district_name){
            $district_model = new Base_District();
            $district_array = $district_model->getByWhere(array('district_name'=>$district_name));
            $district_info = array_shift($district_array);
            $district_id = $district_info['district_id'];
            $data = array();
            $data['district_name'] = trim($district_name_all);
            $data['district_id'] = $district_id;
            return $data;
        }else{
            return array();
        }
    }
    
    /*
     * 根据名称获取地区信息
     * name格式：'陕西 西安市 临潼区 相桥镇'
     */
    public function getDistrictDetailByName($name){
        if(!trim($name)){
            return array();
        }
        $name_array = explode(' ', $name);
        $district_info = array();
        $count = count($name_array);
        $district_id = 0;
        for($i = 0; $i < $count; $i ++){
            $where = array('district_name' => trim($name_array[$i]),'district_parent_id'=>$district_id);
            $district_result = $this->getOneByWhere($where);
            $district_id = $district_result['district_id'];
            $district_info[$i] = $district_result;
            
        }
        return $district_info;
    }
    
    /**
     * 根据ID获取地区全名 ，return 格式 ：陕西 西安市 临潼区 相桥镇
     * @param type $district_id
     * @return string
     */
    public function getAllName($district_id = null) {
        if(!$district_id){
            return '';
        }
        $district_name = '';
        $district_result = $this->getOne($district_id);
        $district_name = $district_result['district_name'].' '.$district_name;
        if($district_result['district_parent_id'] > 0){
            $district_name = $this->getAllName( $district_result['district_parent_id']).' '.$district_name;
        }
        return $district_name;
    }
}

?>
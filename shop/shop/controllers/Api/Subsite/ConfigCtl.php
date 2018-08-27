<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Subsite_ConfigCtl extends Yf_AppController
{
	public $Subsite_BaseModel = null;

	/**
	 * Constructor
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);

		$this->Subsite_BaseModel = new Subsite_BaseModel();
	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function index()
	{
		include $this->view->getView();
	}

	// 根据分站id获取分站信息
	public function getOneSubsite()
	{
		$subsite_id = request_int('subsite_id');
		$subsite = $this->Subsite_BaseModel->getSubsite($subsite_id);
		$subsite = reset($subsite);

		$this->data->addBody(-140, $subsite);

	}

	// 根据地区id 依次向上查询其父级id
	public function getMyFatherDistrictTreeId($district_id)
	{
		$row = array();
		$baseDistrictModel  = new Base_DistrictModel();
		$row = $baseDistrictModel->getDistrictParent($district_id,true);
		$row = array_column($row,'district_id');
		return $row;
	}

	// 根据地区id 向下查询全部子级id
	public function getMyChildDistrictTreeId($district_id)
	{	
		$row = array();
		$baseDistrictModel  = new Base_DistrictModel();
		$row = $baseDistrictModel->getDistrictChildId($district_id,true);
		return $row;
	}

	// 根据分站所选择的地区id  查询相应的地区等级数据(依次的父级)
	public function getSubsiteDistrictTree(){
		$district_id = request_string('district_id');

		$district_id_arr = explode(',',$district_id);
		$data = array();
		foreach($district_id_arr as $key=>$val){
            $subsite_district_name = $this->getDistrictAllName($val);
			$data['subsite_district_name'][$key] = ltrim($subsite_district_name);
			$data['subsite_district_ids'][$key] = $district_id_arr[$key];
		}
		
		$this->data->addBody(-140, $data);
	}

	// 获取已经添加过的全部地区id
	public function getAllLimitDistrict()
	{
		$limited_district_str = '';
		$limited_district_arr = explode(',',$limited_district_str);
		return $limited_district_arr;
	}

	// 获取已经添加过的全部地区id
	public function getLimitDistrictAll()
	{
		$data['limit_ids'] = $this->getAllLimitDistrict();
		$this->data->addBody(-140, $data);
		include $this->view->getView();
	}

	// 上级分站
	public function getSubsiteName()
	{
		$subsite_id = request_int('id');

		$data = $this->Subsite_BaseModel->getOne($subsite_id);

		$this->data->addBody(-140, $data);
	}

	/**
	 * 列表数据
	 *
	 * @access public
	 */
	public function getSubsiteList()
	{
        //暂时不做分站的分级
        $data = $this->Subsite_BaseModel->getSubsiteList(array(), array('subsite_id' => 'ASC'),1, 1000);
        
		$this->data->addBody(-140, $data);
	}
    
    /**
     *  含有默认值得分站列表
     *  下拉框使用
     */
	public function getSubsiteListDefault()
	{
        $result = $this->Subsite_BaseModel->getSubsiteList(array(), array('subsite_id' => 'ASC'),1, 1000);
        $data = array();
        if(is_array($result['items']) && $result['records'] > 0){
            array_unshift($result['items'], array('subsite_id'=>0,'sub_site_name'=>'全部'));
            $data['items'] = $result['items'];
        }else{
            $data = array('items'=>array('0'=>array('subsite_id'=>0,'sub_site_name'=>'全部')));
        }

		$this->data->addBody(-140, $data);
	}
    
	/**
	 * 修改分站启用状态 关闭分站时清空该分站地区id
	 *
	 * @access public
	 */
	public function setSubsiteState(){
		$subsite_id = request_string('subsite_id');
		$edit_data['sub_site_is_open'] = request_int('enable');

		if ($subsite_id){	
			if($edit_data['sub_site_is_open'] == Sub_SiteModel::SUB_SITE_IS_OPEN){
                //判断是否有分站地区
                $subsite_info = $this->Subsite_BaseModel->getSubsite($subsite_id);
                if(!$subsite_info[$subsite_id]['sub_site_district_ids'] || !$subsite_info[$subsite_id]['district_child_ids']){
                    $flag = false;
                    $msg = '请先设置分站地区';
                }else{
                    $flag = $this->Subsite_BaseModel->editSubsite($subsite_id, $edit_data);	
                }
            }else{
                $flag = $this->Subsite_BaseModel->editSubsite($subsite_id, $edit_data);	
            }
        }else{
            $msg = '数据有误';
        }

		$data = array();
        if ($flag !== false){
            $msg    = 'sucess';
            $status = 200;
        }else{
            $msg    = !$msg ? '修改失败' : $msg;
            $status = 250;
        }
		$this->data->addBody(-140, $data, $msg, $status);
	}

	// 判断地区数据有效性
	public function districtJudge($disid_arr, $sub_site_id=0){
        $flag = true;
        $tip = '';
        if(!$disid_arr || $sub_site_id <= 1){
            return array('flag'=>$flag,'tip'=>$tip);
        }
		$baseDistrictModel  = new Base_DistrictModel();
        $Sub_SiteModel = new Sub_SiteModel();
        $sub_site_district_list = $Sub_SiteModel->getSubSiteList(array(), array('subsite_id' => 'ASC'), 1, 10000);
        if($sub_site_district_list['records'] > 1 && isset($sub_site_district_list['items'])){
            $limit_ids_arr =  array();
            foreach ($sub_site_district_list['items'] as $value){
                if($value['subsite_id'] != 1 && $value['subsite_id'] != $sub_site_id){
                    $ids = array();
                    $ids = explode(',', $value['sub_site_district_ids']);
                    $limit_ids_arr = array_merge($limit_ids_arr, $ids);
                }
            }
            if(count($limit_ids_arr) > 0){
                foreach($disid_arr as $key=>$val){
                    // 查询指定地区id的父级id 子级id
                    $my_father_district = $this->getMyFatherDistrictTreeId($val);
                    $my_child_district = $this->getMyChildDistrictTreeId($val);	
                    $district_name_array = reset($baseDistrictModel->getDistrict($val));
                    $my_district_name = $district_name_array['district_name'];
                    // 判断自己的数据是否存在包含关系
                    if(array_intersect($my_father_district, $disid_arr)||array_intersect($my_child_district, $disid_arr)){
                        $flag = false;
                        $tip = '您选择的地区中,存在上下级关系,请重新选择';
                        break;
                    }
                    // 判断自己的地区id中是否存在首受限制的地区id
                    if(in_array($val,$limit_ids_arr)){
                        $flag = false;
                        $tip = '您选择的'.$my_district_name.'受到限制,请选择其他地区';
                        break;
                    }
                    // 判断自己的地区id的上下级地区是否受限
                    if(array_intersect($my_father_district,$limit_ids_arr)||array_intersect($my_child_district,$limit_ids_arr)){
                        $flag = false;
                        $tip = $my_district_name.'地区与受限制地区存在上下级关系,请重新选择';
                        break;
                    }
                }
            }
        }
        return array('flag'=>$flag,'tip'=>$tip);
    }
    
    
	// 添加分站
	public function addSubsite(){	
		$data = array();
		$data['sub_site_logo'] 				= request_string('sub_site_logo');
		$data['sub_site_name']      		= request_string('sub_site_name');
		$data['sub_site_parent_id'] 		= request_int('parent_subsite');
		$data['sub_site_domain'] 			= str_replace(array('http://',' '),'',request_string('sub_site_domain'));
		$data['sub_site_template'] 			= request_string('sub_site_template');
		$data['sub_site_des'] 				= request_string('sub_site_des');
		$data['sub_site_copyright']	 		= request_string('sub_site_copyright');
		$data['sub_site_web_title'] 		= request_string('sub_site_web_title');
		$data['sub_site_web_keyword'] 		= request_string('sub_site_web_keyword');
		$data['sub_site_web_des'] 			= request_string('sub_site_web_des');
        $data['sub_site_district_ids']      = '';
        $data['district_child_ids']         = '';
        $data['sub_site_is_open']           = 0;  //添加时默认关闭
        $check_data = $data;
        $check_data['subsite_id'] = 0;
		$check = $this->checkRequest($check_data);
		if($check['status'] != 200){
			$msg 	= $check['msg'];
			$status = 250;
		}else{
            $subsite_id = $this->Subsite_BaseModel->addSubsite($data, true);
            
            if(!$subsite_id){
                $msg 	= __('添加失败');
                $status = 250;
            }else{
                $msg 	= __('添加成功');
                $status = 200;
                $data['id'] = $subsite_id;
                $data['subsite_id'] = $subsite_id;
            }
        }
        $return_data['items'] = $data;
		$this->data->addBody(-140, $return_data, $msg, $status);
	}

	// 修改分站
	public function editSubsite()
	{	
		$data_re = array();

		$id 									= request_int('subsite_id');
		$edit_data['sub_site_name']   			= request_string('sub_site_name');
		$edit_data['sub_site_parent_id'] 		= request_string('parent_subsite');
		$edit_data['sub_site_domain'] 			= str_replace(array('http://',' '),'',request_string('sub_site_domain'));
		$edit_data['sub_site_template'] 		= request_string('sub_site_template');
		$edit_data['sub_site_des'] 				= request_string('sub_site_des');
		$edit_data['sub_site_copyright']	 	= request_string('sub_site_copyright');
		$edit_data['sub_site_web_title'] 		= request_string('sub_site_web_title');
		$edit_data['sub_site_web_keyword'] 		= request_string('sub_site_web_keyword');
		$edit_data['sub_site_web_des'] 			= request_string('sub_site_web_des');
		$edit_data['sub_site_logo'] 			= request_string('sub_site_logo');
        $check_data = $edit_data;
        $check_data['subsite_id'] = $id;
        $check = $this->checkRequest($check_data);
		if($check['status'] != 200){
			$msg 	= $check['msg'];
			$status = 250;
		}else{
			// 执行修改操作
            $flag = $this->Subsite_BaseModel->editSubsite($id, $edit_data);

            if ($flag !== false){
                $msg    = __('success');
                $status = 200;
            }else{
                $msg    = __('failure');
                $status = 250;
            }
		}
        $edit_data['id'] = $id;
        $edit_data['subsite_id'] = $id;
        $data_re['items'] = $edit_data;
		$this->data->addBody(-140, $data_re, $msg, $status);
	}
    
    /**
     * 检查数据是否正确
     * @param type $data
     * @return int
     */
    public function checkRequest($data){
        $row = array('status'=>200);
        if(!$data['sub_site_name']){
            $row['msg'] = __('请填写分站名称');
            $row['status'] = 250;
            return $row;
        }
        if(isset($data['sub_site_domain']) && $data['sub_site_domain']){
            $preg = '/^[\w-]{1,20}$/';
            if(!preg_match($preg, $data['sub_site_domain'])){
                $row['msg'] = __('域名前缀格式有误');
                $row['status'] = 250;
                return $row;
            }
            //检查域名前缀
            $check_domain = $this->Subsite_BaseModel->getAllDomain($data['sub_site_domain'],$data['subsite_id'],'subsite');
            if($check_domain){
                $row['msg'] = __('域名前缀已被使用');
                $row['status'] = 250;
                return $row;
            }
        }
        return $row;
    }

    

    // 在分站中删除一个地区 同时将其他数据存入数据库
	public function deleteOneDistrict()
	{	
		$subsite_id = request_int('subsite_id');
		$delete_id = request_int('delete_id'); 

		$subsite_info = $this->Subsite_BaseModel->getSubsite($subsite_id);
		$delete_from = $subsite_info[$subsite_id]['sub_site_district_ids'];
		
		$edit_data['sub_site_district_ids'] = implode(',',array_diff(explode(',',$delete_from),array($delete_id)));

		$flag = $this->Subsite_BaseModel->editSubsite($subsite_id, $edit_data);
		if ($flag !== false)
		{
			$msg    = __('success');
			$status = 200;

		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		$data['items'] = $edit_data;
		$this->data->addBody(-140,$data,$msg,$status);
	}

    /**
     *  获取站点下的城市
     */
    public function getSubsiteData(){
        $subsite_id = request_int('sub_site_id');
        if($subsite_id > 1){
            $subsite_info = $this->Subsite_BaseModel->getSubsite($subsite_id);
        }else{
            $subsite_info = array();
        }
        
        
        if ($subsite_info !== false && is_array($subsite_info))
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
        $this->data->addBody(-140,$subsite_info,$msg,$status);
        
    }
    
    /**
     *  添加分站地区
     */
    public function addSubsiteDistrict(){
        $subsite_id = request_int('subsite_id');
        $district_ids = request_string('sub_site_district_ids');
        $data_re = array();
        // 去除重复数据
        $district_ids_array = explode(',',$district_ids);
        $num1 = count($district_ids_array); 
        $disid_arr = array_unique($district_ids_array);
        $num2 = count($disid_arr); 
        if(!trim($district_ids,',')){
            //如果没有传地区，就关闭分站并清除地区
            $result  = $this->Subsite_BaseModel->editSubsite($subsite_id, array('sub_site_district_ids'=>'','district_child_ids'=>'','sub_site_is_open'=>0));
            if($result !== false){
                $msg    = __('success');
                $status = 200;
                $subsite_info = $this->Subsite_BaseModel->getSubsite($subsite_id);
                $data_re['items'] = $subsite_info[$subsite_id];
            }else{
                $msg    = __('地区更新失败');
                $status = 250;
            }
            return $this->data->addBody(-140, $data_re, $msg, $status);
            
        }else if($num1 !== $num2){
            $error = array('flag'=>false, 'tip'=>'分站地区选择有误');
        }else{
            //获取最底层地区数据
            $Base_District = new Base_District();
            $district_child_id = $Base_District->getSubsiteDistrictId($disid_arr);
            // 判断地区数据有效性
            $error = $this->districtCheck($subsite_id,$district_child_id);
        }

        if($error['flag']){
            // 重新拼接地区数据
            $data['sub_site_district_ids']		= implode(',',$disid_arr);
            $data['district_child_ids']		= implode(',',$district_child_id);
            
            $result  = $this->Subsite_BaseModel->editSubsite($subsite_id, $data);

            if ($result){
                $msg    = __('success');
                $status = 200;
                
                $subsite_info = $this->Subsite_BaseModel->getSubsite($subsite_id);
                $data_re['items'] = $subsite_info[$subsite_id];
            }else{
                $msg    = __('地区更新失败');
                $status = 250;
            }

        }else{
            // 地区数据无效时
            $msg    = $error['tip'];
            $status = 250;
        }
        return $this->data->addBody(-140, $data_re, $msg, $status);
    }

    
    // 增加分站 页面
	public function subsiteDistrict(){
		// 获取一级地区
		$baseDistrictModel  = new Base_DistrictModel();
		$district_parent_id = 0;
		$district           = $baseDistrictModel->getDistrictTree($district_parent_id);

		// 获取全部受限制的地区
		$data['limit_ids'] = $this->getAllLimitDistrict();

		foreach($district['items'] as $key=>$val){
			if(in_array($val['district_id'],$data['limit_ids'])){
				unset($district['items'][$key]);
			}
		}

		$data['district']   = $district;
		$this->data->addBody(-140, $data);
		include $this->view->getView();

	}
    
    /**
     *  检查地区是否冲突
     * @param type $district_ids
     * @return type
     */
    public function districtCheck($sub_site_id,$district_ids=array()){
        if(!$sub_site_id){
            return array('flag'=>false,'tip'=>'地区检验失败');
        }
        $Sub_SiteModel = new Sub_SiteModel();
        $sub_site_list = $Sub_SiteModel->getSubSiteList(array(), array(), 1, 1000);
        foreach ($sub_site_list['items'] as $value){
            if($value['district_child_ids'] && $value['subsite_id'] != $sub_site_id){
                $child_id = explode(',', $value['district_child_ids']);
                $district_intersect = array();
                $district_intersect = array_intersect($child_id, $district_ids);
                if($district_intersect){
                    //获取冲突的地址
                    $district_intersect_id = reset($district_intersect);
                    $district_name = $this->getDistrictAllName($district_intersect_id);
                    return array('flag'=>false,'tip'=>'您选择的地区'.$district_name.'受到限制,请选择其他地区');
                }
            }
        }
        return array('flag'=>true);
        
    }
    
    /**
     *  获取地区各级的名称
     * @param type $district_id
     * @return string
     */
    public function getDistrictAllName($district_id){
        if(!$district_id){
            return '';
        }
        $Base_District = new Base_District();
        $district_info1 = $Base_District->getDistrict($district_id);
        if($district_info1[$district_id]['district_parent_id'] == 0){
             return $district_info1[$district_id]['district_name'];
        }else{
            $district_id2 = $district_info1[$district_id]['district_id'];
            $district_p_id2 = $district_info1[$district_id]['district_parent_id'];
            $district_info2 = $Base_District->getDistrict($district_p_id2);
            if($district_info2[$district_p_id2]['district_parent_id'] == 0){
                return  $district_info2[$district_p_id2]['district_name'].' '.$district_info1[$district_id]['district_name'];
            }else{
                $district_id3 = $district_info2[$district_p_id2]['district_id'];
                $district_p_id3 = $district_info2[$district_p_id2]['district_parent_id'];
                $district_info3 = $Base_District->getDistrict($district_p_id3);
                if($district_info3[$district_p_id3]['district_parent_id'] == 0){
                    return  $district_info3[$district_p_id3]['district_name'].' '.$district_info2[$district_p_id2]['district_name'].' '.$district_info1[$district_id]['district_name'];
                }else{
                    $district_id4 = $district_info3[$district_p_id3]['district_id'];
                    $district_p_id4 = $district_info3[$district_p_id3]['district_parent_id'];
                    $district_info4 = $Base_District->getDistrict($district_p_id4);
                    return  $district_info4[$district_p_id4]['district_name'].' '.$district_info3[$district_p_id3]['district_name'].' '.$district_info2[$district_p_id2]['district_name'].' '.$district_info1[$district_id]['district_name'];
                }
            }
        }
    }
    

}

?>
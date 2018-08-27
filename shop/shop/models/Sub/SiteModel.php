<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Yf <service@yuanfeng.cn>
 */
class Sub_SiteModel extends Sub_Site
{
    const SUB_SITE_IS_OPEN = 1;//开启分站

	/**
	 * 读取分页列表
	 *
	 * @param  int $district_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getSubSiteList($cond_row = array(), $order_row = array('district_displayorder' => 'ASC'), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}
    
    /**
     * 
     * @param type $sub_site_id
     * @return type
     *   
     *  获取分站子id
     */
    public function getDistrictChildId($sub_site_id){
        //分站筛选
            $subsite_info = $this->getSubsite($sub_site_id);
            //获取地区信息
            if(isset($subsite_info[$sub_site_id]['district_child_ids']) && $subsite_info[$sub_site_id]['district_child_ids']){
                $sub_site_district_ids = explode(',', $subsite_info[$sub_site_id]['district_child_ids']);
                return $sub_site_district_ids;
            }else{
                return false;
            }
    }

    /**
     *  获取分站信息
     * @param type $ip
     * @return boolean
     */
    function getSubsiteByArea($district=array()){
        if(isset($district['province']) && $district['province']){
            $district_name = $district['province'];
        }
        if(isset($district['city']) && $district['city']){
            $district_name = $district['city'];
        }
        if(isset($district['district']) && $district['district']){
            $district_name = $district['district'];
        }
        if(!in_array($district['city'], array('北京','上海','天津','重庆'))){
            
            if(isset($district['street']) && $district['street']){
                $district_name = $district['street'];
            }
        }
       
        if(isset($district_name) && $district_name){
            $district_model = new Base_District();
            $district_array = $district_model->getByWhere(array('district_name'=>$district_name));
            $district_info = array_shift($district_array);
            $district_id = $district_info['district_id'];
            $district_id_array = $district_model->getSubsiteDistrictId(array($district_id));

            //查询分站
            $sub_site_model = new Sub_SiteModel();
            $cond_row = array('sub_site_is_open'=>Sub_SiteModel::SUB_SITE_IS_OPEN);
            $sub_site_array = $sub_site_model->getSubSiteList($cond_row,array(),1,500);
              
            if(isset($sub_site_array['items']) && $sub_site_array['items']){
                foreach ($sub_site_array['items'] as $key => $value){
                    $district_intersect = array();
                    $subsite_district_ids = array();
                    if($value['district_child_ids']){
                        $subsite_district_ids = explode(',', $value['district_child_ids']);
                    }
                    $district_intersect = array_intersect($subsite_district_ids,$district_id_array);
                    if($district_intersect){
                        return $sub_site_array['items'][$key];
                    }
                }
            }
            
        }
        return false;
    }

    /**
     * ip获得的地址转换，（为了保证和数据库地区一致，请核对后使用）
     * 
     * @param type $ip
     * @return string
     */
    public function areaConvert($area_array){
        if(!is_array($area_array) || !isset($area_array['province'])){
            return $area_array;
        }
        if(isset($area_array['city'])){
            switch($area_array['city']){
                case '迪庆':case '甘南':case '海北':case '黄南':case '果洛':case '玉树':case '甘孜':
                    $area_array['city'] .= '藏族自治州';
                    break;
                case '怒江':
                    $area_array['city'] .= '傈僳族自治州';
                    break;
                case '大理':
                    $area_array['city'] .= '白族自治州';
                    break;
                case '楚雄': case '凉山':
                    $area_array['city'] .= '彝族自治州';
                    break;
                case '红河':
                    $area_array['city'] .= '哈尼族彝族自治州';
                    break;
                case '德宏':
                    $area_array['city'] .= '傣族景颇族自治州';
                    break;
                case '文山':
                    $area_array['city'] .= '壮族苗族自治州';
                    break;
                case '西双版纳':
                    $area_array['city'] .= '傣族自治州';
                    break;
                case '大兴安岭':case '铜仁':case '毕节':case '海东':case '阿勒泰':case '塔城':case '吐鲁番': case '哈密':case '阿克苏':case '喀什':case '和田':case '阿里':case '那曲':case '日喀则': case '山南':case '林芝':case '昌都':
                    $area_array['city'] .= '地区';
                    break;
                case '湘西': case '恩施':
                    $area_array['city'] .= '土家族苗族自治州';
                    break;
                case '神农架':
                    $area_array['city'] .= '林区';
                    break;
                case '湘西': case '恩施':
                    $area_array['city'] .= '土家族苗族自治州';
                    break;
                case '临夏': case '昌吉':
                    $area_array['city'] .= '回族自治州';
                    break;
                case '延边':
                    $area_array['city'] .= '朝鲜族自治州';
                    break;
                case '黔东':
                    $area_array['city'] .= '南苗族侗族自治州';
                    break;
                case '黔南': case '黔西南':
                    $area_array['city'] .= '布依族苗族自治州';
                    break;
                case '海西':
                    $area_array['city'] .= '蒙古族藏族自治州';
                    break;
                case '阿坝':
                    $area_array['city'] .= '藏族羌族自治州';
                    break;
                case '临高': case '澄迈': case '屯昌': case '定安':
                    $area_array['city'] .= '县';
                    break;
                case '昌江': case '白沙': case '乐东': case '陵水':
                    $area_array['city'] .= '黎族自治县';
                    break;
                case '博尔塔拉': case '巴音郭楞': 
                    $area_array['city'] .= '蒙古自治州';
                    break;
                case '伊犁':
                    $area_array['city'] .= '哈萨克自治州';
                    break;
                case '克孜勒苏':
                    $area_array['city'] .= '柯尔克孜自治州';
                    break;
                case '兴安': case '锡林郭勒': case '阿拉善': 
                    $area_array['city'] .= '盟';
                    break;
                case '台湾': case '香港': case '澳门':  case '北京': case '天津': case '上海': case '重庆':
                    $area_array['city'] .= '';
                    break;
                default :
                    $area_array['city'] .= '市';
                    break;
            }
        }else{
            return $area_array; 
        }
        return $area_array;
    }
    
    /**
     * 根据ip获取地址
     * @param type $queryIP
     * @return type
     */
    public function getIPLoc_sina_new($queryIP){
        $ip_array = explode(',', $queryIP);
        $url = 'http://ip.taobao.com/service/getIpInfo.php?ip='.$ip_array[0];
        $ch = curl_init($url);
        //curl_setopt($ch,CURLOPT_ENCODING ,'utf8');
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
        $location = curl_exec($ch);
        $location = json_decode($location,true);
        curl_close($ch);
        $location['data']['province'] = $location['data']['region'];
        return $location['data'];
    }
    
    /**
     * 获取定位地址
     * @param type $address
     * $address 格式：province:河北省,city:石家庄市
     * $level 用于判断地区精确的等级
     */
    public function getLbsGeo($address,$level=4){

        if($address){
            $addr_array = explode(',', $address);
            foreach ($addr_array as $value){
                $temp_array = explode(':', $value);
                if($temp_array[0] === 'province'){
                    $provice = $temp_array[1];
                }
                if($temp_array[0] === 'city'){
                    $city = $temp_array[1];
                }
                if($temp_array[0] === 'district'){
                    $district = $temp_array[1];
                }
                if($temp_array[0] === 'street'){
                    $street = $temp_array[1];
                }
            }
        }
        
        $area = array();
        if($provice){
           $area['province'] = $provice;
        }
        if($city){
           $area['city'] = $city;
        }
        if($district){
           $area['district'] = $district;
        }
        if($street){
           $area['street'] = $street;
        }

        $district_model = new Base_DistrictModel();
        if(!$area){
           $ip = get_ip();
            $area_array = $this->getIPLoc_sina_new($ip);
            if(empty($area_array['province'])){
                //设置默认的省
                $area_array['province'] = '江西';
            }
            if(empty($area_array['city'])){
                //设置默认的市
                $city = $district_model ->getDistrictByArea($area_array,1);
                if(!empty($city['district_id'])){
                    $city_info = $district_model ->getLowerAll($city['district_id']);
                    $area_array['city'] =  rtrim(current($city_info)['district_name'],'市');
                }

            }
            if(in_array($area_array['province'], array('北京','上海','天津','重庆'))){
                $default_dsstrict = array('北京'=>'朝阳区','上海'=>'黄浦区','天津'=>'河东区','重庆'=>'沙坪坝区');
                $area_array['city'] = rtrim($area_array['province'],'市');
                if(!isset($area_array['district']) || !$area_array['district']){
                    $area_array['district'] = $default_dsstrict[$area_array['province']];
                }
            }
            $area = $this->areaConvert($area_array);
        }else{
            if(in_array($area['province'], array('北京市','上海市','天津市','重庆市'))){
                $default_dsstrict = array('北京市'=>'朝阳区','上海市'=>'黄浦区','天津市'=>'河东区','重庆市'=>'沙坪坝区');
                $area['city'] = rtrim($area['province'],'市');
                if(!isset($area['district']) || !$area['district']){
                    $area['district'] = $default_dsstrict[$area['province']];
                }
            }
        }

        $district = $district_model->getDistrictByArea($area,$level);
        return $district;
    }
    
    /**
     *  根据前缀获取域名信息
     * @param type $user_address_id
     * @param type $sort_key_row
     */
    public function getSubSiteByPrefix($prefix) {
        if($prefix && Web_ConfigModel::value('subsite_is_open') == 1){
            $result = $this->getOneByWhere(array('sub_site_domain'=>$prefix));
            return $result;
        }else{
            return array();
        }
    }
}

?>
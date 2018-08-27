<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Base_DistrictCtl extends Controller
{
	public $baseDistrictModel = null;
    public $subSiteModel = null;
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

		//include $this->view->getView();
		$this->baseDistrictModel = new Base_DistrictModel();
        $this->subSiteModel = new Sub_SiteModel();
	}

	/**
	 *
	 *
	 * @access public
	 */
	public function district()
	{
		$district_parent_id = request_int('pid', request_int('area_id'));
		$data               = $this->baseDistrictModel->getDistrictTree($district_parent_id);
		fb($data);
		$this->data->addBody(-140, $data);
	}

	public function getAllDistrict()
	{
		$data = $this->baseDistrictModel->getDistrictAll();

		
		$this->data->addBody(-140, $data);
	}

    /**
     * 根据名称获取默认地址
     * 
     */
	public function getDistrictNameList()
	{
		$district_name = request_string('name');

		$data = $this->baseDistrictModel->getCookieDistrictName($district_name,2);

		$this->data->addBody(-140, $data);
	}

	public function getDistrictName()
	{
		$district_id = request_int('id');

		$data = $this->baseDistrictModel->getOne($district_id);

		$this->data->addBody(-140, $data);
	}
	
	public function getDistrictInfo()
	{
		$area = request_string('area');
		$cond_rows['district_name:LIKE'] = '%'.$area . '%';
		$data = $this->baseDistrictModel->getOneByWhere($cond_rows);

		$this->data->addBody(-140, $data);
	}
        
        
        
    public function subSite()
    {
        $sub_site_parent_id = request_int('pid', request_int('sub_site_id'));
        $cond_row['sub_site_parent_id'] =  $sub_site_parent_id;
        $cond_row['sub_site_is_open'] =  Sub_SiteModel::SUB_SITE_IS_OPEN;
        $data_rows = $this->subSiteModel->getByWhere($cond_row);

        $data['items'] = array_values($data_rows);
        $this->data->addBody(-140, $data);
    }
    
    /**
     *  定位分站
     */
    public function getLocalSubsite(){
        if(!isset($_COOKIE['isset_local_subsite'])){
            $ip = get_ip();
            $area_array = $this->subSiteModel->getIPLoc_sina_new($ip);
            $district = $this->subSiteModel->areaConvert($area_array);
          
            $sub_site_info = $this->subSiteModel->getSubsiteByArea($district);
            if(isset($sub_site_info['subsite_id'])){
                $data = $sub_site_info;
                $status = 200;
                $smg = 'success';
            }else{
                $data = array();
                $status = 250;
                $smg = 'failure';
            }
            $this->data->addBody(-140, $data, $smg, $status);
        }else{
           $this->data->addBody(-140, array(), 'failure', 250);
        }
        
    }
    
    /**
     *  定位分站 wap 
     */
    public function getLocalSubsiteWap(){
        $provice = request_string('province');
        $city = request_string('city');
        $district = request_string('district');
        $street = request_string('street');
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
        if(!$area){
            $ip = get_ip();
            $area_array = $this->subSiteModel->getIPLoc_sina_new($ip);
            $area = $this->subSiteModel->areaConvert($area_array);
        }else{
            if(in_array($area['province'], array('北京市','上海市','天津市','重庆市'))){
                $area['city'] = trim($area['province'],'市');
            }
        }
        $sub_site_info = $this->subSiteModel->getSubsiteByArea($area);
        if(isset($sub_site_info['subsite_id'])){
            $data = array('subsite_id'=>$sub_site_info['subsite_id']);
            $status = 200;
            $smg = 'success';
        }else{
            $data = array();
            $status = 250;
            $smg = 'failure';
        }
        $this->data->addBody(-140, $data, $smg, $status);
    }
    
    /**
     *  wap分站域名访问时调用该方法
     *  获取分站信息
     */
    public function getSubsiteHost(){
        $host = request_string('host');
        $master_url_array = parse_url(Yf_Registry::get('shop_wap_url'));
        $master_host = $master_url_array['host'];
        if($host && $host != $master_host){
            //查询分站信息
            $host_prefix = strstr($host,'.',1);
            $sub_array = $this->subSiteModel->getSubSiteByPrefix($host_prefix);
            if($sub_array){
                $data = array(
                    'sub_site_id'=>$sub_array['subsite_id']
                );
            }else{
                $data = array(
                    'sub_site_id'=>0
                );
            }
        }else{
            //返回主站
            $data = array(
                'sub_site_id'=>0
            );
        }
        $this->data->addBody(-140, $data, $sub_array, 200);
    }
    

}

?>
<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author     Banchangle
 */
class Seller_Shop_SetshopCtl extends Seller_Controller {
    
    public $shopBaseModel     = null;
    public $shopClassModel    = null;
    public $shopGradeModel    = null;
    public $shopTemplateModel = null;
    
    /**
     * Constructor
     *
     * @param  string $ctl 控制器目录
     * @param  string $met 控制器方法
     * @param  string $typ 返回数据类型
     *
     * @access public
     */
    public function __construct(&$ctl, $met, $typ)
    {
        
        parent::__construct($ctl, $met, $typ);
        $this->shopBaseModel     = new Shop_BaseModel();
        $this->shopClassModel    = new Shop_ClassModel();
        $this->shopGradeModel    = new Shop_GradeModel();
        $this->shopTemplateModel = new Shop_TemplateModel();
    }
    
    /**
     * 首页
     *
     * @access public
     */
    public function index()
    {
        
        //判断是否开启店铺二级域名
        $Web_ConfigModel = new Web_ConfigModel();
        $shop_domain     = $Web_ConfigModel->getByWhere(array('config_type' => 'domain'));
        
        
        //查询出当前店铺信息
        $shop_id['shop_id'] = Perm::$shopId;
        $re                 = $this->shopBaseModel->getBaseOneList($shop_id);
        
        //查看单个店铺二级域名的编辑次数
        $Shop_DomainModel = new Shop_DomainModel();
        $domain_list      = $Shop_DomainModel->getOne($shop_id['shop_id']);
        
        if ('json' == $this->typ)
        {
            $status              = 200;
            $msg                 = __('success');
            $data['shop_domain'] = $shop_domain;
            $data['re']          = $re;
            $this->data->addBody(-140, $data, $msg, $status);
            
        }
        else
        {
            include $this->view->getView();
        }
    }
    
    /**
     * 修改店铺信息
     *
     * @access public
     */
    public function editShop()
    {
        $edit_shop_row = request_row("shop");
        
        //判断是否开启店铺二级域名
        $Web_ConfigModel = new Web_ConfigModel();
        $shop_domain     = $Web_ConfigModel->getByWhere(array('config_type' => 'domain'));
        
        //是否可以修改并且修改次数没达到上线
        if (empty($shop_domain['is_modify']['config_value']) || empty($edit_shop_row['shop_domain']))
        {
            unset($edit_shop_row['shop_domain']);
        }
        
        $shop_id          = request_int('shop_id');
        $Shop_DomainModel = new Shop_DomainModel();
        $domain_list      = $Shop_DomainModel->getOne($shop_id);
        
        if ($domain_list)
        {
            $can_times = intval($domain_list['shop_edit_domain']) - 1;
            
            if ($can_times < 0)
            {
                unset($edit_shop_row['shop_domain']);
            }
        }
    
        $flag             = $this->shopBaseModel->editBase($shop_id, $edit_shop_row);




        if ($can_times >= 0 && !empty($edit_shop_row['shop_domain']) && !empty($shop_domain['is_modify']['config_value']))
        {
            $field_row['shop_sub_domain']  = $edit_shop_row['shop_domain'];
            $field_row['shop_edit_domain'] = $domain_list['shop_edit_domain'] - 1;
            $flag                         = $Shop_DomainModel->editDomain($shop_id, $field_row);
        }
        else
        {
            $msg    = __('域名修改次数已达上限');
        }
        
        if ($flag === false)
        {
            $status = 250;
            $msg    = __('failure');
        }
        else
        {
            $status = 200;
            $msg    = __('success');
        }
        
        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }
    
    
    /**
     * 设置幻灯
     *
     * @access public
     */
    public function slide()
    {
        
        $array          = array(
            "0" => 0,
            "1" => 1,
            "2" => 2,
            "3" => 3,
            "4" => 4
        );
        $shop_id        = Perm::$shopId;
        $re             = $this->shopBaseModel->getOne($shop_id);
        $de['slide']    = $re['shop_slide'] ? explode(',', $re['shop_slide']) : "";
        $de['slideurl'] = $re['shop_slide'] ? explode(',', $re['shop_slideurl']) : "";
        if ('json' == $this->typ)
        {
            $status           = 200;
            $msg              = __('success');
            $data['slide']    = $de['slide'];
            $data['re']       = $re;
            $data['slideurl'] = $de['slideurl'];
            $data['array']    = $array;
            $this->data->addBody(-140, $data, $msg, $status);
            
        }
        else
        {
            include $this->view->getView();
        }
    }
    
    
    /**
     * 修改幻灯
     *
     * @access public
     */
    public function editSlide()
    {
        
        $data['shop_slide']    = implode(",", request_row("slide"));
        $data['shop_slideurl'] = implode(",", request_row("slideurl"));
        $shop_id               = Perm::$shopId;
        $flag                  = $this->shopBaseModel->editBase($shop_id, $data);
        if ($flag === false)
        {
            $status = 250;
            $msg    = __('failure');
        }
        else
        {
            $status = 200;
            $msg    = __('success');
        }
        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
        
    }
    
    
    /**
     * 设置主题
     *
     * @access public
     */
    public function theme()
    {
        
        //店铺当前的模板
        $shop_id      = Perm::$shopId;
        $re           = $this->shopBaseModel->getOne($shop_id);
        $default_temp = $this->shopTemplateModel->getOne($re['shop_template']);
        
        if ($re['shop_self_support'] == "true")
        {
            //自营店铺绑定全部模板
            $shopTemplateModel = new Shop_TemplateModel();
            $grade_temp        = $shopTemplateModel->getByWhere();
        }
        else
        {
            //查询出当前店铺等级，根据等级查看能用多少模板
            $grade_temp = $this->shopGradeModel->getGradetemplist($re['shop_grade_id']);
        }
        
        if ('json' == $this->typ)
        {
            $status               = 200;
            $msg                  = __('success');
            $data['default_temp'] = $default_temp;
            $data['grade_temp']   = $grade_temp;
            $this->data->addBody(-140, $data, $msg, $status);
            
        }
        else
        {
            include $this->view->getView();
        }
    }
    
    /**
     * 修改店铺主题
     *
     * @access public
     */
    public function setShopTemp()
    {
        
        $shop_temp['shop_template'] = request_string("shop_temp_name");
        $shop_id                    = Perm::$shopId;
        
        $flag = $this->shopBaseModel->editBase($shop_id, $shop_temp);
        if ($flag === false)
        {
            $status = 250;
            $msg    = __('failure');
        }
        else
        {
            $status = 200;
            $msg    = __('success');
        }
        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
        
    }

    public function service()
    {
        //查找店铺默认的售后服务内容
        $shop_id                    = Perm::$shopId;

        $data = $this->shopBaseModel->getOne($shop_id);

        if ('json' == $this->typ)
        {
            $status               = 200;
            $msg                  = __('success');
            $this->data->addBody(-140, $data, $msg, $status);

        }
        else
        {
            include $this->view->getView();
        }
    }

    public function editShopCommonService()
    {
        $shop_id                    = Perm::$shopId;
        $common_service = request_string('common_service');

        //判断是否存在违禁词
        $matche_row = array();
        //有违禁词
        if (Text_Filter::checkBanned($common_service, $matche_row))
        {
            $data   = array();
            $msg    = __('含有违禁词');
            $status = 250;
            $this->data->addBody(-140, array(), $msg, $status);
            return false;
        }

        $shop_edit_row = array();
        $shop_edit_row['shop_common_service'] = $common_service;

        $flag = $this->shopBaseModel->editBase($shop_id, $shop_edit_row);
        if ($flag === false)
        {
            $status = 250;
            $msg    = __('failure');
        }
        else
        {
            $status = 200;
            $msg    = __('success');
        }
        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);

    }
}

?>
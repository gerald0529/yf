<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author
 */
class Api_Shop_InfoCtl extends Api_Controller
{
//
    public $shopInfoModel = null;
    public $goodsBaseModel = null;
    public $goodsCatModel = null;
    public $goodsCommonModel = null;
    public $orderBaseModel = null;
    public $orderGoodsModel = null;
    public $orderReturnModel = null;
    public $userInfoModel = null;
    public $shopClassModel = null;
    public $shopGradeModel = null;
    public $shopClassBindModel = null;
    public $shopCompanyModel   = null;
    public $messageModel       = null;


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
        $this->shopInfoModel = new Shop_BaseModel();
        $this->goodsBaseModel = new Goods_BaseModel();
        $this->goodsCatModel = new Goods_CatModel();
        $this->goodsCommonModel = new Goods_CommonModel();
        $this->orderBaseModel = new Order_BaseModel();
        $this->orderGoodsModel = new Order_GoodsModel();
        $this->orderReturnModel = new Order_ReturnModel();
        $this->userInfoModel = new User_InfoModel();
        $this->shopClassModel     = new Shop_ClassModel();
        $this->shopGradeModel     = new Shop_GradeModel();
        $this->shopClassBindModel = new Shop_ClassBindModel();
        $this->shopCompanyModel   = new Shop_CompanyModel();
        $this->messageModel       = new MessageModel();

    }
    /**
     * 通过店家用户id获取店铺商品
     * @param int $user_id 用户id
     * @access public
     */
    public function getShopGoodsByUserId()
    {
        $user_id = request_int('user_id');//店员或店家用户id
        $data = [];
        $shop_info = $this->shopInfoModel->getOneByWhere(['user_id'=>$user_id]);
        //如果没有店铺信息，说明是店员登录，返回
        if($shop_info)
        {
            $status = 200;
            $cond_rows['shop_id'] = $shop_info['shop_id'];
            $data = $this->goodsBaseModel->getByWhere($cond_rows);
            if($data)
            {
                $data = array_values($data);
                foreach($data as $key=>$value)
                {
                    $goods_cat = $this->goodsCatModel->getOneByWhere(['cat_id'=>$value['cat_id']]);
                    $goods_common = $this->goodsCommonModel->getOneByWhere(['common_id'=>$value['common_id']]);
                    $data[$key]['cat_name'] = $goods_cat['cat_name'];
                    $data[$key]['common_cubage'] = $goods_common['common_cubage'];
                    foreach($value['goods_spec'] as $k=>$v)
                    {
                        $data[$key]['goods_spec'] = implode(',', $v);
                    }
                }
                $msg = 'success';
            }
            else
            {
                $msg = 'success:店铺还没有商品';
            }
        }
        else
        {
            $msg = 'failure:您不是店家';
            $status = 250;
        }

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /*
     *通过店家id获取店铺所有订单信息
     * @param int $user_id 用户id
     * @access public
     */
    public function getShopOrderByUserId(){
        $user_id = request_int('user_id');//店员或店家用户id
        $data = [];
        $shop_info = $this->shopInfoModel->getOneByWhere(['user_id'=>$user_id]);
        //如果没有店铺信息，说明是店员登录，返回
        if($shop_info)
        {
            $status = 200;
            $cond_rows['shop_id'] = $shop_info['shop_id'];
            $data = $this->orderBaseModel->getByWhere($cond_rows);
            if($data)
            {
                $data = array_values($data);
                $msg = 'success';
            }
            else
            {
                $msg = 'success:店铺还没有订单';
            }
        }
        else
        {
            $msg = 'failure:您不是店家';
            $status = 250;
        }

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /*
     *通过店家id获取店铺所有订单商品
     * @param int $user_id 用户id
     * @access public
     */
    public function getShopOrdergoodsByUserId(){
        $user_id = request_int('user_id');//店员或店家用户id
        $data = [];
        $shop_info = $this->shopInfoModel->getOneByWhere(['user_id'=>$user_id]);
        //如果没有店铺信息，说明是店员登录，返回
        if($shop_info)
        {
            $status = 200;
            $cond_rows['shop_id'] = $shop_info['shop_id'];
            $data = $this->orderGoodsModel->getByWhere($cond_rows);
            if($data)
            {
                $data = array_values($data);
                foreach($data as $key=>$value)
                {
                    $data[$key]['goods_spec'] = implode(',',$value['order_spec_info']);
                    $data[$key]['shop_name'] = $shop_info['shop_name'];
                }
                $msg = 'success';
            }
            else
            {
                $msg = 'success:店铺还没有订单商品';
            }
        }
        else
        {
            $msg = 'failure:您不是店家';
            $status = 250;
        }

        $this->data->addBody(-140, $data, $msg, $status);
    }
    
    /*
     *通过店家id获取店铺退货列表信息
     * @param int $user_id 用户id
     * @access public
     */
    public function getShopOrderreturnByUserId(){
        $user_id = request_int('user_id');//店员或店家用户id
        $data = [];
        //如果没有店铺信息，说明是店员登录，返回
        if($user_id)
        {
            $status = 200;
            $cond_rows['seller_user_id'] = $user_id;
            $data = $this->orderReturnModel->getByWhere($cond_rows);
            if($data)
            {
                $data = array_values($data);
                foreach($data as $key=>$value)
                {
                    $shop_info = $this->shopInfoModel->getOneByWhere(['user_id'=>$value['seller_user_id']]);
                    $data[$key]['shop_id'] = $shop_info['shop_id'];
                }
                $msg = 'success';
            }
            else
            {
                $msg = 'success:店铺还没有退货订单';
            }
        }
        else
        {
            $msg = 'failure:您不是店家';
            $status = 250;
        }

        $this->data->addBody(-140, $data, $msg, $status);
    }


    /*
     *通过店家id获取店铺所有订单信息的买家信息
     * @param int $user_id 用户id
     * @access public
     */
    public function getShopOrderBuyerInfoByUserId(){
        $user_id = request_int('user_id');//店员或店家用户id
        $data = [];
        $shop_info = $this->shopInfoModel->getOneByWhere(['user_id'=>$user_id]);
        //如果没有店铺信息，说明是店员登录，返回
        if($shop_info)
        {
            $status = 200;
            $cond_rows['shop_id'] = $shop_info['shop_id'];
            $order_info_rows = $this->orderBaseModel->getByWhere($cond_rows);
            if($order_info_rows)
            {
                $order_info_rows = array_values($order_info_rows);
                $user_id_rows = array_column($order_info_rows, 'buyer_user_id');
                $data = $this->userInfoModel->getByWhere(['user_id:IN'=>$user_id_rows]);
                
                $msg = 'success';
            }
            else
            {
                $msg = 'success:店铺还没有订单';
            }
        }
        else
        {
            $msg = 'failure:您不是店家';
            $status = 250;
        }

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /*
     *通过用户id获取店铺信息
     * @param int $user_id 用户id
     * @access public
     */
    public function getShopInfoByUserId()
    {
        $user_id = request_int('user_id');
        $Shop_BaseModel = new Shop_BaseModel();
        $Shop_GradeModel = new Shop_GradeModel();
        $shop_info = $Shop_BaseModel->getOneByWhere(['user_id'=>$user_id]);
        $data = [];
        if($shop_info)
        {
            $shop_grade = $Shop_GradeModel->getOneByWhere(['shop_grade_id'=>$shop_info['shop_grade_id']]);
            $data['shop_name'] = $shop_info['shop_name'];
            $data['shop_logo'] = $shop_info['shop_logo'];
            $data['shop_qq'] = $shop_info['shop_qq'];
            $data['shop_ww'] = $shop_info['shop_ww'];
            $data['shop_grade'] = $shop_grade['shop_grade_name'];
            $msg = 'success';
            $status = 200;
        }
        else
        {
            $msg = 'failure';
            $status = 250;
        }
        $this->data->addBody(-140, $data, $shop_info, $status);
    }

    /*
     *通过用户id修改店铺信息
     * @param int $user_id 用户id
     * @access public
     */
    public function editShopInfoByUserId()
    {
        $user_id = request_string('user_id');
        $cond['shop_qq'] = request_string('shop_qq');
        $cond['shop_ww'] = request_string('shop_ww');
        $cond['shop_logo'] = request_string('shop_logo');
        $Shop_BaseModel = new Shop_BaseModel();
        $shop_info = $Shop_BaseModel->getOneByWhere(['user_id'=>$user_id]);
        if($shop_info)
        {
            $flag = $Shop_BaseModel->editBase($shop_info['shop_id'], $cond);
            if($flag)
            {
                $msg = 'success';
                $status = 200;
            }
            else
            {
                $msg = 'failure';
                $status = 250;
            }
        }
        else
        {
            $msg = 'failure';
            $status = 250;
        }
        $data = [$user_id];
        $this->data->addBody(-140, $data, $msg, $status);
    }


    /*
     *通过用户id获取店铺公司信息
     * @param int $user_id 用户id
     * @access public
     */
    public function getUserShopCompany()
    {
        $user_id = request_int('user_id');
        $Seller_BaseModel = new Seller_BaseModel();
        $seller_rows      = $Seller_BaseModel->getByWhere(array('user_id' => $user_id));

        $data            = array();
        if ($seller_rows)
        {
            $data['shop_id_row'] = array_column($seller_rows, 'shop_id');
            $data['shop_id']     = current($data['shop_id_row']);
            $cond_row = array("shop_id" =>$data['shop_id']);
        }
        else
        {
            $data['shop_id'] = 0;
            $cond_row = array("user_id" => $user_id);
        }

        $shop_info = $this->shopInfoModel->getBaseOneList($cond_row);

        $shop_company = $this->shopInfoModel->getbaseCompanyList($shop_info['shop_id']);

        $data['seller_rows'] = $seller_rows;
        $data['user_id'] = $user_id;
        $data['shop_info'] = $shop_info;
        $data['shop_company'] = $shop_company;

        $this->data->addBody(-140, $data);
    }

    /*
     *增加或减少库存
     * @param int $goods_id 商品id
     * @param int $goods_num 商品数量
     * @param string $op 类型，增加或减少
     * @access public
     */
    public function reduceOrAddStock()
    {
        $goods_id = request_int('goods_id');
        $goods_num = request_int('goods_num');
        $op = request_string('op');
        $Goods_BaseModel = new Goods_BaseModel();
        $Goods_CommonModel = new Goods_CommonModel();
        $base_info = $Goods_BaseModel->getOneByWhere(['goods_id'=>$goods_id]);
        $common_info = $Goods_CommonModel->getOneByWhere(['common_id'=>$base_info['common_id']]);
        //开启事务
        $Goods_BaseModel->sql->startTransactionDb();
        if($op == 'add')
        {
            $type = '增加';
            $flag = $Goods_BaseModel->editBase($goods_id, ['goods_stock'=>$base_info['$goods_info']+$goods_num]);
            $flag1 = $Goods_CommonModel->editCommon($common_info['common_id'], ['common_stock'=>$common_info['common_stock']+$goods_num]);
        }
        else
        {
            $type = '减少';
            $flag = $Goods_BaseModel->editBase($goods_id, ['goods_stock'=>$base_info['$goods_info']-$goods_num]);
            $flag1 = $Goods_CommonModel->editCommon($common_info['common_id'], ['common_stock'=>$common_info['common_stock']-$goods_num]);
        }
        if($flag && $flag1 && $Goods_BaseModel->sql->commitDb())
        {
            $msg = 'success:库存'.$type.'成功';
            $status = 200;
        }
        else
        {
            //修改失败，回滚
            $Goods_BaseModel->sql->rollBackDb();
            $msg = 'failure:库存'.$type.'失败';
            $status = 250;
        }
        $data = [];
        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 通过手机号获取用户id
     * @param string $phone 用户注册手机号
     * @access public
     */
    public function getUserIdByPhone()
    {
        $phone = request_string('user_mobile');
        $User_InfoModel = new User_InfoModel();
        $user_info_data = $User_InfoModel->getOneByWhere(['user_mobile'=>$phone]);
        $data = [];
        if($user_info_data)
        {
            $User_BaseModel = new User_BaseModel();
            $user_base_data = $User_BaseModel->getOneByWhere(['user_account'=>$user_info_data['user_name']]);
            $data['user_id'] = $user_base_data['user_id'];
            $msg = 'success';
            $status = 200;
        }
        else
        {
            $msg = 'failure:用户还没注册';
            $status = 250;
        }
        $this->data->addBody(-140, $data, $msg, $status);
    }

    
    //修改公司信息
    public function editShopCompany()
    {
        $Shop_BaseModel = new Shop_BaseModel();
        $Shop_CompanyModel = new Shop_CompanyModel();

        $shop_id                                                 = request_string('shop_id');

        //修改财务资质
        if(request_string('bank_account_name'))
        {
            $shop_company['bank_account_name']                       = request_string('bank_account_name');
            $shop_company['bank_account_number']                     = request_string('bank_account_number');
            $shop_company['bank_name']                               = request_string('bank_name');
            $shop_company['bank_code']                               = request_string('bank_code');
            $shop_company['bank_address']                            = request_string('bank_address');
            $shop_company['bank_licence_electronic']                 = request_string('bank_licence_electronic');
            $shop_company['general_taxpayer']                        = request_string('general_taxpayer');
        }

        //修改证件信息
        if(request_string('business_id'))
        {
            $shop_company['business_id']                  = request_string('business_id');
            $shop_company['business_licence_start']       = request_string('business_licence_start');
            $shop_company['business_licence_end']         = request_string('business_licence_end');

            if(!$shop_company['business_id'])
            {
                return $this->data->setError("请填写营业执照号码");
            }
            $shop_company['business_license_electronic']  = request_string('business_license_electronic');
            if(!$shop_company['business_license_electronic'])
            {
                return $this->data->setError("请上传营业执照电子版");
            }

            $shop_company['business_license_location']    = request_string('business_license_location');

            $shop_company['organization_code']            = request_string('organization_code');
            $shop_company['organization_code_electronic'] = request_string('organization_code_electronic');

            $shop_company['taxpayer_id']                             = request_string('taxpayer_id');
            $shop_company['tax_registration_certificate']            = request_string('tax_registration_certificate');
            $shop_company['tax_registration_certificate_electronic'] = request_string('tax_registration_certificate_electronic');
        }

        //开启事物
        $Shop_CompanyModel->sql->startTransactionDb();
        $flag = $Shop_CompanyModel->editCompany($shop_id, $shop_company);
        $flag1 = true;
        if(!$shop_company['business_id'])
        {
            $flag1 = $Shop_BaseModel->editBase($shop_id, array('shop_status'=>9));
        }

        $rs_rows = array();
        check_rs($flag, $rs_rows);
        check_rs($flag1, $rs_rows);
        if (is_ok($rs_rows) && $Shop_CompanyModel->sql->commitDb())
        {
            $status = 200;
            $msg    = __('success');
        }
        else
        {
            $Shop_CompanyModel->sql->rollBackDb();
            $status = 250;
            $msg    = __('failure');
        }

        $data = array();

        $this->data->addBody(-140, $data, $msg, $status);

    }

    /**
     *  添加店铺基本信息
     */
    public function addShopCompany()
    {
        $Shop_BaseModel = new Shop_BaseModel();
        $MessageModel = new MessageModel();
        $Shop_CompanyModel = new Shop_CompanyModel();

        $user_id   = request_int('user_id');
        $user_name   = request_string('user_name');
        $cond_row  = array("user_id" => $user_id);
        $shop_info = $Shop_BaseModel->getBaseOneList($cond_row);

        $apply  = request_string('apply');
        $shop_company_data = $this->getShopRequest($apply);
        if($shop_company_data['status'] == 250)
        {
            return $this->data->addBody(-140, array(), $shop_company_data['msg'], 250);
        }
        $shop_company = $shop_company_data['data'];
        $shop_company['shop_company_address'] = trim($shop_company['shop_company_address']);
        if(request_int('street_id'))
        {
            $district_id = request_int('street_id');
        }else{
            if(request_int('area_id'))
            {
                $district_id = request_int('area_id');
            }else
            {
                $district_id = request_int('city_id');
            }
        }

        //检查$district_id是否到最底层
        $Base_DistrictModel = new Base_DistrictModel();
        $check_district = $Base_DistrictModel->getDistrictList(array('district_parent_id'=>$district_id));

        //开启事物
        $MessageModel->sql->startTransactionDb();
        $shop_base['district_id'] = $district_id;
        $shop_base['shop_status']   = 8; //提交个人（企业）信息
        $shop_base['user_id']   = $user_id;
        $shop_base['user_name'] = $user_name;
        $shop_base['shop_business'] = $apply == 1 ? 0 : 1;
        $shop_base['shop_type'] = 1;
        if (!$shop_info){

            $flag = $Shop_CompanyModel->addCompany($shop_company, TRUE);
            $shop_base['shop_id']   = $flag;
            $flag1                  = $Shop_BaseModel->addBase($shop_base);
            if ($flag1 && $flag && $MessageModel->sql->commitDb())
            {
                $status = 200;
                $msg    = __('success');
            }
            else
            {
                $MessageModel->sql->rollBackDb();
                $status = 250;
                $msg    = __('failure1');
            }
        }else{
            $flag = $Shop_CompanyModel->editCompany($shop_info['shop_id'], $shop_company);
            $flag1 = $Shop_BaseModel->editBase($shop_info['shop_id'], $shop_base);
            $rs_rows = array();
            check_rs($flag, $rs_rows);
            check_rs($flag1, $rs_rows);
            if (is_ok($rs_rows) && $MessageModel->sql->commitDb()){
                $status = 200;
                $msg    = __('success');
            }else{
                $MessageModel->sql->rollBackDb();
                $status = 250;
                $msg    = __('failure2');
            }
        }

        $data = array();

        return $this->data->addBody(-140, $data, $msg, $status);

    }

    /**
     * 获取添加店铺时所需要的request数据
     * @param type $apply
     * @return array
     */
    public function getShopRequest($apply){
        //验证格式
        $shop_company = array();
        $data = array();
        $contacts_email = request_string('contacts_email');
        $contacts_phone = request_string('contacts_phone');
        $phone_verify  = request_string('phone_verify');
        $email_verify = request_string('email_verify');

        if(!preg_match('/^[1][0-9]{10}$/', $contacts_phone)){
            $data['msg'] = '手机号码有误';
            $data['status'] = 250;
            return $data;
        }

        if($contacts_email)
        {
            if(!preg_match("/^[0-9a-zA-Z]+@(([0-9a-zA-Z]+)[.])+[a-z]{2,4}$/",$contacts_email )){
                $data['msg'] = '邮箱有误';
                $data['status'] = 250;
                return $data;
            }
        }


        //非公共数据
        if($apply == 1)
        {
            $shop_company['shop_company_name']      = '';
            $shop_company['company_phone']          = '';
            $shop_company['company_employee_count'] = 0;
            $shop_company['company_registered_capital']   = 0;
            $shop_company['legal_identity_type'] = request_string('legal_identity_type'); //证件类型
            $shop_company['business_license_location']    = '';
            $shop_company['legal_person']                = request_string('contacts_name');
            $shop_company['legal_person_number'] = request_string('business_id');
            if(!$shop_company['legal_person_number']){
                $data['msg'] = '证件号码有误';
                $data['status'] = 250;
                return $data;
            }
            $shop_company['legal_person_electronic']  = request_string('user_identity_face_logo'); //
            $shop_company['legal_person_electronic2']  = request_string('user_identity_font_logo');
            if(!$shop_company['legal_person_electronic2'] || !$shop_company['legal_person_electronic']){
                $data['msg'] = '证件照上传失败';
                $data['status'] = 250;
                return $data;
            }

            $shop_company['business_licence_start']       = request_string('business_licence_start');
            $shop_company['business_licence_end']         = request_string('business_licence_end');
        }
        else if($apply == 2)
        {
            $shop_company['shop_company_name']      = request_string('shop_company_name');
            if(!$shop_company['shop_company_name']){
                $data['msg'] = '公司名称有误';
                $data['status'] = 250;
                return $data;
            }
            $shop_company['company_registered_capital']   = request_string('company_registered_capital');
            $shop_company['company_phone']          = request_string('company_phone');
            $shop_company['company_employee_count'] = request_string('company_employee_count');

        }else{
            $data['msg'] = '数据有误';
            $data['status'] = 250;
            return $data;
        }

        //公共数据
        $shop_company['contacts_phone']               = $contacts_phone;
        $shop_company['contacts_email']               = $contacts_email;
        $shop_company['contacts_name']                = request_string('contacts_name');

        $data['msg'] = '数据获取成功';
        $data['status'] = 200;
        $data['data'] = $shop_company;
        return $data;

    }

    public function getSellledInfo()
    {
        $user_id = request_int('user_id');
        $Seller_BaseModel = new Seller_BaseModel();

        $shop_class = $this->shopClassModel->getByWhere();
        $shop_grade = $this->shopGradeModel->getByWhere();

        $data            = array();
        $seller_rows      = $Seller_BaseModel->getByWhere(array('user_id' => $user_id));
        if ($seller_rows)
        {
            $data['shop_id_row'] = array_column($seller_rows, 'shop_id');
            $data['shop_id']     = current($data['shop_id_row']);
            $cond_row = array("shop_id" =>$data['shop_id']);
        }
        else
        {
            $cond_row = array("user_id" => $user_id);
        }


        $shop_info = $this->shopInfoModel->getBaseOneList($cond_row);
        $shop_company = $this->shopInfoModel->getbaseCompanyList($shop_info['shop_id']);

        $data            = array();
        $data['shop_class']   = empty($shop_class) ? array() : array_values($shop_class);
        $data['shop_grade']   = empty($shop_grade) ? array() : array_values($shop_grade);
        $data['shop_info']    = empty($shop_info) ? array() : $shop_info;
        $data['shop_company'] = empty($shop_company) ? array() : $shop_company;
        $this->data->addBody(-140, $data);
    }

    //修改店铺信息
    public function editShopBase()
    {

        $shop_id                       = request_int('shop_id');
        $shop_list['shop_name']        = request_string("shop_name");
        $shop_list['shop_grade_id']    = request_int('shop_grade_id');
        $shop_list['joinin_year']      = request_int('joinin_year');
        $shop_list['shop_class_id']    = request_int('shop_class_id');
        $product_class_id              = request_row('product_class_id');
        $commission_rate               = request_row('commission_rate');
        $shop_list['shop_status']      = 1;
        $shop_list['shop_create_time'] = get_date_time();
        $shop_list['shop_settlement_last_time'] = get_date_time();
        $shop_list['shop_end_time']    = date("Y-m-d H:i:s", strtotime(" $shop_list[shop_create_time] + $shop_list[joinin_year] year"));

        $flag = $this->shopInfoModel->editBase($shop_id, $shop_list);
        $product_class_id = array_unique($product_class_id);
        $shopClassBind = $this->shopClassBindModel->listByWhere(array('shop_id'=>$shop_id));
        if($shopClassBind['items']){
            $calss_id = array();
            foreach ($shopClassBind['items'] as $val){
                $calss_id[] = $val['shop_class_bind_id'];
            }
            $this->shopClassBindModel->removeClassBind($calss_id);
        }

        foreach ($product_class_id as $key => $value)
        {
            $shop_class['product_class_id']       = $value;
            $shop_class['commission_rate']        = $commission_rate[$key];
            $shop_class['shop_class_bind_enable'] = 2;
            $shop_class['shop_id']                = $shop_id;
            $flag1                                = $this->shopClassBindModel->addClassBind($shop_class);
        }

        $shop_company['shop_company_address']   = request_string('shop_company_address');
        $shop_company['company_address_detail'] = request_string('company_address_detail');
        $this->shopCompanyModel->editCompany($shop_id, $shop_company);

        if ($flag !== FALSE)
        {
            /**
             *  统计中心
             *  添加统计代码
             */
            $shop_info = $this->shopCompanyModel->getCompany($shop_id);
            $analytics_data = array(
                'shop_id'=>$shop_id,
                'area'=>$shop_info[$shop_id]['shop_company_address'],
                'shop_name'=>$shop_list['shop_name'],
                'date'=>$shop_list['shop_create_time']
            );

            Yf_Plugin_Manager::getInstance()->trigger('analyticsShopAdd',$analytics_data);
            /****************************************************/
            $status = 200;
            $msg    = __('success');
        }
        else
        {
            $status = 250;
            $msg    = __('failure');
        }

        $data = array();

        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function shopPaystatus()
    {
        $shop_id                              = request_int('shop_id');
        $shop_list['payment_voucher']         = request_string("payment_voucher");
        $shop_list['payment_voucher_explain'] = request_string('payment_voucher_explain');
        $shop_base['shop_payment']            = 1;
        $shop_base['shop_status']  = 2;
        $user_id = request_int('user_id');
        $user_name = request_string('user_name');

        //开启事物
        $SellerBaseModel = new Seller_BaseModel();
        $this->messageModel->sql->startTransactionDb();
        $flag  = $this->shopInfoModel->editBase($shop_id, $shop_base);
        $flag1 = $this->shopCompanyModel->editCompany($shop_id, $shop_list);
        $rs_rows = array();
        check_rs($flag, $rs_rows);
        check_rs($flag1, $rs_rows);
        //添加二级域名
        //平台设置二级域名
        $Web_ConfigModel = new Web_ConfigModel();
        $shop_domain     = $Web_ConfigModel->getByWhere(array('config_type' => 'domain'));
        $domain = array();
        $domain['shop_edit_domain'] = $shop_domain['domain_modify_frequency']['config_value'];
        $domain['shop_self_domain'] = $shop_domain['retain_domain']['config_value'];

        //初始化用户的二级域名
        $Shop_DomainModel = new Shop_DomainModel();
        $check_domain = $Shop_DomainModel->getDomain($shop_id);
        if($check_domain){
            $flag2 = $Shop_DomainModel->editDomain($shop_id,$domain);
        }else{
            $domain['shop_id'] = $shop_id;
            $flag2 = $Shop_DomainModel->addDomain($domain);
        }
        check_rs($flag2, $rs_rows);

        //添加卖家

        $check_seller = $SellerBaseModel->getByWhere(array('shop_id'=>$shop_id));
        if(!$check_seller){
            $seller_base['shop_id']         = $shop_id;
            $seller_base['user_id']         = $user_id;
            $seller_base['seller_name']     = $user_name;
            $seller_base['seller_is_admin'] = 1;
            $seller_add  = $SellerBaseModel->addBase($seller_base);
        }else{
            $seller_add = true;
        }

        if (is_ok($rs_rows) && $seller_add  && $this->messageModel->sql->commitDb())
        {
            $status = 200;
            $msg    = __('success');
        }
        else
        {
            $this->messageModel->sql->rollBackDb();
            $status = 250;
            $msg    = __('failure');
        }

        $data = array();

        $this->data->addBody(-140, $data, $msg, $status);
    }

    //入驻协议
    public function shopXiYi()
    {
        $ShopHelpModel = new Shop_HelpModel();
        $shop_xieyi = $ShopHelpModel->getByWhere(array('page_show'=>1), array('help_sort' => "asc"));
        $this->data->addBody(-140, $shop_xieyi);
    }

    /*
     *通过shopid获取店铺信息
     * @param int $shop_id 用户id
     * @access public
     */
    public function getShopInfoByShopId()
    {
        $shop_id = request_int('shop_id');
        $Shop_BaseModel = new Shop_BaseModel();
        $shop_info = $Shop_BaseModel->getOne($shop_id);

        $this->data->addBody(-140, $shop_info);
    }
}

?>
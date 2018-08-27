<?php if (!defined('ROOT_PATH')) exit('No Permission');

/**
 * @author     zhouchenggang
 */
class Seller_Shop_ChainCtl extends Seller_Controller
{
    public $chainBaseModel = null;
    public $chainUserModel = null;

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
        $this->chainUserModel = new Chain_UserModel();
        $this->chainBaseModel = new Chain_BaseModel();
        //include $this->view->getView();
    }

    /**
     * @author      houpeng
     * 删除指定的门店信息
     * @return array $rows 返回的查询内容
     * @access public
     */
    public function delChain()
    {
        $chain_id = request_int('chain_id');
        //echo $chain_id;exit;
        $del_base_flag = $this->chainBaseModel->removeBase($chain_id);
        if ($del_base_flag !== false) {
            $chain_user_data = $this->chainUserModel->getOneByWhere(array('chain_id' => $chain_id), array());
            $del_user_flag = $this->chainUserModel->removeUser($chain_user_data['chain_user_id']);
            if ($del_user_flag !== false) {
                $msg = __('success');
                $status = 200;
            } else {
                $msg = __('failure');
                $status = 250;
            }
            $data = array();
            $this->data->addBody(-140, $data, $msg, $status);
        }
    }

    /**
     * 删除全部门店信息
     * @access public
     */
    public function delAllChain()
    {
        $chain_id_row = request_row("id");

        $delAllFlag = $this->chainBaseModel->removeBase($chain_id_row);
        if ($delAllFlag !== false) {
            $status = 200;
            $msg = __('success');
        } else {
            $status = 250;
            $msg = __('failure');
        }
        $message = array();
        $this->data->addBody(-140, $message, $msg, $status);
    }


    /**
     * 首页
     *
     * @access public
     */
    public function chain()
    {
        $act = request_string('act');
        $district_parent_id = request_int('pid', 0);
        $Base_DistrictModel = new Base_DistrictModel();
        $district = $Base_DistrictModel->getDistrictTree($district_parent_id);

        if ($act == 'edit') {
            $chain_id = request_string('chain_id');
            $data = $this->chainBaseModel->getOne($chain_id);

            $cond_row['chain_id'] = $chain_id;
            $userdate = $this->chainUserModel->getOneByWhere($cond_row);
            $User_BaseModel = new User_BaseModel();
            $user_row = $User_BaseModel->getOne($userdate['user_id']);
            $data['chain_user'] = $user_row['user_account'];
            $chain_area[] = $data['chain_province'];
            $chain_area[] = $data['chain_city'];
            $chain_area[] = $data['chain_county'];
            $data['chain_area'] = implode(' ', $chain_area);
//            fb($date);exit;
            $this->view->setMet('setChain');
        } elseif ($act == 'add') {
            $this->view->setMet('setChain');
        } else {
            $Yf_Page = new Yf_Page();
            $Yf_Page->listRows = 10;
            $rows = $Yf_Page->listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $rows);
            $shop_id = Perm::$shopId;
            $chain_user_list = $this->chainUserModel->getByWhere(array('shop_id' => $shop_id), array());
            $chain_id_rows = array_column($chain_user_list, 'chain_id');
            $cond_row['chain_id:IN'] = $chain_id_rows;
            $data = $this->chainBaseModel->getBaseList($cond_row, array(), $page, $rows);
            $Yf_Page->totalRows = $data['totalsize'];
            $page_nav = $Yf_Page->prompt();
            //echo '<pre>';print_r($data);exit;
        }
        if ('json' == $this->typ) {
            $chain_id = request_int('chain_id');

            $data = $this->chainBaseModel->getOne($chain_id);
            $this->data->addBody(-140, $data);
        } else {
            //echo '<pre>';print_r($data);exit;
            include $this->view->getView();
        }
    }


    /**
     * 添加
     *
     * @access public
     */
    public function addChain()
    {
        $key = Yf_Registry::get('ucenter_api_key');;
        $url = Yf_Registry::get('ucenter_api_url');
        $app_id = Yf_Registry::get('ucenter_app_id');

        $formvars = array();
        $formvars['app_id'] = $app_id;
        $formvars['user_account'] = request_string('chain_user');
        $formvars['user_password'] = request_string('chain_pwd');

        

        $data['chain_name'] = request_string('chain_name'); // 门店名称
        //名称不超过15个字符

        if(mb_strlen($data['chain_name'], 'UTF-8') > 25){
            return $this->data->addBody(-140, $data, __('门店名称不能超过25个字符'), 250);
        }
        
        $data['chain_mobile'] = request_string('chain_phone'); // 手机号码
//        $data['chain_telephone']        = request_string('chain_telephone'); // 联系电话
//        $data['chain_contacter']        = request_string('chain_contacter'); // 联系人
        $chain_area = explode(' ', request_string('address_area'));
        $data['chain_province_id'] = request_string('province_id'); // 省id
        $data['chain_province'] = $chain_area[0]; // 省份
        $data['chain_city_id'] = request_string('city_id'); // 市id
        $data['chain_city'] = $chain_area[1]; // 市
        $data['chain_county_id'] = request_string('area_id'); // 县
        $data['chain_county'] = $chain_area[2]; // 县区
        $data['chain_address'] = request_string('chain_address'); // 详细地址
        $data['chain_opening_hours'] = request_string('chain_opening_hours'); // 营业时间
        $data['chain_traffic_line'] = request_string('chain_traffic_line'); // 交通路线
        $data['chain_img'] = request_string('chainimagePath'); // 门店图片
        $data['chain_time'] = date('Y-m-d H:i:s', time()); // 添加时间
        
        //开启事物
        $this->chainBaseModel->sql->startTransactionDb();
        
        $chain_id = $this->chainBaseModel->addBase($data, true);

        $url = sprintf('%s?ctl=%s&met=%s&typ=%s', $url, 'Login', 'registerChain', 'json');
        $init_rs = get_url_with_encrypt($key, $url, $formvars);
        if ($init_rs['status'] == 200) {
            if ($init_rs['data']['k']) {
                $User_BaseModel = new User_BaseModel();
                $userdata['user_id'] = $init_rs['data']['user_id']; // 用户id
                $userdata['user_account'] = $init_rs['data']['user_name']; // 用户帐号
                $userdata['user_delete'] = 0; // 用户状态
                $user_id = $User_BaseModel->addBase($userdata, true);
                $chainuser['user_id'] = $init_rs['data']['user_id'];
                $chainuser['chain_id'] = $chain_id;
                $chainuser['shop_id'] = perm::$shopId;
                $chain_user_id = $this->chainUserModel->addUser($chainuser, true);
                if (!$user_id) {
                    $msg = __('初始化用户出错66!');
                    return $this->data->setError($msg, array());
                } else {
                    //初始化用户信息
                    $user_info_row = array();
                    $user_info_row['user_id'] = $user_id;
                    $user_info_row['user_realname'] = '';
                    $user_info_row['user_name'] = $init_rs['data']['user_name'];
                    $user_info_row['user_mobile'] = request_string('chain_phone');
                    $user_info_row['user_logo'] = '';
                    $user_info_row['user_regtime'] = get_date_time();
                    $User_InfoModel = new User_InfoModel();
                    $info_flag = $User_InfoModel->addInfo($user_info_row);

                    $user_resource_row = array();
                    $user_resource_row['user_id'] = $user_id;
                    $user_resource_row['user_points'] = Web_ConfigModel::value("points_reg");//注册获取积分;

                    $User_ResourceModel = new User_ResourceModel();
                    $res_flag = $User_ResourceModel->addResource($user_resource_row);

                    $User_PrivacyModel = new User_PrivacyModel();
                    $user_privacy_row['user_id'] = $user_id;
                    $privacy_flag = $User_PrivacyModel->addPrivacy($user_privacy_row);
                    //积分
                    $Points_LogModel = new Points_LogModel();
                    $user_points_row['user_id'] = $user_id;
                    $user_points_row['user_name'] = $init_rs['data']['user_name'];
                    $user_points_row['class_id'] = Points_LogModel::ONREG;
                    $user_points_row['points_log_points'] = $user_resource_row['user_points'];
                    $user_points_row['points_log_time'] = get_date_time();
                    $user_points_row['points_log_desc'] = __('会员注册');
                    $user_points_row['points_log_flag'] = 'reg';
                    $Points_LogModel->addLog($user_points_row);
                    //发送站内信
                    $message = new MessageModel();
                    $message->sendMessage('welcome', $user_id, $data['user_account'], '', '', 0, MessageModel::OTHER_MESSAGE);
                }
            } else {
                $chain_user=$this->chainUserModel->getByWhere(array('user_id'=>$init_rs['data']['user_id']));
                if($chain_user){
                    $msg='用户名已经存在';
                }else{
                    $chainuser['user_id'] = $init_rs['data']['user_id'];
                    $chainuser['chain_id'] = $chain_id;
                    $chainuser['shop_id'] = perm::$shopId;
                    $chain_user_id = $this->chainUserModel->addUser($chainuser, true);
                }
            }
        }

        if ($chain_user_id && $this->chainBaseModel->sql->commitDb())
        {
            $msg    = __('success');
            $status = 200;
        }
        else
        {
            $this->chainBaseModel->sql->rollBackDb();
            if ($init_rs['status'] == 250) {
                $msg = $init_rs['msg'];
            }
            $status = 250;
        }

        $data['chain_id'] = $chain_id;

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 修改
     *
     * @access public
     */
    public function editChain()
    {
        $key = Yf_Registry::get('ucenter_api_key');;
        $url = Yf_Registry::get('ucenter_api_url');
        $app_id = Yf_Registry::get('ucenter_app_id');

        $formvars = array();
        $formvars['app_id'] = $app_id;
        $formvars['user_account'] = request_string('chain_user');
        $formvars['user_password'] = request_string('chain_pwd');
        $formvars['from'] = 'chain';

        $this->chainBaseModel->sql->startTransactionDb();

        if(request_string('chain_pwd')){
            $url = sprintf('%s?ctl=%s&met=%s&typ=%s', $url, 'Login', 'resetPasswd', 'json');
            $resetPasswd = get_url_with_encrypt($key, $url, $formvars);
        }else{
            $resetPasswd['status'] = 200;
        }


        $data_rs = array();
        if ($resetPasswd['status'] == 200)
        {
            $data['chain_name']   = request_string('chain_name'); // 门店名称
            $data['chain_mobile'] = request_string('chain_phone'); // 手机号码
            //        $data['chain_telephone']        = request_string('chain_telephone'); // 联系电话
            //        $data['chain_contacter']        = request_string('chain_contacter'); // 联系人
            $chain_area                  = explode(' ', request_string('address_area'));
            $data['chain_province_id']   = request_string('province_id'); // 省id
            $data['chain_province']      = $chain_area[0]; // 省份
            $data['chain_city_id']       = request_string('city_id'); // 市id
            $data['chain_city']          = $chain_area[1]; // 市
            $data['chain_county_id']     = request_string('area_id'); // 县
            $data['chain_county']        = $chain_area[2]; // 县区
            $data['chain_address']       = request_string('chain_address'); // 详细地址
            $data['chain_opening_hours'] = request_string('chain_opening_hours'); // 营业时间
            $data['chain_traffic_line']  = request_string('chain_traffic_line'); // 交通路线
            $data['chain_img']           = request_string('chainimagePath'); // 门店图片
            $chain_id                    = request_int('chain_id');
            $data_rs                     = $data;

            $flag = $this->chainBaseModel->editBase($chain_id, $data);
        }
        if(($flag === 0 || $flag) && $this->chainBaseModel->sql->commitDb()){
            $msg    = __('success');
            $status = 200;
        }else{
            $this->chainBaseModel->sql->rollBackDb();

            $msg = $resetPasswd['msg'];
            $status = 250;
        }
        $this->data->addBody(-140, $data_rs, $msg, $status);
    }
}

?>
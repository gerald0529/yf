<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    /**
     * @author     Yf <service@yuanfeng.cn>
     */
    class UserCtl extends Yf_AppController
    {
        public $userInfoModel = null;
        
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
            
            $this->userInfoModel = new User_InfoDetailModel();
            $this->userGradeModel = new User_GradeModel();
            //$this->userResourceModel  =  new User_ResourceModel();
            //$this->userAddressModel  =  new User_AddressModel();
            $this->userPrivacyModel = new User_PrivacyModel();
            $this->userBaseModel = new User_InfoModel();
            //$this->userTagModel  =  new User_TagModel();
            //$this->userTagRecModel  =  new User_TagRecModel();
            //$this->userFriendModel  =  new User_FriendModel();
            $this->messageTemplateModel = new Message_TemplateModel();
            
            $this->web = $this->webConfig();
            $this->web['web_logo'] = $this->web['site_logo'];
            
        }
        
        //默认设置
        public function webConfig()
        {
            $web['site_logo'] = Web_ConfigModel::value("site_logo");//首页logo
            $web['web_name'] = Web_ConfigModel::value("site_name");//首页名称
            $web['buyer_logo'] = Web_ConfigModel::value("setting_buyer_logo");//会员中心logo
            $web['seller_logo'] = Web_ConfigModel::value("setting_seller_logo");//卖家中心logo
            $web['goods_image'] = Web_ConfigModel::value("photo_goods_logo");//商品图片
            $web['shop_head_logo'] = Web_ConfigModel::value("photo_shop_head_logo");//店铺头像
            $web['shop_logo'] = Web_ConfigModel::value("photo_shop_logo");//店铺标志
            $web['user_avatar'] = Web_ConfigModel::value("photo_user_avatar");//默认头像
            
            return $web;
        }
        
        /**
         *获取会员信息
         *
         * @access public
         */
        public function getUserInfo()
        {
            $user_id = Perm::$userId;
            $user_name = Perm::$row['user_name'];
            if ($user_id && !$user_name) {
                $user_info_model = new User_Info();
                $user_info = $user_info_model->getOne($user_id);
                $user_name = $user_info['user_name'];
            }
            $data = $this->userInfoModel->getInfoDetail($user_name);
            $data = $data[$user_name];
            
            if (empty($data['user_mobile']) && empty($data['user_email'])) {
                header('location:' . Yf_Registry::get('url') . '?ctl=User&met=security&op=mobiles');
                die;
            }
            
            $privacy = $this->userPrivacyModel->getPrivacy($user_id);
            $privacy = @$privacy[$user_id];
            
            //本地读取远程信息
            $key = Yf_Registry::get('shop_api_key');
            $url = Yf_Registry::get('shop_api_url');
            $formvars['app_id'] = Yf_Registry::get('shop_app_id');
            $formvars['ctl'] = 'Api_User_Info';
            $formvars['met'] = 'getUserInfoByName';
            $formvars['user_name'] = $user_name;
            $formvars['typ'] = 'json';
            
            $result = get_url_with_encrypt($key, $url, $formvars);
            
            if ($result['status'] == 200) {
                $user_grade_id = current($result['data'])['user_grade'];
            }
            
            //会员用户
            if ($user_grade_id) {
                $User_GradeModel = new User_GradeModel();
                $this->user['grade'] = $User_GradeModel->getOne($user_grade_id);
                
            } else {
                $this->user['grade']['user_grade_name'] = '普通会员';
            }

//		if (empty($user['grade']))
//		{
//			$this->user['grade']['user_grade_name'] = '普通会员';
//		}
            
            //获取一级地址
            $district_parent_id = request_int('pid', 0);
            $baseDistrictModel = new Base_DistrictModel();
            $district = $baseDistrictModel->getDistrictTree($district_parent_id);
            
            //扩展字段
            $User_OptionModel = new User_OptionModel();
            $user_option_rows = $User_OptionModel->getByWhere(array('user_id' => $user_id));
            fb($user_option_rows);
            
            $Reg_OptionModel = new Reg_OptionModel();
            $reg_opt_rows = $Reg_OptionModel->getByWhere(array('reg_option_active' => 1));
            fb($reg_opt_rows);
            
            $option_rows = array();
            
            if ($user_option_rows) {
                foreach ($user_option_rows as $user_option_id => $user_option_row) {
                    $option_rows[$user_option_row['reg_option_id']] = $user_option_row;
                }
            }
            
            if ('json' == $this->typ) {
                $data['district'] = $district;
                $data['privacy'] = $privacy;
                
                /*
                //$grade_row = $this->userGradeModel->getOne($data['user_grade']);
                //$data['user_grade_name'] = $grade_row['user_grade_name'];
    
                $User_FavoritesGoodsModel = new User_FavoritesGoodsModel();
                $User_FavoritesShopModel = new User_FavoritesShopModel();
    
                $data['favorites_goods_num'] = $User_FavoritesGoodsModel->getFavoritesGoodsNum($user_id);
                $data['favorites_shop_num'] = $User_FavoritesShopModel->getFavoritesShopNum($user_id);
                //$data['resource'] = $this->userResourceModel->getOne($user_id);
                */
                $this->data->addBody(-140, $data);
            } else {
                $this->data->addBody(-140, $district);
                include $this->view->getView();
            }
        }
        
        /**
         * 修改会员信息
         *
         * @access public
         */
        public function editUserInfo()
        {
            
            if (!CSRF::check(request_string(CSRF::name()))) {
                throw new Exception(__('令牌错误'));
            }
            
            $option_value_row = request_row('option');
            
            $Reg_OptionModel = new Reg_OptionModel();
            $reg_opt_rows = $Reg_OptionModel->getByWhere(array('reg_option_active' => 1));
            
            foreach ($reg_opt_rows as $reg_option_id => $reg_opt_row) {
                if ($reg_opt_row['reg_option_required']) {
                    if ('' == $option_value_row[$reg_option_id]) {
                        $this->data->setError('请输入' . $reg_opt_row['reg_option_name']);
                        return false;
                    }
                }
                
                //判断类型
                if ($reg_opt_row['reg_option_datatype']) {
                
                }
            }
            
            $user_id = Perm::$userId;
            $user_name = Perm::$row['user_name'];
            
            $year = request_int('year');
            $month = request_int('month');
            $day = request_int('day');
            //判断当前时间
            $check_time = strtotime($year .'-'. $month .'-'.$day);
            $now_time = time();
            if ($check_time > $now_time)
            {
                $this->data->addBody(-140, array(), '生日应小于当前日期', 250);
                return false;
            }
            $user_qq = request_string('user_qq');
            $user_ww = request_string('user_ww');
            $rows = request_row('privacy');
            $nickname = request_string('nickname', $user_name);
            $edit_user_row['user_birth'] = $year . "-" . $month . "-" . $day;
            $edit_user_row['user_gender'] = request_int('user_gender');
            $edit_user_row['user_truename'] = request_string('user_truename');
            $edit_user_row['user_provinceid'] = request_int('province_id');
            $edit_user_row['user_cityid'] = request_int('city_id');
            $edit_user_row['user_areaid'] = request_int('area_id');
            $edit_user_row['user_area'] = request_string('address_area');
            $edit_user_row['user_qq'] = $user_qq;
            $edit_user_row['nickname'] = $nickname;
            //$edit_user_row['user_ww'] = $user_ww;
            
            $this->userInfoModel = new User_InfoDetailModel();
            $this->userPrivacyModel = new User_PrivacyModel();
            
            if (!$this->userPrivacyModel->getOne($user_id)) {
                $this->userPrivacyModel->addPrivacy(array('user_id' => $user_id));
            }
            
            if (!$this->userInfoModel->getOne($user_name)) {
                $this->userInfoModel->addInfoDetail(array('user_name' => $user_name));
            }
            
            //开启事物
            $rs_row = array();
            $this->userInfoModel->sql->startTransactionDb();
            
            $flag = $this->userInfoModel->editInfoDetail($user_name, $edit_user_row);
            check_rs($flag, $rs_row);
            
            $User_OptionModel = new User_OptionModel();
            
            foreach ($reg_opt_rows as $reg_option_id => $reg_opt_row) {
                if (isset($option_value_row[$reg_option_id])) {
                    $reg_option_value_row = explode(',', $reg_opt_row['reg_option_value']);
                    
                    $user_option_row = array();
                    $user_option_row['reg_option_id'] = $reg_option_id;
                    $user_option_row['user_id'] = $user_id;
                    //当类型为多选款时
                    if ($reg_opt_row['option_id'] == 3) {
                        if (isset($option_value_row[$reg_option_id])) {
                            $user_option_row['reg_option_value_id'] = implode(',', $option_value_row[$reg_option_id]);
                            
                            //从$reg_option_value_row中根据,$option_value_row[$reg_option_id]获取值
                            $user_option_value = array();
                            foreach ($reg_option_value_row as $rokey => $roval) {
                                if (in_array($rokey, $option_value_row[$reg_option_id])) {
                                    $user_option_value[] = $roval;
                                }
                            }
                            $user_option_row['user_option_value'] = implode(',', $user_option_value);
                            
                        }
                    } else {
                        $user_option_row['reg_option_value_id'] = $option_value_row[$reg_option_id];
                        $user_option_row['user_option_value'] = isset($reg_option_value_row[$option_value_row[$reg_option_id]]) ? $reg_option_value_row[$option_value_row[$reg_option_id]] : $option_value_row[$reg_option_id];
                    }
                    
                    //$reg_option_id
                    $user_opt_key = $User_OptionModel->getKeyByWhere(array('reg_option_id' => $reg_option_id, 'user_id' => $user_id));
                    
                    if (isset($user_opt_key[0])) {
                        $flag = $User_OptionModel->editOption($user_opt_key[0], $user_option_row);
                        check_rs($flag, $rs_row);
                    } else {
                        $flag = $User_OptionModel->addOption($user_option_row);
                        check_rs($flag, $rs_row);
                    }
                }
            }
            
            $flag = is_ok($rs_row);
            
            if ($flag && $this->userInfoModel->sql->commitDb()) {
                $status = 200;
                $msg = _('success');
                $res = $this->userInfoModel->sync($user_id);
                
                $userName = request_string('userName');
                $preg_check1 = '/^\w{5,20}$/';
                $preg_check2 = '/^\d+$/';
                if (!preg_match($preg_check1, $user_name) && preg_match($preg_check1, $userName) && !preg_match($preg_check2, $userName)) {
                    $this->modifUserName($user_name, $userName, $user_id);
                }
            } else {
                $this->userInfoModel->sql->rollBackDb();
                $status = 250;
                $msg = _('failure');
                
            }
            
            $data = array();
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
        /**
         * 修改用户名
         */
        private function modifUserName($user_name, $userName, $user_id)
        {
            $this->InfoModel = new User_InfoModel();
            //根据用户名获取信息
            $infoDetail = $this->userInfoModel->getOne($user_name);
            //根据获取到的新用户名创建新的用户信息
            $infoDetail['user_name'] = $userName;
            unset($infoDetail['id']);
            $rs_rows = [];
            $this->userInfoModel->sql->startTransactionDb();
            $res1 = $this->userInfoModel->addInfoDetail($infoDetail);
            $res1 = $res1 === false ? false : true;
            check_rs($res1, $rs_rows);
            //删除info_detail表信息
            $res2 = $this->userInfoModel->removeInfoDetail($user_name);
            $res2 = $res2 === false ? false : true;
            check_rs($res2, $rs_rows);
            //修改info表用户名
            $field_row = ['user_name' => $userName];
            $res3 = $this->InfoModel->editInfo($user_id, $field_row);
            $res3 = $res3 === false ? false : true;
            check_rs($res3, $rs_rows);
            $formvars = array(
                'user_id' => $user_id,
                'userName' => $userName
            );
            $init_rs = $this->remote_user_name($formvars);
            if ($init_rs) {
                if (is_ok($rs_rows) && $this->userInfoModel->sql->commitDb()) {
                    return $this->data->addBody(-140, [], __('修改成功'), 200);
                } else {
                    $this->userInfoModel->sql->rollBackDb();
                    return $this->data->addBody(-140, $rs_rows, __('修改失败'), 250);
                }
            }
            
        }
        
        /**
         * 同步数据
         *
         * @param type $formvars
         *
         * @return type
         */
        private function remote_user_name($formvars = [])
        {
            //同步， 需要同步启用的
            $cond_row = array();
            $cond_row['app_id:IN'] = array(102, 103, 105);
            
            $Base_App = new Base_AppModel();
            $base_app_rows = $Base_App->getByWhere($cond_row);
            
            $uri = array(
                '102' => array('ctl' => 'Api_User_Base', 'met' => 'editUserName'),
                '103' => array('ctl' => 'Api_User_Info', 'met' => 'modifUserName'),
                '105' => array('ctl' => 'Api_User_Info', 'met' => 'modifUserName')
            );
            $res = array();
            foreach ($base_app_rows as $base_app_row) {
                $url = $base_app_row['app_url'];
                
                if ($url) {
                    $key = $base_app_row['app_key'];
                    $formvars['app_id'] = $base_app_row['app_id'];
                    $url = sprintf('%s?ctl=%s&met=%s&typ=%s', $url, $uri[$base_app_row['app_id']]['ctl'], $uri[$base_app_row['app_id']]['met'], 'json');
                    
                    //权限加密数据处理
                    $init_rs = get_url_with_encrypt($key, $url, $formvars);
                    $res[]['url'] = $url;
                    $res[] = $init_rs;
                    
                }
            }
            return $res;
            
        }
        
        /**
         *获取会员头像
         *
         * @access public
         */
        public function getUserImg()
        {
            $user_id = Perm::$userId;
            $user_name = Perm::$row['user_name'];
            
            $data = $this->userInfoModel->getInfoDetail($user_name);;
            $this->user['info'] = $data[$user_name];
            
            include $this->view->getView();
        }
        
        /**
         * 修改会员头像
         *
         * @access public
         */
        public function editUserImg()
        {
            $user_id = Perm::$userId;
            $user_name = Perm::$row['user_name'];
            $edit_user_row['user_avatar'] = request_string('user_avatar');
            $flag = $this->userInfoModel->editInfoDetail($user_name, $edit_user_row);
            //echo '<pre>';print_r($flag);exit;
            if ($flag === false) {
                $status = 250;
                $msg = _('failure');
            } else {
                $status = 200;
                $msg = _('success');
                
                $res = $this->userInfoModel->sync($user_id);
//			$this->userInfoModel->sync($user_id);
            }
            
            $data = array();
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
        /**
         *获取会员等级
         *
         * @access public
         */
        public function getUserGrade()
        {
            $user_id = Perm::$userId;
            $user_name = Perm::$row['user_name'];
            
            $re = $this->userInfoModel->getOne($user_id);
            $resource = $this->userResourceModel->getOne($user_id);
            
            $re['user_growth'] = $resource['user_growth'];
            
            $user_grade_id = $re['user_grade'];
            
            $data = $this->userGradeModel->getOne($user_grade_id);
            
            $data = $this->userGradeModel->getUserExpire($data);
            
            $gradeList = $this->userGradeModel->getGradeList();
            
            $data = $this->userGradeModel->getGradeGrowth($data, $gradeList, $re);
            
            include $this->view->getView();
        }
        
        /**
         *获取会员标签
         *
         * @access public
         */
        public function tag()
        {
            $user_id = Perm::$userId;
            $user_name = Perm::$row['user_name'];
            
            $order_row['user_id'] = $user_id;
            
            $data = $this->userTagRecModel->getTagRecList($order_row);
            $re = array();
            if ($data['items']) {
                $user_tag_ids = array_column($data['items'], 'user_tag_id');
                
                $tag = array();
                
                if ($data['items']) {
                    $tag['user_tag_id:not in'] = $user_tag_ids;
                }
                
                $tag_row['user_tag_id:in'] = $user_tag_ids;
                
                $ce = $this->userTagModel->getTagList($tag_row);
                
                $user_tag = array_column($ce['items'], 'user_tag_id');
                
                foreach ($data['items'] as $key => $val) {
                    
                    if (in_array($val['user_tag_id'], $user_tag)) {
                        $data['items'][$key]['user_tag_name'] = $ce['items'][$key]['user_tag_name'];
                    }
                }
                
                $re = $this->userTagModel->getTagList($tag);
            }
            include $this->view->getView();
        }
        
        /**
         *编辑会员兴趣标签
         *
         * @access public
         */
        public function editTagRec()
        {
            $user_id = Perm::$userId;
            $user_name = Perm::$row['user_name'];
            
            $id_row = array();
            $id_row = request_row('mid');
            
            $edit_rec_row['user_id'] = $user_id;
            $edit_rec_row['tag_rec_time'] = get_date_time();
            
            //开启事物
            $rs_row = array();
            $this->userTagRecModel->sql->startTransactionDb();
            
            $order_row['user_id'] = $user_id;
            
            $de = $this->userTagRecModel->getTagRecList($order_row);
            
            $user_tag = array_column($de['items'], 'tag_rec_id');
            
            $updata_flag = $this->userTagRecModel->removeRec($user_tag);
            check_rs($updata_flag, $rs_row);
            
            foreach ($id_row as $v) {
                $edit_rec_row['user_tag_id'] = $v;
                $flag = $this->userTagRecModel->addRec($edit_rec_row);
            }
            check_rs($flag, $rs_row);
            
            $flag = is_ok($rs_row);
            if ($flag && $this->userTagRecModel->sql->commitDb()) {
                $status = 200;
                $msg = _('success');
            } else {
                $this->userTagRecModel->sql->rollBackDb();
                $msg = _('failure');
                $status = 250;
            }
            
            $data = array();
            $this->data->addBody(-140, $data, $msg, $status);
            
        }
        
        /**
         *获取会员地址信息
         *
         * @access public
         */
        public function address()
        {
            $user_id = Perm::$userId;
            $user_name = Perm::$row['user_name'];
            
            $act = request_string('act');
            
            //获取一级地址
            $district_parent_id = request_int('pid', 0);
            $baseDistrictModel = new Base_DistrictModel();
            $district = $baseDistrictModel->getDistrictTree($district_parent_id);
            
            if ($act == 'edit') {
                $userId = Perm::$userId;
                $user_address_id = request_int('id');
                
                $data = $this->userAddressModel->getAddressInfo($user_address_id);
            } elseif ($act == 'add') {
                $userId = Perm::$userId;
                
                $data = array();
            } elseif ($act == 'edit_delivery') {
                $userId = Perm::$userId;
                $data = array();
            } else {
                $order_row['user_id'] = $user_id;
                
                $data = $this->userAddressModel->getAddressList($order_row);
            }
            
            $this->data->addBody(-140, $data);
            
            include $this->view->getView();
        }
        
        /**
         *编辑会员地址信息
         *
         * @access public
         */
        public function editAddressInfo()
        {
            $user_id = Perm::$userId;
            $user_name = Perm::$row['user_name'];
            
            $user_address_id = request_int('user_address_id');
            $user_address_contact = request_string('user_address_contact');
            $user_address_area = request_string('address_area');
            $user_address_address = request_string('user_address_address');
            $user_address_phone = request_string('user_address_phone');
            $user_address_default = request_string('user_address_default');
            
            $edit_address_row['user_id'] = $user_id;
            $edit_address_row['user_address_contact'] = $user_address_contact;
            $edit_address_row['user_address_province_id'] = request_int('province_id');
            $edit_address_row['user_address_city_id'] = request_int('city_id');
            $edit_address_row['user_address_area_id'] = request_int('area_id');
            $edit_address_row['user_address_area'] = $user_address_area;
            $edit_address_row['user_address_address'] = $user_address_address;
            $edit_address_row['user_address_phone'] = $user_address_phone;
            $edit_address_row['user_address_default'] = $user_address_default;
            $edit_address_row['user_address_time'] = date("Y-m-d H:i:s", time());
            
            //验证用户
            $cond_row = array('user_id' => $user_id, 'user_address_id' => $user_address_id,);
            
            $re = $this->userAddressModel->getByWhere($cond_row);
            
            if (!$re) {
                $msg = _('failure');
                $status = 250;
            } else {
                //开启事物
                $rs_row = array();
                $this->userAddressModel->sql->startTransactionDb();
                if ($user_address_default == '1') {
                    
                    $order_row['user_id'] = $user_id;
                    $order_row['user_address_default'] = '1';
                    $de = $this->userAddressModel->getAddressList($order_row);
                    
                    if (!empty($de)) {
                        $updata_flag = $this->userAddressModel->editAddressInfo($de);
                    }
                }
                check_rs($updata_flag, $rs_row);
                
                $flag = $this->userAddressModel->editAddress($user_address_id, $edit_address_row);
                
                check_rs($flag, $rs_row);
                
                $flag = is_ok($rs_row);
                if ($flag && $this->userAddressModel->sql->commitDb()) {
                    $status = 200;
                    $msg = _('success');
                } else {
                    $this->userAddressModel->sql->rollBackDb();
                    $msg = _('failure');
                    $status = 250;
                }
                
                $edit_address_row['user_address_id'] = $user_address_id;
                $data = $edit_address_row;
                $this->data->addBody(-140, $data, $msg, $status);
            }
            
        }
        
        /**
         *增加会员地址信息
         *
         * @access public
         */
        public function addAddressInfo()
        {
            $user_id = Perm::$userId;
            $user_name = Perm::$row['user_name'];
            
            $user_address_contact = request_string('user_address_contact');
            $user_address_area = request_string('address_area');
            $user_address_address = request_string('user_address_address');
            $user_address_phone = request_string('user_address_phone');
            $user_address_default = request_string('user_address_default');
            
            $edit_address_row['user_id'] = $user_id;
            $edit_address_row['user_address_contact'] = $user_address_contact;
            $edit_address_row['user_address_province_id'] = request_int('province_id');
            $edit_address_row['user_address_city_id'] = request_int('city_id');
            $edit_address_row['user_address_area_id'] = request_int('area_id');
            $edit_address_row['user_address_area'] = $user_address_area;
            $edit_address_row['user_address_address'] = $user_address_address;
            $edit_address_row['user_address_phone'] = $user_address_phone;
            $edit_address_row['user_address_default'] = $user_address_default;
            $edit_address_row['user_address_time'] = get_date_time();
            
            $cond_row['user_id'] = $user_id;
            
            $re = $this->userAddressModel->getCount($cond_row);
            
            if ($re > 19) {
                
                $status = 250;
                $msg = _('failure');
                
            } else {
                
                //开启事物
                $rs_row = array();
                $this->userAddressModel->sql->startTransactionDb();
                
                //判断是否设默认，默认改变前面的状态
                if ($user_address_default == '1') {
                    
                    $order_row['user_id'] = $user_id;
                    $order_row['user_address_default'] = '1';
                    $de = $this->userAddressModel->getAddressList($order_row);
                    
                    if (!empty($de)) {
                        $updata_flag = $this->userAddressModel->editAddressInfo($de);
                    }
                }
                check_rs($updata_flag, $rs_row);
                $flag = $this->userAddressModel->addAddress($edit_address_row, true);
                check_rs($flag, $rs_row);
                $flag = is_ok($rs_row);
                if ($flag && $this->userAddressModel->sql->commitDb()) {
                    $edit_address_row['user_address_id'] = $flag;
                    $status = 200;
                    $msg = _('success');
                } else {
                    $this->userAddressModel->sql->rollBackDb();
                    
                    $status = 250;
                    $msg = _('failure');
                }
            }
            
            $data = $edit_address_row;
            $this->data->addBody(-140, $data, $msg, $status);
            
        }
        
        /**
         *删除会员地址信息
         *
         * @access public
         */
        public function delAddress()
        {
            $user_id = Perm::$row['user_id'];
            $user_name = Perm::$row['user_name'];
            
            $user_address_id = request_string('id');
            
            //验证用户
            $cond_row = array('user_id' => $user_id, 'user_address_id' => $user_address_id);
            $re = $this->userAddressModel->getByWhere($cond_row);
            
            if ($re) {
                $flag = $this->userAddressModel->removeAddress($user_address_id);
            } else {
                $flag = false;
            }
            
            if ($flag !== false) {
                $status = 200;
                $msg = _('success');
            } else {
                $status = 250;
                $msg = _('failure');
            }
            
            $data = array();
            
            $this->data->addBody(-140, $data, $msg, $status);
            
        }
        
        /**
         *查找好友
         *
         * @access public
         */
        public function friend()
        {
            $user_id = Perm::$userId;
            $user_name = Perm::$row['user_name'];
            $act = request_string('op');
            
            $user_name = request_string("searchname");
            
            if ($act == 'follow') {
                $cond_row['user_id'] = $user_id;
                $order_row = array('friend_addtime' => 'DESC');
                
                $Yf_Page = new Yf_Page();
                $Yf_Page->listRows = 30;
                $rows = $Yf_Page->listRows;
                $offset = request_int('firstRow', 0);
                $page = ceil_r($offset / $rows) + 1;
                
                $friend_list = $this->userFriendModel->getFriendAllDetail($cond_row, $order_row, $page, $rows);
                
                $Yf_Page->totalRows = $friend_list['totalsize'];
                $page_nav = $Yf_Page->prompt();
                
                $this->view->setMet('follow');
                
            } elseif ($act == 'fan') {
                $cond_row['friend_id'] = $user_id;
                $order_row = array('friend_addtime' => 'DESC');
                
                $Yf_Page = new Yf_Page();
                $Yf_Page->listRows = 30;
                $rows = $Yf_Page->listRows;
                $offset = request_int('firstRow', 0);
                $page = ceil_r($offset / $rows) + 1;
                
                $friend_list = $this->userFriendModel->getBeFriendAllDetail($cond_row, $order_row, $page, $rows);
                
                $Yf_Page->totalRows = $friend_list['totalsize'];
                $page_nav = $Yf_Page->prompt();
                
                $this->view->setMet('fan');
                
            } else {
                
                if ($user_name) {
                    $type = 'user_name:LIKE';
                    $cond_row[$type] = '%' . $user_name . '%';
                    $order_row = array();
                    
                    $Yf_Page = new Yf_Page();
                    $Yf_Page->listRows = 30;
                    $rows = $Yf_Page->listRows;
                    $offset = request_int('firstRow', 0);
                    $page = ceil_r($offset / $rows) + 1;
                    
                    $cond_row['user_id:!='] = $user_id;
                    
                    $user_list = $this->userInfoModel->getInfoList($cond_row, $order_row, $page, $rows);
                    
                    $friend_row = array();
                    $friend_row['friend_id:IN'] = array_column($user_list['items'], 'user_id');
                    
                    $friend_row['user_id'] = $user_id;
                    
                    $friend_list = $this->userFriendModel->getFriendAll($friend_row);
                    
                    $friend_id = array();
                    $friend_id = array_column($friend_list, 'friend_id');
                    
                    //获取已经加好友的
                    foreach ($user_list['items'] as $key => $val) {
                        
                        if (in_array($val['user_id'], $friend_id)) {
                            $user_list['items'][$key]['status'] = 1;
                        } else {
                            $user_list['items'][$key]['status'] = 0;
                        }
                    }
                    
                    $Yf_Page->totalRows = $user_list['totalsize'];
                    $page_nav = $Yf_Page->prompt();
                } else {
                    //推荐标签列表
                    $cond_row['user_tag_recommend'] = 1;
                    $sort = array('user_tag_sort' => 'DESC');
                    
                    $data = $this->userTagModel->getTagList($cond_row, $sort, 1, 10);
                    
                    if (!empty($data['items'])) {
                        foreach ($data['items'] as $key => $val) {
                            $Yf_Page = new Yf_Page();
                            $Yf_Page->listRows = 10;
                            $rows = $Yf_Page->listRows;
                            $offset = request_int('firstRow', 0);
                            $page = ceil_r($offset / $rows) + 1;
                            
                            //查询已经是好友的，排除掉
                            $friend_row['user_id'] = $user_id;
                            
                            $friend_list = $this->userFriendModel->getFriendAll($friend_row);
                            
                            $user = array();
                            
                            $user = array_column($friend_list, 'friend_id');
                            
                            array_push($user, $user_id);
                            $tag_row['user_id:not in'] = $user;
                            $tag_row['user_tag_id'] = $val['user_tag_id'];
                            $order_row = array();
                            
                            $tag = $this->userTagRecModel->getTagRecList($tag_row, $order_row, $page, $rows);
                            
                            //标签下除好友的总会员个数
                            $count = count($tag['items']);
                            $data['items'][$key]['count'] = $count;
                            
                            foreach ($tag['items'] as $k => $v) {
                                $user_row['user_id'] = $v['user_id'];
                                
                                $detail = $this->userInfoModel->getUserInfo($user_row);
                                $tag['items'][$k]['detail'] = $detail;
                            }
                            $data['items'][$key]['user'] = $tag;
                        }
                    }
                    
                }
                
            }
            include $this->view->getView();
        }
        
        /**
         *关注标签下面没有关注的会员
         *
         * @access public
         */
        public function addFriends()
        {
            $user_id = Perm::$userId;
            $user_name = Perm::$row['user_name'];
            
            $cond_row['user_id'] = $user_id;
            $cond_row['friend_addtime'] = get_date_time();
            //查询已经是好友的，排除掉
            $friend_row['user_id'] = $user_id;
            
            $friend_list = $this->userFriendModel->getFriendAll($friend_row);
            
            $user = array();
            $user = array_column($friend_list, 'friend_id');
            
            array_push($user, $user_id);
            $tag_row['user_id:not in'] = $user;
            $tag_row['user_tag_id'] = request_int('id');
            $order_row = array();
            
            $tag = $this->userTagRecModel->getTagRecList($tag_row, $order_row);
            
            //开启事物
            $rs_row = array();
            $this->userFriendModel->sql->startTransactionDb();
            
            foreach ($tag['items'] as $key => $val) {
                $user_id = $val['user_id'];
                $detail = $this->userInfoModel->getInfoDetail($user_name);
                $detail = $detail[$user_id];
                
                $cond_row['friend_id'] = $val['user_id'];
                $cond_row['friend_name'] = $detail['user_name'];
                $cond_row['friend_image'] = $detail['user_avatar'];
                
                $flag = $this->userFriendModel->addFriend($cond_row);
            }
            check_rs($flag, $rs_row);
            $flag = is_ok($rs_row);
            if ($flag && $this->userFriendModel->sql->commitDb()) {
                $status = 200;
                $msg = _('success');
            } else {
                $this->userFriendModel->sql->rollBackDb();
                $msg = _('failure');
                $status = 250;
            }
            
            $data = array();
            $this->data->addBody(-140, $data, $msg, $status);
            
        }
        
        /**
         *关注一个会员
         *
         * @access public
         */
        public function addFriendDetail()
        {
            $user_id = Perm::$userId;
            $user_name = Perm::$row['user_name'];
            
            $cond_row['user_id'] = $user_id;
            $cond_row['friend_addtime'] = get_date_time();
            
            $userId = request_int('id');
            $detail = $this->userInfoModel->getOne($userId);
            
            $cond_row['friend_id'] = $userId;
            $cond_row['friend_name'] = $detail['user_name'];
            $cond_row['friend_image'] = $detail['user_avatar'];
            
            $flag = $this->userFriendModel->addFriend($cond_row);
            
            if ($flag) {
                $status = 200;
                $msg = _('success');
            } else {
                $msg = _('failure');
                $status = 250;
            }
            
            $data = array();
            $this->data->addBody(-140, $data, $msg, $status);
            
        }
        
        /**
         *取消一个会员
         *
         * @access public
         */
        public function cancelFriendDetail()
        {
            $user_id = Perm::$userId;
            $user_name = Perm::$row['user_name'];
            
            $user_friend_id = request_int('id');
            
            $cond_row['user_id'] = $user_id;
            $cond_row['user_friend_id'] = $user_friend_id;
            
            $de = $this->userFriendModel->getFriendInfo($cond_row);
            
            if (!$de) {
                
                $msg = _('failure');
                $status = 250;
            } else {
                $flag = $this->userFriendModel->removeFriend($user_friend_id);
                
                if ($flag !== false) {
                    $status = 200;
                    $msg = _('success');
                } else {
                    
                    $msg = _('failure');
                    $status = 250;
                }
            }
            
            $data = array();
            $this->data->addBody(-140, $data, $msg, $status);
            
        }
        
        //手机绑定验证
        
        /**
         *获取会员安全信息
         *
         * @access public
         */
        public function security()
        {
            $user_id = Perm::$userId;
            $user_name = Perm::$row['user_name'];
            $op = request_string('op');
            $is_bind = request_int('op');
            $data = $this->userInfoModel->getOne($user_name);
            if ($op == 'email' || $op == 'mobile') {
                if($op == 'email')
                {
                    $name = _('邮箱');
                    if($data['user_email_verify'] == 1)
                    {
                        $action = '修改';
                    }else{
                        $action = '绑定';
                    }
                }else{
                    if($data['user_mobile_verify'] == 1)
                    {
                        $action = '修改';
                    }else{
                        $action = '绑定';
                    }
                    $name = _('手机');
                }
                if ($data['user_email']) {
                    $this->view->setMet('security_identity');
                } else {
                    $this->view->setMet('security_identity');
                }
            } elseif ($op == 'mobiles') {
                $name = _('手机');
                if($data['user_mobile_verify'] == 1)
                {
                    $action = '修改';
                }else{
                    $action = '绑定';
                }
                $this->view->setMet('mobile');
            } elseif ($op == 'emails') { 
                $name = _('邮箱');
                if($data['user_email_verify'] == 1)
                {
                    $action = '修改';
                }else{
                    $action = '绑定';
                }
                $this->view->setMet('email');
            } elseif ($op == 'passwd') {
                $action = '修改';
                $name = '密码';   
                $this->view->setMet('security_identity');
//                $this->view->setMet('passwd');
            } else {   
                $this->view->setMet('security');
            }
            //获取用户的实名验证信息
            $rs = $this->getCertification();
            $user_info = $rs['data'];
            $second1 = strtotime($user_info['user_identity_end_time']);
            $second2 = time();
            $duff_time = ($second1 - $second2) / 86400;
            $identify_status = $user_info['user_identity_statu'];
            $data = $this->userInfoModel->getOne($user_name);
            $de = $this->userBaseModel->getOne($user_id);
            $data = array_merge($data, $de);
            $data['user_level_id'] = 0;
            if ($data['user_mobile_verify']) {
                $data['user_level_id']++;
            }
            if ($data['user_email_verify']) {
                $data['user_level_id']++;
            }
            include $this->view->getView();
        }
        
        //生成验证码,并且发送验证码
        
        /**
         *  获取实名认证
         */
        public function getCertification()
        {
            //从paycenter中获取用户的实名认证信息
            $key = Yf_Registry::get('paycenter_api_key');
            $url = Yf_Registry::get('paycenter_api_url');
            $app_id = Yf_Registry::get('paycenter_app_id');
            
            $formvars = array();
            $formvars['app_id'] = $app_id;
            $formvars['user_id'] = Perm::$userId;
            $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Info&met=getUserInfo&typ=json', $url), $formvars);
            return $rs;
        }
        
        //检测验证码
        
        /**
         *修改密码
         *
         * @access public
         */
        public function passwd()
        {
            //$str = Email::send('553898963@qq.com','test','哈哈','测试');
            $user_id = Perm::$userId;
            $user_name = Perm::$row['user_name'];
            $op = request_string('op');
            
            if ($op == 'email') {
                $this->view->setMet('email');
            } elseif ($op == 'mobile') {
                $this->view->setMet('mobile');
            } elseif ($op == 'passwd') {
                $name = '密码';
                $this->view->setMet('passwd');
            }
            
            $user_id = Perm::$userId;
            
            $data = $this->userInfoModel->getInfoDetail($user_name);
            $data = $data[$user_name];
            
            $de = $this->userBaseModel->getInfo($user_id);
            $de = $de[$user_id];
            
            $data = array_merge($data, $de);
            
            $data['user_level_id'] = 0;
            
            if ($data['user_mobile_verify']) {
                $data['user_level_id']++;
            }
            
            if ($data['user_email_verify']) {
                $data['user_level_id']++;
            }
            
            include $this->view->getView();
        }
        
        //绑定手机
        
        public function getMobile()
        {
            $user_name = Perm::$row['user_name'];
            $cond_row['user_mobile'] = request_string('verify_field');
            $cond_row['user_name:!='] = $user_name;
            
            $de = $this->userInfoModel->getByWhere($cond_row);
            
            
             if ($de)
             {
            
             	$msg    = _('该手机已绑定了账号');
             	$status = 250;
             }
             else
             {
            
                $status = 200;
                $msg = _('该手机号可以使用');
            
             }
            
            $data = array();
            $this->data->addBody(-140, $data, $msg, $status);
            
        }
        
        //邮箱绑定验证
        
        public function getMobileYzm()
        {
            
            $mobile = request_string('mobile');
            $cond_row['code'] = request_string('type') == 'passwd' ? 'edit_passwd' : 'verification';
            $yzm = request_string('yzm');
            
            if (!Perm::checkYzm($yzm)) {
                return $this->data->addBody(-140, array(), _('图形验证码有误'), 250);
            }
            $de = $this->messageTemplateModel->getTemplateDetail($cond_row);
            
            $me = $de['content_phone'];
            
            $code_key = $mobile;
            $code = VerifyCode::getCode($code_key);
            
            $me = str_replace("[weburl_name]", $this->web['web_name'], $me);
            $me = str_replace("[yzm]", $code, $me);
            
            $str = Sms::send($mobile, $me, $de['baidu_tpl_id'],['weburl_name'=>$this->web['web_name'],'yzm'=>$code]);
            
            $status = $str ? 200 : 250;
            $msg = $str ? _('发送成功') : _('发送失败');
            
            $data = array();
            if (DEBUG === false) {
                $data['user_code'] = $code;
            }
            return $this->data->addBody(-140, $data, $msg, $status);
            
        }
        
        //邮箱生成验证码,并且发送验证码
        
        public function checkMobileYzm()
        {
            $yzm = request_string('yzm');
            $mobile = request_string('mobile');
            
            if (VerifyCode::checkCode($mobile, $yzm)) {
                
                $status = 200;
                $msg = _('success');
            } else {
                $msg = _('failure');
                $status = 250;
                
            }
            $data = array();
            
            $this->data->addBody(-140, $data, $msg, $status);
            
        }
        
        //邮箱检测验证码
        
        public function editMobileInfo()
        {
            $user_id = Perm::$userId;
            //检查用户信息
            $cond_user['user_id'] = $user_id;
            $userModel = new User_InfoModel();
            $user_info_row = $userModel->getOne($cond_user);
            if (!$user_info_row) {
                $data['code'] = 3;
                return $this->data->addBody(-140, $data, __('用户信息有误'), 250);
            }
            
            $user_name = $user_info_row['user_name'];
            $rs_row = array();
            
            $user_mobile = request_string('user_mobile');
            $yzm = request_string('yzm', request_string('auth_code'));
            $data = array();
            if (!VerifyCode::checkCode($user_mobile, $yzm)) {
                $data['code'] = 1;
                return $this->data->addBody(-140, $data, __('验证码错误'), 250);
            }
            
            //检查手机号是否被使用
            $userInfoDetailModel = new User_InfoDetail();
            $check_user_name = $userInfoDetailModel->checkUserName($user_mobile, $user_id);
            if ($check_user_name) {
                $data['code'] = 2;
                return $this->data->addBody(-140, $data, __('该手机已经被使用'), 250);
            }
            
            //查找bind绑定表
            $new_bind_id = sprintf('mobile_%s', $user_mobile);
            $User_BindConnectModel = new User_BindConnectModel();
            $bind_info = $User_BindConnectModel->getOne($new_bind_id);
            if (isset($bind_info['user_id']) && $bind_info['user_id'] != $user_id) {
                $data['code'] = 4;
                return $this->data->addBody(-140, $data, __('该手机已经被使用'), 250);
            }
            
            //开启事务
            $this->userInfoModel->sql->startTransactionDb();
            
            //查找该用户之前是否已经绑定手机号，如果有的话需要删除
            $user_mobile_bind = $User_BindConnectModel->getByWhere(array('user_id' => $user_id, 'bind_type' => $User_BindConnectModel::MOBILE));
            if ($user_mobile_bind) {
                $old_bind_id_row = array_keys($user_mobile_bind);
                //将之前用户绑定的手机号删除
                $flag_remove = $User_BindConnectModel->removeBindConnect($old_bind_id_row);
                check_rs($flag_remove, $rs_row);
            }
            
            //该手机号可用，将手机号写入用户详情表中，验证状态为已验证
            if ($user_name) {
                $edit_user_row['user_mobile'] = $user_mobile;
                $edit_user_row['user_mobile_verify'] = 1;
                $flag_edit = $this->userInfoModel->editInfoDetail($user_name, $edit_user_row);
                check_rs($flag_edit, $rs_row);
            } else {
                $flag_edit = true;
            }
            if ($flag_edit === false) {
                $this->userInfoModel->sql->rollBackDb();
                $data['code'] = 5;
                return $this->data->addBody(-140, $data, __('绑定失败'), 250);
            }
            
            //用户信息表中的手机号修改完成后，修改绑定表中的数据
            //添加mobile绑定.
            //绑定标记：mobile/email/openid  绑定类型+openid
            //插入绑定表
            if (isset($bind_info['user_id']) && $bind_info['user_id'] == $user_id) {
                check_rs(true, $rs_row);
            } else {
                $time = date('Y-m-d H:i:s', time());
                $bind_array = array('bind_id' => $new_bind_id, 'user_id' => $user_id, 'bind_type' => $User_BindConnectModel::MOBILE, 'bind_time' => $time);
                $flag_add = $User_BindConnectModel->addBindConnect($bind_array);
                if ($flag_add) {
                    //将用户原来绑定的手机号删除
                    $bind_id = sprintf('mobile_%s', $user_info_row['user_mobile']);
                    $flag_del = $User_BindConnectModel->removeBindConnect($bind_id);
                    check_rs($flag_del, $rs_row);
                }
            }
            
            $flag = is_ok($rs_row);
            
            $this->userInfoModel->sync($user_id);
            
            if ($flag && $this->userInfoModel->sql->commitDb()) {
                $status = 200;
                $msg = __('操作成功');
            } else {
                $this->userInfoModel->sql->rollBackDb();
                $msg = __('操作失败');
                $status = 250;
            }
            
            return $this->data->addBody(-140, $data, $msg, $status);
            
        }
        
        //绑定邮箱
        
        public function getEmail()
        {
            $user_id = Perm::$userId;
            $user_name = Perm::$row['user_name'];
            $cond_row['user_email'] = request_string('verify_field');
            $cond_row['user_name:!='] = $user_name;
            
            $de = $this->userInfoModel->getByWhere($cond_row);
            
            if ($de) {
                
                $msg = _('failure');
                $status = 250;
            } else {
                
                $status = 200;
                $msg = _('success');
                
            }
            
            $data = array();
            $this->data->addBody(-140, $data, $msg, $status);
            
        }
        
        //解除绑定,生成验证码,并且发送验证码
        
        public function getEmailYzm()
        {
            $email = request_string('email');
            
            $cond_row['code'] = 'verification';
            $yzm = request_string('yzm');
            if (!Perm::checkYzm($yzm)) {
                return $this->data->addBody(-140, array(), __('图形验证码有误'), 250);
            }
            $de = $this->messageTemplateModel->getTemplateDetail($cond_row);
            
            $me = $de['content_email'];
            $title = $de['title'];
            
            $code_key = $email;
            $code = VerifyCode::getCode($code_key);
            $me = str_replace("[weburl_name]", Web_ConfigModel::value("site_name"), $me);
            $me = str_replace("[yzm]", $code, $me);
            $title = str_replace("[weburl_name]", Web_ConfigModel::value("site_name"), $title);
            
            $str = Email::send($email, Perm::$row['user_name'], $title, $me);
            $data['code'] = $code;
            $status = $str ? 200 : 250;
            $msg = $str ? __('发送成功') : __('发送失败');
            return $this->data->addBody(-140, $data, $msg, $status);
        }
        
        //检测解除验证码
        
        public function checkEmailYzm()
        {
            
            $yzm = request_string('yzm');
            $email = request_string('email');
            
            if (VerifyCode::checkCode($email, $yzm)) {
                
                $status = 200;
                $msg = _('success');
            } else {
                $msg = _('failure');
                $status = 250;
                
            }
            $data = array();
            
            $this->data->addBody(-140, $data, $msg, $status);
            
        }
        
        //解除绑定
        
        public function editEmailInfo()
        {
            $user_id = Perm::$userId;
            $user_name = Perm::$row['user_name'];
            
            $user_email = request_string('user_email');
            $yzm = request_string('yzm');
            $data = array();
            //判断邮箱验证码是否正确
            if (!VerifyCode::checkCode($user_email, $yzm)) {
                $data['code'] = 1;
                $msg = '验证码错误';
                return $this->data->addBody(-140, $data, __('验证码错误'), 250);
            }
            
            //检查手机号是否被使用
            $userInfoDetailModel = new User_InfoDetail();
            $check_user_name = $userInfoDetailModel->checkUserName($user_email, $user_id);
            if ($check_user_name) {
                $data['code'] = 2;
                $msg = '该邮箱已经被使用';
                return $this->data->addBody(-140, $data, $msg, 250);
            }
            
            //检查用户信息
            $user_info_row = $this->userInfoModel->getOne($user_name);
            if (!$user_info_row) {
                $data['code'] = 3;
                $msg = '用户信息有误';
                return $this->data->addBody(-140, $data, $msg, 250);
            }
            
            //查找bind绑定表
            $new_bind_id = sprintf('email_%s', $user_email);
            $User_BindConnectModel = new User_BindConnectModel();
            $bind_info = $User_BindConnectModel->getOne($new_bind_id);
            if (isset($bind_info['user_id']) && $bind_info['user_id'] != $user_id) {
                $data['code'] = 4;
                $msg = '该邮箱已经被使用';
                return $this->data->addBody(-140, $data, $msg, 250);
            }
            
            //开启事务
            $this->userInfoModel->sql->startTransactionDb();
            
            //将邮箱绑定到用户详情表中并修改邮箱验证状态为已验证
            $edit_user_row['user_email'] = $user_email;
            $edit_user_row['user_email_verify'] = 1;
            $flag_edit = $this->userInfoModel->editInfoDetail($user_name, $edit_user_row);
            
            if ($flag_edit === false) {
                $this->userInfoModel->sql->rollBackDb();
                $data['code'] = 4;
                return $this->data->addBody(-140, $data, __(''), 250);
            }
            
            //用户信息表中的手机号修改完成后，修改绑定表中的数据
            //添加mobile绑定.
            //绑定标记：mobile/email/openid  绑定类型+openid
            $flag = false;
            if (isset($bind_info['user_id']) && $bind_info['user_id'] == $user_id) {
                $flag = true;
            } else {
                $time = date('Y-m-d H:i:s', time());
                //插入绑定表
                $bind_array = array('bind_id' => $new_bind_id, 'user_id' => $user_id, 'bind_type' => $User_BindConnectModel::EMAIL, 'bind_time' => $time);
                $flag_add = $User_BindConnectModel->addBindConnect($bind_array);
                //将用户原来绑定的手机号删除
                if ($flag_add) {
                    $bind_id = sprintf('email_%s', $user_info_row['user_email']);
                    $flag_del = $User_BindConnectModel->removeBindConnect($bind_id);
                    if ($flag_del !== false) {
                        $flag = true;
                    }
                }
            }
            
            $this->userInfoModel->sync($user_id);
            
            if ($flag && $this->userInfoModel->sql->commitDb()) {
                $status = 200;
                $msg = __('操作成功');
            } else {
                $this->userInfoModel->sql->rollBackDb();
                $msg = __('操作失败');
                $status = 250;
            }
            
            return $this->data->addBody(-140, $data, $msg, $status);
        }
        
        //修改绑定信息之前验证用户身份
        
        public function getYzm()
        {
            $type = request_string('type');
            $val = request_string('val');
            $yzm = request_string('yzm');
            if (!Perm::checkYzm($yzm)) {
                return $this->data->addBody(-140, array(), __('图形验证码有误'), 250);
            }
            $cond_row['code'] = 'Lift verification';
            
            $de = $this->messageTemplateModel->getTemplateDetail($cond_row);
            
            if ($type == 'mobile') {
                $me = $de['content_phone'];
                
                $code_key = $val;
                $code = VerifyCode::getCode($code_key);
                $me = str_replace("[weburl_name]", $this->web['web_name'], $me);
                $me = str_replace("[yzm]", $code, $me);
                
                $str = Sms::send($val, $me, $de['baidu_tpl_id'],['weburl_name'=>$this->web['web_name'],'yzm'=>$code]);
            } else {
                $me = $de['content_email'];
                $title = $de['title'];
                
                $code_key = $val;
                $code = VerifyCode::getCode($code_key);
                $me = str_replace("[weburl_name]", Web_ConfigModel::value("site_name"), $me);
                $me = str_replace("[yzm]", $code, $me);
                $title = str_replace("[weburl_name]", Web_ConfigModel::value("site_name"), $title);
                
                $str = Email::send($val, Perm::$row['user_name'], $title, $me);
            }
            $data = array();
            $status = 200;
            if (DEBUG == false) {
                $data[] = $code;
            }
            $msg = "success";
            return $this->data->addBody(-140, $data, $msg, $status);
            
        }
        
        //账号绑定（第三方）
        
        public function checkYzm()
        {
            
            $yzm = request_string('yzm');
            $type = request_string('type');
            $val = request_string('val');
            
            if (VerifyCode::checkCode($val, $yzm)) {
                
                $status = 200;
                $msg = _('success');
            } else {
                $msg = _('failure');
                $status = 250;
                
            }
            $data = array();
            
            $this->data->addBody(-140, $data, $msg, $status);
            
        }
        
        //解除绑定
        
        public function editAllInfo()
        {
            $type = request_string('type');
            $yzm = request_string('yzm');
            $val = request_string('val');
            $user_id = Perm::$userId;
            $user_name = Perm::$row['user_name'];
            
            $user_info_row = $this->userInfoModel->getOne($user_name);
            
            $flag = false;
            
            if (!VerifyCode::checkCode($val, $yzm)) {
                $msg = _('failure');
                $status = 240;
            } else {
                if ($type == 'passwd') {
                    $password = request_string('password');
                    
                    if ($password) {
                        $edit_user_row['password'] = md5($password);
                        
                        $de = $this->userBaseModel->getOne($user_id);
                        $flag = $this->userBaseModel->editInfo($user_id, $edit_user_row);
                    } else {
                        $msg = _('密码不能为空');
                        $status = 250;
                    }
                } else {
                    if ($type == 'mobile') {
                        $edit_user_row['user_mobile'] = '';
                        $edit_user_row['user_mobile_verify'] = 0;
                        
                        {
                            
                            //添加mobile绑定.
                            //绑定标记：mobile/email/openid  绑定类型+openid
                            $bind_id = sprintf('mobile_%s', $user_info_row['user_mobile']);
                            
                            //查找bind绑定表
                            $User_BindConnectModel = new User_BindConnectModel();
                            $bind_info = $User_BindConnectModel->getOne($bind_id);
                            
                            if ($bind_info) {
                                $User_BindConnectModel->removeBindConnect($bind_id);
                            }
                        }
                        
                    } else if ($type == 'email') {
                        $edit_user_row['user_email'] = '';
                        $edit_user_row['user_email_verify'] = 0;
                        
                        {
                            //添加mobile绑定.
                            //绑定标记：mobile/email/openid  绑定类型+openid
                            $bind_id = sprintf('email_%s', $user_info_row['user_email']);
                            
                            //查找bind绑定表
                            $User_BindConnectModel = new User_BindConnectModel();
                            $bind_info = $User_BindConnectModel->getOne($bind_id);
                            
                            if ($bind_info) {
                                $User_BindConnectModel->removeBindConnect($bind_id);
                            }
                        }
                    } else {
                        $edit_user_row['user_email'] = '';
                        $edit_user_row['user_email_verify'] = 0;
                        
                        {
                            //添加mobile绑定.
                            //绑定标记：mobile/email/openid  绑定类型+openid
                            $bind_id = sprintf('email_%s', $user_info_row['user_email']);
                            
                            //查找bind绑定表
                            $User_BindConnectModel = new User_BindConnectModel();
                            $bind_info = $User_BindConnectModel->getOne($bind_id);
                            
                            if ($bind_info) {
                                $User_BindConnectModel->removeBindConnect($bind_id);
                            }
                        }
                    }
                    
                    $de = $this->userInfoModel->getOne($user_name);
                    $flag = $this->userInfoModel->editInfoDetail($user_name, $edit_user_row);
                }
                
                fb($flag);
                if ($flag === false) {
                    $msg = _('failure');
                    $status = 250;
                    
                } else {
                    
                    $status = 200;
                    $msg = _('success');
                    
                    $this->userInfoModel->sync($user_id);
                }
            }
            $data = array();
            
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
        public function checkUserIdentity()
        {
            $type = request_string('type');
            $yzm = request_string('yzm');
            $val = request_string('val');
            $checkFrom = request_string('checkFrom');
            $user_id = Perm::$userId;
            $user_name = Perm::$row['user_name'];
            
            $user_info_row = $this->userInfoModel->getOne($user_name);  
            $flag = false;
            $msg = '';
            $status = 250;
            if (!VerifyCode::checkCode($val, $yzm)) {
                $msg = _('验证码错误');
                $status = 240;
            } else {
                //手机验证
                if ($checkFrom == 'mobile') {
                    //查找绑定用户
                    //绑定标记：mobile/email/openid  绑定类型+openid
                    $bind_id = sprintf('mobile_%s', $user_info_row['user_mobile']);
                    //查找bind绑定表
                    $User_BindConnectModel = new User_BindConnectModel();
                    $bind_info = $User_BindConnectModel->getOne($bind_id);
                    if ($bind_info) {
                        $flag = true;
                    }
                } else  //除手机验证外，默认使用邮箱验证
                {
                    //查找绑定用户
                    //绑定标记：mobile/email/openid  绑定类型+openid
                    $bind_id = sprintf('email_%s', $user_info_row['user_email']);
                    
                    //查找bind绑定表
                    $User_BindConnectModel = new User_BindConnectModel();
                    $bind_info = $User_BindConnectModel->getOne($bind_id);
                    
                    if ($bind_info) {
                        $flag = true;
                    }
                    
                }
                
                if ($flag === false) {
                    $msg = $msg ? $msg : __('failure');
                    $status = $status ? $status : 250;
                    
                } else {
                    $status = 200;
                    $msg = _('success');
                }
            }
            $data['checkFrom'] = $checkFrom;
            
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
        public function bindAccount()
        {
            $user_id = Perm::$userId;
            $user_account = Perm::$row['user_name'];
            
            $qq_url = sprintf('%s?ctl=Connect_Qq&met=login', Yf_Registry::get('url'));
            $wx_url = sprintf('%s?ctl=Connect_Weixin&met=login', Yf_Registry::get('url'));
            $wb_url = sprintf('%s?ctl=Connect_Weibo&met=login', Yf_Registry::get('url'));
            
            //查找用户的绑定信息
            $User_BindConnectModel = new User_BindConnectModel();
            //1.QQ绑定信息
            $qq_bind = $User_BindConnectModel->getBindConnectByUseridType($user_id, User_BindConnectModel::QQ);
            fb($qq_bind);
            
            //2.微信绑定信息
            $wx_bind = $User_BindConnectModel->getBindConnectByUseridType($user_id, User_BindConnectModel::WEIXIN);
            fb($wx_bind);
            
            //3.微博绑定信息
            $wb_bind = $User_BindConnectModel->getBindConnectByUseridType($user_id, User_BindConnectModel::SINA_WEIBO);
            fb($wb_bind);
            
            //4.手机绑定信息
            $mobile_bind = $User_BindConnectModel->getBindConnectByuserid($user_id, User_BindConnectModel::MOBILE);
            
            include $this->view->getView();
        }
        
        public function unbind()
        {
            $type = request_int('type');
            $user_id = Perm::$userId;
            $user_name = Perm::$row['user_name'];
            
            $User_BindConnectModel = new User_BindConnectModel();
            $bind_id_row = $User_BindConnectModel->getBindConnectByuserid($user_id, $type);
            
            $User_InfoDetailModel = new User_InfoDetailModel();
            $user_info_datail = $User_InfoDetailModel->getInfoDetail($user_name);
            $user_info_datail = current($user_info_datail);
            
            /*$array = array();
            $array['user_id'] = 0;
            $flag = $User_BindConnectModel->editBindConnect($bind_id_row,$array);*/
            $flag = $User_BindConnectModel->removeBindConnect($bind_id_row);
            
            if ($flag) {
                $msg = _('failure');
                $status = 250;
                
            } else {
                $status = 200;
                $msg = _('success');
                
                //发送解除绑定提示信息
                $mobile = $user_info_datail['user_mobile'];
                
                $cond_row['code'] = 'unbind';
                
                $de = $this->messageTemplateModel->getTemplateDetail($cond_row);
                
                $me = $de['content_phone'];
                
                $me = str_replace("[weburl_name]", $this->web['web_name'], $me);
                
                Sms::send($mobile, $me, $de['baidu_tpl_id'],['weburl_name'=>$this->web['web_name']]);
            }
            
            $data = array();
            
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
        /**
         * 获取im的用户昵称，统一使用ucenter的nickname，不使用im的nickname
         *
         * @return type
         */
        public function getImNickname()
        {
            $im_user_name = request_string('im_user_name');
            if (!$im_user_name) {
                return $this->data->addBody(-140, [], __('failure'), 250);
            }
            $user_model = new User_InfoDetail();
            $user_info = $user_model->getOne($im_user_name);
            $nickname = $user_info['nickname'] ? $user_info['nickname'] : $user_info['user_name'];
            return $this->data->addBody(-140, ['nickname' => $nickname], __('failure'), 200);
        }
    }

?>
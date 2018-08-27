<?php

class Api_UserCtl extends Api_Controller
{
    public function getUserInfo()
    {
        $user_id = request_int('user_id');

        if(!$user_id){
            return $this->data->addBody(-140, array('user_id'=>$user_id),__('数据有误'),250);
        }
        $user_info_row = array();

        $User_InfoModel = new User_InfoModel();
        $user_row = $User_InfoModel->getOne($user_id);

        if ($user_row)
        {
            $User_InfoDetailModel = new User_InfoDetailModel();
            $user_info_row = $User_InfoDetailModel->getOne($user_row['user_name']);
            $user_info_row['password'] = $user_row['password'];
            $user_info_row['user_id'] = $user_id;
            return $this->data->addBody(-140, $user_info_row);
        }
        else
        {
            return $this->data->addBody(-140, array('data'=>$user_row),__('数据有误'),250);
        }

    }


    //获取列表信息
    public function listUser()
    {
        $skey = request_string('skey');
        $page = $_REQUEST['page'];
        $rows = $_REQUEST['rows'];
        $asc = $_REQUEST['asc'];
        $userInfoModel = new User_InfoDetailModel();

        $items = array();
        $cond_row = array();
        $order_row['user_reg_time'] = 'desc';

        if($skey)
        {
            $cond_row['user_name:LIKE'] = '%'.$skey.'%';
        }

        $data = $userInfoModel->getInfoDetailList($cond_row, $order_row, $page, $rows);

        if($data){
            $msg = 'success';
            $status = 200;
        }
        else{
            $msg = 'failure';
            $status = 250;
        }
        $this->data->addBody(-140,$data,$msg,$status);
    }


    function details()
    {
        $user_name = request_string('id');
        $status = $_REQUEST['server_status'];
        //开启事物
        $User_InfoDetailModel  = new User_InfoDetailModel();

        $data = $User_InfoDetailModel->getOne($user_name);

        $User_InfoModel = new User_InfoModel();
        $user_id = $User_InfoModel->getUserIdByName($user_name);

        //扩展字段
        $User_OptionModel = new User_OptionModel();
        $user_option_rows = $User_OptionModel->getByWhere(array('user_id'=>$user_id));


        if ($user_option_rows)
        {
            $Reg_OptionModel = new Reg_OptionModel();
            $reg_opt_rows = $Reg_OptionModel->getByWhere(array('reg_option_active'=>1));


            foreach ($user_option_rows as $user_option_id=>$user_option_row)
            {
                $user_option_row['reg_option_name'] = $reg_opt_rows[$user_option_row['reg_option_id']]['reg_option_name'];

                $user_option_rows[$user_option_id] = $user_option_row;
            }
        }

        $data['user_option_rows'] = $user_option_rows;

        $this->data->addBody(-140, $data);
    }

    function add()
    {
        $user_name 	= request_string('user_name');
        $password 	= request_string('password');
        $User_InfoModel = new User_InfoModel();
        $User_InfoDetail = new User_InfoDetailModel();

        $cond_row['user_name']   = $user_name;
        $cond_row['password']    = md5($password);
        $user_name_info_1 = $User_InfoModel->getByWhere(array('user_name'=>$cond_row['user_name']));
        $user_name_info_1 = current($user_name_info_1) ;

        if($user_name_info_1){
            $status = 250;
            $msg    = '此用户已存在！请更换用户名。';
            $data['id'] = $user_name_info_1['user_id'];
            return $this->data->addBody(-1, $data, $msg, $status);
        }else{
            $last_id = $User_InfoModel->addInfo($cond_row,true);

            $arr_field_user_info_detail = array();
            $now_time = time();
            $ip       = get_ip();
            $arr_field_user_info_detail['user_name']           = $user_name;
            $arr_field_user_info_detail['user_reg_time']       = $now_time;
            $arr_field_user_info_detail['user_count_login']    = 1;
            $arr_field_user_info_detail['user_lastlogin_time'] = $now_time;
            $arr_field_user_info_detail['user_lastlogin_ip']   = $ip;
            $arr_field_user_info_detail['user_reg_ip']         = $ip;
            $arr_field_user_info_detail['user_mobile']   = $mobile;
            $User_InfoDetail->addInfoDetail($arr_field_user_info_detail);

            $msg    = 'success';
            $status = 200;
            $data['id'] = $last_id;
        }

        $this->data->addBody(-1, $data, $msg, $status);
    }

    function change()
    {

        $user_name = request_string('id');
        $status = $_REQUEST['server_status'];
        $userInfoModel = new User_InfoModel();

        if($user_name)
        {
            $data['user_state'] = $status;
            $user_id = $userInfoModel->getUserIdByName($user_name);
            $flag = $userInfoModel->editInfo($user_id, $data);

            if(false !== $flag)
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
        $this->data->addBody(-140,array(),$msg,$status);
    }

    //解除绑定,生成验证码,并且发送验证码
    public function getYzm()
    {
        $type = request_string('type');
        $val  = request_string('val');

        $cond_row['code'] = 'Lift verification';

        $Message_TemplateModel = new Message_TemplateModel();

        $de = $Message_TemplateModel->getTemplateDetail($cond_row);

        fb($de);
        if ($type == 'mobile')
        {
            $me = $de['content_phone'];

            $code_key = $val;
            $code     = VerifyCode::getCode($code_key);
            $me       = str_replace("[weburl_name]", $this->web['web_name'], $me);
            $me       = str_replace("[yzm]", $code, $me);

            $str = Sms::send($val, $me, $de['baidu_tpl_id'],['weburl_name'=>$this->web['web_name'],'yzm'=>$code]);
        }
        else
        {
            $me    = $de['content_email'];
            $title = $de['title'];

            $code_key = $val;
            $code     = VerifyCode::getCode($code_key);
            $me       = str_replace("[weburl_name]", Web_ConfigModel::value("site_name"), $me);
            $me       = str_replace("[yzm]", $code, $me);
            $title    = str_replace("[weburl_name]", Web_ConfigModel::value("site_name"), $title);

            $str = Email::send($val, Perm::$row['user_account'], $title, $me);
        }
        $status = 200;
        $data   = array($code);
        $msg    = "success";
        $this->data->addBody(-140, $data, $msg, $status);

    }

    /**
     * 修改会员密码
     *
     * @access public
     */
    public function editUserPassword()
    {
        $user_name   = request_string('user_id');
        $user_password = request_string('user_password');

        $User_InfoModel = new User_InfoModel();
        $rs_row = array();

        //开启事务
        $User_InfoModel->sql->startTransactionDb();

        if($user_name && $user_password)
        {
            $user_id = $User_InfoModel->getUserIdByName($user_name);

            $edit_user['password'] = md5($user_password);
            $flag = $User_InfoModel->editInfo($user_id,$edit_user);
            check_rs($flag, $rs_row);
        }

        $flag = is_ok($rs_row);

        if ($flag && $User_InfoModel->sql->commitDb())
        {
            $status = 200;
            $msg    = _('success');
        }
        else
        {
            $User_InfoModel->sql->rollBackDb();
            $status = 250;
            $msg    = _('failure');
        }

        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 修改会员头像
     *
     * @access public
     */
    public function editUserImg()
    {
        $user_id   = request_int('user_id');
        $User_Info = new User_Info();
        $user_info = current($User_Info->getInfo($user_id));
        $user_name = $user_info['user_name'];

        $userInfoModel  = new User_InfoDetailModel();
        $edit_user_row['user_avatar'] = request_string('user_avatar');

        $flag = $userInfoModel->editInfoDetail($user_name, $edit_user_row);
//		$data = array();
//		$data[0] = $user_name;
//		$this->data->addBody(-140, $edit_user_row);
        $data = array();
        //echo '<pre>';print_r($flag);exit;
        if ($flag === false)
        {
            $status = 250;
            $msg    = _('failure');
        }
        else
        {
            $status = 200;
            $msg    = _('success');
            $data[0] = $flag;
            $res = $userInfoModel->sync($user_id);
            //$userInfoModel->sync($user_id);
        }

        $this->data->addBody(-140, $data, $msg, $status);
    }


    /**
     * 修改会员信息
     *
     * @access public
     */
    public function editUserInfoDetail()
    {
        $user_id   = request_int('user_id');
        $User_Info = new User_Info();
        $user_info = current($User_Info->getInfo($user_id));
        $user_name = $user_info['user_name'];

        //$year    = request_int('year');
        //$month   = request_int('month');
        //$day     = request_int('day');
        //$user_qq = request_string('user_qq');

        $edit_user_row['user_birth']      = request_string('user_birth');
        $edit_user_row['user_gender']     = request_int('user_gender');
        $edit_user_row['user_truename']   = request_string('user_truename');
        $edit_user_row['user_provinceid'] = request_int('province_id');
        $edit_user_row['user_cityid']     = request_int('city_id');
        $edit_user_row['user_areaid']     = request_int('area_id');
        $edit_user_row['user_area']       = request_string('user_area');
        $edit_user_row['nickname']       = request_string('nickname');
        $edit_user_row['user_sign']       = request_string('user_sign');
        $edit_user_row['user_province']       = request_string('user_province');
        $edit_user_row['user_city']       = request_string('user_city');

        //$edit_user_row['user_ww'] = $user_ww;
        //echo '<pre>';print_r($edit_user_row);exit;
        $userInfoModel  = new User_InfoDetailModel();
        $userPrivacyModel = new User_PrivacyModel();

        if (!$userPrivacyModel->getOne($user_id))
        {
            $userPrivacyModel->addPrivacy(array('user_id'=>$user_id));
        }

        if (!$userInfoModel->getOne($user_name))
        {
            $userInfoModel->addInfoDetail(array('user_name'=>$user_name));
        }

        //开启事物
        $rs_row = array();
        $userInfoModel->sql->startTransactionDb();

        //$flagPrivacy = $this->userPrivacyModel->editPrivacy($user_id, $rows);
        //check_rs($flagPrivacy, $rs_row);
        $flag = $userInfoModel->editInfoDetail($user_name, $edit_user_row);
        check_rs($flag, $rs_row);
        $flag_status = array();
        $flag_status[0] = $flag;

        $flag = is_ok($rs_row);
        $flag_status[1] = $flag;
        $res = array();
        if ($flag && $userInfoModel->sql->commitDb())
        {
            $status = 200;
            $msg    = _('success');

            $res = $userInfoModel->sync($user_id);
        }
        else
        {
            $userInfoModel->sql->rollBackDb();
            $status = 250;
            $msg    = _('failure');

        }


        $this->data->addBody(-140, $flag_status, $msg, $status);
    }


    /**
     * 修改会员信息
     *
     * @access public
     */
    public function editUserInfo()
    {
        $user_id   = request_int('user_id');
        $User_Info = new User_Info();
        $user_info = current($User_Info->getInfo($user_id));
        $user_name = $user_info['user_name'];
        $edit_user_row['user_gender']     = request_int('user_gender');
        $edit_user_row['user_avatar']   = request_string('user_logo');
        $user_delete = request_int('user_delete');

        //开启事物
        $User_InfoDetailModel  = new User_InfoDetailModel();
        $rs_row = array();
        $User_InfoDetailModel->sql->startTransactionDb();

        $User_InfoModel = new User_InfoModel();
        $user_row = $User_InfoModel->getOne($user_id);
        if($user_delete)
        {
            $edit_user['user_state'] = 3;
            $flagState =$User_InfoModel->editInfo($user_id,$edit_user);
            check_rs($flagState, $rs_row);
        }
        else
        {
            if($user_row['user_state'] == 3)
            {
                $edit_user['user_state'] = 0;  //解禁后用户状态恢复到未激活
                $flagState =$User_InfoModel->editInfo($user_id,$edit_user);
                check_rs($flagState, $rs_row);
            }
        }

        $flag = $User_InfoDetailModel->editInfoDetail($user_name, $edit_user_row);
        check_rs($flag, $rs_row);

        $flag = is_ok($rs_row);

        if ($flag && $User_InfoDetailModel->sql->commitDb())
        {
            $status = 200;
            $msg    = _('success');


            $User_InfoDetailModel->sync($user_id);
        }
        else
        {
            $User_InfoDetailModel->sql->rollBackDb();
            $status = 250;
            $msg    = _('failure');

        }

        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function checkUserAccount()
    {
        $user_name 	= request_string('user_name');
        $password 	= request_string('password');

        $User_InfoModel = new User_InfoModel();
        $cond_row = array();
        $cond_row['user_name']	 = $user_name;
        $cond_row['password'] 	 = md5($password);

        $user_info = $User_InfoModel->getOneByWhere($cond_row);

        $data = array();
        if($user_info)
        {
            $data['user_id'] 	= $user_info['user_id'];
            $data['user_name'] 	= $user_info['user_name'];
            $data['password'] 	= $user_info['password'];
            $msg    = 'success';
            $status = 200;

            // cookie login

            $session_id   = $user_info['session_id'];
            $d            = array();
            $d['user_id'] = $user_info['user_id'];
            $data['k'] = Perm::encryptUserInfo($d, $session_id);


            //
        }
        else
        {
            $msg    = '用户不存在1111';
            $status = 250;
        }
        $this->data->addBody(-1, $data, $msg, $status);

    }


    /*webpos通过用户名获取用户id
 * */
    public function getUserIdByUsername(){
        $user_name = request_string('user_name');
        $User_InfoModel = new User_InfoModel();
        $user_info_data = $User_InfoModel->getOneByWhere(['user_name'=>$user_name]);
        $data = [];
        if($user_info_data)
        {
            $data['user_id'] = $user_info_data['user_id'];
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

    //修改绑定手机
    public function editMobileInfo()
    {
        $user_id   = request_int('user_id');
        $user_name = request_string('user_name');
        $user_mobile = request_string('user_mobile');

        $rs_row = array();
        $data = array();

        //检查手机号是否被使用
        $userInfoDetailModel = new User_InfoDetail();
        $check_user_name = $userInfoDetailModel->checkUserName($user_mobile);
        if($check_user_name)
        {
            $data['code'] = 2;
            $this->data->addBody(-140, $data, __('该手机已经被使用'), 250);
        }

        //检查用户信息
        $cond_user['user_id'] = $user_id;
        $userModel = new User_InfoModel();
        $user_info_row = $userModel->getOne($cond_user);
        if (!$user_info_row)
        {
            $data['code'] = 3;
            $this->data->addBody(-140, $data, __('用户信息有误'), 250);
        }

        //查找bind绑定表
        $new_bind_id = sprintf('mobile_%s', $user_mobile);
        $User_BindConnectModel = new User_BindConnectModel();
        $bind_info             = $User_BindConnectModel->getOne($new_bind_id);
        if (isset($bind_info['user_id']) && $bind_info['user_id'] != $user_id)
        {
            $data['code'] = 4;
            $this->data->addBody(-140, $data, __('该手机已经被使用'), 250);
        }

        //开启事务
        $User_InfoModel = new User_InfoDetailModel();
        $User_InfoModel->sql->startTransactionDb();

        //查找该用户之前是否已经绑定手机号，如果有的话需要删除
        $user_mobile_bind = $User_BindConnectModel->getByWhere(array('user_id'=>$user_id,'bind_type'=>$User_BindConnectModel::MOBILE));
        if($user_mobile_bind)
        {
            $old_bind_id_row = array_keys($user_mobile_bind);
            //将之前用户绑定的手机号删除
            $flag_remove = $User_BindConnectModel->removeBindConnect($old_bind_id_row);
            check_rs($flag_remove,$rs_row);
        }

        //该手机号可用，将手机号写入用户详情表中，验证状态为已验证
        if($user_name)
        {
            $edit_user_row['user_mobile']        = $user_mobile;
            $edit_user_row['user_mobile_verify'] = 1;
            $flag_edit = $User_InfoModel->editInfoDetail($user_name, $edit_user_row);
            check_rs($flag_edit,$rs_row);
        }else{
            $flag_edit = true;
        }
        if ($flag_edit === false)
        {
            $User_InfoModel->sql->rollBackDb();
            $data['code'] = 5;
            $this->data->addBody(-140, $data, __('绑定失败'), 250);
        }

        //用户信息表中的手机号修改完成后，修改绑定表中的数据
        //添加mobile绑定.
        //绑定标记：mobile/email/openid  绑定类型+openid
        //插入绑定表
        if (isset($bind_info['user_id']) && $bind_info['user_id'] == $user_id)
        {
            check_rs(true,$rs_row);
        }
        else
        {
            $time = date('Y-m-d H:i:s', time());
            $bind_array = array('bind_id' => $new_bind_id, 'user_id' => $user_id, 'bind_type' => $User_BindConnectModel::MOBILE, 'bind_time' => $time);
            $flag_add = $User_BindConnectModel->addBindConnect($bind_array);
            if ($flag_add)
            {
                //将用户原来绑定的手机号删除
                $bind_id = sprintf('mobile_%s', $user_info_row['user_mobile']);
                $flag_del = $User_BindConnectModel->removeBindConnect($bind_id);
                check_rs($flag_del,$rs_row);
            }
        }

        $flag = is_ok($rs_row);

        $User_InfoModel->sync($user_id);

        if ($flag && $User_InfoModel->sql->commitDb())
        {
            $status = 200;
            $msg    = __('操作成功');
        }
        else
        {
            $User_InfoModel->sql->rollBackDb();
            $msg    =  __('操作失败');
            $status = 250;
        }

        $this->data->addBody(-140, $data, $msg, $status);

    }

    public function getUserInfoDetail()
    {
        $user_name = request_string('user_name');

        $User_InfoDetailModel = new User_InfoDetailModel();

        $user_info = $User_InfoDetailModel->getInfoDetail($user_name);

        $this->data->addBody(100, $user_info);

    }

    public function register()
    {
        $user_name 	= request_string('user_name');
        $password 	= request_string('password');
        $mobile     = request_string('user_mobile');
        $reg_checkcode = request_int('reg_checkcode', 1);
        $server_id = 0;

        $rs_row = array();
        $cond_row = array();
        $cond_row['user_name']	 = $user_name;
        $cond_row['password'] 	 = md5($password);
        //用户是否存在
        $User_InfoModel = new User_InfoModel();
        $User_InfoDetail = new User_InfoDetailModel();
        //$user_rows_1 = $User_InfoDetail->getByWhere(array('user_name'=>$cond_row['user_name']));       
        $user_name_info_1 = $User_InfoModel->getByWhere(array('user_name'=>$cond_row['user_name']));
        $user_name_info_1 = current($user_name_info_1) ;
        if(!empty($mobile)) {
            $user_name_info_2 = $User_InfoDetail->getByWhere(array('user_mobile'=>$mobile));
            $user_name_info_2 = current($user_name_info_2) ;
        }else{

            $user_name_info_2=false;
        }

        if($user_name_info_1){
            $status = 250;
            $msg    = '此用户已存在！请更换用户名。';
            $data['id'] = $user_name_info_1['user_id'];
            return $this->data->addBody(-1, $data, $msg, $status);
        }elseif($user_name_info_2) {
            $status = 250;
            $msg    = '手机号已绑定！请更换手机号。';
            $data['id'] = $user_name_info_1['user_id'];
            return $this->data->addBody(-1, $data, $msg, $status);
        }else{
            $User_InfoModel -> sql -> startTransaction();
            $Db = Yf_Db::get('ucenter');
            $seq_name = 'user_id';
            $user_id = $Db -> nextId($seq_name);
            $now_time = time();
            $ip = get_ip();
            $session_id = uniqid();
            $arr_field_user_info = array();
            $arr_field_user_info['user_id'] = $user_id;
            $arr_field_user_info['user_name'] = $user_name;
            $arr_field_user_info['password'] = md5($password);
            $arr_field_user_info['action_time'] = $now_time;
            $arr_field_user_info['action_ip'] = $ip;
            $arr_field_user_info['session_id'] = $session_id;
            $flag =$User_InfoModel ->addInfo($arr_field_user_info);
            array_push($rs_row, $flag);
            $arr_field_user_info_detail = array();
            //添加mobile绑定.
            //绑定标记：mobile/email/openid  绑定类型+openid
            $bind_id = $reg_checkcode == 1 ? sprintf('mobile_%s', $mobile) : sprintf('email_%s', $email);
            //查找bind绑定表
            $User_BindConnectModel = new User_BindConnectModel();
            $bind_info = $User_BindConnectModel -> getOne($bind_id);
            
            if (!$bind_info) {
                $time = date('Y-m-d H:i:s', time());
                //插入绑定表
                $bind_array = array(
                    'bind_id' => $bind_id,
                    'user_id' => $user_id,
                    'bind_type' => $reg_checkcode == 1 ? $User_BindConnectModel::MOBILE : $User_BindConnectModel::EMAIL,
                    'bind_time' => $time
                );
                $flag = $User_BindConnectModel -> addBindConnect($bind_array);
                array_push($rs_row, $flag);
                //绑定关系
                if ($reg_checkcode == 1) {
                    $arr_field_user_info_detail['user_mobile_verify'] = 1;
                } else {
                    $arr_field_user_info_detail['user_email_verify'] = 1;
                }
            }else{
                $msg    = '该手机号已经注册！请更换手机号。';
                $status = 250;
                $data['id'] = '';
                return $this->data->addBody(-1, $data, $msg, $status);
            }
            $arr_field_user_info_detail['user_name'] = $user_name;
            if ($reg_checkcode == 1) {
                $arr_field_user_info_detail['user_mobile'] = $mobile;
            } else {
                $arr_field_user_info_detail['user_email'] = $email;
            }
            //$arr_field_user_info_detail['user_mobile_verify']         = 1;
            $arr_field_user_info_detail['user_reg_time'] = $now_time;
            $arr_field_user_info_detail['user_count_login'] = 1;
            $arr_field_user_info_detail['user_lastlogin_time'] = $now_time;
            $arr_field_user_info_detail['user_lastlogin_ip'] = $ip;
            $arr_field_user_info_detail['user_reg_ip'] = $ip;
            $arr_field_user_info_detail['user_avatar'] = Web_ConfigModel::value('user_default_avatar', Yf_Registry::get('static_url') . '/images/default_user_portrait.png');
            $flag = $User_InfoDetail -> addInfoDetail($arr_field_user_info_detail);
            array_push($rs_row, $flag);
            // $User_OptionModel = new User_OptionModel();
            // foreach ($reg_opt_rows as $reg_option_id => $reg_opt_row) {
            //     if (isset($option_value_row[$reg_option_id])) {
            //         $reg_option_value_row = explode(',', $reg_opt_row['reg_option_value']);
            //         $user_option_row = array();
            //         $user_option_row['reg_option_id'] = $reg_option_id;
            //         $user_option_row['reg_option_value_id'] = $option_value_row[$reg_option_id];
            //         $user_option_row['user_id'] = $user_id;
            //         $user_option_row['user_option_value'] = isset($reg_option_value_row[$option_value_row[$reg_option_id]]) ? $reg_option_value_row[$option_value_row[$reg_option_id]] : $option_value_row[$reg_option_id];
            //         $flag = $User_OptionModel -> addOption($user_option_row);
            //         array_push($rs_row, $flag);
            //     }
            // }

            if (is_ok($rs_row) && $User_InfoDetail -> sql -> commit()) {
                $d = array();
                $d['user_id'] = $user_id;
                $encrypt_str = Perm::encryptUserInfo($d, $session_id);
                $arr_body = array(
                    "user_name" => $user_name,
                    "server_id" => $server_id,
                    "k" => $encrypt_str,
                    "user_id" => $user_id
                );
                $msg    = 'success';
                $status = 200;
                $data['id'] = $user_id;

            }else{
                $msg    = '创建账号失败';
                $status = 250;
                $data['id'] = '';
            }
            $this->data->addBody(-1, $data, $msg, $status);
        }



    }

}


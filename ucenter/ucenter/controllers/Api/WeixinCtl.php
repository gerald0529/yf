<?php
/**
 * Created by PhpStorm.
 * User: rd07
 * Date: 2017/4/14
 * Time: 17:36
 */
class Api_WeixinCtl extends Api_Controller
{
    /**
     * /?ctl=Api_Weixin&met=login&typ=json
     *
     * return
     *
     * 
     * {
            cmd_id: 100,
            status: 200,
            msg: "success",
            data: {
                user_id: 173946,
                user_name: "openid_ocVj7wwmY3yiXq87RwSSh7PVbxnQ11111",
                password: null,
                user_state: 1,
                action_time: 0,
                action_ip: "127.0.0.1",
                session_id: "ocVj7wwmY3yiXq87RwSSh7PVbxnQ11111",
                id: 173946,
                result: 1,
                k: "CXgDIwBlUXNTDgRvVjNRYFFrUTMFYQZqUTBSYA=="
                }
            }
     * 
     */

    public function Login()
    {
        $user_info_row = $_POST;
//        $this->data->addBody(100, $user_info_row);

        if(!$user_info_row['openid']){
            exit(json_encode(array('status'=>400)));
        }

        $User_BindConnectModel = new User_BindConnectModel();

        $bind_id     = sprintf('%s_%s', 'openid', $user_info_row['openid']);
        $connect_rows = $User_BindConnectModel->getBindConnect($bind_id);

        if ($connect_rows)
        {
            $connect_row = array_pop($connect_rows);
        }

        //已经绑定,并且用户正确
        if (isset($connect_row['user_id']) && $connect_row['user_id'])
        {
            $login_flag = true;
            $User_InfoModel = new User_InfoModel();
            $user_info_row = $User_InfoModel->getOne($connect_row['user_id']);
            $session_id =  $user_info_row['session_id'];
            $user_id = $connect_row['user_id'];
            $user = $user_info_row;
            unset($user_info_row['password']);
        }
        else
        {
            //将微信登录的新用户信息插入到用户详情表中
            $User_InfoDetailModel = new User_InfoDetailModel();
            $user_info_detail = array();
            $user_info_detail['user_name'] = $bind_id;
            $user_info_detail['user_avatar'] = $user_info_row['headimgurl'];
            $user_info_detail['user_gender'] = $user_info_row['sex'];
            $user_info_detail['user_province'] = $user_info_row['province'];
            $user_info_detail['user_city'] = $user_info_row['city'];
            $user_info_detail['user_reg_time'] = time();
            $user_info_detail['user_reg_ip'] = get_ip();
            $user_info_detail['user_lastlogin_ip'] = get_ip();
            $flag = $User_InfoDetailModel->addInfoDetail($user_info_detail);

            $bind_avator = $user_info_row['headimgurl'];
            //将微信登录的新用户信息插入到用户表中
            $User_InfoModel = new User_InfoModel();
            $user_info = array();
            $user_info['user_name'] = $bind_id;
            $user_info['user_state'] = 1;
            $user_info['action_ip'] = get_ip();
            $session_id = $user_info['session_id'] = $user_info_row['openid'];
            $user_id = $User_InfoModel->addInfo($user_info, true);

            $user_info['user_id'] = $user_id;
            $data = array();
            $data['bind_id']           = $bind_id;
            $data['bind_type']         = $User_BindConnectModel::WEIXIN;
            $data['user_id']           = $user_id;
            $data['bind_nickname']     = $user_info_row['nickname']; // 名称
            $data['bind_avator']         = $bind_avator; //
            $data['bind_gender']       = $user_info_row['sex']; // 性别 1:男  2:女
            $data['bind_openid']       = $user_info_row['openid']; // 访问
            $data['bind_token']        = '';
            $connect_flag = $User_BindConnectModel->addBindConnect($data);
            if ($connect_flag && $flag)
            {
                $user = $user_info;
            }
         }

        $data = array();
        $data['user_id']    = $user_id;
        $encrypt_str        = Perm::encryptUserInfo($data, $session_id);
        $arr_body['k'] = $encrypt_str;
        $arr_body['user_id'] = $user_id;
        $arr_body['user_name'] = $bind_id;
        $arr_body['city'] = $user_info_row['city'];
        $arr_body['country'] = $user_info_row['country'];
        $arr_body['headimgurl'] = $user_info_row['headimgurl'];
        $arr_body['language'] = $user_info_row['language'];
        $arr_body['nickname'] = $user_info_row['nickname'];
        $arr_body['privilege'] = $user_info_row['privilege'];
        $arr_body['openid'] = $user_info_row['openid'];
        $arr_body['province'] = $user_info_row['province'];
        $arr_body['sex'] = $user_info_row['sex'];
        $arr_body['unionid'] = $user_info_row['unionid'];

        $this->data->addBody(100, $arr_body);
    }
}

<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Api接口, 让webpos调用
 *
 *
 * @category   Game
 * @package    User
 * @author
 * @copyright
 * @version    1.0
 * @todo
 */
class WebPosApi_UserCtl extends WebPosApi_Controller
{

    public $userInfoModel     = null;
    public $userBaseModel     = null;
    public $userResourceModel = null;

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

        $this->userInfoModel     = new User_InfoModel();
        $this->userBaseModel     = new User_BaseModel();
        $this->userResourceModel = new User_ResourceModel();
        $this->User_GradeModel = new User_GradeModel();
        $this->userResourceModel = new User_ResourceModel();
        $this->redPacketBaseModel = new RedPacket_BaseModel();
        $this->voucherBaseModel = new Voucher_BaseModel();
    }

    /**
     *获取用户列表
     * @access public
     */
    public function getUserList()
    {
        $page = request_int('page', 1);
        $rows = request_int('rows', 10);

        $cond_row = array();
        $sort     = array();
        $name = request_string('skey');

        if ($name)
        {
            $cond_row['user_name:LIKE'] = '%' . $name . '%';
        }

        $data = $this->userInfoModel->getInfoList($cond_row, $sort, $page, $rows);
        if($data['items'])
        {
            $user_id_row = array_column($data['items'],'user_id');
            $user_resource_rows = array_column($this->userResourceModel->getResourceList(array('user_id:IN'=>$user_id_row)),'user_points','user_id');;
            foreach($data['items'] as $key=>$value)
            {
                if(in_array($value['user_id'],array_keys($user_resource_rows)))
                {
                    $data['items'][$key]['user_points'] = $user_resource_rows[$value['user_id']];
                }
            }
        }

        $this->data->addBody(-140, $data);

    }

    /*获取平台用户信息
    条件查询方式：用户会员卡号搜索、用户名搜索、真实姓名搜索*/

    public function getBuyerList()
    {
        $cond_row  = array();
        $order_row = array();
        $user_name = request_string('skey');
        if($user_name)
        {
            $cond_row['user_name'] = $user_name;
        }
        $user_card_num  = request_string('user_card_num');
        /*$user_name      = request_string('search_name');
        $type           = request_string('user_type');
        if($user_card_num)
        {
            $cond_row['user_card_num'] = $user_card_num;
        }
        if ($user_name)
        {
            $type            = 'user_name:LIKE';
            $cond_row[$type] = '%' . $user_name . '%';
        }*/
        $page = request_int('page', 1);
        $rows = request_int('rows', 10);

        $data = $this->userInfoModel->getInfoList($cond_row, $order_row, $page, $rows);
        if($data['items'])
        {
            $user_id_row = array_column($data['items'],'user_id');
            $user_resource_rows = array_column($this->userResourceModel->getResourceList(array('user_id:IN'=>$user_id_row)),'user_points','user_id');;
            foreach($data['items'] as $key=>$value)
            {
                $data['items'][$key]['user_dresser_label'] = __(User_InfoModel::$userTypeMap[$value['is_dresser_user']]);
                if(in_array($value['user_id'],array_keys($user_resource_rows)))
                {
                    $data['items'][$key]['user_points'] = $user_resource_rows[$value['user_id']];
                }
            }
        }

        $this->data->addBody(-140, $data);
    }



    /**
     * 获取修改会员信息
     *
     * @access public
     */
    public function editInfo()
    {
        $user_id              = request_int('user_id');
        $order_row['user_id'] = $user_id;

        $data = $this->userInfoModel->getUserInfo($order_row);
        if ($data)
        {
            //会员的钱
            $key                 = Yf_Registry::get('shop_api_key');
            $formvars            = array();
            $formvars['user_id'] = $user_id;

            $money_row = get_url_with_encrypt($key, sprintf('%sindex.php?ctl=Index&met=getUserResourceInfo&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);

            if ($money_row['status'] == '200')
            {
                $money = $money_row['data'];

                $data['user_cash']        = $money[$user_id]['user_money'];
                $data['user_freeze_cash'] = $money[$user_id]['user_money_frozen'];

            }
            else
            {
                $data['user_cash']        = 0;
                $data['user_freeze_cash'] = 0;
            }

            $re = $this->userResourceModel->getOne($order_row);
            $de = $this->userBaseModel->getOne($order_row);

            $data['user_points'] = $re['user_points'];
            $data['user_growth'] = $re['user_growth'];
            $data['user_delete'] = $de['user_delete'];
        }

        $this->data->addBody(-140, $data);

    }

    /**
     * 修改会员信息
     *
     * @access public
     */
    public function editUserInfo()
    {
        $user_id = request_int('user_id');
        //$user_passwd = request_string('user_passwd');
        $user_email    		= request_string('user_email');
        $user_realname 		= request_string('user_realname');
        $user_sex      		= request_int('user_sex');
        $user_qq       		= request_string('user_qq');
        $user_logo     		= request_string('user_logo', request_string('user_avatar'));
        $is_dresser_user 	= request_int('is_dresser_user');
        $user_delete   		= request_int('user_delete');

        isset($_REQUEST['user_mobile']) ? $edit_user_row['user_mobile']=request_string('user_mobile') : '';

        $edit_user_row['user_email']    	= $user_email;
        $edit_user_row['user_sex']      	= $user_sex;
        $edit_user_row['user_realname'] 	= $user_realname;
        $edit_user_row['user_qq']       	= $user_qq;
        $edit_user_row['user_logo']     	= $user_logo;
        $edit_user_row['is_dresser_user']	= $is_dresser_user;

        $edit_base_row['user_delete'] = $user_delete;

        //开启事物
        $rs_row = array();
        $this->userInfoModel->sql->startTransactionDb();

        $update_flag = $this->userBaseModel->editBase($user_id, $edit_base_row);

        check_rs($update_flag, $rs_row);

        $flag = $this->userInfoModel->editInfo($user_id, $edit_user_row);

        check_rs($flag, $rs_row);
        $flag = is_ok($rs_row);

        if ($flag !== false && $this->userInfoModel->sql->commitDb())
        {
            $status = 200;
            $msg    = __('success');
        }
        else
        {
            $this->userInfoModel->sql->rollBackDb();

            $status = 250;
            $msg    = __('failure');
        }

        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 增加会员
     *
     * @access public
     */
    public function addUserInfo()
    {
        $time          = get_date_time();
        $user_name     = request_string('user_name');
        $user_passwd   = request_string('user_passwd');
        $user_email    = request_string('user_email');
        $user_realname = request_string('user_realname');
        $user_sex      = request_int('user_sex');
        $user_qq       = request_string('user_qq');
        $user_logo     = request_string('user_logo');

        $cond_row['user_account']          = $user_name;
        $edit_user_row['user_name']        = $user_name;
        $edit_user_row['user_email']       = $user_email;
        $edit_user_row['user_sex']         = $user_sex;
        $edit_user_row['user_realname']    = $user_realname;
        $edit_user_row['user_qq']          = $user_qq;
        $edit_user_row['user_logo']        = $user_logo;
        $edit_user_row['user_regtime']     = $time;
        $edit_user_row['user_update_date'] = $time;


        $key = Yf_Registry::get('ucenter_api_key');;
        $url       = Yf_Registry::get('ucenter_api_url');
        $app_id    = Yf_Registry::get('ucenter_app_id');
        $server_id = Yf_Registry::get('server_id');
        //开通ucenter
        //本地读取远程信息
        $formvars              = array();
        $formvars['user_name'] = request_string("user_name");
        $formvars['password']  = request_string("user_passwd");
        $formvars['app_id']    = $app_id;
        $formvars['server_id'] = $server_id;

        $formvars['ctl'] = 'Api';
        $formvars['met'] = 'addUserAndBindAppServer';
        $formvars['typ'] = 'json';

        $init_rs = get_url_with_encrypt($key, $url, $formvars);
        if (200 == $init_rs['status'])
        {
            //本地读取远程信息
            $data['user_id']      = $init_rs['data']['user_id']; // 用户帐号
            $data['user_account'] = request_string("user_name"); // 用户帐号
            $data['user_delete']  = 0; // 用户状态

            $user_id = $this->UserBaseModel->addBase($data, true);//初始化用户信息

            $User_InfoModel = new User_InfoModel();
            $info_flag      = $User_InfoModel->addInfo($user_id, $edit_user_row);

            $user_resource_row                = array();
            $user_resource_row['user_id']     = $user_id;
            $user_resource_row['user_points'] = Web_ConfigModel::value("points_reg");//注册获取积分;
            $user_resource_row['user_pointssum'] = Web_ConfigModel::value("points_reg");//注册获取积分;(sum)
            $User_ResourceModel          = new User_ResourceModel();
            $res_flag                    = $User_ResourceModel->addResource($user_resource_row);
            $User_PrivacyModel           = new User_PrivacyModel();
            $user_privacy_row['user_id'] = $user_id;
            $privacy_flag                = $User_PrivacyModel->addPrivacy($user_privacy_row);
            //积分
            $user_points_row['user_id']           = $user_id;
            $user_points_row['user_name']         = request_string("user_name");
            $user_points_row['class_id']          = Points_LogModel::ONREG;
            $user_points_row['points_log_points'] = $user_resource_row['user_points'];
            $user_points_row['points_log_time']   = get_date_time();
            $user_points_row['points_log_desc']   = '会员注册';
            $user_points_row['points_log_flag']   = 'reg';
            $Points_LogModel                      = new Points_LogModel();
            $Points_LogModel->addLog($user_points_row);

            if ($user_id)
            {

                $msg    = 'success';
                $status = 200;

            }
            else
            {
                $msg    = 'failure';
                $status = 250;
            }


        }
        else
        {
            $msg    = __("该会员名已存在！");
            $status = 250;
        }


        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }



    /*
     *获取会员信息
     * 
     * @access public
     */
    public function getUserInfos(){
        $data = $this->userInfoModel->getByWhere([]);
        $grades = [];
        if ($data)
        {
            $grade_info = $this->User_GradeModel->getGradeList([]);
            foreach ($grade_info as $k => $v) 
            {
                $grades[$v['user_grade_id']] = $v['user_grade_rate'];
            }

            foreach ($data as $k => $v) 
            {
                foreach ($grades as $key => $val) 
                {
                    if ($v['user_grade'] == $key) 
                    {
                        $data[$k]['discount'] = $val;
                    } 
                    elseif ($v['user_grade'] > $key) 
                    {
                        $data[$k]['discount'] = $val;
                    }
                    
                }
                  

            }
            $msg    = 'success';
            $status = 200;

        }
        else
        {
            $msg    = 'failure';
            $status = 250;
        }

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /*
    *获取会员积分
    * 
    * @access public
    */   
    public function getUserPoints(){
        $userId = request_int('user_id');

        $data = $this->userResourceModel->getOne($userId);
        if(!$data || !$userId)
        {
            $status = 250;
        }
        else
        {
            $status = 200;
        }
        
        $this->data->addBody(-140, $data ,$msg='success' ,$status);

    }

    /*
    *获取会员红包
    * 
    * @access public
    */   
    public function getRedPacket(){
        $userId = request_int('user_id');

        $data = $this->redPacketBaseModel->getByWhere(['redpacket_owner_id'=>$userId]);
        foreach ($data as $k => $v) {
            if ($v['redpacket_state'] == 1) {
                $v['redpacket_start_date'] = date("Y.m.d",strtotime($v['redpacket_start_date']));
                $v['redpacket_end_date'] = date("Y.m.d",strtotime($v['redpacket_end_date']));
                $date[] = $v;
            }
        }


        $data = $date;
        if($data)
        {
            $status = 200;
        }
        else
        {
            $status = 250;
        }
        
        $this->data->addBody(-140, $data ,$msg='success' ,$status);

    }
    /*
    *获取会员代金券
    * 
    * @access public
    */   
    public function getVoucher(){
        $userId = request_int('user_id');

        $data = $this->voucherBaseModel->getByWhere(['voucher_owner_id'=>$userId]);
        foreach ($data as $k => $v) {
            if ($v['voucher_state'] == 1) {
                $v['voucher_start_date'] = date("Y.m.d",strtotime($v['voucher_start_date']));
                $v['voucher_end_date'] = date("Y.m.d",strtotime($v['voucher_end_date']));
                $date[] = $v;
            }
        }


        $data = $date;
        if($data)
        {
            $status = 200;
        }
        else
        {
            $status = 250;
        }
        
        $this->data->addBody(-140, $data ,$msg='success' ,$status);

    }
}

?>
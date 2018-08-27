<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_User_InfoCtl extends Yf_AppController
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

	}
	
	/**
	 *获取会员信息
	 *
	 * @access public
	 */
	public function getInfoList()
	{
		
		$page = request_int('page', 1);
		$rows = request_int('rows', 10);
		$type = request_string('user_type');
		$name = request_string('search_name');
		
		$shopBaseModel = new Shop_BaseModel();
		
		$cond_row = array();
		$sort     = array();
		
		if(request_int('shop_source')){
			$shop_list = $shopBaseModel->getByWhere(array('shop_type'=>request_int('shop_source')));
			$shop_user = array_column($shop_list,'user_id');
			$cond_row['user_id:IN'] = $shop_user;
		}
		
		if ($name)
		{
			if ($type == '1')
			{
				$cond_row['user_id'] = $name;
			}
			else
			{
				$type            = 'user_name:LIKE';
				$cond_row[$type] = '%' . $name . '%';
			}
			
		}
		
		$data = $this->userInfoModel->getInfoList($cond_row, $sort, $page, $rows);
		
		$shopBaseModel = new Shop_BaseModel();
		foreach ($data['items'] as $key => $value) {
			$shop_info = 	$shopBaseModel->getOneByWhere(array('user_id'=>$value['user_id']));
			if(!empty($shop_info)){
				$data['items'][$key]['shop_type'] = $shop_info['shop_type'];
				$data['items'][$key]['shop_status'] = $shop_info['shop_status'];
			}
		}
		
		
		$this->data->addBody(-140, $data);

	}

    /**
     * 获取单个会员信息
     *
     * @access public
     * @return json
     */
    public function getUserInfoByName(){
        $user_name = request_string('user_name');
        $cond_row['user_name'] = $user_name;
        $data = $this->userInfoModel->getByWhere($cond_row);
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
			$formvars['app_id'] = Yf_Registry::get('shop_app_id');
			
			$money_row = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Info&met=getUserResourceInfo&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);

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
	 * 远程通过Ucenter修改会员信息
	 *
	 * @access public
	 */
	public function editUserInfoByUcenter()
	{
		$user_id = request_int('user_id');
		$user_name = request_string('user_name');
		//$user_passwd = request_string('user_passwd');
		$user_email    = request_string('user_email');
		$user_realname = request_string('user_realname');
		$user_sex      = request_int('user_sex');
		$user_qq       = request_string('user_qq');
		$user_logo     = request_string('user_logo', request_string('user_avatar'));
		$user_delete   = request_int('user_delete');
		$user_birthday = request_string('user_birthday');
		$user_provinceid = request_int('user_provinceid');
		$user_cityid = request_int('user_cityid');
		$user_areaid = request_int('user_areaid');
		$user_area = request_string('user_area');


		$key = Yf_Registry::get('ucenter_api_key');;
		$url       = Yf_Registry::get('ucenter_api_url');
		$app_id    = Yf_Registry::get('ucenter_app_id');
		$server_id = Yf_Registry::get('server_id');
		//开通ucenter
		//本地读取远程信息
		$formvars              = array();
		$formvars['app_id']    = $app_id;
		$formvars['server_id'] = $server_id;

		$formvars['ctl'] = 'Api_User';
		$formvars['met'] = 'editUserInfo';
		$formvars['typ'] = 'json';

		isset($_REQUEST['user_mobile']) ? $formvars['user_mobile']=request_string('user_mobile') : '';

		$formvars['user_id']    = $user_id;
		$formvars['user_name']    = $user_name;
		$formvars['user_gender']    = $user_sex;
		$formvars['user_logo']     = $user_logo;
		$formvars['user_delete'] = $user_delete;

		$init_rs = get_url_with_encrypt($key, $url, $formvars);
		if ($init_rs['status'] == 200)
		{
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


	/**
	 * 修改会员信息
	 *
	 * @access public
	 */
	public function editUserInfo()
	{
		$user_id = request_int('user_id');
		//$user_passwd = request_string('user_passwd');
		$user_email    = request_string('user_email');
		$user_realname = request_string('user_realname');
		$user_sex      = request_int('user_sex');
		$user_qq       = request_string('user_qq');
		$user_logo     = request_string('user_logo', request_string('user_avatar'));
		$user_delete   = request_int('user_delete');
		$user_birthday = request_string('user_birthday');
		$user_provinceid = request_int('user_provinceid');
		$user_cityid = request_int('user_cityid');
		$user_areaid = request_int('user_areaid');
		$user_area = request_string('user_area');
		//$user_report = request_int('user_report');
		//$user_buy = request_int('user_buy');
		//$user_talk = request_int('user_talk');

		//$cond_row['user_passwd'] = md5($user_passwd);
		isset($_REQUEST['user_mobile']) ? $edit_user_row['user_mobile']=request_string('user_mobile') : '';

		$edit_user_row['user_email']    = $user_email;
		$edit_user_row['user_sex']      = $user_sex;
		$edit_user_row['user_realname'] = $user_realname;
		$edit_user_row['user_qq']       = $user_qq;
		$edit_user_row['user_logo']     = $user_logo;
		$edit_user_row['user_birthday']     = $user_birthday;
		$edit_user_row['user_provinceid']     = $user_provinceid;
		$edit_user_row['user_cityid']     = $user_cityid;
		$edit_user_row['user_areaid']     = $user_areaid;
		$edit_user_row['user_area']     = $user_area;
		//$edit_user_row['user_report'] = $user_report;
		//$edit_user_row['user_buy'] = $user_buy;
		//$edit_user_row['user_talk'] = $user_talk;
		$edit_base_row['user_delete'] = $user_delete;
		
		//开启事物
		$rs_row = array();
		$this->userInfoModel->sql->startTransactionDb();
		
		//if(!empty($cond_row['user_passwd'])){

		//$up= $this->userBaseModel->editBase($user_id,$cond_row);
		//check_rs($up,$rs_row);

		// }
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

	public function editUserBtStatus()
	{
		$user_id = request_int('user_id');
		$status = request_int('user_bt_status');

		$edit_row = array();
		$edit_row['user_bt_status'] = $status;
		$update_flag = $this->userInfoModel->editInfo($user_id, $edit_row);

		if ($update_flag)
		{
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

	public function getSubUser()
	{
		$sub_user_id = request_int('sub_user_id');

		$User_SubUserModel = new User_SubUserModel();
		$cond_row = array();
		$cond_row['sub_user_id'] = $sub_user_id;
		$cond_row['sub_user_active'] = User_SubUserModel::IS_ACTIVE;
		$sub_user = $User_SubUserModel->getByWhere($cond_row);
		if($sub_user)
		{
			$sub_user = current($sub_user);

			//查找用户名
			$User_BaseModel = new  User_BaseModel();
			$user_base = $User_BaseModel->getOne($sub_user['user_id']);
			$sub_user['user_account'] = $user_base['user_account'];

			$data['sub'] = $sub_user;
		}
		else
		{
			$data['sub'] = array();
		}
		$count = count($sub_user);
		$data['count'] = $count;
		$this->data->addBody(-140, $data);
	}

	public function checkSubUser()
	{
		$user_id = request_int('user_id');
		$subuser_id = request_int('sub_user_id');

		$User_SubUserModel = new User_SubUserModel();
		$cond_row['user_id'] = $user_id;
		$cond_row['sub_user_id'] = $subuser_id;
		$cond_row['sub_user_active'] = User_SubUserModel::IS_ACTIVE;
		$sub_user = $User_SubUserModel->getByWhere($cond_row);

		$this->data->addBody(-140, $sub_user);
	}
	
}

?>
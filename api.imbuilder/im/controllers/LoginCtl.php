<?php
class LoginCtl extends Yf_AppController
{
	public function index()
	{
		include $this->view->getView();
	}

	


	/**
	 * 手机获取找回密码验证码
	 *
	 * @access public
	 */
	public function findPasswdCode()
	{
		$mobile                    = request_string('mobile');

		//判断用户是否存在  $mobile
		if (true)
		{
			$data = array();

			$data['user_code'] = rand(1000, 9999);

			$config_cache = Yf_Registry::get('config_cache');

			if (!file_exists($config_cache['default']['cacheDir']))
			{
				mkdir($config_cache['default']['cacheDir']);
			}

			$Cache_Lite = new Cache_Lite_Output($config_cache['default']);

			$Cache_Lite->save($data['user_code'], $mobile);

			//发送短消息
			$contents = '您的验证码是：' . $data['user_code'] . '。请不要把验证码泄露给其他人。如非本人操作，可不用理会！';

			$result = Sms::send($mobile, $contents);

			{
				if (true)
				{
					$msg = 'success';
					$status = 200;
				}
				else
				{
					$msg = '失败';
					$status = 250;
				}

			}
		}
		else
		{
			$msg = '用户账号不存在';
			$status = 250;
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}


	public function resetPasswd()
	{
		//
		$user_code = request_string('user_code');

		$data         = array();
		$data['user'] = request_string('user_account');

		if (request_string('user_password'))
		{
			$data['password'] = md5(request_string('user_password'));


			$config_cache = Yf_Registry::get('config_cache');
			$Cache_Lite   = new Cache_Lite_Output($config_cache['default']);

			$user_code_pre = $Cache_Lite->get($data['user']);


			if ($user_code == $user_code_pre)
			{
				$User_BaseModel = new User_BaseModel();

				//检测登录状态
				$user_id_row = $User_BaseModel->getInfoByName($data['user']);

				if ($user_id_row)
				{
					//重置密码
					$user_id          = $user_id_row['user_id'];
					$reset_passwd_row = array();

					$reset_passwd_row['password'] = $data['password'];

					fb($user_id);
					fb($reset_passwd_row);
					$flag = $User_BaseModel->editInfo($user_id, $reset_passwd_row);

					if ($flag)
					{
						$msg    = '重置密码成功';
						$status = 200;

						$Cache_Lite->remove($data['user']);
					}
					else
					{
						$msg    = '重置密码失败';
						$status = 250;
					}
				}
				else
				{
					$msg    = '用户不存在';
					$status = 250;
				}
			}
			else
			{
				$msg = '验证码错误';
				$status = 250;
			}

		}
		else
		{
			$msg    = '密码不能为空';
			$status = 250;
		}


		unset($data['password']);

		$this->data->addBody(-140, $data, $msg, $status);
	}


	public function register_shanghai()
	{


		if(isset($_GET['us']) && isset($_GET['ks']))
        {
            $userid = intval($_GET['us']);
            $k = $_GET['ks']; 

            //本地读取远程信息
            $key = Yf_Registry::get('imbuilder_key');
            $url    = Yf_Registry::get('imbuilder_url');
            $app_id = Yf_Registry::get('imbuilder_app_id');

            $formvars              = array();
            $formvars['user_id'] = $userid;
            $formvars['u'] = $userid;
            $formvars['k']  = $k;
            $formvars['app_id']    = $app_id;

            $formvars['ctl'] = 'Api';
            $formvars['met'] = 'checkLogin';
            $formvars['typ'] = 'json';
            $init_rs         = get_url_with_encrypt($key, $url, $formvars);

            $status = $init_rs['status'];

            if($status == 200)
            {
                //进行注册
                $User_AppServerModel = new User_AppServerModel();

				$user_app_server_row = array();
				$user_app_server_row['user_name'] = $user_name;
				$user_app_server_row['app_id'] = $app_id;
				$user_app_server_row['server_id'] = $server_id;
				$user_app_server_row['active_time'] = time();

				$flag = $User_AppServerModel->addAppServer($user_app_server_row);
                location_to("index.php?ctl=Squale");
                die;
            }

        }
	}

	public function register()
	{
		$app_id = request_int('app_id');

		$user_name = request_string('user_account', null);
		$password  = request_string('user_password', null);


		$user_code = request_string('user_code');

		if (!$user_name)
		{
			$this->data->setError('user_account 不存在');
			return false;
		}

		if (null === $password)
		{
			$this->data->setError('user_password 不能为空');
			return false;
		}

		$config_cache = Yf_Registry::get('config_cache');
		$Cache_Lite = new Cache_Lite_Output($config_cache['default']);

		$user_code_pre = $Cache_Lite->get($user_name);

		if ($user_code == $user_code_pre)
		{
			$rs_row = array();

			//用户是否存在
			$User_BaseModel = new User_BaseModel();
			$User_InfoDetail = new User_InfoDetail();

			$user_rows = $User_BaseModel->getInfoByName($user_name);

			if ($user_rows)
			{
				$this->data->setError('用户已经存在,请更换用户名!');
				return false;
			}
			else
			{

				$User_BaseModel->sql->startTransaction();

				$Db  = Yf_Db::get('ucenter');
				$seq_name = 'user_id';
				$user_id = $Db->nextId($seq_name);

				//$User_BaseModel->check_input($user_name, $password, $user_mobile);

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

				$flag = $User_BaseModel->addInfo($arr_field_user_info);
				array_push($rs_row, $flag);

				$arr_field_user_info_detail = array();
				$arr_field_user_info_detail['user_name'] = $user_name;
				$arr_field_user_info_detail['user_mobile'] = $user_name;
				$arr_field_user_info_detail['user_reg_time'] = $now_time;
				$arr_field_user_info_detail['user_count_login'] = 1;
				$arr_field_user_info_detail['user_lastlogin_time'] = $now_time;
				$arr_field_user_info_detail['user_lastlogin_ip'] = $ip;
				$arr_field_user_info_detail['user_reg_ip'] = $ip;

				$flag = $User_InfoDetail->addInfoDetail($arr_field_user_info_detail);
				array_push($rs_row, $flag);
			}




			$app_id = isset($_REQUEST['app_id']) ?  $_REQUEST['app_id'] : 0;
			$Base_App = new Base_AppModel();

			if($app_id && !($base_app_rows = $Base_App->getApp($app_id)))
			{
				/*
				$base_app_row = array_pop($base_app_rows);

				$arr_field_user_app = array();
				$arr_field_user_app['user_name'] = $user_name;
				$arr_field_user_app['app_id'] = $app_id;
				$arr_field_user_app['active_time'] = time();

				$User_App = new User_AppModel();

				//是否存在
				$user_app_row = $User_App->getAppByNameAndAppId($user_name, $app_id);

				if ($user_app_row)
				{
					// update app_quantity
					$app_quantity_row = array();
					$app_quantity_row['app_quantity'] = $user_app_row['app_quantity'] + 1;
					$flag = $User_App->editApp($user_name, $app_quantity_row);
					array_push($rs_row, $flag);
				}
				else
				{

					$flag = $User_App->addApp($arr_field_user_app);
					array_push($rs_row, $flag);

				}

				$User_AppServerModel = new User_AppServerModel();

				$user_app_server_row = array();
				$user_app_server_row['user_name'] = $user_name;
				$user_app_server_row['app_id'] = $app_id;
				$user_app_server_row['server_id'] = $server_id;
				$user_app_server_row['active_time'] = time();

				$flag = $User_AppServerModel->addAppServer($user_app_server_row);
				*/
			}
			else
			{
			}

			if (is_ok($rs_row) && $User_InfoDetail->sql->commit())
			{
				$d = array();
				$d['user_id'] = $user_id;
				$encrypt_str = Perm::encryptUserInfo($d, $session_id);

				$arr_body = array("user_name"=>$user_name, "server_id"=>$server_id, 'k'=>$encrypt_str);
				$this->data->addBody(100, $arr_body);


			}
			else
			{
				$Base_App->sql->rollBack();
				$this->data->setError('创建用户信息失败');
			}
		}
		else
		{
			$msg = '验证码错误';
			$status = 250;
		}
	}

	public function login()
	{
		$user_account = strtolower($_REQUEST['user_account']);
		$User_InfoModel = new User_InfoModel();

		//判断是否是手机号
		if(Yf_Utils_String::isMobile($user_account))
		{
			$mobile = $user_account;
			//根据用户手机号获取用户信息
			$user_info = $User_InfoModel->getByWhere(array('user_mobile'=>$mobile));
			$user_info = current($user_info);
			$user_account = $user_info['user_name'];
		}

		$password = $_REQUEST['user_password'];
		$userBaseModel = new User_BaseModel();
		$user_id_row = $userBaseModel->getUserIdByAccount($user_account);
		fb($user_id_row);

		//从ucenter中获取用户信息
		$key = Yf_Registry::get('ucenter_api_key');
		$url = Yf_Registry::get('ucenter_api_url');
		$app_id = Yf_Registry::get('ucenter_app_id');

		$formvars = array();
		$formvars['app_id'] = $app_id;
		$formvars['user_id'] = current($user_id_row);

		$formvars['ctl'] = 'Api_User';
		$formvars['met'] = 'getUserInfo';
		$formvars['typ'] = 'json';

		$init_rs = get_url_with_encrypt($key, $url,$formvars);

		if($init_rs['status'] == 200)
		{
			$user_password = $init_rs['data']['password'];
		}

		$server_id = 10001;
		//初始化用户信息,插入数据
		if (!$user_id_row)
		{
			$user_row = array();
			$user_row['user_account'] = $user_account;
			$user_row['user_password'] = $password;
			//$user_row['user_mobile'] = $user_account;
			$user_row['server_id'] = $server_id;


			$user_id = $userBaseModel->addUser($user_row, true);
			$user_id_row = $userBaseModel->getUserIdByAccount($user_account);
		}

		if ($user_id_row)
		{
			$user_rows = $userBaseModel->getUser($user_id_row);
			$user_row  = array_pop($user_rows);

			if($user_password != md5($password))
			{
				$this->data->setError('输入密码有误');
				return;
			}

			//判断状态是否开启
			if ($user_row['user_delete'] == 1)
			{
				$this->data->setError('用户尚未启用');
				return;
			}

			unset($user_row['user_password']);
			fb($user_row);
		}
		else
		{
		}


		if ($user_id_row)
		{
			$data              = array();
			$data['user_id']   = $user_row['user_id'];
			$data['server_id'] = $user_row['server_id'];
			srand((double)microtime() * 1000000);
			$user_key = md5(rand(0, 32000));
            $user_key = 'aaaabbbb';
			$userBaseModel->editSingleField($user_row['user_id'], 'user_key', $user_key, $user_row['user_key']);
			Yf_Hash::setKey($user_key);
			$encrypt_str        = Perm::encryptUserInfo($data);

			$user_row['k'] = $encrypt_str;
			//location_to(Yf_Registry::get('base_url'));
		}
		else
		{
			//location_go_back('输入密码有误');
			$this->data->setError('输入密码有误');
			return;
		}

		//权限设置
		$user_row['user_name'] = $user_row['user_account'];
		fb($user_row);

		$this->data->addBody(-140, $user_row);

	}


	/**
	 * 用户登录
	 *
	 * @access public
	 */
	public function login1()
	{
		$user_account = $_REQUEST['user_account'];

		//本地读取远程信息
		$key = Yf_Registry::get('ucenter_api_key');;
		$url = Yf_Registry::get('ucenter_api_url');
		$app_id = Yf_Registry::get('ucenter_app_id');

		$formvars = array();
		$formvars['user_name'] = $_REQUEST['user_account'];
		$formvars['password'] = $_REQUEST['user_password'];
		$formvars['app_id'] = $app_id;

		$formvars['ctl'] = 'Api';
		$formvars['met'] = 'login';
		$formvars['typ'] = 'json';
		$init_rs = get_url_with_encrypt($key, $url, $formvars);

		fb($init_rs);
		if (200 == $init_rs['status'])
		{
			//读取服务列表
			$formvars = array();
			$formvars['user_name'] = $_REQUEST['user_account'];
			$formvars['app_id'] = $app_id;

			$formvars['ctl'] = 'Api';
			$formvars['met'] = 'getUserAppServer';
			$formvars['typ'] = 'json';
			$server_rows_rs = get_url_with_encrypt($key, $url, $formvars);

			fb($server_rows_rs);
			if (200 == $server_rows_rs['status'])
			{
				$server_rows = $server_rows_rs['data'];

				$server_row = array_pop($server_rows);
				$server_id = $server_row['server_id'];


				if (!$server_id)
				{
					location_go_back('尚未开通服务');
				}
			}
			else
			{
				location_go_back('获取服务信息有误');
			}
		}
		else
		{
			location_go_back('登录信息有误');
		}


		$config = Yf_Registry::get('db_cfg');

		$db_row = include INI_PATH . '/db_' . $server_id . '.ini.php';

		fb($db_row);
		//设置本地数据库信息, 通过server_id本地文件读取PHP文件,
		$config['db_cfg_rows'] = array(
			'master' => array(
				'ucenter' => array(
					$db_row
				)
			)
		);

		Yf_Registry::set('db_cfg', $config);


		$userBaseModel = new User_BaseModel();

		//本地数据校验登录
		$user_id_row = $userBaseModel->getUserIdByAccount($user_account);

		if ($user_id_row)
		{
			$user_rows = $userBaseModel->getUser($user_id_row);
			$user_row = array_pop($user_rows);
			//判断状态是否开启
			if($user_row['user_delete'] == 1){
				return location_go_back('该账户未启用，请启用后登录！');
			}
			fb($user_row);
		}

		//if ($user_id_row && ($user_row['user_password'] == md5($_REQUEST['user_password'])))
		if ($user_id_row )
		{
			$data = array();
			$data['user_id'] = $user_row['user_id'];
			$data['server_id'] = $user_row['server_id'];
			srand((double)microtime() *1000000);
			$user_key = md5(rand(0, 32000));
            $user_key = 'aaaabbbb';
			$userBaseModel->editSingleField($user_row['user_id'],'user_key',$user_key,$user_row['user_key']);
			Yf_Hash::setKey($user_key);
			Perm::encryptUserInfo($data);

			location_to(Yf_Registry::get('base_url'));
		}
		else
		{

			location_go_back('输入密码有误');
		}

		//权限设置

	}

	/*
	 * 检测登录数据是否正确
	 *
	 *
	 */
	public function check()
	{
		//本地读取远程信息

		$key = Yf_Registry::get('ucenter_api_key');;
		$url = Yf_Registry::get('ucenter_api_url');
		$app_id = Yf_Registry::get('ucenter_app_id');

		$formvars = array();
		$formvars['user_id'] = $_REQUEST['user_id'];
		$formvars['u']  = $_REQUEST['user_id'];
		$formvars['k'] = $_REQUEST['k'];
		$formvars['app_id'] = $app_id;

		$formvars['ctl'] = 'Login';
		$formvars['met'] = 'checklogin';
		$formvars['typ'] = 'json';
		$init_rs = get_url_with_encrypt($key, $url,$formvars);
		//var_dump($init_rs);

		fb($init_rs);
		$this->data->addBody(100, $init_rs);
	}

	/**
	 * App登录专用接口
	 *2017/4/20 hp
	 * return
	 *
	 * cmd_id:-140
		data:
			id:"173961"
			k:"ASELeFY2DS8NDlVsBmRVPgU/BDZTZVY+Dz4CMVUjBXwFNVEjCHtVYA0jAg0DbFVnBzEJPA=="
			rights_group_id:"0"
			server_id:"0"
			user_account:"openid_osZqrwsDuJNaYkQsR_cy4-NKGzAU1115ceshi"
			user_admin:"0"
			user_delete:"0"
			user_email:""
			user_id:"173961"
			user_key:"aaaabbbb"
			user_name:"openid_osZqrwsDuJNaYkQsR_cy4-NKGzAU1115ceshi"
			user_password:""
			user_rights_ids:""
		msg:"success"
		status:200
	 */
	public function checkToStatus()
	{
		$user_info_row = $_POST;
		$user_id = $user_info_row['user_id'];
		$userBaseModel = new User_BaseModel();
		$user_row = $userBaseModel->getOne($user_id);
		$data = array();
		if($user_row)
		{
			$ks = $user_info_row['k'];
			$us = $user_id;
			$arr['ks'] = $ks;
			$arr['us'] = $us;
			$data = $this->checkLogin($arr);
		}
		else
		{
			$user_base['user_id']      = $user_id; // 用户id
			$user_base['user_account'] = $user_info_row['user_name']; // 用户帐号

			$user_base['user_delete'] = 0; // 用户状态
			$user_id_flag             = $userBaseModel->addUser($user_base);
			//插入info表
			$now_time = time();
			$ip = get_ip();
			$user_info = array();
			$user_info['user_name'] = $user_info_row['user_name'];
			$user_info['user_avatar'] = $user_info_row['headimgurl'];
			$user_info['user_gender'] = $user_info_row['sex'];
			$user_info['user_province'] = $user_info_row['province'];
			$user_info['user_city'] = $user_info_row['city'];
			$user_info['user_reg_time'] = $now_time;
			$user_info['user_count_login'] = 1;
			$user_info['user_lastlogin_time'] = $now_time;
			$user_info['user_lastlogin_ip'] = $ip;
			$user_info['user_reg_ip'] = $ip;
			$user_info['user_idcard'] = $ip;

			$userInfoModel = new User_InfoModel();
			$flag = $userInfoModel->addInfo($user_info);
			if($flag && $user_id_flag)
			{
				$ks = $user_info_row['k'];
				$us = $user_id;
				$arr['ks'] = $ks;
				$arr['us'] = $us;
				$data = $this->checkLogin($arr);
			}
		}
		if(empty($data))
		{
			$msg = 'failure';
			$status = 250;
		}
		else
		{
			$msg = 'success';
			$status = 200;
		}
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * @param $data app微信UCenter登陆后返回的数据
	 */
	public function checkLogin($data)
	{
		//本地读取远程信息
		$key = Yf_Registry::get('ucenter_api_key');
		$url = Yf_Registry::get('ucenter_api_url');
		$app_id = Yf_Registry::get('ucenter_app_id');

		$formvars = array();

		$formvars['user_id'] = $data['us'];
		$formvars['u']  = $data['us'];
		$formvars['k'] = $data['ks'];
		$formvars['app_id'] = $app_id;

		$formvars['ctl'] = 'Login';
		$formvars['met'] = 'checkLogin';
		$formvars['typ'] = 'json';
		$init_rs = get_url_with_encrypt($key, $url,$formvars);
		fb($init_rs);

		if($init_rs['status'] == 200)
		{
			//登陆
			$user_row = $init_rs['data'];
			$user_id = $user_row['user_id'];
			$user_name = $user_row['user_name'];
			$user_account = $user_row['user_name'];

			$userBaseModel = new User_BaseModel();
			$user_id_row = $userBaseModel->getUserIdByAccount($user_account);
			$user_row = $userBaseModel->getOne($user_id);

			$server_id = 10001;
			//初始化用户信息,插入数据
			if ($user_row)
			{
				//判断状态是否开启
				if ($user_row['user_delete'] == 1)
				{
					$msg = _('该账户未启用，请启用后登录！');
					if ('e' == $this->typ)
					{
						location_go_back(_('初始化用户出错!'));
					}
					else
					{
						return $this->data->setError($msg, array());
					}
				}
			}
			else
			{
				//添加用户
				//$data['user_id']       = $user_row['user_id']; // 用户id
				//$data['user_account']  = $user_row['user_name']; // 用户帐号

				$data['user_id']      = $init_rs['data']['user_id']; // 用户id
				$data['user_account'] = $init_rs['data']['user_name']; // 用户帐号

				$data['user_delete'] = 0; // 用户状态
				$user_id_flag             = $userBaseModel->addUser($data);

				//判断状态是否开启
				fb($init_rs);
				if (!$user_id_flag)
				{
					$msg = _('初始化用户出错!');
					if ('e' == $this->typ)
					{
						location_go_back(_('初始化用户出错!'));
					}
					else
					{
						return $this->data->setError($msg, array());
					}
				}
				else
				{
					//插入info表
					$now_time = time();
					$ip = get_ip();
					$user_info = array();
					$user_info['user_name'] = $init_rs['data']['user_name'];
					$user_info['nickname'] = $init_rs['data']['nickname'];
					$user_info['user_group'] = $init_rs['data']['user_group'];
					$user_info['user_site_id'] = $init_rs['data']['user_site_id'];
					$user_info['user_site_domain'] = $init_rs['data']['user_site_domain'];
					$user_info['user_question'] = $init_rs['data']['user_question'];
					$user_info['user_answer'] = $init_rs['data']['user_answer'];
					$user_info['user_avatar'] = $init_rs['data']['user_avatar'];
					$user_info['user_avatar_thumb'] = $init_rs['data']['user_avatar_thumb'];
					$user_info['user_gender'] = $init_rs['data']['user_gender'];
					$user_info['user_truename'] = $init_rs['data']['user_truename'];
					$user_info['user_tel'] = $init_rs['data']['user_tel'];
					$user_info['user_birth'] = $init_rs['data']['user_birth'];
					$user_info['user_email'] = $init_rs['data']['user_email'];
					$user_info['user_qq'] = $init_rs['data']['user_qq'];
					$user_info['user_msn'] = $init_rs['data']['user_msn'];
					$user_info['user_province'] = $init_rs['data']['user_province'];
					$user_info['user_city'] = $init_rs['data']['user_city'];
					$user_info['user_intro'] = $init_rs['data']['user_intro'];
					$user_info['user_sign'] = $init_rs['data']['user_sign'];
					$user_info['user_reg_time'] = $now_time;
					$user_info['user_count_login'] = 1;
					$user_info['user_lastlogin_time'] = $now_time;
					$user_info['user_lastlogin_ip'] = $ip;
					$user_info['user_reg_ip'] = $ip;
					$user_info['user_idcard'] = $ip;
					$user_info['user_mobile'] = $init_rs['data']['user_mobile'];
					$userInfoModel = new User_InfoModel();
					$userInfoModel->addInfo($user_info);
				}

				$user_row = $data;
			}

			if ($user_row)
			{
				$data              = array();
				$data['user_id']   = $user_row['user_id'];
				$data['server_id'] = $user_row['server_id'];
				srand((double)microtime() * 1000000);
				$user_key = md5(rand(0, 32000));
				$user_key = 'aaaabbbb';
				$userBaseModel->editSingleField($user_row['user_id'], 'user_key', $user_key, $user_row['user_key']);
				Yf_Hash::setKey($user_key);
				$encrypt_str        = Perm::encryptUserInfo($data);
				$user_row['k'] = $encrypt_str;
			}

			//权限设置
			$user_row['user_name'] = $user_account;
			fb($user_row);

			return $user_row;
		}
		else
		{
			return $data=array();
		}
	}

	public function checkToLogin()
	{
		$redirect = request_string('redirect');
		
		//本地读取远程信息
		$key = Yf_Registry::get('ucenter_api_key');
		$url = Yf_Registry::get('ucenter_api_url');
		$app_id = Yf_Registry::get('ucenter_app_id');

		$formvars = array();

		$formvars['user_id'] = $_REQUEST['us'];
		$formvars['u']  = $_REQUEST['us'];
		$formvars['k'] = $_REQUEST['ks'];
		$formvars['app_id'] = $app_id;

		$formvars['ctl'] = 'Login';
		$formvars['met'] = 'checkLogin';
		$formvars['typ'] = 'json';
		$init_rs = get_url_with_encrypt($key, $url,$formvars);
		fb($init_rs);

		if($init_rs['status'] == 200)
		{
			//登陆
			$user_row = $init_rs['data'];
			$user_id = $user_row['user_id'];
			$user_name = $user_row['user_name'];
			$user_account = $user_row['user_name'];

			$userBaseModel = new User_BaseModel();
			$user_id_row = $userBaseModel->getUserIdByAccount($user_account);
			$user_row = $userBaseModel->getOne($user_id);
			
			$server_id = 10001;
			//初始化用户信息,插入数据
			if ($user_row)
			{
				//判断状态是否开启
				if ($user_row['user_delete'] == 1)
				{
					$msg = _('该账户未启用，请启用后登录！');
					if ('e' == $this->typ)
					{
						location_go_back(_('初始化用户出错!'));
					}
					else
					{
						return $this->data->setError($msg, array());
					}
				}
			}
			else
			{
				//添加用户
				//$data['user_id']       = $user_row['user_id']; // 用户id
				//$data['user_account']  = $user_row['user_name']; // 用户帐号

				$data['user_id']      = $init_rs['data']['user_id']; // 用户id
				$data['user_account'] = $init_rs['data']['user_name']; // 用户帐号

				$data['user_delete'] = 0; // 用户状态
				$user_id_flag             = $userBaseModel->addUser($data);

				//判断状态是否开启
			   fb($init_rs);
				if (!$user_id_flag)
				{
					$msg = _('初始化用户出错!');
					if ('e' == $this->typ)
					{
						location_go_back(_('初始化用户出错!'));
					}
					else
					{
						return $this->data->setError($msg, array());
					}
				}
				else
				{
					//插入info表
					$now_time = time();
					$ip = get_ip();
					$user_info = array();
					$user_info['user_name'] = $init_rs['data']['user_name'];
					$user_info['nickname'] = $init_rs['data']['nickname'];
					$user_info['user_group'] = $init_rs['data']['user_group'];
					$user_info['user_site_id'] = $init_rs['data']['user_site_id'];
					$user_info['user_site_domain'] = $init_rs['data']['user_site_domain'];
					$user_info['user_question'] = $init_rs['data']['user_question'];
					$user_info['user_answer'] = $init_rs['data']['user_answer'];
					$user_info['user_avatar'] = $init_rs['data']['user_avatar'];
					$user_info['user_avatar_thumb'] = $init_rs['data']['user_avatar_thumb'];
					$user_info['user_gender'] = $init_rs['data']['user_gender'];
					$user_info['user_truename'] = $init_rs['data']['user_truename'];
					$user_info['user_tel'] = $init_rs['data']['user_tel'];
					$user_info['user_birth'] = $init_rs['data']['user_birth'];
					$user_info['user_email'] = $init_rs['data']['user_email'];
					$user_info['user_qq'] = $init_rs['data']['user_qq'];
					$user_info['user_msn'] = $init_rs['data']['user_msn'];
					$user_info['user_province'] = $init_rs['data']['user_province'];
					$user_info['user_city'] = $init_rs['data']['user_city'];
					$user_info['user_intro'] = $init_rs['data']['user_intro'];
					$user_info['user_sign'] = $init_rs['data']['user_sign'];
					$user_info['user_reg_time'] = $now_time;
					$user_info['user_count_login'] = 1;
					$user_info['user_lastlogin_time'] = $now_time;
					$user_info['user_lastlogin_ip'] = $ip;
					$user_info['user_reg_ip'] = $ip;
					$user_info['user_idcard'] = $ip;
					$user_info['user_mobile'] = $init_rs['data']['user_mobile'];
					$userInfoModel = new User_InfoModel();
					$userInfoModel->addInfo($user_info);
				}

				$user_row = $data;
			}

			if ($user_row)
			{
				$data              = array();
				$data['user_id']   = $user_row['user_id'];
				$data['server_id'] = $user_row['server_id'];
//				$data['user_account'] = $user_row['user_name'];
				srand((double)microtime() * 1000000);
				$user_key = md5(rand(0, 32000));
				$userBaseModel->editUserField($user_row['user_id'], 'user_key', $user_key, $user_row['user_key']);
				Yf_Hash::setKey($user_key);
				$encrypt_str        = Perm::encryptUserInfo($data);
				$user_row['k'] = $encrypt_str;
			}

			//权限设置
			$user_row['user_name'] = $user_account;

			if('e' == $this->typ)
			{
				if($redirect)
				{
					location_to(urldecode($redirect));
				}
			}
			else
			{
				$this->data->addBody(-140, $user_row);
				if ($jsonp_callback = request_string('jsonp_callback'))
				{
					exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
				}
			}
		}
		else
		{
			$this->data->setError('登陆失败');
		}
	}
	/*
	 * 用户退出
	 *
	 *
	 */
    public function loginout()
    {
        if ($_REQUEST['met'] == 'loginout')
        {
            if(isset($_COOKIE['key']) || isset($_COOKIE['id']))
            {
                echo "<script>parent.location.href='index.php';</script>";
                setcookie("key", null, time()-3600*24*365);
                setcookie("id", null, time()-3600*24*365);

            }
        }
    }

    /**
     * 客户端验证码
     */
    public function getAppVerifyCode(){
        $token = $_REQUEST['token'];
        $user_base_model = new User_BaseModel();
        $result = $user_base_model->getUcenterData('Login', 'getAppVerifyCode', array('app_token'=>$token));
        if($result){
            return $this->data->addBody(-140, array('verify_code'=>$result['data']['verify_code']), $result['msg'], $result['status']);
        }else{
            return $this->data->addBody(-140, array('verify_code'=>''), __('服务繁忙'), 250);
        }
    }
    
    /**
     *  客户端验证短信内容
     */
    public function appRegCode(){
        $token = $_REQUEST['token'];
        $user_base_model = new User_BaseModel();
        $result = $user_base_model->getUcenterData('Login', 'appRegCode', array('app_token'=>$token));
        if($result){
            return $this->data->addBody(-140, array('verify_code'=>$result['data']['user_code']), $result['msg'], $result['status']);
        }else{
            return $this->data->addBody(-140, array('verify_code'=>''), __('服务繁忙'), 250);
        }
    }
    
    /**
     *  客户端验证--找回密码
     */
    public function appPhoneCode(){
        $token = $_REQUEST['token'];
        $user_base_model = new User_BaseModel();
        $result = $user_base_model->getUcenterData('Login', 'appPhoneCode', array('app_token'=>$token));
        if($result){
            return $this->data->addBody(-140, array('verify_code'=>$result['data']['user_code']), $result['msg'], $result['status']);
        }else{
            return $this->data->addBody(-140, array('verify_code'=>''), __('服务繁忙'), 250);
        }
    }
    
    /**
     * 解密数据
     * @param type $value
     * @return type
     */
    private function ecryptDecode($value){
        $ECryptModel = new ECrypt();
        $data = $ECryptModel->decode($value);
        return $data;
    }


    /**
     *  缓存验证码
     * @param type $key
     * @param type $value
     * @param type $group
     * @return type
     */
    private function saveCodeCache($key,$value,$group='default'){
        $config_cache = Yf_Registry::get('config_cache');
        if (!file_exists($config_cache[$group]['cacheDir'])){
            mkdir($config_cache[$group]['cacheDir'],0755);
        }
        $Cache_Lite = new Cache_Lite_Output($config_cache[$group]);
        $result = $Cache_Lite->save($value, $key);
        return $result;
    }
}
?>

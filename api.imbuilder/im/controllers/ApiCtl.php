<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Api接口, 让App等调用
 *
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class ApiCtl extends Yf_AppController
{
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


		/*
		$base_app_row = array_pop($base_app_rows);
		$key = $base_app_row['app_key'];

		if (!check_url_with_encrypt($key, $_POST))
		{
			$this->data->setError('协议数据有误');

			$d = $this->data->getDataRows();

			$protocol_data = Yf_Data::encodeProtocolData($d);
			echo $protocol_data;

			exit();
		}
		*/
	}

	/**
	 * 用户登录
	 *
	 * @access public
	 * http://localhost/imbuilder/index.php?ctl=Api&met=login&user_account=admin&user_password=111111
	 */
	public  function loginto()
	{
		$user_account = $_REQUEST['user_account'];
		$user_password = $_REQUEST['user_password'];

		//本地读取远程信息
		$key = Yf_Registry::get('ucenter_api_key');;
		$url    = Yf_Registry::get('ucenter_api_url');
		$app_id = Yf_Registry::get('ucenter_app_id');

		$callback = Yf_Registry::get('im_api_url').'?ctl=Api&met=Login&user_account='.$user_account.'&user_password='.$user_password.'&typ=json';
		$url = Yf_Registry::get('ucenter_api_url').'?ctl=Api&met=login&typ=json&user_account='.$user_account.'&user_password='.$user_password.'&callback='.urlencode($callback);

		header("Location:".$url);
	}
	public function login()
	{
		$user_account = $_REQUEST['user_account'];

		//本地读取远程信息
		$key = Yf_Registry::get('ucenter_api_key');;
		$url    = Yf_Registry::get('ucenter_api_url');
		$app_id = Yf_Registry::get('ucenter_app_id');

		$formvars              = array();
		$formvars['user_account'] = $_REQUEST['user_account'];
		$formvars['user_password']  = $_REQUEST['user_password'];
		$formvars['app_id']    = $app_id;

		$formvars['ctl'] = 'Api';
		$formvars['met'] = 'login';
		$formvars['typ'] = 'json';
		
		$init_rs         = get_url_with_encrypt($key, $url, $formvars);

		fb($init_rs);
		 
		if (200 == $init_rs['status'])
		{
			$user_account = $init_rs['data']['user_name'];
		}
		else
		{
			//location_go_back('登录信息有误');
			$msg = $init_rs['msg'];
			$this->data->setError($msg);
			return;
		}


		 
		 

		$userBaseModel = new User_BaseModel();

		$userInfoModel = new User_InfoModel();
//		$User_LoginModel = new User_LoginModel();


		//本地数据校验登录
		$user_id_row = $userBaseModel->getUserIdByAccount($user_account);

		fb($user_id_row);


		//初始化用户信息,插入数据
		if (!$user_id_row)
		{
			$user_row = array();
			$user_row['user_id'] = $init_rs['data']['user_id'];
			$user_row['user_account'] = $init_rs['data']['user_name'];
			$user_row['user_password'] = md5($formvars['user_password']);
			//$user_row['user_mobile'] = $user_account;
			$user_row['server_id'] = $server_id;


			$user_id = $userBaseModel->addUser($user_row, true);
			$user_id_row = $userBaseModel->getUserIdByAccount($user_account);


			//插入info表
			$now_time = time();
			$ip = get_ip();
			$user_info = array();
			$user_info['user_name'] = $init_rs['data']['user_name'];
			$user_info['user_mobile'] = $init_rs['data']['user_mobile'];
			$user_info['user_reg_time'] = $now_time;
			$user_info['user_count_login'] = 1;
			$user_info['user_lastlogin_time'] = $now_time;
			$user_info['user_lastlogin_ip'] = $ip;
			$user_info['user_reg_ip'] = $ip;

			$userInfoModel->addInfo($user_info);

//			//插入login表
//			$user_login['user_id'] = $init_rs['data']['user_id'];
//			$user_login['user_login_times'] = 1;
//			$user_login['user_last_login_time'] = time();
//			$user_login['user_active_time'] = time();
//			$user_login['user_status'] = 1;
//			$User_LoginModel->addLogin($user_login);
		}

		if ($user_id_row)
		{
			$user_rows = $userBaseModel->getUser($user_id_row);
			$user_row  = array_pop($user_rows);

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

		//if ($user_id_row && ($user_row['user_password'] == md5($_REQUEST['user_password'])))
		if ($user_id_row)
		{

			/**
			 * 如果ucenter返回的user_id与本地不同，需要同步
			 * sun
			 * @var [type]
			 */
			$ucenter_id =  $init_rs['data']['user_id'];
	 
			if( $ucenter_id  &&  $user_row['user_id'] && ( $ucenter_id !=  $user_row['user_id'] ) ){ 
				$userBaseModel->editUser($user_row['user_id'] , ['user_id' => $ucenter_id ] );   
				Yf_Log::log("ucenter_return_id:".$ucenter_id." im_id:".$user_row['user_id'], Yf_Log::INFO, 'ucenter_id_notsameas_im');
 			}  



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

//			//修改user_login表状态
//			$user_login_info = current($User_LoginModel->getLogin($init_rs['data']['user_id']));
//			if($user_login_info)
//			{
//				$edit_row['user_login_times'] = $user_login_info['user_login_times'] + 1;
//				$edit_row['user_last_login_time'] = time();
//				if($user_login_info['user_status'] != 1)
//				{
//					$edit_row['user_status'] = 1;
//				}
//				$User_LoginModel->editLogin($init_rs['data']['user_id'], $edit_row);
//			}
//			else
//			{
//				//插入login表
//				$user_login['user_id'] = $init_rs['data']['user_id'];
//				$user_login['user_login_times'] = 1;
//				$user_login['user_last_login_time'] = time();
//				$user_login['user_active_time'] = time();
//				$user_login['user_status'] = 1;
//				$User_LoginModel->addLogin($user_login);
//			}

		}
		else
		{
			//location_go_back('输入密码有误');
			$this->data->setError('输入密码有误');
			return;
		}

		//权限设置
		$User_GroupRelModel = new User_GroupRelModel();
		$user_row['user_name'] = $user_row['user_account'];
		$user_row['url'] = Yf_Registry::get('ucenter_api_url').'?ctl=Api&met=login&typ=json';
		//如果登录成功，取出用户所有群组信息
		$user_row['user_group_disturb'] = array_values($User_GroupRelModel->getByWhere(array('user_id'=>$user_row['user_id'], 'group_is_disturb'=>User_GroupRelModel::GROUP_DISTURB)));
		fb($user_row);

		$this->data->addBody(-140, $user_row);
	}


	public function verify()
	{
		if (Perm::checkUserPerm())
		{
			$msg = '数据正确';
			$status = 200;
			$data = Perm::$row;
		}
		else
		{
			$msg = '权限错误';
			$status = 250;
			$data = array();
		}

		$this->data->addBody(100, $data, $msg, $status);
	}

	public function checkLogin()
	{
		$user_name  = strtolower($_REQUEST['user_name']);
		$session_id = $_REQUEST['session_id'];

		if (!$user_name || !$session_id)
		{
			$this->data->setError('参数错误');
		}

		$name_hash      = Yf_Hash::hashNum($user_name, 2);

		$User_BaseModel = new User_BaseModel();
		$user_id_row = $User_BaseModel->getUserIdByName($user_name);

		if ($user_id_row)
		{
			$user_info_rows = $User_BaseModel->getUser($user_id_row);

			if ($user_info_rows)
			{
				$user_info_row = array_pop($user_info_rows);
			}
		}

		if (!$user_info_row)
		{
			$this->data->setError('账号不存在');
		}

		if ($user_info_row['session_id'] != $session_id)
		{
			$this->data->setError('登录验证失败');
		}

		$arr_body = array("result" => 1);

		$this->data->addBody($arr_body);
	}

	public function returnVersion()
	{
		echo $_REQUEST['version'];
		die();
	}


	public function index()
	{
		include $this->view->getView();
	}

	/**
	 * 手机获取注册码
	 *
	 * @access public
	 */
	public function regCode()
	{
		$mobile                    = request_string('mobile');

		$data = array();


		//本地读取远程信息
		$key = Yf_Registry::get('ucenter_api_key');;
		$url    = Yf_Registry::get('ucenter_api_url');
		$app_id = Yf_Registry::get('ucenter_app_id');
		//$url = 'http://localhost/pcenter/index.php';

		$formvars              = array();
		$formvars['mobile']    = $mobile;
		$formvars['app_id']    = $app_id;

		$formvars['ctl'] = 'Login';
		$formvars['met'] = 'regCode';
		$formvars['typ'] = 'json';
		$init_rs         = get_url_with_encrypt($key, $url, $formvars);
		fb($init_rs);
		if (200 == $init_rs['status'])
		{
			$data['user_code'] = $init_rs['data']['user_code'];
			$msg = 'success';
			$status = 200;
			
		}
		else
		{
			$msg = $init_rs['msg'];
			$status = 250;
			$data = array();
		}

		/*
				$contents = array($data['user_code'], 2);
				$tpl_id = 63463;
				$result = Sms::send($mobile, $contents, $tpl_id);
		*/


		$this->data->addBody(-140, $data, $msg, $status);
	}


	/**
	 * 手机获取找回密码验证码
	 *
	 * @access public
	 */
	public function findPasswdCode()
	{
		$mobile     = request_string('mobile');
		$user_name  = request_string('user_name');

		$data = array();


		//本地读取远程信息
		$key = Yf_Registry::get('ucenter_api_key');;
		$url    = Yf_Registry::get('ucenter_api_url');
		$app_id = Yf_Registry::get('ucenter_app_id');
		//$url = 'http://localhost/pcenter/';


		$formvars              = array();
		$formvars['mobile']    = $mobile;
		$formvars['app_id']    = $app_id;
		$formvars['user_name'] = $user_name;

		$formvars['ctl'] = 'Login';
		$formvars['met'] = 'findPasswdCode';
		$formvars['typ'] = 'json';
		$init_rs         = get_url_with_encrypt($key, $url, $formvars);

		if (200 == $init_rs['status'])
		{
			$data['user_code'] = $init_rs['data']['user_code'];

			$config_cache = Yf_Registry::get('config_cache');

			if (!file_exists($config_cache['default']['cacheDir']))
			{
				mkdir($config_cache['default']['cacheDir']);
			}

			$Cache_Lite = new Cache_Lite_Output($config_cache['default']);

			$Cache_Lite->save($data['user_code'], $mobile);
			
			$msg = 'success';
			
			$status = 200;
		}
		else
		{
			$data = array();
			$msg = '失败';
			$status = 250;
		}

		/*
				$contents = array($data['user_code'], 2);
				$tpl_id = 63463;
				$result = Sms::send($mobile, $contents, $tpl_id);
		*/


		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function resetPasswd()
	{
		$User_InfoModel = new User_InfoModel();
		$User_BaseModel = new User_BaseModel();
		$mobile   = request_string('mobile');
		$cond_row['user_mobile'] = $mobile;
		$user_info_row = $User_InfoModel->getOneByWhere($cond_row);
		if($user_info_row)
		{
			$account = $user_info_row['user_name'];
		}

		$code     = request_string('user_code');
		$password = request_string('user_password');


		$data = array();


		//本地读取远程信息
		$key = Yf_Registry::get('ucenter_api_key');;
		$url    = Yf_Registry::get('ucenter_api_url');
		$app_id = Yf_Registry::get('ucenter_app_id');

		//$url = 'http://localhost/pcenter/';

		$formvars                  = array();
		$formvars['mobile']        = $mobile;
		$formvars['user_account']  = $account;
		$formvars['user_password'] = $password;
		$formvars['user_code']     = $code;
		$formvars['app_id']        = $app_id;

		$formvars['ctl'] = 'Login';
		$formvars['met'] = 'resetPasswd';
		$formvars['typ'] = 'json';
		$init_rs         = get_url_with_encrypt($key, $url, $formvars);

		fb($init_rs);
		if (200 == $init_rs['status'])
		{
				//检测登录状态
				$user_id_row = $User_BaseModel->getInfoByName($account);
				if ($user_id_row)
				{
					//重置密码
					$user_id          = $user_id_row['user_id'];
					$reset_passwd_row = array();

					$reset_passwd_row['user_password'] = md5($password);

					$flag = $User_BaseModel->editUser($user_id, $reset_passwd_row);
					if ($flag !== 'false')
					{
						$msg    = '重置密码成功';
						$status = 200;
						$data['user'] = $account;
						$config_cache = Yf_Registry::get('config_cache');
						$Cache_Lite   = new Cache_Lite_Output($config_cache['default']);

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

		/*
				$contents = array($data['user_code'], 2);
				$tpl_id = 63463;
				$result = Sms::send($mobile, $contents, $tpl_id);
		*/
		else
		{
				$msg = $init_rs['msg'];
				$status = 250;

		}


		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function resetPasswd1()
	{
		//
		$user_code = request_string('user_code');

		$data         = array();
		$data['user'] = request_string('user_account');
		$data['mobile'] = request_string('mobile');
		$mobile = request_string('mobile');

		if (!$data['mobile'])
		{
			$this->data->setError('手机号不能为空');
			return false;
		}

		if (request_string('user_password'))
		{
			$data['password'] = md5(request_string('user_password'));


			$config_cache = Yf_Registry::get('config_cache');
			$Cache_Lite   = new Cache_Lite_Output($config_cache['default']);

			$user_code_pre = $Cache_Lite->get($data['mobile']);
			fb($user_code);
			fb($user_code_pre);

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

					$reset_passwd_row['user_password'] = $data['password'];

					fb($user_id);
					fb($reset_passwd_row);
					$flag = $User_BaseModel->editUser($user_id, $reset_passwd_row);

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


	public function register()
	{
		$user_account = request_string('user_account', null);

		//本地读取远程信息
		$key = Yf_Registry::get('ucenter_api_key');;
		$url    = Yf_Registry::get('ucenter_api_url');
		$app_id = Yf_Registry::get('ucenter_app_id');
		//$url = 'http://localhost/pcenter/index.php';

		$formvars              = array();
		$formvars['user_account'] = $user_account;
		$formvars['user_password']  = request_string('user_password', null);
		$formvars['user_code'] = request_string('user_code');
		$formvars['mobile'] = request_string('mobile');
		$formvars['app_id']    = $app_id;

		$formvars['ctl'] = 'Login';
		$formvars['met'] = 'register';
		$formvars['typ'] = 'json';
		$init_rs         = get_url_with_encrypt($key, $url, $formvars);

		fb($init_rs);
		$server_id = '10001';
		if (200 == $init_rs['status'])
		{	
			/*
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
					//location_go_back('尚未开通服务');
					$this->data->setError('尚未开通服务');
					return;
				}
			}
			else
			{
				//location_go_back('获取服务信息有误');
				$this->data->setError('获取服务信息有误');
				return;
			}
			*/
		}
		else
		{
			//location_go_back('登录信息有误');
			$msg = $init_rs['msg'];
			$this->data->setError($msg);
			return;
		}

//		$config = Yf_Registry::get('db_cfg');
//
//		$db_row = include INI_PATH . '/db_' . $server_id . '.ini.php';
//
//		fb($db_row);
//		//设置本地数据库信息, 通过server_id本地文件读取PHP文件,
//		$config['db_cfg_rows'] = array(
//			'master' => array(
//				'im-builder' => array(
//					array(
//						$db_row
//					)
//				)
//			)
//		);
//
//		Yf_Registry::set('db_cfg', $config);

		$userBaseModel = new User_BaseModel();
		$userInfoModel = new User_InfoModel();

		//本地数据校验登录
		$user_id_row = $userBaseModel->getUserIdByAccount($user_account);
		fb($user_id_row);
		if (!$user_id_row)
		{
			$user_row = array();
			$user_row['user_account'] = $user_account;
			$user_row['user_password'] = md5($formvars['user_password']);
			//$user_row['user_mobile'] = $user_account;
			$user_row['server_id'] = $server_id;

			$user_id = $userBaseModel->addUser($user_row, true);
			$user_row['user_id'] = $user_id;
			$user_row['user_key'] = '';

			//插入info表
			$ip       = get_ip();
			$mobile = request_string('mobile');
			$user_info = array();
			$user_info['user_name'] = $user_account;
			$user_info['user_reg_time'] = time();
			$user_info['user_count_login'] = 1;
			$user_info['user_lastlogin_time'] = time();
			$user_info['user_reg_ip'] = $ip;
			$user_info['user_lastlogin_ip'] = $ip;
			$user_info['user_mobile'] = $mobile;
			$user_info['user_avatar'] = Web_ConfigModel::value("user_default_avatar");
			$user_info['nickname'] = $user_account;//昵称默认为用户名
			$user_info['user_sign'] = '';
			$user_info['user_birth'] = date('Y-m-d');
			$userInfoModel->addInfo($user_info);

			//登录
			if ($user_id)
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
		}


		//权限设置

		$this->data->addBody(-140, $user_row);
	}

	/*
	 * 检测登录数据是否正确,app端先直接请求用户中心登录, 获取登录信息后发送到此处验证, 此处请求用户中心判断是否正确,然后完成app登录
	 *
	 *
	 */
	public function check()
	{
		$ucenter_u    = request_string('ucenter_u', null);
		$ucenter_key  = request_string('ucenter_key', null);


		//本地读取远程信息
		$key = Yf_Registry::get('ucenter_api_key');;
		$url    = Yf_Registry::get('ucenter_api_url');
		$app_id = Yf_Registry::get('ucenter_app_id');

		$formvars              = array();
		$formvars['ucenter_u'] = $ucenter_u;
		$formvars['ucenter_key']  = $ucenter_key;
		$formvars['app_id']    = $app_id;

		$formvars['ctl'] = 'Api';
		$formvars['met'] = 'checkLogin';
		$formvars['typ'] = 'json';
		$init_rs         = get_url_with_encrypt($key, $url, $formvars);

		fb($init_rs);
		$server_id = 10001;
		if (200 == $init_rs['status'])
		{
			/*
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
					//location_go_back('尚未开通服务');
					$this->data->setError('尚未开通服务');
					return;
				}
			}
			else
			{
				//location_go_back('获取服务信息有误');
				$this->data->setError('获取服务信息有误');
				return;
			}
			*/
		}
		else
		{
			//location_go_back('登录信息有误');
			$this->data->setError('登录信息有误');
			return;
		}


		$config = Yf_Registry::get('db_cfg');

		$db_row = include INI_PATH . '/db_' . $server_id . '.ini.php';

		fb($db_row);
		//设置本地数据库信息, 通过server_id本地文件读取PHP文件,
		$config['db_cfg_rows'] = array(
			'master' => array(
				'im-builder' => array(
					array(
						$db_row
					)
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
			$user_row  = array_pop($user_rows);

			//判断状态是否开启
			if ($user_row['user_delete'] == 1)
			{
				$this->data->setError('用户尚未启用');
				return;
			}

			unset($user_row['user_password']);
			fb($user_row);
		}

		//if ($user_id_row && ($user_row['user_password'] == md5($_REQUEST['user_password'])))
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

		$this->data->addBody(-140, $user_row);
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
				//修改user_login表状态
				$User_LoginModel = new User_LoginModel();
				$user_login_info = current($User_LoginModel->getLogin($_COOKIE['id']));
				if($user_login_info)
				{
					$edit_row['user_status'] = 0;
					$User_LoginModel->editLogin($_COOKIE['id'], $edit_row);

				}
				else
				{
					//插入login表
					$user_login['user_id'] = $_COOKIE['id'];
					$user_login['user_login_times'] = 1;
					$user_login['user_last_login_time'] = time();
					$user_login['user_active_time'] = time();
					$user_login['user_status'] = 0;
					$User_LoginModel->addLogin($user_login);
				}
				echo "<script>parent.location.href='index.php';</script>";
				setcookie("key", null, time()-3600*24*365);
				setcookie("id", null, time()-3600*24*365);
			}
		}
	}

	/**
	 * app退出登录接口
	 * 2017-07-07 hp
	 * 为了修改登录状态
	 */
	public function AppLoginOut()
	{
		$user_id = Perm::$userId;//用户id
		if($user_id)
		{
			//修改user_login表状态
			$User_LoginModel = new User_LoginModel();
			$user_login_info = current($User_LoginModel->getLogin($user_id));
//			echo '<pre>';print_r($user_login_info);exit;
			if($user_login_info)
			{
				if($user_login_info['user_status'] == 1)
				{
					$edit_row['user_status'] = 0;
					$flag = $User_LoginModel->editLogin($user_id, $edit_row);
					if($flag)
					{
						$msg = 'success';
						$status = 200;
					}
					else
					{
						$msg = 'failure:修改状态失败';
						$status = 250;
					}
				}
				else
				{
					$msg = 'success';
					$status = 200;
				}
			}
			else
			{
				//插入login表
				$user_login['user_id'] = $user_id;
				$user_login['user_login_times'] = 1;
				$user_login['user_last_login_time'] = time();
				$user_login['user_active_time'] = time();
				$user_login['user_status'] = 0;
				$flag = $User_LoginModel->addLogin($user_login);
				if($flag)
				{
					$msg = 'success';
					$status = 200;
				}
				else
				{
					$msg = 'failure:修改状态失败';
					$status = 250;
				}
			}
		}
		$data = [];
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function checkToLogin()
	{
		$redirect = request_string('redirect');

		//本地读取远程信息
		$key = Yf_Registry::get('ucenter_api_key');;
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
//		$this->data->addBody(-140, $formvars);
//		echo '<pre>';print_r($init_rs);exit;
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
				$userBaseModel->editUserField($user_row['user_id'], 'user_key', $user_key, $user_row['user_key']);
				Yf_Hash::setKey($user_key);
				$encrypt_str        = Perm::encryptUserInfo($data);

				$user_row['k'] = $encrypt_str;
			}

			//权限设置
			$user_row['user_name'] = $user_account;
			fb($user_row);
			//print_r($user_row);

			if('e' == $this->typ)
			{
				if($redirect)
				{
					location_to(urldecode($redirect));
				}
			}
			else
			{
//				echo '<pre>';print_r($user_row);exit;
				$this->data->addBody(-140, $user_row);
			}
		}
		else
		{
			$this->data->setError('登陆失败');
		}
		if ($jsonp_callback = request_string('jsonp_callback'))
		{
			exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
		}
	}
}

?>

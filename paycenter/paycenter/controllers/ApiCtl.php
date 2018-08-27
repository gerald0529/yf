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

		//判断用户是否存在, 不存在则从ucenter初始化

		/*
		//获取用户信息
		//$user_id = Perm::$userId;
		$user_id = 1;
		$user_account = '111test11';

		//本地读取远程信息
		$key = Yf_Registry::get('ucenter_api_key');

		$url                       = Yf_Registry::get('ucenter_api_url');
		$ucenter_app_id            = Yf_Registry::get('ucenter_app_id');
		$formvars                  = array();
		$formvars['app_id']        = $ucenter_app_id;

		$formvars['ctl'] = 'Api';
		$formvars['met'] = 'getUserInfo';
		$formvars['typ'] = 'json';
		$formvars['user_id'] = $user_id;
		$user         = get_url_with_encrypt($key, $url, $formvars);


		if ($user['status'] == 200)
		{
			$user_info = current($user['data']);
		}
		else
		{
			$user_info = array();
		}
		*/
	}


	/**
	 * 用户登录
	 *
	 * @access public
	 * http://localhost/imbuilder/index.php?ctl=Api&met=login&user_account=admin&user_password=111111
	 */
	public function login()
	{
		$user_account = $_REQUEST['user_account'];

		//本地读取远程信息
		$key = Yf_Registry::get('ucenter_im_key');;
		$url    = Yf_Registry::get('ucenter_api_url');
		$app_id = Yf_Registry::get('app_id');

		$formvars              = array();
		$formvars['user_account'] = $_REQUEST['user_account'];
		$formvars['user_password']  = $_REQUEST['user_password'];
		$formvars['app_id']    = $app_id;

		$formvars['ctl'] = 'Api';
		$formvars['met'] = 'login';
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
			$msg = $init_rs['msg'];
			$this->data->setError($msg);
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

		$userInfoModel = new User_InfoModel();

		//本地数据校验登录
		$user_id_row = $userBaseModel->getUserIdByAccount($user_account);

		fb($user_id_row);

		//初始化用户信息,插入数据
		if (!$user_id_row)
		{
			$user_row = array();
			$user_row['user_account'] = $user_account;
			$user_row['user_password'] = md5($formvars['password']);
			//$user_row['user_mobile'] = $user_account;
			$user_row['server_id'] = $server_id;


			$user_id = $userBaseModel->addUser($user_row, true);
			$user_id_row = $userBaseModel->getUserIdByAccount($user_account);


			//插入info表
			$now_time = time();
			$ip = get_ip();
			$user_info = array();
			$user_info['user_name'] = $user_account;
			$user_info['user_reg_time'] = $now_time;
			$user_info['user_count_login'] = 1;
			$user_info['user_lastlogin_time'] = $now_time;
			$user_info['user_lastlogin_ip'] = $ip;
			$user_info['user_reg_ip'] = $ip;

			$userInfoModel->addInfo($user_info);
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
			$data              = array();
			$data['user_id']   = $user_row['user_id'];
			$data['server_id'] = $user_row['server_id'];
			srand((double)microtime() * 1000000);
			$user_key = md5(rand(0, 32000));
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
		$key = Yf_Registry::get('ucenter_im_key');;
		$url    = Yf_Registry::get('ucenter_api_url');
		$app_id = Yf_Registry::get('app_id');

		$formvars              = array();
		$formvars['mobile']    = $mobile;
		$formvars['app_id']    = $app_id;

		$formvars['ctl'] = 'Login';
		$formvars['met'] = 'regCode';
		$formvars['typ'] = 'json';
		$init_rs         = get_url_with_encrypt($key, $url, $formvars);

		if (200 == $init_rs['status'])
		{
			$data['user_code'] = $init_rs['data']['user_code'];
		}

		/*
				$contents = array($data['user_code'], 2);
				$tpl_id = 63463;
				$result = Sms::send($mobile, $contents, $tpl_id);
		*/
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


		$this->data->addBody(-140, $data, $msg, $status);
	}


	/**
	 * 手机获取找回密码验证码
	 *
	 * @access public
	 */
	public function findPasswdCode()
	{
		$mobile                    = request_string('mobile');

		$data = array();


		//本地读取远程信息
		$key = Yf_Registry::get('ucenter_im_key');;
		$url    = Yf_Registry::get('ucenter_api_url');
		$app_id = Yf_Registry::get('app_id');
		//$url = 'http://localhost/pcenter/';


		$formvars              = array();
		$formvars['mobile']    = $mobile;
		$formvars['app_id']    = $app_id;

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
		}

		/*
				$contents = array($data['user_code'], 2);
				$tpl_id = 63463;
				$result = Sms::send($mobile, $contents, $tpl_id);
		*/
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


		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function resetPasswd()
	{
		$mobile   = request_string('mobile');
		$account  = request_string('user_account');
		$code     = request_string('user_code');
		$password = request_string('user_password');


		$data = array();


		//本地读取远程信息
		$key = Yf_Registry::get('ucenter_im_key');;
		$url    = Yf_Registry::get('ucenter_api_url');
		$app_id = Yf_Registry::get('app_id');

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
				$User_BaseModel = new User_BaseModel();

				//检测登录状态
				$user_id_row = $User_BaseModel->getInfoByName($account);

				if ($user_id_row)
				{
					//重置密码
					$user_id          = $user_id_row['user_id'];
					$reset_passwd_row = array();

					$reset_passwd_row['user_password'] = md5($password);

					$flag = $User_BaseModel->editUser($user_id, $reset_passwd_row);

					if ($flag)
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
		$key = Yf_Registry::get('ucenter_im_key');;
		$url    = Yf_Registry::get('ucenter_api_url');
		$app_id = Yf_Registry::get('app_id');

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
			$msg = $init_rs['msg'];
			$this->data->setError($msg);
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
		$userInfoModel = new User_InfoModel();

		//本地数据校验登录
		$user_id_row = $userBaseModel->getUserIdByAccount($user_account);

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
			$userInfoModel->addInfo($user_info);

			//登录
			if ($user_id)
			{
				$data              = array();
				$data['user_id']   = $user_row['user_id'];
				$data['server_id'] = $user_row['server_id'];
				srand((double)microtime() * 1000000);
				$user_key = md5(rand(0, 32000));
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
		$key = Yf_Registry::get('ucenter_im_key');;
		$url    = Yf_Registry::get('ucenter_api_url');
		$app_id = Yf_Registry::get('app_id');

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
				echo "<script>parent.location.href='index.php';</script>";
				setcookie("key", null, time()-3600*24*365);
				setcookie("id", null, time()-3600*24*365);

			}
		}
	}
    public function getPayWays()
    {
		$type = $_REQUEST['type'];
		$Pay_PaymentChannelModel = new Pay_PaymentChannelModel();
		$data = $Pay_PaymentChannelModel->getPayWaysByCode($type);
		fb($data);
		if ($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		$this->data->addBody(-140, $data,$msg, $status);
	}
	public function editPay()
	{
		$payid = $_REQUEST['payment_channel_id'];
		$data['payment_channel_code'] = $_REQUEST['payment_channel_code'];
		$data['payment_channel_name'] = $_REQUEST['payment_channel_name'];
		$data['payment_channel_status'] = 1;
		$data['payment_channel_config'] = $_REQUEST['payment_channel_config'];
		
		
		$Pay_PaymentChannelModel = new Pay_PaymentChannelModel();
		$flag = $Pay_PaymentChannelModel->editPaymentChannel($payid,$data);
		
		if ($flag)
		{
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
	public function editPayStatus()
	{
		$payid = $_REQUEST['payment_channel_id'];
		$Pay_PaymentChannelModel = new Pay_PaymentChannelModel();
		$date = $Pay_PaymentChannelModel->getPaymentChannel($payid);
		
		$payment_channel_status = $date[$payid]['payment_channel_status']?0:1;
		$data =array('payment_channel_status'=>$payment_channel_status);
		$flag = $Pay_PaymentChannelModel->editPaymentChannel($payid,$data);
		
		
		//$data=array();
		//fb($flag);
		if ($flag)
		{
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

}

?>
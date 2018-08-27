<?php

class LoginCtl extends Yf_AppController
{
	public function index()
	{
		include $this->view->getView();
	}

	/*
	 * 检测登录数据是否正确
	 *
	 *
	 */
	public function login()
	{
		if (!Perm::checkUserPerm())
		{
			$login_url   = Yf_Registry::get('ucenter_api_url') . '?ctl=Login&met=index&typ=e';
			$reg_url     = Yf_Registry::get('ucenter_api_url') . '?ctl=Login&met=regist&typ=e';
			$findpwd_url = Yf_Registry::get('ucenter_api_url') . '?ctl=Login&met=findpwd&typ=e';

			$callback = Yf_Registry::get('url') . '?ctl=Login&met=check&typ=e&redirect=' . urlencode(request_string('forward'));


			$login_url = $login_url . '&from=shop&callback=' . urlencode($callback);
			header('location:' . $login_url);
			exit();
		}
		else
		{
			header('location:' . Yf_Registry::get('base_url') . '/index.php?ctl=Info&met=account');

		}
	}

	/*
	 * 检测登录数据是否正确
	 *
	 *
	 */
	public function reg()
	{
		if (!Perm::checkUserPerm())
		{
			$login_url   = Yf_Registry::get('ucenter_api_url') . '?ctl=Login&met=index&typ=e';
			$reg_url     = Yf_Registry::get('ucenter_api_url') . '?ctl=Login&met=regist&typ=e';
			$findpwd_url = Yf_Registry::get('ucenter_api_url') . '?ctl=Login&met=findpwd&typ=e';

			$callback = Yf_Registry::get('url') . '?ctl=Login&met=check&typ=e&redirect=' . urlencode(request_string('forward'));


			$login_url = $reg_url . '&from=shop&callback=' . urlencode($callback);
			header('location:' . $login_url);
			exit();
		}
		else
		{
			header('location:' . Yf_Registry::get('base_url') . '/index.php?ctl=Info&met=account');
		}
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
		$url    = Yf_Registry::get('ucenter_api_url');
		$app_id = Yf_Registry::get('ucenter_app_id');


		$formvars            = array();
		$formvars['user_id'] = request_int('us');
		$formvars['u']       = request_int('us');
		$formvars['k']       = request_string('ks');
		$formvars['app_id']  = $app_id;


		$url     = sprintf('%s?ctl=%s&met=%s&typ=%s', $url, 'Login', 'checkLogin', 'json');
		$init_rs = get_url_with_encrypt($key, $url, $formvars);

		if (200 == $init_rs['status'] && $init_rs['data']['user_id'] > 0)
		{
			//读取服务列表

			$user_row  = $init_rs['data'];
			$user_id   = $user_row['user_id'];
			$user_name = $user_row['user_name'];


			$User_BaseModel  = new User_BaseModel();
			$User_InfoModel  = new User_InfoModel();

			//本地数据校验登录
			$user_row = $User_BaseModel->getOne($user_id);

			if ($user_row)
			{
				//判断状态是否开启
				if ($user_row['user_delete'] == 1)
				{
					$msg = _('该账户未启用，请启用后登录！');
					if ('e' == $this->typ)
					{
						location_go_back($msg);
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
				$result           = $User_BaseModel->addBase($data);

				//判断状态是否开启
				if (!$result)
				{
					$msg = _('*初始化用户出错!');
					if ('e' == $this->typ)
					{
						location_go_back(_('初始化用户出错*!'));
					}
					else
					{
						return $this->data->setError($msg, array());
					}
				}
				else
				{
					//初始化用户信息                    
                    $add_user_info                  = array();
                    $add_user_info['user_id']       = $init_rs['data']['user_id'];
                    $add_user_info['user_nickname'] = $init_rs['data']['user_name'];
                    $add_user_info['user_active_time'] = date('Y-m-d H:i:s');
                    $add_user_info['user_realname'] = $init_rs['data']['user_truename'];
                    $add_user_info['user_email']    = $init_rs['data']['user_email'];
                    $add_user_info['user_mobile']   = $init_rs['data']['user_mobile'];
                    $add_user_info['user_qq']       = $init_rs['data']['user_qq'];
                    $add_user_info['user_avatar']   = $init_rs['data']['user_avatar'];
                    $add_user_info['user_identity_card'] = $init_rs['data']['user_idcard'];
                    
                    
					$User_InfoModel                 = new User_InfoModel();
					$info_flag                      = $User_InfoModel->addInfo($add_user_info);

					$user_resource_row                = array();
					$user_resource_row['user_id']     = $init_rs['data']['user_id'];
					
					$User_ResourceModel = new User_ResourceModel();
					$res_flag           = $User_ResourceModel->addResource($user_resource_row);
				}

				$user_row = $data;
			}

			if ($user_row)
			{
				$data            = array();
				$data['user_id'] = $user_row['user_id'];
				srand((double)microtime() * 1000000);
				//$user_key = md5(rand(0, 32000));
				$user_key = $init_rs['data']['session_id'];
				$time     = get_date_time();
				//获取上次登录的时间
				$info = $User_BaseModel->getBase($user_row['user_id']);
				
				$lotime   = strtotime($info[$user_row['user_id']]['user_login_time']);


				$login_info_row                     = array();
				$login_info_row['user_key']         = $user_key;
				$login_info_row['user_login_time']  = $time;
				$login_info_row['user_login_times'] = $user_row['user_login_times'] + 1;
				$login_info_row['user_login_ip']    = get_ip();

				$flag = $User_BaseModel->editBase($user_row['user_id'], $login_info_row, false);
				
				//$login_row['user_logintime'] = $time;
				//$login_row['lastlogintime']  = $info[$user_row['user_id']]['user_login_time'];
				//$login_row['user_ip']        = get_ip();
				//$login_row['user_lastip']    = $info[$user_row['user_id']]['user_login_ip'];
				//$flag                        = $User_InfoModel->editInfo($user_row['user_id'], $login_row, false);
				//当天没有登录过执行

				//$flag     = $User_BaseModel->editBaseSingleField($user_row['user_id'], 'user_key', $user_key, $user_row['user_key']);
				Yf_Hash::setKey($user_key);
				
				$encrypt_str = Perm::encryptUserInfo($data);
				
				
				//判断有没有回调地址
				if(request_string('redirect'))
				{
					$redirect = request_string('redirect');
				}
				else
				{
					$redirect = Yf_Registry::get('base_url');
				}
				
				if ('e' == $this->typ)
				{
					if($redirect)
					{
						location_to(urldecode($redirect));
					}
				}
				else
				{
					$data            = array();
					$data['user_id'] = $user_row['user_id'];
					$data['user_account'] = $user_row['user_account'];
					$data['key'] = $encrypt_str;
					$this->data->addBody(100, $data);
				}
			}
			else
			{
				$msg = _('登录出错！');
				if ('e' == $this->typ)
				{
					location_go_back($msg);
				}
				else
				{
					return $this->data->setError($msg, array());
				}
			}
		}
		else
		{
			$msg = _('登录信息有误！');
			
			if ('e' == $this->typ)
			{
				location_go_back($msg);
			}
			else
			{
				return $this->data->setError($msg, array());
			}
		}

		$this->data->addBody(100, $init_rs);
		
		if ($jsonp_callback = request_string('jsonp_callback'))
		{
			exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
		}
	}


	/**
	 * 用户登录,通过本站输入用户名密码登录
	 *
	 * @access public
	 */
	public function doLogin()
	{
		$user_account = $_REQUEST['user_account'];

		//本地读取远程信息
		$key = Yf_Registry::get('ucenter_api_key');

		$url                       = Yf_Registry::get('ucenter_api_url');
		$ucenter_app_id            = Yf_Registry::get('ucenter_app_id');
		$formvars                  = array();
		$formvars['user_account']  = $_REQUEST['user_account'];
		$formvars['user_password'] = $_REQUEST['user_password'];
		$formvars['app_id']        = $ucenter_app_id;

		$formvars['ctl'] = 'Api';
		$formvars['met'] = 'login';
		$formvars['typ'] = 'json';
		$init_rs         = get_url_with_encrypt($key, $url, $formvars);


		if (200 == $init_rs['status'])
		{
			//读取服务列表
		}
		else
		{
			$msg = _('登录信息有误');
			if ('e' == $this->typ)
			{
				location_go_back($msg);
			}
			else
			{
				return $this->data->setError($msg, array());
			}
		}


		$userBaseModel = new User_BaseModel();

		//本地数据校验登录
		$user_id_row = $userBaseModel->getUserIdByAccount($user_account);

		if ($user_id_row)
		{
			$user_rows = $userBaseModel->getBase($user_id_row);
			$user_row  = array_pop($user_rows);
			//判断状态是否开启
			if ($user_row['user_delete'] == 1)
			{

				$msg = _('该账户未启用，请启用后登录！');
				if ('e' == $this->typ)
				{
					location_go_back($msg);
				}
				else
				{
					return $this->data->setError($msg, array());
				}
			}
			//fb($user_row);
		}
		else
		{
			$user_row = $init_rs['data'];

			//添加用户
			$data['user_id']       = $user_row['user_id']; // 用户id
			$data['user_account']  = $user_row['user_name']; // 用户帐号
			$data['user_password'] = $user_row['password']; // 密码：使用用户中心-此处废弃

			$data['user_delete'] = 0; // 用户状态
			$user_id             = $userBaseModel->addBase($data, true);

			//判断状态是否开启
			if (!$user_id)
			{

				$msg = _('初始化用户出错！');
				if ('e' == $this->typ)
				{
					location_go_back($msg);
				}
				else
				{
					return $this->data->setError($msg, array());
				}

			}
		}

		//if ($user_id_row && ($user_row['user_password'] == md5($_REQUEST['user_password'])))
		if ($user_row)
		{
			$data            = array();
			$data['user_id'] = $user_row['user_id'];
			srand((double)microtime() * 1000000);
			//$user_key = md5(rand(0, 32000));
			$user_key = $init_rs['data']['session_id'];
			$flag     = $userBaseModel->editBaseSingleField($user_row['user_id'], 'user_key', $user_key, $user_row['user_key']);
			Yf_Hash::setKey($user_key);
			$encrypt_str = Perm::encryptUserInfo($data);

			if ('e' == $this->typ)
			{
				location_to(Yf_Registry::get('base_url'));
			}
			else
			{
				$data['user_account'] = $formvars['user_account'];
				$data['key'] = $encrypt_str;
				$this->data->addBody(100, $data);
			}

		}
		else
		{
			$msg = _('输入密码有误！');
			if ('e' == $this->typ)
			{
				location_go_back($msg);
			}
			else
			{
				return $this->data->setError($msg, array());
			}
		}

		//权限设置

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
			if (isset($_COOKIE['key']) || isset($_COOKIE['id']))
			{
				echo "<script>parent.location.href='index.php';</script>";
				setcookie("key", null, time() - 3600 * 24 * 365);
				setcookie("id", null, time() - 3600 * 24 * 365);

				setcookie("key", null, time() - 3600 * 24 * 365,'/');
				setcookie("id", null, time() - 3600 * 24 * 365,'/');

			}


			$login_url = Yf_Registry::get('ucenter_api_url') . '?ctl=Login&met=logout&typ=e';
			$callback  = Yf_Registry::get('url') . '?redirect=' . urlencode(Yf_Registry::get('url')) . '&type=ucenter';

			$login_url = $login_url . '&from=paycenter&callback=' . urlencode($callback);

			header('location:' . $login_url);
			exit();
		}
	}
}

?>
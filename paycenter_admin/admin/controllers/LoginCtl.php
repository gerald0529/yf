<?php

class LoginCtl extends AdminController
{
	public function index()
	{
		include $this->view->getView();
	}
	
	/**
	 * 用户登录
	 *
	 * @access public
	 */
	public function login()
	{
		session_start();
        $data = array();
		if (strtolower($_SESSION['auth']) != strtolower($_REQUEST['yzm']))
		{
//			location_go_back('验证码错误');
            return $this->data->addBody(-140, $data, '验证码错误!', 250);
		}
		
		$user_account = $_REQUEST['user_account'];
		//本地读取远程信息
		$key = Yf_Registry::get('ucenter_api_key');;
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
//			location_go_back(isset($init_rs['msg']) ? '登录失败,请重新登录!' . $init_rs['msg'] : '登录失败,请重新登录!');
            $msg = isset($init_rs['msg']) ? '登录失败,请重新登录!' . $init_rs['msg'] : '登录失败,请重新登录!';
            return $this->data->addBody(-140, $data, $msg, 250);
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
//				return location_go_back('该账户未启用，请启用后登录！');
                return $this->data->addBody(-140, $data, '该账户禁用，请启用后登录！', 250);
			}
			//fb($user_row);
		}
		else
		{
			$user_row = $init_rs['data'];

            return $this->data->addBody(-140, $data, '该账户未启用，请启用后登录！', 250);

		}
		
		//if ($user_id_row && ($user_row['user_password'] == md5($_REQUEST['user_password'])))
		if ($user_row)
		{
			$data            = array();
			$data['user_id'] = $user_row['user_id'];
			srand((double)microtime() * 1000000);
			//$user_key = md5(rand(0, 32000));
			$user_key = 'ttt';
			$flag     = $userBaseModel->editBaseSingleField($user_row['user_id'], 'user_key', $user_key, $user_row['user_key']);
			Yf_Hash::setKey($user_key);
			Perm::encryptUserInfo($data);
			
//			location_to(Yf_Registry::get('base_url'));
			$data['url'] = Yf_Registry::get('base_url');
			$this->data->addBody(-140, $data, '登录成功', 200);
   
		}
		else
		{
		
//			location_go_back('输入密码有误');
            return $this->data->addBody(-140, $data, '输入密码有误', 250);
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
				setcookie("key", null, time() - 3600 * 24 * 365,'/');
				setcookie("id", null, time() - 3600 * 24 * 365,'/');
				echo "<script>parent.location.href='" . Yf_Registry::get('base_url') . "';</script>";
			}
		}
	}

	public function getCheckCode()
	{
		session_start();
		//===============================
		$width  = $_GET['w'] ? $_GET['w'] : "80";
		$height = $_GET['h'] ? $_GET['h'] : "33";
		$image  = new ValidationCode($width, $height, '4');
		$image->outImg();
		$_SESSION["auth"] = $image->checkcode;
	}
}

?>



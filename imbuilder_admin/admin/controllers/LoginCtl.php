<?php
class LoginCtl extends Yf_AppController
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
			location_go_back(isset($init_rs['msg']) ? '登录失败,请重新登录!' . $init_rs['msg'] : '登录失败,请重新登录!');
		}

		$user_account = $_REQUEST['user_account'];

		$userBaseModel = new User_BaseModel();

		//本地数据校验登录
		$user_id_row = $userBaseModel->getUserIdByAccount($user_account);

		if ($user_id_row)
		{
			$user_rows = $userBaseModel->getUser($user_id_row);
			$user_row = array_pop($user_rows);
			fb($user_row);
		}

		if ($user_id_row)
		{
			$data = array();
			$data['user_id'] = $user_row['user_id'];

            Perm::encryptUserInfo($data);

			location_to(Yf_Registry::get('base_url'));
		}
		else
		{

			location_go_back('输入密码有误');
		}

		//权限设置

	}


	/**
	 * 远程回调用户登录
	 *
	 * @access public
	 */
	public function loginCallback()
	{
		//远程数据校验
		$user_account = $_REQUEST['user_account'];
		$server_id    = $_REQUEST['server_id'];

		$config = Yf_Registry::get('db_cfg');

		$db_row = include_once 'db_' . $server_id . '.ini.php';

		//设置本地数据库信息, 通过server_id本地文件读取PHP文件,
		$config['db_cfg_rows'] = array(
			'master' => array(
				'im_data' => array(
					$db_row
				)
			)
		);

		Yf_Registry::set('db_cfg', $config);



		$userBaseModel = new User_BaseModel();

		//远程callback, 目前选择远程读取, 获取本地信息
		$user_id_row = $userBaseModel->getUserIdByAccount($user_account);

		if ($user_id_row)
		{
			$user_rows = $userBaseModel->getUser($user_id_row);
			$user_row = array_pop($user_rows);
			fb($user_row);
		}

		if ($user_id_row && ($user_row['user_password'] == md5($_REQUEST['user_password'])))
		{
			$data = array();
			$data['user_id'] = $user_row['user_id'];
			$data['server_id'] = $user_row['server_id'];
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
				setcookie("key", null, time()-3600*24*365);
				setcookie("id", null, time()-3600*24*365);
                echo "<script>parent.location.href='index.php';</script>";

            }
        }
    }




}
?>
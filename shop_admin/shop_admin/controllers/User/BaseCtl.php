<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author
 */
class User_BaseCtl extends AdminController
{
	public $dataUserModel = null;

	/**
	 * 初始化方法，构造函数
	 *
	 * @access public
	 */
	public function init()
	{
		//include $this->view->getView();
		$this->dataUserModel = new User_BaseModel();
	}

	/**
	 * 列表数据
	 *
	 * @access public
	 */
	public function userList()
	{
		$user_id = Perm::$userId;

		$page = request_int('page');
		$rows = request_int('rows');
		$sort = request_int('sort');

		$data = array();

		if (isset($_REQUEST['skey']))
		{
			$skey = $_REQUEST['skey'];

			$data = $this->dataUserModel->getUserList('*', $page, $rows, $sort);

		}
		//是否封禁
		if (!empty($_REQUEST['user_delete']))
		{
			$this->dataUserModel->sql->getByWhere(array('user_delete' => $_REQUEST['user_delete']));
		}
		else
		{
			$data = $this->dataUserModel->getUserList('*', $page, $rows, $sort);
		}
		$data['leftTotal'] = '';
		$this->data->addBody(-140, $data);
	}

	/**
	 * 读取
	 *
	 * @access public
	 */
	public function right()
	{
		$user_id = Perm::$userId;

		switch ($_REQUEST['action'])
		{
			case 'isMaxShareUser' :
				$this->isMaxShareUser();
				break;
			case 'auth2UserCancel' :
				$this->auth2UserCancel();
				break;
			case 'auth2User' :
				$this->auth2User();
				break;
			case 'queryAllUserRights' :
				$this->queryAllUserRights();
				break;
			default :
				break;
		}
	}

	/**
	 * 读取
	 *
	 * @access public
	 */
	public function auth2UserCancel()
	{
		$data['user_id'] = $_REQUEST['user_id']; // 用户id

		$data['user_delete'] = 0; // 是否被封禁，0：未封禁，1：封禁


		$user_id = $_REQUEST['user_id'];
		$data_rs = $data;

		unset($data['user_id']);

		$flag = $this->dataUserModel->editUser($user_id, $data);
		$this->data->addBody(-140, $data_rs);
	}

	/**
	 * 读取
	 *
	 * @access public
	 */
	public function auth2User()
	{
		$data['user_id'] = $_REQUEST['user_id']; // 用户id

		$data['user_delete'] = 1; // 是否被封禁，0：未封禁，1：封禁


		$user_id = $_REQUEST['user_id'];
		$data_rs = $data;

		unset($data['user_id']);

		$flag = $this->dataUserModel->editUser($user_id, $data);
		$this->data->addBody(-140, $data_rs);
	}

	/*
	 * 2016-5-16
	 * 权限控制
	 */
	public function index()
	{
		include $this->view->getView();
	}

	public function manage()
	{
		include $this->view->getView();
	}

	/**
	 * 添加
	 *
	 * @access public
	 */
	public function add()
	{
		$key = Yf_Registry::get('ucenter_api_key');
		$url       = Yf_Registry::get('ucenter_api_url');
		$app_id    = Yf_Registry::get('ucenter_app_id');
		$server_id = Yf_Registry::get('server_id');

		//验证是否已达到共享上限
		/*
		$formvars['app_id']    = $app_id;
		$formvars['server_id'] = $server_id;
		$formvars['ctl']       = 'Api';
		$formvars['met']       = 'getUserAppServerInfo';
		$formvars['typ']       = 'json';
		$UserAppServerInfo     = get_url_with_encrypt($key, $url, $formvars);

		$user_list = $this->dataUserModel->get('*');

		if ($UserAppServerInfo['data']['account_num'] - count($user_list) <= 0)
		{
			return $this->data->addBody(-140, array(), '共享人数已达到上限！', 250);
		}
		*/

		//开通ucenter
		//本地读取远程信息
		$formvars              = array();
		$formvars['user_name'] = $_REQUEST['user_account'];
		$formvars['password']  = $_REQUEST['user_password'];
		$formvars['app_id']    = $app_id;
		$formvars['server_id'] = $server_id;

		$formvars['ctl'] = 'Api';
		$formvars['met'] = 'addUserAndBindAppServer';
		$formvars['typ'] = 'json';

		$init_rs = get_url_with_encrypt($key, $url, $formvars);

		if (200 == $init_rs['status'])
		{
			//本地读取远程信息
			$data['user_id']         = $init_rs['data']['user_id']; // 用户帐号
			$data['user_account']    = $_REQUEST['user_account']; // 用户帐号
			$data['user_password']   = md5($_REQUEST['user_password']); // 密码：使用用户中心-此处废弃
			$data['user_delete']     = 0; // 用户状态
			$data['rights_group_id'] = request_int('rights_group_id'); // 用户权限组
            $data['sub_site_id'] = request_int('subsite_id');
			$user_id                    = $this->dataUserModel->addBase($data, true);
			$this->baseRightsGroupModel = new Rights_GroupModel();
			$data_rights                = $this->baseRightsGroupModel->getRightsGroupList();
			$data_rights                = $data_rights['items'];

			foreach ($data_rights as $key => $val)
			{
				if ($val['rights_group_id'] == $data['rights_group_id'])
				{
					$data['rights_group_name'] = $val['rights_group_name'];
				}
			}
			$data['user_id'] = $user_id;

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
			$data   = array();
			$msg    = $init_rs['msg'];
			$status = 250;
		}


		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['user_account'] = $_REQUEST['user_account']; // 用户帐号
		//$data['user_realname']   = $_REQUEST['user_realname']; // 真实姓名
		$data['rights_group_id'] = request_int('rights_group_id'); // 用户权限组
		$user_id                 = $_REQUEST['user_id'];
        $data['sub_site_id'] = request_int('subsite_id');

		if (!empty($_REQUEST['user_password']))
		{

			$data['user_password'] = $_REQUEST['user_password']; // 密码
			$user_dat              = current($this->dataUserModel->getBase($user_id));

			/*
			if ($user_dat['user_password'] !== $data['user_password'])
			{

			}
			*/

			$key = Yf_Registry::get('ucenter_api_key');;
			$url       = Yf_Registry::get('ucenter_api_url');
			$app_id    = Yf_Registry::get('ucenter_app_id');
			$server_id = Yf_Registry::get('server_id');

			$formvars['app_id']        = $app_id;
			$formvars['server_id']     = $server_id;
			$formvars['ctl']           = 'Api';
			$formvars['met']           = 'resetPasswd';
			$formvars['typ']           = 'json';
			$formvars['user_account']  = $data['user_account'];
			$formvars['user_password'] = $data['user_password'];
			$formvars['from']          = 'shop';
			$resetPasswd               = get_url_with_encrypt($key, $url, $formvars);

			if ($resetPasswd['status'] != 200)
			{
				return $this->data->addBody(-140, $data, $resetPasswd['msg'], 250);
			}


			$data['user_password'] = md5($_REQUEST['user_password']); // 密码：使用用户中心-此处废弃

		}

		$data_rs            = $data;
		$data_rs['user_id'] = $user_id;
		$flag               = $this->dataUserModel->editUser($user_id, $data);

		if ($flag !== false)
		{
			$this->baseRightsGroupModel = new Rights_GroupModel();
			$data_rights                = $this->baseRightsGroupModel->getRightsGroupList();
			$data_rights                = $data_rights['items'];

			foreach ($data_rights as $key => $val)
			{
				if ($val['rights_group_id'] == $data_rs['rights_group_id'])
				{
					$data_rs['rights_group_name'] = $val['rights_group_name'];
				}
			}

			$this->data->addBody(-140, $data_rs);
		}
	}
}

?>
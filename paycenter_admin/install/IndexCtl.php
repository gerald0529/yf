<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class IndexCtl extends Yf_AppController
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);

		//检测lock file
		$lock_file = APP_PATH . '/data/installed.lock';

		if(file_exists($lock_file))
		{
			$this->view->setMet('msg');

			$msg = _("系统已经安装过了，如果要重新安装，那么请删除install目录下的data/installed.lock文件");
			include $this->view->getView();
			die();
		}
	}

    public function index()
    {
		//检测配置文件是否存在正确,
		//检测表是否正确
		//
		// 如果无表,则直接安装 install

		$state_row = check_install_db();

		if (10 == $state_row['state'])
		{
			$this->view->setMet('msg');

			$msg = '已经安装完成,不可以再次安装!';
			include $this->view->getView();
		}
		elseif (9 == $state_row['state'])
		{
			$this->view->setMet('msg');

			$msg = '数据库信息已经存在,不可以继续安装,请先手动删除存在的表后,执行安装程序!';
			include $this->view->getView();
		}
		else
		{
			$this->view->setMet('policy');
			$this->policy();
		}

    }

	public function msg()
	{
		include $this->view->getView();
	}

    public function policy()
    {
		include $this->view->getView();
    }

	public function env()
	{
		include $this->view->getView();
	}

	public function dirs()
	{
		include $this->view->getView();
	}

	public function checkEnv()
	{
		sleep(1);

		$check_rs = true;

		//版本
		$version_row = array();
		$version_row['php_version'] = PHP_VERSION;
		$version_row['short_open_tag'] = get_cfg_var('short_open_tag');


		$os = explode(" ", php_uname());
		$version_row['php_uname'] = $os[0];
		$version_row['upload_max_filesize'] = min(get_cfg_var('post_max_size'), get_cfg_var('upload_max_filesize'));


		//var_dump($version_row['short_open_tag']);

		//print_r($version_row);

		//扩展
		$loaded_ext_row = get_loaded_extensions();
		$check_ext_row = include_once INI_INSTALL_PATH . '/check_ext.ini.php';

		foreach ($check_ext_row as $ext_name)
		{
			if (!in_array($ext_name, $loaded_ext_row))
			{
				$check_rs = false;
				break;
			}
		}

		//目录权限
		$check_dir_row = include_once INI_INSTALL_PATH . '/check_dir.ini.php';
		$dir_rows = check_dirs_priv($check_dir_row);

		//函数检查
		if (!$dir_rows['result'])
		{
			$check_rs = false;
		}

		include $this->view->getView();
	}

    public function plugin()
    {
        include $this->view->getView();
    }

	public function db()
	{
		$db_cfg = Yf_Registry::get('db_cfg');
		$db_row = current($db_cfg['db_cfg_rows']['master']['paycenter_admin']);
		include $this->view->getView();
	}

	public function initDbConfig()
	{
		$db_row = array(
			'host' => 'localhost',
			'port' => '3306',
			'user' => '',
			'password' => '',
			'database' => '',
			'charset' => 'UTF8'
		);

		$db_row['host'] = request_string('host');
		$db_row['port'] = request_string('port');
		$db_row['user'] = request_string('user');
		$db_row['password'] = request_string('password');
		$db_row['database'] = request_string('database');

		$db_row = array_map('htmlspecialchars', $db_row);


		$file = INI_PATH . '/db.ini.php';

		$prefix = htmlspecialchars(request_string('prefix'));

		$data[] = 'define("TABEL_PREFIX", "' . $prefix . '"); //表前缀';
		$data['db_row'] = $db_row;
		$data[] = 'return $db_row';

		if (Yf_Utils_File::generatePhpFile($file, $data))
		{
			$status = 200;
			$msg = _('success!');
		}
		else
		{
			$status = 250;
			$msg = _('生成配置文件错误!');
		}

		location_to('./index.php?met=install');
	}

	public function install()
	{
		$state_row = check_install_db();

		if (10 == $state_row['state'])
		{
			$this->view->setMet('msg');

			$msg = '已经安装完成,不可以再次安装!';
			include $this->view->getView();
		}
		elseif (9 == $state_row['state'])
		{
			location_to('./index.php?met=admin');
		}
		else if (2 == $state_row['state'])
		{
			ob_end_flush();

			include $this->view->getView();

			echo str_repeat(" ", 4096);  //以确保到达output_buffering值
			echo '安装中';  //以确保到达output_buffering值
			ob_flush();
			flush();


			//如果无表,则直接安装 install
			$Db    = Yf_Db::get('paycenter_admin');

			$db = new Yf_Utils_DbManage ($Db);

			$sql_path = APP_PATH . '/data/sql/';

			$dir = scandir($sql_path);

			$init_db_row = array();

			foreach ($dir as $item)
			{
				$file = $sql_path . DIRECTORY_SEPARATOR . $item;
				if (is_file($file))
				{
					$flag = $db->import($file, TABEL_PREFIX, 'pay_admin_');
					check_rs($flag, $init_db_row);
				}
			}
      echo "</ol>";
			if (is_ok($init_db_row))
			{
				die('<script>window.location.href="./index.php?met=admin";</script>;');

			}
			else
			{
				$this->view->setMet('msg');

				$msg = '初始化数据库失败!';
				include $this->view->getView();
			}
		}
		else
		{
			location_to('./index.php?met=db&msg=' . urlencode('数据库配置不正确!'));
		}

	}

	public function admin()
	{
		include $this->view->getView();
	}

	public function createAdminAccount()
	{
		$User_BaseModel = new User_BaseModel();

		/*
		$key = Yf_Registry::get('ucenter_api_key');;
		$url       = Yf_Registry::get('ucenter_api_url');
		$app_id    = Yf_Registry::get('ucenter_app_id');
		$server_id = Yf_Registry::get('server_id');
		*/

		$key       = request_string('ucenter_api_key');;
		$url       = request_string('ucenter_api_url');
		$app_id    = request_string('ucenter_app_id', '103');
		$server_id = Yf_Registry::get('server_id');


		//开通ucenter
		//本地读取远程信息
		$formvars              = array();
		$formvars['user_name'] = request_string('user_account');
		$formvars['password']  = request_string('user_password');
		$formvars['app_id']    = $app_id;
		$formvars['server_id'] = $server_id;
		$formvars['is_install'] = 1;

		$formvars['ctl'] = 'Api';
		$formvars['met'] = 'addUserAndBindAppServer';
		$formvars['typ'] = 'json';

		$init_rs = get_url_with_encrypt($key, $url, $formvars);

		if (200 == $init_rs['status'])
		{
			//本地读取远程信息
			$data['user_id']    = $init_rs['data']['user_id']; // 用户帐号
			$data['user_account']    = request_string('user_account'); // 用户帐号
			$data['user_password']   = request_string('user_password'); // 密码：使用用户中心-此处废弃
			$data['user_delete']     = 0; // 用户状态
			$data['rights_group_id'] = 1; // 用户权限组

			if ($User_BaseModel->getOne($data['user_id']))
			{
				$user_id                    = $data['user_id'];
			}
			else
			{
				$user_id                    = $User_BaseModel->addBase($data, true);
			}

			/*
			$Rights_GroupModel = new Rights_GroupModel();
			$data_rights                = $Rights_GroupModel->getRightsGroupList();
			$data_rights                = $data_rights['items'];

			foreach ($data_rights as $key => $val)
			{
				if ($val['rights_group_id'] == $data['rights_group_id'])
				{
					$data['rights_group_name'] = $val['rights_group_name'];
				}
			}
			*/
			$data['user_id'] = $user_id;

			if ($user_id)
			{
				$msg    = 'success';
				$status = 200;

				$data                    = array();
				$data['ucenter_api_key'] = $key;
				$data['ucenter_api_url'] = $url;
				$data['ucenter_app_id']  = $app_id;

				$file = INI_PATH . '/ucenter_api.ini.php';

				if (!Yf_Utils_File::generatePhpFile($file, $data))
				{
					$status = 250;
					$msg    = _('生成配置文件错误!');
				}
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
			$msg    = $init_rs['msg'] ? $init_rs['msg'] : '发生错误, 请检查接口网址是否正确!' ;
			$status = 250;
		}

		if (250 == $status)
		{
			location_go_back($msg);
		}
		else
		{

			//检测lock file
			$lock_file = APP_PATH . '/data/installed.lock';

			if(!file_exists($lock_file))
			{
				file_put_contents($lock_file, '');
			}

			$this->view->setMet('complete');

			include $this->view->getView();
		}

	}
}

/**
 * 检查目录的读写权限
 *
 * @access  public
 * @param   array     $check_dir_row     目录列表
 * @return  array     检查后的消息数组，
 */
function check_dirs_priv($check_dir_row)
{
	$state = array('result' => true, 'detail' => array());

	foreach ($check_dir_row as $dir)
	{
		$file = ROOT_PATH . $dir;

		if (!file_exists($file))
		{
			//$flag = mkdir($file, 0777, true);
		}

		if (is_writable($file))
		{
			$state['detail'][] = array($dir, _('yes'), _('可写'));
		}
		else
		{
			$state['detail'][] = array($dir, _('no'), _('不可写'));
			$state['result'] = false;
		}
	}

	return $state;
}



function check_install_db()
{
	//如果无表,则直接安装 install
	$db_cfg = Yf_Registry::get('db_cfg');
	$state = 1;
	$msg = '';

	if ($db_row = current($db_cfg['db_cfg_rows']['master']['paycenter_admin']))
	{
		try
		{
			$db_id = 'paycenter_admin';
			$Db  = Yf_Db::get($db_id);
			//define("DATABASE", $Dbh->cfg[$db_id]['database']);

			if ($Db->detectDbConnect())
			{
				$state = 2;

				$table_sql = 'SELECT table_name FROM information_schema.tables WHERE table_schema="' . $db_row['database'] .'" AND table_type="BASE TABLE"';

				$table_rows = $Db->getAll($table_sql);

				foreach ($table_rows as $table_row)
				{
					//表存在,则停止安装
					if (TABEL_PREFIX == substr($table_row['table_name'], 0, strlen(TABEL_PREFIX)))
					{
						$state = 9;
						$msg = '数据库信息已经存在,不可以继续安装,请先手动删除存在的表后,执行安装程序!';
						break;
					}
				}

				//判断admin是否设置.
				$admin_user_base = sprintf('%suser_base', TABEL_PREFIX);

				//$admin_user_base
				if (false)
				{
					$state = 10;
				}
			}
		}
		catch(Exception $e)
		{

		}
	}

	return array('state'=>$state, 'msg'=>$msg);
}

?>
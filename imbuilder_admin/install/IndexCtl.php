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

			$msg = _("系统已经安装过了，如果要重新安装，那么请删除install/data/目录下的installed.lock文件");
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
		//$db_row = current($db_cfg['db_cfg_rows']['master']['im_data']);
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
		$file_admin = ROOT_PATH . '/imbuilder_admin/configs/db.ini.php';

		$prefix = htmlspecialchars(request_string('prefix'));

		$data[] = 'define("TABEL_PREFIX", "' . $prefix . '"); //表前缀';
		$data['db_row'] = $db_row;
		$data[] = 'return $db_row';

		if (Yf_Utils_File::generatePhpFile($file, $data) && Yf_Utils_File::generatePhpFile($file_admin, $data))
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
			$Db    = Yf_Db::get('im_data');

			$db = new Yf_Utils_DbManage ($Db);

			$sql_path = APP_PATH . '/data/sql/';

			$dir = scandir($sql_path);

			$init_db_row = array();

			foreach ($dir as $item)
			{
				$file = $sql_path . DIRECTORY_SEPARATOR . $item;
				if (is_file($file))
				{
					$flag = $db->import($file, TABEL_PREFIX, 'imadmin_');
					check_rs($flag, $init_db_row);
				}
			}

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
		//检测lock file
		$lock_file = APP_PATH . '/data/installed.lock';

		if(!file_exists($lock_file))
		{
			file_put_contents($lock_file, '');
		}

		include $this->view->getView();
	}

	public function createAdmin()
	{

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
 
 

 
	if ($db_row = current($db_cfg['db_cfg_rows']['master']['im_data']))
	{
		 
		try
		{
			$db_id = 'im_data';
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
				$admin_user_base = sprintf('%sadmin_user_base', TABEL_PREFIX);

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
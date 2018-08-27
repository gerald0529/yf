<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * Api接口  管理用户开通新    erp设置新开通   用户运行环境:db....
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
	private $fp=null;
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);

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

	//未封装,未来需要从erp中提取出来,放入守护进程中运行
	public function create()
	{
		$Db = Yf_Db::get('root_rights');

		$app_id = $_REQUEST['app_id'];
		$server_id = $_REQUEST['server_id'];

		$db_name = 'erp_' . $server_id;
		$db_user = 'user_' . $server_id;

		$user_name = $_REQUEST['user_name'];
		$user_mobile = $_REQUEST['company_phone'];
		$plantform_url = isset($_REQUEST['plantform_url']) ? $_REQUEST['plantform_url'] : null;

		$config = Yf_Registry::get('db_cfg');

		$db_host                = $config['db_cfg_rows']['master']['root_rights'][0]['host']            ; // 数据库IP
		$db_passwd              = Text_Password::create(10)         ; // 数据库密码

		if (!$db_host || !$db_passwd || !$app_id || !$server_id)
		{
			$this->data->setError('参数错误');
		}
		else
		{

			$rs_row = array();

			$flag = $Db->exec('USE `mysql`');
			array_push($rs_row, false!==$flag);

			//判断数据库是否存在
			$check_db_sql = "SELECT * FROM information_schema.SCHEMATA where SCHEMA_NAME='{$db_name}'";
			$check_db_rows = $Db->getAll($check_db_sql);
			fb($check_db_rows);

			if (!$check_db_rows)
			{
				$Db->startTransaction();

				//创建数据库
				$create_db_sql = 'CREATE DATABASE `' . $db_name . '` CHARACTER SET utf8 COLLATE utf8_general_ci';
				$flag = $Db->exec($create_db_sql);
				array_push($rs_row, false!==$flag);


				//用户是否存在
				$check_user_sql = "SELECT user,host  FROM user WHERE user='{$db_user}'";
				$check_user_rows = $Db->getAll($check_user_sql);
				fb($check_user_rows);

				if (!$check_user_rows)
				{
					$create_user_sql = "GRANT USAGE ON *.* TO '{$db_user}'@'%' IDENTIFIED BY '{$db_passwd}' WITH MAX_QUERIES_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0";
					$flag = $Db->exec($create_user_sql);
					array_push($rs_row, false!==$flag);
				}

				//赋权限
				$create_pri_sql = "GRANT Create Routine, Insert, Lock Tables, References, Select, Drop, Delete, Index, Alter Routine, Create View, Create Temporary Tables, Show View, Trigger, Event, Create, Update, Execute, Grant Option, Alter ON `{$db_name}`.* TO `{$db_user}`@`%`";
				$flag = $Db->exec($create_pri_sql);
				array_push($rs_row, false!==$flag);


				//$user_name  为管理员权限
				
				//初始化数据
				$flag = $Db->exec("USE `{$db_name}`;");
				array_push($rs_row, false!==$flag);
				//创建数据表和导入基础数据
				$file_name='./erp/docs/erpbuilder.sql';

				if (!$this->fp = @fopen($file_name, "r"))
				{
					$this->data->setError("不能打开文件 $file_name 请检查文件是否存在，并且检查该文件夹的权限!");
					return false;
				}

				@set_time_limit(0);
				while($mysql=$this->GetNextSQL())
			    {
					$flag = $Db->exec($mysql);
					array_push($rs_row, false!==$flag);
				}

			    fclose($this->fp);
				
				//添加超管
				$flag = $Db->exec("INSERT INTO `erpbuilder_user_base` (`user_id`, `user_account`, `user_password`, `user_key`, `user_realname`, `user_nickname`, `user_mobile`, `user_email`, `rights_group_id`, `user_rights_ids`, `user_delete`, `user_admin`, `server_id`) VALUES
				(1, '{$user_name}', '96e79218965eb72c92a549dd5a330112', '', '', '', '', '', 1, '', 0, 1, '{$server_id}')");
				array_push($rs_row, false!==$flag);
				
				//导入地区数据
				$file_name='./erp/docs/district.sql';

				//$this->fp = @fopen($file_name, "r") or die("不能打开文件 $file_name 请检查文件是否存在，并且检查该文件夹的权限!");
				if (!$this->fp = @fopen($file_name, "r"))
				{
					$this->data->setError("不能打开文件 $file_name 请检查文件是否存在，并且检查该文件夹的权限!");
					return false;
				}

				while($mysql=$this->GetNextSQL())
			    {
					$flag = $Db->exec($mysql);
					array_push($rs_row, false!==$flag);
				}
			    fclose($this->fp);
				
				fb($rs_row);

				if (is_ok($rs_row) && $Db->commit())
				{
					//写入配置文件
					$db_config_file = INI_PATH . '/db_' . $server_id . '.ini.php';
					$db_config_row = array();
					$db_config_row['host'] = $db_host;
					$db_config_row['port'] = 3306;
					$db_config_row['user'] = $db_user;
					$db_config_row['password'] = $db_passwd;
					$db_config_row['database'] = $db_name;
					$db_config_row['charset'] = 'UTF8';


					$db_config_contents = "<?php\n	return ";
					$db_config_contents .= var_export($db_config_row, true);
					$db_config_contents .= ";\n?>";

					file_put_contents($db_config_file, $db_config_contents);



					$server_config_file = INI_PATH . '/server_' . $server_id . '.ini.php';
					$server_config_row = array();
					$server_config_row['url'] = $plantform_url;


					$db_config_contents = "<?php\n	return ";
					$db_config_contents .= var_export($server_config_row, true);
					$db_config_contents .= ";\n?>";

					file_put_contents($server_config_file, $db_config_contents);



					$this->data->addBody(100, $db_config_row);
				}
				else
				{
					$Db->rollBack();

					$msg = '初始化失败';
					$status = 250;
					$this->data->addBody(100, array(), $msg, $status);
				}
			}
			else
			{
				$msg = '对应的数据库信息已经存在';

				$status = 250;
				$this->data->addBody(100, array(), $msg, $status);
			}
		}

		//添加app server
		/*
		$data['server_id']              = $_REQUEST['server_id']          ; //
		$data['server_prefix']          = $_REQUEST['server_prefix']      ; // 服务器前缀 ：一区
		$data['server_name']            = $_REQUEST['server_name']        ; // 服务器名称
		$data['server_url']             = $_REQUEST['server_url']         ; // 服务器url
		$data['server_order']           = $_REQUEST['server_order']       ; // 服务器列表排序
		$data['app_id']                 = $app_id                         ; // 所属游戏id
		$data['company_id']             = $_REQUEST['company_id']         ; // 运营商id
		$data['server_type']            = $_REQUEST['server_type']        ; // 服类型,1:new,2:hot,3:满 4:维护
		$data['server_stop_start_time'] = $_REQUEST['server_stop_start_time']; // 停服开始时间
		$data['server_stop_end_time']   = $_REQUEST['server_stop_end_time']; // 停服结束时间
		$data['server_stop_tip']        = $_REQUEST['server_stop_tip']    ; // 服务器宕机提示
		$data['app_version_package']    = $_REQUEST['app_version_package']; // CPP中定义的版本, 决定是否显示
		$data['company_name']           = $_REQUEST['company_name']       ; // 公司名称
		$data['company_phone']          = $user_mobile                    ; // 电话
		$data['contacter']              = $_REQUEST['contacter']          ; // 联系人
		$data['sign_time']              = $_REQUEST['sign_time']          ; // 签约时间
		$data['account_num']            = $_REQUEST['account_num']        ; // 账号个数
		$data['db_host']                = $_REQUEST['db_host']            ; // 数据库IP
		$data['db_name']                = $_REQUEST['db_name']            ; // 数据库名
		$data['db_passwd']              = $_REQUEST['db_passwd']          ; // 数据库密码
		$data['upload_path']            = $_REQUEST['upload_path']        ; // 附件存放地址
		$data['business_agent']         = $_REQUEST['business_agent']     ; // 业务代表
		$data['price']                  = $_REQUEST['price']              ; // 费用
		$data['effective_date_start']   = $_REQUEST['effective_date_start']; // 有效期开始与结束
		$data['effective_date_end']     = $_REQUEST['effective_date_end'] ; // 有效期开始与结束1
		*/
	}
	
	private function GetNextSQL()
	{
		$sql="";
		while ($line = @fgets($this->fp, 40960))
		{
			$line = trim($line);
			//以下三句在高版本php中不需要
			$line = str_replace("\\\\","\\",$line);
			$line = str_replace("\'","'",$line);
			$line = str_replace("\\r\\n",chr(13).chr(10),$line);
			$line = stripcslashes($line);
			if (strlen($line)>1)
			{
				if ($line[0]=="-" && $line[1]=="-")
				{
					continue;
				}
			}
			$sql.=$line.chr(13).chr(10);
			if (strlen($line)>0)
			{
				if ($line[strlen($line)-1]==";")
				{
				break;
				}
			}
		}
		return $sql;
	}

    //erp获取用户意见信息
    public function getIdea()
    {
        $server_id = $_REQUEST['server_id'];
        $serviceIdeaModel = new Service_IdeaModel();
        $serviceIdeaModel->sql->setWhere('server_id',$server_id);
        $data = $serviceIdeaModel->getServiceList('*');
        $datas = json_encode($data, true);
        echo $datas;
    }

    //erp添加数据
    public function add()
    {
        $data['title']          = $_REQUEST['title'];
        $data['idea']           = $_REQUEST['idea'];
        $data['creat_time']     = $_REQUEST['creat_time'];
        $data['creat_id']       = $_REQUEST['creat_id'];
        $data['idea_status']    = $_REQUEST['idea_status'];
        $data['server_id']      = $_REQUEST['server_id'];
        $serviceIdeaModel = new Service_IdeaModel();
        $idea_id = $serviceIdeaModel->add($data, true);
        echo $idea_id;
    }

    public function getIdeaById()
    {
        $id = $_POST['id'];
        $serviceIdeaModel = new Service_IdeaModel();
        $serviceIdeaModel->sql->setWhere('idea_id',$id);
        $res = $serviceIdeaModel->get('*');
        $results = json_encode($res, true);
        echo $results;
    }
}
?>

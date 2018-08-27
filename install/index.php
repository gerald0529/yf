<?php 
error_reporting(0);
set_time_limit(0);
function __($str){return $str;}  

$met = $_GET['met'];

include __DIR__.'/Medoo.php';
use Medoo\Medoo;
define('ROOT_PATH',realpath(__DIR__.'/../'));

global $installDBFiles;
$installDBFiles = [
		'/ucenter/ucenter/configs/db.ini.php'=>'ucenter_',
		'/ucenter_admin/ucenter_admin/configs/db.ini.php'=>'ucenter_admin_',

		'/paycenter/paycenter/configs/db.ini.php'=>'pay_',
		'/paycenter_admin/admin/configs/db.ini.php'=>'pay_admin_',

		'/shop/shop/configs/db.ini.php'=>'yf_',
		'/shop_admin/shop_admin/configs/db.ini.php'=>'yf_admin_',
		

];


global $startUrlCheck;
$startUrlCheck = [
		'/ucenter/install/data/ucenter.txt',
		'/ucenter_admin/install/data/ucenteradmin.txt',
 
		'/paycenter/install/data/paycenter.txt',
		'/paycenter_admin/install/data/paycenteradmin.txt',
 
		'/shop/install/data/shop.txt',
		'/shop_wap/cache/shopwap.txt',
		'/shop_admin/install/data/shopadmin.txt',
 
		

];

foreach ($startUrlCheck as $key) {
 		$fs = ROOT_PATH.$key;
 		if(!file_exists($fs)){
 			file_put_contents($fs,date('Y-m-d H:i:s'));
 		}
}

$lock = __DIR__.'/data/lock.txt';


global $db;


function get_db(){
		global $db; 
		if($db){return $db;}
		try 
		{ 

			  $db_row = include ROOT_PATH.'/ucenter/ucenter/configs/db.ini.php'; 

				$db = new Medoo([
				    'database_type' => 'mysql',
				    'database_name' => $db_row['database'],
				    'server' => $db_row['host'],
				    'username' => $db_row['user'],
				    'password' => $db_row['password'],
				    'port' => $db_row['port']?:3306,
				    'command' => [
							'SET SQL_MODE=ANSI_QUOTES'
						]
				]);  
				return $db;

		} 
		catch(Exception $e) 
		{  
			 
		} 
}

if($met != 'admin' && file_exists($lock) )
{

  header("Content-type: text/html; charset=utf-8"); 
	$msg =  "安装已锁定，请删除 data/lock.txt";
	$close = true;
	include __DIR__.'/views/msg.php';
	exit;

}	

 

switch ($met) {

	case 'deleteinstall': 
			$dirs = [
					'/ucenter/install',
					'/ucenter_admin/install',
					'/paycenter/install',
					'/paycenter_admin/install',
					'/shop/install',
					'/shop_admin/install',
			];
			unlink(ROOT_PATH.'/shop_wap/cache/shopwap.txt');
			header("Content-type: text/html; charset=utf-8"); 
			foreach ($dirs as $key => $value) {
				  $redir = ROOT_PATH.$value;
				  if(is_dir($redir)){ 
				  	echo __("删除目录").":".$redir."<br>";
				 		@rmdirs($redir);
				  }
			}
			$msg = __("删除目录成功");
			include __DIR__.'/views/notice.php';

			@rmdirs(ROOT_PATH.'/install');

			exit;
			break;

	case 'admin':
			if(!file_exists($lock))
			{
				file_put_contents($lock ,date('Y-m-d H:i:s'));
			}
			include __DIR__.'/views/admin.php';
			exit;
			break;

	case 'install':    




		$state_row = check_install_db(); 

	 
	  if (9 == $state_row['state'])
		{
			 $msg = $state_row['msg'];
			 include __DIR__.'/views/msg.php';
			 exit;
		}

		foreach( $installDBFiles as $f=>$pre){
				$arr = explode('/',$f);
				$dr = ROOT_PATH.'/'.$arr[1].'/install/data/sql';
				if(is_dir($dr)){
					$installSql[$dr] = $pre;
				} 
		}
		if(count($installSql) != 6)
		{
			  $msg = __('安装SQL文件缺少');
				include  __DIR__.'/views/msg.php';
				exit;
		}

    ob_end_flush(); 
	  include  __DIR__.'/views/install.php';
		foreach ($installSql as $sql_path => $TABEL_PREFIX) { 
					echo str_repeat(" ", 4096);  //以确保到达output_buffering值 
					echo "<li class='line'><span class='yes'><i class='iconfont'></i></span>".
							__("安装...").$TABEL_PREFIX."</li>";
 					ob_flush();
					flush(); 
					$dir = scandir($sql_path);

					$init_db_row = array();

					foreach ($dir as $item)
					{
						$file = $sql_path . DIRECTORY_SEPARATOR . $item;
						if (is_file($file))
						{  
								$flag = import($file, $TABEL_PREFIX); 
						}
					}   
		} 

		$r = $db->error();
		if($r[2]){ 
				var_dump($r);
 				exit;
		}

		//写shop_admin管理员
		//
		$data = [];
		$data['user_id']    =    10001; // 用户帐号
		$data['user_account']    = 'admin'; // 用户帐号
		$data['user_password']   = ""; // 密码：使用用户中心-此处废弃
		$data['user_delete']     = 0; // 用户状态
		$data['rights_group_id'] = 1; // 用户权限组

		$get = $db->select("yf_user_base", [
		    "user_id" 
		], [
		    "user_id" => 10001
		]);
		
		if(!$get){
				$db->query("
					insert into yf_user_base set 
							user_id=10001,
							user_account='admin',
							user_key='t',
							user_delete=0  
							" 
				); 
		}


		$list = [
				 			102 => 'shop',
				 			104 => 'ucenter',
				 			105 => 'paycenter',
				 	];
		$gg = include __DIR__.'/data/datalog.php'; 
 
		foreach($list as $k=>$v){
			  $a = $gg[$v][$v.'_api_url'];
			  $b = $gg[$v][$v.'_admin_api_url'];
			  $key = $gg[$v][$v.'_api_key'];

 				$db->query("
								update ucenter_base_app set app_url='".$a."',  app_admin_url='".$b."' , app_key = '".$key."' where app_id = ".$k."
				 		");

 			 
 				$db->query("
								update pay_user_app set app_url='".$a."',app_key = '".$key."' where app_id = ".$k."
				 		");

		} 

		$admin_rights = __DIR__.'/admin_right.txt';
		if(file_exists($admin_rights)){
				$rs = file_get_contents($admin_rights);
				$db->query("
								update yf_admin_rights_group set rights_group_rights_ids='".$rs."'  where rights_group_id = 1
				 		");
		}
	 

		$db->query("
								update yf_web_config set  config_value=0 where config_key = 'remote_image_status'
				 		");


	 	echo "<li class='line'><span class='yes' style='color:blue'><i class='iconfont'></i></span>".
							__("安装完成，页面即将跳转……")."</li>"; 
	 
 	 	echo "<script>location.href='index.php?met=admin';</script>";			 
		exit;

	  
		break;		

	case 'initDbConfig':  
		include  __DIR__.'/views/initDbConfig.php';
		exit;
		break;		



	case 'db':

		if($_POST){
				 	$db_row = array(
						'host' => 'localhost',
						'port' => '3306',
						'user' => '',
						'password' => '',
						'database' => '',
						'charset' => 'UTF8'
					); 
					$db_row['host'] = trim($_POST['host']);
					$db_row['port'] = trim($_POST['port']);
					$db_row['user'] = trim($_POST['user']);
					$db_row['password'] = trim($_POST['password']);
					$db_row['database'] = trim($_POST['database']); 

					try 
					{ 

							$database = new Medoo([
							    'database_type' => 'mysql',
							    'database_name' => $db_row['database'],
							    'server' => $db_row['host'],
							    'username' => $db_row['user'],
							    'password' => $db_row['password'],
							    'port' => $db_row['port']?:3306,
							]); 

					} 
					catch(Exception $e) 
					{  
							$msg = "数据库配置不正确";
							include  __DIR__.'/views/db.php';
							exit;
					} 

					//检查MYSQL是否是严格模式
					$database->query(" set global sql_mode='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'"); 

					
					
					$ucenter = trim($_POST['ucenter']);
					$ucenteradmin = trim($_POST['ucenteradmin']);
					$paycenter = trim($_POST['paycenter']);
					$paycenteradmin = trim($_POST['paycenteradmin']);
					$shop = trim($_POST['shop']);
					$shopadmin = trim($_POST['shopadmin']);
					$shopwap = trim($_POST['shopwap']);



					if(!$ucenter || !$paycenter || !$shop 
								|| !$ucenteradmin || !$paycenteradmin ||!$shopadmin
								|| !$shopwap
						){
							$msg = "接口配置必须";
							include  __DIR__.'/views/db.php';
							exit;
					} 

 				 	$dataArray = [];
					$api_key  = rand_string(); 
					$dataArray['shop']['shop_api_key'] = $api_key;
					$dataArray['shop']['shop_wap_url'] = $shopwap;
					$dataArray['shop']['shop_api_url'] = $shop;
					$dataArray['shop']['shop_admin_api_url'] = $shopadmin;
					$dataArray['shop']['shop_admin_url'] = $shopadmin;
					$dataArray['shop']['shop_app_id'] = 102; 

					$dataArray['ucenter']['ucenter_api_key'] = $api_key; 
					$dataArray['ucenter']['ucenter_api_url'] = $ucenter; 
					$dataArray['ucenter']['ucenter_app_id'] = 104;
					$dataArray['ucenter']['ucenter_admin_url']  = $ucenteradmin;
					$dataArray['ucenter']['ucenter_admin_api_url']  = $ucenteradmin;

					$dataArray['paycenter']['paycenter_api_key'] = $api_key; 
					$dataArray['paycenter']['paycenter_api_url'] = $paycenter; 
					$dataArray['paycenter']['paycenter_app_id'] = 105;  
 					$dataArray['paycenter']['paycenter_admin_api_url'] = $paycenteradmin;
 					$dataArray['paycenter']['paycenter_admin_url'] = $paycenteradmin;
					$dataArray['paycenter']['paycenter_api_name'] = 'fff';



					$dataArray['shopwap']['ShopUrl'] 				= $shop;
					$dataArray['shopwap']['WapSiteUrl'] 		= $shopwap;
					$dataArray['shopwap']['UCenterApiUrl']  = $ucenter;
					$dataArray['shopwap']['PayCenterWapUrl'] = $paycenter;
					$dataArray['shopwap']['ImApiUrl'] 			= "http://*/index.php";

					$dataArray['shopwap']['copyright'] 			= "Copyright&nbsp;&copy;&nbsp;2007-".date('Y')." 版权所有";
					$dataArray['shopwap']['title']          = "商城触屏版";
					$dataArray['shopwap']['paySiteName']    = "网付宝"; 
					$dataArray['shopwap']['openWeixinLoginInWeixin'] = false;   
					file_put_contents(__DIR__.'/data/datalog.php', "<?php\n return ".var_export($dataArray,true).";");

					$ucenter = remove_php($ucenter);
					$ucenteradmin = remove_php($ucenteradmin);
					$paycenter = remove_php($paycenter);
					$paycenteradmin = remove_php($paycenteradmin);
					$shop = remove_php($shop);
					$shopadmin = remove_php($shopadmin);
					$shopwap = remove_php($shopwap); 

					if(!get_url($ucenter.'/install/data/ucenter.txt')){
							$msg = "Ucenter接口配置有误";
							include  __DIR__.'/views/db.php';
							exit;
					}
					if(!get_url($ucenteradmin.'/install/data/ucenteradmin.txt')){
							$msg = "UcenterAdmin接口配置有误";
							include  __DIR__.'/views/db.php';
							exit;
					} 

					if(!get_url($paycenter.'/install/data/paycenter.txt')){
							$msg = "Paycenter接口配置有误";
							include  __DIR__.'/views/db.php';
							exit;
					}

					if(!get_url($paycenteradmin.'/install/data/paycenteradmin.txt')){
							$msg = "PaycenterAdmin接口配置有误";
							include  __DIR__.'/views/db.php';
							exit;
					} 
 
					if(!get_url($shop.'/install/data/shop.txt')){
							$msg = "Shop接口配置有误";
							include  __DIR__.'/views/db.php';
							exit;
					}
					if(!get_url($shopwap.'/cache/shopwap.txt')){
							$msg = "ShopWap接口配置有误";
							include  __DIR__.'/views/db.php';
							exit;
					} 
  
					if(!get_url($shopadmin.'/install/data/shopadmin.txt')){
							$msg = "ShopAdmin接口配置有误";
							include  __DIR__.'/views/db.php';
							exit;
					}
					
					$flag = true;
					foreach ($installDBFiles as $fs => $prefix) {
						  $file = ROOT_PATH.$fs;
						  unset($data);
						  $data[] = 'define("TABEL_PREFIX", "' . $prefix . '"); //表前缀';
							$data['db_row'] = $db_row;
							$data[] = 'return $db_row';    
						  if ( generatePhpFile ($file, $data))
							{
								$status = 200;
								 
							}
							else
							{
								$status = 250;
								$flag = false;
							}
					} 

					if(!$flag){
							$msg = __('生成配置文件错误!');
							include  __DIR__.'/views/db.php';
							exit;
					}

					//生成其他配置
					//ucenter_api.ini.php shop_api.ini.php paycenter_api.ini.php
					$config_ini = [
							'shop_api.ini.php'=>$dataArray['shop'],
							'paycenter_api.ini.php'=>$dataArray['paycenter'],
							'ucenter_api.ini.php'=>$dataArray['ucenter'],
					]; 
					foreach ($installDBFiles as $fs => $k0){
							$di = substr($fs,0,strrpos($fs,'/'));
						
							foreach ($config_ini as $ini_file => $array) {
											$ini = ROOT_PATH.'/'.$di.'/'.$ini_file;
											$str = "<?php\n"; 
											foreach($array  as $k=>$v){
													if(is_numeric($v)){
														$str.="\$".$k." = ".$v.";\n";
													}else{
														$str.="\$".$k." = '".$v."';\n";
													}
											}
										 	file_put_contents($ini,$str); 
							}
							
					}


					$ini = ROOT_PATH.'/shop_wap/configs/config.php';
					$str = "<?php\n"; 
					foreach($dataArray['shopwap']  as $k=>$v){
							if($v == false){
									$str.="\$".$k." = false;\n";
							}else{
									$str.="\$".$k." = '".$v."';\n";
							}
							
					}
				 	file_put_contents($ini,$str);   
				 	
					Header("Location:index.php?met=initDbConfig"); 
 

		}
 
		include  __DIR__.'/views/db.php';
		exit;
		break;		



	case 'env':
 
		include  __DIR__.'/views/env.php';
		exit;
		break;

	case 'plugin':
 
		include  __DIR__.'/views/plugin.php';
		exit;
		break;		
		

	case 'checkEnv':
				$check_rs = true;

				//扩展
				$loaded_ext_row = get_loaded_extensions();
				$check_ext_row = include __DIR__ . '/configs/check_ext.ini.php';

				foreach ($check_ext_row as $ext_name)
				{
					if (!in_array($ext_name, $loaded_ext_row))
					{
						$check_rs = false;
						break;
					}
				}

				//目录权限
				$check_dir_row = include __DIR__ . '/configs/check_dir.ini.php';
				$dir_rows = check_dirs_priv($check_dir_row);

				//函数检查
				if (!$dir_rows['result'])
				{
					$check_rs = false;
				}

				include  __DIR__.'/views/checkEnv.php';

			exit;
	
	default:
		include __DIR__.'/views/index.php';
		break;
}

function get_url($url){
		$opts = array(  
   		 'http'=>array(  
        'method'=>"GET",  
        'timeout'=>3,  
    ));  

		$context = stream_context_create($opts);       
		return @file_get_contents($url, false, $context);  
}
function remove_php($url){

		return str_replace('/index.php','',$url);
}


function generatePhpFile($file, $row = array())
	{
		$php_start = '<?php' . PHP_EOL;
		$php_end   = '?>' . PHP_EOL;
		$str = $php_start;

		foreach ($row as $key=>$val)
		{
			$val_str = '';

			if (is_array($val))
			{
				$val_str = var_export($val, true);
			}
			elseif (is_string($val))
			{
				if (!is_numeric($key))
				{
					$val_str = untrim($val);
				}
				else
				{
					$val_str = $val;
				}
			}
			else
			{
				$val_str = $val;
			}

			$val_str = str_replace("\"","'",$val_str);

			if (!is_numeric($key))
			{
				$str  = $str . sprintf('$%s = %s; %s', $key, $val_str, PHP_EOL);
			}
			else
			{
				$str  = $str . sprintf('%s; %s', $val_str, PHP_EOL);
			}
		}

		$php_code  = $str . $php_end;

		return file_put_contents($file, $php_code);;
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
			$flag = mkdir($file, 0777, true);
		}

		if (is_writable($file))
		{
			$state['detail'][] = array($dir, __('yes'), __('可写'));
		}
		else
		{
			$state['detail'][] = array($dir, __('no'), __('不可写'));
			$state['result'] = false;
		}
	}

	return $state;
}



function import($sqlfile, $db_prefix='yf_')
{
	get_db();
	global $db;
	static $loop;
	if(!$loop) $loop = 0;
		// sql文件包含的sql语句数组
		$sqls = array();
		$f    = fopen($sqlfile, "rb");

		// 创建表缓冲变量
		$create_table = '';
		if($loop == 0){
			$scr = "
<script>
			function install_bottom()
{
var now = new Date();
var div = document.getElementById('installed'); 
div.scrollTop = div.scrollHeight;
}
</script>
";
			echo $scr."<ol id='installed' name='installed' style='height: 600px;overflow-y: auto;'>";
		}
		while (!feof($f))
		{
			// 读取每一行sql
			$line = fgets($f);

			if (substr($line, 0, 2) == '/*' || substr($line, 0, 2) == '--' || $line == '')
			{
				continue;
			}

			$create_table .= $line;
			if (substr(trim($line), -1, 1) == ';')
			{
				// 默认一键安装，不支持修改表前缀
				//$create_table = str_replace($db_prefix_base, $db_prefix, $create_table);  
				//执行sql语句创建表
				 
				$flag = $db->query($create_table);  
				echo str_repeat(" ", 4096);  //以确保到达output_buffering值
				
				$pattern = '/CREATE TABLE.*`(.*)`/i';
			  preg_match($pattern, $create_table, $matches);  
			  $show_table_created = $matches[1];  

				if($show_table_created){
					echo "<li class='line'><span class='yes'><i class='iconfont'></i></span>".
							__("创建数据库")." ".$show_table_created." ".__("成功")."</li>";
				} 
				echo "<script>install_bottom();</script>";
				ob_flush();
				flush(); 

				// 清空当前，准备下一个表的创建
				unset($create_table);
				$create_table = '';
			}

			unset($line);
		}
	 
 	  $loop++;
		fclose($f);
		
		return true;
}


 /**
	* 随机字符
	* @param string $j 位数 　 
	* @return string
	*/
function rand_string($j = 8){
		$string = "";
	    for($i=0;$i < $j;$i++){
	        srand((double)microtime()*1234567);
	        $x = mt_rand(0,2);
	        switch($x){
	            case 0:$string.= chr(mt_rand(97,122));break;
	            case 1:$string.= chr(mt_rand(65,90));break;
	            case 2:$string.= chr(mt_rand(48,57));break;
	        }
	    }
		return $string; //to uppercase
	}


	 /**
     * 删除指定目录及其下的所有文件和子目录，失败抛出异常
     *
     * 用法：
     * @code php
     * // 删除 my_dir 目录及其下的所有文件和子目录
     * Helper_Filesys::rmdirs('/path/to/my_dir');
     * @endcode
     *
     * 注意：使用该函数要非常非常小心，避免意外删除重要文件。
     *
     * @param string $dir 要删除的目录
     *
     * @throw Q_RemoveDirFailedException
     */
    function rmdirs($dir)
    {
        $dir = realpath($dir);
        if ($dir == '' || $dir == '/' || (strlen($dir) == 3 && substr($dir, 1) == ':\\'))
        {
            // 禁止删除根目录
            return;
        }
        // 遍历目录，删除所有文件和子目录
        if(false !== ($dh = opendir($dir)))
        {
            while(false !== ($file = readdir($dh)))
            {
                if($file == '.' || $file == '..')
                {
                    continue;
                }
                $path = $dir . '/' . $file;

                if (is_dir($path))
                {
                    rmdirs($path);
                }
                else
                { 
                    unlink($path);
                }
            }
            closedir($dh);
            if (@rmdir($dir) == false)
            {
                return;
            }
        }
        else
        {
            return;
        }
    }


function check_install_db()
{

	  get_db();
	  global $db;
	  global $installDBFiles; 
		try
		{ 
 				$db_row = include ROOT_PATH.'/ucenter/ucenter/configs/db.ini.php' ;
				$state = 2;
 				$table_sql = "SELECT table_name FROM information_schema.tables WHERE table_schema='" . $db_row['database'] ."' AND table_type='BASE TABLE'";
  			$table_rows = $db->query($table_sql)->fetchAll(); 
   			foreach($installDBFiles as $fs=>$TABEL_PREFIX){
  						foreach ($table_rows as $table_row)
							{
								//表存在,则停止安装
								if ($TABEL_PREFIX == substr($table_row['table_name'], 0, strlen($TABEL_PREFIX)))
								{
									$state = 9;
									$msg = '数据库信息已经存在,不可以继续安装,请先手动删除存在的表后,执行安装程序!';
									break;
								}
							}
  			} 
			 
		}
		catch(Exception $e)
		{

		}
 

	return array('state'=>$state, 'msg'=>$msg);
}
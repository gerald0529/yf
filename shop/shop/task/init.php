<?php 
/**
vi /etc/crontab  

0/1 0 0 0 0 /usr/bin/php /website/local/shop/shop/task/init.php 
其中0改成*
crontab需要加入上面的配置

*/
$dir = dirname(__FILE__).'/../../shop/configs/config.ini.php';
if (is_file($dir))
{
	require_once $dir;
}
else
{
	die('请先运行index.php,生成应用程序框架结构！');
}
$time = time();
$Base_CronModel = new Base_CronModel();
	 
$cron_rows = $Base_CronModel
			->getByWhere(array('cron_active' => 1), array('cron_nexttransact' => 'ASC'));
if(!$cron_rows){
	exit('no cronjob!');
	return;
}
foreach($cron_rows as $cron_row)	{
	if (
		   $cron_row['cron_script'] && $cron_row['cron_nexttransact'] <= $time
 
		)
	{
 		$script = APP_PATH . '/task/crons/' . $cron_row['cron_script'];
 		if (file_exists($script))
		{
			 
			$cron_scripts[$script] = $script;
		}
  		$cron_row['cron_lasttransact'] = $time;
		$Base_CronModel->editNextTaskTime($cron_row);
		 
	}

}	 
 
if($cron_scripts){
	$cronFiles = checkPhpScript($cron_scripts);
}

if($cronFiles){
	foreach ($cronFiles as $key => $value) {
		include $value;
	}
}
 
 
file_put_contents(dirname(__FILE__)."/~cronjob.log", 
	var_export($cronFiles,true) ." \n runtime:".date('Y-m-d H:i:s'));




function checkPhpScript($cronFiles){
	exec ( "ps aux|grep .php", $scripts ); 
	file_put_contents(dirname(__FILE__)."/~on_runing_scripts.log", 
	var_export($scripts,true) ." \n runtime:".date('Y-m-d H:i:s'));

	if($cronFiles && $scripts){
		foreach ($cronFiles as $key => $value) {
			foreach ($scripts as $k => $v) {
				if(strstr($v,$value)){
					unset($cronFiles[$key]);
				}
			}
		}
		if(is_array($cronFiles) && count($cronFiles)>0){
			return $cronFiles;
		}
	}
	
	return $cronFiles;
}

 
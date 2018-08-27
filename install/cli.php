<?php 
//仅限liunx使用
error_reporting(0);
set_time_limit(0);
ini_set('safe_mode','off');
 
define('ROOT_PATH',realpath(__DIR__.'/../'));

$check_dir_row = include __DIR__.'/configs/check_dir.ini.php';
$flag = true;
foreach($check_dir_row as $dir) {
		$realpath = ROOT_PATH.$dir;
		if(!is_dir($realpath)){
			mkdir($realpath,0777,true);
		}
		
		@system("sudo chmod -R 777  ".$realpath."  ",$status);
	  echo "chmod -R 777 ".$realpath."\n"; 
		if($status == 'true')  
		{  
		     
		}  
		else  
		{  
		    $flag = false;
		}   
}
if($flag == false){
		echo "\n failed chmod 777";
}
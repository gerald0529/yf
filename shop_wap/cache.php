<?php 
/**
 * footer里面有统计时，JS中的document.write如果在页面渲染完成后，
 * 将会替换整个body内容。修正 以上问题。
 */
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));
$html = $_POST['html'];
$key = $_SERVER['SERVER_NAME'];
 
$file = __DIR__.'/cache/'.$key.'.footer.php';
$time = $_GET['cache_time']?:5*60;
if($html){
	$r = filemtime($file);
	if($r+$time >= time()){
		 echo "read cache;";
		 exit;
	}
	file_put_contents($file, $html);
	if(!is_writable($file)){
		echo "footer:".$file." not writeable";
		exit;
	}else{
		 echo "create cache;";
		 exit;
	}
}
if($_GET['delete'] == 1){
	unlink($file);
	echo "delete cache file :".$file." finished!";
	exit;
}
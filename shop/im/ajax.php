<?php include __DIR__.'/config.php';
/*
收到的聊天信息格式是 <p></p>

在输入是个URL的时候需要把p拿掉

weichat:sunkangchina
*/
error_reporting(0);
$reg = "/((https|http|ftp|rtsp|mms)?:\/\/)[^\s]+/";


$str = trim($_POST['str']);



$a = substr($str,0,3);
$b = substr($str,-4);
$fg  = false;
if($a == "<p>" && $b == "</p>"){
	$str = substr($str,3,-4);
	$fg  = true;
}
 


preg_match_all($reg,$str,$out);


$mt = $out[0];
 
if($mt){
	foreach($mt as $v){
		$v = trim($v);
		$link = "<a href='".$v."'>$v</a>";
		$str = str_replace($v, $link, $str);
	}
}



$str = strip_tags($str, "<a> <img>");  

if($fg  == true){
		echo "<p>".$str."</p>";
}else{
	echo $str;
}



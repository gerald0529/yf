<?php   
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

header("Access-Control-Allow-Credentials: true"); 
$origin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';  
header("Access-Control-Allow-Origin:$origin");  
 

function menu_active($name){
	 if($name == '/index.html' && $_SERVER['REQUEST_URI']=='/')
	 {
	 	return true;
	 }
   if(strpos($_SERVER['REQUEST_URI'],$name)!==false){
   		return true;
   }
   return false;
}

$host = '';
if (isset($_SERVER['HTTP_HOST']))
{
	$host = $_SERVER['HTTP_HOST'];
} 
  

include __DIR__.'/weixin_login.php';



$config_js = __DIR__."/../js/config_".$_SERVER['SERVER_NAME'].".js";
if(file_exists($config_js)){
	$_js_header = file_get_contents($config_js); 
	$_js_header = '<script type="text/javascript">'.$_js_header.'</script>';
}else{
   include __DIR__.'/../configs/config.php'; 
   ob_start();
	include __DIR__.'/js.php';  
	 $_js_header =  ob_get_contents();
	ob_clean();
}
  
$_js_header = str_replace('~',"",$_js_header);
$_js_header = str_replace('~',"",$_js_header);
  
  

include __DIR__.'/../messages/I18N.php';


ob_start();

 




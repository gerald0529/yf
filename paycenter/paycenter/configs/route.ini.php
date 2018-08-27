<?php 
/**
 * DoNot change code 
 * 这区别的代码不要改动
 */
include __DIR__.'/../YfRouteMatch.php';
$uri = $_SERVER['REQUEST_URI'];
$openRoute = false;
if(!$openRoute) return;

/**
 * 以上内容不要修改
 */

/**
 * Here You can setting routes 
 * 用户自己设置路由，此处需要设置
 */
$routes = [
	'goods'=>"Goods_Goods@goodslist",
	'goods/<cat_id:\d+>'=>"Goods_Goods@goodslist",
	'goods/<type:\w+>/<gid:\d+>'=>"Goods_Goods@goods",
	'shop/<id:\d+>'=>"Shop@index",
	'brand'=>"Goods_Brand@index",
	'brand/<brand_id:\d+>'=>"Goods_Brand@brandList",
 
	

	

];



/**
 *  DoNot change code 
 * 以下内容不要修改
 */
foreach($routes as $k=>$v){
	YfRouteMatch::all($k, $v);   
}


//

if(in_array($uri ,['/index.php' , '/','/index.php/'] ) || strpos($uri ,'index.php?ctl=')!==false){
	return;
} 
/**
 * run router
 */
try {  
	$rt = YfRouteMatch::run();
	if($rt){
		if(!str_replace('\\','',$rt['ctl'])){
			$rt['ctl'] = 'Index';
		}
		$_REQUEST['ctl'] = $rt['ctl']; 
		$_REQUEST['met'] = $rt['met'];  
		YfRouteMatch::toRequest($_GET);
		YfRouteMatch::toRequest($_POST); 
	}

	 
	 
}catch (Exception $e) {  
	 
} 

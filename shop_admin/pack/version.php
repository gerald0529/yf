<?php 

/**
 * 返回version
 * @auth: sunkangchina
 */


//每次升级要改这里的
include __DIR__.'/config.php';

 
 

$gl = glob(dirname(__FILE__)."/pack_*.php");

$pack = $gl[count($gl)-1];


if($pack){
	$r = include $pack;   
	if($r['pack'] && $r['text']){
		$rt['version'] = $version['version'].$r['pack'];
		$rt['text']  = $version['text'];
		$version = $rt;
	} 
} 

return $version;


 
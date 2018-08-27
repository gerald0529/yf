<?php 
function mkdirs($dir)
{    
	return is_dir($dir) or (mkdirs(dirname($dir)) and mkdir($dir, 0777));
}
function makethumb($srcFile,$dstFile,$dstW,$dstH,$watermark=true)
{ 
	global $config;
	include_once("./image_class.php");
	$t=new cls_image();
	$t-> watermark=$watermark;
	$t-> make_thumb($srcFile, $dstFile,$dstW,$dstH);
	unset($t);
}
?>
<?php include __DIR__.'/config.php';
/*
直接取IM上传成功的URL，此文件暂时不用
过滤附件，只取URL

weichat:sunkangchina
*/
error_reporting(0);
 

$str = trim($_POST['str']);

 


$img = get_imgtag($str);


echo '<img imtype="msg_attach_src" src="'.$img[1].'" style="max-width:130px; max-height:200px;" onclick="open_img(this)">';
exit;
 


function get_imgtag($content,$all=true,$return_img_tag = false){ 
		$preg = '/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i'; 
		preg_match_all($preg,$content,$out);
		$i = 2;
		if($return_img_tag === true){
			$i = 0;
		}
		$img = $out[$i];  
		if($all === true){
			return $img;
		}else if($all === false){
			return $img[0]; 
		}
		return $out[0];
} 

<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}
 
class User 
{
	 
	 static $_obj;
	 /**
	  * 返回用户等级
	  */
	 static function info($arr = [] , $return = []){  
	 		$key = md5(json_encode($arr));
	 		if(!$_obj[$key]){
	 			$_obj[$key] = []; 
	 		}
	 		$rs = $_obj[$key];
	 		if(!$rs){
	 			return;
	 		}
	 		if($return && is_array($return)){
	 			foreach ($return as $value) {
	 				 $new[$value] = $rs[$value];
	 			}
	 			$rs = $new;
	 		}

			return $rs;
	 }	
	  




}

 
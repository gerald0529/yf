<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}
 
class ImLog
{

	 /**
	  * ImLog::list([
	  * 
	  * 	'name'=>'',
	  * 	'is_read'=>1,
	  * 	'group'=>1, //分组显示同一个发送者信息只有一行。
	  * 	
	  * ]);
	  * @param   array        $arr
	  * @return  [type]
	  * @weichat sunkangchina
	  * @date    2018-01-16
	  */
	 static function getList($arr = []){ 
	 	$name = $arr['name'];
	 	$is_read = $arr['is_read'];
	 	$group = $arr['group'];
		if($is_read && in_array($is_read,[0,1])){
			$is_read = 0;
		} 
		$sql  = "SELECT [##] FROM ".TABEL_PREFIX.'user_msg WHERE  '; 

		$view = false;
		if($view == true){
			$sql .= " ( msg_sender = :msg_sender AND msg_receiver=:msg_receiver) OR (
					msg_sender = :msg_sender1 AND msg_receiver=:msg_receiver1
			) ";
			$bind_value[':msg_sender'] = $msg_sender;
			$bind_value[':msg_receiver'] = $msg_receiver; 
			$bind_value[':msg_sender1'] = $msg_receiver;
			$bind_value[':msg_receiver1'] = $msg_sender; 
		}else{
			$sql .= " ( msg_sender = :msg_sender OR msg_receiver=:msg_receiver) ";
			$bind_value[':msg_sender'] = $name;
			$bind_value[':msg_receiver'] = $name; 
		}


		$sql .= " AND is_read=:is_read";
		$bind_value[':is_read'] = $is_read;  
		if($group){
		 $sql .= "  GROUP BY msg_sender  ";
		} 
		$sql .= "  ORDER BY date_created DESC ";	 

		$sql_count = str_replace('[##]',"count(*) num",$sql);
		$sql = str_replace('[##]',"count(*) num,msg_sender,msg_receiver,
			msg_content,date_created,msg_type
			",$sql); 
		$limit = false;
		if($limit){
			$sql = $sql.' LIMIT '.$limit;
		}

		$db = new YFSQL();
		return $db->pager([
			'sql'=>$sql,
			'sql_count'=>$sql_count,
			'value'=>$bind_value,
			'size'=>$size,
			'reset'=>true,
		]);
	 }


}

 
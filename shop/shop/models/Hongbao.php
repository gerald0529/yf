<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}
 
class Hongbao 
{

	/**
	 * 分页显示
	 * 
	 $rs = Hongbao::pager([ 
			'size'=>2
		]);
	返回如下格式

	 Array
	(
	    [data] => Array
	        (
	            [0] => Array
	                (
	                    
	                ) 

	        )

	    [nums] => 265 总数
	    [page] => 1 当前页
	    [pager] => 分页条
	)
	 
	 
	 */
	static function pager($arr=[]){

		$sql  = "SELECT * FROM yf_redpacket_base ORDER BY redpacket_id desc";

		 
		$db = new YFSQL;
		$rs = $db->pager([
			'sql'=>$sql,
			'value'=>$value,
			'size'=>$arr['size']
		]);

		return $rs;

	}
	/**
	 * 确认订单页
	 * 根据订单总金额判断是否有可用平台红包
	 *
	 * 返回红包描述如： 满1000减20
	 * Hongbao::plat([
	 * 		'pay_amount'=>800,
	 * 		'limt'=>1
	 * ]);
	 *
	 * 返回列表。数组形式：如下
	 *
	   [ 
		   [
				'price'=> 20,
			 	'desc'=>满1000减20,
			 	'status'=>1, 0
			 	'end_time'=>
		   ] 
	   ] 
	 * 
	 * Hongbao::plat([
	 * 		'pay_amount'=>800, 
	 * ]);
	 * 
	 */
	 static function plat($arr = []){ 
	 		$user_id = $arr['user_id']?:Perm::$userId;
	 		$pay_amount = $arr['pay_amount'];
	 		$start_time = $arr['start_time']?:time();
	 		$end_time = $arr['end_time']?:time();  
	 		$order_by = $arr['order_by']?:'redpacket_price DESC ';
	 		$limit = $arr['limit'];
	 		if($limit){
	 			$limit = " LIMIT ".$limit;
	 		}


	 		$sql  = "SELECT * FROM yf_redpacket_base WHERE 
	 		 		redpacket_state   = 1 AND
	 		 		redpacket_owner_id = ".$user_id." AND  
	 				redpacket_start_date <= ".$start_time." AND
					redpacket_end_date >= ".$end_time."
					redpacket_t_orderlimit <= ".$pay_amount." AND	
					ORDER BY ".$order_by." ".$limit; 

			if($limit == 1){
				return $limit?:$list['redpacket_desc'];
			}
			if($list){
				foreach ($list as $key => $value) {
					 $lists[] = [
					 	'price'=>$value['redpacket_price'],
					 	'desc'=>$value['redpacket_desc'],
					 	'status'=>$value['redpacket_state'],
					 	'end_time'=>$value['redpacket_end_date'], 
					 ];
				}
				return $lists;
			}
			return;
	 }
	 /**
	  * 领取红包　
	  * 
	  */
	 static function save($arr = []){
	 	//$level = User::info(['id'=>1],['level']);

	 }





}

 
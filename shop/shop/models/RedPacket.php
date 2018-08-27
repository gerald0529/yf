<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}
 
class RedPacket
{

	/**
	 * 根据查询条件获取红包列表
	 *
	 * RedPacket::user([
	 * 		'user_id'=>10003,	//用户id
	 * 		'pay_amount'=>800,  //订单金额
	 * 		'limit' => 100  //红包使用时的订单限额
	 * 		'start_date'=>'2017-10-20 10:00:00'，	//开始时间
	 * 		'end_date'=>'2017-10-20 10:00:00'，	//结束时间
	 * 		'redpacket_state'=>1，	//红包状态
	 * 		'order_by'=>'redpacket_price DESC'，//排序条件
	 * 		'limt'=>1			//显示条数
	 * ]);
	 *
	 * 返回列表。数组形式：如下
	 *
	   [ 
		   [
				'id' => 264			//红包id
				'title' => 十元红包	//红包标题
				'desc' => 快领		//红包描述
				'price' => 10		//红包面额
				'limit' => 100  //红包使用时的订单限额
				'start_date' => 2017-10-20 10:00:00		//红包有效期开始时间
				'end_date' => 2018-10-20 10:00:00		//红包有效期结束时间
				'status' => 1		//红包状态(1-未用,2-已用,3-过期)
				'active_date' => 1		//红包发放日期
		   ]
	   ] 
	 *
	 * 
	 */
	 static function lists($arr = [])
	 {
	 		$user_id = $arr['user_id'];
	 		$pay_amount = $arr['pay_amount'];
		 	$redpacket_state = $arr['redpacket_state'];
	 		$start_date = $arr['start_date'];
	 		$end_date = $arr['end_time'];
	 		$order_by = $arr['order_by']?:'redpacket_id DESC ';
	 		$limit = $arr['limit'];
	 		if($limit)
			{
	 			$limit = " LIMIT ".$limit;
	 		}

		 $sql  = "SELECT * FROM ".TABEL_PREFIX."redpacket_base WHERE 1=1 ";
		 if($user_id)
		 {
			 $sql .= " AND redpacket_owner_id   = :user_id  ";
			 $bind_value[':user_id'] = $user_id;
		 }
		 if($pay_amount)
		 {
			 $sql .= " AND redpacket_t_orderlimit <= :pay_amount  ";
			 $bind_value[':pay_amount'] = $pay_amount;
		 }
		 if($redpacket_state)
		 {
			 $sql .=" AND redpacket_state in ( ".addslashes($redpacket_state)." )";
		 }
		 if($start_date)
		 {
			 $sql .= " AND redpacket_start_date <= :start_date  ";
			 $bind_value[':start_date'] = $start_date;
		 }
		 if($end_date)
		 {
			 $sql .= " AND redpacket_end_date >= :end_date  ";
			 $bind_value[':end_date'] = $end_date;
		 }
		 $sql .= "  ORDER BY ".$order_by." ".$limit;

		 $db = new YFSQL();
		 $list = [];
		 $list = $db->find($sql,$bind_value);

			if($limit == 1){
				return $limit?:$list;
			}
			if($list){
				$redpacket_list = [];
				foreach ($list as $key => $value) {
					$redpacket_list[] = [
						'id'=>$value['redpacket_id'],
						'title'=>$value['redpacket_title'],
						'desc'=>$value['redpacket_desc'],
					 	'price'=>$value['redpacket_price'],
						'start_date'=>$value['redpacket_start_date'],
						'end_date'=>$value['redpacket_end_date'],
						'limit'=>$value['redpacket_t_orderlimit'],
					 	'status'=>$value['redpacket_state'],
					 	'active_date'=>$value['redpacket_active_date'],
					 ];
				}
				return $redpacket_list;
			}
			return [];
	 }

	/**
	 * 根据订单金额判断红包列表中的红包是否可用
	 *
	 * RedPacket::isable([
	 * 		'data'=>[	//红包列表
	 * 					[
							'id' => 264			//红包id
							'title' => 十元红包	//红包标题
							'desc' => 快领		//红包描述
	 						'limit' => 100  //红包使用时的订单限额
							'price' => 10		//红包面额
							'start_date' => 2017-10-20 10:00:00		//红包有效期开始时间
							'end_date' => 2018-10-20 10:00:00		//红包有效期结束时间
							'status' => 1		//红包状态(1-未用,2-已用,3-过期)
							'active_date' => 1		//红包发放日期
							]
	 * 				],
	 * 		'pay_amount'=> 100.00,  //订单金额
	 * ]);
	 *
	 * 返回列表。数组形式：如下
	 *
	[
		[
		'id' => 264			//红包id
		'title' => 十元红包	//红包标题
		'desc' => 快领		//红包描述
		'price' => 10		//红包面额
	 	'limit'=> 100		//红包使用时的订单限额
		'start_date' => 2017-10-20 10:00:00		//红包有效期开始时间
		'end_date' => 2018-10-20 10:00:00		//红包有效期结束时间
		'status' => 1		//红包状态(1-未用,2-已用,3-过期)
		'active_date' => 1		//红包发放日期
	 	'isable' =>1		//1可用 0 不可用
		]
	]
	 *
	 *
	 */
	static function isable($arr = []){
		$data = $arr['data'];
		$pay_amount = $arr['pay_amount'];
		$able = array();
		$disable = array();
		foreach($data as $key => $val)
		{
			$data[$key]['isable'] = 1;
			if($val['limit'] > $pay_amount)
			{
				$data[$key]['isable'] = 0;
			}

			if($val['status'] != RedPacket_BaseModel::UNUSED)
			{
				$data[$key]['isable'] = 0;
			}

			if($val['end_date'] < get_date_time())
			{
				$data[$key]['isable'] = 0;
			}

			//将整个代金券分成两个数组，一个是可使用数组，一个是不可使用数组
			if($data[$key]['isable'])
			{
				$able[] = $data[$key];
			}
			else
			{
				$disable[] = $data[$key];
			}
		}

		//将两个数组和成一个数组
		$data = array_merge($able,$disable);

		return $data;
	}


	/**
	 * 用户使用平台红包后修改状态
	 *
	 * RedPacket::change([
	 * 		'id'=>10003,	//红包id
	 * 		'order_id'=> DD-123-456789-1212121,  //订单金额
	 * ]);
	 *
	 * 返回列表。数组形式：如下
	 *
	[
	[
	'id' => 264			//红包id
	'title' => 十元红包	//红包标题
	'desc' => 快领		//红包描述
	'price' => 10		//红包面额
	'start_date' => 2017-10-20 10:00:00		//红包有效期开始时间
	'end_date' => 2018-10-20 10:00:00		//红包有效期结束时间
	'status' => 1		//红包状态(1-未用,2-已用,3-过期)
	'active_date' => 1		//红包发放日期
	]
	]
	 *
	 *
	 */
	static function change($arr = []){
		$id = $arr['id'];
		$order_id = $arr['order_id'];
		$status = $arr['status'];

		$db = new YFSQL();

		if(!$id || !$order_id || !$status){
			return;
		}
		$sql = "UPDATE ".TABEL_PREFIX."redpacket_base SET  ";
		if($order_id)
		{
			$sql .= " redpacket_order_id  = :order_id  ,";
			$bind_value[':order_id'] = $order_id;
		}
		if($status)
		{
			$sql .= " redpacket_state = :status  ,";
			$bind_value[':status'] = $status;
		}
		$sql = substr($sql,0,-1);
		$sql .= "  WHERE   redpacket_id=:redpacket_id";
		$bind_value[':redpacket_id'] = $id;

		$db->save($sql,$bind_value);

		unset($bind_value);
		//根据id 查找redpacket_base信息，获取需要修改的redpacket_temp_id
		$sql = "SELECT redpacket_t_id  FROM ".TABEL_PREFIX."redpacket_base WHERE redpacket_id=:id";
		$bind_value[':id'] = $id;
		$temp_id = $db->find_one($sql,$bind_value);
		$temp_id = $temp_id['redpacket_t_id'];
		//更新redpacket_temp表中的信息
		$sql = "UPDATE ".TABEL_PREFIX."redpacket_template SET redpacket_t_used = redpacket_t_used+1";
		$db->save($sql);
	}







}

 
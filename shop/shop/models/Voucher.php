<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}
 
class Voucher
{

	/**
	 * 获取用户可用的店铺代金券
	 * 根据店铺订单总金额获取用户可以使用的店铺代金券
	 *
	 * Voucher::userVoucher([
	 * 				'user_id'=>10010,	//用户id
	 * 				'shop_id' =>123,	//店铺id
	 * 				'voucher_state'=>1, //代金券状态值(1-未用,2-已用,3-过期,4-收回)
	 * 				'voucher_start_date'=>'2019-07-17 23:59:59' //代金券开始时间
	 * 				'voucher_end_date'=>'2019-07-17 23:59:59' //代金券结束时间
	 * 				'order_price'=>'183.23' //店铺订单价格
	 * 				'order_by'=>'voucher_id DESC', //查询排序
	 * 				'limit'=>1,  //查询限制数量
	 * ]);
	 *
	 * 返回列表。数组形式：如下
	 *
	   [ 
		   [
				'id' => 140      //代金券di
				'title' => hp01的代金券    //代金券标题
				'desc' => 呵呵       //代金券描述
				'start_time' => 2017-07-22 12:30:42      //代金券开始时间
				'end_time' => 2018-07-17 23:59:59       //代金券结束时间
				'price' => 100      //代金券面额
				'limit' => 600.00    //代金券使用时的订单限额
				'status' => 1     //代金券状态(1-未用,2-已用,3-过期,4-收回)
				'active_date' => 2017-08-10 13:57:38  //代金券发放日期
		   ] 
	   ] 
	 *
	 * 
	 */
	 static function user($arr = []){ 
		 $voucher_owner_id = $arr['user_id']?:Perm::$userId;
		 
		 $voucher_shop_id = $arr['shop_id'];
		 $voucher_state = $arr['voucher_state'];
		 $voucher_start_date = $arr['voucher_start_date'];
		 $voucher_end_date = $arr['voucher_end_date'];
		 $order_price = $arr['order_price']?:0;
		 $order_by = $arr['order_by']?:'voucher_id DESC ';
		 $limit = $arr['limit'];
 
		 

		 if($limit)
		 {
			 $limit = " LIMIT ".$limit;
		 }

		 $sql  = "SELECT * FROM ".TABEL_PREFIX."voucher_base WHERE 1=1
	 		 		AND voucher_owner_id = :voucher_owner_id";
	 	 $bind_value[':voucher_owner_id'] = $voucher_owner_id;
		 if($voucher_state)
		 {
			 $sql .=" AND voucher_state in ( ".addslashes($voucher_state)." )";
 		 }
		 if($voucher_shop_id)
		 {
			 $sql .=" AND voucher_shop_id = :voucher_shop_id";
			 $bind_value[':voucher_shop_id'] = $voucher_shop_id;
		 }
		 if($order_price)
		 {
			 $sql .= " AND voucher_limit <= :voucher_limit ";
			 $bind_value[':voucher_limit'] = $order_price;
		 }
		 if($voucher_start_date)
		 {
			 $sql .= " AND voucher_start_date <= :voucher_start_date  ";
			 $bind_value[':voucher_start_date'] = $voucher_start_date;
		 }
		 if($voucher_end_date)
		 {
			 $sql .= " AND voucher_end_date >= :voucher_end_date  ";
			 $bind_value[':voucher_end_date'] = $voucher_end_date;
		 }
		 $sql .= "  ORDER BY ".$order_by." ".$limit;
 
		 $db = new YFSQL();
		 $list = $db->find($sql,$bind_value);

		 if($limit == 1)
		 {
			 return $limit?:$list;
		 }

		 $voucher_list = [];
		 if($list){
			 foreach ($list as $key => $value) {
				 $voucher_list[] = [
					 'id'=>$value['voucher_id'],
					 'title'=>$value['voucher_title'],
					 'desc'=>$value['voucher_desc'],
					 'start_time'=>$value['voucher_start_date'],
					 'end_time'=>$value['voucher_end_date'],
					 'price'=>$value['voucher_price'],
					 'limit'=>$value['voucher_limit'],
					 'status'=>$value['voucher_state'],
					 'active_date'=>$value['voucher_active_date'],
				 ];
			 }

		 }

		 return $voucher_list;
	 }


	/**
	 * 获取店铺可领取的代金券
	 * 根据店铺id获取可以使用的店铺代金券
	 *
	 * Voucher::userVoucher([
	 * 				'method'=>10010,	//用户id
	 * 				'shop_id' =>123,	//店铺id
	 * 				'state'=>1, //代金券状态值(1-未用,2-已用,3-过期,4-收回)
	 * 				'end_date'=>'2019-07-17 23:59:59' //代金券开始时间
	 * 				'start_date'=>'2019-07-17 23:59:59' //代金券结束时间
	 * 				'order_price'=>'183.23' //店铺订单价格
	 * 				'order_by'=>'voucher_id DESC', //查询排序
	 * 				'limit'=>1,  //查询限制数量
	 * ]);
	 *
	 * 返回列表。数组形式：如下
	 *
	[
		[
			'id' => 79         //代金券模板id
			'title' => 满299减20     //代金券模板名称
			'desc' => 人情味        //代金券模板描述
			'start_time' => 2017-10-23 15:52:32   //代金券模版有效期开始时间
			'end_time' => 2023-10-31 23:59:59     //代金券模版有效期结束时间
			'price' => 20			//代金券模版面额
			'limit' => 299.00		//代金券使用时的订单限额
			'status' => 1			//代金券模版状态(1-有效,2-失效)
			'giveout' => 20			//模版已发放的代金券数量
			'points' => 0			//兑换所需积分
			'eachlimit' => 0		//每人限领张数
			'customimg' => https://shop.local.yuanfeng021.com/image.php/shop/data/upload/media/d3aabd05be45670d48e2685d1e1f5992/10104/51/image/20171023/1508745150397379.png!200x200.png	 	//自定义代金券模板图片
			'method' => 3		//代金券领取方式，1-积分兑换(默认)，2-卡密兑换，3-免费领取
			'recommend' => 0	//推荐状态，0-为不推荐，1-推荐
		]
	]
	 *
	 *
	 */
	static function shop($arr = []){
		$method = $arr['method']?:Voucher_TempModel::GETFREE;  //代金券领取方式，1-积分兑换(默认)，2-卡密兑换，3-免费领取
		$shop_id = $arr['shop_id'];  //店铺id
		$state = $arr['state']?:Voucher_TempModel::VALID;  //代金券模版状态(1-有效,2-失效)
		$end_date = $arr['end_date'];  //代金券模版状态(1-有效,2-失效)
		$start_date = $arr['start_date'];  //代金券模版状态(1-有效,2-失效)
		$price = $arr['price'];		//订单金额
		$order_by = $arr['order_by']?:'voucher_t_price DESC ';
		$limit = $arr['limit'];

		$sql  = "SELECT * FROM ".TABEL_PREFIX."voucher_template WHERE 1=1
	 		 		AND voucher_t_access_method   = :voucher_t_access_method 
	 		 		AND shop_id = :shop_id 
	 		 		AND voucher_t_state = :voucher_t_state";

	 	$bind_value[':voucher_t_access_method'] = $method;
	 	$bind_value[':shop_id'] = $shop_id;
	 	$bind_value[':voucher_t_state'] = $state;

		if($price)
		{
			$sql .=" AND voucher_t_limit <= :voucher_t_limit ";
			$bind_value[':voucher_t_limit'] = $price;

		}
		if($end_date)
		{
			$sql .=" AND voucher_t_end_date >= :voucher_t_end_date  ";
			$bind_value[':voucher_t_end_date'] = $end_date;
		}
		if($start_date)
		{
			$sql .= " AND voucher_t_start_date <= :voucher_t_start_date  ";
			$bind_value[':voucher_t_start_date'] = $start_date;
		}
		$sql .= " ORDER BY ".$order_by." ".$limit;

		$db = new YFSQL();
		$list = $db->find($sql ,$bind_value);

		$shop_list = [];
		if($list){
			foreach ($list as $key => $value) {
				$shop_list[] = [
					'id'=>$value['voucher_t_id'],
					'title'=>$value['voucher_t_title'],
					'desc'=>$value['voucher_t_desc'],
					'start_time'=>$value['voucher_t_start_date'],
					'end_time'=>$value['voucher_t_end_date'],
					'price'=>$value['voucher_t_price'],
					'limit'=>$value['voucher_t_limit'],
					'status'=>$value['voucher_t_state'],
					'giveout'=>$value['voucher_t_giveout'],
					'points'=>$value['voucher_t_points'],
					'eachlimit'=>$value['voucher_t_eachlimit'],
					'customimg'=>$value['voucher_t_customimg'],
					'method'=>$value['voucher_t_access_method'],
					'recommend'=>$value['voucher_t_recommend'],
				];
			}
		}

		return $shop_list;
	}

	/**
	 * 根据订单金额判断代金券列表中的代金券是否可用
	 *
	 * Voucher::isable([
	 * 				'data'=>[   //代金券数组列表
									[
	 								'id' => 79         //代金券模板id
									'title' => 满299减20     //代金券模板名称
									'desc' => 人情味        //代金券模板描述
									'start_time' => 2017-10-23 15:52:32   //代金券模版有效期开始时间
									'end_time' => 2023-10-31 23:59:59     //代金券模版有效期结束时间
									'price' => 20			//代金券模版面额
									'limit' => 299.00		//代金券使用时的订单限额
									'status' => 1			//代金券模版状态(1-有效,2-失效)
									'giveout' => 20			//模版已发放的代金券数量
									'points' => 0			//兑换所需积分
									'eachlimit' => 0		//每人限领张数
									'customimg' => https://shop.local.yuanfeng021.com/image.php/shop/data/upload/media/d3aabd05be45670d48e2685d1e1f5992/10104/51/image/20171023/1508745150397379.png!200x200.png	 	//自定义代金券模板图片
									'method' => 3		//代金券领取方式，1-积分兑换(默认)，2-卡密兑换，3-免费领取
									'recommend' => 0	//推荐状态，0-为不推荐，1-推荐
	 								]
								],
	 * 				'order_price'=>'183.23' //店铺订单价格
	 * ]);
	 *
	 * 返回列表。数组形式：如下
	 *
	[
		[
		'id' => 79         //代金券模板id
		'title' => 满299减20     //代金券模板名称
		'desc' => 人情味        //代金券模板描述
		'start_time' => 2017-10-23 15:52:32   //代金券模版有效期开始时间
		'end_time' => 2023-10-31 23:59:59     //代金券模版有效期结束时间
		'price' => 20			//代金券模版面额
		'limit' => 299.00		//代金券使用时的订单限额
		'status' => 1			//代金券模版状态(1-有效,2-失效)
		'giveout' => 20			//模版已发放的代金券数量
		'points' => 0			//兑换所需积分
		'eachlimit' => 0		//每人限领张数
		'customimg' => https://shop.local.yuanfeng021.com/image.php/shop/data/upload/media/d3aabd05be45670d48e2685d1e1f5992/10104/51/image/20171023/1508745150397379.png!200x200.png	 	//自定义代金券模板图片
		'method' => 3		//代金券领取方式，1-积分兑换(默认)，2-卡密兑换，3-免费领取
		'recommend' => 0	//推荐状态，0-为不推荐，1-推荐
	 	'isable'=> 1 		//1可用，0不可用
		]
	]
	 */
	/*
	 * 排序：
	 * 1.订单小计满足店铺代金券消费金额在上
	 * 2.订单小计不满足店铺代金券消费金额在下
	 * 3.满足代金券金额排序根据立减金额从小到大排序
	 * 4.满足代金券金额，且立减金额相同根据到期时间由近及远排列
	 * 5.不满代金券金额排列根据立减金额从小到大排列
	 * 6.不满足代金券金额，且立减金额相同根据到期时间由近及远排序
	 * */
	static function isable($arr = [])
	{
		$data = $arr['data'];
		$order_price = $arr['order_price'];
		$able = array();
		$disable = array();

		foreach($data as $key => $val)
		{
			$data[$key]['isable'] = 1;
			if($val['limit'] > $order_price)
			{
				$data[$key]['isable'] = 0;
			}
			if($val['status'] != Voucher_BaseModel::UNUSED)
			{
				$data[$key]['isable'] = 0;
			}
			if($val['end_time'] < get_date_time())
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




}

 
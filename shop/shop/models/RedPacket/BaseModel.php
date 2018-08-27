<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class RedPacket_BaseModel extends RedPacket_Base
{
	const UNUSED  = 1;   	//未使用
	const USED    = 2;      //已使用
	const EXPIRED = 3;  	//过期
	

	public static $redpacketState = array(
		self::UNUSED => "未用",
		self::USED => "已用",
		self::EXPIRED => "过期"
	);

	/**
	 * 读取分页列表
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getRedPacketList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
        $expire_row = array();
		foreach ($data["items"] as $key => $value)
		{
			$data["items"][$key]["redpacket_state_label"] = __(self::$redpacketState[$value["redpacket_state"]]);

            if (strtotime($value['redpacket_end_date']) < time())
            {
                $data['items'][$key]['redpacket_state']        = self::EXPIRED;
                $data['items'][$key]['redpacket_state_label'] = __(self::$redpacketState[self::EXPIRED]);
                $expire_row[]                                     = $value['redpacket_id'];
            }
		}

        $this->editRedPacket($expire_row, array('redpacket_state'=>self::EXPIRED));

		return $data;
	}

	//获取用户所有的平台优惠券数量
	public function getAllRedPacketCountByUserId($user_id)
	{
		$cond_row['redpacket_owner_id'] = $user_id;
		return $this->getNum($cond_row);
	}

	//获取用户可用的平台优惠券数量
	public function getAvaRedPacketCountByUserId($user_id)
	{
		$cond_row['redpacket_owner_id']      = $user_id;
		$cond_row['redpacket_start_date:<='] = get_date_time();
		$cond_row['redpacket_end_date:>=']   = get_date_time();
		$cond_row['redpacket_state']         = self::UNUSED;
		return $this->getNum($cond_row);
	}

	public function getRedPacketNumByWhere($cond_row)
	{
        return $this->getNum($cond_row);
	}

	/**
	 * 读取列表
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getConfigList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->getByWhere($cond_row, $order_row, $page, $rows);
	}

	//获取用户可使用的店铺平台优惠券
	public function getUserOrderRedPacketByWhere($user_id)
	{
		$cond_row                     	= array();
		$cond_row['redpacket_owner_id'] = $user_id;
		$cond_row['redpacket_state']    = self::UNUSED;
		$order_row['redpacket_id'] 		= 'DESC';
		$rows                      = $this->getByWhere($cond_row, $order_row);
		if ($rows)
		{
			$expire_redpacket = array();
			foreach ($rows as $key => $value)
			{
				if (strtotime($value['redpacket_end_date']) < time())
				{
					$expire_redpacket[] = $value['redpacket_id'];
					unset($rows[$key]); //过期的平台优惠券
				}
			}

			$this->editRedPacket($expire_redpacket, array('redpacket_state' => self::EXPIRED));
		}
		return $rows;
	}

	//平台优惠券使用后，更改状态
	public function changeRedPacketState($redpacket_id, $order_id)
	{
		$rs_row = array();

		$field_row['redpacket_order_id'] = $order_id;
		$field_row['redpacket_state']    = RedPacket_BaseModel::USED;
		$update_flag                   = $this->editRedPacket($redpacket_id, $field_row);
		check_rs($update_flag, $rs_row);

		$redpacket_row = $this->getOne($redpacket_id);

		if ($redpacket_row) //更新平台优惠券模板中平台优惠券已使用数量
		{
			$RedPacket_TempModel = new RedPacket_TempModel();
			$edit_flag         = $RedPacket_TempModel->editRedPacketTemplate($redpacket_row['redpacket_t_id'], array('redpacket_t_used' => 1), true);
			check_rs($edit_flag, $rs_row);
		}

		return is_ok($rs_row);
	}

	/**
	 * 读数量
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCount($cond_row = array())
	{
		return $this->getNum($cond_row);
	}

	/**
	 * 计算确认订单页使用红包之后的价格
	 * @param $data
	 * @param $user_rate
	 * @param $is_discount
	 * @param $increase_shop_row
	 * @param $shop_voucher_row
	 * @return array
	 */
	public function computeRedPacket($shop_order_goods_row,$rpacket_id)
	{
		$order_price = $shop_order_goods_row['order_price'];
		unset($shop_order_goods_row['order_price']);
		//1.检验此红包是否有效可用
		$cond_row_rpt = array();
		$cond_row_rpt['redpacket_id']               = $rpacket_id;
		$cond_row_rpt['redpacket_owner_id']         = Perm::$userId;
		$cond_row_rpt['redpacket_state']            = RedPacket_BaseModel::UNUSED;
		$cond_row_rpt['redpacket_t_orderlimit:<=']  = $order_price;
		$cond_row_rpt['redpacket_start_date:<=']    = get_date_time();
		$cond_row_rpt['redpacket_end_date:>=']      = get_date_time();
		$redpacket_base = $this->getOneByWhere($cond_row_rpt); 
		//存在有效的红包信息
		if($redpacket_base)
		{
			//修正订单总价格，订单商品总价格
			foreach ($shop_order_goods_row as $rptkey => $val)
			{
				//计算每个店铺享受到的红包金额
				$order_rpt_price = round((($val['shop_pay_amount']  / $order_price) * $redpacket_base['redpacket_price']),2);
				$shop_order_goods_row[$rptkey]['shop_pay_amount'] 			= $val['shop_pay_amount'] - $order_rpt_price;	//修改订单商品总价
				$shop_order_goods_row[$rptkey]['redpacket_code'] 			= $redpacket_base['redpacket_code']; //红包编码
				$shop_order_goods_row[$rptkey]['redpacket_price'] 			= $redpacket_base['redpacket_price']; //红包面额
				$shop_order_goods_row[$rptkey]['rpt_id']		  			= $rpacket_id;
				$shop_order_goods_row[$rptkey]['order_rpt_price']			= $order_rpt_price;					//红包抵扣订单金额

				/*//每件商品的红包优惠额
				$j = 1;
				$goods_num = count($val['goods']);
				$goods_rpt_acc = 0 ;
				foreach($val['goods'] as $gk=>$gv)
				{
					//每件商品的优惠券优惠额
					if($j < $goods_num)
					{
						$goods_reduce_price 	=  number_format(($order_rpt_price*$gv['goods_pay_amount']/$val['shop_pay_amount']), 2, '.', '');//一种商品优惠的价格
						$goods_pay_price 		=  $gv['goods_pay_amount'] - $goods_reduce_price;
						$shop_order_goods_row[$rptkey]['goods'][$gk]['goods_pay_amount'] = $goods_pay_price;  		 			//每件商品的实际支付金额
						$shop_order_goods_row[$rptkey]['goods'][$gk]['goods_pay_price'] = round(($goods_pay_price/$gv['goods_num']),2);  	//每件商品的实际支付金额

						$goods_rpt_acc += $goods_reduce_price;
					}
					elseif($j == $goods_num)
					{
						$goods_reduce_price 	=  	$order_rpt_price - $goods_rpt_acc; //最后一件商品将享有剩余的红包金额
						$goods_pay_price 		= 	$gv['goods_pay_amount'] - $goods_reduce_price;
						$shop_order_goods_row[$rptkey]['goods'][$gk]['goods_pay_amount'] = $goods_pay_price;  		 //每件商品的实际支付金额
						$shop_order_goods_row[$rptkey]['goods'][$gk]['goods_pay_price'] = round(($goods_pay_price/$gv['goods_num']),2);  		 //每件商品的实际支付金额
					}
					$j++;
				}*/
			}
			$shop_order_goods_row['order_price'] =  $order_price - $redpacket_base['redpacket_price'];
			return $shop_order_goods_row;
		}
		else
		{
			return false;
		}
	}

}

?>
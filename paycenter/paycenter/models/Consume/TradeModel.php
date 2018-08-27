<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Consume_TradeModel extends Consume_Trade
{
	/**
	 * 读取分页列表
	 *
	 * @param  int $consume_trade_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getTradeList($consume_trade_id = null, $page=1, $rows=100, $sort='asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$consume_trade_id_row = array();
		$consume_trade_id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($consume_trade_id_row)
		{
			$data_rows = $this->getTrade($consume_trade_id_row);
		}

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}

	/**
	 * 根据订单号读取信息
	 *
	 * @param  int $order_id 订单id
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getTradeByOrderId($order_id)
	{
		$cond_row = array('order_id'=>$order_id);

		$row = array();
		$rows = $this->getMultiCond($cond_row);
		if ($rows)
		{
			$row = reset($rows);
		}

		return $row;
	}

	public function getTradeByState($user_id = null,$status = null,$type = null)
	{
		if($status)
		{
			$this->sql->setWhere('order_state_id',$status);
		}
		if($type == 1)//1-卖家
		{
			$this->sql->setWhere('seller_id',$user_id);
		}
		if($type == 2)//2-买家
		{
			$this->sql->setWhere('buy_id',$user_id);
		}

		$data = $this->getTrade("*");
		return $data;
	}

	public function getConsumeTradeByOid($order_id = null)
	{
		$this->sql->setWhere('order_id',$order_id);
		$data = $this->getTrade("*");

		$data = current($data);

		return $data;
	}

	public function editConsumeTrade($order_id = null,$edit_uorder_row = array())
	{
        if(!is_array($order_id)){
            $order_ids = explode(",",$order_id);
            $order_ids[] = $order_id;
        }else{
            $order_ids = $order_id;
        }
        
		//修改支付订单支付类型
        $Union_OrderModel = new Union_OrderModel();
		$uorder_row = $Union_OrderModel->getByWhere(array('inorder:IN' => $order_ids));
		$uorder_id_row = array_column($uorder_row,'union_order_id');
		
		$Union_OrderModel->editUnionOrder($uorder_id_row,$edit_uorder_row);
        
        //更新交易记录表的支付类型
        if($edit_uorder_row['payment_channel_id'] == Payment_ChannelModel::BAITIAO){
            $edit_uorder_row['trade_payment_amount'] = 0;
        }
        $retsult = $this->editTrade($order_ids,$edit_uorder_row);
        
        return $retsult;
	}

	//白条订单还款
	//$user_id  还款用户id
	//$user_return_credit 还款金额
	public function returnCredit($user_id,$user_return_credit)
	{
		//查找出所有用白条支付并且trade_payment_amount 小于 order_payment_amount
		$sql = "
					SELECT
						*
					FROM
						" . TABEL_PREFIX . "consume_trade where buyer_id=".$user_id." and payment_channel_id=".Payment_ChannelModel::BAITIAO." and trade_payment_amount < order_payment_amount ORDER BY trade_create_time asc
					";
		$rows = $this->sql->getAll($sql);

		foreach($rows as $key => $val)
		{
			$edit_row = array();
			$diff = $val['order_payment_amount'] - $val['trade_payment_amount'];
			//如果该笔订单剩余应还金额大于还款金额，则该笔订单部分还款。否则该笔订单全额还款。
			if($diff >= $user_return_credit)
			{
				$edit_row['trade_payment_amount'] = $val['trade_payment_amount'] + $user_return_credit;
				$user_return_credit = 0;
			}
			else
			{
				$edit_row['trade_payment_amount'] = $val['order_payment_amount'];
				$user_return_credit = $user_return_credit - $diff;
			}

			$result = $this->editTrade($val['consume_trade_id'],$edit_row);

			if($user_return_credit == 0)
			{
				break;
			}

		}

		return $result;

	}

	public function getTradeId($symbol = null)
	{
		$sql = "
					SELECT
						consume_trade_id
					FROM
						" . TABEL_PREFIX . "consume_trade where payment_channel_id=9 ". $symbol ."
					";
		$rows = $this->sql->getAll($sql);

		if($rows)
		{
			$rows = array_column($rows,'consume_trade_id');
		}

		return $rows;
	}

    //添加交易订单信息
    public function addConsumeTrade($data)
    {
        $check_row = array();
        $consume_trade_id     = $data['consume_trade_id'];  //交易订单id
        $order_id             = $data['order_id'];      //商户订单id
        $buy_id               = $data['buy_id'];        //买家id
        $pay_user_id          = $data['pay_user_id'];   //付款人id
        $buyer_name			  = $data['buyer_name'];    //付款人名称
        $seller_id            = $data['seller_id'];     //商家用户id
        $seller_name		  = $data['seller_name'];   //商家用户名称
        $order_state_id       = $data['order_state_id'];//订单状态id
        $payment_channel_id   = $data['payment_channel_id'];//支付渠道
        $trade_type           = $data['trade_type'];    //交易类型 1：担保交易 2：直接交易
        $order_payment_amount = $data['order_payment_amount'];  //总付款额度
        $trade_payment_amount = $data['trade_payment_amount'];  //实付金额，在线支付金额
        $trade_payment_money  = $data['trade_payment_money'];   //余额支付金额
        $trade_payment_online = $data['trade_payment_online'];  //在线支付金额
        $trade_remark         = $data['trade_remark'];          //备注
        $trade_create_time    = $data['trade_create_time'];     //创建时间
        $trade_pay_time       = $data['trade_pay_time'];        //支付时间
        $trade_title		  = $data['trade_title'];       //标题
        $app_id               = $data['from_app_id'];  //订单来源
        $order_commission_fee = $data['order_commission_fee'];  //佣金
        $notify_data          = $data['notify_data'];     //支付回调参数信息

        //1.添加订单信息
        $add_row                         = array();
        $add_row['consume_trade_id']     = $consume_trade_id;
        $add_row['order_id']             = $order_id;
        $add_row['buyer_id']             = $buy_id;
        $add_row['seller_id']             = $seller_id;
        $add_row['pay_user_id']          = $pay_user_id;
        $add_row['order_state_id']       = $order_state_id;
        $add_row['trade_type_id']        = Trade_TypeModel::PAY;
        $add_row['payment_channel_id']   = $payment_channel_id;
        $add_row['app_id']               = $app_id;
        $add_row['trade_type']           = $trade_type;
        $add_row['order_payment_amount'] = $order_payment_amount;
        $add_row['trade_payment_amount'] = $trade_payment_amount;
        $add_row['trade_payment_money']  = $trade_payment_money;
        $add_row['trade_date']           = date('Y-m-d');
        $add_row['trade_year']           = date('Y');
        $add_row['trade_month']          = date('m');
        $add_row['trade_day']            = date('d');
        $add_row['trade_title']         = $trade_title;
        $add_row['trade_remark']         = $trade_remark;
        $add_row['trade_create_time']    = $trade_create_time;
        $add_row['trade_pay_time']       = $trade_pay_time;
        $add_row['trade_amount']         = $order_payment_amount;
        $add_row['trade_commis_amount']  = $order_commission_fee;

        $flag1 = $this->addTrade($add_row);
        check_rs($flag1,$check_row);

        //2.生成合并支付订单
        $uorder      = "U" . date("Ymdhis", time()) . rand(100, 999);  //18位
        $union_add_row = array();
        $union_add_row['union_order_id'] = $uorder;
        $union_add_row['inorder']        = $order_id;
        $union_add_row['trade_payment_amount']  = $trade_payment_amount;
        $union_add_row['create_time']        = date("Y-m-d H:i:s");
        $union_add_row['buyer_id']        = $buy_id;
        $union_add_row['trade_title']        = $trade_title;
        $union_add_row['trade_desc']        = $trade_title;
        $union_add_row['order_state_id']  = $order_state_id;
        $union_add_row['pay_time']  = date("Y-m-d H:i:s");
        $union_add_row['payment_channel_id']  = $payment_channel_id;
        $union_add_row['app_id']  = $app_id;
        $union_add_row['trade_type_id']  = Trade_TypeModel::PAY;
        $union_add_row['union_money_pay_amount']  = $trade_payment_money;
        $union_add_row['union_online_pay_amount']  = $trade_payment_online;
        $union_add_row['notify_data']  = $notify_data;

        $Union_OrderModel = new Union_OrderModel();
        $flag2 = $Union_OrderModel->addUnionOrder($union_add_row);
        check_rs($flag2,$check_row);

        //3.生成交易明细（付款方）
        $Consume_RecordModel = new Consume_RecordModel();
        $record_add_buy_row                  = array();
        $record_add_buy_row['order_id']      = $order_id;
        $record_add_buy_row['user_id']       = $buy_id;
        $record_add_buy_row['user_nickname'] = $buyer_name;
        $record_add_buy_row['record_money']  = $order_payment_amount;
        $record_add_buy_row['record_date']   = date('Y-m-d');
        $record_add_buy_row['record_year']	   = date('Y');
        $record_add_buy_row['record_month']	= date('m');
        $record_add_buy_row['record_day']		=date('d');
        $record_add_buy_row['record_title']  = $trade_title;
        $record_add_buy_row['record_time']   = date('Y-m-d H:i:s');
        $record_add_buy_row['record_paytime']   = date('Y-m-d H:i:s');
        $record_add_buy_row['trade_type_id'] = Trade_TypeModel::PAY;
        $record_add_buy_row['record_payorder'] = $uorder;
        $record_add_buy_row['user_type']     = 2;	//付款方
        $record_add_buy_row['record_status'] = $order_state_id;

        $flag3 = $Consume_RecordModel->addRecord($record_add_buy_row);
        check_rs($flag3,$check_row);

        //3.生成交易明细（收款方）
        $record_add_seller_row                  = array();
        $record_add_seller_row['order_id']      = $order_id;
        $record_add_seller_row['user_id']       = $seller_id;
        $record_add_seller_row['user_nickname'] = $seller_name;
        $record_add_seller_row['record_money']  = $order_payment_amount;
        $record_add_seller_row['record_date']   = date('Y-m-d');
        $record_add_seller_row['record_year']	   = date('Y');
        $record_add_seller_row['record_month']	= date('m');
        $record_add_seller_row['record_day']		=date('d');
        $record_add_seller_row['record_title']  = $trade_title;
        $record_add_seller_row['record_time']   = date('Y-m-d H:i:s');
        $record_add_seller_row['record_paytime']   = date('Y-m-d H:i:s');
        $record_add_seller_row['trade_type_id'] = Trade_TypeModel::PAY;
        $record_add_seller_row['record_payorder'] = $uorder;
        $record_add_seller_row['user_type']     = 1;	//收款方
        $record_add_seller_row['record_status'] = $order_state_id;

        $flag4 = $Consume_RecordModel->addRecord($record_add_seller_row);
        check_rs($flag4,$check_row);

        $flag = is_ok($check_row);

        $result['flag'] = $flag;
        $result['uorder'] = $uorder;

        return $result;

    }
}
?>
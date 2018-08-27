<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Consume_DepositModel extends Consume_Deposit
{
	public static $status = array(
/* 		"TRADE_FINISHED" => '未付款', */
		"TRADE_FINISHED" => '成功',
	);

	const TRADE_STATUS_SUCCESS = 2;
	/**
	 * 读取分页列表
	 *
	 * @param  int $order_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getDepositList($order_id = null, $page=1, $rows=100, $sort='asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$order_id_row = array();
		$order_id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($order_id_row)
		{
			$data_rows = $this->getDeposit($order_id_row);
		}

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}
	 public function getDepositList1($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
		foreach ($data["items"] as $key => $value)
		{

			$data["items"][$key]["deposit_trade_status"] = _(Consume_DepositModel::$status[$value["deposit_trade_status"]]);
		}
		return $data;
	}
	/**
	 * 支付完成接口调用
	 *
	 * @param  array $notify_row  回调通知数据
	 * @return bool  处理结果
	 * @access public
	 */
	public function processDeposit($notify_row)
	{
		$order_id = $notify_row['order_id'];

		$deposit_row = $this->getOne($order_id);

		if (!$deposit_row)
		{
			//增加充值信息
			$flag = $this->addDeposit($notify_row);
		}
		else
		{
			//edit , 同步处理的时候, 数据可能确实,此处更新, 是否更新,判断数据
			$deposit_data = array();
			$deposit_data = $notify_row;
			unset($deposit_data['order_id']);

			//异步完成,则不更新
			if (!$deposit_row['deposit_async'])
			{
				$flag = $this->editDeposit($order_id, $deposit_data);
			}
			else
			{
				$flag = 0;
			}
		}

		if (false !== $flag)
		{
			$deposit_row = $this->getOne($order_id);
		}
		else
		{
			return false;
		}

		//待走流程
		if ($deposit_row)
		{
			if (0 == $deposit_row['deposit_state'])
			{
				$time = time();

				//订单信息
				$Consume_TradeModel = new Consume_TradeModel();
				$trade_row = $Consume_TradeModel->getTradeByOrderId($order_id);

				if ($trade_row && 1==$trade_row['order_state_id'])
				{
					$user_id = $trade_row['buy_id'];

					$flag_row = array();

					//用户账户增加充值额度
					$User_ResourceModel = new User_ResourceModel();
					$user_res_row = $User_ResourceModel->getOne($user_id);

					$user_res_data = array();


					//需要开启事务 warning : 事务不应该在此处开启, 考虑到使用唯一,暂如此处理
					$Consume_TradeModel->sql->startTransactionDb();

					if ($user_res_row)
					{
						$user_res_data['user_money'] = $user_res_row['user_money'] + $deposit_row['deposit_total_fee'];
						$flag_row[] = $User_ResourceModel->editResource($user_id, $user_res_data); //where
					}
					else
					{
						$user_res_data['user_money'] = $deposit_row['deposit_total_fee'];
						$user_res_data['user_id'] = $user_id;
						$flag_row[] = $User_ResourceModel->addResource($user_res_data);
					}

					$consume_record_row = array();
					//$consume_record_row['consume_record_id'] = date('ymd');
					$consume_record_row['order_id'] = $order_id;
					$consume_record_row['user_id']  = $user_id;
					$consume_record_row['user_nickname']  = '';
					$consume_record_row['record_money']  = $deposit_row['deposit_total_fee'];
					$consume_record_row['record_date']  = date('Y-m-d', $time);


					$consume_record_row['record_year']  = date('Y', $time);
					$consume_record_row['record_month']  = date('m', $time);
					$consume_record_row['record_day']  = date('d', $time);

					$consume_record_row['record_title']  = $deposit_row['deposit_subject'];
					$consume_record_row['record_desc']  = $deposit_row['deposit_body'];
					$consume_record_row['record_time']  = date('Y-m-d H:i:s', $time);
					$consume_record_row['trade_type_id']  = Trade_TypeModel::DEPOSIT;


					//写入充值流水
					$Consume_RecordModel = new Consume_RecordModel();
					$flag_row[] = $Consume_RecordModel->addRecord($consume_record_row);

					//订单扣除流水
					//订单消费流水
					if (Trade_TypeModel::SHOPPING == $trade_row['trade_type_id'])
					{
						$consume_record_row['trade_type_id']  = Trade_TypeModel::SHOPPING;
						$consume_record_row['record_money']  = -$deposit_row['deposit_total_fee'];

						$flag_row[] = $Consume_RecordModel->addRecord($consume_record_row);
					}


					//订单处理
					//更改订单状态
					$trade_data = array();
					$trade_data['order_state_id'] = Order_StateModel::ORDER_PAYED;
					$flag_row[] = $Consume_TradeModel->editTrade($trade_row['consume_trade_id'], $trade_data); //where

					$deposit_data = array();
					$deposit_data['deposit_state'] = 1;
					$flag_row[] = $this->editDeposit($order_id, $deposit_data);

					if (is_ok($flag_row) && $Consume_TradeModel->sql->commitDb())
					{
						return true;
					}
					else
					{
						$Consume_TradeModel->sql->rollBackDb();
						return false;
					}
				}
				else
				{
					//记录数据,异常
					$deposit_data = array();
					$deposit_data['deposit_state'] = 9;

					$flag = $this->editDeposit($order_id, $deposit_data);

					if ($flag)
					{
						return true;
					}
					else
					{
						return false;
					}
				}
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	}
	
    /**
	 * 支付完成后,异步通知商城,更新订单状态
	 *
	 * @param  array $order_id  需要修改状态的订单
	 * @return bool  处理结果
	 * @access public
	 */
	public function notifyShop($order_id = null,$user_id = null)
	{
        $rs_row = array();
		
		//修改本地的订单信息

		//修改合并订单的状态
		$Union_OrderModel = new Union_OrderModel();
       
		//修改合并订单中的订单支付状态
		$union_order = $Union_OrderModel->getOne($order_id);
        //白条
        $bt_flag = $this->checkBt(array('trade_payment_amount'=>$union_order['trade_payment_amount'],'payment_channel_id'=>$union_order['payment_channel_id']));
        if (($union_order['trade_payment_amount'] == $union_order['union_online_pay_amount'] + $union_order['union_money_pay_amount'] + $union_order['union_cards_pay_amount']) || $bt_flag)
        {
            $Union_OrderModel->sql->startTransactionDb();
    
            $edit_row = array();
            $edit_row['order_state_id'] = Order_StateModel::ORDER_PAYED;
            $edit_row['pay_time'] = date('Y-m-d H:i:s');

			$flag = $Union_OrderModel->editUnionOrder($order_id,$edit_row);

            check_rs($flag, $rs_row);
    
            $inorder = $union_order['inorder'];
    
            $uorder_id = $order_id;
            $order_id = explode(",",$inorder);
            $order_id = array_filter($order_id);
            if(count($order_id) > 1)
			{
                //修改单个合并订单状态
                $uorder_row = $Union_OrderModel->getByWhere(array('inorder:IN' => $order_id));
                $uorder_id_row = array_column($uorder_row,'union_order_id');
                $edit_uorder_row = array();
                $edit_uorder_row['order_state_id'] = Order_StateModel::ORDER_PAYED;
                $edit_uorder_row['pay_time'] = date('Y-m-d H:i:s');

				$flag = $Union_OrderModel->editUnionOrder($uorder_id_row,$edit_uorder_row);
                check_rs($flag, $rs_row);
            }

            //修改订单表中的交易状态
            $order_edit_row = array();
            $order_edit_row['trade_pay_time'] = date('Y-m-d H:i:s');
            $order_edit_row['order_state_id'] = Order_StateModel::ORDER_PAYED;
			if($user_id)
			{
				$order_edit_row['pay_user_id'] = $user_id;
			}

    
            $Consume_TradeModel = new Consume_TradeModel();
            $flag = $Consume_TradeModel->editTrade($order_id,$order_edit_row);
            check_rs($flag, $rs_row);
    
            //修改交易明细中的订单状态
            $Consume_RecordModel = new Consume_RecordModel();
            $record_row = $Consume_RecordModel->getByWhere(array('order_id:IN'=> $order_id));
            $record_id_row = array_column($record_row,'consume_record_id');
			if($union_order['trade_type_id'] == Trade_TypeModel::PAY)
			{
				$edit_consume_record['record_status'] = RecordStatusModel::RECORD_FINISH;
			}
			else
			{
				$edit_consume_record['record_status'] = RecordStatusModel::RECORD_WAIT_SEND_GOODS;
			}

            $edit_consume_record['record_payorder'] = $uorder_id;
            $edit_consume_record['record_paytime'] = date('Y-m-d H:i:s');
            $flag = $Consume_RecordModel->editRecord($record_id_row,$edit_consume_record);

            check_rs($flag, $rs_row);
            
            if($union_order['union_money_pay_amount'] > 0){
                //修改用户的资源状态
                $User_ResourceModel = new User_ResourceModel();
                //1.用户资源中订单金额冻结(现金)
                fb($union_order['union_money_pay_amount']);
                $flag = $User_ResourceModel->frozenUserMoney($user_id,$union_order['union_money_pay_amount']);
                check_rs($flag, $rs_row);
            }
            if($union_order['union_cards_pay_amount'] > 0){
                $User_ResourceModel = new User_ResourceModel();
                //2.用户资源中订单金额冻结（卡）
                $flag = $User_ResourceModel->frozenUserCards($user_id,$union_order['union_cards_pay_amount']);
                check_rs($flag, $rs_row);
            }
            //修改白条余额
            if($bt_flag){
                $User_ResourceModel = new User_ResourceModel();
                $user_credit_availability = $union_order['trade_payment_amount']*(-1);
                $flag = $User_ResourceModel->editResource($user_id,array('user_credit_availability'=>$user_credit_availability),true);
                check_rs($flag, $rs_row);
            }

            if (is_ok($rs_row) && $Union_OrderModel->sql->commitDb())
            {
				/****************************************************************************************************/
				$user_id_row = $user_id;
				try{
					//付款成功后对买家进行极光推送
					require_once "Jpush/JPush.php";
					$type=array('type'=>'1');
					$app_key = 'cf3f465424d79b172f0fc732';
					$master_secret = 'bd7c4a065d3c4c320ffe3ed1';
					$alert="您在".date("Y-m-d H:i:s")."支付订单成功";
					$client = new JPush($app_key, $master_secret);
					$result=$client->push()
						->setPlatform(array('ios', 'android'))
						->addAlias($user_id_row)
						->addIosNotification($alert,'', null, null, null, $type)
						->addAndroidNotification($alert,null,null,$type)
						->setOptions(100000, 3600, null, false)
						->send();
				}
				catch(Exception $e){

				}

				//给卖家极光推送
				$Consume_TradeModel = new Consume_TradeModel();
				//如果是合并支付订单
				if(strpos($union_order['inorder'], ',') !== false)
				{
					$order_id_arr = explode($union_order['inorder'], ',');
					$consume_trade_info = $Consume_TradeModel->getByWhere(['consume_trade_id:IN'=>$order_id_arr]);
				}
				else
				{
					$consume_trade_info = $Consume_TradeModel->getByWhere(['consume_trade_id'=>$union_order['inorder']]);
				}

				foreach($consume_trade_info as $key=>$val)
				{
					$user_id_row = $val['seller_id'];
					try{
						//订单付款成功后对卖家进行极光推送
						require_once "Jpush/JPush.php";
						$type=array('type'=>'1');
						$app_key = 'cf3f465424d79b172f0fc732';
						$master_secret = 'bd7c4a065d3c4c320ffe3ed1';
						$alert="您的会员在".date("Y-m-d H:i:s")."提交了订单".$val['order_id'].'，请尽快发货。';
						$client = new JPush($app_key, $master_secret);
						$result=$client->push()
							->setPlatform(array('ios', 'android'))
							->addAlias($user_id_row)
							->addIosNotification($alert,'', null, null, null, $type)
							->addAndroidNotification($alert,null,null,$type)
							->setOptions(100000, 3600, null, false)
							->send();
					}
					catch(Exception $e){

					}
				}
				/****************************************************************************************************/


                //远程改变订单状态
                //根据订单来源，修改订单状态
                $consume_record = $Consume_TradeModel->getOne($order_id);
                $app_id = $consume_record['app_id'];

                $User_AppModel = new User_AppModel();
                $app_row = $User_AppModel->getOne($app_id);


                $key = $app_row['app_key'];
                $url = $app_row['app_url'];
                $shop_app_id = $app_id;

                $formvars = array();
                $formvars = $_POST;
                $formvars['app_id'] = $shop_app_id;
                $formvars['order_id'] = $order_id;
                $formvars['uorder_id'] = $uorder_id;
                if($consume_record['payment_channel_id'] == Payment_ChannelModel::BAITIAO){
                    $formvars['payment_channel_code'] = 'baitiao';
                }else{
                    $formvars['payment_channel_code'] = '';
                }

                $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Trade_Order&met=editOrderRowSatus&typ=json', $url), $formvars);
                return $rs;
            }
            else
            {
                $Union_OrderModel->sql->rollBackDb();
                return false;
            }
        }
        else
		{
            return false;
		}
	}

	/**
	 * 充值完成后,更新订单状态
	 *
	 * @param  array $order_id  需要修改状态的订单
	 * @return bool  处理结果
	 * @access public
	 */
	public function notifyDeposit($order_id = null,$user_id = null,$pay_channel = null,$trade_no = null)
	{

		//修改合并订单的状态
		$Union_OrderModel = new Union_OrderModel();
		$union_order = $Union_OrderModel->getOne($order_id);

		Yf_Log::log(var_export($union_order,true), Yf_Log::INFO, 'union_order');


		if($union_order['buyer_id']){
				$user_id = $union_order['buyer_id'];	
		}
		
		if($union_order['order_state_id'] == Order_StateModel::ORDER_PAYED)
		{
			return true;
		}

		$Union_OrderModel->sql->startTransactionDb();

		$edit_row = array();
		$edit_row['order_state_id'] = Order_StateModel::ORDER_PAYED;
		$edit_row['pay_time'] = date('Y-m-d H:i:s');

		$Union_OrderModel->editUnionOrder($order_id,$edit_row);



		//修改充值表中的交易状态
		$deposit_edit_row = array();
		$deposit_edit_row['deposit_gmt_payment'] = date('Y-m-d H:i:s');
		$deposit_edit_row['deposit_trade_status'] = Order_StateModel::ORDER_PAYED;
		$deposit_edit_row['deposit_pay_channel'] = $pay_channel;
		if($trade_no)
		{
			$deposit_edit_row['deposit_return_trade_no'] = $trade_no;
		}

		$flag = $this->editDeposit($order_id,$deposit_edit_row);


		//修改交易明细中的订单状态
		$Consume_RecordModel = new Consume_RecordModel();
		$record_row = $Consume_RecordModel->getByWhere(array('order_id'=> $order_id));
		$record_id_row = array_column($record_row,'consume_record_id');
		$edit_consume_record['record_status'] = RecordStatusModel::RECORD_FINISH;
		$edit_consume_record['record_payorder'] = $order_id;
		$edit_consume_record['record_paytime'] = date('Y-m-d H:i:s');
		$Consume_RecordModel->editRecord($record_id_row,$edit_consume_record);

		//修改用户的资源状态
		$User_ResourceModel = new User_ResourceModel();
		//用户资源中账户余额增加
		$User_ResourceModel->editResource($user_id,array('user_money'=>$union_order['trade_payment_amount']),true);


		if ($flag && $Union_OrderModel->sql->commitDb())
		{
			/****************************************************************************************************/
			$user_id_row = $user_id;
			try{
				//充值成功后进行极光推送
				require_once "Jpush/JPush.php";
				$type=array('type'=>'1');
				$app_key = 'cf3f465424d79b172f0fc732';
				$master_secret = 'bd7c4a065d3c4c320ffe3ed1';
				$alert="您在".date("Y-m-d H:i:s")."充值成功";
				$client = new JPush($app_key, $master_secret);
				$result=$client->push()
					->setPlatform(array('ios', 'android'))
					->addAlias($user_id_row)
					->addIosNotification($alert,'', null, null, null, $type)
					->addAndroidNotification($alert,null,null,$type)
					->setOptions(100000, 3600, null, false)
					->send();
			}
			catch(Exception $e){

			}
			/****************************************************************************************************/
			return true;
		}
		else
		{
			$Union_OrderModel->sql->rollBackDb();
			return false;
		}
	}

    //判断白条是否可用
    public function checkBt($order_info = array()){
        //获取信息
        $user_id = Perm::$userId;
        if(!$user_id){
            return false;
        }
        if($order_info['payment_channel_id'] != Payment_ChannelModel::BAITIAO){
            return false;
        }
        $User_InfoModel = new User_InfoModel();
		$user_info      = $User_InfoModel->getUserInfo($user_id);
        $user_resource_model = new User_ResourceModel();
        $user_resource      = $user_resource_model->getResource($user_id);
        if($user_info['user_bt_status'] != 2 || $user_resource[$user_id]['user_credit_availability'] < $order_info['trade_payment_amount']){
            return false;
        }else{
            return true;
        }
    }
}
?>

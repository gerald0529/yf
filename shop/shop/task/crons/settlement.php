<?php
if (!defined('ROOT_PATH'))
{
	if (is_file('../../../shop/configs/config.ini.php'))
	{
		require_once '../../../shop/configs/config.ini.php';
	}
	else
	{
		die('请先运行index.php,生成应用程序框架结构！');
	}

	//不会重复包含, 否则会死循环: web调用不到此处, 通过crontab调用
	$Base_CronModel = new Base_CronModel();
	$rows = $Base_CronModel->checkTask(); //并非指执行自己, 将所有需要执行的都执行掉, 如果自己达到执行条件,也不执行.

	//终止执行下面内容, 否则会执行两次
	return ;
}


Yf_Log::log(__FILE__, Yf_Log::INFO, 'crontab');

$file_name_row = pathinfo(__FILE__);
$crontab_file = $file_name_row['basename'];

fb($crontab_file);
//执行任务

$Order_SettlementModel = new Order_SettlementModel();

$Shop_BaseModel = new Shop_BaseModel();
//查找店铺信息
$shop_info      = $Shop_BaseModel->getSettlementCycle();

foreach($shop_info as $key => $val)
{
	if($val['shop_settlement_last_time'] > 0)
	{
		$start_unixtime = strtotime($val['shop_settlement_last_time']);
	}
	else
	{
		$start_unixtime = strtotime($val['shop_create_time']);
	}

	$start_unixtime = $start_unixtime ? strtotime(date('Y-m-d 00:00:00', $start_unixtime) . "+1 day") : "";
	$start_time     = @date('Y-m-d H:i:s', $start_unixtime);

	$end_unixtime = $start_unixtime ? strtotime(date('Y-m-d 23:59:59', $start_unixtime) . "+" . ($val['shop_settlement_cycle']-1) . " day") : "";
	$end_time     = @date('Y-m-d H:i:s', $end_unixtime);

	$time = time();

	fb($time);
	fb($end_unixtime);

	fb($val['shop_settlement_cycle']);
	fb($start_time);
	fb($end_time);

	if ($time > $end_unixtime)
	{
		$rs_row = array();

		//开启事务
		$Order_SettlementModel->sql->startTransactionDb();

		//店铺实物订单结算
		$data = $Order_SettlementModel->settleNormalOrder($val);
		check_rs($data['flag'],$rs_row);

		//店铺虚拟订单结算
		$data1 = $Order_SettlementModel->settleVirtualOrder($val);
		check_rs($data1['flag'],$rs_row);


		if(is_ok($rs_row))
		{
			//修改店铺信息中的结算时间
			$edit_shop_base['shop_settlement_last_time'] = $data['end_time'];
			$edit_flag = $Shop_BaseModel->editBase($val['shop_id'],$edit_shop_base);
			check_rs($edit_flag,$rs_row);
		}

		$flag = is_ok($rs_row);
		//关闭事务
		if ($flag && $Order_SettlementModel->sql->commitDb())
		{
			//结算单等待确认提醒
			$message = new MessageModel();
			$message->sendMessage('Settlement sheet for confirmation',$val['user_id'], $val['user_name'], $data['os_id'], $shop_name = NULL, 1, 1, $end_time = $data['end_time'],$common_id=NULL,$goods_id=NULL,$des=NULL, $start_time = $data['start_time']);

			$message->sendMessage('Settlement sheet for confirmation',$val['user_id'], $val['user_name'], $data1['os_id'], $shop_name = NULL, 1, 1, $end_time = $data1['end_time'],$common_id=NULL,$goods_id=NULL,$des=NULL, $start_time = $data1['start_time']);
		}
		else
		{
			$Order_SettlementModel->sql->rollBackDb();
			$m      = $Order_SettlementModel->msg->getMessages();
		}

	}
}




$flag = true;
return $flag;
?>
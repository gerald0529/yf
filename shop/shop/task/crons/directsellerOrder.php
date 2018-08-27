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
	$cur_dir = dirname(__FILE__); 
	chdir($cur_dir); 

	Yf_Log::log(__FILE__, Yf_Log::INFO, 'crontab');

	$file_name_row = pathinfo(__FILE__);
	$crontab_file = $file_name_row['basename'];

	$Order_BaseModel = new Order_BaseModel();
	$Order_GoodsModel = new Order_GoodsModel();
	$User_InfoModel = new User_InfoModel();

	//开启事物
	$Order_BaseModel->sql->startTransactionDb();

	//查找出所有确认收货的未结算订单
	$time = time()-60*60;
	$N = date('Y-m-d H:i:s',$time);

	$cond_row = array();
	$cond_row['order_status'] = Order_StateModel::ORDER_FINISH;
	$cond_row['order_is_virtual'] = Order_BaseModel::ORDER_IS_REAL;
	$cond_row['order_finished_time:<='] = $N;
	$cond_row['directseller_is_settlement'] = Order_BaseModel::IS_NOT_SETTLEMENT; //未结算

	$data = $Order_BaseModel->getBaseList($cond_row);

	if($data['items'])
	{
	foreach($data['items'] as $key=>$val)
	{
		$directseller_member[0] = $val['directseller_id'];     //直属一级
		$directseller_member[1] = $val['directseller_p_id'];   //直属二级
		$directseller_member[2] = $val['directseller_gp_id'];  //直属三级

		$condition['directseller_is_settlement'] = Order_BaseModel::IS_SETTLEMENT;
		$flag = $Order_BaseModel->editBase($val['order_id'], $condition);

		$directseller_commission = array(0,0,0);  //三级分佣数组
		//将佣金结算给对应的上级
		foreach($val['goods_list'] as $k=>$v)
		{
			//判断订单商品否是分销商品
			if($v['directseller_flag'])
			{
				$directseller_commission[0] += ($v['directseller_commission_0'] - $v['directseller_commission_0_refund']);  //一级分佣
				$directseller_commission[1] += ($v['directseller_commission_1'] - $v['directseller_commission_1_refund']);  //二级级分佣
				$directseller_commission[2] += ($v['directseller_commission_2'] - $v['directseller_commission_2_refund']);  //三级分佣
			}

			$goods_field['directseller_is_settlement'] = Order_BaseModel::IS_SETTLEMENT;
			$flag                          = $Order_GoodsModel->editGoods($v['order_goods_id'], $goods_field);
		}

		foreach($directseller_member as $ks=>$vs)
		{
			//存在分销上级并且分销佣金大于0则写入流水
			if($vs && $directseller_commission[$ks])
			{
				$user_info = $User_InfoModel->getOne($vs);
				$edit_row['user_directseller_commission'] = $user_info['user_directseller_commission'] + $directseller_commission[$ks];
				$User_InfoModel->editInfo($vs,$edit_row);

				//将需要确认的订单号远程发送给Paycenter修改订单状态
				//远程修改paycenter中的订单状态
				$key      = Yf_Registry::get('paycenter_api_key');
				$url         = Yf_Registry::get('paycenter_api_url');
				$paycenter_app_id = Yf_Registry::get('paycenter_app_id');
				$formvars = array();

				$formvars['order_id']    = $val['order_id'];
				$formvars['user_id'] = $vs;
				$formvars['user_money'] = $directseller_commission[$ks];
				$formvars['reason'] = '订单'.$val['order_id'].'佣金结算';
				$formvars['app_id']        = $paycenter_app_id;
				$formvars['type']		= 'row';

				$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=directsellerOrder&typ=json', $url), $formvars);
			}
		}

	}
	}
	else
	{
	$flag = true;
	}

	if ($flag && $Order_BaseModel->sql->commitDb())
	{
	$status = 200;
	$msg    = __('success');
	}
	else
	{
	$Order_BaseModel->sql->rollBackDb();
	$m      = $Order_BaseModel->msg->getMessages();
	$msg    = $m ? $m[0] : __('failure');
	$status = 250;
	}

	return $flag;
?>
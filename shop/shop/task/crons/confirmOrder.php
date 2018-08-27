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

//自动确认收货(实物订单)


$Order_BaseModel = new Order_BaseModel();
$Order_GoodsModel = new Order_GoodsModel();

//开启事物
$Order_BaseModel->sql->startTransactionDb();

//查找出所有待收货状态的商品
$cond_row = array();
$cond_row['order_status'] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
$cond_row['order_is_virtual'] = Order_BaseModel::ORDER_IS_REAL;
$cond_row['order_receiver_date:<='] = get_date_time();
$order_list = $Order_BaseModel->getKeyByWhere($cond_row);
fb($order_list);

$order_row = array();
if($order_list)
{
	foreach ($order_list as $key => $val)
	{
		$order_row[] = $val;
		$order_id = $val;

		$order_base           = $Order_BaseModel->getOne($order_id);
		$order_payment_amount = $order_base['order_payment_amount'];

		$condition['order_status'] = Order_StateModel::ORDER_FINISH;

		$condition['order_finished_time'] = get_date_time();

		$flag = $Order_BaseModel->editBase($order_id, $condition);

		//修改订单商品表中的订单状态
		$edit_row['order_goods_status'] = Order_StateModel::ORDER_FINISH;

		$order_goods_id = $Order_GoodsModel->getKeyByWhere(array('order_id' => $order_id));

		$Order_GoodsModel->editGoods($order_goods_id, $edit_row);


		/*
        *  经验与成长值
        */
		$user_points        = Web_ConfigModel::value("points_recharge");//订单每多少获取多少积分
		$user_points_amount = Web_ConfigModel::value("points_order");//订单每多少获取多少积分

		if ($order_payment_amount / $user_points < $user_points_amount)
		{
			$user_points = floor($order_payment_amount / $user_points);
		}
		else
		{
			$user_points = $user_points_amount;
		}

		$user_grade        = Web_ConfigModel::value("grade_recharge");//订单每多少获取多少积分
		$user_grade_amount = Web_ConfigModel::value("grade_order");//订单每多少获取多少成长值

		if ($order_payment_amount / $user_grade < $user_grade_amount)
		{
			$user_grade = floor($order_payment_amount / $user_grade);
		}
		else
		{
			$user_grade = $user_grade_amount;
		}

		$User_ResourceModel = new User_ResourceModel();
		//获取积分经验值
		$ce = $User_ResourceModel->getResource($order_base['buyer_user_id']);

		$resource_row['user_points'] = $ce[$order_base['buyer_user_id']]['user_points'] * 1 + $user_points * 1;
		$resource_row['user_growth'] = $ce[$order_base['buyer_user_id']]['user_growth'] * 1 + $user_grade * 1;

		$res_flag = $User_ResourceModel->editResource($order_base['buyer_user_id'], $resource_row);

		$User_GradeModel = new User_GradeModel;
		//升级判断
		$res_flag = $User_GradeModel->upGrade($order_base['buyer_user_id'], $resource_row['user_growth']);
		//积分
		$points_row['user_id']           = $order_base['buyer_user_id'];
		$points_row['user_name']         = $order_base['buyer_user_name'];
		$points_row['class_id']          = Points_LogModel::ONBUY;
		$points_row['points_log_points'] = $user_points;
		$points_row['points_log_time']   = get_date_time();
		$points_row['points_log_desc']   = '确认收货';
		$points_row['points_log_flag']   = 'confirmorder';

		$Points_LogModel = new Points_LogModel();

		$Points_LogModel->addLog($points_row);

		//成长值
		$grade_row['user_id']         = $order_base['buyer_user_id'];
		$grade_row['user_name']       = $order_base['buyer_user_name'];
		$grade_row['class_id']        = Grade_LogModel::ONBUY;
		$grade_row['grade_log_grade'] = $user_grade;
		$grade_row['grade_log_time']  = get_date_time();
		$grade_row['grade_log_desc']  = '确认收货';
		$grade_row['grade_log_flag']  = 'confirmorder';

		$Grade_LogModel = new Grade_LogModel;
		$Grade_LogModel->addLog($grade_row);

        if (Web_ConfigModel::value('Plugin_Fenxiao')) {
            Fenxiao::getInstance()->confirmOrder($order_id);
        }
	}

	//将需要确认的订单号远程发送给Paycenter修改订单状态
	//远程修改paycenter中的订单状态
	$key      = Yf_Registry::get('shop_api_key');
	$url         = Yf_Registry::get('paycenter_api_url');
	$shop_app_id = Yf_Registry::get('shop_app_id');
	$formvars = array();

	$formvars['order_id']    = $order_row;
	$formvars['app_id']        = $shop_app_id;
	$formvars['type']		= 'row';

	fb($formvars);

	$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=confirmOrder&typ=json', $url), $formvars);
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
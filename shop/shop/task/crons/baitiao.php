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


//查找出所有用户id
$User_InfoModel = new User_InfoModel();
$user_id = $User_InfoModel->getAllUserId();

foreach($user_id as $key => $val)
{
	//1.从paycenter中获取到还有7天到还款日期的订单
	$key = Yf_Registry::get('paycenter_api_key');
	$url = Yf_Registry::get('paycenter_api_url');
	$paycenter_app_id = Yf_Registry::get('paycenter_app_id');
	$formvars = array();


	$formvars['app_id'] = $paycenter_app_id;
	$formvars['user_id'] = $val['user_id'];
	$formvars['day_type'] = 1;

	$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Paycen_PayRecord&met=getBtOrder&typ=json', $url), $formvars);

	if($rs['status'] == 200 && !empty($rs['data']))
	{
		$money = bcsub($rs['data']['user_credit_limit'],$rs['data']['user_credit_availability'],2);
		$message->sendMessage('credit return waring', $val['user_id'], $val['user_name'], $order_id = NULL, $shop_name = NULL, $message_mold = 1, $message_type = MessageModel::USER_MESSAGE, $end_time = __('7天后到期'),$common_id=NULL,$goods_id=NULL,$des=NULL, $start_time = Null,$goods_name=NULL,$av_amount=$money ,$freeze_amount=NULL);

	}

	//2.从paycenter中获取到当天到还款日期的订单
	$key = Yf_Registry::get('paycenter_api_key');
	$url = Yf_Registry::get('paycenter_api_url');
	$paycenter_app_id = Yf_Registry::get('paycenter_app_id');
	$formvars = array();


	$formvars['app_id'] = $paycenter_app_id;
	$formvars['user_id'] = $val['user_id'];
	$formvars['day_type'] = 2;

	$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Paycen_PayRecord&met=getBtOrder&typ=json', $url), $formvars);

	if($rs['status'] == 200 && !empty($rs['data']))
	{
		$money = bcsub($rs['data']['user_credit_limit'],$rs['data']['user_credit_availability'],2);
		$message->sendMessage('credit return waring', $val['user_id'], $val['user_name'], $order_id = NULL, $shop_name = NULL, $message_mold = 1, $message_type = MessageModel::USER_MESSAGE, $end_time = __('今天到期'),$common_id=NULL,$goods_id=NULL,$des=NULL, $start_time = Null,$goods_name=NULL,$av_amount=$money ,$freeze_amount=NULL);
	}
}



$flag = true;
return $flag;
?>
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
$Order_BaseModel = new Order_BaseModel();
$Order_GoodsModel = new Order_GoodsModel();
//开启事物
$Order_BaseModel->sql->startTransactionDb();

//查找出所有待付款的订单
$order_list = $Order_BaseModel->getByWhere(array('order_status'=>Order_StateModel::ORDER_WAIT_PAY ));
$order_row = array();
foreach($order_list as $key => $val)
{
	$diff =  time() - strtotime($val['order_create_time']);

	if($diff >= Yf_Registry::get('wait_pay_time'))
	{
		//取消订单

		//加入取消时间
		$condition['order_status']        = Order_StateModel::ORDER_CANCEL;
		$condition['order_cancel_reason'] = '支付超时自动取消';
		$condition['order_cancel_identity'] = Order_BaseModel::IS_ADMIN_CANCEL;
		$condition['order_cancel_date'] = get_date_time();

		$Order_BaseModel->editBase($val['order_id'], $condition);

		//修改订单商品表中的订单状态
		$edit_row['order_goods_status'] = Order_StateModel::ORDER_CANCEL;
		$order_goods_id                 = $Order_GoodsModel->getKeyByWhere(array('order_id' => $val['order_id']));

		$Order_GoodsModel->editGoods($order_goods_id, $edit_row);

		//退还订单商品的库存
        $order_base=current($Order_BaseModel->getByWhere(array('order_id'=>$order_id)));
        if($order_base['chain_id']!=0){
            $Chain_GoodsModel = new Chain_GoodsModel();
            $chain_row['chain_id:='] = $order_base['chain_id'];
            $chain_row['goods_id:='] = is_array($order_goods_id)?$order_goods_id[0]:$order_goods_id;
            $chain_row['shop_id:='] = $order_base['shop_id'];
            $chain_goods = current($Chain_GoodsModel->getByWhere($chain_row));
            $chain_goods_id = $chain_goods['chain_goods_id'];
            $goods_stock['goods_stock'] = $chain_goods['goods_stock'] + 1;
            $Chain_GoodsModel->editGoods($chain_goods_id, $goods_stock);
        }else{
            $Goods_BaseModel = new Goods_BaseModel();
            $Goods_BaseModel->returnGoodsStock($order_goods_id);
        }

		$order_row[] = $val['order_id'];

	}
}

//将需要取消的订单号远程发送给Paycenter修改订单状态
//远程修改paycenter中的订单状态
$key      = Yf_Registry::get('paycenter_api_key');
$url         = Yf_Registry::get('paycenter_api_url');
$paycenter_app_id = Yf_Registry::get('paycenter_app_id');
$formvars = array();

$formvars['order_id']    = $order_row;
$formvars['app_id']        = $paycenter_app_id;
$formvars['type']		= 'row';

fb($formvars);

$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=cancelOrder&typ=json', $url), $formvars);

if ($Order_BaseModel->sql->commitDb())
{
	$msg    = __('success');
	$status = 200;
	$flag = true;
}
else
{
	$Order_BaseModel->sql->rollBackDb();
	$m      = $Order_BaseModel->msg->getMessages();
	$msg    = $m ? $m[0] : __('failure');
	$status = 250;
	$flag = false;
}


return $flag;
?>
<?php
/**
 * Created by PhpStorm.
 * User: xinze
 * Date: 16/4/8
 * Time: 下午2:01
 */

require_once '../../../configs/config.ini.php';

$trade_id = request_string('code');

$Union_OrderModel = new Union_OrderModel();
$uorder_base = $Union_OrderModel->getOne($trade_id);

fb($trade_id);

$Consume_DepositModel = new Consume_DepositModel();

//处理一步回调-通知商城更新订单状态
$rs =  $Consume_DepositModel->notifyShop($trade_id,$uorder_base['buyer_id']);

fb($rs);
echo encode_json($rs);
return encode_json($rs);
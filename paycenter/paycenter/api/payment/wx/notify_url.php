<?php
/* *
 * 功能：微信服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */
require_once '../../../configs/config.ini.php';
$data = xmltoarray($GLOBALS['HTTP_RAW_POST_DATA']);
//将回调通知参数写入数据库
$order_model = new Union_OrderModel();
//$notify_data = json_encode($data);
$order_model->editUnionOrder($data['out_trade_no'],array('notify_data'=>$data));

if($data['trade_type'] == 'APP'){
    $Payment_WxNativeModel = PaymentModel::create('app_wx_native');
}else{
    $Payment_WxNativeModel = PaymentModel::create('wx_native');
}

$verify_result          = $Payment_WxNativeModel->verifyNotify();


//计算得出通知验证结果
if ($verify_result)
{
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
	$notify_row = $Payment_WxNativeModel->getNotifyData();
    Yf_Log::log($notify_row, Yf_Log::ERROR, 'error_fee');
	
	if ($notify_row)
	{
		//插入充值记录
		$Consume_DepositModel = new Consume_DepositModel();
 
		$Union_OrderModel = new Union_OrderModel();
		$union_order = $Union_OrderModel->getOne($notify_row['order_id']);
        Yf_Log::log($union_order, Yf_Log::ERROR, 'error_fee');

		if($notify_row['deposit_total_fee'] != $union_order['trade_payment_amount'])
		{
			Yf_Log::log('金额错误', Yf_Log::ERROR, 'error_fee');
			die;
		}
        //webpos 支付
		if($union_order['app_id'] == 207){
            $edit_row = array();
            $edit_row['order_state_id'] = Order_StateModel::ORDER_PAYED;
            $edit_row['pay_time'] = date('Y-m-d H:i:s');
            $result = $Union_OrderModel->editUnionOrder($notify_row['order_id'],$edit_row);
            if($result){
                echo "SUCCESS";        //请不要修改或删除
                Yf_Log::log('Process-SUCCESS', Yf_Log::INFO, 'pay_wxnative_notify');
            }else{
                echo "FAIL";        //请不要修改或删除
                Yf_Log::log('Process-FAIL', Yf_Log::ERROR, 'pay_wxnative_notify_error');
                Yf_Log::log('Process-FAIL', Yf_Log::ERROR, 'pay_wxnative_notify');
            }
            
            exit;
        }
        //查找此支付单的交易类型
        $trade_type = Trade_TypeModel::$trade_type_row[$notify_row['trade_type_id']];

        //充值
        if($trade_type == 'deposit')
        {
            //修改充值表的状态
            $Consume_DepositModel = new Consume_DepositModel();
            $deposit = $Consume_DepositModel->getOne($notify_row['order_id']);
            if($deposit['deposit_trade_status']==2)
            {
                $rs = 1;
            }else{				
                $rs = $Consume_DepositModel->notifyDeposit($notify_row['order_id'],$notify_row['buyer_id'],$notify_row['payment_channel_id'],$notify_row['trade_no']);
            }
        }
        else
        {
            //处理一步回调-通知商城更新订单状态
            //修改订单表中的各种状态
            $Consume_DepositModel = new Consume_DepositModel();
            $rs                 = $Consume_DepositModel->notifyShop($notify_row['order_id'],$notify_row['buyer_id']);
        }

        echo "SUCCESS";        //请不要修改或删除
        Yf_Log::log('Process-SUCCESS', Yf_Log::INFO, 'pay_wxnative_notify');
            
	}
	else
	{
		echo "FAIL";
		Yf_Log::log('Process-FAIL', Yf_Log::ERROR, 'pay_wxnative_notify_error');
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else
{
	//验证失败
	echo "FAIL";
	Yf_Log::log($error_msg, Yf_Log::ERROR, 'pay_wxnative_notify_error');

}
function xml_to_array( $xml )
{
    $reg = "/<(\\w+)[^>]*?>([\\x00-\\xFF]*?)<\\/\\1>/";
    if(preg_match_all($reg, $xml, $matches))
    {
        $count = count($matches[0]);
        $arr = array();
        for($i = 0; $i < $count; $i++)
        {
            $key= $matches[1][$i];
            $val = xml_to_array( $matches[2][$i] );  // 递归
            if(array_key_exists($key, $arr))
            {
                if(is_array($arr[$key]))
                {
                    if(!array_key_exists(0,$arr[$key]))
                    {
                        $arr[$key] = array($arr[$key]);
                    }
                }else{
                    $arr[$key] = array($arr[$key]);
                }
                $arr[$key][] = $val;
            }else{
                $arr[$key] = $val;
            }
        }
        return $arr;
    }else{
        return $xml;
    }
}
// Xml 转 数组, 不包括根键
function xmltoarray( $xml )
{
    $arr = xml_to_array($xml);
    $key = array_keys($arr);
    $data = $arr[$key[0]];
    foreach ($data as $k=>$value){
        $data[$k] = str_replace(array('<![CDATA[',']]>'), '', $value);
    }
    return $data;
}



?>
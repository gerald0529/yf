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
/*
 *
 * 买家申请退款/退货后商家逾期未处理，系统自动处理为商家同意。
 * */

//执行任务
$orderReturnModel       = new Order_ReturnModel();
$Order_BaseModel = new Order_BaseModel();
//开启事物

$cond_row['return_state'] = 1;
$Returnlist = $orderReturnModel->getByWhere( $cond_row );

if ( !empty($Returnlist) )
{
    $message = new MessageModel();

    foreach ( $Returnlist as $return_id => $return_detail )
    {
        //开启事务
        $orderReturnModel->sql->startTransactionDb();

        $flag = $orderReturnModel->SellerAgreeReturn($return_detail);

        if ($flag && $orderReturnModel->sql->commitDb())
        {
            $status = 200;
            $msg    = __('success');
            //退款退货提醒
            $message = new MessageModel();
            $message->sendMessage('Refund return reminder', $return_detail['buyer_user_id'], $return_detail['buyer_user_account'], $order_id = NULL, $shop_name = NULL, 0, MessageModel::ORDER_MESSAGE);
        }
        else
        {
            $orderReturnModel->sql->rollBackDb();
            $status = 250;
            $msg    = $msg ? $msg : __('failure');
        }
    }

}


return true;
?>
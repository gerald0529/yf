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
$goodsCommonModel = new Goods_CommonModel();
$goodsBaseModel = new Goods_BaseModel();
$shopBaseModel = new Shop_BaseModel();
//开启事物
$need_alarm   = array();
$shop_ids     = array();
$condi_common = array();
$condi_common['common_state']    = Goods_CommonModel::GOODS_STATE_NORMAL;
$condi_common['common_verify']   = Goods_CommonModel::GOODS_VERIFY_ALLOW;

$goods_common_list = $goodsCommonModel->getByWhere( $condi_common );

$common_ids = array_column($goods_common_list, 'common_id');

if ( !empty($common_ids) )
{
    $condi_goods['goods_alarm:<>']   = Goods_CommonModel::GOODS_NO_ALARM;
    $condi_goods['common_id:IN']     = $common_ids;

    $goods_list = $goodsBaseModel->getByWhere( $condi_goods );

    if ( !empty($goods_list) )
    {
        foreach ( $goods_list as $goods_id => $goods_data )
        {
            if ( $goods_data['goods_alarm'] > $goods_data['goods_stock'] )
            {
                //触发预警
                $shop_ids[] = $goods_data['shop_id'];

                $need_alarm[$goods_id]['shop_id'] = $goods_data['shop_id'];
                $need_alarm[$goods_id]['common_id'] = $goods_data['common_id'];
                $need_alarm[$goods_id]['goods_id']  = $goods_data['goods_id'];
            }
        }
    }

    if ( !empty($need_alarm) )
    {
        $message = new MessageModel();
        $shop_list = $shopBaseModel->getBase($shop_ids);

        foreach ( $need_alarm as $goods_id => $goods_data )
        {
            $common_id = $goods_data['common_id'];
            $goods_id = $goods_data['goods_id'];
            $shop_id = $goods_data['shop_id'];

            $message_user_id = $shop_list[$shop_id]['user_id'];
            $message_user_name= $shop_list[$shop_id]['user_name'];

            $message->sendMessage('goods are not in stock',$message_user_id, $message_user_name, $order_id = NULL, $shop_name = NULL, 1, 1, $end_time = Null,$common_id,$goods_id);
        }
    }
}


return true;
?>
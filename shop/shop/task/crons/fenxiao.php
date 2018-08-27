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

$fenxiao = new Fenxiao;

//开启事物
$fenxiao->db->sql->startTransactionDb();

//查找出所有确认收货的未结算订单
$rows = $fenxiao->getCycleList();

if($rows)
{
    //将需要确认的订单号远程发送给Paycenter修改订单状态
    //远程修改paycenter中的订单状态
    $key = Yf_Registry::get('paycenter_api_key');
    $url = Yf_Registry::get('paycenter_api_url');
    $paycenter_app_id = Yf_Registry::get('paycenter_app_id');

    $flags = [];
    foreach($rows as $row)
    {
        $id = $row['id'];
        $user_id = $row['user_id'];
        $order_id = $row['order_id'];
        $price = $row['price'];

        $f = $fenxiao->settled($id);
        array_push($flags, $f);

        $formvars = array();
        $formvars['order_id'] = $order_id;
        $formvars['user_id'] = $user_id;
        $formvars['user_money'] = $price;
        $formvars['reason'] = "订单$order_id佣金结算";
        $formvars['type'] = 'row';
        $formvars['app_id'] = $paycenter_app_id;
        $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=directsellerOrder&typ=json', $url), $formvars);
    }

    if (count($flags) !== count(array_filter($flags))) {
        $flag = false;
    } else {
        $flag = true;
    }
}
else
{
    $flag = true;
}


if ($flag && $fenxiao->db->sql->commitDb())
{
    $status = 200;
    $msg    = __('success');
}
else
{
    $fenxiao->db->sql->rollBackDb();
    $msg    = __('failure');
    $status = 250;
}

return $flag;
?>
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

//    终止执行下面内容, 否则会执行两次
    return ;
}

Yf_Log::log(__FILE__, Yf_Log::INFO, 'crontab');

$file_name_row = pathinfo(__FILE__);
$crontab_file = $file_name_row['basename'];

//执行任务
$goodsCommonModel = new Goods_CommonModel();
//查找出超过有效期的虚拟商品
$conditions = [
    'common_is_virtual'=> Goods_CommonModel::GOODS_VIRTUAL, //虚拟商品
    'common_virtual_date:<='=> date('Y-m-d') //超过有效期的虚拟商品
];

$common_goods_rows = $goodsCommonModel->getByWhere($conditions);
//echo '<pre>';print_r($common_goods_rows);exit;
if (!empty($common_goods_rows)) {
    $common_ids = array_keys($common_goods_rows);
    $goodsCommonModel->editCommon($common_ids, ['common_state'=> Goods_CommonModel::GOODS_STATE_OFFLINE]);
}

return true;
?>



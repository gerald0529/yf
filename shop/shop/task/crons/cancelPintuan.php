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

/* *
 * 判断拼团是否失败，更新团状态
 */
$pintuanModel = new PinTuan_Base;

$disabledPintuanList = $pintuanModel->getByWhere([
    'end_time:<='=> date('Y-m-d H:i:s')
]);

if ($disabledPintuanList) {
    $pintuanIds = array_keys($disabledPintuanList);

    $detailModel = new PinTuan_Detail;
    $detailList = $detailModel->getByWhere([
        'pintuan_id:IN'=> $pintuanIds
    ]);
    $detailIds = array_keys($detailList);

    $markModel = new PinTuan_Mark;
    $markList = $markModel->getByWhere([ //获取成团失败列表
        'detail_id:IN'=> $detailIds,
        'status'=> PinTuan_Mark::STATUS_WAIT
    ]);

    if ($markList) { //更新团状态失败
        $markIds = array_keys($markList);
        $markModel->editInfo($markIds, [
            'status'=> PinTuan_Mark::STATUS_FAILURE
        ]);
    }
}
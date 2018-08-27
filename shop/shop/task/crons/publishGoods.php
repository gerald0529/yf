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

    //执行任务
    $goodsCommonModel = new Goods_CommonModel();
    //查找出定时发布的商品
    $conditions = [
        'common_state'=> Goods_CommonModel::GOODS_STATE_TIMING, //定时发布的商品
        'common_sell_time:<='=> date('Y-m-d H:i:s'),
        'common_goods_from'=> Goods_CommonModel::GOODS_FORM_SHOP //正常添加
    ];

    $common_goods_rows = $goodsCommonModel->getByWhere( $conditions );

    if (!empty($common_goods_rows)) {
        $common_ids = array_keys($common_goods_rows);
        foreach($common_goods_rows as $k=>$v)
        {
            if($v['common_verify'] == 1)
            {
                $pass_common_id[] = $k;
            }else{
                $unpass_common_id[] = $k;
            }
        }
        if (Web_ConfigModel::value('goods_verify_flag') == 0) { //商品是否需要审核
            $goodsCommonModel->editCommon($common_ids, ['common_state' => Goods_CommonModel::GOODS_STATE_NORMAL, 'common_verify' => Goods_CommonModel::GOODS_VERIFY_ALLOW]);
        }else{
            if($unpass_common_id)
            {
                $goodsCommonModel->editCommon($unpass_common_id, ['common_state' => Goods_CommonModel::GOODS_STATE_NORMAL, 'common_verify' => Goods_CommonModel::GOODS_VERIFY_WAITING]);
            }
            if($pass_common_id)
            {
                $goodsCommonModel->editCommon($pass_common_id, ['common_state' => Goods_CommonModel::GOODS_STATE_NORMAL, 'common_verify' => Goods_CommonModel::GOODS_VERIFY_ALLOW]);
            }
        }
    }

	return true;
?>
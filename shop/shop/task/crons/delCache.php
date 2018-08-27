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

//清除shop缓存
$error_row = array();
$data_row  = array();
$config_cache = Yf_Registry::get('config_cache');
foreach ($config_cache as $name => $item)
{
    if (isset($item['cacheDir']))
    {
        if (clean_cache($item['cacheDir']))
        {
            $data_row[] = $item['cacheDir'];
        }
        else
        {
            $error_row[] = $item['cacheDir'];
        }
    }
}
//清除wap端缓存
$url = Yf_Registry::get('shop_wap_url').'/tmpl/delCache.php';
file_get_contents($url."?str=".md5(Yf_Registry::get('shop_app_id')));
return true;
?>



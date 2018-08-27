<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * App版本信息等
 *
 */
class Api_AppCtl extends Yf_AppController
{
    public $app_version = '3.1.3';
    public $app_download_url = 'apk/shop_yuanfeng_3.1.3.apk';

    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);

        $this->app_download_url = sprintf("%s/%s", Yf_Registry::get('base_url'),$this->app_download_url);
    }

    /**
     * 获取当前App版本号
     */
    public function getAppVersion()
    {
        $app_version = [
            'app_version'=> $this->app_version,
            'app_download_url'=> $this->app_download_url
        ];
        $this->data->addBody(-140, $app_version, 'success', 200);
    }
}
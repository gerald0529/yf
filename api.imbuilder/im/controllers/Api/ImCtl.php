<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_ImCtl extends Api_Controller
{
    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);
    }

    /*
     * IM配置获取
     *
     */
    public function im()
    {
        $host = 'default';
        if (isset($_SERVER['HTTP_HOST']))
        {
            $host = $_SERVER['HTTP_HOST'];
        }
        $INI_PATH = __DIR__.'/../../configs/im/'; 
        $server_id = $host; 
        $im_config_file = $INI_PATH.$host.'.php';
        if(file_exists($im_config_file)){
            include $im_config_file;
        } 
 
        $data['im_appId'] = $appId;
        $data['im_appToken'] = $app_token;
        $this->data->addBody(-140, $data);

    }
}
?>
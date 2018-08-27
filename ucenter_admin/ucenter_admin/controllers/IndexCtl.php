<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class IndexCtl extends Yf_AppController
{
    public function index()
    {
        //查找地址配置信息
        $key    = Yf_Registry::get('ucenter_api_key');;
        $url    = Yf_Registry::get('ucenter_api_url');

        $app_id = Yf_Registry::get('ucenter_app_id');
        $data              = array();
        $data['app_id']    = $app_id;
        $data['ctl'] = 'Api';
        $data['met'] = 'getBaseApp';
        $data['typ'] = 'json';
        $init_rs         = get_url_with_encrypt($key, $url, $data);
        fb($init_rs);
        $shop_admin_url = '';
        $paycenter_admin_url = '';
        if($init_rs['status'] == 200)
        {
            $shop_admin_url = $init_rs['data'][102]['app_admin_url'];
            $paycenter_admin_url = $init_rs['data'][105]['app_admin_url'];
        }


        include $this->view->getView();
    }

    public function main()
    {
        $a = _("asa");
        include $this->view->getView();
    }
}
?>
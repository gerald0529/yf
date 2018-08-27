<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     yesai
 */
class ImCtl extends Controller
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);

	}
    public function Im(){
        $key = Yf_Registry::get('im_api_key');
        $url = Yf_Registry::get('im_api_url');

        $formvars                     = array();
        $formvars['app_id']           = Yf_Registry::get('im_app_id');

        $init_rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Im&met=im&typ=json', $url), $formvars);
        $data = array();
        if(200 == $init_rs['status']){
            $data['im_appId']       = $init_rs['data']['im_appId'];
            $data['im_appToken']    = $init_rs['data']['im_appToken'];
            $msg = 'success';
            $status = 200;
        }else{
            $msg = $init_rs['msg'];
            $status = 200;
        }
        $this->data->addBody(-140, $data,$msg,$status);
    }
}

?>
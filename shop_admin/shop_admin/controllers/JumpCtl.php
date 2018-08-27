<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}
 
class JumpCtl extends AdminController
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}

	public function index()
	{	

			 $ctl = $_GET['ct'];
			 $met = $_GET['mt'];  
			 unset($_GET['ct'],$_GET['mt'],$_GET['met'],$_GET['ctl']);
			 $formvars = $_GET;
  		 $this->getUrl($ctl, $met, $typ = 'json', true, $formvars);

	}




	public function getUrl($ctl, $met, $typ = 'json', $jump=null, $formvars=null)
	{
		  //本地读取远程信息
			$key = Yf_Registry::get('shop_api_key');;
			$url         = Yf_Registry::get('shop_api_url');
			$shop_app_id = Yf_Registry::get('shop_app_id');
			 
				if (null === $formvars)
        {
            $formvars                  = $_POST;
    
            foreach ($_GET as $k => $item)
            {
                if ('ct' != $k && 'mt' != $k && 'typ' != $k && 'debug' != $k)
                {
                    $formvars[$k] = $item;
                }
            }
        }
        unset($formvars['met'],$formvars['ctl']);
        
        $formvars['app_id']        = $shop_app_id;
        $formvars['admin_account'] = Perm::$row['user_account'];
        $formvars['sub_site_id']   =  @Perm::$row['sub_site_id'];
	 
				get_url_with_encrypt($key, sprintf('%s?ctl=Api_%s&met=%s&typ=%s', $url, $ctl, $met, strtolower($typ)), $formvars, $typ, 'GET', true);

	}


 
}

 
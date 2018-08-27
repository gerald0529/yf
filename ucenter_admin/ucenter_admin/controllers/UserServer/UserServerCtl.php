<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class UserServer_UserServerCtl extends Yf_AppController
{
    public $userLoadModel = null;
	
	
	public function index()
	{
		include $this->view->getView();
	}
	public function getUserServer()
	{
		//$app_id = $_REQUEST['app_id'];
		$key    = Yf_Registry::get('ucenter_api_key');
        $url    = Yf_Registry::get('ucenter_api_url');
        $app_id = Yf_Registry::get('ucenter_app_id');
        $data              = array();
        
        $data['ctl'] = 'Api';
        $data['met'] = 'getAppId';
        $data['typ'] = 'json';
		$data['app_id']    = $app_id;
        $init_rs         = get_url_with_encrypt($key, $url, $data);
		$list = $init_rs['data'];
		
		$applist = array();
		fb($list);
		foreach($list as $k =>$v)
		{
			$applist[$k]['app_id'] = $v['app_id'];
		}
		include $this->view->getView();
	}
	public function getUserServerlist()
	{
		
		//print_r($skey);
		
		$skey = $_REQUEST['skey'];
		$app_id = $_REQUEST['app_id'];
		$key    = Yf_Registry::get('ucenter_api_key');
        $url    = Yf_Registry::get('ucenter_api_url');
		
        $data              = array();
        
        $data['ctl'] = 'Api';
        $data['met'] = 'getUserServer';
        $data['typ'] = 'json';
		$data['skey'] = $skey;
		$data['app_id'] = $app_id;
        $init_rs         = get_url_with_encrypt($key, $url, $data);
		fb($init_rs);
		$user = $init_rs['data'];
		//fb($user);
		$userlist = array();
		foreach($user as $k => $v)
		{
			$userlist[$k]['user_name'] = $v['user_name'];
			$userlist[$k]['app_id'] = $v['app_id'];
			$userlist[$k]['server_id'] = $v['server_id'];
			$userlist[$k]['active_time'] = date('Y-m-d h:i:s',time());;
		}
		//fb($userlist);
		//$username = $userlist['user_name'];
		//fb($username);
		
		$a = array_values($userlist);
		$b['items'] = $a;
		$b['records'] = count($a);
		$total = count($a);
        $b['page'] = 1;
        $b['total'] = ceil_r($total / 100);
		if($b)
        {
            $msg = 'success';
            $status = 200;
        }
        else
        {
            $msg = 'failure';
            $status = 250;
        }
		$this->data->addBody(-140,$b,$msg,$status);
	}
	/*public function getAppid()
	{
		
		
		$this->data->addBody(-140,$applist,$msg,$status);
	}*/
}
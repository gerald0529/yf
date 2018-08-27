<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class BaseApp_BaseAppCtl extends Yf_AppController
{
    public $userLoadModel = null;
	
	
	public function index()
	{
		include $this->view->getView();
	}
	public function application()
	{
		include $this->view->getView();
	}

    /**
     *
     */
    public function getBaseAppList()
	{
		$key    = Yf_Registry::get('ucenter_api_key');;
        $url    = Yf_Registry::get('ucenter_api_url');
        $app_id = Yf_Registry::get('ucenter_app_id');
        $data              = array();
        $data['app_id']    = $app_id;
        $data['ctl'] = 'Api';
        $data['met'] = 'getBaseApp';
        $data['typ'] = 'json';
        $init_rs         = get_url_with_encrypt($key, $url, $data);
		$baseapp = $init_rs['data'];
		$baselist = array();
		foreach($baseapp as $k =>$v)
		{
			$baselist[$k]['app_id'] = $v['app_id'];
			$baselist[$k]['app_name'] = $v['app_name'];
			$baselist[$k]['app_type'] = $v['app_type'];
			$baselist[$k]['app_seq'] = $v['app_seq'];
			$baselist[$k]['app_key'] = $v['app_key'];
			$baselist[$k]['app_ip_list'] = $v['app_ip_list'];
			$baselist[$k]['app_url'] = $v['app_url'];
			$baselist[$k]['app_admin_url'] = $v['app_admin_url'];
			$baselist[$k]['app_url_recharge'] = $v['app_url_recharge'];
			$baselist[$k]['app_url_order'] = $v['app_url_order'];
			$baselist[$k]['app_logo'] = $v['app_logo'];
			$baselist[$k]['app_hosts'] = $v['app_hosts'];
			$baselist[$k]['return_fields'] = $v['return_fields'];
			$baselist[$k]['app_status'] = $v['app_status'];
		}
		$a = array_values($baselist);

		$b['rows'] = $a;
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
	public function editApps()
	{
		include $this->view->getView();
	}

    public function getApps()
    {
        $appid = $_REQUEST['app_id'];
        $key    = Yf_Registry::get('ucenter_api_key');
        $url    = Yf_Registry::get('ucenter_api_url');
        $app_id = Yf_Registry::get('ucenter_app_id');
        $data              = array();
        $data['app_id']  = $app_id;
        $data['ctl'] = 'Api';
        $data['met'] = 'getEditApp';
        $data['typ'] = 'json';
        $data['appid'] = $appid;
        $init_rs         = get_url_with_encrypt($key, $url, $data);
        $applist = $init_rs['data'];

        $applists = current($applist);
        if($applists)
        {
            $msg = 'success';
            $status = 200;
        }
        else
        {
            $msg = 'failure';
            $status = 250;
        }


        $this->data->addBody(-140,$applists,$msg,$status);
    }

	public function edit()
	{
		$data = array();
		$appid = $_REQUEST['app_id'];
		$data['app_name'] = $_REQUEST['app_name'];
		$data['app_type'] = $_REQUEST['app_type'];
		$data['app_seq'] = $_REQUEST['app_seq'];
		$data['app_key'] = $_REQUEST['app_key'];
		$data['app_ip_list'] = $_REQUEST['app_ip_list'];
		$data['app_url'] = $_REQUEST['app_url'];
		$data['app_admin_url'] = $_REQUEST['app_admin_url'];
		$data['app_url_recharge'] = $_REQUEST['app_url_recharge'];
		$data['app_url_order'] = $_REQUEST['app_url_order'];
		$data['app_logo'] = $_REQUEST['app_logo'];
		$data['app_hosts'] = $_REQUEST['app_hosts'];
		$data['return_fields'] = $_REQUEST['return_fields'];
		$data['app_status'] = request_int('app_status');

		$key    = Yf_Registry::get('ucenter_api_key');
        $url    = Yf_Registry::get('ucenter_api_url');
       
        $data['id']    = $appid;
        $data['ctl'] = 'Api';
        $data['met'] = 'edit';
        $data['typ'] = 'json';

        $init_rs         = get_url_with_encrypt($key, $url, $data);

		$flag = $init_rs['status'];
		if ($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		$this->data->addBody(-140,$data,$msg,$status);
	}
	public function add()
	{
        $data = array();
        $appid = $_REQUEST['app_id'];
        $data['app_name'] = $_REQUEST['app_name'];
        $data['app_type'] = $_REQUEST['app_type'];
        $data['app_seq'] = $_REQUEST['app_seq'];
        $data['app_key'] = $_REQUEST['app_key'];
        $data['app_ip_list'] = $_REQUEST['app_ip_list'];
        $data['app_url'] = $_REQUEST['app_url'];
        $data['app_admin_url'] = $_REQUEST['app_admin_url'];
        $data['app_url_recharge'] = $_REQUEST['app_url_recharge'];
        $data['app_url_order'] = $_REQUEST['app_url_order'];
        $data['app_logo'] = $_REQUEST['app_logo'];
        $data['app_hosts'] = $_REQUEST['app_hosts'];
        $data['return_fields'] = $_REQUEST['return_fields'];

        $key    = Yf_Registry::get('ucenter_api_key');
        $url    = Yf_Registry::get('ucenter_api_url');
        $app_id = Yf_Registry::get('ucenter_app_id');

        $data['ctl'] = 'Api';
        $data['met'] = 'add';
        $data['typ'] = 'json';
        $data['app_id']    = $appid;
        $init_rs         = get_url_with_encrypt($key, $url, $data);

        $flag = $init_rs['status'];
        if ($flag)
        {
            $msg    = 'success';
            $status = 200;
        }
        else
        {
            $msg    = 'failure';
            $status = 250;
        }
        $this->data->addBody(-140,$data,$msg,$status);
	}
}
?>
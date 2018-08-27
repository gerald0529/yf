<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Paycen_PaywayCtl extends Yf_AppController
{
    public $userLoadModel = null;
	
	
	public function index()
	{
		include $this->view->getView();
	}
	public function payload()
	{
            $key    = Yf_Registry::get('paycenter_api_key');;
            $url    = Yf_Registry::get('paycenter_api_url');
            fb($url);
            $app_id = Yf_Registry::get('paycenter_app_id');
            $data              = array();
            $data['app_id']    = $app_id;
            $data['ctl'] = 'Api';
            $data['met'] = 'getPayWays';
            $data['typ'] = 'json';
            $init_rs         = get_url_with_encrypt($key, $url, $data);
                    $listarr = $init_rs['data'];
                    $paylist = array();
                     foreach($listarr as $k =>$v)
                     {
                             $paylist[$k]['payment_channel_id'] = $v['payment_channel_id'];
                             $paylist[$k]['payment_channel_code'] = $v['payment_channel_code'];
                             $paylist[$k]['payment_channel_name'] = $v['payment_channel_name'];
                             $paylist[$k]['payment_channel_status'] = $v['payment_channel_status'];
                     }
            include $this->view->getView();
	}
    
	
	public function alipay()
	{
		$type = $_REQUEST['paytype'];
		fb($type);
		$key    = Yf_Registry::get('zend_payway_key');
                $url    = Yf_Registry::get('payway_api_url');
                        //fb($url);
                $app_id = Yf_Registry::get('app_id');
                $data              = array();
                $data['app_id']    = $app_id;
                $data['ctl'] = 'Api';
                $data['met'] = 'getPayWays';
                $data['typ'] = 'json';
                $data['type'] = $type;
                $init_rs         = get_url_with_encrypt($key, $url, $data);
		fb($init_rs['data']);
		$payway = $init_rs['data'];
		foreach($payway as $key => $val)
		{
			$payways = $val;
			
		}
		fb($payways);
		//$payway_config = array();
		$list_config = json_decode($payways['payment_channel_config'],true);
		include $this->view->getView();
	}
	public function tenpay()
	{
		$type = $_REQUEST['paytype'];
		fb($type);
		$key    = Yf_Registry::get('zend_payway_key');
                $url    = Yf_Registry::get('payway_api_url');
                        //fb($url);
                $app_id = Yf_Registry::get('app_id');
                $data              = array();
                $data['app_id']    = $app_id;
                $data['ctl'] = 'Api';
                $data['met'] = 'getPayWays';
                $data['typ'] = 'json';
                        $data['type'] = $type;
                $init_rs         = get_url_with_encrypt($key, $url, $data);
		fb($init_rs['data']);
		$payway = $init_rs['data'];
		foreach($payway as $key => $val)
		{
			$payways = $val;
			
		}
		//$payway_config = array();
		$list_config = json_decode($payways['payment_channel_config'],true);
		include $this->view->getView();
	}
	public function alipay_wap()
	{
		$type = $_REQUEST['paytype'];
		fb($type);
		$key    = Yf_Registry::get('zend_payway_key');
                $url    = Yf_Registry::get('payway_api_url');
                        //fb($url);
                $app_id = Yf_Registry::get('app_id');
                $data              = array();
                $data['app_id']    = $app_id;
                $data['ctl'] = 'Api';
                $data['met'] = 'getPayWays';
                $data['typ'] = 'json';
                        $data['type'] = $type;
                $init_rs         = get_url_with_encrypt($key, $url, $data);
		fb($init_rs['data']);
		$payway = $init_rs['data'];
		foreach($payway as $key => $val)
		{
			$payways = $val;
			
		}
		//$payway_config = array();
		$list_config = json_decode($payways['payment_channel_config'],true);
		include $this->view->getView();
	}
	public function wx_native()
	{
		$type = $_REQUEST['paytype'];
		fb($type);
		$key    = Yf_Registry::get('zend_payway_key');
                $url    = Yf_Registry::get('payway_api_url');
                        //fb($url);
                $app_id = Yf_Registry::get('app_id');
                $data              = array();
                $data['app_id']    = $app_id;
                $data['ctl'] = 'Api';
                $data['met'] = 'getPayWays';
                $data['typ'] = 'json';
                        $data['type'] = $type;
                $init_rs         = get_url_with_encrypt($key, $url, $data);
		fb($init_rs['data']);
		$payway = $init_rs['data'];
		foreach($payway as $key => $val)
		{
			$payways = $val;
			
		}
		//$payway_config = array();
		$list_config = json_decode($payways['payment_channel_config'],true);
		include $this->view->getView();
	}
	public function cash()
	{
		$type = $_REQUEST['paytype'];
		fb($type);
		$key    = Yf_Registry::get('zend_payway_key');
                $url    = Yf_Registry::get('payway_api_url');
                        //fb($url);
                $app_id = Yf_Registry::get('app_id');
                $data              = array();
                $data['app_id']    = $app_id;
                $data['ctl'] = 'Api';
                $data['met'] = 'getPayWays';
                $data['typ'] = 'json';
                        $data['type'] = $type;
                $init_rs         = get_url_with_encrypt($key, $url, $data);
		fb($init_rs['data']);
		$payway = $init_rs['data'];
		foreach($payway as $key => $val)
		{
			$payways = $val;
			
		}
		//$payway_config = array();
		$list_config = json_decode($payways['payment_channel_config'],true);
		include $this->view->getView();
	}
	public function cards()
	{
		$type = $_REQUEST['paytype'];
		fb($type);
		$key    = Yf_Registry::get('zend_payway_key');
                $url    = Yf_Registry::get('payway_api_url');
                        //fb($url);
                $app_id = Yf_Registry::get('app_id');
                $data              = array();
                $data['app_id']    = $app_id;
                $data['ctl'] = 'Api';
                $data['met'] = 'getPayWays';
                $data['typ'] = 'json';
                        $data['type'] = $type;
                $init_rs         = get_url_with_encrypt($key, $url, $data);
		fb($init_rs['data']);
		$payway = $init_rs['data'];
		foreach($payway as $key => $val)
		{
			$payways = $val;
			
		}
		//$payway_config = array();
		$list_config = json_decode($payways['payment_channel_config'],true);
		include $this->view->getView();
	}
    public function tenpay_wap()
    {
        $type = $_REQUEST['paytype'];
        fb($type);
        $key    = Yf_Registry::get('zend_payway_key');
        $url    = Yf_Registry::get('payway_api_url');
        //fb($url);
        $app_id = Yf_Registry::get('app_id');
        $data              = array();
        $data['app_id']    = $app_id;
        $data['ctl'] = 'Api';
        $data['met'] = 'getPayWays';
        $data['typ'] = 'json';
        $data['type'] = $type;
        $init_rs         = get_url_with_encrypt($key, $url, $data);
        fb($init_rs['data']);
        $payway = $init_rs['data'];
        foreach($payway as $key => $val)
        {
            $payways = $val;

        }
        //$payway_config = array();
        $list_config = json_decode($payways['payment_channel_config'],true);
        include $this->view->getView();
    }
	public function editPayLoad()
	{
		$data              = array();
		$data['payment_channel_id'] = $_REQUEST['payment_channel_id'];
		$data['payment_channel_code'] = $_REQUEST['payment_channel_code'];
		$data['payment_channel_name'] = $_REQUEST['payment_channel_name'];
		unset($_REQUEST['payment_channel_id']);
		unset($_REQUEST['payment_channel_code']);
		unset($_REQUEST['payment_channel_name']);
		unset($_REQUEST['payment_channel_status']);
		unset($_REQUEST['ctl']);
		unset($_REQUEST['met']);
		unset($_REQUEST['typ']);
		
		foreach($_REQUEST as $key => $val)
		{
			$array[$key] = $val;
		}
		
		$data['payment_channel_config'] = json_encode($array);
		
		$key    = Yf_Registry::get('zend_payway_key');
        $url    = Yf_Registry::get('payway_api_url');

        $app_id = Yf_Registry::get('app_id');
        
        $data['app_id']    = $app_id;
        $data['ctl'] = 'Api';
        $data['met'] = 'editPay';
        $data['typ'] = 'json';
		$init_rs         = get_url_with_encrypt($key, $url, $data);
		$flag = $init_rs['status'];
		
		if($flag == 200)
        {
            $msg = 'success';
            $status = 200;
        }
        else
        {
            $msg = 'failure';
            $status = 250;
        }
		
	   
        $this->data->addBody(-140,$data,$msg,$status);
	}
	public function editPayStatus()
	{
		//fb($data);
		$data['payment_channel_id'] = $_REQUEST['payment_channel_id'];
		
		$key    = Yf_Registry::get('zend_payway_key');
        $url    = Yf_Registry::get('payway_api_url');

        $app_id = Yf_Registry::get('app_id');
        
        $data['app_id']    = $app_id;
        $data['ctl'] = 'Api';
        $data['met'] = 'editPayStatus';
        $data['typ'] = 'json';
		$init_rs         = get_url_with_encrypt($key, $url, $data);
		fb($init_rs['data']);
		$flag = $init_rs['status'];
		//fb($init_rs);
		if($flag == 200)
        {
            $msg = 'success';
            $status = 200;
			location_to(Yf_Registry::get('pay_status_url'));
        }
        else
        {
            $msg = 'failure';
            $status = 250;
        }
		
	   
        $this->data->addBody(-140,$data,$msg,$status);
	}
}
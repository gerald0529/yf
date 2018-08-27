<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_UpdateCtl extends Yf_AppController
{
	/**
	 * Constructor
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}

	/**
	 * 检测证书正确与否
	 *
	 * @access public
	 */
	public function checkLicence()
	{
		$licence_key = request_string('licence_key');
		$env_row['domain'] = request_string('domain', @$_SERVER['HTTP_REFERER']);
		if($licence_key){
				$lic = new Yf_Licence_Maker();
				$res = $lic->check($licence_key, file_get_contents(APP_PATH . '/data/licence/public.pem'), $env_row);

		}else{
				$lic = new Base_AppLicence();
				$con = ['licence_domain'=>$env_row['domain']];
				if($_GET['server']){
					$con['app_id'] = (int)$_GET['server'];
				} 

				$res = $lic->listByWhere($con);
				$res = $res['items'][0];
  
				if($res){ 

						$a  = $res['licence_effective_startdate'];
						$b  = $res['licence_effective_enddate'];
						 
						$now = time();
						$a = strtotime($a." 23:59:59");
						$b = strtotime($b." 23:59:59");
					 
						if($now <= $b && $now >= $a){}
						else{ $res = [];}
					
				}
		}
	 
		

 
		if ($res)
		{
			$status = 200;
			$msg = _('success');
			exit();
		}
		else
		{
			$data['licence_key']            = $licence_key  ; // 授权码
			$data['licence_log_domain']     = $env_row['domain']; // 域名

			$data['licence_log_date']       = date('Y-m-d'); // 有效期开始与结束1
			$data['licence_log_state']      = 0; //

			$Base_AppLicenceLogModel = new Base_AppLicenceLogModel();
			$Base_AppLicenceLogModel->addAppLicenceLog($data);


			$status = 250;
			$msg = _('failure');
		}


		if ('json' == $this->typ)
		{
			$this->data->addBody(-140, array(), $msg, $status);
		}
		else
		{
			include $this->view->getView();
		}
	}
}
?>

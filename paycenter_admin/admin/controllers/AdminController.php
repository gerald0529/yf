<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class AdminController extends Yf_AppController
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}

	/**
	 * 不要建议使用
	 *
	 * @param string $method 方法名称
	 * @param string $args 参数
	 * @return void
	 */
	public function __call($method, $args)
	{
		$view = $this->view->getView();;
		$ctl = $_REQUEST['ctl'];
		$met = $_REQUEST['met'];

		$data = $this->getUrl($ctl, $met);

		if (is_file($view))
		{
			include $view;
		}
	}


	/**
	 * 不要建议使用
	 *
	 * @param string $method 方法名称
	 * @param string $args 参数
	 * @return void
	 */
	public function getUrl($ctl, $met, $typ = 'json', $jump=null)
	{
		//本地读取远程信息
		$key = Yf_Registry::get('paycenter_api_key');;
		$url         = Yf_Registry::get('paycenter_api_url');
		$paycenter_app_id = Yf_Registry::get('paycenter_app_id');

		$formvars                  = $_POST;
		$formvars['app_id']        = $paycenter_app_id;
		$formvars['admin_account'] = Perm::$row['user_account'];

		foreach ($_GET as $k => $item)
		{
			if ('ctl' != $k && 'met' != $k && 'typ' != $k && 'debug' != $k)
			{
				$formvars[$k] = $item;
			}
		}

		$init_rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_%s&met=%s&typ=%s', $url, $ctl, $met, strtolower($typ)), $formvars, $typ, 'POST', $jump);
        fb($init_rs);
		$data = array();

		if (200 == $init_rs['status'])
		{
			//读取服务列表
			$data   = $init_rs['data'];
			$status = 200;
			$msg    = isset($init_rs['msg']) ? $init_rs['msg'] : _('sucess');
		}
		else
		{
			$status = 250;
			$msg    = isset($init_rs['msg']) ? $init_rs['msg'] : _('请求错误!');
		}

		{
			$this->data->addBody(-140, $data, $msg, $status);
		}

		return $data;
	}
}

?>
<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}


use Yf\Upgrader\Base;
use Yf\Upgrader\Core;

/**
 * @author     Xinze <xinze@live.cn>
 */
class ConfigCtl extends AdminController
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}


	/**
	 * 设置用户中心API网址及key - 后台独立使用
	 *
	 * @access public
	 */
	public function editUcenterApi()
	{
		$ucenter_api_row = request_row('ucenter_api');

		$ucenter_api_key = $ucenter_api_row['ucenter_api_key'];
		$ucenter_api_url = $ucenter_api_row['ucenter_api_url'];
		$ucenter_admin_url = $ucenter_api_row['ucenter_admin_url'];
		$ucenter_app_id  = 104;

		//先检测API是否正确
		$key                = $ucenter_api_key;
		$url                = $ucenter_api_url;
		$formvars           = array();
		$formvars['app_id'] = $ucenter_app_id;
		$init_rs            = get_url_with_encrypt($key, sprintf('%s?ctl=Api&met=checkApi&typ=json', $url), $formvars);

		$data = array();

		if (200 == $init_rs['status'])
		{
			//读取服务列表
			$data   = $init_rs['data'];
			$status = 200;
			$msg    = isset($init_rs['msg']) ? $init_rs['msg'] : _('sucess');


			//

			$data                    = array();
			$data['ucenter_api_key'] = $ucenter_api_key;
			$data['ucenter_api_url'] = $ucenter_api_url;
			$data['ucenter_admin_url'] = $ucenter_admin_url;
			$data['ucenter_app_id']  = $ucenter_app_id;

			if (is_file(INI_PATH . '/ucenter_api_' . Yf_Registry::get('server_id') . '.ini.php'))
			{
				$file = INI_PATH . '/ucenter_api_' . Yf_Registry::get('server_id') . '.ini.php';
			}
			else
			{
				$file = INI_PATH . '/ucenter_api.ini.php';
			}

			if (!Yf_Utils_File::generatePhpFile($file, $data))
			{
				$status = 250;
				$msg    = _('生成配置文件错误!');
			}


			$data = $this->getUrl('Config', 'editUcenterApi');
		}
		else
		{
			$status = 250;
			$msg    = isset($init_rs['msg']) ? $init_rs['msg'] : _('请求错误!');
		}


		$this->data->addBody(-140, $data = array(), $msg, $status);
	}


	/**
	 * 设置用户中心API网址及key - 后台独立使用
	 *
	 * @access public
	 */
	public function editPaycenterApi()
	{
		$paycenter_api_row = request_row('paycenter_api');

		$paycenter_api_key = $paycenter_api_row['paycenter_api_key'];
		$paycenter_api_url = $paycenter_api_row['paycenter_api_url'];
		$paycenter_app_id  = 105;


		$paycenter_api_name = $paycenter_api_row['paycenter_api_name'];

		/*
		//先检测API是否正确
		$key                = $paycenter_api_key;
		$url                = $paycenter_api_url;
		$formvars           = array();
		$formvars['app_id'] = $paycenter_app_id;
		$init_rs            = get_url_with_encrypt($key, sprintf('%s?ctl=Api&met=checkApi&typ=json', $url), $formvars);
		*/
		$data = array();

		if (true || 200 == @$init_rs['status'])
		{
			/*
			//读取服务列表
			$data   = $init_rs['data'];
			$status = 200;
			$msg    = isset($init_rs['msg']) ? $init_rs['msg'] : _('sucess');
			*/

			//

			$data                    = array();
			$data['paycenter_api_key'] = $paycenter_api_key;
			$data['paycenter_api_url'] = $paycenter_api_url;
			$data['paycenter_app_id']  = $paycenter_app_id;
			$data['paycenter_api_name']  = $paycenter_api_name;

			if (is_file(INI_PATH . '/paycenter_api_' . Yf_Registry::get('server_id') . '.ini.php'))
			{
				$file = INI_PATH . '/paycenter_api_' . Yf_Registry::get('server_id') . '.ini.php';
			}
			else
			{
				$file = INI_PATH . '/paycenter_api.ini.php';
			}

			if (!Yf_Utils_File::generatePhpFile($file, $data))
			{
				$status = 250;
				$msg    = _('生成配置文件错误!');
			}
			else
			{
				$data = $this->getUrl('Config', 'editPaycenterApi');

				$status = 200;
				$msg    = _('设置成功!');
			}


		}
		else
		{
			$status = 250;
			$msg    = isset($init_rs['msg']) ? $init_rs['msg'] : _('请求错误!');
		}


		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 设置商城API网址及key - 后台独立使用
	 *
	 * @access public
	 */
	public function editShopApi()
	{
		$shop_api_row = request_row('shop_api');

		$shop_api_key = $shop_api_row['shop_api_key'];
		$shop_api_url = $shop_api_row['shop_api_url'];
		$shop_admin_url = $shop_api_row['shop_admin_url'];
		$shop_wap_url = $shop_api_row['shop_wap_url'];
		$shop_app_id  = Yf_Registry::get('shop_app_id');

		//先检测API是否正确
		$key = Yf_Registry::get('paycenter_api_key');
		$url = Yf_Registry::get('paycenter_api_url');
		$app_id = Yf_Registry::get('paycenter_app_id');

		$formvars                     = array();
		$formvars['app_id']       = $app_id;
		$formvars['shop_app_id']  = $shop_app_id;
		$formvars['shop_api_key'] = $shop_api_key;
		$formvars['shop_api_url'] = $shop_api_url;
		$formvars['shop_admin_url'] = $shop_admin_url;
		$formvars['shop_wap_url']     = $shop_wap_url;

		//自己调用,直接生成
		//$init_rs         = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Config&met=checkApi&typ=json', $url), $formvars);
		$init_rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Config&met=editApi&typ=json', $url), $formvars);

		$data = array();

		if (200 == $init_rs['status'])
		{
			//读取服务列表
			$data   = $init_rs['data'];
			$status = 200;
			$msg    = isset($init_rs['msg']) ? $init_rs['msg'] : _('sucess');


			//

			$data                 = array();
			$data['shop_api_key'] = $shop_api_key;
			$data['shop_api_url'] = $shop_api_url;
			$data['shop_app_id']  = $shop_app_id;
			$data['shop_admin_url']  = $shop_admin_url;
			$data['shop_wap_url'] = $shop_wap_url;

			if (is_file(INI_PATH . '/shop_api_' . Yf_Registry::get('server_id') . '.ini.php'))
			{
				$file = INI_PATH . '/shop_api_' . Yf_Registry::get('server_id') . '.ini.php';
			}
			else
			{
				$file = INI_PATH . '/shop_api.ini.php';
			}

			if (!Yf_Utils_File::generatePhpFile($file, $data))
			{
				$status = 250;
				$msg    = _('生成配置文件错误!');
			}
		}
		else
		{
			$status = 250;
			$msg    = isset($init_rs['msg']) ? $init_rs['msg'] : _('请求错误!');
		}


		$this->data->addBody(-140, $data = array(), $msg, $status);
	}



	/**
	 * 列表数据
	 *
	 * @access public
	 */
	public function type()
	{
		$supply_type_rows = array();

		//类似数据可以放到前端整理
		$supply_type_row                = array();
		$supply_type_row['sort_index']  = 0;
		$supply_type_row['id']          = 1;
		$supply_type_row['parent_id']   = 0;
		$supply_type_row['detail']      = true;
		$supply_type_row['type_number'] = 'trade';
		$supply_type_row['level']       = 1;
		$supply_type_row['status']      = 0;
		$supply_type_row['remark']      = null;
		$supply_type_row['name']        = 'aaaa';

		$supply_type_rows[] = $supply_type_row;

		$data          = array();
		$data['items'] = $supply_type_rows;

		$this->data->addBody(-140, $data);
	}


	public function upload()
	{
		include $this->view->getView();
	}

	public function cropperImage()
	{
		include $this->view->getView();
	}

	public function image()
	{
		include $this->view->getView();
	}
	
	
	public function update()
	{
		 
		
		include $view = $this->view->getView();
	}
	
	public function updatePay()
	{
		 
		
		include $view = $this->view->getView();
		
	}
	
	public function upgradePayContainer()
	{
		
		include $view = $this->view->getView();
		
	}
	
	
	public function upgradePay()
	{
		//从API获取。
		$data = $this->getUrl('Config', 'update');
		
		$change_file_row = $data['change_file_row'];
		$version_row     = $data['version_row'];
		$client_version  = $data['client_version'];
		$partial         = $data['partial'];
		
		
		if ($partial && request_int('upgrade') || request_int('force-upgrade'))
		{
			//url 带加密数据跳转
			
			$data = $this->getUrl('Config', 'update', 'e', true);
			
		}
		
	}
	
}

?>
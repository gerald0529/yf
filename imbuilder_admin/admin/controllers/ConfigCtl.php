<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>  yf_AppController   AdminController
 */
class ConfigCtl extends AdminController
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}


	/**
	 * 设置用户中心API网址及key - 后台独立使用
	 * @access public
	 */

	public function editUcenterApi()
	{
        $ucenter_api_row = request_row('ucenter_api');

        $ucenter_api_key       = $ucenter_api_row['ucenter_api_key'];
        $ucenter_api_url       = $ucenter_api_row['ucenter_api_url'];
        $ucenter_admin_api_url = $ucenter_api_row['ucenter_admin_api_url'];
        $ucenter_app_id        = 104;

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

            $data                          = array();
            $data['ucenter_api_key']       = $ucenter_api_key;
            $data['ucenter_api_url']       = $ucenter_api_url;
            $data['ucenter_app_id']        = $ucenter_app_id;
            $data['ucenter_admin_api_url'] = $ucenter_admin_api_url;
			$data_test = $data;
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
	public function editImbuilderApi()
	{
		$im_api_row = request_row('im_api');

		$im_api_key = $im_api_row['im_api_key'];
		$im_api_url = $im_api_row['im_api_url'];
		$im_app_id  = 103;


		$im_api_name = $im_api_row['im_api_name'];


		$data = array();

		if (true || 200 == @$init_rs['status'])
		{

			$data                    = array();
			$data['im_api_key'] = $im_api_key;
			$data['im_api_url'] = $im_api_url;
			$data['im_app_id']  = $im_app_id;
			$data['im_api_name']  = $im_api_name;

			$file = INI_PATH . '/im_api.ini.php';

			if (!Yf_Utils_File::generatePhpFile($file, $data))
			{
				$status = 250;
				$msg    = _('生成配置文件错误!');
			}
			else
			{
				$data = $this->getUrl('Config', 'editImbuilderApi');

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
        $shop_admin_api_url = $shop_api_row['shop_admin_api_url'];
		$shop_app_id  = 102;
		$data = array();

		if (true || 200 == @$init_rs['status'])
		{
			$data                    = array();
			$data['shop_api_key'] = $shop_api_key;
			$data['shop_api_url'] = $shop_api_url;
			$data['shop_app_id']  = $shop_app_id;
			$data['shop_admin_api_url']  = $shop_admin_api_url;

			$file = INI_PATH . '/shop_api.ini.php';

			if (!Yf_Utils_File::generatePhpFile($file, $data))
			{
				$status = 250;
				$msg    = _('生成配置文件错误!');
			}
			else
			{
				$data = $this->getUrl('Config', 'editShopApi');

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
//	 *****************原来代码*************
//		$shop_api_row = request_row('shop_api');
//
//		$shop_api_key = $shop_api_row['shop_api_key'];
//		$shop_api_url = $shop_api_row['shop_api_url'];
//		$shop_app_id  = Yf_Registry::get('shop_app_id');
//
//		//先检测API是否正确
//		$key = Yf_Registry::get('shop_api_key');
//		$url = $shop_api_url;
//
//		$formvars                     = array();
//		$formvars['app_id']           = $shop_app_id;
//		$formvars['shop_app_id_new']  = $shop_app_id;
//		$formvars['shop_api_key_new'] = $shop_api_key;
//		$formvars['shop_api_url_new'] = $shop_api_url;
//		//$formvars['shop_wap_url']     = $shop_wap_url;
//
//		//自己调用,直接生成
//		//$init_rs         = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Config&met=checkApi&typ=json', $url), $formvars);
//		$init_rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Config&met=editApi&typ=json', $url), $formvars);
//
//		$data = array();
//
//		if (200 == $init_rs['status'])
//		{
//			//读取服务列表
//			$data   = $init_rs['data'];
//			$status = 200;
//			$msg    = isset($init_rs['msg']) ? $init_rs['msg'] : _('sucess');
//
//
//			//
//
//			$data                 = array();
//			$data['shop_api_key'] = $shop_api_key;
//			$data['shop_api_url'] = $shop_api_url;
//			$data['shop_app_id']  = $shop_app_id;
//			//$data['shop_wap_url'] = $shop_wap_url;
//
//			$file = INI_PATH . '/shop_api.ini.php';
//
//			if (!Yf_Utils_File::generatePhpFile($file, $data))
//			{
//				$status = 250;
//				$msg    = _('生成配置文件错误!');
//			}
//		}
//		else
//		{
//			$status = 250;
//			$msg    = isset($init_rs['msg']) ? $init_rs['msg'] : _('请求错误!');
//		}
//
//
//		$this->data->addBody(-140, $data = array(), $msg, $status);
//		**************************
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


//	public function upload()
//	{
//		include $this->view->getView();
//	}

	public function cropperImage()
	{
		include $this->view->getView();
	}

	public function image()
	{
		include $this->view->getView();
	}
    public function cacheManage()
    {
        include $view = $this->view->getView();;

    }
}

?>
<?php

class CategoryCtl extends AdminController
{
	public function index()
	{
		include $this->view->getView();
	}

	/**
	 * 获取lists
	 *
	 * @access public
	 */
	public function lists()
	{
		if (request_string('type_number'))
		{
			$type_name = explode('_', request_string('type_number'));
			array_map('ucfirst', $type_name);

			call_user_func(array(
							   $this,
							   'list' . implode('', $type_name)
						   ));
		}
	}


	/**
	 * 获取用户 lists
	 *
	 * @access public
	 */
	public function listUser()
	{
		$User_BaseModel = new User_BaseModel();

		$data = $User_BaseModel->getBaseList();

		$this->data->addBody(-140, $data);
	}

	/**
	 *
	 *
	 * @access public
	 */
	public function listGoodsState()
	{
		//本地读取远程信息
		$key = Yf_Registry::get('shop_api_key');;
		$url         = Yf_Registry::get('shop_api_url');
		$shop_app_id = Yf_Registry::get('shop_app_id');

		$formvars           = $_POST;
		$formvars['app_id'] = $shop_app_id;

		foreach ($_GET as $k => $item)
		{
			if ('ctl' != $k && 'met' != $k && 'debug' != $k)
			{
				$formvars[$k] = $item;
			}
		}

		$init_rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_%s&met=%s&typ=json', $url, 'Goods_Goods', 'getStateCombo'), $formvars);

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

		$this->data->addBody(-140, $data, $msg, $status);
	}


	/**
	 *
	 *
	 * @access public
	 */
	public function listDateFormat()
	{
		$data                = array();
		$data['date_format'] = array(
			array(
				'id' => _('Y-m-d'),
				'name' => _('Y-m-d')
			),
			array(
				'id' => _('Y年m月d日'),
				'name' => _('Y年m月d日')
			),
			array(
				'id' => _('date_format-1'),
				'name' => _('date_format-1')
			),
			array(
				'id' => _('date_format-2'),
				'name' => _('date_format-2')
			),
			array(
				'id' => _('date_format-3'),
				'name' => _('date_format-3')
			),
		);
		$data['time_format'] = array(
			array(
				'id' => _('H:i:s'),
				'name' => _('H:i:s')
			),
			array(
				'id' => _('H时i分s秒'),
				'name' => _('H时i分s秒')
			),
			array(
				'id' => _('time_format-1'),
				'name' => _('time_format-1')
			),
			array(
				'id' => _('time_format-2'),
				'name' => _('time_format-2')
			),
			array(
				'id' => _('time_format-3'),
				'name' => _('time_format-3')
			),
		);

		$this->data->addBody(-140, $data);
	}

	/**
	 *
	 *
	 * @access public
	 */
	public function listTimeFormat()
	{
		$this->listDateFormat();
	}


	/**
	 *
	 *
	 * @access public
	 */
	public function listGoodsCat()
	{
		//本地读取远程信息
		$key = Yf_Registry::get('shop_api_key');;
		$url         = Yf_Registry::get('shop_api_url');
		$shop_app_id = Yf_Registry::get('shop_app_id');

		$formvars           = $_POST;
		$formvars['app_id'] = $shop_app_id;

		foreach ($_GET as $k => $item)
		{
			if ('ctl' != $k && 'met' != $k && 'debug' != $k)
			{
				$formvars[$k] = $item;
			}
		}
		$init_rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_%s&met=%s&typ=json', $url, 'Goods_Cat', 'cat'), $formvars);

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

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 *
	 *
	 * @access public
	 */
	public function listDistrict()
	{
		$data = $this->getUrl('Base_District', 'district');

		$this->data->addBody(-140, $data);
	}

	/**
	 *
	 *
	 * @access public
	 */
	public function listGoodsType()
	{
		$data = $this->getUrl('Goods_Type', 'lists');
	}


	/**
	 *
	 *
	 * @access public
	 */
	public function listBrand()
	{
		//本地读取远程信息
		$key = Yf_Registry::get('shop_api_key');;
		$url         = Yf_Registry::get('shop_api_url');
		$shop_app_id = Yf_Registry::get('shop_app_id');

		$formvars           = $_POST;
		$formvars['app_id'] = $shop_app_id;

		foreach ($_GET as $k => $item)
		{
			if ('ctl' != $k && 'met' != $k && 'debug' != $k)
			{
				$formvars[$k] = $item;
			}
		}

		$init_rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_%s&met=%s&typ=json', $url, 'Goods_Goods', 'getBrand'), $formvars);

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

		$this->data->addBody(-140, $data, $msg, $status);
	}

}

?>



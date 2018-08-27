<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Banchangle
 */
class Seller_Shop_DecorationCtl extends Seller_Controller
{

	public $shopBaseModel            = null;
	public $shopDecorationModel      = null;
	public $shopDecorationAlbumModel = null;
	public $shopDecorationBlockModel = null;

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
		$this->shopBaseModel            = new Shop_BaseModel();
		$this->shopDecorationModel      = new Shop_DecorationModel();
		$this->shopDecorationAlbumModel = new Shop_DecorationAlbumModel();
		$this->shopDecorationBlockModel = new Shop_DecorationBlockModel();

	}

	/**
	 * 装潢设置首页
	 *
	 * @access public
	 */
	public function decoration()
	{
		$act     = request_string("act");
		$shop_id = Perm::$shopId;
		if (empty($act))
		{
			//进入这个页面创建店铺装潢
			$shop_decoration_info = $this->shopDecorationModel->getOneByWhere(array('shop_id' => $shop_id));
			if (empty($shop_decoration_info))
			{
				//创建默认装修
				$param                    = array();
				$param['decoration_name'] = '默认装修';
				$param['shop_id']         = $shop_id;
				$decoration_id            = $this->shopDecorationModel->addDecoration($param);
			}
			else
			{
				$decoration_id = $shop_decoration_info['decoration_id'];
			}
			$renovation_list = $this->shopBaseModel->getOne($shop_id);
		}
		elseif ($act == "set")
		{

			$decoration_detail = $this->decorationList();
			$this->view->setMet('setdecoration');
		}
		include $this->view->getView();
	}

	/**
	 * 修改装潢设置
	 *
	 * @access public
	 */

	public function editRenovation()
	{
		$shop_id                          = Perm::$shopId;
		$renovation['is_renovation']      = request_int("is_renovation");
		$renovation['is_only_renovation'] = request_int("is_only_renovation");
		$renovation['is_index_left']      = request_int("is_index_left");

		$renovation_list = $this->shopBaseModel->editBase($shop_id, $renovation);
		if ($flag === FALSE)
		{
			$status = 250;
			$msg    = __('failure');
		}
		else
		{
			$status = 200;
			$msg    = __('success');
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

	public function decorationList()
	{
		$shop_id              = Perm::$shopId;
		$shop_decoration_info = $this->shopDecorationModel->getOneByWhere(array('shop_id' => $shop_id));
		$decoration_id        = $shop_decoration_info['decoration_id'];
		$decoration_detail    = $this->shopDecorationModel->getshopDecorationInfoDetail($decoration_id, $shop_id);
		if ($decoration_detail)
		{
			$decoration_detail["decoration_background_style"] = $this->shopDecorationModel->getDecorationBackgroundStyle($decoration_detail['decoration_setting']);
		}
		else
		{
			$decoration_detail = array();
		}
		$decoration_detail["seller_layout_no_menu"] = true;
		$decoration_detail["decoration_id"]         = $decoration_id;
		return $decoration_detail;
	}

	//添加模块
	public function decorationBlockAdd()
	{
		$decoration_id = request_int("decoration_id");
		$block_layout  = request_string("block_layout");
		$shop_id       = Perm::$shopId;
		//验证装修编号
		$condition                  = array();
		$condition['decoration_id'] = $decoration_id;
		$decoration_info            = $this->shopDecorationModel->getStoreDecorationInfo($condition, $shop_id);
		if (!$decoration_info)
		{
			$msg    = __('param_error');
			$status = 250;
			$this->data->addBody(-140, array(), $msg, $status);
		}
		//验证装修块布局
		$block_layout_array = $this->shopDecorationModel->getStoreDecorationBlockLayoutArray();
		if (!in_array($block_layout, $block_layout_array))
		{
			$msg    = __('param_error');
			$status = 250;
			$this->data->addBody(-140, array(), $msg, $status);
		}

		$param                  = array();
		$param['decoration_id'] = $decoration_id;
		$param['shop_id']       = $shop_id;
		$param['block_layout']  = $block_layout;
		$block_id               = $this->shopDecorationBlockModel->addDecorationBlock($param, TRUE);
		$block                  = array('block_id' => $block_id);

		if ($block_id)
		{
			ob_start();
			//Tpl::output('block', array('block_id' => $block_id));
			//Tpl::showpage('store_decoration_block', 'null_layout');
			include_once $this->tpl = TPL_PATH . '/' . implode('/', explode('_', $this->ctl)) . '/store_decoration_block.php';
			$temp = ob_get_contents();
			ob_end_clean();

			$data['html'] = $temp;
		}
		else
		{
			$data['error'] = '添加失败';
		}

		$data['html'] = $temp;
		echo json_encode($data);
		die;


	}


	/**
	 * 装修块排序
	 */
	public function decorationBlockSort()
	{
		$sort_array = explode(',', rtrim(request_string("sort_string"), ','));

		$sort = 1;
		foreach ($sort_array as $value)
		{
			$this->shopDecorationBlockModel->editDecorationBlock($value, array('block_sort' => $sort));
			$sort = $sort + 1;
		}

		$data            = array();
		$data['message'] = '保存成功';
		echo json_encode($data);
		die;
	}


	/**
	 * 装修块删除
	 */
	public function decorationBlockDel()
	{
		$block_id = intval(request_int("block_id"));

		$data   = array();
		$result = $this->shopDecorationBlockModel->removeDecorationBlock($block_id);

		if ($result)
		{
			$data['message'] = '删除成功';
		}
		else
		{
			$data['error'] = '删除失败';
		}
		echo json_encode($data);
		die;

	}

	//保存背景
	public function  decorationBackgroundSettingSave()
	{
		$decoration_id = intval(request_int('decoration_id'));
		//验证参数
		if ($decoration_id <= 0)
		{
			$data['error'] = __('param_error');
			echo json_encode($data);
			die;
		}

		$setting                            = array();
		$setting['background_color']        = request_string('background_color');
		$setting['background_image']        = request_string('background_image');
		$setting['background_image_repeat'] = request_string('background_image_repeat');
		$setting['background_position_x']   = request_string('background_position_x');
		$setting['background_position_y']   = request_string('background_position_y');
		$setting['background_attachment']   = request_string('background_attachment');

		//背景设置保存验证
		$validate_setting = $this->shopDecorationModel->_validate_background_setting_input($decoration_id, $setting);
		if (isset($validate_setting['error']))
		{
			$data['error'] = $validate_setting['error'];
			echo json_encode($data);
			die;
		}
		$data                         = array();
		$update                       = array();
		$update['decoration_setting'] = $setting;

		$result = $this->shopDecorationModel->editDecoration($decoration_id, $update);
		if ($result)
		{
			$data['decoration_background_style'] = $this->shopDecorationModel->getDecorationBackgroundStyle($validate_setting);
		}
		else
		{
			$data['error'] = '保存失败';
		}
		echo json_encode($data);
		die;
	}

	//Nav 保存
	public function decorationNavSave()
	{
		$decoration_id  = intval(request_int('decoration_id'));
		$nav            = array();
		$nav['display'] = request_string("nav_display");
		$nav['style']   = request_string("content");

		$data = array();

		//验证参数
		if ($decoration_id <= 0)
		{
			$data['error'] = __('param_error');
			echo json_encode($data);
			die;
		}


		$update                   = array();
		$update['decoration_nav'] = $nav;

		$result = $this->shopDecorationModel->editDecoration($decoration_id, $update);
		if ($result)
		{
			$data['message'] = '保存成功';
		}
		else
		{
			$data['error'] = '保存失败';
		}
		echo json_encode($data);
		die;
	}

	/**
	 * 装修banner保存
	 */
	public function decorationBannerSave()
	{

		$decoration_id     = intval(request_int('decoration_id'));
		$banner            = array();
		$banner['display'] = request_string("banner_display");
		$banner['image']   = request_string("content");
		$data              = array();

		//验证参数
		if ($decoration_id <= 0)
		{
			$data['error'] = __('param_error');
			echo json_encode($data);
			die;
		}
		$update                      = array();
		$update['decoration_banner'] = $banner;

		$result = $this->shopDecorationModel->editDecoration($decoration_id, $update);
		if ($result)
		{
			$data['message']   = '保存成功';
			$data['image_url'] = $banner['image'];
		}
		else
		{
			$data['error'] = '保存失败';
		}
		echo json_encode($data);
		die;
	}

	/**
	 * 商品搜索
	 */
	public function goodsSearch()
	{
		$GoodsCommonModel                = new Goods_CommonModel();
		$condition                       = array();
		$condition['shop_id']            = Perm::$shopId;
		$condition['common_name:LIKE']   = request_string('keyword') . '%';
		$condition['shop_status'] = Shop_BaseModel::SHOP_STATUS_OPEN;
		$condition['common_state'] = Goods_CommonModel::GOODS_STATE_NORMAL;
		$condition['common_verify'] = Goods_CommonModel::GOODS_VERIFY_ALLOW;


		$common_list                     = $GoodsCommonModel->getGoodsList($condition, array(), 1, 10);
		$decoration_detail['goods_list'] = $common_list['items'];

		$this->view->setMet('store_decoration_module.goods');
		include $this->view->getView();
	}


	/**
	 * 装修块保存
	 */
	public function decorationBlockSave()
	{
		$block_id    = intval(request_int('block_id'));
		$module_type = request_string('module_type');
		$data        = array();
		// $model_store_decoration = Model('store_decoration');
		//验证模块类型
		$block_type_array = $this->shopDecorationModel->getStoreDecorationBlockTypeArray();
		if (!in_array($module_type, $block_type_array))
		{
			$data['error'] = __('param_error');
			echo json_encode($data);
		}

		switch ($module_type)
		{
			case 'html':
				$content = $_POST['content'];
				break;
			default:
				$content = $_POST['content'];
		}

//        $condition = array();
//        $condition['block_id'] = $block_id;
		//$condition['shop_id'] = Perm::$shopId;

		$param                      = array();
		$param['block_content']     = $content;
		$param['block_full_width']  = intval(request_string('full_width'));
		$param['block_module_type'] = $module_type;
		$result                     = $this->shopDecorationBlockModel->editDecorationBlock($block_id, $param);

		if ($result !== false)
		{
			$data['message']                    = '保存成功';
			$decoration_detail['block_content'] = $content;
			ob_start();
			include_once $this->tpl = TPL_PATH . '/' . implode('/', explode('_', $this->ctl)) . '/store_decoration_module.' . $module_type . '.php';
			$temp = ob_get_contents();
			ob_end_clean();

			$data['html'] = $temp;
		}
		else
		{
			$data['error'] = '保存失败';
		}
		echo json_encode($data);
		die;
	}


}

?>
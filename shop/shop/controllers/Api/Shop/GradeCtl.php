<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author
 */
class Api_Shop_GradeCtl extends Api_Controller
{
//
	public $shopGradeModel = null;

	/**
	 * 初始化方法，构造函数
	 *
	 * @access public
	 */
	public function init()
	{
		$this->shopGradeModel = new Shop_GradeModel();
	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function shopIndex()
	{
		$order = array('shop_grade_sort' => 'desc');
		$data  = $this->shopGradeModel->listGradeWhere(array(), $order);
		$this->data->addBody(-140, $data);
	}

	/**
	 * 删除店铺等级
	 *
	 * @access public
	 */
	public function delShopLevel()
	{

		$shop_grade_id         = request_int('shop_grade_id');
		$del                   = $this->shopGradeModel->removeGrade($shop_grade_id);
		$data['shop_grade_id'] = $shop_grade_id;
		$this->data->addBody(-140, $data);

	}

	/**
	 * 新增店铺等级
	 *
	 * @access public
	 */
	public function addShopLevelrow()
	{
		//获取接收过来的数据
		$data['shop_grade_name']        = request_row("shop_grade_name");
		$data['shop_grade_goods_limit'] = request_row("shop_grade_goods_limit");
		$data['shop_grade_album_limit'] = request_row("shop_grade_album_limit");
		$data['shop_grade_fee']         = request_row("shop_grade_fee");
		$data['shop_grade_desc']        = request_row("shop_grade_desc");
		$data['shop_grade_function_id'] = request_row("shop_grade_function_id");
		$data['shop_grade_sort']        = request_row("shop_grade_sort");
		$data['shop_grade_template']    = "default";
		$flag                           = $this->shopGradeModel->addGrade($data);
		if ($flag)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}
		$data['shop_grade_id'] = $this->shopGradeModel->getGradeId($data);;
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改店铺列表页
	 *
	 * @access public
	 */
	public function editShopLevel()
	{
		$shop_grade_id = request_int('shop_grade_id');
		$data          = $this->shopGradeModel->getGrade($shop_grade_id);
		$this->data->addBody(-140, $data);
	}


	/**
	 * 修改店铺等级
	 *
	 * @access public
	 */
	public function editShopLevelrow()
	{
		//获取接收过来的数据
		$shop_grade_id                  = request_int('shop_grade_id');
		$data['shop_grade_name']        = request_row("shop_grade_name");
		$data['shop_grade_goods_limit'] = request_row("shop_grade_goods_limit");
		$data['shop_grade_album_limit'] = request_row("shop_grade_album_limit");
		$data['shop_grade_fee']         = request_row("shop_grade_fee");
		$data['shop_grade_desc']        = request_row("shop_grade_desc");
		$data['shop_grade_function_id'] = request_row("shop_grade_function_id");
		$data['shop_grade_sort']        = request_row("shop_grade_sort");

		$flag = $this->shopGradeModel->editGrade($shop_grade_id, $data);
		if ($flag !== FALSE)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}
		$data['shop_grade_id'] = $shop_grade_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	//获取模板
	public function getTemplist()
	{
		$shop_grade_id      = request_int('grade_id');
		$data['grade_temp'] = $this->shopGradeModel->getGradeTemp($shop_grade_id);
		$shopTemplateModel  = new Shop_TemplateModel();
		$data['temp']       = $shopTemplateModel->getByWhere();
		$this->data->addBody(-140, $data);
	}


	/**
	 * 修改绑定模板
	 *
	 * @access public
	 */
	public function editGradeTemp()
	{
		//获取接收过来的数据
		$shop_grade_id               = request_int('shop_grade_id');
		$shop_grade_template         = request_row("shop_grade_template");
		$data['shop_grade_template'] = implode(',', $shop_grade_template);

		$flag = $this->shopGradeModel->editGrade($shop_grade_id, $data);
		if ($flag !== FALSE)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}
		$data['shop_grade_id'] = $shop_grade_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}
}

?>
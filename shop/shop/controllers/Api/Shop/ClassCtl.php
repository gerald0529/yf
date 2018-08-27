<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author
 */
class Api_Shop_ClassCtl extends Api_Controller
{
//
	public $shopClassModel = null;

	/**
	 * 初始化方法，构造函数
	 *
	 * @access public
	 */
	public function init()
	{
		$this->shopClassModel = new Shop_ClassModel();
	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function shopIndex()
	{
		$order = array('shop_class_displayorder' => 'asc');
		$data  = $this->shopClassModel->listClassWhere(array(), $order);
		$this->data->addBody(-140, $data);
	}

	public function shopClass()
	{
		$order = array('shop_class_displayorder' => 'asc');
		$data  = $this->shopClassModel->getByWhere(array(),$order);
		$data = array_values($data);
		$result = array();
		$result[0]['id'] = 0;
		$result[0]['name'] = "店铺类型";
		foreach($data as $key=>$value)
		{
			$result[$key+1]['id'] = $value['shop_class_id'];
			$result[$key+1]['name'] = $value['shop_class_name'];
		}

		$this->data->addBody(-140, $result);
	}

	/**
	 *  删除店铺分类
	 *
	 * @access public
	 */
	public function delShopclass()
	{
		$shop_class_id         = request_int('shop_class_id');
		$del                   = $this->shopClassModel->removeClass($shop_class_id);
		$data['shop_class_id'] = $shop_class_id;
		$this->data->addBody(-140, $data);
	}

	/**
	 * 新增店铺分类
	 *
	 * @access public
	 */
	public function addShopclassrow()
	{
		//获取接收过来的数据
		$data['shop_class_name']         = request_row("shop_class_name");
		$data['shop_class_deposit']      = request_row("shop_class_deposit");
		$data['shop_class_displayorder'] = request_row("shop_class_displayorder");
		$add                             = $this->shopClassModel->addClass($data);
		//查询出插入的id
		$data['shop_class_id'] = $this->shopClassModel->getClassId($data);
		$this->data->addBody(-140, $data);
	}

	/**
	 * 单独店铺分类页面
	 *
	 * @access public
	 */
	public function editShopClass()
	{
		$shop_class_id = request_int('shop_class_id');
		$data          = $this->shopClassModel->getClass($shop_class_id);
		$this->data->addBody(-140, $data);
	}

	/**
	 * 修改店铺分类页面
	 *
	 * @access public
	 */
	public function editShopclassrow()
	{
		//获取接收过来的数据
		$shop_class_id                   = request_int('shop_class_id');
		$data['shop_class_name']         = request_row("shop_class_name");
		$data['shop_class_deposit']      = request_row("shop_class_deposit");
		$data['shop_class_displayorder'] = request_row("shop_class_displayorder");
		$add                             = $this->shopClassModel->editClass($shop_class_id, $data);
		$data['shop_class_id']           = $shop_class_id;
		$this->data->addBody(-140, $data);
	}

}

?>
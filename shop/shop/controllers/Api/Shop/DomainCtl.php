<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author
 */
class Api_Shop_DomainCtl extends Api_Controller
{
//
	public $shopDomainModel = null;

	/**
	 * 初始化方法，构造函数
	 *
	 * @access public
	 */
	public function init()
	{
		$this->shopDomainModel = new Shop_DomainModel();
	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function shopIndex()
	{
		$order = array('shop_id' => 'asc');
		$data  = $this->shopDomainModel->getDomainList(array(), $order);
		$this->data->addBody(-140, $data);
	}


	/**
	 * 二级域名列表页
	 *
	 * @access public
	 */
	public function getShopDomain()
	{
		$shop_id = request_int('shop_id');
		//获取单个二级域名
		$data = $this->shopDomainModel->getDomainRow($shop_id);
		$this->data->addBody(-140, $data);
	}

	/**
	 * 修改二级域名
	 *
	 * @access public
	 */
	public function  editShopDomainrow()
	{
		$shop_id                  = request_int('shop_id');
		$data['shop_sub_domain']  = request_row("shop_sub_domain");
		$data['shop_edit_domain'] = request_row("shop_edit_domain");
		$edit                     = $this->shopDomainModel->editDomain($shop_id, $data);
		$data['shop_id']          = $shop_id;
		$this->data->addBody(-140, $data);
	}
}

?>
<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Banchangle
 */
class Seller_Shop_BrandCtl extends Seller_Controller
{

	public $shopBaseModel   = null;
	public $goodsBrandModel = null;

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
		$this->shopBaseModel   = new Shop_BaseModel();
		$this->goodsBrandModel = new Goods_BrandModel();

	}

	/**
	 * 品牌首页
	 *
	 * @access public
	 */
	public function brand()
	{

		$shop_id      = Perm::$shopId;
		$brand_search = request_string('brand_name');
		$cond_row     = array('shop_id' => $shop_id);

		if ($brand_search)
		{

			$type            = 'brand_name:LIKE';
			$cond_row[$type] = '%' . $brand_search . '%';
		}
		$Yf_Page            = new Yf_Page();
		$Yf_Page->listRows  = 10;
		$rows               = $Yf_Page->listRows;
		$offset             = request_int('firstRow', 0);
		$page               = ceil_r($offset / $rows);
		$data               = $this->goodsBrandModel->getBrandCatlist($cond_row, array(), $page, $rows);
		$Yf_Page->totalRows = $data['totalsize'];
		$page_nav           = $Yf_Page->prompt();
		if ('json' == $this->typ)
		{
			$this->data->addBody(-140, $data);

		}
		else
		{
			include $this->view->getView();
		}
	}

	/**
	 * 删除未审核品牌
	 *
	 * @access public
	 */
	public function delBrand()
	{
		$brand_id   = request_int("id");
		$shop_id    = Perm::$shopId;
		$brand_list = $this->goodsBrandModel->getOne($brand_id);
		//判断删除操作是不是当前店铺
		if ($brand_list['shop_id'] == $shop_id)
		{
			$flag = $this->goodsBrandModel->removeBrand($brand_id);
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
		}
		else
		{
			$status = 250;
			$msg    = __('未审核的品牌不能删除');    
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}


	//仅仅加载一个页面
	public function addBrandInfo()
	{
		include $this->view->getView();
	}


	//加载一个页面并传值
	public function editBrandInfo()
	{
		$brand_id               = request_int('brand_id');
		$brand_info             = $this->goodsBrandModel->getOne($brand_id);
		$Goods_CatModel         = new Goods_CatModel();
                if(!empty($brand_info['cat_id'])){
                    $catlist                = $Goods_CatModel->getOne($brand_info['cat_id']);
                    $brand_info['cat_name'] = $catlist['cat_name'];
                }
		if ('json' == $this->typ)
		{
			$data['brand_info'] = $brand_info;
			$data['catlist']    = $catlist;
			$this->data->addBody(-140, $data);

		}
		else
		{
			include $this->view->getView();
		}
	}

	//添加一个品牌
	public function addBrandrow()
	{
		$data['brand_name']    = request_string("brand_name");
		$data['shop_id']       = Perm::$shopId;
		$data['brand_pic']     = request_string("brand_pic");
		$cat_id                = request_string("cat_id");
		if ($cat_id != "-1")
		{
			$data['cat_id'] = $cat_id;
		}
		$flag = $this->goodsBrandModel->addBrand($data);
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
		$this->data->addBody(-140, $data, $msg, $status);
	}


	//编辑一个品牌
	public function editBrandrow()
	{
		$brand_id              = request_int("brand_id");
		$brand['brand_name']    = request_string("brand_name");
		$brand['brand_pic']     = request_string("brand_pic");
		$cat_id                = request_string("cat_id");
		$shop_id               = Perm::$shopId;
		if (!empty($cat_id)&& $cat_id != "-1")
		{
			$brand['cat_id'] = $cat_id;
		}
		$brand_list = $this->goodsBrandModel->getOne($brand_id);
		//判断操作是不是当前店铺
		if ($brand_list['shop_id'] == $shop_id)
		{
                   
			$flag = $this->goodsBrandModel->editBrand($brand_id, $brand, false);
                        
			if ($flag === false)
			{
				$status = 250;
				$msg    = __('failure');
			}
			else
			{
                                $status = 200;
				$msg    = __('success');
			}
		}
                $data =array();
		$this->data->addBody(-140, $data, $msg, $status);
	}
}

?>
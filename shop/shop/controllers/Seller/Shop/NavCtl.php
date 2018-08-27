<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Banchangle
 */
class Seller_Shop_NavCtl extends Seller_Controller
{

	public $shopNavModel = null;

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
		$this->shopNavModel = new Shop_NavModel();
	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function nav()
	{
		$act = request_string('act');
		if ($act == 'edit')
		{
			$nav_id = request_int('nav_id');
			$data   = $this->shopNavModel->getNavinfo($nav_id);
			$this->view->setMet('setNav');

			
		}
		elseif ($act == 'add')
		{
			$this->view->setMet('setNav');
			$data = array();
		}
		else
		{
			$Yf_Page            = new Yf_Page();
			$Yf_Page->listRows  = 10;
			$rows               = $Yf_Page->listRows;
			$offset             = request_int('firstRow', 0);
			$page               = ceil_r($offset / $rows);
			$shop_id['shop_id'] = Perm::$shopId;
			$data               = $this->shopNavModel->getNavlist($shop_id, array(), $page, $rows);
			// var_dump($data);
			$Yf_Page->totalRows = $data['totalsize'];
			$page_nav           = $Yf_Page->prompt();

		}
		if ('json' == $this->typ)
		{

			$this->data->addBody(-140, $data);

		}
		else
		{
			include $this->view->getView();
		}
	}

	public function addNav()
	{
		$nav                = request_row("nav");
		$nav['create_time'] = date('Y-m-d H:i:s');
		$nav['shop_id']     = Perm::$shopId;
		$flag               = $this->shopNavModel->addNav($nav);
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
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function editNav()
	{
		$nav    = request_row("nav");
		$nav_id = request_int("id");
		$flag   = $this->shopNavModel->editNav($nav_id, $nav);
		if ($flag === "flase")
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

	public function delNav()
	{
		$nav_id = request_int("id");

		$flag = $this->shopNavModel->removeNav($nav_id);


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
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

}

?>
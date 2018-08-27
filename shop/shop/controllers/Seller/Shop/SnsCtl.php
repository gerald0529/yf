<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Banchangle
 */
class Seller_Shop_SnsCtl extends Seller_Controller
{

	public $shopBaseModel  = null;
	public $shopClassModel = null;
	public $shopGradeModel = null;

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
		$this->shopBaseModel  = new Shop_BaseModel();
		$this->shopClassModel = new Shop_ClassModel();
		$this->shopGradeModel = new Shop_GradeModel();
	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function index()
	{

		include $this->view->getView();
	}
}

?>
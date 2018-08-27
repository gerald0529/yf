<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_BrandCtl extends AdminController
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}

	/**
	 * 设置商城API网址及key - 后台独立使用
	 *
	 * @access public
	 */
	public function brand()
	{
		include $view = $this->view->getView();;
	}

	public function brandmanage()
	{
		include $view = $this->view->getView();;
	}
}

?>
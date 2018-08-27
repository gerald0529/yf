<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_SpecCtl extends AdminController
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
	public function spec()
	{
		include $view = $this->view->getView();;

	}
}

?>
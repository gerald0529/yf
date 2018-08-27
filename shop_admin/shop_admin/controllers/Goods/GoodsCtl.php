<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_GoodsCtl extends AdminController
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
	public function common()
	{
		include $view = $this->view->getView();;

	}


	public function goodsManage()
	{
		$data = $this->getUrl('Goods_Goods', 'getGoodsInfo');
		$json = json_encode($data);

		include $view = $this->view->getView();;

	}

}

?>
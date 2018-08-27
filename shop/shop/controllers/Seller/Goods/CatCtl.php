<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Seller_Goods_CatCtl extends Seller_Controller
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
	public function cat()
	{
		$cat_id    = request_string('cat_id', 0);
		$recursive = request_int('recursive', 0);

		$Goods_CatModel = new Goods_CatModel();
		$data           = $Goods_CatModel->getCatTreeData($cat_id, $recursive, 0, true);


		$this->data->addBody(-140, array_values($data));
	}
}

?>
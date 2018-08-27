<?php

if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author
 */
class Api_Analysis_GeneralCtl extends Api_Controller
{

	public $Analysis_PlatformGeneralModel = null;
	public $Analysis_PlatformTotalModel   = null;
	public $Analysis_PlatformGoodsModel   = null;
	public $Analysis_ShopGeneralModel     = null;


	/**
	 * 初始化方法，构造函数
	 *
	 * @access public
	 */
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
		$this->Analysis_PlatformGeneralModel = new Analysis_PlatformGeneralModel();
		$this->Analysis_PlatformTotalModel   = new Analysis_PlatformTotalModel();
		$this->Analysis_PlatformGoodsModel   = new Analysis_PlatformGoodsModel();
		$this->Analysis_ShopGeneralModel     = new Analysis_ShopGeneralModel();
	}


	public function general()
	{
		$cond_row['general_date']       = date("Y-m-d");
		$order                          = $this->Analysis_PlatformGeneralModel->getOneByWhere($cond_row);
		$data['t']['order_cash']        = $order['order_cash'];
		$data['t']['order_user_num']    = $order['order_user_num'];
		$data['t']['order_num']         = $order['order_num'];
		$data['t']['order_goods_num']   = $order['order_goods_num'];
		$data['t']['general_cash']      = round($order['order_cash'] / $order['order_goods_num'], 2);
		$data['t']['general_user_cash'] = round($order['order_cash'] / $order['order_user_num'], 2);
		$data['t']['user_new_num']      = $order['user_new_num'];
		$data['t']['shop_new_num']      = $order['shop_new_num'];
		$data['t']['goods_new_num']     = $order['goods_new_num'];
		$total                          = $this->Analysis_PlatformTotalModel->getOne(1);
		$data['t']['shop_num']          = $total['shop_num'];
		$data['t']['user_num']          = $total['user_num'];
		$data['t']['goods_num']         = $total['goods_num'];

		$date               = date("Y-m-d");
		$goods_list         = $this->Analysis_PlatformGoodsModel->listByWhere(array('goods_date' => $date), array('order_cash' => 'DESC'), 1, 10);
		$data['goods_list'] = $goods_list['items'];

		$shop_list         = $this->Analysis_ShopGeneralModel->listByWhere(array('general_date' => $date), array('order_cash' => 'DESC'), 1, 10);
		$data['shop_list'] = $shop_list['items'];

		$this->data->addBody(-140, $data);
	}
}

?>
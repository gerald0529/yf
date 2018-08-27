<?php

if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Seller_Statics_GeneralCtl extends Seller_Controller
{

	public $orderBaseModel  = null;
	public $orderGoodsModel = null;
	public $goodsBaseModel  = null;


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
		$this->orderBaseModel  = new Order_BaseModel();
		$this->orderGoodsModel = new Order_GoodsModel();
		$this->orderBaseModel  = new Order_BaseModel();
	}

	function get_weekinfo($month)
	{
		$weekinfo = array();
		$end_date = date('d', strtotime($month . ' +1 month -1 day'));
		for ($i = 1; $i < $end_date; $i = $i + 7)
		{
			$w = date('N', strtotime($month . '-' . $i));

			$weekinfo[] = array(
				date('Y-m-d', strtotime($month . '-' . $i . ' -' . ($w - 1) . ' days')),
				date('Y-m-d', strtotime($month . '-' . $i . ' +' . (7 - $w) . ' days'))
			);
		}
		return $weekinfo;
	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function general()
	{
		$order_cond_row['seller_id']            = Perm::$shopId;
		$order_cond_row['order_create_time:>='] = date("Y-m-d", time() - 29 * 86400);
		$order_data                             = $this->orderBaseModel->getByWhere($order_cond_row);
		$cash_month                             = 0;
		$buyer_month                            = array();
		$buyer_name                             = array();
		$buyer_cash                             = array();
		$order_month                            = count($order_data);
		$goods_month                            = array();
		$goods_name                             = array();
		$goods_num                              = array();
		$goods_price                            = array();

		foreach ($order_data as $v)
		{
			$goods_data = $this->orderGoodsModel->getOneByWhere(array('order_id' => $v['order_id']));
			if (in_array($goods_data['goods_id'], $goods_month))
			{
				$goods_num[$goods_data['goods_id']] += $goods_data['goods_num'];
				$goods_price[$goods_data['goods_id']] += $goods_data['goods_price'];
			}
			else
			{
				$goods_month[]                        = $goods_data['goods_id'];
				$goods_name[$goods_data['goods_id']]  = $goods_data['goods_name'];
				$goods_num[$goods_data['goods_id']]   = $goods_data['goods_num'];
				$goods_price[$goods_data['goods_id']] = $goods_data['goods_price'];
			}
			$cash_month += $v['order_amount'];
			if (in_array($v['buyer_id'], $buyer_month))
			{
				$buyer_cash[$v['buyer_id']] += $v['goods_amount'];
			}
			else
			{
				$buyer_month[]              = $v['buyer_id'];
				$buyer_name[$v['buyer_id']] = $v['buyer_name'];
				$buyer_cash[$v['buyer_id']] = $v['goods_amount'];
			}
		}

		//统计数据
		$data['general']['month_cash']         = $cash_month;
		$data['general']['month_buyer']        = count($buyer_month);
		$data['general']['month_order']        = $order_month;
		$data['general']['month_goods']        = count($goods_month);
		$data['general']['general_buyer_cash'] = round($cash_month / $data['general']['month_buyer'], 2);
		$data['general']['general_order_cash'] = round($cash_month / $order_month, 2);

		//图表数据
		for ($i = 0; $i < 30; $i++)
		{
			$pic_cond_row['seller_id']            = Perm::$shopId;
			$date_format                          = date("Y-m-d", time() - $i * 86400);
			$pic_cond_row['order_create_time:<='] = $date_format . " 23:59:59";
			$pic_cond_row['order_create_time:>='] = $date_format . " 00:00:00";
			$order_data                           = $this->orderBaseModel->getByWhere($pic_cond_row);
			$cash                                 = 0;
			foreach ($order_data as $v)
			{
				$cash += $v['order_amount'];
			}
			$data['pic'][$date_format] = $cash;
		}

		//产品销量榜
		$list_goods = arsort($goods_num);
		$list_goods = array_slice($list_goods, 0, 10);
		foreach ($list_goods as $k => $v)
		{
			$goods['name']   = $goods_name[$k];
			$goods['num']    = $goods_num[$k];
			$data['listm'][] = $goods;
		}


		//同行产品销量榜
		$order_cond_row2['seller_id:!=']         = Perm::$shopId;
		$order_cond_row2['order_create_time:>='] = date("Y-m-d", time() - 29 * 86400);
		$order_data                              = $this->orderBaseModel->getByWhere($order_cond_row2);
		$goods_month                             = array();
		$goods_name                              = array();
		$goods_num                               = array();

		foreach ($order_data as $v)
		{
			$goods_data = $this->orderGoodsModel->getOneByWhere(array('order_id' => $v['order_id']));
			if (in_array($goods_data['goods_id'], $goods_month))
			{
				$goods_num[$goods_data['goods_id']] += $goods_data['goods_num'];
			}
			else
			{
				$goods_month[]                       = $goods_data['goods_id'];
				$goods_name[$goods_data['goods_id']] = $goods_data['goods_name'];
				$goods_num[$goods_data['goods_id']]  = $goods_data['goods_num'];
			}
		}

		$list_goods = arsort($goods_num);
		$list_goods = array_slice($list_goods, 0, 10);
		foreach ($list_goods as $k => $v)
		{
			$goods['name']   = $goods_name[$k];
			$goods['num']    = $goods_num[$k];
			$data['listy'][] = $goods;
		}
		include $this->view->getView();
	}


	public function getProductDetail()
	{
		$order_cond_row['seller_id'] = Perm::$shopId;
		$start_time                  = request_string("start_time");
		$end_time                    = request_string("end_time");
		if ($start_time && $end_time)
		{
			$order_cond_row['order_create_time:>='] = $start_time . " 00:00:00";
			$order_cond_row['order_create_time:<='] = $end_time . " 23:59:59";
		}
		$catid = request_int("catid");
		if ($catid)
		{
			$goods_cond_row['goods_class_id'] = $catid;
		}

		$goods_month = array();
		$goods_name  = array();
		$goods_num   = array();
		$goods_price = array();

		$order_data = $this->orderBaseModel->getByWhere($order_cond_row);
		foreach ($order_data as $v)
		{
			$goods_cond_row['order_id'] = $v['order_id'];
			$goods_data                 = $this->orderGoodsModel->getOneByWhere($goods_cond_row);
			if (in_array($goods_data['goods_id'], $goods_month))
			{
				$goods_num[$goods_data['goods_id']] += $goods_data['goods_num'];
				$goods_price[$goods_data['goods_id']] += $goods_data['goods_price'];
			}
			else
			{
				$goods_month[]                        = $goods_data['goods_id'];
				$goods_name[$goods_data['goods_id']]  = $goods_data['goods_name'];
				$goods_num[$goods_data['goods_id']]   = $goods_data['goods_num'];
				$goods_price[$goods_data['goods_id']] = $goods_data['goods_price'];
			}
		}

		foreach ($goods_month as $v)
		{
			$goods['name']   = $goods_name[$v];
			$price           = $this->goodsBaseModel->getOne($v);
			$goods['price']  = $price['goods_price'];
			$goods['num']    = $goods_num[$v];
			$goods['amount'] = $goods_price[$v];
			$data[]          = $goods;
		}

		include $this->view->getView();
	}


	public function getHotProduct()
	{
		$order_cond_row['seller_id'] = Perm::$shopId;
		$start_time                  = request_string("start_time");
		$end_time                    = request_string("end_time");
		if ($start_time && $end_time)
		{
			$order_cond_row['order_create_time:>='] = $start_time . " 00:00:00";
			$order_cond_row['order_create_time:<='] = $end_time . " 23:59:59";
		}

		$goods_month = array();
		$goods_name  = array();
		$goods_num   = array();
		$goods_price = array();

		$order_data = $this->orderBaseModel->getByWhere($order_cond_row);
		foreach ($order_data as $v)
		{
			$goods_cond_row['order_id'] = $v['order_id'];
			$goods_data                 = $this->orderGoodsModel->getOneByWhere($goods_cond_row);
			if (in_array($goods_data['goods_id'], $goods_month))
			{
				$goods_num[$goods_data['goods_id']] += $goods_data['goods_num'];
				$goods_price[$goods_data['goods_id']] += $goods_data['goods_price'];
			}
			else
			{
				$goods_month[]                        = $goods_data['goods_id'];
				$goods_name[$goods_data['goods_id']]  = $goods_data['goods_name'];
				$goods_num[$goods_data['goods_id']]   = $goods_data['goods_num'];
				$goods_price[$goods_data['goods_id']] = $goods_data['goods_price'];
			}
		}

		$list_goods = arsort($goods_num);
		$list_goods = array_slice($list_goods, 0, 30);

		foreach ($list_goods as $k => $v)
		{
			$goods['name'] = $goods_name[$k];
			$goods['num']  = $goods_num[$k];
			$data['num'][] = $goods;
		}

		$list_goods2 = arsort($goods_price);
		$list_goods2 = array_slice($list_goods2, 0, 30);

		foreach ($list_goods2 as $k => $v)
		{
			$goods['name']    = $goods_name[$k];
			$goods['amount']  = $goods_price[$k];
			$data['amount'][] = $goods;
		}

		include $this->view->getView();
	}


	public function getOperationReport()
	{
		$order_cond_row['seller_id'] = Perm::$shopId;
		$start_time                  = request_string("start_time");
		$end_time                    = request_string("end_time");
		if ($start_time && $end_time)
		{
			$order_cond_row['order_create_time:>='] = $start_time . " 00:00:00";
			$order_cond_row['order_create_time:<='] = $end_time . " 23:59:59";
		}
		$order_row['order_goods_amount'] = "DESC";
		$order_data                      = $this->orderBaseModel->getByWhere($order_cond_row, $order_row);
	}

}

?>
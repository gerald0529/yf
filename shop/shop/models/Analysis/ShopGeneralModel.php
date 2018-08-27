<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 *
 *
 * @category   Framework
 * @package    __init__
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2016, 黄新泽
 * @version    1.0
 * @todo
 */
class Analysis_ShopGeneralModel extends Analysis_ShopGeneral
{
	public function getShop($shop_id = null, $date = '2000-01-01')
	{
		if ($shop_id)
		{
			$shop_id_str = ' shop_id = ' . $shop_id;
		}
		else
		{
			$shop_id_str = '';
		}

		$sql = '
			SELECT count(shop_general_id) num,
				SUM(order_cash) AS order_cash,
				SUM(order_goods_num) AS order_goods_num,
				SUM(order_num) AS order_num,
				SUM(order_user_num) AS order_user_num,
				SUM(goods_favor_num) AS goods_favor_num,
				SUM(shop_favor_num) AS shop_favor_num
			FROM ' . $this->_tableName . '
			WHERE ' . $shop_id_str . '  AND general_date >= "' . $date . '"
			GROUP BY shop_id
		';

		$data = $this->sql->getRow($sql);
		
		if (!is_array($data))
		{
			$data = array();
		}
		
		return $data;

	}

	//单品排行
	public function getShopGoodsTop($shop_id = null, $date = '2000-01-01', $order_status = array(Order_StateModel::ORDER_WAIT_PAY))
	{
		if ($shop_id)
		{
			$shop_id_str = ' shop_id = ' . $shop_id;
		}
		else
		{
			$shop_id_str = '';
		}

//		$order_status[] = Order_StateModel::ORDER_PAYED;
//		$order_status[] = Order_StateModel::ORDER_WAIT_PREPARE_GOODS;
//		$order_status[] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
		$order_status[] = Order_StateModel::ORDER_RECEIVED;
		$order_status[] = Order_StateModel::ORDER_FINISH;
//		$order_status[] = Order_StateModel::ORDER_REFUND;
//		$order_status[] = Order_StateModel::ORDER_REFUND_FINISH;
//		$order_status[] = Order_StateModel::ORDER_SELF_PICKUP;
		$sql = '
			SELECT
				SUM(order_goods_num) as goods_num,
				goods_id,
				goods_name,
				goods_image,
				goods_price,
				common_id,
				order_goods_num
			FROM ' . TABEL_PREFIX . 'order_goods
			WHERE ' . $shop_id_str . '  AND  order_goods_status IN (' . implode(', ', $order_status) . ') AND  order_goods_time >= "' . $date . '"
			GROUP BY goods_id ORDER BY goods_num DESC
		';

		$data = $this->sql->getAll($sql);

		if (!$data)
		{
			$data = array();
		}

		return $data;

	}


	public function getBySql($field, $where = NULL, $group = NULL, $order = NULL, $limit = NULL)
	{
		$fieldtxt = implode(",", $field);
		$wheretxt = "";
		if (!empty($where))
		{
			$wheretxt .= " where 1";
			foreach ($where as $k => $v)
			{
				$arr        = explode(":", $k);
				$fieldwhere = $arr[0];
				$flagwhere  = isset($arr[1]) ? $arr[1] : "=";
				$wheretxt .= " and {$fieldwhere}{$flagwhere}'{$v}'";
			}
		}
		if ($group)
		{
			$wheretxt .= " group by {$group}";
		}
		if (!empty($order))
		{
			$wheretxt .= " order by ";
			$ordertxt = "";
			foreach ($order as $k => $v)
			{
				$ordertxt .= "{$k} {$v},";
			}
			$ordertxt = trim($ordertxt, ",");
			$wheretxt .= $ordertxt;
		}
		if (!empty($limit))
		{
			$limittxt = implode(",", $limit);
			$wheretxt .= " limit {$limittxt}";
		}
		$sql = "select {$fieldtxt} from {$this->_tableName} {$wheretxt}";
		//echo $sql;die;
		$data = $this->sql->getAll($sql);
		return $data;
	}
}

?>
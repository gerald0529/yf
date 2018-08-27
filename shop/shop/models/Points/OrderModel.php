<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/15
 * Time: 17:57
 */
class Points_OrderModel extends Points_Order
{
	const UNORDER   = 1;  //已下单
	const DELIVERED = 2;//已发货
	const CONFIRM   = 3; //确认收货
	const CANCEL    = 4;  //取消

	public static $order_state_map = array(
		self::UNORDER => '已下单',
		self::DELIVERED => '已发货',
		self::CONFIRM => '确认收货',
		self::CANCEL => '取消'
	);

	public $Points_OrderGoodsModel   = null;
	public $Points_OrderAddressModel = null;

	public function __construct()
	{
		parent::__construct();
		$this->Points_OrderGoodsModel   = new Points_OrderGoodsModel();
		$this->Points_OrderAddressModel = new Points_OrderAddressModel();
	}

    //多条件获取积分商品列表
	public function getPointsOrderList($cond_row = array(), $order_row = array('points_order_id'=>'DESC'), $page = 1, $rows = 100)
	{
		$rows = $this->listByWhere($cond_row, $order_row, $page, $rows);
		if ($rows['items'])
		{
			foreach ($rows['items'] as $key => $value)
			{
				$rows['items'][$key]['points_orderstate_label'] = self::$order_state_map[$value['points_orderstate']];
			}
		}
		return $rows;
	}

    //多条件获取积分商品列表
	public function getPointsOrderListByWhere($cond_row, $order_row = array('points_order_id'=>'DESC'), $page, $rows)
	{
		$order_rows = $this->listByWhere($cond_row, $order_row, $page, $rows);

		if ($order_rows['items'])
		{
			foreach ($order_rows['items'] as $key => $value)
			{
				$points_order_id_row[] = $value['points_order_rid'];
			}
			if ($points_order_id_row)
			{
				$cond_row_goods['points_orderid:IN'] = $points_order_id_row;
				$points_order_goods_row              = $this->Points_OrderGoodsModel->getPointsOrderGoodsByWhere($cond_row_goods);
				
				if ($points_order_goods_row)
				{
					foreach ($points_order_goods_row as $key => $value)
					{
						$points_order_goods[$value['points_orderid']][] = $value;
					}
				}
			}

			foreach ($order_rows['items'] as $key => $value)
			{
				if ($points_order_goods[$value['points_order_rid']])
				{
					$order_rows['items'][$key]['points_ordergoods_list'] = $points_order_goods[$value['points_order_rid']];
				}
			}
		}
		return $order_rows;
	}

	/*points_order_id 为积分订单表主键id,并非订单id*/
	public function getPointsOrderInfo($points_order_id)
	{
		$row = $this->getOne($points_order_id);

		if ($row)
		{
			$deliver_address_row            = $this->Points_OrderAddressModel->getOneByWhere(array('points_orderid' => $row['points_order_rid']));
			$row['points_order_goods_list'] = $this->Points_OrderGoodsModel->getPointsOrderGoodsByWhere(array('points_orderid' => $row['points_order_rid']));
			$row['points_orderstate_label'] = self::$order_state_map[$row['points_orderstate']];
			if ($deliver_address_row)
			{
				$row['points_address']  = $deliver_address_row['points_address'];
                $row['points_mobphone'] = $deliver_address_row['points_mobphone'];
                $row['points_truename'] = $deliver_address_row['points_truename'];
			}
			else
			{
				$row['points_address']  = '';
				$row['points_mobphone'] = '';
			}

		}
		return $row;
	}

	//积分订单信息
	public function getOnePointsOrderByID($points_order_id)
	{
		return $this->getOne($points_order_id);
	}

	//用户已兑换的积分订单数量
	public function getUserPointsGoodsCount($user_id)
	{
		$cond_row['points_buyerid'] = $user_id;
		return $this->getNum($cond_row);
	}

	//添加积分换购订单
	public function addPointsOrder($cond_row, $flag)
	{
		return $this->add($cond_row, $flag);
	}

	//编辑积分订单
	public function editPointsOrder($points_order_id, $field_row)
	{
		return $this->edit($points_order_id, $field_row);
	}

	//删除积分订单
	public function removePointsOrder($points_order_id)
	{
		$del_flag = $this->remove($points_order_id);

		return $del_flag;
	}
}
<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Shop_CostModel extends Shop_Cost
{
	const UNSETTLED = 0; //未结算
	const SETTLED   = 1; //已结算

	const GROUPBUY = 1;	//团购
	const INCREASE = 2;	//加价购
	const DISCOUNT = 3;	//限时折扣
	const MEET = 4;	//限时折扣
	const VOUCHER = 5;	//代金券
	const PINTUAN = 6;	//拼团

	//结算店铺费用
	public function settleShopCost($cond_row = array(), $order_row = array())
	{
		//店铺活动费用
		$shop_cost_amount = 0;

		$data = $this->getByWhere($cond_row, $order_row);

		foreach ($data as $key => $val)
		{
			$shop_cost_amount += $val['cost_price'];
		}

		$id_row = array_keys($data);
		$this->editCost($id_row, array('cost_status' => Shop_CostModel::SETTLED));

		return $shop_cost_amount;

	}

	public function getCostExcel($cond_row = array(), $order_row = array())
	{
		$data = $this->getByWhere($cond_row, $order_row);

		return array_values($data);
	}

}

?>
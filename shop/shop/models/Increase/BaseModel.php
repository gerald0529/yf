<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/20
 * Time: 15:44
 */
class Increase_BaseModel extends Increase_Base
{

	const NORMAL   = 1;
	const FINISHED = 2;
	const CLOSED   = 3;

	public static $increases_state_map = array(
		self::NORMAL => '正常',
		self::FINISHED => '已结束',
		self::CLOSED => '管理员关闭',
	);

	public $Increase_GoodsModel       = null;
	public $Increase_RedempGoodsModel = null;
	public $Increase_RuleModel        = null;
	public $Goods_BaseModel           = null;

	public function __construct()
	{
		parent::__construct();
		$this->Increase_GoodsModel       = new Increase_GoodsModel();
		$this->Increase_RedempGoodsModel = new Increase_RedempGoodsModel();
		$this->Increase_RuleModel        = new Increase_RuleModel();
		$this->Goods_BaseModel           = new Goods_BaseModel();
	}

	/* 加价购活动列表
	*分页
	 * */
	public function getIncreaseActList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$rows = $this->listByWhere($cond_row, $order_row, $page, $rows);

		$expire_increase = array();
		foreach ($rows['items'] as $key => $value)
		{
			$rows['items'][$key]['increase_state_label'] = __(self::$increases_state_map[$value['increase_state']]);

			if (strtotime($value['increase_end_time']) < time()) //过期活动，更改状态
			{
				$rows['items'][$key]['increase_state']       = self::FINISHED;
				$rows['items'][$key]['increase_state_label'] = __(self::$increases_state_map[self::FINISHED]);
				$expire_increase[]                           = $value['increase_id'];
			}
		}

		$this->editIncrease($expire_increase, array('increase_state' => self::FINISHED));
		return $rows;
	}

	/*活动详情信息,活动下的商品、规则、换购商品*/
	public function getIncreaseActDetail($increase_id)
	{
		$Goods_BaseModel = new Goods_BaseModel();
		$row                     = array();
		$cond_row['increase_id'] = $increase_id;

		$row          = $this->getOne($increase_id);
		$row['goods'] = $this->Increase_GoodsModel->getIncreaseGoodsByWhere($cond_row, array('increase_goods_id' => 'ASC'));

		if ($row['goods'])
		{
			$increase_goods_rows   = array(); //参加活动的所有商品goods_id
			$delete_increase_goods = array();
			foreach ($row['goods'] as $key => $value)
			{
				$increase_goods_rows[] = $value['goods_id'];
			}

			$goods_rows = $this->Goods_BaseModel->get($increase_goods_rows);

			foreach ($row['goods'] as $key => $value)
			{
				if (in_array($value['goods_id'], array_keys($goods_rows)))
				{
					$row['goods'][$key] = $goods_rows[$value['goods_id']];
				}
				else
				{
					unset($row['goods'][$key]);
					$delete_increase_goods[] = $value['increase_goods_id'];
				}
			}

			if ($delete_increase_goods)
			{
				$row['goods'] = array_values($row['goods']);
				$this->Increase_GoodsModel->removeIncreaseGoods($delete_increase_goods);
			}

			$row['rule'] = $this->Increase_RuleModel->getIncreaseRuleByWhere($cond_row, array('rule_price' => 'ASC'));

			if ($row['rule'])
			{
				//活动下的换购商品
				$redemption_goods = $this->Increase_RedempGoodsModel->getIncreaseRedempGoodsByWhere($cond_row, array('redemp_price' => 'ASC'));

				if ($redemption_goods)
				{
					$refer_red_goods_id = array();
					foreach ($redemption_goods as $key => $value)
					{
						$refer_red_goods_id[] = $value['goods_id'];
					}
					$refer_red_goods_rows = $this->Goods_BaseModel->get($refer_red_goods_id);//活动下所有换购商品信息

					$redemption_goods_row = array();
					foreach ($redemption_goods as $key => $value)
					{
						if (in_array($value['goods_id'], array_keys($refer_red_goods_rows)))
						{
							$refer_red_goods_base = $this->Goods_BaseModel->getGoodsSpecName($value['goods_id']);

							$redemption_goods_row[$value['rule_id']][$key]                = $value;
							$redemption_goods_row[$value['rule_id']][$key]['goods_name']  = $refer_red_goods_rows[$value['goods_id']]['goods_name'];
							$redemption_goods_row[$value['rule_id']][$key]['goods_price'] = $refer_red_goods_rows[$value['goods_id']]['goods_price'];
							$redemption_goods_row[$value['rule_id']][$key]['goods_image'] = $refer_red_goods_rows[$value['goods_id']]['goods_image'];
							$redemption_goods_row[$value['rule_id']][$key]['goods_id']    = $refer_red_goods_rows[$value['goods_id']]['goods_id'];
							$redemption_goods_row[$value['rule_id']][$key]['goods_spec']    = $refer_red_goods_base;
						}
					}
				}

				foreach ($row['rule'] as $key => $value)
				{
					if (in_array($value['rule_id'], array_keys($redemption_goods_row)))
					{
						foreach ($redemption_goods_row[$value['rule_id']] as $k => $vv)
						{
							$row['rule'][$key]['redemption_goods'][$k]['redemp_goods_id'] = $vv['redemp_goods_id'];
							$row['rule'][$key]['redemption_goods'][$k]['redemp_price']    = $vv['redemp_price'];
							$row['rule'][$key]['redemption_goods'][$k]['goods_name']      = $vv['goods_name'];
							$row['rule'][$key]['redemption_goods'][$k]['goods_price']     = $vv['goods_price'];
							$row['rule'][$key]['redemption_goods'][$k]['goods_image']     = $vv['goods_image'];
							$row['rule'][$key]['redemption_goods'][$k]['goods_id']        = $vv['goods_id'];
							$row['rule'][$key]['redemption_goods'][$k]['goods_spec']        = $vv['goods_spec'];
							$goods_info = $Goods_BaseModel->getOne($vv['goods_id']);
							$row['rule'][$key]['redemption_goods'][$k]['goods_stock']     = $goods_info['goods_stock'];
						}
					}
					else
					{
						unset($row['rule'][$key]);
					}
				}
			}

			$row['rule'] = array_values($row['rule']);
		}

		return $row;
	}

	public function getIncreaseByWhere($cond_row, $order_row = array())
	{
		$rows = $this->getByWhere($cond_row, $order_row);
		return $rows;
	}

	public function getIncreaseActItem($increase_id)
	{
		return $this->getOne($increase_id);
	}

	public function addIncreaseActItem($field_row, $return_flag = true)
	{
		return $this->add($field_row, $return_flag);
	}

	/*删除加价购活动
	*关联删除，活动，活动下的规则，规则下的换购商品
	*/
	public function removeIncreaseActItem($increase_id)
	{
		$rs_row                   = array();
		$Increase_goods_id        = array_keys($this->Increase_GoodsModel->getIncreaseGoodsByWhere(array('increase_id' => $increase_id)));
		$Increase_rules_id        = array_keys($this->Increase_RuleModel->getIncreaseRuleByWhere(array('increase_id' => $increase_id)));
		$Increase_redemp_goods_id = array_keys($this->Increase_RedempGoodsModel->getIncreaseRedempGoodsByWhere(array('increase_id' => $increase_id)));

		//1、删除活动下的商品
		if ($Increase_goods_id)
		{
			$flag1 = $this->Increase_GoodsModel->removeIncreaseGoods($Increase_goods_id);
			check_rs($flag1, $rs_row);
		}

		//2、删除活动下的规则
		if ($Increase_rules_id)
		{
			$flag2 = $this->Increase_RuleModel->removeIncreaseRuleItem($Increase_rules_id);
			check_rs($flag2, $rs_row);
		}

		//3、删除活动下的换购商品
		if ($Increase_redemp_goods_id)
		{
			$flag3 = $this->Increase_RedempGoodsModel->removeIncreaseRedempGoods($Increase_redemp_goods_id);
			check_rs($flag3, $rs_row);
		}

		//4、删除活动
		$del_flag = $this->remove($increase_id);  //删除活动本身
		check_rs($del_flag, $rs_row);

		return is_ok($rs_row);
	}

	/* 编辑活动*/
	public function editIncrease($increase_id, $field_row)
	{
		return $this->edit($increase_id, $field_row);
	}

	//编辑活动和活动下的商品状态，更改为不可用状态，针对活动到期，管理员关闭活动操作
	//需要根据条件对goods_common 表中common_id 是否参加加价购活动字段进行更改
    //其它地方更新加价购状态时请勿调用该方法
	public function editIncreaseUnnormal($increase_id, $field_row)
	{
		$rs_row = array();

		if(is_array($increase_id))
		{
			$cond_row['increase_id:IN'] = $increase_id;
		}
		else
		{
			$cond_row['increase_id'] = $increase_id;
		}

		$increase_goods_id_row = $this->Increase_GoodsModel->getKeyByWhere($cond_row);

		$update_flag1 = $this->Increase_GoodsModel->changeIncreaseGoodsUnnormal($increase_goods_id_row);
		check_rs($update_flag1, $rs_row);

		//更改活动状态
        $update_flag2 =  $this->edit($increase_id, $field_row);
		check_rs($update_flag2, $rs_row);

		return is_ok($rs_row);
	}

}
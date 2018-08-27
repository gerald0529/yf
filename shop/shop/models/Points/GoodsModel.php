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
class Points_GoodsModel extends Points_Goods
{
	const ONSHELVES  = 1;    //上架
	const OFFSHELVES = 0;    //下架(手动下架，库存为0，活动到期导致)
	
	const RECOMMEND   = 1;    //推荐
	const UNRECOMMEND = 0;  //未推荐

	const ISNUMLIMIT = 1; //有兑换数量限制
	const NONUMLIMIT = 0; //没有兑换数量限制

	const ISTLIMIT = 1;        //有兑换时间限制
	const NOTLIMIT = 0;        //没有兑换时间限制

	const WILLSTART   = -1;   //即将开始
	const ONEXCHANGE  = 1;       //兑换中
	const ENDEXCHANGE = 2;      //兑换结束
	
	//积分礼品上架状态，是否上架
	public static $shelves_state_map = array(
		self::ONSHELVES => '是',
		self::OFFSHELVES => '否'
	);
	//积分礼品状态
	public static $points_goods_state_map = array(
		0 => '禁售',
		1 => '可售'
	);
	//积分礼品是否推荐
	public static $points_goods_recommend_map = array(
		self::UNRECOMMEND => '否',
		self::RECOMMEND => '是'
	);


	public $htmlKey = array(
		'points_goods_body'
	);

	//积分礼品列表
	public function getPointsGoodsList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$rows = $this->listByWhere($cond_row, $order_row, $page, $rows);
		foreach ($rows['items'] as $key => $value)
		{
			$rows['items'][$key]['points_goods_shelves_label'] = __(self::$shelves_state_map[$value['points_goods_shelves']]);
			//$rows['items'][$key]['points_goods_state_label']         =  __(self::$points_goods_state_map[$value['points_goods_state']]);
			$rows['items'][$key]['points_goods_recommend_label'] = __(self::$points_goods_recommend_map[$value['points_goods_recommend']]);

			if ($value['points_goods_islimittime'] == self::ISTLIMIT)
			{
				
				if (time() < strtotime($value['points_goods_starttime']))
				{
					$rows['items'][$key]['sell_state'] = self::WILLSTART;
				}
				elseif ((strtotime($value['points_goods_endtime']) > time()) && (time() > strtotime($value['points_goods_starttime'])))
				{
					$rows['items'][$key]['sell_state'] = self::ONEXCHANGE;
				}
				elseif (time() >= strtotime($value['points_goods_endtime']))
				{
					$rows['items'][$key]['sell_state']           = self::ENDEXCHANGE;
					$rows['items'][$key]['points_goods_shelves'] = self::OFFSHELVES;
				}
			}
			elseif ($value['points_goods_islimittime'] == self::NOTLIMIT)
			{
				$rows['items'][$key]['sell_state'] = self::ONEXCHANGE;
			}
		}
		
		return $rows;
	}

	public function getPointsGoods($points_goods_id)
	{
		$rows = $this->get($points_goods_id);
		foreach ($rows as $key => $value)
		{
			$rows[$key]['points_goods_shelves_label'] = __(self::$shelves_state_map[$value['points_goods_shelves']]);
			//$rows['items'][$key]['points_goods_state_label']         =  __(self::$points_goods_state_map[$value['points_goods_state']]);
			$rows[$key]['points_goods_recommend_label'] = __(self::$points_goods_recommend_map[$value['points_goods_recommend']]);


			if ($value['points_goods_islimittime'] == self::ISTLIMIT)
			{

				if (time() < strtotime($value['points_goods_starttime']))
				{
					$rows[$key]['sell_state'] = self::WILLSTART;
				}
				elseif ((strtotime($value['points_goods_endtime']) > time()) && (time() > strtotime($value['points_goods_starttime'])))
				{
					$rows[$key]['sell_state'] = self::ONEXCHANGE;
				}
				elseif (time() >= strtotime($value['points_goods_endtime']))
				{
					$rows[$key]['sell_state']           = self::ENDEXCHANGE;
					$rows[$key]['points_goods_shelves'] = self::OFFSHELVES;
				}
			}
			elseif ($value['points_goods_islimittime'] == self::NOTLIMIT)
			{
				$rows[$key]['sell_state'] = self::ONEXCHANGE;
			}
		}

		return $rows;
	}

	/**
	 * 积分礼品详情
	 * @param $points_goods_id
	 * @return array
	 */
	public function getPointsGoodsByID($points_goods_id)
	{
		$row = $this->getOne($points_goods_id);
		if ($row)
		{
			if ($row['points_goods_islimittime'] == self::ISTLIMIT)
			{
				if (time() < strtotime($row['points_goods_starttime']))
				{
					$row['sell_state'] = self::WILLSTART;
				}
				elseif ((strtotime($row['points_goods_endtime']) > time()) && (time() > strtotime($row['points_goods_starttime'])))
				{
					$row['sell_state'] = self::ONEXCHANGE;
				}
				elseif (time() >= strtotime($row['points_goods_endtime']))
				{
					$row['sell_state']           = self::ENDEXCHANGE;
					$row['points_goods_shelves'] = self::OFFSHELVES;

					$field_row['points_goods_shelves'] = self::OFFSHELVES;
					$this->editPointsGoods($row['points_goods_id'], $field_row, false);
				}
			}
			elseif ($row['points_goods_islimittime'] == self::NOTLIMIT)
			{
				$row['sell_state'] = self::ONEXCHANGE;
			}
		}

		return $row;
	}


	public function getPointsGoodsDetailByWhere($cond_row)
	{
		$row = $this->getOneByWhere($cond_row);
		if ($row)
		{
			if ($row['points_goods_islimittime'] == self::ISTLIMIT)
			{
				if (time() < strtotime($row['points_goods_starttime']))
				{
					$row['sell_state'] = self::WILLSTART;
				}
				elseif ((strtotime($row['points_goods_endtime']) > time()) && (time() > strtotime($row['points_goods_starttime'])))
				{
					$row['sell_state'] = self::ONEXCHANGE;
				}
				elseif (time() >= strtotime($row['points_goods_endtime']))
				{
					$row['sell_state']           = self::ENDEXCHANGE;
					$row['points_goods_shelves'] = self::OFFSHELVES;

					$field_row['points_goods_shelves'] = self::OFFSHELVES;
					$this->editPointsGoods($row['points_goods_id'], $field_row, false);
				}
			}
			elseif ($row['points_goods_islimittime'] == self::NOTLIMIT)
			{
				$row['sell_state'] = self::ONEXCHANGE;
			}
		}

		return $row;
	}

	public function editPointsGoods($points_goods_id, $field_row, $flag = null)
	{
		return $this->edit($points_goods_id, $field_row, $flag);
	}


	public function addPointsGoods($field_row, $flag)
	{
		return $this->add($field_row, $flag);
	}

	/**
	 * 删除积分礼品
	 * @param $points_goods_id
	 * @return bool
	 */
	public function removePointsGoods($points_goods_id)
	{
		$del_flag = $this->remove($points_goods_id);

		return $del_flag;
	}
}
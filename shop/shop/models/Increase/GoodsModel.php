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
class Increase_GoodsModel extends Increase_Goods
{
	public function getIncreaseGoodsList($cond_row, $order_row, $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	public function getOneIncreaseGoodsByWhere($cond_row)
	{
		return $this->getOneByWhere($cond_row);
	}

	public function getIncreaseGoodsByWhere($cond_row, $order_row = array())
	{
		return $this->getByWhere($cond_row, $order_row);
	}

	public function getIncreaseGoodsIdByWhere($cond_row, $order_row = array())
	{
		$rows = array();
		$row  = array();
		$rows = $this->getIncreaseGoodsByWhere($cond_row, $order_row);
		$rows = array_values($rows);

		foreach ($rows as $key => $value)
		{
			$row[$value['increase_goods_id']] = $value['goods_id'];
		}
		return $row;
	}

	public function removeIncreaseGoods($increase_goods_id)
	{
		$rs_row = array();

		//活动商品对应的common_id
		$increase_goods_rows = $this->get($increase_goods_id);
		$common_id_row       = array_column($increase_goods_rows, 'common_id');

		//删除活动商品，先操作
		$del_flag = $this->remove($increase_goods_id);
		check_rs($del_flag, $rs_row);

		//common_id 是否还有其它的商品仍然处在加价购活动的正常状态或即将开始状态？
		//如果没有，更新common表中对应的活动状态字段
		//如果有，则不做更新
		if ($common_id_row)
		{
			$need_edit_row = $this->getCommonNormalPromotion($common_id_row);
			//更改goods_common 活动状态
			if ($need_edit_row)
			{
				$Goods_CommonModel = new Goods_CommonModel();
				$update_flag       = $Goods_CommonModel->editCommon($need_edit_row, array('common_is_jia' => 0));
				check_rs($update_flag, $rs_row);
			}
		}

		return is_ok($rs_row);
	}

	//goods_common 中需要更改common表活动字段的common_id
	public function getCommonNormalPromotion($common_id)
	{
		if (is_array($common_id))
		{
			$common_id                      = array_unique($common_id);
			$cond_row_goods['common_id:IN'] = $common_id;
		}
		else
		{
			$common_id                   = (array)$common_id;
			$cond_row_goods['common_id'] = $common_id;
		}

		//根据 common_id 获取对应的加价购商品
		$increase_goods_rows  = $this->getByWhere($cond_row_goods);
		$no_modify_common_row = array();

		if ($increase_goods_rows)
		{
			$increase_common_id_row = array();
			foreach ($increase_goods_rows as $key => $value)
			{
				$increase_common_id_row[$value['common_id']][] = $value['increase_id'];
			}

			$increase_id_row = array_unique(array_column($increase_goods_rows, 'increase_id')); //活动ID

			if ($increase_id_row)
			{
				$Increase_BaseModel               = new Increase_BaseModel();
				$cond_row['increase_id:IN']       = $increase_id_row;
				$cond_row['increase_state']       = Increase_BaseModel::NORMAL;
				$cond_row['increase_end_time:>='] = get_date_time();
				$increase_keys_row                = $Increase_BaseModel->getKeyByWhere($cond_row);

				foreach ($increase_common_id_row as $key => $value)
				{
					if (array_intersect($value, $increase_keys_row))
					{
						$no_modify_common_row[] = $key;
					}
				}
			}
		}

		return array_diff($common_id, $no_modify_common_row);
	}

	//添加加价购活动商品
	public function addIncreaseGoods($field_row, $return_insert_id)
	{
		return $this->add($field_row, $return_insert_id);
	}

	//更改活动商品common_id 对应的加价购活动状态
	public function changeIncreaseGoodsUnnormal($increase_goods_id)
	{
		$rs_row = array();

		//活动商品对应的common_id
		$increase_goods_rows = $this->get($increase_goods_id);
		$common_id_row       = array_column($increase_goods_rows, 'common_id');

		//common_id 是否还有其它的商品仍然处在加价购活动的正常状态或即将开始状态？
		//如果没有，更新common表中对应的活动状态字段
		//如果有，则不做更新
		if ($common_id_row)
		{
			$need_edit_row = $this->getCommonNormalPromotion($common_id_row);
			//更改goods_common 活动状态
			if ($need_edit_row)
			{
				$Goods_CommonModel = new Goods_CommonModel();
				$update_flag       = $Goods_CommonModel->editCommon($need_edit_row, array('common_is_jia' => 0));
				check_rs($update_flag, $rs_row);
			}
		}

		return is_ok($rs_row);
	}
    
    
    /**
     * 获取店铺正在进行活动或者即将进行活动的商品
     * @param type $common_id
     * @return type
     */
    public function getIncreaseByCommonId($common_id){
        //获取团购
        $cond_row = is_array($common_id) ? array('common_id:IN'=>$common_id) : array('common_id'=>$common_id);
        $cond_row['goods_end_time:>'] = date('Y-m-d H:i:s');
        $list = $this->getByWhere($cond_row);
        return $list;
    }
    
    /**
     * 获取common_id
     * @param type $list
     * @return type
     */
    public function getCommonidByIncreaseList($list){
        if(!$list){
            return array();
        }
        $ids = array();
        foreach ($list as $value){
            $ids[] = $value['common_id'];
        }
        return $ids;
    }
}
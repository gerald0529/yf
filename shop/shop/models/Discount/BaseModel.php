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
class Discount_BaseModel extends Discount_Base
{
	const NORMAL = 1;//正常
	const END    = 2;//结束
	const CANCEL = 3;//管理员关闭
	public static $state_array_map = array(
		self::NORMAL => '正常',
		self::END => '已结束',
		self::CANCEL => '管理员关闭'
	);

	public $Discount_GoodsModel = null;

	public function __construct()
	{
		parent::__construct();

		$this->Discount_GoodsModel = new Discount_GoodsModel();
	}

	/**
	 * @param array $cond_row
	 * @param array $order_row
	 * @param int $page
	 * @param int $rows
	 * @return array
	 */
	public function getDiscountActList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data_rows = $this->listByWhere($cond_row, $order_row, $page, $rows);

		if ($data_rows)
		{
			$expire_discount_id       = array();  //过期的活动id
			$expire_discount_goods_id = array();
			foreach ($data_rows['items'] as $key => $value)
			{
				$data_rows['items'][$key]['discount_state_label'] = __(self::$state_array_map[$value['discount_state']]);
				if (time() > strtotime($value['discount_end_time']))
				{
					$data_rows['items'][$key]['discount_state']       = self::END;
					$data_rows['items'][$key]['discount_state_label'] = __(self::$state_array_map[self::END]);

					$expire_discount_id[] = $value['discount_id'];
				}
			}

			if ($expire_discount_id)
			{
				$field_row['discount_state'] = self::END;

				$this->changeDiscountStateUnnormal($expire_discount_id, $field_row);

				//$this->editDiscountActInfo($expire_discount_id, $field_row); //活动结束

				$cond_row_discount['discount_id:IN'] = $expire_discount_id;
				$expire_discount_goods_id            = array_keys($this->Discount_GoodsModel->getDiscountGoodsByWhere($cond_row_discount));

				if ($expire_discount_goods_id)
				{
					$field_row_discount_goods['discount_goods_state'] = Discount_GoodsModel::END;
					$this->Discount_GoodsModel->editDiscountGoods($expire_discount_goods_id, $field_row_discount_goods);
				}
			}
		}


		return $data_rows;
	}


	public function removeDiscountActItem($discount_id)
	{
		$rs_row = array();

		//删除活动下的商品
		$discount_goods_id_row = $this->Discount_GoodsModel->getKeyByWhere(array('discount_id' => $discount_id));

		if($discount_goods_id_row)
		{
			$flag = $this->Discount_GoodsModel->removeDiscountGoods($discount_goods_id_row);
			check_rs($flag, $rs_row);
		}

		//删除活动
		$del_flag = $this->remove($discount_id);
		check_rs($del_flag, $rs_row);

		return is_ok($rs_row);
	}

	public function getDiscountActItemById($discount_id)
	{
		$row                         = $this->getOne($discount_id);
		$row['discount_state_label'] = __(self::$state_array_map[$row['discount_state']]);
		return $row;
	}

	public function addDiscountActivity($field_row, $return_insert_id)
	{
		return $this->add($field_row, $return_insert_id);
	}

	public function getDiscountActInfo($cond_row)
	{
		$row = $this->getOneByWhere($cond_row);

		if ($row)
		{
			$row['discount_state_label'] = __(self::$state_array_map[$row['discount_state']]);
		}
		return $row;
	}

	public function editDiscountActInfo($discount_id, $field_row)
	{
		$update_flag = $this->edit($discount_id, $field_row);

		return $update_flag;
	}

	//除计划任务和管理员取消活动外，其它地方请勿调用
	//更改活动状态为不可用，针对活动到期或管理员关闭
	public function changeDiscountStateUnnormal($discount_id, $field_row)
	{
		$rs_row = array();

		if(is_array($discount_id))
		{
			$cond_row['discount_id:IN'] = $discount_id;
		}
		else
		{
			$cond_row['discount_id'] = $discount_id;
		}

		//活动下的商品
		$discount_goods_id_row = $this->Discount_GoodsModel->getKeyByWhere($cond_row);

		$flag = $this->Discount_GoodsModel->changeDiscountGoodsUnnormal($discount_goods_id_row);
		check_rs($flag, $rs_row);

		//更改活动状态
		$update_flag = $this->edit($discount_id, $field_row);
		check_rs($update_flag, $rs_row);

		return is_ok($rs_row);

	}
}
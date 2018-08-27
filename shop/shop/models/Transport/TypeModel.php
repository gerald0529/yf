<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Transport_TypeModel extends Transport_Type
{
	/**
	 * 读取店铺列表
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getTransportList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$Transport_ItemModel = new Transport_ItemModel();
		$num                 = $this->getByWhere($cond_row, $order_row);
		//如果该店铺没有物流工具模板则生成一个默认的全国物流模板
		if (!$num)
		{
			$add_row['transport_type_name']           = __('全国');
			$add_row['shop_id']                       = Perm::$shopId;
			$add_row['transport_type_pricing_method'] = 1;
			$add_row['transport_type_time']           = get_date_time();
			$add_row['transport_type_price']          = 10;

			$fl = $this->addType($add_row, true);

			if ($fl)
			{
				$add_item_row['transport_type_id']            = $fl;
				$add_item_row['transport_item_default_num']   = 1;
				$add_item_row['transport_item_default_price'] = 10;
				$add_item_row['transport_item_city']          = 'default';

				$Transport_ItemModel->addItem($add_item_row);
			}
		}

		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);

		$Base_DistrictModel = new Base_DistrictModel();

		if ($data['items'])
		{
			foreach ($data['items'] as $key => $val)
			{
				$transport_item = $Transport_ItemModel->getOneByWhere(array('transport_type_id' => $val['transport_type_id']));
				if ($transport_item)
				{
					if ($transport_item['transport_item_city'] != 'default')
					{
						$district_row  = explode(',', $transport_item['transport_item_city']);
						$district_name = $Base_DistrictModel->getName($district_row);
						if (is_array($district_name))
						{
							$district_name                   = implode(',', $district_name);
							$transport_item['district_name'] = $district_name;
						}

					}
					else
					{
						$transport_item['district_name'] = __('全国');
					}
				}
				else
				{
					unset($data['items'][$key]);
				}

				$data['items'][$key]['transport_item'] = $transport_item;

			}
		}
		fb($data);

		return $data;
	}

	public function getTransportInfo($transport_type_id)
	{
		$data = $this->getOne($transport_type_id);

		$Transport_ItemModel = new Transport_ItemModel();

		if ($data)
		{
			$data['transport_item'] = $Transport_ItemModel->getOneByWhere(array('transport_type_id' => $data['transport_type_id']));
		}
		fb($data);
		return $data;
	}


	//根据收货地址与cart_id获取物流运费
	public function countTransportCost($city = null, $cart_id = array())
	{
		//根据cart_id获取商品的信息
		$cord_row  = array();
		$order_row = array();

		$cond_row = array('cart_id:IN' => $cart_id);
		//购物车中的商品信息
		$CartModel = new CartModel();
		$cart      = $CartModel->getCardList($cond_row, $order_row);

		unset($cart['count']);
		$data           = array();
		$Shop_BaseModel = new Shop_BaseModel();

		if(!$city)
		{
			foreach ($cart as $key => $val)
			{

						$data[$key]['cost'] = 0;
						$data[$key]['con']  = '';
			}

			return $data;
		}



		foreach ($cart as $key => $val)
		{
			//获取店铺的免运费设置
			$shop = $Shop_BaseModel->getOne($key);

			if ($shop['shop_free_shipping'] > 0 && $val['sprice'] >= $shop['shop_free_shipping'])
			{
				$data[$key]['cost'] = number_format(0, 2);
				$data[$key]['con']  = sprintf("满%s免运费", ceil($shop['shop_free_shipping']));
			}
			else
			{
				//获取店铺的物流
				$transport           = $this->getByWhere(array('shop_id' => $key));
				$Transport_ItemModel = new Transport_ItemModel();

				if ($transport)
				{
					$chose_transport   = array();
					$default_transport = array();

					foreach ($transport as $tk => $tv)
					{
						$transport_item = $Transport_ItemModel->getOneByWhere(array('transport_type_id' => $tv['transport_type_id']));

						$city_row = explode(',', $transport_item['transport_item_city']);

						if (in_array($city, $city_row))
						{
							$transport[$tk]['item'] = $transport_item;
							$chose_transport        = $transport[$tk];
						}
						if ($transport_item['transport_item_city'] == 'default')
						{
							$transport[$tk]['item'] = $transport_item;
							$default_transport      = $transport[$tk];
						}
					}

					//如果没有对应区域的物流就选择全国的物流

					if (empty($chose_transport))
					{
						$chose_transport = $default_transport;
					}

					fb($chose_transport);
					fb("物流信息");

					//计算店铺中商品的重量
					$cubage = 0;
					foreach ($val['goods'] as $gk => $gv)
					{
						$cubage += $gv['cubage'] * $gv['goods_num'];
					}

					//计算首重
					$diff_num = $cubage - $chose_transport['item']['transport_item_default_num'];
					$cost     = $chose_transport['item']['transport_item_default_price'];

					if ($diff_num > 0 && $chose_transport['item']['transport_item_add_num'] > 0)
					{
						$cost += ceil(($diff_num / $chose_transport['item']['transport_item_add_num'])) * $chose_transport['item']['transport_item_add_price'];
					}

					$data[$key]['cost'] = $cost;
					$data[$key]['con']  = '';
				}
				else
				{
					$data[$key]['cost'] = 0;
					$data[$key]['con']  = '';
				}

			}

		}
		return $data;
	}

	//删除
	public function delType($transport_type_id = null)
	{
		$Transport_ItemModel = new Transport_ItemModel();

		$flag = $Transport_ItemModel->delItem($transport_type_id);

		if ($flag)
		{
			$data = $this->removeType($transport_type_id);
		}
		else
		{
			$data = false;
		}

		return $data;

	}

	//获取店铺的所有售卖区域
	public function getShopDistrict($shop_id = null)
	{
		$Transport_ItemModel = new Transport_ItemModel();
		$transport_type      = $this->getByWhere(array('shop_id' => $shop_id));

		$city = '';
		foreach ($transport_type as $key => $val)
		{
			$transport_item = $Transport_ItemModel->getOneByWhere(array('transport_type_id' => $val['transport_type_id']));
			if ($transport_item)
			{
				if ($transport_item['transport_item_city'] != 'default')
				{
					$city .= $transport_item['transport_item_city'] . ',';
				}

			}
			else
			{
				unset($transport_type[$key]);
			}
		}

		$city_row = explode(',', $city);

		return $city_row;
	}

}

?>
<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     windfnn 2016-06-10
 */
class Shop_ShippingAddressModel extends Shop_ShippingAddress
{
	/**
	 * 读取分页列表
	 *
	 * @param  int $goods_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}


	/*
	 * 添加发货地址
	 */
	public function addAddress($field_row)
	{
		return $this->add($field_row);
	}

	/*
	 * 设置默认发货地址
	 */
	public function setDefaultAddress($shop_id, $shipping_address_id, $field_row)
	{
		//获取店铺已设置默认地址信息
		$data = $this->getByWhere(array(
									  'shop_id' => $shop_id,
									  'shipping_address_default' => 1
								  ));
		if (!empty($data))
		{
			foreach ($data as $key => $val)
			{
				//已有默认地址置为0
				$this->edit($val['shipping_address_id'], array('shipping_address_default' => 0));
			}
		}

		//设置新的默认地址
		$this->edit($shipping_address_id, $field_row);
	}

	/*
	 * 删除发货地址
	 */
	public function removeAddress($shipping_address_id)
	{
		$del_flag = $this->remove($shipping_address_id);
		return $del_flag;
	}

	/*
	 * 获取地址信息
	 */
	public function getAddress($shipping_address_id)
	{
		return $this->getOne($shipping_address_id);
	}

	/*
	 * 修改地址信息
	 */
	public function updateAddress($shipping_address_id, $field_row)
	{
		$update_flag = $this->edit($shipping_address_id, $field_row);
		return $update_flag;
	}
}

?>
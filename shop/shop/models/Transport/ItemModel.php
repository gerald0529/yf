<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Transport_ItemModel extends Transport_Item
{
	/**
	 * 读取店铺列表
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getItemList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
		fb($data);
		return $data;
	}

	public function delItem($transport_type_id = null)
	{
		$data = $this->getOneByWhere(array('transport_type_id' => $transport_type_id));
		fb($data);
		$falg = $this->removeItem($data['transport_item_id']);

		return $falg;
	}

	public function getItemByTransportId($transport_id = null)
	{
		$data1 = $this->getByWhere(array("transport_item_city:LIKE"=> '%'.$transport_id.'%'));

		foreach($data1 as $key => $val)
		{
			$transport_city_row = explode(',',$val['transport_item_city']);

			if(!in_array($transport_id,$transport_city_row))
			{
				unset($data1[$key]);
			}
		}

		$data2 = $this->getByWhere(array('transport_item_city' => 'default'));

		$data = array_merge($data1,$data2);

		$type_id = array_column($data,'transport_type_id');

		return $type_id;

	}

	//获取店铺的默认模板
	public function getItemByShopId($shop_id = null)
	{
		$Transport_TypeModel = new Transport_TypeModel();
		$transport_type      = $Transport_TypeModel->getByWhere(array('shop_id' => $shop_id));

		$transport_id = array_column($transport_type, 'transport_type_id');

		$data = $this->getByWhere(array('transport_type_id:IN' => $transport_id ,'transport_item_city'=>'default'));

		$data = current($data);

		$data = $Transport_TypeModel->getOne($data['transport_type_id']);

		return $data;

	}

}

?>
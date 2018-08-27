<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Waybill_TplModel extends Waybill_Tpl
{
	public static $waybillTplEnable = array(
		"0" => '否',
		"1" => '是'
	);

	const ENABLE_TRUE = 1;
	public $jsonKey = array('waybill_tpl_item');
	
	/**
	 * 读取分页列表
	 *
	 * @param  array $cond_row 查询条件
	 * @param  array $order_row 排序信息
	 * @param  array $page 当前页码
	 * @param  array $rows 每页记录数
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getTplList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$ExpressModel = new ExpressModel();
		$express_data = $ExpressModel->getByWhere();

		if ( empty($cond_row['shop_id:IN']) )
		{
			$cond_row['shop_id'] = Perm::$shopId;
		}
		$data                = $this->listByWhere($cond_row, $order_row, $page, $rows);
		
		foreach ($data["items"] as $key => $value)
		{

			$data["items"][$key]["waybill_tpl_enable"] = __(Waybill_TplModel::$waybillTplEnable[$value["waybill_tpl_enable"]]);
			$data["items"][$key]["express_name"]       = $express_data[$value['express_id']]['express_name'];
		}
		return $data;
	}

	/**
	 * 读取分页列表
	 *
	 * @param  array $cond_row 主键值
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getTplDetail($cond_row)
	{
		$data = $this->getOneByWhere($cond_row);

		return $data;
	}

	//打印运单
	public function createPrintData($order_id = 0, $waybill_tpl_id = 0)
	{
		$shopExpressModel  = new Shop_ExpressModel();
		$shop_express_base = $shopExpressModel->getDefaultShopExpress();

		//过滤没有绑定模板的数据
		foreach ($shop_express_base as $key => $val)
		{
			if (empty($val['way_bill']))
			{
				unset($shop_express_base[$key]);
			}
		}

		if (empty($waybill_tpl_id))
		{
			//优先取出默认模板
			$default_express_id = array_search(Shop_ExpressModel::DEFAULT_TRUE, array_column($shop_express_base, 'user_is_default', 'user_express_id'));
			if ( !empty($default_express_id) )
			{
				$shop_express_base_f   = $shop_express_base[$default_express_id];
			}
			else
			{
				$shop_express_base   = array_values($shop_express_base);
				$shop_express_base_f = pos($shop_express_base);
			}

			$waybill_data        = $shop_express_base_f['way_bill'];
		}
		else
		{
			foreach ($shop_express_base as $key => $val)
			{
				if ($val['way_bill']['waybill_tpl_id'] == $waybill_tpl_id)
				{
					$waybill_data = $val['way_bill'];
					break;
				}
			}
		}

		$Order_BaseModel = new Order_BaseModel();
		$order_base      = $Order_BaseModel->getBase($order_id);

		if (!empty($order_base))
		{
			$order_base = pos($order_base);

			//读取订单对应数据
			if ( !empty($waybill_data['waybill_tpl_item']) )
			{
				foreach ($waybill_data['waybill_tpl_item'] as $key => $val)
				{
					switch ($key)
					{
						case 'buyer_name':
							$waybill_data['waybill_tpl_item'][$key]['value'] = $order_base['buyer_user_name'];                    //收货人
							break;
						case 'buyer_area':
							$waybill_data['waybill_tpl_item'][$key]['value'] = $order_base['order_receiver_address'];            //收货人地区
							break;
						case 'buyer_address':
							$waybill_data['waybill_tpl_item'][$key]['value'] = $order_base['order_receiver_address'];            //收货人地址
							break;
						case 'buyer_mobile':
							$waybill_data['waybill_tpl_item'][$key]['value'] = $order_base['order_receiver_contact'];            //收货人手机
							break;
						case 'buyer_phone':
							$waybill_data['waybill_tpl_item'][$key]['value'] = $order_base['order_receiver_contact'];            //收货人电话
							break;
						case 'seller_name':
							$waybill_data['waybill_tpl_item'][$key]['value'] = $order_base['order_seller_name'];                //发货人
							break;
						case 'seller_area':
							$waybill_data['waybill_tpl_item'][$key]['value'] = $order_base['order_seller_address'];             //发货人地区
							break;
						case 'seller_address':
							$waybill_data['waybill_tpl_item'][$key]['value'] = $order_base['order_seller_address'];             //发货人地址
							break;
						case 'seller_company':
							$waybill_data['waybill_tpl_item'][$key]['value'] = $order_base['shop_name'];                        //发货人公司
							break;
						case 'seller_phone':
							$waybill_data['waybill_tpl_item'][$key]['value'] = $order_base['order_seller_contact'];            //发货人phone
							break;
					}
				}

				//偏移量
				$waybill_data['waybill_tpl_top']  = $waybill_data['waybill_tpl_top'] * 3.8;
				$waybill_data['waybill_tpl_left'] = $waybill_data['waybill_tpl_left'] * 3.8;
			}
			else
			{
				echo '未设置打印模板,无效打印！';
			}

		}


		$data['data']         = $shop_express_base;
		$data['waybill_data'] = $waybill_data;

		return $data;
	}
}

?>
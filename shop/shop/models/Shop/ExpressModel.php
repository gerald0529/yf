<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     windfnn 2016-06-10
 */
class Shop_ExpressModel extends Shop_Express
{

	const DEFAULT_TRUE  = 1;
	const DEFAULT_FALSE = 0;

	public $jsonKey = array('user_tpl_item');

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

	/**
	 * 获取店铺默认物流公司ID
	 * express_id
	 */
	public function getShopExpressId($cond_row = array())
	{
		//获取店铺默认物流公司
		$shop_express = $this->getByWhere($cond_row);
		$express_rows = array();

		//店铺物流公司ID
		if (!empty($shop_express))
		{
			foreach ($shop_express as $key => $val)
			{
				$express_rows[$key] = $val['express_id'];
			}
		}

		return $express_rows;
	}


	/**
	 * 获取店铺默认物流公司
	 *
	 * @param  int $goods_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getShopExpressList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		//获取平台物流公司
		$ExpressModel = new ExpressModel();
		$data         = $ExpressModel->getExpressList(array('express_status' => 1), array('express_commonorder' => 'desc'));
		
		//获取店铺默认物流公司ID
		$express_rows = $this->getShopExpressId($cond_row);

		foreach ($data['items'] as $key => $val)
		{
			if (in_array($val['express_id'], $express_rows))
			{
				$data['items'][$key]['checked'] = 1;
			}
			else
			{
				$data['items'][$key]['checked'] = 0;
			}
		}
		
		return $data;
	}

	public function getDefaultShopExpress()
	{
		$ExpressModel     = new ExpressModel();
		$Waybill_TplModel = new Waybill_TplModel();

		$default_shop_express = $this->getByWhere(array('shop_id' => Perm::$shopId),array('express_id'=>'asc'));

		if (!empty($default_shop_express))
		{
			$default_express_ids = array_column($default_shop_express, 'express_id');
			$default_waybill_ids = array_column($default_shop_express, 'waybill_tpl_id');

            //店铺支持的快递公司的信息
			$express_data  = $ExpressModel->getExpress($default_express_ids);
            //店铺支持的所有运单的信息
			$way_bill_data = $Waybill_TplModel->getByWhere(array('waybill_tpl_id:IN' => $default_waybill_ids));
            
            
            
            $way_bill_list = array();    
            foreach ($way_bill_data as $value){
                $way_bill_list[$value['waybill_tpl_id']][$value['express_id']] = $value;
            }
            
			foreach ($default_shop_express as $key => $val)
			{
				if ( empty($express_data[$val['express_id']]) )
				{
					unset($default_shop_express[$key]); continue;
				}
                
				$default_shop_express[$key]['express_name'] = $express_data[$val['express_id']]['express_name'];
                $default_shop_express[$key]['way_bill'] = $way_bill_list[$val['waybill_tpl_id']][$val['express_id']] ? $way_bill_list[$val['waybill_tpl_id']][$val['express_id']] : array();
				
			}

			return $default_shop_express;
		}
	}

	/**
	 * 更改店铺默认物流公司
	 *
	 * @param  int $goods_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	function editShopExpress($shop_id)
	{
		//获取店铺默认物流公司ID
		$express_rows = $this->getShopExpressId(array('shop_id' => $shop_id));
		$express_id   = request_row('id');

		foreach ($express_id as $key => $val)
		{
			if (!in_array($val, $express_rows))
			{
				//不在已有默认物流公司ID内，插入
				$field['express_id'] = $val;
				$field['shop_id']    = $shop_id;
				$this->add($field);
			}
		}

		$del_row = array();

		foreach ($express_rows as $k => $v)
		{
			if (!in_array($v, $express_id))
			{
				//如果已有ID，不在提交的数组里，删除
				$cond_row['shop_id']    = $shop_id;
				$cond_row['express_id'] = $v;

				//获取主键值，合并数组
				$key_row = $this->getKeyByWhere($cond_row);
				$del_row = array_merge($del_row, $key_row);

			}
		}
		//删除操作
		$this->remove($del_row);
	}
}

?>
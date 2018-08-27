<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Analysis_AnalysisCtl extends Yf_AppController
{

	public $Analysis_PlatformAreaModel    = null;
	public $Analysis_PlatformClassModel   = null;
	public $Analysis_PlatformGeneralModel = null;
	public $Analysis_PlatformGoodsModel   = null;
	public $Analysis_PlatformReturnModel  = null;
	public $Analysis_PlatformTotalModel   = null;
	public $Analysis_PlatformUserModel    = null;
	public $Analysis_ShopAreaModel        = null;
	public $Analysis_ShopGeneralModel     = null;
	public $Analysis_ShopGoodsModel       = null;
	public $Analysis_ShopUserModel        = null;

	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
		$this->Analysis_PlatformAreaModel    = new Analysis_PlatformAreaModel();
		$this->Analysis_PlatformClassModel   = new Analysis_PlatformClassModel();
		$this->Analysis_PlatformGeneralModel = new Analysis_PlatformGeneralModel();
		$this->Analysis_PlatformGoodsModel   = new Analysis_PlatformGoodsModel();
		$this->Analysis_PlatformReturnModel  = new Analysis_PlatformReturnModel();
		$this->Analysis_PlatformTotalModel   = new Analysis_PlatformTotalModel();
		$this->Analysis_PlatformUserModel    = new Analysis_PlatformUserModel();
		$this->Analysis_ShopAreaModel        = new Analysis_ShopAreaModel();
		$this->Analysis_ShopGeneralModel     = new Analysis_ShopGeneralModel();
		$this->Analysis_ShopGoodsModel       = new Analysis_ShopGoodsModel();
		$this->Analysis_ShopUserModel        = new Analysis_ShopUserModel();
	}

	//修改支付卡信息
	public function payOrder()
	{
		$listInfo['buyer_id']        = request_int("buyer_id");
		$listInfo['shop_id']         = request_int("shop_id");
		$listInfo['shop_name']       = request_string("shop_name");
		$listInfo['order_cash']      = request_float("order_cash");
		$listInfo['province_id']     = request_int("province_id");
		$listInfo['city_id']         = request_int("city_id");
		$listInfo['goods']           = request_row("goods");
		$listInfo['order_goods_num'] = 0;
		foreach ($listInfo['goods'] as $v)
		{
			$listInfo['order_goods_num'] += $v['goods_num'];
		}
		$listInfo['time'] = date("Y-m-d");

		$shop_search_row['user_date'] = $listInfo['time'];
		$shop_search_row['shop_id']   = $listInfo['shop_id'];
		$shop_search_row['user_id']   = $listInfo['buyer_id'];
		$shop_buyer_flag              = $this->Analysis_ShopUserModel->getOneByWhere($shop_search_row);
		$this->generalShop($listInfo, $shop_buyer_flag);
		$this->areaShop($listInfo, $shop_buyer_flag);

		$platform_search_row['user_date'] = $listInfo['time'];
		$platform_search_row['user_id']   = $listInfo['buyer_id'];
		$platform_buyer_flag              = $this->Analysis_PlatformUserModel->getOneByWhere($platform_search_row);
		$this->generalPlatform($listInfo, $shop_buyer_flag);
		$this->areaPlatform($listInfo, $shop_buyer_flag);

		$this->userShop($listInfo);
		$this->userPlatform($listInfo);

		$this->goodsShop($listInfo);
		$this->goodsPlatform($listInfo);

		$this->classPlatform($listInfo);
	}


	public function collectShop()
	{
		$listInfo['shop_id'] = request_int("shop_id");
		$listInfo['time']    = date("Y-m-d");

		$search_row['general_date'] = $listInfo['time'];
		$search_row['shop_id']      = $listInfo['shop_id'];
		$data                       = $this->Analysis_ShopGeneralModel->getOneByWhere($search_row);

		$field_row['shop_favor_num'] = 1;
		if (!empty($data))
		{
			$this->Analysis_ShopGeneralModel->editShopGeneral($data['shop_general_id'], $field_row, true);
		}
		else
		{
			$field_row['general_date'] = $listInfo['time'];
			$field_row['shop_id']      = $listInfo['shop_id'];
			$this->Analysis_ShopGeneralModel->addShopGeneral($field_row);
		}
	}


	public function collectGoods()
	{
		$listInfo['shop_id'] = request_int("shop_id");
		$listInfo['time']    = date("Y-m-d");

		$search_row['general_date'] = $listInfo['time'];
		$search_row['shop_id']      = $listInfo['shop_id'];
		$data                       = $this->Analysis_ShopGeneralModel->getOneByWhere($search_row);

		$field_row['goods_favor_num'] = 1;
		if (!empty($data))
		{
			$this->Analysis_ShopGeneralModel->editShopGeneral($data['shop_general_id'], $field_row, true);
		}
		else
		{
			$field_row['general_date'] = $listInfo['time'];
			$field_row['shop_id']      = $listInfo['shop_id'];
			$this->Analysis_ShopGeneralModel->addShopGeneral($field_row);
		}
	}


	public function addUser()
	{
		$listInfo['time'] = date("Y-m-d");

		$search_row['general_date'] = $listInfo['time'];
		$data                       = $this->Analysis_PlatformGeneralModel->getOneByWhere($search_row);

		$field_row['user_new_num'] = 1;
		if (!empty($data))
		{
			$this->Analysis_PlatformGeneralModel->editPlatformGeneral($data['platform_general_id'], $field_row, true);
		}
		else
		{
			$field_row['general_date'] = $listInfo['time'];
			$this->Analysis_PlatformGeneralModel->addPlatformGeneral($field_row);
		}

		$this->Analysis_PlatformTotalModel->editPlatformTotal(1, array('user_num' => 1), true);
	}


	public function addShop()
	{
		$listInfo['time'] = date("Y-m-d");

		$search_row['general_date'] = $listInfo['time'];
		$data                       = $this->Analysis_PlatformGeneralModel->getOneByWhere($search_row);

		$field_row['shop_new_num'] = 1;
		if (!empty($data))
		{
			$this->Analysis_PlatformGeneralModel->editPlatformGeneral($data['platform_general_id'], $field_row, true);
		}
		else
		{
			$field_row['general_date'] = $listInfo['time'];
			$this->Analysis_PlatformGeneralModel->addPlatformGeneral($field_row);
		}

		$this->Analysis_PlatformTotalModel->editPlatformTotal(1, array('shop_num' => 1), true);
	}


	public function addGoods()
	{
		$listInfo['time'] = date("Y-m-d");
		$shopId           = request_int("shop_id");

		$search_row['general_date'] = $listInfo['time'];
		$data                       = $this->Analysis_PlatformGeneralModel->getOneByWhere($search_row);

		$field_row['goods_new_num'] = 1;
		if (!empty($data))
		{
			$this->Analysis_PlatformGeneralModel->editPlatformGeneral($data['platform_general_id'], $field_row, true);
		}
		else
		{
			$field_row['general_date'] = $listInfo['time'];
			$this->Analysis_PlatformGeneralModel->addPlatformGeneral($field_row);
		}

		$this->Analysis_PlatformTotalModel->editPlatformTotal(0, array('goods_num' => 1), true);
		if ($this->Analysis_PlatformTotalModel->getOne($shopId))
		{
			$this->Analysis_PlatformTotalModel->editPlatformTotal($shopId, array('goods_num' => 1), true);
		}
		else
		{
			$this->Analysis_PlatformTotalModel->addPlatformTotal(array(
																	 'platform_total_id' => $shopId,
																	 'goods_num' => 1
																 ));
		}
	}

	public function delGoods()
	{
		$listInfo['time']           = date("Y-m-d");
		$shopId                     = request_int("shop_id");
		$search_row['general_date'] = $listInfo['time'];
		$data                       = $this->Analysis_PlatformGeneralModel->getOneByWhere($search_row);

		$field_row['goods_new_num'] = -1;
		if (!empty($data))
		{
			$this->Analysis_PlatformGeneralModel->editPlatformGeneral($data['platform_general_id'], $field_row, true);
		}
		else
		{
			$field_row['general_date'] = $listInfo['time'];
			$this->Analysis_PlatformGeneralModel->addPlatformGeneral($field_row);
		}

		$this->Analysis_PlatformTotalModel->editPlatformTotal(1, array('goods_num' => -1), true);
		$this->Analysis_PlatformTotalModel->editPlatformTotal($shopId, array('goods_num' => -1), true);

	}

	public function addReturn()
	{
		$listInfo['time'] = date("Y-m-d");
		$return_cash      = request_float("return_cash");

		$search_row['return_date'] = $listInfo['time'];
		$data                      = $this->Analysis_PlatformReturnModel->getOneByWhere($search_row);

		$field_row['return_cash'] = $return_cash;
		if (!empty($data))
		{
			$this->Analysis_PlatformReturnModel->editPlatformReturn($data['platform_return_id'], $field_row, true);
		}
		else
		{
			$field_row['return_date'] = $listInfo['time'];
			$this->Analysis_PlatformReturnModel->addPlatformReturn($field_row);
		}
	}


	public function generalShop($listInfo, $shop_buyer_flag)
	{
		$search_row['general_date'] = $listInfo['time'];
		$search_row['shop_id']      = $listInfo['shop_id'];
		$data                       = $this->Analysis_ShopGeneralModel->getOneByWhere($search_row);

		$field_row['order_cash']      = $listInfo['order_cash'];
		$field_row['shop_name']       = $listInfo['shop_name'];
		$field_row['order_goods_num'] = $listInfo['order_goods_num'];
		$field_row['order_num']       = 1;
		if (empty($shop_buyer_flag))
		{
			$field_row['order_user_num'] = 1;
		}
		if (!empty($data))
		{
			$this->Analysis_ShopGeneralModel->editShopGeneral($data['shop_general_id'], $field_row, true);
		}
		else
		{
			$field_row['general_date'] = $listInfo['time'];
			$field_row['shop_id']      = $listInfo['shop_id'];
			$this->Analysis_ShopGeneralModel->addShopGeneral($field_row);
		}
	}

	public function generalPlatform($listInfo, $platform_buyer_flag)
	{
		$search_row['general_date'] = $listInfo['time'];
		$data                       = $this->Analysis_PlatformGeneralModel->getOneByWhere($search_row);

		$field_row['order_cash']      = $listInfo['order_cash'];
		$field_row['order_goods_num'] = $listInfo['order_goods_num'];
		$field_row['order_num']       = 1;
		if (empty($platform_buyer_flag))
		{
			$field_row['order_user_num'] = 1;
		}
		if (!empty($data))
		{
			$this->Analysis_PlatformGeneralModel->editPlatformGeneral($data['platform_general_id'], $field_row, true);
		}
		else
		{
			$field_row['general_date'] = $listInfo['time'];
			$this->Analysis_PlatformGeneralModel->addPlatformGeneral($field_row);
		}
	}

	public function areaShop($listInfo, $shop_buyer_flag)
	{
		$search_row['area_date']   = $listInfo['time'];
		$search_row['province_id'] = $listInfo['province_id'];
		$search_row['city_id']     = $listInfo['city_id'];
		$search_row['shop_id']     = $listInfo['shop_id'];
		$data                      = $this->Analysis_ShopAreaModel->getOneByWhere($search_row);

		$field_row['order_cash'] = $listInfo['order_cash'];
		$field_row['order_num']  = 1;
		if (empty($shop_buyer_flag))
		{
			$field_row['order_user_num'] = 1;
		}
		if (!empty($data))
		{
			$this->Analysis_ShopAreaModel->editShopArea($data['shop_area_id'], $field_row, true);
		}
		else
		{
			$field_row['area_date']   = $listInfo['time'];
			$field_row['province_id'] = $listInfo['province_id'];
			$field_row['city_id']     = $listInfo['city_id'];
			$field_row['shop_id']     = $listInfo['shop_id'];
			$this->Analysis_ShopAreaModel->addShopArea($field_row);
		}
	}

	public function areaPlatform($listInfo, $platform_buyer_flag)
	{
		$search_row['area_date']   = $listInfo['time'];
		$search_row['province_id'] = $listInfo['province_id'];
		$search_row['city_id']     = $listInfo['city_id'];
		$data                      = $this->Analysis_PlatformAreaModel->getOneByWhere($search_row);

		$field_row['order_cash'] = $listInfo['order_cash'];
		$field_row['order_num']  = 1;
		if (empty($platform_buyer_flag))
		{
			$field_row['order_user_num'] = 1;
		}
		if (!empty($data))
		{
			$this->Analysis_PlatformAreaModel->editPlatformArea($data['platform_area_id'], $field_row, true);
		}
		else
		{
			$field_row['area_date']   = $listInfo['time'];
			$field_row['province_id'] = $listInfo['province_id'];
			$field_row['city_id']     = $listInfo['city_id'];
			$this->Analysis_PlatformAreaModel->addPlatformArea($field_row);
		}
	}

	public function userPlatform($listInfo)
	{
		$search_row['user_date'] = $listInfo['time'];
		$search_row['user_id']   = $listInfo['buyer_id'];
		$data                    = $this->Analysis_PlatformUserModel->getOneByWhere($search_row);

		$field_row['order_cash'] = $listInfo['order_cash'];
		$field_row['order_num']  = 1;
		if (!empty($data))
		{
			$this->Analysis_PlatformUserModel->editPlatformUser($data['platform_user_id'], $field_row, true);
		}
		else
		{
			$field_row['user_date'] = $listInfo['time'];
			$field_row['user_id']   = $listInfo['buyer_id'];
			$this->Analysis_PlatformUserModel->addPlatformUser($field_row);
		}
	}

	public function userShop($listInfo)
	{
		$search_row['user_date'] = $listInfo['time'];
		$search_row['user_id']   = $listInfo['buyer_id'];
		$search_row['shop_id']   = $listInfo['shop_id'];
		$data                    = $this->Analysis_ShopUserModel->getOneByWhere($search_row);

		$field_row['order_cash'] = $listInfo['order_cash'];
		$field_row['order_num']  = 1;
		if (!empty($data))
		{
			$this->Analysis_ShopUserModel->editShopUser($data['shop_user_id'], $field_row, true);
		}
		else
		{
			$field_row['user_date'] = $listInfo['time'];
			$field_row['user_id']   = $listInfo['buyer_id'];
			$field_row['shop_id']   = $listInfo['shop_id'];
			$this->Analysis_ShopUserModel->addShopUser($field_row);
		}
	}

	public function goodsShop($listInfo)
	{
		$search_row['goods_date'] = $listInfo['time'];
		$search_row['shop_id']    = $listInfo['shop_id'];
		foreach ($listInfo['goods'] as $v)
		{
			$search_row['goods_id']  = $v['goods_id'];
			$data                    = $this->Analysis_ShopGoodsModel->getOneByWhere($search_row);
			$field_row['order_cash'] = $v['goods_cash'];
			$field_row['order_num']  = 1;
			if (!empty($data))
			{
				$this->Analysis_ShopGoodsModel->editShopGoods($data['shop_goods_id'], $field_row, true);
			}
			else
			{
				$field_row['goods_date']  = $listInfo['time'];
				$field_row['shop_id']     = $listInfo['shop_id'];
				$field_row['goods_price'] = $v['goods_price'];
				$field_row['goods_name']  = $v['goods_name'];
				$field_row['goods_id']    = $v['goods_id'];
				$this->Analysis_ShopGoodsModel->addShopGoods($field_row);
			}
		}
	}

	public function goodsPlatform($listInfo)
	{
		$search_row['goods_date'] = $listInfo['time'];
		foreach ($listInfo['goods'] as $v)
		{
			$search_row['goods_id']  = $v['goods_id'];
			$data                    = $this->Analysis_PlatformGoodsModel->getOneByWhere($search_row);
			$field_row['order_cash'] = $v['goods_cash'];
			$field_row['order_num']  = 1;
			if (!empty($data))
			{
				$this->Analysis_PlatformGoodsModel->editPlatformGoods($data['platform_goods_id'], $field_row, true);
			}
			else
			{
				$field_row['goods_date']  = $listInfo['time'];
				$field_row['goods_price'] = $v['goods_price'];
				$field_row['goods_name']  = $v['goods_name'];
				$field_row['goods_id']    = $v['goods_id'];
				$this->Analysis_PlatformGoodsModel->addPlatformGoods($field_row);
			}
		}
	}

	public function classPlatform($listInfo)
	{
		$search_row['class_date'] = $listInfo['time'];
		foreach ($listInfo['goods'] as $v)
		{
			$search_row['class_id']  = $v['goods_class_id'];
			$data                    = $this->Analysis_PlatformClassModel->getOneByWhere($search_row);
			$field_row['order_cash'] = $v['goods_cash'];
			$field_row['order_num']  = 1;
			if (!empty($data))
			{
				$this->Analysis_PlatformClassModel->editPlatformClass($data['platform_class_id'], $field_row, true);
			}
			else
			{
				$field_row['class_date'] = $listInfo['time'];
				$field_row['class_id']   = $v['goods_class_id'];
				$field_row['class_name'] = $v['goods_class_name'];
				$this->Analysis_PlatformClassModel->addPlatformClass($field_row);
			}
		}
	}

	public function showindex()
	{
		$start_date = date("Y-m") . "-1";
		$yes_date   = date("Y-m-d", strtotime("-1 days"));
		$end_date   = date("Y-m-d");
		$field      = array(
			"SUM(order_num) as nums",
			"SUM(order_cash) as cashes"
		);

		$cond_row['general_date'] = $yes_date;
		$cond_row['shop_id']      = request_int("shop_id");
		$data['yes']              = $this->Analysis_ShopGeneralModel->getBySql($field, $cond_row);

		$cond_row['general_date'] = $end_date;
		$data['today']            = $this->Analysis_ShopGeneralModel->getBySql($field, $cond_row);

		$cond_row['general_date:>='] = $start_date;
		$cond_row['general_date:<='] = $end_date;
		$cond_row['shop_id']         = Perm::$shopId;
		$data['month']               = $this->Analysis_ShopGeneralModel->getBySql($field, $cond_row);

		$this->data->addBody(-140, $data);
	}

}

?>
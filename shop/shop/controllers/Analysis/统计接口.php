<?php

/*
新商城统计接口
具体可查看Analysis/TestCtl
*/


/*
NO1.订单付款时(ctl=Analysis_Analysis&met=payOrder)

需要传入的参数
1、买家id(buyer_id)
2、店铺id(shop_id)
3、下单金额(order_cash)
4、收货省id(province_id)
5、收货市id(city_id)
6、商品数组（id，名称，数量，类目id，类目名称，价格，订单金额）
goods(goods_id,goods_name,goods_num,goods_class_id,goods_class_name,goods_price,goods_cash)

返回：空
*/

public
function testOrder()
{
	$key = Yf_Registry::get('shop_api_key');

	$formvars = array();

	$formvars['buyer_id']      = 1;
	$formvars['shop_id']       = 1;
	$formvars['order_cash']    = 63.45;
	$formvars['province_id']   = 1;
	$formvars['city_id']       = 4;
	$formvars['city_id']       = 4;
	$formvars['goods']         = array();
	$goods['goods_id']         = 6;
	$goods['goods_name']       = '统计测试商品';
	$goods['goods_price']      = 26.00;
	$goods['goods_cash']       = 30.00;
	$goods['goods_class_id']   = 9;
	$goods['goods_class_name'] = '测试分类';
	$formvars['goods'][]       = $goods;

	$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Analysis_Analysis&met=payOrder&typ=json', self::$SITE_URL), $formvars);
}

/*
NO2.收藏店铺时(ctl=Analysis_Analysis&met=collectShop)

需要传入的参数：店铺id(shop_id)

返回：空
*/

public
function testCshop()
{
	$key = Yf_Registry::get('shop_api_key');

	$formvars = array();

	$formvars['shop_id'] = 1;

	$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Analysis_Analysis&met=collectShop&typ=json', self::$SITE_URL), $formvars);
}

/*
NO3.收藏店铺商品时(ctl=Analysis_Analysis&met=collectGoods)

需要传入的参数：店铺id(shop_id)

返回：空
*/

public
function testCgoods()
{
	$key = Yf_Registry::get('shop_api_key');

	$formvars = array();

	$formvars['shop_id'] = 1;

	$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Analysis_Analysis&met=collectGoods&typ=json', self::$SITE_URL), $formvars);
}

/*
NO4.买家注册时(ctl=Analysis_Analysis&met=addUser)

需要传入的参数：无

返回：空
*/

public
function testUser()
{
	$key = Yf_Registry::get('shop_api_key');

	$formvars = array();
	$rs       = get_url_with_encrypt($key, sprintf('%s?ctl=Analysis_Analysis&met=addUser&typ=json', self::$SITE_URL), $formvars);
}


/*
NO5.商户入驻时(ctl=Analysis_Analysis&met=addShop)

需要传入的参数：无

返回：空
*/

public
function testShop()
{
	$key = Yf_Registry::get('shop_api_key');

	$formvars = array();
	$rs       = get_url_with_encrypt($key, sprintf('%s?ctl=Analysis_Analysis&met=addShop&typ=json', self::$SITE_URL), $formvars);
}

/*
NO6.增加商品时(ctl=Analysis_Analysis&met=addGoods)

需要传入的参数：无

返回：空
*/

public
function testGoods()
{
	$key = Yf_Registry::get('shop_api_key');

	$formvars = array();
	$rs       = get_url_with_encrypt($key, sprintf('%s?ctl=Analysis_Analysis&met=addGoods&typ=json', self::$SITE_URL), $formvars);
}

/*
NO7.删除商品时(ctl=Analysis_Analysis&met=delGoods)

需要传入的参数：无

返回：空
*/

public
function testFgoods()
{
	$key = Yf_Registry::get('shop_api_key');

	$formvars = array();
	$rs       = get_url_with_encrypt($key, sprintf('%s?ctl=Analysis_Analysis&met=delGoods&typ=json', self::$SITE_URL), $formvars);
}

/*
NO8.平台审核退款通过时(ctl=Analysis_Analysis&met=addReturn)

需要传入的参数：退款金额(return_cash)

返回：空
*/

public
function testReturn()
{
	$key = Yf_Registry::get('shop_api_key');

	$formvars                = array();
	$formvars['return_cash'] = 23;
	$rs                      = get_url_with_encrypt($key, sprintf('%s?ctl=Analysis_Analysis&met=addReturn&typ=json', self::$SITE_URL), $formvars);
}


?>
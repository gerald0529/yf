<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Analysis_TestCtl extends Yf_AppController
{
	public $Discount_BaseModel = null;

	/**
	 * Constructor
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */

	public static $SITE_URL;

	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
		self::$SITE_URL = Yf_Registry::get('url');
	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function testOrder()
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

	public function testCshop()
	{
		$key = Yf_Registry::get('shop_api_key');

		$formvars = array();

		$formvars['shop_id'] = 1;

		$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Analysis_Analysis&met=collectShop&typ=json', self::$SITE_URL), $formvars);
	}

	public function testCgoods()
	{
		$key = Yf_Registry::get('shop_api_key');

		$formvars = array();

		$formvars['shop_id'] = 1;

		$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Analysis_Analysis&met=collectGoods&typ=json', self::$SITE_URL), $formvars);
	}

	public function testUser()
	{
		$key = Yf_Registry::get('shop_api_key');

		$formvars = array();
		$rs       = get_url_with_encrypt($key, sprintf('%s?ctl=Analysis_Analysis&met=addUser&typ=json', self::$SITE_URL), $formvars);
	}

	public function testShop()
	{
		$key = Yf_Registry::get('shop_api_key');

		$formvars = array();
		$rs       = get_url_with_encrypt($key, sprintf('%s?ctl=Analysis_Analysis&met=addShop&typ=json', self::$SITE_URL), $formvars);
	}

	public function testGoods()
	{
		$key = Yf_Registry::get('shop_api_key');

		$formvars = array();
		$rs       = get_url_with_encrypt($key, sprintf('%s?ctl=Analysis_Analysis&met=addGoods&typ=json', self::$SITE_URL), $formvars);
	}

	public function testReturn()
	{
		$key = Yf_Registry::get('shop_api_key');

		$formvars                = array();
		$formvars['return_cash'] = 23;
		$rs                      = get_url_with_encrypt($key, sprintf('%s?ctl=Analysis_Analysis&met=addReturn&typ=json', self::$SITE_URL), $formvars);
	}

	public function testFgoods()
	{
		$key = Yf_Registry::get('shop_api_key');

		$formvars = array();
		$rs       = get_url_with_encrypt($key, sprintf('%s?ctl=Analysis_Analysis&met=delGoods&typ=json', self::$SITE_URL), $formvars);
	}

}

?>
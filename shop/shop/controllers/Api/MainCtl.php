<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * Api接口  管理用户开通新    shop设置新开通   用户运行环境:db....
 *
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class Api_MainCtl extends Api_Controller
{
	/**
	 * Constructor
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */
	private $fp=null;

	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}

	public function getMainInfo()
	{
		$msg    = __('success');
		$status = 200;
		$data = array();
		$start_date   = date("Y-m-d", strtotime("this week"));
		$date = get_date_time();
		$end_date = date("Y-m-d", strtotime("this week +6 day"));
		$end7_date =date("Y-m-d", strtotime("+7 days"));

		$start_month = date('Y-m-01', strtotime(date("Y-m-d")));
		$end_month = date('Y-m-d', strtotime("$start_month +1 month -1 day"));

		//会员总数量
		$userModel = new User_InfoModel();
		$user = $userModel->getSubQuantity(array());
		$data['member_count'] = $user;

		//获取本周新增会员数量
		$cond_row['user_regtime:>='] = $start_date;
		$cond_row['user_regtime:<='] = $end_date;
		$week_member = $userModel->getSubQuantity($cond_row);
		$data['week_member'] = $week_member;

		//获取本月新增会员数量
		$condm_row['user_regtime:>='] = $start_month;
		$condm_row['user_regtime:<='] = $end_month;
		$month_member = $userModel->getSubQuantity($condm_row);
		$data['month_member'] = $month_member;

		//商品总数量
		$goodsModel = new Goods_CommonModel();
		$goods = $goodsModel->getSubQuantity(array());
		$data['goods_num'] = $goods;

		//本周新增商品数量
		$cond_goods_row['common_add_time:>='] = $start_date;
		$cond_goods_row['common_add_time:<='] = $end_date;
		$week_goods = $goodsModel->getSubQuantity($cond_goods_row);
		$data['week_goods_num'] = $week_goods;

		//待审核商品数量
		$cond_goods['common_verify'] = Goods_CommonModel::GOODS_VERIFY_WAITING;
		$verify = $goodsModel->getSubQuantity($cond_goods);
		$data['verify_goods_num'] = $verify;

		//举报数目
		$reportModel = new Report_BaseModel();
		$report_cond_row['report_state'] = Report_BaseModel::REPORT_DO;
		$report = $reportModel->getSubQuantity($report_cond_row);
		$data['report_num'] = $report;

		//品牌数目
		$goodsBrand = new Goods_BrandModel();
		$brands = $goodsBrand->getSubQuantity(array());
		$data['goods_brands_num'] = $brands;

		//店铺总数量
		$shopModel = new Shop_BaseModel();
		$shop_cond_rows = array();
		$shop_cond_rows["shop_self_support"]=  "false";
		$shop_cond_rows["shop_status:in"]=  array("0","3");
		$shops = $shopModel->getSubQuantity($shop_cond_rows);
		$data['shop_nums'] = $shops;

		//待审核店铺数量
		$shop_cond_row['shop_status'] = 1;
		$verify_shops = $shopModel->getSubQuantity($shop_cond_row);
		$data['verify_shop_nums'] = $verify_shops;

		//经营类目申请数量
		$shopClassModel = new Shop_ClassBindModel();
		$shopClass = $shopClassModel->getSubQuantity(array('shop_class_bind_enable'=>1));
		$data['shop_class_nums'] = $shopClass;

		//店铺续签申请数量
		$renewalModel = new Shop_RenewalModel();
		$renewal = $renewalModel->getSubQuantity(array());
		$data['renewal_nums'] = $renewal;

		//店铺到期的数量
		$shop_cond_rows['shop_end_time:<='] = $date;
		$expired = $shopModel->getSubQuantity($shop_cond_rows);
		$data['shop_expired_nums'] = $expired;


		//即将到期店铺数量
		$shop_cond_rows['shop_end_time:<='] = $end7_date;
		$shop_cond_rows['shop_end_time:>='] = $date;
		$expire = $shopModel->getSubQuantity($shop_cond_rows);
		$data['shop_expire_nums'] = $expire;

		//交易订单总数
		$orderModel = new Order_BaseModel();
		$orders = $orderModel->getSubQuantity(array());
		$data['order_nums'] = $orders;

		//退款
		$order_cond_row['return_state'] = Order_ReturnModel::RETURN_SELLER_GOODS;
		$order_cond_row['order_is_virtual'] = 0;
		$order_cond_row['return_goods_return'] = 0;
		$order_cond_row['return_type'] = 1;
		$returnModel = new Order_ReturnModel();
		$returns = $returnModel->getSubQuantity($order_cond_row);
		$data['physical_return_nums'] = $returns;

		//退货
		/* $order_cond_row['return_goods_return'] = 1;
		$order_cond_row['return_type'] = 2; */
		$order_cond_row['return_state'] = Order_ReturnModel::RETURN_SELLER_GOODS;
		$order_cond_row['return_type']  = 2;
		$return_goods = $returnModel->getSubQuantity($order_cond_row);
		$data['physical_return_goods_nums'] = $return_goods;

		//虚拟订单退款
		$order_cond_row['return_goods_return'] = 0;
		/*$order_cond_row['order_is_virtual'] = 1;*/
		$order_cond_row['return_type'] = 3;
		$virtual_returns = $returnModel->getSubQuantity($order_cond_row);
		$data['virtual_return_goods_nums'] = $virtual_returns;


		//投诉
		$complainModel = new Complain_BaseModel();
		$complains = $complainModel->getSubQuantity(array('complain_state'=>1));
		$data['complain_nums'] = $complains;

		//待仲裁
		$handle = $complainModel->getSubQuantity(array('complain_state'=>4));
		$data['handle_nums'] = $handle;

		$data['week_time'] = $start_date;
		$data['date'] = $date;

		$this->data->addBody(-140, $data, $msg, $status);
	}
}
?>

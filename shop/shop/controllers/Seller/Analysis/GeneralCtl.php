<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Yf <service@yuanfeng.cn>
 */
class Seller_Analysis_GeneralCtl extends Seller_Controller
{
	public $Analysis_ShopGeneralModel   = null;
	public $Analysis_ShopGoodsModel     = null;
	public $Analysis_PlatformTotalModel = null;

	/**
	 * Constructor
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
		$this->Analysis_ShopGeneralModel   = new Analysis_ShopGeneralModel();
		$this->Analysis_ShopGoodsModel     = new Analysis_ShopGoodsModel();
		$this->Analysis_PlatformTotalModel = new Analysis_PlatformTotalModel();
	}

    /**
     *  店铺概况
     */
	public function index(){
        $start_date = !request_string('sdate') ? date("Y-m-d", strtotime("-30 days")) : request_string('sdate');
        $end_date = !request_string('edate') ?  date('Y-m-d') : request_string('edate');
        $time_diff = ceil((strtotime($end_date)-strtotime($start_date))/86400);
        if($time_diff > 31){
            //最多查询31天数据
            $end_date = date("Y-m-d", strtotime($start_date)+86400*30);
        }
		$cond_row['start_time'] = $start_date;
		$cond_row['end_time'] = $end_date;
		$cond_row['shop_id']         = Perm::$shopId;
//        $analytics = new Analytics();
//        $result = $analytics->getGeneralInfo($cond_row);
        $result = $this->getCountInfo($cond_row);
        if($result){
            $total['goods_num'] = $result['data']['goods_num'];
            $total['order_cash'] = $result['data']['order_cash'];
			$total['order_goods_num'] = $result['data']['order_goods_num'];
			$total['order_num'] = $result['data']['order_num'];
			$total['order_user_num'] = $result['data']['order_user_num'];
			$total['goods_favor_num'] = $result['data']['goods_favor_num'];
			$total['shop_favor_num'] = $result['data']['shop_favor_num'];
            $total['order_num'] ? $total['general_cash']      = round($total['order_cash'] / $total['order_num'], 2) : "";
            $total['order_user_num'] ? $total['general_user_cash'] = round($total['order_cash'] / $total['order_user_num'], 2) : '';
            if(isset($result['data']['chart_num'])){
                $data['x_data'] = json_encode(array_keys($result['data']['chart_num']));
                $data['y_data_num'] = json_encode(array_values($result['data']['chart_num']));
            }else{
                $data['x_data'] = json_encode(array());
                $data['y_data_num'] = json_encode(array());
            }
            if(isset($result['data']['chart_cost'])){
                $data['x_data'] = json_encode(array_keys($result['data']['chart_cost']));
                $data['y_data_cost'] = json_encode(array_values($result['data']['chart_cost']));
            }else{
                $data['y_data'] = json_encode(array());
                $data['y_data_cost'] = json_encode(array());
            }
            $goods_list = $result['data']['recommend'];
        }else{
            $total['goods_num'] = '';
            $total['order_cash'] = '';
			$total['order_goods_num'] = '';
			$total['order_num'] = '';
			$total['order_user_num'] = '';
			$total['goods_favor_num'] = '';
			$total['shop_favor_num'] = '';
            $total['general_cash']      = '';
            $total['general_user_cash'] ='';
            $data['x_data'] = json_encode(array());
            $data['y_data_cost'] = json_encode(array());
            $data['y_data_num'] = json_encode(array());
            $goods_list = array();
        }
        if('json' == request_string('typ'))
        {
            return $this->data->addBody(-140, $data, 'success', 200);
        }
		include $this->view->getView();
	}

    //获取订单统计
    public function getOrderStatistics ()
    {
        $start_date = !request_string('sdate') ? date("Y-m-d", strtotime("-30 days")) : request_string('sdate');
        $end_date = !request_string('edate') ? date('Y-m-d') : request_string('edate');
        $time_diff = ceil((strtotime($end_date) - strtotime($start_date)) / 86400);
        if ($time_diff > 31) {
            //最多查询31天数据
            $end_date = date("Y-m-d", strtotime($start_date) + 86400 * 30);
        }
        $cond_row['start_time'] = $start_date;
        $cond_row['end_time'] = $end_date;
        $cond_row['shop_id'] = Perm::$shopId;
        $analytics = new Analytics();
        $result = $analytics->getGeneralInfo($cond_row);

        if ($result['status'] === 200) {
            $data = $result['data'];
            $data['date_list'] = array_keys($data['chart_cost']);
            $data['cost_list'] = array_values($data['chart_cost']);
            $data['num_list'] = array_values($data['chart_num']);
        } else {
            $data = [];
        }

        $this->data->addBody(-140, $data, $result['msg'], $result['status']);
    }
    /**
     * 店铺相关数据统计（代替原数据罗盘）
     *
     * @access public
     * @param $cond_row
     * @return array
     */
    public function getCountInfo($cond_row)
    {
        $data = array();

        $goods_common_model = new Goods_CommonModel();
        $orderBase = new Order_BaseModel();

        //店铺商品数量
        $goods_commons = count($goods_common_model->getCommonIdByShopId($cond_row['shop_id']));
        //商品收藏量
        $collect = $goods_common_model->getCommonCollect($cond_row);
        $where_row['shop_id'] = $cond_row['shop_id'];
        $where_row['start_time'] = $cond_row['start_time'];
        $where_row['end_time'] = $cond_row['end_time'];
        //下单会员数
        $order_user_num = count($orderBase->getOrderUsers($where_row));
        //下单金额
        $order_cash = $orderBase->getOrderPrices($where_row);
        //下单量
        $order_num = $orderBase->getOrderNums($where_row);
        //推荐商品
        $order_goods = $orderBase->getOrderGoodsByDate($where_row);
        //下单商品数
        $order_goods_num = 0;
        foreach ($order_goods as $key => $val) {
            $order_goods_num += $val['order_num'];
        }
        //平均客单价
        if ($order_cash && $order_user_num) {
            $general_user_cash = $order_cash % $order_user_num;
            $data['data']['general_user_cash'] = $general_user_cash;
        }
        //平均价格
        if ($order_cash && $order_num) {
            $general_cash = $order_cash % $order_num;
            $data['data']['general_cash'] = $general_cash;
        }
        //日期区间
        $dates = $this->getDateFromRange($cond_row);
        //图表数据
        foreach ($dates as $key => $val) {
            $where_row['order_date'] = $val;
            $data['data']['chart_num'][$val] = intval($orderBase->getOrderNums($where_row));
            $data['data']['chart_cost'][$val] = (float)$orderBase->getOrderPrices($where_row);
        }

        $data['data']['goods_num'] = $goods_commons;
        $data['data']['goods_favor_num'] = $collect;
        $data['data']['order_user_num'] = $order_user_num;
        $data['data']['order_cash'] = $order_cash;
        $data['data']['order_num'] = $order_num;
        $data['data']['order_goods_num'] = $order_goods_num;
        $data['data']['recommend'] = array_slice($order_goods, 0, 5);
        $data['data']['order_goods_num'] = $order_goods_num;

        return $data;
    }
    /**
     * 获取指定日期段内每一天的日期
     *
     * @param Date $startdate 开始日期
     * @param Date $enddate  结束日期
     * @return Array
     */
    public function getDateFromRange($cond_row)
    {
        $stimestamp = strtotime($cond_row['start_time']);
        $etimestamp = strtotime($cond_row['end_time']);
        // 计算日期段内有多少天
        $days = ($etimestamp - $stimestamp) / 86400 + 1;
        //保存每天日期
        $date = array();
        for ($i = 0; $i < $days; $i++) {
            $date[] = date('Y-m-d', $stimestamp + (86400 * $i));
        }
        return $date;
    }
}

?>
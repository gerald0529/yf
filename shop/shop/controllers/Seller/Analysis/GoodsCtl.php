<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Yf <service@yuanfeng.cn>
 */
class Seller_Analysis_GoodsCtl extends Seller_Controller
{
	public $Analysis_ShopGoodsModel = null;

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
		$this->Analysis_ShopGoodsModel = new Analysis_ShopGoodsModel();
	}


	public function get_weekinfo($month, $k = NULL)
	{
		$weekinfo = array();
		$end_date = date('d', strtotime($month . ' +1 month -1 day'));
		for ($i = 1; $i < $end_date; $i = $i + 7)
		{
			$w = date('N', strtotime($month . '-' . $i));

			$weekinfo[] = array(
				date('Y-m-d', strtotime($month . '-' . $i . ' -' . ($w - 1) . ' days')),
				date('Y-m-d', strtotime($month . '-' . $i . ' +' . (7 - $w) . ' days'))
			);
		}
		if ($k)
		{
			return $weekinfo[$k];
		}
		else
		{
			return $weekinfo;
		}

	}

	public function getYear()
	{
		$start_year = date("Y", strtotime("-5 years"));
		$end_year   = date("Y", strtotime("+5 years"));
		$year       = "";
		for ($i = $start_year; $i <= $end_year; $i++)
		{
			$selected = "";
			if ($i == date("Y"))
			{
				$selected = "selected='selected'";
			}
			$year .= "<option value='{$i}' {$selected}>{$i}" . __('年') . "</option>";
		}
		$month = "";
		for ($i = 1; $i <= 12; $i++)
		{
			$selected = "";
			if ($i == date("m"))
			{
				$selected = "selected='selected'";
			}
			$month .= "<option value='{$i}' {$selected}>{$i}" . __('月') . "</option>";
		}
		$arr['year']  = $year;
		$arr['month'] = $month;
		return $arr;
	}

	/**
	 * 2017.3.17 hp 用户选中的时间加选中状态
	 * @param $syear 用户选中的年份
	 * @param $emonth 用户选中的月份
	 * @return mixed
	 */
	public function getYearNew($syear, $emonth)
	{
		$start_year = date("Y", strtotime("-5 years"));
		$end_year   = date("Y", strtotime("+5 years"));
		$year       = "";
		for ($i = $start_year; $i <= $end_year; $i++)
		{
			$selected = "";
			if ($i == $syear)
			{
				$selected = "selected='selected'";
			}
			$year .= "<option value='{$i}' {$selected}>{$i}" . __('年') . "</option>";
		}
		$month = "";
		for ($i = 1; $i <= 12; $i++)
		{
			$selected = "";
			if ($i == $emonth)
			{
				$selected = "selected='selected'";
			}
			$month .= "<option value='{$i}' {$selected}>{$i}" . __('月') . "</option>";
		}
		$arr['year']  = $year;
		$arr['month'] = $month;
		return $arr;
	}

	public function getMonthRange($month)
	{
		$timestamp     = strtotime($month . "-1");
		$monthFirstDay = date('Y-m-1 00:00:00', $timestamp);
		$arr[]         = $monthFirstDay;
		$mdays         = date('t', $timestamp);
		$monthLastDay  = date('Y-m-' . $mdays . ' 23:59:59', $timestamp);
		$arr[]         = $monthLastDay;
		return $arr;
	}

	public function getWeek()
	{
		$month = request_int("month");
		$year  = request_int("year");
		$time  = $year . "-" . $month;
		$data  = $this->get_weekinfo($time);
		$week  = "";
		foreach ($data as $k => $v)
		{
			$week .= "<option value='{$v['0']}~{$v['1']}'>{$v['0']}~{$v['1']}</option>";
		}
		echo $week;
	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function index()
	{
		$start_date                = date("Y-m-d", strtotime("-30 days"));
		$end_date                  = date("Y-m-d");
		$cond_row['goods_date:>='] = $start_date;
		$cond_row['goods_date:<='] = $end_date;

		$cond_row['shop_id']       = Perm::$shopId;
        $analytics = new Analytics();
        $result = $analytics->getGoodsAnalytics($cond_row);
        if($result['status'] == 200){
            $data = $result['data'];
        }else{
            $data = array();
        }
		include $this->view->getView();
	}

	public function hotbak()
	{
		$option = $this->getYear();

		$tyear  = date("Y");
		$tmonth = date("m");
		$stype  = request_string("stype", "month");
		$year   = request_int("year", $tyear);
		$month  = request_int("month", $tmonth);

		if ($stype == "month")
		{
			$time = $this->getMonthRange($year . "-" . $month);
		}
		elseif ($stype == "week")
		{
			$week = request_int("week");
			$time = $this->get_weekinfo($year . "-" . $month, $week);
		}
		$cond_row['goods_date:>='] = $time[0];
		$cond_row['goods_date:<='] = $time[1];

		$cond_row['shop_id'] = Perm::$shopId;

		$field = array(
			"SUM(order_num) as nums",
			"goods_name"
		);
		$group = "goods_id";
		$order = array("nums" => "DESC");
		$limit = array(30);
        
		$num_list = $this->Analysis_ShopGoodsModel->getBySql($field, $cond_row, $group, $order, $limit);

		$data_num['line'] = array();
		$data_num['num']  = array();
		foreach ($num_list as $k => $v)
		{
			$data_num['line'][] = ($k + 1);
			$data_num['num'][]  = $v['nums'];
		}
		$data_num['line'] = json_encode($data_num['line']);
		$data_num['num']  = json_encode($data_num['num']);

		$field     = array(
			"SUM(order_cash) as cashes",
			"goods_name"
		);
		$order     = array("cashes" => "DESC");
		$cash_list = $this->Analysis_ShopGoodsModel->getBySql($field, $cond_row, $group, $order, $limit);

		$data_cash['line'] = array();
		$data_cash['num']  = array();
		foreach ($cash_list as $k => $v)
		{
			$data_cash['line'][] = ($k + 1);
			$data_cash['num'][]  = $v['cashes'];
		}
		$data_cash['line'] = json_encode($data_cash['line']);
		$data_cash['num']  = json_encode($data_cash['num']);
		include $this->view->getView();
	}

    public function hot()
	{
		$week = request_string("week", 1);
		$tyear  = date("Y");
		$tmonth = date("m");
		$stype  = request_string("stype", "month");
		$year   = request_int("year", $tyear);
		$month  = request_int("month", $tmonth);
		$option = $this->getYearNew($year, $month);
		if ($stype == "month")
		{
			$time = $this->getMonthRange($year . "-" . $month);
			$stype_html = '<option value="month" selected="selected">按月统计</option><option value="week">按周统计</option>';
		}
		elseif ($stype == "week")
		{
			$time = explode('~', $week);
			$week_data = $time;
			$stype_html = '<option value="month">按月统计</option><option value="week" selected="selected">按周统计</option>';
		}
		$cond_row['shop_id'] = Perm::$shopId;
		$Order_GoodsModel = new Order_GoodsModel();
		$time[0] = addslashes($time[0]);
		$time[1] = addslashes($time[1]);
		$sql = "SELECT 
			  goods_id,SUM(order_goods_num) nums ,goods_name,SUM(order_goods_amount) amount
			FROM
			  `yf_order_goods` 
			WHERE order_goods_status = 6 and order_goods_time >= '".$time[0]."' and order_goods_time <= '".$time[1]."' and shop_id = ".Perm::$shopId."
			GROUP BY goods_id 
			ORDER BY nums DESC ";

		$rows = $Order_GoodsModel->sql->getAll($sql);

		$k = 0;
		foreach ($rows as  $v)
		{
			$data_num['line'][] = ($k + 1);
			$data_num['num'][]  = $v['nums'];
			$data_num['goods_name'][]  = $v['goods_name'];
			$data_cash['line'][] = ($k + 1);
			$data_cash['num'][]  = $v['amount'];
			$data_cash['goods_name'][] = $v['goods_name'];

			$cash_list[$k] = [
				'goods_name'	=> $v['goods_name'],
				'cashes'	=> $v['amount'],
			];
			$num_list[$k] = [
				'goods_name'	=> $v['goods_name'],
				'nums'	=> $v['nums'],
			];
			$k++;
		}

		$data_cash['line'] = json_encode($data_cash['line']);
		$data_cash['num']  = json_encode($data_cash['num']);
		$data_cash['goods_name']  = json_encode($data_cash['goods_name']);
		$data_num['line'] = json_encode($data_num['line']);
		$data_num['num']  = json_encode($data_num['num']);
		$data_num['goods_name']  = json_encode($data_num['goods_name']);

		include $this->view->getView();
	}
	
	//监控商品的详情
	public function detail()
	{
		if(isset($_GET['keywords']) and !empty($_GET['keywords'])) {
			$keywords = $_GET['keywords'];

            $keysword = '%'.$keywords.'%';
            $cond_row['keyword']       = $keysword;
		}else{
			$keywords = '';
		}

//		$page_row 	  = isset($_GET["page"]) ?  $_GET['page'] ==1 ? 0 :$_GET['page'] : 0;
//		$pageSize = 10;
//		$plat_id = Yf_Registry::get('analytics_app_id');

		$cond_row['shop_id'] = $shop_id = Perm::$shopId;
//		$cond_row['page']       = $page;
//		$cond_row['pageSize']       = $pageSize;
//		$analytics = new Analytics();
//		$result = $analytics->getGoodsDetail($cond_row);
        //获取商品
        $Yf_Page = new Yf_Page();
        $Yf_Page->listRows = request_int('rows', 10);
        $rows = $Yf_Page->listRows;
        $offset = request_int('firstRow', 0);
        $page = ceil_r($offset / $rows);

        $wap_pagesize = request_int('pagesize');
        $pos = request_int('pos');
        if ($pos > 0) {
            $wap_pagesize = $wap_pagesize * (ceil($pos / $wap_pagesize)) + $wap_pagesize;
        }
        $wap_curpage = request_int('curpage');

        if (!empty($wap_pagesize)) {
            $rows = $wap_pagesize;
        }

        if (!empty($wap_curpage)) {
            $page = $wap_curpage;
        }
        //$cond_row['shop_status'] = Shop_BaseModel::SHOP_STATUS_OPEN;
        //$wap_pagesize = request_int('pagesize');
        $Goods_BaseModel = new Goods_BaseModel();
        $cond_row['goods_is_shelves'] = $Goods_BaseModel::GOODS_UP;
        $order_row = array();
        $goods_lists = $Goods_BaseModel->getBaseList($cond_row, $order_row, $page, $rows);
        $Yf_Page->totalRows = $goods_lists['totalsize'];
        $page_nav = $Yf_Page->prompt();
        $result['data']['page']  = $goods_lists['page'];
        $result['data']['total'] = $goods_lists['total'];
        $result['data']['totalsize'] = $goods_lists['totalsize'];
        $result['data']['records'] = $goods_lists['records'];

        $Order_GoodsModel = new Order_GoodsModel();
        foreach ($goods_lists['items'] as $key=> $value){
            $result['data']['items'][$key]['apb_product_id'] = $value['goods_id'];
            $cond_row['goods_id'] = $value['goods_id'];
            $order_goods_amounts = $Order_GoodsModel->getOrderGoodsPrices($cond_row);
            $result['data']['items'][$key]['apb_total_price'] = empty($order_goods_amounts)? 0.00 : $order_goods_amounts;
            $result['data']['items'][$key]['apb_access'] = $value['goods_click'];
            $result['data']['items'][$key]['apb_follow'] = $value['goods_collect'];
            $result['data']['items'][$key]['apb_single_price'] = $value['goods_price'];
            $result['data']['items'][$key]['apb_state'] = $value['goods_is_shelves'];
            $result['data']['items'][$key]['apb_common_id'] = $value['common_id'];
            $result['data']['items'][$key]['apb_url'] = Yf_Registry::get('shop_api_url').'/index.php?ctl=Goods_Goods&met=goods&type=goods&gid='.$value['goods_id'];
            $result['data']['items'][$key]['apb_name'] =$value['goods_name'];
            $result['data']['items'][$key]['apb_sales_num']= $value['goods_salenum'];
            $result['data']['items'][$key]['apb_pic'] = $value['goods_image'];
           // $result['data']['items'][$key]['apb_conversion'] =round((empty($value['goods_salenum'])?0:$value['goods_salenum'])/(empty($value['goods_click'])?0:$value['goods_click']),2);
            $result['data']['items'][$key]['apb_spec'] =$value['goods_spec'];
        }

		if($result){
			$data_productbase = $result['data']['items'];
			$product_total 	  = $result['data']['totalsize'];
			$maxPages 		  = $result['data']['total'];
			//获取商品下单金额

            $goods_ids = array();
            foreach ($data_productbase as $value){
                $goods_ids[] = $value['apb_product_id'];
            }

            $goods_model = new Goods_BaseModel();
            //获取商品规格
            $spec_list = $goods_model->getGoodsSpecByGoodIds($goods_ids);
            foreach ($spec_list as $key => $val){
                foreach ($val as $v){
                    $spec_list[$key]['spec_info'] .= $v['name'].':'.$v['value'].';';
                }
            }
            foreach ($data_productbase as $k => $spec){
                $data_productbase[$k]['spec'] = isset($spec_list[$spec['apb_product_id']]['spec_info']) ? $spec_list[$spec['apb_product_id']]['spec_info'] : '';
            }
		}else{
			$data = array();
		}
        
		include $this->view->getView();
	}

	//监控商品单品分析
	public function analysis()
	{
		if(!empty($_POST['sdate']) && !empty($_POST['edate'])) {
			$stime = $_POST['sdate'];
			$etime = $_POST['edate'];
		}else {
			$stime = date('Y-m-d', (time()-3600*24*7));
			$etime = date('Y-m-d', (time()-3600*24));
		}

		/* 图表 */
		$starttime 		   = strtotime($stime);
		$endtime   		   = strtotime($etime);
		$second            = $endtime-$starttime;
		$day               = floor($second/(3600*24)); //共有多少天
		if(isset($_GET['product_id']) and !empty($_GET['product_id'])) {
			$product_id = $_GET['product_id'];
		}else{
			$product_id = '';
		}
//		$plat_id = Yf_Registry::get('analytics_app_id');
		$cond_row['shop_id'] = $shop_id = Perm::$shopId;
//		$cond_row['product_id'] = $product_id;
		if($day > 31)
		{
			$stime = date('Y-m-d', (time()-3600*24*7));
			$etime = date('Y-m-d', (time()-3600*24));
			$starttime 		   = strtotime($stime);
			$endtime   		   = strtotime($etime);
			$second            = $endtime-$starttime;
			$day               = floor($second/(3600*24)); //共有多少天
		}
		$cond_row['stime'] = $stime;
		$cond_row['etime'] = $etime;
		$cond_row['goods_id'] = $product_id;
		//获取最近七天的下单量
        $Order_GoodsModel = new Order_GoodsModel();
        $data_orderproduct = $Order_GoodsModel->getOrderCountSum($cond_row);
        //获取最近七天的商品评分
        $Goods_EvaluationModel =new Goods_EvaluationModel();
        $data_productday = $Goods_EvaluationModel->getGoodsEvalution($cond_row);
        //获取最近七天的商品关注
        $User_FavoritesGoodsModel = new User_FavoritesGoodsModel();
        $date_favortiess=$User_FavoritesGoodsModel->getGoodsFavoritesSum($cond_row);
        //获取最近七天商品浏览量
        $User_FootprintModel =new User_FootprintModel();
        $date_foot_prints = $User_FootprintModel->getGoodsFootSum($cond_row);
        //$result['data']['time'] = $stime;
        $stime = strtotime($stime);
        for ($i = 0; $i<=6;$i++){
            //今日时间

            $time 		       = date("Y-m-d", $stime);
            $categories[]  	   = date("m-d", $stime);
            //单品销售量 、 单品销售额
            $day_orderproduct  = $this->day_array($data_orderproduct, $stime, 'time');
            $data_order[] 	   = $this->sum_array($day_orderproduct, 'aop_num');
            $data_sales[]  	   = $this->sum_mul_array($day_orderproduct, 'aop_num', 'aop_price');
            //商品关注
            $day_favorites = $this->day_array($date_favortiess,$stime,'time');
            $data_followr[] =$this->sum_array($day_favorites, 'apd_follow');
            //商品浏览量
            $day_productday	   = $this->day_array($data_productday, $stime, 'time');
            $data_score[]  = $this->sum_array($day_productday, 'apd_score');
            //商品浏览量
            $date_foot_print = $this->day_array($date_foot_prints,$stime,'time');
            $data_pv_num[] 	 = $pv_total = $this->sum_array($date_foot_print,'foot_print');
            @$bai = (count($data_orderproduct) / $pv_total)?:0;
            if($bai){
                $bai = number_format($bai*100,2);
            }
            $data_conversion[] = $bai;
            //明天时间
            $stime = $stime + 3600*24;
        }
        $x_data = json_encode($categories);
        $y_data_order 	   = json_encode($data_order);
        $y_data_sales 	   = json_encode($data_sales);
        $y_data_followr 	   = json_encode($data_followr);
        $y_data_conversion 	   = json_encode($data_conversion);
        $y_data_pv_num 	   = json_encode($data_pv_num);
        $y_data_score 	   = json_encode($data_score);
//		$analytics = new Analytics();
//		$result = $analytics->goodsAnalysis($cond_row);
//		$time = $result['data']['time'];
//		$categories = $result['data']['categories'];
//		$data_order = $result['data']['data_order'];
//		$data_sales = $result['data']['data_sales'];
//		$data_followr = $result['data']['data_followr'];
//		$data_conversion = $result['data']['data_conversion'];
//		$data_pv_num = $result['data']['data_pv_num'];
//		$data_score = $result['data']['data_score'];
//		$starttime = $result['data']['starttime'];
		
//		$x_data = $result['data']['x_data'];
//		$y_data_order = $result['data']['y_data_order'];
//		$y_data_sales = $result['data']['y_data_sales'];
//		$y_data_followr = $result['data']['y_data_followr'];
//		$y_data_conversion = $result['data']['y_data_conversion'];
//		$y_data_pv_num = $result['data']['y_data_pv_num'];
//		$y_data_score = $result['data']['y_data_score'];
//		echo '<pre>';print_r($result);exit;
		include $this->view->getView();
	}
    /* 函数功能：取出二维数组某天的数据组成新的数组
 * 参数说明：$arr-源数组；$daytime-某天时间戳；target_key-包含时间的字段
 */
    private function day_array($arr, $daytime, $target_key) {

        $new_arr=array();
        foreach ($arr as $k => $v)
        {
            if(strtotime($v[$target_key])>=$daytime && strtotime($v[$target_key])<($daytime + 24*60*60))
            {
                $new_arr[] = $v;
            }
        }

        return $new_arr;
    }
    /* 函数功能：对二维数组以某一键名进行相加求和，返回总和
 * 参数说明：$arr-源数组；target_key_sum-要求和的键名
 */
    private function sum_array($arr, $target_key_sum) {
        $sum = 0;

        foreach ($arr as $k => $v)
        {
            $sum += $v[$target_key_sum];
        }

        return $sum;
    }
    /* 函数功能：对二维数组以某两个键名进行相乘后相加求和，返回总和
 * 参数说明：$arr-源数组；target_key_sum-要求和的键名
 */
    private function sum_mul_array($arr, $target_key_1, $target_key_2) {

        $sum = 0;

        foreach ($arr as $k => $v)
        {
            $sum += $v[$target_key_1] * $v[$target_key_2];
        }

        return $sum;
    }
}

?>
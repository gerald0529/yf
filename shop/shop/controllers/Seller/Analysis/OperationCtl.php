<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Yf <service@yuanfeng.cn>
 */
class Seller_Analysis_OperationCtl extends Seller_Controller
{
	public $Analysis_ShopAreaModel = null;

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
		$this->Analysis_ShopAreaModel = new Analysis_ShopAreaModel();
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
			$week .= "<option value='{$k}'>{$v['0']}~{$v['1']}</option>";
		}
		echo $week;
	}

	/**
	 * 首页
	 *
	 * @access public
	 */

	public function indexBak()
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
		$cond_row['area_date:>='] = $time[0];
		$cond_row['area_date:<='] = $time[1];

		$cond_row['shop_id'] = Perm::$shopId;

		$field = array(
			"SUM(order_num) as nums",
			"area"
		);
		$group = "area";

		$num_list = $this->Analysis_ShopAreaModel->getBySql($field, $cond_row, $group);

		$data_order_num = array();
		foreach ($num_list as $k => $v)
		{
			$arr['name']      = $v['area'];
			$arr['value']     = $v['nums'];
			$data_order_num[] = $arr;
		}
		$data_order_num = json_encode($data_order_num);

		$field     = array(
			"SUM(order_cash) as cashes",
			"area"
		);
		$cash_list = $this->Analysis_ShopAreaModel->getBySql($field, $cond_row, $group);

		$data_order_cash = array();
		foreach ($cash_list as $k => $v)
		{
			$arr['name']       = $v['area'];
			$arr['value']      = $v['cashes'];
			$data_order_cash[] = $arr;
		}
		$data_order_cash = json_encode($data_order_cash);

		$field     = array(
			"SUM(order_user_num) as users",
			"area"
		);
		$user_list = $this->Analysis_ShopAreaModel->getBySql($field, $cond_row, $group);

		$data_order_user = array();
		foreach ($user_list as $k => $v)
		{
			$arr['name']       = $v['area'];
			$arr['value']      = $v['users'];
			$data_order_user[] = $arr;
		}
		$data_order_user = json_encode($data_order_user);

		include $this->view->getView();
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

	//@param flag表示查询数据类别，1表示查询下单会员数，2表示下单金额，3表示下单量.默认是1
    public function index()
	{
		$kinds = request_int('kinds', 1);
		$week = request_string("week", 1);
		$tabname     = '下单会员数';
		$tyear  = date("Y");
		$tmonth = date("m");
		$stype  = request_string("stype", "month");
		$year   = request_int("year", $tyear);
		$month  = request_int("month", $tmonth);
		$option = $this->getYearNew($year, $month);
//		echo  '<pre>';print_r($week);exit;
		if ($stype == "month")
		{
			$time = $this->getMonthRange($year . "-" . $month);
			$stype_html = '<option value="month" selected="selected">按月统计</option><option value="week">按周统计</option>';
		}
		elseif ($stype == "week")
		{
//			$time = $this->get_weekinfo($year . "-" . $month, $week);
			$time = explode('~', $week);
			$week_data = $time;
			$stype_html = '<option value="month">按月统计</option><option value="week" selected="selected">按周统计</option>';
		}
//		echo  '<pre>';print_r($week);exit;
		$cond_row['start_time'] = $time[0];
		$cond_row['end_time'] = $time[1];
		if($kinds == 2)
		{
			$tabname = '下单金额';
			$cond_row['flag'] = 2;
		}
		elseif($kinds == 3)
		{
			$tabname = '下单数';
			$cond_row['flag'] = 3;
		}
		else
		{
			$cond_row['flag'] = 1;
		}
		$cond_row['shop_id'] = Perm::$shopId;
//        $analytics = new Analytics();
//        $result = $analytics->getAreaData($cond_row);
        $result = $this->getResult($cond_row);
		if($result['data'])
		{
			$data_country = $result['data']['data_country'];
			$data_provices = $result['data']['data_provices'];
		}
		
		if($kinds == 1)
		{
			$tabmenu_html = '
        <li class="active bbc_seller_bg"><a href="javascript:void(0);" data-id="1">下单会员数</a></li>
        <li><a href="javascript:void(0);" data-id="2">下单金额</a></li>
        <li><a href="javascript:void(0);" data-id="3">下单量</a></li>';
		}
		elseif($kinds == 2)
		{
			$tabmenu_html = '
        <li><a href="javascript:void(0);" data-id="1">下单会员数</a></li>
        <li class="active bbc_seller_bg"><a href="javascript:void(0);" data-id="2">下单金额</a></li>
        <li><a href="javascript:void(0);" data-id="3">下单量</a></li>';
		}
		elseif($kinds == 3)
		{
			$tabmenu_html = '
        <li><a href="javascript:void(0);" data-id="1">下单会员数</a></li>
        <li><a href="javascript:void(0);" data-id="2">下单金额</a></li>
        <li class="active bbc_seller_bg"><a href="javascript:void(0);" data-id="3">下单量</a></li>';
		}

		include $this->view->getView();
	}

	/**
	 * 获取时间范围内下单会员数的省份和城市信息 2016.3.16 hp
	 * @param flag表示查询数据类别，1表示查询下单会员数，2表示下单金额，3表示下单量.默认是1
	 */
	public function orderpeople()
	{
		$tabname     = '下单会员数';
		$tyear  = date("Y");
		$tmonth = date("m");
		$stype  = request_string("stype", "month");
		$year   = request_int("year", $tyear);
		$month  = request_int("month", $tmonth);
		$option = $this->getYearNew($year, $month);

		if ($stype == "month")
		{
			$time = $this->getMonthRange($year . "-" . $month);
		}
		elseif ($stype == "week")
		{
			$week = request_int("week");
			$time = $this->get_weekinfo($year . "-" . $month, $week);
		}
		
		$cond_row['start_time'] = $time[0];
		$cond_row['end_time'] = $time[1];
		$cond_row['flag'] = 1;
		$cond_row['shop_id'] = Perm::$shopId;
//		$analytics = new Analytics();
//		$result = $analytics->getAreaData($cond_row);
        $result = $this->getResult($cond_row);
		if($result['data'])
		{
			$data_country = $result['data']['data_country'];
			$data_provices = $result['data']['data_provices'];
		}

		include $this->view->getView();
	}
	
	/**
	 * 获取时间范围内订单总金额的省份和城市信息 2016.3.16 hp
	 * @param flag表示查询数据类别，1表示查询下单会员数，2表示下单金额，3表示下单量.默认是1
	 */
	public function orderprice()
	{
		$tabname     = '下单金额';
		$plat_id = Yf_Registry::get('analytics_app_id');

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
//		echo  '<pre>';print_r($time);exit;
		$cond_row = array();
		$cond_row['start_time'] = $time[0];
		$cond_row['end_time'] = $time[1];
		$cond_row['shop_id'] = Perm::$shopId;
		$cond_row['flag'] = 2;
//		$analytics = new Analytics();
//		$result = $analytics->getAreaData($cond_row);
        $result = $this->getResult($cond_row);
		$data_country = $result['data']['data_country'];
		$data_provices = $result['data']['data_provices'];
//		echo '<pre>';print_r($result);exit;
		include $this->view->getView();
	}

	/**
	 * 获取时间范围内订单总数的省份和城市信息 2016.3.16 hp
	 * @param flag表示查询数据类别，1表示查询下单会员数，2表示下单金额，3表示下单量.默认是1
	 */
	public function ordernum()
	{
		$tabname     = '下单数';
		$plat_id = Yf_Registry::get('analytics_app_id');

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
//		echo  '<pre>';print_r($time);exit;
		$cond_row = array();
		$cond_row['start_time'] = $time[0];
		$cond_row['end_time'] = $time[1];
		$cond_row['shop_id'] = Perm::$shopId;
		$cond_row['flag'] = 3;
//		$analytics = new Analytics();
//		$result = $analytics->getAreaData($cond_row);
        $result = $this->getResult($cond_row);
		$data_country = $result['data']['data_country'];
		$data_provices = $result['data']['data_provices'];
//		echo '<pre>';print_r($result);exit;
		include $this->view->getView();
	}

    /**
     * 替换掉原来的数据统计
     * @param $cond_row
     * @return mixed
     */
    public function getResult($cond_row){
        $baseDistrict = new Base_DistrictModel();
        $districts = $baseDistrict->getByWhere(array('district_parent_id'=>0));

        foreach ($districts as $key => $val){
            $provices = $baseDistrict->getByWhere(array('district_parent_id'=>$val['district_id']));
            $con = 0;
            $num = 0;
            $orderBase = new Order_BaseModel();

            foreach ($provices as $provice){
                $where_row['order_receiver_name:LIKE'] = '%'.$provice['district_name'].'%';
                $where_row['shop_id'] = $cond_row['shop_id'];
                $where_row['start_time'] = $cond_row['start_time'];
                $where_row['end_time'] = $cond_row['end_time'];
                if ($cond_row['flag'] == 1) {
                    $rs = $orderBase->getOrderUsers($where_row);
                    $con = count($rs);
                } elseif ($cond_row['flag'] == 2){
                    $rs = $orderBase->getOrderPrices($where_row);
                    $con= $rs;
                } else {
                    $rs = $orderBase->getOrderNums($where_row);
                    $con = $rs;
                }
                $num +=$con;
                $city[$provice['district_name']] = $con;
            }
            $data['data_country'][$val['district_name']] = $num;
            $data['data_provices'][$val['district_name']] = $city;
            unset($city);
        }
        $data['data'] = $data;

        return $data;
    }
}

?>
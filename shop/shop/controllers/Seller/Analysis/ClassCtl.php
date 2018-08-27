<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Seller_Analysis_ClassCtl extends Seller_Controller
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

	public function index()
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

}

?>
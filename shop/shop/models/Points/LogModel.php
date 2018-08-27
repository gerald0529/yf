<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/15
 * Time: 17:57
 */
class Points_LogModel extends Points_Log
{
	const ONREG        = 1;    //会员注册
	const ONLOGIN      = 2;    //会员登录
	const ONEVALUATION = 3;    //评价
	const ONBUY        = 4;    //购买商品
	const ONOFF        = 5;    //
	const ONADMIN      = 6;    //管理员操作
	const ONCHANGE     = 7;    //换购商品
	const ONVOUCHER    = 8;    //兑换代金券
	//积分获取途径
	public static $classId = array(
		1 => '会员注册',
		2 => '会员登录',
		3 => '评价',
		4 => '购买商品',
		6 => '管理员操作',
		7 => '换购商品',
		8 => '兑换代金券'
	);

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
	public function getPointsLogList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
		foreach ($data['items'] as $key => $value)
		{
			$data['items'][$key]['classid']  = $data['items'][$key]['class_id'];
			$data['items'][$key]['class_id'] = __(Points_LogModel::$classId[$value['class_id']]);
			if ($value['points_log_type'] == '2')
			{
				$data['items'][$key]['points_log_points'] = -$value['points_log_points'];
			}
		}

		return $data;
	}
	
	/**
	 * 获取增加减少积分
	 * @param  array $cond_row 查询条件
	 * @return array $data 返回的查询内容
	 */
	public function getPointsLog($cond_row)
	{
		$data = $this->getByWhere($cond_row);
		return $data;
	}


}
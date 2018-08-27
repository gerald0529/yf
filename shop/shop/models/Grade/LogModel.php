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
class Grade_LogModel extends Grade_Log
{
	const ONLOGIN = 1;    //会员登录
	const ONBUY = 2;    //购买商品
	const ONEVALUATION = 3;    //评价

	//经验值获取途径
	public static $classId = array(
		1 => '会员登录',
		2 => '购买商品',
		3 => '评价'
	);

	/**
	 * 读取分页列表
	 *
	 * @param  array $cond_row 查询条件
	 * @param  array $order_row 排序信息
	 * @param  array $page 当前页码
	 * @param  array $rows 每页记录数
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getGradeLogList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
		foreach ($data['items'] as $key => $value)
		{
			$data['items'][$key]['class_id'] = __(Grade_LogModel::$classId[$value['class_id']]);
		}
		return $data;
	}
	
	/**
	 * 经验值等级
	 * @param  array $cond_row 查询条件
	 * @return array $data 返回的查询内容
	 */
	public function getGradeLog($cond_row)
	{
		$data = $this->getByWhere($cond_row);
		return $data;
	}


}
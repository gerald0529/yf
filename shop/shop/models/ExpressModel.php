<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class ExpressModel extends Express
{
	public static $expressStatus       = array(
		"0" => '否',
		"1" => '是'
	);
	public static $expressDisplayorder = array(
		"0" => '否',
		"1" => '是'
	);
	public static $expressCommonorder  = array(
		"0" => '否',
		"1" => '是'
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
	public function getExpressList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
		
		foreach ($data["items"] as $key => $value)
		{

			$data["items"][$key]["express_status"]       = __(ExpressModel::$expressStatus[$value["express_status"]]);
			$data["items"][$key]["express_displayorder"] = __(ExpressModel::$expressDisplayorder[$value["express_displayorder"]]);
			$data["items"][$key]["express_commonorder"]  = __(ExpressModel::$expressCommonorder[$value["express_commonorder"]]);

		}
		return $data;
	}

	/**
	 * 读取物流公司详情
	 *
	 * @param  int $express_id 主键值
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getOneExpress($express_id)
	{
		$data = $this->getOne($express_id);

		return $data;
	}

	/**
	 * 读取详情
	 *
	 * @param  array $order_row 主键值
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getExpressName($order_row)
	{
		$data = $this->getOneByWhere($order_row);

		return $data;
	}
	

}

?>
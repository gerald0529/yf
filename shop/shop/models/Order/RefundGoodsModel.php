<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Order_RefundGoodsModel extends Order_RefundGoods
{


	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 读取分页列表
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getReturnReasonList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);

		return $data;
	}

}

?>
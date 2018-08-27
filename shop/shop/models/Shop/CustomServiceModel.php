<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Shop_CustomServiceModel extends Shop_CustomService
{
	const SHOP_STATUS_OPEN = 3;  //开启

	/**
	 * 读取客服列表
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getServiceList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data  = $this->listByWhere($cond_row, $order_row, $page, $rows);
		
		return $data;
	}

	
}

?>
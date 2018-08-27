<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class AdvertisementModel extends Advertisement
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getAdvertisementList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
		return $data;
	}

	/**
	 * 读取详情
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getOneAdvertisement($express_id)
	{
		$data = $this->getOne($express_id);

		return $data;
	}

	/**
	 * 读取详情
	 *
	 *
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getAdvertisementName($order_row)
	{
		$data = $this->getOneByWhere($order_row);

		return $data;
	}
	

}

?>
<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Distribution_ShopDirectsellerConfigModel extends Distribution_ShopDirectsellerConfig
{

	private static $_instance;

	/**
	 * 读取分页列表
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getConfigList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->getByWhere($cond_row, $order_row, $page, $rows);
	}
	
	/*
	* 获取店铺分销设置
	*/
	public function getConfigs($shop_id)
	{
		return $this->getOne($shop_id);
	}
 
	/*
	 * 修改店铺分销配置
	 */
	public function updateConfig($shop_id, $field_row)
	{
		$update_flag = $this->edit($shop_id, $field_row);
		return $update_flag;
	}
}
?>
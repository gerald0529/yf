<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Platform_CustomServiceTypeModel extends Platform_CustomServiceType
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $custom_service_type_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCustomServiceTypeList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}
	
	public function getOneType($custom_type_id)
	{
		$data = $this->getOne($custom_type_id);
		return $data;
	}
}

?>
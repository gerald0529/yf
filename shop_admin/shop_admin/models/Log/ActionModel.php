<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Log_ActionModel extends Log_Action
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $log_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getActionList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);


		$action_ids = array_column($data['items'], 'action_id');

		$Rights_BaseModel = new Rights_BaseModel();
		$rights_rows      = $Rights_BaseModel->getBase($action_ids);

		foreach ($data['items'] as $k => $item)
		{
			if ($item['action_id'])
			{
				$item['action_id'] = $rights_rows[$item['action_id']]['rights_name'];
			}
			else
			{
				$item['action_id'] = '';
			}

			is_array($item['log_param']) ? $item['log_param'] = encode_json($item['log_param']) : null;
			$data['items'][$k] = $item;
		}
		return $data;
	}
}

?>
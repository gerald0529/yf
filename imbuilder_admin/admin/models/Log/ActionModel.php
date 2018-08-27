<?php if (!defined('ROOT_PATH')) exit('No Permission');
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
	public function getActionList($log_id = null, $page=1, $rows=100, $sort='asc')
	{
		$this->sql->setOrder('log_time', $sort);

		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$log_id_row = array();
		$log_id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($log_id_row)
		{
			$this->sql->setOrder('log_time', $sort);
			$data_rows = $this->getAction($log_id_row);
		}

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);
		
		$Base_ProtocalModel = new Base_ProtocalModel();
		foreach($data['items'] as $key=>$val)
		{
			$Base_ProtocalModel->sql->setWhere('rights_id', $val['action_id']);
			$base_pro = current($Base_ProtocalModel->get('*'));
			$data['items'][$key]['log_param'] = $base_pro['comment'];
		}
		return $data;
	}
}
?>
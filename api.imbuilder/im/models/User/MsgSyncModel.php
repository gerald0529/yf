<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_MsgSyncModel extends User_MsgSync
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $msg_sync_date 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getMsgSyncList($msg_sync_date = null, $page=1, $rows=100, $sort='asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$msg_sync_date_row = array();
		$msg_sync_date_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($msg_sync_date_row)
		{
			$data_rows = $this->getMsgSync($msg_sync_date_row);
		}

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}
}
?>
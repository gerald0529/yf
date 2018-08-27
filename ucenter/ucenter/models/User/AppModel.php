<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_AppModel extends User_App
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $user_name 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getAppList($user_name = null, $page=1, $rows=100, $sort='asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$user_name_row = array();
		$user_name_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($user_name_row)
		{
			$data_rows = $this->getApp($user_name_row);
		}

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}

	public function getAppByNameAndAppId($user_name, $app_id)
	{
		$data = array();

		$this->sql->setWhere('user_name', $user_name);
		$this->sql->setWhere('app_id', $app_id);
		$data_rows = $this->getApp('*');

		if ($data_rows)
		{
			$data = array_pop($data_rows);
		}

		return $data;
	}


}
?>
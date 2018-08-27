<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_BindConnectModel extends User_BindConnect
{

	const SINA_WEIBO = 1;
	const QQ         = 2;
	const WEIXIN     = 3;
	const ALIPAY     = 4;



	const EMAIL     = 11;
	const MOBILE     = 12;


	/**
	 * 读取分页列表
	 *
	 * @param  int $bind_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBindConnectList($bind_id = null, $page=1, $rows=100, $sort='asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$bind_id_row = array();
		$bind_id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($bind_id_row)
		{
			$data_rows = $this->getBindConnect($bind_id_row);
		}

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}

	/**
	 * 用户绑定
	 *
	 * @param  int $bind_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function updateBindConnect($token=null, $user_id=null)
	{
		$this->sql->setWhere('bind_token',$token);
		$bind_id_row = $this->selectKeyLimit();
		fb($bind_id_row);

	}

	public function getBindConnectByToken($token=null)
	{
		$this->sql->setWhere('bind_token',$token);

		$bind_id_row = array();
		$bind_id_row = $this->selectKeyLimit();

		$data_rows = array();

		if ($bind_id_row)
		{
			$data_rows = $this->getBindConnect($bind_id_row);
		}

		$data_rows = array_values($data_rows);
		return $data_rows;
	}

	//根据用户id获取绑定信息
	public function getBindConnectByuserid($user_id=null, $type=null)
	{
		$this->sql->setWhere('user_id',$user_id);
		$this->sql->setWhere('bind_type',$type);

		$bind_id_row = $this->selectKeyLimit();
		return $bind_id_row;
	}

	//根据用户id获取绑定信息
	public function getBindConnectByUseridType($user_id=null, $type=null)
	{
		$this->sql->setWhere('user_id',$user_id);
		$this->sql->setWhere('bind_type',$type);
		$bind_id_row = $this->selectKeyLimit();

		$data_rows = array();

		if ($bind_id_row)
		{
			$data_rows = $this->getBindConnect($bind_id_row);
		}

		$data_rows = current($data_rows);
		return $data_rows;
	}
}
?>

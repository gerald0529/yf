<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Consult_ReplyModel extends Consult_Reply
{

	const REPLY_BUYER  = 2;
	const REPLY_SELLER = 1;

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
	public function getReplyList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
		return $data;
	}

	public function removeSelectedConsult($consult_id)
	{

		$del_flag = $this->remove($consult_id);

		//$this->removeKey($config_key);
		return $del_flag;
	}

}

?>
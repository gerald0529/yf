<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class User_MessageModel extends User_Message
{

	/**
	 * 读取分页列表
	 *
	 * @param  array $cond_row 查询条件
	 * @param  array $order_row 排序信息
	 * @param  array $page 当前页码
	 * @param  array $rows 每页记录数
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getMessageList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
		
		return $data;
	}
	
	/**
	 * 删除选中的消息
	 *
	 * @param  array $config_array 主键值
	 * @return array $flag 返回的查询内容
	 * @access public
	 */
	public function removeMessageSelected($config_array = array())
	{

		foreach ($config_array as $key => $value)
		{
			$flag = $this->removeMessage($value);
		}
	}

	/**
	 * 读取详情
	 *
	 * @param  array $cond_row 查询条件
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getMessageDetail($order_row = array())
	{
		$data = $this->getOneByWhere($order_row);
		return $data;
	}

	/**
	 * 读数量
	 *
	 * @param  array $cond_row 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCount($cond_row = array())
	{
		return $this->getNum($cond_row);
	}

}

?>
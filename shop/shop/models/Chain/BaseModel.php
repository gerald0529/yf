<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Chain_BaseModel extends Chain_Base
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $chain_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseList($cond_row = array(), $order_row = array(), $page=1, $rows=100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	//获取单个门店信息
	public function getChainInfo ($chain_id)
	{
		return current($this->getBase($chain_id));
	}
}
?>
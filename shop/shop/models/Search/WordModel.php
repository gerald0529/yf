<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Search_WordModel extends Search_Word
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
	public function getSearchWordList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	/**
	 * 读取详情
	 * @param  array $cond_row 查询条件
	 * @return array $data 返回的查询内容
	 */
	public function getSearchWordInfo($cond_row = array())
	{
		$data = $this->getOneByWhere($cond_row);

		return $data;
	}
}

?>
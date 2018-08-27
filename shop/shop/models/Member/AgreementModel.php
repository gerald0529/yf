<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Member_AgreementModel extends Member_Agreement
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $member_agreement_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getAgreementList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}
}

?>
<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Rec_PositionModel extends Rec_Position
{
	const POSITION_TYPE_PIC        = 0; //推荐位类型，图片
	const POSITION_TYPE_CON        = 1; //推荐位类型，文字
	const POSITION_ALERT_TYPE_SELF = 0; //本窗口
	const POSITION_ALERT_TYPE_NEW  = 1; //新窗口弹出


	/**
	 * 读取分页列表
	 *
	 * @param  int $position_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getPositionList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}
}

?>
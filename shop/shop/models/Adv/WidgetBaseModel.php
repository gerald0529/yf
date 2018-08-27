<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Adv_WidgetBaseModel extends Adv_WidgetBase
{

	const WIDGET_TYPE_PIC    = 1; //类型图片
	const WIDGET_TYPE_SLIDE  = 2; //类型幻灯片
	const WIDGET_TYPE_SCROLL = 3; //类型滚动
	const WIDGET_TYPE_WRIT   = 4; //文字

	const WIDGET_ACTIVE_TRUE  = 0; //不启用
	const WIDGET_ACTIVE_FALSE = 1; //启用

	/**
	 * 读取分页列表
	 *
	 * @param  int $widget_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getWidgetBaseList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}
}

?>
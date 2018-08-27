<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/15
 * Time: 17:57
 */
class ManSong_RuleModel extends ManSong_Rule
{
	public function getManSongRuleByWhere($cond_row, $order_row = array())
	{
		return $this->getByWhere($cond_row, $order_row);
	}

	public function addManSongRule($field_row, $return_insert_id)
	{
		return $this->add($field_row, $return_insert_id);
	}

	public function removeManSongRule($rule_id)
	{
		$del_flag = $this->remove($rule_id);
		return $del_flag;
	}

	public function getManSongRuleByActId($cond_row, $order_row)
	{
		return $this->getByWhere($cond_row, $order_row);
	}

	public function getManSongGoodsIdByWhere($cond_row)
	{
		$mansong_list = $this->getByWhere($cond_row);
		$goods_ids = array_column($mansong_list,'goods_id');
		return  $goods_ids;
	}
}
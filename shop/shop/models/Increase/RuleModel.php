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
class Increase_RuleModel extends Increase_Rule
{
	public function getIncreaseRuleList($cond_row = array(), $order_row, $page, $rows)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	public function getIncreaseRuleByWhere($cond_row, $order_row = array())
	{
		return $this->getByWhere($cond_row, $order_row);
	}

	public function removeIncreaseRuleItem($rule_id)
	{
		$del_flag = $this->remove($rule_id);

		return $del_flag;
	}

	public function getIncreaseRuleByActId($cond_row, $order_row)
	{
		return $this->getByWhere($cond_row, $order_row);
	}

	public function getRuleIdByWhere($cond_row)
	{
		$row = $this->getOneByWhere($cond_row);
		if ($row)
		{
			return $row['rule_id'];
		}

	}

	public function getRulePriceByWhere($cond_row, $order_row)
	{
		$price_row = array();
		$row       = $this->getByWhere($cond_row, $order_row);
		if ($row)
		{
			foreach ($row as $key => $value)
			{
				$price_row[$value['rule_id']] = $value['rule_price'];
			}
		}
		return $price_row;
	}

	public function addIncreaseRule($field_row, $return_insert_id)
	{
		return $this->add($field_row, $return_insert_id);
	}

	public function editIncreaseRule($rule_id, $field_row)
	{
		return $this->edit($rule_id, $field_row);
	}
}
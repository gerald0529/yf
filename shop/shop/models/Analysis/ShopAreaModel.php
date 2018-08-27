<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 *
 *
 * @category   Framework
 * @package    __init__
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2016, 黄新泽
 * @version    1.0
 * @todo
 */
class Analysis_ShopAreaModel extends Analysis_ShopArea
{
	public function getBySql($field, $where = NULL, $group = NULL, $order = NULL, $limit = NULL)
	{
		$fieldtxt = implode(",", $field);
		$wheretxt = "";
		if (!empty($where))
		{
			$wheretxt .= " where 1";
			foreach ($where as $k => $v)
			{
				$arr        = explode(":", $k);
				$fieldwhere = $arr[0];
				$flagwhere  = isset($arr[1]) ? $arr[1] : "=";
				$wheretxt .= " and {$fieldwhere}{$flagwhere}'{$v}'";
			}
		}
		if ($group)
		{
			$wheretxt .= " group by {$group}";
		}
		if (!empty($order))
		{
			$wheretxt .= " order by ";
			$ordertxt = "";
			foreach ($order as $k => $v)
			{
				$ordertxt .= "{$k} {$v},";
			}
			$ordertxt = trim($ordertxt, ",");
			$wheretxt .= $ordertxt;
		}
		if (!empty($limit))
		{
			$limittxt = implode(",", $limit);
			$wheretxt .= " limit {$limittxt}";
		}
		$sql = "select {$fieldtxt} from {$this->_tableName} {$wheretxt}";
		//echo $sql;die;
		$data = $this->sql->getAll($sql);
		return $data;
	}

}

?>
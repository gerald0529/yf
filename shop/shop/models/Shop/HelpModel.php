<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Shop_HelpModel extends Shop_Help
{

	public function getHelpRow($table_primary_key_value = null, $key_row = null, $order_row = array())
	{
		return $this->get($table_primary_key_value, $key_row, $order_row);
	}


	public function getHelpWhere($cond_row = array(), $order_row = array())
	{
		return $this->getByWhere($cond_row, $order_row);
	}


	public function getHelpId($cond_row = array(), $order_row = array())
	{
		return $this->getKeyByMultiCond($cond_row, $order_row);
	}
}

?>
<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Shop_DecorationAlbumModel extends Shop_DecorationAlbum
{
	/**
	 *
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getDecorationAlbumRow($table_primary_key_value = null, $key_row = null, $order_row = array())
	{
		return $this->get($table_primary_key_value, $key_row, $order_row);
	}


	public function DecorationAlbumWhere($cond_row = array(), $order_row = array())
	{
		return $this->getByWhere($cond_row, $order_row);
	}


	//多条件获取主键
	public function getDecorationAlbumId($cond_row = array(), $order_row = array())
	{
		return $this->getKeyByMultiCond($cond_row, $order_row);
	}
}

?>
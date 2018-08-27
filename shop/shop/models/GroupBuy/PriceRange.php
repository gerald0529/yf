<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/20
 * Time: 15:42
 */
class GroupBuy_PriceRange extends Yf_Model
{
	public $_cacheKeyPrefix  = 'c|groupbuy_price_range|';
	public $_cacheName       = 'groupbuy';
	public $_tableName       = 'groupbuy_price_range';
	public $_tablePrimaryKey = 'range_id';

	/**
	 * @param string $user User Object
	 * @var   string $db_id 指定需要连接的数据库Id
	 * @return void
	 */
	public function __construct(&$db_id = 'shop', &$user = null)
	{
		$this->_tableName = TABEL_PREFIX . $this->_tableName;
		$this->_cacheFlag = CHE;
		parent::__construct($db_id, $user);
	}


}
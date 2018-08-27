<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Seller_BaseModel extends Seller_Base
{
	public function __construct()
	{
		parent::__construct();
	}
	/**
	 * 读取分页列表
	 *
	 * @param  int $seller_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$rows = $this->listByWhere($cond_row, $order_row, $page, $rows);

		return $rows;
	}

	/**
	 * 多条件获取卖家账号信息
	 *
	 * @param  array $cond_row
	 * @return bool
	 * @access public
	 */
	public function getSellerInfoByWhere($cond_row)
	{
		return $this->getOneByWhere($cond_row);
	}


	/**
	 * 多条件查询卖家账号是否存在
	 *
	 * @param  array $cond_row
	 * @return bool
	 * @access public
	 */
	public function isSellerExist($cond_row)
	{
		$row = $this->getOneByWhere($cond_row);
		return empty($row)?false:true;
	}
}

?>
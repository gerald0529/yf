<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

class Voucher_PriceModel extends Voucher_Price
{

	/**
	 * 面额列表
	 *
	 * @param  int $voucher_price_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getVoucherPriceList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	public function getVoucherDenomination($cond_row = array(), $order_row = array('voucher_price' => 'ASC'))
	{
		return $this->getByWhere($cond_row, $order_row);
	}

	//根据面额获取对应的所需的积分点
	/**$cond_row 查询条件，array
	 * $field 查询的字段
	 * return string
	 * @return mixed
	 */
	public function getOneVoucherPriceByWhere($cond_row)
	{
		return $this->getOneByWhere($cond_row);
	}

    //根据面额ID获取代金券面额信息
    /*$voucher_price_id 查询条件，int
     * return array
     */
	public function getVoucherPriceByID($voucher_price_id)
	{
		return $this->getOne($voucher_price_id);
	}

}

?>
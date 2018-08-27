<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Order_GoodsChainCodeModel extends Order_GoodsChainCode
{
    const CHAIN_CODE_USE = 1;            //自提码已使用
    const CHAIN_CODE_NOT_USE = 0;            //自提码未使用
    const CHAIN_CODE_FROZEN = 2;            //自提码冻结

	/**
	 * 读取分页列表
	 *
	 * @param  int $chain_code_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getGoodsChainCodeList($cond_row = array(), $order_row = array(), $page=1, $rows=100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

    /**
     * @param $orderId
     * @return boolean
     * 判断当前订单是否为门店自提订单
     */
	public function isChainOrder($orderId)
    {
        $order = $this->getOneByWhere([
            'order_id'=> $orderId
        ]);

        return empty($order)
            ? false
            : true;
    }
}
?>
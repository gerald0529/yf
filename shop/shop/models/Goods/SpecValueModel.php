<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_SpecValueModel extends Goods_SpecValue
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $spec_value_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getSpecValueList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	/**
	 * 根据规格读取规格值
	 *
	 * @param  int $spec_id 规格id
	 * @param  int $cat_id  分类id
	 * @param  array $order_row  排序
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getSpecValueBySpecId($spec_id, $cat_id, $order_row = array('spec_value_displayorder' => 'desc'))
	{
		return $this->getByWhere(array(
									 'spec_id' => $spec_id,
									 'cat_id' => $cat_id
								 ), $order_row);
	}
	
	/**
	 * 取出颜色的规格值
	 *
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getSpecValueByColor()
	{
		$goodsSpecModel = new Goods_SpecModel();
		$spec_base      = $goodsSpecModel->getByWhere(array('spec_readonly' => Goods_SpecModel::COLOR));

		$spec_base = pos($spec_base);
		$condi['spec_id'] = $spec_base['spec_id'];

		$spec_value_base = $this->getByWhere($condi);

		$spec_value_ids = array_column($spec_value_base, 'spec_value_id');

		return $spec_value_ids;
	}

	/**
	 * 按照店铺读取对应规格值
	 *
	 * @param array $cond_row
	 * @param array $order_row
	 * @return array
	 */
	public function getSpecValueByShop($cond_row = array(), $order_row = array('spec_value_displayorder' => 'ASC'))
	{
		$cond_row['shop_id'] = Perm::$shopId;
		return $this->getByWhere($cond_row, $order_row);
	}
}

?>
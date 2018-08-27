<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_TypeBrandModel extends Goods_TypeBrand
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $type_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getTypeBrandList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	public function getBrandType($cond_row = array(), $order_row = array())
	{
		$brand = $this->getByWhere($cond_row);

		if (!$brand)
		{
			return array();
		}

		$type_id = array_column($brand, 'type_id');

		/*$Goods_TypeModel = new Goods_TypeModel();
		$type_cond_row['type_id:IN'] = $type_id;
		$type_row = $Goods_TypeModel->getByWhere($type_cond_row);

		if(!$type_row)
		{
			return array();
		}*/

		$Goods_CatModel             = new Goods_CatModel();
		$cat_cond_row['type_id:IN'] = $type_id;
		$cat_row                    = $Goods_CatModel->getByWhere($cat_cond_row);
		if (!$cat_row)
		{
			return array();
		}

		return $cat_row;
	}

}

?>
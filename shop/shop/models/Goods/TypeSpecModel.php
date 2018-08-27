<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_TypeSpecModel extends Goods_TypeSpec
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $type_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getTypeSpecList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}


	/**
	 * 根据分类读取规格
	 *
	 * @param  int $type_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getTypeSpecByTypeId($type_id)
	{
		$cond_row = array('type_id' => $type_id);

		return $this->getByWhere($cond_row);
	}

	/**
	 * 根据分类读取规格
	 *
	 * @param  int $cat_id
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getTypeSpecByCatId($cat_id)
	{
		$Goods_CatModel = new Goods_CatModel();
		$cat_row        = $Goods_CatModel->getOne($cat_id);
		$spec_rows      = array();

		if ($cat_row)
		{
			$cond_row = array('type_id' => $cat_row['type_id']);

			$type_spec_rows = $this->getByWhere($cond_row);
			$spec_id_row    = array_column($type_spec_rows, 'spec_id');

			if ($spec_id_row)
			{
				$Goods_SpecModel = new Goods_SpecModel();
				$spec_rows       = $Goods_SpecModel->getSpec($spec_id_row);
			}

			foreach ($spec_rows as $key => $val)
			{
				$spec_rows[$key]['cat_id'] = $cat_id;
			}
		}

		return array_values($spec_rows);
	}
}

?>
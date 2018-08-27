<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_BrandModel extends Goods_Brand
{
	const   RECOMMEND_TRUE  = 1;   //推荐
	const   RECOMMEND_FALSE = 0;  //不推荐
	const   ENABLE_TRUE     = 1;    //启用
	const   ENABLE_FALSE    = 0;   //不启用
	const   SHOW_TYPE_WORD  = 1;  // 文字展示
	const   SHOW_TYPE_PIC   = 0;  // 图片展示

	public static $recommend_content = array(
		'0' => '否',
		'1' => '是'
	);

	public static $show_content = array(
		'0' => '图片',
		'1' => '文字'
	);

	/**
	 * 读取分页列表
	 *
	 * @param  int $brand_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBrandList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	/**
	 * 读取品牌列表
	 *
	 * @param  int $brand_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBrandCatlist($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$brand = $this->listByWhere($cond_row, $order_row, $page, $rows);
		//查询出根据cat_id，查询出cat_name
		$Goods_CatModel = new Goods_CatModel();
		foreach ($brand['items'] as $key => $value)
		{
			if (!empty($value['cat_id']))
			{
				$catlist = $Goods_CatModel->getOne($value['cat_id']);
				if ($catlist)
				{
					$brand['items'][$key]["catname"] = $catlist['cat_name'];
				}
				else
				{
					$brand['items'][$key]["catname"] = "";
				}
			}
			else
			{
				$brand['items'][$key]["catname"] = "";
			}
		}
		return $brand;
	}

    /*
     * 推荐品牌
     * @reurn array $recommend_cat_row 查询结果
     */
	public function getRecommendBrandList()
	{
		$data = $this->getByWhere(array('brand_enable' => $this::ENABLE_TRUE));

		$recommend_cat_row = array();

		$Goods_CatModel = new Goods_CatModel();

		foreach ($data as $key => $val)
		{
			if (array_key_exists($val['cat_id'], $recommend_cat_row))
			{

				$recommend_cat_row[$val['cat_id']]['brand'][] = $val;
			}
			else
			{
				//查找分类名称
				$cat_name                                     = $Goods_CatModel->getNameByCatid($val['cat_id']);
				$recommend_cat_row[$val['cat_id']]['catname'] = $cat_name;
				$recommend_cat_row[$val['cat_id']]['brand'][] = $val;
			}
		}
		return $recommend_cat_row;
	}

    /*
     * 显示品牌列表
     * @return array $re 查询结果
     */
	public function listRecommonBrand()
	{
		$re             = array();
		$Goods_CatModel = new Goods_CatModel();
		$data           = $Goods_CatModel->getByWhere(array('cat_parent_id' => '0'));
		$cat_ids        = array_column($data, 'cat_id');
		if (!empty($cat_ids))
		{
			$data_brand = $this->getBrandByCat($cat_ids);
			foreach ($cat_ids as $key => $val)
			{
				if (!empty($data_brand[$val]))
				{
					$re[$key]['cat_name'] = $data[$val]['cat_name'];
					$re[$key]['cat_id']   = $data[$val]['cat_id'];
					$re[$key]['sub']      = $data_brand[$val];
				}
			}
		}
		return $re;
	}


    /*
     * 根据分类id 获取商品品牌
     * @param array $cat_ids 分类id
     * @return array $data_re 查询数据
     */
	public function getBrandByCat($cat_ids)
	{
		$data_re          = array();
		$Goods_CatModel   = new Goods_CatModel();
		$Goods_BrandModel = new Goods_BrandModel();
		$data             = $Goods_CatModel->getByWhere(array('cat_parent_id:in' => $cat_ids));
		$cat_id_rows      = array_column($data, 'cat_id');
		/*if(!empty($data))
		{
			foreach($data as $key=>$value)
			{
				$cat_id = $value['cat_id'];
				$Goods_BrandModel->sql->setLimit(0,8);
				$data_brand = $Goods_BrandModel->getByWhere(array('cat_id'=>$cat_id, 'brand_enable'=>$Goods_BrandModel::ENABLE_TRUE,'brand_recommend'=>$Goods_BrandModel::RECOMMEND_TRUE),array('brand_displayorder'=>'asc'));
				if(!empty($data_brand))
				{
					$data_re[$value['cat_parent_id']][$key]['cat_name'] = $value['cat_name'];
					$data_re[$value['cat_parent_id']][$key]['cat_pic']  = $value['cat_pic'];
					$data_re[$value['cat_parent_id']][$key]['cat_id']   = $value['cat_id'];
					$data_re[$value['cat_parent_id']][$key]['brand']    = $data_brand;
				}
			}
		}*/
		if (!empty($cat_id_rows))
		{
			$data_brand = $Goods_BrandModel->getByWhere(array(
															'cat_id:in' => $cat_id_rows,
															'brand_enable' => $Goods_BrandModel::ENABLE_TRUE,
															'brand_recommend' => $Goods_BrandModel::RECOMMEND_TRUE
														), array('brand_displayorder' => 'asc'));
			if (!empty($data_brand))
			{
				foreach ($data as $key => $value)
				{
					$cat_id        = $value['cat_id'];
					$cat_parent_id = $value['cat_parent_id'];
					foreach ($data_brand as $keys => $values)
					{
						if ($cat_id == $values['cat_id'])
						{
							$data_re[$cat_parent_id][$cat_id]['cat_name'] = $value['cat_name'];
							$data_re[$cat_parent_id][$cat_id]['cat_pic']  = $value['cat_pic'];
							$data_re[$cat_parent_id][$cat_id]['cat_id']   = $cat_id;
							$data_re[$cat_parent_id][$cat_id]['brand'][]  = $values;
						}
					}
				}
			}
		}
		return $data_re;
	}

    /*
     * 获取相同分类下的品牌
     * @param int $brand_id 品牌id
     * @return array $re 查询结果
     */
	public function getCatBrands($brand_id)
	{
		$re               = array();
		$Goods_BrandModel = new Goods_BrandModel();
		if (!empty($brand_id))
		{
			$data_brand = $Goods_BrandModel->getOne($brand_id);
			if (!empty($data_brand))
			{
				$cat_id = $data_brand['cat_id'];
				$re     = $Goods_BrandModel->getByWhere(array('cat_id' => $cat_id));
			}
		}
		return $re;
	}


	/*
	 * 品牌的排行
	 * @param array 所有品牌列表
	 * @return array 查询结果
	 */
	public function getRankRows($data)
	{
		$re               = array();
		$Goods_CatModel   = new Goods_CatModel();
		$Goods_BrandModel = new Goods_BrandModel();

		if (!empty($data))
		{
			foreach ($data as $key => $value)
			{
				$cat_id      = $value['cat_id'];
				$cat_id_rows = $Goods_CatModel->getCatChildId($cat_id);
				$Goods_BrandModel->sql->setLimit(0, 10);
				$re[$key] = array_values($Goods_BrandModel->getByWhere(array('cat_id:in' => $cat_id_rows,'brand_enable'=>$Goods_BrandModel::ENABLE_TRUE), array('brand_collect' => 'desc')));
			}
		}
		return $re;
	}

	/*
	 * 获取分类下的所有品牌
	 * @param int $cat_id
	 * @return array 查询结果
	 */

	public function getBrandListByCatId($cat_id = 0)
	{
		$condi_brand['cat_id'] = $cat_id;
		$condi_brand['brand_enable'] = Goods_BrandModel::ENABLE_TRUE;

		return $this->getByWhere( $condi_brand );
	}

	public function getSubQuantity($cond_row)
	{
		return $this->getNum($cond_row);
	}

	//获取所有可用品牌
	public function getBrandAll()
	{
		$sql = "
					SELECT
						SQL_CALC_FOUND_ROWS *
					FROM
						" . TABEL_PREFIX . "goods_brand where brand_enable=1
					";
		$rs = $this->sql->getAll($sql);

		return $rs;
	}
}

?>
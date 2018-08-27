<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_TypeModel extends Goods_Type
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $type_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getTypeList($cond_row = array(), $order_row = array('type_displayorder' => 'ASC'), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	/*
	 * 获取分类详细信息
	 *
	 *
	 * */
	public function getTypeInfo($type_id = 0, $property_is_search=1)
	{
//		$Goods_TypeModel      = new Goods_TypeModel();        //商品类型表
		$Goods_TypeBrandModel     = new Goods_TypeBrandModel();   //商品类型和品牌对应表
		$Goods_TypeSpecModel      = new Goods_TypeSpecModel();    //商品类型和规格对应表
		$Goods_PropertyModel      = new Goods_PropertyModel();    //商品属性表
		$Goods_PropertyValueModel = new Goods_PropertyValueModel(); // 商品属性值表

		$type_rows  = pos($this->getType($type_id));
		$data['id'] = $type_id;
		if (!empty($type_rows))
		{
			$data['type_name']         = $type_rows['type_name'];  //类型名称
			$data['type_displayorder'] = $type_rows['type_displayorder'];  //类型排序
			$data['cat_id']            = $type_rows['cat_id']; //分类名称id
			$data['cat_name']          = $type_rows['cat_name'];   //分类名称

			//商品类型和品牌对应
			$goods_type_brand_rows = $Goods_TypeBrandModel->getByWhere(array('type_id' => $type_id));
			if (!empty($goods_type_brand_rows))
			{
				foreach ($goods_type_brand_rows as $key_brand => $value_brand)
				{
					$data['brand'][$value_brand['brand_id']] = $value_brand['brand_id'];
				}
			}
			else
			{
				$data['brand'] = '';
			}

			//商品类型和规格对应
			$goods_type_spec_rows = $Goods_TypeSpecModel->getByWhere(array('type_id' => $type_id));
			if (!empty($goods_type_spec_rows))
			{
				foreach ($goods_type_spec_rows as $key_spec => $value_spec)
				{
					$data['spec'][$value_spec['spec_id']] = $value_spec['spec_id'];
				}
			}
			else
			{
				$data['spec'] = '';
			}

			//商品属性值表
			if($property_is_search == 1){
				$goods_property_rows = $Goods_PropertyModel->getByWhere(array('type_id' => $type_id, 'property_is_search'=>1));
			}else{
				$goods_property_rows = $Goods_PropertyModel->getByWhere(array('type_id' => $type_id));
			}
			if (!empty($goods_property_rows))
			{
				foreach ($goods_property_rows as $key_property => $value_property)
				{
					//获取对应的属性值
					if ($value_property['property_format'] == 'select' || $value_property['property_format'] == 'checkbox')
					{
						$property_values                   = $Goods_PropertyValueModel->getByWhere(array('property_id' => $value_property['property_id']));
						$value_property['property_values'] = $property_values;
					}
					$data['property'][] = $value_property;
				}
			}
			else
			{
				$data['property'] = array();
			}
		}

		return $data;
	}

	/*
	 * getTypeInfo 扩展 => 发布商品
	 * */
	public function getTypeInfoByPublishGoods($cat_id = 0,$shop_id=0)
	{
		$goodsCatModel       = new Goods_CatModel();
		$goodsBrandModel     = new Goods_BrandModel();
		$goodsSpecModel      = new Goods_SpecModel();
		$goodsSpecValueModel = new Goods_SpecValueModel();


		$bottom_cat_name = $goodsCatModel->getNameByCatid($cat_id);
		$cat_parent      = $goodsCatModel->getCatParent($cat_id);
		$cat_name_list   = '';
		if (!empty($cat_parent))
		{
			foreach ($cat_parent as $key => $val)
			{
				$cat_name_list .= $val['cat_name'] . ' > ';
			}
			$bottom_cat_name = $cat_name_list . $bottom_cat_name;
		}

		$cat_data = $goodsCatModel->getCat($cat_id);
		$cat_data = current($cat_data);
		$type_id  = $cat_data['type_id'];

		/*
		 * 2016-06-28 商品可以没有绑定类型
		 * */
		if (empty($type_id))
		{
		}
		else
		{
			$data = $this->getTypeInfo($type_id);

			//过滤垃圾property
			if ( !empty($data['property']) )
			{
				foreach ( $data['property'] as $pro_k => $property_data )
				{
					foreach ($property_data['property_values'] as $pro_v_k => $pro_v_d)
					{
						if ( empty($pro_v_d['property_value_name']) )
						{
							unset($data['property'][$pro_k]['property_values'][$pro_v_k]);
						}
					}

					if ( empty($property_data['property_values']) )
					{
						unset($data['property'][$pro_k]);
					}
				}
			}
			
			//获取关联品牌
			if (!empty($data['brand']))
			{
				$brand_keys    = array_keys($data['brand']);
				$brand_data    = $goodsBrandModel->getBrand($brand_keys);
				$data['brand'] = $brand_data;
			}

			//获取关联规格
			if (!empty($data['spec']))
			{
				$spec_keys = array_keys($data['spec']);
				$spec_data = $goodsSpecModel->getSpec($spec_keys);

				//过滤出属于该店铺的规格值
				//color_id
				foreach ( $spec_data as $spec_id_k => $spec_data_v )
				{
					if ( $spec_data_v['spec_readonly'] == Goods_SpecModel::COLOR )
					{
						$spec_data[$spec_id_k]['spec_img'] = 't';
					}
					else
					{
						$spec_data[$spec_id_k]['spec_img'] = 'f';
					}
				}

				$spec_ids = array_column($spec_data, 'spec_id');
				$shop_id = $shop_id?$shop_id:Perm::$shopId;
				$condi_spec_val['shop_id'] = $shop_id;
				$condi_spec_val['spec_id:IN'] = $spec_ids;

				$spec_val_list = $goodsSpecValueModel->getByWhere($condi_spec_val);
				foreach ($spec_data as $key => $val)
				{
					$spec_values = array();
					foreach ( $spec_val_list as $spec_val_id => $spec_val_data )
					{
						if ( $val['spec_id'] == $spec_val_data['spec_id'] )
						{
							$spec_values[] = $spec_val_data; unset($spec_val_list[$spec_val_id]);
							/*$spec_values                    = $goodsSpecValueModel->getByWhere(array('spec_id' => $val['spec_id'], 'shop_id' => Perm::$shopId));
							$spec_data[$key]['spec_values'] = $spec_values;*/
						}
					}
					$spec_data[$key]['spec_values'] = $spec_values;
				}



				$data['spec'] = array_values($spec_data);
			}
		}


		//分类链名字
		$data['cat_directory'] = $bottom_cat_name;
		//查看是否为虚拟类型
		$data['cat_is_virtual'] = $cat_data['cat_is_virtual'];

		//获取店铺分类

		$shopGoodCatModel       = new Shop_GoodCatModel();
		$data['goods_cat_list'] = $shopGoodCatModel->getShopCatList();

		//运费地区
		$baseDistrictModel = new Base_DistrictModel();
		$district_base     = $baseDistrictModel->getDistrictTree(0, false);
		$data['district']  = pos($district_base);

		//取出关联版式
		$goodsFormatModel        = new Goods_FormatModel();
		$condi_format['shop_id'] = Perm::$shopId;
		$format_list             = $goodsFormatModel->getByWhere($condi_format);

		if (!empty($format_list))
		{
			foreach ($format_list as $key => $val)
			{
				if ($val['position'] == Goods_FormatModel::FORMAT_POSITION_TOP)
				{
					$format_top[] = $val;
				}
				else
				{
					$format_bottom[] = $val;
				}

			}
			if (!empty($format_top))
			{
				$data['format_top'] = $format_top;
			}
			if (!empty($format_bottom))
			{
				$data['format_bottom'] = $format_bottom;
			}
		}

		return $data;
	}

	public function getTypeBrand($cond_row)
	{
		$type = $this->getOneByWhere($cond_row);
		if (!$type)
		{
			return array();
		}
		$type_id = $type['type_id'];
		//根据type查找相关的品牌
		$Goods_TypeBrandMode            = new Goods_TypeBrandModel();
		$type_brand_cond_row['type_id'] = $type_id;
		$type_brand                     = $Goods_TypeBrandMode->getByWhere($type_brand_cond_row);

		if (!$type_brand)
		{
			return array();
		}

		$brand_id = array_column($type_brand, 'brand_id');

		$Goods_BrandModel              = new Goods_BrandModel();
		$brand_cond_row['brand_id:IN'] = $brand_id;
		$brand                         = $Goods_BrandModel->getByWhere($brand_cond_row);
		if (!$brand)
		{
			return array();
		}

		return $brand;
	}

}

?>
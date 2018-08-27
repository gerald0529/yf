<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Api接口, 让webpos调用
 *
 *
 * @category   Game
 * @package    User
 * @author
 * @copyright
 * @version    1.0
 * @todo
 */
class WebPosApi_GoodsCtl extends WebPosApi_Controller
{
    /**
     * Constructor
     *
     * @param  string $ctl 控制器目录
     * @param  string $met 控制器方法
     * @param  string $typ 返回数据类型
     * @access public
     */
    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);
        $this->shopInfoModel = new Shop_BaseModel();
        $this->goodsBaseModel = new Goods_BaseModel();
        $this->goodsCatModel = new Goods_CatModel();
        $this->goodsCommonModel = new Goods_CommonModel();
        $this->Chain_GoodsModel = new Chain_GoodsModel();
        $this->Points_GoodsModel = new Points_GoodsModel();
    }
	
    /**
     * 通过店家用户id获取店铺商品
     * @param int $user_id 用户id
     * @access public
     */
    public function getShopGoodsByUserId()
    {
        $user_id = request_int('user_id');//店员或店家用户id
        $chain_id = request_int('chain_id');//实体店id
        $data = [];
        $shop_info = $this->shopInfoModel->getOneByWhere(['user_id'=>$user_id]);
        //如果没有店铺信息，说明是店员登录，返回
        if($shop_info)
        {
            $status = 200;
            $cond_rows['shop_id'] = $shop_info['shop_id'];
            $data = $this->goodsBaseModel->getByWhere($cond_rows);
            if($data)
            {
                $data = array_values($data);
                foreach($data as $key=>$value)
                {
                    $goods_cat = $this->goodsCatModel->getOneByWhere(['cat_id'=>$value['cat_id']]);
                    $goods_common = $this->goodsCommonModel->getOneByWhere(['common_id'=>$value['common_id']]);
                    $chain_goods = $this->Chain_GoodsModel->getByWhere(['shop_id'=>$shop_info['shop_id']]);
                    $data[$key]['cat_name'] = $goods_cat['cat_name'];
                    $data[$key]['common_cubage'] = $goods_common['common_cubage'];
                    $data[$key]['goods_stock'] = 0;
                    if ($chain_goods ) {

	                    foreach ($chain_goods as $k => $v) {
	                    	if ($v['goods_id'] == $value['goods_id'] && $v['chain_id'] == $chain_id) {
	                    		
	                    		$data[$key]['goods_stock'] += $v['goods_stock'];

	                    	}
	                    }
                    	
                    }
                    foreach($value['goods_spec'] as $k=>$v)
                    {
                        $data[$key]['goods_spec'] = implode(',', $v);
                    }
                }
                $msg = 'success';
            }
            else
            {
                $msg = 'success:店铺还没有商品';
            }
        }
        else
        {
            $msg = 'failure:您不是店家';
            $status = 250;
        }

        $this->data->addBody(-140, $data, $msg, $status);
    }
	
	
	/**
	 * 查询分类品牌和分类关联属性
	 * @return array
	 */
	public function getBrandAndProperty ()
	{
		$cat_id = request_int('assistId');
		$brand_id = request_int('brand_id');
		$property_id = request_int('property_id');
		$property_value_id =request_int('property_value_id');
		$search_property = request_row('search_property');

		if ( !empty($cat_id) )
		{
			//存储查询条件
			$search_string = '';
			$property_value_ids = array();

			if ( !empty($property_id) )
			{
				$search_property[$property_id] = $property_value_id;
			}

			$goodsCatModel = new Goods_CatModel();
			$goodsTypeModel = new Goods_TypeModel();
			$goodsBrandModel = new Goods_BrandModel();

			$cata_data = $goodsCatModel->getCat($cat_id);

			$cata_data = pos($cata_data);
			$type_id = $cata_data['type_id'];

			if ($type_id)
			{
				$data = $goodsTypeModel->getTypeInfo($type_id);
			}

			if ( !empty($data['property']) )
			{
				//过滤类型为 text property
				foreach ($data['property'] as $key => $property_data)
				{
					if ( $property_data['property_format'] == 'text' || empty($property_data['property_format']) || empty($property_data['property_values']) )
					{
						unset($data['property'][$key]);
					}
					else
					{
						//拼接搜索条件
						if ( !empty($search_property[$property_data['property_id']]) )
						{
							$property_value_id = $search_property[$property_data['property_id']];

							$property_array = array();
							$property_array['property_name'] = $property_data['property_name'];
							$property_array['property_value_id'] = $property_value_id;
							$property_array['property_value_name'] = $property_data['property_values'][$property_value_id]['property_value_name'];
							$search_property[$property_data['property_id']] = $property_array;

							unset($data['property'][$key]);
						}
					}
				}

				$data['search_property'] = $search_property;

				if ( !empty($data['search_property']) )
				{
					foreach ($data['search_property'] as $property_id => $property_data)
					{
						$property_value_id = $property_data['property_value_id'];
						$string = "search_property[$property_id]=$property_value_id&";
						$search_string .= $string;

						$property_value_ids[] = $property_value_id;
					}
				}

				$data['search_string'] = $search_string;
			}

			if ( !empty($brand_id) )
			{
				unset($data['brand']);

				$data['search_string'] .= "brand_id=$brand_id&";

				$search_brand =  $goodsBrandModel->getBrand($brand_id);
				if ( !empty($search_brand) )
				{
					$data['search_brand'] = pos($search_brand);
				}

			}
			else if ( !empty($data['brand']) )
			{
				$brand_list = $goodsBrandModel->getBrand($data['brand']);

				$data['brand_list'] = $brand_list;
			}


			//过滤出所有符合筛选条件的common_id
			if ( !empty($property_value_ids) )
			{
				$condi_pro_index['property_value_id:IN'] = array(72, 82);
				$goodsPropertyIndexModel = new Goods_PropertyIndexModel();
				$property_index_list = $goodsPropertyIndexModel->getByWhere( $condi_pro_index );
				$common_ids = array_column($property_index_list, 'common_id');

				$data['common_ids'] = $common_ids;
			}


			//如果有下级分类，则取出展示
			$child_cat = $goodsCatModel->getChildCat($cat_id);
			if ( !empty($cat_id) )
			{
				$data['child_cat'] = $child_cat;
			}

			return $data;
		}
	}

	/*积分礼品列表*/
	public function getPointsGoodsList()
	{
		
		$data = $this->Points_GoodsModel->getByWhere([]);

		$this->data->addBody(-140, $data);
	}
}

?>
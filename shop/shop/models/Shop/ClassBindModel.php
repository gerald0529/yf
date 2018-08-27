<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Shop_ClassBindModel extends Shop_ClassBind
{
	public static $shop_class_bind_enable = array(
		"0" => '拒绝',
		"1" => "未审核",
		"2" => "已审核"
	);

	const PASS_VERIFY = 2;

	/**
	 * 读取店铺经营类目
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getClassBindrow($table_primary_key_value = null, $key_row = null, $order_row = array())
	{
		return $this->get($table_primary_key_value, $key_row, $order_row);
	}


	/**
	 * 根据多个条件取得
	 *
	 * @param  array $cond_row
	 * @return array $rows 信息
	 * @access public
	 */
	public function getClassBindWhere($cond_row = array(), $order_row = array())
	{
		return $this->getByWhere($cond_row, $order_row);
	}

	/**
	 * 获取分页信息
	 *
	 * @param  array $cond_row
	 * @return array $rows 信息
	 * @access public
	 */
	public function listClassBindWhere($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{

		return $this->listByWhere($cond_row, $order_row, $page, $rows);

	}

	//多条件获取主键
	public function getClassBindId($cond_row = array(), $order_row = array())
	{

		return $this->getKeyByMultiCond($cond_row, $order_row);

	}

	/**
	 * 根据店铺id 获取所有的经营类目名称以及分佣比例
	 *
	 * @param  array $cond_row
	 * @return array $rows 信息
	 * @access public
	 */
	public function getClassBindlist($shop_id = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data             = $this->listClassBindWhere($shop_id, $order_row, $page, $rows);
		$product_class    = array();
		$product_name_all = array();
		//循环得到商品分类id
		foreach ($data['items'] as $key => $value)
		{
			//根据商品分类ID查询出分类名
			$CatModel                                     = new Goods_CatModel();
			$product_class["shop_class_bind_id"][]        = $value["shop_class_bind_id"];
			$product_class["shop_class_bind_enablecha"][] = __(self::$shop_class_bind_enable[$value['shop_class_bind_enable']]);
			$product_class["commission_rate"][]           = $value["commission_rate"];
			$product_class["shop_class_bind_enable"][]    = $value["shop_class_bind_enable"];
			$product_class["product_parent_name"][]       = $CatModel->getCatParent($value['product_class_id']);
			$product_class["shop_class_bind_desc"][]      = $value["shop_class_bind_desc"];
			$product_name[]                               = $CatModel->getOne($value['product_class_id']);
		}
		//循环父类经营类目把子类插进去
		if (!empty($product_class["product_parent_name"]))
		{
			foreach ($product_class["product_parent_name"] as $key => $value)
			{

				$product_class["product_parent_name"][$key][] = $product_name[$key];
			}
		}
		$data['items'] = $product_class;
		return $data;

	}

	public function getSubQuantity($cond_row)
	{
		return $this->getNum($cond_row);
	}
    
    /**
     * 获取店铺的分类佣金
     * @param type $shop_id
     * @param type $cate_id
     * @return int
     */
    public function getShopCateCommission($shop_id,$cate_id){
        $goods_cat = $this->getByWhere(array('shop_id'=>$shop_id,'product_class_id'=>$cate_id));

        if($goods_cat)
        {
            $goods_cat = current($goods_cat);
            $cat_commission = $goods_cat['commission_rate'];
        }
        else
        {
            $Goods_CatModel = new Goods_CatModel();
            $goods_cat = $Goods_CatModel->getOne($cate_id);
            if ($goods_cat)
            {
                $cat_commission = $goods_cat['cat_commission'];
            }
            else
            {
                $cat_commission = 0;
            }
        }
        return $cat_commission;
    }
}

?>
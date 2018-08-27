<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}


/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_CatModel extends Goods_Cat
{
	public $treeRows   = array();
	public $treeAllKey = null;
	public $catListAll = null;

	/**
	 * 读取分页列表
	 *
	 * @param  int $cat_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCatList($cond_row = array(), $order_row = array('cat_displayorder' => 'ASC'), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	/**
	 * 查询所有的首页导航分类
	 *
	 * @param  int $cat_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCatListAll()
	{
		//设置cache
		$Cache = Yf_Cache::create('base');
        $data = $Cache->get($this->catListAll);
		if (!$data){
			$data              = $this->getCatTreeData(0, false, 0);
			$Goods_CatNavModel = new Goods_CatNavModel();
			$Goods_BrandModel  = new Goods_BrandModel();
			//循环的到下面的分类导航
			foreach ($data as $key => $value)
			{
				$row     = array("goods_cat_id" => $value['cat_id']);
				$cat_nav = $Goods_CatNavModel->getOneByWhere($row);
				if (!empty($cat_nav['goods_cat_nav_brand']))
				{
					$brand_id_list     = explode(",", $cat_nav['goods_cat_nav_brand']);
					$data[$key]['adv'] = explode(",", $cat_nav['goods_cat_nav_adv']);

                    $brand_lsit = $Goods_BrandModel->getByWhere(array('brand_id:IN'=>$brand_id_list),array('brand_displayorder'=>'desc'));
                    $data[$key]['brand'] = array_values($brand_lsit);

                }
				$data[$key]['cat_nav'] = $cat_nav;
			}

			$Cache->save($data, $this->catListAll);
		}


		return $data;
	}


	/**
	 * 根据分类父类id读取子类信息,
	 *
	 * @param  int $cat_parent_id 父id
	 * @param  bool $recursive 是否子类信息
	 * @param  int $level 当前层级
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCatTree($cat_parent_id = 0, $recursive = true, $level = 0)
	{
		//设置cache
		$Cache = Yf_Cache::create('base');

		if ($data_rows = $Cache->get($this->treeAllKey))
		{

		}
		else
		{
			$data_rows = $this->getCatTreeData($cat_parent_id, $recursive, $level);

			$Cache->save($data_rows, $this->treeAllKey);
		}

//		$this->filterCatTreeData($data_rows);

		$data['items'] = array_values($data_rows);

		return $data;
	}


    /*
     * 根据分类id 获取分组名称
     * @param int $cat_id 分组id
     * @return string $data 分组名称
     */
	public function getNameByCatid($cat_id = null)
	{
		if ($cat_id)
		{

			$name = $this->getOne($cat_id);
			if ($name)
			{
				$data = $name['cat_name'];
			}
			else
			{
				$data = '未分组';
			}
		}
		else
		{
			$data = '未分组';
		}
		return $data;
	}

	/*
	 * 获取分类导航显示数据
	 * @param   int $id 分类Id
	 * @param   array $rows 查询结果
	 * @param   bool  $tag 子分类为空是否显示
	 * @return  array $re  分类导航数据
	 */
	public function getCatDisplayRows($id = 0, $rows = null, $tag = false)
	{
		$Goods_CatModel = new Goods_CatModel();
		$re             = array();
		$data           = $Goods_CatModel->getByWhere(array('cat_parent_id' => $id),array('cat_displayorder'=>'desc'));
		if (!empty($data))
		{
			foreach ($data as $key => $value)
			{
				$sub          = $this->getCatDisplayRows($value['id'], $rows, $tag);
				$value['sub'] = $sub;
				if (empty($sub) && in_array($value['id'], $rows))
				{
					$re[$value['id']]['cat_id']   = $value['cat_id'];
					$re[$value['id']]['cat_name'] = $value['cat_name'];
				}
				elseif (!empty($sub))
				{
					$re[$value['id']]['cat_id']   = $value['cat_id'];
					$re[$value['id']]['cat_name'] = $value['cat_name'];
					$re[$value['id']]['sub']      = $sub;
				}
				elseif (empty($sub) && $tag)
				{
					$re[$value['id']]['cat_id']   = $value['cat_id'];
					$re[$value['id']]['cat_name'] = $value['cat_name'];
				}
			}
		}
		return $re;
	}

	//新增分类后，获取返回数据
	/*public function getReturnData($cat_id)
	{
		$re             = array();
		$Goods_CatModel = new Goods_CatModel();
		$data           = $Goods_CatModel->getCatTreeData();
		if (!empty($cat_id) && !empty($data))
		{
			foreach ($cat_id as $k => $v)
			{
				foreach ($data as $key => $value)
				{
					if ($v == $value['id'])
					{
						$re[] = $value;
					}
				}
			}
		}
		return $re;
	}*/

	/*  前台全部商品分类
     * @return array $data_re 分类数据
	 * */
	public function getGoodsCatList()
	{
		$Goods_CatModel = new Goods_CatModel();
		$data_re        = array();
		//取所有一级分类
		$data_cat = $Goods_CatModel->getByWhere(array('cat_parent_id' => 0));
		if (!empty($data_cat))
		{
			foreach ($data_cat as $key => $value)
			{
				$cat_id = $value['cat_id'];
				//一级分类下的热卖商品
				$data_re[$key]['cat_name'] = $value['cat_name'];
				$data_re[$key]['cat_id']   = $value['cat_id'];
				$img                       = $this->getHotByCatId($cat_id);
				$cat                       = $this->getChildCat($cat_id);
				$data_re[$key]['img']      = $img;
				$data_re[$key]['cat']      = $cat;
			}
		}
		return $data_re;
	}
    /*
     * 根据分类id 获取热销商品
     * @param int $cat_id 分类id
     * @return array $re 热销商品
     */
	public function getHotByCatId($cat_id)
	{
		$re                = array();
		$Goods_CommonModel = new Goods_CommonModel();

		$data = $Goods_CommonModel->getCommonList(array('cat_id' => $cat_id,'common_state'=>$Goods_CommonModel::GOODS_STATE_NORMAL), array('common_salenum' => 'desc'), 1, 3);
		$re   = $Goods_CommonModel->getRecommonRow($data);
		return $re;
	}

    /*
     * 获取自分类id
     * @param int $cat_id 商品分类
     * @return array $data_re 查询数据
     */
	public function getChildCat($cat_id)
	{
		$data_re        = array();
		$Goods_CatModel = new Goods_CatModel();
		$data           = $Goods_CatModel->getByWhere(array('cat_parent_id' => $cat_id));

		if (!empty($data))
		{
			foreach ($data as $key => $value)
			{
				$data_re[$key]['cat_id']   = $value['cat_id'];
				$data_re[$key]['cat_name'] = $value['cat_name'];
				$child                     = $Goods_CatModel->getByWhere(array('cat_parent_id' => $value['cat_id']));
				$data_re[$key]['child']    = array_values($child);
			}
		}

		return array_values($data_re);
	}

	/**
	 * 根据分类读取规格信息
	 * @param $cat_rows array
	 * @param $shop_id int 因为每个店铺规格值可以自定义
	 * @return array
	 * 返回格式 [ '笔记本'=> [ cat_id=> 1,
	 * 							spec=> [ '颜色'=> [spec_id=> 2, spec_value=> [白色=> 3, 黑色=> 4] ]
	 * 						]
	 * 			]
	 */
	public function getSpecAndSpecValue ($cat_rows, $shop_id)
	{
		$type_ids = array_filter(array_column($cat_rows, 'type_id', 'cat_id'));

		if (empty($type_ids)) { //有可能出现所选分类没有绑定商品类型
			return [];
		}

		$goodsTypeSpecModel = new Goods_TypeSpecModel;
		$goods_type_spec_rows = $goodsTypeSpecModel->getByWhere(['type_id:IN'=> $type_ids]);

		if (empty($goods_type_spec_rows)) { //有可能出现商品类型没有绑定规格
			return [];
		}

		$spec_ids = [];
		$use_spec_type_ids = []; //使用规格的商品类型 [type_id=> [spec_id]]

		foreach ($goods_type_spec_rows as $goods_type_spec_data) {
			$spec_id = $goods_type_spec_data['spec_id'];
			$type_id = $goods_type_spec_data['type_id'];

			$spec_ids[$spec_id] = $spec_id;
			$use_spec_type_ids[$type_id][] = $spec_id;
		}


		$goodsSpecModel = new Goods_SpecModel;
		$goodsSpecValueModel = new Goods_SpecValueModel;

		$goods_spec_rows = $goodsSpecModel->getByWhere(['spec_id:IN'=> $spec_ids]);
		$goods_spec_value_rows = $goodsSpecValueModel->getByWhere(['spec_id:IN'=> $spec_ids, 'shop_id'=> $shop_id]);

		//规格值按规格分组

		$specId_specValue_map = [];

		foreach ($goods_spec_rows as $spec_id=> $goods_spec_data) {
			foreach ($goods_spec_value_rows as $goods_spec_value_data) {
				if ($spec_id == $goods_spec_value_data['spec_id']) {
					$spec_value_id = $goods_spec_value_data['spec_value_id'];
					$spec_value_name = $goods_spec_value_data['spec_value_name'];
					$specId_specValue_map[$spec_id][$spec_value_name] = $spec_value_id;
				}
			}
		}

		$result_rows = []; //返回数据

		//有可能所绑定的商品分类没有启用规格
		foreach ($cat_rows as $cat_id=> $cat_data) {
			if ( !empty($cat_data['type_id']) &&
				isset($use_spec_type_ids[$cat_data['type_id']])
			) {
				$cat_name = $cat_data['cat_name'];
				$result_rows[$cat_name]['cat_id'] = $cat_id;

				$spec_ids = $use_spec_type_ids[$cat_data['type_id']];

				foreach ($spec_ids as $spec_id) {
					$spec_name = $goods_spec_rows[$spec_id]['spec_name'];
					$spec_value_rows = $specId_specValue_map[$spec_id];

					$result_rows[$cat_name]['spec'][$spec_name] = ['spec_id'=> $spec_id, 'spec_value'=> $spec_value_rows];
				}
			}
		}
		return $result_rows;
	}

    /**
     * 获取佣金
     * @param type $price
     * @return type
     */
    public function getCatCommission($price,$cat_id = 0){
        $goods_cat = $this->getOne($cat_id);
        $cat_commission = $goods_cat ? $goods_cat['cat_commission'] : 0;
        $commission = number_format(($price * $cat_commission / 100), 2, '.', '');
        return $commission;
    }
    
    /**
     * 清除缓存
     * @param type $type
     * @return boolean
     */
    public function removeAllCatCache($type = 'all'){
        $res = $this->removeCatCache($type);
        return $res;
    }
    
    /**
     * 获取一级分类目录
     * 拼团使用
     * 数据量有点大，只取需要的数据
     */
    public function getOneCatList(){
        $data = $this->getCatListAll();
        $result = array();
        foreach ($data as $val){
            $cate = array();
            if($val['cat_parent_id'] == 0 ){
                $cate['cat_id'] = $val['cat_id'];
                $cate['cat_name'] =$val['cat_name'];
				if(count($result)<22){
					$result[] = $cate;
				}

            }
        }
        return $result;
    }
}

?>
<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Goods_CatCtl extends Api_Controller
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}

	/**
	 * 设置商城API网址及key - 后台独立使用
	 *
	 * @access public
	 */
	public function cat()
	{
		$Goods_CatModel = new Goods_CatModel();
		$data           = $Goods_CatModel->getCatTree();


		$this->data->addBody(-140, $data);
	}

	/**
	 * 设置商城API网址及key - 后台独立使用
	 *
	 * @access public
	 */
	public function removeCat()
	{
		$shopClassBindModel = new Shop_ClassBindModel;
		$Goods_CatModel = new Goods_CatModel();

		$cat_id     = trim(request_string('cat_id'),',');
		$cat_id_row = explode(',', $cat_id);

		if ($cat_id_row)
		{
            //先查询分类下是否存在商品，存在则不允许删除
            $Goods_CommonModel = new Goods_CommonModel();
            if(is_array($cat_id_row) && count($cat_id_row)>1){
                $cond_row = array('cat_id:IN'=>$cat_id);
            }else{
                $cond_row = array('cat_id'=>$cat_id);
            }
            
            $goods_list_info = $Goods_CommonModel->getGoodsList($cond_row);
			$shop_class_bind_rows = $shopClassBindModel->getByWhere(['product_class_id:IN'=> $cat_id]);

            if(isset($goods_list_info['total']) && $goods_list_info['total']>0){
                $msg    = __('该分类下有商品存在');
                $status = 250;
            }elseif ($shop_class_bind_rows){
				$msg    = __('该分类下有店铺绑定');
				$status = 250;
			}else{
            
                $Goods_CatModel->sql->startTransactionDb();

                $flag = $Goods_CatModel->removeCat($cat_id_row);

                if ($flag && $Goods_CatModel->sql->commitDb())
                {
                    $msg    = __('success');
                    $status = 200;
                }
                else
                {
                    $Goods_CatModel->sql->rollBackDb();
                    $m      = $Goods_CatModel->msg->getMessages();
                    $msg    = $m ? $m[0] : __('failure');
                    $status = 250;
                }
            }
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, array('id' => $cat_id_row), $msg, $status);
	}


	/**
	 * 添加商品分类
	 *
	 * @access public
	 */
	public function addCat()
	{
		$Goods_CatModel   = new Goods_CatModel();
		$data['cat_name']         = request_string('cat_name'); //  分类名称
		$data['cat_parent_id']    = request_string('cat_parent_id'); // 父类
		$data['cat_pic']          = request_string('cat_pic'); // 分类图片
		$data['type_id']          = request_int('type_id'); // 类型id
		$data['cat_commission']   = request_int('cat_commission'); // 分佣比例
		$data['cat_is_wholesale'] = request_int('cat_is_wholesale'); //
		$data['cat_is_virtual']   = request_int('cat_is_virtual'); // 是否允许虚拟
		$data['cat_templates']    = request_string('cat_templates'); //
		$data['cat_displayorder'] = request_int('cat_displayorder'); // 排序
		$data['cat_level']        = request_int('cat_level'); // 分类级别
		$data['cat_show_type']    = request_string('cat_show_type'); // 1:SPU  2:颜色


		$cat_id = $Goods_CatModel->addGoodsCat($data, true);
		if ($cat_id)
		{

            if (Web_ConfigModel::value('Plugin_Fenxiao')) { //分销
                Yf_Plugin_Manager::getInstance()->trigger('updateCat', [
                    'cat_id' => $cat_id,
                    'user_id' => 0,
                    'shop_id' => 0,
                    'values' => [
                        request_string('c_first'),
                        request_string('c_second'),
                        request_string('c_third'),
                        request_string('c_lowest')
                    ],
                ]);
            }

			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['cat_id'] = $cat_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/*
	 * 编辑商品分类
	 */
	public function editGoodsCat()
	{
		$Goods_CatModel   = new Goods_CatModel();
		$edit_data        = array();
		$cat_id           = request_int('cat_id');
		$cat_name         = request_string('cat_name');
		$cat_is_virtual   = request_int('cat_is_virtual');
		$cat_show_type    = request_int('cat_show_type');
		$cat_commission   = request_int('cat_commission');
		$cat_displayorder = request_int('cat_displayorder');
		$cat_parent_id    = request_int('cat_parent_id');
		$cat_pic          = request_string('cat_pic');
		$type_id          = request_int('type_id');
		$t_gc_virtual     = request_int('t_gc_virtual');
		$t_commis_rate    = request_int('t_commis_rate');
		$t_show_type      = request_int('t_show_type');
		if (isset($t_gc_virtual) && $t_gc_virtual == 1)
		{
			$edit_data['cat_is_virtual'] = $cat_is_virtual;
		}
		if (isset($t_commis_rate) && $t_commis_rate == 1)
		{
			$edit_data['cat_commission'] = $cat_commission;
		}
		if (isset($t_show_type) && $t_show_type == 1)
		{
			$edit_data['cat_show_type'] = $cat_show_type;
		}

		$flag = $Goods_CatModel->editCat($cat_id, array(
			'cat_name' => $cat_name,
			'cat_is_virtual' => $cat_is_virtual,
			'cat_show_type' => $cat_show_type,
			'cat_commission' => $cat_commission,
			'cat_displayorder' => $cat_displayorder,
			'cat_parent_id' => $cat_parent_id,
			'cat_pic' => $cat_pic,
			'type_id' => $type_id
		));


		if ($flag !== false && !empty($edit_data))
		{
			//$this->editChild($cat_id, $editData);
			$child_cat_id_row = $Goods_CatModel->getCatChildId($cat_id);
			if (!empty($child_cat_id_row))
			{
				$Goods_CatModel->editCat($child_cat_id_row, $edit_data);
			}
		}
		if ($flag !== false)
		{
			$msg    = __('success');
			$status = 200;

            if (Web_ConfigModel::value('Plugin_Fenxiao')) { //分销
                Fenxiao::getInstance()->updateCat([
                    'cat_id' => $cat_id,
                    'user_id' => 0,
                    'shop_id' => 0,
                    'values' => [
                        request_string('c_first'),
                        request_string('c_second'),
                        request_string('c_third'),
                    ],
                ]);
            }
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		if (!empty($child_cat_id_row))
		{
			array_push($child_cat_id_row, $cat_id);
		}
		else
		{
			$child_cat_id_row = $cat_id;
		}
		$data = $Goods_CatModel->getCat($child_cat_id_row);
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/*
	 * 修改子类信息
	 */
	public function editChild($cat_id = null, $edit_data = array())
	{
		$Goods_CatModel = new Goods_CatModel();
		if ($cat_id)
		{
			$data_goods = '';
			$data_goods = $Goods_CatModel->getByWhere(array('cat_parent_id' => $cat_id));
			if (!empty($data_goods))
			{
				foreach ($data_goods as $key => $value)
				{
					$flag = $Goods_CatModel->editCat($value['cat_id'], $edit_data);
					$this->editChild($value['cat_id'], $edit_data);
				}
			}
		}
	}
	/*
	 * 新增分类
	 */
	public function add()
	{
		$cat_name     = $_REQUEST['cat_name'];
		$cat_name_row = preg_split('/\n/', $cat_name);
		foreach ($cat_name_row as $name)
		{
			$cat_name_rows[] = $name;
		}

		$Goods_CatModel   = new Goods_CatModel();
		$edit_data        = array();
		$cat_name         = request_string('cat_name');
		$cat_is_virtual   = request_int('cat_is_virtual');
		$cat_show_type    = request_int('cat_show_type');
		$cat_commission   = request_int('cat_commission');
		$cat_displayorder = request_int('cat_displayorder');
		$cat_parent_id    = request_int('cat_parent_id');
		$type_id          = request_int('type_id');
		$t_gc_virtual     = request_int('t_gc_virtual');
		$t_commis_rate    = request_int('t_commis_rate');
		$t_show_type      = request_int('t_show_type');
		if (isset($t_gc_virtual) && $t_gc_virtual == 1)
		{
			$edit_data['cat_is_virtual'] = $cat_is_virtual;
		}
		if (isset($t_commis_rate) && $t_commis_rate == 1)
		{
			$edit_data['cat_commission'] = $cat_commission;
		}
		if (isset($t_show_type) && $t_show_type == 1)
		{
			$edit_data['cat_show_type'] = $cat_show_type;
		}
		$edit_cat = array(
			'cat_is_virtual' => $cat_is_virtual,
			'cat_show_type' => $cat_show_type,
			'cat_commission' => $cat_commission,
			'cat_displayorder' => $cat_displayorder,
			'cat_parent_id' => $cat_parent_id,
			'type_id' => $type_id
		);
		$return   = array();
		if (!empty($cat_name_rows))
		{
			foreach ($cat_name_rows as $name_value)
			{
				$edit_cat['cat_name'] = $name_value;
				$cat_id               = $Goods_CatModel->addCat($edit_cat, true);

				if ($cat_id && !empty($edit_data))
				{

					$child_cat_id_row = $Goods_CatModel->getCatChildId($cat_id);
					if (!empty($child_cat_id_row))
					{
						$Goods_CatModel->editCat($child_cat_id_row, $edit_data);
					}
				}
                $return[] = $cat_id;
			}
		}

		if (!empty($return))
		{
            $Goods_CatModel->removeAllCatCache('all');
			$msg    = __('success');
			$status = 200;

			foreach($return as $k=>$v){
                if (Web_ConfigModel::value('Plugin_Fenxiao')) { //分销
                    Fenxiao::getInstance()->updateCat([
                        'cat_id' => $v,
                        'user_id' => 0,
                        'shop_id' => 0,
                        'values' => [
                            request_string('c_first'),
                            request_string('c_second'),
                            request_string('c_third'),
                        ],
                    ]);
                }
            }

		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		/*$return_data = $Goods_CatModel->getReturnData($return);*/
		$this->data->addBody(-140, $return, $msg, $status);
	}

	public function listCatNav()
	{
		$cat_id           = request_int('id');
		$Goods_CatModel   = new Goods_CatModel();
		$Goods_BrandModel = new Goods_BrandModel();

		$data_cat_rows = $Goods_CatModel->getCatDisplayRows($cat_id, array(), true);

		//推荐品牌
		$Goods_BrandModel = new Goods_BrandModel();
		$data_brand_rows  = $Goods_BrandModel->getRecommendBrandList();


		$data['cat']   = $data_cat_rows;
		$data['brand'] = $data_brand_rows;

		$this->data->addBody(-140, $data);
	}

	public function editNav()
	{
		$Goods_CatNavModel = new Goods_CatNavModel();
		$Goods_CatModel    = new Goods_CatModel();

		$id = request_int('goods_cat_id');

		$goods_cat = $Goods_CatNavModel->getByWhere(array('goods_cat_id' => $id));

		$data = array();

		$recommend_cat_rows = request_row('recommend_cat');

		$goods_cat_nav_recommend_display         = $Goods_CatModel->getCatDisplayRows($id, $recommend_cat_rows);
		$data['goods_cat_nav_name']              = request_string('cat_other_name');
		$data['goods_cat_nav_pic']               = request_string('cat_image');
		$data['goods_cat_nav_adv']               = request_string('adv_image') . ',' . request_string('advs_image'); //广告图
		$data['goods_cat_nav_adv_url']           = request_string('adv_image_url') . ',' . request_string('advs_image_url'); //广告图链接地址
		$data['goods_cat_nav_brand']             = implode(request_row('brand_value'), ','); //推荐品牌
		$data['goods_cat_nav_recommend']         = implode(request_row('recommend_cat'), ',');
		$data['goods_cat_nav_recommend_display'] = $goods_cat_nav_recommend_display;
		$data['goods_cat_id']                    = $id;

		if (empty($goods_cat))
		{
			$flag = $Goods_CatNavModel->addCatNav($data, true);
		}
		else
		{
			foreach ($goods_cat as $key => $val)
			{
				$goods_cat_nav_id = $val['goods_cat_nav_id'];
				$flag             = $Goods_CatNavModel->editCatNav($goods_cat_nav_id, $data);
			}
		}
		if ($flag !== false)
		{
            $Goods_CatModel->removeAllCatCache('all');
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function getNav()
	{
		$Goods_CatNavModel = new Goods_CatNavModel();
		$id                = request_int('id');
		if ($id)
		{
			$data_row = $Goods_CatNavModel->getByWhere(array('goods_cat_id' => $id));

			if ($data_row)
			{
				$data                               = pos($data_row);
				$data_re                            = array();
				$data_re['goods_cat_nav_adv']       = explode(',', $data['goods_cat_nav_adv']);
				$data_re['goods_cat_nav_adv_url']   = explode(',', $data['goods_cat_nav_adv_url']);
				$data_re['goods_cat_nav_brand']     = explode(',', $data['goods_cat_nav_brand']);
				$data_re['goods_cat_nav_recommend'] = explode(',', $data['goods_cat_nav_recommend']);
				$data_re['goods_cat_nav_pic']       = $data['goods_cat_nav_pic'];
				$data_re['goods_cat_nav_name']      = $data['goods_cat_nav_name'];
			}
		}
		if (!$data_re)
		{
            $data_re = array('goods_cat_id'=>$id);
		}
		$this->data->addBody(-140, $data_re, __('success'), 200);
	}

	public function getGoodsCatName()
	{
		$Goods_CatModel = new Goods_CatModel();
		$data_re        = array();
		$id             = request_int('id');
        if(!$id){
           return $this->data->addBody(-140, $data_re, __('success'), 200);
        }
		$data           = $Goods_CatModel->getOne($id);
         
		if ($data)
		{
			$data_re['id']       = $id;
			$data_re['cat_name'] = $data['cat_name'];
		}

		if ($data_re)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		return $this->data->addBody(-140, $data_re, $msg, $status);
	}

	public function getCatListByName()
	{
		$cat_name = request_string('cat_name');
		$cat_name = '%'.$cat_name.'%';

		$Goods_CatModel = new Goods_CatModel();
		$cond_row = array();
		$cond_row['cat_name:LIKE'] = $cat_name;
		$cat_row = $Goods_CatModel->getByWhere($cond_row);

		if($cat_row)
		{
			foreach($cat_row as $key => $val)
			{
				$cond_row = array();
				//将拥有下级的分类删除
				$cond_row['cat_parent_id'] = $val['cat_id'];
				$cat_child = $Goods_CatModel->getByWhere($cond_row);
				if($cat_child)
				{
					unset($cat_row[$key]);
				}
				else
				{
					$cat = array();
					$cat = $Goods_CatModel->getCatParentTree($val['cat_id']);
					$cat_row[$key]['cat_name_str'] = '';

					if($cat)
					{
						foreach($cat as $catkey => $catval)
						{
							$cat_row[$key]['cat_name_str'] .= $catval['cat_name'].'>';
						}
						$cat_row[$key]['cat_name_str'] = substr($cat_row[$key]['cat_name_str'],0,strlen($cat_row[$key]['cat_name_str'])-1);
					}
				}
			}

			$cat_row = array_values($cat_row);
		}

		$this->data->addBody(-140, $cat_row);
	}
}

?>
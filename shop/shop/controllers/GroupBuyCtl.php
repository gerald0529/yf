<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     yesai
 */
class GroupBuyCtl extends Controller
{
	public $groupBuyBaseModel       = null;
	public $groupBuyQuotaModel      = null;
	public $groupBuyPriceRangeModel = null;
	public $groupBuyCatModel        = null;
	public $groupBuyAreaModel       = null;


	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
        $this->initData();
		$this->web = $this->webConfig();
		$this->nav = $this->navIndex();
		$this->cat = $this->catIndex();

		if (!Web_ConfigModel::value('groupbuy_allow'))
		{
            $this->showMsg("团购功能已经关闭!");
		}

		$this->groupBuyBaseModel       = new GroupBuy_BaseModel();
		$this->groupBuyQuotaModel      = new GroupBuy_QuotaModel();
		$this->groupBuyPriceRangeModel = new GroupBuy_PriceRangeModel();
		$this->groupBuyCatModel        = new GroupBuy_CatModel();
		$this->groupBuyAreaModel       = new GroupBuy_AreaModel();
	}

	/**
	 * 团购首页
	 *
	 * @access public
	 */
	public function index()
	{
		$data         = array();
		$cond_row     = array();
		$cond_row_phy = array();
		$cond_row_vir = array();

		$cond_row['groupbuy_state']         = GroupBuy_BaseModel::NORMAL;
		$cond_row['groupbuy_starttime:<']  = get_date_time();
		$cond_row['groupbuy_endtime:>'] 	  = get_date_time();
		$cond_row['groupbuy_recommend'] 	  = GroupBuy_BaseModel::RECOMMEND;

		$order_row['groupbuy_recommend']    = 'DESC';
		$order_row['groupbuy_buy_quantity'] = 'DESC';

		$cond_row_phy = $cond_row_vir = $cond_row;

		$cond_row_phy['groupbuy_type'] = GroupBuy_BaseModel::ONLINEGBY;
		$cond_row_vir['groupbuy_type'] = GroupBuy_BaseModel::VIRGBY;

        $cond_row_phy_rec['groupbuy_type']        = GroupBuy_BaseModel::ONLINEGBY;
        $cond_row_phy_rec['groupbuy_recommend']  = GroupBuy_BaseModel::HIGHLYRECOMMEND;
        $cond_row_phy_rec['groupbuy_state']  = GroupBuy_BaseModel::NORMAL;
        $order_row_phy_rec['groupbuy_id']         = 'DESC';
        $data['goods']['physical']['highly_recommend'] = $this->groupBuyBaseModel->getGroupBuyDetailByWhere($cond_row_phy_rec, $order_row_phy_rec);
		$data['goods']['physical']['recommend'] = $this->groupBuyBaseModel->getGroupBuyGoodsList($cond_row_phy, $order_row, 0, 10);

        $cond_row_vir_rec['groupbuy_type']       = GroupBuy_BaseModel::VIRGBY;
        $cond_row_vir_rec['groupbuy_recommend'] = GroupBuy_BaseModel::HIGHLYRECOMMEND;
        $cond_row_vir_rec['groupbuy_state'] = GroupBuy_BaseModel::NORMAL;
		$cond_row_phy_rec['groupbuy_state']  = GroupBuy_BaseModel::NORMAL;
        $order_row_vir_rec['groupbuy_id']        = 'DESC';
        $data['goods']['virtual']['highly_recommend'] = $this->groupBuyBaseModel->getGroupBuyDetailByWhere($cond_row_vir_rec, $order_row_vir_rec);
		$data['goods']['virtual']['recommend']  = $this->groupBuyBaseModel->getGroupBuyGoodsList($cond_row_vir, $order_row, 0, 10);

		$data['cat']['physical'] = $this->groupBuyCatModel->getGroupBuyCatByWhere(array(
																					  "groupbuy_cat_type" => GroupBuy_CatModel::PHYSICALCAT,
																					  "groupbuy_cat_parent_id" => 0
																				  ));
		$data['cat']['virtual']  = $this->groupBuyCatModel->getGroupBuyCatByWhere(array(
																					  "groupbuy_cat_type" => GroupBuy_CatModel::VIRTUAL,
																					  "groupbuy_cat_parent_id" => 0
																				  ));
		//虚拟团购地区
        $request_groupbuy_area = request_int('city_id',-1); //-1 未选择团购地区,0-全国

		if ($request_groupbuy_area != -1)
		{
            setcookie("groupbuy_city_id", $request_groupbuy_area, time()+3600*24, "/");
            if ($request_groupbuy_area == 0)
            {
                $data['location']   = __('全国');
            }
            else
            {
                $data['location']             = $this->groupBuyAreaModel->getGroupBuyAreaNameByID(request_int('city_id'));
            }
		}
		else
		{
            if(@$_COOKIE['groupbuy_city_id'])
            {
                $data['location']             = $this->groupBuyAreaModel->getGroupBuyAreaNameByID($_COOKIE['groupbuy_city_id']);
            }
            else
            {
                $data['location']   = __('全国');
            }

		}

		$title             = Web_ConfigModel::value("tg_title");//首页名;
		$this->keyword     = Web_ConfigModel::value("tg_keyword");//关键字;
		$this->description = Web_ConfigModel::value("tg_description");//描述;
		$this->title       = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $title);
		$this->title       = str_replace("{name}", '爱拼团', $this->title);
		
		$this->keyword       = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $this->keyword);
		$this->keyword       = str_replace("{name}", '爱拼团', $this->keyword);
		
		$this->description       = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $this->description);
		$this->description       = str_replace("{name}", '爱拼团', $this->description);

		if ('e' == $this->typ)
		{
			include $this->view->getView();
		}
		else
		{
            //wap版分站
            if(request_string('ua') === 'wap'){
                $sub_site_id = request_int('sub_site_id');
            }
            if(Web_ConfigModel::value('subsite_is_open') && isset($sub_site_id) && $sub_site_id > 0){
                $sub_suffix = '_'.$sub_site_id;
            }else{
                $sub_suffix = '';
            }
            $data['cat']['physical'] = array_values($data['cat']['physical']);
            $data['cat']['virtual'] = array_values($data['cat']['virtual']);
            $data['banner']['slider1']['slider1_image'] = image_thumb(Web_ConfigModel::value('slider1_image'.$sub_suffix),1043,396);
            $data['banner']['slider1']['live_link1'] = Web_ConfigModel::value('live_link1'.$sub_suffix);
            $data['banner']['slider2']['slider2_image'] = image_thumb(Web_ConfigModel::value('slider2_image'.$sub_suffix),1043,396);
            $data['banner']['slider2']['live_link2'] = Web_ConfigModel::value('live_link2'.$sub_suffix);
            $data['banner']['slider3']['slider3_image'] = image_thumb(Web_ConfigModel::value('slider3_image'.$sub_suffix),1043,396);
            $data['banner']['slider3']['live_link3'] = Web_ConfigModel::value('live_link3'.$sub_suffix);
            $data['banner']['slider4']['slider4_image'] = image_thumb(Web_ConfigModel::value('slider4_image'.$sub_suffix),1043,396);
            $data['banner']['slider4']['live_link4'] = Web_ConfigModel::value('live_link4'.$sub_suffix);
			$this->data->addBody(-140, $data);
		}

	}

	//线上团购列表
	public function groupBuyList()
	{
		$data      = array();
		$cond_row  = array();
		$order_row = array();

		$data['price_range'] = $this->groupBuyPriceRangeModel->getPriceRangeByWhere();
		//分页
		$Yf_Page           = new Yf_Page();
		$Yf_Page->listRows = request_int('listRows')?request_int('listRows'):12;
		$rows              = $Yf_Page->listRows;
		$offset            = request_int('firstRow', 0);
		$page              = ceil_r($offset / $rows);

		$cond_row['groupbuy_type'] = GroupBuy_BaseModel::ONLINEGBY;

        $title             = Web_ConfigModel::value("tg_title_content");//首页名;
        $this->keyword     = Web_ConfigModel::value("tg_keyword_content");//关键字;
        $this->description = Web_ConfigModel::value("tg_description_content");//描述;
        $this->title       = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $title);
        $this->keyword       = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $this->keyword);
        $this->description       = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $this->description);


        //wap搜索
        $groupbuy_keyword = request_string('groupbuy_keyword');
        if($groupbuy_keyword != ''){
            $cond_row['groupbuy_name:like'] = '%'.$groupbuy_keyword.'%';
        }
        
		if (request_string('state') == 'underway') //即将开始
		{
			$cond_row['groupbuy_starttime:>'] = get_date_time();
			$cond_row['groupbuy_state']       = GroupBuy_BaseModel::NORMAL;
		}
		elseif (request_string('state') == 'history') //已经结束
		{
			$cond_row['groupbuy_state'] = GroupBuy_BaseModel::FINISHED;
		}
		else
		{
			$cond_row['groupbuy_state']        = GroupBuy_BaseModel::NORMAL;
			$cond_row['groupbuy_starttime:<='] = get_date_time();
			$cond_row['groupbuy_endtime:>=']   = get_date_time();
		}

		if (request_int('price'))
		{
			$range_id        = request_int('price');
			$price_range_row = $this->groupBuyPriceRangeModel->getPriceRangeById($range_id);
			if ($price_range_row)
			{
				$cond_row['groupbuy_price:>='] = $price_range_row['range_start'];
				$cond_row['groupbuy_price:<='] = $price_range_row['range_end'];
			}
		}
		//排序
		$orderby = request_string('orderby');
		switch ($orderby)
		{
			case 'priceasc':
				$order_row['groupbuy_price'] = 'ASC';
				break;
			case 'pricedesc':
				$order_row['groupbuy_price'] = 'DESC';
				break;
			case 'ratedesc':
				$order_row['groupbuy_rebate'] = 'DESC';
				break;
			case 'rateasc':
				$order_row['groupbuy_rebate'] = 'ASC';
				break;
			case 'saledesc':
				$order_row['groupbuy_virtual_quantity'] = 'DESC';
				break;
			case 'saleasc':
				$order_row['groupbuy_virtual_quantity'] = 'ASC';
				break;
			default:
			{
				$order_row['groupbuy_price'] = 'ASC';
				break;
			}
		}

        $groupbuy_cat_row = $this->groupBuyCatModel->getGroupBuyCatByWhere(array('groupbuy_cat_type' => GroupBuy_CatModel::PHYSICALCAT));
        if ($groupbuy_cat_row)
        {
            foreach ($groupbuy_cat_row as $key => $value)
            {
                if ($value['groupbuy_cat_parent_id'] == 0)
                {
                    $groupbuy_cat[$value['groupbuy_cat_id']] = $value;
                }
                else
                {
                    $groupbuy_cat[$value['groupbuy_cat_parent_id']]['scat'][$value['groupbuy_cat_id']] = $value;
                }
            }
            $data['groupbuy_cat'] = $groupbuy_cat;
        }

        $data['current_cat'] = array();
		if (request_int('cat_id'))
		{
			$cat_id                      = request_int('cat_id');
			$cond_row['groupbuy_cat_id'] = $cat_id;

            $cond_row_cat['groupbuy_cat_id'] = $cat_id;
            $cond_row_cat['groupbuy_cat_type'] = GroupBuy_CatModel::PHYSICALCAT;
            $data['current_cat'] = $this->groupBuyCatModel->getOneByWhere(array('groupbuy_cat_id'=>$cat_id));
		}

		if (request_int('scat_id'))
		{
			$scat_id                      = request_int('scat_id');
			$cond_row['groupbuy_scat_id'] = $scat_id;
		}
        $sub_site_id = 0;
        //pc分站
        if(isset($_COOKIE['sub_site_id']) && $_COOKIE['sub_site_id'] > 0){
            $sub_site_id = $_COOKIE['sub_site_id'];
        }
        //wap分站
        if(request_string('ua') === 'wap'){
            $sub_site_id = request_int('sub_site_id');
        }
		$data['groupbuy_goods'] = $this->groupBuyBaseModel->getGroupBuyGoodsList($cond_row, $order_row, $page, $rows,$sub_site_id);
		$data['page'] = $page;
		$Yf_Page->totalRows      = $data['groupbuy_goods']['totalsize'];
		$page_nav                 = $Yf_Page->prompt();

        $data['cat']['physical'] = $this->groupBuyCatModel->getGroupBuyCatByWhere(array(
            "groupbuy_cat_type" => GroupBuy_CatModel::PHYSICALCAT,
            "groupbuy_cat_parent_id" => 0
        ));
        $data['cat']['virtual']  = $this->groupBuyCatModel->getGroupBuyCatByWhere(array(
            "groupbuy_cat_type" => GroupBuy_CatModel::VIRTUAL,
            "groupbuy_cat_parent_id" => 0
        ));

		if ('e' == $this->typ)
		{
			$this->view->setMet('list');
			include $this->view->getView();
		}
		else
		{
            $data['cat']['physical'] = array_values($data['cat']['physical']);
            $data['cat']['virtual'] = array_values($data['cat']['virtual']);
			$this->data->addBody(-140, $data);
		}
	}

	//虚拟团购列表
	public function vrGroupBuyList()
	{
		$data      = array();
		$cond_row  = array();
		$order_row = array();
		//分页
		$Yf_Page           = new Yf_Page();
		$Yf_Page->listRows = request_int('listRows')?request_int('listRows'):12;
		$rows              = $Yf_Page->listRows;
		$offset            = request_int('firstRow', 0);
		$page              = ceil_r($offset / $rows);

		$cond_row['groupbuy_type'] = GroupBuy_BaseModel::VIRGBY;
        
        //wap搜索
        $groupbuy_keyword = request_string('groupbuy_keyword');
        if($groupbuy_keyword != ''){
            $cond_row['groupbuy_name:like'] = '%'.$groupbuy_keyword.'%';
        }

		if (request_string('state') == 'underway')   //即将开始
		{
			$cond_row['groupbuy_starttime:>'] = get_date_time();
			$cond_row['groupbuy_state']       = GroupBuy_BaseModel::NORMAL;
		}
		elseif (request_string('state') == 'history') //已经结束
		{
			$cond_row['groupbuy_state'] = GroupBuy_BaseModel::FINISHED;
		}
		else
		{
			$cond_row['groupbuy_state']        = GroupBuy_BaseModel::NORMAL;
			$cond_row['groupbuy_starttime:<='] = get_date_time();
			$cond_row['groupbuy_endtime:>=']   = get_date_time();
		}

        //虚拟团购地区
        $request_groupbuy_area = request_int('city_id',-1); //-1 未选择团购地区,0-全国

        if ($request_groupbuy_area != -1)
        {
            setcookie("groupbuy_city_id", $request_groupbuy_area, time()+3600*24, "/");
            if ($request_groupbuy_area == 0)
            {
                $data['location']   = __('全国');
                $data['child_area'] = array();
            }
            else
            {
                $cond_row['groupbuy_city_id'] = request_int('city_id');
                $data['location']              = $this->groupBuyAreaModel->getGroupBuyAreaNameByID(request_int('city_id'));
                $data['child_area'] = $this->groupBuyAreaModel->getGroupBuyAreaByWhere(array('groupbuy_area_parent_id' => request_int('city_id')));
            }
        }
        else
        {
            if(@$_COOKIE['groupbuy_city_id'])
            {
                $data['location']              = $this->groupBuyAreaModel->getGroupBuyAreaNameByID($_COOKIE['groupbuy_city_id']);
                $cond_row['groupbuy_city_id'] = $_COOKIE['groupbuy_city_id'];
                $data['child_area'] = $this->groupBuyAreaModel->getGroupBuyAreaByWhere(array('groupbuy_area_parent_id' => request_int('city_id')));
            }
            else
            {
                $data['location']   = __('全国');
                $data['child_area'] = array();
            }
        }


		if (request_int('area_id'))
		{
			$cond_row['groupbuy_area_id'] = request_int('area_id');
		}

		if (request_int('price'))
		{
			$range_id        = request_int('price');
			$price_range_row = $this->groupBuyPriceRangeModel->getPriceRangeById($range_id);
			if ($price_range_row)
			{
				$cond_row['groupbuy_price:>='] = $price_range_row['range_start'];
				$cond_row['groupbuy_price:<='] = $price_range_row['range_end'];
			}
		}

		$groupbuy_cat_row = $this->groupBuyCatModel->getGroupBuyCatByWhere(array('groupbuy_cat_type' => GroupBuy_CatModel::VIRTUAL));
		if ($groupbuy_cat_row)
		{
			foreach ($groupbuy_cat_row as $key => $value)
			{
				if ($value['groupbuy_cat_parent_id'] == 0)
				{
					$groupbuy_cat[$value['groupbuy_cat_id']] = $value;
				}
				else
				{
					$groupbuy_cat[$value['groupbuy_cat_parent_id']]['scat'][$value['groupbuy_cat_id']] = $value;
				}
			}
			$data['groupbuy_cat'] = $groupbuy_cat;
		}

        $data['current_cat'] = array();
		if (request_int('cat_id'))
		{
			$cat_id                      = request_int('cat_id');
			$cond_row['groupbuy_cat_id'] = $cat_id;

            $cond_row_cat['groupbuy_cat_id'] = $cat_id;
            $cond_row_cat['groupbuy_cat_type'] = GroupBuy_CatModel::VIRTUAL;
            $data['current_cat'] = $this->groupBuyCatModel->getOneByWhere(array('groupbuy_cat_id'=>$cat_id));
		}


		if (request_int('scat_id'))
		{
			$scat_id                      = request_int('scat_id');
			$cond_row['groupbuy_scat_id'] = $scat_id;
		}

		$orderby = request_string('orderby');
		switch ($orderby)
		{
			case 'priceasc':
				$order_row['groupbuy_price'] = 'ASC';
				break;
			case 'pricedesc':
				$order_row['groupbuy_price'] = 'DESC';
				break;
			case 'ratedesc':
				$order_row['groupbuy_rebate'] = 'DESC';
				break;
			case 'rateasc':
				$order_row['groupbuy_rebate'] = 'ASC';
				break;
			case 'saledesc':
				$order_row['groupbuy_virtual_quantity'] = 'DESC';
				break;
			case 'saleasc':
				$order_row['groupbuy_virtual_quantity'] = 'ASC';
				break;
			default:
			{
				$order_row['groupbuy_price'] = 'ASC';
				break;
			}
		}

        $sub_site_id = 0;
        //pc分站
        if(isset($_COOKIE['sub_site_id']) && $_COOKIE['sub_site_id'] > 0){
            $sub_site_id = $_COOKIE['sub_site_id'];
        }
        //wap分站
        if(request_string('ua') === 'wap'){
            $sub_site_id = request_int('sub_site_id');
        }
		$data['area']           = $this->groupBuyAreaModel->getGroupBuyAreaByWhere(array('groupbuy_area_parent_id' => 0), array());
		$data['price_range']    = $this->groupBuyPriceRangeModel->getPriceRangeByWhere();
		$data['groupbuy_goods'] = $this->groupBuyBaseModel->getGroupBuyGoodsList($cond_row, $order_row, $page, $rows,$sub_site_id);
		$Yf_Page->totalRows     = $data['groupbuy_goods']['totalsize'];
		$page_nav               = $Yf_Page->prompt();

        $data['cat']['physical'] = $this->groupBuyCatModel->getGroupBuyCatByWhere(array(
            "groupbuy_cat_type" => GroupBuy_CatModel::PHYSICALCAT,
            "groupbuy_cat_parent_id" => 0
        ));
        $data['cat']['virtual']  = $this->groupBuyCatModel->getGroupBuyCatByWhere(array(
            "groupbuy_cat_type" => GroupBuy_CatModel::VIRTUAL,
            "groupbuy_cat_parent_id" => 0
        ));

		if ('e' == $this->typ)
		{
			$this->view->setMet('vrList');
			include $this->view->getView();
		}
		else
		{
            $data['cat']['physical'] = array_values($data['cat']['physical']);
            $data['cat']['virtual'] = array_values($data['cat']['virtual']);
			$this->data->addBody(-140, $data);
		}
	}

	//团购详情
	public function detail()
	{
//        exit; //该页面在v3.1.3以后已经关闭
        
		$data                    = array();
		$groupbuy_id             = request_int('id');
		$data['groupbuy_detail'] = $this->groupBuyBaseModel->getGroupBuyDetailByID($groupbuy_id);

		$shop_id = $data['groupbuy_detail']['shop_id'];

		$Goods_CommonModel = new Goods_CommonModel();
		$goods_id          = $Goods_CommonModel->getNormalStateGoodsId($data['groupbuy_detail']['common_id']);
		$data['groupbuy_detail']['goods_id'] = $goods_id;

		//1.商品信息（商品活动信息，评论数，销售数，咨询数）
		$Goods_BaseModel = new Goods_BaseModel();
		$goods_detail    = $Goods_BaseModel->getGoodsDetailInfoByGoodId($goods_id);

		if ($data['groupbuy_detail'] && $goods_detail)
		{
			//更新浏览次数
			$this->groupBuyBaseModel->editGroupBuy($groupbuy_id, array('groupbuy_views' => 1), true);

			//团购分类
			$data['cat'] = $this->groupBuyCatModel->getCatName($data['groupbuy_detail']['groupbuy_cat_id'], $data['groupbuy_detail']['groupbuy_scat_id']);

			//热门团购
			$cond_row_hot['groupbuy_state']         = GroupBuy_BaseModel::NORMAL;
			$cond_row_hot['groupbuy_starttime:<=']  = get_date_time();
			$cond_row_hot['groupbuy_endtime:>=']    = get_date_time();
			$data['hot_groupbuy'] = $this->groupBuyBaseModel->getGroupBuyGoodsList($cond_row_hot, array('groupbuy_buy_quantity'=>'DESC'), 0, 5);;

			//团购区域
			$data['area'] = $this->groupBuyAreaModel->getGroupBuyAreaList(array('groupbuy_area_parent_id' => 0), array(), 0, 12);

			if ($shop_id)
			{
				$Goods_CommonModel   = new Goods_CommonModel();
				$data_recommon       = $Goods_CommonModel->listByWhere(array(
																		   'common_is_recommend' => 1,
																		   'shop_id' => $shop_id
																	   ), array('common_sell_time' => 'desc'), 0, 4);
				$data_recommon_goods = $Goods_CommonModel->getRecommonRow($data_recommon);

				//推荐商品
				$data_foot_recommon       = $Goods_CommonModel->listByWhere(array(
																				'shop_id' => $shop_id
																			), array('common_is_recommend'=>'DESC'), 0, 5);
				$data_foot_recommon_goods = $Goods_CommonModel->getRecommonRow($data_foot_recommon);

				//热门销售
				$data_hot_salle = $Goods_CommonModel->getHotSalle($shop_id);
				$data_salle     = $Goods_CommonModel->getRecommonRow($data_hot_salle);
				//热门收藏
				$data_hot_collect = $Goods_CommonModel->getHotCollect($shop_id);
				$data_collect     = $Goods_CommonModel->getRecommonRow($data_hot_collect);

				//商品咨询数量
				$Consult_BaseModel = new Consult_BaseModel();
				$data_consult      = $Consult_BaseModel->getByWhere(array(
																		'goods_id' => $goods_id,
																		'shop_id' => $shop_id
																	));
				$consult_num       = count($data_consult);
			}


			//1.商品信息（商品活动信息，评论数，销售数，咨询数）
			$Goods_BaseModel = new Goods_BaseModel();
			$goods_detail    = $Goods_BaseModel->getGoodsDetailInfoByGoodId($goods_id);

			//计算商品的销售数量1.直接显示本件商品的销售数量，2.显示本类common商品的销售数量

			$common_goods = $Goods_BaseModel->getByWhere(array('common_id' => $goods_detail['goods_base']['common_id']));
			$count_sale   = 0;
			foreach ($common_goods as $comkey => $comval)
			{
				$count_sale += $comval['goods_salenum'];
			}
			$goods_detail['goods_base']['count_sale'] = $count_sale;
			fb($goods_detail);
			fb("商品信息");

			//关联样式
			$Goods_FormatModel = new Goods_FormatModel();
			$goods_data        = $Goods_BaseModel->getOne($goods_id);
			$common_id         = $goods_data['common_id'];
			$common_data       = $Goods_CommonModel->getOne($common_id);
			if ($common_data)
			{
				$common_formatid_top = $common_data['common_formatid_top'];

				if ($common_formatid_top)
				{
					$goods_format_top = $Goods_FormatModel->getOne($common_formatid_top);
				}

				$common_formatid_bottom = $common_data['common_formatid_bottom'];

				if ($common_formatid_bottom)
				{
					$goods_format_bottom = $Goods_FormatModel->getOne($common_formatid_bottom);
				}
			}


			//2.店铺信息
			$Shop_BaseModel = new Shop_BaseModel();
			$shop_detail    = $Shop_BaseModel->getShopDetail($shop_id);
			fb($shop_detail);
			fb("店铺详情");
			//获取店铺的消费者保障服务

			$Shop_ContractModel                  = new Shop_ContractModel();
			$shop_cond_row                       = array();
			$shop_cond_row['shop_id']            = $shop_id;
			$shop_cond_row['contract_state']     = Shop_ContractModel::CONTRACT_INUSE;
			$shop_cond_row['contract_use_state'] = Shop_ContractModel::CONTRACT_JOIN;
			$constract                           = $Shop_ContractModel->getByWhere($shop_cond_row);
			$data['shop']['constract']           = $constract;


			$title             = Web_ConfigModel::value("tg_title_content");//首页名;
			$this->keyword     = Web_ConfigModel::value("tg_keyword_content");//关键字;
			$this->description = Web_ConfigModel::value("tg_description_content");//描述;
			$this->title       = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $title);
			$this->title       = str_replace("{name}", $data['groupbuy_detail']['groupbuy_name'], $this->title);
			$this->keyword       = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $this->keyword);
			$this->keyword       = str_replace("{name}", $data['groupbuy_detail']['groupbuy_name'], $this->keyword);
			$this->description       = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $this->description);
			$this->description       = str_replace("{name}", $data['groupbuy_detail']['groupbuy_name'], $this->description);
			
		}
		else
		{
			$this->view->setMet('404');
		}

		if ('e' == $this->typ)
		{
			include $this->view->getView();
		}
		else
		{
            $data['data_foot_recommon_goods'] = $data_foot_recommon_goods;
            $data['shop_base'] = $shop_detail;
			$data['shop_base']['shop_desc_scores'] = number_format($data['shop_base']['shop_desc_scores'], 2, '.', '');
			$data['shop_base']['shop_service_scores'] = number_format($data['shop_base']['shop_service_scores'], 2, '.', '');
			$data['shop_base']['shop_send_scores'] = number_format($data['shop_base']['shop_send_scores'], 2, '.', '');
			$this->data->addBody(-140, $data);
		}

	}


}

?>
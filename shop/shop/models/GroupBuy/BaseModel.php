<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/20
 * Time: 15:44
 */
class GroupBuy_BaseModel extends GroupBuy_Base
{
	const WILLSTART    = 0; //审核通过，但未到开始时间，即将开始
	const UNDERREVIEW  = 1;  //审核中
	const NORMAL       = 2;  //正常
	const FINISHED     = 3;  //结束
	const AUDITFAILUER = 4; //审核失败
	const CLOSED       = 5; //管理员关闭


	const ONLINEGBY = 1;  //线上团
	const VIRGBY    = 2;  //虚拟团

	const UNRECOMMEND        = 0;   //不推荐
	const RECOMMEND          = 1;   //首页推荐
	const HIGHLYRECOMMEND   = 2;   //大图推荐

	public $Goods_CommonModel = null;
	//团购状态 1.未发布 2.已取消 3.正常 4.已完成 5.已结束'
	public static $groupbuy_state_map = array(
		self::UNDERREVIEW => '审核中',
		self::NORMAL => '正常',
		self::FINISHED => '结束',
		self::AUDITFAILUER => '审核失败',
		self::CLOSED => '管理员关闭',
		self::WILLSTART => '即将开始'
	);

	//团购商品推荐状态 0.否 1.是'
	public static $recommend_map = array(
		self::UNRECOMMEND => '否',
		self::RECOMMEND => '首页推荐',
		self::HIGHLYRECOMMEND => '首页大图推荐'
	);

	//团购商品类型 1-实物，2-虚拟商品
	public static $goods_type_map = array(
		self::ONLINEGBY => '实物',
		//线上团
		self::VIRGBY => '虚拟商品'
		//虚拟团
	);

	public $htmlKey = array(
		'groupbuy_intro'
	);

	public function __construct()
	{
		parent::__construct();
		$this->Goods_CommonModel = new Goods_CommonModel();
	}

	/*
	 *获取团购商品
	 *分页
     * $is_all 是否查询全部数据，包括不正常的商品
	 */
	public function getGroupBuyGoodsList($cond_row = array(), $order_row = array(), $page = 1, $pagesize = 100,$sub_site_id = 0 ,$is_all = false)
	{
		$rows = $this->listByWhere($cond_row, $order_row, $page, $pagesize);

		if ($rows['items'])
		{
			$groupbuy_goods  = array();  //团购商品
			$expire_groupbuy = array(); //过期的活动
//			$delete_groupbuy = array(); //活动下的商品已经被删除

			foreach ($rows['items'] as $key => $value)
			{
				$rows['items'][$key]['groupbuy_recommend_label'] = __(self::$recommend_map[$value['groupbuy_recommend']]);
				$rows['items'][$key]['groupbuy_state_label']     = __(self::$groupbuy_state_map[$value['groupbuy_state']]);
				$rows['items'][$key]['groupbuy_type_label']      = __(self::$goods_type_map[$value['groupbuy_type']]);

				if (strtotime($value['groupbuy_endtime']) < time() && $value['groupbuy_state'] == self::NORMAL)
				{
					$rows['items'][$key]['groupbuy_state']       = self::FINISHED;
					$rows['items'][$key]['groupbuy_state_label'] = __(self::$groupbuy_state_map[self::FINISHED]);

					$expire_groupbuy[] = $value['groupbuy_id'];
				}

				$groupbuy_goods[] = $value['common_id'];
			}

            //获取分站的商品
            $goods_cond = array();
            if($sub_site_id > 0){
                //获取站点信息
                $sub_SiteModel = new Sub_SiteModel();
                $sub_site_district_ids = $sub_SiteModel->getDistrictChildId($sub_site_id);
                if($sub_site_district_ids){
                    $goods_cond['district_id:IN'] = $sub_site_district_ids;
                }
            }

			$goods_common_rows = $this->Goods_CommonModel->getNormalStateGoodsCommon($groupbuy_goods,$goods_cond);
            $group_rows = array();
            $k = 0;
			foreach ($rows['items'] as $key => $value)
			{
				if (in_array($value['common_id'], array_keys($goods_common_rows)))
				{
					$rows['items'][$key]['goods_name']  = $goods_common_rows[$value['common_id']]['common_name'];
					$rows['items'][$key]['goods_price'] = $goods_common_rows[$value['common_id']]['common_price'];
					$rows['items'][$key]['reduce']      = $goods_common_rows[$value['common_id']]['common_price'] - $value['groupbuy_price'];
					$rows['items'][$key]['rate']        = sprintf("%.2f", $value['groupbuy_price'] / $goods_common_rows[$value['common_id']]['common_price'] * 10);
                    $rows['items'][$key]['goods_stock']  = $goods_common_rows[$value['common_id']]['common_stock'];
                    $rows['items'][$key]['is_start']  = $value['groupbuy_starttime'] > date('Y-m-d H:i:s') ? 0 : 1;
                    $group_rows[$k] = $rows['items'][$key];
                    $k ++ ;
				}
//				else
//				{
//					unset($rows['items'][$key]);
//					$delete_groupbuy[] = $value['groupbuy_id'];
//				}
			}

            if($is_all == false){
                $rows['items'] = $group_rows;
            }

			$field_row['groupbuy_state'] = self::FINISHED;
			$this->editGroupBuy($expire_groupbuy, $field_row);  //活动到期，更改活动状态

//			$this->removeGroupBuyGoods($delete_groupbuy);       //删除商品不存在的活动
		}
        
        
		return $rows;
	}

	//多条件 获取商品团购详情
	public function getGroupBuyDetailByWhere($cond_row)
	{
		$row = $this->getOneByWhere($cond_row);
		if ($row)
		{
			$row['recommend_label']      = __(self::$recommend_map[$row['groupbuy_recommend']]);
			$row['groupbuy_state_label'] = __(self::$groupbuy_state_map[$row['groupbuy_state']]);

			$goods_common_row = $this->Goods_CommonModel->getOneByWhere(array('common_id' => $row['common_id']));

			if ($goods_common_row)
			{
				$row['goods_name']  = $goods_common_row['common_name'];
				$row['goods_price'] = $goods_common_row['common_price'];
				$row['reduce']      = $goods_common_row['common_price'] - $row['groupbuy_price'];
				$row['rate']        = sprintf("%.2f", $row['groupbuy_price'] / $goods_common_row['common_price'] * 10);

				if (strtotime($row['groupbuy_endtime']) < time() && $row['groupbuy_state'] == self::NORMAL)
				{
					$row['groupbuy_state']       = self::FINISHED;
					$row['groupbuy_state_label'] = __(self::$groupbuy_state_map[self::FINISHED]);

					$field_row['groupbuy_state'] = self::FINISHED;
					$this->editGroupBuy($row['groupbuy_id'], $field_row);
				}
				else
				{
					if ($row['groupbuy_state'] == self::NORMAL && strtotime(($row['groupbuy_starttime'])) > time())//即将开始
					{
						$row['groupbuy_state']       = self::WILLSTART; //审核通过，即将开始
						$row['groupbuy_state_label'] = __(self::$groupbuy_state_map[self::WILLSTART]);
					}
				}
			}
			else
			{
//				$this->removeGroupBuyGoods($row['groupbuy_id']);
				unset($row);
			}
		}

		return $row;
	}

	//根据主键搜索团购详情
	public function getGroupBuyDetailByID($groupbuy_id)
	{
		$row = $this->getOne($groupbuy_id);
		if ($row)
		{
			$row['recommend_label']      = __(self::$recommend_map[$row['groupbuy_recommend']]);
			$row['groupbuy_state_label'] = __(self::$groupbuy_state_map[$row['groupbuy_state']]);

			$goods_common_row = $this->Goods_CommonModel->getOneByWhere(array('common_id' => $row['common_id']));

			if ($goods_common_row)
			{
				$row['goods_name']  = $goods_common_row['common_name'];
				$row['goods_price'] = $goods_common_row['common_price'];
				$row['reduce']      = $goods_common_row['common_price'] - $row['groupbuy_price'];
				$row['rate']        = sprintf("%.2f", $row['groupbuy_price'] / $goods_common_row['common_price'] * 10);

				if (strtotime($row['groupbuy_endtime']) < time() && $row['groupbuy_state'] == self::NORMAL) //活动到期
				{
					$row['groupbuy_state']       = self::FINISHED;
					$row['groupbuy_state_label'] = __(self::$groupbuy_state_map[self::FINISHED]);

					$field_row['groupbuy_state'] = self::FINISHED;
					$this->editGroupBuy($row['groupbuy_id'], $field_row);
				}
				else
				{
					if ($row['groupbuy_state'] == self::NORMAL && strtotime(($row['groupbuy_starttime'])) > time())//即将开始
					{
						//$row['groupbuy_state']       = self::WILLSTART; //审核通过，即将开始
						$row['groupbuy_state_label'] = __(self::$groupbuy_state_map[self::WILLSTART]);
					}
				}
			}
			else  //参加团购的商品已被删除
			{
//				$this->removeGroupBuyGoods($row['groupbuy_id']);
				unset($row);
			}
		}

		return $row;
	}

	//发布活动
	public function addGroupBuy($field_row, $return_insert_flag)
	{
		return $this->add($field_row, $return_insert_flag);
	}
	/*删除团购商品*/
	/**
	 * @param $groupbuy_id
	 * @return bool
	 */
	public function removeGroupBuyGoods($groupbuy_id)
	{
		$rs_row = array();

		/*//活动商品对应的common_id
		$groupbuy_goods_rows = $this->get($groupbuy_id);
		$common_id_row = array_column($groupbuy_goods_rows,'common_id');
		$cond_row['common_id:IN'] =    $common_id_row;
		$cond_row['groupbuy_id:!='] =  $groupbuy_id;*/


		$del_flag = $this->remove($groupbuy_id);
		check_rs($del_flag, $rs_row);

		return is_ok($rs_row);
	}

	/*修改团购信息*/
	public function editGroupBuy($groupbuy_id, $field_row, $flag = null)
	{
		$update_flag = $this->edit($groupbuy_id, $field_row, $flag);
		return $update_flag;
	}
    
    /**
     * 获取店铺正在进行活动或者即将进行活动的商品
     * @param type $common_id
     * @return type
     */
    public function getGroupbuyByCommonId($common_id){
        //获取团购
        $cond_row = is_array($common_id) ? array('common_id:IN'=>$common_id) : array('common_id'=>$common_id);
        $cond_row['groupbuy_endtime:>'] = date('Y-m-d H:i:s');
        $cond_row['groupbuy_state:IN'] = array(self::UNDERREVIEW, self::NORMAL, self::AUDITFAILUER);
        
        $list = $this->getByWhere($cond_row);
        return $list;
    }
    
    
    /**
     * 获取店铺正在进行活动或者即将进行活动的商品
     * @return type
     */
    public function getGroupbuy(){
        //获取团购
        $cond_row['groupbuy_endtime:>'] = date('Y-m-d H:i:s',time());
        $cond_row['groupbuy_state:IN'] = array(self::UNDERREVIEW, self::NORMAL, self::AUDITFAILUER);
        
        $list = $this->getByWhere($cond_row);
        return $list;
    }
    
    
    /**
     * 获取common_id
     * @param type $list
     * @return type
     */
    public function getCommonidByGroupbuyList($list){
        if(!$list){
            return array();
        }
        $ids = array();
        foreach ($list as $value){
            $ids[] = $value['common_id'];
        }
        return $ids;
    }
    
    
    
    /**
     * 获取参加活动的商品common_id
     * @param type $common_id
     * @return type
     */
    public function getAllActivityCommonId($common_id){
        //团购
        $group_list = $this->getGroupbuyByCommonId($common_id);
        $group_common_ids = $this->getCommonidByGroupbuyList($group_list);
        //折扣
        $discount_goods_model = new Discount_GoodsModel();
        $discount_list = $discount_goods_model->getDiscountByCommonId($common_id);
        $discount_common_ids = $discount_goods_model->getCommonidByDiscountList($discount_list);
        $ids = array_unique(array_merge($group_common_ids,$discount_common_ids));
        return $ids;
    }
    
    /**
     * 获取参加活动的商品goods_id
     * @param type $goods_id
     * @return type
     */
    public function getAllActivityGoodsId(){
        //获取正在团购的商品
        $group_list = $this->getGroupbuy();
        $group_common_ids = array_column($group_list, 'common_id');
        $goods_model = new Goods_BaseModel();
        $goods_list = $goods_model->getByWhere(array('common_id:IN'=>$group_common_ids));
        $group_goods_ids = array_column($goods_list, 'goods_id');
        
        //折扣
        $discount_goods_model = new Discount_GoodsModel();
        $discount_list = $discount_goods_model->getDiscount();
        $discount_goods_ids = array_column($discount_list, 'goods_id');
        $ids = array_unique(array_merge($group_goods_ids,$discount_goods_ids));
   
        return $ids;
    }

    /**
     * 获取店铺正在进行活动或者即将进行活动的商品
     * @return type
     */
    public function getGroupbuyList($cond_row){
        $list = $this->getByWhere($cond_row);
        return $list;
    }
}
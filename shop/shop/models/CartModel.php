<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Yf <service@yuanfeng.cn>
 */
class CartModel extends Cart
{
	/**
	 * 读取分页列表
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCatGoodsList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	/**
	 * 虚拟商品确认订单
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getVirtualCart($goods_id = null, $num = null)
	{

		$Goods_BaseModel = new Goods_BaseModel();

		//验证商品信息
		$res = $Goods_BaseModel->checkGoods($goods_id);
		if (!$res)
		{
			return null;
		}

		$goods_base = $Goods_BaseModel->getGoodsInfo($goods_id);


        $goods_base['goods_base']['old_price']  = 0;
        $goods_base['goods_base']['now_price']  = $goods_base['goods_base']['goods_price'];
        $goods_base['goods_base']['down_price'] = 0;
		//计算商品价格
		if (isset($goods_base['goods_base']['promotion_price']) && !empty($goods_base['goods_base']['promotion_price']) && $goods_base['goods_base']['promotion_price'] < $goods_base['goods_base']['goods_price'])
		{
            if ($goods_base['goods_base']['groupbuy_starttime'] < date('Y-m-d H:i:s') && $goods_base['goods_base']['groupbuy_endtime'] > date('Y-m-d H:i:s'))
			{
                $goods_base['goods_base']['old_price']  = $goods_base['goods_base']['goods_price'];
                $goods_base['goods_base']['now_price']  = $goods_base['goods_base']['promotion_price'];
                $goods_base['goods_base']['down_price'] = $goods_base['goods_base']['down_price'];
            }
		}

		//商品总价格
		$goods_base['goods_base']['sumprice'] = number_format($goods_base['goods_base']['now_price'] * $num, 2, '.', '');
		//商品的交易佣金
		$Goods_CatModel = new Goods_CatModel();
		$Shop_ClassBindModel = new Shop_ClassBindModel();
		$goods_cat = $Shop_ClassBindModel->getByWhere(array('shop_id'=>$goods_base['goods_base']['shop_id'],'product_class_id'=>$goods_base['goods_base']['cat_id']));
		if($goods_cat)
		{
			$goods_cat = current($goods_cat);
			$cat_commission = $goods_cat['commission_rate'];
		}
		else
		{
			$goods_cat = $Goods_CatModel->getOne($goods_base['goods_base']['cat_id']);
			if ($goods_cat)
			{
				$cat_commission = $goods_cat['cat_commission'];
			}
			else
			{
				$cat_commission = 0;
			}
		}

		$goods_base['goods_base']['cat_commission'] = $cat_commission;
		$goods_base['goods_base']['commission'] = number_format(($goods_base['goods_base']['sumprice'] * $cat_commission / 100), 2, '.', '');

		$Promotion = new Promotion();
		//店铺满送活动
		$mansong_info = $Promotion->getShopOrderGift($goods_base['goods_base']['shop_id'], $goods_base['goods_base']['sumprice']);

        $has_physical = 0;
		if ($mansong_info)
		{
			if (isset($mansong_info['gift_goods_id']))
			{
				$man_goods_base = $Goods_BaseModel->checkGoods($mansong_info['gift_goods_id']);
				if (!$man_goods_base)
				{
					$mansong_info['gift_goods_id'] = 0;
				}
				else
				{
					$mansong_info['goods_name']  = $man_goods_base['goods_base']['goods_name'];
					$mansong_info['goods_image'] = $man_goods_base['goods_base']['goods_image'];
					$mansong_info['common_id'] = $man_goods_base['goods_base']['common_id'];
                    //判断商品类型：虚拟 or  实物
                    $goods_common_model = new Goods_CommonModel();
                    $is_virtual = $goods_common_model->isVirtual($mansong_info['common_id']);
                    $has_physical = $is_virtual ? 0 : 1;
				}
			}

			if (!$mansong_info['gift_goods_id'] && !$mansong_info['rule_discount'])
			{
				$mansong_info = array();
			}
		}

		if (isset($mansong_info['rule_discount']) && $mansong_info['rule_discount'])
		{
			$goods_base['goods_base']['sumprice'] = $goods_base['goods_base']['sumprice'] - $mansong_info['rule_discount'];
		}
		$goods_base['mansong_info'] = $mansong_info;

		//加价购
		$increase                              = array();
		$goods_base['goods_base']['goods_num'] = $num;
		$increase['shop_id']                   = $goods_base['goods_base']['shop_id'];
		$increase['goods'][]                   = $goods_base['goods_base'];
		$increase_info                         = $Promotion->getOrderIncreaseInfo($increase);

		//去除加价购商品中没有库存和不存在的商品，若是改活动下没有有效商品则去除该活动
		foreach ($increase_info as $inckey => $incval)
		{
			if (!empty($incval['exc_goods']))
			{
				foreach ($incval['exc_goods'] as $excgkey => $excgval)
				{
					$goods_basel = $Goods_BaseModel->checkGoods($excgval['goods_id']);
					if (!$goods_basel)
					{
						unset($incval['exc_goods'][$excgkey]);
						unset($increase_info[$inckey]['exc_goods'][$excgkey]);
					}
					else
					{
						$increase_info[$inckey]['exc_goods'][$excgkey]['goods_name']  = $goods_basel['goods_base']['goods_name'];
						$increase_info[$inckey]['exc_goods'][$excgkey]['goods_image'] = $goods_basel['goods_base']['goods_image'];
                        
					}
				}

				if (empty($incval['exc_goods']))
				{
					unset($increase_info[$inckey]);
				}
			}
			else
			{
				unset($increase_info[$inckey]);
			}
		}

		$goods_base['increase_info'] = $increase_info;


		//店铺代金券
		$Voucher_BaseModel = new Voucher_BaseModel();
		$voucher_base      = $Voucher_BaseModel->getUserOrderVoucherByWhere(Perm::$userId, $goods_base['goods_base']['shop_id'], $goods_base['goods_base']['sumprice']);

		$goods_base['voucher_base'] = $voucher_base;
        $goods_base['has_physical'] = $has_physical;
		return $goods_base;
	}

    /**
     * 门店自提商品
     * @param $goods_id
     * @param $num
     * @param $chain_id
     * @return array
     */
    public function getChainGoods($goods_id, $num, $chain_id)
    {
        $Goods_BaseModel = new Goods_BaseModel();

        //验证商品信息
        $Goods_BaseModel->isValidChainGoods($goods_id, $num, $chain_id);
        $goods_base = $Goods_BaseModel->getGoodsInfo($goods_id);

        $goods_base['goods_base']['old_price'] = 0;
        $goods_base['goods_base']['now_price'] = $goods_base['goods_base']['goods_price'];
        $goods_base['goods_base']['down_price'] = 0;
        //计算商品价格
        if (isset($goods_base['goods_base']['promotion_price']) && !empty($goods_base['goods_base']['promotion_price']) && $goods_base['goods_base']['promotion_price'] < $goods_base['goods_base']['goods_price']) {
            if ($goods_base['goods_base']['groupbuy_starttime'] < date('Y-m-d H:i:s') && $goods_base['goods_base']['groupbuy_endtime'] > date('Y-m-d H:i:s')) {
                $goods_base['goods_base']['old_price'] = $goods_base['goods_base']['goods_price'];
                $goods_base['goods_base']['now_price'] = $goods_base['goods_base']['promotion_price'];
                $goods_base['goods_base']['down_price'] = $goods_base['goods_base']['down_price'];
            }
        }

        //商品总价格
        $goods_base['goods_base']['sumprice'] = number_format($goods_base['goods_base']['now_price'] * $num, 2, '.', '');
        //商品的交易佣金
        $Goods_CatModel = new Goods_CatModel();
        $Shop_ClassBindModel = new Shop_ClassBindModel();
        $goods_cat = $Shop_ClassBindModel->getByWhere(array('shop_id' => $goods_base['goods_base']['shop_id'], 'product_class_id' => $goods_base['goods_base']['cat_id']));
        if ($goods_cat) {
            $goods_cat = current($goods_cat);
            $cat_commission = $goods_cat['commission_rate'];
        } else {
            $goods_cat = $Goods_CatModel->getOne($goods_base['goods_base']['cat_id']);
            if ($goods_cat) {
                $cat_commission = $goods_cat['cat_commission'];
            } else {
                $cat_commission = 0;
            }
        }

        $goods_base['goods_base']['cat_commission'] = $cat_commission;
        $goods_base['goods_base']['commission'] = number_format(($goods_base['goods_base']['sumprice'] * $cat_commission / 100), 2, '.', '');

        return $goods_base;
    }

	/**
	 * 购物车数据
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCardList($cond_row = array(), $order_row = array(),$is_discount=false,$chain_id=0)
	{
		$user_id  = Perm::$row['user_id'];
		$cart_row = $this->getByWhere($cond_row, $order_row);
		$Goods_BaseModel   = new Goods_BaseModel();
		$Shop_BaseModel    = new Shop_BaseModel();
		$Order_GoodsModel  = new Order_GoodsModel();
		$Goods_CatModel    = new Goods_CatModel();
		$data = array();
		//判断商品库存，商品状态，商品审核，店铺状态。 将无效的商品从购物车中删除
		if($chain_id <= 0)
		{
			foreach ($cart_row as $key => $val)
			{

				$goods_base = $Goods_BaseModel->checkGoods($val['goods_id']);

				if (!$goods_base)
				{
					//若该商品状态为无效，则删除该购物车商品
					$this->removeCart($cart_row[$key]);

					unset($cart_row[$key]);
				}
			}
		}
		//门店商品需要进行第二次检验
		if($chain_id)
		{
			$cart_row = array_values($cart_row);

			$goods_id = $cart_row[0]['goods_id'];
			$num = $cart_row[0]['goods_num'];
			$chain_goods_base = $Goods_BaseModel->isValidChainGoods($goods_id, $num, $chain_id);
			if(!is_array($chain_goods_base))
			{
				$cart_row = array();
			}
		}

		//循环有效的购物车数组
		foreach ($cart_row as $key => $val)
		{
			$shop_base  = array();
			$goods_base = array();
			//获取商品信息
			$goods_base = $Goods_BaseModel->getGoodsInfo($val['goods_id']);

			//商品的售卖区域
			$val['transport_area_id'] = $goods_base['common_base']['transport_area_id'];
			$val['buy_able'] = 1;
			//商品重量
			$val['cubage'] = $goods_base['common_base']['common_cubage'];

			//计算商品库存，如果商品库存小于当前购物车中的商品数量，则将购物车中的商品数量改为商品库存
			if ($goods_base['goods_base']['goods_stock'] < $val['goods_num'])
			{
				$val['goods_num'] = $goods_base['goods_base']['goods_stock'];
				//将cart中的商品数量修改为商品库存
				$edit_cart_row['goods_num'] = $val['goods_num'];
				$this->editCart($val['cart_id'],$edit_cart_row);
			}

            $val['old_price']  = 0;
            $val['now_price']  = $goods_base['goods_base']['goods_price'];
            $val['down_price'] = 0;


			$IsHaveBuy = 0;
			if ($user_id)
			{
				//团购商品是否已经开始
				//查询该用户是否已购买过该商品
				$order_goods_cond['common_id']             = $goods_base['goods_base']['common_id'];
				$order_goods_cond['buyer_user_id']         = $user_id;
				$order_goods_cond['order_goods_status:!='] = Order_StateModel::ORDER_REFUND_FINISH;
				$order_list                                = $Order_GoodsModel->getByWhere($order_goods_cond);

				$order_goods_count        = count($order_list);
				$val['order_goods_count'] = $order_goods_count;
				$promotion = 0;  //用于记录店铺中是否存在活动商品

				if (isset($goods_base['goods_base']['promotion_type']) && $goods_base['goods_base']['promotion_type'])
				{
					if ($goods_base['goods_base']['groupbuy_starttime'] < date('Y-m-d H:i:s') && $goods_base['goods_base']['groupbuy_endtime'] > date('Y-m-d H:i:s'))
					{
						//检测是否限购数量
						$upper_limit = $goods_base['goods_base']['upper_limit'];
						if ($upper_limit > 0 && $order_goods_count >= $upper_limit)
						{
							$IsHaveBuy = 1;
						}
                        $val['old_price']  = $goods_base['goods_base']['goods_price'];
                        $val['now_price']  = $goods_base['goods_base']['promotion_price'];
                        $val['down_price'] = $goods_base['goods_base']['down_price'];
					}

					$promotion= 1;
				}
                
				//商品限购数量判断
				if ($goods_base['common_base']['common_limit'] > 0 && $order_goods_count >= $goods_base['common_base']['common_limit'])
				{
					$IsHaveBuy = 1;
				}

				$val['IsHaveBuy'] = $IsHaveBuy;
			}

			//计算商品购买数量
			//计算限购数量
			if (isset($goods_base['goods_base']['upper_limit']))
			{
				if ($goods_base['goods_base']['upper_limit'] && $goods_base['common_base']['common_limit'])
				{
					if ($goods_base['goods_base']['upper_limit'] >= $goods_base['common_base']['common_limit'])
					{
						$val['buy_limit'] = $goods_base['common_base']['common_limit'];
					}
					else
					{
						$val['buy_limit'] = $goods_base['goods_base']['upper_limit'];
					}
				}
				elseif ($goods_base['goods_base']['upper_limit'] && !$goods_base['common_base']['common_limit'])
				{
					$val['buy_limit'] = $goods_base['goods_base']['upper_limit'];
				}
				elseif (!$goods_base['goods_base']['upper_limit'] && $goods_base['common_base']['common_limit'])
				{
					$val['buy_limit'] = $goods_base['common_base']['common_limit'];
				}
				else
				{
					$val['buy_limit'] = 0;
				}
			}
			else
			{
				$val['buy_limit'] = $goods_base['common_base']['common_limit'];
			}

			//有限购数量且仍可以购买，计算还可购买的数量
			if ($val['buy_limit'] && !$IsHaveBuy)
			{
				$val['buy_residue'] = $val['buy_limit'] - $order_goods_count;
			}

			//商品总价格
			$val['sumprice'] = number_format($val['now_price'] * $val['goods_num'], 2, '.', '');
			
			//如果是分销商购买的供货商的商品，计算折扣
			if(Web_ConfigModel::value('Plugin_Distribution') && Perm::$shopId)
			{
				$shopDistributorModel = new Distribution_ShopDistributorModel();
				$shopDistributorLevelModel = new Distribution_ShopDistributorLevelModel();
				
				//所有供货商，用于对商品操作的判断
				$suppliers = $shopDistributorModel->getByWhere(array('distributor_id' =>Perm::$shopId));
				$suppliers  = array_column($suppliers,'shop_id');
				
				//查看折扣，改变对应供销商商品显示的价格
				$shopDistributorInfo     =  $shopDistributorModel->getOneByWhere(array('shop_id' =>$val['shop_id'],'distributor_id'=>Perm::$shopId));				
				if(!empty($shopDistributorInfo) && $shopDistributorInfo['distributor_enable'] == 1){
					$distritutor_rate_info     = $shopDistributorLevelModel->getOne($shopDistributorInfo['distributor_level_id']);
					if(!empty($distritutor_rate_info) && $distritutor_rate_info['distributor_leve_discount_rate']){
						$val['now_price'] = $val['now_price']*$distritutor_rate_info['distributor_leve_discount_rate']/100;
						$distritutor_rate = $val['sumprice'] - number_format($val['now_price'] * $val['goods_num'], 2, '.', '');
						$val['sumprice'] -= $distritutor_rate;
						$val['rate_price']  = $distritutor_rate;
					}
				}
			}
			
			//该商品的交易佣金计算
			$Shop_ClassBindModel = new Shop_ClassBindModel();
			$goods_cat = $Shop_ClassBindModel->getByWhere(array('shop_id'=>$val['shop_id'],'product_class_id'=>$goods_base['goods_base']['cat_id']));
			if($goods_cat)
			{
				$goods_cat = current($goods_cat);
				$cat_commission = $goods_cat['commission_rate'];
			}
			else
			{
				$goods_cat = $Goods_CatModel->getOne($goods_base['goods_base']['cat_id']);
				if ($goods_cat)
				{
					$cat_commission = $goods_cat['cat_commission'];
				}
				else
				{
					$cat_commission = 0;
				}
			}

			$val['cat_commission'] = $cat_commission;
			$val['commission'] = number_format(($val['sumprice'] * $cat_commission / 100), 2, '.', '');
			
			//分佣开启，并且参与分佣
			$val['directseller_flag'] = 0;
			if(Web_ConfigModel::value('Plugin_Directseller')&&$goods_base['common_base']['common_is_directseller'])
			{
				$Distribution_ShopDirectsellerModel = new Distribution_ShopDirectsellerModel();
				$directseller_commission = 0;

				//获取该用户直属上三级用户
				$User_InfoModel = new User_InfoModel();
				$user_parent = $User_InfoModel->getUserPatents($user_id);

				//用户存在分销上级，并且这件商品是分销上级分销的商品则产生相应的分销佣金
				$val['directseller_commission_0'] = 0;
				$val['directseller_flag_0'] = 0;

				if($user_parent['user_parent_0'])
				{
					//一级分佣
					$a = $Distribution_ShopDirectsellerModel->checkDirectsellerGoods($user_parent['user_parent_0'],$goods_base['common_base']['shop_id']);
					if($a)
					{
						$val['directseller_commission_0'] =  number_format(($val['sumprice']*$goods_base['common_base']['common_cps_rate']/100), 2, '.', '');
						$val['directseller_flag_0'] = 1;
						$val['directseller_flag'] = $goods_base['common_base']['common_is_directseller'];
					}

				}

				$val['directseller_commission_1']=0;
				$val['directseller_flag_1'] = 0;
				if($user_parent['user_parent_1'])
				{
					//二级分佣
					$b = $Distribution_ShopDirectsellerModel->checkDirectsellerGoods($user_parent['user_parent_1'],$goods_base['common_base']['shop_id']);
					if($b)
					{
						$val['directseller_commission_1'] = number_format(($val['sumprice']*$goods_base['common_base']['common_second_cps_rate']/100), 2, '.', '');
						$val['directseller_flag_1'] = 1;
						$val['directseller_flag'] = $goods_base['common_base']['common_is_directseller'];
					}

				}
				
				$val['directseller_commission_2']=0;
				$val['directseller_flag_2'] = 0;
				if($user_parent['user_parent_2'])
				{
					//三级分佣
					$c = $Distribution_ShopDirectsellerModel->checkDirectsellerGoods($user_parent['user_parent_2'],$goods_base['common_base']['shop_id']);
					if($c)
					{
						$val['directseller_commission_2'] = number_format(($val['sumprice']*$goods_base['common_base']['common_third_cps_rate']/100), 2, '.', '');
						$val['directseller_flag_2'] = 1;
						$val['directseller_flag'] = $goods_base['common_base']['common_is_directseller'];
					}

				}

				$directseller_commission += $val['directseller_commission_0'] + $val['directseller_commission_1'] + $val['directseller_commission_2'];
			}

			$val['goods_base']  = $goods_base['goods_base'];
			$val['common_base'] = $goods_base['common_base'];
			if (!array_key_exists($val['shop_id'], $data))
			{
				//获取店铺信息
				$shop_base = $Shop_BaseModel->getOne($val['shop_id']);

				$data[$val['shop_id']]['shop_id']        = $shop_base['shop_id'];
				$data[$val['shop_id']]['shop_name']      = $shop_base['shop_name'];
				$data[$val['shop_id']]['shop_user_id']   = $shop_base['user_id'];
				$data[$val['shop_id']]['shop_user_name'] = $shop_base['user_name'];
                $data[$val['shop_id']]['district_id'] = $shop_base['district_id'];
                $data[$val['shop_id']]['promotion'] = $promotion;
				$data[$val['shop_id']]['shop_self_support'] = $shop_base['shop_self_support'];   //店铺是否自营  true 自营 false 非自营
				$data[$val['shop_id']]['goods'][]        = $val;
			}
			else
			{
				$data[$val['shop_id']]['goods'][] = $val;
			}

			if(isset($data[$val['shop_id']]['promotion']) && $data[$val['shop_id']]['promotion'] == 0)
			{
				$data[$val['shop_id']]['promotion'] = $promotion;
			}

			if (isset($data[$val['shop_id']]['sprice']))
			{
				//店铺总价
				$data[$val['shop_id']]['sprice'] = str_replace(',', '', $data[$val['shop_id']]['sprice']) * 1;
				$val['sumprice']                 = str_replace(',', '', $val['sumprice']) * 1;

				$data[$val['shop_id']]['sprice'] += $val['sumprice'];

				//店铺佣金
				$data[$val['shop_id']]['commission'] = str_replace(',', '', $data[$val['shop_id']]['commission']) * 1;
				$val['commission']                   = str_replace(',', '', $val['commission']) * 1;

				$data[$val['shop_id']]['commission'] += $val['commission'];
			}
			else
			{
				$data[$val['shop_id']]['sprice']     = $val['sumprice'];
				$data[$val['shop_id']]['commission'] = $val['commission'];
			}
			
			//分销商折扣
			if(isset($distritutor_rate)){
				if(isset($data[$val['shop_id']]['distributor_rate'])){
					$data[$val['shop_id']]['distributor_rate']  += $distritutor_rate;
				}else{
					$data[$val['shop_id']]['distributor_rate'] = 0;
					$data[$val['shop_id']]['distributor_rate']  += $distritutor_rate;
				}	
			}
			
			$data[$val['shop_id']]['sprice']     = number_format($data[$val['shop_id']]['sprice'] * 1, 2, '.', '');
			$data[$val['shop_id']]['commission'] = number_format($data[$val['shop_id']]['commission'] * 1, 2, '.', '');

			if($val['directseller_flag'])
			{
				$data[$val['shop_id']]['directseller_flag'] = $val['directseller_flag'];
			}

			if($val['directseller_flag_0'])
			{
				$data[$val['shop_id']]['directseller_flag_0'] = $val['directseller_flag_0'];
			}
			if($val['directseller_flag_1'])
			{
				$data[$val['shop_id']]['directseller_flag_1'] = $val['directseller_flag_1'];
			}
			if($val['directseller_flag_2'])
			{
				$data[$val['shop_id']]['directseller_flag_2'] = $val['directseller_flag_2'];
			}
		}

		$Voucher_BaseModel = new Voucher_BaseModel();
		$Promotion         = new Promotion();
		foreach ($data as $key => $val)
		{
			$data[$val['shop_id']]['ini_sprice'] = $data[$val['shop_id']]['sprice'];

			//如果是门店商品则不需要考虑满送和加价购活动
			$mansong_info = array();
			$increase_info = array();
			if(!$chain_id)
			{
				/*  店铺满即送  */
				$mansong_info = $Promotion->getShopOrderGift($val['shop_id'], $val['sprice']);

				if ($mansong_info)
				{
					if (isset($mansong_info['gift_goods_id']))
					{
						$goods_base = $Goods_BaseModel->checkGoods($mansong_info['gift_goods_id']);
						if (!$goods_base )
						{
							$mansong_info['gift_goods_id'] = 0;
						}elseif ($goods_base['goods_base']['is_del'] == Goods_BaseModel::IS_DEL_YES){
                            $mansong_info['gift_goods_id'] = 0;
                        }else
						{
							$mansong_info['goods_name']  = $goods_base['goods_base']['goods_name'];
							$mansong_info['goods_image'] = $goods_base['goods_base']['goods_image'];
							$mansong_info['common_id']   = $goods_base['goods_base']['common_id'];

						}
					}

					if (!$mansong_info['gift_goods_id'] && !$mansong_info['rule_discount'])
					{
						$mansong_info = array();
					}

				}

				//计算满减后的金额
				if (isset($mansong_info['rule_discount']) && $mansong_info['rule_discount'])
				{
					$data[$val['shop_id']]['sprice'] = number_format(($data[$val['shop_id']]['sprice'] - $mansong_info['rule_discount']),2,".","");
					$val['sprice'] = $data[$val['shop_id']]['sprice'];
				}

				/*  加价购  */
				$increase_info = $Promotion->getOrderIncreaseInfo($val);

				//去除加价购商品中没有库存和不存在的商品，若是该活动下没有有效商品则去除该活动
				foreach ($increase_info as $inckey => $incval)
				{
					if (!empty($incval['exc_goods']))
					{
						foreach ($incval['exc_goods'] as $excgkey => $excgval)
						{
							$goods_base = $Goods_BaseModel->checkGoods($excgval['goods_id']);

							if (!$goods_base)
							{
								unset($incval['exc_goods'][$excgkey]);
								unset($increase_info[$inckey]['exc_goods'][$excgkey]);
							}
							else
							{
								$increase_info[$inckey]['exc_goods'][$excgkey]['goods_name']  = $goods_base['goods_base']['goods_name'];
								$increase_info[$inckey]['exc_goods'][$excgkey]['goods_image'] = $goods_base['goods_base']['goods_image'];
							}
						}

						if (empty($incval['exc_goods']))
						{
							unset($increase_info[$inckey]);
						}
					}
					else
					{
						unset($increase_info[$inckey]);
					}
				}
			}

			$data[$val['shop_id']]['mansong_info'] = $mansong_info;
			$data[$key]['increase_info'] = $increase_info;

			//如果开启了会员折扣，就不在计算代金券
			$data[$key]['best_voucher'] = array();
			$data[$key]['voucher_base'] = array();
			if(!$is_discount)
			{
				//选出用户拥有的该店铺优惠券中最优的代金券
				$best_voucher = Voucher::user(['shop_id'=>$val['shop_id'],'voucher_end_date'=>get_date_time(),'voucher_start_date'=>get_date_time(),'limit'=>1,'order_price'=>$val['sprice'],'order_by'=>'voucher_price DESC','voucher_state'=>Voucher_BaseModel::UNUSED]);
				$data[$key]['best_voucher'] = $best_voucher;

				//店铺代金券(将用户拥有的该店铺的所有代金券都查询出来)
				$user_voucher = Voucher::user(['shop_id'=>$val['shop_id'],'order_by'=>'voucher_price ASC,voucher_end_date ASC','voucher_state'=>Voucher_BaseModel::UNUSED.','.Voucher_BaseModel::EXPIRED]);
				$user_voucher = Voucher::isable(['data'=>$user_voucher,'order_price'=>$val['sprice']]);
				$data[$key]['voucher_base'] = $user_voucher;
			}

			//获取该店铺可领取的代金券
			$shop_base = Voucher::shop(['shop_id'=>$val['shop_id'],'end_date'=>get_date_time(),'voucher_state'=>Voucher_BaseModel::UNUSED]);

			$data[$key]['shop_voucher'] = $shop_base;
		}

		$data['count'] = count($cart_row);
		return $data;
	}


	/**
	 * 首页侧边栏中获取购物车中的数据
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCardListInIndex($cond_row = array(), $order_row = array())
	{
		$user_id  = Perm::$row['user_id'];
		$cart_row = $this->getByWhere($cond_row, $order_row);

		$Goods_BaseModel   = new Goods_BaseModel();
		$Shop_BaseModel    = new Shop_BaseModel();
		$Goods_CommonModel = new Goods_CommonModel();
		$Promotion         = new Promotion();
		$Order_GoodsModel  = new Order_GoodsModel();
		$Goods_CatModel    = new Goods_CatModel();


		$data = array();

		//判断商品库存，商品状态，商品审核，店铺状态。 将无效的商品从购物车中删除
		foreach ($cart_row as $key => $val)
		{

			$goods_base = $Goods_BaseModel->checkGoods($val['goods_id']);

			if (!$goods_base)
			{
				unset($cart_row[$key]);
			}

		}

		//$data['count'] = count($cart_row);
		$count = count($cart_row);

		foreach ($cart_row as $key => $val)
		{
			$shop_base  = array();
			$goods_base = array();
			//获取商品信息
			$goods_base = $Goods_BaseModel->getGoodsInfo($val['goods_id']);
			fb($goods_base);

			//商品重量
			$val['cubage'] = $goods_base['common_base']['common_cubage'];

			//计算商品库存
			if ($goods_base['goods_base']['goods_stock'] < $val['goods_num'])
			{
				$val['goods_num'] = $goods_base['goods_base']['goods_stock'];
			}


			//计算商品价格
			if (isset($goods_base['goods_base']['promotion_price']) && !empty($goods_base['goods_base']['promotion_price']) && $goods_base['goods_base']['promotion_price'] < $goods_base['goods_base']['goods_price'])
			{
				$val['old_price']  = $goods_base['goods_base']['goods_price'];
				$val['now_price']  = $goods_base['goods_base']['promotion_price'];
				$val['down_price'] = $goods_base['goods_base']['down_price'];
			}
			else
			{
				$val['old_price']  = 0;
				$val['now_price']  = $goods_base['goods_base']['goods_price'];
				$val['down_price'] = 0;
			}

			$IsHaveBuy = 0;
			if ($user_id)
			{
				//团购商品是否已经开始
				//查询该用户是否已购买过该商品
				$order_goods_cond['common_id']             = $goods_base['goods_base']['common_id'];
				$order_goods_cond['buyer_user_id']         = $user_id;
				$order_goods_cond['order_goods_status:!='] = Order_StateModel::ORDER_REFUND_FINISH;
				$order_list                                = $Order_GoodsModel->getByWhere($order_goods_cond);

				$order_goods_count        = count($order_list);
				$val['order_goods_count'] = $order_goods_count;

				if (isset($goods_base['goods_base']['promotion_type']))
				{
					$promotion_type = $goods_base['goods_base']['promotion_type'];

					if ($promotion_type == 'groupbuy')
					{
						//检测是否限购数量
						$upper_limit = $goods_base['goods_base']['upper_limit'];
						if ($upper_limit > 0 && $order_goods_count >= $upper_limit)
						{
							$IsHaveBuy = 1;
						}
					}
				}


				//商品限购数量判断
				if ($goods_base['common_base']['common_limit'] > 0 && $order_goods_count >= $goods_base['common_base']['common_limit'])
				{
					$IsHaveBuy = 1;
				}

				fb($IsHaveBuy);
				$val['IsHaveBuy'] = $IsHaveBuy;
			}
			fb($IsHaveBuy);
			fb('购买权限');

			//计算商品购买数量
			//计算限购数量
			if (isset($goods_base['goods_base']['upper_limit']))
			{
				if ($goods_base['goods_base']['upper_limit'] && $goods_base['common_base']['common_limit'])
				{
					if ($goods_base['goods_base']['upper_limit'] >= $goods_base['common_base']['common_limit'])
					{
						$val['buy_limit'] = $goods_base['common_base']['common_limit'];
					}
					else
					{
						$val['buy_limit'] = $goods_base['goods_base']['upper_limit'];
					}
				}
				elseif ($goods_base['goods_base']['upper_limit'] && !$goods_base['common_base']['common_limit'])
				{
					$val['buy_limit'] = $goods_base['goods_base']['upper_limit'];
				}
				elseif (!$goods_base['goods_base']['upper_limit'] && $goods_base['common_base']['common_limit'])
				{
					$val['buy_limit'] = $goods_base['common_base']['common_limit'];
				}
				else
				{
					$val['buy_limit'] = 0;
				}
			}
			else
			{
				$val['buy_limit'] = $goods_base['common_base']['common_limit'];
			}

			//有限购数量且仍可以购买，计算还可购买的数量
			if ($val['buy_limit'] && !$IsHaveBuy)
			{
				$val['buy_residue'] = $val['buy_limit'] - $order_goods_count;
			}

			//商品总价格
			$val['sumprice'] = number_format($val['now_price'] * $val['goods_num'], 2, '.', '');
			//该商品的交易佣金计算
			$goods_cat = $Goods_CatModel->getOne($goods_base['goods_base']['cat_id']);
			if ($goods_cat)
			{
				$cat_commission = $goods_cat['cat_commission'];
			}
			else
			{
				$cat_commission = 0;
			}
			$val['commission'] = number_format(($val['sumprice'] * $cat_commission / 100), 2, '.', '');


			$val['goods_base']  = $goods_base['goods_base'];
			$val['common_base'] = $goods_base['common_base'];
			if (!array_key_exists($val['shop_id'], $data))
			{
				//获取店铺信息
				$shop_base = $Shop_BaseModel->getOne($val['shop_id']);

				$data[$val['shop_id']]['shop_id']        = $shop_base['shop_id'];
				$data[$val['shop_id']]['shop_name']      = $shop_base['shop_name'];
				$data[$val['shop_id']]['shop_user_id']   = $shop_base['user_id'];
				$data[$val['shop_id']]['shop_user_name'] = $shop_base['user_name'];
				$data[$val['shop_id']]['goods'][]        = $val;
			}
			else
			{
				$data[$val['shop_id']]['goods'][] = $val;
			}

			fb($val);
			fb("商品信息");

			if (isset($data[$val['shop_id']]['sprice']))
			{
				//店铺总价
				$data[$val['shop_id']]['sprice'] = str_replace(',', '', $data[$val['shop_id']]['sprice']) * 1;
				$val['sumprice']                 = str_replace(',', '', $val['sumprice']) * 1;

				$data[$val['shop_id']]['sprice'] += $val['sumprice'];

				//店铺佣金
				$data[$val['shop_id']]['commission'] = str_replace(',', '', $data[$val['shop_id']]['commission']) * 1;
				$val['commission']                   = str_replace(',', '', $val['commission']) * 1;

				$data[$val['shop_id']]['commission'] += $val['commission'];
			}
			else
			{
				$data[$val['shop_id']]['sprice']     = $val['sumprice'];
				$data[$val['shop_id']]['commission'] = $val['commission'];
			}

			$data[$val['shop_id']]['sprice']     = number_format($data[$val['shop_id']]['sprice'] * 1, 2, '.', '');
			$data[$val['shop_id']]['commission'] = number_format($data[$val['shop_id']]['commission'] * 1, 2, '.', '');
		}


		foreach ($data as $key => $val)
		{
			$Promotion = new Promotion();

			//店铺满送活动
			$mansong_info = $Promotion->getShopOrderGift($val['shop_id'], $val['sprice']);

			fb($mansong_info);
			fb("满送1");
			if ($mansong_info)
			{
				if (isset($mansong_info['gift_goods_id']))
				{
					$goods_base = $Goods_BaseModel->checkGoods($mansong_info['gift_goods_id']);
					if (!$goods_base)
					{
						$mansong_info['gift_goods_id'] = 0;
					}
					else
					{
						$mansong_info['goods_name']  = $goods_base['goods_base']['goods_name'];
						$mansong_info['goods_image'] = $goods_base['goods_base']['goods_image'];
						$mansong_info['common_id']   = $goods_base['goods_base']['common_id'];
					}
				}

				if (!$mansong_info['gift_goods_id'] && !$mansong_info['rule_discount'])
				{
					$mansong_info = array();
				}
			}

			if (isset($mansong_info['rule_discount']) && $mansong_info['rule_discount'])
			{
				$data[$val['shop_id']]['sprice'] = $data[$val['shop_id']]['sprice'] - $mansong_info['rule_discount'];
			}
			$data[$val['shop_id']]['mansong_info'] = $mansong_info;
			fb($mansong_info);
			fb('满送');

			$increase_info = $Promotion->getOrderIncreaseInfo($val);

			//去除加价购商品中没有库存和不存在的商品，若是改活动下没有有效商品则去除该活动
			foreach ($increase_info as $inckey => $incval)
			{
				if (!empty($incval['exc_goods']))
				{
					foreach ($incval['exc_goods'] as $excgkey => $excgval)
					{
						$goods_base = $Goods_BaseModel->checkGoods($excgval['goods_id']);
						if (!$goods_base)
						{
							unset($incval['exc_goods'][$excgkey]);
							unset($increase_info[$inckey]['exc_goods'][$excgkey]);
						}
						else
						{
							$increase_info[$inckey]['exc_goods'][$excgkey]['goods_name']  = $goods_base['goods_base']['goods_name'];
							$increase_info[$inckey]['exc_goods'][$excgkey]['goods_image'] = $goods_base['goods_base']['goods_image'];
						}
					}

					if (empty($incval['exc_goods']))
					{
						unset($increase_info[$inckey]);
					}
				}
				else
				{
					unset($increase_info[$inckey]);
				}
			}

			$data[$key]['increase_info'] = $increase_info;
			fb($increase_info);
			fb("加价购信息");
		}

		$data['count'] = count($cart_row);

		fb($data);
		fb("购物详细列表");
		return $data;
	}

	public function getCartGoodPrice($cart_id = null)
	{
		$cart_base = $this->getOne($cart_id);

		$Goods_BaseModel = new Goods_BaseModel();
		$Discount_GoodsModel = new Discount_GoodsModel();
		$GroupBuy_BaseModel = new GroupBuy_BaseModel();
        $con_row['goods_id'] = $cart_base['goods_id'];
        $con_row['goods_start_time:<='] = date('Y-m-d H:i:s');
        $con_row['goods_end_time:>='] = date('Y-m-d H:i:s');
		$goods_data = $Discount_GoodsModel->getOneByWhere($con_row);
		
		//如果是显示折扣商品，则返回折扣后的总价
		if($goods_data)
		{
			$price = $cart_base['goods_num'] * $goods_data['discount_price'];
		}
		else
		{
            $con['goods_id'] = $cart_base['goods_id'];
            $con['groupbuy_starttime:<='] = date('Y-m-d H:i:s');
            $con['groupbuy_endtime:>='] = date('Y-m-d H:i:s');
			$group_data = $GroupBuy_BaseModel->getOneByWhere($con);
			//判断是否是团购商品
			if($group_data)
			{
				$price = $cart_base['goods_num'] * $group_data['groupbuy_price'];
			}
			else
			{
				$goods_base = $Goods_BaseModel->getOne($cart_base['goods_id']);
				//计算商品的活动价格
				$price = $cart_base['goods_num'] * $goods_base['goods_price'];
			}
		}
//		echo '<pre>';print_r($data);exit;
		

		return $price;
	}


	//计算购物车中的商品数量
	public function getCartGoodsNum($cond_row = array(), $order_row = array())
	{
		$user_id = $cond_row['user_id'];
		$sql = '
			SELECT
				COUNT(cart_id) num
			FROM ' . $this->_tableName . '
			WHERE 1 AND user_id = ' . $user_id . '
		';

		$data = $this->sql->getRow($sql);

		return isset($data['num']) ? $data['num'] : 0;
	}

	//将cookie中的购物车信息插入用户中
	public function updateCookieCart($user_id = null)
	{
		$cart_list = $_COOKIE['goods_cart'];

		$cart_list = explode('|',$cart_list);

		foreach($cart_list as $key => $val)
		{
			$val = explode(',',$val);
			if(count($val) > 1)
			{
				//将商品id与数量添加到购物车表中
				$this->updateCart($user_id,$val[0],$val[1]);
			}
		}
	}


	public function updateCart($user_id=null, $goods_id=null, $goods_num=null)
	{
		if ($goods_id)
		{
			//查找商品的shop_id
			$Goods_BaseModel = new Goods_BaseModel();
			$goods_base      = $Goods_BaseModel->getOne($goods_id);

			//查找店铺主人
			$Shop_BaseModel = new Shop_BaseModel();
			$shop = $Shop_BaseModel->getOne($goods_base['shop_id']);
			if($shop['user_id'] == $user_id)
			{
				return false;
			}

			//判断该件商品是否为虚拟商品，若为虚拟商品则加入购物车失败
			$Goods_CommonModel = new Goods_CommonModel();
			$common_base = $Goods_CommonModel->getOne($goods_base['common_id']);
			if($common_base['common_is_virtual'] != $Goods_CommonModel::GOODS_VIRTUAL)
			{
				$shop_id = $goods_base['shop_id'];

				//判断购物车中是否存在该商品
				$cart_cond             = array();
				$cart_cond['user_id']  = $user_id;
				$cart_cond['shop_id']  = $shop_id;
				$cart_cond['goods_id'] = $goods_id;
				$cart_row              = current($this->getByWhere($cart_cond));
				$msg                   = '';


				//查询该用户是否已购买过该商品
				$Order_GoodsModel = new Order_GoodsModel();
				$order_goods_cond['common_id']             = $goods_base['common_id'];
				$order_goods_cond['buyer_user_id']         = $user_id;
				$order_goods_cond['order_goods_status:!='] = Order_StateModel::ORDER_REFUND_FINISH;
				$order_list                                = $Order_GoodsModel->getByWhere($order_goods_cond);

				$order_goods_count         = count($order_list);

				//如果有限购数量就计算还剩多少可购买的商品数量

				$limit_num = $goods_base['goods_max_sale'];
				if($goods_base['goods_max_sale'])
				{
					$limit_num = $goods_base['goods_max_sale'] - $order_goods_count;
					$limit_num = $limit_num < 0 ? 0:$limit_num;
				}

				if ($cart_row)
				{
					//判断商品的个人购买数
					//if (($cart_row['goods_num'] >= $goods_base['goods_max_sale'] || $cart_row['goods_num'] + $goods_num > $goods_base['goods_max_sale']) && $goods_base['goods_max_sale'] != 0)
					if (($cart_row['goods_num'] >= $limit_num || $cart_row['goods_num'] + $goods_num > $limit_num) && $goods_base['goods_max_sale'] != 0)
					{
						return false;
					}
					else
					{
						$edit_row = array('goods_num' => $goods_num);
						$flag     = $this->editCart($cart_row['cart_id'], $edit_row, true);
					}
					$cart_id = $cart_row['cart_id'];
				}
				else
				{
					$add_row              = array();
					$add_row['user_id']   = $user_id;
					$add_row['shop_id']   = $shop_id;
					$add_row['goods_id']  = $goods_id;
					$add_row['goods_num'] = $goods_num;

					$flag    = $this->addCart($add_row, true);
					$cart_id = $flag;
				}
			}
			else
			{
				return false;
			}


		}
		else
		{
			return false;
		}


		return true;
	}


	/**
     * @auth yuli
	 * @param $goods_id
	 * @param $goods_num
	 * @return boolean
	 * 判断购物车商品是否可以添加
	 */
	public function checkCartGoodsLimits($goods_id, $goods_num)
	{
		$goodsCommonModel = new Goods_CommonModel();
		$limit_data = $goodsCommonModel->checkGoodsPurchaseLimit($goods_id, $goods_num);

		//当前商品没有开启限购
		if (!$limit_data['open_limit']) {
			return true;
		}

		//当前商品开启限购，但不满足限购条件，已经购买数量+当前请求数量>限购数量
		if (!$limit_data['valid_status']) {
			return false;
		}



		//已经购买数量+当前请求数量<限购数量
		//获取购物车商品数量
		//判断: 已经购买数量+当前请求数量+购物车内数量>限购数量
		//需求现改为购物车内的商品与立即购买的商品数不累加

//		$common_id = $limit_data['common_id'];
//		$valid_num = $limit_data['valid_num']; //还可以购买有效数量
//		$cart_goods_num = $this->getCartGoodsNumByCommonId($common_id); //购物车内数量

//		return $cart_goods_num + $goods_num > $valid_num
//			? false
//			: true;
		
		return $goods_num > $limit_data['limit_num']
			? false
			: true;
	}

	/**
	 * @param $common_id
	 * @return int|number
	 * 根据common_id获取商品数量
	 */
	public function getCartGoodsNumByCommonId($common_id)
	{
		$goodsBaseModel = new Goods_BaseModel;
		$goods_ids = $goodsBaseModel->getGoodsIdByCommonId($common_id);

        $condi = [
            'user_id'=> Perm::$userId,
            'goods_id:IN'=> $goods_ids
        ];
		$rows = $this->getByWhere($condi);

		return empty($rows)
			? 0
			: array_sum(array_column($rows, 'goods_num'));
	}


    /*
     * 判断购物车提交数据是否与购物车相同
     * @param $goods_row，$list
     *
     */
    public function getCatGoods_stock($goods_row,$list){
        $Goods_BaseModel = new Goods_BaseModel();
        $goods_row_list['goods_id:IN'] = $goods_row;
        $goods_list = $Goods_BaseModel->getByWhere($goods_row_list);
        $data = array();

        if($goods_list){
            foreach($goods_list as $k=>$v){
                if($v['goods_stock'] == 0 || $list[$v['goods_id']] > $v['goods_stock']){

                    $data['state'] = false;
                    $data['msg'] = $v['goods_name']. __('库存不足');
                    return $data;
                }elseif($v['is_del'] == Goods_BaseModel::IS_DEL_YES){
                    $data['state'] = false;
                    $data['msg'] = $v['goods_name'].__('商品已被商户删除，请编辑后提交');
                    return $data;
                }
            }
        }
        $data['state'] = true;
        return $data;

    }

    /*
     * 查询促销活动，判断当前购物车添加产品的数目是否符合活动条件
     *  @param $goods_row，$list
     */
    public function getDiscount_Goods($goods_row,$list){
        $Discount_GoodsModel = new Discount_GoodsModel();
        $con['goods_id:IN'] = $goods_row;
        $con['discount_goods_state'] = Discount_GoodsModel::NORMAL;
        $con['goods_start_time:<'] = date('Y-m-d H:i:s');
        $con['goods_end_time:>'] = date('Y-m-d H:i:s');
        $data = array();
        $discount_list = $Discount_GoodsModel->getByWhere($con);

        if($discount_list){
            foreach($discount_list as $k=>$v){
                if(intval($v['goods_lower_limit']) != 0 && intval($v['goods_lower_limit']) > intval($list[$v['goods_id']])){
                    $data['state'] = false;
                    $data['msg'] = $v['goods_name']. __('为限制折扣活动，最低购买').$v['goods_lower_limit'].__('件!');
                    return $data;
                }
            }
        }
        $data['state'] = true;
        return $data;
    }
    
    
    /*
     * 根据当前购物车产品id判断当前用户购买此产品总数 
     * @param $common_id
	 * @return array()
     */
    public function getOrderNums($goods_row){
        $Order_GoodsModel = new Order_GoodsModel();
        $in = implode(',',$goods_row);
        $buyer_user_id = Perm::$row['user_id'];
        $order_goods_status = Order_StateModel::ORDER_CANCEL;
        $arr = array();
        $sql = "SELECT goods_id,SUM(order_goods_num) nums from `yf_order_goods` where buyer_user_id='".$buyer_user_id."' and goods_id in('".$in."') and order_goods_status !='".$order_goods_status."' GROUP BY goods_id";

        $order_goods_list = $Order_GoodsModel->sql->getAll($sql);

        if($order_goods_list){
            foreach($order_goods_list as $k=>$v){
                $arr[$v['goods_id']] = $v['nums'];
            }
            return $arr;
        }else{
            return array();
        }
    }


    /*
     *查询团购活动，删除团购商品信息，返回没有参加团购的商品信息
     *
     */
    public function getGroupBuylist($goods_row,$list){
        $Groupbuy_BaseModel = new GroupBuy_BaseModel();
        $Goods_BaseModel = new Goods_BaseModel();
        $cons['goods_id:IN'] = $goods_row;
        $cons['groupbuy_state'] = GroupBuy_BaseModel::NORMAL;
        $cons['groupbuy_starttime:<'] = date('Y-m-d H:i:s');
        $cons['groupbuy_endtime:>'] = date('Y-m-d H:i:s');
        $groupbug_list = $Groupbuy_BaseModel->getByWhere($cons);
        $order_goods_list = $this->getOrderNums($goods_row);

        $data = array();
        if($groupbug_list){

            foreach($groupbug_list as $k=>$v){
                if(intval($v['groupbuy_upper_limit']) > 0 && intval($v['groupbuy_upper_limit']) < intval($list[$v['goods_id']])+intval($order_goods_list[$v['goods_id']])){

                    $data['state'] = false;
                    $data['msg'] = $v['goods_name']. __('为团购活动，最多购买').$v['groupbuy_upper_limit'].__('件!');
                    return $data;
                }
                unset($list[$v['goods_id']]);
            }
        }

        if($list){
            foreach($list as $k=>$v){
                $arr[] = $k;
            }
            $consss['goods_id:IN'] = $arr;
            $result_list = $Goods_BaseModel->getByWhere($consss);

            if($result_list){
                foreach($result_list as $k=>$v){
                    if(intval($v['goods_max_sale']) != 0 && intval($v['goods_max_sale']) < intval($list[$v['goods_id']])+intval($order_goods_list[$v['goods_id']])){
                        $data['state'] = false;
                        $data['msg'] = $v['goods_name']. __('已经超出该商品的单人最大购买数量');
                        return  $data;
                    }
                }
            }
        }
        $data['state'] = true;
        return $data;
    }


    /**
     * WEBPOS门店自提商品
     * @param $goods_id
     * @param $num
     * @param $chain_id
     * @return array
     */
 	public function getWebpos_ChainGoods($goods=array(), $chain_id)
    {
       foreach($goods as $key =>$val){
       	    $goods_id = $val[0];
       	    $num =$val[1];
       	    $Goods_BaseModel = new Goods_BaseModel();
            //验证商品信息
	        $Goods_BaseModel->isValidChainGoods($goods_id, $num, $chain_id);
	        $goods_base['goods'][$key] = $Goods_BaseModel->getWebposGoodsInfo($goods_id);

	        $goods_base['goods'][$key]['goods_base']['old_price'] = 0;
	        $goods_base['goods'][$key]['goods_base']['now_price'] = $goods_base['goods'][$key]['goods_base']['goods_price'];
	        $goods_base['goods'][$key]['goods_base']['down_price'] = 0;
	        $goods_base['goods'][$key]['goods_base']['num'] = $num;
	        

	        //商品总价格
	        $goods_base['goods'][$key]['goods_base']['sumprice'] = number_format($goods_base['goods'][$key]['goods_base']['now_price'] * $num, 2, '.', '');


	        //商品的交易佣金
	        $Goods_CatModel = new Goods_CatModel();
	        $Shop_ClassBindModel = new Shop_ClassBindModel();
	        $goods_cat = $Shop_ClassBindModel->getByWhere(array('shop_id' => $goods_base['goods'][$key]['goods_base']['shop_id'], 'product_class_id' => $goods_base['goods'][$key]['goods_base']['cat_id']));
	        if ($goods_cat) {
	            $goods_cat = current($goods_cat);
	            $cat_commission = $goods_cat['commission_rate'];
	        } else {
	            $goods_cat = $Goods_CatModel->getOne($goods_base['goods'][$key]['goods_base']['cat_id']);
	            if ($goods_cat) {
	                $cat_commission = $goods_cat['cat_commission'];
	            } else {
	                $cat_commission = 0;
	            }
	        }

	        $goods_base['goods'][$key]['goods_base']['cat_commission'] = $cat_commission;
	        $goods_base['goods'][$key]['goods_base']['commission'] = number_format(($goods_base['goods'][$key]['goods_base']['sumprice'] * $cat_commission / 100), 2, '.', '');
       }
        $goods_base['goods_sumprice']=0;
        $goods_base['commission']=0;
        foreach($goods_base['goods'] as $key =>$val)
        {
              $goods_base['goods_sumprice']+= $goods_base['goods'][$key]['goods_base']['sumprice'];
              $goods_base['commission']+= $goods_base['goods'][$key]['goods_base']['commission'];
        }
        
       
        return $goods_base;
    }


}

?>
<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Yf <service@yuanfeng.cn>
 */
class Goods_BaseModel extends Goods_Base
{

	const GOODS_UP   = 1;//上架
	const GOODS_DOWN = 2;//下架
    const IS_DEL_YES = 2;//商品删除
    const IS_DEL_NO = 1;//商品未删除

	/**
	 * 读取分页列表
	 *
	 * @param  int $goods_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
	    $cond_row['is_del'] = self::IS_DEL_NO;
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}


	/**
	 * 读取分页列表
	 *
	 * @param  int $goods_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseListByCommonId($common_id, $order_row = array(), $page = 1, $rows = 100)
	{
		$cond_row = array('common_id' => $common_id);
        $cond_row['is_del'] = self::IS_DEL_NO;
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}


	public function getBaseByCommonId($common_id, $order_row = array(), $page = 1, $rows = 100)
	{
		$cond_row = array('common_id' => $common_id);
        $cond_row['is_del'] = self::IS_DEL_NO;
		return $this->getByWhere($cond_row, $order_row, $page, $rows);
	}

	public function getBaseSpecByCommonId($common_id)
	{
		$cond_row = array('common_id' => $common_id);
        $cond_row['is_del'] = self::IS_DEL_NO;
		$res      = $this->getByWhere($cond_row);
		foreach ($res as $key => $val)
		{
			$data[$key] = current($val['goods_spec']);
		}

		return $data;

	}

	public function getGoodsIdByCommonId($common_id)
	{
		if (is_array($common_id))
		{
			$cond_row = array('common_id:in' => $common_id);

		}
		else
		{
			$cond_row = array('common_id' => $common_id);

		}
        $cond_row['is_del'] = self::IS_DEL_NO;
		return $this->getKeyByWhere($cond_row);
	}
	
	public function getGoodsListByGoodId($goods_id,$is_del_flag = false)
	{
		if (is_array($goods_id))
		{
			$cond_row = array('goods_id:in' => $goods_id);

		}
		else
		{
			$cond_row = array('goods_id' => $goods_id);

		}
		if($is_del_flag == true){
            $cond_row['is_del'] = self::IS_DEL_NO;
        }
		return $this->getByWhere($cond_row);
	}
	
	public function getGoodsDetail($cond_row)
	{
        $cond_row['is_del'] = self::IS_DEL_NO;
		return $this->getOneByWhere($cond_row);
	}

	public function getGoodsDetailss($cond_row)
	{
        $cond_row['is_del'] = self::IS_DEL_NO;
		return $this->getByWhere($cond_row);
	}

	public function getGoodsDetailByGoodId($goods_id)
	{

		return $this->getOne($goods_id);
	}

	/**
	 * 店铺查商品
	 *
	 * @author Zhuyt
	 */
	public function getGoodsListByShopId($shop_id, $order_row, $page, $rows)
	{
		
		if (is_array($shop_id))
		{
			$cond_row = array('shop_id:in' => $shop_id);

		}
		else
		{
			$cond_row = array('shop_id' => $shop_id);

		}
        $cond_row['is_del'] = self::IS_DEL_NO;
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	/**
	 * 删除商品库存
	 *
	 * @author Zhuyt
	 */
	public function delStock($goods_id, $num)
	{
		$goods_base    = $this->getOne($goods_id);
		$edit_base_num = $goods_base['goods_stock'] - $num;

		if($edit_base_num < 0)
		{
			return 'no_stock' ;
		}
		$edit_base_row = array('goods_stock' => $edit_base_num);
		$flag          = $this->editBase($goods_id, $edit_base_row, false);

		if ($flag)
		{
			$Goods_CommonModel = new Goods_CommonModel();
			$common_base       = $Goods_CommonModel->getOne($goods_base['common_id']);

			//查询此件商品是否为团购商品
			$GroupBuy_BaseModel = new GroupBuy_BaseModel();
			$group_buy_row = array(
				'common_id' => $goods_base['common_id'],
				'groupbuy_state'=>GroupBuy_BaseModel::NORMAL,
				'groupbuy_starttime:<=' => get_date_time(),
				'groupbuy_endtime:>=' => get_date_time(),
			);
			$gruop = $GroupBuy_BaseModel->getOneByWhere($group_buy_row);
			if($gruop)
			{
				$edit_gruop_row['groupbuy_buyer_count'] = 1;
				$edit_gruop_row['groupbuy_buy_quantity'] = $num;
				$edit_gruop_row['groupbuy_virtual_quantity'] = $num;
				$GroupBuy_BaseModel->editGroupBuy($gruop['groupbuy_id'],$edit_gruop_row,true);
			}

			$edit_common_num   = $common_base['common_stock'] - $num;
			$edit_common_row   = array('common_stock' => $edit_common_num);
			$resd               = $Goods_CommonModel->editCommon($goods_base['common_id'], $edit_common_row, false);

			$rs_row = array();
			check_rs($resd,$rs_row);
			$res = is_ok($rs_row);

			if($goods_base['goods_alarm'] >= $edit_base_num)
			{
				//查找店铺信息
				$Shop_BaseModel = new Shop_BaseModel();
				$shop_base = $Shop_BaseModel->getOne($common_base['shop_id']);
				$message = new MessageModel();
				$message->sendMessage('goods are not in stock',$shop_base['user_id'], $shop_base['user_name'], $order_id = NULL, $shop_name = NULL, 1, 1, $end_time = Null,$goods_base['common_id'],$goods_id);
			}

		}
		else
		{
			$res = false;
		}


		return $res;

	}

	/**
	 * 返回商品库存(取消订单后根据订单商品id返回商品库存,退款后根据订单商品id返回商品库存)
	 *
	 * @author Zhuyt
	 */
	public function returnGoodsStock($order_goods_id,$return_num=0,$behalf_deliver=0)
	{
		$Order_GoodsModel  = new Order_GoodsModel();
		$Goods_CommonModel = new Goods_CommonModel();
		$rs_row = array();
        $flag = true;

		if (is_array($order_goods_id))
		{
			foreach ($order_goods_id as $key => $val)
			{
				$order_goods_base = $Order_GoodsModel->getOne($val);
				$goods_id         = $order_goods_base['goods_id'];
				$num              = $order_goods_base['order_goods_num'];

				$edit_base_row = array('goods_stock' => $num);
				$result1 = $this->editBase($goods_id, $edit_base_row);
                if($result1 === false){
                    $flag = false;
                    break;
                }
				$edit_common_row = array('common_stock' => $num);
				$result2 = $Goods_CommonModel->editCommonTrue($order_goods_base['common_id'], $edit_common_row);
                if($result2 === false){
                    $flag = false;
                    break;
                }
			}
		}
		else
		{
			$order_goods_base = $Order_GoodsModel->getOne($order_goods_id);

			$num              = $order_goods_base['order_goods_num'];
			$goods_parent_id = 0;

			//退款中有可能退的是代发货的订单，如果是代发货的订单，则需要退还的是供应商的商品库存
			if($behalf_deliver)
			{
				/*退款退货商品是代发商品，需要查找出供应商品的商品id*/
				//根据goods_id查找出goods_parent_id
				$Goods_BaseModel = new Goods_BaseModel();
				$goods = $Goods_BaseModel->getOne($order_goods_base['goods_id']);
				$goods_parent_id         = $goods['goods_parent_id'];
				$goods_parent = $Goods_BaseModel->getOne($goods_parent_id);
			}

			//退款退货商品不是代发商品
			$goods_id         = $order_goods_base['goods_id'];

			if($return_num > 0)
			{
				$num = $return_num;
			}

			$edit_base_row = array('goods_stock' => $num);
			$result1          = $this->editBase($goods_id, $edit_base_row);
			check_rs($result1,$rs_row);

			//如果是代发分销商品需要将供应商品库存返回
			if($goods_parent_id)
			{
				$edit_base_row = array('goods_stock' => $num);
				$result2          = $this->editBase($goods_id, $edit_base_row);
				check_rs($result2,$rs_row);
			}

			$edit_common_row = array('common_stock' => $num);
			$result3 = $Goods_CommonModel->editCommonTrue($order_goods_base['common_id'], $edit_common_row);
			check_rs($result3,$rs_row);

			if($goods_parent_id)
			{
				$result3 = $Goods_CommonModel->editCommonTrue($goods_parent['common_id'], $edit_common_row);
				check_rs($result3,$rs_row);
			}

			$flag = is_ok($rs_row);
		}
        return $flag;

	}

	/**
	 * 检测商品状态
	 * 1.商品id是否存在
	 * 2.商品base信息是否存在
	 * 3.商品是否上架，商品是否有库存，商品是否存在店铺id
	 * 4.商品common信息是否存在
	 * 5.common是否正常，common审核是否通过，店铺是否正常开启
	 * @author Zhuyt
	 */
	public function checkGoods($goods_id)
	{
		if ($goods_id <= 0)
		{
			return null;
		}

		//获取商品信息及活动信息
		$goods_base = $this->getOne($goods_id);

		if (empty($goods_base))
		{
			return null;
		}

		//商品状态
		if ($goods_base['goods_is_shelves'] != Goods_BaseModel::GOODS_UP || $goods_base['goods_stock'] <= 0 || !$goods_base['shop_id'])
		{
			return null;
		}

		//获取商品Common信息
		$Goods_CommonModel = new Goods_CommonModel();
		$goods_common      = $Goods_CommonModel->getOne($goods_base['common_id']);
		if (empty($goods_common))
		{
			return null;
		}

		//common状态与店铺状态
		if ($goods_common['common_state'] != Goods_CommonModel::GOODS_STATE_NORMAL || $goods_common['shop_status'] != Shop_BaseModel::SHOP_STATUS_OPEN)
		{
			return null;
		}


		$data['goods_base'] = $goods_base;
		return $data;
	}

    /**
     * @param $goods_id
     * @param $goods_num
     * @param $chain_id
     * @return array
     * @throws Exception
     * 检查自提商品状态
     */
    public function isValidChainGoods($goods_id, $goods_num, $chain_id)
    {
        if ($goods_id <= 0) {
            throw new Exception('请勿重复操作');
        }

        //获取商品信息及活动信息
        $goods_base = $this->getOne($goods_id);

        if (empty($goods_base)) {
            throw new Exception('无此商品');
        }

        //商品状态
        if ($goods_base['goods_is_shelves'] != Goods_BaseModel::GOODS_UP) {
            throw new Exception('商品未上架');
        }

        //获取门店商品库存
        $chainGoodsModel = new Chain_GoodsModel;
        $chainGoods = $chainGoodsModel->getOneByWhere([
            'chain_id'=> $chain_id,
            'goods_id'=> $goods_id
        ]);

        if ($goods_num > $chainGoods['goods_stock']) {
            throw new Exception('商品库存不足');
        }

        if (!$goods_base['shop_id']) {
            throw new Exception('商品所在店铺失效');
        }

        //获取商品Common信息
        $Goods_CommonModel = new Goods_CommonModel();
        $goods_common = $Goods_CommonModel->getOne($goods_base['common_id']);
        if (empty($goods_common)) {
            throw new Exception('获取商品信息失败');
        }

        //common状态与店铺状态
        if ($goods_common['common_state'] != Goods_CommonModel::GOODS_STATE_NORMAL || $goods_common['shop_status'] != Shop_BaseModel::SHOP_STATUS_OPEN) {
            throw new Exception('商品所在店铺关闭');
        }

        $data['goods_base'] = $goods_base;
        return $data;
    }

	/**
	 * 检测商品状态(检测是否存在店铺的信息)
	 *
	 * @author Zhuyt
	 */
	public function checkGoodsI($goods_id)
	{
		if ($goods_id <= 0)
		{
			return null;
		}

		//获取商品信息及活动信息
		$goods_base = $this->getGoodsInfoAndPromotionById($goods_id);
		if (empty($goods_base))
		{
			return null;
		}

		//商品状态
		if ($goods_base['goods_is_shelves'] != Goods_BaseModel::GOODS_UP || $goods_base['goods_stock'] <= 0 || !$goods_base['shop_id'])
		{
			return null;
		}

		//获取商品Common信息
		$Goods_CommonModel = new Goods_CommonModel();
		$goods_common      = $Goods_CommonModel->getOne($goods_base['common_id']);
		if (empty($goods_common))
		{
			return null;
		}

		//common状态
		if ($goods_common['common_state'] != Goods_CommonModel::GOODS_STATE_NORMAL)
		{
			return null;
		}

		//店铺信息
		$Shop_BaseModel = new Shop_BaseModel();
		$shop_base      = $Shop_BaseModel->getOne($goods_base['shop_id']);

		if (!$shop_base || $shop_base['shop_status'] != Shop_BaseModel::SHOP_STATUS_OPEN)
		{
			return null;
		}


		$data['goods_base']  = $goods_base;
		$data['common_base'] = $goods_common;
		$data['shop_base']   = $shop_base;

		return $data;
	}

	/**
	 * 检测商品状态
	 * 1.商品id是否存在
	 * 2.商品base信息是否存在
	 * 3.商品是否上架，商品是否有库存，商品是否存在店铺id
	 * 4.商品common信息是否存在
	 * 5.common是否正常，common审核是否通过，店铺是否正常开启
	 * @author Zhuyt
	 */
	public function checkGoodsII($goods_id)
	{
		if ($goods_id <= 0)
		{
			return null;
		}

		//获取商品信息及活动信息
		$goods_base = $this->getOne($goods_id);
		if (empty($goods_base))
		{
			return null;
		}

		//商品状态
		if ($goods_base['goods_is_shelves'] != Goods_BaseModel::GOODS_UP || !$goods_base['shop_id'])
		{
			return null;
		}

		//获取商品Common信息
		$Goods_CommonModel = new Goods_CommonModel();
		$goods_common      = $Goods_CommonModel->getOne($goods_base['common_id']);
		if (empty($goods_common))
		{
			return null;
		}

		//common状态与店铺状态
		// if ($goods_common['common_state'] != Goods_CommonModel::GOODS_STATE_NORMAL || $goods_common['shop_status'] != Shop_BaseModel::SHOP_STATUS_OPEN)
		// {
		// 	return null;
		// }
		if ($goods_common['shop_status'] != Shop_BaseModel::SHOP_STATUS_OPEN)
		{
			return null;
		}
        
        //如果商品需要审核，则审核通过的才上架
        $Web_Config = new Web_ConfigModel();
		$goods_verify_flag     = $Web_Config->getConfigValue('goods_verify_flag');
        if($goods_common['common_verify'] != Goods_CommonModel::GOODS_VERIFY_ALLOW && $goods_verify_flag == 1){
            return null;
        }

		$data['goods_base'] = $goods_base;
		return $data;
	}
	/**
	 * 获取商品信息(购物车中获取商品信息，此处获取的商品信息并不全面)
	 *
	 * @author Zhuyt
	 */
	public function getWebposGoodsInfo($goods_id)
	{
		if ($goods_id <= 0)
		{
			return null;
		}

		//获取商品信息及活动信息
		$goods_base = $this->getGoodsInfoAndPromotionById($goods_id);
		fb($goods_base);
		fb("商品信息goods_base");
		if (empty($goods_base))
		{
			return null;
		}
		$Goods_ImagesModel = new Goods_ImagesModel();
		//商品详细图片
		$image_cond                         = array();
		$image_cond['common_id']            = $goods_base['common_id'];
		$image_cond['images_color_id']      = $goods_base['color_id'];
		$image_order                        = array();
		$image_order['images_displayorder'] = 'ASC';
		$image_order['images_is_default'] = 'DESC';
		$image_row                          = current($Goods_ImagesModel->getGoodsImage($image_cond, $image_order));
		if($image_row['images_image'])
		{
			$goods_base['goods_image'] = $image_row['images_image'];
		}
		else
		{
			$goods_base['goods_image'] = $goods_base['goods_image'];
		}
		
		//获取商品Common信息
		$Goods_CommonModel = new Goods_CommonModel();
		$goods_common      = $Goods_CommonModel->getOne($goods_base['common_id']);
		if (empty($goods_common))
		{
			return null;
		}
		//判断是否为代发货的分销商品
        $goods_common = $Goods_CommonModel->getSupplierCommon($goods_common);
        
        
		//商品规格信息
		$spec_name  = $goods_common['common_spec_name'];
		$spec_value = $goods_common['common_spec_value'];

		if (is_array($spec_name) && $spec_name && $goods_base['goods_spec'])
		{
			$goods_spec = current($goods_base['goods_spec']);

			foreach ($goods_spec as $gpk => $gbv)
			{
				foreach ($spec_value as $svk => $svv)
				{
					$pk = array_search($gbv, $svv);

					if ($pk)
					{
						$goods_base['spec'][] = $spec_name[$svk] . ":" . $gbv;
					}
				}
			}

		}
		else
		{
			$goods_base['spec'] = array();
		}

		$goods_base['spec_str'] = '';
		if($goods_base['spec'])
		{
			foreach($goods_base['spec'] as $spk=>$spv)
			{
				$goods_base['spec_str'] = $goods_base['spec_str'] . $spv .'  ';
			}
		}

		$goods_base['spec_val_str'] = empty($goods_base['spec'])
			? ''
			: implode(';', current($goods_base['goods_spec']));

		//店铺信息
		//店铺信息
		if ($goods_base['shop_id'])
		{
			$Shop_BaseModel = new Shop_BaseModel();
			$shop_base      = $Shop_BaseModel->getOne($goods_base['shop_id']);

			if (!$shop_base || $shop_base['shop_status'] != Shop_BaseModel::SHOP_STATUS_OPEN)
			{
				return null;
			}

		}
		else
		{
			return null;
		}

		$data['goods_base']  = $goods_base;
		$data['common_base'] = $goods_common;
		$data['shop_base']   = $shop_base;


        if($data['goods_base']['promotion_type']=='xianshi'){
            $start_time = strtotime($data['goods_base']['groupbuy_starttime']);
            $end_time = strtotime($data['goods_base']['groupbuy_endtime']);
            if(time() >= $start_time && time() <= $end_time){

            }else{
                $data['goods_base']['promotion_price']=null;
                $data['goods_base']['promotion_type']=null;
            }
        }

		return $data;
	}
	/**
	 * 获取商品信息(购物车中获取商品信息，此处获取的商品信息并不全面)
	 *
	 * @author Zhuyt
	 */
	public function getGoodsInfo($goods_id)
	{
		if ($goods_id <= 0)
		{
			return null;
		}

		//获取商品信息及活动信息
		$goods_base = $this->getGoodsInfoAndPromotionById($goods_id);
		fb($goods_base);
		fb("商品信息goods_base");
		if (empty($goods_base))
		{
			return null;
		}
		$Goods_ImagesModel = new Goods_ImagesModel();
		//商品详细图片
		$image_cond                         = array();
		$image_cond['common_id']            = $goods_base['common_id'];
		$image_cond['images_color_id']      = $goods_base['color_id'];
		$image_order                        = array();
		$image_order['images_displayorder'] = 'ASC';
		$image_order['images_is_default'] = 'DESC';
		$image_row                          = current($Goods_ImagesModel->getGoodsImage($image_cond, $image_order));
		if($image_row['images_image'])
		{
			$goods_base['goods_image'] = $image_row['images_image'];
		}
		else
		{
			$goods_base['goods_image'] = $goods_base['goods_image'];
		}
		
		//获取商品Common信息
		$Goods_CommonModel = new Goods_CommonModel();
		$goods_common      = $Goods_CommonModel->getOne($goods_base['common_id']);
		if (empty($goods_common))
		{
			return null;
		}
		//判断是否为代发货的分销商品
        $goods_common = $Goods_CommonModel->getSupplierCommon($goods_common);
        
        
		//商品规格信息
		$spec_name  = $goods_common['common_spec_name'];
		$spec_value = $goods_common['common_spec_value'];

		if (is_array($spec_name) && $spec_name && $goods_base['goods_spec'])
		{
			$goods_spec = current($goods_base['goods_spec']);

			foreach ($goods_spec as $gpk => $gbv)
			{
				foreach ($spec_value as $svk => $svv)
				{
					$pk = array_search($gbv, $svv);

					if ($pk)
					{
						$goods_base['spec'][] = $spec_name[$svk] . ":" . $gbv;
					}
				}
			}

		}
		else
		{
			$goods_base['spec'] = array();
		}

		$goods_base['spec_str'] = '';
		if($goods_base['spec'])
		{
			foreach($goods_base['spec'] as $spk=>$spv)
			{
				$goods_base['spec_str'] = $goods_base['spec_str'] . $spv .'  ';
			}
		}

		$goods_base['spec_val_str'] = empty($goods_base['spec'])
			? ''
			: implode(';', current($goods_base['goods_spec']));

		fb($goods_base['groupbuy_info']);
		fb('团购');
		//团购
		if (!empty($goods_base['groupbuy_info']) && $goods_base['groupbuy_info']['groupbuy_price'] < $goods_base['goods_price'])
		{
			$goods_base['promotion_type']  = 'groupbuy';
			$goods_base['title']           = $goods_base['groupbuy_info']['groupbuy_name'];
			$goods_base['remark']          = $goods_base['groupbuy_info']['groupbuy_remark'];
			$goods_base['promotion_price'] = $goods_base['groupbuy_info']['groupbuy_price'];
			$goods_base['down_price']      = $goods_base['goods_price'] - $goods_base['groupbuy_info']['groupbuy_price'];
			$goods_base['upper_limit']     = $goods_base['groupbuy_info']['groupbuy_upper_limit'];
            $goods_base['groupbuy_starttime'] = $goods_base['groupbuy_info']['groupbuy_starttime'];
            $goods_base['groupbuy_endtime'] = $goods_base['groupbuy_info']['groupbuy_endtime'];

			unset($goods_base['groupbuy_info']);
		}

		//限时折扣
		if (!empty($goods_base['xianshi_info']) && $goods_base['xianshi_info']['discount_price'] < $goods_base['goods_price'])
		{
			if ($goods_base['goods_price'] > $goods_base['xianshi_info']['discount_price'])
			{
				$goods_base['promotion_type']  = 'xianshi';
				$goods_base['title']           = $goods_base['xianshi_info']['discount_name'];
				$goods_base['remark']          = $goods_base['xianshi_info']['discount_title'];
				$goods_base['promotion_price'] = $goods_base['xianshi_info']['discount_price'];
				$goods_base['down_price']      = $goods_base['goods_price'] - $goods_base['xianshi_info']['discount_price'];
				$goods_base['lower_limit']     = $goods_base['xianshi_info']['goods_lower_limit'];
				$goods_base['explain']         = $goods_base['xianshi_info']['discount_explain'];
                $goods_base['groupbuy_starttime'] = $goods_base['xianshi_info']['goods_start_time'];
                $goods_base['groupbuy_endtime'] = $goods_base['xianshi_info']['goods_end_time'];
			}

			unset($goods_base['xianshi_info']);
		}
        //拼团
        if (!empty($goods_base['pintuan_info']) && $goods_base['pintuan_info']['detail']['price'] < $goods_base['goods_price'])
		{
            $goods_base['promotion_type']  = 'pintuan';
		}
		//验证是否赠送商品
		if (true)
		{
			$gift_array = array();
			if (!empty($gift_array))
			{
				$goods_base['have_gift'] = 'gift';
			}
			else
			{
				$goods_base['have_gift'] = '';
			}
		}

		//店铺信息
		//店铺信息
		if ($goods_base['shop_id'])
		{
			$Shop_BaseModel = new Shop_BaseModel();
			$shop_base      = $Shop_BaseModel->getOne($goods_base['shop_id']);

			if (!$shop_base || $shop_base['shop_status'] != Shop_BaseModel::SHOP_STATUS_OPEN)
			{
				return null;
			}

		}
		else
		{
			return null;
		}

		$data['goods_base']  = $goods_base;
		$data['common_base'] = $goods_common;
		$data['shop_base']   = $shop_base;


        if($data['goods_base']['promotion_type']=='xianshi'){
            $start_time = strtotime($data['goods_base']['groupbuy_starttime']);
            $end_time = strtotime($data['goods_base']['groupbuy_endtime']);
            if(time() >= $start_time && time() <= $end_time){

            }else{
                $data['goods_base']['promotion_price']=null;
                $data['goods_base']['promotion_type']=null;
            }
        }

		return $data;
	}

	/**
	 * 获取商品详细信息(商品详情中获取商品信息，此处获取的商品信息全面)
	 *
	 * @author Zhuyt
	 */
	public function getGoodsDetailInfoByGoodId($goods_id, $check = true, $is_del = false)
	{
		if ($goods_id <= 0)
		{
			return null;
		}

		//检测商品是否属于正常状态
        if ($check){
            $goods_status = $this->checkGoodsII($goods_id);
            if (!$goods_status)
            {
                return null;
            }
        }

		//获取商品信息及活动信息
		$goods_base = $this->getGoodsInfoAndPromotionById($goods_id);
        //判断商品是否已被删除
        if($is_del == true){
            if($goods_base['is_del'] == Goods_BaseModel::IS_DEL_YES){
                return null;
            }
        }
		if (empty($goods_base))
		{
			return null;
		}
		if($goods_base['goods_promotion_tips'] === null)
		{
			$goods_base['goods_promotion_tips'] = '';
		}
		
		//商品规格信息
		if (is_array($goods_base['goods_spec']))
		{
			$goods_base['goods_spec'] = current($goods_base['goods_spec']);
			if($goods_base['goods_spec'] === null)
				$goods_base['goods_spec'] = '';
		}

		//获取商品Common信息
		$Goods_CommonModel = new Goods_CommonModel();
		$goods_common      = $Goods_CommonModel->getOne($goods_base['common_id']);
		if (empty($goods_common))
		{
			return null;
		}
        //判断是否为代发货的分销商品
        $goods_common = $Goods_CommonModel->getSupplierCommon($goods_common);

		//字段返回null会导致APP解析错误，替换为空字符串
		if($goods_common['common_property'] === null)
			$goods_common['common_property'] = '';
		if($goods_common['common_spec_name'] === null)
			$goods_common['common_spec_name'] = '';
		if($goods_common['common_spec_value'] === null)
			$goods_common['common_spec_value'] = '';
		if($goods_common['common_location'] === null)
			$goods_common['common_location'] = '';

		if($goods_common['common_property'])
		{
			$Goods_PropertyValueModel = new Goods_PropertyValueModel();
			foreach($goods_common['common_property'] as $cgpkey => $cgpval)
			{
				$goods_propertyval = $Goods_PropertyValueModel->getOne($cgpval['1']);
				$common_property_row[$cgpval['0']] = $goods_propertyval['property_value_name'];
			}

			$goods_common['common_property_row'] = $common_property_row;
		}
		else
		{
			$goods_common['common_property_row'] = array();
		}


		//商品详情
		$Goods_CommonDetailModel = new Goods_CommonDetailModel();
		$goods_detail            = $Goods_CommonDetailModel->getOne($goods_base['common_id']);

		if ($goods_detail)
		{
			$goods_common['common_detail'] = $goods_detail['common_body'];
		}
		else
		{
			$goods_common['common_detail'] = '';
		}

		//商品common图片
		$image_common_cond                      = array();
		$image_common_cond['common_id']         = $goods_common['common_id'];
//		$image_common_cond['images_is_default'] = Goods_ImagesModel::IMAGE_DEFAULT;
		$Goods_ImagesModel                      = new Goods_ImagesModel();

		$goods_common['common_spec_value_c'] = $goods_common['common_spec_value'];
		if (is_array($goods_common['common_spec_value']))
		{
			foreach ($goods_common['common_spec_value'] as $comvk => $comvv)
			{
				//所有商品颜色规格图片只有一个有默认值1，其它规格找出对应规格第一张图片剪切
				foreach ($comvv as $cvk => $cvv)
				{
					$image_default = 0;
					$image_common_cond['images_color_id'] = $cvk;
					$image_common_row                     = $Goods_ImagesModel->getGoodsImage($image_common_cond);
					foreach($image_common_row as $ik=>$iv)
					{
						$image_default += $iv['images_is_default'];
						if($iv['images_is_default'] == 1)
						{
							$img_default_key = $ik;
						}
					}
					
					if($image_default == 0)
					{
						$image_common_row = array_values($image_common_row)[0];
					}
					else
					{
						$image_common_row = $image_common_row[$img_default_key];
						unset($img_default_key);
					}

					if ($image_common_row)
					{
						$goods_common['common_spec_value'][$comvk][$cvk] = sprintf('<img src="%s" title="%s" alt="%s"/>', image_thumb($image_common_row['images_image'],42,42),$cvv,$cvv);
						$goods_common['common_spec_value_color'][$cvk] = image_thumb($image_common_row['images_image'], 360, 360);
					}
				}

			}
		}

		//商品详细图片
		$image_cond                         = array();
		$image_cond['common_id']            = $goods_common['common_id'];
		$image_cond['images_color_id']      = $goods_base['color_id'];
		$image_order                        = array();
		$image_order['images_displayorder'] = 'ASC';
		$image_order['images_is_default'] = 'DESC';
		$image_row                          = array_values($Goods_ImagesModel->getGoodsImage($image_cond, $image_order));
		//去除为空的图片
		foreach($image_row as $imgk=>$imgv)
		{
			if(empty($imgv['images_image']))
			{
				unset($image_row[$imgk]);
			}
		}
		$goods_base['image_row'] = $image_row;
        
		//商品评论数
		$Goods_EvaluationModel   = new Goods_EvaluationModel();
		$countall                = $Goods_EvaluationModel->countEvaluation($goods_common['common_id']);
		$goods_base['evalcount'] = $countall;


		//商品销售记录
		$Order_GoodsModel        = new Order_GoodsModel();
		$sale                    = $Order_GoodsModel->getGoodsSaleNum($goods_id);
		$goods_base['salecount'] = $sale;

		//团购
		if (!empty($goods_base['groupbuy_info']) && $goods_base['groupbuy_info']['groupbuy_price'] < $goods_base['goods_price'])
		{
			$goods_base['promotion_type']  = 'groupbuy';
			$goods_base['title']           = $goods_base['groupbuy_info']['groupbuy_name'];
			$goods_base['remark']          = $goods_base['groupbuy_info']['groupbuy_remark'];
			$goods_base['promotion_price'] = $goods_base['groupbuy_info']['groupbuy_price'];
			$goods_base['down_price']      = $goods_base['goods_price'] - $goods_base['groupbuy_info']['groupbuy_price'];
			$goods_base['upper_limit']     = $goods_base['groupbuy_info']['groupbuy_upper_limit'];
			$goods_base['groupbuy_starttime'] = $goods_base['groupbuy_info']['groupbuy_starttime'];
			$goods_base['groupbuy_endtime'] = $goods_base['groupbuy_info']['groupbuy_endtime'];
            $goods_base['groupbuy_virtual_quantity'] = $goods_base['groupbuy_info']['groupbuy_virtual_quantity'];
            
            if($goods_base['groupbuy_starttime'] > date('Y-m-d H:i:s') || $goods_base['groupbuy_endtime'] < date('Y-m-d H:i:s')){
                $goods_base['promotion_is_start'] = 0;
            } else {
                $goods_base['promotion_is_start'] = 1;
//                //获取销售量
//                $cond_row = array(
//                    'common_id' => $goods_base['common_id'],
//                    'order_goods_time:>=' => $goods_base['groupbuy_info']['groupbuy_starttime'],
//                    'order_goods_time:<=' => $goods_base['groupbuy_info']['groupbuy_endtime']
//                );
//                $order_goods_num = $Order_GoodsModel->getOrderGoodsNum($cond_row);
                $goods_base['groupbuy_salecount'] = $goods_base['groupbuy_info']['groupbuy_virtual_quantity'];
            }
            
			unset($goods_base['groupbuy_info']);
		}
        
		//限时折扣
		if (!empty($goods_base['xianshi_info']) && $goods_base['xianshi_info']['discount_price'] < $goods_base['goods_price'])
		{
			if ($goods_base['goods_price'] > $goods_base['xianshi_info']['discount_price'])
			{
				$goods_base['promotion_type']  = 'xianshi';
				$goods_base['title']           = $goods_base['xianshi_info']['discount_name'];
				$goods_base['remark']          = $goods_base['xianshi_info']['discount_title'];
				$goods_base['promotion_price'] = $goods_base['xianshi_info']['discount_price'];
				$goods_base['down_price']      = $goods_base['goods_price'] - $goods_base['xianshi_info']['discount_price'];
				$goods_base['lower_limit']     = $goods_base['xianshi_info']['goods_lower_limit'];
                $goods_base['xianshi_lower_limit']     = $goods_base['xianshi_info']['goods_lower_limit'];
				$goods_base['explain']         = $goods_base['xianshi_info']['discount_explain'];
                $goods_base['groupbuy_starttime'] = $goods_base['xianshi_info']['goods_start_time'];
                $goods_base['groupbuy_endtime'] = $goods_base['xianshi_info']['goods_end_time'];
                $goods_base['groupbuy_starttimes'] = strtotime($goods_base['xianshi_info']['goods_start_time']);
                $goods_base['groupbuy_endtimes'] = strtotime($goods_base['xianshi_info']['goods_end_time']);
                $goods_base['promotion_is_start'] = $goods_base['groupbuy_starttime'] > date('Y-m-d H:i:s') ? 0 : 1;
			}

			unset($goods_base['xianshi_info']);
		}

        //拼团
        if (!empty($goods_base['pintuan_info']) && $goods_base['pintuan_info']['detail']['price'] < $goods_base['goods_price'])
		{
            $goods_base['promotion_type']  = 'pintuan';
            if($goods_base['pintuan_info']['start_time'] > date('Y-m-d H:i:s') || $goods_base['pintuan_info']['end_time'] < date('Y-m-d H:i:s')){
                $goods_base['promotion_is_start'] = 0;
            } else {
                $goods_base['promotion_is_start'] = 1;
            }
		}
		//验证是否赠送商品
		$goods_base['have_gift'] = '';

		//加入购物车按钮
		$goods_base['cart'] = true;

		//虚拟、F码、预售不显示加入购物车
		if ($goods_common['common_is_virtual'] == 1)
		{
			$goods_base['cart'] = false;
		}
        //一键代发的分销商品不可加入购物车
        if ($goods_common['product_is_behalf_delivery'] == 1 && $goods_common['common_parent_id'] > 0)
		{
			$goods_base['cart'] = false;
		}
		//店铺满送活动
		$Promotion    = new Promotion();
		$mansong_info = $Promotion->getShopGiftInfo($goods_base['shop_id']);

        

		//店铺信息
		if ($goods_base['shop_id'])
		{
			$Shop_BaseModel = new Shop_BaseModel();
			$shop_base      = $Shop_BaseModel->getOne($goods_base['shop_id']);

			if (!$shop_base || $shop_base['shop_status'] != Shop_BaseModel::SHOP_STATUS_OPEN)
			{
				return null;
			}

			//商品运费信息（查找是否是包邮产品，或者满多少包邮）
			if ($shop_base['shop_free_shipping'] > 0)
			{
				$shop_base['shipping'] = sprintf("满%s免运费", ceil($shop_base['shop_free_shipping']));
			}
			else
			{
				$shop_base['shipping'] = '';
			}

		}
		else
		{
			return null;
		}

		$data                 = array();
		$data['goods_base']   = $goods_base;
		$data['common_base']  = $goods_common;
		$data['shop_base']    = $shop_base;
		$data['mansong_info'] = $mansong_info;
		$data['gift_array']   = array();


		return $data;
	}

	/**
	 * 查询商品详细信息及其促销信息
	 * @param int $goods_id
	 * @return array
	 */
	public function getGoodsInfoAndPromotionById($goods_id = null)
	{
		$goods_info = $this->getOne($goods_id);
		if (empty($goods_info))
		{
			return array();
		}

		$Promotion = new Promotion();

		//团购
		$goods_info['groupbuy_info'] = $Promotion->getGroupBuyInfoByGoodsCommonID($goods_info['common_id']);

		//限时折扣
		if (empty($goods_info['groupbuy_info']))
		{
			$goods_info['xianshi_info'] = $Promotion->getXianShiGoodsInfoByGoodsID($goods_info['goods_id']);

		}

		//加价购
		$goods_info['increase_info'] = $Promotion->getIncreaseDetailByGoodsId($goods_info['goods_id']);

        //拼团
        $pt_detail_id = request_int('pt_detail_id');
		$goods_info['pintuan_info'] = $Promotion->getPintuanDetailByGoodsId($pt_detail_id);

		return $goods_info;
	}

	public function getCommonInfo($goods_id = 0)
	{
		$Goods_CommonModel = new Goods_CommonModel();

		$goods_base = $this->getBase($goods_id);
		if ( empty($goods_base) )
		{
			return array();
		}
		else
		{
			$goods_base = pos($goods_base);

			$common_id   = $goods_base['common_id'];
			$common_data = $Goods_CommonModel->getCommon($common_id);
			$common_data = pos($common_data);

			return $common_data;
		}

	}

	//获取商品规格名称
	public function getGoodsSpecName($goods_id = 0)
	{
		$spec_name            = null;
		$Goods_SpecModel      = new Goods_SpecModel();
		$Goods_SpecValueModel = new Goods_SpecValueModel();

		$goods_base = $this->getBase($goods_id);
		$goods_base = pos($goods_base);

		if (!empty($goods_base['goods_spec']))
		{
			$goods_spec = pos($goods_base['goods_spec']);

			if (!empty($goods_spec))
			{
				foreach ($goods_spec as $key => $val)
				{
					$spec_value = $Goods_SpecValueModel->getSpecValue($key);
					$spec_value = pos($spec_value);

					$spec = $Goods_SpecModel->getSpec($spec_value['spec_id']);
					$spec = pos($spec);

					$spec_base_name  = $spec['spec_name'];
					$spec_value_name = $spec_value['spec_value_name'];
					$spec_name .= "$spec_base_name: $spec_value_name  &nbsp;";

				}
			}
		}

		return $spec_name;
	}

	//修改商品的销量（增加）
	public function editGoodsSale($order_goods_id = null)
	{
		//查找出订单商品的信息
		$Order_GoodsModel = new Order_GoodsModel();
		$order_goods_row  = $Order_GoodsModel->getByWhere(array('order_goods_id:IN' => $order_goods_id));

		$Goods_CommonModel = new Goods_CommonModel();

		foreach ($order_goods_row as $key => $val)
		{
			//修改common的销售数量
			$edit_common_row = array('common_salenum' => $val['order_goods_num']);
			$Goods_CommonModel->editCommonTrue($val['common_id'], $edit_common_row);

			//修改商品的销售数量
			$edit_goods_row = array('goods_salenum' => $val['order_goods_num']);
			$this->editBase($val['goods_id'], $edit_goods_row);

		}
	}

	public function getGoodsSpecByGoodId($goods_id)
	{
		$Goods_BaseModel = new Goods_BaseModel();
		$Goods_SpecModel = new Goods_SpecModel();
		$data = array();
		if($goods_id)
		{
			$data = $Goods_BaseModel->getOne($goods_id);

			if(is_array($data['goods_spec']))
			{
				$spec = pos($data['goods_spec']);
				if(!empty($spec))
				{
					$spec_data = array();
					foreach($spec as $key=>$value)
					{
						$spec_id = $key;
						$spec_value = $value;
						if($spec_id)
						{
							$spec_name = $Goods_SpecModel->getSpecNameById($spec_id);
							if($spec_name)
							{
								$spec_data[$spec_name] = $spec_value;
							}
						}
					}
					$data['spec'] = $spec_data;
				}
			}
		}
		return $data;
	}

	public function createSGIdByWap( $common_id = 0 )
	{
		$spec_goods_ids = array();

		$goods_base = $this->getBaseByCommonId($common_id);

		if ( !empty($goods_base) )
		{
			foreach ($goods_base as $goods_id => $goods_data)
			{
				if ( !empty($goods_data['goods_spec']) )
				{
					foreach ($goods_data['goods_spec'] as $k => $spec_data)
					{
						$spec_ids = array();
						$spec_ids = array_keys($spec_data);
						sort($spec_ids);
						$spec_ids = implode("|", $spec_ids);
						$spec_goods_ids[$spec_ids] = $goods_id;
					}
				}
			}
		}
		return $spec_goods_ids;
	}

    public function createSGIdByWaps($common_id = 0 ){
        $spec_goods_ids = array();

        $goods_base = $this->getBaseByCommonId($common_id);

        if ( !empty($goods_base) )
        {
            foreach ($goods_base as $goods_id => $goods_data)
            {
                if ( !empty($goods_data['goods_spec']) )
                {
                    foreach ($goods_data['goods_spec'] as $k => $spec_data)
                    {
                        $spec_ids = array();
                        $spec_ids = array_keys($spec_data);
                        sort($spec_ids);
                        $spec_ids = implode(",", $spec_ids);
                        $spec_goods_ids[$spec_ids] = $goods_id;
                    }
                }
            }
        }
        return $spec_goods_ids;
    }


	public function getGoodsStockList($common_id)
	{
		$spec_goods_ids = array();
		$goods_base = $this->getBaseByCommonId($common_id);

		if ( !empty($goods_base) )
		{
			foreach ($goods_base as $goods_id => $goods_data)
			{
				if ( !empty($goods_data['goods_spec']) )
				{
					foreach ($goods_data['goods_spec'] as $k => $spec_data)
					{
						$spec_ids = array();
						$spec_ids = array_keys($spec_data);
						sort($spec_ids);
						$spec_ids = implode("|", $spec_ids);
						$spec_goods_ids[$spec_ids] = $goods_data['goods_stock'];
					}
				}
				else
				{
					$spec_goods_ids['nothing'] = $goods_data['goods_stock'];
				}
			}
		}
		return $spec_goods_ids;
	}

	public function getGoodsPriceList($common_id)
	{
		$spec_goods_ids = array();
		$goods_base = $this->getBaseByCommonId($common_id);

		if ( !empty($goods_base) )
		{
			foreach ($goods_base as $goods_id => $goods_data)
			{
				//获取商品信息
				$base = $this->getGoodsInfo($goods_id);

				$price = $goods_data['goods_price'];
				if (isset($base['goods_base']['promotion_type']) && $base['goods_base']['promotion_type'])
				{
					if ($base['goods_base']['groupbuy_starttime'] < date('Y-m-d H:i:s') && $base['goods_base']['groupbuy_endtime'] > date('Y-m-d H:i:s'))
					{
						$price = $base['goods_base']['promotion_price'];
					}
				}

				if ( !empty($goods_data['goods_spec']) )
				{
					foreach ($goods_data['goods_spec'] as $k => $spec_data)
					{
						$spec_ids = array();
						$spec_ids = array_keys($spec_data);
						sort($spec_ids);
						$spec_ids = implode("|", $spec_ids);
						$spec_goods_ids[$spec_ids] = $price;
					}
				}
				else
				{
					$spec_goods_ids['nothing'] = $price;
				}
			}
		}
		return $spec_goods_ids;
	}

    /**
     * 获取售卖区域和运费
     * @param type $area_id
     * @param type $common_id
     * @return type
     */
	public function getTransportInfo ($area_id , $common_id)
	{
		//获取common的transport
		$Goods_CommonModel = new Goods_CommonModel();
		$common_base = $Goods_CommonModel->getOne($common_id);
        //判断是否是分销代发货商品
        if($common_base['product_is_behalf_delivery'] == 1 && $common_base['common_parent_id'] && $common_base['supply_shop_id']){
            $common_base = $Goods_CommonModel->getOne($common_base['common_parent_id']);
        }
        $Transport_AreaModel = new Transport_AreaModel();
        $isSale = $Transport_AreaModel->isSale($common_base['transport_area_id'],$area_id);
        if(!$isSale){
            return array('transport_str'=>'无货','result'=>false); //无货
        }
        //固定运费暂不考虑
        //模板运费
        $Transport_TemplateModel = new Transport_RuleModel();
        $transport = $Transport_TemplateModel->getOpenRuleInfo($area_id,$common_base['shop_id']);
        $rule_info = array();
        if($transport['id']){
            if($transport['rule_info']){
                if(($transport['rule_info']['default_price'] + $transport['rule_info']['add_num'] * $transport['rule_info']['add_price']) == 0){
                    $transport_str = '免运费';
                }else{
                    $transport_str = sprintf('首重价格：%s',format_money($transport['rule_info']['default_price']));
                    /*$transport_str = sprintf('首重%sKg,默认运费：%s',$transport['rule_info']['default_num'],format_money($transport['rule_info']['default_price']));*/
                   /* if($transport['rule_info']['add_price'] > 0 && $transport['rule_info']['add_num'] > 0){
                        $transport_str .= sprintf('，每续重%sKg,增加运费：%s',$transport['rule_info']['add_num'],format_money($transport['rule_info']['add_price']));
                    }*/
                    $rule_info= $transport['rule_info'];
                }
            }else{
                $transport_str = '免运费';
            }
            return array('transport_str'=>$transport_str,'result'=>true,'rule_info'=>$rule_info);
        }else{
            return array('transport_str'=>'','result'=>false,'rule_info'=>$rule_info); //无货
        }
		
	}
    
    /**
     *  根据店铺修改商品属性
     */
    public function editBaseByShopId($shop_id,$set=array()){
        if(!$set || !$shop_id){
            return false;
        }
        
        $result = $this->updateBaseByShopId($shop_id,$set);
        return $result;
    }
    
    /**
     * 批量获取商品属性
     * @param type $goods_ids
     * @return type
     */
    public function getGoodsSpecByGoodIds($goods_ids)
	{
        $data = array();
		if(is_array($goods_ids) && $goods_ids)
		{
            $Goods_BaseModel = new Goods_BaseModel();
            $Goods_SpecModel = new Goods_SpecModel();
			$goods_list = $Goods_BaseModel->get($goods_ids);
            if($goods_list){
                foreach ($goods_list as $val){
                    if(is_array($val['goods_spec']))
                    {
                        $spec = pos($val['goods_spec']);
                        if(!empty($spec))
                        {
                            $spec_data = array();
                            foreach($spec as $key=>$value)
                            {
                                $spec_id = $key;
                                $spec_value = $value;
                                if($spec_id)
                                {
                                    $spec_name = $Goods_SpecModel->getSpecNameById($spec_id);
                                    if($spec_name)
                                    {
                                        $spec_data[$key]['name'] = $spec_name;
                                        $spec_data[$key]['value'] = $spec_value;
                                    }
                                }
                            }
                            $data[$val['goods_id']] = $spec_data;
                        }
                    }
                }
            }
		}
		return $data;
	}
    
    /**
     * 检查商品是否正常
     * @param type $goods_base
     * @return boolean
     */
    public function checkGoodsBase($goods_base){
        if(!is_array($goods_base) || !$goods_base){
            return false;
        }
        //商品状态
		if ($goods_base['goods_is_shelves'] != Goods_BaseModel::GOODS_UP || $goods_base['goods_stock'] <= 0 || !$goods_base['shop_id']) {
			return false;
		}
        return true;

    }
    
    
    
    /**
     * 获取正常的商品信息
     * @param type $goods_id
     * @return boolean
     */
    public function getGoodsAndCommon($goods_id){
        //获取商品信息
        $goods_base = $this->getOne($goods_id);
        $check_goods_base = $this->checkGoodsBase($goods_base);
        if(!$check_goods_base){
            return false;
        }
       
        //获取common信息
        $Goods_CommonModel   = new Goods_CommonModel();
        $goods_common = $Goods_CommonModel->getOne($goods_base['common_id']);
        //代发货的分销商品使用供应商售卖区域和限购信息
        $goods_common = $Goods_CommonModel->getSupplierCommon($goods_common);
        $check_goods_common = $Goods_CommonModel->checkCommonBase($goods_common);
        if(!$check_goods_common){
            return false;
        }
        $goods_spec = $this->getGoodsSpec($goods_base['goods_spec'],$goods_common['common_spec_name'],$goods_common['common_spec_value']);
    
        $goods_base = array_merge($goods_base,$goods_spec);
        $data = array(
            'base' => $goods_base,
            'common' => $goods_common
        );
//        dump($data);
        return $data;
    }
    
    /**
     * 格式化商品属性
     * @param type $goods_spec
     * @param type $spec_name
     * @param type $spec_value
     * @return string
     */
    function getGoodsSpec($goods_spec,$spec_name,$spec_value){
        //商品规格信息
        $spec = array();
		if (is_array($spec_name) && $spec_name && $goods_spec)
		{
			$goods_spec = current($goods_spec);

			foreach ($goods_spec as $gpk => $gbv)
			{
				foreach ($spec_value as $svk => $svv)
				{
					$pk = array_search($gbv, $svv);

					if ($pk)
					{
						$spec['spec'][] = $spec_name[$svk] . ":" . $gbv;
					}
				}
			}
		}
		else
		{
			$spec['spec'] = array();
		}

		$spec['spec_str'] = '';
		if($spec['spec'])
		{
			foreach($spec['spec'] as $spk=>$spv)
			{
				$spec['spec_str'] = $spec['spec_str'] . $spv .'  ';
			}
		}

        return $spec;
    }
    
    /**
     * 获取用户的商品会员折扣后的单价 
     * @param type $user_id
     * @param array $goods_info
     * $goods_info = array(
     *      'shop_id'=>'',
     *      'goods_price'=>'',
     * );
     */
    public function getGoodsRatePrice($user_id,$goods_info = array()){
        //会员折扣
        $User_InfoMode = new User_InfoModel();
		$user_info     = $User_InfoMode->getOne($user_id);
        $User_GradeMode = new User_GradeModel();
		$user_grade     = $User_GradeMode->getGradeRate($user_info['user_grade']);
        $user_rate = !$user_grade ? 100 : $user_grade['user_grade_rate'];

		$Shop_BaseModel = new Shop_BaseModel();
		$shop_base = $Shop_BaseModel->getOne($goods_info['shop_id']);
		//判断是否是分销商购买
		$Distribution_ShopDirectsellerModel = new Distribution_ShopDirectsellerModel();
		$distuibution_info = $Distribution_ShopDirectsellerModel->getOneByWhere(['directseller_id'=>$user_id]);
		if($distuibution_info) {
            //分销商购买不计算会员折扣
            $user_rate = $distuibution_info['shop_id'] == $goods_info['shop_id'] ? 100 : $user_rate;
		}
        //如果是分销商购买的供货商的商品，计算分销折扣
        $shopDistributorModel = new Distribution_ShopDistributorModel();
        $distritutor_rate_info = $shopDistributorModel->getDistributorByShopId($goods_info['shop_id']);
        $data = array();
        if($distritutor_rate_info && $distritutor_rate_info['distributor_leve_discount_rate']){
            $data['now_price'] = number_format($goods_info['goods_price'] * $distritutor_rate_info['distributor_leve_discount_rate'] / 100, 2, '.', '');
            $data['distributor_rate'] = $distritutor_rate_info['distributor_leve_discount_rate'];
            $data['user_rate'] = 0;
        }else{
			if($shop_base['shop_self_support'] == 'true'){
				$data['now_price'] = number_format($goods_info['goods_price'] * $user_rate / 100, 2, '.', '');
				$data['user_rate'] = $user_rate;
				$data['distributor_rate'] = 0;
			}else{
				$data['now_price'] = number_format($goods_info['goods_price'] , 2, '.', '');
				$data['user_rate'] = 0;
				$data['distributor_rate'] = 0;
			}

        }
        $data['rate_price']  = number_format($goods_info['goods_price'] - $data['now_price'], 2, '.', '');
        return $data;
    }


    public function getGoodsSpecByGoodsId($cond_row,$order,$page,$rows){
    	$goods_list = $this->listByWhere($cond_row,$order,$page,$rows);
        $goods_id = array_column($goods_list['items'],'goods_id');
        foreach($goods_id as $key=>$v){
            $goods_list['items'][$key] =  $this->getGoodsSpecByGoodId($v);
        }
        return $goods_list;
    }

	public function editGoodsOldPrice($goods_list)
	{
		foreach($goods_list as $key => $val)
		{
			$goods_info = $this->getOne($val['goods_id']);
			$goods_list[$key]['old_price'] = $goods_info['goods_price'];
		}

		return $goods_list;
	}
}

?>
<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Yf <service@yuanfeng.cn>
 */
class Order_GoodsModel extends Order_Goods
{
	const EVALUATION_YES = 1;        //已评价
	const EVALUATION_NO  = 0;        //未评价
	const EVALUATION_AGAIN  = 2;     //追加评价
	const REFUND_NO      = 0;  //无退款退货
	const REFUND_IN      = 1;	//退款退货中
	const REFUND_COM     = 2;  //退款退货完成
	const REFUND_REF     = 3;  //商户不同意退款退货

	/**
	 * 读取分页列表
	 *
	 * @param  int $goods_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data            = $this->listByWhere($cond_row, $order_row, $page, $rows);
		$Order_BaseModel = new Order_BaseModel();
		if ($data['items'])
		{
			foreach ($data['items'] as $key => $val)
			{
				$order_base                                 = $Order_BaseModel->getOne($val['order_id']);
				$data['items'][$key]['buyer_user_name']     = $order_base['buyer_user_name'];
				$data['items'][$key]['order_finished_time'] = $order_base['order_finished_time'];
			}
		}

		return $data;

	}

	/**
	 * 商品销售列表
	 *
	 * @author Zhuyt
	 */
	public function getGoodSaleList($cond_row = array(), $order_row = array(), $page, $rows)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);

		$Order_BaseModel = new Order_BaseModel();
		if ($data['items'])
		{
			foreach ($data['items'] as $key => $val)
			{
				$order = $Order_BaseModel->getOne($val['order_id']);

				$data['items'][$key]['order'] = $order;
			}
		}

		fb($data);
		return $data;
	}

	/**
	 * 商品销售数量
	 *
	 * @author Zhuyt
	 */
	public function getGoodsSaleNum($goods_id = null)
	{
		$data = $this->listByWhere(array('goods_id' => $goods_id));

		$count = count($data['items']);

		return $count;
	}

	/**
	 * 获取订单产品列表
	 *
	 * @param  int $goods_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getGoodsListByOrderId($order_id, $order_row = array(), $page = 1, $rows = 100)
	{
		if (is_array($order_id))
		{
			$cond_row = array('order_id:IN' => $order_id);
		}
		else
		{
			$cond_row = array('order_id' => $order_id);
		}

		return $this->listByWhere($cond_row);
	}

	/**
	 * 获取订单产品详情
	 *
	 * @param  int $order_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getGoodsDetail($cond_row)
	{

		return $this->getOneByWhere($cond_row);
	}


	/**
	 * @param $common_id
	 * @param $time array
	 * @return number
	 * 获取用户购买商品数量
	 */
	public function getGoodsPurchaseNumByUser($common_id, $time = [])
	{
		$user_id = Perm::$userId;

		//有效订单状态
		$order_goods_status = [
			Order_StateModel::ORDER_PAYED, //已付款
			Order_StateModel::ORDER_WAIT_PREPARE_GOODS, //待发货
			Order_StateModel::ORDER_WAIT_CONFIRM_GOODS, //已发货
			Order_StateModel::ORDER_RECEIVED, //已签收
			Order_StateModel::ORDER_FINISH, //已完成
		];

		//筛选条件
		$condi = [
			'order_goods_status:IN'=> $order_goods_status,
			'buyer_user_id'=> $user_id,
			'common_id'=> $common_id
		];

		if (! empty($time)) {
			$condi['order_goods_time:>='] = $time['start_time'];
			$condi['order_goods_time:<='] = $time['end_time'];
		}
		
		$order_goods_rows = $this->getByWhere($condi);

		//没有购买记录
		if (empty($order_goods_rows)) {
			return 0;
		}

		return array_sum(array_column($order_goods_rows, 'order_goods_num'));
	}
    
    /**
     * 根据条件获取商品的售卖数量
     */
    public function getOrderGoodsNum($cond_row){
        $count = $this->getCount($cond_row);
        $list = $this->getByWhere($cond_row,array(),1,$count);
        $num = 0;
        if($list){
            foreach ($list as $value){
                $num += $value['order_goods_num'];
            }
        }
        
        return $num;
    }

	/*
	 * 更加订单商品id获取订单商品信息判断该订单商品是否是分销商品，如果是分销商品需要将退款退货的分销佣金从订单商品表中扣除
	 * order_goods_id:订单商品id
	 * num:退款/退货的商品数量
	 * */
	public function refundDirectsellercommission($order_goods_id,$num)
	{
		$order_goods_info = $this->getOne($order_goods_id);

		$data = array();
		$data[0] = 0;
		$data[1] = 0;
		$data[2] = 0;

		if($order_goods_info['directseller_flag'])
		{
			//计算需要退还的三级分销佣金金额
			//将获取的分销佣金按照订单商品数量等分
			$dc0 = ($order_goods_info['directseller_commission_0']/$order_goods_info['order_goods_num'])*$num;
			$dc1 = ($order_goods_info['directseller_commission_1']/$order_goods_info['order_goods_num'])*$num;
			$dc2 = ($order_goods_info['directseller_commission_2']/$order_goods_info['order_goods_num'])*$num;

			$dc0 = number_format($dc0, 2, '.', '');
			$dc1 = number_format($dc1, 2, '.', '');
			$dc2 = number_format($dc2, 2, '.', '');

			//控制退还的佣金金额不可比收到的金额大
			if($order_goods_info['directseller_commission_0_refund']+$dc0 > $order_goods_info['directseller_commission_0'])
			{
				$dc0 = $order_goods_info['directseller_commission_0'];
			}
			else
			{
				$dc0 = $order_goods_info['directseller_commission_0_refund']+$dc0;
			}

			if($order_goods_info['directseller_commission_1_refund']+$dc1 > $order_goods_info['directseller_commission_1'])
			{
				$dc1 = $order_goods_info['directseller_commission_1'];
			}
			else
			{
				$dc1 = $order_goods_info['directseller_commission_1_refund']+$dc1;
			}

			if($order_goods_info['directseller_commission_2_refund']+$dc2 > $order_goods_info['directseller_commission_2'])
			{
				$dc2 = $order_goods_info['directseller_commission_2'];
			}
			else
			{
				$dc2 = $order_goods_info['directseller_commission_2_refund']+$dc2;
			}

			$data[0] = $dc0 - $order_goods_info['directseller_commission_0_refund'];
			$data[1] = $dc1 - $order_goods_info['directseller_commission_1_refund'];
			$data[2] = $dc2 - $order_goods_info['directseller_commission_2_refund'];

			//将应该退还的金额写入订单商品表总
			$edit_row = array();
			$edit_row['directseller_commission_0_refund'] = number_format($dc0, 2, '.', '');  //一级分佣;
			$edit_row['directseller_commission_1_refund'] = number_format($dc1, 2, '.', '');
			$edit_row['directseller_commission_2_refund'] = number_format($dc2, 2, '.', '');

			$this->editGoods($order_goods_id,$edit_row);
		}

		return $data;
	}

	/*
	 * 退货情况下返回三级分销佣金
	 * order_goods_id:订单商品id
	 * nun:退款/退货的商品数量
	 * */
	public function returnDirectsellercommission($order_goods_id,$dc)
	{
		$order_goods_info = $this->getOne($order_goods_id);
		$Order_BaseModel = new Order_BaseModel();
		$order_base = $Order_BaseModel->getOne($order_goods_info['order_id']);
		//该笔订单商品是分销商品需要退还分销佣金
		if($order_goods_info['directseller_flag'])
		{
			$User_InfoModel = new User_InfoModel();
			//1.判断该笔退货发生在分销佣金结算前还是分销佣金结算后
			if($order_goods_info['directseller_is_settlement'] == Order_BaseModel::IS_SETTLEMENT)
			{
				//将各级分销员获取的分销佣金扣除
				$directseller_member[0] = $order_base['directseller_id'];     //直属一级
				$directseller_member[1] = $order_base['directseller_p_id'];   //直属二级
				$directseller_member[2] = $order_base['directseller_gp_id'];  //直属三级

				foreach($directseller_member as $ks=>$vs)
				{
					//存在分销上级并且分销佣金大于0则写入流水
					if($vs && $dc[$ks])
					{
						$user_info = $User_InfoModel->getOne($vs);
						$edit_row['user_directseller_commission'] = $user_info['user_directseller_commission'] + $dc[$ks];
						$User_InfoModel->editInfo($vs,$edit_row);

						//将需要确认的订单号远程发送给Paycenter修改订单状态
						//远程修改paycenter中的订单状态
						$key      = Yf_Registry::get('paycenter_api_key');
						$url         = Yf_Registry::get('paycenter_api_url');
						$paycenter_app_id = Yf_Registry::get('paycenter_app_id');
						$formvars = array();

						$formvars['order_id']    = $order_goods_info['order_id'];
						$formvars['user_id'] = $vs;
						$formvars['user_money'] = $dc[$ks];
						$formvars['reason'] = '订单'.$order_goods_info['order_id'].'退还分销佣金';
						$formvars['app_id']        = $paycenter_app_id;
						$formvars['type']		= 'row';

						$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=returnDirectsellerOrder&typ=json', $url), $formvars);
					}
				}
			}
			else
			{
				//直接修改订单表中分销总金额
				$condition = array();
				$condition['order_directseller_commission'] = $order_base['order_directseller_commission'] - array_sum($dc);
				$Order_BaseModel->editBase($order_goods_info['order_id'], $condition);
			}

			//2.判断该笔退款发生在订单结算前还是订单结算后
			if($order_base['order_settlement_time'] !== '0000-00-00 00:00:00')
			{
				//订单已经结算，需要将多扣除的分销佣金退还给商家
				//将需要确认的订单号远程发送给Paycenter修改订单状态
				//远程修改paycenter中的订单状态
				$key      = Yf_Registry::get('paycenter_api_key');
				$url         = Yf_Registry::get('paycenter_api_url');
				$paycenter_app_id = Yf_Registry::get('paycenter_app_id');
				$formvars = array();

				$formvars['order_id']    = $order_goods_info['order_id'];
				$formvars['user_id'] = $order_base['seller_user_id'];
				$formvars['user_money'] = array_sum($dc);
				$formvars['reason'] = '订单'.$order_goods_info['order_id'].'退还分销佣金';
				$formvars['app_id']        = $paycenter_app_id;
				$formvars['type']		= 'row';

				$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=DirectsellerOrder&typ=json', $url), $formvars);

			}
		}

	}
    /**
     * 获取（地区，时间段）下单金额
     *
     * 1,在线支付订单已付款，已完成
     * 2，货到付款，已完成
     * 3，时间区间内
     * @author xzg
     * @param $cond_row
     * @return array
     */
    public function getOrderGoodsPrices($cond_row)
    {
            $where = "where goods_id ='" . $cond_row['goods_id'] . "' and order_goods_status in ('" . Order_StateModel::ORDER_FINISH . "','" . Order_StateModel::ORDER_PAYED . "','".Order_StateModel::ORDER_FINISH."')";
            $sql = "SELECT SUM(order_goods_amount) sums from `yf_order_goods` {$where}";
            $sums = $this->sql->getAll($sql);
            return $sums[0]['sums'];
    }

    /**
     * 获取某个时间段的下单总量
     * @param $cond_row
     * @return mixed
     */
    public function  getOrderCountSum($cond_row){
        $where = 'where goods_id='.$cond_row['goods_id'].' and order_goods_status >'.Order_StateModel::ORDER_WAIT_PAY.' and order_goods_time>="'.$cond_row['stime'].' 00:00:00" and order_goods_time<="'.$cond_row['etime'].' 23:59:59"';
        $sql = " SELECT FROM_UNIXTIME(unix_timestamp(order_goods_time),'%Y-%m-%d') as time,order_goods_amount as aop_price, 1 as aop_num  from `yf_order_goods` {$where}";
        $order_goods_list = $this->sql->getAll($sql);
        return $order_goods_list;
    }
 
}

?>
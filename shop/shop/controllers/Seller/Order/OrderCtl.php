<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     windfnn
 */
class Seller_Order_OrderCtl extends Seller_Controller
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
	}

	/**
	 * 实物交易订单
	 *
	 * @access public
	 */
	public function physical()
	{
		include $this->view->getView();
	}
	
	/**
	 * 虚拟交易订单
	 *
	 * @access public
	 */
	public function virtual()
	{
		include $this->view->getView();
	}

	//过期取消订单
	public function cancelOrder()
	{
		$Order_BaseModel  = new Order_BaseModel();
		$Order_GoodsModel = new Order_GoodsModel();
		//开启事物
		$Order_BaseModel->sql->startTransactionDb();

		//查找出所有待付款的订单
		$order_list = $Order_BaseModel->getByWhere(array('order_status' => Order_StateModel::ORDER_WAIT_PAY));

		foreach ($order_list as $key => $val)
		{
			$diff = time() - strtotime($val['order_create_time']);

			if ($diff >= Yf_Registry::get('wait_pay_time'))
			{
				//取消订单

				//加入取消时间
				$condition['order_status']          = Order_StateModel::ORDER_CANCEL;
				$condition['order_cancel_reason']   = '支付超时自动取消';
				$condition['order_cancel_identity'] = Order_BaseModel::IS_ADMIN_CANCEL;
				$condition['order_cancel_date']     = get_date_time();

				$flag = $Order_BaseModel->editBase($val['order_id'], $condition);

				//修改订单商品表中的订单状态
				$edit_row['order_goods_status'] = Order_StateModel::ORDER_CANCEL;
				$order_goods_id                 = $Order_GoodsModel->getKeyByWhere(array('order_id' => $val['order_id']));

				$Order_GoodsModel->editGoods($order_goods_id, $edit_row);

				//退还订单商品的库存
                if($val['chain_id']!=0){
                    $Chain_GoodsModel = new Chain_GoodsModel();
                    $chain_row['chain_id:='] = $val['chain_id'];
                    $chain_row['goods_id:IN'] = $order_goods_id;
                    $chain_row['shop_id:='] = $val['shop_id'];
                    $chain_goods = $Chain_GoodsModel->getByWhere($chain_row);
                    foreach($chain_goods as $v){
                        $chain_goods_id = $v['chain_goods_id'];
                        $goods_stock['goods_stock'] = $v['goods_stock'] + 1;
                        $Chain_GoodsModel->editGoods($chain_goods_id, $goods_stock);
                    }
                }else{
                    $Goods_BaseModel = new Goods_BaseModel();
                    $Goods_BaseModel->returnGoodsStock($order_goods_id);
                }

			}
		}

		if ($flag && $Order_BaseModel->sql->commitDb())
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$Order_BaseModel->sql->rollBackDb();
			$m      = $Order_BaseModel->msg->getMessages();
			$msg    = $m ? $m[0] : __('failure');
			$status = 250;
		}

	}
}

?>
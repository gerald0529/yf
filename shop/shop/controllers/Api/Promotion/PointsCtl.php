<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Api接口, 让App等调用
 *
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class Api_Promotion_PointsCtl extends Api_Controller
{
	public $Points_GoodsModel = null;
	public $Points_OrderModel = null;
	public $ExpressModel      = null;

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
		$this->Points_GoodsModel = new Points_GoodsModel();
		$this->Points_OrderModel = new Points_OrderModel();
		$this->ExpressModel      = new ExpressModel();
	}

	/*积分礼品列表*/
	public function getPointsGoodsList()
	{
		$page              = request_int('page', 1);
		$rows              = request_int('rows', 100);
		$points_goods_name = request_string('points_goods_name');
		$cond_row          = array();

		if ($points_goods_name)
		{
			$cond_row['points_goods_name:LIKE'] = $points_goods_name . '%';
		}

		$data = $this->Points_GoodsModel->getPointsGoodsList($cond_row, array('points_goods_id'=>'DESC'), $page, $rows);

		$this->data->addBody(-140, $data);
	}

	/*积分兑换列表*/
	public function getPointsOrderList()
	{
		$page             = request_int('page', 1);
		$rows             = request_int('rows', 100);
		$points_order_rid = request_string('points_order_rid');
		$points_buyername = request_string('points_buyername');
		$cond_row         = array();

		if ($points_order_rid)
		{
			$cond_row['points_order_rid:LIKE'] = $points_order_rid . '%';
		}
		if ($points_buyername)
		{
			$cond_row['points_buyername:LIKE'] = $points_buyername . '%';
		}

		$data = $this->Points_OrderModel->getPointsOrderList($cond_row, array('points_order_id' => 'DESC'), $page, $rows);
		$this->data->addBody(-140, $data);
	}

	//获取积分订单详情
	public function getPointsOrderInfo()
	{
		$points_order_id = request_int('id');

		$data = $this->Points_OrderModel->getPointsOrderInfo($points_order_id);

		$this->data->addBody(-140, $data);
	}

	//添加积分商品
	public function addPointsGoods()
	{
		$field_row['points_goods_name']    = request_string('points_goods_name');//礼品名称
		$field_row['points_goods_price']   = request_string('points_goods_price');//礼品原价
		$field_row['points_goods_points']  = request_int('points_goods_points');//兑换积分
		$field_row['points_goods_serial']  = request_string('points_goods_serial');//礼品编号
		$field_row['points_goods_image']   = request_string('points_goods_image');//礼品图片
		$field_row['points_goods_tag']     = request_string('points_goods_tag');//礼品标签
		$field_row['points_goods_storage'] = request_int('points_goods_storage');//礼品库存
		if (request_int('islimit') == Points_GoodsModel::ISNUMLIMIT)//有兑换数量限制
		{
			$field_row['points_goods_islimit']  = request_int('islimit');//限制兑换数量
			//如果限购，则限购数量应该大于0小于等于库存，否则限购数量设为1
			if(request_int('limitnum') > 0 && request_int('limitnum') <= request_int('points_goods_storage'))
			{
				$field_row['points_goods_limitnum'] = request_int('limitnum');//限制兑换数量
			}
			else
			{
				$msg    = __('限兑数量有误');
				$status = 250;
				return $this->data->addBody(-140, [], $msg, $status);
			}
		}
		elseif (request_int('islimit') == Points_GoodsModel::NONUMLIMIT)//没有兑换数量限制
		{
			$field_row['points_goods_islimit']  = 0;//限制兑换数量
			$field_row['points_goods_limitnum'] = 0;//限制兑换数量
		}

		if (request_int('islimittime') == Points_GoodsModel::ISTLIMIT)//有兑换时间限制
		{
			$field_row['points_goods_islimittime'] = Points_GoodsModel::ISTLIMIT;//限制兑换时间
			$field_row['points_goods_starttime']   = request_string('starttime');//兑换开始时间
			$field_row['points_goods_endtime']     = request_string('endtime');//兑换结束时间
		}
		elseif (request_int('islimittime') == Points_GoodsModel::NOTLIMIT)
		{
			$field_row['points_goods_islimittime'] = Points_GoodsModel::NOTLIMIT;//限制兑换时间
			$field_row['points_goods_starttime']   = 0;//兑换开始时间
			$field_row['points_goods_endtime']     = 0;//兑换结束时间
		}

		$field_row['points_goods_add_time']    = get_date_time();//发布时间
		$field_row['points_goods_limitgrade']  = request_int('limitgrade');//会员兑换等级限制
		$field_row['points_goods_shelves']     = request_int('points_goods_shelves');//是否上架
		$field_row['points_goods_recommend']   = request_int('points_goods_recommend');//是否推荐
		$field_row['points_goods_keywords']    = request_string('keywords');//关键字
		$field_row['points_goods_description'] = request_string('description');//seo 描述
		$field_row['points_goods_body']        = request_string('points_goods_body');//礼品描述
		$field_row['points_goods_sort']        = request_int('points_goods_sort');//礼品排序

		//库存必须大于0，当库存为0将下架
		if($field_row['points_goods_storage'] > 0)
		{
			if (request_string('operate') == 'add')
			{
				$flag                      = $this->Points_GoodsModel->addPointsGoods($field_row, true);
				$data['points_goods_id'] = $points_goods_id = $flag;
			}
			elseif (request_string('operate') == 'edit')
			{
				$points_goods_id = request_int('points_goods_id');
				$this->Points_GoodsModel->editPointsGoods($points_goods_id, $field_row);
				$data['points_goods_id'] = $points_goods_id;
				$flag                    = true;
			}

			$data = $this->Points_GoodsModel->getPointsGoodsByID($points_goods_id);
		}
		else
		{
			$flag = false;
		}


		if ($flag)
		{
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
	
	public function managePointsGoods()
	{
		$points_goods_id = request_int('id');
		if ($points_goods_id)
		{
			$data = $this->Points_GoodsModel->getPointsGoodsByID($points_goods_id);
		}
		else
		{
			$data = array();
		}
		
		$userGradeModel        = new User_GradeModel();
        $data['user_grade']    = $userGradeModel->getGradeList();

		$this->data->addBody(-140, $data);
	}

	public function removePointsGoods()
	{
		$points_goods_id = request_int('points_goods_id');

		$flag = $this->Points_GoodsModel->removePointsGoods($points_goods_id);

		if ($flag)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		$data['points_goods_id'] = $points_goods_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function cancelPointsOrder()
	{
		$data            = array();
		$points_order_id = request_int('points_order_id');
		if ($points_order_id)
		{
			$field_row['points_orderstate'] = Points_OrderModel::CANCEL;
			$this->Points_OrderModel->editPointsOrder($points_order_id, $field_row);

			$data                            = $this->Points_OrderModel->getOnePointsOrderByID($points_order_id);
			$data['points_orderstate_label'] = Points_OrderModel::$order_state_map[Points_OrderModel::CANCEL];//订单状态
		}

		$this->data->addBody(-140, $data);
	}

	public function deliver()
	{
		$data = array();

		$points_order_id = request_int('id');
		if ($points_order_id)
		{
			$data['p_order']                    = $this->Points_OrderModel->getOnePointsOrderByID($points_order_id);
			$cond_express_row['express_status'] = 1;
			$data['express_list']               = $this->ExpressModel->getExpressList($cond_express_row);
		}

		$this->data->addBody(-140, $data);
	}

	//平台发货
	public function pointsOrderDeliver()
	{
		$points_order_id                  = request_int('points_order_id');
		$field_row['points_shippingcode'] = request_string('points_shippingcode');//物流单号
		$field_row['points_logistics']    = request_string('points_logistics'); //物流公司名称
		$field_row['points_shippingtime'] = get_date_time();//配送时间
		$field_row['points_orderstate']   = Points_OrderModel::DELIVERED;//订单状态
		$this->Points_OrderModel->editPointsOrder($points_order_id, $field_row);

		$data                            = $field_row;
		$data['points_order_id']         = $points_order_id;
		$data['points_orderstate_label'] = Points_OrderModel::$order_state_map[Points_OrderModel::DELIVERED];//订单状态

		$this->data->addBody(-140, $data);
	}


}

?>
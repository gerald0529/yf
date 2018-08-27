<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Buyer_PointsCtl extends Buyer_Controller
{
	public $pointsLogModel   = null;
	public $pointsOrderModel = null;


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
		$this->pointsLogModel        = new Points_LogModel();
		$this->pointsOrderModel      = new Points_OrderModel();
		$this->pointsOrderGoodsModel = new Points_OrderGoodsModel();
		$this->webConfigModel        = new Web_ConfigModel();

	}

	/**
	 * 用户积分页面
	 * @access public
	 * 
	 */
	public function points()
	{
		$op = request_string('op');

		$Yf_Page           = new Yf_Page();
		$Yf_Page->listRows = request_int('listRows')?request_int('listRows'):10;
		$rows              = $Yf_Page->listRows;
		$offset            = request_int('firstRow', 0);

		if((int)$_REQUEST['curpage']){
			$page = (int)$_REQUEST['curpage'];
		}else{
			$page              = ceil_r($offset / $rows);
		}

		
		if ($op == 'getPointsOrder')//兑换记录 积分订单
		{
			
			$state                         = request_int('state');
			$cond_row['points_buyerid']    = Perm::$userId;
			if($state){
				$cond_row['points_orderstate'] = $state;
			}

            $order_row['points_order_id'] = 'DESC';

			$data = $this->pointsOrderModel->getPointsOrderListByWhere($cond_row, $order_row, $page, $rows);

			$Yf_Page->totalRows = $data['totalsize'];

            $express=new ExpressModel;
            foreach($data['items'] as $key=>$val){
                $express_id=$express->getOneByWhere(['express_name'=>$val['points_logistics']]);

                $data['items'][$key]['points_express_id']=$express_id['express_id'];
            }

			$page_nav           = $Yf_Page->prompt();
			$this->view->setMet('getPointsOrder');
		}
		else
		{
			
			$cond_row['user_id'] = Perm::$userId;
			$start_date         = request_string("start_date");
			$end_date            = request_string("end_date");
			$class_id            = request_string("class_id");
			$des                 = request_string("des");
			$class               = "";
			//积分获取的默认设置
			$web                    = array();
			$web['points_reg']      = Web_ConfigModel::value("points_reg");//注册获取积分
			$web['points_login']    = Web_ConfigModel::value("points_login");//登陆获取积分
			$web['points_evaluate'] = Web_ConfigModel::value("points_evaluate");//评论获取积分
			$web['points_recharge'] = Web_ConfigModel::value("points_recharge");//订单每多少获取多少积分
			$web['points_order']    = Web_ConfigModel::value("points_order");//订单每多少获取多少积分
			
			$classId = Points_LogModel::$classId;
			
			$order_row = array();
			$order_row = array('points_log_time' => 'DESC');
			
			if ($start_date)
			{
				$cond_row['points_log_time:>='] = $start_date;
			}
			if ($end_date)
			{
				$cond_row['points_log_time:<='] = $end_date;
			}
			if ($class_id)
			{
				$cond_row['class_id'] = $class_id;
				
				$class = __(Points_LogModel::$classId[$class_id]);
			}
			if ($des)
			{
				$type            = 'points_log_desc:LIKE';
				$cond_row[$type] = '%' . $des . '%';
			}
			$cond_row['points_log_points:!='] = 0;
			$data = $this->pointsLogModel->getPointsLogList($cond_row, $order_row, $page, $rows);

			$data['hasmore'] = $page >= $data['total'] ?false:true;

			$Yf_Page->totalRows = $data['totalsize'];

			$page_nav           = $Yf_Page->prompt();
			
			$data['web'] = $web;

			$this->userResourceModel = new User_ResourceModel();
			$data['points']          = $this->userResourceModel->getOne(Perm::$userId);
		}
		fb($data);
		if ('json' == $this->typ)
		{

			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}
	}

	/**
	 * 确认收货
	 *
	 * @author     Zhuyt
	 */
	public function confirmOrder()
	{
		$typ = request_string('typ');

		if ($typ == 'e')
		{
			if ('json' == $this->typ)
			{
				$data = array();
				$this->data->addBody(-140, $data);
			}
			else
			{
				include $this->view->getView();
			}
		}
		else
		{

			$points_order_id = request_string('order_id');

			$condition['points_orderstate'] = Points_OrderModel::CONFIRM;

			$condition['points_finnshedtime'] = get_date_time();

			$flag = $this->pointsOrderModel->editPointsOrder($points_order_id, $condition);


			if ($flag !== false)
			{
				$status = 200;
				$msg    = __('success');
			}
			else
			{

				$msg    = __('failure');
				$status = 250;
			}

			$this->data->addBody(-140, array(), $msg, $status);
		}

	}

	public function getOrderDetail()
	{
		$points_order_id = request_string('order_id');

		$pointsCartModel = new Points_OrderModel();;

		$data= $pointsCartModel->getPointsOrderInfo($points_order_id);

		$data['points_order_goods_list'] = array_values($data['points_order_goods_list']);

		$express=new ExpressModel;

		$data['express']=$express->getOneByWhere(['express_name'=>$data['points_logistics']]);
		

		if ($data['points_buyerid'] ===  Perm::$userId) {

			$status = 200;
			$msg    = __('success');
		} else {
			$data = array();
			$msg    = __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, $data, $msg, $status);

	}



	public function getPointGoodsByCart()
	{
        $pointsCartModel = new Points_CartModel;

        $point_cart_ids = request_row('point_cart_ids');

        $rows = $pointsCartModel->getByWhere([
            'points_cart_id:IN'=> $point_cart_ids
        ]);

        if ($rows === false) {
            $msg    = __('failure');
            $status = 250;
        } else {
            $rows = array_values($rows);
            $status = 200;
            $msg    = __('success');
        }

        $this->data->addBody(-140, $rows, $msg, $status);
	}
}

?>
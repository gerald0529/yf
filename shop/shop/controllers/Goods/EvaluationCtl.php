<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_EvaluationCtl extends Yf_AppController
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

		//include $this->view->getView();
		$this->goodsImagesModel     = new Goods_ImagesModel();
		$this->goodsEvaluationModel = new Goods_EvaluationModel();
	}

	/**
	 * 添加商品评论
	 *
	 * @access public
	 */
	public function addGoodsEvaluation()
	{
		//开启事物
		$this->goodsEvaluationModel->sql->startTransactionDb();
		
		if (Perm::checkUserPerm())
		{
			$user_id      = Perm::$row['user_id'];
			$user_account = Perm::$row['user_account'];
		}

		$evaluation = request_row('evaluation');

        if(request_int('wxapp') == 1){
            $evaluation = json_decode($evaluation);
        }

		if (empty($evaluation)) {
			return $this->data->addBody(-140, ['request'=> $_REQUEST], __('无效参数'), 250);
		}

		if (request_int('app') == 1) { //移动端 json格式
			$evaluation = json_decode($evaluation);
		}

		foreach($evaluation as $key => $val)
		{

			//订单商品信息
			$Order_GoodsModel = new Order_GoodsModel();
			$order_goods      = $Order_GoodsModel->getOne($val[0]);

			//商品信息
			$Goods_BaseModel = new Goods_BaseModel();
			$goods_base      = $Goods_BaseModel->getOne($order_goods['goods_id']);

			//订单信息
			$Order_BaseModel = new Order_BaseModel();
			$order_base      = $Order_BaseModel->getOne($order_goods['order_id']);

			$Goods_CommonModel = new Goods_CommonModel();

			$matche_row = array();
			//有违禁词
			if (Text_Filter::checkBanned($val[3], $matche_row))
			{
				$data   = array();
				$msg    = __('含有违禁词');
				$status = 250;
				$this->data->addBody(-140, array(), $msg, $status);
				return false;
			}

			//修改商品的评价
			$evaluation_num = $this->goodsEvaluationModel->countGoodsEvaluation($order_goods['goods_id']);

			//星级好评数
			$goods_evaluation_good_star = ceil(($evaluation_num * $goods_base['goods_evaluation_good_star'] + $val[1]) / ($evaluation_num * 1 + 1));
			$goods_evaluation_count     = $evaluation_num * 1 + 1;

			$edit_row                               = array();
			$edit_row['goods_evaluation_good_star'] = $goods_evaluation_good_star;
			$edit_row['goods_evaluation_count']     = $goods_evaluation_count;

			fb($evaluation_num);

			fb($edit_row);

			$Goods_BaseModel->editBaseFalse($order_goods['goods_id'], $edit_row);

			//修改商品common表中的评论数量
			$edit_common_row['common_evaluate'] = 1;
			$Goods_CommonModel->editCommonTrue($order_goods['common_id'],$edit_common_row);

			//插入商品评价表
			$add_row                = array();
			$add_row['user_id']     = $user_id;
			$add_row['member_name'] = $user_account;
			$add_row['order_id']    = $order_base['order_id'];    //订单id
			$add_row['shop_id']     = $order_base['shop_id'];        //商家id
			$add_row['shop_name']   = $order_base['shop_name'];    //店铺名称
			$add_row['common_id']   = $order_goods['common_id'];
			$add_row['goods_id']    = $order_goods['goods_id'];    //商品id
			$add_row['goods_name']  = $order_goods['goods_name'];//商品名称
			$add_row['goods_price'] = $order_goods['goods_price'];    //商品价格
			$add_row['goods_image'] = $order_goods['goods_image'];    //商品图片
			$add_row['scores']      = $val[1];
			$add_row['result']      = $val[2];
			$add_row['content']     = $val[3];
			$add_row['image']       = $val[4]; //wap端和pc端混乱 pc端这个字段为图片地址

			//wap端传递的匿名信息
			if($val[5])
			{
				$add_row['isanonymous'] = $val[5];    //是否匿名
			}
			else
			{
				$add_row['isanonymous'] = request_int('isanonymous');    //是否匿名
			}

			$add_row['create_time'] = get_date_time();        //创建时间
			$add_row['status']      = Goods_EvaluationModel::SHOW;

//			echo '<pre>';print_r($add_row);exit;
			$flag = $this->goodsEvaluationModel->addEvalution($add_row);
//			return $this->data->addBody(-140, [$flag], __('无效参数'), 250);
			if($flag)
			{
				/********************************************************/
				//评价成功添加数据到统计中心  商品评分
				$analytics_data = array(
					'product_id'=>$goods_base['goods_id'],
					'shop_id'=>$goods_base['shop_id'],
					'score'=>$goods_evaluation_good_star,
				);
				Yf_Plugin_Manager::getInstance()->trigger('analyticsScore',$analytics_data);
				/********************************************************/
			}

			//修改订单商品表
			$edit_order_goods['order_goods_evaluation_status'] = Order_GoodsModel::EVALUATION_YES;
			$Order_GoodsModel->editGoods($val[0], $edit_order_goods);

		}

		$package_scores = request_int('package_scores'); //描述相符
		$send_scores    = request_int('send_scores');    //发货速度
		$service_scores = request_int('service_scores');  //服务态度

		$Shop_EvaluationModel      = new Shop_EvaluationModel();

		$add_shop_row                              = array();
		$add_shop_row['shop_id']                   = $order_base['shop_id'];
		$add_shop_row['user_id']                   = $user_id;
		$add_shop_row['order_id']                  = $order_base['order_id'];
		$add_shop_row['evaluation_desccredit']     = $package_scores;
		$add_shop_row['evaluation_servicecredit']  = $service_scores;
		$add_shop_row['evaluation_deliverycredit'] = $send_scores;
		$add_shop_row['evaluation_create_time']    = get_date_time();

		$Shop_EvaluationModel->addEvaluation($add_shop_row);

		/********************************************************/
		//评价成功添加数据到统计中心  店铺评分
		$analytics_data = array(
			'shop_id'=>$order_base['shop_id']
		);
		Yf_Plugin_Manager::getInstance()->trigger('analyticsShopCredit',$analytics_data);
		/********************************************************/
		
		//修改订单中的评价信息
		$edit_order['order_buyer_evaluation_status'] = Order_BaseModel::BUYER_EVALUATE_YES;
		$edit_order['order_buyer_evaluation_time']   = get_date_time();
		$Order_BaseModel->editBase($order_base['order_id'], $edit_order);

		if ($flag && $this->goodsEvaluationModel->sql->commitDb())
		{
			$status = 200;
			$msg    = __('success');
			
			/*
			*  经验与成长值
			*/
			$user_points = Web_ConfigModel::value("points_evaluate");
			$user_grade  = Web_ConfigModel::value("grade_evaluate");

			$User_ResourceModel = new User_ResourceModel();
			//获取积分经验值
			$ce = $User_ResourceModel->getResource(Perm::$userId);

			$resource_row['user_points'] = $ce[Perm::$userId]['user_points'] * 1 + $user_points * 1;
			$resource_row['user_growth'] = $ce[Perm::$userId]['user_growth'] * 1 + $user_grade * 1;

			$res_flag = $User_ResourceModel->editResource(Perm::$userId, $resource_row);

			$User_GradeModel = new User_GradeModel;
			//升级判断
			$res_flag = $User_GradeModel->upGrade(Perm::$userId, $resource_row['user_growth']);
			//积分
			$points_row['user_id']           = Perm::$userId;
			$points_row['user_name']         = Perm::$row['user_account'];
			$points_row['class_id']          = Points_LogModel::ONEVALUATION;
			$points_row['points_log_points'] = $user_points;
			$points_row['points_log_time']   = get_date_time();
			$points_row['points_log_desc']   = '评价订单';
			$points_row['points_log_flag']   = 'evaluation';

			$Points_LogModel = new Points_LogModel();

			$Points_LogModel->addLog($points_row);

			//成长值
			$grade_row['user_id']         = Perm::$userId;
			$grade_row['user_name']       = Perm::$row['user_account'];
			$grade_row['class_id']        = Grade_LogModel::ONEVALUATION;
			$grade_row['grade_log_grade'] = $user_grade;
			$grade_row['grade_log_time']  = get_date_time();
			$grade_row['grade_log_desc']  = '评价订单';
			$grade_row['grade_log_flag']  = 'evaluation';

			$Grade_LogModel = new Grade_LogModel;
			$Grade_LogModel->addLog($grade_row);
		}
		else
		{
			$this->goodsEvaluationModel->sql->rollBackDb();
			$m      = $this->goodsEvaluationModel->msg->getMessages();
			$msg    = $m ? $m[0] : __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, ['request'=> $_REQUEST], $flag, $status);

	}

	/**
	 * 追加商品评论
	 *
	 * @access public
	 */
	public function againGoodsEvaluation()
	{
		//开启事物
		$this->goodsEvaluationModel->sql->startTransactionDb();

		$rs_row = array();

		if (Perm::checkUserPerm())
		{
			$user_id      = Perm::$row['user_id'];
			$user_account = Perm::$row['user_account'];
		}

		$evaluation_goods_id = request_int('evaluation_goods_id');

		$evaluation_base = $this->goodsEvaluationModel->getOne($evaluation_goods_id);

		$order_id    = $evaluation_base['order_id'];    //订单id
		$shop_id     = $evaluation_base['shop_id'];        //商家id
		$shop_name   = $evaluation_base['shop_name'];    //店铺名称
		$common_id   = $evaluation_base['common_id'];
		$goods_id    = $evaluation_base['goods_id'];    //商品id
		$goods_name  = $evaluation_base['goods_name'];//商品名称
		$goods_price = $evaluation_base['goods_price'];    //商品价格
		$goods_image = $evaluation_base['goods_image'];    //商品图片
		$scores      = request_int('goods_scores');        //商品评分
		$result      = request_string('result');        //good,neutral,bad
		$content     = request_string('content');
		$img         = request_string('evaluate_img');        //晒单图
		$isanonymous = request_int('isanonymous');    //是否匿名（追加评论，默认为匿名）
		$create_time = get_date_time();        //创建时间


		$matche_row = array();
		//有违禁词
		if (Text_Filter::checkBanned($content, $matche_row))
		{
			$data   = array();
			$msg    = __('含有违禁词');
			$status = 250;
			$this->data->addBody(-140, array(), $msg, $status);
			return false;
		}

		//修改商品的评价
		$evaluation_num = $this->goodsEvaluationModel->countEvaluation($goods_id);
		$goods_evaluation_count     = $evaluation_num * 1 + 1;


		$edit_row                               = array();
		$edit_row['goods_evaluation_count']     = $goods_evaluation_count;

		$Goods_BaseModel = new Goods_BaseModel();
		$edit_flag =  $Goods_BaseModel->editBase($goods_id, $edit_row);
		check_rs($edit_flag,$rs_row);

		//修改商品common表中的评论数量
		/*$Goods_CommonModel = new Goods_CommonModel();
		$edit_common_row['common_evaluate'] = 1;
		$Goods_CommonModel->editCommonTrue($common_id,$edit_common_row);*/

		//插入商品评价表
		$add_row                = array();
		$add_row['user_id']     = $user_id;
		$add_row['member_name'] = $user_account;
		$add_row['order_id']    = $order_id;
		$add_row['shop_id']     = $shop_id;
		$add_row['shop_name']   = $shop_name;
		$add_row['common_id']   = $common_id;
		$add_row['goods_id']    = $goods_id;
		$add_row['goods_name']  = $goods_name;
		$add_row['goods_price'] = $goods_price;
		$add_row['goods_image'] = $goods_image;
		$add_row['scores']      = $scores;
		$add_row['result']      = $result;
		$add_row['content']     = $content;
		$add_row['image']       = $img;
		$add_row['isanonymous'] = $isanonymous;
		$add_row['create_time'] = $create_time;
		$add_row['status']      = Goods_EvaluationModel::SHOW;

		fb($add_row);
		$add_flag = $this->goodsEvaluationModel->addEvalution($add_row);
		check_rs($add_flag,$rs_row);

		//2017-05-18 追加评论完成后，订单表 order_goods_evaluation_status=2
		$order_goods_model = new Order_GoodsModel();
		$order_goods_id = request_int('order_goods_id');
		$og_update_flag = $order_goods_model->editGoods($order_goods_id, ['order_goods_evaluation_status'=> Order_GoodsModel::EVALUATION_AGAIN]);
		check_rs($og_update_flag,$rs_row);

		$flag = is_ok($rs_row);

		if ($flag && $og_update_flag && $this->goodsEvaluationModel->sql->commitDb())
		{
			$status = 200;
			$msg    = __('success');

			/*
			*  经验与成长值
			*/
			$user_points = Web_ConfigModel::value("points_evaluate");
			$user_grade  = Web_ConfigModel::value("grade_evaluate");

			$User_ResourceModel = new User_ResourceModel();
			//获取积分经验值
			$ce = $User_ResourceModel->getResource(Perm::$userId);

			$resource_row['user_points'] = $ce[Perm::$userId]['user_points'] * 1 + $user_points * 1;
			$resource_row['user_growth'] = $ce[Perm::$userId]['user_growth'] * 1 + $user_grade * 1;

			$res_flag = $User_ResourceModel->editResource(Perm::$userId, $resource_row);

			$User_GradeModel = new User_GradeModel;
			//升级判断
			$res_flag = $User_GradeModel->upGrade(Perm::$userId, $resource_row['user_growth']);
			//积分
			$points_row['user_id']           = Perm::$userId;
			$points_row['user_name']         = Perm::$row['user_account'];
			$points_row['class_id']          = Points_LogModel::ONEVALUATION;
			$points_row['points_log_points'] = $user_points;
			$points_row['points_log_time']   = get_date_time();
			$points_row['points_log_desc']   = '评价订单';
			$points_row['points_log_flag']   = 'evaluation';

			$Points_LogModel = new Points_LogModel();

			$Points_LogModel->addLog($points_row);

			//成长值
			$grade_row['user_id']         = Perm::$userId;
			$grade_row['user_name']       = Perm::$row['user_account'];
			$grade_row['class_id']        = Grade_LogModel::ONEVALUATION;
			$grade_row['grade_log_grade'] = $user_grade;
			$grade_row['grade_log_time']  = get_date_time();
			$grade_row['grade_log_desc']  = '评价订单';
			$grade_row['grade_log_flag']  = 'evaluation';

			$Grade_LogModel = new Grade_LogModel;
			$Grade_LogModel->addLog($grade_row);
		}
		else
		{
			$this->goodsEvaluationModel->sql->rollBackDb();
			$m      = $this->goodsEvaluationModel->msg->getMessages();
			$msg    = $m ? $m[0] : __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, array(), $msg, $status);
	}
/*
* wap追加商品评论
*
* @access public
*/
    public function againWapGoodsEvaluation()
    {
        //开启事物
        $this->goodsEvaluationModel->sql->startTransactionDb();


        if (Perm::checkUserPerm())
        {
            $user_id      = Perm::$row['user_id'];
            $user_account = Perm::$row['user_account'];
        }

        $evaluation = request_row('evaluation');


        foreach($evaluation as $key => $val)
        {
            //订单商品信息
            $Order_GoodsModel = new Order_GoodsModel();
            $order_goods      = $Order_GoodsModel->getOne($val[0]);

            //商品信息
            $Goods_BaseModel = new Goods_BaseModel();
            $goods_base      = $Goods_BaseModel->getOne($order_goods['goods_id']);

            //订单信息
            $Order_BaseModel = new Order_BaseModel();
            $order_base      = $Order_BaseModel->getOne($order_goods['order_id']);

            $Goods_CommonModel = new Goods_CommonModel();

            $matche_row = array();
            //有违禁词
            if (Text_Filter::checkBanned($val[3], $matche_row))
            {
                $data   = array();
                $msg    = __('含有违禁词');
                $status = 250;
                $this->data->addBody(-140, array(), $msg, $status);
                return false;
            }

            //修改商品的评价
            $evaluation_num = $this->goodsEvaluationModel->countEvaluation($order_goods['goods_id']);
            $goods_evaluation_count     = $evaluation_num * 1 + 1;

            $edit_row                               = array();
            $edit_row['goods_evaluation_count']     = $goods_evaluation_count;

            $Goods_BaseModel = new Goods_BaseModel();
            $Goods_BaseModel->editBase($order_goods['goods_id'], $edit_row);

            //修改商品common表中的评论数量
//            $edit_common_row['common_evaluate'] = 1;
//            $Goods_CommonModel->editCommonTrue($order_goods['common_id'],$edit_common_row);


            //插入商品评价表
            $add_row                = array();
            $add_row['user_id']     = $user_id;
            $add_row['member_name'] = $user_account;
            $add_row['order_id']    = $order_base['order_id'];    //订单id
            $add_row['shop_id']     = $order_base['shop_id'];        //商家id
            $add_row['shop_name']   = $order_base['shop_name'];    //店铺名称
            $add_row['common_id']   = $order_goods['common_id'];
            $add_row['goods_id']    = $order_goods['goods_id'];    //商品id
            $add_row['goods_name']  = $order_goods['goods_name'];//商品名称
            $add_row['goods_price'] = $order_goods['goods_price'];    //商品价格
            $add_row['goods_image'] = $order_goods['goods_image'];    //商品图片
            $add_row['scores']      = $val[1];
            $add_row['result']      = $val[2];
            $add_row['content']     = $val[3];
            $add_row['image']       = $val[4];
            $add_row['isanonymous'] = request_int('isanonymous');    //是否匿名
            $add_row['create_time'] = get_date_time();        //创建时间
            $add_row['status']      = Goods_EvaluationModel::SHOW;

            $flag = $this->goodsEvaluationModel->addEvalution($add_row);

        }

		//2017-05-18 追加评论完成后，订单表 order_buyer_evaluation_status=2
		$order_model = new Order_BaseModel;
		$update_order_flag = $order_model->editBase($order_base['order_id'], ['order_buyer_evaluation_status'=> Order_BaseModel::BUYER_EVALUATE_AGAIN]);

        if ($flag && $update_order_flag && $this->goodsEvaluationModel->sql->commitDb())
        {
            $status = 200;
            $msg    = __('success');

            /*
            *  经验与成长值
            */
            $user_points = Web_ConfigModel::value("points_evaluate");
            $user_grade  = Web_ConfigModel::value("grade_evaluate");

            $User_ResourceModel = new User_ResourceModel();
            //获取积分经验值
            $ce = $User_ResourceModel->getResource(Perm::$userId);

            $resource_row['user_points'] = $ce[Perm::$userId]['user_points'] * 1 + $user_points * 1;
            $resource_row['user_growth'] = $ce[Perm::$userId]['user_growth'] * 1 + $user_grade * 1;

            $res_flag = $User_ResourceModel->editResource(Perm::$userId, $resource_row);

            $User_GradeModel = new User_GradeModel;
            //升级判断
            $res_flag = $User_GradeModel->upGrade(Perm::$userId, $resource_row['user_growth']);
            //积分
            $points_row['user_id']           = Perm::$userId;
            $points_row['user_name']         = Perm::$row['user_account'];
            $points_row['class_id']          = Points_LogModel::ONEVALUATION;
            $points_row['points_log_points'] = $user_points;
            $points_row['points_log_time']   = get_date_time();
            $points_row['points_log_desc']   = '评价订单';
            $points_row['points_log_flag']   = 'evaluation';

            $Points_LogModel = new Points_LogModel();

            $Points_LogModel->addLog($points_row);

            //成长值
            $grade_row['user_id']         = Perm::$userId;
            $grade_row['user_name']       = Perm::$row['user_account'];
            $grade_row['class_id']        = Grade_LogModel::ONEVALUATION;
            $grade_row['grade_log_grade'] = $user_grade;
            $grade_row['grade_log_time']  = get_date_time();
            $grade_row['grade_log_desc']  = '评价订单';
            $grade_row['grade_log_flag']  = 'evaluation';

            $Grade_LogModel = new Grade_LogModel;
            $Grade_LogModel->addLog($grade_row);
        }
        else
        {
            $this->goodsEvaluationModel->sql->rollBackDb();
            $m      = $this->goodsEvaluationModel->msg->getMessages();
            $msg    = $m ? $m[0] : __('failure');
            $status = 250;
        }

        $this->data->addBody(-140, array(), $msg, $status);


    }

}

?>
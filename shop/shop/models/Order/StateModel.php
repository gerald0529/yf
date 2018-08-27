<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Order_StateModel extends Order_State
{
	const ORDER_WAIT_PAY           = 1;                //待付款     等待买家付款	     下单
	const ORDER_PAYED              = 2;                   // 已付款  待配货     等待卖家配货	     付款
	const ORDER_WAIT_PREPARE_GOODS = 3;      //待发货     等待卖家发货	     配货
	const ORDER_WAIT_CONFIRM_GOODS = 4;      //已发货     等待买家确认收货	 出库
	const ORDER_RECEIVED           = 5;                //已签收     买家已签收	     已签收
	const ORDER_FINISH             = 6;                  //已完成     交易成功	         交易成功
	const ORDER_CANCEL             = 7;                  //已取消     交易关闭	         交易关闭
	const ORDER_REFUND             = 8;                    //退款中
	const ORDER_REFUND_FINISH      = 9;            //退款完成
	const ORDER_SELF_PICKUP        = 11;            //代自提     交易关闭	         交易关闭

	const ORDER_GOODS_RETURN_NO  = 0;            //无退货
	const ORDER_GOODS_RETURN_IN  = 1;            //退货中
	const ORDER_GOODS_RETURN_END = 2;        //退货完成

	const ORDER_REFUND_NO  = 0;            //无退款
	const ORDER_REFUND_IN  = 1;            //退款中
	const ORDER_REFUND_END = 2;        //退款完成

	const FROM_PC = 1;			//PC端
	const FROM_WAP = 2;		//移动端


	const SUB_SELF_PAY = 0;  //自己支付
	const SUB_USER_PAY = 1;  //主管账号支付

	/*public static $orderState      = array(
	);*/

	public $orderFrom;
	public $evaluationStatus;

	public function __construct()
	{
		parent::__construct();

		$this->orderState = array(
			'1' => __("待付款"),
			//待付款
			'2' => __("已付款"),
			//待配货
			'3' => __("待发货"),
			//待发货
			'4' => __("已发货"),
			//已发货
			'5' => __("已签收"),
			//已签收
			'6' => __("已完成"),
			//已完成
			'7' => __("已取消"),
			//已取消
			'8' => __("退款中"),
			//已取消
			'9' => __("已退款"),
			//已取消
			'11' => __("待自提"),
			//已取消
		);

		$this->orderRefundState = array(
			'0' => __("无退款"),
			//无退款
			'1' => __("退款中"),
			//退款中
			'2' => __("退款完成"),
			//退款完成
		);

		$this->orderReturnState = array(
			'0' => __("无退货"),
			//无退货
			'1' => __("退货中"),
			//退货中
			'2' => __("退货完成"),
			//退货完成
		);

		$this->orderFrom = array(
			'1' => __("PC端"),
			//PC端
			'2' => __("移动端"),
			//移动端
		);
		
		$this->evaluationStatus = array(
			'0' => __("未评价"),
			//未评价
			'1' => __("已评价"),
			//已评价
		);
	}
}

?>
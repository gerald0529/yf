<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Order_StateModel
{
	const ORDER_WAIT_PAY = 1;                //待付款     等待买家付款	     下单
	const ORDER_PAYED = 2;                   //待配货     等待卖家配货	     付款
	const ORDER_WAIT_PREPARE_GOODS = 3;      //待发货     等待卖家发货	     配货
	const ORDER_WAIT_CONFIRM_GOODS = 4;      //已发货     等待买家确认收货	 出库
	const ORDER_RECEIVED = 5;                //已签收     买家已签收	         已签收
	const ORDER_FINISH = 6;                  //已完成     交易成功	         交易成功
	const ORDER_CANCEL = 7;                  //已取消     交易关闭	         交易关闭

	public function __construct()
	{

		$this->orderState = array(
			'1' => _("待付款"),
			//待付款
			//'2' => _("已付款"),
			'2' => _("等待发货"),
			//待配货
			'3' => _("待发货"),
			//待发货
			//'4' => _("已发货"),
			'4' => _("未确认收货"),
			//已发货
			'5' => _("已签收"),
			//已签收
			'6' => _("已完成"),
			//已完成
			'7' => _("已取消"),
			//已取消
			'8' => _("退款中"),
			//已取消
			'9' => _("已退款"),
			//已取消
			'11' => _("待自提"),
			//已取消
		);

		$this->orderRefundState = array(
			'0' => _("无退款"),
			//无退款
			'1' => _("退款中"),
			//退款中
			'2' => _("退款完成"),
			//退款完成
		);

		$this->orderReturnState = array(
			'0' => _("无退货"),
			//无退货
			'1' => _("退货中"),
			//退货中
			'2' => _("退货完成"),
			//退货完成
		);

		$this->orderFrom = array(
			'1' => _("PC端"),
			//PC端
			'2' => _("移动端"),
			//移动端
		);

		$this->evaluationStatus = array(
			'0' => _("未评价"),
			//未评价
			'1' => _("已评价"),
			//已评价
		);
	}

}
?>
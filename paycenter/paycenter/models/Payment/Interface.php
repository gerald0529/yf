<?php if (!defined('ROOT_PATH')) exit('No Permission');
/*
		$trade_row = array();
		$trade_row['order_id'] = 'fdafajfkl2lj2k31232132131212';
		$trade_row['trade_title'] = 'fafe43jklfdaf';
		$trade_row['trade_desc'] = 'fadafafjkljfkadf';
		$trade_row['trade_payment_amount'] = '0.1';
		$trade_row['trade_type_id'] = '1';
*/
interface Payment_Interface
{
	/**
	 * Constructor
	 *
	 * @param  array $payment_row  支付平台信息
	 * @param  array $order_row    订单信息
	 * @access public
	 */
	public function __construct($payment_row = array(), $order_row = array());

	/**
	 * 支付
	 *
	 * @access public
	 */
	public function pay($order_row);

	/**
	 *
	 * 取得订单支付状态，成功或失败
	 * @param array $param
	 * @return array
	 */
	public function getPayResult($param);

	/**
	 * 通知验证
	 *
	 * @access public
	 */
	public function verifyNotify();

	/**
	 * 回调验证
	 *
	 * @access public
	 */
	public function verifyReturn();


	/**
	 * 签名
	 *
	 * @access public
	 */
	public function sign($parameter);

	/**
	 * 发送请求
	 *
	 * @access public
	 */
	public function request();
}

?>
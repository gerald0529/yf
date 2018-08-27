<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Trade_TypeModel
{

	const SHOPPING = 1;  //购物
	const TRANSFER = 2;  //转账
	const DEPOSIT  = 3; //充值
	const WITHDRAW = 4;  //提现
	const REFUND	= 5;  //退款
	const RECEIPT  = 6;  //收款
	const PAY		= 7;   //付款
	const CREDIT_RETURN		= 8;   //白条还款
	const WEB_POS = 9; //webPos付款

	public static $trade_type_row = array(
		'1' => 'shopping',
		'2' => 'transfer',
		'3' => 'deposit',
		'4' => 'withdraw',
		'5' => 'refund',
		'6' => 'receipt',
		'7' => 'pay',
		'8' => 'credit_return',
		'9' => 'web_pos'

	);
    public function __construct()
	{
		$this->trade_type = array(
			'1' => _('购物'),
			'2' => _('转账'),
			'3' => _('充值'),
			'4' => _('提现'),
            '5' => _('退款'),
            '6' => _('收款'),
			'7' => _('付款'),
			'8' => _('白条还款'),
            '9' => _('pos端购物'),
		);
	}
        

}
?>
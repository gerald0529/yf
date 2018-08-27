<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class RecordStatusModel
{
	const IN_HAND = 1; //处理中
	const RECORD_FINISH = 2; //交易完成
	const RECORD_CANCEL = 3; //交易取消
	const RECORD_FAIL = 4; //交易失败
	const RECORD_WAIT_SEND_GOODS = 5; //待发货
	const RECORD_WAIT_CONFIRM_GOODS = 6; //待收货

	public function __construct()
	{
		$this->recordStatus = array(
			'1' => _('处理中'),
			'2' => _('交易完成'),
			'3' => _('交易取消'),
			'4' => -('交易失败'),
		);
		$this->userType = array(
			'1' => _('收款方'),
			'2' => _('付款方'),
            '3'=>_('管理员'),
		);

	}

}
?>
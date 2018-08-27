<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}
/**
 * 退款
 * @author yuli
 */

class RefundCtl extends Controller
{
    /**
     * RefundCtl constructor.
     * @param string $ctl
     * @param string $met
     * @param string $typ
     */
    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);
    }

    public function refund()
    {
        $order_id = request_string('order_id');

        $refundModel = new refundModel($order_id);
        $resultList = $refundModel->refund();
        $this->data->addBody(-140, $resultList, 'success', 200);
    }
}
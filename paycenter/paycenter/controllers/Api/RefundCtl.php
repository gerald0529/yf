<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

class Api_RefundCtl extends Api_Controller
{
    public $unionModel;

    public function __construct($ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);
        $this->unionModel = new Union_OrderModel;
    }

    public function pintuan()
    {
        $unionOrderIds = request_row('unionOrderIds');

        if (empty($unionOrderIds)) {
            throw new Exception('unionOrderIds为空');
        }

        $refundModel = new refundModel();
        $resultList = $refundModel->refund($unionOrderIds);
        $this->data->addBody(-140, $resultList, 'success', 200);
    }

    public function refund()
    {
        $arr = [
            'order_number'=> request_string('order_number'),
            'shop_id'=> request_string('shop_id'),
            'refund_amount'=> request_string('refund_amount'),
            'return_number'=> request_string('return_number'),
            'return_goods_name'=> request_string('return_goods_name'),
        ];

        $refundModel = new refundModel();
        $flag = $refundModel->refundSingle($arr);

        if ($flag) {
            $status = 200;
            $msg = 'success';
        } else {
            $status = 250;
            $msg = 'failure';
        }
        $this->data->addBody(-140, [], $msg, $status);
    }
}
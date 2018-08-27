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
 * @author     banchangle <1427825015@qq.com>
 * @copyright  Copyright (c) 2016, 班常乐
 * @version    1.0
 * @todo
 */
class Api_Paycen_PayRecordCtl extends Api_Controller
{
    /**
     *交易流水
     *
     * @access public
     */

    function getRecordList() {
        $username  = request_string('userName');   //用户名称
        $payorder  = request_string('payOrder');   //支付单号
        $trade_type_id = request_int('trade_type_id');
        $page = request_int('page');
        $rows = request_int('rows');
        $cond_row = array();
        $Consume_RecordModel = new Consume_RecordModel();
        $data           = $Consume_RecordModel->getRecordList(null,null,null,$page,$rows,'asc',$username,$trade_type_id,$payorder);
        if ($data)
        {
            $msg    = 'success';
            $status = 200;
        }
        else
        {
            $msg    = 'failure';
            $status = 250;
        }
        $this->data->addBody(-140, $data, $msg, $status);
    }

    //获取到还款期的白条订单
    public function getBtOrder()
    {
        $day_type = request_int('day_type');  //提醒还款：1：还有7天到还款限期  2：当天为还款期限
        $user_id = request_int('user_id');

        //查找用户的白条还款期限
        $User_ResourceModel = new User_ResourceModel();
        $user_res = $User_ResourceModel->getResource($user_id);
        $user_res = current($user_res);

        $user_credit_cycle = $user_res['user_credit_cycle'];

        if($day_type == 1)
        {
            $day = $user_credit_cycle*1 + 7;
        }
        else
        {
            $day = $user_credit_cycle;
        }

        $time1 = date("Y-m-d 00:00:00", strtotime('-'.$day.' day'));
        $time2 = date("Y-m-d 23:59:59", strtotime('-'.$day.' day'));

        $Consume_TradeModel = new Consume_TradeModel();
        $symbol = " and trade_create_time< '".$time2."' and trade_create_time>'".$time1."' and order_payment_amount>trade_payment_amount and buyer_id=".$user_id;

        $re = $Consume_TradeModel->getTradeId($symbol);

        //如果有到期需要还的订单，就将用户需要还的白条金额返回

        $result = array();
        if($re)
        {
            $result =  $user_res;
        }


        $this->data->addBody(-140, $result);
    }


}

?>
<?php if (!defined('ROOT_PATH')) exit('No Permission');

class Transfer_MoneyModel extends Transfer_Money
{
    const TYPE_HAPPY_MONEY = 1;
    const TYPE_TRANSFER_MONEY = 2;
    //1为已收到，2为过期
    const STATUS_NOT_RECEIVED = 0;
    const STATUS_ACCEPTED = 1;
    const STATUS_EXPIRED = 2;

    public static $types = [
        1 => '红包',
        2 => '转帐'
    ];

    /**
     * @param $transfer_money_data array
     * @return boolean
     * 转账过期处理数据
     * transfer_money改变状态已过期
     * pay_user_resource用户余额从冻结金额还原
     * pay_consume_record改变状态交易取消
     */
    public function refundTransferMoney($transfer_money_data)
    {
        $userResourceModel = new User_ResourceModel;
        $consumeRecordModel = new Consume_RecordModel;

        $this->sql->startTransactionDb(); //开启事务

        $id = $transfer_money_data['id'];
        $user_id = $transfer_money_data['from_to'];
        $amount = $transfer_money_data['money'];
        $order_id = $transfer_money_data['transaction_number'];

        $res_arr = [];
        $res_arr[] = $this->expireTransferMoney($id);
        $res_arr[] = $userResourceModel->expireTransferMoney($user_id, $amount);
        $res_arr[] = $consumeRecordModel->cancelTrade($order_id);

        if (! in_array(false, $res_arr, true)) {
            return $this->sql->rollBackDb();
        }

        return $this->sql->commitDb();
    }

    /**
     * @param $id
     * @return boolean
     * 转账过期
     * transfer_money改变状态已过期
     */
    private function expireTransferMoney($id)
    {
        return $this->editTransferMoney($id, [
            'status'=> self::STATUS_EXPIRED
        ]);
    }
}
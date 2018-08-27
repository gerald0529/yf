<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_BankCardCtl extends Yf_AppController
{
    public $userBankCardModel;
    public $request_parameter;
    public static $require_field = [ //必要字段
        'addUserBankCard'=> [
            'bank_name',
            'bank_card_number',
            'bank_user_name',
            'bank_mobile_number'
        ],
        'applyWithdrawal'=> [
            'password',
            'service_fee_id',
            'amount',
            'bank_card_id'
        ],
        'unbind'=> [
            'bank_card_id'
        ]
    ];

    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);

        $this->request_parameter = $_REQUEST;
        $this->userBankCardModel = new User_BankCardModel();

        $this->verify();
    }

    //验证
    private function verify ()
    {
        if (isset(self::$require_field[$this->request_parameter['met']])) {
            foreach (self::$require_field[$this->request_parameter['met']] as $field) {
                if (! isset($this->request_parameter[$field]) || empty($this->request_parameter[$field])) {
                    $this->showError();
                }
            }
        }
    }

    private function showError ($msg = '无效请求')
    {
        $error_json = json_encode(array('cmd_id' => -140, 'status' => 250, 'msg' => $msg, 'data' => []));
        exit($error_json);
    }

    public function addUserBankCard ()
    {
        $add_data = [
            'user_id'=> Perm::$userId,
            'bank_name'=> request_string('bank_name'),
            'bank_card_number'=> request_string('bank_card_number'),
            'bank_user_name'=> request_string('bank_user_name'),
            'bank_mobile_number'=> request_string('bank_mobile_number'),
        ];

        if (! VerifyCode::checkCode($add_data['bank_mobile_number'], request_string('auth_code'))) {
            $this->showError('手机验证码失败');
        }

        $card_id = $this->userBankCardModel->addUserBankCard($add_data);

        $this->verifyResult($card_id, $status, $msg);

        $this->data->addBody(-140, ['card_id'=> $card_id], $msg, $status);
    }

    public function getUserBankCardList ()
    {
        $user_bank_card_list = $this->userBankCardModel->getByWhere(
            [ 'user_id'=> Perm::$userId ],
            [ 'card_id'=> 'DESC' ]
        );

        $this->verifyResult($user_bank_card_list, $status, $msg);

        if ($user_bank_card_list) {
            foreach($user_bank_card_list as $k=> $user_bank_card) {
                $user_bank_card_list[$k]['tail_number'] = substr($user_bank_card['bank_card_number'], -4);
            }
        }
        $this->data->addBody(-140, $user_bank_card_list, $msg, $status);
    }

    public function unbind ()
    {
        $bank_card_id = $this->request_parameter['bank_card_id'];
        $flag = $this->userBankCardModel->removeUserBankCard($bank_card_id);

        $this->verifyResult($flag, $status, $msg);
        $this->data->addBody(-140, ['bank_card_id'=> $bank_card_id], $msg, $status);
    }

    private function verifyResult (&$result, &$status, &$msg)
    {
        if ($result === false) {
            $result = [];
            $status = 250;
            $msg = "failure";
        } else {
            is_array($result) && $result = array_merge($result);
            $status = 200;
            $msg = "success";
        }
    }

    public function applyWithdrawal()
    {
        $user_id = Perm::$userId;

        $password = request_string('password');  //支付密码

        $userBaseModel = new User_BaseModel;

        if (! $userBaseModel->checkPaymentPassWord($user_id, $this->request_parameter['password'])) {
            return $this->data->setError('支付密码错误');
        }

        $serviceFeeModel = new Service_FeeModel;
        $service_fee = $serviceFeeModel->getServiceFee($this->request_parameter['service_fee_id'], $this->request_parameter['amount']);

        $total_amount = $service_fee + $this->request_parameter['amount'];

        //判断用户余额是否满足
        $userResourceModel = new User_ResourceModel;
        $user_resource = $userResourceModel->getOne($user_id);
        $user_money = $user_resource['user_money'];

        if ($total_amount > $user_money) {
            return $this->data->setError('余额不足');
        }

        //获取用户绑定银行卡信息
        $bank_card = $this->userBankCardModel->getOne($this->request_parameter['bank_card_id']);

        $userResourceModel->sql->startTransactionDb();
        //搬过来的逻辑
        $resource_edit_row['user_money']        = $user_resource['user_money'] - $total_amount;
        $resource_edit_row['user_money_frozen'] = $user_resource['user_money_frozen'] + $total_amount;
        $flag_user_resource = $userResourceModel->editResource($user_id, $resource_edit_row);

        //插入交易明细表
        $flow_id = date("Ymdhis") . rand(0, 9);
        $add_time = date('Y-m-d h:i:s', time());
        $record_row = array(
            'order_id' => $flow_id,
            'user_id' => $user_id,
            'record_money' => -$total_amount,
            'record_date' => date("Y-m-d"),
            'record_year' => date("Y"),
            'record_month' => date("m"),
            'record_day' => date("d"),
            'record_title' => _('提现'),
            'record_time' => date('Y-m-d h:i:s'),
            'trade_type_id' => '4',
            'user_type' => '2',
        );
        $consumeRecordModel = new Consume_RecordModel();
        $record_id = $consumeRecordModel->addRecord($record_row, true);

        //插入提现申请表
        $Withdraw_row = array(
            'pay_uid' => $user_id,
            'orderid' => $flow_id,
            'amount' => $this->request_parameter['amount'],
            'add_time' => time(),
            'bank' => $this->request_parameter['bank_name'],
            'cardno' => $bank_card['bank_card_number'],
            'cardname' => $bank_card['bank_user_name'],
            'supportTime' => $this->request_parameter['service_fee_id'],
            'fee' => $service_fee,
        );
        $consumeWithdrawModel = new Consume_WithdrawModel();
        $withdraw_id = $consumeWithdrawModel->addWithdraw($Withdraw_row);

        if ($flag_user_resource && $record_id && $withdraw_id) {
            $userResourceModel->sql->commitDb();
            $this->data->addBody(-140, [], 'success', 200);
        } else {
            $userResourceModel->sql->rollBackDb();
            $this->data->setError('操作失败');
        }
    }

    public function getUserInfo()
    {
        $userInfoModel = new User_InfoModel();
        $user_info = $userInfoModel->getOne(Perm::$userId);
        $this->verifyResult($user_info, $msg, $status);
        return $this->data->addBody(-140, $user_info, $status, $msg);
    }

    //获取最近提现使用的银行卡
    //由于之前设计没有银行卡功能，该接口不稳定
    public function getUsedLastCardWithWithdrawal()
    {
        $withdrawalModel = new Consume_WithdrawModel;
        $withdrawal_rows = $withdrawalModel->getOneByWhere(
            [ 'pay_uid'=> Perm::$userId ],
            [ 'id'=> 'DESC' ]
        );
        $this->verifyResult($withdrawal_rows, $msg, $status);

        $bank_card = []; //返回信息

        if ($withdrawal_rows) {
            $bank_card = $this->userBankCardModel->getOneByWhere([
                'bank_card_number'=> $withdrawal_rows['cardno']
            ]);
        }
        return $this->data->addBody(-140, $bank_card, $status, $msg);
    }
}
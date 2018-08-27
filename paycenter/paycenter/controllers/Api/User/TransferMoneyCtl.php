<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author yuli
 *
 * app用户转账、红包接口
 *
 */
class Api_User_TransferMoneyCtl extends Yf_AppController
{
    const SINGLE_HAPPY_MONEY_LIMIT = 200;
    const DAY_TRANSFER_MONEY_LIMIT = 10000;

    const BUSINESS_TRANSFER = 1; //红包、转账
    const BUSINESS_DEPOSIT = 2; //充值
    const BUSINESS_WITHDRAWAL = 3; //提现

    private $eCrypt; //加密
    private $from_user_resource; //发送方用户资源信息
    private $transfer_money_data; //转账信息
    private $request_parameters; //请求参数
    private $transferMoneyModel; //用户转账
    private $userResourceModel; //用户资源表
    private $consumeRecordModel; //交易明细表

    public static $error_msg = [
        'CREATE_TRANSFER_MONEY_FAIL' => '插入用户转账失败',
        'UPDATE_TRANSFER_MONEY_FAIL' => '更新用户转账失败',
        'UPDATE_USER_RESOURCE_FAIL' => '更新用户资金失败',
        'CREATE_TRADE_LOG_FAIL' => '创建日志失败',
        'UPDATE_TRADE_LOG_FAIL' => '更新日志失败',
        'EXCEED_SINGLE_LIMIT' => '单次金额超出限制',
        'EXCEED_DAY_LIMIT' => '当日金额超出限制',
        'PARSE_FAIL' => '解析失败',
        'INVALID_REQUEST' => '无效参数',
        'INSUFFICIENT_BALANCE' => '余额不足',
        'PAY_PASSWORD_FAIL'=> '支付密码错误',
        'UNDEFINED_PAY_PASSWORD'=> '请前往PC端设置支付密码',
        'TRANSFER_MONEY_RECEIVED'=> '已领取，请勿重复领取',
        'NOT_FOUND_USER'=> '暂无该用户支付信息',
        'AUTHENTICATION_FAIL'=> '该用户未实名认证'
    ];

    public static $transfer_type = [
        1 => '红包',
        2 => '转帐'
    ];


    public function __construct($ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);

        $this->eCrypt = new ECrypt;
        $decode_res = $this->decode();

        if (! $decode_res['flag']) {
            exit(
                json_encode([
                    'cmd_id' => -140,
                    'data' => [],
                    'msg' => $decode_res['error_msg'],
                    'status' => 250
                ])
            );
        }
        $this->userResourceModel = new User_ResourceModel;
        $this->transferMoneyModel = new Transfer_MoneyModel;
        $this->consumeRecordModel = new Consume_RecordModel;
    }

    private function decode()
    {
        $transfer_money_str = $_REQUEST['transfer_money_str'];
        if (! $transfer_money_str) {
            return ['flag' => false, 'error_msg' => self::$error_msg['INVALID_REQUEST']];
        }

        $transfer_money_arr = $this->eCrypt->decode(urldecode($transfer_money_str));

        if (! $transfer_money_arr) {
            return ['flag' => false, 'error_msg' => self::$error_msg['PARSE_FAIL']];
        }

        $this->request_parameters = $transfer_money_arr;

        return ['flag' => true];
    }

    /**
     * @return string
     * @throws Exception
     * 发起转账
     */
    public function transferMoney()
    {
        $check_res = $this->isValidPayerRequest(); //验证请求有效性
        if (! $check_res['flag']) {
            return $this->data->setError($check_res['error_msg']);
        }
        $check_auth = $this->checkUserAuthenticationStatus();
        if (! $check_auth['flag']) { //用户未实名认证
            return $this->data->setError($check_auth['error_msg']);
        }
        $check_pay_password_res = $this->checkPayPassword(); //验证用户支付密码
        if (! $check_pay_password_res['flag']) {
            return $this->data->setError($check_pay_password_res['error_msg']);
        }

        $this->transferMoneyModel->sql->startTransactionDb(); //开启事务

        $transfer_money_id = $this->addTransferMoney();

        if (! $transfer_money_id) { //转账表插入失败
            return $this->data->setError(self::$error_msg['CREATE_TRANSFER_MONEY_FAIL']);
        }

        $user_resource_flag = $this->updateUserResourceByPayer(); //改变用户资源表

        if ($user_resource_flag === false) {
            $this->transferMoneyModel->sql->rollBackDb();
            return $this->data->setError(self::$error_msg['UPDATE_USER_RESOURCE_FAIL']);
        }

        $consume_record_id = $this->addConsumeRecordByPayer(); //记录日志

        if (! $consume_record_id) {
            $this->transferMoneyModel->sql->rollBackDb();
            return $this->data->setError(self::$error_msg['CREATE_TRADE_LOG_FAIL']);
        }

        if ($this->transferMoneyModel->sql->commitDb()) {
            /****************************************************************************************************/
            $user_id_row = $this->request_parameters['user_id'];
            try{
                //转账成功后对买家进行极光推送
                require_once "Jpush/JPush.php";
                $type=array('type'=>'1');
                $app_key = 'cf3f465424d79b172f0fc732';
                $master_secret = 'bd7c4a065d3c4c320ffe3ed1';
                $alert="您在".date("Y-m-d H:i:s")."转账成功";
                $client = new JPush($app_key, $master_secret);
                $result=$client->push()
                    ->setPlatform(array('ios', 'android'))
                    ->addAlias($user_id_row)
                    ->addIosNotification($alert,'', null, null, null, $type)
                    ->addAndroidNotification($alert,null,null,$type)
                    ->setOptions(100000, 3600, null, false)
                    ->send();
            }
            catch(Exception $e){

            }
            /****************************************************************************************************/
            return $this->data->addBody(-140, $this->transfer_money_data, 'success', 200);
        }
    }

    /**
     * 接受转账
     */
    public function acceptTransfer()
    {
        $check_res = $this->isValidTransferMoney(); //验证请求有效性

        if (! $check_res['flag']) { //无效请求
            return $this->data->setError($check_res['error_msg']);
        }

        $check_auth = $this->checkUserAuthenticationStatus();
        if (! $check_auth['flag']) { //用户未实名认证
            return $this->data->setError($check_auth['error_msg']);
        }

        $check_received_res = $this->isNotReceived(); //判断转账是否已领取

        if (! $check_received_res['flag']) {
            return $this->data->setError($check_received_res['error_msg']);
        }

        $this->transferMoneyModel->sql->startTransactionDb(); //开启事物

        $transfer_money_flag = $this->updateTransferMoney();

        if (! $transfer_money_flag) { //转账表插入失败
            return $this->data->setError(self::$error_msg['UPDATE_TRANSFER_MONEY_FAIL']);
        }

        $user_resource_flag = $this->updateUserResourceByBeneficiary(); //更新用户资金表

        if (! $user_resource_flag) {
            $this->transferMoneyModel->sql->rollBackDb();
            return $this->data->setError(self::$error_msg['UPDATE_USER_RESOURCE_FAIL']);
        }

        $consume_record_flag = $this->addConsumeRecordByBeneficiary();

        if (! $consume_record_flag) {
            $this->transferMoneyModel->sql->rollBackDb();
            return $this->data->setError(self::$error_msg['UPDATE_TRADE_LOG_FAIL']);
        }

        if ($this->transferMoneyModel->sql->commitDb()) {
            return $this->data->addBody(-140, $this->transfer_money_data, 'success', 200);
        }
    }

    /**
     * @return string
     * @throws Exception
     * 获取红包状态
     */
    public function getTransferMoneyStatus()
    {
        $check_res = $this->isValidTransferMoney(); //验证请求有效性

        if (! $check_res['flag']) { //无效请求
            return $this->data->setError($check_res['error_msg']);
        }
        /****************************************************************************************************/
        $user_id_row = $this->request_parameters['user_id'];
        try{
            //转账成功后对买家进行极光推送
            require_once "Jpush/JPush.php";
            $type=array('type'=>'1');
            $app_key = 'cf3f465424d79b172f0fc732';
            $master_secret = 'bd7c4a065d3c4c320ffe3ed1';
            $alert="您在".date("Y-m-d H:i:s")."转账成功";
            $client = new JPush($app_key, $master_secret);
            $result=$client->push()
                ->setPlatform(array('ios', 'android'))
                ->addAlias($user_id_row)
                ->addIosNotification($alert,'', null, null, null, $type)
                ->addAndroidNotification($alert,null,null,$type)
                ->setOptions(100000, 3600, null, false)
                ->send();
        }
        catch(Exception $e){

        }
        /****************************************************************************************************/
        return $this->data->addBody(-140, $this->transfer_money_data, 'success', 200);
    }

    /**
     * 获取用户余额
     */
    public function getUserBalance()
    {
        $check_res = $this->isValidUserId();

        if (! $check_res['flag']) { //无效请求
            return $this->data->setError($check_res['error_msg']);
        }

        $user_data = $this->userResourceModel->getOne($this->request_parameters['user_id']);

        if (! $user_data) {
            return $this->data->addBody(-140, [], self::$error_msg['NOT_FOUND_USER'], 250);
        }

        $result = [
            'user_id'=> $user_data['user_id'],
            'user_money'=> $user_data['user_money']
        ];

        return $this->data->addBody(-140, $result, 'success', 200);
    }

    /**
     * @return array
     * 判断用户是否已经实名认证
     */
    private function checkUserAuthenticationStatus()
    {
        $userInfoModel = new User_InfoModel;

        $user_id = $_REQUEST['met'] === 'transferMoney'
            ? $this->request_parameters['from_user']
            : $this->transfer_money_data['to_user'];

        $user_info_data = $userInfoModel->getOne($user_id);

        if ($user_info_data === false) {
            return ['flag'=> false, 'error_msg'=> self::$error_msg['NOT_FOUND_USER']];
        }

        if ($user_info_data['user_identity_statu'] != User_InfoModel::BT_VERIFY_PASS) {
            return ['flag'=> false, 'error_msg'=> self::$error_msg['AUTHENTICATION_FAIL']];
        }

        return ['flag'=> true];
    }

    /**
     * 获取用户装转账列表
     */
    private function getTransferMoneyList()
    {
        $user_id = $this->request_parameters['user_id'];

        $transfer_money_from_list = $this->transferMoneyModel->getByWhere([
            'from_user'=> $user_id,
        ]);
        //对于接受方只需要展示已接受的状态
        $transfer_money_to_list = $this->transferMoneyModel->getByWhere([
            'status:IN'=> [Transfer_MoneyModel::STATUS_ACCEPTED],
            'to_user'=> $user_id,
        ]);

        if (!$transfer_money_from_list && !$transfer_money_to_list) {
            return []; //没有红包、转账
        }

        $transfer_money_list = array_merge($transfer_money_from_list, $transfer_money_to_list);
        $one_day_seconds = 24 * 60 * 60;
        foreach ($transfer_money_list as $k=> $transfer_money) {
            switch($transfer_money['status']) {
                case Transfer_MoneyModel::STATUS_NOT_RECEIVED:
                    $transfer_money_list[$k]['sort_time'] = $transfer_money['send_time'];
                    break;
                case Transfer_MoneyModel::STATUS_ACCEPTED:
                    $transfer_money_list[$k]['sort_time'] = $transfer_money['receive_time'];
                    break;
                case Transfer_MoneyModel::STATUS_EXPIRED:
                    $transfer_money_list[$k]['sort_time'] = $transfer_money['send_time'] + $one_day_seconds;
                    break;
            }
            $transfer_money_list[$k]['business_id'] = self::BUSINESS_TRANSFER;
        }

        return $transfer_money_list;
    }

    /**
     * 获取用户充值记录
     */
    private function getUserDeposits()
    {
        $consumeDepositModel = new Consume_DepositModel;
        $consume_deposit_list = $consumeDepositModel->getByWhere([
            'deposit_buyer_id'=> $this->request_parameters['user_id'],
            'deposit_trade_status'=> Consume_DepositModel::TRADE_STATUS_SUCCESS //交易完成
        ]);

        if (! $consume_deposit_list) {
            return [];
        }

        $result_arr = []; //返回数据
        foreach($consume_deposit_list as $consume_deposit) {
            $result_arr[] = [
                'sort_time'=> strtotime($consume_deposit['deposit_gmt_payment']),
                'user_id'=> $consume_deposit['deposit_buyer_id'],
                'trade_no'=> $consume_deposit['deposit_trade_no'],
                'amount'=> $consume_deposit['deposit_total_fee'],
                'pay_channel_id'=> $consume_deposit['deposit_pay_channel'],
                'business_id'=> self::BUSINESS_DEPOSIT
            ];
        }

        return $result_arr;
    }

    /**
     * 获取用户提现记录
     */
    private function getUserWithdrawals()
    {
        $consumeWithdrawModel = new Consume_WithdrawModel;
        $consume_withdraw_list = $consumeWithdrawModel->getByWhere([
            'pay_uid'=> $this->request_parameters['user_id'],
            'is_succeed'=> Consume_WithdrawModel::WITHDRAW_STATUS //显示通过的状态
        ]);

        if (! $consume_withdraw_list) {
            return [];
        }

        $result_arr = []; //返回数据
        foreach($consume_withdraw_list as $consume_withdraw) {
            $result_arr[] = [
                'sort_time'=> $consume_withdraw['check_time'],
                'user_id'=> $consume_withdraw['pay_uid'],
                'trade_no'=> $consume_withdraw['orderid'],
                'amount'=> $consume_withdraw['amount'],
                'business_id'=> self::BUSINESS_WITHDRAWAL
            ];
        }
        return $result_arr;
    }


    /**
     * 获取用户账户明细
     * 现阶段账户明细包含
     * 1.红包
     * 2.转账
     * 3.充值
     * 4.提现
     */
    public function getUserAccountDetails()
    {
        $transfer_list = $this->getTransferMoneyList();
        $deposit_list = $this->getUserDeposits();
        $withdraw_list = $this->getUserWithdrawals();

        $user_account_details = $this->sortUserAccountDetails(array_merge($transfer_list, $deposit_list, $withdraw_list));

        $this->data->addBody(-140, $user_account_details, 'success', 200);
    }

    /**
     * 排序
     * @param $account_details
     * @return array
     */
    private function sortUserAccountDetails($account_details)
    {
        usort($account_details, function($a, $b){

            if($a['sort_time'] == $b['sort_time']) {
                return 0;
            }
            return $a['sort_time'] > $b['sort_time']
                ? -1
                : 1;
        });

        array_walk($account_details, function(&$a){
            $a['sort_time'] = date('Y-m-d H:i:s', $a['sort_time']);
        });

        return array_values($account_details);
    }


    /**
     * 转账表
     */
    private function addTransferMoney()
    {
        $insert_data = [
            'from_user' => $this->request_parameters['from_user'], //发起转帐或红包的人
            'to_user' => $this->request_parameters['to_user'], //接收人
            'money' => $this->request_parameters['money'], //转了多少钱
            'type' => $this->request_parameters['type'], //1红包 2 转帐
            'send_time' => $_SERVER['REQUEST_TIME'], //发送时间
            'transaction_number'=> self::createTransactionNumber() //交易号
        ];

        if (! empty($this->request_parameters['txt'])) {
            $insert_data['txt'] = $this->request_parameters['txt'];
        }

        $transfer_money_id = $this->transferMoneyModel->addTransferMoney($insert_data, true);

        if (! $transfer_money_id) {
            return false;
        }

        $this->transfer_money_data = array_merge($insert_data, ['id' => $transfer_money_id]);

        return true;
    }

    /**
     * 用户资源表
     * 扣除用户资金user_money，把转账金额暂时移交到冻结资金里user_money_frozen
     */
    private function updateUserResourceByPayer()
    {
        $transfer_money = $this->request_parameters['money'];
        $from_user_id = $this->request_parameters['from_user'];

        $update_data = [
            'user_money' => -$transfer_money,
            'user_money_frozen' => $transfer_money
        ];

        return $this->userResourceModel->editResource($from_user_id, $update_data, true);
    }

    /**
     * @return int id
     * 交易明细表
     */
    private function addConsumeRecordByPayer()
    {
        return $this->addConsumeRecord(Consume_RecordModel::PAYER);
    }

    /**
     * @return bool
     * 判断付款请求是否有效
     */
    private function isValidPayerRequest()
    {
        $check_arr = [];

        $check_arr[] = isset($this->request_parameters['from_user']) && is_numeric($this->request_parameters['from_user'])
            ? true
            : false;

        $check_arr[] = isset($this->request_parameters['to_user']) && is_numeric($this->request_parameters['to_user'])
            ? true
            : false;

        $check_arr[] = isset($this->request_parameters['money']) && is_numeric($this->request_parameters['money']) && $this->request_parameters['money'] > 0
            ? true
            : false;

        $check_arr[] = isset($this->request_parameters['type']) && is_numeric($this->request_parameters['type'])
            ? true
            : false;

        if (in_array(false, $check_arr, true)) {
            return ['flag' => false, 'error_msg' => self::$error_msg['INVALID_REQUEST']];
        }

        $check_limit_res = $this->checkLimit();

        if (! $check_limit_res['flag']) {
            return $check_limit_res;
        }

        return $this->checkBalance();
    }

    /**
     * @return array
     * 判断用户余额是否充足
     */
    private function checkBalance()
    {
        $from_user_resource = $this->userResourceModel->getOne($this->request_parameters['from_user']);

        if ($from_user_resource && $this->request_parameters['money'] > $from_user_resource['user_money']) {
            return ['flag' => false, 'error_msg' => self::$error_msg['INSUFFICIENT_BALANCE']];;
        }

        $this->from_user_resource = $from_user_resource;
        return ['flag' => true];
    }

    /**
     * @return array
     * 判断转账上限
     *
     */
    private function checkLimit()
    {
        //红包只判断单次不能超过200
        if ($this->request_parameters['type'] == Transfer_MoneyModel::TYPE_HAPPY_MONEY) {
            return $this->request_parameters['money'] > self::SINGLE_HAPPY_MONEY_LIMIT
                ? ['flag' => false, 'error_msg' => self::$error_msg['EXCEED_SINGLE_LIMIT']]
                : ['flag' => true];
        }

        //转账只判断当日不能超过10000
        if ($this->request_parameters['money'] > self::DAY_TRANSFER_MONEY_LIMIT) {
            return ['flag' => false, 'error_msg' => self::$error_msg['EXCEED_SINGLE_LIMIT']];
        }

        $start_day_time = strtotime(date('Y-m-d 00:00:00'));
        $end_day_time = $start_day_time + 24 * 60 * 60 - 1;

        //获取当日转账记录
        $transfer_money_rows = $this->transferMoneyModel->getByWhere([
            'from_user' => $this->request_parameters['from_user'],
            'send_time:>=' => $start_day_time,
            'receive_time:<=' => $end_day_time,
        ]);

        if (empty($transfer_money_rows)) {
            return ['flag' => true];
        }

        return array_sum(array_column($transfer_money_rows, 'money')) + $this->request_parameters['money'] > self::DAY_TRANSFER_MONEY_LIMIT
            ? ['flag' => false, 'error_msg' => self::$error_msg['EXCEED_DAY_LIMIT']]
            : ['flag' => true];
    }

    /**
     * 判断接收方请求是否有效
     * 需要transfer_money_id
     */
    private function isValidTransferMoney()
    {
        return isset($this->request_parameters['transfer_money_id']) && is_numeric($this->request_parameters['transfer_money_id'])
            ? $this->isValidTransferMoneyId()
            : ['flag' => false, 'error_msg' => self::$error_msg['INVALID_REQUEST']];
    }

    /**
     * 判断是否领取
     */
    private function isNotReceived()
    {
        return $this->transfer_money_data['status'] == Transfer_MoneyModel::STATUS_NOT_RECEIVED
            ? ['flag'=> true]
            : ['flag'=> false, 'error_msg'=> self::$error_msg['TRANSFER_MONEY_RECEIVED']];
    }

    /**
     * @return array
     * 验证transfer_money_id是否有效
     */
    private function isValidTransferMoneyId()
    {
        $transfer_money_data = $this->transferMoneyModel->getOne($this->request_parameters['transfer_money_id']);

        if (! $transfer_money_data) {
            return ['flag' => false, 'error_msg' => self::$error_msg['INVALID_REQUEST']];
        }

        $this->transfer_money_data = $transfer_money_data;

        return ['flag' => true];
    }

    private function isValidUserId()
    {
        return isset($this->request_parameters['user_id']) && is_numeric($this->request_parameters['user_id'])
            ? ['flag'=> true]
            : ['flag'=> false, 'error_msg'=> self::$error_msg['INVALID_REQUEST']];
    }

    /**
     * @return bool
     * 接受转账后更新转账表
     */
    private function updateTransferMoney()
    {
        $transfer_money_id = $this->request_parameters['transfer_money_id'];

        $update_data = [
            'receive_time' => $_SERVER['REQUEST_TIME'], //收到时间
            'status' => Transfer_MoneyModel::STATUS_ACCEPTED //1为已收到，2为过期
        ];

        $this->transfer_money_data = array_merge($this->transfer_money_data, $update_data);
        return $this->transferMoneyModel->editTransferMoney($transfer_money_id, $update_data);
    }

    /**
     * 更新用户资源表
     * 做两件事情：
     * 1.更新发送方冻结金额
     * 2.更新收入方可用金额
     */
    private function updateUserResourceByBeneficiary()
    {
        //首先更新发送方信息

        //获取发送方资源信息
        $payer_user_id = $this->transfer_money_data['from_user'];
        $transfer_money = $this->transfer_money_data['money'];

        $payer_update_data = [
            'user_money_frozen' => -$transfer_money //发送方冻结资金减去转账金额
        ];

        $payer_update_flag = $this->userResourceModel->editResource($payer_user_id, $payer_update_data, true);

        if ($payer_update_flag === false) {
            return false; //修改失败直接返回
        }

        //其次更新接收方信息
        $beneficiary_user_id = $this->transfer_money_data['to_user']; //接受方user_id

        $beneficiary_update_data = [
            'user_money' => $transfer_money
        ];

        return $this->userResourceModel->editResource($beneficiary_user_id, $beneficiary_update_data, true);
    }

    /**
     * 插入日志表
     *
     * 做两件事情：
     * 1.更新发送方日志
     * 2.插入接受方日志
     */
    private function addConsumeRecordByBeneficiary()
    {
        //更新发送方日志
        //交易明细表order_id存放的是转账id
        $payer_user_id = $this->transfer_money_data['from_user'];
        $order_id = $this->transfer_money_data['transaction_number'];

        //获取发送方用户日志
        $payer_consume_record_rows = $this->consumeRecordModel->getByWhere([
            'order_id' => $order_id,
            'user_id' => $payer_user_id,
        ]);

        $payer_consume_record_data = current($payer_consume_record_rows);
        $payer_consume_record_id = $payer_consume_record_data['consume_record_id'];

        $payer_update_data = [
            'record_status' => RecordStatusModel::RECORD_FINISH
        ];

        $payer_flag = $this->consumeRecordModel->editRecord($payer_consume_record_id, $payer_update_data);

        if (! $payer_flag) {
            return false;
        }

        return $this->addConsumeRecord(Consume_RecordModel::BENEFICIARY);
    }

    /**
     * @param $user_type 1-收款方 2-付款方
     * @return bool
     */
    private function addConsumeRecord($user_type)
    {
        if ($user_type == Consume_RecordModel::PAYER) {
            $user_id = $this->transfer_money_data['from_user'];
            $record_status = RecordStatusModel::IN_HAND;
        } else {
            $user_id = $this->transfer_money_data['to_user'];
            $record_status = RecordStatusModel::RECORD_FINISH;
        }

        //插入接受方日志
        $user_info_model = new User_InfoModel;
        $user_info_data = $user_info_model->getOne($user_id);

        $record_date = date('Y-m-d');
        $record_date_arr = explode('-', $record_date);

        $insert_data = [
            'user_id' => $user_id, //所属用id
            'user_nickname' => $user_info_data['user_nickname'],
            'record_money' => $this->transfer_money_data['money'], //金额
            'record_date' => $record_date, //年-月-日
            'record_year' => $record_date_arr[0], //年
            'record_month' => $record_date_arr[1], //月
            'record_day' => $record_date_arr[2], //日
            'record_title' => self::$transfer_type[$this->transfer_money_data['type']], //红包 or 转账
            'trade_type_id' => Trade_TypeModel::TRANSFER, //交易类型
            'user_type' => $user_type, //1-收款方 2-付款方
            'record_time' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']), //创建时间
            'record_paytime' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']), //付款状态
            'record_status' => $record_status, //付款状态
            'order_id' => $this->transfer_money_data['transaction_number'] //注意：这里存放pay_transfer_money的transaction_number 交易单号
        ];

        return $this->consumeRecordModel->addRecord($insert_data, true);
    }

    /**
     * @return array
     * 验证用户支付密码
     */
    private function checkPayPassword()
    {
        if (! isset($this->request_parameters['pay_password'])) {
            return ['flag'=> false, 'error_msg'=> self::$error_msg['PAY_PASSWORD_FAIL']];
        }

        $user_id = $this->request_parameters['from_user'];

        $user_base_model = new User_BaseModel;
        $user_data = $user_base_model->getOne($user_id);

        if (! $user_data) {
            return ['flag'=> false, 'error_msg'=> self::$error_msg['UNDEFINED_PAY_PASSWORD']];
        }

        if (empty($user_data['user_pay_passwd'])) {
            return ['flag'=> false, 'error_msg'=> self::$error_msg['UNDEFINED_PAY_PASSWORD']];
        }

        return md5($this->request_parameters['pay_password']) == $user_data['user_pay_passwd']
            ? ['flag'=> true]
            : ['flag'=> false, 'error_msg'=> self::$error_msg['PAY_PASSWORD_FAIL']];
    }

    private static function createTransactionNumber()
    {
        $micro_time_arr = explode(' ', microtime());
        $random = mt_rand(10000000, 99999999); //8位随机数
        return 'TM' . $micro_time_arr[1] . str_replace($micro_time_arr[0], '0.', '') . $random; //20位
    }
}
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
class Api_Paycen_PayWithdrawCtl extends Api_Controller
{
    /**
     *支付会员
     *
     * @access public
     */
    
    function getPayWithdrawList() {
       
        $Consume_WithdrawModel = new Consume_WithdrawModel();


        $cond_row = array();
        $user_account = request_string('userName');


        if($user_account){
            $UserModel = new User_BaseModel();
            $cond['user_account:LIKE'] = '%' . $user_account . '%';

            $user_base = $UserModel->getByWhere($cond);
            $user_ids = array_values(array_column($user_base, 'user_id'));
            $cond_row['pay_uid:IN'] = $user_ids;



        }

        $data           = $Consume_WithdrawModel->getWithdrawList($cond_row);
        if(isset($data['items']) && $data['items']) {
            //获取用户信息
            $uid = array();
            foreach ($data['items'] as $value){
                $uid[] = $value['pay_uid'];
            }
            $uid = array_unique($uid);
            if(count($uid) > 1){
                $where = array('user_id:IN'=>$uid);
            }else{
                $uid_str = array_shift($uid);
                $where = array('user_id'=>$uid_str);
            }
            $UserModel = new User_BaseModel();
            $user_info = $UserModel->getPayBaseList($where);
            $user_account_array = array();
            foreach ($user_info['items'] as $val){
                $user_account_array[$val['user_id']] = $val['user_account'];
            }
            foreach ($data['items'] as $key=>$v){
                $data['items'][$key]['user_account'] = $user_account_array[$v['pay_uid']];
                $data['items'][$key]['add_time'] = date('Y-m-d H:i:s', $v['add_time']);
            }
        }

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
    
    function getEditWithdraw() {
        $id = request_int("id");
        $Consume_WithdrawModel = new Consume_WithdrawModel();
        $data = $Consume_WithdrawModel->getOne($id);
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
    
    function editWithdrawRow() {
            $id = request_int("id");
            $data['is_succeed'] = request_int("is_succeed");
            $data['bankflow'] = request_string("bankflow");
            $data['remark'] = request_string("remark");
            $data['check_time'] = time();
            if('hp' == request_string('typ'))
            {
                echo '<pre>';print_r($data);exit;
            }
            $Consume_WithdrawModel = new Consume_WithdrawModel();
            $Withdrawlist = $Consume_WithdrawModel->getOne($id);
            $flag = $Consume_WithdrawModel->editWithdraw($id,$data);
            if ($flag!==false)
            {
                 if( $data['is_succeed'] == 3)
                 {

                     //实例化流水表
                     $Consume_RecordModel = new Consume_RecordModel();
                     //用充值的订单id查询出流水表信息
                     $cond_row['order_id'] = $Withdrawlist['orderid'];
                     $record_list = $Consume_RecordModel->getOneByWhere($cond_row);

                     //更改流水表的信息
                     $flag1 = $Consume_RecordModel->editRecord($record_list['consume_record_id'],array("record_status"=>2));

                 if($flag1!==false)
                 {
                     //修改用户的冻结金额
                     $User_ResourceModel = new User_ResourceModel();
                     $user_resource      = current($User_ResourceModel->getResource($record_list['user_id']));
                     $resource_edit_row['user_money_frozen'] = $user_resource['user_money_frozen'] + $record_list['record_money']*1;
                     $flag2 = $User_ResourceModel->editResource($record_list['user_id'], $resource_edit_row);

                     if($flag2!==false)
                     {
                         $msg    = 'success';
                         $status = 200;
                     }
                     else
                     {
                         $msg    = 'failure';
                         $status = 250;
                     }

                 }
                 else
                 {
                     $msg    = 'failure';
                     $status = 250; 
                 }
                }
                 elseif($data['is_succeed'] == 4)
                 {
                    //实例化流水表
                     $Consume_RecordModel = new Consume_RecordModel();
                     //用充值的订单id查询出流水表信息
                     $cond_row['order_id'] = $Withdrawlist['orderid'];
                     $record_list = $Consume_RecordModel->getOneByWhere($cond_row);

                     //更改流水表的信息
                     $flag1 = $Consume_RecordModel->editRecord($record_list['consume_record_id'],array("record_status"=>RecordStatusModel::RECORD_CANCEL));
                     if($flag1!==false)
                     {
                         //修改用户的冻结金额
                         $User_ResourceModel = new User_ResourceModel();
                         $user_resource      = current($User_ResourceModel->getResource($record_list['user_id']));
                         $resource_edit_row['user_money']        = $user_resource['user_money'] - $record_list['record_money']*1;
                         $resource_edit_row['user_money_frozen'] = $user_resource['user_money_frozen'] + $record_list['record_money']*1;
                         $flag2 = $User_ResourceModel->editResource($record_list['user_id'], $resource_edit_row);

                         if($flag2!==false)
                         {
                             $msg    = 'success';
                             $status = 200;
                         }
                         else
                         {
                             $msg    = 'failure';
                             $status = 250;
                         }

                     }
                 }
                 
            }
            else
            {
                $msg    = 'failure';
                $status = 250;
            }
            $data['id'] =$id ;
            
            $this->data->addBody(-140, $data, $msg, $status);
    }
 
}
?>
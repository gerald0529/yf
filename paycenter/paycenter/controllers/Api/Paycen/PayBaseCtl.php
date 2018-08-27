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
class Api_Paycen_PayBaseCtl extends Api_Controller
{
    /**
     *支付会员
     *
     * @access public
     */
    
    function getPayBaseList() {
          $username  = request_string('userName');   //用户名称
          $cond_row = array();
          if($username){
                $cond_row = array( "user_account" => $username);
          }
          $User_BaseModel = new User_BaseModel();
        $page = request_int('page',1);
        $rows = request_int('rows',20);
          $data           = $User_BaseModel->getPayBaseList($cond_row,array(),$page,$rows);
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
    
    function getEditBase() {
        $user_id = request_int("user_id");
        $User_BaseModel = new User_BaseModel();
        $data = $User_BaseModel->getOne($user_id);
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
    
    function editBaseRow() {
        $data['user_id'] = request_int("user_id");
        $data['user_nickname'] = request_string("user_account");
        $data['record_money'] = request_int("add_user_money");
        $data['record_desc'] = request_string("record_desc");
        $User_ResourceModel = new User_ResourceModel();
        $flag = $User_ResourceModel->editResource($data['user_id'],array("user_money"=>$data['record_money']),true);
        if ($flag)
        {
             $data['order_id'] =  "";
             $data['record_date']=  date("Y-m-d H:i:s");
             $data['record_time']=  date("Y-m-d H:i:s");
             $data['trade_type_id']=  3;
             $data['user_type']=  3;
             $data['record_status']=  2;
              if($data['record_money'] > 0){
                $data['record_title'] = _("管理员增加金额");
              }else{
                $data['record_title'] = _("管理员减少金额");
              }
             $Consume_RecordModel = new Consume_RecordModel(); 
             $flag1 = $Consume_RecordModel->addRecord($data);
             if ($flag1)
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
        
        $this->data->addBody(-140, $data, $msg, $status);
    
    }
}
?>
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
class Api_Paycen_PayServiceCtl extends Api_Controller
{
    /**
     *支付会员
     *
     * @access public
     */
    
    function getServiceList() {
//          $username  = request_string('userName');   //用户名称
//          $cond_row = array();
//          if($username){
//                $cond_row = array( "user_account" => $username);
//          }
          $Service_FeeModel = new Service_FeeModel();
          $data           = $Service_FeeModel->getFeeList($cond_row);
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
    
    function addServiceRow(){
        $data['name'] = request_string("name");
        $data['fee_rates'] = request_float("fee_rates");
        $data['fee_min'] = request_int("fee_min");
        $data['fee_max'] = request_int("fee_max");
          $Service_FeeModel = new Service_FeeModel();
          $flag           = $Service_FeeModel->addFee($data);
            if ($flag)
            {
                $msg    = 'success';
                $status = 200;
            }
            else
            {
                $msg    = 'failure';
                $status = 250;
            }
           $data['id'] =$flag; 
          $this->data->addBody(-140, $data, $msg, $status);
    }
    
    
    function geteditRow(){
        $id = request_int("id");
        $Service_FeeModel = new Service_FeeModel();
        $data           = $Service_FeeModel->getOne($id);
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
    
    
     function editServiceRow(){
       
        $id = request_int("id");
        $data['name'] = request_string("name");
        $data['fee_rates'] = request_string("fee_rates");
        $data['fee_min'] = request_string("fee_min");
        $data['fee_max'] = request_string("fee_max");
        $Service_FeeModel = new Service_FeeModel();
        $flag           = $Service_FeeModel->editid($id,$data);
        if ($flag !== FALSE)
            {
                $msg    = 'success';
                $status = 200;
            }
            else
            {
                $msg    = 'failure';
                $status = 250;
            }
           $data['id'] =$id; 
          $this->data->addBody(-140, $data, $msg, $status);
    }
    
    function removeServiceRow() {
          $id = request_int("id");
          $Service_FeeModel = new Service_FeeModel();
          $flag           = $Service_FeeModel->remove($id);
        if ($flag)
            {
                $msg    = 'success';
                $status = 200;
            }
            else
            {
                $msg    = 'failure';
                $status = 250;
            }
           $data['id'] =$id; 
          $this->data->addBody(-140, $data, $msg, $status);
    }
}

?>
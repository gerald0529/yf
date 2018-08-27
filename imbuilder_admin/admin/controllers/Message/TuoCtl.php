<?php if (!defined('ROOT_PATH')) exit('No Permission');
 
class Message_TuoCtl extends Yf_AppController
{
      
    public function init()
    {
         
        $this->userMsgModel = new User_MsgModel();
    }
     

    public function message()
    {
        include $this->view->getView();
    }


    public function message_view()
    {
        include $this->view->getView();
    }


    


    public function store()
    {
        include $this->view->getView();
    }

    public function store_add()
    {
        include $this->view->getView();
    }
  

    public function sendMessage()
    {
        $receiver_name = $_REQUEST['vendor_type_name']; //收信人
        $name = explode(',',$receiver_name);
        $num = count($name);
        if($num<=1)
        {
            $r_name = $name[0];
        }
        else
        {
            $r_name = json_encode($name, true);
        }
        $contant = $_REQUEST['vendor_type_desc'];      //信息内容
        $url            = Yf_Registry::get('im_api_url');
        $key            = Yf_Registry::get('im_api_key');
        $data['app_id'] = Yf_Registry::get('im_app_id');
        $data['ctl'] = 'ImApi';
        $data['met'] = 'pushMsg';
        $data['typ'] = 'json';
        $data['receiver'] = $r_name;
        $data['push_type'] = 1;
        $data['msg_content'] = $contant;
        $result = get_url_with_encrypt($key,$url,$data);
        Yf_Log::log("result=".print_r($result), Yf_Log::INFO, 'im_push');
        if($result)
        {
 
            $msg = $result['msg'] ? $result['msg'] : 'success';
            $status = $result['status'] ? $result['status'] : 250;
        }
        else
        {
            $msg = '发送失败';
            $status =250;
        }
        $data = array();
        $this->data->addBody(-140,$data,$msg,$status);
    }

    
}
 
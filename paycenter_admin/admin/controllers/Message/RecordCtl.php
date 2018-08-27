<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Message_RecordCtl extends Yf_AppController
{
    public $userMsgModel = null;

    /**
     * 初始化方法，构造函数
     *
     * @access public
     */
    public function init()
    {
        //include $this->view->getView();
        $this->userMsgModel = new User_MsgModel();
    }

    public function index()
    {
        include $this->view->getView();
    }

    public function manage()
    {
        include $this->view->getView();
    }
    public function send()
    {
        include $this->view->getView();
    }

    public function getList()
    {
        $skey   = $_REQUEST['skey'];
        $skey1  = $_REQUEST['skey1'];
        /*$beginDate = $_REQUEST['beginDate'];
        $endDate = $_REQUEST['endDate'];*/
        if($skey||$skey1)
        {
            $userMsgModel = new User_MsgModel();
            /*if($beginDate)
            {
                $userMsgModel->sql->setWhere('date_created',$beginDate,'>=');
            }
            if($endDate)
            {
                $userMsgModel->sql->setWhere('date_created',$endDate,'<=');
            }*/
            if($skey)
            {
                $userMsgModel->sql->setWhere('msg_sender',$skey);
            }
            if($skey1)
            {
                $userMsgModel->sql->setWhere('msg_receiver',$skey1);
            }
            $userMsgModel->sql->setOrder('date_created','ASC');
            $data1 = $userMsgModel->get('*');
            foreach($data1 as $k=>$v)
            {
                $data2[] = $v;
            }
        }
        else
        {
            $userMsgModel = new User_MsgModel();
            /*if($beginDate)
            {
                $userMsgModel->sql->setWhere('date_created',$beginDate,'>=');
            }
            if($endDate)
            {
                $userMsgModel->sql->setWhere('date_created',$endDate,'<=');
            }*/
            $data = $userMsgModel->getSender('*');
            $data2 = array();
            foreach($data as $key=>$value)
            {
                $sender_name = $value['msg_sender'];
                $userMsgModel->sql->setWhere('msg_sender',$sender_name);
                $userMsgModel->sql->setOrder('date_created','ASC');
                $data1 = $userMsgModel->get('*');
                foreach($data1 as $k=>$v)
                {
                    $data2[] = $v;
                }
            }
        }

        $data3['items'] = $data2;
        $data3['records'] = count($data2);
        $total = count($data2);
        $data3['page'] = 1;
        $data3['total'] = ceil_r($total / 100);
        if($data3)
        {
            $msg = 'success';
            $status = 200;
        }
        else
        {
            $msg = 'failure';
            $status = 250;
        }
        $this->data->addBody(-140,$data3,$msg,$status);
    }

    //获取好友列表信息
    public function getFriends()
    {
        $skey = $_REQUEST['skey'];
        $userInfoDetailModel = new User_InfoDetailModel();
        if($skey)
        {
            $userInfoDetailModel->sql->setWhere('user_name','%'.$skey.'%','LIKE');
        }
        $data = $userInfoDetailModel->getUserList();
        $items = $data['items'];
        if($items)
        {
            foreach($items as $key=>$value)
            {
                if($value['user_gender']==0)
                {
                    $items[$key]['user_gender']='女';
                }
                else
                {
                    $items[$key]['user_gender']='男';
                }
                $user_reg_time = $value['user_reg_time'];
                $items[$key]['user_reg_time'] = date('Y-m-d h:i:s',$user_reg_time);
                $user_lastlogin_time = $value['user_lastlogin_time'];
                $items[$key]['user_lastlogin_time'] = date('Y-m-d h:i:s',$user_lastlogin_time);
            }
        }
        unset($data['items']);
        $data['items'] = $items;
        if($data){
            $msg = 'success';
            $status = 200;
        }
        else{
            $msg = 'failure';
            $status = 250;
        }
        $this->data->addBody(-140,$data,$msg,$status);
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
        $contant = $_REQUEST['vendor_type_desc'];   //信息内容

        $url            = Yf_Registry::get('ucenter_api_url');
        $key            = Yf_Registry::get('pcenter_erp_key');

        $data['app_id'] = Yf_Registry::get('app_id');
        $data['ctl'] = 'ImApi';
        $data['met'] = 'pushMsg';
        $data['typ'] = 'json';
        $data['receiver'] = $r_name;
        $data['push_type'] = 1;
        $data['msg_content'] = $contant;
        $result = get_url_with_encrypt($key,$url,$data);
        if($result)
        {
            $e = strip_tags($result['d'][1]);
            if($e =='push msg success!')
            {
                $msg = 'success';
                $status = 200;
            }
            else
            {
                $msg = $e;
                $status = 250;
            }
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
?>
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
    //消息记录
    public function record()
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

    public function site()
    {
        include $this->view->getView();
    }
    //获取消息列表    (    废弃    )
//    public function getList()
//    {
//        error_reporting(0);
//        $skey   = $_REQUEST['skey'];
//        $skey1  = $_REQUEST['skey1'];
//            if($skey)
//            {
//                $data['msg_sender'] = $skey;
//            }
//            if($skey1)
//            {
//                $data['msg_receiver'] = $skey1;
//            }
//            $url            = Yf_Registry::get('imbuilder_api_url');
//            $key            = Yf_Registry::get('imbuilder_erp_key');
//
//            $data['app_id'] = Yf_Registry::get('app_id');
//            $data['ctl'] = 'ImApi';
//            $data['met'] = 'getMsgList';
//            $data['typ'] = 'json';
//            $data['page']= $_REQUEST['page'];
//            $data['rows']= 20;
//            $data1 = get_url_with_encrypt($key,$url,$data);
//        $data3=$data1['data'];
//
//        $status = $data1['status'];
//        $msg    = $data1['msg'];
//
//        $this->data->addBody(-140,$data3,$msg,$status);
//    }



    //获取好友列表信息   (    废弃    )
//    public function getFriends()
//    {
//        error_reporting(0);
//        $skey = $_REQUEST['skey'];
//        $userInfoDetailModel = new User_InfoDetailModel();
//        if($skey)
//        {
//            $data['user_name'] = $skey;
//        }
//
//        $url            = Yf_Registry::get('imbuilder_api_url');
//        $key            = Yf_Registry::get('imbuilder_erp_key');
//
//        $data['app_id'] = Yf_Registry::get('app_id');
//        $data['ctl'] = 'ImApi';
//        $data['met'] = 'getUserList';
//        $data['typ'] = 'json';
//        $data['page']= $_REQUEST['page'];
//        $data['rows']=20;
//        $data_rs = get_url_with_encrypt($key,$url,$data);
//        /*$data = $userInfoDetailModel->getUserList();
//        $items = $data['items'];*/
//        $data = array();
//        $data = $data_rs['data'];
//
//        if($data_rs['status']==200)
//        {
//            foreach($data['items'] as $key=>$value)
//            {
//                if($value['user_gender']==0)
//                {
//                    $data['items'][$key]['user_gender']='女';
//                }
//                else
//                {
//                    $data['items'][$key]['user_gender']='男';
//                }
//                $user_reg_time = $value['user_reg_time'];
//                $data['items'][$key]['user_reg_time'] = date('Y-m-d h:i:s',$user_reg_time);
//                $user_lastlogin_time = $value['user_lastlogin_time'];
//                $data['items'][$key]['user_lastlogin_time'] = date('Y-m-d h:i:s',$user_lastlogin_time);
//            }
//        }
//
//        if($data){
//            $msg = 'success';
//            $status = 200;
//        }
//        else{
//            $msg = 'failure';
//            $status = 250;
//        }
//        $this->data->addBody(-140,$data,$msg,$status);
//    }

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
//        echo '<pre>';print_r([$receiver_name,$contant,$result,$url,$key]);exit;
        Yf_Log::log("result=".print_r($result), Yf_Log::INFO, 'im_push');
        if($result)
        {
//            $e = strip_tags($result['d'][1]);
//            if($e =='push msg success!')
//            {
//                $msg = 'success';
//                $status = 200;
//            }
//            else
//            {
//                $msg = $e;
//                $status = 250;
//            }
            
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

    //朋友圈管理
    public function firends()
    {
        include $this->view->getView();
    }


    //获取朋友圈信息
    public function friendsList()
    {
        $url            = Yf_Registry::get('imbuilder_api_url');
        $key            = Yf_Registry::get('imbuilder_erp_key');
        $data['app_id'] = Yf_Registry::get('app_id');
        $data['ctl'] = 'ImApi';
        $data['met'] = 'getSns';
        $data['typ'] = 'json';
        $data['page'] = $_REQUEST['page'];
        $data['rows'] = 20;
        $result = get_url_with_encrypt($key,$url,$data);

        if($result['status']==200)
        {
            $rows = $result['data']['items'];
            if(!empty($rows)){
                foreach($rows as $key => $value)
                {
                    $data_rs[$key]['id'] = $value['sns']['sns_id'];
                    $data_rs[$key]['user_id'] = $value['sns']['user_id'];
                    $data_rs[$key]['user_name'] = $value['sns']['user_name'];
                    $data_rs[$key]['sns_title'] = $value['sns']['sns_title'];
                    $data_rs[$key]['sns_content'] = $value['sns']['sns_content'];
                    $data_rs[$key]['sns_create_time'] = date('Y-m-d h:i:s',$value['sns']['sns_create_time']);
                    $data_rs[$key]['sns_comment_count'] = $value['sns']['sns_comment_count'];  //评论人数
                    $data_rs[$key]['sns_copy_count'] = $value['sns']['sns_copy_count'];//转发人数
                    $data_rs[$key]['sns_like_count'] = $value['sns']['sns_like_count'];//点赞人数
                    foreach($value['sns']['img'] as $k=>$v)
                    {
                        $data_rs[$key]['img'][$k] = $v;
                    }
                }
            }
            if(!empty($data_rs))
            {
                $msg = 'success';
                $status = 200;
            }
        }
        else
        {
            $data_rs = array();
            $msg = $result['msg'];
            $status = $result['status'];
        }
        $data_re['page'] = $result['data']['page'];
        $data_re['total'] = $result['data']['total'];
        $data_re['totalsize'] = $result['data']['totalsize'];
        $data_re['records'] = $result['data']['records'];
        $data_re['items'] = $data_rs;

        $this->data->addBody(-140,$data_re,$msg,$status);
    }
    public function picture()
    {
        include $this->view->getView();
    }

    public function getImagesById()
    {
        $id = $_REQUEST['id'];
        $data['sns_id'] = $id;
        $url            = Yf_Registry::get('imbuilder_api_url');
        $key            = Yf_Registry::get('imbuilder_erp_key');
        $data['app_id'] = Yf_Registry::get('app_id');
        $data['ctl'] = 'ImApi';
        $data['met'] = 'getSnsInfo';
        $data['typ'] = 'json';
        $result = get_url_with_encrypt($key,$url,$data);

        if($result['status']==200)
        {
            $img = $result['data']['img'];
            if($img)
            {
                foreach($img as $key=>$value)
                {
                    $items[]['url'] = $value;
                }
                echo json_encode( array('msg' => 'success','status' =>200,'files'=>$items) );
                die();
            }
            else
            {
                $items = array();
                echo json_encode( array('msg' => 'success','status' =>200,'files'=>$items) );
                die();
            }
        }
        else
        {
            $items = array();
            echo json_encode( array('msg' => 'success','status' =>200,'files'=>$items) );
            die();
        }
    }

    public function comment()
    {
        include $this->view->getView();
    }

    public function commentList()
    {
        $id = $_REQUEST['id'];
        $data['sns_id'] = $id;
        $url            = Yf_Registry::get('imbuilder_api_url');
        $key            = Yf_Registry::get('imbuilder_erp_key');
        $data['app_id'] = Yf_Registry::get('app_id');
        $data['ctl'] = 'ImApi';
        $data['met'] = 'getSnsInfo';
        $data['typ'] = 'json';
        $result = get_url_with_encrypt($key,$url,$data);
        if($result['status']==200)
        {
            $comment = $result['data']['comment'];
            if($comment)
            {
                foreach($comment as $key=>$value)
                {
                    $items[$key]['id'] = $value['commect_id'];
                    $items[$key]['commect_id'] = $value['commect_id'];
                    $items[$key]['user_name'] = $value['user_name'];
                    $items[$key]['commect_addtime'] = date('Y-m-d h:i:s',$value['commect_addtime']);
                    $items[$key]['commect_content'] = $value['commect_content'];
                }
                $data_rs['items'] = $items;
                $msg = 'success';
                $status = 200;
            }
            else
            {
                $data_rs = array();
                $msg = '没有查询到回复内容';
                $status = 250;
            }
        }
        else
        {
            $data_rs = array();
            $msg = $result['result'];
            $status = $result['status'];
        }
        $this->data->addBody(-140,$data_rs,$msg,$status);
    }

    public function removeFriends()
    {
        $id = $_REQUEST['id'];
        $data['sns_id'] = $id;
        $url            = Yf_Registry::get('imbuilder_api_url');
        $key            = Yf_Registry::get('imbuilder_erp_key');
        $data['app_id'] = Yf_Registry::get('app_id');
        $data['ctl'] = 'ImApi';
        $data['met'] = 'delSns';
        $data['typ'] = 'json';
        $result = get_url_with_encrypt($key,$url,$data);
        $msg = $result['msg'];
        $status = $result['status'];
        $data = array();
        $this->data->addBody(-140,$data,$msg,$status);
    }

    public function removeComment()
    {
        $id = $_REQUEST['id'];
        $data['commect_id'] = $id;
        $url            = Yf_Registry::get('imbuilder_api_url');
        $key            = Yf_Registry::get('imbuilder_erp_key');
        $data['app_id'] = Yf_Registry::get('app_id');
        $data['ctl'] = 'ImApi';
        $data['met'] = 'delCommect';
        $data['typ'] = 'json';
        $result = get_url_with_encrypt($key,$url,$data);
        $msg = $result['msg'];
        $status = $result['status'];
        $data = array();
        $this->data->addBody(-140,$data,$msg,$status);
    }
}
?>
<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));
    
    /**
     * @author     Xinze <xinze@live.cn>
     */
    class Api_ChatlogCtl extends Yf_AppController
    {
        public $model;
        
        public function __construct(&$ctl, $met, $typ)
        {
            parent::__construct($ctl, $met, $typ);
            $this->model = new Chatlog;
        }
        
        public function get()
        {
            $r = $this->model->getChatlog();
            $html = $this->model->getChatlogHtml($r);
            $out['html'] = $html;
            $this->data->addBody(100, $out);
            
        }
        
        //wap端获取最近聊天用户消息列表，不指定某个聊天者
        public function getMessage()
        {
            $r = $this->model->getMessagelog();
            $r['user_account'] = $_REQUEST['u'];
            //将消息修改为已读
            if ($r['user_account']) {
                $addMessage = new User_MsgModel();
                $res = $addMessage->editUserReadStatus($r['user_account']);
            }
            $this->data->addBody(100, $r);
        }
        
        //wap端通过主键id删除某条聊天记录
        public function delMessage()
        {
            $_REQUEST['t_id'] = empty($_REQUEST['t_id']) ? request_int('sender') : $_REQUEST['t_id'];
            $del_flag = $this->model->delChatlog($_REQUEST['t_id']);
            if ($del_flag) {
                $status = 200;
                $msg = 'success';
            } else {
                $status = 250;
                $msg = 'failure';
            }
            $data = array();
            $this->data->addBody(100, $data, $msg, $status);
        }
        
        /*
         * IM配置获取
         *
         */
        public function add()
        {
            
            $data['sender'] = trim($_POST['u']);
            $data['receiver'] = trim($_POST['to']);
            $data['msgid'] = trim($_POST['msgid']);
            $data['content'] = trim($_POST['content']);
            $data['type'] = trim($_POST['type']);
            $data['created'] = time();
            
            $userBase = new User_BaseModel();
            $receiverId = $userBase->getUserIdByName($data['receiver']);
            $senderId = $userBase->getUserIdByName($data['sender']);
            
            if (!$data['sender'] || !$data['receiver'] || !$data['msgid'] || !$data['content'] || !$receiverId || !$senderId) {
                
                $this->data->setError('异常错误');
                
            } else {
                $params['app_id_sender'] = $senderId;
                $params['app_id_receiver'] = $receiverId;
                $params['msg_sender'] = $data['sender'];
                $params['msg_receiver'] = $data['receiver'];
                $params['msg_id'] = $data['msgid'];
                $params['msg_content'] = $data['content'];
                $params['msg_type'] = $data['type'];
                $params['date_created'] = $data['created'];
                
                $addMessage = new User_MsgModel();
                $addMessage->addMsg($params);
                
                $id = $this->model->addChatlog($data);
                $out = array('id' => $id);
                
            }
            
            $this->data->addBody(100, $out);
            
        }
        
        //获取未读信息的数量
        public function getUnreadMsgCount()
        {
            $user_account = request_string('u');
            $addMessage = new User_MsgModel();
            $count = $addMessage->getUnreadMsgCount($user_account);
            return $this->data->addBody(100, array('count' => $count));
        }
        
        /**
         * wap端消息列表
         */
        public function getMessageList()
        {
            $r = $this->model->getMessageListlog();
            $r['user_account'] = $_REQUEST['u'];
            //将消息修改为已读
            if ($r['user_account']) {
                $addMessage = new User_MsgModel();
                $res = $addMessage->editUserReadStatus($r['user_account']);
            }
            $this->data->addBody(100, $r);
        }
        
        //wap端消息列表删除某条聊天记录
        public function delMessageList()
        {
            $user = request_string('u');
            $sender = request_string('sender');
            $log_id_list1 = $this->model->getByWhere(array('sender' => $sender, 'receiver' => $user, 'status' => 0), array('created' => 'desc'));
            $log_id_list2 = $this->model->getByWhere(array('sender' => $user, 'receiver' => $sender, 'status' => 0), array('created' => 'desc'));
            $ids1 = $log_id_list1 && is_array($log_id_list1) ? array_column($log_id_list1, 'id') : [];
            $ids2 = $log_id_list2 && is_array($log_id_list2) ? array_column($log_id_list2, 'id') : [];
            $ids = array_merge($ids1, $ids2);
            $edit_res = $this->model->editInfo($ids, array('status' => 1)); //删除记录
            
            if ($edit_res !== false) {
                $status = 200;
                $msg = 'success';
            } else {
                $status = 250;
                $msg = 'failure';
            }
            $data = array();
            return $this->data->addBody(100, $data, $msg, $status);
        }
    }
 
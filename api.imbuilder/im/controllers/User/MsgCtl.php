<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_MsgCtl extends Yf_AppController
{
    public $userMsgModel = null;

    /**
     * Constructor
     *
     * @param  string $ctl 控制器目录
     * @param  string $met 控制器方法
     * @param  string $typ 返回数据类型
     * @access public
     */
    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);

        //include $this->view->getView();
        $this->userMsgModel = new User_MsgModel();
    }

    /**
     * 首页
     * 
     * @access public
     */
    public function index()
    {
        include $this->view->getView();
    }
    
    /**
     * 管理界面
     * 
     * @access public
     */
    public function manage()
    {
        include $this->view->getView();
    }
    

    /**
     * 列表数据
     * 
     * @access public
     */
    public function MsgList()
    {
        $user_id = Perm::$userId;

		$page = $_REQUEST['page'];
		$rows = $_REQUEST['rows'];
		$sort = $_REQUEST['sord'];


		$data = array();

		if (isset($_REQUEST['skey']))
		{
			$skey = $_REQUEST['skey'];

			$data = $this->userMsgModel->getMsgList('*', $page, $rows, $sort);
		}
		else
		{
			$data = $this->userMsgModel->getMsgList('*', $page, $rows, $sort);
		}


		$this->data->addBody(-140, $data);
    }

    /**
     * 读取
     * 
     * @access public
     */
    public function get()
    {
        $user_id = Perm::$userId;

		$msg_log_id = $_REQUEST['msg_log_id'];
		$rows = $this->userMsgModel->getMsg($msg_log_id);

		$data = array();

		if ($rows)
		{
			$data = array_pop($rows);
		}

		$this->data->addBody(-140, $data);
    }

    /**
     * 添加
     *
     * @access public
     */
    public function add()
    {
        $data['msg_log_id']             = $_REQUEST['msg_log_id']         ; // ID              
        $data['app_id_sender']          = $_REQUEST['app_id_sender']      ; // 发送者id        
        $data['msg_sender']             = $_REQUEST['msg_sender']         ; // 发送者名称      
        $data['app_id_receiver']        = $_REQUEST['app_id_receiver']    ; // 接受者id        
        $data['msg_receiver']           = $_REQUEST['msg_receiver']       ; // 接收者名称      
        $data['device_type']            = $_REQUEST['device_type']        ; //                 
        $data['msg_len']                = $_REQUEST['msg_len']            ; //                 
        $data['msg_type']               = $_REQUEST['msg_type']           ; //                 
        $data['msg_content']            = $_REQUEST['msg_content']        ; //                 
        $data['msg_file_url']           = $_REQUEST['msg_file_url']       ; //                 
        $data['msg_file_name']          = $_REQUEST['msg_file_name']      ; //                 
        $data['group_id']               = $_REQUEST['group_id']           ; //                 
        $data['msg_id']                 = $_REQUEST['msg_id']             ; //                 
        $data['msg_file_size']          = $_REQUEST['msg_file_size']      ; //                 
        $data['date_created']           = $_REQUEST['date_created']       ; // 日期            
        $data['msg_domain']             = $_REQUEST['msg_domain']         ; // 说明            


        $msg_log_id = $this->userMsgModel->addMsg($data, true);

        if ($msg_log_id)
        {
            $msg = 'success';
            $status = 200;
        }
        else
        {
            $msg = 'failure';
            $status = 250;
        }

        $data['msg_log_id'] = $msg_log_id;

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 删除操作
     *
     * @access public
     */
    public function remove()
    {
        $msg_log_id = $_REQUEST['msg_log_id'];

        $flag = $this->userMsgModel->removeMsg($msg_log_id);

        if ($flag)
        {
            $msg = 'success';
            $status = 200;
        }
        else
        {
            $msg = 'failure';
            $status = 250;
        }

        $data['msg_log_id'] = $msg_log_id;

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 修改
     *
     * @access public
     */
    public function edit()
    {
        $data['msg_log_id']             = $_REQUEST['msg_log_id']         ; // ID              
        $data['app_id_sender']          = $_REQUEST['app_id_sender']      ; // 发送者id        
        $data['msg_sender']             = $_REQUEST['msg_sender']         ; // 发送者名称      
        $data['app_id_receiver']        = $_REQUEST['app_id_receiver']    ; // 接受者id        
        $data['msg_receiver']           = $_REQUEST['msg_receiver']       ; // 接收者名称      
        $data['device_type']            = $_REQUEST['device_type']        ; //                 
        $data['msg_len']                = $_REQUEST['msg_len']            ; //                 
        $data['msg_type']               = $_REQUEST['msg_type']           ; //                 
        $data['msg_content']            = $_REQUEST['msg_content']        ; //                 
        $data['msg_file_url']           = $_REQUEST['msg_file_url']       ; //                 
        $data['msg_file_name']          = $_REQUEST['msg_file_name']      ; //                 
        $data['group_id']               = $_REQUEST['group_id']           ; //                 
        $data['msg_id']                 = $_REQUEST['msg_id']             ; //                 
        $data['msg_file_size']          = $_REQUEST['msg_file_size']      ; //                 
        $data['date_created']           = $_REQUEST['date_created']       ; // 日期            
        $data['msg_domain']             = $_REQUEST['msg_domain']         ; // 说明            


        $msg_log_id = $_REQUEST['msg_log_id'];
		$data_rs = $data;

        unset($data['msg_log_id']);

        $flag = $this->userMsgModel->editMsg($msg_log_id, $data);
        $this->data->addBody(-140, $data_rs);
    }
}
?>
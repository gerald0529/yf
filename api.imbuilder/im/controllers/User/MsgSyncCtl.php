<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_MsgSyncCtl extends Yf_AppController
{
    public $userMsgSyncModel = null;

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
        $this->userMsgSyncModel = new User_MsgSyncModel();
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
    public function MsgSyncList()
    {
        $user_id = Perm::$userId;

		$page = $_REQUEST['page'];
		$rows = $_REQUEST['rows'];
		$sort = $_REQUEST['sord'];


		$data = array();

		if (isset($_REQUEST['skey']))
		{
			$skey = $_REQUEST['skey'];

			$data = $this->userMsgSyncModel->getMsgSyncList('*', $page, $rows, $sort);
		}
		else
		{
			$data = $this->userMsgSyncModel->getMsgSyncList('*', $page, $rows, $sort);
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

		$msg_sync_date = $_REQUEST['msg_sync_date'];
		$rows = $this->userMsgSyncModel->getMsgSync($msg_sync_date);

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
        $data['msg_sync_date']          = $_REQUEST['msg_sync_date']      ; // ID              
        $data['msg_sync_flag']          = $_REQUEST['msg_sync_flag']      ; // 是否同步：0：未同步； 1:已经同步


        $msg_sync_date = $this->userMsgSyncModel->addMsgSync($data, true);

        if ($msg_sync_date)
        {
            $msg = 'success';
            $status = 200;
        }
        else
        {
            $msg = 'failure';
            $status = 250;
        }

        $data['msg_sync_date'] = $msg_sync_date;

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 删除操作
     *
     * @access public
     */
    public function remove()
    {
        $msg_sync_date = $_REQUEST['msg_sync_date'];

        $flag = $this->userMsgSyncModel->removeMsgSync($msg_sync_date);

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

        $data['msg_sync_date'] = $msg_sync_date;

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 修改
     *
     * @access public
     */
    public function edit()
    {
        $data['msg_sync_date']          = $_REQUEST['msg_sync_date']      ; // ID              
        $data['msg_sync_flag']          = $_REQUEST['msg_sync_flag']      ; // 是否同步：0：未同步； 1:已经同步


        $msg_sync_date = $_REQUEST['msg_sync_date'];
		$data_rs = $data;

        unset($data['msg_sync_date']);

        $flag = $this->userMsgSyncModel->editMsgSync($msg_sync_date, $data);
        $this->data->addBody(-140, $data_rs);
    }
}
?>
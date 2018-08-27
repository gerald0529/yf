<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_FriendCtl extends Yf_AppController
{
    public $userFriendModel = null;

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
        $this->userFriendModel = new User_FriendModel();
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
    public function FriendList()
    {
        $user_id = Perm::$userId;

		$page = $_REQUEST['page'];
		$rows = $_REQUEST['rows'];
		$sort = $_REQUEST['sord'];


		$data = array();

		if (isset($_REQUEST['skey']))
		{
			$skey = $_REQUEST['skey'];

			$data = $this->userFriendModel->getFriendList('*', $page, $rows, $sort);
		}
		else
		{
			$data = $this->userFriendModel->getFriendList('*', $page, $rows, $sort);
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

		$user_id = $_REQUEST['user_id'];
		$rows = $this->userFriendModel->getFriend($user_id);

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
        $data['user_id']                = $_REQUEST['user_id']            ; // 用户ID          
        $data['friend_id']              = $_REQUEST['friend_id']          ; // 好友id          
        $data['add_time']               = $_REQUEST['add_time']           ; // 添加时间        


        $user_id = $this->userFriendModel->addFriend($data, true);

        if ($user_id)
        {
            $msg = 'success';
            $status = 200;
        }
        else
        {
            $msg = 'failure';
            $status = 250;
        }

        $data['user_id'] = $user_id;

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 删除操作
     *
     * @access public
     */
    public function remove()
    {
        $user_id = $_REQUEST['user_id'];

        $flag = $this->userFriendModel->removeFriend($user_id);

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

        $data['user_id'] = $user_id;

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 修改
     *
     * @access public
     */
    public function edit()
    {
        $data['user_id']                = $_REQUEST['user_id']            ; // 用户ID          
        $data['friend_id']              = $_REQUEST['friend_id']          ; // 好友id          
        $data['add_time']               = $_REQUEST['add_time']           ; // 添加时间        


        $user_id = $_REQUEST['user_id'];
		$data_rs = $data;

        unset($data['user_id']);

        $flag = $this->userFriendModel->editFriend($user_id, $data);
        $this->data->addBody(-140, $data_rs);
    }
}
?>
<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_InfoCtl extends Yf_AppController
{
    public $userInfoModel = null;

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
        $this->userInfoModel = new User_BaseModel();
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
    public function InfoList()
    {
        $user_id = Perm::$userId;

		$page = $_REQUEST['page'];
		$rows = $_REQUEST['rows'];
		$sort = $_REQUEST['sord'];


		$data = array();

		if (isset($_REQUEST['skey']))
		{
			$skey = $_REQUEST['skey'];

			$data = $this->userInfoModel->getInfoList('*', $page, $rows, $sort);
		}
		else
		{
			$data = $this->userInfoModel->getInfoList('*', $page, $rows, $sort);
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
		$rows = $this->userInfoModel->getInfo($user_id);

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
        $data['user_name']              = $_REQUEST['user_name']          ; // 用户名          
        $data['password']               = $_REQUEST['password']           ; // 密码            
        $data['email']                  = $_REQUEST['email']              ; // 电子邮件        
        $data['user_state']             = $_REQUEST['user_state']         ; // 状态(0:未激活,1:未认证,2:已认证,3:锁定)
        $data['action_time']            = $_REQUEST['action_time']        ; // 活动时间        
        $data['action_ip']              = $_REQUEST['action_ip']          ; // 活动IP          
        $data['session_id']             = $_REQUEST['session_id']         ; //                 


        $user_id = $this->userInfoModel->addInfo($data, true);

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

        $flag = $this->userInfoModel->removeInfo($user_id);

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
        $data['user_name']              = $_REQUEST['user_name']          ; // 用户名          
        $data['password']               = $_REQUEST['password']           ; // 密码            
        $data['email']                  = $_REQUEST['email']              ; // 电子邮件        
        $data['user_state']             = $_REQUEST['user_state']         ; // 状态(0:未激活,1:未认证,2:已认证,3:锁定)
        $data['action_time']            = $_REQUEST['action_time']        ; // 活动时间        
        $data['action_ip']              = $_REQUEST['action_ip']          ; // 活动IP          
        $data['session_id']             = $_REQUEST['session_id']         ; //                 


        $user_id = $_REQUEST['user_id'];
		$data_rs = $data;

        unset($data['user_id']);

        $flag = $this->userInfoModel->editInfo($user_id, $data);
        $this->data->addBody(-140, $data_rs);
    }
}
?>
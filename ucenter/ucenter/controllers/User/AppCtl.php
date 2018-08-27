<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_AppCtl extends Yf_AppController
{
    public $userAppModel = null;

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
        $this->userAppModel = new User_AppModel();
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
    public function AppList()
    {
        $user_id = Perm::$userId;

		$page = $_REQUEST['page'];
		$rows = $_REQUEST['rows'];
		$sort = $_REQUEST['sord'];


		$data = array();

		if (isset($_REQUEST['skey']))
		{
			$skey = $_REQUEST['skey'];

			$data = $this->userAppModel->getAppList('*', $page, $rows, $sort);
		}
		else
		{
			$data = $this->userAppModel->getAppList('*', $page, $rows, $sort);
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

		$user_name = $_REQUEST['user_name'];
		$rows = $this->userAppModel->getApp($user_name);

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
        $data['user_name']              = $_REQUEST['user_name']          ; // 用户名          
        $data['app_id']                 = $_REQUEST['app_id']             ; // 服务ID          
        $data['app_user_id']            = $_REQUEST['app_user_id']        ; // 用户在该服务的ID
        $data['app_user_level']         = $_REQUEST['app_user_level']     ; // 用户在该服务的等级
        $data['active_time']            = $_REQUEST['active_time']        ; // 激活时间        


        $user_name = $this->userAppModel->addApp($data, true);

        if ($user_name)
        {
            $msg = 'success';
            $status = 200;
        }
        else
        {
            $msg = 'failure';
            $status = 250;
        }

        $data['user_name'] = $user_name;

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 删除操作
     *
     * @access public
     */
    public function remove()
    {
        $user_name = $_REQUEST['user_name'];

        $flag = $this->userAppModel->removeApp($user_name);

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

        $data['user_name'] = $user_name;

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 修改
     *
     * @access public
     */
    public function edit()
    {
        $data['user_name']              = $_REQUEST['user_name']          ; // 用户名          
        $data['app_id']                 = $_REQUEST['app_id']             ; // 服务ID          
        $data['app_user_id']            = $_REQUEST['app_user_id']        ; // 用户在该服务的ID
        $data['app_user_level']         = $_REQUEST['app_user_level']     ; // 用户在该服务的等级
        $data['active_time']            = $_REQUEST['active_time']        ; // 激活时间        


        $user_name = $_REQUEST['user_name'];
		$data_rs = $data;

        unset($data['user_name']);

        $flag = $this->userAppModel->editApp($user_name, $data);
        $this->data->addBody(-140, $data_rs);
    }
}
?>
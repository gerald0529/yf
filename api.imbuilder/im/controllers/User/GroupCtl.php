<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_GroupCtl extends Yf_AppController
{
    public $userGroupModel = null;

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
        $this->userGroupModel = new User_GroupModel();
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
    public function GroupList()
    {
        $user_id = Perm::$userId;

		$page = $_REQUEST['page'];
		$rows = $_REQUEST['rows'];
		$sort = $_REQUEST['sord'];


		$data = array();

		if (isset($_REQUEST['skey']))
		{
			$skey = $_REQUEST['skey'];

			$data = $this->userGroupModel->getGroupList('*', $page, $rows, $sort);
		}
		else
		{
			$data = $this->userGroupModel->getGroupList('*', $page, $rows, $sort);
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

		$group_id = $_REQUEST['group_id'];
		$rows = $this->userGroupModel->getGroup($group_id);

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
        $data['group_id']               = $_REQUEST['group_id']           ; // 好友组ID        
        $data['group_name']             = $_REQUEST['group_name']         ; // 组名称          
        $data['group_type']             = $_REQUEST['group_type']         ; // 组分类（商品等）
        $data['group_describe']         = $_REQUEST['group_describe']     ; //                 
        $data['user_id']                = $_REQUEST['user_id']            ; // 会员及店铺ID    


        $group_id = $this->userGroupModel->addGroup($data, true);

        if ($group_id)
        {
            $msg = 'success';
            $status = 200;
        }
        else
        {
            $msg = 'failure';
            $status = 250;
        }

        $data['group_id'] = $group_id;

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 删除操作
     *
     * @access public
     */
    public function remove()
    {
        $group_id = $_REQUEST['group_id'];

        $flag = $this->userGroupModel->removeGroup($group_id);

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

        $data['group_id'] = $group_id;

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 修改
     *
     * @access public
     */
    public function edit()
    {
        $data['group_id']               = $_REQUEST['group_id']           ; // 好友组ID        
        $data['group_name']             = $_REQUEST['group_name']         ; // 组名称          
        $data['group_type']             = $_REQUEST['group_type']         ; // 组分类（商品等）
        $data['group_describe']         = $_REQUEST['group_describe']     ; //                 
        $data['user_id']                = $_REQUEST['user_id']            ; // 会员及店铺ID    


        $group_id = $_REQUEST['group_id'];
		$data_rs = $data;

        unset($data['group_id']);

        $flag = $this->userGroupModel->editGroup($group_id, $data);
        $this->data->addBody(-140, $data_rs);
    }
}
?>
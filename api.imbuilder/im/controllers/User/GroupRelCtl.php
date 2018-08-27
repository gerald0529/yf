<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_GroupRelCtl extends Yf_AppController
{
    public $userGroupRelModel = null;

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
        $this->userGroupRelModel = new User_GroupRelModel();
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
    public function GroupRelList()
    {
        $user_id = Perm::$userId;

		$page = $_REQUEST['page'];
		$rows = $_REQUEST['rows'];
		$sort = $_REQUEST['sord'];


		$data = array();

		if (isset($_REQUEST['skey']))
		{
			$skey = $_REQUEST['skey'];

			$data = $this->userGroupRelModel->getGroupRelList('*', $page, $rows, $sort);
		}
		else
		{
			$data = $this->userGroupRelModel->getGroupRelList('*', $page, $rows, $sort);
		}


		$this->data->addBody(-140, $data);
    }

}
?>
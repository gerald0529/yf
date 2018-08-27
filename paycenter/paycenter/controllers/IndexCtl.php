<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class IndexCtl extends Controller
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}

	//首页
	public function index()
	{
		if (!Perm::checkUserPerm())
		{
			include $this->view->getView();
		}
		else
		{
			header('location:' . Yf_Registry::get('base_url') . '/index.php?ctl=Info&met=index');
			exit();
		}
	}

    /**
     *  空白iframe页面
     *  shop商城调用
     */
    public function iframe(){
        include $this->view->getView();
    }
}

?>
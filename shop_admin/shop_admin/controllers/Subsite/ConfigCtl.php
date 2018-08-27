<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Subsite_ConfigCtl extends AdminController
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}

	public function index()
	{
		include $this->view->getView();
	}

	public function config()
	{
		include $this->view->getView();
	}

	function manage()
	{
		include $view = $this->view->getView();
	}
    public function subsiteManage(){
		include $this->view->getView();
	}
    
}

?>
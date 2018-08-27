<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author yesai
 */
class Promotion_RedPacketCtl extends AdminController
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}

	public function index()
	{
		$view = $this->view->getView();
		include $view;
	}



}

?>
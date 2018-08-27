<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class FeedCtl extends AdminController
{
    function index()
    {
        include $view = $this->view->getView();
    }
}

?>
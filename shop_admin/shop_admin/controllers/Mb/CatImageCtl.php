<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Mb_CatImageCtl extends AdminController
{
    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);
    }

    public function index()
    {
        include $this->view->getView();
    }

    public function manage()
    {
        include $this->view->getView();
    }
}

?>
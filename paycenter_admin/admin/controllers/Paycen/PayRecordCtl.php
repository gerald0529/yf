<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author     banchangle
 */
class Paycen_PayRecordCtl extends AdminController
{
    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);
    }
      public function index()
    {
        include $view = $this->view->getView();

    }   
}

?>
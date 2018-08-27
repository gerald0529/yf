<?php if (!defined('ROOT_PATH')) exit('No Permission');
class Database_BackupCtl extends AdminController
{
    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);
    }
    public function index()
    {
        include $this->view->getView();
    }

}
?>
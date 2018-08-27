<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author     banchangle
 */
class Paycen_BtInfoCtl extends AdminController
{
    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);
    }

    public function index()
    {
        include $view = $this->view->getView();
    }

    public function setBtLimit()
    {
        include $view = $this->view->getView();
    }

    public function setBtReturn()
    {
        include $view = $this->view->getView();
    }

    public function setBtOrderList()
    {
        include $view = $this->view->getView();
    }

    public function editCreditLimit()
    {
        $ctl = 'Paycen_PayInfo';
        $met = 'getCreditInfo';
        $data = $this->getUrl($ctl, $met);
        include $view = $this->view->getView();
    }

    public function editCreditReturn()
    {
        $ctl = 'Paycen_PayInfo';
        $met = 'getCreditInfo';
        $data = $this->getUrl($ctl, $met);
        include $view = $this->view->getView();
    }
   

}

?>
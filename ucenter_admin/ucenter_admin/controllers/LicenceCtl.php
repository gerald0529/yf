<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class LicenceCtl extends AdminController
{
    public function index()
    {
        include $this->view->getView();
    }

}
?>
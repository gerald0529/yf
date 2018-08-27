<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class IndexCtl extends Yf_AppController
{
    public function index()
    {
        include $this->view->getView();
    }

    public function main()
    {
        $a = _("asa");
        include $this->view->getView();
    }
}
?>
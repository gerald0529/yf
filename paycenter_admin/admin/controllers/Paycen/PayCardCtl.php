<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Paycen_PayCardCtl extends AdminController
{
    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);
    }

    public function index()
    {
        include $view = $this->view->getView();;

    }

    public function manage()
    {
        include $view = $this->view->getView();;

    }
    public function payCard()
    {
        //如果该页面有payCard的方法则使用一下方法来调用Paycen_PayCard的getCardBaseList方法
        $ctl = 'Paycen_PayCard';
        $met = 'getCardBaseList';
        $data = $this->getUrl($ctl, $met);

        include $view = $this->view->getView();;

    }

}

?>
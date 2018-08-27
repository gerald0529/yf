<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author
 */
class Shop_SelfsupportCtl extends AdminController
{
    
    public function setIm(){
        $ctl = $_REQUEST['ctl'];
		$met = $_REQUEST['met'];
		$data = $this->getUrl($ctl, $met);
        $user_account = $data['user_account'];
        include $this->view->getView();
        
    }
    
}

?>
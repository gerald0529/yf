<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    /**
     * @author
     */
    class Trade_ReturnCtl extends AdminController
    {
        public $webconfigModel = null;
        
        public function __construct(&$ctl, $met, $typ)
        {
            parent::__construct($ctl, $met, $typ);
        }
        
        public function refundWait()
        {
            $otyp = request_int("otyp", 1);
            include $this->view->getView();
        }
        
        public function refundAll()
        {
            $otyp = request_int("otyp", 1);
            include $this->view->getView();
        }
    }

?>
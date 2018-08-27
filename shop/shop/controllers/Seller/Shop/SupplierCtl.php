<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    /**
     * @author     Banchangle
     */
    class Seller_Shop_SupplierCtl extends Seller_Controller
    {
        public $shopSupplierModel = null;
        
        /**
         * Constructor
         *
         * @param  string $ctl 控制器目录
         * @param  string $met 控制器方法
         * @param  string $typ 返回数据类型
         *
         * @access public
         */
        public function __construct(&$ctl, $met, $typ)
        {
            parent::__construct($ctl, $met, $typ);
            $this->shopSupplierModel = new Shop_SupplierModel();
            
        }
        
        /**
         * 首页
         *
         * @access public
         */
        public function supplier()
        {
            $act = request_string('act');
            if ($act == 'edit') {
                $supplier_id = request_int('supplier_id');
                
                $cond_row['id'] = $supplier_id;
                $data = $this->shopSupplierModel->getSupplierinfo($supplier_id);
                $this->view->setMet('setSupplier');
                
            } elseif ($act == 'add') {
                $this->view->setMet('setSupplier');
                $data = array();
            } else {
                $Yf_Page = new Yf_Page();
                $Yf_Page->listRows = 10;
                $rows = $Yf_Page->listRows;
                $offset = request_int('firstRow', 0);
                $page = ceil_r($offset / $rows);
                $shop_id = Perm::$shopId;
                $brand_search = request_string('supplier_name');
                $cond_row = array('shop_id' => $shop_id);
                
                if ($brand_search) {
                    $type = 'supplier_name:LIKE';
                    $cond_row[$type] = '%' . $brand_search . '%';
                }
                
                $data = $this->shopSupplierModel->getSupplierlist($cond_row, array(), $page, $rows);
                $Yf_Page->totalRows = $data['totalsize'];
                $page_nav = $Yf_Page->prompt();
                
            }
            
            if ('json' == $this->typ) {
                
                $this->data->addBody(-140, $data);
                
            } else {
                include $this->view->getView();
            }
        }
        
        public function addSupplier()
        {
            $supplier = request_row("supplier");
            $supplier['shop_id'] = Perm::$shopId;
            $flag = $this->shopSupplierModel->addsupplier($supplier);
            if ($flag) {
                $status = 200;
                $msg = __('success');
            } else {
                $status = 250;
                $msg = __('failure');
            }
            $data = array();
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
        public function editSupplier()
        {
            
            $supplier = request_row("supplier");
            $supplier_id = request_int("supplier_id");
            
            $flag = $this->shopSupplierModel->editsupplier($supplier_id, $supplier);
            
            if ($flag === "flase") {
                $status = 250;
                $msg = __('编辑失败！');
            } else {
                $status = 200;
                $msg = __('编辑成功！');
                
            }
            $data = array();
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
        public function delSupplier()
        {
            $supplier_id = request_row("id");
            $flag = $this->shopSupplierModel->removeSupplier($supplier_id);
            
            if ($flag) {
                $status = 200;
                $msg = __('删除成功！');
            } else {
                $status = 250;
                $msg = __('删除失败！');
            }
            $data = array();
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
    }

?>
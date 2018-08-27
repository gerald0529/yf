<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    /**
     * @author     Banchangle
     */
    class Seller_Shop_CatCtl extends Seller_Controller
    {
        
        public $shopGoodCatModel = null;
        public $contractModel = null;
        
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
            $this->shopGoodCatModel = new Shop_GoodCatModel();
            $this->contractModel = new Shop_ContractModel();
        }
        
        /**
         * 首页
         *
         * @access public
         */
        public function cat()
        {
            $act = request_string('act');
            if ($act == 'edit') {
                $pid = request_int('pid');
                $cond_row['id'] = $pid;
                
                $data = $this->shopGoodCatModel->getOne($pid);
                $this->view->setMet('setCat');
                
            } elseif ($act == 'add') {
                $pid = request_int('pid');
                $shop_id['shop_id'] = Perm::$shopId;
                $data = $this->shopGoodCatModel->getGoodCatparent($shop_id);
                $this->view->setMet('setCat');
            } else {
                $shop_id['shop_id'] = Perm::$shopId;
                $data = $this->shopGoodCatModel->getGoodCatList($shop_id, array('shop_goods_cat_displayorder' => 'DESC'));
            }
            if ('json' == $this->typ) {
                $this->data->addBody(-140, $data);
                
            } else {
                include $this->view->getView();
            }
        }
        
        public function addCat()
        {
            
            $cat = request_row("cat");
            $cat['shop_id'] = Perm::$shopId;
            $flag = $this->shopGoodCatModel->addGoodCat($cat);
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
        
        public function editCat()
        {
            
            $cat = request_row("cat");
            $shop_goods_cat_id = request_int("shop_goods_cat_id");
            $flag = $this->shopGoodCatModel->editGoodCat($shop_goods_cat_id, $cat);
            if ($flag === false) {
                $status = 250;
                $msg = __('failure');
            } else {
                $status = 200;
                $msg = __('success');
                
            }
            $data = array();
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
        public function delCat()
        {
            $cat_id = request_row("id");
            //开启事物
            $this->contractModel->sql->startTransactionDb();
            $cat = $this->shopGoodCatModel->getOne($cat_id);

            if ($cat['parent_id'] == "0") {
                $flag = $this->shopGoodCatModel->removeGoodAllCat($cat_id);
            } else {
                $flag = $this->shopGoodCatModel->removeGoodCat($cat_id);
            }
            
            $data = array();
    
            if ($flag && $this->contractModel->sql->commitDb()) {
                $status = 200;
                $msg = __('删除成功！');
            } else {
                $this->contractModel->sql->rollBackDb();
                $status = 250;
                $msg = __('删除失败！');
            }
            
//            if ($flag) {
//                if ($this->contractModel->sql->commitDb()) {
//                    $status = 200;
//                    $msg = __('删除成功！');
//                } else {
//                    $this->contractModel->sql->rollBackDb();
//                    $status = 250;
//                    $msg = __('删除失败！');
//                }
//            } else {
//                $status = 250;
//                $msg = __('删除失败！');
//            }
            
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
        public function delAllCat()
        {
            $cat_id_list = request_row("id");
            
            //开启事物
            $this->contractModel->sql->startTransactionDb();
            
            //删除选中的分类
            $flag = $this->shopGoodCatModel->removeGoodCat($cat_id_list);
            if ($flag && $this->contractModel->sql->commitDb()) {
                $status = 200;
                $msg = __('删除成功！');
            } else {
                $this->contractModel->sql->rollBackDb();
                $status = 250;
                $msg = __('删除失败！');
            }
            $data = array();
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
    }

?>
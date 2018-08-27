<?php if (!defined('ROOT_PATH')) exit('No Permission');
    
    /**
     * @author     yjj
     */
    class Seller_Supplier_GoodsCtl extends Seller_Controller
    {
        public $shopBaseModel;
        public $goodsTypeModel;
        public $goodsCatModel;
        public $goodsBrandModel;
        public $goodsSpecModel;
        public $goodsPropertyValueModel;
        public $goodsSpecValueModel;
        public $goodsCommonModel;
        public $goodsBaseModel;
        public $goodsCommonDetailModel;
        public $shopGoodsCat;
        
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
            
            $this->shopBaseModel = new Shop_BaseModel();
            $this->goodsTypeModel = new Goods_TypeModel();
            $this->goodsCatModel = new Goods_CatModel();
            $this->goodsBrandModel = new Goods_BrandModel();
            $this->goodsSpecModel = new Goods_SpecModel();
            $this->goodsPropertyValueModel = new Goods_PropertyValueModel();
            $this->goodsSpecValueModel = new Goods_SpecValueModel();
            $this->goodsCommonModel = new Goods_CommonModel();
            $this->goodsBaseModel = new Goods_BaseModel();
            $this->goodsCommonDetailModel = new Goods_CommonDetailModel();
            $this->shopGoodsCat = new Shop_GoodsCat();
        }
        
        //出售中的分销商品
        public function online()
        {
            $met = request_string('met');
            $action = request_string('action');
            $goods_key = request_string('goods_key');
            $Goods_CommonModel = new Goods_CommonModel();
            
            $cront_row = array('shop_id' => Perm::$shopId, 'common_parent_id:>' => 0, 'common_state' => Goods_CommonModel::GOODS_STATE_NORMAL, 'common_verify' => Goods_CommonModel::GOODS_VERIFY_ALLOW);
            if (!empty($goods_key) && isset($goods_key)) {
                $cront_row['common_name:like'] = '%' . $goods_key . '%';
            }
            
            $Yf_Page = new Yf_Page();
            $row = $Yf_Page->listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $row);
            
            $goods_rows = $Goods_CommonModel->getCommonNormal($cront_row, array('common_id' => 'DESC'), $page, $row);
            $common_id_rows = array_column($goods_rows['items'], 'common_id');
            if (!empty($common_id_rows)) {
                $goods_detail_rows = $Goods_CommonModel->getGoodsDetailRows($common_id_rows);
            }
            $goods = $Goods_CommonModel->getRecommonRow($goods_rows);
            if ('e' == $this->typ) {
                if ($action == 'edit_goods') {
                    return $this->editGoods();
                } else if ($action == 'edit_image') {
                    $common_id = request_int('common_id');
                    $data = $this->goodsImageManage($common_id);
                    
                    if (!empty($data['color'])) {
                        $color = $data['color'];
                    }
                    
                    $common_data = $data['common_data'];
                    $common_image = $common_data['common_image'];
                    
                    $this->view->setMet('goodsImageManage');
                    return include $this->view->getView();
                } else {
                    $Yf_Page->totalRows = $goods_rows['totalsize'];
                    $page_nav = $Yf_Page->prompt();
                    include $this->view->getView();
                }
            } else {
                $this->data->addBody('', $goods_rows);
            }
        }
        
        //仓库中的分销商品
        public function offline()
        {
            $met = request_string('met');
            $action = request_string('action');
            $Goods_CommonModel = new Goods_CommonModel();
            $cront_row = array('shop_id' => Perm::$shopId, 'common_parent_id:>' => 0);
            $goods_key = request_string('goods_key');
            if (!empty($goods_key) && isset($goods_key)) {
                $cront_row = array('common_name:like' => '%' . $goods_key . '%');
            }
            
            $Yf_Page = new Yf_Page();
            $row = $Yf_Page->listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $row);
            
            $goods_rows = $Goods_CommonModel->getCommonOffline($cront_row, array('common_id' => 'DESC'), $page, $row);
            $common_id_rows = array_column($goods_rows['items'], 'common_id');
            if (!empty($common_id_rows)) {
                $goods_detail_rows = $Goods_CommonModel->getGoodsDetailRows($common_id_rows);
            }
            $goods = $Goods_CommonModel->getRecommonRow($goods_rows);
            
            foreach ($goods as $key => $goods_data) {
                if (is_array($goods_data['goods_id'])) {
                    $goods_row = current($goods_data['goods_id']);
                    $goods[$key]['goods_id'] = isset($goods_row['goods_id']) ? $goods_row['goods_id'] : 0;
                }
            }
            
            if ('e' == $this->typ) {
                if ($action == 'edit_goods') {
                    return $this->editGoods();
                } else {
                    $Yf_Page->totalRows = $goods_rows['totalsize'];
                    $page_nav = $Yf_Page->prompt();
                    
                    $this->view->setMet('online');
                    include $this->view->getView();
                }
            } else {
                $this->data->addBody('', $goods_rows);
            }
        }
        
        //等待审核的商品
        public function verify()
        {
            $met = request_string('met');
            $action = request_string('action');
            $Goods_CommonModel = new Goods_CommonModel();
            
            $cront_row = array('shop_id' => Perm::$shopId, 'common_parent_id:>' => 0);
            
            $goods_key = request_string('goods_key');
            if (!empty($goods_key) && isset($goods_key)) {
                $cront_row = array('common_name:like' => '%' . $goods_key . '%');
            }
            
            $Yf_Page = new Yf_Page();
            $row = $Yf_Page->listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $row);
            
            $goods_rows = $Goods_CommonModel->getCommonVerifyWaiting($cront_row, array('common_id' => 'DESC'), $page, $row);
            $common_id_rows = array_column($goods_rows['items'], 'common_id');
            if (!empty($common_id_rows)) {
                $goods_detail_rows = $Goods_CommonModel->getGoodsDetailRows($common_id_rows);
            }
            $goods = $Goods_CommonModel->getRecommonRow($goods_rows);
            
            if ('e' == $this->typ) {
                if ($action == 'edit_goods') {
                    return $this->editGoods();
                } else {
                    $Yf_Page->totalRows = $goods_rows['totalsize'];
                    $page_nav = $Yf_Page->prompt();
                    
                    $this->view->setMet('online');
                    include $this->view->getView();
                }
            } else {
                $this->data->addBody('', $goods_rows);
            }
        }
    }
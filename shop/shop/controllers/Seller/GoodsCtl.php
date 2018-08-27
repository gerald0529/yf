 <?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    /**
     * @author     Yf <service@yuanfeng.cn>
     */
    class Seller_GoodsCtl extends Seller_Controller
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
            $this -> shopBaseModel = new Shop_BaseModel();
            $this -> goodsTypeModel = new Goods_TypeModel();
            $this -> goodsCatModel = new Goods_CatModel();
            $this -> goodsBrandModel = new Goods_BrandModel();
            $this -> goodsSpecModel = new Goods_SpecModel();
            $this -> goodsPropertyValueModel = new Goods_PropertyValueModel();
            $this -> goodsSpecValueModel = new Goods_SpecValueModel();
            $this -> goodsCommonModel = new Goods_CommonModel();
            $this -> goodsBaseModel = new Goods_BaseModel();
            $this -> goodsCommonDetailModel = new Goods_CommonDetailModel();
            $this -> shopGoodsCat = new Shop_GoodsCat();
        }
        
        /**
         * 首页
         *
         * @access public
         */
        public function add()
        {
            $cat_id = request_int('cat_id');
            $action = request_string('action');
            $common_id = request_int('common_id');
            $shop_id = Perm::$shopId;
            if ($cat_id) {
                if (empty($common_id)) {
                    //获取运费模板信息
                    $template_model = new Transport_TemplateModel();
                    $transport_template = $template_model -> getOpenTemplate($shop_id);
                    $shop_model = new Shop_BaseModel();
                    $shop_info = $shop_model -> getOne($shop_id);
                    $data = $this -> goodsTypeModel -> getTypeInfoByPublishGoods($cat_id);
                    $this -> view -> setMet('goodsInfoManage');
                } else {
                    return $this -> editGoods();
                }
            } elseif (!empty($action) && $action == 'goodsImageManage') {
                $data = $this -> goodsImageManage($common_id);
                $common_data = $data['common_data'];
                $common_image = $common_data['common_image'];
                if (!empty($data['color'])) {
                    $color = $data['color'];
                }
                $this -> view -> setMet('goodsImageManage');
            } else {
                $Goods_CatModel = new Goods_CatModel();
                $cat_rows = $Goods_CatModel -> getCatTreeData(0, false, 0, true);
            }
            include $this -> view -> getView();
        }
        
        public function editGoods()
        {
            $common_id = request_int('common_id');
            $common_data = $this -> goodsCommonModel -> listByWhere(array('shop_id' => Perm::$shopId, 'common_id' => $common_id));
            if (empty($common_data)) {
                return;
            }
            $common_data = pos($common_data['items']);
            $common_detail_data = $this -> goodsCommonDetailModel -> getCommonDetail($common_data['common_id']);
            $common_detail_data = pos($common_detail_data);
            $common_sell_time_d = strtotime($common_data['common_sell_time']);
            if ($common_sell_time_d && $common_sell_time_d > 0) {
                //读取上架时间
                $common_sell_time[0] = date('Y-m-d', $common_sell_time_d);
                $common_sell_time[1] = date('H', $common_sell_time_d);
                $common_sell_time[2] = date('i', $common_sell_time_d);
                $common_data['common_sell_time'] = $common_sell_time;
            } else {
                unset($common_data['common_sell_time']);
            }
            $cat_id = $common_data['cat_id'];
            $cat_base = $this -> goodsCatModel -> getCat($cat_id);
            if (empty($cat_base)) {
                include $this -> view -> getTplPath() . '/' . 'error.php';
            } else {
                //判断是否修改商品分类
                $action = request_string('action');
                if (!empty($action) && $action == 'edit_goods_cat') {
                    $cat_id = request_int('cat_id');
                }
                $shop_id = Perm::$shopId;
                if ($common_data['supply_shop_id']) {
                    $shop_id = $common_data['supply_shop_id'];
                    //如果是代发货，使用代发货的商家
                    $supplier_common_info = $this -> goodsCommonModel -> getOne($common_data['common_parent_id']);
                    $common_data['transport_area_id'] = $common_data['product_is_behalf_delivery'] == 1 && $common_data['common_parent_id'] ? $supplier_common_info['transport_area_id'] : $common_data['transport_area_id'];
                    $common_data['common_limit'] = $supplier_common_info['common_limit'];
                }
                $data = $this -> goodsTypeModel -> getTypeInfoByPublishGoods($cat_id, $shop_id); //商品属性、规格等
                $goods_base_data = $this -> goodsBaseModel -> getByWhere(array('common_id' => $common_data['common_id'])); //取出商品规格值
                /**********构造spec数据S**********/
                /**
                 *  [
                 *      [
                 *          spec_id=> spec_id,
                 *          spec_name=> spec_name,
                 *          spec_values=> [
                 *                          spec_value_id=> spec_value_id,
                 *                          spec_value_name=> spec_value_name,
                 *                          id=> spec_value_id
                 *                        ]
                 *      ]
                 * ]
                 */
                if ($common_data['supply_shop_id']) {
                    $spec_names = $common_data['common_spec_name'];
                    $spec_values = $common_data['common_spec_value'];
                    $spec = [];
                    if (is_array($spec_names)){
                        foreach ($spec_names as $spec_id => $spec_name) {
                            foreach ($spec_values as $spec_value_spec_id => $spec_value) {
                                if ($spec_id == $spec_value_spec_id) {
                                    $arr = [
                                        'spec_id' => $spec_id,
                                        'spec_name' => $spec_name,
                                        'spec_values' => []
                                    ];
                                    foreach ($spec_value as $spec_value_id => $spec_value_name) {
                                        array_push($arr['spec_values'], [
                                            'spec_value_id' => $spec_value_id,
                                            'spec_value_name' => $spec_value_name,
                                            'id' => $spec_value_id
                                        ]);
                                    }
                                    array_push($spec, $arr);
                                }
                            }
                        }
                    }
                    $data['spec'] = $spec;
                }
                //获取运费模板信息
                $template_model = new Transport_TemplateModel();
                $transport_template = $template_model -> getOpenTemplate($shop_id);
                //获取售卖区域信息
                if ($common_data['transport_area_id']) {
                    $area_model = new Transport_AreaModel();
                    $transport_area = $area_model -> getOne($common_data['transport_area_id']);
                    $common_data['transport_area_name'] = $transport_area['name'];
                } else {
                    $common_data['transport_area_name'] = __('未设置');
                }
                $shop_model = new Shop_BaseModel();
                $shop_info = $shop_model -> getOne($shop_id);
                $this -> view -> setMet('goodsInfoManage');
                include $this -> view -> getView();
            }
        }
        
        public function goodsImageManage($common_id)
        {
            $data = array();
            $common_data = $this -> goodsCommonModel -> getCommon($common_id);
            $common_data = pos($common_data);
            $data['common_data'] = $common_data;
            $readonly_data = $this -> goodsSpecModel -> getByWhere(array('spec_readonly' => 1));
            $readonly_data = pos($readonly_data);
            //取出颜色
            $Goods_ImagesModel = new Goods_ImagesModel();
            $goods_images = $Goods_ImagesModel -> getByWhere(array('common_id' => $common_id));
            //如果是分销商品而且没有设置过商品图片，则默认显示分销商品的图片
            if ($common_data['supply_shop_id'] > 0 && $common_data['common_parent_id'] > 0 && !$goods_images) {
                $goods_images = $Goods_ImagesModel -> getByWhere(array('common_id' => $common_data['common_parent_id']));
            }
            if (!empty($common_data['common_spec_value'])) {
                //spec_id = 1 => 是系统默认只读属性: 颜色
                if (!empty($common_data['common_spec_value'][$readonly_data['spec_id']])) {
                    $color = $common_data['common_spec_value'][$readonly_data['spec_id']];
                    $data['color'] = $color;
                    foreach ($color as $key => $val) {
                        foreach ($goods_images as $k => $v) {
                            if ($key == $v['images_color_id']) {
                                $color_images[$key][] = $v;
                                unset($goods_images[$k]);
                            }
                        }
                    }
                }
            }
            if (empty($color_images)) {
                foreach ($goods_images as $key => $val) {
                    if ($val['images_is_default'] == Goods_ImagesModel::IMAGE_DEFAULT) {
                        $image_default = $goods_images[$key];
                        unset($goods_images[$key]);
                        array_unshift($goods_images, $image_default);
                        break;
                    }
                }
                $data['goods_images'] = array_values($goods_images);
            } else {
                foreach ($color_images as $key => $val) {
                    foreach ($val as $k => $v) {
                        if ($v['images_is_default'] == Goods_ImagesModel::IMAGE_DEFAULT) {
                            $image_default = $color_images[$key][$k];
                            unset($color_images[$key][$k]);
                            array_unshift($color_images[$key], $image_default);
                            break;
                        }
                    }
                }
                $data['color_images'] = $color_images;
            }
            return $data;
        }
        
        //违规的商品
        public function online()
        {
            $met = request_string('met');
            $action = request_string('action');
            $goods_key = request_string('goods_key');
            $Goods_CommonModel = new Goods_CommonModel();
            $cront_row = array('shop_id' => Perm::$shopId, 'common_state' => Goods_CommonModel::GOODS_STATE_NORMAL, 'common_verify' => Goods_CommonModel::GOODS_VERIFY_ALLOW);
            $cront_row['is_del'] = Goods_BaseModel::IS_DEL_NO;
            if (!empty($goods_key) && isset($goods_key)) {
                $cront_row['common_name:like'] = '%' . $goods_key . '%';
            }
            $Yf_Page = new Yf_Page();
            $row = $Yf_Page -> listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $row);
            $goods_rows = $Goods_CommonModel -> getCommonNormal($cront_row, array('common_id' => 'DESC'), $page, $row);
            $common_id_rows = array_column($goods_rows['items'], 'common_id');
            if (!empty($common_id_rows)) {
                $goods_detail_rows = $Goods_CommonModel -> getGoodsDetailRows($common_id_rows);
            }
            $goods = $Goods_CommonModel -> getRecommonRow($goods_rows);
            if ('e' == $this -> typ) {
                if ($action == 'edit_goods') {
                    return $this -> editGoods();
                } elseif ($action == 'edit_image') {
                    $common_id = request_int('common_id');
                    $data = $this -> goodsImageManage($common_id);
                    if (!empty($data['color'])) {
                        $color = $data['color'];
                    }
                    $common_data = $data['common_data'];
                    $common_image = $common_data['common_image'];
                    $this -> view -> setMet('goodsImageManage');
                    return include $this -> view -> getView();
                } else {
                    $Yf_Page -> totalRows = $goods_rows['totalsize'];
                    $page_nav = $Yf_Page -> prompt();
                    $Shop_BaseModel = new Shop_BaseModel();
                    $shop_info = $Shop_BaseModel -> getOne(Perm::$shopId);
                    $Shop_GradeModel = new Shop_GradeModel();
                    $shop_GradeInfo = $Shop_GradeModel -> getOne($shop_info['shop_grade_id']);
                    //过滤过期的虚拟商品
                    foreach ($goods as $k => $v) {
                        if ($v['common_is_virtual'] == 1 && $v['common_virtual_date'] < date('Y-m-d', time())) {
                            unset($goods[$k]);
                        }
                    }
                    include $this -> view -> getView();
                }
            } else {
                $this -> data -> addBody('', $goods_rows);
            }
        }
        
        //待审核的商品
        public function offline()
        {
            $ctl = request_string('ctl');
            $met = request_string('met');
            $action = request_string('action');
            $Goods_CommonModel = new Goods_CommonModel();
            
            $cront_row['shop_id'] = Perm::$shopId;
            $cront_row['common_state'] = Goods_CommonModel::GOODS_STATE_OFFLINE;
            $goods_key = request_string('goods_key');
            if (!empty($goods_key) && isset($goods_key)) {
                $cront_row['common_name:like'] = '%' . $goods_key . '%';
            }
            $Yf_Page = new Yf_Page();
            $row = $Yf_Page -> listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $row);
            $goods_rows = $Goods_CommonModel -> getCommonOffline($cront_row, array('common_id' => 'DESC'), $page, $row);
            $common_id_rows = array_column($goods_rows['items'], 'common_id');
            if (!empty($common_id_rows)) {
                $goods_detail_rows = $Goods_CommonModel -> getGoodsDetailRows($common_id_rows);
            }
            $goods = $Goods_CommonModel -> getRecommonRow($goods_rows);
            foreach ($goods as $key => $goods_data) {
                if (is_array($goods_data['goods_id'])) {
                    $goods_row = current($goods_data['goods_id']);
                    $goods[$key]['goods_id'] = isset($goods_row['goods_id']) ? $goods_row['goods_id'] : 0;
                }
                if ($goods_data['common_parent_id']) {
                    $parent_goods = $Goods_CommonModel -> getOne($goods_data['common_parent_id']);
                    if ($parent_goods['common_state'] == 0) {
                        //供应商商品已经下架--禁止分销商上架商品
                        $goods[$key]['disabled_up'] = 1;
                    }
                }
                //过滤已经过期的虚拟商品和下架商品
                if ($goods_data['common_is_virtual'] == Goods_CommonModel::GOODS_NORMAL && $goods_data['common_state'] == Goods_CommonModel::GOODS_STATE_NORMAL) {
                    unset($goods[$key]);
                } else {
                    if ($goods_data['common_virtual_date'] > date('Y-m-d', time()) && $goods_data['common_state'] == Goods_CommonModel::GOODS_STATE_NORMAL) {
                        unset($goods[$key]);
                    }
                }
                //排除待发布商品
                // if ($goods_data['common_verify'] == 1 && strtotime($goods_data['common_sell_time']) > 0) {
                //     unset($goods[$key]);
                // }
            }
            if ('e' == $this -> typ) {
                if ($action == 'edit_goods') {
                    return $this -> editGoods();
                } else {
                    $Yf_Page -> totalRows = $goods_rows['totalsize'];
                    $page_nav = $Yf_Page -> prompt();
                    $shopModel = new Shop_BaseModel;
                    $shop = $shopModel -> getOne(Perm::$shopId);
                    $this -> view -> setMet('online');
                    include $this -> view -> getView();
                }
            } else {
                $this -> data -> addBody('', $goods_rows);
            }
        }
        
        //未通过审核的商品
        public function lockup()
        {
            $ctl = request_string('ctl');
            $met = request_string('met');
            $action = request_string('action');
            $Goods_CommonModel = new Goods_CommonModel();
            $cront_row = array('shop_id' => Perm::$shopId);
            $goods_key = request_string('goods_key');
            if (!empty($goods_key) && isset($goods_key)) {
                $cront_row = array('common_name:like' => '%' . $goods_key . '%');
            }
            $Yf_Page = new Yf_Page();
            $row = $Yf_Page -> listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $row);
            $goods_rows = $Goods_CommonModel -> getCommonIllegal($cront_row, array('common_id' => 'DESC'), $page, $row);
            $common_id_rows = array_column($goods_rows['items'], 'common_id');
            if (!empty($common_id_rows)) {
                $goods_detail_rows = $Goods_CommonModel -> getGoodsDetailRows($common_id_rows);
            }
            $goods = $Goods_CommonModel -> getRecommonRow($goods_rows);
            if ('e' == $this -> typ) {
                if ($action == 'edit_goods') {
                    return $this -> editGoods();
                } else {
                    $Yf_Page -> totalRows = $goods_rows['totalsize'];
                    $page_nav = $Yf_Page -> prompt();
                    $no_shangjia = true; //不需要展示上架功能
                    $this -> view -> setMet('online');
                    include $this -> view -> getView();
                }
            } else {
                $this -> data -> addBody('', $goods_rows);
            }
        }
        
        //淘宝导入的商品
        public function verify()
        {
            $met = request_string('met');
            $action = request_string('action');
            $Goods_CommonModel = new Goods_CommonModel();
            $cront_row = array('shop_id' => Perm::$shopId);
            $goods_key = request_string('goods_key');
            if (!empty($goods_key) && isset($goods_key)) {
                $cront_row = array('common_name:like' => '%' . $goods_key . '%');
            }
            $Yf_Page = new Yf_Page();
            $row = $Yf_Page -> listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $row);
            $goods_rows = $Goods_CommonModel -> getCommonVerifyWaiting($cront_row, array('common_id' => 'DESC'), $page, $row);
            $common_id_rows = array_column($goods_rows['items'], 'common_id');
            if (!empty($common_id_rows)) {
                $goods_detail_rows = $Goods_CommonModel -> getGoodsDetailRows($common_id_rows);
            }
            $goods = $Goods_CommonModel -> getRecommonRow($goods_rows);
            if ('e' == $this -> typ) {
                if ($action == 'edit_goods') {
                    return $this -> editGoods();
                } else {
                    $Yf_Page -> totalRows = $goods_rows['totalsize'];
                    $page_nav = $Yf_Page -> prompt();
                    $no_shangjia = true; //不需要展示上架功能
                    $this -> view -> setMet('online');
                    include $this -> view -> getView();
                }
            } else {
                $this -> data -> addBody('', $goods_rows);
            }
        }
        
        public function verifyDeny()
        {
            $met = request_string('met');
            $action = request_string('action');
            $Goods_CommonModel = new Goods_CommonModel();
            $cront_row = array('shop_id' => Perm::$shopId);
            $goods_key = request_string('goods_key');
            if (!empty($goods_key) && isset($goods_key)) {
                $cront_row = array('common_name:like' => '%' . $goods_key . '%');
            }
            $Yf_Page = new Yf_Page();
            $row = $Yf_Page -> listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $row);
            $goods_rows = $Goods_CommonModel -> getCommonVerifyDeny($cront_row, array('common_id' => 'DESC'), $page, $row);
            $common_id_rows = array_column($goods_rows['items'], 'common_id');
            if (!empty($common_id_rows)) {
                $goods_detail_rows = $Goods_CommonModel -> getGoodsDetailRows($common_id_rows);
            }
            $goods = $Goods_CommonModel -> getRecommonRow($goods_rows);
            if ('e' == $this -> typ) {
                if ($action == 'edit_goods') {
                    return $this -> editGoods();
                } else {
                    $Yf_Page -> totalRows = $goods_rows['totalsize'];
                    $page_nav = $Yf_Page -> prompt();
                    $no_shangjia = true; //不需要展示上架功能
                    $this -> view -> setMet('online');
                    include $this -> view -> getView();
                }
            } else {
                $this -> data -> addBody('', $goods_rows);
            }
        }
        
        public function OutsideImport()
        {
            $met = request_string('met');
            $action = request_string('action');
            $Goods_CommonModel = new Goods_CommonModel();
            $cront_row = array('shop_id' => Perm::$shopId);
            $goods_key = request_string('goods_key');
            if (!empty($goods_key) && isset($goods_key)) {
                $cront_row = array('common_name:like' => '%' . $goods_key . '%');
            }
            $Yf_Page = new Yf_Page();
            $row = $Yf_Page -> listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $row);
            $goods_rows = $Goods_CommonModel -> getCommonOutsideImport($cront_row, array('common_id' => 'DESC'), $page, $row);
            $common_id_rows = array_column($goods_rows['items'], 'common_id');
            if (!empty($common_id_rows)) {
                $goods_detail_rows = $Goods_CommonModel -> getGoodsDetailRows($common_id_rows);
            }
            $goods = $Goods_CommonModel -> getRecommonRow($goods_rows);
            if ('e' == $this -> typ) {
                if ($action == 'edit_goods') {
                    return $this -> editGoods();
                } else {
                    $Yf_Page -> totalRows = $goods_rows['totalsize'];
                    $page_nav = $Yf_Page -> prompt();
                    $this -> view -> setMet('online');
                    include $this -> view -> getView();
                }
            } else {
                $this -> data -> addBody('', $goods_rows);
            }
        }
        
        public function appointment()
        {
            include $this -> view -> getView();
        }
        
        /*
         * 上传商品图片 -- 第三步
         * */
        public function addOrEditShopGoods()
        {
            $common_id = request_int('common_id'); //区分是修改商品，还是添加商品
            $spec = request_row('spec');
            $price = request_int('price');
            if (!$this -> isEditGoods($common_id, $price, $spec)) {
                throw new Exception('活动商品不允许修改商品价格');
            }
            $Goods_CommonModel = new Goods_CommonModel();
            //商家货号唯一
            $common_code = request_string('code');
            if ($common_code) {
                $common_code_list = $Goods_CommonModel -> getByWhere(array('common_code' => $common_code));
                if (count($common_code_list) > 1) {
                    throw new Exception('商家货号不能重复');
                }
            }
            $action = request_string('action');//echo '<pre>';print_r(Yf_Registry::get('url'));exit;
            $shop_base = $this -> shopBaseModel -> getBase(Perm::$shopId);
            $shop_base = current($shop_base);
            $common_data['shop_status'] = $shop_base['shop_status'];  //插入店铺状态
            $goods_cat_base = $this -> goodsCatModel -> getCat(request_int('cat_id'));
            $goods_cat_base = current($goods_cat_base);
            $shop_cat_id = ',';
            /*$shop_goods_cat_base = $this->shopGoodsCat->getByWhere(array('shop_id' => $shop_base['shop_id']));*/
            $sgcate_id = request_row('sgcate_id');
            if (empty($sgcate_id)) {
                $shop_cat_id .= ',';
            } else {
                foreach ($sgcate_id as $key => $val) {
                    $shop_cat_id .= $val . ',';
                }
            }
            $matche_row = array();
            //有违禁词
            if (Text_Filter::checkBanned(request_string('name'), $matche_row) || Text_Filter::checkBanned(request_string('promotion_tips'), $matche_row) || Text_Filter::checkBanned(request_string('body'), $matche_row)) {
                return $this -> data -> addBody(-140, array(), __('含有违禁词'), 250);
            }
            //当前商品数量统计, 非自营店铺
            if ($shop_base['shop_self_support'] != Shop_BaseModel::SELF_SUPPORT_TRUE) {
                $shop = array();
                $shop['shop_id'] = $shop_base['shop_id'];
                $shop_base_row = $this -> shopBaseModel -> getBaseOneList($shop);
                $goods_state_normal_num = $Goods_CommonModel -> getCommonStateNum($shop_base['shop_id'], -1);
                if (!empty($shop_base_row['shop_grade_row'])) {
                    $shop_grade_goods_limit = $shop_base_row['shop_grade_row']['shop_grade_goods_limit'];
                    $shop_grade_album_limit = $shop_base_row['shop_grade_row']['shop_grade_album_limit'];
                } else {
                    $shop_grade_goods_limit = 0;
                    $shop_grade_album_limit = 0;
                }
                if (0 != $shop_grade_goods_limit && $shop_grade_goods_limit <= $goods_state_normal_num) {
                    return $this -> data -> addBody(-140, array(), __('商品发布数量超出平台限制！'), 250);
                }
            }
            $Goods_BrandModel = new Goods_BrandModel();
            $Upload_BaseModel = new Upload_BaseModel();
            $shop_album_num = $Upload_BaseModel -> getUploadNum($shop['shop_id']);
            $common_data['shop_id'] = $shop_base['shop_id'];                        //店铺id
            $common_data['shop_name'] = $shop_base['shop_name'];                        //店铺名称
            $common_data['shop_cat_id'] = $shop_cat_id;                                //店铺分类id
            $common_data['type_id'] = $goods_cat_base['type_id'];                    //类型id
            $common_data['shop_self_support'] = $shop_base['shop_self_support'] == Shop_BaseModel::SELF_SUPPORT_TRUE ? 1 : 0;     //是否自营
            $common_data['cat_id'] = request_string('cat_id');                    //商品分类id
            $common_data['cat_name'] = request_string('cat_name');                    //商品分类
            $common_data['common_name'] = Text_Filter::filterWords(request_string('name'));                        //商品名称
            $common_data['brand_id'] = request_int('brand_id');                        //品牌id
            if (request_int('brand_id')) {
                $goods_brand = $Goods_BrandModel -> getOne($common_data['brand_id']);
                $common_data['brand_name'] = $goods_brand['brand_name'];                    //品牌名称
            }
            $common_data['common_promotion_tips'] = Text_Filter::filterWords(request_string('promotion_tips'));                //商品广告词
            $common_data['common_image'] = request_string('imagePath');                    //商品主图
            $common_data['common_video'] = request_string('videoPath');                       //商品视频
            $common_data['common_price'] = request_float('price');                        //商品价格
            $common_data['common_market_price'] = request_float('market_price');                //市场价
            $common_data['common_cost_price'] = request_float('cost_price');                    //成本价
            if ($common_data['common_price'] > $common_data['common_market_price']) {
                $msg = $shop_base['shop_type'] == 2 ? __('市场价不能低于供货价格') : __('市场价不能低于商品价格');
                return $this -> data -> addBody(-140, array(), $msg, 250);
            }
            $common_data['common_stock'] = request_int('stock');                            //商品库存
            $common_data['common_alarm'] = request_int('alarm');                            //库存预警值
            $common_data['common_code'] = $common_code;                                     //商家编号
            $common_data['common_formatid_top'] = request_int('formatid_top');                    //顶部关联板式
            $common_data['common_formatid_bottom'] = request_int('formatid_bottom');                //底部关联板式
            $common_data['common_cubage'] = request_float('cubage');                        //商品重量
            $common_data['common_is_return'] = request_int('is_return');                        //7天无理由退货
            if (request_string('service')) {
                $common_data['common_service'] = request_string('service');                    //售后服务
            } else {
                $common_data['common_service'] = $shop_base['shop_common_service'];
            }
            $common_data['common_packing_list'] = request_string('packing_list');                //包装清单
            $common_data['common_state'] = request_int('state');                            //
            $common_data['common_is_recommend'] = request_string('is_recommend');                //商品推荐
            $common_data['common_add_time'] = date('Y-m-d H:i:s', time());                    //商品添加时间
            //设置地区
            $common_data['district_id'] = $shop_base['district_id'];
            $is_limit = request_int('is_limit');
            //0不限购
            $goods_limit = $is_limit ? request_int('limit') : 0;
            $common_data['common_limit'] = $goods_limit;
            $common_data['common_invoices'] = 1;                    //商品默认支持开发票
            //读取店铺关联的消费者保障服务
            $this -> goodsCommonModel -> getShopContract($common_data);
            $common_property = request_row('property');
            $spec_name = request_string('spec_name');
            $province_id = request_string('province_id');
            $city_id = request_string('city_id');
            if ($province_id) {
                $common_location = array();
                $common_location[] = $province_id;
                if ($city_id) {
                    $common_location[] = $city_id;
                }
                $common_data['common_location'] = $common_location;                                //商品所在地
            }
            //选择售卖区域
            $transport_area_id = request_int('transport_area_id');
            if ($transport_area_id) {
                $area_model = new Transport_AreaModel();
                $check_area = $area_model -> checkArea($transport_area_id, Perm::$shopId);
                if ($check_area) {
                    return $this -> data -> addBody(-140, array(), __('售卖区域数据有误'), 250);
                }
            } else {
                return $this -> data -> addBody(-140, array(), __('请设置售卖区域'), 250);
            }
            $common_data['transport_area_id'] = $transport_area_id;
            //获取运费模板信息
            $shop_id = Perm::$shopId;
            $template_model = new Transport_TemplateModel();
            $transport_template = $template_model -> getOpenTemplate($shop_id);
            if (!$transport_template) {
                return $this -> data -> addBody(-140, array(), __('请设置运费模板'), 250);
            }
            //本店分类
            $sgcate_id = request_row('sgcate_id');
            if (!empty($sgcate_id)) {
                $common_data['shop_goods_cat_id'] = $sgcate_id;                                //shop_goods_cat_id
            }
            /* 只有可发布虚拟商品才会显示 S */
            $is_gv = request_int('is_gv');
            $common_data['common_is_virtual'] = $is_gv;                                            //虚拟商品
            if ($is_gv == 1) {
                $common_data['common_virtual_date'] = request_string('g_vindate');                        //虚拟商品有效期
                $common_data['common_virtual_refund'] = request_int('g_vinvalidrefund');                    //支持过期退款
            }
            if (!empty($common_property)) {
                $common_data['common_property'] = $common_property;                    //属性
            }
            if ($common_data['common_state'] == Goods_CommonModel::GOODS_STATE_TIMING) {
                $starttime = request_string('starttime');
                $time_hour = request_string('hour');
                $time_minute = request_string('minute');
                $sell_time = "$starttime $time_hour:$time_minute:00";
                if (strtotime($sell_time) < time()) {
                    return $this -> data -> addBody(-140, array(), __('发布时间不能小于当前时间'), 250);
                }
                $common_data['common_sell_time'] = date('Y-m-d H:i:s', strtotime($sell_time));            //上架时间
            } elseif ($common_data['common_state'] == Goods_CommonModel::GOODS_STATE_NORMAL) {
                //如果是立即发布，则发布时间为当前添加时间
                $common_data['common_sell_time'] = $common_data['common_add_time'];
            }
            $spec_val = request_row('spec_val');
            if (!empty($spec_val)) {
                $diff_spec_array = array_diff_key($spec_name, $spec_val);
                $flag_spec = empty($diff_spec_array);
                if (!empty($spec_name) && $flag_spec) {
                    $common_data['common_spec_name'] = $spec_name;                        //规格名称
                    $common_data['common_spec_value'] = $spec_val;                        //规格名称
                }
            }
            //判断发布的的商品是否需要审核
            if (in_array($common_data['common_state'], [Goods_CommonModel::GOODS_STATE_NORMAL, Goods_CommonModel::GOODS_STATE_TIMING, Goods_CommonModel::GOODS_STATE_OFFLINE])) { //正常发布、定时上架、放入仓库
                if (Web_ConfigModel::value('goods_verify_flag') == 0) { //商品是否需要审核
                    if ($common_data['common_state'] == Goods_CommonModel::GOODS_STATE_OFFLINE) {  //如果是放入仓库
                        $common_data['common_verify'] = Goods_CommonModel::GOODS_VERIFY_DENY;    //审核未通过
                    } else {
                        $common_data['common_verify'] = Goods_CommonModel::GOODS_VERIFY_ALLOW;
                    }
                    //如果是外部导入商品且不需要审核，则将商品状态改为待上架
                    if ($common_data['common_goods_from'] == Goods_CommonModel::GOODS_FROM_OUTSIDEIMPORT) {
                        $common_data['common_state'] = Goods_CommonModel::GOODS_STATE_WAITING_ADD;
                    }
                } else {
                    $common_data['common_verify'] = Goods_CommonModel::GOODS_VERIFY_WAITING;
                }
            }
            //供应商店铺
            if ($shop_base['shop_type'] == 2) {
                $common_data['product_is_allow_update'] = request_int('product_is_allow_update'); //是否允许修改内容
                $common_data['product_is_allow_price'] = request_int('product_is_allow_price');   //是否允许商品价格
                $common_data['product_is_behalf_delivery'] = request_int('product_is_behalf_delivery'); //是否支持待发货
                $common_data['product_distributor_flag'] = 1; //供应商店铺商品
                $common_data['goods_recommended_min_price'] = request_float('goods_recommended_min_price');
                $common_data['goods_recommended_max_price'] = request_float('goods_recommended_max_price');
                if ($common_data['goods_recommended_max_price'] < $common_data['goods_recommended_min_price']) {
                    return $this -> data -> addBody(-140, array(), __('最高零售价格不能低于最低零售价格'), 250);
                }
                $common_data['common_distributor_description'] = request_string('common_distributor_description');
            }
            //关联版式
            $formatid_top = request_int('formatid_top');
            $format_bottom = request_int('formatid_bottom');
            if (!empty($formatid_top)) {
                $common_data['common_formatid_top'] = $formatid_top;
            }
            if (!empty($format_bottom)) {
                $common_data['common_formatid_bottom'] = $format_bottom;
            }
            $this -> goodsCommonModel -> sql -> startTransactionDb();
            if ($common_property) {
                $property_index = new Goods_PropertyIndexModel();
                foreach ($common_property as $k => $v) {
                    $property_row = array(
                        'common_id' => $common_id,
                        'property_id' => $v[3],
                    );
                    $property = $property_index -> getOneByWhere($property_row);
                    $property_row['property_value_id'] = $v[1];
                    if (!$property) {
                        $flag = $property_index -> addPropertyIndex($property_row);
                    } else {
                        $flag = $property_index -> editPropertyIndex($property['goods_property_index_id'], $property_row);
                    }
                }
            }
            if ($action == 'edit') {
                //分销商分销商品商品修改权限
                $common_base = $this -> goodsCommonModel -> getOne($common_id);
                //判断商品是否修改限购
                if ($common_base['common_limit'] != $goods_limit) {
                    $common_data['common_edit_time'] = date('Y-m-d H:i:s', time());
                }
                if ($shop_base['shop_type'] == 1 && $common_base['product_is_allow_update'] == 0 && $common_base['product_is_allow_price'] == 1) {
                    $dist_allow_edit = array();
                    $dist_allow_edit['common_price'] = $common_data['common_price'];
                    $dist_allow_edit['common_market_price'] = $common_data['common_market_price'];
                    $edit_status = $this -> goodsCommonModel -> editCommon($common_id, $dist_allow_edit);
                } else {
                    // var_dump($common_id);die;
                    if ($shop_base['shop_type'] == 1 && $common_base['product_is_allow_update'] == 1 && $common_base['product_is_allow_price'] == 0) {
                        unset($common_data['common_price']);
                        unset($common_data['common_market_price']);
                    } elseif ($shop_base['shop_type'] == 1 && $common_base['product_is_allow_update'] == 0 && $common_base['product_is_allow_price'] == 0) {
                        $common_data = array();
                    }
                    $edit_status = $this -> goodsCommonModel -> editCommon($common_id, $common_data);
                    //获取供应商商品对应的店家商品common_id,修改库存
                    foreach ($commons as $common) {
                        if($common['product_is_behalf_delivery'] == 1)
                        {
                            $common_cond['common_stock'] = $common_data['common_stock'];
                            $edit_falg = $this->goodsCommonModel->editCommon($common['common_id'], $common_cond);
                        }
                    }
                }
                $data['action'] = 'edit';
                $data['property'] = request_row('property');
            } else {
                $common_data['common_edit_time'] = date('Y-m-d H:i:s', time());
                $common_id = $this -> goodsCommonModel -> addCommon($common_data, true);
            }
            if ($common_id && $this -> goodsCommonModel -> sql -> commitDb()) {
                //将用户发布或者修改的商品同步到im中
                $key = Yf_Registry::get('im_api_key');
                $url = Yf_Registry::get('im_api_url');
                $im_app_id = Yf_Registry::get('im_app_id');
                $formvars = array();
                $formvars['goods_common_id'] = $common_id;
                $formvars['app_id'] = $im_app_id;
                $formvars['user_id'] = $shop_base['user_id'];
                $formvars['goods_name'] = $common_data['common_name'];
                $formvars['goods_price'] = $common_data['common_price'];
                $formvars['goods_pic'] = $common_data['common_image'];
                $formvars['goods_url'] = Yf_Registry::get('shop_wap_url') . '/tmpl/product_detail.html?cid=' . $common_id;
                $formvars['goods_status'] = $common_data['common_state'];
                $formvars['goods_verify'] = $common_data['common_verify'];
                $formvars['time'] = get_date_time();
                fb($formvars);
                $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Goods&met=addOrEditUserGoods&typ=json', $url), $formvars);
                $body = Text_Filter::filterWords(request_string('body'));
                $spec_data = request_row('spec');
//          echo '<pre>';print_r($spec_data);exit;
                //内容详情
                if (true || !empty($body)) {
                    $common_detail_data['common_id'] = $common_id;
                    $common_detail_data['common_body'] = $body;
                    if ($action == 'edit') {
                        unset($common_detail_data['common_id']);
                        if ($this -> goodsCommonDetailModel -> getOne($common_id)) {
                            $this -> goodsCommonDetailModel -> editCommonDetail($common_id, $common_detail_data);
                        } else {
                            $this -> goodsCommonDetailModel -> addCommonDetail($common_detail_data);
                        }
                    } else {
                        $this -> goodsCommonDetailModel -> addCommonDetail($common_detail_data);
                    }
                }
                //库存配置
                //判断  修改的只修改
                //取出已有的所有goods_id
                $goods_base = $this -> goodsBaseModel -> getByWhere(array('common_id' => $common_id));
                if (!empty($goods_base)) {
                    $goods_base_ids = array_column($goods_base, 'goods_id');
                }
                $goods_data['cat_id'] = $common_data['cat_id'];                    //商品分类id
                $goods_data['common_id'] = $common_id;                                //商品公共表id
                $goods_data['shop_id'] = $common_data['shop_id'];                    //shop_id
                $goods_data['shop_name'] = $common_data['shop_name'];                //shop_name
                $goods_data['goods_name'] = $common_data['common_name'];                //商品名称
                $goods_data['goods_promotion_tips'] = $common_data['common_promotion_tips'];    //促销提示
                $goods_data['goods_is_recommend'] = $common_data['common_is_recommend'];        //商品推荐
                $goods_data['goods_image'] = $common_data['common_image'];                //商品主图
                //加入goods_id 冗余数据
                $goods_ids = array();
                $color_ids = array();
                $edit_goods_ids = array();
                $retain_flag = false;
                $down_flag = false;
                if (!empty($spec_data) && $flag_spec) {
                    //读取颜色规格值
                    $goodsSpecValueModel = new Goods_SpecValueModel();
                    $spec_value_color_ids = $goodsSpecValueModel -> getSpecValueByColor();
                    //判断前台是否有老数据
                    //过滤无用垃圾数据
                    $edit_goods_ids = array_column($spec_data, 'goods_id');
                    //判断有无修改goods_id 如果没有修改goods_id 则要删除之前goods_id 不符合现在标准
                    $edit_goods_ids_string = implode("", $edit_goods_ids);
                    if (empty($edit_goods_ids_string) && $action == 'edit') {
                        $retain_flag = true;
                        $goods_base_ids = array_values($goods_base_ids);
                        $retain_f_goods_id = $goods_base_ids[0];
                        unset($goods_base_ids[0]);
                    }
                    //删除无用垃圾数据
                    $remove_goods_ids = array();
                    foreach ($goods_base_ids as $old_id) {
                        if (!in_array($old_id, $edit_goods_ids)) {
                            $remove_goods_ids[] = $old_id;
                        }
                    }
                    if (!empty($remove_goods_ids)) {
                        $this -> goodsBaseModel -> removeBase($remove_goods_ids);
                    }
                    foreach ($spec_data as $key => $val) {
                        $goods_data['goods_price'] = $val['price'];                            //商品价格
                        $goods_data['goods_market_price'] = $val['market_price'];                        //市场价
                        $goods_data['goods_stock'] = $val['stock'];                            //商品库存
                        $goods_data['goods_alarm'] = $val['alarm'];                            //库存预警值
                        $goods_data['goods_code'] = $val['sku'];                                //商家编号货号
                        $goods_data['goods_max_sale'] = $goods_limit;                   //单人最大购买数量
                        $goods_data['goods_spec'] = array($key => $val['sp_value']);        //商品规格-JSON存储
                        //分销商店铺
                        if ($shop_base['shop_type'] == 1 && $val['goods_recommended_min_price'] && $val['goods_recommended_max_price']) {
                            if ($val['price'] < $val['goods_recommended_min_price']) {
                                $goods_data['goods_price'] = $val['goods_recommended_min_price'];
                            } elseif ($val['price'] > $val['goods_recommended_max_price']) {
                                $goods_data['goods_price'] = $val['goods_recommended_max_price'];
                            }
                        }
                        //供应商店铺
                        if ($shop_base['shop_type'] == 2) {
                            $goods_data['goods_recommended_min_price'] = $val['goods_recommended_min_price']; //最低销售价格
                            $goods_data['goods_recommended_max_price'] = $val['goods_recommended_max_price'];   //最高销售价格
                        }
                        if (!empty($val['color'])) {
                            $goods_data['color_id'] = $val['color'];                                //颜色
                        }
                        //判断是修改数据还是新增数据
                        if (!empty($val['goods_id'])) {
                            $goods_id = $val['goods_id'];
                            //获取原有的base数据信息
                            $old_base = $this -> goodsBaseModel -> getOne($goods_id);
                            if (($goods_data['goods_price'] != $old_base['goods_price']) || ($goods_data['goods_stock'] != $old_base['goods_stock']) || ($goods_data['goods_recommended_min_price'] != $old_base['goods_recommended_min_price']) || ($goods_data['goods_recommended_max_price'] != $old_base['goods_recommended_max_price'])) {
                                //产品价格、产品库存、最低零售价、最高零售价格 是否更改
                                $down_flag = true;
                            }
                            $this -> goodsBaseModel -> editBase($goods_id, $goods_data, false);
                            $edit_ids[] = $goods_id;
                        } else {
                            if ($retain_flag) {
                                //获取原有的base数据信息
                                $old_base = $this -> goodsBaseModel -> getOne($retain_f_goods_id);
                                if (($goods_data['goods_price'] != $old_base['goods_price']) || ($goods_data['goods_stock'] != $old_base['goods_stock']) || ($goods_data['goods_recommended_min_price'] != $old_base['goods_recommended_min_price']) || ($goods_data['goods_recommended_max_price'] != $old_base['goods_recommended_max_price'])) {
                                    //产品价格、产品库存、最低零售价、最高零售价格 是否更改
                                    $down_flag = true;
                                }
                                $goods_id = $this -> goodsBaseModel -> editBase($retain_f_goods_id, $goods_data, false);
                                $retain_flag = false;
                            } else {
                                $goods_id = $this -> goodsBaseModel -> addBase($goods_data, true);
                                $add_ids[] = $goods_id;
                            }
                        }
                        //color_id 冗余数据
                        foreach ($val['sp_value'] as $k => $v) {
                            if (in_array($k, $spec_value_color_ids) && !in_array($k, $color_ids)) {
                                $color_ids[] = $k;
                                $goods_ids[] = array(
                                    'goods_id' => $goods_id,
                                    'color_id' => $k
                                );
                                break;
                            }
                        }
                    }
                } else {
                    $goods_data['goods_price'] = $common_data['common_price'];                //商品价格
                    $goods_data['goods_market_price'] = $common_data['common_market_price'];        //市场价
                    $goods_data['goods_stock'] = $common_data['common_stock'];                //商品库存
                    $goods_data['goods_alarm'] = $common_data['common_alarm'];                //库存预警值
                    $goods_data['goods_code'] = $common_data['common_code'];                //商家编号货号
                    //供应商店铺
                    if ($shop_base['shop_type'] == 2) {
                        $goods_data['goods_recommended_min_price'] = request_float('goods_recommended_min_price'); //最低销售价格
                        $goods_data['goods_recommended_max_price'] = request_float('goods_recommended_max_price');   //最高销售价格
                    }
                    if ($action == 'edit') {
                        $goods_id = pos($goods_base_ids);
                        //获取原有的base数据信息
                        $old_base = $this -> goodsBaseModel -> getOne($goods_id);
                        if (($goods_data['goods_price'] != $old_base['goods_price']) || ($goods_data['goods_stock'] != $old_base['goods_stock']) || ($goods_data['goods_recommended_min_price'] != $old_base['goods_recommended_min_price']) || ($goods_data['goods_recommended_max_price'] != $old_base['goods_recommended_max_price'])) {
                            //产品价格、产品库存、最低零售价、最高零售价格 是否更改
                            $down_flag = true;
                        }
                        $this -> goodsBaseModel -> editBase($goods_id, $goods_data, false);
                        $edit_ids[] = $goods_id;
                    } else {
                        $goods_id = $this -> goodsBaseModel -> addBase($goods_data, true);
                        $add_ids[] = $goods_id;
                    }
                }
                if (empty($goods_ids)) {
                    $goods_ids[] = array(
                        'goods_id' => $goods_id,
                        'color' => 0
                    );
                }
                $edit_common_data['goods_id'] = $goods_ids;
                $test_id = $this -> goodsCommonModel -> editCommon($common_id, $edit_common_data);
                if ($common_base['common_parent_id']) {//如果是分销商更改数据，改变修改完的跳转链接
                    $data['dist_goods'] = 1;
                }
                //供货商规格商品列表
                $supplier_goods_list = $this -> goodsBaseModel -> getByWhere(array('common_id' => $common_id));
                if ($shop_base['shop_type'] == 2) {
                    $MessageModel = new MessageModel();
                    $all_common = $this -> goodsCommonModel -> getByWhere(array('common_parent_id' => $common_id, 'product_is_behalf_delivery' => 1));
                    foreach ($all_common as $key => $value) {
                        $dist_common_base = $this -> goodsCommonModel -> getOne($value['common_id']);
                        $dist_shop_base = $this -> shopBaseModel -> getOne($dist_common_base['shop_id']);
                        //修改商品信息，并下架,重新加载商品规格
                        $dist_common_row = $this -> goodsCommonModel -> SynchronousCommon($common_id, $dist_shop_base, 'edit');
                        //如果商品允许修改内容，只更新部分内容
                        if ($common_base['product_is_allow_update']) {
                            $allow_edit = array();
                            $allow_edit['common_spec_name'] = $dist_common_row['common_spec_name'];
                            $allow_edit['common_spec_value'] = $dist_common_row['common_spec_value'];
                            $allow_edit['common_price'] = $dist_common_row['common_price'];
                            $allow_edit['common_market_price'] = $dist_common_row['common_market_price'];
                            $allow_edit['goods_recommended_min_price'] = $dist_common_row['goods_recommended_min_price'];
                            $allow_edit['goods_recommended_max_price'] = $dist_common_row['goods_recommended_max_price'];
                            $allow_edit['common_cubage'] = $dist_common_row['common_cubage'];
                            $allow_edit['product_is_allow_update'] = $dist_common_row['product_is_allow_update'];
                            $allow_edit['product_is_allow_price'] = $dist_common_row['product_is_allow_price'];
                            $dist_common_row = $allow_edit;
                        }
                        if (!$common_base['product_is_behalf_delivery']) {
                            $this -> goodsCommonModel -> removeCommon($value['common_id']);
                            //发送消息
                            $des = '该商品不支持代发货';
                            $MessageModel -> sendMessage('del goods', $dist_shop_base['user_id'], $dist_shop_base['user_name'], $order_id = null, $shop_name = null, 1, 1, $end_time = null, $value['common_id'], $goods_id = null, $des);
                        } else {
                            $old_goods_base = $this -> goodsBaseModel -> getByWhere(array('common_id' => $value['common_id']));
                            $base_row = array();
                            foreach ($old_goods_base as $k => $val) {
                                if (in_array($val['goods_parent_id'], $remove_goods_ids)) {
                                    $this -> goodsBaseModel -> removeBase($val['goods_id']);
                                } elseif (in_array($val['goods_parent_id'], $edit_ids)) {
                                    $parent_goods = $this -> goodsBaseModel -> getOne($val['goods_parent_id']);
                                    $edit_rows = array();
                                    $edit_rows['goods_spec'] = $parent_goods['goods_spec'];
                                    $edit_rows['goods_price'] = $parent_goods['goods_recommended_min_price'];
                                    $edit_rows['goods_market_price'] = $parent_goods['goods_recommended_max_price'];
                                    $edit_rows['goods_stock'] = $parent_goods['goods_stock'];
                                    $edit_rows['goods_recommended_min_price'] = $parent_goods['goods_recommended_min_price'];
                                    $edit_rows['goods_recommended_max_price'] = $parent_goods['goods_recommended_max_price'];
                                    $this -> goodsBaseModel -> editBase($val['goods_id'], $edit_rows, false);
                                    $dist_common_row['goods_id'][] = array(
                                        'goods_id' => $val['goods_id'],
                                        'color' => $val['color_id']
                                    );
                                }
                                if ($add_ids) {
                                    foreach ($add_ids as $addk => $addv) {
                                        $parent_goods = $this -> goodsBaseModel -> getOne($addv);
                                        $add_rows = array();
                                        $add_rows['common_id'] = $value['common_id'];
                                        $add_rows['shop_id'] = $dist_shop_base['shop_id'];
                                        $add_rows['shop_name'] = $dist_shop_base['shop_name'];
                                        $add_rows['goods_name'] = $parent_goods['goods_name'];
                                        $add_rows['cat_id'] = $parent_goods['cat_id'];
                                        $add_rows['brand_id'] = $parent_goods['brand_id'];
                                        $add_rows['goods_spec'] = $parent_goods['goods_spec'];
                                        $add_rows['goods_price'] = $parent_goods['goods_recommended_min_price'];
                                        $add_rows['goods_market_price'] = $parent_goods['goods_recommended_max_price'];
                                        $add_rows['goods_stock'] = $parent_goods['goods_stock'];
                                        $add_rows['goods_image'] = $value['common_image'];
                                        $add_rows['goods_parent_id'] = $parent_goods['goods_id'];
                                        $add_rows['goods_is_shelves'] = 1;
                                        $add_rows['goods_recommended_min_price'] = $parent_goods['goods_recommended_min_price'];
                                        $add_rows['goods_recommended_max_price'] = $parent_goods['goods_recommended_max_price'];
                                        $add_goods_id = $this -> goodsBaseModel -> addBase($add_rows, true);
                                        $dist_common_row['goods_id'][] = array(
                                            'goods_id' => $add_goods_id,
                                            'color' => $parent_goods['color_id']
                                        );
                                    }
                                }
                            }
                            $dist_common_row['common_distributor_flag'] = 2;
                            if ($down_flag) {
                                $dist_common_row['common_state'] = 0;//下架
                                $dist_common_row['common_distributor_flag'] = 1;
                                //给每个商品下架的店铺发送提示
                                $common_state_remark = '供货商修改了商品-' . $common_base["common_name"] . '！';
                                $MessageModel -> sendMessage('Commodity violation is under the shelf', $dist_shop_base['user_id'], $dist_shop_base['user_name'], $order_id = null, $shop_name = null, 1, 1, $end_time = null, $value['common_id'], $goods_id = null, $common_state_remark);
                            }
                            $this -> goodsCommonModel -> editCommon($value['common_id'], $dist_common_row);
                        }
                    }
                }
                //商品添加或者修改成功向统计中心发送数据
                if ($action == 'edit') {
                    if ($edit_status) {
                        $analytics_data = array(
                            'common_id' => $common_id,
                        );
                        Yf_Plugin_Manager::getInstance() -> trigger('analyticsGoodsEdit', $analytics_data);
                        /******************************************************/
                    }
                } else {
                    $analytics_data = array(
                        'common_id' => $common_id,
                    );
                    Yf_Plugin_Manager::getInstance() -> trigger('analyticsGoodsAdd', $analytics_data);
                    /******************************************************/
                }
                if (Web_ConfigModel::value('Plugin_Fenxiao')) {
                    $cat_id = request_string('cat_id');
                    $values = request_row('fenxiao');
                    $data = [
                        'shop_id' => $shop_id,
                        'cat_id' => $cat_id,
                        'goods_id' => $common_id,
                        'values' => $values,
                    ];
                    Fenxiao::getInstance() -> updateGoods($data);
                }
                $data['common_id'] = $common_id;
                $this -> data -> addBody(-140, $data, __('success'), 200);
            } else {
                $this -> goodsCommonModel -> sql -> rollBackDb();
                $this -> data -> addBody(-140, array(), __('failure'), 250);
            }
        }
        
        /*
         *  保存图片
         * */
        /**
         * 是否可以编辑商品
         * 规则：
         *     活动商品不允许修改商品价格低于活动价2018/01/26
         *
         * @param $commonId
         * @param $spec
         *
         * @return boolean
         */
        private function isEditGoods($commonId, $price, $spec)
        {
            if (empty($commonId)) {
                return true;
            }
            $promotion = new Promotion;
            //两种情况：1、无goodsId 2、有goodsId
            if ($spec) { //有goodsId
                //获取goodsId
                foreach ($spec as $item) {
                    $goodsId = $item['goods_id'];
                    $editGoodsPrice = $item['price'];
                    $goodsPromotionPrice = $promotion -> getGoodsPromotionPrice($goodsId);
                    if ($goodsPromotionPrice > 0 && $editGoodsPrice < $goodsPromotionPrice) {
                        return false;
                    }
                }
            } else {
                $goods = $this -> goodsBaseModel -> getOneByWhere([
                    'common_id' => $commonId
                ]);
                $goodsId = $goods['goods_id'];
                $goodsPromotionPrice = $promotion -> getGoodsPromotionPrice($goodsId);
                if ($goodsPromotionPrice > 0 && $price < $goodsPromotionPrice) {
                    return false;
                }
            }
            return true;
        }
        
        public function saveGoodsImage()
        {
            $image_list = request_row('image');
            $common_id = request_int('common_id');
            $is_color = request_int('is_color');
            if (!empty($image_list)) {
                $Goods_CommonModel = new Goods_CommonModel();
                $goodsImagesModel = new Goods_ImagesModel();
                $images = $goodsImagesModel -> getByWhere(array('common_id' => $common_id));
                $images_ids = array_column($images, 'id');
                $goodsImagesModel -> removeImages($images_ids);
                $image_data['shop_id'] = Perm::$shopId;
                $image_data['common_id'] = $common_id;
                $num = 0;
                foreach ($image_list as $key => $val) {
                    $num++;
                    foreach ($val as $k => $v) {
                        if (!empty($is_color)) {
                            $image_data['images_color_id'] = $key;
                        }
                        $image_data['images_image'] = $v['name'];
                        $image_data['images_displayorder'] = $v['displayorder'];
                        $image_data['images_is_default'] = $v['default'];
                        //图片只有都有和都没有两种情况
                        if (!empty($v['name'])) {
                            if ($v['default'] == 1) {
                                $Goods_CommonModel -> editCommon($common_id, array('common_image' => $v['name']));
                            }
                            $flag = $goodsImagesModel -> addImages($image_data, true);
                            $flags[] = $flag;
                        } else {
                            $common_image = $Goods_CommonModel -> getOne($common_id);
                            if ($num == 1 && $k == 0) {
                                $image_data['images_is_default'] = Goods_ImagesModel::IMAGE_DEFAULT;
                                $image_data['images_image'] = $common_image['common_image'];
                            } elseif ($num > 1 && $k == 0) {
                                $image_data['images_image'] = $common_image['common_image'];
                            }
                            $flag = $goodsImagesModel -> addImages($image_data, true);
                            $flags[] = $flag;
                        }
                    }
                    unset($new0);
                    unset($new1);
                    unset($new_val);
                }
                header("location: " . Yf_Registry::get('url') . '?ctl=Seller_Goods&met=online&typ=e');
            }
        }
        
        // 测试-- 禅道 1072 --- 多个默认主图
        //                    1949 --- 默认主图与列表购物车订单以及退款/货不一致
        //                    3714 --- 有商品图片上传,永远无法调用 后台图片 的解决方案
        //
        //  goodsInfoMange.php 商品图片                   删除,不能注释
        //  store_goods_add.step2.js                             imagePath
        //  GoodCtlphp>addOrEditShopGoods()的     imagePath必填项
        // 取消注释.
        public function saveGoodsImage1()
        {
            $image_list = request_row('image');
            $common_id = request_int('common_id');
            $is_color = request_int('is_color');
            if (!empty($image_list)) {
                $Goods_CommonModel = new Goods_CommonModel();
                $goodsImagesModel = new Goods_ImagesModel();
                $images = $goodsImagesModel -> getByWhere(array('common_id' => $common_id));
                $images_ids = array_column($images, 'id');
                $goodsImagesModel -> removeImages($images_ids);
                $image_data['shop_id'] = Perm::$shopId;
                $image_data['common_id'] = $common_id;
                $num = 0;
                foreach ($image_list as $key => $val) {
                    $num++;
                    foreach ($val as $k => $v) {
                        if (!empty($is_color)) {
                            $image_data['images_color_id'] = $key;
                        }
                        $image_data['images_image'] = $v['name'];
                        $image_data['images_displayorder'] = $v['displayorder'];
                        $image_data['images_is_default'] = $v['default'];
                        //图片只有都有和都没有两种情况
                        if (!empty($v['name'])) {
                            if ($v['default'] == 1) {
                                $Goods_CommonModel -> editCommon($common_id, array('common_image' => $v['name']));
                            }
                            $flag = $goodsImagesModel -> addImages($image_data, true);
                            $flags[] = $flag;
                        } else {
                            $common_image = $Goods_CommonModel -> getOne($common_id);
                            if ($num == 1 && $k == 0) {
                                $image_data['images_is_default'] = Goods_ImagesModel::IMAGE_DEFAULT;
                                $image_data['images_image'] = Web_ConfigModel::value('photo_goods_logo');
                                $Goods_CommonModel -> editCommon($common_id, array('common_image' => Web_ConfigModel::value('photo_goods_logo')));
                            } elseif ($num > 1 && $k == 0) {
                                $image_data['images_image'] = $common_image['common_image'];
                            }
                            $flag = $goodsImagesModel -> addImages($image_data, true);
                            $flags[] = $flag;
                        }
                    }
                    unset($new0);
                    unset($new1);
                    unset($new_val);
                }
                header("location: " . Yf_Registry::get('url') . '?ctl=Seller_Goods&met=online&typ=e');
            }
        }
        
        public function delGoodsImage()
        {
            $image_id = request_int('image_id');
            if(!empty($image_id)){
                $goodsImagesModel = new Goods_ImagesModel();
                $flag = $goodsImagesModel -> removeImages($image_id);
            }else{
                $flag = true;
            }
            // 开启事务：
            $data = array();
            if ('json' == $this -> typ) {
                if ($flag) {
                    $msg = __("删除成功");
                    $status = 200;
                } else {
                    $msg = __("删除失败");
                    $status = 250;
                }
                $this -> data -> addBody(-140, $data, $msg, $status);
            }
        }
        
        public function editGoodsCommon()
        {
            $shop_id = Perm::$shopId;
            $Goods_CommonModel = new Goods_CommonModel();
            $goods_common_id_rows = request_row('chk');
            $shop_base = $this -> shopBaseModel -> getOne($shop_id);
            foreach ($goods_common_id_rows as $key => $value) {
                $goods_common_id = $value;
                $data_goods = $Goods_CommonModel -> getOne($goods_common_id);
                if ($data_goods['shop_id'] == $shop_id) {
                    if (request_string('act') == 'down') {
                        $flag = $Goods_CommonModel -> editCommon($goods_common_id, array('common_state' => Goods_CommonModel::GOODS_STATE_OFFLINE));
                        //如果是供货商下架，同时下架其分销商的该商品
                        if ($shop_base['shop_type'] == 2) {
                            $MessageModel = new MessageModel();
                            $all_dist_common = $Goods_CommonModel -> getByWhere(array('common_parent_id' => $goods_common_id));
                            if (!empty($all_dist_common)) {
                                foreach ($all_dist_common as $k => $v) {
                                    $dist_shop_base = $this -> shopBaseModel -> getOne($v['shop_id']);
                                    $dist_common_row['common_state'] = 0;//下架
                                    //给每个商品下架的店铺发通知
                                    $common_state_remark = '供货商修改了商品-' . $v["common_name"] . '！';
                                    $MessageModel -> sendMessage('Commodity violation is under the shelf', $dist_shop_base['user_id'], $dist_shop_base['user_name'], $order_id = null, $shop_name = null, 1, 1, $end_time = null, $v['common_id'], $goods_id = null, $common_state_remark);
                                    $Goods_CommonModel -> editCommon($v['common_id'], $dist_common_row);
                                }
                            }
                        }
                    } elseif (request_string('act') == 'up') {
                        if (request_string('me') == 'lockup') {
                            $flag = $Goods_CommonModel -> editCommon($goods_common_id, array('common_state' => Goods_CommonModel::GOODS_STATE_NORMAL, 'common_verify' => Goods_CommonModel::GOODS_STATE_ILLEGAL));
                        } else {
                            $flag = $Goods_CommonModel -> editCommon($goods_common_id, [
                                'common_state' => Goods_CommonModel::GOODS_STATE_NORMAL,
                                'common_verify' => Goods_CommonModel::GOODS_STATE_NORMAL,
                                'common_goods_from' => 1 //外部导入一经上架，撕掉外部导入标签
                            ]);
                            //对goods_base对应的数据上架
                            $goodsBaseModel = new Goods_BaseModel();
                            $goods_item = $goodsBaseModel -> getByWhere(array("common_id:IN" => $goods_common_id));
                            $goods_ids = array_column($goods_item, 'goods_id');
                            $flag = $goodsBaseModel -> editBase($goods_ids, array("goods_is_shelves" => Goods_BaseModel::GOODS_UP), false);
                        }
                    } elseif (request_string('act') == 'del') {
                        $flag = $Goods_CommonModel -> removeCommon($goods_common_id);
                    }
                }
            }
            if ($flag !== false) {
                $msg = __('success');
                $status = 200;
            } else {
                $msg = __('failure');
                $status = 250;
            }
            $this -> data -> addBody(-140, array(), $msg, $status);
        }
        
        /*
         *
         * 分销商品删除 即修改商品的分销状态为0
         *
         */
        public function deleteGoodsCommon()
        {
            $id = request_row('id');
//            $shop_id = Perm::$shopId;
//            $MessageModel = new MessageModel();
//            $data = $this->goodsCommonModel->getOne($id);
            //修改数据
            $common_data = array(
                'common_is_directseller' => 0,
                'common_cps_rate' => '0.00',
                'common_second_cps_rate' => '0.00',
                'common_third_cps_rate' => '0.00',
            );
            $flag = $this -> goodsCommonModel -> editCommon($id, $common_data);
//            //ljx修改逻辑
//            //1、判断获取的商品是否存在上级商品信息
//            if ($data['common_parent_id'] > 0 && request_string('act') == 'del') {
//                //2、是分销别人的商品就删除这个商品，
//                $flag = $this->goodsCommonModel->removeCommon($id);
//            } elseif (request_string('act') == 'update') {
//                //3、是自己分销的商品修改商品的分销状态设置为0，分理、佣金设置为0
//                //修改数据
//                $common_data = array(
//                    'common_is_directseller' => 0,
//                    'common_cps_rate' => '0.00',
//                    'common_second_cps_rate' => '0.00',
//                    'common_third_cps_rate' => '0.00',
//                );
//                $flag = $this->goodsCommonModel->editCommon($id, $common_data);
//            }
            if ($flag) {
                $msg = __('分销商品删除成功！');
                $status = 200;
            } else {
                $msg = __('分销商品删除失败！');
                $status = 250;
            }
//            $data_re['id'] = $id;
            $this -> data -> addBody(-140, array(), $msg, $status);
        }
        
        public function deleteGoodsCommonRows()
        {
            $id = request_row('id');
            $shop_id = Perm::$shopId;
            $MessageModel = new MessageModel();
            $shop_base = $this -> shopBaseModel -> getOne($shop_id);
            $data = $this -> goodsCommonModel -> getByWhere(array(
                'common_id:in' => $id,
                'shop_id' => $shop_id
            ));
            $Goods_CommonModel = new Goods_CommonModel();
            $common_ids = array_values(array_column($data, 'common_id'));
            $flag = $this -> goodsCommonModel -> removeCommon($common_ids);
            //批量删除分销商的商品
            if ($shop_base == 2 && !empty($id)) {
                foreach ($id as $key => $value) {
                    $all_dist_common = $Goods_CommonModel -> getByWhere(array('common_parent_id' => $value));
                    if (!empty($all_dist_common)) {
                        foreach ($all_dist_common as $k => $v) {
                            if ($data['common_parent_id'] > 0 && request_string('act') == 'del') {
                                //2、是分销别人的商品就删除这个商品，
                                $flag = $this -> goodsCommonModel -> removeCommon($v['common_id']);
                            } elseif (request_string('act') == 'update') {
                                //3、是自己分销的商品修改商品的分销状态设置为0，分理、佣金设置为0
                                //修改数据
                                $common_data = array(
                                    'common_is_directseller' => 0,
                                    'common_cps_rate' => '0.00',
                                    'common_second_cps_rate' => '0.00',
                                    'common_third_cps_rate' => '0.00',
                                );
                                $flag = $this -> goodsCommonModel -> editCommon($v['common_id'], $common_data);
                            }
                        }
                    }
                }
            }
            if ($flag) {
                $msg = __('success');
                $status = 200;
            } else {
                $msg = __('failure2');
                $status = 250;
            }
            $data_re['id'] = $id;
            $this -> data -> addBody(-140, $data_re, $msg, $status);
        }
        
        //关联板式
        public function format()
        {
            $Goods_FormatModel = new Goods_FormatModel();
            $cond_row = array();
            $cond_row['shop_id'] = Perm::$shopId;
            $key = request_string('search');
            $act = request_string('act');
            if ($act == 'add') {
                $this -> view -> setMet('addformat');
                include $this -> view -> getView();
                return;
            }
            if ($act == 'edit') {
                $Goods_FormatModel = new Goods_FormatModel();
                $id = request_int('id');
                $data = $Goods_FormatModel -> getOne($id);
                $data['content'] = addslashes($data['content']);
                $this -> view -> setMet('addformat');
                include $this -> view -> getView();
                return;
            }
            if ($key) {
                $cond_row['name:like'] = '%' . $key . '%';
            }
//        $Yf_Page = new Yf_Page();
//        $row     = $Yf_Page->listRows;
//        $offset  = request_int('firstRow', 0);
//        $page    = ceil_r($offset / $row);
            $Yf_Page = new Yf_Page();
            //$Yf_Page->listRows = 2;
            $rows = $Yf_Page -> listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $rows);
            $format_rows = $Goods_FormatModel -> getFormatList($cond_row, array(), $page, $rows);
            $data = $format_rows['items'];
            if (!empty($data)) {
                foreach ($data as $key => $value) {
                    if ($value['position'] == $Goods_FormatModel::FORMAT_POSITION_TOP) {
                        $data[$key]['position_name'] = __('顶部');
                    } elseif ($value['position'] == $Goods_FormatModel::FORMAT_POSITION_BOTTOM) {
                        $data[$key]['position_name'] = __('底部');
                    }
                }
            }
            $Yf_Page -> totalRows = $format_rows['totalsize'];
            $page_nav = $Yf_Page -> prompt();
            include $this -> view -> getView();
        }
        
        public function addFormatRow()
        {
            $Goods_FormatModel = new Goods_FormatModel();
            $add_data = array();
            $add_data['name'] = request_string('name');
            $add_data['position'] = request_string('position');
            $add_data['content'] = request_string('content');
            $add_data['shop_id'] = Perm::$shopId;
            $id = $Goods_FormatModel -> addFormat($add_data, true);
            if ($id) {
                $msg = __('success');
                $status = 200;
            } else {
                $msg = __('failure');
                $status = 250;
            }
            $re = array();
            $re['id'] = $id;
            $this -> data -> addBody(-140, $re, $msg, $status);
        }
        
        public function editFormatRow()
        {
            $Goods_FormatModel = new Goods_FormatModel();
            $edit_data = array();
            $id = request_int('id');
            $edit_data['name'] = request_string('name');
            $edit_data['position'] = request_string('position');
            $edit_data['content'] = request_string('content');
            $edit_data['shop_id'] = Perm::$shopId;
            $flag = $Goods_FormatModel -> editFormat($id, $edit_data);
            if ($flag !== false) {
                $msg = __('success');
                $status = 200;
            } else {
                $msg = __('failure');
                $status = 250;
            }
            $this -> data -> addBody(-140, array(), $msg, $status);
        }
        
        //删除版式
        public function deleteGoodsFormat()
        {
            $id = request_row('id');
            $Goods_FormatModel = new Goods_FormatModel();
            $flag = $Goods_FormatModel -> removeFormat($id);
            if ($flag !== false) {
                $msg = __('success');
                $status = 200;
            } else {
                $msg = __('failure');
                $status = 250;
            }
            $this -> data -> addBody(-140, array(), $msg, $status);
        }
        
        public function deleteGoodsFormatRows()
        {
            $ids = request_row('id');
            $Goods_FormatModel = new Goods_FormatModel();
            $flag = $Goods_FormatModel -> removeFormat($ids);
            if ($flag !== false) {
                $msg = __('success');
                $status = 200;
            } else {
                $msg = __('failure');
                $status = 250;
            }
            $this -> data -> addBody(-140, array(), $msg, $status);
        }
        
        public function catListManage()
        {
            include $this -> view -> getView();
        }
        
        /**
         * 商品导入页面
         */
        public function importGoods()
        {
            $typ = request_string('typ');
            if ($typ == 'e') {
                include $this -> view -> getView();
            } else {
                set_time_limit(15);
                $url_path = request_string('url_path'); //  /media/f528764d624db129b32c21fbca0cb8d6/1/1/file/20161101/1477965409390298.xlsx
                $url_path = ".$url_path";
                $objPHPExcel = PHPExcel_IOFactory::load($url_path);
                $objWorksheet = $objPHPExcel -> getActiveSheet();
                $highestRow = $objWorksheet -> getHighestRow();
                $highestColumn = $objWorksheet -> getHighestColumn();
                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                $excel_data = array();
                //getCellByColumnAndRow($col, $row)  start position :  $row : 1 , $col : 0
                for ($row = 4; $row <= $highestRow; $row++) {
                    for ($col = 0; $col < $highestColumnIndex; $col++) {
                        $excel_data[$row][] = (string)$objWorksheet -> getCellByColumnAndRow($col, $row) -> getValue();
                    }
                }
                //获取相关信息
                $shop_data = $this -> shopBaseModel -> getBase(Perm::$shopId);
                $shop_data = current($shop_data);
                $goods_cat_data = $this -> goodsCatModel -> getByWhere();
                $KGoodsCatName_VGoodsCatId = array_column($goods_cat_data, 'cat_id', 'cat_name');
                $goodsFormatModel = new Goods_FormatModel();
                $goods_format_rows = $goodsFormatModel -> getByWhere(array('shop_id' => Perm::$shopId));
                $KFormatName_VFormatId = array_column($goods_format_rows, 'id', 'name');
                $baseDistrictModel = new Base_DistrictModel();
                $district_rows = $baseDistrictModel -> getByWhere();
                $KDistrictName_VId = array_column($district_rows, 'district_id', 'district_name');
//          $transportTypeModel = new Transport_TypeModel();
//          $transport_type_rows = $transportTypeModel->getByWhere();
//          $KTransportTypeName_VId = array_column($transport_type_rows, 'transport_area_id', 'transport_type_name');
                $import_goods_rows = array();
                foreach ($excel_data as $key => $row_data) {
                    $goods_class = $row_data[0];    //商品分类
                    $goods_name = $row_data[3];    //商品名称
                    $goods_price = $row_data[5];    //商品价格
                    $market_price = $row_data[6];    //市场价
                    $goods_stock = $row_data[10];//商品库存
                    $goods_weight = $row_data[22];//商品重量
                    $goods_image = $row_data[13];//商品图片
                    //验证
                    //商品分类、商品名称、商品价格、市场价、商品库存、商品重量不能为空
                    if (empty($KGoodsCatName_VGoodsCatId[$goods_class]) || empty($goods_price) || empty($market_price) || empty($goods_stock) || empty($goods_weight)) {
                        continue 1;
                    }
                    if (strlen($goods_name) < 10 || strlen($goods_name) > 50) {
                        continue 1;
                    }
                    if ($goods_price > $market_price) {
                        continue 1;
                    }
                    $result_image_info = UploadCtl::catchImageByExcel($goods_image);
                    if ($result_image_info['state'] != "SUCCESS") {
                        continue 1;
                    } else {
                        $goods_image = $result_image_info['url'];
                    }
                    $goods_property = $row_data[1];    //商品属性组合
                    $goods_property_value = $row_data[2];    //商品属性值
                    $promotion_tips = $row_data[4];    //促销提示
                    $cost_price = $row_data[7];    //成本价
                    $goods_spec = $row_data[8];    //商品规格组合
                    $goods_spec_value = $row_data[9];    //商品规格值
                    $goods_alarm = $row_data[11];//库存预警值
                    $goods_code = $row_data[12];//商家货号
                    $goods_describe = $row_data[14];//商品描述
                    $goods_plate_top = $row_data[15];//关联顶部版式
                    $goods_plate_bottom = $row_data[16];//关联底部版式
                    $goods_is_virtual = $row_data[17];//虚拟商品
                    $goods_virtual_validity = $row_data[18];//商品有效期至
                    $goods_validity_refund = $row_data[19];//支持过期退款
                    $goods_address = $row_data[20];//所在地
//              $goods_shipping_cost        = $row_data[21];//运费
                    $g_vat = $row_data[23];//是否提供发票
                    $goods_service = $row_data[24];//售后服务
                    $goods_is_limit = $row_data[25];//每人限购件数
                    $goods_packing_list = $row_data[26];//包装清单
                    $store_class_rows = $row_data[27];//本店分类
                    $goods_publish_type = $row_data[28];//商品发布
                    $goods_publish_time = $row_data[29];//发布时间
                    $goods_recommend = $row_data[30];//商品推荐
                    $goods_cat_data = $goods_cat_data[$KGoodsCatName_VGoodsCatId[$goods_class]];
                    $import_goods_rows[$key]['shop_id'] = $shop_data['shop_id'];
                    $import_goods_rows[$key]['shop_name'] = $shop_data['shop_name'];
                    $import_goods_rows[$key]['shop_self_support'] = $shop_data['shop_self_support'] == Shop_BaseModel::SELF_SUPPORT_TRUE ? 1 : 0;;
                    $import_goods_rows[$key]['cat_id'] = $goods_cat_data['cat_id'];
                    $import_goods_rows[$key]['cat_name'] = $goods_cat_data['cat_name'];
                    $import_goods_rows[$key]['type_id'] = $goods_cat_data['type_id'];
                    $import_goods_rows[$key]['common_name'] = $goods_name;
                    $import_goods_rows[$key]['common_promotion_tips'] = $promotion_tips;
                    $import_goods_rows[$key]['common_price'] = $goods_price;
                    $import_goods_rows[$key]['common_market_price'] = $market_price;
                    $import_goods_rows[$key]['common_cost_price'] = $cost_price;
                    $import_goods_rows[$key]['common_stock'] = $goods_stock;
                    $import_goods_rows[$key]['common_alarm'] = $goods_alarm;
                    $import_goods_rows[$key]['common_code'] = $goods_code;
                    $import_goods_rows[$key]['common_image'] = $goods_image;
                    $import_goods_rows[$key]['common_body'] = $goods_describe;
                    $import_goods_rows[$key]['common_formatid_top'] = empty($KFormatName_VFormatId[$goods_plate_top]) ? 0 : $KFormatName_VFormatId[$goods_plate_top];
                    $import_goods_rows[$key]['common_formatid_bottom'] = empty($KFormatName_VFormatId[$goods_plate_bottom]) ? 0 : $KFormatName_VFormatId[$goods_plate_bottom];
                    $import_goods_rows[$key]['common_cubage'] = $goods_weight;
                    $import_goods_rows[$key]['common_service'] = $goods_service;
                    $import_goods_rows[$key]['common_limit'] = $goods_is_limit;
                    $import_goods_rows[$key]['common_packing_list'] = $goods_packing_list;
                    $import_goods_rows[$key]['common_add_time'] = date('Y-m-d H:i:s', time());
                    if ($g_vat == "是") {
                        $import_goods_rows[$key]['common_invoices'] = 2;
                    } else {
                        $import_goods_rows[$key]['common_invoices'] = 1;
                    }
                    if ($goods_recommend == "是") {
                        $import_goods_rows[$key]['common_is_recommend'] = 2;
                    } else {
                        $import_goods_rows[$key]['common_is_recommend'] = 1;
                    }
                    //商品所在地
                    if (!empty($goods_address)) {
                        $goods_address = explode('/', $goods_address);
                        $province_name = $goods_address[0];
                        $city_name = $goods_address[1];
                        $common_location[] = $KDistrictName_VId[$province_name];
                        $common_location[] = $KDistrictName_VId[$city_name];
                        $import_goods_rows[$key]['common_location'] = $common_location;
                    }
                    //售卖区域
                    //              if ( !empty($goods_shipping_cost) && !empty($KTransportTypeName_VId[$goods_shipping_cost]) ) {
//                  $import_goods_rows[$key]['transport_area_id']   = $KTransportTypeName_VId[$goods_shipping_cost];
//                  $import_goods_rows[$key]['transport_type_name'] = $goods_shipping_cost;
//              }
                    //虚拟商品
                    if ($goods_is_virtual == "是") {
                        $import_goods_rows[$key]['common_is_virtual'] = 1;
                        $import_goods_rows[$key]['common_virtual_date'] = $goods_virtual_validity;
                        $import_goods_rows[$key]['common_virtual_refund'] = $goods_validity_refund == "是" ? 1 : 0;
                    }
                    //发布时间
                    if ($goods_publish_type == "立即发布") {
                        $import_goods_rows[$key]['common_state'] = 1;
                    } elseif ($goods_publish_type == "发布时间" && !empty($goods_publish_time)) {
                        $import_goods_rows[$key]['common_state'] = 2;
                        $import_goods_rows[$key]['common_sell_time'] = date('Y-m-d H:i:s', strtotime($goods_publish_time));
                    } else {
                        $import_goods_rows[$key]['common_state'] = 3;
                    }
                }
                if (!empty($import_goods_rows)) {
                    foreach ($import_goods_rows as $import_goods_data) {
                        $common_body = $import_goods_data['common_body'];
                        unset($import_goods_data['common_body']);
                        $common_id = $this -> goodsCommonModel -> addCommon($import_goods_data, true);
                        if ($common_id) {
                            $common_detail_data = array();
                            $common_detail_data['common_id'] = $common_id;
                            $common_detail_data['common_body'] = $common_body;
                            $this -> goodsCommonDetailModel -> addCommonDetail($common_id, $common_detail_data);
                            $goods_data = array();
                            $goods_data['common_id'] = $common_id;                                        //商品公共表id
                            $goods_data['cat_id'] = $import_goods_data['cat_id'];                    //商品分类id
                            $goods_data['shop_id'] = $import_goods_data['shop_id'];                    //shop_id
                            $goods_data['shop_name'] = $import_goods_data['shop_name'];                    //shop_name
                            $goods_data['goods_name'] = $import_goods_data['common_name'];                //商品名称
                            $goods_data['goods_promotion_tips'] = $import_goods_data['common_promotion_tips'];        //促销提示
                            $goods_data['goods_is_recommend'] = $import_goods_data['common_is_recommend'];        //商品推荐
                            $goods_data['goods_image'] = $import_goods_data['common_image'];               //商品主图
                            $goods_data['goods_price'] = $import_goods_data['common_price'];               //商品价格
                            $goods_data['goods_market_price'] = $import_goods_data['common_market_price'];        //市场价
                            $goods_data['goods_stock'] = $import_goods_data['common_stock'];               //商品库存
                            $goods_data['goods_alarm'] = $import_goods_data['common_alarm'];               //库存预警值
                            $goods_data['goods_code'] = $import_goods_data['common_code'];                //商家编号货号
                            $goods_id = $this -> goodsBaseModel -> addBase($goods_data, true);
                            $edit_common_data['goods_id'] = array('goods_id' => $goods_id, 'color' => 0);
                            $edit_flag = $this -> goodsCommonModel -> editCommon($common_id, $edit_common_data);
                        }
                    }
                }
            }
            $this -> data -> addBody(-140, array(), '导入完成！', 200);
            exit;
        }
        
        /**
         * 下载导入模板
         */
        public function downloadTemplate()
        {
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=商品导入模板.xlsx");
            $objPHPExcel = new PHPExcel();
            $objPHPExcel -> getActiveSheet() -> getDefaultColumnDimension() -> setWidth(15);
            $objPHPExcel -> getDefaultStyle() -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel -> getDefaultStyle() -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $active_sheet = $objPHPExcel -> getActiveSheet();
            $active_sheet -> setCellValue('A1', '商品导入表');
            $active_sheet -> getStyle('A1') -> getFont() -> setSize(20);
            $active_sheet -> getStyle('A1') -> getFont() -> setBold(true);
            $explanation =
                "说明：
            1 不能修改模版格式(包括表头名称)。
            2 商品分类、商品名称、商品价格、市场价、商品库存、商品重量不能为空；其他为非必录项，按需录入。
            3 商品标题名称长度至少10个字符，最长50个汉字
            4 价格必须是0.01~9999999之间的数字，且不能高于市场价
            5 商品图片为网络图片地址，大小不能超过1M
            6 如果商品有属性，用/分隔属性名称和属性值
            7 如果商品有规格，用/分隔规格名称和规格值，后续行输入规格组合值";
            $active_sheet -> setCellValue('A2', $explanation);
            $active_sheet -> getStyle('A2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $active_sheet -> getStyle('A2') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_DISTRIBUTED);
            $active_sheet -> getRowDimension(2) -> setRowHeight(150);
            $objPHPExcel -> getActiveSheet() -> mergeCells('A1:AE1');
            $objPHPExcel -> getActiveSheet() -> mergeCells('A2:AE2');
            $title = array(
                '商品分类', '商品属性组合', '商品属性值',
                '商品名称', '促销提示',
                '商品价格', '市场价', '成本价',
                '商品规格组合', '商品规格值',
                '商品库存', '库存预警值', '商家货号',
                '商品图片', '商品描述',
                '关联顶部版式', '关联底部版式',
                '虚拟商品', '商品有效期至', '支持过期退款',
                '所在地', '运费', '商品重量',
                '是否提供发票', '售后服务',
                '每人限购件数', '包装清单', '本店分类',
                '商品发布', '发布时间', '商品推荐'
            );
            for ($x = "A", $y = 0; $y < count($title); $x++, $y++) {
                $value = $title[$y];
                $String_ABC = sprintf('%s3', $x);
                $active_sheet -> setCellValue($String_ABC, $value);
                $active_sheet -> getStyle($String_ABC) -> getFont() -> setBold(true);
            }
            //获取所有的商品分类
            $goodsCatModel = new Goods_CatModel();
            $cat_list_1 = $goodsCatModel -> getCatTreeData(0, 0, 0, true);
            $cat_list_2 = array_column($cat_list_1, 'cat_id');
            $cat_list_3 = $goodsCatModel -> getCatTreeData($cat_list_2, 0, 0, true);
            $cat_list = array();
            /*foreach ($cat_list_3 as $cat_data)
            {
                $cat_list[] = $cat_data['cat_name'];
            }*/
            $cat_list[] = 1;
            $cat_list[] = 2;
            //关联顶部版式 关联底部版式
            $goodsFormatModel = new Goods_FormatModel();
            $condi_format['shop_id'] = Perm::$shopId;
            $format_list = $goodsFormatModel -> getByWhere($condi_format);
            $format_top = array();
            $format_bottom = array();
            if (!empty($format_list)) {
                foreach ($format_list as $key => $val) {
                    if ($val['position'] == Goods_FormatModel::FORMAT_POSITION_TOP) {
                        $format_top[] = $val['name'];
                    } else {
                        $format_bottom[] = $val['name'];
                    }
                }
            }
            //售卖区域
            $transportAreaModel = new Transport_AreaModel();
            $transport_list = $transportAreaModel -> getByWhere(array('shop_id' => Perm::$shopId));
            $transport_list = $transport_list['items'];
            $transport_name_row = array_column($transport_list, 'name');
            for ($i = 4; $i < 99; $i++) {
                $objValidation_A = $active_sheet -> getCell("A$i") -> getDataValidation();
                $objValidation_A -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation_A -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation_A -> setAllowBlank(true);
                $objValidation_A -> setShowInputMessage(true);
                $objValidation_A -> setShowDropDown(true);
                $objValidation_A -> setFormula1('"' . join(',', $cat_list) . '"');
                $objValidation_P = $active_sheet -> getCell("P$i") -> getDataValidation();
                $objValidation_P -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation_P -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation_P -> setAllowBlank(true);
                $objValidation_P -> setShowInputMessage(true);
                $objValidation_P -> setShowErrorMessage(true);
                $objValidation_P -> setShowDropDown(true);
                $objValidation_P -> setErrorTitle('failure');
                $objValidation_P -> setError('该关联顶部版式不存在,请再次选择!');
                $objValidation_P -> setFormula1('"' . join(',', $format_top) . '"');
                $objValidation_Q = $active_sheet -> getCell("Q$i") -> getDataValidation();
                $objValidation_Q -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation_Q -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation_Q -> setAllowBlank(true);
                $objValidation_Q -> setShowInputMessage(true);
                $objValidation_Q -> setShowErrorMessage(true);
                $objValidation_Q -> setShowDropDown(true);
                $objValidation_Q -> setErrorTitle('failure');
                $objValidation_Q -> setError('该关联底部版式不存在,请再次选择!');
                $objValidation_Q -> setFormula1('"' . join(',', $format_bottom) . '"');
                $objValidation_Q = $active_sheet -> getCell("V$i") -> getDataValidation();
                $objValidation_Q -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation_Q -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation_Q -> setAllowBlank(true);
                $objValidation_Q -> setShowInputMessage(true);
                $objValidation_Q -> setShowErrorMessage(true);
                $objValidation_Q -> setShowDropDown(true);
                $objValidation_Q -> setErrorTitle('failure');
                $objValidation_Q -> setError('该运费不存在,请再次选择!');
                $objValidation_Q -> setFormula1('"' . join(',', $transport_name_row) . '"');
                $objValidation_R = $active_sheet -> getCell("R$i") -> getDataValidation();    //虚拟商品
                $objValidation_R -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation_R -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation_R -> setAllowBlank(true);
                $objValidation_R -> setShowInputMessage(true);
                $objValidation_R -> setShowErrorMessage(true);
                $objValidation_R -> setShowDropDown(true);
                $objValidation_R -> setErrorTitle('failure');
                $objValidation_R -> setError('请按格式填写!');
                $objValidation_R -> setFormula1('"是,否"');
                $objValidation_T = $active_sheet -> getCell("T$i") -> getDataValidation();    //支持过期退款
                $objValidation_T -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation_T -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation_T -> setAllowBlank(true);
                $objValidation_T -> setShowInputMessage(true);
                $objValidation_T -> setShowErrorMessage(true);
                $objValidation_T -> setShowDropDown(true);
                $objValidation_T -> setErrorTitle('failure');
                $objValidation_T -> setError('请按格式填写!');
                $objValidation_T -> setFormula1('"是,否"');
                $objValidation_X = $active_sheet -> getCell("X$i") -> getDataValidation();     //是否提供发票
                $objValidation_X -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation_X -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation_X -> setAllowBlank(true);
                $objValidation_X -> setShowInputMessage(true);
                $objValidation_X -> setShowErrorMessage(true);
                $objValidation_X -> setShowDropDown(true);
                $objValidation_X -> setErrorTitle('failure');
                $objValidation_X -> setError('请按格式填写!');
                $objValidation_X -> setFormula1('"是,否"');
                $objValidation_X = $active_sheet -> getCell("AC$i") -> getDataValidation();     //商品发布
                $objValidation_X -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation_X -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation_X -> setAllowBlank(true);
                $objValidation_X -> setShowInputMessage(true);
                $objValidation_X -> setShowErrorMessage(true);
                $objValidation_X -> setShowDropDown(true);
                $objValidation_X -> setErrorTitle('failure');
                $objValidation_X -> setError('请按格式填写!');
                $objValidation_X -> setFormula1('"立即发布,发布时间,放入仓库"');
                $objValidation_X = $active_sheet -> getCell("AE$i") -> getDataValidation();     //商品推荐
                $objValidation_X -> setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
                $objValidation_X -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                $objValidation_X -> setAllowBlank(true);
                $objValidation_X -> setShowInputMessage(true);
                $objValidation_X -> setShowErrorMessage(true);
                $objValidation_X -> setShowDropDown(true);
                $objValidation_X -> setErrorTitle('failure');
                $objValidation_X -> setError('请按格式填写!');
                $objValidation_X -> setFormula1('"是,否"');
            }
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter -> save("php://output");
            exit;
        }
        
        public function findDataImporter()
        {
            $this -> data -> addBody(-140, array(), 'success', 200);
        }
        
        /**
         * @author LY
         * @time   2017-05-16
         * 等待发布商品页
         */
        public function waitReleaseGoods()
        {
            $yf_page = new Yf_Page(); //分页
            $row = $yf_page -> listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $row);
            $conditions = [
                'common_verify' => Goods_CommonModel::GOODS_VERIFY_ALLOW,  //common_verify=> 1 审核通过
                'common_state' => Goods_CommonModel::GOODS_STATE_TIMING,  //common_state=> 2 定时发布
                'shop_id' => Perm::$shopId                                 //所属店铺
            ];
            $goods_key = request_string('goods_key'); //按商品名称搜索
            if (!empty($goods_key)) {
                $conditions['common_name:like'] = '%' . $goods_key . '%';
            }
            $common_goods_rows = $this -> goodsCommonModel -> listByWhere($conditions, ['common_id' => 'DESC'], $page, $row);
            $yf_page -> totalRows = $common_goods_rows['totalsize'];
            $page_nav = $yf_page -> prompt();
            //获取goods_id
            $common_ids = array_column($common_goods_rows['items'], 'common_id');
            if (!empty($common_id_rows)) {
                $goods_detail_rows = $this -> goodsCommonModel -> getGoodsDetailRows($common_ids);
            }
            $goods = $this -> goodsCommonModel -> getRecommonRow($common_goods_rows);
            $no_shangjia = true; //不需要展示上架功能
            $this -> view -> setMet('online');
            include $this -> view -> getView();
        }
    }

?>

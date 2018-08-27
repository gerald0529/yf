<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Distribution_Seller_SettingCtl extends Seller_Controller
{
    public $Distribution_ShopDirectsellerConfigModel = null;
    public $userBaseModel = null;

    /**
     * Constructor
     *
     * @param  string $ctl 控制器目录
     * @param  string $met 控制器方法
     * @param  string $typ 返回数据类型
     * @access public
     */
    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);
        $this->Distribution_ShopDirectsellerConfigModel = new Distribution_ShopDirectsellerConfigModel();
        $this->userBaseModel = new User_BaseModel();
    }

    /**
     * 首页
     *
     * @access public
     */
    public function index()
    {
        $shop_id = Perm::$shopId;
        $data = $this->Distribution_ShopDirectsellerConfigModel->getConfigs($shop_id);
        include $this->view->getView();
    }

    public function edit()
    {
        $shop_id = Perm::$shopId;
        $edit_directseller_row = request_row("directseller");

        $temp = $this->Distribution_ShopDirectsellerConfigModel->getConfigList(array('shop_id' => $shop_id));
        if (!empty($temp)) {
            $flag = $this->Distribution_ShopDirectsellerConfigModel->updateConfig($shop_id, $edit_directseller_row);
        } else {
            $edit_directseller_row['shop_id'] = $shop_id;
            $flag = $this->Distribution_ShopDirectsellerConfigModel->addConfig($edit_directseller_row);
        }

        if ($flag === FALSE) {
            $status = 250;
            $msg = __('failure');
        } else {
            $status = 200;
            $msg = __('success');
        }
        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function directseller()
    {
        $shop_id = Perm::$shopId;
        $cond_row['shop_id'] = $shop_id;
        $order_row['directseller_create_time'] = 'DESC';
        $Distribution_ShopDirectsellerModel = new Distribution_ShopDirectsellerModel();

        if (request_string('start_date')) {
            $cond_row['directseller_create_time:>='] = request_string('start_date');
        }
        if (request_string('end_date')) {
            $cond_row['directseller_create_time:<='] = request_string('end_date');
        }
        if (request_int('state')) {
            $state = request_int('state') - 1;
            $cond_row['directseller_enable'] = $state;
        }

        if (request_string('op') == 'audit') {
            $id = request_int('id');
            $field_row['directseller_enable'] = 1;
            $flag = $Distribution_ShopDirectsellerModel->editBase($id, $field_row);
            if ($flag === FALSE) {
                $status = 250;
                $msg = __('failure');
            } else {
                $status = 200;
                $msg = __('success');
            }
            $data = array();
            $this->data->addBody(-140, $data, $msg, $status);
        }

        //获取店铺分销员
        $Yf_Page = new Yf_Page();
        $Yf_Page->listRows = 15;
        $rows = $Yf_Page->listRows;
        $offset = request_int('firstRow', 0);
        $page = ceil_r($offset / $rows);

        $data = $Distribution_ShopDirectsellerModel->getDirectseller($cond_row, $order_row, $page, $rows);

        $Yf_Page->totalRows = $data['totalsize'];
        $page_nav = $Yf_Page->prompt();

        include $this->view->getView();
    }

    /**
     * 删除分销员
     *
     * @access public
     */
    public function delDirectseller()
    {
        $shop_directseller_id = request_int("id");
        $shop_id = Perm::$shopId;

        $Distribution_ShopDirectsellerModel = new Distribution_ShopDirectsellerModel();

        $directseller = $Distribution_ShopDirectsellerModel->getOne($shop_directseller_id);

        //判断删除操作是不是当前店铺
        if ($directseller['shop_id'] == $shop_id) {
            $flag = $Distribution_ShopDirectsellerModel->removeShopDirectseller($shop_directseller_id);
            if ($flag) {
                $status = 200;
                $msg = __('success');
            } else {
                $status = 250;
                $msg = __('failure');
            }
        } else {
            $status = 250;
            $msg = __('failure');
        }
        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);

    }

    /**
     * 分销商品列表
     *
     * @access public
     */
    function directsellerGoods()
    {
        $Yf_Page = new Yf_Page();
        $Yf_Page->listRows = 10;
        $rows = $Yf_Page->listRows;
        $offset = request_int('firstRow', 0);
        $page = ceil_r($offset / $rows);

        $Goods_CommonModel = new Goods_CommonModel();
        $cond_row['shop_id'] = Perm::$shopId;
        $cond_row['common_state'] = Goods_CommonModel::GOODS_STATE_NORMAL;
        $cond_row['common_verify'] = Goods_CommonModel::GOODS_VERIFY_ALLOW;
        $cond_row['common_is_directseller'] = 1;

        $common_name = request_string('common_name');
        if ($common_name) {
            $cond_row['common_name:LIKE'] = "%" . $common_name . "%";
        }

        $data = $Goods_CommonModel->getGoodsList($cond_row, array('common_id' => 'DESC'), $page, $rows);
        //print_r($data);
        $Yf_Page->totalRows = $data['totalsize'];
        $page_nav = $Yf_Page->prompt();

        include $this->view->getView();
    }

    /**
     * 添加分销商品页面
     *
     * @access public
     */
    function addDirectsellerGoods()
    {
        include $this->view->getView();
    }

    /**
     * 获取店铺未参与分销的商品
     *
     * @access public
     */
    public function getShopGoods()
    {
        $cond_row = array();

        //分页
        $Yf_Page = new Yf_Page();
        $Yf_Page->listRows = request_int('listRows') ? request_int('listRows') : 12;
        $rows = $Yf_Page->listRows;
        $offset = request_int('firstRow', 0);
        $page = ceil_r($offset / $rows);

        //店铺ID
        $cond_row['shop_id'] = Perm::$shopId;
        $cond_row['common_state'] = Goods_CommonModel::GOODS_STATE_NORMAL;
        $cond_row['common_verify'] = Goods_CommonModel::GOODS_VERIFY_ALLOW;
        $cond_row['common_is_directseller:<>'] = 1;

        $goods_name = request_string('goods_name');
        if ($goods_name) {
            $cond_row['common_name:LIKE'] = "%" . $goods_name . "%";
        }

        $Goods_CommonModel = new Goods_CommonModel();
        $data = $Goods_CommonModel->getGoodsList($cond_row, array('common_id' => 'DESC'), $page, $rows);

        $Yf_Page->totalRows = $data['totalsize'];
        $page_nav = $Yf_Page->prompt();

        $rows = array();
        foreach ($data['items'] as $key => $value) {
            $rows[$value['common_id']] = $data['items'][$key];
            $rows[$value['common_id']]['id'] = $value['common_id'];
            $rows[$value['common_id']]['common'] = $value['common_id'];
            $rows[$value['common_id']]['price'] = format_money($value['common_price']);
            $rows[$value['common_id']]['image'] = $value['common_image'];
            $rows[$value['common_id']]['name'] = $value['common_name'];
        }
        $rows = encode_json($rows);

        if ('json' == $this->typ) {
            $this->data->addBody(-140, $data);
        } else {
            include $this->view->getView();
        }
    }

    /**
     * 新增分销商品
     *
     * @access public
     */
    function addGoods()
    {
        $common_id_rows = request_row('join_act_common_id'); //商品Common

        if ($common_id_rows) {
            $Goods_CommonModel = new Goods_CommonModel();

            foreach ($common_id_rows as $k => $v) {
                //三级分佣
                $common_data['common_cps_rate'] = request_float('common_cps_rate'); //一级分佣比例
                $common_data['common_second_cps_rate'] = request_float('common_second_cps_rate'); //二级分佣比例
                $common_data['common_third_cps_rate'] = request_float('common_third_cps_rate');   //三级分佣比例
                $common_data['common_is_directseller'] = 1;

                $common = $Goods_CommonModel->getOne($v);
                $common_data['common_cps_commission'] = number_format((request_float('common_cps_rate') * $common['common_price'] / 100), 2, '.', '');

                $flag = $Goods_CommonModel->editCommon($v, $common_data);
            }
        }

        if ($flag !== false) {
            $msg = __('success');
            $status = 200;
        } else {
            $msg = __('failure');
            $status = 250;
        }

        $this->data->addBody(-140, array(), $msg, $status);
    }

    //删除分销商品
    public function delGoods()
    {
        $id = request_row('id');
        $shop_id = Perm::$shopId;
        $rs_row = array();

       $Goods_CommonModel = new Goods_CommonModel();
        //判断商品是否是本店商品
        $data = $Goods_CommonModel->getByWhere(array(
                                                        'common_id:in' => $id,
                                                        'shop_id' => $shop_id
                                                    ));
        $common_id_rows = array_values(array_column($data, 'common_id'));

        if ($common_id_rows) {
            foreach ($common_id_rows as $k => $v) {
                //三级分佣
                $common_data['common_cps_rate'] = 0; //一级分佣比例
                $common_data['common_second_cps_rate'] = 0; //二级分佣比例
                $common_data['common_third_cps_rate'] = 0;   //三级分佣比例
                $common_data['common_is_directseller'] = 0;
                $common_data['common_cps_commission'] = 0;

                $edit_flag = $Goods_CommonModel->editCommon($v, $common_data);
                check_rs($edit_flag,$rs_row);
            }
        }

        $flag = is_ok($edit_flag);
        if ($flag) {
            $msg = __('success');
            $status = 200;
        } else {
            $msg = __('failure2');
            $status = 250;
        }
        $data_re['id'] = $id;
        $this->data->addBody(-140, $data_re, $msg, $status);
    }

    /**
     * 编辑分销商品分佣比例
     *
     * @access public
     */
    function editDirectsellerGoods()
    {
        $common_id = request_int('common_id');
        $Goods_CommonModel = new Goods_CommonModel();

        //三级分佣
        $common_data['common_cps_rate'] = request_float('common_cps_rate'); //一级分佣比例
        $common_data['common_second_cps_rate'] = request_float('common_second_cps_rate'); //二级分佣比例
        $common_data['common_third_cps_rate'] = request_float('common_third_cps_rate');   //三级分佣比例
        $common_data['common_is_directseller'] = 1;

        $common = $Goods_CommonModel->getOne($common_id);
        $common_data['common_cps_commission'] = number_format((request_float('common_cps_rate') * $common['common_price'] / 100), 2, '.', '');

        $flag = $Goods_CommonModel->editCommon($common_id, $common_data);

        if ($flag !== false) {
            $msg = __('success');
            $status = 200;
        } else {
            $msg = __('failure');
            $status = 250;
        }

        $this->data->addBody(-140, array(), $msg, $status);
    }

    //分销业绩
    function directsellerDetail()
    {
        $status = request_string('status');
        $skip_off = request_int('skip_off');
        $buyer_name = request_string('buyer_name');
        $Order_BaseModel = new Order_BaseModel();

        //订单号
        if (request_string('orderkey')) {
            $cond_row['order_id:LIKE'] = '%' . request_string('orderkey') . '%';
        }

        //开始时间
        if (request_string('start_date')) {
            $cond_row['order_create_time:>'] = request_string('start_date');
        }

        //结束时间
        if (request_string('end_date')) {
            $cond_row['order_create_time:<'] = request_string('end_date');
        }

        //买家
        if (!empty($buyer_name)) {
            $cond_row['buyer_user_name:LIKE'] = "%$buyer_name%";
        }

        //取消订单
        if ($skip_off) {
            $cond_row['order_status:<>'] = Order_StateModel::ORDER_CANCEL;
        }

        //待付款
        if ($status == 'wait_pay') {
            $cond_row['order_status'] = Order_StateModel::ORDER_WAIT_PAY;
        }
        //待发货 -> 只可退款
        if ($status == 'wait_perpare_goods') {
            $cond_row['order_status'] = Order_StateModel::ORDER_WAIT_PREPARE_GOODS;
        }
        //已付款
        if ($status == 'order_payed') {
            $cond_row['order_status'] = Order_StateModel::ORDER_PAYED;
        }
        //待收货、已发货 -> 退款退货
        if ($status == 'wait_confirm_goods') {
            $cond_row['order_status'] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
        }
        //已完成 -> 订单评价
        if ($status == 'finish') {
            $cond_row['order_status'] = Order_StateModel::ORDER_FINISH;
        }
        //已取消
        if ($status == 'cancel') {
            $cond_row['order_status'] = Order_StateModel::ORDER_CANCEL;
        }

        if (request_int('directseller_id')) {
            $cond_rows['directseller_id'] = request_int('directseller_id'); //直属一级
            $first_orders = $Order_BaseModel->getByWhere($cond_rows);
            $first_order_ids = array_column($first_orders, 'order_id');

            $cond_rows['directseller_p_id'] = request_int('directseller_id'); //二级
            $second_orders = $Order_BaseModel->getByWhere($cond_rows);
            $second_order_ids = array_column($second_orders, 'order_id');

            $cond_rows['directseller_gp_id'] = request_int('directseller_id'); //三级
            $third_orders = $Order_BaseModel->getByWhere($cond_rows);
            $third_order_ids = array_column($third_orders, 'order_id');

            $order_ids = $first_order_ids + $second_order_ids + $third_order_ids;

            if (empty($order_ids)) $order_ids = array('-1'); //没有数据
        }

        if (isset($order_ids)) {
            $cond_row['order_id:IN'] = $order_ids;
        }

        $Yf_Page = new Yf_Page();
        $Yf_Page->listRows = 10;
        $rows = $Yf_Page->listRows;
        $offset = request_int('firstRow', 0);
        $page = ceil_r($offset / $rows);

        //在goods_common表中查出用户可以分销的商品
        $Goods_CommonModel = new Goods_CommonModel();
        $cond_goods_row['shop_id'] = Perm::$shopId;
        // $cond_goods_row['common_state'] = Goods_CommonModel::GOODS_STATE_NORMAL;
        // $cond_goods_row['common_verify'] = Goods_CommonModel::GOODS_VERIFY_ALLOW;
        $cond_goods_row['common_is_directseller'] = 1;
        $goods_data = $Goods_CommonModel->getByWhere($cond_goods_row, array('common_id' => 'DESC'));

        $goods_ids = array();
        //将分销的商品id放在一个数组
        foreach ($goods_data as $gkey => $gval) {
            foreach ($gval['goods_id'] as $gk => $gv) {
                foreach($gv as $k => $v)
                {
                    $goods_ids[] = $gv['goods_id'];
                }

            }
        };
        //去order_goods表中查询有分销商品的订单，并且将订单id放在一个新数组中
        $Order_GoodsModel = new Order_GoodsModel();
        $cond_goods_ids_row['goods_id:IN'] = $goods_ids;
        $order_goods_data = $Order_GoodsModel->getByWhere($cond_goods_ids_row, array());

        $order_ids = array_column($order_goods_data, 'order_id');

        //查询订单id在订单id数组中的订单列表
        $data = array();
        $cond_row['directseller_id:!='] = 0;
        $cond_row['shop_id'] = Perm::$shopId;
        $cond_row['order_id:IN'] = $order_ids;
        $data = $Order_BaseModel->getBaseList($cond_row, array('order_create_time' => 'DESC'), $page, $rows);

        if ($data['items']) {
            foreach ($data['items'] as $k => $v) {
                switch ($v['order_status']) {
                    case Order_StateModel::ORDER_WAIT_PAY :
                        $data['items'][$k]['order_status_text'] = '订单已经提交，等待买家付款';
                        break;

                    case Order_StateModel::ORDER_PAYED :
                        $data['items'][$k]['order_status_text'] = '已经付款';
                        break;

                    case Order_StateModel::ORDER_WAIT_CONFIRM_GOODS :
                        $data['items'][$k]['order_status_text'] = '已经发货';
                        break;

                    case Order_StateModel::ORDER_RECEIVED || Order_StateModel::ORDER_FINISH :
                        $data['items'][$k]['order_status_text'] = '已经收货';
                        break;

                    case Order_StateModel::ORDER_CANCEL:
                        $data['items'][$k]['order_status_text'] = '交易关闭';
                        break;
                }

                if ($v['order_shipping_fee'] == 0) {
                    $data['items'][$k]['shipping_info'] = "(免运费)";
                } else {
                    $shipping_fee = @format_money($v['order_shipping_fee']);
                    $data['items'][$k]['shipping_info'] = "(含运费$shipping_fee)";
                }
            }
        }

        $Yf_Page->totalRows = $data['totalsize'];
        $page_nav = $Yf_Page->prompt();
        $data['page_nav'] = $page_nav;

        if ($this->typ == "json") {
            $this->data->addBody(-140, $data);
        } else {
            include $this->view->getView();
        }
    }
}

?>

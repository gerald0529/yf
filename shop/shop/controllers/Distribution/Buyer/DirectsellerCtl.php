<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Distribution_Buyer_DirectsellerCtl extends Buyer_Controller
{
    public $directseller_model = null;

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
        $this->directseller_model = new Distribution_ShopDirectsellerModel();
    }

    /**
     * 首页
     *
     * @access public
     */
    public function index()
    {
        $act = request_string('act');

        $Yf_Page = new Yf_Page();
        $Yf_Page->listRows = 10;
        $rows = $Yf_Page->listRows;
        $offset = request_int('firstRow', 0);
        $page = ceil_r($offset / $rows);

        //获取店铺列表
        $data = $this->directseller_model->getShopList(array(), array(), $page, $rows);
//        $Goods_CommonModel = new Goods_CommonModel();
//        $data = $Goods_CommonModel->getCommonList(array(), array(), $page, $rows);

        $Yf_Page->totalRows = $data['totalsize'];
        $page_nav = $Yf_Page->prompt();

        if ($act == "apply") {
            $this->view->setMet('apply');
        }

        if ($this->typ == "json") {
            $this->data->addBody(-140, $data);
        } else {
            include $this->view->getView();
        }
    }

    /*
    * 分销中心-WAP端
    */
    public function wapIndex()
    {
        $userId = Perm::$userId;
        $User_InfoModel = new User_InfoModel();
        $data = $User_InfoModel->getOne($userId);

        //获取用户邀请的直属会员---全部
        $row['user_parent_id'] = Perm::$userId;
        $data['invitors'] = $User_InfoModel->getSubQuantity($row);

        //获取用户邀请的直属会员---本月
        $row['user_regtime:<='] = get_date_time();
        $beginDate = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $row['user_regtime:>='] = $beginDate;
        $data['month_invitors'] = $User_InfoModel->getSubQuantity($row);

        //用户推广订单
        $Order_BaseModel = new Order_BaseModel();
        $order_con['directseller_id'] = $userId;
        $data['promotion_order_nums'] = $Order_BaseModel->getPromotionOrderNum($order_con);

        //完成订单数量
        $order_con['order_status'] = Order_StateModel::ORDER_FINISH;
        $data['finish_nums'] = $Order_BaseModel->getPromotionOrderNum($order_con);

        //用户推广商品的数量
//        $data['goods_num'] = $this->directseller_model->getDistributionGoodsNum($userId);

        // 用户推广的 所有店铺商家 商品数量
        $cond_row['directseller_id'] = $userId;
        $Distribution_ShopDirectsellerModel = new Distribution_ShopDirectsellerModel();
        $shops = $Distribution_ShopDirectsellerModel->getByWhere($cond_row);

        $shop_ids = array_column($shops, 'shop_id');

        $cond_good_row['shop_id:in'] = $shop_ids;
        $cond_good_row['common_is_directseller'] = 1;

        if (request_string('keywords')) {
            $cond_good_row['common_name:LIKE'] = '%' . request_string('keywords') . '%'; //商品名称搜索
        }

        $Yf_Page = new Yf_Page();
        $Yf_Page->listRows = 10;
        $rows = $Yf_Page->listRows;
        $offset = request_int('firstRow', 0);
        $page = ceil_r($offset / $rows);

        $Goods_CommonModel = new Goods_CommonModel();
        $data_dir = $Goods_CommonModel->getCommonList($cond_good_row, array(), $page, $rows);

        $data['goods_num'] = $data_dir['totalsize'];

        //用户分销店铺
        $data['directseller'] = $this->directseller_model->getOneByWhere(array('directseller_id' => $userId));


        $shopBaseModel = new Shop_BaseModel();
        $shop_base = $shopBaseModel->getBaseOneList(array('shop_id' => Perm::$shopId));
        $data['shop_name'] = $shop_base['shop_name'];

        if ($this->typ == "json") {
            $this->data->addBody(-140, $data);
        } else {
            include $this->view->getView();
        }
    }

    /*
    * 添加店铺分销员
    */
    public function addDirectseller()
    {
        $data['directseller_id'] = Perm::$userId;  //分销用户ID
        $data['directseller_shop_name'] = request_string("directseller_shop_name");  //分销小店名称
        $data['directseller_create_time'] = get_date_time();   //创建时间
        $data['directseller_enable'] = 0;
        $data['shop_id'] = request_int('shop_id');
        $flag = $this->directseller_model->addDirectseller($data);

        if ($flag) {
            $msg = __('success');
            $status = 200;
        } else {
            $msg = __('failure');
            $status = 250;
        }
        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }

    /*
    * 分销用户下级列表
    */
    public function directsellerList()
    {
        $User_InfoModel = new User_InfoModel();
        $cond_row['user_parent_id'] = Perm::$userId;
        if (request_string('userName')) {
            $cond_row['user_name:LIKE'] = '%' . request_string('userName') . '%';
        }
        if (request_string('act') == 'month') {
            $cond_row['user_regtime:<='] = get_date_time();
            $beginDate = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), 1, date('Y')));
            $cond_row['user_regtime:>='] = $beginDate;
        }

        $order_row['user_regtime'] = 'DESC';

        $Yf_Page = new Yf_Page();
        $Yf_Page->listRows = 10;
        $rows = $Yf_Page->listRows;
        $offset = request_int('firstRow', 0);
        $page = ceil_r($offset / $rows);

        $data = $this->directseller_model->getInvitors($cond_row, $order_row, $page, $rows);

        $Yf_Page->totalRows = $data['totalsize'];
        $page_nav = $Yf_Page->prompt();

        if ($this->typ == "json") {
            $this->data->addBody(-140, $data);
        } else {
            include $this->view->getView();
        }
    }

    /*
    * 分销佣金明细
    */
    public function directsellerCommission()
    {
        $status = request_string('status');
        // BEGIN 条件筛选
        if (request_string('orderkey')) {
            $cond_row['order_id:LIKE'] = '%' . request_string('orderkey') . '%';
        }
        if (request_string('start_date')) {
            $cond_row['order_finished_time:>'] = request_string('start_date');
        }
        if (request_string('end_date')) {
            $cond_row['order_finished_time:<'] = request_string('end_date');
        }
        // END

        switch ($status) {
            case "second" :
                $cond_row['directseller_p_id'] = Perm::$userId;
                break;
            case "third" :
                $cond_row['directseller_gp_id'] = Perm::$userId;
                break;
            default:
                $cond_row['directseller_id'] = Perm::$userId;
                break;
        }
        $Yf_Page = new Yf_Page();
        $Yf_Page->listRows = 10;
        $rows = $Yf_Page->listRows;
        $offset = request_int('firstRow', 0);
        $page = ceil_r($offset / $rows);

        $cond_row['order_status'] = Order_StateModel::ORDER_FINISH;
        $Order_BaseModel = new Order_BaseModel();
        $data = $Order_BaseModel->getBaseList($cond_row, array('order_create_time' => 'DESC'), $page, $rows);
        $Yf_Page->totalRows = $data['totalsize'];
        $page_nav = $Yf_Page->prompt();
        $data['t'] = $status;

        if ($this->typ == "json") {
            $this->data->addBody(-140, $data);
        } else {
            include $this->view->getView();
        }
    }

    /*
     *  用户推广订单
     */
    public function directsellerOrder()
    {
        $status = request_string('status');

        if (request_string('orderkey')) {
            $cond_row['order_id:LIKE'] = '%' . request_string('orderkey') . '%';
        }
        if (request_string('start_date')) {
            $cond_row['order_create_time:>'] = request_string('start_date');
        }
        if (request_string('end_date')) {
            $cond_row['order_create_time:<'] = request_string('end_date');
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

        $Yf_Page = new Yf_Page();
        $Yf_Page->listRows = 10;
        $rows = $Yf_Page->listRows;
        $offset = request_int('firstRow', 0);
        $page = ceil_r($offset / $rows);

        $data = array();
        //$cond_row['order_status'] = Order_StateModel::ORDER_FINISH;
        $cond_row['directseller_id'] = Perm::$userId;
        $Order_BaseModel = new Order_BaseModel();
        $data = $Order_BaseModel->getBaseList($cond_row, array('order_create_time' => 'DESC'), $page, $rows);
        $Yf_Page->totalRows = $data['totalsize'];
        $page_nav = $Yf_Page->prompt();
        $data['t'] = $status;

        if ($this->typ == "json") {
            $this->data->addBody(-140, $data);
        } else {
            include $this->view->getView();
        }
    }

    //设置分销店铺名称
    public function setShopName()
    {
        $userId = Perm::$userId;
        $shop_name = request_string('user_directseller_shop', '我的小店');

        $User_InfoModel = new User_InfoModel();
        $flag = $User_InfoModel->editInfo($userId, array('user_directseller_shop' => $shop_name));

        if ($flag !== false) {
            $msg = __('success');
            $status = 200;
        } else {
            $msg = __('failure');
            $status = 250;
        }
        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }
}

?>

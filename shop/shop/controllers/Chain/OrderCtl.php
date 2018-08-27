<?php if (!defined('ROOT_PATH')) exit('No Permission');

/**
 * @author     zcg <xinze@live.cn>
 */
class Chain_OrderCtl extends Chain_Controller
{
    public $chainBaseModel = null;
    public $orderGoodsModel = null;
    public $orderBaseModel = null;
    public $goodsBaseModel = null;
    public $goodsImagesModel = null;

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
        $this->chainBaseModel = new Chain_BaseModel();
        $this->orderBaseModel = new Order_BaseModel();
        $this->orderGoodsModel = new Order_GoodsModel();
        $this->goodsBaseModel = new Goods_BaseModel();
        $this->goodsImagesModel = new Goods_ImagesModel();
    }

    /**
     * @author houpeng
     * 读取门店对应的订单数据
     *
     * @access public
     */
    public function index()
    {
        $chain_id = Perm::$chainId;
        $search_state_type = request_string('search_state_type');
        $search_key_type = request_string('search_key_type');
        $keyword = request_string('keyword');
        $Order_StateModel = new Order_StateModel();
        $cond_row = array();
        $cond_row['order_status:='] = 11;
        $cond_row['chain_id:='] = $chain_id;

        if ($search_state_type == 'yes' && $chain_id) {
            $cond_row['order_status:='] = $Order_StateModel::ORDER_FINISH;
        }
        if ('buyer_phone' == $search_key_type) {
            $cond_row['order_receiver_contact:LIKE'] = '%' . $keyword . '%';
        } elseif ('order_sn' == $search_key_type) {
            $cond_row['order_id:LIKE'] = '%' . $keyword . '%';
        }
        $Yf_Page = new Yf_Page();
        $Yf_Page->listRows = 10;
        $rows = $Yf_Page->listRows;
        $offset = request_int('firstRow', 0);
        $page = ceil_r($offset / $rows);
        $data = $this->orderBaseModel->getBaseList($cond_row, array(), $page, $rows);

        $orderReturnModel = new Order_ReturnModel;
        $orderIds = array_column($data['items'], 'order_id');
        $orderRefundList =  $orderReturnModel->getByWhere([
            'order_number:IN'=> $orderIds,
            'return_state'=> Order_ReturnModel::RETURN_PLAT_PASS
        ]);
        $orderRefundMap = array();
        foreach ($orderRefundList as $orderRefund) {
            $orderRefundMap[$orderRefund['order_number']] = isset($orderRefundMap[$orderRefund['order_number']])
                ? $orderRefundMap[$orderRefund['order_number']] + $orderRefund['order_goods_num']
                : $orderRefund['order_goods_num'];
        }
        foreach ($data['items'] as $k=> $item) {
            $order_id = $item['order_id'];
            if (isset($orderRefundMap[$order_id])) {
                $data['items'][$k]['goods_list'][0]['order_goods_num'] -= $orderRefundMap[$order_id];
            }
        }

        $Yf_Page->totalRows = $data['totalsize'];
        $page_nav = $Yf_Page->prompt();
        include $this->view->getView();
    }

    /**
     * 订单处理界面
     *
     * @access public
     */
    public function orderManage()
    {
        $chain_id = Perm::$chainId;
        $order_id = request_string('order_id');

        $cond_row['chain_id:='] = $chain_id;
        $order_detai = $this->orderBaseModel->getOrderDetail($order_id);

        $orderReturnModel = new Order_ReturnModel;
        //很坑爹，现在又TM要加上 --> 门店自提订单退款中，门店管理中心的订单，点击自提操作，弹窗中在提货验证后添加语句【该笔订单正在退款中，退款件数2件。请注意正确的自提商品数量！】
        $orderRefundList =  $orderReturnModel->getByWhere([
            'order_number'=> $order_id,
            //'return_state'=> Order_ReturnModel::RETURN_PLAT_PASS
        ]);
        $orderRefundedMap = array(); //已经退款
        $orderRefundingMap = array(); //退款中
        foreach ($orderRefundList as $orderRefund) {
            //商家同意退款/退货并且平台审核通过的加入“已经退款”数组中。
            if ($orderRefund['return_state'] == Order_ReturnModel::RETURN_PLAT_PASS && $orderRefund['return_shop_handle'] == Order_ReturnModel::RETURN_SELLER_PASS) {
                $orderRefundedMap[$orderRefund['order_number']] = isset($orderRefundedMap[$orderRefund['order_number']])
                    ? $orderRefundedMap[$orderRefund['order_number']] + $orderRefund['order_goods_num']
                    : $orderRefund['order_goods_num'];
            }

            //平台为审核通过的退款/退货加入“退款中”数组中
            if($orderRefund['return_state'] != Order_ReturnModel::RETURN_PLAT_PASS ){
                $orderRefundingMap[$orderRefund['order_number']] = isset($orderRefundingMap[$orderRefund['order_number']])
                    ? $orderRefundingMap[$orderRefund['order_number']] + $orderRefund['order_goods_num']
                    : $orderRefund['order_goods_num'];
            }
        }


        $order_detai['goods_list'][0]['order_goods_num'] -= array_sum($orderRefundedMap);
        $order_detai['goods_list'][0]['refundedCount'] = array_sum($orderRefundedMap);
        $order_detai['goods_list'][0]['refundingCount'] = array_sum($orderRefundingMap);


        $cahin_base = current($this->chainBaseModel->getByWhere($cond_row));
        $chain_address[] = $cahin_base['chain_province'];
        $chain_address[] = $cahin_base['chain_city'];
        $chain_address[] = $cahin_base['chain_county'];
        $chain_address[] = $cahin_base['chain_address'];
        $chain_address = implode(' ', $chain_address);

        include $this->view->getView();
    }

    /**
     * 订单处理
     *
     * @access public
     */
    public function processOrder()
    {
        $order_state = new Order_StateModel();
        $pickup_code = request_string('pickup_code');
        $order_id = request_string('order_id');
        $shop_id = request_int('shop_id');
        $stock = request_int('stock');
        $goods_id = request_int('goods_id');
        $chain_id = Perm::$chainId;
        $now_time = date('Y-m-d H:i:s', time());
        $user_id  = Perm::$userId;

        //判断当前登录用户是否是门店账号
        $chain_user_model = new Chain_UserModel();
        $chain_user = current($chain_user_model->getByWhere(array("user_id"=>$user_id)));
        if (!is_array($chain_user)) {
            return $this->data->addBody(-1, array(), '当前登录的账号不是门店账号', 250);
        }
        $order_goodsChainCodeModel = new Order_GoodsChainCodeModel();
        $order_goodsChainCode = current($order_goodsChainCodeModel->getByWhere(array('order_id' => $order_id)));

        //判断该门店是否是当前订单自提门店
        if ($chain_user['chain_id'] != $order_goodsChainCode['chain_id']){
            return $this->data->addBody(-1, array(), '该订单不属于此门店', 250);
        }

        if ($pickup_code == $order_goodsChainCode['chain_code_id']) {
            //开启事物
            $this->orderBaseModel->sql->startTransactionDb();

            //修改订单表中的订单状态
            $order_info['order_status'] = $order_state::ORDER_FINISH;
            $order_info['order_finished_time'] = $now_time;
            $flag = $this->orderBaseModel->editBase($order_id, $order_info);

            //修改订单商品表中的订单状态
            $edit_row['order_goods_status'] = $order_state::ORDER_FINISH;
            $order_goods_id = $this->orderGoodsModel->getKeyByWhere(array('order_id' => $order_id));
            $this->orderGoodsModel->editGoods($order_goods_id, $edit_row);

            //将需要确认的订单号远程发送给Paycenter修改订单状态
            //远程修改paycenter中的订单状态
            $key = Yf_Registry::get('shop_api_key');
            $url = Yf_Registry::get('paycenter_api_url');
            $shop_app_id = Yf_Registry::get('shop_app_id');
            $formvars = array();

            $formvars['order_id'] = $order_id;
            $formvars['app_id'] = $shop_app_id;
            $formvars['from_app_id'] = Yf_Registry::get('shop_app_id');
            //判断门店自提订单的付款方式
            $Order_BaseModel = new Order_BaseModel();
            $order_base           = $Order_BaseModel->getOne($order_id);
            if($order_base['payment_id'] == PaymentChannlModel::PAY_CHAINPYA)
            {
                $formvars['payment'] = 0;
            }
            $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=confirmOrder&typ=json', $url), $formvars);

            //更改自提码使用状态
            $code_row['chain_code_status'] = $order_goodsChainCodeModel::CHAIN_CODE_USE;
            $code_row['chain_code_usetime'] = $now_time;
            $flag=$order_goodsChainCodeModel->editGoodsChainCode($order_goodsChainCode['chain_code_id'], $code_row);

            $orderGoodsModel = new Order_GoodsModel;
            $commonModel = new Goods_CommonModel;
            $orderGoods = $orderGoodsModel->getOneByWhere([
                'order_id'=> $order_id
            ]);

            $commonId = $orderGoods['common_id'];
            $goodsNum = $orderGoods['order_goods_num'];
            $commonModel->updateGoodsSaleNum($commonId, $goodsNum);

            if ($flag && $this->orderBaseModel->sql->commitDb()) {
                $msg = __('success');
                $status = 200;
            } else {
                $this->orderBaseModel->sql->rollBackDb();
                $m = $this->orderBaseModel->msg->getMessages();
                $msg = $m ? $m[0] : __('failure');
                $status = 250;
            }
        } else {
            if(!$pickup_code)
            {
                $msg = '验证码不能为空';
            }
            else
            {
                $msg = '验证码不正确';
            }
        }
        $this->data->addBody(-140, array(), $msg, $status);
    }
}

?>
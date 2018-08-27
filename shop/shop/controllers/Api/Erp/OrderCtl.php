<?php
/**
 * Created by PhpStorm.
 * User: tech05
 * Date: 2016-11-2
 * Time: 10:08
 */
class Api_Erp_OrderCtl extends Api_Controller{

    public $orderBaseModel;
    public $orderReturnModel;

    public function  __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);

        $this->orderBaseModel = new Order_BaseModel();
        $this->orderReturnModel = new Order_ReturnModel();
    }


    //erp下载订单
    public function downOrder()
    {

        $Order_BaseModel=new Order_BaseModel();
        if (request_string('end_created'))
        {
            $Order_BaseModel->sql->setWhere('order_create_time',request_string('end_created'),'<=');
        }
        if (request_string('start_created'))
        {
            $Order_BaseModel->sql->setWhere('order_create_time',request_string('start_created'),'>=');
        }
        if (request_row('store_account'))
        {
            $shop_account=request_row('store_account');
        }

        $User_BaseModel = new User_BaseModel();
        $User_BaseModel->sql->setWhere('user_account',$shop_account,'IN');
        $User_BaseModel->sql->setLimit(0,999999999);
        $User_Base = $User_BaseModel->getBase('*');
        $user_id  = array_column($User_Base,'user_id');

        $Shop_BaseModel = new Shop_BaseModel();
        $Shop_BaseModel->sql->setWhere('user_id',$user_id,'IN');
        $Shop_BaseModel->sql->setLimit(0,999999999);
        $Shop_Base = $Shop_BaseModel->getBase('*');
        $shop_id  = array_column($Shop_Base,'shop_id');

        $Order_BaseModel->sql->setWhere('order_status',array(8,9),'NOT IN');//不包括退款退货订单
        $Order_BaseModel->sql->setWhere('shop_id',$shop_id,'IN');
        $Order_BaseModel->sql->setLimit(0,999999999);
        $Order_Base = $Order_BaseModel->getBase('*');
        $order_id = array_column($Order_Base,'order_id');

        $Order_GoodsModel=new Order_GoodsModel();
        $Order_GoodsModel->sql->setLimit(0,999999999);
        $Order_GoodsModel->sql->setWhere('order_id',$order_id,'IN');
        $Order_Goods = $Order_GoodsModel->getGoods('*');

        $User_InfoModel = new User_InfoModel();
        $User_InfoModel->sql->setLimit(0,999999999);
        $User_Info  = $User_InfoModel->getInfo('*');

        $data = array();
        if($Order_Base){
            foreach($Order_Base as $key=>$value){
                $User_id = $Shop_Base[$value['shop_id']]['user_id'];
                $data['items'][$key]['order_id'] = $value['order_id'];
                $data['items'][$key]['shop_id'] = $value['shop_id'];
                $data['items'][$key]['store_account'] = $User_Base[$User_id]['user_account'];
                $data['items'][$key]['shop_name'] = $value['shop_name'];
                $data['items'][$key]['shop_mobile'] = $Shop_Base[$value['shop_id']]['shop_tel'];
                $data['items'][$key]['user_id'] = $value['buyer_user_id'];
                $data['items'][$key]['user_account'] = $value['buyer_user_name'];
                $data['items'][$key]['user_sex'] = $User_Info[$value['buyer_user_id']]['user_sex'];
                $data['items'][$key]['user_mobile'] = $User_Info[$value['buyer_user_id']]['user_mobile'];
                $data['items'][$key]['user_email'] = $User_Info[$value['buyer_user_id']]['user_email'];
                $data['items'][$key]['user_qq'] = $User_Info[$value['buyer_user_id']]['user_qq'];
                $data['items'][$key]['user_ww'] = $User_Info[$value['buyer_user_id']]['user_ww'];
                $data['items'][$key]['create_time'] = strtotime($value['order_create_time']);
                $data['items'][$key]['consignee_mobile'] = $value['order_receiver_contact'];
                $data['items'][$key]['consignee_tel'] = '';
                $data['items'][$key]['consignee'] = $value['order_receiver_name'];
                $data['items'][$key]['order_delivery_address_province'] = '';
                $data['items'][$key]['order_delivery_address_city'] = '';
                $data['items'][$key]['order_delivery_address_county'] = '';
                $data['items'][$key]['order_delivery_address_address'] = '';
                if($value['order_receiver_address']){
                    $order_delivery_address = explode(' ',$value['order_receiver_address']);
                    if($order_delivery_address[0]=='北京' ||$order_delivery_address[0]=='天津' ||$order_delivery_address[0]=='上海' ||$order_delivery_address[0]=='重庆'){
                        $data['items'][$key]['order_delivery_address_province'] = $order_delivery_address[0];
                        $data['items'][$key]['order_delivery_address_city'] = $order_delivery_address[0];
                        $data['items'][$key]['order_delivery_address_county'] = $order_delivery_address[1];
                        for($i=2;$i<count($order_delivery_address);$i++){
                            $data['items'][$key]['order_delivery_address_address'].=$order_delivery_address[$i];
                        }
                    }else{
                        $data['items'][$key]['order_delivery_address_province'] = $order_delivery_address[0];
                        $data['items'][$key]['order_delivery_address_city'] = $order_delivery_address[1];
                        $data['items'][$key]['order_delivery_address_county'] = $order_delivery_address[2];
                        for($i=3;$i<count($order_delivery_address);$i++){
                            $data['items'][$key]['order_delivery_address_address'].=$order_delivery_address[$i];
                        }
                    }
                }
                $data['items'][$key]['des'] = $value['order_message'];
                $data['items'][$key]['payment_id'] = $value['payment_id'];
                $data['items'][$key]['payment_name'] = $value['payment_name'];
                $data['items'][$key]['order_goods_amount'] = $value['order_goods_amount'];
                $data['items'][$key]['order_discount_amount'] = $value['order_discount_fee'];
                $data['items'][$key]['order_payment'] = $value['order_payment_amount'];
                $data['items'][$key]['order_shipping_fee_amount'] = $value['order_shipping_fee'];
                $data['items'][$key]['order_shipping_fee'] = '';
                $data['items'][$key]['voucher_id'] = $value['voucher_id'];
                $data['items'][$key]['voucher_number'] = $value['voucher_code'];
                $data['items'][$key]['voucher_price'] = $value['voucher_price'];
                $data['items'][$key]['order_point_add'] = $value['order_points_add'];
                $data['items'][$key]['payment_time'] = strtotime($value['payment_time'])>0?strtotime($value['payment_time']):'';
                if($value['order_status']==1){
                    $data['items'][$key]['status']=1;
                }else if($value['order_status']==2 || $value['order_status']==3){
                    $data['items'][$key]['status']=2;
                }else if($value['order_status']==4){
                    $data['items'][$key]['status']=3;
                }else if($value['order_status']==5 || $value['order_status']==6){
                    $data['items'][$key]['status']=4;
                }else if($value['order_status']==7){
                    $data['items'][$key]['status']=0;
                }
                if($value['order_finished_time'] != 0){
                    $data['items'][$key]['order_finished_time']=strtotime($value['order_finished_time']);
                }else{
                    $data['items'][$key]['order_finished_time']=0;
                }

                $data['items'][$key]['discounts']=$value['order_discount_fee'];
                $data['items'][$key]['order_type']=$value['order_is_virtual'];
                $goods_msg=array();
                foreach($Order_Goods as $k=>$v){
                    if($v['order_id']==$value['order_id']){
                        $goods_msg[$k]['id']=$v['order_goods_id'];
                        $goods_msg[$k]['order_id']=$v['order_id'];
                        $goods_msg[$k]['setmeal']=$v['goods_id'];
                        $goods_msg[$k]['pid']=$v['common_id'];
                        $goods_msg[$k]['name']=$v['goods_name'];
                        $goods_msg[$k]['pcatid']=$v['goods_class_id'];
                        $goods_msg[$k]['price']=$v['goods_price'];
                        $goods_msg[$k]['num']=$v['order_goods_num'];
                        $goods_msg[$k]['pic']=$v['goods_image'];
                        $goods_msg[$k]['status']=$data['items'][$key]['status'];
                    }
                }
                $data['items'][$key]['goods_msg']=$goods_msg;
            }
        }
        $this->data->addBody(-140, $data);
    }

    //下载退货订单
    public function downreorder()
    {

        $Order_ReturnModel=new Order_ReturnModel();
        if (request_string('start_created'))
        {
            $cond_row['return_add_time:>='] = request_string('start_created');
        }
        if (request_string('end_created'))
        {
            $cond_row['return_add_time:<='] = request_string('end_created');
        }
        if (request_row('store_account'))
        {
            $shop_account=request_row('store_account');
        }

        $User_BaseModel = new User_BaseModel();
        $User_BaseModel->sql->setWhere('user_account',$shop_account,'IN');
        $User_BaseModel->sql->setLimit(0,999999999);
        $User_Base = $User_BaseModel->getBase('*');
        $user_id  = array_column($User_Base,'user_id');

        $Shop_BaseModel = new Shop_BaseModel();
        $Shop_BaseModel->sql->setWhere('user_id',$user_id,'IN');
        $Shop_BaseModel->sql->setLimit(0,999999999);
        $Shop_Base = $Shop_BaseModel->getBase('*');
        $shop_id  = array_column($Shop_Base,'shop_id');

        $cond_row['seller_user_id:IN'] = $shop_id;
        $cond_row['return_type:='] = 2;

        $Order_Return = $Order_ReturnModel->getReturnList($cond_row, array(), 1, 999999999);
        $order_id = array_column($Order_Return,'order_number');
        $Order_GoodsModel=new Order_GoodsModel();
        $Order_GoodsModel->sql->setLimit(0,999999999);
        $Order_GoodsModel->sql->setWhere('order_id',$order_id,'IN');
        $Order_Goods = $Order_GoodsModel->getGoods('*');
        $Shop_BaseModel = new Shop_BaseModel();
        $Shop_BaseModel->sql->setLimit(0,999999999);
        $Shop_Base  = $Shop_BaseModel->getBase('*');
        $User_BaseModel = new User_BaseModel();
        $User_BaseModel->sql->setLimit(0,999999999);
        $User_Base  = $User_BaseModel->getBase('*');
        $data=array();
        if($Order_Return['items']){
            foreach($Order_Return['items'] as $key=>$value){
                $data['data'][$key]['order_id']=$value['order_number'];
                $data['data'][$key]['refund_id']=$value['return_code'];
                $data['data'][$key]['member_id']=$value['buyer_user_id'];
                $data['data'][$key]['seller_id']=$value['seller_user_id'];
                $data['data'][$key]['product_id']='';
                $data['data'][$key]['product_name']='';
                foreach($Order_Goods as $k=>$v){
                    if($v['order_goods_id']==$value['order_goods_id']){
                        $data['data'][$key]['product_id']=$v['order_goods_id'];
                        $data['data'][$key]['product_name']=$v['goods_name'];
                        $data['data'][$key]['goods_sku']=$v['order_spec_info'];
                    }
                }

                $data['data'][$key]['refund_price']=$value['return_cash'];
                $data['data'][$key]['reason']=$value['return_reason'];
                if($value['return_type']==1 || $value['return_type']==3){
                    $data['data'][$key]['goods_status']=0;
                }else{
                    $data['data'][$key]['goods_status']=1;
                }
                $data['data'][$key]['type']=2;
                $data['data'][$key]['create_time']=strtotime($value['return_add_time']);
                $data['data'][$key]['close_reason']='';
                $data['data'][$key]['refuse_reason']='';
                $data['data'][$key]['goods_image']=$value['order_goods_pic'];
                $data['data'][$key]['goods_price']=$value['order_goods_price'];
                $data['data'][$key]['goods_num']=$value['order_goods_num'];
                $data['data'][$key]['return_desc']='';
                $data['data'][$key]['warehouse_id']=0;
                $data['data'][$key]['return_express_id']='';
                $data['data'][$key]['return_logistic_num']='';
                $data['data'][$key]['exchangeOrderNum']='';
            }
        }
        $this->data->addBody(-140, $data);
    }
//下载退款订单
    public function downrefundorder()
    {

        $Order_ReturnModel=new Order_ReturnModel();
        if (request_string('start_created'))
        {
            $cond_row['return_add_time:>='] = request_string('start_created');
        }
        if (request_string('end_created'))
        {
            $cond_row['return_add_time:<='] = request_string('end_created');
        }
        if (request_row('store_account'))
        {
            $shop_account=request_row('store_account');
        }

        $User_BaseModel = new User_BaseModel();
        $User_BaseModel->sql->setWhere('user_account',$shop_account,'IN');
        $User_BaseModel->sql->setLimit(0,999999999);
        $User_Base = $User_BaseModel->getBase('*');
        $user_id  = array_column($User_Base,'user_id');

        $Shop_BaseModel = new Shop_BaseModel();
        $Shop_BaseModel->sql->setWhere('user_id',$user_id,'IN');
        $Shop_BaseModel->sql->setLimit(0,999999999);
        $Shop_Base = $Shop_BaseModel->getBase('*');
        $shop_id  = array_column($Shop_Base,'shop_id');

        $cond_row['seller_user_id:IN'] = $shop_id;


        $cond_row['return_type:='] = 1;

        $Order_Return = $Order_ReturnModel->getReturnList($cond_row, array(), 1, 999999999);

        $order_id = array_column($Order_Return,'order_number');
        $Order_GoodsModel=new Order_GoodsModel();
        $Order_GoodsModel->sql->setLimit(0,999999999);
        $Order_GoodsModel->sql->setWhere('order_id',$order_id,'IN');
        $Order_Goods = $Order_GoodsModel->getGoods('*');
        $Shop_BaseModel = new Shop_BaseModel();
        $Shop_BaseModel->sql->setLimit(0,999999999);
        $Shop_Base  = $Shop_BaseModel->getBase('*');
        $User_BaseModel = new User_BaseModel();
        $User_BaseModel->sql->setLimit(0,999999999);
        $User_Base  = $User_BaseModel->getBase('*');
        $data=array();
        if($Order_Return['items']){
            foreach($Order_Return['items'] as $key=>$value){
                $data['data'][$key]['order_id']=$value['order_number'];
                $data['data'][$key]['refund_id']=$value['return_code'];
                $data['data'][$key]['member_id']=$value['buyer_user_id'];
                $data['data'][$key]['seller_id']=$value['seller_user_id'];
                $data['data'][$key]['product_id']='';
                $data['data'][$key]['product_name']='';
                foreach($Order_Goods as $k=>$v){
                    if($v['order_goods_id']==$value['order_goods_id']){
                        $data['data'][$key]['product_id']=$v['order_goods_id'];
                        $data['data'][$key]['product_name']=$v['goods_name'];
                        $data['data'][$key]['goods_sku']=$v['order_spec_info'];
                    }
                }

                $data['data'][$key]['refund_price']=$value['return_cash'];
                $data['data'][$key]['reason']=$value['return_reason'];

                $data['data'][$key]['type']=1;
                $data['data'][$key]['create_time']=strtotime($value['return_add_time']);
                $data['data'][$key]['close_reason']='';
                $data['data'][$key]['refuse_reason']='';
                $data['data'][$key]['status']=$value['return_state'];
                $data['data'][$key]['goods_image']=$value['order_goods_pic'];
                $data['data'][$key]['goods_price']=$value['order_goods_price'];
                $data['data'][$key]['goods_num']=$value['order_goods_num'];
                $data['data'][$key]['return_desc']='';
                $data['data'][$key]['warehouse_id']=0;
            }
        }
        $this->data->addBody(-140, $data);
    }
    /**
     * 更新订单状态
     *
     * erp审核更新商城订单状态待发货（待开发）
     * erp发货更新商城订单状态已发货，同时更新物流公司、物流单号、发货时间、最晚收货时间
     * erp退款、退货审核更新商城退款、退货订单状态
     */
    public function updateOrderState()
    {
        $operation  = request_string('operation');

        $update_order = array();

        if ($operation == 'audit') {

        } else if ( $operation == 'consignment' ) {

            $order_number = request_string('order_number');
            $logistics_no = request_string('logistics_no');
            $express_name = request_string('express_name');

            $order_data = $this->orderBaseModel->getByWhere( array('order_id'=> $order_number) );

            if ( empty($order_data) ) {
                return $this->data->addBody(-140, array(), '单据不存在！', 250);
            }

            $expressModel = new ExpressModel();
            $shop_ExpressModel = new Shop_ExpressModel();
            $shop_express_rows = $shop_ExpressModel->getByWhere();
            $express_ids = array_column($shop_express_rows, 'express_id');

            $express_rows = $expressModel->getExpress($express_ids);
            $KExpressName_VId = array_column($express_rows, 'express_id', 'express_name');

            $confirm_order_time = Yf_Registry::get('confirm_order_time');

            $update_order['order_status'] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
            $update_order['order_shipping_time'] = date('Y-m-d H:i:s');
            $update_order['order_receiver_date'] = date('Y-m-d H:i:s', time() + $confirm_order_time);
            $update_order['order_shipping_code'] = $logistics_no;
            $update_order['order_shipping_express_id'] = empty($KExpressName_VId[$express_name]) ? -1 : $KExpressName_VId[$express_name];

            $order_id = $order_number;
            $flag = $this->orderBaseModel->editBase($order_id, $update_order);

        } else if ($operation == 'service') {
            $orderReturnModel = new Order_ReturnModel();
            $order_number = request_string('order_id');
            $order_goods_id = request_string('product_id');
            $order_return = $orderReturnModel->getByWhere(array('order_number'=>$order_number,'order_goods_id'=>$order_goods_id));
            if($order_return){
                foreach($order_return as $k=>$v){
                    $order_return_id = $v['order_return_id'];
                    $return_shop_message = $v['return_reason'];
                    $user_id = $v['seller_user_id'];
                }
                $User_BaseModel = new User_BaseModel();
                $user_info = $User_BaseModel->getOne($user_id);
                $flag = $this->erpAgreeReturn($order_return_id,$return_shop_message,$user_id,$user_info['user_account']);
            }
        } else if ($operation == 'service_failure') {
            $orderReturnModel = new Order_ReturnModel();
            $order_number = request_string('order_id');
            $order_goods_id = request_string('product_id');
            $order_return = $orderReturnModel->getByWhere(array('order_number'=>$order_number,'order_goods_id'=>$order_goods_id));
            if($order_return){
                foreach($order_return as $k=>$v){
                    $order_return_id = $v['order_return_id'];
                    $return_shop_message = $v['return_reason'];
                }

                $flag = $this->erpCloseReturn($order_return_id,$return_shop_message);
            }
        } else if ($operation == 'refundmoney') {
            $orderReturnModel = new Order_ReturnModel();
            $order_number = request_string('order_id');
            $order_goods_id = request_string('product_id');
            $order_return = $orderReturnModel->getByWhere(array('order_number'=>$order_number,'order_goods_id'=>$order_goods_id));
            if($order_return){
                foreach($order_return as $k=>$v){
                    $order_return_id = $v['order_return_id'];
                    $return_shop_message = $v['return_reason'];
                    $user_id = $v['seller_user_id'];
                }
                $User_BaseModel = new User_BaseModel();
                $user_info = $User_BaseModel->getOne($user_id);
                if(request_string('return_state') == 2){
                    $flag = $this->erpAgreeReturn($order_return_id,$return_shop_message,$user_id,$user_info['user_account']);
                }elseif(request_string('return_state') == 3){
                    $flag = $this->erpCloseReturn($order_return_id,$return_shop_message);
                }

            }

        }

        if ($flag!==false) {
            $msg = 'success';
            $status = 200;
        } else {
            $msg = 'failure';
            $status = 250;
        }

        $this->data->addBody(-140, array(), $msg, $status);
    }

    /*
     * 同意退款
     *
     */
    public function erpAgreeReturn($order_return_id,$return_shop_message,$user_id,$user_name)
    {
        $Order_StateModel    = new Order_StateModel();
        $return              = $this->orderReturnModel->getOne($order_return_id);
        if($return['return_state'] == Order_ReturnModel::RETURN_SELLER_PASS){
            $msg    = __('已经退款，请刷新页面。');

            $status = 200;
            $this->data->addBody(-140, array(), $msg, $status);
            return false;
        }

        $rs_row = array();

        $msg = '';
        $order_finish = false;
        $shop_return_amount = 0;
        $money = 0;

        //开启事物
        $this->orderReturnModel->sql->startTransactionDb();

        $matche_row = array();
        //有违禁词
        if (Text_Filter::checkBanned($return_shop_message, $matche_row))
        {

            $msg    = __('含有违禁词');

            $status = 250;
            $this->data->addBody(-140, array(), $msg, $status);
            return false;
        }

        //判断该笔退款金额的订单是否已经结算
        $Order_BaseModel = new Order_BaseModel();
        $order_base = $Order_BaseModel->getOne($return['order_number']);

        //判断该笔订单是否已经收货，如果没有收货的话，不扣除卖家资金。已确认收货则扣除卖家资金
        if($order_base['order_status'] == $Order_StateModel::ORDER_FINISH )
        {
            $order_finish = false;

            //获取商家的账户资金资源
            $key                 = Yf_Registry::get('shop_api_key');
            $formvars            = array();

            $formvars['user_id'] = $user_id;
            $formvars['app_id'] = Yf_Registry::get('shop_app_id');

            $money_row = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Info&met=getUserResourceInfo&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
            $user_money = 0;
            $user_money_frozen = 0;
            if ($money_row['status'] == '200')
            {
                $money = $money_row['data'];

                $user_money        = $money['user_money'];
                $user_money_frozen = $money['user_money_frozen'];
            }

            $shop_return_amount = $return['return_cash'] - $return['return_commision_fee'];

            //获取该店铺最新的结算结束日期
            $Order_SettlementModel = new Order_SettlementModel();
            $settlement_last_info = $Order_SettlementModel->getLastSettlementByShopid(Perm::$shopId, $return['order_is_virtual']);

            if($settlement_last_info)
            {
                $settlement_unixtime = $settlement_last_info['os_end_date'] ;
            }
            else
            {
                $settlement_unixtime = '';
            }

            $settlement_unixtime = strtotime($settlement_unixtime);
            $order_finish_time = $order_base['order_finished_time'];
            $order_finish_unixtime = strtotime($order_finish_time);

            fb($settlement_unixtime);
            fb($order_finish_unixtime);
            if($settlement_unixtime >= $order_finish_unixtime )
            {
                //结算时间大于订单完成时间。需要扣除卖家的现金账户
                $money = $user_money;
                $pay_type = 'cash';
            }
            else
            {
                //结算时间小于订单完成时间。需要扣除卖家的冻结资金,如果冻结资金不足就扣除账户余额
                $money = $user_money_frozen + $user_money;
                $pay_type = 'frozen_cash';
            }
            fb($pay_type);
        }
        else
        {
            $order_finish = true;
        }



        $shop_return_amount = sprintf("%.2f",$shop_return_amount);
        $money = sprintf("%.2f",$money);


        if(($shop_return_amount <= $money) || $order_finish)
        {
            $data['return_shop_message'] = $return_shop_message;
            if ($return['return_goods_return'] == Order_ReturnModel::RETURN_GOODS_RETURN)
            {
                $data['return_state'] = Order_ReturnModel::RETURN_SELLER_PASS;
            }
            else
            {
                $data['return_state'] = Order_ReturnModel::RETURN_SELLER_GOODS;
            }
            $data['return_shop_handle'] = Order_ReturnModel::RETURN_SELLER_PASS;
            $data['return_shop_time'] = get_date_time();
            $flag                     = $this->orderReturnModel->editReturn($order_return_id, $data);
            check_rs($flag,$rs_row);

            //如果订单为分销商采购单，扣除分销商的钱
            if($order_base['order_source_id'])
            {
                fb($return);
                fb('return');
                $dist_order = $Order_BaseModel -> getOneByWhere(array('order_id'=>$order_base['order_source_id']));

                fb($data);
                fb('data');
                fb($dist_order);
                fb('dist_order');
                if(!empty($dist_order)){
                    $dist_return_order = $this->orderReturnModel->getOneByWhere(array('order_number'=>$dist_order['order_id'],'return_type'=>$return['return_type']));

                    fb($dist_return_order);
                    fb('$dist_return_order');
                    $flag = $this->orderReturnModel->editReturn($dist_return_order['order_return_id'], $data);
                    check_rs($flag,$rs_row);
                }
            }

            if($flag && !$order_finish)
            {
                //扣除卖家的金额
                $key                 = Yf_Registry::get('shop_api_key');
                $formvars            = array();

                $formvars['user_id'] = $user_id;
                $formvars['user_name'] = $user_name;
                $formvars['app_id'] = Yf_Registry::get('shop_app_id');
                $formvars['money'] = $shop_return_amount * (-1);
                $formvars['pay_type'] = $pay_type;
                $formvars['reason'] = '退款';
                $formvars['order_id'] = $order_base['order_id'];
                $formvars['goods_id'] = $return['order_goods_id'];

                $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Info&met=editReturnUserResourceInfo&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);

                $dist_rs['status'] = 200;
                if(isset($dist_return_order) && !empty($dist_return_order)){
                    $key                 = Yf_Registry::get('shop_api_key');
                    $formvars            = array();
                    $user_id             = $user_id;
                    $formvars['user_id'] = $dist_order['seller_user_id'];
                    $formvars['user_name'] = $dist_order['seller_user_name'];
                    $formvars['money'] = ($dist_return_order['return_cash']-$dist_return_order['return_commision_fee'])*(-1);
                    $formvars['order_id'] = $dist_order['order_id'];
                    $formvars['goods_id'] =0;
                    $formvars['app_id'] = Yf_Registry::get('shop_app_id');
                    $formvars['pay_type'] = $pay_type;
                    $formvars['reason'] = '退款';

                    $dist_rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Info&met=editReturnUserResourceInfo&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
                }

                if($rs['status'] == 200 && $dist_rs['status']==200)
                {
                    $flag = true;
                }
                else
                {
                    $flag = false;
                }
                check_rs($flag,$rs_row);
            }
        }
        else
        {
            $flag = false;
            $msg    = __('账户余额不足');
            check_rs($flag,$rs_row);
        }




        $flag = is_ok($rs_row);
        if ($flag && $this->orderReturnModel->sql->commitDb())
        {
            $status = 200;
            $msg    = __('success');
            //退款退货提醒
            $message = new MessageModel();
            $message->sendMessage('Refund return reminder', $return['buyer_user_id'], $return['buyer_user_account'], $order_id = NULL, $shop_name = NULL, 0, MessageModel::ORDER_MESSAGE);
        }
        else
        {
            $this->orderReturnModel->sql->rollBackDb();
            $status = 250;
            $msg    = $msg ? $msg : __('failure');
        }

        $data = array();

        $this->data->addBody(-140, $data, $msg, $status);

    }

    /*
     *
     * 不同意退款
     * */
    public function erpCloseReturn($order_return_id,$return_shop_message)
    {
        $Order_StateModel    = new Order_StateModel();
        $matche_row = array();
        //有违禁词
        if (Text_Filter::checkBanned($return_shop_message, $matche_row))
        {
            $data   = array();
            $msg    = __('failure');
            $status = 250;
            $this->data->addBody(-140, array(), $msg, $status);
            return false;
        }

        $data['return_shop_message'] = $return_shop_message;
        $data['return_state']        = Order_ReturnModel::RETURN_SELLER_UNPASS;
        $data['return_shop_handle']        = Order_ReturnModel::RETURN_SELLER_UNPASS;
        $data['return_shop_time'] = get_date_time();

        $rs_row = array();
        $this->orderReturnModel->sql->startTransactionDb();
        $edit_flag = $this->orderReturnModel->editReturn($order_return_id, $data);
        check_rs($edit_flag, $rs_row);

        /*if ($return['order_goods_id'])
        {
            $order                              = $this->orderBaseModel->getOne($return['order_number']);
            $goods_field['goods_refund_status'] = Order_GoodsModel::REFUND_NO;
            $edit_flag                          = $this->orderGoodsModel->editGoods($return['order_goods_id'], $goods_field);
            check_rs($edit_flag, $rs_row);
        }
        else
        {
            $order_field['order_refund_status'] = Order_BaseModel::REFUND_NO;
            $edit_flag                          = $this->orderBaseModel->editBase($return['order_number'], $order_field);
            check_rs($edit_flag, $rs_row);
        }*/
        $flag = is_ok($rs_row);
        if ($flag && $this->orderReturnModel->sql->commitDb())
        {
            $status = 200;
            $msg    = __('success');
        }
        else
        {
            $this->orderReturnModel->sql->rollBackDb();
            $status = 250;
            $msg    = __('failure');
        }


        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);

    }


}
<?php
/**
 * Created by PhpStorm.
 * User: 大爷
 * Date: 2018/5/21
 * Time: 11:44
 */

class Supplier
{
    public $userId;
    public $apiId;
    public $apiKey;
    public $apiUrl;

    public function __construct()
    {
        $this->userId = Perm::$userId;
        $this->apiId = Yf_Registry::get('supplier_app_id');
        $this->apiKey = Yf_Registry::get('supplier_api_key');
        $this->apiUrl = Yf_Registry::get('supplier_api_url');
    }

    /**
     * @param $ctl
     * @param $met
     * @param $parameters
     * @return array
     */
    public function api($ctl, $met, $parameters)
    {
        $parameters = array_merge($parameters, ['user_id' => $this->userId, 'app_id'=> $this->apiId]);
        return get_url_with_encrypt($this->apiKey, sprintf('%s?ctl=%s&met=%s&typ=json', $this->apiUrl, $ctl, $met), $parameters);
    }

    /**
     * 生成分销商进货订单
     * //该方法生成的是分销商在供货商出进货的订单，分销商为买家，供货商为卖家
     */
    public function addOrder($goods_id, $num, $distributor_id, $rec_name, $rec_address, $rec_phone, $addr_id, $pay_way_id, $p_order_id, $invoice, $invoice_title, $invoice_content)
    {
        $parameters = [
            'goods_id' => $goods_id,
            'num' => $num,
            'distributor_id' => $distributor_id,
            'rec_name' => $rec_name,
            'rec_address' => $rec_address,
            'rec_phone' => $rec_phone,
            'addr_id' => $addr_id,
            'pay_way_id' => $pay_way_id,
            'p_order_id' => $p_order_id,
            'invoice' => $invoice,
            'invoice_title' => $invoice_title,
            'invoice_content' => $invoice_content,
        ];


        $Shop_BaseModel = new Shop_BaseModel();
        $Goods_BaseModel = new Goods_BaseModel();
        $address_id = $addr_id;                    //买家收货地址id

        //分销商（买家数据）

        $distributor_shop_info = $Shop_BaseModel->getOne($distributor_id);//分销商店铺
        $goodsbaseinfo = $Goods_BaseModel->getGoodsDetailInfoByGoodId($goods_id);//商品详情$data['goods_base']，$data['common_base']，$data['shop_base']，$data['mansong_info']

        $parameters['distributor_shop_info'] = $distributor_shop_info;
        $parameters['goodsbaseinfo'] = $goodsbaseinfo;

        $user_id = $distributor_shop_info['user_id']; //分销商店铺用户user_id
        $user_account = $distributor_shop_info['user_name'];  //分销商店铺用户user_name


        //查找收货地址,计算运费
        $User_AddressModel = new User_AddressModel();
        $Transport_TemplateModel = new Transport_TemplateModel();
        $city_id = 0;
        if ($address_id) {
            $user_address = $User_AddressModel->getOne($address_id);
            $city_id = $user_address['user_address_city_id'];
        }
        $parameters['city_id'] = $city_id;

        $res = $this->api('Api_Supplier', 'addOrder', $parameters);

        return $res['status'] == 200
            ? $res['data']['order_id']
            : false;
    }

    /**
     * 获取订单
     * @param $where
     * @return array
     */
    public function getOrderList($where)
    {
        $res = $this->api('Api_Supplier', 'getOrder', ['where' => $where]);

        return $res['data']['order_list'];
    }

    /**
     * 修改订单
     */
    public function editOrder($order_ids, $data)
    {
        $order_ids = is_array($order_ids) ? $order_ids : [$order_ids];
        $res = $this->api('Api_Supplier', 'editOrder', ['order_ids' => $order_ids, 'data'=> $data]);

        return $res['status'] == 200
            ? true
            : false;
    }

    /**
     * 修改订单
     */
    public function editOrderStatusAferPay($order_id = null, $uorder_id = null)
    {
        $res = $this->api('Api_Supplier', 'editOrderStatusAferPay', ['order_id' => $order_id, 'uorder_id'=> $uorder_id]);
        return $res['status'] == 200
            ? true
            : false;
    }

    public function getOrderGoodsKeyByWhere($where)
    {
        $res = $this->api ('Api_Supplier', 'getOrderGoodsKeyByWhere', ['where' => $where]);

        return $res['data']['order_goods_id'];
    }

    public function editOrderGoods($id, $data)
    {
        $res = $this->api ('Api_Supplier', 'editOrderGoods', ['id' => $id, 'data' => $data]);

        return $res['status'] == 200
            ? true
            : false;
    }
}
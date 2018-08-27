<?php

/**
 *  拼团
 *  @author Str <tech40@yuanfeng021.com>
 */
class PinTuanCtl extends Controller
{
	public function __construct(&$ctl, $met, $typ)
	{
            parent::__construct($ctl, $met, $typ);
            $this->nav = $this->navIndex();
            $this->cat = $this->catIndex();
            $this->initData();
            $this->web = $this->webConfig();
            $this->pinTuanBaseModel = new PinTuan_Base();
            $this->pinTuanDetailModel = new PinTuan_Detail();
            $this->pinTuanMarkModel = new PinTuan_Mark();
            $this->pinTuanBuyerModel = new PinTuan_Buyer();
            $this->goodsBaseModel = new Goods_BaseModel();
                
	}

    /*
     *  拼团主页面
     */
    public function index() {
        $data = array();
        //类目
        $data['category'] = $this->pinTuanBaseModel->getCategory();
        //banner大图
        $data['banner'] = $this->pinTuanBaseModel->getBanner();

        $data['banner'] = array_values($data['banner']);

        //拼团大图列表
        $recommend_goods = $this->pinTuanBaseModel->getRecommend();
        //获取商品的销量
        $data['recommend'] = $this->pinTuanDetailModel->getSaleNumsByList($recommend_goods);
        //商品
        $cat_id = request_int('cat_id',0);

        $goods = $this->pinTuanBaseModel->getGoodsList(array(),$cat_id);
        foreach($goods as $k=>$v)
        {
            if($v['goods']['goods_stock'] <= 0)
            {
                $this->pinTuanBaseModel->editBase($k,array('status'=>0));
            }
        }
        //获取商品的销量
        $data['goods'] = $this->pinTuanDetailModel->getSaleNumsByList($goods);
        $data['goods'] = array_values($data['goods']);
        $data['cat_id'] = $cat_id;

        if($this->typ == 'json'){
            return $this->data->addBody(-140,$data);
        } else {
            include $this->view->getView();
        }
    }

    /**
     *  参团(去拼团)
     *  拼团详细信息
     */
    public function pinTuanDetail(){
        $detail_id = request_int('detail_id');
        $mark_id = request_int('mark_id');
        if($detail_id < 0 || $mark_id < 0){
            return $this->data->addBody(-140,array('code'=>1),__('参数有误'),250);
        }
        //检验mark_id是否属于该detail拼团
        $mark_info = $this->pinTuanMarkModel->getOne($mark_id);
        if($mark_info['detail_id'] != $detail_id){
            return $this->data->addBody(-140,array('code'=>2),__('参数有误'),250);
        }
        $data = array();
        //活动详情
        $detail = $this->pinTuanDetailModel->getPtByDetailId($detail_id);
        $data['pintuan'] = $detail;
        //商品详情
        $goods_model = new Goods_BaseModel();
        $goods = $goods_model->getGoodsAndCommon($detail['goods_id']);
        $data['goods'] = $goods;
        //团员列表
        $buyer_list = $this->pinTuanBuyerModel->getBuyerByMarkId($mark_id);
        $data['buyer_list'] = $buyer_list;
        //推荐商品（其他拼团商品）
        $where = array(
            'status'=>self::$statusEnabled,
            'start_time:<'=>date('Y-m-d H:i:s'),
            'end_time:>'=>date('Y-m-d H:i:s')
        );
        $goods_list = $this->pinTuanBaseModel->getGoodsList($where);
        $data['goods_list'] = $goods_list;
        
        return $this->data->addBody(-140,$data);
    }
    
    /**
     *  拼团规则
     */
    public function rule(){
        $data['rule'] = Web_ConfigModel::value('pintuan_rule_description');
        return $this->data->addBody(-140,$data);
    }
    

    /**
     *  通过detail_id获取团长列表
     *  
     */
    public function markList(){
        $detail_id = request_int('detail_id');
        $page = request_int('page',1);
        $rows = 20;
        $mark_list = $this->pinTuanMarkModel->getMarkDetail(array('detail_id'=>$detail_id,'status'=>0),array('num'=>'desc'),$page,$rows);
        $data = is_array($mark_list) ? array_values($mark_list) : array();

        foreach($data as $k=>$v){
            $end_time = strtotime($v['end_time']);
            $remain_time =$end_time-time();
            $data[$k]['hour'] = floor($remain_time/3600);
            $data[$k]['min'] = floor(($remain_time-$data[$k]['hour']*3600)/60);
            $data[$k]['second'] = $remain_time - $data[$k]['hour']*3600 -  $data[$k]['min']*60;
        }

        return $this->data->addBody(-140,$data);
    }

    /**
     *  通过mark_id获取拼团详情
     *  
     */
    public function pintuanInfo(){
        $mark_id = request_int('mark_id');
        $detail_id = request_int('detail_id');

        $pintuan_detail = $this->pinTuanDetailModel->getDetail($detail_id);
        $pintuan_goods_id = $pintuan_detail[$detail_id]['goods_id'];
        $data = $this->pinTuanDetailModel->getOneDetailInfo($detail_id);
        $data['goods_detail'] = $this->goodsBaseModel->getGoodsDetailByGoodId($pintuan_goods_id);

        $buyer = $this->pinTuanBuyerModel->getBuyerInfoByMarkId($mark_id);
        foreach($buyer as $key=>$val){
            $v[] = $val;
            $v_id[] = $val['user_id'];
        }
        $data['buyer'] = $v;
        $data['buyer_id'] = $v_id;
        $data['lack'] = ($data['person_num'] - count($v)) > 0 ? ($data['person_num'] - count($v)) : 0;
        //已经拼团多少
        $buyer_sum = $this->pinTuanBuyerModel->getCount(array('detail_id'=>$detail_id));
        $data['buyer_sum'] = $buyer_sum;

        //拼团商品
        $goods = $this->pinTuanBaseModel->getGoodsList();
        //获取商品的销量
        $goods_list = $this->pinTuanDetailModel->getSaleNumsByList($goods);
        foreach($goods_list as $key=>$value){
            $list[] = $value;
        }
        $data['goods'] = $list;
        $end_time = strtotime($data['end_time']);
        $remain_time =$end_time - time();
        $data['timestamp'] = $remain_time;

        return $this->data->addBody(-140,$data);
    }
    
    /**
     * 确认拼团订单
     */
//    public function confirmPintuanOrder()
//    {
//        $goods_id = request_string('goods_id');
//        $goodsModel = new Goods_BaseModel;
//        $goodsData = $goodsModel->getOne($goods_id);
//
//        if (! $goodsData) {
//            return $this->data->addBody(-140, [], '失效商品', 250);
//        }
//
//        $shop_id = $goodsData['shop_id'];
//        $pintuanMoel = new PinTuan_Base;
//        $pintuanData = $pintuanMoel->getOneByWhere([ //店铺在时间段内只能有一个拼团活动是有效的
//            'shop_id'=> $shop_id,
//            'status'=> PinTuan_Base::$statusEnabled
//        ]);
//
//        if (! $pintuanData) {
//            return $this->data->addBody(-140, [], '该店铺没有拼团活动', 250);
//        }
//        $pintuanId = $pintuanData['id'];
//        $pintuanDetailModel = new PinTuan_Detail;
//        $pintuanDetail = $pintuanDetailModel->getOneByWhere([
//            'goods_id'=> $goods_id,
//            'pintuan_id'=> $pintuanId
//        ]);
//
//        $shopModel = new Shop_BaseModel;
//        $shopData = $shopModel->getOne($shop_id);
//
//        //获取用户默认地址
//        $userAddressModel = new User_AddressModel;
//        $defaultAddress = $userAddressModel->getByWhere([
//            'user_id'=>Perm::$userId,
//            //'user_address_default'=> User_AddressModel::DEFAULT_ADDRESS,
//        ]);
//        $defaultAddress = array_values($defaultAddress);
//
//        $result = [
//            'pintuan'=> array_merge($pintuanData, $pintuanDetail),
//            'goods'=> $goodsData,
//            'shop'=> $shopData,
//            'address'=> $defaultAddress
//        ];
//
//        $this->data->addBody(-140, $result, 'success', 200);
//    }

    /**
     * 获取用户收货地址
     */
//    public function getUserAddressList()
//    {
//        $userAddressModel = new User_AddressModel;
//        $addressRows = $userAddressModel->getByWhere([
//            'user_id'=> Perm::$userId
//        ]);
//        $addressRows = array_values($addressRows);
//        $this->data->addBody(-140, $addressRows, 'success', 200);
//    }
//



    
}
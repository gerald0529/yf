<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

class Seller_Promotion_PinTuanCtl extends Seller_Controller
{

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

        $this->goodsBaseModel     = new Goods_BaseModel();
        $this->shopCostModel      = new Shop_CostModel();
        $this->shopBaseModel      = new Shop_BaseModel();
        $this->pinTuanBaseModel   = new PinTuan_Base();
        $this->pinTuanDetailModel = new PinTuan_Detail();
        $this->PinTuanBuyerModel  = new PinTuan_Buyer;
        $this->PinTuanComboModel  = new PinTuan_Combo();
        $this->GoodsCommonModel   = new Goods_CommonModel();

        $this->shopInfo        = $this->shopBaseModel->getOne(Perm::$shopId);//店铺信息
        //拼团过期
        $pintuanInfo = $this->pinTuanBaseModel->getByWhere(array('shop_id'=>Perm::$shopId));
        foreach($pintuanInfo as $k=>$v){
            if(strtotime($v['end_time']) < time())
            {
                $this->pinTuanBaseModel->editBase($v['id'],[
                        'status'=>0
                    ]);
            }
        }
    }


    /**
     * 首页
     *活动列表
     * @access public
     */
    public function index()
    {
        $combo_row = array();

        //分页
        $Yf_Page           = new Yf_Page();
        $Yf_Page->listRows = request_int('listRows')?request_int('listRows'):10;
        $rows              = $Yf_Page->listRows;
        $offset            = request_int('firstRow', 0);
        $page              = ceil_r($offset / $rows);

        $cond_row['shop_id'] = Perm::$shopId;

        if (request_string('op') == 'detail' && request_int('id'))
        {
            $pintuan_id = request_int('id');
            $data = array();
            $data = $this->pinTuanBaseModel->getPinTuanDetailList($pintuan_id);
            $data['goods'] = $this->goodsBaseModel->getGoodsSpecByGoodId($data['detail']['goods_id']);
            $this->view->setMet('detail');
        }
        else
        {
            if(request_string('status') !== '')
            {
                $cond_row['status'] = request_string('status');
            }
            if(request_string('keyword') !== '')
            {
                $cond_row['name:LIKE'] = request_string('keyword') . '%';
            }
            $data = $this->pinTuanBaseModel->getPinTuanGoodsList($cond_row, array('id' => 'DESC'), $page, $rows);
            foreach($data['items'] as $k=>$v){
                if(strtotime($v['end_time']) < time())
                {
                    $this->pinTuanBaseModel->editBase($v['id'],[
                            'status'=>0
                        ]);
                }
            }

            $Yf_Page->totalRows = $data['totalsize'];
            $page_nav           = $Yf_Page->prompt();

        }
        
        if('json' == $this->typ)
        {
            $json_data['data']      = $data;
            $json_data['shop_type'] = $shop_type;
            $json_data['combo_flag']= $this->comboFlag;
            $json_data['combo']     = $combo_row;
            $this->data->addBody(-140, $json_data);
        }
        else
        {
            include $this->view->getView();
        }
    }

    //添加活动
    public function add()
    {
        $combo['combo_end_time'] = date("Y-m-d H:i:s", strtotime("11 june 2030"));
        include $this->view->getView();
    }


    //店铺商品信息和已参加活动的商品
    public function getShopGoods()
    {
        $cond_row = array();

        //分页
        $Yf_Page           = new Yf_Page();
        $Yf_Page->listRows = request_int('listRows')?request_int('listRows'):8;
        $rows              = $Yf_Page->listRows;
        $offset            = request_int('firstRow', 0);
        $page              = ceil_r($offset / $rows);
        //需要加入商品状态限定
        $shop_id = Perm::$shopId;
        $cond_row['common_state']  = Goods_CommonModel::GOODS_STATE_NORMAL;
        $cond_row['common_verify'] = Goods_CommonModel::GOODS_VERIFY_ALLOW;
        $cond_row['shop_status']   = Shop_BaseModel::SHOP_STATUS_OPEN;
        $cond_row['common_is_virtual'] = 0;//非虚拟商品
        $cond_row['shop_id'] = $shop_id;
        //非分销商品
        $supplier_end_list = $this->GoodsCommonModel->getSupplierSendCommonByShopId($shop_id);
        $supplier_end_common_id = is_array($supplier_end_list) ? array_column($supplier_end_list, 'common_id') : array();
        if($supplier_end_common_id){
            $cond_row['common_id:not in'] = $supplier_end_common_id;
        }

        //所有非分销商品comon_id
        $goods_rows = $this->GoodsCommonModel->getCommonNormal($cond_row, array('common_id' => 'DESC'));
        $all_common_ids = is_array($goods_rows['items']) ? array_column($goods_rows['items'],'common_id') : array();

        //活动中商品
        $active_ids = $this->pinTuanBaseModel->getActiveIds($shop_id);
        $active_goods_id = $active_ids['goods_id'];

        $goods_name = request_string('goods_name');
        if ($goods_name)
        {
            $pintuan_goods_row['goods_name:LIKE'] = "%".$goods_name . "%";
        }
        $pintuan_goods_row['common_id:IN'] = $all_common_ids;
        // if($active_goods_id){
        //     $pintuan_goods_row['goods_id:not in'] = $active_goods_id;
        // }
        $goodsModel = new Goods_BaseModel;
        $goods_list = $goodsModel->getGoodsSpecByGoodsId($pintuan_goods_row,array('goods_id'=>'DESC'),$page,$rows);

        foreach($goods_list['items'] as $key=>$value){
            if(is_array($value['spec'])){
                foreach($value['spec'] as $k=>$v){
                    $goods_list['items'][$key]['spec_title'] .= $k.'：'.$v.'；';
                }
            }
            if(in_array($value['goods_id'],$active_goods_id) && $active_goods_id)
            {
                $goods_list['items'][$key]['is_join'] = 'true';
            }
            else
            {
                $goods_list['items'][$key]['is_join'] = 'false';
            }
        }
        $Yf_Page->totalRows = $goods_list['totalsize'];
        $page_nav           = $Yf_Page->prompt();
        $data = $goods_list;
        if('json' == $this->typ)
        {
            $this->data->addBody(-140, $data);
        }
        else
        {
            include $this->view->getView();
        }
    }

    /**
     * 拼团
     */
    public function addPinTuan()
    {
        $addData = [
            'shop_id'=> Perm::$shopId,
            'user_id'=> Perm::$userId,
            'person_num'=> request_int('person_num'),
            'start_time'=> request_string('start_time'),
            'end_time'=> request_string('end_time'),
            'created_time'=> date('Y-m-d H:i:s'),
            'name'=> request_string('pintuan_name')
        ];
        if(!$addData['name']){
            return $this->data->addBody(-140, [], __('请填写活动名称'), 250);
        }
        if($addData['person_num'] < 2 || $addData['person_num'] > 9999){
            return $this->data->addBody(-140, [], __('成团人数有误'), 250);
        }
        $pinModel = new PinTuan_Base;
        $pinModel->sql->startTransactionDb(); //开启事物
        $id = $pinModel->addBase($addData, true);
        if ($id == false) {
            return $this->data->addBody(-140, [], __('创建拼团失败'), 250);
        }
        $goods_id = request_int('goods_id');
        $goods_detail = $this->goodsBaseModel->getGoodsDetailByGoodId($goods_id);
        $details = request_row('details');
        foreach($details as $k=>$v){
            $details[$k]['cid'] = $goods_detail['cat_id'];
        }
        $flag = $this->addPinTuanDetail($id, $details);

        if ($flag) {
            $msg = 'success';
            $status = 200;
            $pinModel->sql->commitDb(); //回滚事物
        } else {
            $msg = __('创建拼团详情失败');
            $status = 250;
            $pinModel->sql->rollBackDb(); //回滚事物
        }
        
        $this->data->addBody(-140, [], $msg, $status);
    }

    /**
     * 拼团详情
     * @param $pintuan_id int
     * @param $details array
     * @return boolean
     */
    private function addPinTuanDetail($pintuan_id, $details)
    {
        $pinDetailModel = new PinTuan_Detail;
        $goodsModel = new Goods_BaseModel;
        $goodsCommonModel = new Goods_CommonModel;

        foreach ($details as $detail) {
            $addData = [
                'pintuan_id'=> $pintuan_id, //拼团共用ID，如开始时间等
                'goods_id'=> $detail['goods_id'], //参加拼团的商品ID
                'price_ori'=> $detail['price_ori'], //原价格
                'price'=> $detail['price'], //团购价格
                'price_one'=> $detail['price_one'], //单独购买价格
                'cid'=> $detail['cid'] //商品类别ID
            ];

            $id = $pinDetailModel->addDetail($addData);
          
            $updateGoodsFlag = $goodsModel->editBaseFalse($detail['goods_id'], [ //覆盖goods表库存数据
                'goods_stock'=> $detail['goods_stock']
            ]);

            //修改goods_common表库存
            $goods_common = $goodsModel->getCommonInfo($detail['goods_id']);
            $common_stock = $detail['goods_stock'] - $detail['stock'] + $goods_common['common_stock'];
            $updateCommonFlag = $goodsCommonModel->editCommon($goods_common['common_id'], [ 
                'common_stock'=> $common_stock
            ]);

            if ($id == false || $updateGoodsFlag === false || $updateCommonFlag === false) {
                return false;
            }
        }
        return true;
    }

    //购买套餐、套餐续费
    public function combo()
    {
        //查找出店铺团购活动的消费记录
        $Yf_Page           = new Yf_Page();
        $Yf_Page->listRows = request_int('listRows')?request_int('listRows'):10;
        $rows              = $Yf_Page->listRows;
        $offset            = request_int('firstRow', 0);
        $page              = ceil_r($offset / $rows);

        $cond_row = array();
        $order_row = array();
        $this->shopCostModel = new Shop_CostModel();
        $cond_row['shop_id'] = Perm::$shopId;
        $cond_row['activity_type'] = Shop_CostModel::PINTUAN;
        $data = $this->shopCostModel->listByWhere($cond_row,$order_row,$page, $rows);

        $Yf_Page->totalRows = $data['totalsize'];
        $page_nav           = $Yf_Page->prompt();

        if('json' == $this->typ)
        {
            $data['pintuan_price'] = Web_ConfigModel::value('pintuan_price');
            $this->data->addBody(-140, $data);
        }
        else
        {
            include $this->view->getView();
        }

    }

    /*
     * 在店铺的账期结算中扣除相关费用
     * */
    public function addCombo()
    {
        $data      = array();
        $combo_row = array();
        $field     = array();
        $rs_row    = array();
            
        $this->PinTuanComboModel = new PinTuan_Combo();
        $this->shopCostModel     = new Shop_CostModel();
        $this->shopBaseModel     = new Shop_BaseModel();
        $this->shopInfo          = $this->shopBaseModel->getOne(Perm::$shopId);//店铺信息
        $month_price             = Web_ConfigModel::value('pintuan_price');
        $month                   = request_int('month');
        $days                    = 30 * $month;

        if($month > 0)
        {
            $this->PinTuanComboModel->sql->startTransactionDb();
            //记录到店铺费用表
            $field_row['user_id']        = Perm::$userId;
            $field_row['shop_id']        = Perm::$shopId;
            $field_row['cost_price']     = $month_price * $month;
            $field_row['cost_desc']      = __('店铺购买拼团活动消费');
            $field_row['cost_status']    = 0;
            $field_row['cost_time']      = get_date_time();
            $field_row['activity_type']  = Shop_CostModel::PINTUAN;
            $field_row['activity_price'] = $month_price;
            $field_row['activity_month'] = $month;
            $flag                        = $this->shopCostModel->addCost($field_row, true);
            check_rs($flag, $rs_row);
            if ($flag)
            {
                //购买或续费套餐
                $combo_row = $this->PinTuanComboModel->getPinTuanQuotaByShopID(Perm::$shopId);
                    
                //记录已经存在，套餐续费
                if ($combo_row)
                {
                    //1、原套餐已经过期,更新套餐开始时间和结束时间
                    if (strtotime($combo_row['combo_end_time']) < time())
                    {
                        $field['combo_start_time'] = get_date_time();
                        $field['combo_end_time']   = date('Y-m-d H:i:s', strtotime("+$days days"));
                    }
                    elseif ((time() >= strtotime($combo_row['combo_start_time'])) && (time() <= strtotime($combo_row['combo_end_time'])))
                    {
                        //2、原套餐尚未过期，只需更新结束时间
                        $field['combo_end_time'] = date('Y-m-d H:i:s', strtotime("+$days days", strtotime($combo_row['combo_end_time'])));
                    }

                    $op_flag = $this->PinTuanComboModel->renewPinTuanCombo($combo_row['combo_id'], $field);
                }
                else            //记录不存在，添加套餐
                {
                    $field['combo_start_time'] = get_date_time();
                    $field['combo_end_time']   = date('Y-m-d H:i:s', strtotime("+$days days"));
                    $field['shop_id']          = Perm::$shopId;
                    $field['shop_name']        = $this->shopInfo['shop_name'];
                    $field['user_id']          = Perm::$userId;
                    $field['user_nickname']    = Perm::$row['user_account'];

                    $op_flag = $this->PinTuanComboModel->addPinTuanCombo($field, true);
                }
                check_rs($op_flag, $rs_row);
            }
            if(is_ok($rs_row))
            {
                //在paycenter中添加交易记录
                $key         = Yf_Registry::get('shop_api_key');
                $url         = Yf_Registry::get('paycenter_api_url');
                $shop_app_id = Yf_Registry::get('shop_app_id');
                
                $formvars                    = array();
                $formvars['app_id']          = $shop_app_id;
                $formvars['buyer_user_id']   = Perm::$userId;
                $formvars['buyer_user_name'] = Perm::$row['user_account'];
                $formvars['amount']          = $month_price * $month;

                $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=addCombo&typ=json', $url), $formvars);
            }

            if (is_ok($rs_row) && isset($rs) && $rs['status'] == 200 && $this->PinTuanComboModel->sql->commitDb())
            {
                $msg    = __('操作成功！');
                $status = 200;
            }
            else
            {
                $this->PinTuanComboModel->sql->rollBackDb();
                $msg    = __('操作失败！');
                $status = 250;
            }
        }
        else
        {
            $msg    = __('购买月份必须为正整数！');
            $status = 250;
        }
        $this->data->addBody(-140, $field, $op_flag, $status);
    }

    //删除活动
    public function removePinTuanAct()
    {
        $PinTuan_ids = request_row('id');
        if (is_array($PinTuan_ids)) {
            foreach ($PinTuan_ids as $PinTuan_id){
                $check_row   = $this->pinTuanBaseModel->getOne($PinTuan_id);
            }
        }

        if ($check_row['shop_id'] == Perm::$shopId)
        {
            $this->pinTuanBaseModel->sql->startTransactionDb();

            $flag = $this->pinTuanBaseModel->removePinTuanActItem($PinTuan_id); //删除活动

            if ($flag && $this->pinTuanBaseModel->sql->commitDb())
            {
                $msg    = __('删除成功！');
                $status = 200;
            }
            else
            {
                $this->pinTuanBaseModel->sql->rollBackDb();

                $msg    = __('删除失败！');
                $status = 250;
            }
        }
        else
        {
            $msg    = __('删除失败！');
            $status = 250;
        }

        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }
}
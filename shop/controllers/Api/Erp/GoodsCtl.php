<?php
/**
 * Created by PhpStorm.
 * User: tech05
 * Date: 2016-11-2
 * Time: 10:08
 */
class Api_Erp_GoodsCtl extends Api_Controller
{
    
    //erp同步商品
    public function listGoods()
    {

        $Goods_CommonModel = new Goods_CommonModel();
        $Goods_CommonModel->sql->setWhere('common_verify',1);//审核通过的商品

        $Goods_CommonModel->sql->setWhere('common_is_virtual',0);//虚拟商品
        if (request_string('endDate'))
        {
            $Goods_CommonModel->sql->setWhere('common_add_time',request_string('endDate'),'<=');
        }
        if (request_string('beginDate'))
        {
            $Goods_CommonModel->sql->setWhere('common_add_time',request_string('beginDate'),'>=');
        }
        if (request_string('goodsId'))
        {
            $Goods_CommonModel->sql->setWhere('common_id',explode(',',request_string('goodsId')),'IN');
        }
        if (request_string('goodsName'))
        {
            $Goods_CommonModel->sql->setWhere('common_name',explode(',',request_string('goodsName')),'IN');
        }
        if (request_string('goodsNumber'))
        {
            $Goods_CommonModel->sql->setWhere('common_code',explode(',',request_string('goodsNumber')),'IN');
        }
        if (request_string('goodsStatus')==1)
        {
            $Goods_CommonModel->sql->setWhere('common_state',1);
        }elseif(request_string('goodsStatus')==2){
            $Goods_CommonModel->sql->setWhere('common_state',0);
        }
        if (request_row('store_account'))
        {
            $shop_account = request_row('store_account');
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

        $Goods_CommonModel->sql->setWhere('shop_id',$shop_id,'IN');
        $Goods_CommonModel->sql->setLimit(0,999999999);
        $goodscommon  = $Goods_CommonModel->getCommon('*');

        $Goods_BaseModel = new Goods_BaseModel();
        $Goods_BaseModel->sql->setLimit(0,999999999);
        $Goods_Base  = $Goods_BaseModel->getBase('*');

        $Base_DistrictModel = new Base_DistrictModel();
        /*$Base_DistrictModel->sql->setLimit(0,999999999);
        $Base_District  = $Base_DistrictModel->getDistrict('*');
        */
        $data = array('count');

        if ($goodscommon)
        {
            foreach($goodscommon as $key=>$value){
                $User_id = $Shop_Base[$value['shop_id']]['user_id'];
                $data['count']+=1;
                $data['data'][$key]['productId']=$value['common_id'];
                $data['data'][$key]['cate']=$value['cat_name'];
                $data['data'][$key]['imageUrl']=$value['common_image'];
                $data['data'][$key]['productName']=$value['common_name'];
                $data['data'][$key]['price']=$value['common_price'];
                $data['data'][$key]['isShelves']=$value['common_state'];
                $data['data'][$key]['postPrice']=$value['common_freight'];
                $data['data'][$key]['area']='';
                if($value['common_location']){
                    foreach($value['common_location'] as $areaid){
                        $Base_District  = $Base_DistrictModel->getDistrict($areaid);
                        $data['data'][$key]['area'].=$Base_District[$areaid]['district_name'];
                    }
                }
                $data['data'][$key]['shopId']=$value['shop_id'];
                $data['data'][$key]['shopName']=$value['shop_name'];
                $data['data'][$key]['member_name']=$User_Base[$User_id]['user_account'];
                $data['data'][$key]['stock']=$value['common_stock'];
                $data['data'][$key]['code']=$value['common_code'];
                $sku=array();

                foreach($Goods_Base as $k=>$v){
                    if($v['common_id']==$value['common_id']){
                        $sku[$k]['id']=$v['goods_id'];
                        $sku[$k]['pid']=$v['common_id'];
                        $sku[$k]['property_value_id']='';
                        $sku[$k]['setmeal']=$value['common_name'];
                        if($v['goods_spec']){
                            foreach($v['goods_spec'] as $goods_spec){
                                $sku[$k]['property_value_id']=implode(',',array_keys($goods_spec));
                                $sku[$k]['setmeal']=implode(',',array_values($goods_spec));
                            }
                        }
                        $sku[$k]['spec_id']= '';
                        $sku[$k]['spec_name']= '商品名称';
                        if($value['common_spec_name']){
                            $sku[$k]['spec_id']= implode(',',array_keys($value['common_spec_name']));
                            $sku[$k]['spec_name']= implode(',',array_values($value['common_spec_name']));

                        }

                        $sku[$k]['price']=$v['goods_price'];
                        $sku[$k]['market_price']=$v['goods_market_price'];
                        $sku[$k]['cost_price']=$value['common_cost_price'];
                        $sku[$k]['stock']=$v['goods_stock'];
                        $sku[$k]['sku']=$v['goods_code'];
                    }
                }
                $data['data'][$key]['sku']=$sku;
            }
            $status = 200;
            $msg    = __('success');
        }
        else
        {
            $status = 250;
            $msg    = __('没有满足条件的结果哦');
        }

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 同步ERP内商品
     */
    public function synchronizeERPGoods ()
    {
        $goodsCatModel = new Goods_CatModel;

        $user_account = request_string('store_account');
        $upload_goods_rows = request_row('upload_goods_rows');

        $userBaseModel = new User_BaseModel;
        $shop_data = $userBaseModel->getStoreInfoByUserAccount($user_account);
        
        if (empty($shop_data)) {
            return $this->data->addBody(-140, ['user_account'=> $user_account], '没有对应店铺信息，请重试！', 250);
        }

        /**
         * 过滤无效数据
         * 规则：
         * 1.根据平台的商家货号->ERP商品编号作参照。如有相同编号，暂不处理。
         * 2.根据平台的商品分类->ERP商品分类作参照。如没有对应，暂不处理。
         * 3.如果商品启用SKU，则判断：1.平台分类是否启用SKU。2.平台SKU->ERP SKU
         * 
         */

        $shopBaseModel = new Shop_BaseModel();

        $shop_id = $shop_data['shop_id'];
        $shop_category_rows = $shopBaseModel->getBindCategoryByShopId($shop_id, $shop_data['shop_self_support'], $shop_data['shop_all_class']);    //获取店铺经营类目

        if (empty($shop_category_rows)) {
            return $this->data->addBody(-140, ['store_unbind_category'=> 'all'], '店铺没有绑定经营类目，请重试！', 250);
        }

        $error_rows = [];//统计错误信息

        //判断上传商品的分类是否对应店铺中的经营类目
        $cat_names = array_column($shop_category_rows, 'cat_id', 'cat_name');

        $filter_upload_goods_rows = array_filter($upload_goods_rows, function ($row) use ($cat_names, &$error_rows) {
            if ( isset($cat_names[$row['goods_category_name']]) ) {
                return true;
            } else {
                $error_rows['goods_category_name'][] = $row['goods_name'];
                return false;
            }
        });

        if (empty($filter_upload_goods_rows)) { //没有对应的商品分类
            return $this->data->addBody(-140, $error_rows, '没有符合上传条件的商品，请重试！', 250);
        }

        //判断上传商品的编号是否跟店铺商品的编号重复
        $goods_common_rows = $shopBaseModel->getShopGoods($shop_id);
        $goods_numbers = array_filter(array_column($goods_common_rows, 'common_code', 'common_code'));

        if (!empty($goods_numbers)) {//商家货号不是必填项，有可能全部为空
            $filter_upload_goods_rows = array_filter($filter_upload_goods_rows, function ($row) use ($goods_numbers, &$error_rows) {
                if ( isset($goods_numbers[$row['goods_number']]) ) {
                    $error_rows['goods_number'][] = $row['goods_name'];
                    return false;
                } else {
                    return true;
                }
            });
        }

        if (empty($filter_upload_goods_rows)) {
            return $this->data->addBody(-140, $error_rows, '没有符合上传条件的商品，请重试！', 250);
        }

        //把数据分成两份：单sku商品，多sku商品
        $single_property_goods_rows = [];
        $multi_property_goods_rows = [];
        foreach ($filter_upload_goods_rows as $filter_upload_goods_data) {
            if (isset($filter_upload_goods_data['goods_sku_data'])) {
                $multi_property_goods_rows[] = $filter_upload_goods_data;
            } else {
                $single_property_goods_rows[] = $filter_upload_goods_data;
            }
        }

        if (!empty($multi_property_goods_rows)) {

            $goodsCatModel = new Goods_CatModel;
            $goods_cat_spec = $goodsCatModel->getSpecAndSpecValue($shop_category_rows, $shop_id); //获取分类所绑定的规格信息

            foreach ($multi_property_goods_rows as $k_multi=> $multi_property_goods_data) {

                //判断经营类目是否有启用规格
                $cat_name = $multi_property_goods_data['goods_category_name'];
                if (!isset($goods_cat_spec[$cat_name])) {
                    $error_rows['sku']['not_found_sku_name'][] = $multi_property_goods_data['goods_name'];
                    unset($multi_property_goods_rows[$k_multi]);
                    continue 1;
                }

                //验证规格数量是否匹配
                //验证规格名字是否匹配
                $store_spec = $goods_cat_spec[$cat_name]['spec'];
                $store_spec_names = array_keys($store_spec);

                $erp_spec = $multi_property_goods_data['goods_sku_data'];
                $erp_spec_names = $erp_spec['goods_sku_names'];

                if ( array_udiff($erp_spec_names, $store_spec_names, function($a, $b) {
                        if ($a == $b) return 0;
                        return $a > $b ? 1 : -1;
                    })
                ) {
                    $error_rows['sku']['not_match_sku_name'][] = $multi_property_goods_data['goods_name'];
                    unset($multi_property_goods_rows[$k_multi]);
                    continue 1;
                }

                //验证规格值是否匹配
                $erp_sku_values = $erp_spec['goods_sku_values'];

                foreach ($erp_sku_values as $sku_value_data) {
                    $sku_values = explode('/', $sku_value_data['sku_value']);  //[M, 黑色]
                    foreach ($sku_values as $k=> $sku_value) { //M
                        $erp_spec_name = $erp_spec_names[$k]; //尺寸
                        $store_spec_values = $store_spec[$erp_spec_name]['spec_value']; //[M, S]

                        if (!isset($store_spec_values[$sku_value])) {
                            $error_rows['sku']['not_match_sku_values'][] = $multi_property_goods_data['goods_name']."($sku_value)";
                            unset($multi_property_goods_rows[$k_multi]);
                            continue 3;
                        }
                    }
                }
            }
        }

        if (empty($single_property_goods_rows) && empty($multi_property_goods_rows)) {//没有需要上传的商品
            return $this->data->addBody(-140, $error_rows, '没有符合上传条件的商品，请重试！', 250);
        }

        $upload_goods_rows = array_merge($single_property_goods_rows, $multi_property_goods_rows);

        //平台内部验证
        //当前店铺商品数量上限验证（自营店铺不需要验证）
        
        //所有验证结束
        //拼接商品对应的平台类目信息
        //如果商品为多属性，拼接商品对应的平台规格信息
        // spec_name [spec_id=> spec_name] 例：[1=> 颜色]
        // spec_value [spec_id=> [spec_value_id=> spec_value_name]] 例：[1=> [2=> 白色, 3=> 黑色]]
        // goods_rows [i_spec_value_ids=> [spec_value_id=> spec_value_name]] 例：[i_101102103=> [101:白色, 102:32G, 103:5.5英寸]]

        foreach ($upload_goods_rows as $k=> $upload_goods_data) {

            $erp_cat_name = $upload_goods_data['goods_category_name'];
            $cat_id = $cat_names[$erp_cat_name];
            $cat_data = $shop_category_rows[$cat_id];

            $upload_goods_rows[$k]['cat_id'] = $cat_id; //经营分类id
            $upload_goods_rows[$k]['cat_name'] = $cat_data['cat_name']; //经营类目分类名称
            $upload_goods_rows[$k]['type_id'] = $cat_data['type_id']; //商品分类id

            if (isset($upload_goods_data['goods_sku_data'])) {//商品为多属性

                $spec_names = [];
                $spec_values = [];
                $goods_rows = [];

                $store_specs = $goods_cat_spec[$erp_cat_name]['spec'];
                $erp_spec_names = $upload_goods_data['goods_sku_data']['goods_sku_names'];
                $erp_spec_values = $upload_goods_data['goods_sku_data']['goods_sku_values'];

                foreach ($store_specs as $store_name=> $store_spec_data) {
                    $spec_id = $store_spec_data['spec_id'];
                    $spec_names[$spec_id] = $store_name;
                }

                //goods_sku_names = ['颜色', '尺寸'], goods_sku_values = [ ['sku_value'=> '白色/M', 'sku_goods_number'=> 10001] ]
                foreach ($erp_spec_values as $erp_spec_value) {
                    $color_spec_value_id = 0; //goods_base 需要关联颜色规格值id
                    $single_sku_value = []; //[spec_value_id=> '白色', spec_value_id=> '32G', spec_value_id=> '5.5英寸']

                    $erp_sku_values = explode('/', $erp_spec_value['sku_value']); //['白色', 'M']
                    foreach ($erp_sku_values as $k_sku_value=> $erp_sku_value) { //$erp_sku_value 白色
                        $erp_spec_name = $erp_spec_names[$k_sku_value]; //颜色
                        $spec_id = $store_specs[$erp_spec_name]['spec_id'];
                        $store_spec_values = $store_specs[$erp_spec_name]['spec_value']; //['白色'=> 1, '黑色'=> 2]

                        $spec_value_id = $store_spec_values[$erp_sku_value];
                        $spec_values[$spec_id][$spec_value_id] = $erp_sku_value;

                        $single_sku_value[$spec_value_id] = $erp_sku_value;

                        if ($spec_id == Goods_SpecModel::COLOR) {
                            $color_spec_value_id = $spec_value_id;
                        }
                    }

                    ksort($single_sku_value, SORT_NUMERIC); //对规格值升序排序
                    $goods_rows['i_'.implode('', array_keys($single_sku_value))] = [    'sku_value'=> $single_sku_value,
                                                                                        'sku_number'=> $erp_spec_value['sku_goods_number'],
                                                                                        'color_spec_value_id'=> $color_spec_value_id
                                                                                    ];
                }

                unset($upload_goods_rows[$k]['goods_sku_data']); //去除无用值
                $upload_goods_rows[$k]['spec_names'] = $spec_names;
                $upload_goods_rows[$k]['spec_values'] = $spec_values;
                $upload_goods_rows[$k]['goods_rows'] = $goods_rows;
            }
        }
        
        //准备工作完成，开始上传
        $goodsCommonModel = new Goods_CommonModel;
        $errors = $goodsCommonModel->addGoodsByERPUpload($upload_goods_rows, $shop_data);

        if (!empty($errors)) {
            $status = 250;
            $error_rows['add_failure'] = $errors;
        } else {
            $status = 200;
        }

        $this->data->addBody(-140, $error_rows, 'upload complete', $status);
    }


    /*
     * 新增方法，检测绑定账户是否是商家账户，供应商以及普通会员禁止绑定
     * 参数：用户账号
     *
     */
    public function bindshop()
    {
        $user_account = request_string('user_account');
        if($user_account){
            $Shop_BaseModel = new Shop_BaseModel();
            $shop_info = $Shop_BaseModel->getByWhere(['user_name'=>$user_account,'shop_type'=>1]);
            if($shop_info){
                $status = 200;
                $msg    = __('绑定成功!');
            }else{
                $status = 250;
                $msg    = __('绑定失败,该账号不是商家账号，不可绑定！');
            }
        }else{
            $status = 250;
            $msg    = __('账号不能为空!');
        }
        $this->data->addBody(-140, array(), $msg, $status);
    }
}
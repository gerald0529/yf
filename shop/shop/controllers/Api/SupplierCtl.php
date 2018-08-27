<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_SupplierCtl extends Yf_AppController
{
    public function synchronizeGoods()
    {
        $user_id = request_string('user_id');
        $common_info = request_row('common');
        $common_detail = request_string('common_body');
        $base_list = request_row('goods_list');

        $Shop_BaseModel = new Shop_BaseModel();
        $shop_info = $Shop_BaseModel->getOneByWhere([
            'user_id' => $user_id
        ]);

        if (empty($shop_info)) {
            throw new Exception('当前用户没有开店，不允许一键上架或这购买供应商商品');
        }

        //判断是修改还是新增
        $Goods_CommonModel = new Goods_CommonModel();
        $goods_common = $Goods_CommonModel->getOneByWhere([
            'common_parent_id'=> $common_info['common_id'],
            'shop_id'=> $shop_info['shop_id']
        ]);

        if (empty($goods_common)) {
            $flag = $this->addGoods($common_info, $common_detail, $base_list, $shop_info);
        } else {
            $flag = $this->editGoods($goods_common['common_id'], $common_info, $common_detail, $base_list, $shop_info);
        }

        if ($flag) {
            $Goods_CommonModel->sql->commitDb();
            $msg = __('success');
            $status = 200;
        } else {
            $Goods_CommonModel->sql->rollBackDb();
            $msg = __('failure');
            $status = 250;
        }
        $this->data->addBody(-140, [], $msg, $status);
    }

    private function addGoods($common_info, $common_detail, $base_list, $shop_info)
    {
        $Goods_CommonModel = new Goods_CommonModel();

        $Goods_CommonModel->sql->startTransactionDb();
        $res_arr = [];

        $common_row = array();
        $common_row['common_name'] = $common_info['common_name'];
        $common_row['cat_id'] = $common_info['cat_id'];
        $common_row['cat_name'] = str_replace('&gt;', '>', $common_info['cat_name']);
        $common_row['shop_id'] = $shop_info['shop_id'];
        $common_row['shop_name'] = $shop_info['shop_name'];
        $common_row['shop_self_support'] = $shop_info['shop_self_support'] == 'false' ? 0 : 1;
        $common_row['brand_id'] = $common_info['brand_id'];
        $common_row['brand_name'] = $common_info['brand_name'];
        $common_row['common_property'] = $common_info['common_property'];
        $common_row['common_spec_name'] = $common_info['common_spec_name'];
        $common_row['common_spec_value'] = $common_info['common_spec_value'];
        $common_row['common_image'] = $common_info['common_image'];
        $common_row['common_price'] = $common_info['common_price'];
        $common_row['common_cubage'] = $common_info['common_cubage'];
        $common_row['common_market_price'] = $common_info['common_market_price'];
        $common_row['common_cost_price'] = $common_info['common_cost_price'];
        $common_row['common_verify'] = $common_info['common_verify'];
        $common_row['common_stock'] = $common_info['common_stock'];
        $common_row['common_is_virtual'] = $common_info['common_is_virtual'];
        $common_row['common_add_time'] = get_date_time();
        $common_row['common_state'] = 1;
        $common_row['product_is_allow_update'] = $common_info['product_is_allow_update'];
        $common_row['product_is_allow_price'] = $common_info['product_is_allow_price'];
        $common_row['product_is_behalf_delivery'] = $common_info['product_is_behalf_delivery'];
        $common_row['goods_recommended_min_price'] = $common_info['common_price'];
        $common_row['goods_recommended_max_price'] = $common_info['common_market_price'];
        $common_row['common_parent_id'] = $common_info['common_id'];
        $common_row['supply_shop_id'] = $common_info['shop_id'];

        //添加新商品
        $new_common_id = $Goods_CommonModel->addCommon($common_row, true);
        array_push($res_arr, $new_common_id);

        //商品详情信息
        $goodsCommonDetailModel = new Goods_CommonDetailModel();

        $common_detail_data['common_id'] = $new_common_id;
        $common_detail_data['common_body'] = $common_detail;
        $flag_common_detail = $goodsCommonDetailModel->addCommonDetail($common_detail_data);
        array_push($res_arr, $flag_common_detail);

        $Goods_BaseModel = new Goods_BaseModel();

        //根据common_id查询base表，同步base数据

        if (!empty($base_list)) {
            foreach ($base_list as $k => $v) {
                $base_row = array();
                $base_row['common_id'] = $new_common_id;
                $base_row['shop_id'] = $shop_info['shop_id'];
                $base_row['shop_name'] = $shop_info['shop_name'];
                $base_row['goods_name'] = $v['goods_name'];
                $base_row['cat_id'] = $v['cat_id'];
                $base_row['brand_id'] = $v['brand_id'];
                $base_row['goods_spec'] = $v['goods_spec'];
                $base_row['goods_price'] = $v['goods_price'];
                $base_row['goods_market_price'] = $v['goods_market_price'];
                $base_row['goods_stock'] = $v['goods_stock'];
                $base_row['goods_image'] = $v['goods_image'];
                $base_row['goods_parent_id'] = $v['goods_id'];
                $base_row['goods_is_shelves'] = 1;
                $base_row['goods_recommended_min_price'] = $v['goods_price'];
                $base_row['goods_recommended_max_price'] = $v['goods_market_price'];

                $goods_id = $Goods_BaseModel->addBase($base_row, true);
                array_push($res_arr, $goods_id);
                $new_goods_ids[] = array(
                    'goods_id' => $goods_id,
                    'color' => $v['color_id']
                );
            }
        }

        $edit_common_data['goods_id'] = $new_goods_ids;
        $data['goods_id'] = $edit_common_data;
        $flag_edit_common = $Goods_CommonModel->editCommon($new_common_id, $edit_common_data);
        array_push($res_arr, $flag_edit_common === false ? false : true);

        return count(array_filter($res_arr)) === count($res_arr)
            ? true
            : false;
    }

    private function editGoods($common_id, $common_info, $common_detail, $base_list, $shop_info)
    {
        $Goods_CommonModel = new Goods_CommonModel();

        $Goods_CommonModel->sql->startTransactionDb();
        $res_arr = [];

        $common_row = array();
        $common_row['common_name'] = $common_info['common_name'];
        $common_row['cat_id'] = $common_info['cat_id'];
        $common_row['cat_name'] = str_replace('&gt;', '>', $common_info['cat_name']);
        $common_row['shop_id'] = $shop_info['shop_id'];
        $common_row['shop_name'] = $shop_info['shop_name'];
        $common_row['shop_self_support'] = $shop_info['shop_self_support'] == 'false' ? 0 : 1;
        $common_row['brand_id'] = $common_info['brand_id'];
        $common_row['brand_name'] = $common_info['brand_name'];
        $common_row['common_property'] = $common_info['common_property'];
        $common_row['common_spec_name'] = $common_info['common_spec_name'];
        $common_row['common_spec_value'] = $common_info['common_spec_value'];
        $common_row['common_image'] = $common_info['common_image'];
        $common_row['common_price'] = $common_info['common_price'];
        $common_row['common_cubage'] = $common_info['common_cubage'];
        $common_row['common_market_price'] = $common_info['common_market_price'];
        $common_row['common_cost_price'] = $common_info['common_cost_price'];
        $common_row['common_verify'] = $common_info['common_verify'];
        $common_row['common_stock'] = $common_info['common_stock'];
        $common_row['common_is_virtual'] = $common_info['common_is_virtual'];
        $common_row['common_add_time'] = get_date_time();
        $common_row['common_state'] = 1;
        $common_row['product_is_allow_update'] = $common_info['product_is_allow_update'];
        $common_row['product_is_allow_price'] = $common_info['product_is_allow_price'];
        $common_row['product_is_behalf_delivery'] = $common_info['product_is_behalf_delivery'];
        $common_row['goods_recommended_min_price'] = $common_info['common_price'];
        $common_row['goods_recommended_max_price'] = $common_info['common_market_price'];
        $common_row['common_parent_id'] = $common_info['common_id'];
        $common_row['supply_shop_id'] = $common_info['shop_id'];

        //添加新商品
        $flag_common = $Goods_CommonModel->editCommon($common_id, $common_row);
        array_push($res_arr, $flag_common === false ? false : true);

        //商品详情信息
        $goodsCommonDetailModel = new Goods_CommonDetailModel();

        $common_detail_data['common_body'] = $common_detail;
        $flag_common_detail = $goodsCommonDetailModel->editCommonDetail($common_id, $common_detail_data);
        array_push($res_arr, $flag_common_detail === false ? false : true);

        $Goods_BaseModel = new Goods_BaseModel();
        $goods_list = $Goods_BaseModel->getByWhere(['common_id'=> $common_id]);

        #如果goods数量与请求的goods数量不一致，则删除goods，重新添加

        if (count($goods_list) == count($base_list)) {
            array_values($goods_list);
            array_values($base_list);
            foreach ($base_list as $k => $v) {
                $base_row = array();
                $base_row['common_id'] = $common_id;
                $base_row['shop_id'] = $shop_info['shop_id'];
                $base_row['shop_name'] = $shop_info['shop_name'];
                $base_row['goods_name'] = $v['goods_name'];
                $base_row['cat_id'] = $v['cat_id'];
                $base_row['brand_id'] = $v['brand_id'];
                $base_row['goods_spec'] = $v['goods_spec'];
                $base_row['goods_price'] = $v['goods_price'];
                $base_row['goods_market_price'] = $v['goods_market_price'];
                $base_row['goods_stock'] = $v['goods_stock'];
                $base_row['goods_image'] = $v['goods_image'];
                $base_row['goods_parent_id'] = $v['goods_id'];
                $base_row['goods_is_shelves'] = 1;
                $base_row['goods_recommended_min_price'] = $v['goods_price'];
                $base_row['goods_recommended_max_price'] = $v['goods_market_price'];

                $goods_id = $goods_list[$k]['goods_id'];

                $flag = $Goods_BaseModel->editBase($goods_id, $base_row);
                array_push($res_arr, $flag === flase ? false : true);
                $new_goods_ids[] = array(
                    'goods_id' => $goods_id,
                    'color' => $v['color_id']
                );
            }
        } else {
            $flag_remove_goods = $Goods_BaseModel->removeBase(array_keys($goods_list));
            array_push($res_arr, $flag_remove_goods);
            foreach ($base_list as $k => $v) {
                $base_row = array();
                $base_row['common_id'] = $common_id;
                $base_row['shop_id'] = $shop_info['shop_id'];
                $base_row['shop_name'] = $shop_info['shop_name'];
                $base_row['goods_name'] = $v['goods_name'];
                $base_row['cat_id'] = $v['cat_id'];
                $base_row['brand_id'] = $v['brand_id'];
                $base_row['goods_spec'] = $v['goods_spec'];
                $base_row['goods_price'] = $v['goods_price'];
                $base_row['goods_market_price'] = $v['goods_market_price'];
                $base_row['goods_stock'] = $v['goods_stock'];
                $base_row['goods_image'] = $v['goods_image'];
                $base_row['goods_parent_id'] = $v['goods_id'];
                $base_row['goods_is_shelves'] = 1;
                $base_row['goods_recommended_min_price'] = $v['goods_price'];
                $base_row['goods_recommended_max_price'] = $v['goods_market_price'];

                $goods_id = $Goods_BaseModel->addBase($base_row, true);
                array_push($res_arr, $goods_id);
                $new_goods_ids[] = array(
                    'goods_id' => $goods_id,
                    'color' => $v['color_id']
                );
            }
        }

        //根据common_id查询base表，同步base数据

        $edit_common_data['goods_id'] = $new_goods_ids;
        $data['goods_id'] = $edit_common_data;
        $flag_edit_common = $Goods_CommonModel->editCommon($common_id, $edit_common_data);
        array_push($res_arr, $flag_edit_common === false ? false : true);

        return count(array_filter($res_arr)) === count($res_arr)
            ? true
            : false;
    }

    public function getCommon()
    {
        $common_id = request_string('common_id');

        $commonModel = new Goods_CommonModel;
        $common = $commonModel->getOne($common_id);

        $this->data->addBody(-140, ['common'=> $common]);
    }

    public function getOrderOneByWhere()
    {
        $where = request_row('where');

        $orderModel = new Order_BaseModel;
        $order = $orderModel->getOneByWhere($where);

        $this->data->addBody (-140, ['order'=> $order]);
    }

    public function getOrderGoodsByWhere()
    {
        $where = request_row('where');

        $order_goods_model = new Order_GoodsModel;
        $order_goods_rows = $order_goods_model->getByWhere ($where);

        $this->data->addBody (-140, ['order_goods_rows'=> $order_goods_rows]);
    }

    public function editOrderGoods()
    {
        $id = $_REQUEST['id'];
        $data = request_row ('data');

        $order_goods_model = new Order_GoodsModel;
        $flag = $order_goods_model->editGoods ($id, $data);

        if ($flag !== false) {
            $msg = 'success';
            $status = 200;
        } else {
            $msg = 'failure';
            $status = 250;
        }

        $this->data->addBody (-140, [], $msg, $status);
    }

    public function editOrder()
    {
        $id = request_string ('id');
        $data = request_row ('data');

        $order_model = new Order_BaseModel;
        $flag = $order_model->editBase ($id, $data);

        if ($flag !== false) {
            $msg = 'success';
            $status = 200;
        } else {
            $msg = 'failure';
            $status = 250;
        }

        $this->data->addBody (-140, [], $msg, $status);
    }

    public function getOrderGoodsKeyByWhere()
    {
        $where = request_row('where');

        $order_goods_model = new Order_GoodsModel;
        $order_goods_id = $order_goods_model->getKeyByWhere ($where);

        $this->data->addBody (-140, ['order_goods_id'=> $order_goods_id]);
    }
}

?>
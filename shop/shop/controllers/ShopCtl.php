<?php

/**
 * @author     charles
 */
class ShopCtl extends Controller
{

    public $shopBaseModel = null;
    public $shopGoodCatModel = null;
    public $shopNavModel = null;
    public $goodsCommonModel = null;
    public $shopDecorationModel = null;

    // public $shopDecorationBlockModel = null;


    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);
        $this->shopBaseModel = new Shop_BaseModel();
        $this->shopGoodCatModel = new Shop_GoodCatModel();
        $this->shopNavModel = new Shop_NavModel();
        $this->goodsCommonModel = new Goods_CommonModel();
        $this->shopDecorationModel = new Shop_DecorationModel();
        // $this->shopDecorationBlockModel = new Shop_DecorationBlockModel();
        //调用这个方法查询出当下店铺是否开启自定义店铺，如果开启自定义店铺只能用店铺默认的模板，如果不是自定义店铺则需要分配那个模板
        $this->setTemp();
        $this->initData();
    }

    public function setTemp()
    {
        $shop_id = request_int('id');

        if ($shop_id) {
            //根据店铺id查询出是否开启自定义店铺
            $renovation_list = $this->shopBaseModel->getOne($shop_id);
            if (!empty($renovation_list['is_renovation'])) {

                //店铺装修
                $this->view->setMet(null, "default");
            } else {
                if ($renovation_list) {
                    //分配模板
                    $shop_template = $renovation_list['shop_template'];
                    $this->view->setMet(null, $shop_template);
                } else {
                    $this->view->setMet('404');
                }
            }
        } else {
            $this->view->setMet('404');

        }
    }

    public function index()
    {
        $shop_id = request_int('id');
        if ($shop_id) {
            $this->shopCustomServiceModel = new Shop_CustomServiceModel;

            $cond_row['shop_id'] = $shop_id;

            $service = $this->shopCustomServiceModel->getServiceList($cond_row);
            if ($service['items']) {
                foreach ($service['items'] as $key => $val) {
                    //QQ
                    if ($val['tool'] == 1) {
                        $service[$key]["tool"] = "<a target='_blank' href='http://wpa.qq.com/msgrd?v=3&uin=" . $val['number'] . "&site=qq&menu=yes'><img border='0' src='http://wpa.qq.com/pa?p=2:" . $val['number'] . ":41 &amp;r=0.22914223582483828' alt='点击这里'></a>";
                    }
                    //旺旺
                    if ($val['tool'] == 2) {
                        $service[$key]["tool"] = "<a target='_blank' href='http://www.taobao.com/webww/ww.php?ver=3&amp;touid=" . $val['number'] . "&amp;siteid=cntaobao&amp;status=1&amp;charset=utf-8' ><img border='0' src='http://amos.alicdn.com/online.aw?v=2&amp;uid=" . $val['number'] . "&amp;site=cntaobao&s=1&amp;charset=utf-8' alt='点击这里' /></a>";
                    }
                    //IM
                    if ($val['tool'] == 3) {
                        $service[$key]["tool"] = '<a href="javascript:;" class="chat-enter" onclick="return chat(\'' . $val['number'] . '\');"><img src="' . $this->view->img . '/icon-im.gif" alt=""></a>';
                    }

                    //$service[$key]["tool"] = $val['tool'];
                    $service[$key]["number"] = $val['number'];
                    $service[$key]["name"] = $val['name'];
                    $service[$key]["id"] = $val['id'];

                    if ($val['type'] == 1) {
                        $de['after'][] = $service[$key];
                    } else {
                        $de['pre'][] = $service[$key];
                    }
                }
                $service = array();
                $service = $de;
            }
        }
        $GroupBuy_BaseModel = new GroupBuy_BaseModel();
        $data_hot_groupbuy = $GroupBuy_BaseModel->getGroupBuyGoodsList(array("shop_id" => $shop_id), array('groupbuy_buy_quantity' => 'desc'), 0, 5);

        if (!empty($data_hot_groupbuy['items'])) {
            //排除已经结束和审核中的团购商品
            foreach ($data_hot_groupbuy['items'] as $k => $v) {
                if (!in_array($data_hot_groupbuy['items'][$k]['groupbuy_state'], array(0, 2))) {
                    unset($data_hot_groupbuy['items'][$k]);
                }
            }
            $hot_groupbuy_data = $data_hot_groupbuy['items'];
        }

        //店铺信息
        $shop_base = $this->shopBaseModel->getOne($shop_id);
        $shop_type = $shop_base['shop_type']; //店铺类型: 1-卖家店铺; 2:供应商店铺
        $ctl = 'Goods_Goods';
        if ($shop_type == 2) {
            $ctl = 'Supplier_Goods';
        }

        //2.评分信息
        $shop_detail = $this->shopBaseModel->getShopDetail($shop_id);
        $shop_scores_num = ($shop_detail['shop_desc_scores'] + $shop_detail['shop_service_scores'] + $shop_detail['shop_send_scores']) / 3;
        $shop_scores_count = sprintf("%.2f", $shop_scores_num);
        $shop_scores_percentage = $shop_scores_count * 20;

        if ($shop_base['shop_self_support'] == 'false') {
            $shop_all_base = $this->shopBaseModel->getbaseCompanyList($shop_id);
        }

        //判断是否显示自营店铺
        if (isset($_COOKIE['sub_site_id']) && $_COOKIE['sub_site_id'] > 0) {
            $sub_site_id = $_COOKIE['sub_site_id'];
        }
        $self_shop_show_key = !$sub_site_id ? 'self_shop_show' : 'self_shop_show_' . $sub_site_id;
        $check_shop_show = $shop_base['shop_self_support'] == 'true' && !Web_ConfigModel::value($self_shop_show_key) ? false : true;

        if (!empty($shop_base) && $shop_base['shop_status'] == 3 && $check_shop_show) {
            //店铺幻灯和幻灯对应的连接
            $shop_slide = explode(",", $shop_base['shop_slide']);
            $shop_slide_url = explode(",", $shop_base['shop_slideurl']);

            //用来判断是不是开启了店铺装潢
            // $renovation_list = $this->shopRenovationModel->getOne($shop_id);
            //查询数据的条件
            $nav_cond_row = array(
                "shop_id" => $shop_id,
                "status" => 1
            );
            $nav_order_row = array("displayorder" => "asc");
            //店铺导航
            $shop_nav = $this->shopNavModel->listByWhere($nav_cond_row, $nav_order_row);
            if (($shop_base['is_renovation'] && $shop_base['is_only_renovation'] == "0") || !$shop_base['is_renovation']) {
                //店铺分类
                $cat_row['shop_id'] = $shop_id;
                $shop_cat = $this->shopGoodCatModel->getGoodCatList($cat_row);

                //店铺下面的产品 新品 推荐 热销排行 收藏排行
                $goods_new_list = $this->goodsCommonModel->getGoodsList(array(
                    "shop_id" => $shop_id,
                    "common_state" => 1,
                    'common_verify' => 1
                ), array("common_add_time" => "desc"), 1, 12);
                $goods_recom_list = $this->goodsCommonModel->getGoodsList(array(
                    "shop_id" => $shop_id,
                    "common_is_recommend" => 2,
                    "common_state" => 1,
                    'common_verify' => 1
                ), array(), 1, 12);

                //ajax 读取
                $goods_selling_list = $this->goodsCommonModel->getGoodsList(array(
                    "shop_id" => $shop_id,
                    "common_state" => 1,
                    'common_verify' => 1
                ), array("common_salenum" => "desc"), 1, 5);


                $goods_collec_list = $this->goodsCommonModel->getGoodsList(array(
                    "shop_id" => $shop_id,
                    "common_state" => 1,
                    'common_verify' => 1
                ), array("common_collect" => "desc"), 1, 5);
            }

            if ($shop_base['is_renovation']) {

                //根据店铺id，查询出装修编号
                $cat_row['shop_id'] = $shop_id;
                $decoration_row = $this->shopDecorationModel->getOneByWhere($cat_row);

                //店铺装潢
                $decoration_detail = $this->shopDecorationModel->outputStoreDecoration($decoration_row['decoration_id'], $shop_id);
            }
            $title = Web_ConfigModel::value("shop_title");//首页名;
            $this->keyword = Web_ConfigModel::value("shop_keyword");//关键字;
            $this->description = Web_ConfigModel::value("shop_description");//描述;
            $this->title = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $title);
            $this->title = str_replace("{shopname}", $shop_base['shop_name'], $this->title);
            $this->keyword = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $this->keyword);
            $this->keyword = str_replace("{shopname}", $shop_base['shop_name'], $this->keyword);
            $this->description = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $this->description);
            $this->description = str_replace("{shopname}", $shop_base['shop_name'], $this->description);
        } else {
            $this->view->setMet('404');
        }


        //传递数据
        if ('json' == $this->typ) {
            $data['shop_base'] = empty($shop_base) ? array() : $shop_base;
            $data['shop_nav'] = empty($shop_nav) ? array() : $shop_nav;
            $data['shop_cat'] = empty($shop_cat) ? array() : $shop_cat;
            $data['goods_new_list'] = empty($goods_new_list) ? array() : $goods_new_list;
            $data['goods_recom_list'] = empty($goods_recom_list) ? array() : $goods_recom_list;
            $data['goods_selling_list'] = empty($goods_selling_list) ? array() : $goods_selling_list;
            $data['goods_collec_list'] = empty($goods_collec_list) ? array() : $goods_collec_list;
            $this->data->addBody(-140, $data);

        } else {
            include $this->view->getView();
        }
    }

    /**
     * 收藏店铺
     *
     * @author     Zhuyt
     */
    public function addCollectShop()
    {
        $shop_id = request_int('shop_id');
        $data = array();
        $User_FavoritesShopModel = new User_FavoritesShopModel();
        //开启事物
        $User_FavoritesShopModel->sql->startTransactionDb();
        $data = array();
        $data['msg'] = '';

        if (Perm::checkUserPerm()) {
            $user_id = Perm::$userId;
            //用户登录情况下,插入用户收藏商品表
            $add_row = array();
            $add_row['user_id'] = $user_id;
            $add_row['shop_id'] = $shop_id;

            $res = $User_FavoritesShopModel->getByWhere($add_row);

            if ($res) {
                $flag = false;
                $data['msg'] = __("您已收藏过该店铺！");

            } else {
                $Shop_BaseModel = new Shop_BaseModel();
                $shop_base = $Shop_BaseModel->getOne($shop_id);

                $add_row['shop_name'] = $shop_base['shop_name'];
                $add_row['shop_logo'] = $shop_base['shop_logo'];
                $add_row['favorites_shop_time'] = get_date_time();


                $User_FavoritesShopModel->addShop($add_row);

                //店铺详情中收藏数量增加
                $edit_row = array();
                $edit_row['shop_collect'] = '1';
                $flag = $Shop_BaseModel->editBaseCollectNum($shop_id, $edit_row, true);
                fb($flag);
                fb($shop_id);
            }

        } else {
            $flag = false;
            $data['msg'] = '请先登录';
        }

        if ($flag && $User_FavoritesShopModel->sql->commitDb()) {
            $status = 200;
            $msg = __('success');
            $data['msg'] = $data['msg'] ? $data['msg'] : __("收藏成功！");

            //店铺收藏成功添加数据到统计中心
            $analytics_data = array(
                'shop_id' => $shop_id,
                'date' => date('Y-m-d'),
            );
            Yf_Plugin_Manager::getInstance()->trigger('analyticsShopCollect', $analytics_data);
            /******************************************************/
        } else {
            $User_FavoritesShopModel->sql->rollBackDb();
            $m = $User_FavoritesShopModel->msg->getMessages();
            $msg = $m ? $m[0] : __('failure');
            $status = 250;
            $data['msg'] = $data['msg'] ? $data['msg'] : __("收藏失败！");
        }

        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function goodsList()
    {
        $Yf_Page = new Yf_Page();
        $Yf_Page->listRows = 20;
        $rows = $Yf_Page->listRows;
        $offset = request_int('firstRow', 0);
        $page = ceil_r($offset / $rows);

        $wap_pagesize = request_int('pagesize');
        $wap_curpage = request_int('curpage');

        if (!empty($wap_pagesize)) {
            $rows = $wap_pagesize;
        }

        if (!empty($wap_curpage)) {
            $page = $wap_curpage;
        }

        $shop_id = request_int('id');
        $sort = request_string('sort');
        if ($shop_id) {
            $this->shopCustomServiceModel = new Shop_CustomServiceModel;

            $cond_row['shop_id'] = $shop_id;

            $service = $this->shopCustomServiceModel->getServiceList($cond_row);

            if ($service['items']) {
                foreach ($service['items'] as $key => $val) {
                    //QQ
                    if ($val['tool'] == 1) {
                        $service[$key]["tool"] = "<a target='_blank' href='http://wpa.qq.com/msgrd?v=3&uin=" . $val['number'] . "&site=qq&menu=yes'><img border='0' src='http://wpa.qq.com/pa?p=2:" . $val['number'] . ":41 &amp;r=0.22914223582483828' alt='点击这里'></a>";
                    }
                    //旺旺
                    if ($val['tool'] == 2) {
                        $service[$key]["tool"] = "<a target='_blank' href='http://www.taobao.com/webww/ww.php?ver=3&amp;touid=" . $val['number'] . "&amp;siteid=cntaobao&amp;status=1&amp;charset=utf-8' ><img border='0' src='http://amos.alicdn.com/online.aw?v=2&amp;uid=" . $val['number'] . "&amp;site=cntaobao&s=1&amp;charset=utf-8' alt='点击这里' /></a>";
                    }
                    //IM
                    if ($val['tool'] == 3) {
                        $service[$key]["tool"] = '<a href="javascript:;" class="chat-enter" onclick="return chat(\'' . $val['number'] . '\');"><img src="' . $this->view->img . '/icon-im.gif" alt=""></a>';
                    }

                    //$service[$key]["tool"] = $val['tool'];
                    $service[$key]["number"] = $val['number'];
                    $service[$key]["name"] = $val['name'];
                    $service[$key]["id"] = $val['id'];

                    if ($val['type'] == 1) {
                        $de['after'][] = $service[$key];
                    } else {
                        $de['pre'][] = $service[$key];
                    }
                }
                $service = array();
                $service = $de;
            }
        }
        //店铺信息
        $shop_base = $this->shopBaseModel->getOne($shop_id);
        $shop_type = $shop_base['shop_type']; //店铺类型: 1-卖家店铺; 2:供应商店铺
        $ctl = 'Goods_Goods';
        if ($shop_type == 2) {
            $ctl = 'Supplier_Goods';
        }

        //2.评分信息
        $shop_detail = $this->shopBaseModel->getShopDetail($shop_id);
        $shop_scores_num = ($shop_detail['shop_desc_scores'] + $shop_detail['shop_service_scores'] + $shop_detail['shop_send_scores']) / 3;
        $shop_scores_count = sprintf("%.2f", $shop_scores_num);
        $shop_scores_percentage = $shop_scores_count * 20;

        if ($shop_base['shop_self_support'] == 'false') {
            $shop_all_base = $this->shopBaseModel->getbaseCompanyList($shop_id);
        }

        if (!empty($shop_base) && $shop_base['shop_status'] == 3) {
            //店铺幻灯和幻灯对应的连接
            $shop_slide = explode(",", $shop_base['shop_slide']);
            $shop_slide_url = explode(",", $shop_base['shop_slideurl']);
            //店铺导航
            $nav_cond_row = array(
                "shop_id" => $shop_id,
                "status" => 1
            );
            $nav_order_row = array("displayorder" => "asc");

            $shop_nav = $this->shopNavModel->listByWhere($nav_cond_row, $nav_order_row);

            if ($sort == 'desc') {
                $new_sort = 'asc';
            } else {
                $new_sort = 'desc';
            }


            $order_row = array();
            $cond_row = array();
            $search = request_string('search');
            $order = request_string('order');
            $shop_cat_id = request_int('shop_cat_id');
            $price_from = request_float('price_from');
            $price_to = request_float('price_to');
            $cond_row['shop_id'] = $shop_id;
            $Goods_CommonModel = new Goods_CommonModel();

            if ($search) {
                $cond_row['common_name:like'] = '%' . $search . '%';
            }

            if ($shop_cat_id) {
                $cond_row['shop_cat_id:like'] = '%' . ',' . $shop_cat_id . ',' . '%';
            }

            if ($price_from) {
                $cond_row['common_price:>='] = $price_from;
            }

            if ($price_to) {
                $cond_row['common_price:<='] = $price_to;
            }

            if ($order) {
                $order_row = array($order => $sort);
            }

            $cond_row['common_state'] = Goods_CommonModel::GOODS_STATE_NORMAL;
            $cond_row['common_verify'] = 1;
            if (request_string('type_wxapp') == 'wxapp' && request_string('type_wxapp')) {
                $cond_row['common_is_tuan'] = 0;
            }
            $datas = $Goods_CommonModel->getGoodsList($cond_row, $order_row, $page, $rows);

            $Yf_Page->totalRows = $datas['totalsize'];
            $page_nav = $Yf_Page->prompt();
            $data = $datas['items'];

        } else {
            $this->view->setMet('404');
        }

        if ('json' == $this->typ) {
            $arrayList = [];
            foreach ($datas['items'] as $k => $v) {
                if (!is_array($v['goods_id'])) {
                    $datas['items'][$k]['goods_id'] = [['goods_id' => $v['goods_id'], 'color_id' => 0]];
                }

                //wap版显示，商品上新。
                $check = substr($datas['items'][$k]['common_add_time'], 0, 10);
                if (!$arrayList[$check]) {
                    $datas['items'][$k]['goods_addtime_text'] = $check;
                    $arrayList[$check] = true;
                }

            }
            $this->data->addBody(-140, $datas);

        } else {
            include $this->view->getView();
        }

    }

    /**
     *
     * 获取店铺信息和推荐商品 wap
     */
    public function getStoreInfo()
    {
        $data = array();
        $store_info = array();
        $shop_id = request_int('shop_id');
        if (!$shop_id) {
            return $this->data->addBody(-140, $data, __('数据有误'), 250);
        }
        //读取店铺详情
        $shop_base = $this->shopBaseModel->getShopDetail($shop_id);
        if (!$shop_base) {
            return $this->data->addBody(-140, $data, __('数据有误'), 250);
        }
        $condi_rec_goods['shop_id'] = $shop_id;
        $condi_rec_goods['common_state'] = Goods_CommonModel::GOODS_STATE_NORMAL;

        $goods_common_list = $this->goodsCommonModel->getbywhere($condi_rec_goods);

        //读取推荐商品
        $condi_rec_goods['common_is_recommend'] = Goods_CommonModel::RECOMMEND_TRUE;
        $rec_goods_list = $this->goodsCommonModel->getGoodsList($condi_rec_goods);

        //判断当前店铺是否为用户所收藏
        $condi_u_f = array();
        $condi_u_f['user_id'] = Perm::$userId;
        $condi_u_f['shop_id'] = $shop_id;
        $userFavoritesShopModel = new User_FavoritesShopModel();
        $user_f_base = $userFavoritesShopModel->getByWhere($condi_u_f);
        if (empty($user_f_base)) {
            $u_f_shop = false;
        } else {
            $u_f_shop = true;
        }

        //店铺幻灯片
        $mb_sliders = array();
        if ($shop_base['shop_slide']) {
            $shop_slide = explode(",", $shop_base['shop_slide']);
            $shop_slide_url = explode(",", $shop_base['shop_slideurl']);

            if (!empty($shop_slide)) {
                foreach ($shop_slide as $key => $silde_img) {
                    if ($shop_slide_url[$key] && $silde_img) {
                        $sliders['link'] = $shop_slide_url[$key];
                        $sliders['imgUrl'] = $silde_img;
                        array_push($mb_sliders, $sliders);
                    }
                }
            }
        }

        $store_info['goods_count'] = count($goods_common_list);
        $store_info['is_favorate'] = $u_f_shop;
        $store_info['is_own_shop'] = $shop_base['shop_self_support'];
        $store_info['mb_sliders'] = $mb_sliders;
        $store_info['mb_title_img'] = $shop_base['shop_banner'];
        $store_info['member_id'] = Perm::$userId;
        $store_info['store_avatar'] = $shop_base['shop_logo'];
        $store_info['user_name'] = $shop_base['user_name'];
        $store_info['store_collect'] = $shop_base['shop_collect'];
        $store_info['store_credit_text'] = sprintf('描述: %.2f, 服务: %.2f, 物流: %.2f', $shop_base['shop_desc_scores'], $shop_base['com_service_scores'], $shop_base['shop_send_scores']);        //描述: 5.0, 服务: 5.0, 物流: 5.0
        $store_info['shop_id'] = $shop_base['shop_id'];
        $store_info['store_name'] = $shop_base['shop_name'];
        $store_info['user_id'] = $shop_base['user_id'];
        $store_info['store_tel'] = $shop_base['shop_tel'];
        $store_info['is_open_im'] = Yf_Registry::get('im_statu');
        //获取店铺IM
        if ($shop_base['shop_self_support'] == 'true' && Web_ConfigModel::value('self_shop_im')) {
            $store_info['self_shop_im'] = $this->shopBaseModel->getSelfShopIm($shop_base['user_name'], $shop_base['shop_self_support']);
        }
        $data['rec_goods_list'] = $rec_goods_list['items'];
        $data['rec_goods_list_count'] = count($rec_goods_list['items']);
        $data['store_info'] = $store_info;
        //获取代金券信息
        $voucher_model = new Voucher_TempModel();
        $voucher_list = $voucher_model->getShopVoucher($shop_id);

        $data['voucher_list'] = $voucher_list['items'] ? $voucher_list['items'] : array();
        return $this->data->addBody(-140, $data);

    }

    /**
     *
     * wap 获取店铺满送 限时
     */

    public function getShopPromotion()
    {
        $mansong = array();
        $xianshi = array();
        $promotion = array();

        $discountBaseModel = new Discount_BaseModel();
        $manSongBaseModel = new ManSong_BaseModel();

        $shop_id = request_int('shop_id');

        //限时
        $discount_list = $discountBaseModel->getDiscountActList(array('discount_state' => Discount_BaseModel::NORMAL, 'shop_id' => $shop_id));
        $xianshi = $discount_list['items'];

        //满送
        $mansong_list = $manSongBaseModel->getManSongActList(array('mansong_state' => ManSong_BaseModel::NORMAL, 'shop_id' => $shop_id));
        $mansong_list_f = $mansong_list['items'];
        fb($mansong_list_f);

        if ($mansong_list_f) {
            foreach ($mansong_list_f as $maskey => $masval) {
                $mansong[] = $manSongBaseModel->getManSongActItem(array('shop_id' => $shop_id, 'mansong_id' => $masval['mansong_id']));
            }

        } else {
            $mansong = $mansong_list_f;
        }

        //当店铺没有满送活动和即时活动的时候对应字段返回默认值，防止App接收数据崩溃
        $flag_xianshi = false;
        if (!$mansong && 'json' == request_string('typ')) {
            $mansong['mansong_id'] = 0;
            $mansong['mansong_name'] = '';
            $mansong['combo_id'] = 0;
            $mansong['mansong_start_time'] = '2017-04-01 10:00:00';
            $mansong['mansong_end_time'] = '2017-04-01 11:00:00';
            $mansong['user_id'] = 0;
            $mansong['shop_id'] = 0;
            $mansong['user_nickname'] = '';
            $mansong['shop_name'] = '';
            $mansong['mansong_state'] = 2;
            $mansong['mansong_remark'] = '';
            $mansong['id'] = 0;
            $mansong['mansong_state_label'] = '已关闭';
            $mansong['rule']['rule_id'] = 0;
            $mansong['rule']['mansong_id'] = 0;
            $mansong['rule']['rule_price'] = 0;
            $mansong['rule']['rule_discount'] = 0;
            $mansong['rule']['goods_name'] = '';
            $mansong['rule']['goods_id'] = 0;
            $mansong['rule']['id'] = 0;
            $mansong['rule']['goods_price'] = 0;
            $mansong['rule']['goods_image'] = '';
            $mansong[] = $mansong;
            $flag_mansong = true;
        }

        $flag_xianshi = false;
        if (!$xianshi && 'json' == request_string('typ')) {
            $xianshi['discount_id'] = 0;
            $xianshi['discount_name'] = '';
            $xianshi['discount_title'] = '';
            $xianshi['discount_explain'] = '';
            $xianshi['combo_id'] = 0;
            $xianshi['discount_start_time'] = '2017-04-01 10:00:00';
            $xianshi['discount_end_time'] = '2017-04-01 11:00:00';
            $xianshi['user_id'] = 0;
            $xianshi['shop_id'] = 0;
            $xianshi['user_nick_name'] = '';
            $xianshi['shop_name'] = '';
            $xianshi['discount_lower_limit'] = 0;
            $xianshi['discount_state'] = 0;
            $xianshi['id'] = 0;
            $xianshi['discount_state_label'] = '已关闭';
            $xianshi[] = $xianshi;
            $flag_xianshi = true;
        }

        if ($flag_mansong && $flag_xianshi) {
            $promotion['count'] = 0;
        } else {
            $promotion['count'] = 1;
        }

        $promotion['mansong'] = $mansong;
        $promotion['xianshi'] = $xianshi;


        $data['promotion'] = $promotion;

        $this->data->addBody(-140, $data);
    }

    /**
     * 店铺详细信息
     */
    public function getStoreIntro()
    {
        $data = array();
        $shop_id = request_int('shop_id');

        $shop_base = $this->shopBaseModel->getShopDetail($shop_id);

        //判断当前店铺是否为用户所收藏
        $condi_u_f = array();
        $condi_u_f['user_id'] = Perm::$userId;
        $condi_u_f['shop_id'] = $shop_id;
        $userFavoritesShopModel = new User_FavoritesShopModel();
        $user_f_base = $userFavoritesShopModel->getByWhere($condi_u_f);
        if (empty($user_f_base)) {
            $u_f_shop = false;
        } else {
            $u_f_shop = true;
        }
        $shop_base['is_favorate'] = $u_f_shop;

        $data['store_info'] = $shop_base;
        $this->data->addBody(-140, $data);
    }


    /**
     * 获取分销员分销的商品
     */
    public function directsellerGoodsList()
    {
        $uid = request_int('uid')?:Perm::$userId;
        $sort = request_string('sort');

        $cond_row['directseller_id'] = $uid;
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

        $act = request_string('act');
        $actorder = request_string('actorder', 'DESC');

        if ($act !== '') {
            //销量
            if ($act == 'sales') {
                $order_row['common_salenum'] = $actorder;
            }

            //佣金排序
            if ($act == 'price') {
                if (request_string('actorder')) {
                    $order_row['common_price'] = $actorder;
                } else {
                    $order_row['common_price'] = 'ASC';
                }
            }

            //时间排序
            if ($act == 'uptime') {
                $order_row['common_add_time'] = $actorder;
            }

        } else {
            $order_row['common_id'] = 'DESC';
        }

        //获取推广商品
        $data = array();
        $Goods_CommonModel = new Goods_CommonModel();
        $data = $Goods_CommonModel->getCommonList($cond_good_row, $order_row, $page, $rows);
        $data['user_id'] = $uid;

        //获取店铺名称
        $data['shop'] = $Distribution_ShopDirectsellerModel->getOneByWhere(array('directseller_id' => $uid));
        $data['shop_qrcode'] = Yf_Registry::get('shop_wap_url') . "/tmpl/member/directseller_store.html?uid=" . $uid;
        $data['shop_wap_qrcode'] = Yf_Registry::get('base_url') . "/shop/api/qrcode.php?data=" . urlencode(Yf_Registry::get('shop_wap_url') . "/tmpl/member/directseller_store.html?uid=" . $uid);
        $Yf_Page->totalRows = $data['totalsize'];
        $page_nav = $Yf_Page->prompt();

        if ('json' == $this->typ) {
            $this->data->addBody(-140, $data);

        } else {
            include $this->view->getView();
        }

    }


    public function info()
    {
        $shop_id = request_int('id');
        $shop_base = $this->shopBaseModel->getShopDetail($shop_id);
        //2.评分信息
        $shop_detail = $this->shopBaseModel->getShopDetail($shop_id);
        $shop_scores_num = ($shop_detail['shop_desc_scores'] + $shop_detail['shop_service_scores'] + $shop_detail['shop_send_scores']) / 3;
        $shop_scores_count = sprintf("%.2f", $shop_scores_num);
        $shop_scores_percentage = $shop_scores_count * 20;

        $nav_cond_row = array(
            "shop_id" => $shop_id,
            "status" => 1
        );
        $nav_order_row = array("displayorder" => "asc");
        //店铺导航
        $shop_nav = $this->shopNavModel->listByWhere($nav_cond_row, $nav_order_row);

        $nav_id = request_int('nav_id');
        $data = $this->shopNavModel->getOne($nav_id);

        if ($shop_base['shop_self_support'] == 'false') {
            $shop_all_base = $this->shopBaseModel->getbaseCompanyList($shop_id);
        }
        if ($shop_id) {
            $service = $this->getService($shop_id);
        }

        if ('json' == $this->typ) {
            $this->data->addBody(-140, $data);

        } else {
            include $this->view->getView();
        }
    }

    public function getService($shop_id)
    {
        $this->shopCustomServiceModel = new Shop_CustomServiceModel;
        $cond_row['shop_id'] = $shop_id;
        $service = $this->shopCustomServiceModel->getServiceList($cond_row);
        if ($service['items']) {
            foreach ($service['items'] as $key => $val) {
                $service[$key]["tool"] = $val["tool"] == 2 ? "<a target='_blank' href='http://www.taobao.com/webww/ww.php?ver=3&amp;touid=" . $val['number'] . "&amp;siteid=cntaobao&amp;status=1&amp;charset=utf-8' ><img border='0' src='http://amos.alicdn.com/online.aw?v=2&amp;uid=" . $val['number'] . "&amp;site=cntaobao&s=1&amp;charset=utf-8' alt='点击这里' /></a>" : "<a target='_blank' href='http://wpa.qq.com/msgrd?v=3&uin=" . $val['number'] . "&site=qq&menu=yes'><img border='0' src='http://wpa.qq.com/pa?p=2:" . $val['number'] . ":41 &amp;r=0.22914223582483828' alt='点击这里'></a>";
                //$service[$key]["tool"] = $val['tool'];
                $service[$key]["number"] = $val['number'];
                $service[$key]["name"] = $val['name'];
                $service[$key]["id"] = $val['id'];

                if ($val['type'] == 1) {
                    $de['after'][] = $service[$key];
                } else {
                    $de['pre'][] = $service[$key];
                }
            }
            $service = array();
            $service = $de;
        }

        return $service;
    }

    /**
     * 获取商家的代金券信息
     * @return type
     */
    public function getShopVoucher()
    {
        //获取代金券信息
        $shop_id = request_int('shop_id');
        $data = array();
        if (!$shop_id) {
            return $this->data->addBody(-140, $data, __('数据有误'), 250);
        }
        $Voucher_TempModel = new Voucher_TempModel();
        $voucher_list = $Voucher_TempModel->getShopVoucher($shop_id);
        if ($voucher_list['items']) {
            $data['items'] = $voucher_list['items'];
        }
        //获取我的优惠券

        $data['items'] = $voucher_list['items'] ? $voucher_list['items'] : array();
        return $this->data->addBody(-140, $data);

    }

}

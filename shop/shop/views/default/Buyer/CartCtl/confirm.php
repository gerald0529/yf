<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'site_nav.php';
?>

    <link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/shop-cart.css" />
    <script type="text/javascript" src="<?= $this->view->js ?>/cart.js"></script>
    <script type="text/javascript" src="<?= $this->view->js ?>/alert.js"></script>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
    <link type="text/css" rel="stylesheet" href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css">
    <link href="<?= $this->view->css ?>/tips.css" rel="stylesheet">
    <script type="text/javascript" src="<?= $this->view->js ?>/common.js"></script>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.toastr.min.js" charset="utf-8"></script>
    <style>
        .nctouch-cart-item li .goods-info dt.goods-name a{
            word-wrap: break-word;
            word-break: normal;
        }
        .nctouch-cart-item li .goods-info dd.goods-type{
            position: absolute;
            top: 50px;
        }
    </style>

    <div class="cart-head">
        <div class="wrap">
            <div class="head_cont clearfix">
                <div class="nav_left" style="float:none;">
                    <a href="index.php" class=""><img src="<?= $this->web['web_logo'] ?>" /></a>
                    <a href="#" class="download iconfont"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="wrap">
        <div class="shop_cart_head clearfix">
            <div class="cart_head_left">
                <h4><?= __('确认订单') ?></h4>

            </div>
            <div class="cart-head-module clearfix">
                <p class="tips-p"><span><i class="iconfont icon-orders-tips"></i></span><?= __('请仔细核对收货,发货等信息,以确保物流快递能准确投递') ?>.</p>
                <ul class="cart_process">
                    <li class="mycart">
                        <div class="fl">
                            <i class="iconfont icon-wodegouwuche bbc_color"></i>
                            <h4><?= __('我的购物车') ?><h4>
                        </div>

                    </li>
                    <li class="mycart process_selected1">
                        <div class="fl to"></div>
                        <div class="fl">
                            <i class="iconfont icon-iconquerendingdan bbc_color"></i>
                            <h4 class=""><?= __('确认订单') ?><h4>
                        </div>


                    </li>
                    <li class="mycart">
                        <div class="fl to"></div>
                        <div class="fl">
                            <i class="iconfont icon-icontijiaozhifu"></i>
                            <h4><?= __('支付提交') ?><h4>
                        </div>


                    </li>
                    <li class="mycart">
                        <div class="fl to"></div>
                        <div class="fl">
                            <i class="iconfont icon-dingdanwancheng"></i>
                            <h4><?= __('订单完成') ?><h4>
                        </div>

                    </li>
                </ul>
            </div>

        </div>
        <ul class="receipt_address clearfix">
            <div id="address_list">
                <?php if (isset($data['address'])) {
                    $total = 0;
                    $total_dian_rate = 0;
                    foreach ($data['address'] as $key => $value) {
                        ?>
                        <li class="<?php if (!$address_id && $value['user_address_default'] == 1) { ?>add_choose<?php } ?><?php if ($address_id && $value['user_address_id'] == $address_id) { ?>add_choose<?php } ?> " id="addr<?= ($value['user_address_id']) ?>">
                            <input type="hidden" id="address_id" value="<?= ($value['user_address_id']) ?>">
                            <input type="hidden" id="user_address_province_id" value="<?= ($value['user_address_province_id']) ?>">
                            <input type="hidden" id="user_address_city_id" value="<?= ($value['user_address_city_id']) ?>">
                            <input type="hidden" id="user_address_area_id" value="<?= ($value['user_address_area_id']) ?>">
                            <div class="editbox">
                                <a class="edit_address" data_id="<?= ($value['user_address_id']) ?>"><?= __('编辑') ?></a>
                                <a class="del_address" data_id="<?= ($value['user_address_id']) ?>"><?= __('删除') ?></a>
                            </div>
                            <h5 class="one-overflow wp70"><?= ($value['user_address_contact']) ?></h5>
                            <p class="addr-len"><?= ($value['user_address_area']) ?> <?= ($value['user_address_address']) ?></p><span class="phone"><?= ($value['user_address_phone']) ?></span>

                        </li>
                    <?php }
                } ?>
                <script>
                    $(function () {
                        if (".addr-len") {
                        }
                    })
                </script>
            </div>
            <div class="add_address">
                <a><?= __('+') ?></a>
            </div>
        </ul>

        <h4 class="confirm"><?= __('支付方式') ?></h4>
        <div class="pay_way pay-selected mar-b-52" pay_id="1">
            <i></i><?= __('在线支付') ?>
        </div>
        <div class="pay_way" pay_id="2">
            <i></i><?= __('货到付款') ?>
        </div>

        <h4 class="confirm"><?= __('确认商品信息') ?></h4>
        <div class="cart_goods">
            <ul class='cart_goods_head clearfix'>
                <li class="price_all price_all-z"><?= __('小计') ?>(<?= (Web_ConfigModel::value('monetary_unit')) ?>)</li>
                <li class="confirm_sale confirm_sale-z"><?= __('优惠') ?></li>
                <li class="goods_num goods_num-z"><?= __('数量') ?></li>
                <li class="goods_price goods_price-z"><?= __('单价') ?>(<?= (Web_ConfigModel::value('monetary_unit')) ?>)</li>
                <li class="goods_name goods_name-z2"><?= __('商品信息') ?></li>
                <li class="goods_name goods_name-z"><?= __('商品') ?></li>
                <li class="cart_goods_all"></li>
            </ul>

            <?php unset($data['glist']['count']);
            $total_voucher_price = 0;
            foreach ($data['glist'] as $key => $val) { ?>

                <!-- S 计算店铺的会员折扣和总价 -->
                <?php
                $reduced_money = 0;//满送活动优惠的金额单独赋予一个变量
                $voucher_money = 0;//代金券活动优惠的金额单独赋予一个变量
                //判断后台是否开启了会员折扣，如果开启会员折扣则判断是否为自营店铺。计算店铺的折扣
                if (!Web_ConfigModel::value('rate_service_status') || (Web_ConfigModel::value('rate_service_status') && $val['shop_self_support'] == 'true')) {
                    $dian_rate = ($val['sprice'] - $val['mansong_info']['rule_discount']) * (100 - $user_rate) / 100;
                } else {
                    $dian_rate = 0;
                }

                //扣除折扣后店铺的店铺价格（本店合计）
                $shop_all_cost = number_format($val['sprice'], 2, '.', '');
                // $shop_all_cost = number_format($val['sprice']-$dian_rate,2,'.','');
                //所有店铺代最优金券优惠金额
                if ($val['promotion'] == 1) $val['best_voucher'][0]['price'] = 0;
                $total_voucher_price += $val['best_voucher'][0]['price'];
                $promotion += $val['promotion']; //判断是否有活动商品

                ?>
                <div class="wrap">
                    <!-- E 计算店铺的会员折扣和总价 -->
                    <ul class="cart_goods_list clearfix">
                        <li>
                            <div class="bus_imfor bus_imfor-z clearfix">
                                <p class="bus_name">
                            <span>
                                <i class="iconfont icon-icoshop"></i>
                                <span><?= ($val['shop_name']) ?></span>
                                <?php if ($val['shop_self_support'] == 'true') { ?>
                                    <span><?= __('自营店铺') ?></span>
                                <?php } ?>
                            </span>
                                </p>
                            </div>
                            <table>
                                <tbody class="rel_good_infor rel_good_infor2">
                                <?php foreach ($val['goods'] as $k => $v) { ?>
                                    <tr>
                                        <td class="goods_sel">
                                            <p>
                                                <input type="hidden" name="cart_id" value="<?= ($v['cart_id']) ?>">
                                            </p>
                                        </td>
                                        <td class="goods_img goods_img-z"><img src="<?= ($v['goods_base']['goods_image']) ?>" /></td>
                                        <td class="goods_name_reset goods_name_reset-z">
                                            <span class="p-name"><?= ($v['goods_base']['goods_name']) ?></span>
                                        </td>
                                        <!--新增商品信息-->
                                        <td class="goods_price goods_name-z2">
                                            <?php if (!empty($v['goods_base']['spec'])) {
                                                foreach ($v['goods_base']['spec'] as $sk => $sv) { ?>
                                                    <p class="now_price"><?= ($sv) ?></p>
                                                <?php }
                                            } ?>
                                            <!--1.26新增-->
                                            <!--<div class="props-txt">颜色：黄色</div>
                                            <div class="props-txt">尺码：X</div>-->
                                        </td>
                                        <!--新增商品信息END-->
                                        <td class="goods_price goods_price-z">
                                            <?php if ($v['old_price'] > 0) { ?><p class="ori_price"><?= ($v['old_price']) ?></p><?php } ?>
                                            <p class="now_price"><?= ($v['now_price']) ?></p>

                                        </td>
                                        <td class="goods_num goods_num-z">
                                            <span><?= ($v['goods_num']) ?></span>
                                        </td>
                                        <td class="confirm_sale confirm_sale-z">
                                            <?php if (isset($v['goods_base']['promotion_type'])): ?>
                                                <?php if ($v['goods_base']['promotion_type'] == 'groupbuy' && $v['goods_base']['groupbuy_starttime'] < date('Y-m-d H:i:s') && $v['goods_base']['groupbuy_endtime'] > date('Y-m-d H:i:s')): ?>
                                                    <p class="sal_price"><?= __('团购') ?></p>
                                                    <?php if ($v['goods_base']['down_price']): ?><p><?= __('直降') ?><?= format_money($v['goods_base']['down_price']) ?></p><?php endif; ?>
                                                <?php endif; ?>
                                                <?php if ($v['goods_base']['promotion_type'] == 'xianshi' && $v['goods_base']['groupbuy_starttime'] < date('Y-m-d H:i:s') && $v['goods_base']['groupbuy_endtime'] > date('Y-m-d H:i:s')): ?>
                                                    <p class="sal_price"><?= __('限时折扣') ?></p>
                                                    <?php if ($v['goods_base']['down_price']): ?><p><?= __('每件直降') ?><?= format_money($v['goods_base']['down_price']) ?></p><?php endif; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="price_all price_all-z">
                                            <span class="subtotal"><?= ($v['sumprice']) ?></span>
                                            <?php if (!$v['buy_able']) { ?><p class="colred"><?= __('无货') ?></p><?php } ?>
                                        </td>
                                    </tr>
                                    <!-- 判断加价购是否过期 -->
                                    <?php if($val['jia_jia_gou']){?>
                                        <tr>
                                            <td colspan=8>
                                                <!-- 后期修改加价购2017.8.2 -->
                                                <?php
                                                $cart_total_price = $v['now_price'] * $v['goods_num'];
                                                ?>
                                                <?php if ($v['goods_base']['increase_info']) { ?>
                                                    <div class="more-buy fz0">
                                                        <h4><?= __('加价购') ?></h4>
                                                        <div class="inline morder-buy-con">
                                                            <p class="sel-goods">
                                                                    <span>
                                                                        <?= __('购物满') ?>
                                                                        <?= (Web_ConfigModel::value('monetary_unit')) ?>
                                                                        <?php echo $v['goods_base']['increase_info']['rule'][0]['rule_price']; ?>
                                                                        <?php if ($v['goods_base']['increase_info']['rule'][0]['rule_goods_limit'] > 0) { ?>，
                                                                            <?= __('最多可购') ?>
                                                                            <?php echo $v['goods_base']['increase_info']['rule'][0]['rule_goods_limit']; ?>
                                                                            <?= __('件') ?>
                                                                        <?php } ?>
                                                                    </span>
                                                                <i class="icon"></i>
                                                            </p>
                                                            <!-- 点击.sel-goods下拉列表 -->
                                                            <div class="quan-ar jia-shop-are">
                                                                <div class="jia-gou-height">
                                                                    <table class="wp100">
                                                                        <!-- 遍历div.item-li -->
                                                                        <?php foreach ($v['goods_base']['increase_info']['rule'] as $increasekey => $increaseval) { ?>
                                                                            <?php if ($cart_total_price > $increaseval['rule_price'] || $cart_total_price == $increaseval['rule_price']) { ?>
                                                                                <?php foreach ($increaseval['redemption_goods'] as $redempotionkey => $redempotionval) { ?>
                                                                                    <?php if ($increaseval['rule_goods_limit'] == 0) {
                                                                                        $increaseval['rule_goods_limit'] = $redempotionval['goods_stock'];
                                                                                    } ?>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <div class="clearfix bgf item-li <?php echo $v['goods_base']['increase_info']['shop_id']; ?>">
                                                                                                <p class="tit-tip">
                                                                                                    <input class="select_increase" rule_id="<?php echo $increaseval['rule_id']; ?>" goods_price="<?php echo $redempotionval['redemp_price']; ?>" goods_total_price="<?= (number_format($val['sgprice'], 2, '.', '')) ?>" shop_price="<?= (number_format($val['sprice'], 2, '.', '')) ?>" shop_id="<?php echo $v['goods_base']['increase_info']['shop_id']; ?>" type="checkbox" data-promotion_goods_id="<?php echo $v['goods_base']['goods_id']; ?>">
                                                                                                    <label for="jjg_rule16">
                                                                                                        <span><?= __('购物满') ?><?= (Web_ConfigModel::value('monetary_unit')) ?><?php echo $increaseval['rule_price']; ?><?php if ($increaseval['rule_goods_limit'] > 0) { ?>，<?= __('最多可购') ?><?php echo $increaseval['rule_goods_limit']; ?><?= __('件') ?><?php } ?></span>
                                                                                                    </label>
                                                                                                </p>
                                                                                                <ul class="nctouch-cart-item">
                                                                                                    <!-- 活动规则加价商品 -->
                                                                                                    <li class="buy-item">
                                                                                                        <div class="bgf6 buy-li pd10 p-relative">
                                                                                                            <div class="goods-pic">
                                                                                                                <a href="javascript:;">
                                                                                                                    <img class="mh60" src="<?php echo $redempotionval['goods_image']; ?>">
                                                                                                                </a>
                                                                                                            </div>
                                                                                                            <dl class="goods-info">
                                                                                                                <dt class="goods-name">
                                                                                                                    <a href="javascript:;">
                                                                                                                        <?= $redempotionval['goods_name']; ?>
                                                                                                                    </a>
                                                                                                                </dt>
                                                                                                                <dd class="goods-type" title="<?= $redempotionval['goods_spec']; ?>"><?= $redempotionval['goods_spec']; ?></dd>
                                                                                                            </dl>
                                                                                                            <div class="goods-subtotal">
                                                                                                                    <span class="goods-price"><?= (Web_ConfigModel::value('monetary_unit')) ?>
                                                                                                                        <em><?= $redempotionval['redemp_price']; ?></em>
                                                                                                                    </span>
                                                                                                            </div>
                                                                                                            <div class="goods-num">
                                                                                                                <em>x<?= $redempotionval['goods_stock']; ?></em>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="jia-shop clearfix">
                                                                                                            <p class="fl"><?= __('加价购') ?>
                                                                                                                <em><?= (Web_ConfigModel::value('monetary_unit')) ?><?= $redempotionval['redemp_price']; ?></em>
                                                                                                            </p>
                                                                                                            <div class="fr mrt4 JS_operation">
                                                                                                                <div class="num-sel">
                                                                                                                    <a class="declick" href="javascript:;">-</a>
                                                                                                                    <input class="increase_num" goods_id="<?php echo $redempotionval['goods_id']; ?>" data-max="<?php echo $increaseval['rule_goods_limit']; ?>" goods_price="<?php echo $redempotionval['redemp_price']; ?>" type="text" value="1">
                                                                                                                    <a class="inclick">+</a>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </li>
                                                                                                </ul>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php } } } ?>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php }?>
                                <?php } ?>
                                </tbody>
                            </table>
                        </li>
                    </ul>

                    <div class="goods_remark clearfix">
                        <!--1.9店铺代金券-->
                        <div class="tlr bgf z-bor clearfix">
                            <div class="inline fl mw-800">
                                <p class="tl fl mb10">
                                    <span class="activity-dt lh-28">店铺代金券</span>
                                    <?php
                                    $count_v = 0;
                                    foreach ($val['voucher_base'] as $v) {
                                        $count_v += $v['isable'];
                                    }
                                    ?>
                                    <?php if ($data['is_discount'] == 1) { ?>
                                        <span voucher_id="0" class="no_voucher isFavoritesShop lh-28">代金券不与会员折扣共用</span>
                                    <?php } else { ?>
                                        <?php if ($val['promotion'] == 1) { ?>
                                            <span class="no_voucher lh-28">涉及活动商品不可使用店铺代金券</span>
                                        <?php } else { ?>
                                            <input type="hidden" name="voucher" value="<?php if ($count_v > 0) { ?><?php if ($val['voucher_base']) { ?>请选择优惠劵<?php } else { ?>暂无可用的优惠券<?php } ?><?php } else { ?>暂无可用的优惠券<?php } ?>">
                                            <?php if ($count_v > 0) { ?>

                                                <?php if ($val['voucher_base']) { ?>
                                                    <select class="w400 select z-select shop_voucher<?= $val['shop_id'] ?> shop_voucher">
                                                        <option></option>
                                                        <?php if ($val['voucher_base']) { ?>
                                                            <?php foreach ($val['voucher_base'] as $voukey => $vouval) { ?>
                                                                <!--判断店铺合计是否满足代金券的使用条件-->
                                                                <option value="<?= $vouval['price'] ?>" voucher_id="<?= $vouval['id'] ?>" shop_id="<?= $val['shop_id'] ?>" <?php if ($vouval['id'] == $val['best_voucher'][0]['id']) { ?> selected="selected" <?php } ?> <?php if ($vouval['isable'] == 0) { ?> disabled="disabled" <?php } ?> voucher_price="<?= $vouval['price'] ?>">满<?= $vouval['limit'] ?>减<?= $vouval['price'] ?> <time><?= date('Y-m-d', strtotime($vouval['end_time'])) ?><?= __('到期') ?></time>
                                                                </option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        <?php if ($count_v > 0) { ?>
                                                            <option value="0" voucher_id="0" shop_id="<?= ($val['shop_id']) ?>"><?= __('不使用店铺优惠券') ?></option>
                                                        <?php } ?>
                                                    </select>
                                                <?php } else { ?>
                                                    <input type="text" value="暂无可用的优惠券" disabled="disabled" class="w390">
                                                <?php } ?><?php } else { ?>
                                                <input type="text" value="暂无可用的优惠券" disabled="disabled" class="w390">
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                    <input type="hidden" name="best_voucher<?= ($val['shop_id']) ?>" value="<?= $val['best_voucher'][0]['price'] ?>">
                                </p>
                                <!--如果有购物车商品所属店铺优惠券，则默认显示优惠券使用0-->
                                <!--1.9新注释-->
                                <?php if ($val['promotion'] == 0) { ?>
                                    <?php if ($val['voucher_base']) { ?>
                                        <input type="hidden" name="best_voucher<?= ($val['shop_id']) ?>" value="<?= $val['best_voucher'][0]['price'] ?>">
                                        <p class=" vou-sels shop_voucher<?= ($key) ?>" style="display:none">
                                            <span>代金券优惠</span>
                                            <i shop_voucher="<?= $voucher_money ?>">
                                                -<?= $val['best_voucher'][0]['price'] ?>
                                            </i>
                                        </p>
                                    <?php } ?>
                                <?php } ?>

                                <p class="remarks "><span class="remarks-z activity-dt activity-dt"><?= __('备注') ?></span><input type="text" class="remarks_content" name="remarks" id="<?= ($val['shop_id']) ?>" placeholder="<?= __('限45个字（定制类商品，请将购买需求在备注中做详细说明）') ?>"><?= __('提示：请勿填写有关支付、收货、发票方面的信息') ?></p>
                            </div>
                            <div class="order_total">
                                <p class="clearfix">
                                    <span><?= __('商品金额') ?></span>
                                    <i class="price<?= ($val['shop_id']) ?>" total_shop_price="<?= (number_format($val['sgprice'], 2, '.', '')) ?>"><?= (number_format($val['sgprice'], 2, '.', '')) ?></i>
                                </p>
                                <p class="clearfix trans<?= ($key) ?>">
                                    <span><?= __('物流运费') ?></span>
                                    <?php if ($data['cost'][$key]['cost'] > 0) { ?>
                                        <strong class="trancon<?= ($val['shop_id']) ?>"><?= ($data['cost'][$key]['con']) ?></strong>
                                        <i class="trancost<?= ($val['shop_id']) ?>">
                                            <?= (number_format($data['cost'][$key]['cost'], 2)) ?>
                                            <input type="hidden" class="shop_trancost<?= ($val['shop_id']) ?>" value="<?= (number_format($data['cost'][$key]['cost'], 2)) ?>">
                                        </i>
                                    <?php } else { ?>
                                        <i class="trancost<?= ($key) ?>">0</i>
                                        <input type="hidden" class="shop_trancost<?= ($key) ?>" value="0.00">
                                    <?php } ?>
                                </p>

                                <p class="dian_total clearfix">
                                    <span class=""><?= __('本店合计') ?></span>
                                    <em></em>
                                    <i class="sprice<?= ($val['shop_id']) ?> sprice">
                                        <?php echo number_format($val['sprice'], 2, '.', ''); ?>
                                    </i>
                                </p>

                                <!--新增-->
                                <?php if (!empty($val['mansong_info'])) { ?>
                                    <?php if ($val['mansong_info']['rule_discount']) { ?>
                                        <?php $reduced_money = $val['mansong_info']['rule_discount']; ?>
                                        <p class="clearfix">
                                            <span><i class="iconfont icon-manjian fln mr4 f22 middle"></i><?= __('满') ?><?= ($val['mansong_info']['rule_price']) ?><?= __('立减') ?><?= ($val['mansong_info']['rule_discount']) ?></span>
                                            <em></em>
                                            <i class="msprice<?= ($key) ?>" msprice="<?= ($val['mansong_info']['rule_discount']) ?>">
                                                -<?= ($val['mansong_info']['rule_discount']) ?>
                                            </i>
                                        </p>
                                    <?php } ?>
                                    <?php if ($val['mansong_info']['gift_goods_id']) { ?>
                                        <?= __('<p>送') ?>&nbsp;<a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&gid=<?= ($val['mansong_info']['gift_goods_id']) ?>"><img title="<?= ($val['mansong_info']['goods_name']) ?>" alt="<?= ($val['mansong_info']['goods_name']) ?>" src="<?= image_thumb($val['mansong_info']['goods_image'], 60, 60) ?>"></a>
                                        </p>
                                        <?= ($val['mansong_info']['goods_name']) ?>
                                    <?php } ?>
                                <?php } ?>

                                <?php if (isset($val['distributor_rate'])) { ?>
                                    <p class="clearfix">
                                        <span><?= __('分销商优惠') ?></span>
                                        <i><?= number_format($val['distributor_rate'], 2, '.', '') ?></i>
                                    </p>
                                <?php } ?>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- S 平台红包  只有自营店铺可以使用平台红包-->
                <input type="hidden" class="redpacked_id redpacket_<?= ($key) ?>">
                <?php if ($val['shop_self_support'] == 'true' && $data['rpt_list']) { ?>
                    <div class="hongb redpacket<?= ($key) ?>">

                    </div>
                <?php } ?>
                <!--- E 平台红包 ---->
                <?php
                $total += $val['sprice'];
                $total_dian_rate += $dian_rate;
                //促销活动优惠的价格单独赋值一个变量
                $promotion_reduced[] = $reduced_money; //满减
                $voucher_reduced[] = $voucher_money;   //优惠劵
                // $promotion_money = array_sum($promotion_reduced) + array_sum($voucher_reduced);//促销活动优惠的总价格
                $redpacket_price = 0;//记录红包价格
                if ($data['rpt_info']) {
                    if ($promotion > 0) {
                        $redpacket_price = 0;
                    } else {
                        $redpacket_price = $data['rpt_info'][0]['price'];
                    }
                } else {
                    $redpacket_price = 0;
                }
            } ?>

            <div class="frank clearfix">
                <div class="invoice tl">
                    <div class="invoice-cont clearfix">
                        <div class="fl h-30 lh-28 activity-dt">平台红包</div>
                        <?php
                        $count_r = 0;
                        foreach ($data['rpt_list'] as $vr) {
                            $count_r += $vr['isable'];
                        }
                        ?>
                        <div class="inline" id="redpacketBox">
                            <input id="redp" value="平台红包不与会员折扣共用" readonly style="display:none;width:180px">
                            <input name="promotion" value="<?= ($val['promotion']) ?>" style="display:none;width:180px">
                            <?php if($data['is_discount'] == 1){ ?>
                                <input name="no_redpacket" value="平台红包不与会员折扣共用" readonly style="width:180px;font-size: 14px" class="isFavoritesShop lh-28">
                            <?php }else{ ?>
                                <?php if ($promotion > 0) { ?>
                                    <input name="goods_promotion" value="涉及活动商品不可使用平台红包" readonly style="width:180px;margin-top: 6px;">
                                <?php } else { ?>
                                    <input type="hidden" name="redpacket" value="<?php if ($count_r > 0) { ?><?php if ($val['voucher_base']) { ?>请选择平台红包<?php } else { ?>暂无可用的平台红包<?php } ?><?php } else { ?>暂无可用的平台红包<?php } ?>" data-is_discount="<?= $data['is_discount'] ?>">
                                    <?php if ($count_r > 0) { ?>
                                        <select id="redpacket" class="w400 <?php if ($data['is_discount'] < 1) { ?>js-example-templating <?php } ?>">
                                            <option></option>
                                            <?php if ($data['rpt_list']) { ?>
                                                <?php foreach ($data['rpt_list'] as $redkey => $redval) { ?>
                                                    <option value="<?= $redval['price'] ?>" red_id="<?= ($redval['id']) ?>" <?php if ($redval['id'] == $data['rpt_info'][0]['id']) { ?> selected="selected" <?php } ?> <?php if ($redval['isable'] == 0) { ?> disabled="disabled" <?php } ?> >满<?= ($redval['limit']) ?>减<?= ($redval['price']) ?> <time><?= (date('Y-m-d', strtotime($redval['end_date']))) ?><?= __('到期') ?></time>
                                                    </option>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php if ($count_r > 0) { ?>
                                                <option value="0" shop_id="<?= ($val['shop_id']) ?>"><?= __('不使用平台红包') ?></option>
                                            <?php } ?>
                                        </select>
                                    <?php } else { ?>
                                        <input type="text" value="暂无可用的平台红包" disabled="disabled" class="w390">
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- start 计算会员折扣率，显示 -->
                    <div class="invoice-cont">
                        <div class="inline activity-dt">会员折扣</div>
                        <div class="inline">
                            <?php if ($data['is_discount'] == 1) { ?>
                                <input type="checkbox" name="order_discount" id="is_discount" <?php if ($data['is_discount'] == 1) { ?> checked="checked" <?php } ?> >
                                <input class="bbc_color" name="order_discount_price" readonly value=" <?= round($data['user_rate'] / 10, 2); ?> 折，即：减￥<?= $data['order_discount']; ?>" order_discount_price="<?= $data['order_discount'] ?>" style="width: 200px;">
                            <?php } else { ?>
                                <?php if ($promotion > 0) { ?>
                                    <input name="order_discount" value="涉及活动商品不可使用会员折扣" readonly style="width:180px">
                                <?php } else { ?>
                                    <input type="checkbox" name="order_discount" id="is_discount">
                                    <span class="c-999" id="no_discount">开启会员折扣将不可用代金券及红包</span>
                                    <input name="order_discount_price" readonly>
                                <?php } ?>
                            <?php } ?>

                            <?php if(Web_ConfigModel::value('rate_service_status')){?>
                                    <span><?=__('仅限自营店铺享受会员折扣')?></span>
                            <?php }else{?>
                                    <span><?=__('全平台店铺享受会员折扣')?></span>
                            <?php }?>

                        </div>
                    </div>
                    <!-- end 计算会员折扣率，显示 -->
                    <div class="invoice-cont">
                        <span class="mt10">发票信息</span>
                        <input type="hidden" name="invoice_id" value="" id='order_invoice_id'>
                        <input type="hidden" name="invoice_content" value="" id='order_invoice_content'>
                        <input type="hidden" name="invoice_title" value="" id='order_invoice_title'>
                        <span class="mr10 invoice-no"> <?= __('不开发票') ?> </span><a class="invoice-edit"><?= __('修改') ?><a style="margin-left: 5px;display: none" class="invoice-cancel"><?= __('取消') ?></a>
                    </div>
                </div>

                <p class="back_cart"><a id="back_cart"><i class="iconfont icon-iconjiantouzuo rel_top2"></i><?= __('返回我的购物车') ?></a></p>
                <?php if (!Web_ConfigModel::value('rate_service_status') || (Web_ConfigModel::value('rate_service_status') && $val['shop_self_support'] == 'true')) {
                } else {
                    $user_rate = 100;
                } ?>
                <p class="submit" style="text-align: center;" rate="<?php echo $user_rate; ?>">
                    <span>
                        <?= __('订单金额：') ?>
                        <strong>
                            <?= (Web_ConfigModel::value('monetary_unit')) ?>
                            <i class="total" total_price="<?= (number_format($total, 2, '.', '')) ?>">
                                <?= (number_format($total, 2, '.', '')) ?>
                            </i>
                        </strong>
                    </span>
                    <span id="redpacket_price">
                        <?= __('平台红包：') ?>
                        <strong>
                            <?php if ($promotion > 0) { ?>
                                <i redpacket_price="0" class="isFavoritesShop">未使用</i>
                            <?php } else { ?>
                                <?php if ($data['rpt_info']) { ?>
                                    <i redpacket_price="<?= $data['rpt_info'][0]['price'] ?>">减￥<?= $data['rpt_info'][0]['price'] ?></i>
                                <?php } else { ?>
                                    <i redpacket_price="0" class="isFavoritesShop">未使用</i>
                                <?php } ?>
                            <?php } ?>
                        </strong>
                    </span>

                    <!-- 是否使用会员折扣 -->
                    <span>
                        <?= __('会员折扣：') ?>
                        <!--判断颜色isFavoritesShop-->
                        <strong id="order_discount">
                            <input type="hidden" name="o_discount" value="<?= number_format($data['order_discount'], 2, '.', '') ?>" rate_total="<?= number_format($data['order_discount'], 2, '.', '') ?>">
                            <?php if ($data['is_discount'] == 1) { ?>
                                <i rate_total="<?= number_format($data['order_discount'], 2, '.', '') ?>">减<?= number_format($data['order_discount'], 2, '.', '') ?> 元</i>
                            <?php } else { ?>
                                <i rate_total="<?= number_format($data['order_discount'], 2, '.', '') ?>" class="isFavoritesShop">未使用</i>
                            <?php } ?>
                        </strong>
                    </span>
                    <!-- 是否使用会员折扣 -->

                    <span>
                        <?php
                        $after_total = number_format($total - $promotion_money - $redpacket_price, 2, '.', '');
                        if ($after_total < 0) {
                            $after_total = 0;
                        }
                        ?>
                        <?= __('支付金额：') ?>
                        <strong class="common-color">
                            <?= (Web_ConfigModel::value('monetary_unit')) ?><i class="after_total bbc_color" after_total="<?= (number_format($after_total, 2, '.', '')) ?>"><?= (number_format($data['order_price'], 2, '.', '')) ?></i>
                        </strong>
                    </span>
                    <!--                    --><?php //echo $user_rate; ?>
                    <a id="pay_btn" class="bbc_btns"><?= __('提交订单') ?></a>
                </p>

            </div>
        </div>
    </div>
    </div>
    </div>
    <!-- 订单提交遮罩 -->
    <div id="mask_box" style="display:none;">
        <div class='loading-mask'></div>
        <div class="loading">
            <div class="loading-indicator">
                <img src="<?= $this->view->img ?>/large-loading.gif" width="32" height="32" style="margin-right:8px;vertical-align:top;" />
                <br /><span class="loading-msg"><?= __('正在提交订单，请稍后...') ?></span>
            </div>
        </div>
    </div>

    <script>
        var app_id = <?=(Yf_Registry::get('shop_app_id'))?>;
        var buy_able = <?=intval($buy_able) ? intval($buy_able) : 1?>;
        var user_rate = '<?=$user_rate?>';

        $(function () {
            $(".remarks_content").val("");
            $(".remarks_content").keyup(function () {
                var len = $(this).val().length;
                if (len > 45) {
                    $(this).val($(this).val().substring(0, 45));
                }
            });
            var best_total_voucher_price = <?=$total_voucher_price?>;//记录最优代金券总额
            var best_redpacket_price = <?=$redpacket_price?>;//记录最优红包金额
            if (best_redpacket_price == "") best_redpacket_price = 0;
            //会员折扣勾选
            var is_discount = getQueryString('is_discount');//是否开启会员折扣
            //锚点
            if (is_discount != '') {
                $("html,body").animate({scrollTop: $("#is_discount").offset().top}, 1);
            }
            if (is_discount == 1) {
                $("#redpacket").hide();
                $("#redpacket").removeClass("js-example-templating");
                $("#is_discount").attr('checked', true);
            }
            else {
                $("#is_discount").attr('checked', false);
            }

            var order_discount = $("input[name='o_discount']").val();//折扣金额
            var address_id = $(".add_choose").find('#address_id').val();//默认收货地址id
            if (!address_id) {
                address_id = 0;
            }
            var product_id = getQueryString('product_id');
            $("#is_discount").click(function () {
                //所有店铺代金券金额总和
                var total_voucher_price = 0;
                $(".select").find('option:selected').each(function () {
                    total_voucher_price += parseFloat($(this).val());
                })
                var order_redpacket_price = $("#redpacket_price").find('i').attr('redpacket_price');
                if (order_redpacket_price == undefined) order_redpacket_price = 0;
                if ($('#is_discount').is(':checked')) {
                    is_discount = 1;
                }
                else {
                    is_discount = 0;
                    $('#is_discount').attr('checked', false);
                }
                best_total_voucher_price = 0;
                best_redpacket_price = 0;
                location.href = SITE_URL + "?ctl=Buyer_Cart&met=confirm&product_id=" + product_id + "&address_id=" + address_id + "&is_discount=" + is_discount;
            });
            var voucher_price = 0;
            var total_price = 0;//店铺加价购商品总金额
            var a_total_price = 0;//店铺加价购商品总金额
            var old_order_price = parseFloat($('.submit').find('.total').attr('total_price'));//没选择加价购商品前的订单总金额
            var old_pay_price = parseFloat($('.submit').find('.after_total').attr('after_total'));//没选择加价购商品前的支付总金额

            //下拉列表选中平台红包
            $("#redpacket").change(function () {
                var before_redpacket = $("#redpacket_price").find('i').attr('redpacket_price'); // 记录红包金额
                var red_price = $(this).val();
                if (red_price > 0) {
                    $("#redpacket_price").find('i').html('减￥' + red_price);
                    $("#redpacket_price").find('i').removeClass('isFavoritesShop');
                }
                else {
                    $("#redpacket_price").find('i').html('未使用');
                    $("#redpacket_price").find('i').addClass('isFavoritesShop');
                }
                $("#redpacket_price").find('i').attr('redpacket_price', red_price);
                console.log(before_redpacket);
                $(".after_total").html((Number($(".after_total").html()) + Number(before_redpacket) - Number(red_price)).toFixed(2));
                before_redpacket = red_price;
            });

            //下拉列表选中优惠券金额
            $(".select").change(function () {
                var shop_id = $(this).find('option:selected').attr('shop_id');
                var best_v = $("input[name='best_voucher" + shop_id + "']").val();
                if (best_v) {
                    var best_order_voucher = parseFloat($("input[name='best_voucher" + shop_id + "']").val());//记录代金券金额
                }
                else {
                    var best_order_voucher = 0;
                }
                if ($(this).val() == 0) {
                    $(".shop_voucher" + shop_id).children("i").html($(this).val());
                    $(".shop_voucher" + shop_id).children("i").attr('shop_voucher', $(this).val());
                }
                else {
                    $(".shop_voucher" + shop_id).children("i").html("-" + $(this).val());
                    $(".shop_voucher" + shop_id).children("i").attr('shop_voucher', $(this).val());
                }

                var shop_total = parseFloat($(".sprice" + shop_id).html());//本店合计
                var goods_total = parseFloat($(".price" + shop_id).html());//商品金额
                var msprice = parseFloat($(".msprice" + shop_id).html());//物流运费
                var after_total = parseFloat($(".after_total").html()); //支付金额
                var total = parseFloat($(".total").html()); //订单金额

                //循环累加当前店铺选中的加价购商品
                $('.clearfix.bgf.' + shop_id).each(function () {
                    if ($(this).find('input:checkbox').is(':checked')) {
                        var goods_price = parseFloat($(this).find('.select_increase:checked').attr('goods_price'));
                        var now_num = parseInt($(this).find('.select_increase:checked').parents('tr:eq(0)').find('.increase_num').val());
                        total_shop_price += +(goods_price * now_num);
                    }
                });

                //选择代金券 本店合计 商品金额 支付金额 订单金额随着变化
                $(".price" + shop_id).html((goods_total + best_order_voucher - $(this).val()).toFixed(2));//商品金额
                $(".sprice" + shop_id).html((shop_total + best_order_voucher - $(this).val()).toFixed(2));//本店合计
                //订单金额
                $('.submit').find('.total').html((total + best_order_voucher - $(this).val()).toFixed(2));
                $('.submit').find('.total').attr('total_price', (total + best_order_voucher - $(this).val()).toFixed(2));
                //支付金额
                $(".after_total").html((after_total + best_order_voucher - $(this).val()).toFixed(2));
                $(".after_total").attr('after_total', (shop_total + best_order_voucher - $(this).val()).toFixed(2));

                //判断红包
                var order_price = Number(after_total + best_order_voucher - $(this).val());
                $.ajax({
                    type: "POST",
                    url: SITE_URL + '?ctl=Buyer_Cart&met=getUserRedpacket&typ=json',
                    data: {order_price: order_price},
                    success: function (result) {
                        var rpt_list = result.data.rpt_list;
                        var rpt_info = result.data.rpt_info[0];
                        if (rpt_info) {
                            $("input[name='redpacket']").val('请选择平台红包');
                        }
                        else {
                            $("input[name='redpacket']").val('暂无可用的平台红包');
                        }
                        var html = "<option></option>";
                        for (var v = 0; v < rpt_list.length; v++) {
                            html += "<option value='" + rpt_list[v].price + "' red_id='" + rpt_list[v].id + "'";
                            if (rpt_info && rpt_list[v].id == rpt_info.id) {
                                html += "selected='selected'";
                            }
                            if (rpt_list[v].isable == 0) {
                                html += "disabled='disabled'";
                            }
                            html += ">满" + rpt_list[v].limit + "减" + rpt_list[v].price + " <time>" + rpt_list[v].end_date.split(' ')[0] + "到期</time></option>"
                        }
                        html += "<option  value="
                        0
                        ">不使用平台红包</option>";
                        $("#redpacket").html(html);
                        if (rpt_info) {
                            var after_t = parseFloat($(".after_total").html());
                            var redpacket_p = Number($("#redpacket_price").find('i').attr("redpacket_price"));
                            $("#redpacket_price").find('i').html('减￥' + rpt_info.price);
                            $("#redpacket_price").find('i').attr("redpacket_price", rpt_info.price);
                            $(".after_total").html((after_t + redpacket_p - rpt_info.price).toFixed(2));
                            $("#redpacket_price").find('i').removeClass('isFavoritesShop');
                        }
                        else {
                            var after_t = parseFloat($(".after_total").html());
                            var redpacket_p = $("#redpacket_price").find('i').attr("redpacket_price");
                            $("#redpacket_price").find('i').html('未使用');
                            $(".after_total").html((after_t * 1 + redpacket_p * 1).toFixed(2));
                            $("#redpacket_price").find('i').addClass('isFavoritesShop');
                            $("#redpacket_price").find('i').attr("redpacket_price", 0);
                        }
                    }
                });
                $("input[name='best_voucher" + shop_id + "']").val($(this).val());
            });

            //-------

            //选择减少加价购商品数量
            $('.declick').on('click', function (e) {
                var num = parseInt($(this).next().val());
                var check_status = $(this).parents('tr:eq(0)').find('input:checkbox').is(':checked');
                var goods_price = parseFloat($(this).parents('tr:eq(0)').find('input:checkbox').attr('goods_price'));
                if (num > 1) {
                    $(this).next().val(num - 1);
                    //计算加价购商品的折扣
                    var jiagoods_price = parseFloat(((num + 1) * goods_price)).toFixed(2);//+1后的加价购商品的价格
                    var jiagoods_discount = (jiagoods_price * (1 - user_rate / 100)).toFixed(2);//计算加价购折扣
                    var oldgoods_price = parseFloat((num * goods_price)).toFixed(2);//没有+1前的加价购商品的价格
                    var oldgoods_discount = (oldgoods_price * (1 - user_rate / 100)).toFixed(2);//计算加价购折扣
                    //计算加价购折扣
                    var jia_discount = (jiagoods_discount - oldgoods_discount).toFixed(2);
                    $(this).parents('tr:eq(0)').find('.jia-shop .fl em').html("<?=(Web_ConfigModel::value('monetary_unit'))?>" + jiagoods_price);
                    if (check_status) {
                        //当选中的时候点击减少加价购商品，当前店铺总金额和结算总金额都要随着变化
                        var shop_id = $(this).parents('tr:eq(0)').find('input:checkbox').attr('shop_id');
                        var shop_price = parseFloat($('.sprice' + shop_id).html());//本店合计
                        var goods_total_price = parseFloat($('.price' + shop_id).html());//商品金额

                        $('.price' + shop_id).html((goods_total_price - goods_price * 1).toFixed(2));
                        $('.sprice' + shop_id).html((shop_price - goods_price * 1).toFixed(2));

                        //会员折扣
                        var order_discount_price = 0;
                        var discount_price = 0;
                        if (is_discount == 1) {
                            order_discount_price = $("input[name='order_discount_price']").attr("order_discount_price");
                            discount_price = (order_discount_price * 1 - jia_discount * 1).toFixed(2);//折扣价格
                            $("input[name='order_discount_price']").val('减￥' + discount_price);
                            $("input[name='order_discount_price']").attr('order_discount_price', discount_price);
                            $("#order_discount").find('i').html('减￥' + discount_price);
                            $("#order_discount").find('i').attr('rate_total', discount_price)
                        } else {
                            jia_discount = 0;
                        }
                        var order_total_price = parseFloat($('.submit').find('.total').html());
                        var order_after_total_price = parseFloat($('.submit').find('.after_total').html());
                        $('.submit').find('.total').html((order_total_price - goods_price * 1).toFixed(2));
                        var afterTotal = (order_after_total_price - goods_price * 1 + jia_discount * 1).toFixed(2);
                        $('.submit').find('.after_total').html(afterTotal);

                        total_price = 0;
                        voucher_price = 0;
                        a_total_price = 0;
                        total_shop_price = 0;
                        shop_rate = 0;
                    }
                }
            })

            //选择增加加价购商品数量
            $('.inclick').on('click', function (e) {
                var num = parseInt($(this).prev().val());
                var num_max = parseInt($(this).prev().attr('data-max'));//最多购买数
                //点击增加按钮时判断当前加价购商品有没有被选中
                var check_status = $(this).parents('tr:eq(0)').find('input:checkbox').is(':checked');
                var goods_price = parseFloat($(this).parents('tr:eq(0)').find('input:checkbox').attr('goods_price'));

                if (num < num_max) {
                    $(this).prev().val(num + 1);
                    //计算加价购商品的折扣
                    var jiagoods_price = parseFloat(((num + 1) * goods_price)).toFixed(2);//+1后的加价购商品的价格
                    var jiagoods_discount = (jiagoods_price * (1 - user_rate / 100)).toFixed(2);//计算加价购折扣
                    var oldgoods_price = parseFloat((num * goods_price)).toFixed(2);//没有+1前的加价购商品的价格
                    var oldgoods_discount = (oldgoods_price * (1 - user_rate / 100)).toFixed(2);//计算加价购折扣
                    //计算加价购折扣
                    var jia_discount = (jiagoods_discount - oldgoods_discount).toFixed(2);

                    $(this).parents('tr:eq(0)').find('.jia-shop .fl em').html("<?=(Web_ConfigModel::value('monetary_unit'))?>" + jiagoods_price);
                    if (check_status) {
                        //当选中的时候点击增加加价购商品，当前店铺总金额和结算总金额都要随着变化
                        var shop_id = $(this).parents('tr:eq(0)').find('input:checkbox').attr('shop_id');
                        var shop_price = parseFloat($('.sprice' + shop_id).html());//本店合计
                        var goods_total_price = parseFloat($('.price' + shop_id).html());//商品金额
                        $('.price' + shop_id).html((goods_total_price + goods_price * 1).toFixed(2));
                        $('.sprice' + shop_id).html((shop_price + goods_price * 1).toFixed(2));
                        //会员折扣
                        var order_discount_price = 0;
                        var discount_price = 0;
                        if (is_discount == 1) {
                            order_discount_price = $("input[name='order_discount_price']").attr("order_discount_price");
                            discount_price = (order_discount_price * 1 + jia_discount * 1).toFixed(2);//折扣价格
                            $("input[name='order_discount_price']").val('减￥' + discount_price);
                            $("input[name='order_discount_price']").attr('order_discount_price', discount_price);
                            $("#order_discount").find('i').html('减￥' + discount_price);
                            $("#order_discount").find('i').attr('rate_total', discount_price)
                        } else {
                            jia_discount = 0;
                        }
                        var order_total_price = parseFloat($('.submit').find('.total').html());
                        var order_after_total_price = parseFloat($('.submit').find('.after_total').html());
                        $('.submit').find('.total').html((order_total_price + goods_price * 1).toFixed(2));
                        var afterTotal = (order_after_total_price + goods_price * 1 - jia_discount * 1).toFixed(2);
                        if (afterTotal < 0.01) {
                            afterTotal = 0.01;
                        }
                        $('.submit').find('.after_total').html(afterTotal);

                        total_shop_price = 0;
                        order_total_price = 0;
                        order_after_total_price = 0;
                        this_goods_price = 0;
                        this_goods_num = 0;
                        this_total_price = 0;
                    }
                }
            })

            //当输入框获取焦点时，获取当前的商品数量
            $('.increase_num').on('focus', function () {
                old_goods_num = parseInt($(this).val());
            })

            //判断加价购输入框手动输入的内容
            $('.increase_num').on('keyup', function () {
                //最大限购数量^[1-9]\\d*$
                var num_max = parseInt($(this).attr('data-max'));
                var check_status = $(this).parents('tr:eq(0)').find('input:checkbox').is(':checked');

                if (!/^[1-9]\d*$/.test(this.value) || $(this).val() < 1) {
                    $(this).val(1);
                    $(this).blur();
                    //判断当前加价购商品是否被选中，如果选中将总计价格做相应修改
                    if (check_status) {
                        if (old_goods_num > 1) {
                            increase_rate_total(this);
                        }
                    }
                }
                else if ($(this).val() > num_max) {
                    $(this).val(num_max);
                    $(this).blur();
                    if (check_status) {
                        increase_rate_total(this);
                    }
                }
                else if ($(this).val() <= num_max) {
                    var now_num = $(this).val();
                    $(this).val(now_num);
                    $(this).blur();
                    if (check_status) {
                        increase_rate_total(this);
                    }
                }
            })

            var total_shop_price = 0;//店铺加价购商品总金额

            //点击选中一个加价购商品
            $('.select_increase').on('click', function () {
                var shop_id = $(this).attr('shop_id');
                var goods_total_price = parseFloat($('.price' + shop_id).html());//商品金额
                var shop_price = parseFloat($('.sprice' + shop_id).html());//本店合计

                //当前加价购商品的价格
                var this_goods_price = parseFloat($(this).attr('goods_price'));
                var this_goods_num = parseInt($(this).parents('tr:eq(0)').find('.increase_num').val());
                var this_total_price = +(this_goods_price * this_goods_num);
                //计算加价购折扣
                var jia_discount = (this_total_price * (1 - user_rate / 100)).toFixed(2);
                if ($(this).is(':checked')) {
                    $(this).attr('checked', true);
                    $(this).parents('.clearfix.bgf.' + shop_id).addClass('status');
                } else {
                    $(this).attr('checked', false);
                    $(this).parents('.clearfix.bgf.' + shop_id).removeClass('status');
                    //当某个店铺取消选择加价购商品时，将当前店铺的商品总价恢复，总订单金额改变
                    //循环累加当前店铺选中的加价购商品
                    // after_total_price = -after_total_price;
                    this_total_price = -this_total_price;
                    jia_discount = -jia_discount;
                }

                $('.price' + shop_id).html((goods_total_price + this_total_price * 1).toFixed(2));
                $('.sprice' + shop_id).html((shop_price + this_total_price * 1).toFixed(2));

                //会员折扣
                var order_discount_price = 0;
                var discount_price = 0;
                if (is_discount == 1) {
                    order_discount_price = $("input[name='order_discount_price']").attr("order_discount_price");
                    discount_price = (order_discount_price * 1 + jia_discount * 1).toFixed(2);//折扣价格
                    $("input[name='order_discount_price']").val('减￥' + discount_price);
                    $("input[name='order_discount_price']").attr('order_discount_price', discount_price);
                    $("#order_discount").find('i').html('减￥' + discount_price);
                    $("#order_discount").find('i').attr('rate_total', discount_price)
                } else {
                    jia_discount = 0;
                }
                var order_total_price = parseFloat($('.submit').find('.total').html());
                var order_after_total_price = parseFloat($('.submit').find('.after_total').html());
                $('.submit').find('.total').html((order_total_price * 1 + this_total_price * 1).toFixed(2));
                var afterTotal = (order_after_total_price * 1 + this_total_price * 1 - jia_discount * 1).toFixed(2);
                $('.submit').find('.after_total').html(afterTotal);

                this_goods_price = 0;
                this_goods_num = 0;
                this_total_price = 0;
                order_total_price = 0;
                order_after_total_price = 0;
            })

            //将加价购和商品折扣封装成一个函数
            function increase_rate_total(obj) {
                var now_goods_num = parseFloat($(obj).val());
                var goods_price = parseFloat($(obj).attr('goods_price'));
                var now_total_goods_price = parseFloat(now_goods_num * goods_price);
                var old_total_goods_price = parseFloat(old_goods_num * goods_price);

                //当选中的时候点击增加加价购商品，当前店铺        总金额和结算总金额都要随着变化
                var shop_id = $(obj).parents('tr:eq(0)').find('input:checkbox').attr('shop_id');
                var goods_total_price = parseFloat($('.price' + shop_id).html());//商品金额
                var shop_price = parseFloat($('.sprice' + shop_id).html());//本店合计
                //循环累加当前店铺选中的加价购商品
                $('.clearfix.bgf.' + shop_id).each(function () {
                    if ($(this).find('input:checkbox').is(':checked')) {
                        var goods_price = parseFloat($(this).find('.select_increase:checked').attr('goods_price'));
                        var now_num = parseInt($(this).find('.select_increase:checked').parents('tr:eq(0)').find('.increase_num').val());
                        total_shop_price += +(goods_price * now_num);
                    }
                })


                //当前店铺总价和折扣显示
                $('.price' + shop_id).html((goods_total_price - old_total_goods_price + now_total_goods_price).toFixed(2));
                $('.sprice' + shop_id).html((shop_price - old_total_goods_price + now_total_goods_price).toFixed(2));

                var order_total_price = parseFloat($('.submit').find('.total').html());
                var order_after_total_price = parseFloat($('.submit').find('.after_total').html());
                $('.submit').find('.total').html((order_total_price - old_total_goods_price + now_total_goods_price).toFixed(2));
                $('.submit').find('.after_total').html((order_after_total_price - old_total_goods_price + now_total_goods_price).toFixed(2));
                total_shop_price = 0;
                shop_rate = 0;
            }
        });
    </script>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>

<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
//搜索框关键字
$search_array = array();
if (is_array($this->searchWord)) {
    foreach ($this->searchWord as $key => $val) {
        $search_array[] = $val['search_keyword'];
    }
}
$search_words = array_map(function ($v) {
    return "<a href=\"javascript:;\" class=\"cheap\">{$v}</a>";
//    return sprintf('<a href="%s?ctl=Goods_Goods&met=goodslist&typ=e&keywords=%s" class="cheap">%s</a>', Yf_Registry::get('url'), urlencode($v), $v);
}, $search_array);
$keywords = Web_ConfigModel::value('search_words');
?>
<!--head_star-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit|ie-stand|ie-comp">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1"/>
    <meta name="description" content="<?php if ($this->description) { ?><?= $this->description ?><?php } ?>"/>
    <meta name="Keywords" content="<?php if ($this->keyword) { ?><?= $this->keyword ?><?php } ?>"/>
    <title><?php if ($this->title) { ?><?= addslashes($this->title) ?><?php } else { ?><?= addslashes(Web_ConfigModel::value('site_name')) ?><?php } ?></title>

    <link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/headfoot.css"/>
    <link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/sidebar.css"/>
    <link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/index.css"/>
    <link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/nav.css"/>
    <link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/base.css"/>
    <link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/swiper.css"/>
    <link rel="stylesheet" href="<?= $this->view->css ?>/iconfont/iconfont.css">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/jquery.js"></script> <!--jquery-->
    <script type="text/javascript">
        var IM_URL = "<?=Yf_Registry::get('im_api_url')?>";
        var IM_STATU = "<?=Yf_Registry::get('im_statu')?>";
        var BASE_URL = "<?=Yf_Registry::get('base_url')?>";
        var SITE_URL = "<?=Yf_Registry::get('url')?>";
        var INDEX_PAGE = "<?=Yf_Registry::get('index_page')?>";
        var STATIC_URL = "<?=Yf_Registry::get('static_url')?>";
        var PAYCENTER_URL = "<?=Yf_Registry::get('paycenter_api_url')?>";
        var UCENTER_URL = "<?=Yf_Registry::get('ucenter_api_url')?>";
        var is_open_city = "<?= Web_ConfigModel::value('subsite_is_open');?>";
        var DOMAIN = document.domain;
        var WDURL = "";
        var SCHEME = "default";
        var MASTER_SITE_URL = "<?=Yf_Registry::get('shop_api_url')?>";
    </script>
    <link rel="stylesheet" type="text/css" href="<?= $this->view->css_com ?>/iealert/style.css"/> <!--ie兼容-->
    <script type="text/javascript" src="<?= $this->view->js_com ?>/iealert.js"></script> <!--ie兼容-->
    <script type="text/javascript" src="<?= $this->view->js ?>/jquery.lazy.js"></script> <!--图片延时加载-->
    <link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/goods-detail.css"/>
    <script type="text/javascript" src="<?= $this->view->js ?>/tuangou-index.js"></script>
    <script src="<?= $this->view->js ?>/preview_goods_detail.js"></script> <!--商品详情预览-->
    <script src="<?= $this->view->js_com ?>/plugins/jquery.imagezoom.min.js"></script> <!--图片放大-->
    <script type="text/javascript" src="<?= $this->view->js ?>/common.js"></script>
    <style>
        div.zoomDiv {
            position: absolute;
            top: 0px;
            left: 0px;
            width: 200px;
            height: 200px;
            background: #ffffff;
            border: 1px solid #CCCCCC;
            display: none;
            text-align: center;
            overflow: hidden;
            z-index: 999;
        }

        div.zoomMask {
            position: absolute;
            background: url("<?=$this->view->img?>/mask.png") repeat scroll 0 0 transparent;
            cursor: move;
            z-index: 1;
        }

        .tuan_go_gray, tuan_join_cart, .tuan_join_cart_gray, .tuan_go_gray {
            display: inline-block;
            padding: 0 30px;
            text-align: center;
            font-size: 18px;
            border-radius: 2px;
        }

        .tuan_go {
            border: 2px solid #d73834;
            height: 36px;
            line-height: 36px;
        }

        .tuan_join_cart_gray, .tuan_go_gray {
            line-height: 40px;
            height: 40px;
        }

        .tuan_go_gray {
            border: 2px solid #d73834;
            border-color: #ccc !important;
            color: #ccc;
        }

        .tuan_join_cart_gray {
            background: #ccc;
            margin-right: 8px;
            margin-left: 72px;
            line-height: 40px;
            height: 40px;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="head_cont">
        <div style="clear:both;"></div>
        <div class="nav_left">
            <a href="javascript:;" class="logo"><img
                        src="<?php if (Web_ConfigModel::value('subsite_is_open') && isset($_COOKIE['sub_site_logo']) && $_COOKIE['sub_site_logo'] != '' && isset($_COOKIE['sub_site_id']) && $_COOKIE['sub_site_id'] > 0) {
                            echo $_COOKIE['sub_site_logo'];
                        } else {
                            if (@$this->web['web_logo']) {
                                echo @$this->web['web_logo'];
                            } else {
                                echo $this->view->img . '/setting_logo.jpg';
                            }
                        } ?>"/></a>
            <a href="#" class="download iconfont"></a>
        </div>
        <div class="nav_right clearfix">
            <ul class="clearfix search-types">
                <li class="<?php if (@request_string('ctl') != 'Shop_Index') echo 'active'; ?>"><a
                            href="javascript:void(0);" data-param='goods'><?= __('宝贝') ?></a></li>
                <li class="<?php if (@request_string('ctl') == 'Shop_Index') echo 'active'; ?>"><a
                            href="javascript:void(0);" data-param='shop'><?= __('店铺') ?></a></li>
            </ul>
            <div class="clearfix">
                <form name="form_search" id="form_search" action="" class="">
                    <input type="hidden" id="search_ctl" name="ctl"
                           value="<?php if (@request_string('ctl') != 'Shop_Index') echo 'Goods_Goods'; else echo 'Shop_Index'; ?>">
                    <input type="hidden" id="search_met" name="met"
                           value="<?php if (@request_string('ctl') != 'Shop_Index') echo 'goodslist'; else echo 'index'; ?>">
                    <input type="hidden" name="typ" value="e">
                    <input name="keywords" id="site_keywords" type="text" value="<?= request_string('keywords') ?>">
                    <input type="submit" style="display: none;">
                    <?php if ($now_page == 'shop_page') { ?>
                        <label for="site_keywords" style="display: none;"></label>
                    <?php } else { ?>
                        <label for="site_keywords"><?= $keywords ?></label>
                    <?php } ?>

                </form>
                <a href="javascript:;" class="ser"><?= __('搜索') ?></a>
                <!-- 购物车 -->

                <div class="bbuyer_cart" id="J_settle_up">
                    <div id="J_cart_head">
                        <a href="javascript:;" target="_blank" class="bbc_buyer_icon bbc_buyer_icon2">
                            <i class="ci_left iconfont icon-zaiqigoumai bbc_color rel_top2"></i>
                            <span><?= __('我的购物车') ?></span> <i class="ci_right iconfont icon-iconjiantouyou"></i>
                            <i class="ci-count bbc_bg" id="cart_num">0</i> </a>
                    </div>
                    <!--                    <div class="dorpdown-layer" id="J_cart_body"><span class="loading"></span></div>-->
                </div>
            </div>
            <div class="nav clearfix searchs">
                <?= implode($search_words) ?>
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>
    <div>
        <div class="thead clearfix">
            <div class="classic clearfix">
                <div class="class_title"><span>&equiv;</span><a href="javascript:;" class="ta1"><?= __('全部分类') ?></a>
                </div>
            </div>
            <p class="high_gou"></p>
        </div>
    </div>
</div>
<div class="hr" style="background:#c51e1e;">
</div>
<div class="J-global-toolbar">
</div>
<!--head_end-->
<div class="bgcolor">
    <div class="wrapper">
        <div class="t_goods_detail">
            <div class="crumbs clearfix">
                <p>
                    <?php if ($parent_cat) { ?>
                        <?php foreach ($parent_cat as $catkey => $catval): ?>
                            <a href="javascipt:;"><?= ($catval['cat_name']) ?></a><?php if (!isset($catval['ext'])) { ?>
                                <i class="iconfont icon-iconjiantouyou"></i><?php } ?>
                        <?php endforeach; ?>
                    <?php } ?>
                </p>
            </div>

            <div class="t_goods_ev clearfix">
                <div class="ev_left">
                    <div class="ev_left_img">
                        <?php if (isset($goods_detail['goods_base']['image_row'][0]['images_image'])) {
                            $goods_image = $goods_detail['goods_base']['image_row'][0]['images_image'];
                        } else {
                            $goods_image = $goods_detail['goods_base']['goods_image'];
                        } ?>

                        <img class="jqzoom lazy" width=366 rel="<?= image_thumb($goods_image, 900, 976) ?>"
                             data-original="<?= image_thumb($goods_image, 366, 340) ?>"/>
                    </div>
                    <div class="retw">
                        <div class="gdt_ul">
                            <ul class="clearfix" id="jqzoom">
                                <?php if (isset($goods_detail['goods_base']['image_row']) && $goods_detail['goods_base']['image_row']) {
                                    foreach ($goods_detail['goods_base']['image_row'] as $imk => $imv) { ?>
                                        <li <?php if ($imv['images_is_default'] == 1){ ?>class="check"<?php } ?>>
                                            <img class='lazy' width=60 height=60
                                                 data-original="<?= image_thumb($imv['images_image'], 60, 60) ?>"/>
                                            <input type="hidden"
                                                   value="<?= image_thumb($imv['images_image'], 366, 340) ?>"
                                                   rel="<?= image_thumb($imv['images_image'], 900, 976) ?>">
                                        </li>
                                    <?php }
                                } else { ?>
                                    <li class="check">
                                        <img class='lazy' width=60 height=60
                                             data-original="<?= image_thumb($goods_image, 60, 60) ?>"/>
                                        <input type="hidden" value="<?= image_thumb($goods_image, 366, 340) ?>"
                                               rel="<?= image_thumb($goods_image, 900, 976) ?>">
                                    </li>
                                <?php } ?>
                                <?php if (!empty($goods_detail['recImages'])) {
                                    foreach ($goods_detail['recImages'] as $k => $v) {
                                        ?>
                                        <li>
                                            <img class='lazy' width=60 height=60
                                                 data-original="<?= image_thumb($v, 60, 60) ?>"/>
                                            <input type="hidden" value="<?= image_thumb($v, 366, 340) ?>"
                                                   rel="<?= image_thumb($v, 900, 976) ?>">
                                        </li>
                                    <?php }
                                } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="ev_left_num">
                            <span class="number_imp word-w one-overflow"><?= __('商品编号：') ?>

                                <?php if ($goods_detail['common_base']['common_code']) { ?>
                                    <?= ($goods_detail['common_base']['common_code']) ?><?php } else { ?>
                                    <?= __("无") ?>
                                <?php } ?>
                            </span>
                        <span class="others_imp">
                                <b class="top iconfont icon-icoshare icon-1 bbc_color"></b><em
                                    class="top"><?= __('分享') ?></em>
                            </span>
                        <span>
                                <b class="top iconfont icon-2 bbc_color <?php if ($isFavoritesGoods) { ?> icon-taoxinshi<?php } else { ?>  icon-icoheart <?php } ?>"></b><em
                                    class="top"><?= __('收藏') ?></em>
                            </span>


                        <span class="cprodict ">
                                <a href="javascript:;">
                                    <b class="top iconfont icon-jubao icon-1 bbc_color"><em
                                                class="top"></b><?= __('举报') ?></em>
                                </a>
                            </span>
                    </div>
                    <div class="bdsharebuttonbox icon-medium hidden" style="clear:both;padding:10px 20px 0 20px;">
                        <a href="#" class="bds_qzone" data-cmd="qzone"></a>
                        <a href="#" class="bds_tsina" data-cmd="tsina"></a>
                        <a href="#" class="bds_tqq" data-cmd="tqq"></a>
                        <a href="#" class="bds_renren" data-cmd="renren"></a>
                        <a href="#" class="bds_weixin" data-cmd="weixin"></a>
                        <a href="#" class="bds_more" data-cmd="more"></a>
                    </div>

                </div>
                <div class="ev_center">
                    <div class="ev_head">

                        <h3><?= ($goods_detail['goods_base']['goods_name']) ?></h3>
                    </div>
                    <div class="small_title">
                        <?php if ($goods_detail['common_base']['common_is_virtual']): ?>
                            <p class="bbc_color"><?= __('虚拟商品') ?></p>
                        <?php endif; ?>
                        <p class="bbc_color"><?= ($goods_detail['goods_base']['goods_promotion_tips']) ?></p>

                    </div>

                    <div class="obvious">
                        <p class="clearfix">
                            <span class="mar-r _letter-spacing"><?= __('市场价：') ?></span>
                            <span class="mar-b-1"><del><?= format_money($goods_detail['common_base']['common_market_price']) ?></del></span>
                        </p>
                        <p class="clearfix">
                            <span class="mar-r _letter-spacing"><?= __('商城价：') ?></span>
                            <span class="mar-b-2">
                                    <?php if (isset($goods_detail['goods_base']['promotion_price']) && !empty($goods_detail['goods_base']['promotion_price'])
                                    ) : ?>
                                        <strong class="color-db0a07 bbc_color"><?= format_money($goods_detail['goods_base']['promotion_price']) ?></strong>
                                        <span><?= __('（原售价：') ?><?= format_money($goods_detail['goods_base']['goods_price']) ?><?= __('）') ?></span>
                                        <input type="hidden" name="goods_price"
                                               value="<?= $goods_detail['goods_base']['promotion_price'] ?>"
                                               id="goods_price"/>
                                    <?php else: ?>
                                        <input type="hidden" name="goods_price"
                                               value="<?= $goods_detail['goods_base']['goods_price'] ?>"
                                               id="goods_price"/>
                                        <strong class="color-db0a07 bbc_color"><?= format_money($goods_detail['goods_base']['goods_price']) ?></strong>

                                    <?php endif; ?>
                                </span>
                        </p>
                        <p class="clearfix">
                            <span class="mar-r _letter-spacing-2"><?= __('商品评分：') ?></span>
                            <span class="mar-b-3">
                                <?php for ($i = 1; $i <= $goods_detail['goods_base']['goods_evaluation_good_star']; $i++) { ?>
                                    <em></em><?php } ?>
                                </span>
                        </p>
                        <p class="clearfix"><span class="mar-r _letter-spacing-2"><?= ('商品评价：') ?></span>
                            <span class="color-1876d1 mar-b-3 "><a href="javascript:;" name="elist" class="pl"><i
                                            class="num_style"><?= ($goods_detail['common_base']['common_evaluate']) ?></i> <?= __('条评论') ?></a></span>
                        </p>
                        <p class="clearfix"><span
                                    class="mar-r _letter-spacing-2"><?= ('销&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;量：') ?></span>
                            <span class="color-1876d1 mar-b-5 "><a href="javascript:;" name="elist" class="pl"><i
                                            class="num_style"><?= ($goods_detail['common_base']['common_salenum']) ?></i> <?= __('件') ?></a></span>
                        </p>
                        <div>
                            <img class='lazy'
                                 data-original="<?= Yf_Registry::get('base_url') ?>/shop/api/qrcode.php?data=<?= urlencode(Yf_Registry::get('shop_wap_url') . "/tmpl/product_detail.html?goods_id=" . $goods_detail['goods_base']['goods_id']) ?>"
                                 width="80" height="80"/>
                            <span><?= __('扫描二维码') ?></span><span><?= __('手机上购物') ?></span>
                        </div>
                    </div>
                    <?php if (isset($goods_detail['goods_base']['promotion_type']) && $goods_detail['goods_base']['promotion_type']) {
                        $now_time = time();
                        $start_time = strtotime($goods_detail['goods_base']['groupbuy_starttime']);
                        $end_time = strtotime($goods_detail['goods_base']['groupbuy_endtime']);
                        if ($start_time > $now_time) {
                            $time_tips = __('距开始');
                            $diff_time = $start_time - $now_time;
                        }
                        if ($end_time > $now_time && $start_time < $now_time) {
                            $time_tips = __('距结束');
                            $diff_time = $end_time - $now_time;
                        }

                        ?>
                        <div class="count-down">
                            <i class="iconfont icon-julishijian"></i>
                            <dl>
                                <dt><?= $time_tips ?>：</dt>
                                <dd>
                                    <span id="day_show"></span><?= __('天') ?>
                                    <span id="hour_show"></span><?= __('时') ?>
                                    <span id="minute_show"></span><?= __('分') ?>
                                    <span id="second_show"></span><?= __('秒') ?>
                                </dd>
                            </dl>
                            <?php
                            if ($goods_detail['goods_base']['promotion_type'] === 'groupbuy' && $goods_detail['goods_base']['groupbuy_virtual_quantity'] > 0) { ?>
                                <div class="fr"><?= $goods_detail['goods_base']['groupbuy_virtual_quantity'] ?><?= __('件已团购') ?></div>
                            <?php } ?>
                        </div>
                    <?php } ?>

                    <div class="goods_style_sel ">
                        <div>
                            <input type="hidden" id="common_id"
                                   value="<?= ($goods_detail['goods_base']['common_id']) ?>"/>

                            <?php if (isset($goods_detail['goods_base']['promotion_type']) || $goods_detail['goods_base']['have_gift'] == 'gift' || !empty($goods_detail['goods_base']['increase_info']) || !empty($goods_detail['mansong_info'])) { ?>
                                <?php if (isset($goods_detail['goods_base']['promotion_type']) || !empty($goods_detail['mansong_info']) || !empty($goods_detail['goods_base']['increase_info'])) { ?>
                                    <span class="span_w lineh-1 mar_l "><?= __('促&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;销：') ?></span>

                                    <div class="activity_reset">
                                        <?php if (isset($goods_detail['goods_base']['title']) && $goods_detail['goods_base']['title'] != '') { ?>
                                            <span><i class="iconfont icon-huanyipi"></i><?= ($goods_detail['goods_base']['title']) ?></span>

                                            <!--S 限时折扣 -->
                                            <?php if ($goods_detail['goods_base']['promotion_type'] == 'xianshi') { ?>
                                                <i class="group_purchase "><?= __('限时折扣：') ?></i>
                                                <strong><?= __('直降') ?></strong><?= ($goods_detail['goods_base']['down_price']) ?>
                                                <?php if ($goods_detail['goods_base']['lower_limit']) { ?>
                                                    <?php echo sprintf('最低购%s件，', $goods_detail['goods_base']['lower_limit']); ?><?php echo $goods_detail['goods_base']['explain']; ?>
                                                <?php }
                                            } ?>
                                            <!--E 限时折扣 -->

                                            <!--S 团购 -->
                                            <?php if ($goods_detail['goods_base']['promotion_type'] == 'groupbuy') { ?>
                                                <?php if ($goods_detail['goods_base']['upper_limit']) { ?>
                                                    <i class="group_purchase "><?= __('团购：') ?></i>
                                                    <em><?php echo sprintf('最多限购%s件', $goods_detail['goods_base']['upper_limit']); ?></em>
                                                <?php } ?>
                                                <span><?php echo $goods_detail['goods_base']['remark']; ?></span>
                                            <?php } ?>
                                            <!--E 团购 -->
                                        <?php } ?>

                                        <!--S 加价购 -->
                                        <?php if ($goods_detail['goods_base']['increase_info']) { ?>
                                            <div class="ncs-mansong">
                                                <i class="group_purchase "><?= __('加价购：') ?></i>
                                                <span class="sale-rule">
                                                  <em><?= ($goods_detail['goods_base']['increase_info']['increase_name']) ?></em>

                                                    <?php if (!empty($goods_detail['goods_base']['increase_info']['rule'])) { ?>
                                                        <?= __('购物满') ?>
                                                        <em><?= format_money($goods_detail['goods_base']['increase_info']['rule'][0]['rule_price']) ?></em><?= __('即可加价换购最多') ?><?php if ($goods_detail['goods_base']['increase_info']['rule'][0]['rule_goods_limit']): ?><?= ($goods_detail['goods_base']['increase_info']['rule'][0]['rule_goods_limit']) ?><?= __('样') ?><?php endif; ?><?= __('商品') ?>
                                                    <?php } ?>

                                                    <span class="sale-rule-more" nctype="show-rule">
                                                    <a href="javascript:void(0);">
                                                        <?= __('详情') ?><i class="iconfont icon-iconjiantouxia"></i>
                                                    </a>
                                                  </span>

                                                    <?php if (!empty($goods_detail['goods_base']['increase_info']['goods'])) { ?>
                                                        <div class="sale-rule-content" style="display: none;"
                                                             nctype="rule-content">
                                                            <div class="title"><span class="sale-name">
                                                            <?= ($goods_detail['goods_base']['increase_info']['increase_name']) ?></span><?= __('，共') ?>
                                                                <strong><?php echo count($goods_detail['goods_base']['increase_info']['rule']); ?></strong>
                                                                <?= __('种活动规则') ?><a href="javascript:;"
                                                                                     nctype="hide-rule"><?= __('关闭') ?></a>
                                                            </div>

                                                            <?php foreach ($goods_detail['goods_base']['increase_info']['rule'] as $rule) { ?>
                                                                <div class="content clearfix">
                                                                    <div class="mjs-tit">
                                                                        <?= __('购物满') ?>
                                                                        <em><?= format_money($rule['rule_price']) ?></em><?= __('即可加价换购更多') ?><?php if ($rule['rule_goods_limit']): ?><?= ($rule['rule_goods_limit']) ?><?= __('样') ?><?php endif; ?><?= __('商品') ?>
                                                                    </div>
                                                                    <ul class="mjs-info clearfix">
                                                                        <?php foreach ($rule['redemption_goods'] as $goods) { ?>
                                                                            <li>
                                                                                <a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= ($goods['goods_id']) ?>"
                                                                                   title="<?= ($goods['goods_name']) ?>"
                                                                                   target="_blank" class="gift"> <img
                                                                                            src="<?= image_thumb($goods['goods_image'], 80, 80) ?>"
                                                                                            alt="<?= ($goods['goods_name']) ?>"> </a>&nbsp;
                                                                            </li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } ?>
                                            </div>
                                        <?php } ?>
                                        <!--E 加价购 -->

                                        <!--S 满即送 -->
                                        <?php if ($goods_detail['mansong_info'] && $goods_detail['mansong_info']['rule']) { ?>
                                            <div class="ncs-mansong">
                                                <i class="group_purchase "><?= __('满即送：') ?></i>
                                                <span class="sale-rule">
                                              <?php $rule = $goods_detail['mansong_info']['rule'][0]; ?>
                                                    <?= __('购物满') ?>
                                                    <em><?= format_money($rule['rule_price']) ?></em>
                                                    <?php if (!empty($rule['rule_discount'])) { ?>
                                                        <?= __('，即享') ?>
                                                        <em><?= ($rule['rule_discount']) ?></em><?= __('元优惠') ?>
                                                    <?php } ?>
                                                    <?php if (!empty($rule['goods_id'])) { ?>
                                                        <?= __('，送') ?><a
                                                        href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= ($rule['goods_id']) ?>"
                                                        title="<?= ($rule['goods_name']) ?>"
                                                        target="_blank"><?= __('赠品') ?></a>
                                                    <?php } ?>
                                              </span> <span class="sale-rule-more" nctype="show-rule"><a
                                                            href="javascript:void(0);"><?= __('共') ?>
                                                        <strong><?php echo count($goods_detail['mansong_info']['rule']); ?></strong><?= __('项，展开') ?>
                                                        <i class="iconfont icon-iconjiantouxia"></i></a></span>
                                                <div class="sale-rule-content" style="display: none;"
                                                     nctype="rule-content">
                                                    <div class="title"><span
                                                                class="sale-name"><?= __('满即送') ?></span><?= __('共') ?>
                                                        <strong><?php echo count($goods_detail['mansong_info']['rule']); ?></strong><?= __('项，促销活动规则') ?>
                                                        <a href="javascript:;"
                                                           nctype="hide-rule"><?= __('关闭') ?></a></div>
                                                    <div class="content clearfix">
                                                        <div class="mjs-tit"><?= ($goods_detail['mansong_info']['mansong_name']) ?>
                                                            <time>
                                                                (<?= ($goods_detail['mansong_info']['mansong_start_time']) ?>
                                                                -- <?= ($goods_detail['mansong_info']['mansong_end_time']) ?>
                                                                )
                                                            </time>
                                                        </div>
                                                        <ul class="mjs-info clearfix">
                                                            <?php foreach ($goods_detail['mansong_info']['rule'] as $rule) { ?>
                                                                <li> <span class="sale-rule"><?= __('购物满') ?>
                                                                        <em><?= format_money($rule['rule_price']) ?></em>
                                                                        <?php if (!empty($rule['rule_discount'])) { ?>
                                                                            <?= __('， 即享') ?>
                                                                            <em><?= (($rule['rule_discount'])) ?></em><?= __('元优惠') ?>
                                                                        <?php } ?>
                                                                        <?php if (!empty($rule['goods_id'])) { ?>
                                                                            <?= __('， 送 ') ?><a
                                                                            href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= ($rule['goods_id']) ?>"
                                                                            title="<?= ($rule['goods_name']) ?>"
                                                                            target="_blank" class="gift"> <img
                                                                                    src="<?= image_thumb($rule['goods_image'], 60, 60) ?>"
                                                                                    alt="<?= ($rule['goods_name']) ?>">
                                                                            </a>&nbsp;<br><?= __('，数量有限，赠完为止。 ') ?>
                                                                        <?php } ?>
                                                      </span></li>
                                                            <?php } ?>
                                                        </ul>
                                                        <div class="mjs-remark wp100 overflow"><?= ($goods_detail['mansong_info']['mansong_remark']) ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <!--E 满即送 -->
                                    </div>
                                <?php } ?>

                            <?php } ?>
                        </div>

                        <p class="mar-top">
                            <span class="span_w lineh-2 mar_l "><?= __('配送至：') ?></span>
                        </p>
                        <div class="span_w_p clearfix">
                            <div id="ncs-freight-selector" class="ncs-freight-select">
                                <div class="text">
                                    <div><?php if ($goods_detail['transport']) {
                                            echo $goods_detail['transport']['area'];
                                        } else {
                                            echo __('请选择地区');
                                        } ?></div>
                                    <b>∨</b></div>
                                <div class="content">
                                    <div id="ncs-stock" class="ncs-stock" data-widget="tabs">
                                        <div class="mt">
                                            <ul class="tab">
                                                <li data-index="0" data-widget="tab-item" class="curr"><a
                                                            href="#none" class="hover"><em><?= __('请选择') ?></em><i>
                                                            ∨</i></a></li>
                                            </ul>
                                        </div>
                                        <div id="stock_province_item" data-widget="tab-content" data-area="0">
                                            <ul class="area-list">
                                            </ul>
                                        </div>
                                        <div id="stock_city_item" data-widget="tab-content" data-area="1"
                                             style="display: none;">
                                            <ul class="area-list">
                                            </ul>
                                        </div>
                                        <div id="stock_area_item" data-widget="tab-content" data-area="2"
                                             style="display: none;">
                                            <ul class="area-list">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <a href="javascript:;" class="close"
                                   onclick="$('#ncs-freight-selector').removeClass('hover')"><?= __('关闭') ?></a>
                            </div>

                            <span class="goods_have linehe">
                                    <?php if ($goods_detail['goods_base']['goods_stock'] <= 0) {

                                        echo __('无货');

                                    } ?>
                                </span>

                            <em class="transport" id="transport_all_money"></em>
                        </div>

                        <?php if (isset($goods_detail['common_base']['common_spec_name']) && isset($goods_detail['common_base']['common_spec_value']) && $goods_detail['common_base']['common_spec_value']) {
                            foreach ($goods_detail['common_base']['common_spec_name'] as $speck => $specv) {
                                ?>
                                <p class="goods_pl"><span class="span_w lineh-3 mar_l "><?= ($specv) ?>：</span>
                                    <?php if (isset($goods_detail['common_base']['common_spec_value']) && $goods_detail['common_base']['common_spec_value']) {
                                        foreach ($goods_detail['common_base']['common_spec_value'][$speck] as $specvk => $specvv) {
                                            ?>
                                            <a <?php if (isset($goods_detail['goods_base']['goods_spec'][$specvk])) { ?> class="check" <?php } ?>
                                                    value="<?= ($specvk) ?>">
                                                <?= ($specvv) ?>
                                            </a>
                                        <?php }
                                    } ?>
                                </p>
                            <?php }
                        } ?>
                        <!--                           <p class="purchase_type "><span class="span_w ">购买方式:</span> <a href="# ">全新未拆封</a></p>-->
                        <?php if ($goods_detail['chain_stock']) { ?>
                            <p class="clearfix">
                                <span class="mar-r _letter-spacing-2">门店服务：</span>
                                <span class="color-1876d1 mar-b-4 ">
                                        <a href="javascript:;" name="elist" class="num_style mendian">
                                            <i class="iconfont icon-tabhome"></i><?= __('门店自提') ?>
                                        </a>
                                    <? __('· 选择有现货的门店下单，可立即提货') ?>
                                    </span>
                            </p>
                        <?php } ?>
                        <?php if ($goods_status) { ?>

                            <p class="need_num clearfix">
                                <span class="span_w lineh-6 mar_l "><?= __('数量：') ?></span>
                                <span class="goods_num">
                                            <a class="no_reduce"><?= __('-') ?></a>
                                            <input id="nums" name="nums" AUTOCOMPLETE="off"
                                                   data-id="<?= ($goods_detail['goods_base']['goods_id']) ?>"
                                                   data-min="<?php if ($goods_detail['goods_base']['lower_limits']): ?><?= ($goods_detail['goods_base']['lower_limits']) ?><?php else: ?><?= (1) ?><?php endif; ?>"
                                                   data-max="<?php if ($goods_detail['buyer_limit']): ?><?= ($goods_detail['buyer_limit']) ?><?php else: ?><?= ($goods_detail['goods_base']['goods_stock']) ?><?php endif; ?>"
                                                   value="<?php if ($goods_detail['goods_base']['lower_limits']) echo $goods_detail['goods_base']['lower_limits']; else echo 1; ?>">
                                            <input type="hidden"
                                                   value="<?= ($goods_detail['common_base']['common_cubage']) ?>"
                                                   id="weight"/>
                                            <a class="<?php if ($goods_detail['buy_limit'] == 1 || $goods_detail['goods_base']['goods_stock'] == 1): ?>no_<?php endif; ?>add"><?= __('+') ?></a>
                                        </span>

                                <span class="stock_num">&nbsp;&nbsp;(<?= __('库存') ?><?= ($goods_detail['goods_base']['goods_stock']) ?><?= __('件') ?>
                                    )</span>

                                <?php if ($goods_detail['buy_limit']) { ?>
                                    <span class="limit_purchase "><?= __('每人限购') ?><?= ($goods_detail['buy_limit']) ?><?= __('件') ?></span>
                                <?php } ?>
                            </p>
                            <?php if ($goods_detail['goods_base']['goods_stock']): ?>
                                <?php if ($goods_detail['common_base']['common_is_virtual']) { ?>
                                    <p class="buy_box">
                                        <a class="tuan_go buy_now_virtual bbc_btns"><?= __('立即购买') ?></a>
                                    </p>
                                <?php } else if ($goods_detail['common_base']['product_is_behalf_delivery'] == 1 && $goods_detail['common_base']['common_parent_id']) { ?>
                                    <p class="buy_box">
                                        <a class="tuan_go buy_now_supplier bbc_btns"><?= __('立即购买') ?></a>
                                    </p>
                                <?php } else { ?>
                                    <p class="buy_box">
                                        <a class="tuan_join_cart bbc_btns"><?= __('加入购物车') ?></a>
                                        <a class="tuan_go buy_now  bbc_color bbc_border"><?= __('立即购买') ?></a>
                                    </p>
                                <?php } ?>

                            <?php endif; ?>
                            <p class="buy_box_gray" <?php if ($goods_detail['goods_base']['goods_stock']) { ?> style="display: none;"<?php } ?> >
                                <?php if ($goods_detail['common_base']['common_is_virtual'] != 1) { ?>
                                    <a class="tuan_join_cart_gray bbc_btns"><?= __('加入购物车') ?></a>
                                <?php } ?>
                                <a class="tuan_go_gray bbc_color bbc_border"><?= __('立即购买') ?></a>
                            </p>
                        <?php } else { ?>
                            <div class="good_status lower-frame"><?= __('已下架') ?></div>
                        <?php } ?>

                    </div>
                </div>
                <div class="ev_right ">
                    <div class="ev_right_pad ">
                        <div class="divimg ">
                            <?php if (!empty($shop_detail['shop_logo'])) {
                                $shop_logo = $shop_detail['shop_logo']; ?>
                                <img class='lazy' width=200 height=60 data-original="<?= ($shop_logo) ?>">
                            <?php }/*else{
                                    $shop_logo =$this->web['shop_logo']; }*/
                            ?>


                        </div>
                        <div class="txttitle clearfix ">
                            <p>
                                <a class="store-names" href="javascript:;"><?= ($shop_detail['shop_name']) ?></a>
                                <?php if (Web_ConfigModel::value('im_statu') && Yf_Registry::get('im_statu')) { ?>
                                    <a href="javascript:;" class="chat-enter" rel=""></a>
                                <?php } ?>
                            </p>
                            <?php if ($shop_detail['shop_self_support'] == 'true') { ?>
                                <div class="bbc_btns"><?= __('平台自营') ?></div>
                            <?php } ?>
                        </div>

                        <!-- 品牌-->
                        <?php if ($shop_detail['shop_self_support'] == 'false') { ?>
                            <div class="brandself ">
                                <ul class="shop_score clearfix ">
                                    <li><?= __('店铺动态评分') ?></li>
                                    <li><?= __('同行业相比') ?></li>
                                </ul>
                                <ul class="shop_score_content clearfix ">
                                    <li>
                                        <span><?= __('描述相符：') ?><?= number_format($shop_detail['shop_desc_scores'], 2, '.', '') ?></span>
                                        <span class="high_than bbc_bg">
                                            <?php if ($shop_detail['com_desc_scores'] >= 0): ?><i
                                                    class="iconfont  icon-gaoyu rel_top1"></i>
                                                <?= __('高于') ?><?php else: ?><i
                                                    class="iconfont  icon-diyu rel_top1"></i><?= __('低于') ?><?php endif; ?>
                                        </span>
                                        <em class="bbc_color"><?= number_format(abs($shop_detail['com_desc_scores']), 2, '.', '') ?><?= __('%') ?></em>
                                    </li>
                                    <li>
                                        <span><?= __('服务态度：') ?><?= number_format($shop_detail['shop_service_scores'], 2, '.', '') ?></span>
                                        <span class="high_than bbc_bg">
                                            <?php if ($shop_detail['com_service_scores'] >= 0): ?><i
                                                    class="iconfont  icon-gaoyu rel_top1"></i><?= __('高于') ?><?php else: ?>
                                                <i class="iconfont  icon-diyu rel_top1"></i><?= __('低于') ?><?php endif; ?>
                                        </span>
                                        <em class="bbc_color"><?= number_format(abs($shop_detail['com_service_scores']), 2, '.', '') ?><?= __('%') ?></em>
                                    </li>
                                    <li>
                                        <span><?= __('发货速度：') ?><?= number_format($shop_detail['shop_send_scores'], 2, '.', '') ?></span>
                                        <span class="high_than bbc_bg">
                                            <?php if ($shop_detail['com_send_scores'] >= 0): ?><i
                                                    class="iconfont  icon-gaoyu rel_top1"></i><?= __('高于') ?><?php else: ?>
                                                <i class="iconfont  icon-diyu rel_top1"></i><?= __('低于') ?><?php endif; ?>
                                        </span>
                                        <em class="bbc_color"><?= number_format(abs($shop_detail['com_send_scores']), 2, '.', '') ?><?= __('%') ?></em>
                                    </li>
                                </ul>
                            </div>

                            <div class="shop_address">
                                <?= __('所 在 地 ：') ?><?= ($shop_detail['shop_company_address']) ?>
                            </div>

                            <div class="follow_shop ">
                                <a href="javascript:;" target="_blank" class="shop_enter"><?= __('进入店铺') ?></a>
                                <a class="shop_save"><?= __('收藏店铺') ?></a>
                            </div>

                        <?php } ?>

                        <?php if (isset($shop_detail['contract']) && $shop_detail['contract']): ?>
                            <span class="fwzc "><?= __('服务支持：') ?></span>
                            <ul class="ev_right_ul clearfix ">
                                <?php foreach ($shop_detail['contract'] as $sckey => $scval): ?>
                                    <a href="javascript:;">
                                        <li><i><img class='lazy' width=22 height=22
                                                    data-original="<?= image_thumb($scval['contract_type_logo'], 22, 22) ?>"/></i>&nbsp;&nbsp;&nbsp;<?= ($scval['contract_type_name']) ?>
                                        </li>
                                    </a>
                                <?php
                                endforeach;
                                ?>
                            </ul>
                        <?php
                        endif;
                        ?>
                    </div>
                    <!-- 自营 -->
                    <?php if ($shop_detail['shop_self_support'] == 'true') { ?>
                        <div class="look_again "><?= __('看了又看') ?></div>
                        <ul class="look_again_goods clearfix ">
                            <?php if (!empty($data_recommon_goods)) {
                                foreach ($data_recommon_goods as $key_recommon => $value_recommon) {
                                    ?>
                                    <li>
                                        <a target="_blank"
                                           href="javascript:;">
                                            <img class='lazy'
                                                 data-original="<?= $value_recommon['common_image'] ?>"/>
                                            <h5 class="bbc_color"><?= format_money($value_recommon['common_price']) ?></h5>
                                        </a>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    <?php } ?>
                </div>

            </div>

        </div>
    </div>
</div>

<div class="wrap">
    <div class="t_goods_bot clearfix ">
        <div class="t_goods_bot_left ">

            <?php if ($shop_detail['shop_self_support'] == 'false') { ?>

                <div class="goods_classify">
                    <h4><?= ($shop_detail['shop_name']) ?>
                        <?php if ($shop_detail['shop_qq']) { ?>
                        <a rel="1" target="_blank" href="javascript:;" title="QQ: <?= $shop_detail['shop_qq'] ?>"><img
                                    border="0"
                                    src="http://wpa.qq.com/pa?p=2:<?= $shop_detail['shop_qq'] ?>:52&amp;r=0.22914223582483828"
                                    style=" vertical-align: middle;">
                            </a><?php } ?><?php if ($shop_detail['shop_ww']) { ?>
                            <a rel="2" target="_blank" href="javascript:;"><img border="0"
                                                                                src='http://amos.alicdn.com/realonline.aw?v=2&uid=<?= $shop_detail['shop_ww'] ?>&site=cntaobao&s=2&charset=utf-8'
                                                                                alt="<?= __('点击这里给我发消息') ?>"
                                                                                style=" vertical-align: middle;">
                            </a><?php } ?></h4>


                </div>

            <?php } ?>

            <div class="goods_classify ">
                <h4><?= __('商品分类') ?></h4>
                <p class="classify_like">
                    <a href="javascript:;"><?= __('按新品') ?></a>
                    <a href="javascript:;"><?= __('按价格') ?></a>
                    <a href="javascript:;"><?= __('按销量') ?></a>
                    <a href="javascript:;"><?= __('按人气') ?></a></p>

                <p class="classify_ser"><input type="text" name="searchGoodsList" placeholder="<?= __('搜索店内商品') ?>"><a
                            id="searchGoodsList"><?= __('搜索') ?></a></p>
            </div>
            <div class="goods_ranking ">
                <h4><?= __('商品排行') ?></h4>
                <p class="selling"><a><?= __('热销商品排行') ?></a><a><?= __('热门收藏排行') ?></a></p>
                <ul id="hot_salle">
                    <?php if (!empty($data_salle)) {
                        foreach ($data_salle as $key_salle => $value_salle) {
                            ?>
                            <li class="clearfix">
                                <a target="_blank" href="javascript:;"
                                   class="selling_goods_img"><img class='lazy'
                                                                  data-original="<?= $value_salle['common_image'] ?>"></a>

                                <p>
                                    <a target="_blank" href="javascript:;"><?= $value_salle['common_name'] ?></a>
                                    <span class="bbc_color"><?= format_money($value_salle['common_price']) ?></span>
                                    <span>
                                                <i></i><?= __('出售：') ?>
                                        <i class="num_style"><?= $value_salle['common_salenum'] ?></i> <?= __('件') ?>
                                           </span>
                                </p>
                            </li>
                            <?php
                        }
                    } ?>
                </ul>
                <ul style="display: none;" id="hot_collect">
                    <?php if (!empty($data_collect)) {
                        foreach ($data_collect as $key_collect => $value_collect) {
                            ?>
                            <li class="clearfix">
                                <a target="_blank" href="javascript:;"
                                   class="selling_goods_img"><img class='lazy'
                                                                  data-original="<?= $value_collect['common_image'] ?>"></a>

                                <p>
                                    <a target="_blank" href="javascript:;"><?= $value_collect['common_name'] ?></a>
                                    <span class="bbc_color"><?= format_money($value_collect['common_price']) ?></span>
                                    <span>
                                            <i></i><?= __('收藏人气：') ?>
                                        <i class="num_style"><?= $value_collect['common_salenum'] ?></i>
                                        </span>
                                </p>
                            </li>
                            <?php
                        }
                    } ?>
                </ul>
                <a target="_blank" href="javascript:;"><p class="look_other_goods bbc_btns"><?= __('查看本店其他商品') ?></p>
                </a>
            </div>
        </div>
        <div name="elist" id="elist"></div>
        <div class="t_goods_bot_right ">
            <ul class="goods_det_about goods_det clearfix border_top">
                <li><a class="xq checked"><?= __('商品详情') ?></a></li>
                <li class="al"><a class="pl"><?= __('商品评论') ?></a></li>
                <!--<li><a class="xs"><? /*=__('销售记录')*/ ?><span><? /*=__('(')*/ ?><? /*= ($goods_detail['goods_base']['salecount']) */ ?><? /*=__(')')*/ ?></span></a></li>-->
                <?php if ($entity_shop){ ?>
                <li><a class="wz"><?= __('商家位置') ?></a></li>
                <?php } ?>
                <li><a class="bz"><?= __('包装清单') ?></a></li>
                <li><a class="sh"><?= __('售后保障') ?></a></li>
                <li><a class="zl"><?= __('购买咨询') ?></a></li>
            </ul>

            <ul class="goods_det_about_cont">

                <!-- 商家位置 -->
                <li class="wz_1 clearfix" style="display: none;">
                    <?php if ($entity_shop){ ?>
                    <div id="baidu_map" style="height:600px;width: 79%;border:1px solid gray"></div>
                    <div class="entity_shop">
                        <?php foreach ($entity_shop as $key => $value) { ?>
                        <div class="entity_shop_box">
                            <strong class="entity_shop_name"><?= $value['entity_name'] ?></strong>
                            <?php if (in_array($value['province'], array('北京市', '上海市', '天津市', '重庆市', '香港特别行政区', '澳门特别行政区'))){ ?>
                            <span class="entity_shop_address"><?= __("地址：") ?><?= $value['city'] ?><?= $value['entity_xxaddr'] ?></span>

                            <?php }else{ ?>
                            <span class="entity_shop_address"><?= __("地址：") ?><?= $value['province'] ?><?= $value['city'] ?><?= $value['entity_xxaddr'] ?></span>
                            <?php } ?>
                            <span class="entity_shop_tel"><?= __("电话：") ?><?= $value['entity_tel'] ?></span>
                        </div>
                        <?php } ?>
                    </div>


                    <?php } ?>
                </li>
                <!--商品咨询-->
                <div id="goodsadvisory" style="display:none;" class="ncs-commend-main zl_1"></div>
                <!-- 商品评论 -->
                <div id="goodseval" style="display:none;" class="ncs-commend-main pl_1"></div>
                <!-- 商品查询 -->
                <div id="saleseval" style="display:none;" class="ncs-commend-main xs_1"></div>
                <!-- 详细-->
                <li class="xq_1" style="display:block;    position: relative;">

                </li>
                <!-- 包装清单 -->
                <li class="bz_1 tlf" style="display: none">
                    <div class="product-details">
                        <div>
                            <?= html_entity_decode($goods_detail['common_base']['common_packing_list']) ?>
                        </div>
                    </div>
                </li>
                <!-- 售后服务 -->
                <li class="sh_1 tlf" style="display: none">
                    <div class="product-details">
                        <div>
                            <?= html_entity_decode($goods_detail['common_base']['common_service']) ?>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

    </div>
</div>

<div class="footer">
    <div class="wrapper">
        <?php if (!$this->ctl =="Seller_Shop_Settled"){ ?>
        <div class="promise">
            <div><span class="iconfont icon-qitiantuihuan bbc_color"></span><strong
                        class="bbc_color"><?= __('七天退货') ?></strong></div>
            <div><span class="iconfont icon-iconzhengping bbc_color"></span><strong
                        class="bbc_color"><?= __('正品保障') ?></strong></div>
            <div><span class="iconfont icon-iconshandian bbc_color"></span><strong
                        class="bbc_color"><?= __('闪电发货') ?></strong></div>
            <div><span class="iconfont icon-iconbaoyou bbc_color"></span><strong
                        class="bbc_color"><?= __('满额免邮') ?></strong></div>
        </div>
        <?php } ?>
        <ul class="services clearfix">
            <?php if (!empty($this->foot)):
            $i = 1;
            $j = 0;
            $Article_BaseModel = new Article_BaseModel();
            foreach ($this->foot as $key => $value):
            $j ++;
            if ($j>6)break;
            ?>
            <li>
                <h5><i class="iconfont icon-weibu<?= $i ?>"></i><span><?= $value['group_name'] ?></span></h5>
                <?php
                if (!empty($value['article'])):
                foreach ($value['article'] as $k => $v):
                ?>
                                <?php if ($v['article_status'] ==$Article_BaseModel::ARTICLE_STATUS_TRUE){ ?>
                                    <?php if (!empty($v['article_url'])){ ?>
                <p>
                    <a href="javascript:;">&bull;&nbsp;<?= $v['article_title'] ?></a>
                </p>
                <?php }else{ ?>
                <p>
                    <a href="javascript:;">&bull;&nbsp;<?= $v['article_title'] ?></a>
                </p>
                <?php } ?>
                                <?php } ?>
								<?php
                endforeach;
                endif;
                ?>
            </li>
            <?php
            $i++;
            endforeach;
            endif; ?>
        </ul>
        <p class="about">
            <?php if (isset($this->bnav) && $this->bnav){
            foreach ($this->bnav['items'] as $key => $nav) {
            if ($key<10){
            ?>
            <a href="javascript:;"
               <?php if ($nav['nav_new_open']==1){ ?>target="_blank"<?php } ?>><?= $nav['nav_title'] ?></a>
            <?php }else{
            return;
            }
            }
            } ?>
        </p>

        <p class="copyright"><?php if (!empty($_COOKIE['sub_site_id']) && Web_ConfigModel::value("subsite_is_open") == Sub_SiteModel::SUB_SITE_IS_OPEN  && isset($_COOKIE['sub_site_copyright'])){
            echo $_COOKIE['sub_site_copyright'];
            }else{
            echo Web_ConfigModel::value('copyright');
            } ?></p>

    </div>
</div>
</div>

</body>
<script>
    var goods_id = <?=($goods_detail['goods_base']['goods_id'])?>;
    var common_id = <?=($goods_detail['goods_base']['common_id'])?>;
    var shop_id = <?=($shop_detail['shop_id'])?>;
    lazyload();

    function contains(arr, str) {//检测goods_id是否存入
        var i = arr.length;
        while (i--) {
            if (arr[i] == str) {
                return true;
            }
        }
        return false;
    }

    //热销商品，热收商品
    $(".selling").children().eq(0).hover(function () {
        $("#hot_salle").show();
        $("#hot_collect").hide();
    });
    $(".selling").children().eq(1).hover(function () {
        $("#hot_salle").hide();
        $("#hot_collect").show();
    });
</script>
<script>
    $(window).load(function () {
        $.ajax({
            type: 'POST',
            url: SITE_URL + '/index.php?ctl=Goods_Goods&met=getGoodsDetailFormat&typ=json',
            data: {gid: goods_id, isCheck: false},
            dataType: 'JSON',
            success: function (data) {
                console.log(data);
                var html = '';
                if (data.data.goods_format_top) {
                    html += data.data.goods_format_top;
                }
                if (data.data.brand_name) {
                    html += '<p style="text-align: left;"><?=__('品牌')?>：' + data.data.brand_name + '</p>';
                }
                if (data.data.common_property_row) {
                    for (var i in data.data.common_property_row) {
                        html += '<span style="margin:5%; width: 22%;">' + i + '：' + data.data.common_property_row[i] + '</span>';
                    }
                }
                html += data.data.common_detail;
                if (data.data.goods_format_bottom) {
                    html += data.data.goods_format_bottom;
                }
                // console.log(html);
                // console.log(data.data);
                $('.xq_1').html(html);
            }
        })
    })

</script>


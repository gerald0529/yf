<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'supplier_header.php';
?>

    <link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/goods-detail.css"/>
    <link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/Group-integral.css"/>
    <script type="text/javascript" src="<?= $this->view->js ?>/tuangou-index.js"></script>
    <script src="<?= $this->view->js_com ?>/plugins/jquery.slideBox.min.js" type="text/javascript"></script>
    <script src="<?= $this->view->js_com ?>/sppl.js"></script>
    <script src="<?= $this->view->js ?>/supplier_detail.js"></script>
    <script src="<?= $this->view->js_com ?>/plugins/jquery.imagezoom.min.js"></script>
    <script type="text/javascript" src="<?= $this->view->js ?>/common.js"></script>
    <link href="<?= $this->view->css ?>/tips.css" rel="stylesheet">
    <link href="<?= $this->view->css ?>/login.css" rel="stylesheet">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.toastr.min.js"
            charset="utf-8"></script>
    <style>
        div.zoomMask {
            position: absolute;
            background: url("<?=$this->view->img?>/mask.png") repeat scroll 0 0 transparent;
            cursor: move;
            z-index: 1;
        }
    </style>
    <div class="bgcolor">
        <div class="wrapper">
            <div class="t_goods_detail">
                <div class="crumbs clearfix">
                    <p>
                        <?php if ($parent_cat) { ?>
                            <?php foreach ($parent_cat as $catkey => $catval): ?>
                                <a
                                href="<?= Yf_Registry::get('url') ?>?ctl=Supplier_Goods&met=goodslist&cat_id=<?= ($catval['cat_id']) ?>"><?= ($catval['cat_name']) ?></a><?php if (!isset($catval['ext'])) { ?>
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

                            <img class="jqzoom lazy" rel="<?= image_thumb($goods_image, 900, 976) ?>"
                                 data-original="<?= image_thumb($goods_image, 366, 340) ?>"/>
                        </div>
                        <div class="retw">
                            <a><i class="iconfont icon-btnreturnarrow btn_left"></i></a>
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
                                            <img width=60 height=60 class='lazy'
                                                 data-original="<?= image_thumb($goods_image, 60, 60) ?>"/>
                                            <input type="hidden" value="<?= image_thumb($goods_image, 366, 340) ?>"
                                                   rel="<?= image_thumb($goods_image, 900, 976) ?>">
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <a><i class="iconfont icon-btnrightarrow btn_right"></i></a>
                        </div>
                        <div class="ev_left_num">                            
                            <span class="cprodict ">
                                <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Report&met=index&act=add&gid=<?= ($goods_detail['goods_base']['goods_id']) ?>">
                                <b class="iconfont icon-jubao icon-1 bbc_color"></b><?= __('举报') ?>
                                </a>
                            </span>
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
                            <?php if ($goods_detail['goods_base']['goods_recommended_min_price'] > $goods_detail['goods_base']['goods_price'] && $goods_detail['goods_base']['goods_recommended_max_price'] > $goods_detail['goods_base']['goods_recommended_min_price']) { ?>
                                <p class="clearfix">
                                    <span class="mar-r _letter-spacing"><?= __('利润比') ?>：</span>
                                    <span class="mar-b-1" style="color:#e45050;font-weight:bold;">
                  <?php echo number_format(($goods_detail['goods_base']['goods_recommended_min_price'] - $goods_detail['goods_base']['goods_price']) / $goods_detail['goods_base']['goods_price'] * 100, 2, '.', ''); ?>
                                        % ~ <?php echo number_format(($goods_detail['goods_base']['goods_recommended_max_price'] - $goods_detail['goods_base']['goods_price']) / $goods_detail['goods_base']['goods_price'] * 100, 2, '.', ''); ?>
                                        %
                  
                </span>
                                </p>
                            <?php } ?>
                            <p class="clearfix">
                                <span class="mar-r _letter-spacing"><?= __('成本价') ?>：</span>
                                <span class="mar-b-2">
                                    <strong class="color-db0a07 bbc_color"><?= format_money($goods_detail['goods_base']['goods_price']) ?></strong>
                                </span>
                            </p>
                            <p class="clearfix">
                                <span class="mar-r _letter-spacing"><?= __('建议价') ?>：</span>
                                <span class="mar-b-1">                                  
                                    <?= format_money($goods_detail['goods_base']['goods_recommended_min_price']) ?>
                                    ~ <?= format_money($goods_detail['goods_base']['goods_recommended_max_price']) ?>
                                </span>
                            </p>
                            <p class="clearfix"><span class="mar-r _letter-spacing-2"><?= __('商品评价') ?>：</span>
                                <span class="color-1876d1 mar-b-4 "><a href="#elist" name="elist" class="pl"><i
                                                class="num_style"><?= ($goods_detail['common_base']['common_evaluate']) ?></i> <?= __('条评论') ?></a></span>
                            </p>
                        </div>
                        <div class="goods_style_sel ">
                            <div>
                                <input type="hidden" id="common_id"
                                       value="<?= ($goods_detail['goods_base']['common_id']) ?>"/>

                                <?php if (isset($goods_detail['goods_base']['promotion_type']) || $goods_detail['goods_base']['have_gift'] == 'gift' || !empty($goods_detail['goods_base']['increase_info']) || !empty($goods_detail['mansong_info'])) { ?>
                                    <?php if (isset($goods_detail['goods_base']['promotion_type']) || !empty($goods_detail['mansong_info']) || !empty($goods_detail['goods_base']['increase_info'])) { ?>
                                        <span class="span_w lineh-1 mar_l "><?= __('促') ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= __('销') ?>
                                            ：</span>

                                        <div class="activity_reset">
                                            <?php if (isset($goods_detail['goods_base']['title']) && $goods_detail['goods_base']['title'] != '') { ?>
                                                <span><i class="iconfont icon-huanyipi"></i><?= ($goods_detail['goods_base']['title']) ?></span>

                                                <!--S 限时折扣 -->
                                                <?php if ($goods_detail['goods_base']['promotion_type'] == 'xianshi') { ?>
                                                    <i class="group_purchase "><?= __('限时折扣：') ?></i>
                                                    <strong><?= __('直降') ?></strong><?= ($goods_detail['goods_base']['down_price']) ?>
                                                    <?php if ($goods_detail['goods_base']['lower_limit']) { ?>
                                                        <?php echo sprintf("<?=__('最低')?>" . '%s' . "<?=__('件起')?>" . '，', $goods_detail['goods_base']['lower_limit']); ?><?php echo $goods_detail['goods_base']['explain']; ?>
                                                    <?php }
                                                } ?>
                                                <!--E 限时折扣 -->

                                                <!--S 团购 -->
                                                <?php if ($goods_detail['goods_base']['promotion_type'] == 'groupbuy') { ?>
                                                    <?php if ($goods_detail['goods_base']['upper_limit']) { ?>
                                                        <i class="group_purchase "><?= __('团购：') ?></i>
                                                        <em><?php echo sprintf("<?=__('最多限购')?>" . '%s' . "<?=__('件')?>", $goods_detail['goods_base']['upper_limit']); ?></em>
                                                    <?php } ?>
                                                    <span><?php echo $goods_detail['goods_base']['remark']; ?></span>
                                                <?php } ?>
                                                <!--E 团购 -->
                                            <?php } ?>

                                            <!--S 加价购 -->
                                            <?php if ($goods_detail['goods_base']['increase_info']) { ?>
                                                <div class="ncs-mansong">
                                                    <i class="group_purchase "><?= __('加价购') ?>：</i>
                                                    <span class="sale-rule">
                                                  <em><?= ($goods_detail['goods_base']['increase_info']['increase_name']) ?></em>

                                                        <?php if (!empty($goods_detail['goods_base']['increase_info']['rule'])) { ?>
                                                            <?= __('购物满') ?>
                                                            <em><?= format_money($goods_detail['goods_base']['increase_info']['rule'][0]['rule_price']) ?></em><?= __('即可加价换购') ?><?php if ($goods_detail['goods_base']['increase_info']['rule'][0]['rule_goods_limit']): ?><?= ($goods_detail['goods_base']['increase_info']['rule'][0]['rule_goods_limit']) ?><?= __('样') ?><?php endif; ?><?= __('商品') ?>
                                                        <?php } ?>

                                                        <?php if (!empty($goods_detail['goods_base']['increase_info']['goods'])) { ?>
                                                        <?= __('加价购商品') ?>
                                                </span>
                                                    <span class="sale-rule-more" nctype="show-rule">
                                                    <a href="javascript:void(0);"><?= __('共') ?>
                                                        <strong><?php echo count($goods_detail['goods_base']['increase_info']['goods']); ?></strong>
                                                        <?= __('样') ?>，<?= __('展开') ?><i
                                                                class="iconfont icon-iconjiantouxia"></i>
                                                    </a>
                                                  </span>

                                                    <div class="sale-rule-content" style="display: none;"
                                                         nctype="rule-content">
                                                        <div class="title"><span
                                                                    class="sale-name"><?= __('加价购') ?></span><?= __('共') ?>
                                                            <strong><?php echo count($goods_detail['goods_base']['increase_info']['goods']); ?></strong>
                                                            <?= __('样') ?>，<?= __('换购商品') ?><a href="javascript:;"
                                                                                               nctype="hide-rule"><?= __('关闭') ?></a>
                                                        </div>
                                                        <div class="content clearfix">
                                                            <div class="mjs-tit">
                                                                <?= ($goods_detail['goods_base']['increase_info']['increase_name']) ?>
                                                            </div>
                                                            <ul class="mjs-info clearfix">
                                                                <?php foreach ($goods_detail['goods_base']['increase_info']['goods'] as $goods) { ?>
                                                                    <li>
                                                                        <a href="<?= Yf_Registry::get('url') ?>?ctl=Supplier_Goods&met=goods&gid=<?= ($goods['goods_id']) ?>"
                                                                           title="<?= ($goods['goods_name']) ?>"
                                                                           target="_blank" class="gift"> <img
                                                                                    src="<?= image_thumb($goods['goods_image'], 80, 80) ?>"
                                                                                    alt="<?= ($goods['goods_name']) ?>">
                                                                        </a>&nbsp;
                                                                    </li>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                            <!--E 加价购 -->

                                            <!--S 满即送 -->
                                            <?php if ($goods_detail['mansong_info'] && $goods_detail['mansong_info']['rule']) { ?>
                                                <div class="ncs-mansong">
                                                    <i class="group_purchase "><?= __('满即送') ?>：</i>
                                                    <span class="sale-rule">
                                              <?php $rule = $goods_detail['mansong_info']['rule'][0]; ?>
                                                        <?= __('购物满') ?>
                                                        <em><?= format_money($rule['rule_price']) ?></em>
                                                        <?php if (!empty($rule['rule_discount'])) { ?>
                                                            ，<?= __('即享') ?>
                                                            <em><?= ($rule['rule_discount']) ?></em><?= __('折优惠') ?>
                                                        <?php } ?>
                                                        <?php if (!empty($rule['goods_id'])) { ?>
                                                            <?= __('，送') ?><a
                                                            href="<?= Yf_Registry::get('url') ?>?ctl=Supplier_Goods&met=goods&gid=<?= ($rule['goods_id']) ?>"
                                                            title="<?= ($rule['goods_name']) ?>"
                                                            target="_blank"><?= __('赠品') ?></a>
                                                        <?php } ?>
                                              </span> <span class="sale-rule-more" nctype="show-rule"><a
                                                                href="javascript:void(0);"><?= __('共') ?>
                                                            <strong><?php echo count($goods_detail['mansong_info']['rule']); ?></strong><?= __('项') ?>
                                                            ，<?= __('展开') ?><i class="iconfont icon-iconjiantouxia"></i></a></span>
                                                    <div class="sale-rule-content" style="display: none;"
                                                         nctype="rule-content">
                                                        <div class="title"><span
                                                                    class="sale-name"><?= __('满即送') ?></span><?= __('共') ?>
                                                            <strong><?php echo count($goods_detail['mansong_info']['rule']); ?></strong><?= __('项') ?>
                                                            ，<?= __('促销活动规则') ?><a href="javascript:;"
                                                                                   nctype="hide-rule"><?= __('关闭') ?></a>
                                                        </div>
                                                        <div class="content clearfix">
                                                            <div class="mjs-tit"><?= ($goods_detail['mansong_info']['mansong_name']) ?>
                                                                <time>
                                                                    (<?= ($goods_detail['mansong_info']['mansong_start_time']) ?>
                                                                    -- <?= ($goods_detail['mansong_info']['mansong_end_time']) ?>
                                                                    )
                                                                </time>
                                                            </div>
                                                            <ul class="mjs-info">
                                                                <?php foreach ($goods_detail['mansong_info']['rule'] as $rule) { ?>
                                                                    <li> <span class="sale-rule"><?= __('购物满') ?>
                                                                            <em><?= format_money($rule['rule_price']) ?></em>
                                                                            <?php if (!empty($rule['rule_discount'])) { ?>
                                                                                ， <?= __('即享') ?>
                                                                                <em><?= (($rule['rule_discount'])) ?></em><?= __('折优惠') ?>
                                                                            <?php } ?>
                                                                            <?php if (!empty($rule['goods_id'])) { ?>
                                                                                ， <?= __('送') ?> <a
                                                                                        href="<?= Yf_Registry::get('url') ?>?ctl=Supplier_Goods&met=goods&gid=<?= ($rule['goods_id']) ?>"
                                                                                        title="<?= ($rule['goods_name']) ?>"
                                                                                        target="_blank"
                                                                                        class="gift"> <img
                                                                                            src="<?= image_thumb($rule['goods_image'], 60, 60) ?>"
                                                                                            alt="<?= ($rule['goods_name']) ?>"> </a>&nbsp;。
                                                                            <?php } ?>
                                                      </span></li>
                                                                <?php } ?>
                                                            </ul>
                                                            <div class="mjs-remark"><?= ($goods_detail['mansong_info']['mansong_remark']) ?></div>
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
                                <span class="span_w lineh-2 mar_l "><?= __('配送至') ?>：</span>
                            </p>
                            <div class="span_w_p clearfix">
                                <div id="ncs-freight-selector" class="ncs-freight-select">
                                    <div class="text">
                                        <div><?= __('请选择地区') ?></div>
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
                                    <?php if ($goods_detail['goods_base']['goods_stock']): ?><?= __('有货') ?><?php else: ?><?= __('无货') ?><?php endif; ?>
                                </span>
                                <?php if ($goods_detail['shop_base']['shipping']) { ?>
                                    <em><?= ($goods_detail['shop_base']['shipping']) ?></em>
                                <?php } ?>
                                <em class="transport"></em>
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
                                <p class="clearfix"><span class="mar-r _letter-spacing-2"><?= __('门店服务') ?>：</span>
                                    <span class="color-1876d1 mar-b-4 "><a href="#" name="elist"
                                                                           class="num_style mendian" nctype="get_chain"> <i
                                                    class="iconfont icon-tabhome"></i><?= __('门店自提') ?></a>· <?= __('选择有现货的门店下单') ?>
                                        ，<?= __('可立即提货') ?></span>
                                </p>
                            <?php } ?>
                            <?php if ($goods_status) { ?>
                                <p class="need_num clearfix">
                                    <span class="span_w lineh-6 mar_l "><?= __('数量') ?>：</span>
                                    <span class="goods_num">
                                      <a class="no_reduce"><?= __('-') ?></a><input id="nums" name="nums" AUTOCOMPLETE="off" data-id="<?= ($goods_detail['goods_base']['goods_id']) ?>" data-max="<?php if ($goods_detail['buy_limit']): ?><?= ($goods_detail['buy_limit']) ?><?php else: ?><?= ($goods_detail['goods_base']['goods_stock']) ?><?php endif; ?>" value="1"><a class="<?php if ($goods_detail['buy_limit'] == 1 || $goods_detail['goods_base']['goods_stock'] == 1): ?>no_<?php endif; ?>add"><?= __('+') ?></a>
                                    </span>
                                    <?php if ($goods_detail['buy_limit']) { ?>
                                        <span class="limit_purchase "><?= __('每人限购') ?><?= ($goods_detail['buy_limit']) ?><?= __('件') ?></span>
                                    <?php } ?>
                                </p>
                                <?php if ($goods_detail['goods_base']['goods_stock']){ ?>
                                  <?php if (isset($suppliers) && !in_array($goods_detail['goods_base']['shop_id'], $suppliers)){ ?>
                                      <a class="apply_dist" data-id=<?= $goods_detail['goods_base']['shop_id'] ?> style="margin-top:10px;display:inline-block;padding:10px 50px;background-color: #e45050;color: white;"><?= __('申请分销商') ?></a>
                                  <?php } elseif (isset($suppliers) && in_array($goods_detail['goods_base']['shop_id'], $suppliers) && $goods_detail['common_base']['product_is_behalf_delivery'] != 1) { ?>
                                      <?php if ($goods_detail['common_base']['common_is_virtual']){ ?>
                                          <p class="buy_box"><a class="tuan_go buy_now_virtual bbc_btns"><?= __('立即购买') ?></a></p>
                                      <?php }else{ ?>
                                          <p class="buy_box">
                                              <a class="tuan_join_cart bbc_btns"><?= __('加入购物车') ?></a>
                                              <a class="tuan_go buy_now  bbc_color bbc_border"><?= __('立即购买') ?></a>
                                          </p>
                                      <?php } ?>
                                  <?php } elseif ($goods_detail['common_base']['product_is_behalf_delivery'] && isset($suppliers) && in_array($goods_detail['goods_base']['shop_id'], $suppliers)) { ?>
                                      <?php if (isset($common_ids) && in_array($goods_detail['goods_base']['common_id'], $common_ids)) { ?>
                                        <a style="margin-top:10px;display:inline-block;padding: 10px 50px;background-color: #e45050;color: white;"> <?= __('已分销') ?> </a>
                                      <?php } else { ?>
                                        <a class="add_distributor" data-id=<?= $goods_detail['goods_base']['common_id'] ?> style="margin-top:10px;display:inline-block;padding:10px 50px;background-color: #e45050;color:white;" > <?= __('一键上架') ?> </a>
                                      <?php } ?>
                                  <?php } ?>
                                <?php }else{ ?>
                                  <?php if (isset($suppliers) && !in_array($goods_detail['goods_base']['shop_id'], $suppliers)){ ?>
                                    <a data-id=<?= $goods_detail['goods_base']['shop_id'] ?> style="margin-top:10px;display:inline-block;padding:10px 50px;background-color: #9a908d;color: white;"><?= __('申请分销商') ?></a>
                                  <?php } elseif (isset($suppliers) && in_array($goods_detail['goods_base']['shop_id'], $suppliers) && $goods_detail['common_base']['product_is_behalf_delivery'] != 1) { ?>
                                    <?php if ($goods_detail['common_base']['common_is_virtual']){ ?>
                                        <p><a class="tuan_go uncli bbc_btns"><?= __('立即购买') ?></a></p>
                                    <?php }else{ ?>
                                        <p>
                                            <a class="tuan_join_cart bbc_btns uncli"><?= __('加入购物车') ?></a>
                                            <a class="tuan_go uncli" style="color:white"><?= __('立即购买') ?></a>
                                        </p>
                                    <?php } ?>
                                  <?php } elseif ($goods_detail['common_base']['product_is_behalf_delivery'] && isset($suppliers) && in_array($goods_detail['goods_base']['shop_id'], $suppliers)) { ?>
                                    <?php if (isset($common_ids) && in_array($goods_detail['goods_base']['common_id'], $common_ids)) { ?>
                                      <a style="margin-top:10px;display:inline-block;padding: 10px 50px;background-color: #e45050;color: white;"> <?= __('已分销') ?> </a>
                                    <?php } else { ?>
                                      <a data-id=<?= $goods_detail['goods_base']['common_id'] ?> style="margin-top:10px;display:inline-block;padding:10px 50px;background-color: #9a908d;color:white;" > <?= __('一键上架') ?> </a>
                                    <?php } ?>
                                  <?php } ?>
                                <?php } ?>
                            <?php } else { ?>
                                <div class="good_status"><?= __('该商品已下架') ?></div>
                            <?php } ?>

                        </div>
                    </div>
                    <div class="ev_right ">
                        <div class="ev_right_pad ">
                            <div class="divimg ">
                                <?php if (!empty($shop_detail['shop_logo'])) {
                                    $shop_logo = $shop_detail['shop_logo'];
                                } else {
                                    $shop_logo = $this->web['shop_logo'];
                                }
                                ?>

                                <img src="<?= ($shop_logo) ?>">
                            </div>
                            <div class="txttitle clearfix ">
                                <p>
                                    <a class="store-names"
                                       href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&typ=e&id=<?= ($shop_detail['shop_id']) ?>"><?= ($shop_detail['shop_name']) ?></a>
                                    <?php if (Web_ConfigModel::value('im_statu') == 1) { ?>
                                        <a href="javascript:;" class="chat-enter"
                                           onclick="return chat('<?= $shop_detail['user_name'] ?>');"><i
                                                    class="iconfont icon-btncomment"></i></a>
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
                                            <span><?= __('描述相符') ?>
                                                ：<?= number_format($shop_detail['shop_desc_scores'], 2, '.', '') ?></span>
                                            <span class="high_than bbc_bg">
                                            <?php if ($shop_detail['com_desc_scores'] >= 0): ?><i
                                                    class="iconfont  icon-gaoyu rel_top1"></i>
                                                <?= __('高于') ?><?php else: ?><i
                                                    class="iconfont  icon-diyu rel_top1"></i><?= __('低于') ?><?php endif; ?>
                                        </span>
                                            <em class="bbc_color"><?= number_format(abs($shop_detail['com_desc_scores']), 2, '.', '') ?><?= __('%') ?></em>
                                        </li>
                                        <li>
                                            <span><?= __('服务态度') ?>
                                                ：<?= number_format($shop_detail['shop_service_scores'], 2, '.', '') ?></span>
                                            <span class="high_than bbc_bg">
                                            <?php if ($shop_detail['com_service_scores'] >= 0): ?><i
                                                    class="iconfont  icon-gaoyu rel_top1"></i><?= __('高于') ?><?php else: ?>
                                                <i class="iconfont  icon-diyu rel_top1"></i><?= __('低于') ?><?php endif; ?>
                                        </span>
                                            <em class="bbc_color"><?= number_format(abs($shop_detail['com_service_scores']), 2, '.', '') ?><?= __('%') ?></em>
                                        </li>
                                        <li>
                                            <span><?= __('发货速度') ?>
                                                ：<?= number_format($shop_detail['shop_send_scores'], 2, '.', '') ?></span>
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
                                    <?= __('所 ') ?> <?= __('在') ?> <?= __('地') ?>：<?= ($shop_detail['shop_region']) ?>
                                </div>

                                <div class="follow_shop ">
                                    <a href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&typ=e&id=<?= ($shop_detail['shop_id']) ?>"
                                       target="_blank" class="shop_enter"><?= __('进入店铺') ?></a>
                                    <a style="display:none" onclick="collectShop(<?= ($shop_detail['shop_id']) ?>)"
                                       class="shop_save"><?= __('收藏店铺') ?></a>
                                </div>

                            <?php } ?>

                            <?php if (isset($shop_detail['contract']) && $shop_detail['contract']): ?>
                                <span class="fwzc "><?= __('服务支持') ?>：</span>
                                <ul class="ev_right_ul clearfix ">
                                    <?php foreach ($shop_detail['contract'] as $sckey => $scval): ?>
                                        <a href="<?= ($scval['contract_type_url']) ?>">
                                            <li><i><img src="<?= image_thumb($scval['contract_type_logo'], 22, 22) ?>"/></i>&nbsp;&nbsp;&nbsp;<?= ($scval['contract_type_name']) ?>
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
                                               href="<?= Yf_Registry::get('url') ?>?ctl=Supplier_Goods&met=goods&gid=<?= ($value_recommon['goods_id']) ?>">
                                                <img src="<?= $value_recommon['common_image'] ?>"/>
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
                            <a rel="1" target="_blank"
                               href="http://wpa.qq.com/msgrd?v=3&uin=<?= $shop_detail['shop_qq'] ?>&site=qq&menu=yes"
                               title="QQ: <?= $shop_detail['shop_qq'] ?>"><img border="0"
                                                                               src="http://wpa.qq.com/pa?p=2:<?= $shop_detail['shop_qq'] ?>:52&amp;r=0.22914223582483828"
                                                                               style=" vertical-align: middle;">
                                </a><?php } ?><?php if ($shop_detail['shop_ww']) { ?>
                            <a rel="2" target="_blank"
                               href='http://www.taobao.com/webww/ww.php?ver=3&touid=<?= $shop_detail['shop_ww'] ?>&siteid=cntaobao&status=2&charset=utf-8'>
                                <img border="0"
                                     src='http://amos.alicdn.com/realonline.aw?v=2&uid=<?= $shop_detail['shop_ww'] ?>&site=cntaobao&s=2&charset=utf-8'
                                     alt="<?= __('点击这里给我发消息') ?>" style=" vertical-align: middle;"></a><?php } ?></h4>

                        <div class="service-list1" store_id="8" store_name="<?= ($shop_detail['shop_name']) ?>">
                            <?php if (!empty($service['pre'])) { ?>
                                <dl>
                                    <dt><?= __('售前客服') ?>：</dt>

                                    <?php foreach ($service['pre'] as $key => $val) { ?>
                                        <?php if (!empty($val['number'])) { ?>
                                            <dd><span><?= $val['name'] ?></span><span>
                  <span c_name="<?= $val['name'] ?>" member_id="9"><?= $val['tool'] ?></span>
                  </span></dd>
                                        <?php } ?>
                                    <?php } ?>
                                </dl>
                            <?php } ?>
                            <?php if (!empty($service['after'])) { ?>
                                <dl>
                                    <dt><?= __('售后客服') ?>：</dt>
                                    <?php foreach ($service['after'] as $key => $val) { ?>
                                        <?php if (!empty($val['number'])) { ?>
                                            <dd><span><?= $val['name'] ?></span><span>
                  <span c_name="<?= $val['name'] ?>" member_id="9"><?= $val['tool'] ?></span>
                  </span></dd>
                                        <?php } ?>
                                    <?php } ?>

                                </dl>
                            <?php } ?>
                            <?php if ($shop_detail['shop_workingtime']) { ?>
                                <dl class="workingtime">
                                    <dt><?= __('工作时间') ?>：</dt>
                                    <dd>
                                        <p><?= ($shop_detail['shop_workingtime']) ?></p>
                                    </dd>
                                </dl>
                            <?php } ?>
                        </div>
                    </div>

                <?php } ?>

                <div class="goods_ranking ">
                    <h4><?= __('商品排行') ?></h4>
                    <p class="selling"><a><?= __('热销商品排行') ?></a><a><?= __('热门收藏排行') ?></a></p>
                    <ul id="hot_salle">
                        <?php if (!empty($data_salle)) {
                            foreach ($data_salle as $key_salle => $value_salle) {
                                ?>
                                <li class="clearfix">
                                    <a target="_blank"
                                       href="<?= Yf_Registry::get('url') ?>?ctl=Supplier_Goods&met=goods&gid=<?= ($value_salle['goods_id']) ?>"
                                       class="selling_goods_img"><img src="<?= $value_salle['common_image'] ?>"></a>

                                    <p>
                                        <a target="_blank"
                                           href="<?= Yf_Registry::get('url') ?>?ctl=Supplier_Goods&met=goods&gid=<?= ($value_salle['goods_id']) ?>"><?= $value_salle['common_name'] ?></a>
                                        <span class="bbc_color"><?= format_money($value_salle['common_price']) ?></span>
                                        <span>
                                                <i></i><?= __('出售') ?>：
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
                                    <a target="_blank"
                                       href="<?= Yf_Registry::get('url') ?>?ctl=Supplier_Goods&met=goods&gid=<?= $value_collect['goods_id'] ?>"
                                       class="selling_goods_img"><img src="<?= $value_collect['common_image'] ?>"></a>

                                    <p>
                                        <a target="_blank"
                                           href="<?= Yf_Registry::get('url') ?>?ctl=Supplier_Goods&met=goods&gid=<?= $value_collect['goods_id'] ?>"><?= $value_collect['common_name'] ?></a>
                                        <span class="bbc_color"><?= format_money($value_collect['common_price']) ?></span>
                                        <span>
                                            <i></i><?= __('收藏人气') ?>：
                                            <i class="num_style"><?= $value_collect['common_salenum'] ?></i>
                                        </span>
                                    </p>
                                </li>
                                <?php
                            }
                        } ?>
                    </ul>
                    <a target="_blank"
                       href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=goodsList&id=<?= ($shop_detail['shop_id']) ?>">
                        <p class="look_other_goods bbc_btns"><?= __('查看本店其他商品') ?></p></a>
                </div>
            </div>
            <div name="elist" id="elist"></div>
            <div class="t_goods_bot_right ">
                <ul class="goods_det_about goods_det clearfix border_top">
                    <li><a class="xq checked"><?= __('商品详情') ?></a></li>
                    <li><a class="pl"><?= __('商品评论') ?>
                            <span><?= __('(') ?><?= ($goods_detail['common_base']['common_evaluate']) ?><?= __(')') ?></span></a>
                    </li>
                    <?php if ($entity_shop) { ?>
                        <li><a class="wz"><?= __('商家位置') ?></a></li>
                    <?php } ?>
                    <li><a class="bz"><?= __('包装清单') ?></a></li>
                    <li><a class="sh"><?= __('售后保障') ?></a></li>
                    <li><a class="fx"><?= __('分销说明') ?></a></li>
                </ul>

                <ul class="goods_det_about_cont">

                    <!-- 商家位置 -->
                    <li class="wz_1 clearfix" style="display: none;">
                        <?php if ($entity_shop) { ?>
                            <div id="baidu_map" style="height:600px;width: 79%;border:1px solid gray"></div>
                            <div class="entity_shop">
                                <?php foreach ($entity_shop as $key => $value) { ?>
                                    <div class="entity_shop_box">
                                        <strong class="entity_shop_name"><?= $value['entity_name'] ?></strong>
                                        <?php if (in_array($value['province'], array("<?=__('北京市')?>", "<?=__('上海市')?>", "<?=__('天津市')?>", "<?=__('重庆市')?>", "<?=__('香港特别行政区')?>", "<?=__('澳门特别行政区')?>"))) { ?>
                                            <span class="entity_shop_address"><?= __("地址") ?>
                                                ：<?= $value['city'] ?><?= $value['entity_xxaddr'] ?></span>

                                        <?php } else { ?>
                                            <span class="entity_shop_address"><?= __("地址") ?>
                                                ：<?= $value['province'] ?><?= $value['city'] ?><?= $value['entity_xxaddr'] ?></span>
                                        <?php } ?>
                                        <span class="entity_shop_tel"><?= __("电话") ?>：<?= $value['entity_tel'] ?></span>
                                    </div>
                                <?php } ?>
                            </div>


                            <script type="text/javascript"
                                    src="https://api.map.baidu.com/api?v=2.0&ak=5At3anZe83x8oOpFap42Gt8eHYpy3wm9"></script>
                            <link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css" rel="stylesheet">
                            <script type="text/javascript">


                            </script>
                        <?php } ?>
                    </li>
                    <!--商品咨询-->
                    <div id="goodsadvisory" style="display:none;" class="ncs-commend-main zl_1"></div>
                    <!-- 商品评论 -->
                    <div id="goodseval" style="display:none;" class="ncs-commend-main pl_1"></div>
                    <!-- 详细-->
                    <li class="xq_1" style="display:block;    position: relative;">
                        <?php if (isset($goods_format_top) && !empty($goods_format_top)): ?>
                            <?= $goods_format_top['content']; ?>
                        <?php endif; ?>
                        <?= ($goods_detail['common_base']['common_detail']) ?>
                        <?php if (isset($goods_format_bottom) && !empty($goods_format_bottom)): ?>
                            <?= $goods_format_bottom['content']; ?>
                        <?php endif; ?>
                    </li>
                    <!-- 分销说明 -->
                    <li class="fx_1 tlf" style="display: none">
                        <div class="product-details">
                            <div>
                                <?= ($goods_detail['common_base']['common_distributor_description']) ?>
                            </div>
                        </div>
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

    </div>

    <!-- 登录遮罩层 -->
    <div id="login_content" style="display:none;">
    </div>

    <script>
        lazyload();
        //申请分销商
        $(".apply_dist").click(function () {
            var self_shop_type = <?=$shop_info['shop_type']?>;
            var shop_id = $(this).attr("data-id");
            if (self_shop_type == 2) {
                Public.tips.error("<?=__('供货商不能参与分销')?>！");
            } else {
                location.href = SITE_URL + "?ctl=Seller_Supplier_Supplier&met=apply&typ=e&shop_id=" + shop_id;
            }
        })

        //一键分销
        $('.add_distributor').click(function () {
            var common_id = $(this).attr('data-id');
            $.ajax({
                url: SITE_URL + '?ctl=Supplier_Goods&met=addto_distributor&typ=json',
                data: {common_id: common_id},
                dataType: "json",
                success: function (a) {
                    if (a.status == 200) {
                        Public.tips.success(a.msg);
                    }
                    else {
                        Public.tips.error(a.msg);
                    }
                },
            });
        })

    </script>


    <script type="text/javascript" charset="utf-8"
            src="http://static.bshare.cn/b/buttonLite.js#style=-1&amp;uuid=&amp;pophcol=2&amp;lang=zh"></script>
    <script type="text/javascript" charset="utf-8" src="http://static.bshare.cn/b/bshareC0.js"></script>
    <script>
        var goods_id = <?=($goods_detail['goods_base']['goods_id'])?>;
        var common_id = <?=($goods_detail['goods_base']['common_id'])?>;
        var shop_id = <?=($shop_detail['shop_id'])?>;

        function contains(arr, str) {//检测goods_id是否存入
            var i = arr.length;
            while (i--) {
                if (arr[i] == str) {
                    return true;
                }
            }
            return false;
        }


        $("#add_consult").bind("click", function () {
            if ($.cookie('key')) {
                $.dialog({
                    title: "<?=__('发起咨询')?>",
                    height: 290,
                    width: 380,
                    lock: true,
                    drag: false,
                    content: 'url: ' + SITE_URL + '?ctl=Buyer_Service_Consult&met=add&typ=e&gid=' + goods_id
                });
            }
            else {
                $("#login_content").show();
                load_goodseval(SITE_URL + '?ctl=Index&met=fastLogin', 'login_content');
            }
        });


        //加入购物车
        $(".tuan_join_cart").bind("click", function () {
            if (<?=$shop_owner?>) {
                Public.tips.warning('<?=__('不能购买自己商店的商品')?>！');
                //$.dialog.alert('不能购买自己商店的商品！');

                return false;
            }
            if (<?=$IsHaveBuy?>) {
                Public.tips.warning('<?=__('您已达购买上限')?>！');
                //$.dialog.alert('您达到购买上限！');

                return false;
            }

            goods_num = $("#nums").val();

            if ($.cookie('key')) {
                $.ajax({
                    url: SITE_URL + '?ctl=Buyer_Cart&met=addCart&typ=json',
                    data: {goods_id: goods_id, goods_num: goods_num},
                    dataType: "json",
                    contentType: "application/json;charset=utf-8",
                    async: false,
                    success: function (a) {
                        if (a.status == 250) {
                            Public.tips.error(a.msg);
                            //$.dialog.alert(a.msg);
                        }
                        else {
                            $.dialog({
                                title: "<?=__('加入购物车')?>",
                                height: 100,
                                width: 250,
                                lock: true,
                                drag: false,
                                content: 'url: ' + SITE_URL + '?ctl=Buyer_Cart&met=add&typ=e'
                            });
                        }
                    },
                    failure: function (a) {
                        Public.tips.error('<?=__('操作失败')?>！');
                        //$.dialog.alert("操作失败！");
                    }
                });
            }
            else {
                $("#login_content").show();
                load_goodseval(SITE_URL + '?ctl=Index&met=fastLogin', 'login_content');
            }
        });

        //立即购买虚拟商品
        $(".buy_now_virtual").bind("click", function () {
            if (<?=$shop_owner?>) {
                Public.tips.warning('<?=__('不能购买自己商店的商品')?>！');
                //$.dialog.alert('不能购买自己商店的商品！');

                return false;
            }
            if (<?=$IsHaveBuy?>) {
                //$.dialog.alert('您达到购买上限！');
                Public.tips.warning('<?=__('您已达购买上限')?>！');
                return false;
            }
            if ($.cookie('key')) {

                window.location.href = SITE_URL + '?ctl=Buyer_Cart&met=buyVirtual&goods_id=' + goods_id + '&goods_num=' + $("#nums").val();

            } else {
                $("#login_content").show();
                load_goodseval(SITE_URL + '?ctl=Index&met=fastLogin', 'login_content');
            }

        })

        //立即购买 - 实物商品
        $(".buy_now").bind("click", function () {
            if (<?=$shop_owner?>) {
                Public.tips.warning('<?=__('不能购买自己商店的商品')?>！');
                //$.dialog.alert('不能购买自己商店的商品！');

                return false;
            }
            if (<?=$IsHaveBuy?>) {
                //$.dialog.alert('您达到购买上限！');
                Public.tips.warning('<?=__('您已达购买上限')?>！');
                return false;
            }
            if ($.cookie('key')) {
                $.ajax({
                    url: SITE_URL + '?ctl=Buyer_Cart&met=addCart&typ=json',
                    data: {goods_id: goods_id, goods_num: $("#nums").val()},
                    dataType: "json",
                    contentType: "application/json;charset=utf-8",
                    async: false,
                    success: function (a) {
                        if (a.status == 250) {
                            $.dialog.alert(a.msg);
                        }
                        else {
                            if (a.data.cart_id) {
                                window.location.href = SITE_URL + '?ctl=Buyer_Cart&met=confirm&product_id=' + a.data.cart_id;
                            }

                        }
                    },
                    failure: function (a) {
                        Public.tips.error('<?=__('操作失败')?>！');
                        //$.dialog.alert("操作失败！");
                    }
                });
            } else {
                $("#login_content").show();
                load_goodseval(SITE_URL + '?ctl=Index&met=fastLogin', 'login_content');
            }

        })

        //门店自提
        $('a[nctype="get_chain"]').click(function () {

            var goodsQuantity = $('#nums').val();

            $.ajax({
                type: 'POST',
                url: "<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=chain&typ=json",
                data: {goods_id: goods_id, shop_id: shop_id, goods_num: goodsQuantity},
                dataType: 'JSON',
                success: function (data) {
                    if (data.status != 250) {
                        $.dialog({
                            title: '<?=__('查看门店')?>',
                            content: "url: <?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=chain&goods_id=" + goods_id + "&shop_id=" + shop_id,
                            data: {callback: callback},
                            width: 800,
                            lock: true
                        })

                        function callback(url) {
                            window.location.href = url;
                        }
                    }
                    else {
                        Public.tips.error(data.msg);
                    }
                }
            })

        });


        //热销商品，热收商品
        $(".selling").children().eq(0).hover(function () {
            $("#hot_salle").show();
            $("#hot_collect").hide();
        });
        $(".selling").children().eq(1).hover(function () {
            $("#hot_salle").hide();
            $("#hot_collect").show();
        });

        //收藏商品
        window.collectGoods = function (e) {
            if ($.cookie('key')) {
                $.post(SITE_URL + '?ctl=Goods_Goods&met=collectGoods&typ=json', {goods_id: e}, function (data) {
                    if (data.status == 200) {
                        Public.tips.success(data.data.msg);
                        $(".icon-icoheart").addClass("icon-taoxinshi").removeClass('icon-icoheart');
                        //$.dialog.alert(data.data.msg);
                    }
                    else {
                        Public.tips.error(data.data.msg);
                        //$.dialog.alert(data.data.msg);
                    }
                });
            }
            else {
                $("#login_content").show();
                load_goodseval(SITE_URL + '?ctl=Index&met=fastLogin', 'login_content');
            }

        }

        //收藏店铺
        window.collectShop = function (e) {
            if ($.cookie('key')) {
                $.post(SITE_URL + '?ctl=Shop&met=addCollectShop&typ=json', {shop_id: e}, function (data) {
                    if (data.status == 200) {
                        Public.tips.success(data.data.msg);
                        //$.dialog.alert(data.data.msg);
                    }
                    else {
                        Public.tips.error(data.data.msg);
                        //$.dialog.alert(data.data.msg);
                    }
                });
            }
            else {
                $("#login_content").show();
                load_goodseval(SITE_URL + '?ctl=Index&met=fastLogin', 'login_content');
            }
        }

        $("input[name='searchGoodsList']").blur(function () {
            var search = $("input[name='searchGoodsList']").val();
            if (search) {
                $("#searchGoodsList").attr('href', SITE_URL + '?ctl=Shop&met=goodsList&search=' + search + '&id=' + shop_id);
            }
        });
    </script>
    <script>
        $(document).ready(function () {
            url = 'index.php?ctl=Goods_Goods&met=getShopCat&shop_id=' + shop_id;
            $(".ser_lists").load(url, function () {
            });

            if ('<?=$_REQUEST['from']?>' == 'consult') {
                window.location.hash = "#elist";
                $(".zl").click();
            }
        })


        $('.share').click(function () {
            if ($('.bshare-custom').css('display') == 'block')
                $('.bshare-custom').hide();
            else
                $('.bshare-custom').show();


        });

        $('.wz').click(function () {
            $(".pl_1").css("display", "none");
            $(".zl_1").css("display", "none");
            $(".xs_1").css("display", "none");
            $(".wz_1").css("display", "block");
            $(".bz_1").css("display", "none");
            $(".sh_1").css("display", "none");
            $(".xq_1").css("display", "none");

            var map = new BMap.Map("baidu_map", {enableMapClick: false});
            var geo = new BMap.Geocoder();
            var city = new BMap.LocalCity();
            var top_left_navigation = new BMap.NavigationControl();
            var overView = new BMap.OverviewMapControl();
            var currentArea = '';//当前地图中心点的区域对象
            var currentCity = '';//当前地图中心点的所在城市
            var idArray = new Array();

            map.addControl(top_left_navigation);
            map.addControl(overView);
            map.enableScrollWheelZoom(true);
            city.get(local_city);

            function local_city(cityResult) {
                map.centerAndZoom(cityResult.center, 15);
                currentCity = cityResult.name;
                pointArray = new Array();
                var point = '';
                var marker = '';
                var label = '';
                var k = 0;
                <?php if($entity_shop){

                foreach ($entity_shop as $key => $value) {

                if($value['lng'] && $value['lat']){
                ?>
                point = new BMap.Point(<?=$value['lng']?>, <?=$value['lat']?>);
                pointArray[k++] = point;
                label = new BMap.Label("<?=$value['entity_name']?>", {offset: new BMap.Size(20, -10)});
                marker = new BMap.Marker(point);
                marker.setTitle('<?=__('地址')?>-' + k);
                marker.setLabel(label);
                marker.enableDragging();
                //                                    marker.addEventListener("dragend",getMarkerPoint);
                map.addOverlay(marker);
                idArray['<?=__('地址')?>-' + k] = <?=$value['entity_id']?>;

                <?php } } }?>

                map.setViewport(pointArray);
            }


            function getPointArea(point, callback) {//通过点找到地区
                geo.getLocation(point, function (rs) {
                    var addComp = rs.addressComponents;
                    if (addComp.province != '') callback(addComp);
                }, {numPois: 1});
            }
        });
    </script>

    <!--  地址选择 -->
    <script>
        var $cur_area_list, $cur_tab, next_tab_id = 0, cur_select_area = [], calc_area_id = '', calced_area = [],
            calced_area_transport = [], cur_select_area_ids = [];


        <?php if($goods_detail['goods_base']['goods_stock']){?>
        $(document).ready(function () {

            if ($.cookie('areaId')) {
                //获取该地区的名字
                $.post(SITE_URL + '?ctl=Base_District&met=getDistrictNameList&id=' + $.cookie('areaId') + '&typ=json', function (data) {
                    $("#ncs-freight-selector .text div").html(data.data.area);

                    $.post(SITE_URL + '?ctl=Goods_Goods&met=getTramsport&area_id=' + data.data.city.id + '&common_id=' + <?=($goods_detail['common_base']['common_id'])?> +'&typ=json', function (data) {

                        <?php if ($goods_detail['common_base']['transport_type_id']){ ?>
                        if (data.status === 250) {
                            $('.goods_have').html('<?=__('无货')?>');
                            $('.transport').html('');
                            $('a[nctype="buynow_submit"]').addClass('no-buynow');
                            $('a[nctype="addcart_submit"]').addClass('no-buynow');
                            $('.buy_box').hide();
                        } else {
                            $('.goods_have').html('<?=__('有货')?> ');
                            $('.transport').html(data.data.transport_str);
                            $('a[nctype="buynow_submit"]').removeClass('no-buynow');
                            $('a[nctype="addcart_submit"]').removeClass('no-buynow');
                            $('.buy_box').show();
                        }
                        <?php }?>

                    });
                });
            }


            $("#ncs-freight-selector").hover(function () {
                //如果店铺没有设置默认显示区域，马上异步请求

                if (typeof nc_a === "undefined") {
                    $.post(SITE_URL + '?ctl=Base_District&met=getAllDistrict&typ=json', function (data) {
                            nc_a = data.data;
                            $cur_tab = $('#ncs-stock').find('li[data-index="0"]');
                            _loadArea(0);
                        }
                    );
                }

                $(this).addClass("hover");
                $(this).on('mouseleave', function () {
                    $(this).removeClass("hover");
                });
            });

            $('ul[class="area-list"]').on('click', 'a', function () {
                $('#ncs-freight-selector').unbind('mouseleave');
                var tab_id = parseInt($(this).parents('div[data-widget="tab-content"]:first').attr('data-area'));
                if (tab_id == 0) {
                    cur_select_area = [];
                    cur_select_area_ids = []
                }
                ;
                if (tab_id == 1 && cur_select_area.length > 1) {
                    cur_select_area.pop();
                    cur_select_area_ids.pop();
                    if (cur_select_area.length > 1) {
                        cur_select_area.pop();
                        cur_select_area_ids.pop();
                    }
                }
                next_tab_id = tab_id + 1;
                var area_id = $(this).attr('data-value');
                if (tab_id == 0) {
                    $.cookie('areaId', area_id)
                }
                $cur_tab = $('#ncs-stock').find('li[data-index="' + tab_id + '"]');
                $cur_tab.find('em').html($(this).html());
                $cur_tab.find('em').attr('data_value', $(this).attr('data-value'));
                $cur_tab.find('i').html(' ∨');
                if (tab_id < 2) {
                    cur_select_area.push($(this).html());
                    cur_select_area_ids.push(area_id);
                    $cur_tab.find('a').removeClass('hover');
                    $cur_tab.nextAll().remove();
                    if (typeof nc_a === "undefined") {
                        $.post(SITE_URL + '?ctl=Base_District&met=getAllDistrict&typ=json', function (data) {
                            nc_a = data.data;
                            _loadArea(area_id);
                        })
                    } else {
                        _loadArea(area_id);
                    }
                } else {
                    //点击第三级，不需要显示子分类
                    if (cur_select_area.length == 3) {
                        cur_select_area.pop();
                        cur_select_area_ids.pop();
                    }
                    cur_select_area.push($(this).html());
                    cur_select_area_ids.push(area_id);
                    $('#ncs-freight-selector > div[class="text"] > div').html(cur_select_area.join(''));
                    $('#ncs-freight-selector').removeClass("hover");
                    _calc();
                }
                $('#ncs-stock').find('li[data-widget="tab-item"]').on('click', 'a', function () {
                    var tab_id = parseInt($(this).parent().attr('data-index'));
                    if (tab_id < 2) {
                        $(this).parent().nextAll().remove();
                        $(this).addClass('hover');
                        $('#ncs-stock').find('div[data-widget="tab-content"]').each(function () {
                            if ($(this).attr("data-area") == tab_id) {
                                $(this).show();
                            } else {
                                $(this).hide();
                            }
                        });
                    }
                });
            });

            function _loadArea(area_id) {
                if (nc_a[area_id] && nc_a[area_id].length > 0) {
                    $('#ncs-stock').find('div[data-widget="tab-content"]').each(function () {
                        if ($(this).attr("data-area") == next_tab_id) {
                            $(this).show();
                            $cur_area_list = $(this).find('ul');
                            $cur_area_list.html('');
                        } else {
                            $(this).hide();
                        }
                    });
                    var areas = [];
                    areas = nc_a[area_id];
                    for (i = 0; i < nc_a[area_id].length; i++) {
                        $cur_area_list.append("<li><a data-value='" + nc_a[area_id][i]['district_id'] + "' >" + nc_a[area_id][i]['district_name'] + "</a></li>");
                    }
                    if (area_id > 0) {
                        $cur_tab.after('<li data-index="' + (next_tab_id) + '" data-widget="tab-item"><a class="hover"  ><em><?=__('请选择')?></em><i> ∨</i></a></li>');
                    }
                } else {
                    //点击第一二级时，已经到了最后一级
                    $cur_tab.find('a').addClass('hover');
                    $('#ncs-freight-selector > div[class="text"] > div').html(cur_select_area);
                    $('#ncs-freight-selector').removeClass("hover");
                    _calc();
                }
            }

            //计算运费，是否配送
            function _calc() {
                <?php if ($goods_detail['common_base']['transport_type_id']){ ?>
                var _args = '';
                calc_area_id = $('li[data-index="1"]').find("em").attr("data_value");
                if (typeof calced_area[calc_area_id] == 'undefined') {
                    //需要请求配送区域设置
                    $.post(SITE_URL + '?ctl=Goods_Goods&met=getTramsport&area_id=' + calc_area_id + '&common_id=' + <?=($goods_detail['common_base']['common_id'])?> +'&typ=json', function (data) {

                        calced_area[calc_area_id] = data.msg;
                        calced_area_transport[calc_area_id] = data.data.transport_str;
                        if (data.status === 250) {
                            $('.goods_have').html('<?=__('无货')?>');
                            $('.transport').html('');
                            $('a[nctype="buynow_submit"]').addClass('no-buynow');
                            $('a[nctype="addcart_submit"]').addClass('no-buynow');
                            $('.buy_box').hide();
                        } else {
                            $('.goods_have').html('<?=__('有货')?> ');
                            $('.transport').html(data.data.transport_str);
                            $('a[nctype="buynow_submit"]').removeClass('no-buynow');
                            $('a[nctype="addcart_submit"]').removeClass('no-buynow');
                            $('.buy_box').show();
                        }
                    });

                } else {
                    if (calced_area[calc_area_id] === 'failure') {
                        $('.goods_have').html('<?=__('无货')?>');
                        $('.transport').html('');
                        $('a[nctype="buynow_submit"]').addClass('no-buynow');
                        $('a[nctype="addcart_submit"]').addClass('no-buynow');
                        $('#store-free-time').hide();
                    } else {
                        $('.goods_have').html('<?=__('有货')?> ');
                        $('.transport').html(calced_area_transport[calc_area_id]);
                        $('a[nctype="buynow_submit"]').removeClass('no-buynow');
                        $('a[nctype="addcart_submit"]').removeClass('no-buynow');
                        $('#store-free-time').show();
                    }
                }

                <?php }?>
            }
        });
        <?php }?>

        function consult() {
            window.location.href = window.location.href + "&from=consult";
        }

    </script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>

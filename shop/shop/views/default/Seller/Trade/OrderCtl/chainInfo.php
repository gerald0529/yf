<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<link href="<?= $this->view->css ?>/seller_center.css?ver=<?= VER ?>" rel="stylesheet">
<link href="<?= $this->view->css ?>/base.css?ver=<?= VER ?>" rel="stylesheet">
<link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css?ver=<?=VER?>" rel="stylesheet">
<script src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js" charset="utf-8"></script>
<style>
    .ncsc-order-condition {
        width: 55%;
    }

</style>
</head>
<body>

<div id="mainContent">

    <div class="ncsc-oredr-show">
        <div class="ncsc-order-info">
            <div class="ncsc-order-details">
                <div class="title"><?=__('订单信息')?></div>
                <div class="content">
                    <dl>
                        <dt><?=__('收货人')?>：</dt>
                        <dd><?= $data['receiver_info']; ?></dd>
                    </dl>
                    <dl>
                        <dt><?=__('支付方式')?>：</dt>
                         <?php if($val['order_status'] == Order_StateModel::ORDER_WAIT_PREPARE_GOODS  && $val['payment_id'] == PaymentChannlModel::PAY_CHAINPYA ){?>
                             <dd><?=__('门店付款')?></dd>
                         <?php } else {?>
                             <dd><?=__('在线付款')?></dd>
                        <?php }?>
                    </dl>
                    <dl>
                        <dt><?=__('买家留言')?>：</dt>
                        <dd><?= $data['order_message']; ?></dd>
                    </dl>
                    <dl class="line">
                        <dt><?=__('订单编号')?>：</dt>
                        <dd><?= $data['order_id']; ?><a href="javascript:void(0);"><?=__('更多')?><i class="iconfont icon-iconjiantouxia"></i>
                                <div class="more"><span class="arrow"></span>
                                    <ul>
                                        <li><span><?= $data['order_create_time']; ?></span><?=__('买家下单')?></li>
                                        <li><span><?= $data['order_create_time']; ?></span><?=__('买家 生成订单')?></li>
                                    </ul>
                                </div>
                            </a></dd>
                    </dl>
                    <dl>
                        <dt></dt>
                        <dd></dd>
                    </dl>
                </div>
            </div>
            <div class="ncsc-order-details chain-absolute-center" style="width: 30%;padding-bottom: 3%;">
                <div class="title"><?=__('门店信息')?></div>
                <div class="content">
                    <dl>
                        <dt><?=__('门店名称')?>：</dt>
                        <dd><?= $chain_data['chain_name']; ?></dd>
                    </dl>
                    <dl>
                        <dt><?=__('联系电话')?>：</dt>
                        <dd><?= $chain_data['chain_mobile']; ?></dd>
                    </dl>
                    <dl>
                        <dt><?=__('详细地址')?>：</dt>
                        <dd><?= $chain_data['chain_province'].'&nbsp;&nbsp;'.$chain_data['chain_city'].'&nbsp;&nbsp;'.$chain_data['chain_county'].'&nbsp;&nbsp;'.$chain_data['chain_address']; ?></dd>
                    </dl>
                </div>
            </div>
            <div class="ncsc-order-condition chain-absolute-right" style="width: 34%;">
                <dl>
                    <dt><i class="icon-ok-circle green"></i><?=__('订单状态')?>：</dt>
                    <dd><?= $data['order_status_text']; ?></dd>
                </dl>
                <ul class="order_state"><?= $data['order_status_html']; ?></ul>
            </div>
        </div>
        <?php if ($data['order_status'] != Order_StateModel::ORDER_CANCEL) { ?>
            <div id="order-step" class="ncsc-order-step" style="text-align: center;">
                <dl class="step-first current">
                    <dt><?=__('提交订单')?></dt>
                    <dd class="bg"></dd>
                    <dd class="date" title="<?=__('下单时间')?>"><?= $data['order_create_time']; ?></dd>
                </dl>
                <dl class="<?= $data['order_received']; ?>">
                    <dt><?=__('门店自提')?></dt>
                    <dd class="bg"> </dd>
                    <dd class="date" title="<?=__('完成时间')?>"><?= $data['order_finished_time']; ?></dd>
                </dl>
                <dl class="<?= $data['order_evaluate']; ?>">
                    <dt><?=__('评价')?></dt>
                    <dd class="bg"></dd>
                    <dd class="date" title="<?=__('评价时间')?>"><?= $data['order_buyer_evaluation_time']; ?></dd>
                </dl>
            </div>
        <?php } ?>
        <div class="ncsc-order-contnet">
            <table class="ncsc-default-table order">
                <thead>
                <tr>
                    <th class="w10">&nbsp;</th>
                    <th colspan="2"><?=__('商品')?></th>
                    <th class="w120"><?=__('单价')?><!--(<?/*=Web_ConfigModel::value('monetary_unit')*/?>)--></th>
                    <th class="w60"><?=__('数量')?></th>
                    <th class="w100"><?=__('优惠活动')?></th>
                    <th class="w200"><strong><?=__('实付')?> * <?=__('佣金比')?> = <?=__('应付佣金')?>(<?=Web_ConfigModel::value('monetary_unit')?>)</strong></th>
                     <th class="w100"><?=__('交易状态')?></th>
                    <th class="w100"><?=__('状态与操作')?></th>
                </tr>
                </thead>
                <tbody>
                <?php if ( !empty($data['goods_list']) ) { ?>
                    <?php foreach ( $data['goods_list'] as $key => $val ) { ?>
                        <tr class="bd-line">
                            <td>&nbsp;</td>
                            <td class="w50">
                                <div class="pic-thumb">
                                    <a target="_blank" href="<?= $val['goods_link']; ?>">
                                        <img src="<?= $val['goods_image']; ?>">
                                    </a>
                                </div>
                            </td>
                            <td class="tl">
                                <dl class="goods-name">
                                    <dt>
                                        <a target="_blank" href="<?= $val['goods_link']; ?>"><?= $val['goods_name']; ?></a>
                                        <a target="_blank" href="<?= $val['goods_link']; ?>" class="blue ml5"><?=__('[交易快照]')?></a>
                                    </dt>
                                    <!--<dd><?/*= $val['spec_name']; */?></dd>-->
                                </dl>
                            </td>
                            <td><?= format_money($val['goods_price'])
                                ; ?><p class="green"></p></td>
                            <td><?= $val['order_goods_num']; ?></td>
                            <td>
                                <?php foreach($data['order_benefits'] as $v){ ?>
                                    <p><?=($v)?></p>
                                <?php } ?>
                            </td>
                            <td class="commis bdl bdr"><?=$val['order_goods_commission']?></td>

                            <!-- S 合并TD -->
                            <?php if ( $key == 0 ) { ?>
                                <td class="bdl bdr" rowspan="<?= $data['goods_cat_num']; ?>"><?= $data['order_stauts_const']; ?></td>
                            <?php } ?>
                            <!-- E 合并TD -->
                             <td class="bdl bdr" rowspan="1"></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                <!-- S 赠品列表 -->
                <!-- E 赠品列表 -->

                </tbody>
                <tfoot>
                <tr>
                    <td colspan="20" class="pt-10 pb-10 tfoot-pad">
                        <div class="fl">
                             <?php if($data['voucher_price'] > 0):?>
                                <dl>
                                    <dd><?=__('店铺代金券：')?><?=__('减￥')?><?=$data['voucher_price']?></dd>
                                </dl>
                            <?php endif;?>
                            <?php if($data['order_rpt_price'] > 0):?>
                                <dl>
                                    <dd><?=__('平台红包：')?><?=__('减￥')?><?=$data['order_rpt_price']?></dd>
                                </dl>
                            <?php endif;?>
                            <?php if($data['order_user_discount'] > 0):?>
                                <dl>
                                    <dd><?=__('会员折扣：')?><?=__('减￥')?><?=$data['order_user_discount']?></dd>
                                </dl>
                            <?php endif;?>
                        </div>
                        <div class="fr">
                           
                            <?php if($data['redpacket_code']){ ?>
                            <dl class="tr">
                                <dt></dt>
                                <dd><?=__('红包抵扣')?><?= format_money($data['order_rpt_price']); ?></dd>
                            </dl>
                            <?php } ?>
                            <dl class="sum">
                                <dt><?=__('订单金额')?>：</dt>
                                <dd><em class="bbc_seller_color"><?= format_money($data['order_payment_amount']); ?></em></dd>
                            </dl>
                        </div>
                        </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <script type="text/javascript">

    </script>
</div>


<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

<script>
    $('.tabmenu > ul').find('li:lt(6)').remove();
//    $($('.tabmenu > ul')[0]).find('li:lt(6)').remove();
    var href = window.location.href; ;
    $('.tabmenu > ul > li > a').attr('href',href);
</script>
<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>

<?php
    include $this -> view -> getTplPath() . '/' . 'seller_header.php';
?>
<link href="<?= $this -> view -> css ?>/seller_center.css?ver=<?= VER ?>" rel="stylesheet">
<link href="<?= $this -> view -> css ?>/base.css?ver=<?= VER ?>" rel="stylesheet">
<link href="<?= $this -> view -> css_com ?>/jquery/plugins/dialog/green.css?ver=<?= VER ?>" rel="stylesheet">
<script src="<?= $this -> view -> js_com ?>/plugins/jquery.dialog.js" charset="utf-8"></script>
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
                <div class="title"><?= __('订单信息') ?></div>
                <div class="content">
                    <!--<dl>
                        <dt><?= __('收&nbsp;&nbsp;货&nbsp;&nbsp;人') ?>：</dt>
                        <dd><?= $data['receiver_info']; ?></dd>
                    </dl>-->
                    <dl>
                        <dt><?= __('收&nbsp;&nbsp;货&nbsp;&nbsp;人') ?>：</dt>
                        <dd class="one-overflow line-consignee" style="max-width: 30%;"><?= ($data['order_receiver_name']) ?></dd>
                        <dd class="line-phone" style="margin-left: 30px;"><?= ($data['order_receiver_contact']) ?></dd>
                    </dl>
                    <dl>
                        <dt><?= __('收货地址：') ?></dt>
                        <dd><?= ($data['order_receiver_address']) ?></dd>
                    </dl>
                    <dl>
                        <dt><?= __('支付方式') ?>：</dt>
                        <dd> <?= $data['payment_name']; ?> </dd>
                    </dl>
                    <dl>
                        <dt><?= __('发&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;票') ?>：</dt>
                        <dd> <?= $data['order_invoice']; ?> </dd>
                    </dl>
                    <dl>
                        <dt><?= __('买家留言') ?>：</dt>
                        <dd><?= $data['order_message']; ?></dd>
                    </dl>
                    <dl class="line">
                        <dt><?= __('订单编号') ?>：</dt>
                        <dd><?= $data['order_id']; ?><a href="javascript:void(0);"><?= __('更多') ?><i class="iconfont icon-iconjiantouxia"></i>
                                <div class="more"><span class="arrow"></span>
                                    <ul>
                                        <li><span><?= $data['order_create_time']; ?></span><?= __('买家下单') ?></li>
                                        <li><span><?= $data['order_create_time']; ?></span><?= __('买家 生成订单') ?></li>
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
            <div class="ncsc-order-condition">
                <dl>
                    <dt><i class="icon-ok-circle green"></i><?= __('订单状态') ?>：</dt>
                    <dd><?= $data['order_status_text']; ?></dd>
                </dl>
                <ul class="order_state"><?= $data['order_status_html']; ?></ul>
            </div>
        </div>
        <?php if ($data['order_status'] != Order_StateModel::ORDER_CANCEL) { ?>
            <div id="order-step" class="ncsc-order-step" style="text-align: center;">
                <dl class="step-first current">
                    <dt><?= __('提交订单') ?></dt>
                    <dd class="bg"></dd>
                    <dd class="date" title="<?= __('下单时间') ?>"><?= $data['order_create_time']; ?></dd>
                </dl>
                <?php if ($data['payment_id'] != PaymentChannlModel::PAY_CONFIRM): ?>
                    <dl class="<?= $data['order_payed']; ?>">
                        <dt><?= __('支付订单') ?></dt>
                        <dd class="bg"></dd>
                        <dd class="date" title="<?= __('付款时间') ?>"><?= $data['payment_time']; ?></dd>
                    </dl>
                <?php endif; ?>
                <dl class="<?= $data['order_wait_confirm_goods']; ?>">
                    <dt><?= __('商家发货') ?></dt>
                    <dd class="bg"></dd>
                    <dd class="date" title="<?= __('发货时间') ?>"><?= $data['order_shipping_time']; ?></dd>
                </dl>
                <dl class="<?= $data['order_received']; ?>">
                    <dt><?= __('确认收货') ?></dt>
                    <dd class="bg"></dd>
                    <dd class="date" title="<?= __('完成时间') ?>"><?= $data['order_finished_time']; ?></dd>
                </dl>
                <dl class="<?= $data['order_evaluate']; ?>">
                    <dt><?= __('评价') ?></dt>
                    <dd class="bg"></dd>
                    <dd class="date" title="<?= __('评价时间') ?>"><?= $data['order_buyer_evaluation_time']; ?></dd>
                </dl>
            </div>
        <?php } ?>
        <div class="ncsc-order-contnet">
            <table class="ncsc-default-table order">
                <!--表头-->
                <thead>
                <tr>
                    <th colspan="2"><?= __('商品') ?></th>
                    <th class="w120"><?= __('优惠单价') ?><!--(<? /*=Web_ConfigModel::value('monetary_unit')*/ ?>)--></th>
                    <th class="w60"><?= __('数量') ?></th>
                    <th class="w200"><strong><?= __('实付') ?> * <?= __('佣金比') ?> = <?= __('应付佣金') ?>(<?= Web_ConfigModel ::value('monetary_unit') ?>)</strong></th>
                    <th class="w200"><?= __('优惠活动') ?></th>
                    <th class="w100"><?= __('操作') ?></th>
                </tr>
                </thead>
                <!--表内容-->
                <tbody>
                <?php if (!empty($data['goods_list'])) { ?>
                    <?php foreach ($data['goods_list'] as $key => $val) { ?>
                        <tr class="bd-line">
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
                                        <a target="_blank" href="<?= $val['goods_link']; ?>" class="blue ml5"><?= __('[交易快照]') ?></a>
                                    </dt>
                                </dl>
                            </td>
                            <td>
                                <?= format_money($val['order_goods_payment_amount']); ?>
                                <p class="green"></p>
                            </td>
                            <td><?= $val['order_goods_num']; ?></td>
                            <!-- S 合并TD -->
                            <?php if ($key == 0) { ?>
                                <td class="bdl bdr" rowspan="<?= $data['goods_cat_num']; ?>">
                                    <?= format_money($data['order_commission_fee']);?>
                                </td>
                                <td class="bdl bdr" rowspan="<?= $data['goods_cat_num']; ?>">
                                    <?php foreach ($data['order_benefits'] as $v) { ?>
                                        <p><?= ($v) ?></p>
                                    <?php } ?>
                                </td>
                                <td class="bdl bdr" rowspan="<?= $data['goods_cat_num']; ?>">
                                    <?= $data['order_stauts_const']; ?>
                                    <?php if ($data['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS) { ?>
                                        <p>
                                            <a style="position:relative;" onmouseover="show_logistic('<?= ($data['order_id']) ?>','<?= ($data['order_shipping_express_id']) ?>','<?= ($data['order_shipping_code']) ?>')" onmouseout="hide_logistic('<?= ($data['order_id']) ?>')"> <i class="iconfont icon-icowaitproduct rel_top2"></i><?= __('物流信息') ?>
                                                <div style="display: none;" id="info_<?= ($data['order_id']) ?>" class="prompt-01"></div>
                                            </a>
                                        </p>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                            <!-- E 合并TD -->
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="20" class="tfoot-pad">
                            <div class="fr">
                                    <dl class="freight tr">
                                        <dd><?= $data['shipping_info']; ?></dd>
                                    </dl>
                                    <?php if ($data['redpacket_code']) { ?>
                                        <dl class="tr">
                                            <dt></dt>
                                            <dd><?= __('红包抵扣') ?><?= format_money($data['order_rpt_price']); ?></dd>
                                        </dl>
                                    <?php } ?>
                                    <dl class="sum">
                                        <dt><?= __('订单金额') ?>：</dt>
                                        <dd><em class="bbc_seller_color"><?= format_money($data['order_payment_amount']); ?></em></dd>
                                        <?php if ($data['order_status'] == Order_StateModel::ORDER_WAIT_PAY) { ?>
                                            <a onclick="edit_cost('<?= ($data['order_id']) ?>')" class="ncbtn-mini bbc_seller_btns"><?= __('修改金额') ?></a>
                                        <?php } ?>
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


<?php
    include $this -> view -> getTplPath() . '/' . 'seller_footer.php';
?>

<script>
    $(".tabmenu > ul").find("li:eq(8)").remove();
    $(".tabmenu > ul").find("li:lt(7)").remove();
    var href = window.location.href;
    ;
    $(".tabmenu > ul > li > a").attr("href", href);
    /*$($('.tabmenu > ul')[0]).find('li:lt(6)').remove();*/
    
    
    window.edit_cost = function (e) {
        url = SITE_URL + "?ctl=Seller_Trade_Order&met=cost&typ=e&order_id=" + e;
        
        $.dialog({
            title: '<?=__('修改订单金额')?>',
            content: "url: " + url,
            height: 340,
            width: 580,
            lock: true,
            drag: false
            
        });
    };
    
    window.hide_logistic = function (order_id) {
        $("#info_" + order_id).hide();
        $("#info_" + order_id).html("");
    };
    
    window.show_logistic = function (order_id, express_id, shipping_code) {
        $("#info_" + order_id).show();
        $.post(BASE_URL + "/shop/api/logistic.php", {"order_id": order_id, "express_id": express_id, "shipping_code": shipping_code}, function (da) {
            
            if (da) {
                $("#info_" + order_id).html(da);
            }
            else {
                $("#info_" + order_id).html('<div class="error_msg"><?=__('接口出现异常')?></div>');
            }
            
        });
    };
</script>

<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<link href="<?= $this->view->css ?>/seller_center.css?ver=<?= VER ?>" rel="stylesheet">

<style>

    a.ncbtn-mini, a.ncbtn {
        font: normal 12px/20px "microsoft yahei", arial;
        color: #FFF;
        background-color: #CCD0D9;
        text-align: center;
        vertical-align: middle;
        display: inline-block;
        height: 20px;
        padding: 5px 10px;
        border-radius: 3px;
        cursor: pointer;
    }

    a.ncbtn-mini {
        line-height: 16px;
        height: 16px;
        padding: 3px 7px;
        border-radius: 2px;
    }

    .ncsc-order-condition {
        width: 536px;
    }

    .ncsc-order-step dl.long {
        width: 385px;
    }

</style>

<script src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js" charset="utf-8"></script>
<link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css?ver=<?=VER?>" rel="stylesheet">

<div class="ncsc-oredr-show">
    <div class="ncsc-order-info">
        <div class="ncsc-order-details">
            <div class="title"><?=__('虚拟订单信息')?></div>
            <div class="content">
                <dl>
                    <dt><?=__('虚拟单号')?>：</dt>
                    <dd><?php echo $order_data['order_id']; ?>
                        <a href="javascript:void(0);"><?=__('更多')?><i class="iconfont icon-iconjiantouxia"></i>
                            <div class="more"><span class="arrow"></span>
                                <ul>
                                    <li><?=__('支付方式')?>：<?php echo $order_data['payment_name']; ?></li>
                                    <li><?=__('下单时间')?>：<span><?php echo $order_data['order_create_time']; ?></span></li>
                                    <!--<li>付款时间：<span><?php /*echo date('Y-m-d H:i:s', $order_data['payment_time']); */ ?></span></li>-->
                                </ul>
                            </div>
                        </a></dd>
                </dl>
                <dl class="line">
                    <dt><?=__('买　　家')?>：</dt>
                    <dd><?php echo $order_data['buyer_user_name']; ?></dd>
                </dl>
                <dl class="line">
                    <dt><?=__('接收手机')?>：</dt>
                    <dd><?php echo $order_data['order_receiver_contact']; ?></dd>
                </dl>
                <dl class="line">
                    <dt><?=__('买家留言')?>：</dt>
                    <dd><?php echo $order_data['order_message']; ?></dd>
                </dl>
            </div>
        </div>
        <div class="ncsc-order-condition">
            <!--<dl>
                <dt><i class="icon-ok-circle green"></i>订单状态：</dt>
                <dd><?php /*echo $order_data['order_stauts_text']; */?></dd>
            </dl>-->
        </div>
    </div>
    <div class="ncsc-order-step" style="text-align: center;">
        <dl class="step-first current">
            <dt><?=__('生成订单')?></dt>
            <dd class="bg"></dd>
            <dd class="date" title="<?=__('订单生成时间')?>"><?php echo $order_data['order_create_time']; ?></dd>
        </dl>
        <dl class="">
            <dt><?=__('完成付款')?></dt>
            <dd class="bg"></dd>
            <dd class="date" title="<?=__('付款时间')?>"><?php echo $order_data['payment_time']; ?></dd>
        </dl>
        <dl class="">
            <dt><?=__('发放兑换码')?></dt>
            <dd class="bg"></dd>
            <dd class="date" title="<?=__('发放兑换码时间')?>"><?php echo $order_data['payment_time']; ?></dd>
        </dl>
        <dl class="long">
            <dt><?=__('订单完成')?></dt>
            <dd class="bg" style="background-position: -210px -210px;width: 389px;"></dd>
            <dd class="date" title="<?=__('订单完成')?>"><?php echo $order_data['order_finished_time']; ?></dd>
        </dl>
    </div>
    <div class="ncsc-order-contnet">
        <table class="ncsc-default-table order">
            <thead>
            <tr>
                <th class="w10"></th>
                <th colspan="2"><?=__('商品')?></th>
                <th class="w100"><?=__('单价')?> <!--(<?/*=Web_ConfigModel::value('monetary_unit')*/?>)-->.</th>
                <th class="w60"><?=__('数量')?></th>
                <th class="w240"><strong><?=__('实付')?> * <?=__('佣金比')?> = <?=__('应付佣金')?>(<?=Web_ConfigModel::value('monetary_unit')?>)</strong></th>
                <th class="w100"><?=__('交易状态')?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th colspan="20">
                    <span class="ml10" title="<?=__('我的订单')?>虚拟订单号"><?=__('虚拟订单号')?>：<?php echo $order_data['order_id']; ?></span><span><?=__('下单时间')?>：<?php echo $order_data['order_create_time']; ?></span>
                </th>
            </tr>
            <tr>
                <td class="bdl"></td>
                <td>
                    <div class="pic-thumb">
                        <a target="_blank" href="<?php echo Yf_Registry::get('url') . '?ctl=Goods_Goods&met=goods&type=goods&gid=' . $goods_list['goods_id']; ?>" target="_blank"><img width="60px" height="60px" src="<?php echo $goods_list['goods_image']; ?>"></a>
                    </div>
                </td>
                <td class="tl">
                    <dl class="goods-name">
                        <dt>
                            <a target="_blank" href="<?php echo Yf_Registry::get('url') . '?ctl=Goods_Goods&met=goods&type=goods&gid=' . $goods_list['goods_id']; ?>" target="_blank" style="color: #666;" title="<?php echo $goods_list['goods_name']; ?>"><?php echo $goods_list['goods_name']; ?></a>
                            <a target="_blank" href="<?php echo Yf_Registry::get('url') . '?ctl=Goods_Goods&met=goods&type=goods&gid=' . $goods_list['goods_id']; ?>" class="blue ml5"><?=__('[交易快照]')?></a>
                        </dt>
                        <dd>
                            <?=__('使用时效')?>：<?=__('即日起')?> <?=__('至')?> <?php echo $order_data['common_virtual_date']; ?>
                        </dd>
                        <!-- S消费者保障服务 -->

                        <!-- E消费者保障服务 -->
                    </dl>
                </td>
                <td><?= @format_money($goods_list['goods_price']) ?></td>
                <td><?= $goods_list['order_goods_num'] ?></td>
                <td class="commis bdl bdr"><?= @format_money($goods_list['order_goods_commission'])?></td>
                <td class="bdl border-right1"><?php echo $order_data['order_stauts_const']; ?></td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="20" class="transportation tl">
                    <dl class="sum">
                        <dt><?=__('订单金额')?>：</dt>
                        <dd><em class="bbc_seller_color"><?php echo @format_money($order_data['order_payment_amount']); ?></em></dd>
                    </dl>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

<script>

    $('.tabmenu > ul').find('li:lt(7)').remove();
    var href = window.location.href; ;
    $('.tabmenu > ul > li > a').attr('href',href);
//    $($('.tabmenu > ul')[0]).find('li:lt(6)').remove();

    var order_info, ncsc_order_condition = $('.ncsc-order-condition');


    <?php if ( $order_data['order_status'] == 1 ) { //待付款订单 ?>

    order_info = '<dl>' +
                        '<dt><i class="icon-ok-circle green"></i><?=__('订单状态')?>：</dt>' +
                        '<dd><?=__('订单已经生成，等待买家付款')?></dd>' +
                '</dl>';

    order_info += '<ul>' +
                        '<li>1. <?=__('买家尚未对该订单进行支付')?>。</li>' +
                        '<li>2. <?=__('如果该订单是一个无效订单')?>，<?=__('您可以点击')?> <a href="javascript:void(0)" class="ncbtn-mini" id="order_action_cancel"><?=__('取消订单')?></a>。 </li>' +
                        '<li>3. <?=__('如果买家未对该笔订单进行支付')?>，<?=__('系统将于')?><time><?php echo date( 'Y-m-d H:i:s', strtotime("+7 days", time($order_data['order_create_time']) ) ); ?></time><?=__('自动关闭该订单')?>。</li>' +
                  '</ul>';

    order_info = $(order_info);

    var order_id = $(this).data('order_id'),
        url = SITE_URL + '?ctl=Seller_Trade_Order&met=orderCancel&typ=';

    order_info.find('#order_action_cancel').on('click', function () {

        $.dialog({
            title: '<?=__('取消订单')?>',
            content: 'url: ' + url + 'e',
            data: { order_id: "<?php echo $order_data['order_id']; ?>" },
            height: 250,
            width: 400,
            lock: true,
            drag: false,
            ok: function () {
                var form_ser = $(this.content.order_cancel_form).serialize();
                $.post(url + 'json', form_ser, function (data) {
                    if ( data.status == 200 ) {
                        parent.Public.tips({
                            content: '<?=__('修改成功')?>',
                            type: 3
                        }), window.location.reload();
                        return true;
                    } else {
                        parent.Public.tips({
                            content: '<?=__('修改失败')?>',
                            type: 1
                        });
                        return false;
                    }
                })
            }
        })
    });

    <?php } elseif ( $order_data['order_status'] == 4 ) { //待配货订单 ?>

    order_info = '<dl>' +
                    '<dt><i class="icon-ok-circle green"></i><?=__('订单状态')?>：</dt>' +
                    '<dd><?=__('买家已付款，电子兑换码已发放')?></dd>' +
                 '</dl>';

    order_info += '<ul>' +
                      '<li>1. <?=__('该笔订单的电子兑换码已由系统自动发送至买家接收')?>。</li>' +
                      '<li>2. <?=__('本次交易从即日起至')?><time><?php echo $order_data["common_virtual_date"]; ?></time>，<?=__('逾期自动失效')?>。</li>' +
                  '</ul>';

    $('.ncsc-order-step').children(':lt(3)').addClass('current');
    $('#codeList').children("ul").append('<?php foreach($code_data as $v){echo '<li class=""><strong>'.$v["virtual_code_id"].'</strong>'."<?=__('未使用，有效期至')?>".' '.$order_data['common_virtual_date'].'</li>';}?>');

    <?php } elseif ( $order_data['order_status'] == 6 ) { //已完成订单 ?>

    order_info = '<dl>' +
                    '<dt><i class="icon-ok-circle green"></i><?=__('订单状态')?>：</dt>' +
                    '<dd><?=__('订单完成')?>。</dd>' +
                 '</dl>';

    $('.ncsc-order-step').children().addClass('current');
    $('#codeList').children("ul").append('<?php foreach($code_data as $v){echo '<li class=""><strong>'.$v["virtual_code_id"].'</strong>'."<?=__('已使用')?>".'，</li>';}?>');

    <?php } elseif ( $order_data['order_status'] == 7 ) { //已取消 ?>

    $('.ncsc-order-step').remove();

    order_info = '<dl>' +
                    '<dt><i class="icon-off orange"></i><?=__('订单状态')?>：</dt>' +
                    '<dd><?=__('交易关闭')?></dd>' +
                 '</dl>';

    order_info += "<ul><li><?= $order_data['order_cancel_date']; ?> <?=__('交易关闭，原因')?>：<?php echo $order_data['order_cancel_reason']; ?></li></ul>";

    <?php } ?>

    ncsc_order_condition.append(order_info);

</script>
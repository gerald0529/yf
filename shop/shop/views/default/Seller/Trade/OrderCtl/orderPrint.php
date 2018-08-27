<!DOCTYPE html PUBLIC “-//W3C//DTD XHTML 1.0 Transitional//EN” “http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd”><!-- saved from url=(0046)http://demo.bbc-builder.com/seller.php?m=order -->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?=__('订单打印')?></title>
    <link rel="stylesheet" href="<?= $this->view->css ?>/base.css?ver=<?= VER ?>" />
    <link href="<?= $this->view->css ?>/seller_center.css?ver=<?= VER ?>" rel="stylesheet">
    <link href="<?= $this->view->css ?>/iconfont/iconfont.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/jquery.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.poshytip.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.printarea.js" charset="utf-8"></script>
</head>
<body>
<div class="print-layout">
    <div class="print-btn" id="printbtn" title="<?=__('选择喷墨或激光打印机<br/>根据下列纸张描述进行<br/>设置并打印发货单据')?>"><i></i><a href="javascript:void(0);"><?=__('打印')?></a></div>
    <div class="a5-size"></div>
    <dl class="a5-tip">
        <dt>
        <h1>A5</h1>
        <em>Size: 210mm x 148mm</em></dt>
        <dd><?=__('当打印设置选择A5纸张、横向打印、无边距时每张A5打印纸可输出1页订单')?>。</dd>
    </dl>
    <div class="a4-size"></div>
    <dl class="a4-tip">
        <dt>
        <h1>A4</h1>
        <em>Size: 210mm x 297mm</em></dt>
        <dd><?=__('当打印设置选择A4纸张、竖向打印、无边距时每张A4打印纸可输出2页订单')?>。</dd>
    </dl>
    <div class="print-page">
        <div id="printarea">
            <div class="orderprint">
                <div class="top">
                    <div class="full-title"><?= $data['shop_name']; ?> <?=__('发货单')?></div>
                </div>
                <table class="buyer-info">
                    <tbody><tr>
                        <td class="w200"><?=__('收货人')?>：<?= $data['buyer_user_name']; ?></td>
                        <td><?=__('电话')?>：<?= $data['order_receiver_contact']; ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3"><?=__('地址')?>：<?= $data['order_receiver_address']; ?></td>
                    </tr>
                    <tr>
                        <td><?=__('订单号')?>：<?= $data['order_id']; ?></td>
                        <td><?=__('下单时间')?>：<?= $data['order_create_time']; ?></td>
                        <td></td>
                    </tr>
                    </tbody></table>
                <table class="order-info">
                    <thead>
                    <tr>
                        <th class="w40"><?=__('序号')?></th>
                        <th class="tl"><?=__('商品名称')?></th>
                        <th class="w100 tl"><?=__('单价(元)')?></th>
                        <th class="w100"><?=__('数量')?></th>
                        <th class="w100 tl"><?=__('小计(元)')?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ( !empty($data['goods_list']) ) { ?>
                    <?php foreach ($data['goods_list'] as $key => $val) { ?>
                    <tr>
                        <td class="tc"><?= $key; ?></td>
                        <td class="tl"><?= $val['goods_name']; ?></td>
                        <td class="tc">¥<?= sprintf("%.2f",$val['goods_price']); ?></td>
                        <td class="tc"><?= $val['order_goods_num_c']; ?></td>
                        <td class="tc">¥<?= sprintf("%.2f",$val['order_goods_amount_c']); ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <th></th>
                        <th colspan="2" class="tl"><?=__('合计')?></th>
                        <th><?= $data['goods_count']; ?></th>
                        <th class="tl">¥<?= sprintf("%.2f",$data['order_goods_amount_c']); ?></th>
                    </tr>
                    <?php } ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="10"><span><?=__('订单总额')?>：¥<?= sprintf("%.2f",$data['order_goods_amount_c']); ?></span><span><?=__('运费')?>：¥<?= $data['order_shipping_fee']; ?></span><span><?=__('优惠')?>：¥<?= sprintf("%.2f",$data['order_discount_fee_c']); ?></span><span><?=__('总计')?>：<??>¥<?= sprintf("%.2f",$data['order_payment_amount_c']); ?></span><span><?=__('店铺')?>：<?= $data['shop_name']; ?></span>
                        </th>
                    </tr>
                    </tfoot>
                </table>
                <div class="explain clearfix pt-10 pb-10">
                    <?php if ( !empty($shop_stamp) ) { ?>
                        <img src="<?= $shop_stamp = $shop_base['shop_stamp']; ?> alt="<?=__('店铺印章')?>" />
                    <?php } else { ?>
                        <b style="color: red"><?=__('请设置店铺印章后，再打印单据')?></b>
                    <?php } ?>
                    <span><?= $shop_print_desc ?></span>
                </div>
                <div class="tc page"><?=__('第1页/共1页')?></div>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    $(function(){
        $("#printbtn").click(function(){
            $("#printarea").printArea();
        });
    });

    //打印提示
    $('#printbtn').poshytip({
        className: 'tip-yellowsimple',
        showTimeout: 1,
        alignTo: 'target',
        alignX: 'center',
        alignY: 'bottom',
        offsetY: 5,
        allowTipHover: false
    });
</script>
</html>
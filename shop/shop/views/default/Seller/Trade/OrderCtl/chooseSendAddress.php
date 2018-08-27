<!DOCTYPE HTML>
<html>
<head>
    <link href="<?= $this->view->css ?>/seller.css?ver=<?= VER ?>" rel="stylesheet">
    <link href="<?= $this->view->css ?>/iconfont/iconfont.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
    <link href="<?= $this->view->css ?>/seller_center.css?ver=<?= VER ?>" rel="stylesheet">
    <link href="<?= $this->view->css ?>/base.css?ver=<?= VER ?>" rel="stylesheet">
    <script type="text/javascript" src="<?=$this->view->js_com?>/jquery.js" charset="utf-8"></script>
</head>
<body>

<div class="dialog_content" style="margin: 0px; padding: 0px;">
    <div class="eject_con">
        <div class="adds" style=" min-height:240px;">
            <table class="ncsc-default-table wp100">
                <thead>
                <tr>
                    <th class="w80"><?=__('发货人')?></th>
                    <th><?=__('发货地址')?></th>
                    <th class="w100"><?=__('电话')?></th>
                    <th class="w100"><?=__('操作')?></th>
                </tr>
                </thead>

                <tbody>
                <?php if ( !empty($address_list) ) { ?>
                <?php foreach ( $address_list as $key => $val ) { ?>
                <tr class="bd-line">
                    <td class="tc"><?= $val['shipping_address_contact']; ?></td>
                    <td class="tc"><?= $val['address_info']; ?></td>
                    <td class="tc"><?= $val['shipping_address_phone']; ?></td>
                    <td class="tc"><a href="javascript:void(0);" nc_type="select" class="ncbtn bbc_seller_btns margint4" address_id="<?= $val['shipping_address_id']; ?>" address_value="<?= $val['address_value']; ?>"><?=__('选择')?></a></td>
                </tr>
                <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>

<script>

    api = frameElement.api;
    callback = api.data.callback;

    $(function () {

        $('a[nc_type="select"]').on('click', function () {

            if ( typeof callback == 'function' ) {
                var send_address = {};
                    this_tr = $($(this).parents('tr'));

                send_address.order_seller_name = this_tr.children(':eq(0)').html();
                send_address.order_seller_address = this_tr.children(':eq(1)').html();
                send_address.order_seller_contact = this_tr.children(':eq(2)').html();
                send_address.seller_address_span = $(this).attr('address_value');
                callback(send_address, window);
            }
        })
    })
</script>
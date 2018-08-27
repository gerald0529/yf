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

<div class="dialog_content" style="margin: 0px; padding: 0px;"><div class="eject_con">
        <div id="warning"></div>

        <form id="changeform" method="post" action="index.php?act=store_deliver&amp;op=delay_receive&amp;order_id=193">
            <input type="hidden" name="form_submit" value="ok">
            <dl>
                <dt><?=__('订单号')?>：</dt>
                <dd><span class="num" id="order_id"></span></dd>
            </dl>
            <dl>
                <dt><?=__('买家')?>：</dt>
                <dd id="buyer_name"></dd>
            </dl>
            <dl>
                <dt><?=__('最晚收货时间')?>：</dt>
                <dd><span id="order_receiver_date"></span><br><?=__('如果超过该时间买家未点击收货')?>，<?=__('系统将自动更改为收货状态')?> </dd>
            </dl>
            <dl>
                <dt><?=__('延迟')?>：</dt>
                <dd>
                    <select name="delay_date">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                    </select> <?=__('天')?>
                </dd>
            </dl>
        </form>
    </div>
</div>

</body>
</html>

<script>
    api = frameElement.api,
    data = api.data;

    $(function () {
console.info(data);
        $('#order_id').html( data.order_id );
        $('#buyer_name').html( data.buyer_name );
        $('#order_receiver_date').html( data.order_receiver_date );

    })
</script>
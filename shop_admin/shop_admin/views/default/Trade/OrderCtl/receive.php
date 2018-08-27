<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<style>
body{background: #fff;}
.manage-wrap{margin: 20px auto 10px;width: 600px;}
.manage-wrap .ui-input{width: 185px;font-size:12px;}
.row-item{float:left ; width:50%;}
.mod-form-rows .label-wrap {font-size: 12px;}
span.msg-wrap.n-error {
    margin-left: -75px;
    top: 3px;
}
</style>
</head>
<body>

<div id="manage-wrap" class="manage-wrap">
    <form id="manage-form" action="#">
        <ul class="mod-form-rows">
            <li class="row-item">
                <div class="label-wrap"><label for="order_id">订单编号:</label></div>
                    <div class="ctn-wrap"><input disabled type="text" value="" class="ui-input" name="order_id" id="order_id"></div>
            </li>
            <li class="row-item">
                <div class="label-wrap"><label for="payment_number">支付单号:</label></div>
                <div class="ctn-wrap"><input disabled type="text" value="" class="ui-input" name="payment_number" id="payment_number"></div>
            </li>
            <li class="row-item">
                <div class="label-wrap"><label for="order_payment_amount">订单金额:</label></div>
                <div class="ctn-wrap"><input disabled type="text" value="" class="ui-input" name="order_payment_amount" id="order_payment_amount"></div>
            </li>
            <li class="row-item">
                <div class="label-wrap"><label for="payment_date">付款时间:</label></div>
                <div class="ctn-wrap"> <input readonly="readonly" type="text" value="" class="ui-input" name="payment_date" id="payment_date"></div>
                <!--<span class="msg-box" for="payment_date" style="margin-left: 76px;"></span>-->
            </li>
            <li class="row-item">
                <div class="label-wrap"><label for="payment_code">付款方式:</label></div>
                <div class="ctn-wrap"><span id="payment_code"></div><span class="msg-box" for="payment_code"></span>
            </li>
            <li class="row-item">
                <div class="label-wrap"><label for="payment_other_number">第三方支付平台交易号:</label></div>
                <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="payment_other_number" id="payment_other_number"></div>
                <!--<span class="msg-box" for="payment_other_number"></span>-->
            </li>
        </ul>
    </form>
</div>


<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>

<script>

    var api = frameElement.api;
        data = api.data,
        callback = data.callback;

    $(function () {

        $('#order_id').val(data.order_id);
        $('#payment_number').val(data.payment_number);
        $('#order_payment_amount').val(data.order_goods_amount);
        $('#payment_date').datepicker({dateFormat: 'yy-mm-dd', format:'Y-m-d', timepicker:false});

        var payment_code = $("#payment_code").combo({
            data: [{
                id: 'alipay',
                name: "支付宝"
            }, {
                id: 'tenpay',
                name: "财付通"
            }, {
                id: 'chinabank',
                name: "网银在线"
            }, {
                id: 'wxpay',
                name: "微信支付"
            }],
            value: "id",
            text: "name",
            width: 197,
        }).getCombo();

        //验证
        $('#manage-form').validator({
            timely: true,

            fields: {
                'payment_date':             'required;',
                'payment_other_number':     'required;'
            },

            valid: function () {

            }
        });

        api.button({
            id: "confirm",
            name: '确定',
            focus: true,
            callback: function() {

                $("#manage-form").trigger("validate");

                if ( $("#manage-form").validate().isValid ) {

                    var param = {
                        order_id: data.order_id,
                        payment_number: data.payment_number,
                        payment_date: $('#payment_date').val(),
                        payment_name: payment_code.getText(),
                        payment_other_number: $('#payment_other_number').val()
                    };

                    Public.ajaxPost(SITE_URL + '?ctl=Trade_Order&met=receivePay&typ=json', param, function ( data ){
                        if ( data.status == 200 ) {
                            parent.Public.tips({type: 3, content : data.msg});
                            parent.window.THISPAGE.reloadData(), api.close();
                        } else {
                            parent.Public.tips({type: 1, content : data.msg});
                        }
                    })
                } else {
                    $('#manage-form').trigger("validate");
                }

                return false;
            }
        }, {
            id: "cancel",
            name: '取消'
        });
    })
</script>

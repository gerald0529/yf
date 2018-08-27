<!DOCTYPE HTML>
<html>
<head>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/jquery.js" charset="utf-8"></script>
    <style>
        .eject_con {
            background-color: #FFF;
            overflow: hidden;
            font: 12px/20px "Hiragino Sans GB", "Microsoft Yahei", arial, 宋体, "Helvetica Neue", Helvetica, STHeiTi, sans-serif;
        }

        .eject_con dl {
            line-height: 20px;
            display: block;
            clear: both;
            overflow: hidden;
        }

        .title {
            text-align: center;
            color: #FF892A;
        }

        .eject_con dl dd {
            font-size: 12px;
            vertical-align: top;
            letter-spacing: normal;
            word-spacing: normal;
            display: inline-block;
            width: 69%;
            padding: 0 76px;
            zoom: 1;
            margin-left: 0px;
            color: #BBBBC5;
        }

        .eject_con dl dt {
            font-size: 12px;
            line-height: 32px;
            vertical-align: top;
            letter-spacing: normal;
            word-spacing: normal;
            text-align: right;
            display: inline-block;
            width: 100%;
            padding: 10px 1% 10px 0;
            margin: 0;
            zoom: 1;
            text-align: center;
        }

        .eject_con span.num {
            font-weight: 600;
            color: #390;
        }
    </style>
</head>
<body>

<div class="dialog_content" style="margin: 0px; padding: 0px;">
    <div class="eject_con">
        <div id="warning"></div>
        <form method="post" id="order_confirm_form"
              onsubmit="ajaxpost('order_confirm_form', '', '', 'onerror');return false;">
            <input type="hidden" name="order_id" value="">
            <dl class="title">
                <?= __('您是否确已收到以下订单的货品？') ?>
            </dl>
            <dl style="text-align: center;">
                <dt><?= __('订单编号：') ?><span class="num"></span></dt>
            </dl>
            <dl>
                <dd>
                    <?= __('请注意，如果您尚未收到货品请不要点击“确认”，大部分被骗案件都是由于提前确认收货时被骗的，请谨慎操作！') ?>
                </dd>
            </dl>
        </form>
    </div>

</div>

</body>
</html>

<script>
    api = frameElement.api;
    order_id = api.data.order_id;

    $(function () {

        $('span.num').html(order_id);
        $('input[name="order_id"]').val(order_id);

    })
</script>
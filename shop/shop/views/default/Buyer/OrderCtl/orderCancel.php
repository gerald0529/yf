<!DOCTYPE HTML>
<html>
<head>
<script type="text/javascript" src="<?=$this->view->js_com?>/jquery.js" charset="utf-8"></script>
<style>
    .eject_con {
        background-color: #FFF;
        overflow: hidden;
        font: 12px/20px "Hiragino Sans GB","Microsoft Yahei",arial,宋体,"Helvetica Neue",Helvetica,STHeiTi,sans-serif;
    }

    .eject_con dl {
        font-size: 0;
        line-height: 20px;
        display: block;
        clear: both;
        overflow: hidden;
    }

    .eject_con dl dd {
        font-size: 12px;
        line-height: 32px;
        vertical-align: top;
        letter-spacing: normal;
        word-spacing: normal;
        display: inline-block;
        width: 69%;
        padding: 10px 0 10px 0;
        zoom: 1;
        margin-left: 0px;
    }

    .eject_con dl dt {
        font-size: 12px;
        line-height: 32px;
        vertical-align: top;
        letter-spacing: normal;
        word-spacing: normal;
        text-align: right;
        display: inline-block;
        width: 29%;
        padding: 10px 1% 10px 0;
        margin: 0;
        zoom: 1;
    }

    .eject_con span.num {
        font-weight: 600;
        color: #390;
    }

    .eject_con .checked {
        float: left;
        padding: 0;
        margin: 0;
    }

    .eject_con ul {
        overflow: hidden;
    }

    .eject_con .checked li {
        line-height: 16px;
        height: 16px;
        padding: 4px 0;
    }

    input[type="radio"], .radio {
        /*vertical-align: middle;*/
        display: inline-block;
        margin-right: 5px;
    }

    textarea, .textarea {
        font: 12px/18px Arial;
        color: #777;
        background-color: #FFF;
        vertical-align: top;
        display: inline-block;
        height: 40px;
        padding: 4px;
        border: solid 1px #CCD0D9;
        outline: 0 none;
    }
</style>
</head>
<body>

<div class="dialog_content" style="margin: 0px; padding: 0px;">
    <div class="eject_con">
        <div id="warning"></div>
        <form method="post" id="order_cancel_form" onsubmit="ajaxpost('order_cancel_form', '', '', 'onerror');return false;" action="index.php?act=store_vr_order&amp;op=change_state&amp;state_type=order_cancel&amp;order_id=29">
            <input type="hidden" name="order_id" value="">
            <dl>
                <dt><?=__('订单编号：')?></dt>
                <dd><span class="num"></span></dd>
            </dl>
            <dl>
                <dt><?=__('取消缘由：')?></dt>
                <dd>
                    <ul class="checked">
                        <?php foreach($reason as $key=>$val):?>
                            <li>
                                <input type="radio" <?php if($key == 0):?>checked="" <?php endif;?> name="state_info" value="<?=($val['cancel_reason_content'])?>">
                                <label for="d1"><?=($val['cancel_reason_content'])?></label>
                            </li>
                        <?php endforeach;?>
                        <li>
                            <input type="radio" name="state_info" flag="other_reason" id="d4" value="">
                            <label for="d4"><?=__('其他原因')?></label>
                        </li>
                        <li id="other_reason" style="height: 48px; display: list-item; display: none">
                            <textarea name="state_info1" rows="2" id="other_reason_input" style="width:200px;"></textarea>
                        </li>
                    </ul>
                </dd>
            </dl>
        </form>
    </div>

</div>

</body>
</html>

<script>
    api = frameElement.api;
    order_id = api.data.order_id ;

    $(function () {

        $('span.num').html(order_id);
        $('input[name="order_id"]').val(order_id);

        $('input[type="radio"]').on('click', function () {

            if ( this.id == 'd4' ) {
                $('#other_reason').show();
            } else {
                $('#other_reason').hide();
            }
        })

    })
</script>
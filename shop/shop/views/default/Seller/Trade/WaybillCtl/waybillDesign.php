<?php if (!defined('ROOT_PATH')) exit('No Permission'); ?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<style>
    .add-on {
        height: 28px;
    }

    .ncsc-form-checkbox-list {
        font-size: 0;
    }

    .ncsc-form-checkbox-list li {
        font-size: 12px;
        vertical-align: top;
        letter-spacing: normal;
        word-spacing: normal;
        display: inline-block;
        margin-right: 30px;
    }

    .ncsc-form-checkbox-list li {
        width: 20%;
        margin: 0;
    }

    .waybill_area {
        margin: 10px auto auto 136px;
        width: 820.797px;
        height: 528.188px;
        position: relative;
        z-index: 1;
    }

    .waybill_back {
        position: relative;
        width: 467.4px;
       /* height: 467.4px;*/
    }

    .waybill_design {
        position: absolute;
        left: 0;
        top: 0;
        width: 467.4px;
        /*height: 467.4px;*/
    }

    .waybill_item {
        background-color: #FEF5E6;
        position: absolute;
        left: 0;
        top: 0;
        width: 90px;
        height: 20px;
        padding: 1px 5px 4px 5px;
        border-color: #FFBEBC;
        border-style: solid;
        border-width: 1px 1px 1px 1px;
        cursor: move;
    }

    div.bottom {
        text-align: center;
        margin-top: 50px;
    }

    .bottom .submit-border {
        margin: 10px auto;
    }

    .submit-border {
        vertical-align: middle;
        display: inline-block;
    }

    .bottom .submit {
        font: 14px/36px "microsoft yahei";
        text-align: center;
        min-width: 100px;
        height: 36px;
    }

    /*input[type="submit"], input.submit, a.submit {
        font-size: 12px;
        line-height: 30px;
        font-weight: bold;
        color: #FFF;
        background-color: #48CFAE;
        display: block;
        height: 30px;
        padding: 0 20px;
        border-radius: 3px;
        border: none 0;
        cursor: pointer;
    }*/

    .ncsc-form-checkbox-list li input[type="checkbox"], .ncsc-form-checkbox-list li .checkbox {
        vertical-align: middle;
        margin-right: 4px;
    }

    .iconfont {
        vertical-align: middle;
    }
</style>

<div class="alert alert-block mt10">
    <ul class="mt5">
        <li>1、<?=__('勾选需要打印的项目，勾选后可以用鼠标拖动确定项目的位置、宽度和高度，也可以点击项目后边的微调按钮手工录入')?></li>
        <li>2、<?=__('设置完成后点击提交按钮完成设计')?></li>
    </ul>
</div>

<div class="form-style form-style_reset">
    <form method="post" id="form" class="nice-validator n-yellow" novalidate="novalidate">
        <dl>
            <dt><i class="required">*</i><?=__('显示项目')?>：</dt>
            <dd>
                <ul id="waybill_item_list" class="ncsc-form-checkbox-list">
                    <li>
                        <input id="check_buyer_name" class="checkbox" type="checkbox" name="waybill_data[buyer_name][check]" data-waybill-name="buyer_name" data-waybill-text="<?=__('收货人')?>">
                        <label for="check_buyer_name" class="label"><?=__('收货人')?></label>
                        <i nctype="btn_item_edit" data-item-name="buyer_name" title="<?=__('微调')?>" class="iconfont icon-edit"></i>
                        <input id="left_buyer_name" type="hidden" name="waybill_data[buyer_name][left]" value="613">
                        <input id="top_buyer_name" type="hidden" name="waybill_data[buyer_name][top]" value="21">
                        <input id="width_buyer_name" type="hidden" name="waybill_data[buyer_name][width]" value="100">
                        <input id="height_buyer_name" type="hidden" name="waybill_data[buyer_name][height]" value="20">
                        <input id="" type="hidden" name="waybill_data[buyer_name][name]" value="<?=__('收货人')?>">
                    </li>
                    <li>
                        <input id="check_buyer_area" class="checkbox" type="checkbox" name="waybill_data[buyer_area][check]" data-waybill-name="buyer_area" data-waybill-text="<?=__('收货人地区')?>">
                        <label for="check_buyer_area" class="label"><?=__('收货人地区')?></label>
                        <i nctype="btn_item_edit" data-item-name="buyer_area" title="<?=__('微调')?>" class="iconfont icon-edit"></i>
                        <input id="left_buyer_area" type="hidden" name="waybill_data[buyer_area][left]" value="180">
                        <input id="top_buyer_area" type="hidden" name="waybill_data[buyer_area][top]" value="172">
                        <input id="width_buyer_area" type="hidden" name="waybill_data[buyer_area][width]" value="100">
                        <input id="height_buyer_area" type="hidden" name="waybill_data[buyer_area][height]" value="20">
                        <input id="" type="hidden" name="waybill_data[buyer_area][name]" value="<?=__('收货人地区')?>">
                    </li>
                    <li>
                        <input id="check_buyer_address" class="checkbox" type="checkbox" name="waybill_data[buyer_address][check]" data-waybill-name="buyer_address" data-waybill-text="<?=__('收货人地址')?>">
                        <label for="check_buyer_address" class="label"><?=__('收货人地址')?></label>
                        <i nctype="btn_item_edit" data-item-name="buyer_address" title="<?=__('微调')?>" class="iconfont icon-edit"></i>
                        <input id="left_buyer_address" type="hidden" name="waybill_data[buyer_address][left]" value="0">
                        <input id="top_buyer_address" type="hidden" name="waybill_data[buyer_address][top]" value="0">
                        <input id="width_buyer_address" type="hidden" name="waybill_data[buyer_address][width]" value="0">
                        <input id="height_buyer_address" type="hidden" name="waybill_data[buyer_address][height]" value="0">
                        <input id="" type="hidden" name="waybill_data[buyer_address][name]" value="<?=__('收货人地址')?>">
                    </li>
                    <li>
                        <input id="check_buyer_mobile" class="checkbox" type="checkbox" name="waybill_data[buyer_mobile][check]" data-waybill-name="buyer_mobile" data-waybill-text="<?=__('收货人手机')?>">
                        <label for="check_buyer_mobile" class="label"><?=__('收货人手机')?></label>
                        <i nctype="btn_item_edit" data-item-name="buyer_mobile" title="<?=__('微调')?>" class="iconfont icon-edit"></i>
                        <input id="left_buyer_mobile" type="hidden" name="waybill_data[buyer_mobile][left]" value="0">
                        <input id="top_buyer_mobile" type="hidden" name="waybill_data[buyer_mobile][top]" value="0">
                        <input id="width_buyer_mobile" type="hidden" name="waybill_data[buyer_mobile][width]" value="0">
                        <input id="height_buyer_mobile" type="hidden" name="waybill_data[buyer_mobile][height]" value="0">
                        <input id="" type="hidden" name="waybill_data[buyer_mobile][name]" value="<?=__('收货人手机')?>">
                    </li>
                    <li>
                        <input id="check_buyer_phone" class="checkbox" type="checkbox" name="waybill_data[buyer_phone][check]" data-waybill-name="buyer_phone" data-waybill-text="<?=__('收货人电话')?>">
                        <label for="check_buyer_phone" class="label"><?=__('收货人电话')?></label>
                        <i nctype="btn_item_edit" data-item-name="buyer_phone" title="<?=__('微调')?>" class="iconfont icon-edit"></i>
                        <input id="left_buyer_phone" type="hidden" name="waybill_data[buyer_phone][left]" value="0">
                        <input id="top_buyer_phone" type="hidden" name="waybill_data[buyer_phone][top]" value="0">
                        <input id="width_buyer_phone" type="hidden" name="waybill_data[buyer_phone][width]" value="0">
                        <input id="height_buyer_phone" type="hidden" name="waybill_data[buyer_phone][height]" value="0">
                        <input id="" type="hidden" name="waybill_data[buyer_phone][name]" value="<?=__('收货人电话')?>">
                    </li>
                    <li>
                        <input id="check_seller_name" class="checkbox" type="checkbox" name="waybill_data[seller_name][check]" data-waybill-name="seller_name" data-waybill-text="<?=__('发货人')?>">
                        <label for="check_seller_name" class="label"><?=__('发货人')?></label>
                        <i nctype="btn_item_edit" data-item-name="seller_name" title="<?=__('微调')?>" class="iconfont icon-edit"></i>
                        <input id="left_seller_name" type="hidden" name="waybill_data[seller_name][left]" value="-222">
                        <input id="top_seller_name" type="hidden" name="waybill_data[seller_name][top]" value="223">
                        <input id="width_seller_name" type="hidden" name="waybill_data[seller_name][width]" value="100">
                        <input id="height_seller_name" type="hidden" name="waybill_data[seller_name][height]" value="20">
                        <input id="" type="hidden" name="waybill_data[seller_name][name]" value="<?=__('发货人')?>">
                    </li>
                    <li>
                        <input id="check_seller_area" class="checkbox" type="checkbox" name="waybill_data[seller_area][check]" data-waybill-name="seller_area" data-waybill-text="<?=__('发货人地区')?>">
                        <label for="check_seller_area" class="label"><?=__('发货人地区')?></label>
                        <i nctype="btn_item_edit" data-item-name="seller_area" title="<?=__('微调')?>" class="iconfont icon-edit"></i>
                        <input id="left_seller_area" type="hidden" name="waybill_data[seller_area][left]" value="227">
                        <input id="top_seller_area" type="hidden" name="waybill_data[seller_area][top]" value="276">
                        <input id="width_seller_area" type="hidden" name="waybill_data[seller_area][width]" value="91">
                        <input id="height_seller_area" type="hidden" name="waybill_data[seller_area][height]" value="19">
                        <input id="" type="hidden" name="waybill_data[seller_area][name]" value="<?=__('发货人地区')?>">
                    </li>
                    <li>
                        <input id="check_seller_address" class="checkbox" type="checkbox" name="waybill_data[seller_address][check]" data-waybill-name="seller_address" data-waybill-text="<?=__('发货人地址')?>">
                        <label for="check_seller_address" class="label"><?=__('发货人地址')?></label>
                        <i nctype="btn_item_edit" data-item-name="seller_address" title="<?=__('微调')?>" class="iconfont icon-edit"></i>
                        <input id="left_seller_address" type="hidden" name="waybill_data[seller_address][left]" value="0">
                        <input id="top_seller_address" type="hidden" name="waybill_data[seller_address][top]" value="0">
                        <input id="width_seller_address" type="hidden" name="waybill_data[seller_address][width]" value="0">
                        <input id="height_seller_address" type="hidden" name="waybill_data[seller_address][height]" value="0">
                        <input id="" type="hidden" name="waybill_data[seller_address][name]" value="<?=__('发货人地址')?>">
                    </li>
                    <li>
                        <input id="check_seller_phone" class="checkbox" type="checkbox" name="waybill_data[seller_phone][check]" data-waybill-name="seller_phone" data-waybill-text="<?=__('发货人电话')?>">
                        <label for="check_seller_phone" class="label"><?=__('发货人电话')?></label>
                        <i nctype="btn_item_edit" data-item-name="seller_phone" title="<?=__('微调')?>" class="iconfont icon-edit"></i>
                        <input id="left_seller_phone" type="hidden" name="waybill_data[seller_phone][left]" value="0">
                        <input id="top_seller_phone" type="hidden" name="waybill_data[seller_phone][top]" value="0">
                        <input id="width_seller_phone" type="hidden" name="waybill_data[seller_phone][width]" value="0">
                        <input id="height_seller_phone" type="hidden" name="waybill_data[seller_phone][height]" value="0">
                        <input id="" type="hidden" name="waybill_data[seller_phone][name]" value="<?=__('发货人电话')?>">
                    </li>
                    <li>
                        <input id="check_seller_company" class="checkbox" type="checkbox" name="waybill_data[seller_company][check]" data-waybill-name="seller_company" data-waybill-text="<?=__('发货人公司')?>">
                        <label for="check_seller_company" class="label"><?=__('发货人公司')?></label>
                        <i nctype="btn_item_edit" data-item-name="seller_company" title="<?=__('微调')?>" class="iconfont icon-edit"></i>
                        <input id="left_seller_company" type="hidden" name="waybill_data[seller_company][left]" value="0">
                        <input id="top_seller_company" type="hidden" name="waybill_data[seller_company][top]" value="0">
                        <input id="width_seller_company" type="hidden" name="waybill_data[seller_company][width]" value="0">
                        <input id="height_seller_company" type="hidden" name="waybill_data[seller_company][height]" value="0">
                        <input id="" type="hidden" name="waybill_data[seller_company][name]" value="<?=__('发货人公司')?>">
                    </li>
                </ul>
                <p class="hint"><?=__('选中需要打印的项目，未勾选的将不会被打印')?></p>
            </dd>
        </dl>
        <dl>
            <dt><?=__('打印项偏移校正')?>：</dt>
        </dl>
        <div>
            <div class="waybill_area">
                <div class="waybill_back"> <img style="max-width:800px;" src="<?php echo $waybill_data['waybill_tpl_image']; ?>" alt=""><div class="waybill_design"></div> </div>
                
            </div>
        </div>
        <div class="bottom">
            <label class="submit-border">
                <input id="submit" type="button" class="submit bbc_seller_btns" value="<?=__('提交')?>">
                <input id="submit" name="waybill_tpl_id" type="hidden" value="<?php echo $waybill_tpl_id; ?>">
            </label>
        </div>
    </form>
</div>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

<script>

    $('.tabmenu').children().children('li:gt(1)').hide();
    $('.tabmenu').children().children('.active').show();

    $(function () {

        var draggable_event = {
            stop: function(event, ui) {
                var item_name = ui.helper.attr('data-item-name');
                var position = ui.helper.position();
                $('#left_' + item_name).val(position.left);
                $('#top_' + item_name).val(position.top);
            }
        };

        var resizeable_event = {
            stop: function(event, ui) {
                var item_name = ui.helper.attr('data-item-name');
                $('#width_' + item_name).val(ui.size.width);
                $('#height_' + item_name).val(ui.size.height);
            }
        };

        $('#waybill_item_list input:checkbox').on('click', function() {
            var item_name = $(this).attr('data-waybill-name');
            var div_name = 'div_' + item_name;
            if($(this).prop('checked')) {
                var item_text = $(this).attr('data-waybill-text');
                var waybill_item = '<div id="' + div_name + '" data-item-name="' + item_name + '" class="waybill_item">' + item_text + '</div>';
                $('.waybill_design').append(waybill_item);
                $('#' + div_name).draggable(draggable_event);
                $('#' + div_name).resizable(resizeable_event);
                $('#left_' + item_name).val('0');
                $('#top_' + item_name).val('0');
                $('#width_' + item_name).val('100');
                $('#height_' + item_name).val('20');
            } else {
                $('#' + div_name).remove();
            }
        });

        $('.waybill_design').on('click', '.waybill_item', function() {
            console.log($(this).position());
        });

        $('#submit').on('click', function () {
            $.post(SITE_URL + '?ctl=Seller_Trade_Waybill&met=designTpl&typ=json', $('#form').serialize(), function (data){
                if( data.status == 200 ) {
                    Public.tips({ content: "<?=__('修改成功')?>", type: 3 });
                    setTimeout(function () {
                        window.location.href = SITE_URL + '?ctl=Seller_Trade_Waybill&met=waybillIndex&typ=e';
                    })
                } else {
                    Public.tips({ content: "<?=__('修改失败')?>", type: 1 });
                }
            })
        })


        //初始化
        <?php if ( !empty($waybill_data['waybill_tpl_item']) ) { ?>
        <?php foreach ($waybill_data['waybill_tpl_item'] as $key => $val) { ?>

            var _this = check_<?php echo $key; ?>;
            _this.checked = true;

            var item_name = $(_this).attr('data-waybill-name');
            var div_name = 'div_' + item_name;
            var item_text = $(_this).attr('data-waybill-text');
            var style = 'position: absolute; width: <?php echo $val['width']; ?>px; height: <?php echo $val['height']; ?>px; left: <?php echo $val['left']; ?>px; top: <?php echo $val['top']; ?>px;';

            var waybill_item = '<div id="' + div_name + '" data-item-name="' + item_name + '" class="waybill_item" style="' + style + '">' + item_text + '</div>';
            $('.waybill_design').append(waybill_item);
            $('#' + div_name).draggable(draggable_event);
            $('#' + div_name).resizable(resizeable_event);
            $('#left_' + item_name).val('0');
            $('#top_' + item_name).val('0');
            $('#width_' + item_name).val('100');
            $('#height_' + item_name).val('20');

            $('#left_<?php echo $key; ?>').val('<?php echo $val['left']; ?>');
            $('#top_<?php echo $key; ?>').val('<?php echo $val['top']; ?>');
            $('#width_<?php echo $key; ?>').val('<?php echo $val['width']; ?>');
            $('#height_<?php echo $key; ?>').val('<?php echo $val['height']; ?>');

        <?php } ?>
        <?php } ?>

    })
</script>
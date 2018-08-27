<?php if (!defined('ROOT_PATH')) exit('No Permission'); ?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<style>
    .ncsc-form-radio-list li, .ncsc-form-checkbox-list li {
        font-size: 12px;
        vertical-align: top;
        letter-spacing: normal;
        word-spacing: normal;
        display: inline-block;
        margin-right: 30px;
    }

    /*.webuploader-pick {
        padding: 0px;
    }*/

    select, .select {
        color: #777;
        background-color: #FFF;
        height: 30px;
        vertical-align: middle;
        padding: 0 4px;
        border: solid 1px #E6E9EE;
    }
</style>

<script src="<?=$this->view->js_com?>/webuploader.js"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/upload/upload_image.js" charset="utf-8"></script>

<div class="form-style">
    <form method="post" id="form" class="nice-validator n-yellow" novalidate="novalidate">
        <dl>
            <dt><i>*</i><?=__('模板名称')?>：</dt>
            <dd>
                <input type="text" autocomplete="off" name="waybill_name" id="waybill_name" maxlength="10" class="text w100" aria-required="true">
                <p class="hint"><?=__('运单模板名称，最多10个字')?></p>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('物流公司')?>：</dt>
            <dd>
                <select name="waybill_express" id="waybill_express" class="valid">
                <?php if( !empty($express_data) ) { ?>
                <?php foreach( $express_data as $key => $val ) { ?>
                        <option value="<?php echo $val['express_id']; ?>"><?php echo $val['express_name']; ?></option>
                <?php } ?>
                <?php } ?>
                </select>
                <p class="hint"><?=__('模板对应的物流公司')?></p>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('宽度')?>：</dt>
            <dd>
                <input type="text" autocomplete="off" name="waybill_width" id="waybill_width" class="text w100" aria-required="true"><em class="add-on">mm</em>
                <p class="hint"><?=__('运单宽度，单位为毫米')?>(mm)</p>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('高度')?>：</dt>
            <dd>
                <input type="text" autocomplete="off" name="waybill_height" id="waybill_height" class="text w100" aria-required="true"><em class="add-on">mm</em>
                <p class="hint"><?=__('运单高度，单位为毫米')?>(mm)</p>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('上偏移量')?>：</dt>
            <dd>
                <input type="text" autocomplete="off" name="waybill_top" id="waybill_top" class="text w100" aria-required="true"><em class="add-on">mm</em>
                <p class="hint"><?=__('运单模板上偏移量，单位为毫米')?>(mm)</p>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('左偏移量')?>：</dt>
            <dd>
                <input type="text" autocomplete="off" name="waybill_left" id="waybill_left" class="text w100" aria-required="true"><em class="add-on">mm</em>
                <p class="hint"><?=__('运单模板左偏移量，单位为毫米')?>(mm)</p>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('模板图片')?>：</dt>
            <dd>
                <input type="hidden" name="waybill_image" id="waybill_image">
                <img max-width="200" id="img_show" src="<?php echo $this->view->img_com; ?>/image.png" >
                <div id="btn_upload_img" style="width: 200px;"><i class="iconfont icon-tupianshangchuan"></i><?=__('上传图片')?></div>
                <span class="msg-box" for="waybill_image" style="float: right;margin-right: 445px;"></span>
                <p class="hint"><?=__('请上传扫描好的运单图片，图片尺寸必须与快递单实际尺寸相符')?></p>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('启用')?>：</dt>
            <dd>
                <ul class="ncsc-form-radio-list">
                    <li>
                        <label for="waybill_usable_1"><input id="waybill_usable_1" type="radio" name="waybill_usable" value="1"> <?=__('是')?></label>
                    </li>
                    <li>
                        <label for="waybill_usable_0"><input id="waybill_usable_0" type="radio" name="waybill_usable" value="0" checked=""> <?=__('否')?></label>
                    </li>
                </ul>
                <p class="hint"><?=__('请首先设计并测试模板然后再启用，启用后商家可以使用')?></p>
            </dd>
        </dl>

        <dl>
            <dt></dt>
            <dd>
                <input type="submit" class="button button_blue bbc_seller_submit_btns" value="<?=__('提交')?>">
                <input type="hidden" name="waybill_tpl_id" id="waybill_tpl_id" value="" />
            </dd>
        </dl>
    </form>
</div>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

<script>

    $('.tabmenu').children().children('li:gt(1) ').hide();
    $('.tabmenu').children().children('.active').show();

    $(function () {

        var met = 'addTpl';

        upload_image = new UploadImage({
            thumbnailWidth: 500,
            thumbnailHeight: 281,
            uploadButton: '#btn_upload_img',
            inputHidden: '#waybill_image',
            imageContainer: '#img_show',
            callback: function (response) {
                Public.tips({ content: '<?=__('上传成功')?>', type: 3});
            }
        })

        //验证
        $('#form').validator({
            theme: 'yellow_right',
            timely: true,

            rules: {

            },

            fields: {
                'waybill_name': 'required;length[~10]',
                'waybill_width':'required;range[0.01~9999];',
                'waybill_height': 'required;range[0.01~9999];',
                'waybill_top':'required;range[0.01~9999];',
                'waybill_left': 'required;range[0.01~9999];',
                'waybill_image': 'required'
            },

            valid: function(form){
                //表单验证通过，提交表单到服务器
                $.post( SITE_URL + "?ctl=Seller_Trade_Waybill&typ=json&met=" + met, $('#form').serialize(), function(data) {

                    if( data.status == 200 ) {
                        Public.tips({ content: '<?=__('保存成功')?>！', type: 3 });
                        setTimeout(function () {
                            window.location.href = SITE_URL + '?ctl=Seller_Trade_Waybill&met=waybillIndex&typ=e';
                        }, 1000);
                    } else {
                        Public.tips({ content: '<?=__('保存失败')?>！', type: 1 });
                    }
                })
            }
        })

        //初始化
        <?php if ( !empty($waybill_data) ) { ?>
            met = 'editTpl';
            $('#waybill_tpl_id').val(<?php echo $waybill_tpl_id; ?>);
            $('#waybill_name').val("<?php echo $waybill_data['waybill_tpl_name']; ?>");
            $('#waybill_width').val(<?php echo $waybill_data['waybill_tpl_width']; ?>);
            $('#waybill_height').val(<?php echo $waybill_data['waybill_tpl_height']; ?>);
            $('#waybill_top').val(<?php echo $waybill_data['waybill_tpl_top']; ?>);
            $('#waybill_left').val(<?php echo $waybill_data['waybill_tpl_left']; ?>);
            $('#waybill_express').children('[value="<?php echo $waybill_data['express_id']; ?>"]').prop('selected', 'selected');

            $('#img_show').prop('src', "<?php echo $waybill_data['waybill_tpl_image']; ?>");
            $('#waybill_image').val("<?php echo $waybill_data['waybill_tpl_image']; ?>");
            $('#waybill_usable_<?= $waybill_data['waybill_tpl_enable'] ?>').prop('checked', 'checked');
        <?php } ?>
    })
</script>
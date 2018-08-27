<?php
include TPL_PATH . '/' . 'header.php';
?>
<link href="<?= $this->view->css ?>/mb.css" rel="stylesheet" type="text/css">
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<style>
    .upload-image {
        line-height: 24px !important;
        height: 24px !important;
        padding: 0px 6px !important;
    }
    #button{
        display: inline-block;
        vertical-align: top;
    }
</style>

<div id="dialog_item_edit_image" style="display: block; z-index: 1100; width: 600px; left: 546.5px; top: 84.5px;" class="ui-draggable">
    <div class="dialog_body" style="position: relative;">
        <div class="dialog_content">
            <div class="s-tips"><i class="fa fa-lightbulb-o"></i>请按提示尺寸制作上传图片，已达到手机客户端及Wap手机商城最佳显示效果。</div>
            <div class="upload-thumb"><img id="dialog_item_image" src="<?= $this->view->img_com ?>/image.png" alt="" style="display: none;"></div>
            <form id="form_image" action="">
                <div class="ncap-form-default">
                    <dl class="row">
                        <dt class="tit">选择要上传的图片：</dt>
                        <dd class="opt">
                            <div class="input-file-show"><span class="type-file-box">
                                <input type="text" name="textfield" id="textfield" class="type-file-text">
                                <div name="button" id="button" class="">选择上传</div>
                            </span></div>
                            <p id="dialog_image_desc" class="notic">推荐图片尺寸640*340</p>
                        </dd>
                    </dl>
                    <dl class="row">
                        <dt class="tit">操作类型：</dt>
                        <dd class="opt">
                            <select id="dialog_item_image_type" name="" class="vatop">
                                <option value="">-请选择-</option>
                                <option value="keyword">关键字</option>
                                <!--<option value="special">专题编号</option>-->
                                <option value="goods">商品编号</option>
                                <option value="url">链接</option>
                            </select>
                            <input id="dialog_item_image_data" type="text" class="txt w200 marginright marginbot vatop">
                            <p id="dialog_item_image_desc" class="notic">操作类型一共四种，对应点击以后的操作。</p>
                        </dd>
                    </dl>
                </div>
            </form>
        </div>
    </div>
    <div style="clear:both;"></div>
</div>

<?php
include TPL_PATH . '/' . 'footer.php';
?>

<script type="text/javascript" src="<?= $this->view->js_com ?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->view->js ?>/models/upload_image.js" charset="utf-8"></script>

<script>
    $(function() {
        var api = frameElement.api,
            image_name = api.data.image_name,
            dialog_type = api.data.dialog_type,
            callback = api.data.callback,
            layout_data = api.data.layout_data,
            img_data = {};

        $('#dialog_image_desc').text('推荐图片尺寸'+api.data.image_spec);
        image_spec = api.data.image_spec.split('*');
        image_spec_width = image_spec[0];
        image_spec_height = image_spec[1];

        if ( dialog_type ) {
            if ( layout_data ) {
                $('#dialog_item_image').show().prop('src', layout_data.image);
                $('#dialog_item_image_data').val(layout_data.image_data);
                $('#dialog_item_image_type').val(layout_data.image_type);
            }
            else {
                $('#dialog_item_image').show();
            }
        }

        $('#dialog_item_image_type').on('change', function () {
            change_image_type_desc($(this).val());
        });

        function change_image_type_desc(type) {
            var desc_array = {};
            var desc = '操作类型一共四种，对应点击以后的操作。';
            if (type != '') {
                desc_array['keyword'] = '关键字类型会根据搜索关键字跳转到商品搜索页面，输入框填写搜索关键字。';
                desc_array['special'] = '专题编号会跳转到指定的专题，输入框填写专题编号。';
                desc_array['goods'] = '商品编号会跳转到指定的商品详细页面，输入框填写商品编号。';
                desc_array['url'] = '链接会跳转到指定链接，输入框填写完整的URL。';
                desc = desc_array[type];
            }
            $('#dialog_item_image_desc').text(desc);
        }

        new UploadImage({
            thumbnailWidth: image_spec_width,
            thumbnailHeight: image_spec_height,
            uploadButton: '#button',
            inputHidden: '#textfield',
            imageContainer: '#dialog_item_image',
            callback: function(res) {
                if (!image_name) {
                    image_name = res.title;
                }
            }
        });

        api.button({
            id: "confirm", name: '确定', focus: !0, callback: function ()
            {
                img_data.image = $('#textfield').val() ? $('#textfield').val() : $('#dialog_item_image').prop('src');
                img_data.image_name = image_name;
                img_data.image_type = $('#dialog_item_image_type').val();
                img_data.image_data = $('#dialog_item_image_data').val();
                console.info(img_data);
                callback(img_data);
            }
        }, {id: "cancel", name: '取消'});
    })
</script>

<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>

<?php
include TPL_PATH . '/' . 'header.php';
?>
<link href="<?= $this->view->css ?>/mb.css" rel="stylesheet" type="text/css">
<div class="mb-item-edit-content">
    <div class="index_block home1">
        <!--<h3>模型版块布局A</h3>-->
        <div class="title">
            <h5>标题：</h5>
            <input id="home1_title" type="text" class="txt w200" name="item_data[title]" value="">
        </div>
        <div nctype="item_content" class="content">
            <h5>内容：</h5>
            <div nctype="item_image" class="item"> <img nctype="image" src="<?= $this->view->img_com ?>/image.png" alt="">
                <input nctype="image_name" name="item_data[image]" type="hidden" value="s0_04953034893900222.jpg">
                <input nctype="image_type" name="item_data[type]" type="hidden" value="special">
                <input nctype="image_data" name="item_data[data]" type="hidden" value="3">
                <a nctype="btn_edit_item_image" data-desc="640*260" href="javascript:;"><i class="fa fa-pencil-square-o"></i>编辑</a>
            </div>
        </div>
    </div>
</div>

<?php
include TPL_PATH . '/' . 'footer.php';
?>

<script>
    $(function(){

        var api = frameElement.api,
            item_id = api.data.item_id,
            item_data = api.data.item_data, image,
            layout_data = item_data.mb_tpl_layout_data,
            callback = api.data.callback;
 
        if (layout_data) {
            $('#home1_title').val(item_data.mb_tpl_layout_title);
            render(layout_data);
        }

        api.button({
            id: "confirm", name: '确定', focus: !0, callback: function () {
                postData();
                return false;
            }
        }, {id: "cancel", name: '取消'});

        function postData() {
            var layout_title = $('#home1_title').val();
            Public.ajaxPost(SITE_URL + '?ctl=Mb_TplLayout&met=editTplLayout&typ=json', {
                item_id: item_id,
                layout_data: layout_data,
                layout_title: layout_title
            }, function (data) {
                if (data.status == 200) {
                    typeof callback == 'function' && callback();
                    return true;
                } else {
                    Public.tips({type: 1, content: data.msg});
                }
            })
        }

        $('[nctype="btn_edit_item_image"]').on('click', function () {
            $.dialog({
                title: '编辑',
                content: 'url:' + SITE_URL + '?ctl=Mb_TplLayout&met=editImage&typ=e',
                max: false,
                min: false,
                cache: false,
                lock: true,
                width: 600,
                height: 400,
                zIndex: 9999,
                parent: parent,
                data: {
                    image_spec: '640*260',
                    dialog_type: 'home1',
                    layout_data: layout_data,
                    callback: function (img_data) {
                        layout_data = img_data;
                        render(img_data);
                    }
                }
            })
        });

        function render (img_data) {
            console.info(img_data);
            $('[nctype="image"]').prop('src', img_data.image);
            $('[nctype="image_name"]').val(img_data.image_name);
            $('[nctype="image_type"]').val(img_data.image_type);
            $('[nctype="image_data"]').val(img_data.image_data);
        }
    })
</script>
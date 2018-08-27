<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>

<?php
include TPL_PATH . '/' . 'header.php';
?>
<link href="<?= $this->view->css ?>/mb.css" rel="stylesheet" type="text/css">
<div class="mb-item-edit-content">
    <div class="index_block home3">
        <!--<h3>模型版块布局C</h3>-->
        <div class="title">
            <h5>标题：</h5>
            <input id="home1_title" type="text" class="txt w200" name="item_data[title]" value="">
        </div>
        <div nctype="item_content" class="content">
            <h5>内容：</h5>
        </div>
        <a nctype="btn_add_item_image" class="ncap-btn ui-btn" data-desc="320*85" href="javascript:;"><i class="fa fa-plus"></i>添加新的块内容</a>
    </div>
</div>
<?php
include TPL_PATH . '/' . 'footer.php';
?>
<script type="text/javascript" src="<?= $this->view->js_com ?>/template.js" charset="utf-8"></script>
<script id="item_image_template" type="text/html">
    <div nctype="item_image" class="item">
        <img nctype="image" src="<%=image%>" alt="">
        <input nctype="image_name" name="item_data[item][<%=image_name%>][image]" type="hidden" value="<%=image_name%>">
        <input nctype="image_type" name="item_data[item][<%=image_name%>][type]" type="hidden" value="<%=image_type%>">
        <input nctype="image_data" name="item_data[item][<%=image_name%>][data]" type="hidden" value="<%=image_data%>">
        <a nctype="btn_del_item_image" href="javascript:;">删除</a>
    </div>
</script>

<script>

    $(function() {

        var api = frameElement.api,
            item_id = api.data.item_id,
            item_data = api.data.item_data,
            layout_data = item_data.mb_tpl_layout_data
        callback = api.data.callback;
        console.info(item_data);
        //初始化
        $('#home1_title').val(item_data.mb_tpl_layout_title);
        if ( layout_data && layout_data.length > 0 ) {
            for (var i=0; i<layout_data.length; i++) {
                $('[nctype="item_content"]').append(template.render('item_image_template', layout_data[i]));
            }
        } else {
            layout_data = new Array();
        }

        console.info(api.data);
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

        $('[nctype="btn_add_item_image"]').on('click', function () {
            $.dialog({
                title: '添加',
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
                    image_spec: '320*180',
                    image_name: false,
                    callback: function (img_data) {
                        layout_data.push(img_data);
                        $('[nctype="item_content"]').append(template.render('item_image_template', img_data));
                    }
                }
            })
        });

        //删除图片
        $('[nctype="item_content"]').on('click', '[nctype="btn_del_item_image"]', function () {
            $(this).parents('[nctype="item_image"]').remove();
            var image_name = $(this).prevAll('input[nctype="image_name"]').val();
            removeImage(image_name);
            console.info(item_data);
        });

        function removeImage(image_name) {
            for (var i = 0; i < layout_data.length; i++) {
                if(layout_data[i].image_name == image_name) {
                    layout_data.splice(i, 1);
                }
            }
        }
    })
</script>
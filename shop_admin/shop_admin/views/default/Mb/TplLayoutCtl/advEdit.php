<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>

<?php
include TPL_PATH . '/' . 'header.php';
?>
<link href="<?= $this->view->css ?>/mb.css" rel="stylesheet" type="text/css">
<div class="mb-item-edit-content">
    <div class="index_block adv_list">
        <!--<h3>广告条版块</h3>-->
        <h5>内容：</h5>
        <div nctype="item_content" class="content"></div>
        <a nctype="btn_add_item_image" class="ui-btn ui-btn-sp submit-btn" href="javascript:;" data-dialog_type="add">添加新的广告条</a>
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
        <a nctype="btn_edit_item_image" href="javascript:;" style="right: 50px;">编辑</a>
        <a nctype="btn_del_item_image" href="javascript:;">删除</a>
    </div>
</script>

<script>

    $(function() {

        var api = frameElement.api,
            item_id = api.data.item_id,
            item_data = api.data.item_data,
            layout_data = item_data.mb_tpl_layout_data,
            callback = api.data.callback;
        //初始化
        if ( layout_data && layout_data.length > 0 ) {
            for (var i=0; i<layout_data.length; i++) {
                $('[nctype="item_content"]').append(template.render('item_image_template', layout_data[i]));
            }
        } else {
            layout_data = new Array();
        }

        api.button({
            id: "confirm", name: '确定', focus: !0, callback: function () {
                postData();
                return false;
            }
        }, {id: "cancel", name: '取消'});

        function postData() {
            Public.ajaxPost(SITE_URL + '?ctl=Mb_TplLayout&met=editTplLayout&typ=json', {
                item_id: item_id,
                layout_data: getPostData()
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
                    image_name: false,
                    image_spec: '640*340',
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
        });

        function removeImage(image_name) {
            for (var i = 0; i < layout_data.length; i++) {
                if(layout_data[i].image_name == image_name) {
                    layout_data.splice(i, 1);
                }
            }
        }

        $('[nctype="btn_edit_item_image"]').on('click', function () {
            var _this = this;
            var layout_data = getImageData(_this);
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
                    image_spec: '640*340',
                    dialog_type: 'adv',
                    layout_data: layout_data,
                    callback: function (img_data) {
                        layout_data = img_data;
                        render(_this, img_data);
                    }
                }
            })
        });

        function render (_this, img_data) {
            var $div = $(_this).parent();

            $div.children('[nctype="image"]').prop('src', img_data.image);
            $div.children('[nctype="image_name"]').val(img_data.image_name);
            $div.children('[nctype="image_type"]').val(img_data.image_type);
            $div.children('[nctype="image_data"]').val(img_data.image_data);
        }

        function getImageData(_this)
        {
            var $div = $(_this).parent();

            return {
                'image': $div.children("[nctype='image']").attr("src"),
                'image_data': $div.children("[nctype='image_data']").val(),
                'image_type': $div.children("[nctype='image_type']").val()
            };
        }

        function  getPostData()
        {
            var postData = [];
            $('[nctype="item_image"]').each(function (i, e){

                var $this = $(this);
                postData.push({
                    'image': $this.children("[nctype='image']").attr("src"),
                    'image_name': $this.children("[nctype='image_name']").val(),
                    'image_data': $this.children("[nctype='image_data']").val(),
                    'image_type': $this.children("[nctype='image_type']").val()
                })
            });

            return postData;
        }
    })
</script>


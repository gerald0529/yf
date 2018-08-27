<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>

<?php
include TPL_PATH . '/' . 'header.php';
?>
<link href="<?= $this->view->css ?>/mb.css" rel="stylesheet" type="text/css">
<div class="mb-item-edit-content">
    <div class="index_block home2">
        <!--<h3>模型版块布局D</h3>-->
        <div class="title">
            <h5>标题：</h5>
            <input id="home1_title" type="text" class="txt w200" name="item_data[title]" value="">
        </div>
        <div class="content">
            <h5>内容：</h5>
            <div class="home2_2">
                <div class="home2_2_1">
                    <div nctype="item_image" class="item"> <img nctype="image" name="item_data[rectangle1_image]" src="<?= $this->view->img_com ?>/image.png" alt="">
                        <input nctype="image_name" name="item_data[rectangle1_image]" type="hidden" value="">
                        <input nctype="image_type" name="item_data[rectangle1_type]" type="hidden" value="">
                        <input nctype="image_data" name="item_data[rectangle1_data]" type="hidden" value="">
                        <a nctype="btn_edit_item_image" data-name="rectangle1" data-desc="320*130" href="javascript:;"><i class="fa fa-pencil-square-o"></i>编辑</a>
                    </div>
                    <div class="home2_2_2">
                        <div nctype="item_image" class="item"> <img nctype="image" name="item_data[rectangle2_image]" src="<?= $this->view->img_com ?>/image.png" alt="">
                            <input nctype="image_name" name="item_data[rectangle2_image]" type="hidden" value="">
                            <input nctype="image_type" name="item_data[rectangle2_type]" type="hidden" value="">
                            <input nctype="image_data" name="item_data[rectangle2_data]" type="hidden" value="">
                            <a nctype="btn_edit_item_image" data-name="rectangle2" data-desc="320*130" href="javascript:;"><i class="fa fa-pencil-square-o"></i>编辑</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="home2_1">
                <div nctype="item_image" class="item"> <img nctype="image" name="item_data[square_image]" src="<?= $this->view->img_com ?>/image.png" alt="">
                    <input nctype="image_name" name="item_data[square_image]" type="hidden" value="">
                    <input nctype="image_type" name="item_data[square_type]" type="hidden" value="">
                    <input nctype="image_data" name="item_data[square_data]" type="hidden" value="">
                    <a nctype="btn_edit_item_image" data-name="square" data-desc="320*260" href="javascript:;"><i class="fa fa-pencil-square-o"></i>编辑</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include TPL_PATH . '/' . 'footer.php';
?>

<script>
    $(function(){

        api = frameElement.api,
            item_id = api.data.item_id,
            item_data = api.data.item_data,
            layout_data = item_data.mb_tpl_layout_data,
            callback = api.data.callback;

        console.info(layout_data);

        //init
        $('#home1_title').val(item_data.mb_tpl_layout_title);
        if (layout_data) {
            layout_data.rectangle1 && render(layout_data.rectangle1 ,'rectangle1');
            layout_data.rectangle2 && render(layout_data.rectangle2 ,'rectangle2');
            layout_data.square && render(layout_data.square ,'square');
        } else {
            layout_data = {
                rectangle1: {},
                rectangle2: {},
                square: {}
            }
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

            var name = $(this).data('name');
            var l_data = {};
            eval('l_data=layout_data.' + name);
            var image_spec = $(this).data('desc');

            console.info(l_data);
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
                    image_spec: image_spec,
                    image_name: name,
                    dialog_type: 'home2',
                    layout_data: l_data,
                    callback: function (img_data) {
                        render(img_data, name);
                    }
                }
            })
        });

        function render (img_data, name) {
            eval('layout_data.'+ name +'=img_data');
            $('[nctype="image"][name="item_data[' + name + '_image]"]').prop('src', img_data.image);
            $('[nctype="image_name"][name="item_data[' + name + '_image]"]').val(img_data.image_name);
            $('[nctype="image_type"][name="item_data[' + name + '_type]"]').val(img_data.image_type);
            $('[nctype="image_data"][name="item_data[' + name + '_data]"]').val(img_data.image_data);
        }
    })
</script>

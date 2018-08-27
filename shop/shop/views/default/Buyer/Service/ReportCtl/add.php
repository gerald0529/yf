<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>

<link type="text/css" rel="stylesheet" href="<?= $this->view->css ?>/headfoot.css">
<link type="text/css" rel="stylesheet" href="<?= $this->view->css ?>/iconfont/iconfont.css">
<link type="text/css" rel="stylesheet" href="<?= $this->view->css ?>/index.css">
<style>.webuploader-pick{color:#FFF;}.webuploader-pick i{color:#FFF;}</style>
<div class="aright">
    <div class="member_infor_content">
        <div class="div_head  tabmenu clearfix">
            <ul class="tab pngFix clearfix">
                <li class="active">
                    <a><?=__('举报商品')?></a>
                </li>
            </ul>
        </div>
<div class="order_content" id="ncmComplainFlow">
    <div class="ncm-flow-step" style="text-align: center;">
            <dl class="step-first current">
                <dt><?=__('填写举报内容')?></dt>
                <dd class="bg"></dd>
            </dl>
            <dl class="">
                <dt><?=__('平台审核处理')?></dt>
                <dd class="bg"></dd>
            </dl>
            <dl class="">
                <dt><?=__('举报完成')?></dt>
                <dd class="bg"></dd>
            </dl>
    </div>
    <form id="form" action="#" method="post">
        <input type="hidden" name="goods_id" value="<?=$data['goods']['goods_id']?>">
        <div class="div_Consultation">
            <div style="margin-left:90px;">
                <table>
                    <tr>
                        <td><?=__('商品相关：')?></td>
                        <td style="width:88%; text-align: left;">
                            <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$data['goods']['goods_id']?>"><?=$data['goods']['goods_name']?></a>
                        </td>
                    </tr>
                    <tr>
                        <td><?=__('举报类型：')?></td>
                        <td style="width:88%;  text-align: left;">
                            <?php foreach($data['type'] as $k=>$v){ ?>
                                <p style="margin-left: 0px;"><input type="radio" <?php if($k==0){echo "checked='checked'";}?> name="report_type_id" value="<?=$v['report_type_id']?>" onclick="get_subject(<?=$v['report_type_id']?>)"> <?=$v['report_type_name']?></p>
                            <?php }?>
                        </td>
                    </tr>
                    <tr>
                        <td><?=__('举报主题：')?></td>
                        <td style="width:88%; text-align: left; ">
                            <select name="report_subject_id" id="report_subject_id">
                                <?php foreach($data['subject'] as $v){ ?>
                                    <opiton value="<?=$v['report_subject_id']?>"><?=$v['report_subject_name']?></opiton>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="margin-top:-45px;"><?=__('举报说明:')?></div>
                        </td>
                        <td style="padding-bottom:30px;padding-top: 10px; text-align: left;">
                 <textarea name="report_message" rows="5" cols="40" class="textarea_text"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td><?=__('上传举报证据:')?></td>
                        <td style="width:88%; text-align: left; ">
                            <input id="inputHidden" value="" type="hidden"/>
                            <div id="uploader-demo">
<!--                                <div id="fileList" class="uploader-list"></div>-->
                                 <li class="mb20"><img id="report_img" src="" style="max-width: 200px;max-height: 200px" /><input id="report_pic" type="hidden" name="report_pic[]" value=""></li>
                                 <div class="upload_img" id="filePicker"><i class="iconfont icon-jia"></i><?=__('上传证据')?></div>
                               
                            </div>
                        </td>
                    </tr>
                </table>
                <div style="margin-left:140px; padding-bottom:100px;margin-top:20px;">
                    <div class="div_abtn bbc_btns" id="handle_submit"><?=__('确认提交')?></div>
                </div>

            </div>
        </div>
    </form>
</div>
    </div>
</div>
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/jquery.validator.js"
        charset="utf-8"></script>
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<link href="./shop/static/common/css/jquery/plugins/dialog/green.css" rel="stylesheet">
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/upload/upload_image.js" charset="utf-8"></script>


 <script>
            report_pic_upload = new UploadImage({
                 thumbnailWidth: 500,
                 thumbnailHeight: 500,
                 imageContainer: '#report_img',
                 uploadButton: '#filePicker',
                 inputHidden: '#report_pic'
             });
    //图片上传
//    $(function(){

//        var $imagePreview, $imageInput, imageWidth, imageHeight,shopWidth;
//
//        $('#filePicker').on('click', function () {
//
//          
//                $imagePreview = $('#report_img');
//                $imageInput = $('#report_pic');
//                imageWidth = 500, imageHeight = 500,shopWidth = 500;
//             
//            $.dialog({
//                title: '图片裁剪',
//                content: "url: <--?= Yf_Registry::get('url') ?>?ctl=Upload&met=cropperImage&typ=e",
//                data: { width: imageWidth, height: imageHeight, callback: callback },    // 需要截取图片的宽高比例
//                width: shopWidth,
//                lock: true
//            })
//        });
//
//        function callback ( respone , api ) {
//            $imagePreview.attr('src', respone.url);
//            $imageInput.attr('value', respone.url);
//            api.close();  
//        }

//        if ( window.isIE8 ) {
//            $('#filePicker').off('click');




//        }

//    })

//    $('#filePicker').click(function ()
//    {
//        $(function ()
//        {
//            aloneImage = $.dialog({
//                content: 'url: ' + SITE_URL + '?ctl=Upload&met=image&typ=e',
//                data: {callback: getImageList},
//                height: 460
//            })
//        })
//
//        function getImageList(imageList)
//        {
//            for (i = 0; i < imageList.length; i++)
//            {
//                $('#fileList').append('<li><img src="' + imageList[i].src + '" /><input type="hidden" name="report_pic[]" value="' + imageList[i].src + '"></li>')
//            }
//        }
//    })


    function get_subject(type_id){
        $.ajax({
            type: 'POST',
            url: SITE_URL + '?ctl=Buyer_Service_Report&met=getSubject&typ=json',
            cache: false,
            data: "type_id=" + type_id,
            dataType: 'json',
            success: function (data)
            {
                //var data = eval("("+data+")");
                data = data.data;
                var link = '';
                $.each(data, function (n, value) {
                    link += "<option value='"+ value.report_subject_id+"'>" + value.report_subject_name +"</option>";
                });
                $("#report_subject_id").html(link);
            }
        });
    }
    $(document).ready(function ()
    {
        var type_id = $('input:radio[name="report_type_id"]:checked').val();
        get_subject(type_id);


        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            fields: {
                report_message: "required;"
            },
            valid: function (form)
            {
                //表单验证通过，提交表单
                $.ajax({
                    url: SITE_URL + '?ctl=Buyer_Service_Report&met=addReport&typ=json',
                    data: $("#form").serialize(),
                    success: function (a)
                    {
                        if (a.status == 200)
                        {
                            location.href = SITE_URL + '?ctl=Buyer_Service_Report&met=index';

                        }
                        else
                        {
                            Public.tips.error('<?=__('操作失败！')?>');
                        }
                    }
                });
            }

        }).on("click", "#handle_submit", function (e)
        {
            $(e.delegateTarget).trigger("validate");
        });
    });
</script>

<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>
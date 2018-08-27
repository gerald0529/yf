<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link href="<?=$this->view->css?>/jquery/plugins/validator/jquery.validator.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<body>

<div class="wrapper page">
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em></div>
        <ul>
            <li>用户注册，设置注册密码长度与密码复杂度。</li>
        </ul>
    </div>
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>注册设置</h3>
                <h5>用户注册设置</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=register&config_type%5B%5D=register"><span>注册设置</span></a></li>
                <li><a class="current"><span>注册图片设置</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Reg_Option&met=index"><span>注册项设置</span></a></li>
            </ul>
        </div>
    </div>
    <form style="" method="post" name="form_index" id="log-setting-form">
        <input type="hidden" name="config_type[]" value="register_img"/>
        <div class="ncap-form-default">

            <dl class="row">
                <dt class="tit">
                    <label>登录页广告图片</label>

                </dt>
                <dd class="opt">

                    <img id="login_logo_image" name="register_img[login_logo]" alt="选择图片" src="<?=($data['login_logo']['config_value'])?>"/>

                    <div class="image-line upload-image" id="login_logo_upload">上传图片<i class="iconfont icon-tupianshangchuan"></i></div>

                    <input id="login_logo" name="register_img[login_logo]" value="<?=($data['login_logo']['config_value'])?>" class="ui-input w400" type="hidden"/>
                    <p class="notic">登录页显示，最佳显示尺寸为500*500像素,请根据背景色选择使用图片色彩</p>
                    <input type="text" placeholder="链接地址" name="register_img[login_logo_url]" value="<?=($data['login_logo_url']['config_value'])?>">
                    <input type="text" placeholder="背景色，例如：#ffffff" name="register_img[login_backcolor]" value="<?=($data['login_backcolor']['config_value'])?>">
                </dd>
            </dl>


            <dl class="row">
                <dt class="tit">
                    <label>注册页广告图片</label>

                </dt>
                <dd class="opt" style="width: 30%;">

                    <img id="register_logo_image" name="register_img[register_logo]" alt="选择图片" src="<?=($data['register_logo']['config_value'])?>"/>

                    <div class="image-line upload-image"  id="register_logo_upload">上传图片<i class="iconfont icon-tupianshangchuan"></i></div>

                    <input id="register_logo" name="register_img[register_logo]" value="<?=($data['register_logo']['config_value'])?>" class="ui-input w400" type="hidden"/>
                    <p class="notic">注册页广告图片，最佳显示尺寸为210*270像素</p>
                    <input type="text" placeholder="链接地址" name="register_img[register_logo_url]" value="<?=($data['register_logo_url']['config_value'])?>">
                </dd>
            </dl>

            <div class="bot"> <a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </div>
    </form>
</div>
<script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/models/upload_image.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<script>
    //图片上传
    $(function(){

        //图片裁剪

        var $imagePreview, $imageInput, imageWidth, imageHeight;

        $('#register_logo_upload, #login_logo_upload').on('click', function () {

            if ( this.id == 'login_logo_upload' ) {
                $imagePreview = $('#login_logo_image');
                $imageInput = $('#login_logo');
                imageWidth = 470, imageHeight = 470;
            } else {
                $imagePreview = $('#register_logo_image');
                $imageInput = $('#register_logo');
                imageWidth = 210, imageHeight = 270;
            }
            console.info($imagePreview);
            $.dialog({
                title: '图片裁剪',
                content: "url: <?= Yf_Registry::get('url') ?>?ctl=Config&met=cropperImage&typ=e",
                data: { UCENTER_URL: UCENTER_URL, width: imageWidth, height: imageHeight, callback: callback },    // 需要截取图片的宽高比例
                width: '800px',
                lock: true
            })
        });

        function callback ( respone , api ) {
            console.info($imagePreview);
            $imagePreview.attr('src', respone.url);
            $imageInput.attr('value', respone.url);
            api.close();
        }

//        new UploadImage({
//            thumbnailWidth: "270",
//            imageHeight: "210",
//            uploadButton: "#register_logo_upload",
//            imageContainer: "#register_logo_image",
//            inputHidden: "#register_logo",
//        });
    })
</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>

<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>

<style>
    .webuploader-pick{ padding:1px; }
    .image-line {height: 23px;width: 50px; }
</style>
</head>
<body>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>基础设置&nbsp;</h3>
                <h5>站点相关基础信息及功能设置选项</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=site&config_type%5B%5D=site"><span>基础设置</span></a></li>
                <li><a class="current"><span>站点Logo</span></a></li>
				<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=msgAccount&config_type%5B%5D=email&config_type%5B%5D=sms">邮件设置</a></li>
				<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=mobileAccount&config_type%5B%5D=mobile&config_type%5B%5D=sms">短信设置</a></li>
				<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=seo&typ=e&config_type%5B%5D=seo"><span>SEO配置</span></a></li>
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>
            <li>网站全局基本设置。</li>
        </ul>
    </div>

    <form method="post" enctype="multipart/form-data" id="site-config-form" name="form">
        <input type="hidden" name="config_type[]" value="site"/>

        <div class="ncap-form-default">

            <dl class="row">
                <dt class="tit">
                    <label>网站Logo</label>
                </dt>
                <dd class="opt">
                    <img id="site_logo_image" name="site[site_logo]" alt="选择图片" src="<?=($data['site_logo']['config_value'])?>" width="200px" height="200px" />

                    <div class="image-line upload-image"  id="site_logo_upload">上传图片</div>

                    <input id="site_logo"  name="site[site_logo]" value="<?=($data['site_logo']['config_value'])?>" class="ui-input w400" type="hidden"/>
                    <div class="notic">默认网站LOGO,通用头部显示，最佳显示尺寸为200*200像素</div>
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

        /*site_logo_upload = new UploadImage({
            thumbnailWidth: 240,
            thumbnailHeight: 60,
            imageContainer: '#site_logo_image',
            uploadButton: '#site_logo_upload',
            inputHidden: '#site_logo'
        });

        seller_logo_upload = new UploadImage({
            thumbnailWidth: 160,
            thumbnailHeight: 60,
            imageContainer: '#site_seller_image',
            uploadButton: '#seller_logo_upload',
            inputHidden: '#site_seller_logo'
        });

        buyer_logo_upload = new UploadImage({
            thumbnailWidth: 150,
            thumbnailHeight: 40,
            imageContainer: '#site_buyer_image',
            uploadButton: '#buyer_logo_upload',
            inputHidden: '#site_buyer_logo'
        });*/

        //图片裁剪

        var $imagePreview, $imageInput, imageWidth, imageHeight;

        $('#site_logo_upload').on('click', function () {

            if ( this.id == 'site_logo_upload' ) {
                $imagePreview = $('#site_logo_image');
                $imageInput = $('#site_logo');
                imageWidth = 200, imageHeight = 200;
            } else if ( this.id == 'seller_logo_upload' ) {
                $imagePreview = $('#site_seller_image');
                $imageInput = $('#site_seller_logo');
                imageWidth = 160, imageHeight = 60;
            } else {
                $imagePreview = $('#site_buyer_image');
                $imageInput = $('#site_buyer_logo');
                imageWidth = 150, imageHeight = 40;
            }

            $.dialog({
                title: '图片裁剪',
                content: "url: <?= Yf_Registry::get('url') ?>?ctl=Config&met=cropperImage&typ=e",
                data: { PAYCENTER_URL: PAYCENTER_URL, width: imageWidth, height: imageHeight, callback: callback },    // 需要截取图片的宽高比例
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
    })
</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
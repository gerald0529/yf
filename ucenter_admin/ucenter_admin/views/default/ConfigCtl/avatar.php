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
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=ucenterApi&config_type%5B%5D=api"><span>本系统 API设置</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=site&config_type%5B%5D=site"><span>基础设置</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=settings&typ=e&config_type%5B%5D=site"><span>站点Logo</span></a></li>
                <li><a class="current"><span>用户默认头像</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=seo&typ=e&config_type%5B%5D=seo"><span>SEO配置</span></a></li>
            </ul>
        </div>
    </div>

    <!-- 操作说明 -->

    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em></div>
        <ul>
            <li>网站基础信息配置,系统安装成功后,应先配置此处。</li>
        </ul>
    </div>

    <form method="post" enctype="multipart/form-data" id="site-config-form" name="form">
        <input type="hidden" name="config_type[]" value="site"/>

        <div class="ncap-form-default">

            <dl class="row">
                <dt class="tit">
                    <label>用户默认头像</label>
                </dt>
                <dd class="opt">
                    <img id="user_default_image" name="site[user_default_avatar]" alt="选择图片" src="<?=($data['user_default_avatar']['config_value'])?>" width="120px" height="120px" />

                    <div class="image-line upload-image"  id="user_default_avatar_upload">上传图片</div>

                    <input id="user_default_avatar"  name="site[user_default_avatar]" value="<?=($data['user_default_avatar']['config_value'])?>" class="ui-input w400" type="hidden"/>
                    <div class="notic">用户默认头像,通用与用户未设置头像时使用，最佳显示尺寸为120*120像素</div>
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

        var $imagePreview, $imageInput, imageWidth, imageHeight;

        $('#user_default_avatar_upload, #seller_logo_upload, #buyer_logo_upload').on('click', function () {

            if ( this.id == 'user_default_avatar_upload' ) {
                $imagePreview = $('#user_default_image');
                $imageInput = $('#user_default_avatar');
                imageWidth = 120, imageHeight = 120;
            } else if ( this.id == 'seller_logo_upload' ) {
                $imagePreview = $('#site_seller_image');
                $imageInput = $('#site_seller_logo');
                imageWidth = 160, imageHeight = 60;
            } else {
                $imagePreview = $('#site_buyer_image');
                $imageInput = $('#site_buyer_logo');
                imageWidth = 150, imageHeight = 40;
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
    })
</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
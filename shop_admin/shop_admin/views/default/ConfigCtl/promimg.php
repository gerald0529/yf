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
            <li>设置积分商城首页图片</li>
        </ul>
    </div>
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>积分兑换</h3>
                <h5>平台会员积分兑换礼品管理</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_Points&met=goods" ><span>礼品列表</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Promotion_Points&met=order" ><span>兑换列表</span></a></li>
                <li><a class="current"><span>积分商城首页图片</span></a></li>
            </ul>
        </div>
    </div>
    <form style="" method="post" name="form_index" id="promotiom-setting-form">
        <input type="hidden" name="config_type[]" value="promotiom_img"/>
        <div class="ncap-form-default">

            <dl class="row">
                <dt class="tit">
                    <label>图片</label>

                </dt>
                <dd class="opt">

                    <img id="promotiom_image" name="promotiom_img[promotiom_img]" alt="选择图片" src="<?=($data['promotiom_img']['config_value'])?>"/>

                    <div class="image-line upload-image" id="promotiom_img_upload">上传图片<i class="iconfont icon-tupianshangchuan"></i></div>

                    <input id="promotiom_logo" name="promotiom_img[promotiom_img]" value="<?=($data['promotiom_img']['config_value'])?>" class="ui-input w400" type="hidden"/>
                    <p class="notic">积分商城页显示，最佳显示尺寸为900*368像素</p>
                   <!-- <input type="text" name="promotiom_img[promotiom_img_url]" placeholder="链接地址" value="<?/*=@($data['promotiom_img_url']['config_value'])*/?>">-->
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

        $('#promotiom_img_upload').on('click', function () {

            if ( this.id == 'promotiom_img_upload' ) {
                $imagePreview = $('#promotiom_image');
                $imageInput = $('#promotiom_logo');
                imageWidth = 900, imageHeight = 368;
            }

            console.info($imagePreview);
            $.dialog({
                title: '图片裁剪',
                content: "url: <?= Yf_Registry::get('url') ?>?ctl=Index&met=cropperImage&typ=e",
                data: { SHOP_URL: SHOP_URL, width: imageWidth, height: imageHeight, callback: callback },    // 需要截取图片的宽高比例
                width: '800px',
                height:$(window).height()*0.9,
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

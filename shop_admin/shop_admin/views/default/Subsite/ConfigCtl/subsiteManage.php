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
/*.page{width:963px !important;min-width:900px;}*/
.hidden{display:none !important;}
.area_in_sub{height:30px;line-height: 30px;margin-bottom: 10px;}
.area_in_sub div span{margin-right: 10px;} 
.area_in_sub .second{width:95%;display:inline-block;}
.area_in_sub .second select{width:130px;height:30px;}
.area_in_sub .delete_area{width:3%;display:inline-block;cursor:pointer;text-align:center;}
.add_row{display:block;}
</style>
</head>
<body>

<div class="wrapper">
	<form id="manage-form" action="#">
		<div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="sub_site_name">分站名称</label>
                </dt>
                <dd class="opt">
                	<input type="hidden" id="subsite_id">
                    <input id="sub_site_name" name="sub_site_name" value="" class="ui-input w346 h27" type="text"/>

                    <p class="notic">分站名称，将显示在前台顶部欢迎信息等位置</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="sub_site_logo">分站logo</label>
                </dt>
                <dd class="opt">
                	<img id="setting_logo_image" name="sub_site_logo" alt="选择图片" src="" width="150px" height="40px" />

                    <div class="image-line upload-image" id="sub_site_logo_upload">上传图片<i class="iconfont icon-tupianshangchuan"></i></div>
                    <input id="sub_site_logo" name="sub_site_logo" value="" class="ui-input w400" type="hidden"/>

                    <p class="notic">分站logo,通用头部显示，最佳显示尺寸为240*60像素</p>
                </dd>
            </dl>
<!--			<dl class="row">
                <dt class="tit">
                    <label for="parent_subsite">上级分站</label>
                </dt>
                <dd class="opt">
                    <input id="parent_subsite" name="parent_subsite" value="" readonly="true" class="ui-input w346 h27" type="text" placeholder="没有上级分站"/>
					<input type="text" hidden="true" value="" class="ui-input" name="parent_id" id="parent_id">
                    <p class="notic">当前分站所属的上级分站</p>
                </dd>
            </dl>-->
            <dl class="row">
                <dt class="tit">
                    <label for="sub_site_domain">分站域名前缀</label>
                </dt>
                <dd class="opt">
                    <input id="sub_site_domain" name="sub_site_domain" value="" class="ui-input w346 h27" type="text"/>
                    <p class="notic">当前分站的域名前缀，20个字符以内</p>
                </dd>
            </dl>

<!--            <dl class="row">
                <dt class="tit">
                    <label for="sub_site_template">分站模板</label>
                </dt>
                <dd class="opt">
                    <input id="sub_site_template" name="sub_site_template" value="" class="ui-input w346 h27" type="text"/>
                </dd>
            </dl>-->

            <dl class="row">
                <dt class="tit">
                    <label for="sub_site_des">分站描述</label>
                </dt>
                <dd class="opt">
                    <textarea name="sub_site_des" class="ui-input w346" id="sub_site_des"></textarea>
                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="sub_site_copyright">分站版权信息</label>
                </dt>
                <dd class="opt">
                    <textarea name="sub_site_copyright" class="ui-input w346" id="sub_site_copyright"></textarea>
                    <p class="notic">版权信息</p>
                </dd>
            </dl>

			<dl class="row">
                <dt class="tit">
                    <label for="sub_site_web_title">SEO标题</label>
                </dt>
                <dd class="opt">
                    <input id="sub_site_web_title" name="sub_site_web_title" value="" class="ui-input w346 h27" type="text"/>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="sub_site_web_keyword">SEO关键字</label>
                </dt>
                <dd class="opt">
                    <input id="sub_site_web_keyword" name="sub_site_web_keyword" value="" class="ui-input w346 h27" type="text"/>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="sub_site_web_des">SEO描述</label>
                </dt>
                <dd class="opt">
                    <textarea name="sub_site_web_des" class="ui-input w346" id="sub_site_web_des"></textarea>
                    <p class="notic"></p>
                </dd>
            </dl>
            
        </div>
        
	</form>

</div>
<script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/models/upload_image.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/subsite/subsite_manage.js" charset="utf-8"></script>
<script>
    //图片上传
    $(function(){
        function uploadImage() {
            var sub_site_logo_upload = new UploadImage({
                thumbnailWidth: 240,
                thumbnailHeight: 60,
                imageContainer: '#setting_logo_image',
                uploadButton: '#sub_site_logo_upload',
                inputHidden: '#sub_site_logo'
            });
        }

        var agent = navigator.userAgent.toLowerCase();
        if ( agent.indexOf("msie") > -1 && (version = agent.match(/msie [\d]/), ( version == "msie 8" || version == "msie 9" )) ) {
            uploadImage();
        } else {
            cropperImage();
        }

        //图片裁剪
        function cropperImage() {
            var $imagePreview, $imageInput, imageWidth, imageHeight;
            $('#sub_site_logo_upload').on('click', function () {
                if ( this.id == 'sub_site_logo_upload' ) {
                    $imagePreview = $('#setting_logo_image');
                    $imageInput = $('#sub_site_logo');
                    imageWidth = 240, imageHeight = 60;
                }
				//            console.info($imagePreview);
                $.dialog({
                    title: '图片裁剪',
                    content: "url: <?= Yf_Registry::get('url') ?>?ctl=Index&met=cropperImage&typ=e",
                    data: { SHOP_URL: SHOP_URL, width: imageWidth, height: imageHeight, callback: callback },    // 需要截取图片的宽高比例
                    width: '800px',
                    height:$(window).height()*0.9,
                    lock: true,
                    zIndex:1999
                })
            });

            function callback ( respone , api ) {
                $imagePreview.attr('src', respone.url);
                $imageInput.attr('value', respone.url);
                api.close();
            }
        }
    })
</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
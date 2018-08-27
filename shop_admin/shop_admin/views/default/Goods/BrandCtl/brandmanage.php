<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
<link href="<?= $this->view->css ?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?= $this->view->css_com ?>/jquery/plugins/validator/jquery.validator.css">
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
    <style>
body{background: #fff;}
.mod-form-rows .label-wrap{font-size:12px;}
.mod-form-rows .row-item {padding-bottom: 15px;margin-bottom: 0;}/*兼容IE7 ，重写common的演示*/
.manage-wrapper{margin:20px auto 10px;width:600px;}
.manage-wrap .ui-input{width: 198px;}
.base-form{*zoom: 1;}
.base-form:after{content: '.';display: block;clear: both;height: 0;overflow: hidden;}
.base-form li{float: left;width: 290px;}
.base-form li.odd{padding-right:20px;}
.base-form li.last{width:350px}
.manage-wrap textarea.ui-input{width: 588px;height: 32px;overflow:hidden;}
.contacters{margin-bottom: 10px;}
.contacters h3{margin-bottom: 10px;font-weight: normal;}
.remark .row-item{padding-bottom:0;}
.mod-form-rows .ctn-wrap{overflow: visible;}
.grid-wrap .ui-jqgrid{border-width:1px 0 0 1px;}
</style>
</head>
<body>
<div class="manage-wrapper">
    <div id="manage-wrap" class="manage-wrap">
    	<form id="manage-form" action="">
    		<ul class="mod-form-rows base-form cf" id="base-form">
    			<li class="row-item odd">
    				<div class="label-wrap"><label for="brand_name">品牌名称</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="brand_name" id="brand_name" maxlength="5"></div>
    			</li>
    			<!--<li class="row-item">
    				<div class="label-wrap"><label for="brand_initial">品牌首字母</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="brand_initial" id="brand_initial"></div>
    			</li>-->

    			<li class="row-item">
    				<div class="label-wrap"><label for="cat_name">所属分类</label></div>
    				<div class="ctn-wrap"><input style='height: 50px;' type="text" value="" class="ui-input" name="cat_name" id="cat_name"></div>
    			</li>

    			<li class="row-item odd">
    			    <div class="label-wrap"><label for="brand_show_type">展现形式</label></div>
    				<dd class="opt">
                        <div class="onoff">
                            <label for="brand_show_type1" class="cb-enable  ">文字</label>
                            <label for="brand_show_type0" class="cb-disable  selected">图片</label>
                            <input id="brand_show_type1"  name ="brand_show_type"  value="1" type="radio">
                            <input id="brand_show_type0"  name ="brand_show_type"  checked="checked"  value="0" type="radio">
                        </div>
                    </dd>
    			</li>
    			<li class="row-item ">
    			    <div class="label-wrap"><label for="brand_recommend">是否推荐</label></div>
    				<dd class="opt">
                        <div class="onoff">
                            <label for="brand_recommend1" class="cb-enable  ">是</label>
                            <label for="brand_recommend0" class="cb-disable  selected">否</label>
                            <input id="brand_recommend1"  name ="brand_recommend"  value="1" type="radio">
                            <input id="brand_recommend0"  name ="brand_recommend"  checked="checked"  value="0" type="radio">
                        </div>
                    </dd>
    			</li>
    			<li class="row-item wp100">
    				<div class="label-wrap"><label for="brand_displayorder">排序</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="brand_displayorder" id="brand_displayorder"></div>
    			</li>
    			
    			<input style="display: none;" id="brand_enable1"  name ="brand_enable"  value="1" type="radio" checked="checked">
    			
    			<li class="row-item wp100">
    				<div class="label-wrap"><label for="brand_image">图片标识</label></div>
    				<div class="ctn-wrap" >
                        <img id="brand_image" name="setting[brand_logo]" alt="选择图片" src="./shop_admin/static/common/images/image.png" class="image-line" />
                        <div class="image-line" style="margin-left: 80px;" id="brand_upload">上传图片<i class="iconfont icon-tupianshangchuan"></i></div>

                        <input id="brand_logo" name="setting[brand_logo]" value="" class="ui-input w400" type="hidden"/>
                    </div>
    			</li>
    		</ul>
    	</form>
    </div>
</div>
<script>
    //图片上传
    $(function(){
        buyer_logo_upload = new UploadImage({
            thumbnailWidth: 240,
            thumbnailHeight: 200,
            imageContainer: '#brand_image',
            uploadButton: '#brand_upload',
            inputHidden: '#brand_logo'
        });
    })
</script>
<script type="text/javascript" src="<?= $this->view->js_com ?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->view->js ?>/models/upload_image.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->view->js ?>/controllers/goods/listCatNav.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->view->js ?>/controllers/goods/goods_brandmanage.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
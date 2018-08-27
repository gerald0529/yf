<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<style>
.manage-wrap{margin: 20px auto 10px;width:450px;}
input[type="text"]{width:250px}
</style>
</head>
<body>

<div id="manage-wrap" class="manage-wrap">
	<form id="manage-form" action="#">
		<ul class="mod-form-rows">
			<li class="row-item">
				<div class="label-wrap">
                    <label for="groupbuy_cat_name">分类名称:</label>
                </div>
				<div class="ctn-wrap">
                    <input type="text" value="" class="ui-input" name="groupbuy_cat_name" id="groupbuy_cat_name">
                </div>
			</li>
			<li class="row-item">
				<div class="label-wrap">
                    <label for="parent_district">上级分类:</label>
                </div>
				<div class="ctn-wrap">
                    <input type="text" value="" class="ui-input" name="parent_cat" id="parent_cat" readonly="true" placeholder="没有上级分类">
                </div>
				<div class="ctn-wrap">
                    <input type="hidden" value="" class="ui-input" name="parent_id" id="parent_id">
                </div>
			</li>
			<li class="row-item">
				<div class="label-wrap">
                    <label for="groupbuy_cat_type">分类类型:</label>
                </div>
				<div class="ctn-wrap">
				    <input type="radio" name="cat_type" class="cat_type" checked="checked" value="1">实物
				    <input type="radio" name="cat_type" class="cat_type" value="2">虚拟
				    <div class="ctn-wrap">
                        <input type="hidden" value="1" class="ui-input" name="groupbuy_cat_type" id="groupbuy_cat_type">
                    </div>
				</div>
			</li>
			<li class="row-item">
				<div class="label-wrap">
                    <label for="groupbuy_cat_sort">排序:</label>
                </div>
				<div class="ctn-wrap">
                    <input type="text" maxlength="20" value="0" name="groupbuy_cat_sort" id="groupbuy_cat_sort" class="ui-input">
                    <p class="notic">排序数值从0到255，数字0优先级最高</p>
                </div>
			</li>
		</ul>
	</form>
</div>

<script src="<?= Yf_Registry::get('base_url') ?>/shop_admin/static/default/js/controllers/promotion/groupbuy/cat_manage.js"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>

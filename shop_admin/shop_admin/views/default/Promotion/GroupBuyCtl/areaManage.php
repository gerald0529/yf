<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<style>
.manage-wrap{margin: 20px auto 10px;width: 300px;}
</style>
</head>
<body>
<div id="manage-wrap" class="manage-wrap">
	<form id="manage-form" action="#">
		<ul class="mod-form-rows">
			<li class="row-item">
				<div class="label-wrap"><label for="groupbuy_area_name">地区名称:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="groupbuy_area_name" id="groupbuy_area_name"></div>
			</li>
			<li class="row-item">
				<div class="label-wrap"><label for="parent_district">上级地区:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="parent_district" id="parent_district" readonly="true" placeholder="没有上级地区"></div>
				<div class="ctn-wrap"><input type="hidden" value="" class="ui-input" name="parent_id" id="parent_id"></div>
			</li>
		</ul>
	</form>
</div>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/promotion/vtgroupbuy/area_manage.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
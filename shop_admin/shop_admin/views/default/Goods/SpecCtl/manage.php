<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<style>
.manage-wrap{margin: 20px auto 10px;width: 350px;}
</style>
</head>
<body>
<div id="manage-wrap" class="manage-wrap">
	<form id="manage-form" action="#">
		<ul class="mod-form-rows">
			<li class="row-item">
				<div class="label-wrap"><label for="spec_name"><i>*</i>规格:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="spec_name" id="spec_name"></div>
			</li>
			<li class="row-item">
				<div class="label-wrap"><label for="spec_displayorder">排序:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="spec_displayorder" id="spec_displayorder"></div>
			</li>
		</ul>
	</form>
</div>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/goods/goods_spec.js" charset="utf-8"></script>
<!--<script type="text/javascript" src="./shop_admin/static/common/js/plugins/jquery.datetimepicker.js" charset="utf-8"></script>
<script type="text/javascript" src="./shop_admin/static/common/css/jquery/plugins/datepicker/dateTimePicker.css" charset="utf-8"></script>-->
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
</head>
<style>
.manage-wrap{margin: 20px auto 10px;width: 300px;}
</style>
</head>
<body>
<div id="manage-wrap" class="manage-wrap">
	<form id="manage-form" action="#">
		<ul class="mod-form-rows">
			<li class="row-item">
				<div class="label-wrap"><label for="msg_tpl_name">模版名称:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="msg_tpl_name" id="msg_tpl_name"></div>
			</li>
			<li class="row-item">
				<div class="label-wrap"><label for="msg_tpl_desc">模版描述:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="msg_tpl_desc" id="msg_tpl_desc" readonly="true"></div>
			</li>
		</ul>
	</form>
</div>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/manageMsgTpl.js" charset="utf-8"></script>
<!--<script type="text/javascript" src="./shop_admin/static/common/js/jquery.validator.js" charset="utf-8"></script>-->
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
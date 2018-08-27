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
				<div class="label-wrap"><label for="article_group_sort">排序:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="article_group_sort" id="article_group_sort"></div>
			</li>
			<li class="row-item">
				<div class="label-wrap"><label for="article_group_title">分类名称:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="article_group_title" id="article_group_title"></div>
			</li>
			<!--
			<li class="row-item">
				<div class="label-wrap"><label for="parent_group">上级分类:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="parent_name" id="parent_name" readonly="true" placeholder="没有上级分类"></div>
				<div class="ctn-wrap"><input type="text" hidden="true" value="" class="ui-input" name="parent_id" id="parent_id"></div>
			</li>
			-->
		</ul>
	</form>
</div>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/article/group_manage.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
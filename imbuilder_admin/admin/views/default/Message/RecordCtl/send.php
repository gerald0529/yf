<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>
<link rel="stylesheet" href="./admin/static/default/css/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="./admin/static/default/js/libs/jquery/plugins/validator/jquery.validator.js"></script>
<script type="text/javascript" src="./admin/static/default/js/libs/jquery/plugins/validator/local/zh_CN.js"></script>
<style>
.manage-wrap{margin: 20px auto 10px;width: 300px;}
</style>
</head>
<body>
<div id="manage-wrap" class="manage-wrap">
	<form id="manage-form" action="#">
		<ul class="mod-form-rows" style="width:400px;  margin-left: -130px;">
			<li class="row-item">
				<div class="label-wrap"><label for="vendor_type_name">接收人:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="vendor_type_name" id="vendor_type_name" style="width: 300px;"></div>
			</li>
			<li class="row-item">
				<div class="label-wrap"><label for="vendor_type_desc">消息内容:</label></div>
				<div class="ctn-wrap"><textarea value="" class="ui-input" name="vendor_type_desc" id="vendor_type_desc" style="width:300px;height:190px;"></textarea></div>
			</li>
		</ul>
	</form>
</div>
<script src="./admin/static/default/js/controllers/message/send.js"></script>
<?php
include TPL_PATH . '/'  . 'footer.php';
?>
<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>
<style>
body{background: #fff;}
.manage-wrap{margin:20px auto 10px;width:300px;}
.manage-wrap .ui-input{width:200px;font-size:14px;}
.mg-right{margin-right:10px;}
</style>
</head>
<body>
<div id="manage-wrap" class="manage-wrap">
	<form id="manage-form" action="#">
		<ul class="mod-form-rows">
			<li class="row-item">
				<div class="label-wrap"><label>操作:</label></div>
				<div class="ctn-wrap"><input type="checkbox" id="database_select_all" onclick='select_all();' >全选<input type="checkbox"  id="database_select_reverse" onclick='select_reverse();' style='margin-left:10px;'>反选</div>
			</li>
			<li class="row-item">
				<div class="label-wrap"><label for="database_list">数据库:</label></div>
				<div class="ctn-wrap"><span id='database_list'></span></div>
			</li>
		</ul>
	</form>
</div>
<script src="./admin/static/default/js/controllers/database_backup_manage.js"></script>
<?php
include TPL_PATH . '/'  . 'footer.php';
?>
<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>
<style>
body{background: #fff;}
.manage-wrap{margin: 20px auto 10px;width: 300px;}
.manage-wrap .ui-input{width: 200px;font-size:14px;}
.manage-wrap .input-text{width: 200px;font-size:14px;height:160px;}
.mt_info{line-height:22px;}
.mt_stit{font-weight:bold;color:#777;}
</style>
</head>
<body>
<div class="wrapper">
	<div class="mod-toolbar-top cf">
	    <div class="fl"><strong class="tit">数据库备份</strong></div>
		<div style='clear:both;'></div>
		<div style='margin-top:7px;' class="fl"><a href="javascript:void(0)" class="ui-btn ui-btn-sp mrb" id="btn-backup">一键备份</a></div>
		<div style='margin-top:7px;' class="fr"><a href="javascript:void(0)" class="ui-btn ui-btn-sp mrb" id="btn-refresh">刷新</a></div>
	</div>

	 <div class="grid-wrap">
	    <table id="grid">
	    </table>
	    <div id="page"></div>
	  </div>
</div>
<script src="./admin/static/default/js/controllers/database_backup.js"></script>
<?php
include TPL_PATH . '/'  . 'footer.php';
?>
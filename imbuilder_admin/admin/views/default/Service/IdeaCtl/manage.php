<?php
/**
 * Created by PhpStorm.
 * User: 新泽
 * Date: 2015/2/22
 * Time: 9:53
 */
?>
<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>
<link rel="stylesheet" href="./admin/static/default/css/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="./admin/static/default/js/libs/jquery/plugins/validator/jquery.validator.js"></script>
<script type="text/javascript" src="./admin/static/default/js/libs/jquery/plugins/validator/local/zh_CN.js"></script>
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
    				<div class="label-wrap"><label for="idea_id">编号</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="idea_id" id="idea_id"></div>
    			</li>
    			<li class="row-item">
    				<div class="label-wrap"><label for="title">标题</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="title" id="title"></div>
    			</li>
    			<li class="row-item odd">
    				<div class="label-wrap"><label for="creat_name">提问人</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="creat_name" id="creat_name"></div>
    			</li>
    			<li class="row-item">
    				<div class="label-wrap"><label for="creat_time">提问时间</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="creat_time" id="creat_time"></div>
    			</li>
    		</ul>
    		<ul class="mod-form-rows remark">
    			<li class="row-item">
    			    <div class="label-wrap"><label for="idea">内容</label></div>
    				<div class="ctn-wrap"><textarea name="idea" id="idea" class="ui-input ui-input-ph"></textarea></div>
    			</li>
    		</ul>
    		<ul class="mod-form-rows remark">
    			<li class="row-item">
    			    <div class="label-wrap"><label for="respon">回复</label></div>
    				<div class="ctn-wrap"><textarea name="respon" id="respon" class="ui-input ui-input-ph"></textarea></div>
    			</li>
    		</ul>
    	</form>
    </div>
    <div class="hideFile dn">
	    <input type="text" class="textbox address" name="address" id="address" autocomplete="off" readonly>
	</div>
</div>
<script src="./admin/static/default/js/controllers/service/manage.js"></script>
<?php
include TPL_PATH . '/'  . 'footer.php';
?>
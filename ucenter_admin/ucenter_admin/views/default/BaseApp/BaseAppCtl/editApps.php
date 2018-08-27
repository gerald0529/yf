<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>

<link rel="stylesheet" href="./ucenter_admin/static/default/css/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="./ucenter_admin/static/default/js/libs/jquery/plugins/validator/jquery.validator.js"></script>
<script type="text/javascript" src="./ucenter_admin/static/default/js/libs/jquery/plugins/validator/local/zh_CN.js"></script>
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
.ui-input{width:200px;height:30px;}
</style>
</head>
<body>
<div class="manage-wrapper">
    <div id="manage-wrap" class="manage-wrap">
    	<form id="manage-form" action="">
		<input type="hidden" name="app_id" id = "app_id" value="">
    		<ul class="mod-form-rows base-form cf" id="base-form">
    			<li class="row-item odd">
    				<div class="label-wrap"><label for="app_name">服务名称</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="app_name" id="app_name"></div>
    			</li>
    			<li class="row-item">
    				<div class="label-wrap"><label for="app_type">服务类型</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="app_type" id="app_type"></div>
    			</li>
    			<!--<li class="row-item odd">
    				<div class="label-wrap"><label for="vendor_amount_money">顺序号</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="app_seq" id="app_seq"></div>
    			</li>-->
    			<li class="row-item odd">
    				<div class="label-wrap"><label for="app_key">服务密钥</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="app_key" id="app_key"></div>
    			</li>
    			<li class="row-item">
    				<div class="label-wrap"><label for="app_ip_list">服务 IP 列表</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="app_ip_list" id="app_ip_list"></div>
    			</li>
				<li class="row-item odd">
    				<div class="label-wrap"><label for="app_url">服务网址</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="app_url" id="app_url"></div>
    			</li>
    			<li class="row-item">
    				<div class="label-wrap"><label for="app_admin_url">后台网址</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="app_admin_url" id="app_admin_url"></div>
    			</li>
				<!--<li class="row-item odd"  style="margin-top:10px;">
					<div class="label-wrap"><label for="enable">是否启用:</label></div>
					<div class="onoff">
						<label for="enable1" class="cb-enable  ">是</label>
						<label for="enable0" class="cb-disable  selected">否</label>
						<input id="enable1"  name ="app_status"  value="1" type="radio">
						<input id="enable0"  name ="app_status"   value="0" type="radio">
					</div>
				</li>-->

    		</ul>
    </div>

</div>
 
<script src="./ucenter_admin/static/default/js/controllers/application/baseapp_edit.js"></script>

<?php
include TPL_PATH . '/'  . 'footer.php';
?>
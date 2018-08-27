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
<div class="manage-wrap" style="margin:auto;width:500px;margin-top:20px;">
		<ul class="mod-form-rows">
			<li style="margin-top:10px;">
				<div class="label-wrap"><label for="app_name">应用名称:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input w400" name="app_name" id="app_name"></div>
				<div class="ctn-wrap"><input type="hidden" value="" class="ui-input" name="app_id" id="app_id"></div>
			</li>
			<li style="margin-top:10px;">
				<div class="label-wrap"><label for="app_key">应用KEY:</label></div>
				<div class="ctn-wrap"><input type="text"  class="ui-input w400" name="app_key" id="app_key"></div>
			</li>
			<li style="margin-top:10px;">
				<div class="label-wrap"><label for="app_key">应用URL:</label></div>
				<div class="ctn-wrap"><input type="text" class="ui-input w400" name="app_url" id="app_url"></div>
			</li>
			<!--<li class="row-item"  style="margin-top:10px;">
				<div class="label-wrap"><label for="enable">是否启用:</label></div>
				<div class="onoff">
                    <label for="enable1" class="cb-enable  ">是</label>
                    <label for="enable0" class="cb-disable  selected">否</label>
                    <input id="enable1"  name ="app_status"  value="1" type="radio">
                    <input id="enable0"  name ="app_status"  checked="checked"  value="0" type="radio">
                </div>
			</li>-->
		</ul>
</div>
<!--<script>
    $.get(SITE_URL + '?ctl=Base_Cron&met=getFileName&typ=json',function(e){

    });
</script>-->
<script type="text/javascript" src="<?=$this->view->js?>/controllers/app/app_manage.js" charset="utf-8"></script>
<!--<script type="text/javascript" src="./shop_admin/static/common/js/plugins/jquery.datetimepicker.js" charset="utf-8"></script>
<script type="text/javascript" src="./shop_admin/static/common/css/jquery/plugins/datepicker/dateTimePicker.css" charset="utf-8"></script>-->
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
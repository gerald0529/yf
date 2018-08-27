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
<div style="margin:auto;width:500px;">
		<ul class="mod-form-rows mod-form-rows-reset" style="width: 500px;">
			<li class="row-item" style="float: left;width:50%;">
				<div class="label-wrap"><label for="cron_name">任务名称:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="cron_name" id="cron_name"></div>
			</li>
			<li class="row-item" style="margin-left: 50%;">
				<div class="label-wrap"><label for="cron_script">任务脚本:</label></div>
				<div class="ctn-wrap">
				    <select class="ui-input" id="cron_script" style="width: 130px;height:30px;">
				    <?php
                        if(!empty($data))
                        {
                            foreach($data as $k=>$v)
                            {
                    ?>
                                <option name="cron_script" value="<?=$v ?>"><?=$v ?></option>
				    <?php   }
                        } ?>
                    </select>
                </div>
			</li>
			<!--<li class="row-item" style="margin-left: 50%;">
				<div class="label-wrap"><label for="cron_script">任务脚本:</label></div>
				<div class="ctn-wrap"><span id="type"></span></div>
			</li>-->
			<!--<li class="row-item" style="float: left;">
				<div class="label-wrap"><label for="cron_lasttransact">上次执行时间:</label></div>
				<div class="ctn-wrap"><input type="text" id="cron_lasttransact" class="ui-input ui-datepicker-input"></div>
			</li>
			<li class="row-item" style="margin-left: 50%;">
				<div class="label-wrap"><label for="cron_nexttransact">下次执行时间:</label></div>
				<div class="ctn-wrap"><input type="text" id="cron_nexttransact" class="ui-input ui-datepicker-input"></div>
			</li>-->
			<li class="row-item" style="float: left;width:50%;">
				<div class="label-wrap"><label for="cron_minute">分:</label></div>
				<div class="ctn-wrap"><input type="text" value="*" class="ui-input" name="cron_minute" id="cron_minute"></div>
			</li>
			<li class="row-item" style="margin-left: 50%;">
				<div class="label-wrap"><label for="cron_hour">小时:</label></div>
				<div class="ctn-wrap"><input type="text" value="*" class="ui-input" name="cron_hour" id="cron_hour"></div>
			</li>
			<li class="row-item" style="float: left;width:50%;">
				<div class="label-wrap"><label for="cron_day">日:</label></div>
				<div class="ctn-wrap"><input type="text" value="*" class="ui-input" name="cron_day" id="cron_day"></div>
			</li>
			<li class="row-item" style="margin-left: 50%;">
				<div class="label-wrap"><label for="cron_month">月:</label></div>
				<div class="ctn-wrap"><input type="text" value="*" class="ui-input" name="cron_month" id="cron_month"></div>
			</li>
			<li class="row-item" style="float: left;width:50%;">
				<div class="label-wrap"><label for="cron_week">周:</label></div>
				<div class="ctn-wrap"><input type="text" value="*" class="ui-input" name="cron_week" id="cron_week"></div>
			</li>
			<li class="row-item" style="margin-left: 50%;">
				<div class="label-wrap"><label for="enable">是否启用:</label></div>
				<div class="onoff">
                    <label for="enable1" class="cb-enable">是</label>
                    <label for="enable0" class="cb-disable">否</label>
                    <input id="enable1"  name ="enable"  value="1" type="radio">
                    <input id="enable0"  name ="enable"  value="0" type="radio">
                </div>
			</li>
		</ul>
</div>
<!--<script>
    $.get(SITE_URL + '?ctl=Base_Cron&met=getFileName&typ=json',function(e){

    });
</script>-->
<script type="text/javascript" src="<?=$this->view->js?>/controllers/cron/cron_manage.js" charset="utf-8"></script>
<!--<script type="text/javascript" src="./shop_admin/static/common/js/plugins/jquery.datetimepicker.js" charset="utf-8"></script>
<script type="text/javascript" src="./shop_admin/static/common/css/jquery/plugins/datepicker/dateTimePicker.css" charset="utf-8"></script>-->
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
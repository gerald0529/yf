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

<style>
body{background: #fff;}
.manage-wrap{margin: 20px auto 10px;width: 300px;}
.manage-wrap .ui-input{width: 200px;font-size:14px;}
</style>
</head>
<body>
<div id="manage-wrap" class="manage-wrap">
	<form id="manage-form" action="#">
		<ul class="mod-form-rows">
			<li class="row-item">
				<div class="label-wrap"><label for="user_account">用户:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="user_account" id="user_account"></div>
			</li>
			<li class="row-item">
				<div class="label-wrap"><label for="user_password">密码:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="user_password" id="user_password"></div>
			</li>
			<li class="row-item">
				<div class="label-wrap row-salesman"><label for="rights_group_id">权限组:</label></div>
                <div class="ctn-wrap"><span id="rights_group_id"></span></div>
    		</li>
            <li class="row-item">
				<div class="label-wrap"><label for="subsite_id">管理站点:</label></div>
                <div class="ctn-wrap"><span id="subsite_id"></span></div>
    		</li>
			<!--<li class="row-item">
				<div class="label-wrap"><label for="user_realname">真实姓名:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="user_realname" id="user_realname"></div>
			</li>-->
		</ul>
	</form>
</div>
<script src="./shop_admin/static/default/js/controllers/user/base/manage.js"></script>
<?php
include TPL_PATH . '/'  . 'footer.php';
?>
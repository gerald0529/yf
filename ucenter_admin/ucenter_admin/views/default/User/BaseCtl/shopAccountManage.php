<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
</head>
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
<body>

<div class="manage-wrapper">
    <div id="manage-wrap" class="manage-wrap">
    	<form id="manage-form" action="">
    		<input type="hidden" value="<?=Yf_Registry::get('shop_app_id')?>" class="ui-input" name="request_app_id" id="request_app_id">
    		<input type="hidden" value="<?=request_int('cloud_type');?>" class="ui-input" name="cloud_type" id="cloud_type">
    		<ul class="mod-form-rows base-form cf" id="base-form">
    			<li class="row-item odd">
    				<div class="label-wrap"><label for="company_name">公司名称:</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="company_name" id="company_name"></div>
    			</li>

    			<li class="row-item">
    				<div class="label-wrap"><label for="company_phone">公司电话:</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="company_phone" id="company_phone"></div>
    			</li>

    			<li class="row-item odd">
    				<div class="label-wrap"><label for="contacter">联系人:</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="contacter" id="contacter"></div>
    			</li>

    			<li class="row-item">
    				<div class="label-wrap"><label for="plantform_url">访问域名:</label></div>
    				<div class="ctn-wrap"><input type="text" value="http://t2.shop.yuanfengyun.cn" class="ui-input" name="plantform_url" id="plantform_url"></div>
    			</li>
				<?php
				if (request_int('cloud_type')):
				?>
    			<li class="row-item odd">
    				<div class="label-wrap"><label for="ucenter_url">用户中心:</label></div>
    				<div class="ctn-wrap"><input type="text" value="http://t2.ucenter.yuanfengyun.cn/index.php" class="ui-input" name="ucenter_url" id="ucenter_url"></div>
    			</li>

    			<li class="row-item">
    				<div class="label-wrap"><label for="paycenter_url">支付中心:</label></div>
    				<div class="ctn-wrap"><input type="text" value="http://t2.paycenter.yuanfengyun.cn/index.php" class="ui-input" name="paycenter_url" id="paycenter_url"></div>
    			</li>
				<?php
				endif;
				?>

    			<li class="row-item odd">
    				<div class="label-wrap"><label for="sign_time">签约时间:</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="sign_time" id="sign_time"></div>
    			</li>

    			<li class="row-item">
    				<div class="label-wrap"><label for="account_num">帐号个数:</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="account_num" id="account_num"></div>
    			</li>

                <li class="row-item odd">
    				<div class="label-wrap"><label for="user_name">用户帐号:</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="user_name" id="user_name"></div>
    			</li>

    			<li class="row-item">
    				<div class="label-wrap"><label for="business_agent">业务代表:</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="business_agent" id="business_agent"></div>
    			</li>

    			<li class="row-item odd">
    				<div class="label-wrap"><label for="price">费用:</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="price" id="price"></div>
    			</li>

    			<li class="row-item">
    				<div class="label-wrap"><label for="effective_date_start">开始时间:</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="effective_date_start" id="effective_date_start"></div>
    			</li>

    			<li class="row-item odd">
    				<div class="label-wrap"><label for="effective_date_end">结束时间:</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="effective_date_end" id="effective_date_end"></div>
    			</li>
    		</ul>
    	</form>
    </div>
</div>
<script>
var cloud_type = <?=request_int('cloud_type')?>;
</script>
<script src="./ucenter_admin/static/default/js/controllers/user/base/shop_accountmanage.js"></script>
<?php
include TPL_PATH . '/'  . 'footer.php';
?>
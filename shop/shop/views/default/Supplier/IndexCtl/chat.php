<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link href="./im/im_pc/css/emoji.css" rel="stylesheet">
<link href="./im/im_pc/templates/default/css/chat.css" rel="stylesheet" type="text/css">
<link href="./im/im_pc/templates/default/css/home_login.css" rel="stylesheet" type="text/css">

<link href="./im/im_pc/templates/default/css/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<style>
	#emoji_div{
		display: block;
		margin-left: -10px;
		top:211px;
		border: 1px solid #d5e5f5;
		height: 94px;
		padding: 6px;
		position: absolute;
		width: 224px;
		z-index: 999999;
		width: 229px;
		height: 96px;
		background: #fff;
	}
</style>

<script>
	var APP_SITE_URL  = '';
	var CHAT_SITE_URL = '';
	var SHOP_SITE_URL = '';
	var connect_url   = "";

	var layout     = "";
	var act_op     = "";
	var user       = {};

	user['u_id']   = "1";
	user['u_name'] = "";
	user['s_id']   = "";
	user['s_name'] = "";
	user['avatar'] = "image/default/avatar.png";

	window.domain_root = "http://www.im-builder.com/demo1/";

	var ucenter_url = $('.ucenter_url').val();
	 var imbuilder_url = $('.imbuilder_url').val();

	 var user_name=$("#navbar_user_account").val();

	 DO_login(user_name);

</script>
<div id="navbar" class="navbar navbar-inverse navbar-fixed-top" style="display:none;">
	<div class="navbar-inner">
		<div class="container">
                    <span style="float: left;display: block;font-size: 20px;font-weight: 200;
                    padding-top: 10px;padding-right: 0px;padding-bottom: 10px;padding-left: 0px;text-shadow: 0px 0px 0px;color:#eee"><!--云通讯 IM--></span>
			<div id="navbar_login" class="nav-collapse in collapse" style="height: auto;" align="right">
				<div name="loginType" class="navbar-form pull-right" id="1">
					<input id='navbar_user_account' style="width:140px;margin-right: 5px;" type="text" value="<?=$user_name?>">
					<input type="password" id='navbar_user_password' style="width:95px;margin-right: 5px;" type="text">
					<input class="btns" type="button"  value="<?=__('登录')?>" style="line-height:20px;" />
				</div>
			</div>
		</div>
	</div>
</div>

<div style="clear: both;"></div>
<div id="web_chat_dialog" style="display: none;float:right;">
</div>
<a id="chat_login" href="javascript:void(0)" style="display: none;"></a>
<input type="hidden" name="ucenter_url" class="ucenter_url" value="<?= Yf_Registry::get('ucenter_api_url') ?>">
<input type="hidden" name="imbuilder_url" class="imbuilder_url" value="<?= Yf_Registry::get('im_api_url') ?>">
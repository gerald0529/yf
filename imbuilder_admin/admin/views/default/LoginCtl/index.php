<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>登录 - IM系统 </title>
	<link href="<?=$this->view->css?>/login.css" media="screen" rel="stylesheet" type="text/css">
	<!-- Scripts -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script src="./admin/static/default/js/libs/jquery/jquery-1.10.2.min.js"></script>
</head>

<style type="text/css">
	body {
		width: 100%;
		z-index: -10;
		padding: 0;
	}
</style>
<script type="text/javascript">
	$(document).ready(function(){
		var n=0;
		var lgBan=$(".items .item").length;
		function timeflex(){
 			if(n>=lgBan-1){
				n=-1;
			};
		n++;
		$(".items .item").css("opacity","0");
		$(".items .item").eq(n).css("opacity","1");
 	};
	setInterval(timeflex,3000)
	})
</script>
<body>
	<body style="width:100%;height:100%;">
	<div class="index">
		<div class="index-head"><p></p></div>
	</div>
	<div class="center-content">
		<div class="slogan"></div>
		<div class="login-area">
			<div class="top">
				<!--<h5 class="shadow">线下下单系统<em></em></h5>-->
				<h2 class="shadow">imbuilder</h2>
				<!-- <h6>请立即注册 或是 找回密码？</h6>-->
			</div>
			<div class="box">
				<form method="post" action="./admin.php?ctl=Login&met=login">
							<span>
								<label for="user_name">帐号</label>
								<input type="text" name="user_account"  autocomplete="off" class="input-text text" tabindex="1" value="">
							</span>

							<span>
								<label for="password">密码</label>
								<input type="password"  autocomplete="off" name="user_password" class="input-password text" tabindex="2">
							</span>
							<!--
							<span>
								<input type="text" name="yzm" class="input-code text3" autocomplete="off" title="验证码为4个字符"
									   maxlength="4" placeholder="输入验证码" id="captcha-input" tabindex="3">
								<div class="code" style="display: block;">
									<div id="captcha" class="code-img">
										<img onClick="get_randfunc(this);" style="cursor:pointer;" src='../includes/rand_func.php'/>
									</div>
								</div>
							</span>
							-->
							<span>
								<input type="submit" value="登录" class="input-button" name="">
							</span>
				</form>
				<!-- <span>
					<a class="ml15 shadow" href="/forget-password/">忘记密码？</a>
					<a class="ml5 shadow" href="/register/">新用户注册</a>
				</span> -->
			</div>
		</div>
	</div>
	<div class="back">
		<div class="items" id="items">
			<div class="item item1"></div>
			<div class="item item2"></div>
			<div class="item item3"></div>
		</div>
	</div>
	<script src="<?= $this->view->js ?>/controllers/login.js"></script>
</body>
</html>


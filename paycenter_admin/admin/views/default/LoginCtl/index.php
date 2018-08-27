<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
} ?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-stand|ie-comp">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
	<title>登录 - 支付中心 </title>
	<link href="<?= $this->view->css ?>/login.css" media="screen" rel="stylesheet" type="text/css">
	<!-- Scripts -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script src="<?= $this->view->js?>/libs/jquery/jquery-1.10.2.min.js"></script>
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
		
		if (window.parent!=window)
		{
			window.parent.location.reload();
		}
		
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
<body style="width:100%;height:100%;">
	<div class="index">
		<div class="index-head"><p></p></div>
	</div>
	<div class="center-content">
		<div class="slogan"></div>
		<div class="login-area">
			<div class="top">
				<!--<h5 class="shadow">线下下单系统<em></em></h5>-->
				<h2 class="shadow">商城系统</h2>
				<!-- <h6>请立即注册 或是 找回密码？</h6>-->
			</div>
			<div class="box">
				<form id="form1">
								<span>
									<label for="user_name">帐号</label>
									<input type="text" name="user_account" id="user_account"  autocomplete="off" class="input-text text" tabindex="1" value="">
								</span>

								<span>
									<label for="password">密码</label>
									<input type="password" autocomplete="off" name="user_password" class="input-password text" tabindex="2">
								</span>

								<span class="cf">
									<input type="text" name="yzm" class="input-code text3" autocomplete="off" title="验证码为4个字符"
										   maxlength="4" placeholder="输入验证码" id="captcha-input" tabindex="3">
									<div class="code" style="display: block;">
										<div id="captcha" class="code-img">
											<img onClick="get_randfunc(this);" style="cursor:pointer;" src='./libraries/rand_func.php'/>
										</div>
									</div>
								</span>

								<span>
									<input type="submit" value="登录" class="input-button" name="">
								</span>
					</form>
				<div class="tip" style="display: none;">
					<div class="relative">
						<div class="tip-area">
							<h5><span class="icon"></span>提示</h5>
							<div class="tip-cont"></div>
							<div class="clearfix"><a href="javascript:;" class="btn-sure">确定</a></div>

						</div>
						<a href="javascript:;" class="btn-close"></a>
					</div>
				</div>
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
	<script>
		var ajax_url = "<?= Yf_Registry::get('index_page') ?>?ctl=Login&met=login&typ=json";
		$(".input-button").click(function (){
			$.ajax({
				url: ajax_url,
				data:$("#form1").serialize(),
				success:function(a){
					if(a.status == 200)
					{
						window.location = a.data.url;
					}
					else
					{
						$(".tip").show();
						$(".tip-cont").html(a.msg);
                        if(a.msg === "验证码错误!"){
                            $('#captcha-input').val('');
                            $("#captcha").find('img').trigger("click");
                        }
					}
				}
			});
			return false;
		});

		$('.btn-close').click(function(){ $(".tip").hide(); });
		$('.btn-sure').click(function(){ $(".tip").hide(); });
        function get_randfunc(obj)
        {
            var sj = new Date();
            url = obj.src;
            obj.src = url + '?' + sj;
        }
	</script>
</body>
</html>


<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
$re_url = '';
$re_url = Yf_Registry::get('re_url');

$from = '';
$callback = $re_url;
$t = '';
$type = '';
$act= '';
$code = '';

extract($_GET);

$qq_url = sprintf('%s?ctl=Connect_Bind&met=login&callback=%s&from=%s&type=%s', Yf_Registry::get('url'), urlencode($callback) ,$from,'qq');
$wx_url = sprintf('%s?ctl=Connect_Bind&met=login&callback=%s&from=%s&type=%s', Yf_Registry::get('url'), urlencode($callback) ,$from,'weixin');
$wb_url = sprintf('%s?ctl=Connect_Bind&met=login&callback=%s&from=%s&type=%s', Yf_Registry::get('url'), urlencode($callback) ,$from,'weibo');

$connect_rows = Yf_Registry::get('connect_rows');

$qq = $connect_rows['qq']['status'];
$wx = $connect_rows['weixin']['status'];
$wb = $connect_rows['weibo']['status'];

?>



<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-touch-fullscreen" content="yes" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="msapplication-tap-highlight" content="no" />
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
	<title>登录</title>
	<link rel="stylesheet" href="<?=$this->view->css?>/index_login.css">
	<link rel="stylesheet" href="<?=$this->view->css?>/base.css">
	<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/headfoot.css" />
	<script src="<?=$this->view->js?>/jquery-1.9.1.js"></script>
    <script src="<?=$this->view->js?>/plugins/jquery.cookie.js"></script>
	<script src="<?=$this->view->js?>/respond.js"></script>
	<script src="<?=$this->view->js?>/regist.js"></script>
</head>
<body>
	<div class="login-wrap header clearfix">
        <div id="logo">
            <a href="<?=$shop_url?>" style="float:left;">
				<img src="<?= $web['site_logo'] ?>" height="60"/>
            </a>
            <b>欢迎登录</b>
        </div>

    </div>
	<div id="content">
		<div class="login-cont" style="background:<?=Web_ConfigModel::value('login_backcolor')?>">
			<div class="login-wrap login-wrap-content" style="background: url(<?=Web_ConfigModel::value('login_logo')?>) no-repeat -1px;">
				<div class="login-form">
					<div class="login-tab login-tab-r">
					<a href="javascript:history.go(-1)" class="back-pre"></a>
						<a href="javascript:void(0)" class="checked">
                            账户登录
                        </a>
                        <a href="<?=sprintf('%s?ctl=Login&act=reg&t=%s&from=%s&callback=%s', Yf_Registry::get('url'), request_string('t'), request_string('from'), urlencode(request_string('callback')))?>" class="back-to-regist" target="_blank">没有账号？去注册 》</a>
					</div>
					<div class="login-box" style="visibility: visible;">
						<div class="mt tab-h" style="display:none;">
						</div>
						<div class="msg-wrap" style="display:none;">
							<!--<div class="msg-warn"><b></b>公共场所不建议自动登录，以防账号丢失</div>-->
							<div class="msg-error"></div>
						</div>
						<div class="mc">
							<div class="form">
								<form id="formlogin" method="post" onsubmit="return false;">

									<!--<input type="hidden" name="ctl" value="Login">
									<input type="hidden" name="met" value="login">
									<input type="hidden" name="typ" value="e">-->


									<input type="hidden" name="from" class="from" value="<?php echo $from;?>">
									<input type="hidden" name="callback" class="callback" value="<?php echo urlencode($callback);?>">
									<input type="hidden" name="t" class="t" value="<?php echo $t;?>">
									<input type="hidden" name="type" class="type" value="<?php echo $type;?>">
									<input type="hidden" name="act" class="act" value="<?php echo $act;?>">
									<input type="hidden" name="code" class="code" value="<?php echo $code;?>">
									<input type="hidden" name="re_url" class="re_url" value="<?php echo $re_url;?>">

									<div class="item item-fore1">
										<label for="loginname" class="login-label name-label"></label>
                                        <span class="clear-btn clear-icon js_clear_btn"></span>
										<input id="loginname" type="text" class="itxt lo_user_account" name="user_account" tabindex="1" autocomplete="off" placeholder="邮箱/用户名/已验证手机">
										
									</div>
									<div id="entry" class="item item-fore2" style="visibility: visible;">
										<label class="login-label pwd-label" for="nloginpwd"></label>
										<input type="password" id="nloginpwd" name="user_password" class="itxt itxt-error lo_user_password" tabindex="2" autocomplete="off" placeholder="请输入您的密码">
										<span class="clear-btn eye-icon"></span>
									</div>
                                    <div id="imgCodeVerification" class="item item-fore2 hide">
                                        <label class="login-label pwd-label" for="imgCode"></label>
                                        <input id="imgCode" type="text" class="itxt" style="width: 224px;" name="img_code" tabindex="3" maxlength="4" placeholder="验证码">
                                        <img onclick="get_randfunc(this);" class="img-code2" src="./libraries/rand_func.php">
                                    </div>
									<div class="item item-fore3">
										<div class="safe">
											<span>
                                                <input id="autoLogin" name="auto_login" type="checkbox" class="yfcheckbox" tabindex="3" >
                                                <label for="">自动登录</label>
                                            </span>
											<span class="forget-pw-safe">
                                                <a href="<?=sprintf('%s?ctl=Login&act=reset&t=%s&from=%s&callback=%s', Yf_Registry::get('url'), request_string('t'), request_string('from'), urlencode(request_string('callback')))?>" class="" target="_blank" >忘记密码</a>
                                            </span>
										</div>
									</div>

									<input type="submit" style="display: none;" >

									<div class="item item-fore5">
										<div class="login-btn">
											<a href="javascript:;" onclick="loginSubmit()" class="btn-img btn-entry" id="loginsubmit" tabindex="6">登&nbsp;&nbsp;&nbsp;&nbsp;录</a>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="wap-show">
						<a href="<?=sprintf('%s?ctl=Login&act=reg&t=%s&from=%s&callback=%s', Yf_Registry::get('url'), request_string('t'), request_string('from'), urlencode(request_string('callback')))?>" target="_blank"><b></b>立即注册</a>|<a href="<?=sprintf('%s?ctl=Login&act=reset&t=%s&from=%s&callback=%s', Yf_Registry::get('url'), request_string('t'), request_string('from'), urlencode(request_string('callback')))?>" class="" target="_blank" >忘记密码</a>
					</div>
					<div class="coagent" style="display: block; visibility: visible;">
						<div class="titlea"> 其他登录方式</div>
						<ul>

							<?php if($qq == 1) {?> <!-- 1-开启 2-关闭 -->
							<li class="bg-1 qq"><a href="<?=$qq_url;?>"></a></li>
							<?php }?>

							<?php if($wx == 1){
								?>
								<li class="bg-1 wx"><a href="<?=$wx_url;?>"></a></li>
							<?php }
							if($wb == 1){
								?>
								<li class="bg-1 wb"><a href="<?=$wb_url;?>"></a></li>
							<?php }?>
							<!-- <li class="extra-r">
								<div>
									<div class="regist-link pa"><a href="<?=sprintf('%s?ctl=Login&act=reg&t=%s&from=%s&callback=%s', Yf_Registry::get('url'), request_string('t'), request_string('from'), urlencode(request_string('callback')))?>" target="_blank"><b></b>立即注册</a></div>
								</div>
							</li> -->
						</ul>
					</div>
				</div>
			</div>
			<div class="login-banner" style="background-color: #ca1933 ">
				<div class="w">
					<div id="banner-bg" class="i-inner" style=""></div>
				</div>
			</div>
			<div class="dialog-tips" id="mobile_box" style="z-index: 9999999;display: none;">
				<form id="userbindmobile" name="userbindmobile"  method="post" style="height:100%;">
				<div class="table">
					<div class="table-cell">
						<div class="tips-bd-phone">
							<h3>绑定手机</h3>
							<div class="bd-phone-area">
								<p><i class="icon"></i><span>为了您的账户安全，请绑定手机</span></p>
								<div class="bd-form">
									<dl>
										<dt>手机号：</dt>
										<dd>
											<input type="text" name="user_mobile" id="user_mobile" class="text w190" value="" onblur="checkMobile()"/>
											<p class="error must tl mrt4"></p>
										</dd>
									</dl>
									<dl>
										<dt>图形验证：</dt>
										<dd>
											<input type="text"  name="authcode" id="form-authcode" maxlength="6" class="field form-authcode w96" placeholder="请输入验证码" default="<i class=&quot;i-def&quot;></i>看不清？点击图片更换验证码">
											<img onClick="get_randfunc(this);" title="换一换" class="img-code" style="cursor:pointer;width:84px;height:40px;" src='./libraries/rand_func.php'/>
											<p class="error must tl mrt4"></p>
										</dd>
									</dl>
									<dl>
										<dt>验证码：</dt>
										<dd>
											<input type="text" name="yzm" id="yzm" class="text w96" value="" onchange="javascript:checkyzm();"/>
											<input type="button" class="btn-send wid-reset get-code" data-type="mobile" value="<?=_('发送验证码')?>" />
											<p class="error must tl mrt4"></p>
										</dd>
									</dl>
									<div><a href="javascript:;" class="btn cancel box_cancel">取消</a><a href="javascript:;" class="btn binds" id="bindmobile">绑定</a></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
	<?php
	include $this->view->getTplPath() . '/' . 'footer.php';
	?>

<script>
	$(document).ready(function() {

		$from = $(".from").val();
		$callback = $(".callback").val();
		$t = $(".t").val();
		$type = $(".type").val();
		$act = $(".act").val();
		$re_url = $(".re_url").val();

		var k = '';
		var u = '';

	});


	$("#formlogin").keydown(function(e){
		var e = e || event,
			keycode = e.which || e.keyCode;

		if(keycode == 13)
		{
			loginSubmit();
		}
	});

    //多次密码错误设置cookie
    var passwordCookie = {
        set passwordErrorCount(val) {
            $.cookie('passwordErrorCount', val);
        },
        get passwordErrorCount() {
            return $.cookie('passwordErrorCount') ? Number($.cookie('passwordErrorCount')) : 0;
        },
        check: function () {
            if (this.passwordErrorCount >= 3) {
                $("#imgCodeVerification").removeClass('hide').css('visibility', 'visible');
            }
        },
        failRecord: function () {
            this.passwordErrorCount = this.passwordErrorCount + 1;
        }
    };

    passwordCookie.check();

	//登录按钮
	function loginSubmit()
	{
		var user_account = $('.lo_user_account').val();
		var user_password = $('.lo_user_password').val();
		var auto_login = $('#autoLogin').is(':checked');

		$("#loginsubmit").html('正在登录...');

        var param = {
            "user_account": user_account,
            "user_password": user_password,
            "t": $t,
            "type": $type,
            "auto_login": auto_login
        };

        passwordCookie.passwordErrorCount >= 3 && (param.imgCode = $("#imgCode").val());

		$.post("./index.php?ctl=Login&met=login&typ=json", param,function(data) {

			if(data.status == 200)
			{
				k = data.data.k;
				u = data.data.user_id;

				//判断用户是否绑定手机号
				if(!data.data.mobile)
				{
					$("#mobile_box").show();
				}
				else
				{
					if($callback)
					{
						$.dialog.tips("登录成功", "1.5", false, false, function() {
							window.location.href = decodeURIComponent($callback) + '&us=' + encodeURIComponent(u) + '&ks=' + encodeURIComponent(k);
						});
					}
					else
					{
						window.location.href = decodeURIComponent($re_url);
					}
				}

			}else{
				$(".msg-warn").hide();
				$(".msg-error").html('<b></b>'+data.msg);
				$(".msg-wrap").show();
				$(".msg-error").show();
				$("#loginsubmit").html('登&nbsp;&nbsp;&nbsp;&nbsp;录');

				data.msg === '密码错误' && (passwordCookie.failRecord(), passwordCookie.check());
				data.msg === '验证码错误' && get_randfunc($('#imgCodeVerification').find('img').get(0));
			}
		});

	}
</script>

	<!-- 绑定手机 js -->
	<script type="text/javascript">
		var icon = '<i class="iconfont icon-exclamation-sign"></i>';

		$("#user_mobile").on('keyup', function (e) {
			var value = $(this).val();
			if(value.length >= 11)
			{
				$(".get-code").addClass('code_active');
			}
		})

		//图形验证码
		function get_randfunc(obj)
		{
			var sj = new Date();
			url = obj.src;
			obj.src = url + '?' + sj;
		}

		//验证手机
		function checkMobile()
		{
			var mobile = $("#user_mobile").val();

			if(mobile)
			{
				//先匹配是否为手机号
				if(!isNaN(mobile) && mobile.length == 11)
				{
					//验证该手机号是否被注册过
					var ajaxurl = './index.php?ctl=Login&met=checkMobile&typ=json&mobile='+mobile;
					$.ajax({
						type: "POST",
						url: ajaxurl,
						dataType: "json",
						async: false,
						success: function (respone)
						{
							if(respone.status == 250)
							{
								$('#user_mobile').siblings('.error').html('该手机号已被注册');
								mobile_check = false;
							}
							else
							{
								$('#user_mobile').siblings('.error').html('');
								mobile_check = true;
							}
						}
					});
				}else
				{
					$('#user_mobile').siblings('.error').html('请输入正确的手机号');
					mobile_check = false;
				}
			}
			else
			{
				$('#user_mobile').siblings('.error').html('');
			}

		}

		$(".btn-send").click(function(){
			$('.get-code').siblings('.error').html('');
			var obj = $("#user_mobile");
			var val = obj.val();
			var patrn = /^1\d{10}$/;
			var code = $('#form-authcode').val();

			if(mobile_check == false)
			{
				return;
			}

			if (!window.randStatus)
			{
				return;
			}

			if(!val){
				$('#user_mobile').siblings('.error').html('请输入正确的手机号');
			}
			else if(!patrn.test(val)){
				$('#user_mobile').siblings('.error').html('请输入正确的手机号');
			}
			else{
				var url = './index.php?ctl=User&met=getMobile&typ=json';
				var sj = new Date();
				var pars = 'shuiji=' + sj+'&verify_type=mobile&verify_field='+val;
				$.post(url, pars, function (data)
				{
					if(data && 200 == data.status){
						obj.removeClass('red');
						msg = "<?=_('获取手机验证码')?>";
						$(".btn-send").attr("disabled", "disabled");
						$(".btn-send").attr("readonly", "readonly");
						$("#user_mobile").attr("readonly", "readonly");
						

						var url ='./index.php?ctl=User&met=getMobileYzm&typ=json';
						var sj = new Date();
						var pars = 'shuiji=' + sj +'&mobile='+val+'&yzm='+code;
						$.post(url, pars, function (resp){
                            if(resp.status == 200){
                                t = setTimeout(countDown,1000);
                            }else{
                                $(".btn-send").attr("disabled", false);
                                $(".btn-send").attr("readonly", false);
                                $("#user_mobile").attr("readonly",false);
                            }
                        })
					}
					else{
						$('#user_mobile').siblings('.error').html('该手机已绑定了账号');
					}
				});
			}
		});
		var delayTime = 60;
		window.randStatus = true;
		function countDown()
		{
			window.randStatus = false;
			delayTime--;
			$(".btn-send").val(delayTime + "<?=_('秒后重新获取')?>");
			if (delayTime == 0) {
				delayTime = 60;
				$(".btn-send").val(msg);
				$(".btn-send").removeAttr("disabled");
				$(".btn-send").removeAttr("readonly");
				$("#user_mobile").removeAttr("disabled");
				$("#user_mobile").removeAttr("readonly");
				clearTimeout(t);
				window.randStatus = true;
			}
			else
			{
				t=setTimeout(countDown,1000);
			}
		}

		flag = false;
		function checkyzm(){
			$('.get-code').siblings('.error').html('');
			var yzm = $.trim($("#yzm").val());
			var mobile = $.trim($("#user_mobile").val());

			var obj = $(".btn-send");
			if(yzm == ''){
				$('.get-code').siblings('.error').html('请填写验证码');
				return false;
			}
			var url = './index.php?ctl=User&met=checkMobileYzm&typ=json';

			$.post(url, {'yzm':yzm,'mobile':mobile}, function(a){
				flag = false;
				if (a.status == 200)
				{
					flag = true;
				}
				else
				{
					$('.get-code').siblings('.error').html('验证码错误');
					return flag;
				}
			});
			return flag;
		}

		$(".box_cancel").click(function(){
			//退出当前登录
			window.location = './index.php?ctl=Login&met=logout';
			$("#mobile_box").hide();
		})

		$("#bindmobile").click(function(){
			var ajax_url = './index.php?ctl=User&met=editMobileInfo&typ=json';
			var yzm = $.trim($("#yzm").val());
			var mobile = $.trim($("#user_mobile").val());
			$.ajax({
				url: ajax_url,
				data:{'yzm':yzm,'user_mobile':mobile},
				success:function(a){
					if(a.status == 200)
					{
						if($callback)
						{
							$.dialog.tips("登录成功", "1.5", false, false, function() {
								window.location.href = decodeURIComponent($callback) + '&us=' + encodeURIComponent(u) + '&ks=' + encodeURIComponent(k);
							});
						}
						else
						{
							window.location.href = decodeURIComponent($re_url);
						}
					}else if(a.status == 240){
						$('.get-code').siblings('.error').html('验证码错误');
					}
					else
					{
						Public.tips.error("<?=_('操作失败！')?>");
					}
				}
			});
		});
	</script>

</body>

</html>
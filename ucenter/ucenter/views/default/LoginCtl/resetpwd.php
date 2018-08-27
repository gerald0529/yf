<html class="root61">
<?php
$re_url = '';
$re_url = Yf_Registry::get('re_url');

$from = '';
$callback = $re_url;
$t = '';
$code = '';

extract($_GET);
?>
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
	<title>找回密码</title>
	<link rel="stylesheet" href="<?=$this->view->css?>/register.css">
	<link rel="stylesheet" href="<?=$this->view->css?>/base.css">
    <link rel="stylesheet" href="<?=$this->view->css?>/tips.css">
	<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/headfoot.css" />
	<script src="<?=$this->view->js?>/jquery-1.9.1.js"></script>
	<script src="<?=$this->view->js?>/respond.js"></script>
    <script src="<?=$this->view->js?>/regist.js"></script>
</head>

<body>
<div id="form-header" class="header">
	<div class="logo-con w clearfix">
		<a href="<?=$shop_url?>" class="index_logo">
			<img src="<?= $web['site_logo'] ?>" alt="logo" height="60">
		</a>
		<div class="logo-title">找 回 密 码</div>
		<div class="have-account">已有账号 <a href="<?=sprintf('%s?ctl=Login&met=index&t=%s&from=%s&callback=%s', Yf_Registry::get('url'), request_string('t'), request_string('from'), urlencode(request_string('callback')))?>" target="_blank">请登录</a></div>
	</div>

</div>
<div class="container w">
	<div id="header"><a href="javascript:history.go(-1)" class="back-pre"></a>找回密码</div>
	<div class="main clearfix" id="form-main">
		<div class="reg-form fl">
			<form action="" id="register-form" method="post" novalidate="novalidate" onsubmit="return false;">

				<input type="hidden" name="from" class="from" value="<?php echo $from;?>">
				<input type="hidden" name="callback" class="callback" value="<?php echo urlencode($callback);?>">
				<input type="hidden" name="t" class="t" value="<?php echo $t;?>">
				<input type="hidden" name="code" class="code" value="<?php echo $code;?>">
				<input type="hidden" name="re_url" class="re_url" value="<?php echo $re_url;?>">


				<!--chrome autocomplete off bug hack-->
				<input style="display:none" name="hack">
				<input type="password" style="display:none" name="hack1">

                
				<div class="Mobile_div" style="display: <?=$mobile_display?>">
					<div class="item-phone-wrap">
                        <div class="form-item form-item-mobile" id="form-item-mobile">
                            <label class="select-country" id="select-country">手 机 号 码：
                            </label>
                            <span class="clear-btn JS-account js_clear_btn clear-icon" style="top:17px;right:110px;"></span>
                            <input type="text" id="re_user_mobile"  class="field re_user_mobile" placeholder="建议使用常用手机" maxlength="11" default="<i class=&quot;i-def&quot;></i>完成验证后，可以使用该手机登录和找回密码" onblur="checkMobile()" onfocus="showTip(this)" autocomplete="off">

                            
                            <i class="i-status"></i>
                        </div>
                        <div class="input-tip">
                            <span></span>
                        </div>
                        <div class="orEmail" style="display: <?=$both_display?>;">
                            <a href="javascript:;" onclick="orEmail()">邮箱验证</a>
                        </div>
                    </div>
				</div>
                
                <div class="Email_div" style="display: <?=$email_display?>">
                    <div class="item-phone-wrap">
						<div class="form-item form-item-email" id="form-item-email">
							<label class="select-country" id="select-country">邮 箱 地 址：
							</label>
                            <span class="clear-btn JS-account js_clear_btn clear-icon" style="top:17px;right:110px;"></span>
							<input type="text" id="re_user_email"  class="field re_user_email" placeholder="建议使用常用邮箱" default="<i class=&quot;i-def&quot;></i>完成验证后，可以使用该邮箱登录和找回密码" onblur="checkEmail()" onfocus="showTip(this)" autocomplete="off">
							
                            <i class="i-status"></i>
						</div>
						<div class="input-tip">
							<span></span>
						</div>
						<div class="orMobile" style="display: <?=$both_display?>;">
							<a href="javascript:;" onclick="orMobile()">手机验证</a>
						</div>
					</div>
                </div>
                
                <div class="form-item" id="form-item-authcode">
                    <label>验　证　码：</label>
                    <input type="text" autocomplete="off" name="authcode" id="form-authcode" maxlength="4" class="field form-authcode" placeholder="请输入验证码" default="<i class=&quot;i-def&quot;></i>看不清？点击图片更换验证码" onfocus="showTip(this)" onblur="checkCode()">
                    <img onClick="get_randfunc(this);" title="换一换" class="img-code" style="cursor:pointer;" src='./libraries/rand_func.php'/>
                </div>
                <div class="input-tip ">
                    <span></span>
                </div>
                <div class="Mobile_div" style="display: <?=$mobile_display?>">
                    <div class="form-item form-item-phonecode">
                        <label>手机验证码：</label>

                        <input type="text" name="mobileCode" maxlength="6" id="phoneCode" class="field phonecode  re_mobile" placeholder="请输入手机验证码" autocomplete="off">
                        <button id="getPhoneCode" class="btn-phonecode" type="button" onclick="get_randcode()">获取验证码</button>
                        <i class="i-status"></i>
                    </div>
                    <div class="input-tip">
                        <span></span>
                    </div>
                </div>
                
                <div class="Email_div" style="display: <?=$email_display?>">
                    <div class="form-item form-item-phonecode">
                        <label>邮箱验证码：</label>

                        <input type="text" name="emailCode" maxlength="6" id="emailCode" class="field emailcode  re_email" placeholder="请输入邮箱验证码" autocomplete="off">
                        <button id="getEmailCode" class="btn-phonecode" type="button" onclick="get_randcode()">获取验证码</button>
                        <i class="i-status"></i>
                    </div>
                </div>
				<div class="input-tip">
					<span></span>
				</div>

				<div id="form-item-password" class="form-item" style="z-index: 12;">
					<label>重 置 密 码：</label>
					<input type="password" id="re_user_password" class="field re_user_password" autocomplete="off" maxlength="20" placeholder="建议至少使用两种字符组合" default="<i class=i-def></i><?=$pwd_str?>" onfocus="checkPwd()" onblur="pwdCallback()">
					<span class="clear-btn eye-icon"></span>
                    <i class="i-status"></i>
					
				</div>
				<div class="input-tip">
					<span></span>
				</div>
				<div id="form-item-rpassword" class="form-item disb">
					<label>确 认 密 码：</label>
					<input type="password" name="form-equalTopwd" id="form-equalTopwd" class="field" placeholder="请再次输入密码" maxlength="20" default="<i class=&quot;i-def&quot;></i>请再次输入密码" onblur="checkRpwd()" onfocus="showTip(this)">
					<span class="clear-btn eye-icon"></span>
                    <i class="i-status"></i>
				</div>
				<div class="input-tip disb">
					<span></span>
				</div>

                
				<div>
					<button type="submit" class="btn-register" onclick="resetPasswdClick()">重置密码</button>
				</div>

			</form>
		</div>
		<div id="form-company" class="reg-other disb">
			<div class="phone-fast-reg">
				<a href="<?=Web_ConfigModel::value('register_logo_url')?>"><img src="<?=Web_ConfigModel::value('register_logo')?>" alt="广告位"></a>
			</div>
		</div>
	</div>
	<?php
	include $this->view->getTplPath() . '/' . 'footer.php';
	?>
</div>

<script>
	var check_type = <?=$reg_row['reg_checkcode']['config_value']?>;
	var pwdLength = <?=$reg_row['reg_pwdlength']['config_value']?>

	var form_account = $("#re_user_account");
	var form_pwd = $("#re_user_password");
	var form_rpwd = $("#form-equalTopwd");
	var form_mobile = $("#re_user_mobile");
    var form_email = $("#re_user_email");
	var form_authcode = $("#form-authcode");

	suggestsList = {};
	function get_randfunc(obj)
	{
		var sj = new Date();
		url = obj.src;
		obj.src = url + '?' + sj;
	}
	var icons = {
		def: '<i class="i-def"></i>',
		error: '<i class="i-error"></i>',
		weak: '<i class="i-pwd-weak"></i>',
		medium: '<i class="i-pwd-medium"></i>',
		strong: '<i class="i-pwd-strong"></i>'
	};

	var pwdStrength = {
		1: {
			reg: /^.*([\W_])+.*$/i,
			msg: icons.weak + '有被盗风险,建议使用字母、数字和符号两种及以上组合'
		},
		2: {
			reg: /^.*([a-zA-Z])+.*$/i,
			msg: icons.medium + '安全强度适中，可以使用三种以上的组合来提高安全强度'
		},
		3: {
			reg: /^.*([0-9])+.*$/i,
			msg: icons.strong + '你的密码很安全'
		}
	};

	var weakPwds = [
	];

	function filterKey(e) {
		var excludedKeys = [13, 9, 16, 17, 18, 20, 35, 36, 37, 38,
			39,
			40, 45, 144, 225
		];
		return $.inArray(e.keyCode, excludedKeys) !== -1;
	}

	function hideError(input, msg) {
		var item = input.parent();
		var msg = msg || input.attr('default');
		item.removeClass('form-item-error form-item-valid');
		item.next().find('span').removeClass('error').html(msg).show();
		item.next().removeClass('phone-bind-tip');
		item.removeClass('phone-binded');
            item.next().removeClass('email-bind-tip');
			item.removeClass('email-binded');
	}

	/**输入过程中处理标签的状态**/
	function onKeyupHandler(input, msg) {
		var item = input.parent();
		if (!item.hasClass('form-item-error')) {
			item.addClass('form-item-error')
		}
		item.removeClass('form-item-valid');
		item.next().find('span').addClass('error').html(msg).show();
	}

	//显示提示语
	function showTip(e)
	{
		var msg = $(e).attr('default');

		if(!$(e).parent().next().find("span").html())
		{
			$(e).parent().next().find("span").html(msg);
		}


	}

	function getStringLength(str){
		if(!str){
			return;
		}
		var bytesCount=0;
		for (var i = 0; i < str.length; i++)
		{
			var c = str.charAt(i);
			if (/^[\u0000-\u00ff]$/.test(c))
			{
				bytesCount += 1;
			}
			else
			{
				bytesCount += 2;
			}
		}
		return bytesCount;
	}


	//检测密码
	function checkPwd() {
		var msg = form_pwd.attr('default');

		var s = form_pwd.parent().next().find("span").html();
		if(!s)
		{
			form_pwd.parent().next().find("span").html(msg);
		}

		form_pwd.on('keyup', function (e) {
			var value = $(this).val();
			pwdStrengthRule(form_pwd, value);
		})
	}

	function pwdStrengthRule(element, value) {
		var level = 0;
		var typeCount=0;
		var flag = true;
		var valueLength=getStringLength(value);
		if (valueLength < pwdLength) {
				element.parent().removeClass('form-item-valid').removeClass('form-item-error');
				element.parent().next().find('span').removeClass('error').html($(element).attr('default'));
				return;
			}

		for (key in pwdStrength) {
			if (pwdStrength[key].reg.test(value)) {
				typeCount++;
			}
		}
		if(typeCount==1){
			if(valueLength>10){
				level=2;
			}else{
				level=1;
			}
		}else if(typeCount==2){
			if(valueLength<11&&valueLength>5){
				level=2;
			}
			if(valueLength>10){
				level=3;
			}
		}else if(typeCount==3){
			if(valueLength>6){
				level=3;
			}
		}

		if ($.inArray(value, weakPwds) !== -1) {
			flag = false;
			level=1;
		}

		if (flag && level > 0) {
			element.parent().removeClass('form-item-error').addClass(
				'form-item-valid');
		} else {
			element.parent().addClass('form-item-error').removeClass(
				'form-item-valid');
		}
		if (pwdStrength[level] !== undefined) {
			pwdStrength[level]>3?pwdStrength[level]=3:pwdStrength[level];
			element.parent().next().html('<span>' + pwdStrength[level].msg +
				'</span>')
		}
		return flag;
	}


	function pwdCallback()
	{
		var user_pwd = $("#re_user_password").val();
		hideError(form_pwd);

		if(user_pwd)
		{
			var flag = true;
                var reg_number = <?=$reg_row['reg_number']['config_value']?$reg_row['reg_number']['config_value']:0 ?>;
                var reg_lowercase = <?=$reg_row['reg_lowercase']['config_value']?$reg_row['reg_lowercase']['config_value']:0 ?>;
                var reg_uppercase = <?=$reg_row['reg_uppercase']['config_value']?$reg_row['reg_uppercase']['config_value']:0 ?>;
                var reg_symbols = <?=$reg_row['reg_symbols']['config_value']?$reg_row['reg_symbols']['config_value']:0 ?>;
                var reg_pwdlength = <?=$reg_row['reg_pwdlength']['config_value']?$reg_row['reg_pwdlength']['config_value']:0 ?>;
			//必须包含数字
				if(reg_number > 0)
			{
				if (/[0-9]+/.test(user_pwd))
				{
					flag = flag && true;
				}
				else
				{
					flag = flag && false;
				}
			}

			//必须小写字母
				if(reg_lowercase > 0)
			{
				if (/[a-z]+/.test(user_pwd))
				{
					flag = flag && true;
				}
				else
				{
					flag = flag && false;
				}
			}

			//必须大写字母
				if(reg_uppercase > 0)
			{
				if (/[A-Z]+/.test(user_pwd))
				{
					flag = flag && true;
				}
				else
				{
					flag = flag && false;
				}
			}

			//必须字符
				if(reg_symbols > 0)
			{
				if (/[ !@#$%^&*()_+<>]+/.test(user_pwd))
				{
					flag = flag && true;
				}
				else
				{
					flag = flag && false;
				}
			}

				if(reg_pwdlength > 0)
			{
				if (user_pwd.length >= <?=$reg_row['reg_pwdlength']['config_value']?>)
				{
					flag = flag && true;
				}
				else
				{
					flag = flag && false;
				}
			}

			if(flag)
			{
				$("#form-item-password").addClass("form-item-valid");
				$("#form-item-password").next().find("span").html("");
				$("#form-item-password").removeClass("pending");
			}else
			{
				$("#form-item-password").removeClass("pending");
				var errormsg = icons.error + "<?=$pwd_str?>";
				onKeyupHandler(form_pwd, errormsg);
			}
		}
		else
		{
			$("#form-item-password").removeClass("pending");
			$("#form-item-password").next().find("span").html("");
		}
	}

	function checkRpwd()
	{
		var rpwd = $("#form-equalTopwd").val();
		var pwd = $("#re_user_password").val();

		hideError(form_rpwd);

		if(rpwd)
		{
			if(rpwd == pwd)
			{
				$("#form-item-rpassword").addClass("form-item-valid");
				$("#form-item-rpassword").next().find("span").html("");
			}
			else
			{
				$("#form-item-rpassword").removeClass("form-item-valid");
				var errormsg = icons.error + '两次密码输入不一致';
				onKeyupHandler(form_rpwd, errormsg);
			}
		}
		else
		{
			$("#form-item-rpassword").next().find("span").html("");
		}

	}

	//验证手机
	function checkMobile()
	{
		hideError(form_mobile);

		var mobile = $("#re_user_mobile").val();
		var result = false;

		if(mobile)
		{
			//先匹配是否为手机号
			if(!isNaN(mobile) && mobile.length == 11)
			{
				$("#form-item-mobile").next().find("span").html("");
				result = true;
			}else{
				var errormsg = icons.error + '请输入正确的手机号';
				onKeyupHandler(form_mobile, errormsg);
				result = false;
			}
		}else{
			$("#form-item-mobile").next().find("span").html("");
		}

		return result;

	}
    //验证邮箱
    function checkEmail()
    {
        hideError(form_email);
        var email = $("#re_user_email").val();
        var result = false;
        
        if(email)
        {
            var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/; 
            //先匹配是否为邮箱
            if(reg.test(email))
            {
                $("#form-item-mobile").next().find("span").html("");
				result = true;
              
            }else{
                var errormsg = icons.error + '请输入正确的邮箱';
                onKeyupHandler(form_email, errormsg);
                result = false;
            }
        } else {
            $("#form-item-email").next().find("span").html("");
        }
        return result;

    }

	function checkCode()
	{
		hideError(form_authcode);
		$("#form-item-authcode").next().find("span").html("");
	}

    function get_randcode(){
		//判断图形验证码是否正确
		if(!$("#form-authcode").val())
		{
			var errormsg = icons.error + '请输入图片验证码';
			onKeyupHandler(form_authcode, errormsg);
			return;
		}
        if(check_type == 2){
			get_randcode_email();
        }else{
            get_randcode_phone();
        }   
        return ;
    }
	//获取注册验证码
	function get_randcode_phone(){
		//手机号码
		var mobile = $("#re_user_mobile").val();
		if(mobile)
		{
			if(!checkMobile())
			{
				return;
			}
		}else{
			var errormsg = icons.error + '请填写手机号';
			onKeyupHandler(form_mobile, errormsg);
			return;
		}
		 

		if (!window.randStatus)
		{
			return;
		}

		var ajaxurl = './index.php?ctl=Login&met=findPasswdCode&typ=json&mobile='+mobile+'&yzm='+$("#form-authcode").val();
		$.ajax({
			type: "POST",
			url: ajaxurl,
			dataType: "json",
			async: false,
			success: function (respone)
			{
                if(respone.status == 200){
                    window.countDown();
                    Public.tips.alert('请查看手机短信获取验证码!');
                }else if(respone.status == 230){
                    $("#form-authcode").val("");
					$(".img-code").click();
					var errormsg = icons.error + respone.msg;
					onKeyupHandler(form_authcode, errormsg);
                }else{
                    if(respone.msg && respone.msg != 'undefined'){
                        Public.tips.error(respone.msg);
                        
                    }else{
                        Public.tips.error('验证码获取失败!');
                    }
                }
			}
		});

		$('.btn-phonecode').html('重新获取验证码');

	}
	function get_randcode_email(){
		//手机号码
		var email = $("#re_user_email").val();
		if(email)
		{
			if(!checkEmail())
			{
				return;
			}
		}else{
			var errormsg = icons.error + '请填写手机号';
			onKeyupHandler(form_mobile, errormsg);
			return;
		}
	 
		if (!window.randStatus)
		{
			return;
		}

		var ajaxurl = './index.php?ctl=Login&met=findPasswdCode&typ=json&email='+email+'&yzm='+$("#form-authcode").val();
		$.ajax({
			type: "POST",
			url: ajaxurl,
			dataType: "json",
			async: false,
			success: function (respone)
			{
				if(respone.status == 200){
                    window.countDown();
                    Public.tips.alert('请查看手机短信获取验证码!');
                }else if(respone.status == 230){
                    $("#form-authcode").val("");
					$(".img-code").click();
					var errormsg = icons.error + respone.msg;
					onKeyupHandler(form_authcode, errormsg);
                }else{
                    if(respone.msg && respone.msg != 'undefined'){
                        Public.tips.error(respone.msg);
                    }else{
                        Public.tips.error('验证码获取失败!');
                    }
                }

			}
		});

		$('.btn-phonecode').html('重新获取验证码');

	}
    
	msg = "<?=_('获取验证码')?>";
	var delayTime = 60;
	window.randStatus = true;
	window.countDown = function ()
	{
		window.randStatus = false;
		delayTime--;
		$('.btn-phonecode').html(delayTime + "<?=_(' 秒后重新获取')?>");
		if (delayTime == 0) {
			delayTime = 60;
			$('.btn-phonecode').html(msg);

			clearTimeout(t);

			window.randStatus = true;
		}
		else
		{
			t=setTimeout(countDown, 1000);
		}
	}

	$("#register-form").keydown(function(e){
		var e = e || event,
			keycode = e.which || e.keyCode;

		if(keycode == 13)
		{
			resetPasswdClick();
		}
	});

	$from = $(".from").val();
	$callback = $(".callback").val();
	$t = $(".t").val();
	$re_url = $(".re_url").val();

	//重置密码
	function resetPasswdClick(){
		var user_password = $('#re_user_password').val();
		var mobile = $('#re_user_mobile').val();
        var email = $('#re_user_email').val();
        var reg_checkcode = check_type;
		var imgCode = $('#form-authcode').val();

        if (! imgCode) {
            return Public.tips.alert('请输入图形验证码');
        }

        if(reg_checkcode == 1 || reg_checkcode == 3){
            var user_code = $("#phoneCode").val();
        }else{
            var user_code = $("#emailCode").val();
        }
		$.post("./index.php?ctl=Login&met=resetPasswd&typ=json", {
			"user_password": user_password,
			"user_code": user_code,
			"mobile": mobile,
			'email': email,
			'reg_checkcode': reg_checkcode,
            imgCode: imgCode
		}, function (data) {
            if (data.status == 210) { //验证码错误
                get_randfunc($('#form-item-authcode').find('img').get(0));
                return Public.tips.alert(data.msg);
            }

			if(data.status == 200)
			{
				Public.tips.alert('重置密码成功，请妥善保管新密码！',afterreset);
				
			}else{
				$("#form-authcode").val("");
				$(".img-code").click();
				 Public.tips.alert(data.msg);
			}
		});
	}

	function afterreset(){
		window.location.href = '<?=sprintf('%s?ctl=Login&met=index&t=%s&from=%s&callback=%s', Yf_Registry::get('url'), request_string('t'), request_string('from'), urlencode(request_string('callback')))?>';
	}


	function orEmail()
	{
		$(".Email_div").show();
		$(".Mobile_div").hide();
		check_type = 2;
	}

	function orMobile()
	{
		$(".Email_div").hide();
		$(".Mobile_div").show();
		check_type = 1;
	}


</script>
</body>

</html>
<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php 
    //echo $_SERVER["QUERY_STRING"]."<br>"; #id=5
    $url_array = array();
    $url_array = explode('&', $_SERVER["QUERY_STRING"]);

    $from = '';
    $callback = '';
    foreach ($url_array as $key => $value) 
    {
        $v = substr($value, stripos($value,'=')+1);
        $k = substr($value, 0,stripos($value,'='));
        if($k == 'callback')
        {
            $callback = $v;
        }
        if($k == 'from')
        {
            $from = $v;
        }
    }

    // echo $from.'<br/>';
    // echo $callback.'<br/>';
    
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>忘记密码</title>
	<link href="<?=$this->view->css?>/login.css" media="screen" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="<?=$this->view->js?>/jquery.js"></script>
	<!-- Scripts -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta property="qc:admins" content="340166442164526151665670216375" />
</head>

<style type="text/css">
	body {
		background:#56a4f6;
		width: 100%;
		z-index: -10;
		padding: 0;
	}
</style>
<body>
<div class="login-layout" style="background:url('<?=$this->view->img?>/53.jpg') no-repeat;">

	<div class="fr login-area" style="height:400px;">
		<div class="top">
			<img src="<?=$this->view->img?>/32.jpg" width="70">
			<h2>忘记密码</h2>
		</div>
		<div class="box">
							<span>
								<label for="user_name">帐号</label>
								<input type="text" name="user_account"  autocomplete="off" class="input-text text user_account" tabindex="1" value="">
							</span>

							<span>
								<label for="password">密码</label>
								<input type="password"  autocomplete="off" name="user_password" class="input-password text user_password" tabindex="2">
							</span>

							<span>
								<label for="user_name">手机</label>
								<input type="text" name="mobile"  autocomplete="off" class="input-text text mobile" tabindex="3" value="">
							</span>

							<span>
								<input type="text" name="user_code" class="input-code text3 user_code" autocomplete="off" title="验证码为4个字符"
									   maxlength="4" placeholder="输入验证码" id="captcha-input" tabindex="4">
								<div class="code" style="display: block;">
									<div id="captcha" class="code-img">
										<a onClick="get_randfunc(this);" style="cursor:pointer;line-height:40px;color:#333333;" class="randfuc"/>获取手机验证码</a>
									</div>
								</div>
							</span>
						
							<input type="hidden" name="from" class="from" value="<?php echo $from;?>">
							<input type="hidden" name="callback" class="callback" value="<?php echo urlencode($callback);?>">
							<span>
								<input type="submit" value="确认" class="input-button submit" name="">
							</span>
			<!-- </form> -->
			<span>
				<a class="ml15 shadow" class="ml15">用户注册？</a>
				<a class="ml5 shadow" class="ml5">用户登录</a>
			</span>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
	$from = $(".from").val();
    $callback = $(".callback").val();

    if($from)
    {
    	$('.ml15').attr('href','./index.php?ctl=Login&met=regist&from='+$from+'&callback='+$callback);
    	$('.ml5').attr('href','./index.php?ctl=Login&from='+$from+'&callback='+$callback);
    }
	else
	{
		$('.ml15').attr('href','./index.php?ctl=Login&met=regist');
    	$('.ml5').attr('href','./index.php?ctl=Login');
	}

    });
    function get_randfunc($obj)
    {
    	mobile = $('.mobile').val();
    	user_name = $('.user_account').val();
    	var ajaxurl = './index.php?ctl=Login&met=findPasswdCode&typ=json&mobile='+mobile+'&user_name='+user_name;
        $.ajax({
                type: "POST",
                url: ajaxurl,
                dataType: "json",
                async: false,
                success: function (respone)
                {
                	if(respone.status == 250)
                	{
                		alert(respone.msg);
                	}else
                	{
						window.countDown();
                		alert('请查看手机短信获取验证码!');

                	}
					
                    console.info(respone);
                }
            });
			
		$('.randfuc').html('重新获取验证码');
    }

	msg = "<?=_('获取验证码')?>";
	var delayTime = 60;
	window.countDown = function ()
	{
		delayTime--;
		$('.randfuc').html(delayTime + "<?=_(' 秒后重新获取')?>");
		if (delayTime == 0) {
			delayTime = 60;
			$('.randfuc').html(msg);

			clearTimeout(t);
		}
		else
		{
			t=setTimeout(countDown, 1000);
		}
	}

    $('.submit').click(function(){
    	user_account = $('.user_account').val();
    	user_password = $('.user_password').val();
    	user_code = $('.user_code').val();
    	mobile = $('.mobile').val();
        
        $.post("./index.php?ctl=Login&met=resetPasswd&typ=json",{"user_account":user_account,"user_password":user_password,"user_code":user_code,"mobile":mobile} ,function(data) {
            console.info(data);
              if(data.status == 200)
              {
                alert('重置密码成功，请妥善保管新密码！');
                window.history.go(-1);

              }else{
                alert(data.msg);
              }
            });

    });
</script>
</body>
</html>


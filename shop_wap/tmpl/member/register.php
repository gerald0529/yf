<?php 
include __DIR__.'/../../includes/header.php';
?>
<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="msapplication-tap-highlight" content="no" />
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
    <title>会员注册</title>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
</head>

<body>
    <header id="header">
        <div class="header-wrap">
            <div class="header-l"><a href="../../index.html"><i class="home"></i></a></div>
            <div class="header-title">
                <h1>会员注册</h1>
            </div>
            <div class="header-r"> <a id="header-nav" href="login.html" class="text">登录</a> </div>
        </div>
    </header>
    <div class="nctouch-main-layout fixed-Width">
        <div class="nctouch-single-nav mb5 register-tab" style="display: none;">
            <ul>
                <li class="selected"><a href="javascript: void(0);"><i class="reg"></i>普通注册</a></li>
                <li><a href="register_mobile.html"><i class="regm"></i>手机注册</a></li>
            </ul>
        </div>
        <div class="nctouch-inp-con">
            <form action="" method="">
                <ul class="form-box">
                    <li class="form-item">
                        <h4>用&nbsp;户&nbsp;名</h4>
                        <div class="input-box">
                            <input type="text" placeholder="请输入用户名" class="inp" name="username" id="username" oninput="writeClear($(this));" />
                            <span class="input-del"></span></div>
                    </li>
                    <li class="form-item">
                        <h4>设置密码</h4>
                        <div class="input-box">
                            <input type="password" placeholder="请输入密码" class="inp" name="pwd" id="userpwd" oninput="writeClear($(this));" />
                            <span class="input-del"></span></div>
                    </li>
                    <li class="form-item">
                        <h4>确认密码</h4>
                        <div class="input-box">
                            <input type="password" placeholder="请再次输入密码" class="inp" name="password_confirm" id="password_confirm" oninput="writeClear($(this));" />
                            <span class="input-del"></span></div>
                    </li>
                    <li class="form-item">
                        <h4>手&nbsp;机&nbsp;号</h4>
                        <div class="input-box">
                            <input type="tel" placeholder="请输入手机号" class="inp" name="usermobile" id="usermobile" oninput="writeClear($(this));" maxlength="11" />
                            <span class="input-del"></span></div>
                    </li>
                    <li class="form-item">
                        <h4>验&nbsp;证&nbsp;码</h4>
                        <div class="input-box">
                            <input type="text" id="captcha" name="captcha" maxlength="4" size="10" class="inp" autocomplete="off" placeholder="输入验证码" oninput="writeClear($(this));" />
                            <span class="input-del"></span>
                        </div>
                    </li>
                </ul>
                <div class="form-notes"><a href="javascript:void(0);" class="btn id_get id_get_de" id="refister_mobile_btn" style="cursor:pointer;">获取验证码</a></div>
                <div class="form-notes">绑定手机不收任何费用，一个手机只能绑定一个账号，若需修改或解除已绑定的手机，请登录商城PC端进行操作。</div>
                <div class="remember-form">
                    <input id="checkbox" type="checkbox" checked="" class="checkbox">
                    <label for="checkbox">同意</label>
                    <a class="reg-cms" href="document.html" target="_blank">用户注册协议</a> </div>
                <div class="error-tips"></div>
                <div class="form-btn"><a href="javascript:void(0);" class="btn" id="registerbtn">注册</a></div>
            </form>
            <input type="hidden" name="referurl">
        </div>
    </div>
    <footer id="footer" class="bottom"></footer>
    
    <script type="text/javascript" src="../../js/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>
    <script type="text/javascript" src="../../js/register.js"></script>
</body>

</html>
<?php 
include __DIR__.'/../../includes/footer.php';
?>
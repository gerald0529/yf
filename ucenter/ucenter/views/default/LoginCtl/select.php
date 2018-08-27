<?php if (!defined('ROOT_PATH')) exit('No Permission');
    
    $open_user = [
        1 => _('微博'),
        2 => _('QQ'),
        3 => _('微信'),
    ];
?>
<!DOCTYPE html>
<html>
<head>
    <title>用户登录中心</title>
    <link rel="stylesheet" href="<?= $this->view->css ?>/base.css">
    <link rel="stylesheet" href="<?= $this->view->css ?>/register.css">
    <link rel="stylesheet" href="<?= $this->view->css ?>/iconfont/iconfont.css">
    <script src="<?= $this->view->js ?>/jquery.js"></script>
    <script src="<?= $this->view->js ?>/common.js"></script>
    <script type="text/javascript" src="<?= $this->view->js ?>/plugins/jquery.toastr.min.js" charset="utf-8"></script>
    <script src="<?= $this->view->js ?>/regist.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="" . />
    <style>
        .center dl dd h5 a {
            margin-left: 0;
        }
        
        .center dl dd h5 input {
        
        }
        
        .center dl dd h5 a.disappear {
            display: none;
        }
        
        #yzm {
            width: 110px;
        }
        
        #phonecode {
            width: 80px;
        }
    </style>
</head>


<body>
<!-- 头部 -->
<div id="form-header" class="header">
    <div class="logo-con w clearfix">
        <a href="<?= $shop_url ?>" class="index_logo">
            <img src="<?= $web['site_logo'] ?>" alt="logo" height="60">
        </a>
        <div class="logo-title">欢 迎 注 册</div>
        
        <div class="have-account">已有账号 <a href="<?= sprintf('%s?ctl=Login&met=index&from=%s&callback=%s', Yf_Registry::get('url'), request_string('from'), urlencode(request_string('callback'))) ?>" target="_blank">请登录</a></div>
    </div>
</div>

<!--用户注册协议-->
<div class="agreement hidden">
    <!-- 用户协议 -->
    <div class="ui-alert">
        <div class="ui-title">
            <span>商城用户注册协议</span>
            <a href="javascript:;" class="btn-close iconfont icon-cuowu"></a>
        </div>
        <div class="ui-content">
            <div class="ui-content-text">
                <?= $reg_row['reg_protocol']['config_value'] ?>
            </div>
            <div class="button-area">
                <a href="javascript:;" class="btn protocol_close">同意并继续</a>
            </div>
        </div>
    </div>
    <!-- 遮罩层 -->
    <div class="ui-mask"></div>
</div>

<!--wap-->


<div class="unbind-phone smart_wap" id='wap_login'>
    <div class="tc"><img src="<?php echo $user_info['bind_avator']; ?>" alt="" class="user-img"></div>
    <div class="text-tips">
        <dl>
            <dt>亲爱的<?php echo $open_user[$user_info['bind_type']]; ?>用户：</dt>
            <dd><?= $user_info['bind_nickname'] ?></dd>
        </dl>
        <p class="text">为了给您更好的服务，请关联一个：已绑定手机号的账号</p>
    </div>
    <div class="tc">
        <div class="to-reg">
            <h5>还没有账号？</h5>
            <div class="tc clearfix"><a href="javascript:;" class="btn" onclick="show_tab('#tab2')">快速注册</a></div>
        </div>
    </div>
    <div class="tc">
        <div class="to-bin">
            <h5>已有账号？</h5>
            <div class="tc clearfix"><a href="javascript:;" class="btn" onclick="show_tab('#tab1')">立即关联</a></div>
        </div>
    </div>

</div>
<!--end wap-->




<!-- 内容部分 -->
<div class="third-part ">
    <ul class="tab smart_pc">
        <li class="active"><a href="javascript:;">手机号绑定</a></li>
        <li><a href="javascript:;">已绑定手机，立即关联</a></li>
    </ul>
    <!-- 手机号绑定 -->
    <div class="third-part-con tab2 tabs phone-bin" style="display:none;" id='tab2'>
        <h3>Hi,<?= $user_info['bind_nickname'] ?>,欢迎登录，账号绑定后可一键登录！</h3>
        <div class="tc form-con">
            <div class="center">
                <dl class="clearfix">
                    <dt>您的用户名：</dt>
                    <dd>
                        <h5>
                            <input type="text" id="nickname" data-name="<?= $user_info['bind_nickname'] ?>" readonly="readonly" value="<?= $user_info['bind_nickname'] ?>" maxlength="30" minlength="1">
                            <span class="error"></span>
                            <div style="float: right;">
                                <a href="javascript:void(0)" class="edit-name Js_edit">修改</a>
                                <a href="javascript:void(0)" class="edit-name disappear Js_edit_ok">确定</a>
                                <a href="javascript:void(0)" class="edit-name disappear Js_edit_cancel">取消</a>
                            </div>
                        </h5>
                        <p class="input-tip">
                            <span class="error"></span>
                        </p>
                        <p class="name-tips">为了您的账户安全，请验证手机号</p>
                    </dd>
                </dl>
                <dl class="clearfix">
                    <dt>手机号码：</dt>
                    <dd>
                        <input type="text" id="re_user_mobile" class="field re_user_mobile" placeholder="建议使用常用手机" maxlength="11" default="<i class=&quot;i-def&quot;></i>完成验证后，可以使用该手机登录和找回密码" onblur="checkMobile()" autocomplete="off">
                        <p class="input-tip">
                            <span class="error"></span>
                        </p>
                    </dd>
                </dl>
                <!--隐藏的input,防止浏览器自动填充-->
                <input type="hidden">
                <dl class="clearfix">
                    <dt><?= _('图形验证码') ?>：</dt>
                    <dd>
                        <input type="text" name="yzm" id="yzm" maxlength="6" placeholder="<?= _('请输入验证码') ?>" default="<i class=&quot;i-def&quot;></i><?= _('看不清？点击图片更换验证码') ?>" onblur="checkCode()" />
                        <div style="float: right;">
                            <img onClick="get_randfunc(this);" title="<?= _('换一换') ?>" class="img-code" style="cursor:pointer;" src='./libraries/rand_func.php' />
                        </div>
                        <p class="input-tip">
                            <span class="error" id='yzm_error'></span>
                        </p>
                    </dd>
                
                </dl>
                <dl class="clearfix">
                    <dt>手机验证码：</dt>
                    <dd class="clearfix">
                        <input type="text" autocomplete="off" class="fl" id="phonecode" /><a href="javascript:;" class="btn-code-get fr " id="btn-phonecode" onclick="get_randcode_phone()"> 获 取 验 证 码 </a>
                    </dd>
                
                </dl>
                <p class="input-tip">
                    <span class="error"></span>
                </p>
                <dl class="clearfix">
                    <dt>设置密码：</dt>
                    <dd class="pas-set">
                        <input type="text" autocomplete="false" id="re_user_password" class="field re_user_password" placeholder="建议至少使用两种字符组合密码" maxlength="20" onfocus="checkPwd()" onblur="pwdCallback()" autocomplete="off">
                        <span class="clear-btn eye-icon"></span>
                        <p class="input-tip">
                            <span class="error"></span>
                        </p>
                    
                    </dd>
                </dl>
                <p class="agree"><input type="checkbox" id="xieyi"><span>我已阅读并同意<a href="javascript:;" onclick="registalert()">《用户注册协议》</a></span></p>
                <a href="javascript:;" class="btn bind-go bind uncli" onclick="registclick()">立即绑定</a>
            </div>
        </div>
    </div>
    <!-- 已绑定手机，立即关联 -->
    <div class="third-part-con tab1 tabs" id='tab1' style="display:none;">
        <h3>Hi,<?= $user_info['bind_nickname'] ?>,欢迎登录，账号绑定后可一键登录！</h3>
        
        <div class="tc form-con">
            <dl class="mrb20">
                <dt>用户名：</dt>
                <dd><input type="text" id="user_account" placeholder="请输入用户名"></dd>
            </dl>
            <dl>
                <dt>登录密码：</dt>
                <dd><input type="text" id="user_password" placeholder="请输入密码"></dd>
            </dl>
            
            <dl class="smart_wap" style="margin-top: 15px;">
                关联后，您的<?php echo $open_user[$user_info['bind_type']]; ?>账号和商城注册账号都可以登陆
            </dl>
            
            <a href="javascript:;" class="btn bind-go" onclick="bindclick()">立即绑定</a>
        </div>
    </div>

</div>

<!-- 绑定成功 -->
<div class="bind-success-tips" style="display: none;">
    <div>
        <i class="icon"></i>
        <span><strong>恭喜您！</strong>账号绑定成功</span>
        <b>3s后自动跳转到<a href="<?= $shop_url ?>">商城首页</a></b>
    
    </div>
    <a href="<?= $shop_url ?>" class="btn btn-shop-go">立即购物</a>
</div>
<script>
    $(function () {
        if ($(window).width() > 640) {
            $('#tab2').show();
        }
        
        $(".third-part .tab li").click(function () {
            var index = $(this).index();
            $(".third-part .tab li").removeClass("active");
            $(this).addClass("active");
            $(".third-part-con").hide();
            $(".third-part-con").eq(index).show();
        })
    });
    
    var form_mobile = $("#re_user_mobile");
    var form_nickname = $("#nickname");
    var form_pwd = $('#re_user_password');
    var from = '<?=$callback?>';
    var token = '<?=$token?>';
    var type = '<?=$type?>';
    
    var pwd_check = false;
    
    var pwdLength = '<?=$reg_row['reg_pwdlength']['config_value']?>';
    
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
    
    
    function show_tab(id) {
        $('#wap_login,.tabs').hide();
        $(id).show();
    }
    
    //判断验证码格式
    function checkCode() {
        if (!$('#yzm').val()) {
            $('#yzm_error').html('请输入验证码');
        } else {
            $('#yzm_error').html('');
        }
    }
    
    //点击验证码
    function get_randfunc(obj) {
        var sj = new Date();
        url = obj.src;
        obj.src = url + '?' + sj;
    }
    
    //隐藏错误提示语句
    function hideError(input, msg) {
        var item = input.next().find("span");
        item.html('');
    }
    
    //显示提示语
    function showTip(e) {
        var msg = $(e).attr('default');
        if (!$(e).next().find("span").html()) {
            $(e).next().find("span").html(msg);
        }
    }
    
    //验证手机
    function checkMobile() {
        hideError(form_mobile);
        
        var mobile = $("#re_user_mobile").val();
        
        if (mobile) {
            //先匹配是否为手机号
            if (!isNaN(mobile) && mobile.length == 11) {
                //验证该手机号是否被注册过
                var ajaxurl = './index.php?ctl=Login&met=checkMobile&typ=json&mobile=' + mobile;
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    dataType: "json",
                    async: false,
                    success: function (respone) {
                        if (respone.status == 250) {
                            var errormsg = icons.error + '该手机号已被注册';
                            form_mobile.next().find("span").html(errormsg).show();
                            
                            mobile_check = false;
                        }
                        else {
                            mobile_check = true;
                        }
                    }
                });
            } else {
                var errormsg = icons.error + '请输入正确的手机号';
                form_mobile.next().find("span").html(errormsg).show();
                mobile_check = false;
            }
        }
        else {
            form_mobile.next().find("span").html("");
        }
    }
    
    //获取注册验证码
    msg = "<?=_('获取验证码')?>";
    var delayTime = 60;
    window.randStatus = true;
    window.countDown = function () {
        window.randStatus = false;
        delayTime--;
        $('#btn-phonecode').html(delayTime + "<?=_(' 秒后重新获取')?>");
        if (delayTime == 0) {
            delayTime = 60;
            $('#btn-phonecode').html(msg);
            
            clearTimeout(t);
            
            window.randStatus = true;
        } else {
            t = setTimeout(countDown, 1000);
        }
    }
    
    function get_randcode_phone() {
        //手机号码
        var mobile = $("#re_user_mobile").val();
        
        if (mobile) {
            if (!mobile_check) {
                return;
            }
        } else {
            var errormsg = icons.error + '请填写手机号';
            form_mobile.next().find("span").html(errormsg).show();
            return;
        }
        
        if (!window.randStatus) {
            return;
        }
        
        var ajaxurl = './index.php?ctl=Login&met=regCode&typ=json&mobile=' + mobile;
        var yzm = $('#yzm').val();
        $.ajax({
            type: "POST",
            url: ajaxurl,
            dataType: "json",
            async: false,
            data: 'mobile' + mobile + '&yzm=' + yzm,
            success: function (respone) {
                if (respone.status != 200) {
                    var errormsg = icons.error + respone.msg;
                    form_mobile.next().find("span").html(errormsg).show();
                    $('.img-code').click();
                }
                else {
                    window.countDown();
                    Public.tips.alert('请查看手机短信获取验证码!');
                }
            }
        });
    }
    
    function getStringLength(str) {
        if (!str) {
            return;
        }
        var bytesCount = 0;
        for (var i = 0; i < str.length; i++) {
            var c = str.charAt(i);
            if (/^[\u0000-\u00ff]$/.test(c)) {
                bytesCount += 1;
            }
            else {
                bytesCount += 2;
            }
        }
        return bytesCount;
    }
    
    function pwdStrengthRule(element, value) {
        var level = 0;
        var typeCount = 0;
        var flag = true;
        var valueLength = getStringLength(value);
        if (valueLength < pwdLength) {
            hideError(form_pwd);
            return;
        }
        
        for (key in pwdStrength) {
            if (pwdStrength[key].reg.test(value)) {
                typeCount++;
            }
        }
        if (typeCount == 1) {
            if (valueLength > 10) {
                level = 2;
            } else {
                level = 1;
            }
        } else if (typeCount == 2) {
            if (valueLength < 11 && valueLength > 5) {
                level = 2;
            }
            if (valueLength > 10) {
                level = 3;
            }
        } else if (typeCount == 3) {
            if (valueLength > 6) {
                level = 3;
            }
        }
        
        if (pwdStrength[level] !== undefined) {
            pwdStrength[level] > 3 ? pwdStrength[level] = 3 : pwdStrength[level];
            element.next().next().html('<span class="error">' + pwdStrength[level].msg +
                '</span>')
        }
        return flag;
    }
    
    //检测密码
    function checkPwd() {
        hideError(form_pwd);
        form_pwd.on('keyup', function (e) {
            var value = $(this).val();
            pwdStrengthRule(form_pwd, value);
        })
    }
    
    function pwdCallback() {
        var user_pwd = $("#re_user_password").val();
        
        if (user_pwd) {
            var flag = true;
            var reg_number = <?=$reg_row['reg_number']['config_value'] ? $reg_row['reg_number']['config_value'] : 0 ?>;
            var reg_lowercase = <?=$reg_row['reg_lowercase']['config_value'] ? $reg_row['reg_lowercase']['config_value'] : 0 ?>;
            var reg_uppercase = <?=$reg_row['reg_uppercase']['config_value'] ? $reg_row['reg_uppercase']['config_value'] : 0 ?>;
            var reg_symbols = <?=$reg_row['reg_symbols']['config_value'] ? $reg_row['reg_symbols']['config_value'] : 0 ?>;
            var reg_pwdlength = <?=$reg_row['reg_pwdlength']['config_value'] ? $reg_row['reg_pwdlength']['config_value'] : 0 ?>;
            
            //必须包含数字
            if (reg_number > 0) {
                if (/[0-9]+/.test(user_pwd)) {
                    flag = flag && true;
                }
                else {
                    flag = flag && false;
                }
            }
            
            //必须小写字母
            if (reg_lowercase > 0) {
                if (/[a-z]+/.test(user_pwd)) {
                    flag = flag && true;
                }
                else {
                    flag = flag && false;
                }
            }
            
            //必须大写字母
            if (reg_uppercase > 0) {
                if (/[A-Z]+/.test(user_pwd)) {
                    flag = flag && true;
                }
                else {
                    flag = flag && false;
                }
            }
            
            //必须字符
            if (reg_symbols > 0) {
                if (/[ !@#$%^&*()_+<>]+/.test(user_pwd)) {
                    flag = flag && true;
                }
                else {
                    flag = flag && false;
                }
            }
            
            if (reg_pwdlength > 0) {
                if (user_pwd.length >= <?=$reg_row['reg_pwdlength']['config_value']?>) {
                    flag = flag && true;
                }
                else {
                    flag = flag && false;
                }
            }
            
            if (flag) {
                //hideError(form_pwd);
            } else {
                var errormsg = icons.error + "<?=$pwd_str?>";
                form_pwd.next().find("span").html(errormsg).show();
            }
        }
        else {
            hideError(form_pwd);
        }
    }
    
    //未勾选同意提交按钮置灰
    $("#xieyi").click(function () {
        var off=false;
        if (!$(this).is(':checked')) {
            $('.bind').addClass("uncli");
            
        }
        else {
            $(".phone-bin dd input[type='text']").each(function(){
                if($(this).val()==""){
                    off=false;
                    return false;
                }else{
                    off=true;
                }
            })
            if(off==true){
                 $('.bind').removeClass("uncli");
            }
        }
        
   })
    
    //立即绑定按钮
    function registclick() {
        //1，判断用户是否勾选了《用户注册协议》
        if (!$('#xieyi').is(':checked')) {
            // $('.bind').css('background', '#cec8c8"');
            return;
        }
        
        var mobile = $("#re_user_mobile").val();
        var user_code = $("#phonecode").val();
        var user_password = $("#re_user_password").val();
        
        //用户昵称
        var nickname = $("#nickname").val();
        
        if (nickname) {
            if (!/^.{1,30}$/.test(nickname)) {
                var errormsg = icons.error + '用户昵称为1~30个字符，请修改';
                form_nickname.parent().next().find("span").html(errormsg).show();
                return;
            }
        } else {
            var errormsg = icons.error + '请填写用户昵称';
            form_nickname.parent().next().find("span").html(errormsg).show();
            return;
        }
        
        if (!mobile) {
            var errormsg = icons.error + '请填写手机号';
            form_mobile.next().find("span").html(errormsg).show();
            return;
        }
        
        if (!user_password) {
            var errormsg = icons.error + '请设置密码';
            form_pwd.next().find("span").html(errormsg).show();
            return;
        }
        mobileerror = form_mobile.next().find("span").html();
        if (mobileerror) {
            return;
        }
        
        pwderror = form_pwd.next().find("span").html();
        if (pwderror) {
            return;
        }
        
        data = {"mobile": mobile, "code": user_code, "password": user_password, "token": token};
        
        $.post("./index.php?ctl=Login&met=bindRegist&typ=json", data, function (data) {
            console.info(data);
            if (data.status == 200) {
                $('.third-part').hide();
                $(".bind-success-tips").show();
                
                k = data.data.k;
                u = data.data.user_id;
                
                setTimeout(function () {
                    if (from) {
                        window.location.href = decodeURIComponent(from) + '&us=' + encodeURIComponent(u) + '&ks=' + encodeURIComponent(k);
                    }
                    else {
                        window.location.href = decodeURIComponent(from);
                    }
                }, 6000);
                
            } else {
                $("#phonecode").val("");
                Public.tips.alert(data.msg);
            }
        });
    }
    
    //绑定已有用户
    function bindclick() {
        var user_account = $('#user_account').val();
        var user_password = $('#user_password').val();
        
        data = {"user_account": user_account, "user_password": user_password, "token": token, "type": type};
        
        $.post("./index.php?ctl=Login&met=bindLogin&typ=json", data, function (data) {
            if (data.status == 200) {
                $('.third-part').hide();
                $(".bind-success-tips").show();
                
                k = data.data.k;
                u = data.data.user_id;
                
                setTimeout(function () {
                    if (from) {
                        window.location.href = decodeURIComponent(from) + '&us=' + encodeURIComponent(u) + '&ks=' + encodeURIComponent(k);
                    }
                    else {
                        window.location.href = decodeURIComponent(from);
                    }
                }, 6000);
                
            } else {
                Public.tips.alert(data.msg, function () {
                    data.msg == "该账号未绑定手机号！" && $($(".third-part .tab li").get(0)).trigger("click");
                });
                
            }
        });
    }
    
    //用户协议
    
    
    function registalert() {
        $(".agreement").show();
        if ($(window).height() > $(".ui-alert").height()) {
            var Top = ($(window).height() - $(".ui-alert").height()) / 2;
            $(".ui-alert").css("top", Top);
        }
        
    }
    
    function closealert() {
        $(".agreement").hide();
    }
    
    
    $(function () {
        
        $(".btn-close").click(function () {
            closealert();
        });
        
        $(".protocol_close").click(function () {
           
            closealert();
             $("#xieyi").prop('checked', true);
             $(".bind").removeClass("uncli");
        });
        
        $(".ui-mask").click(function () {
            closealert();
        });
        
        
        //修改用户名
        $(".Js_edit").on("click", function () {
            $(this).addClass("disappear");
            $(".Js_edit_ok, .Js_edit_cancel").removeClass("disappear");
            
            $("#nickname").prop("readonly", "");
            $(this).parents("h5").addClass("active");
        });
        
        $(".Js_edit_cancel").on("click", function () {
            var nick_name = $('#nickname').attr('data-name');
            $(".Js_edit").removeClass("disappear");
            $(".Js_edit_ok, .Js_edit_cancel").addClass("disappear");
            
            $("#nickname").prop("readonly", "readonly");
            $("#nickname").val(nick_name);
            $(this).parents("h5").removeClass("active");
        });
        
        $(".Js_edit_ok").on("click", function () {
            var nickname = $("#nickname").val();
            if (nickname) {
                Public.ajaxPost("./index.php?ctl=Login&met=editNickName&typ=json", {"token": token, nickname: nickname},
                    function (data) {
                        Public.tips.alert(data.msg);
                        console.log(data);
                        if (data.status == 200) {
                            $("#nickname").attr('data-name', nickname);
                            $("#nickname")
                                .prop("readonly", "readonly")
                                .parents("h5").removeClass("active");
                            
                            
                            $(".Js_edit").removeClass("disappear");
                            $(".Js_edit_ok, .Js_edit_cancel").addClass("disappear");
                        }
                    }
                )
            }
        });
    });




</script>

</body>
</html>
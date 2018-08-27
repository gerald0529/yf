<!DOCTYPE html>
<html class="root61">
<?php
$re_url = '';
$re_url = Yf_Registry::get('re_url');
$from = $_REQUEST['callback'];
$callback = $from ?: $re_url;
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
    <title>个人注册</title>
    <link rel="stylesheet" href="<?= $this->view->css ?>/register.css">
    <link rel="stylesheet" href="<?= $this->view->css ?>/base.css">
    <link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/headfoot.css" />
    <link rel="stylesheet" href="<?= $this->view->css ?>/iconfont/iconfont.css">
    <script src="<?= $this->view->js ?>/jquery-1.9.1.js"></script>
    <script type="text/javascript">
        $(function () {
            function changeDiv(firstDiv, secondDiv) {
                var temp;
                temp = firstDiv.html();
                firstDiv.html(secondDiv.html());
                secondDiv.html(temp);
            }

            if ($(window).width() < 690) {
                changeDiv($(".pas"), $(".mobile"));
            }
        })
    </script>
    <script src="<?= $this->view->js ?>/respond.js"></script>
    <script src="<?= $this->view->js ?>/regist.js"></script>
    <style type="text/css">
        .form-item .clear-btn {
            right: 10px;
        }

        /* 取消H5表单上下箭头 */
        /* 谷歌浏览器兼容性 */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none !important;
            margin: 0;
        }

        /* 火狐浏览器兼容性 */
        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body>
<!--注册/登录-->
<div id="form-header" class="header">
    <div class="logo-con w clearfix">
        <a href="<?= $shop_url ?>" class="index_logo">
            <img src="<?= $web['site_logo'] ?>" alt="logo" height="60">
        </a>
        <div class="logo-title">欢 迎 注 册</div>
        <div class="have-account">
            已有账号
            <a href="<?= sprintf('%s?ctl=Login&met=index&t=%s&from=%s&callback=%s', Yf_Registry::get('url'), request_string('t'), request_string('from'), urlencode(request_string('callback'))) ?>" target="_blank">
                请登录
            </a>
        </div>
    </div>
</div>
<!--用户协议-->
<div class="agreement hidden">
    <!-- 用户协议 -->
    <div class="ui-alert">
        <div class="ui-title">
            <span>商城用户注册协议</span>
            <a href="javascript:" class="btn-close iconfont icon-cuowu"></a>
        </div>
        <div class="ui-content">
            <div class="ui-content-text">
                <?= $reg_row['reg_protocol']['config_value'] ?>
            </div>
            <div class="button-area">
                <a href="javascript:" class="btn protocol_close">同意并继续</a>
            </div>
        </div>
    </div>
    <!-- 遮罩层 -->
    <div class="ui-mask"></div>
</div>

<div class="container w">
    <div id="header">
        <a href="javascript:history.go(-1)" class="back-pre"></a>
        手机号快速注册
        <!--<a href="<?= sprintf('%s?ctl=Login&met=index&t=%s&from=%s&callback=%s', Yf_Registry::get('url'), request_string('t'), request_string('from'), urlencode(request_string('callback'))) ?>" class="back-to-login">登录</a>-->
    </div>
    <div class="main clearfix" id="form-main">
        <div class="reg-form fl">
            <form action="" id="register-form" method="post" novalidate="novalidate" onsubmit="return false;" autocomplete="off">
                <input type="hidden" name="from" class="from" value="<?php echo $from; ?>">
                <input type="hidden" name="callback" class="callback" value="<?php echo urlencode($callback); ?>">
                <input type="hidden" name="t" class="token" value="<?php echo $t; ?>">
                <input type="hidden" name="code" class="code" value="<?php echo $code; ?>">
                <input type="hidden" name="re_url" class="re_url" value="<?php echo $re_url; ?>">
                <!--chrome autocomplete off bug hack-->
                <input style="display:none" name="hack">
                <input type="password" style="display:none" name="hack1">
                <div class="form-item form-item-account disb" id="form-item-account">
                    <label><em class="must">*</em>用　户　名：</label>
                    <span class="clear-btn js_clear_btn clear-icon"></span>
                    <input type="text" id="re_user_account" class="field re_user_account" maxlength="20" placeholder="您的账户和登录名" default="<i class=&quot;i-def&quot;></i>支持5-20位数字、字母、“_”的组合" onfocus="checkAcount()" onblur="userCallback()">
                    <i class="i-status"></i>
                </div>
                <div class="input-tip disb">
                    <span></span>
                </div>
                <div class="pas">
                    <div id="form-item-password" class="form-item" style="z-index: 12;">
                        <label><em class="must">*</em>设 置 密 码：</label>
                        <span class="close-btn js_clear_btn clear-icon"></span>
                        <input type="password" id="re_user_password" class="field re_user_password" maxlength="20" placeholder="建议至少使用两种字符组合" default="<i class=i-def></i><?= $pwd_str ?>" onfocus="checkPwd()" onblur="pwdCallback()">
                        <span class="clear-btn eye-icon"></span>
                        <i class="i-status"></i>
                    </div>
                    <div class="input-tip disb">
                        <span></span>
                    </div>
                </div>
                <div id="form-item-rpassword" class="form-item disb">
                    <label><em class="must">*</em>确 认 密 码：</label>
                    <span class="close-btn js_clear_btn clear-icon"></span>
                    <input type="password" name="form-equalTopwd" id="form-equalTopwd" class="field" placeholder="请再次输入密码" maxlength="20" default="<i class=&quot;i-def&quot;></i>请再次输入密码" onblur="checkRpwd()" onfocus="showTip(this)">
                    <span class="clear-btn eye-icon"></span>
                    <i class="i-status"></i>
                </div>
                <div class="input-tip disb">
                    <span></span>
                </div>
                <div class="mobile">
                    <div id="Mobile" style="display: <?= $mobile_display ?>">
                        <div class="item-phone-wrap">
                            <div class="form-item form-item-mobile" id="form-item-mobile">
                                <label class="select-country" id="select-country"><em class="must">*</em>手 机 号 码：</label>
                                <span class="clear-btn js_clear_btn clear-icon"></span>
                                <input type="number" oninput="if(value.length>11)value=value.slice(0,11)" id="re_user_mobile" class="field re_user_mobile" placeholder="建议使用常用手机" default="<i class=&quot;i-def&quot;></i>完成验证后，可以使用该手机登录和找回密码" onblur="checkMobile()" onfocus="showTip(this)">
                                <i class="i-status"></i>
                            </div>
                            <div class="input-tip">
                                <span></span>
                            </div>
                            <!--<div class="orEmail" style="display: <? /*=$both_display*/ ?>;">
									<a href="javascript:;" onclick="orEmail()">邮箱验证</a>
								</div>-->
                        </div>
                        <div class="form-item" id="form-item-authcode">
                            <label><em class="must">*</em>验　证　码：</label>
                            <input type="text" name="authcode" id="form-authcode" maxlength="4" class="field form-authcode" placeholder="请输入验证码" default="<i class=&quot;i-def&quot;></i>看不清？点击图片更换验证码" onfocus="showTip(this)" onblur="checkCode()">
                            <img onClick="get_randfunc(this);" title="换一换" class="img-code" style="cursor:pointer;" src='./libraries/rand_func.php' />
                        </div>
                        <div class="input-tip disb">
                            <span></span>
                        </div>
                        <div class="form-item form-item-phonecode">
                            <label><em class="must">*</em>手机验证码：</label>
                            <input type="text" name="mobileCode" maxlength="6" id="phoneCode" class="field phonecode  re_mobile" placeholder="请输入手机验证码">
                            <button id="getPhoneCode" class="btn-phonecode" type="button" onclick="get_randcode()">获取验证码</button>
                            <i class="i-status"></i>
                        </div>
                    </div>
                </div>
                <div id="Email" style="display: <?= $email_display ?>">
                    <div class="item-phone-wrap">
                        <div class="form-item form-item-email" id="form-item-email">
                            <label class="select-country" id="select-country"><em class="must">*</em>邮 箱 地 址：</label>
                            <input type="text" id="re_user_email" class="field re_user_email" placeholder="建议使用常用邮箱" default="<i class=&quot;i-def&quot;></i>完成验证后，可以使用该邮箱登录和找回密码" onblur="checkEmail()" onfocus="showTip(this)">
                            <i class="i-status"></i>
                        </div>
                        <div class="input-tip">
                            <span></span>
                        </div>
                        <div class="form-item" id="form-item-authcode">
                            <label><em class="must">*</em>验　证　码：</label>
                            <input type="text" name="authcode" id="form-authcode" maxlength="6" class="field form-authcode" placeholder="请输入验证码" default="<i class=&quot;i-def&quot;></i>看不清？点击图片更换验证码" onfocus="showTip(this)" onblur="checkCode()">
                            <img onClick="get_randfunc(this);" title="换一换" class="img-code" style="cursor:pointer;" src='./libraries/rand_func.php' />
                        </div>
                        <div class="input-tip disb">
                            <span></span>
                        </div>
                        <div class="orMobile" style="display: <?= $both_display ?>">
                            <a href="javascript:" onclick="orMobile()">手机验证</a>
                        </div>
                    </div>
                    <div class="form-item form-item-phonecode">
                        <label><em class="must">*</em>邮箱验证码：</label>
                        <input type="text" name="emailCode" maxlength="6" id="emailCode" class="field emailcode  re_email" placeholder="请输入邮箱验证码">
                        <button id="getEmailCode" class="btn-phonecode" type="button" onclick="get_randcode()">获取验证码</button>
                        <i class="i-status"></i>
                    </div>
                </div>
                <div class="input-tip">
                    <span></span>
                </div>
                <?php foreach ($reg_opt_rows as $opt_row): ?>
                    <div class="form-item  clearfix form-item-<?= $opt_row['reg_option_id'] ?>" id="form-item-<?= $opt_row['reg_option_id'] ?>">
                        <label>
                            <?php if ($opt_row['reg_option_required'] == 1) { ?>
                                <em class="must">*</em>
                            <?php } ?>
                            <?= $opt_row['reg_option_name'] ?>：
                        </label>
                        <?php if ($opt_row['option_id'] == 1): ?>
                            <select id="re_user_<?= $opt_row['reg_option_id'] ?>" name="option[<?php echo $opt_row['reg_option_id']; ?>]" class="field select-drap field-reset re_user_<?= $opt_row['reg_option_id'] ?>" maxlength="20" placeholder="<?= $opt_row['reg_option_placeholder'] ?>" default="<?= $opt_row['reg_option_placeholder'] ?>" data-datatype="<?= $opt_row['reg_option_datatype'] ?>">
                                <?php $reg_option_value_row = explode(',', $opt_row['reg_option_value']); ?>
                                <?php foreach ($reg_option_value_row as $k => $option_value) { ?>
                                    <option value="<?= $k; ?>"><?= $option_value; ?></option>
                                <?php } ?>
                            </select>
                        <?php elseif ($opt_row['option_id'] == 2): ?>
                            <?php $reg_option_value_row = explode(',', $opt_row['reg_option_value']); ?>
                            <div class="clearfix cont-area">
                                <?php foreach ($reg_option_value_row as $k => $option_value) { ?>
                                    <label class="pad0">
                                        <input type="radio" name="option[<?= $opt_row['reg_option_id']; ?>]" value="<?= $k; ?>" />
                                        <?= $option_value; ?>
                                    </label>
                                <?php } ?>
                            </div>
                        <?php elseif ($opt_row['option_id'] == 3): ?>
                            <?php $reg_option_value_row = explode(',', $opt_row['reg_option_value']); ?>
                            <div class="clearfix cont-area">
                                <?php foreach ($reg_option_value_row as $k => $option_value) { ?>
                                    <label>
                                        <input type="checkbox" name="option[<?= $opt_row['reg_option_id']; ?>]" value="<?= $k; ?>" />
                                        <?= $option_value; ?>
                                    </label>
                                <?php } ?>
                            </div>
                        <?php elseif ($opt_row['option_id'] == 4): ?>
                            <input type="text" id="re_user_<?= $opt_row['reg_option_id'] ?>" name="option[<?= $opt_row['reg_option_id']; ?>]" class="field field-reset re_user_<?= $opt_row['reg_option_id'] ?>" <?php if ($opt_row['reg_option_datatype'] == 1){ ?>maxlength="11"<?php } else { ?> minlength="15" maxlength="18"<?php } ?> placeholder="请输入<?= $opt_row['reg_option_name'] ?>" default="<?= $opt_row['reg_option_placeholder'] ?>" data-datatype="<?= $opt_row['reg_option_datatype'] ?>" onblur="checkFormatter(this)" onfocus="showTip(this)">
                        <?php elseif ($opt_row['option_id'] == 5): ?>
                            <textarea type="text" id="re_user_<?= $opt_row['reg_option_id'] ?>" name="option[<?= $opt_row['reg_option_id']; ?>]" class="field field-reset re_user_<?= $opt_row['reg_option_id'] ?>" placeholder="请输入<?= $opt_row['reg_option_name'] ?>" default="<?= $opt_row['reg_option_placeholder'] ?>" data-datatype="<?= $opt_row['reg_option_datatype'] ?>" onblur="checkFormatter(this)" onfocus="showTip(this)"></textarea>
                        <?php elseif ($opt_row['option_id'] == 6): ?>
                        <?php endif; ?>
                        <i class="i-status"></i>
                    </div>
                    <div class="input-tip"><span></span></div>

                <?php endforeach; ?>
                <div class="form-agreen">
                    <div>
                        <input id="agree_button" type="checkbox" name="agreen" checked="">我已阅读并同意
                        <a href="javascript:" id="protocol" onclick="registalert()">《用户注册协议》</a>
                    </div>
                    <div class="input-tip"><span></span></div>
                </div>
                <div>
                    <button type="submit" class="btn-register" onclick="registclick()">立即注册</button>
                </div>
            </form>
        </div>
        <div id="form-company" class="reg-other disb">
            <div class="phone-fast-reg">
                <a href="<?= Web_ConfigModel::value('register_logo_url') ?>"><img src="<?= Web_ConfigModel::value('register_logo') ?>" alt="广告位"></a>
            </div>
        </div>
    </div>
    <?php
    include $this->view->getTplPath() . '/' . 'footer.php';
    ?>
</div>
<script>
    function getUrlParam(url, name) {
        var reg = new RegExp('[?&]' + name + '=([^&#]+)');
        var query = url.match(reg);
        return query ? query[1] : "";
    }

    $('#agree_button').click(function () {
        if ($(this).is(':checked')) {
            $(this).attr('checked', true);
        } else {
            $(this).attr('checked', false);
        }
    });

    var check_type = <?=$reg_row['reg_checkcode']['config_value']?>;
    if (check_type == 3) {
        check_type = 1;
    }

    var pwdLength = '<?=$reg_row['reg_pwdlength']['config_value']?>';
    var form_account = $("#re_user_account");
    var form_pwd = $("#re_user_password");
    var form_rpwd = $("#form-equalTopwd");
    var form_mobile = $("#re_user_mobile");
    var form_email = $("#re_user_email");
    var form_authcode = $("#form-authcode");
    var user_check = false;
    var option_check = true;
    var mobile_check = false;
    var email_check = false;
    var code_check = false;
    var pwd_check = false;
    var both_pwd_check = false;
    suggestsList = {};

    function get_randfunc(obj) {
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

    var weakPwds = [];

    function filterKey(e) {
        var excludedKeys = [13, 9, 16, 17, 18, 20, 35, 36, 37, 38, 39, 40, 45, 144, 225];
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
    function showTip(e) {
        var msg = $(e).attr('default');
        if (!$(e).parent().next().find("span").html()) {
            $(e).parent().next().find("span").html(msg);
        }
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
            } else {
                bytesCount += 2;
            }
        }
        return bytesCount;
    }

    //验证用户名
    function checkAcount() {
        var msg = form_account.attr('default');
        var s = form_account.parent().next().find("span").html();
        if (!s) {
            form_account.parent().next().find("span").html(msg);
        }
        //输入过程中需要
        //判断是否有特殊字符
        //关闭重名提醒提示面板
//			var item = form_account.parent();
        var reg = /^[A-Za-z0-9_-]{5,20}$/;
        var errormsg = icons.error + '仅支持5-20位字母、数字、“_”的组合';
        form_account.on('keyup', function (e) {
            if (filterKey(e)) {
                return;
            }
            var value = $(this).val();

            hideError(form_account);
            if (value != '' && !reg.test(value)) {
                onKeyupHandler(form_account, errormsg);
            }
            //如果提示面板存在则隐藏
            if (suggestsList['username']) {
                suggestsList['username'].hide();
            }
        });
        $("#form-phone,#form-email").on('keyup', function (e) {
            if (filterKey(e)) {
                return;
            }
            var value = $(this).val();
            if (value == '') {
                hideError($(e.target));
            }
        })
    }

    /*
     * 用户名验证错误回调
     * @element  input元素
     * @repsonse 服务器返回的数据
     */
    function userCallback() {
        var user_account = $("#re_user_account").val();
        hideError(form_account);
        if (user_account) {
            var reg = /^[A-Za-z0-9_-]{5,20}$/;
            var errormsg = icons.error + '仅支持5-20位字母、数字、“_”的组合';
            if (user_account != '' && !reg.test(user_account)) {
                user_check = false;
                onKeyupHandler(form_account, errormsg);
            } else {
                // $("#form-item-account").addClass("pending");
                $.post("./index.php?ctl=Login&met=checkUserName&typ=json", {"user_name": user_account}, function (data) {
                    if (data.status == 200) {
                        $("#form-item-account").removeClass("form-item-valid");
                        // $("#form-item-account").removeClass("pending");
                        user_check = false;
                        var errormsg = icons.error + '该用户名已被使用，请重新输入';
                        onKeyupHandler(form_account, errormsg);
                    } else {
                        $("#form-item-account").addClass("form-item-valid");
                        $("#form-item-account").next().find("span").html("");
                        // $("#form-item-account").removeClass("pending");
                        user_check = true;
                    }
                });
            }
        } else {
            // $("#form-item-account").removeClass("pending");
            $("#form-item-account").next().find("span").html("");
            user_check = false;
        }
    }

    //检测密码
    function checkPwd() {
        var msg = form_pwd.attr('default');
        var s = form_pwd.parent().next().find("span").html();
        if (!s) {
            form_pwd.parent().next().find("span").html(msg);
        }

        form_pwd.on('keyup', function (e) {
            var value = $(this).val();
            // 检查输入的密码长度
            pwdStrengthRule(form_pwd, value);
            pwd_check = pwdStrengthRule(form_pwd, value);
        });
    }

    /*检测密码长度*/
    function pwdStrengthRule(element, value) {
        var level = 0;
        var typeCount = 0;
        var flag = true;
        var valueLength = getStringLength(value);

        if (valueLength < pwdLength) {
            pwd_check = false;
            element.parent().removeClass('form-item-valid').removeClass('form-item-error');
            element.parent().next().find('span').removeClass('error').html($(element).attr('default'));
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

        if ($.inArray(value, weakPwds) !== -1) {
            flag = false;
            level = 1;
        }

        if (flag && level > 0) {
            element.parent().removeClass('form-item-error').addClass('form-item-valid');
        } else {
            element.parent().addClass('form-item-error').removeClass('form-item-valid');
        }

        if (pwdStrength[level] !== undefined) {
            pwdStrength[level] > 3 ? pwdStrength[level] = 3 : pwdStrength[level];
            element.parent().next().html('<span>' + pwdStrength[level].msg + '</span>')
        }

        return flag;
    }

    /*检测密码*/
    function pwdCallback() {
        var user_pwd = $("#re_user_password").val();
        hideError(form_pwd);
        if (user_pwd) {
            var flag = true;
            var reg_number = <?=$reg_row['reg_number']['config_value'] ? $reg_row['reg_number']['config_value'] : 0 ?>;
            var reg_lowercase = <?=$reg_row['reg_lowercase']['config_value'] ? $reg_row['reg_lowercase']['config_value'] : 0 ?>;
            var reg_uppercase = <?=$reg_row['reg_uppercase']['config_value'] ? $reg_row['reg_uppercase']['config_value'] : 0 ?>;
            var reg_symbols = <?=$reg_row['reg_symbols']['config_value'] ? $reg_row['reg_symbols']['config_value'] : 0 ?>;
            var reg_pwdlength = <?=$reg_row['reg_pwdlength']['config_value'] ? $reg_row['reg_pwdlength']['config_value'] : 0 ?>;
            var reg = '';
            reg_number > 0 && (reg += '0-9');
            reg_lowercase > 0 && (reg += 'a-z');
            reg_uppercase > 0 && (reg += 'A-Z');
            reg_symbols > 0 && (reg += "~'!@#￥$%^&*()-/+_=:.。】、—,，【;；：“”’？?）（`·{}《》<>！[…");
            if (reg !== '') {
                reg = new RegExp('^[' + reg + ']+$');
                !reg.test(user_pwd) && (flag = false);
            }

            //必须包含数字
            if (reg_number > 0) {
                if (/[0-9]+/.test(user_pwd)) {
                    flag = flag && true;
                } else {
                    flag = flag && false;
                }
            }

            //必须小写字母
            if (reg_lowercase > 0) {
                if (/[a-z]+/.test(user_pwd)) {
                    flag = flag && true;
                } else {
                    flag = flag && false;
                }
            }

            //必须大写字母
            if (reg_uppercase > 0) {
                if (/[A-Z]+/.test(user_pwd)) {
                    flag = flag && true;
                } else {
                    flag = flag && false;
                }
            }

            //必须字符
            if (reg_symbols > 0) {
                if (/[~'!@#￥$%^&*()-+_=:\.。—·【】\[\]"、》《<>?？/\|`]+/.test(user_pwd)) {
                    flag = flag && true;
                } else {
                    flag = flag && false;
                }
            }

            if (reg_pwdlength > 0) {
                if (user_pwd.length >= <?=$reg_row['reg_pwdlength']['config_value']?>) {
                    flag = flag && true;
                } else {
                    flag = flag && false;
                }
            }

            if (flag) {
                pwd_check = true;
                $("#form-item-password").addClass("form-item-valid");
                $("#form-item-password").next().find("span").html("");
                $("#form-item-password").removeClass("pending");
            } else {
                pwd_check = false;
                $("#form-item-password").removeClass("pending");
                var errormsg = icons.error + "<?= $pwd_str ?>";
                onKeyupHandler(form_pwd, errormsg);
            }
        } else {
            pwd_check = false;
            $("#form-item-password").removeClass("pending");
            $("#form-item-password").next().find("span").html("");
        }
    }

    /*检测重复密码*/
    function checkRpwd() {
        var rpwd = $("#form-equalTopwd").val();
        var pwd = $("#re_user_password").val();
        hideError(form_rpwd);
        if (rpwd) {
            if (rpwd == pwd) {
                both_pwd_check = true;
                $("#form-item-rpassword").addClass("form-item-valid");
                $("#form-item-rpassword").next().find("span").html("");
            } else {
                both_pwd_check = false;
                $("#form-item-rpassword").removeClass("form-item-valid");
                var errormsg = icons.error + '两次密码输入不一致';
                onKeyupHandler(form_rpwd, errormsg);
            }
        } else {
            both_pwd_check = false;
            $("#form-item-rpassword").next().find("span").html("");
        }
    }

    //验证手机
    function checkMobile() {
        hideError(form_mobile);
        var mobile = $("#re_user_mobile").val();
        if (mobile) {
            //先匹配是否为手机号
            if (/^1[34578]\d{9}$/.test(mobile)) {
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
                            //onKeyupHandler(form_mobile, errormsg);
                            onKeyupHandler($("#re_user_mobile"), errormsg);
                            mobile_check = false;
                        } else {
                            $("#form-item-mobile").addClass("form-item-valid");
                            $("#form-item-mobile").next().find("span").html("");
                            mobile_check = true;
                        }
                    }
                });
            } else {
                var errormsg = icons.error + '请输入正确的手机号';
                onKeyupHandler(form_mobile, errormsg);
                mobile_check = false;
            }
        } else {
            $("#form-item-mobile").next().find("span").html("");
        }
    }

    //验证邮箱
    function checkEmail() {
        hideError(form_email);
        var email = $("#re_user_email").val();
        if (email) {
            var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
            //先匹配是否为邮箱
            if (reg.test(email)) {
                //验证该邮箱是否被注册过
                var ajaxurl = './index.php?ctl=Login&met=checkEmail&typ=json&email=' + email;
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    dataType: "json",
                    async: false,
                    success: function (respone) {
                        if (respone.status == 250) {
                            var errormsg = icons.error + '该邮箱已被注册';
                            onKeyupHandler(form_email, errormsg);
                            email_check = false;
                        } else {
                            $("#form-item-email").addClass("form-item-valid");
                            $("#form-item-email").next().find("span").html("");
                            email_check = true;
                        }
                    }
                });
            } else {
                var errormsg = icons.error + '请输入正确的邮箱';
                onKeyupHandler(form_email, errormsg);
                email_check = false;
            }
        } else {
            $("#form-item-email").next().find("span").html("");
        }
    }

    function checkCode() {
        hideError(form_authcode);
        $("#form-item-authcode").next().find("span").html("");
    }

    function checkFormatter(obj) {
        var checkObj = $(obj);
        hideError(checkObj);
        var datatype = parseInt(checkObj.data('datatype'));
        if (datatype) {
            var val = checkObj.val();
            if (val) {
                //规则可以封装。
                if (1 == datatype) {
                    // 截取输入的长度，控制在11位以内：
                    val.substring(0, 11);
                    //var reg = new RegExp("^\\d{11}$");
                    var reg = /^1(3|4|5|7|8)\d{9}$/;
                    //先匹配是否为手机号
                    if (reg.test(val)) {
                        checkObj.parent().next().find("span").html("");
                        option_check = true;
                    } else {
                        var msg = checkObj.attr('default');
                        var errormsg = icons.error + '请输入正确的手机号码';
                        onKeyupHandler(checkObj, errormsg);
                        option_check = false;
                    }
                } else if (2 == datatype) {
                    var reg = new RegExp("^\\d{15}|\\d{}18$");

                    //身份证
                    if (reg.test(val)) {
                        checkObj.parent().next().find("span").html("");
                        option_check = true;
                    } else {
                        var msg = checkObj.attr('default');
                        var errormsg = icons.error + '请输入合法的身份证号码';
                        onKeyupHandler(checkObj, errormsg);
                        option_check = false;
                    }
                } else if (3 == datatype) {
                    var reg = /^[0-9]*$/;
                    //数字
                    if (reg.test(val)) {
                        checkObj.parent().next().find("span").html("");
                        option_check = true;
                    } else {
                        var msg = checkObj.attr('default');
                        var errormsg = icons.error + '请输入纯数字';
                        onKeyupHandler(checkObj, errormsg);
                        option_check = false;
                    }
                } else if (4 == datatype) {
                    //字母
                    if (true) {
                        checkObj.parent().next().find("span").html("");
                        option_check = true;
                    } else {
                        var msg = checkObj.attr('default');
                        var errormsg = icons.error + '请输入字符串';
                        onKeyupHandler(checkObj, errormsg);
                        option_check = false;
                    }
                } else if (5 == datatype) {
                    var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;

                    //email
                    if (reg.test(val)) {
                        checkObj.parent().next().find("span").html("");
                        option_check = true;
                    } else {
                        var msg = checkObj.attr('default');
                        var errormsg = icons.error + '请输入正确的邮箱地址';
                        onKeyupHandler(checkObj, errormsg);
                        option_check = false;
                    }
                } else if (6 == datatype) {
                    var reg = /^([\u4E00-\u9FA5]|\w)*$/;//特殊符号
                    //先匹配是否有特殊符号
                    if (reg.test(val) && val.length <= 20) {
                        checkObj.parent().next().find("span").html("");
                        option_check = true;
                    } else {
                        var msg = checkObj.attr('default');
                        var errormsg = icons.error + '真实姓名格式有误';
                        onKeyupHandler(checkObj, errormsg);
                        option_check = false;
                    }
                }
            } else {
                checkObj.parent().next().find("span").html("");
            }
        }

    }

    function get_randcode() {

        var user_account = $('#re_user_account').val();
        if ($(".disb").css("display") == 'none' && !user_account) {
            both_pwd_check = true;
            user_check = true;
        }
        console.log(check_type);
        if (check_type == 2) {
            get_randcode_email();
        } else {
            get_randcode_phone();
        }

    }

    //获取邮箱验证码
    function get_randcode_email() {
        var email = $("#re_user_email").val();

        if (email) {
            if (!email_check) {
                return;
            }
        } else {
            var errormsg = icons.error + '请填写邮箱地址';
            onKeyupHandler(form_email, errormsg);
            return;
        }
        //验证码
        if (!$('#form-authcode').val()) {
            var errormsg = icons.error + '请输入正确的验证码';
            onKeyupHandler(form_authcode, errormsg);
            return;
        }

        if (!window.randStatus) {
            return;
        }

        var ajaxurl = './index.php?ctl=Login&met=regCode&typ=json';
        var yzm = $('#form-authcode').val();
        $.ajax({
            type: "POST",
            url: ajaxurl,
            dataType: "json",
            async: false,
            data: "yzm=" + yzm + "&email=" + email,
            success: function (respone) {
                if (respone.status == 250) {
                    $("#form-authcode").val("");
                    $(".img-code").click();
                    var errormsg = icons.error + respone.msg;
                    onKeyupHandler(form_email, errormsg);
                } else if (respone.status == 230) {
                    $("#form-authcode").val("");
                    $(".img-code").click();
                    var errormsg = icons.error + respone.msg;
                    onKeyupHandler(form_authcode, errormsg);
                } else {
                    window.countDown();
                    Public.tips.alert('请查看邮箱获取验证码!');
                    return false;
                }
            }
        });
    }

    //获取注册验证码
    function get_randcode_phone() {
        //手机号码
        var mobile = $("#re_user_mobile").val();
        if (mobile) {
            if (!mobile_check) {
                return;
            }
        } else {
            var errormsg = icons.error + '请填写手机号';
            onKeyupHandler(form_mobile, errormsg);
            return;
        }

        //验证码
        if (!$('#form-authcode').val()) {
            var errormsg = icons.error + '请输入正确的验证码';
            onKeyupHandler(form_authcode, errormsg);
            return;
        }

        if (!window.randStatus) {
            return;
        }
        if (!user_check) {
            Public.tips.alert('请确认用户名是否符合要求');
            return;
        }
        /*if (!pwd_check) {
            Public.tips.alert('请确认密码是否符合要求');
            return;
        }*/

        var ajaxurl = './index.php?ctl=Login&met=regCode&typ=json';
        var yzm = $("#form-authcode").val();
        $.ajax({
            type: "POST",
            url: ajaxurl,
            dataType: "json",
            async: false,
            data: "yzm=" + yzm + "&mobile=" + mobile,
            success: function (respone) {
                if (respone.status == 250) {
                    $("#form-authcode").val("");
                    $(".img-code").click();
                    var errormsg = icons.error + respone.msg;
                    onKeyupHandler(form_mobile, errormsg);
                } else if (respone.status == 230) {
                    $("#form-authcode").val("");
                    $(".img-code").click();
                    var errormsg = icons.error + respone.msg;
                    onKeyupHandler(form_authcode, errormsg);
                } else {
                    window.countDown();
                    Public.tips.alert('请查看手机短信获取验证码!');
                    return false;
                }
            }
        });
    }

    msg = "<?=_('获取验证码')?>";
    var delayTime = 60;
    window.randStatus = true;
    window.countDown = function () {
        window.randStatus = false;
        delayTime--;
        $('.btn-phonecode').html(delayTime + "<?=_(' 秒后重新获取')?>");
        if (delayTime == 0) {
            delayTime = 60;
            $('.btn-phonecode').html(msg);
            clearTimeout(t);
            window.randStatus = true;
        } else {
            t = setTimeout(countDown, 1000);
        }
    };

    $("#register-form").keydown(function (e) {
        var e = e || event, keycode = e.which || e.keyCode;
        if (keycode == 13) {
            registclick();
        }
    });

    var from = $(".from").val();
    var callback = $(".callback").val();
    var token = $(".token").val();
    var re_url = $(".re_url").val();

    $.fn.serializeObject = function () {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

    //注册按钮
    function registclick() {
        var user_account = $('#re_user_account').val();
        var reg_checkcode = check_type;

        if ($(".disb").css("display") == 'none' && !user_account) {
            both_pwd_check = true;
            user_check = true;
            if (reg_checkcode == 1 || reg_checkcode == 3) {
                user_account = $('#re_user_mobile').val();
            } else {
                user_account = $('#re_user_email').val();
            }
        }

        //判断是否选中我已阅读用户手册
        if (!$('#agree_button').is(':checked')) {
            Public.tips.alert('请确认是否同意用户注册协议');
            return;
        }

        if (!user_account) {
            Public.tips.alert('请输入用户名');
            return false;
        }

        if (!user_check) {
            return;
        }

        if (!pwd_check) {
            Public.tips.alert('请确认密码是否符合要求');
            return;
        }

        if (!option_check) {
            return;
        }

        if (!both_pwd_check) {
            $("#form-item-rpassword").removeClass("form-item-valid");
            var errormsg = icons.error + '两次密码输入不一致';
            onKeyupHandler(form_rpwd, errormsg);
            return;
        }

        if (reg_checkcode == 1 || reg_checkcode == 3) {
            var user_code = $("#phoneCode").val();
        } else {
            var user_code = $("#emailCode").val();
        }

        var user_password = $('#re_user_password').val();
        var mobile = $("#re_user_mobile").val();
        var email = $("#re_user_email").val();
        console.log(user_code);

        var register_obj = $("#register-form").serializeObject();

        register_obj = $.extend(register_obj, {"user_account": user_account, "user_password": user_password, "user_code": user_code, "mobile": mobile, "t": token, 'email': email, 'reg_checkcode': reg_checkcode});

        $.post("./index.php?ctl=Login&met=register&typ=json", register_obj, function (data) {
            if (data.status == 200) {
                k = data.data.k;
                u = data.data.user_id;
                if (from) {
                    window.location.href = decodeURIComponent(callback) + '&us=' + encodeURIComponent(u) + '&ks=' + encodeURIComponent(k);
                } else {
                    window.location.href = decodeURIComponent(re_url);
                }
            } else {
                /*$(".img-code").click();*/
                Public.tips.alert(data.msg);
                return false;
            }
        });

    }

    function orEmail() {
        $("#Email").show();
        $("#Mobile").hide();
        check_type = 2;
    }

    function orMobile() {
        $("#Email").hide();
        $("#Mobile").show();
        check_type = 1;
    }

    //用户协议
    function registalert() {
        $(".agreement").show();
        if ($(window).height() > $(".ui-alert").height()) {
            var Top = ($(window).height() - $(".ui-alert").height()) / 2;
            $(".ui-alert").css("top", Top);
        } else {
            $(".ui-alert").css("top", "0");
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
        });
        $(".ui-mask").click(function () {
            closealert();
        })
    });

    //验证失败小图标点击清空input内容
    $(document).on("click", ".i-status", function () {
        var $input = $(this).prev("input").val("");
        switch ($input.attr("id")) {
            case "re_user_account":
                userCallback();
                break;
            case "re_user_password":
                pwdCallback();
                break;
            case "form-equalTopwd":
                checkRpwd();
                break;
            case "form-authcode":
                checkCode();
                break;
            case "re_user_mobile":
                checkMobile();
                break;
            case "re_user_email":
                checkEmail();
                break;
        }
    });
</script>
</body>
</html>

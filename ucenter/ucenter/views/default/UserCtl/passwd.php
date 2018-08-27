<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    include $this->view->getTplPath() . '/' . 'header.php';
?>
    <link rel="stylesheet" href="<?= $this->view->css ?>/security.css">
    </div>
    <div class="form-style-layout">
        <?php if ($data['user_email_verify'] == 1 || $data['user_mobile_verify'] == 1) { ?>
            <div class="form-style">
                <div class="step">
                    <dl class="step-first">
                        <dt>1.验证身份</dt>
                    </dl>
                    <dl class="step-2 current">
                        <dt>2.<?= _('修改密码') ?></dt>
                        <dd></dd>
                    </dl>
                    <dl class="step-3">
                        <dt>3.修改完成</dt>
                        <dd></dd>
                    </dl>
                </div>
                <form id="form" name="form" action="" method="post">
                    <input type="hidden" value="<?= $op ?>" name="op">
                    <dl>
                        <dt><em>*</em>验证方式：</dt>
                        <dd>
                            <select id="type">
                                <?php if ($data['user_email_verify'] == 1 && $data['user_email']) { ?>
                                    <option value="email">邮箱验证</option>
                                <?php } ?>
                                <?php if ($data['user_mobile_verify'] == 1 && $data['user_mobile']) { ?>
                                    <option value="mobile">手机验证</option>
                                <?php } ?>
                            </select>
                        </dd>
                    </dl>
                    <?php if ($data['user_email_verify'] == 1 && $data['user_email']) { ?>
                        <div id="email">
                            <dl>
                                <dt>邮箱：</dt>
                                <dd><?= $data['user_email'] ?></dd>
                            </dl>
                        </div>
                    <?php } ?>
                    <?php if ($data['user_mobile_verify'] == 1 && $data['user_mobile']) { ?>
                        <div id="mobile" <?php if ($data['user_email_verify'] == 1 && $data['user_email']){ ?>class="fn-hidden"<?php } ?>>
                            <dl>
                                <dt>手机：</dt>
                                <dd><?= $data['user_mobile'] ?></dd>
                            </dl>
                        </div>
                    <?php } ?>
                    <dl>
                        <dt><em>*</em><?= _('图形验证码') ?>：</dt>
                        <dd>
                            <input type="text" name="img_yzm" id="img_yzm" maxlength="6" class='text w110' placeholder="<?= _('请输入验证码') ?>" default="<i class=&quot;i-def&quot;></i><?= _('看不清？点击图片更换验证码') ?>" />
                            <img onClick="get_randfunc(this);" title="<?= _('换一换') ?>" class="img-code" style="cursor:pointer;" src='./libraries/rand_func.php' />
                        </dd>
                    </dl>
                    <dl>
                        <?php if ($data['user_email_verify'] != 1) { ?>
                            <dt><em>*</em><span class="verify_type">手机</span>验证码：</dt>
                            <dd>
                                <input type="text" name="yzm" id="yzm" class="text w60" value="" />
                                <input type="button" class="send" data-type="mobile" value="获取手机验证码" />
                            </dd>
                        <?php } else { ?>
                            <dt><em>*</em><span class="verify_type">邮箱</span>验证码：</dt>
                            <dd>
                                <input type="text" name="yzm" id="yzm" class="text w60" value="" />
                                <input type="button" class="send" data-type="email" value="获取邮箱验证码" />
                            </dd>
                        <?php } ?>
                    </dl>
                    <dl>
                        <dt><em>*</em>设置密码：</dt>
                        <dd>
                            <input type="password" class="text" maxlength="30" name="password" id="password">
                            <span class="btn-eye-icon"></span><!-- 可视加active -->
                        </dd>
                        <dt><em>*</em>重复密码：</dt>
                        <dd>
                            <input type="password" class="text" maxlength="30" name="repeat_password" id="repeat_password">
                            <span class="btn-eye-icon"></span> <!-- 可视加active -->
                        </dd>
                    </dl>
                    <dl class="foot">
                        <dt>&nbsp;</dt>
                        <dd><input type="submit" value="提交" class="submit"></dd>
                    </dl>
                </form>
            </div>
            <script type="text/javascript">
                var email = "<?=$data['user_email']?>";
                var mobile = "<?=$data['user_mobile']?>";
                $("#type").change(function () {
                    val = $(this).val();
                    var text = val == "email" ? "邮箱" : "手机"
                    $("#form").find("#" + val).show().siblings("div").hide();
                    $(".send").attr("data-type", val);
                    $(".send").val("获取" + text + "验证码");
                    $(".verify_type").html(text);
                });
                var icon = '<i class="iconfont icon-exclamation-sign"></i>';
                $(".send").click(function () {
                    var type = $(this).attr("data-type");
                    var val = eval(type);
                    msg = "获取" + (type == "email" ? "邮箱" : "手机") + "验证码";
                    $(".send").attr("disabled", "disabled");
                    $(".send").attr("readonly", "readonly");
                    $("#type").attr("disabled", "disabled");
                    
                    var url = SITE_URL + '?ctl=User&met=getMobileYzm&typ=json';
                    var sj = new Date();
                    var img_yzm = $('#img_yzm').val();
//        var pars = 'shuiji=' + sj +'&type='+type +'&val='+val + '&yzm=' + img_yzm; 
                    var pars = 'shuiji=' + sj + '&mobile=' + val + '&yzm=' + img_yzm + '&type=passwd';
                    $.post(url, pars, function (data) {
                        if (data.status == 200) {
                            t = setTimeout(countDown, 1000);
                        } else {
                            $('.img-code').click();
                            $(".send").attr("disabled", false);
                            $(".send").attr("readonly", false);
                            $("#type").attr("disabled", false);
                            Public.tips.error(data.msg);
                        }
                    }, 'json');
                });
                var delayTime = 60;
                function countDown() {
                    delayTime--;
                    $(".send").val(delayTime + '秒后重新获取');
                    if (delayTime == 0) {
                        delayTime = 60;
                        $(".send").val(msg);
                        $(".send").removeAttr("disabled");
                        $(".send").removeAttr("readonly");
                        clearTimeout(t);
                    } else {
                        t = setTimeout(countDown, 1000);
                    }
                }
                $(".submit").click(function () {
                    var obj = $(".send");
                    var yzm = $.trim($("#yzm").val());
                    var password = $.trim($("#password").val());
                    var repeat_password = $.trim($("#repeat_password").val());
                    var type = $(".send").attr("data-type");
                    var val = eval(type);
                    var pars = 'yzm=' + yzm + '&type=passwd' + '&val=' + val + '&password=' + password;
                    var ajax_url = SITE_URL + '?ctl=User&met=editAllInfo&typ=json';


                    if (password !== repeat_password) {
                        Public.tips.error("<?=_('两次密码不一致，请重新输入')?>");
                        return false;
                    } else {
                        $('#form').validator({
                            ignore: ':hidden',
                            theme: 'yellow_right',
                            timely: 1,
                            stopOnError: false,
                            fields: {
                                'yzm': 'required;',
                                'password': 'required;',
                            },
                            valid: function (form) {
                                //表单验证通过，提交表单
                                $.ajax({
                                    url: ajax_url,
                                    data: pars,
                                    success: function (a) {
                                        if (a.status == 200) {
                                            $('.step-3').addClass('current');
                                            $.dialog({
                                                title: '提示',
                                                content: '操作成功',
                                                height: 100,
                                                width: 410,
                                                lock: true,
                                                drag: false,
                                                ok: function () {
                                                    location.href = SITE_URL + "?ctl=User&met=security";
                                                }
                                            })
                                        } else if (a.status == 240) {
                                            obj.addClass('red');
                                            obj.parent().find('.error').remove();
                                            $("<label class='error'>" + icon + "<?=_('验证码错误')?></label>").insertAfter(obj);
                                        } else {
                                            Public.tips.error("<?=_('操作失败')?>");
                                        }
                                    }
                                });
                            }
                        });
                    }
                });
                
                //展示密码
                function showPassword(password, trigger, active) {
                    this.passwordElement = password;
                    this.triggerElement = trigger;
                    this.activeClass = active;
                    var _this = this;
                    this.show = function () {
                        $(_this.passwordElement).prop('type', 'input');
                        $(_this.triggerElement).addClass(_this.activeClass);
                    };
                    
                    this.hide = function () {
                        $(_this.passwordElement).prop('type', 'password');
                        $(_this.triggerElement).removeClass(_this.activeClass);
                    };
                    $(this.triggerElement).on('click', function () {
                        return $(_this.triggerElement).hasClass(_this.activeClass)
                            ? _this.hide()
                            : _this.show();
                    })
                };
                
                new showPassword('#password', $('#password').parent().find('span.btn-eye-icon'), 'active');
                new showPassword('#repeat_password', $('#repeat_password').parent().find('span.btn-eye-icon'), 'active');
                
                //点击验证码
                function get_randfunc(obj) {
                    var sj = new Date();
                    url = obj.src;
                    obj.src = url + '?' + sj;
                }
            </script>
        <?php }else{ ?>
            <div class="security-tips">修改密码前必须先进行邮箱绑定或手机绑定，点击这里进行<a href="<?= Yf_Registry::get('url') ?>?ctl=User&met=security&op=mobile">手机绑定</a>或<a href="<?= Yf_Registry::get('url') ?>?ctl=User&met=security&op=email">邮箱绑定</a></div>
        <?php } ?>
    </div>
    </div>
    </div>
<?php
    include $this->view->getTplPath() . '/' . 'footer.php';
?>
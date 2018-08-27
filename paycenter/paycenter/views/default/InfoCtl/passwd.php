<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>
<?php
    include $this -> view -> getTplPath() . '/' . 'header.php';
?>
<link rel="stylesheet" href="<?= $this -> view -> css ?>/security.css">
</div>
<div class="form-style-layout wrap" style="border:none;">
    <h2 class="pc_user_about"><?= _('支付密码') ?></h2>
    <input name="from" type="hidden" id='page_from' value="<?= $from ?>"/>
    <?php if ($data['user_email'] || $data['user_mobile']) { ?>
        <div class="form-style">
            <div class="step">
                <dl class="step-first">
                    <dt>1.验证身份</dt>
                </dl>
                <dl class="step-2 current">
                    <dt>2.<?= _('支付密码') ?></dt>
                    <dd></dd>
                </dl>
                <dl class="step-3">
                    <dt>3.操作完成</dt>
                    <dd></dd>
                </dl>
            </div>
            <form id="form" name="form" action="" method="post">
                <dl>
                    <dt><em>*</em>验证方式：</dt>
                    <dd>
                        <select id="type">
                            <?php if ($data['user_email']) { ?>
                                <option value="email">邮件验证</option>
                            <?php } ?>
                            
                            <?php if ($data['user_mobile']) { ?>
                                <option value="mobile">手机验证</option>
                            <?php } ?>
                        </select>
                    </dd>
                </dl>
                
                <?php if ($data['user_email']) { ?>
                    <div id="email">
                        <dl>
                            <dt>邮箱：</dt>
                            <dd><?= $data['user_email'] ?></dd>
                        </dl>
                    </div>
                <?php } ?>
                
                <?php if ($data['user_mobile']) { ?>
                    <div id="mobile" <?php if ($data['user_email']){ ?>class="fn-hidden"<?php } ?>>
                        <dl>
                            <dt>手机：</dt>
                            <dd><?= $data['user_mobile'] ?></dd>
                        </dl>
                    </div>
                <?php } ?>
                
                <dl>
                    <dt><em>*</em><?= _('图形验证码') ?>：</dt>
                    
                    <dd>
                        <input type="text" name="img_yzm" id="img_yzm" maxlength="6" style="width:86px;vertical-align:top;" placeholder="<?= _('请输入验证码') ?>" default="<i class=&quot;i-def&quot;></i><?= _('看不清？点击图片更换验证码') ?>"/>
                        &nbsp;&nbsp;&nbsp;
                        <img onClick="get_randfunc(this);" title="<?= _('换一换') ?>" class="img-code" style="cursor:pointer;" src='./libraries/rand_func.php'/>
                    
                    </dd>
                
                </dl>
                <dl>
                    <?php if ($data['user_email']) { ?>
                        <dt id="email_code_dt"><em>*</em><?= __('邮箱验证码') ?>：</dt>
                    <?php } ?>
                    <?php if ($data['user_mobile']) { ?>
                        <dt id="mobile_code_dt" <?php if ($data['user_email']){ ?>style="display: none;"<?php } ?>><em>*</em><?= __('手机验证码') ?>：</dt>
                    <?php } ?>
                    <dd>
                        <input type="text" name="yzm" id="yzm" class="text w82" value=""/>
                        <?php if (empty($data['user_email'])) { ?>
                            <input type="button" class="send" data-type="mobile" value="获取手机验证码"/>
                        <?php } else { ?>
                            <input type="button" class="send" data-type="email" value="获取邮箱验证码"/>
                        <?php } ?>
                    </dd>
                </dl>
                
                
                <dl>
                    <dt><em>*</em>设置密码：</dt>
                    <dd>
                        <input type="password" class="text" maxlength="30" name="password" id="password">
                    </dd>
                    <dt><em>*</em>重复密码：</dt>
                    <dd>
                        <input type="password" class="text" maxlength="30" name="repeat_password" id="repeat_password">
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
                $("#form").find("#" + val).show().siblings("div").hide();
                $(".send").attr("data-type", val);
                $(".send").val("获取" + (val == "email" ? "邮件" : "手机") + "验证码");
                if (val == "email") {
                    $("#mobile_code_dt").hide();
                    $("#email_code_dt").show();
                } else {
                    $("#email_code_dt").hide();
                    $("#mobile_code_dt").show();
                }
            });
            var icon = "<i class=\"iconfont icon-exclamation-sign\"></i>";
            $(".send").click(function () {
                var type = $(this).attr("data-type");
                var val = eval(type);
                msg = "获取" + (type == "email" ? "邮件" : "手机") + "验证码";
                $(".send").attr("disabled", "disabled");
                $(".send").attr("readonly", "readonly");
                $("#type").attr("disabled", "disabled");
                
                var url = SITE_URL + "?ctl=Info&met=getYzm&typ=json";
                var sj = new Date();
                var img_yzm = $("#img_yzm").val();
                var pars = "shuiji=" + sj + "&type=" + type + "&val=" + val + "&yzm=" + img_yzm;
                $.post(url, pars, function (data) {
                    if (data.status == 200) {
                        t = setTimeout(countDown, 1000);
                    } else {
                        $(".img-code").click();
                        $(".send").attr("disabled", false);
                        $(".send").attr("readonly", false);
                        $("#type").attr("disabled", false);
                        Public.tips.error(data.msg);
                    }
                }, "json");
                
            });
            var delayTime = 60;
            
            function countDown() {
                delayTime--;
                $(".send").val(delayTime + "秒后重新获取");
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
            // 请问你这里是不是想做  单次请求?
            flag = false;
            
            $(".submit").click(function () {
                var obj = $(".send");
                var yzm = $.trim($("#yzm").val());
                var password = $.trim($("#password").val());
                var repeat_password = $.trim($("#repeat_password").val());
                var type = $(".send").attr("data-type");
                var val = eval(type);
                var pars = "yzm=" + yzm + "&type=passwd" + "&val=" + val + "&password=" + password;
                var ajax_url = SITE_URL + "?ctl=Info&met=editAllInfo&typ=json";
                var from = $("#page_from").val();
                flag = true;
                if (!password) {
                    Public.tips.error("密码不能为空！");
                    return false;
                } else if (password.length < 5 || password.length > 20) {
                    Public.tips.error("<?=_('密码中必须包含6-20个字符。')?>");
                    return false;
                }
                if (password !== repeat_password) {
                    Public.tips.error("<?=_('两次密码不一致，请重新输入')?>");
                    return false;
                } else {
                    $("#form").validator({
                        ignore: ":hidden",
                        theme: "yellow_right",
                        timely: 1,
                        stopOnError: false,
                        fields: {
                            "yzm": "required;",
                            "password": "required;"
                        },
                        valid: function (form) {
                            //表单验证通过，提交表单
                            if (flag){
                                $.ajax({
                                    url: ajax_url,
                                    data: pars,
                                    success: function (a) {
                                        flag = false;
                                        if (a.status == 200) {
                                            $(".step-3").addClass("current");
                                            $.dialog({
                                                title: "提示",
                                                content: "操作成功",
                                                height: 100,
                                                width: 410,
                                                lock: true,
                                                drag: false,
                                                ok: function () {
                                                    if (from == "bt") {
                                                        // alert(1);
                                                        location.href = "<?= Yf_Registry ::get('url');?>?ctl=Info&met=btinfo";
                                                    } else {
                                                        // alert(2);
                                                        location.href = BASE_URL;
                                                    }
                                                }
                                            });
                                        } else if (a.status == 240) {
                                            obj.addClass("red");
                                            obj.parent().find(".error").remove();
                                            $("<label class='error'>" + icon + "<?=_('验证码错误')?></label>").insertAfter(obj);
                                        } else {
                                            $.dialog({
                                                title: "提示",
                                                content: "密码修改失败",
                                                height: 100,
                                                width: 410,
                                                lock: true,
                                                drag: false,
                                                ok: function () {
                        
                                                }
                                            });
                                        }
                                    }
                                });
                            }
                        }
                    });
                }
            });
            
            //点击验证码
            function get_randfunc(obj) {
                var sj = new Date();
                url = obj.src;
                obj.src = url + "?" + sj;
            }
        </script>
    <?php }else{ ?>
        <div class="security-tips">修改密码前必须先进行邮箱绑定或手机绑定，点击这里进行<a href="<?= Yf_Registry ::get('ucenter_api_url') ?>?ctl=User&met=security&op=mobile">手机绑定</a>或<a href="<?= Yf_Registry ::get('ucenter_api_url') ?>?ctl=User&met=security&op=email">邮箱绑定</a></div>
    <?php } ?>
</div>
<?php
    include $this -> view -> getTplPath() . '/' . 'footer.php';
?>

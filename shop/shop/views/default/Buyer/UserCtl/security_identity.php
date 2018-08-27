<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
    </div>
    <div class="form-style-layout">
        <h2><?= __('修改') ?><?= $name ?></h2>
        <?php if ($data['user_email_verify'] == 1 || $data['user_mobile_verify'] == 1) { ?>
            <div class="form-style">
                <div class="step">
                    <dl class="step-first current">
                        <dt><?= __('1.验证身份') ?></dt>
                    </dl>
                    <dl>
                        <dt><?= __('2.修改') ?><?= $name ?></dt>
                        <dd></dd>
                    </dl>
                    <dl>
                        <dt><?= __('3.修改完成') ?></dt>
                        <dd></dd>
                    </dl>
                </div>
                <form id="form" name="form" action="" method="post">
                    <input type="hidden" value="<?= $op ?>" name="op">
                    <dl>
                        <dt><em>*</em><?= __('验证方式：') ?></dt>
                        <dd>
                            <select id="type">
                                <?php if ($data['user_email_verify'] == 1 && $data['user_email']) { ?>
                                    <option value="email"><?= __('邮件验证') ?></option>
                                <?php } ?>
                                
                                <?php if ($data['user_mobile_verify'] == 1 && $data['user_mobile']) { ?>
                                    <option value="mobile"><?= __('手机验证') ?></option>
                                <?php } ?>
                            </select>
                        </dd>
                    </dl>
                    
                    <?php if ($data['user_email_verify'] == 1 && $data['user_email']) { ?>
                        <div id="email">
                            <dl>
                                <dt><?= __('邮箱：') ?></dt>
                                <dd><?= $data['user_email'] ?></dd>
                            </dl>
                        </div>
                    <?php } ?>
                    
                    <?php if ($data['user_mobile_verify'] == 1 && $data['user_mobile']) { ?>
                        <div id="mobile" <?php if ($data['user_email_verify'] == 1 && $data['user_email']){ ?>class="fn-hidden"<?php } ?>>
                            <dl>
                                <dt><?= __('手机：') ?></dt>
                                <dd><?= $data['user_mobile'] ?></dd>
                            </dl>
                        </div>
                    <?php } ?>
                    
                    <dl>
                        <dt><em>*</em><?= __('验证码：') ?></dt>
                        <dd>
                            <input type="text" name="yzm" id="yzm" class="text w60" value="" onchange="checkyzm();" />
                            <?php if ($data['user_email_verify'] != 1) { ?>
                                <input type="button" class="send" data-type="mobile" value="<?= __('获取手机验证码') ?>" />
                            <?php } else { ?>
                                <input type="button" class="send" data-type="email" value="<?= __('获取邮箱验证码') ?>" />
                            <?php } ?>
                        </dd>
                    </dl>
                    
                    <dl class="foot">
                        <dt>&nbsp;</dt>
                        <dd><input type="submit" value="<?= __('提交') ?>" class="submit"></dd>
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
                    $(".send").val("<?=__('获取')?>" + (val == "email" ? "<?=__('邮件')?>" : "<?=__('手机')?>") + "<?=__('验证码')?>");
                });
                var icon = '<i class="iconfont icon-exclamation-sign"></i>';
                $(".send").click(function () {
                    var type = $(this).attr("data-type");
                    var val = eval(type);
                    msg = "<?=__('获取')?>" + (type == "email" ? "<?=__('邮件')?>" : "<?=__('手机')?>") + "<?=__('验证码')?>";
                    $(".send").attr("disabled", "disabled");
                    $(".send").attr("readonly", "readonly");
                    $("#type").attr("disabled", "disabled");
                    t = setTimeout(countDown, 1000);
                    var url = SITE_URL + '?ctl=Buyer_User&met=getYzm&typ=json';
                    var sj = new Date();
                    var pars = 'shuiji=' + sj + '&type=' + type + '&val=' + val;
                    $.post(url, pars, function (data) {
                    })
                });
                var delayTime = 60;
                
                function countDown() {
                    delayTime--;
                    $(".send").val(delayTime + '<?=__('秒后重新获取')?>');
                    if (delayTime == 0) {
                        delayTime = 60;
                        $(".send").val(msg);
                        $(".send").removeAttr("disabled");
                        $(".send").removeAttr("readonly");
                        clearTimeout(t);
                    }
                    else {
                        t = setTimeout(countDown, 1000);
                    }
                }
                
                flag = false;
                
                function checkyzm() {
                    $("label.error").remove();
                    var yzm = $.trim($("#yzm").val());
                    var type = $(".send").attr("data-type");
                    var val = eval(type);
                    var obj = $(".send");
                    if (yzm == '') {
                        obj.addClass('red');
                        $("<label class='error'>" + icon + "<?=__('请填写验证码')?></label>").insertAfter(obj);
                        return false;
                    }
                    var url = SITE_URL + '?ctl=Buyer_User&met=checkYzm&typ=json';
                    var pars = 'yzm=' + yzm + '&type=' + type + '&val=' + val;
                    $.post(url, pars, function (a) {
                        flag = false;
                        if (a.status == 200) {
                            flag = true;
                        }
                        else {
                            obj.addClass('red');
                            $("<label class='error'>" + icon + "<?=__('验证码错误')?></label>").insertAfter(obj);
                            return flag;
                        }
                    });
                    return flag;
                }
                
                $(".submit").click(function () {
                    var obj = $(".send");
                    
                    var yzm = $.trim($("#yzm").val());
                    var type = $(".send").attr("data-type");
                    var val = eval(type);
                    var pars = 'yzm=' + yzm + '&type=' + type + '&val=' + val;
                    var ajax_url = SITE_URL + '?ctl=Buyer_User&met=editAllInfo&typ=json';
                    
                    $('#form').validator({
                        ignore: ':hidden',
                        theme: 'yellow_right',
                        timely: 1,
                        stopOnError: false,
                        fields: {
                            'yzm': 'required;',
                        },
                        valid: function (form) {
                            //表单验证通过，提交表单
                            $.ajax({
                                url: ajax_url,
                                data: pars,
                                success: function (a) {
                                    if (a.status == 200) {
                                        Public.tips.success("<?=__('操作成功')?>");
                                        location.href = SITE_URL + "?ctl=Buyer_User&met=security";
                                    } else if (a.status == 240) {
                                        obj.addClass('red');
                                        $("<label class='error'>" + icon + "<?=__('验证码错误')?></label>").insertAfter(obj);
                                    }
                                    else {
                                        Public.tips.error("<?=__('操作失败')?>");
                                    }
                                }
                            });
                        }
                        
                    });
                    
                });
            </script>
        <?php }else{ ?>
            <div class="security-tips"><?= __('修改密码前必须先进行邮箱绑定或手机绑定，点击这里进行') ?><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_User&met=security&op=mobile"><?= __('手机绑定') ?></a><?= __('或') ?><a href="<?= Yf_Registry::get('ucenter_api_url') ?>?ctl=User&met=security&op=email"><?= __('邮箱绑定') ?></a></div>
        <?php } ?>
    </div>
<?php
    include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>
<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

include $this->view->getTplPath() . '/' . 'header.php';

?>
    </div>

    <div class="ncm-default-form">
        <form method="post" id="form" name="form" action="" class="formcs">
            <input type="hidden" name="<?php echo CSRF::name();?>" value="<?php echo  CSRF::create(); ?>">
            <input value="edit" name="submit" type="hidden">
            <input type="hidden" name="user_name" value="<?= $data['user_name'] ?>"/>
            <dl>
                <dt><?= _('用户名称：') ?></dt>
                <dd>
					<span class="w400"><?= $data['user_name'] ?>&nbsp;&nbsp;
                <div class="nc-grade-mini bbc_bg"
                     style="cursor:pointer;"><?= $this->user['grade']['user_grade_name']; ?></div>
                </span>
                    <!--<span class="midisplay">&nbsp;&nbsp;<? /*=_('隐私设置')*/ ?></span>-->
                </dd>
            </dl>
            <dl>
                <dt><?= _('昵称：') ?></dt>
                <dd><span class="w400">
                        <input type="text" class="text" maxlength="20" name="nickname"
                               value="<?= $data['nickname'] ?>">
                        </span>
                </dd>
            </dl>
            <?php if ($data['user_email'])
            { ?>
                <dl>
                    <dt><?= _('邮箱：') ?></dt>
                    <dd><span class="w400"><?= $data['user_email']; ?>&nbsp;&nbsp;
                    <a href="<?= Yf_Registry::get('url') ?>?ctl=User&met=security&op=email">
                     <?= _('修改邮箱') ?></a>
                     </span>
                        <!--<span class="midisplay">
                        <select name="privacy[user_privacy_email]">
                          <option value="0" <?php /*if($privacy ['user_privacy_email'] == 0){*/ ?>selected="selected"<?php /*}*/ ?>><? /*=_('公开')*/ ?></option>
                          <option value="1" <?php /*if($privacy ['user_privacy_email'] == 1){*/ ?>selected="selected"<?php /*}*/ ?>><? /*=_('好友可见')*/ ?></option>
                          <option value="2" <?php /*if($privacy ['user_privacy_email'] == 2){*/ ?>selected="selected"<?php /*}*/ ?>><? /*=_('保密')*/ ?></option>
                        </select>
                        </span>-->
                    </dd>
                </dl>
            <?php } ?>
            <?php if ($data['user_mobile'])
            { ?>
                <dl>
                    <dt><?= _('手机：') ?></dt>
                    <dd><span class="w400"><?= $data['user_mobile']; ?>&nbsp;&nbsp;
                    <a href="<?= Yf_Registry::get('url') ?>?ctl=User&met=security&op=mobile">
                      <?= _('修改手机') ?></a>
                     </span>
                        <!--<span class="midisplay">
                        <select name="privacy[user_privacy_mobile]">
						
                          <option value="0" <?php /*if($privacy ['user_privacy_mobile'] == 0){*/ ?>selected="selected"<?php /*}*/ ?>><? /*=_('公开')*/ ?></option>
                          <option value="1" <?php /*if($privacy ['user_privacy_mobile'] == 1){*/ ?>selected="selected"<?php /*}*/ ?>><? /*=_('好友可见')*/ ?></option>
                          <option value="2" <?php /*if($privacy ['user_privacy_mobile'] == 2){*/ ?>selected="selected"<?php /*}*/ ?>><? /*=_('保密')*/ ?></option>
                        </select>
                        </span>-->
                    </dd>
                </dl>
            <?php } ?>
            <dl>
                <dt><?= _('真实姓名：') ?></dt>
                <dd><span class="w400">
                        <input type="text" class="text" maxlength="20" name="user_truename"
                               value="<?= $data['user_truename'] ?>">
                        </span>
                    <!--<span class="midisplay">
                        <select name="privacy[user_privacy_realname]">
                          <option value="0" <?php /*if($privacy ['user_privacy_realname'] == 0){*/ ?>selected="selected"<?php /*}*/ ?>><? /*=_('公开')*/ ?></option>
                          <option value="1" <?php /*if($privacy ['user_privacy_realname'] == 1){*/ ?>selected="selected"<?php /*}*/ ?>><? /*=_('好友可见')*/ ?></option>
                          <option value="2" <?php /*if($privacy ['user_privacy_realname'] == 2){*/ ?>selected="selected"<?php /*}*/ ?>><? /*=_('保密')*/ ?></option>
                        </select>
                        </span>-->
                </dd>
            </dl>
            <dl>
                <dt><?= _('性别：') ?></dt>
                <dd><span class="w400">
                        <label>
                          <input type="radio" name="user_gender"
                                 value="0" <?= ($data['user_gender'] == 0 ? 'checked' : ''); ?>>
                            <?= _('女') ?></label>
                        &nbsp;&nbsp;
                        <label>
                          <input type="radio" name="user_gender"
                                 value="1" <?= ($data['user_gender'] == 1 ? 'checked' : ''); ?>>
                            <?= _('男') ?></label>
                        &nbsp;&nbsp;
                        <label>
                          <input type="radio" name="user_gender"
                                 value="2" <?= ($data['user_gender'] == 2 ? 'checked' : ''); ?>>
                            <?= _('保密') ?></label>
                        </span>
                    <!--<span class="midisplay">
                        <select name="privacy[user_privacy_sex]">
                          <option value="0" <?php /*if($privacy ['user_privacy_sex'] == 0){*/ ?>selected="selected"<?php /*}*/ ?>><? /*=_('公开')*/ ?></option>
                          <option value="1" <?php /*if($privacy ['user_privacy_sex'] == 1){*/ ?>selected="selected"<?php /*}*/ ?>><? /*=_('好友可见')*/ ?></option>
                          <option value="2" <?php /*if($privacy ['user_privacy_sex'] == 2){*/ ?>selected="selected"<?php /*}*/ ?>><? /*=_('保密')*/ ?></option>
                        </select>
                        </span>-->
                </dd>
            </dl>
            <dl>
                <dt><?= _('生日：') ?></dt>
                <dd><span class="w400"><select id="birthdayYear" name="year"></select>
						<label><?= _('年') ?></label>
						<select id="birthdayMonth" name="month"></select>
						<label><?= _('月') ?></label>
						<select id="birthdayDay" name="day"></select>
						<label><?= _('日') ?></label>
					   <b class="kbs"><?= _('填生日有惊喜哦~') ?></b></span>
                    <!--<span class="midisplay">
                        <select name="privacy[user_privacy_birthday]">
                          <option value="0" <?php /*if($privacy ['user_privacy_birthday'] == 0){*/ ?>selected="selected"<?php /*}*/ ?> ><? /*=_('公开')*/ ?></option>
                          <option value="1" <?php /*if($privacy ['user_privacy_birthday'] == 1){*/ ?>selected="selected"<?php /*}*/ ?>><? /*=_('好友可见')*/ ?></option>
                          <option value="2" <?php /*if($privacy ['user_privacy_birthday'] == 2){*/ ?>selected="selected"<?php /*}*/ ?>><? /*=_('保密')*/ ?></option>
                        </select>
                        </span>-->
                </dd>
            </dl>
            <dl>
                <dt><?= _('所在地区：') ?></dt>
                <dd><span class="w400"><input type="hidden" name="address_area" id="t"
                                              value="<?= @$data['user_area'] ?>"/>
					<input type="hidden" name="province_id" id="id_1" value="<?= @$data['user_provinceid'] ?>"/>
					<input type="hidden" name="city_id" id="id_2" value="<?= @$data['user_cityid'] ?>"/>
					<input type="hidden" name="area_id" id="id_3" value="<?= @$data['user_areaid'] ?>"/>
                        <?php if (@$data['user_area'])
                        { ?>
                            <div id="d_1"><?= @$data['user_area'] ?>&nbsp;&nbsp;<a
                                        href="javascript:sd();"><?= _('编辑') ?></a></div>
                        <?php } ?>
                        <div id="d_2" class="<?php if (@$data['user_area'])
                        {
                            echo 'hidden';
                        } ?>">
						<select id="select_1" name="select_1" onChange="district(this);">
							<option value="">--<?= _('请选择') ?>--</option>
                            <?php foreach ($district['items'] as $key => $val)
                            { ?>
                                <option value="<?= $val['district_id'] ?>|1"><?= $val['district_name'] ?></option>
                            <?php } ?>
						</select>
						<select id="select_2" name="select_2" onChange="district(this);" style="display: none"></select>
						<select id="select_3" name="select_3" onChange="district(this);" style="display: none"></select>
					</div></span>
                    <!--<span class="midisplay">
                        <select name="privacy[user_privacy_area]">
                          <option value="0" <?php /*if($privacy ['user_privacy_area'] == 0){*/ ?>selected="selected"<?php /*}*/ ?>><? /*=_('公开')*/ ?></option>
                          <option value="1" <?php /*if($privacy ['user_privacy_area'] == 1){*/ ?>selected="selected"<?php /*}*/ ?>><? /*=_('好友可见')*/ ?></option>
                          <option value="2" <?php /*if($privacy ['user_privacy_area'] == 2){*/ ?>selected="selected"<?php /*}*/ ?>><? /*=_('保密')*/ ?></option>
                        </select>
                        </span>-->
                </dd>
            </dl>
            <dl>
                <dt>QQ：</dt>
                <dd><span class="w400">
                        <input type="text" class="text" maxlength="30" name="user_qq" id="user_qq"
                               value="<?= $data['user_qq'] ?>">
                        </span>
                </dd>
            </dl>
    
            <?php if(!empty($reg_opt_rows)){?>
            <?php foreach ($reg_opt_rows as $opt_row):?>
            <dl>
                    <dt><?=$opt_row['reg_option_name']?>：</dt>
                    <dd><span class="w400">
                    <?php if ($opt_row['option_id'] == 1):?>
                        <select id="re_user_<?=$opt_row['reg_option_id']?>"  style='width:156px;height:30px !important;' name="option[<?php echo $opt_row['reg_option_id']; ?>]"  class="field re_user_<?=$opt_row['reg_option_id']?>" autocomplete="off" maxlength="20" placeholder="<?=$opt_row['reg_option_placeholder']?>" default="<?=$opt_row['reg_option_placeholder']?>" data-datatype="<?=$opt_row['reg_option_datatype']?>">
                            <?php
                            $reg_option_value_row = explode(',', $opt_row['reg_option_value']);
                            ?>
                            <?php foreach ($reg_option_value_row as $k=>$option_value) { ?>
                                <option value="<?php echo $k; ?>" <?php
                                if (isset($option_rows[$opt_row['reg_option_id']]) && $k==$option_rows[$opt_row['reg_option_id']]['reg_option_value_id']){
                                    echo 'selected';
                                }
                                ?>   ><?php echo $option_value; ?>
                                </option>
                            <?php } ?>
                        </select>
                    <?php elseif ($opt_row['option_id'] == 2): ?>
                        <?php
                        $reg_option_value_row = explode(',', $opt_row['reg_option_value']);
                        ?>
                        <?php foreach ($reg_option_value_row as $k=>$option_value) {?>
                            <label>
                                <input type="radio" name="option[<?php echo $opt_row['reg_option_id']; ?>]" value="<?php echo $k; ?>" <?php
                                if (isset($option_rows[$opt_row['reg_option_id']]) && $k==$option_rows[$opt_row['reg_option_id']]['reg_option_value_id']){
                                    echo 'checked';
                                }
                                ?> />
                                <?php echo $option_value; ?>
                            </label>
                        <?php } ?>
            
                    <?php elseif ($opt_row['option_id'] == 3): ?>
                        <?php
                        $reg_option_value_row = explode(',', $opt_row['reg_option_value']);
						if(isset($option_rows[$opt_row['reg_option_id']])){
							$reg_option_value_id = explode(',',$option_rows[$opt_row['reg_option_id']]['reg_option_value_id']);
						}else{
							$reg_option_value_id = array();
						}
                        
                        ?>
                        <?php foreach ($reg_option_value_row as $k=>$option_value) { ?>
                            <label>
                                <input type="checkbox" name="option[<?php echo $opt_row['reg_option_id']; ?>][]" value="<?php echo $k; ?>" <?php
                                if (isset($option_rows[$opt_row['reg_option_id']]) && in_array($k,$reg_option_value_id)){
                                    echo 'checked';
                                }
                                ?> />
                                <?php echo $option_value; ?>
                            </label>
                        <?php } ?>
            
                    <?php elseif ($opt_row['option_id'] == 4): ?>

                        <input type="text" class="text" id="re_user_<?=$opt_row['reg_option_id']?>" name="option[<?php echo $opt_row['reg_option_id']; ?>]"  class="field re_user_<?=$opt_row['reg_option_id']?>" autocomplete="off" maxlength="20" placeholder="<?=$opt_row['reg_option_placeholder']?>" default="<?=$opt_row['reg_option_placeholder']?>" data-datatype="<?=$opt_row['reg_option_datatype']?>" value="<?php echo $option_rows[$opt_row['reg_option_id']]['user_option_value'];?>" >
            
                    <?php elseif ($opt_row['option_id'] == 5): ?>
                        <textarea type="text" id="re_user_<?=$opt_row['reg_option_id']?>" style='width:300px;height:160px;' name="option[<?php echo $opt_row['reg_option_id']; ?>]"  class="field field-reset2  re_user_<?=$opt_row['reg_option_id']?>" autocomplete="off" maxlength="20" placeholder="<?=$opt_row['reg_option_placeholder']?>" default="<?=$opt_row['reg_option_placeholder']?>" data-datatype="<?=$opt_row['reg_option_datatype']?>" ><?php echo $option_rows[$opt_row['reg_option_id']]['user_option_value'];?></textarea>
            
                    <?php elseif ($opt_row['option_id'] == 6): ?>
                    <?php endif ?>
                    </span> </dd>
                </dl>
    
            <?php endforeach;?>
            <?php }?>

            
            <dl class="bottom">
                <dt></dt>
                <dd>
                    <label class="submit-border">
                        <input type="submit" class="submit bbc_btns" value="<?= _('保存修改') ?>">
                    </label>
                </dd>
            </dl>
        </form>
    </div>
    </div>
    </div>
    </div>
    </div>
    <link href="<?= $this->view->css ?>/jquery/plugins/dialog/green.css" rel="stylesheet">
    <script type="text/javascript" src="<?= $this->view->js ?>/plugins/jquery.dialog.js"></script>
    <!---  END 新增地址 --->
    <script type="text/javascript" src="<?= $this->view->js ?>/district.js"></script>
    <script>
        //更改默认发货地址
        var originalBirthday = '<?=$data['user_birth']?>'.split("-");
        var originalBirthdayYear = originalBirthday[0]; // 原年份
        var originalBirthdayMonth = parseInt(originalBirthday[1], 10); // 原月份
        var originalBirthdayDay = parseInt(originalBirthday[2], 10); // 原日期

        var nowdate = new Date(); //获取当前时间的年份
        var nowYear = nowdate.getFullYear(); //当前年份
        var nowMonth = nowdate.getMonth() + 1; //当前月份
        //清空年份、月份的下拉框 进行重新添加选项
        $("#birthdayYear").empty();
        $("#birthdayMonth").empty();
        //首先为年份字段 添加选项
        for (var startYear = nowYear; startYear >= 1930; startYear--)
        {
            $("<option value='" + startYear + "'>" + startYear + "</option>").appendTo("#birthdayYear");
        }
        for (var startMonth = 1; startMonth <= 12; startMonth++)
        {
            $("<option value='" + startMonth + "'>" + startMonth + "</option>").appendTo("#birthdayMonth");
        }
        if (originalBirthdayYear == null || originalBirthdayYear == "" || originalBirthdayYear == "1")
        {
            $("#birthdayYear").val(0);
            $("#birthdayMonth").val(0);
            $("#birthdayDay").val(0);
        }
        else
        {
            $("#birthdayYear").val(originalBirthdayYear);
            $("#birthdayMonth").val(originalBirthdayMonth);
        }
        changeSelectBrithdayDay();
        //选择生日年份后触发
        $("#birthdayYear").change(function ()
        {
            changeSelectBrithdayDay();
        });
        //选择生日月份后触发
        $("#birthdayMonth").change(function ()
        {
            changeSelectBrithdayDay();
        });
        //根据所选择的年份、月份计算月最大天数,并重新填充生日下拉框中的日期项
        function changeSelectBrithdayDay()
        {
            var maxNum;
            var month = $("#birthdayMonth").val();
            var year = $("#birthdayYear").val();
            if (year == 0)
            { //如果年份没有选择，则按照闰年计算日期(借用2004年为闰年)
                year = 2004;
            }
            if (month == 0)
            {
                maxNum = 31;
            }
            else if (month == 2)
            {
                if (year % 400 == 0 || (year % 4 == 0 && year % 100 != 0))
                { //判断闰年
                    maxNum = 29;
                }
                else
                {
                    maxNum = 28;
                }
            }
            else if (month == 4 || month == 6 || month == 9 || month == 11)
            {
                maxNum = 30;
            }
            else
            {
                maxNum = 31;
            }
            $("#birthdayDay").empty();
            for (var startDay = 1; startDay <= maxNum; startDay++)
            {
                $("<option value='" + startDay + "'>" + startDay + "</option>").appendTo("#birthdayDay");
            }
            if (maxNum >= originalBirthdayDay)
            {
                setTimeout(function ()
                {
                    $("#birthdayDay").val(originalBirthdayDay);
                }, 1);
            }
            else
            {
                setTimeout(function ()
                {
                    $("#birthdayDay").val(1);
                }, 1);
                originalBirthdayDay = 1;
            }
        }

        $(document).ready(function ()
        {
            areaChange();

            var ajax_url = SITE_URL + '?ctl=User&met=editUserInfo&typ=json';

            $('#form').validator({
                ignore: ':hidden',
                theme: 'yellow_right',
                timely: 1,
                stopOnError: false,
                rules: {
                    qq: [/^\d{5,11}$/, "<?=_('请输入正确qq')?>"],
                    nickname: [/^[a-zA-Z0-9_\u4e00-\u9fa5]+$/, "<?=_('昵称只能使用中文、数字、字母、下划线')?>"]
                  

                },
                fields: {
                    'select_1': 'required',
                    'select_2': 'required',
                    'select_3': 'required',
                    'user_qq': 'qq;',
                    'nickname': 'nickname;'
                },
                valid: function (form)
                {
                    //表单验证通过，提交表单
                    $.ajax({
                        url: ajax_url,
                        data: $("#form").serialize(),
                        success: function (a)
                        {
                            if (a.status == 200)
                            {
                                Public.tips.success("<?=_('操作成功！')?>");
                                setTimeout('location.href = SITE_URL + "?ctl=User&met=getUserInfo"', 1000);//成功后跳转
                            }
                            else
                            {
                                Public.tips.error("<?=_('操作失败！')?>");
                            }
                        }
                    });
                }

            });

        });

        //优化区域选择
        function areaChange () {
            var s1 = "#select_1", s2 = "#select_2", s3 = "#select_3";

            $(s1).on("change", function () {
                $(s3).hide();
                if (!this.value) {
                    $(s2).hide()
                } else {
                    $(s2).show();
                }
            });

            $(s2).on("change", function () {
                if (!this.value) {
                    $(s3).hide();
                } else {
                    $(s3).show();
                }
            })
        }
    </script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>

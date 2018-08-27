<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>&nbsp;</h3>
                <h5>网站全局内容基本选项设置</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=site&config_type%5B%5D=site"><span>站点设置</span></a></li>
                <li><a class="current"><span>防灌水设置</span></a></li>

            </ul>
        </div>
    </div>
    <?php

  ?>
    <form method="post" id="dump-setting-form" name="settingForm">
        <input type="hidden" name="config_type[]" value="dump"/>

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit"> 使用验证码</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
                            <input type="checkbox" value="1" name="dump[captcha_status_login]" id="captcha_status1"  <?=($data['captcha_status_login']['config_value'] ? 'checked' : '')?> />
                            <label for="captcha_status1">前台登录</label>
                        </li>
                        <li>
                            <input type="checkbox" value="1" name="dump[captcha_status_register]" id="captcha_status2"  <?=($data['captcha_status_register']['config_value'] ? 'checked' : '')?>  />
                            <label for="captcha_status2">前台注册</label>
                        </li>
                    </ul>
                    <p class="notic">选择是否开启登录、注册页面验证码功能。</p>
                </dd>
            </dl>
            <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </div>
    </form>
</div>

<script type="text/javascript">
</script>

<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
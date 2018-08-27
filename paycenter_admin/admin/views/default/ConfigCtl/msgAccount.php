<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<body>
<?php
foreach ($data as $key =>$item) {
$$key = $item['config_value'];
}
?>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>基础设置&nbsp;</h3>
                <h5>邮件及短信账号设置</h5>
            </div>
            <ul class="tab-base nc-row">
	
				<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=site&config_type%5B%5D=site"><span>基础设置</span></a></li>
				<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=settings&typ=e&config_type%5B%5D=site"><span>站点Logo</span></a></li>
				<li><a class="current"><span>邮件设置</span></a></li>
				<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=mobileAccount&config_type%5B%5D=mobile&config_type%5B%5D=sms">短信设置</a></li>
				<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=seo&typ=e&config_type%5B%5D=seo"><span>SEO配置</span></a></li>
            </ul>
        </div>
    </div>

    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>
            <li>填写邮件服务器相关参数，并点击“测试”按钮进行效验，保存后生效。</li>
            <li>如使用第三方提供的邮件服务器，请认真阅读服务商提供的相关帮助文档。</li>
        </ul>
    </div>
    <form method="post" id="email_msg-setting-form" name="settingForm">
        <input type="hidden" name="config_type[]" value="email"/>

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">SMTP 服务器</dt>
                <dd class="opt">
                    <input type="text" value="<?=$email_host?>" name="email[email_host]" id="email_host" class="ui-input w400">

                    <p class="notic">设置 SMTP 服务器的地址，如 smtp.163.com</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">SMTP 端口</dt>
                <dd class="opt">
                    <input type="text" value="<?=$email_port?>" name="email[email_port]" id="email_port" class="ui-input w400">

                    <p class="notic">设置 SMTP 服务器的端口，默认为 25</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">发信人邮件地址</dt>
                <dd class="opt">
                    <input type="text" value="<?=$email_addr?>" name="email[email_addr]" id="email_addr" class="ui-input w400">

                    <p class="notic">使用SMTP协议发送的邮件地址，如 mallbuilder@163.com</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">SMTP 身份验证用户名</dt>
                <dd class="opt">
                    <input type="text" value="<?=$email_id?>" name="email[email_id]" id="email_id" class="ui-input w400">

                    <p class="notic">如 mallbuilder</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">SMTP 身份验证密码</dt>
                <dd class="opt">
                    <input type="text" value="<?=$email_pass?>" name="email[email_pass]" id="email_pass" class="ui-input w400">

                    <p class="notic">mallbuilder@163.com邮件的密码，如 123456</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">测试接收的邮件地址</dt>
                <dd class="opt">
                    <input type="text" value="" name="email[email_test]" id="email_test" class="ui-input w400">
                    <input type="button" value="测试" name="send_test_email" class="input-btn" id="send_test_email">
                </dd>
            </dl>
            <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </div>
    </form>

       
        <!--<form method="post" id="sms-setting-form" name="settingForm">
        <input type="hidden" name="config_type[]" value="sms"/>

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">远丰短信账号</dt>
                <dd class="opt">
                    <input type="text" value="<?/*=$sms_account*/?>" name="sms[sms_account]" id="sms_account" class="ui-input w400">

                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">远丰短信密码</dt>
                <dd class="opt">
                    <input type="text" value="<?/*=$sms_pass*/?>" name="sms[sms_pass]" id="sms_pass" class="ui-input w400">
                </dd>
            </dl>

            <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </div>
    </form>-->

</div>
<script>
</script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
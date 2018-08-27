<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';

// 当前管理员权限
$admin_rights = $this->getAdminRights();
// 当前页父级菜单 同级菜单 当前菜单
$menus = $this->getThisMenus();
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
                <h3><?=$menus['father_menu']['menu_name']?></h3>
                <h5><?=$menus['father_menu']['menu_url_note']?></h5>
            </div>
            <ul class="tab-base nc-row">
                <?php 
                foreach($menus['brother_menu'] as $key=>$val){ 
                    if(in_array($val['rights_id'],$admin_rights)||$val['rights_id']==0){
                ?>
                <li><a <?php if(!array_diff($menus['this_menu'], $val)){?> class="current"<?php }?> href="<?= Yf_Registry::get('url') ?>?ctl=<?=$val['menu_url_ctl']?>&met=<?=$val['menu_url_met']?><?php if($val['menu_url_parem']){?>&<?=$val['menu_url_parem']?><?php }?>"><span><?=$val['menu_name']?></span></a></li>
                <?php 
                    }
                }
                ?>
               
            </ul>
        </div>
    </div>

    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em></div>
        <ul>
            <?=$menus['this_menu']['menu_url_note']?>
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

        <form method="post" id="sms-setting-form" name="settingForm">
        <input type="hidden" name="config_type[]" value="sms"/>

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">远丰短信账号</dt>
                <dd class="opt">
                    <input type="text" value="<?=$sms_account?>" name="sms[sms_account]" id="sms_account" class="ui-input w400">

                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">远丰短信密码</dt>
                <dd class="opt">
                    <input type="text" value="<?=$sms_pass?>" name="sms[sms_pass]" id="sms_pass" class="ui-input w400">
                </dd>
            </dl>

			<!--
            <dl class="row">
                <dt class="tit">测试接收的手机号码</dt>
                <dd class="opt">
                    <input type="text" value="" name="sms[sms_test]" id="sms_test" class="ui-input w400">
                    <input type="button" value="测试" name="send_test_sms" class="input-btn" id="send_test_sms">
                </dd>
            </dl>
            -->
            <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </div>
    </form>
</div>
<script>
</script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
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
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3><?=$menus['father_menu']['menu_name']?></h3>
                <h5><?=$menus['father_menu']['menu_url_note']?></h5>
            </div>
            <?php include dirname(__FILE__).'/comm_api_menu.php';?>
        </div>
    </div>
    <?php

  ?>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em></div>
        <ul>
            <?=$menus['this_menu']['menu_url_note']?>
        </ul>
    </div>
    <form method="post" id="shop_api-setting-form" name="settingForm">
        <input type="hidden" name="config_type[]" value="shop_api"/>

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="site_name">PC版前台URL</label>
                </dt>
                <dd class="opt">
                    <input id="shop_api_url" name="shop_api[shop_api_url]" value="<?=Yf_Registry::get('shop_api_url')?>" class="w400 ui-input " type="text"/>

                    <p class="notic">请填写您的商城前台网址</p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="site_name">WAP版前台URL</label>
                </dt>
                <dd class="opt">
                    <input id="shop_wap_url" name="shop_api[shop_wap_url]" value="<?=Yf_Registry::get('shop_wap_url')?>" class="w400 ui-input " type="text"/>
                    <p class="notic">请填写您商城的Wap移动版网址</p>
                </dd>
            </dl>


            <dl class="row">
                <dt class="tit">
                    <label for="site_name">本系统Key</label>
                </dt>
                <dd class="opt">
                    <input id="shop_api_key" name="shop_api[shop_api_key]" value="<?=Yf_Registry::get('shop_api_key')?>" class="ui-input w400" type="text"/>

                    <p class="notic">请填写网站前台与管理员后台通讯时的认证Key值,此Key值在安装时随机生成,请不要修改。</p>
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
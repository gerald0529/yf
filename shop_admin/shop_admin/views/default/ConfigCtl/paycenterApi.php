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
    <form method="post" id="paycenter-shop_api-setting-form" name="paycenterSettingForm">
        <input type="hidden" name="config_type[]" value="paycenter_api"/>

        <div class="ncap-form-default">

            <dl class="row">
                <dt class="tit">
                    <label for="site_name">Paycenter 显示名称</label>
                </dt>
                <dd class="opt">
                    <input id="paycenter_api_name" name="paycenter_api[paycenter_api_name]" value="<?=Yf_Registry::get('paycenter_api_name')?>" class="w400 ui-input " type="text"/>

                    <p class="notic">Paycenter</p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="site_name">Paycenter URL</label>
                </dt>
                <dd class="opt">
                    <input id="paycenter_api_url" name="paycenter_api[paycenter_api_url]" value="<?=Yf_Registry::get('paycenter_api_url')?>" class="w400 ui-input " type="text"/>

                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="site_name">Paycenter后台 URL</label>
                </dt>
                <dd class="opt">
                    <input id="paycenter_admin_api_url" name="paycenter_api[paycenter_admin_api_url]" value="<?=Yf_Registry::get('paycenter_admin_api_url')?>" class="w400 ui-input " type="text"/>

                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="site_name">Paycenter key</label>
                </dt>
                <dd class="opt">
                    <input id="paycenter_api_key" name="paycenter_api[paycenter_api_key]" value="<?=Yf_Registry::get('paycenter_api_key')?>" class="ui-input w400" type="text"/>

                    <p class="notic">请填写商城系统与Paycenter通讯的Key值,此外的值要与Paycenter后台应用的值保持一致</p>
                </dd>
            </dl>
            <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp paycenter-submit-btn">确认提交</a></div>
        </div>
    </form>

</div>

<script type="text/javascript">
</script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
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
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>分站管理</h3>
                <h5>分站管理</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a class="current"><span>分站设置设置</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Subsite_Config&met=index"><span>分站管理</span></a></li>
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em>
        </div>
        <ul>
            <li>分站设置</li>
        </ul>
    </div>
    
    <form method="post" id="shop_api-setting-form" name="settingForm">
        <input type="hidden" name="config_type[]" value="shop_api"/>

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="site_name">店铺网站URL</label>
                </dt>
                <dd class="opt">
                    <input id="shop_api_url" name="shop_api[shop_api_url]" value="<?=Yf_Registry::get('shop_api_url')?>" class="w400 ui-input " type="text"/>

                    <p class="notic">后台与网站通信的URL</p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="site_name">店铺 WAP URL</label>
                </dt>
                <dd class="opt">
                    <input id="shop_wap_url" name="shop_api[shop_wap_url]" value="<?=Yf_Registry::get('shop_wap_url')?>" class="w400 ui-input " type="text"/>
                    <p class="notic">店铺 WAP URL</p>
                </dd>
            </dl>


            <dl class="row">
                <dt class="tit">
                    <label for="site_name">管理店铺KEY</label>
                </dt>
                <dd class="opt">
                    <input id="shop_api_key" name="shop_api[shop_api_key]" value="<?=Yf_Registry::get('shop_api_key')?>" class="ui-input w400" type="text"/>

                    <p class="notic">后台与网站通信的数据加密验证Key</p>
                </dd>
            </dl>
            <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </div>
    </form>


    <form method="post" id="ucenter-shop_api-setting-form" name="ucenterSettingForm">
        <input type="hidden" name="config_type[]" value="ucenter_api"/>

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="site_name">用户中心API URL</label>
                </dt>
                <dd class="opt">
                    <input id="ucenter_api_url" name="ucenter_api[ucenter_api_url]" value="<?=Yf_Registry::get('ucenter_api_url')?>" class="w400 ui-input " type="text"/>

                    <p class="notic">后台与用户中心网站通信的URL</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="site_name">管理用户中心KEY</label>
                </dt>
                <dd class="opt">
                    <input id="ucenter_api_key" name="ucenter_api[ucenter_api_key]" value="<?=Yf_Registry::get('ucenter_api_key')?>" class="ui-input w400" type="text"/>

                    <p class="notic">后台与用户中心网站通信的数据加密验证Key</p>
                </dd>
            </dl>
            <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp ucenter-submit-btn">确认提交</a></div>
        </div>
    </form>

</div>

<script type="text/javascript">
</script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
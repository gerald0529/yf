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
                <h3>&nbsp;</h3>
                <h5>商城设置-网站全局内容基本选项设置</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a class="current"><span>商城设置</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Shop_Manage&met=join"><span>开店申请</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Shop_Manage&met=reopen"><span>续签申请</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Shop_Manage&met=join"><span>经营类目申请</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Shop_Manage&met=join"><span>结算周期设置</span></a></li>
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em></div>
        <ul>
            <li>如果当前时间超过店铺有效期或店铺处于关闭状态，前台将不能继续浏览该店铺，但是店主仍然可以编辑该店铺</li>
        </ul>
    </div>
</div>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
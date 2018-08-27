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
                <h3>基础设置&nbsp;</h3>
                <h5>敏感词设置</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=site&config_type%5B%5D=site"><span>站点设置</span></a></li>

                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=upload&config_type%5B%5D=upload"><span>上传设置</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=msgAccount&config_type%5B%5D=email&config_type%5B%5D=sms">邮件设置</a></li>
<!--                <li><a href="--><?//= Yf_Registry::get('url') ?><!--?ctl=Config&met=im&config_type%5B%5D=im">IM设置</a></li>-->
<!--                <li><a href="--><?//= Yf_Registry::get('url') ?><!--?ctl=Config&met=district"><span>地区设置</span></a></li>-->
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=sphinx&config_type%5B%5D=sphinx"><span>搜索引擎</span></a></li>
                <li><a class="current" ><span>敏感词设置</span></a></li>
<!--                <li><a href="--><?//= Yf_Registry::get('url') ?><!--?ctl=Config&met=licence&config_type%5B%5D=licence"><span>授权证书</span></a></li>-->
            </ul>
        </div>
    </div>

    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>
            <li>敏感词:可以设置为汉字 数字 字母等等。</li>
        </ul>
    </div>
    
	<div class="mod-search cf">
		<div class="fr">
			<a href="#" class="ui-btn ui-btn-sp mrb" id="btn-add">新增<i class="iconfont icon-btn03"></i></a>
			<a class="ui-btn" id="btn-refresh">刷新<i class="iconfont icon-btn01"></i></a>
		</div>
	</div>
    <div class="cf">
        <div class="grid-wrap">
            <table id="grid">
            </table>
            <div id="page"></div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/filter/filter_list.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
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
                <h3>支付应用管理</h3>
                <h5>支付应用管理相关设定及管理</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a class="current"><span>支付应用管理</span></a></li>
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>
            <li>支付应用管理。</li>
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
<script type="text/javascript" src="<?=$this->view->js?>/controllers/app/app_list.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
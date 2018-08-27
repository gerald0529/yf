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
                <h3>供应商入驻</h3>
                <h5>供应商入驻将在入驻展示</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=supplier_slider&config_type%5B%5D=supplier_slider"><span>幻灯片管理</span></a></li>
                <li><a  class="current" ><span>供应商入驻</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=supplier_setting&config_type%5B%5D=supplier_setting"><span>供应商入驻设置</span></a></li>
             
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em></div>
        <ul>
            <li>入驻指南会出现在招商首页的最下方，以切换卡形式出现，可编辑但不可删减数量。</li>
        </ul>
    </div>
        <div style="margin-top:20px;margin-bottom: 10px;">
    </div>
        <div class="grid-wrap">
		<table id="grid">
		</table>
		<div id="page"></div>
    </div>
    
</div>
 <script type="text/javascript" src="<?=$this->view->js?>/controllers/supplier/help/supplier_help_list.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
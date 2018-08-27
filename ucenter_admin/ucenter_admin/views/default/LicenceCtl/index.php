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
                <h3>软件授权&nbsp;</h3>
                <h5>软件授权</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a class="current"  ><span>软件授权</span></a></li>
				<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Licence&met=log&typ=e"><span>检测记录</span></a></li>
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
            <li>软件授权, 记录用户使用的域名及有效期</li>
        </ul>
    </div>
    <div class="mod-search cf">
        <div class="fl">
            <ul class="ul-inline">
                <li>
                    <input type="text" id="begin_date" class="ui-input ui-datepicker-input">
                    <i>-</i>
                    <input type="text" id="end_date" class="ui-input ui-datepicker-input">
                </li>
                <li><a class="ui-btn" id="search">查询<i class="iconfont icon-btn02"></i></a><!--<a class="ui-btn ui-btn-refresh" id="refresh" title="刷新"><b></b></a>--></li>
            </ul>
        </div>
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
<script type="text/javascript" src="<?=$this->view->js?>/controllers/licence_list.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>


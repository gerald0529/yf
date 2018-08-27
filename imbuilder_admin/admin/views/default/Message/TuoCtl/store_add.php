<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>
</head>
<style>
		body{overflow-y:hidden;}
		.matchCon{width:200px;}
		.matchCon1{width:200px;}
		#tree{background-color: #fff;width: 225px;border: solid #ddd 1px;margin-left: 5px;height:100%;}
		/*h3{background: #EEEEEE;border: 1px solid #ddd;padding: 5px 10px;}*/
		.grid-wrap{position:relative;width:100%;}
		.grid-wrap h3{border-bottom: none;}
		#tree h3{border-style:none;border-bottom:solid 1px #D8D8D8;}
		.quickSearchField{padding :10px; background-color: #f5f5f5;border-bottom:solid 1px #D8D8D8;}
		#searchCategory input{width:165px;}
		.innerTree{overflow-y:auto;}
		#hideTree{cursor: pointer;color:#fff;padding: 0 4px;background-color: #B9B9B9;border-radius: 3px;position: absolute;top: 5px;right: 5px;}
		#hideTree:hover{background-color: #AAAAAA;}
		#clear{display:none;}
</style>
</head>
<body>
<div class="wrapper page">
	<!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em>查询添加托管商家</em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
    </div>
	<div class="fixed-bar">
	    <div class="item-title">
	      <div class="subject">
	        <h3>新增托管商家</h3>
	        <h5>查询添加托管商家</h5>
	      </div>
		      <ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Message_Tuo&met=message" class=""><span>托管消息</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Message_Tuo&met=store" class=""><span>托管商家</span></a></li>
                <li><a href="###" class="current"><span>新增托管商家</span></a></li>
	          </ul>
	    </div>
 	</div>
	<div class="mod-search cf">
            <div class="fl">
                <ul class="ul-inline">
                    <li>
                        <input type="text" id="matchCon" class="ui-input ui-input-ph matchCon" value="商家名称/商家手机号">
                    </li>
                    <li><a class="ui-btn mrb" id="search">查询<i class="iconfont icon-btn02"></i></a></li>
                </ul>
            </div>
        </div> 
	<div class="cf">
		<div class="grid-wrap fl cf"> 
			<table id="grid">
			</table>
			<div id="page"></div>
		</div>

	</div>
</div>
<script src="./admin/static/default/js/controllers/message/addstore.js"></script>
<?php
include TPL_PATH . '/'  . 'footer.php';
?>
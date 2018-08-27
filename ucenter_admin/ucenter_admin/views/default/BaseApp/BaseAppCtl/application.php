<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>
<style>
	html{ overflow:hidden; }
	.matchCon{width:280px;}
	.ui-input{width:200px;height: 30px;}
	.matchCon1{width:200px;}
	#tree{background-color: #fff;width: 225px;border: solid #ddd 1px;margin-left: 5px;height:100%;}
	.grid-wrap h3{background: #EEEEEE;border: 1px solid #ddd;padding: 5px 10px;}
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

<body>
<div class="wrapper page">

	<!-- 操作说明 -->
   <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em></div>
        <ul>
            <li>可以将不同的系统接入Ucenter,实现用户的同步,可以在此处配置需要接入的应用</li>
        </ul>
    </div>

     <div class="fixed-bar">
        <div class="item-title">
          <div class="subject">
            <h3>应用配置</h3>
            <h5>增加或删除相关应用</h5>
          </div>
       		<ul class="tab-base nc-row">
	          <li><a class="current"><span>应用配置</span></a></li>
	      </ul>
        </div>
    </div>
    <div class="ncap-form-default">
		<div class="mod-search cf">

		    <div class="fr"><a href="#" class="ui-btn ui-btn-sp" id="btn-add">新增<i class="iconfont icon-btn03"></i></a></div>
		</div>
	    <div class="cf">
			<div class="grid-wrap">
				<!-- <h3>应用配置:<span id='currentCategory'></span></h3> -->
				<table id="grid">
				</table>
				<div id="page"></div>
			</div>
		</div>
	</div>
</div>
<script src="./ucenter_admin/static/default/js/controllers/application/application.js"></script>
</body>
<?php
include TPL_PATH . '/'  . 'footer.php';
?>
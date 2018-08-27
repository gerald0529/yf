<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<style>
    #matchCon { width: 200px; }
</style>
</head>
<body class="bgwh">
<div class="container fix p20">
	<div class="mod-search m0 cf">
		<div class="fl">
			<ul class="ul-inline">
				<!--<li>
					<input type="text" id="matchCon" class="ui-input ui-input-ph" value="输入编号 / 名称 / 联系人 / 电话查询">
				</li>
				<li> <a class="ui-btn" id="search">查询<i class="iconfont icon-btn02"></i></a><a class="ui-btn" id="refresh">刷新</a></li>-->
			</ul>
		</div>
	</div>
	<div class="grid-wrap" style="width: 735px; ">
		<table id="grid">
		</table>
		<div id="page"></div>
	</div>
</div>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/goods/goods_brandlist.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
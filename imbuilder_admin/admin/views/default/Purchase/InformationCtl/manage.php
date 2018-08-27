<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>
</head>

<style>
		body{overflow-y:hidden;}
		.matchCon{width:280px;}
		#tree{background-color: #fff;width: 225px;border: solid #ddd 1px;margin-left: 5px;height:100%;}
		h3{background: #EEEEEE;border: 1px solid #ddd;padding: 5px 10px;}
		.grid-wrap{position:relative;}
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
<div class="wrapper">
	<div class="mod-search cf">
		<div class="fl">

		</div>
	</div>
	<div class="cf">
		<div class="grid-wrap fl cf">
			<h3>当前用户：<span id='currentCategory'></span></h3>
			<table id="grid">
			</table>
			<div id="page"></div>
		</div>

	</div>
</div>
<script src="./admin/static/default/js/controllers/purchase/purchase_information_list.js"></script>
<?php
include TPL_PATH . '/'  . 'footer.php';
?>
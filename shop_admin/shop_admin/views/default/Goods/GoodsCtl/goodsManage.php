<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<style>
body{background: #fff;}
</style>
</head>
<body>
<div class="wrapper">
	<div class="grid-wrap">
		<table id="grid">
		</table>
		<div id="page"></div>
	</div>
</div>

<script type="text/javascript">
	var goods_data = <?=$json?>;
</script>

<script type="text/javascript" src="<?=$this->view->js?>/controllers/goods/goods_list.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
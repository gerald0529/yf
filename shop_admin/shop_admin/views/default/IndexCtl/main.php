<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>

<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<script src="<?=$this->view->js_com?>/template.js"></script>
</head>
<body>
<div id="hd" class="cf">
    <div class="fl welcome cf">
        <strong><span id="greetings"></span><span id="username"></span></strong>
    </div>
</div>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
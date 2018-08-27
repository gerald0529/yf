<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<style>
	body{width:100%;}
</style>
<body>

<div class="wrapper" style="padding-top:0px;">
    <div class="wrapper">
        <div class="grid-wrap">
            <table id="grid"></table>
            <div id="page"></div>
        </div>
    </div>
</div>

<script src="<?= Yf_Registry::get('base_url') ?>/shop_admin/static/default/js/controllers/promotion/redpacket/redpacket_list.js"></script>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
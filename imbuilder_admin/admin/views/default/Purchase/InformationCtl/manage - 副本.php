<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>
</head>

<body>
<div class="wrapper">
  <div class="mod-search cf">
    <div class="fr"><a class="ui-btn ui-btn-sp mrb" id="add">新增</a><a class="ui-btn mrb" id="export" target="_blank">导出</a><a href="#" class="ui-btn" id="btn-batchDel">删除</a></div>
  </div>
  <div class="grid-wrap">
    <table id="grid">
    </table>
    <div id="page"></div>
  </div>
</div>
<script src="./admin/static/default/js/controllers/purchase/purchase_information_list.js"></script>
<?php
include TPL_PATH . '/'  . 'footer.php';
?>
<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>
<style>
html{ overflow:hidden; }
.matchCon{width:280px;}
</style>
</head>
<body>
<div class="wrapper">
	<div class="mod-search cf">
	    <div class="fl">
	      <ul class="ul-inline">
	        <li>
	          <input type="text" id="matchCon" class="ui-input ui-input-ph matchCon" value="输入问题编号查询">
	        </li>
	        <li><a class="ui-btn mrb" id="search">查询</a></li>
	      </ul>
	    </div>
	  </div>
    <div class="grid-wrap">
	    <table id="grid">
	    </table>
	    <div id="page"></div>
	  </div>
</div>
<script src="./admin/static/default/js/controllers/service/idea.js"></script>
<?php
include TPL_PATH . '/'  . 'footer.php';
?>
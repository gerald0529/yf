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

<form method="post" name="manage-form" id="manage-form" action="">
    <div class="ncap-form-default">
        <dl class="row">
            <dt class="tit">
                <label class="property_name" for="property_name"><em>*</em>属性名称</label>
            </dt>
            <dd class="opt">
                <input type="text" maxlength="20" value="" name="property_name" id="property_name" class="ui-input ui-input-ph">
                <span class="err"></span>
                <p class="notic"></p>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label>排序</label>
            </dt>
            <dd class="opt">
                <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="property_displayorder" id="property_displayorder"></div>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label>是否被搜索</label>
            </dt>
            <dd class="opt">
                <div class="onoff">
                    <label for="property_is_search1" class="cb-enable  ">是</label>
                    <label for="property_is_search0" class="cb-disable  selected">否</label>
                    <input id="property_is_search1"  name ="property_is_search"  value="1" type="radio">
                    <input id="property_is_search0"  name ="property_is_search"  checked="checked"  value="0" type="radio">
                </div>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label>数据格式</label>
            </dt>
            <dd class="opt">
            	<span id="property_format"></span>
            </dd>
        </dl>
    </div>
    <div class="grid-wrap" >
		<table id="grid">
		</table>
		<div id="page"></div>
	</div>
</form>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/goods/property.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
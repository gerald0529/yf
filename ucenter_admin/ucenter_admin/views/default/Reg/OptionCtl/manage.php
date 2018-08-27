<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<link href="<?=$this->view->css?>/jquery/plugins/validator/jquery.validator.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<style>
    body{background: #fff;}
</style>
</head>
<body>

<form method="post" name="manage-form" id="manage-form">
    <input type="hidden" class="input-text form-control" name="reg_option_id" id="reg_option_id"  placeholder="选项值" autocomplete="off" />

    <div class="ncap-form-default">
        <dl class="row">
            <dt class="tit">
                <label class="reg_option_name" for="reg_option_name"><em>*</em>配置名称</label>
            </dt>
            <dd class="opt">
                <input type="text" maxlength="200" value="" name="reg_option_name" id="reg_option_name" class="ui-input ui-input-ph w400">
                <span class="err"></span>
            </dd>
        </dl>


        <dl class="row">
            <dt class="tit">
                <label class="option_id" for="option_id"><em></em>类型</label>
                <input type="hidden" id="option_id" name="option_id" class="ui-input">
            </dt>
            <dd class="opt">
                <span id="option_id_combo"></span>
            </dd>
            
        </dl>

        <dl class="row">
            <dt class="tit">
                <label class="reg_option_value" for="reg_option_value"><em></em>配置值</label>
            </dt>
            <dd class="opt">
                <input type="text" maxlength="200" value="" name="reg_option_value" id="reg_option_value" class="ui-input ui-input-ph w400">
                <span class="err"></span>
            </dd>
        </dl>

        <!--
                <dl class="row">
                    <dt class="tit">
                        <label class="reg_option_placeholder" for="reg_option_placeholder"><em></em>placeholder</label>
                    </dt>
                    <dd class="opt">
                        <input type="text" maxlength="200" value="" name="reg_option_placeholder" id="reg_option_placeholder" class="ui-input ui-input-ph w400">
                        <span class="err"></span>
                    </dd>
                </dl>
        
                <dl class="row">
                    <dt class="tit">
                        <label class="reg_option_order" for="reg_option_order"><em></em>placeholder</label>
                    </dt>
                    <dd class="opt">
                        <input type="text" maxlength="200" value="" name="reg_option_order" id="reg_option_order" class="ui-input ui-input-ph w400">
                        <span class="err"></span>
                    </dd>
                </dl>
                -->

        <dl class="row">
            <dt class="tit">
                <label class="reg_option_datatype" for="reg_option_datatype"><em></em>规则设定</label>
                <input type="hidden" id="reg_option_datatype" name="reg_option_datatype" class="ui-input">
            </dt>
            <dd class="opt">
                <span id="reg_option_datatype_combo"></span>
            </dd>

        </dl>
        


        <dl class="row">
            <dt class="tit">
                <label class="reg_option_required" for="reg_option_required"><em></em>是否必填</label>
            </dt>

            <dd class="opt onoff" style="font-size:0;">
                <label for="enable1" class="cb-enable  ">是</label>
                <label for="enable0" class="cb-disable  selected">否</label>
                <input id="enable1"  name ="reg_option_required"  value="1" type="radio">
                <input id="enable0"  name ="reg_option_required"  checked="checked" value="0" type="radio">
            </dd>
            
        </dl>

        <dl class="row">
            <dt class="tit">
                <label class="reg_option_active" for="reg_option_active"><em></em>是否启用</label>
            </dt>
            <dd class="opt onoff" style="font-size:0;">
                <label for="a-enable1" class="cb-enable  ">是</label>
                <label for="a-enable0" class="cb-disable  selected">否</label>
                <input id="a-enable1"  name ="reg_option_active"  value="1" type="radio">
                <input id="a-enable0"  name ="reg_option_active"  checked="checked" value="0" type="radio">
            </dd>
        </dl>
    </div>
</form>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/reg/option_manage.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>

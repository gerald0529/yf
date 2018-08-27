<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<style>
body{background: #fff;}
</style>
</head>
<body>

<form method="post" name="manage-form" id="manage-form">
    <input type="hidden" name="form_submit" value="ok">
    <input type="hidden" name="licence_id" id="licence_id">


    <div class="ncap-form-default">
        <dl class="row">
            <dt class="tit">
                <label class="licence_domain" for="licence_domain"><em>*</em>允许的域名</label>
            </dt>
            <dd class="opt">
                <input type="text" maxlength="200" value="" name="licence_domain" id="licence_domain" class="ui-input ui-input-ph w400">
                <span class="err"></span>
                <p class="notic">添加多个域名的时候，以","隔开</p>
            </dd>
        </dl>

        <dl class="row">
            <dt class="tit">
                <label class="company_name" for="company_name"><em>*</em>授权名称</label>
            </dt>
            <dd class="opt">
                <input type="text" maxlength="200" value="" name="company_name" id="company_name" class="ui-input ui-input-ph w400">
                <span class="err"></span>
                <p class="notic">可以填写公司名称或者个人名称</p>
            </dd>
        </dl>

        <dl class="row">
            <dt class="tit">
                <label><em>*</em>生效日期</label>
            </dt>
            <dd class="opt">
                <div style="float: left;width:50%;">
                    <input type="text" id="licence_effective_startdate" name="licence_effective_startdate" class="ui-input ui-datepicker-input">
                </div>
            </dd>
        </dl>

        <dl class="row">
            <dt class="tit">
                <label><em>*</em>失效日期</label>
            </dt>
            <dd class="opt">
                <div style="float: left;width:50%;">
                    <input type="text" id="licence_effective_enddate" name="licence_effective_enddate" class="ui-input ui-datepicker-input">
                </div>
            </dd>
        </dl>

        <dl class="row">
            <dt class="tit">
                <label><em>*</em>所属应用</label>
				<input type="hidden" id="app_id" name="app_id" class="ui-input">
            </dt>
            <dd class="opt">
            	<span id="app_id_combo"></span>
            </dd>
        </dl>
    </div>
</form>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/licence_manage.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
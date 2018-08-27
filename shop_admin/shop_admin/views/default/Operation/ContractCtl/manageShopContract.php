<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<body>
    <form method="post" id="manage-form" name="settingForm">
        <input type="hidden" name="contract_id" id="contract_id" value="<?=$data['contract_id']?>">

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">店铺名称</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
							<span><?=$data['shop_name']?></span>
                        </li>
                    </ul>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">项目名称</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
							<span><?=$data['contract_type_name']?></span>
                        </li>
                    </ul>
                </dd>
            </dl>
	    <dl class="row">
                <dt class="tit">状态</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
							<span><?=$data['contract_state_text']?></span>
                        </li>
                    </ul>
                </dd>
            </dl>
	    <dl class="row">
                <dt class="tit">关闭状态</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
						<div class="onoff" id="contract_type_state">
								<label title="允许使用" class="cb-enable <?=($data['contract_state_etext']=='inuse' ? 'selected' : '')?> " for="contract_state_enable">允许使用</label>
								<label title="永久禁止使用" class="cb-disable <?=($data['contract_state_etext']=='unuse' ? 'selected' : '')?>" for="contract_state_disabled">永久禁止使用</label>
								<input type="radio" value="1" name="contract_state" id="contract_state_enable" <?=($data['contract_state_etext']=='inuse' ? 'checked' : '')?> />
								<input type="radio" value="2" name="contract_state" id="contract_state_disabled" <?=($data['contract_state_etext']=='unuse' ? 'checked' : '')?> />
						</div>
                        </li>
                    </ul>
                </dd>
            </dl>
        </div>
    </form>
    <script type="text/javascript" src="<?=$this->view->js?>/controllers/operation/contract_manage.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
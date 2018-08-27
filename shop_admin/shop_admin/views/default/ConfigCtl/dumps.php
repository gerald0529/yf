<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/dump.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>&nbsp;</h3>
                <h5>商城相关基础信息及功能设置选项</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=shop&config_type%5B%5D=setting"><span>商城设置</span></a></li>
                <li><a class="current"><span>防灌水设置</span></a></li>        
            </ul>
        </div>
    </div>
    <?php

  ?>
    <form method="post" id="dumps-setting-form" name="settingForm">
        <input type="hidden" name="config_type[]" value="dumps"/>

        <div class="ncap-form-default">
			<dl class="row">
				<dt class="tit">
				<label>允许游客咨询</label>
				</dt>
				<dd class="opt">
				  <div class="onoff">
					<label title="是" class="cb-enable <?=($data['guest_comment']['config_value']==1 ? 'selected' : '')?> " for="guest_comment_enable">是</label>
					<label title="否" class="cb-disable <?=($data['guest_comment']['config_value']==0 ? 'selected' : '')?>" for="guest_comment_disabled">否</label>
					<input type="radio" value="1" name="dumps[guest_comment]" id="guest_comment_enable" <?=($data['guest_comment']['config_value']==1 ? 'checked' : '')?> />
					<input type="radio" value="0" name="dumps[guest_comment]" id="guest_comment_disabled" <?=($data['guest_comment']['config_value']==0 ? 'checked' : '')?> />
				  </div>
				  <p class="notic">允许游客在商品的详细展示页面，对当前商品进行咨询</p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">使用验证码</dt>
				<dd class="opt">
				  <input type="checkbox"  value="1" name="dumps[captcha_status_goodsqa]" id="captcha_status3" <?=($data['captcha_status_goodsqa']['config_value']==1 ? 'checked' : '')?> />
				  <label for="captcha_status3">商品咨询</label>
				</dd>
				<p class="notic"></p>
				
			</dl>
            <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </div>
    </form>
</div>

<script type="text/javascript">
	
</script>

<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
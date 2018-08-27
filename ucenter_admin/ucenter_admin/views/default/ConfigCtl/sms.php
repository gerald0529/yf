<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<body>

<div class="wrapper page">
	<!-- 操作说明 -->
	<p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
		<div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
			<h4 title="">操作提示</h4>
			<span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em></div>
		<ul>
			<li>用户注册,找回密码,消息通知等需要用短信来发送通知,在使用短信功能之前,需要向远丰公司购买短信。</li>
		</ul>
	</div>
	<div class="fixed-bar">
		<div class="item-title">
			<div class="subject">
				<h3>短信设置</h3>
				<h5>在远丰公司购买的短信账号和密码</h5>
			</div>
			<ul class="tab-base nc-row">
				<li><a class="current"><span>短信设置</span></a></li>
			</ul>
		</div>
	</div>
	<form style="" method="post" name="form_index" id="sms-setting-form">
		<input type="hidden" name="config_type[]" value="sms"/>
		<div class="ncap-form-default">
			<div id="para-wrapper">
				<!-- <div class="para-item">
					<ul class="mod-form-rows" id="establish-form">

						<li class="row-item">
							<div class="label-wrap">
								<label for="sms_account">账号:</label>
							</div>
							<div class="ctn-wrap">
								<input id="sms_account" name="sms[sms_account]" value="<?=($data['sms_account']['config_value'])?>" class="ui-input w400" type="text" />
								<p class="notic">在远丰公司购买短信后,客服提供的用户名</p>
							</div>
						</li>
						<li class="row-item">
							<div class="label-wrap">
								<label for="sms_pass">密码:</label>
							</div>
							<div class="ctn-wrap">

								<input id="sms_pass" name="sms[sms_pass]" value="<?=($data['sms_pass']['config_value'])?>" class="ui-input w400" type="text" />
								<p class="notic">在远丰公司购买短信后,客服提供的密码</p>
							</div>
						</li>
					</ul>
				</div> -->
				<div class="ncap-form-default">
					<div class="mod-form-rows" id="establish-form">

						<dl class="row">
							<dt class="tit">
								<label for="sms_account">账号:</label>
							</dt>
							<dd class="opt">
								<input id="sms_account" name="sms[sms_account]" value="<?=($data['sms_account']['config_value'])?>" class="ui-input w400" type="text" />
								<p class="notic">在远丰公司购买短信后,客服提供的用户名</p>
							</dd>
						</dl>
						<dl class="row">
							<dt class="tit">
								<label for="sms_pass">密码:</label>
							</dt>
							<dd class="opt">

								<input id="sms_pass" name="sms[sms_pass]" value="<?=($data['sms_pass']['config_value'])?>" class="ui-input w400" type="text" />
								<p class="notic">在远丰公司购买短信后,客服提供的密码</p>
							</dd>
						</dl>
					</div>
				</div>
				<div class="btn-wrap bot"> <a name="submit" id="submit" class="ui-btn ui-btn-sp submit-btn">提交</a> </div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>

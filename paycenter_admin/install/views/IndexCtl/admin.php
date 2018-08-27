<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="viewport" content="width=device-width"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="robots" content="noindex,nofollow"/>
	<title><?= _('欢迎您选用远丰支付中心系统') ?></title>
	<link rel='stylesheet' id='buttons-css' href='./static/css/buttons.css?ver=4.5.2' type='text/css' media='all'/>
	<link rel='stylesheet' id='install-css' href='./static/css/install.css?ver=4.5.2' type='text/css' media='all'/>
</head>
<body class="wp-core-ui">
<p id="logo"><a href="http://www.yuanfeng.cn" tabindex="-1">远丰</a></p>

<h1>创建管理员账号</h1>

<form id="setup" method="post" action="./index.php?met=createAdminAccount&language=zh_CN" novalidate="novalidate">
	<table class="form-table">
		<tr>
			<th scope="row"><label for="user_login">用户名</label></th>
			<td>
				<input name="user_account" type="text" id="user_login" size="25" value=""  style="width: 100%" />
				<p></p>
			</td>
		</tr>
		<tr class="form-field form-required user-pass1-wrap">
			<th scope="row">
				<label for="pass1"> 密码	</label>
			</th>
			<td>
				<div class="">
					<input type="text" name="user_password" id="pass1" class="regular-text"  value=""  style="width: 100%" />
					<div id="pass-strength-result" aria-live="polite"></div>
				</div>
				<p><span class="description important hide-if-no-js">
				<strong>重要：</strong>您将需要此密码来登录，请将其保存在安全的位置。</span></p>
			</td>
		</tr>


		<tr class="form-field form-required user-pass1-wrap">
			<th scope="row">
				<label for="ucenter_api_url"> 用户中心网址	</label>
			</th>
			<td>
				<div class="">
					<input type="text" name="ucenter_api_url" id="ucenter_api_url" class="regular-text"  value=""  style="width: 100%" />
					<div id="pass-strength-result" aria-live="polite"></div>
				</div>
				<p><span class="description important hide-if-no-js">
				<strong></strong>用户中心网址。</span></p>
			</td>
		</tr>

		<tr class="form-field form-required user-pass1-wrap">
			<th scope="row">
				<label for="ucenter_api_key"> 用户中心Key	</label>
			</th>
			<td>
				<div class="">
					<input type="text" name="ucenter_api_key" id="ucenter_api_key" class="regular-text"  value="<?=Yf_Registry::get('ucenter_api_key')?>" style="width: 100%" />
					<div id="pass-strength-result" aria-live="polite"></div>
				</div>
				<p><span class="description important hide-if-no-js">
				<strong></strong>用户中心Key。</span></p>
			</td>
		</tr>

	</table>
	<p class="step"><input type="submit" name="Submit" id="submit" class="button button-large" value="创建管理员账号"  /></p>
	<input type="hidden" name="language" value="zh_CN" />
</form>
</body>
</html>

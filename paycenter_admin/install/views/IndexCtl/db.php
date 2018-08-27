<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex,nofollow" />
	<title><?=_('欢迎您选用远丰支付中心系统')?></title>
	<link rel='stylesheet' id='buttons-css'  href='./static/css/buttons.css?ver=4.5.2' type='text/css' media='all' />
	<link rel='stylesheet' id='install-css'  href='./static/css/install.css?ver=4.5.2' type='text/css' media='all' />
</head>
<body class="wp-core-ui">
<p id="logo"><a href="http://www.yuanfeng.cn" tabindex="-1">远丰</a></p>
<?php
$msg = '';
if (request_string('msg'))
{
	$msg = sprintf(' - <b style="color:#e02222;">%s</b>', request_string('msg'));
}
?>
<h1>设置您的数据库连接  <?php echo $msg;?></h1>
<form method="post" action="./index.php?met=initDbConfig&language=zh_CN">
	<table class="form-table">
		<tr>
			<th scope="row"><label for="host">数据库主机</label></th>
			<td><input name="host" id="host" type="text" size="25"  placeholder="localhost"  value="<?php echo @$db_row['host']?:"127.0.0.1";?>" /></td>
			<td>如果<code>localhost</code>不能用，您通常可以从网站服务提供商处得到正确的信息。</td>
		</tr>
		<tr>
			<th scope="row"><label for="port">端口</label></th>
			<td><input name="port" id="port" type="text" size="25"  placeholder="3306"  value="<?php echo @$db_row['port']?:"3306";?>" /></td>
			<td>一般默认为3306。</td>
		</tr>
		<tr>
			<th scope="row"><label for="database">数据库名</label></th>
			<td><input name="database" id="database" type="text" size="25" value="<?php echo @$db_row['database'];?>" /></td>
			<td>将远丰安装到哪个数据库？</td>
		</tr>
		<tr>
			<th scope="row"><label for="user">用户名</label></th>
			<td><input name="user" id="user" type="text" size="25" placeholder="用户名" value="<?php echo @$db_row['user'];?>" /></td>
			<td>您的数据库用户名。</td>
		</tr>
		<tr>
			<th scope="row"><label for="password">密码</label></th>
			<td><input name="password" id="password" type="text" size="25" placeholder="密码" autocomplete="off" value="<?php echo @$db_row['password'];?>" /></td>
			<td>您的数据库密码。</td>
		</tr>
		<tr>
			<th scope="row"><label for="prefix">表前缀</label></th>
			<td><input name="prefix" id="prefix" type="text" value="<?php echo defined('TABEL_PREFIX') ? TABEL_PREFIX : 'shop_';?>" size="25" /></td>
			<td>如果您希望在同一个数据库安装多个远丰，请修改前缀。</td>
		</tr>
	</table>
	<input type="hidden" name="language" value="zh_CN" />
	<p class="step"><input name="submit" type="submit" value=" 提交数据库配置信息 " class="button button-large" /></p>
</form>
<script type='text/javascript' src='./static/js/jquery.js?ver=1.12.3'></script>
</body>
</html>

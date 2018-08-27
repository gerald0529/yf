<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="viewport" content="width=device-width"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="robots" content="noindex,nofollow"/>
	<title><?= _('欢迎您选用远丰电商系统') ?></title>
	<link rel='stylesheet' id='buttons-css' href='./static/css/buttons.css?ver=4.5.2' type='text/css' media='all'/>
	<link rel='stylesheet' id='install-css' href='./static/css/install.css?ver=4.5.2' type='text/css' media='all'/>
</head>
<body class="wp-core-ui">
<p id="logo"><a href="http://www.yuanfeng.cn" tabindex="-1">远丰电商</a></p>
<h1>警告提示</h1>
<p><?php echo $msg;?></p>

<div class="step"><a href="./index.php?met=install&language=zh_CN" class="button button-large button-primary"  style="margin: auto;">进行安装</a></div>
<script type='text/javascript' src='./static/js/jquery.js?ver=1.12.3'></script>
</body>
</html>
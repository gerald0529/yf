<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>登录</title>
	<link href="<?=$this->view->css?>/login.css" media="screen" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="<?=$this->view->js?>/jquery.js"></script>
	<!-- Scripts -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta property="qc:admins" content="340166442164526151665670216375" />
</head>
<style type="text/css">
	body {
		background:#56a4f6;
		width: 100%;
		z-index: -10;
		padding: 0;
	}
</style>
<body>
	<a href="./index.php?ctl=Login&met=regist">注册新用户</a>
	<a href="./index.php?ctl=Login">登录用户</a>
</body>
</html>


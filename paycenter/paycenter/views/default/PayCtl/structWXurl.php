<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta property="wb:webmaster" content="fc8386d315808f90" />
	<title>mallpaycenter登录</title>
	<!--<script type="text/javascript" src="<?=$this->view->js?>/jquery.js"></script>-->
	<!-- Scripts -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta property="qc:admins" content="340166442164526151665670216375" />
</head>

<style type="text/css">
	body {
		background:#fff;
		width: 100%;
		z-index: -10;
		padding: 0;
	}
</style>
<body>
<div align="center">
    <div style="margin-left: 10px;color:#556B2F;font-size:30px;font-weight: bolder;">扫描支付模式</div><br/>
	<img alt="模式二扫码支付" src="index.php?ctl=Pay&met=structWXcode&data=<?php echo urlencode($url);?>" style="width:150px;height:150px;"/>
	</div>
</html>


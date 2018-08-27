<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name='robots' content='noindex,follow' />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$site_name?></title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<style type="text/css">
		html {
			background: #f1f1f1;
		}
		body {
			color: #444;
			font-family:"Helvetica Neue","Luxi Sans","DejaVu Sans",Tahoma,"Hiragino Sans GB",STHeiti,"Microsoft YaHei",Arial,sans-serif;
			margin: 2em auto;
			padding: 1em;
			max-width: 300px;
			text-align: center;
		}
		h1 {
			clear: both;
			color: #666;
			margin: 30px 0 0 0;
			padding: 0;
		}
		hr {
			margin-top: 20px;
			margin-bottom: 20px;
			border: 0;
			border-top: 1px solid #dadada;
		}
		#error-page {
			margin-top: 50px;
		}
		#error-page p {
			font-size: 14px;
			line-height: 28px;
			margin: 10px 0;
		}
		ol li ,ul li {
			margin-bottom: 10px;
			font-size: 14px ;
		}
		a {
			color: #21759B;
			text-decoration: none;
		}
		a:hover {
			color: #D54E21;
		}

		#time {
			padding: 0 5px;
			margin: 0 5px;
			color: #369;
			background: #f5f5f5;
			font-weight: bold;
		}

	</style>
</head>
<body id="error-page">
<h1>站点维护中~</h1>
<p><?=$closed_reason?> </p>
<div style="display:none" id="box"></div>
</body>
</html>
<?php
die();
?>
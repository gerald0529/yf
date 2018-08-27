<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="viewport" content="width=device-width"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="robots" content="noindex,nofollow"/>
	<title><?= __('欢迎您选用远丰电商系统') ?></title>
	<link rel='stylesheet' id='buttons-css' href='./static/css/buttons.css?ver=4.5.2' type='text/css' media='all'/>
	<link rel='stylesheet' id='install-css' href='./static/css/install.css?ver=4.5.2' type='text/css' media='all'/>
	<style type="text/css">
			ul li{
					list-style: none;
					display: block;
					line-height: 46px;
			}
			ul li span{
				color:blue;
				display: inline;
			}
		 
	</style>
</head>
<body class="wp-core-ui">

<?php 
	$gg = include ROOT_PATH.'/install/data/datalog.php';

	$shop = $gg['shop']['shop_api_url'];
	$ucenter = $gg['ucenter']['ucenter_api_url'];
	$paycenter = $gg['paycenter']['paycenter_api_url'];
	$shopadmin = $gg['shop']['shop_admin_api_url'];
	$ucenteradmin = $gg['ucenter']['ucenter_admin_api_url'];
	$paycenteradmin = $gg['paycenter']['paycenter_admin_api_url'];
	 
	 

?>
<p id="logo"><a href="http://www.yuanfeng.cn/" tabindex="-1">远丰</a></p>

<h1>安装成功</h1>


<ul style="height: 250px;">

<li>感谢您的使用，商城系统一键已经安装成功，默认管理员，用户名 <span>admin</span> 密码 <span>111111</span> </li>
<li>您可至Ucenter Admin修改默认密码</li>




<li style="float: left;"><a href="<?php echo $shop;?>" target="_blank">商城前台</a></li>
<li style="float: left;"><a href="<?php echo $shopadmin;?>" target="_blank">商城后台</a></li>


<li style="float: left;"><a href="<?php echo $ucenter;?>" target="_blank">Ucenter前台</a></li>
<li style="float: left;"><a href="<?php echo $ucenteradmin;?>" target="_blank">Ucenter后台</a></li>

<li style="float: left;"><a href="<?php echo $paycenter;?>" target="_blank">Paycenter前台</a></li>
<li style="float: left;"><a href="<?php echo $paycenteradmin;?>" target="_blank">Paycenter后台</a></li>

<li style="display:block;color:red;clear: both;">安装完成，请删除各个项目下的install文件夹,或<a href='index.php?met=deleteinstall'>点击此处</a>自动删除！</li>

<li style="display:block;color:red;clear: both;">请手动删除本安装程序。</li>
</p>
</body>
</html>

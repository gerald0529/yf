<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex,nofollow" />
	<title><?=__('欢迎您选用远丰电商系统')?></title>
	<link rel='stylesheet' id='buttons-css'  href='./static/css/buttons.css?ver=4.5.2' type='text/css' media='all' />
	<link rel='stylesheet' id='install-css'  href='./static/css/install.css?ver=4.5.2' type='text/css' media='all' />
	<style type="text/css">
		.form-table input[type=text], .form-table input[type=email], .form-table input[type=url], .form-table input[type=password] {
    width: 300px;
}
	</style>
</head>
<body class="wp-core-ui">
<p id="logo"><a href="https://cn.yf_.org/" tabindex="-1">远丰</a></p>
<?php
 
if ($msg)
{
	$msg = sprintf(' - <b style="color:#e02222;">%s</b>',$msg );
}
?>
<h1>统一设置您的数据库连接  <?php echo $msg;?></h1>
<form method="post" action="./index.php?met=db&language=zh_CN">
	<table class="form-table">
		<tr>
			<th scope="row"><label for="host">数据库主机</label></th>
			<td><input name="host" id="host" type="text" size="25"  required=true placeholder="localhost"  value="<?php echo @$db_row['host']?:"127.0.0.1";?>" /></td>
			<td>如果<code>localhost</code>不能用，您通常可以从网站服务提供商处得到正确的信息。</td>
		</tr>
		<tr>
			<th scope="row"><label for="port">端口</label></th>
			<td><input name="port" id="port" required=true type="text" size="25"  placeholder="3306"  value="<?php echo @$db_row['port']?:3306;?>" /></td>
			<td>一般默认为3306。</td>
		</tr>
		<tr>
			<th scope="row"><label for="database">数据库名</label></th>
			<td><input name="database"  required=true id="database" type="text" size="25" value="<?php echo @$db_row['database'];?>" /></td>
			<td>将远丰安装到哪个数据库？</td>
		</tr>
		<tr>
			<th scope="row"><label for="user">用户名</label></th>
			<td><input name="user" id="user"  required=true type="text" size="25" placeholder="用户名" value="<?php echo @$db_row['user'];?>" /></td>
			<td>您的数据库用户名。</td>
		</tr>
		<tr>
			<th scope="row"><label for="password">密码</label></th>
			<td><input name="password"  required=true id="password" type="text" size="25" placeholder="密码" autocomplete="off" value="<?php echo @$db_row['password'];?>" /></td>
			<td>您的数据库密码。</td>
		</tr>
		
	

	<tr>
			<th scope="row" colspan=3 style="font-size:16px;    font-weight: normal;">
			<label for="" style="float:left;">统一配置Ucenter、Paycenter、Shop接口地址.<span style='color:red;float:right;margin-left:20px;'>请以 / 结尾</span></label>
	 
			</th>
			 
		</tr>
		 
	
	<tr>
			<th scope="row"><label for="">Ucenter</label></th>
			<td><input name="ucenter" required=true type="text" size="25" placeholder="" value="<?php echo $_POST['ucenter'];?>" /></td>
			<td>Ucenter接口地址</td>
	</tr>


	<tr>
			<th scope="row"><label for="">Ucenter Admin</label></th>
			<td><input name="ucenteradmin" required=true type="text" size="25" placeholder="" value="<?php echo $_POST['ucenteradmin'];?>" /></td>
			<td>Ucenter后台访问地址</td>
	</tr>


  <tr>
			<th scope="row"><label for="">Paycenter</label></th>
			<td><input name="paycenter" required=true  type="text" size="25" placeholder="" value="<?php echo $_POST['paycenter'];?>" /></td>
			<td>Paycenter接口地址</td>
	</tr>

<tr>
			<th scope="row"><label for="">Paycenter Admin</label></th>
			<td><input name="paycenteradmin" required=true  type="text" size="25" placeholder="" value="<?php echo $_POST['paycenteradmin'];?>" /></td>
			<td>Paycenter后台访问地址</td>
	</tr>

	<tr>
			<th scope="row"><label for="">Shop</label></th>
			<td><input name="shop" required=true type="text" size="25" placeholder="" value="<?php echo $_POST['shop'];?>" /></td>
			<td>Shop接口地址</td>
	</tr>

 <tr>
			<th scope="row"><label for="">Shop Wap</label></th>
			<td><input name="shopwap" required=true type="text" size="25" placeholder="" value="<?php echo $_POST['shopwap'];?>" /></td>
			<td>Shop Wap地址</td>
	</tr>

	<tr>
			<th scope="row"><label for="">Shop Admin</label></th>
			<td><input name="shopadmin" required=true type="text" size="25" placeholder="" value="<?php echo $_POST['shopadmin'];?>" /></td>
			<td>Shop后台地址</td>
	</tr>
	  
	</table>
	<input type="hidden" name="language" value="zh_CN" />




	

	<p class="step"><input name="submit" type="submit" value=" 提交数据库配置信息 " class="button button-large" /></p>
</form>
<script type='text/javascript' src='./static/js/jquery.js?ver=1.12.3'></script>
</body>
</html>

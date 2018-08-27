<html lang="zh">
	<head>
		<meta charset="UTF-8" />
		<title>支付成功</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="X-UA-Compatible" content="ie=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" /> 
	</head>
	<link rel="stylesheet" type="text/css"  href="<?=$this->view->css?>/WxNative.css"/>
	<style>
		@media screen and (max-width: 640px) {
		body {
			margin: 0;
		}
		.pay-li-cashier {
			display: none;
		}
		.Navigation {
			width: 100%;
			text-align: right;
			margin-right: 0.454rem;
		}
		.zf-success {
			width: 100%;
			padding-left: 0;
			padding-top: 0;
		}
		.sj-li {
			width: 70%;
		}
		.success-font {
			margin-top: -10px;
			font-size: 1rem;
		}
		.mr1 {
			margin-right: 1rem;
		}
		.zf-success .img-success{
			margin: 0 10px;
		}
	}
	</style>
	<body>
		<!--导航-->
		<header>
			<div class="clearfix Navigation">
				<span class=" pay-li-cashier">
					<span class="fz-col">远丰商城 </span>
					 收银台
				</span>
				<span class=" pay-li-nickname"><?=$user_name?></span>
				<span class=" pay-li-nickname ml14"><a href="<?=Yf_Registry::get('url')?>?ctl=Login&met=loginout">退出</a></span>
				<span class=" pay-li-nickname ml14">|</span>
				<span class=" pay-li-nickname ml14 mr1"><a href="<?=$return_app_url?>">返回商城</a></span>

			</div>
		</header>
		<!--支付内容-->
		<!--支付成功内容-->
		<div class="zf-success">
			<ul class="clearfix">
				<li class="fl img-success">
					<!--<img src="success2.png" />-->
				</li>
				<li class="fl sj-li ">
					<p class="success-font">恭喜您，支付成功啦！</p>
					<?php if($order_id){?>
						<p class="success-order">订单编号为：<?=$order_id?></p>
					<?php }?>
					<p class="success-prompt">温馨提示：您完成支付后，订单状态的更新将存在短期的延迟，建议您半个小时后再查看您的支付情况</p>
					<a href="<?=$return_app_url?>" class="success-black">返回首页</a>
				</li>
			</ul>
		</div>
</body>
</html>
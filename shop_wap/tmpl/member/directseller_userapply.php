<?php
include __DIR__.'/../../includes/header.php';
?>
<!DOCTYPE html>
<html lang="zh">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="Author" contect="U2FsdGVkX1+liZRYkVWAWC6HsmKNJKZKIr5plAJdZUSg1A==">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-touch-fullscreen" content="yes" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="msapplication-tap-highlight" content="no" />
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
	<title>分销申请</title>
	<link rel="stylesheet" type="text/css" href="../../css/base.css">
	<link rel="stylesheet" type="text/css" href="../../css/private-store.css">
</head>
<style>
	body{background: #fff;}
</style>
<body>
	<header id="header">
        <div class="header-wrap">
            <div class="header-l">
                <a href="directseller.html"> <i class="back"></i> </a>
            </div>
            <div class="header-title">
                <h1>分销申请</h1>
            </div>
        </div>
    </header>
    
    <!--详情-->
	<ul class="mt20" id="directseller-userapply-list">
	</ul>

	<script type="text/html" id="directseller-userapply-list-tmpl">
		<% var list = data.items; %>
		<% if (list.length > 0){%>
		<% for(var i = 0;i<list.length;i++){
		   var info = list[i];
		%>
		<li class="distribution">
			<div class="mt0">申请条件：<span><%if(info.expenditure > 0){%>消费满<%=info.expenditure%>可申请<%}else{%>成功消费任意金额<%}%></span></div>

			<div class="distribution-z1">
				<%if(info.status == 0){%>待审核<%}else if(info.status == 1){%>审核通过<%}else{%>未申请<%}%>
			</div>

			<div>消费金额：<span>￥<%=info.expends%></span></div>
			<div>通过时间：<span><%=info.directseller.directseller_create_time%></span></div>
			<%if(info.status){%>
			<div class="distribution-z2">已申请</div>
			<%}else if(info.expends > info.expenditure){%>
			<div class="distribution-z2" id="apply">分销申请</div>
			<%}%>
		</li>
		<%}%>
		<% if (hasmore) {%>
		<li class="loading">
			<div class="spinner"><i></i></div>数据读取中...</li>
		<% } %>
		<%}else{%>
		<div class="nctouch-norecord order">
			<div class="norecord-ico"><i></i></div>
			<dl>
				<dt>暂无任何分销申请</dt>
			</dl>
		</div>
		<%}%>
	</script>
	<script type="text/javascript" src="../../js/zepto.js"></script>
	<script type="text/javascript" src="../../js/common.js"></script>
	<script type="text/javascript" src="../../js/template.js"></script>
	<script type="text/javascript" src="../../js/simple-plugin.js"></script>
	<script type="text/javascript" src="../../js/tmpl/directseller_userapply.js"></script>
</body>
</html>
<?php
include __DIR__.'/../../includes/footer.php';
?>
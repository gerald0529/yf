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
<p id="logo"><a href="http://www.yuanfeng.cn/" tabindex="-1">远丰</a></p>
<h1>安装中.....</h1>
<script type='text/javascript' src='./static/js/jquery.js?ver=1.12.3'></script>


<script type="text/javascript">
	  $(document).ready(function (){
	       var nScrollHight = 0; //滚动距离总长(注意不是滚动条的长度)
	       var nScrollTop = 0;   //滚动到的当前位置
	       var nDivHight = $("#installed").height();
	       $("#installed").scroll(function(){
	         nScrollHight = $(this)[0].scrollHeight;
	         nScrollTop = $(this)[0].scrollTop;
	         if(nScrollTop + nDivHight >= nScrollHight)
	           alert("滚动条到底部了");
	         });
	});
</script>
</body>
</html>

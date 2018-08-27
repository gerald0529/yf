<?php 
include __DIR__.'/../../includes/header.php';
?>
<!DOCTYPE html>
<html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta name="msapplication-tap-highlight" content="no">
<meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1,viewport-fit:cover;">
<title>我的商城</title>
<link rel="stylesheet" type="text/css" href="../../css/base.css">
<link rel="stylesheet" type="text/css" href="../../css/nctouch_directseller.css">
</head>
<body>
<header id="header" class="transparent">
    <div class="header-wrap">
        <div class="header-l">
            <a href="member.html">
                <i class="back back2"></i>
            </a>
        </div>
        <div class="header-title">
            <h1>我的商城</h1>
        </div>
        <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>
    </div>
    <div class="nctouch-nav-layout">
        <div class="nctouch-nav-menu"> <span class="arrow"></span>
            <ul>
                <li><a href="../../index.html"><i class="home"></i>首页</a></li>
                <li><a href="../cart_list.html"><i class="cart"></i>购物车</a><sup></sup></li>
                <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
            </ul>
        </div>
    </div>
</header>
<div class="scroller-body">
    <div class="scroller-box">
        <div class="member-top"></div>
        <div class="member-center">
            <dl class="mt5">
                <dd>
                    <ul id="order_ul">
                    </ul>
                </dd>
            </dl>
        </div>
    </div>
</div>

<script type="text/javascript" src="../../js/zepto.js"></script> 
<script type="text/javascript" src="../../js/common.js"></script> 
<script type="text/javascript" src="../../js/template.js"></script>
<script type="text/javascript" src="../../js/tmpl/fenxiao.js"></script>
</body>
</html>
<?php 
include __DIR__.'/../../includes/footer.php';
?>
<?php 
include __DIR__.'/../../includes/header.php';
?>
<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="msapplication-tap-highlight" content="no" />
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
    <title>推广用户</title>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_common.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_cart.css">
	<style>
		.goods-info p{font-size:0.55rem;height:1rem;line-height:1rem;color:#888;}
	</style>
</head>

<body>
    <header id="header" class="fixed">
        <div class="header-wrap">
            <div class="header-l"><a href="directseller.html"><i class="back"></i></a></div>
           <div class="header-title">
                <h1>推广用户</h1>
            </div>
            <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a></div>
        </div>
        
		<div class="nctouch-nav-layout">
            <div class="nctouch-nav-menu"> <span class="arrow"></span>
                <ul>
                    <li><a href="../../index.html"><i class="home"></i>首页</a></li>
                    <li><a href="../search.html"><i class="search"></i>搜索</a></li>
                    <li><a href="../cart_list.html"><i class="cart"></i>购物车</a><sup></sup></li>
                    <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
                </ul>
            </div>
        </div>
    
	</header>
   
    <div class="nctouch-main-layout">
        <div class="nctouch-order-search">
            <form>
                <span class="ser-area  write">
                    <i class="icon-ser"></i>
					<input type="text" autocomplete="on" maxlength="50" placeholder="输入会员名称进行搜索" name="user_name" id="user_name" oninput="writeClear($(this));" >
					<span class="input-del"></span>
				</span>
                <input type="button" id="search_btn" value="搜索">
            </form>
        </div>
		
        <!-- <div id="fixed_nav" class="nctouch-single-nav">
            <ul id="filtrate_ul" class="w20h">
                <li class="selected"><a href="javascript:void(0);" data-state="">全部用户</a></li>
            </ul>
        </div> -->
        <div class="nctouch-order-list">
            <ul id="order-list" class="fenxiao-ul borb1"></ul>
        </div> 
    </div>
	
    <div class="fix-block-r">
        <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>
    </div>
    <footer id="footer" class="bottom"></footer>
    <script type="text/html" id="order-list-tmpl">
        <% var invitelist = data.items; %>
        <% if (invitelist.length > 0){%>
        <% for(var i = 0;i<invitelist.length;i++)
		{
			var memberinfo = invitelist[i];
		%>
            <li>                    
				<div class="nctouch-order-item">
					<div class="nctouch-order-item-con">
                        <div class="goods-block">
                            <a href="javascript:void(0);">
                                <div class="goods-pic"><img src="<%=memberinfo.user_logo%>" /></div>
                                <dl class="goods-info">
                                    <p>用户名：<%=memberinfo.user_name%></p>
									<p>手机号：<%=memberinfo.user_mobile%></p>
									<p>注册时间：<%=memberinfo.user_regtime%></p>
                                </dl>
                            </a>
                        </div>
                    </div>
                </div>
            </li>
        <% } %>
        <% if (hasmore) {%>
            <li class="loading">
                <div class="spinner"><i></i></div>会员数据读取中...</li>
            <% } %>
        <%}else {%>
            <div class="nctouch-norecord order">
                <div class="norecord-ico"><i></i></div>
                <dl>
                    <dt>您还没有相关的会员</dt>
                    <dd>可以去看看哪些想要买的</dd>
                </dl>
                <a href="<%=WapSiteUrl%>" class="btn">随便逛逛</a>
            </div>
        <%}%>
    </script>
    <script type="text/javascript" src="../../js/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/template.js"></script>
    
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/zepto.waypoints.js"></script>
    <script type="text/javascript" src="../../js/tmpl/directseller_invitelist.js"></script>
</body>

</html>
<?php 
include __DIR__.'/../../includes/footer.php';
?>
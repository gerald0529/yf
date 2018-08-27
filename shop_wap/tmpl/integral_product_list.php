<?php 
include __DIR__.'/../includes/header.php';
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
    <title>积分列表</title>
    <link rel="stylesheet" type="text/css" href="../css/base.css">
    <link rel="stylesheet" type="text/css" href="../css/nctouch_common.css">
    <link rel="stylesheet" type="text/css" href="../css/nctouch_products_list.css">
</head>
<body>
<header id="header" class="nctouch-product-header fixed">
    <div class="header-wrap">
        <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>
        <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup style="display: inline;"></sup></a> </div>
    </div>
    <div class="nctouch-nav-layout">
        <div class="nctouch-nav-menu"> <span class="arrow"></span>
            <ul>
                <li><a href="../index.html"><i class="home"></i>首页</a></li>
                <li><a href="../tmpl/cart_list.html"><i class="cart"></i>购物车<sup style="display: inline;"></sup></a></li>
                <li><a href="../tmpl/member/member.html"><i class="member"></i>我的商城</a></li>
                <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
            </ul>
        </div>
    </div>
</header>
<div class="goods-search-list-nav">
    <ul id="nav_ul">
        <li><a href="javascript:void(0);" class="current" id="sort_default">默认排序<i></i></a></li>
        <li><a href="javascript:void(0);" class="" id="grade_default">会员等级<i></i></a></li>
        <li><a href="javascript:void(0);" id="search_adv">筛选<i></i></a></li>
    </ul>
    <div class="browse-mode"><a href="javascript:void(0);" id="show_style"><span class="browse-list"></span></a></div>
</div>
<div id="sort_inner" class="goods-sort-inner hide">
    <span><a href="javascript:void(0);" onclick="init_get_list('order', 'default')">默认排序<i></i></a></span>
    <span><a href="javascript:void(0);" onclick="init_get_list('order', 'stimedesc')">最近上架<i></i></a></span>
    <span><a href="javascript:void(0);" onclick="init_get_list('order', 'pointsasc')">积分值从低到高<i></i></a></span>
    <span><a href="javascript:void(0);" onclick="init_get_list('order', 'pointsdesc')">积分值从高到低<i></i></a></span>
</div>
<div id="grade_inner" class="goods-sort-inner hide">
    <span><a href="javascript:void(0);" onclick="init_get_list('grade', 0)">会员等级<i></i></a></span>
    <span><a href="javascript:void(0);" onclick="init_get_list('grade', 1)">注册会员<i></i></a></span>
    <span><a href="javascript:void(0);" onclick="init_get_list('grade', 2)">铜牌会员<i></i></a></span>
    <span><a href="javascript:void(0);" onclick="init_get_list('grade', 3)">银牌会员<i></i></a></span>
    <span><a href="javascript:void(0);" onclick="init_get_list('grade', 4)">金牌会员<i></i></a></span>
    <span><a href="javascript:void(0);" onclick="init_get_list('grade', 5)">钻石会员<i></i></a></span>
</div>
<!--筛选部分-->
<div class="nctouch-full-mask hide">
    <div class="nctouch-full-mask-bg"></div>
    <div class="nctouch-full-mask-block">
        <div class="header">
            <div class="header-wrap">
                <div class="header-l"> <a href="javascript:void(0);"><i class="back"></i></a></div>
                <div class="header-title">
                    <h1>积分商品筛选</h1>
                </div>
                <div class="header-r"><a href="javascript:void(0);" id="reset" class="text">重置</a> </div>
            </div>
        </div>
        <div class="nctouch-main-layout-a secreen-layout" id="list-items-scroll" style="top: 2rem;"><div></div></div>
    </div>
</div>
<div class="nctouch-main-layout mt40 mb20">
    <div id="product_list" class="list">
        <ul class="goods-secrch-list">
        </ul>
    </div>
</div>
<div class="fix-block-r">
    <a href="javascript:void(0);" class="gotop-btn gotop" id="goTopBtn"><i></i></a>
</div>
<script type="text/html" id="integral_product_list">
    <% if (points_goods) { %>
    <% var items = points_goods.items %>
    <% for ( var i in items ) { %>
    <li class="goods-item integral-list">
				<span class="goods-pic">
					<a href="integral_product_detail.html?id=<%= items[i].id %>">
		                <img src="<%= items[i].points_goods_image %>">
		            </a>
				</span>
        <dl class="goods-info">
            <dt class="goods-name">
                <a href="integral_product_detail.html?id=<%= items[i].id %>">
                    <h4><%= items[i].points_goods_name %></h4>
                </a>
            </dt>
            <dd class="goods-sale">
                <span class="goods-price">所需积分：<em><%= items[i].points_goods_points %></em></span>

            </dd>
            <dd class="goods-assist">
                <span>参考价格：￥<%= items[i].points_goods_price %></span>
            </dd>
        </dl>
    </li>
    <% } %>
    <% } %>
</script>

<script type="text/html" id="search_items">
    <div>
        <dl>
            <dt>所需积分</dt>
            <dd>
                <span class="inp-balck"><input type="text" id="points_min" nctype="points" pattern="[0-9]*" class="inp" placeholder="最低积分"/></span>
                <span class="line"></span>
                <span class="inp-balck"><input type="text" id="points_max" nctype="points" pattern="[0-9]*" class="inp" placeholder="最高积分"/></span>
            </dd>
        </dl>
<!--        <dl>-->
<!--            <dt>兑换范围</dt>-->
<!--            <dd>-->
<!--                <a href="javascript:void(0);" nctype="items" id="is_self" class="">只看我能兑换</a>-->
<!--            </dd>-->
<!--        </dl>-->
        <div class="bottom">
            <a href="javascript:void(0);" class="btn-l" id="search_submit">筛选积分商品</a>
        </div>
    </div>
</script>


<script type="text/javascript" src="../js/zepto.js"></script>
<script type="text/javascript" src="../js/simple-plugin.js"></script>
<script type="text/javascript" src="../js/template.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/tmpl/integral_product_list.js"></script>
<script type="text/javascript" src="../js/tmpl/footer.js"></script>
</body>
</html>
<?php 
include __DIR__.'/../includes/footer.php';
?>
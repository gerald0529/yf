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
    <title>商品详情</title>
    <link rel="stylesheet" type="text/css" href="../css/base.css">
    <link rel="stylesheet" type="text/css" href="../css/nctouch_common.css">
    <link rel="stylesheet" type="text/css" href="../css/nctouch_products_list.css">
</head>
<body>
<header id="header" class="nctouch-product-header fixed">
    <div class="header-wrap">
        <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>
        <!--<div class="header-r"> <a href="../tmpl/product_first_categroy.html" class="categroy">
        </a> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup style="display: inline;"></sup></a> </div>-->
         <div class="header-title">
                <h1>热门代金券兑换</h1>
            </div>
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
<div class="goods-search-list-nav packets-type">
    <ul id="nav_ul">
        <li><a href="javascript:void(0);" class="current" id="sort_default">默认排序<i></i></a></li>
        <li><a href="javascript:void(0);" class="" id="price_default">优惠券面额<i></i></a></li>
        <li><a href="javascript:void(0);" id="search_adv">筛选<i></i></a></li>
    </ul>
    <div class="browse-mode"><a href="javascript:void(0);" id="show_style"><span class="browse-list"></span></a></div>
</div>

<div id="sort_inner" class="goods-sort-inner hide">
    <span><a href="javascript:void(0);" onclick="init_get_list('order', 'exchangenumdesc')">兑换量从高到低<i></i></a></span>
    <span><a href="javascript:void(0);" onclick="init_get_list('order', 'exchangenumasc')">兑换量从低到高<i></i></a></span>
    <span><a href="javascript:void(0);" onclick="init_get_list('order', 'pointsdesc')">积分值从高到低<i></i></a></span>
    <span><a href="javascript:void(0);" onclick="init_get_list('order', 'pointsasc')">积分值从低到高<i></i></a></span>
</div>

<div id="price_inner" class="goods-sort-inner hide">
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

<div class="nctouch-main-layout  mb20">
    <div id="product_list" class="list">
        <ul class="goods-secrch-list">
        </ul>
    </div>
</div>
<div class="fix-block-r">
    <a href="javascript:void(0);" class="gotop-btn gotop" id="goTopBtn"><i></i></a>
</div>

<script type="text/html" id="price_search">
    <% if (price_range) { %>
        <% for ( var i in price_range ) { %>
            <span><a href="javascript:void(0);" onclick="init_get_list('price', '<%= price_range[i].voucher_price %>')"><%= price_range[i].voucher_price_describe %><i></i></a></span>
        <% } %>
    <% } %>
</script>

<script type="text/html" id="voucher_list">
    <% if (voucher) { %>
    <% var items = voucher.items %>
    <% for ( var i in items ) { %>
    <li class="goods-item integral-list">
        <span class="goods-pic integral-pic">
                <a href="store.html?shop_id=<%= items[i].shop_id %>" class="store"><%= items[i].shop_name %></a>
                <p class="store-classify"><%= items[i].voucher_t_cat_name %></p>
                <img src="<%= items[i].voucher_t_customimg %>">
        </span>
        <dl class="goods-info  integral-info">
            <dt class="price">
                <a href="">
                    <h4 class="cole45">￥<em><%= items[i].voucher_t_price %></em></h4>
                </a>
            </dt>
            <dd class="goods-condition">
                <span class="">购物满¥<%= items[i].voucher_t_limit %>可用</span> <span class="">有效期至<%= items[i].voucher_t_end_date_day %></span>
            </dd>
            <dd class="goods-sale">
                <span class="goods-price">需<%= items[i].voucher_t_points %>积分</span>

            </dd>
            <dd class="goods-assist">
                <span><%= items[i].voucher_t_giveout %>张兑换</span>
            </dd>
            <dd class="goods-assist">
                <span class="tr"><a href="javascript:void(0)" nctype="exchange_integrate" data-vid="<%= items[i].id %>" class="btn btn-exchange">立即兑换</a></span>
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
        <div class="bottom">
            <a href="javascript:void(0);" class="btn-l" id="search_submit">筛选积分商品</a>
        </div>
    </div>
</script>


<script type="text/javascript" src="../js/zepto.js"></script>
<script type="text/javascript" src="../js/simple-plugin.js"></script>
<script type="text/javascript" src="../js/template.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/tmpl/voucher_list.js"></script>
<script type="text/javascript" src="../js/tmpl/footer.js"></script>
</body>
</html>
<?php 
include __DIR__.'/../includes/footer.php';
?>
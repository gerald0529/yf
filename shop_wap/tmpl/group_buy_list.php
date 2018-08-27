<?php 
include __DIR__.'/../includes/header.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>商城触屏版</title>
    <link rel="stylesheet" href="../css/base.css" />
    <link rel="stylesheet" href="../css/Group.css" />
    <link rel="stylesheet" href="../css/swiper.min.css" />
    <link rel="stylesheet" href="../css/nctouch_common.css" />
    <link rel="stylesheet" href="../css/nctouch_products_list.css" />
    <script src="../js/swiper.min.js"></script>
    <style type="text/css">
        .bor-2{border-bottom:1px solid red !important; color:red !important;}
    </style>
</head>

<body>
<div class="integral-wap fixed-Width">
   <!--  <div id="Group-head" class="clearfix">
        <div class="back-icon">
            <a href="javascript:history.go(-1)"> <i class="back"></i> </a>
        </div>
        <div class="swiper-container swiper-container-nav "></div>
        <div class="righttubiao">
            <a class="bottG"></a>
        </div>
    </div> -->
    <!-- 新增团购搜索 -->
      <header id="header" class="nctouch-product-header fixed">
        <div class="header-wrap">
            <div class="header-l"><a href="javascript:history.go(-1)"> <i class="back"></i> </a></div>
            <div class="header-inp clearfix" style="display:none"><a href="../tmpl/search.html?f=groupbuy&groupbuy_type=1" id="group_buy_search"> <i class="icon"></i> <span class="search-input col2" id="keyword">线上团</span></a></div>
            <div class="header-title"><h1 class=" group_buy">线上团</h1></div>
            <div class="header-r"><a href="../tmpl/product_first_categroy.html" class="categroy"><i style="display:none"></i> </a>
                <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a></div>
        </div>
        <div class="nctouch-nav-layout">
            <div class="nctouch-nav-menu"><span class="arrow"></span>
                <ul>
                    <li><a href="../index.html"><i class="home"></i>首页</a></li>
                    <li><a href="../tmpl/cart_list.html"><i class="cart"></i>购物车<sup></sup></a></li>
                    <li><a href="../tmpl/member/member.html"><i class="member"></i>我的商城</a></li>
                    <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>

                </ul>
            </div>
        </div>
    </header>
    <div class="goods-search-list-nav">
        <ul id="nav_ul">
            <li><a href="javascript:void(0);" class="current" id="sort_default">默认排序<i></i></a></li>
            <li><a href="javascript:void(0);" class="" id="grade_default">团购状态<i></i></a></li>
            <li class="browse-mode"><a href="javascript:void(0);" id="show_style"><span class="browse-grid"></span></a></li>
        </ul>
         <div class="ser-adv"><a href="javascript:void(0);" id="search_adv">筛选<i></i></a></div>
    </div>
    <div id="sort_inner" class="goods-sort-inner hide">
        <span><a href="javascript:void(0);" onclick="init_get_list('order', '')">默认排序<i></i></a></span>
        <span><a href="javascript:void(0);" onclick="init_get_list('order', 'pricedesc')">价格从高到低<i></i></a></span>
        <span><a href="javascript:void(0);" onclick="init_get_list('order', 'priceasc')">价格从低到高<i></i></a></span>
        <span><a href="javascript:void(0);" onclick="init_get_list('order', 'saledesc')">销量优先<i></i></a></span>
    </div>
    <div id="grade_inner" class="goods-sort-inner hide">
        <span><a href="javascript:void(0);" onclick="init_get_list('state', '')">正在进行<i></i></a></span>
        <span><a href="javascript:void(0);" onclick="init_get_list('state', 'underway')">即将开始<i></i></a></span>
    </div>

     <!--筛选部分-->
    <div class="nctouch-full-mask hide">
        <div class="nctouch-full-mask-bg"></div>
        <div class="nctouch-full-mask-block">
            <div class="header absolute">
                <div class="header-wrap">
                    <div class="header-l"> <a href="javascript:void(0);"><i class="back"></i></a></div>
                    <div class="header-title">
                        <h1>团购商品筛选</h1>
                    </div>
                    <div class="header-r"><a href="javascript:void(0);" id="reset" class="text">重置</a> </div>
                </div>
            </div>
            <div class="nctouch-main-layout-a secreen-layout" id="list-items-scroll" style="top: 2rem;"></div>
        </div>
    </div>
    <div class="nctouch-main-layout mt40 mb20">
        <div id="product_list" class="grid">
            <ul class="goods-secrch-list"></ul>
        </div>
    </div>
    <div class="maskG"></div>
</div>

<script type="text/html" id="swiper-container-nav">
    <div class="swiper-wrapper">
        <% if(data.cat.nav){ %>
        <% if(data.current_cat.length != 0){ %>
        <div class="swiper-slide" onclick="location.href='group_buy_list.html?groupbuy_type=<%= data.cat.type %>'">全部</div>
        <% }else{ %>
        <div class="swiper-slide bor-2" onclick="location.href='group_buy_list.html?groupbuy_type=<%= data.cat.type %>'">全部</div>
        <% }%>
        <% var items = data.cat.nav %>
        <% for( var i in items ){ %>
        <% if(data.current_cat && items[i].groupbuy_cat_id == data.current_cat.groupbuy_cat_id){ %>
        <div class="swiper-slide bor-2" onclick="location.href='group_buy_list.html?groupbuy_type=<%= data.cat.type %>&groupbuy_cat_id=<%= items[i].groupbuy_cat_id %>'"><%= items[i].groupbuy_cat_name %></div>
        <% }else{ %>
        <div class="swiper-slide" onclick="location.href='group_buy_list.html?groupbuy_type=<%= data.cat.type %>&groupbuy_cat_id=<%= items[i].groupbuy_cat_id %>'"><%= items[i].groupbuy_cat_name %></div>
        <% }%>
        <% }} %>
    </div>
</script>

<script type="text/html" id="maskG">
    <div class="title">
        <a href="group_buy_list.html?groupbuy_type=1">线上团</a>
    </div>
    <ul class="list clearfix">
        <% if(data.cat.physical){ %>
        <% var items = data.cat.physical %>
        <% for( var i in items ){ %>
        <li><a href="group_buy_list.html?groupbuy_type=1&groupbuy_cat_id=<%= items[i].groupbuy_cat_id %>"><%= items[i].groupbuy_cat_name %></a></li>
        <% }} %>
    </ul>
    <div class="title">
        <a href="group_buy_list.html?groupbuy_type=2">虚拟团</a>
    </div>
    <ul class="list clearfix">
        <% if(data.cat.virtual){ %>
        <% var items = data.cat.virtual %>
        <% for( var i in items ){ %>
        <li><a href="group_buy_list.html?groupbuy_type=2&groupbuy_cat_id=<%= items[i].groupbuy_cat_id %>"><%= items[i].groupbuy_cat_name %></a></li>
        <% }} %>
    </ul>
    <div class="X righttubiao"></div>
</script>

<script type="text/html" id="goods-secrch-list">
    <% if (data.groupbuy_goods) { %>
    <% var items = data.groupbuy_goods.items %>
    <% for ( var i in items ) { %>
    <li class="goods-item" goods_id="<%= items[i].goods_id %>" onclick="location.href='product_detail.html?goods_id=<%= items[i].goods_id %>'">
        <span class="goods-pic">
            <a href="product_detail.html?goods_id=<%= items[i].goods_id %>">
                <img src="<%= items[i].groupbuy_image %>">
                <div class="time fnTimeCountDown" data-end="<%= items[i].groupbuy_endtime %>">
                        <span class="ts">
                            <span class="day" >00</span><strong>天</strong>
                            <span class="hour">00</span><strong>小时</strong>
                            <span class="mini">00</span><strong>分</strong>
                            <span class="sec" >00</span><strong>秒</strong>
                        </span>
                </div>
            </a>
            
        </span>
        
        <dl class="goods-info">
            <dt class="goods-name">
                <a href="product_detail.html?goods_id=<%= items[i].goods_id %>">
                    <h4><%= items[i].groupbuy_name %> </h4>
                   <!--  <h6></h6> -->
                </a>
            </dt>
            <dt>
            <dd class="goods-sale">
                <a href="product_detail.html?goods_id=<%= items[i].goods_id %>">
                    <span class="goods-price">￥<em><%= items[i].groupbuy_price %></em></span>
                </a>
            </dd>
            </dt>
            <dd class="goods-assist goods-tuangou">
                <a href="product_detail.html?goods_id=<%= items[i].goods_id %>">
                                <span class="goods-sold">已售
                                    <em><%= items[i].groupbuy_virtual_quantity %></em>
                                </span>

                </a>
                <div class="btn-inter goods-store">
                    <a href="product_detail.html?goods_id=<%= items[i].goods_id %>">
                        <% if(items[i].is_start == 0){ %>
                            未开团
                        <% }else{ %>
                            <% if(items[i].goods_stock <= 0){ %>
                                已抢光
                            <% }else{ %>
                                我要团
                            <% } %>

                        <% } %>
                    </a>
                </div>
            </dd>
        </dl>
    </li>
    <% } %>
    <% } %>
</script>

<script type="text/html" id="search_items">
    <div style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
        <dl>
            <dt>价格</dt>
            <dd>
                <a href="javascript:;" nctype="items" onclick="init_rows('price','',this)">不限</a>
                <% if(data.price_range){ %>
                <% var items = data.price_range %>
                <% for( var i in items ){ %>
                <a href="javascript:;" nctype="items" onclick="init_rows('price','<%= items[i].range_id%>',this)"><%= items[i].range_name%></a>
                <% }} %>
            </dd>
        </dl>
        <% if(data.area){ %>
        <dl>
            <dt>区域</dt>
            <dd>
                <a href="javascript:void(0);" nctype="items" onclick="init_rows('area_id','',this)">不限</a>
                <% var items = data.area %>
                <% for( var i in items ){ %>
                <a href="javascript:void(0);" nctype="items" onclick="init_rows('area_id','<%= items[i].groupbuy_area_id%>',this)"><%= items[i].groupbuy_area_name%></a>
                <% } %>
            </dd>
        </dl>
        <% } %>
        <div class="bottom">
            <a href="javascript:void(0);" class="btn-l" id="search_submit" onclick="search_adv()">筛选商品</a>
        </div>
    </div>
</script>
<div class="fix-block-r">
    <a href="javascript:void(0);" class="gotop-btn gotop" id="goTopBtn"><i></i></a>
</div>

<script type="text/javascript" src="../js/zepto.js"></script>
<script type="text/javascript" src="../js/simple-plugin.js"></script>
<script type="text/javascript" src="../js/template.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/group_buy_list.js"></script>
<script type="text/javascript" src="../js/jquery.timeCountDown.js" ></script>
</body>

</html>
<?php 
include __DIR__.'/../includes/footer.php';
?>
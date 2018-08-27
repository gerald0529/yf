<?php
include __DIR__ . '/../../includes/header.php';
?>
<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="Author" contect="U2FsdGVkX1+liZRYkVWAWC6HsmKNJKZKIr5plAJdZUSg1A==">
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-touch-fullscreen" content="yes"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="msapplication-tap-highlight" content="no"/>
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1"/>
    <title>推广商品</title>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_products_list.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_common.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
    <style>
        .goods-search-list-nav {
            position: relative;
            top: 0px;
        }

        .goods-search-list-nav ul {
            width: 100%;
        }

        .goodsName {
            font-size: 0.65rem;
            color: #333;
            height: 1.8rem;
            overflow: hidden;
            word-wrap: break-word;
        }

        .goods-info p {
            color: #06bf04;
            font-size: 0.65rem;
        }
    </style>
</head>

<body>
<header id="header" class="nctouch-product-header fixed">
    <div class="header-wrap">
        <div class="header-l">
            <a href="javascript:history.go(-1)"> <i class="back"></i> </a>
        </div>
        <span class="header-title"><h1>推广商品</h1></span>
        <div class="header-r"><a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a>
        </div>
    </div>

    <div class="nctouch-nav-layout">
        <div class="nctouch-nav-menu"><span class="arrow"></span>
            <ul>
                <li><a href="../../index.html"><i class="home"></i>首页</a></li>
                <li><a href="../search.html"><i class="search"></i>搜索</a></li>
                <li><a href="../cart_list.html"><i class="cart"></i>购物车</a><sup></sup></li>
                <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
            </ul>
        </div>
    </div>
</header>

<div class="nctouch-order-search" style="margin-top:2rem;width:100%;">
    <form>
            <span class="ser-area ">
                <i class="icon-ser"></i>
				<input type="text" autocomplete="on" maxlength="50" placeholder="输入商品名称进行搜索" name="goodskey"
                       id="goodskey" oninput="writeClear($(this));">
				<span class="input-del"></span>
			</span>
        <input type="button" id="search_btn" value="搜索">
    </form>
</div>

<div class="goods-search-list-nav">
    <ul id="nav_ul">
        <li><a href="javascript:void(0);" class="current" id="sort_default">综合排序<i></i></a></li>
        <li><a href="javascript:void(0);" class="" onclick="init_get_list('uptime', 'DESC')">最新</a></li>
        <li><a href="javascript:void(0);" class="" onclick="init_get_list('sales','DESC');">最热</a></li>
    </ul>
</div>


<div id="sort_inner" class="goods-sort-inner hide">
    <span><a href="javascript:void(0);" class="cur" onclick="init_get_list('', '')">综合排序<i></i></a></span> <span><a
                href="javascript:void(0);" onclick="init_get_list('commission', 'DESC')">佣金从高到低<i></i></a></span>
    <span><a href="javascript:void(0);" onclick="init_get_list('commission', 'ASC')">佣金从低到高<i></i></a></span>
</div>

<div class="mb20 t">
    <div id="product_list" class="list">
        <ul class="goods-secrch-list">
        </ul>
    </div>
</div>

<div class="fix-block-r">
    <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>
</div>
<footer id="footer" class="bottom"></footer>
</body>
<script type="text/html" id="home_body">
    <% var common_list = data.items; %>
    <% if(common_list.length >0){ %>
    <% for(j=0;j < common_list.length; j++){ %>
    <% var goods_info = common_list[j]; %>
    <% if(goods_info.goods_id.goods_id){
            goods_id = goods_info.goods_id.goods_id;
            }else{
            goods_id = goods_info.goods_id[0].goods_id;
    } %>
    <li class="goods-item" goods_id="<%=goods_id%>">
		<span class="goods-pic">
			<a href="../product_detail.html?goods_id=<%=goods_id%>&rec=u<%=data.user_id%>s<%=goods_info.shop_id%>c<%=goods_info.common_id%>">
                <img style="width:4rem;height:4rem;" src="<%=goods_info.common_image%>">
            </a>
		</span>
        <dl class="goods-info" style="height:4rem;">
            <dt class="goodsName">
                <a href="../product_detail.html?goods_id=<%=goods_id%>&rec=u<%=data.user_id%>s<%=goods_info.shop_id%>c<%=goods_info.common_id%>">
                    <h4><%=goods_info.common_name%></h4>
                </a>
            </dt>
            <dd class="goods-sale">
                <span class="goods-price">￥<em><%=goods_info.common_price%></em></span>
            </dd>
            <p>推广佣金：￥<%= goods_info.common_cps_rate * goods_info.common_price/100 %></p>
        </dl>
    </li>
    <%}%>
    <% if (hasmore) {%>
    <li class="loading">
        <div class="spinner"><i></i></div>
        商品数据读取中...
    </li>
    <% } %>
    <%
    }else {
    %>
    <div class="nctouch-norecord search">
        <div class="norecord-ico"><i></i></div>
        <dl>
            <dt>没有找到任何相关信息</dt>
            <dd>选择或搜索其它商品分类/名称...</dd>
        </dl>
    </div>
    <%
    }
    %>
</script>

<script type="text/javascript" src="../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../js/template.js"></script>
<script type="text/javascript" src="../../js/common.js"></script>
<script type="text/javascript" src="../../js/tmpl/directseller_goods.js"></script>
<script type="text/javascript" src="../../js/tmpl/footer.js"></script>

</html>
<?php
include __DIR__ . '/../../includes/footer.php';
?>

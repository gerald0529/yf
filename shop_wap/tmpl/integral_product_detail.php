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
    <link rel="stylesheet" type="text/css" href="../css/nctouch_products_detail.css">
    <link rel="stylesheet" type="text/css" href="../css/iconfont.css">
</head>
<body>
<header id="header" class="posf">
    <div class="header-wrap">
        <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>
        <ul class="header-nav">
            <li class="cur"><a href="javascript:void(0);" id="productDetail" >商品</a></li>
            <li><a href="javascript:void(0)" id="productBody">详情</a></li>
        </ul>
        <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>
    </div>
    <div class="nctouch-nav-layout">
        <div class="nctouch-nav-menu"> <span class="arrow"></span>
            <ul>
                <li><a href="../index.html"><i class="home"></i>首页</a></li>
                <li><a href="../tmpl/search.html"><i class="search"></i>搜索</a></li>
                <li><a href="../tmpl/cart_list.html"><i class="cart"></i>购物车<sup></sup></a></li>
                <li><a href="../tmpl/member/member.html"><i class="member"></i>我的商城</a></li>
                <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
            </ul>
        </div>
    </div>
</header>
<div id="product_detail_html" class="pt-100 bgf"></div>
<div id="product_detail_spec_html" class="nctouch-bottom-mask down"></div>
<div class="goods-detail-foot">
   <!--  <div class="otreh-handle">
        <a href="javascript:void(0);" class="kefu"><i></i><p>客服</p></a>
        <a href="http://wap.bbc-builder.com/tmpl/cart_list.html" class="cart"><i></i><p>购物车</p><span id="cart_count"></span></a>
    </div> -->
    <div class="buy-handle wp100">

        <a href="javascript:void(0);" class="animation-up buy-now ">立即兑换</a>
    </div>
</div>

<script type="text/html" id="product_detail">
    <% if (goods_detail) { %>
    <div class="goods-detail-top">
        <div class="goods-detail-pic" id="mySwipe">
            <ul>
                <li><img src="<%= goods_detail.points_goods_image %>"/></li>
            </ul>
        </div>
    </div>
    <div class="goods-detail-cnt bgf">
        <div class="goods-detail-name">
            <dl>
                <dt class="fz-28 col2"><%= goods_detail.points_goods_name %></dt>
            </dl>
        </div>
        <div class="goods-detail-price integral-detail pl-20 pr-20 pb-20 borb1">
            <dl>
                <dt class="fz-30 default-color"><em><%= goods_detail.points_goods_points %>积分</em>
                </dt>
                <dd class="fz-24 colbc pt-10">原价：<%= goods_detail.points_goods_price %>元</dd>
            </dl>
            <% if ( goods_detail.points_goods_limitgrade > 1 ) { %>
            <span class="sold level">v<%= goods_detail.points_goods_limitgrade - 1 %>专享</span>
            <% } %>
        </div>
        <div class="goods-detail-item mb25" id="goods_spec_selected">
            <div class="itme-name fz-28 colbc">已选</div>
            <div class="item-con">
                <dl class="goods-detail-sel">
                    <dt>

                        <span>默认</span>
                    </dt>
                </dl>
            </div>
            <i class="iconfont icon-arrow-right align-middle fz-30 col9 fr mt-d3"></i>
        </div>
    </div>
    <% } %>
</script>

<script type="text/html" id="product_detail_spec">
    <% if (goods_detail) { %>
    <div class="nctouch-bottom-mask-bg"></div>
    <div class="nctouch-bottom-mask-block">
        <div class="nctouch-bottom-mask-tip"><i></i>点击此处返回</div>
        <div class="nctouch-bottom-mask-top goods-options-info borb1">
            <div class="goods-pic">
                <img src="<%= goods_detail.points_goods_image %>"/>
            </div>
            <dl>
                <dt class="fz-28 col2 more-overflow"><%= goods_detail.points_goods_name %></dt>
                <dd class="goods-price integral-price fz-28 wp100">
                    <em class="price-color"><%= goods_detail.points_goods_points %>积分</em>
                    <span class="goods-storage">库存：<%= goods_detail.points_goods_storage %></span>
                </dd>
            </dl>
            <a href="javascript:void(0);" class="nctouch-bottom-mask-close"><i></i></a>
        </div>
        <div class="nctouch-bottom-mask-rolling" id="product_roll">
            <div class="goods-options-stock" style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
            </div>
        </div>
       
        <div class="goods-option-foot">
            <div class="goods-option-value fz-28 colbc mb-0">购买数量
                <div class="value-box">
                    <span class="minus">
                        <a href="javascript:void(0);">&nbsp;</a>
                    </span>
                    <span>
                        <input type="text" pattern="[0-9]*" class="buy-num" id="buynum" value="1">
                    </span>
                    <span class="add">
                        <a href="javascript:void(0);">&nbsp;</a>
                    </span>
                </div>
            </div>
            <!-- <div class="otreh-handle">
                <a href="javascript:void(0);" class="kefu">
                    <i></i>
                    <p>客服</p>
                </a>
                <a href="../tmpl/cart_list.html" class="cart">
                    <i></i>
                    <p>购物车</p>
                    <span id="cart_count1"></span>
                </a>
            </div> -->
            <div class="buy-handle wp100">

                <a href="javascript:void(0);" class="buy-now " id="buy-now">立即兑换</a>
            </div>
        </div>
    </div>
    <% } %>
</script>

<script type="text/javascript" src="../js/zepto.min.js"></script>

<script type="text/javascript" src="../js/template.js"></script>
<script type="text/javascript" src="../js/swipe.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/iscroll.js"></script>
<script type="text/javascript" src="../js/simple-plugin.js"></script>
<script type="text/javascript" src="../js/tmpl/footer.js"></script>
<script type="text/javascript" src="../js/fly/requestAnimationFrame.js"></script>
<script type="text/javascript" src="../js/fly/zepto.fly.min.js"></script>
<script type="text/javascript" src="../js/tmpl/integral_product_detail.js"></script>

</body>
</html>
<?php 
include __DIR__.'/../includes/footer.php';
?>
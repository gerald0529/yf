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
    <link rel="stylesheet" type="text/css" href="../css/Group.css">


</head>
<body>
<header id="header" class="posf">
    <div class="header-wrap">
        <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>
        <ul class="header-nav">
            <li class="cur"><a href="javascript:void(0)" id="productDetail">商品</a></li>
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
<div id="product_detail_html" style="position: relative; z-index: 1;">

</div>
<div class="Gfooter">
    <a href="javascript:;" class="ljgm">
        我要团
    </a>
</div>

<script type="text/html" id="product_detail">
    <div class="goods-detail-top">
        <div class="goods-detail-pic" id="mySwipe">
            <ul>
                <li><img src="<%= groupbuy_detail.groupbuy_image %>"/></li>
            </ul>
        </div>
    </div>

    <div class="goods-detail-cnt">
        <div class="priceTime clearfix">
            <div class="price">
                <span class="lt"><b>￥</b><%= groupbuy_detail.groupbuy_price %></span>
            <span class="rt">
        <span><b><%= groupbuy_detail.groupbuy_virtual_quantity %></b>件已团购</span>
            <del><b>￥</b><%= groupbuy_detail.goods_price %></del>
            </span>
            </div>
            <div class="time">
                <p class="txt">距结束仅剩</p>
                <div class="hms fnTimeCountDown" data-end="<%= groupbuy_detail.groupbuy_endtime %>">
                                    <span class="day" >00</span><strong>天</strong>
                                    <span class="hour">00</span><strong>小时</strong>
                                    <span class="mini">00</span><strong>分</strong>
                                    <span class="sec" >00</span><strong>秒</strong>
                </div>
            </div>
        </div>
        <div class="goods-detail-name">
            <dl>
                <dt><%= groupbuy_detail.groupbuy_name %></dt>
            </dl>
        </div>
        <p class="prompt"><%= groupbuy_detail.groupbuy_remark %></p>
        <div class="goods-detail-store">
            <a href="store.html?shop_id=<%= groupbuy_detail.shop_id %>">
                <div class="store-name"><i class="icon-store"></i><%= shop_base.shop_name %></div>
                <div class="store-rate">
                <span class="">描述相符
                    <em><%= shop_base.shop_desc_scores %></em>
                    <i></i>
                </span>
                <span class="">服务态度
                    <em><%= shop_base.shop_service_scores %></em>
                    <i></i>
                </span>
                <span class="">发货速度
                    <em><%= shop_base.shop_send_scores %></em>
                    <i></i>
                </span>
                </div>
                <div class="item-more"></div>
            </a>
        </div>
        <div class="goods-detail-recom">
            <% if(data_foot_recommon_goods){ %>
            <h4>店铺推荐</h4>
            <ul>
                <% for(var i in data_foot_recommon_goods){ %>
                <li>
                    <a href="product_detail.html?goods_id=<%= data_foot_recommon_goods[i].goods_id %>">
                        <div class="pic"><img src="<%= data_foot_recommon_goods[i].common_image %>"></div>
                        <dl>
                            <dt><%= data_foot_recommon_goods[i].common_name %></dt>
                            <dd>￥<em><%= data_foot_recommon_goods[i].common_price %></em></dd>
                        </dl>
                    </a>
                </li>
                <% } %>
            </ul>
            <% } %>
        </div>
    </div>
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
<script type="text/javascript" src="../js/group_buy_goods.js"></script>
<script type="text/javascript" src="../js/jquery.timeCountDown.js" ></script>
</body>
</html>
<?php 
include __DIR__.'/../includes/footer.php';
?>
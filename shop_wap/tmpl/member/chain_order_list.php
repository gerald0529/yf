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
    <meta name="wap-font-scale" content="no">
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
    <title>自提订单</title>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_common.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_cart.css">
    <link rel="stylesheet" type="text/css" href="../../css/private-store.css"/>
    <link rel="stylesheet" href="../../css/iconfont.css">
</head>
<body>
   		<header id="header" class="fixed">
	        <div class="header-wrap">
	            <div class="header-l">
	                <a href="javascript:history.go(-1)"> <i class="back"></i> </a>
	            </div>
	            <div class="header-title">
	                <h1>门店自提订单</h1>
	            </div>
	        </div>
	    </header>
    	<div class="nctouch-main-layout">
        	<div class="nctouch-order-search">
           		<form>
               		<span class="ser-area ">
               			<i class="icon-ser"></i>
               			<input type="text" autocomplete="on" maxlength="50" placeholder="输入商品标题或订单号进行搜索" name="order_key" id="order_key" oninput="writeClear($(this));">
     					<span class="input-del"></span>
               		</span>
               		<input type="button" id="search_btn" value="搜索">
            	</form>
       		</div>
        </div>
        <div id="fixed_nav" class="nctouch-single-nav">
            <ul id="filtrate_ul" class="w20h">
                <li class="selected"><a href="javascript:;" data-state="">全部</a></li>
                <li><a href="javascript:;" data-state="chain_finish">已完成</a></li>
                <li><a href="javascript:;" data-state="received">待评价</a></li>
                <li><a href="javascript:;" data-state="order_chain">待自提</a></li>
                <li><a href="javascript:;" data-state="wait_pay">待付款</a></li>
            </ul>
        </div>
        <!--暂无订单-->
         <!--<div class="norecord tc">
            <div class="ziti-store">
                <i></i>
            </div>
            <p class="fz-30 col9">暂无任何订单</p >
        </div>-->
        <div class="nctouch-order-list bgf8" id="chain-order-list">

        </div>
    <div class="fix-block-r">
        <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>
    </div>
    <footer id="footer" class="bottom"></footer>
<script type="text/html" id="chain-order-list-tmp">
    <% if (chainOrderList.length > 0) { %>
        <ul id="order-list">
        <% for (var i = 0; i < chainOrderList.length; i++) {  
            var goods_list = chainOrderList[i].goods_list
        %>
            <li class="green-order-skin ">
                <div class="nctouch-order-item">
                    <div class="nctouch-order-item-head">
                        <a href="<%=WapSiteUrl%>/tmpl/store.html?shop_id=<%=chainOrderList[i].shop_id%>" class="store"><i class="iconfont icon-stores align-middle fz-30 mr-10"></i><span class="store-tit-text align-middle"><%=chainOrderList[i].shop_name%></span><i class="iconfont icon-arrow-right align-middle ml-10 fz-26 col9"></i></a>
                        <span class="state">
                        <% if(chainOrderList[i].order_status == 6 && chainOrderList[i].order_buyer_evaluation_status == 0){ %>
                            <span class="ot-nofinish">待评价</span>
                        <% }else{ %>
                            <span class="ot-nofinish"><%=chainOrderList[i].order_state_con%></span>
                        <% } %>
                        </span>
                    </div>
                    <div class="nctouch-order-item-con">
                    <% for (var j = 0; j < goods_list.length; j++) { %>
                        <div class="goods-block z-ztbgc">
                            <a href="<%=WapSiteUrl%>/tmpl/member/order_detail.html?from=chain&order_id=<%=chainOrderList[i].id%>" class="clearfix wp100">
                                <div class=""> 
                                    <div class="goods-pic">
                                        <img src="<%=goods_list[j]['goods_image']%>">
                                    </div>
                                    <dl class="goods-info">
                                        <dt class="goods-name"><%=goods_list[j]['goods_name']%></dt>
                                        <dd class="goods-type one-overflow"><%=goods_list[j]['title_order_spec_info']%></dd>
                                    </dl>
                                </div>
                                <div class="goods-subtotal">
                                    <span class="goods-price">￥<em><%=goods_list[j]['goods_price']%></em></span>
                                    <span class="goods-num goods-num-top">x<%=goods_list[j]['order_goods_num']%></span>
                                    <div class="fz0">
                                    </div>
                                </div>
                            </a>
                        </div>
                    <% } %>
                    </div>
                    <div class="nctouch-order-item-footer">
                        <div class="store-totle">
                            <span>共<em><%=chainOrderList[i]['order_nums']%></em>件商品，合计</span><span class="sum">￥<em><%=chainOrderList[i]['order_payment_amount']%></em></span>
                        </div>
                        <% if(chainOrderList[i].order_status == 6 || chainOrderList[i].order_status == 1 || chainOrderList[i].order_status == 7){ %>
                        <div class="handle">
                        <%if(chainOrderList[i].order_status == 7 || chainOrderList[i].order_status == 6){%>
                        <a href="javascript:void(0)" order_id="<%=chainOrderList[i].order_id%>" class="del delete-order btn">删除</a>
                        <%}%>
                        <% if(chainOrderList[i].order_status == 6 && chainOrderList[i].order_buyer_evaluation_status == 1){ %>
                            <a href="javascript:void(0)" order_id="<%=chainOrderList[i].id%>" class="del btn view-evaluation">查看评价</a>
                        <% } %>
                        <% if(chainOrderList[i].order_status == 6 && chainOrderList[i].order_buyer_evaluation_status == 0){ %>
                            <a href="javascript:void(0)" order_id="<%=chainOrderList[i].id%>" class="del btn evaluation-order">立即评价</a>
                        <% } %>
                        <% if(chainOrderList[i].order_status == 1){ %>
                            <a href="javascript:void(0)" order_id="<%=chainOrderList[i].id%>" class="del btn check-payment" onclick="payOrder('<%= chainOrderList[i].payment_number %>','<%= chainOrderList[i].id %>')" data-paySn="<%= chainOrderList[i].id %>">立即付款</a>
                        <% } %>
                        </div>
                        <% } %>
                    </div>
                </div>
            </li>
        <% } %>
        </ul>
    <% }else{ %>
    <!--暂无订单-->
        <div class="norecord tc">
            <div class="ziti-store">
                <i></i>
            </div>
            <p class="fz-30 col9">暂无任何订单</p >
        </div>
    <% } %>
</script>
<script type="text/javascript" src="../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../js/template.js"></script>
<script type="text/javascript" src="../../js/common.js"></script>
<script type="text/javascript" src="../../js/simple-plugin.js"></script>
<script type="text/javascript" src="../../js/zepto.waypoints.js"></script>
<script type="text/javascript" src="../../js/tmpl/chain_order_list.js"></script>
</body>
</html>
<?php 
include __DIR__.'/../../includes/footer.php';
?>
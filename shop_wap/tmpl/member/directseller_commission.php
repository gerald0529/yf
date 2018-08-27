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
    <title>佣金明细</title>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_common.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_cart.css">
</head>

<body>
    <header id="header" class="fixed">
        <div class="header-wrap">
            <div class="header-l"><a href="directseller.html"><i class="back"></i></a></div>
            <div class="header-title"><h1>佣金明细</h1></div>
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
   
    <div class="nctouch-main-layout-reset">
        <div class="nctouch-order-search">
            <form>
                <span class="ser-area">
                    <i class="icon-ser"></i>
					<input type="text" autocomplete="on" maxlength="50" placeholder="输入订单号进行搜索" name="order_key" id="order_key" oninput="writeClear($(this));" >
					<span class="input-del"></span>
				</span>
                <input type="button" id="search_btn" value="搜索">
            </form>
        </div>
		
        <div id="fixed_nav" class="nctouch-single-nav">
            <ul id="filtrate_ul" class="w20h">
                <li class="selected"><a href="javascript:void(0);" data-state="">一级佣金</a></li>
                <li><a href="javascript:void(0);" data-state="second">二级佣金</a></li>
                <li><a href="javascript:void(0);" data-state="third">三级佣金</a></li>
            </ul>
        </div>
        <div class="nctouch-order-list">
            <ul id="order-list"></ul>
        </div> 
    </div>
	
    <div class="fix-block-r">
        <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>
    </div>
    <footer id="footer" class="bottom"></footer>
    <script type="text/html" id="order-list-tmpl">
        <% var orderlist = data.items; %>
        <% if (orderlist.length > 0){%>
        <% for(var i = 0;i<orderlist.length;i++)
		{
			var orderinfo = orderlist[i];
			var goodslist = orderinfo.goods_list;
		%>
            <li class="<%if(orderinfo.order_payment_amount){%>green-order-skin<%}else{%>gray-order-skin<%}%> <%if(i>0){%>mt-20<%}%>">                    
				<div class="nctouch-order-item">
                    <div class="nctouch-order-item-head">
                        <%if (orderinfo.shop_self_support){%>
                            <a class="store"><i class="icon"></i>自营店铺</a>
                        <%}else{%>
                            <a href="<%=WapSiteUrl%>/tmpl/store.html?shop_id=<%=orderinfo.shop_id%>" class="store"><i class="icon"></i><%= orderinfo.shop_name %></a>
                        <%}%>
											 
                        <span class="state">
							<span class="ot-nofinish">
								<%if(orderinfo.directseller_is_settlement==1){%>
								已结算
								<%}else{%>
									未结算
								<%}%>
							</span>
                        </span>
                    </div>
					
					<div class="nctouch-order-item-head">
						<span class="fz-26 fr col6">订单号：<%=orderinfo.order_id%></span>
					</div>
					<div class="nctouch-order-item-con bgf">
                        <%  count = 0;
                            for(var j = 0;j<goodslist.length;j++){
                            var order_goods = goodslist[j];
                            count += order_goods.order_goods_num;
						
                        %>
                                <div class="goods-block">
                                    <a href="<%=WapSiteUrl%>/tmpl/product_detail.html?goods_id=<%=order_goods.goods_id%>" class="wp100">
                                        <div class="goods-pic"><img src="<%=order_goods.goods_image%>" /></div>
                                        <dl class="goods-info">
                                            <dt class="goods-name"><%=order_goods.goods_name%></dt>
											<dt class="goods-name default-color fz-24">
												佣金：￥
												<%if(data.t=='second'){%>
												<%=order_goods.directseller_commission_1%>
												<%}else if(data.t=='third'){%>
												<%=order_goods.directseller_commission_2%>	
												<%}else{%>
												<%=order_goods.directseller_commission_0%>
												<%}	%>
                                                <%=order_goods.goods_return_status_con %>&nbsp;
                                                <%=order_goods.goods_refund_status_con%>
											</dt>
                                            <%if(order_goods.order_spec_info){%>
                                            <dd class="goods-type">
                                                <%=order_goods.order_spec_info%>
                                            </dd>
                                            <%}%>
                                        </dl>
										
                                        <div class="goods-subtotal">
                                            <span class="goods-price">￥<em><%=order_goods.goods_price%></em></span>
                                            <span class="goods-num">x<%=order_goods.order_goods_num%></span>
                                        </div>
                                    </a>
                                        </div>
                        <%}%>
                    </div>
                   
                </div>
            </li>
        <%}%>
        <% if (hasmore) {%>
            <li class="loading">
                <div class="spinner"><i></i></div>订单数据读取中...</li>
            <% } %>
        <%}else {%>
            <div class="nctouch-norecord order">
                <div class="norecord-ico"><i></i></div>
                <dl>
                    <dt>暂无任何佣金明细</dt>
                </dl>
            </div>
        <%}%>
    </script>
    <script type="text/javascript" src="../../js/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/template.js"></script>
    
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/zepto.waypoints.js"></script>
    <script type="text/javascript" src="../../js/tmpl/order_payment_common.js"></script>
    <script type="text/javascript" src="../../js/tmpl/directseller_commission.js"></script>
</body>

</html>
<?php 
include __DIR__.'/../../includes/footer.php';
?>
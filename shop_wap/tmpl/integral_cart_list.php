<?php 
include __DIR__.'/../includes/header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">


    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1,viewport-fit:cover;">
    <title>积分购物车</title>
    <link rel="stylesheet" type="text/css" href="../css/base.css">
    <link rel="stylesheet" type="text/css" href="../css/nctouch_common.css">
    <link rel="stylesheet" type="text/css" href="../css/nctouch_cart.css">
</head>
<style>
	.buy-li{padding: 0.681rem;}
	.nctouch-cart-item li .goods-info{width: 60%;}
	.nctouch-cart-item li .goods-info dt.goods-name a{width: 8rem;}
    .value-box{float: left;margin-top: 0.5rem;}
    .nctouch-cart-item li .goods-del{width: 0.636rem;height: 0.681rem;background: none;}
    .nctouch-cart-item li .goods-del a{background-size: 100%;opacity: 1;margin-top: 1rem;}
    .nctouch-cart-item{padding-bottom: 0 !important;}
</style>
<body>
<header id="header" class="fixed">
    <div class="header-wrap">
        <div class="header-l">
            <a href="javascript:history.go(-1)"> <i class="back"></i> </a>
        </div>
        <div class="header-title">
            <h1>积分购物车</h1>
        </div>
        <div class="header-r">
            <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a>
        </div>
    </div>
    <div class="nctouch-nav-layout">
        <div class="nctouch-nav-menu">
            <span class="arrow"></span>
            <ul>
                <li><a href="../../index.html"><i class="home"></i>首页</a></li>
                <li><a href="./search.html"><i class="search"></i>搜索</a></li>
                <li><a href="./member/member.html"><i class="member"></i>我的商城</a></li>
                <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
            </ul>
        </div>
    </div>
</header>
<div class="nctouch-main-layout">
    <div id="cart-list-wp"></div>



        <!--<div class="nctouch-cart-container">
            <dl class="nctouch-cart-store">
                <dt><span class="store-check">
							<input class="store_checkbox" type="checkbox" checked="">
						</span> <i class="icon-store"></i> 林小夕的店铺

                </dt>


            </dl>
            <ul class="nctouch-cart-item">


                <li cart_id="1530" class="cart-litemw-cnt">
                    <div class="goods-check">
                        <input type="checkbox" checked="" name="cart_id" value="1530">
                    </div>
                    <div class="goods-pic">
                        <a href="http://wap.bbc-builder.com/tmpl/product_detail.html?goods_id=983">
                            <img src="http://shop.bbc-builder.com/image.php/media/10244/59/image/20160902/1472789654555675.jpeg!120x120.jpeg">
                        </a>
                    </div>
                    <dl class="goods-info">
                        <dt class="goods-name"><a href="＃"> 海尔（haier）XNO28-YEW 智慧嫩烤箱 电烤箱 蒸汽烤箱 家用 </a></dt>

                    </dl>
                    <div class="goods-del" cart_id="1530"><a href="javascript:void(0);"></a></div>
                    <div class="goods-subtotal"><span class="goods-price"><em>100积分</em></span>
							<span class="goods-sale">

							</span>
                        <div class="value-box">
								<span class="minus">
									<a href="javascript:void(0);">&nbsp;</a>
								</span>
        						<span>
									<input type="text" pattern="[0-9]*" readonly="" class="buy-num buynum" value="1">
								</span>
								<span class="add">
									<a href="javascript:void(0);">&nbsp;</a>
								</span>
                        </div>
                    </div>
                </li>


            </ul>

            <div class="nctouch-bottom-mask nctouch-bottom-mask0">
                <div class="nctouch-bottom-mask-bg"></div>
                <div class="nctouch-bottom-mask-block">
                    <div class="nctouch-bottom-mask-tip"><i></i>点击此处返回</div>
                    <div class="nctouch-bottom-mask-top store-voucher">
                        <i class="icon-store"></i> 林小夕的店铺&nbsp;&nbsp;领取店铺代金券
                        <a href="javascript:void(0);" class="nctouch-bottom-mask-close"><i></i></a>
                    </div>
                    <div class="nctouch-bottom-mask-rolling nctouch-bottom-mask-rolling0">
                        <div class="nctouch-bottom-mask-con">
                            <ul class="nctouch-voucher-list">

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="nctouch-cart-container">
            <dl class="nctouch-cart-store">
                <dt><span class="store-check">
							<input class="store_checkbox" type="checkbox" checked="">
						</span> <i class="icon-store"></i> 爱丽丝仙境

                </dt>


            </dl>
            <ul class="nctouch-cart-item">


                <li cart_id="1544" class="cart-litemw-cnt">
                    <div class="goods-check">
                        <input type="checkbox" checked="" name="cart_id" value="1544">
                    </div>
                    <div class="goods-pic">
                        <a href="http://wap.bbc-builder.com/tmpl/product_detail.html?goods_id=651">
                            <img src="http://shop.bbc-builder.com/image.php/media/10244/59/image/20160902/1472789654555675.jpeg!120x120.jpeg">
                        </a>
                    </div>
                    <dl class="goods-info">
                        <dt class="goods-name">
                            <a href="http://wap.bbc-builder.com/tmpl/product_detail.html?goods_id=651"> 苹果(Apple) iPhone 6s Plus 4G手机 深空灰 公开版(64G ROM)标配 </a>
                        </dt>

                    </dl>
                    <div class="goods-del" cart_id="1544"><a href="javascript:void(0);"></a></div>
                    <div class="goods-subtotal"><span class="goods-price"><em>100积分</em></span>
							<span class="goods-sale">

							</span>
                        <div class="value-box">
								<span class="minus">
									<a href="javascript:void(0);">&nbsp;</a>
								</span>
        						<span>
									<input type="text" pattern="[0-9]*" readonly="" class="buy-num buynum" value="1">
								</span>
								<span class="add">
									<a href="javascript:void(0);">&nbsp;</a>
								</span>
                        </div>
                    </div>
                </li>


            </ul>

        </div>



        <div class="nctouch-cart-bottom">
            <div class="all-check"><input class="all_checkbox" type="checkbox" checked=""></div>
            <div class="total">
                <dl class="total-money">
                    <dt>合计总积分：</dt>
                    <dd><em>100积分</em></dd>
                </dl>
            </div>
            <div class="check-out ok">
                <a href="javascript:void(0)">确认兑换</a>
            </div>
        </div>-->


</div>
<footer id="footer" class="bottom"></footer>

<script id="cart-list" type="text/html">
    <% var integral_cart_list = items; %>

    <% if ( integral_cart_list && integral_cart_list.length > 0  ) { %>
         <div class="nctouch-cart-container">
        <% for ( var i in integral_cart_list ) { %>
           
            <ul class="nctouch-cart-item borb1">
                <li cart_id="<%= integral_cart_list[i].id %>" class="buy-li clearfix box-size cart-litemw-cnt">
                    <div class="goods-check">
                        <input type="checkbox" name="cart_id" value="<%= integral_cart_list[i].id %>">
                    </div>
                    <div class="goods-pic">
                        <a href="integral_product_detail.html?id=<%= integral_cart_list[i].points_goods_id %>">
                            <img src="<%= integral_cart_list[i].points_goods_image %>">
                        </a>
                    </div>
                    <dl class="goods-info fl pl-20">
                        <dt class="goods-name"><a href="integral_product_detail.html?id=<%= integral_cart_list[i].points_goods_id %>" class="z-dhwz"><%= integral_cart_list[i].points_goods_name %></a></dt>
                    </dl>
                    <div class="value-box pl-20">
                        <!--购物车减操作-->
                        <span class="minus">
                            <a href="javascript:void(0);">&nbsp;</a>
                        </span>
                        <!--数量-->
                        <span>
                            <input type="text" pattern="[0-9]*" readonly="" class="buy-num buynum" value="<%= integral_cart_list[i].points_goods_choosenum %>" data-point="<%= integral_cart_list[i].points_goods_points %>">
                        </span>
                        <!--购物车加操作-->
                        <span class="add">
                            <a href="javascript:void(0);">&nbsp;</a>
                        </span>
                    </div>
                    <div class="goods-del" cart_id="<%= integral_cart_list[i].id %>">
                        <a href="javascript:void(0);"></a>
                    </div>
                    <div class="goods-subtotal">
                        <span class="goods-price">
                            <em><%= integral_cart_list[i].points_goods_points %>积分</em>
                        </span>
                    </div>
                </li>
            </ul>
           

            
        <% }%>
         </div>
         <div class="nctouch-cart-bottom">
                <div class="all-check fl pl-20"><input class="all_checkbox" type="checkbox" /></div>
                <div class="total">
                    <dl class="total-money"><dt>合计总积分：</dt><dd><em>0积分</em></dd></dl>
                </div>
                <div class="check-out">
                    <a href="javascript:void(0)">确认兑换</a>
                </div>
            </div>

    <% } else { %>
        <div class="nctouch-norecord cart">
            <div class="norecord-ico"><i></i></div>
            <dl>
                <dt>您的购物车还是空的</dt>
                <dd>去挑一些中意的商品吧</dd>
            </dl>
            <a href="<%= WapSiteUrl %>" class="btn">随便逛逛</a>
        </div>
    <% } %>
</script>

<script type="text/javascript" src="../js/zepto.js"></script>

<script type="text/javascript" src="../js/template.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/simple-plugin.js"></script>
<script type="text/javascript" src="../js/integral_cart_list.js"></script>

</body>
</html>
<?php 
include __DIR__.'/../includes/footer.php';
?>
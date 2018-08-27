<?php
include __DIR__ . '/../includes/header.php';
?>
    <!DOCTYPE html>
    <html>
    <head>
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
        <title>购物车</title>
        <link rel="stylesheet" type="text/css" href="../css/base.css">
        <link rel="stylesheet" type="text/css" href="../css/nctouch_common.css">
        <link rel="stylesheet" type="text/css" href="../css/nctouch_cart.css">
        <link rel="stylesheet" type="text/css" href="../css/iconfont.css">
    </head>
    <style>
    	.nctouch-cart-item li .goods-info{width: 50%;margin-left: 0.454rem;margin-top: 0;height: 4.09rem;}
    	.nctouch-cart-item li .goods-price{position: absolute;top: 0;right: -2.4rem;}
    	.nctouch-cart-item li .goods-info dd.goods-type{position: absolute;bottom: 0;left: 0;}
    	.nctouch-cart-item li .nums{position: absolute;bottom: 0;right: -2.4rem;    color: #999;}
    </style>
    <body>
    <header id="header" class="fixed">
        <div class="header-wrap">
            <div class="header-l">
                <a href="javascript:history.go(-1)"> <i class="back"></i> </a>
            </div>
            <div class="header-title">
                <h1>购物车</h1>
            </div>
            <div class="JS-header-edit fz-26 col6">
                编辑
            </div>
            <div class="header-r">
                <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a>
            </div>
        </div>
        <div class="nctouch-nav-layout">
            <div class="nctouch-nav-menu">
                <span class="arrow"></span>
                <ul>
                    <li><a href="../index.html"><i class="home"></i>首页</a></li>
                    <li><a href="../tmpl/search.html"><i class="search"></i>搜索</a></li>
                    <li><a href="../tmpl/member/member.html"><i class="member"></i>我的商城</a></li>
                    <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout">
        <div id="cart-list-wp" class="mb50"></div>
    </div>
    <footer id="footer" class="bottom"></footer>
    <div class="pre-loading hide">
        <div class="pre-block">
            <div class="spinner"><i></i></div>
            购物车数据读取中...
        </div>
    </div>
    <script id="cart-list" type="text/html">
        <% if(cart_list.length >0){%>
        <% for (var i = 0;i
        <cart_list.length;i++){%>
        <div class="nctouch-cart-container bort1 borb1">
            <dl class="nctouch-cart-store bgf borb1">
                <dt><span class="store-check">
							<input class="store_checkbox" type="checkbox" checked>
						</span>
                    <i class="iconfont icon-stores align-middle"></i><strong class="iblock align-middle ml-10"><%=
                        cart_list[i].shop_name%></strong>
                    <% if (cart_list[i].shop_self_support == 'true') { %>
						<span>
                            自营店铺
						</span> <% } %>
                    <i class="iconfont icon-arrow-right align-middle ml-20 fz-28 col9"></i>

                    <span class="JS-edit fr">编辑</span>
                </dt>
            </dl>
            <ul class="nctouch-cart-item nctouch-cart-item-new">
                <% if (cart_list[i].goods) { %> <% for (var j=0; j < cart_list[i].goods.length; j++) {var goods =
                cart_list[i].goods[j];%>
                <li cart_id="<%=goods.cart_id%>" class="cart-litemw-cnt">
                    <div class="buy-li clearfix borb1">
                        <div class="goods-check "> 
                            <input type="checkbox" data-num="<%= goods.goods_num %>" checked name="cart_id"
                                   value="<%=goods.cart_id%>" <% if(goods.IsHaveBuy){ %> disabled="" title="您已达限购数量" <%
                            } %> />
                        </div>
                        <div class="goods-pic posr">
                            <% if(goods.goods_base.is_del == 2){ %>
                        	    <p class="old-Failed">该商品<br/>已失效</p>
                            <% } %>
                            <a href="<%=WapSiteUrl%>/tmpl/product_detail.html?goods_id=<%=goods.goods_id%>">
                                <img src="<%=goods.common_base.common_image%>"/> </a>
                        </div>
                        <dl class="goods-info fl">
                            <dt class="goods-name z-dhwz word-wrap w7">
                                <a href="<%=WapSiteUrl%>/tmpl/product_detail.html?goods_id=<%=goods.goods_id%>">
                                    <%=goods.common_base.common_name%> </a>
                            </dt>
                            <dd class="goods-type"><%=goods.goods_base.spec_val_str%></dd>
                            <span class="goods-price">￥<em><%=goods.now_price%></em></span>
                            <!--  <% if(goods.old_price > 0){ %>
                             <span class="old-price">￥<s><%=goods.old_price%></s></span>
                             <% } %> -->
                            <span class="fr nums">x<%=goods.goods_num%></span>
                            <span class="old-price">
                                <% if(goods.goods_base.is_del ==2){ %>
                                <p style="color: red">商品已被商家删除</p>
                                <% } %>
                            </span>
                            <span class="goods-sale">
                            <% if (!isEmpty(goods.groupbuy_info))
                                {%><em>团购</em><% }
                            else if (!isEmpty(goods.xianshi_info))
                                { %><em>限时折扣</em><% }
                            else if (!isEmpty(goods.sole_info))
                                { %><em><i></i>手机专享</em><% } %>
                            </span>
                        </dl>
                        <div class="edit-area">
                            <div class="goods-del" cart_id="<%=goods.cart_id%>">
                                删除
                            </div>
                            <div class="goods-subtotal">

                                <div class="value-box">
                                    <span class="minus">
                                        <a href="javascript:void(0);">&nbsp;</a>
                                    </span>
                                    <!-- s 获取并设置限购数量 2017.5.2 -->
                                    <span>
                                        <% if(goods.buy_limit > 0 && !goodsIsHaveBuy)
                                        {
                                            data_max = goods.buy_limit;
                                        }
                                        else
                                        {
                                            data_max = goods.goods_base.goods_stock;
                                        }
                                        if(goods.goods_base.lower_limit)
                                        {
                                            data_min = goods.goods_base.lower_limit;
                                            promotion = 1;
                                        }
                                        else
                                        {
                                            data_min = 1;
                                            promotion = 0;
                                        }
                                        %>
                                        <input type="number" min="1" class="buy-num buynum" promotion="<%=promotion%>"
                                               data_max="<%=data_max%>" data_min="<%=data_min%>"
                                               value="<%=goods.goods_num%>"/>
                                        <!-- e 获取并设置限购数量 2017.5.2 -->
                                    </span>
                                    <span class="add">
                                        <a href="javascript:void(0);">&nbsp;</a>
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </li>
                <% } %> <% } %>
            </ul>
            <dl class="nctouch-cart-store bgf">
                <% if (cart_list[i].free_freight) { %>
                <dd class="store-activity">
                    <em>免运费</em> <span><%=cart_list[i].free_freight%></span>
                </dd>
                <% } %> <% if (cart_list[i].mansong_info && !isEmpty(cart_list[i].mansong_info)) { %>
                <dd class="store-activity">
                    <em>满即送</em> <%var mansong = cart_list[i].mansong_info%> <span class="fz-28 col2"><%if(mansong.rule_discount){%>店铺优惠<%=mansong.rule_discount%>。<%}%> <%if(mansong.goods_name){%>赠品：<%=mansong.goods_name%><%}%></span>
                    <i class="arrow-down"></i>
                </dd>
                <% } %>
            </dl>


            <% if (cart_list[i].shop_voucher) { %>
            <div class="nctouch-bottom-mask down nctouch-bottom-mask<%=i%>">
                <div class="nctouch-bottom-mask-bg"></div>
                <div class="nctouch-bottom-mask-block">
                    <div class="nctouch-bottom-mask-tip"><i></i>点击此处返回</div>
                    <div class="nctouch-bottom-mask-top store-voucher">
                        <i class="iconfont icon-stores"></i> <%=cart_list[i].shop_name%>&nbsp;&nbsp;领取店铺代金券
                        <a href="javascript:void(0);" class="nctouch-bottom-mask-close"><i></i></a>
                    </div>
                    <div class="nctouch-bottom-mask-rolling nctouch-bottom-mask-rolling<%=i%>">
                        <div class="nctouch-bottom-mask-con">
                            <ul class="nctouch-voucher-list">
                                <% for (var j=0; j < cart_list[i].shop_voucher.length; j++) { var voucher =
                                cart_list[i].shop_voucher[j];%>
                                <li>
                                    <dl>
                                        <dt class="money">面额<em><%=voucher.voucher_t_price%></em>元</dt>
                                        <dd class="need">需消费<%=voucher.voucher_t_limit%>使用</dd>
                                        <dd class="time">至<%=voucher.voucher_t_end_date%>前使用</dd>
                                    </dl>
                                    <a href="javascript:void(0);" class="btn" data-tid=<%=voucher.voucher_t_id%>>领取</a>
                                </li>
                                <% } %>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <% } %>
        </div><%}%>
        <% if (check_out === true) {%>
        <div class="nctouch-cart-bottom bot-2">
            <div class="all-check fl pl-20">
                <input class="all_checkbox" type="checkbox" checked> <span class="selected-all fz-30 col2">全选</span>
            </div>
            <div class="total">
                <dl class="total-money">
                    <dt class="fz-32 col2">合计：</dt>
                    <dd>￥<em><%=sum%></em></dd>
                </dl>
            </div>
            <div id="batchRemove">删除</div>
            <div class="check-out ok">
                <a href="javascript:void(0)" id="productNumber">去付款(<%= number %>)</a>
            </div>
        </div><% } else { %>
        <div class="nctouch-cart-bottom no-login">
            <div class="cart-nologin-tip">为了您的购物有更好的体验请优先登录</div>
            <div class="cart-nologin-btn"><a href="../tmpl/member/login.html" class="btn">登录</a>
                <!-- <a href="../tmpl/member/register.html" class="btn">注册</a> -->
            </div>
        </div><% } %><%}else{%>
        <div class="nctouch-norecord cart">
            <div class="norecord-ico"><i></i></div>
            <dl>
                <dt class="colbc">购物车空空如也</dt>

            </dl>

        </div><%}%>
    </script>


    <script id="cart-list1" type="text/html">
        <% if(cart_list.length >0){%><% for (var i = 0;i
        <cart_list.length;i++){%>
        <div class="nctouch-cart-container bort1 borb1">
            <dl class="nctouch-cart-store bgf borb1">
                <dt>
                    <span class="store-check">
					    <input class="store_checkbox" type="checkbox" checked>
					</span>
                    <i class="iconfont icon-stores"></i> <%=cart_list[i].store_name%>
                    <span class="JS-edit fr">编辑</span>
                </dt>
            </dl>
            <ul class="nctouch-cart-item nctouch-cart-item-new">
                <% if (cart_list[i].goods) { %> <% for (var j=0; j
                <cart_list
                        [i].goods.length; j++) {var goods=cart_list[i].goods[j];%>
                    <li cart_id="<%=goods.cart_id%>" class="cart-litemw-cnt borb1">
                        <div class="buy-li">
                            <div class="goods-check">
                                <input type="checkbox" checked name="cart_id" data-num="<%= goods.goods_num %>"
                                       value="<%=goods.cart_id%>"/>
                            </div>
                            <div class="goods-pic">
                                <a href="<%=WapSiteUrl%>/tmpl/product_detail.html?goods_id=<%=goods.goods_id%>">
                                    <img src="<%=goods.goods_image_url%>"/> </a>
                            </div>
                            <dl class="goods-info fr">
                                <dt class="goods-name">
                                    <a href="<%=WapSiteUrl%>/tmpl/product_detail.html?goods_id=<%=goods.goods_id%>">
                                        <%=goods.goods_name%> </a>
                                </dt>
                                <dd class="goods-type"><%=goods.goods_spec%></dd>
                            </dl>
                            <div class="edit-area">
                                <div class="goods-del" cart_id="<%=goods.cart_id%>">
                                    删除
                                </div>
                                <div class="goods-subtotal"><span
                                            class="goods-price">￥<em><%=goods.goods_price%></em></span>
                                    <div class="value-box">
                                        <span class="minus">
                                            <a href="javascript:void(0);">&nbsp;</a>
                                        </span>
                                        <span>
                                            <input type="number" min="1" class="buy-num buynum"
                                                   value="<%=goods.goods_num%>"/>
                                        </span>
                                        <span class="add">
                                            <a href="javascript:void(0);">&nbsp;</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <% } %> <% } %>
            </ul>
        </div><%}%><% if (check_out === true) {%>
        <div class="nctouch-cart-bottom">
            <div class="all-check fl pl-20">
                <input class="all_checkbox" type="checkbox" checked> <span class="selected-all fz-30 col2">全选</span>
            </div>
            <div class="total">
                <dl class="total-money">
                    <dt class="fz-32 col2">合计：</dt>
                    <dd>￥<em><%=sum%></em></dd>
                </dl>
            </div>
            <div class="check-out ok">
                <a href="javascript:void(0)">去付款(<%=number%>)</a>
            </div>
        </div><% } else { %>
        <div class="nctouch-cart-bottom no-login">
            <div class="cart-nologin-tip clearfix"><span class="fl">为了您的购物有更好的体验请优先登录</span><a
                        href="../tmpl/member/login.html" class="fz-26 fr col2"><b class="align-middle">去登录</b><i
                            class="iconfont icon-arrow-right align-middle colbc ml-10"></i></a></div>
        </div><% } %><%}else{%>
        <div class="nctouch-norecord cart">
            <div class="norecord-ico"><i></i></div>
            <dl>
                <dt class="colbc">购物车空空如也</dt>

            </dl>

        </div><%}%>
    </script>

    <!-- 底部 -->
    <?php
    include __DIR__ . '/../includes/footer_menu.php';
    ?>

    <script type="text/javascript" src="../js/zepto.js"></script>
    <script type="text/javascript" src="../js/template.js"></script>
    <script type="text/javascript" src="../js/common.js"></script>
    <script type="text/javascript" src="../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../js/cart-list.js"></script>

    </body>
    </html>
<?php
include __DIR__ . '/../includes/footer.php';
?>
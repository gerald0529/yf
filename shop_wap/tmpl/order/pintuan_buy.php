<?php
include __DIR__ . '/../../includes/header.php';
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
    <title>确认订单</title>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_common.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_cart.css">
    <style>
        .jia-shop .fr a.min {
            background: #d5d5d5;
        }

        .jia-shop .fr a.min.disabled, .jia-shop .fr a.max.disabled {
            background: #eeeeee;
        }
    </style>
</head>
<body>
<header id="header" class="fixed">
    <div class="header-wrap">
        <div class="header-l"><a href="javascript:history.go(-1)"> <i class="back"></i> </a></div>
        <div class="header-title">
            <h1>确认订单</h1>
        </div>
        <div class="header-r"><a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a>
        </div>
    </div>
    <div class="nctouch-nav-layout">
        <div class="nctouch-nav-menu"><span class="arrow"></span>
            <ul>
                <li><a href="../../index.html"><i class="home"></i>首页</a></li>
                <li><a href="../../tmpl/search.html"><i class="search"></i>搜索</a></li>
                <li><a href="../../tmpl/member/member.html"><i class="member"></i>我的商城</a></li>
                <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
            </ul>
        </div>
    </div>
</header>
<div id="container-fcode" class="hide">
    <div class="fcode-bg">
        <div class="con">
            <h3>您正在购买“F码”商品</h3>
            <h5>请输入所知的F码序列号并提交验证<br /> 系统效验后可继续完成下单</h5>
            <input type="text" name="fcode" id="fcode" placeholder="" />
            <p class="fcode_error_tip" style="display:none;color:red;"></p>
            <a href="javascript:void(0);" class="submit">提交验证</a></div>
    </div>
</div>
<div class="nctouch-main-layout mb20">
    <div class="nctouch-cart-block">
        <!--正在使用的默认地址Begin-->
        <div class="nctouch-cart-add-default borb1">
            <a href="javascript:void(0);" id="list-address-valve"><i class="icon-add"></i>
                <dl>
                    <input type="hidden" class="inp" name="address_id" id="address_id" />
                    <dt>收货人：<span id="true_name"></span><span id="mob_phone"></span></dt>
                    <dd><span id="address"></span></dd>
                </dl>
                <i class="icon-arrow"></i></a></div>
        <!--正在使用的默认地址End-->
    </div>
    <!--选择收货地址Begin-->
    <div id="list-address-wrapper" class="nctouch-full-mask hide">
        <div class="nctouch-full-mask-bg"></div>
        <div class="nctouch-full-mask-block">
            <div class="header">
                <div class="header-wrap">
                    <div class="header-l"><a href="javascript:void(0);"> <i class="back"></i> </a></div>
                    <div class="header-title">
                        <h1>收货地址管理</h1>
                    </div>
                </div>
            </div>
            <div class="nctouch-main-layout" style="display: block; position: absolute; top: 0; right: 0; left: 0; bottom:2rem; overflow: hidden; z-index: 1;" id="list-address-scroll">
                <ul class="nctouch-cart-add-list" id="list-address-add-list-ul"></ul>
            </div>
            <div id="addresslist" class="mt10" style="position: absolute; right: 0; left: 0; bottom: 0; z-index: 1;">
                <a href="javascript:void(0);" class="btn-l" id="new-address-valve">新增收货地址</a></div>
        </div>
    </div>
    <!--选择收货地址End-->
    <!--新增收货地址Begin-->
    <div id="new-address-wrapper" class="nctouch-full-mask hide">
        <div class="nctouch-full-mask-bg"></div>
        <div class="nctouch-full-mask-block">
            <div class="header">
                <div class="header-wrap">
                    <div class="header-l"><a href="javascript:void(0);"> <i class="back"></i> </a></div>
                    <div class="header-title">
                        <h1>新增收货地址</h1>
                    </div>
                </div>
            </div>
            <div class="nctouch-main-layout" id="new-address-scroll">
                <div class="nctouch-inp-con">
                    <form id="add_address_form">
                        <ul class="form-box">
                            <li class="form-item">
                                <h4>收货人姓名</h4>
                                <div class="input-box">
                                    <input type="text" class="inp" name="true_name" id="vtrue_name" autocomplete="off" oninput="writeClear($(this));" />
                                    <span class="input-del"></span></div>
                            </li>
                            <li class="form-item">
                                <h4>联系手机</h4>
                                <div class="input-box">
                                    <input type="tel" class="inp" name="mob_phone" id="vmob_phone" autocomplete="off" oninput="writeClear($(this));" />
                                    <span class="input-del"></span></div>
                            </li>
                            <li class="form-item">
                                <h4>地区选择</h4>
                                <div class="input-box">
                                    <input name="area_info" type="text" class="inp" id="varea_info" unselectable="on" onfocus="this.blur()" autocomplete="off" onchange="btn_check($('form'));" readonly />
                                </div>
                            </li>
                            <li class="form-item">
                                <h4>详细地址</h4>
                                <div class="input-box">
                                    <input type="text" class="inp" name="vaddress" id="vaddress" autocomplete="off" oninput="writeClear($(this));" />
                                    <span class="input-del"></span></div>
                            </li>
                        </ul>
                        <div class="error-tips"></div>
                        <div class="form-btn"><a href="javascript:void(0);" class="btn">保存地址</a></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--新增收货地址End-->

    <!--商品列表Begin-->
    <div id="goodslist_before" class="mt5">
        <div id="deposit"></div>
    </div>
    <!--商品列表End-->

    <!--合计支付金额Begin-->
    <div id="rptVessel" class="nctouch-cart-block mt5">
        <div class="current-con">
            <dl class="total-money">
                合计：<span class="col4 fz8">￥<em id="totalPrice">0.00</em></span>
            </dl>
        </div>
    </div>

    <!--底部总金额固定层Begin-->
    <div class="nctouch-cart-bottom">
        <div class="total"><span id="online-total-wrapper"></span>
            <dl class="total-money">
            </dl>
        </div>
        <div class="check-out"><a href="javascript:void(0);" id="ToBuyStep2">提交订单</a></div>
    </div>
    <!--底部总金额固定层End-->
</div>
<script type="text/html" id="goods_list">
    <div class="nctouch-cart-container">
        <dl class="nctouch-cart-store">
            <dt><i class="icon-store"></i><%= shop.shop_name %></dt>
        </dl>
        <ul class="nctouch-cart-item">
            <li class="buy-item bgf6">
                <div class="buy-li">
                    <div class="goods-pic">
                        <a href="<%=WapSiteUrl%>/tmpl/pintuan_detail.html?goods_id=<%=goods.goods_id%>">
                            <img src="<%= goods.goods_image %>"/>
                        </a>
                    </div>
                    <dl class="goods-info">
                        <dt class="goods-name">
                            <a href="<%=WapSiteUrl%>/tmpl/pintuan_detail.html?goods_id=<%=goods.goods_id%>">
                                <em style="color:red">[<%= pintuan.person_num %>人团]</em><%= pintuan.name %>
                            </a>
                        </dt>
                        <dd class="goods-type"><%= goods.goods_name %></dd>
                    </dl>
                    <div class="goods-subtotal">
                        <span class="goods-price">￥<em><%= currentGoodsPrice %></em></span>
                    </div>
                    <div class="goods-num">
                        <em>x1</em>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</script>
<script type="text/html" id="list-address-add-list-script">
    <% var address_list = address; %><% if(address[0].user_address_id != 0){ %><% for (var i=0; i
    <address_list.length; i++) { %>
    <li <% if ( (!isEmpty(address_id) && address_list[i].user_address_id == address_id) || (isEmpty(address_id) && address_list[i].user_address_default == 1) ) { %>class="selected"<% } %> data-param="{user_address_id:'<%=address_list[i].user_address_id%>',user_address_contact:'<%=address_list[i].user_address_contact%>',user_address_phone:'<%=address_list[i].user_address_phone%>',user_address_area:'<%=address_list[i].user_address_area%>',user_address_area:'<%=address_list[i].user_address_area%>',user_address_area_id:'<%=address_list[i].user_address_area_id%>',user_address_city_id:'<%=address_list[i].user_address_city_id%>',user_address_address:'<%=address_list[i].user_address_address%>'}">
    <i></i>
    <dl>
        <dt>收货人：<span id=""><%=address_list[i].user_address_contact%></span><span id=""><%=address_list[i].user_address_phone%></span><% if (address_list[i].user_address_default == 1) { %><sub>默认</sub><% } %>
        </dt>
        <dd>
            <span id=""><%=address_list[i].user_address_area %>&nbsp;<%=address_list[i].user_address_address %></span>
        </dd>
    </dl></li><% }} %>
</script>

<script type="text/javascript" src="../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../js/template.js"></script>
<script type="text/javascript" src="../../js/common.js"></script>
<script type="text/javascript" src="../../js/iscroll.js"></script>
<script type="text/javascript" src="../../js/simple-plugin.js"></script>
<script type="text/javascript" src="../..//js/fly/requestAnimationFrame.js"></script>
<script type="text/javascript" src="../../js/fly/zepto.fly.min.js"></script>
<script type="text/javascript" src="../../js/tmpl/order_payment_common.js"></script>
<script type="text/javascript" src="../../js/tmpl/pintuan_buy.js"></script>
</body>
</html>
<?php
include __DIR__ . '/../../includes/footer.php';
?>
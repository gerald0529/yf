<?php
    include __DIR__ . '/../../includes/header.php';
?>
    <!DOCTYPE html>
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
        <title>虚拟订单</title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_common.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_cart.css">
        <link rel="stylesheet" href="../../css/iconfont.css">
    </head>
    <body>
    <header id="header" class="fixed">
        <div class="header-wrap">
            <div class="header-l"><a href="member.html"> <i class="back"></i> </a></div>
            <span class="header-tab"><a href="order_list.html">实物订单</a><a href="javascript:void(0);" class="cur">虚拟订单</a></span>
            <div class="header-r"><a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a></div>
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
    <div class="nctouch-main-layout">
        <!--订单搜索-->
        <div class="nctouch-order-search">
            <form>
                <span class="ser-area">
                    <i class="icon-ser"></i>
                    <input type="text" autocomplete="on" maxlength="50" placeholder="输入商品标题或订单号进行搜索" name="order_key" id="order_key" oninput="writeClear($(this));">
                    <span class="input-del"></span>
                </span>
                <input type="button" id="search_btn" value="搜索">
            </form>
        </div>
        <!--导航标签-->
        <div id="fixed_nav" class="nctouch-single-nav borb1">
            <ul id="filtrate_ul" class="w33h">
                <li class="selected"><a href="javascript:void(0);" data-state="">全部</a></li>
                <li><a href="javascript:void(0);" data-state="wait_pay">待付款</a></li>
                <li><a href="javascript:void(0);" data-state="wait_confirm_goods">待使用</a></li>
                <li><a href="javascript:void(0);" data-state="finish">待评价</a></li>
            </ul>
        </div>
        <!--订单列表 list-->
        <div class="nctouch-order-list" id="order-list">
            <ul id="order-list" class="mt-20"></ul>
        </div>
        <!--底部总金额固定层End-->
        <div class="nctouch-bottom-mask down">
            <div class="nctouch-bottom-mask-bg"></div>
            <div class="nctouch-bottom-mask-block">
                <div class="nctouch-bottom-mask-tip"><i></i>点击此处返回</div>
                <div class="nctouch-bottom-mask-top">
                    <p class="nctouch-cart-num">本次交易需在线支付<em id="onlineTotal">0.00</em>元</p>
                    <p style="display:none" id="isPayed"></p>
                    <a href="javascript:void(0);" class="nctouch-bottom-mask-close"><i></i></a>
                </div>
                <div class="nctouch-inp-con nctouch-inp-cart">
                    <ul class="form-box" id="internalPay">
                        <p class="rpt_error_tip" style="display:none;color:red;"></p>
                        <li class="form-item" id="wrapperUseRCBpay">
                            <div class="input-box pl5">
                                <label>
                                    <input type="checkbox" class="checkbox" id="useRCBpay" autocomplete="off" />
                                    使用充值卡支付
                                    <span class="power"><i></i></span>
                                </label>
                                <p>可用余额 ￥<em id="availableRcBalance"></em></p>
                            </div>
                        </li>
                        <li class="form-item" id="wrapperUsePDpy">
                            <div class="input-box pl5">
                                <label>
                                    <input type="checkbox" class="checkbox" id="usePDpy" autocomplete="off" />
                                    使用预存款支付
                                    <span class="power"><i></i></span>
                                </label>
                                <p>可用余额 ￥<em id="availablePredeposit"></em></p>
                            </div>
                        </li>
                        <li class="form-item" id="wrapperPaymentPassword" style="display:none">
                            <div class="input-box"><span class="txt">输入支付密码</span>
                                <input type="password" class="inp" id="paymentPassword" autocomplete="off" />
                                <span class="input-del"></span>
                            </div>
                            <a href="../member/member_paypwd_step1.html" class="input-box-help" style="display:none"><i>i</i>尚未设置</a>
                        </li>
                    </ul>
                    <div class="nctouch-pay">
                        <div class="spacing-div"><span>在线支付方式</span></div>
                        <div class="pay-sel">
                            <label style="display:none">
                                <input type="radio" name="payment_code" class="checkbox" id="alipay" autocomplete="off" />
                                <span class="alipay">支付宝</span>
                            </label>
                            <label style="display:none">
                                <input type="radio" name="payment_code" class="checkbox" id="wxpay_jsapi" autocomplete="off" />
                                <span class="wxpay">微信</span>
                            </label>
                        </div>
                    </div>
                    <div class="pay-btn"><a href="javascript:void(0);" id="toPay" class="btn-l">确认支付</a></div>
                </div>
            </div>
        </div>
    </div>
    <!--返回顶部按钮-->
    <div class="fix-block-r">
        <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>
    </div>
    <!-- 底部bottom-->
    <footer id="footer" class="bottom"></footer>
    
    <script type="text/html" id="order-list-tmpl">
        <div class="order-list">
            <% var order_list = items %>
            <% if (order_list && order_list.length > 0) { %>
            <ul class="mt-20">
                <% for (var i = 0; i < order_list.length; i++) {
                        var order = order_list[i];
                        order_goods = order.goods_list[0];
                %>
                <li class="<% if (order.order_status == 1 ) { %>gray-order-skin<% } else { %>green-order-skin<% } %>">
                    <div class="nctouch-order-item">
                        <div class="nctouch-order-item-head">
                            <% if (order_goods.shop_self_support){ %>
                            <a class="store">
                                <i class="iconfont icon-stores mr-10 iblock align-middle fz-30"></i>
                                <strong class="iblock align-middle"><%=order.shop_name%></strong>
                                <i class="iconfont icon-arrow-right ml-10 iblock align-middle fz-26 col9"></i>
                            </a>
                            <% } else { %>
                            <a href="<%=WapSiteUrl%>/tmpl/store.html?shop_id=<%=order.shop_id%>" class="store one-overflow">
                                <i class="iconfont icon-stores mr-10 iblock align-middle fz-30"></i>
                                <strong class="iblock align-middle"><%= order.shop_name %></strong>
                                <i class="iconfont icon-arrow-right ml-10 iblock align-middle fz-26 col9"></i>
                            </a>
                            <% } %>
                            <span class="state">
                                <span class="<% if (order.order_status == 1) { %>ot-cancel<% } else { %>ot-nofinish<% } %>">
                                    <%= order.order_state_con %>
                                </span>
					        </span>
                        </div>
                        <div class="nctouch-order-item-con">
                            <div class="goods-block">
                                <a href="<%=WapSiteUrl%>/tmpl/member/vr_order_detail.html?order_id=<%=order.order_id%>" class="wp100">
                                    <div class="goods-pic">
                                        <img src="<%=order_goods.goods_image%>" />
                                    </div>
                                    <dl class="goods-info">
                                        <dt class="goods-name"><%=order_goods.goods_name%></dt>
                                        <dd class="goods-type"></dd>
                                    </dl>
                                    <div class="goods-subtotal">
                                        <span class="goods-price">￥<em><%=order_goods.goods_price%></em></span>
                                        <span class="old-goods-price">￥<em><%=order_goods.old_price%></em></span>
                                        <span class="goods-num colbc fz-24">x<%=order_goods.order_goods_num%></span>
                                    </div>
                                </a>
                                <!--退款状态-->
                                <div class="return-tips tr">
                                    <div class="fz-24">
                                        <% if(order_goods.goods_return_status > 0) {%>
                                            <a href="<%=WapSiteUrl%>/tmpl/member/member_refund_info.html?refund_id=<%=order_goods.order_return_id%>" class=''>
                                                <span class="default-color">
                                                    <%=order_goods.goods_return_status_con%>
                                                </span>
                                            </a>
                                        <% } %>
                                        <% if(order_goods.goods_refund_status > 0) {%>
                                            <a href="<%=WapSiteUrl%>/tmpl/member/member_return_info.html?refund_id=<%=order_goods.order_refund_id%>" class=''>
                                                <span class="default-color">
                                                    <%=order_goods.goods_refund_status_con%>
                                                </span>
                                            </a>
                                        <% } %>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="nctouch-order-item-footer">
                            <div class="store-totle">
                                <span>共<em><%=order.order_nums%></em>件商品，合计</span>
                                <span class="sum">￥<em><%= p2f(order.order_payment_amount) %></em></span>
                                <span class="freight">(含运费￥<%=order.order_shipping_fee%>)</span>
                            </div>
                            <!--订单状态：-->
                            <% if (order.order_status == 1 || order.order_status == 6 || order.order_status == 7) { %>
                            <div class="handle">
                                <!--取消订单-->
                                <% if (order.order_status == 1) { %>
                                    <a href="javascript:void(0)" order_id="<%=order.order_id%>" class="btn cancel-order">取消订单</a>
                                <% } %>
                                <!--评价订单-->
                                <% if (order.order_status == 6 && order.order_buyer_evaluation_status == 0) { %>
                                    <a href="javascript:void(0)" order_id="<%=order.order_id%>" class="btn evaluation-order">评价订单</a>
                                <% } %>
                                <!--查看评价-->
                                <% if (order.order_status == 6 && order.order_buyer_evaluation_status == 1) { %>
                                    <a href="javascript:void(0)" order_id="<%=order.order_id%>" class="btn evaluation-order">查看评价</a>
                                <% } %>
                                <!--订单支付-->
                                <% if (order.order_status == 1) { %>
                                    <a href="javascript:" onclick="payOrder('<%= order.payment_number %>','<%= order.order_id %>')" data-paySn="<%= order.order_id %>" class="btn key check-payment">订单支付</a>
                                <% } %>
                                <!--删除订单-->
                                <%if(order.order_status == 7 || order.order_status == 6){%>
                                    <a href="javascript:void(0)" order_id="<%=order.order_id%>" class="del delete-order btn">删除</a>
                                <%}%>
                            </div>
                            <% } %>
                        </div>
                    </div>
                </li>
                <% } %>
                <% if (hasmore) {%>
                    <li class="loading">
                        <div class="spinner"><i></i></div>
                        订单数据读取中...
                    </li>
                <% } %>
            </ul>
            <% } else { %>
            <div class="nctouch-norecord order">
                <div class="norecord-ico"><i></i></div>
                <dl>
                    <dt>您还没有相关的订单</dt>
                    <dd>可以去看看哪些想要买的</dd>
                </dl>
                <a href="<%=WapSiteUrl%>" class="btn">随便逛逛</a>
            </div>
            <% } %>
        </div>
    </script>
    <script type="text/javascript" src="../../js/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/template.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/zepto.waypoints.js"></script>
    <script type="text/javascript" src="../../js/tmpl/order_payment_common.js"></script>
    <script type="text/javascript" src="../../js/tmpl/vr_order_list.js"></script>
    </body>
    </html>
<?php
    include __DIR__ . '/../../includes/footer.php';
?>

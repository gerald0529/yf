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
        <title>实物订单</title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_common.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_cart.css">
        <link rel="stylesheet" href="../../css/iconfont.css">
    </head>
    
    <body>
    <header id="header" class="fixed">
        <div class="header-wrap">
            <div class="header-l"><a href="member.html"><i class="back"></i></a></div>
            <span class="header-tab"><a href="javascript:void(0);" class="cur">实物订单</a><a href="vr_order_list.html">虚拟订单</a></span>
            <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>
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
    <div class="nctouch-main-layout">
        <div class="nctouch-order-search">
            <form>
                <span class="ser-area "><i class="icon-ser"></i><input type="text" autocomplete="on" maxlength="50" placeholder="输入商品标题或订单号进行搜索" name="order_key" id="order_key" oninput="writeClear($(this));" >
      <span class="input-del"></span></span>
                <input type="button" id="search_btn" value="搜索">
            </form>
        </div>
        <div id="fixed_nav" class="nctouch-single-nav borb1">
            <ul id="filtrate_ul" class="w20h">
                <li class="selected"><a href="javascript:void(0);" data-state="">全部</a></li>
                <li><a href="javascript:void(0);" data-state="wait_pay">待付款</a></li>
                <li><a href="javascript:void(0);" data-state="order_payed">待发货</a></li>
                <li><a href="javascript:void(0);" data-state="wait_confirm_goods">待收货</a></li>
                <li><a href="javascript:void(0);" data-state="finish">待评价</a></li>
            </ul>
        </div>
        <div class="nctouch-order-list">
            <ul id="order-list" class="mt-20">
            </ul>
        </div>
        <!--底部总金额固定层End-->
        <div class="nctouch-bottom-mask down">
            <div class="nctouch-bottom-mask-bg"></div>
            <div class="nctouch-bottom-mask-block">
                <div class="nctouch-bottom-mask-tip"><i></i>点击此处返回</div>
                <div class="nctouch-bottom-mask-top">
                    <p class="nctouch-cart-num">本次交易需在线支付<em id="onlineTotal">0.00</em>元</p>
                    <p style="display:none" id="isPayed"></p>
                    <a href="javascript:void(0);" class="nctouch-bottom-mask-close"><i></i></a> </div>
                <div class="nctouch-inp-con nctouch-inp-cart">
                    <ul class="form-box" id="internalPay">
                        <p class="rpt_error_tip" style="display:none;color:red;"></p>
                        <li class="form-item" id="wrapperUseRCBpay">
                            <div class="input-box pl5">
                                <label>
                                    <input type="checkbox" class="checkbox" id="useRCBpay" autocomplete="off" /> 使用充值卡支付 <span class="power"><i></i></span> </label>
                                <p>可用充值卡余额 ￥<em id="availableRcBalance"></em></p>
                            </div>
                        </li>
                        <li class="form-item" id="wrapperUsePDpy">
                            <div class="input-box pl5">
                                <label>
                                    <input type="checkbox" class="checkbox" id="usePDpy" autocomplete="off" /> 使用预存款支付 <span class="power"><i></i></span> </label>
                                <p>可用预存款余额 ￥<em id="availablePredeposit"></em></p>
                            </div>
                        </li>
                        <li class="form-item" id="wrapperPaymentPassword" style="display:none">
                            <div class="input-box"> <span class="txt">输入支付密码</span>
                                <input type="password" class="inp" id="paymentPassword" autocomplete="off" />
                                <span class="input-del"></span></div>
                            <a href="../member/member_paypwd_step1.html" class="input-box-help" style="display:none"><i>i</i>尚未设置</a> </li>
                    </ul>
                    <div class="nctouch-pay">
                        <div class="spacing-div"><span>在线支付方式</span></div>
                        <div class="pay-sel">
                            <label style="display:none">
                                <input type="radio" name="payment_code" class="checkbox" id="alipay" autocomplete="off" />
                                <span class="alipay">支付宝</span></label>
                            <label style="display:none">
                                <input type="radio" name="payment_code" class="checkbox" id="wxpay_jsapi" autocomplete="off" />
                                <span class="wxpay">微信</span></label>
                        </div>
                    </div>
                    <div class="pay-btn"> <a href="javascript:void(0);" id="toPay" class="btn-l">确认支付</a> </div>
                </div>
            </div>
        </div>
    </div>
    <div class="fix-block-r">
        <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>
    </div>
    <footer id="footer" class="bottom"></footer>
    <script type="text/html" id="order-list-tmpl">
        <% var orderlist = data.items; %>
        <% if (orderlist.length > 0){%>
        <% for(var i = 0;i<orderlist.length;i++){
        var orderinfo = orderlist[i];
        var goodslist = orderinfo.goods_list;
        var pintuan_person_num = orderinfo.pintuan_person_num;
        var pintuan_type = orderinfo.pintuan_type;
        var pintuan_temp_order = orderinfo.pintuan_temp_order;
        %>
        <li class="<%if(orderinfo.order_payment_amount){%>green-order-skin<%}else{%>gray-order-skin<%}%> <%if(i>0){%>mt-20<%}%>">
            
            <div class="nctouch-order-item">
                <div class="nctouch-order-item-head">
                    <%if (orderinfo.shop_self_support){%>
                    <a class="store one-overflow"><i class="iconfont icon-stores mr-10 iblock align-middle"></i>自营店铺</a>
                    <%}else{%>
                    <a href="<%=WapSiteUrl%>/tmpl/store.html?shop_id=<%=orderinfo.shop_id%>" class="store"><i class="iconfont icon-stores mr-10 iblock align-middle fz-30"></i><strong class="iblock align-middle one-overflow wp50"><%= orderinfo.shop_name %></strong><i class="iconfont icon-arrow-right ml-10 iblock align-middle fz-26 col9"></i> </a>
                    <%}%>
                    <span class="state">
                                                    <%
                                                    if(pintuan_temp_order == 1){
                                                        if(pintuan_type == 1)
                                                        {
                                                    %>
                                                        1人团
                                                    <%
                                                        }
                                                        else
                                                        {
                                                            if(pintuan_person_num){
                                                    %>
                                                        <%=pintuan_person_num%>人团
                                                    <% } } }%>

                        							<%
                        								var stateClass ="ot-finish";
                        								var orderstate = orderinfo.order_status;
                        								if(orderstate == 2 || orderstate == 3 || orderstate == 4 || orderstate == 5){
                        									stateClass = stateClass;
                        								}else if(orderstate == 7) {
                        									stateClass = "ot-cancel";
                        								}else {
                        									stateClass = "ot-nofinish";
                        								}
                        							%>
                        							<span class="<%=stateClass%>"><%=orderinfo.order_state_con%></span>
                                                </span>
                </div>
                <div class="nctouch-order-item-con nctouch-order-item-con-cart">
                    <%
                    if(goodslist){
                    for(var j = 0;j<goodslist.length;j++){
                    var order_goods = goodslist[j];
                    %>
                    <div class="goods-block">
                        <a href="<%=WapSiteUrl%>/tmpl/member/order_detail.html?order_id=<%=orderinfo.order_id%>" class="clearfix wp100">
                            <div class="">
                                <div class="goods-pic">
                                    <% if(order_goods.is_del == 2){ %>
                                	<p class="old-Failed">此商品<br/>已失效</p>
                                    <% } %>
                                    <img src="<%=order_goods.goods_image%>" />
                                    <!-- 1113拼团图标 -->
                                    <!-- <b class="icon-tg"></b> -->
                                </div>
                                <dl class="goods-info">
                                    <dt class="goods-name"><%=order_goods.goods_name%></dt>
                                    <dd class="goods-type one-overflow">
                                        <%=order_goods.title_order_spec_info%>
                                    </dd>
                                </dl>
                            </div>
                            
                            <div class="goods-subtotal">
                                <%
                                if(pintuan_temp_order == 1){
                                %>
                                <span class="goods-price">￥<em><%=order_goods.order_goods_payment_amount%></em></span>
                                <span class="old-goods-price">￥<em><%=order_goods.old_price%></em></span>
                                <%}else{%>
                                <span class="goods-price">￥<em><%=order_goods.goods_price%></em></span>
                                <%}%>
                                <span class="goods-num fz-24 colbc">x<%=order_goods.order_goods_num%></span>
                            
                            
                            </div>
                        </a>
                        <div class="return-tips tr">
                            <!-- 退款状态 -->
                            <div class="fz-24">
                                <% if(order_goods.goods_return_status > 0) {%>
                                <a href="<%=WapSiteUrl%>/tmpl/member/member_refund_info.html?refund_id=<%=order_goods.order_return_id%>" class=''><span class="default-color"><%=order_goods.goods_return_status_con%></span></a>
                                <% } %>
                                <% if(order_goods.goods_refund_status > 0) {%>
                                <a href="<%=WapSiteUrl%>/tmpl/member/member_return_info.html?refund_id=<%=order_goods.order_refund_id%>" class=''><span class="default-color"><%=order_goods.goods_refund_status_con%></span></a>
                                <% } %>
                            </div>
                        </div>
                    </div>
                    <%}%>
                </div>
                <div class="nctouch-order-item-footer">
                    <div class="store-totle">
                        <span>共<em><%=orderinfo.order_nums%></em>件商品，合计</span><span class="sum">￥<em><%= p2f(orderinfo.order_payment_amount) %></em></span><span class="freight">(含运费￥<%=orderinfo.order_shipping_fee%>)</span>
                    
                    </div>
                    <%if(orderinfo.order_status == 1 || orderinfo.order_status == 3 || orderinfo.order_status == 4 || orderinfo.order_status == 6 || orderinfo.order_status == 7 ){%>
                    <div class="handle">
                        <%if(orderinfo.order_status == 7 || orderinfo.order_status == 6){%>
                        <a href="javascript:void(0)" order_id="<%=orderinfo.order_id%>" class="del delete-order btn">删除</a>
                        <%}%>
                        <%if(orderinfo.order_status == 1 || (orderinfo.order_status == 3 && orderinfo.payment_id == 2)){%>
                        <a href="javascript:void(0)" order_id="<%=orderinfo.order_id%>" class="btn cancel-order">取消订单</a>
                        <%}%>
                        <%if(orderinfo.order_status == 4){%>
                        <a href="javascript:void(0)" order_id="<%=orderinfo.order_id%>" express_id="<%=orderinfo.order_shipping_express_id%>" express_name="<%=orderinfo.express_name%>" shipping_code="<%=orderinfo.order_shipping_code%>" class="btn viewdelivery-order">查看物流</a>
                        <%}%>
                        <%if(orderinfo.order_status == 4){%>
                        <a href="javascript:void(0)" order_id="<%=orderinfo.order_id%>" class="btn key sure-order">确认收货</a>
                        <%}%>
                        <%if(orderinfo.order_status == 6){%>
                        <% if(order_goods.goods_return_status <= 0) {%>
                        <%if(orderinfo.evala_status == 1){%>
                        <a href="javascript:void(0)" order_id="<%=orderinfo.order_id%>" class="btn key evaluation-order">评价</a>
                        <%}else if(orderinfo.evala_status == 2){%>
                        <a href="javascript:void(0)" order_id="<%=orderinfo.order_id%>" class="btn evaluation-again-order">追加评价</a>
                        <%}else if(orderinfo.evala_status == 3){%>
                        <a href="javascript:void(0)" order_id="<%=orderinfo.order_id%>" class="btn evaluation-again-order">查看评价</a>
                        <%}%>
                        <%}%>
                        <%}%>
                        <%if(orderinfo.order_status == 1 && orderinfo.order_payment_amount>0){%>
                        <a href="javascript:;" onclick="payOrder('<%= orderinfo.payment_number %>','<%= orderinfo.order_id %>')" data-paySn="<%= orderinfo.order_id %>" class="btn key check-payment">订单支付</a>
                        <%}%>
                    </div>
                    <%}%>
                </div>
            </div>
        
        
        </li>
        <%}}%>
        <% if (hasmore) {%>
        <li class="loading">
            <div class="spinner"><i></i></div>订单数据读取中...</li>
        <% } %>
        <%}else {%>
        <div class="nctouch-norecord order">
            <div class="norecord-ico"><i></i></div>
            <dl>
                <dt>您还没有相关的订单</dt>
                <dd>可以去看看哪些想要买的</dd>
            </dl>
            <a href="<%=WapSiteUrl%>" class="btn">随便逛逛</a>
        </div>
        <%}%>
    </script>
    <script type="text/javascript" src="../../js/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/template.js"></script>
    
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/zepto.waypoints.js"></script>
    <script type="text/javascript" src="../../js/tmpl/order_payment_common.js"></script>
    <script type="text/javascript" src="../../js/tmpl/order_list.js"></script>
    </body>
    
    </html>
<?php
    include __DIR__.'/../../includes/footer.php';
?>

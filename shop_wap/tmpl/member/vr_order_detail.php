<?php
    include __DIR__ . '/../../includes/header.php';
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-touch-fullscreen" content="yes"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="msapplication-tap-highlight" content="no"/>
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1"/>
    <title>订单详情</title>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" href="../../css/iconfont.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_common.css">
</head>
<body>
<header id="header">
    <div class="header-wrap">
        <div class="header-l"><a href="javascript:history.go(-1)"> <i class="back"></i> </a></div>
        <div class="header-title">
            <h1>订单详情</h1>
        </div>
        <div class="header-r">
            <a id="header-nav" href="javascript:void(0);"> <i class="more"></i> <sup></sup> </a>
        </div>
    </div>
    <div class="nctouch-nav-layout">
        <div class="nctouch-nav-menu">
            <span class="arrow"></span>
            <ul>
                <li><a href="../../index.html"><i class="home"></i>首页</a></li>
                <li><a href="../search.html"><i class="search"></i>搜索</a></li>
                <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
            </ul>
        </div>
    </div>
</header>

<div class="nctouch-main-layout mb20" id="order-info-container">
    <div class="nctouch-order-list" id="order-info-container">
        <ul></ul>
    </div>
</div>
<footer id="footer"></footer>

<script type="text/html" id="order-info-tmpl">
    <div class="nctouch-oredr-detail-block order-status-bg pt-0 pb-0">
        <h3 class="lg2">交易状态</h3>
        <div class="order-state lg2"><%=order_state_con%></div>
        <%if (order_cancel_reason != ''){%>
            <div class="info colf"><%=order_cancel_reason%></div>
        <%}%>
        <%if(order_status == 1){%>
        <div class="time fnTimeCountDown colf fz-28" data-end="<%= cancel_time %>">
            <i class="icon-time"></i>
            <span class="ts">
                剩余
                <span class="day">00</span>
                <strong>天</strong>
                <span class="hour">00</span>
                <strong>小时</strong>
                <span class="mini">00</span>
                <strong>分</strong>
                <span class="sec">00</span>
                <strong>秒</strong>
                自动关闭订单
            </span>
        </div>
        <% }%>
    </div>
    <div class="nctouch-oredr-detail-block pl-20 pr-20 fz0">
        <h3 class="lg2">手机号码：</h3>
        <span class="msg-phone lg2 ml-10 col4">
            <%=order_receiver_contact%>
        </span>
    </div>

    <%if (order_message != ''){ %>
    <div class="nctouch-oredr-detail-block clearfix pl-20">
        <h3 class='fl lg2'>买家留言：</h3>
        <div class="info fl ml-10 col4 wp78 pt-20 pb-20"><%= order_message %></div>
    </div>
    <%}%>
    
    <div class="nctouch-oredr-detail-block fz0">
        <%if (order_invoice != ''){%>
        <div class="order-det-overview clearfix pl-20 pr-20 ">
            <h3 class="fl mb-0 lg2">发票信息</h3>
            <div class="info fr lg2"><%= order_invoice %></div>
        </div>
        
        <% } %>
        <% if(payment_name != '') { %>
        <div class="order-det-overview clearfix pl-20 pr-20 ">
            <h3 class="lg2">付款方式：</h3>
            <span class="info lg2 ml-10 col4">
                <%= payment_name %>
            </span>
        </div>
        <%}%>
    </div>
    <!--虚拟兑换码-->
    <% if(code_list) { %>
        <div class="nctouch-vr-order-codes">
            <div class="tit pl-20 pr-20">
                <h3 class="lg2">虚拟兑换码</h3>
                <span class="lg2">有效期至<%=common_virtual_date%></span>
            </div>
            <ul class="pl-0">
                <% var j=0;
                    for (var i in code_list){
                %>
                    <% if(code_list[i].virtual_code_status == 0){ %>
                    <li>
                        <em>有效</em>
                        <%= code_list[i].id %>
                        <span>二维码</span>
                        <br>
                        <% if(goods_list[0].goods_image){ %>
                        <!--                                <img style="display: none; margin-left: 2rem;" src="<%= goods_list[0].goods_image %>">-->
                        <% } %>
                    </li>
                    <% } %>
                <% j++; } %>
                    <% for (var i in code_list){ %> <% if(code_list[i].virtual_code_status != 0){ %>
                        <li class="lose">
                            <em>失效</em>
                            <%=code_list[i].id%>
                        </li>
                    <% } %>
                <% } %>
            </ul>
        </div>
    <% } %>
    <div class="nctouch-vr-order-location">
        <div class="tit">
            <h3><i class="msg"></i>商家信息</h3>
        </div>
        <div class="default" id="goods-detail-o2o">
        </div>
        <div class="more-location">
            <a href="javascript:void(0);" id="store_addr_list"></a>
            <i class="arrow-r"></i>
        </div>
    </div>
    <!--店铺信息-->
    <div class="nctouch-order-item mt5">
        <div class="nctouch-order-item-head">
            <a href="<%=WapSiteUrl%>/tmpl/store.html?shop_id=<%= shop_id %>" class="store one-overflow">
                <i class="iconfont icon-stores mr-10 fz-30 align-middle"></i>
                <strong class="iblock align-middle"><%= shop_name %></strong>
                <i class="iconfont icon-arrow-right ml-10 iblock align-middle fz-26 col9"></i>
            </a>
        </div>
        <!--商品信息-->
        <% for (var i = 0; i < goods_list.length; i++){ %>
        <div class="nctouch-order-item-con">
            <div class="goods-block detail">
                <a href="<%= WapSiteUrl %>/tmpl/product_detail.html?goods_id=<%= goods_list[i].goods_id %>" class="clearfix wp100">
                    <div class="goods-pic">
                        <img src="<%= goods_list[i].goods_image %>">
                    </div>
                    <dl class="goods-info">
                        <dt class="goods-name"><%= goods_list[i].goods_name %></dt>
                        <dd class="goods-type"><%= goods_list[i].order_spec_info %></dd>
                    </dl>
                    <div class="goods-subtotal">
                        <span class="goods-price">￥<em><%= goods_list[i].goods_price %></em></span>
                        <span class="goods-num">x<%= goods_list[i].order_goods_num %></span>
                    </div>
                </a>
                <div class="return-tips tr">
                    <!--虚拟退款-->
                    <% if (goods_list[i].goods_return_status == 0) {%>
                    <a href="javascript:void(0)" order_id="<%= order_id %>" order_goods_id="<%= goods_list[0].order_goods_id %>" class="goods-return fr all_refund_order">退款</a>
                    <% } %>
                    <!--退款状态-->
                    <div class="fz-24 iblock v-top">
                        <% if(goods_list[i].goods_return_status > 0) { %>
                            <a href="<%=WapSiteUrl%>/tmpl/member/member_refund_info.html?refund_id=<%=goods_list[i].order_return_id%>" class="ml4">
                                <span class="default-color">
                                    <%= goods_list[i].goods_return_status_con %>
                                </span>
                            </a>
                        <% } %>
                    </div>
                </div>
            </div>
        </div>
        <div class="nctouch-oredr-detail-block pl-20 pr-20 pt-20 pb-20 bort1 mb-20">
            <div class="order-buyer-message">
                <dl>
                    <dt class="clearfix hgauto">
                        <h4 class="col3">
                            <i class="iconfont icon-news mr-20 col9 fz-32 align-middle"></i>
                            买家留言
                        </h4>
                        <% if(order_message != ''){ %>
                            <p class="z-dhwz col9 mt-10"><%=order_message%></p>
                        <% } %>
                    </dt>
                </dl>
            </div>
        </div>
        <div class="goods-subtotle bgf bort1" style="display:none;">
            <dl>
                <dt>运费</dt>
                <dd class="col8">￥<em><%=order_shipping_fee%></em></dd>
            </dl>
            <!-- 12.20新增加-->
            <% if(voucher_price > 0){ %>
            <dl>
                <dt>店铺优惠券</dt>
                <dd class="col-ed55">减￥<%=voucher_price%></dd>
            </dl>
            <% } %>
            <% if(order_rpt_price > 0){ %>
            <dl>
                <dt>平台红包</dt>
                <dd class="col-ed55">减￥<%=order_rpt_price%></dd>
            </dl>
            <% } %>
            <% if(order_user_discount > 0){ %>
            <dl>
                <dt>会员折扣</dt>
                <dd class="col-ed55">减￥<%=order_user_discount%></dd>
            </dl>
            <% } %>
            <dl class="t">
                <dt>
                    <%if(order_status == 1 && order_payment_amount > 0){ %>
                    应付款
                    <% }else{ %>
                    实付款
                    <% } %>
                    <em class="col8 fz4">（含运费123）</em>
                </dt>
                <dd>￥<em><%=order_payment_amount%></em></dd>
            </dl>
        </div>
        <% } %>
    </div>
    
    <!--联系客服-->
    <span class="im-contact"><a href="javascript:void(0);" class="kefu"><i class="im"></i>联系客服</a></span>
    <!--拨打电话-->
    <% if(shop_phone){ %>
        <span class="to-call"><a href="tel:<%=shop_phone%>" tel="<%=shop_phone%>"><i class="tel"></i>拨打电话</a></span>
    <% } %>
    
    <div class="nctouch-oredr-detail-block mt5">
        <ul class="order-log">
            <li>订单编号：<%=order_id%></li>
            <li>创建时间：<%=order_create_time%></li>
            <% if(payment_time !== '0000-00-00 00:00:00'){%>
            <li>付款时间：<%=payment_time%></li>
            <%}%>
            <% if(order_finished_time !== '0000-00-00 00:00:00'){%>
            <li>完成时间：<%=order_finished_time%></li>
            <%}%>
        </ul>
    </div>
    
    <div class="nctouch-oredr-detail-bottom">
        <% if (order_status == 1) {%>
            <a href="javascript:void(0)" order_id="<%=order_id%>" class="btn cancel-order">取消订单</a>
        <% } %>
        
        <% if (order_refund_status == 1) {%>
            <a href="javascript:void(0)" order_id="<%=order_id%>" class="btn all_refund_order">订单退款</a>
        <% } %>
        
        <% if (order_status == 6 && order_buyer_evaluation_status == 0 && order_refund_status == 0) {%>
            <a href="javascript:void(0)" order_id="<%=order_id%>" class="btn evaluation-order">评价订单</a>
        <% } %>
        
        <% if (order_buyer_evaluation_status == 1 && order_refund_status < 1){ %>
            <a href="javascript:void(0)" order_id="<%=order_id%>" class="btn evaluation-again-order">查看评价</a>
        <% } %>
        
        <% if (order_buyer_evaluation_status == 1 && order_refund_status < 1){ %>
            <a href="javascript:void(0)" order_id="<%=order_id%>" class="btn evaluation-again-order">追加评价</a>
        <% } %>
        
        <% if (order_status == 6 && order_buyer_evaluation_status == 1) { %>
            <a href="javascript:void(0)" order_id="<%=order_id%>" class="btn evaluation-order">查看评价</a>
        <% } %>
        
        <%if(order_status == 7 || order_status == 6){%>
            <a href="javascript:void(0)" order_id="<%=order_id%>" class="del delete-order btn">删除订单</a>
        <% } %>
        
        <%if(order_status == 1 && order_payment_amount > 0){%>
            <a href="javascript:void(0)" data-paySn="<%= payment_number %>" class="btn key check-payment">订单支付</a>
        <% } %>
    
    </div>
</script>

<script type="text/html" id="list-address-script">
    <% for (var i=0 ;i < addr_list.length; i++) { %>
    <li>
        <dl>
            <a href="javascript:void(0)" index_id="<%=i%>">
                <dt><%=addr_list[i].name_info%><span><i></i>查看地图</span></dt>
                <dd><%=addr_list[i].address_info%></dd>
            </a>
        </dl>
        <span class="tel"><a href="tel:<%=addr_list[i].phone_info%>"></a></span>
    </li>
    <% } %>
</script>

<script type="text/javascript" src="../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../js/template.js"></script>
<script type="text/javascript" src="../../js/common.js"></script>
<script type="text/javascript" src="../../js/simple-plugin.js"></script>
<script type="text/javascript" src="../../js/tmpl/vr_order_detail.js"></script>
<script type="text/javascript" src="../../js/jquery.timeCountDown.js"></script>

<!--o2o分店地址Begin-->
<div id="list-address-wrapper" class="nctouch-full-mask hide">
    <div class="nctouch-full-mask-bg"></div>
    <div class="nctouch-full-mask-block">
        <div class="header absolute">
            <div class="header-wrap">
                <div class="header-l"><a href="javascript:void(0);"> <i class="back"></i> </a></div>
                <div class="header-title">
                    <h1>商家信息</h1>
                </div>
            </div>
        </div>
        <div class="nctouch-main-layout">
            <div class="nctouch-o2o-tip">
                <a href="javascript:void(0);" id="map_all"> <i></i> 全部实体分店共 <em></em> 家 <span></span> </a>
            </div>
            <div class="nctouch-main-layout-a" id="list-address-scroll">
                <ul class="nctouch-o2o-list" id="list-address-ul"></ul>
            </div>
        </div>
    </div>
</div>
<!--o2o分店地址End-->
<!--o2o分店地图Begin-->
<div id="map-wrappers" class="nctouch-full-mask hide">
    <div class="nctouch-full-mask-bg"></div>
    <div class="nctouch-full-mask-block">
        <div class="header transparent-map absolute">
            <div class="header-wrap">
                <div class="header-l">
                    <a href="javascript:void(0);"> <i class="back"></i> </a>
                </div>
            </div>
        </div>
        <div class="nctouch-map-layout">
            <div id="baidu_map" class="nctouch-map"></div>
        </div>
    </div>
</div>
<!--o2o分店地图End-->
<!--底部总金额固定层End-->
<div class="nctouch-bottom-mask down">
    <div class="nctouch-bottom-mask-bg"></div>
    <div class="nctouch-bottom-mask-block">
        <div class="nctouch-bottom-mask-tip"><i></i>点击此处返回</div>
        <div class="nctouch-bottom-mask-top"><a class="nctouch-bottom-mask-close" href="javascript:void(0);"><i></i></a>
            <div class="msg-again-layout">
                <h4>如果您没有收到虚拟商品兑换码或更改其它手机接收信息，请正确输入接收用手机号码并确认发送。</h4>
                <h5>系统最多可重新发送5次兑换码提示信息。</h5>
                <input type="tel" name="buyer_phone" class="inp-tel fz-28 tc" id="buyer_phone" autocomplete="off"
                       maxlength="11"/>
            </div>
            <p class="rpt_error_tip"></p>
        </div>
        <a href="javascript:void(0);" id="tosend" class="btn-l mt10">确认发送</a>
    </div>
</div>
</body>
</html>
<?php
    include __DIR__ . '/../../includes/footer.php';
?>

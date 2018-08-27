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
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
    <link rel="stylesheet" href="../../css/iconfont.css">
</head>
<body>
<header id="header" class="fixed">
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
<div class="nctouch-main-layout mb20">
    <div class="nctouch-order-list" id="order-info-container">
        <ul></ul>
    </div>
</div>
<footer id="footer"></footer>
<script type="text/html" id="order-info-tmpl">
    <div class="nctouch-oredr-detail-block order-status-bg">
        <h3>交易状态</h3>
        <div class="order-state"><%= order_state_con %></div>
        <%if (order_cancel_reason != ''){%>
            <div class="info"><%=order_cancel_reason%></div>
        <%}%>
        <%if(order_status == 4){%>
        <div class="time fnTimeCountDown colf fz-28" data-end="<%=order_receiver_date%>">
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
                自动确认收货
            </span>
        </div>
        <% }%>
        <%if(order_status == 1){%>
        <div class="time fnTimeCountDown colf fz-28" data-end="<%=cancel_time%>">
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
    <!--   <%if(order_status == 4){%>
      <div class="nctouch-oredr-detail-delivery">
          <a href="<%=WapSiteUrl%>/tmpl/member/order_delivery.html?order_id=<%=order_id%>">
              <span class="time-line">
                  <i></i>
              </span>
              <div class="info">
                  <p id="delivery_content"></p>
                  <time id="delivery_time"></time>
              </div>
              <span class="arrow-r"></span>
          </a>
      </div>
      <%}%> -->
    <div class="nctouch-oredr-detail-block pl-20 pr-20 pt-30 pb-20">
        <div class="nctouch-oredr-detail-add">
            <dl class="clearfix">
                <% if(chain_id > 0){ %>
                <dt class="fl"><p class="one-overflow fl w4"><%=order_receiver_name%></p></dt>
                <dd class="fr"><p class="addr-detail  z-dhwz"><%=order_receiver_contact%></p></dd>
                <% }else{ %>
                <dt class="clearfix"><p class="one-overflow fl w4"><%=order_receiver_name%></p>
                    <p class="fr"><%=order_receiver_contact%></p></dt>
                <dd><p class="addr-detail z-dhwz"><%=order_receiver_address%></p></dd>
                <% } %>
            </dl>
        </div>
    </div>
    <% if(order_message != ''){ %>
    <!--<div class="nctouch-oredr-detail-block">
        <h3><i class="msg"></i>买家留言</h3>
        <div class="info"><%=order_message%></div>
    </div>-->
    <% } %>
    <div class="nctouch-oredr-detail-block ">
        <%if (order_invoice != ''){%>
        <div class="order-det-overview clearfix pl-20 pr-20 pt-20 pb-20">
            <h3 class="fl mb-0">发票信息</h3>
            <div class="info fr"><%=order_invoice%></div>
        </div>
        <%}%> <%if (payment_name != ''){%>
        <div class="order-det-overview clearfix pl-20 pr-20 pt-20 pb-20">
            <h3 class="fl mb-0">付款方式</h3>
            <div class="info fr"><%=payment_name%></div>
        </div>
        <%}%>
    </div>
    <!-- 门店自提 -->
    <% if(chain_id > 0){ %>
    <div class="order-ziti-addr col5 bgf">
        <dl class="pt-30 pl-20 pr-20">
            <dt>店铺地址：</dt>
            <dd>
                <%=chain_info.chain_province+chain_info.chain_city+chain_info.chain_county+chain_info.chain_address%>
            </dd>
        </dl>
        <dl class="pt-30 pb-30 pl-20 pr-20 relative wp100 box-size">
            <dt>联系电话：</dt>
            <dd><%=chain_info.chain_mobile%></dd>
            <a onclick="dial('<%=chain_info.chain_mobile%>')" href="javascript:void(0)" class="btn-phone"> <i class="icon icon-phone"></i> </a>
        </dl>
        <dl class="pt-30 pb-30 pl-20 pr-20 bort1 borb1">
            <dt>提货码：</dt>
            <% if(chain_code.chain_code_id){ %>
            <dd><%=chain_code.chain_code_id%></dd>
            <% }else{ %>
            <dd>无</dd>
            <% } %>
        </dl>
    </div>
    <% } %>
    <!--店铺信息-->
    <div class="nctouch-order-item mt-20">
        <div class="nctouch-order-item-head bgf">
            <%if (shop_self_support){%>
                <a href="<%=WapSiteUrl%>/tmpl/store.html?shop_id=<%=shop_id%>" class="store">
                    <i class="iconfont icon-stores mr-10 fz-30 align-middle"></i>
                    <strong class="iblock align-middle one-overflow mwp60"><%=shop_name%></strong>
                    <i class="iconfont icon-arrow-right iblock align-middle fz-26 col9 ml-10"></i>
                </a>
                <% }else{ %>
                <a href="<%=WapSiteUrl%>/tmpl/store.html?shop_id=<%=shop_id%>" class="store">
                    <i class="icon"></i>
                    <%=shop_name%>
                    <i class="arrow-r"></i>
                </a>
            <%}%>
        </div>
        <!--商品信息-->
        <div class="nctouch-order-item-con">
            <% for(i=0; i < goods_list.length; i++){%>
            <% if (goods_list[i].goods_refund_status == 0 && order_status == 6 && goods_list[i].goods_price !=0 && goods_list[i].order_goods_num > goods_list[i].order_goods_returnnum) { %>
            <div class="goods-block detail borb1 mb-20 return_block_height">
                <% }else{ %>
                <div class="goods-block detail borb1 mb-20 clearfix">
                    <%}%>
                    <a href="<%=WapSiteUrl%>/tmpl/product_detail.html?goods_id=<%=goods_list[i].goods_id%>" class="wp100">
                        <div class="goods-pic">
                            <img src="<%=goods_list[i].goods_image%>">
                        </div>
                        <dl class="goods-info">
                            <dt class="goods-name"><%=goods_list[i].goods_name%></dt>
                            <% var order_spec_info = '';
                                    if(goods_list[i].order_spec_info && goods_list[i].order_spec_info.length > 0){
                                        for(var j in goods_list[i].order_spec_info){
                                            order_spec_info += goods_list[i].order_spec_info[j] + '; ';
                                        }
                            %>
                            <dd class="goods-type one-overflow"><%=order_spec_info%></dd>
                            <% } %>
                        </dl>
                        <div class="goods-subtotal">
                            <% if(goods_list[i].pintuan_temp_order == 1){ %>
                                <span class="goods-price">￥<em><%=goods_list[i].order_goods_amount%></em></span>
                                <span class="old-goods-price">￥<em><%=goods_list[i].goods_price%></em></span>
                            <% }else{ %>
                                <span class="goods-price">￥<em><%=goods_list[i].goods_price%></em></span>
                            <% } %>
                            <span class="goods-num">x<%=goods_list[i].order_goods_num%></span>
                        </div>
                    </a>
                    <div class="return-tips tr">
                        <% if(goods_list[i].goods_return_status == 1) {%>
                            <a class="ml4 cancel-refund" order_return_id="<%=goods_list[i].order_return_id%>"><span>取消退款</span></a>
                        <% } %>
                        <div class="fz-24 iblock ">
                            <% if(goods_list[i].goods_return_status > 0) {%>
                            <a href="<%=WapSiteUrl%>/tmpl/member/member_refund_info.html?refund_id=<%=goods_list[i].order_return_id%>" class="ml4">
                                <span class="default-color">
                                    <%=goods_list[i].goods_return_status_con%>
                                </span>
                            </a>
                            <% } %>
                            <% if(goods_list[i].goods_refund_status > 0) {%>
                            <a href="<%=WapSiteUrl%>/tmpl/member/member_return_info.html?refund_id=<%=goods_list[i].order_refund_id%>" class="ml4">
                                <span class="default-color"><%=goods_list[i].goods_refund_status_con%></span>
                            </a>
                            <% } %>
                        </div>
                        <div class="iblock">
                            <% if(goods_list[i].pintuan_temp_order == 1){ %>
                                <a href="javascript:void(0)"></a>
                            <%}else{%>
                                <% if (goods_list[i].goods_refund_status == 0 && order_status == 6 && goods_list[i].goods_price !=0 && goods_list[i].order_goods_num > goods_list[i].order_goods_returnnum) {%>
                                    <a href="javascript:void(0)" order_id="<%=order_id%>" order_goods_id="<%=goods_list[i].order_goods_id%>" class="goods-return fr">退货</a>
                                <%}%>
                                <% if (goods_list[i].goods_return_status == 0 && (order_status == 2 || (order_status == 11 && payment_id == 1)) && goods_list[i].goods_price !=0) {%>
                                    <a href="javascript:void(0)" order_id="<%=order_id%>" order_goods_id="<%=goods_list[i].order_goods_id%>" class="goods-return fr">退款</a>
                                <%}%>
                            <%}%>
                        </div>
                    </div>
                </div>
                <%}%>
                <!-- 门店自提 -->
                <% if(chain_id > 0){ %>
                    <div class="order-ziti-addr order-ziti-message col5 bgf">
                        <dl class="pl-20 pr-20 pt-20 pb-20">
                            <dt class="">买家留言：</dt>
                            <% if(order_message){ %>
                                <dd class="one-overflow mt1"><%=order_message%></dd>
                            <% }else{ %>
                                <div class="info col5 fr">无</div>
                            <% } %>
                        </dl>
                    </div>
                    <div class="nctouch-oredr-detail-block bort1 borb0 order-ziti-det">
                        <% if(voucher_price > 0){ %>
                            <div class="order-det-overview clearfix pl-20 pr-20 pt-20 pb-20">
                                <h3 class="fl mb-0">店铺代金券</h3>
                                <div class="info col5 fr">减￥<%=voucher_price%></div>
                            </div>
                        <% } %>
                        <% if(order_rpt_price > 0){ %>
                            <div class="order-det-overview clearfix pl-20 pr-20 pt-20 pb-20">
                                <h3 class="fl mb-0">平台红包</h3>
                                <div class="info col5 fr">减￥<%=order_rpt_price%></div>
                            </div>
                        <% } %>
                        <% if(order_user_discount > 0){ %>
                            <div class="order-det-overview clearfix pl-20 pr-20 pt-20 pb-20">
                                <h3 class="fl mb-0">会员折扣</h3>
                                <div class="info col5 fr">减￥<%=order_user_discount%></div>
                            </div>
                        <% } %>
                        <div class="order-det-overview clearfix pl-20 pr-20 pt-20 pb-20">
                            <div class="info fr default-color">合计：￥<%=order_payment_amount%></div>
                        </div>
                    </div>
                <% } else { %>
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
                <div class="goods-subtotle bgf bort1">
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
        </div>
        <span class="im-contact">
            <a href="javascript:void(0);" class="kefu"><i class="im"></i>联系客服</a>
        </span>
        <% if(shop_phone){ %>
        <span class="to-call">
            <a href="tel:<%=shop_phone%>" tel="<%=shop_phone%>"><i class="tel"></i>拨打电话</a>
        </span>
        <% } %>
        <div class="nctouch-oredr-detail-block mt5 bort1">
            <ul class="order-log">
                <li>订单编号：<%=order_id%></li>
                <li>创建时间：<%=order_create_time%></li>
                <% if(payment_time !== '0000-00-00 00:00:00'){%>
                    <li>付款时间：<%=payment_time%></li>
                <%}%>
                <% if(chain_id > 0){ %>
                    <% if(order_finished_time !== '0000-00-00 00:00:00'){%>
                        <li>提货时间：<%=order_finished_time%></li>
                    <%}%>
                <% } %>
                <% if(order_buyer_evaluation_time !== '0000-00-00 00:00:00'){%>
                    <li>评价时间：<%=order_buyer_evaluation_time %></li>
                <%}%>
            </ul>
        </div>
        <div class="nctouch-oredr-detail-bottom">
            <!--退款/货状态-->
            <% if (order_return_status == 1 || order_refund_status == 1) {%>
                <p>退款/退货中...</p>
            <% } %>
            <!--取消状态-->
            <% if (order_status == 1 || (order_status == 3 && payment_id == 2)) {%>
                <a href="javascript:void(0)" order_id="<%=order_id%>" class="btn cancel-order">取消订单</a>
            <% } %>
            <!--物流信息-->
            <% if (order_status == 4) { %>
                <a href="javascript:void(0)" order_id="<%=order_id%>" express_id="<%=order_shipping_express_id%>" shipping_code="<%=order_shipping_code%>" class="btn viewdelivery-order">查看物流</a>
            <%}%>
            <!--确认收货-->
            <% if (order_status == 4){ %>
                <a href="javascript:void(0)" order_id="<%=order_id%>" class="btn key sure-order">确认收货</a>
            <% } %>
            <!--删除订单-->
            <% if (order_status == 6 || order_status == 7) {%>
                <a href="javascript:void(0)" order_id="<%=order_id%>" class="btn delete-order">删除订单</a>
            <% } %>
            <!--评价订单-->
            <% if (order_status == 6 && order_buyer_evaluation_status == 0) {%>
                <% if (order_refund_status < 1){ %>
                    <a href="javascript:void(0)" order_id="<%=order_id%>" class="btn key evaluation-order">评价订单</a>
                <% } %>
            <% } %>
            <!--自提订单-->
            <%if(chain_id > 0){ %>
                <!--查看评价-->
                <% if (order_buyer_evaluation_status == 1 && order_refund_status < 1){ %>
                    <a href="javascript:void(0)" order_id="<%=order_id%>" class="btn evaluation-again-order"> 查看评价 </a>
                <% } %>
            
            <% } else { %>
                <!--追评-->
                <% if (order_buyer_evaluation_status == 1 && order_refund_status < 1){ %>
                    <a href="javascript:void(0)" order_id="<%=order_id%>" class="btn evaluation-again-order"> 追加评价 </a>
                <% } %>
            
            <% } %>
            <!--订单支付-->
            <% if(order_status == 1 && order_payment_amount > 0){ %>
                <a href="javascript:" onclick="payOrder('<%= payment_number %>','<%=order_id %>')" data-paySn="<%=order_id %>" class="btn key check-payment">订单支付</a>
            <% } %>
        </div>
    </script>
    <script type="text/javascript" src="../../js/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/template.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/order_detail.js"></script>
    <script type="text/javascript" src="../../js/jquery.timeCountDown.js"></script>
    </body>
    </html>
<?php
    include __DIR__ . '/../../includes/footer.php';
?>

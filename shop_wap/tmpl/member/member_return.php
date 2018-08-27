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
    <title>退款列表</title>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
</head>
	<style>
		.nctouch-order-item-footer .store-totle{height: auto !important;}
	</style>
<body>
    <header id="header">
        <div class="header-wrap">
            <div class="header-l">
                <a href="member.html"> <i class="back"></i> </a>
            </div>
            <span class="header-tab">
                <a href="member_refund.html">退款</a>
                <a href="javascript:void(0);" class="cur">退货</a>
                <a href="vr_member_refund.html">虚拟退款</a>
            </span>
            <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>
        </div>
        <div class="nctouch-nav-layout">
            <div class="nctouch-nav-menu"> <span class="arrow"></span>
                <ul>
                    <li><a href="../../index.html"><i class="home"></i>首页</a></li>
                    <li><a href="../search.html"><i class="search"></i>搜索</a></li>
                    <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout">
        <div class="nctouch-order-list mt-20">
            <ul id="return-list">
            </ul>
        </div>
    </div>
    <div class="fix-block-r"> <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a> </div>
    <footer id="footer" class="bottom"></footer>
    <script type="text/html" id="return-list-tmpl">
        <% var return_list = items; %>
        <% if (return_list.length > 0){%>
            <% for(var i = 0;i<return_list.length;i++){
	%>
                <li class=" <%if(i>0){%>mt-20<%}%>">
                    <div class="nctouch-order-item">
                        <div class="nctouch-order-item-head borb1">
                            <a href="javascript:void(0);" class="store one-overflow mw6"><i class="icon"></i><%=return_list[i].seller_user_account%></a><span class="state"><%=return_list[i].return_state_text%></span>
                        </div>
                        <div class="nctouch-order-item-con">
                            <div class="goods-block">
                                <a href="<%=WapSiteUrl%>/tmpl/member/member_return_info.html?refund_id=<%=return_list[i].order_return_id%>" class="wp100">
                                    <div class="goods-pic">
                                        <img src="<%=return_list[i].order_goods_pic%>" />
                                    </div>
                                    <dl class="goods-info">
                                        <dt class="goods-name"><%=return_list[i].order_goods_name%></dt>
                                    </dl>
                                    <div class="goods-subtotal">
                                                
                                        <span class="goods-price">￥<em><%=return_list[i].order_goods_price%></em></span>
                                     
                                        <span class="goods-num fz-24 colbc">x<%=return_list[i].order_goods_num%></span>
                                        <div class="fz0">
                                           
                                             
                                        </div>
                                        
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="nctouch-order-item-footer">
                        	 <div  class="store-totle tl borb1 clearfix" ><div class="fl">订单编号：</div><div class="fl" style="width: 75%;"><%=return_list[i].order_number%></div></div>
                            <div class="store-totle clearfix">
                                <time class="refund-time fl ">
                                    <%=return_list[i].return_add_time%>
                                </time>
                                <span class="refund-sum">退款金额：<em>￥<%=return_list[i].return_cash%></em></span>
                            </div>
                            <div class="handle">
                                <a href="<%=WapSiteUrl%>/tmpl/member/member_return_info.html?refund_id=<%=return_list[i].order_return_id%>" class="btn">退货详情</a>
                            </div>
                        </div>
                    </div>
                </li>
                <%}%>
                    <% if (hasmore) {%>
                        <li class="loading">
                            <div class="spinner"><i></i></div>订单数据读取中...</li>
                        <% } %>
                            <%}else {%>
                                <div class="nctouch-norecord refund">
                                    <div class="norecord-ico"><i></i></div>
                                    <dl>
                                        <dt>您还没有退货信息</dt>
                                        <dd>已购订单详情可申请退货</dd>
                                    </dl>
                                </div>
                                <%}%>
    </script>
    <script type="text/javascript" src="../../js/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/template.js"></script>
    
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/ncscroll-load.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member_return.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>
</body>

</html>
<?php 
include __DIR__.'/../../includes/footer.php';
?>

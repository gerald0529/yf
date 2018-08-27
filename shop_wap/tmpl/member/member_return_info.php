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
    <meta name="format-detection" content="telephone=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="msapplication-tap-highlight" content="no" />
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
    <title>退货详情</title>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
</head>
<body>
<header id="header">
    <div class="header-wrap">
        <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>
    <span class="header-title">
    <h1>退货详情</h1>
    </span>
    </div>
</header>
<div class="nctouch-main-layout" id="return-info-div"> </div>
<footer id="footer"></footer>
<script type="text/html" id="return-info-script">

    <div class="nctouch-order-item mt5">
        <div class="nctouch-order-item-con">
            <div class="goods-block detail">
                <a href="javascript:;" class="wp100">
                    <div class="goods-pic">
                        <img src="<%=order_goods_pic%>">
                    </div>
                    <dl class="goods-info">
                        <dt class="goods-name"><%=order_goods_name%></dt>
                    </dl>
                    <div class="goods-subtotal">
                        <span class="goods-price">￥<%=order_goods_price%></em></span>
                        <span class="goods-num">x <%=order_goods_num%></span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <h3 class="nctouch-default-list-tit">我的退货申请</h3>
    <ul class="nctouch-default-list">
        <li>
            <h4>退货编号</h4>
            <span class="num"><%=return_code%></span>
        </li>
        <li>
            <h4>退货原因</h4>
            <span class="num"><%=return_reason%></span>
        </li>
        <li>
            <h4>退款金额</h4>
            <span class="num"><%=return_cash%></span>
        </li>
        <li>
            <h4>退款说明</h4>
            <span class="num"><%=return_message%></span>
        </li>
    </ul>
    <h3 class="nctouch-default-list-tit">商家退货处理</h3>
    <ul class="nctouch-default-list">
        <li>
            <h4>审核状态</h4>
            <span class="num"><%=return_shop_state_text%></span></li>
        <li>
            <h4>商家备注</h4>
            <span class="num"><%=return_shop_message%></span></li>
    </ul>
    <h3 class="nctouch-default-list-tit">商城退货审核</h3>
    <ul class="nctouch-default-list">
        <li>
            <h4>平台确认</h4>
            <span class="num"><%=return_platform_state_text%></span></li>
        <li>
            <h4>平台备注</h4>
            <span class="num"><%=return_platform_message%></span></li>
    </ul>
    <%if( return_state == 5 ) {%>
    <h3 class="nctouch-default-list-tit">退款详细</h3>
    <!--由原先的“预存款”修改为“余额”-->
    <ul class="nctouch-default-list">
        <li>
            <h4>支付方式</h4>
            <span class="num">余额</span></li>
        <li>
            <h4>返还金额</h4>
            <span class="num"><%= return_cash %></span></li>
    </ul>
    <%}%>
</script>
<script type="text/javascript" src="../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../js/template.js"></script>

<script type="text/javascript" src="../../js/common.js"></script>
<script type="text/javascript" src="../../js/simple-plugin.js"></script>
<script type="text/javascript" src="../../js/tmpl/member_return_info.js"></script>
</body>
</html>
<?php 
include __DIR__.'/../../includes/footer.php';
?>
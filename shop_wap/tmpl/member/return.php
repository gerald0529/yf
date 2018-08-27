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
        <title>申请退款/退货</title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
        <style>
            .word {
                font-size: 0.5rem;
                color: #888;
            }
        </style>
    </head>
    <body>
    <header id="header">
        <div class="header-wrap">
            <div class="header-l"><a href="javascript:history.go(-1)"> <i class="back"></i> </a></div>
            <div class="header-title">
                <h1>申请退货</h1>
            </div>
            <div class="header-r"><a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a>
            </div>
        </div>
        <div class="nctouch-nav-layout">
            <div class="nctouch-nav-menu"><span class="arrow"></span>
                <ul>
                    <li><a href="../../index.html"><i class="home"></i>首页</a></li>
                    <li><a href="../search.html"><i class="search"></i>搜索</a></li>
                    <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout mb20">
        <div class="nctouch-order-list" id="order-info-container"></div>
        <form>
            <div class="nctouch-inp-con">
                <ul class="form-box borb1 pt-0">
                    <li class="form-item borb1">
                        <h4 class="cash">退款金额</h4>
                        <div class="input-box">
                            <input pattern="[0-9.]*" class="inp bgf mrt2" name="refund_amount" readonly placeholder="退款金额不能超过可退金额" type="text">
                            <span class="input-del"></span> <span class="note"><!-- <em id="returnAble"></em> -->
                                <h6>最多可退金额</h6>
                            </span>
                        </div>
                    </li>
                    <li class="form-item">
                        <h4 class="num">退货数量</h4>
                        <div class="input-box pdt3">
                            <div class="refundnum inblock">
                                <input type="hidden" class="gprice" value="">
                                <input type="hidden" class="gnum" value="">
                                <a class="reduce numsclick">-</a>
                                <input class="refundnums" data-max="" name="nums" value="" style="">
                                <a class="no_add numsclick">+</a>
                            </div>
                        </div>
                    </li>
                    <!--退款/退货原因说明下拉选择-->
                    <li class="form-item">
                        <h4 class="reason">退款原因</h4>
                        <div class="input-box pdt3">
                            <select name="return_reason_id" id="refundReason">
<!--                                <option value=""></option>-->
                            </select>
                        </div>
                    </li>

                </ul>
                <div class="back-goods-text">
                    <h4 class="remark">退货说明</h4>
                    <textarea name="buyer_message" id="buyer_message" cols="30" rows="10" placeholder="请输入退货说明"
                              maxlength="200" style="position: relative;"></textarea>
                    <div style="position: absolute;right: 10px;">
                        <span id="words" class="word">0</span>
                        <span class="word">/ 200</span>
                    </div>
                </div>
                <div class="error-tips"></div>
                <div class="form-btn"><a href="javascript:" class="btn-l">提交</a></div>
            </div>
        </form>
    </div>
    <footer id="footer"></footer>
    <script type="text/html" id="order-info-tmpl">
        <div class="nctouch-order-item mt5 mb-0">
            <div class="nctouch-order-item-con">
                <div class="goods-block detail bgf mb-0">
                    <a href="<%=WapSiteUrl%>/tmpl/product_detail.html?goods_id=<%=goods.goods_id%>" class="wp100">
                        <div class="goods-pic">
                            <img src="<%=goods.goods_image%>">
                        </div>
                        <dl class="goods-info">
                            <dt class="goods-name">
                                <%=goods.goods_name%>
                            </dt>
                            <% var goods_spec_info = '';
                            if(goods.order_spec_info && goods.order_spec_info.length > 0){
                            for(var i in goods.order_spec_info){
                            goods_spec_info += goods.order_spec_info[i] + '; ';
                            }
                            %>

                            <dd class="goods-type one-overflow"><%=goods_spec_info%></dd>
                            <% } %>
                        </dl>
                        <div class="goods-subtotal">
                            <span class="goods-price">￥<em><%=goods.goods_price%></em></span>
                            <span class="goods-num">x<%=goods.order_goods_num%></span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </script>
    <script type="text/javascript" src="../../js/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/template.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/return.js"></script>
    </body>
    </html>
<?php
include __DIR__ . '/../../includes/footer.php';
?>

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
        <meta name="sharecontent" data-msg-img="https://ss0.baidu.com/6ONWsjip0QIZ8tyhnq/it/u=2927678406,1546747626&fm=58" />
        <title>商品详情</title>
        <link rel="stylesheet" type="text/css" href="../css/base.css">
        <link rel="stylesheet" type="text/css" href="../css/nctouch_common.css">
        <link rel="stylesheet" type="text/css" href="../css/nctouch_products_detail.css">
        <link rel="stylesheet" type="text/css" href="../css/nctouch_products_list.css"/>
        <link rel="stylesheet" href="../css/new-style.css">
        <style type="text/css">
            .goods-detail-foot .buy-handle a.add-cart, .goods-option-foot .buy-handle a.add-cart {
                float: left;
            }

            .sku-dtips {
                font-size: 0.8rem;
            }

            .goods-option-foot .only-two-handle {
                width: 100%;
            }

            .goods-option-foot .only-two-handle a.add-cart {
                width: 50%;
            }
        </style>
    </head>
    <body>
        <header id="header" class="posf">
            <div class="header-wrap">
                <div class="header-l">
                    <a href="javascript:history.go(-1)"> <i class="back"></i> </a>
                </div>
                <ul class="header-nav">
                    <li><a href="javascript:void(0);" id="goodsDetail">商品</a></li>
                    <li><a href="javascript:void(0);" id="goodsBody">详情</a></li>
                    <li><a href="javascript:void(0);" id="goodsEvaluation">评价</a></li>
                    <li class="cur"><a href="javascript:void(0);" id="goodsRecommendation">推荐</a></li>
                </ul>
                <div class="header-r"><a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>
            </div>
            <div class="nctouch-nav-layout">
                <div class="nctouch-nav-menu"> <span class="arrow"></span>
                    <ul>
                        <li><a href="../index.html"><i class="home"></i>首页</a></li>
                        <li><a href="../tmpl/search.html"><i class="search"></i>搜索</a></li>
                        <li><a href="../tmpl/cart_list.html"><i class="cart"></i>购物车<sup></sup></a></li>
                        <li><a href="../tmpl/member/member.html"><i class="member"></i>我的商城</a></li>
                        <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
                    </ul>
                </div>
            </div>
        </header>

    </body>
    </html>

    <script type="text/html" id="productRecommendation">
        <div class="goods-detail-recom grid">
            <h4>店铺推荐</h4>
            <ul class="new-goods bg-f5">
                <% if(goods_commend_list){ %>
                    <% for (var i = 0; i<goods_commend_list.length ;i++){ %>
                    <li>
                        <a href="product_detail.html?goods_id=<%=goods_commend_list[i].goods_id%>">
                            <div class="overhide">
                                <div class="table">
                                    <span class="img-area">
                                        <img src="<%=goods_commend_list[i].common_image%>">
                                    </span>
                                </div>
                            </div>
                            <h5 class="fz-28 col2 more-overflow pt-20"><%=goods_commend_list[i].common_name%></h5>
                            <b class="fz-30 pl-10 pr-10 mb-20 mt-20">￥<%=goods_commend_list[i].common_price%></b>
                        </a>
                        
                        
                    </li>
                    <% } %>
                <% } %>
            </ul>
        </div>
    </script>

    <script type="text/javascript" src="../js/zepto.min.js"></script>

    <script type="text/javascript" src="../js/template.js"></script>
    <script type="text/javascript" src="../js/swipe.js"></script>
    <script type="text/javascript" src="../js/common.js"></script>
    <script type="text/javascript" src="../js/iscroll.js"></script>
    <script type="text/javascript" src="../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../js/tmpl/footer.js"></script>
    <script type="text/javascript" src="../js/fly/requestAnimationFrame.js"></script>
    <script type="text/javascript" src="../js/fly/zepto.fly.min.js"></script>

    <script type="application/javascript">
        //渲染页面
        $.ajax({
            url: ApiUrl + "/index.php?ctl=Goods_Goods&met=goods&typ=json",
            type: "get",
            data: { goods_id: getQueryString("goods_id"),
                    k: getCookie('key'),
                    u: getCookie('id')
            },
            dataType: "json",
            success: function (result) {
                if (result.status == 200) {
                    var html = template.render('productRecommendation', result.data);
                    $("body").append(html);
                } else {
                    $.sDialog({skin: "red", content: "网络异常", okBtn: false, cancelBtn: false});
                }
            }
        });

        var goods_id = getQueryString("goods_id");

        $("#goodsDetail").click(function () {
            window.location.href = WapSiteUrl + "/tmpl/product_detail.html?goods_id=" + goods_id
        });
        $("#goodsBody").click(function () {
            window.location.href = WapSiteUrl + "/tmpl/product_info.html?goods_id=" + goods_id
        });
        $("#goodsEvaluation").click(function () {
            window.location.href = WapSiteUrl + "/tmpl/product_eval_list.html?goods_id=" + goods_id
        });
        $('body').on('click', '#goodsRecommendation', function () {
            window.location.href = WapSiteUrl + '/tmpl/product_recommendation.html?goods_id=' + goods_id;
        });
    </script>
<?php
include __DIR__ . '/../includes/footer.php';
?>
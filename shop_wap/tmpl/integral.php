<?php 
include __DIR__.'/../includes/header.php';
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
    <title>商城触屏版</title>
    <link rel="stylesheet" type="text/css" href="../css/base.css">
    <link rel="stylesheet" type="text/css" href="../css/index.css">
    <link rel="apple-touch-icon" href="images/touch-icon-iphone.png"/>
    <link rel="stylesheet" type="text/css" href="../css/nctouch_integral.css"/>
</head>
<body>
<header id="" class="transparent">
    <div class="header-wrap">
        
        <div class="header-l">
            <a href="javascript:history.go(-1)">
                <i class="back back2"></i>
            </a>
        </div>
        
        
    </div>
    
</header>
<div class="integral-wap fixed-Width">
    <div class="integral-ban"><img id="promotiom_img"></div>
<!--    <ul class="integral-types">-->
<!--        <li class="icon-voucher">-->
<!--            <a href="javascript:;">-->
<!--                <i class="iconfont "></i>-->
<!--                <p>店铺代金券</p>-->
<!--            </a>-->
<!--        </li>-->
<!--        <li class="login-btn">-->
<!--            <a href="javascript:;">-->
<!--                <i class="iconfont"></i>-->
<!--                <p>登录获知会员信息</p>-->
<!--            </a>-->
<!--        </li>-->
<!--        <li class="icon-present">-->
<!--            <a href="javascript:;">-->
<!--                <i class="iconfont"></i>-->
<!--                <p>积分兑换礼品</p>-->
<!--            </a>-->
<!--        </li>-->
<!--    </ul>-->
    <div class="integral-list mrt4 bort1">
        <div class="integral-list-head">
            <h3>
                <i class="icon ic-present"></i>
                <span>热门礼品兑换</span>
                <a href="integral_product_list.html" class="more">更多<i class="iconfont icon-iconjiantouyou"></i></a>
            </h3>
        </div>
        <div class="item-goods"></div>
        <div class="integral-list-head">
            <h3>
                <i class="icon ic-integral"></i>
                <span>热门代金券</span>
                <a href="voucher_list.html" class="more">更多<i class="iconfont icon-iconjiantouyou"></i></a>
            </h3>
        </div>
        <div class="integral-area"></div>
    </div>
    <footer id="footer" class="fixed-Width"></footer>
    <!-- 新增积分底部导航栏 -->
    <ul class="integral-foot-tab">
        <li class="active"><a href="integral.html"><i class="icon"></i><span>积分兑换</span></a></li>
        <li><a href="javascript:;" class="myScore"><i class="icon"></i><span>我的积分</span></a></li>
    </ul>
</div>

<script type="text/html" id="goods">
    <% if (points_goods) { %>
    <% var items = points_goods %>
    <ul class="integral-present-goods goods-list clearfix">
        <% for (var i in items) { %>
        <% if (i>=4) break; %>
        <li>
            <a href="integral_product_detail.html?id=<%= items[i].id %>">
                
                <dl class="goods-info">
                    <dt class="goods-name"><%= items[i].points_goods_name %></dt>
                    <dd>参考价格：￥<%= items[i].points_goods_price %></dd>
                    <dd><b class="col-red">所需积分：</b><em><%= items[i].points_goods_points %></em></dd>
                </dl>
                <% if (items[i].points_goods_limitgrade != 1) { %><i class="iconfont"><img src="../images/V<%= items[i].points_goods_limitgrade - 1 %>.png"></i> <% } %>
                <div class="goods-pic">
                    <img src="<%= items[i].points_goods_image %>">
                </div>
            </a>
        </li>
        <% } %>
    </ul>
    <% } %>
</script>

<script type="text/html" id="integral">
    <% if (voucher) { %>
    <% var items = voucher %>
        <% for (var i in items) { %>
        <% if (i>2) break; %>
        <div class="vou-exchange">
            <div class="left">
                <h4><%= items[i].shop_name %></h4>
                <div><span>购满<%= items[i].voucher_t_limit %>可使用</span></div>
                <time><%= items[i].voucher_t_end_date_day %>前有效</time>
                <div class="vou-pri"><em>￥</em><b><%= items[i].voucher_t_price %></b></div>
            </div>
            <div class="right">
                <p>需<%= items[i].voucher_t_points %>积分</p>
                <i><%= items[i].voucher_t_giveout %>人已兑换</i>
                <div><a href="javascript:void(0)" nctype="exchange_integrate" data-vid="<%= items[i].id %>">立即兑换</a></div>
                
            </div>
        </div>
        <% } %>
    <% } %>
</script>

<script type="text/html" id="user">
    <% if (user_info && user_resource) { %>
    <div class="logged-in">
        <p>
            <img src="<%= user_info.user_logo %>">
        </p>
        <ul>
            <li>
                <a href="javascript:;" class="myScore">
                    <div><em><%= user_resource.user_points %></em></div>
                    <span style="line-height:0.7rem">我的积分</span>
                </a>
            </li>
            <li style="line-height:1.6rem;float:right">
                <a href="./member/pointslog_list.html" class="more">兑换记录
                    <img src="../images/new/arrow-right.png" style="background-size:50%;margin-top:0.5rem;height:0.6rem;margin-left:0.2rem%">
                </a>
            </li>
        </ul>
    </div>
    <% } %>
</script>

<script type="text/javascript" src="../js/zepto.min.js"></script>
<script type="text/javascript" src="../js/simple-plugin.js"></script>
<script type="text/javascript" src="../js/template.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/swipe.js"></script>
<script type="text/javascript" src="../js/integral.js"></script>
<script type="text/javascript" src="../js/tmpl/footer.js"></script>
<script>
    $(function(){
        $(document).on('click','.myScore',function(){
            var key = getCookie('key');
            if (!key) {
                window.location.href = WapSiteUrl + '/tmpl/member/login.html';
                return false;
            }
            else
            {
                window.location.href = WapSiteUrl + '/tmpl/member/signin.html';
            }
        })
    })
</script>
</body>
</html>
<?php 
include __DIR__.'/../includes/footer.php';
?>
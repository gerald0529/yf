
<!DOCTYPE html>
<html>
<?php 
include __DIR__.'/../includes/header.php';
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>商城触屏版</title>
    <link rel="stylesheet" href="../css/base.css" />
    <link rel="stylesheet" href="../css/Group.css" />
    <link rel="stylesheet" href="../css/swiper.min.css" />
    <link rel="stylesheet" href="../css/nctouch_products_list.css" />
    <link rel="stylesheet" href="https://at.alicdn.com/t/font_562768_pwu4kym4n3o9a4i.css">
    <script src="../js/swiper.min.js"></script>
</head>

<body>
<header id="header" class="fixed">
    <div class="header-wrap">
        <div class="header-l">
            <a href="javascript:history.go(-1)"> <i class="back"></i> </a>
        </div>
        <div class="header-title">
            <h1>团购中心</h1>
        </div>
        <a href="javascript:;" class="righttubiao fr mr-20 mt4"><i class="iconfont icon-class fz-44 col2"></i></a>
    </div>
</header>

    <div class="integral-wap fixed-Width">
        <!-- <div id="Group-head" class="clearfix">
            <div class="righttubiao">
                <a class=""></a>
            </div>
        </div> -->
        <div class="Group-banenr">
            <div class="swiper-container  swiper-banner "></div>
            <div class="swiper-pagination"></div>
        </div>
        <div class="Group-main"></div>
        <div class="maskG pb-30 pt-30"></div>
        <footer id="footer" class="fixed-Width"></footer>
    </div>

    <script type="text/html" id="swiper-container-nav">
        <div class="swiper-wrapper">
            <div class="header-title">
                <h1>团购中心</h1>
            </div>
            <!--<div class="swiper-slide bor-2">全部</div>-->
            <!--<% if(cat.physical){ %>
            <% var items = cat.physical %>
            <% for( var i in items ){ %>
                <div class="swiper-slide" onclick="location.href='group_buy_list.html?groupbuy_type=1&groupbuy_cat_id=<%= items[i].groupbuy_cat_id %>'"><%= items[i].groupbuy_cat_name %></div>
            <% }} %>-->
        </div>
    </script>

    <script type="text/html" id="swiper-banner">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <a href="<%= banner.slider1.live_link1 %>">
                    <img src="<%= banner.slider1.slider1_image %>">
                </a>
            </div>
            <div class="swiper-slide">
                <a href="<%= banner.slider2.live_link2 %>">
                    <img src="<%= banner.slider2.slider2_image %>">
                </a>
            </div>
            <div class="swiper-slide">
                <a href="<%= banner.slider3.live_link3 %>">
                    <img src="<%= banner.slider3.slider3_image %>">
                </a>
            </div>
            <div class="swiper-slide">
                <a href="<%= banner.slider4.live_link4 %>">
                    <img src="<%= banner.slider4.slider4_image %>">
                </a>
            </div>
        </div>
        <!-- <div class="swiper-pagination"></div> -->
    </script>

    <script type="text/html" id="maskG">
        <div class="title mb-20">
            <a href="group_buy_list.html?groupbuy_type=1" class="fz-30 col2">线上团</a>
        </div>
        <ul class="list clearfix">
            <% if(cat.physical){ %>
            <% var items = cat.physical %>
            <% for( var i in items ){ %>
            <li><a href="group_buy_list.html?groupbuy_type=1&groupbuy_cat_id=<%= items[i].groupbuy_cat_id %>"><%= items[i].groupbuy_cat_name %></a></li>
            <% }} %>
        </ul>
        <div class="title mb-20">
            <a href="group_buy_list.html?groupbuy_type=2" class="fz-30 col2">虚拟团</a>
        </div>
        <ul class="list clearfix">
            <% if(cat.virtual){ %>
            <% var items = cat.virtual %>
            <% for( var i in items ){ %>
            <li><a href="group_buy_list.html?groupbuy_type=2&groupbuy_cat_id=<%= items[i].groupbuy_cat_id %>"><%= items[i].groupbuy_cat_name %></a></li>
            <% }} %>
        </ul>
        <div class="X righttubiao"></div>
    </script>

    <script type="text/html" id="Group-main">
        <h3 class="tit-style1">
            <a href="group_buy_list.html?groupbuy_type=1">线上团</a><i>每天整点开抢</i>
            <span class="more"> <a href="group_buy_list.html?groupbuy_type=1">更多<i class="iconfont icon-iconjiantouyou"></i></a></span>
        </h3>
        <% if(goods.physical.highly_recommend.groupbuy_id){ %>
        <div class="hot">
            <div class="hot-img">
                <a href="product_detail.html?goods_id=<%= goods.physical.highly_recommend.goods_id %>">
                    <img src="<%= goods.physical.highly_recommend.groupbuy_image_rec %>">
                </a>
            </div>
            <div class="content">
                <div class="hot-title"><%= goods.physical.highly_recommend.groupbuy_name %></div>
                <div class="hot-txt"><%= goods.physical.highly_recommend.groupbuy_remark %></div>
                <div class="num fr"><b><%= goods.physical.highly_recommend.groupbuy_virtual_quantity %></b>件已付款</div>
            </div>
            <div class="price clearfix">
               
                <div class="con clearfix">
                    <div class="left">
                        <div class="">
                            <b>￥</b><%= goods.physical.highly_recommend.groupbuy_price %> <del><%= goods.physical.highly_recommend.goods_price %></del>
                        </div>
                        <div class="time fnTimeCountDown" data-end="<%= goods.physical.highly_recommend.groupbuy_endtime %>">
                                <span>还剩
                                    <span class="day" >00</span><strong>天</strong>
                                    <span class="hour">00</span><strong>小时</strong>
                                    <span class="mini">00</span><strong>分</strong>
                                    <span class="sec" >00</span><strong>秒</strong>
                                </span>
                        </div>
                    </div>
                    <div class="right">
                        <a href="javascript:;" class="immediately" onclick="location.href='product_detail.html?goods_id=<%= goods.physical.highly_recommend.goods_id %>'">
                            立即抢购
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <% } %>
        <div class="list">
            <ul class="clearfix">
                <% if(goods.physical.recommend.items){ %>
                <% var items = goods.physical.recommend.items %>
                <% for( var i in items ){ %>
                <% if (i>=8) break; %>
                <li>
                    <div class="list-img" onclick="location.href='product_detail.html?goods_id=<%= items[i].goods_id %>'">
                        <img src="<%= items[i].groupbuy_image %>">
                        <div class="time fnTimeCountDown" data-end="<%= items[i].groupbuy_endtime %>">
                                <span class="ts">
                                    <span class="day" >00</span><strong>天</strong>
                                    <span class="hour">00</span><strong>小时</strong>
                                    <span class="mini">00</span><strong>分</strong>
                                    <span class="sec" >00</span><strong>秒</strong>
                                </span>
                        </div>
                    </div>

                    <div class="txt" onclick="location.href='product_detail.html?goods_id=<%= items[i].goods_id %>'"><%= items[i].groupbuy_name %></div>
                    <div class="content">
                        <!-- <div class="price clearfix"> -->
                            <!--原价-->
                           <!--  <b>￥</b><%= items[i].goods_price %>
                        </div> -->
                        <div class="price clearfix">
                            <b>￥</b><%= items[i].groupbuy_price %>
                        </div>
                        
                         <div class="right">已售<b><%= items[i].groupbuy_virtual_quantity %></b></div>
                        <div class="btn-inter goods-store">
                            <a href="product_detail.html?goods_id=<%= items[i].goods_id %>">
                                <% if(items[i].is_start == 0){ %>
                                    未开团
                                <% }else{ %>
                                    <% if(items[i].goods_stock <= 0){ %>
                                        已抢光
                                    <% }else{ %>
                                        我要团
                                    <% } %>
                                
                                <% } %>
                            
                            </a>
                        </div>
                    </div>

                </li>
                <% }} %>
            </ul>

        </div>
        <h3 class="tit-style2">
            <a href="group_buy_list.html?groupbuy_type=2">虚拟团</a><i>每天整点开抢</i>
            <span class="more"> <a href="group_buy_list.html?groupbuy_type=2">更多<i class="iconfont icon-iconjiantouyou"></i></a></span>
        </h3>
        <% if(goods.virtual.highly_recommend.groupbuy_id){ %>
        <div class="hot">
            <div class="hot-img">
                <a href="product_detail.html?goods_id=<%= goods.virtual.highly_recommend.goods_id %>">
                    <img src="<%= goods.virtual.highly_recommend.groupbuy_image_rec %>">
                </a>
            </div>
            <div class="content">
                <div class="hot-title"><%= goods.virtual.highly_recommend.groupbuy_name %></div>
                <div class="hot-txt"><%= goods.virtual.highly_recommend.groupbuy_remark %></div>
                <div class="num fr"><b><%= goods.virtual.highly_recommend.groupbuy_virtual_quantity %></b>件已付款</div>
            </div>
            <div class="price clearfix">
                
                <div class="con clearfix">
                    <div class="left">
                        <div>
                            <b>￥</b><%= goods.virtual.highly_recommend.groupbuy_price %> <del><%= goods.virtual.highly_recommend.goods_price %></del>
                        </div>
                        <div class="time fnTimeCountDown" data-end="<%= goods.virtual.highly_recommend.groupbuy_endtime %>">
                                <span><i class="iconfont icon-shijian2"></i>还剩
                                    <span class="day" >00</span><strong>天</strong>
                                    <span class="hour">00</span><strong>小时</strong>
                                    <span class="mini">00</span><strong>分</strong>
                                    <span class="sec" >00</span><strong>秒</strong>
                                </span>
                        </div>
                    </div>
                    <div class="right">
                        <div class="immediately" onclick="location.href='product_detail.html?goods_id=<%= goods.virtual.highly_recommend.goods_id %>'">
                            立即购买
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <% } %>
        <div class="list">
            <ul>
                <% if(goods.virtual.recommend.items){ %>
                <% var items = goods.virtual.recommend.items %>
                <% for( var i in items ){ %>
                <% if (i>=8) break; %>
                <li>
                    <div class="list-img" onclick="location.href='product_detail.html?goods_id=<%= items[i].goods_id %>'">
                        <img src="<%= items[i].groupbuy_image %>">
                        <div class="time fnTimeCountDown" data-end="<%= items[i].groupbuy_endtime %>">
                                <span class="ts">
                                    <span class="day" >00</span><strong>天</strong>
                                    <span class="hour">00</span><strong>小时</strong>
                                    <span class="mini">00</span><strong>分</strong>
                                    <span class="sec" >00</span><strong>秒</strong>
                                </span>
                        </div>
                    </div>

                    <div class="txt" onclick="location.href='product_detail.html?goods_id=<%= items[i].goods_id %>'"><%= items[i].groupbuy_name %></div>
                    <div class="content">
                        <!-- <div class="price clearfix"> -->
                            <!--原价-->
                           <!--  <b>￥</b><%= items[i].goods_price %>
                        </div> -->
                        <div class="price clearfix">
                            <b>￥</b><%= items[i].groupbuy_price %>
                        </div>
                        <div class="right">已售<b><%= items[i].groupbuy_virtual_quantity %></b></div>
                        <div class="btn-inter goods-store">
                            <a href="product_detail.html?goods_id=<%= items[i].goods_id %>">
                                <% if(items[i].is_start == 0){ %>
                                    未开团
                                <% }else{ %>
                                    <% if(items[i].goods_stock <= 0){ %>
                                        已抢光
                                    <% }else{ %>
                                        我要团
                                    <% } %>
                                
                                <% } %>
                            </a>
                        </div>
                    </div>

                </li>
                <% }} %>
            </ul>

        </div>
    </script>

    
    <script type="text/javascript" src="../js/zepto.min.js"></script>
    <script type="text/javascript" src="../js/template.js"></script>
    <script type="text/javascript" src="../js/common.js"></script>
    <script type="text/javascript" src="../js/group_buy.js"></script>
    <script type="text/javascript" src="../js/tmpl/footer.js"></script>
    <script type="text/javascript" src="../js/jquery.timeCountDown.js" ></script>
</body>

</html>
<?php 
include __DIR__.'/../includes/footer.php';
?>
<?php
    include __DIR__ . '/../includes/header.php';
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
        <title>店铺首页</title>
        <link rel="stylesheet" type="text/css" href="../css/base.css">
        <link rel="stylesheet" type="text/css" href="../css/nctouch_common.css">
        <link rel="stylesheet" type="text/css" href="../css/nctouch_store.css">
        <link rel="stylesheet" href="https://at.alicdn.com/t/font_562768_pwu4kym4n3o9a4i.css">
    </head>
    <body>
    <header id="header" class="nctouch-store-header fixed-Width">
        <div class="header-wrap">
            <div class="header-l">
                <a href="../index.html"><i class="back"></i></a></div>
            <a class="header-inp clearfix" id="goods_search" href="">
                <i class="icon"></i>
                <span class="search-input colbc">搜索店铺内商品</span>
            </a>
            <div class="header-r">
                <a id="header-nav" href="javascript:void(0);">
                    <i class="more"></i>
                    <sup></sup>
                </a>
            </div>
        </div>
        <div class="nctouch-nav-layout">
            <div class="nctouch-nav-menu"><span class="arrow"></span>
                <ul>
                    <li><a href="../index.html"><i class="home"></i>首页</a></li>
                    <li><a href="../tmpl/search.html"><i class="search"></i>搜索</a></li>
                    <li><a href="../tmpl/cart_list.html"><i class="cart"></i>购物车<sup></sup></a></li>
                    <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
                    <?php if ($_COOKIE['is_app_guest']) { ?>
                        <li><a href="" id="shareit_store"><i class="share"></i>分享<sup></sup></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </header>
    
    <div class="nctouch-main-layout fixed-Width mb25">
        <div id="store-wrapper" class="nctouch-store-con">
            <!-- banner -->
            <div class="nctouch-store-top" id="store_banner"></div>
            <!-- 导航条 -->
            <div id="nav_tab_con" class="nctouch-single-nav nctouch-store-nav">
                <ul id="nav_tab">
                    <li class="selected"><a href="javascript: void(0);" data-type="storeindex" id="storeindex" onclick="nav_click('storeindex')">店铺首页</a></li>
                    <li><a href="javascript: void(0);" data-type="allgoods" id="allgoods" onclick="nav_click('allgoods')">全部商品</a></li>
                    <li><a href="javascript: void(0);" data-type="newgoods" id="newgoods" onclick="nav_click('newgoods')">商品上新</a></li>
                    <li><a href="javascript: void(0);" data-type="storeactivity" id="storeactivity" onclick="nav_click('storeactivity')">店铺活动</a></li>
                </ul>
            </div>
            <!-- 首页s -->
            <div id="storeindex_con" style="position: relative; z-index: 1;">
                <!-- 轮播图 -->
                <div id="store_sliders" class="nctouch-store-wapper nctouch-store-sliders"></div>
                <!-- 新增代金券2017.7.20 -->
                <div class="store-vou bort1 borb1" id="voucher_list_div"></div>
                <!-- 店铺排行榜 -->
                <div class="nctouch-store-block nctouch-store-ranking">
                    <div class="title"><i class="icon icon-rank"></i><span>商品排行榜</span></div>
                    <div class="nctouch-single-nav">
                        <ul id="goods_rank_tab">
                            <li><a href="javascript: void(0);" data-type="collect">收藏排行</a></li>
                            <li><a href="javascript: void(0);" data-type="salenum">销量排行</a></li>
                        </ul>
                    </div>
                    <div class="top-list" nc_type="goodsranklist" id="goodsrank_collect"></div>
                    <div class="top-list" nc_type="goodsranklist" id="goodsrank_salenum"></div>
                </div>
                <!-- 店主推荐 -->
                <div class="nctouch-store-block">
                    <div class="title">
                        <i class="icon icon-intro"></i>
                        <span>店主推荐</span>
                    </div>
                    <div class="nctouch-store-goods-list" id="goods_recommend"></div>
                </div>
            </div>
            <!-- 首页e -->
            <!-- 全部宝贝 -->
            <div id="allgoods_con"></div>
            <!-- 商品上新 -->
            <div id="newgoods_con" class="nctouch-store-goods-list">
                <ul id="newgoods_next"></ul>
            </div>
            <!-- 店铺活动 -->
            <div id="storeactivity_con"></div>
        </div>
    </div>
    
    <div class="fix-block-r">
        <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>
    </div>
    
    <div id="store_voucher_con"></div>
    
    <div class="nctouch-store-bottom fixed-Width">
        <ul>
            <li><a id="store_intro" href="javascript:void(0);">店铺介绍</a></li>
            <li><a id="store_voucher" href="javascript: void(0);">免费领券</a></li>
            <li><a id="store_kefu" style="display: none;" class="kefu" href="javascript: void(0);">联系客服</a></li>
        </ul>
    </div>
    
    <!-- banner tpl -->
    <script type="text/html" id="store_banner_tpl">
        <div class="store-top-bg"><span class="img" nc_type="store_banner_img"></span></div>
        <div class="store-top-mask"></div>
        <%if(!store_info.store_avatar){store_info.store_avatar = '../images/default_store_image.png'}%>
        <div class="store-avatar"><img src="<%= store_info.store_avatar %>"/></div>
        <div class="store-name">
            <%= store_info.store_name %>
        </div>
        <div class="store-favorate">
            <a href="javascript:void(0);" id="store_collected" class="added fz-28"> <i class="iconfont icon-like default-color align-middle mr-10"></i><b class="default-color">已收藏</b> </a> <a href="javascript:void(0);" id="store_notcollect" class="fz-28"> <i class="iconfont icon-like align-middle mr-10"></i><b>收藏</b> </a> <span class="num">
                <input type="hidden" id="store_favornum_hide" value="<%= store_info.store_collect %>"/>
                <em id="store_favornum" class="fz-24"><%= store_info.store_collect %></em><b class="fz-24">人</b>
            </span>
        </div>
    </script>
    <!-- 轮播图 tpl -->
    <script type="text/html" id="store_sliders_tpl">
        <ul class="swipe-wrap">
            <% for (var i in store_info.mb_sliders) { var s = store_info.mb_sliders[i]; %> <% if(s.imgUrl){ %>
            <li class="item">
                <% if (s.link) { %> <a href="<%= s.link %>"><img alt="" src="<%= s.imgUrl %>"/></a> <% } else { %> <a href="javascript:void(0);"><img alt="" src="<%= s.imgUrl %>"/></a> <% } %>
            </li>
            <% } %> <% } %>
        </ul>
    </script>
    <!--代金券-->
    <script type="text/html" id="voucher_list_tpl">
        <ul class="inline">
            <% for (var i in voucher_list) { %>
            <li>
                <a href="store_voucher_list.html?shop_id=<%=voucher_list[i].shop_id%>">
                    <div class="left"><span><i>￥</i><strong><%=voucher_list[i].voucher_t_price%></strong></span></div>
                    <div class="right"><span>满<%=voucher_list[i].voucher_t_limit%>元使用</span></div>
                </a>
            </li>
            <% } %>
        </ul>
        <p class="fr"><a href="store_voucher_list.html?shop_id=<%=voucher_list[i].shop_id%>"><span>更多</span><i class="icon-arrow"></i></a></p>
    
    </script>
    <!-- 店铺排行榜_收藏排行 tpl -->
    <script type="text/html" id="goodsrank_collect_tpl">
        <% var goods_list = items; %>
        <% for (var i in goods_list) { var v = goods_list[i]; %>
        <dl class="goods-item">
            <a href="product_detail.html?goods_id=<%= v.goods_id[0].goods_id %>">
                <dt><img alt="<%= v.common_name %>" src="<%= v.common_image %>"/></dt>
                <dd><span>收藏<em><%= v.common_collect %></em></span><span>￥<em><%= v.common_price %></em></span></dd>
            </a>
        </dl>
        <% } %>
    </script>
    <!-- 店铺排行榜_销量排行 tpl -->
    <script type="text/html" id="goodsrank_salenum_tpl">
        <% var goods_list = items; %>
        <% for (var i in goods_list) { var v = goods_list[i]; %>
        <dl class="goods-item">
            <a href="product_detail.html?goods_id=<%= v.goods_id[0].goods_id %>">
                <dt><img alt="<%= v.common_name %>" src="<%= v.common_image %>"/></dt>
                <dd><span>已售<em><%= v.common_salenum %></em></span><span>￥<em><%= v.common_price %></em></span></dd>
            </a>
        </dl>
        <% } %>
    </script>
    <!-- 店主推荐 tpl -->
    <script type="text/html" id="goods_recommend_tpl">
        <ul>
            <% for (var i in rec_goods_list) { var g = rec_goods_list[i]; %>
            <li class="goods-item">
                <a href="product_detail.html?goods_id=<%= g.goods_id %>">
                    <div class="goods-item-pic">
                        <img alt="" src="<%= g.common_image %>"/>
                    </div>
                    <div class="goods-item-name more-overflow">
                        <%= g.common_name %>
                    </div>
                    <div class="goods-item-price">￥<em><%= g.common_price %></em></div>
                </a>
            </li>
            <% } %>
        </ul>
    </script>
    <!-- 商品上新 tpl -->
    <script type="text/html" id="newgoods_tpl">
        <% var goods_list = items; %>
        <% if(goods_list.length >0){%>
        <% for (var i in goods_list) {
                var v = goods_list[i];
        %>
        <% if(v.goods_addtime_text_show){ %>
        <li class="addtime" addtimetext='<%=v.common_add_time %>'>
            <time><%= v.goods_addtime_text_show %></time>
        </li>
        <% } %>
        <li class="goods-item">
            <a href="product_detail.html?goods_id=<%= v.goods_id[0].goods_id %>">
                <div class="goods-item-pic">
                    <img alt="" src="<%= v.common_image %>"/>
                </div>
                <div class="goods-item-name more-overflow">
                    <%= v.common_name %>
                </div>
                <div class="goods-item-price">￥<em><%= v.common_price %></em></div>
            </a>
        </li>
        <% } %>
        <li class="loading">
            <div class="spinner"><i></i></div>
            商品数据读取中...
        </li>
        <% } else { %>
        <div class="nctouch-norecord search">
            <div class="norecord-ico"><i></i></div>
            <dl>
                <dt>商铺最近没有新品上架</dt>
                <dd>收藏店铺经常来逛一逛</dd>
            </dl>
        </div>
        <% } %>
    </script>
    <!-- 店铺活动 tpl -->
    <script type="text/html" id="storeactivity_tpl">
        <% if(promotion.count){ %>
        <% if(promotion.mansong){ for(var k=0; k < promotion.mansong.length; k++){ var mansong = promotion.mansong[k];if(mansong.shop_id != 0){ %>
        <div class="store-sale-block">
            <a href="store_goods.html?shop_id=<%=shop_id %>">
                <div class="store-sale-tit">
                    <h3><%=mansong.mansong_start_time %></h3>
                    <time>活动时间： <%=mansong.start_time_text%> 至 <%=mansong.mansong_end_time%>
                    </time>
                </div>
                <div class="sotre-sale-con">
                    <ul class="mjs">
                        <% for (var i in mansong.rule) { var rules = mansong.rule[i]; %>
                        <li>单笔订单消费满<em>¥<%=rules.rule_price %></em> <% if(rules.rule_discount) { %>，立减现金<em>¥<%=rules.rule_discount %></em> <% } %> <% if(rules.goods_id > 0) { %>， 还可获赠品<img src="<%=rules.goods_image %>" alt="<%=rules.goods_name %>">&nbsp;。 <% } %>
                        </li>
                        <% } %>
                    </ul>
                    <% if(mansong.mansong_remark){ %>
                    <p class="note">活动说明： <%=mansong.mansong_remark %>
                    </p>
                    <% } %>
                </div>
            </a>
        </div>
        <% }}} %>
        
        <% if(promotion.xianshi){ for(var k=0; k < promotion.xianshi.length; k++){var xianshi = promotion.xianshi[k]; if(xianshi.shop_id != 0){%>
        <div class="store-sale-block">
            <a href="store_goods.html?shop_id=<%=shop_id %>">
                <div class="store-sale-tit">
                    <h3><%=xianshi.discount_name %></h3>
                    <time>活动时间： <%=xianshi.discount_start_time%> 至 <%=xianshi.discount_end_time%>
                    </time>
                </div>
                <div class="sotre-sale-con">
                    <ul class="xs">
                        <li>单件活动商品满<em><%=xianshi.discount_lower_limit %></em>件即可享受折扣价。</li>
                    </ul>
                    <% if(xianshi.discount_explain){ %>
                    <p class="note">活动说明： <%=xianshi.discount_explain %>
                    </p>
                    <% } %>
            </a>
        </div>
        <% }}} %>
        
        <% }else{ %>
        <div class="nctouch-norecord search">
            <div class="norecord-ico"><i></i></div>
            <dl>
                <dt>商铺最近没有促销活动</dt>
                <dd>收藏店铺经常来逛一逛</dd>
            </dl>
        </div>
        <% } %>
    </script>
    <script type="text/html" id="store_voucher_con_tpl">
        <div class="nctouch-bottom-mask">
            <div class="nctouch-bottom-mask-bg"></div>
            <div class="nctouch-bottom-mask-block">
                <div class="nctouch-bottom-mask-tip"><i></i>点击此处返回</div>
                <div class="nctouch-bottom-mask-top store-voucher">
                    <i class="icon-store"></i>领取店铺代金券<a href="javascript:void(0);" class="nctouch-bottom-mask-close"><i></i></a>
                </div>
                <div class="nctouch-bottom-mask-rolling">
                    <div class="nctouch-bottom-mask-con">
                        <ul class="nctouch-voucher-list">
                            <% var voucher_list = voucher.items %> <% if(voucher_list.length > 0){ %> <% for (var i=0; i < voucher_list.length; i++) { var v = voucher_list[i]; %>
                            <li>
                                <dl>
                                    <dt class="money">面额<em><%=v.voucher_t_price %></em>元</dt>
                                    <dd class="need">需消费 <%=v.voucher_t_limit %>元使用</dd>
                                    <dd class="time">至 <%=v.voucher_t_end_date %>前使用</dd>
                                </dl>
                                <a href="javascript:void(0);" nc_type="getvoucher" class="btn" data-tid="<%=v.voucher_t_id%>">领取</a>
                            </li>
                            <% } %> <% }else{ %>
                            <div class="nctouch-norecord voucher" style="position: relative; margin: 3rem auto; top: auto; left: auto; text-align: center;">
                                <div class="norecord-ico"><i></i></div>
                                <dl style="margin: 1rem 0 0;">
                                    <dt style="color: #333;">暂无代金券可以领取</dt>
                                    <dd>店铺代金券可享受商品折扣</dd>
                                </dl>
                            </div>
                            <% } %>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </script>
    <script type="text/javascript" src="../js/zepto.min.js"></script>
    <script type="text/javascript" src="../js/template.js"></script>
    <script type="text/javascript" src="../js/swipe.js"></script>
    <script type="text/javascript" src="../js/common.js"></script>
    <script type="text/javascript" src="../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../js/zepto.waypoints.js"></script>
    <script type="text/javascript" src="../js/ncscroll-load.js"></script>
    <script type="text/javascript" src="../js/tmpl/store.js"></script>
    <script type="text/javascript" src="../js/tmpl/footer.js"></script>
    </body>
    
    </html>
<?php
    include __DIR__ . '/../includes/footer.php';
?>

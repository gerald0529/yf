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
        <meta name="sharecontent" data-msg-img="https://ss0.baidu.com/6ONWsjip0QIZ8tyhnq/it/u=2927678406,1546747626&fm=58"/>
        <title>商品详情</title>
        <link rel="stylesheet" type="text/css" href="../css/base.css">
        <link rel="stylesheet" type="text/css" href="../css/nctouch_common.css">
        <link rel="stylesheet" type="text/css" href="../css/nctouch_products_detail.css">
        <link rel="stylesheet" href="../css/iconfont.css">
        <link rel="stylesheet" href="../css/swiper.min.css">
        <link rel="stylesheet" href="https://at.alicdn.com/t/font_562768_sjz8pq8glqujif6r.css">
    </head>
    <style>
        .s-dialog-btn-cancel {
            border-left-width: 0px !important;
        }
    </style>
    <body>
    <header id="header" class="transparent">
        <div class="header-wrap">
            <div class="header-l"><a href="javascript:void(0)" onclick="backGoodsList()"> <i class="mine-back"></i> </a>
            </div>
            <ul class="header-nav">
                <li class="cur"><a href="javascript:void(0);">商品</a></li>
                <li><a href="javascript:void(0);" id="goodsBody">详情</a></li>
                <li><a href="javascript:void(0);" id="goodsEvaluation">评价</a></li>
                <li><a href="javascript:void(0);" id="goodsRecommendation">推荐</a></li>
            </ul>
            <div class="header-r"><a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a>
            </div>
        </div>
        <div class="nctouch-nav-layout">
            <div class="nctouch-nav-menu"><span class="arrow"></span>
                <ul>
                    <li><a href="../index.html"><i class="home"></i>首页</a></li>
                    <li><a href="../tmpl/search.html"><i class="search"></i>搜索</a></li>
                    <!--<li><a href="../tmpl/cart_list.html"><i class="cart"></i>购物车<sup></sup></a></li>-->
                    <li><a href="../tmpl/member/member.html"><i class="member"></i>我的商城</a></li>
                    <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
                    <?php if ($_COOKIE['is_app_guest']) { ?>
                        <li>
                            <a href="" id='shareit'> <i class="share"></i>分享<sup></sup> </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </header>
    <div id="product_detail_html" class="posr zIndex1"></div>
    <div id="product_detail_spec_html" class="nctouch-bottom-mask down"></div>
    <!-- 新增促销 2017.7.17 -->
    <div class="nctouch-bottom-mask down" id="sale-activity-html"></div>
    <!-- 代金券 -->
    <div id="voucher_html" class="nctouch-bottom-mask down"></div>
    <script src="../js/swiper.min.js"></script>
    <script type="text/html" id="product_detail">
        <div class="goods-detail-top">
            <div class="goods-detail-pic swiper-container" id="mySwipe">
                <ul class="swiper-wrapper">
                    <% for (var i =0;i < goods_image.length; i++){ %>
                    <li class="swiper-slide"><img src="<%=goods_image[i]%>"/></li>
                    <% } %>
                </ul>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <div class="goods-detail-cnt">
            <% if(goods_info.promotion_type == 'groupbuy' && goods_info.promotion_is_start == 1){ %>
            <!--团购样式 start-->
            <div class="goods-detail-price sale-pri">
                <h5 class="sale-tip">团购</h5>
                <span class="w40 pri">￥<em><%=goods_info.promotion_price%></em></span>
                <dl class="inline fz-24">
                    <dt>商品原价：</dt>
                    <dd>
                        <del>￥<%=goods_info.goods_price%></del>
                    </dd>
                    <dt class="block"><%=goods_info.groupbuy_virtual_quantity%>件已团购</dt>
                </dl>
                <% if(goods_info.promotion_is_start == 0){ %> <span class="sold"><i class="icon-time"></i>距离开始</span>
                <div class="time fnTimeCountDown" data-end="<%=goods_info.groupbuy_starttime%>">
                    <% }else{ %> <span class="sold"><i class="icon-time"></i>距离结束</span>
                    <div class="time fnTimeCountDown" data-end="<%=goods_info.groupbuy_endtime%>">
                        <% } %> <span>
                                    <span class="day">00</span><strong>天</strong>
                                    <span class="hour">00</span><strong>小时</strong>
                                    <span class="mini">00</span><strong>分</strong>
                                    <span class="sec">00</span><strong>秒</strong>
                                </span>
                    </div>
                </div>
            </div>
            <!--团购样式 end--><% }else if(goods_info.promotion_type == 'xianshi' && goods_info.promotion_is_start == 1){ %>
            <!--限时折扣 start-->
            <div class="goods-detail-price sale-pri">
                <h5 class="sale-tip">限时折扣</h5>
                <span class="w40 pri">￥<em><%=goods_info.promotion_price%></em></span>
                <dl class="inline">
                    <dt>商品原价：</dt>
                    <dd>
                        <del>￥<%=goods_info.common_market_price%></del>
                    </dd>
                    <dt class="block">限时折扣</dt>
                </dl>
                <% if(goods_info.promotion_is_start == 0){ %> <span class="sold"><i class="icon-time"></i>距离开始</span>
                <div class="time fnTimeCountDown" data-end="<%=goods_info.groupbuy_starttime%>">
                    <% }else{ %> <span class="sold"><i class="icon-time"></i>距离结束</span>
                    <div class="time fnTimeCountDown" data-end="<%=goods_info.groupbuy_endtime%>">
                        <% } %> <span>
                                    <span class="day">00</span><strong>天</strong>
                                    <span class="hour">00</span><strong>小时</strong>
                                    <span class="mini">00</span><strong>分</strong>
                                    <span class="sec">00</span><strong>秒</strong>
                                </span>
                    </div>
                </div>
            </div>
            <!--限时折扣 end--><% }else{ %> <% if (goods_info.promotion_type && goods_info.promotion_is_start == 1) { %>
            <div class="goods-detail-price">
                
                <dl>
                    <dt class="fz-30 col-red">￥<em><%=goods_info.promotion_price%></em>
                    </dt>
                    <dd>￥<%=goods_info.goods_price%></dd>
                </dl>
                <span class="sold" style="top: 2.5rem;">销量：<%=goods_info.common_salenum%>件</span>
            
            </div>
            <% } %> <% } %>
            <div class="bg-ff">
                <div class="goods-detail-name">
                    <dl>
                        <dt class="fz-28 z-dhwz"><%if(goods_info.common_is_virtual == '1'){%><span>虚拟</span><%}%><% if (goods_info.is_presell == '1') { %><span>预售</span><% } %><% if (goods_info.is_fcode == '1') { %><span>F码</span><% } %><%=goods_info.goods_name%>
                        </dt>
                        <% if(goods_info.goods_promotion_tips && goods_info.goods_promotion_tips.length > 50){ %>
                        <dd><%=goods_info.goods_promotion_tips.substring(0,50)%>...</dd>
                        <% } %>
                    </dl>
                </div>
                <% if (!goods_info.promotion_type || goods_info.promotion_is_start != 1) { %>
                <div class="goods-detail-price">
                    <dl>
                        <dt class="fz-30 col-red">￥<em><%=goods_info.goods_price%></em></dt>
                    </dl>
                    <dl class="fz-24">
                        <dd class="colbc">商品原价：
                        </dt>
                        <dd class="colbc">￥<%=goods_info.common_market_price%></dd>
                    </dl>
                    <span class="sold" style="top: 2.5rem;">销量：<%=goods_info.common_salenum%>件</span>
                </div>
                <% } %>
            </div>
            <% if (goods_info.promotion_type == 'xianshi' || (mansong_info != null && mansong_info.rule) || (gift_array && !isEmpty(gift_array)) || promotion_info) { %> <% if(promotion_info.length > 0){ %>
            <div class="goods-detail-item  bort1 fz-0" id="for-sale">
                <div class="itme-name tit-sale ">促销</div>
                <div class="item-con">
                    
                    <p class="fz6 lh100 ml-20"><% if (goods_info.promotion_type == 'xianshi') { %>限时折扣<% } %> <% if(promotion_info.jia_jia_gou) { %>加价购<% } if(promotion_info.jia_jia_gou && promotion_info.man_song){%>、<%} if (promotion_info.man_song) { %>满即送<% } %></p>
                    <div class="item-more"></div>
                </div>
            </div>
            <% } %> <% } %>
            <!-- 新增代金券2017.7.19 -->
            <div class="voucher-enter goods-detail-item  fz-0" id="getVoucher">
                <div class="itme-name tit-sale">代金券</div>
                <div class="item-con">
                    <p class="fz6 lh100 ml-20" id='voucher_list_text'>领取代金券</p>
                    <div class="item-more"></div>
                </div>
            </div>
            
            <div class="goods-detail-item">
                <div class="itme-name fz-30">送至</div>
                <div class="item-con">
                    <a href="javascript:void(0);" id="get_area_selected" data-common_id=<%=goods_info.common_id%> data-transport_type_id=<%=goods_info.transport_type_id%> >
                        <dl class="goods-detail-freight">
                            <dt><span id="get_area_selected_name"><%=goods_hair_info.area_name%></span> <strong id="get_area_selected_whether"><%=goods_hair_info.if_store_cn%></strong>&nbsp;
                                <span id="get_area_selected_content" style="display: none;">
                                            <%if(typeof(goods_hair_info.if_store)!='undefined' && typeof(goods_hair_info.transport_data)!='undefined' && typeof(goods_hair_info.transport_data.result)!='undefined') {%>
                                        <%= goods_hair_info.transport_data.transport_str; %>
                                        <% } %>
                                        </span>
                            </dt>
                            <!--<dd id="get_area_selected_content">
                                <%if(typeof(goods_hair_info.if_store)!='undefined' && typeof(goods_hair_info.transport_data)!='undefined' && typeof(goods_hair_info.transport_data.result)!='undefined') {%>
                                <%= goods_hair_info.transport_data.transport_str; %>
                                <% } %>
                            </dd>-->
                        </dl>
                    </a>
                </div>
                <div class="item-more location"></div>
            </div>
            <!--<div class="goods-detail-item goods-detail-o2o mt5 mb5">
                <div class="tit">
                    <h3>商家信息</h3>
                </div>
                <div class="default" id="goods-detail-o2o">
                </div>
                <div class="more-location"><a href="javascript:void(0);" id="store_addr_list"></a><i class="arrow-r"></i></div>
            </div>-->
            <div class="goods-detail-item " id="goods_spec_selected">
                <div class="itme-name fz-30 colbc">已选</div>
                <div class="item-con">
                    <dl class="goods-detail-sel">
                        <dt>
                            <% if (!isEmpty(goods_info.common_spec_name)) { %> <% if(goods_map_spec.length>0){%> <% for(var i =0;i
                            <goods_map_spec.length
                                ;i++){%>
                            <span>
							<%=goods_map_spec[i].goods_spec_name%>
							<%for(var j = 0;j<goods_map_spec[i].goods_spec_value.length;j++){%>
								<%if (goods_info.goods_spec[goods_map_spec[i].goods_spec_value[j].specs_value_id]){%>
									<em><%=goods_map_spec[i].goods_spec_value[j].specs_value_name%></em>
								<%}%>
							<%}%>
							</span> <%}%> <%}} else { %> <span>默认</span> <% } %>
                        </dt>
                    </dl>
                </div>
                <div class="item-more"></div>
            </div>
            <% if (!isEmpty(goods_info.contractlist)) { %>
            <div class="goods-detail-item">
                <div class="itme-name">服务</div>
                <div class="item-con">
                    <dl class="goods-detail-contract">
                        <dt>由“<%= store_info.store_name %>”销售和发货，并享受售后服务</dt>
                        <dd>
                            <% for (var k in goods_info.contractlist) { var v = goods_info.contractlist[k]; %> <span><i><img src="<%=v.cti_icon_url_60%>"></i><%=v.cti_name%></span> <% } %>
                        </dd>
                    </dl>
                </div>
            </div>
            <% } %>
            
            <!-- 客户-商品组合begin -->
            <!-- <div class="goods-detail-item clearfix">
                 <div class="fl fz-0">
                        <i class="iconfont icon-combination mr-10 fz-50 align-middle colbc"></i>
                        <span class="itme-name middle ml-20 fz6">商品组合</span>
                    </div>
                    <div class="fr">
                        <span class="itme-name align-top fz6">共5组</span>
                        <i class="iconfont icon-arrow-right fz6 col9 iblock align-top md-icon-lh"></i>
                    </div>
            </div>
            <div class="combination-module bgf borb1">
                <h3 class="fz-28 c-666">组合1</h3>
                <ul class="tc">
                    <li><div><img src="" alt=""></div><span class="block">A21以存线上品</span></li>
                    <i class="iconfont icon-add"></i>
                    <li><div><img src="" alt=""></div><span class="block">A21以存线上品</span></li>
                </ul>
            </div> -->
            <!-- 客户-商品组合end -->
            
            <!-- 门店自提 -->
            <div id="ziti" class="goods-detail-item ziti-area clearfix">
                <div class="clearfix">
                    <div class="fl fz-0">
                        <i class="icon middle"></i> <span class="itme-name middle ml-20 fz6">门店服务</span>
                    </div>
                    <div class="fr">
                        <span class="itme-name align-top fz6">门店自提</span> <i class="iconfont icon-arrow-right fz6 col9 iblock align-top md-icon-lh"></i>
                    </div>
                </div>
            
            </div>
            
            <section id="s-rate" data-spm=""></section>
            
            <% if (store_info.shop_self_support != "true") {%>
            <div class="goods-detail-store bort1 borb1">
                <a href="store.html?shop_id=<%= store_info.store_id %>">
                    <div class="store-name"><i class="icon-store"></i><%= store_info.store_name %></div>
                    <div class="store-rate">
				<span class="<%= store_info.store_credit.store_desccredit.percent_class %>">
                    <b class="icon1"></b>
                    <strong>描述相符</strong>
                    <em><%= store_info.store_credit.store_desccredit.credit %></em>
                    <i><%= store_info.store_credit.store_desccredit.percent_text %></i>
                </span> <span class="<%= store_info.store_credit.store_servicecredit.percent_class %>">
                    <b class="icon2"></b>
                    <strong>服务态度</strong>
                    <em><%= store_info.store_credit.store_servicecredit.credit %></em>
                    <i><%= store_info.store_credit.store_servicecredit.percent_text %></i>
                </span> <span class="<%= store_info.store_credit.store_deliverycredit.percent_class %>">
                    <b class="icon3"></b>
                    <strong>发货速度</strong>
                    <em><%= store_info.store_credit.store_deliverycredit.credit %></em>
                    <i><%= store_info.store_credit.store_deliverycredit.percent_text %></i>
                </span>
                    </div>
                    <div class="item-more top-6"></div>
                </a>
            </div>
            <% } %>
            
            
            <div class="goods-detail-bottom"><a href="javascript:void(0);" id="goodsBody1">点击查看商品详情</a></div>
            <div class="goods-detail-foot">
                <div class="otreh-handle bgf">
                    <a style="display: none;" href="javascript:void(0);" class="kefu wp30"><i></i>
                        <p>客服</p></a> <a href="javascript:void(0);"
                                         class="borl1 wp30 collect pd-collect <% if (is_favorate) { %>favorate<% } %>"><i></i>
                        <p>收藏</p></a> <a href="../tmpl/cart_list.html" class="cart"><i></i>
                        <p>购物车</p><span id="cart_count"></span></a>
                </div>
                <div class="buy-handle <%if(!goods_hair_info.if_store || goods_info.goods_storage == 0){%>no-buy<%}%>">
                    <% if (goods_info.cart == '1') { %> <a href="javascript:void(0);" class="<%if(goods_hair_info.if_store){%>animation-up<%}%> add-cart">加入购物车</a> <% } %> <a href="javascript:void(0);"
                                                                                                                                                                               class="<%if(goods_hair_info.if_store){%>animation-up<%}%> buy-now <%if(goods_info.cart != '1'){%>wp100<%}%>">立即购买</a>
                </div>
            </div>
    </script>
    <script type="text/html" id="product_detail_sepc">
        <div class="nctouch-bottom-mask-bg"></div>
        <div class="nctouch-bottom-mask-block">
            <!--<div class="nctouch-bottom-mask-tip"><i></i>点击此处返回</div>-->
            <div class="nctouch-bottom-mask-top goods-options-info">
                <div class="goods-pic">
                    <img src="<%=goods_image[0]%>"/>
                </div>
                <dl>
                    <dt class="fz-28 col2"><%= goods_info.goods_name; %></dt>
                    <dd class="goods-price price-color fz-28">
                        <% if (goods_info.promotion_type && goods_info.promotion_is_start == 1 ) { var promo; switch (goods_info.promotion_type) { case 'groupbuy': promo = '团购'; break; case 'xianshi': promo = '限时折扣'; break; } %> ￥<em><%=goods_info.promotion_price%></em> <span class="activity">
                        <% if (promo) { %>
                            <%= promo %>
                        <% } %>
                        </span> <% } else { %> ￥<em><%=goods_info.goods_price%></em> <% } %>
                    </dd>
                    <span class="goods-storage">库存：<%=goods_info.goods_stock%>件</span>
                </dl>
                <a href="javascript:void(0);" class="nctouch-bottom-mask-close"><i></i></a>
            </div>
            <div class="nctouch-bottom-mask-rolling go overflow-auto" id="product_roll">
                <div class="goods-options-stock bort1">
                    <% if(goods_map_spec.length>0){%> <% for(var i =0;i < goods_map_spec.length; i++){%>
                    <dl class="spec JS-goods-specs">
                        <dt spec_id="<%=goods_map_spec[i].id%>">
                            <%=goods_map_spec[i].goods_spec_name%>：
                        </dt>
                        <dd>
                            <%for(var j = 0;j < goods_map_spec[i].goods_spec_value.length;j++){%> <a href="javascript:void(0);" <% if(goods_info.goods_spec[goods_map_spec[i].goods_spec_value[j].specs_value_id]){ %> class="current" <% } %> specs_value_id = "<%=goods_map_spec[i].goods_spec_value[j].specs_value_id%>"> <%=goods_map_spec[i].goods_spec_value[j].specs_value_name%></a><%}%>
                        </dd>
                    </dl>
                    <%}%> <%}%> <% if (goods_info.is_virtual == '1') { %>
                    <dl class="spec-promotion">
                        <dt>提货方式：</dt>
                        <dd><a href="javascript:void(0);" class="current">电子兑换券</a></dd>
                    </dl>
                    <dl class="spec-promotion">
                        <dt>有效期：</dt>
                        <dd>
                            <a href="javascript:void(0);" class="current"> 即日起 到 <%= goods_info.virtual_indate_str %> </a> <% if (goods_info.buyLimitation && goods_info.buyLimitation > 0) { %> （每人次限购 <%= goods_info.buyLimitation %> 件） <% } %>
                        </dd>
                    </dl>
                    <% } else { %> <% if (goods_info.is_presell == '1') { %>
                    <dl class="spec-promotion">
                        <dt>预售：</dt>
                        <dd>
                            <a href="javascript:void(0);" class="current"> <%= goods_info.presell_deliverdate_str %>日发货 </a>
                        </dd>
                    </dl>
                    <% } %> <% if (goods_info.is_fcode == '1') { %>
                    <dl class="spec-promotion">
                        <dt>购买类型：</dt>
                        <dd>
                            <a href="javascript:void(0);" class="current">F码优先购买</a> （每个F码优先购买一件商品）
                        </dd>
                    </dl>
                    <% } %> <% } %>
                </div>
            </div>
            <div class="goods-option-value clearfix fz-28 colbc">
                购买数量
                <div class="value-box">
                    <span class="minus">
                        <a href="javascript:void(0);">&nbsp;</a>
                    </span> <span>
             <% if(buyer_limit != 0) {
                        if(buyer_limit >= goods_info.goods_stock){
                            data_max = goods_info.goods_stock;
                        }else{
                            data_max = buyer_limit;
                        }
            } else {
                data_max = goods_info.goods_stock;
            }
            if(goods_info.lower_limit > 1 && goods_info.promotion_is_start == 1 )
            {
                data_min = goods_info.lower_limit;
                promotion = 1;
            }
            else
            {
                data_min = 1;
                promotion = 0;
            }
            %>
            <input type="text" pattern="[0-9]*" class="buy-num" promotion="<%=promotion%>" data-max="<%=data_max%>"
                   data-min="<%=data_min%>" id="buynum" value="<%=data_min%>"/>
        </span> <span class="add">
            <a href="javascript:void(0);">&nbsp;</a>
        </span> <% if(buyer_limit != 0) { %>
                    <div style="font-size: 0.5rem;text-align: center;">限购<%= buyer_limit; %>件</div>
                    <% } %>
                </div>
            </div>
            <div class="goods-option-foot">
                <!--<div class="otreh-handle">
                    <a href="javascript:void(0);" class="kefu">
                        <i></i>
                        <p>客服</p>
                    </a>
                    <a href="../tmpl/cart_list.html" class="cart">
                        <i></i>
                        <p>购物车</p>
                        <span id="cart_count1"></span>
                    </a>
                </div>-->
                <div class="only-two-handle buy-handle <%if(!goods_hair_info.if_store || goods_info.goods_storage == 0){%>no-buy<%}%>">
                    <% if (goods_info.cart == '1') { %> <a href="javascript:void(0);" class="add-cart" id="add-cart">加入购物车</a> <% } %> <a href="javascript:void(0);"
                                                                                                                                          class=" fl buy-now <%if(goods_info.cart != '1'){%>wp100<%}%>" id="buy-now">确定</a>
                </div>
            </div>
    </script>
    <!-- 新增促销 -->
    <script type="text/html" id="sale-activity">
        <div class="nctouch-bottom-mask-bg"></div>
        <div class="nctouch-bottom-mask-block">
            <% if (promotion_info.jia_jia_gou || promotion_info.man_song) { %>
            <div class="goods-detail-item drap-ar">
                <!-- 加价购 --><% if (promotion_info.jia_jia_gou) { %>
                <div class="item-con">
                    <div class="tit-sale mb-20">加价购</div>
                    <dl class="goods-detail-sale v-top wp100">
                        <% for(var i = 0; i < promotion_info.jia_jia_gou.rule.length; i++) { %> <% var rule_price = promotion_info.jia_jia_gou.rule[i].rule_price; var rule_goods_limit = promotion_info.jia_jia_gou.rule[i].rule_goods_limit; var redemption_goods = promotion_info.jia_jia_gou.rule[i].redemption_goods; %>
                        <dt>购物满<b class="col-red">￥<%= rule_price; %></b>即可加价换购最多<%= rule_goods_limit; %>样以下商品</dt>
                        <dd>
                            <% for (var m = 0; m < redemption_goods.length; m++) { %>
                            <div>
                                <a href="<%= WapSiteUrl; %>/tmpl/product_detail.html?goods_id=<%= redemption_goods[m].goods_id; %>"><%= redemption_goods[m].goods_name %></a></div>
                            <% } %>
                        <dd>
                            <% } %>
                    </dl>
                </div>
                <% } %>
                <!-- 加价购 -->
                
                <!-- 满送 --><% if (promotion_info.man_song) { %>
                <div class="item-con">
                    <div class="tit-sale mb-20">满即送</div>
                    <dl class="goods-detail-sale v-top wp100">
                        <% for(var i = 0; i < promotion_info.man_song.rule.length; i++) { %> <% var rule_price = promotion_info.man_song.rule[i].rule_price; var rule_discount = promotion_info.man_song.rule[i].rule_discount; %>
                        <dt>购物满<b class="col-red">￥<%= rule_price; %></b>， 即享<%= rule_discount; %>元优惠</dt>
                        <% } %>
                    </dl>
                </div>
                <% } %>
                <!-- 满送 -->
                <!-- 限时折扣 -->
                <!--                    <% if (promotion_info.xian_shi) { %>
                                        <div class="item-con">
                                                <div>限时折扣</div>
                                                <dl class="goods-detail-sale">
                                                    <dt>直降<%= promotion_info.xian_shi.goods_price - promotion_info.xian_shi.discount_price %>，最低购<%= promotion_info.xian_shi.goods_lower_limit %>件</dt>
                                                </dl>
                                        </div>
                                    <% } %>-->
                <!-- 限时折扣 -->
            
            </div>
            <% } %>
            <p class="new-btn close-btn absolute"><a href="javascript:" class="btns">关闭</a></p>
        </div>
    </script>
    <!-- 代金券 -->
    <script type="text/html" id="voucher_script">
        <!--  <% if (voucher) { %>
         <div class="nctouch-bottom-mask-bg"></div>
         <div class="nctouch-bottom-mask-block">
             <div class="nctouch-bottom-mask-tip"><i></i>点击此处返回</div>
             <div class="nctouch-bottom-mask-top store-voucher">
                 <i class="icon-store"></i>
                 <%=store_info.store_name%>&nbsp;&nbsp;领取店铺代金券
                 <a href="javascript:void(0);" class="nctouch-bottom-mask-close"><i></i></a>
             </div>
             <div class="nctouch-bottom-mask-rolling" id="voucher_roll">
                 <div class="nctouch-bottom-mask-con">
                     <ul class="nctouch-voucher-list">
                         <% for (var i=0; i<voucher.length; i++) { %>
                         <li>
                             <dl>
                                 <dt class="money">面额<em><%=voucher[i].voucher_t_price%></em>元</dt>
                                 <dd class="need">需消费<%=voucher[i].voucher_t_limit%>使用</dd>
                                 <dd class="time">至<%=voucher[i].voucher_t_end_date%>前使用</dd>
                                 <dl>
                                     <a href="javascript:void(0);" class="btn" data-tid=<%=voucher[i].voucher_t_id%>>领取</a>
                         </li>
                         <% } %>
                     </ul>
                 </div>
             </div>
         </div>
         <% } %> -->
        <div class="nctouch-bottom-mask-bg"></div>
        <div class="nctouch-bottom-mask-block vou-area">
            <h3 class="tc">代金券</h3>
            <ul class="vou-lists">
                <% if(voucher_list.length > 0){ for(var i=0; i < voucher_list.length; i ++){ %>
                
                <li>
                    <div class="left tc hp100">
                        <div class="flex-middle hp100">
                            <div class="fz0">
                                <p>
                                    <i>￥</i> <span><%=voucher_list[i].voucher_t_price%></span>
                                </p>
                                <%if(voucher_list[i].voucher_t_points > 0){%> <em>需花费<%=voucher_list[i].voucher_t_points%>积分</em> <% } %>
                            </div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="rgl">
                            <h4 class="one-overflow">店铺优惠券</h4>
                            <span>购满<%=voucher_list[i].voucher_t_limit%>元使用</span>
                            <time><%=voucher_list[i].voucher_t_end_date_day%>前有效</time>
                        </div>
                        <div class="rgr">
                            <% if(voucher_list[i].is_get == 1){%>
                            <a href="javascript:" class="had">已经<br>领取</a>
                            <%}else{%>
                            <a onclick="confrimVoucher(<%=voucher_list[i].voucher_t_id %>, <%= voucher_list[i].voucher_t_points %>, <%= voucher_list[i].voucher_t_price %>)">
                                立即
                                <br>
                                领取
                            </a>
                            <%}%>
                        </div>
                    </div>
                </li>
                <%}}%>
            
            </ul>
            <p class="new-btn close-btn absolute"><a href="javascript:" class="btns">关闭</a></p>
        </div>
    
    </script>
    <script type="text/html" id="list-address-script">
        <% for (var i=0;i
        <addr_list.length;i++) {%>
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
    <script type="text/javascript" src="../js/zepto.min.js"></script>
    <script type="text/javascript" src="../js/template.js"></script>
    <script type="text/javascript" src="../js/swipe.js"></script>
    <script type="text/javascript" src="../js/common.js"></script>
    <script type="text/javascript" src="../js/zepto.cookie.js"></script>
    <script type="text/javascript" src="../js/iscroll.js"></script>
    <script type="text/javascript" src="../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../js/tmpl/footer.js"></script>
    <script type="text/javascript" src="../js/fly/requestAnimationFrame.js"></script>
    <script type="text/javascript" src="../js/fly/zepto.fly.min.js"></script>
    <script type="text/javascript" src="../js/product_detail.js"></script>
    <script type="text/javascript" src="../js/jquery.timeCountDown.js"></script>
    <script type="text/javascript" src="../js/tmpl/store_voucher_list.js"></script>
    <script>
        $(function () {
            //商品属性面板折叠
            function isHidden(oDiv) {
                var vDiv = document.getElementById(oDiv);
                var vBtn = document.getElementById("btn_attr");
                if (vDiv.style.display == "none") {
                    vBtn.innerHTML = "商品属性&nbsp;︽";
                } else {
                    vBtn.innerHTML = "商品属性&nbsp;︾";
                }
                vDiv.style.display = (vDiv.style.display == "none") ? "block" : "none";
            }
        });
    </script>
    <!--o2o分店地址Begin-->
    <div id="list-address-wrapper" class="nctouch-full-mask hide">
        <div class="nctouch-full-mask-bg"></div>
        <div class="nctouch-full-mask-block ">
            <div class="header">
                <div class="header-wrap">
                    <div class="header-l"><a href="javascript:void(0);"> <i class="mine-back"></i> </a></div>
                    <div class="header-title">
                        <h1>商家信息</h1>
                    </div>
                </div>
            </div>
            <div class="nctouch-main-layt">
                <div class="nctouch-o2o-tip"><a href="javascript:void(0);"
                                                id="map_all"><i></i>全部实体分店共<em></em>家<span></span></a></div>
                <div class="nctouch-main-layout-a" id="list-address-scroll">
                    <ul class="nctouch-o2o-list" id="list-address-ul">
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--o2o分店地址End-->
    <!--o2o分店地图Begin-->
    <div id="map-wrappers" class="nctouch-full-mask hide">
        <div class="nctouch-full-mask-bg"></div>
        <div class="nctouch-full-mask-block ">
            <div class="header transparent">
                <div class="header-wrap">
                    <div class="header-l"><a href="javascript:void(0);"> <i class="mine-back"></i> </a></div>
                </div>
            </div>
            <div class="nctouch-map-layout">
                <div id="baidu_map" class="nctouch-map"></div>
            </div>
        </div>
    </div>
    
    <script type="text/html" id="goodsReview">
        <p class="evals bgf mt5 bort1 borb1"><span>商品评价<% if (evalcount > 0) { %>
                    (<%= evalcount %>)
                    <% } else { %>
                (0)
                <% } %></span></p>
        <% if (evalcount > 0) { %>
        <div id="mui-tagscloud-i" class="mui-tagscloud borb1 pb-0">
            <div class="mui-tagscloud-main">
                <% if (goods_review_rows.length > 0) { %> <% for(var i = 0; i < goods_review_rows.length; i++) { %>
                <div class="mui-tagscloud-comments mar-0 pb-30">
                    <div class="mui-tagscloud-user clearfix">
                        <img class="mui-tagscloud-img fl" src="<%= goods_review_rows[i].user_logo %>">
                        <div class="fl">
                            <span class="mui-tagscloud-name"><%= goods_review_rows[i].user_name %></span>
                            <p class="levels">
                                <% for(var j = 0; j < goods_review_rows[i].scores; j++) { %> <i class="icon-star"></i> <% } %>
                            </p>
                        </div>
                        <li class="fr mt5 product-li-font"><%= goods_review_rows[i].create_time %></li>
                    </div>
                    <div class="mui-tagscloud-content"><%= goods_review_rows[i].content %></div>
                    <% if(goods_review_rows[i].image_row.length>0){ %> <% var image_row=goods_review_rows[i].image_row %>
                    <div class="goods_geval">
                        <% for(var j=0;j < image_row.length; j++ ){ %>
                            <a href="javascript:void(0);" data-start="<%=j%>">
                                <img class="goods_geval-img2" src="<%= image_row[j] %>"/>
                            </a>
                        <% } %>
                        <div class="nctouch-bigimg-layout hide">
                            <div class="close" style="margin-top:50px"></div>
                            <div class="pic-box">
                                <ul>
                                    <% for(var j=0; j < image_row.length; j++ ){ %>
                                    <li style="background-image: url(<%= image_row[j] %>)"></li>
                                    <% } %>
                                </ul>
                            </div>
                            <div class="nctouch-bigimg-turn">
                                <ul>
                                    <% for(var j=0;j < image_row.length; j++ ){ %>
                                    <li class="<% if(j == 0) { %> cur <% } %>"></li>
                                    <% } %>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <% } %>
                    <div class="mui-tagscloud-date"><%= goods_review_rows[i].goods_spec_str; %></div>
                </div>
                <% } %> <% } %>
            </div>
            <div class="mui-tagscloud-more">
                <% if (goods_review_rows.length > 0) { %>
                <button id="reviewLink">查看全部评价</button>
                <% } %>
            </div>
        </div>
        <% } %>
    </script>
    <!--o2o分店地图End-->
    </body>
    </html>
<?php
    include __DIR__ . '/../includes/footer.php';
?>

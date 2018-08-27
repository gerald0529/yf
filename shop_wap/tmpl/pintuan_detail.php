<!DOCTYPE html>
<html lang="en">
<?php
    include __DIR__ . '/../includes/header.php';
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>商品详情</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/swiper.min.css">
    <link rel="stylesheet" href="../css/fight-groups.css">
    <link rel="stylesheet" href="../css/nctouch_products_detail.css">
    <link rel="stylesheet" href="../css/nctouch_common.css">
    <script type="text/javascript" src="../js/zepto.min.js"></script>
    <script type="text/javascript" src="../js/swiper.min.js"></script>
</head>

<body>
<header id="header" class="fixed">
    <div class="header-wrap">
        <div class="header-l">
            <a href="javascript:history.go(-1)"> <i class="back"></i> </a>
        </div>
        <div class="header-title">
            <h1>商品详情</h1>
        </div>
        <div class="header-r">
            <a href="javascript:void(0);" onclick="show_mask('share_mask',1)"><i class="icon-share"></i></a>
        </div>
    </div>
</header>
<div class="nctouch-main-layout nctouch-main-layout-bottom">
    <ul class="pt_goods_previews">
        <li class="borb1 mt0">
            <div class="swiper-pt-banner swiper-container">
                <ul class="swiper-wrapper" id="pt_goods_image">
                    <li class="swiper-slide swiper-slide-active"><img class="default-img" src=""></li>
                </ul>
                <div class="swiper-pt-pagination"></div>
            </div>
            <div class="pt_goods_title">
                
                <strong class="common-red">[<label id="pt_person_num">0</label>人团]</strong>
                
                <p class="pt_goods_name">拼团活动</p>
            </div>
            <dl class="clearfix">
                <dt class="fl">
                    <p class="part1"><strong class="common-red">￥<label id="pt_price">0</label></strong><em class="common-red free-ship" id="pt_transport_fee">包邮</em><b>单买价：￥<label id="pt_price_one">0</label></b></p>
                    <p class="part2"><b class="through">原价：￥<label id="pt_price_ori">0</label></b></p>
                    <!-- <p class="part3"><em class="common-red free-ship" id="pt_transport_fee">包邮</em></p> -->
                </dt>
                <dd class="fr"><em class="pt_surplus">仅剩<label id="pt_goods_stock">0</label>件</em><em class="pt_surplus">已拼<label id="pt_buyer_num">0</label>件</em></dd>
            </dl>
        </li>
    </ul>
    <h4 class="pt_tit borb1 bort1 pt_tit_mrt"><a href="javascript:" id="pt_mark_url"><span>以下小伙伴发起的团购，你可直接参与</span><span id="more" style="float:right">更多<i class="icon fr" id="pt_mark_icon"></i></span></a></h4>
    <ul class="pt_det_lists" id='pt_mark_list'>
    
    </ul>
    <h4 class="pt_tit borb1 pt_tit_mrb"><a href="/tmpl/pintuan_rule.html"><span>支付成功即算参团，时间截止人数不足自动退款</span><i class="icon fr"></i></a></h4>
    <h4 class="pt_tit borb1 bort1" onclick="show_mask('goods_spec',1)" id="spec"><a href="javascript:"><span>已选择：<span id="pt_goods_spec"></span></span><i class="icon fr"></i></a></h4>
    
    
    <section id="pt_goods_review" data-spm=""></section>
    
    <div class="goods-detail-store">
        <a href="javascript:" id='pt_store_url'>
            <div class="store-name"><i class="icon-store"></i><label id='pt_store_name'></label></div>
            <input type="hidden" name="pt_member_id" id="pt_member_id">
            <div class="store-rate">
                    <span class="">
                    <b class="icon1"></b>
                    <strong>描述相符</strong>
                    <em id='store_grade_1'>5.00</em>
                    <i></i>
                </span>
                <span class="">
                    <b class="icon2"></b>
                    <strong>服务态度</strong>
                    <em id='store_grade_2'>5.00</em>
                    <i></i>
                </span>
                <span class="">
                    <b class="icon3"></b>
                    <strong>发货速度</strong>
                    <em id='store_grade_3'>5.00</em>
                    <i></i>
                </span>
            </div>
            <!--  <div class="item-more"><b>进店逛逛</b></div>-->
        </a>
    </div>
    <div class="pt_bottom_btn">
        <a href="javascript:" class="btn_style1 js-buy" data-type="alone"><span id='bt_price_one'>0.00</span><em>单独购买</em></a>
        <a href="javascript:" class="btn_style2 js-buy" data-type="pintuan"><span id='bt_price'>0.00</span><em>一键拼单</em></a>
    </div>
</div>
<!-- 上拉商品规格内容 -->
<div class="nctouch-bottom-mask down" id="goods_spec">
    <!-- 显示隐藏切换：class为 up down -->
    <div class="nctouch-bottom-mask-bg"></div>
    <div class="nctouch-bottom-mask-block">
        <div class="nctouch-bottom-mask-tip" onclick="show_mask('goods_spec',0)"><i></i>点击此处返回</div>
        <div class="nctouch-bottom-mask-top goods-options-info">
            <div class="goods-pic">
                <img src="" class="pt_goods_image">
            </div>
            <dl>
                <dt></dt>
                <dd class="goods-price">
                    <p class="pt_good_status pt_goods_name"></p>
                    <b style="color:#111">价格：</b><span id="price"></span><br />
                    <b style="color:#111">库存：</b><b id="goods_stock" style="color:#111"></b>
                </dd>
            </dl>
            <a href="javascript:void(0);" class="nctouch-bottom-mask-close" onclick="show_mask('goods_spec',0)"><i></i></a>
        </div>
        <div class="nctouch-bottom-mask-rolling mb20">
            <div class="goods-options-stock" id="goods_spec_html">
                <!--                    <dl class="spec JS-goods-specs ">
                                        <dt>颜色：</dt>
                                        <dd>
                                            <a href="javascript:void(0);" class="current">白色</a>
                                            <a href="javascript:void(0);">黑色 </a><a href="javascript:void(0);">灰色 </a>
                                        </dd>
                                    </dl>
                                    <dl class="spec JS-goods-specs">
                                        <dt>尺码：</dt>
                                        <dd>
                                            <a href="javascript:void(0);" class="current">XL</a>
                                            <a href="javascript:void(0);">L </a>
                                        </dd>
                                    </dl>-->
            </div>
        </div>
    </div>
</div>
<!-- 上拉分享 -->
<div class="nctouch-bottom-mask down" id="share_mask">
    <div class="nctouch-bottom-mask-bg"></div>
    <div class="nctouch-bottom-mask-block height-auto">
        
        <div class="share-area tc mb20">
            <ul>
                <li class="icon-weixin" id="cp_url_link" onclick="cpurl('share_mask')">
                    <a href="javascript:">
                        <span></span>
                        <h5>复制链接</h5>
                    </a>
                </li>
                <!--                    <li class="bdsharebuttonbox">
                                            <span><a href="#" class="bds_tsina" data-cmd="tsina"></a></span>
                                    </li>
                                    <li class="bdsharebuttonbox">
                                            <span><a href="#" class="bds_qzone" data-cmd="qzone"></a></span>
                                    </li>-->
            </ul>
        </div>
        <div class="goods-option-foot">
            <div class="">
                <a href="javascript:void(0);" class="pt_common cancel" onclick="show_mask('share_mask',0)" style="background:#ecdfdf">取消</a>
            
            </div>
        </div>
    </div>
</div>
</body>

<script type="text/javascript" src="../js/template.js"></script>
<script type="text/javascript" src="../js/swipe.js"></script>

<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/iscroll.js"></script>
<script type="text/javascript" src="../js/simple-plugin.js"></script>
<script type="text/javascript" src="../js/tmpl/footer.js"></script>
<script type="text/javascript" src="../js/fly/requestAnimationFrame.js"></script>
<script type="text/javascript" src="../js/fly/zepto.fly.min.js"></script>
<script type="text/javascript" src="../js/pintuan_detail.js"></script>
<script type="text/javascript" src="../js/jquery.timeCountDown.js"></script>
<script type="text/javascript" src="../js/clipboard.min.js"></script>

<script type="text/html" id="goodsReview">
    <p class="evals bgf mt5 bort1 borb1"><i class="icon"></i><span>商品评价
                    <% if (num > 0) { %>
                    (<%= num %>)
                    <% } %></span></p>
    <div id="mui-tagscloud-i" class="mui-tagscloud bort1">
        <div class="mui-tagscloud-main">
            <% if (goods_review_rows.length > 0) { %>
            <% for(var i = 0; i < goods_review_rows.length; i++) { %>
            <div class="mui-tagscloud-comments">
                <div class="mui-tagscloud-user clearfix">
                    <img class="mui-tagscloud-img fl" src="<%= goods_review_rows[i].user_logo %>">
                    <div class="fl">
                        <span class="mui-tagscloud-name"><%= goods_review_rows[i].user_name %></span>
                        <p class="levels">
                            <% for(var j = 0; j < goods_review_rows[i].scores; j++) { %>
                            <i class="icon-star"></i>
                            <% } %>
                        </p>
                    </div>
                </div>
                <div class="mui-tagscloud-content"><%= goods_review_rows[i].content %></div>
                <% if(goods_review_rows[i].image_row.length>0){ %>
                <% var image_row=goods_review_rows[i].image_row %>
                <div class="goods_geval">
                    <% for(var j=0;j
                    <image_row.length
                            ; j++ ){ %>
                        <a href="javascript:void(0);" data-start="<%=j%>"><img style="height:50px;width:50px" src="<%=image_row[j]%>" /></a>
                        <% } %>
                        <div class="nctouch-bigimg-layout hide">
                            <div class="close" style="margin-top:50px"></div>
                            <div class="pic-box">
                                <ul>
                                    <% for(var j=0;j
                                    <image_row.length
                                            ; j++ ){ %>
                                        <li style="background-image: url(<%=image_row[j]%>)"></li>
                                        <% } %>
                                
                                </ul>
                            </div>
                            <div class="nctouch-bigimg-turn">
                                <ul>
                                    <% for(var j=0;j
                                    <image_row.length
                                            ; j++ ){ %>
                                        
                                        <li class="<% if(j == 0) { %>cur<%}%>"></li>
                                        <% } %>
                                
                                </ul>
                            </div>
                        
                        </div>
                
                </div>
                <% } %>
                <div class="mui-tagscloud-date"><%= goods_review_rows[i].goods_spec_str; %></div>
            </div>
            <% } %>
            <% } %>
        </div>
        
        <div class="mui-tagscloud-more">
            <% if (goods_review_rows.length > 0) { %>
            <% if (num > 2) { %>
            <button id="reviewLink" onclick="more_review()">查看全部评价</button>
            <% } %>
            <% } else { %>
            暂无评价
            <% } %>
        </div>
    </div>
</script>


<?php
    include __DIR__ . '/../includes/footer.php';
?>
</html>
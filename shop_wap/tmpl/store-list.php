<?php
include __DIR__ . '/../includes/header.php';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <title>店铺列表</title>
    <link rel="stylesheet" href="../css/base.css"/>
    <link rel="stylesheet" href="../css/index.css"/>
    <link rel="stylesheet" href="../css/Group.css"/>
    <link rel="stylesheet" href="../css/swiper.min.css"/>
    <link rel="stylesheet" href="../css/nctouch_products_list.css"/>
    <link rel="stylesheet" href="../css/nctouch_common.css"/>
    <!-- <link rel="stylesheet" href="../css/iconfont.css"> -->
    <link rel="stylesheet" href="https://at.alicdn.com/t/font_562768_pwu4kym4n3o9a4i.css">
    <script type="text/javascript" src="../js/swipe.js"></script>

    <style>
        #search-btn {
            position: absolute;
            right: 2rem;
            top: 0;
            font-size: 0.7rem;
            color: #666;
            display: inline-block;
            line-height: 1.8rem;
        }

        .header-r a i.more {
            background-size: 40%;
        }

        .nctouch-norecord .norecord-ico {
            margin: 10rem 0 0 0;
        }
    </style>

    <script type="text/javascript">
        window.addressStr = '';
        window.coordinate = null;

        function initialize() {
            // 百度地图API功能
            var geolocation = new BMap.Geolocation();
            var geoc = new BMap.Geocoder();

            geolocation.getCurrentPosition(function (r) {
                if (this.getStatus() == BMAP_STATUS_SUCCESS) {
                    var mk = new BMap.Marker(r.point);
                    //alert('您的位置：'+r.point.lng+','+r.point.lat);

                    window.coordinate = {'lng': r.point.lng, lat: r.point.lat};

                    console.info(window.coordinate);

                    geoc.getLocation(r.point, function (rs) {
                        var addComp = rs.addressComponents;
                        window.addressStr = addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber;
                        console.info(window.addressStr);
                        //alert(window.addressStr);
                    });

                }
                else {
                    alert('failed' + this.getStatus());
                    /*
                     function myFun(result){
                     var cityName = result.name;
                     map.setCenter(cityName);
                     alert("当前定位城市:"+cityName);
                     }
                     var myCity = new BMap.LocalCity();
                     myCity.get(myFun);
                     */
                }
            }, {enableHighAccuracy: true})

            //关于状态码
            //BMAP_STATUS_SUCCESS	检索成功。对应数值“0”。
            //BMAP_STATUS_CITY_LIST	城市列表。对应数值“1”。
            //BMAP_STATUS_UNKNOWN_LOCATION	位置结果未知。对应数值“2”。
            //BMAP_STATUS_UNKNOWN_ROUTE	导航结果未知。对应数值“3”。
            //BMAP_STATUS_INVALID_KEY	非法密钥。对应数值“4”。
            //BMAP_STATUS_INVALID_REQUEST	非法请求。对应数值“5”。
            //BMAP_STATUS_PERMISSION_DENIED	没有权限。对应数值“6”。(自 1.1 新增)
            //BMAP_STATUS_SERVICE_UNAVAILABLE	服务不可用。对应数值“7”。(自 1.1 新增)
            //BMAP_STATUS_TIMEOUT	超时。对应数值“8”。(自 1.1 新增)

        }

        function loadScript() {
            var script = document.createElement("script");
            script.src = "http://api.map.baidu.com/api?v=2.0&ak=5At3anZe83x8oOpFap42Gt8eHYpy3wm9&callback=initialize";//此为v2.0版本的引用方式
            // http://api.map.baidu.com/api?v=1.4&ak=您的密钥&callback=initialize"; //此为v1.4版本及以前版本的引用方式
            document.body.appendChild(script);
        }

        window.onload = loadScript;
    </script>
</head>
<body>
<header id="header" class="nctouch-product-header fixed">
    <div class="header-wrap">
        <div class="header-l"><a href="javascript:history.go(-1)"> <i class="back"></i> </a></div>
        <div class="header-inp clearfix">
            <i class="icon"></i>
            <input type="text" class="search-input" name="keyword" value="" oninput="writeClear($(this));" id="keyword"
                   placeholder="" maxlength="50" autocomplete="on" autofocus>
            <span class="input-del"></span>
        </div>
        <a id="search-btn" href="javascript:void(0);" class="search-btn" style="top: 4px;">搜索</a>
        <div class="header-r">
            <a id="header-nav" href="javascript:void(0);">
                <i class="more"></i>
                <sup style="display: inline;"></sup>
            </a>
        </div>
    </div>
    <div class="nctouch-nav-layout">
        <div class="nctouch-nav-menu"><span class="arrow"></span>
            <ul>
                <li><a href="../index.html"><i class="home"></i>首页</a></li>
                <li><a href="../tmpl/cart_list.html"><i class="cart"></i>购物车<sup style="display: inline;"></sup></a></li>
                <li><a href="../tmpl/member/member.html"><i class="member"></i>我的商城</a></li>
                <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
            </ul>
        </div>
    </div>
</header>
<div class="goods-search-list-nav">
    <ul id="nav_ul" style="width:100%;">
        <li style="width:25%;"><a href="javascript:void(0);" onclick="init_get_list('default', '')"class="current">默认排序</a></li>
        <li style="width:25%;"><a href="javascript:void(0);" onclick="init_get_list('or', 'collect')" class="">收藏量</a></li>
        <li style="width:25%;"><a href="javascript:void(0);" onclick="init_get_list('plat', 1)">平台自营</a></li>
        <li style="width:25%;"><a href="javascript:void(0);" onclick="init_get_list('near', 1)">附近的店铺</a></li>
        <!--
    <li  style="width:25%;"><a href="javascript:void(0);" id="search_adv">所在地<i></i></a></li>
    -->
    </ul>
</div>
<!--筛选部分-->
<div class="nctouch-full-mask hide">
    <div class="nctouch-full-mask-bg"></div>
    <div class="nctouch-full-mask-block">
        <div class="header">
            <div class="header-wrap">
                <div class="header-l"><a href="javascript:void(0);"><i class="back"></i></a></div>
                <div class="header-title">
                    <h1>地区筛选</h1>
                </div>
                <div class="header-r"><a href="javascript:void(0);" id="reset" class="text">重置</a></div>
            </div>
        </div>
        <div class="nctouch-main-layout-a secreen-layout" id="list-items-scroll" style="top: 2rem;"></div>
    </div>
</div>
<div class="store-lists-area"></div>
<div class="fix-block-r">
    <a href="javascript:void(0);" class="gotop-btn gotop" id="goTopBtn"><i></i></a>
</div>
</body>
<script type="text/html" id="search_items">
    <div style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
        <dl>
            <dt>所在地</dt>
            <dd>
                <a href="javascript:void(0);" nctype="items" onclick="init_rows('district_name','',this)">不限</a>
                <% if(data.items){ %>
                <% var items = data.items %>
                <% for( var i in items ){ %>
                <a href="javascript:void(0);" nctype="items"
                   onclick="init_rows('district_name','<%= items[i].district_name%>',this)">
                    <%= items[i].district_name %></a>
                <% }} %>
            </dd>
        </dl>
        <div class="bottom">
            <a href="javascript:void(0);" class="btn-l" id="search_submit" onclick="search_adv()">筛选店铺</a>
        </div>
    </div>
</script>

<script type="text/html" id="store-lists-area">
    <ul>
        <% if(data.records > 0){ %>
        <% var items = data.items %>
        <% for( var i in items ){ %>
        <li class="store-list clearfix">
            <div class="store-item-name">
                <div class="store-info clearfix">
                    <div class="store-img">
                        <% if(items[i].shop_logo){ %>
                        <a href="store.html?shop_id=<%= items[i].shop_id%>" title="">
                            <img src="<%= items[i].shop_logo%>" class="img-ber">
                        </a>
                        <% }else{ %>
                        <a href="store.html?shop_id=<%= items[i].shop_id%>" title="">
                            <img src="../images/default_store_image.png" class="img-ber">
                        </a>
                        <% } %>
                    </div>
                    <div class="store-info-o">
                        <p>
                            <a class="store-name m-r-5 one-overflow" href="store.html?shop_id=<%= items[i].shop_id%>">
                                <%= items[i].shop_name%>
                            </a>
                            <a href="javascript:;" data-nc-im="" data-im-seller-id="6" data-im-common-id="0"><i
                                        class="im_common offline"></i></a>

                        </p>
                        <p>共<%= items[i].goods_num %>件宝贝</p>
                        <% if(items[i].distance){ %>
                        <p><span class="store-major"
                                 title=""><%= items[i].distance%> 米  <%= items[i].entity_xxaddr%></span></p>
                        <% } else { %>
                        <p>所在地：<span><%= items[i].shop_company_address%></span></p>
                        <% } %>

                        <!--  <p>店铺等级：<span class="store-major" title=""><%= items[i].shop_detail.shop_grade%></span></p> -->
                    </div>
                </div>
                <% if(items[i].is_collect == 1){%>
                <div class="fav-store">
                    <a href="javascript:;" class="store_save_btn_<%= items[i].shop_id%> active"
                       nc_type="storeFavoritesBtn" onclick="collectShop('<%= items[i].shop_id%>')">
                        <i class="iconfont icon-star align-middle fz-26 mr-10"></i>
                        已收藏
                    </a>
                </div>
                <%}else{%>
                <div class="fav-store">
                    <a href="javascript:;" class="store_save_btn_<%= items[i].shop_id%>" nc_type="storeFavoritesBtn"
                       onclick="collectShop('<%= items[i].shop_id%>')">
                        <i class="iconfont icon-save align-middle fz-26 mr-10"></i>
                        收藏店铺
                    </a>
                </div>
                <%}%>
            </div>

            <div class="store-item-goods item-goods fl">
                <ul class="store-preview-goods clearfix pl-20 pr-20 pb-30">
                    <% var goods_items = items[i].goods_recommended.items %>
                    <% for( var j in goods_items ){ %>
                    <li>
                        <a href="product_detail.html?goods_id=<%= goods_items[j].goods_id%>">
                            <div class="goods-pic"><img src="<%= goods_items[j].common_image%>" alt=""></div>
                            <p class="goods-price tr fz-24 pl-10 pr-10"><em>￥<%= goods_items[j].common_price%></em></p>
                        </a>
                    </li>
                    <% } %>
                </ul>
            </div>
        </li>
        <% }}else { %>
        <div class="nctouch-norecord search">
            <div class="norecord-ico"><i></i></div>
            <dl>
                <dt>没有找到任何相关信息</dt>
            </dl>
        </div>
        <%
        }
        %>

    </ul>
</script>

<script type="text/javascript" src="../js/zepto.js"></script>
<script type="text/javascript" src="../js/simple-plugin.js"></script>
<script type="text/javascript" src="../js/template.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/tmpl/store_list.js"></script>
<script type="text/javascript" src="../js/tmpl/footer.js"></script>
</html>
<?php
include __DIR__ . '/../includes/footer.php';
?>

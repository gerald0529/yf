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
    <meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="msapplication-tap-highlight" content="no" />
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
    <title>店铺介绍</title>
    <link rel="stylesheet" type="text/css" href="../css/base.css">
    <link rel="stylesheet" type="text/css" href="../css/nctouch_store.css">
</head>

<body>
    <header id="header">
        <div class="header-wrap">
            <div class="header-l">
                <a href="javascript:history.go(-1);"> <i class="back"></i> </a>
            </div>
            <div class="header-title">
                <h1>店铺介绍</h1>
            </div>
            <div class="header-r"> <a href="javascript:void(0);" id="header-nav"><i class="more"></i><sup></sup></a> </div>
        </div>
        <div class="nctouch-nav-layout">
            <div class="nctouch-nav-menu"> <span class="arrow"></span>
                <ul>
                    <li><a href="../index.html"><i class="home"></i>首页</a></li>
                    <li><a href="../tmpl/search.html"><i class="search"></i>搜索</a></li>
                    <li><a href="../tmpl/cart_list.html"><i class="cart"></i>购物车<sup></sup></a></li>
                    <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout fixed-Width">
        <div class="" id="store_intro"> </div>
    </div>
    <div class="fix-block-r">
        <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>
    </div>
</body>
<script type="text/html" id="store_intro_tpl">
    <div class="nctouch-store-info">
        <div class="store-avatar"><img src="<%= store_info.shop_logo %>" /></div>
        <dl class="store-base">
            <dt><%= store_info.store_name %></dt>
           <!--  <dd class="class">
                <% if(store_info.shop_self_support == 'false'){%>类型：
                    <%= '普通店铺' %>
                        <% }else{ %>类型：
                <%= '平台自营' %>
                <% } %>
            </dd> -->
            <dd class="type">
                <% if(store_info.shop_self_support == 'false'){%>普通店铺
                    <% }else{%>平台自营
                        <% } %>
            </dd>
        </dl>
        <div class="store-collect">
            <a href="javascript:void(0);" id="store_collected">已收藏</a>
            <a href="javascript:void(0);" id="store_notcollect">收藏</a>
            <p>
                <input type="hidden" id="store_favornum_hide" value="<%= store_info.shop_collect %>" />
                <em id="store_favornum"><%= store_info.shop_collect %></em>粉丝</p>
        </div>
    </div>
    <% if(!store_info.shop_self_support){%>
        <div class="nctouch-store-block pl-20 pr-20">
            <ul class="credit pb-20">
                <li>
                    <!-- span 样式名称可以是high、equal、low -->
                    <h4>描述相符</h4>
                    <span class="">
					<strong><%= store_info.shop_desc_scores %></strong>
					<% if(store_info.com_desc_scores == 0){%>
					与同行业持平
					<% }else{ %>
					<% if (store_info.com_desc_scores > 0){ %> <%= '高于' %> <%}else{%><%= '低于' %><%}%>同行业
					<% } %>
					<em><%= store_info.com_desc_scores %></em>
				</span>
                </li>
                <li>
                    <h4>服务态度</h4>
                    <span class="">
					<strong><%= store_info.shop_service_scores %></strong>
					<% if(store_info.com_service_scores == 0){%>
					与同行业持平
					<% }else{ %>
					<% if (store_info.com_service_scores > 0){ %> <%= '高于' %> <%}else{%><%= '低于' %><%}%>同行业
					<% } %>
					<em><%= store_info.com_service_scores %></em>
				</span>
                </li>
                <li>
                    <h4>物流服务</h4>
                    <span class="">
					<strong><%= store_info.shop_send_scores %></strong>
					<% if(store_info.com_send_scores == 0){%>
					与同行业持平
					<% }else{ %>
					<% if (store_info.com_send_scores > 0){ %> <%= '高于' %> <%}else{%><%= '低于' %><%}%>同行业
					<% } %>
					<em><%= store_info.com_send_scores %></em>
				</span>
                </li>
            </ul>
        </div>
        <% } %>
            <div class="nctouch-store-block pl-20 pr-20">
                <ul class="pb-20">
                    <% if(store_info.shop_name){%>
                        <li>
                            <h4>公司名称</h4>
                            <span><%= store_info.shop_name %></span>
                        </li>
                        <% } %>
                            <% if(store_info.shop_region){%>
                                <li>
                                    <h4>所在地</h4>
                                    <span><%= store_info.shop_region %></span>
                                </li>
                                <% } %>
                                    <% if(store_info.shop_create_time){%>
                                        <li>
                                            <h4>开店时间</h4>
                                            <span><%= store_info.shop_create_time %></span>
                                        </li>
                                        <% } %>
                                            <% if(store_info.store_zy){%>
                                                <li>
                                                    <h4>主营商品</h4>
                                                    <span><%= store_info.store_zy %></span>
                                                </li>
                                                <% } %>
                </ul>
            </div>
            <div class="nctouch-store-block pl-20 pr-20">
                <ul class="pb-20">
                    <% if(store_info.shop_tel){%>
                        <li>
                            <h4>联系电话</h4>
                            <span>
					<%= store_info.shop_tel %>
				</span>
                            <a href="tel:<%= store_info.shop_tel%>" class="call"></a>
                        </li>
                        <% } %>
                            <% if(store_info.shop_workingtime){%>
                                <li>
                                    <h4>工作时间</h4>
                                    <span><%= store_info.shop_workingtime %></span>
                                </li>
                                <% } %>
                                    <% if(store_info.shop_qq || store_info.shop_ww){%>
                                        <li>
                                            <h4>联系方式</h4>
                                            <span>
					<% if(store_info.shop_qq){%>
					<a href="http://wpa.qq.com/msgrd?v=3&uin=<%= store_info.shop_qq %>&site=qq&menu=yes" target="_blank" class="qq">
						<i></i>QQ联系
					</a>
					<% }　%>
				</span>
                                        </li>
                                        <% } %>
                </ul>
            </div>
</script>
<script type="text/javascript" src="../js/zepto.min.js"></script>

<script type="text/javascript" src="../js/template.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/simple-plugin.js"></script>
<script type="text/javascript" src="../js/tmpl/store_intro.js"></script>

</html>
<?php 
include __DIR__.'/../includes/footer.php';
?>
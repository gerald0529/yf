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
    <title>店内搜索</title>
    <link rel="stylesheet" type="text/css" href="../css/base.css">
    <link rel="stylesheet" type="text/css" href="../css/nctouch_store.css">
</head>

<body>
    <header id="header">
        <div class="header-wrap">
            <div class="header-l">
                <a href="javascript:history.go(-1);"> <i class="back"></i> </a>
            </div>
            <div class="header-inp clearfix"> <i class="icon"></i>
                <input type="text" class="search-input" id="search_keyword" placeholder="请输入搜索关键词" maxlength="50" autocomplete="on" autofocus>
            </div>
            <div class="header-r"><a id="search_btn" href="javascript:void(0);" class="search-btn">搜索</a></div>
        </div>
    </header>
    <div class="nctouch-main-layout fixed-Width">
        <div>
            <div class="categroy-cnt">
                <div class="categroy-all"><a id="goods_search_all" href="javascript:void(0);">全部商品<i class="arrow-r"></i></a></div>
                <ul class="categroy-list" id="store_category">

                </ul>
            </div>
        </div>
    </div>
    <div class="fix-block-r">
        <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>
    </div>
</body>
<script type="text/html" id="store_category_tpl">
    <% for (var i in store_goods_class) { var gc = store_goods_class[i]; %>
        <li class="category-frist">
            <a class="level<%= 1 %>" href="javascript:;">
                <%= gc.shop_goods_cat_name %></a>
        </li>
            <% if (gc.subclass) { %>
                <% for (j=0; j < gc.subclass.length; j++) { var gcs = gc.subclass[j]; %>
                    <li class="category-seciond" >
                        <a href="store_goods.html?shop_id=<%= shop_id %>&shop_cat_id=<%= gcs.id %>"><%= gcs.shop_goods_cat_name %></a>
                    </li>
                <% } %>
        <% } %>
    <% } %>
</script>

<script type="text/javascript" src="../js/zepto.min.js"></script>
<script type="text/javascript" src="../js/touch.js"></script>
<script type="text/javascript" src="../js/template.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/tmpl/store_search.js"></script>
</html>
<?php 
include __DIR__.'/../includes/footer.php';
?>
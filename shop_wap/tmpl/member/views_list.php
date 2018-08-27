<?php
    include __DIR__ . '/../../includes/header.php';
?>
    <!doctype html>
    <html>
    
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="Author" contect="U2FsdGVkX1+liZRYkVWAWC6HsmKNJKZKIr5plAJdZUSg1A==">
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-touch-fullscreen" content="yes" />
        <meta name="format-detection" content="telephone=no" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
        <meta name="format-detection" content="telephone=no" />
        <meta name="msapplication-tap-highlight" content="no" />
        <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
        <title>浏览历史</title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_products_list.css">
    </head>
    
    <body>
    <header id="header" class="fixed">
        <div class="header-wrap">
            <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>
            <div class="header-title">
                <h1>浏览记录</h1>
            </div>
            <div class="header-r">
                <span class="history-edit js-history-edit fz-24 col8 mr-20">编辑</span>
                <!-- <a id="clearbtn" href="javascript:void(0);" class="text">清空</a> -->
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout">
        <div id="viewlist" class="list list-history"></div>
        <div class="history-bottom js-history-bottom clearfix">
            <label class="fl pl-20 fz-30"><input type="checkbox" class="mr-20"><em class="iblock align-middle">全选</em></label>
            <button class="fr fz-30">删除</button>
        </div>
    </div>
    <div class="fix-block-r">
        <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>
    </div>
    <script type="text/html" id="viewlist_data">
        <% if ('undefined' != typeof arr.items  &&  arr.items.length > 0) {%>
        <ul class="goods-secrch-list">
            <% for (var i=0; i < arr.items.length; i++) {%>
                <li class="goods-item flex wp100 ">
                    <div class="goods-check fl ml-20">
                        <input type="checkbox" value="12500">
                    </div>
                    <span class="goods-pic fl mr-20 ml-20">
                        <a class="posr" href="../product_detail.html?goods_id=<%=arr.items[i].goods_id%>">
                            <% if(arr.items[i].is_del == 2){ %>
                        	<p class="old-Failed  old-Failed-wh">此商品已失效</p>
                            <% } %>
                            <img src="<%=$image_thumb(arr.items[i].common_image, 116, 116)%>" />
                        </a>
                    </span>
                    <dl class="goods-info flex1">
                        <dt class="goods-name">
                            <a href="../product_detail.html?goods_id=<%=arr.items[i].goods_id%>">
                                <h4 class="more-overflow"><%=arr.items[i].common_name%></h4>
                                <h6></h6>
                            </a>
                        </dt>
                        <dd class="goods-sale">
                            <a href="../product_detail.html?goods_id=<%=arr.items[i].goods_id%>">
                                <span class="goods-price">￥<em><%=arr.items[i].common_price%></em></span>
                            </a>
                        </dd>
                    </dl>
                </li>
                <% } %>
                <li class="loading">
                    <div class="spinner"><i></i></div>
                    浏览记录读取中...
                </li>
        </ul>
        <% } else {%>
        <div class="nctouch-norecord views">
            <div class="norecord-ico"><i></i></div>
            <dl>
                <dt>暂无您的浏览记录</dt>
                <dd>可以去看看哪些想要买的</dd>
            </dl>
            <a href="<%=WapSiteUrl%>" class="btn">随便逛逛</a>
        </div>
        <% } %>
    </script>
    <script type="text/javascript" src="../../js/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/template.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>
    <script type="text/javascript" src="../../js/tmpl/view_list.js"></script>
    <script type="text/javascript" src="../../js/ncscroll-load.js"></script>
    </body>
    </html>
<?php
    include __DIR__ . '/../../includes/footer.php';
?>
<?php
    include __DIR__ . '/../includes/header.php';
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
        <title>商品搜索</title>
        <link rel="stylesheet" type="text/css" href="../css/base.css">
        <link rel="stylesheet" type="text/css" href="../css/nctouch_common.css">
        <link rel="stylesheet" type="text/css" href="../css/swiper.min.css">
    </head>
    <body>
    <header id="header">
        <div class="header-wrap">
            <div class="header-l">
                <a href="javascript:history.go(-1)">
                    <i class="back"></i>
                </a>
            </div>
            <div class="header-inp clearfix header-inp-ser">
                <div class="goods-class">
                    <span>宝贝</span>
                    <i class="icon-drapdown"></i>
                </div>
                <ul class="goods-class-sel" style="display:none">
                    <li class="active search_kind" id="searchWords">
                        <i class="icon"></i>
                        <span>宝贝</span>
                    </li>
                    <li class="search_kind" id="searchShopWords">
                        <i class="icon"></i>
                        <span>店铺</span>
                    </li>
                </ul>
                
                <form id="search-form" action="product_list.php">
                    <input type="text" class="search-input" name="keyword" oninput="writeClear($(this));" id="keyword" placeholder="请输入搜索关键词" maxlength="50" autocomplete="on" autofocus>
                    <span class="input-del"></span>
                </form>
                
            </div>
            <div class="header-r">
                <a id="header-nav" href="javascript:void(0);" class="search-btn">搜索</a>
            </div>
        </div>
    </header>
    
    <div id="store-wrapper">
        <div class="nctouch-search-layout">
            <dl class="hot-keyword bgf borb1">
                <dt>热门搜索</dt>
                <dd id="hot_list_container" class="swiper-container swiper-container-hot swiper-container-free-mode">
                    <ul id="hot_kw_url"></ul>
                </dd>
            </dl>
            <dl class="history-keyword historical-search bgf mt-20 borb1 bort1">
                <dt>历史搜索</dt>
                <dd id="search_his_list_container">
                    <ul id="history_kw_url"></ul>
                </dd>
                <a href="javascript:void(0);" id="clear-history" class="clear-history">
                    <p class="clear-history-p">
                        <i class="icon del"></i>
                        <span>清空历史记录</span>
                    </p>
                </a>
            </dl>
        </div>
    </div>

    <footer id="footer" class="bottom"></footer>
    <script type="text/javascript" src="../js/zepto.min.js"></script>
    <script type="text/javascript" src="../js/template.js"></script>
    <script type="text/javascript" src="../js/common.js"></script>
    <script type="text/javascript" src="../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../js/tmpl/search.js"></script>
    <script type="text/javascript" src="../js/tmpl/footer.js"></script>
    <script type="text/javascript" src="../js/swiper.min.js"></script>

    </body>
    </html>
<?php
    include __DIR__ . '/../includes/footer.php';
?>

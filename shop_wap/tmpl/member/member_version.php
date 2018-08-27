<?php 
include __DIR__.'/../../includes/header.php';
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
    <title>我的商城</title>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
</head>

<body>
    <header id="header">
        <div class="header-wrap">
            <div class="header-l">
                <a href="javascript:history.go(-1)"> <i class="back"></i> </a>
            </div>
            <div class="header-title">
                <h1>关于我们</h1>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout member_version fix">
        <div class="table tc">
            <div class="table-cell">
                <div class="shop_logo">
                    <img id="img" src="" />
                </div>
                <div class="shop_version mt10">
                </div>
            </div>
        </div>
        
        
    </div>
    <footer id="footer" class="bottom"></footer>
    
    <script type="text/javascript" src="../../js/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member_version.js"></script>
</body>

</html>
<?php 
include __DIR__.'/../../includes/footer.php';
?>
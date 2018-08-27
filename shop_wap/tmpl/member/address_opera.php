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
    <title>新增收货地址</title>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_common.css">
</head>
<style>
	.form-item-h5{    
	font-size: 0.6rem;
    color: #999;
    float: right;
    line-height: 1.5rem;}
    .form-item-h5 i{
	    display: inline-block;
	    vertical-align: middle;
	    width: 0.4rem;
	    height: 0.7rem;
	    background-image: url(../../images/bbc-bg44.png);
	    background-repeat: no-repeat;
	    background-size: 100%;
	    margin-left: 0.227rem;
    }
    .nctouch-main-layout-a{top: 4rem;}
</style>
<body>
    <header id="header">
        <div class="header-wrap">
            <div class="header-l">
                <a href="address_list.html"> <i class="back"></i> </a>
            </div>
            <div class="header-title">
                <h1>新增收货地址</h1>
            </div>
            <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="save"></i></a> </div>
        </div>
    </header>
    <div class="nctouch-main-layout">
        <form>
            <div class="nctouch-inp-con">
                <ul class="form-box">
                    <li class="form-item">
                        <h4>收货人：</h4>
                        <div class="input-box">
                            <input type="text" class="inp" name="true_name" id="true_name" autocomplete="off" oninput="writeClear($(this));" />
                            <span class="input-del"></span> </div>
                    </li>
                    <li class="form-item">
                        <h4>联系方式：</h4>
                        <div class="input-box">
                            <input type="tel" class="inp" name="mob_phone" id="mob_phone" autocomplete="off" oninput="writeClear($(this));" />
                            <span class="input-del"></span> </div>
                    </li>
                    <li class="form-item">
                        <h4>所在地区：</h4>
                        <div class="input-box">
                            <input name="area_info" type="text" class="inp" id="area_info" unselectable="on" onfocus="this.blur()" autocomplete="off" onchange="btn_check($('form'));" readonly/>
                        	<h5 class="form-item-h5"><i class="arrow-r"></i></h5>
                        </div>
                    </li>
                    <li class="form-item">
                        <h4>详细地址：</h4>
                        <div class="input-box">
                            <input type="text" class="inp" name="address" id="address" autocomplete="off" placeholder="街道、楼牌号等" oninput="writeClear($(this));">
                            <span class="input-del"></span> </div>
                    </li>
                    <li>
                        <h4>默认地址</h4>
                        <div class="input-box clearfix">
                            <label>
                                <input type="checkbox" class="checkbox" name="is_default" id="is_default" value="1" />
                                <span class="power"><i></i></span> </label>
                        </div>
                    </li>
                </ul>
                <div class="error-tips"></div>
                <div class="form-btn"><a class="btn" href="javascript:;">保存</a></div>
            </div>
        </form>
    </div>
    <footer id="footer" class="bottom"></footer>
    <script type="text/javascript" src="../../js/zepto.min.js"></script>
    
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>
    <script type="text/javascript" src="../../js/tmpl/address_opera.js"></script>
</body>

</html>
<?php 
include __DIR__.'/../../includes/footer.php';
?>
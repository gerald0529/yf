<?php
include __DIR__.'/../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title>选择门店</title>
	<link rel="stylesheet" href="../css/base.css">
	<link rel="stylesheet" href="../css/swiper.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_common.css">

    <script type="text/javascript" src="../js/zepto.min.js"></script>

    <script type="text/javascript" src="../js/template.js"></script>
    <script type="text/javascript" src="../js/swipe.js"></script>
    <script type="text/javascript" src="../js/common.js"></script>
    <script type="text/javascript" src="../js/iscroll.js"></script>
    <script type="text/javascript" src="../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../js/tmpl/footer.js"></script>
    <script type="text/javascript" src="../js/fly/requestAnimationFrame.js"></script>
    <script type="text/javascript" src="../js/fly/zepto.fly.min.js"></script>
    <script type="text/javascript" src="../js/ziti.js"></script>
    <script type="text/javascript" src="../js/jquery.timeCountDown.js" ></script>
</head>
<body>
	<header id="header" class="fixed">
        <div class="header-wrap">
            <div class="header-l">
                <a href="javascript:history.go(-1)"> <i class="back"></i> </a>
            </div>
            <div class="header-title">
                <h1>选择门店</h1>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout">
        <div class="style-layout-lr bgf borb1 mb-20 relatives">
            <span class="fz-28 col5 pad30">选择门店地址</span>
            <input value="" readonly style="border:none" id="area">
            <input name="area_info" type="text" class="inp wp100" id="area_info" autocomplete="off" unselectable="on" onfocus="this.blur()" readonly/>
            <i class="icon-arrow-r mr-30 mt-30"></i>
        </div>
        <!-- 有门店 -->
        <div id="chain-list">
        </div>
        <script type="text/html" id="chainList">
            <% if (chain_rows.length > 0) { %>
                <% for(var i = 0; i < chain_rows.length; i++) { %>
               <div class="bgf clearfix ziti-store-addr pad20 posr">
                    <div class="fl fz-28 col5">
                        <dl class="clearfix">
                            <dt class="fl">店铺名称：</dt>
                            <dd class="z-dhwz fl" style="width: 9rem;"><%= chain_rows[i].chain_name %></dd>
                        </dl>
                        <dl>
                            <dt>联系电话：</dt>
                            <dd><%= chain_rows[i].chain_mobile %></dd>
                        </dl>
                        <dl class="z-chain-list-dhyc">
                            <dd><%= chain_rows[i].chain_province %> <%= chain_rows[i].chain_city %> <%= chain_rows[i].chain_county %> <%= chain_rows[i].chain_address %></dd>
                        </dl>
                    </div>
                    <div class="fr ziti-btn-area">
                        <a href="javascript:;" class="fz-24 btn-ziti" onclick="confirm(<%=chain_rows[i].chain_id%>)">马上自提</a>
                    </div>
                </div>
                <% } %>
            <% } %>
            	
        </script>
        <!-- 无门店可自提 -->
         <div class="norecord tc js-none" style="display: none;">
		            <div class="ziti-store">
		                <i></i>
		            </div>
		            <p class="fz-30 col9">该地区无门店</p>
		  </div>
    </div>
    
</body>
</html>
<?php
include __DIR__.'/../includes/footer.php';
?>
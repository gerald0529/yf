<?php 
include __DIR__.'/../includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title>平台红包</title>
	<link rel="stylesheet" href="../css/swiper.min.css">
	<link rel="stylesheet" href="../css/fight-groups.css">

	<link rel="stylesheet" type="text/css" href="../css/base.css">
	<link rel="stylesheet" type="text/css" href="../css/index.css">
	<link rel="apple-touch-icon" href="images/touch-icon-iphone.png"/>
	<link rel="stylesheet" type="text/css" href="../css/nctouch_products_list.css"/>
</head>
<body>
	<header id="header" class="fixed">
        <div class="header-wrap">
            <div class="header-l">
                <a href="javascript:history.go(-1)"> <i class="back"></i> </a>
            </div>
            <div class="header-title">
                <h1>平台红包</h1>
            </div>
        </div>
    </header>
	<div class="goods-search-list-nav">
		<ul class="bor0 packet-nav">
			<li><a href="javascript:void(0);" id="nav">默认排序<i></i></a></li>
		</ul>
	</div>
	<div class="goods-sort-inner packets-type" id="filtrate_ul">
		<span><a href="javascript:void(0);"  class="cur" data-orderby="default">默认排序<i></i></a></span>
		<span><a href="javascript:void(0);"  data-orderby="exchangenumdesc">兑换量由高到低<i></i></a></span>
		<span><a href="javascript:void(0);"  data-orderby="exchangenumasc">兑换量由低到高<i></i></a></span>
		<span><a href="javascript:void(0);"  data-orderby="denominationdesc">兑换面额由高到低<i></i></a></span>
		<span><a href="javascript:void(0);"  data-orderby="denominationasc">兑换面额由低到高<i></i></a></span>
	</div>
	<ul class="packets">
		<section id="repacket" data-spm=""></section>

		<div class="no-packets" id="none" style="display:none"><em></em><p>还没有红包嗷~</p></div>
	</ul>

	<script type="text/javascript" src="../js/zepto.min.js"></script>
	<script type="text/javascript" src="../js/simple-plugin.js"></script>
	<script type="text/javascript" src="../js/template.js"></script>
	<script type="text/javascript" src="../js/common.js"></script>
	<script type="text/javascript" src="../js/swipe.js"></script>
	<script type="text/javascript" src="../js/redpacket.js"></script>
	<script type="text/javascript" src="../js/tmpl/footer.js"></script>
	<script  type="text/html" id="repacket_index">

		<% var redpack=redpacket.items %>
		<% for(var i=0;i<redpack.length;i++){%>
		<li>
			<div class="left">
				<h3 class="mt4 m-0-auto one-overflow wp80"><%=redpack[i].redpacket_t_title%></h3>
				<% if(redpack[i].redpacket_t_img){%>
				<img src="<%=redpack[i].redpacket_t_img%>" alt="" style="width:90px;height:90px">
				<% }else{ %>
				<img src="../images/new/cc/packets-img.jpg" alt="">
				<% } %>
			</div>
			<div class="right tc">
				<div class="pri"><em>￥</em><span><%=redpack[i].redpacket_t_price%></span></div>
				<div class=""><span class='fit'>购满<%=redpack[i].redpacket_t_orderlimit%>元使用</span></div>
				<p class="confit">每人仅限兑换<%=redpack[i].redpacket_t_eachlimit%>张<time>有效期至：<%=redpack[i].redpacket_t_end_date_day%></time></p>

				<div class="clearfix bot">
					<div class="stat" data-num="<%=redpack[i].redpacket_t_giveout%>">已兑换<aa><%=redpack[i].redpacket_t_giveout%></aa>张</div>
					<div id="exchange"><a href="javascript:;" class="go-use" redpacket_t_id="<%=redpack[i].redpacket_t_id%>">立即兑换</a></div>
				</div>
			</div>
		</li>
		<% } %>

	</script>

	<script>

		function off(obj,obj2){
			obj.click(function(){
				obj2.toggle();
			})
		}
		$(function(){
			off($(".packet-nav li"),$(".packets-type"));
		})

	</script>
</body>
<?php 
include __DIR__.'/../includes/footer.php';
?>
</html>


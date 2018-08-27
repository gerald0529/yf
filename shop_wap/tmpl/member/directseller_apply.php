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
    <title>分销申请</title>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_common.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_cart.css">
	
	<style type="text/css">
		.nctouch-order-item-head{font-size:0.55rem;height:2.9rem;}
		.shop_head{font-weight:bold;font-size:0.6rem;}
		.rh{text-align:right;line-height:1.0rem;}
	</style>
</head>

<body>
    <header id="header" class="fixed">
        <div class="header-wrap">
            <div class="header-l"><a href="directseller.html"><i class="back"></i></a></div>
             <div class="header-title">
                <h1>分销申请</h1>
            </div>
            <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a></div>
        </div>
        
		<div class="nctouch-nav-layout">
            <div class="nctouch-nav-menu"> <span class="arrow"></span>
                <ul>
                    <li><a href="../../index.html"><i class="home"></i>首页</a></li>
                    <li><a href="../search.html"><i class="search"></i>搜索</a></li>
                    <li><a href="../cart_list.html"><i class="cart"></i>购物车</a><sup></sup></li>
                    <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
                </ul>
            </div>
        </div>
    
	</header>
   
    <div class="nctouch-main-layout">
        <!-- <div class="nctouch-order-search">
            <form>
                <span>
					<input type="text" autocomplete="on" maxlength="50" placeholder="输入订单号进行搜索" name="order_key" id="order_key" oninput="writeClear($(this));" >
					<span class="input-del"></span>
				</span>
                <input type="button" id="search_btn" value="&nbsp;">
            </form>
        </div>
		
        <div id="fixed_nav" class="nctouch-single-nav">
        </div> -->
        <div class="nctouch-order-list">
            <ul id="apply-list"></ul>
        </div> 
    </div>
	
    <div class="fix-block-r">
        <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>
    </div>
    <footer id="footer" class="bottom"></footer>
    <script type="text/html" id="apply-list-tmpl">
        <% var applylist = data.items; %>
        <% if (applylist.length > 0){%>
        <% for(var i = 0;i<applylist.length;i++)
		{
			var applyinfo = applylist[i];
		%>
            <li class="">                    
				<div class="nctouch-order-item">
                    <div class="nctouch-order-item-head">
                       <p class="shop_head"><%=applyinfo.shop_name%></p>
					   <p class="rh">消费满￥<%=applyinfo.expenditure%>可申请或单笔满￥<%=data.user_paymember_money%></p>
					   <p class="rh">
						<%
							if(!applyinfo.status&&applyinfo.status!='')
							{							
								if(applyinfo.apply_enable)
								{
						%>
								<a href="javascript:void(0);" onclick="apply('<%=applyinfo.shop_id%>')">成为分销员</a>
						<%									 
								}else{
						%>
								未满足条件
						<%					
								}
						%>
							
						<% 
							}else{
							
								if(applyinfo.status==0)
								{
						%>
								等待审核
						<%
								}else{
						%>
								审核通过
						<%			
								}
							
							}
						%>
					   </p>
                    </div>
                </div>
            </li>
        <%}%>
        <% if (hasmore) {%>
            <li class="loading">
                <div class="spinner"><i></i></div>店铺数据读取中...</li>
            <% } %>
        <%}else {%>
            <div class="nctouch-norecord order">
                <div class="norecord-ico"><i></i></div>
                <dl>
                    <dt>您还没有相关的店铺</dt>
                    <dd>可以去看看哪些想要买的</dd>
                </dl>
                <a href="<%=WapSiteUrl%>" class="btn">随便逛逛</a>
            </div>
        <%}%>
    </script>
    <script type="text/javascript" src="../../js/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/template.js"></script>
    
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/zepto.waypoints.js"></script>
    <script type="text/javascript" src="../../js/tmpl/order_payment_common.js"></script>
    <script type="text/javascript" src="../../js/tmpl/directseller_apply.js"></script>
</body>

</html>
<?php 
include __DIR__.'/../../includes/footer.php';
?>
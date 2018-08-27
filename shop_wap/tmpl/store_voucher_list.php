<?php 
include __DIR__.'/../includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<link rel="stylesheet" href="../css/base.css">
</head>
<body>
    <header id="header" class="posf">
        <div class="header-wrap">
            <div class="header-l">
                <a href="javascript:history.go(-1)"> <i class="back"></i> </a>
            </div>
            <div class="tit">代金券</div>
            <div class="header-r"><a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>
        </div>
        <div class="nctouch-nav-layout">
            <div class="nctouch-nav-menu"> <span class="arrow"></span>
                <ul>
                    <li><a href="../../index.html"><i class="home"></i>首页</a></li>
                    <li><a href="../../tmpl/search.html"><i class="search"></i>搜索</a></li>
                    <li><a href="../../tmpl/cart_list.html"><i class="cart"></i>购物车<sup></sup></a></li>
                    <li><a href="../../tmpl/member/member.html"><i class="member"></i>我的商城</a></li>
                    <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
                </ul>
            </div>
        </div>
    </header>
	<div class="nctouch-bottom-mask-block vou-area">
        <h3 class="tc">&nbsp;</h3>
         <ul class="vou-lists hgauto" id="v_list">
             
         </ul>
    </div>
    <script type="text/html" id="voucher_list">
       
        <%if(items){
            for (var i in items) {%>
                <li>
                    <div class="left tc">
                        <p>
                            <i>￥</i><span><%=items[i].voucher_t_price%></span>
                        </p>
                        
                        <%if(items[i].voucher_t_points > 0){%>
                            <em>需花费<%=items[i].voucher_t_points%>积分</em>
                        <% } %>
                        
                    </div>
                    <div class="right">
                        <div class="rgl">
                            <h4>店铺优惠券</h4>
                            <span>购满<%=items[i].voucher_t_limit%>元使用</span>
                            <time><%=items[i].voucher_t_end_date_day%>前有效</time>
                        </div>
                        <div class="rgr">
                            <%if(items[i].is_get == 1){%>
                            <a href="javascript:;" class="had">已经<br>领取</a>
                            <%}else{%>
                            <a onclick="confrimVoucher(<%=items[i].voucher_t_id%>,<%=items[i].voucher_t_points%>,<%=items[i].voucher_t_price%>)">立即<br>领取</a>
                            <%}%>
                        </div>
                    </div>
                </li>

        <%}}%>
    </script>
    <script type="text/javascript" src="../js/zepto.min.js"></script>
    <script type="text/javascript" src="../js/template.js"></script>
    <script type="text/javascript" src="../js/common.js"></script>
    <script type="text/javascript" src="../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../js/tmpl/store_voucher_list.js"></script>
    <script type="text/javascript" src="../js/tmpl/footer.js"></script>
    </body></html>

<?php 
include __DIR__.'/../includes/footer.php';
?>

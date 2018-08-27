<?php 
include __DIR__.'/../../includes/header.php';
$act = $_GET['act'] ? $_GET['act'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title>代金券</title>
	<link rel="stylesheet" href="../../css/base.css">
</head>
<body>
	<header id="header" class="posf">
        <div class="header-wrap">
            <div class="header-l">
                <a href="member.html"> <i class="back"></i> </a>
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
    <ul class="v-list-tab bgf borb1">
        <li class="vocher_status_li <?php if($act == 0){echo 'active';}?>"><a href="member_voucher.html?act=0">全部</a></li>
    	<li class="vocher_status_li <?php if($act == 1){echo 'active';}?>"><a href="member_voucher.html?act=1">未使用</a></li>
    	<li class="vocher_status_li <?php if($act == 2){echo 'active';}?>"><a href="member_voucher.html?act=2">已失效</a></li>
        
    </ul>
    <div class="v-list" id="v_list">
    	
    </div>
    
    <script type="text/html" id="voucher_list">
        <%if(items){%>
            <ul>
                <% for (var i in items) {%>
                <a href="/tmpl/store.html?shop_id=<%=items[i].voucher_shop_id%>">
                    <%if(items[i].voucher_state == 1){%>
                    <li class="clearfix yes">
                    <%}else if(items[i].voucher_state == 2){%>    
                    <li class="clearfix no">
                    <%}else{%>    
                    <li class="clearfix pass">
                    <% } %>    
                        
                        <div class="fl">
                            <div>
                                <p class="tc fz-56 colf"><b class="fz-28 iblock align-top">￥</b><span><%=items[i].voucher_price%></span></p>
                                <div class="tc fz-24"><span>满<%=items[i].voucher_limit%>元使用</span></div>
                            </div>
                            
                        </div>
                        <div class="fr pt-20 pb-20 pr-30">
                            <h3 class="fz-24 more-overflow"><%=items[i].voucher_shop_name%></h3>
                            <p class="fz-24 colbc mt-10"><%=items[i].voucher_end_date%>前有效</p>
                        </div>
                        <%if(items[i].voucher_state ==1){%>
                        <span class="btn-voucher-use fz-24 default-color">立即使用</span>
                         <% } %>    
                       <!--  <i class="icon-pase"></i> -->
                    </li>
                </a>   
                <%}%>
            </ul>
        <%}%>
    </script>
    <script type="text/javascript" src="../../js/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/template.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/member_voucher.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>
    </body></html>

<?php 
include __DIR__.'/../../includes/footer.php';
?>


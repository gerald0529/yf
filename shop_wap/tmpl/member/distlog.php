<?php 
include __DIR__.'/../../includes/header.php';
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
    <title>分销明细</title>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_products_list.css">
</head>

<body>
    <header id="header" class="fixed">
        <div class="header-wrap">
            <div class="header-l"><a href="javascript:history.go(-1)"><i class="back"></i></a></div>
            <div class="header-title">
                <h1>分销明细</h1>
            </div>
        </div>
        <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>
        <div class="nctouch-nav-layout">
            <div class="nctouch-nav-menu"> <span class="arrow"></span>
                <ul>
                    <li><a href="../../index.html"><i class="home"></i>首页</a></li>
                    <li><a href="../search.html"><i class="search"></i>搜索</a></li>
                    <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="nctouch-main-layout">
	    <div class="list">
	        <ul id="distlog_list" class="goods-secrch-list"> </ul>
	    </div>
	</div>    
    
    <div class="fix-block-r">
        <a href="javascript:void(0);" class="gotop-btn gotop hide" id="goTopBtn"><i></i></a>
    </div>
    
    <script type="text/html" id="distlog">
        <% if (data.items.length > 0) { %>
            <% for (var k in data.items) { %>
                <li class="goods-item">
                	<span class="goods-pic">
						<a href="order_detail.html?order_id=<%=data.items[k].order_id%>">
							<img src="<%=data.items[k].goods_image%>"/>
						</a>
					</span>
                	<dl class="goods-info">
                        <dt class="goods-name" style="height: 3.4rem;">
							<a href="order_detail.html?order_id=<%=data.items[k].order_id%>">
								<h4><%=data.items[k].order_id%></h4>
								<h6><%=data.items[k].goods_name%></h6>
							</a>
						</dt>
						<dd class="goods-sale">
							<a>
                                <span class="goods-price">￥<em><%=data.items[k].goods_price%></em></span>
                            </a>
						</dd>
                	</dl>
            </li>
            <%}%>
        <% } else { %>
            <div class="nctouch-norecord talk">
                <div class="norecord-ico"><i></i></div>
                <dl>
                    <dt>没有相关数据！</dt>
                    <dd></dd>
                </dl>
            </div>
        <% } %>
    </script>
    
    
    <script type="text/javascript" src="../../js/zepto.min.js"></script>
    <script type="text/javascript" src="../../js/template.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>
    <script type="text/javascript" src="../../js/tmpl/distlog.js"></script>
</body>

</html>
<?php 
include __DIR__.'/../../includes/footer.php';
?>
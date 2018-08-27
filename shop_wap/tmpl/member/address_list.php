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
    <title>地址管理</title>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_member.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_products_detail.css"/>
</head>

<body>
    
    <header id="header">
        <div class="header-wrap">
            <div class="header-l">
                <a href="member.html"> <i class="back"></i> </a>
            </div>
            <div class="header-title">
                <h1>地址管理</h1>
            </div>
            <div class="header-r"> <a id="header-nav" href="address_opera.html"><i class="icon-add"></i></a> </div>
        </div>
    </header>
    <div class="nctouch-main-layout mb20">
        <div class="nctouch-address-list" id="address_list"></div>
    </div>
    <footer id="footer" class="bottom"></footer>
    <script type="text/html" id="saddress_list">
    <% var len = address_list.length %>
        <% if(len != 0){ %>
            <ul>
                <% for (var i in address_list) {%>
                    <li>
                        <dl>
                            <dt>
            					<span class="name one-overflow w4"><%=address_list[i].user_address_contact %></span>
            					<span class="phone ml-40"><%=address_list[i].user_address_phone %></span>
            				</dt>
                            <dd class="more-overflow">
                                <%=address_list[i].user_address_area %>&nbsp;
                                    <%=address_list[i].user_address_address %>
                            </dd>
                        </dl>
                        <div class="handle">
                            <input type="radio" name="address" class="user_address" data-user_address_id=<%=address_list[i].user_address_id %> <% if (address_list[i].user_address_default == 1) { %> checked
                            <% } %> />  <p class="mrdz">默认地址</p>
                            <span><a href="address_opera_edit.html?user_address_id=<%=address_list[i].user_address_id %>"><i class="edit"></i>编辑</a><a href="javascript:;" user_address_id="<%=address_list[i].user_address_id %>" class="deladdress"><i class="del"></i>删除</a></span>
                        </div>
                    </li>
                <%}%>
            </ul>    
           
            <!--暂无收货地址需要判断-->
            <div class="norecord tc hide">
                <p class="fz-30 col9">暂无收货地址</p>
            </div>
            <div class="goods-option-foot z-xjdz-foot">
            	<a class="btn-l" href="address_opera.html">新建地址</a>
            </div>
            
        <%}else{%>
            <div class="nctouch-norecord address">
               <!-- <div class="norecord-ico"><i></i></div> -->
                <dl>
                    <dt>您还没有过添加收货地址</dt>
                    <dd>正确填写常用收货地址方便购物</dd>
                </dl>
                <a href="address_opera.html" class="btn">添加新地址</a>
                <!-- <a class="btn-l mt5 goods-option-foot z-xjdz-foot" href="address_opera.html">新建地址</a> -->
            </div>
        <%}%>
    </script>
    <script type="text/javascript" src="../../js/zepto.min.js"></script>
    
    <script type="text/javascript" src="../../js/template.js"></script>
    <script type="text/javascript" src="../../js/common.js"></script>
    <script type="text/javascript" src="../../js/simple-plugin.js"></script>
    <script type="text/javascript" src="../../js/tmpl/address_list.js"></script>
    <script type="text/javascript" src="../../js/tmpl/footer.js"></script>
</body>

</html>
<?php 
include __DIR__.'/../../includes/footer.php';
?>
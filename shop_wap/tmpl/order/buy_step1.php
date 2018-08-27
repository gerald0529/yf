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
    <meta name="format-detection" content="telephone=no"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="msapplication-tap-highlight" content="no" />
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
    <title>确认订单</title>
    <link rel="stylesheet" type="text/css" href="../../css/base.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_common.css">
    <link rel="stylesheet" type="text/css" href="../../css/nctouch_cart.css">
    <link rel="stylesheet" type="text/css" href="../../css/voucher.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/private-store.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/nctouch_products_detail.css"/>
    
    <style>
        .jia-shop .fr a.min {
            background: #d5d5d5;
        }
        .jia-shop .fr a.min.disabled, .jia-shop .fr a.max.disabled{
            background: #eeeeee;
        }
        
   </style>
</head>
<body class="hp100 hidden">
<header id="header" class="fixed">
    <div class="header-wrap">
        <div class="header-l"> <a href="javascript:history.go(-1)"> <i class="back"></i> </a> </div>
        <div class="header-title">
            <h1>确认订单</h1>
        </div>
        <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a> </div>
    </div>
    <div class="nctouch-nav-layout">
        <div class="nctouch-nav-menu"> <span class="arrow"></span>
            <ul>
                <li><a href="../../index.html"><i class="home"></i>首页</a></li>
                <li><a href="../../tmpl/search.html"><i class="search"></i>搜索</a></li>
                <li><a href="../../tmpl/member/member.html"><i class="member"></i>我的商城</a></li>
                <li><a href="javascript:void(0);"><i class="message"></i>消息<sup></sup></a></li>
            </ul>
        </div>
    </div>
</header>
<div id="container-fcode" class="hide">
    <div class="fcode-bg">
        <div class="con">
            <h3>您正在购买“F码”商品</h3>
            <h5>请输入所知的F码序列号并提交验证<br/>
                系统效验后可继续完成下单</h5>
            <input type="text" name="fcode" id="fcode" placeholder="" />
            <p class="fcode_error_tip" style="display:none;color:red;"></p>
            <a href="javascript:void(0);" class="submit">提交验证</a> </div>
    </div>
</div>
<div class="nctouch-main-layout mb20" id="js-module-order">
    <div class="nctouch-cart-block">
        <!--正在使用的默认地址Begin-->
        <div class="nctouch-cart-add-default borb1">
        	<div class='address1 packets-type'>
	        		<a href="javascript:void(0);" id="list-address-valve"><i class="icon-add"></i>
	            <dl>
	                <input type="hidden" class="inp" name="address_id" id="address_id"/>
	                <dt class="clearfix"><p id="true_name" class="fl one-overflow w4"></p><p id="mob_phone" class="fl ml-60"></p></dt>
	                <dd><span id="address" class="z-dhwz wt12"></span></dd>
	            </dl>
	            <i class="icon-arrow"></i></a>
        	</div>
             <!--添加收货地址-->
		    <div class="address2 z-box2 packets-type  new-address-valve">
		    	<div class="z-box2-tj js-add-address">
		            <img src="../../images/SHIZI.png"/>
		            <div class="z-box2-a">添加收货人信息</div>
		       	</div>
		    </div>
        </div>
        <!--正在使用的默认地址End-->
    </div>
    <!--商品列表Begin-->
    <div id="goodslist_before" class="mt5">
        <div id="deposit"> </div>
    </div>

    <!-- 新增平台红包Begin -->
    <div class="nctouch-cart-block bort1">
        <div class="mrl54 pdt2">
            <a href="javascript:void(0);" class="posr  hongbao" id="redpacket">
            <h3>平台红包</h3>
            <div class="current-con  mr53">
                <p id="use_redpacket">暂无可用平台红包</p>
            </div>
            <i class="icon-arrow"></i> </a>  
            <input type="hidden" name="red-price" is-best="0">
        </div>
   </div>
   <!-- 新增会员折扣 -->
   <div class="nctouch-cart-block bort1 ratePrice pt-10 pb-10" id="support">
        <div class="mrl54 pdt2">
            <a href="javascript:void(0);" class="posr">
                <h3>会员折扣</h3>
                <div class="current-con mr-20 clearfix">
                    <input type="hidden" name="is_discount" value="0">
                    <input type="hidden" value="0" class="discount">
                    <div class="rptdiv">
                        <em class="align-top iblock price-lh" id="ratePrice">此功能不与优惠券共用</em>
                        <div class="iblock fr btn-switch ml-20 rptbutton">
                            <input id="checkStatus" type="checkbox">
                            <label></label>
                        </div>
                    </div>
                </div>
            </a>
        </div>
   </div>
    <!--商品列表End-->
     <!--付款方式Begin-->
    <div class="nctouch-cart-block bort1">
        <div class="mrl54 pdb2">
            <a href="javascript:void(0);" class="posr" id="select-payment-valve">
                <h3>支付方式：</h3>
                <div class="current-con mr53">在线付款</div>
                <i class="icon-arrow"></i> </a>
        </div>
    </div>
        
    <!--付款方式End-->
      <!--发票信息Begin-->
    <div class="nctouch-cart-block bort1 borb1">
        <div class="mrl54 pdt2">
            <a href="javascript:void(0);" class="posr" id="invoice-valve">
            <h3>发票信息：</h3>
            <div class="current-con mr53">
                <p id="invContent">不需要发票</p>
                <input type="hidden" name="invoice_id" value='0' id='order_invoice_id'/>
                <input type="hidden" name="order_invoice_title" value='个人' id='order_invoice_title'/>
                <input type="hidden" name="order_invoice_content" value='' id='order_invoice_content'/>
            </div>
            <i class="icon-arrow"></i> </a>  
        </div>
   </div>
    <!--发票信息End-->
</div>
   <!--选择收货地址Begin-->
    <div id="list-address-wrapper" class="nctouch-full-mask hide">
        <div class="nctouch-full-mask-bg"></div>
        <div class="nctouch-full-mask-block">
            <div class="header absolute">
                <div class="header-wrap">
                    <div class="header-l"> <a href="javascript:void(0);"> <i class="back"></i> </a> </div>
                    <div class="header-title">
                        <h1>收货地址管理</h1>
                    </div>
                </div>
            </div>
            <div class="nctouch-main-layout fp-main-layout" id="list-address-scroll">
                <ul class="nctouch-cart-add-list" id="list-address-add-list-ul">
                </ul>
            </div>
            <div id="addresslist" class="mt10 bottom-btns"> <a href="javascript:void(0);" class="btn-l" id="new-address-valve">新增收货地址</a> </div>
        </div>
    </div>
    <!--选择收货地址End-->
    
  
    <!--新增收货地址Begin-->
    <div id="new-address-wrapper" class="nctouch-full-mask hide">
        <div class="nctouch-full-mask-bg"></div>
        <div class="nctouch-full-mask-block">
            <div class="header absolute">
                <div class="header-wrap">
                    <div class="header-l"> <a href="javascript:void(0);"> <i class="back"></i> </a> </div>
                    <div class="header-title">
                        <h1>新增收货地址</h1>
                    </div>
                </div>
            </div>
            <div class="nctouch-main-layout fp-main-layout" id="new-address-scroll">
                <div class="nctouch-inp-con">
                    <form id="add_address_form">
                        <ul class="form-box">
                            <li class="form-item">
                                <h4>收货人姓名</h4>
                                <div class="input-box">
                                    <input type="text" class="inp" name="true_name" id="vtrue_name" autocomplete="off" oninput="writeClear($(this));"/>
                                    <span class="input-del"></span> </div>
                            </li>
                            <li class="form-item">
                                <h4>联系手机</h4>
                                <div class="input-box">
                                    <input type="tel" class="inp" name="mob_phone" id="vmob_phone" autocomplete="off" oninput="writeClear($(this));"/>
                                    <span class="input-del"></span> </div>
                            </li>
                            <li class="form-item">
                                <h4>地区选择</h4>
                                <div class="input-box">
                                    <input name="area_info" type="text" class="inp" id="varea_info" unselectable="on" onfocus="this.blur()" autocomplete="off" onchange="btn_check($('form'));" readonly/>
                                </div>
                            </li>
                            <li class="form-item">
                                <h4>详细地址</h4>
                                <div class="input-box">
                                    <input type="text" class="inp" name="vaddress" id="vaddress" autocomplete="off" oninput="writeClear($(this));"/>
                                    <span class="input-del"></span> </div>
                            </li>
                        </ul>
                        <div class="error-tips"></div>
                        <div class="form-btn"><a href="javascript:void(0);" class="btn">保存地址</a></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--新增收货地址End-->
   <div id="select-redpacket" class="nctouch-full-mask hide">
        <div class="nctouch-full-mask-bg"></div>
        <div class="nctouch-full-mask-block">
            <div class="header absolute">
                <div class="header-wrap">
                    <div class="header-l"> <a href="javascript:void(0);"> <i class="back"></i> </a> </div>
                    <div class="header-title">
                        <h1>选择平台红包</h1>
                    </div>
                </div>
            </div>
            <div id="order_redpacket_list" class="overflow-auto height-set"></div>
        </div>
    </div>

   <!-- 新增平台红包end -->
   
    
<!--        点击效果-->
    <div class="nctouch-bottom-mask down hp100" id="payways">
        <div class="nctouch-bottom-mask-bg"></div>
        <div class="nctouch-bottom-mask-block">
            <div class="z-zffs">支付方式</div>
            <div class="z-zffs2 clearfix">
                <p>在线支付</p>
                <input type="radio" name="pay-selected" id="payment-online" value="1" checked="checked"/>
            </div>
            <div class="z-zffs2 clearfix z-mdzf">
                <p>货到付款</p>
                <input type="radio" name="pay-selected" id="payment-offline" value="2"/>
            </div>
            <div class="z-zffs3 z-bgc JS_close">确定</div>
        </div>
    </div>
       
       
    <!--选择付款方式Begin-->
    <div id="select-payment-wrapper" class="nctouch-full-mask hide">
        <div class="nctouch-full-mask-bg"></div>
        <div class="nctouch-full-mask-block">
            <div class="header absolute">
                <div class="header-wrap">
                    <div class="header-l"> <a href="javascript:void(0);"> <i class="back"></i> </a> </div>
                    <div class="header-title">
                        <h1>选择支付方式</h1>
                    </div>
                </div>
            </div>
            <div class="nctouch-main-layout fp-main-layout">
                <div class="nctouch-sel-box pl-30 pr-30">
                    <h4 class="tit">支付方式</h4>
                    <div class="sel-con"> <a href="javascript:void(0);" class="sel" id="payment-online">在线支付</a> <a href="javascript:void(0);" id="payment-offline">货到付款</a></div>
                </div>
            </div>
        </div>
    </div>
    <!--选择付款方式End-->
      

    <!--管理发票信息Begin-->
    <div id="invoice-wrapper" class="nctouch-full-mask hide">
        <div class="nctouch-full-mask-bg"></div>
        <div class="nctouch-full-mask-block">
            <div class="header absolute">
                <div class="header-wrap">
                    <div class="header-l" id="js-btn-back"> <a href="javascript:void(0);"> <i class="back"></i> </a> </div>
                    <div class="header-title">
                        <h1>修改发票信息</h1>
                    </div>
                </div>
            </div>
            <div class="nctouch-main-layout wp100 hp93 scroll-y fp-main-layout">
               <!--  <div class="nctouch-sel-box pl-30 pr-30">
                    <div class="sel-con">
                        <div class="tic-tab"><a href="javascript:void(0);" class="sel" id="invoice-noneed">不需要开发票</a></div>
                        <div class="tic-tab"> <a href="javascript:void(0);" id="invoice-need">需要开发票</a></div>
                    </div>
                </div> -->
                <div id="invoice-div" class="">
                    <div class="nctouch-inp-con">
                        <ul class="form-box pt-0 mb-20 borb1">
                            <li class="form-item mrl0 pl-20 pr-20 pt-30 pb-30">
                                <h3 class="fz-30 col2 pb-20">发票类型</h3>
                                <div id="invoice_type" class="fp-tab input-box btn-style clearfix fz-0 pt-0 pb-0 tl">
                                   <em class="label checked">
                                        <input type="radio" checked="checked" name="inv_title_select" value="normal" id="norm" >
                                        普通发票 </em>
                                    <em class="label ucli">
                                        <input type="radio" name="inv_title_select" value="electronics" id="electronics" disabled="true">
                                        电子发票 </em>
                                    <em class="label ucli">
                                        <input type="radio" name="inv_title_select" value="increment" id="increment" disabled="true">
                                        增值税专用发票 </em>
                                </div>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="nctouch-inp-con mb-20" id="invoice_add" style="display:none">
                       
                       <ul id="invoice-list" class="nctouch-sel-list bort1 pt-0 bg-transparent">
                        </ul>
                    </div>
                   
                   
                    <!-- <div style="width:100%; height: 50px;"></div> -->

                </div>
                <ul class="fp-select pl-20 bgf pb-20 bort1 borb1 clearfix">
                    <h4 class="fz-30 col2 borb1 mb-20">发票内容<em class="fz-24 colbc ml-10 iblock align-middle wp80">发票内容选项已根据税法调整，具体请以展示为准</em></h4>
                    <div class="clearfix">
                        <li><a href="javascript:void(0);" class="sel fz-24 col2" id="invoice-noneed">不开发票</a></li>
                        <li><a href="javascript:void(0);" class="fz-24 col2" id="invoice-need">商品明细</a></li> 
                    </div>
                     <b class="fz-24 colbc iblock align-middle">发票内容将显示详细商品名称与价格信息</b>
                </ul>
                 <a href="javascript:void(0);" id="invoice-sure" class="btn-l mt10 mb-30">确定</a> 
            </div>
        </div>
    </div>
    <!--管理发票信息End-->

  
    <!--底部总金额固定层End-->
    <div class="nctouch-bottom-mask down">
        <div class="nctouch-bottom-mask-bg"></div>
        <div class="nctouch-bottom-mask-block">
            <div class="nctouch-bottom-mask-tip"><i></i>点击此处返回</div>
            <div class="nctouch-bottom-mask-top">
                <p class="nctouch-cart-num">本次交易需在线支付<em id="onlineTotal">0.00</em></p>
                <p style="display:none" id="isPayed"></p>
                <a href="javascript:void(0);" class="nctouch-bottom-mask-close"><i></i></a> </div>
            <div class="nctouch-inp-con nctouch-inp-cart">
                <ul class="form-box" id="internalPay">
                    <p class="rpt_error_tip" style="display:none;"></p>
                    <li class="form-item" id="wrapperUseRCBpay">
                        <div class="input-box pl5">
                            <label>
                                <input type="checkbox" class="checkbox" id="useRCBpay" autocomplete="off" />
                                使用充值卡支付 <span class="power"><i></i></span> </label>
                            <p>可用充值卡余额 ￥<em id="availableRcBalance"></em></p>
                        </div>
                    </li>
                    <li class="form-item" id="wrapperUsePDpy">
                        <div class="input-box pl5">
                            <label>
                                <input type="checkbox" class="checkbox" id="usePDpy" autocomplete="off" />
                                使用预存款支付 <span class="power"><i></i></span> </label>
                            <p>可用预存款余额 ￥<em id="availablePredeposit"></em></p>
                        </div>
                    </li>
                    <li class="form-item" id="wrapperPaymentPassword" style="display:none">
                        <div class="input-box"> <span class="txt">输入支付密码</span>
                            <input type="password" class="inp" id="paymentPassword" autocomplete="off" />
                            <span class="input-del"></span> </div>
                        <a href="../member/member_paypwd_step1.html" class="input-box-help" style="display:none"><i>i</i>尚未设置</a> </li>
                </ul>
                <div class="nctouch-pay">
                    <div class="spacing-div"><span>在线支付方式</span></div>
                    <div class="pay-sel">
                        <label style="display:none">
                            <input type="radio" name="payment_code" class="checkbox" id="alipay" autocomplete="off" />
                            <span class="alipay">支付宝</span></label>
                        <label style="display:none">
                            <input type="radio" name="payment_code" class="checkbox" id="wxpay_jsapi" autocomplete="off" />
                            <span class="wxpay">微信</span></label>
                    </div>
                </div>
                <div class="pay-btn"> <a href="javascript:void(0);" id="toPay" class="btn-l">确认支付</a> </div>
            </div>
        </div>
    </div>
</div>
  <!--底部总金额固定层Begin-->
    <div class="nctouch-cart-bottom">
        <div class="total"><span id="online-total-wrapper"></span>
            <dl class="total-money">
                <dt>合计：</dt>
                <dd>￥<em id="totalPrice"></em></dd>
            <!--     <dt>支付总金额：</dt>
                <dd>￥<em id="totalPayPrice"></em></dd> -->
            </dl>
        </div>
        <div class="check-out"><a href="javascript:void(0);" id="ToBuyStep2">提交订单</a></div>
    </div>
<script type="text/html" id="goods_list">
    <% var store_cart_list = glist; var shop_goods_num = []; %>
    <% for (var k in store_cart_list) { %>
    <div class="nctouch-cart-container">
        <dl class="nctouch-cart-store bgf borb1 bort1">
            <dt><i class="icon-store"></i><%=store_cart_list[k].shop_name%> <% if (store_cart_list[k].shop_self_support == 'true') { %><span>自营店铺</span> <% } %> <span data-store_id="<%=k%>" class="store-cod-supported" style="display:none;">（该店铺不支持选定收货地址的货到付款）</span>
            </dt>
            <% if(store_cart_list[k].mansong_info.common_id != 0){ %>
            <!--<dd class="store-activity">
                <em>满即送</em>
                <span><%if(store_cart_list[k].mansong_info.rule_discount){%>店铺优惠<%=store_cart_list[k].mansong_info.rule_discount%>。<%}%><%if(store_cart_list[k].mansong_info.goods_name){%><%=store_cart_list[k].mansong_info.goods_name%><%if (store_cart_list[k].mansong_info.goods_image) {%>，送<img src="<%=store_cart_list[k].mansong_info.goods_image%>"><%}%><%}%></span>
            </dd>-->
            <% } %>
        </dl>
        <ul class="nctouch-cart-item pb-0 borb1">
            <% for (var l in store_cart_list[k].goods) { 
                var v1 = store_cart_list[k].goods[l]; 
                if(shop_goods_num[k] == undefined) shop_goods_num[k] = 0;
                shop_goods_num[k] += v1.goods_num * 1; 
            %>
            <li class="buy-item bgf borb1" data-buy_able="<%=v1.buy_able%>" data-goods_name="<%=v1.goods_base.goods_name%>">
                <div class="buy-li clearfix">
                     <div class="goods-pic">
                        <input type="hidden" name="cart_id" value="<%=v1.cart_id%>">
                        <a href="javascript:void(0);">
                            <img src="<%=v1.goods_base.goods_image%>"/>
                        </a>
                    </div>
                    <dl class="goods-info fl pl-20">
                        <dt class="goods-name z-dhwz">
                            <a href="javascript:void(0);">
                            <span>
                                <%=v1.goods_base.goods_name%><% if(!v1.buy_able){ %> <span style="color: #db4453;">无货</span><% } %>
                            </span>
                            </a>
                        </dt>
                        <dd class="goods-type"><%=v1.goods_base.spec_str%></dd>
                    </dl>
                    <div class="goods-subtotal">
                        <span class="goods-price">￥<em><%=sprintf('%.2f', v1.now_price)%></em></span>
                    </div>
                    <div class="goods-num">
                        <em>x<%=v1.goods_num%></em>
                    </div>
                    <div class="notransport" style="display:none;"><p>该商品不支持配送</p></div>
                </div>
                
            </li>
            <% } %>
        </ul>

        <!-- 新增代金券 -->
        <% if(!is_discount) {%>
            <div id="shop_voucher_html_<%= store_cart_list[k].shop_id; %>" class="nctouch-full-mask hide voucher_shop">
            <div class="nctouch-full-mask-bg"></div>
            <div class="nctouch-full-mask-block">
                <div class="header absolute">
                    <div class="header-wrap">
                        <div class="header-l"> <a href="javascript:void(0);"> <i class="back"></i> </a> </div>
                        <div class="header-title">
                            <h1>选择店铺代金券</h1>
                        </div>
                    </div>
                </div>
                <!-- <div id="list-voucher-scroll"> -->

                    <div class="overflow-auto height-set">
             
                        <ul class="z-main">
                            <% if (store_cart_list[k].voucher_base && store_cart_list[k].voucher_base.length > 0) { %>
                            <% if(store_cart_list[k].best_voucher.length > 0 ){ %>
                            <li class="z-main-li1 bort1 borb1 clearfix">
                                <span>不使用店铺代金券</span>
                                <input type="radio" name="v-radio" class="z-main-ipt1 fr" data-id="0" data-price="0" data-shop_id="<%= store_cart_list[k].shop_id %>"/>
                            </li>
                            <% } %>
                            <% var voucher_list = store_cart_list[k].voucher_base; %>  
                            <% for (var a = 0; a < voucher_list.length; a++) { if(voucher_list[a].isable){active='active'}else{active=''} %>
                                <li class="z-main-li2">
                                    <div class="<%= active %> clearfix">
                                        <div class="fl z-main-li2-div1">
                                            <span>￥</span>
                                            <span><%= voucher_list[a].price %></span>
                                            <p>满<%= voucher_list[a].limit %>可用</p>
                                        </div>
                                        <div class="fl z-main-li2-div2">
                                            <div class="one-overflow"><%= voucher_list[a].title %></div>
                                            <div><%= voucher_list[a].end_time.split(" ")[0] %>到期</div>
                                        </div>
                                        <% if(voucher_list[a].isable){ %>
                                        <input type="radio" name="v-radio" <% if(voucher_list[a].id == store_cart_list[k].best_voucher[0].id){ %> checked="checked" <%}%> class="z-main-ipt2 fr" data-id="<%= voucher_list[a].id %>" data-price="<%= voucher_list[a].price %>" data-shop_id="<%= store_cart_list[k].shop_id %>" data-limit="<%= voucher_list[a].limit %>"/>
                                        <% } %>
                                    </div>
                                </li>
                            <% } %>
                            <% } %>
                        </ul>
                    </div>
                <!-- </div> -->
                <% if(store_cart_list[k].voucher_base.length <= 0){ %>
                <!--暂无可用店铺代金券-->
                    <div class="norecord tc">
                        <div class="ziti-store2">
                            <i></i>
                        </div>
                        <p class="fz-30 col9">暂无可用店铺代金券</p>
                    </div>
                <% } %>
            </div>
        </div>
        <% } %>
        <!--  代金券弹出框内容 -->

        <!-- 加价购 -->
        <% if (store_cart_list[k].jia_jia_gou && store_cart_list[k].jia_jia_gou.length > 0) { %>
            <%
                var jia_jia_gou = store_cart_list[k].jia_jia_gou;
            %>
            <div class="order-vou trigger_shop_jjg mt-20 borb1 bg-ff" id="trigger_shop_jjg_<%= store_cart_list[k].shop_id; %>" data-current_index="<%= k; %>">
                <div class="tit-sale">加价购</div>
                <div class="fr sale-cons clearfix" id="jjg_rule_info<%= store_cart_list[k].shop_id; %>">
                    <p class="fr mr-20 w6">
                        <span class="wp100">
                            <!-- 多个加价购多条规则多个换购商品 -->
                            <% for(var a = 0; a < jia_jia_gou.length; a++) { %>
                                <!-- 活动规则 -->
                                <% var rules = jia_jia_gou[a].rule; %>
                                <% for (var b = 0; b < rules.length; b++) { %>
                                    <% if(rules[b].rule_goods_limit > 0){ %>
                                    购物满￥<%= rules[b].rule_price %>即可加价换购<%= rules[b].rule_goods_limit %>件
                                    <% }else{ %>
                                    购物满￥<%= rules[b].rule_price %>即可加价换购
                                    <% } %>
                                    <!-- 活动规则加价商品 -->
                                    <% var redemption_goods = rules[b].redemption_goods; %>
                                    <% for (var c = 0; c < redemption_goods.length; c++) { %>
                                        <%= redemption_goods[c].goods_name; %>
                                    <% } %>
                                <% } %>
                            <% } %>
                            <!-- 多个加价购多条规则多个换购商品 -->
                        </span>
                    </p>
                    <div class="item-more" style="background-position:0;right: 0.33rem;"></div>
                </div>
                <div id="jjg_rule_checked<%= store_cart_list[k].shop_id; %>" style="display: none;" class="fr sales-text"></div>
            </div>

        <!-- 加价购 -->
        <!--  加价购弹出框内容 -->
            <div class="quan-ar mt-20 nctouch-bottom-mask jia-shop-area shop_jjg_html down" id="shop_jjg_html_<%= store_cart_list[k].shop_id; %>">
                <div class="nctouch-bottom-mask-bg"></div>
                <div class="nctouch-bottom-mask-block">
                    <h3 class="tc">加价购</h3>
                    <!-- 加价购活动规则循环 -->
                    <div class="jia-gou-height">
                    <% for(var a = 0; a < jia_jia_gou.length; a++) { %>
                        <!-- 活动规则 -->
                        <% var rules = jia_jia_gou[a].rule; %>
                        <% var promotion_goods = jia_jia_gou[a]; %>
                        <% for (var b = 0; b < rules.length; b++) { %>
                            <div>
                                <p class="tit-tip">
                                    <input type="checkbox" id="shop_jjg<%= rules[b].rule_id; %>" name="shop_jjg<%= store_cart_list[k].shop_id; %>" data-promotion_type="jjg" data-rule_id="<%= rules[b].rule_id; %>" data-rule_goods_limit="<%= rules[b].rule_goods_limit; %>" />
                                    <label for="jjg_rule<%= rules[b].rule_id %>">
                                        <% if(rules[b].rule_goods_limit > 0){ %>
                                        <span>购物满￥<%= rules[b].rule_price; %>，最多可购买<%= rules[b].rule_goods_limit; %>件</span>
                                        <% }else{ %>
                                        <span>购物满￥<%= rules[b].rule_price; %>，即可换购</span>
                                        <% } %>
                                    </label>
                                </p>
                                <ul class="nctouch-cart-item">
                                    <!-- 活动规则加价商品 -->
                                    <% var redemption_goods = rules[b].redemption_goods; %>
                                    <% for (var c = 0; c < redemption_goods.length; c++) { %>
                                        <li class="buy-item">
                                            <div class="bgf6 buy-li clearfix pb-20 clearfix">
                                                <div class="goods-pic goods-pic-size fl">
                                                    <a href="javascript:void(0)">
                                                        <img src="<%= redemption_goods[c].goods_image; %>">
                                                    </a>
                                                </div>
                                                <dl class="goods-info fl pl-20">
                                                    <dt class="goods-name">
                                                        <a href="javascript:void(0)" class="one-overflow">
                                                            <%= redemption_goods[c].goods_name; %>
                                                        </a>
                                                    </dt>
                                                    <dd class="goods-type"></dd>
                                                </dl>
                                                <div class="goods-subtotal">
                                                    <span class="goods-price">￥<em><%= redemption_goods[c].goods_price; %></em></span>
                                                </div>
                                                <div class="goods-num" style="display: none">
                                                    <em></em>
                                                </div>
                                            </div>
                                             <div class="jia-shop clearfix pl-20 pr-20">
                                                <p class="fl">加价购<em>￥<%= redemption_goods[c].redemp_price; %></em></p>
                                                <div class="fr mrt4 JS_operation">
                                                    <span><a href="javascript:void(0)" class="min">-</a></span>
                                                    <span>
                                                        <input type="number" readonly="readonly" value="0" name="jjg_goods<%= rules[b].rule_id; %>" data-jjg_goods_id="<%= redemption_goods[c].goods_id %>" data-promotion_price="<%= redemption_goods[c].redemp_price %>" data-promotion_goods_id="<%=promotion_goods.id%>">
                                                    </span>
                                                    <span><a href="javascript:void(0)" class="max">+</a></span>
                                                </div>
                                            </div>
                                        </li>
                                    <% } %>
                                </ul>

                            </div>
                        <% } %>
                    <% } %>
                    <!-- 加价购活动规则循环 -->
                    <p class="new-btn JS_close"><a href="javascript:void(0)" class="btns">完成</a></p>
                </div>
                </div>
            </div>
        <% } %>
        <!--  加价购弹出框内容 -->
		<!--满即送-->
        <% if(store_cart_list[k].mansong_info.rule_discount){ %>
        <div class="order-vou trigger_shop_jjg  borb1 bgf">
            <div class="tit-sale">满即送</div>
            <div class="fr sale-cons clearfix">
                <p class="fr tlr w6">
                        <span class="wp100 tr">
                           <!--店铺优惠￥999.99 赠品：XXXXX-->
                            <!--满<%= store_cart_list[k].mansong_info.rule_price; %>立减<%= store_cart_list[k].mansong_info.rule_discount; %>：-<%= store_cart_list[k].mansong_info.rule_discount; %>-->
                            店铺优惠￥<%= store_cart_list[k].mansong_info.rule_discount; %> <% if(store_cart_list[k].mansong_info.gift_goods_id){ %>赠品：<%= store_cart_list[k].mansong_info.goods_name %><% } %>
                        </span>
                </p>
            </div>
            <div style="display: none;" class="fr sales-text"></div>
        </div>
        <% } %>
		<!--满即送-->
        <div class="nctouch-cart-subtotal bort1 borb1 mb-20 bgf mt-20">
            <dl id="voucher<%=k%>" style="display: none;">
                <dt>代金券</dt>
                <dd><em id="vourchPrice<%=k%>"></em></dd>
                <input type="hidden" class="voucher_list" id="vourch_id<%=k%>" name="vourch_id" value="">
            </dl>
            <dl class="borb1 pad30 ">
                <dt>物流配送</dt>
                <dd><em id="storeFreight<%=k%>" class="fz-28 price-color">￥<%= store_cart_list[k].freight %></em></dd>
            </dl>
            <!-- 新增店铺代金券 -->
            <div class="nctouch-cart-block borb1">
                <div class="mrl54 pdt2">
                    <% if(is_discount){ %>
                        <a href="javascript:void(0);" class="posr" id="trigger_shop_voucher_<%= store_cart_list[k].shop_id; %>" data-current_index="<%= k; %>">
                            <h3>店铺代金券</h3>
                            <div class="current-con">
                                <p>代金券不与会员折扣共用</p>
                            </div>
                        </a>
                    <% }else{ %>
                        <% if(store_cart_list[k].promotion){ %>
                            <a href="javascript:void(0);" class="posr" id="trigger_shop_voucher_<%= store_cart_list[k].shop_id; %>" data-current_index="<%= k; %>">
                                <h3>店铺代金券</h3>
                                <div class="current-con">
                                    <p>涉及活动商品不可用店铺代金券</p>
                                </div>
                            </a>
                        <% }else{%>
                            <a href="javascript:void(0);" class="posr  trigger_shop_voucher" id="trigger_shop_voucher_<%= store_cart_list[k].shop_id; %>" data-current_index="<%= k; %>">
                                <h3>店铺代金券</h3>
                                <div class="current-con mr53">

                                    <% if(store_cart_list[k].best_voucher.length > 0){ %>
                                    <input type="hidden" name="best_voucher_price" value="<%= store_cart_list[k].best_voucher[0].price %>" data-voucher_id="<%= store_cart_list[k].best_voucher[0].id %>" is-best="1">
                                    <p class="btn-ziti">满<%= store_cart_list[k].best_voucher[0].limit %>减<%= store_cart_list[k].best_voucher[0].price %></p>
                                    <% }else{ %>
                                    <input type="hidden" name="best_voucher_price" value="0" data-voucher_id="0" is-best="0">
                                    <% if(is_discount == 1){ %>
                                    <p>不使用店铺代金券</p>
                                    <% }else{ %>
                                    <p>暂无可用店铺代金券</p>
                                    <% } %>
                                    <% } %>
                                </div>
                                <i class="icon-arrow"></i> </a>
                        <% } %>
                    <% } %>

                </div>
            </div>
           <!-- 代金券end -->

            <dl class="message borb1">
                <dt><label for="storeMessage<%=k%>">买家留言：</label></dt>
                
                <dd>
                    <input type="text" class="remarks buyerMessage2" name="remarks" placeholder="店铺订单留言" rel="<%=store_cart_list[k].shop_id%>" id="storeMessage<%=k%>">
                	<img src="../../images/close_window.png" class="hide icon-X">
                </dd>

            </dl>
        <!--     <% if(store_cart_list[k].shop_cost > 0) { %>
                <div class="store-total borb1">
                    会员折扣：<span><em ><%= -store_cart_list[k].shop_cost %></em></span>
                </div>
            <% } %> -->
            <div class="store-total">
                共<%= shop_goods_num[k] %>件商品 小计：
                <span>
                    <em id="storeTotal<%=k%>" class="js_store_total">
                        <%= store_cart_list[k].sprice %>
                    </em>
                </span>
            </div>
        </div>
        <% if (store_cart_list[k].voucher_base != '') { %>
        <div class="nctouch-bottom-mask down nctouch-bottom-mask<%=k%>">
            <div class="nctouch-bottom-mask-bg"></div>
            <div class="nctouch-bottom-mask-block">
                <!--<div class="nctouch-bottom-mask-tip"><i></i>点击此处返回</div>-->
                <div class="nctouch-bottom-mask-top store-voucher">
                    <i class="icon-store"></i>
                    <%=store_cart_list[k].shop_name%>&nbsp;&nbsp;领取店铺代金券
                    <a href="javascript:void(0);" class="nctouch-bottom-mask-close"><i></i></a>
                </div>
                <div class="nctouch-bottom-mask-rolling nctouch-bottom-mask-rolling<%=k%>">
                    <div class="nctouch-bottom-mask-con">
                        <ul class="nctouch-voucher-list">
                            <% for (var j in store_cart_list[k].voucher_base) {
                            var voucher = store_cart_list[k].voucher_base[j];%>
                            <li>
                                <dl>
                                    <dt class="money">面额<em><%=voucher.voucher_price%></em></dt>
                                    <dd class="need">需消费<%=voucher.voucher_limit%>使用</dd>
                                    <dd class="time">至<%=voucher.voucher_end_date%>前使用</dd>
                                </dl>
                                <a href="javascript:void(0);" class="btn" onclick="getvoucher(<%=voucher.voucher_id%>)" data-tid=<%=voucher.voucher_id%>>领取</a>
                            </li>
                            <% } %>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <% } %>

        <% } %>
</script>
<script type="text/html" id="order-voucher-script">
    <div class="nctouch-bottom-mask-bg"></div>   
</script>
<script type="text/html" id="redpacket_list">
    <% if(rpt_list && rpt_list.length > 0){ %>
        <ul class="z-main">
            <% if(rpt_info.length > 0){ %>
            <li class="z-main-li1 clearfix">
                <span>不使用平台红包</span>
                <input type="radio" name="radio" class="z-main-ipt1 fr" data-price="0" data-id="0"/>
            </li>
            <% } %>
            <% for(var v=0;v < rpt_list.length;v++ ){ if(rpt_list[v].isable){active='active'}else{active=''} %>
                <li class="z-main-li2 clearfix">
                    <div class="fl z-main-li2-div1 <%= active %>">
                        <span>￥</span>
                        <span><%=rpt_list[v].price %></span>
                        <p>满￥<%=rpt_list[v].limit %>可用</p>
                    </div>
                    <div class="fl z-main-li2-div2">
                        <div class="one-overflow"><%=rpt_list[v].title %></div>
                        <div><%=rpt_list[v].end_date.split(" ")[0] %>到期</div>
                    </div>
                    <% if(rpt_list[v].isable){ %>
                    <input type="radio" name="radio" <%if(rpt_list[v].id == rpt_info[0].id && rpt_info.length > 0){%> checked="checked" <%}%> class="z-main-ipt2 fr" data-id="<%=rpt_list[v].id %>" data-limit="<%=rpt_list[v].limit %>" data-price="<%=rpt_list[v].price %>"/>
                    <% } %>
                </li>
            <% } %>
        </ul>
    <% }else{ %>
       <div class="norecord tc">
            <div class="ziti-store2">
                <i></i>
            </div>
            <p class="fz-30 col9">暂无可用平台红包</p>
        </div>
    <% } %>
</script>
<script type="text/html" id="list-address-add-list-script">
    <% var address_list = address; %>
    <% if(address[0].user_address_id != 0){ %>
    <% for (var i=0; i<address_list.length; i++) { %>
    <li <% if ( (!isEmpty(address_id) && address_list[i].user_address_id == address_id) || (isEmpty(address_id) && address_list[i].user_address_default == 1) ) { %>class="selected"<% } %> data-param="{user_address_id:'<%=address_list[i].user_address_id%>',user_address_contact:'<%=address_list[i].user_address_contact%>',user_address_phone:'<%=address_list[i].user_address_phone%>',user_address_area:'<%=address_list[i].user_address_area%>',user_address_area:'<%=address_list[i].user_address_area%>',user_address_area_id:'<%=address_list[i].user_address_area_id%>',user_address_city_id:'<%=address_list[i].user_address_city_id%>',user_address_address:'<%=address_list[i].user_address_address%>'}"> <i></i>
    <dl class='col2'>
        <dt class="clearfix"><p id="" class="fl one-overflow w3"><%=address_list[i].user_address_contact%></p><p id="" class="fl ml-40"><%=address_list[i].user_address_phone%></p><% if (address_list[i].user_address_default == 1) { %><sub class="fl ml-20">默认</sub><% } %></dt>
        <dd><span id="" class="more-overflow wp100"><%=address_list[i].user_address_area %>&nbsp;<%=address_list[i].user_address_address %></span></dd>
    </dl>
    </li>
    <% }} %>
</script>

<script type="text/html" id="invoice-list-script">
    <h3 class="fz-30 col2 pb-20 pl-20 pr-20 pt-30 bgf">发票抬头</h3>
    <div  id="normal" class="fz-0 bgf pl-20 pr-20 borb1">
        <input type="hidden" name="invoice_id" id="invoice_id" <% if (normal.length > 0) {%> value="<%=normal[0].invoice_id%>" <% } %>/>
         <label class="checked personal_lable"  onclick="CompanyTaxNumShow(0,'normal');"  ><i></i>
            <input type="radio" name="inv_ele_title_type" checked="checked" value="personal"/>
            <span >个人</span>
            <input   type="hidden" name="inv_ele_title"  value="个人" />
        </label>
        <label class="input-box company_lable" onclick="CompanyTaxNumShow(1,'normal');"><i></i>
            <input   type="radio" name="inv_ele_title_type"  value="company" data-status="false" class="company_inv"/>
            <span >单位</span>
            <input   type="hidden" name="inv_ele_title"  value="单位" />
           
        </label>
        <ul>
            <li class="ml-0 js-company-tax-num hide">
                <input <% if (normal.length > 0) {%> id="inv_<%=normal[0].invoice_id%>" <% } %> type="text" class="inp_input company_tit" name="inv_ele_title" <% if (normal.length > 0) {%>value="<%=normal[0].invoice_title%>"<% } %> placeholder="请填写单位名称">
            </li>
            <li class="ml-0 form-item js-company-tax-num hide"> 
                <input type="text" class="select inp_input placeholder-red" name="company_tax_num" <% if (normal.length > 0) {%> value="<%=normal[0].invoice_code%>" <% } %> placeholder="请在此填写纳税人识别号">
            </li>
            <li class="form-item ml-0">
                <select id="inc_normal_content" name="inv_normal_content" class="select">
                    <option value="明细">明细</option>
                    <option value="办公用品">办公用品</option>
                    <option value="电脑配件">电脑配件</option>
                    <option value="耗材">耗材</option>
                </select>
                <i class="arrow-down"></i>
            </li>
        </ul>

    </div>

    <div id="electron" style="display: none;">
        <div class="pl-20 pr-20 bgf mb-20 borb1">
            <input type="hidden" name="invoice_id" id="invoice_id" <% if (electron.length > 0) {%> value="<%=electron[0].invoice_id%>" <% } %>/>
            <label class="checked personal_lable" onclick="CompanyTaxNumShow(0,'electron');" ><i></i>
                <input  type="radio" name="inv_ele_title_type" checked="checked" value="personal"/>
                <span >个人</span>
                <input   type="hidden" name="inv_ele_title"  value="个人" />
            </label>
            <label class="input-box company_lable"  onclick="CompanyTaxNumShow(1,'electron');" ><i></i>
                <input type="radio" name="inv_ele_title_type" value="company" data-status="false"  class="company_inv"/>
                
                <span >单位</span>
                <input   type="hidden" name="inv_ele_title"  value="单位" />
            </label>
            <ul class="mb-20">
                <li class="ml-0 js-company-tax-num hide">
                   <input <% if (electron.length > 0) {%> id="inv_<%=electron[0].invoice_id%>" <% } %> type="text" class="inp_input company_tit" name="inv_ele_title" <% if (electron.length > 0) {%>value="<%=electron[0].invoice_title%>"<% } %> placeholder="请填写单位名称"> 
                </li>
                <li class="form-item js-company-tax-num hide ml-0" >
                        <input type="text" class="select inp_input placeholder-red" name="company_tax_num" <% if (electron.length > 0) {%> value="<%=electron[0].invoice_code%>" <% } %> placeholder="请在此填写纳税人识别号">
                </li>
                <li class="form-item ml-0">
                        <select id="inc_content" name="inv_ele_content" class="select">
                            <option value="明细">明细</option>
                            <option value="办公用品">办公用品</option>
                            <option value="电脑配件">电脑配件</option>
                            <option value="耗材">耗材</option>
                        </select>
                        <i class="arrow-down"></i>
                </li>
                
            </ul>
        </div>
        
        <ul class="tic-elc bort1 borb1 pl-20 mb-20 bgf pt-0">
            <h4 class="fz-30 col2 borb1">收票人信息</h4>
            <li class="form-item ml-0 borb1">
                <h4 class="fz-26 col6">收票人手机 </h4>
                <div class="input-box">
                    <input type="text" class="inp bg-transparent h88" name="inv_ele_phone" <% if (electron.length > 0) {%>value="<%=electron[0].invoice_rec_phone%>"<% } %> placeholder="可通过手机号在发票服务平台查询">
                </div>
            </li>
            <li class="form-item ml-0">
                <h4 class="fz-26 col6">收票人邮箱</h4>
                <div class="input-box">
                    <input type="text" class="inp bg-transparent h88" name="inv_ele_email" <% if (electron.length > 0) {%>value="<%=electron[0].invoice_rec_email%>"<% } %> placeholder="用来接收电子发票邮箱，可选填">
                </div>
            </li>
        </ul>
    </div>

    <div  id="addtax" style="display: none;">
        <ul class="form-box form-box-tic">
            <li class="form-item">
                <h4>单位名称</h4>
                <div class="input-box">
                    <input type="text" class="inp" name="inv_tax_title" <% if (addtax.length > 0) {%>value="<%=addtax[0].invoice_company%>"<% } %> placeholder="输入单位名称">
                </div>
            </li>
            <li class="form-item">
                <h4>纳税人识别码</h4>
                <div class="input-box">
                    <input type="text" class="inp" name="inv_tax_code" <% if (addtax.length > 0) {%>value="<%=addtax[0].invoice_code%>"<% } %> placeholder="输入纳税人识别码">
                </div>
            </li>
            <li class="form-item">
                <h4>注册地址</h4>
                <div class="input-box">
                    <input type="text" class="inp" name="inv_tax_address" <% if (addtax.length > 0) {%>value="<%=addtax[0].invoice_reg_addr%>"<% } %> placeholder="输入注册地址">
                </div>
            </li>
            <li class="form-item">
                <h4>注册电话</h4>
                <div class="input-box">
                    <input type="text" class="inp" name="inv_tax_phone" <% if (addtax.length > 0) {%>value="<%=addtax[0].invoice_reg_phone%>"<% } %> placeholder="输入注册电话">
                </div>
            </li>
            <li class="form-item">
                <h4>开户银行</h4>
                <div class="input-box">
                    <input type="text" class="inp" name="inv_tax_bank" <% if (addtax.length > 0) {%>value="<%=addtax[0].invoice_reg_bname%>"<% } %> placeholder="输入开户银行">
                </div>
            </li>
            <li class="form-item">
                <h4>银行账户</h4>
                <div class="input-box">
                    <input type="text" class="inp" name="inv_tax_bankaccount" <% if (addtax.length > 0) {%>value="<%=addtax[0].invoice_reg_baccount%>"<% } %> placeholder="输入银行账户">
                </div>
            </li>
            <li class="form-item">
                <h4>发票内容</h4>
                <div class="input-box">
                    <select id="inc_tax_content" name="inv_tax_content" class="select">
                        <option value="明细">明细</option>
                        <option value="办公用品">办公用品</option>
                        <option value="电脑配件">电脑配件</option>
                        <option value="耗材">耗材</option>
                    </select>
                    <i class="arrow-down"></i>
                </div>
            </li>
            <li class="form-item">
                <h4>收票人姓名</h4>
                <div class="input-box">
                    <input type="text" class="inp" name="inv_tax_recname" <% if (addtax.length > 0) {%>value="<%=addtax[0].invoice_rec_name%>"<% } %> placeholder="输入收票人姓名">
                </div>
            </li>
            <li class="form-item">
                <h4>收票人手机</h4>
                <div class="input-box">
                    <input type="text" class="inp" name="inv_tax_recphone" <% if (addtax.length > 0) {%>value="<%=addtax[0].invoice_rec_phone%>"<% } %> placeholder="输入收票人手机">
                </div>
            </li>
            <li class="form-item">
                <h4>收票人省份</h4>
                <div class="input-box">
                    <input type="text" id="invoice_area_info" class="inp" name="invoice_tax_rec_province" <% if (addtax.length > 0) {%>value="<%=addtax[0].invoice_rec_province%>" data-areaid1="<%=addtax[0].invoice_province_id%>" data-areaid2="<%=addtax[0].invoice_city_id%>" data-areaid3="<%=addtax[0].invoice_area_id%>" data-areaid="<%=addtax[0].invoice_province_id%>" <% } %> placeholder="输入收票人省份">
                </div>
            </li>
            <li class="form-item">
                <h4>详细地址</h4>
                <div class="input-box">
                    <input type="text" class="inp" name="inv_tax_rec_addr" <% if (addtax.length > 0) {%>value="<%=addtax[0].invoice_goto_addr%>"<% } %> placeholder="输入收票人详细地址">
                </div>
            </li>
        </ul>
    </div>

</script>
<script>

</script>

<script type="text/javascript" src="../../js/zepto.min.js"></script>
<script type="text/javascript" src="../../js/template.js"></script>
<script type="text/javascript" src="../../js/common.js"></script>
<script type="text/javascript" src="../../js/iscroll.js"></script>
<script type="text/javascript" src="../../js/simple-plugin.js"></script>
<script type="text/javascript" src="../..//js/fly/requestAnimationFrame.js"></script>
<script type="text/javascript" src="../../js/fly/zepto.fly.min.js"></script>
<script type="text/javascript" src="../../js/tmpl/order_payment_common.js"></script>
<script type="text/javascript" src="../../js/tmpl/buy_step1.js"></script>
<script type="text/javascript" src="../../js/tmpl/invoice.js"></script>

</body>
</html>
<?php 
include __DIR__.'/../../includes/footer.php';
?>

<?php
    include __DIR__ . '/../../includes/header.php';
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
        <title>确认订单</title>
        <link rel="stylesheet" type="text/css" href="../../css/base.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_common.css">
        <link rel="stylesheet" type="text/css" href="../../css/nctouch_cart.css">
        <link rel="stylesheet" type="text/css" href="../../css/private-store.css" />
        <style>
            .jia-shop .fr a.min {
                background: #d5d5d5;
            }
            
            .jia-shop .fr a.min.disabled, .jia-shop .fr a.max.disabled {
                background: #eeeeee;
            }
            
            header, .nctouch-cart-bottom {
                position: absolute !important;
            }
        </style>
    </head>
    <body>
    <header id="header" class="fixed">
        <div class="header-wrap">
            <div class="header-l"><a href="javascript:history.go(-1)"> <i class="back"></i> </a></div>
            <div class="header-title">
                <h1>确认订单</h1>
            </div>
            <div class="header-r"><a id="header-nav" href="javascript:void(0);"><i class="more"></i><sup></sup></a></div>
        </div>
        <div class="nctouch-nav-layout">
            <div class="nctouch-nav-menu"><span class="arrow"></span>
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
                <h5>请输入所知的F码序列号并提交验证<br />
                    系统效验后可继续完成下单</h5>
                <input type="text" name="fcode" id="fcode" placeholder="" />
                <p class="fcode_error_tip" style="display:none;color:red;"></p>
                <a href="javascript:void(0);" class="submit">提交验证</a></div>
        </div>
    </div>
    <div class="nctouch-main-layout mb20">
        <div class="nctouch-cart-block">
            <!--正在使用的默认地址Begin-->
            <div class="nctouch-cart-add-default borb1"><a href="javascript:void(0);" id="list-address-valve"><i class="icon-add"></i>
                    <dl>
                        <input type="hidden" class="inp" name="address_id" id="address_id" />
                        <dt class="clearfix"><p class="fl one-overflow w4" id="true_name"></p>
                            <p id="mob_phone" class="fl ml-60"></p></dt>
                        <dd><span id="address" class="z-dhwz wt12"></span></dd>
                    </dl>
                    <i class="icon-arrow"></i></a></div>
            <!--正在使用的默认地址End-->
        </div>
        <!--选择收货地址Begin-->
        <div id="list-address-wrapper" class="nctouch-full-mask hide">
            <div class="nctouch-full-mask-bg"></div>
            <div class="nctouch-full-mask-block">
                <div class="header absolute">
                    <div class="header-wrap">
                        <div class="header-l"><a href="javascript:void(0);"> <i class="back"></i> </a></div>
                        <div class="header-title">
                            <h1>收货地址管理</h1>
                        </div>
                    </div>
                </div>
                <div class="nctouch-main-layout" style="display: block; position: absolute; top: 0; right: 0; left: 0; bottom:2rem; overflow: hidden; z-index: 1;" id="list-address-scroll">
                    <ul class="nctouch-cart-add-list" id="list-address-add-list-ul">
                    </ul>
                </div>
                <div id="addresslist" class="mt10" style="position: absolute; right: 0; left: 0; bottom: 0; z-index: 1;"><a href="javascript:void(0);" class="btn-l" id="new-address-valve">新增收货地址</a></div>
            </div>
        </div>
        <!--选择收货地址End-->
        <!--新增收货地址Begin-->
        <div id="new-address-wrapper" class="nctouch-full-mask hide">
            <div class="nctouch-full-mask-bg"></div>
            <div class="nctouch-full-mask-block">
                <div class="header absolute">
                    <div class="header-wrap">
                        <div class="header-l"><a href="javascript:void(0);"> <i class="back"></i> </a></div>
                        <div class="header-title">
                            <h1>新增收货地址</h1>
                        </div>
                    </div>
                </div>
                <div class="nctouch-main-layout" id="new-address-scroll">
                    <div class="nctouch-inp-con">
                        <form id="add_address_form">
                            <ul class="form-box">
                                <li class="form-item">
                                    <h4>收货人姓名</h4>
                                    <div class="input-box">
                                        <input type="text" class="inp" name="true_name" id="vtrue_name" autocomplete="off" oninput="writeClear($(this));" />
                                        <span class="input-del"></span></div>
                                </li>
                                <li class="form-item">
                                    <h4>联系手机</h4>
                                    <div class="input-box">
                                        <input type="tel" class="inp" name="mob_phone" id="vmob_phone" autocomplete="off" oninput="writeClear($(this));" />
                                        <span class="input-del"></span></div>
                                </li>
                                <li class="form-item">
                                    <h4>地区选择</h4>
                                    <div class="input-box">
                                        <input name="area_info" type="text" class="inp" id="varea_info" autocomplete="off" unselectable="on" onfocus="this.blur()" onchange="btn_check($('form'));" readonly />
                                    </div>
                                </li>
                                <li class="form-item">
                                    <h4>详细地址</h4>
                                    <div class="input-box">
                                        <input type="text" class="inp" name="vaddress" id="vaddress" autocomplete="off" oninput="writeClear($(this));" />
                                        <span class="input-del"></span></div>
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
        
        <!--管理发票信息Begin-->
        <div id="invoice-wrapper" class="nctouch-full-mask hide">
            <div class="nctouch-full-mask-bg"></div>
            <div class="nctouch-full-mask-block">
                <div class="header absolute">
                    <div class="header-wrap">
                        <div class="header-l"><a href="javascript:void(0);"> <i class="back"></i> </a></div>
                        <div class="header-title">
                            <h1>修改发票信息</h1>
                        </div>
                    </div>
                </div>
                <div class="nctouch-main-layout" style="width:100%;height:100%; overflow-y:scroll;">
                    <div class="nctouch-sel-box">
                        <div class="sel-con">
                            <div class="tic-tab"><a href="javascript:void(0);" class="sel" id="invoice-noneed">不需要开发票</a></div>
                            <div class="tic-tab"><a href="javascript:void(0);" id="invoice-need">需要开发票</a></div>
                        </div>
                    </div>
                    <div id="invoice-div" class="">
                        <div class="nctouch-inp-con" id="invoice_add" style="display:none">
                            <ul class="form-box">
                                <li class="form-item mrl0 bgf5">
                                    <div id="invoice_type" class="input-box btn-style">
                                        <label class="checked">
                                            <input type="radio" checked="checked" name="inv_title_select" value="normal" id="norm">
                                            增值税普通发票 </label>
                                        <label>
                                            <input type="radio" name="inv_title_select" value="electronics" id="electronics">
                                            电子发票 </label>
                                        <label>
                                            <input type="radio" name="inv_title_select" value="increment" id="increment">
                                            增值税专用发票 </label>
                                    </div>
                                </li>
                            </ul>
                            
                            <ul id="invoice-list" class="nctouch-sel-list bort1 borb1">
                            </ul>
                        </div>
                        
                        <a href="javascript:void(0);" class="btn-l mt10">确定</a>
                        <div style="width:100%; height: 50px;"></div>
                    </div>
                
                </div>
            </div>
        </div>
        <!--管理发票信息End-->
        
        
        <!--选择付款方式Begin-->
        <div id="select-payment-wrapper" class="nctouch-bottom-mask down hp100">
            <div class="nctouch-full-mask-bg"></div>
            <div class="nctouch-bottom-mask-block">
                <div class="z-zffs">支付方式</div>
                <div class="z-zffs2 clearfix">
                    <p>在线支付</p>
                    <input type="radio" name="pay-selected" id="payment-online" value="1" checked="checked" />
                </div>
                <div class="z-zffs2 clearfix z-mdzf">
                    <p>货到付款</p>
                    <input type="radio" name="pay-selected" id="payment-offline" value="2" />
                </div>
                <div class="z-zffs3 z-bgc JS_close">确定</div>
            </div>
        </div>
        <!--选择付款方式End-->
        
        <!--商品列表Begin-->
        <div id="goodslist_before" class="mt5">
            <div id="deposit">
                <div class="nctouch-cart-container">
                    <dl class="nctouch-cart-store bgf borb1 bort1">
                        <dt><i class="icon-store"></i><span id='shop_name'></span></dt>
                    </dl>
                    <ul class="nctouch-cart-item">
                        <li class="buy-item bgf borb1">
                            <div class="buy-li clearfix">
                                <div class="goods-pic pr-20 pl-0">
                                    <a href="" id='goods_image_a'>
                                        <img src="" id='goods_image' />
                                    </a>
                                </div>
                                <dl class="goods-info fl">
                                    <dt class="goods-name more-overflow">
                                        <a href="" id='goods_name_a'>
                                        
                                        </a>
                                    </dt>
                                    <dd class="goods-type" id='goods_spec_str'></dd>
                                </dl>
                                <div class="goods-subtotal">
                                    <span class="goods-price">￥<em id='goods_price'></em></span>
                                </div>
                                <div class="goods-num">
                                    <em id='goods_num'></em>
                                </div>
                                <div class="notransport" style="display:none;"><p>该商品不支持配送</p></div>
                            </div>
                        </li>
                    </ul>
                    
                    <div class="nctouch-cart-subtotal bort1 borb1 mb-20 bgf">
                        <dl class="borb1 pad30">
                            <dt>物流配送</dt>
                            <dd><em id="storeFreight"></em></dd>
                        </dl>
                        <dl class="message borb1">
                            <dt><label for="storeMessage<%=k%>">买家留言：</label></dt>
                            
                            <dd>
                                <input type="text" class="remarks buyerMessage2" name="remarks" placeholder="店铺订单留言" rel="<%=store_cart_list[k].shop_id%>" id="storeMessage<%=k%>">
                                <img src="../../images/close_window.png" class="hide icon-X">
                            </dd>
                        
                        </dl>
                        <div class="store-total">
                            本店合计：<span><em id="storeTotal" class="js_store_total"></em></span>元
                        </div>
                    </div>
                
                
                </div>
            </div>
            <!--商品列表End-->
            <!--付款方式Begin-->
            <div class="nctouch-cart-block bort1">
                <div class="mrl54 pdb2">
                    <a href="javascript:void(0);" class="posr" id="select-payment-valve">
                        <h3>支付方式：</h3>
                        <div class="current-con mr53">在线付款</div>
                        <input type="hidden" name="pay-selected" id="pay-selected" value="1">
                        <!--<div class="current-con">货到付款</div>-->
                        <i class="icon-arrow" id="pay_selected_icon"></i> </a>
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
                            <input type="hidden" name="invoice_id" value='0' id='order_invoice_id' />
                            <input type="hidden" name="order_invoice_title" value='个人' id='order_invoice_title' />
                            <input type="hidden" name="order_invoice_content" value='' id='order_invoice_content' />
                        </div>
                        <i class="icon-arrow"></i> </a>
                </div>
            </div>
            <!--发票信息End-->
            
            <div class="nctouch-cart-bottom" style="position:fixed !important">
                <div class="total"><span id="online-total-wrapper"></span>
                    <dl class="total-money">
                        <dt>合计：</dt>
                        <dd>￥<em id="totalPayPrice"></em></dd>
                    </dl>
                </div>
                <div class="check-out"><a href="javascript:void(0);" id="ToBuyStep2">提交订单</a></div>
            </div>
            <!--底部总金额固定层End-->
            <div class="nctouch-bottom-mask down">
                <div class="nctouch-bottom-mask-bg"></div>
                <div class="nctouch-bottom-mask-block">
                    <div class="nctouch-bottom-mask-tip"><i></i>点击此处返回</div>
                    <div class="nctouch-bottom-mask-top">
                        <p class="nctouch-cart-num">本次交易需在线支付<em id="onlineTotal">0.00</em>元</p>
                        <p style="display:none" id="isPayed"></p>
                        <a href="javascript:void(0);" class="nctouch-bottom-mask-close"><i></i></a></div>
                    <div class="nctouch-inp-con nctouch-inp-cart">
                        <ul class="form-box" id="internalPay">
                            <p class="rpt_error_tip" style="display:none;color:red;"></p>
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
                                <div class="input-box"><span class="txt">输入支付密码</span>
                                    <input type="password" class="inp" id="paymentPassword" autocomplete="off" />
                                    <span class="input-del"></span></div>
                                <a href="../member/member_paypwd_step1.html" class="input-box-help" style="display:none"><i>i</i>尚未设置</a></li>
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
                        <div class="pay-btn"><a href="javascript:void(0);" id="toPay" class="btn-l">确认支付</a></div>
                    </div>
                </div>
            </div>
        </div>
        
        <script type="text/html" id="order-voucher-script">
            <div class="nctouch-bottom-mask-bg"></div>
        
        </script>
        
        <script type="text/html" id="invoice-list-script">
            <div id="normal">
                <input type="hidden" name="invoice_id" id="invoice_id" <% if (normal.length > 0) {%> value="<%=normal[0].invoice_id%>" <% } %>/>
                <label class="checked personal_lable" onclick="CompanyTaxNumShow(0,'normal');"><i></i>
                    <input type="radio" name="inv_ele_title_type" checked="checked" value="personal" />
                    <span>个人</span>
                    <input type="hidden" name="inv_ele_title" value="个人" />
                </label>
                <label class="input-box company_lable" onclick="CompanyTaxNumShow(1,'normal');"><i></i>
                    <input type="radio" name="inv_ele_title_type" value="company" />
                    <span>企业</span>
                    <input <% if (normal.length > 0) {%> id="inv_<%=normal[0].inv_id%>" <% } %> type="text" class="inp_input" name="inv_ele_title" <% if (normal.length > 0) {%>value="<%=normal[0].invoice_title%>"<% } %> placeholder="输入企业发票抬头">
                </label>
                
                
                <ul class="form-box">
                    <li class="form-item js-company-tax-num hide">
                        <h4>企业税号</h4>
                        <div class="input-box">
                            <input type="text" class="select inp_input" id='company_tax_num' name="company_tax_num" <% if (normal.length > 0) {%> value="<%=normal[0].invoice_code%>" <% } %> placeholder="输入企业税号">
                        </div>
                    </li>
                    <li class="form-item">
                        <h4>发票内容</h4>
                        <div class="input-box">
                            <select id="inc_normal_content" name="inv_normal_content" class="select">
                                <option value="明细">明细</option>
                                <option value="办公用品">办公用品</option>
                                <option value="电脑配件">电脑配件</option>
                                <option value="耗材">耗材</option>
                            </select>
                            <i class="arrow-down"></i>
                        </div>
                    </li>
                </ul>
            
            </div>
            
            <div id="electron" style="display: none;">
                <input type="hidden" name="invoice_id" id="invoice_id" <% if (electron.length > 0) {%> value="<%=electron[0].invoice_id%>" <% } %>/>
                <label class="checked personal_lable" onclick="CompanyTaxNumShow(0,'electron');"><i></i>
                    <input type="radio" name="inv_ele_title_type" checked="checked" value="personal" />
                    <span>个人</span>
                    <input type="hidden" name="inv_ele_title" value="个人" />
                </label>
                <label class="input-box company_lable" onclick="CompanyTaxNumShow(1,'electron');"><i></i>
                    <input type="radio" name="inv_ele_title_type" value="company" />
                    
                    <span>企业</span>
                    <input <% if (electron.length > 0) {%> id="inv_<%=electron[0].inv_id%>" <% } %> type="text" class="inp_input" name="inv_ele_title" <% if (electron.length > 0) {%>value="<%=electron[0].invoice_title%>"<% } %> placeholder="输入企业发票抬头">
                </label>
                <ul class="form-box">
                    <li class="form-item js-company-tax-num hide">
                        <h4>企业税号</h4>
                        <div class="input-box">
                            <input type="text" class="select inp_input" id='company_tax_num' name="company_tax_num" <% if (electron.length > 0) {%> value="<%=electron[0].invoice_code%>" <% } %> placeholder="输入企业税号">
                        </div>
                    </li>
                    <li class="form-item">
                        <h4>发票内容</h4>
                        <div class="input-box">
                            <select id="inc_content" name="inv_ele_content" class="select">
                                <option value="明细">明细</option>
                                <option value="办公用品">办公用品</option>
                                <option value="电脑配件">电脑配件</option>
                                <option value="耗材">耗材</option>
                            </select>
                            <i class="arrow-down"></i>
                        </div>
                    </li>
                    <li class="form-item">
                        <h4>手 &nbsp;机 &nbsp;号 </h4>
                        <div class="input-box">
                            <input type="text" class="inp" name="inv_ele_phone" <% if (electron.length > 0) {%>value="<%=electron[0].invoice_rec_phone%>"<% } %> placeholder="输入收票人手机号">
                        </div>
                    </li>
                    <li class="form-item">
                        <h4>电子邮箱</h4>
                        <div class="input-box">
                            <input type="text" class="inp" name="inv_ele_email" <% if (electron.length > 0) {%>value="<%=electron[0].invoice_rec_email%>"<% } %> placeholder="输入收票人电子邮箱">
                        </div>
                    </li>
                </ul>
                <style>
                    .inp_input {
                        border: 0 none !important;
                        color: #000;
                        font-size: 0.6rem;
                        line-height: 0.95rem;
                        min-height: 0.95rem;
                        width: 90%;
                    }
                </style>
            </div>
            
            <div id="addtax" style="display: none;">
                <ul class="form-box">
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
        
        <script type="text/javascript" src="../../js/zepto.min.js"></script>
        <script type="text/javascript" src="../../js/template.js"></script>
        <script type="text/javascript" src="../../js/common.js"></script>
        <script type="text/javascript" src="../../js/iscroll.js"></script>
        <script type="text/javascript" src="../../js/simple-plugin.js"></script>
        <script type="text/javascript" src="../..//js/fly/requestAnimationFrame.js"></script>
        <script type="text/javascript" src="../../js/fly/zepto.fly.min.js"></script>
        <script type="text/javascript" src="../../js/tmpl/order_payment_common.js"></script>
        <script type="text/javascript" src="../../js/tmpl/buy_step2.js"></script>
        <script type="text/javascript" src="../../js/tmpl/invoice.js"></script>
        <script type="text/javascript" src="../../js/tmpl/integral_product_buy.js"></script>
    
    </body>
    </html>
<?php
    include __DIR__ . '/../../includes/footer.php';
?>
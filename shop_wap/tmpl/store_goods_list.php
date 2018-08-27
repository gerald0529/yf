<script>
    window.load = loadCss(WapSiteUrl+"/css/nctouch_products_list.css");
    window.load = loadCss(WapSiteUrl+"/css/nctouch_common.css");
</script>
<style>
    #search-btn{
        position: absolute;
        right: 2rem;
        top: 0;
        font-size: 0.7rem;
        color: #666;
        display: inline-block;
        line-height: 1.8rem;
    }
</style>
<div class="goods-search-list-nav bort1">
    <ul id="nav_ul">
        <li><a href="javascript:void(0);" class="current" id="sort_default">综合排序<i></i></a></li>
        <li><a href="javascript:void(0);" id="sort_salesnum">销量优先</a></li>
        <li><a href="javascript:void(0);" id="search_adv">筛选<i></i></a></li>
    </ul>
    <div class="browse-mode"><a href="javascript:void(0);" id="show_style"><span class="browse-list"></span></a></div>
</div>
<div id="sort_inner" class="goods-sort-inner hide borb1">
    <span><a href="javascript:void(0);" class="cur" id="default">综合排序<i></i></a></span>
    <span><a href="javascript:void(0);" id="pricedown">价格从高到低<i></i></a></span>
    <span><a href="javascript:void(0);" id="priceup">价格从低到高<i></i></a></span>
    <span><a href="javascript:void(0);" id="collect">人气排序<i></i></a></span>
</div>
<div class="list" nc_type="product_content">
    <ul class="goods-secrch-list" id="product_list"></ul>
</div>

<!--筛选部分-->
<div class="nctouch-full-mask hide">
    <div class="nctouch-full-mask-bg"></div>
    <div class="nctouch-full-mask-block">
        <div class="header">
            <div class="header-wrap">
                <div class="header-l"> <a href="javascript:void(0);"><i class="back"></i></a></div>
                <div class="header-title">
                    <h1>商品筛选</h1>
                </div>
                <div class="header-r"><a href="javascript:void(0);" id="reset" class="text">重置</a> </div>
            </div>
        </div>
        <div class="nctouch-main-layout secreen-layout" id="list-items-scroll">
            <dl>
                <dt>价格区间</dt>
                <dd>
                    <span class="inp-balck"><input type="text" id="price_from" nctype="price" pattern="[0-9]*" class="inp" placeholder="最低价"/></span><span class="line"></span><span class="inp-balck"><input nctype="price" type="text" id="price_to" pattern="[0-9]*" class="inp" placeholder="最高价"/></span>
                </dd>
            </dl>
            <div class="bottom"> <a href="javascript:void(0);" class="btn-l" id="search_submit">筛选商品</a> </div>
        </div>
    </div>
</div>

<script type="text/html" id="goods_list_tpl">
    <% var goods_list = data.items; %>
    <% if(typeof(goods_list)!=='undefined' && goods_list.length >0){%>
        <% for (var k in goods_list) { var v = goods_list[k];%>
        <li class="goods-item" goods_id="<%=v.goods_id[0].goods_id;%>">
    				<span class="goods-pic">
    					<a href="product_detail.html?goods_id=<%=v.goods_id[0].goods_id;%>">
    						<img src="<%=v.common_image;%>"/>
    					</a>
    				</span>
            <dl class="goods-info">
                <dt class="goods-name">
                    <a href="product_detail.html?goods_id=<%=v.goods_id[0].goods_id;%>">
                        <h4><%=v.common_name;%></h4><h6></h6>
                    </a>
                </dt>
                <dd class="goods-sale">
                    <a href="product_detail.html?goods_id=<%=v.goods_id[0].goods_id;%>">
    					<span class="goods-price">￥<em><%=v.common_price;%></em>
    						<% if (v.sole_flag) {%>
    							<span class="phone-sale"><i></i>手机专享</span>
    						<% } %>
    					</span>
                        <% if (v.common_is_virtual == '1') { %>
                            <span class="sale-type">虚</span>
                        <% } else { %>
                            <% if (v.is_presell == '1') { %>
                                <span class="sale-type">预</span>
                            <% } %>
                            <% if (v.is_fcode == '1') { %>
                                <span class="sale-type">F</span>
                            <% } %>
                        <% } %>

                        <% if(v.group_flag || v.xianshi_flag){ %>
                        <span class="sale-type">降</span>
                        <% } %>
                    </a>
                </dd>
                <dd class="goods-assist">
                    <a href="product_detail.html?goods_id=<%=v.goods_id[0].goods_id;%>">
                        <span class="goods-sold">销量&nbsp;<em><%=v.common_salenum;%></em></span>
                    </a>
                </dd>
            </dl>
        </li>
        <%}%>
        <!-- <li class="loading"><div class="spinner"><i></i></div>商品数据读取中...</li> -->
    <% }else { %>
    <div class="nctouch-norecord search">
        <div class="norecord-ico"><i></i></div>
        <dl>
            <dt>没有找到任何相关信息</dt>
            <dd>搜索其它商品名称或筛选项...</dd>
        </dl>
        <a href="javascript:void(0);" onclick="get_list({'order_val':'<%=order%>','order_key':'<%=key%>'},true)" class="btn">查看全部商品</a>
    </div>
    <% } %>
</script>

<script>
    window.load = loadJs(WapSiteUrl+"/js/tmpl/store_goods_list.js");
</script>
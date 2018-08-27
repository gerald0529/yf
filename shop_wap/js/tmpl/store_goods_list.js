var shop_id = getQueryString("shop_id");
var keyword = decodeURIComponent(getQueryString("keyword"));
var order_key = getQueryString("key");
var order_val = getQueryString("order");
var price_from = getQueryString("price_from");
var price_to = getQueryString("price_to");
var stc_id = getQueryString("stc_id");
var prom_type = getQueryString("prom_type");
//var load_class_storegoodslist = new ncScrollLoad;
var isload_goods = false;
var pagesize = pagesize;
var curpage = 1;
var firstRow = 0;
var hasmore = true;
var footer = false;
$(function () {
    $("#show_style").click(function () {
        var e = $('[nc_type="product_content"]');
        if ($(e).hasClass("grid")) {
            $(this).find("span").removeClass("browse-grid").addClass("browse-list");
            $(e).removeClass("grid").addClass("list")
        } else {
            $(this).find("span").addClass("browse-grid").removeClass("browse-list");
            $(e).addClass("grid").removeClass("list")
        }
    });
    $("#sort_default").click(function () {
        if ($("#sort_inner").hasClass("hide")) {
            $("#sort_inner").removeClass("hide")
        } else {
            $("#sort_inner").addClass("hide")
        }
    });
    $("#sort_inner").find("a").click(function () {
        $("#sort_inner").addClass("hide").find("a").removeClass("cur");
        var e = $(this).addClass("cur").text();
        $("#sort_default").addClass("current").html(e + "<i></i>");
        $("#sort_salesnum").removeClass("current")
    });
    //综合排序   onclick="get_list({'order_val':'0','order_key':'0'})"
    $("#default").click(function (){
        order_val = 0;
        order_key = 0;
        hasmore = true;
        $("#product_list").html('');
        curpage = 1;
        firstRow = 0;
        get_list();
    });
    //价格从高到底 onclick="get_list({'order_val':'2','order_key':'2'})"
    $("#pricedown").click(function (){
        order_val = 2;
        order_key = 2;
        hasmore = true;
        $("#product_list").html('');
        curpage = 1;
        firstRow = 0;
        get_list();
    });
    //价格从底到高 onclick="get_list({'order_val':'1','order_key':'2'})"
    $("#priceup").click(function (){
        order_val = 1;
        order_key = 2;
        hasmore = true;
        $("#product_list").html('');
        curpage = 1;
        firstRow = 0;
        get_list();
    });
    //人气排序 onclick="get_list({'order_val':'2','order_key':'5'})"
    $("#collect").click(function (){
        order_val = 2;
        order_key = 5;
        hasmore = true;
        $("#product_list").html('');
        curpage = 1;
        firstRow = 0;
        get_list();
    });
    //销量优先
    $("#sort_salesnum").click(function () {
        order_val = 2;
        order_key = 3;
        $(this).addClass("current");
        $("#sort_default").removeClass("current");
        $("#sort_inner").addClass("hide").find("a").removeClass("cur");
        hasmore = true;
        $("#product_list").html('');
        curpage = 1;
        firstRow = 0;
        get_list()
    });
    $("#product_list").on("click", '[nc_type="goods_more_link"]', function () {
        var e = $(this).attr("param_id");
        if (e <= 0) {
            $.sDialog({skin: "green", content: "参数错误", okBtn: false, cancelBtn: false});
            return false
        }
        var r = getCookie("key");
        if (!r) {
            $("#goods_more_" + e).show()
        }
        var o = $(this);
        if ($(o).hasClass("goods_more_loading")) {
            return
        }
        $(o).addClass("goods_more_loading");
        if ($("#goods_more_" + e).hasClass("goods_more_has")) {
            $("#goods_more_" + e).show();
            $(o).removeClass("goods_more_loading");
            return
        }
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?ctl=Buyer_Favorites&met=getGoodsFI&typ=json",
            data: {k: r, u: getCookie('id'), fav_id: e},
            dataType: "json",
            success: function (r) {
                if (r.data.favorites_info) {
                    $("#goods_more_" + e + " [nc_type='goods_cancelfav']").show();
                    $("#goods_more_" + e + " [nc_type='goods_addfav']").hide()
                } else {
                    $("#goods_more_" + e + " [nc_type='goods_cancelfav']").hide();
                    $("#goods_more_" + e + " [nc_type='goods_addfav']").show()
                }
                $("#goods_more_" + e).addClass("goods_more_has");
                $("#goods_more_" + e).show();
                $(o).removeClass("goods_more_loading")
            }
        })
    }).on("click", '[nc_type="goods_more_con"]', function () {
        var e = $(this).attr("param_id");
        $("#goods_more_" + e).hide()
    }).on("click", '[nc_type="goods_addfav"]', function () {
        var e = $(this).attr("param_id");
        favoriteGoods(e);
        $(this).hide();
        $("#goods_more_" + e + " [nc_type='goods_cancelfav']").show()
    }).on("click", '[nc_type="goods_cancelfav"]', function () {
        var e = $(this).attr("param_id");
        dropFavoriteGoods(e);
        $(this).hide();
        $("#goods_more_" + e + " [nc_type='goods_addfav']").show()
    });
    $.animationLeft({valve: "#search_adv", wrapper: ".nctouch-full-mask"});
    $("#search_submit").click(function () {
        var e = false;
        if ($("#price_from").val() != "") {
            price_from = $("#price_from").val();
            e = true
        } else {
            price_from = ""
        }
        if ($("#price_to").val() != "") {
            price_to = $("#price_to").val();
            e = true
        } else {
            price_to = ""
        }
        if (e) {
            $("#search_adv").addClass("current");
            hasmore = true;
            $("#product_list").html('');
            curpage = 1;
            firstRow = 0;
            get_list();
            get_list()
        } else {
            $("#search_adv").removeClass("current")
        }
        $(".nctouch-full-mask").addClass("hide").removeClass("left")
    });
    $('input[nctype="price"]').on("blur", function () {
        if ($(this).val() != "" && !/^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test($(this).val())) {
            $(this).val("")
        }
    });
    $("#reset").click(function () {
        $('input[nctype="price"]').val("")
    });
    get_list()
    $(window).scroll(function ()
    {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1)
        {
            get_list()
        }
    });
});
function get_list()
{
    $(".loading").remove();
    if (!hasmore)
    {
        return false
    }
    hasmore = false;
    param = {};
    param.pagesize = pagesize;
    param.curpage = curpage;
    param.firstRow = firstRow;
    param.id = shop_id;
    if (keyword) {
        param.search = keyword
    }
    if (price_from) {
        param.price_from = price_from
    }
    if (price_to) {
        param.price_to = price_to
    }
    if (stc_id) {
        param.catid = stc_id
    }
    if (prom_type) {
        param.prom_type = prom_type
    }
    if (order_key) {
        if ( order_key == 2 ) {
            param.order = 'common_price';
        } else if ( order_key == 3 ) {
            param.order = 'common_salenum';
        } else if ( order_key == 5 ) {
            param.order = 'common_collect';
        }
    }
    if (order_val) {
        if ( order_val == 2 ) {
            param.sort = 'desc';
        } else if ( order_val == 1 ) {
            param.sort = 'asc';
        }
    }

    $.getJSON(ApiUrl + "/index.php?ctl=Shop&met=goodsList&typ=json" + window.location.search.replace("?", "&"), param, function (e)
    {
        if (!e)
        {
            e = [];
            e.datas = [];
            e.data.goods_list = []
        }
        $(".loading").remove();
        curpage++;
        console.info(e);
        var r = template.render("goods_list_tpl", e);

        $("#product_list").append(r);
       if(e.data.page < e.data.total)
       {
           firstRow = e.data.records;
           hasmore = true;
       }
        else
       {
           hasmore = false;
       }
    })
}

//function get_list(e, r) {
//    if (isload_goods) {
//        $.sDialog({skin: "green", content: "搜索过于频繁，请稍后再进行搜索", okBtn: false, cancelBtn: false});
//        return false
//    }
//    isload_goods = true;
//    if (!r) {
//        r = false
//    }
//    if (r == false && e) {
//        if (e.keyword) {
//            keyword = e.keyword
//        }
//        if (e.price_from) {
//            price_from = e.price_from
//        }
//        if (e.price_to) {
//            price_to = e.price_to
//        }
//        if (e.stc_id) {
//            stc_id = e.stc_id
//        }
//        if (e.prom_type) {
//            prom_type = e.prom_type
//        }
//    }
//    if (e) {
//        if (e.order_key) {
//            order_key = e.order_key
//        }
//        if (e.order_val) {
//            order_val = e.order_val
//        }
//    }
//    param = {};
//    param.id = shop_id;
//    if (r == false) {
//        if (keyword) {
//            param.search = keyword
//        }
//        if (price_from) {
//            param.price_from = price_from
//        }
//        if (price_to) {
//            param.price_to = price_to
//        }
//        if (stc_id) {
//            param.catid = stc_id
//        }
//        if (prom_type) {
//            param.prom_type = prom_type
//        }
//    } else {
//        price_from = "";
//        price_to = ""
//    }
//    if (order_key) {
//        if ( order_key == 2 ) {
//            param.order = 'common_price';
//        } else if ( order_key == 3 ) {
//            param.order = 'common_salenum';
//        } else if ( order_key == 5 ) {
//            param.order = 'common_collect';
//        }
//    }
//    if (order_val) {
//        if ( order_val == 2 ) {
//            param.sort = 'desc';
//        } else if ( order_val == 1 ) {
//            param.sort = 'asc';
//        }
//    }
//    param = {};
//    param.pagesize = pagesize;
//    param.curpage = curpage;
//    param.firstRow = firstRow;
//    
//    load_class_storegoodslist.loadInit({
//        url: ApiUrl + "/index.php?ctl=Shop&met=goodsList&typ=json",
//        getparam: param,
//        tmplid: "goods_list_tpl",
//        containerobj: $("#product_list"),
//        data: {key: order_key, order: order_val},
//        iIntervalId: true,
//        callback: function () {
//            isload_goods = false
//        }
//    })
//}
var page = pagesize;
var curpage = 1;
var firstRow = 0;
var hasMore = true;
var footer = false;
var keyword = decodeURIComponent(getQueryString("keyword"));
var key = getQueryString("key");
var order = getQueryString("order");
var actgoods = getQueryString("actgoods");
var virtual = getQueryString("virtual");
var myDate = new Date;
var reset = true;
var searchTimes = myDate.getTime();
var uid = getQueryString("uid");
$(function () {
    /* var e = getCookie("key");
    if (!e) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html"
    } */
    
    $("#search_btn").click(function () {
        curpage = 1;
        firstRow = 0;
        hasMore = true;
        reset = true;
        get_list();
    });
    
    //排序下拉事件
    $("#sort_default").click(function () {
        if ($("#sort_inner").hasClass("hide")) {
            $("#sort_inner").removeClass("hide");
        }
        else {
            $("#sort_inner").addClass("hide");
        }
    });
    
    //排序按钮
    $("#nav_ul").find("a").click(function () {
        $(this).addClass("current").parent().siblings().find("a").removeClass("current");
        if (!$("#sort_inner").hasClass("hide") && $(this).parent().index() > 0) {
            $("#sort_inner").addClass("hide");
        }
    });
    
    //排序下拉框内容
    $("#sort_inner").find("a").click(function () {
        $("#sort_inner").addClass("hide").find("a").removeClass("cur");
        var e = $(this).addClass("cur").text();
        $("#sort_default").html(e + "<i></i>");
    });
    
    get_list();
    
    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {
            get_list();
        }
    });
    //search_adv()
});

function get_list() {
    $(".loading").remove();
    
    if (!hasMore) {
        return false;
    }
    hasMore = false;
    
    param = {};
    param.rows = page;
    param.page = curpage;
    param.firstRow = firstRow;
    $("#show_style").click(function () {
        if ($("#product_list").hasClass("grid")) {
            $(this).find("span").removeClass("browse-grid").addClass("browse-list");
            $("#product_list").removeClass("grid").addClass("list");
        }
        else {
            $(this).find("span").addClass("browse-grid").removeClass("browse-list");
            $("#product_list").addClass("grid").removeClass("list");
        }
    });
    keyword = $("#goodskey").val();
    param.keywords = keyword;
    
    if (key != "") {
        param.actorder = key;
    }
    
    if (order != "") {
        param.act = order;
    }
    
    if (actgoods != "") {
        param.op2 = "active";
    }
    if (virtual != "") {
        param.isvirtual = virtual;
    }
    
    $.getJSON(ApiUrl + "/index.php?ctl=Shop&met=directsellerGoodsList&typ=json" + window.location.search.replace("?", "&"), param, function (e) {
        if (!e) {
            e = [];
            e.datas = [];
            e.data.goods_list = [];
        }
        $(".loading").remove();
        curpage++;
        
        var r = template.render("home_body", e);
        
        if (reset) {
            reset = false;
            $("#product_list .goods-secrch-list").html(r);
        } else {
            $("#product_list .goods-secrch-list").append(r);
        }
        var r = template.render("store_banner_tpl", e);
        $("#store_banner").html(r);
        create_ewm(e.data.shop_qrcode);
        
        if (e.data.page < e.data.total) {
            firstRow = e.data.page * page;
            hasMore = true;
        }
        else {
            hasMore = false;
        }
    });
}

function init_get_list(e, r) {
    order = e;
    key = r;
    curpage = 1;
    firstRow = 0;
    hasMore = true;
    
    $("#product_list .goods-secrch-list").html("");
    $("#footer").removeClass("posa");
    get_list();
}

function create_ewm(e) {
    
    //1、创建qrcode对象, width 和 height 是最后显示二维码图片的宽和高
    var qrcode = new QRCode(document.getElementById("qrcode"), {
        width: 200,
        height: 200
    });
    
    //2、判断输入框是否有地址，如果有则生成二维码
    function makeCode() {
        qrcode.makeCode(e);
    }
    
    makeCode();
    
};

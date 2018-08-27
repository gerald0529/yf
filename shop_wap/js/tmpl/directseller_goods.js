var pagesize = pagesize;
var curpage = 1;
var firstRow = 0;
var hasmore = true;
var footer = false;
var keyword = decodeURIComponent(getQueryString("keyword"));
var key = getQueryString("key");
var order = getQueryString("order");
var actgoods = getQueryString("actgoods");
var virtual = getQueryString("virtual");
var myDate = new Date;
var reset = true;
var searchTimes = myDate.getTime();
$(function () {
    var e = getCookie("key");
    if (!e) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html"
    }

    $("#search_btn").click(function () {
        reset = true;
        curpage = 1;
        firstRow = 0;
        hasmore = true;
        $("#product_list .goods-secrch-list").html("");
        get_list();
    });

    //排序下拉事件
    $("#sort_default").click(function () {
        if ($("#sort_inner").hasClass("hide")) {
            $("#sort_inner").removeClass("hide")
        }
        else {
            $("#sort_inner").addClass("hide")
        }
    });

    //排序按钮
    $("#nav_ul").find("a").click(function () {
        $(this).addClass("current").parent().siblings().find("a").removeClass("current");
        if (!$("#sort_inner").hasClass("hide") && $(this).parent().index() > 0) {
            $("#sort_inner").addClass("hide")
        }
    });

    //排序下拉框内容
    $("#sort_inner").find("a").click(function () {
        $("#sort_inner").addClass("hide").find("a").removeClass("cur");
        var e = $(this).addClass("cur").text();
        $("#sort_default").html(e + "<i></i>")
    });

    get_list();

    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {
            get_list()
        }
    });
    //search_adv()
});

function get_list() {
    $(".loading").remove();

    if (!hasmore) //没有更多
    {
        return false
    }

    param = {};
    param.rows = pagesize;  //每页显示行数
    param.page = curpage;   //当前页数
    param.firstRow = firstRow;  //开始行数
    param.k = getCookie('key'); //用户
    param.u = getCookie('id');  //id

    keyword = $('#goodskey').val(); //名称搜索
    if (keyword != "") {
        param.keywords = keyword
    }

    if (key != "") {
        param.actorder = key
    }

    if (order != "") {
        param.act = order
    }

    if (actgoods != '') {
        param.op2 = 'active'
    }
    if (virtual != '') {
        param.isvirtual = virtual
    }

    $.getJSON(ApiUrl + "/index.php?ctl=Shop&met=directsellerGoodsList&typ=json" + window.location.search.replace("?", "&"), param, function (e) {
        if (!e) {
            e = [];
            e.datas = [];
            e.data.goods_list = []
        }
        $(".loading").remove();
        curpage++;

        var r = template.render("home_body", e);
        $("#product_list .goods-secrch-list").append(r);

        if (e.data.page < e.data.total) {
            firstRow = e.data.page * pagesize;
            hasmore = true;
        } else {
            hasmore = false;
        }
    })
}

function init_get_list(e, r) {
    order = e;
    key = r;
    curpage = 1;
    firstRow = 0;
    hasmore = true;
    $("#product_list .goods-secrch-list").html("");
    $("#footer").removeClass("posa");
    get_list()
}

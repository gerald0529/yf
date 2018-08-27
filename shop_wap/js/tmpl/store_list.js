var page = pagesize;
var curpage = 1;
var firstRow = 0;
var hasmore = true;
var footer = false;
var or = getQueryString("or");
var district = getQueryString("district");
var plat = getQueryString("plat");
var keywords = getQueryString("keywords");
var k = getCookie("key");
var u = getCookie('id');
if (keywords !== null) {
    keywords = decodeURI(keywords);
}
var param = {};
var default_shop_list = '';

if (!getCookie('sub_site_id')) {
    addCookie('sub_site_id', 0, 0);
}
var sub_site_id = getCookie('sub_site_id');
$(function () {
    $.getJSON(ApiUrl + "/index.php?ctl=Index&met=getSearchKeyList&typ=json", function (e) {
        // alert(1111);
        // console.log(e.data);
        default_shop_list = e.data.default_shop_list;
        // console.log(default_shop_list);
        $("#keyword").attr('placeholder', default_shop_list);
    });

    $("#keyword").val(keywords);

    $.animationLeft({valve: "#search_adv", wrapper: ".nctouch-full-mask", scroll: "#list-items-scroll"});
    $.getJSON(ApiUrl + "/index.php?ctl=Base_District&met=district&typ=json", param, function (e) {
        var search_items = template.render("search_items", e);
        $("#list-items-scroll").append(search_items);
    })

    get_list();

    // $("#header").on("click", ".search-input", function () {
    //     $("#keyword").val('');
    // });

    $("#nav_ul").find("a").click(function () {
        $(this).addClass("current").parent().siblings().find("a").removeClass("current");
        if (!$("#sort_inner").hasClass("hide") && $(this).parent().index() > 0) {
            $("#sort_inner").addClass("hide")
        }
    });

    $("#header").on("click", ".search-btn", function () {
        var keyword = $('#keyword').val();
        if (!keyword) {
            keyword = default_shop_list ? default_shop_list : keywords;
        }
        window.location.href = WapSiteUrl + "/tmpl/store-list.html" + '?keywords=' + encodeURI(keyword);
    });

    $("#reset").click(function () {
        district = '';
        $('a[nctype="items"]').removeClass("current");
    });

    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {
            get_list()
        }
    });
});

function get_list() {
    if (!hasmore) {
        return false
    }
    hasmore = false;

    param.rows = page;
    param.page = curpage;
    param.firstRow = firstRow;
    param.or = or;
    param.keywords = keywords;
    param.district = district;
    param.plat = plat;

    if (this.near) {
        param.coordinate = window.coordinate;
        var url = ApiUrl + "/index.php?ctl=Shop_Index&met=near&typ=json";
    } else {
        var url = ApiUrl + "/index.php?ctl=Shop_Index&met=index&typ=json&ua=wap&sub_site_id=" + sub_site_id + "&k=" + k + "&u=" + u;
    }

    $.getJSON(url, param, function (e) {
        if (!e) {
            e = [];
            e.data.items = [];
        }
        var html = template.render("store-lists-area", e);
        $(".store-lists-area").append(html);
        console.log(e);
        curpage++;
        if (e.data.page < e.data.total) {
            firstRow = e.data.records;
            hasmore = true;
        } else {
            hasmore = false;
        }
    })
}

function search_adv() {
    window.location.href = WapSiteUrl + "/tmpl/shop_list.html" + '?or=' + or + '&plat=' + plat + '&price=' + price + '&district=' + district;
}

function init_get_list(type, value) {
    this.keywords = keywords;

    if (type == "or") {
        this.or = value;
        this.plat = '';
        this.near = '';
    } else if (type == "plat") {
        this.plat = value;
        this.or = '';
        this.near = '';
    } else if (type == "default") {
        this.plat = '';
        this.or = '';
        this.near = '';
    } else if (type == "near") {
        if (window.coordinate) {
            this.plat = '';
            this.or = '';
            this.near = value;
        } else {
            alert('尚未取到位置信息，功能暂无法使用。');
            return;
        }
    }

    curpage = 1;
    firstRow = 0;
    hasmore = true;

    $(".store-lists-area").html("");
    $("#footer").removeClass("posa");
    get_list();
}

function init_rows(type, value, obj) {
    if (type == "district_name") {
        this.district = value;
    }
    $(obj).parents().children().removeClass("current");
    $(obj).toggleClass("current");
}

// 收藏店铺变成已收藏
function collectShop(shop_id) {
    var k = getCookie("key");
    var u = getCookie("id");
    if (k && u) {
        $.getJSON(ApiUrl + '/index.php?ctl=Shop&met=addCollectShop&typ=json', {
            shop_id: shop_id,
            k: k,
            u: u
        }, function (data) {
            if (data.status == 200) {
                a = $(".shop_" + shop_id).html();
                $(".shop_" + shop_id).html(a * 1 + 1);
                var btn_which = ".store_save_btn_" + shop_id;
                $(btn_which).addClass("active");

                // $(btn_which).find(".iconfont").removeClass("icon-save").addClass("icon-star");
                $(btn_which).html("<i class=\"iconfont icon-star align-middle fz-26 mr-10\"></i>已收藏");
            }
            // $.sDialog({
            //     skin: "red",
            //     content: data.data.msg,
            //     okBtn: false,
            //     cancelBtn: false
            // });
        });
    } else {
        $.sDialog({skin: "red", content: "请先登录！", okBtn: false, cancelBtn: false});
    }
}

// 取消收藏变成 收藏店铺
// 暂时先不做~

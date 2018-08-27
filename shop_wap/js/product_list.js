var pagesize = pagesize;
var curpage = 1;
var firstRow = 0;
var hasmore = true;
var footer = false;
var keyword = decodeURIComponent(getQueryString("keyword"));
var cat_id = getQueryString("cat_id");
var brand_id = getQueryString("brand_id");
var key = getQueryString("key");
var order = getQueryString("order");
var area_id = getQueryString("area_id");
var price_from = getQueryString("price_from");
var price_to = getQueryString("price_to");
var own_shop = getQueryString("own_shop");
var other_shop = getQueryString("other_shop");
var gift = getQueryString("gift");
var actgoods = getQueryString("actgoods");
var virtual = getQueryString("virtual");
var ci = getQueryString("ci");
var myDate = new Date;
var searchTimes = myDate.getTime();
if (!getCookie('sub_site_id')) {
    addCookie('sub_site_id', 0, 0);
}
var sub_site_id = getCookie('sub_site_id');
$(function () {
    var handler = function () {
        event.preventDefault();
        event.stopPropagation();
    };
    $(document).on("click", "#ldg_lockmask", function () {
        $(this).remove();
        $(document.body).css("overflow", "auto");
        document.body.removeEventListener('touchmove', handler, false);
        document.body.removeEventListener('wheel', handler, false);
    });


    $.animationLeft(
        {
            valve: "#search_adv",
            wrapper: ".nctouch-full-mask",
            scroll: "#list-items-scroll",
            openCallback: function () {
                $(".JS-search").css("z-index", 1999);
                $("body").append("<div id=\"ldg_lockmask\"></div>");
                $(document.body).css("overflow", "hidden");
                document.body.addEventListener('touchmove', handler, false);
                document.body.addEventListener('wheel', handler, false);
            }
        }
    );

    $('#ldg_lockmask').bind("touchmove", function (e) {
        e.preventDefault();
    });
    $("#header").on("click", ".header-inp", function () {
        location.href = WapSiteUrl + "/tmpl/search.html?keyword=" + keyword
    });
    if (keyword != "") {
        $("#keyword").html(keyword)
    }
    $("#show_style").click(function () {
        if ($("#product_list").hasClass("grid")) {
            $(this).find("span").addClass("browse-grid").removeClass("browse-list");
            $("#product_list").removeClass("grid").addClass("list")
        }
        else {
            $(this).find("span").removeClass("browse-grid").addClass("browse-list");
            $("#product_list").addClass("grid").removeClass("list")
        }
    });
    $("#sort_default").click(function () {
        if ($("#sort_inner").hasClass("hide")) {
            $("#sort_inner").removeClass("hide")
        }
        else {
            $("#sort_inner").addClass("hide")
        }
    });
    $("#nav_ul").find("a").click(function () {
        $(this).addClass("current").parent().siblings().find("a").removeClass("current");
        if (!$("#sort_inner").hasClass("hide") && $(this).parent().index() > 0) {
            $("#sort_inner").addClass("hide")
        }
    });
    $("#sort_inner").find("a").click(function () {
        $("#sort_inner").addClass("hide").find("a").removeClass("cur");
        var e = $(this).addClass("cur").text();
        $("#sort_default").html(e + "<i></i>")
    });
    $("#product_list").on("click", ".goods-store a", function () {
        var e = $(this);
        var r = $(this).attr("data-id");
        var i = $(this).text();
        $.getJSON(ApiUrl + "/index.php?act=store&op=store_credit", {shop_id: r}, function (t) {
            var a = "<dl>" + '<dt><a href="store.html?shop_id=' + r + '">' + i + '<span class="arrow-r"></span></a></dt>' + '<dd class="' + t.datas.store_credit.store_desccredit.percent_class + '">描述相符：<em>' + t.datas.store_credit.store_desccredit.credit + "</em><i></i></dd>" + '<dd class="' + t.datas.store_credit.store_servicecredit.percent_class + '">服务态度：<em>' + t.datas.store_credit.store_servicecredit.credit + "</em><i></i></dd>" + '<dd class="' + t.datas.store_credit.store_deliverycredit.percent_class + '">发货速度：<em>' + t.datas.store_credit.store_deliverycredit.credit + "</em><i></i></dd>" + "</dl>";
            e.next().html(a).show()
        })
    }).on("click", ".sotre-creidt-layout", function () {
        $(this).hide()
    });
    get_list();
    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {
            get_list();
        }
    });
    search_adv()
});

function get_list() {
    // $(".loading").remove();
    if (!hasmore) {
        return false
    }
    hasmore = false;
    param = {};
    param.pagesize = pagesize;
    param.curpage = curpage;
    param.firstRow = firstRow;
    if (cat_id != "") {
        param.cat_id = cat_id
    }
    else if (keyword != "") {
        param.keywords = keyword
    }
    else if (brand_id != "") {
        param.brand_id = brand_id
    }
    if (key != "") {
        param.actorder = key
    }
    if (order != "") {
        param.act = order
    }
    if (price_from != "") {
        param.price_from = price_from
    }
    if (price_to != "") {
        param.price_to = price_to
    }
    if (own_shop != "") {
        param.op3 = 'ziying'
    }
    if (other_shop != "") {
        param.op3 = 'ruzhu'
    }
    if (actgoods != '') {
        param.op2 = 'active'
    }
    if (virtual != '') {
        param.isvirtual = virtual
    }
//定位到上次浏览的商品
    var goods_pos = getCookie('goods_pos');
    param.pos = goods_pos
    $.getJSON(ApiUrl + "/index.php?ctl=Goods_Goods&met=goodslist&typ=json&ua=wap&sub_site_id=" + sub_site_id + window.location.search.replace("?", "&"), param, function (e) {
       console.log(e.data);
        if (!e) {
            e = [];
            e.datas = [];
            e.data.goods_list = []
        }
       
        $(".loading").remove();
        curpage++;
        e['pagesize'] = pagesize;

        var r = template.render("home_body", e);
       
        $("#product_list .goods-secrch-list").append(r);
        //hasmore = e.hasmore
        if (e.data.page < e.data.total) {
            firstRow = e.data.records;
            hasmore = true;
        } else {
            hasmore = false;
            if (e.data.totalsize >= 5) {
                $(".loading").html("已加载全部了！");
            } else {
                $(".loading").hide();
            }
        }

        if (goods_pos != 'undefined' && goods_pos > 0) {
            goods_pos = goods_pos - 1;
            window.location.hash = "#goods_pos_" + goods_pos;
        }
    });
}

function search_adv() {
    $.getJSON(ApiUrl + "/index.php?ctl=Index&met=getSearchAdv&typ=json", function (e) {
        var r = e.data;
        $("#list-items-scroll").html(template.render("search_items", r));
        if (area_id) {
            $("#area_id").val(area_id)
        }
        if (price_from) {
            $("#price_from").val(price_from)
        }
        if (price_to) {
            $("#price_to").val(price_to)
        }
        if (own_shop) {
            $("#own_shop").addClass("current")
        }
        if (other_shop) {
            $("#other_shop").addClass("current")
        }
        if (actgoods) {
            $("#actgoods").addClass("current")
        }
        if (virtual) {
            $("#virtual").addClass("current")
        }
        if (ci) {
            var i = ci.split("_");
            for (var t in i) {
                $('a[name="ci"]').each(function () {
                    if ($(this).attr("value") == i[t]) {
                        $(this).addClass("current")
                    }
                })
            }
        }
        $("#search_submit").click(function () {
            var e = "?keyword=" + keyword, r = "";
            if (typeof($("#area_id").val()) !== 'undefined' && $("#area_id").val() !== '') {
                e += "&transport_id=" + $("#area_id").val();
            }

            if ($("#price_from").val() != "") {
                e += "&price_from=" + $("#price_from").val()
            }
            if ($("#price_to").val() != "") {
                e += "&price_to=" + $("#price_to").val()
            }
            if ($("#own_shop")[0].className == "current") {
                e += "&own_shop=1"
            }
            if ($("#other_shop")[0].className == "current") {
                e += "&other_shop=1"
            }
            if ($("#actgoods")[0].className == "current") {
                e += "&actgoods=1"
            }
            if ($("#virtual")[0].className == "current") {
                e += "&virtual=1"
            }
            if (typeof(cat_id) !== 'undefined' && cat_id !== '') {
                e += "&cat_id=" + cat_id
            }
            $('a[name="ci"]').each(function () {
                if ($(this)[0].className == "current") {
                    r += $(this).attr("value") + "_"
                }
            });
            if (r != "") {
                e += "&ci=" + r
            }
            window.location.href = WapSiteUrl + "/tmpl/product_list.html" + e
        });
        $('a[nctype="items"]').click(function () {
            var e = new Date;
            if (e.getTime() - searchTimes > 300) {
                $(this).toggleClass("current");
                searchTimes = e.getTime()
            }
        });
        $('input[nctype="price"]').on("blur", function () {
            if ($(this).val() != "" && !/^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test($(this).val())) {
                $(this).val("")
            }
        });
        $(".reset").click(function () {
            $('a[nctype="items"]').removeClass("current");
            $('input[nctype="price"]').val("");
            $("#area_id").val("");
        })
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
    get_list();
}

$('#list-items-scroll').on('click', '#area_info', function () {
    $.areaSelected({
        success: function (a) {
            $("#area_info").val(a.area_info).attr({
                "data-areaid1": a.area_id_1,
                "data-areaid2": a.area_id_2,
                "data-areaid3": a.area_id_3,
                "data-areaid": a.area_id,
                "data-areaid2": a.area_id_2 == 0 ? a.area_id_1 : a.area_id_2
            })
        }
    });
});

$(function () {
    $(window).scroll(function (e) {
        addCookie('goodsListPosition', document.documentElement.scrollTop);
    })

    if (getQueryString('jump') == 1) {
        var goodsListPosition = getCookie('goodsListPosition');

        var s = setInterval(function () {
            $(window).scrollTop(goodsListPosition);
            if (document.documentElement.scrollTop == goodsListPosition) {
                clearInterval(s);
            }
        }, 1);
    }
})

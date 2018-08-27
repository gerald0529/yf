var page = pagesize;
var curpage = 1;
var firstRow = 0;
var hasmore = true;
var footer = false;
var keyword = decodeURIComponent(getQueryString("keyword"));
var myDate = new Date;
var order = getQueryString("orderby");
var grade = getQueryString("level");
var only_self = getQueryString("is_self");
var points_min = getQueryString("points_min");
var points_max = getQueryString("points_max");
var price = getQueryString("price");
var require_once = true;

$(function () {

    $.animationLeft({valve: "#search_adv", wrapper: ".nctouch-full-mask", scroll: "#list-items-scroll"});

    get_list();
    search_adv();

    $("#nav_ul").find("a").click(function ()
    {
        $(this).addClass("current").parent().siblings().find("a").removeClass("current");
        if (!$("#sort_inner").hasClass("hide") && $(this).parent().index() > 0)
        {
            $("#sort_inner").addClass("hide")
        }
    });

    //上架时间
    $("#sort_default").click(function ()
    {
        if ($("#sort_inner").hasClass("hide"))
        {
            $("#sort_inner").removeClass("hide")
        }
        else
        {
            $("#sort_inner").addClass("hide")
        }
    });

    //优惠券面额
    $("#price_default").click(function ()
    {
        if ($("#price_inner").hasClass("hide"))
        {
            $("#price_inner").removeClass("hide")
        }
        else
        {
            $("#price_inner").addClass("hide")
        }
    });

    $("#sort_inner").find("a").click(function ()
    {
        $("#sort_inner").addClass("hide").find("a").removeClass("cur");
        var e = $(this).addClass("cur").text();
        $("#sort_default").html(e + "<i></i>")
    });

    $("#price_inner").on("click", "a", function ()
    {
        $("#price_inner").addClass("hide").find("a").removeClass("cur");
        var e = $(this).addClass("cur").text();
        $("#price_default").html(e + "<i></i>")
    });

    $("#show_style").click(function ()
    {
        if ($("#product_list").hasClass("grid"))
        {
            $(this).find("span").removeClass("browse-grid").addClass("browse-list");
            $("#product_list").removeClass("grid").addClass("list");
            $("img").each(function(i, e){
                var src = e.src.replace(/!430x430\./, "!116x116.");
                $(e).attr("src", src);
            });
        }
        else
        {
            $(this).find("span").addClass("browse-grid").removeClass("browse-list");
            $("#product_list").addClass("grid").removeClass("list");
            $("img").each(function(i, e){
                var src = e.src.replace(/!116x116\./, "!430x430.");
                $(e).attr("src", src);
            });
        }
    });

    $("#product_list").on('click', "a[nctype='exchange_integrate']", function() {
        if(!getCookie("id")){
            $.sDialog({skin: "red", content: '用户尚未登录！', okBtn: false, cancelBtn: false});
            return false;
        }
        var v_id = $(this).data("vid");
        $.ajax({
            url: ApiUrl + "/index.php?ctl=Voucher&met=getVoucherById&typ=json",
            data: {vid: v_id},
            type: 'post',
            dataType: 'json',
            success: function(data) {
                if ( data.status == 200 ) {
                    var data = data.data, voucher_t_eachlimit = data.voucher_t_eachlimit;
                    var limit = '';
                    if(voucher_t_eachlimit == 0){
                        limit = "每个ID领取无限制";
                    }else{
                        limit = "每个ID限领" + voucher_t_eachlimit + "张";
                    }

                    $.sDialog({ skin: "red",
                        content: limit,
                        okBtn: true,
                        cancelBtn: true,
                        okFn: function () {
                            $.ajax({
                                url: ApiUrl + "/index.php?ctl=Voucher&met=receiveVoucher&typ=json",
                                data: {vid: v_id,k: getCookie("key"),u: getCookie("id")},
                                type: 'post',
                                dataType: 'json',
                                success: function (data) {
                                    $.sDialog({
                                        skin: "red",
                                        content: data.msg,
                                        okBtn: false,
                                        cancelBtn: false
                                    });
                                }
                            })
                        }
                    });
                } else {
                    $.sDialog({skin: "red", content: "网络异常", okBtn: false, cancelBtn: false});
                }
            }
        });
    });

    $(window).scroll(function ()
    {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1)
        {
            get_list()
        }
    });
});


//搜索条件
function init_get_list(type, value)
{
    if ( type == "order" ) {
        this.order = value;
    } else if ( type == "grade" ) {
        this.grade = value;
    } else if ( type == "price" ) {
        this.price = value;
    }

    curpage = 1;
    firstRow = 0;
    hasmore = true;
    $("#product_list .goods-secrch-list").html("");
    $("#footer").removeClass("posa");
    get_list()
}

function get_list()
{
    $(".loading").remove();
    if (!hasmore)
    {
        return false
    }
    hasmore = false;
    var param = {};
    param.rows = page;
    param.page = curpage;
    curpage++;
    console.log(curpage);
    param.firstRow = firstRow;

    param.orderby = order;
    param.level = grade;
    param.price = price;

    $.getJSON(ApiUrl + "/index.php?ctl=Voucher&met=vList&typ=json" + window.location.search.replace("?", "&"), param, function (data) {
        $(".loading").remove();

        if (data.status == 200) {
            data = data.data;

            var html = template.render("voucher_list", data);
            $("#product_list > .goods-secrch-list").append(html);

            if ( require_once ) {
                require_once = false;
                var searchHtml = template.render("price_search", data);
                $("#price_inner").append(searchHtml);
            }

            if(data.voucher.page < data.voucher.total)
            {
                firstRow = data.records;
                hasmore = true;
            }
            else
            {
                hasmore = false;
            }
        }
    });
}

function search_adv()
{
    $("#list-items-scroll").html(template.render("search_items"));

    if ( points_min > -1 ) $("#points_min").val(points_min);
    if ( points_max > -1 ) $("#points_max").val(points_max);
    if ( only_self ) $("#is_self").addClass("current");

    $('a[nctype="items"]').click(function ()
    {
        $(this).toggleClass("current");
    });
    $('input[nctype="points"]').on("blur", function ()
    {
        if ($(this).val() != "" && !/^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test($(this).val()))
        {
            $(this).val("")
        }
    });
    $("#reset").click(function ()
    {
        $('a[nctype="items"]').removeClass("current");
        $('input[nctype="points"]').val("");
    });

    $("#search_submit").click(function ()
    {
        var param = "?";
        if ($("#is_self").hasClass("current")) {
            param += "is_self=1&", only_self = 1;
        } else {
            only_self = 0;
        }
        if ($("#points_min").val() >= 0) {
            var min_val = $("#points_min").val();
            param += "points_min=" + min_val + "&", points_min = min_val;
        } else {
            points_min = -1;
        }
        if ($("#points_max").val() >= 0) {
            var max_val = $("#points_max").val();
            param += "points_max=" + max_val, points_max = max_val;
        } else {
            points_max = -1;
        }

        window.location.href = WapSiteUrl + "/tmpl/voucher_list.html" + param
    });
}
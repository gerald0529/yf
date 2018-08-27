var page = pagesize;
var curpage = 1;
var firstRow = 10;
var hasmore = true;
var footer = false;
var order = getQueryString("orderby");
var state = getQueryString("state");
var area_id = getQueryString("area_id");
var price = getQueryString("price");
var groupbuy_cat_id = getQueryString("groupbuy_cat_id");
var groupbuy_type = getQueryString("groupbuy_type");
$(function ()
{
      
    //搜索链接
    $('#group_buy_search').attr('href',WapSiteUrl+'/tmpl/search.html?f=groupbuy&groupbuy_type='+groupbuy_type);
    
    $.animationLeft({valve: "#search_adv", wrapper: ".nctouch-full-mask", scroll: "#list-items-scroll"});

    get_list();

    $("#reset").click(function ()
    {
        price = '';
        area_id = '';
        $('a[nctype="items"]').removeClass("current");
    });

    //会员等级
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

    $("#grade_default").click(function ()
    {
        if ($("#grade_inner").hasClass("hide"))
        {
            $("#grade_inner").removeClass("hide")
        }
        else
        {
            $("#grade_inner").addClass("hide")
        }
    });

    $("#nav_ul").find("a").click(function ()
    {
        $(this).addClass("current").parent().siblings().find("a").removeClass("current");
        if (!$("#sort_inner").hasClass("hide") && $(this).parent().index() > 0)
        {
            $("#sort_inner").addClass("hide")
        }
    });

    $("#sort_inner").find("a").click(function ()
    {
        $("#sort_inner").addClass("hide").find("a").removeClass("cur");
        var e = $(this).addClass("cur").text();
        $("#sort_default").html(e + "<i></i>")
    });

    $("#grade_inner").find("a").click(function ()
    {
        $("#grade_inner").addClass("hide").find("a").removeClass("cur");
        var e = $(this).addClass("cur").text();
        $("#grade_default").html(e + "<i></i>")
    });

    $("#show_style").click(function ()
    {
        if ($("#product_list").hasClass("grid"))
        {
            $(this).find("span").removeClass("browse-grid").addClass("browse-list");
            $("#product_list").removeClass("grid").addClass("list");
        }
        else
        {
            $(this).find("span").addClass("browse-grid").removeClass("browse-list");
            $("#product_list").addClass("grid").removeClass("list");
        }
    });

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
    if (!hasmore)
    {
        return false
    }
    hasmore = false;
    var param = {};
    param.rows = page;
    param.page = curpage;
    param.firstRow = firstRow;
    param.orderby = order;
    param.state = state;
    param.cat_id = groupbuy_cat_id;
    param.price = price;
    param.area_id = area_id;

    if(groupbuy_type == 1){
        $('.group_buy').html('线上团');
        $.getJSON(ApiUrl + "/index.php?ctl=GroupBuy&met=groupBuyList&typ=json"+ window.location.search.replace("?", "&"), param, function (e)
        {
            if (!e)
            {
                e = [];
                e.data.cat = [];
                e.data.groupbuy_goods = []
            }

            e.data.cat.type = 1;
            e.data.cat.nav = e.data.cat.physical;

            if(curpage == 1){
                var swiper_container_nav = template.render("swiper-container-nav", e);
                $(".swiper-container-nav").empty();
                $(".swiper-container-nav").append(swiper_container_nav);
                var mySwiper = new Swiper('.swiper-container-nav', {
                    slidesPerView: 4

                })

                var maskG = template.render("maskG", e);
                $(".maskG").empty();
                $(".maskG").append(maskG);
                $(".X").click(function () {
                    $(".maskG").css("display", "none");
                    // $(".righttubiao a").removeClass("topg");
                    $(".righttubiao a").addClass("bottG");
                    $("body").css("overflow", "none");
                })

                var search_items = template.render("search_items", e);
                $("#list-items-scroll").empty();
                $("#list-items-scroll").append(search_items);
            }

            var html = template.render("goods-secrch-list", e);
            $("#product_list > .goods-secrch-list").append(html);
            var _TimeCountDown = $(".fnTimeCountDown");
            _TimeCountDown.fnTimeCountDown();
            curpage++;

            if(e.data.groupbuy_goods.page < e.data.groupbuy_goods.total)
            {
                firstRow = e.data.groupbuy_goods.records;
                hasmore = true;
            }
            else
            {
                hasmore = false;
            }
        })
    }else{
        $('.group_buy').html('虚拟团');
        $.getJSON(ApiUrl + "/index.php?ctl=GroupBuy&met=vrGroupBuyList&typ=json"+ window.location.search.replace("?", "&"), param, function (e)
        {
            if (!e)
            {
                e = [];
                e.data.cat = [];
                e.data.groupbuy_goods = []
            }

            e.data.cat.type = 2;
            e.data.cat.nav = e.data.cat.virtual;

            if(curpage == 1){
                var swiper_container_nav = template.render("swiper-container-nav", e);
                $(".swiper-container-nav").empty();
                $(".swiper-container-nav").append(swiper_container_nav);
                var mySwiper = new Swiper('.swiper-container-nav', {
                    slidesPerView:4

                })

                var maskG = template.render("maskG", e);
                $(".maskG").empty();
                $(".maskG").append(maskG);
                $(".X").click(function () {
                    $(".maskG").css("display", "none");
                    // $(".righttubiao a").removeClass("topg");
                    $(".righttubiao a").addClass("bottG");
                    $("body").css("overflow", "none");
                })

                var search_items = template.render("search_items", e);
                $("#list-items-scroll").empty();
                $("#list-items-scroll").append(search_items);
            }

            var html = template.render("goods-secrch-list", e);
            $("#product_list > .goods-secrch-list").append(html);
            var _TimeCountDown = $(".fnTimeCountDown");
            _TimeCountDown.fnTimeCountDown();
           


            curpage++;

            if(e.data.groupbuy_goods.page < e.data.groupbuy_goods.total)
            {
                firstRow = e.data.groupbuy_goods.records;
                hasmore = true;
            }
            else
            {
                hasmore = false;
            }
        })
    }

}
function search_adv()
{
    window.location.href = WapSiteUrl + "/tmpl/group_buy_list.html" + '?groupbuy_type='+groupbuy_type+'&groupbuy_cat_id='+groupbuy_cat_id+'&price='+price+'&area_id='+area_id;

}
function init_get_list(type, value)
{
    if ( type == "order" ) {
        this.order = value;
    } else if ( type == "state" ) {
        this.state = value;
    }

    curpage = 1;
    firstRow = 10;
    hasmore = true;

    $("#product_list .goods-secrch-list").html("");
    $("#footer").removeClass("posa");
    get_list()
}

function init_rows(type, value ,obj)
{
    if ( type == "price" ) {
        this.price = value;
    } else if ( type == "area_id" ) {
        this.area_id = value;
    }
    $(obj).parents().children().removeClass("current");
    $(obj).toggleClass("current");
}

$(document).ready(function () {
    $(".swiper-container-nav .swiper-slide").click(function () {
        $(".swiper-container-nav .swiper-slide").removeClass("bor-2");
        $(this).addClass("bor-2");

    })
    var onoffcont = true;
    $(".righttubiao").click(function (e) {
        if (onoffcont) {
            // $(this).children("a").removeClass("bottG");
            // $(this).children("a").addClass("topg");
            $(".maskG").show();
            onoffcont = false;
        } else {
            // $(this).children("a").removeClass("topg");
            // $(this).children("a").addClass("bottG");
            $(".maskG").hide();
            onoffcont = true;
        }
    });

})
var page = pagesize;
var curpage = 1;
var hasMore = true;
var footer = false;
var reset = true;
var orderKey = "";
$(function () {
    var e = getCookie("key");
    if (!e) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html"
    }
    if (getQueryString("data-state") != "") {
        $("#filtrate_ul").find("li").has('a[data-state="' + getQueryString("data-state") + '"]').addClass("selected").siblings().removeClass("selected")
    }
    var t = false;
    var a = false;
    $("#search_btn").click(function () {
        reset = true;
        r()
    });
    $("#fixed_nav").waypoint(function () {
        $("#fixed_nav").toggleClass("fixed")
    }, {offset: "50"});
    function r() {
        if (reset) {
            curpage = 1;
            hasMore = true;
            $("#footer").html("")
        }
        $(".loading").remove();
        if (!hasMore) {
            return false
        }
        hasMore = false;
        var t = $("#filtrate_ul").find(".selected").find("a").attr("data-state");
        var a = $("#order_key").val();
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?ctl=Buyer_Order&met=virtual&typ=json&page=" + page + "&curpage=" + curpage,
            data: {k: e, u: getCookie('id'), status: t, keyword: a},
            dataType: "json",
            success: function (e) {
                checkLogin(e.login);
                curpage++;
                hasMore = e.hasmore;
                if (!hasMore) {
                    get_footer()
                }
                if (e.data.items.length <= 0) {
                    $("#footer").addClass("posa")
                }
                var t = e.data;console.info(t);
                t.WapSiteUrl = WapSiteUrl;
                t.ApiUrl = ApiUrl;
                t.key = getCookie("key");
                template.helper("$getLocalTime", function (e) {
                    var t = new Date(parseInt(e) * 1e3);
                    var a = "";
                    a += t.getFullYear() + "年";
                    a += t.getMonth() + 1 + "月";
                    a += t.getDate() + "日 ";
                    a += t.getHours() + ":";
                    a += t.getMinutes();
                    return a
                });
                template.helper("p2f", function (e) {
                    return (parseFloat(e) || 0).toFixed(2)
                });
                template.helper("parseInt", function (e) {
                    return parseInt(e)
                });
                var a = template.render("order-list-tmpl", t);
                if (reset) {
                    reset = false;
                    $("#order-list").html(a)
                } else {
                    $("#order-list").append(a)
                }
            }
        })
    }

    $.ajax({
        type: "get",
        url: ApiUrl + "/index.php?act=member_payment&op=payment_list",
        data: {k: e, u: getCookie('id')},
        dataType: "json",
        success: function (e) {
            var r = {};
            $.each(e && e.data && e.data.payment_list || [], function (e, t) {
                r[t] = true
            });
            var o = navigator.userAgent.match(/MicroMessenger\/(\d+)\./);
            if (parseInt(o && o[1] || 0) >= 5) {
                if (r.wxpay_jsapi) {
                    a = true
                }
            } else {
                if (r.alipay) {
                    t = true
                }
            }
        }
    });
    r(page, curpage);
    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {
            r()
        }
    });

    //取消订单
    $("#order-list").on("click", ".cancel-order", o);
    function o() {
        var e = $(this).attr("order_id");
        $.sDialog({
            content: "确定取消订单？", okFn: function () {
                i(e)
            }
        })
    }

    function i(t) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?ctl=Buyer_Order&met=orderCancel&typ=json",
            data: {order_id: t, k: e, u: getCookie('id'), user: 'buyer'},
            dataType: "json",
            success: function (e) {
                if (e.data && e.status == 200) {
                    //r(page, curpage)
                    window.location.reload();
                } else {
                    $.sDialog({skin: "red", content: e.data.error, okBtn: false, cancelBtn: false})
                }
            }
        })
    }
    //删除订单
    $("#order-list").on("click", ".delete-order", m);
    function m() {
        var e = $(this).attr("order_id");
        $.sDialog({
            content: "是否移除订单？<h6>电脑端订单回收站可找回订单！</h6>", okFn: function () {
                n(e)
            }
        })
    }

    function n(r) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?ctl=Buyer_Order&met=hideOrder&typ=json",
            data: {order_id: r, k: e, u: getCookie('id'), user: 'buyer'},
            dataType: "json",
            success: function (e) {
                if (e.status == 200) {
                    location.href = WapSiteUrl + "/tmpl/member/vr_order_list.html";
                } else {
                    $.sDialog({skin: "red", content: "操作失败！", okBtn: false, cancelBtn: false})
                }
            }
        })
    }

    $("#order-list").on("click", ".evaluation-order", function () {
        var e = $(this).attr("order_id");
        location.href = WapSiteUrl + "/tmpl/member/member_vr_evaluation.html?order_id=" + e
    });
    $("#filtrate_ul").find("a").click(function () {
        $("#filtrate_ul").find("li").removeClass("selected");
        $(this).parent().addClass("selected").siblings().removeClass("selected");
        reset = true;
        window.scrollTo(0, 0);
        r()
    })
});
function get_footer() {
    if (!footer) {
        footer = true;
        $.ajax({url: WapSiteUrl + "/js/tmpl/footer.js", dataType: "script"})
    }
}
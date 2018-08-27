var page = pagesize;
var curpage = 0;
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
    $("#search_btn").click(function () {
        reset = true;
        t()
    });
    $("#fixed_nav").waypoint(function () {
        $("#fixed_nav").toggleClass("fixed")
    }, {offset: "50"});
    function t() {
        if (reset) {
            curpage = 0;
            hasMore = true
        }
        $(".loading").remove();
        if (!hasMore) {
            return false
        }
        hasMore = false;
        var t = $("#filtrate_ul").find(".selected").find("a").attr("data-state");
        var r = $("#order_key").val();

        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?ctl=Buyer_Order&met=chain&typ=json&typ=json&firstRow=" + curpage,
            data: {k:e,u:getCookie("id"),status: t, orderkey: r},
            dataType: "json",
            success: function (e) {
                console.log(e);
                curpage = e.data.page * pagesize;
                if(page < e.data.totalsize)
                {
                    hasMore = true;
                }

                if (!hasMore) {
                    get_footer()
                }
                if (e.data.items.length <= 0) {
                    $("#footer").addClass("posa")
                } else {
                    $("#footer").removeClass("posa")
                }
                var chainOrderListHtml = template.render("chain-order-list-tmp", {chainOrderList:e.data.items});
                if (reset) {
                    reset = false;
                    $("#chain-order-list").html(chainOrderListHtml)
                } else {
                    $("#chain-order-list").append(chainOrderListHtml)
                }
            }
        })
    }

    $("#chain-order-list").on("click", ".view-evaluation", function(){
        var e = $(this).attr("order_id");
        location.href = WapSiteUrl + "/tmpl/member/member_evaluation_again.html?from=chain&order_id=" + e
    });
    $("#chain-order-list").on("click", ".evaluation-order", function(){
        var e = $(this).attr("order_id");
        location.href = WapSiteUrl + "/tmpl/member/member_evaluation.html?from=chain&order_id=" + e
    });
    $("#chain-order-list").on("click", ".check-payment", function () {
        var e = $(this).attr("data-paySn");
        toPay(e, "member_buy", "pay");
        return false
    });
    $("#chain-order-list").on("click", ".delete-order", function () {
        var e = $(this).attr("order_id");
        $.sDialog({
            content: "是否移除订单？<h6>电脑端订单回收站可找回订单！</h6>", okFn: function () {
                i(e)
            }
        })
        return false
    });
    function i(r) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?ctl=Buyer_Order&met=hideOrder&typ=json",
            data: {order_id: r, k: e, u: getCookie('id'), user: 'buyer'},
            dataType: "json",
            success: function (e) {
                if (e.status == 200) {
                    reset = true;
                    t()
                } else {
                    $.sDialog({skin: "red", content: "操作失败！", okBtn: false, cancelBtn: false})
                }
            }
        })
    }


    $("#filtrate_ul").find("a").click(function () {
        $("#filtrate_ul").find("li").removeClass("selected");
        $(this).parent().addClass("selected").siblings().removeClass("selected");
        reset = true;
        window.scrollTo(0, 0);
        t()
    });
    t();
    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {
            t()
        }
    })
});
function get_footer() {
    if (!footer) {
        footer = true;
        $.ajax({url: "../../js/tmpl/footer.js", dataType: "script"})
    }
}

window.payOrder = function(uo,o)
{
    //判断有没有支付单号，如果没有支付单号就去支付中心生成支付单号，如果有直接支付
    if(uo)
    {
        location.href = PayCenterWapUrl + "/?ctl=Info&met=pay&uorder=" + uo;
    }
    else
    {
        $.ajax({
            url: ApiUrl  + '?ctl=Buyer_Order&met=addUorder&typ=json',
            data:{order_id:o,k:key, u:getCookie('id')},
            dataType: "json",
            contentType: "application/json;charset=utf-8",
            async:false,
            success:function(a){
                console.info(a);
                if(a.status == 200)
                {
                    location.href = PayCenterWapUrl + "/?ctl=Info&met=pay&uorder=" + a.data.uorder;
                }
                else
                {
                    if(a.msg != 'failure')
                    {
                        /*Public.tips.error(a.msg);*/
                        $.sDialog({skin: "red", content: a.msg, okBtn: false, cancelBtn: false})
                    }else
                    {
                        $.sDialog({skin: "red", content: '订单支付失败！', okBtn: false, cancelBtn: false})
                        /*Public.tips.error('订单支付失败！');*/
                    }

                    //alert('订单提交失败');
                }
            },
            failure:function(a)
            {
                $.sDialog({skin: "red", content: '操作失败！', okBtn: false, cancelBtn: false})
                /*Public.tips.error('操作失败！');*/
            }
        });
    }
}

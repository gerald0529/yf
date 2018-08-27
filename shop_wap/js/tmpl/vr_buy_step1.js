var key = getCookie("key");
var u = getCookie("id");
var goods_id = getQueryString("goods_id");
var quantity = getQueryString("quantity");
var data = {};
data.k = key;
data.u = u;
data.goods_id = goods_id;
data.nums = quantity;
Number.prototype.toFixed = function (e) {
    var t = this + "";
    if (!e) {
        e = 0;
    }
    if (t.indexOf(".") == -1) {
        t += ".";
    }
    t += new Array(e + 1).join("0");
    if (new RegExp("^(-|\\+)?(\\d+(\\.\\d{0," + (e + 1) + "})?)\\d*$").test(t)) {
        var t = "0" + RegExp.$2, a = RegExp.$1, r = RegExp.$3.length, o = true;
        if (r == e + 2) {
            r = t.match(/\d/g);
            if (parseInt(r[r.length - 1]) > 4) {
                for (var n = r.length - 2; n >= 0; n--) {
                    r[n] = parseInt(r[n]) + 1;
                    if (r[n] == 10) {
                        r[n] = 0;
                        o = n != 1;
                    }
                    else {
                        break;
                    }
                }
            }
            t = r.join("").replace(new RegExp("(\\d+)(\\d{" + e + "})\\d$"), "$1.$2");
        }
        if (o) {
            t = t.substr(1);
        }
        return (a + t).replace(/\.$/, "");
    }
    return this + "";
};
var p2f = function (e) {
    return (parseFloat(e) || 0).toFixed(2);
};
$(function () {
    $.ajax({
        type: "post",
        url: ApiUrl + "/index.php?ctl=Buyer_Cart&met=confirmVirtual&typ=json",
        dataType: "json",
        data: data,
        success: function (e) {
            console.info(e);
            var t = e.data;
            if (typeof t.error != "undefined") {
                location.href = WapSiteUrl;
                return;
            }
            t.WapSiteUrl = WapSiteUrl;
            var a = template.render("goods_list", t);
            $("#deposit").html(a);
            $("#totalPrice").html(Number(t.goods_base.sumprice).toFixed(2));
            if (t.user_rate > 0) {
                var payprice = parseFloat(t.goods_base.sumprice * (t.user_rate / 100));
            }
            else {
                var payprice = t.goods_base.sumprice;
            }
            $("#totalPayPrice").html(payprice);
        }
    });
    //会员折扣是否开启
    $(document).on("click", "input[name='is_discount']", function () {
        var storeTotal = $("#storeTotal").data("storetotal");
        var totalPrice = $("#totalPrice").html();
        var discount_price = $("#discount_text").data("discount_price");
        if ($(this).is(":checked")) {
            $("#discount_text").show();
            $("#storeTotal").html("￥" + (Number(storeTotal) - Number(discount_price)).toFixed(2));
            $("#totalPrice").html((Number(storeTotal) - Number(discount_price)).toFixed(2));
        }
        else {
            $("#discount_text").hide();
            $("#storeTotal").html("￥" + Number(storeTotal).toFixed(2));
            $("#totalPrice").html(Number(storeTotal).toFixed(2));
        }
    });
    $("#ToBuyStep2").click(function () {
        var has_physical = $("#has_physical").val();
        var storeMessage = $("#storeMessage").val();
        if (typeof(has_physical) != "undefined" && has_physical == 1) {
            if (storeMessage == "") {
                $.sDialog({skin: "red", content: "请填写收货人信息", okBtn: false, cancelBtn: false});
                $("#storeMessage").focus();
                return false;
            }
        }
        var e = {};
        e.k = key;
        e.u = u;
        //商品信息
        e.goods_id = goods_id;
        e.goods_num = quantity;
        e.pay_way_id = 1;
        e.from = "wap";
        //是否开启会员折扣
        e.is_discount = $("input[name='is_discount']").is(":checked") ? 1 : 0;
        var t = $("#buyerPhone").val();
        if (!/^1[34578]\d{9}$/.test(t)) {
            $.sDialog({skin: "red", content: "请正确输入接收手机号码！", okBtn: false, cancelBtn: false});
            return false;
        }
        //手机号
        e.buyer_phone = t;
        //店铺留言
        e.remarks = storeMessage;
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?ctl=Buyer_Order&met=addVirtualOrder&typ=json",
            data: e,
            dataType: "json",
            success: function (e) {
                checkLogin(e.login);
                console.info(e);
                if (e.status == 250) {
                    $.sDialog({skin: "red", content: e.msg, okBtn: false, cancelBtn: false});
                    return false;
                }
                
                if (e.data.uorder) {
                    location.href = PayCenterWapUrl + "/?ctl=Info&met=pay&uorder=" + e.data.uorder + "&order_g_type=virtual";
                }
                /*if (e.datas.order_id)
                {
                    toPay(e.datas.order_sn, "member_vr_buy", "pay")
                }*/
                return false;
            }
        });
    });
});

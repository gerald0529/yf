$(function () {
    var e = getCookie("key");
    if (!e) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html";
        return
    }
    var order_id = getQueryString("order_id");
    var express_id = getQueryString("express_id");
    var express_name = decodeURIComponent(getQueryString("express_name"));
    var shipping_code = getQueryString("shipping_code");
    $.ajax({
        type: "post",
        url: ApiUrl + "/shop/api/logistic.php?typ=json",
        data: {key: e, order_id: order_id, express_id: express_id, shipping_code: shipping_code},
        dataType: "json",
        success: function (e) {
            checkLogin(e.login);
            var r = {};
            r.deliver_info = e && e.data;
            if (!r.deliver_info) {
                r = {};
                r.err = "暂无物流信息"
            } else {
                r.express_name = express_name;
                r.shipping_code = shipping_code;
            }
            var t = template.render("order-delivery-tmpl", r);
            $("#order-delivery").html(t)
        }
    })
});
/**
 * Created by rd04 on 2016/11/14.
 */

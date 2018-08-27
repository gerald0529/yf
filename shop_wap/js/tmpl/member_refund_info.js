$(function () {
    var e = getCookie("key");
    var u = getCookie("id");

    var r = getQueryString("refund_id");
    
    template.helper("isEmpty", function (e) {
        for (var r in e) {
            return false
        }
        return true
    });
    $.getJSON(ApiUrl + "/index.php?ctl=Buyer_Service_Return&met=index&act=detail&typ=json", {k: e,u:u, id: r}, function (e) {
        $("#refund-info-div").html(template.render("refund-info-script", e.data))
    })
});
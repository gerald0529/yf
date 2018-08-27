$(function () {
    var e = getCookie("key");
    var u = getCookie("id");
    var t = new ncScrollLoad;
    t.loadInit({
        url: ApiUrl + "/index.php?ctl=Buyer_Service_Return&met=index&typ=json&state=3",
        getparam: {k: e,u:u},
        tmplid: "refund-list-tmpl",
        containerobj: $("#refund-list"),
        iIntervalId: true,
        data: {WapSiteUrl: WapSiteUrl}
    })
});

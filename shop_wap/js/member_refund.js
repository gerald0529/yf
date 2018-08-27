$(function ()
{
    var e = getCookie("key");
    var t = new ncScrollLoad;
    t.loadInit({
        url: ApiUrl + "/index.php?act=member_refund&op=get_refund_list",
        getparam: {k: e, u: getCookie('id')},
        tmplid: "refund-list-tmpl",
        containerobj: $("#refund-list"),
        iIntervalId: true,
        data: {WapSiteUrl: WapSiteUrl}
    })
});
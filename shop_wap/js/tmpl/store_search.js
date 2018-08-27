$(function () {
    var t = getQueryString("shop_id");
    $("#goods_search_all").attr("href", WapSiteUrl + "/tmpl/store_goods.html?shop_id=" + t);
    $("#search_btn").click(function () {
        var e = $("#search_keyword").val();
        // if (e != "") {
            window.location.href = WapSiteUrl + "/tmpl/store_goods.html?shop_id=" + t + "&keyword=" + encodeURIComponent(e)
        // }
    });
    $.ajax({
        type: "post",
        url: ApiUrl + "/index.php?ctl=Goods_Goods&met=getShopCat&shop_id=1&typ=json",
        data: {shop_id: t},
        dataType: "json",
        success: function (t) {
            var e = t.data;
            var o = e.shop_name + " - 店内搜索";
            document.title = o;
            var r = template.render("store_category_tpl", e);
            $("#store_category").html(r)
        }
    })
})
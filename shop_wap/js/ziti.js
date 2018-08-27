var goods_id = getQueryString("goods_id");
var shop_id = getQueryString("shop_id");
var goods_num = getQueryString("goods_num");

$(function () {
    var physicalStore;
    getPhysicalStoreList();

    //获取门店列表
    function getPhysicalStoreList() {
        $.ajax({
            url: ApiUrl + "/index.php?ctl=Goods_Goods&met=chain&typ=json",
            type: "get",
            data: {goods_id: goods_id,shop_id:shop_id,goods_num:goods_num},
            dataType: "json",
            success: function (data) {
                if (data.status == 200) {
                    physicalStore = data.data;
                    if (physicalStore.chain_rows.length > 0) {
                        var chainListHtml = template.render('chainList', physicalStore);
                        $("#chain-list").append(chainListHtml);
                        $('.js-none').hide();
                    }
                }
            }
        });
    };

    //重新渲染页面
    function renderPhysicalStoreList(provinceId, cityId) {
        $("#chain-list").empty();
        if (!physicalStore) { //没有获取到数据
            return false;
        }
        var chainRows = [];

        for(var i=0; i<physicalStore.chain_rows.length; i++) {
            var chain = physicalStore.chain_rows[i];
            if (chain.chain_province_id == provinceId && chain.chain_city_id == cityId) {
                chainRows.push(chain);
            }
        }
        if (chainRows.length > 0) {
            var chainListHtml = template.render('chainList', { chain_rows: chainRows });
            $("#chain-list").append(chainListHtml);
            $('.js-none').hide();
        } else {
            $('.js-none').show();
        }
    }
    
    $("#area_info").on("click", function ()
    {
        var $this = $(this);
        $.areaSelected({
            hideThirdLevel: true,
            success: function (a) {
                $("#area_info").attr({
                    "data-areaid": a.area_id,
                    "data-areaid1": a.area_id_1,
                    "data-areaid2": a.area_id_2,
                    "data-areaid3": a.area_id_3
                });
                $("#area").val(a.area_info);
                renderPhysicalStoreList(a.area_id_1, a.area_id_2);
            }
        })
    })
});

function confirm(chain_id)
{
    window.location.href = WapSiteUrl + '/tmpl/ziti_confirm.html?goods_id=' + goods_id + '&chain_id=' + chain_id;
}









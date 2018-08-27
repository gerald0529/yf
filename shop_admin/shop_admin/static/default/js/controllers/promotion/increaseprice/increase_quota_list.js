/**
 * Created by Administrator on 2016/5/29.
 */
function initEvent()
{
    //搜索
    $_shop_name = $("#shop_name"),
    $_shop_name.placeholder(),
    $("#search").on("click", function (a)
    {
        a.preventDefault();
        var search_name =  $.trim($_shop_name.val());
        $("#grid").jqGrid("setGridParam", {page: 1, postData: {shop_name:search_name}}).trigger("reloadGrid")
    });

    //刷新
    $("#btn-refresh").click(function (t)
    {
        t.preventDefault();
        $_shop_name.val('');
        $("#grid").jqGrid('setGridParam',{postData: ''}).trigger("reloadGrid")
    });

    //跳转到店铺认证信息页面
    $('#grid').on('click', '.to-shop', function(e) {
        e.stopPropagation();
        e.preventDefault();
        var shop_id = $(this).attr('data-id');
        $.dialog({
            title: '查看店铺信息',
            content: "url:"+SITE_URL + '?ctl=Shop_Manage&met=getShoplist&shop_id=' + shop_id,
            width: 1000,
            height: $(window).height(),
            max: !1,
            min: !1,
            cache: !1,
            lock: !0
        })
    });

    $(window).resize(function ()
    {
        Public.resizeGrid()
    })
}
function initGrid()
{
    var t = ["店铺名称", "开始时间","结束时间"],
        e = [
            {name: "shop_name", index: "shop_name", align: "center",width: 200,"formatter":linkShopFormatter},
            {name: "combo_start_time", index: "combo_start_time",align: "center",width:150},
            {name: "combo_end_time", index: "combo_end_time",align: "center",width: 150}
        ];

    $("#grid").jqGrid({
        url: SITE_URL + '?ctl=Promotion_ActIncrease&met=getIncreaseComboList&typ=json',
        datatype: "json",
        height: Public.setGrid().h,
        colNames: t,
        colModel: e,
        autowidth: !0,
        pager: "#page",
        viewrecords: !0,
        cmTemplate: {sortable: !1, title: !1},
        page: 1,
        rowNum: 100,
        rowList: [100, 200, 500],
        shrinkToFit: !1,
        jsonReader: {root: "data.items", records: "data.records", total: "data.total", repeatitems: !1, id: "combo_id"},
        loadComplete: function (t)
        {
            if (t && 200 == t.status)
            {
                var e = {};
                t = t.data;
                for (var i = 0; i < t.items.length; i++)
                {
                    var a = t.items[i];
                    e[a.combo_id] = a;
                }
                $("#grid").data("gridData", e);

                0 == t.items.length && parent.Public.tips({type: 2, content: "没有类型数据！"})
            }
            else
            {
                parent.Public.tips({type: 2, content: "获取类型数据失败！" + t.msg})
            }
        },
        loadError: function ()
        {
            parent.Public.tips({type: 1, content: "操作失败了哦，请检查您的网络链接！"})
        }
    })
}

function linkShopFormatter(val, opt, row) {
    return '<a href="javascript:void(0)"><span class="to-shop" data-id="' + row.shop_id + '">' + val + '</span></a>';
}

function operFmatter (val, opt, row)
{
    var html_con = '<div class="operating" data-id="' + row.combo_id + '">--</div>';
    return html_con;
};
initEvent();
initGrid();
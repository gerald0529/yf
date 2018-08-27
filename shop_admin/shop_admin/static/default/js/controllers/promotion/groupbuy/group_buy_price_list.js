/**
 * Created by Administrator on 2016/5/23.
 */

function initEvent()
{
    $("#btn-add").click(function (t)
    {
        t.preventDefault();
        handle.operate("add");
    });

    $("#btn-refresh").click(function (t)
    {
        t.preventDefault();
        $("#grid").trigger("reloadGrid")
    });

    $("#grid").on("click", ".operating .ui-icon-pencil", function (t)
    {
        t.preventDefault();
        var e = $(this).parent().data("id");
        handle.operate("edit", e)
    });

    $("#grid").on("click", ".operating .ui-icon-trash", function (t)
    {
        t.preventDefault();
        var e = $(this).parent().data("id");
        handle.del(e)
    });

    $(window).resize(function ()
    {
        Public.resizeGrid()
    })
}
function initGrid()
{
    var t = ["操作", "价格区间名称", "价格区间下限","价格区间上限"],
        e = [{
            name: "operate",
            width: 60,
            fixed: !0,
            align: "center",
            formatter: operFmatter
        },
            {name: "range_name", index: "range_name", align: "center",width: 100},
            {name: "range_start", index: "range_start",align: "center",width: 100},
            {name: "range_end", index: "range_end",align: "center",width: 100}
        ];

    $("#grid").jqGrid({
        url: SITE_URL + '?ctl=Promotion_GroupBuy&met=getPriceRangeList&typ=json',
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
        jsonReader: {root: "data.items", records: "data.records", total: "data.total", repeatitems: !1, id: "range_id"},
        loadComplete: function (t)
        {
            if (t && 200 == t.status)
            {
                var e = {};
                t = t.data;
                for (var i = 0; i < t.items.length; i++)
                {
                    var a = t.items[i];
                    e[a.range_id] = a;
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
function operFmatter (val, opt, row)
{
    var html_con = '<div class="operating" data-id="' + row.range_id + '"><span class="ui-icon ui-icon-pencil" title="编辑"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
    return html_con;
};

var handle = {
    operate: function (t, e)
    {
        if ("add" == t)
        {
            var i = "团购管理 - 新增团购价格区间", a = {oper: t, callback: this.callback};
        }
        else
        {
            var i = "团购管理 - 编辑团购价格区间", a = {oper: t, rowData: $("#grid").data("gridData")[e], callback: this.callback};
        }
        $.dialog({
            title: i,
            content: "url:"+SITE_URL + '?ctl=Promotion_GroupBuy&met=priceRangeManage',
            data: a,
            width: 588,
            height: 300,
            max: !1,
            min: !1,
            cache: !1,
            lock: !0
        })
    }, callback: function (t, e, i)
    {
        var a = $("#grid").data("gridData");
        if (!a)
        {
            a = {};
            $("#grid").data("gridData", a)
        }
        a[t.range_id] = t;
        if ("edit" == e)
        {
            $("#grid").jqGrid("setRowData", t.range_id, t);
            i && i.api.close()
        }
        else
        {
            $("#grid").jqGrid("addRowData", t.range_id, t, "last");
            i && i.api.close()
        }
    }, del: function (t)
    {
        $.dialog.confirm("删除的数据将不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost(SITE_URL + '?ctl=Promotion_GroupBuy&met=removePriceRange&typ=json', {range_id: t}, function (e)
            {
                //alert(JSON.stringify(e));
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "价格区间删除成功！"});
                    $("#grid").jqGrid("delRowData", t)
                }
                else
                {
                    parent.Public.tips({type: 1, content: "价格区间删除失败！" + e.msg})
                }
            })
        })
    }
};
initEvent();
initGrid();
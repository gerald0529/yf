
function initEvent()
{
    $("#btn-add").click(function (t)
    {
        t.preventDefault();
        Business.verifyRight("INVLOCTION_ADD") && handle.operate("add")
    });

    $("#btn-refresh").click(function (t)
    {
        t.preventDefault();
        $("#grid").trigger("reloadGrid")
    });

    $("#grid").on("click", ".operating .ui-icon-pencil", function (t)
    {
        t.preventDefault();
        if (Business.verifyRight("INVLOCTION_UPDATE"))
        {
            var e = $(this).parent().data("id");
            handle.operate("edit", e)
        }
    });

    $("#grid").on("click", ".operating .ui-icon-trash", function (t)
    {
        t.preventDefault();
        if (Business.verifyRight("INVLOCTION_DELETE"))
        {
            var e = $(this).parent().data("id");
            handle.del(e)
        }
    });

    $(window).resize(function ()
    {
        Public.resizeGrid()
    })
}
function initGrid()
{
    var t = ["操作", "排序", "标题", "链接","显示位置", "新窗口打开", "是否启用"], e = [{
        name: "operate",
        width: 100,
        fixed: !0,
        align: "center",
        formatter: Public.operFmatter
    },
        {name: "nav_displayorder", index: "nav_displayorder", align: "center",width: 100},
        {name: "nav_title", index: "nav_title", width:200,align:"center"},
        {name: "nav_url", index: "nav_url", width: 450},
        {name: "nav_location_name", index: "nav_location_name",align: "center", width: 100},
        {name: "nav_new_open_name", index: "nav_new_open_name",align: "center", width:100},
        {name: "nav_active_name", index: "nav_active_name", align: "center", width:100}
    ];

    $("#grid").jqGrid({
        url: SITE_URL + "?ctl=Platform_Nav&met=platformNavList&typ=json&isDelete=2",
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
        jsonReader: {root: "data.items", records: "data.records", total: "data.total", repeatitems: !1, id: "nav_id"},
        loadComplete: function (t)
        {
            if (t && 200 == t.status)
            {
                var e = {};
                t = t.data;
                for (var i = 0; i < t.items.length; i++)
                {
                    var a = t.items[i];
                    e[a.nav_id] = a;
                }
                $("#grid").data("gridData", e);
                0 == t.items.length && parent.Public.tips({type: 2, content: "没有分类数据！"})
            }
            else
            {
                parent.Public.tips({type: 2, content: "获取分类数据失败！" + t.msg})
            }
        },
        loadError: function ()
        {
            parent.Public.tips({type: 1, content: "操作失败了哦，请检查您的网络链接！"})
        }
    })
}

var handle = {
    operate: function (t, e)
    {
        if (t=="add")
        {
            var i = "新增导航", a = {oper: t, callback: this.callback};
        }
        else
        {
            var i = "修改导航", a = {oper: t, rowData: $("#grid").data("gridData")[e], callback: this.callback};
        }
        $.dialog({
            title: i,
            content: 'url:'+SITE_URL + "?ctl=Platform_Nav&met=manage",
            // width: $(window).width() * 0.4,
            // height: $(window).height() * 0.5,
            width:620,
            height:430,
            data: a,
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
            $("#grid").data("gridData", a);
        }
        a[t.id] = t;
        if ("edit" == e)
        {
            $("#grid").jqGrid("setRowData", t.id, t);
            i && i.api.close()
            $("#grid").trigger("reloadGrid");
        }
        else
        {
            $("#grid").jqGrid("addRowData", t.id, t, "last");
            i && i.api.close()
            $("#grid").trigger("reloadGrid");
        }
    }, del: function (t)
    {
        $.dialog.confirm("删除的分类将不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost(SITE_URL + "?ctl=Platform_Nav&met=removeNav&typ=json", {nav_id: t}, function (e)
            {
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "分类删除成功！"});
                    $("#grid").jqGrid("delRowData", t)
                }
                else
                {
                    parent.Public.tips({type: 1, content: "分类删除失败！" + e.msg})
                }
            })
        })
    }
};
initEvent();
initGrid();